<?php

	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	$reasonlist = "";
	//header
	$txnid = $_GET['tid'];
	$rs = $sp->spSelectTransferByID($database, $txnid);
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{			
			$txnno = $row->TxnNo;
			$id = $row->TxnID;
			$docno = $row->DocumentNo;
			$txndate = $row->TxnDate;
			$warehouse = $row->warehouseName;
			$swhouseid = $row->WarehouseID;
			$DestinationWarehouse = $row->DestinationWarehouse;
			$dwhouseid = $row->DestinationWarehouseID;
			$statid = $row ->statusid;			
			$remarks = $row->Remarks;
			$movement = $row->movementName;
			$movementID = $row->movementId;
			$branchid = $row->branchName;
			$createdby = $row->CreatedBy;
			$confirmedby = $row->ConfirmedBy;
			$status = $row->TxnStatus;
			$datemodified = $row->DateModified;
		}
	}
	//details
	$rs_detailsall = $sp->spSelectTransferDetailsByID($database, $txnid, $statid);
?>
