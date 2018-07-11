<?php
global $database;

	/*if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}*/ 
	
	$txnid = $_GET['TxnID'];
	
	$datetoday = date("m/d/Y");
		
	$rs_detailsall = $sp->spSelectDRByID($database,$txnid);
	
	$rs = $sp->spSelectDRByID($database,$txnid);
	
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{							
			$id = $row->ID;
			$txnno = $row->TxnNo;
			$docno = $row->DocumentNo;
			$reftxnid = $row->RefTxnID;
			$reftxnno = $row->RefTxnNo;
			$customerid = $row->CustomerID;
			$code = $row->CustomerCode;
			$name = $row->Customer;
			$termsid = $row->TermsID;
			$salesmanid = $row->SalesmanID;
			$txndate = $row->TxnDate;						
			$deliverydate = $row->DeliveryDate;
			$remarks = $row->Remarks;
			$preparedby = $row->PreparedBy;
			$confirmedby = $row->ConfirmedBy;
			$txnstatusid = $row->TxnStatusID;
			$warehouseid = $row->WarehouseID;
			$txnstatus = $row->TxnStatus;
			$employeeid = $row->EmployeeID;			
		}
	}

	
	// $rs_drproddetails = $sp->spSelectDRDetailsByID($database,$txnid);
	
	$rs_dpd = $sp->spSelectDRDetailsByID($database, $txnid);
	
	if ($rs_dpd->num_rows)
	{
		$cnt = 0;
		$tmpdisc1 = 0;
		$tmpdisc2 = 0;
		$tmpdisc3 = 0;
		while ($row = $rs_dpd->fetch_object())
		{
			$alt = $row->LineNo;
			$tmpdisc1 = $row->Discount1;
			$tmpdisc2 = $row->Discount2;
			$tmpdisc3 = $row->Discount3;
			$totgrossamt = $row->TotalGrossAmt;
			
			/*$cnt ++;
			($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
			$lineno = $row->LineNo;
			$productid = $row->ProductID;
			$drid = $row->DRID;
			$uomid = $row->UOMID;
			$uom = $row->UOM;
			$prodcode = $row->ProductCode;
			$prodname = $row->Product;
			$ordqty = number_format($row->OrdQty,0);
			$delqty = number_format($row->DelQty,0);
			$unitprice = $row->UnitPrice;
			$totalamt = $row->TotalAmount;
			$ispercentdisc = $row->IsPercentDiscount;
			$discount = number_format($row->discount,0);
			$disc1 = number_format($row->Discount1,0);
			$disc2 = number_format($row->Discount2,0);
			$disc3 = number_format($row->Discount3,0);*/		
		}
	}
			
?>