<?php
	require_once("../initialize.php");
	global $database;
	
	$date = time();
	$today = date("m/d/Y",$date);
	
	if(isset($_POST['startdate']))
	{
		$datetoday = $_POST['startdate'];
	}
	else
	{
		$datetoday = $today;
	}
   	
	if(isset($_POST['btnAdd']))
	{
		$vSearch = trim($_POST['txtSearch']);				
		$_SESSION["trans_refNo"] = $_POST['txtReferenceNo'];
		$_SESSION["trans_DocNo"] = $_POST['txtDocNo'];
		$_SESSION["trans_movementType"] = $_POST['cboMovementType'];
		$_SESSION["trans_sourceWarehouse"] = $_POST['hdncboWarehouse'];
		$_SESSION["trans_destinationWarehouse"] = $_POST['hdncboDesWarehouse'];
		$_SESSION["trans_txnDate"] = $_POST["startDate"];
		$_SESSION["trans_remarks"] = $_POST["txtRemarks"];		
	
	    $r = 0;
		$arrQty = sizeof($_POST['hdnInventoryID']);		
	
		for($i = 0; $i < $arrQty; $i++)
		{
		  	if ($_POST['txtquantity'][$i] != "") 
		  	{				  		 		
		  		$newarrayid[$r] 	= $_POST['hdnInventoryID'][$i];
		  		$newarrayqty[$r]   	= $_POST['txtquantity'][$i];
		  		$newarrayuom[$r] 	= $_POST['hdnUOM'][$i]; 
		  		$newarrayreason[$r]	= $_POST['cboReason'][$i];
		  		$r++;
		  	} 
		}
			
		$session->set_transfer($newarrayid, $newarrayqty, $newarrayuom, $newarrayreason);
		$limit = sizeof($session->prod_add_trans);
		$trans_id = $session->trans_id;			
		
		if (!($limit))
		{		
			for($i = 0; $i < sizeof($trans_id); $i++)
			{		
				$invid = $session->trans_id[$i];				
				$rs_proddet = $sp->spSelectProductInvTransfer($database, $invid,$_POST['hdncboWarehouse'],10);
					
				while($row = $rs_proddet->fetch_object())
				{			
					$session->set_trans_prod(
							$session->trans_id[$i], 
							$row->ProductID,
							$row->ProductCode,
						   	$row->ProductName, 
						   	$row->SOH, 
						   	$session->trans_uom[$i], 
						   	$session->trans_qty[$i],
						   	$session->trans_reason[$i]
						   	);	
						   	
				}
				$rs_proddet->close();					
			}			
		}
		else
		{	
			$formlimit = sizeof($session->trans_id);
			for($i = 0; $i < $formlimit; $i++)
			{
				$action = "New";
				$f_invid = $session->trans_id[$i];
				$f_uom   = $session->trans_uom[$i];
				$f_rea   = $session->trans_reason[$i];
	       
				for ($y = 0; $y < $limit; $y++)
				{
					$invid = $session->prod_add_trans[$y]['InventoryID'];
					$uom = $session->prod_add_trans[$y]['UOM'];
					$rea = $session->prod_add_trans[$y]['Reason'];
				
					if (($invid == $f_invid) && ($uom == $f_uom) && ($f_rea == $rea))
					{
						$action = "Update";
						break;
					}
				}

				if($action == "Update")
				{	
					$addedQty = $session->prod_add_trans[$y]['Qty'] + $session->trans_qty[$i];
					$lastSelectedReason =  $session->trans_reason[$i];
				  	$session->set_trans_prod_qty($y, $addedQty,$lastSelectedReason);				  	
				}
				else if ($action == "New")
				{					
					$invid = $session->trans_id[$i];					
					$rs_proddet = $sp->spSelectProductInvTransfer($database, $invid, $_POST['hdncboWarehouse'], 10);
				
					while($row = $rs_proddet->fetch_object())
					{
						$session->set_trans_prod(
								$session->trans_id[$i], 
								$row->ProductID, 
								$row->ProductCode,
							   	$row->ProductName, 
							   	$row->SOH, 
							   	$session->trans_uom[$i], 
							   	$session->trans_qty[$i], 
							   	$session->trans_reason[$i]
							   	);			
					}
					$rs_proddet->close();					
				}					
			}		
		}
		$txtd = $_POST['txtDocNo'];		
		$swid = $_POST['hdncboWarehouse'];
		$mvid = $_POST['hdncboMovement'];
		$desid = $_POST['hdncboDesWarehouse'];
		$plid = 0;
		$docno = $_POST['txtDocNo'];
		$remarks = $_POST['txtRemarks'];
		$txnDate=$_POST['startDate'];
		
		redirect_to("../index.php?pageid=25&swid=$swid&plid=$plid&mvid=$mvid&desid=$desid&docNo=$docno&remarks=$remarks&txnDate=$txnDate&search=$vSearch");
	}
	else if(isset($_POST['btnRemoveInv']))		
	{
		$tmp_prodlist = $_SESSION['trans_prodlist'];
		$n = sizeof($tmp_prodlist);
		if(isset($_POST['chkIID']))
		{
			foreach($_POST['chkIID'] as $key => $value)
			{			
				$prodid = $value;
				$rs_proddet = $sp->spSelectProductListInv($database, $prodid, $_GET['wid']);		
				
				if ($rs_proddet->num_rows)
                {
              		while ($row = $rs_proddet->fetch_object())
              		{
              			$pid = $row->ProductID;
          			}
          			
					for ($i = 0; $i < $n; $i++)
					{					
						if (isset($tmp_prodlist[$i]))
						{		
							if ($pid == $tmp_prodlist[$i]) 
							{
								unset($tmp_prodlist[$i]);
								break;
							}
						}
					}
				}
				
			    unset($_SESSION['trans_prodlist']);
				$hasvalue = 0;
				
				for ($i = 0; $i < $n; $i++)
				{
					if (isset($tmp_prodlist[$i]))
					{	 
						$_SESSION['trans_prodlist'][] = $tmp_prodlist[$i];
						if($hasvalue != "")
						{
							$hasvalue .= ','.$tmp_prodlist[$i];
						}
						else
						{
							$hasvalue .= $tmp_prodlist[$i];
						}
					}		
				}
			}
		}
		
		$docno = $_POST['txtDocNo'];		
		$wid = $_POST['hdncboWarehouse'];
		$mid = $_POST['hdncboMovement'];
		$desid = $_POST['hdncboDesWarehouse'];		
		$docno = $_POST['txtDocNo'];
		$remarks = $_POST['txtRemarks'];
		$txnDate=$_POST['startDate'];
		if ($hasvalue != 0)
		{
			$hasvalue = 1;
		}
	
		redirect_to("../index.php?pageid=25&dno=$docno&mtypeid=$mid&wid=$wid&remarks=$remarks&prodlist=$hasvalue&desid=$desid");
		
//		$vSearch = trim($_POST['txtSearch']);		
//		$limit = sizeof($session->prod_add_trans);
//		$tmp_prod = $session->prod_add_trans;
//		foreach($_POST['chkIID'] as $key => $value)
//		{			
//			$inventoryid =	"hdnIID_$value";
//			$uomid = "hdnUID_$value";
//			for ($i = 0; $i < $limit; $i++)
//			{					
//				if (isset($tmp_prod[$i]['InventoryID']))
//				{		
//					if (($_POST[$inventoryid] == $tmp_prod[$i]['InventoryID']) && ($_POST[$uomid] == $tmp_prod[$i]['UOM'])) 
//					{									
//						unset($tmp_prod[$i]);
//						break;
//					}
//				}
//			}
//		}
//		
//		$session->unset_trans_prod();
//		
//		for ($i = 0; $i < $limit; $i++)
//		{
//			if (isset($tmp_prod[$i]['InventoryID']))
//			{	 
//				$session->set_trans_prod(
//						$tmp_prod[$i]['InventoryID'], 
//						$tmp_prod[$i]['ProductID'], 
//						$tmp_prod[$i]['ProductCode'],
//					   	$tmp_prod[$i]['ProductName'], 
//					   	$tmp_prod[$i]['SOH'], 
//					   	$tmp_prod[$i]['UOM'], 
//					   	$tmp_prod[$i]['Qty'], 					    
//					   	$tmp_prod[$i]['Reason']
//					   	);						
//			}					
//		}
//		
//		$msg = "Successfully removed product(s).";		
//		
//		$txtd = $_POST['txtDocNo'];		
//		$swid = $_POST['hdncboWarehouse'];
//		$mvid = $_POST['hdncboMovement'];
//		$desid = $_POST['hdncboDesWarehouse'];
//		$plid = $_POST['hdncboProdLine'];
//		$docno = $_POST['txtDocNo'];
//		$remarks = $_POST['txtRemarks'];
//		$txnDate=$_POST['startDate'];
//		
//		redirect_to("../index.php?pageid=25&swid=$swid&plid=$plid&mvid=$mvid&desid=$desid&docNo=$docno&remarks=$remarks&&msg=$msg&txnDate=$txnDate&search=$vSearch");
	}
	else if (isset($_POST['btnSaveInv']))	
	{
		try
		{
			$documentNo = trim(htmlentities(addslashes($_POST["txtDocNo"]))); 				
			$tmptxndate = strtotime($_POST['startDate']);		
			$transactionDate = date("Y-m-d", $tmptxndate);
			$fromWarehouseID = $_POST['hdncboWarehouse'];			
			$toWarehouseID = $_POST['hdncboDesWarehouse'];	
			$remarks = trim(htmlentities(addslashes($_POST["txtRemarks"]))); 
			$createdBy =$session->emp_id;
			$confirmedBy = null;
		    $movementTypeID =$_POST['hdncboMovement'];
		   	$branchId = $_POST['txtBranchID'] ;
		    
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
				redirect_to("../index.php?pageid=25&message=$message&swid=$fromWarehouseID&docNo=$documentNo&desid=$toWarehouseID&remarks=$remarks&mvid=$movementTypeID&search=$vSearch");
			}
			else
			{
				//check if document number already exists
				if($documentNo != "")
				{
					$cnt_docno = $sp->spCheckDocumentNoIfExists($database, $documentNo, 'inventorytransfer');
					if($cnt_docno->num_rows)
					{
						while($row = $cnt_docno->fetch_object())
						{
							$cntdocno = $row->cnt;
						}
					}
					else
					{
						$cntdocno = 0;						
					}
				}
				else
				{
					$cntdocno = 0;
				}
				
				if ($cntdocno != 0)
				{
					$message = "Document No. already exists.";
					redirect_to("../index.php?pageid=25&message=$message&swid=$fromWarehouseID");			
				}
				else
				{
					$database->beginTransaction();			
					$rs_TID = $sp->spInsertTransfer($database, $movementTypeID, $documentNo, $fromWarehouseID, $toWarehouseID, $transactionDate, $remarks, $createdBy, $branchId);
					if (!$rs_TID)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
					$tmpTransID = 0;                            
					
					if($rs_TID->num_rows)
					{
						while($row = $rs_TID->fetch_object())
						{
							$TransID = $row->ID;
							$tmpTransID = $TransID;
						}
					}
					
					$r = 0;
				  	if (isset($_SESSION['trans_prodlist']) && $_SESSION['trans_prodlist'] != "")
                 	{
	                 	$cnt = 0;
	                   	//$prodlist_url = split(',', $_GET['prodlist']);
	                    //$_SESSION['prod_list'] = $prodlist_url;
	                                
	                    for ($i = 0, $n = sizeof($_SESSION['trans_prodlist']); $i < $n; $i++ ) 
                     	{
                     		$cnt ++;
                     		$newarrayid[$r] 	= $_POST['hdnInventoryID'.$cnt];
						  	$newarrayqty[$r]   	= $_POST['txtQuantity'.$cnt];
						  	$newarrayuom[$r] 	= $_POST['hdnUOM'.$cnt]; 
						  	$newarrayreason[$r]	= $_POST['cboReasons'.$cnt];
						  	$r++; 
                         }
                 	}
					
//					$r = 0;
//					$arrQty = sizeof($_POST['hdnInventoryID']);		
				
//					for($i = 0; $i < $arrQty; $i++)
//					{
//					  	if ($_POST['txtquantity'][$i] != "") 
//					  	{		
//					  		echo $_POST['txtquantity'][$i];		  		 		
//					  		$newarrayid[$r] 	= $_POST['hdnInventoryID'.$cnt];
//					  		$newarrayqty[$r]   	= $_POST['txtquantity'.$cnt];
//					  		$newarrayuom[$r] 	= $_POST['hdnUOM'.$cnt]; 
//					  		$newarrayreason[$r]	= $_POST['cboReason'.$cnt];
//					  		$r++;
//					  	} 
//					}
						
					$session->set_transfer($newarrayid, $newarrayqty, $newarrayuom, $newarrayreason);
					$limit = sizeof($session->prod_add_trans);
					$trans_id = $session->trans_id;			
					
					if (!($limit))
					{		
						for($i = 0; $i < sizeof($trans_id); $i++)
						{		
							$invid = $session->trans_id[$i];				
							$rs_proddet = $sp->spSelectProductInvTransfer($database, $invid,$_POST['hdncboWarehouse'],10);
								
							while($row = $rs_proddet->fetch_object())
							{			
								$session->set_trans_prod(
										$session->trans_id[$i], 
										$row->ProductID,
										$row->ProductCode,
									   	$row->ProductName, 
									   	$row->SOH, 
									   	$session->trans_uom[$i], 
									   	$session->trans_qty[$i],
									   	$session->trans_reason[$i]
									   	);	
									   	
							}
							$rs_proddet->close();					
						}			
					}
					else
					{	
						$formlimit = sizeof($session->trans_id);
						for($i = 0; $i < $formlimit; $i++)
						{
							$action = "New";
							$f_invid = $session->trans_id[$i];
							$f_uom   = $session->trans_uom[$i];
							$f_rea   = $session->trans_reason[$i];
				       
							for ($y = 0; $y < $limit; $y++)
							{
								$invid = $session->prod_add_trans[$y]['InventoryID'];
								$uom = $session->prod_add_trans[$y]['UOM'];
								$rea = $session->prod_add_trans[$y]['Reason'];
							
								if (($invid == $f_invid) && ($uom == $f_uom) && ($f_rea == $rea))
								{
									$action = "Update";
									break;
								}
							}
			
							if($action == "Update")
							{	
								$addedQty = $session->prod_add_trans[$y]['Qty'] + $session->trans_qty[$i];
								$lastSelectedReason =  $session->trans_reason[$i];
							  	$session->set_trans_prod_qty($y, $addedQty,$lastSelectedReason);				  	
							}
							else if ($action == "New")
							{					
								$invid = $session->trans_id[$i];					
								$rs_proddet = $sp->spSelectProductInvTransfer($database, $invid, $_POST['hdncboWarehouse'], 10);
							
								while($row = $rs_proddet->fetch_object())
								{
									$session->set_trans_prod(
											$session->trans_id[$i], 
											$row->ProductID, 
											$row->ProductCode,
										   	$row->ProductName, 
										   	$row->SOH, 
										   	$session->trans_uom[$i], 
										   	$session->trans_qty[$i], 
										   	$session->trans_reason[$i]
										   	);			
								}
								$rs_proddet->close();					
							}					
						}		
					}
								
					
					for ($i = 0, $n = sizeof($_SESSION['prod_add_trans']); $i < $n; $i++ ) 
					{			
						$v_invid		= 0;
						$v_productid 	= $_SESSION['prod_add_trans'][$i]['ProductID'];
						$v_unitTypeid 	= $_SESSION['prod_add_trans'][$i]['UOM'];
						$v_qty 			= $_SESSION['prod_add_trans'][$i]['Qty'];
						$v_reason 		= $_SESSION['prod_add_trans'][$i]['Reason'];
						$v_soh   		= $_SESSION['prod_add_trans'][$i]['SOH'];
						$affected_rows = $sp->spInsertTransferDetails($database, $tmpTransID, $v_productid, $v_unitTypeid, $v_soh, $v_qty, $v_reason);
						if (!$affected_rows)
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					}
				
					$database->commitTransaction();		
					$message = "Successfully created Inventory Transfer.";
					redirect_to("../index.php?pageid=29&msg=$message");				
				}				
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage();
			redirect_to("../index.php?pageid=25&message=$message&swid=$fromWarehouseID&docNo=$documentNo&desid=$toWarehouseID&remarks=$remarks&mvid=$movementTypeID&search=$vSearch");
		}
	}
	else if (isset($_POST['btnCancelInv']))
	{
		$session->unset_trans_prod();
		redirect_to("../index.php?pageid=29");			
	}
	else if(isset($_POST['btnSearch']))	
	{
		$_GET['prodlist'] = 1;
		if (isset($_GET['wid'])) { $fromWarehouseID = $_GET['wid']; } else { $fromWarehouseID = 0; };
		if (isset($_GET['desid'])) { $toWarehouseID = $_GET['desid']; } else { $toWarehouseID = 0; };
		if (isset($_GET['mtypeid'])) { $movementType = $_GET['mtypeid']; } else { $movementType = 0; };
		$vSearch = "";	
		$docno = $_POST['txtDocNo'];
		$remarks = $_POST['txtRemarks'];
		$prod = "";
		
	 	if (isset($_SESSION['trans_prodlist']) && $_SESSION['trans_prodlist'] != "")
     	{
       		$cnt = 0;
           	//$prodlist_url = split(',', $_GET['prodlist']);
           	//$_SESSION['prod_list'] = $prodlist_url;
                                        
       		for ($i = 0, $n = sizeof($_SESSION['trans_prodlist']); $i < $n; $i++ ) 
        	{
            	if($prod != "")
				{
					$prod .= ','.$_SESSION['trans_prodlist'][$i];
				}
				else
				{
					$prod .= $_SESSION['trans_prodlist'][$i];
				}	
            }
     	}
		redirect_to("../index.php?pageid=25&wid=$fromWarehouseID&plid=0&dno=$docno&desid=$toWarehouseID&remarks=$remarks&mtypeid=$movementType&prodlist=1&search=$vSearch");
	}
?>