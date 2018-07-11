<?php
/*   
  @modified by John Paul Pineda.
  @date October 18, 2012.
  @email paulpineda19@yahoo.com         
*/

if(!ini_get('display_errors')) ini_set('display_errors', 1);
	
global $database;

$rs_reason=$sp->spSelectReason($database, 8, '');
$employee='';

$ibmname='';

unset($_SESSION['hCustomerIDApplyPayment']);

if(isset($_POST['btnCancel'])) {

  unset($_SESSION['id_add_editOR']);
  redirect_to("index.php?pageid=110");
}

$rs_branch=$sp->spSelectBranch($database, -2, '');
if($rs_branch->num_rows) {

	while($row=$rs_branch->fetch_object()) {
  
		$branch=$row->Name ;
		$branchID=$row->ID;
	}
}

$rsEmployee=$sp->spSelectEmployee($database, $_SESSION['emp_id'], "");
if($rsEmployee->num_rows) {

	while($row=$rsEmployee->fetch_object()) $employee=$row->Name ;			
}

$txnid=$_GET['TxnID'];

// Retrieve Official Receipt Header.
$rs_header=$sp->spViewORHeaderByID($database, $txnid);
if($rs_header->num_rows) {

	while($row=$rs_header->fetch_object()) {
  			
  	$id=$row->ORID;
  	$orno=$row->ORNo;
  	$docno=$row->DocumentNo;
  	$status=$row->TxnStatus;
  	$custcode=$row->CustCode;
  	$custname=$row->CustName;
  	$custid=$row->CustID;
  	$tmpTxnDate=strtotime($row->ORDate);
  	$txndate=date("m/d/Y", $tmpTxnDate);		
  	$remarks=$row->Remarks;			
  	$totalamt=number_format($row->TotalAmount, 2, '.', '');
  	$totalappamt=number_format($row->TotalAppliedAmt, 2, '.', '');
  	$totalunappamt=number_format($row->TotalUnappliedAmt, 2, '.', '');
  	$txnstatusId=$row->TxnStatusID;
  	$orType=$row->ortypeNames;
  	$ibmname=$row->IBMName;
  	//$nonReason = $row->ReasonName;
	}
	$rs_header->close();
}

if(!isset($_SESSION['hCustomerIDApplyPayment'])) $_SESSION['hCustomerIDApplyPayment']=$custid;			

//retrieve or details
$rs_details=$sp->spSelectORDetailsByID($database, $txnid); 
$rs_silist=$sp->spSelectCustomerIGSSI($database, $custid);

$totapplied=0;

