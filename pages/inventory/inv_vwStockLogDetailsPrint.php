<?php
	require "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
		
	include IN_PATH.DS."scStockLogDetails.php";
	global $database;
	ini_set('max_execution_time', 1000);

	$prodid = $_GET['pid'];
	$wareid = $_GET['wid'];
	$datefrom_q =  $_GET['fdte'];
	$dateto_q = $_GET['tdte'];
	$stocks = "";
	$daterange = date("m/d/Y", strtotime($datefrom_q))." - ".date("m/d/Y", strtotime($dateto_q." -1 day"));
	$qtyout = 0;
	$runbal = 0;
	
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
	$pdf->SetFont("courier", "", 9);

	// add a page
	$pdf->AddPage();
	
	$html = '<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20">&nbsp;</td>
	          	<td height="20" align="center"><strong>Stock Card Report</strong></td>
	          	<td height="20">&nbsp;</td>
	        </tr>
	        <tr>
	        	<td height="20">&nbsp;</td>
	          	<td height="20"align="center"><strong>'.$daterange.'</strong></td>
	          	<td height="20">&nbsp;</td>
	        </tr>
	 		</table> 
	  		<br>
	  		<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
  			<tr>
				<td width="20%" height="20" align="right"><strong>WAREHOUSE :   </strong></td>
				<td width="30%" height="20" align="left"> '.$warehouse.'</td>
				<td width="20%" height="20">&nbsp;</td>
				<td width="20%" height="20">&nbsp;</td>
			</tr>
  			<tr>
				<td height="20" align="right">&nbsp;<strong>PRODUCT CODE :   </strong></td>
				<td height="20" align="left"> '.$prodcode.'</td>
				<td height="20" align="right"><strong>PRODUCT NAME :   </strong></td>
				<td height="20" align="left"> '.$prodname.'</td>
			</tr>
  			<tr>
				<td height="20" colspan="4">&nbsp;</td>
  			</tr>
			</table>
			<br>';

	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");
	
	$rs_stocklog = $sp->spSelectStockLog($database, $prodid, $wareid, $datefrom_q, $dateto_q);
	if ($rs_stocklog->num_rows)
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
		$numRowsPerPage = 18;
		
		$numRows = $numRowsPerPage;
		
		$runbal = $beg;
		while ($row = $rs_stocklog->fetch_object())
		{
			$runbal += $row->QtyIn; 
			$runbal -= $row->QtyOut;
			
			if ($j < $numRows)
			{
				$stocks .= '<tr>
	          					<td height="20" align="center">'.$row->TxnDate.'</td>
		              			<td height="20" align="center">'.$row->MovementType.'</td>
		              			<td height="20" align="center">'.$row->RefNo.'</td>		  
		              			<td height="20" align="center">'.$row->RefTxnNo.'</td>
		              			<td height="20" align="center">'.$row->QtyIn.'</td>
		              			<td height="20" align="center">'.$row->QtyOut.'</td>
		              			<td height="20" align="center">'.$runbal.'</td>
			 				</tr>';
 				
 				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->MovementType) > $productLenThreshold) 
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
				$stocks .= '<tr>
	          					<td height="20" align="center">'.$row->TxnDate.'</td>
		              			<td height="20" align="center">'.$row->MovementType.'</td>
		              			<td height="20" align="center">'.$row->RefNo.'</td>		  
		              			<td height="20" align="center">'.$row->RefTxnNo.'</td>
		              			<td height="20" align="center">'.$row->QtyIn.'</td>
		              			<td height="20" align="center">'.$row->QtyOut.'</td>
		              			<td height="20" align="center">'.$runbal.'</td>
			 				</tr>';
			 				
 				$html = '<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
		    			<tr>
		    				<td width="15%" height="20" align="center"><strong>Transaction Date</strong></td>
		        			<td width="25%" height="20" align="center"><strong>Movement Type</strong></td>
		        			<td width="15%" height="20" align="center"><strong>Document No.</strong></td>
		        			<td width="15%" height="20" align="center"><strong>Reference No.</strong></td>
		        			<td width="10%" height="20" align="center"><strong>Qty In</strong></td>
		        			<td width="10%" height="20" align="center"><strong>Qty Out</strong></td>
		        			<td width="10%" height="20" align="center"><strong>Running Balance</strong></td>
		 				</tr>
		     			<tr>
		    				<td height="20" colspan="4" align="right"><strong>Beginning Balance :   </strong>&nbsp;</td>		  
							<td height="20">&nbsp;</td>
							<td height="20">&nbsp;</td>
							<td height="20" align="right">'.$beg   .'</td>
						</tr>'.$stocks.'</table>';
			 				
 				// We only print the page to PDF if we have enough rows to print
	      		$pdf->writeHTML($html, true, false, true, false, "");
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$stocks = '';
				$j = 0;
				$numRows = $numRowsPerPage;				
			}
		}
		$rs_stocklog->close();
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($stocks != '')
		{
			$html = '<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
	    			<tr>
	    				<td width="15%" height="20" align="center"><strong>Transaction Date</strong></td>
	        			<td width="25%" height="20" align="center"><strong>Movement Type</strong></td>
	        			<td width="15%" height="20" align="center"><strong>Document No.</strong></td>
	        			<td width="15%" height="20" align="center"><strong>Reference No.</strong></td>
	        			<td width="10%" height="20" align="center"><strong>Qty In</strong></td>
	        			<td width="10%" height="20" align="center"><strong>Qty Out</strong></td>
	        			<td width="10%" height="20" align="center"><strong>Running Balance</strong></td>
	 				</tr>
	     			<tr>
	    				<td height="20" colspan="4" align="right"><strong>Beginning Balance :   </strong>&nbsp;</td>		  
						<td height="20">&nbsp;</td>
						<td height="20">&nbsp;</td>
						<td height="20" align="right">'.$beg   .'</td>
					</tr>'.$stocks.
					'<tr>
						<td width="70%" height="20" colspan="4" align="right"><strong>Ending Balance :   </strong>&nbsp;</td>		  
						<td width="10%" height="20">&nbsp;</td>
						<td width="10%" height="20">&nbsp;</td>
						<td width="10%" height="20" align="right">'.$runbal.'</td>
					</tr>    
	     			</table>';
		}
		else
		{
			$html = '<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td width="70%" height="20" colspan="4" align="right"><strong>Ending Balance :   </strong>&nbsp;</td>		  
						<td width="10%" height="20">&nbsp;</td>
						<td width="10%" height="20">&nbsp;</td>
						<td width="10%" height="20" align="right">'.$runbal.'</td>
					</tr>    
	     			</table>';			
		}
		
		$pdf->writeHTML($html, true, false, true, false, "");
	}
	else
	{
		$stocks = '<tr><td height="20" colspan="7" align="center"><strong>No record(s) to display.</strong></td></tr>';
		$html = '<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
    			<tr>
    				<td width="15%" height="20" align="center"><strong>Transaction Date</strong></td>
        			<td width="25%" height="20" align="center"><strong>Movement Type</strong></td>
        			<td width="15%" height="20" align="center"><strong>Document No.</strong></td>
        			<td width="15%" height="20" align="center"><strong>Reference No.</strong></td>
        			<td width="10%" height="20" align="center"><strong>Qty In</strong></td>
        			<td width="10%" height="20" align="center"><strong>Qty Out</strong></td>
        			<td width="10%" height="20" align="center"><strong>Running Balance</strong></td>
 				</tr>'.$stocks.'</table>';
 				
		$pdf->writeHTML($html, true, false, true, false, "");
	}
         			
	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	$pdf->Output("StockLog.pdf", "I");	
?>