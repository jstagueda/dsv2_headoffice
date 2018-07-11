<?php 
/* 
 *  Modified by: marygrace cabardo 
 *  10.09.2012
 *  marygrace.cabardo@gmail.com
 */
	require_once("../initialize.php");
   	global $database;
   	
   	if(isset($_POST['hdnBankID']))
	{
		$bID = $_POST['hdnBankID'];
	}
	else
	{
		$bID = 0;
	}
	
	$code    = htmlentities(addslashes($_POST['code']));
	$name    = htmlentities(addslashes($_POST['name']));
	$account = htmlentities(addslashes($_POST['account']));
    $branch  = htmlentities(addslashes($_POST['branch']));
   	
	if(isset($_POST['btnSave']))
	{
		$rs_ExistingBank = $sp->spSelectExistingBank($database, 0, $code);
		if($rs_ExistingBank->num_rows)
		{
			$errormessage = "Bank already exists.";		
			redirect_to("../index.php?pageid=152&errmsg=$errormessage");	
		}
		else
		{
			$rs_bankid = $sp->spInsertUpdateBank($database, $code, $name, $account, $bID, $branch);
			$message = "Successfully saved record.";		
			redirect_to("../index.php?pageid=152&msg=$message");	
		}
	}
	elseif(isset($_POST['btnUpdate']))
	{
		$rs_ExistingBank = $sp->spSelectExistingBank($database, $bID, $code);
		if($rs_ExistingBank->num_rows)
		{
			$errormessage = "Bank already exists.";		
			redirect_to("../index.php?pageid=152&errmsg=$errormessage");	
		}
		else
		{
			$affected_rows = $sp->spInsertUpdateBank($database, $code, $name, $account, $bID, $branch);
			$message = "Successfully updated record.";		
			redirect_to("../index.php?pageid=152&msg=$message");		
		}
	}
	elseif(isset($_POST['btnDelete']))
	{		
			$id = 0;
		    $id = $_POST['hdnBankID'];	
			$affected_rows = $sp->spDeleteExistingBank($database, $id);
			$message = "Successfully deleted record.";		
			redirect_to("../index.php?pageid=152&msg=$message");			
	}
?>