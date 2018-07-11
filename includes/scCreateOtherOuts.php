<?php
	/*if (!ini_get('display_errors'))
	{
		ini_set('display_errors', 1);
	} */
	
	$search="";
	$prodclass="";
	global $database;
	if (!isset($session->createid))
	{
		$datesession = date("YmdHis");
		$session->set_createid($datesession);
		$affected_row = $sp->spDeleteTmpInventoryOutDetails($database,$session->emp_id, $datesession, 0);
	}
	
	$datetoday = date("Y.m.d");
	$rs_warehouse = $sp->spSelectWarehouse($database,0,$search);
	
	$rs_inventoryout = $sp->spSelectTmpInventoryOutDetails($database, $session->emp_id, $session->createid);
	
	$rs_uom = $sp->spSelectUOM($database);
	
	$rs_invouttype = $sp->spSelectInventoryOutType($database);
	
	if (isset($_POST['btnSearch'])) 
	{
	 	$vSearch = $_POST['txtSearch'];
	 	if (isset($_POST['lstWarehouse']))
	 		$warehouseid = $_POST['lstWarehouse'];
	 	else 
	 		$warehouseid = $_POST['hdnlstWarehouse'];	 
	}
	else
	{
	  	$vSearch = '';
	  	$warehouseid = 1;
	}

	if($rs_inventoryout->num_rows) 
	{
		$dis = "disabled";
		$row = $rs_inventoryout->fetch_object();
		$warehouseid = $row->WarehouseID;
		$rs_inventoryout->data_seek(0);
	}
	else 
	{
		$dis = ""; 	
	}
		
	$rs_product = $sp->spSelectProductList($database, $vSearch, $warehouseid);
?>