<?php 
	global $database;
	require_once("../initialize.php");
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	
	if(isset($_POST['btnDelete']))
	{
		try
		{
			$database->beginTransaction();
			$txnid = $_GET['tid'];
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
			
			if ($statusid_inv == 21)
			{
				$message = "Cannot save transaction, Inventory Count is in progress.";
				redirect_to("../index.php?pageid=29.1&msg=$message&tid=$txnid");				
			}
			else
			{
				$affected_rows = $sp->spDeleteTransfer($database, $txnid, $session->emp_id);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				$database->commitTransaction();
				
				$message = "Successfully cancelled transaction.";
				redirect_to("../index.php?pageid=29&msg=$message");				
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage();
			redirect_to("../index.php?pageid=29.1&msg=$message&tid=$txnid");
		}
	}	
	elseif(isset($_POST['btnConfirm'])) 
	{
		try
		{
			$database->beginTransaction();
			$cnt = 0;
			$txnid = $_GET['tid'];
			$mtypeid = $_POST['hdnMTypeID'];
			$swhid = $_POST['hdnSWhouseID'];
			$dwhid = $_POST['hdnDWhouseID'];
			$docno = $_POST['hdnDocNo'];
			$remarks = $_POST['txtRemarks'];
			$txndate = date('Y-m-d', strtotime($_POST['hdnTxnDate']));
						
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
			
			if ($statusid_inv == 21)
			{
				$message = "Cannot save transaction, Inventory Count is in progress.";
				redirect_to("../index.php?pageid=29.1&msg=$message&tid=$txnid");				
			}
			else
			{
				$rs_detailsall = $sp->spSelectTransferDetailsByID($database, $txnid, 6);
				if ($rs_detailsall->num_rows)
				{
					while ($row = $rs_detailsall->fetch_object())
					{
				  		$invid = $row->InventoryID;
						$tmpqty = $row->Quantity;
						$qty = number_format($tmpqty, 0, '.', '');
				  
				  		$rs = $sp->spCheckSOH($database, $invid);			  		
				  		$tmpsoh = $row->PrevBalance;
				  		$soh = number_format($tmpsoh, 0, '.', '');
						
						if ($soh < $qty)
						{
							$cnt += 1;
						}
					}
				}
			
				$txnid = $_POST['hdnTxnID'];
				foreach ($_POST["chkID2"] as $key => $value) 
				{		
														
					$tmpqty = $_POST["txtQuantity{$value}"];
					$qty = number_format($tmpqty, 0, '.', '');
					$reasid = $_POST["cboReasons{$value}"];
					
					$affected_rows = $sp->spUpdateInvTrandferDetDraft($database, $txnid, $value, $qty, $reasid);
				}

				$rs_detailsall = $sp->spSelectTransferDetailsByID($database, $txnid, 6);
				if ($cnt < 1)
				{
					if ($rs_detailsall->num_rows)
					{
						while ($row = $rs_detailsall->fetch_object())
						{			
							$prodid = $row->ProductID;	
							$invid = $row->InventoryID;
							$toinvid = $row->ToInventoryID;
							$tmpqty = $row->Quantity;
							$qty = number_format($tmpqty, 0, '.', '');
							$tmpsoh = $row->SOH;
							$soh = number_format($tmpsoh, 0, '.', '');
							
							if ($mtypeid == 7)
							{
								$mtypeid_2 = 15;
							}
							else
							{
								$mtypeid_2 = 16;
							}
					  		
					  		//update prevbalance
							$rs_update = $sp->spUpdateInvTransferDetailsByID($database, $txnid, $prodid, $soh);
							if (!$rs_update)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}
						
					  		//update stocklog
							$rs_stocklog = $stocklog->AddToStockLog($dwhid, $toinvid, 1, "", "", "", $prodid, 0, $txnid, $docno, $remarks, $mtypeid_2, $qty, $txndate);
							$rs_stocklog = $stocklog->AddToStockLog($swhid, $invid, 1, "", "", "", $prodid, 0, $txnid, $docno, $remarks, $mtypeid, $qty, $txndate);
						}
					}
	
			  	  	$remarks = htmlentities(addslashes($_POST['txtRemarks']));
				  	$cby = $session->emp_id;
				  	$affected_rows = $sp->spUpdateTransferConfirm($database, $txnid, $remarks, $cby);
				  	if (!$affected_rows)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
				  	$database->commitTransaction();  
				  	$message = "Successfully confirmed transaction.";
			  	  	redirect_to("../index.php?pageid=29&msg=$message");
		  	  	}
		  	  	else
		  	  	{
		  	  		$message = "Transferred quantity is greater than SOH. Cannot confirm transaction.";
		  	  		redirect_to("../index.php?pageid=29&msg=$message");
		  		}				
			}	
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage();
			redirect_to("../index.php?pageid=29.1&msg=$message&tid=$txnid");
		}
  	}
			
	if(isset($_POST['btnRemove']))	
	{
		try
		{
			$database->beginTransaction();	
			$txnid = $_POST['hdnTxnID'];		
			foreach ($_POST['chkID'] as $key=>$value) 
			{
				$txnid = $_POST['hdnTxnID'];
				$prodid = $value;
				$affected_rows = $sp->spDeleteTransferDetails($database, $txnid, $prodid);
				if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
			}				
			$database->commitTransaction();
			$message = "Product(s) successfully removed.";
			redirect_to("../index.php?pageid=29.1&tid=$txnid&msg=$message");
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage();
			redirect_to("../index.php?pageid=29.1&msg=$message&tid=$txnid");
		}
	}
	
	if(isset($_POST['btnDraft']))	
	{
		try
		{
			$database->beginTransaction();	
			$txnid = $_POST['hdnTxnID'];
			
			$rs_detailsall = $sp->spSelectTransferDetailsByID($database, $txnid, 6);
			if ($rs_detailsall->num_rows)
			{
				while ($row = $rs_detailsall->fetch_object())
				{
			  		$invid = $row->InventoryID;
					$tmpqty = $row->Quantity;
					$qty = number_format($tmpqty, 0, '.', '');
			  
			  		$rs = $sp->spCheckSOH($database, $invid);			  		
			  		$tmpsoh = $row->PrevBalance;
			  		$soh = number_format($tmpsoh, 0, '.', '');
					
					if ($soh < $qty)
					{
						$cnt += 1;
					}
				}
			}
			
			if ($cnt < 1)
			{
				foreach ($_POST["chkID2"] as $key => $value) 
				{												
					$qty = $_POST["txtQuantity{$value}"];
					$reasid = $_POST["cboReasons{$value}"];
					
					$affected_rows = $sp->spUpdateInvTrandferDetDraft($database, $txnid, $value, $qty, $reasid);
				}
				$message = "Transaction successfully updated.";				
			}
			else
	  	  	{
	  	  		$message = "Transferred quantity is greater than SOH. Cannot confirm transaction.";
	  		}		
			
			$database->commitTransaction();
			redirect_to("../index.php?pageid=29.1&tid=$txnid&msg=$message");
		}
		catch(Exception $e)
		{
		    $database->rollbackTransaction();
			$message = $e->getMessage();
			redirect_to("../index.php?pageid=29.1&msg=$message&tid=$txnid");
		}
	}
	if(isset($_POST['btnCancel']))
	{
		redirect_to("../index.php?pageid=29");		
	}
?>