<?php
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	
	include IN_PATH."scConfirmReceiptFromSTA_print.php";
	global $database;
	ini_set('max_execution_time', 500);
	
	$cnt = 0;
	$cntid = 0;
	$strTable1 = ''; 
	$totalQ = 0; 
	$totalcQ = 0;
	
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
	
	$html = '<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20" align="left">&nbsp;<strong>Receipt of STA</strong>&nbsp;&nbsp;<strong>Movement Type :</strong>&nbsp;'.$invtype.'&nbsp;&nbsp;<strong>STA No.:</strong>&nbsp;'.$docno.'&nbsp;&nbsp;<strong>Reference No. :   </strong>'.$txnno.'</td>
			</tr>
			</table>';
	
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
		$numRowsPerPage = 30;
		
		$numRows = $numRowsPerPage;
		
    	while ($row = $rs_detailsall->fetch_object())
        {	 
			$cntid ++;
		    $cnt ++;
		    ($cnt % 2) ? $alt = "" : $alt = "bgEFF0EB";
		    $txid = $row->txnid;
		    $lineno = $cnt;
		    //$lineno = $row->line;
		    $pcode = $row->pcode;
		    $pname = $row->pname;
			$pid = $row->PID;
		    $desc = $row->description;
		    $quantity = number_format($row->quantity,0);
		    $cquantity = number_format($row->cquantity,0);
		    $dquantity =  $cquantity - $quantity;
		    $utname = $row->utname;
		    $rtid = $row->rtid;
			$invID = $row->InvID;
			$soh = $row->SOH; 	
			$rname = $row->rname;
			
			if ($j < $numRows)
			{
				$strTable .= '<tr>
								<td height="20" align="center">'.$cnt.'</td>
								<td height="20" align="center">'.$pcode.'</td>
								<td height="20" align="left">&nbsp;'.$pname.'</td>
								<td height="20" align="center">'.$utname.'</td>
								<td height="20" align="center">'.$quantity.'</td>
	                      	</tr>';
	                      	
              	// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($pname) > $productLenThreshold) 
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
				$strTable .= '<tr>
								<td height="20" align="center">'.$cnt.'</td>
								<td height="20" align="center">'.$pcode.'</td>
								<td height="20" align="left">&nbsp;'.$pname.'</td>
								<td height="20" align="center">'.$utname.'</td>
								<td height="20" align="center">'.$quantity.'</td>
	                      	</tr>';
	                      	
              	$html = '<table width="100%" border="1" cellpadding="0" cellspacing="0">							
						<tr>
							<td height="20" width="5%" align="center"><strong>Line #</strong></td>
							<td height="20" width="14%" align="center"><strong>Item Code</strong></td>
							<td height="20" width="30%" align="center"><strong>Item Description</strong></td>
							<td height="20" width="10%" align="center"><strong>UOM</strong></td>
							<td height="20" width="9%" align="center"><strong>Loaded Qty</strong></td>
						</tr>'.$strTable.'</table>';
						
				// We only print the page to PDF if we have enough rows to print
	      		$pdf->writeHTML($html, true, false, true, false, "");
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$strTable = '';
				$j = 0;
				$numRows = $numRowsPerPage; 					
			}							
		
			$totalQ += $quantity;
            $totalcQ += $cquantity;                               
       }
       $rs_detailsall->close();
                           
       $strTable1 = '<tr>
							 <td height="20" width="5%" align="center">&nbsp;</td>
							 <td height="20" width="14%" align="center">&nbsp;</td>
							 <td height="20" width="30%" align="center">&nbsp;</td>
							 <td height="20" width="10%" align="center">Totals :</td>
							 <td height="20" width="9%" align="center"><strong>'.$totalQ.'</strong></td>
                    </tr>'; 
                     
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($strTable != '')
		{
			$html = '<table width="100%" border="1" cellpadding="0" cellspacing="0">							
					<tr>
						<td height="20" width="5%" align="center"><strong>Line #</strong></td>
						<td height="20" width="14%" align="center"><strong>Item Code</strong></td>
						<td height="20" width="30%" align="center"><strong>Item Description</strong></td>
						<td height="20" width="10%" align="center"><strong>UOM</strong></td>
						<td height="20" width="9%" align="center"><strong>Loaded Qty</strong></td>
					</tr>'.$strTable;
		}
		else
		{
			$html = '<table width="100%" border="1" cellpadding="0" cellspacing="0">';			
		}
		
		$html .= $strTable1.'</table>';
		$html .= '<br>
				<br>
				<br>
				<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
	 			<tr>
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
	         	</table>';
	         	
		$pdf->writeHTML($html, true, false, true, false, "");
	}
	
	/*$html = '<table border="0" width="100%">
			<tr>
				<td>
					<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    				<tr>
      					<td height="20" align="center"><strong>Receipt of STA</strong></td>
        			</tr>
    				</table>
    				<br>
    				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
          			<tr>
        				<td align="left" height="20">&nbsp;<strong>General Information</strong></td>
          			</tr>
    				</table>
    				<br>
					<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
					<tr>
			 			<td width="20%" height="20" align="right"><strong>Movement Type :   </strong>&nbsp;</td>
					 	<td width="30%" height="20" align="left">&nbsp;'.$invtype.'</td>
				 		<td width="20%" height="20" align="right"><strong>Transaction Date:   </strong>&nbsp;</td>
					 	<td width="30%" height="20" align="left">&nbsp;'.$txndate.'</td>
					</tr>
					<tr>
				     	<td height="20" align="right"><strong>Reference No. :   </strong>&nbsp;</td>
				     	<td height="20" align="left">&nbsp;'.$txnno.'</td>
				     	<td height="20" align="right"><strong>Remarks :   </strong>&nbsp;</td>
				     	<td height="20" align="left">&nbsp;'.$remarks.'</td>
					</tr>
					<tr>
				     	<td height="20" align="right"><strong>STA No. :   </strong>&nbsp;</td>
				     	<td height="20" align="left">&nbsp;'.$docno.'</td>
				     	<td height="20" align="right">&nbsp;</td>
				     	<td height="20" align="left">&nbsp;</td>
					</tr>
					<tr>
				     	<td height="20" align="right"><strong>Delivery Date :   </strong>&nbsp;</td>
				     	<td height="20" align="left">&nbsp;'.$txndate.'</td>
				     	<td height="20" align="right">&nbsp;</td>
				     	<td height="20" align="left">&nbsp;</td>
					</tr>
					<tr>
				    	<td height="20" align="right"><strong>Issuing Branch :   </strong>&nbsp;</td>
				     	<td height="20" align="left">&nbsp;'.$sbranch.'</td>
				     	<td height="20" align="right">&nbsp;</td>
				     	<td height="20" align="left">&nbsp;</td>
					</tr>
					<tr>
			     		<td height="20" align="right"><strong>Receiving Branch :   </strong>&nbsp;</td>
				     	<td height="20" align="left">&nbsp;'.$rbranch.'</td>
				     	<td height="20" align="right">&nbsp;</td>
				     	<td height="20" align="left">&nbsp;</td>
					</tr>
					</table>
				</td>
			</tr>	
			</table>
			<br><br>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr align="center">
				<td height="20"><strong>Product Details</strong></td>
			</tr>
			</table>					
			<table width="100%" border="1" cellpadding="0" cellspacing="0">							
			<tr>
				<td height="20" width="5%" align="center"><strong>Line #</strong></td>
				<td height="20" width="14%" align="center"><strong>Item Code</strong></td>
				<td height="20" width="30%" align="center"><strong>Item Description</strong></td>
				<td height="20" width="10%" align="center"><strong>UOM</strong></td>
				<td height="20" width="9%" align="center"><strong>Loaded Qty</strong></td>
			</tr>'.$strTable.$strTable1.
			'</table>
		 	<br>
			<br>
			<br>
			<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
 			<tr>
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
	$pdf->Output("InventoryInOutReceiptOfSTA.pdf", "I");
?>