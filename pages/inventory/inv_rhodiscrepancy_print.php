<?php
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
		
	global $database;
	//ini_set('max_execution_time', 500);

	$date = $_GET['date'];
	$bcode = $_GET['bcode'];
	$mtype = $_GET['mtype'];
	
	/*if ($bcode == 0)
	{
		$bcode = "";	
	}*/

	$html = '';
	$details = '';
	
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
	$pdf->SetFont("courier", "", 8);

	// add a page
	$pdf->AddPage();
	
	$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    		<tr>
        		<td height="20" align="center"><strong>RHO/STA Discrepancy Report</strong></td>
        	</tr>
    		</table>
    		<br>';

	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false,true, false, "");
	
	$query 	   = $sp->spSelectRHODiscrepancyReport($database, 0, 0, 3, $date, $bcode, $mtype);
	$num       = $query->num_rows;

	if($num > 0)
	{
		$j = 0; 
		// Estimated string length of product description when the TCPDF engine will wrap the text,
		// therfore consuming and extra row
		$productLenThreshold = 26;
		
		// Threshold to determine whether the number of rows per page should be decremented
		// to accomodate product whose length is greater than $productLenThreshold
		$rowDeletionThreshold = 2; 
		
		// Counter of current row deletion threshold
		$deletionThreshold = 0;
		
		// Estimated number of rows per page
		$numRowsPerPage = 24;
		
		$numRows = $numRowsPerPage;
		$details = "";
		
		while($row = $query->fetch_object()) 
		{
			$discrepancy = abs($row->LoadedQty - $row->ConfirmedQty);
						
			if ($j < $numRows) 
			{
		  	 	$details .= '<tr>
					  			  <td height="20" align="center" class="borderBR">'.date("m/d/Y", strtotime($row->TransactionDate)).'</td>
								  <td height="20" align="left" class="padl5 borderBR">'.$row->DocumentNo.'</td>
								  <td height="20" align="left" class="padl5 borderBR">'.$row->PicklistRefNo.'</td>
								  <td height="20" align="left" class="padl5 borderBR">'.$row->ShipmentAdviseNo.'</td>
								  <td height="20" align="left" class="padl5 borderBR">'.$row->Code.'-'.$row->Name.'</td>
								  <td height="20" align="right" class="padr5 borderBR">'.$row->LoadedQty.'</td>
								  <td height="20" align="right" class="padr5 borderBR">'.$row->ConfirmedQty.'</td>
								  <td height="20" align="right" class="padr5 borderBR">'.$discrepancy.'</td>
								  <td height="20" align="left" class="padl5 borderBR">'.$row->Reason.'</td>
							</tr>';
				
				$length = strlen($row->Code) + strlen($row->Name);
	  			
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if ($length > $productLenThreshold) 
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
				$details .= '<tr>
					  			  <td height="20" align="center" class="borderBR">'.date("m/d/Y", strtotime($row->TransactionDate)).'</td>
								  <td height="20" align="left" class="padl5 borderBR">'.$row->DocumentNo.'</td>
								  <td height="20" align="left" class="padl5 borderBR">'.$row->PicklistRefNo.'</td>
								  <td height="20" align="left" class="padl5 borderBR">'.$row->ShipmentAdviseNo.'</td>
								  <td height="20" align="left" class="padl5 borderBR">'.$row->Code.'-'.$row->Name.'</td>
								  <td height="20" align="right" class="padr5 borderBR">'.$row->LoadedQty.'</td>
								  <td height="20" align="right" class="padr5 borderBR">'.$row->ConfirmedQty.'</td>
								  <td height="20" align="right" class="padr5 borderBR">'.$discrepancy.'</td>
								  <td height="20" align="left" class="padl5 borderBR">'.$row->Reason.'</td>
							</tr>'; 
	  			
				$html = '<br>
	    		<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
	    		<tr>
      				  <td width="8%" height="20" align="center" class="bdiv_r"><strong>Date</strong></td>
					  <td width="8%" height="20" align="left" class="padl5 bdiv_r"><strong>Ref. No.</strong></td>
					  <td width="8%" height="20" align="left" class="padl5 bdiv_r"><strong>DR No.</strong></td>
					  <td width="13%" height="20" align="left" class="padl5 bdiv_r"><strong>Shipment Adv No.</strong></td>
					  <td width="24%" height="20" align="left" class="padl5 bdiv_r"><strong>Item Code - Description</strong></td>
					  <td width="8%" height="20" align="right" class="padr5 bdiv_r"><strong>Loaded Qty</strong></td>
					  <td width="8%" height="20" align="right" class="padr5 bdiv_r"><strong>Actual Qty</strong></td>
					  <td width="9%" height="20" align="right" class="padr5 bdiv_r"><strong>Discrepancy</strong></td>
					  <td width="14%" height="20" align="left" class="padl5"><strong>Reason</strong></td>
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
			$html = '<br>
	    		<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
	    		<tr>
	      			  <td width="8%" height="20" align="center" class="bdiv_r"><strong>Date</strong></td>
					  <td width="8%" height="20" align="left" class="padl5 bdiv_r"><strong>Ref. No.</strong></td>
					  <td width="8%" height="20" align="left" class="padl5 bdiv_r"><strong>DR No.</strong></td>
					  <td width="13%" height="20" align="left" class="padl5 bdiv_r"><strong>Shipment Adv No.</strong></td>
					  <td width="24%" height="20" align="left" class="padl5 bdiv_r"><strong>Item Code - Description</strong></td>
					  <td width="8%" height="20" align="right" class="padr5 bdiv_r"><strong>Loaded Qty</strong></td>
					  <td width="8%" height="20" align="right" class="padr5 bdiv_r"><strong>Actual Qty</strong></td>
					  <td width="9%" height="20" align="right" class="padr5 bdiv_r"><strong>Discrepancy</strong></td>
					  <td width="14%" height="20" align="left" class="padl5"><strong>Reason</strong></td>
				</tr>'.$details.
				'</table>';
				
			$pdf->writeHTML($html, true, false, true, false, "");
		}
	}
	else
	{
		$details = '<tr><td height="20" colspan="9" align="center"><strong>No record(s) to display.</strong></td></tr>';
		$html = '<br>
	    		<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
	    		<tr>
	      			  <td width="8%" height="20" align="center" class="bdiv_r"><strong>Date</strong></td>
					  <td width="8%" height="20" align="left" class="padl5 bdiv_r"><strong>Ref. No.</strong></td>
					  <td width="8%" height="20" align="left" class="padl5 bdiv_r"><strong>DR No.</strong></td>
					  <td width="13%" height="20" align="left" class="padl5 bdiv_r"><strong>Shipment Adv No.</strong></td>
					  <td width="24%" height="20" align="left" class="padl5 bdiv_r"><strong>Item Code - Description</strong></td>
					  <td width="8%" height="20" align="right" class="padr5 bdiv_r"><strong>Loaded Qty</strong></td>
					  <td width="8%" height="20" align="right" class="padr5 bdiv_r"><strong>Actual Qty</strong></td>
					  <td width="9%" height="20" align="right" class="padr5 bdiv_r"><strong>Discrepancy</strong></td>
					  <td width="14%" height="20" align="left" class="padl5"><strong>Reason</strong></td>
				</tr>'.$details.
				'</table>';
		$pdf->writeHTML($html, true, false, true, false, "");
	}
		
	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("RHODiscrepancyReport.pdf", "I");
?>