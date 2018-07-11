<?php
/*   
  @modified by John Paul Pineda
  @date May 4, 2013
  @email paulpineda19@yahoo.com         
*/

ini_set('display_errors', '1');

$tpi_debug_mode=true;

global $database;

$isAdvance=(!isset($_GET['adv']))?0:$_GET['adv'];

$custID=(!isset($_GET['custID']))?0:$_GET['custID']; 

$gsutypeID=(!isset($_POST['hGSUType']))?'null':$_POST['hGSUType'];

if($isAdvance==1) {

	$rsCheckifAdvPO=$tpi->checkIfAdvPO();
	$ifAdvPO=$rsCheckifAdvPO->fetch_object()->cnt; // Updated by JP on September 18, 2012.		
}

$table='salesorder';

$db->execute("DELETE FROM tmp_applied_promos_application_functions WHERE SessionID='".session_id()."';");

if(isset($_GET['TxnID'])) {

	$txnid=$_GET['TxnID'];
  
  $rs_header=$sp->spSelectSOHeaderByID($database, $txnid);		
	$rsServedDetails=$sp->spSelectServedItemsBackOrder($database, $txnid);  
	$rsOpenDetails=$sp->spSelectOpenItemsBackOrder($database, $txnid);
  $rsAppliedPromoEntitlements=$tpi->getAppliedPromoEntitlements($txnid);	
  
  if($rs_header->num_rows) {
  
    while($row=$rs_header->fetch_object()) {
      
      $id=$row->SOID;
      $custID=$row->CustomerID;
      $sono=$row->SONo;
      $docno=$row->DocumentNo;
      $status=$row->TxnStatus;			
      $custcode=$row->CustCode;
      $custname=$row->CustomerName;			
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
      $gsutypeID=$row->GSUTypeID;
      $gsuType=$row->GSUType;
      $customerStatus=$row->CustomerStatus;
      $createdby=$row->CreatedBy;
      $confirmedby=$row->ConfirmedBy;
      
      $isEmployee=($row->CustomerTypeID==99)?1:0;            
    }
  }
  
  // Source Codes added on October 30, 2012 start here...
  
  // Get the Dealer's Total Unpaid Invoices Amount. 
  $rsUnpaidSalesInvoice=$sp->spSelectNetUnpaidSIByCustomerID($database, $custID); 
	if($rsUnpaidSalesInvoice->num_rows) {
  
		while($unpaidSI=$rsUnpaidSalesInvoice->fetch_object()) $unpaidInvoice=$unpaidSI->unpaidSI;			
	}
  
  // Get the Dealer's Credit Limit Amount. 
  $rsCreditLimit=$sp->spSelectCreditLimitByCustomerID($database, $custID);
	if($rsCreditLimit->num_rows) {
  
		while($customerCL=$rsCreditLimit->fetch_object()) $creditLimit=$customerCL->ApprovedCL;		
	}
  
  // Compute the Dealer's Available Credit Amount.
  $availableCredit=($unpaidInvoice>$creditLimit)?(0.00):($creditLimit-$unpaidInvoice);
  
  // Get the Dealer's Total Penalty Amount.
  $rsPenalty=$sp->spSelectPenaltyByCustomerID($database, $custID);
	if($rsPenalty->num_rows) {
  
		while($Penalty=$rsPenalty->fetch_object()) $outstandingPenalty=$Penalty->OutstandingAmount;					
	}
  
  // Get the Dealer's Total Month to Date CFT.
  $rsMTDCFT=$sp->spSelectMTDbyCustomerID($database, $custID, date('m'), date('Y'), 1);				
	if($rsMTDCFT->num_rows) {
  
		while($tmpMTDCFT=$rsMTDCFT->fetch_object()) {
    
			$MTDCFT=$tmpMTDCFT->Amount;
			$MTDCFTDisc=$tmpMTDCFT->DiscountID;			
		}
	}
  
  // Get the Dealer's Total Year to Date CFT.
  $rsYTDCFT=$sp->spSelectYTDbyCustomerID($database, $custID, date('Y'), 1);				
	if($rsYTDCFT->num_rows) {
  
		while($tmpYTDCFT=$rsYTDCFT->fetch_object()) $YTDCFT=$tmpYTDCFT->Amount;								
	}
  
  // Get the Dealer's Total Month to Date NCFT.
  $rsMTDNCFT=$sp->spSelectMTDbyCustomerID($database, $custID, date('m'), date('Y'), 2);				
	if($rsMTDNCFT->num_rows) {
  
		while($tmpMTDNCFT=$rsMTDNCFT->fetch_object()) {
    
		  $MTDNCFT=$tmpMTDNCFT->Amount;
		  $MTDNCTDisc=$tmpMTDNCFT->DiscountID;			
		}
	}
  
  // Get the Dealer's Total Year to Date NCFT.
	$rsYTDNCFT=$sp->spSelectYTDbyCustomerID($database, $custID, date('Y'), 2);				
	if($rsYTDNCFT->num_rows) {
  
		while($tmpYTDNCFT=$rsYTDNCFT->fetch_object()) $YTDNCFT=$tmpYTDNCFT->Amount;
	}
  // Source Codes added on October 30, 2012 end here...	
}

