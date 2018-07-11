<?php

	global $database;
	$islocked = 0;
	$lockedby = null;
	$table = 'provisionalreceipt';
	$txnid = $_GET['TxnID'];
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	
	$rs_reason = $sp->spSelectReason($database, 10, '');

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
			redirect_to("index.php?pageid=51.1&msg=$errmsg&Txnid=$txnid");	
		}*/
		
		redirect_to("index.php?pageid=51");
	}
	
	$totalwords="";
	$amtcomnt    = "";
	
	$rsBranch = $sp->spSelectBranchOR($database);
	if($rsBranch->num_rows)
	{
		while ($row = $rsBranch->fetch_object())
		{
			$permitno 	= $row->PermitNo;
			$branchname = $row->Name;
			$tinno 		= $row->TIN;
		 	$address    = $row->StreetAdd;
			$serversn   = $row->ServerSN;
			$branchcode = $row->Code;			
		}
	}
	
	$txnid = $_GET['TxnID'];
	//retrieve or header
	$rs_header = $sp->spViewPRHeaderByID($database, $txnid);
	
	if ($rs_header->num_rows)
	{
		while ($row = $rs_header->fetch_object())
		{			
			$id = $row->PRID;
			$orno = $row->PRNo;
			$docno = $row->DocumentNo;
			$status = $row->TxnStatus;
			$custcode = $row->CustCode;
			$custname = $row->CustName;
			$tmpTxnDate = strtotime($row->ORDate);
			$txndate = date("m/d/Y", $tmpTxnDate);		
			$remarks = $row->Remarks;			
			$totalamt = number_format($row->TotalAmount, 2);
			$txnstatusId = $row->TxnStatusID;
			$ibmName = $row->IBMName;
			$time =$row->TxnTime;
			$number = explode(".", str_replace(",", "", number_format($row->TotalAmount, 2)));
			$createdby = $row->CreatedBy;
			$confirmedby = $row->ConfirmedBy;
			
			if (convert_number($number[1]) == "zero")
			{
				$number[1] = "Only";
			} 
			else 
			{
				$number[1] = " and ".$number[1]." centavos";
			}
			$totalwords  = strtoupper(convert_number($number[0])." PESO(S) ".$number[1]);
			
		}
		$rs_header->close();
	}
	
	
	//retrieve or details
	$rs_details = $sp->spSelectPRDetailsByID($database, $txnid); 
	$rs_detailsForPDF = $sp->spSelectPRDetailsByID($database, $txnid); 
	
	if ($rs_detailsForPDF->num_rows)
	{
		while($row = $rs_detailsForPDF->fetch_object())
		{			
			$amtcomnt = "CHEQUE (".$row->CheckNumber.")";
		}
	}
	
	$rs_selectBranch = $sp->spSelectBranchTransfer($database);
	if($rs_selectBranch->num_rows)
	{
		while($row = $rs_selectBranch->fetch_object())
		{
			$branchName = $row->BranchName;	
			$branchID = $row->ID;			
		}
	}
	
	$rs_CreatedBy = $sp->spSelectUserEmployee($database,$session->emp_id);
	if($rs_CreatedBy->num_rows)
	{
		while($row = $rs_CreatedBy->fetch_object())
		{
			$userName = $row->EmployeeName;	
			$userID = $row->EmployeeID;			
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
					redirect_to("index.php?pageid=51&msg=$errmsg");
				}
				else
				{
					$updatestatus = $sp->spUpdateLockStatus($database, $table, 1, $session->emp_id, $txnid);
					if (!$updatestatus)
					{
						$errmsg = "An error occurred, please contact your system administrator.";
						redirect_to("index.php?pageid=51&msg=$errmsg");
					}				
				}			
			}
			
			$database->commitTransaction();
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=51.1&msg=$errmsg");	
		}
	}
?>