<?php

	global $database;
	
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$dateToday= date("Y-m-d",$tmpdate);			
	
	$branchid = -1;
	$fromdate = $dateToday;
	$todate = $dateToday;

		
	$rs_branches = $sp->spSelectBranch($database, $branchid, '');
	
	$rs_defaultbranch = $sp->spSelectBranchbyBranchParameter($database);
	
	
?>	