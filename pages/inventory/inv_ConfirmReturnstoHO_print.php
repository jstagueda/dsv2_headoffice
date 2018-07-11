<?php
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	
	include IN_PATH.'scConfirmsReturnstoHO_print.php';
	global $database;
	ini_set('max_execution_time', 1000);
	
	$cnt = 0;
	$html = '';
	$strTable = '';
	$strTable1 = ''; 
	$totalQ = 0; 
	$totalcQ = 0;
	
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
				<td height="20" align="left">&nbsp;<strong>Returns to HO</strong>&nbsp;&nbsp;<strong>Movement Type :</strong>&nbsp;'.$invtype.'&nbsp;&nbsp;<strong>STA No.:</strong>&nbsp;'.$docno.'&nbsp;&nbsp;<strong>Reference No. :   </strong>'.$txnno.'</td>
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
        	$cnt ++;
            $txid = $row->txnid;
            $lineno = $cnt;
            //$lineno = $row->line;
            $pcode = $row->pcode;
            $pname = $row->pname;
			$pid = $row->PID;
            $desc = $row->description;
            $quantity = number_format($row->quantity,0);
            $cquantity = number_format($row->cquantity,0);
            $dquantity = $cquantity - $quantity ;
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
	                        	<td height="20" align="center">'.$cquantity.'</td>
	                         	<td height="20" align="center">'.$dquantity.'</td>
	                         	<td height="20" align="center">'.$rname.'</td>
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
	                        	<td height="20" align="center">'.$cquantity.'</td>
	                         	<td height="20" align="center">'.$dquantity.'</td>
	                         	<td height="20" align="center">'.$rname.'</td>
	                          </tr>';
                          
              	$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
						<tr>
							<td height="20" width="5%" align="center"><strong>Line #</strong></td>
							<td height="20" width="14%" align="center"><strong>Item Code</strong></td>
							<td height="20" width="30%" align="center"><strong>Item Description</strong></td>
							<td height="20" width="10%" align="center"><strong>UOM</strong></td>
							<td height="20" width="9%" align="center"><strong>Loaded Qty</strong></td>
							<td height="20" width="9%" align="center"><strong>Actual Qty</strong></td>
							<td height="20" width="10%" align="center"><strong>Discrepancy</strong></td>
							<td height="20" width="13%" align="center"><strong>Reason Code</strong></td>			
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
	                     	<td height="20" align="center">&nbsp;</td>
	                     	<td height="20" align="center">&nbsp;</td>
	                     	<td height="20" align="center">&nbsp;</td>
	                     	<td height="20" align="center">Totals :</td>
	                     	<td height="20" align="center"><strong>'.$totalQ.'</strong></td>
	                     	<td height="20" align="center"><strong>'.$totalcQ.'</strong></td>
	                     	<td height="20" align="center">&nbsp;</td>
	                     	<td height="20" align="center">&nbsp;</td>
                     	</tr>'; 
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($strTable != '')
		{
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
			<tr>
				<td height="20" width="5%" align="center"><strong>Line #</strong></td>
				<td height="20" width="14%" align="center"><strong>Item Code</strong></td>
				<td height="20" width="30%" align="center"><strong>Item Description</strong></td>
				<td height="20" width="10%" align="center"><strong>UOM</strong></td>
				<td height="20" width="9%" align="center"><strong>Loaded Qty</strong></td>
				<td height="20" width="9%" align="center"><strong>Actual Qty</strong></td>
				<td height="20" width="10%" align="center"><strong>Discrepancy</strong></td>
				<td height="20" width="13%" align="center"><strong>Reason Code</strong></td>			
           	</tr>'.$strTable;
		}
		
		$html .= $strTable1.'</table>';
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
	
	/*$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
        	<tr>
      			<td height="20" align="center"><strong>Returns to Head Office</strong></td>
        	</tr>
    		</table>	
			<br>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		 	<tr>
				<td height="20" align="left">&nbsp;<strong>General Information</strong></td>
		 	</tr>
			</table>
			<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
			<tr>
		 		<td width="20%" height="20" align="right"><strong>Movement Type :   </strong></td>
			 	<td width="30%" height="20" align="left">&nbsp;'.$invtype.'</td>
			 	<td width="20%" height="20" align="right"><strong>Transaction Date :   </strong></td>
			 	<td width="30%" height="20" align="left">&nbsp;'.$txndate.'</td>
			</tr>
			<tr>
	     		<td height="20" align="right"><strong>Reference No. :   </strong></td>
		     	<td height="20" align="left">&nbsp;'.$txnno.'</td>
		     	<td height="20" align="right"><strong>Remarks :   </strong></td>
		     	<td height="20" align="left">&nbsp;'.$remarks.'</td>
			</tr>
			<tr>
	     		<td height="20" align="right"><strong>STA No. :   </strong></td>
		     	<td height="20" align="left">&nbsp;'.$docno.'</td>
		     	<td height="20" align="right">&nbsp;</td>
		     	<td height="20" align="left">&nbsp;</td>
			</tr>
			<tr>
	     		<td height="20" align="right"><strong>Delivery Date :   </strong></td>
		     	<td height="20" align="left">&nbsp;'.$txndate.'</td>
		     	<td height="20" align="right">&nbsp;</td>
		     	<td height="20" align="left">&nbsp;</td>
			</tr>
			<tr>
	     		<td height="20" align="right"><strong>Issuing Branch :   </strong></td>
		     	<td height="20" align="left">&nbsp;'.$sbranch.'</td>
		     	<td height="20" align="right">&nbsp;</td>
		     	<td height="20" align="left">&nbsp;</td>
			</tr>
			<tr>
	     		<td height="20" align="right"><strong>Receiving Branch :   </strong></td>
		     	<td height="20" align="left">&nbsp;'.$rbranch.'</td>
		     	<td height="20" align="right">&nbsp;</td>
		     	<td height="20" align="left">&nbsp;</td>
			</tr>
			</table>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20" align="left">&nbsp;<strong>Product Details</strong></td>									
			</tr>
			</table>
			<br><br>				
			<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
			<tr>
				<td height="20" width="5%" align="center"><strong>Line #</strong></td>
				<td height="20" width="14%" align="center"><strong>Item Code</strong></td>
				<td height="20" width="30%" align="center"><strong>Item Description</strong></td>
				<td height="20" width="10%" align="center"><strong>UOM</strong></td>
				<td height="20" width="9%" align="center"><strong>Loaded Qty</strong></td>
				<td height="20" width="9%" align="center"><strong>Actual Qty</strong></td>
				<td height="20" width="10%" align="center"><strong>Discrepancy</strong></td>
				<td height="20" width="13%" align="center"><strong>Reason Code</strong></td>			
           	</tr>'.$strTable.$strTable1.			 
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
	$pdf->Output("InventoryInOutReturnstoHO.pdf", "I");	
?>