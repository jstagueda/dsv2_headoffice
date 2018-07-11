<?php

	global $database;

	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	$branchid = -1;
	$campgnid = 0;
	$tmpbranchid = 0;
	
	$rs_branches = $sp->spSelectBranch($database, $branchid, '');
	$rs_campaign = $sp->spSelectCampaign($database);
	
	
?>	