<?php
/*   
  @modified by John Paul Pineda
  @date May 21, 2013
  @email paulpineda19@yahoo.com         
*/

require_once('../initialize.php');

global $database;

$customerID=$_GET['custid'];
$penaltyAmount=0;

$rsCheckPenalty=$sp->spCheckCustomerPenalty($database, $customerID);
if($rsCheckPenalty->num_rows) {

  while($field=$rsCheckPenalty->fetch_object()) echo ((float)$field->penalty);		
}