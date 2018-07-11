<?php
	require_once "../initialize.php";
	global $database;
	$datemonth =  $_GET['month'];
	$dateyear = date("Y");
	$tmpdate = $dateyear."-".$datemonth."-01";  
	
		
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
								if($rsGetCampaignMonth->num_rows)
								{		
									while($campaignMonth = $rsGetCampaignMonth->fetch_object())
									{
										$campaignmonthID = $campaignMonth->ID;
										//check if record exist in customercommission
										$rsGetCommission = $sp->spSelectCustomerCommissionByCustomerID($database,$customerID,$campaignmonthID,4);	
										if($rsGetCommission->num_rows)	
										{
											while($commissionDetails = $rsGetCommission->fetch_object())
											{												
												$commissionID = $commissionDetails->ID;												
												$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$earnedBonus ,$commissionID);
											}
										}
										else
										{
											$rsgetBranchID 	= $sp->spSelectBranchbyBranchParameter($database);
											while($branchDetails = $rsgetBranchID->fetch_object())
											{
												$branchID = $branchDetails->Id;
												$rsInsertCommission = $sp->spInsertCustomerCommission($database, $branchID,$customerID,$campaignmonthID,$earnedBonus,4);
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
									$rsInsertCommissionDetailed = $sp->spInsertCustomerCommissionDetailed($database,4, 10, 10 , $earnedCFTBonus, $earnedNCFTBonus , $earnedBonus, $vatamount , $withholdingtax ,$netoftax , $BCRID);
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
										$rsGetCommission = $sp->spSelectCustomerCommissionByCustomerID($database,$customerID,$campaignmonthID,4);	
										if($rsGetCommission->num_rows)	
										{
											while($commissionDetails = $rsGetCommission->fetch_object())
											{												
												$commissionID = $commissionDetails->ID;												
												$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$earnedBonus ,$commissionID);
											}
										}
										else
										{
											$rsgetBranchID 	= $sp->spSelectBranchbyBranchParameter($database);
											while($branchDetails = $rsgetBranchID->fetch_object())
											{
												$branchID = $branchDetails->Id;
												$rsInsertCommission = $sp->spInsertCustomerCommission($database, $branchID,$customerID,$campaignmonthID,$earnedBonus,4);
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
									$rsInsertCommissionDetailed = $sp->spInsertCustomerCommissionDetailed($database,4, 10, 10 , $earnedCFTBonus, $earnedNCFTBonus , $earnedBonus, $vatamount , $withholdingtax ,$netoftax , $BCRID);
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
	
	//Service Fee
	try
	{
		$database->beginTransaction();
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
				$IBMCID = $row->customerID;
				$year = $yearfrom;	
				
			}
			
		}
		
		$database->commitTransaction();		
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		//$database->rollbackTransaction();
	}
        
	//auto termination of dealers
        /*
         * @author: jdymosco
         * @date: April 05, 2013
         * @update: Changed the stored procedure process of auto termination for dealers status.
         */
	try
	{
		/*$database->beginTransaction();
		$txnDate = date("Y-m-d",strtotime("-3 Months",strtotime($tmpdate)));
		
		$rsDealerList = $sp->spSelectListofDealersAutoTeminate($database,$txnDate);
		if($rsDealerList->num_rows)
		{
			while($row = $rsDealerList->fetch_object())
			{
				$insertNewStatus = $sp->spInsertAutoTerminateDealer($database,$row->customerID,$_SESSION['user_id']);
				
			}
		}				
		$database->commitTransaction();*/
                $UserSessionedID = $_SESSION['user_id'];
                $database->execute("CALL spEOMAutoMovementTerminationStatus($UserSessionedID)");
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		//$database->rollbackTransaction();
	}
	
	
	
	// credit limit auto update	
	try	
	{
		$database->beginTransaction();
		//$txnDate = date("Y-m-d");
		$rsDealerList = $sp->spSelectCustomerCreditLimitUpdate($database,$tmpdate);
		if($rsDealerList->num_rows)
		{
			while($dealerDetails = $rsDealerList->fetch_object())
			{
				$creditLimit = $dealerDetails->CreditLimit;
				//$creditLimit = 5000.99;
				$dgs = $dealerDetails->NetAmount;
				$paidDGS = $dealerDetails->OutstandingBalance;
				$customerID = $dealerDetails->customerID;
				//echo $creditLimit;
				$totalAR = $dgs - $paidDGS;
				$minCreditUsage = $creditLimit * 0.7;
				//echo "AR---".$totalAR."   usage---".$minCreditUsage."<br>";
				if($totalAR >= $minCreditUsage)
				{
					if($creditLimit <= 5000.99)
					{
						
						$checkDate = date("Y-m-d",strtotime("-2 Months",strtotime($tmpdate)));
						$chkMonth  = date("m",strtotime($checkDate));
						$chkYear   = date("Y",strtotime($checkDate));
						$availed = 1;
						for($i=$chkMonth ; $i<= $datemonth ; $i++ )
						{
							
							
							$rsCheckBCR = $sp->spSelectBRCbyCustomerID($database,$v_customerID,$i,$chkYear);
							if($rsCheckBCR->num_rows)
							{
								while($row ==$rsCheckBCR->fetch_object())
								{
									if($row->rating != 100)
									{
										$availed = 0;
									}
								}
							}
							if($availed == 0)
							{
								break;
							}
						} 
						if($availed == 1)
						{
							$newCreditLimit = $creditLimit + 1500;
							$rsInsertCL = $sp->spInsertCreditLimitDetails($database, $creditLimit , $newCreditLimit, $customerID);
						}
						 	
						
						
					}
					else
					{
						$checkDate = date("Y-m-d",strtotime("-3 Months",strtotime($tmpdate)));
						$chkMonth  = date("m",strtotime($checkDate));
						$chkYear   = date("Y",strtotime($checkDate));
						$availed = 1;
						for($i=$chkMonth ; $i<= $datemonth ; $i++ )
						{
							
							
							$rsCheckBCR = $sp->spSelectBRCbyCustomerID($database,$v_customerID,$i,$chkYear);
							if($rsCheckBCR->num_rows)
							{
								while($row ==$rsCheckBCR->fetch_object())
								{
									if($row->rating != 100)
									{
										$availed = 0;
									}
								}
							}
							if($availed == 0)
							{
								break;
							}
						} 
						if($availed == 1)
						{
							$newCreditLimit = $creditLimit + 2500;
							$rsInsertCL = $sp->spInsertCreditLimitDetails($database, $creditLimit , $newCreditLimit, $customerID);
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
	//update EOM parameters
	
	try
	{
		$database->beginTransaction();
		$rsUpdateEOMParameters = $sp->spUpdateEOMParameters($database,$tmpdate);
		$database->commitTransaction();	
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		$database->rollbackTransaction();
	}
	
	
	
	
	
	
	
	
	echo "<font color='#00008B'><b>End of Mpnth Transactions Completed<b></font>";
	
?>		