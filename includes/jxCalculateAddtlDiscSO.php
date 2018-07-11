<?php
/*   
  @modified by John Paul Pineda.
  @date November 13, 2012.
  @email paulpineda19@yahoo.com         
*/

header("Expires: Mon, 20 Dec 1998 01:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");

require_once('../initialize.php');

global $database;

$totalCFT=str_replace(',', '', $_POST['totCFT']); 
$totalNCFT=str_replace(',', '', $_POST['totNCFT']); 
$custID=$_POST['hCOA'];
$discountCFT=0;
$discountNCFT=0;
$addtldisc=0;
$tmpaddtldisc=0;
$disc=0;
$month=date("m"); 
$year=date("Y");
$MTDCFT=0;
$MTDNCFT=0;
$MTDCFTDisc=0;
$MTDNCFTDisc=0;
$tmptotalCFT=0;
$tmptotalNCFT=0;
$ADPPNCFT=0;
$ADPPCFT=0;	
$amountToNextDiscCFT=0;
$amountToNextDiscNCFT=0;
$currentdiscCFT=0;
$currentdiscNCFT=0;

// Additional Discount.
$rsDiscountCFT=$sp->spSelectDiscountBracket($database);

$rsMTDCFT=$sp->spSelectMTDbyCustomerID($database, $custID, $month, $year, 1);
if($rsMTDCFT->num_rows) {

  while($tmpMTDCFT=$rsMTDCFT->fetch_object()) {
  
    $MTDCFT=$tmpMTDCFT->Amount;
    $MTDCFTDisc=$tmpMTDCFT->DiscountID;
  }
}

$rsMTDNCFT=$sp->spSelectMTDbyCustomerID($database, $custID, $month, $year, 2);				
if($rsMTDNCFT->num_rows) {

  while($tmpMTDNCFT=$rsMTDNCFT->fetch_object()) {
  
    $MTDNCFT=$tmpMTDNCFT->Amount;
    $MTDNCFTDisc=$tmpMTDNCFT->DiscountID;  
  }
}

if($rsDiscountCFT->num_rows) {

  while($CFTbracket=$rsDiscountCFT->fetch_object()) {
  
    $minimum=$CFTbracket->Minimum;
    $maximum=$CFTbracket->Maximum;	
    $PMGID=$CFTbracket->PMGID;
    		
    if($CFTbracket->PMGID==1) {
    
      $tmptotalCFT=$totalCFT+$MTDCFT;
      
      if($tmptotalCFT>=$minimum && $tmptotalCFT<=$maximum && $PMGID==1) {
              
        $disc=$CFTbracket->Discount;	
        $discountCFT=$totalCFT*($disc/100);	
        $currentdiscCFT=0;	    
      }								
    } else {
      
      $tmptotalNCFT=$totalNCFT+$MTDNCFT;
      
      if($tmptotalNCFT>=$minimum && $tmptotalNCFT<=$maximum && $PMGID==2) {
      					
        $disc=$CFTbracket->Discount;	
        $discountNCFT=$totalNCFT*($disc/100);	
        $currentdiscNCFT=$disc;            
      }
    }    
  }
  		
  $tmpaddtldisc=$discountCFT+$discountNCFT;				
  $addtldisc=number_format($tmpaddtldisc, 2, '.', '');			
}

