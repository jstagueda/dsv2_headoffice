<?php
	global $database;

	if (isset($_POST['txtSearch']))
	{
		$vSearch = $_POST['txtSearch'];	                   			
	}
	else
	{
		$vSearch = "";
	}

	$prodlist="";
	
	if(!isset($_GET['prodlist']))
	{
		$_GET['prodlist'] = "";				
	}	
	else
	{
		$prodlist = $_GET['prodlist'];
	}
	
	if(!isset($_GET['locid']))
	{
		$_GET['locid'] = 0;				
	}
 
	$prodclass="";
	$warehouseid = 0;
	$locationid = 0;
	$statusid = 0;
	$datetoday = date("m/d/Y");
	
	$rs_warehouse = $sp->spSelectWarehouse($database, 0, $vSearch);
	/*if (isset($_POST['lstWarehouse']))
	{
		$wid = $_POST['lstWarehouse'];	                   			
	}
	else
	{
		$wid = 0;
	}*/
	
	if(!isset($_GET['prodlist']))
	{
		$_GET['prodlist'] = "";				
	}
	
	//check status of inventory
	$rs_freeze = $sp->spCheckInventoryStatus($database);
	if ($rs_freeze->num_rows)
	{
		$cnt_inv = $rs_freeze->num_rows;
		while ($row = $rs_freeze->fetch_object())
		{
			$statusid_inv = $row->StatusID;			
		}		
	}
	else
	{
		$cnt_inv = 0;
		$statusid_inv = 20;
	}
	
	if(isset($_GET['tid']))
	{
		$icID = $_GET['tid'];
	   	$rs_invCnt = $sp->spSelectInvCntbyID($database,$icID);
	   	
		if ($rs_invCnt->num_rows)
		{
			while ($row = $rs_invCnt->fetch_object())
			{			
				$transno = $row->ID;
				$transdate = $row->TransactionDate;
				$docno = $row->DocumentNo;
				$status = $row->Status;
				$mtype = $row->MovementType;
				$remarks = $row->Remarks;
				$statusid = $row->StatusID;
				$refid = $row->refID;
				$invID = $row->invID;
				$warehouseid = $row->WarehouseID;
				$warehouse = $row->Warehouse;
				$statusid = $row->StatusID;
			}
		}
		
		$rs_product = $sp->spSelectRecordInvCountDetails($database,$vSearch, $icID,$warehouseid, $locationid);
		$_GET['whid'] = $warehouseid;
	}
	
	if(!isset($_GET['add']))
	{
		$_GET['add'] = 0;		
	}
	
	$rs_location = $sp->spSelectLocationWS($database,$warehouseid);	
	$rscboLocation = $sp->spSelectLocation($database);
	
	$rs_uom = $sp->spSelectUOMbyID($database,1);
	if ($rs_uom->num_rows)
	{
		while ($row_uom = $rs_uom->fetch_object())
		{
			$uomid = $row_uom->ID;
			$uomname = $row_uom->Name;
		} 
	}
	
	if(isset($_POST['btnSearch']))
	{
		$id = htmlentities(addslashes($_POST['hdnicid']));
	    $vSearch = htmlentities(addslashes($_POST['txtSearch']));
		//$warehouseid = htmlentities(addslashes($_POST['lstWarehouse']));
		$locationid = htmlentities(addslashes($_POST['lstLocation']));
		
	   	$rs = $sp->spSelectInvCntbyID($database,$id);
		if ($rs->num_rows)
		{
			while ($row = $rs->fetch_object())
			{			
				$transno = $row->ID;
				$transdate = $row->TransactionDate;
				$docno = $row->DocumentNo;
				$status = $row->Status;
				$mtype = $row->MovementType;
				$remarks = $row->Remarks;
				$statusid = $row->StatusID;
				$refid = $row->refID;
				$invID = $row->invID;
			}
		}
		$rs_product = $sp->spSelectRecordInvCountDetails($database,$vSearch, $id, $warehouseid, $locationid);
	}
	
	if(isset($_POST['btnFreeze']))
	{
		try
		{
			$invid = $_GET['invid'];
			$tid = $_GET['tid'];
			$database->beginTransaction();
			
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
			//update freeze table
			if ($statusid_inv == 20)
			{
				$new_stat = 21;
			}
			else
			{
				$new_stat = 20;
			}
			$rs_inventory = $sp->spUpdateInventoryStatus($database, $cnt_inv, $new_stat, $session->emp_id, $invid);
			if (!$rs_inventory)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}  
			$database->commitTransaction();
			if ($statusid_inv == 20)
			{
				$msg = "Inventory transactions are now locked. You may begin inventory count.";			
			}
			else
			{
				$msg = "Successful unfreezing of inventory.";
			}
			$database->commitTransaction();
			
			redirect_to("index.php?pageid=100.1&tid=$tid&errmsg=$msg");  
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			$_GET['msg'] = $errmsg;
		}	
	}	
?>
