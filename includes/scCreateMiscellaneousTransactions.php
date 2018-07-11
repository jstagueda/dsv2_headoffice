<?php
	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	//ini_set('max_execution_time', 1000);
		
	$vSearch ="";
	$prodclass = "";
	$warehouseid = 0;
	$mtypeid = 0;
	$totamt = 0;
	$datetoday = date("m/d/Y");
	$parentid = 50;
	$prodlist = "";
	$username = "";
	
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
	
	$rs_warehouse = $sp->spSelectWarehouse($database, 0, $vSearch);	
	$rs_uom = $sp->spSelectUOM($database);
	$rs_movetype = $sp->spSelectMovementType($database, -1, $vSearch);
	$rs_reasons = $sp->spSelectReason($database, 2,'');
	$rs_productType = $sp->spSelectProductLine($database,2);  
	$rs_productsDG = $sp->spSelectProductListDG($database);
	
	//get products in DG
	if($rs_productsDG->num_rows)
	{
		while($row_dg = $rs_productsDG->fetch_object())
		{
			$prodlist .= $row_dg->ProductID.",";
		}
		$rs_productsDG->close();
		
		$prodlist = substr($prodlist, 0, -1);
	}

	if(!isset($_GET['prodlist']))
	{
		$_GET['prodlist'] = "";				
	}	
	
	if (isset($_GET['wid']))	 	
	{
		$warehouseid = $_GET['wid'];
		
		if (isset($_GET['mtypeid']))
		{
			$mtypeid = $_GET['mtypeid'];			
		}
		
		if ($warehouseid == 2 && $mtypeid == 10 && $_GET['prodlist'] == "")
		{
			$_GET['prodlist'] = $prodlist;			
		}
	}
	else
	{
		$warehouseid = 0;		
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
	
//	if(isset($_GET['search']))
//	{
//	   $vSearch = $_GET['search'];		
//	
//	   $rs_product = $sp->spSelectProductListInventory2($database, $vSearch, 0, $warehouseid);
//	}
//	else
//	{
//		$rs_product = $sp->spSelectProductListInventory2($database, $vSearch, 0, $warehouseid);
//	}
	
	//check status of inventory
	$rs_freeze = $sp->spCheckInventoryStatus($database);
	if ($rs_freeze->num_rows)
	{
		while ($row = $rs_freeze->fetch_object())
		{
			$statusid_inv = $row->StatusID;			
		}		
	}
	else
	{
		$statusid_inv = 20;
	}
	
	
?>