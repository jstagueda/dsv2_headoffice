<?php
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}

	global $database;
	
	if(!isset($_GET['prodlist']))
	{
		$_GET['prodlist'] = "";				
	}	
	
	$txnid = $_GET['tid'];
	$statid = 0;
	$rtid = 0;
	$search = "";
	$actQuantity = 0;
	$dateconfirmed="";

	$rs = $sp->spSelectInventoryOutSTADetailsbyID($database, $txnid);
	
	if(isset($_POST['btnSearch']))
	{
		$search = addslashes($_POST['txtSearch']);	
	}	
	
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{	
			$id = $row->TxnID;		
			$txnno = $row->TxnNo;
			$invtypeid = $row->InvType;
			$invtype = $row->MTName;		
			$docno = $row->DocNo;
			$sbrancid = $row->SBID;
			$sbranch = $row->IssuingBranch;
			$bbrancid = $row->RBID;
			$rbranch = '';
			$rbranch = $row->ReceivingBranch;
			$wid = $row->WID;
			//$datecreated = $row->DateCreated;
			$txndate = $row->TxnDate;
			$shipdate = $row->shipdate;
			$dateconfirmed = $row->DateConfirmed;
			$warehouse = $row->WarehouseName;
			$remarks = $row->Remarks;
			$statid = $row->StatusID;
			$status = $row->Status;
			$confirmedby = $row->ConfirmedBy;
		}
	}
	$rs_detailsall = $sp->spSelectInvInSTADetails($database,$search,$txnid,$wid);
	$rs_detailsall_soh = $sp->spSelectInvInSTADetails($database,$search,$txnid,$wid);
	
	if(isset($_POST['btnCancel'])) 
	{
		redirect_to("index.php?pageid=27");	
	}
	else if(isset($_POST['btnSave'])) 
	{
		try
		{			
	
			$database->beginTransaction();
			$ctr = 0;
			$ctr_soh = 0;
			$cnt_soh = 0;
			$docno = htmlentities(addslashes($_POST['txtDocNo']));
			$remarks = htmlentities(addslashes($_POST['txtRemarks']));
		/*	$tmptxndate = strtotime($_POST['startDate']);
			$txndate = date("Y-m-d", $tmptxndate);*/
			$date = date("");
			$datetoday = date("m/d/Y");	
			$txndate =  date('Y-m-d',strtotime($datetoday));	
			
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
				redirect_to("index.php?pageid=27&errmsg=$message");				
			}
			else
			{
				//check soh availability
				if ($rs_detailsall_soh->num_rows)
				{
					while ($row = $rs_detailsall_soh->fetch_object())
					{
						$ctr_soh += 1;
						$invid = $_POST['hdnInventoryID'.$ctr_soh];
						if($_POST['txtActQuantity'.$ctr_soh] == ""){
							$tmpactual = 0;
						}else{
							$tmpactual = $_POST['txtActQuantity'.$ctr_soh];
						}
						$actual = number_format($tmpactual, 0, '.', '');
						$rs_inv = $sp->spCheckSOH($database, $invid);
						if ($rs_inv->num_rows)
						{
							while ($row_soh = $rs_inv->fetch_object())
							{
								if ($actual > $row_soh->SOH)
								{
									$cnt_soh = 1;
									break;							
								}
							}					
						}
		
					}			
				}
		
				if ($cnt_soh == 0)
				{
					//update details
					if ($rs_detailsall->num_rows)
					{
						while ($row = $rs_detailsall->fetch_object())
						{
							$ctr += 1;
							$prodid = $_POST['hdnProductID'.$ctr];		
                            $dtxnid = $_POST['hdndtxnID'.$ctr];  							
							$qty = $_POST['hdnQuantity'.$ctr];
							$invid = $_POST['hdnInventoryID'.$ctr];
							$tmpsoh = $_POST['hdnSOH'.$ctr];
							$soh = number_format($tmpsoh, 0, '.', '');
							if($_POST['txtActQuantity'.$ctr] == ""){
								$tmpactual = 0;
							}else{
								$tmpactual = $_POST['txtActQuantity'.$ctr];
							}
							$actual = number_format($tmpactual, 0, '.', '');
							if (isset($_POST['cboReasons'.$ctr]))
							{
								$reason = $_POST['cboReasons'.$ctr];					
							}
							else
							{
								$reason = 0;
							}
							//$rs_details = $sp->spUpdateInventoryInOutDetailsConfirm($database, $txnid, $actual, $reason, $prodid);
							$rs_details = $sp->spUpdateInventoryInOutDetailsConfirm($database, $txnid, $actual, $reason, $prodid, $dtxnid);
							if (!$rs_details)
							{
							throw new Exception("An error occurred, please contact your system administrator.");
							}		
							//stocklog
							$rs_stocklog = $stocklog->AddToStockLog($wid, $invid, 1, "", "", "", $prodid, 0, $txnid, $docno, $remarks, $invtypeid, $actual, $txndate);	
						}
					}
					
					//update header
					$rs_header = $sp->spUpdateInventoryInOutConfirm($database, $txnid, $remarks, $session->emp_id, $txndate);	
					if (!$rs_header)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
				  	$message = "Transaction successfully Confirmed.";			
				}
				else
				{
					$message = "Actual quantity is greater than SOH. Cannot confirm transaction.";			
				}
				$database->commitTransaction();
		  	  	redirect_to("index.php?pageid=27&msg=$message");					
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=27&errmsg=$errmsg");	
		}
	}
?>