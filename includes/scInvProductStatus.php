<?php
//print_r($_GET);
//die();
global $database;
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$tmpStartDate = strtotime(date("Y-m-d", strtotime($today)));
	//$tmpStartDate = strtotime(date("Y-m-d", strtotime($today)) . " -1 month");
	$end = date("m/d/Y",$tmpdate);
	$start = date("m/d/Y",$tmpStartDate);
	$fromDate = '';
	$ref=0;
	$vSearch2="";
	if(isset($_POST['btnSearch'])) 
	{
			$vSearch2 = $_POST['txtProdCode1'] ;	
          	$fromDate = $_POST['txtStartDates'];
          	$pos = strrpos($_POST['txtProdCode1'], "-");	
			$vSearch = trim(substr($_POST['txtProdCode1'],0,$pos));
				
	}
	else
	{
			
		    $vSearch = '';
			$fromDate = $today;
	}
(isset($_POST["cboWarehouse"])) ? $warehouseid = $_POST["cboWarehouse"] : $warehouseid = 0;


						if(isset($_GET['wid']))
						{
							$tmpWid = $_GET['wid'];
						}
						else
						{
							$tmpWid = 0;
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
							if (isset($_POST['rdRefNo'])) 
							{ 
								if ($_POST['rdRefNo'] == 1) 
								{
									$ref = 1;
								}
								else
								{
									$ref = 2;
								}
							}
							
				
							

$rs_warehouse = $sp->spSelectWarehouse($database, 0, '');
$rs_location = $sp->spSelectLocationWS($database,$tmpWid);
$rs_productline = $sp->spSelectProductLine($database,2);
$rs_pmg = $sp->spSelectPMG($database);
?>