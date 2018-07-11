<?php
	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	}*/ 
	
	$search="";
	$prodclass="";
	
	$rs_reasons = $sp->spSelectReason();
	
	if (!isset($session->createid)){
		$datesession = date("YmdHis");
		$session->set_createid($datesession);
		$affected_row = $sp->spDeleteTmpAdjustmentDetails($session->emp_id, $datesession, 0);
	}
	
	$datetoday = date("Y.m.d");
	$rs_warehouse = $sp->spSelectWarehouse(0,$search);
	
	$rs_adjustment = $sp->spSelectTmpAdjustmentDetails($session->emp_id, $session->createid);


	
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

	if($rs_adjustment->num_rows) 
	{
		$dis = "disabled";
		$row = $rs_adjustment->fetch_object();
		$warehouseid = $row->WarehouseID;
		$rs_adjustment->data_seek(0);
	}
	else {
	$dis = ""; 	
	}
	
	$rs_product = $sp->spSelectProductList($vSearch, $warehouseid);
	
	
?>