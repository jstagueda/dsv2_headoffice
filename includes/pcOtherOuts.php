<?php

	require_once("../initialize.php");	
	global $database;
	$errmsg="";
	if (isset($_POST['btnSaveInv']))
	{		
		try
		{
		$database->beginTransaction();
		$documentno = htmlentities(addslashes($_POST["txtDocNo"])); 
		$txndate = $_POST["startDate"];
		$invouttypeid = $_POST["cboInventoryOutType"];
		$warehouseid = $_POST["hdnWarehouseID"];
		$totalqty =  $_POST["hdntotqty"];
		$remarks = htmlentities(addslashes($_POST["txtRemarks"])); 
		$prepearedby = $session->emp_id;
		$userid = $session->emp_id; 
		$createid = $session->createid;
		
		$affected_rows = $sp->spInsertInventoryOut($database, $documentno, $txndate, $invouttypeid, $warehouseid, $totalqty, $remarks, $prepearedby, $userid, $createid);
		if (!$affected_rows)
		{
		throw new Exception("An error occurred, please contact your system administrator.");
		}
		
		$database->commitTransaction();
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
		}	
	}
	
	$message = "Successfully created Inventory Out.";
	redirect_to("../index.php?pageid=31");
?>