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
		
		$rs_existingBrand = $sp->spSelectExistingBrand($database, $code);
			
		if($rs_existingBrand->num_rows)
		{
			$errorMessage = "Code already exists.";
			redirect_to("../index.php?pageid=7&errmsg=$errorMessage");	
		}
		else
		{		
			$affected_rows = $sp->spInsertBrand($database, $code, $name, $desc);
		
			$message = "Successfully saved record.";		
			redirect_to("../index.php?pageid=7&msg=$message");	
		}
	}
	
	elseif(isset($_POST['btnDelete']))
	{
		$id = 0;
		$id = $_POST['hdnBrandID'];	
		
		$rs_checkBrand = $sp->spCheckBrand($database, $id);

		if($rs_checkBrand->num_rows)
		{
			$errorMessage = "Cannot delete record. There is a/are product(s) linked to this brand.";
			redirect_to("../index.php?pageid=7&errmsg=$errorMessage");	
		}
		else
		{	
			$affected_rows = $sp->spDeleteBrand($database, $id);
			
			$message = "Successfully deleted record.";		
			redirect_to("../index.php?pageid=7&msg=$message");	
		}	
	}
	
	elseif(isset($_POST['btnUpdate']))
	{
		$id = 0;
		$code = htmlentities(addslashes($_POST['code']));
		$name = htmlentities(addslashes($_POST['name']));
		$desc = htmlentities(addslashes($_POST['description']));
		
		if(isset($_POST['hdnBrandID'])){
			$id = $_POST['hdnBrandID'];			
		}		
		
		$affected_rows = $sp->spUpdateBrand($database, $id, $code, $name, $desc);
		
		$message = "Successfully edited record.";		
		redirect_to("../index.php?pageid=7&msg=$message");		
	}
	
			
?>