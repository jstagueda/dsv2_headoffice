<?php
	require "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
		
	include IN_PATH.'scProductExchangePrint.php';
	global $database;
	ini_set('max_execution_time', 1000);


	$stocks = "";
	
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
	
	$html = '<table width="98%" border="0" align="center" cellpadding="0"	cellspacing="1">
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td width="50%" valign="top">
				<table width="98%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td height="25" class="txt10" align="right">Customer Code :</td>
						<td width="60%" align="left" height="20">'.$custCode.'	
		  				</td>
					</tr>	
					<tr>
						<td height="25" class="txt10" align="right">Customer Name :</td>
						<td height="25" class="txt10" align="left">'.$custName.'</td>
					</tr>	
					<tr>
						<td height="25" class="txt10" align="right">IBM Code / IBM Name :</td>
						<td height="25" class="txt10" align="left">'.$ibm.'</td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >SI No. : </td>
						<td height="25" align="left" width="60%">'.$sino.'</td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Sales Invoice Date : </td>
						<td height="25" align="left" width="60%">'.$invoiceDate.'</td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Reference SO No. : </td>
						<td height="25" align="left" width="60%">'.$refno.'</td>
					</tr>	 
					<tr>
						<td height="25" class="txt10"  align="right" >Remarks: </td>
						<td height="25" align="left" width="60%">'.$remarks.'</td>
					</tr>
				</table>
				</td>
				<td valign="top">
				<table width="98%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td height="25" class="txt10"  align="right" >Document No. : </td>
						<td height="25" width="60%">'.$docno.'</td>
					</tr>
				
					<tr>
						<td height="25" class="txt10"  align="right" >Branch : </td>
						<td height="25" width="60%">'.$branch.'</td>
					</tr>
					<tr>
						<td height="25" class="txt10"  align="right" >Created By : </td>
						<td height="25" width="60%">'.$createdby.'</td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Status: </td>
						<td height="25" width="60%">'.$status.'</td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Confirmed By: </td>
						<td height="25" width="60%">'.$confirmedby.'</td>
					</tr>
					<tr>
						<td height="25" class="txt10"  align="right" >Date Confirmed: </td>
						<td height="25" width="60%">'.$dateconfirmed.'</td>
					</tr>
					
				</table> 
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</table>
		<br>';

	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");
	
	//$rs_stocklog = $sp->spSelectStockLog($database, $prodid, $wareid, $datefrom_q, $dateto_q);
	if ($rsDetails->num_rows)
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
		$i=0;
		
		$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
					<tr align="center">
					<td width="5%" height="20" >Line No.</td>
					<td width="10%" height="20" >Product Code</td>   
					<td width="16%" height="20" >Product Name</td>
					<td width="5%" height="20" >UOM</td>
					<td width="5%" height="20" >PMG</td>			  				
					<td width="5%" height="20" >Qty</td>		  			 			
					<td width="5%" height="20" >Price</td>
					<td width="8%" height="20" >Net Amount</td>			  			
					<td width="8%" height="20" >Reason</td>
					<td width="12%" height="20" > Exchange Product Code</td>   
					<td width="16%" height="20" >Exchange Product Name</td>				      
					</tr>';
		
		while ($row=$rsDetails->fetch_object()) {
			
				$strTable = '<tr align="center">			         					
		  			<td width="5%" height="20" >'.($i+1).'</td>
		  			<td width="10%" height="20">'.$row->prodCode.'</td>   
		  			<td width="16%" height="20">'.$row->prodName.'</td>
		  			<td width="5%" height="20">'.$row->uom.'</td>
		  			<td width="5%" height="20">'.$row->pmg.'</td>			  				
		  			<td width="5%" height="20">'.$row->qty.'</td>		  			 			
		  			<td width="5%" height="20">'.number_format($row->UnitPrice,2).'</td>
		  			<td width="8%" height="20">'.number_format($row->TotalAmount,2).'</td>			  			
		  			<td width="8%" height="20" >'.$row->reason.'</td>
		  			<td width="12%" height="20">'.$row->ExchangeCode.'</td>   
		  			<td width="16%" height="20">'.$row->ExchangeName.'</td>
				</tr>';
				
				$html .= $strTable;
			$i++;
		
		}
		
		$html .= '</table>';
		$pdf->writeHTML($html, true, false, true, false, "");
			
		
	}
	else
	{
		$stocks = '<tr><td height="20" colspan="7" align="center"><strong>No record(s) to display.</strong></td></tr>';
		$html = '<table width="100%"  border="1" align="center" cellpadding="0" cellspacing="0">
    			<tr align="center">
					  <td width="5%" height="20" >Line No.</td>
					  <td width="10%" height="20" >Product Code</td>   
					  <td width="16%" height="20" >Product Name</td>
					  <td width="5%" height="20" >UOM</td>
					  <td width="5%" height="20" >PMG</td>			  				
					  <td width="5%" height="20" >Qty</td>		  			 			
					  <td width="5%" height="20" >Price</td>
					  <td width="8%" height="20" >Net Amount</td>			  			
					  <td width="8%" height="20" >Reason</td>
					  <td width="12%" height="20" > Exchange Product Code</td>   
					  <td width="16%" height="20" >Exchange Product Name</td>					
 				</tr>'.$stocks.'</table>';
 				
		$pdf->writeHTML($html, true, false, true, false, "");
	}
         			
	// reset pointer to the last page
	$pdf->lastPage();
	 ob_start();
	// Close and output PDF document
	$pdf->Output("StockLog.pdf", "I");	
?>