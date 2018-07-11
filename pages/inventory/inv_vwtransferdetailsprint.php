<?PHP 
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
		
	include IN_PATH.DS."scViewTransferDetails.php";
	global $database;
	ini_set('max_execution_time', 1000);
	
	$ctr = 0;
	$html = "";
	$transferDetails = "";
	
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
				<td height="20" align="left">&nbsp;<strong>Inventory Transfer</strong>&nbsp;&nbsp;<strong>Reference No.:</strong>&nbsp;'.$txnno.'&nbsp;&nbsp;<strong>Transaction Date :   </strong>'.$txndate.'</td>
			</tr>
			</table><br>';
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");	
		
	if ($rs_detailsall->num_rows)
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
		$numRowsPerPage = 25;
		
		$numRows = $numRowsPerPage;
		
		while ($row = $rs_detailsall->fetch_object())
		{
			$ctr += 1;
			$amount = number_format($row->Quantity, 0, ".", ",");
			
			if ($j < $numRows)
			{
				$transferDetails .= '<tr align="center"> 
						  				<td height="20" align="center">'.$ctr.'</td>
						  				<td height="20" align="left"> '.$row->ProductCode.'</td>
						  				<td height="20" align="left"> '.$row->ProductName.'</td>
						  				<td height="20" align="right">'.$row->PrevBalance.' </td>
						  				<td height="20" align="center">'.$row->uom.'</td>
						  				<td height="20" align="right">'.$amount. ' </td>
						  				<td height="20" align="center">'.$row->reasons.'</td>
						  			</tr>';
						  			
  				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->ProductName) > $productLenThreshold) 
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
				$transferDetails .= '<tr align="center"> 
						  				<td height="20" align="center">'.$ctr.'</td>
						  				<td height="20" align="left"> '.$row->ProductCode.'</td>
						  				<td height="20" align="left"> '.$row->ProductName.'</td>
						  				<td height="20" align="right">'.$row->PrevBalance.' </td>
						  				<td height="20" align="center">'.$row->uom.'</td>
						  				<td height="20" align="right">'.$amount. ' </td>
						  				<td height="20" align="center">'.$row->reasons.'</td>
						  			</tr>';
						  			
	  			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
						<tr>
				    		<td width="5%"  height="20" align="center"><strong>Line No.</strong></td>
					   		<td width="15%" height="20" align="center"><strong>Item Code</strong></td>
							<td width="35%" height="20" align="left"> <strong>Item Description</strong></td>
							<td width="10%" height="20" align="center"><strong>SOH</strong></td>
							<td width="10%" height="20" align="center"><strong>UOM</strong></td>
							<td width="10%" height="20" align="center"><strong>Qty</strong></td>
							<td width="15%" height="20" align="center"><strong>Reason</strong></td>
						</tr>'.$transferDetails.'</table>';
						  			
  				// We only print the page to PDF if we have enough rows to print
	      		$pdf->writeHTML($html, true, false, true, false, "");
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$transferDetails = '';
				$j = 0;
				$numRows = $numRowsPerPage; 					
			}
		}
		$rs_detailsall->close();
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($transferDetails != '')
		{
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
						<tr>
				    		<td width="5%"  height="20" align="center"><strong>Line No.</strong></td>
					   		<td width="15%" height="20" align="center"><strong>Item Code</strong></td>
							<td width="35%" height="20" align="left"> <strong>Item Description</strong></td>
							<td width="10%" height="20" align="center"><strong>SOH</strong></td>
							<td width="10%" height="20" align="center"><strong>UOM</strong></td>
							<td width="10%" height="20" align="center"><strong>Qty</strong></td>
							<td width="15%" height="20" align="center"><strong>Reason</strong></td>
						</tr>'.$transferDetails.'</table>';
		}
		
		$html .= '<br>
				<br>
				<br>
				<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
	 			<tr align="left">
	    			<td height="20" width="20%"><strong>&nbsp;Prepared By :</strong></td>
	    			<td height="20" width="85%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ___________________________ </td>	
	      		</tr>
	      		<tr align="left">
	        		<td height="20"></td>
	        		<td height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Warehouse Clerk</td>                		
	      		</tr>
	       		<tr align="left">
	        		<td height="20"><strong>&nbsp;Approved By :</strong></td>
	        		<td height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ___________________________ </td>
	        	</tr>
	        	<tr align="left">
	        		<td height="20"></td>
	        		<td height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Warehouse Supervisor </td>                		
	      		</tr>
	         	</table>';
	         	
 		$pdf->writeHTML($html, true, false, true, false, "");
	}
	else
	{
		$transferDetails .= '<tr align="center"><td height="20" colspan="7"><strong>No record(s) to display. </strong></td></tr>';
	}
	
	/*$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        	<tr>
          		<td height="20" align="center"><strong>Inventory Transfer</strong></td>
        	</tr>
        	</table>
        	<br>
        	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          	<tr>
        		<td height="20" align="left"><strong>&nbsp;General Information</strong></td>
      		</tr>
    		</table>
    		<br>
			<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
			<tr>
	    		<td width="20%" height="20" align="right"><strong>Reference No. :   </strong></td>
	    		<td width="30%" height="20" align="left"> '.$txnno.'</td>
	    		<td width="20%" height="20" align="right"><strong>Transaction Date :   </strong></td>
	    		<td width="30%" height="20" align="left"> '.$txndate.'</td>
			</tr>			
			</table>
			<br>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          	<tr>
       	 		<td height="20" align="left"> <strong>Transaction Details</strong></td>
          	</tr>
         	</table>
	    	<br>
			<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
			<tr>
	    		<td width="5%"  height="20" align="center"><strong>Line No.</strong></td>
		   		<td width="15%" height="20" align="center"><strong>Item Code</strong></td>
				<td width="35%" height="20" align="left"> <strong>Item Description</strong></td>
				<td width="10%" height="20" align="center"><strong>SOH</strong></td>
				<td width="10%" height="20" align="center"><strong>UOM</strong></td>
				<td width="10%" height="20" align="center"><strong>Qty</strong></td>
				<td width="15%" height="20" align="center"><strong>Reason</strong></td>
			</tr>'.$transferDetails.
			'</table>
		 	<br>
			<br>
			<br>
			<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
 			<tr align="left">
    			<td height="20" width="15%"><strong>&nbsp;Prepared By :</strong></td>
    			<td height="20" width="85%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ___________________________ </td>	
      		</tr>
      		<tr align="left">
        		<td height="20"></td>
        		<td height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Warehouse Clerk</td>                		
      		</tr>
       		<tr align="left">
        		<td height="20"><strong>&nbsp;Approved By :</strong></td>
        		<td height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ___________________________ </td>
        	</tr>
        	<tr align="left">
        		<td height="20"></td>
        		<td height="20">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Warehouse Supervisor </td>                		
      		</tr>
         	</table>';*/
         	
	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("TransferList.pdf", "I");	
?>