<?php
/*   
  @modified by John Paul Pineda
  @date May 10, 2013
  @email paulpineda19@yahoo.com         
*/

if(!ini_get('display_errors')) ini_set('display_errors', 1);

global $database;

$islocked=0;
$lockedby=null;
$table='salesinvoice';

$txnid=$_GET['tid'];

$errmsg='';

$month=date('m'); 
$year=date('Y');
$MTDCFT=0;
$MTDCFTDisc=0;
$YTDCFT=0;
$MTDNCFT=0;
$MTDNCTDisc=0; 
$YTDNCFT=0;
$customeraddress='';
$customercode='';
$customername='';
$customeribmcode='';
$customertin='';
$creditLimit=0;

$ytdCustSelPrice=0;
$ytdBasicDisc=0;
$ytdAddlDisc=0;
$ytdAddlDpp=0;	
$ytdiscGrSales=0;

$qMTDCFT=0;
$qMTDNCFT=0;
$qYTDCFT=0;
$qYTDNCFT=0;
$qNextLevelCFT=0;
$qNextLevelNCFT = 0;
$arOutstandingbalance = 0;
$employee='';
$branch='';

$rs_reason=$sp->spSelectReason($database, 4, '');

$rs=$tpi->selectSalesInvoiceByID($database, $txnid);
$rs_detailsall=$sp->spSelectSalesInvoiceDetailsByID($database, $txnid);

$rs_branch=$sp->spSelectBranch($database, -2, '');
if($rs_branch->num_rows) {

  while($row=$rs_branch->fetch_object()) $branch=$row->Name;  
}

$rsEmployee=$sp->spSelectEmployee($database, $_SESSION['emp_id'], '');
if($rsEmployee->num_rows) {

  while($row=$rsEmployee->fetch_object()) $employee=$row->Name;    
}

if(isset($txnid)) {

  $rsDateDiff=$sp->spSelectSalesInvoiceDateDiff($database, $txnid);
  if($rsDateDiff->num_rows) {
  
    while($row=$rsDateDiff->fetch_object()) $dateDiff=$row->datedif;  			  		
  }
}

// Retrieve latest BIR series.
$rs_birseries=$sp->spSelectBIRSeriesByTxnTypeID($database, 7);
if($rs_birseries->num_rows) {

  while($row=$rs_birseries->fetch_object()) {
  
    $bir_series=$row->Series;
    $bir_id=$row->NextID;			
  }		
} else {

  $rs_branch=$sp->spSelectBranch($database, -2, '');
  if($rs_branch->num_rows) {
  
    while($row=$rs_branch->fetch_object()) {
    
      $bir_series=$row->Code.'700000001';
      $bir_id=1;				
    }
  }
}

if($rs->num_rows) {

  while($row=$rs->fetch_object()) {
  
    $sino=$row->SINo;
    $id=$row->TxnID;
    $docno=$row->DocumentNo;
    $txndate=$row->SIDate;
    $txnDateFormat=$row->TxnDate;
    $tmpDate=strtotime($txnDateFormat);
    $year_si=date('Y', $tmpDate);
    $month_si=date('m', $tmpDate);
    $effectivitydate=$row->EffectivityDate;
    $status=$row->TxnStatus;
    $statid=$row->TxnStatusID;
    $remarks=$row->Remarks;
    $rsAppliedPromoEntitlements=$tpi->getAppliedPromoEntitlements($row->SOID);
    $rsAppliedIncentiveEntitlements=$tpi->getAppliedIncentiveEntitlements($row->SOID);    
    $refno=$row->RefNo;
    $termsduration=$row->TermsDuration;
    $drdate=$row->DRDate;
    $custId=$row->CustomerID;
    $v_gsu_type_id=$row->GSUTypeID;
    $custcode=$row->CustomerCode;
    $custname=$row->CustomerName;
    $terms=$row->Terms;
    $outstandingBalance=$row->OutstandingBalance;
    $totgrossamt=$row->GrossAmount;
    $basicdiscount=$row->BasicDiscount;
    $additionaldiscount=$row->AddtlDiscount;
    $prevadditionaldiscount=$row->AddtlDiscountPrev;
    $vatamt=$row->VatAmount;
    $totalnetamt=$row->NetAmount;
    $ibm=$row->IBM;
    $totalCFT=$row->TotalCFT;
    $totalNCFT=$row->TotalNCFT;
    $totalCPI=$row->TotalCPI;
    $ibmCode=$row->IBMCode;
    $customerType=$row->CustomerTypeID;
    $qMTDCFT=$row->MTDCFT;
    $qMTDNCFT=$row->MTDNCFT;
    $qYTDCFT=$row->YTDCFT;
    $qYTDNCFT=$row-> YTDNCFT;
    $qNextLevelCFT=$row->NextLevelCFT;
    $qNextLevelNCFT=$row->NextLevelNCFT;
        
    // For printing.
    $printCnt=$row->PrintCounter;
    $reftxnid=$row->RefTxnID;
    $reftxnid='SO'.str_pad($reftxnid, 8, "0", STR_PAD_LEFT);
    $salesinvoice   = "".str_pad( $docno, 8, "0", STR_PAD_LEFT);         
  }
}

