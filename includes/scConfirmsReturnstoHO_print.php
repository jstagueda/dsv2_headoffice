<?php
global $database;
    $txnid = $_GET['tid'];
	$wid = 0;
	$search = "";

	$invtype=''; $txndate=''; $txnno=''; $sbranch=''; $rbranch=''; $docno=''; $remarks=''; $strTable=''; 
	$rs = $sp->spSelectInventoryInSTADetailsbyID($database, $txnid);
	
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{	
			$id = $row->TxnID;		
			$txnno = $row->TxnNo;
			$invtypeid = $row->InvType;
			$invtype = $row->MTName;		
			$docno = $row->DocNo;
			$bbrancid = $row->SBID;
			$sbranch = $row->IssuingBranch;
			$sbrancid = $row->RBID;
			$rbranch = $row->ReceivingBranch;
			$wid = $row->WID;
			//$datecreated = $row->DateCreated;
			$txndate = $row->TxnDate;
			$warehouse = $row->WarehouseName;
			$remarks = $row->Remarks;
		}
	}
	
	$rs_detailsall = $sp->spSelectInvInSTADetails($database,$search,$txnid,$wid);
?>