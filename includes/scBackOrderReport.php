<?php

	global $database;
	
	$date = time();
	$today = date("Y/m/d",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$dateToday= date("Y-m-d",$tmpdate);		
	$sdate = $dateToday;
	$edate = $dateToday;
	$sbranch = 0;
	$ebranch = 0;
	$sdealer = 0;
	$edealer = 0;
	$sprod = 0;
	$eprod = 0;
	$sid = 0;
	$eid = 0;
	$grp = 0;
	$branchid = -1;

	
	
	$rs_frombranches = $sp->spSelectBranch($database, $branchid, '');
	$rs_tobranches = $sp->spSelectBranch($database, $branchid, '');
	$rs_dealerfrom = $sp->spSelectSO($database, 1);
	$rs_dealerto = $sp->spSelectSO($database, 1);
	$rs_SOfrom = $sp->spSelectSO($database, 2);
	$rs_SOto = $sp->spSelectSO($database, 2);
	$rs_PCfrom = $sp->spSelectProductAll($database);
	$rs_PCto = $sp->spSelectProductAll($database);

	

?>	