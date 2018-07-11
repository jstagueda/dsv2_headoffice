<?php
   
   require_once "../initialize.php";
   
   $vSearch = $_POST['txtIBMNo'];
    global $database;
   $rsGetCustomer = $sp->spSelectIBMAjax($database, $vSearch);
   
   if($rsGetCustomer->num_rows){
   echo "<ul> ";
      while($row = $rsGetCustomer->fetch_object())
         {
		 	$customer =  $row->ID.'_'.$row->Name.'_'.$row->Code;
             echo "
               <li id='$customer' ><div align='left'><strong>$row->Code - $row->Name</strong></div></li>              
              ";

         }
   echo "</ul>";
   }
   else
   {
   }
?>