$rsNextSONo=$sp->spSelectNextSONo($database);
$row=$rsNextSONo->fetch_object();
$SODOCNo=$row->SONo;
$branchID=1;

if(!isset($_POST["txtSODate"])) {

	$date=time();
	$sodate=date("m/d/Y", $date);
} else $sodate=$_POST["txtSODate"];

if(!isset($_POST["txtDelDate"])) {

	$date=time();
	$deldate=date("m/d/Y", $date);
} else $deldate=$_POST["txtDelDate"];

// Source Code added on November 5, 2012 starts here...
$v_Remarks=isset($_POST['txtRemarks'])?$_POST['txtRemarks']:'';
// Source Code added on November 5, 2012 ends here...

$rs_branch=$sp->spSelectBranch($database, -2, '');
if($rs_branch->num_rows) {

	while($row=$rs_branch->fetch_object()) {
  
		$branch=$row->Name ;
		$branchID=$row->ID;
	}
}

$rsEmployee=$sp->spSelectEmployee($database, $_SESSION['emp_id'], '');
if($rsEmployee->num_rows) {

	while($row=$rsEmployee->fetch_object()) $employee=$row->Name;			
}

// If the "Create SO" button has been clicked.
if(isset($_POST['btnConfirmSO'])) {

  if($isAdvance=='') $isAdvance=0;

  $rs_freeze=$sp->spCheckInventoryStatus($database);
  
  if($rs_freeze->num_rows) {
  
    while($row=$rs_freeze->fetch_object()) $statusid_inv=$row->StatusID;                      
  } else $statusid_inv=20;
    
  if($statusid_inv==21) {
  
    $message='Cannot save transaction, Inventory Count is in progress.';
    redirect_to('index.php?pageid=31&errmsg='.$message);               
  } else {
  
    $v_CustomerID=$_POST['hCOA'];
    $v_DocumentNo=$_POST['txtRefNo'];
    $v_EmployeeID=$_SESSION['emp_id'];
    $v_WarehouseID=1;		
    $v_GrossAmount=str_replace(",","",$_POST['txtGrossAmt']);	
    $v_BasicDiscount=str_replace(",","",$_POST['txtBasicDiscount']);	
    $v_AddtlDiscount=str_replace(",","",$_POST['txtAddtlDisc']);	
    $v_VatAmount=str_replace(",","",$_POST['txtVatAmt']);	
    $v_AddtlDiscountPrev=str_replace(",","",$_POST['txtADPP']);	
    $v_NetAmount=str_replace(",","",$_POST['txtNetAmt']);
    $v_TotalCPI=str_replace(",","",$_POST['totCPI']);
    $v_TotalCFT=str_replace(",","",$_POST['totCFT']);
    $v_TotalNCFT=str_replace(",","",$_POST['totNCFT']);    
    	
    $cnt=$_POST['hcnt']-1;
    
    $termsID=0;
    
    $v_statusID=7;
    
    $rsGetTerms=$sp->spSelectCredittermsByCustID($database, $v_CustomerID);    
    if($rsGetTerms->num_rows) {
    
      while($row=$rsGetTerms->fetch_object()) $termsID=$row->TermsID;                   
    }
    
    if($v_NetAmount<=500.00) $termsID=1;
    				    
    $sessionID=session_id();
    
    $rsPromoDate=$sp->spSelectPurchaseDateProductList($database, $sessionID);     	
    if($rsPromoDate->num_rows) {
    
      while($getPromoDate=$rsPromoDate->fetch_object()) $promoDate=$getPromoDate->PurchaseDate;      
    }
    
    try {
    
      $database->beginTransaction();
      
      $soheader=$tpi->insertSOHeader($database, $v_CustomerID, $v_DocumentNo, $v_EmployeeID, $v_WarehouseID, $v_GrossAmount,  $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount, $v_TotalCPI, $v_TotalCFT, $v_TotalNCFT, $v_statusID, $termsID, $gsutypeID, $promoDate, $isAdvance, $branchID, $v_Remarks);
      
      if(!$soheader) {
      
        throw new Exception('An error occurred, please contact your system administrator.');
      }
      
      // Remove applied incentives.
      $database->execute("DELETE FROM u USING unclaimedproducts u INNER JOIN productlist ON UnclaimedProductsID=u.ID WHERE PHPSession='".$sessionID."'");
      
      $availSI=0;
      
      while($row=$soheader->fetch_object()) {
      				
        $SOID=$row->SOID;				
        $session=session_id();
        						
        for($i=1;$i<=$cnt;$i++) {
        
          $productID=$_POST['hProdID'.$i];
          $substituteID=$_POST['hSubsID'.$i];
          
          if($substituteID!=0) $productID=$substituteID;
                    
          $promoID=$_POST['hPromoID'.$i];					
          $pmgid=$_POST['hPMGID'.$i];
          $qty=$_POST['txtOrderedQty'.$i];
          $effectiveprice=str_replace(",","",$_POST['txtEffectivePrice'.$i]);
          $totalamt=str_replace(",","",$_POST['txtTotalPrice'.$i]);					
          $producttype=$_POST['hProductType'.$i];
          $soh=$_POST['hSOH'.$i];	
          $served=$_POST['hServed'.$i];
          $forIncentive=$_POST['hForIncentive'.$i];
          
          if($promoID=='' || $promoID==0) $promoID='null';          
          
          $promotype=$_POST['hPromoType'.$i];                    
          if($promotype=='') $promotype='null';
                              
          if($served==1) {
          
            $availSI=1;
            $v_outstandingqty=0;
          } else $v_outstandingqty=$qty;
                              
          $insertSODetails=$sp->spInsertSODetails($database, $SOID, $productID, $promoID, $promotype, $pmgid, $qty, $totalamt, $effectiveprice, $i, $producttype, $v_outstandingqty, $forIncentive);
          
          if(!$insertSODetails) {
          
            throw new Exception('An error occurred, please contact your system administrator.');
          }	
          
          // Insert into unclaimedproducts. Used for incentives calculation.
          for($j=1;$j<=$qty;$j++) $sp->spInsertTmpUnclaimedProducts($database, $v_CustomerID, $productID, $effectiveprice, $pmgid, $producttype, $SOID);
          
          // Commit Transaction.          
          $database->commitTransaction();        
        }
      }				            
      redirect_to('index.php?pageid=35');    
    } catch(Exception $e) {
    
      $database->rollbackTransaction();	
      $errmsg=$e->getMessage()."\n";
      redirect_to('index.php?pageid=34&msg='.$errmsg);	
    }
  }	
}



