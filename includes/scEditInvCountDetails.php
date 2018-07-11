<?php
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
	
	$rs_productsforprinting = $sp->spSelectInvCntDetByID($database, $txnid, 1);
	
	if (isset($_POST['btnSearch']))
	{
		$datecreate = date('Y-m-d', strtotime($_POST['txtCreatedDate']. " +1 day"));
		$rs_product = $sp->spEditProductListForWorksheet($database, $warehouseid, $txnid, $datecreate);
	}
	
	if (isset($_POST['btnCancel']))
	{
		redirect_to("index.php?pageid=133");		
	}	
	
	if (isset($_POST['btnAdd']))
	{
		try
		{
			$sort = 0;
			$database->beginTransaction();
			
			//get last count tag no.
			$rs_sort = $sp->spSelectInvCntDetByID($database, $txnid, 4);
			if ($rs_sort->num_rows)
			{
				while ($row = $rs_sort->fetch_object())
				{
					$osort = $row->numrows;
					$sort = $osort;
				}
				$rs_sort->close();
			}
			
			//add products
			$rs_location = $sp->spSelectLocationWS($database, $warehouseid);
			if ($rs_location->num_rows)
			{
				while ($row_loc = $rs_location->fetch_object())
				{
					foreach ($_POST['chkSelect'] as $key=>$value)
					{
						$sort += 1;
						$prodid = $_POST['hProdID'.$value];
						$uomid = $_POST['hUOMID'.$value];

						$affected_rows = $sp->spInsertInvCountDetails($database, $txnid, $prodid, $uomid, $row_loc->ID, 'null', 0, 1, $sort, 0);
					    if (!$affected_rows)
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}				
					}
				}
				$rs_location->close();
			}
			
			//update header
			$txndate = date('Y-m-d', strtotime($transdate));
			$affected_rows = $sp->spUpdateInventoryCountHeaderByID($database, $txnid, $refid, $docno, $txndate, $warehouseid, $remarks);
		    if (!$affected_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			
			$database->commitTransaction();
			$msg = "Successfully added product(s) in Worksheet.";
			redirect_to("index.php?pageid=133&message=$msg");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=133.1&tid=$txnid&errmsg=$msg");
		}
	}
	
	if (isset($_POST['btnAddPrint']))
	{
		try
		{
			$sort = 0;
			$database->beginTransaction();
			
			//get last count tag no.
			$rs_sort = $sp->spSelectInvCntDetByID($database, $txnid, 4);
			if ($rs_sort->num_rows)
			{
				while ($row = $rs_sort->fetch_object())
				{
					$osort = $row->numrows;
					$sort = $osort;
				}
				$rs_sort->close();
			}
			
			//add products
			$rs_location = $sp->spSelectLocationWS($database, $warehouseid);
			if ($rs_location->num_rows)
			{
				while ($row_loc = $rs_location->fetch_object())
				{
					foreach ($_POST['chkSelect'] as $key=>$value)
					{
						$sort += 1;
						$prodid = $_POST['hProdID'.$value];
						$uomid = $_POST['hUOMID'.$value];

						$affected_rows = $sp->spInsertInvCountDetails($database, $txnid, $prodid, $uomid, $row_loc->ID, 'null', 0, 1, $sort, 0);
					    if (!$affected_rows)
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}				
					}
				}
				$rs_location->close();
			}		
			
			$database->commitTransaction();
			$url = "'pages/inventory/inv_printCountTags.php?txnid=".$txnid."&sort=".$osort."'";
			echo"<script type='text/javascript'>
					window.open($url, 'counttag', 'left=0, top=35, width=900, height=900, toolbar=0, resizable=1');
					location.href = 'index.php?pageid=133&message=Successfully added product(s) in Worksheet.';
			</script>";
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=133.1&tid=$txnid&errmsg=$msg");
		}
	}
	
	if (isset($_POST['btnCancelPrint']))
	{
		redirect_to("index.php?pageid=134");		
	}		
?>
