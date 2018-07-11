<style>
    body{font-family: arial;}
    .pageset{margin-bottom:20px;}
    .pageset table{font-size:12px; border-collapse: collapse;}
    .pageset table td{padding:3px;}
    @page{margin:0.5in 0; size: landscape;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>

<?php
	require_once "../../initialize.php";
	include IN_PATH.DS."scBirBackEnd.php";
	
	include IN_PATH.'scBirBackEnd_print.php';
	ini_set('max_execution_time', 500);
	
	$strTable = "";
	$ctr = 0;
	$cnt = 0;
	$grssamttot = 0;
	$vsatot = 0;
	$vamnttot = 0;
	$vnamnttot = 0;
	$zerotot = 0;
	$adptot = 0;
	$txndate2 = "";
	$grssamttot = 0;
	$vsatot2 = 0;
	$vamnttot2 = 0;
	$adptot2 = 0;
		
	$header = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td height="20" align="center"><strong>BIR Back End Report</strong></td>
            </tr>
        	</table>
        	<br>
        	<table width="100%" border="0" style="font-size:12px;" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="20%" height="20" align="right"><strong>Date From :   </strong></td>
		     	<td width="30%" height="20" align="left">&nbsp;'.$frmdate.'</td>
		     	<td width="20%" height="20" align="right"><strong>Date To :   </strong></td>
		     	<td width="30%" height="20" align="left">&nbsp;'.$todate.'</td>
			</tr>	 
			<tr>
			 	<td height="20" align="right"><strong>Compay Name / Branch :   </strong></td>
			 	<td height="20" align="left">Tupperware Brands / '.$bname.'</td>
			  	<td height="20" align="right">&nbsp;</td>
			 	<td height="20" align="left">&nbsp;</td>
			</tr>
			<tr>
		     	<td height="20" align="right"><strong>TIN :   </strong></td>
		     	<td height="20" align="left">&nbsp;'.$btin.'</td>
		     	<td height="20" align="right">&nbsp;</td>
	     		<td height="20" align="left">&nbsp;</td>
			 </tr>
			 <tr>
		     	<td height="20" align="right"><strong>SN# of Server :   </strong></td>
		     	<td height="20" align="left">&nbsp;'.$bsn.'</td>
		     	<td height="20" align="right">&nbsp;</td>
	     		<td height="20" align="left">&nbsp;</td>
			 </tr>
			</table>
			<br>';
	
	// Print text using writeHTML()
	//$pdf->writeHTML($html, true, false, true, false, "");
        echo $header;
	
	//$query = $sp->spSelecBirBackEndReportCount($database, $frmdate2, $todate2,0);
	$query = birbackend($database, date('Y-m-d', strtotime($frmdate)), date('Y-m-d', strtotime($todate)), true, 1, 10, $branch);
	$num = $query->num_rows;
	
	if ($query->num_rows > 0)
	{
		$j = 0; 
		// Estimated string length of product description when the TCPDF engine will wrap the text,
		// therfore consuming and extra row
		$productLenThreshold = 30;
		
		// Threshold to determine whether the number of rows per page should be decremented
		// to accomodate product whose length is greater than $productLenThreshold
		$rowDeletionThreshold = 2; 
		
		// Counter of current row deletion threshold
		$deletionThreshold = 0;
		
		// Estimated number of rows per page
		$numRowsPerPage = 20;
		
		$numRows = $numRowsPerPage;
		
		while($row = $query->fetch_object()) 
		{	 
			$cnt ++;
            $Date = date("m/d/Y",strtotime($row->txndate));
            $beginv = $row->dn1;
            $endinv = $row->dn2;
            $vsa = number_format($row->VatSales, 2, '.', ',');
            $vamnt = number_format($row->VA, 2, '.', ',');
            $vnamt = number_format($row->nonvat, 2, '.', ',');
            $zero = number_format($row->ZeroRated, 2, '.', ',');
            $adp = number_format($row->ADP, 2, '.', ',');
            $ret = number_format($row->returnss, 2, '.', ',');
            $voids = $row->Voids;
            $overrun = number_format($row->Overrun, 2, '.', ',');
            $vatsales = number_format($row->vatsales, 2, '.', ',');
            $totSales = ($row->vatsales + $row->VA) - $row->ADP;
           // if($voids != 1){
                if($cnt == 1){
                    $ntamnt = $row->NA;
                    $outsbal = $row->NA + $totSales;                
                }else{
                    $ntamnt = $outsbal; // Net Amount + Total Sales (Vat Sales + Vat Amount - Additional Prev Purchase)
                    $outsbal = $outsbal + $totSales;                
                }
            //}
            $xtotSales = $row->vatsales;
            $endBal = number_format($outsbal, 2, '.', ',');
            $begBal = number_format($ntamnt, 2, '.', ','); 
	  		  		
	  		
	  		if ($j < $numRows)
			{
				$strTable .= "<tr class='trlist'>
								<td align='center'>".$row->xDate."</td>
								<td align='center'>".$row->BeginningInvoice."</td>
								<td align='center'>".$row->EndingInvoice."</td>                    
								<td align='right'>".$row->BeginningBalance."</td>
								<td align='right'>".$row->EndingBalance."</td>
								<td align='right'>".$row->TotalSales."</td>
								<td align='right'>".$row->VATSales."</td>
								<!-- td align='right'>$vatsales</td -->
								<td align='right'>".number_format($row->VATAmount,2)."</td>
								<td align='right'>".number_format(($row->NonVATSales2),2)."</td>
								<td align='right'>".$row->ZeroRated."</td>
								<td align='right'>".$row->DiscountPrevPurchase."</td>
								<td align='right'>".$row->Returns."</td>
								<td align='right'>".$row->Void."</td>
								<td align='right'>".$row->OverunOverflow."</td>
								<!-- td align='right'>$overrun</td -->   </tr>";
				
				
				
				
				
				// $xBegBal = (($voids >= 0)?number_format(($ntamnt + ($xtotSales)),2):0);
				$grssamttot += $row->TotalSales;
				$vsatot     += $row->VATSales;
				$vamnttot   += $row->VATAmount;
				$adptot     += $row->DiscountPrevPurchase;
			  				
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($beginv) > $productLenThreshold) 
				{
					// Subtract the number of rows only if we reached threshold of the number
					// of rows whose string length is greater than product length threshold
					if ($deletionThreshold != $rowDeletionThreshold) 
					{
						$numRows--;
						$deletionThreshold++;
					}
					else 
					{
						// Reset the current count
						$deletionThreshold = 0;
					}
				}
				$j++;					
			}
			else
			{
				$strTable .= "<tr class='trlist'>
								<td align='center'>".$row->xDate."</td>
								<td align='center'>".$row->BeginningInvoice."</td>
								<td align='center'>".$row->EndingInvoice."</td>                    
								<td align='right'>".$row->BeginningBalance."</td>
								<td align='right'>".$row->EndingBalance."</td>
								<td align='right'>".$row->TotalSales."</td>
								<td align='right'>".$row->VATSales."</td>
								<!-- td align='right'>$vatsales</td -->
								<td align='right'>".number_format($row->VATAmount,2)."</td>
								<td align='right'>".number_format(($row->NonVATSales2),2)."</td>
								<td align='right'>".$row->ZeroRated."</td>
								<td align='right'>".$row->DiscountPrevPurchase."</td>
								<td align='right'>".$row->Returns."</td>
								<td align='right'>".$row->Void."</td>
								<td align='right'>".$row->OverunOverflow."</td>
								<!-- td align='right'>$overrun</td -->   </tr>";
				
				
				
				
				
				// $xBegBal = (($voids >= 0)?number_format(($ntamnt + ($xtotSales)),2):0);
				$grssamttot += $row->TotalSales;
				$vsatot     += $row->VATSales;
				$vamnttot   += $row->VATAmount;
				$adptot     += $row->DiscountPrevPurchase;
			  				
				$html = '<table width="100%" align="center" border="1" cellpadding="0" cellspacing="0">							
						<tr>
				    		<td height="20" align="center" width="7%"><strong>Date</strong></td>
				        	<td height="20" align="center" width="7%"><strong>Beginning Invoice</strong></td>
				          	<td height="20" align="center" width="7%"><strong>Ending Invoice</strong></td>
							<td height="20" align="center" width="8%"><strong>Beginning Balance</strong></td>
				          	<td height="20" align="center" width="7%"><strong>Ending Balance</strong></td>				          	
				          	<td height="20" align="center" width="7%"><strong>Total Sales</strong></td>
				          	<td height="20" align="center" width="7%"><strong>VAT Sales</strong></td>
				          	<td height="20" align="center" width="7%"><strong>VAT Amount</strong></td>
				          	<td height="20" align="center" width="7%"><strong>Non-VAT Sales</strong></td>
				          	<td height="20" align="center" width="7%"><strong>Zero Rated</strong></td>
				          	<td height="20" align="center" width="7%"><strong>Discount Prev Purchase</strong></td>
				          	<td height="20" align="center" width="7%"><strong>Returns</strong></td>
				          	<td height="20" align="center" width="7%"><strong>Voids</strong></td>
				          	<td height="20" align="center" width="8%"><strong>Overrun / Overflow</strong></td>
						</tr>'.$strTable.'</table>';
						
				// We only print the page to PDF if we have enough rows to print
                                //$pdf->writeHTML($html, true, false, true, false, "");
				//$pdf->AddPage();
                                echo "<div class='pageset'>";
                                echo $html;
                                echo "</div>";
				
				// reset the row counter and the details 
				$strTable = '';
				$j = 0;
				$numRows = $numRowsPerPage;						
			}
	
			$grssamttot2 = number_format($grssamttot, 2, ".", ",");
			$vsatot2 = number_format($vsatot, 2, ".", ",");
			$vamnttot2 = number_format($vamnttot, 2, ".", ",");
			$adptot2 = number_format($adptot, 2, ".", ",");
		}
		$query->close();
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($strTable != '')
		{
			$html = '<table width="100%" align="center" border="1" cellpadding="0" cellspacing="0">							
					<tr>
		    			<td height="20" align="center" width="7%"><strong>Date</strong></td>
			        	<td height="20" align="center" width="7%"><strong>Beginning Invoice</strong></td>
			          	<td height="20" align="center" width="7%"><strong>Ending Invoice</strong></td>
			          	<td height="20" align="center" width="8%"><strong>Beginning Balance</strong></td>
						<td height="20" align="center" width="7%"><strong>Ending Balance</strong></td>
			          	<td height="20" align="center" width="7%"><strong>Total Sales</strong></td>
			          	<td height="20" align="center" width="7%"><strong>VAT Sales</strong></td>
			          	<td height="20" align="center" width="7%"><strong>VAT Amount</strong></td>
			          	<td height="20" align="center" width="7%"><strong>Non-VAT Sales</strong></td>
			          	<td height="20" align="center" width="7%"><strong>Zero Rated</strong></td>
			          	<td height="20" align="center" width="7%"><strong>Discount Prev Purchase</strong></td>
			          	<td height="20" align="center" width="7%"><strong>Returns</strong></td>
			          	<td height="20" align="center" width="7%"><strong>Voids</strong></td>
			          	<td height="20" align="center" width="8%"><strong>Overrun / Overflow</strong></td>
					</tr>'.$strTable;
		}
		
		$html .= '<tr>
                                    <td height="20" align="right" colspan=5><strong> Total :   </strong></td>
                                    <td height="20" align="right" width="7%"><strong>'.$grssamttot2.'</strong></td>
                                    <td height="20" align="right" width="7%"><strong>'.$vsatot2.'</strong></td>
                                    <td height="20" align="right" width="7%"><strong>'.$vamnttot2.'</strong></td>
                                    <td height="20" align="right" width="7%">&nbsp;</td>
                                    <td height="20" align="right" width="7%"></td>
                                    <td height="20" align="right" width="7%"><strong>'.$adptot2.'</strong></td>
                                    <td height="20" align="right" width="7%">&nbsp;</td>
                                    <td height="20" align="right" width="7%">&nbsp;</td>
                                    <td height="20" align="right" width="8%">&nbsp;</td>  
                                </tr></table>';
				
		//$pdf->writeHTML($html, true, false, true, false, "");
                echo "<div class='pageset'>";
                echo $html;
                echo "</div>";
	}
	else
	{
		$strTable .= '<tr align="center"><td height="20" colspan="14" align="center"><strong>No record(s) to display.</strong></td></tr>';
		$html = '<table width="100%" align="center" border="1" cellpadding="0" cellspacing="0">							
				<tr>
	    			<td height="20" align="center" width="7%"><strong>Date</strong></td>
		        	<td height="20" align="center" width="7%"><strong>Beginning Invoice</strong></td>
		          	<td height="20" align="center" width="7%"><strong>Ending Invoice</strong></td>
					<td height="20" align="center" width="8%"><strong>Beginning Balance</strong></td>
		          	<td height="20" align="center" width="7%"><strong>Ending Balance</strong></td>		          	
		          	<td height="20" align="center" width="7%"><strong>Total Sales</strong></td>
		          	<td height="20" align="center" width="7%"><strong>VAT Sales</strong></td>
		          	<td height="20" align="center" width="7%"><strong>VAT Amount</strong></td>
		          	<td height="20" align="center" width="7%"><strong>Non-VAT Sales</strong></td>
		          	<td height="20" align="center" width="7%"><strong>Zero Rated</strong></td>
		          	<td height="20" align="center" width="7%"><strong>Discount Prev Purchase</strong></td>
		          	<td height="20" align="center" width="7%"><strong>Returns</strong></td>
		          	<td height="20" align="center" width="7%"><strong>Voids</strong></td>
		          	<td height="20" align="center" width="8%"><strong>Overrun / Overflow</strong></td>
				</tr>'.$strTable.'</table><br>';
				
		//$pdf->writeHTML($html, true, false, true, false, "");	
                echo "<div class='pageset'>";
                echo $html;
                echo "</div>";
	}

	// reset pointer to the last page
	//$pdf->lastPage();
	
	// Close and output PDF document
	//$pdf->Output("BIRBackEnd.pdf", "I");	
?>

<script>
    window.print();
</script>