<?php
/*   
  @modified by John Paul Pineda
  @date June 1, 2013
  @email paulpineda19@yahoo.com         
*/

ini_set('error_reporting', E_ERROR);

global $database;

if(!ini_get('display_errors')) ini_set('display_errors', 1);

if(!isset($_GET['ctypeid'])) $_GET['ctypeid']=0;   	

$rem='';
$customerName='';

// Get the Next Official Receipt ID
$rs_orno=$sp->spGetMaxID($database, 2, '`officialreceipt`');
if($rs_orno->num_rows) {

  while($row=$rs_orno->fetch_object()) {
  
    $orno=$row->txnno;
    
    if($orno=='') $orno='OR00000001';    
  }
  $rs_orno->close();
}

// Set the Customer ID.
if(!isset($_SESSION['CustomerID'])) $_SESSION['CustomerID']=0;
else {

  if($_SESSION['CustomerID']==0) {
  
    if(isset($_POST['hCustID'])) $_SESSION['CustomerID']=$_POST['hCustID'];    		
  }  		
}

// Get the list of the IGS Dealers.
//$rs_dealers=$sp->spSelectIGSDealersByIBMID($database, $_SESSION['CustomerID']);

// Get the list of Bank.
$rs_bank=$sp->spSelectBankByBranchID($database);

// Get the Username.
$rs_username=$sp->spSelectUserEmployee($database, $_SESSION['emp_id']);
if($rs_username->num_rows) {

  while($row=$rs_username->fetch_object()) { $username=$row->EmployeeName; }			
  
  $rs_username->close();		
}

// Get the Branch.
$rs_branchname=$sp->spGetBranchParameter($database);
if($rs_branchname->num_rows) {

  while($row=$rs_branchname->fetch_object()) { $branchname=$row->name; }
  
  $rs_branchname->close();		
}

// Retrieve the latest BIR series.
$rs_birseries=$sp->spSelectBIRSeriesByTxnTypeID($database, 8);
if($rs_birseries->num_rows) {

  while($row=$rs_birseries->fetch_object()) {
  
    $bir_series=$row->Series;
    $bir_id=$row->NextID;			
  }		
} else {

  $rs_branch=$sp->spSelectBranch($database, -2, '');
  if($rs_branch->num_rows) {
  
    while($row=$rs_branch->fetch_object()) {
    
      $bir_series=$row->Code.'800000001';
      $bir_id=1;				
    }
  }
}

// Reset session variables.	
if($_SESSION['CustomerID']!=$_SESSION['coll_add_custid']) {

  unset($_SESSION['coll_mop']);
  unset($_SESSION['tmp_coll_mop']);
  unset($_SESSION['coll_addsi']);			
}

// Cancel the transaction.
if(isset($_POST['btnCancel'])) {

  $session->unset_trans_prod();
  
  redirect_to('index.php?pageid=96');			
}

