<?php
	global $database;
	include CS_PATH.DS.'ClassInventory.php';
	if (!ini_get('display_errors')) { ini_set('display_errors', 1); }
	
	$rs_campaign = $sp->spSelectCampaign($database);
	$date = time();
	if (!isset($_POST['txtStartDate']))
	{
		$dateto = date("m/d/Y", $date);
		$tmpdate = date("m/d/Y", $date);
		$dateto_q = date("Y-m-d", strtotime($tmpdate. " +1 day"));
	}
	else
	{
		$datefrom = $_POST['txtStartDate'];
		$datefrom_q = date("Y-m-d", strtotime($_POST['txtStartDate']));
	}
	
	if (!isset($_POST['txtEndDate']))
	{
		$dateto = date("m/d/Y", $date);
		$tmpdate = strtotime(date("Y-m-d", strtotime($dateto)));
		$datefrom = date("m/d/Y",$tmpdate);
		$datefrom_q = date("Y-m-d", $tmpdate);
		
		$yr = date("Y",$tmpdate);
		$mo = date("m",$tmpdate);
		$datefrom_bb = date("Y-m-d", strtotime($dateto));
		//$datefrom_bb = date("Y-m-d", strtotime($dateto. " -30 day"));
		$dateto_bb = date("Y-m-d", $tmpdate);
		
	}
	else
	{
		
		$dateto = $_POST['txtEndDate'];
		$dateto_q = date("Y-m-d", strtotime($_POST['txtEndDate']. " +1 day"));
		
		$datefrom_bb = date("Y-m-d", strtotime($_POST['txtStartDate']));
		//$datefrom_bb = date("Y-m-d", strtotime($_POST['txtStartDate']. " -30 day"));
		$dateto_bb = date("Y-m-d", strtotime($_POST['txtEndDate']));

	}
	
	if((isset($_GET['pid'])) && (isset($_GET['wid'])))
	{
		$prodid = $_GET['pid'];
		$wareid = $_GET['wid'];
		//$year = date("Y");
		//$month = 0;
	}
	elseif (isset($_POST['btnSearch']))
	{
		
		$prodid = $_POST["hdnProductID"];
		$wareid = $_POST["hdnWarehouseID"];
		//$year = addslashes($_POST['cboYear']);
		//$month = addslashes($_POST['cboMonth']);
		
	}
	else 
	{
		$prodid = 0;
		$wareid = 0;
		//$year = date("Y");
		//$month = 0;
		$chkendbal= 0;
	}
	$endsoh = 0;
	$rs_productdetails = $sp->spSelectInventoryDetails($database, $prodid, $wareid);
	if ($rs_productdetails->num_rows)
	{
		while ($row = $rs_productdetails->fetch_object())
		{
			$warehouse = $row->Warehouse;
			$prodname = $row->ProductName;
			$prodcode = $row->Code;
			$endsoh = $row->SOH;
		}
	}
	//beginning balance
	$beg = 0;
	//$rs_begabal = $sp->spSelectStockLogBeginningBalance($database, $prodid, $wareid, $datefrom_bb, $dateto_bb);
	
	if(!isset($_POST['txtStartDate'])){
		$rs_begabal = $database->execute("SELECT DISTINCT i.WarehouseID, 
									 i.SOH BegBalance, i.ProductID, (IFNULL(i.SOH, 0)) - SUM(IFNULL(s.QtyIn, 0) ) + SUM(IFNULL(s.QtyOut, 0)) xBegBalance 
									 FROM `stocklog` s
									 LEFT JOIN `inventory` i ON i.ID= s.InventoryID
									 WHERE i.ProductID = ".$prodid." AND i.WarehouseID = ".$wareid." GROUP BY i.WarehouseID, s.ProductID");
	}else{
		$rs_begabal = $database->execute("SELECT DISTINCT i.WarehouseID, 
									 i.SOH BegBalance, i.ProductID, (IFNULL(i.SOH, 0)) - SUM(IFNULL(s.QtyIn, 0) ) + SUM(IFNULL(s.QtyOut, 0)) xBegBalance 
									 FROM `stocklog` s
									 LEFT JOIN `inventory` i ON i.ID= s.InventoryID
									 WHERE i.ProductID = ".$prodid." AND i.WarehouseID = ".$wareid." AND s.TxnDate BETWEEN '".$datefrom_bb."' and '".$dateto_bb."'
									 GROUP BY i.WarehouseID, s.ProductID");
	}
	
	if ($rs_begabal->num_rows){ 
		while ($row = $rs_begabal->fetch_object()){
			$beg = $row->BegBalance;
		}
		$rs_begabal->close();
	}
	
	$end = 0;
	$qtyin = 0;
	$qtyout = 0;
	//$end1 = 0;
	// stocklog		
	$rs_stocklog = $tpiInventory->spSelectStockLog($database, $prodid, $wareid, $datefrom_q, $dateto_q);
	// $rs_stocklog = $sp->spSelectStockLog($database, $prodid, $wareid, $datefrom_q, $dateto_q);
	if ($rs_stocklog->num_rows){ 
		while ($row = $rs_stocklog->fetch_object()){
			$qtyin += $row->QtyIn;
			$qtyout += $row->QtyOut;
			$end = $row->EndingBalance;
			//$end1 = $row->EndBal;
		}
		
		$rs_stocklog->data_seek(0);
		$end = ($beg + $qtyin) - $qtyout;
		
	}
	else{
		$end = $endsoh;
	}
?>