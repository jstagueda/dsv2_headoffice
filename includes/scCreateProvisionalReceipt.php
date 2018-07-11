<?php
	
	global $database;
	
	//set variables
   	ini_set('error_reporting', E_ERROR);
   	if (!ini_get('display_errors')) 
   	{
   		ini_set('display_errors', 1);
   	}
	
	$rem='';
	$customerName='';
	if (isset($_POST['btnCancel']))
	{
		$session->unset_trans_prod();
		redirect_to("index.php?pageid=18");			
	}
	
	if(!isset($_POST['hCOA']))
	{	
		$custID = 0 ;
	}
	else 
	{
		$custID =  $_POST['hCOA'];
	}
	
	if(!isset($_POST['txtDocNo']))
	{	
		$docNo = '' ;
	}
	else 
	{
		$docNo =  $_POST['txtDocNo'];
	}
	
	if(!isset($_POST['txtRemarks']))
	{	
		$remarks ='' ;
	}
	else 
	{
		$remarks =  $_POST['txtRemarks'];
	}

	if(!isset($_POST['txtCustomer']))
	{	
		$custName ='' ;
	}
	else 
	{
		$custName =  $_POST['txtCustomer'];
	}
	
	if (isset($_GET['custID']))
	
	if ($_GET['custID'] != $_SESSION["coll_add_custid"])
	{
		unset($_SESSION["coll_mop"]);
		unset($_SESSION["tmp_coll_mop"]);
		unset($_SESSION["coll_addsi"]);			
	}
	
	//get next orid
	$rs_prno = $sp->spGetMaxID($database, 4, "`provisionalreceipt`");
	if($rs_prno->num_rows)
	{
		while($row = $rs_prno->fetch_object())
		{
			$prno = $row->txnno;
			
			if ($prno == '')
			{
				$prno = "PR00000001";
			}
		}
		$rs_prno->close();
	}
	
	//retrieve latest bir series
	$rs_birseries = $sp->spSelectBIRSeriesByTxnTypeID($database, 9);
	if ($rs_birseries->num_rows)
	{
		while ($row = $rs_birseries->fetch_object())
		{
			$bir_series	= $row->Series;
			$bir_id = $row->NextID;			
		}		
	}
	else
	{
		$rs_branch = $sp->spSelectBranch($database, -2, "");
		if ($rs_branch->num_rows)
		{
			while ($row = $rs_branch->fetch_object())
			{
				$bir_series	= $row->Code."900000001";
				$bir_id = 1;				
			}
		}
	}
	
	//add mode of payment
	if(isset($_POST['btnAddMOP']))
	{
		$ptypeID = $_POST['hdnPtype'];
		$csmg = "";
		
		//set parameters to session
		$_SESSION["coll_add_custid"] = $_GET["custID"];
		$_SESSION["coll_docno"] = $_POST["txtDocNo"];
 		$_SESSION["coll_txn_date"] = $_POST['txtTxnDate'];
 		$_SESSION["coll_remarks"] = $_POST["txtRemarks"];
		
		if ($ptypeID == 2) //for check
		{	
			if (isset($_SESSION['coll_mop'])) 
			{
				$exist = 0;
				$cnt = sizeof($_SESSION['coll_mop']) + 1;
				$bank = htmlentities(addslashes($_POST['txtBank']));
				$branchName = htmlentities(addslashes($_POST['txtBankBranch']));
				$checkno = htmlentities(addslashes($_POST['txtCheckNo']));
				$checkdate = $_POST['txtCheckDate'];
				$amount = number_format($_POST['txtAmount'], 2, '.', '');
				$tmp_mop = $_SESSION['coll_mop'];
			
				for ($i=0, $n=sizeof($tmp_mop); $i<$n; $i++ )
				{
					if ($tmp_mop[$i]['CheckNo'] == $checkno)
					{
						$exist = 1;
					}
				}
			
				if ($exist == 1)
				{
					$cmsg = "Check No. already exists."; 
					$custid = $_GET["custID"];
					redirect_to("index.php?pageid=50&custID=$custid&cmsg=$cmsg");
				}
				else
				{
					$_SESSION['coll_mop'][] = array('ctr' => $cnt, 'PTypeID'=> $ptypeID, 'Bank' => $bank, 'CheckNo' => $checkno, 'CheckNDate' => $checkdate, 'Amount' => $amount, 'UnappliedAmount' => $amount, 'BranchName'=>$branchName);					
				}
			}
			else
			{
				$_SESSION['coll_mop'] = array();
			
				$cnt = sizeof($_SESSION['coll_mop']) + 1;
				$bank = htmlentities(addslashes($_POST['txtBank']));
				$branchName = htmlentities(addslashes($_POST['txtBankBranch']));
				$checkno = htmlentities(addslashes($_POST['txtCheckNo']));
				$checkdate = $_POST['txtCheckDate'];
				$amount = number_format($_POST['txtAmount'], 2, '.', '');
				$tmp_mop = $_SESSION['coll_mop'];
				$_SESSION['coll_mop'][] = array('ctr' => $cnt, 'PTypeID'=> $ptypeID, 'Bank' => $bank, 'CheckNo' => $checkno, 'CheckNDate' => $checkdate, 'Amount' => $amount, 'UnappliedAmount' => $amount, 'BranchName'=>$branchName);
			}			
		}	
				
		$hdnID = $_POST['hCOA'];
		$hdnCode = $_POST['txtCustomer'];
		$hdnName = $_POST['txtDealerName'];
		$hdnDocNo = $_POST['txtDocNo'];
		$hdnRemarks = $_POST['txtRemarks'];
		$hdnDate = $_POST['txtTxnDate'];
		$hdnIBMCode = $_POST['txtIBMCode'];
	}

	//create payment
	if(isset($_POST['btnSave']))
	{
		try 
		{
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
				$message = "Cannot save transaction, Inventory Count is in progress.";
				redirect_to("../index.php?pageid=50&msg=$message");				
			}
			else
			{
				if ($_POST['counter'] !=0)
				{
					$database->beginTransaction();
					$custId =  $_POST['hCOA'];
					$docNo = $_POST['txtDocNo'];
					$txnDates = strtotime($_POST['txtTxnDate']);
					$txnDate = date("Y-m-d", $txnDates);
					$totalAmount = $_POST['txtMOPTotAmt'];
					$totalAppliedAmount  = 0;
					$totalUnappliedAmount = $_POST['txtMOPTotAmt'];
					$remarks =  $_POST['txtRemarks'];
					$createdBy = $session->emp_id;
					$confirmedBy = $session->emp_id;
					
					$rs_TID =   $sp->spInsertProvisionalReceipt($database, $custId, $docNo, $txnDate, $totalAmount, $remarks, $createdBy,$confirmedBy);
					if (!$rs_TID)
					{
						throw new Exception("An error occurred, please contact your system administrator");
					}
					$tmpTransID = 0;   
						                         
					if($rs_TID->num_rows)
					{
						while($row = $rs_TID->fetch_object())
						{
							$TransID = $row->ID;
							$tmpTransID = $TransID;
						}
					}	
					
					$prid =$tmpTransID;
					$cntRecord = $_POST['txtCountRecord'];	
					for ($i=0; $i<$cntRecord; $i++ )
					{
					//$mopID = $_POST["hdnPaymentType$i"];
						$bankName = $_POST["hdnbankName$i"];
						$branchName =  $_POST["hdnbBranchName$i"];
						$checkNo =  $_POST["hdnCheckNo$i"];
						$checkDate = strtotime($_POST["hdnCheckDate$i"]);
						$checkDate = date("Y-m-d", $checkDate);				
						$depositSlipNo =$_POST["hdnDsvn$i"];
						$depDate = strtotime($_POST["hdnbdteDeposit$i"]);
						$depDate = date("Y-m-d", $depDate);
						$depType = $_POST["hdndepType1$i"];
						$totAmount = $_POST["txtUnapplied$i"];				
		
						$affected_rows = $sp->spInserPRCheck($database, $prid, $bankName, $branchName, $checkNo, $checkDate, $totAmount);
						if (!$affected_rows)
						{
							throw new Exception("An error occurred, please contact your system administrator");
						}
					}
				
					//update bir series
					$rs_update = $sp->spUpdateBIRSeriesByBranchID($database, $bir_id, 9);
					if (!$rs_update)
					{ 
						throw new Exception("An error occurred, please contact your system administrator");
					}
				
					$database->commitTransaction();
					$msg ="Successfully created Provisional Receipt.";
			   		redirect_to("index.php?pageid=51&msg=$msg");
				}
				else
				{
					$database->commitTransaction();  
					$msg ="Please fill up required fields";
				   	redirect_to("index.php?pageid=50&msg=$msg");
				}					
			}
		}
		catch (Exception $e)  
		{
			$database->rollbackTransaction();
			$message = $e->getMessage();
			redirect_to("../index.php?pageid=50&msg=$message");
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
	
	$rs_CreatedBy = $sp->spSelectEmployee($database,$session->emp_id,'');
	if($rs_CreatedBy->num_rows)
	{
		while($row = $rs_CreatedBy->fetch_object())
		{
			$userName = $row->Name;	
			$userID = $row->ID;			
		}
	}
?>