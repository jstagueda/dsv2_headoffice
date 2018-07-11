<?php
	global $database;
	//ini_set('max_execution_time', 300);
	
	$errmsg = "";
	$refNo = "";
	$docNo = "";
	$remarks = "";
	$datetoday = date("m/d/Y");
	$whid = 0;
	$createdby = "";
	
	$rs_refNo = $sp->spGetMaxID($database, 6, "inventorycount");
	if($rs_refNo->num_rows)
	{
		while($row = $rs_refNo->fetch_object())
		{
			$trno = $row->txnno;
		}
		
		if ($trno == '')
		{
			$trno = "IC00000001";
		}
		$rs_refNo->close();
	}
	
	$rs_createdby = $sp->spSelectUserEmployee($database, $session->user_id);
	if($rs_createdby->num_rows)
	{
		while($row = $rs_createdby->fetch_object())
		{
			$createdby = $row->EmployeeName;
		}
		$rs_createdby->close();
	}
	
	$rs_whouse = $sp->spSelectWarehouse($database,0, "");
	if (isset($_POST['cboWarehouse'])) 
	{
		$whid = $_POST['cboWarehouse'];
	}
	else
	{
		$whid = 0;
	}
	
	if(isset($_POST['btnSavePrint'])) 
	{
		try
		{
			$InvCID = 0;
			$database->beginTransaction();	
		    $docno = $_POST['txtDocNo'];
		    $whouseid = $_POST['cboWarehouse'];
			$remarks = $_POST['txtRemarks'];
			$createdby = $_SESSION['emp_id'];			
			$dmydate = date("Y-m-d");
			
			//check if transaction alrady exists.
			$rs_exists = $sp->spCheckInvCountDocNoWarehouse($database, $docno, $whouseid);
			if($rs_exists->num_rows)
			{
				$msg = "A worksheet for this Document No. and Warehouse already exists. Please go to Edit Workshet.";
				redirect_to("index.php?pageid=57&errmsg=$msg");				
			}
			else
			{							
				//insert to inventorycount header
				$rs_ICID = $sp->spInsertInvCount($database, 'null', 15, $docno, $dmydate, $whouseid, $remarks, $createdby);
	
				if (!$rs_ICID)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
						
				if($rs_ICID->num_rows)
				{  
					while($row = $rs_ICID->fetch_object())
					{
						$InvCID = $row->ID;
					}
				}			
				
				//insert into inventorycount details
				$sort = 1;
				$rs_location = $sp->spSelectLocationWS($database, $_POST['cboWarehouse']);
				if ($rs_location->num_rows)
				{
					while ($row_loc = $rs_location->fetch_object())
					{
						$rs_product = $sp->spSelectProductListForWorksheet($database, $_POST['cboWarehouse']);
						if ($rs_product->num_rows)
						{
							while ($row = $rs_product->fetch_object())
							{
								$affected_rows = $sp->spInsertInvCountDetails($database, $InvCID, $row->ProductID, $row->UnitTypeID, $row_loc->ID, 'null', 0, 1, $sort, 0);
							    if (!$affected_rows)
								{
								throw new Exception("An error occurred, please contact your system administrator.");
								}
								$sort += 1;
							}
							$rs_product->close();
						}
					}	
					$rs_location->close();		
				}
	
				$database->commitTransaction();
				$url = "'pages/inventory/inv_printCountTags.php?txnid=".$InvCID."&sort=0'";
				echo"<script type='text/javascript'>
						window.open($url, 'counttag', 'left=0, top=35, width=900, height=900, toolbar=0, resizable=1');
						location.href = 'index.php?pageid=100&message=Successfully created Count Tags.';
				</script>";
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=57&errmsg=$errmsg");
		}
	}
	else if(isset($_POST['btnSave'])) 
	{
		try
		{
			$InvCID = 0;
			$database->beginTransaction();	
		    $docno = $_POST['txtDocNo'];
		    $whouseid = $_POST['cboWarehouse'];
			$remarks = $_POST['txtRemarks'];
			$createdby = $_SESSION['emp_id'];			
			$dmydate = date("Y-m-d");
			
			//check if transaction alrady exists.
			$rs_exists = $sp->spCheckInvCountDocNoWarehouse($database, $docno, $whouseid);
			if($rs_exists->num_rows)
			{
				$msg = "A worksheet for this Document No. and Warehouse already exists. Please go to Edit Workshet.";
				redirect_to("index.php?pageid=57&errmsg=$msg");				
			}
			else
			{
				//insert to inventorycount header
				$rs_ICID = $sp->spInsertInvCount($database, 'null', 15, $docno, $dmydate, $whouseid, $remarks, $createdby);
				if (!$rs_ICID)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
						
				if($rs_ICID->num_rows)
				{  
					while($row = $rs_ICID->fetch_object())
					{
						$InvCID = $row->ID;
					}
				}			
				
				//insert into inventorycount details
				$sort = 1;
				$rs_location = $sp->spSelectLocationWS($database, $_POST['cboWarehouse']);
				if ($rs_location->num_rows)
				{
					while ($row_loc = $rs_location->fetch_object())
					{
						$rs_product = $sp->spSelectProductListForWorksheet($database, $_POST['cboWarehouse']);
						if ($rs_product->num_rows)
						{
							while ($row = $rs_product->fetch_object())
							{
								$affected_rows = $sp->spInsertInvCountDetails($database, $InvCID, $row->ProductID, $row->UnitTypeID, $row_loc->ID, 'null', 0, 1, $sort, 0);
							    if (!$affected_rows)
								{
								throw new Exception("An error occurred, please contact your system administrator.");
								}
								$sort += 1;
							}
							$rs_product->close();
						}
					}	
					$rs_location->close();		
				}
	
				$database->commitTransaction();
				$msg = "Successfully created Count Tag & Worksheet.";
				redirect_to("index.php?pageid=100&message=$msg");				
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=57&errmsg=$errmsg");
		}
	}
	else if(isset($_POST['btnCancel']))
	{
		redirect_to("index.php?pageid=100");		
	}
?>