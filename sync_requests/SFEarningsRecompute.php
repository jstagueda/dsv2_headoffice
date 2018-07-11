<?php 

class SFEarningRecompute 
{
	
	public function __construct()
	{
		global $database;
		$this->_database = $database;
	}
	
	public function doSFEarningsRecompute($IBMID,$CampaignCode,$CampaignMonth,$CampaignYear,$BranchID)
	{
		//1st step get the bracket of TotalDGSPayment..
		//$Date = date("Y-m-d");
		//$CampaignMonth = date("n",strtotime($Date)); //Campaign Month..
		//$CampaignYear  = date("y",strtotime($Date)); //Campaign Year..
		//echo ":_GetTotalDGSPaymentPerPMG(1,$CampaignMonth,$CampaignYear,$IBMID,$BranchID)";
			$TotalDGSPaymentCFT = self::_GetTotalDGSPaymentPerPMG(1,$CampaignMonth,$CampaignYear,$IBMID,$BranchID);
			$TotalDGSPaymentNCFT = self::_GetTotalDGSPaymentPerPMG(2,$CampaignMonth,$CampaignYear,$IBMID,$BranchID);
			
		
			$CommissionAmount  = $TotalDGSPaymentCFT + $TotalDGSPaymentNCFT; //Total Commission..
			
			//get Tax Amount
			$tax = 10.00 / 100; // TAX
			$CFTTaxAmount = $TotalDGSPaymentCFT * $tax;   // total CFT tax amount...
			$NCFTTaxAmount = $TotalDGSPaymentNCFT * $tax; // total NCFT tax amount...		
			$TaxAmount = $CFTTaxAmount + $NCFTTaxAmount; //Total Tax Amount...
			
			
			
			$vat = 12.00 / 100; // VAT
			$CFTVAT		= $TotalDGSPaymentCFT  * $vat; // total CFT vat amount..
			$NCFTVAT    = $TotalDGSPaymentNCFT * $vat; // total NCFT vat amount..
			$VatAmount = $CFTVAT + $NCFTVAT; //Total Vat..
			
			// get Earned Commission = (Commission Amount + Vat Amount - Tax Amount)...
			$Earned_CommissionCFT  = $TotalDGSPaymentCFT 	+ $CFTVAT 	 - $CFTTaxAmount;     // total Earned Commission CFT  amount...
			$Earned_CommissionNCFT = $TotalDGSPaymentNCFT 	+ $NCFTVAT	 - $NCFTTaxAmount;   // total Earned Commission NCFT amount...
			$EarnedCommission = $Earned_CommissionCFT + $Earned_CommissionNCFT; // Total Earned Commission..
			// $EarnedCommission = $TotalDGSPaymentCFT + $TotalDGSPaymentNCFT; // Total Earned Commission..
			$VatAmount = $CFTVAT + $NCFTVAT; //Total Vat..
			
			//update here...
			//$BranchID			= getgBranchID(); 	 //return BranchID..
			$IBM	 			= $IBMID; 			 //IBMID CustomerID..
			$CommissionTypeID 	= 1; 				 //SF Earnings..
			$EarnedBonusAmount  = $EarnedCommission; // Earned Bonus Amount..
			$OutstandingBalance = $EarnedCommission; // Earned OutStanding Balance..
			$VAT				= $VatAmount; 		 // VAT..
			$WithHoldingTax 	= $TaxAmount;
			
			
			
			
			self::doUpdateCustomerCommissionAndCustomerCommissionSummary($BranchID, $IBM, $CampaignMonth, $CampaignYear, $CommissionTypeID,$EarnedBonusAmount, $OutstandingBalance, $VAT, $WithHoldingTax);
			
			return "success";
	}
	
