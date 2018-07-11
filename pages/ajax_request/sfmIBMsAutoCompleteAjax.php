<?php
/*
 * @author: jdymosco
 * @date: Feb. 20, 2013
 * @description: New AJAX callback file for getting IBMs autocompleter.
 * @explanation: We just mimic the old AJAX callback file used so there would be no big changes. The only different is we
 * changed the method used in getting IBMs lists database table used.
 */

   include('../../initialize.php');
   $vSearch = $_POST['txtIBMNo'];
   
   $rsGetCustomer = tpi_getManagerUplines($vSearch);
   
   if($rsGetCustomer){
        echo "<ul>";
        foreach($rsGetCustomer as $row){
                $customer =  $row->ID.'_'.$row->uplineName.'_'.$row->mCode;
                echo '<li id="'.$customer.'" ><div align="left"><strong>'.$row->mCode.' - '.$row->uplineName.'</strong></div></li>';

        }
        echo "</ul>";
   }
   
?>
