<?php

	require_once("../initialize.php");
	global $database;
	$errmsg="";
	if (isset($_POST['btnSaveInv']))
		{
			try
			{
			$documentno = htmlentities(addslashes($_POST["txtDocNo"])); 
			$txndate = $_POST["startDate"];
			$warehouseid = $_POST["hdnWarehouseID"];
			$classid = 1; 
			$itemtypeid = 1;
			$totalqty = 0;
			$remarks = htmlentities(addslashes($_POST["txtRemarks"])); 
			$prepearedby = $session->emp_id;
			$userid = $session->emp_id; 
			$createid = $session->createid;

			$affected_rows = $sp->spInsertInventoryCount($database, $documentno, $txndate, $warehouseid, $classid, $itemtypeid, $totalqty, $remarks, $prepearedby, $userid, $createid);
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
	redirect_to("../index.php?pageid=32");
?>