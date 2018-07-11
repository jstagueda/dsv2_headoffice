<?PHP 
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	ini_set('max_execution_time', 1000);

	global $database;
	
	$html = "";
	$details = "";
	
	if(isset($_GET['fdate']))
	{
		$fdate = $_GET['fdate'];
	}
	else
	{
		$fdate = '';
	}
	
	if(isset($_GET['tdate']))
	{
		$tdate = $_GET['tdate'];
	}
	else
	{
		$tdate = '';
	}
	
	if(isset($_GET['ftime']))
	{
		$ftime = $_GET['ftime'];
	}
	else
	{
		$ftime = '';
	}
	
	if (isset($_GET['ttime']))
	{
		$ttime  = $_GET['ttime'];
	}
	else 
	{
		$ttime  = '';
	}
	
	if(isset($_GET['product']))
	{
		$product = $_GET['product'];
	}
	else
	{
		$product = '';
	}
	
	
	$tmpFDate = strtotime($fdate);
	$fromdate = date("Y-m-d", $tmpFDate);
	$tmpTDate = strtotime($tdate);
	$todate = date("Y-m-d", $tmpTDate);
	
	// create new PDF document
	$pdf = new TCPDF("P", PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);

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
		
	$html = '<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
        	<tr>
          		<td height="20" align="center"><strong>Top-selling Products Report</strong></td>
        	</tr>
      		</table>
			<br>';
			
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");

	$html = '';	
	$query = $sp->spSelectTopSellingProducts($database, 3, 0, 0, $fromdate, $todate, $ftime, $ttime, $product);

	if ($query->num_rows)
	{
		$j = 0; 
		// Estimated string length of product description when the TCPDF engine will wrap the text,
		// therfore consuming and extra row
		$productLenThreshold = 24;
		
		// Threshold to determine whether the number of rows per page should be decremented
		// to accomodate product whose length is greater than $productLenThreshold
		$rowDeletionThreshold = 2; 
		
		// Counter of current row deletion threshold
		$deletionThreshold = 0;
		
		// Estimated number of rows per page
		$numRowsPerPage = 26;
		
		$numRows = $numRowsPerPage;
		
		$ctr = 0;
		
		while ($row = $query->fetch_object())
		{
			$ctr += 1;
			
			if ($j < $numRows)
			{
				$details .= '<tr align="center">
	                  			<td width="40%" height="20" align="left">'.$row->Product.'</td>
	                  			<td width="20%" height="20" align="right">'.$row->TotalQty.'&nbsp;</td>
	                  			<td width="20%" height="20" align="right">'.number_format($row->TotalDGS, 2).'&nbsp;</td>
	                  			<td width="20%" height="20" align="right">'.$row->SOH.'&nbsp;</td> 
	                  		</tr>';
	  						
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->Product) > $productLenThreshold) 
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
				// We only print the page to PDF if we have enough rows to print
				$details .= '<tr align="center">
	                  			<td width="40%" height="20" align="left">'.$row->Product.'</td>
	                  			<td width="20%" height="20" align="right">'.$row->TotalQty.'&nbsp;</td>
	                  			<td width="20%" height="20" align="right">'.number_format($row->TotalDGS, 2).'&nbsp;</td>
	                  			<td width="20%" height="20" align="right">'.$row->SOH.'&nbsp;</td> 
	                  		</tr>';
				$html = '<table width="100%"  border="1" cellpadding="0" cellspacing="0">
			            <tr align="center">
			              <td width="40%" height="20" align="left"><strong>Item Code and Description</strong></td>
			              <td width="20%" height="20" align="right"><strong>Units Sold</strong></td>
						  <td width="20%" height="20" align="right"><strong>Total DGS</strong></td>
						  <td width="20%" height="20" align="right"><strong>Current SOH Balance</strong></td>
						</tr>'.$details.
						'</table>';
	       		
				$pdf->writeHTML($html, true, false, true, false, "");
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$details = '';
				$j = 0;
				$numRows = $numRowsPerPage;				
			}
		}
		$query->close();
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($details != '')
		{
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
						<tr align="center">
			              <td width="40%" height="20" align="left"><strong>Item Code and Description</strong></td>
			              <td width="20%" height="20" align="right"><strong>Units Sold</strong></td>
						  <td width="20%" height="20" align="right"><strong>Total DGS</strong></td>
						  <td width="20%" height="20" align="right"><strong>Current SOH Balance</strong></td>
						</tr>'.$details
					 .'</table>';
			$pdf->writeHTML($html, true, false, true, false, "");			
		}
	}
	else
	{
		$details = '<tr ><td height="20" colspan="4" align="center"><strong> No record(s) to display. <strong></td></tr>';
		$html =  '<table width="100%"  border="1" cellpadding="0" cellspacing="0">
		            <tr align="center">
		              <td width="40%" height="20" align="left"><strong>Item Code and Description</strong></td>
		              <td width="20%" height="20" align="right"><strong>Units Sold</strong></td>
					  <td width="20%" height="20" align="right"><strong>Total DGS</strong></td>
					  <td width="20%" height="20" align="right"><strong>Current SOH Balance</strong></td>
					</tr>'.$details.
					'</table>';
	       		
   		$pdf->writeHTML($html, true, false, true, false, "");
	}
	
	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("TopSellingProducts.pdf", "I");	
?>