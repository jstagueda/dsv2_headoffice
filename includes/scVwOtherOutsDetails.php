<?php

	/*if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}*/ 
	global $database;
	$txnid = $_GET['tid'];
	
	$rs_detailsall = $sp->spSelectInvOutDetailsByID($database, $txnid);

	$rs = $sp->spSelectInventoryOutByID($database,$txnid);
	
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{			
			$txnno = $row->TxnNo;
			$id = $row->TxnID;
			$docno = $row->DocumentNo;
			$txndate = $row->TxnDate;
			$warehouse = $row->Warehouse;
			$invouttype = $row->InvOutType;
			$invouttypeid = $row->InvOutTypeID;
			$status = $row->TxnStatus;
			$statid = $row->StatusID;
			$remarks = $row->Remarks;
			$wid = $row->WarehouseID;
		}
	}
			
?>