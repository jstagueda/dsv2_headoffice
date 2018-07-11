<?php
	require_once("../initialize.php");
	global $database;
	$ksID = 0;

	if(isset($_POST['btnSave']))
	{		
		try
		{
			$database->beginTransaction();
			
			$affected_rows = $sp->spInsertNetFactor($database, $_POST['cboPMG'], $_POST['txtNetFactor'], date("Y-m-d", strtotime( $_POST['txtStartDate'])), date("Y-m-d", strtotime( $_POST['txtEndDate'])));
			if (!$affected_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}		
					
			$database->commitTransaction();
			$message = "Successfully saved Net Factor.";		
			redirect_to("../index.php?pageid=124&msg=$message");	
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=124&errmsg=$message");	
		}
	}
?>