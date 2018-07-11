<?php
	require_once "../initialize.php";
	global $database;
	$datemonth =  date("m");
	$dateyear = date("Y");
	$tmpdate = $dateyear."-".$datemonth."-01";  
	
	/**Referral Fee**/
	try
	{
		$database->BeginTransaction();
		$rsListMotherIBM = $sp->spSelectListMotherIBM($database);
		if($rsListMotherIBM->num_rows)
		{
			while($motherIBM = $rsListMotherIBM->fetch_object())
			{
				$earnedDGS =0;
				$earnedDGSEnhanced = 0;
				$IBMID = $motherIBM->motherIBMID;
				$rsGetDaughterList = $sp->spSelectDaughterIBM($database,$IBMID);
				if($rsGetDaughterList->num_rows)
				{
					
					while($daughterIBM = $rsGetDaughterList->fetch_object())
					{
						$paidDGS = 0;
						$daughterIBMID = $daughterIBM->daugtherIBMID;
						$promotionDate = $daughterIBM->promotionYear;
						$rsGetBCRDGS = $sp->spSelectDGSBCRByIBMID($database,$daughterIBMID,$datemonth,$dateyear);
						if($rsGetBCRDGS->num_rows)
						{
							while($BCRDetails = $rsGetBCRDGS->fetch_object())
							{
								$paidDGS = $BCRDetails->PaidDGS;
							}
						}
						if($promotionDate < 2007)
						{
							$earnedDGS = $earnedDGS + $paidDGS;
						}
							
						
						
					}
				}
				$earnedCommission = $earnedDGS * 0.02;
				$rsGetCampaignMonth = $sp->spSelectCampaignMonthIDbyMonth($database,$datemonth,$dateyear);
				if($rsGetCampaignMonth->num_rows)
				{		
					while($campaignMonth = $rsGetCampaignMonth->fetch_object())
					{
						$campaignmonthID = $campaignMonth->ID;
						$rsGetCommission = $sp->spSelectCustomerCommissionByCustomerID($database,$IBMID,$campaignmonthID,2);	
						if($rsGetCommission->num_rows)	
						{
							while($commissionDetails = $rsGetCommission->fetch_object())
							{												
								$commissionID = $commissionDetails->ID;			
								$prevAmount = $commissionDetails->Amount;
								$addtlBonus =  $earnedBonus - $prevAmount;								
								$commissionID = $commissionDetails->ID;												
								$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$earnedBonus ,$commissionID,$addtlBonus);	
								$updateCustomerChanged = $sp->spUpdateCustomerChanged($database,$IBMID);								
								//$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$earnedBonus ,$commissionID);
							}
						}
					}
				}
				
				
			}
		}
		
		$database->commitTransaction();
		
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		$database->rollbackTransaction();
	}
	
	
	/**Servie Fee**/
	try
	{
		$database->BeginTransaction();
		$rsGetListIBM = $sp->spSelectIBMFFforServiceFee($database);
		if($rsGetListIBM->num_rows)
		{
			while($customerDetails = $rsGetListIBM->fetch_object())
			{
				$branchID = 0;
				$customerID = $customerDetails->customerID;
				$rsGetBranch = $sp->spSelectMotherBranchperIBMID($database,$customerID);
				
				if($rsGetBranch->num_rows)
				{
					while($custBranch = $rsGetBranch->fetch_object())
					{
						$branchID = $custBranch->branchID;	
					}
					
				}
				$rsGetPaidDGS = $sp->spSelectDGSperIBMIDSFMainBranch($database,$customerID,$datemonth,$dateyear,$branchID);
				$paidDGSCFT =0;
				$paidDGSNCFT = 0;
				$earnDiscCFT = 0;
				$earnDiscNCFT = 0;
				$earnedBonus = 0;
				if($rsGetPaidDGS->num_rows)
				{
					while($salesDetails = $rsGetPaidDGS->fetch_object())
					{
						$paidDGSCFT = $salesDetails->PaidDGSCFT;
						$paidDGSNCFT = $salesDetails->PaidDGSNCFT;
						$rsGetBracketCFT = $sp->spSelectServiceFeeByAmount($database,$paidDGSCFT,1);
						if($rsGetBracketCFT->num_rows)
						{
							while($SFCFTDetails = $rsGetBracketCFT->fetch_object())
							{
								$cftDiscount = $SFCFTDetails->Discount;
								if($cftDiscount != 0)
								{
									$earnDiscCFT = 	$paidDGSCFT * ($cftDiscount/100);
									 	
								}
								
							}
						}
						$rsGetBracketNCFT = $sp->spSelectServiceFeeByAmount($database,$paidDGSNCFT,2);
						if($rsGetBracketNCFT->num_rows)
						{
							while($SFNCFTDetails = $rsGetBracketCFT->fetch_object())
							{
								$ncftDiscount = $SFNCFTDetails->Discount;
								if($cftDiscount != 0)
								{
									$earnDiscNCFT = 	$paidDGSNCFT * ($ncftDiscount/100);
									 	
								}	
								
							}
						}
						
						$earnedBonus = $earnDiscNCFT + $earnDiscCFT;
						$rsGetCampaignMonth = $sp->spSelectCampaignMonthIDbyMonth($database,$datemonth,$dateyear);
						if($rsGetCampaignMonth->num_rows)
						{		
							while($campaignMonth = $rsGetCampaignMonth->fetch_object())
							{
								$campaignmonthID = $campaignMonth->ID;
								$rsGetCommission = $sp->spSelectCustomerCommissionByCustomerID($database,$customerID,$campaignmonthID,4);	
								if($rsGetCommission->num_rows)	
								{
									while($commissionDetails = $rsGetCommission->fetch_object())
									{												
										$commissionID = $commissionDetails->ID;												
										$prevAmount = $commissionDetails->Amount;
										$addtlBonus =  $earnedBonus - $prevAmount;								
										$commissionID = $commissionDetails->ID;												
										$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$earnedBonus ,$commissionID,$addtlBonus);
										$updateCustomerChanged = $sp->spUpdateCustomerChanged($database,$customerID);	
										//$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$earnedBonus ,$commissionID);
									}
								}
							}
						}
						
						
					}
				}
				
			}
		}
		$database->commitTransaction();
		
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		$database->rollbackTransaction();
	}
	
		
		
	/*Candidacy as IBMC*/
	try
	{
		$datefrom = date("Y-m",strtotime("-3 month"));
		$dateto = date("Y-m");
		$monthfrom = date("m",strtotime("-2 month"));
		$monthto = date("m");
		$yearfrom = date("Y",strtotime("-2 month"));
		$yearto = date("Y");		
		$rsGetIBMC = $sp->spSelectIBMCandidacy($database,$datefrom,$dateto);
		
		if($rsGetIBMC->num_rows)
		{
			while($row = $rsGetIBMC->fetch_object())
			{
				$customerID = $row->customerID;
				$year = $yearfrom;				 
				$cnt = 1;
				//check if the ibmc passed the requirments
				$availBonus = 0;	
				$countRecruits = 0 ;			
				for($i=$monthfrom ; $i <= $monthto ; $i++)
				{					
					$sales = 0;			
					$rsGetDGSBCR = $sp->spSelectDGSBCRByCustomerID($database,$customerID,$i,$year);	
					if($rsGetDGSBCR->num_rows)
					{
						while($salesDetails = $rsGetDGSBCR->fetch_object())
						{
							$sales = $salesDetails->DGS;
							
						}
					}	
					$rsGetRecruits = $sp->spSelectCountRecruits($database,$customerID,$year,$i);
					if($rsGetRecruits->num_rows)
					{
						while($recruitDetails = $rsGetRecruits->fetch_object())
						{
							$countRecruits = $recruitDetails->recruits;
							
						}
					}
					
					if($cnt ==1 && $availBonus == 0)
					{
						if($sales >= 30000 && $countRecruits >= 7 )
						{
							$availBonus = 1;							
						}
					
					}
					if($cnt ==2 && $availBonus == 0)	
					{
						if($sales >= 30000 && $countRecruits >= 7 )
						{
							$availBonus = 1;									
						}
					}
					if($cnt ==2 && $availBonus == 1)	
					{
						if( $sales < 30000 && $countRecruits < 7 )
						{
							$availBonus = 0;
							
						}
					}
					if($cnt ==3 && $availBonus == 1)	
					{
						if($sales < 30000 && $countRecruits < 7 )
						{
							$availBonus = 0;									
						}
					}
					if($i == 12)
					{
						$year = $year + 1;
					}
					$cnt = $cnt + 1 ;				
				}				
				if($availBonus == 1)
				{
					$bonusCFT = 0;
					$bonusNCFT = 0;
					$year = $yearfrom;	
					for($i=$monthfrom ; $i <= $monthto ; $i++)
					{
						
						$rsGetBCRDGSDetailed = $sp->spSelectDGSBCRByCustomerID($database,$customerID,$i,$year);
						if($rsGetBCRDGSDetailed->num_rows)
						{
							
							while($BCRDetailed = $rsGetBCRDGSDetailed->fetch_object())
							{
								$earnedBonus = 0;
								$earnedCFTBonus = 0;
								$earnedNCFTBonus = 0;
								$BCRID	= $BCRDetailed->ID;
								$cftrate = 0.10;
								$ncftrate = 0.10;
								$paidDGSNCFT = 	$BCRDetailed->PaidDGSNCFT;
								$paidDGSCFT = 	$BCRDetailed->PaidDGSCFT;
								$earnedCFTBonus = 	$paidDGSCFT * $cftrate;
								$earnedNCFTBonus =  $paidDGSNCFT * $ncftrate;
								$earnedBonus = $earnedCFTBonus + $earnedNCFTBonus;	
								//insert record in customercommission						
								$rsGetCampaignMonth = $sp->spSelectCampaignMonthIDbyMonth($database,$i,$year);
								//echo "spSelectCampaignMonthIDbyMonth($i,$year)";
								if($rsGetCampaignMonth->num_rows)
								{		
									while($campaignMonth = $rsGetCampaignMonth->fetch_object())
									{
										$campaignmonthID = $campaignMonth->ID;
										//check if record exist in customercommission
										$rsGetCommission = $sp->spSelectCustomerCommissionByCustomerID($database,$customerID,$campaignmonthID,3);	
										
										
										
										if($rsGetCommission->num_rows)	
										{
											
											while($commissionDetails = $rsGetCommission->fetch_object())
											{		
												$prevAmount = $commissionDetails->Amount;
												$addtlBonus =  $earnedBonus - $prevAmount;								
												$commissionID = $commissionDetails->ID;												
												$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$earnedBonus ,$commissionID,$addtlBonus);
												$updateCustomerChanged = $sp->spUpdateCustomerChanged($database,$customerID);	
												//echo "spUpdateCustomerCommission($database,$earnedBonus ,$commissionID);";
											}
										}
										else
										{
											$rsgetBranchID 	= $sp->spSelectBranchbyBranchParameter($database);
											while($branchDetails = $rsgetBranchID->fetch_object())
											{
												$branchID = $branchDetails->Id;
												$rsInsertCommission = $sp->spInsertCustomerCommission($database, $branchID,$customerID,$campaignmonthID,$earnedBonus,3);
												$updateCustomerChanged = $sp->spUpdateCustomerChanged($database,$customerID);	
											}
										
										}
											
																
									}
								}								
								//insert a record in tpi_customercommissiondetailed
								$rsCheckCommissionDetailed = $sp->spCheckCustomerCommissionDetailed($database, $BCRID , 4);
								if($rsCheckCommissionDetailed->num_rows)
								{
									while($commissiondetailed = $rsCheckCommissionDetailed->fetch_object())
									{
										$commissionID = $commissiondetailed->ID;
										$cftearnedbonus = $commissiondetailed->CFTEarnedBonus;
										$ncftearnedbonus = $commissiondetailed->NCFTEarnedBonus;
										$totalprevbonus 	= $commissiondetailed->TotalEarnedBonus;
										$totalapplied = $commissiondetailed->TotalApplied;
										
										$tmpbonus = $earnedBonus + $totalprevbonus;
										
										$vatamount = $tmpbonus * 0.12;
										$withholdingtax = $tmpbonus * 0.10;										
										$netoftax = $tmpbonus + round($vatamount,2) - round($withholdingtax,2);										
										$rsUpdateCommissionDetailed = $sp->spUpdateCustomerCommissionDetailed($database,$commissionID, $earnedCFTBonus, $earnedNCFTBonus, $earnedBonus, $vatamount, $withholdingtax , $netoftax);
									}
									
								}
								else
								{
									$vatamount = $earnedBonus * 0.12;
									$withholdingtax = $earnedBonus * 0.10;		
									$netoftax = $earnedBonus + round($vatamount,2) - round($withholdingtax,2);	
									$rsInsertCommissionDetailed = $sp->spInsertCustomerCommissionDetailed($database,3, 10, 10 , $earnedCFTBonus, $earnedNCFTBonus , $earnedBonus, $vatamount , $withholdingtax ,$netoftax , $BCRID);
								}										
								
							}							
						}							
						if($i == 12)
						{
							$year = $year + 1;
						}
					}
					
				}
			}
		}
	
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		$database->rollbackTransaction();
	}
	/*Candidacy as IBMFF*/
	try
	{
		$datefrom = date("Y-m",strtotime("-2 month"));
		$dateto = date("Y-m");
		$monthfrom = date("m",strtotime("-2 month"));
		$monthto = date("m");
		$yearfrom = date("Y",strtotime("-2 month"));
		$yearto = date("Y");		
		$rsGetIBMC = $sp->spSelectIBMFF($database,$datefrom,$dateto);
		if($rsGetIBMC->num_rows)
		{ 
			while($row = $rsGetIBMC->fetch_object())
			{
				$customerID = $row->customerID;
				$year = $yearfrom;				 
				$cnt = 1;
				//check if the ibmc passed the requirments
				$availBonus = 0;	
				$countRecruits = 0 ;
						
				for($i=$monthfrom ; $i <= $monthto ; $i++)
				{					
					$sales = 0;		
					$countRecruits = 0 ;	
					$rate = 0;		
					$tmprate = 0;
					$rsGetBCRDGS = $sp->spSelectDGSBCRByIBMID($database,$customerID,$i,$year);
					if($rsGetBCRDGS->num_rows)
					{
						
						while($BCRDetails = $rsGetBCRDGS->fetch_object())
						{
							
							$sales	= $BCRDetails->DGS ;
							$payment = $BCRDetails->PaidDGS;
							$tmprate = 	($sales/$payment) *100;
							$rate	=	round($tmprate,2);							
						
						}
					}
					$rsGetRecruits = $sp->spSelectCountRecruits($database,$customerID,$year,$i);
					if($rsGetRecruits->num_rows)
					{
						while($recruitDetails = $rsGetRecruits->fetch_object())
						{
							$countRecruits = $recruitDetails->recruits;
							
						}
					}
					
					if($cnt ==1 && $availBonus == 0)
					{
						if($sales >= 40000 && $countRecruits >= 7 && $rate >= 97 )
						{
							$availBonus = 1;							
						}
					
					}					
					if($cnt ==2 && $availBonus == 1)	
					{
						if( $sales >= 50000 && $countRecruits >= 7 && $rate >= 97 )
						{
							$availBonus = 1;
							
						}
					}
					
					if($i == 12)
					{
						$year = $year + 1;
					}
					$cnt = $cnt + 1 ;				
				}				
				if($availBonus == 1)
				{
					$bonusCFT = 0;
					$bonusNCFT = 0;
					$year = $yearfrom;	
					for($i=$monthfrom ; $i <= $monthto ; $i++)
					{
						
						$rsGetBCRDGSDetailed = $sp->spSelectDGSBCRByIBMID($database,$customerID,$i,$year);
						if($rsGetBCRDGSDetailed->num_rows)
						{
							
							while($BCRDetailed = $rsGetBCRDGSDetailed->fetch_object())
							{
								$earnedBonus = 0;
								$earnedCFTBonus = 0;
								$earnedNCFTBonus = 0;
								$BCRID	= $BCRDetailed->ID;
								$cftrate = 0.10;
								$ncftrate = 0.10;
								$paidDGSNCFT = 	$BCRDetailed->PaidDGSNCFT;
								$paidDGSCFT = 	$BCRDetailed->PaidDGSCFT;
								$earnedCFTBonus = 	$paidDGSCFT * $cftrate;
								$earnedNCFTBonus =  $paidDGSNCFT * $ncftrate;
								$earnedBonus = $earnedCFTBonus + $earnedNCFTBonus;	
								//insert record in customercommission						
								$rsGetCampaignMonth = $sp->spSelectCampaignMonthIDbyMonth($database,$i,$year);
								if($rsGetCampaignMonth->num_rows)
								{		
									while($campaignMonth = $rsGetCampaignMonth->fetch_object())
									{
										$campaignmonthID = $campaignMonth->ID;
										//check if record exist in customercommission
										$rsGetCommission = $sp->spSelectCustomerCommissionByCustomerID($database,$customerID,$campaignmonthID,3);	
										if($rsGetCommission->num_rows)	
										{
											while($commissionDetails = $rsGetCommission->fetch_object())
											{												
												$commissionID = $commissionDetails->ID;			
												$prevAmount = $commissionDetails->Amount;
												$addtlBonus =  $earnedBonus - $prevAmount;								
												$commissionID = $commissionDetails->ID;												
												$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$earnedBonus ,$commissionID,$addtlBonus);	
												$updateCustomerChanged = $sp->spUpdateCustomerChanged($database,$customerID);									
												//$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$earnedBonus ,$commissionID);
												
											}
										}
										else
										{
											$rsgetBranchID 	= $sp->spSelectBranchbyBranchParameter($database);
											while($branchDetails = $rsgetBranchID->fetch_object())
											{
												$branchID = $branchDetails->Id;
												$rsInsertCommission = $sp->spInsertCustomerCommission($database, $branchID,$customerID,$campaignmonthID,$earnedBonus,3);
												$updateCustomerChanged = $sp->spUpdateCustomerChanged($database,$customerID);	
											}
										
										}
											
																
									}
								}								
								//insert a record in tpi_customercommissiondetailed
								$rsCheckCommissionDetailed = $sp->spCheckCustomerCommissionDetailed($database, $BCRID , 4);
								if($rsCheckCommissionDetailed->num_rows)
								{
									while($commissiondetailed = $rsCheckCommissionDetailed->fetch_object())
									{
										$commissionID = $commissiondetailed->ID;
										$cftearnedbonus = $commissiondetailed->CFTEarnedBonus;
										$ncftearnedbonus = $commissiondetailed->NCFTEarnedBonus;
										$totalprevbonus 	= $commissiondetailed->TotalEarnedBonus;
										$totalapplied = $commissiondetailed->TotalApplied;
										
										$tmpbonus = $earnedBonus + $totalbonus;
										
										$vatamount = $tmpbonus * 0.12;
										$withholdingtax = $tmpbonus * 0.10;										
										$netoftax = $tmpbonus + round($vatamount,2) - round($withholdingtax,2);										
										$rsUpdateCommissionDetailed = $sp->spUpdateCustomerCommissionDetailed($database,$commissionID, $earnedCFTBonus, $earnedNCFTBonus, $earnedBonus, $vatamount, $withholdingtax , $netoftax);
									}
									
								}
								else
								{
									$vatamount = $earnedBonus * 0.12;
									$withholdingtax = $earnedBonus * 0.10;		
									$netoftax = $earnedBonus + round($vatamount,2) - round($withholdingtax,2);	
									$rsInsertCommissionDetailed = $sp->spInsertCustomerCommissionDetailed($database,3, 10, 10 , $earnedCFTBonus, $earnedNCFTBonus , $earnedBonus, $vatamount , $withholdingtax ,$netoftax , $BCRID);
								}										
								
							}							
						}							
						if($i == 12)
						{
							$year = $year + 1;
						}
					}
					
				}
			}
		}
	
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		$database->rollbackTransaction();
	}
	
	
	
?>