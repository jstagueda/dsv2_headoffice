<?php

	require_once("../initialize.php");

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	}*/ 
	
	global $database;
	if (isset($_POST['btnUpdate']))
	{		
		$id = $_POST["hdnAreaID"];
			
		$code = htmlentities(addslashes($_POST['txtfldCode']));
		$name = htmlentities(addslashes($_POST['txtfldName']));
		$arealevel = htmlentities(addslashes($_POST['cboAreaLevel']));
		$areaprnt = htmlentities(addslashes($_POST['cboParent']));
		
		$rs_existingArea = $sp->spSelectExistingArea($database, $id, trim($code));
		$rs_existingArea2 = $sp->spSelectExistingArea2($database,$id, trim($code));		
		
		if($rs_existingArea2 ->num_rows)
		{
			  $affected_rows = $sp->spUpdateArea($database,$id, $code, $name, $arealevel, $areaprnt);	
			  $message = "Successfully updated record.";
			  redirect_to("../index.php?pageid=77&msg=$message");
			
		}
		else
		{
		
	        if($rs_existingArea->num_rows)
			{
			  $errorMessage = "Code already exists.";
			  redirect_to("../index.php?pageid=77&errmsg=$errorMessage");	
			}
			else
			{
			  $affected_rows = $sp->spUpdateArea($database,$id, $code, $name, $arealevel, $areaprnt);	
			  $message = "Successfully updated record.";
			  redirect_to("../index.php?pageid=77&msg=$message");
			}
		}
		
	}
	else if(isset($_POST['btnSave'])) 
	{
		$code = htmlentities(addslashes($_POST['txtfldCode']));
		$name = htmlentities(addslashes($_POST['txtfldName']));
		$arealevel = htmlentities(addslashes($_POST['cboAreaLevel']));
		$areaprnt = htmlentities(addslashes($_POST['cboParent']));
			
		$rs_existingArea = $sp->spSelectExistingArea($database,0,trim($code));
			
		if($rs_existingArea->num_rows)
		{
			$errorMessage = "Code already exists.";
			redirect_to("../index.php?pageid=77&errmsg=$errorMessage");	
		}
		else
		{	
			$affected_rows = $sp->spInsertArea($database, $code, $name, $arealevel, $areaprnt);	
			
			$message = "Successfully saved record.";
			redirect_to("../index.php?pageid=77&msg=$message");	
		}	
		
	}	
	else if(isset($_POST['btnDelete'])) 
	{	
		$id = 0;
		$id = $_POST["hdnAreaID"];
		/*$rs_checkWarehouse = $sp->spCheckWarehouse($database,$id);*/

		/*if($rs_checkWarehouse->num_rows)
		{
			$errorMessage = "Cannot delete record. An employee is linked to this warehouse.";
			redirect_to("../index.php?pageid=5&errmsg=$errorMessage");	
		}
		else
		*/
		
		{	
			$affected_rows = $sp->spDeleteArea($database,$id);
			//$message = "Cannot delete record. There are employee(s) linked to this warehouse.";
			$message = "Successfully deleted record.";
			redirect_to("../index.php?pageid=77&msg=$message");	
		}	
	}

	
?>