	public function _GetTotalDGSPaymentPerPMG($PMGID,$CampaignMonth,$CampaignYear,$IBMID,$BranchID,$isRF=false)
	{
		if($PMGID==1){
			$PMGIDType = "CFT";
		}else{
			$PMGIDType = "NCFT";
		}
		

		$query = "select TotalDGSPayment from tpi_sfasummary_pmg where PMGType =  '".$PMGIDType."' AND CustomerID=".$IBMID." AND IBMID=".$IBMID."
				  AND CampaignMonth=".$CampaignMonth."  and DATE_FORMAT(CONCAT(CampaignYear,'-1-1'),'%y') = ".$CampaignYear." AND 
				  SPLIT_STR(HOGeneralID,'-',2)=".$BranchID;
		
		$q = $this->_database->execute($query);
		if($q->num_rows):
			while($r=$q->fetch_object()):
				$TotalDGSPayment = $r->TotalDGSPayment;
			endwhile;
		else:
			$TotalDGSPayment = 0;
		endif;
		
		//Get Discount...
		$GetDiscount = self::_GetDiscountServiceFeeCommission($TotalDGSPayment,$PMGID);
		
		if($GetDiscount->num_rows):
		   while($r = $GetDiscount->fetch_object()): 
				$Discount = $r->Discount; 
			endwhile;
        else: 
				$Discount = 0; 
		endif;
		 
		//to get discount will divide to 100..
        $DiscountPercent = $Discount / 100;
		$CommissionAmountPerPMG = $TotalDGSPayment * $DiscountPercent;
		return $CommissionAmountPerPMG;
	}
	
