<?php
	global $database;	
	
	if (isset($_POST["btnSubmit"]))
	{
		$warehouseid = $_POST["cbowarehouse"];
		$productlineid = $_POST["cboproductline"];
		$invstatusid = $_POST["cboStatus"];		
		$search = $_POST["txtSearch"];
		$age = $_POST["txtAge"];
	/*	
		if ($_POST["txtStartDate"] != "")
		{
			$today = date("m/d/Y", strtotime($_POST["txtStartDate"]));
		}
		else
		{
			$today = "";
		}
		//$tmpStartDate = strtotime(date("Y-m-d", strtotime($today)));
	*/
	}
	else
	{
		$today = "";
		$warehouseid = 1;
		$productlineid = 0;
		$pmgid = 0;
		$invstatusid = 0;
		$search = "";
		$age = "";
	}
	
	$rs_warehouse = $sp->spSelectWarehouse($database, 0, "");
	//$rs_productline = $sp->spSelectProductLine($database, 2);
	$rs_productline = $database->execute("SELECT ID, Code, Name FROM product WHERE ProductLevelID =  2 ORDER BY Code");
	
	$rs_status = $sp->spSelectStatusStocks($database);
?>