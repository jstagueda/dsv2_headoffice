<?php
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	
	global $database;
	$islocked = 0;
	$lockedby = null;
	$table = 'officialreceipt';

	$txnid = $_GET['TxnID'];
	$rs_reason = $sp->spSelectReason($database, 8, '');

	if(isset($_POST['btnCancel']))
	{
		//unlock transaction
		/*try
		{
			$database->beginTransaction();*/
			$updatestatus = $sp->spUpdateLockStatus($database, $table, 0, 0, $txnid);
			if (!$updatestatus)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			/*$database->commitTransaction();			
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=96.1&msg=$errmsg&Txnid=$txnid");	
		}*/
		
		redirect_to("index.php?pageid=96");
	}

	//retrieve or header
	$rs_header = $sp->spViewORHeaderByID($database, $txnid);
	if ($rs_header->num_rows)
	{
		while ($row = $rs_header->fetch_object())
		{			
			$id = $row->ORID;
			$orno = $row->ORNo;
			$docno = $row->DocumentNo;
			$status = $row->TxnStatus;
			$custcode = $row->CustCode;
			$custname = $row->CustName;
			$tmpTxnDate = strtotime($row->ORDate);
			$txndate = date("m/d/Y", $tmpTxnDate);		
			$remarks = $row->Remarks;			
			$totalamt = number_format($row->TotalAmount, 2);
			$totalappamt = number_format($row->TotalAppliedAmt, 2);
			$totalunappamt = number_format($row->TotalUnappliedAmt, 2);
			$txnstatusId = $row->TxnStatusID;
			$orType = $row->ortypeNames;
			$prntcnt = $row->pc;
			$branch = $row->Branch;
			$createdby = $row->CreatedBy;
			$confirmedby = $row->ConfirmedBy;
			$cancelledby = $row->CancelledBy;
			$tmpLastModifiedDate = strtotime($row->LastModifiedDate);
			$lastmodifieddate = date("m/d/Y", $tmpLastModifiedDate);
			$ibmname = $row->IBMName;
			$reason = $row->reasonName;
			//$nonReason = $row->ReasonName;
		}
		$rs_header->close();
	}
	
	//retrieve or details
	$rs_details = $sp->spSelectORDetailsByID($database, $txnid); 
	
	//retrieve si by or
	$rs_silist = $sp->spSelectOfficialReceiptSIByID($database, $txnid, 1);
	$rs_silist_app = $sp->spSelectOfficialReceiptSIByID($database, $txnid, 1);
	
	$totapplied = 0;
	if ($rs_silist_app->num_rows)
	{				
		while ($row = $rs_silist_app->fetch_object())
		{
			$totapplied += $row->AmountApplied;
		}
	}
	
	
	$locked = 0;
	if(isset($_GET['locked']))
	{
		$locked = 1;
	}
	
	if($locked == 0)
	{
		try
		{
			$database->beginTransaction();
			
			$checkiflocked = $sp->spCheckIfTransactionIsLocked($database, $table, $txnid);
			if (!$checkiflocked)
			{
				$errmsg = "An error occurred, please contact your system administrator.";
				redirect_to("index.php?pageid=96&msg=$errmsg");
			}
			else
			{
				if ($checkiflocked->num_rows)
				{
					while ($row = $checkiflocked->fetch_object())
					{
						$islocked = $row->IsLocked;
						$lockedby = $row->FirstName." ".$row->LastName;					
					}				
				}
				
				if ($islocked == 1)
				{
					$errmsg = "Selected transaction is locked by ".$lockedby;
					redirect_to("index.php?pageid=96&msg=$errmsg");
				}
				else
				{
					$updatestatus = $sp->spUpdateLockStatus($database, $table, 1, $session->emp_id, $txnid);
					if (!$updatestatus)
					{
						$errmsg = "An error occurred, please contact your system administrator.";
						redirect_to("index.php?pageid=96&msg=$errmsg");
					}				
				}			
			}
			
			$database->commitTransaction();
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=96.1&msg=$errmsg");	
		}
	}
?>