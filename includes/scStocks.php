<?php
	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	
	$offset = 0;
	$RPP	= 0;
	$rs_campaign = $sp->spSelectCampaign($database);
	
	if(isset($_POST['cboWarehouse'])) 
	{
		$warehouseid = $_POST["cboWarehouse"];	
	}
	else
	{
		$warehouseid = 1;
	}
	
	if(isset($_POST['btnsearch'])) 
	{
		$vSearch = $_POST['txtsearch'] ;	
		/*$branch = $_POST['cboWarehouse'];	
		$campaign = $_POST['cboCampaign'];	*/
	}
	else
	{
	    $vSearch = '';
		/*    $branch=0;
	    $campaign=0;*/
	}

	if(isset($_GET['wid']))
	{
		$tmpWid = $_GET['wid'];
	}
	else
	{
		$tmpWid = 1;
		$warehouseid = 1;
	}
						
	if(isset($_GET['lid']))
	{
		$tmpLid = $_GET['lid'];
	}
	else
	{
		$tmpLid = 0;
	}
						
	if(isset($_GET['plid']))
	{
		$tmpPlid = $_GET['plid'];
	}
	else
	{
		$tmpPlid = 0;
	}
						
	if(isset($_GET['bid']))
	{
		$tmpBid = $_GET['bid'];
	}
	else
	{
		$tmpBid = 0;
	}
						
	if(isset($_GET['cid']))
	{
		$tmpCid = $_GET['cid'];
	}
	else
	{
		$tmpCid = 0;
	}
						
	if(isset($_GET['is']))
	{
		$tmpIs = $_GET['is'];
	}
	else
	{
		$tmpIs = 0;
	}
						
	if(isset($_GET['pmgid']))
	{
		$tmpPmg = $_GET['pmgid'];
	}
	else
	{
		$tmpPmg = 0;
	}
	
	if(isset($_GET['brid']))
	{
		$tmpbrid = $_GET['brid'];
	}
	else
	{
		$tmpbrid = 0;
	}
	
	if(isset($_GET['pgid']))
	{
		$tmppgid = $_GET['pgid'];
	}
	else
	{
		$tmppgid = 0;
	}
	
	if(isset($_GET['brdid']))
	{
		$tmpbrdid = $_GET['brdid'];
	}
	else
	{
		$tmpbrdid = 0;
	}
	
	//$rs_stocks = $sp->spSelectStockz($database, $offset, $RPP, $vSearch, $warehouseid,$getLocation);	
	$rs_warehouse = $sp->spSelectWarehouse($database, 0, $vSearch);
	$rs_location = $sp->spSelectLocationWS($database, $tmpWid);
	$rs_branch = $sp->spSelectBranch($database, -1, '');
	//$rs_productline = $sp->spSelectProductLine($database, 2);
	$rs_productline = $database->execute("select * from product where ProductLevelID = 2 order by Code asc");
	$rs_pmg = $sp->spSelectPMG($database);
	$rs_status = $sp->spSelectStatusStocks($database);
	$rs_brochure = $sp->spSelectBrochuresByCampaignID($database, $tmpCid);
	$rs_brochurepage = $sp->spSelectBrochureDetailsByBrochureID($database, $tmpbrid);
?>