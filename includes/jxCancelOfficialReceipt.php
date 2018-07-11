<?php
	require_once "../initialize.php";
	global $database;
	
	$orid = $_POST['orId'];
   	$reasonId = $_POST['reasonId'];
   	$remarks = $_POST['remarks'];
   	$cancelledBy = $session->emp_id; 
 	
 	try
	{
		$database->beginTransaction();
		//check status of inventory
		$rs_freeze = $sp->spCheckInventoryStatus($database);
		if ($rs_freeze->num_rows)
		{
			while ($row = $rs_freeze->fetch_object())
			{
				$statusid_inv = $row->StatusID;			
			}		
		}
		else
		{
			$statusid_inv = 20;
		}
		
		if ($statusid_inv == 21)
		{
			$message = "Cannot save transaction, Inventory Count is in progress.";
			redirect_to("../index.php?pageid=96.1&msg=$message");				
		}
		else
		{
			$affected_rows = $sp->spUpdateOfficialReceiptCancel($database, $orid, $reasonId, $remarks, $cancelledBy);
			if (!$affected_rows)
			{ 
				throw new Exception("An error occurred, please contact your system administrator");
			}
			$database->commitTransaction();
		}
		
		//unlock transaction
		//try
		//{
			//$database->beginTransaction();
			$updatestatus = $sp->spUpdateLockStatus($database, 'officialreceipt', 0, 0, $orid);
			if (!$updatestatus)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			//$database->commitTransaction();			
		//}
		/*catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=96.1&msg=$errmsg&Txnid=$txnid");	
		}*/
	}
	catch (Exception $e)
	{
		$database->rollbackTransaction();
		$message = $e->getMessage();
		redirect_to("../index.php?pageid=96.1&msg=$message");
	}
?>
