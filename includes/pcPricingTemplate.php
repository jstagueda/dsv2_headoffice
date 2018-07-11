<?php

require_once("../initialize.php");

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	} */
global $database;
if(isset($_POST['btnSave'])) {

		
		$code = htmlentities(addslashes($_POST['txtfldcode']));
		$name = htmlentities(addslashes($_POST['txtfldname']));
		$markup = addslashes($_POST['txtfldmarkup']);
		$discount1 = addslashes($_POST['txtfldd1']);
		$discount2 = addslashes($_POST['txtfldd2']);
		$discount3 = addslashes($_POST['txtfldd3']);
		$refid = addslashes($_POST['cboRef']);
		
		$rs_ExistingPriceList = $sp->spSelectExistingPriceList($database,0, $code);
		if($rs_ExistingPriceList->num_rows)
		{
			$errormessage = "Code already exists.";
			redirect_to("../index.php?pageid=9&errmsg=$errormessage");
		}
		else
		{
			$affected_rows = $sp->spInsertPriceList($database,$code,$name,$markup,$discount1,$discount2,$discount3,$refid);	
		
			$message = "Successfully saved record.";
			redirect_to("../index.php?pageid=9&msg=$message");
		}		
} 
elseif (isset($_POST['btnUpdate'])) {
		
		$id = $_POST["hdnPriceTempID"];
		$code = htmlentities(addslashes($_POST['txtfldcode']));
		$name = htmlentities(addslashes($_POST['txtfldname']));
		$markup = addslashes($_POST['txtfldmarkup']);
		$discount1 = addslashes($_POST['txtfldd1']);
		$discount2 = addslashes($_POST['txtfldd2']);
		$discount3 = addslashes($_POST['txtfldd3']);
		$refid = addslashes($_POST['cboRef']);
		
		$rs_ExistingPriceList = $sp->spSelectExistingPriceList($database,0, $code);
		$rs_ExistingPriceList2 = $sp->spSelectExistingPriceList2($database,$id, $code);

		if($rs_ExistingPriceList2->num_rows)
		{
			if($refid == $id)
			{
				$errormessage = "Please select a different template.";
				redirect_to("../index.php?pageid=9&errmsg=$errormessage");
			}
			else
			{
				$affected_rows = $sp->spUpdatePriceList($database,$id, $code, $name, $markup, $discount1, $discount2, $discount3, $refid);
		
				$message = "Successfully updated record.";
				redirect_to("../index.php?pageid=9&msg=$message");
			}
		}
		else
		{
			if($rs_ExistingPriceList->num_rows)
			{
				$errormessage = "Code already exists.";
				redirect_to("../index.php?pageid=9&errmsg=$errormessage");
			}
			else
			{
				if($refid == $id)
				{
					$errormessage = "Please select a different template.";
					redirect_to("../index.php?pageid=9&errmsg=$errormessage");
				}
				else
				{
					$affected_rows = $sp->spUpdatePriceList($database,$id, $code, $name, $markup, $discount1, $discount2, $discount3, $refid);
		
					$message = "Successfully updated record.";
					redirect_to("../index.php?pageid=9&msg=$message");
				}
			}

		}

					   
}
elseif (isset($_POST['btnDelete'])){
	
		$id = 0;	
	
		$id = $_POST["hdnPriceTempID"];

		
		$affected_rows = $sp->spDeletePriceList($database,$id);
		
		$message = "Successfully deleted record.";
		redirect_to("../index.php?pageid=9&msg=$message");


}



		
		
?>