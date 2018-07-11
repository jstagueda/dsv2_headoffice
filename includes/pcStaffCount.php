<?php
	require_once("../initialize.php");
	global $database;
	$ksID = 0;

	if(isset($_POST['btnSave']))
	{		
		try
		{
			$database->beginTransaction();
			$affected_rows = $sp->spInsertStaffCount($database, $_POST['cboCampaign'], $_POST['txtStaffCount'], $_POST['txtActiveCount'], $_GET['txnID']);
			if (!$affected_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}		
					
			$database->commitTransaction();
			$message = "Successfully saved Staff and Active Count.";		
			redirect_to("../index.php?pageid=125&msg=$message");	
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=125&errmsg=$message");	
		}
	}
?>