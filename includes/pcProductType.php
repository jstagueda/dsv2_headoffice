<?php
	require_once("../initialize.php");
	global $database;
	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	} */

	if(isset($_POST['btnSave']))
	{		
		try
		{
			$database->beginTransaction();
			$code = htmlentities(addslashes($_POST['code']));
			$name = htmlentities(addslashes($_POST['name']));
			$desc = htmlentities(addslashes($_POST['description']));
			
			$rs_existingProdType = $sp->spSelectExistingProductType($database, $code);
			
				
			if($rs_existingProdType->num_rows)
			{
				$database->commitTransaction();
				$errorMessage = "Code already exists.";
				redirect_to("../index.php?pageid=22&errmsg=$errorMessage");	
			}
			else
			{		
				$affected_rows = $sp->spInsertProdType($database,$code, $name);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}		
						
				$database->commitTransaction();
				$message = "Data Successfully Saved.";		
				redirect_to("../index.php?pageid=22&msg=$message");	
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=22&errmsg=$message");	
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
	
	elseif(isset($_POST['btnUpdate']))
	{
		try
		{
			$database->beginTransaction();
			$id = 0;
			$code = htmlentities(addslashes($_POST['code']));
			$name = htmlentities(addslashes($_POST['name']));
			/*$desc = htmlentities(addslashes($_POST['description']));*/
			
			if(isset($_POST['hdnProdTypeID'])){
				$id = $_POST['hdnProdTypeID'];			
			}		
			
			$affected_rows = $sp->spUpdateProdType($database, $id, $code, $name);
			if (!$affected_rows)
			{
					throw new Exception("An error occurred, please contact your system administrator.");
			}		
				
			$database->commitTransaction();
			$message = "Data Successfully Updated.";		
			redirect_to("../index.php?pageid=22&msg=$message");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=22&errmsg=$message");	
		}		
	}
	
			
?>