<?php

require_once("../initialize.php");

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	} */
	global $database;
	
if (isset($_POST['btnUpdate'])){
		
		$id = 0;
			if (isset($_POST['hdnProdClassID'])){
				$id = $_POST['hdnProdClassID'];
			}

		$code = htmlentities(addslashes($_POST['txtfldcode']));
		$name = htmlentities(addslashes($_POST['txtfldname']));
		$description = htmlentities(addslashes($_POST['txtflddesc']));
		$rs_ExistingProductClass = $sp->spSelectExistingProductClass($database,0, $code);
		$rs_ExistingProductClass2=$sp->spSelectExistingProductClass2($database,$id, $code);
		
		if($rs_ExistingProductClass2->num_rows)
		{
			$affected_rows = $sp->spUpdateProductClass($database,$id, $code, $name, $description);	
	
			$message = "Successfully updated record.";
			redirect_to("../index.php?pageid=6&msg=$message");
		}
		else
		{
			if($rs_ExistingProductClass->num_rows)
			{
				$errormessage = "Code already exists.";
				redirect_to("../index.php?pageid=6&errmsg=$errormessage");		
			}
			else
			{
				$affected_rows = $sp->spUpdateProductClass($database,$id, $code, $name, $description);		
		
				$message = "Successfully updated record.";
				redirect_to("../index.php?pageid=6&msg=$message");	
			}

		}
}
else if(isset($_POST['btnSave'])) {

		
		$code = htmlentities(addslashes($_POST['txtfldcode']));
		$name = htmlentities(addslashes($_POST['txtfldname']));
		$description = htmlentities(addslashes($_POST['txtflddesc']));
		
		$rs_ExistingProductClass = $sp->spSelectExistingProductClass($database,0, $code);
		if($rs_ExistingProductClass->num_rows)
		{
			$errormessage = "Code already exists.";
			redirect_to("../index.php?pageid=6&errmsg=$errormessage");		
		}
		else
		{
			$affected_rows = $sp->spInsertProductClass($database, $code, $name, $description);	
		
			$message = "Successfully saved record.";
			redirect_to("../index.php?pageid=6&msg=$message");		
		}
}

else if(isset($_POST['btnDelete'])) {
	
		$id = $_POST["hdnProdClassID"];
//		
//		$code = addslashes($_POST['txtfldcode']);
//		$name = addslashes($_POST['txtfldname']);
//		$description = addslashes($_POST['txtflddesc']);

		$rs_ProductClassProduct = $sp->spProductClassProduct($database,$id);
		
		if($rs_ProductClassProduct->num_rows)
		{
			$errormessage = "Cannot delete record. There is a/are product(s) linked to this product class.";
			redirect_to("../index.php?pageid=6&errmsg=$errormessage");	
		}
		else
		{
			$affected_rows = $sp->spDeleteProductClass($database,$id);	
		
			$message = "Successfully deleted record.";
			redirect_to("../index.php?pageid=6&msg=$message");	
		}	
}


		
		
?>