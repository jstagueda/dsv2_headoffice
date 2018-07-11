<?php
/*   
  @modified by John Paul Pineda.
  @date November 13, 2012.
  @email paulpineda19@yahoo.com         
*/

ini_set('display_errors', '1');

class CancelSI {
	
	private $database;
	private $SIID;
	private $customerID;
  private $cancelledSIID;
	private $cancelledtotalcft;
	private $cancelledtotalncft;
	private $cancellednetamount;	
	
	// Main Method.
	function CancelSalesInvoice($database, $SIID, $customerID) {      
    
		$this->database=$database;		    
    
		// Get the details of the SI being cancelled. 
		$cancelledSIDetails=$this->GetCancelledSalesInvoiceDetails($SIID);
    
		#$getSucceedingSI=$this->GetSucceedingSalesInvoice($SIID, $customerID);	
    
    $month=date('m');
    $year=date("Y");
    $this->AdjustMTDbyCustomer($database, $customerID, $month, $year);           				            										
    
		$updateAR=$this->spUpdateARSICancel($SIID);
		$updateOutstandingAmt=$this->spUpdateSICancelled($SIID);
    
    $this->ReApplyOR($SIID, $customerID);		
	}
	
	function GetCancelledSalesInvoiceDetails($SIID) {	

		$rsSIDetails=$this->spSelectSIbyIDSICancellation($SIID);
		while($row=$rsSIDetails->fetch_object()) {
    
      $this->cancelledSIID=$SIID;
			$this->cancelledtotalcft=$row->TotalCFT;
			$this->cancelledtotalncft=$row->TotalNCFT;
			$this->cancellednetamount=$row->NetAmount;
		}								
	}
  
  function AdjustMTDbyCustomer($database, $customerID, $mtd_month, $mtd_year) {
    
    global $sp;
    
    // Get the new CFT MTD.
    $rsMTDCFT=$sp->spSelectMTDbyCustomerID($database, $customerID, $mtd_month, $mtd_year, 1);
    if($rsMTDCFT->num_rows) {
    
      while($tmpMTDCFT=$rsMTDCFT->fetch_object()) $adjustedmtdCFT=$tmpMTDCFT->Amount-$this->cancelledtotalcft;    	                      
    }
    
    // Get the new NCFT MTD.
    $rsMTDNCFT=$sp->spSelectMTDbyCustomerID($database, $customerID, $mtd_month, $mtd_year, 2);				
    if($rsMTDNCFT->num_rows) {
    
      while($tmpMTDNCFT=$rsMTDNCFT->fetch_object()) $adjustedmtdNCFT=$tmpMTDNCFT->Amount-$this->cancelledtotalncft;               
    }
    
    $rsDiscountBracket=$this->spSelectDiscountBracket();    
    while($row=$rsDiscountBracket->fetch_object()) {
    
      $pmgID=$row->PMGID;
      $minimumamount=$row->Minimum;
      $maximumamount=$row->Maximum;
      
      if($pmgID==1 && $adjustedmtdCFT>=$minimumamount && $adjustedmtdCFT<=$maximumamount) $newDiscCFT=$row->ID;								
      
      if($pmgID==2 && $adjustedmtdNCFT>=$minimumamount && $adjustedmtdNCFT<=$maximumamount) $newDiscNCFT=$row->ID;											
    }        		
    
    if($newDiscCFT!=0) $updateMTDCFT=$this->spUpdateMTD(1, $mtd_month, $this->cancelledtotalcft, $newDiscCFT, $customerID);
    
    if($newDiscNCFT!=0) $updateMTDNCFT=$this->spUpdateMTD(2, $mtd_month, $this->cancelledtotalncft, $newDiscNCFT, $customerID);
  }
	
  function GetSucceedingSalesInvoice($SIID, $customerID) {
  
    $rsGetSucceedingSalesInvoice=$this->selectSalesInvoiceForAdjustment($SIID, $customerID);    		
    while($row=$rsGetSucceedingSalesInvoice->fetch_object()) {
   
      $nextSIID=$row->ID;
      $sgrossAmount=$row->GrossAmount;
      $stotalCFT=$row->TotalCFT;
      $stotalNCFT=$row->TotalNCFT;
      $smtdCFT=$row->MTDCFT;
      $smtdNCFT=$row->MTDNCFT;
      $sAddtlDiscount=$row->AddtlDiscount;
      $svatAmount=$row->VatAmount;
      $snextlevelcft=$row->NextLevelCFT;
      $snextlevelncft=$row->NextLevelNCFT;
      $sADPP=$row->AddtlDiscountPrev;
      $sNetAmount=$row->NetAmount;
      $sOutstandingBalance=$row->OutstandingBalance;      				      
      
      $recalculate=$this->ReCalculateSalesInvoice($nextSIID, $sgrossAmount, $stotalCFT, $stotalNCFT, $smtdCFT, $smtdNCFT, $snextlevelcft, $snextlevelncft, $customerID);
      
      $tmppayment=$sNetAmount-$sOutstandingBalance;
      $updateAR=$this->spUpdateARSIAdjusted($tmppayment, $nextSIID);    
    }  
  }
  
