<?php

	global $database;
	require_once "../initialize.php";

	if(!isset($_GET['custID'])) 
	{
   		$custID = 0 ;
	} 
	else 
	{
   		$custID = $_GET['custID'];
	}

	//list of sales invoice
	$rs_si_list = $sp->spSelectDMCM_SI($database, $custID);
		
 echo "<table width='100%'  border='0' cellpadding='0' cellspacing='0' class='bgFFFFFF'>";
         
        $ctr = 0;
        $rowalt = 0;
        
        if ($rs_si_list->num_rows)
        {
           $row = $rs_si_list;
           while ($row = $rs_si_list->fetch_object())
           {
              $ctr ++;
              $rowalt += 1;      
              ($rowalt % 2) ? $alt = "" : $alt = "bgEFF0EB";
              
              $siid = $row->SIPNo;
              $sipno = $row->SIPNos;
        	  $igsName = $row->IGSName; 
              $txnDate = $row->TransactionDate;
              $txnAmount= number_format($row->TransactionAmount,2);
              $outStandingBal = number_format($row->OutStandingBalance,2);
              $refType = $row->RefType;
              
              echo" <tr align='center' class='$alt'>
              			<input type='hidden' name='hdnsipNo$ctr' value='$siid'>
              			<input type='hidden' name='hdnRefType$ctr' value='$refType'>
	                    <td width='5%' height='20' class='borderBR'><input type='checkbox' tabindex='9' name='chkInclude$ctr' id='chkInclude$ctr' value='$ctr' onclick='enableAmount($ctr, this.checked);'><input type='hidden' name='hdnId$ctr' value='$ctr'></td>
	                    <td width='15%' height='20' class='borderBR'><div align='center'>$sipno</div></td>
	                    <td width='15%' height='20' class='borderBR'><div align='center'>$igsName</div></td>
	                    <td width='15%' height='20' class='borderBR'><div align='center'>$txnDate</div></td>
	                    <td width='15%' height='20' class='borderBR'><div align='center'>$txnAmount</div></td>
	                    <td width='15%' height='20' class='borderBR'><div align='center'><input type='hidden' name='hOutstanding$ctr' value='$outStandingBal'>$outStandingBal</div></td>
	                    <td width='15%' height='20' class='borderBR'><div align='center'><input type='text' name='txtPayAmt$ctr' size='12' value='0' class='txtfield3' style='text-align:right' tabindex='10' disabled='true' onkeyup='updatePayment($ctr);'/></div></td>                            
	              </tr>";
           }
           $rs_si_list->close();
        }
        else
        {
           echo "<tr align='center'>
                 <td width='100%' height='20' class='borderBR style1' colspan='10''>No Sales Invoice(s) displayed. </span></td>
                </tr>";
        }
         echo "<input type='hidden'   name='cntrows' value='$ctr'>";
  		
      echo '</table>';