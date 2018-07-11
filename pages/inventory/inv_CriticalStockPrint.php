<?php
	/*
		@Author: Gino C. Leabres;
		@Date Created: 5/30/2013;
	*/
	require_once "../../initialize.php";
	include CS_PATH.DS.'ClassInventory.php';
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	global $database;
	ini_set('max_execution_time', 1000);
	//Variables
	$type		 = '';
	$num 		 = 0;
	$pageNum 	 = 1;
	$warehouseid = 0;
	$productid	 = 0;
	$pmgid	     = 0;
	$pcode	 	 = '';
	$invstatus	 = 0;
	$qtyfrom	 = 0;
	$qtyto		 = 0;
	
	if (isset($_POST["cbowarehouse"]))			$warehouseid =	$_POST["cbowarehouse"];
	if (isset($_POST["cboproductline"]))	    $productid	 =	$_POST["cboproductline"];
	if (isset($_POST["cbopmg"]))	       		$pmgid	     =	$_POST["cbopmg"];
	if (isset($_POST["txtitemcode"]))	    	$pcode	 	 =	$_POST["txtitemcode"];
	if (isset($_POST["cboStatus"]))     		$invstatus	 =	$_POST["cboStatus"];
	if (isset($_POST["txtqtyfrom"]))       		$qtyfrom	 =	$_POST["txtqtyfrom"];
	if (isset($_POST["txtqtyto"]))	        	$qtyto		 =	$_POST["txtqtyto"];
	
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
		
	$CriticalStocks = '<table width="100%" border="" align="left" cellpadding="0" cellspacing="0">
							<tr>
								<td height="20" align="center"><strong>Critical Stock / Out of Stock Report</strong></td>
							</tr>
					   </table>
					   <br />';
	
	$header = '<table width="100%" border="1" align="left" cellpadding="0" cellspacing="0">
						<tr align="center">
								<td  align="left" 	 height="20" width = "10%"><strong>Product Line</strong></td>
								<td  align="left" 	 height="20" width = "10%"><strong>Item Code</strong></td>
								<td  align="center"  height="20" width = "20%"><strong>Item Description</strong></td>
								<td  align="center"  height="20" width = "5%" ><strong>PMG</strong></td>
								<td  align="center"  height="20" width = "15%"><strong>Campaign Price</strong></td>
								<td  align="center"  height="20" width = "5%" ><strong>SOH</strong></td>
								<td  align="center"  height="20" width = "15%"><strong>Date Last Sold</strong></td>
								<td  align="center"  height="20" width = "9%" ><strong>Days not Availed</strong></td>
								<td  align="center"  height="20" width = "11%" ><strong>Intransit Qty</strong></td>
						</tr>';
	// Print text using writeHTML()
	$pdf->writeHTML($CriticalStocks, true, false, true, false, "");
	$q = $tpiInventory->spSelectCriticalStockOutofStockReport($database, 0,  0, 0, $warehouseid, $productid, $pmgid, $pcode, $invstatus, $qtyfrom, $qtyto);

	if ($q->num_rows){
		
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
			while ($row = $q->fetch_object()){
				$ctr += 1;
				$details .= '<tr align="center" class="$alt">
									<td align="left"  	width = "10%" height="20" >'.$row->ParentCode.'</td>
									<td align="left"  	width = "10%" height="20" >'.$row->ItemCode.'</td>
									<td align="center"  width = "20%" height="20" >'.$row->ItemDesc.'</td>
									<td align="center"  width = "5%"  height="20" >'.$row->PMG.'</td>
									<td align="center"  width = "15%" height="20" >'.number_format($row->CampaignPrice).'</td>
									<td align="center"  width = "5%"  height="20" >'.$row->SOH.'</td>
									<td align="center"  width = "15%" height="20" >'.date("d/m/Y",strtotime($row->DateLastSold)).'</td>
									<td align="center"  width = "9%"  height="20">'.$row->DaysNotAvailed.'</td>
									<td align="center"  width = "11%"  height="20">'.$row->InTransit.'</td>
							</tr>';	
				if ($j < $numRows){	
					// Determine if product string length is greater than threshold
					// If it is, subtract the number of rows per page if necessary
					if (strlen($row->ItemDescription) > $productLenThreshold) {
						// Subtract the number of rows only if we reached threshold of the number
						// of rows whose string length is greater than product length threshold
						if ($deletionThreshold != $rowDeletionThreshold) {
							$numRows--;
							$deletionThreshold++;
						}
						else {
							// Reset the current count
							$deletionThreshold = 0;
						}
					}
					$j++;				
				}else{
					// We only print the page to PDF if we have enough rows to print
					$html = $header."".$details.'</table>';
					$pdf->writeHTML($html, true, false, true, false, "");
					$pdf->AddPage();
					// reset the row counter and the details 
					$details = '';
					$j = 0;
					$numRows = $numRowsPerPage;				
				}
			}
		$q->close();
		if ($details != ''){
			$html = $header."".$details.'</table>';
			$pdf->writeHTML($html, true, false, true, false, "");			
		}
	}else{
			$details = '<tr  align="center"><td height="20" colspan="9" align="center"><strong> No record(s) to display.</strong></td></tr>';
			$html = $header."".$details.'</table>';
			$pdf->writeHTML($html, true, false, true, false, "");
	}
	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("CriticalStocks.pdf", "I");	
?>