	function _GetDiscountServiceFeeCommission($ServiceFeeCommission,$PMGID)
	{
		$query = $this->_database->execute("SELECT Discount, Minimum, Maximum,PMGID FROM servicefeecommission WHERE Minimum <= ".$ServiceFeeCommission."
								AND maximum >= ".$ServiceFeeCommission." AND PMGID = ".$PMGID);
		return $query;
	}
	
	function doUpdateCustomerCommissionAndCustomerCommissionSummary($BranchID, $IBM, $CampaignMonth, $CampaignYear, $CommissionTypeID,$EarnedBonusAmount, $OutstandingBalance, $VAT, $WithHoldingTax)
	{





		
		$xchecker = $this->_database->execute("	SELECT * FROM customercommission
												WHERE CustomerID = ".$IBM." AND CampaignMonth = ".$CampaignMonth." 
												AND CommissionTypeID = ".$CommissionTypeID."
												AND DATE_FORMAT(CONCAT(CampaignYear,'-1-1'),'%y') = ".$CampaignYear." 
												AND SPLIT_STR(HOGeneralID, '-', 2) = ".$BranchID);
		
		
		
		
		if($xchecker->num_rows > 0){
			while($r = $xchecker->fetch_object()){
				$HOGeneralID = $r->HOGeneralID; // ID and BranchID
				$CommissionID=$r->ID; // Commission ID
				$EarnedBonusAmount; // Earned Amount
				$BranchID; // BranchID
				
				$this->_database->execute("	UPDATE customercommission set EarnedBonusAmount = ".$EarnedBonusAmount.", OutstandingBalance= ".$OutstandingBalance.", 
											VAT = ".$VAT.", WithHoldingTax= ".$WithHoldingTax.", 
											Changed = 1, LastModifiedDate = NOW() 
											WHERE HOGeneralID = '".$HOGeneralID."'
											-- CustomerID = ".$IBM." and 
											-- CampaignMonth = ".$CampaignMonth." and CommissionTypeID = ".$CommissionTypeID."
											-- AND DATE_FORMAT(CONCAT(CampaignYear,'-1-1'),'%y') = ".$CampaignYear." 
											-- AND SPLIT_STR(HOGeneralID, '-', 2) = ".$BranchID);
//				$query = $this->_database->execute("select * from customercommission WHERE 
//													CustomerID = ".$IBM." and CampaignMonth = ".$CampaignMonth." and CommissionTypeID = ".$CommissionTypeID."
//													AND DATE_FORMAT(CONCAT(CampaignYear,'-1-1'),'%y') = ".$CampaignYear." AND SPLIT_STR(HOGeneralID, '-', 2) = ".$BranchID."");
				
				self::_doUpdateCustomerCommissionSummary($CommissionID,$EarnedBonusAmount,$BranchID);
			}
		}else{
				$custid= $IBM;
				$xID = $this->_database->execute("	SELECT IFNULL(MAX(ID) + 1,1) ID FROM (
														SELECT CAST(SPLIT_STR(HOGeneralID,'-',1) AS UNSIGNED) ID 
														FROM customercommission WHERE SPLIT_STR(HOGeneralID,'-',2)=".$BranchID." 
													) a"
												);			
				$rfID = $xID->fetch_object()->ID; 
				$xcampaign = date("Y",strtotime($CampaignYear."-01-01"));
				$CampaignMonth = $CampaignMonth;
				$CommissionTypeID;
				
				$this->_database->execute("INSERT INTO customercommission (HoGeneralID,ID,BranchID, CustomerID, CampaignMonth, CampaignYear, CommissionTypeID, EarnedBonusAmount, OutstandingBalance, 
										   VAT, WithHoldingTax, EnrollmentDate, LastModifiedDate, Changed) VALUES
										   ('".$rfID."-".$BranchID."',".$rfID.",".$BranchID.",".$custid.",".$CampaignMonth.",".$xcampaign.",".$CommissionTypeID.",".$EarnedBonusAmount.", ".$EarnedBonusAmount.",
										   0, 0,NOW(),NOW(),1)");
		}
		
	}
	public function _doUpdateCustomerCommissionSummary($CommissionID,$EarnedBonusAmount,$BranchID)
	{
		$query = $this->_database->execute("SELECT HoGeneralID,OffsetAmount FROM customercommission_summary WHERE CustomerCommissionID=".$CommissionID." and  SPLIT_STR(HOGeneralID, '-', 2) = ".$BranchID." ORDER BY ID ASC");
		$amount = $EarnedBonusAmount;
		if($query->num_rows):
			while($r = $query->fetch_object()):
				
				$ID = $r->HoGeneralID;
				$EarnedAmount  = $amount;
				$BalanceAmount =  $amount - $r->OffsetAmount;
				$this->_database->execute("UPDATE customercommission_summary SET EarnedAmount=".$EarnedAmount." , BalanceAmount = ".$BalanceAmount." WHERE HoGeneralID='".$ID."'");
				
				$amount = $BalanceAmount;
			endwhile;
			$this->_database->execute("UPDATE customercommission set EarnedBonusAmount = ".$BalanceAmount." where HoGeneralID='".$CommissionID."-".$BranchID."'");
		endif;
	}
	public function getgBranchID()
	{
		//get branchid on branchparameter table..
		$q = $this->_database->execute("select BranchID from branchparameter");
		while($r=$q->fetch_object()){
				$BranchID = $r->BranchID; //Branch ID...
		}
		return $BranchID;
	}
	
	public function DoRFEarningsRecompute($BranchID)
	{
		$EarnedBonusAmountByRF = 0;
		$qqq = $this->_database->execute("SELECT a.* FROM sfm_manager_networks a
										 INNER JOIN customer b ON b.Code=a.manager_code 
										 AND SPLIT_STR(b.HoGeneralID,'-',2)=SPLIT_STR(a.HoGeneralID,'-',2)
										 WHERE split_str(b.HoGeneralID,'-',2) = ".$BranchID."
										 -- GROUP BY manager_code 
										 ORDER BY manager_code ASC");
		if($qqq->num_rows > 0){

			while($r = $qqq->fetch_object()){
				$IBMCODE = $r->manager_code;
				$manager_network_code = $r->manager_network_code;
				//get all networks..
				$ibm_networks = $this->_database->execute("	SELECT c.ID FROM sfm_manager_networks a
															INNER JOIN customer c on c.Code=a.manager_network_code 
															AND SPLIT_STR(c.HoGeneralID,'-',2)=SPLIT_STR(a.HoGeneralID,'-',2)
															WHERE split_str(c.HoGeneralID,'-',2) = ".$BranchID." and c.Code = '".$manager_network_code."' 
															 AND a.manager_code <> '".$manager_network_code."' 
															-- GROUP BY manager_code 
															ORDER BY a.manager_network_code ASC");
											
				if($ibm_networks > 0){
					while($q = $ibm_networks->fetch_object()){
						$NetworkID = $q->ID;
						//get history in tpi_sfasummary table..
						
						$campaign = $this->_database->execute("SELECT CampaignMonth,CampaignYear FROM tpi_sfasummary 
															   WHERE CampaignMonth <> 0 AND CampaignYear <> 0 AND 
															   SPLIT_STR(HoGeneralID,'-',2) = ".$BranchID."
															   GROUP BY CampaignCode
															   ORDER BY CampaignMonth,CampaignYear");
						if($campaign->num_rows > 0){
						
							while($cmp = $campaign->fetch_object()){
								$CampaignMonth 		= $cmp->CampaignMonth;
								$CampaignYear 		= $cmp->CampaignYear;
								$tpi_sfasummary = $this->_database->execute("SELECT * FROM tpi_sfasummary 
																			WHERE SPLIT_STR(HOGeneralID,'-',2)=".$BranchID." 
																			AND CustomerID = ".$NetworkID." AND IBMID = ".$NetworkID." AND
																			CampaignMonth = ".$CampaignMonth." and CampaignYear = ".$CampaignYear."
																			");
																			
								if($tpi_sfasummary > 0){
									while($history_table=$tpi_sfasummary->fetch_object()){
										$CustomerID 		= $history_table->CustomerID;
										$LevelID			= $history_table->LevelID;
										$TotalDGSSales 		= $history_table->TotalDGSSales;
										$TotalDGSPayment 	= $history_table->TotalDGSPayment;

										$rfrate = $this->_database->execute("SELECT Discount, Minimum, Maximum FROM rfcommissionfee WHERE Minimum <= ".$TotalDGSSales."
																		AND maximum >= ".$TotalDGSSales." AND SFLevelID = ".$LevelID);
																		
										if($rfrate->num_rows > 0){
											while($rf = $rfrate->fetch_object()){
												$rfDiscount = $rf->Discount;
												$TotalDGSPayment;
												$EarnedBonus = $TotalDGSPayment * $rfDiscount;
												$EarnedBonusAmountByRF += $EarnedBonus;
											}
										}
									}
									
									$ifRf = $this->_database->execute("	SELECT a.* FROM customercommission a
																		INNER JOIN customer b on a.CustomerID=b.ID
																		WHERE b.Code = ".$IBMCODE." and 
																		a.CampaignMonth = ".$CampaignMonth." and a.CommissionTypeID = 2
																		AND DATE_FORMAT(CONCAT(a.CampaignYear,'-1-1'),'%y') = ".$CampaignYear." 
																		AND SPLIT_STR(a.HOGeneralID, '-', 2) = ".$BranchID);
																		
								
									if($ifRf->num_rows > 0){
										while($rfr = $ifRf->fetch_object()){
										
											$rfID = $rfr->ID;
											$IBMID = $rfr->CustomerID;
											$CommissionTypeID = 2;
											$rfEarnedBonusAmount = $rfr->EarnedBonusAmount; 
											$rfOutStandingBalance = $rfr->OutStandingBalance;
											
											
											
											$this->_database->execute("	UPDATE customercommission set EarnedBonusAmount = ".$EarnedBonusAmountByRF.", OutstandingBalance= ".$EarnedBonusAmountByRF."
																		where ID = ".$rfID." and SPLIT_STR(HOGeneralID, '-', 2) = ".$BranchID);
											//UPDATE HERE.. USING LOGS
											self::_doUpdateCustomerCommissionSummary($rfID,$EarnedBonusAmountByRF,$BranchID);
										}
									}else{
										$uifcust = $this->_database->execute("select * from customer where Code='".$IBMCODE."'");
										if($uifcust->num_rows > 0 ){
											$custid= $uifcust->fetch_object()->ID;
											// $xID = $this->_database->execute("SELECT IFNULL(MAX(ID) + 1,1) ID FROM customercommission"); 
											$xID = $this->_database->execute("SELECT IFNULL(MAX(ID) + 1,1) ID FROM (SELECT CAST(SPLIT_STR(HOGeneralID,'-',1) AS UNSIGNED) ID 
																			  FROM customercommission WHERE SPLIT_STR(HOGeneralID,'-',2)=".$BranchID." ) a");					  
											$rfID = $xID->fetch_object()->ID; 
											$xcampaign = date("Y",strtotime($CampaignYear."-01-01"));
											$CampaignMonth = $CampaignMonth;
											$CommissionTypeID = 2;
											$this->_database->execute("INSERT INTO customercommission (HoGeneralID,ID,BranchID, CustomerID, CampaignMonth, CampaignYear, CommissionTypeID, EarnedBonusAmount, OutstandingBalance, 
											VAT, WithHoldingTax, EnrollmentDate, LastModifiedDate, Changed) VALUES
											('".$rfID."-".$BranchID."',".$rfID.",".$BranchID.",".$custid.",".$CampaignMonth.",".$xcampaign.",".$CommissionTypeID.",".$EarnedBonusAmountByRF.", ".$EarnedBonusAmountByRF.",
											0, 0,NOW(),NOW(),1)");
										}	
									}
									$EarnedBonusAmountByRF = 0;
								}
							}
						}
					}
				}
				
			}
		}
		
		$result['type'] = 'success';
		$result['message'] = "RF recomputation successful";		
		return $result;
	}
	
	public function DoCandidacyEarningsRecompute($BranchID)
	{
		$Candidate = $this->_database->execute("	SELECT * FROM tpi_sfasummary WHERE CustomerID IN (
											SELECT ID FROM customer WHERE CustomerTypeID = (SELECT codeID FROM sfm_level WHERE desc_code='IBMC')
											AND SPLIT_STR(HOGeneralID,'-',2)=".$BranchID." ) 
											AND LevelID = (SELECT codeID FROM sfm_level WHERE desc_code='IBMC')");
		
		if($Candidate->num_rows > 0){
			while($r=$Candidate->fetch_object()){
				$CustomerID 		= $r->CustomerID;
				$CampaignMonth 		= $r->CampaignMonth;
				$CampaignYear 		= $r->CampaignYear;
				$TotalDGSSales 		= $r->TotalDGSSales;
				$CommissionTypeID 	= 3;
				//3. Calculate Candidacy Bonus by multiplying 10% Rate of the total amount in step#2
				$CalculateEarnings  = ($TotalDGSSales * .10);
				//4.	Store in customer commission table 
				$checker = $this->_database->execute("select * from customercommission WHERE CustomerID = ".$CustomerID." 
													  AND CampaignMonth = ".$CampaignMonth." AND CommissionTypeID = ".$CommissionTypeID."
													  AND DATE_FORMAT(CONCAT(CampaignYear,'-1-1'),'%y') = ".$CampaignYear." 
													  AND SPLIT_STR(HOGeneralID, '-', 2) = ".$BranchID);
				if($checker->num_rows > 0){
				
						while($rfr = $checker->fetch_object()){
							$rfID = $rfr->ID;
							$IBMID = $rfr->CustomerID;
							$rfEarnedBonusAmount = $rfr->EarnedBonusAmount; 
							$rfOutStandingBalance = $rfr->OutStandingBalance;
							$this->_database->execute("	UPDATE customercommission set EarnedBonusAmount = ".$CalculateEarnings.", OutstandingBalance= ".$CalculateEarnings."
														where ID = ".$rfID." and SPLIT_STR(HOGeneralID, '-', 2) = ".$BranchID);
							//UPDATE HERE.. USING LOGS
							self::_doUpdateCustomerCommissionSummary($rfID,$CalculateEarnings,$BranchID);
						}
					

				}else{
					$xID = $this->_database->execute("SELECT MAX(ID) + 1 ID FROM customercommission"); 
					$rfID = $xID->fetch_object()->ID; 
					$xcampaign = date("Y",strtotime($CampaignYear."-01-01"));
					$CampaignMonth = $CampaignMonth;
					$this->_database->execute("insert into customercommission (HoGeneralID,ID,BranchID, CustomerID, CampaignMonth, CampaignYear, CommissionTypeID, EarnedBonusAmount, OutstandingBalance, 
											  VAT, WithHoldingTax, EnrollmentDate, LastModifiedDate, Changed) VALUES
											  ('".$rfID."-".$BranchID."',".$rfID.",".$BranchID.",".$CustomerID.",".$CampaignMonth.",".$xcampaign.",".$CommissionTypeID.",".$CalculateEarnings.", ".$CalculateEarnings.",
											  0, 0,NOW(),NOW(),1)");
				}
			}
		}
			$result['type'] = 'success';
			$result['message'] = "Candidacy recomputation successful";
			
			return $result;
	}
}
?>