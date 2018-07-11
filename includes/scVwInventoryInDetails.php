<?php

	/*if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}*/ 
	global $database;
	$txnid = $_GET['tid'];
	
	$rs_detailsall = $sp->spSelectInvInDetailsByID($database, $txnid);

	$rs = $sp->spSelectInventoryInByID($database,$txnid);
	
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{			
			$txnno = $row->TxnNo;
			$id = $row->TxnID;
			$docno = $row->DocumentNo;
			$txndate = $row->TxnDate;
			$warehouse = $row->Warehouse;
			$invintype = $row->InvInType;
			$invintypeid = $row->InvInTypeID;
			$status = $row->TxnStatus;
			$statid = $row->StatusID;
			$remarks = $row->Remarks;
			$wid = $row->WarehouseID;
		}
	}
			
?>