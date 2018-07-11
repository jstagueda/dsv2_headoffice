<?php

	if (!ini_get('display_errors'))
 	{
 		ini_set('display_errors', 1);
 	}
 	
	global $database;
	
	$txnid = $_GET['tid'];
	$errmsg = "";
	$month = date("m"); 
	$year  = date("Y");
	$MTDCFT			 	= 0;
	$MTDCFTDisc  	 	= 0;
	$YTDCFT	 		 	= 0;
	$MTDNCFT 		 	= 0;
	$MTDNCTDisc 	 	= 0; 
	$YTDNCFT 		 	= 0;
 	$customeraddress = "";
 	$customercode = "";
	$customername ="";
	$customeribmcode = "";
	$customertin     ="";
	$creditLimit = 0;
	
 	$ytdCustSelPrice=0;
	$ytdBasicDisc = 0;
	$ytdAddlDisc = 0;
	$ytdAddlDpp = 0;	
	$ytdiscGrSales =0;	
	
	
	$qMTDCFT = 0;
	$qMTDNCFT = 0;
   	$qYTDCFT = 0;
    $qYTDNCFT =0;
    $qNextLevelCFT = 0;
	$qNextLevelNCFT = 0;
		  
	
	$rs_reason = $sp->spSelectReason($database,4, '');
	$rs_detailsall = $sp->spSelectSalesInvoiceDetailsByID($database,$txnid);
	$rs=$tpi->selectSalesInvoiceByID($database, $txnid);
	
	//retrieve latest bir series
	$rs_birseries = $sp->spSelectBIRSeriesByTxnTypeID($database, 7);
	if ($rs_birseries->num_rows)
	{
		while ($row = $rs_birseries->fetch_object())
		{
			$bir_series	= $row->Series;
			$bir_id = $row->NextID;			
		}		
	}
	else
	{
		$rs_branch = $sp->spSelectBranch($database, -2, "");
		if ($rs_branch->num_rows)
		{
			while ($row = $rs_branch->fetch_object())
			{
				$bir_series	= $row->Code."700000001";
				$bir_id = 1;				
			}
		}
	}
	
	if ($rs->num_rows)
	{
	   while ($row = $rs->fetch_object())
	   {
	      $sino = $row->SINo;
	      $id = $row->TxnID;
	      $docno = $row->DocumentNo;
	      $txndate = $row->SIDate;
	      $txnDateFormat = date("m/d/Y h:i A", strtotime($row->TxnDate));
	      //$txnDateFormat = $row->TxnDate;
	      $tmpDate = strtotime($txnDateFormat);
	      $year_si = date("Y",$tmpDate);
	      $month_si = date("m",$tmpDate);
	      $effectivitydate = $row->EffectivityDate;
	      $status = $row->TxnStatus;
	      $statid = $row->TxnStatusID;
	      $remarks = $row->Remarks;
        $rsAppliedPromoEntitlements=$tpi->getAppliedPromoEntitlements($row->SOID);
	      $refno = $row->RefNo;
		    $termsduration = $row->TermsDuration;
	      $drdate = $row->DRDate;
	      $custId = $row->CustomerID;
	      $custcode = $row->CustomerCode;
	      $custname = $row->CustomerName;
	      $terms = $row->Terms;
	      $outstandingBalance = $row->OutstandingBalance;
	      $totgrossamt = $row->GrossAmount;
	      $basicdiscount = $row->BasicDiscount;
	      $additionaldiscount = $row->AddtlDiscount;
	      $prevadditionaldiscount = $row->AddtlDiscountPrev;
	      $vatamt = $row->VatAmount;
	      $totalnetamt = $row->NetAmount;
	      $ibm = $row->IBM;
	      $totalCFT = $row->TotalCFT;
	      $totalNCFT = $row->TotalNCFT;
	      $totalCPI = $row->TotalCPI;
	      $ibmCode = $row->IBMCode;
		  $customerType = $row->CustomerTypeID;
		  $statusId = $row->StatusID;
		  $qMTDCFT =  $row->MTDCFT;
		  $qMTDNCFT = $row->MTDNCFT;
   		  $qYTDCFT = $row->YTDCFT;
    	  $qYTDNCFT = $row-> YTDNCFT;
    	  $qNextLevelCFT = $row->NextLevelCFT;
		  $qNextLevelNCFT = $row->NextLevelNCFT;
		  
		
	      //for printing
	      $printCnt = $row->PrintCounter;
	      $reftxnid 		= false?$row->RefTxnID:$row->SOID;
		  $reftxnid 		= "SO".str_pad($reftxnid, 8, "0", STR_PAD_LEFT);
	      $salesinvoice   = "".str_pad( $docno, 8, "0", STR_PAD_LEFT);
	   }
	}
	//echo $termsduration;

	$rsYTDCFT = $sp->spSelectYTDbyCustomerID($database, $custId, $year_si, 1);
	if($rsYTDCFT->num_rows)
	{
		while($tmpYTDCFT = $rsYTDCFT->fetch_object())
		{
			$YTDCFT = $tmpYTDCFT->Amount;
		}
	}

	$rsMTDCFT = $sp->spSelectMTDbyCustomerID($database,$custId,$month,$year,1);
				
	if($rsMTDCFT->num_rows)
	{
		while($tmpMTDCFT = $rsMTDCFT->fetch_object())
		{
			$MTDCFT		 = $tmpMTDCFT->Amount;
			$MTDCFTDisc	 = $tmpMTDCFT->DiscountID;
			//echo $MTDCFT;
		}
	}
	$rsMTDNCFT = $sp->spSelectMTDbyCustomerID($database,$custId,$month,$year,2);
				
	if($rsMTDNCFT->num_rows)
	{
		while($tmpMTDNCFT = $rsMTDNCFT->fetch_object())
		{
			$MTDNCFT 	= $tmpMTDNCFT->Amount;
			$MTDNCTDisc = $tmpMTDNCFT->DiscountID;
			//echo $MTDNCFT;
		}
	}
	$rsYTDNCFT = $sp->spSelectYTDbyCustomerID($database, $custId, $year_si, 2);
	if($rsYTDNCFT->num_rows)
	{
		while($tmpYTDNCFT = $rsYTDNCFT->fetch_object())
		{
			$YTDNCFT = $tmpYTDNCFT->Amount;
		}
	}
	
	//FOR Printing #########################################################################
	 $rsUpcomingDues= $sp->spSelectSIUpcomingDues($database,$custId,$txnid); 
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
	 
	  $rsCustomer = $sp->spSelectCustomerDetails($database,$custId); 
	  if($rsCustomer->num_rows){
		while ($row = $rsCustomer->fetch_object()) 
		{
			 $customercode = $row->Code;
			 $customername = $row->Name;
			 $customeribmcode = $row->IBMNo;
			 $customertin     = $row->TIN;
			 //echo "<pre>";
			 //print_r($row);
			 //echo "</pre>";
		}
	 }
	 if ($customercode=='')
	 {
	 	 $customercode = "N/A";
	 }
	 if ($customername=='')
	 {
	 	 $customername = "N/A";
	 }
	 if ($customeribmcode=='')
	 {
		 $customeribmcode = "N/A";	
		 
	 }
	 
	
	 if ($customertin=='')
	 {
			 $customertin = "N/A";
	 }
	 if ($customeraddress=='')
	 {
	 	 $customeraddress ="N/A";
	 }
	 
	  $rsProdSI= $sp->spSelectProductSalesInvoice($database,$txnid,''); 
	  
	  $rsCreditLimit = $sp->spSelectCreditLimitByCustomerID($database,$custId);
	  if($rsCreditLimit->num_rows)
	  {
		while ($row = $rsCreditLimit->fetch_object()) 
		{
			$creditLimit =$row->ApprovedCL;
		}
	  }
	  
	$rsARBalance = $sp->spSelectARBalanceByCustomerID($database,$custId);
	if($rsARBalance->num_rows)
	{
		while($ARBalance = $rsARBalance->fetch_object())
		{
			$arOutstandingbalance 	=$ARBalance->OutstandingAmount;
			
		}
	}	
	
	$availableCL  = $creditLimit - $arOutstandingbalance;	
	
	if($availableCL < 0)
	{
		$availableCL=0.00;	
	}
	
	
	  $rsMTDSI= $sp->spSelectMTDSI($database,$custId,$month_si,$year_si); 
	  if($rsMTDSI->num_rows)
	  {
		while ($row = $rsMTDSI->fetch_object()) 
		{
			//$mtdCustSelPrice=$row->TotalCFT +$row->TotalNCFT + $row->TotalCPI;
			$mtdCustSelPrice=$row->GrossAmount;
			$mtdBasicDisc = $row->BasicDiscount;
			$mtdAddlDisc = $row->AddtlDiscount;
			$mtdAddlDpp = $row->AddtlDiscountPrev;	
			$mtdiscGrSales =$mtdCustSelPrice-$mtdBasicDisc;
			
		}
	  }
	 
	 $rsYTDSI= $sp->spSelectYTDSI($database,$custId,$year_si); 
	  if($rsYTDSI->num_rows)
	  {
		while ($row = $rsYTDSI->fetch_object()) 
		{
			//$ytdCustSelPrice=$row->TotalCFT +$row->TotalNCFT + $row->TotalCPI;
			$ytdCustSelPrice=$row->GrossAmount;
			$ytdBasicDisc = $row->BasicDiscount;
			$ytdAddlDisc = $row->AddtlDiscount;
			$ytdAddlDpp = $row->AddtlDiscountPrev;	
			//$ytdiscGrSales =$ytdCustSelPrice-$ytdBasicDisc;
	
		}
	  }
	  
	  
	 if ($statusId ==6 )
	 {
	 	$v_mtdcft = $MTDCFT + $totalCFT ;
	 } 
	 else
	 {
	 	$v_mtdcft = $qMTDCFT ; 
	 }
	
	 if($statusId ==6)
	 {
	 	$v_mtdncft = $MTDNCFT +  $totalNCFT;
	 }
	 else
	 {
	 	$v_mtdncft = $qMTDNCFT;
	 }
	

	 //FOR Printing #########################################################################
?>