$rsYTDCFT=$sp->spSelectYTDbyCustomerID($database, $custId, $year_si, 1);
if($rsYTDCFT->num_rows) {

  while($tmpYTDCFT=$rsYTDCFT->fetch_object()) $YTDCFT=$tmpYTDCFT->Amount;  
}

$rsMTDCFT=$sp->spSelectMTDbyCustomerID($database, $custId, $month, $year, 1);
if($rsMTDCFT->num_rows) {

  while($tmpMTDCFT=$rsMTDCFT->fetch_object()) {
  
    $MTDCFT=$tmpMTDCFT->Amount;
    $MTDCFTDisc=$tmpMTDCFT->DiscountID;    
  }
}

$rsMTDNCFT=$sp->spSelectMTDbyCustomerID($database, $custId, $month, $year, 2);
if($rsMTDNCFT->num_rows) {

  while($tmpMTDNCFT=$rsMTDNCFT->fetch_object()) {
  
    $MTDNCFT=$tmpMTDNCFT->Amount;
    $MTDNCTDisc=$tmpMTDNCFT->DiscountID;  
  }
}

$rsYTDNCFT=$sp->spSelectYTDbyCustomerID($database, $custId, $year_si, 2);
if($rsYTDNCFT->num_rows) {

  while($tmpYTDNCFT=$rsYTDNCFT->fetch_object()) $YTDNCFT=$tmpYTDNCFT->Amount;  
}

//FOR Printing #########################################################################
$rsUpcomingDues=$sp->spSelectSIUpcomingDues($database, $custId, $txnid); 
$rsBranch=$sp->spSelectBranch($database, -2, ''); 
if($rsBranch->num_rows) {

  while($row=$rsBranch->fetch_object()) {
  
    $permitno=$row->PermitNo;
    $branchname=$row->Name;
    $tinno=$row->TIN;
    $address=$row->StreetAdd;
    $serversn=$row->ServerSN;
    $branchcode=$row->Code;			
  }
}

$rsCustomer=$sp->spSelectCustomerDetails($database, $custId); 
if($rsCustomer->num_rows) {

  while($row=$rsCustomer->fetch_object()) {
  
    $customercode=$row->Code;
    $customername=$row->Name;
    $customeribmcode=$row->IBMNo;
    $customertin=$row->TIN;
  }
}

if($customercode=='') $customercode='N/A';

if($customername=='') $customername='N/A';

if($customeribmcode=='') $customeribmcode='N/A';	

if($customertin=='') $customertin='N/A';

if($customeraddress=='') $customeraddress='N/A';

$rsProdSI=$sp->spSelectProductSalesInvoice($database, $txnid, ''); 

$rsCreditLimit=$sp->spSelectCreditLimitByCustomerID($database, $custId);
if($rsCreditLimit->num_rows) {

  while($row=$rsCreditLimit->fetch_object()) $creditLimit=$row->ApprovedCL;  
}

$rsARBalance=$sp->spSelectARBalanceByCustomerID($database, $custId);
if($rsARBalance->num_rows) {

  while($ARBalance=$rsARBalance->fetch_object()) $arOutstandingbalance=$ARBalance->GrossAmount-$ARBalance->TotalPayment;  
}	

$availableCL=$creditLimit - $arOutstandingbalance;	
if($availableCL<0) $availableCL=0.00;	

$rsMTDSI=$sp->spSelectMTDSI($database, $custId, $month_si, $year_si); 
if($rsMTDSI->num_rows) {

  while($row=$rsMTDSI->fetch_object()) {
      
    $mtdCustSelPrice=$row->GrossAmount;
    $mtdBasicDisc=$row->BasicDiscount;
    $mtdAddlDisc=$row->AddtlDiscount;
    $mtdAddlDpp=$row->AddtlDiscountPrev;	
    $mtdiscGrSales=$mtdCustSelPrice-$mtdBasicDisc;
  }
}