// If the "Create SI" button has been clicked.
if(isset($_POST['btnSave'])) {
	
  if($isAdvance=='') $isAdvance=0;
      
  // Check the Status of the Inventory.
  $rs_freeze=$sp->spCheckInventoryStatus($database);
  if($rs_freeze->num_rows) {
  
    while($row=$rs_freeze->fetch_object()) $statusid_inv=$row->StatusID;                      
  } else $statusid_inv=20;
    
  if($statusid_inv==21) {
  
    $message='Cannot save transaction, Inventory Count is in progress.';
    redirect_to('index.php?pageid=31&errmsg='.$message);               
  } else {
      
    $v_CustomerID=$_POST['hCOA'];
    $v_DocumentNo=$_POST['txtRefNo'];
    $v_EmployeeID=$_SESSION['emp_id'];
    $v_WarehouseID=1;		
    $v_GrossAmount=str_replace(',', '', $_POST['txtGrossAmt']);	
    $v_BasicDiscount=str_replace(',', '', $_POST['txtBasicDiscount']);	
    $v_AddtlDiscount=str_replace(',', '', $_POST['txtAddtlDisc']);	
    $v_VatAmount=str_replace(',', '', $_POST['txtVatAmt']);	
    $v_AddtlDiscountPrev=str_replace(',', '', $_POST['txtADPP']);	
    $v_NetAmount=str_replace(',', '', $_POST['txtNetAmt']);
    $v_TotalCPI=str_replace(',', '', $_POST['totCPI']);
    $v_TotalCFT=str_replace(',', '', $_POST['totCFT']);
    $v_TotalNCFT=str_replace(',', '', $_POST['totNCFT']);
    	
    $cnt=$_POST['hcnt']-1;
    
    $termsID=0;
    
    $rsGetTerms=$sp->spSelectCredittermsByCustID($database, $v_CustomerID);
    if($rsGetTerms->num_rows) {
    
      while($row=$rsGetTerms->fetch_object()) $termsID=$row->TermsID;                   
    }
    
    if($v_NetAmount<=500.00) $termsID=1;
        
    for($i=1;$i<=$cnt;$i++) {
    
      $qty=$_POST['txtOrderedQty'.$i];
      $soh=$_POST['hSOH'.$i];
      $served=$_POST['hServed'.$i];
      
      if($served==0) {
      
        $v_statusID=7;
        break;
      } else $v_statusID=9;      
    }
    
    $sessionID=session_id();
    
    $rsPromoDate=$sp->spSelectPurchaseDateProductList($database, $sessionID); 	
    if($rsPromoDate->num_rows) {
    
      while($getPromoDate=$rsPromoDate->fetch_object()) $promoDate=$getPromoDate->PurchaseDate;      
    }
    
    try {
    
      $database->beginTransaction();
      
      $soheader=$tpi->insertSOHeader($database, $v_CustomerID, $v_DocumentNo, $v_EmployeeID, $v_WarehouseID, $v_GrossAmount, $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount, $v_TotalCPI, $v_TotalCFT, $v_TotalNCFT, $v_statusID, $termsID, $gsutypeID, $promoDate, $isAdvance, $branchID, $v_Remarks);
      
      if(!$soheader) {
      
        throw new Exception('An error occurred, please contact your system administrator.');
      }
      
      // Remove applied incentives.
      $database->execute("DELETE FROM u USING unclaimedproducts u INNER JOIN productlist ON UnclaimedProductsID=u.ID WHERE PHPSession='".$sessionID."'");
      
      $availSI=0;
      
      while($row=$soheader->fetch_object()) {
      				
        $SOID=$row->SOID;
        				
        $session=session_id();
        						
        for($i=1;$i<=$cnt;$i++) {
        
          $productID=$_POST['hProdID'.$i];
          $substituteID=$_POST['hSubsID'.$i];
          
          if($substituteID!=0) $productID=$substituteID;
                    
          $promoID=$_POST['hPromoID'.$i];					
          $pmgid=$_POST['hPMGID'.$i];
          $qty=$_POST['txtOrderedQty'.$i];
          $effectiveprice=str_replace(',', '', $_POST['txtEffectivePrice'.$i]);
          $totalamt=str_replace(',', '', $_POST['txtTotalPrice'.$i]);					
          $producttype=$_POST['hProductType'.$i];
          $soh=$_POST['hSOH'.$i];	
          $served=$_POST['hServed'.$i];
          $forIncentive=$_POST['hForIncentive'.$i];
          
          if($promoID=='' || $promoID==0) $promoID='null';          
          
          $promotype=$_POST['hPromoType'.$i];
          if($promotype=='') $promotype='null';
                              
          if($served==1) {
          
            $availSI=1;
            $v_outstandingqty=0;
          } else $v_outstandingqty=$qty;
                              
          $insertSODetails=$sp->spInsertSODetails($database, $SOID, $productID, $promoID, $promotype, $pmgid, $qty, $totalamt, $effectiveprice, $i, $producttype, $v_outstandingqty, $forIncentive);
          
          if(!$insertSODetails) {
          
            throw new Exception('An error occurred, please contact your system administrator.');
          }	
          
          // Insert into unclaimedproducts. Used for incentives calculation.
          for($j=1;$j<=$qty;$j++) $sp->spInsertTmpUnclaimedProducts($database, $v_CustomerID, $productID, $effectiveprice, $pmgid, $producttype, $SOID);                                 
        }
        
        if(isset($_POST['applicable_single_line_promo_entitlements'])) {
          
          $applicable_single_line_promo_entitlement=explode('_', $_POST['applicable_single_line_promo_entitlements'][0]);
          
          $promo_id=$applicable_single_line_promo_entitlement[0];
          $promo_entitlement_criteria=$applicable_single_line_promo_entitlement[1];
          $promo_entitlement_id=$applicable_single_line_promo_entitlement[2];
          $product_id=$applicable_single_line_promo_entitlement[3];
          $product_quantity=$applicable_single_line_promo_entitlement[4];
          $product_effective_price=$applicable_single_line_promo_entitlement[5];
          $product_savings=$applicable_single_line_promo_entitlement[6];
          $product_pmg_id=$applicable_single_line_promo_entitlement[7];
          
          $tpi->insertAppliedSingleLinePromoEntitlementDetails($database, $v_CustomerID, $SOID, $promo_id, $promo_entitlement_criteria, $promo_entitlement_id, $product_id, $product_quantity, $product_effective_price, $product_savings, $product_pmg_id);  
        }
        
        foreach(array('applicable_multi_line_promo_entitlements', 'applicable_overlay_promo_entitlements') as $applicable_promo_entitlement_key) {
        
          if(sizeof($_POST[$applicable_promo_entitlement_key])>0) {
          
            foreach($_POST[$applicable_promo_entitlement_key] as $key=>$applicable_promo_entitlement) {
            
              $applicable_promo_entitlement=explode('_', $applicable_promo_entitlement);
              
              $promo_id=$applicable_promo_entitlement[0];
              $promo_entitlement_type=$applicable_promo_entitlement[1];
              $promo_entitlement_details_id=$applicable_promo_entitlement[2];
              $product_id=$applicable_promo_entitlement[3];
              $product_quantity=$applicable_promo_entitlement[4];            
              $product_outstanding_quantity=$applicable_promo_entitlement[5];            
              $product_effective_price=$applicable_promo_entitlement[6];
              $product_savings=$applicable_promo_entitlement[7];
              $product_pmg_id=$applicable_promo_entitlement[8];
              
              $tpi->{($applicable_promo_entitlement_key=='applicable_multi_line_promo_entitlements'?'insertAppliedMultiLinePromoEntitlementDetails':'insertAppliedOverlayPromoEntitlementDetails')}($database, $v_CustomerID, $SOID, $promo_id, $promo_entitlement_type, $promo_entitlement_details_id, $product_id, $product_quantity, $product_outstanding_quantity, $product_effective_price, $product_savings, $product_pmg_id);             
            }
          }                                      
        }
        
        if(sizeof($_POST['applicable_incentive_entitlements'])>0) {
          
          foreach($_POST['applicable_incentive_entitlements'] as $key=>$applicable_incentive_entitlement) {
          
            $applicable_incentive_entitlement=explode('_', $applicable_incentive_entitlement);
            
            $incentive_id=$applicable_incentive_entitlement[0];              
            $incentive_buyin_id=$applicable_incentive_entitlement[1];                          
            $incentive_entitlement_type=$applicable_incentive_entitlement[2];              
            $incentive_entitlement_details_id=$applicable_incentive_entitlement[3];              
            $product_id=$applicable_incentive_entitlement[4];              
            $product_quantity=$applicable_incentive_entitlement[5];                          
            $product_outstanding_quantity=$applicable_incentive_entitlement[6];                          
            $product_effective_price=$applicable_incentive_entitlement[7];
            $product_savings=$applicable_incentive_entitlement[8];
            $product_pmg_id=$applicable_incentive_entitlement[9];
            
            $tpi->insertAppliedIncentiveEntitlementDetails($v_CustomerID, $SOID, $incentive_id, $incentive_buyin_id, $incentive_entitlement_type, $incentive_entitlement_details_id, $product_id, $product_quantity, $product_outstanding_quantity, $product_effective_price, $product_savings, $product_pmg_id);             
          }
        }
        
        // $insertSODetails=$sp->spInsertSODetails($SOID, $session);
        if($availSI>0) {
        
          $insertDR=$sp->spInsertDeliveryReceipt($database, $SOID);
          if(!$insertDR) {
          
            throw new Exception('An error occurred, please contact your system administrator.');
          }
          
          while($row2=$insertDR->fetch_object()) {
          
            $DRID=$row2->DRID;
            $InsertDeliveryReceiptDetails=$sp->spInsertDeliveryReceiptDetails($database, $DRID, $SOID);
          }
          
          $insertSI=$sp->spInsertSalesInvoice($database, $SOID, $DRID);
          if(!$insertSI) {
          
            throw new Exception('An error occurred, please contact your system administrator.');
          }
          
          while($row1=$insertSI->fetch_object()) {
          				
            $SIID=$row1->SIID;
            					
            $insertSIDetails=$sp->spInsertSalesInvoiceDetails($database, $SIID, $SOID);				
            if(!$insertSIDetails) {
            
              throw new Exception('An error occurred, please contact your system administrator.');
            }
            
            $insertUnlaimedProducts=$sp->spInsertUnclaimedProducts($database, $SIID, $SOID);				
            if(!$insertUnlaimedProducts) {
            
              throw new Exception("An error occurred, please contact your system administrator.");
            }
          }        
        }	
      }
      
      $database->commitTransaction();
      
      if($isAdvance) {
        
        redirect_to('index.php?pageid=35'); exit;
      }
       
      if($availSI>0) redirect_to('index.php?pageid=40.1&tid='.$SIID);      
      else redirect_to('index.php?pageid=35');          
    } catch (Exception $e) {
    
      $database->rollbackTransaction();	
      $errmsg=$e->getMessage()."\n";
      redirect_to('index.php?pageid=34&msg='.$errmsg);	
    }    
  }
}