if(isset($_POST["btnSave"])) {

	if(isset($_POST["txtTotalUnapplieds"])) $txtTotalUnapplied=$_POST["txtTotalUnapplieds"];
	else $txtTotalUnapplied=0;
  	
	if($_POST["txtTotalAmount"]<=$txtTotalUnapplied) {
  
		$orid=$_GET["TxnID"];
		$totUnapplied=$_POST["txtTotalUnapplieds"];
		$totApplied=$_POST["txtTotalAmount"];
		$tmpUnApplied=$totUnapplied - $totApplied;			
		$tmpOutstandingAmt=0;
    
		//$hdnCounts =  $_POST["hdnCount"];
    
		$totAppliedAmt=0;		
		$cnt=$_POST['hcntdynamic'];
    
		for($i=1;$i<=$cnt;$i++) {
    				
			$amount=$_POST["txtAppliedAmount{$i}"];
			$sicpids=$_POST["hdnIID{$i}"];
			$refTypes=$_POST["hdnRID{$i}"];
			$OutstandingAmount=$_POST["txtOutStandingBalance{$i}"];
			$creditTerm=$_POST["hdnCreditTerm{$i}"];
			$outamount=(double)str_replace(',', '', $OutstandingAmount); 
			$tmpOutstandingAmt=$outamount-$amount;			      
      
			if($amount>0) {
      
				$createdBy=$session->emp_id;				
				$sp->spInsertOfficialReceiptDetails($database, $orid, $refTypes, $sicpids, $outamount, $amount, $createdBy);
				$totAppliedAmt+=$amount;
				$paidcft=0;
        $paidncft=0;
        $paidcpi=0;        								 				
        
				if($refTypes==1) {
        
					$rs_getInvoices=$sp->spSelectSIDetailsApplyPayment($database, $sicpids);
				  if($rs_getInvoices->num_rows) {
          
			   		while($row=$rs_getInvoices->fetch_object()) {
            
              $netamount=$row->NetAmount;
              $totalcft=$row->TotalCFT;
              $totalncft=$row->TotalNCFT;
              $totalcpi=$row->TotalCPI;
              $paidcft=0;
              $paidncft=0;
              $paidcpi=0;
				   		
  						$outstandingamt=0;
              
  						if($creditTerm==1) {
              
		         		$percentageCFT = round($totalcft/$netamount,2);
		         		$paidcft = round($amount * $percentageCFT,2);
		         		if($totalcpi==0) {
                
		         			$paidncft=$amount-$paidcft;		
		         			$paidcpi=0;						         			
		         		} else {
                
		         			$percentageNCFT=round($totalncft/$netamount,2);
		         			$paidncft=round($amount * $percentageNCFT,2);
		         			$paidcpi=$amount-($paidcft + $paidncft);
		         		}								         	  			         		
  			      }
              
						  $affected_rows=$sp->spUpdateSalesInvoiceOutstandingBalance($database, $sicpids, $amount, 1, $creditTerm,$paidcft,$paidncft,$paidcpi);
			        if(!$affected_rows) {
                 
                throw new Exception("An error occurred, please contact your system administrator");
						  }																			
			   		}
				  }				
				}
        
				if($refTypes==3) $affected_rows=$sp->spUpdateCustomerPenaltyApplyPayment($database, $sicpids, $amount);											 				
			}
		}

		$sp->spUpdateUnappliedAmountOfficialReceipt($database, $orid, $totAppliedAmt);
		
		unset($_SESSION['id_add_editOR']);
		$msg='Payment successfully applied.';
		
    redirect_to('index.php?pageid=97&action='.base64_encode('confirm_and_print').'&or_id='.$txnid.'&prntcnt=0');			
	} else {
  
		$msg='Total Applied must not be greater than Total Unapplied.';
		//redirect_to("../tpi/index.php?pageid=110&msg=$message");
	}			
}


