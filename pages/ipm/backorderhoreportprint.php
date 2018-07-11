<?PHP 
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");	
	include CS_PATH.DS."ClassSalesReport.php";
	global $database;
	ini_set('max_execution_time', 1000);
	$cnt = 0;
	$BranchID 	 = $_GET['BranchID'];
	$StartDate 	 = $_GET['StartDate'];
	$EndDate 	 = $_GET['EndDate'];
	$DealerFrom  = $_GET['DealerFrom'];
	$DealerTo	 = $_GET['DealerTo'];
	$productfrom	 = $_GET['productfrom'];
	$productto	 = $_GET['productto'];
	
	$tmpftxndate = strtotime($StartDate);
	$fromDate = date("Y-m-d", $tmpftxndate);	
	
	$tmpttxndate = strtotime($EndDate. " +1 day");
	$toDate = date("Y-m-d", $tmpttxndate);
	$txnRegister = '';
	
	// $SIRegisterPrint = $tpiSalesReport->spSelectSIRegisterPrint($database, $fromDate, $toDate, $BranchID);
	$BackOrderReport = $tpiSalesReport->spSelectBackOrderReport($database, $fromDate, $toDate, $BranchID, $DealerFrom, $DealerTo, $productfrom, $productto);
	//$rs_emp_total 	 = $tpiSalesReport->spSelectCreatedBySalesInvoice($database, $fromDate, $toDate, $BranchID);
	$num = $BackOrderReport->num_rows;
	
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
	$pdf->SetFont("courier", "", 7);
	// add a page
	$pdf->AddPage();
	/* Customer Code ,Customer Name ,Product Code, Product Description, Sales Order No., Sales Invoice No., Transaction Date, Qty Ordered ,Qty Served, BackOrder Qty */
	$header = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
				<tr>
	    			<td height="20" align="center" width="10%"><strong>Customer Code</strong></td>
	    			<td height="20" align="center" width="10%"><strong>Customer Name</strong></td>
		          	<td height="20" align="center" width="7%" ><strong>Product Code</strong></td>
		          	<td height="20" align="center" width="19%"><strong>Product Description</strong></td>
		          	<td height="20" align="center" width="11%"><strong>Sales Order No.</strong></td>
		          	<td height="20" align="center" width="11%"><strong>Sales Invoice No.</strong></td>
		          	<td height="20" align="center" width="11%"><strong>Transaction Date</strong></td>
		          	<td height="20" align="center" width="7%" ><strong>Qty Ordered</strong></td>
		          	<td height="20" align="center" width="7%" ><strong>Qty Served</strong></td>
		          	<td height="20" align="center" width="7%" ><strong>BackOrder Qty</strong></td>

	  			</tr>';
	
	$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td height="20" align="center"><strong>Confirm Register</strong></td>
				</tr>
			</table>
  			<br />
  			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">							
				<tr>
					<td width="10%" height="20" align="right"><strong>From Date :   </strong></td>
					<td width="90%" height="20" align="left">&nbsp;'.$_GET['StartDate'].'</td>
				</tr>
				<tr>
					<td height="20" align="right"><strong>To Date :   </strong></td>
					<td height="20" align="left">&nbsp;'.$_GET['EndDate'].'</td>
				</tr>
			</table>
			<br />';
	
	// Print text using writeHTML()
	
	$pdf->writeHTML($html, true, false, true, false, "");
	if($num != 0){
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
		$numRowsPerPage = 15;
		
		$numRows = $numRowsPerPage;
		
		while($row = $BackOrderReport->fetch_object()){	 

			$cnt ++;
			if ($j < $numRows){
				$txnRegister .= '<tr>
										<td height="20" align="center" width="10%"><strong>'.$row->DealerCode.'</strong></td>
										<td height="20" align="center" width="10%"><strong>'.$row->DealerName.'</strong></td>
										<td height="20" align="center" width="7%" ><strong>'.$row->ProductCode.'</strong></td>
										<td height="20" align="center" width="19%"><strong>'.$row->ProductName.'</strong></td>
										<td height="20" align="center" width="11%"><strong>'.$row->OrderNo.'</strong></td>
										<td height="20" align="center" width="11%"><strong>'.$row->ID.'</strong></td>
										<td height="20" align="center" width="11%"><strong>'.$row->TransactionDate.'</strong></td>
										<td height="20" align="center" width="7%" ><strong>'.$row->QtyOrdered.'</strong></td>
										<td height="20" align="center" width="7%" ><strong>'.$row->QtyServed.'</strong></td>
										<td height="20" align="center" width="7%" ><strong>'.$row->BackOrderQty.'</strong></td>
								</tr>';					

				
			 	// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($OrderNo) > $productLenThreshold){
					// Subtract the number of rows only if we reached threshold of the number
					// of rows whose string length is greater than product length threshold
					if ($deletionThreshold != $rowDeletionThreshold){
						$numRows--;
						$deletionThreshold++;
					}
					else{
						// Reset the current count
						$deletionThreshold = 0;
					}
				}
				$j++;			 	
			}
	  			  
			// We only print the page to PDF if we have enough rows to print
			if($j == $numRows){
				$html = $txnRegister.'</table>';  
				$pdf->writeHTML($html, true, false, true, false, "");
				$pdf->AddPage();
				// reset the row counter and the details 
				$txnRegister = '';
				$j = 0;
				$numRows = $numRowsPerPage;
			}
		}
		if ($txnRegister != ''){
			$html = $header.''.$txnRegister; 
		}else{
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">';
		}
	}
	$html .= '</table>';
	$pdf->writeHTML($html, true, false, true, false, "");
	// reset pointer to the last page
	$pdf->lastPage();
	// Close and output PDF document
	$pdf->Output("backorderreport.pdf", "I");	
?>