// If the "Save as Draft" button has been clicked.
if(isset($_POST['btnDraft'])) {
	
  if($isAdvance=='') $isAdvance=0;
      
  // Check the status of the Inventory Count.
  $rs_freeze=$sp->spCheckInventoryStatus($database);
  if($rs_freeze->num_rows) {
  
    while($row=$rs_freeze->fetch_object()) $statusid_inv=$row->StatusID;                      
  } else $statusid_inv=20;
    
  if($statusid_inv==21) {
  
    $message='Cannot save transaction, Inventory Count is in progress.';
    redirect_to('index.php?pageid=31&errmsg='.$message);               
  } else {
  
    try {
    
      $database->beginTransaction();
      
      $v_CustomerID=$_POST['hCOA'];
      $v_DocumentNo=$_POST['txtRefNo'];
      $v_EmployeeID=1;
      $v_WarehouseID=1;		
      $v_GrossAmount=str_replace(",", "", $_POST['txtGrossAmt']);	
      $v_BasicDiscount=str_replace(",", "", $_POST['txtBasicDiscount']);	
      $v_AddtlDiscount=str_replace(",", "", $_POST['txtAddtlDisc']);	
      $v_VatAmount=str_replace(",", "", $_POST['txtVatAmt']);	
      $v_AddtlDiscountPrev=str_replace(",", "", $_POST['txtADPP']);	
      $v_NetAmount=str_replace(",", "", $_POST['txtNetAmt']);
      $v_TotalCPI=str_replace(",", "", $_POST['totCPI']);
      $v_TotalCFT=str_replace(",", "", $_POST['totCFT']);
      $v_TotalNCFT=str_replace(",", "", $_POST['totNCFT']);
      	
      $cnt=$_POST['hcnt']-1;
      		
      $v_statusID=6;
      
      if($v_GrossAmount=='') $v_GrossAmount=0.00;	
            
      if($v_BasicDiscount=='') $v_BasicDiscount=0.00;	
      
      if($v_AddtlDiscount=='') $v_AddtlDiscount=0.00;	
            
      if($v_VatAmount=='') $v_VatAmount=0.00;	
      
      if($v_AddtlDiscountPrev=='') $v_AddtlDiscountPrev=0.00;	
            
      if($v_NetAmount=='') $v_NetAmount=0.00;	
      
      if($v_TotalCPI=='') $v_TotalCPI=0.00;	
      
      if($v_TotalCFT=='') $v_TotalCFT=0.00;	
            
      if($v_TotalNCFT=='') $v_TotalNCFT=0.00;	
      
      if($isAdvance==1) {
      		
        $sessionID=session_id();
        
        $rsPromoDate=$sp->spSelectPurchaseDateProductList($database, $sessionID); 	
        if($rsPromoDate->num_rows) {
        
          while($getPromoDate=$rsPromoDate->fetch_object()) $promoDate=$getPromoDate->PurchaseDate;          
        }
        	
        $soheader=$tpi->insertSOHeader($database, $v_CustomerID, $v_DocumentNo, $v_EmployeeID, $v_WarehouseID, $v_GrossAmount, $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount, $v_TotalCPI, $v_TotalCFT, $v_TotalNCFT, $v_statusID, 1, $gsutypeID, $promoDate, $isAdvance, $branchID, $v_Remarks);
      } else $soheader=$tpi->insertSOHeaderDraft($database, $v_CustomerID, $v_DocumentNo, $v_EmployeeID, $v_WarehouseID, $v_GrossAmount, $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount, $v_TotalCPI, $v_TotalCFT, $v_TotalNCFT, $v_statusID, 1, $gsutypeID, $v_Remarks);
            	
      if(!$soheader) {
      
        throw new Exception("An error occurred, please contact your system administrator.");
      }
      
      while($row=$soheader->fetch_object()) {
      
        $SOID=$row->SOID;
        				
        $session=session_id();
        				
        for($i=1;$i<=$cnt;$i++) {
        
          $productID=$_POST['hProdID'.$i];	
          $substituteID=$_POST['hSubsID'.$i];
          
          if($substituteID!=0) $productID=$substituteID;
                    									
          $promoID=$_POST['hPromoID'.$i];					
          $pmgid=$_POST['hPMGID'.$i];
          $qty=$_POST['txtOrderedQty'.$i];
          $forIncentive=$_POST['hForIncentive'.$i];
          
          $effectiveprice=str_replace(",", "", $_POST['txtEffectivePrice'.$i]);
          $totalamt=str_replace(",", "", $_POST['txtTotalPrice'.$i]);
          	
          if($totalamt=='') $totalamt=0.00;	
                    
          $producttype=$_POST['hProductType'.$i];
          $soh=$_POST['hSOH'.$i];	
          $v_outstandingqty=$qty;
          				
          if($promoID=='' || $promoID==0) $promoID='null';
                    
          $promotype=$_POST['hPromoType'.$i];
          if($promotype=='') $promotype='null';          				
          
          $insertSODetails=$sp->spInsertSODetails($database, $SOID, $productID, $promoID, $promotype, $pmgid, $qty, $totalamt, $effectiveprice, $i, $producttype, $v_outstandingqty, $forIncentive);
          
          if(!$insertSODetails) {
          
            throw new Exception('An error occurred, please contact your system administrator.');
          }                        
        }
      }            
      $database->commitTransaction();        
    } catch(Exception $e) {
    
      $database->rollbackTransaction();	
      $errmsg=$e->getMessage()."\n";	
    }
    redirect_to('index.php?pageid=35');
  }
}	



