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
		$siid = 0;
		$invintypeid = $_POST["cboInventoryInType"];
		$warehouseid = $_POST["hdnWarehouseID"];
		$returntypeid = 1;
		$reasonid = 1;
		$totalqty =  $_POST["hdntotqty"];;
		$remarks = htmlentities(addslashes($_POST["txtRemarks"])); 
		$prepearedby = $session->emp_id;
		$userid = $session->emp_id; 
		$createid = $session->createid;
		
		$affected_rows = $sp->spInsertInventoryIn($database,$documentno, $txndate, $siid, $invintypeid, $warehouseid, $returntypeid, $reasonid, $totalqty, $remarks, $preparedby, $userid, $createid);
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
	
	$message = "Successfully created Inventory In.";
	redirect_to("../index.php?pageid=30");
?>