  function ReCalculateSalesInvoice($nextSIID, $v_grossAmount, $v_totalCFT, $v_totalNCFT, $v_mtdCFT, $v_mtdNCFT, $v_nextlevelcft, $v_nextlevelncft, $customerID) {
 
		$newDiscCFT=0;
		$newDiscNCFT=0;
		$ADPPCFT=0;
		$ADPPNCFT=0;
		$amountToNextDiscCFT=0;
		$amountToNextDiscNCFT=0;
		$discountNCFT=0;
		$discountCFT=0;
		$grossAmount=$v_grossAmount;
		$txnID=$nextSIID;
    
		// Get the new MTD
		$adjustedmtdCFT=$v_mtdCFT-$this->cancelledtotalcft;
		$adjustedmtdNCFT=$v_mtdNCFT-$this->cancelledtotalncft;						
    
		// Re-compute the Basic Discount. 
		$grossCFT=$v_totalCFT/0.75;
		$grossNCFT=$v_totalNCFT/0.75;			 				
		$basicDiscountCFT=$grossCFT*0.25;
		$basicDiscountNCFT=$grossNCFT*0.25;		
		$totalBasicDiscount=$basicDiscountCFT+$basicDiscountNCFT;

		// Re-compute for the Additional Discount. 
		$rsDiscountBracket=$this->spSelectDiscountBracket();    
		while($row=$rsDiscountBracket->fetch_object()) {
    
			$pmgID=$row->PMGID;
			$minimumamount=$row->Minimum;
			$maximumamount=$row->Maximum;
      		
			if($pmgID==1 && $adjustedmtdCFT>=$minimumamount && $adjustedmtdCFT<=$maximumamount) {
      
				$discount=$row->Discount;			
				$newDiscCFT=$row->ID;
				$discountCFT=$v_totalCFT*($discount/100);				
			}
      
			if($pmgID==2 && $adjustedmtdNCFT>=$minimumamount && $adjustedmtdNCFT<=$maximumamount) {
      
				$discount=$row->Discount;				
				$newDiscNCFT=$row->ID;
				$discountNCFT=$v_totalNCFT*($discount/100);				
			}
		}
		    				
		// Re-compute the ADPP.	
		$rsDiscountADPP=$this->spSelectDiscountBracket();	
		if($rsDiscountADPP->num_rows) {
    
		  while($ADPPDiscountBracket=$rsDiscountADPP->fetch_object()) {
      
        $minimum=$ADPPDiscountBracket->Minimum;
        $maximum=$ADPPDiscountBracket->Maximum;
        $PMGID=$ADPPDiscountBracket->PMGID;
        	
        if($ADPPDiscountBracket->PMGID==1) {
        				          
          if($adjustedmtdCFT>=$minimum && $adjustedmtdCFT<=$maximum && $PMGID==1) {          				
            
            $discID=$ADPPDiscountBracket->ID;
            $disc=$ADPPDiscountBracket->Discount;
            
            $rsDiscountMTD=$this->selectPreviousMTDPercentageBracket($this->cancelledSIID);                        				                                                    	                         
            if($rsDiscountMTD->num_rows) {
            
              while($MTDDisc=$rsDiscountMTD->fetch_object()) {
              
                $tmpDiscMTD=$MTDDisc->Discount;	
                $DiscMTD=$disc-$tmpDiscMTD;								
                $ADPPCFT=($adjustedmtdCFT/1.12)*($DiscMTD/100);                
              }
            }                                  
          }								        
        } else {

          if($adjustedmtdNCFT>=$minimum && $adjustedmtdNCFT<=$maximum && $PMGID==2) {          
          
            $discID=$ADPPDiscountBracket->ID;	
            $disc=$ADPPDiscountBracket->Discount;                        
                                        	
            $rsDiscountMTD=$this->selectPreviousMTDPercentageBracket($this->cancelledSIID);              
            if($rsDiscountMTD->num_rows) {
            
              while($MTDDisc=$rsDiscountMTD->fetch_object()) {
              
                $tmpDiscMTD=$MTDDisc->Discount;	
                $DiscMTD=$disc-$tmpDiscMTD;
                $ADPPNCFT=($adjustedmtdNCFT/1.12)*($DiscMTD/100);                
              }
            }                                  
          }
        }												
			}
		}
    
		// Amount to Next Discount.
		$rsGetDiscounts=$this->spSelectDiscountBracket();		
		if($rsGetDiscounts->num_rows) {
		
      while($DiscountBrackets=$rsGetDiscounts->fetch_object()) {
      
        $minimum=$DiscountBrackets->Minimum;
        $maximum=$DiscountBrackets->Maximum;
        $PMGID=$DiscountBrackets->PMGID;
        
        if($DiscountBrackets->PMGID==1) {                  
          
          if($adjustedmtdCFT>=$minimum && $adjustedmtdCFT<=$maximum && $PMGID==1) {
          
            $discID=$DiscountBrackets->Discount;	
            
            $rsNextDiscount=$this->spSelectNextDiscBracket($discID, 1);
            if($rsNextDiscount->num_rows) {
            
              while($nextDisc=$rsNextDiscount->fetch_object()) {
              
                $tmpNextMinimum=$nextDisc->Minimum;
                $amountToNextDiscCFT=$tmpNextMinimum-$adjustedmtdCFT;              
              }
            }          
          }        
        } else {                  
          
          if($adjustedmtdNCFT>=$minimum && $adjustedmtdNCFT<=$maximum &&  $PMGID==2) {
          
            $discID=$DiscountBrackets->Discount;
                        						
            $rsNextDiscount=$this->spSelectNextDiscBracket($discID, 2);            
            if($rsNextDiscount->num_rows) {
            
              while($nextDisc=$rsNextDiscount->fetch_object()) {
              
                $tmpNextMinimum=$nextDisc->Minimum;
                $amountToNextDiscNCFT=$tmpNextMinimum-$adjustedmtdNCFT;                            
              }
            }          
          }
        }				
      }
		}
			
		$tmpADPPDisc=$ADPPCFT+$ADPPNCFT;				
		$ADPP=number_format($tmpADPPDisc, 2, '.', '');
    	
		$grossLessBasic=$grossAmount-$totalBasicDiscount;		
		$adjustedAddtlDisc=$discountNCFT+$discountCFT;
		$saleswitVAT=$grossLessBasic-$adjustedAddtlDisc;
		
		$vatableSales=$saleswitVAT/1.12;
		$vatAmount=$vatableSales*0.12;
		$newnetAmount=$saleswitVAT-$ADPP; 						
		
		$updatesalesinvoice=$this->spUpdateSIAdjustment($txnID, $totalBasicDiscount, $adjustedmtdCFT, $adjustedmtdNCFT, $amountToNextDiscCFT, $amountToNextDiscNCFT, $adjustedAddtlDisc, $vatAmount, $ADPP, $newnetAmount);
    
		$month=date('m');
     
		$cancelledCFT=$this->cancelledtotalcft;
		$cancelledNCFT=$this->cancelledtotalncft;
		
		if($newDiscCFT!=0) $updateMTDCFT=$this->spUpdateMTD(1, $month, $cancelledCFT, $newDiscCFT, $customerID);
		
		if($newDiscNCFT!=0) $updateMTDNCFT=$this->spUpdateMTD(2, $month, $cancelledNCFT, $newDiscNCFT, $customerID);					
	}
	
