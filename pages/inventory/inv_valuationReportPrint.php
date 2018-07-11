<?php
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
		
	global $database;
	ini_set('max_execution_time', 1000);

	$vSearch = $_GET['search'];
	if($_GET['wid'] != ""){
		$warehouseid = $_GET['wid'];
	}else{
		$warehouseid = 0;
	}
	if($_GET['lid'] != ""){
		$location = $_GET['lid'];
	}else{
		$location = 0;
	}
	if($_GET['pmgid'] != ""){
		$pmgid = $_GET['pmgid'];
	}else{
		$pmgid = 0;
	}
	if($_GET['plid'] = ""){
		$plid = $_GET['plid'];
	}else{
		$plid = 0;
	}
	$dteAsOf = $_GET['dteAsOf'];
	
	$details = '';
	$body = '';
	$wid = 'ALL WAREHOUSE';
	$lName = 'ALL LOCATION';
	$pmgCode = 'ALL PMG';
	$plName = 'ALL PRODUCT LINE';
	
	//$query = $sp->spSelectValuationReport_Print($database, $vSearch, $warehouseid,$location,$pmgid,$plid,date("Y-m-d", strtotime($_GET['dteAsOf'])));	

	
	
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
	$pdf->SetFont("courier", "", 10);

	// add a page
	$pdf->AddPage();
	
	/*
	 <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">    	
      	<tr>
      		<td width="20%" height="20" align="right"><strong>Warehouse :   </strong></td>
      		<td width="80%" height="20" align="left">'.$wid.'</td>
      	</tr>
      	<tr>
      		<td height="20" align="right"><strong>Location :   </strong></td>
			<td height="20" align="left">'.$lName.'</td>
     	</tr>
		<tr>
      		<td height="20" align="right"><strong>Product Line :   </strong></td>
      		<td height="20" align="left">'.$plName.'</td>
      	</tr>
      	<tr> 
        	<td height="20" align="right"><strong>Item Code :   </strong></td>
        	<td height="20" align="left">'.$vSearch.'</td>
      	</tr>
      	<tr>     
        	<td height="20" align="right"><strong>PMG :   </strong></td>    
        	<td height="20" align="left">'.$pmgCode.'</td>
      	</tr>
      	<tr>   
    		<td height="20" align="right"><strong>As Of :   </strong></td>     
        	<td height="20" align="left">'.$dteAsOf.'</td>
  		</tr> 
		</table>
	 */
	
	$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    		<tr>
        		<td height="20" align="center"><strong>Inventory Valuation Report</strong></td>
        	</tr>
    		</table>
    		<br />';

	if($warehouseid > 0)
	{
		$qryWarehouse = $sp->spSelectWarehouse($database,$warehouseid,'');
		$qryW = $qryWarehouse->fetch_object();
		$wid  = $qryW->Name;
	}

	if($location > 0)
	{
		$qryLocation = $sp->spSelectLocationByID($database,$location);
		$qryL = $qryLocation->fetch_object();
		$lName =  $qryL->Name;
	}

	if($plid > 0)
	{
		$qryProduct = $sp->spSelectProductLineByID($database,$plid);			
		$qryP = $qryProduct->fetch_object();
		$plName =  $qryP->Name;
	}

	if($pmgid > 0)
	{
		$qryPMG = $sp->spSelectPMGByID($database,$pmgid);			
		$qryPMG = $qryPMG->fetch_object();
		$pmgCode = $qryPMG->Name;
	}

	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false,true, false, "");
	
	//$query = $sp->spSelectValuationReportCount($database, $vSearch, $warehouseid,$location,$pmgid,$plid,date("Y-m-d", strtotime($_GET['dteAsOf'])));	
	$query = $sp->spSelectValuationReport_Print($database, $vSearch, $warehouseid,$location,$pmgid,$plid,date("Y-m-d", strtotime($_GET['dteAsOf'])));	
	$row = $query->fetch_object();
	$num =$query->num_rows;

	if($query->num_rows)
	{
		$j =0; 
		// Estimated string length of product description when the TCPDF engine will wrap the text,
		// therfore consuming and extra row
		$productLenThreshold = 26;
		
		// Threshold to determine whether the number of rows per page should be decremented
		// to accomodate product whose length is greater than $productLenThreshold
		$rowDeletionThreshold = 2; 
		
		// Counter of current row deletion threshold
		$deletionThreshold = 0;
		
		// Estimated number of rows per page
		$numRowsPerPage = 30;
		
		$numRows = $numRowsPerPage;
		$details = "";
		
		$ctrProdLine = "";
		$ctrTotal = "";
		$strTotal = "";
		$ctrTotVal = 0;
		$ctrGrandTotal = 0;
		
		while($row = $query->fetch_object()) 
		{
			$totamt = number_format($row->TotalValue, 2, ".", ",");
			$ctrTest = $row->ProdLine;
			
			if ($j < $numRows) 
			{
				if($ctrProdLine != "")
				{
					if(trim($ctrTest, "") != trim($ctrProdLine, ""))
					{
						$ctrTotVal = number_format($ctrTotVal, 2, ".", ",");	
						$strTotal .= '<tr>
				                  		<td height="20" align="left">&nbsp;</td>
				                  		<td height="20" align="left">&nbsp;</td>
				                  		<td height="20" align="left">&nbsp;</td> 
				                  		<td height="20" align="right">&nbsp;</td>
				                  		<td height="20" align="right"><strong>Sub-total :   </strong></td>
				                  		<td height="20" align="right"><strong>'.$ctrTotVal.'</strong></td>
				                  	</tr>';
						$ctrTotVal = $row->TotalValue;
					}		
					else
					{
						$strTotal = "";
						$ctrTotVal = $ctrTotVal + $row->TotalValue;
					}		
				}
				else
				{	
					$ctrTotVal = $ctrTotVal + $row->TotalValue;
				}
				
		  	 	$details .= $strTotal.'<tr>
	              						<td height="20" align="left">&nbsp;'.$row->ProdLineCode.'</td>
		                  				<td height="20" align="left">&nbsp;'.$row->ItemCode.'</td>
		                  				<td height="20" align="left">&nbsp;'.$row->ItemDescription.'</td> 
		                  				<td height="20" align="right">'.$row->ProductPrice.'&nbsp;&nbsp;</td>
		                  				<td height="20" align="right">'.$row->SOH.'&nbsp;&nbsp;</td>
		                  				<td height="20" align="right">'.$totamt.'&nbsp;&nbsp;</td>
	        						</tr>'; 
		  		$ctrProdLine = $row->ProdLine;
	  			$ctrGrandTotal = $ctrGrandTotal + $row->TotalValue;
	  			
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->ItemDescription) > $productLenThreshold) 
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
				$details .= $strTotal.'<tr>
	              						<td height="20" align="left">&nbsp;'.$row->ProdLineCode.'</td>
		                  				<td height="20" align="left">&nbsp;'.$row->ItemCode.'</td>
		                  				<td height="20" align="left">&nbsp;'.$row->ItemDescription.'</td> 
		                  				<td height="20" align="right">'.$row->ProductPrice.'&nbsp;&nbsp;</td>
		                  				<td height="20" align="right">'.$row->SOH.'&nbsp;&nbsp;</td>
		                  				<td height="20" align="right">'.$totamt.'&nbsp;&nbsp;</td>
	        						</tr>'; 
		  		$ctrProdLine = $row->ProdLine;
	  			$ctrGrandTotal = $ctrGrandTotal + $row->TotalValue;
	  			
				$html = '<br>
	    		<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
	    		<tr>
	      			<td width="10%" height="20" align="center"><strong>Product Line</strong></td>
	          		<td width="20%" height="20" align="center"><strong>Item Code</strong></td>
			  		<td width="30%" height="20" align="center"><strong>Item Description</strong></td>
			  		<td width="10%" height="20" align="center"><strong>Price</strong></td>
			  		<td width="10%" height="20" align="center"><strong>SOH</strong></td>
		  			<td width="20%" height="20" align="center"><strong>Total Value</strong></td>
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
	      			<td width="10%" height="20" align="center"><strong>Product Line</strong></td>
	          		<td width="20%" height="20" align="center"><strong>Item Code</strong></td>
			  		<td width="30%" height="20" align="center"><strong>Item Description</strong></td>
			  		<td width="10%" height="20" align="center"><strong>Price</strong></td>
			  		<td width="10%" height="20" align="center"><strong>SOH</strong></td>
		  			<td width="20%" height="20" align="center"><strong>Total Value</strong></td>
				</tr>'.$details.
				'</table>';
				
			$pdf->writeHTML($html, true, false, true, false, "");
		}			
			
		$ctrGrandTotal = number_format($ctrGrandTotal, 2, ".", ",");	
		$details .= '<tr>
                  		<td height="20" align="left">&nbsp;</td>
	                  	<td height="20" align="center">&nbsp;</td>
	                  	<td height="20" align="left">&nbsp;</td>
	                  	<td height="20" colspan="2" align="right"><strong>Grand Total :   </strong></td>
	                  	<td height="20" align="center"><strong>'.$ctrGrandTotal.'</strong></td>
                 	</tr>';	 
	}
	else
	{
		$details = '<tr><td height="20" colspan="6" align="center"><strong>No record(s) to display.</strong></td></tr>';
		$html = '<br>
	    		<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
	    		<tr>
	      			<td width="10%" height="20" align="center"><strong>Product Line</strong></td>
	          		<td width="20%" height="20" align="center"><strong>Item Code</strong></td>
			  		<td width="30%" height="20" align="center"><strong>Item Description</strong></td>
			  		<td width="10%" height="20" align="center"><strong>Price</strong></td>
			  		<td width="10%" height="20" align="center"><strong>SOH</strong></td>
		  			<td width="20%" height="20" align="center"><strong>Total Value</strong></td>
				</tr>'.$details.
				'</table>';
		$pdf->writeHTML($html, true, false, true, false, "");
	}
		
	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("ValuationReport.pdf", "I");
?>