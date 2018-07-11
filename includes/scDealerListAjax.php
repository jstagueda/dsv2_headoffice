<?php
/*   
  @modified by John Paul Pineda
  @date May 18, 2013
  @email paulpineda19@yahoo.com         
*/	

require_once('../initialize.php');
	
global $database;

$vSearch=$_POST['txtCustomer'];
 
$rsGetDealer=$sp->spSelectDealerAjaxProvision($database, $vSearch);
if($rsGetDealer->num_rows) {

  echo '<ul>';
    
  while($field=$rsGetDealer->fetch_object()) {        
          
    $customer=$field->ID.'_'.trim($field->Name).'_'.trim($field->Code).'_'.trim($field->IBMName).'_'.$field->CustomerTypeID;
    echo '<li id="'.$customer.'"><div align="left"><strong>'.$field->Code.' - '.$field->Name.'</strong></div></li>';
  }
  
  echo '</ul>';
}

