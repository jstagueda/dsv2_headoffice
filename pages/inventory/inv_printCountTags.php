<?php 
	require_once "../../initialize.php";
	include CS_PATH.DS."ClassInventory.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	
	global $database;
	ini_set('max_execution_time', 1000);

	$rs_branch = $sp->spGetBranchParameter($database);
	if ($rs_branch->num_rows)
	{
		while ($row_branch = $rs_branch->fetch_object())
		{
			$branch = $row_branch->name;							
		}
	}
	$strTable = "";	
	$txnid = $_GET["txnid"];
	$osort = $_GET["sort"];
	
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
	
	if ($osort == 0)
	{
		$rs_prodlist = $tpiInventory->spSelectInvCntDetByID($database, $txnid, 1);		
	}
	else if ($osort == 2)
	{
		$rs_prodlist = $tpiInventory->spSelectInvCntDetByIDForPrinting($database, $txnid, session_id());		
	}
	else
	{
		$rs_prodlist = $sp->spSelectInvCntDetAddtlByID($database, $txnid, $osort);
	}
	
	if ($rs_prodlist->num_rows)
	{
		$j =0; 
		// Estimated string length of product description when the TCPDF engine will wrap the text,
		// therfore consuming and extra row
		$productLenThreshold = 36;
		
		// Threshold to determine whether the number of rows per page should be decremented
		// to accomodate product whose length is greater than $productLenThreshold
		$rowDeletionThreshold = 1; 
		
		// Counter of current row deletion threshold
		$deletionThreshold = 0;
		
		// Estimated number of rows per page
		$numRowsPerPage = 3;
		
		$numRows = $numRowsPerPage;
		$i = 0;
		
		while ($row = $rs_prodlist->fetch_object())
		{
			if ($j < $numRows)
			{
				$strTable .= "<br/><table width='100%' border='1' cellpadding='0' cellspacing='0'>
								<tr align='center'>
									<td height='25' width='30%'><strong>Branch:</strong>&nbsp;&nbsp;$branch</td>																
									<td height='25' width='70%'><strong>Worksheet No:</strong>&nbsp;&nbsp;$row->TxnNo</td>
								</tr>
								<tr align='center'>
									<td height='25'><strong>Location:</strong>&nbsp;&nbsp;$row->Location</td>																	
									<td height='25'><strong>Product Code:</strong>&nbsp;&nbsp;$row->pCode</td>
								</tr>
								<tr align='center'>
									<td height='25'><strong>Tag Number:</strong>&nbsp;&nbsp;$row->CountTag</td>																	
									<td height='25'><strong>Product Description:</strong>&nbsp;&nbsp;$row->pName</td>
								</tr>
								</table>
								<table width='100%' border='1' cellpadding='0' cellspacing='0'>
								<tr align='center'>
									<td height='20' align ='center' width='20%'><strong>Quantity Counted:</strong></td>
									<td height='20' width='25%'>____________________</td>
									<td height='20' width='10%'></td>
									<td height='20' align ='right' width='20%'><strong>Quantity Recounted:</strong></td>
									<td height='20' width='22%'>____________________</td>
								</tr>
								<tr align='center'>
									<td height='20' align ='right' width='20%'><strong>UM:</strong></td>	
									<td height='20' width='25%'>PC</td>
									<td height='20' width='15%'></td>
									<td height='20' align ='left' width='20%'><strong>UM:</strong></td>	
									<td height='20' width='22%'>PC</td>																		
								</tr>
								<tr align='center'>
									<td height='20' align ='right' width='20%'><strong>Counted By:</strong></td>
									<td height='20' width='25%'>____________________</td>
									<td height='20' width='10%'></td>
									<td height='20' align ='left' width='20%'><strong>Recounted By:</strong></td>
									<td height='20' width='22%'>____________________</td>
								</tr>	
								<tr align='center'>
									<td height='20' align ='right' width='20%'><strong>Date Counted:</strong></td>	
									<td height='20' width='25%'>____________________</td>
									<td height='20' width='15%'></td>
									<td height='20' align ='left' width='20%'><strong>Date Recounted:</strong></td>
									<td height='20' width='22%'>____________________</td>
								</tr>	
								<tr align='center'>	
									<td height='20' align ='right' width='20%'><strong>Checked By:</strong></td>
									<td height='20' width='25%'>____________________</td>
									<td height='20' width='15%'></td>
									<td height='20' width='20%'></td>
									<td height='20' width='22%'></td>	
								</tr>	
								<tr align='center'>	
									<td height='20' align ='right' width='20%'><strong>Remarks:</strong></td>
									<td height='20' width='25%'>____________________</td>
									<td height='20' width='15%'></td>
									<td height='20' width='20%'></td>
									<td height='20' width='22%'></td>	
								</tr>		
								</table>";
							
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->pName) > $productLenThreshold) 
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
				$strTable .= "<br/><table width='100%' border='1' cellpadding='0' cellspacing='0'>
								<tr align='center'>
									<td height='25' width='30%'><strong>Branch:</strong>&nbsp;&nbsp;$branch</td>																
									<td height='25' width='70%'><strong>Worksheet No:</strong>&nbsp;&nbsp;$row->TxnNo</td>
								</tr>
								<tr align='center'>
									<td height='25'><strong>Location:</strong>&nbsp;&nbsp;$row->Location</td>																	
									<td height='25'><strong>Product Code:</strong>&nbsp;&nbsp;$row->pCode</td>
								</tr>
								<tr align='center'>
									<td height='25'><strong>Tag Number:</strong>&nbsp;&nbsp;$row->CountTag</td>																	
									<td height='25'><strong>Product Description:</strong>&nbsp;&nbsp;$row->pName</td>
								</tr>
								</table>
								<table width='100%' border='1' cellpadding='0' cellspacing='0'>
								<tr align='center'>
									<td height='20' align ='center' width='20%'><strong>Quantity Counted:</strong></td>
									<td height='20' width='25%'>____________________</td>
									<td height='20' width='10%'></td>
									<td height='20' align ='right' width='20%'><strong>Quantity Recounted:</strong></td>
									<td height='20' width='22%'>____________________</td>
								</tr>
								<tr align='center'>
									<td height='20' align ='right' width='20%'><strong>UM:</strong></td>	
									<td height='20' width='25%'>PC</td>
									<td height='20' width='15%'></td>
									<td height='20' align ='left' width='20%'><strong>UM:</strong></td>	
									<td height='20' width='22%'>PC</td>																		
								</tr>
								<tr align='center'>
									<td height='20' align ='right' width='20%'><strong>Counted By:</strong></td>
									<td height='20' width='25%'>____________________</td>
									<td height='20' width='10%'></td>
									<td height='20' align ='left' width='20%'><strong>Recounted By:</strong></td>
									<td height='20' width='22%'>____________________</td>
								</tr>	
								<tr align='center'>
									<td height='20' align ='right' width='20%'><strong>Date Counted:</strong></td>	
									<td height='20' width='25%'>____________________</td>
									<td height='20' width='15%'></td>
									<td height='20' align ='left' width='20%'><strong>Date Recounted:</strong></td>
									<td height='20' width='22%'>____________________</td>
								</tr>	
								<tr align='center'>	
									<td height='20' align ='right' width='20%'><strong>Checked By:</strong></td>
									<td height='20' width='25%'>____________________</td>
									<td height='20' width='15%'></td>
									<td height='20' width='20%'></td>
									<td height='20' width='22%'></td>	
								</tr>	
								<tr align='center'>	
									<td height='20' align ='right' width='20%'><strong>Remarks:</strong></td>
									<td height='20' width='25%'>____________________</td>
									<td height='20' width='15%'></td>
									<td height='20' width='20%'></td>
									<td height='20' width='22%'></td>	
								</tr>		
								</table>";
							
				$pdf->writeHTML($strTable, true, false, true, false, "");
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$strTable = '';
				$j = 0;
				$numRows = $numRowsPerPage;    				
			}
			if ($i == 4300)
			{
				break;
			}
			$i++;
		}
		$rs_prodlist->close();
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($strTable != '')
		{
			$pdf->writeHTML($strTable, true, false, true, false, "");
		}
	}
	//ob_end_clean();
	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("CountTags.pdf", "I");	
	
?>