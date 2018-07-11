<?php
/*
  @package TPI Choices Loader 
  @author John Paul Pineda.
  @email paulpineda19@yahoo.com
  @copyright 2012 John Paul Pineda.
  @version 1.0 October 10, 2012.

  @description Is used for textbox(es) with auto-completion feature.
*/
   
require_once('../initialize.php');

global $database;

function_exists($_GET['what_to_load']) or die('Error: The function requested doesn\'t exist. Please contact your Web Application Developer.');

$execute_function=$_GET['what_to_load']; $execute_function();

function InventoryCountList() {
  
  global $tpi, $database;
  
  $text_searched=isset($_POST['worksheet1'])?$_POST['worksheet1']:$_POST['worksheet2'];
  
  $tpi_inventorycounts=$tpi->spLoadTableRecords($database, 'inventorycount', array('ID', 'DocumentNo', 'WarehouseID'), 'DocumentNo LIKE \''.$text_searched.'%\'');
  
  if($tpi_inventorycounts->num_rows) {

    echo '<ul>';  
    while($field=$tpi_inventorycounts->fetch_object()) echo '<li id="'.$field->ID.'_'.$field->WarehouseID.'">'.$field->DocumentNo.'</li>';
    echo '</ul>';
  }
}




