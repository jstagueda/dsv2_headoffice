<?php
	require_once("../initialize.php");

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	} */
    global $database;
	if(isset($_POST['btnSave']))
	{			
		$cd_code=htmlentities(addslashes($_POST['code']));
		$cd_name=htmlentities(addslashes($_POST['name']));
		$cd_address=htmlentities(addslashes($_POST['address']));
		$cd_txnDate=addslashes($_POST['txnDate']);
		
		$od_employeeID=addslashes($_POST['cboSalesman']);
		$od_outlettypeID=addslashes($_POST['cboOutletType']);
		$od_paytermsID=addslashes($_POST['cboTerms']);
		
		/*print_r($_POST);
		exit;*/
				
		$rs_ExistingCustomer = $sp->spSelectExistingCustomer($cd_code);
		if($rs_ExistingCustomer->num_rows)
		{
			$errormessage = "Code already exists.";		
			redirect_to("../index.php?pageid=23&errmsg=$errormessage");	
		}
		else
		{
			$affected_rows = $sp->spInsertCustomer($database,$cd_code, $cd_name, $cd_address, $cd_txnDate, $od_employeeID, $od_outlettypeID, $od_paytermsID);
			$message = "Successfully saved record.";		
			redirect_to("../index.php?pageid=23&msg=$message");	
		}

	}
	
	elseif(isset($_POST['btnDelete']))
	{
		$id = 0;
		$id = $_POST['hdnCustomerID'];	
				
		$affected_rows = $sp->spDeleteCustomer($database, $id);
		
		$message = "Successfully deleted record.";		
		redirect_to("../index.php?pageid=23&msg=$message");		
	}
	
	elseif(isset($_POST['btnUpdate']))
	{
		$id = 0;		
		$cd_code=htmlentities(addslashes($_POST['code']));
		$cd_name=htmlentities(addslashes($_POST['name']));
		$cd_address=htmlentities(addslashes($_POST['address']));
		$cd_txnDate=addslashes($_POST['txnDate']);
		
		$od_employeeID=addslashes($_POST['cboSalesman']);
		$od_outlettypeID=addslashes($_POST['cboOutletType']);
		$od_paytermsID=addslashes($_POST['cboTerms']);

		if(isset($_POST['hdnCustomerID'])){
			$id = $_POST['hdnCustomerID'];			
		}		
		
		$affected_rows = $sp->spUpdateCustomer($database, $id, $cd_code, $cd_name, $cd_address, $cd_txnDate, $od_employeeID, $od_outlettypeID, $od_paytermsID);
		
		$message = "Successfully edited record.";		
		redirect_to("../index.php?pageid=23&msg=$message");		
	}
	
			
?>