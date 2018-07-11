<?php
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}	
	
	$txnid = $_GET['tid'];
	$warehouseid = 0;
	$rtid = 0;
	$search = "";
	$actQuantity = 0;
    global $database;
	$rs = $sp->spSelectInventoryOutIssueSTAHeaderbyID($database,$txnid);
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{	
			$id = $row->ID;		
			$txnno = $row->TxnNo;
			$invtype = $row->MTName;			
			$docno = $row->DocumentNo;
			$sbranchid = $row->SBID;
			$dbranchid = $row->DBID;
			$sbranch = $row->IssuingBranch;
			$dbranch = $row->ReceivingBranch;
			$datecreated = $row->DateCreated;
			$txndate = $row->TxnDate;
		}
	}
	
	if(isset($_POST['btnSearch']))
	{
		$search = addslashes($_POST['txtSearch']);	
	}	
	
	$rs_detailsall = $sp->spSelectInventoryOutIssueSTADetailsbyID($database,$txnid, $search);
	
	if(isset($_POST['btnCancel'])) 
	{
		redirect_to("index.php?pageid=27");	
	}
?>