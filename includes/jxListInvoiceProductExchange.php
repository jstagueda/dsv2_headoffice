<?php
   
   require_once "../initialize.php";
   global $database;
  
   $vSearch = $_POST['txtSINo'];
   $IBMID = $_SESSION['CustomerIDProdExchange'];
    
   $rsGetCustomer = $sp->spSelectListInvoiceProdExchange($database,$IBMID,$vSearch);
   
   
   if($rsGetCustomer->num_rows){
   echo "<ul> ";
      while($row = $rsGetCustomer->fetch_object())
         {
         	//$outstandingbalance = str_replace(",","",number_format($row->OutstandingBalance,2));
		 	$sidetails = $row->SINo . "_" . $row->TxnID . "_" .  $row->DocumentNo . "_" .$row->TxnDate . "_" . $row->RefNo . "_"  . $row->DRDate."_" . $row->BranchName  . "_" . $row->CreatedBy . "_" . $row->TxnStatus  . "_" . $row->ConfirmedBy . "_" . $row->DateConfirmed. "_" . $row->Remarks;
             echo "
               <li id='$sidetails' ><div align='left'><strong>$row->SINo </strong></div></li>              
              ";

         }
   echo "</ul>";
   }
   else
   {
   		echo "
   				<ul><li><strong> No Records to Display.</strong></li></ul>
   		";
   		
   }
?>
