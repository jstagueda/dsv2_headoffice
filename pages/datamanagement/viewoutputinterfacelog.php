<?php	
	require_once "../../initialize.php";	
   	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	
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
		<td height="20" align="center"><strong>Output Interface Generator Logs Report</strong></td>
	</tr>
	</table>
	<br>';
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false,true, false, "");
        
        //@author: jdymosco June, 29, 2013....
        //Added date picked for logs to be displayed 
	$lastrun = date("Y-m-d", strtotime($_GET['date']));
	$query = $database->execute("select a.*, b.Description, c.Name BranchName from outputinterfacelogs a
								  INNER JOIN outputfiletype b on a.FileTypeID = b.ID
								  INNER JOIN branch c on c.ID = a.BranchID where a.TransactionDate = '".$lastrun."'");
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
				$details .= '<tr>
								<td height="20" align="center">'.$row->BranchName.'</td>
								<td height="20" align="left"> '.date("m/d/Y",strtotime($row->TransactionDate)).'</td>
								<td height="20" align="center">'.date("m/d/Y",strtotime($row->DateGenerated)).'</td> 
								<td height="20" align="center">'.$row->FileName.'</td>
								<td height="20" align="center">'.$row->Description.'</td>
								<td height="20" align="center">'.$row->TotalNoRecords.'</td>
							</tr>';
							

			}
			else {
				// We only print the page to PDF if we have enough rows to print
				$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
				<tr align="center">
					<td width="10%" height="20" align="center"><strong>BRANCH</strong></td>
					<td width="10%" height="20" align="center"><strong>TRANSACTION DATE</strong></td>
					<td width="10%" height="20" align="center"><strong>DATE GENERATED</strong></td>
					<td width="30%" height="20" align="center"><strong>FILE NAME</strong></td>
					<td width="30%" height="20" align="center"><strong>FILE TYPE</strong></td>
					<td width="10%" height="20" align="center"><strong>TOTAL NO. RECORDS</strong></td>
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
					<td width="10%" height="20" align="center"><strong>BRANCH</strong></td>
					<td width="10%" height="20" align="center"><strong>TRANSACTION DATE</strong></td>
					<td width="10%" height="20" align="center"><strong>DATE GENERATED</strong></td>
					<td width="30%" height="20" align="center"><strong>FILE NAME</strong></td>
					<td width="30%" height="20" align="center"><strong>FILE TYPE</strong></td>
					<td width="10%" height="20" align="center"><strong>TOTAL NO. RECORDS</strong></td>
				</tr>' . $details . '</table>';
				$pdf->writeHTML($html, true, false, true, false, "");
		}
	}
	else
	{
		$details = '<tr><td height="20" colspan="11" align="center"><strong>No record(s) to display.</strong></td></tr>';
		
		$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
				<tr align="center">
					<td width="10%" height="20" align="center"><strong>BRANCH</strong></td>
					<td width="10%" height="20" align="center"><strong>TRANSACTION DATE</strong></td>
					<td width="10%" height="20" align="center"><strong>DATE GENERATED</strong></td>
					<td width="30%" height="20" align="center"><strong>FILE NAME</strong></td>
					<td width="30%" height="20" align="center"><strong>FILE TYPE</strong></td>
					<td width="10%" height="20" align="center"><strong>TOTAL NO. RECORDS</strong></td>
				</tr>' . $details . '</table>';
		$pdf->writeHTML($html, true, false, true, false, "");
	}
	
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	ob_start();
	$pdf->Output("StockReport.pdf", "I");