$rsYTDSI=$sp->spSelectYTDSI($database, $custId, $year_si); 
if($rsYTDSI->num_rows) {

  while($row=$rsYTDSI->fetch_object()) {
    
    $ytdCustSelPrice=$row->GrossAmount;
    $ytdBasicDisc=$row->BasicDiscount;
    $ytdAddlDisc=$row->AddtlDiscount;
    $ytdAddlDpp=$row->AddtlDiscountPrev;	  
  }
}

$v_mtdcft=$MTDCFT;  
$v_mtdncft=$MTDNCFT;
//FOR Printing #########################################################################


if(isset($_POST['btnConfirm'])) {

  // Get total CFT and NCFT and add to existing sales for availment of ADPP
  // Get previous sales
  $existsMTDCFT=false;
  $existsMTDNCFT=false;   
  $MTDSalesCFT=0;
  $MTDSalesNCFT=0;
  $discountIdCFT=0;
  $discountIdNCFT=0;
  $tmpDate=strtotime($txnDateFormat);
  $month=date('m', $tmpDate);
  $year=date('Y', $tmpDate);
  $date=date('Y-m-d');
  $duration=$termsduration;	
  $MTDCFT=$_POST["hMTDCFT"];
  $MTDNCFT=$_POST["hMTDNCFT"];
  $YTDCFT=$_POST["hYTDCFT"];
  $YTDNCFT=$_POST["hYTDNCFT"];
  $NextLevelCFT=$_POST["hamountToNextDiscCFT"];
  $NextLevelNCFT=$_POST["hamountToNextDiscNCFT"];
  $remarks=$_POST['txtRemarks'];
  
  $test=1;
  
  do {
  
    $tmpduedate=strtotime(date('Y-m-d', strtotime($date)) .'+'.$termsduration.'day');     
    $dueday=date('D', $tmpduedate);
    $v_date=date('Y-m-d', $tmpduedate);
    $rscheckHoliday=$sp->spCheckIfHoliday($database, $v_date);
    $termsduration=$termsduration+1;
  } while($dueday=='Sun' || $rscheckHoliday->num_rows!=0);
  
  $termsduration=$termsduration-1;	
  
  try {
  
    $database->beginTransaction();
    
    // Check the status of the Inventory.
    $rs_freeze=$sp->spCheckInventoryStatus($database);
    if($rs_freeze->num_rows) {
    
      while($row=$rs_freeze->fetch_object()) $statusid_inv=$row->StatusID;			      		
    } else $statusid_inv=20;
        
    if($statusid_inv==21) $errmsg='Cannot save transaction, Inventory Count is in progress.';				    
    else {
    
      // Get and insert the specific dealer information necessary for "Sales Force Promotion" and "Sales Force Reversion" sub-module under "Sales Force Management" module.
      $rsDealerInfo=$tpi->getDealerInfoByID($database, $custId, '*', 'c.Name');
      if($rsDealerInfo->num_rows) {
        
        while($field=$rsDealerInfo->fetch_object()) {
          
          $tpi->updateDailySalesForMonthlySales($database, $field->IBMID, $field->IBMLevelID, $field->IBMCode, $field->CustomerID, $field->CustomerLevelID, $field->CustomerCode, $totalnetamt, date('My', $tmpDate), $txnid, $txnDateFormat, $_SESSION['emp_id']);
          
          if($field->CustomerStatusID=='3') {
          
            $rsRequiredProductKit=$sp->spSelectRequiredProductKit($database, $date);
        		if($rsRequiredProductKit->num_rows) {
            
        			while($requiredProductKitField=$rsRequiredProductKit->fetch_object()) {
                                    			               
                $rsCheckProdRequirement=$sp->spCheckProductKitRequirement($database, $custId, $requiredProductKitField->ProductID);              
        				if($rsCheckProdRequirement->num_rows) {
                
        					$rsCheckAmountRequirement=$sp->spSelectAccumalatedSIDealerApplicants($database, $custId);	
        					if($rsCheckAmountRequirement->num_rows) {
                  
        						while($amountDetails=$rsCheckAmountRequirement->fetch_object()) {
                    
        						  if($amountDetails->NetAmount>=500) {
                        
                        $sp->spInsertCustomerAppointedStatus($database, $custId, $_SESSION['emp_id']);                        
                        $tpi->updateIBMNumberOfRecruits($database, $field->IBMID, $custId, $txnid, $txnDateFormat, $_SESSION['emp_id']);
                      }         							
        						}        						
        					}        					
        				}                                                           
        			}	
        		}
          }
        }
      }
    
      $rsMTDSalesCFT=$sp->spSelectMTDbyCustomerID($database, $custId, $month, $year, 1);			
      if($rsMTDSalesCFT->num_rows) {
      
        while($row=$rsMTDSalesCFT->fetch_object()) {
        
          $MTDSalesCFT=$row->Amount;
          $existsMTDCFT=true;
          $MTDCFTId=$row->ID;
          break;
        }
      }
      
      $rsMTDSalesNCFT=$sp->spSelectMTDbyCustomerID($database, $custId, $month, $year, 2);      
      if($rsMTDSalesNCFT->num_rows) {
      
        while($row=$rsMTDSalesNCFT->fetch_object()) {
        
          $MTDSalesNCFT=$row->Amount;
          $existsMTDNCFT=true;
          $MTDNCFTId=$row->ID;
          break;
        }
      }            
      
      $totalCFTMTD=$totalCFT+$MTDSalesCFT;
      $totalNCFTMTD=$totalNCFT+$MTDSalesNCFT;
      
      // Get discount bracket CFT   
      if($totalCFTMTD!=0) {
      
        $rsDiscountCFT=$sp->spSelectDiscountBracketbyAmount($database, $totalCFTMTD, 1);	   
        if($rsDiscountCFT->num_rows) {
        
          while($row=$rsDiscountCFT->fetch_object()) $discountIdCFT=$row->ID;             
        }
      }
      
      // Get discount bracket NCFT
      if($totalNCFTMTD!=0) {
      
        $rsDiscountNCFT=$sp->spSelectDiscountBracketbyAmount($database, $totalNCFTMTD, 2);	
        if($rsDiscountNCFT->num_rows) {
        
          while($row=$rsDiscountNCFT->fetch_object()) $discountIdNCFT=$row->ID;          
        }
      }
      
      // Perform updates to cumulative sales
      //if entry is existing (CFT)
      if($totalCFT>0) {
      
        if($existsMTDCFT) {
        
          // Update cumulative CFT MTD amount.
          $rsUpdate=$sp->spUpdateCumulativeSales($database, $totalCFT, $discountIdCFT, $MTDCFTId);
          if(!$rsUpdate) {
          
            throw new Exception("An error occurred, please contact your system administrator.");
          }
        } else {
        
          // Insert new cumulative amount entry.
          $rsInsert=$sp->spInsertCumulativeSales($database, $custId, $month, $year, $discountIdCFT, $totalCFT, 1);
          if(!$rsInsert) {
          
            throw new Exception("An error occurred, please contact your system administrator.");
          }
        }
      }
      
      // Perform updates to cumulative sales.
      // if entry is existing (NCFT)      
      if($totalNCFT>0) {
      
        if($existsMTDNCFT) {
        
          // Update cumulative CFT MTD amount.          
          $rsUpdate=$sp->spUpdateCumulativeSales($database, $totalNCFT, $discountIdNCFT, $MTDNCFTId);						
          if(!$rsUpdate) {
          
            throw new Exception('An error occurred, please contact your system administrator.');
          }
        } else  {
              
          // Insert new cumulative amount entry.
          $rsInsert=$sp->spInsertCumulativeSales($database, $custId, $month, $year, $discountIdNCFT, $totalNCFT, 2);
          if(!$rsInsert) {
          
            throw new Exception('An error occurred, please contact your system administrator.');
          }
        }   
      }
      
      // Create new AR  
      $insertAR=$sp->spInsertAccountReceivable($database, $custId, $txnid, $outstandingBalance);
      if(!$insertAR) {
      
        throw new Exception('An error occurred, please contact your system administrator.');
      }
            
      if($rs_detailsall->num_rows) {
      
        while($row=$rs_detailsall->fetch_object()) {
        
          $servedQty=$row->ServedQty;
          
          $rsGetInv=$sp->spSelectAvailableInventoryForSalesInvoice($database, $row->ProductID, 1);
          if($rsGetInv->num_rows) {
          
            while($row_inv=$rsGetInv->fetch_object()) {
            
              $soh=$row_inv->SOH;
              if($soh>=$servedQty) {
              
                if($row->UnitPrice>0) 
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $row->ProductID, 0, $txnid, $docno, $remarks, 13, $servedQty, date('Y-m-d H:i:s', strtotime($txndate)));				 						
                else
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $row->ProductID, 0, $txnid, $docno, $remarks, 13, $servedQty, date('Y-m-d H:i:s', strtotime($txndate)));
                
                break;
              } elseif($soh<$servedQty) {
              
                $qty=$servedQty-$soh;
                if($row->UnitPrice>0)
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $row->ProductID, 0, $txnid, $docno, $remarks, 13, $qty,  date('Y-m-d H:i:s', strtotime($txndate)));						  				                
                else                
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $row->ProductID, 0, $txnid, $docno, $remarks, 13, $qty, date('Y-m-d H:i:s', strtotime($txndate)));                
              }
            }
          }
        }
      }
      
      // Promo Entitlements start here...
      if($rsAppliedPromoEntitlements->num_rows) {
        
        $applied_promos_ids=array();
        
        while($field=$rsAppliedPromoEntitlements->fetch_object()) {
          
          if($field->PromoType=='Single Line' && $field->PromoEntitlementCriteria=='Price') continue;
          
          $servedQty=$field->Quantity;
          
          $rsGetInv=$sp->spSelectAvailableInventoryForSalesInvoice($database, $field->ItemID, 1);
          if($rsGetInv->num_rows) {
          
            while($row_inv=$rsGetInv->fetch_object()) {
            
              $soh=$row_inv->SOH;
              if($soh>=$servedQty) {
              
                if($field->UnitPrice>0) 
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $field->ItemID, 0, $txnid, $docno, $remarks, 13, $servedQty, date('Y-m-d H:i:s', strtotime($txndate)));				 						
                else
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $field->ItemID, 0, $txnid, $docno, $remarks, 13, $servedQty, date('Y-m-d H:i:s', strtotime($txndate)));
                
                break;
              } elseif($soh<$servedQty) {
              
                $qty=$servedQty-$soh;
                if($field->UnitPrice>0)
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $field->ItemID, 0, $txnid, $docno, $remarks, 13, $qty,  date('Y-m-d H:i:s', strtotime($txndate)));						  				                
                else                
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $field->ItemID, 0, $txnid, $docno, $remarks, 13, $qty, date('Y-m-d H:i:s', strtotime($txndate)));                
              }
            }
          }
          
          if(!in_array($field->PromoID, $applied_promos_ids)) { 
            
            $tpi->insertAvailedApplicablePromoByCustomer($custId, $v_gsu_type_id, $field->PromoID);
            $applied_promos_ids[]=$field->PromoID;
          }   
        }
      }
      // Promo Entitlements end here...
      
      // Incentive Entitlements start here...
      if($rsAppliedIncentiveEntitlements->num_rows) {
        
        $applied_incentives_ids=array();
        
        while($field=$rsAppliedIncentiveEntitlements->fetch_object()) {                    
          
          $servedQty=$field->Quantity;
          
          $rsGetInv=$sp->spSelectAvailableInventoryForSalesInvoice($database, $field->ItemID, 1);
          if($rsGetInv->num_rows) {
          
            while($row_inv=$rsGetInv->fetch_object()) {
            
              $soh=$row_inv->SOH;
              if($soh>=$servedQty) {
              
                if($field->UnitPrice>0) 
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $field->ItemID, 0, $txnid, $docno, $remarks, 13, $servedQty, date('Y-m-d H:i:s', strtotime($txndate)));				 						
                else
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $field->ItemID, 0, $txnid, $docno, $remarks, 13, $servedQty, date('Y-m-d H:i:s', strtotime($txndate)));
                
                break;
              } elseif($soh<$servedQty) {
              
                $qty=$servedQty-$soh;
                if($field->UnitPrice>0)
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $field->ItemID, 0, $txnid, $docno, $remarks, 13, $qty,  date('Y-m-d H:i:s', strtotime($txndate)));						  				                
                else                
                $rs_stocklog=$stocklog->AddToStockLog(1, $row_inv->InventoryID, 1, '', '', '', $field->ItemID, 0, $txnid, $docno, $remarks, 13, $qty, date('Y-m-d H:i:s', strtotime($txndate)));                
              }
            }
          }
                    
          if(!in_array($field->IncentiveID, $applied_incentives_ids)) { 
            
            $tpi->insertAvailedApplicableIncentiveByCustomer($custId, $v_gsu_type_id, $field->IncentiveID);
            $applied_incentives_ids[]=$field->IncentiveID;
          }             
        }
      }
      // Incentive Entitlements end here...      
      
      $rsUpdateInvoiceStatus=$sp->spUpdateConfirmSalesInvoice($database, $txnid, $session->emp_id, $bir_series, $remarks);
            
      $rsUpdateInvoiceEffectivity=$sp->spUpdateEffectivityDateSI($database, $txnid, $termsduration);
      if(!$rsUpdateInvoiceEffectivity) {
       
        throw new Exception('An error occurred, please contact your system administrator');
      }
            
      // Update MTD and YTD. 
      $rsUpdateMTD=$sp->spInsertMTDBySI($database, $MTDCFT, $MTDNCFT, $YTDCFT, $YTDNCFT, $NextLevelCFT, $NextLevelNCFT, $txnid);
      if(!$rsUpdateMTD) {
       
        throw new Exception('An error occurred, please contact your system administrator');
      }
      
      // Update BIR series.
      $rs_update=$sp->spUpdateBIRSeriesByBranchID($database, $bir_id, 7);
      if(!$rs_update) {
       
        throw new Exception('An error occurred, please contact your system administrator');
      }
      
      $database->commitTransaction();
      
      // Unlock transaction.
      try {
      	
        $database->beginTransaction();
        
        $updatestatus=$sp->spUpdateLockStatus($database, $table, 0, 0, $txnid);
        if(!$updatestatus) {
        
          throw new Exception('An error occurred, please contact your system administrator.');
        } $database->commitTransaction();			
      } catch(Exception $e) {
      
        $database->rollbackTransaction();	
        $errmsg=$e->getMessage()."\n";
        redirect_to('index.php?pageid=40.1&msg='.$errmsg.'&txnid='.$txnid);	
      }
      
      $message='Sales Invoice has been successfully confirmed.';      
      redirect_to('index.php?pageid=34&adv=0');				
    }  
  } catch(Exception $e) {
  
    $database->rollbackTransaction();	
    $errmsg=$e->getMessage()."\n";	
  }
} else if(isset($_POST['btnCancel'])) {

  // Unlock transaction.
  try {
  
    $database->beginTransaction();
    
    $updatestatus=$sp->spUpdateLockStatus($database, $table, 0, 0, $txnid);
    if(!$updatestatus) {
    
      throw new Exception('An error occurred, please contact your system administrator.');
    } $database->commitTransaction();			
  } catch(Exception $e) {
  
    $database->rollbackTransaction();	
    $errmsg=$e->getMessage()."\n";
    redirect_to('index.php?pageid=40.1&msg='.$errmsg.'&txnid='.$txnid);	
  }
  
  redirect_to('index.php?pageid=40');		
}

