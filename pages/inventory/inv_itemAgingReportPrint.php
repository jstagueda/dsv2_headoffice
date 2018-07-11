<?PHP 
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
		
	global $database;
	ini_set('max_execution_time', 1000);
	
	$html = "";
	$details = "";
	//echo"test";
	//die();
	if(isset($_GET['wid'])){
		$wid = $_GET['wid'];
	}else{
		$wid = 0;
	}
	
	if(isset($_GET['plid'])){
		$plid = $_GET['plid'];
	}
	else{
		$plid = 0;
	}
	
	if(isset($_GET['sid'])){
		$sid = $_GET['sid'];
	}else{
		$sid = 0;
	}
	
	if (isset($_GET['code'])){
		$code  = $_GET['code'];
	}else{
		$code  = "";
	}
	
	if(isset($_GET['age'])){
		$age = $_GET['age'];
	}else{
		$age = 0;
	}
	
	if(isset($_GET['dteAsOf'])){
		$dteAsOf = $_GET['dteAsOf'];	
	}else{
		$dteAsOf = '01/01/1970';
	}
	
	$tmpDate = strtotime($dteAsOf);
	$newdate = date("Y-m-d", $tmpDate);
	
	// create new PDF document
	// $pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);
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
          		<td height="20" align="center"><strong>Item Aging Report</strong></td>
        	</tr>
      		</table>
			<br>';
			
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");
	
	$query = $sp->spSelectItemAgingReportv1($database, 0, 0, $wid, $plid, $code, $sid, $age, 2);

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
	                  			<td width="10%" height="20" align="center">'.$row->ProdLineCode.'</td>
	                  			<td width="10%" height="20" align="center">'.$row->ItemCode.'</td>
	                  			<td width="34%" height="20" align="left">&nbsp;'.$row->ItemDescription.'</td> 
	                  			<td width="20%" height="20" align="center">'.$row->Status.'</td>
	                  			<td width="12%" height="20" align="center">'.$row->SOH.'</td>
	                  			<td width="12%" height="20" align="right">'.$age.'</td>
	                  		</tr>';
	  						
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->ItemDescription) > $productLenThreshold) 
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
	                  			<td width="10%" height="20" align="center">'.$row->ProdLineCode.'</td>
	                  			<td width="10%" height="20" align="center">'.$row->ItemCode.'</td>
	                  			<td width="34%" height="20" align="left">&nbsp;'.$row->ItemDescription.'</td> 
	                  			<td width="20%" height="20" align="center">'.$row->Status.'</td>
	                  			<td width="12%" height="20" align="center">'.$row->SOH.'</td>
	                  			<td width="12%" height="20" align="right">'.$age.'</td>
	                  		</tr>';
				$html = '<table width="100%"  border="1" cellpadding="0" cellspacing="0">
			            <tr align="center">
			               <td width="10%" height="20" align="center"><strong>Product Line</strong></td>
			               <td width="10%" height="20" align="center"><strong>Item Code</strong></td>
						   <td width="34%" height="20" align="center"><strong>Item Description</strong></td>
						   <td width="20%" height="20" align="center"><strong>Inventory Status</strong></td>
						   <td width="12%" height="20" align="center"><strong>SOH Balance</strong></td>
						   <td width="12%" height="20" align="center"><strong>Age</strong></td>
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
			               <td width="10%" height="20" align="center"><strong>Product Line</strong></td>
			               <td width="10%" height="20" align="center"><strong>Item Code</strong></td>
						   <td width="34%" height="20" align="center"><strong>Item Description</strong></td>
						   <td width="20%" height="20" align="center"><strong>Inventory Status</strong></td>
						   <td width="12%" height="20" align="center"><strong>SOH Balance</strong></td>
						   <td width="12%" height="20" align="center"><strong>Age</strong></td>
						</tr>'.$details
					 .'</table>';
			$pdf->writeHTML($html, true, false, true, false, "");			
		}
	}
	else
	{
		$details = '<tr ><td height="20" colspan="6" align="center"><strong> No record(s) to display. <strong></td></tr>';
		$html =  '<table width="100%"  border="1" cellpadding="0" cellspacing="0">
		            <tr align="center">
		               <td width="10%" height="20" align="center"><strong>Product Line</strong></td>
		               <td width="10%" height="20" align="center"><strong>Item Code</strong></td>
					   <td width="34%" height="20" align="center"><strong>Item Description</strong></td>
					   <td width="20%" height="20" align="center"><strong>Inventory Status</strong></td>
					   <td width="12%" height="20" align="center"><strong>SOH Balance</strong></td>
					   <td width="12%" height="20" align="center"><strong>Age</strong></td>
					</tr>'.$details.
					'</table>';
	       		
   		$pdf->writeHTML($html, true, false, true, false, "");
	}
	
	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("ItemAgingReport.pdf", "I");	
?>