<?php
	require_once("../initialize.php");
	include CS_PATH.DS."ClassInventory.php";
	global $database;

	
  	if(isset($_POST['btnConfirm'])) 
	{	
		try
		{
			$database->beginTransaction();
			
			$ctr = 0;
			$txnid = htmlentities(addslashes($_POST['htxnid']));
			$docno = htmlentities(addslashes($_POST['txtDocNo']));
			$remarks = htmlentities(addslashes($_POST['txtRemarks']));
			$tmptxndate = $_POST['txtTxnDate'];
			//$txndate = date("Y-m-d", $tmptxndate);
			$txndate = date('Y-m-d',strtotime($_GET['$tmptxndate']));
	
			//update details
			$rs_detailsall2 = $tpiInventory->spSelectInvCntDetByID($database, $txnid, 5);
			if ($rs_detailsall2->num_rows){
				while ($row = $rs_detailsall2->fetch_object())
				{
					$prodid = $row->ProductID;			
					$invid = $row->invdid;
					$actual = $row->CreatedQty;
					$warehouseid = $row->WarehouseID;
					$inventoryid = $row->InventoryID;
					$soh = $row->SOH;
					
					if ($soh > $actual){
						$inv_qty = -($soh - $actual);					
					}else{
						$inv_qty = $actual - $soh;					
					}
					$rs_details = $sp->spUpadateInventoryCountStatus($database,$txnid);	
					if (!$rs_details){
					throw new Exception("An error occurred, please contact your system administrator.");
					}		
					//stocklog
					$rs_stocklog = $stocklog->AddToStockLog($warehouseid, $inventoryid, 1, "", "", "", $prodid, 0, $txnid, $docno, $remarks, 6, $inv_qty, $tmptxndate);	
				}
			}
			
			$database->commitTransaction();
			$message = "Successfully confirmed Inventory Count.";
			redirect_to("../index.php?pageid=32&msg=$message");
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
			redirect_to("../index.php?pageid=32&errmsg=$errmsg");		
		}
	}	
?>
