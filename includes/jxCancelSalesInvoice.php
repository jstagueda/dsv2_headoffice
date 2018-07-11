<?php

   require_once "../initialize.php";
   global $database;
   
   $islocked = 0;
   $lockedby = null;
   $table = 'salesinvoice';

   $salesInvoiceId = $_POST['salesInvoiceId'];
   $customerId = $_POST['customerId'];
   $reasonId = $_POST['reasonId'];
   $remarks = $_POST['remarks'];
   $cancelledBy = $session->emp_id;
   $tmptxndate = strtotime($_POST['txnDate']);
   $txnDate = date("Y-m-d", $tmptxndate);
   $totalCFT = $_POST['totalCFT'];
   $totalNCFT = $_POST['totalNCFT'];

   try
   {
   		$database->beginTransaction();

   		//update SI   
		$queryUpdate = $sp->spUpdateSalesInvoiceCancel($database, $salesInvoiceId, $reasonId, $remarks, $cancelledBy);

		if (!$queryUpdate)
		{
			throw new Exception("An error occurred, please contact your system administrator.");
		}
		
		$cancelSI = new CancelSI();	
		$cancelSI->CancelSalesInvoice($database,$salesInvoiceId,$customerId);
		
		//reverse inventory		
		$rs_sidetails = $sp->spSelectSalesInvoiceDetailsByID($database, $salesInvoiceId);
		if ($rs_sidetails->num_rows) 
		{
			while ($row = $rs_sidetails->fetch_object()) 
			{
				$quantity = $row->ServedQty;
				$productId = $row->ProductID;
				$inventoryId = 0;
				
				$rs_inv = $sp->spGetInventoryIDWarehouseIDProductID($database,$productId, 1);
				if ($rs_inv->num_rows) 
				{
					while ($row2 = $rs_inv->fetch_object()) 
					{
						$inventoryId = $row2->InventoryID;
						break;
					}
				}
				$stocklog->AddToStockLog(1, $inventoryId, 1, "", "", "", $productId, 0, $salesInvoiceId, '', $remarks, 14, $quantity, $txnDate); 
			}
		}
		$database->commitTransaction();
		
		//unlock transaction
		/*try
		{
			$database->beginTransaction();*/
			$updatestatus = $sp->spUpdateLockStatus($database, $table, 0, 0, $salesInvoiceId);
			if (!$updatestatus)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			/*$database->commitTransaction();			
		}*/
		/*catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=40.1&msg=$errmsg&txnid=$salesInvoiceId");	
		}*/
	}
	catch (Exception $e)
	{
		$database->rollbackTransaction();	
		$errmsg = $e->getMessage()."\n";	
	}
?>	