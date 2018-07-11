<?php
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	global $database;
	
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$dateToday= date("Y-m-d",$tmpdate);			
	
	$branchid = -1;
	$fromdate = $dateToday;
	$todate = $dateToday;
		
	$rs_branches = $sp->spSelectBranch($database, $branchid, '');
	$rs_cusdetails = $sp->spSelectCustomerDetailsNew($database);
	$rs_cusdetails2 = $sp->spSelectCustomerDetailsNew($database);

?>