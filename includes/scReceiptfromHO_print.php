<?php
	global $database;
	$txnid = $_GET['tid'];
	$wid = 0;
	$search = "";
	
	$rs = $sp->spSelectInventoryInRHODetailsbyID($database,$txnid);
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{	
			$id = $row->TxnID;		
			$txnno = $row->TxnNo;
			$invtypeid = $row->InvType;
			$invtype = $row->MTName;		
			$docno = $row->DocNo;
			$sbrancid = $row->SBID;
			$shipmntvia = $row->ModeOfShipment;
			$wid = $row->WID;
			$shipmentdate = $row->ShipDate;
			$txndate = $row->TxnDate;
			$lastmoddate = $row->LastModifiedDate;
			$warehouse = $row->WarehouseName;
			$remarks = $row->Remarks;
		}
	}
	
	$rs_detailsall = $sp->spSelectInvInRHODetails($database, $search, $txnid, $wid);
?>