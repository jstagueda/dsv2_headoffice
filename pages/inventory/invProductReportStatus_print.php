<?php
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	
	global $database;
	include IN_PATH.'scInvProductReportStatus_print.php';
	
	ini_set('max_execution_time', 1000);
	ini_set('display_errors' ,1);
	
	$strTable = "";
	
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
	
	/*
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
	 		<td width="15%" height="20" align="right"><strong>Warehouse :   </strong></td>
		 	<td width="85%" height="20" align="left">&nbsp;'.$warehouse.'</td>
		</tr> 
		<tr>
	 		<td height="20" align="right"><strong>Location :   </strong></td>
		 	<td height="20" align="left">&nbsp;'.$location.'</td>
		</tr>
		<tr>
	 		<td height="20" align="right"><strong>Product Line :   </strong></td>
	 		<td height="20" align="left">&nbsp;'.$parent.'</td>
		</tr>
		<tr>
	 		<td height="20" align="right"><strong>PMG :   </strong></td>
	     	<td height="20" align="left">&nbsp;'.$pmg.'</td>
	 	</tr>
	 	<tr>
	 		<td height="20" align="right"><strong>As of :   </strong></td>
	     	<td height="20" align="left">&nbsp;'.$frmdate.'</td>
	 	</tr>
	 	<tr>
	 		<td height="20" colspan="2">&nbsp;</td>
		</tr>
		</table>
	 */
	
	$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td height="20" align="center"><strong>Product Status Report</strong></td>
            </tr>
        	</table>';
        	
	// add a page
	$pdf->AddPage();
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");
	
	$query = $sp->spSelectProductforProdReportStatCnt($database, $code, $wid,$plid,$pmgid,$lid, $ref, $frmdate2);
	if ($query->num_rows)
	{
		$j = 0; 
		// Estimated string length of product description when the TCPDF engine will wrap the text,
		// therfore consuming and extra row
		$productLenThreshold = 23;
		
		// Threshold to determine whether the number of rows per page should be decremented
		// to accomodate product whose length is greater than $productLenThreshold
		$rowDeletionThreshold = 2; 
		
		// Counter of current row deletion threshold
		$deletionThreshold = 0;
		
		// Estimated number of rows per page
		$numRowsPerPage = 25;
		
		$numRows = $numRowsPerPage;
		
		while ($row = $query->fetch_object())
		{
			//$cnt ++;
            if($pmgid == 0)
			{
				$pmg = "ALL";
			}
			else
			{
				$pmg = $row->pmgname;
			}
			
			if($plid == 0)
			{
				$parent = "ALL";
			}
			else
			{
				$parent = $row->ProdLine;
			}
				                                      
			$soh = number_format($row->soh,0);
			$uprice = number_format($row->up, 2, ".", "");			
	  		$totvalue = $row->up * $soh;           
			$totvalue = number_format($totvalue, 2, ".", "");
			
			if ($j < $numRows)
			{
				$strTable .= '<tr>
	             				<td height="20" align="left">&nbsp;'.$row->ProdLine.'</td>
		                     	<td height="20" align="left">&nbsp;'.$row->prodcode.'</td>
		                     	<td height="20" align="left">&nbsp;'.$row->proddesc.'</td>
		                     	<td height="20" align="right">'.$uprice.'&nbsp;&nbsp;</td>
		                     	<td height="20" align="center">'.$soh.'</td>
		                     	<td height="20" align="right">'.$totvalue.'&nbsp;&nbsp;</td>
						 	</tr>';
					 	
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->proddesc) > $productLenThreshold) 
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
           		$strTable .= '<tr>
	             				<td height="20" align="left">&nbsp;'.$row->ProdLine.'</td>
		                     	<td height="20" align="left">&nbsp;'.$row->prodcode.'</td>
		                     	<td height="20" align="left">&nbsp;'.$row->proddesc.'</td>
		                     	<td height="20" align="right">'.$uprice.'&nbsp;&nbsp;</td>
		                     	<td height="20" align="center">'.$soh.'</td>
		                     	<td height="20" align="right">'.$totvalue.'&nbsp;&nbsp;</td>
						 	</tr>';
						 	
           		$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">		
						<tr>
							<td width="10%" align="left">&nbsp;<strong>Product Line</strong></td>
							<td width="20%" align="left">&nbsp;<strong>Item Code</strong></td>
							<td width="35%" align="left">&nbsp;<strong>Item Description</strong></td>
							<td width="10%" align="right"><strong>Price</strong>&nbsp;&nbsp;</td>
							<td width="10%" align="center"><strong>SOH</strong></td>
							<td width="15%" align="right"><strong>Total Value</strong>&nbsp;&nbsp;</td>	
						</tr>'.$strTable.'</table>';       
						
				$pdf->writeHTML($html, true, false, true, false, "");
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$strTable = '';
				$j = 0;
				$numRows = $numRowsPerPage;    		
           	}
		}
		$query->close();
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($strTable != '')
		{
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">		
						<tr>
							<td width="10%" align="left">&nbsp;<strong>Product Line</strong></td>
							<td width="20%" align="left">&nbsp;<strong>Item Code</strong></td>
							<td width="35%" align="left">&nbsp;<strong>Item Description</strong></td>
							<td width="10%" align="right"><strong>Price</strong>&nbsp;&nbsp;</td>
							<td width="10%" align="center"><strong>SOH</strong></td>
							<td width="15%" align="right"><strong>Total Value</strong>&nbsp;&nbsp;</td>	
						</tr>'.$strTable.'</table>';       
						
			$pdf->writeHTML($html, true, false, true, false, "");			
		}
	}
	else
	{
		$strTable = '<tr><td height="20" colspan="6" align="center"><strong>No record(s) to display.</strong></td></tr>';
		$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">		
				<tr>
					<td width="10%" align="left">&nbsp;<strong>Product Line</strong></td>
					<td width="20%" align="left">&nbsp;<strong>Item Code</strong></td>
					<td width="35%" align="left">&nbsp;<strong>Item Description</strong></td>
					<td width="10%" align="right"><strong>Price</strong>&nbsp;&nbsp;</td>
					<td width="10%" align="center"><strong>SOH</strong></td>
					<td width="15%" align="right"><strong>Total Value</strong>&nbsp;&nbsp;</td>	
				</tr>'.$strTable.'</table>';
		$pdf->writeHTML($html, true, false, true, false, "");       		
	}
   
	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("ProductReportStatus.pdf", "I");	
?>