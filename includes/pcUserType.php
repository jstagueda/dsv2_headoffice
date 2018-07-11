<?php

	require_once("../initialize.php");
	global $database;
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}		
		
	if (isset($_POST['btnUpdate']))
	{
		try
		{
			$database->beginTransaction();
			
			$id = 0;
			if (isset($_POST["btnUpdate"]))
			{
				$id = $_POST["hdnUserTypeID"];
			}
			
			$code = htmlentities(addslashes($_POST['txtfldCode']));
			$name = htmlentities(addslashes($_POST['txtfldName']));
			
			$rs_existingUserType = $sp->spSelectExistingUserType($database, $id, $code);
			if($rs_existingUserType->num_rows)
			{
				$errormessage = "Code already exists.";
				redirect_to("../index.php?pageid=11&errmsg=$errormessage");
			}
			else
			{
				$affected_rows = $sp->spUpdateUserType($database, $id, $code, $name);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				$database->commitTransaction();	
				$message = "Successfully updated record.";
				redirect_to("../index.php?pageid=11&msg=$message");				
			}		
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=11&errmsg=$errmsg");
		}
	}
	else if(isset($_POST['btnSave'])) 
	{
		try
		{
			$database->beginTransaction();
			
			$id = 0;
			if (isset($_GET["utid"]))
			{
				$id = $_GET["utid"];
			}
			
			$code = htmlentities(addslashes($_POST['txtfldCode']));
			$name = htmlentities(addslashes($_POST['txtfldName']));
			
			$rs_existingUserType = $sp->spSelectExistingUserType($database, $id, $code);
	
			if($rs_existingUserType->num_rows)
			{
				$errormessage = "Code already exists.";
				redirect_to("../index.php?pageid=11&errmsg=$errormessage");
			}
			else
			{
				$affected_rows = $sp->spInsertUserType($database, $code, $name);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				$database->commitTransaction();	
			
				$message = "Successfully saved record.";
				redirect_to("../index.php?pageid=11&msg=$message");		
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=11&errmsg=$errmsg");
		}
	}	
	else if(isset($_POST['btnDelete'])) 
	{
		try
		{
			$database->beginTransaction();
			$id = $_POST["hdnUserTypeID"];
			$affected_rows = $sp->spDeleteUserType($database, $id);
			if (!$affected_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			$database->commitTransaction();	
			
			$message = "Successfully deleted record.";
			redirect_to("../index.php?pageid=11&msg=$message");
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=11&errmsg=$errmsg");
		}
	}
	
?>