// ADPP	
$rsDiscountADPP=$sp->spSelectDiscountBracket($database);
if($rsDiscountADPP->num_rows) {

  while($ADPPDiscountBracket=$rsDiscountADPP->fetch_object()) {
  
    $minimum=$ADPPDiscountBracket->Minimum;
    $maximum=$ADPPDiscountBracket->Maximum;
    $PMGID=$ADPPDiscountBracket->PMGID;
    	
    if($ADPPDiscountBracket->PMGID==1) {
    
      $tmptotalCFT=$totalCFT+$MTDCFT;
      
      if($tmptotalCFT>=$minimum && $tmptotalCFT<=$maximum && $PMGID==1) {
      				      
        $discID=$ADPPDiscountBracket->ID;
        					
        if($MTDCFTDisc!=$discID) {
        
          $disc=$ADPPDiscountBracket->Discount;
          	
          $rsDiscountMTD=$sp->spSelectDiscountBracketbyID($database, $MTDCFTDisc);
          if($rsDiscountMTD->num_rows) {
          
            while($MTDDisc=$rsDiscountMTD->fetch_object()) {
            
              $tmpDiscMTD=$MTDDisc->Discount;	
              $DiscMTD=$disc-$tmpDiscMTD;								
              $ADPPCFT=($MTDCFT/1.12)*($DiscMTD/100);              
            }
          }        
        }      
      }								    
    } else {
    
      $tmptotalNCFT=$totalNCFT+$MTDNCFT;		
      
      if($tmptotalNCFT>=$minimum && $tmptotalNCFT<=$maximum && $PMGID==2) {      
      
        $discID=$ADPPDiscountBracket->ID;	
        
        if($MTDNCFTDisc!=$discID) {
        
          $disc=$ADPPDiscountBracket->Discount;	
          $rsDiscountMTD=$sp->spSelectDiscountBracketbyID($database, $MTDNCFTDisc);
          
          if($rsDiscountMTD->num_rows) {
          
            while($MTDDisc=$rsDiscountMTD->fetch_object()) {
            
              $tmpDiscMTD=$MTDDisc->Discount;	
              $DiscMTD=$disc-$tmpDiscMTD;
              $ADPPNCFT=($MTDNCFT/1.12)*($DiscMTD/100);            
            }
          }        
        }      
      }
    }      
  }
  
  $tmpADPPDisc=$ADPPCFT+$ADPPNCFT;  				
  $ADPP=number_format($tmpADPPDisc, 2, '.', '');			
  
  // Amount to Next Discount.
  $rsGetDiscounts=$sp->spSelectDiscountBracket($database);		
  if($rsGetDiscounts->num_rows) {
  
    while($DiscountBrackets=$rsGetDiscounts->fetch_object()) {
    
      $minimum=$DiscountBrackets->Minimum;
      $maximum=$DiscountBrackets->Maximum;
      $PMGID=$DiscountBrackets->PMGID;
      
      if($DiscountBrackets->PMGID==1) {
      
        $tmptotalCFT=$totalCFT+$MTDCFT;
        
        if($tmptotalCFT>=$minimum && $tmptotalCFT<=$maximum && $PMGID==1) {
        
          $discID=$DiscountBrackets->Discount;	
          
          $rsNextDiscount=$sp->spSelectNextDiscBracket($database, $discID, 1);
          if($rsNextDiscount->num_rows) {
          
            while($nextDisc=$rsNextDiscount->fetch_object()) {
            
              $tmpNextMinimum=$nextDisc->Minimum;
              $amountToNextDiscCFT=$tmpNextMinimum-$tmptotalCFT;            
            }
          }        
        }      
      } else {
      
        $tmptotalNCFT=$totalNCFT+$MTDNCFT;	
        
        if($tmptotalNCFT>=$minimum && $tmptotalNCFT<=$maximum && $PMGID==2) {
        
          $discID=$DiscountBrackets->Discount;
          						
          $rsNextDiscount=$sp->spSelectNextDiscBracket($database, $discID, 2);          
          if($rsNextDiscount->num_rows) {
          
            while($nextDisc=$rsNextDiscount->fetch_object()) {
            
              $tmpNextMinimum=$nextDisc->Minimum;
              $amountToNextDiscNCFT=$tmpNextMinimum-$tmptotalNCFT;                        
            }
          }        
        }
      }				
    }
  } echo $addtldisc.'_'.$ADPP.'_'.$amountToNextDiscCFT.'_'.$amountToNextDiscNCFT;	
}
	


