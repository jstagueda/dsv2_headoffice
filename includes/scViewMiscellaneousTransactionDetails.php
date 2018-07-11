<?php
	include CS_PATH.DS."ClassInventory.php";
	global $database;
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	
    $txnid = $_GET['tid'];    
    $rs_invadj = $sp->spSelectInvAdjByID($database, $txnid);
    //$rs_invadj = $tpiInventory->spSelectInvAdjByID($database, $txnid);
	// $rs_invadjdetails = $sp->spSelectInvAdjDetailsByID($database, $txnid);
	$rs_invadjdetails = $tpiInventory->spSelectInvAdjDetailsByID($database, $txnid);
	
	$mid = 1;
	if ($rs_invadj->num_rows)
	{
		while ($row = $rs_invadj->fetch_object())
		{			
			$transno = $row->ID;
			$transdate = date("m/d/Y H:i:s", strtotime($row->TransactionDate));
			$docno = $row->DocumentNo;
			$status = $row->Status;
			$mtype = $row->MovementTypeName;
			$remarks = $row->Remarks;
			$statusid = $row->StatusID;
			$mid = $row->MovementTypeID;
			$whouse = $row->WarehouseName;
			$whouseid = $row->WarehouseID;
			$createdby = $row->CreatedBy;
			$confirmedby = $row->ConfirmedBy;
			$approvedby = $row->ApprovedBy;
			$dateconfirmed = $row->DateModified;
			$branch = $row->Branch;
		}
	}

    if(isset($_POST['btnConfirm']))
    { 
    	try
		{
			$database->beginTransaction();
			//check status of inventory
			$rs_freeze = $sp->spCheckInventoryStatus($database);
			if ($rs_freeze->num_rows){
				while ($row = $rs_freeze->fetch_object()){
					$statusid_inv = $row->StatusID;			
				}		
			}else{
				$statusid_inv = 20;
			}
			
			if ($statusid_inv == 21){
				$message = "Cannot save transaction, Inventory Count is in progress.";
				redirect_to("index.php?pageid=3.1&tid=$txnid&message=$message");				
			}else{
				$rs_invadjdetails = $sp->spSelectInvAdjDetailsByID($database, $txnid);
				if ($rs_invadjdetails->num_rows){
					$ctr = 0;
					while ($row = $rs_invadjdetails->fetch_object()){	
						$ctr++;
						$prodid = $row->ProductID;
						$warehouseid = $row->WarehouseID;	
						$invid = 0;
						if(isset($_POST['txtActQuantity'.$ctr])){
							$tmpactual = $_POST['txtActQuantity'.$ctr];
						}else{
							$tmpactual = $_POST['hQty'.$ctr];
						}
						//$actual = number_format($tmpactual, 0, '.', '');
						$actual = $tmpactual;
						$soh = $row->SOH;
						
						//update prevbalance
						$rs_update = $sp->spUpdateInvAdjDetailsByID($database, $txnid, $prodid, $soh, $actual);
						if (!$rs_update)
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}
						
						//update stocklog
			            $rs_getpwid = $sp->spGetInventoryIDWarehouseIDProductID($database, $prodid, $warehouseid);
			            if($rs_getpwid->num_rows)
			            {
			            	while($row2 = $rs_getpwid->fetch_object())
			            	{
			            		$invid = $row2->InventoryID;
			            	}
			            }
			            $txndate = date('Y-m-d', strtotime($transdate));
						$rs_stocklog = $stocklog->AddToStockLog($warehouseid, $invid, 1, "", "", "", $prodid, 0, $txnid, $docno, $remarks, $mid, $actual, $txndate);

					}
				}

		  	    $rs_update = $sp->spUpdateCycleCount($database, $txnid, $_SESSION['emp_id']);
		  	    if (!$rs_update)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
		  	    $database->commitTransaction();
		    	$message = 'Successfully confirmed transaction.';
		    	redirect_to("index.php?pageid=3&msg=$message");				
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("index.php?pageid=3.1&tid=$txnid&message=$message");
		}
	}
	
	if(isset($_POST['btnDelete']))
    {
    	try
		{
			$database->beginTransaction();//check status of inventory
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
			
			if ($statusid_inv == 21)
			{
				$message = "Cannot save transaction, Inventory Count is in progress.";
				redirect_to("index.php?pageid=59.1&tid=$txnid&message=$message");				
			}
			else
			{
				$rs_update = $sp->spDeleteCycleCount($database, $txnid, $_SESSION['emp_id']);
		  	    if (!$rs_update)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				$database->commitTransaction();
		    	$message = 'Successfully cancelled transaction.';
		    	redirect_to("index.php?pageid=3&msg=$message");				
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("index.php?pageid=59.1&tid=$txnid&message=$message");
		}    	    	
    }
	
    if(isset($_POST['btnCancel']))
	{
		redirect_to("index.php?pageid=3");		
	}	
?>