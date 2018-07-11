<?PHP
	global $database;
	ini_set('max_execution_time', 500); 
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");	

	$f = $_GET['f'];
	$t = $_GET['t'];
	$s = $_GET['s'];
	$tmpftxndate = strtotime($_GET['f']);
	$fromDate = date("Y-m-d", $tmpftxndate);	
	$tmpttxndate = strtotime($_GET['t']. " +1 day");
	$toDate = date("Y-m-d", $tmpttxndate);
	$txnRegister = '';
	
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
	
	$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    		<tr>
  				<td height="20" align="center"><strong>Daily Cash Collection Report</strong></td>
    		</tr>
  			</table>
  			<br>
			<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
			<tr>
				<td width="15%" height="20" align="right"><strong>From Date :   </strong></td>
				<td width="85%" height="20" align="left">&nbsp;'.$f.'</td>
			</tr>
			<tr>
				<td height="20" align="right"><strong>To Date :   </strong></td>
				<td height="20" align="left">&nbsp;'.$t.'</td>
			</tr>
			</table>
			<br>';
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");
	
	$rs_orreg = $sp->spDCCRRegister($database, $fromDate, $toDate, $s);
	if($rs_orreg->num_rows != 0)
	{
		$tot_cash = 0;
		$tot_check = 0;
		$tot_deposit = 0;
		$tot_cancelled = 0;
		$tot_net = 0;
		$cnt = 0;
		
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
		
		while($row = $rs_orreg->fetch_object()) 
		{
			$cnt ++;
			($cnt % 2) ? $class = "" : $class = "bgEFF0EB";
			$tot_cash += $row->CashAmount;
			$tot_check += $row->CheckAmount;
			$tot_deposit += $row->DepositAmount;
			if ($row->StatusID == 8)
			{
				$cancelled = $row->CashAmount + $row->CheckAmount + $row->DepositAmount;
				$net = 0;  							
			}
			else
			{
				$cancelled = 0;
				$net = $row->NetAmount;  							
			}
			$tot_cancelled += $cancelled;
			$tot_net += $net;
			
			if ($j < $numRows)
			{
				$txnRegister .= '<tr>
				  					<td height="20" align="center">'.$row->ORNO.'</td>
	                  				<td height="20" align="center">'.$row->DocumentNo.'</td>
	                  				<td height="20" align="center">'.$row->CustCode.'</td>
	                  				<td height="20" align="left">&nbsp;'.$row->Customer.'</td>
	                  				<td height="20" align="right">'.number_format($row->CashAmount, 2).'&nbsp;&nbsp;</td>
	                  				<td height="20" align="right">'.number_format($row->CheckAmount, 2).'&nbsp;&nbsp;</td>
	                  				<td height="20" align="right">'.number_format($row->DepositAmount, 2).'&nbsp;&nbsp;</td>
	                  				<td height="20" align="right">'.number_format($cancelled, 2).'&nbsp;&nbsp;</td>
	                  				<td height="20" align="right">'.number_format($net, 2).'&nbsp;&nbsp;</td>
	  							</tr>';
  							
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
				$txnRegister .= '<tr>
				  					<td height="20" align="center">'.$row->ORNO.'</td>
	                  				<td height="20" align="center">'.$row->DocumentNo.'</td>
	                  				<td height="20" align="center">'.$row->CustCode.'</td>
	                  				<td height="20" align="left">&nbsp;'.$row->Customer.'</td>
	                  				<td height="20" align="right">'.number_format($row->CashAmount, 2).'&nbsp;&nbsp;</td>
	                  				<td height="20" align="right">'.number_format($row->CheckAmount, 2).'&nbsp;&nbsp;</td>
	                  				<td height="20" align="right">'.number_format($row->DepositAmount, 2).'&nbsp;&nbsp;</td>
	                  				<td height="20" align="right">'.number_format($cancelled, 2).'&nbsp;&nbsp;</td>
	                  				<td height="20" align="right">'.number_format($net, 2).'&nbsp;&nbsp;</td>
	  							</tr>';
	  							
				$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
						<tr>
			        		<td height="20" align="center" width="9%"><strong>Memo No.</strong></td>
			          		<td height="20" align="center" width="9%"><strong>Number</strong></td>
			          		<td height="20" align="center" width="9%"><strong>IBM/IGS</strong></td>
			          		<td height="20" align="left" width="27%">&nbsp;<strong>Name</strong></td>
			          		<td height="20" align="right" width="9%"><strong>Cash</strong>&nbsp;&nbsp;</td>
			          		<td height="20" align="right" width="9%"><strong>Cheque</strong>&nbsp;&nbsp;</td>
			          		<td height="20" align="right" width="10%"><strong>Deposit Slip</strong>&nbsp;&nbsp;</td>
			          		<td height="20" align="right" width="9%"><strong>Cancelled</strong>&nbsp;&nbsp;</td>
			          		<td height="20" align="right" width="9%"><strong>Net</strong>&nbsp;&nbsp;</td>
			  			</tr>'.$txnRegister.'</table>';
			  			
				// We only print the page to PDF if we have enough rows to print
	      		$pdf->writeHTML($html, true, false, true, false, "");
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$txnRegister = '';
				$j = 0;
				$numRows = $numRowsPerPage;	
			}
		}
		$rs_orreg->close();
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($txnRegister != '')
		{
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
					<tr>
		        		<td height="20" align="center" width="9%"><strong>Memo No.</strong></td>
		          		<td height="20" align="center" width="9%"><strong>Number</strong></td>
		          		<td height="20" align="center" width="9%"><strong>IBM/IGS</strong></td>
		          		<td height="20" align="left" width="27%">&nbsp;<strong>Name</strong></td>
		          		<td height="20" align="right" width="9%"><strong>Cash</strong>&nbsp;&nbsp;</td>
		          		<td height="20" align="right" width="9%"><strong>Cheque</strong>&nbsp;&nbsp;</td>
		          		<td height="20" align="right" width="10%"><strong>Deposit Slip</strong>&nbsp;&nbsp;</td>
		          		<td height="20" align="right" width="9%"><strong>Cancelled</strong>&nbsp;&nbsp;</td>
		          		<td height="20" align="right" width="9%"><strong>Net</strong>&nbsp;&nbsp;</td>
		  			</tr>'.$txnRegister;
		}
		else
		{
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">';
		}
		
		$html .= '<tr><td colspan="9" height="20">&nbsp;</td></tr>
						<tr>
                  			<td height="20" align="right" colspan="4"><strong>REPORT TOTAL :   </strong>&nbsp;&nbsp;</td>
                  			<td height="20" align="right"><strong>'.number_format($tot_cash, 2).'</strong>&nbsp;&nbsp;</td>
                  			<td height="20" align="right"><strong>'.number_format($tot_check, 2).'</strong>&nbsp;&nbsp;</td>
                  			<td height="20" align="right"><strong>'.number_format($tot_deposit, 2).'</strong>&nbsp;&nbsp;</td>
                  			<td height="20" align="right"><strong>'.number_format($tot_cancelled, 2).'</strong>&nbsp;&nbsp;</td>
                  			<td height="20" align="right"><strong>'.number_format($tot_net, 2).'</strong>&nbsp;&nbsp;</td>
  						</tr></table>';
  						
		$pdf->writeHTML($html, true, false, true, false, "");
	}
	else
	{
		$txnRegister .= '<tr align="center"><td height="20" colspan="9" align="center"><strong>No record(s) to display.</strong></td></tr>';
		$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
				<tr>
	        		<td height="20" align="center" width="9%"><strong>Memo No.</strong></td>
	          		<td height="20" align="center" width="9%"><strong>Number</strong></td>
	          		<td height="20" align="center" width="9%"><strong>IBM/IGS</strong></td>
	          		<td height="20" align="left" width="27%">&nbsp;<strong>Name</strong></td>
	          		<td height="20" align="right" width="9%"><strong>Cash</strong>&nbsp;&nbsp;</td>
	          		<td height="20" align="right" width="9%"><strong>Cheque</strong>&nbsp;&nbsp;</td>
	          		<td height="20" align="right" width="10%"><strong>Deposit Slip</strong>&nbsp;&nbsp;</td>
	          		<td height="20" align="right" width="9%"><strong>Cancelled</strong>&nbsp;&nbsp;</td>
	          		<td height="20" align="right" width="9%"><strong>Net</strong>&nbsp;&nbsp;</td>
	  			</tr>'.$txnRegister.'</table>';
			  			
		// We only print the page to PDF if we have enough rows to print
  		$pdf->writeHTML($html, true, false, true, false, "");  					
	}
			
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	$pdf->Output("DCCR.pdf", "I");	
?>