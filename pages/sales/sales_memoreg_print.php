<?php
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	
	include IN_PATH.'scSalesMemoreg_print.php';
	ini_set('max_execution_time', 500);
	
	$strTable = "";
	$ctr = 0;
	$ctr3 = 0;
	$cnt = 0;
	$num = 0;
	$num3 = 0;
	$temp = 0;
	$flag = 1;
	$debittotal = 0;
	$credittotal = 0;
	$debittotal2 = 0;
	$credittotal2 = 0;
	$totalvalued = 0;
	$totalvaluec = 0;
	$totalvalued2 = 0;
	$totalvaluec2 = 0;
	$txndate2 = "";
	
	// create new PDF document
	$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	//set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	//set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	//set some language-dependent strings
	$pdf->setLanguageArray($l);
	
	$pdf->setPrintHeader(false);

	// set font
	$pdf->SetFont("courier", "", 9);

	// add a page
	$pdf->AddPage();
	
	/*
	 	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
		 	<td width="25%" align="right" height="20"><strong>Branch :   </strong></td>
		 	<td width="25%" align="left" height="20">&nbsp;'.$bcode.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$bname.'</td>
		 	<td width="20%" align="right" height="20">&nbsp;</td>
		 	<td width="30%" align="left" height="20">&nbsp;</td>
		</tr>
		<tr>
		     <td height="20" align="right"><strong>Date From :   </strong></td>
		     <td height="20" align="left">&nbsp;'.$frmdate.'</td>
		     <td height="20" align="right"><strong>Date To :   </strong></td>
		     <td height="20" align="left">&nbsp;'.$todate.'</td>
		</tr>	 
		<tr>
		 	<td height="20" align="right"><strong>Date (Transaction/Effective) :   </strong></td>
		 	<td height="20" align="left">&nbsp;Transaction</td>
		  	<td height="20" align="right">&nbsp;</td>
		 	<td height="20" align="left">&nbsp;</td>
		</tr>
		<tr>
		 	<td height="20" align="right"><strong>Reason :   </strong></td>
		 	<td height="20" align="left">&nbsp;'.$rname.'</td>
		 	<td height="20" align="right">&nbsp;</td>
		 	<td height="20" align="left">&nbsp;</td>
		</tr>
		</table>
	 */
	
	$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        	<tr>
          		<td height="20" align="center"><strong>Memo Register</strong></td>
            </tr>
        	</table>
			<br>';
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");

	$query = $sp->spSelectDMCMRegisterCount($database, $frmdate2, $todate2, $rid);
	$num = $query->num_rows;	
	
	if($query->num_rows != 0)
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
		$numRowsPerPage = 18;
		
		$numRows = $numRowsPerPage;
		
		while($row = $query->fetch_object()) 
		{	 
			$cnt ++;
			$txndate = strtotime($row->TxnDate);
			$refno = $row->RefNo;
			$docno = $row->DocumentNo;
			$customer = $row->Customer;
			$particulars = $row->Particulars;
			$debit = $row->Debit;
			$credit = $row->Credit;
			//$debit = number_format($row->Debit, 2, ".", "");
			//$credit = number_format($row->Credit, 2, ".", "");
			//$rcode = $row->Reasonname;
	  		$custcode = $row->CustCode;
	  		$remarks = $row->rem;
	  		$bcode = $row->bcode;
	  		$bname = $row->bname;
		  		
	  		if($credit == 0)
	  		{
	  			$credit = "  ";
	  		}
			if($debit == 0)
			{
				$debit = "  ";
			}
			
	  		$totalvalued = $totalvalued + $row->Debit;
	  		$totalvaluec = $totalvaluec + $row->Credit;
	  		$totalvalued2 = number_format($totalvalued, 2, ".", "");
	  		$totalvaluec2 = number_format($totalvaluec, 2, ".", "");
	  		$debittotal = $debittotal + $row->Debit;
	  		$credittotal = $credittotal + $row->Credit;
		  		
	  		if($ctr == 0)
	  		{
	  		  $rcode = $row->Reasonname;	  		  
	  		}
	  		else
	  		{
	  		  $rcode = "";	  			
	  		}
	  		
			if($ctr3 == 0)
	  		{	  		  
	  		    $txndate = date('m/d/Y', $txndate);
	  		}
	  		else
	  		{	  			
	  			$txndate = '';
	  		}
	  		
	  		if ($j < $numRows)
	  		{
	  			$strTable .= '<tr>
					  			<td height="20" align="left">&nbsp;'.$txndate.'</td>
					  			<td height="20" align="left">&nbsp;'.$custcode.'</td>
		                  		<td height="20" align="left">&nbsp;'.$customer.'</td>
		                  		<td height="20" align="left">&nbsp;'.$rcode.'</td>
		                  		<td height="20" align="left">&nbsp;'.$docno.'</td>
		                  		<td height="20" align="right">'.$debit.'&nbsp;&nbsp;</td>
		                  		<td height="20" align="right">'.$credit.'&nbsp;&nbsp;</td>
		                  		<td height="20" align="left">&nbsp;'.$remarks.'</td>
			  				</tr>';
			  		
				$txndate2 = date('Y-m-d',strtotime($row->TxnDate));
			    $queryCnt = $sp->spSelectDMCMRegisterCount2($database,$txndate2, $row->reasonID);
			    $num2=$queryCnt->num_rows;
				$ctr = $ctr + 1;
						
				if($ctr == $num2)
				{
					$debittotal2 = $debittotal;
					$credittotal2 = $credittotal;
									
					if($debittotal2 == 0)
					{
						$debittotal2 = " 0.00";
					}
					else
					{
						$debittotal2 = 0;
						$debittotal2 = number_format($debittotal, 2, ".", "");   
					}
					if($credittotal == 0)
					{
						$credittotal2 = " 0.00";
					}
					else
					{
						$credittotal2 = 0;
						$credittotal2 = number_format($credittotal, 2, ".", "");   
					}
									
				    $strTable .= '<tr>
									<td height="20" align="left">&nbsp;</td>
						  			<td height="20" align="left">&nbsp</td>
		                      		<td height="20" align="left">&nbsp;</td>
		                      		<td height="20" align="right"><strong>'.$row->Reasonname.' Subtotal :   </strong></td>
		                      		<td height="20" align="left">&nbsp;</td>
		                      		<td height="20" align="right"><strong>'.$debittotal2.'</strong>&nbsp;&nbsp;</td>
		                      		<td height="20" align="right"><strong>'.$credittotal2.'</strong>&nbsp;&nbsp;</td>
		                      		<td height="20" align="left">&nbsp;</td>
								</tr>';
												
					$debittotal = 0;
					$credittotal = 0;
					$ctr = 0;							  
				} 
								
				$queryCnt2 = $sp->spSelectDMCMRegisterCount2($database,$txndate2, $rid);		
				$num3 = $queryCnt2->num_rows;
				$dailytotaldate = date('d/m/Y',strtotime($row->TxnDate));
				$ctr3 = $ctr3 + 1;
						
				if($ctr3 == $num3)
				{
					$totalvalued2 = number_format($totalvalued, 2, ".", "");
					$totalvaluec2 = number_format($totalvaluec, 2, ".", "");
			  		$strTable .= '<tr>
									<td height="20" align="left">&nbsp;</td>
						  			<td height="20" align="left">&nbsp;</td>
		                      		<td height="20" colspan="2" align="right"><strong>Daily Total For :   '.$dailytotaldate.'</strong>&nbsp;</td>
		                      		<td height="20" align="left">&nbsp;</td>
		                      		<td height="20" align="right"><strong>'.$totalvalued2.'</strong>&nbsp;&nbsp;</td>
		                      		<td height="20" align="right"><strong>'.$totalvaluec2.'</strong>&nbsp;&nbsp;</td>
		                      		<td height="20" align="left">&nbsp;</td>
								</tr>';
								  
			  		$ctr3 = 0;	
				  	$totalvaluec = 0;
				  	$totalvalued = 0;						  
				}
				
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->Customer) > $productLenThreshold) 
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
	  			$strTable .= '<tr>
					  			<td height="20" align="left">&nbsp;'.$txndate.'</td>
					  			<td height="20" align="left">&nbsp;'.$custcode.'</td>
		                  		<td height="20" align="left">&nbsp;'.$customer.'</td>
		                  		<td height="20" align="left">&nbsp;'.$rcode.'</td>
		                  		<td height="20" align="left">&nbsp;'.$docno.'</td>
		                  		<td height="20" align="right">'.$debit.'&nbsp;&nbsp;</td>
		                  		<td height="20" align="right">'.$credit.'&nbsp;&nbsp;</td>
		                  		<td height="20" align="left">&nbsp;'.$remarks.'</td>
			  				</tr>';
			  		
				$txndate2 = date('Y-m-d',strtotime($row->TxnDate));
			    $queryCnt = $sp->spSelectDMCMRegisterCount2($database,$txndate2, $row->reasonID);
			    $num2=$queryCnt->num_rows;
				$ctr = $ctr + 1;
						
				if($ctr == $num2)
				{
					$debittotal2 = $debittotal;
					$credittotal2 = $credittotal;
									
					if($debittotal2 == 0)
					{
						$debittotal2 = " 0.00";
					}
					else
					{
						$debittotal2 = 0;
						$debittotal2 = number_format($debittotal, 2, ".", "");   
					}
					if($credittotal == 0)
					{
						$credittotal2 = " 0.00";
					}
					else
					{
						$credittotal2 = 0;
						$credittotal2 = number_format($credittotal, 2, ".", "");   
					}
									
				    $strTable .= '<tr>
									<td height="20" align="left">&nbsp;</td>
						  			<td height="20" align="left">&nbsp</td>
		                      		<td height="20" align="left">&nbsp;</td>
		                      		<td height="20" align="right"><strong>'.$row->Reasonname.' Subtotal :   </strong></td>
		                      		<td height="20" align="left">&nbsp;</td>
		                      		<td height="20" align="right"><strong>'.$debittotal2.'</strong>&nbsp;&nbsp;</td>
		                      		<td height="20" align="right"><strong>'.$credittotal2.'</strong>&nbsp;&nbsp;</td>
		                      		<td height="20" align="left">&nbsp;</td>
								</tr>';
												
					$debittotal = 0;
					$credittotal = 0;
					$ctr = 0;							  
				} 
								
				$queryCnt2 = $sp->spSelectDMCMRegisterCount2($database,$txndate2, $rid);		
				$num3 = $queryCnt2->num_rows;
				$dailytotaldate = date('d/m/Y',strtotime($row->TxnDate));
				$ctr3 = $ctr3 + 1;
						
				if($ctr3 == $num3)
				{
					$totalvalued2 = number_format($totalvalued, 2, ".", "");
					$totalvaluec2 = number_format($totalvaluec, 2, ".", "");
			  		$strTable .= '<tr>
									<td height="20" align="left">&nbsp;</td>
						  			<td height="20" align="left">&nbsp;</td>
		                      		<td height="20" colspan="2" align="right"><strong>Daily Total For :   '.$dailytotaldate.'</strong>&nbsp;</td>
		                      		<td height="20" align="left">&nbsp;</td>
		                      		<td height="20" align="right"><strong>'.$totalvalued2.'</strong>&nbsp;&nbsp;</td>
		                      		<td height="20" align="right"><strong>'.$totalvaluec2.'</strong>&nbsp;&nbsp;</td>
		                      		<td height="20" align="left">&nbsp;</td>
								</tr>';
								  
			  		$ctr3 = 0;	
				  	$totalvaluec = 0;
				  	$totalvalued = 0;						  
				}
				
	  			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
						<tr>
			          		<td height="20" align="center" width="10%"><strong>Date</strong></td>
			              	<td height="20" align="center" width="10%"><strong>Dealer</strong></td>
			              	<td height="20" align="center" width="25%"><strong>Name</strong></td>
			              	<td height="20" align="center" width="15%"><strong>Reason Code</strong></td>
			              	<td height="20" align="center" width="10%"><strong>Reference #</strong></td>
			              	<td height="20" align="center" width="10%"><strong>Debit</strong></td>
			              	<td height="20" align="center" width="10%"><strong>Credit</strong></td>
			              	<td height="20" align="center" width="10%"><strong>Remarks</strong></td>
						</tr>'.$strTable.'</table>';
						
				// We only print the page to PDF if we have enough rows to print
	      		$pdf->writeHTML($html, true, false, true, false, "");
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$strTable = '';
				$j = 0;
				$numRows = $numRowsPerPage;		  			
	  		}
	  	}
	  	$query->close();
	  	
	  	// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($strTable != '')
		{
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
					<tr>
		          		<td height="20" align="center" width="10%"><strong>Date</strong></td>
		              	<td height="20" align="center" width="10%"><strong>Dealer</strong></td>
		              	<td height="20" align="center" width="25%"><strong>Name</strong></td>
		              	<td height="20" align="center" width="15%"><strong>Reason Code</strong></td>
		              	<td height="20" align="center" width="10%"><strong>Reference #</strong></td>
		              	<td height="20" align="center" width="10%"><strong>Debit</strong></td>
		              	<td height="20" align="center" width="10%"><strong>Credit</strong></td>
		              	<td height="20" align="center" width="10%"><strong>Remarks</strong></td>
					</tr>'.$strTable.'</table><br>';
		}
		else
		{
			$html = '<br>';
		}
		
		$html .= '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">			
			 		<tr>
						<td height="20" align="right" width="20%">&nbsp;</td>
			            <td height="20" align="right" width="37%">&nbsp;</td>
			            <td height="20" align="right" width="13%"><strong>Report Total :   </strong></td>				            
			            <td height="20" align="right" width="9%"><strong>'.$totalvalued2.'</strong>&nbsp;&nbsp;</td>
			            <td height="20" align="right" width="9%"><strong>'.$totalvaluec2.'</strong>&nbsp;&nbsp;</td>
			         	<td height="20" align="left" width="15%">&nbsp;</td>	           
					</tr>
					</table>';
					
		$pdf->writeHTML($html, true, false, true, false, "");		
	}
	else
	{
		$strTable = '<tr align="center"><td height="20" colspan="8" align="center"><strong>No record(s) to display.</strong></td></tr>';
		$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
					<tr>
		          		<td height="20" align="center" width="10%"><strong>Date</strong></td>
		              	<td height="20" align="center" width="10%"><strong>Dealer</strong></td>
		              	<td height="20" align="center" width="25%"><strong>Name</strong></td>
		              	<td height="20" align="center" width="15%"><strong>Reason Code</strong></td>
		              	<td height="20" align="center" width="10%"><strong>Reference #</strong></td>
		              	<td height="20" align="center" width="10%"><strong>Debit</strong></td>
		              	<td height="20" align="center" width="10%"><strong>Credit</strong></td>
		              	<td height="20" align="center" width="10%"><strong>Remarks</strong></td>
					</tr>'.$strTable.'</table>';
					
		$pdf->writeHTML($html, true, false, true, false, "");		
	}
	
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	$pdf->Output("MemoRegister.pdf", "I");
?>