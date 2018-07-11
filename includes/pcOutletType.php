<?php
	require_once("../initialize.php");

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	} */
    global $database;
	if(isset($_POST['btnSave']))
	{		
		$code = addslashes($_POST['code']);
		$name = addslashes($_POST['name']);		
		
		$rs_ExistingOutletType = $sp->spSelectExistingOutletType($database, $code);
		
		if($rs_ExistingOutletType->num_rows)
		{
			$errormessage = "Code already exists.";		
			redirect_to("../index.php?pageid=24&errmsg=$errormessage");	
		}
		else
		{
			$affected_rows = $sp->spInsertOutletType($database, $code, $name);
		
			$message = "Successfully saved record.";		
			redirect_to("../index.php?pageid=24&msg=$message");	
		}
	}
	
	elseif(isset($_POST['btnDelete']))
	{
		$id = 0;
		$id = $_POST['hdnOutletType'];	

		$rs_checkOutletType = $sp->spCheckOutletType($database,$id);

		if($rs_checkOutletType->num_rows)
		{
			$errorMessage = "Cannot delete record. There is a/are customer(s) linked to this outlet type.";
			redirect_to("../index.php?pageid=24&errmsg=$errorMessage");	
		}
		else
		{	
			$affected_rows = $sp->spDeleteOutletType($database, $id);
			
			$message = "Successfully deleted record.";		
			redirect_to("../index.php?pageid=24&msg=$message");	
		}	
	}
	
	elseif(isset($_POST['btnUpdate']))
	{
		$id = 0;
		$code = addslashes($_POST['code']);
		$name = addslashes($_POST['name']);		
		
		if(isset($_POST['hdnOutletType'])){
			$id = $_POST['hdnOutletType'];			
		}		
		
		$affected_rows = $sp->spUpdateOutletType($database, $id, $code, $name);
		
		$message = "Successfully updated record.";		
		redirect_to("../index.php?pageid=24&msg=$message");		
	}
	
			
?>