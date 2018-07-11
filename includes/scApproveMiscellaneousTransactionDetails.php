<?php
	require_once CS_PATH.DS.'ClassInventory.php';
	global $database;
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	
    $txnid = $_GET['tid'];    
    $rs_invadj = $sp->spSelectInvAdjByID($database, $txnid);
    //$rs_invadj = $tpiInventory->spSelectInvAdjByID($database, $txnid);
	$rs_invadjdetails = $tpiInventory->spSelectInvAdjDetailsByID($database, $txnid);
	//while ($r = $rs_invadjdetails->fetch_object()){
	//	echo "<pre>";
	//	print_r($r);
	//	echo "</pre>";
	//}
	//die();
	$mid = 1;
	if ($rs_invadj->num_rows)
	{
		while ($row = $rs_invadj->fetch_object())
		{			
			$transno 	 = $row->ID;
			$refno 		 = $row->RefID;
			$transdate 	 = date("m/d/Y H:i:s", strtotime($row->TransactionDate));
			$docno 		 = $row->DocumentNo;
			$status 	 = $row->Status;
			$mtype 		 = $row->MovementTypeName;
			$remarks 	 = $row->Remarks;
			$statusid 	 = $row->StatusID;
			$mid 		 = $row->MovementTypeID;
			$whouse 	 = $row->WarehouseName;
			$whouseid 	 = $row->WarehouseID;
			$branch 	 = $row->Branch;
			$createdby 	 = $row->CreatedBy;
			$confirmedby = $row->ConfirmedBy;
			$approvedby  = $row->ApprovedBy;
		}
	}

    if(isset($_POST['btnConfirm'])){
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
						$tmpactual = $_POST['txtActQuantity'.$ctr];
						$actual = number_format($tmpactual, 0, '.', '');
						$soh = $row->SOH;
						
						//update prevbalance
						$rs_update = $sp->spUpdateInvAdjDetailsByID($database, $txnid, $prodid, $soh, $actual);
						if (!$rs_update){
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					}
				}

		  	    $rs_update = $sp->spApproveMiscTransaction($database, $txnid, $_SESSION['emp_id']);
		  	    if (!$rs_update){
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
		  	    $database->commitTransaction();
		    	$message = 'Successfully approved transaction.';
		    	redirect_to("index.php?pageid=119&msg=$message");				
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("index.php?pageid=119.1&tid=$txnid&message=$message");
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
				redirect_to("index.php?pageid=119.1&tid=$txnid&message=$message");				
			}
			else
			{
				$rs_update = $sp->spDeleteCycleCount($database, $txnid);
		  	    if (!$rs_update)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				$database->commitTransaction();
		    	$message = 'Successfully deleted transaction.';
		    	redirect_to("index.php?pageid=119&msg=$message");				
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("index.php?pageid=119.1&tid=$txnid&message=$message");
		}    	    	
    }
    
    if(isset($_POST['btnCancel']))
	{
		redirect_to("index.php?pageid=119");		
	}	
?>