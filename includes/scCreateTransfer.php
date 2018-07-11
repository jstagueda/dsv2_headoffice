<?php
	global $database;
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}

	$warehouseid = 0;
	$temp = 0;
   	$tmpProdLine = 0;
   	$vSearch = '';
   	$username = ''; 
   	
   	if(!isset($_GET['prodlist']))
	{
		$_GET['prodlist'] = 0;
		unset($_SESSION["trans_prodlist"]);				
	}
	else
	{
		if ($_GET['prodlist'] != 1)
		{
			unset($_SESSION["trans_prodlist"]);			
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
	
	/*GENERAL INFORMATION*/
	$rs_refNo = $sp->spGetMaxID($database, 1, "inventorytransfer");
	if($rs_refNo->num_rows)
	{
		while($row = $rs_refNo->fetch_object())
		{
			$trno = $row->txnno;
		}
		if ($trno == '')
		{
			$trno = "TR00000001";
		}
		$rs_refNo->close();
	}
	
	if(isset($_GET['wid']))
	{
   		$temp = $_GET['wid'];		
	}
	else
	{
		$_GET['wid'] = 0;
		$_GET['desid'] = 0;
		$_GET['mtypeid'] = 0;
	}
	
    $rs_MovementType = $sp->spSelectMovementType($database, -2,'');
	$datetoday = date("m/d/Y");
	$rs_sourcewarehouse = $sp->spSelectWarehouse($database, -2,'');
	$rs_deswarehouse = $sp->spSelectDesWarehouse($database, $temp);

	if(isset($_GET['search']))
	{
   		$vSearch = $_GET['search'];
   		
   		if(!isset($_GET['wid']))
		{
	   		$_GET['wid'] = 0;
	   		$_GET['desid'] = 0;
	   		$_GET['mtypeid'] = 0;		
		}		
	}
	
	if($temp == 0)
	{		
		$rs_product = $sp->spSelectProductListInventoryTransfer($database, $vSearch, 0, 0);			
	}
	else
	{
		$rs_product = $sp->spSelectProductListInventoryTransfer($database, $vSearch, 0, $temp);
	}
	
	$rs_selectBranch = $sp->spSelectBranchTransfer($database);
	if($rs_selectBranch->num_rows)
	{
		while($row = $rs_selectBranch->fetch_object())
		{
			$branchName = $row->BranchName;	
			$branchID = $row->ID;			
		}
	}
	
	$rs_uom = $sp->spSelectUOM($database);
    $rs_reasons = $sp->spSelectReason($database, 5, '');
?>
