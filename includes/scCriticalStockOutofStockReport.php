<?php

	global $database;
	
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$dateToday= date("m/d/Y",$tmpdate);			
	

	$warehouseid = 0;
	$productid = 0;
	$pmgid = 0;
	$pcode = '';
	$invstatus = 0;
	$qtyfrom = 0;
	$qtyto = 0;
	
	$productline = 2;
	$statustypeid= 5;
	
	$rs_warehouse = $sp->spSelectWarehouse($database, $warehouseid, ' ');
	$rs_productline = $sp->spSelectProductLine($database, $productline);
	$rs_tpipmg = $sp->spSelectTPIPMG($database);
	$rs_status = $sp->spSelectStatusListByStatusTypeID($database, $statustypeid);
	

?>	