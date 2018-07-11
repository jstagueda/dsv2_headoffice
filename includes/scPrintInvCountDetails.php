<?php
	include CS_PATH.DS."ClassInventory.php";
	global $database;
	$datetoday = date("m/d/Y");
	
	if (isset($_GET["tid"]))
	{
		$txnid = $_GET["tid"];
	}
	else
	{
		$txnid = 0;
	}
	
	$rs_invCnt = $sp->spSelectInvCntbyID($database, $txnid);
	if ($rs_invCnt->num_rows)
	{
		while ($row = $rs_invCnt->fetch_object())
		{			
			$transno = $row->ID;
			$transdate = $row->TransactionDate;
			$lastmodified = $row->LastModifiedDate;
			$docno = $row->DocumentNo;
			$status = $row->Status;
			$mtype = $row->MovementType;
			$remarks = $row->Remarks;
			$statusid = $row->StatusID;
			$refid = $row->refID;
			if ($refid == null)
			{
				$refid = "null";				
			}
			else
			{
				$refid = $row->refID;				
			}
			$invID = $row->invID;
			$warehouseid = $row->WarehouseID;
			$warehouse = $row->Warehouse;
			$statusid = $row->StatusID;
			$createdby = $row->EmployeeName;
		}
		$rs_invCnt->close();
	}
	
	$rs_productsforprinting = $tpiInventory->spSelectInvCntDetByID($database, $txnid, 1);
	
	if (isset($_POST['btnPrint']))
	{
		try
		{
			$database->beginTransaction();
			
			//delete existing
			$affected_rows = $sp->spDeleteProductListForPrinting($database, $txnid, session_id());
		    if (!$affected_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}	
				
			foreach ($_POST['chkSelect'] as $key=>$value)
			{
				$prodid = $_POST['hProdID'.$value];

				$affected_rows = $sp->spPrintCountTags($database, $txnid, $prodid, session_id());
			    if (!$affected_rows)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}				
			}
						
			$database->commitTransaction();
			$url = "'pages/inventory/inv_printCountTags.php?txnid=".$txnid."&sort=2'";
				echo"<script type='text/javascript'>
						window.open($url, 'counttag', 'left=0, top=35, width=900, height=900, toolbar=0, resizable=1');
						location.href = 'index.php?pageid=134&message=Successfully printed Count Tags.';
				</script>";
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=134.1&tid=$txnid&errmsg=$msg");
		}
	}
	
	if (isset($_POST['btnCancel']))
	{
		redirect_to("index.php?pageid=134");		
	}	
?>
