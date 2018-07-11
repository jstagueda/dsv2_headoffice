<?php
	global $database;
	$ksID = 0;
	$pmg = "";
	$factor = "";
	$sdate = "";
	$edate = "";
	$msg = "";
	$ksSearchTxt = "";
	$date = time();
 	$today = date("m/d/Y",$date);
 	$startDate = $today;
 	$endDate = $today;
 	
 	$rs_pmglist = $sp->spSelectPMG($database);
	
	if(isset($_POST['btnSearch']))
	{
		$ksSearchTxt = addslashes($_POST['txtfldsearch']);	
		$rs_netfactor = $sp->spSelectNetFactor($database, $ksSearchTxt, $ksID);
	}	
	else
	{
		$rs_netfactor = $sp->spSelectNetFactor($database, $ksSearchTxt, $ksID);
	}
?>