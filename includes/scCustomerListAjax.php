<?php
/*   
  @modified by John Paul Pineda
  @date May 14, 2013
  @email paulpineda19@yahoo.com         
*/
   
require_once('../initialize.php');

global $database;

$vSearch=trim($_POST['txtCustomer']);

$rsGetCustomer=$sp->spSelectCustomerAjax($database, $vSearch);

if($rsGetCustomer->num_rows) {

  echo '<ul>';
  
  while($field=$rsGetCustomer->fetch_object()) {
    
    $customer_code=trim($field->Code);
    $customer_name=trim($field->Name);
    
    $customer=$field->ID.'_'.$customer_name.'_'.$customer_code.'_'.$field->GSUTypeID;
        
    echo '<li id="'.$customer.'"><div align="left"><strong>'.$customer_code.' - '.$customer_name.'</strong></div></li>';  
  }
  
  echo '</ul>';
}


