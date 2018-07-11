<?php
/*
Author: @Gino C. Leabres
*/
	
   	global $database;
	$sessionID= session_id();
	$custID = "";
	$custCode = "";
	$custName = "";
	$ibm = "";
	$sino = "";
	$docno = "";
	$refno = "";
	$branch = "";
	$invoiceDate = "";
	$createdby = "";
	$status = "";
	$confirmedby = "";
	$dateconfirmed = "";
	$remarks = "";	
	
	

	//cancel
   	if(isset($_POST['btnCancel']))
	{
		//$errmsg= "Transaction Cancelled";
		$rsClearTmpTable = $sp->spDeleteTmpProdExchangebySessionID($database, $sessionID);
		
		redirect_to("index.php?pageid=160&msg=$errmsg");	
	}
	
	//next
	
		//if(isset($_POST['btnNext']))
		//{
		//	$rsClearTmpTable = $sp->spDeleteTmpProdExchangebySessionID($database, $sessionID);
		//	$cnt = $_POST['hcnt'];	
		//	$txnID = $_POST['hSIID'];
		//	//echo $cnt;
		//	for($i = 1 ; $i <= $cnt ; $i ++)
		//	{
		//		if(isset($_POST['chkID'.$i]))
		//		{
		//			$original_qty = $_POST['qty'.$i];
		//			$qty = $_POST['txtQty'.$i];
		//			$reason = $_POST['cboReason'.$i];
		//			$productID = $_POST['hProductID'.$i];
		//			if($qty <= $original_qty) {
		//				for($j=1 ; $j<=$qty ; $j++) {
		//						$rsInsertTmpProdExchange = $sp->spInsertTmpProdExchange($database,$sessionID , $txnID , 1 , $reason , $productID );
		//				}
		//				redirect_to("index.php?pageid=159.1&txnID=$txnID");	
		//			} else{
		//				echo"<script type='text/javascript'>";	
		//				echo"alert('Quantity Should Not Exceed');";	
		//				echo"</script>";	
		//			}
		//		}
		//	}
		//	
		//	//redirect_to("index.php?pageid=159.1&txnID=$txnID");		
		//}
	
	if(isset($_GET['txnID']))
	{
		$txnID = $_GET['txnID'];
		$rsHeader = $sp->spSelectTmpProductExchangeHeader($database,$txnID,$sessionID);
		if($rsHeader->num_rows)
		{
			while($row = $rsHeader->fetch_object())
			{
				$custID = $row->custID;
				$custCode = $row->custCode;
				$custName = $row->custName;
				$ibm = $row->ibm;
				$sino = $row->SINo;
				$siid = $row->siid;
				$docno = $row->DocumentNo;
				$refno = $row->RefNo;
				$branch = $row->BranchName;
				$invoiceDate = $row->EffectivityDate;
				$createdby = $row->CreatedBy;
				$status = $row->TxnStatus;
				$confirmedby = $row->ConfirmedBy;
				$dateconfirmed = $row->DateConfirmed;
				$remarks = $row->Remarks;	
						
			}
		}
		$rsDetails = $sp->spSelectTmpProductExchangeDetails($database,$txnID,$sessionID);
		
	}
	if(isset($_POST['btnPrint']))
	{
		try
		{

			$database->beginTransaction();
			
			$txnID = $_POST['hSIID'];
			$userID = $_SESSION['emp_id'];
			$insertHeader = $sp->spInsertProductExchangeHeader($database,$txnID,$userID, 7);
			$prodExID = "";
			if($insertHeader->num_rows)
			{
				while($row = $insertHeader->fetch_object())
				{
					$prodExID = $row->txnID;
					$rsDetails = $sp->spSelectTmpProductExchangeDetails($database,$txnID,$sessionID);
					if($rsDetails->num_rows)
					{
						$i = 1;
						// echo  $_POST['hProdExchangeID'.$i];
						// exit;
						while($details = $rsDetails->fetch_object())
						{

							$prodID = $details->prodID;
							$qty = $details->qty;
							$reasonID = $details->reasonID;
							$exchangeID = $_POST['hProdExchangeID'.$i];
							

							$insertDetails = $sp->spInsertProductExchangeDetails($database,$prodExID, $prodID, $exchangeID,$qty , $reasonID);
							$i ++;
							$rsGetInv = $sp->spSelectAvailableInventoryForProductExchange($database,$prodID, 1); 
							
							if($rsGetInv->num_rows)
							{
								

								while($returnedInv = $rsGetInv->fetch_object())
								{

									$InvID = $returnedInv->InventoryID;
									$rs_stocklog = $stocklog->AddToStockLog(1, $InvID, 1, "", "", "", $prodID, 0, $prodExID, "Product Exchange", "Product Exchange", 18, $qty, date("Y-m-d H:i:s",strtotime(date("Y-m-d"))));
								}
							}
							$rsGetInv2 = $sp->spSelectAvailableInventoryForProductExchange($database,$exchangeID, 1); 
							if($rsGetInv2->num_rows)
							{

								while($exchangedInv = $rsGetInv2->fetch_object())
								{
									$InvID = $exchangedInv->InventoryID;
									$rs_stocklog = $stocklog->AddToStockLog(1, $InvID, 1, "", "", "", $exchangeID, 0, $prodExID, "Product Exchange", "Product Exchange", 19, $qty, date("Y-m-d H:i:s",strtotime(date("Y-m-d"))));
								}
							}
						}
					}
				}
			}
			printpdf($prodExID);
			$database->commitTransaction();	
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			echo $errmsg;
			exit;
			redirect_to("index.php?pageid=18");	
		}
		
	}
	
	if(isset($_POST['btnConfirm']))
	{
	
		try
		{

			$database->beginTransaction();
			
			$txnID = $_POST['hSIID'];
			$userID = $_SESSION['emp_id'];
			$statusID  = 7;
			$insertHeader = $sp->spInsertProductExchangeHeader($database,$txnID,$userID,$statusID);
			$prodExID = "";
			if($insertHeader->num_rows)
			{
				while($row = $insertHeader->fetch_object())
				{
					$prodExID = $row->txnID;
					$rsDetails = $sp->spSelectTmpProductExchangeDetails($database,$txnID,$sessionID);
					if($rsDetails->num_rows)
					{
						$i = 1;
						// echo  $_POST['hProdExchangeID'.$i];
						// exit;
						while($details = $rsDetails->fetch_object())
						{

							$prodID = $details->prodID;
							$qty = $details->qty;
							$exchangeID = $_POST['hProdExchangeID'.$i];
							$reasonID = $details->reasonID;

							$insertDetails = $sp->spInsertProductExchangeDetails($database,$prodExID, $prodID, $exchangeID, $qty , $reasonID);
							$i ++;
							$rsGetInv = $sp->spSelectAvailableInventoryForProductExchange($database,$prodID, 1); 
							
							if($rsGetInv->num_rows)
							{
								

								while($returnedInv = $rsGetInv->fetch_object())
								{
									$InvID = $returnedInv->InventoryID;
									$rs_stocklog = $stocklog->AddToStockLog(1, $InvID, 1, "", "", "", $prodID, 0, $prodExID, "Product Exchange", "Product Exchange", 18, $qty, date("Y-m-d H:i:s",strtotime(date("Y-m-d"))));
								}
							}
							$rsGetInv2 = $sp->spSelectAvailableInventoryForProductExchange($database,$exchangeID, 1); 
							if($rsGetInv2->num_rows)
							{
								
								while($exchangedInv = $rsGetInv2->fetch_object())
								{
									$InvID = $exchangedInv->InventoryID;
									$rs_stocklog = $stocklog->AddToStockLog(1, $InvID, 1, "", "", "", $exchangeID, 0, $prodExID, "Product Exchange", "Product Exchange", 19, $qty, date("Y-m-d H:i:s",strtotime(date("Y-m-d"))));
								}
							}
						}
					}
				}
			}
			//printpdf($prodExID);
			$database->commitTransaction();	
			redirect_to('index.php?pageid=160&msg=Successfully created Product Exchange.');
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			echo $errmsg;
			exit;
			redirect_to("index.php?pageid=18");	
		}
		
	}
	
	if(isset($_POST['btnSave']))
	{
		try
		{

			$database->beginTransaction();
			
			$txnID = $_POST['hSIID'];
			$userID = $_SESSION['emp_id'];
			$statusID  = 6;
			$insertHeader = $sp->spInsertProductExchangeHeader($database,$txnID,$userID,$statusID);
			$prodExID = "";
			if($insertHeader->num_rows)
			{
				while($row = $insertHeader->fetch_object())
				{
					$prodExID = $row->txnID;
					$rsDetails = $sp->spSelectTmpProductExchangeDetails($database,$txnID,$sessionID);
					if($rsDetails->num_rows)
					{
						$i = 1;
						// echo  $_POST['hProdExchangeID'.$i];
						// exit;
						while($details = $rsDetails->fetch_object())
						{

							$prodID = $details->prodID;
							$qty = $details->qty;
							$reasonID = $details->reasonID;
							$exchangeID = $_POST['hProdExchangeID'.$i];
							$insertDetails = $sp->spInsertProductExchangeDetails($database,$prodExID, $prodID, $exchangeID, $qty , $reasonID);
							$i ++;
						}
					}
				}
			}
			$database->commitTransaction();	
			redirect_to('index.php?pageid=160&msg=Successfully created Product Exchange.');
		}
		
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			echo $errmsg;
			exit;
			redirect_to("index.php?pageid=18");	
		}
	}
	
	if(isset($_POST['btnConfirmSave']))
	{

			
		try
		{
			//echo "<pre>";
			//print_r($_POST);
			//echo "</pre>";
			//die();
			$database->beginTransaction();
			
			$txnID = $_POST['hSIID'];
			$userID = $_SESSION['emp_id'];
			$ID = $_POST['TxnID'];

			$statusID  = 7;
			$updateHeader =$sp->spUpdateProductExchangeHeaderv1($database, $ID, $statusID);
			//$updateHeader =  $database->execute('SELECT ID txnID FROM productexchange WHERE ID = '.$ID);
			$prodExID = "";
			if($updateHeader->num_rows)
			{

	
				while($row = $updateHeader->fetch_object())
				{
					$prodExID = $row->txnID;					
					// echo $prodExID;
					// die();
					//$rsDetails = $sp->spSelectProductExchangeDetails($database,$ID);
						
				
					$rsDetails = $database->execute("SELECT  ped.ID, ped.ProductID,p.ProductLevelID, p.ID prodID,p.Code prodCode,
															 p.ParentID, p.ParentID prodLineID, p.Name prodName, uom.Name uom,
															 t.Code pmg, ped.qty, sd.UnitPrice, sd.`TotalAmount`, r.Name reason,
															 r.ID reasonID, p2.Code ExchangeCode, p2.Name ExchangeName,tp.StatusID
													 FROM productexchange tp
													 INNER JOIN salesinvoice si ON si.ID = tp.SalesInvoiceID
													 INNER JOIN productexchangedetails ped ON ped.ProductExchangeID = tp.ID
													 INNER JOIN salesinvoicedetails sd ON sd.SalesInvoiceID = si.ID  AND sd.ProductID = ped.ProductID
													 INNER JOIN product p ON p.ID = ped.ProductID
													 LEFT JOIN unittype uom ON uom.ID = sd.UnitTypeID
													 LEFT JOIN `tpi_pmg` t ON t.ID = sd.`PMGID`
													 INNER JOIN reason r ON r.ID = ped.ReasonID
													 INNER JOIN product p2 ON p2.ID = ped.exchangeproductID
													 WHERE tp.ID = ".$ID);
					
		
					if($rsDetails->num_rows)
					{
						$i = 1;
						// echo  $_POST['hProdExchangeID'.$i];
						// exit;
						while($details = $rsDetails->fetch_object()){

							$prodID 	 = $details->prodID;
							$qty 		 = $details->qty;
							$reasonID	 = $details->reasonID;
							$exchangeID  = $_POST['hProdExchangeID'.$i];
							$ID			 = $details->ID;
							
							
							    $database->execute("UPDATE productexchangedetails SET
														ProductID = ".$prodID.",
														ExchangeProductID = ".$exchangeID.",
														Qty = ".$qty.",
														ReasonID = ".$reasonID."
													WHERE ID = ".$ID);
    
							//$updateDetails = $sp->spUpdateProductExchangeDetails($database,$prodExID, $prodID, $exchangeID, $qty, $reasonID);
							//$updateDetails = $sp->spInsertProductExchangeDetails($database,$prodExID, $prodID, $exchangeID, $qty , $reasonID);
							$i ++;
							$rsGetInv = $sp->spSelectAvailableInventoryForProductExchange($database,$prodID, 1); 
							
							if($rsGetInv->num_rows){
								while($returnedInv = $rsGetInv->fetch_object()){
									$InvID = $returnedInv->InventoryID;
									$rs_stocklog = $stocklog->AddToStockLog(1, $InvID, 1, "", "", "", $prodID, 0, $prodExID, "Product Exchange", "Product Exchange", 18, $qty, date("Y-m-d H:i:s",strtotime(date("Y-m-d"))));
								}
							}
							
							$rsGetInv2 = $sp->spSelectAvailableInventoryForProductExchange($database,$exchangeID, 1); 
							if($rsGetInv2->num_rows){
								while($exchangedInv = $rsGetInv2->fetch_object()){
									$InvID = $exchangedInv->InventoryID;
									$rs_stocklog = $stocklog->AddToStockLog(1, $InvID, 1, "", "", "", $exchangeID, 0, $prodExID, "Product Exchange", "Product Exchange", 19, $qty, date("Y-m-d H:i:s",strtotime(date("Y-m-d"))));
								}
							}
						}
					}
				}
			}
			// printpdf($prodExID);
			$database->commitTransaction();	
			redirect_to('index.php?pageid=160&msg=Successfully created Product Exchange.');
		}
		
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			echo $errmsg;
			exit;
			redirect_to("index.php?pageid=18");	
		}
	}
	
	
	function printpdf($exchangeID)
	{
		echo"<script language=JavaScript>
							function NewWindow(mypage, myname, w, h, scroll) 
								{
									var winl = (screen.width - w) / 2;
									var wint = (screen.height - h) / 2;
									winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
									win = window.open(mypage, myname, winprops)
									if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
								}
		
							
							pagetoprint = 'pages/sales/sales_productExchange_print.php?TxnID=".$exchangeID."&prntcnt=0';
							NewWindow(pagetoprint,'printps','850','1100','yes');
							location.href='index.php?pageid=159&msg=Successfully created Product Exchange.';
						
						</script>";	
		
	
	}
	
	
	
?>