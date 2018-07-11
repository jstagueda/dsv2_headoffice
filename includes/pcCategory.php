<?php
	require_once("../initialize.php");

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	} */
   global $database;
	if(isset($_POST['btnSave']))
	{		
		$code = htmlentities(addslashes($_POST['code']));
		$name = htmlentities(addslashes($_POST['name']));
		$desc = htmlentities(addslashes($_POST['description']));
		
		$rs_existingCategory = $sp->spSelectExistingCategory($database,$code);
			
		if($rs_existingCategory->num_rows)
		{
			$errorMessage = "Code already exists.";
			redirect_to("../index.php?pageid=8&errmsg=$errorMessage");	
		}
		else
		{			
			$affected_rows = $sp->spInsertCategory($database,$code, $name, $desc);
			
			$message = "Successfully saved record.";		
			redirect_to("../index.php?pageid=8&msg=$message");	
		}
	}
	
	elseif(isset($_POST['btnDelete']))
	{
		$id = 0;
		$id = $_POST['hdnCategoryID'];	
				
		$rs_checkCategory = $sp->spCheckCategory($database,$id);

		if($rs_checkCategory->num_rows)
		{
			$errorMessage = "Cannot delete record. There is a/are product(s) linked to this category.";
			redirect_to("../index.php?pageid=8&errmsg=$errorMessage");	
		}
		else
		{
			$affected_rows = $sp->spDeleteCategory($database,$id);
			
			$message = "Successfully deleted record.";		
			redirect_to("../index.php?pageid=8&msg=$message");	
		}	
	}
	
	elseif(isset($_POST['btnUpdate']))
	{
		$id = 0;
		$code = htmlentities(addslashes($_POST['code']));
		$name = htmlentities(addslashes($_POST['name']));
		$desc = htmlentities(addslashes($_POST['description']));
		
		if(isset($_POST['hdnCategoryID'])){
			$id = $_POST['hdnCategoryID'];			
		}		
		
		$affected_rows = $sp->spUpdateCategory($database, $id, $code, $name, $desc);
		
		$message = "Successfully edited record.";		
		redirect_to("../index.php?pageid=8&msg=$message");		
	}
	
			
?>