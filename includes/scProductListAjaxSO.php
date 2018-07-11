<?php
/*   
  @modified by John Paul Pineda
  @date April 30, 2013
  @email paulpineda19@yahoo.com         
*/

require_once('../initialize.php');

$index=$_GET['index'];

$vSearch=$_POST['txtProdCode'.$index];

$rsGetProductList=$sp->spSelectProductListByLevelIDSO($database, 3, $vSearch);

if($rsGetProductList->num_rows) {

  echo '<ul>';
  
  $cnt=0;
  
  while($row=$rsGetProductList->fetch_object()) {
  
    $cnt++;
    
    $checkSOH=0;
    $cntSubstitute=0;
    $substitute=0;
    
    $rsCheckSubstitute=$sp->spCheckIfSubstitute($database, $row->ID);    
    if($rsCheckSubstitute->num_rows) {
    
      while($substitute=$rsCheckSubstitute->fetch_object()) {
      
        if($checkSOH==0) $checkSOH=$substitute->CheckAvailability;
        
        $cntSubstitute=$substitute->cnt;
      }
    }
    
    if($checkSOH>0) {
    
      if($row->SOH==0) {
      
        if($cntSubstitute>0) $substitute=1;	        
      }    
    } else {
    
      if($cntSubstitute>0) $substitute=1;	      
    }
    
    $unitPrice=number_format($row->UnitPrice, 2, '.', '');
    $product=$row->ID.'_'.$row->Name.'_'.$unitPrice.'_'.$row->PMG.'_'.$row->PMGID.'_'.$row->ProductType.'_'.$row->SOH.'_'.$row->InTransit.'_'.$substitute.'_'.$row->Code;    
    
    if($cnt!=21) echo '<li id="'.str_replace('"', '', $product).'"><div align="left"><strong>'.$row->Code.' - '.$row->Name.'</strong></div></li>';
    else echo '<li id="0"><div align="left"><strong>Refine Search</strong></div></li>';	        
  }
  
  if($cnt==0) echo '<li id="0"><div align="left"><strong>No records found.</strong></div></li>';
  
  echo '</ul>';
} else {

  echo '<ul>';			
  echo '  <li id="0"><div align="left"><strong>No records found.</strong></div></li>';			
  echo '</ul>';
}

