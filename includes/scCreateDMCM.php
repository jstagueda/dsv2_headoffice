<?php
	
	global $database;
	
	//get next orid
    $rs_dmcmNo = $sp->spGetMaxID($database, 5, "`dmcm`");
	if($rs_dmcmNo->num_rows)
	{
		while($row = $rs_dmcmNo->fetch_object())
		{
			$dmcmNo = $row->txnno;
			
			if ($dmcmNo == '')
			{
				$dmcmNo = "DMCM00000001";
			}
		}
		$rs_dmcmNo->close();
	}
	
	$rs_memoType = $sp->spSelectMemoType($database);
	$date = time();
	$today = date("m/d/Y",$date);
	$datetoday = date("m/d/Y");
	$message = '';
	$bir_series	= "";
	$amount_style = "";
	$bir_id = 0;

	if(!isset($tmpCustID))
	{
	   $tmpCustID = -1 ;
	}

	$DocumentNo = '';
	$TxnDate = $datetoday;
	$CustomerID = 0;
	$MemoTypeID = 0;
	$TotalAmt = 0;
	$Particulars = '';
	$Remarks = '';
 	$branch = "";
 	$employee = "";
	$rs_branch = $sp->spSelectBranch($database, -2, "");
	if ($rs_branch->num_rows)
	{
		while ($row = $rs_branch->fetch_object())
		{
			$branch = $row->Name ;
		}
	}
	
	$rsEmployee = $sp->spSelectEmployee($database, $_SESSION['emp_id'], "");
	
	//echo $rsEmployee->num_rows;
	if ($rsEmployee->num_rows)
	{
		while ($row = $rsEmployee->fetch_object())
		{
			$employee = $row->Name ;
			
		}
	}
	// save transaction
	if(isset($_POST['btnSave']))
	{
		try 
		{
			$database->beginTransaction();
			$DocumentNo = htmlentities(addslashes($_POST['txtDocNo']));
		   	$tmpTxnDate = strtotime($today);
		   	$TxnDate = date("Y-m-d", $tmpTxnDate);
		   	$CustomerID = $_POST["hCustomerID"];
		   	$MemoTypeID = $_POST['cboMemoType'];
		   	$TotalAmt = $_POST['txtMemoAmt'];
		   	$reasonId = $_POST['cboReason'];
		   	$Particulars = htmlentities(addslashes($_POST['txtParticulars']));
		   	$Remarks = htmlentities(addslashes($_POST['txtRemarks']));
		   	$userid = $session->emp_id;
		   	if ($_POST['txtTotPayment'] == "")
		   	{
		   		$totalAppliedAmount = 0;		   		
		   	}
		   	else
		   	{
		   		$totalAppliedAmount = $_POST['txtTotPayment'];
		   	}
		   	$totUnappliedAmount = $TotalAmt - $totalAppliedAmount;
		   	$totRows = $_POST['cntrows'];
		   	$birid = $_POST['txtBIRSeriesID'];
		   	
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
				$database->commitTransaction();  
				$message = "Cannot save transaction, Inventory Count is in progress.";
				redirect_to("index.php?pageid=46&msg=$message");				
			}
			else
			{
				//check if document number already exists
			   	$cnt_docno = $sp->spCheckDocumentNoIfExists($database, $DocumentNo, 'dmcm');
			   	if($cnt_docno->num_rows)
			   	{
			   		while($row = $cnt_docno->fetch_object())
			      	{
			      		$cntdocno = $row->cnt;
			      	}
				}
				
				if ($cntdocno != 0)
			   	{
			   		$message = "Document No. already exists.";
			      	//redirect_to("index.php?pageid=46&message=$message&custID=$CustomerID");
		      	}
			   	else
			   	{
			   		$rs_DMCMARID = $sp->spInsertDMCM($database, $DocumentNo, $TxnDate, $CustomerID, $MemoTypeID, $TotalAmt, $Particulars, $Remarks, $userid, $reasonId, $totalAppliedAmount, $totUnappliedAmount);
			   		if (!$rs_DMCMARID)
					{
						throw new Exception("An error occurred, please contact your system administrator");
					}
			       	if ($rs_DMCMARID->num_rows)
			      	{
			         	while($row = $rs_DMCMARID->fetch_object())
			         	{
			            	$DMCMARID = $row->ID;
			         	}
			      	}
			       
			      	if ($DMCMARID)
			      	{
			      		for($i = 1; $i <= $totRows; $i++)
			      		{
			      			if (isset($_POST['chkInclude'.$i]))
			      	      	{
			      	      		$sipNo = $_POST['hdnsipNo'.$i];
			      	      		$inpPayAmt = $_POST['txtPayAmt'. $i];
			      	      		$refType = $_POST['hdnRefType'.$i];

			      	      		$affected_rows = $sp->spInsertDMCMDetails($database, $DMCMARID, $sipNo, $inpPayAmt, $refType, $userid);
			      	      		if (!$affected_rows)
								{
									throw new Exception("An error occurred, please contact your system administrator");
								}		 
			      	      	}
		      	      	}
		      			
		      			//update bir series
		      			if ($MemoTypeID == 1)
		      			{
		      				$rs_update = $sp->spUpdateBIRSeriesByBranchID($database, $birid, 5);	      				
		      			}
		      			else
		      			{
		      				$rs_update = $sp->spUpdateBIRSeriesByBranchID($database, $birid, 6);
		      			}
						if (!$rs_update)
						{ 
							throw new Exception("An error occurred, please contact your system administrator");
						}

		      			$database->commitTransaction();      	
			      		$message = "Successfully created DMCM.";
			      		redirect_to("index.php?pageid=49&msg=$message");
		      		}
		      	}				
			}
		}
		catch (Exception $e)  
		{
			$database->rollbackTransaction();
			$message = $e->getMessage();
			redirect_to("index.php?pageid=46&msg=$message");
		}
   	}
?>
