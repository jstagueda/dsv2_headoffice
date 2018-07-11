<?php
	require_once("../initialize.php");
	global $database;
	
	if(isset($_POST['btnSave'])) 
	{
		$code = htmlentities(addslashes($_POST['txtCodePosition']));
		$pname = htmlentities(addslashes($_POST['txtPosition']));

		$rs_ExistingPosition = $sp->spSelectExistingPosition($database,0, $code);		
		if($rs_ExistingPosition->num_rows)
		{
			$errormessage = "Code already exists.";
			redirect_to("../index.php?pageid=153&errmsg=$errormessage");	
		}
		else
		{
			$affected_rows = $sp->spInsertPosition($database, $code, $pname);	
			$message = "Successfully saved record.";
			redirect_to("../index.php?pageid=153&msg=$message");		
		}
	}
	elseif (isset($_POST['btnUpdate']))
	{
		$code = htmlentities(addslashes($_POST['txtCodePosition']));
		$pname = htmlentities(addslashes($_POST['txtPosition']));
		$id = $_POST["hdnPosID"];
	
		$rs_ExistingPosition = $sp->spSelectExistingPosition($database,$id,$code);
		$rs_ExistingPosition2 = $sp->spSelectExistingPosition2($database,$id,$code);
		
		if($rs_ExistingPosition2->num_rows)
		{
			$affected_rows = $sp->spUpdatePosition($database,$id, $code, $pname);
			$message = "Successfully updated record.";
			redirect_to("../index.php?pageid=153&msg=$message");
		}
		else
		{	
			if($rs_ExistingPosition->num_rows)
			{
				$errorMessage = "Code already exists.";
			  	redirect_to("../index.php?pageid=153&errmsg=$errorMessage");	
			}
			else
			{
			 	$affected_rows = $sp->spUpdatePosition($database,$id, $code, $pname);
				$message = "Successfully updated record.";
				redirect_to("../index.php?pageid=153&msg=$message");
			}
		}	
	}
	else if(isset($_POST['btnDelete'])) 
	{	
		$id = 0;
		$id = $_POST["hdnPosID"];

		$affected_rows = $sp->spDeletePosition($database,$id);
		$message = "Successfully deleted record.";
		redirect_to("../index.php?pageid=153&msg=$message");	
	}
?>