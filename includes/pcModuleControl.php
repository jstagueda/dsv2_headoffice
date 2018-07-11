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
			
			$id = 0;
			$id = $_POST["hdnModControlID"];
			
			$modid = $_POST["cboModule"];
			$subid = $_POST["cboSubModule"];
			
			$code = htmlentities(addslashes($_POST['txtfldCode']));
			$name = htmlentities(addslashes($_POST['txtfldName']));	
			$desc = htmlentities(addslashes($_POST['txtfldDesc']));	
			$pageno = htmlentities(addslashes($_POST['txtfldPageNo']));
			
			$rs_existingModuleControl = $sp->spSelectExistingModuleControl ($database, $modid, $subid, trim($code), $id, 2);
			
			if($rs_existingModuleControl->num_rows)
			{
				$database->beginTransaction();
				$affected_rows = $sp->spUpdateModuleControl($database, $id, $code, $name, $desc, $pageno);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				$database->commitTransaction();	
			
				$message = "Successfully updated record.";
				redirect_to("../index.php?pageid=13&msg=$message");
			}
			else
			{
				$errormessage = "Module Control already exists.";
				redirect_to("../index.php?pageid=13&errmsg=$errormessage");		
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=13&errmsg=$errmsg");
		}
	}
	else if(isset($_POST['btnSave'])) 
	{
		try
		{
			$database->beginTransaction();
			$modid = $_POST["cboModule"];
			$subid = $_POST["cboSubModule"];
	
			$code = htmlentities(addslashes($_POST['txtfldCode']));
			$name = htmlentities(addslashes($_POST['txtfldName']));	
			$desc = htmlentities(addslashes($_POST['txtfldDesc']));
			$pageno = htmlentities(addslashes($_POST['txtfldPageNo']));
	
			$rs_existingModuleControl = $sp->spSelectExistingModuleControl ($database, $modid, $subid, trim($code), 0, 1);
	
			if($rs_existingModuleControl->num_rows)
			{
				$errormessage = "Module Control already exists.";
				redirect_to("../index.php?pageid=13&errmsg=$errormessage");		
			}
			else
			{
				$affected_rows = $sp->spInsertModuleControl($database, $code, $modid, $subid, $name, $desc, $pageno);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				$database->commitTransaction();	
						
				$message = "Successfully saved record.";
				redirect_to("../index.php?pageid=13&msg=$message");		
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=13&errmsg=$errmsg");
		}		
	}
	else if(isset($_POST['btnDelete'])) 
	{
		try
		{
			$database->beginTransaction();
			$id = 0;
			$id = $_POST["hdnModControlID"];
	
			$affected_rows = $sp->spDeleteModuleControl($database, $id);
			if (!$affected_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			$database->commitTransaction();	
			
			$message = "Successfully deleted record.";
			redirect_to("../index.php?pageid=13&msg=$message");
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=13&errmsg=$errmsg");
		}
	}
	
?>