if(isset($_POST['btnCopy'])) {

  echo 'here';
  
  $sessionID=session_id();
  $tmpID=0;
  
  $rsInsertCopySO=$sp->spInsertCopyToSOHeader($database, $custId, $sessionID);
  if($rsInsertCopySO->num_rows) {
  
    while($tmpSOHeader=$rsInsertCopySO->fetch_object()) {
    
      $tmpID=$tmpSOHeader->headerID;
      if($rs_detailsall->num_rows) {
      
        while($siDetails=$rs_detailsall->fetch_object()) {
        
          $productID=$siDetails->ProductID;
          $qty=$siDetails->OrderedQty;
          $rsInsertTmpSODetails=$sp->spInsertCopyToSODetails($database, $tmpID, $productID, $qty);						
        }
      }        
    }
  } redirect_to('index.php?pageid=34.1&TxnID='.$tmpID);
}

$locked=0;
if(isset($_GET['locked'])) $locked=1;

if($locked==0) {
    
  $checkiflocked=$sp->spCheckIfTransactionIsLocked($database, $table, $txnid);
  if(!$checkiflocked) {
  
    $errmsg='An error occurred, please contact your system administrator.';
    redirect_to('index.php?pageid=40&msg='.$errmsg);
  } else {
  
    if($checkiflocked->num_rows) {
    
      while($row=$checkiflocked->fetch_object()) {
      
        $islocked=$row->IsLocked;
        $lockedby=$row->FirstName.' '.$row->LastName;					
      }				
    }
    
    if($islocked==1) {
    
      $errmsg='Selected transaction is locked by '.$lockedby;
      redirect_to('index.php?pageid=40&msg='.$errmsg);
    } else {
    
      $updatestatus=$sp->spUpdateLockStatus($database, $table, 1, $session->emp_id, $txnid);
      if(!$updatestatus) {
      
        $errmsg='An error occurred, please contact your system administrator.';
        redirect_to('index.php?pageid=40&msg='.$errmsg);
      }				
    }			
  }        
}