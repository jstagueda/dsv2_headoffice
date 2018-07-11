<?php 

	require_once("../initialize.php");
	global $database;
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	
	if(isset($_POST['btnSave'])) 
	{
		try
		{
			$database->beginTransaction();
			$utid = 0;
		  	$utid = $_POST["cboUserType"];		
					  
		  	$affected_rows = $sp->spDeleteUserTypeModControl($database, $utid);
		  	if (!$affected_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			  
		  	foreach ($_POST['chkID'] as $key=>$value) 
		  	{
		  		$utid = $_POST["cboUserType"];
			  	$mcid = $value;
		  		$affected_rows = $sp->spInsertUserTypeModControl($database, $utid, $mcid);
		  		if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
	  		}
	  		
	  		$database->commitTransaction();
		  	$message = "Successfully saved record.";
		  	redirect_to("../index.php?pageid=16&msg=$message");
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=16&errmsg=$errmsg");
		}	
	}	
	
?>