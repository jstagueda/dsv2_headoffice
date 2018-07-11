<?php
header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
header( "Cache-Control: no-cache, must-revalidate" );
header( "Pragma: no-cache" );
 
require_once('../initialize.php');

global $database;

$totalCFT  =  $_POST['totCFT'];
$totalNCFT = $_POST['totNCFT'];
$custID = $_POST['hCOA'];
$month = date("m"); 
$year  = date("Y");
$MTDCFT			 	= 0;
$MTDCFTDisc  	 	= 0;
$YTDCFT	 		 	= 0;
$MTDNCFT 		 	= 0;
$MTDNCTDisc 	 	= 0; 
$YTDNCFT 		 	= 0;
$creditLimit 	 	= 0;
$outstandingbalance = 0;
$outstandingpenalty	= 0;
$isEmployee = 0;
$ibm = 0;
$gsutypeid = 0;
$ibmStatus = 0 ;
$customerStatus= 0;
$ibmid = 0;
$gsualert = 0;
$unpaidInvoice = 0;


	//$rsDiscountCFT = $sp->spSelectDiscountBracket();
	$rsMTDCFT = $sp->spSelectMTDbyCustomerID($database,$custID,$month,$year,1);
				
	if($rsMTDCFT->num_rows)
	{
		while($tmpMTDCFT = $rsMTDCFT->fetch_object())
		{
			$MTDCFT		 = $tmpMTDCFT->Amount;
			$MTDCFTDisc	 = $tmpMTDCFT->DiscountID;
			//echo $MTDCFT;
		}
	}
	$rsYTDCFT = $sp->spSelectYTDbyCustomerID($database,$custID,$year,1);
				
	if($rsYTDCFT->num_rows)
	{
		while($tmpYTDCFT = $rsYTDCFT->fetch_object())
		{
			$YTDCFT		 = $tmpYTDCFT->Amount;
			//$MTDCFTDisc	 = $tmpMTDCFT->DiscountID;
			//echo $MTDCFT;
		}
	}
	$rsMTDNCFT = $sp->spSelectMTDbyCustomerID($database,$custID,$month,$year,2);
				
	if($rsMTDNCFT->num_rows)
	{
		while($tmpMTDNCFT = $rsMTDNCFT->fetch_object())
		{
			$MTDNCFT 	= $tmpMTDNCFT->Amount;
			$MTDNCTDisc = $tmpMTDNCFT->DiscountID;
			//echo $MTDNCFT;
		}
	}
	$rsYTDNCFT = $sp->spSelectYTDbyCustomerID($database,$custID,$year,2);
				
	if($rsYTDNCFT->num_rows)
	{
		while($tmpYTDNCFT = $rsYTDNCFT->fetch_object())
		{
			$YTDNCFT		 = $tmpYTDNCFT->Amount;
			//$MTDCFTDisc	 = $tmpMTDCFT->DiscountID;
			//echo $MTDCFT;
		}
	}
	
	$rsCreditLimit = $sp->spSelectCreditLimitByCustomerID($database,$custID);
	if($rsCreditLimit->num_rows)
	{
		while($customerCL = $rsCreditLimit->fetch_object())
		{ 
			
			$creditLimit = $customerCL->ApprovedCL;
		}
	}
	$rsARBalance = $sp->spSelectARBalanceByCustomerID($database,$custID);
	if($rsARBalance->num_rows)
	{
		while($ARBalance = $rsARBalance->fetch_object())
		{
			$outstandingbalance 	= $ARBalance->GrossAmount - $ARBalance->TotalPayment ;
			
		}
	}
	
	$rsPenalty = $sp->spSelectPenaltyByCustomerID($database,$custID);
	if($rsPenalty->num_rows)
	{
		while($Penalty = $rsPenalty->fetch_object())
		{
			$outstandingpenalty	= $Penalty->OutstandingAmount;
			
		}
	}
	
	$rsIBM = $sp->spSelectCustomerIBM($database,$custID);
	if($rsIBM->num_rows)
	{
		while($getIBM = $rsIBM->fetch_object())
		{
			if($getIBM->CustomerTypeID == 99)
			{ 	
				$isEmployee = 1;
			}
		
	$ibm	= $getIBM->IBM;
	$gsutypeid = $getIBM->GSUTypeID;
	$ibmID = $getIBM->IBMID;
		}
	}
	
	$rsCustomerStatus = $sp->spSelectCustomerStatus($database,$custID);
	if($rsCustomerStatus->num_rows)
	{
		while($getStatus = $rsCustomerStatus->fetch_object())
		{
			$customerStatus = $getStatus->statusID;
			//echo "here";
		}
	}
	
	if($MTDNCFT == "")
	{
		$MTDNCFT = "0.00";
	}
	 
 
	$gsuID=0;
	$rsGSUType = $sp->spSelectGSUTypebyCustomerID($database,$custID);
	if($rsGSUType->num_rows)
	{
		while($getGSUCode = $rsGSUType->fetch_object())
		{
			$gsuCode = $getGSUCode->GSUCode;
			$gsuID = $getGSUCode->GSUID;
			
			
			//echo "here";
		}
	}
	if($gsuID != 1)
	{
		$rsGetGSUNetwork = $sp->spSelectGSUNetwork($database, $ibmID);
		if($rsGetGSUNetwork->num_rows)
		{
			while($GSUNetwork = $rsGetGSUNetwork->fetch_object())
			{
				$gsucustomerID = $GSUNetwork->customerID;
				$outstandingbalancegsu = 0;	
				$outstandingpenaltygsu = 0 ;
				$rsARBalance = $sp->spSelectDueARBalanceByCustomerID($database,$gsucustomerID);
				if($rsARBalance->num_rows)
				{
					while($ARBalance = $rsARBalance->fetch_object())
					{
						$outstandingbalancegsu 	= $ARBalance->GrossAmount - $ARBalance->TotalPayment ;
						
					}
				}
				
				$rsPenalty = $sp->spSelectPenaltyByCustomerID($database,$gsucustomerID);
				if($rsPenalty->num_rows)
				{
					while($Penalty = $rsPenalty->fetch_object())
					{
						$outstandingpenaltygsu	= $Penalty->OutstandingAmount;
						
					}
				}
					
				if($outstandingbalancegsu != 0 ||$outstandingpenaltygsu != 0 )
				{
					$gsualert = 1;
				} 
				
			}
		}
	}
	
	$rsUnpaidSalesInvoice=$sp->spSelectNetUnpaidSIByCustomerID($database, $custID); 
	if($rsUnpaidSalesInvoice->num_rows) {
  
		while($unpaidSI=$rsUnpaidSalesInvoice->fetch_object()) $unpaidInvoice=$unpaidSI->unpaidSI;						
	}
	
	$rsAvailableCredit=$tpi->getAvailableCreditByCustomerID($database, $custID);
  if($rsAvailableCredit->num_rows) {
    
    while($field=$rsAvailableCredit->fetch_object()) $availableCredit=$field->availableCredit;
  }
	
$outputString=$creditLimit.'_'.$outstandingbalance.'_'.$outstandingpenalty.'_'.$MTDNCFT.'_'. $MTDCFT.'_'.$YTDCFT.'_'.$YTDNCFT.'_'.$ibm.'_'.$isEmployee.'_'.$gsutypeid.'_'.$customerStatus.'_'.$gsuCode.'_'.$gsualert.'_'.$unpaidInvoice.'_'.$availableCredit;

echo $outputString;
