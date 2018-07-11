<?php
    require_once "../../initialize.php";
    require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");
	ini_set('max_execution_time', 1000);

    $aiid = $_GET['iaID'];
    $mid = 1;
    $html = '';
    $productDetails = '';
    
    $rs_invadj = $sp->spSelectInvAdjByID($database,$aiid);
	$rs_invadjdetails = $sp->spSelectInvAdjDetailsByID($database,$aiid);
	
	if ($rs_invadj->num_rows)
	{
		while ($row = $rs_invadj->fetch_object())
		{			
			$transno = $row->ID;
			$transdate = $row->TransactionDate;
			$docno = $row->DocumentNo;
			$status = $row->Status;
			$mtype = $row->MovementTypeName;
			$remarks = $row->Remarks;
			$statusid = $row->StatusID;
			$mid = $row->MovementTypeID;
			$createdBy = $row->CreatedBy;
			$confirmedBy = $row->ConfirmedBy; 
			$warehouse = $row->WarehouseName;
		}
	}
	
	// create new PDF document
	//$pdf = new TCPDF("L", PDF_UNIT, PDF_PAGE_FORMAT, true, "UTF-8", false);
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
	
	$html = '<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20" align="left">&nbsp;<strong>Cycle Count Adjustment</strong>&nbsp;&nbsp;<strong>Reference No.:</strong>&nbsp;'.$transno.'&nbsp;&nbsp;<strong>Movement Type:   </strong>'.$mtype.'&nbsp;&nbsp;<strong>Transaction Date:   </strong>'.$transdate.'</td>
			</tr>
			</table>
			<br />';
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");
	
	/*
		 <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td width="20%" height="20" align="right"><strong>Reference No. :   </strong></td>
			<td width="30%" height="20" align="left"> '.$transno.'</td>
			<td width="20%" height="20" align="right"><strong>Movement Type :   </strong></td>
			<td width="20%" height="20" align="left"> '.$mtype.'</td>
		</tr>
		<tr>
			<td height="20" align="right"><strong>Document No. :   </strong></td>
			<td height="20" align="left"> '.$docno.'</td>
			<td height="20" align="right"><strong>Status :   </strong></td>
			<td height="20" align="left"> '.$status.'</td>
		</tr>
		<tr>
			<td height="20" align="right"><strong>Warehouse :   </strong></td>
			<td height="20" align="left"> '.$warehouse.'</td>
			<td height="20" align="right"><strong>Remarks :   </strong></td>
			<td height="20" align="left"> '.$remarks.'</td>
		</tr>
		<tr>
			<td height="20" align="right"><strong>Transaction Date :   </strong></td>
			<td height="20" align="left"> '.$transdate.'</td>
			<td height="20" align="right">&nbsp;</td>
			<td height="20" align="left">&nbsp;</td>
		</tr>
		<tr>
			<td height="20" align="right"><strong>Created By :   </strong></td>
			<td height="20" align="left"> '.$createdBy.'</td>
			<td height="20" align="right">&nbsp;</td>
			<td height="20" align="left">&nbsp;</td>
		</tr>
		<tr>
			<td height="20" align="right"><strong>Confirmed By :   </strong></td>
			<td height="20" align="left"> '.$confirmedBy.'</td>
			<td height="20" align="right">&nbsp;</td>
			<td height="20" align="left">&nbsp;</td>
		</tr>
		</table>
	 */
	
	$ctr = 0;
	$totamt = 0;
	$totqty = 0;
	$rowalt = 0;
				
	if ($rs_invadjdetails->num_rows)
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
		$numRowsPerPage = 24;
		
		$numRows = $numRowsPerPage;
								
		while ($row = $rs_invadjdetails->fetch_object())
		{
			$ctr += 1;	
			//$totamt = $totamt + $row->Amt;
			//$totqty = $totqty + $row->Qty;	
			$rowalt += 1;
			($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
		   	$soh = 0;
		  	if($statusid == 6)
		   	{
	   	 		$soh = $row->SOH ;
		   	}
		   	else
		   	{
	   	 		$soh = $row->PrevBalance;
		   	} 
		   	
		   	if ($j < $numRows)
		   	{
		   		$productDetails .= '<tr>
										<td height="20" align="center">'.$ctr.'</td>
										<td height="20" align="center">'.$row->Code.'</td>
										<td height="20" align="left"> '.$row->Name.'</td>
										<td height="20" align="center">'.$soh.'</td>
										<td height="20" align="center">'.$row->UOM.'</td>
										<td height="20" align="center">'.$row->CreatedQty.'</td>
										<td height="20" align="center">'.$row->Reason.'</td>								
								    </tr>';
							    
			    // Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->Name) > $productLenThreshold) 
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
		   		$productDetails .= '<tr>
									<td height="20" align="center">'.$ctr.'</td>
									<td height="20" align="center">'.$row->Code.'</td>
									<td height="20" align="left"> '.$row->Name.'</td>
									<td height="20" align="center">'.$soh.'</td>
									<td height="20" align="center">'.$row->UOM.'</td>
									<td height="20" align="center">'.$row->CreatedQty.'</td>
									<td height="20" align="center">'.$row->Reason.'</td>								
							    </tr>';
							    
			    $html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			          	<tr>
			        		<td align="left" height="20">&nbsp;<strong>Transaction Details</strong></td>
			          	</tr>
			         	</table>
						<br>
						<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
			           	<tr>
							<td width="7%" height="20" align="center"><strong>Line No.</strong></td>
							<td width="13%" height="20" align="center"><strong>Item Code</strong></td>
					    	<td width="30%" height="20" align="center"><strong>Item Description</strong></td>
							<td width="10%" height="20" align="center"><strong>SOH</strong></td>
							<td width="10%" height="20" align="center"><strong>UOM</strong></td>
							<td width="10%" height="20" align="center"><strong>Quantity</strong></td>
							<td width="20%" height="20" align="center"><strong>Reason</strong></td>								
					   </tr>'.$productDetails.'</table>';	
							    
				// We only print the page to PDF if we have enough rows to print
	      		$pdf->writeHTML($html, true, false, true, false, "");
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$productDetails = '';
				$j = 0;
				$numRows = $numRowsPerPage;		   		
		   	}      
		}                    
		$rs_invadjdetails->close();
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($productDetails != '')
		{
			$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		          	<tr>
		        		<td align="left" height="20">&nbsp;<strong>Transaction Details</strong></td>
		          	</tr>
		         	</table>
					<br>
					<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
		           	<tr>
						<td width="7%" height="20" align="center"><strong>Line No.</strong></td>
						<td width="13%" height="20" align="center"><strong>Item Code</strong></td>
				    	<td width="30%" height="20" align="center"><strong>Item Description</strong></td>
						<td width="10%" height="20" align="center"><strong>SOH</strong></td>
						<td width="10%" height="20" align="center"><strong>UOM</strong></td>
						<td width="10%" height="20" align="center"><strong>Quantity</strong></td>
						<td width="20%" height="20" align="center"><strong>Reason</strong></td>								
				   </tr>'.$productDetails.'</table>';	
		}
		
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
	else 
	{
		$productDetails .= '<tr>
				  				<td height="20" align="center" colspan="7"><strong>No record(s) to display.</strong></td>
							</tr>';
							
		$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
	          	<tr>
	        		<td align="left" height="20">&nbsp;<strong>Transaction Details</strong></td>
	          	</tr>
	         	</table>
				<br>
				<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
	           	<tr>
					<td width="7%" height="20" align="center"><strong>Line No.</strong></td>
					<td width="13%" height="20" align="center"><strong>Item Code</strong></td>
			    	<td width="30%" height="20" align="center"><strong>Item Description</strong></td>
					<td width="10%" height="20" align="center"><strong>SOH</strong></td>
					<td width="10%" height="20" align="center"><strong>UOM</strong></td>
					<td width="10%" height="20" align="center"><strong>Quantity</strong></td>
					<td width="20%" height="20" align="center"><strong>Reason</strong></td>								
			   </tr>'.$productDetails.'</table>';
			   
	   $pdf->writeHTML($html, true, false, true, false, "");	                                     
	}
         		
	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("CycleCountTransaction.pdf", "I");	
?>