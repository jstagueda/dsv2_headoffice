<?php

	require_once("../initialize.php");

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	}*/ 
	
	global $database;
	if (isset($_POST['btnUpdate']))
	{		
		$id = $_POST["hdnWarehouseID"];
			
		$code = htmlentities(addslashes($_POST['txtfldCode']));
		$name = htmlentities(addslashes($_POST['txtfldName']));
		
		$rs_existingWarehouse = $sp->spSelectExistingWarehouse($database, 0, trim($code));
		$rs_existingWarehouse2 = $sp->spSelectExistingWarehouse2($database, $id, trim($code));		
		
		if($rs_existingWarehouse2 ->num_rows)
		{
			  $affected_rows = $sp->spUpdateWarehouse($database,$id, $code, $name);	
			  $message = "Successfully updated record.";
			  redirect_to("../index.php?pageid=5&msg=$message");
			
		}
		else
		{
		
	        if($rs_existingWarehouse->num_rows)
			{
			  $errorMessage = "Code already exists.";
			  redirect_to("../index.php?pageid=5&errmsg=$errorMessage");	
			}
			else
			{
			  $affected_rows = $sp->spUpdateWarehouse($database,$id, $code, $name);	
			  $message = "Successfully updated record.";
			  redirect_to("../index.php?pageid=5&msg=$message");
			}
		}
		
	}
	else if(isset($_POST['btnSave'])) 
	{
		$code = htmlentities(addslashes($_POST['txtfldCode']));
		$name = htmlentities(addslashes($_POST['txtfldName']));
		
		$rs_existingWarehouse = $sp->spSelectExistingWarehouse($database,0,trim($code));
			
		if($rs_existingWarehouse->num_rows)
		{
			$errorMessage = "Code already exists.";
			redirect_to("../index.php?pageid=5&errmsg=$errorMessage");	
		}
		else
		{	
			$affected_rows = $sp->spInsertWarehouse($database, $code, $name);	
			
			$message = "Successfully saved record.";
			redirect_to("../index.php?pageid=5&msg=$message");	
		}	
		
	}	
	else if(isset($_POST['btnDelete'])) 
	{	
		$id = 0;
		$id = $_POST["hdnWarehouseID"];
		/*$rs_checkWarehouse = $sp->spCheckWarehouse($database,$id);*/

		/*if($rs_checkWarehouse->num_rows)
		{
			$errorMessage = "Cannot delete record. An employee is linked to this warehouse.";
			redirect_to("../index.php?pageid=5&errmsg=$errorMessage");	
		}
		else
		*/
		
		{	
			$affected_rows = $sp->spDeleteWarehouse($database, $id);
			//$message = "Cannot delete record. There are employee(s) linked to this warehouse.";
			$message = "Successfully deleted record.";
			redirect_to("../index.php?pageid=5&msg=$message");	
		}	
	}

	
?>