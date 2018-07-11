<?php
   
   require_once "../initialize.php";
   global $database;
   $index = $_GET['index'];
   $vSearch = $_POST['txtSINO'.$index];
   $IBMID = $_SESSION['hCustomerIDApplyPayment'];
    //echo $IBMID;
   $rsGetCustomer = $sp->spSelectCustomerIGSSIPaging($database,$vSearch,$IBMID);
   //echo "spSelectCustomerIGSSIPaging($database,$vSearch,$IBMID)";
   
   if($rsGetCustomer->num_rows){
   echo "<ul> ";
      while($row = $rsGetCustomer->fetch_object())
         {
		 	$customer =  $row->RefType.'_'.$row->SIPNo.'_'.$row->SIPNos.'_'.$row->IGSName.'_'.$row->TransactionDate.'_'.$row->TransactionAmount.'_'.$row->OutStandingBalance.'_'.$row->IsWithinCreditTerm.'_'.$index;
             echo "
               <li id='$customer' ><div align='left'><strong>$row->SIPNos</strong></div></li>              
              ";

         }
   echo "</ul>";
   }
   else
   {
   }
?>
