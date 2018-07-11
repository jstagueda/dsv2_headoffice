<?php
	require_once "../../initialize.php";	
   	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");

	global $database;
	ini_set('max_execution_time', 1000);
	
	$vSearch = $_GET['search'];
	$warehouseid = $_GET['wid'];
	$location = $_GET['lid'];
	$pmgid = $_GET['pmgid'];
	$isId = $_GET['isId'];
	$plid = $_GET['plid'];
	$details = '';
	$body = '';
	
	
	// create new PDF document
	//$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);
	$pdf = new TCPDF("p", PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);

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
		<td height="20" align="center"><strong>Stocks Report</strong></td>
	</tr>
	</table>
	<br>';
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false,true, false, "");
	
	$query = $sp->spSelectStockzPrint($database, $vSearch, $warehouseid, $location, $pmgid, $isId, $plid);
	if($query->num_rows)
	{			
		$j =0; 
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
		while($row = $query->fetch_object()) 
		//for ($k=0;$k<400;$k++)
		{
			//$row = $query->fetch_object();
			if ($j<$numRows) {
				$soh = number_format($row->SOH,0);
				$intransit = number_format($row->InTransit,0);
				if ($intransit == 0)
				{
					$intransit = "-";
				}
				
				$details .= '<tr>
								<td height="20" align="left"> '.$row->Code.'</td>
								<td height="20" align="left"> '.$row->Product.'</td>
								<td height="20" align="center">'.$row->UOMCode.'</td> 
								<td height="20" align="center">'.$row->ProdLineCode.'</td>
								<td height="20" align="center">--</td>
								<td height="20" align="center">'.$row->PMGCode.'</td>	
								<td height="20" align="center">'.$row->StatusCode.'</td>
								<td height="20" align="center">'.$soh.'</td>
								<td height="20" align="center">'.$intransit.'</td>
								<td height="20" align="center">'.$row->DateLastSold.'</td>
								<td height="20" align="center">--</td>	                  
							</tr>';
							
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->Product)>$productLenThreshold) {
				
					// Subtract the number of rows only if we reached threshold of the number
					// of rows whose string length is greater than product length threshold
					if ($deletionThreshold!=$rowDeletionThreshold) {
						$numRows--;
						$deletionThreshold++;
					}
					else {
						// Reset the current count
						$deletionThreshold=0;
					}
				}
				$j++;
			}
			else {
				// We only print the page to PDF if we have enough rows to print
				$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
				<tr align="center">
					<td width="8%" height="20" align="left"><strong>Item Code</strong></td>
					<td width="22%" height="20" align="left"><strong>Item Description</strong></td>
					<td width="6%" height="20" align="center"><strong>UOM</strong></td>
					<td width="10%" height="20" align="center"><strong>Prod Line</strong></td>
					<td width="8%" height="20" align="center"><strong>CSP</strong></td>
					<td width="6%" height="20" align="center"><strong>PMG</strong></td>
					<td width="8%" height="20" align="center"><strong>Inv Status</strong></td>
					<td width="6%" height="20" align="center"><strong>SOH</strong></td>
					<td width="6%" height="20" align="center"><strong>In Transit</strong></td>
					<td width="10%" height="20" align="center"><strong>Date Last Sold</strong></td>
					<td width="10%" height="20" align="center"><strong>Days Not Availed</strong></td>
				</tr>' . $details . '</table>';
				$pdf->writeHTML($html, true, false, true, false, "");
				
				
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$details = '';
				$j=0;
				$numRows = $numRowsPerPage;
			}
		}
		$query->close(); 
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($details != '') {
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
				<tr align="center">
					<td width="8%" height="20" align="left"><strong>Item Code</strong></td>
					<td width="22%" height="20" align="left"><strong>Item Description</strong></td>
					<td width="6%" height="20" align="center"><strong>UOM</strong></td>
					<td width="10%" height="20" align="center"><strong>Prod Line</strong></td>
					<td width="8%" height="20" align="center"><strong>CSP</strong></td>
					<td width="6%" height="20" align="center"><strong>PMG</strong></td>
					<td width="8%" height="20" align="center"><strong>Inv Status</strong></td>
					<td width="6%" height="20" align="center"><strong>SOH</strong></td>
					<td width="6%" height="20" align="center"><strong>In Transit</strong></td>
					<td width="10%" height="20" align="center"><strong>Date Last Sold</strong></td>
					<td width="10%" height="20" align="center"><strong>Days Not Availed</strong></td>
				</tr>' . $details . '</table>';
				$pdf->writeHTML($html, true, false, true, false, "");
		}
	}
	else
	{
		$details = '<tr><td height="20" colspan="11" align="center"><strong>No record(s) to display.</strong></td></tr>';
		
		$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
				<thead>
					<td width="8%" height="20" align="left"><strong>Item Code</strong></td>
					<td width="22%" height="20" align="left"><strong>Item Description</strong></td>
					<td width="6%" height="20" align="center"><strong>UOM</strong></td>
					<td width="10%" height="20" align="center"><strong>Prod Line</strong></td>
					<td width="8%" height="20" align="center"><strong>CSP</strong></td>
					<td width="6%" height="20" align="center"><strong>PMG</strong></td>
					<td width="8%" height="20" align="center"><strong>Inv Status</strong></td>
					<td width="6%" height="20" align="center"><strong>SOH</strong></td>
					<td width="6%" height="20" align="center"><strong>In Transit</strong></td>
					<td width="10%" height="20" align="center"><strong>Date Last Sold</strong></td>
					<td width="10%" height="20" align="center"><strong>Days Not Availed</strong></td>
				</thead>' . $details . '</table>';
		$pdf->writeHTML($html, true, false, true, false, "");
	}
	
	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("StockReport.pdf", "I");
?>