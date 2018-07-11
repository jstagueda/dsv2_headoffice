<?php
	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	global $database;
	$vSearch="";
	$prodclass="";
	$rs_reasons =  $sp->spSelectReason($database, 0, '');
	$warehouseid = "";
	
	$datetoday = date("m/d/Y");
	$rs_warehouse = $sp->spSelectWarehouse($database, 0, $vSearch);
	$rs_uom = $sp->spSelectUOMbyID($database, 1);
	$rs_reasons = $sp->spSelectReason($database, 0, '');
	
	if (isset($_GET['wid']))	 	
	{
		$warehouseid = $_GET['wid'];
		$rs_product = $sp->spSelectProdList($database, $vSearch, $warehouseid);
	}
	else
	{
		$_GET['wid'] = 0;	
		$rs_product = $sp->spSelectProdList($database,$vSearch, 1);	
	}
?>
