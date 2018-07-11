<?php
/*   
  @modified by John Paul Pineda
  @date May 4, 2013
  @email paulpineda19@yahoo.com         
*/

global $database;

$txnid=$_GET['TxnID'];
$islocked=0;
$lockedby=null;
$table='salesorder';
$outstandingbalance=0;
$creditLimit=0;

$rs_branch=$sp->spSelectBranch($database, -2, '');
if($rs_branch->num_rows) {

  while($row=$rs_branch->fetch_object()) $branch=$row->Name;  
}

// Retrieve the Sales Order header.
$rs_header=$sp->spSelectSOHeaderByID($database, $txnid);
if($rs_header->num_rows) {

  while($row=$rs_header->fetch_object()) {
  			
    $id=$row->SOID;
    $sono=$row->SONo;
    $docno=$row->DocumentNo;
    $statusid=$row->StatusID;
    $status=$row->TxnStatus;			
    $custcode=$row->CustCode;
    $custname=$row->CustomerName;	
    $custid=$row->CustomerID;		
    $txndate=$row->TxnDate;	
    $remarks=$row->Remarks;			
    $grossamt=$row->GrossAmount;
    $netamt=number_format($row->NetAmount, 2);
    $paymentTerm=$row->PaymentTerm;
    $vat=$row->VatAmount;
    $vatamt=$grossamt;
    $salesman=$row->Salesman;
    $deliverydate=$txndate;
    $totalCft=$row->TotalCFT;
    $totalCpi=$row->TotalCPI;
    $totalNcft=$row->TotalNCFT;
    $basicDiscount=$row->BasicDiscount;
    $vatAmount=$row->VatAmount;
    $addtlDiscount=$row->AddtlDiscount;
    $addtlPrevDiscount=$row->AddtlDiscountPrev;
    $salesWithVat=$row->SalesWithVat;
    $totalInvoiceAmountDue=$row->TotalInvoiceAmountDue;
    $ibm=$row->IBM;
    $gsutype=$row->GSUType;
    $createdby=$row->CreatedBy;
    $confirmedby=$row->ConfirmedBy;
  }
}

// Retrieve Customer Credit details. 
$rsCreditLimit=$sp->spSelectCreditLimitByCustomerID($database, $custid);
if($rsCreditLimit->num_rows) {

  while($customerCL=$rsCreditLimit->fetch_object()) $creditLimit=$customerCL->ApprovedCL;  
}

/*
$rsARBalance=$sp->spSelectARBalanceByCustomerID($database, $custid);
if($rsARBalance->num_rows) {

  while($ARBalance=$rsARBalance->fetch_object()) $outstandingbalance=$ARBalance->GrossAmount-$ARBalance->TotalPayment;   
}
*/

$rsARBalance=$sp->spSelectNetUnpaidSIByCustomerID($database, $custid); 
if($rsARBalance->num_rows) {

	while($field=$rsARBalance->fetch_object()) $ARBalance=$field->unpaidSI;						
}

$rsAvailableCredit=$tpi->getAvailableCreditByCustomerID($database, $custid);
if($rsAvailableCredit->num_rows) {
  
  while($field=$rsAvailableCredit->fetch_object()) $availableCredit=$field->availableCredit;
}

$rsPenalty=$sp->spSelectPenaltyByCustomerID($database, $custid);
if($rsPenalty->num_rows) {

  while($Penalty=$rsPenalty->fetch_object()) $outstandingpenalty=$Penalty->OutstandingAmount;  
}

$availablecredit=0;
$availablecredit=$creditLimit-$outstandingbalance;

// Retrieve Sales Order details.
$rs_details=$sp->spSelectSODetailsByID($database, $txnid, 1);
$rsAppliedPromoEntitlements=$tpi->getAppliedPromoEntitlements($txnid);
$rsAppliedIncentiveEntitlements=$tpi->getAppliedIncentiveEntitlements($txnid);

