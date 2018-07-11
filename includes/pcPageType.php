<?php
	require_once("../initialize.php");
	global $database;
	$ksID = 0;

	if(isset($_POST['btnSave']))
	{		
		try
		{
			$database->beginTransaction();
			
			$code = htmlentities(addslashes($_POST['code']));
			$name = htmlentities(addslashes($_POST['name']));
			
			$affected_rows = $sp->spInsertPageType($database,$code, $name);
			if (!$affected_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}		
					
			$database->commitTransaction();
			$message = "Successfully saved Key Spread.";		
			redirect_to("../index.php?pageid=123&msg=$message");	
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=123&errmsg=$message");	
		}
	}
	elseif(isset($_POST['btnUpdate']))
	{
		try
		{
			$database->beginTransaction();
			$id = 0;
			$code = htmlentities(addslashes($_POST['code']));
			$name = htmlentities(addslashes($_POST['name']));
			
			if(isset($_POST['hdnProdTypeID']))
			{
				$id = $_POST['hdnProdTypeID'];			
			}		
			
			$affected_rows = $sp->spUpdatePageType($database, $id, $code, $name);
			if (!$affected_rows)
			{
					throw new Exception("An error occurred, please contact your system administrator.");
			}		
				
			$database->commitTransaction();
			$message = "Successfully updated Key Spread.";		
			redirect_to("../index.php?pageid=123&msg=$message");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=123&errmsg=$message");	
		}		
	}
	/*elseif(isset($_POST['btnDelete']))
	{
		$id = 0;
		$id = $_POST['hdnProdTypeID'];	

		$rs_checkProdType = $sp->spCheckProdType($database, $id);

		if($rs_checkProdType->num_rows)
		{
			$errorMessage = "Cannot delete record. There is a/are product(s) linked to this product type.";
			redirect_to("../index.php?pageid=22&errmsg=$errorMessage");	
		}
		else
		{	
			$affected_rows = $sp->spDeleteProdType($database, $id);
			
			$message = "Data Successfully Deleted.";		
			redirect_to("../index.php?pageid=22&msg=$message");	
		}	
	}*/			
?>