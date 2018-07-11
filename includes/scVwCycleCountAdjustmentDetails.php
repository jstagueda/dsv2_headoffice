<?php

	global $database;

	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	
    $txnid = $_GET['tid'];
    
    $rs_invadj = $sp->spSelectInvAdjByID($database, $txnid);
	$rs_invadjdetails = $sp->spSelectInvAdjDetailsByID($database, $txnid);
	
	if ($rs_invadj->num_rows)
	{
		while ($row = $rs_invadj->fetch_object())
		{			
			$transno = $row->ID;
			$transdate = date("m/d/Y", strtotime($row->TransactionDate));
			$docno = $row->DocumentNo;
			$status = $row->Status;
			$mtype = $row->MovementTypeName;
			$remarks = $row->Remarks;
			$statusid = $row->StatusID;
			$whouse = $row->WarehouseName;
			$whouseid = $row->WarehouseID;
			$createdby = $row->CreatedBy;
			$confirmedby = $row->ConfirmedBy;
			$dateconfirmed = $row->DateModified;
			$branch = $row->Branch;
		}
	}

    if(isset($_POST['btnConfirm']))
    {
    	try
		{
			$database->beginTransaction();
			$rs_invadjdetails = $sp->spSelectInvAdjDetailsByID($database, $txnid);
			if ($rs_invadjdetails->num_rows)
			{    $ctr = 0;
				while ($row = $rs_invadjdetails->fetch_object())
				{	
					$ctr++;
					$prodid = $row->ProductID;
					$warehouseid = $row->WarehouseID;	
					$invid = 0;
					$inv_qty = 0;
					$tmpactual = $_POST['txtActQuantity'.$ctr];
					$actual = number_format($tmpactual, 0, '.', '');
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
					
					if ($soh > $actual)
					{
						$inv_qty = -($soh - $actual);					
					}
					else
					{
						$inv_qty = $actual - $soh;					
					}
		            $txndate = date('Y-m-d', strtotime($transdate));
					$rs_stocklog = $stocklog->AddToStockLog($warehouseid, $invid, 1, "", "", "", $prodid, 0, $txnid, $docno, $remarks, 6, $inv_qty, $txndate);
				}
			}
		
	  	    $rs_update = $sp->spUpdateCycleCount($database, $txnid, $_SESSION['emp_id']);
	  	    if (!$rs_update)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			$database->commitTransaction();
	    	$message = 'Successfully confirmed transaction.';
	    	redirect_to("index.php?pageid=59&msg=$message");
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("index.php?pageid=59.1&tid=$txnid&message=$message");
		}
    }
    
    if(isset($_POST['btnDelete']))
    {
    	try
		{
			$database->beginTransaction();
		 	$rs_update = $sp->spDeleteCycleCount($database, $txnid, $_SESSION['emp_id']);
	  	    if (!$rs_update)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			$database->commitTransaction();
	    	$message = 'Successfully cancelled transaction.';
	    	redirect_to("index.php?pageid=59&msg=$message");
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
		redirect_to("index.php?pageid=59");		
	}	
?>