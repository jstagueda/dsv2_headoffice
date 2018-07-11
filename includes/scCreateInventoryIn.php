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
		$affected_row = $sp->spDeleteTmpInventoryInDetails($database, $session->emp_id, $datesession, 0);
	}
	
	$datetoday = date("Y.m.d");
	$rs_warehouse = $sp->spSelectWarehouse($database,0,$search);
	
	$rs_inventoryin = $sp->spSelectTmpInventoryInDetails($database, $session->emp_id, $session->createid);
	
	$rs_uom = $sp->spSelectUOM($database);
	
	$rs_invintype = $sp->spSelectInventoryInType($database);
	
	if (isset($_POST['btnSearch'])) 
	{
	 	$vSearch = "";
	 	if (isset($_POST['lstWarehouse']))
	 		$warehouseid = $_POST['lstWarehouse'];
	 	else 
	 		$warehouseid = $_POST['hdnlstWarehouse'];	 
	}
	else
	{
	  	$vSearch = '';
	  	$warehouseid = 0;
	}

	if($rs_inventoryin->num_rows) 
	{
		$dis = "disabled";
		$row = $rs_inventoryin->fetch_object();
		$warehouseid = $row->WarehouseID;
		$rs_inventoryin->data_seek(0);
	}
	else 
	{
		$dis = ""; 	
	}
		
	$rs_product = $sp->spSelectProductList($database, $vSearch, $warehouseid);
?>