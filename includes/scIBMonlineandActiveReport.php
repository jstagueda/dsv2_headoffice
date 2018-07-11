<?php

	global $database;
	
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$dateToday= date("m/d/Y",$tmpdate);			
	
	$branchid = -1;
	$campgnid = 0;
	$ibmcode = 0;
	$ibmfrom = 0;
	$ibmto = 0;
	
	
	$rs_branches = $sp->spSelectBranch($database, $branchid, '');
	$rs_campaign = $sp->spSelectCampaign($database);
	$rs_pmg = $sp->spSelectTPIPMG($database);
	$rs_cusdetails = $sp->spSelectCustomerDetails($database);
	$rs_cusdetails2 = $sp->spSelectCustomerDetails($database);
	
	
?>	