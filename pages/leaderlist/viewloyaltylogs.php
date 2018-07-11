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
		<td height="20" align="center"><strong>Loyalty Logs Report</strong></td>
	</tr>
	</table>
	<br>';
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false,true, false, "");
	
	$query = $database->execute("SELECT lgs.*, us.UserName FROM loyaltylogs lgs
								 INNER JOIN user us ON us.ID = lgs.UserID 
								 ORDER BY lgs.ID DESC");
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
								<td height="20" align="center">'.$row->UserName.'</td>
								<td height="20" align="left"> '.$row->BuyinFileName.'</td>
								<td height="20" align="left"> '.$row->EntitFileName.'</td>
								<td height="20" align="center">'.$row->TotalFileBuyin.'</td> 
								<td height="20" align="center">'.$row->TotalFileEnt.'</td>
								<td height="20" align="center">'.date("m/d/Y",strtotime($row->EnrollmentDate)).'</td>
								<td height="20" align="center">'.date("H:i:s",strtotime($row->EnrollmentDate)).'</td>
							</tr>';
							

			}
			else {
				// We only print the page to PDF if we have enough rows to print
				$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
				<tr align="center">
					<td width="10%" height="20" align="center"><strong>User Name</strong></td>
					<td width="25%" height="20" align="center"><strong>Buyin File Name</strong></td>
					<td width="25%" height="20" align="center"><strong>Entitlement File Name</strong></td>
					<td width="10%" height="20" align="center"><strong>Total Count Buyin</strong></td>
					<td width="10%" height="20" align="center"><strong>Total Count Entitlement</strong></td>
					<td width="10%" height="20" align="center"><strong>Date Upload</strong></td>
					<td width="10%" height="20" align="center"><strong>Time Upload</strong></td>
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
					<td width="10%" height="20" align="center"><strong>User Name</strong></td>
					<td width="25%" height="20" align="center"><strong>Buyin File Name</strong></td>
					<td width="25%" height="20" align="center"><strong>Entitlement File Name</strong></td>
					<td width="10%" height="20" align="center"><strong>Total Count Buyin</strong></td>
					<td width="10%" height="20" align="center"><strong>Total Count Entitlement</strong></td>
					<td width="10%" height="20" align="center"><strong>Date Upload</strong></td>
					<td width="10%" height="20" align="center"><strong>Time Upload</strong></td>
				</tr>' . $details . '</table>';
				$pdf->writeHTML($html, true, false, true, false, "");
		}
	}
	else
	{
		$details = '<tr><td height="20" colspan="11" align="center"><strong>No record(s) to display.</strong></td></tr>';
		
		$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
				<tr align="center">
					<td width="10%" height="20" align="center"><strong>User Name</strong></td>
					<td width="25%" height="20" align="center"><strong>Buyin File Name</strong></td>
					<td width="25%" height="20" align="center"><strong>Entitlement File Name</strong></td>
					<td width="10%" height="20" align="center"><strong>Total Count Buyin</strong></td>
					<td width="10%" height="20" align="center"><strong>Total Count Entitlement</strong></td>
					<td width="10%" height="20" align="center"><strong>Date Upload</strong></td>
					<td width="10%" height="20" align="center"><strong>Time Upload</strong></td>
				</tr>' . $details . '</table>';
		$pdf->writeHTML($html, true, false, true, false, "");
	}
	
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	ob_start();
	$pdf->Output("StockReport.pdf", "I");