	function ReApplyOR($SIID, $customerID) {
  
    $checkifpaid=$this->spCheckIfCancelledSIPaid($SIID);
    
    if($checkifpaid->num_rows) {
    
      while($paymentdetails=$checkifpaid->fetch_object()) {
      
        $totalpayment=$paymentdetails->TotalAmount;
        $ORID=$paymentdetails->OfficialReceiptID;
        
        $rsGetSucceedingSalesInvoice=$this->selectSalesInvoiceForAdjustment($SIID, $customerID);	
        if($rsGetSucceedingSalesInvoice->num_rows) {
        	
          $runningbalance=$totalpayment;
          
          while($row=$rsGetSucceedingSalesInvoice->fetch_object()) {
                    
            if($row->OutstandingBalance!=0) {
            
              if($runningbalance>$row->OutstandingBalance) {
              
                $runningbalance=$totalpayment-$row->OutstandingBalance;
                $rsInsertNewORDetails=$this->spInsertORDetailsSICancellation($ORID, $row->ID, $row->OutstandingBalance, 0, 1);
                $updateOR=$this->spUpdateARSIAdjusted($row->OutstandingBalance, $row->ID);
              } else {
              
                $outstandingAmount=$row->OutstandingBalance-$totalpayment;
                $runningbalance=0;
                $rsInsertNewORDetails=$this->spInsertORDetailsSICancellation($ORID, $row->ID, $totalpayment, $outstandingAmount, 1);
                $updateOR=$this->spUpdateARSIAdjusted($totalpayment, $row->ID);
              }
            }          
          }
          
          // Update the OR OutstandingAmount.
          if($runningbalance!=0) $rsUpdateOR=$this->spUpdateORSICancellation($ORID, $runningbalance);          					
        }
      }    
    }		
	}
	