//add mode of payment
if(isset($_POST['btnAddMOP'])) {

  // Set the parameters to session.
  $ptypeID=$_POST['lstType'];
  $_SESSION['coll_add_custid']=$_SESSION['CustomerID'];
  $_SESSION['coll_docno']=$_POST['txtDocNo'];
  $_SESSION['coll_txn_date']=$_POST['hdnTxnDate'];
  $_SESSION['coll_remarks']=$_POST['txtRemarks']; 
  
  // For Cash.
  if($ptypeID==1) {
  
    if(isset($_SESSION['coll_mop'])) {
    
      // Remove the Cash Data row.
      $existing=0;
      $tmp_collmop=array();
      $tmp_collmop2=array ();
      $tmp_holder=0;
      $tmp_collmop=$_SESSION['coll_mop'];
      $totcnt=sizeof($_SESSION['coll_mop']);
      
      for($i=0, $n=sizeof($tmp_collmop);$i<$n;$i++) {
      
        $index=$i+1;
              
        if($tmp_collmop[$i]['PTypeID']==1) {
          
          // For Cash.
          $existing=1;
          $tmp_holder=$i;	
          $iamount=$tmp_collmop[$i]['Amount'];
          $tmpamount=$iamount+$_POST['txtAmountDue'];
          $amount=number_format($tmpamount, 2, '.', '');
          $tmp_collmop2[]=array('ctr'=>$index, 
          'PTypeID'=>$tmp_collmop[$i]['PTypeID'], 
          'Bank'=>htmlentities(addslashes($tmp_collmop[$i]['Bank'])), 
          'CheckNo'=>$tmp_collmop[$i]['CheckNo'], 
          'CheckNDate'=>$tmp_collmop[$i]['CheckNDate'], 
          'Amount'=>$amount, 
          'UnappliedAmount'=>$amount,
          'BranchName'=>'',
          'DepositValNo'=>'',
          'DateOfDeposit'=>'',
          'DepositType'=>'',
          'Account'=>'',
          'AccountID'=>0,
          'CampaignID'=>0,
          'CustCommID'=>0
          );
        } else if($tmp_collmop[$i]['PTypeID']==2)  {
          
          // For Check.
          $tmp_collmop2[]=array('ctr'=>$index, 
          'PTypeID'=>$tmp_collmop[$i]['PTypeID'], 
          'Bank'=>htmlentities(addslashes($tmp_collmop[$i]['Bank'])), 
          'CheckNo'=>$tmp_collmop[$i]['CheckNo'], 
          'CheckNDate'=>$tmp_collmop[$i]['CheckNDate'], 
          'Amount'=>$tmp_collmop[$i]['Amount'], 
          'UnappliedAmount'=>$tmp_collmop[$i]['Amount'],
          'BranchName'=>'',
          'DepositValNo'=>'',
          'DateOfDeposit'=>'',
          'DepositType'=>'',
          'Account'=>'',
          'AccountID'=>0,
          'CampaignID'=>0,
          'CustCommID'=>0
          );
        } else if($tmp_collmop[$i]['PTypeID']==3) {
        
          // For Deposit.				
          $tmp_collmop2[]=array('ctr'=>$index, 
          'PTypeID'=>$tmp_collmop[$i]['PTypeID'], 
          'Bank'=>htmlentities(addslashes($tmp_collmop[$i]['Bank'])), 
          'CheckNo'=>$tmp_collmop[$i]['CheckNo'], 
          'CheckNDate'=>$tmp_collmop[$i]['CheckNDate'], 
          'Amount'=>$tmp_collmop[$i]['Amount'], 
          'UnappliedAmount'=>$tmp_collmop[$i]['Amount'],
          'BranchName'=>htmlentities(addslashes($tmp_collmop[$i]['BranchName'])), 
          'DepositValNo'=>$tmp_collmop[$i]['DepositValNo'],
          'DateOfDeposit'=>$tmp_collmop[$i]['DateOfDeposit'],
          'DepositType'=>$tmp_collmop[$i]['DepositType'],
          'Account'=>'',
          'AccountID'=>0,
          'CampaignID'=>0,
          'CustCommID'=>0
          );
        }	
        else {
        
          // For Commissions.				
          $tmp_collmop2[]=array('ctr'=>$index, 
          'PTypeID'=>$tmp_collmop[$i]['PTypeID'], 
          'Bank'=>'', 
          'CheckNo'=>'', 
          'CheckNDate'=>'', 
          'Amount'=>$tmp_collmop[$i]['Amount'], 
          'UnappliedAmount'=>$tmp_collmop[$i]['Amount'],
          'BranchName'=>'', 
          'DepositValNo'=>'',
          'DateOfDeposit'=>'',
          'DepositType'=> '',
          'Account'=>'',
          'AccountID'=>0,
          'CampaignID'=>0,
          'CustCommID'=>0
          );
        }					
      }
      
      if($existing==0) {
      
        $amount=number_format($_POST['txtAmountDue'], 2, '.', '');
        $tmp_collmop2[]=array('ctr'=>(sizeof($tmp_collmop2)-1), 
        'PTypeID'=>$ptypeID,
        'Bank'=>'', 
        'CheckNo'=>'', 
        'CheckNDate'=>'', 
        'Amount'=>$amount, 
        'UnappliedAmount'=>$amount,
        'BranchName'=>'',
        'DepositValNo'=>'',
        'DateOfDeposit'=>'',
        'DepositType'=>'',
        'Account'=>'',
        'AccountID'=>0,
        'CampaignID'=>0,
        'CustCommID'=>0
        );					
      }
      
      unset($_SESSION['coll_mop']);
      $_SESSION['coll_mop']=$tmp_collmop2;	
    } else {
    
      $_SESSION['coll_mop']=array();			
      $cnt=sizeof($_SESSION['coll_mop'])+1;
      $bank='';
      $checkno='';
      $checkdate='';
      $branchName='';
      $depositValNo='';
      $dteDeposit='';
      $depositType='';
      $account='';
      $amount=number_format($_POST['txtAmountDue'], 2, '.', '');
      $_SESSION['coll_mop'][]=array('ctr'=>$cnt, 
      'PTypeID'=>$ptypeID, 
      'Bank'=>$bank, 
      'CheckNo'=>$checkno, 
      'CheckNDate'=>$checkdate, 
      'Amount'=>$amount, 
      'UnappliedAmount'=>$amount,
      'BranchName'=>$branchName,
      'DepositValNo'=>$depositValNo,
      'DateOfDeposit'=>$dteDeposit,
      'DepositType'=>$depositType,
      'Account'=>$account,
      'AccountID'=>0,
      'CampaignID'=>0,
      'CustCommID'=>0
      );
    }
  } else if($ptypeID==2) {  
  
    // For Check.	
    if(isset($_SESSION['coll_mop'])) {
    
      $exist=0;
      $cnt=sizeof($_SESSION['coll_mop'])+1;
      $bank=htmlentities(addslashes($_POST['txtBank']));
      $branchName=htmlentities(addslashes($_POST['txtBankBranch']));
      $checkno=htmlentities(addslashes($_POST['txtCheckNo']));
      $checkdate=$_POST['txtCheckDate'];
      $amount=number_format($_POST['txtAmount'], 2, '.', '');
      $tmp_mop=$_SESSION['coll_mop'];
      
      for($i=0, $n=sizeof($tmp_mop);$i<$n;$i++) {
      
        if($tmp_mop[$i]['CheckNo']==$checkno) $exist=1;        
      }
      
      if($exist==1) {
      
        $cmsg='Check No. already exists.'; 
        $custid=$_GET["custID"];
        redirect_to('index.php?pageid=47&custID='.$custid.'&cmsg='.$cmsg);
      } else {
      
        $depositValNo='';
        $dteDeposit='';
        $depositType='';
        $_SESSION['coll_mop'][]=array('ctr'=>$cnt, 
        'PTypeID'=>$ptypeID, 
        'Bank'=>$bank, 
        'CheckNo'=>$checkno, 
        'CheckNDate'=>$checkdate, 
        'Amount'=>$amount, 
        'UnappliedAmount'=>$amount,
        'BranchName'=>$branchName,
        'DepositValNo'=>$depositValNo,
        'DateOfDeposit'=>$dteDeposit,
        'DepositType'=>$depositType,
        'Account'=>'',
        'AccountID'=>0,
        'CampaignID'=>0,
        'CustCommID'=>0
        );
      }
    } else {
    
      $_SESSION['coll_mop']=array();
      $depositValNo='';
      $dteDeposit='';
      $depositType='';
      $cnt=sizeof($_SESSION['coll_mop'])+1;
      $bank=htmlentities(addslashes($_POST['txtBank']));
      $branchName=htmlentities(addslashes($_POST['txtBankBranch']));
      $checkno=htmlentities(addslashes($_POST['txtCheckNo']));
      $checkdate=$_POST['txtCheckDate'];
      $amount=number_format($_POST['txtAmount'], 2, '.', '');
      $tmp_mop=$_SESSION['coll_mop'];
      $_SESSION['coll_mop'][]=array('ctr'=>$cnt, 
      'PTypeID'=>$ptypeID, 
      'Bank'=>$bank, 
      'CheckNo'=>$checkno, 
      'CheckNDate'=>$checkdate, 
      'Amount'=>$amount, 
      'UnappliedAmount'=>$amount,
      'BranchName'=>$branchName,
      'DepositValNo'=>$depositValNo,
      'DateOfDeposit'=>$dteDeposit,
      'DepositType'=>$depositType,
      'Account'=>'',
      'AccountID'=>0,
      'CampaignID'=>0,
      'CustCommID'=>0
      );
    }
  }	else if($ptypeID==3) {
    
    // For Deposit.
    if(isset($_SESSION['coll_mop'])) {
    
      $exist=0;
      $cnt=sizeof($_SESSION['coll_mop'])+1;
      $depositValNo=htmlentities(addslashes($_POST['txtDepSlipValNo']));
      $dteDeposit=$_POST['txtDepDate'];
      $depositType=$_POST['rdDepositType'];			
      $amount=number_format($_POST['txtAmount'], 2, '.', '');
      $tmp_mop=$_SESSION['coll_mop'];
      
      $bank='';
      $checkno='';
      $checkdate='';
      $branchName='';
      $_SESSION['coll_mop'][]=array('ctr'=>$cnt, 
      'PTypeID'=>$ptypeID, 
      'Bank'=>$bank, 
      'CheckNo'=>$checkno, 
      'CheckNDate'=>$checkdate, 
      'Amount'=>$amount, 
      'UnappliedAmount'=>$amount,
      'BranchName'=>$branchName,
      'DepositValNo'=>$depositValNo,
      'DateOfDeposit'=>$dteDeposit,
      'DepositType'=>$depositType,
      'Account'=>'',
      'AccountID'=>0,
      'CampaignID'=>0,
      'CustCommID'=>0
      );	
    } else {
    
      $_SESSION['coll_mop']=array();
      $cnt=sizeof($_SESSION['coll_mop'])+1;
      $depositValNo=htmlentities(addslashes($_POST['txtDepSlipValNo']));
      $dteDeposit=$_POST['txtDepDate'];
      $depositType=$_POST['rdDepositType'];			
      $amount=number_format($_POST['txtAmount'], 2, '.', '');
      $tmp_mop=$_SESSION['coll_mop'];
      
      $bank='';
      $checkno='';
      $checkdate='';
      $branchName='';
      $_SESSION['coll_mop'][]=array('ctr'=>$cnt, 
      'PTypeID'=>$ptypeID, 
      'Bank'=>$bank, 
      'CheckNo'=>$checkno, 
      'CheckNDate'=>$checkdate, 
      'Amount'=>$amount, 
      'UnappliedAmount'=>$amount,
      'BranchName'=>$branchName,
      'DepositValNo'=>$depositValNo,
      'DateOfDeposit'=>$dteDeposit,
      'DepositType'=>$depositType,
      'Account'=>'',
      'AccountID'=>0,
      'CampaignID'=>0,
      'CustCommID'=>0
      );
    }
  } else {
    
    // For Commission.
    if (isset($_SESSION['coll_mop'])) 
    {
    $cnt = sizeof($_SESSION['coll_mop']) + 1;				
    $account = $_POST['cboCommType'];
    $campaign = $_POST['cboCampaign'];
    $custcommid = $_POST['hdnCustCommID'];
    $amount = number_format($_POST['txtAmount'], 2, '.', '');
    
    $existing = 0;
    $tmp_collmop =  array();
    $tmp_collmop2= array ();
    $tmp_holder = 0;
    $tmp_collmop = $_SESSION['coll_mop'];
    $totcnt = sizeof($_SESSION['coll_mop']);
    
    for ($i=0, $n=sizeof($tmp_collmop); $i<$n; $i++ )
    {
    $index = $i + 1;
    if ($tmp_collmop[$i]['PTypeID'] == 1) //for cash
    {
    $tmp_collmop2[] = array('ctr' => $index, 
    'PTypeID'=> $tmp_collmop[$i]['PTypeID'], 
    'Bank' => htmlentities(addslashes($tmp_collmop[$i]['Bank'])), 
    'CheckNo' => $tmp_collmop[$i]['CheckNo'], 
    'CheckNDate' => $tmp_collmop[$i]['CheckNDate'], 
    'Amount' => $tmp_collmop[$i]['Amount'], 
    'UnappliedAmount' => $tmp_collmop[$i]['Amount'],
    'BranchName'=>'',
    'DepositValNo'=>'',
    'DateOfDeposit'=> '',
    'DepositType'=> '',
    'Account'=> '',
    'AccountID'=> 0,
    'CampaignID'=> 0,
    'CustCommID'=>0
    );						
    }
    else if ($tmp_collmop[$i]['PTypeID'] == 2) //for check
    {
    $tmp_collmop2[] = array('ctr' => $index, 
    'PTypeID'=> $tmp_collmop[$i]['PTypeID'], 
    'Bank' => htmlentities(addslashes($tmp_collmop[$i]['Bank'])), 
    'CheckNo' => $tmp_collmop[$i]['CheckNo'], 
    'CheckNDate' => $tmp_collmop[$i]['CheckNDate'], 
    'Amount' => $tmp_collmop[$i]['Amount'], 
    'UnappliedAmount' => $tmp_collmop[$i]['Amount'],
    'BranchName'=>'',
    'DepositValNo'=>'',
    'DateOfDeposit'=> '',
    'DepositType'=> '',
    'Account'=> '',
    'AccountID'=> 0,
    'CampaignID'=> 0,
    'CustCommID'=>0
    );
    }
    else if ($tmp_collmop[$i]['PTypeID'] == 3) //for deposit
    {				
    $tmp_collmop2[] = array('ctr' => $index, 
    'PTypeID'=> $tmp_collmop[$i]['PTypeID'], 
    'Bank' => htmlentities(addslashes($tmp_collmop[$i]['Bank'])), 
    'CheckNo' => $tmp_collmop[$i]['CheckNo'], 
    'CheckNDate' => $tmp_collmop[$i]['CheckNDate'], 
    'Amount' => $tmp_collmop[$i]['Amount'], 
    'UnappliedAmount' => $tmp_collmop[$i]['Amount'],
    'BranchName'=>htmlentities(addslashes($tmp_collmop[$i]['BranchName'])), 
    'DepositValNo'=>$tmp_collmop[$i]['DepositValNo'],
    'DateOfDeposit'=> $tmp_collmop[$i]['DateOfDeposit'],
    'DepositType'=> $tmp_collmop[$i]['DepositType'],
    'Account'=> '',
    'AccountID'=> 0,
    'CampaignID'=> 0,
    'CustCommID'=>0
    );
    }
    else //for commissions
    {
    if ($tmp_collmop[$i]['AccountID'] == $account && $tmp_collmop[$i]['CampaignID'] == $campaign)
    {
    $existing = 1;
    $iamount = $tmp_collmop[$i]['Amount'];
    $tmpamount = $iamount + $amount;
    $totamount = number_format($tmpamount, 2, '.', '');
    $tmp_collmop2[] = array('ctr' => $index, 
    'PTypeID'=> $tmp_collmop[$i]['PTypeID'], 
    'Bank' => '', 
    'CheckNo' => '', 
    'CheckNDate' => '', 
    'Amount' => $totamount, 
    'UnappliedAmount' => $totamount,
    'BranchName'=> '', 
    'DepositValNo'=> '',
    'DateOfDeposit'=> '',
    'DepositType'=> '',
    'Account'=> $tmp_collmop[$i]['Account'],
    'AccountID'=> $tmp_collmop[$i]['AccountID'],
    'CampaignID'=> $tmp_collmop[$i]['CampaignID'],
    'CustCommID'=> $tmp_collmop[$i]['CustCommID']
    );							
    }
    else
    {
    $tmp_collmop2[] = array('ctr' => $index, 
    'PTypeID'=> $tmp_collmop[$i]['PTypeID'], 
    'Bank' => '', 
    'CheckNo' => '', 
    'CheckNDate' => '', 
    'Amount' => $tmp_collmop[$i]['Amount'], 
    'UnappliedAmount' => $tmp_collmop[$i]['Amount'],
    'BranchName'=> '', 
    'DepositValNo'=> '',
    'DateOfDeposit'=> '',
    'DepositType'=> '',
    'Account'=> $tmp_collmop[$i]['Account'],
    'AccountID'=> $tmp_collmop[$i]['AccountID'],
    'CampaignID'=> $tmp_collmop[$i]['CampaignID'],
    'CustCommID'=> $tmp_collmop[$i]['CustCommID']
    );								
    }						
    }					
    }
    
    if ($existing == 0)
    {
    //get commission description
    $rs_commission = $sp->spSelectCommissionTypeByID($database, $account);
    if ($rs_commission->num_rows)
    {
    while ($row_comm = $rs_commission->fetch_object())
    {
    $commtype = $row_comm->Name; 						
    }
    $rs_commission->close();					
    }
    
    //get campaign description
    $rs_campaign = $sp->spSelectCampaignByID($database, $campaign);
    if ($rs_campaign->num_rows)
    {
    while ($row_campaign = $rs_campaign->fetch_object())
    {
    $camp = $row_campaign->Name; 						
    }
    $rs_campaign->close();					
    }
    $comm_camp = $commtype." - ".$camp;
    $bank = '';
    $checkno = '';
    $checkdate = '';
    $branchName = '';
    $depositValNo = '';
    $dteDeposit = '';
    $depositType = '';
    $tmp_collmop2[] = array('ctr' => (sizeof($tmp_collmop2)-1), 
    'PTypeID'=> $ptypeID, 
    'Bank' => $bank, 
    'CheckNo' => $checkno, 
    'CheckNDate' => $checkdate, 
    'Amount' => $amount, 
    'UnappliedAmount' => $amount,
    'BranchName'=>$branchName,
    'DepositValNo'=>$depositValNo,
    'DateOfDeposit'=>$dteDeposit,
    'DepositType'=>$depositType,
    'Account'=>$comm_camp,
    'AccountID'=>$account,
    'CampaignID'=>$campaign,
    'CustCommID'=>$custcommid
    );		
    }
    unset($_SESSION['coll_mop']);
    $_SESSION['coll_mop'] = $tmp_collmop2;	
    }
    else
    {
    $_SESSION['coll_mop'] = array();
    $cnt = sizeof($_SESSION['coll_mop']) + 1;
    $account = $_POST['cboCommType'];
    $campaign = $_POST['cboCampaign'];					
    $custcommid = $_POST['hdnCustCommID'];
    $amount = number_format($_POST['txtAmount'], 2, '.', '');
    $tmp_mop = $_SESSION['coll_mop'];
    
    //get commission description
    $rs_commission = $sp->spSelectCommissionTypeByID($database, $account);
    if ($rs_commission->num_rows)
    {
    while ($row_comm = $rs_commission->fetch_object())
    {
    $commtype = $row_comm->Name; 						
    }
    $rs_commission->close();					
    }
    
    //get campaign description
    $rs_campaign = $sp->spSelectCampaignByID($database, $campaign);
    if ($rs_campaign->num_rows)
    {
    while ($row_campaign = $rs_campaign->fetch_object())
    {
    $camp = $row_campaign->Name; 						
    }
    $rs_campaign->close();					
    }
    $comm_camp = $commtype." - ".$camp;
    $bank = '';
    $checkno = '';
    $checkdate = '';
    $branchName='';
    $depositValNo = '';
    $dteDeposit = '';
    $depositType = '';
    $_SESSION['coll_mop'][] = array('ctr' => $cnt, 
    'PTypeID'=> $ptypeID, 
    'Bank' => $bank, 
    'CheckNo' => $checkno, 
    'CheckNDate' => $checkdate, 
    'Amount' => $amount, 
    'UnappliedAmount' => $amount,
    'BranchName'=>$branchName,
    'DepositValNo'=>$depositValNo,
    'DateOfDeposit'=>$dteDeposit,
    'DepositType'=>$depositType,
    'Account'=>$comm_camp,
    'AccountID'=> $account,
    'CampaignID'=> $campaign,
    'CustCommID'=>$custcommid
    );
    }			
  }
  
  $rs_custtypeid=$sp->spSelectCustomer($database, $_SESSION['CustomerID'], '');
  if($rs_custtypeid->num_rows) {
  
    while($row=$rs_custtypeid->fetch_object()) {
    
      $ctypeid=$row->CustomerTypeID; 						
    } $rs_custtypeid->close();					
  }
  
  $_GET['ctypeid']=$ctypeid;
}

