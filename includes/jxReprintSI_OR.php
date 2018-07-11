<?php

	require_once "../initialize.php";
	global $database;
	
	$txnId = $_POST['txnId'];
   	$table = $_POST['table'];
   	
   	//unlock transaction
	/*try
	{
		$database->beginTransaction();*/
		$updatestatus = $sp->spUpdateLockStatus($database, $table, 0, 0, $txnId);
		if (!$updatestatus)
		{
			throw new Exception("An error occurred, please contact your system administrator.");
		}
		
		$queryStr = $qrystr->UpdateSalesInvoiceOfficialReceiptPrintCounter($database, $txnId, $table);
   		$db->query($queryStr);
   
	/*	$database->commitTransaction();			
	}
	catch (Exception $e)
	{
		$database->rollbackTransaction();	
		$errmsg = $e->getMessage()."\n";
		redirect_to("index.php?pageid=40.1&msg=$errmsg&txnid=$txnid");	
	}*/
?>   