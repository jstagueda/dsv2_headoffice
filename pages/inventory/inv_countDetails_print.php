<?php
/*   
  @modified by John Paul Pineda.
  @date October 8, 2012.
  @email paulpineda19@yahoo.com         
*/

  //ini_set('display_errors', '1');
                                       
	require_once('../../initialize.php');
	require_once('../../tcpdf/config/lang/eng.php');
	require_once('../../tcpdf/tcpdf.php');
	 
	include IN_PATH.DS.'scCountDetails_print.php';	
	global $database;
	ini_set('max_execution_time', 1000);
	
	$strTable='';
	$ctr=0;
	$rowalt=0;
	$untiprice=0;
  
  $QtyUnder=0;
  $ValUnder=0;
  $QtyOver=0;
  $ValOver=0;  		
	
  $totQTA=0;					
  $totVTA=0;              
  $totCQ=0;
  $totAC=0;
  
  $totQU=0;
  $totVU=0;                                      
  $totQO=0;
  $totVO=0;
  														 
	$zeroVal=' 0 ';				
	$pcode='';
	
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
	$pdf->SetFont("courier", "", 8);

	// add a page
	$pdf->AddPage();
	
	$html = '<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1">				
		 	<tr align="center">
 		 		<td height="20" width="15%" align="right"><strong>Worksheet : </strong>&nbsp;</td>
		     	<td height="20" width="85%" align="left">'.$transno.'</td>									     
			</tr>
			<tr align="center">
	 		 	<td height="20" align="right"><strong>Document No : </strong>&nbsp;</td>
			    <td height="20" align="left">'.$docno.'</td>									     
			</tr>
			</table><br>';
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");
	
	if($rs_detailsall->num_rows) {
  
		$j = 0; 
		// Estimated string length of product description when the TCPDF engine will wrap the text,
		// therfore consuming and extra row
		$productLenThreshold = 22;
		
		// Threshold to determine whether the number of rows per page should be decremented
		// to accomodate product whose length is greater than $productLenThreshold
		$rowDeletionThreshold = 2; 
		
		// Counter of current row deletion threshold
		$deletionThreshold = 0;
		
		// Estimated number of rows per page
		$numRowsPerPage = 38;
		
		$numRows = $numRowsPerPage;
								
		while($field=$rs_detailsall->fetch_object()) {
      
			$ctr+=1;	
			$rowalt+=1;
			
			$amountcnt=($field->up*$field->CreatedQty)+($field->up*$field->stockRoomCreatedQty);
      $untiprice=number_format($field->up, 2, ".", ",");
      
      //$SaAndSrSOH=$field->SOH+$field->stockRoomSOH;                                            
      $SaAndSrSOH=$field->SOH;                                            
      // $valueToAdjust=($field->SOH*$field->up)+($field->stockRoomSOH*$field->up);
      $valueToAdjust=($field->SOH*$field->up);
                            
      $SaAndSrCreatedQty=$field->CreatedQty+$field->stockRoomCreatedQty;
      
      $totQTA+=$SaAndSrSOH;
      $totVTA+=$valueToAdjust;                      
      $totCQ+=$SaAndSrCreatedQty;                      
      $totAC+=$amountcnt;
      
      $QtyUnder=0;
      $ValUnder=number_format(0, 2, '.', ',');                                                                
      $QtyOver=0;
      $ValOver=number_format(0, 2, '.', ',');
      
      if($SaAndSrSOH>$SaAndSrCreatedQty) {
      
        $QtyUnder=abs($SaAndSrCreatedQty-$SaAndSrSOH);
        $ValUnder=number_format(($QtyUnder*$field->up), 2, '.', ',');		
        
        $totQU+=$QtyUnder;                        
        $totVU+=($QtyUnder*$field->up);                         							    		
      } else if($SaAndSrSOH<$SaAndSrCreatedQty) {
      
        $QtyOver=abs($SaAndSrSOH-$SaAndSrCreatedQty);
        $ValOver=number_format(($QtyOver*$field->up), 2, '.', ',');
        
        $totQO+=$QtyOver;                      
        $totVO+=($QtyOver*$field->up);                        
      }			      
      
			if($j<$numRows) {
      
			 $strTable.='<tr align="center">
		                <td align="left" width="6%" height="16">'.$field->pCode.'</td>
                		<td align="left" width="22%" height="16">'.substr($field->pName, 0, 20).'</td>
                		<td align="right" width="6%" height="16">'.$untiprice.'</td>
                		<td align="right" width="6%" height="16">'.number_format($SaAndSrSOH, 0, '.', ',').'</td>
                		<td align="right" width="8%" height="16">'.number_format($valueToAdjust, 2, '.', ',').'</td>
                		<td align="right" width="6%" height="16">'.number_format($field->CreatedQty, 0, '.', ',').'</td>
                		<td align="right" width="6%" height="16">'.number_format($field->stockRoomCreatedQty, 0, '.', ',').'</td>
                		<td align="right" width="6%" height="16">'.number_format($SaAndSrCreatedQty, 0, '.', ',').'</td>
                		<td align="right" width="8%" height="16">'.number_format((($field->up*$field->CreatedQty)+($field->up*$field->stockRoomCreatedQty)), 2, '.', ',').'</td>		
                		<td align="right" width="6%" height="16">'.number_format($QtyUnder, 0, '.', ',').'</td>
                		<td align="right" width="8%" height="16">'.$ValUnder.'</td>
                		<td align="right" width="6%" height="16">'.number_format($QtyOver, 0, '.', ',').'</td>
                		<td align="right" width="8%" height="16">'.$ValOver.'</td> 
 	                 </tr>';
						
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($field->pName)>$productLenThreshold) {
        
					// Subtract the number of rows only if we reached threshold of the number
					// of rows whose string length is greater than product length threshold
					if ($deletionThreshold!=$rowDeletionThreshold) {
          
						$numRows--;
						$deletionThreshold++;
					} else {
          
						// Reset the current count
						$deletionThreshold = 0;
					}
				}
				$j++;				
			} else {
      
				$strTable.='<tr align="center">
  		                <td align="left"  width="6%" height="16">'.$field->pCode.'</td>
                  		<td align="left"  width="22%" height="16">'.substr($field->pName, 0, 20).'</td>
                  		<td align="right" width="6%" height="16">'.$untiprice.'</td>
                  		<td align="right" width="6%" height="16">'.number_format($SaAndSrSOH, 0, '.', ',').'</td>
                  		<td align="right" width="8%" height="16">'.number_format($valueToAdjust, 2, '.', ',').'</td>
                  		<td align="right" width="6%" height="16">'.number_format($field->CreatedQty, 0, '.', ',').'</td>
                  		<td align="right" width="6%" height="16">'.number_format($field->stockRoomCreatedQty, 0, '.', ',').'</td>
                  		<td align="right" width="6%" height="16">'.number_format($SaAndSrCreatedQty, 0, '.', ',').'</td>
                  		<td align="right" width="8%" height="16">'.number_format((($field->up*$field->CreatedQty)+($field->up*$field->stockRoomCreatedQty)), 2, '.', ',').'</td>		
                  		<td align="right" width="6%" height="16">'.number_format($QtyUnder, 0, '.', ',').'</td>
                  		<td align="right" width="8%" height="16">'.$ValUnder.'</td>
                  		<td align="right" width="6%" height="16">'.number_format($QtyOver, 0, '.', ',').'</td>
                  		<td align="right" width="8%" height="16">'.$ValOver.'</td> 
 	                  </tr>';
							
				$html='<table width="100%"  border="1"  cellpadding="0" cellspacing="0">							
    				 		<tr align="center">
              		<td align="left" width="6%">Item Code</td>
              		<td align="left" width="22%">Description</td>
              		<td align="right" width="6%">Unit&nbsp;&nbsp; Price</td>
              		<td align="right" width="6%">Qty To<br /> Adjust</td>
                  <td align="right" width="8%">Value To<br /> Adjust&nbsp;&nbsp;&nbsp;</td>
              		<td align="right" width="6%">Selling Area&nbsp;&nbsp;&nbsp;&nbsp;</td>
              		<td align="right" width="6%">Stock Room&nbsp;&nbsp;</td>			
              		<td align="right" width="6%">Counted<br /> Qty&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              		<td align="right" width="8%">Amt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Counted</td>
              		<td align="right" width="6%">Qty Under</td>
              		<td align="right" width="8%">Value Under</td>
              		<td align="right" width="6%">Qty Over</td>
              		<td	align="right" width="8%">Value Over</td> 
             	  </tr>'.$strTable.'</table>';
							
				// We only print the page to PDF if we have enough rows to print
	      $pdf->writeHTML($html, true, false, true, false, '');
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$strTable='';
				$j=0;
				$numRows=$numRowsPerPage;					
			}
		}
		
		$rs_detailsall->close();

		// total worksheet variance
    
    // Computation of the Variance for Qty Under/Over and Value Under/Over starts here...    
    $totVUVTA=$zeroVal;
  	$totQUVTA=$zeroVal;
  	$totVOVTA=$zeroVal;
  	$totQOVTA=$zeroVal;
    
		if($totQTA!=0) {
    
      $totQUVTA=round(($totQU/$totQTA) * 100,2);        
    	$totVUVTA=round(($totVU/$totVTA) * 100,2);
      $totQOVTA=round(($totQO/$totQTA) * 100,2);
    	$totVOVTA=round(($totVO/$totVTA) * 100,2);    	
    }
    // Computation of the Variance for Qty Under/Over and Value Under/Over ends here...         	     
					        						                	  				                               
    $totNetVarQty=$totQO-$totQU; // Net Variance: Qty Over.
    $totNetVarVal=$totVO-$totVU; // Net Variance: Value Over.     
    
    // Computation of the Net Variance Percentage starts here...
    $totNetVarQtyPer=$zeroVal;
    $totNetVarValPer=$zeroVal;
      
    if($totQTA!=0) {
    
      $totNetVarQtyPer=round((($totNetVarQty/$totQTA)*100), 2);
      $totNetVarValPer=round((($totNetVarVal/$totVTA)*100), 2);
    }                         
    // Computation of the Net Variance Percentage ends here...    		
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if($strTable!='') {
    
			$html = '<table width="100%"  border="1"  cellpadding="0" cellspacing="0">							
    				 		<tr align="center">
              		<td align="left" width="6%">Item Code</td>
              		<td align="left" width="22%">Description</td>
              		<td align="right" width="6%">Unit&nbsp;&nbsp; Price</td>
              		<td align="right" width="6%">Qty To<br /> Adjust</td>
                  <td align="right" width="8%">Value To<br /> Adjust&nbsp;&nbsp;&nbsp;</td>
              		<td align="right" width="6%">Selling Area&nbsp;&nbsp;&nbsp;&nbsp;</td>
              		<td align="right" width="6%">Stock Room&nbsp;&nbsp;</td>			
              		<td align="right" width="6%">Counted<br /> Qty&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
              		<td align="right" width="8%">Amt&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Counted</td>
              		<td align="right" width="6%">Qty Under</td>
              		<td align="right" width="8%">Value Under</td>
              		<td align="right" width="6%">Qty Over</td>
              		<td	align="right" width="8%">Value Over</td> 
             	  </tr>'.$strTable.'</table>';
		}
		
		$html.='<br />
				<table border="0" width="100%">				
			 	<tr align="center">
			 		 <td height="20" align="left">&nbsp;<strong>Totals</strong></td>
				</tr>
				</table>
				<table width="100%"  border="1" cellpadding="0" cellspacing="0">							
			 	<tr align="center">
					<td width="15%" height="20" align="right">&nbsp;</td>
			    <td width="10%" height="20" align="right"><strong>Qty to Adjust</strong></td>
			    <td width="12%" height="20" align="right"><strong>Value to Adjust</strong></td>
					<td width="10%" height="20" align="right"><strong>Counted Qty</strong></td>
					<td width="11%" height="20" align="right"><strong>Amounted Count</strong></td>
					<td width="10%" height="20" align="right"><strong>Qty Under</strong></td>
					<td width="12%" height="20" align="right"><strong>Value Under</strong></td>
					<td width="10%" height="20" align="right"><strong>Qty Over</strong></td>
					<td width="12%" height="20" align="right"><strong>Value Over</strong></td>					
				 </tr>
				<tr align="center">
					<td height="20" align="right"><strong>Worksheet Total :</strong>&nbsp;</td>	
			    <td height="20" align="right">'.number_format($totQTA, 0, '.', ',').'&nbsp;</td>
			    <td height="20" align="right">'.number_format($totVTA, 2, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totCQ, 0, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totAC, 2, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totQU, 0, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totVU, 2, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totQO, 0, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totVO, 2, '.', ',').'&nbsp;</td>
				</tr>
				<tr align="center">
					<td height="20" align="right"><strong>Variance :</strong>&nbsp;</td>							
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="right">'.$totQUVTA.'%&nbsp;</td>
					<td height="20" align="right">'.$totVUVTA.'%&nbsp;</td>
					<td height="20" align="right">'.$totQOVTA.'%&nbsp;</td>
					<td height="20" align="right">'.$totVOVTA.'%&nbsp;</td>
				</tr>
				<tr align="center">
					<td height="20" align="right"><strong>Branch Total :</strong>&nbsp;</td>						
					<td height="20" align="right">'.number_format($totQTA, 0, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totVTA, 2, '.', ',').'&nbsp;</td>	
					<td height="20" align="right">'.number_format($totCQ, 0, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totAC, 2, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totQU, 0, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totVU, 2, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totQO, 0, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totVO, 2, '.', ',').'&nbsp;</td>
				</tr>
				<tr align="center">
					<td height="20" align="right"><strong>Variance % :</strong>&nbsp;</td>	
					<td height="20" align="center">&nbsp;</td>								
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="right">'.$totQUVTA.'%&nbsp;</td>
					<td height="20" align="right">'.$totVUVTA.'%&nbsp;</td>
					<td height="20" align="right">'.$totQOVTA.'%&nbsp;</td>
					<td height="20" align="right">'.$totVOVTA.'%&nbsp;</td>
				</tr>
				<tr align="center">
					<td height="20" align="right"><strong>Net Variance :</strong>&nbsp;</td>								
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="right">'.number_format($totNetVarQty, 0, '.', ',').'&nbsp;</td>
					<td height="20" align="right">'.number_format($totNetVarVal, 2, '.', ',').'&nbsp;</td>
				</tr>
				<tr align="center">
					<td height="20" align="right"><strong>Net Variance Value % :</strong>&nbsp;</td>							
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="center">&nbsp;</td>
					<td height="20" align="right">'.$totNetVarQtyPer.'%&nbsp;</td>
					<td height="20" align="right">'.$totNetVarValPer.'%&nbsp;</td>
				</tr>
				</table>';
			
		$pdf->writeHTML($html, true, false, true, false, "");
	}

	// reset pointer to the last page
	$pdf->lastPage();
	ob_start();
	// Close and output PDF document
	$pdf->Output("ConfirmInventoryCount.pdf", "I");