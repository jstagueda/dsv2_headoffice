<?php

	require_once("../initialize.php");
	global $database;
	/*if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}*/

	if(isset($_POST['btnSave'])) 
	{	
		try
		{
			
		$database->beginTransaction();	
		$pricing = $_POST["chkSelect"];
		foreach ($pricing as $key=>$ID) 
		{
			$uomid = $_POST["uomid{$ID}"];
			$prodid = $_POST["prodid{$ID}"];
			$price = $_POST["txtSKUMU{$ID}"];

			$update = $sp->spUpdateProductPricing($database, $prodid, $uomid, $price);
			if (!$update)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
		}
		
		$database->commitTransaction();
		$msg = "Successfully updated Pricing.";
		redirect_to("../index.php?pageid=43&msg=$msg");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=43&errmsg=$errmsg");
		}
	}
?>