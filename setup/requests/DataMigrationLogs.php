<?php	
	require_once "../../initialize.php";	
   	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	
	$dealerUpload = new DealerUpload();
	global $database;
	//ini_set('max_execution_time', 500);

	
	
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
	$pdf->SetFont("courier", "", 10);

	// add a page
	$pdf->AddPage();
	
	$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td height="20" align="center"><strong>Data Migration Logs</strong></td>
	</tr>
	</table>
	<br>';
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false,true, false, "");
	$inventory  = $dealerUpload->spSelectInventoryTmp1($database);
	$totalSOH = 0;
		if($inventory->num_rows){
			while ($row = $inventory->fetch_object()){
				$totalSOH += $row->SOH;
			}
		}else{
			$totalSOH = 0;
		}
	$OpenSi		= $dealerUpload->spSelectOpenSITmp1($database);
	$TotalCSPOSI = 0;
		if($OpenSi->num_rows){
			while($row = $OpenSi->fetch_object()){
				$TotalCSPOSI += $row->csp;
			}
		}else{
			$TotalCSPOSI = 0;
		}
	$LastSI		= $dealerUpload->spSelectLastSITmp1($database);
	$TotalCSPLSI = 0;		
		if($LastSI->num_rows){
			while($row = $LastSI->fetch_object()){
				$TotalCSPLSI += $row->csp;
			}
		}else{
			$TotalCSPLSI = 0;
		}
	$Customer	= $dealerUpload->spSelectCustomerTmp1($database);
	$Product	= $dealerUpload->spSelectProductTmp1($database);

	

		// Estimated string length of product description when the TCPDF engine will wrap the text,
		// therfore consuming and extra row
		$productLenThreshold = 26;
		
		// Threshold to determine whether the number of rows per page should be decremented
		// to accomodate product whose length is greater than $productLenThreshold
		$rowDeletionThreshold = 2; 
		
		// Counter of current row deletion threshold
		$deletionThreshold = 0;
		
		// Estimated number of rows per page
		$numRowsPerPage = 28;
		
		$numRows = $numRowsPerPage;
		
		$details = "";
		
		
			//$row = $query->fetch_object();
						
				$details .= "<tr align = 'center'>
								<td>Dealer</td>
								<td>".dlr."</td>
								<td align = 'center'>".number_format($Customer->num_rows)."</td>
								<td align = 'right'>N/A&nbsp;&nbsp;</td>
							</tr>
							<tr align = 'center'>
								<td>Inventory</td>
								<td>".iv."</td>
								<td align = 'center'>".number_format($inventory->num_rows)."</td>
								<td align = 'right'>".number_format($totalSOH,2)."&nbsp;&nbsp;</td>
							</tr>
							<tr align = 'center'>
								<td>Last SI</td>
								<td>".LastSI."</td>
								<td align = 'center'>".number_format($LastSI->num_rows)."&nbsp;&nbsp;</td>
								<td align = 'right'>".number_format($TotalCSPLSI,2)."&nbsp;&nbsp;</td>
							</tr>
							<tr align = 'center'>
								<td>Open SI</td>
								<td>".OpenSI."</td>
								<td align = 'center'>".number_format($OpenSi->num_rows)."</td>
								<td align = 'right'>".number_format($TotalCSPOSI,2)."&nbsp;&nbsp;</td>
							</tr>
							<tr align = 'center'>
								<td>Product File</td>
								<td>".prod_master."</td>
								<td align = 'center'>".$Product->num_rows."</td>
								<td align = 'right'>N/A&nbsp;&nbsp;</td>
							</tr>";
							

			
			
				// We only print the page to PDF if we have enough rows to print
				$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<th>File Type</th>
							<th>File Name</th>
							<th>Total Records&nbsp;Uploaded</th>
							<th>Total Amount</th>
						</tr>' . $details . '</table>';
				$pdf->writeHTML($html, true, false, true, false, "");
				
				
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$details = '';
				$j=0;
				$numRows = $numRowsPerPage;
			
		

		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($details != '') {
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<th>File Type</th>
					<th>File Name</th>
					<th>Total Records&nbsp;Uploaded</th>
					<th>Total Amount</th>
				</tr>' . $details . '</table>';
				$pdf->writeHTML($html, true, false, true, false, "");
		}
	

	
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	ob_start();
	$pdf->Output("StockReport.pdf", "I");

