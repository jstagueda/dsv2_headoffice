<?php
	require_once "../initialize.php";
	global $database;
	try
	{
		$database->beginTransaction();
		$rsGetCampaignmonth = $sp->spSelectCampagnMonthID($database);
		if($rsGetCampaignmonth->num_rows)
		{
			while($row = $rsGetCampaignmonth->fetch_object())
			{
				
			   $monthID = $row->ID ;
			   $rsGetTotals = $sp->spSelectTotalNCFTCFTByMonth($database);
			   if($rsGetTotals->num_rows)
			   {
					while($transDetails = $rsGetTotals->fetch_object())
					{
						$customerID = $transDetails->CustomerID;
						$totalCFT 	= $transDetails->CFT;
						$totalNCFT 	= $transDetails->NCFT;								
						$discCFT  	= 0 ;
						$discNCFT	= 0 ;
						$discAmtCFT = 0	;
						$discAmtNCFT = 0 ;
						$totalcommission = 0;
						
						$difference 	= 0;
						$rsGetDiscountCFT = $sp->spSelectServiceFeeByAmount($database,$totalCFT, 1);
						while($discountCFT = $rsGetDiscountCFT->fetch_object())						
						{
							$discCFT = $discountCFT->Discount;
						}
						
						$rsGetDiscountNCFT = $sp->spSelectServiceFeeByAmount($database,$totalNCFT, 2);
						while($discountNCFT = $rsGetDiscountNCFT->fetch_object())						
						{
							$discNCFT = $discountNCFT->Discount;
						}		
								
						$discAmtCFT = $totalCFT  * ($discCFT/100) ;						
						$discAmtNCFT = $totalNCFT  * ($discNCFT/100);
						
						$totalcommission = $discAmtCFT + $discAmtNCFT ;
						
						$rsGetCommission = $sp->spSelectCustomerCommissionByCustomerID($database,$customerID,$monthID);
						if($rsGetCommission->num_rows)
						{
							while($commissionDetails = $rsGetCommission->fetch_object())
							{
								$difference = $commissionDetails->Amount  -  $commissionDetails->OustandingBalance ;
								$commissionID = $commissionDetails->ID;
								$outstandingamount = $totalcommission  -	$difference ;
								$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$totalcommission  ,$outstandingamount,$commissionID);
							}
						}
						else
						{
							$rsgetBranchID 	= $sp->spSelectBranchbyBranchParameter($database);
							while($branchDetails = $rsgetBranchID->fetch_object())
							{
								$branchID = $branchDetails->Id;
								$rsInsertCommission = $sp->spInsertCustomerCommission($database, $branchID,$customerID,$monthID,$totalcommission);
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
	
	?>