// Confirm Sales Order
if(isset($_POST['btnConfirm'])) {

  // Update Sales Order status;
  $rs_confirm=$sp->spConfirmSalesOrder($database, $txnid);
  
  // Insert to Stocklog.
  $rs_drdetails=$sp->spSelectDRDetailsByID($database, $rs_confirm);
  if($rs_drdetails->num_rows) {
  
    while($row=$rs_drdetails->fetch_object()) {
    
      $prod_qty=$sp->spGetQtyByMultiplier($row->ProductID, $row->UOMID, $row->DelQty);
      if($prod_qty->num_rows) {
      
        while($row_qty=$prod_qty->fetch_object()) {
        
          $tot_qty=$row_qty->Qty;						
        } $prod_qty->close();					
      }
      
      $rs_stocklog=$stocklog->AddToStockLog($row->WarehouseID, $row->InventoryID, $row->ClassID, $row->CBatchNo, $row->PBatchNo, $row->PLotNo, $row->ProductID, $row->UnitCost, $rs_confirm, '', '', 6, $tot_qty);			
    }	$rs_drdetails->close();
  }
  
  // Unlock transaction
  try {
  
    $database->beginTransaction();
    
    $updatestatus=$sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $txnid);
    if(!$updatestatus) {
    
      throw new Exception('An error occurred, please contact your system administrator.');
    }
    
    $database->commitTransaction();			
  } catch(Exception $e) {
  
    $database->rollbackTransaction();	
    $errmsg=$e->getMessage()."\n";
    redirect_to('index.php?pageid=35.1&msg='.$errmsg.'&txnid='.$txnid);	
  }
  
  $message='Successfully confirmed transaction.';
  redirect_to('index.php?pageid=35&msg='.$message);
}

if(isset($_POST['btnCancel'])) {

// Unlock transaction
try {

  $database->beginTransaction();
  
  $updatestatus=$sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $txnid);
  if(!$updatestatus) {
  
    throw new Exception('An error occurred, please contact your system administrator.');
  }
  			
  $database->commitTransaction();
} catch(Exception $e) {

  $database->rollbackTransaction();	
  $errmsg=$e->getMessage()."\n";
  redirect_to('index.php?pageid=35.1&msg='.$errmsg.'&txnid='.$txnid);	
}

  redirect_to('index.php?pageid=35');
}


// Close Sales Order
if(isset($_POST['btnCloseSO'])) {

  $txnid=$_GET['TxnID'];
  
  $rs_closeSO=$sp->spUpdateSOStatus($database, $txnid);	
  $message='Sales Order has been successfully closed';
  
  // Unlock transaction
  try {
  
    $database->beginTransaction();
    
    $updatestatus=$sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $txnid);
    if(!$updatestatus) {
    
      throw new Exception('An error occurred, please contact your system administrator.');
    }
    			
    $database->commitTransaction();
  } catch(Exception $e) {
  
    $database->rollbackTransaction();	
    $errmsg=$e->getMessage()."\n";
    redirect_to('index.php?pageid=35.1&msg='.$errmsg.'&txnid='.$txnid);	
  }
  
  redirect_to('index.php?pageid=35&msg='.$message);
}



$locked=0;
if(isset($_GET['locked'])) $locked=1;

if($locked == 0) {

  // Check if the transaction is locked
  try {
  
    $database->beginTransaction();
    
    $checkiflocked=$sp->spCheckIfTransactionIsLocked($database, 'salesorder', $txnid);
    if(!$checkiflocked) {
    
      $errmsg='An error occurred, please contact your system administrator.';
      redirect_to('index.php?pageid=35&errmsg='.$errmsg);
    } else {
    
      if($checkiflocked->num_rows) {
      
        while($row=$checkiflocked->fetch_object()) {
        
          $islocked=$row->IsLocked;
          $lockedby=$row->FirstName.' '.$row->LastName;					
        }				
      }
      
      if($islocked==1) {
      
        $errmsg='Selected transaction is locked by '.$lockedby;
        redirect_to('index.php?pageid=35&errmsg='.$errmsg);
      } else {
      
        $updatestatus=$sp->spUpdateLockStatus($database, $table, 1, $session->emp_id, $txnid);
        if(!$updatestatus) {
        
          $errmsg='An error occurred, please contact your system administrator.';
          redirect_to('index.php?pageid=35&errmsg='.$errmsg);
        }				
      }			
    }
    
    $database->commitTransaction();
  } catch(Exception $e) {
  
    $database->rollbackTransaction();	
    $errmsg=$e->getMessage()."\n";
    redirect_to('index.php?pageid=35&errmsg='.$errmsg);	
  }
}
