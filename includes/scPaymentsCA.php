<?php

	global $database;
	
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$dateToday= date("m/d/Y",$tmpdate);			
	
	$branchid = -1;
	$dealerid = -1;
	$campgnid = 0;
	
	$rs_branches = $sp->spSelectBranch($database, $branchid, '');
	$rs_customer = $sp->spSelectCustomer($database, $dealerid, '');
	$rs_campaign = $sp->spSelectCampaign($database);
	
	
?>	