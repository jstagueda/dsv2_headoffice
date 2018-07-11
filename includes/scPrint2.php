<?php

	if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	} 

	 if (isset($_GET['siid'])){
		 	$siid = $_GET['siid'];
	 } else {
		 	$siid = 0;
	 }

	 // Initiate Variables 	 
	 $permitno 	 = 0;
	 $branchname = "";
	 $tinno		 = "";
	 $address    = "";
	 $serverno   = "";
	 $branchcode = "";
	 	 
	 $salesinvoice = 0;
	 $txndate 	   = "";
	 $reftxnid	   = 0;
	 $pageno	   = 1;
	 $customerid   = 0;
	 
	 $grossamount  = 0;
	 $basicdisc    = 0;
	 $vatamount    = 0;
	 $adddiscprev  = 0;
	 $netamount    = 0;
	 $outbal       = 0;
	 $saleswithvat = 0;
	 $vatable      = 0;
	 $vat		   = 0;
	 $printctr	   = 0;
	 
	 $customercode = "";
	 $customername = "";
	 $customeraddress = "";
	 $customeribmcode = "";
	 $customertin     = "";
	 $availableCL = 0.00;
	
	 $custSelPrice = 0;
	 
	 $CFT 		   = 0;
	 $NCFT		   = 0;
	 $YTDCFT	 		 	= 0;
	 $ytdcft1 = 0;	 
	 $MTDNCFT = 0;
	 $mtdncft1 = 0;
	 
	 $MTDCFT = 0;
	 $mtdcft1 =0;
	 
	 
	$month = date("m"); 
	$year  = date("Y");
	$totalLessCPI = 0;
	 
	// $queryStr = $qrystr->SelectBranch();
	 
	// $rsBranch = $db->query($queryStr);
	$rsBranch = $sp->spSelectBranch($database, -2,''); 
	 if($rsBranch->num_rows){
		while ($row = $rsBranch->fetch_object()) {
			$permitno 	= $row->PermitNo;
			$branchname = $row->Name;
			$tinno 		= $row->TIN;
		 	$address    = $row->StreetAdd;
			$serversn   = $row->ServerSN;
			$branchcode = $row->Code;			
		}
	 }
	 

	

	 $rsSalesInvoice = $sp->spSelectSalesInvoiceByID($database,$siid); 
	 if($rsSalesInvoice->num_rows){
		while ($row = $rsSalesInvoice->fetch_object()) 
		{
			$salesinvoice 	= $row->TxnID;
			$salesinvoice   = "ALA7".str_pad($salesinvoice, 8, "0", STR_PAD_LEFT);
			$txndate 		= $row->TxnDate;
			$reftxnid 		= $row->RefTxnID;
			$reftxnid 		= "SO".str_pad($reftxnid, 8, "0", STR_PAD_LEFT);
		 	$customerid   	= $row->CustomerID;
			$grossamount    =$row->GrossAmount;
			$basicdisc		= $row->BasicDiscount;
			$vatamount		= $row->VatAmount;
			$adddiscprev 	= $row->AddtlDiscountPrev;
			$addDiscount =$row->AddtlDiscount;
			$totCPI = $row->TotalCPI;
			$netamount		= number_format($row->NetAmount,2);
			$saleswithvat   = $grossamount - $totCPI - $basicdisc - $addDiscount;
			$totalLessCPI	= $grossamount - $totCPI;
			$vatable		= $saleswithvat / 1.12;
			$vat			= $saleswithvat - $vatable;
			$totalCFT = $row->TotalCFT;
			$totalNCFT      = $row->TotalNCFT;
			$printctr		= $row->PrintCounter;
			$additionaldiscount = $row->AddtlDiscount;
			$prevadditionaldiscount = $row->AddtlDiscountPrev;
			$totgrossamt = $row->GrossAmount;
			$totalCPI = $row->TotalCPI;
			$ibm = $row->IBMCode;
		   $txnDateFormat = $row->TxnDate;
	      $tmpDate = strtotime($txnDateFormat);
	      $year_si = date("Y",$tmpDate);
			 
		}
	 }	 
	 
	 $totalLessCPI = $totgrossamt - $totalCPI;
	 $custSelPrice = $totalLessCPI+$totCPI;
	 
	$rsYTDCFT = $sp->spSelectYTDbyCustomerID($database, $customerid, $year_si, 1);
	if($rsYTDCFT->num_rows)
	{
		while($tmpYTDCFT = $rsYTDCFT->fetch_object())
		{
			$YTDCFT = $tmpYTDCFT->Amount;
		}
	}
	
	$rsMTDNCFT = $sp->spSelectMTDbyCustomerID($database,$customerid,$month,$year,2);
	if($rsMTDNCFT->num_rows)
	{
		while($tmpMTDNCFT = $rsMTDNCFT->fetch_object())
		{
			$MTDNCFT 	= $tmpMTDNCFT->Amount;
			$MTDNCTDisc = $tmpMTDNCFT->DiscountID;
			//echo $MTDNCFT;
		}
	}
	
	$rsMTDCFT = $sp->spSelectMTDbyCustomerID($database,$customerid,$month,$year,1);
	
	if($rsMTDCFT->num_rows)
	{
		while($tmpMTDCFT = $rsMTDCFT->fetch_object())
		{
			$MTDCFT		 = $tmpMTDCFT->Amount;
			$MTDCFTDisc	 = $tmpMTDCFT->DiscountID;
			//echo $MTDCFT;
		}
	}

	$mtdncft1 = $MTDNCFT + $totalNCFT;
	 $ytdcft1 = $YTDCFT + $totalCFT; 
	 
	 $mtdcft1 = $MTDCFT + $totalCFT;
	 $rsCreditLimit = $sp->spSelectCreditLimitByCustomerID($database,$customerid);
	  if($rsCreditLimit->num_rows){
		while ($row = $rsCreditLimit->fetch_object()) 
		{
			$creditLimit =$row->ApprovedCL;
		}
	  }
	  
	  
	$rsARBalance = $sp->spSelectARBalanceByCustomerID($database,$customerid);
	if($rsARBalance->num_rows)
	{
		while($ARBalance = $rsARBalance->fetch_object())
		{
			$arOutstandingbalance 	=$ARBalance->OutstandingAmount;
			
		}
	}	
	
	//Available Credit = CreditLimit - CLLess
	$availableCL  = $creditLimit - $totalLessCPI;		
	
	  $rsCustomer = $sp->spSelectCustomerDetails($database,$customerid); 
	  if($rsCustomer->num_rows){
		while ($row = $rsCustomer->fetch_object()) 
		{
			 $customercode = $row->Code;
			 $customername = $row->Name;
			 $customeribmcode = $row->IBMCode;
			 $customertin     = $row->TIN;
		}
	 }

	 $rsProdSI= $sp->spSelectProductSalesInvoice($database,$siid); 
	  if($rsProdSI->num_rows){
		while ($row = $rsProdSI->fetch_object()) 
		{
			 if ($row->PMGID == 1){
				$CFT += $row->UnitPrice;
				$CFT = number_format($CFT,2);		 
			 }
			 if ($row->PMGID == 2){
				$NCFT += $row->UnitPrice;
				$NCFT = number_format($NCFT,2);	 
			 }
		}
	 }	 
	 
	  $rsProdSI= $sp->spSelectProductSalesInvoice($database,$siid,''); 
	  
	  $rsMTDSI= $sp->spSelectMTDSI($database,$customerid); 
	  if($rsMTDSI->num_rows){
		while ($row = $rsMTDSI->fetch_object()) 
		{
			$mtdCustSelPrice=$row->TotalCFT +$row->TotalNCFT + $row->TotalCPI;
			$mtdBasicDisc = $row->BasicDiscount;
			$mtdAddlDisc = $row->AddtlDiscount;
			$mtdAddlDpp = $row->AddtlDiscountPrev;	
			$mtdiscGrSales =$mtdCustSelPrice-$mtdBasicDisc;
			
		}
	 }
	 
	 $rsUpcomingDues= $sp->spSelectSIUpcomingDues($database,$customerid,$siid); 
	

	 
?>