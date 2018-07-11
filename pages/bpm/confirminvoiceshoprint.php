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
	$tperdate	 = $_GET['tperdate'];
	$tperbranch	 = $_GET['tperbranch'];
	
	$tmpftxndate = strtotime($StartDate);
	$fromDate = date("Y-m-d", $tmpftxndate);	
	
	$tmpttxndate = strtotime($EndDate. " +1 day");
	$toDate = date("Y-m-d", $tmpttxndate);
	$txnRegister = '';
	
	// $SIRegisterPrint = $tpiSalesReport->spSelectSIRegisterPrint($database, $fromDate, $toDate, $BranchID);
	$SelectSIConfirmInvoices = $tpiSalesReport->spSelectSIConfirmInvoices($database, $fromDate, $toDate, $BranchID, $DealerFrom, $DealerTo);
	//$rs_emp_total 	 = $tpiSalesReport->spSelectCreatedBySalesInvoice($database, $fromDate, $toDate, $BranchID);
	$num = $SelectSIConfirmInvoices->num_rows;
	
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
	
	$header = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
				<tr>
	    			<td height="20" align="center" width="10%"><strong>Sales Order No.</strong></td>
	    			<td height="20" align="center" width="10%"><strong>Invoice No.</strong></td>
		          	<td height="20" align="center" width="7%" ><strong>Dealer Code</strong></td>
		          	<td height="20" align="center" width="10%"><strong>Dealer Name</strong></td>
		          	<td height="20" align="center" width="11%"><strong>Total CSP Amount</strong></td>
		          	<td height="20" align="center" width="11%"><strong>Total DGS Amount</strong></td>
		          	<td height="20" align="center" width="11%"><strong>Total Invoice Amount</strong></td>
		          	<td height="20" align="center" width="10%"><strong>Net Sales Value</strong></td>
		          	<td height="20" align="center" width="10%"><strong>Transaction Date</strong></td>
		          	<td height="20" align="center" width="10%" ><strong>Is Advance PO</strong></td>

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
		
		while($row = $SelectSIConfirmInvoices->fetch_object()){	 
			$OrderNo = $row->OrderNo;
			$SalesInvoice = $row->SalesInvoice;
			$DealerCode = $row->DealerCode;
			$DealerName = $row->DealerName;
			$TotalCSPAmount = $row->TotalCSPAmount;
			$TotalDGSAmount = $row->TotalDGSAmount;
			$TotalInvoiceAmount = $row->TotalInvoiceAmount;
			$TransactionDueDate = $row->TransactionDueDate;
			$IsAdvanced = $row->IsAdvanced;
			$campaignPrice = $row->CampaignPrice;
			$basicDicsount = $row->BasicDiscount;
			$addtnlDiscount = $row->AddtlDiscount;
			$addtlDiscountPrev = $row->AddtlDiscountPrev;
			$saleswithvat = $campaignPrice - $basicDicsount - $addtnlDiscount;
			$amountwovat = $saleswithvat / 1.12;           
			$netSalesValue = $amountwovat - $addtlDiscountPrev;
			
			
			
			if($IsAdvanced==0){$advance =  "Regular PO";}else{$advance = "Advance PO";};
			$cnt ++;
			 if ($j < $numRows){
				$txnRegister .= '<tr>
									<td height="20" align="center" width="10%"><strong>'.$OrderNo.'</strong></td>
									<td height="20" align="center" width="10%"><strong>'.$SalesInvoice.'</strong></td>
									<td height="20" align="center" width="7%" ><strong>'.$DealerCode.'</strong></td>
									<td height="20" align="center" width="10%"><strong>'.$DealerName.'</strong></td>
									<td height="20" align="center" width="11%"><strong>'.number_format($TotalCSPAmount,2).'</strong></td>
									<td height="20" align="center" width="11%"><strong>'.number_format($TotalDGSAmount,2).'</strong></td>
									<td height="20" align="center" width="11%"><strong>'.number_format($TotalInvoiceAmount,2).'</strong></td>
									<td height="20" align="center" width="10%"><strong>'.number_format($netSalesValue,2).'</strong></td>
									<td height="20" align="center" width="10%"><strong>'.date("d/m/Y",strtotime($TransactionDueDate)).'</strong></td>
									<td height="20" align="center" width="10%" ><strong>'.$advance.'</strong></td>
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
			  	$html = $header.''.$txnRegister.'</table>';  
	  			  
			 	// We only print the page to PDF if we have enough rows to print
				if($j == $numRows){
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
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, "");
		
		if($_GET['tperbranch'] == 1){
		$q = $tpiSalesReport->spTotalSIConfirmInvoicesBranches($database, $fromDate, $toDate, $BranchID, $DealerFrom, $DealerTo);
		
				$html = '<br />
						<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
							<tr>
								<td height="20" colspan = "2" width="43%"><strong>With Total per Branch</strong></td>
							</tr>
							<tr>
								<td height="20" align="center" width="11%"><strong>Total DGS Amount</strong></td>
								<td height="20" align="center" width="11%"><strong>Total Invoice Amount</strong></td>
								<td height="20" align="center" width="10%"><strong>Net Sales Value</strong></td>
								<td height="20" align="center" width="11%"><strong>Total CSP Amount</strong></td>
							</tr>';
				if($q->num_rows){
					while($r = $q->fetch_object()){

						$TotalCSPAmount 		 = $r->TotalCSPAmount;
						$TotalDGSAmount			 = $r->TotalDGSAmount;
						$TotalInvoiceAmount		 = $r->TotalInvoiceAmount;
						$netSalesValuex 		 = $r->AmountWOVat - $r->VatAmount;
						
						$html .= '	
							<tr>
								<td height="20" align="center" width="11%"><strong>'.number_format($TotalDGSAmount,2).'</strong></td>
								<td height="20" align="center" width="11%"><strong>'.number_format($TotalInvoiceAmount,2).'</strong></td>
								<td height="20" align="center" width="10%"><strong>'.number_format($netSalesValuex,2).'</strong></td>
								<td height="20" align="center" width="11%"><strong>'.number_format($TotalCSPAmount,2).'</strong></td>
							</tr>
							';
					}
				}
				$html1 = $html."</table>";
				if ($j != 13){
					$pdf->writeHTML($html1, true, false, true, false, "");
					$j += 4;
				}else{
				
					$j += 4;
					$pdf->AddPage();
					$pdf->writeHTML($html1, true, false, true, false, "");
				}
			}
	 } $SelectSIConfirmInvoices->close();
	 
	if($_GET['tperdate'] == 1){
		$q = $tpiSalesReport->spTotalSIConfirmInvoicesDate($database, $fromDate, $toDate, $BranchID, $DealerFrom, $DealerTo);
		$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
						<tr>
							<td height="20" colspan = "2" width="43%"><strong>With Total per Date</strong></td>
						</tr>
						<tr>
							<td height="20" align="center" width="11%"><strong>Total DGS Amount</strong></td>
							<td height="20" align="center" width="11%"><strong>Total Invoice Amount</strong></td>
							<td height="20" align="center" width="10%"><strong>Net Sales Value</strong></td>
							<td height="20" align="center" width="11%"><strong>Total CSP Amount</strong></td>
						</tr>';
				if($q->num_rows){
					while($r = $q->fetch_object()){

						$TotalCSPAmount 		 = $r->TotalCSPAmount;
						$TotalDGSAmount			 = $r->TotalDGSAmount;
						$TotalInvoiceAmount		 = $r->TotalInvoiceAmount;
						$netSalesValuex 		 = $r->AmountWOVat - $r->VatAmount;
						
						$html .= '<tr>
									<td height="20" align="center" width="11%"><strong>'.number_format($TotalDGSAmount,2).'</strong></td>
									<td height="20" align="center" width="11%"><strong>'.number_format($TotalInvoiceAmount,2).'</strong></td>
									<td height="20" align="center" width="10%"><strong>'.number_format($netSalesValuex,2).'</strong></td>
									<td height="20" align="center" width="11%"><strong>'.number_format($TotalCSPAmount,2).'</strong></td>
								 </tr>';
					}
				}
				
			$html1 = $html."</table>";
				if ($j != 13){
					$pdf->writeHTML($html1, true, false, true, false, "");
					$j += 4;
				}else{
				
					$j += 4;
					$pdf->AddPage();
					$pdf->writeHTML($html1, true, false, true, false, "");
				}
	}
	// reset pointer to the last page
	$pdf->lastPage();
	// Close and output PDF document
	$pdf->Output("SIRegister.pdf", "I");	
?>