if(isset($_POST['btnAdds']))
{
    $r = 0;
	$arrQty = sizeof($_POST['countRows1']);
	$arrQty = $_POST['countRows1'] ;
	$isChecked = '';
	$amount = 0;
	
	for($i = 0; $i < $arrQty; $i++)
	{
		if ($_POST["txtchks$i"] == 1)
		{				
			$newarrayid[$r] 			= $_POST['hdncpsiNos'][$i];
			$newarrayrefType[$r]		= $_POST['hdnreftypes'][$i];
			$newarrayoutstanding[$r]	= $_POST['hdnOutstandingBals'][$i]; 
			$newarrayamount[$r]			= $_POST['txtAmountApplieds'.$i];
			$newarrayrefId[$r] 			= $_POST['hdnids'][$i];
			$newarrayigsCode[$r] 		= $_POST['hdnigsCodes'][$i];
			$newarraytxnDate[$r] 		= $_POST['hdnTxnDates'][$i];
			$newarraytxnAmount[$r] 		= $_POST['hdnTxnAmounts'][$i];
			$newarraycreditTerm[$r]		= $_POST['hdnIsWithinCreditTerm'][$i];
			$r++;
		}		  
	}
	
	if($r>0)
	{		
		$session->set_editOR($newarrayid, $newarrayrefType, $newarrayoutstanding, $newarrayamount, $newarrayrefId, $newarrayigsCode, $newarraytxnDate, $newarraytxnAmount, $newarraycreditTerm);
		
		$limit = 0;
		if(isset($session->id_add_editOR))
		{
			$limit = sizeof($session->id_add_editOR);
		}
		$editOR_id = $session->editOR_id;			
		$editOR_refType = $session->editOR_refType;		
	
		if (!($limit))
		{					
			for($i = 0; $i < sizeof($editOR_id); $i++)
			{	
				
				$id                 = $session->editOR_id[$i];	
				$refType            = $session->editOR_refType[$i];	
				$outstandingBalance = $session->editOR_outstandingBalance[$i];
				$amount             = $session->editOR_amount[$i];				
				$refid              = $session->editOR_refId[$i];	
				$igsCode            = $session->editOR_igsCode[$i];
				$txnDate            = $session->editOR_txnDate[$i];
				$txnAmount          = $session->editOR_txnAmount[$i];
				$creditTerm         = $session->editOR_creditTerm[$i];
				
				$session->set_editOR_ids($id, $refType, $outstandingBalance, $amount, $refid, $igsCode, $txnDate, $txnAmount, $creditTerm);								
			}			
		}
		else
		{			
			$formlimit = sizeof($session->editOR_id);
			
			for($i = 0; $i < $formlimit; $i++)
			{
				$action = "New";
				$f_id = $session->editOR_id[$i];
				$f_refType   = $session->editOR_refType[$i];
			
				for ($y = 0; $y < $limit; $y++)
				{
					$yId = $session->id_add_editOR[$y]['ID'];
				    $yRefTypes = $session->id_add_editOR[$y]['RefType'];											
			
					if (($yId == $f_id)&& ($yRefTypes == $f_refType))
					{
						$action = "Update";
						break;
					}
				}

				if($action == "Update")
				{						
					$addedAmount = $session->id_add_editOR[$y]['Amount'] + $session->editOR_amount[$i];					
				  	$session->set_editOR_ids_amount($y, $addedAmount);				  	
				}
				else if ($action == "New")
				{					
					$id = $session->editOR_id[$i];						
					$refType = $session->editOR_refType[$i];	
					$outstandingBalance = $session->editOR_outstandingBalance[$i];	
					$amount = $session->editOR_amount[$i];		
					$refid = $session->editOR_refId[$i];	
					$igsCode = $session->editOR_igsCode[$i];
					$txnDate = $session->editOR_txnDate[$i];
					$txnAmount = $session->editOR_txnAmount[$i];
					$creditTerm = $session->editOR_creditTerm[$i];
					
					$session->set_editOR_ids($id, $refType, $outstandingBalance, $amount, $refid, $igsCode, $txnDate, $txnAmount, $creditTerm);											
				}				
			}	
			$txnid = $_GET['TxnID'];		
		}
		redirect_to("index.php?pageid=110.1&TxnID=$txnid");
	}
	else
	{
		$msg = "Please select sales invoice(s) or penalty(ies) to be added.";		
	}
}

if(isset($_POST['btnRemove'])) {
		
	if(isset($session->id_add_editOR))
	{
		$limit = sizeof($session->id_add_editOR);			
		$tmp_prod = $session->id_add_editOR;
	}
	
	if(isset($_POST['chkIID']))
	{
		if($_POST['chkIID']!='')
		{					
			foreach($_POST['chkIID'] as $key => $value)
			{			
				$sid =	"hdnIID_$value";
				$reftypeId = "hdnRID_$value";
				for ($i = 0; $i < $limit; $i++)
				{					
					if (isset($tmp_prod[$i]['ID']))
					{		
						if (($_POST[$sid] == $tmp_prod[$i]['ID']) && ($_POST[$reftypeId] == $tmp_prod[$i]['RefType'])) 
						{									
							unset($tmp_prod[$i]);
							break;
						}
					}
				}
			}
				
			$session->unset_editOR_ids();
			for ($i = 0; $i < $limit; $i++)
			{
				if (isset($tmp_prod[$i]['ID']))
				{	 
					$session->set_editOR_ids(
							$tmp_prod[$i]['ID'], 
							$tmp_prod[$i]['RefType'], 
							$tmp_prod[$i]['OutStandingBalance'],
						   	$tmp_prod[$i]['Amount'], 
						   	$tmp_prod[$i]['RefId'], 
						   	$tmp_prod[$i]['IgsCode'], 
						   	$tmp_prod[$i]['TxnDate'], 					    
						   	$tmp_prod[$i]['TxnAmount'],
						   	$tmp_prod[$i]['WithinCreditTerm']
						   	);						
				}					
			}						
			$msg = "Record successfully removed.";								
		}
	}
}

