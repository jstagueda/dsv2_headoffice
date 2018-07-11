<?php
	global $database;
	$vSearch = "";
	$warehouseid = 0;	
	$rs_uom = $sp->spSelectUOM($database);
	$rs_reasons = $sp->spSelectReason($database, 2,'');
	$pge = 0;
	$prodlist = "";
	
	if (isset($_GET['wid']))	 	
	{
		$warehouseid = $_GET['wid'];
	}
	else
	{
		$warehouseid = 0;		
	}
	
	if (isset($_GET['pge']))	 	
	{
		$pge = $_GET['pge'];
	}
	else
	{
		$pge=0;		
	}
	
	if($pge != 3)
	{
		if(isset($_POST['btnSearch2']))
		{
			$vSearch = htmlentities(addslashes($_POST['txtSearch']));	
			$rs_product = $sp->spSelectProductListInventory2($database, $vSearch, 0, $warehouseid);
					
			if($vSearch == '')
			{
				$vSearch = '   ';
			}
		}
		else
		{
			$rs_product = $sp->spSelectProductListInventory2($database, $vSearch, 0, $warehouseid);
		}
	}
	else
	{
		$temp = $_GET['wid'];		
					
		if(isset($_POST['btnSearch2']))
		{
			$vSearch = htmlentities(addslashes($_POST['txtSearch']));	
			$rs_product = $sp->spSelectProductListInventoryTransfer($database, $vSearch, 0, $temp);	
			/*echo $temp;
			echo $rs_product->num_rows;
			die('xxx');*/
			if($vSearch == '')
			{
				$vSearch = '   ';
			}
		}
		else
		{
			$rs_product = $sp->spSelectProductListInventoryTransfer($database, $vSearch, 0, $temp);
			//echo $rs_product->num_rows;
			//die('xxx');
		}
		
		if (isset($_POST['btnAdd']))
		{
			if (!isset($_SESSION['trans_prodlist']))
			{
				$i = 0;
			}
			else
			{
				$i = sizeof($_SESSION['trans_prodlist']);
			}

			foreach ($_POST['chkInclude'] as $key=>$value)
			{
				$_SESSION['trans_prodlist'][$i] = $value;
				$i++;
			}

			echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=25&wid=".$_GET['wid']."&desid=".$_GET['desid']."&mtypeid=".$_GET['mtypeid']."&remarks=".$_GET['remarks']."&dno=".$_GET['dno']."&prodlist=1"."';
						window.close();
					</script>";			
		}				
	}
	
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