if(isset($_POST['btnSaveSO'])) {
	
	if($isAdvance ==  "")
	{
		$isAdvance = 0;
	}
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
          redirect_to("index.php?pageid=31&errmsg=$message");               
     }
 	   else
     {
          try
		{
			$database->beginTransaction();
			$v_CustomerID 			= $_POST['hCOA'];
			$v_DocumentNo			= $_POST['txtRefNo'];
			$v_EmployeeID	 		= 1;
			$v_WarehouseID			= 1;		
			$v_GrossAmount 			= str_replace(",","",$_POST['txtGrossAmt']);	
			$v_BasicDiscount 		= str_replace(",","",$_POST['txtBasicDiscount']);	
			$v_AddtlDiscount 		= str_replace(",","",$_POST['txtAddtlDisc']);	
			$v_VatAmount 			= str_replace(",","",$_POST['txtVatAmt']);	
			$v_AddtlDiscountPrev	= str_replace(",","",$_POST['txtADPP']);	
			$v_NetAmount			= str_replace(",","",$_POST['txtNetAmt']);
			$v_TotalCPI				= str_replace(",","",$_POST['totCPI']);
			$v_TotalCFT				= str_replace(",","",$_POST['totCFT']);
			$v_TotalNCFT			= str_replace(",","",$_POST['totNCFT']);	
			$cnt					= $_POST['hcnt'] -1 ;		
			$v_statusID 			= 7 ;
			if($v_GrossAmount == '')
			{
				$v_GrossAmount = 0.00;	
			}
			
			if($v_BasicDiscount == '')
			{
				$v_BasicDiscount = 0.00;	
			}
			if($v_AddtlDiscount == '')
			{
				$v_AddtlDiscount = 0.00;	
			}
			if($v_VatAmount == '')
			{
				$v_VatAmount = 0.00;	
			}
			if($v_AddtlDiscountPrev == '')
			{
				$v_AddtlDiscountPrev = 0.00;	
			}
			if($v_NetAmount == '')
			{
				$v_NetAmount = 0.00;	
			}
			if($v_TotalCPI == '')
			{
				$v_TotalCPI = 0.00;	
			}
			if($v_TotalCFT == '')
			{
				$v_TotalCFT = 0.00;	
			}
			if($v_TotalNCFT == '')
			{
				$v_TotalNCFT = 0.00;	
			}
			if($isAdvance == 1)
			{		
				$sessionID = session_id();
				$rsPromoDate = $sp->spSelectPurchaseDateProductList($database,$sessionID); 	
				if($rsPromoDate->num_rows)
				{
					while($getPromoDate = $rsPromoDate->fetch_object())
					{
						$promoDate = $getPromoDate->PurchaseDate;
					}
				}	
				$soheader = $sp->spInsertSOHeader($database,$v_CustomerID,$v_DocumentNo , $v_EmployeeID , $v_WarehouseID , $v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,1,$gsutypeID,$promoDate,$isAdvance,$branchID);
			}
			else
			{
				$soheader = $sp->spInsertSOHeaderDraft($database,$v_CustomerID,$v_DocumentNo , $v_EmployeeID , $v_WarehouseID , $v_GrossAmount , $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount , $v_AddtlDiscountPrev , $v_NetAmount,$v_TotalCPI,$v_TotalCFT,$v_TotalNCFT,$v_statusID,1,$gsutypeID);
			}	
			while ($row = $soheader->fetch_object())
			{				
				$SOID = $row->SOID;				
				$session = session_id();						
				for($i=1 ; $i <= $cnt ; $i++)
				{
					$productID  		= 	$_POST['hProdID'.$i];
					$substituteID		= 	$_POST['hSubsID'.$i];
					if($substituteID != 0)
					{
						$productID = $substituteID;
					}
										
					$promoID			=	$_POST['hPromoID'.$i];					
					$pmgid 				=	$_POST['hPMGID'.$i];
					$qty				=	$_POST['txtOrderedQty'.$i];
					$effectiveprice		= str_replace(",","",$_POST['txtEffectivePrice'.$i]);
					$totalamt			= str_replace(",","",$_POST['txtTotalPrice'.$i]);					
					$producttype		=	$_POST['hProductType'.$i];
					$soh				=	$_POST['hSOH'.$i];	
					$served				=	$_POST['hServed'.$i];
					$forIncentive		= $_POST['hForIncentive'.$i];
					if ($promoID == '' ||$promoID == 0 )
					{
						$promoID = "null";
					}
					$promotype	=	$_POST['hPromoType'.$i];
					if ($promotype == '')
					{
						$promotype = "null";
					}
					
					
					
						$v_outstandingqty = $qty;
					
				
				
					$insertSODetails = $sp->spInsertSODetails($database,$SOID,$productID, $promoID, $promotype, $pmgid,$qty,$totalamt,$effectiveprice,$i,$producttype,$v_outstandingqty,$forIncentive);
					
					if (!$insertSODetails)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}	
					
					// insert into unclaimedproducts. used for incentives calculation
					for($j=1 ; $j <= $qty ; $j++)
					{
						
						$sp->spInsertTmpUnclaimedProducts($database,$v_CustomerID,$productID, $effectiveprice, $pmgid, $producttype, $SOID);
					}
					
				
				
				}
			}
			
			$database->commitTransaction();
			
		}
       catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";	
		}
		redirect_to("index.php?pageid=35");
		
		

     }
}

