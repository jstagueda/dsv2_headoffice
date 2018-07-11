<?php
 
require_once "../initialize.php";
global $database;
$totalCFT  =  $_POST['totCFT'];
$totalNCFT = $_POST['totNCFT'];
$custID = $_POST['hcustID'];
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
			$creditLimit 	= $customerCL->ApprovedCL;
			
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
	


$outputString = $creditLimit.'_'. $outstandingbalance.'_'.$outstandingpenalty.'_'. $MTDNCFT.'_'. $MTDCFT.'_'.$YTDCFT.'_'.$YTDNCFT	;
echo $outputString;




?>
