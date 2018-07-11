<?php

	/*if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}*/ 
	global $database;
	$txnid = $_GET['tid'];
	
	$rs_detailsall = $sp->spSelectAdjDetailsByID($database, $txnid);

	$rs = $sp->spSelectAdjustmentByID($database, $txnid);
	
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{			
			$txnno = $row->TxnNo;
			$id = $row->TxnID;
			$docno = $row->DocumentNo;
			$txndate = $row->TxnDate;
			$warehouse = $row->Warehouse;
			$itemtype = $row->ItemType;
			$class = $row->Class;
			$status = $row->TxnStatus;
			$statid = $row->StatusID;
			$remarks = $row->Remarks;
			$wid = $row->WarehouseID;
		}
	}
			
?>