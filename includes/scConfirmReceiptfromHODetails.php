<?php
	
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}

	global $database;
	global $dtxnID;
	
	if(!isset($_GET['prodlist']))
	{
		$_GET['prodlist'] = "";				
	}	
	
	$txnid = $_GET['tid'];
	$statid = 0;
	//$dtxnID = 0;
	$warehouseid = 0;
	$rtid = 0;
	$search = "";
	$actQuantity = 0;
	
	$rs = $sp->spSelectInventoryInRHODetailsbyID($database, $txnid);
	$rs_deswarehouse = $sp->spSelectDesWarehouse($database, $warehouseid);
	
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
			//$pcklistrefno = $row->PicklistRefNo;			
			$docno = $row->DocNo;
			$sbrancid = $row->SBID;
			//$bbrancid = $row->RBID;
			$shipmntvia = $row->ModeOfShipment;
			$wid = $row->WID;
			$shipmentdate = $row->ShipDate;
			$txndate = $row->TxnDate;
			$lastmoddate = $row->LastModifiedDate;
			$warehouse = $row->WarehouseName;
			$remarks = $row->Remarks;
			$statid = $row->StatusID;
			$status = $row->Status;
			$confirmedby = $row->ConfirmedBy;
			$lastmoddate = $row->LastModifiedDate;
			$frombranch = $row->FromBranch;
			$tobranch = $row->ToBranch;
		}
	}
	$rs_detailsall = $sp->spSelectInvInRHODetails($database, $search, $txnid, $wid);
	
	if(isset($_POST['btnCancel'])) 
	{
		redirect_to("index.php?pageid=26");	
	}
	else if(isset($_POST['btnSave'])) 
	{
		try
		{
			$database->beginTransaction();
			$ctr = 0;
			$docno = htmlentities(addslashes($_POST['txtDocNo']));
			$remarks = htmlentities(addslashes($_POST['txtRemarks']));
			$date = date("");
			$datetoday = date("m/d/Y");	
			$txndate =  date('Y-m-d',strtotime($datetoday));			
			/* $date = date("");
			$txndate = date("m/d/Y");	*/	
			
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
				redirect_to("index.php?pageid=26&errmsg=$message");				
			}
			else
			{
			
			#echo '<pre>';var_dump($_POST);echo '</pre>';
				//update details
				if ($rs_detailsall->num_rows)
				{
					while ($row = $rs_detailsall->fetch_object())
					{
						$ctr += 1;
						$prodid = $_POST['hdnProductID'.$ctr];			
						$dtxnID = $_POST['hdndtxnID'.$ctr];			
						$qty = $_POST['hdnQuantity'.$ctr];
						$invid = $_POST['hdnInventoryID'.$ctr];
						$tmpsoh = $_POST['hdnSOH'.$ctr];
						//$soh = number_format($tmpsoh, 0, '.', '');
						$soh = $tmpsoh;
						$tmpactual = $_POST['txtActQuantity'.$ctr];
						//$actual = number_format($tmpactual, 0, '.', '');
						$actual = $tmpactual;
									
						if (isset($_POST['cboReasons'.$ctr]))
						{
							$reason = $_POST['cboReasons'.$ctr];					
						}
						else
						{
							$reason = 0;
						}
						
						#throw new Exception("error dito $dtxnID,$prodid");
						//$rs_details = $sp->spUpdateInventoryInOutDetailsConfirm($database, $txnid, $actual, $reason, $prodid);	
						
						$rs_details = $sp->spUpdateInventoryInOutDetailsConfirm($database, $txnid, $actual, $reason, $prodid, $dtxnID);		
						
				        		
						if (!$rs_details)
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}
	
						//stocklog
						$rs_stocklog = $stocklog->AddToStockLog($wid, $invid, 1, "", "", "", $prodid, 0, $txnid, $docno, $remarks, $invtypeid, $actual, $txndate);	
					}
				}

				//insert added products
				if (isset($_GET['prodlist']) && $_GET['prodlist'] != "")
		        {
					$prodlist_url = split(',', $_GET['prodlist']);
		          	$_SESSION['prod_list'] = $prodlist_url;
		          	
		          	for ($i = 0, $n = sizeof($_SESSION['prod_list']); $i < $n; $i++ )
		          	{
		          		$ctr += 1;
		          		$prodid = $_POST['hdnProductID'.$ctr];			
						$qty = $_POST['hdnQuantity'.$ctr];
						$invid = $_POST['hdnInventoryID'.$ctr];
						$tmpsoh = $_POST['hdnSOH'.$ctr];
						//$soh = number_format($tmpsoh, 0, '.', '');
						$soh = $tmpsoh;
						$tmpactual = $_POST['txtActQuantity'.$ctr];
						//$actual = number_format($tmpactual, 0, '.', '');
						$actual = $tmpactual;
						if (isset($_POST['cboReasons'.$ctr]))
						{
							$reason = $_POST['cboReasons'.$ctr];					
						}
						else
						{
							$reason = 0;
						}
						
						$rs_details = $sp->spInsertInventoryInOutDetails($database,$txnid, $prodid, 1, $soh, 0, $actual, $reason);		
		          		if (!$rs_details)
						{
						throw new Exception("An error occurred, please contact your system administrator.");
						}
						//stocklog
						$rs_stocklog = $stocklog->AddToStockLog($wid, $invid, 1, "", "", "", $prodid, 0, $txnid, $docno, $remarks, $invtypeid, $actual, $txndate);          		
		          	}
				}
				
				//update header
				$rs_header = $sp->spUpdateInventoryInOutConfirm($database,$txnid, $remarks, $session->emp_id, $txndate);
				if (!$rs_header)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				$database->commitTransaction();
			  	$message = "Transaction successfully Confirmed.";
		  	  	redirect_to("index.php?pageid=26&msg=$message");					
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=26&errmsg=$errmsg");
		}
	}
	else if(isset($_POST['btnAdd'])) 
	{
		try
		{
			$database->beginTransaction();
			//update details
			$ctr = 0;
			if ($rs_detailsall->num_rows)
			{
				while ($row = $rs_detailsall->fetch_object())
				{
					$ctr += 1;
					$prodid = $_POST['hdnProductID'.$ctr];		
					$dtxnID = $_POST['hdndtxnID'.$ctr];	
					$qty = $_POST['hdnQuantity'.$ctr];
					$invid = $_POST['hdnInventoryID'.$ctr];
					$tmpsoh = $_POST['hdnSOH'.$ctr];
					$soh = number_format($tmpsoh, 0, '.', '');
					$tmpactual = $_POST['txtActQuantity'.$ctr];
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
                    					
				   
				   //throw new Exception('error here  $dtxnID,$prodid');
					$rs_details = $sp->spUpdateInventoryInOutDetailsConfirm($database, $txnid, $actual, $reason, $prodid, $dtxnID);		
					
					if (!$rs_details)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}	
				}
			}
			
			if (isset($_GET['prodlist']) && $_GET['prodlist'] != "")
			{
				$prodlist_url = split(',', $_GET['prodlist']);
				$_SESSION['prod_list'] = $prodlist_url;
                                            	
				for ($i = 0, $n = sizeof($_SESSION['prod_list']); $i < $n; $i++ ) 
				{
					$rs_proddet = $sp->spSelectProductbyID($database, $_SESSION['prod_list'][$i], 1);
                    if ($rs_proddet->num_rows)
					{
						while ($row = $rs_proddet->fetch_object())
						{
							$ctr++;      
							$pid = $row->ID;														
							$tmpactual2 = $_POST['txtActQuantity'.$ctr];
							$actualAdd = number_format($tmpactual2, 0, '.', '');
							$tmpsoh2 = $_POST['hdnSOH'.$ctr];
							$sohAdd = number_format($tmpsoh, 0, '.', '');
							if (isset($_POST['cboReasons'.$ctr]))
							{
								$reasonAdd = $_POST['cboReasons'.$ctr];					
							}
							else
							{
								$reasonAdd = 0;
							}
														
							$rs_detailsInsNew = $sp->spInsertInventoryInOutDetails($database,$txnid, $pid, 1, $sohAdd, 0, $actualAdd, $reasonAdd);		
							if (!$rs_detailsInsNew)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}
						}
						$rs_proddet->close();                                            			
					}                                            		
				}
			} 
					
			//update remarks
			$remarks = htmlentities(addslashes($_POST['txtRemarks']));
			$date = date("");
			$datetoday = date("m/d/Y");	
			$txndate =  date('Y-m-d',strtotime($datetoday));	
			$rs_header = $sp->spUpdateInventoryInOutConfirmHeader($database, $txnid, $remarks);
			if (!$rs_header)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			$database->commitTransaction();
			$prodlist = "";
			

			redirect_to("index.php?pageid=26.1&tid=$txnid&statid=$statid&prodlist=$prodlist");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=26&errmsg=$errmsg");
		}
	}
?>