<?php
	
	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
		
	$vSearch ="";
	$prodclass = "";
	$warehouseid = 0;	
	$totamt = 0;
	$date = time();
	$today = date("m/d/Y",$date);
	//$tmpdate = strtotime(date("Y-m-d", strtotime($today)) . " +1 month");
	//$start = date("m/d/Y",$tmpdate);
	$username = ''; 
	
	if(isset($_POST['startdate']))
	{
		$datetoday = $_POST['startdate'];
	}
	else
	{
		$datetoday = $today;
	}
	
	//get username
	$rs_username = $sp->spSelectUserEmployee($database, $_SESSION["emp_id"]);
	if ($rs_username->num_rows)
	{
		while ($row = $rs_username->fetch_object())
		{
			$username = $row->EmployeeName;			
		}
		$rs_username->close();		
	}
	
	//get branchdetails
	$rs_selectBranch = $sp->spSelectBranchTransfer($database);
	if($rs_selectBranch->num_rows)
	{
		while($row = $rs_selectBranch->fetch_object())
		{
			$branchName = $row->BranchName;	
			$branchID = $row->ID;			
		}
	}
	
	$parentid = 50;
	$rs_warehouse = $sp->spSelectWarehouse($database, 0, $vSearch);	
	$rs_uom = $sp->spSelectUOMbyID($database, 1);
	$rs_movetype = $sp->spSelectMovementType($database, -1, $vSearch);
	$rs_reasons = $sp->spSelectReason($database, 2,'');
	$rs_productType = $sp->spSelectProductLine($database,2);

	if (isset($_GET['wid']))	 	
	{
		$warehouseid = $_GET['wid'];
	}
	else
	{
		$warehouseid = 0;		
	}
	
	if(!isset($_GET['prodlist']))
	{
		$_GET['prodlist'] = "";				
	}	
	
	$movetype = $sp->spSelectMovementType($database, 5, $vSearch);
	if($movetype->num_rows)
	{
		while($row = $movetype->fetch_object())
		{
			$movement = $row->Code." - ".$row->Name; 			
			$movementtypeid = $row->ID;
		}
		$movetype->close();		
	}
	
    $rs_refNo = $sp->spGetMaxID($database, 7, "inventoryadjustment");
	if($rs_refNo->num_rows)
	{
		while($row = $rs_refNo->fetch_object())
		{
			$trno = $row->txnno;
		}
		if ($trno == '')
		{
			$trno = "AD00000001";
		}
		$rs_refNo->close();
	}
	
	if(isset($_GET['search']))
	{
	   $vSearch = $_GET['search'];		
	   
	   $rs_product = $sp->spSelectProductListInventory2($database, $vSearch, 0, $warehouseid);
	}
	else
    {	
	   $rs_product = $sp->spSelectProductListInventory2($database, $vSearch, 0, $warehouseid);
	}

?>