<?php

require_once("../initialize.php");

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	}*/ 
global $database;
if(isset($_POST['btnSave'])) 
{
		$code = htmlentities(addslashes($_POST['txtfldcode']));
		$name = htmlentities(addslashes($_POST['txtfldname']));
		$desc = htmlentities(addslashes($_POST['txtflddesc']));
		$duration = htmlentities(addslashes($_POST['txtflddur']));
		$ttid = addslashes($_POST['cboTermsType']);
		
		$rs_existingPaymentTerms = $sp->spSelectExistingPaymentTerms($database, $code);
			
		if($rs_existingPaymentTerms->num_rows)
		{
			$errorMessage = "Code already exists.";
			redirect_to("../index.php?pageid=19&errmsg=$errorMessage");	
		}
		else
		{	
			$affected_rows = $sp->spInsertPaymentTerms($database, $code, $name, $desc, $duration, $ttid);	
			
			$message = "Successfully saved record.";
			redirect_to("../index.php?pageid=19&msg=$message");	
		}	
} 
elseif (isset($_POST['btnUpdate'])) {
		
		$id = $_POST["hdnTermsID"];
		$code = htmlentities(addslashes($_POST['txtfldcode']));
		$name = htmlentities(addslashes($_POST['txtfldname']));
		$desc = htmlentities(addslashes($_POST['txtflddesc']));
		$duration = htmlentities(addslashes($_POST['txtflddur']));
		$ttid = addslashes($_POST['cboTermsType']);

		$affected_rows = $sp->spUpdatePaymentTerms($database, $id, $code, $name, $desc, $duration, $ttid);
		
		$message = "Successfully updated record.";
		redirect_to("../index.php?pageid=19&msg=$message");
					   
}
elseif (isset($_POST['btnDelete']))
{	
		$id = 0;	
	
		$id = $_POST["hdnTermsID"];

		$rs_checkPaymentTerms = $sp->spCheckPaymentTerms($id);

		if($rs_checkPaymentTerms->num_rows)
		{
			$errorMessage = "Cannot delete record. There is a/are customer(s) linked to this payment term.";
			redirect_to("../index.php?pageid=19&errmsg=$errorMessage");	
		}
		else
		{	
			$affected_rows = $sp->spDeletePaymentTerms($database, $id);
			
			$message = "Successfully deleted record.";
			redirect_to("../index.php?pageid=19&msg=$message");
		}
}



		
		
?>