<?php
   
   require_once "../initialize.php";
   global $database;
   $vSearch = $_POST['txtRecruiteAcctNo'];
    
   $rsGetCustomer = $sp->spSelectIGSAjax($database, $vSearch);
   
   if($rsGetCustomer->num_rows){
   echo "<ul> ";
      while($row = $rsGetCustomer->fetch_object())
         {
		 	$customer =  $row->ID.'_'.$row->Name.'_'.$row->Code.'_'.$row->MobileNo;
             echo "
               <li id='$customer' ><div align='left'><strong>$row->Code - $row->Name - $row->MobileNo</strong></div></li>              
              ";

         }
   echo "</ul>";
   }
   else
   {
   }
?>
