<?php
	global $database;
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	
	$offset = 0;
	$RPP	= 0;
	$prodID = 0;
	
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$dateToday= date("m/d/Y",$tmpdate);
	
	(isset($_POST["cboWarehouse"])) ? $warehouseid = $_POST["cboWarehouse"] : $warehouseid = 0;
	(isset($_POST["txtStartDates"])) ? $vdte = $_POST["txtStartDates"] : $vdte = '';
	(isset($_POST["txtProdCode1"])) ? $prodCode = $_POST["txtProdCode1"] : $prodCode = '';
	/*(isset($_POST["hProdID"])) ? $prodID = $_POST["hProdID"] : $prodID = 0;*/
	(isset($_POST["wid"])) ? $tmpWid = $_POST["wid"] : $tmpWid = 0;
	(isset($_POST["cboLocation"])) ? $tmpLid = $_POST["cboLocation"] : $tmpLid = 0;
	(isset($_POST["cboProdLine"])) ? $tmpPlid = $_POST["cboProdLine"] : $tmpPlid = 0;
	(isset($_POST["cboPMG"])) ? $tmpPmg = $_POST["cboPMG"] : $tmpPmg = 0;

	if(isset($_POST['btnSubmit'])) 
	{		
		$vdte = $_POST['txtStartDates'];
		$prodCode = $_POST['txtProdCode1'] ;
		/*$prodID = $_POST['hProdID'] ;*/
		$warehouseid = $_POST['cboWarehouse'];
		$tmpLid = $_POST['cboLocation'];
		$tmpPlid = $_POST['cboProdLine'];
		$tmpPmg = $_POST['cboPMG'];
	}
	else
	{
	    $vdte = $dateToday;
	    $prodCode = '';
	    $warehouseid = 0;
	    $tmpLid = 0;
	    $tmpPlid = 0;
	    $tmpPmg = 0;
	}
	
	$rs_warehouse = $sp->spSelectWarehouse($database, -2,'');
	$rs_location = $sp->spSelectLocationWS($database,$tmpWid);
	//$rs_productline = $sp->spSelectProductLine($database,2);
	$rs_productline = $database->execute("SELECT ID, Code, Name FROM product WHERE ProductLevelID =  2 ORDER BY Code ASC");
	$rs_pmg = $sp->spSelectPMG($database);
	$rs_status = $sp->spSelectStatusStocks($database);
?>