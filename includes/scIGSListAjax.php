<?php
   
   require_once "../initialize.php";
   global $database;
   $index = $_GET['index'];
   $vSearch = $_POST['txtIGSCode'.$index];
   $IBMID = $_SESSION['IBMIDOfficialReceipt'];
    
   $rsGetCustomer = $sp->spSelectIGSDealersByIBMID($database,$IBMID,$vSearch);
   
   
   if($rsGetCustomer->num_rows){
   echo "<ul> ";
      while($row = $rsGetCustomer->fetch_object())
         {
         	//$outstandingbalance = str_replace(",","",number_format($row->OutstandingBalance,2));
		 	$customer =  $row->CustomerID.'_'.$row->CustCode.'_'.$row->CustName.'_'.number_format($row->OutstandingBalance,2).'_'.number_format($row->OutstandingAmount,2).'_'.number_format($row->TotalOutstanding,2).'_'.$index;
             echo "
               <li id='$customer' ><div align='left'><strong>$row->CustCode - $row->CustName </strong></div></li>              
              ";

         }
   echo "</ul>";
   }
   else
   {
   }
?>
