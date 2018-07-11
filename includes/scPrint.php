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

	 $customercode = "";
	 $customername = "";
	 $customeraddress = "";
	 $customeribmcode = "";
	 $customertin     = "";
	 $totalqty        = 0;
	 $grandtotal      = 0;
	 
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

	 $rsSalesInvoice = $sp->spSelectSalesInvoiceByIDPDF($database,$siid); 
	 if($rsSalesInvoice->num_rows){
		while ($row = $rsSalesInvoice->fetch_object()) 
		{
			$salesinvoice 	= $row->SalesInvoiceID;
			$salesinvoice   = "ALA7".str_pad($salesinvoice, 8, "0", STR_PAD_LEFT);
			$txndate 		= $row->TxnDate;
			$reftxnid 		= $row->RefTxnID;
			$reftxnid 		= "SO".str_pad($reftxnid, 8, "0", STR_PAD_LEFT);
		 	$customerid   	= $row->CustomerID;
		}
	 }
	 
	  $rsCustomer = $sp->spSelectCustomerDetails($database,$customerid); 
	  if($rsCustomer->num_rows){
		while ($row = $rsCustomer->fetch_object()) 
		{
			 $customercode 	  = $row->Code;
			 $customername 	  = $row->Name;
			 $customeribmcode = $row->IBMCode;
			 $customertin     = $row->TIN;
		}
	 }

	  $rsProdSI= $sp->spSelectProductSalesInvoice($database,$siid,''); 
	 
	 
?>