<?php
	global $database;
	$islocked = 0;
	$lockedby = null;
	$table = 'dmcm';
	
	$txnid = $_GET['TxnID'];	
	//retrieve so header
	$rs_header = $sp->spSelectDMCMHeaderByID($database, $txnid);
	
	if ($rs_header->num_rows)
	{
		while ($row = $rs_header->fetch_object())
		{			
			$id = $row->TxnID;
			$dmcmno = $row->DMCMNo;
			$docno = $row->DocumentNo;
			$custcode = $row->Code;
			$custname = $row->Customer;
			$memotype = $row->MemoTypeName;
			$txndate = $row->TxnDate;
			$particularss = $row->Particulars;						
			$remarks = $row->Remarks;
			$status = $row->StatusName;									
			$totalamt = number_format($row->TotalAmt, 2);
			$reason = $row->ReasonName;					
			$ibm = $row->IBMName;
			$branch = $row->Branch;
		}
		$rs_header->close();
	}
	
	//retrieve so details
	$rs_details = $sp->spSelectDMCMDetailsByID($database, $txnid);
	
	//cancel transaction
	if(isset($_POST['btnCancel']))
	{
		//unlock transaction
		try
		{
			$database->beginTransaction();
			$updatestatus = $sp->spUpdateLockStatus($database, $table, 0, 0, $txnid);
			if (!$updatestatus)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			$database->commitTransaction();			
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=49.1&msg=$errmsg&Txnid=$txnid");	
		}
		
		redirect_to("index.php?pageid=49");
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
				redirect_to("index.php?pageid=49&msg=$errmsg");
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
					redirect_to("index.php?pageid=49&msg=$errmsg");
				}
				else
				{
					$updatestatus = $sp->spUpdateLockStatus($database, $table, 1, $session->emp_id, $txnid);
					if (!$updatestatus)
					{
						$errmsg = "An error occurred, please contact your system administrator.";
						redirect_to("index.php?pageid=49&msg=$errmsg");
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
