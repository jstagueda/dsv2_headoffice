<?php
	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	}*/ 
	
	$search="";
	$prodclass="";
	global $database;
	if (!isset($session->createid)){
		$datesession = date("YmdHis");
		$session->set_createid($datesession);
		$affected_row = $sp->spDeleteTmpInventoryCountDetails($database, $session->emp_id, $datesession, 0);
	}
	
	$datetoday = date("Y.m.d");
	$rs_warehouse = $sp->spSelectWarehouse(0,$search);
	
	$rs_inventorycount = $sp->spSelectTmpInventoryCountDetails($session->emp_id, $session->createid);


	
	$rs_uom = $sp->spSelectUOM();
	
	if (isset($_POST['btnSearch'])) {
	 $vSearch = $_POST['txtSearch'];
	 if (isset($_POST['lstWarehouse']))
	 	$warehouseid = $_POST['lstWarehouse'];
	 else 
	 	$warehouseid = $_POST['hdnlstWarehouse'];
	 
	}
	else{
	  $vSearch = '';
	  $warehouseid = 0;
	}

	if($rs_inventorycount->num_rows) 
	{
		$dis = "disabled";
		$row = $rs_inventorycount->fetch_object();
		$warehouseid = $row->WarehouseID;
		$rs_inventorycount->data_seek(0);
	}
	else {
	$dis = ""; 	
	}
		
	$rs_product = $sp->spSelectProductList($vSearch, $warehouseid);
	$rs_reasons =  $sp->spSelectReason();
?>