// Create Payment.
if(isset($_POST['btnSave'])) {
  
  $database->beginTransaction();
  
  // Set Parameters.
  $custId=$_SESSION['CustomerID'];
  $docNo=$_POST['txtDocNo'];
  $txnDates=strtotime($_POST['hdnTxnDate']);
  $txnDate=date('Y-m-d', $txnDates);
  $totalAmount=$_POST['txtMOPTotAmt'];
  $totalAppliedAmount=0;
  $totalUnappliedAmount=$_POST['txtMOPTotAmt'];
  $remarks=$_POST['txtRemarks'];
  $createdBy=$session->emp_id;
  $confirmedBy=$session->emp_id;
  $ortype=$_POST['rdoORType'];
  $dmcmDocno=$_POST['txtMemoNo'];
  
  if($ortype==1) {
  
    $setid=$_POST['cboSettlementType'];
    $reaid='null';
  } else {
  
    $setid='null';
    $reaid=$_POST['cboReason'];
  }
  
  $total_mop_amt=0;
  $bankid=$_POST['cboBankHeader'];
  
  // Check the Status of the Inventory.
  $rs_freeze=$sp->spCheckInventoryStatus($database);
  if($rs_freeze->num_rows) {
  
    while($row=$rs_freeze->fetch_object()) $statusid_inv=$row->StatusID;			    		
  } else $statusid_inv=20;
    
  if($statusid_inv==21) {
  
    $database->commitTransaction();  
    $message='Cannot save transaction, Inventory Count is in progress.';
    redirect_to('../index.php?pageid=97&msg='.$message.'&tid='.$txnid);				
  } else {
  
    // Create the Official Receipt Header.
    $rs_TID=$sp->spInsertOfficialReceipt($database, $custId, $docNo, $txnDate, $totalAmount, $totalAppliedAmount, $totalUnappliedAmount, $remarks, $createdBy, $confirmedBy, $ortype, $reaid, $bankid, 1, $dmcmDocno);
    if(!$rs_TID) {
    
      throw new Exception('An error occurred, please contact your system administrator');
    }
    
    $tmpTransID=0;                    
    if($rs_TID->num_rows) {
    
      while($row=$rs_TID->fetch_object()) {
      
        $TransID=$row->ID;
        $tmpTransID=$TransID;
      }
    }
    
    $orid=$tmpTransID;
    $cntRecord=$_POST['txtCountRecord'];
    
    // Create Official Receipt Details.			
    for($i=0;$i<$cntRecord;$i++) {
    
      $bankName=$_POST["hdnbankName$i"];
      $branchName=$_POST["hdnbBranchName$i"];
      $checkNo=$_POST["hdnCheckNo$i"];
      $checkDate=strtotime($_POST["hdnCheckDate$i"]);
      $checkDate=date("Y-m-d", $checkDate);				
      $depositSlipNo=$_POST["hdnDsvn$i"];
      $depDate=strtotime($_POST["hdnbdteDeposit$i"]);
      $depDate=date("Y-m-d", $depDate);
      $depType=$_POST["hdndepType1$i"];
      $depTypeid=$_POST["hdndepTypeId$i"];
      $commtypeid=$_POST["hdncustcomm$i"];
      $totAmount=$_POST["txtUnapplied$i"];
      $unAppAmount=$_POST["txtUnapplied$i"];
      $total_mop_amt+=$_POST["txtUnapplied$i"];
      
      if($_POST["hdnPaymentType$i"]==1) {
      
        $affected_rows=$sp->spSelectORCash($database, $orid, $totAmount, $unAppAmount);
        if(!$affected_rows) {
        
          throw new Exception('An error occurred, please contact your system administrator');
        }
      } else if($_POST["hdnPaymentType$i"]==2) {
      
        $affected_rows=$sp->spInserORCheck($database, $orid, $bankName, $branchName, $checkNo, $checkDate, $totAmount, $unAppAmount);
        if(!$affected_rows) {
        
          throw new Exception('An error occurred, please contact your system administrator');
        }
      } else if($_POST["hdnPaymentType$i"]==3) {
      
        $affected_rows=$sp->spInsertORDepositSlip($database, $orid, $depositSlipNo, $depDate, $depTypeid, $totAmount, $unAppAmount, $bankid);
        if(!$affected_rows) {
        
          throw new Exception('An error occurred, please contact your system administrator');
        }
      } else if($_POST["hdnPaymentType$i"]==4) {
      
        $affected_rows=$sp->spInsertORCommission($database, $orid, $commtypeid, $totAmount, $unAppAmount);
        if(!$affected_rows) {
        
          throw new Exception('An error occurred, please contact your system administrator');
        }        
      }
    }
    
    if($ortype==1) {
          
      if($setid==1) {
      
      	// Create Auto Settlement.
        $customerId=$_SESSION['CustomerID'];        		                
        $rs_getInvoices=$tpi->selectOutstandingInvoicesForAutoSettlement($database, $customerId);
        $oramount=$total_mop_amt;
        $totalapplied=0; 
        $orId=$orid;
        
        if($rs_getInvoices->num_rows) {
        
          while($row=$rs_getInvoices->fetch_object()) {                        
            
            $outstandingBalance=$row->OutstandingBalance;
            $salesInvoiceId=$row->SalesInvoiceID;
            $ifSI=$row->ifSI;
            $isWithinTerm=$row->IsWithinCreditTerm;						 
            $netamount=$row->NetAmount;
            $totalcft=$row->TotalCFT;
            $totalncft=$row->TotalNCFT;
            $totalcpi=$row->TotalCPI;
            $paidcft=0;
            $paidncft=0;
            $paidcpi=0;
            
            if($ifSI==1) {
            
              if($oramount==$outstandingBalance) {
              
                $outstandingamt=0;		
                if($isWithinTerm==1 && $ifSI==1) {
                
                  $percentageCFT=round($totalcft/$netamount, 2);
                  $paidcft=round($oramount*$percentageCFT, 2);
                  
                  if($totalcpi==0) {
                  
                    $paidncft=$oramount-$paidcft;		
                    $paidcpi=0;						         			
                  } else {
                  
                    $percentageNCFT=round($totalncft/$netamount, 2);
                    $paidncft=round($oramount*$percentageNCFT, 2);
                    $paidcpi=$oramount-($paidcft+$paidncft);
                  }								         	                
                }
                
                $affected_rows=$sp->spUpdateSalesInvoiceOutstandingBalance($database, $salesInvoiceId, $oramount, $ifSI, $isWithinTerm, $paidcft, $paidncft, $paidcpi);
                if(!$affected_rows) {
                
                  throw new Exception('An error occurred, please contact your system administrator');
                }
                
                $affected_rows=$sp->spInsertOfficialReceiptDetails($database, $orId, $ifSI, $salesInvoiceId, $outstandingamt, $oramount, $createdBy);
                if(!$affected_rows) {
                
                  throw new Exception('An error occurred, please contact your system administrator');
                }
                
                $totalapplied+=$outstandingBalance;
                break;
              } elseif($oramount>$outstandingBalance) {
                
                $outstandingamt=0;
                if($isWithinTerm== 1 && $ifSI==1) {
                
                  $percentageCFT=round($totalcft/$netamount, 2);
                  $paidcft=round($outstandingBalance*$percentageCFT, 2);
                  
                  if($totalcpi==0) {
                  
                    $paidncft=$outstandingBalance-$paidcft;		
                    $paidcpi=0;						         			
                  } else {
                  
                    $percentageNCFT=round($totalncft/$netamount, 2);
                    $paidncft=round($outstandingBalance*$percentageNCFT, 2);
                    $paidcpi=$outstandingBalance-($paidcft+$paidncft);
                  }								         	                
                }
                
                $affected_rows=$sp->spUpdateSalesInvoiceOutstandingBalance($database, $salesInvoiceId, $outstandingBalance, $ifSI, $isWithinTerm, $paidcft, $paidncft, $paidcpi);
                if(!$affected_rows) {
                
                  throw new Exception('An error occurred, please contact your system administrator');
                }
                
                $affected_rows=$sp->spInsertOfficialReceiptDetails($database, $orId, $ifSI, $salesInvoiceId, $outstandingamt, $outstandingBalance, $createdBy);
                if(!$affected_rows) {
                
                  throw new Exception('An error occurred, please contact your system administrator');
                }
                
                $oramount-=$outstandingBalance;
                $totalapplied+=$outstandingBalance;
              } elseif($oramount<$outstandingBalance) {
              
                $outstandingamt=$outstandingBalance-$oramount;
                if($isWithinTerm==1 && $ifSI==1) {
                
                  $percentageCFT=round($totalcft/$netamount, 2);
                  $paidcft=round($oramount*$percentageCFT, 2);
                  
                  if($totalcpi==0) {
                  
                    $paidncft=$oramount-$paidcft;		
                    $paidcpi=0;						         			
                  } else {
                  
                    $percentageNCFT=round($totalncft/$netamount, 2);
                    $paidncft=round($oramount*$percentageNCFT, 2);
                    $paidcpi=$oramount-($paidcft+$paidncft);
                  }								         	                
                }
                
                $affected_rows=$sp->spUpdateSalesInvoiceOutstandingBalance($database, $salesInvoiceId, $oramount, $ifSI, $isWithinTerm, $paidcft, $paidncft, $paidcpi);
                if(!$affected_rows) {
                
                  throw new Exception('An error occurred, please contact your system administrator');
                }
                $affected_rows=$sp->spInsertOfficialReceiptDetails($database, $orId, $ifSI, $salesInvoiceId, $outstandingamt, $oramount, $createdBy);
                if(!$affected_rows) {
                
                  throw new Exception('An error occurred, please contact your system administrator');
                }
                
                $totalapplied+=$oramount;
                break;
              }	
            }	        
          } 
          
          $affected_rows=$sp->spUpdateUnappliedAmountOfficialReceipt($database, $orId, $totalapplied);
          if(!$affected_rows) {
           
            throw new Exception('An error occurred, please contact your system administrator');
          }        
        }					
      } else {
      
        // Create Manual Settlement.
        $orId=$orid;
        $cnt=$_POST['hcntdynamic'];
                
        for($value=0;$value<=$cnt;$value++) {
                  
          if($_POST["hIGSID{$value}"]!='') {          
          
            $customerId=$_POST["hIGSID{$value}"];
            								
            $rs_getInvoices=$sp->spSelectOutstandingInvoicesForAutoSettlement($database, $customerId);
            
            $tmpamt=$_POST["txtIGSAmt{$value}"];
            
            $oramount=number_format($tmpamt, 2, '.', '');
            $totalapplied=0;             
                        
            $outstandingamt=$outstandingBalance-$oramount;
            
            if($rs_getInvoices->num_rows) {
            
              while($row=$rs_getInvoices->fetch_object()) {
              
                $outstandingBalance=$row->OutstandingBalance;
                $salesInvoiceId=$row->SalesInvoiceID;
                $ifSI=$row->ifSI;
                $isWithinTerm=$row->IsWithinCreditTerm;
                $netamount=$row->NetAmount;
                $totalcft=$row->TotalCFT;
                $totalncft=$row->TotalNCFT;
                $totalcpi=$row->TotalCPI;
                $paidcft=0;
                $paidncft=0;
                $paidcpi=0;
                
                if($ifSI!=1) {
                
                  $affected_rows=$sp->spUpdateCustomerPenaltyApplyPayment($database, $salesInvoiceId, $outstandingBalance);
                  $oramount-=$outstandingBalance;                
                } else {
                
                  if($oramount==$outstandingBalance) {
                  
                    $outstandingamt=0;
                    
                    if($isWithinTerm==1 && $ifSI==1) {
                    
                      $percentageCFT=round($totalcft/$netamount, 2);
                      $paidcft=round($oramount*$percentageCFT, 2);
                      
                      if($totalcpi==0) {
                      
                        $paidncft=$oramount-$paidcft;		
                        $paidcpi=0;						         			
                      } else {
                      
                        $percentageNCFT=round($totalncft/$netamount, 2);
                        $paidncft=round($oramount*$percentageNCFT, 2);
                        $paidcpi=$oramount-($paidcft+$paidncft);
                      }								         	                    
                    }
                                        
                    $affected_rows=$sp->spUpdateSalesInvoiceOutstandingBalance($database, $salesInvoiceId, $oramount, $ifSI, $isWithinTerm, $paidcft, $paidncft, $paidcpi);
                    if(!$affected_rows) {
                     
                      throw new Exception('An error occurred, please contact your system administrator');
                    }
                    
                    $affected_rows=$sp->spInsertOfficialReceiptDetails($database, $orId, $ifSI, $salesInvoiceId, $outstandingamt, $oramount, $createdBy);                    
                    if(!$affected_rows) {
                     
                      throw new Exception('An error occurred, please contact your system administrator');
                    }
                    
                    $totalapplied+=$outstandingBalance;
                    break;
                  } elseif($oramount>$outstandingBalance) {
                  
                    $outstandingamt=0;
                    if($isWithinTerm==1 && $ifSI==1) {
                    
                      $percentageCFT=round($totalcft/$netamount, 2);
                      $paidcft=round($oramount*$percentageCFT, 2);
                      
                      if($totalcpi==0) {
                      
                        $paidncft=$oramount-$paidcft;		
                        $paidcpi=0;						         			
                      } else {
                      
                        $percentageNCFT=round($totalncft/$netamount, 2);
                        $paidncft=round($oramount*$percentageNCFT, 2);
                        $paidcpi=$oramount-($paidcft+$paidncft);
                      }								         	                    
                    }
                    
                    $affected_rows=$sp->spUpdateSalesInvoiceOutstandingBalance($database, $salesInvoiceId, $outstandingBalance, $ifSI, $isWithinTerm, $paidcft, $paidncft, $paidcpi);
                    if(!$affected_rows) {
                     
                      throw new Exception('An error occurred, please contact your system administrator');
                    }
                    
                    $affected_rows=$sp->spInsertOfficialReceiptDetails($database, $orId, $ifSI, $salesInvoiceId, $outstandingamt, $outstandingBalance, $createdBy);                    
                    if(!$affected_rows) {
                     
                      throw new Exception('An error occurred, please contact your system administrator');
                    }
                    
                    $oramount-=$outstandingBalance;
                    $totalapplied+=$outstandingBalance;
                  } elseif($oramount<$outstandingBalance) {
                                    
                    $outstandingamt=$outstandingBalance-$oramount;
                    
                    if($isWithinTerm==1 && $ifSI==1) {
                    
                      $percentageCFT=round($totalcft/$netamount, 2);
                      $paidcft=round($oramount*$percentageCFT, 2);
                      
                      if($totalcpi==0) {
                      
                        $paidncft=$oramount-$paidcft;		
                        $paidcpi=0;						         			
                      } else {
                      
                        $percentageNCFT=round($totalncft/$netamount, 2);
                        $paidncft=round($oramount*$percentageNCFT, 2);
                        $paidcpi=$oramount-($paidcft+$paidncft);
                      }								         	                    
                    }
                    
                    $affected_rows=$sp->spUpdateSalesInvoiceOutstandingBalance($database, $salesInvoiceId, $oramount, $ifSI, $isWithinTerm, $paidcft, $paidncft, $paidcpi);
                    if(!$affected_rows) {
                     
                      throw new Exception('An error occurred, please contact your system administrator');
                    }
                    
                    $affected_rows=$sp->spInsertOfficialReceiptDetails($database, $orId, $ifSI, $salesInvoiceId, $outstandingamt, $oramount, $createdBy);                    
                    if(!$affected_rows) {
                     
                      throw new Exception('An error occurred, please contact your system administrator');
                    }
                    
                    $totalapplied+=$oramount;
                    break;
                  }	
                }	        
              }
              
              $affected_rows=$sp->spUpdateUnappliedAmountOfficialReceipt($database, $orId, $totalapplied);
              if(!$affected_rows) {
               
                throw new Exception('An error occurred, please contact your system administrator');
              }            
            }
          }
        }	          
      }
    }
    
    // Update the BIR series.
    $rs_update=$sp->spUpdateBIRSeriesByBranchID($database, $bir_id, 8);
    if(!$rs_update) {
     
      throw new Exception('An error occurred, please contact your system administrator');
    }
    
    $database->commitTransaction();
    
    printpdf($orId);
    
    $msg='Successfully created Official Receipt.';
            			
    unset($_SESSION['coll_mop']);
    unset($_SESSION['tmp_coll_mop']);
    unset($_SESSION['coll_addsi']);		
    unset($_SESSION['CustomerID']);        
  }  
}

function printpdf($orId) {

  echo '<script type="text/javascript">
        function NewWindow(mypage, myname, w, h, scroll) {
        
          var winl=(screen.width-w)/2;
          var wint=(screen.height-h)/2;
          winprops="height="+h+",width="+w+",top="+wint+",left="+winl+",scrollbars="+scroll+",resizable,menubar=yes,toolbar=no";
          win=window.open(mypage, myname, winprops);
          
          if(parseInt(navigator.appVersion)>=4) win.window.focus();
        }
                
        pagetoprint="pages/sales/prOfficialReceipt.php?orid='.$orId.'&prntcnt=0";
        NewWindow(pagetoprint, "printps", "850", "1100", "yes");
        location.href="index.php?pageid=97&msg=Successfully created Official Receipt.";        
        </script>';	
}