if(isset($_POST['btnCreatBO'])) {

  if($isAdvance=='') $isAdvance=0;
  
  // Check the status of the inventory
  $rs_freeze=$sp->spCheckInventoryStatus($database);
  if($rs_freeze->num_rows) {
  
    while($row=$rs_freeze->fetch_object()) $statusid_inv=$row->StatusID;                    
  } else $statusid_inv=20;
  
  if($statusid_inv==21) {
  
    $message='Cannot save the transaction, Inventory Count is in progress.';
    redirect_to('index.php?pageid=31&errmsg='.$message);               
  } else {
  
    $cnt=$_POST['hcnt'];
    $valid=0;
    
    for($i=1;$i<=$cnt;$i++) {
    
      $productID=$_POST['hProdID'.$i];										
      $promoID=$_POST['hPromoID'.$i];					
      $pmgid=$_POST['hPMGID'.$i];
      $qty=$_POST['txtOrderedQty'.$i];
      $effectiveprice=str_replace(',', '', $_POST['txtEffectivePrice'.$i]);
      $totalamt=str_replace(',', '', $_POST['txtTotalPrice'.$i]);
      $producttype=$_POST['hProductType'.$i];
      $soh=$_POST['hSOH'.$i];
      					
      if($promoID=='' || $promoID==0) $promoID='null';
      
      $promotype=$_POST['hPromoType'.$i];
      if($promotype=='') $promotype='null';    
      
      $served=$_POST['hServed'.$i];
      if($served==1) {
      
        $valid=1;
        $break;
      }			
    }
    
    if($valid==1) {
    
      try {
      
        $database->beginTransaction();
        		
        $v_CustomerID=$_POST['hcustID'];
        $v_DocumentNo=$_POST['txtRefNo'];
        $v_EmployeeID=$_SESSION['emp_id'];
        $txnid=$_POST['hTxnID'];
        $v_WarehouseID=1;		
        $v_GrossAmount=str_replace(',', '', $_POST['txtGrossAmt']);	
        $v_BasicDiscount=str_replace(',', '', $_POST['txtBasicDiscount']);	
        $v_AddtlDiscount=str_replace(',', '', $_POST['txtAddtlDisc']);	
        $v_VatAmount=str_replace(',', '', $_POST['txtVatAmt']);	
        $v_AddtlDiscountPrev=str_replace(',', '', $_POST['txtADPP']);	
        $v_NetAmount=str_replace(',', '', $_POST['txtNetAmt']);
        $v_TotalCPI=str_replace(',', '', $_POST['totCPI']);
        $v_TotalCFT=str_replace(',', '', $_POST['totCFT']);
        $v_TotalNCFT=str_replace(',', '', $_POST['totNCFT']);	
        $v_remarks=$_POST['txtRemarks'];
        $v_txndate=date('Y-m-d');
        
        $insertDR=$sp->spInsertDeliveryReceipt($database, $txnid);				
        if(!$insertDR) {
        
          throw new Exception('An error occurred, please contact your system administrator.');
        }
        
        while($row2=$insertDR->fetch_object()) $DRID=$row2->DRID;      
        
        $insertSI=$sp->spInsertSIBackOrder($database, $v_CustomerID, $DRID, $v_DocumentNo, $v_txndate, $v_remarks, $v_GrossAmount, $v_TotalCPI,$v_BasicDiscount, $v_TotalCFT, $v_TotalNCFT, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount);
        
        if(!$insertSI) {
        
          throw new Exception('An error occurred, please contact your system administrator.');
        }
        
        while($row1=$insertSI->fetch_object()) {
        				
          $SIID=$row1->SIID;
          	
          for($i=1;$i<=$cnt;$i++) {
          
            $productID=$_POST['hProdID'.$i];										
            $promoID=$_POST['hPromoID'.$i];					
            $pmgid=$_POST['hPMGID'.$i];
            $qty=$_POST['txtOrderedQty'.$i];
            $effectiveprice=str_replace(',', '', $_POST['txtEffectivePrice'.$i]);
            $totalamt=str_replace(',', '', $_POST['txtTotalPrice'.$i]);						
            $producttype=$_POST['hProductType'.$i];
            $soh=$_POST['hSOH'.$i];	
            $served=$_POST['hServed'.$i];
            					
            if($promoID=='' || $promoID==0) $promoID='null';          
            
            $promotype=$_POST['hPromoType'.$i];
            if($promotype=='') $promotype='null';          
            
            if($served==1) {
                        
              $outstandingqty=0;
              $InsertDeliveryReceiptDetails=$sp->spInsertDeliveryReceiptDetails($database, $DRID, $txnid);
              
              $insertSIDetails=$sp->spInsertSIDetailsBackOrder($database, $SIID, $productID, $promoID, $promotype, $pmgid, $effectiveprice, $qty, $totalamt, $i, $producttype);	
            } else $outstandingqty=$qty;
                      
            $rsSelectSODetailsID=$sp->spSelectSODetailsID($database, $productID, $txnid);
            if($rsSelectSODetailsID->num_rows) {
            
              while($SODID=$rsSelectSODetailsID->fetch_object()) $sodetais=$SODID->ID;            
            }
                      
            $updateSODetails=$sp->spUpdateSODetailsBackOrder($database, $txnid, $productID, $outstandingqty, $sodetais);
            if(!$insertSIDetails) {
            
              throw new Exception('An error occurred, please contact your system administrator.');
            }        
          }				      
        }
        
        $rsCheckSO=$sp->spCheckSOforOutstandingQty($database, $txnid);
        if($rsCheckSO->num_rows) {
        
          while($row=$rsCheckSO->fetch_object()) {
          
            $ctr=$row->cnt;
            if($ctr==0) $rs_closeSO=$sp->spUpdateSOStatus($database, $txnid);          
          }
        }
        
        $database->commitTransaction();
      } catch(Exception $e) {
      
        $database->rollbackTransaction();	
        $errmsg=$e->getMessage()."\n";	
        redirect_to('index.php?pageid=35.2&TxnID='.$txnid.'&msg='.$errmsg);	
      } redirect_to('index.php?pageid=34&adv=0');
    } else {
    
      $errmsg='Product(s) in the list have no SOH.';
      redirect_to('index.php?pageid=35.2&TxnID='.$txnid.'&msg='.$errmsg);
    }	
    
    // Unlock the transaction  
    $updatestatus=$sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $txnid);
    if(!$updatestatus) {
    
      throw new Exception('An error occurred, please contact your system administrator.');
    }        
  }
}

if(isset($_POST['btnBackToList'])) {

  // Unlock the transaction
  
  try {
  
    $database->beginTransaction();
    
    $txnid=$_POST['hTxnID'];
    
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

if(isset($_POST['btnCloseBO'])) {

  // Unlock the transaction
    
  $txnid=$_POST['hTxnID'];
  
  $updatestatus=$sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $txnid);
  if(!$updatestatus) {
  
    throw new Exception('An error occurred, please contact your system administrator.');
  }			
    
  // Check the status of the inventory
  $rs_freeze=$sp->spCheckInventoryStatus($database);
  if($rs_freeze->num_rows) {
  
    while($row=$rs_freeze->fetch_object()) $statusid_inv=$row->StatusID;            
  } else $statusid_inv=20;
    
  if($statusid_inv==21) {
  
    $message='Cannot save the transaction, Inventory Count is in progress.';
    redirect_to('index.php?pageid=31&errmsg='.$message);               
  } else {
  
    $rs_closeSO=$sp->spUpdateSOStatus($database, $txnid);	
    $message='The sales order has been successfully closed.';
    redirect_to('index.php?pageid=35&msg='.$message);
  }
}