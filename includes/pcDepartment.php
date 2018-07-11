<?php
	require_once("../initialize.php");
   	global $database;
   	
	if(isset($_POST['btnSave']))
	{		
		$code = htmlentities(addslashes($_POST['code']));
		$name = htmlentities(addslashes($_POST['name']));		
		$deptlvlid = 1;
		$parentid = 0;
		$lt = 0;
		$rt = 0;
		
		$rs_ExistingDepartment = $sp->spSelectExistingDepartment($database, 0, $code);
	
		if($rs_ExistingDepartment->num_rows)
		{
			$errormessage = "Code already exists.";		
			redirect_to("../index.php?pageid=14&errmsg=$errormessage");	
		}
		else
		{
			$affected_rows = $sp->spInsertDepartment($database, $code, $name, $deptlvlid, $parentid, $lt, $rt);
		
			$message = "Successfully saved record.";		
			redirect_to("../index.php?pageid=14&msg=$message");	
		}
	}
	elseif(isset($_POST['btnDelete']))
	{

		$dID = 0;
		//$dID = $_POST['hdnDeptID'];	
				
		if(isset($_POST['hdnDeptID'])){
			$dID = $_POST['hdnDeptID'];			
		}		
		
		$rs_ExistingDeparmentEmployee = $sp->spDepartmentEmployee($database,$dID);
		
		if($rs_ExistingDeparmentEmployee-> num_rows)
		{
			$errormessage = "Cannot delete record. There is an/are employee(s) linked to this deparment.";
			redirect_to("../index.php?pageid=14&errmsg=$errormessage");
		}
		else
		{  
			$affected_rows = $sp->spDeleteDepartment($database, $dID);
		
			$message = "Successfully deleted records.";		
			redirect_to("../index.php?pageid=14&msg=$message");	
		}	
	}
	elseif(isset($_POST['btnUpdate']))
	{
		$id = 0;
		$code = htmlentities(addslashes($_POST['code']));
		$name = htmlentities(addslashes($_POST['name']));
		
		if($_POST['ParentID'] == ""){
			$ParentID = 0;
		}else{
			$ParentID = $_POST['ParentID'];
		}
		//$desc = htmlentities(addslashes($_POST['description']));
		
		if(isset($_POST['hdnDeptID']))
		{
			$id = $_POST['hdnDeptID'];			
		}
		
		//$rs_ExistingDepartment = $sp->spSelectExistingDepartment($database, 0, $code);
		$rs_ExistingDepartment = $sp->spSelectExistingDepartment($database, $id, $code);
		
		if($rs_ExistingDepartment->num_rows)
		{
			$errormessage = "Code already exists.";		
			redirect_to("../index.php?pageid=14&errmsg=$errormessage");	
		}
		else
		{
			$affected_rows = $sp->spUpdateDepartment($database, $id, $code, $name, $name, $ParentID);
	
			$message = "Successfully updated record.";		
			redirect_to("../index.php?pageid=14&msg=$message");		
		}
		
		//echo '$id = '.$id.'<BR/>$code = '.$code.'<BR/>$name = '.$name.'<BR/>$desc = '.$desc.'<BR/>';
	}
?>