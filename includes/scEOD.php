<?php
/*   
  @modified by John Paul Pineda
  @date June 12, 2013
  @email paulpineda19@yahoo.com         
*/

require_once('../initialize.php');
require_once('../class/tpi-start-of-day-transactions.php');
require_once('../class/tpi-end-of-day-transactions.php');

$tpi_sod_transactions=new TPI_DSS_Start_Of_Day_Transactions();
$tpi_eod_transactions=new TPI_DSS_End_Of_Day_Transactions();

global $database;

$end_of_day_transaction_session_id=session_id();
$end_of_day_transaction_session_employee_id=isset($_SESSION['emp_id'])?$_SESSION['emp_id']:0;

$campaign_code=strtoupper(date('My'));
$two_digit_date_year=date('y');
	
$datemonth=date('m');
$dateyear=date('Y');
$tmpdate=date('Y-m-d');

$tpi_eod_transactions->consolidateBranchCollectionRating();
echo $tpi_eod_transactions->errmsg;
die('<center><font color="#00008B"><b>End of Day Transaction has been successfully completed.<b></font></center>');
	
	/*check for approved write-off*/	
	try
	{
		
		$database->beginTransaction();
		$rsGetApprovedList = $sp->spSelectApprovedWO($database);
		if($rsGetApprovedList->num_rows)
		{
			while($approvedList = $rsGetApprovedList->fetch_object())
			{
				$customerID 	= $approvedList->CustomerID;
				$writeoffID 	= $approvedList->ID;
				$writeoffamount = $approvedList->PastDue;
				$rs_birseries = $sp->spSelectBIRSeriesByTxnTypeID($database, 5);
				while ($row = $rs_birseries->fetch_object())
				{
					$bir_series	= $row->Series;
					$bir_id = $row->NextID;			
				}
				$insertDMCM = $sp->spInsertDMCMforWO($database,$customerID, $bir_series,$writeoffamount);
				if($insertDMCM->num_rows)
				{
					while($dmcmdetails = $insertDMCM->fetch_object())
					{
						$dmcmID = $dmcmdetails->DMCMID ;
					}
				}
				$rsSelectSalesInvoices = $sp->spSelectSalesInvoicesByCustomerID($database,$customerID);
				if($rsSelectSalesInvoices->num_rows)
				{	
					while($salesinvoiceList = $rsSelectSalesInvoices->fetch_object())
					{
						$SIID = $salesinvoiceList->ID;
						$OutstandingBalance = $salesinvoiceList->OutstandingBalance;
						$updateSI = $sp->spUpdateSIforWO($database,$SIID);
						
						$rsGetListAR = $sp->spSelectARByInvoiceIDWO($database,$SIID);
						if($rsGetListAR->num_rows)													
						{
							while($ARDetails = $rsGetListAR->fetch_object())
							{
								$ARID = $ARDetails->ID;
								$updateAR = $sp->spUpdateARforWO($database,$ARID);
							}
						}					
															
						$rsInsertDMCMDetatils = $sp->spInsertDMCMDetailforWO($database,$dmcmID, $SIID,$OutstandingBalance);
						
					}
				}
				
				$rsUpdateWriteOffStatus = $sp->spUpdateWriteOffStatus($database,$writeoffID);
				
			}
		}
		$database->commitTransaction(); 
		
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		$database->rollbackTransaction();
		
	}
	
	/*Update Dealer Status from Applicant to Appointed*/
	try
	{
		$database->beginTransaction();
		$tmpDate = date("Y-m-d");
		$productID = 0;
		$isApproved = 0;
		//get the required product kit id 
		$rsRequiredProduct = $sp->spSelectRequiredProductKit($database, $tmpDate);
		if($rsRequiredProduct->num_rows)
		{
			while($row = $rsRequiredProduct->fetch_object())
			{
				$productID = $row->ProductID ;
			}	
		}
		//get list of applicants
		$rsListApplicants = $sp->spSelectListDealerApplicants($database);
		if($rsListApplicants->num_rows)
		{
			while($applicantDetails = $rsListApplicants->fetch_object())
			{
				$customerID = $applicantDetails->customerID;
				$rsCheckProdRequirement = $sp->spCheckProductKitRequirement($database,$customerID,$productID);
				if($rsCheckProdRequirement->num_rows)				
				{
					$rsCheckAmountRequirement = $sp->spSelectAccumalatedSIDealerApplicants($database,$customerID);	
					if($rsCheckAmountRequirement->num_rows)
					{
						while($amountDetails = $rsCheckAmountRequirement->fetch_object())
						{
							if($amountDetails->NetAmount >= 500)
							{
								$isApproved = 1;
							}
						}
						
					}
					if($isApproved == 1)
					{
						//echo $customerID;
						$rsInsertNewStatus = $sp->spInsertCustomerAppointedStatus($database,$customerID,$_SESSION['emp_id']);
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
	
	//$so = new SalesOrder($database, $sp);
	//backorder status update 
	try {
		$database->BeginTransaction();
		
		$dbresult = $sp->spSelectEODBackOrder($database);
		while($row = $dbresult->fetch_object())
       	{
       		//echo 	$row->ID;									 						 			
 	       //$so->Close($row->ID);	
 	       $sp->spUpdateSOStatus($database, $row->ID);					 	
      	}
		
		$database->commitTransaction();
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		$database->rollbackTransaction();		
	}
	
	//update dealer status
	try {
		$database->BeginTransaction();
		
		$dbresult = $sp->spSelectEODUpdateDealerStatus($database);
		while($row = $dbresult->fetch_object())
       	{
			$sp->spUpdateEODDealerStatus($database, $row->CustomerID);						 						 								 	
      	}
		
		$database->commitTransaction();
	}	
	catch (Exception $e)
	{
		echo $e->getMessage();
		$database->rollbackTransaction();
	}						
	
	/*Service Fee*/
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
						
						$rsGetCommission = $sp->spSelectCustomerCommissionByCustomerID($database,$customerID,$monthID,1);
						if($rsGetCommission->num_rows)
						{
							while($commissionDetails = $rsGetCommission->fetch_object())
							{
								
								$commissionID = $commissionDetails->ID;								
								$rsUpdateCommission = $sp->spUpdateCustomerCommission($database,$totalcommission ,$commissionID);
							}
						}
						else
						{
							$rsgetBranchID 	= $sp->spSelectBranchbyBranchParameter($database);
							while($branchDetails = $rsgetBranchID->fetch_object())
							{
								$branchID = $branchDetails->Id;
								$rsInsertCommission = $sp->spInsertCustomerCommission($database, $branchID,$customerID,$monthID,$totalcommission,1);
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
	 
	/*PDA Codes*/	
	try
	{
		$database->beginTransaction();
		$rsGetCustomerNotINPDA = $sp->spSelectCustomerPDACode($database);
		if($rsGetCustomerNotINPDA->num_rows)
		{
			while($customerpdadetails = $rsGetCustomerNotINPDA->fetch_object())
			{
				$customerID = $customerpdadetails->CustomerID;
				if($customerpdadetails->OutstandingAmount <= 100)
				{
					$pdaID = 1;
					
				}
				if($customerpdadetails->OutstandingAmount <= 100)
				{
					$pdaID = 2;
					
				}
				if($customerpdadetails->OutstandingAmount <= 4999)
				{
					$pdaID = 2;
					
				}
				if($customerpdadetails->OutstandingAmount >= 4999)
				{
					$pdaID = 3;
					
				}
				$insertCustomerPDA = $sp->spInsertCustomerPDA($database,$customerID, $pdaID);
			}
			
		}
		
		$database->commitTransaction();	
	
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		$database->rollbackTransaction();
	}
	
	/*Bad Debts*/
	try
	{
		$database->beginTransaction();
		//check if branch is ibd
		$rsCheckifIBD = $sp->spCheckifIBD($database);
		if($rsCheckifIBD->num_rows)
		{
			while($ibdDetails = $rsCheckifIBD->fetch_object())
			{
				//get a list of invoice due over 90 days
				$rsSalesInvoiceCM = $sp->spSelectSalesInvoiceCMforIBD($database);
				if($rsSalesInvoiceCM->num_rows)
				{
					$totalAmout = 0;
					while($salesinvoiceDetails = $rsSalesInvoiceCM->fetch_object())
					{						
						$customerID = $salesinvoiceDetails->customerID;
						$pastdueamt = $salesinvoiceDetails->outstandingAmount;
						$totalAmout = $totalAmout + $pastdueamt;
						$rs_birseries = $sp->spSelectBIRSeriesByTxnTypeID($database, 5);
						while ($row = $rs_birseries->fetch_object())
						{
							$bir_series	= $row->Series;
							$bir_id = $row->NextID;			
						}						
						$insertDMCM = $sp->spInsertDMCMforWO($database,$customerID, $bir_series,$pastdueamt);
						if($insertDMCM->num_rows)
						{
							while($dmcmdetails = $insertDMCM->fetch_object())
							{
								$dmcmID = $dmcmdetails->DMCMID ;
							}
						}
						$rsSelectSalesInvoices = $sp->spSelectSalesInvoicesByCustomerID($database,$customerID);
						if($rsSelectSalesInvoices->num_rows)
						{	
							while($salesinvoiceList = $rsSelectSalesInvoices->fetch_object())
							{
								$SIID = $salesinvoiceList->ID;
								$OutstandingBalance = $salesinvoiceList->OutstandingBalance;
								$updateSI = $sp->spUpdateSIforWO($database,$SIID);
								
								$rsGetListAR = $sp->spSelectARByInvoiceIDWO($database,$SIID);
								if($rsGetListAR->num_rows)													
								{
									while($ARDetails = $rsGetListAR->fetch_object())
									{
										$ARID = $ARDetails->ID;
										$updateAR = $sp->spUpdateARforWO($database,$ARID);
									}
								}					
																	
								$rsInsertDMCMDetatils = $sp->spInsertDMCMDetailforWO($database,$dmcmID, $SIID,$OutstandingBalance);
								
							}
						}
					}
					$rs_birseries = $sp->spSelectBIRSeriesByTxnTypeID($database, 5);
					while ($row = $rs_birseries->fetch_object())
					{
						$bir_series	= $row->Series;
						$bir_id = $row->NextID;			
					}
					$rsIBDDetails = $sp->spSelectCustomerIBD($database);
					while($ibdDetails = $rsIBDDetails->fetch_object())
					{
						$IBDID = $ibdDetails->customerID;
						$insertDM = $sp->spInsertDMCMforIBD($database,$IBDID, $bir_series,$pastdueamt);
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