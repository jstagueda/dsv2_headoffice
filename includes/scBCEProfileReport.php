<?php
global $database;

	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$dateToday= date("m/d/Y",$tmpdate);			
	$tmpBid = 0;
	$tmpCid = 0;

	$rs_branch = $sp->spSelectBranchByName($database);
	$rs_campaign = $sp->spSelectCampaign($database);
	
	$rs_defaultbranch = $sp->spSelectBranchbyBranchParameter($database);
?>