  // Stored Procedures.
  function selectSalesInvoiceForAdjustment($v_txnID, $v_customerID) {
  	
    $query="CALL tpiSelectSalesInvoiceForAdjustment($v_txnID, $v_customerID);";			
    $rs=$this->database->execute($query);
    return $rs;		
	}
  
  function selectPreviousMTDPercentageBracket($v_TxnID, $v_CustomerID, $v_PMGID) {
    
    $query="CALL tpiSelectPreviousMTDPercentageBracket($v_TxnID, $v_CustomerID, $v_PMGID);";
    $rs=$this->database->execute($query);
    return $rs;
  }
  				
	function spSelectSIbyIDSICancellation($v_txnID) {
  
	 $query="CALL spSelectSIbyIDSICancellation($v_txnID );";			
	 $rs=$this->database->execute($query);
	 return $rs;	
	}
  	
	function spSelectDiscountBracket() {
  		
    $query="CALL spSelectDiscountBracket();";									
    $rs=$this->database->execute($query);
    return $rs;
	}
  
	function spSelectDiscountBracketbyID($v_ID) {
  		
  	$query="CALL spSelectDiscountBracketbyID($v_ID);";						
  	$rs=$this->database->execute($query);
  	return $rs;
	}
  
	function spInsertSIAdjustment($v_ID) {
  		
    $query="CALL spInsertSIAdjustment($v_ID);";						
    $rs=$this->database->execute($query);
    return $rs;
	}
  
	function spSelectNextDiscBracket($v_ID,$v_pmgID) {
  
    $query="CALL spSelectNextDiscBracket($v_ID, $v_pmgID);"; 		
    $rs=$this->database->execute($query);
    return $rs;
  }
  
	function spUpdateMTD($v_pmgID, $v_month, $v_amount, $v_discountID, $v_customerID) {
  
    $query="CALL spUpdateMTD($v_pmgID, $v_month, $v_amount, $v_discountID, $v_customerID);"; 	   			
    $rs=$this->database->execute($query);
    return $rs;
  }
  
  function spUpdateARSICancel($v_txnID) {
  
    $query="CALL spUpdateARSICancel($v_txnID);";   			
    $rs=$this->database->execute($query);
    return $rs;
  }
  
  function spUpdateSICancelled($v_txnID) {
  
    $query="CALL spUpdateSICancelled($v_txnID);";   			
    $rs=$this->database->execute($query);
    return $rs;
  }
  
  function spUpdateARSIAdjusted($v_payment,$v_txnID) {
  
    $query="CALL spUpdateARSIAdjusted($v_payment, $v_txnID);";   		
    $rs=$this->database->execute($query);
    return $rs;
  }
  
  function spCheckIfCancelledSIPaid($v_txnID) {
  
    $query="CALL spCheckIfCancelledSIPaid($v_txnID);";   		
    $rs=$this->database->execute($query);
    return $rs;
  }
  
  function spUpdateSIAdjustment($v_txnID, $v_basicdisc, $v_mtdcft, $v_mtdncft, $v_nextlevelcft, $v_nextlevelncft, $v_addtldisc, $v_vatamount, $v_adpp, $v_netamount) {
  
    $query="CALL spUpdateSIAdjustment($v_txnID, $v_basicdisc, $v_mtdcft, $v_mtdncft, $v_nextlevelcft, $v_nextlevelncft, $v_addtldisc, $v_vatamount, $v_adpp, $v_netamount);"; 		
    $rs=$this->database->execute($query);
    return $rs;
  }
  
  function spInsertORDetailsSICancellation($v_ORID, $v_SIID, $v_amount, $v_outstandingbalance, $v_employeeID) {
  
    $query="CALL spInsertORDetailsSICancellation($v_ORID, $v_SIID, $v_amount, $v_outstandingbalance, $v_employeeID);"; 		
    $rs=$this->database->execute($query);
    return $rs;
  }
  
  function spUpdateORSICancellation($v_ORID, $v_amount) {
  
    $query="CALL spUpdateORSICancellation($v_ORID, $v_amount);"; 		
    $rs=$this->database->execute($query);
    return $rs;
  }   	
}