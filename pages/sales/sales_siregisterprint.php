<?PHP 
	require_once "../../initialize.php";
	require_once("../../tcpdf/config/lang/eng.php");
	require_once("../../tcpdf/tcpdf.php");	

	global $database;
	ini_set('max_execution_time', 500);
	
	$f = $_GET['f'];
	$t = $_GET['t'];
	$s = $_GET['s'];
	$e = $_GET['e'];
	
	$tmpftxndate = strtotime($_GET['f']);
	$fromDate = date("Y-m-d", $tmpftxndate);	
	$tmpttxndate = strtotime($_GET['t']. " +1 day");
	$toDate = date("Y-m-d", $tmpttxndate);
	
	$txnRegister = '';
	$rs_stocklog = $sp->spSelectSIRegisterPrint($database, $fromDate, $toDate, $s, $e);
	$rs_emp_total = $sp->spSelectCreatedBySalesInvoice($database, $fromDate, $toDate, "");

	//Get all the rows
	$num = $rs_stocklog->num_rows;
	$cnt = 0;
	$tmp_txndate = "";
	$totCampaignPrice = 0;
	$totBasicDicsount = 0;
	$totDiscGrossSales = 0;
	$totAddtnlDiscount = 0;    
	$totSaleswithvat = 0;
	$totAmountwovat = 0;                 
	$totVatAmount = 0;
	$totAddtlDiscountPrev = 0;
	$totNetSalesValue = 0;
	$totInvoiceAmount = 0;
	$totCPI = 0;
	
	$campaign_cpi = 0;
	$dgross_cpi = 0;
	$iamount_cpi = 0;
	
	$rep_totCampaignPrice = 0;
	$rep_totBasicDicsount = 0;
	$rep_totDiscGrossSales = 0;
	$rep_totAddtnlDiscount = 0;
	$rep_totSaleswithvat = 0;
	$rep_totAmountwovat = 0;
	$rep_totAddtlDiscountPrev = 0;
	$rep_totNetSalesValue = 0;
	$rep_totInvoiceAmount = 0;
	$rep_totVatAmount = 0;
	$rep_campaign_cpi = 0;
	$rep_dgross_cpi = 0;
	$rep_iamount_cpi = 0;
	
	$tot_inv_38 = 0;
	$tot_inv_52 = 0;
	$inv_38 = 0;
	$inv_52 = 0;
	$rep_tot_inv_38 = 0;
	$rep_tot_inv_52 = 0;
	
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
	$pdf->SetFont("courier", "", 7);

	// add a page
	$pdf->AddPage();
	
	$html = '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    		<tr>
  				<td height="20" align="center"><strong>Invoice Register</strong></td>
    		</tr>
  			</table>
  			<br>
  			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">							
			<tr>
				<td width="10%" height="20" align="right"><strong>From Date :   </strong></td>
				<td width="90%" height="20" align="left">&nbsp;'.$f.'</td>
			</tr>
			<tr>
				<td height="20" align="right"><strong>To Date :   </strong></td>
				<td height="20" align="left">&nbsp;'.$t.'</td>
			</tr>
			</table>
			<br>';
	
	// Print text using writeHTML()
	$pdf->writeHTML($html, true, false, true, false, "");
	
	if($num != 0)
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
		
		while($row = $rs_stocklog->fetch_object()) 
		{	 
			$cnt ++;
			$txndate = strtotime($row->TxnDate);
			$txndate = date('d/m/Y', $txndate);
			$txno = $row->TxnNo;
			$orderno = $row->OrderNo;
			$customerName = $row->CustomerName;
			$status = $row->Status;
			$customerCode = $row->CustomerCode;
			$campaignPrice = $row->CampaignPrice;
			$basicDicsount = $row->BasicDiscount;
			$discGrossSales = $campaignPrice - $basicDicsount;
			$addtnlDiscount = $row->AddtlDiscount;		
			$saleswithvat = $campaignPrice - $basicDicsount - $addtnlDiscount;
	        $amountwovat = $saleswithvat / 1.12;                 
	        $vatAmount = $saleswithvat - $amountwovat;
	        $addtlDiscountPrev = $row->AddtlDiscountPrev;
	        $netSalesValue = $amountwovat - $addtlDiscountPrev;
	        $invoiceAmount = $netSalesValue + $vatAmount;
	        
	        if ($row->CreditTermID == 2)
	        {
	        	$inv_38 = $invoiceAmount;					        	
	        }
	        else if ($row->CreditTermID == 3)
	        {
	        	$inv_52 = $invoiceAmount;					        	
	        }
	        else
	        {
	        	$inv_38 = 0;
				$inv_52 = 0;					        	
	        }
	         
	         //$campaign_cpi = $totCampaignPrice - $row->TotalCPI;   
			 //$dgross_cpi = $totDiscGrossSales - $row->TotalCPI;
			 //$iamount_cpi = $totInvoiceAmount - $row->TotalCPI;
			 
			 $rep_totCampaignPrice += $campaignPrice;
			 $rep_totBasicDicsount += $basicDicsount;
			 $rep_totDiscGrossSales += $discGrossSales;
			 $rep_totAddtnlDiscount += $addtnlDiscount;
			 $rep_totSaleswithvat += $saleswithvat;
			 $rep_totAmountwovat += $amountwovat;
			 $rep_totAddtlDiscountPrev += $addtlDiscountPrev;
			 $rep_totNetSalesValue += $netSalesValue;
			 $rep_totInvoiceAmount += $invoiceAmount;
			 $rep_totVatAmount += $vatAmount;
			 $rep_campaign_cpi += $campaignPrice;   
			 $rep_dgross_cpi += $discGrossSales;
			 $rep_iamount_cpi += $invoiceAmount;
			 $rep_tot_inv_38 += $inv_38;
			 $rep_tot_inv_52 += $inv_52;
			 
			 if ($j < $numRows)
			 {
			 	if (($tmp_txndate != $txndate && $cnt != 1))
			 	{
					$txnRegister .= '<tr>
					            	<td height="20" align="left">&nbsp;</td>
					                <td height="20" align="left">&nbsp;</td>
					                <td height="20" align="left"><strong>Total for '.$tmp_txndate. ' :   </strong></td>
					                <td height="20" align="right">'.number_format($totCampaignPrice, 2).'</td>
					                <td height="20" align="right">'.number_format($totBasicDicsount, 2).'</td>
					                <td height="20" align="right">'.number_format($totDiscGrossSales, 2).'</td>
					                <td height="20" align="right">'.number_format($totAddtnlDiscount, 2).'</td>
					                <td height="20" align="right">'.number_format($totSaleswithvat, 2).'</td>
					                <td height="20" align="right">'.number_format($totVatAmount, 2).'</td>
					                <td height="20" align="right">'.number_format($totAmountwovat, 2).'</td>
					                <td height="20" align="right">'.number_format($totAddtlDiscountPrev, 2).'</td>
					                <td height="20" align="right">'.number_format($totNetSalesValue, 2).'</td>
					                <td height="20" align="right">'.number_format($totInvoiceAmount, 2).'</td>
					                <td height="20" align="right">'.number_format($tot_inv_38, 2).'</td>
					                <td height="20" align="right">'.number_format($tot_inv_52, 2).'</td>
					                <td height="20" align="left">&nbsp;</td>
				     			</tr>
				         		<tr>
					            	<td height="20" align="left">&nbsp;</td>
					                <td height="20" align="left">&nbsp;</td>
					                <td height="20" align="left">Less CPI :</td>
					                <td height="20" align="right">'.number_format($campaign_cpi, 2).'</td>
					                <td height="20" align="left">&nbsp;</td>
					                <td height="20" align="right">'.number_format($dgross_cpi, 2).'</td>
					                <td height="20" align="right">0.00</td>
					                <td height="20" align="right">0.00</td>
					                <td height="20" align="right">0.00</td>
					                <td height="20" align="right">0.00</td>
					                <td height="20" align="right">0.00</td>
					                <td height="20" align="right">0.00</td>
					                <td height="20" align="right">'.number_format($iamount_cpi, 2).'</td>
					                <td height="20" align="right">0.00</td>
					                <td height="20" align="right">0.00</td>
					                <td height="20" align="left">&nbsp;</td>
					           </tr>';
				    
				    $totCampaignPrice = 0;
				    $totBasicDicsount = 0;
				    $totDiscGrossSales = 0;
				    $totAddtnlDiscount = 0;
				    $totSaleswithvat = 0;
				    $totVatAmount = 0;
				    $totAmountwovat = 0;
				    $totAddtlDiscountPrev = 0;
				    $totNetSalesValue = 0;
				    $totInvoiceAmount = 0;
				    $tot_inv_38 = 0;
				 	$tot_inv_52 = 0;	
				    $campaign_cpi = 0;
				    $dgross_cpi = 0;
				    $iamount_cpi = 0;
				    $totCampaignPrice += $campaignPrice;
				    $totBasicDicsount += $basicDicsount;
				    $totDiscGrossSales += $discGrossSales;
				    $totAddtnlDiscount += $addtnlDiscount;    
				    $totSaleswithvat += $saleswithvat;
				    $totAmountwovat += $amountwovat;                 
				    $totVatAmount += $vatAmount;
				    $totAddtlDiscountPrev += $addtlDiscountPrev;
				    $totNetSalesValue += $netSalesValue;
				    $totInvoiceAmount += $invoiceAmount;
				    $tot_inv_38 += $inv_38;
				 	$tot_inv_52 += $inv_52;						  		
				}
				else
				{
					$totCampaignPrice += $campaignPrice;
				    $totBasicDicsount += $basicDicsount;
				    $totDiscGrossSales += $discGrossSales;
				    $totAddtnlDiscount += $addtnlDiscount;    
				    $totSaleswithvat += $saleswithvat;
				    $totAmountwovat += $amountwovat;                 
				    $totVatAmount += $vatAmount;
				    $totAddtlDiscountPrev += $addtlDiscountPrev;
				    $totNetSalesValue += $netSalesValue;
				    $totInvoiceAmount += $invoiceAmount;
				    $tot_inv_38 += $inv_38;
				 	$tot_inv_52 += $inv_52;
				}
				
				$campaign_cpi = $totCampaignPrice - $row->TotalCPI;   
				$dgross_cpi = $totDiscGrossSales - $row->TotalCPI;
				$iamount_cpi = $totInvoiceAmount - $row->TotalCPI;
				     
				$txnRegister .= '<tr>
							  		<td height="20" align="left">&nbsp;'.$orderno.'</td>
					              	<td height="20" align="left">&nbsp;'.$txno.'</td>
					              	<td height="20" align="left">&nbsp;'.$customerCode.'-'.$customerName.'</td>
					              	<td height="20" align="right">'.number_format($campaignPrice, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($basicDicsount, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($discGrossSales, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($addtnlDiscount, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($saleswithvat, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($vatAmount, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($amountwovat, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($addtlDiscountPrev, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($netSalesValue, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($invoiceAmount, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($inv_38, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="right">'.number_format($inv_52, 2).'&nbsp;&nbsp;</td>
					              	<td height="20" align="center">'.$status.'</td>
								</tr>';
					
				$tmp_txndate = $txndate;
				if ($cnt == $num)
				{
					$campaign_cpi += $row->TotalCPI;   
					$dgross_cpi += $row->TotalCPI;
					$iamount_cpi += $row->TotalCPI;
					
					$txnRegister .= '<tr>		  			
							            	<td height="20" align="left">&nbsp;</td>
							                <td height="20" align="left">&nbsp;</td>
							                <td height="20" align="left"><strong>Total for '.$tmp_txndate.' :   </strong></td>
							                <td height="20" align="right">'.number_format($totCampaignPrice, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">'.number_format($totBasicDicsount, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">'.number_format($totDiscGrossSales, 2).'&nbsp;&nbsp;</td>
											<td height="20" align="right">'.number_format($totAddtnlDiscount, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">'.number_format($totSaleswithvat, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">'.number_format($totVatAmount, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">'.number_format($totAmountwovat, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">'.number_format($totAddtlDiscountPrev, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">'.number_format($totNetSalesValue, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">'.number_format($totInvoiceAmount, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
							                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
							                <td height="20" align="left">&nbsp;</td>
						     		</tr>
						         	<tr>
							            	<td height="20" align="left">&nbsp;</td>
							                <td height="20" align="left">&nbsp;</td>
							                <td height="20" align="left">Less CPI :   </td>
							                <td height="20" align="right">'.number_format($campaign_cpi, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="left">&nbsp;</td>
							                <td height="20" align="right">'.number_format($dgross_cpi, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
							                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
							                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
							                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
							                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
							                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
							                <td height="20" align="right">'.number_format($iamount_cpi, 2).'&nbsp;&nbsp;</td>
							                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
							                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
							                <td height="20" align="left">&nbsp;</td>
									</tr>';						  		
				}  
				
			 	// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($customerName) > $productLenThreshold) 
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
			 	if (($tmp_txndate != $txndate && $cnt != 1))
		  	 	{
		  			$txnRegister .= '<tr>
						            	<td height="20" align="left">&nbsp;</td>
						                <td height="20" align="left">&nbsp;</td>
						                <td height="20" align="left"><strong>Total for '.$tmp_txndate. ' :   </strong></td>
						                <td height="20" align="right">'.number_format($totCampaignPrice, 2).'</td>
						                <td height="20" align="right">'.number_format($totBasicDicsount, 2).'</td>
						                <td height="20" align="right">'.number_format($totDiscGrossSales, 2).'</td>
						                <td height="20" align="right">'.number_format($totAddtnlDiscount, 2).'</td>
						                <td height="20" align="right">'.number_format($totSaleswithvat, 2).'</td>
						                <td height="20" align="right">'.number_format($totVatAmount, 2).'</td>
						                <td height="20" align="right">'.number_format($totAmountwovat, 2).'</td>
						                <td height="20" align="right">'.number_format($totAddtlDiscountPrev, 2).'</td>
						                <td height="20" align="right">'.number_format($totNetSalesValue, 2).'</td>
						                <td height="20" align="right">'.number_format($totInvoiceAmount, 2).'</td>
						                <td height="20" align="right">'.number_format($tot_inv_38, 2).'</td>
						                <td height="20" align="right">'.number_format($tot_inv_52, 2).'</td>
						                <td height="20" align="left">&nbsp;</td>
			             			</tr>
				             		<tr>
						            	<td height="20" align="left">&nbsp;</td>
						                <td height="20" align="left">&nbsp;</td>
						                <td height="20" align="left">Less CPI :</td>
						                <td height="20" align="right">'.number_format($campaign_cpi, 2).'</td>
						                <td height="20" align="left">&nbsp;</td>
						                <td height="20" align="right">'.number_format($dgross_cpi, 2).'</td>
						                <td height="20" align="right">0.00</td>
						                <td height="20" align="right">0.00</td>
						                <td height="20" align="right">0.00</td>
						                <td height="20" align="right">0.00</td>
						                <td height="20" align="right">0.00</td>
						                <td height="20" align="right">0.00</td>
						                <td height="20" align="right">'.number_format($iamount_cpi, 2).'</td>
						                <td height="20" align="right">0.00</td>
						                <td height="20" align="right">0.00</td>
						                <td height="20" align="left">&nbsp;</td>
						           </tr>';
				    
				    $totCampaignPrice = 0;
				    $totBasicDicsount = 0;
				    $totDiscGrossSales = 0;
				    $totAddtnlDiscount = 0;
				    $totSaleswithvat = 0;
				    $totVatAmount = 0;
				    $totAmountwovat = 0;
				    $totAddtlDiscountPrev = 0;
				    $totNetSalesValue = 0;
				    $totInvoiceAmount = 0;
				    $tot_inv_38 = 0;
				 	$tot_inv_52 = 0;	
			        $campaign_cpi = 0;
			        $dgross_cpi = 0;
			        $iamount_cpi = 0;
			        $totCampaignPrice += $campaignPrice;
			        $totBasicDicsount += $basicDicsount;
			        $totDiscGrossSales += $discGrossSales;
			        $totAddtnlDiscount += $addtnlDiscount;    
			        $totSaleswithvat += $saleswithvat;
			        $totAmountwovat += $amountwovat;                 
			        $totVatAmount += $vatAmount;
			        $totAddtlDiscountPrev += $addtlDiscountPrev;
			        $totNetSalesValue += $netSalesValue;
			        $totInvoiceAmount += $invoiceAmount;
			        $tot_inv_38 += $inv_38;
				 	$tot_inv_52 += $inv_52;						  		
			  	}
			  	else
			  	{
			  		$totCampaignPrice += $campaignPrice;
			        $totBasicDicsount += $basicDicsount;
			        $totDiscGrossSales += $discGrossSales;
			        $totAddtnlDiscount += $addtnlDiscount;    
			        $totSaleswithvat += $saleswithvat;
			        $totAmountwovat += $amountwovat;                 
			        $totVatAmount += $vatAmount;
			        $totAddtlDiscountPrev += $addtlDiscountPrev;
			        $totNetSalesValue += $netSalesValue;
			        $totInvoiceAmount += $invoiceAmount;
			        $tot_inv_38 += $inv_38;
				 	$tot_inv_52 += $inv_52;
			  	}
			  	
			  	$campaign_cpi = $totCampaignPrice - $row->TotalCPI;   
			 	$dgross_cpi = $totDiscGrossSales - $row->TotalCPI;
			 	$iamount_cpi = $totInvoiceAmount - $row->TotalCPI;
			  	     
		  		$txnRegister .= '<tr>
							  		<td height="20" align="left">&nbsp;'.$orderno.'</td>
			                      	<td height="20" align="left">&nbsp;'.$txno.'</td>
			                      	<td height="20" align="left">&nbsp;'.$customerCode.'-'.$customerName.'</td>
			                      	<td height="20" align="right">'.number_format($campaignPrice, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($basicDicsount, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($discGrossSales, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($addtnlDiscount, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($saleswithvat, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($vatAmount, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($amountwovat, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($addtlDiscountPrev, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($netSalesValue, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($invoiceAmount, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($inv_38, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="right">'.number_format($inv_52, 2).'&nbsp;&nbsp;</td>
			                      	<td height="20" align="center">'.$status.'</td>
		  						</tr>';
			  		
			  	$tmp_txndate = $txndate;
			  	if ($cnt == $num)
			  	{
			  		$campaign_cpi += $row->TotalCPI;   
			 		$dgross_cpi += $row->TotalCPI;
			 		$iamount_cpi += $row->TotalCPI;
			 		
			  		$txnRegister .= '<tr>		  			
						            	<td height="20" align="left">&nbsp;</td>
						                <td height="20" align="left">&nbsp;</td>
						                <td height="20" align="left"><strong>Total for '.$tmp_txndate.' :   </strong></td>
						                <td height="20" align="right">'.number_format($totCampaignPrice, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">'.number_format($totBasicDicsount, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">'.number_format($totDiscGrossSales, 2).'&nbsp;&nbsp;</td>
			 							<td height="20" align="right">'.number_format($totAddtnlDiscount, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">'.number_format($totSaleswithvat, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">'.number_format($totVatAmount, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">'.number_format($totAmountwovat, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">'.number_format($totAddtlDiscountPrev, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">'.number_format($totNetSalesValue, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">'.number_format($totInvoiceAmount, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
						                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
						                <td height="20" align="left">&nbsp;</td>
			             		</tr>
				             	<tr>
						            	<td height="20" align="left">&nbsp;</td>
						                <td height="20" align="left">&nbsp;</td>
						                <td height="20" align="left">Less CPI :   </td>
						                <td height="20" align="right">'.number_format($campaign_cpi, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="left">&nbsp;</td>
						                <td height="20" align="right">'.number_format($dgross_cpi, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
						                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
						                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
						                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
						                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
						                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
						                <td height="20" align="right">'.number_format($iamount_cpi, 2).'&nbsp;&nbsp;</td>
						                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
						                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
						                <td height="20" align="left">&nbsp;</td>
								</tr>';						  		
			  	}
			  	
			  	$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
						<tr>
			    			<td height="20" align="center" width="5%"><strong>Order</strong></td>
			      			<td height="20" align="center" width="5%"><strong>Invoice No.</strong></td>
				          	<td height="20" align="center" width="15%"><strong>IBM/IGS-Name</strong></td>
				          	<td height="20" align="center" width="5%"><strong>Price</strong></td>
				          	<td height="20" align="center" width="5%"><strong>Basic Discount</strong></td>
				          	<td height="20" align="center" width="5%"><strong>Gross Sale</strong></td>
				          	<td height="20" align="center" width="6%"><strong>Addl Discount</strong></td>
				          	<td height="20" align="center" width="6%"><strong>Sales w/VAT</strong></td>
				          	<td height="20" align="center" width="6%"><strong>12% VAT</strong></td>
				          	<td height="20" align="center" width="6%"><strong>Vatable</strong></td>
				          	<td height="20" align="center" width="6%"><strong>ADPP</strong></td>
				          	<td height="20" align="center" width="6%"><strong>Net</strong></td>
				          	<td height="20" align="center" width="6%"><strong>Invoice Amt</strong></td>
				          	<td height="20" align="center" width="6%"><strong>Inv 38 days</strong></td>
				          	<td height="20" align="center" width="6%"><strong>Inv 52 days</strong></td>
				          	<td height="20" align="center" width="6%"><strong>Status</strong></td>
			  			</tr>'.$txnRegister.'</table>';  
	  			  
			 	// We only print the page to PDF if we have enough rows to print
	      		$pdf->writeHTML($html, true, false, true, false, "");
				$pdf->AddPage();
				
				// reset the row counter and the details 
				$txnRegister = '';
				$j = 0;
				$numRows = $numRowsPerPage;			 	
			 }
  		}
  		$rs_stocklog->close();
  		
  		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($txnRegister != '')
		{
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
					<tr>
		    			<td height="20" align="center" width="5%"><strong>Order</strong></td>
		      			<td height="20" align="center" width="5%"><strong>Invoice No.</strong></td>
			          	<td height="20" align="center" width="15%"><strong>IBM/IGS-Name</strong></td>
			          	<td height="20" align="center" width="5%"><strong>Price</strong></td>
			          	<td height="20" align="center" width="5%"><strong>Basic Discount</strong></td>
			          	<td height="20" align="center" width="5%"><strong>Gross Sale</strong></td>
			          	<td height="20" align="center" width="6%"><strong>Addl Discount</strong></td>
			          	<td height="20" align="center" width="6%"><strong>Sales w/VAT</strong></td>
			          	<td height="20" align="center" width="6%"><strong>12% VAT</strong></td>
			          	<td height="20" align="center" width="6%"><strong>Vatable</strong></td>
			          	<td height="20" align="center" width="6%"><strong>ADPP</strong></td>
			          	<td height="20" align="center" width="6%"><strong>Net</strong></td>
			          	<td height="20" align="center" width="6%"><strong>Invoice Amt</strong></td>
			          	<td height="20" align="center" width="6%"><strong>Inv 38 days</strong></td>
			          	<td height="20" align="center" width="6%"><strong>Inv 52 days</strong></td>
			          	<td height="20" align="center" width="6%"><strong>Status</strong></td>
		  			</tr>'.$txnRegister; 
		}
		else
		{
			$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">';
		}
  		
  		$html .= '<tr>
		            	<td height="20" align="left">&nbsp;</td>
		                <td height="20" align="left">&nbsp;</td>
		                <td height="20" align="right"><strong>Report Total :   </strong></td>
		                <td height="20" align="right">'.number_format($rep_totCampaignPrice, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_totBasicDicsount, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_totDiscGrossSales, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_totAddtnlDiscount, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_totSaleswithvat, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_totVatAmount, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_totAmountwovat, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_totAddtlDiscountPrev, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_totNetSalesValue, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_totInvoiceAmount, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_tot_inv_38, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_tot_inv_52, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="left">&nbsp;</td>
	             	</tr>
	             	<tr>
		            	<td height="20" align="left">&nbsp;</td>
		                <td height="20" align="left">&nbsp;</td>
		                <td height="20" align="left">Less CPI :   </td>
		                <td height="20" align="right">'.number_format($rep_campaign_cpi, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="left">&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_dgross_cpi, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
		                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
		                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
		                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
		                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
		                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
		                <td height="20" align="right">'.number_format($rep_iamount_cpi, 2).'&nbsp;&nbsp;</td>
		                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
		                <td height="20" align="right">0.00&nbsp;&nbsp;</td>
		               	<td height="20" align="left">&nbsp;</td>
					</tr>';

	 	if($rs_emp_total->num_rows != 0)
	 	{
	 		while($row = $rs_emp_total->fetch_object()) 
	     	{
	 			$txnRegister .= '<tr>
					            	<td height="20" align="left">&nbsp;</td>
					                <td height="20" align="left">&nbsp;</td>
					                <td height="20" align="left"><strong>Total for '.$row->Employee.' :   </strong></td>
					                <td height="20" align="right">'.number_format($row->CampaignPrice, 2).'&nbsp;&nbsp;</td>
					                <td height="20" align="right">'.number_format($row->BasicDiscount, 2).'&nbsp;&nbsp;</td>
					                <td height="20" align="right">'.number_format($row->DiscGrossSales, 2).'&nbsp;&nbsp;</td>
					                <td height="20" align="right">'.number_format($row->AddtlDiscount, 2).'&nbsp;&nbsp;</td>
					                <td height="20" align="right">'.number_format($row->SalesWithVat, 2).'&nbsp;&nbsp;</td>
					                <td height="20" align="right">'.number_format($row->AmountWOVat, 2).'&nbsp;&nbsp;</td>
					                <td height="20" align="right">'.number_format($row->VatAmount, 2).'&nbsp;&nbsp;</td>
					                <td height="20" align="right">'.number_format($row->AddtlDiscountPrev, 2).'&nbsp;&nbsp;</td>
					                <td height="20" align="right">'.number_format(($row->NetSalesValue - $row->VatAmount), 2).'&nbsp;&nbsp;</td>
					                <td height="20" align="right">'.number_format($row->NetSalesValue, 2).'&nbsp;&nbsp;</td>
					                <td height="20" align="left">&nbsp;</td>
					                <td height="20" align="left">&nbsp;</td>
					                <td height="20" align="left">&nbsp;</td>
			             		</tr>';
	 		}
	 	}
	 	
	 	$html .= '</table>';
	 	$pdf->writeHTML($html, true, false, true, false, "");
	}
	else
	{
		$txnRegister .= '<tr><td height="20" colspan="17" align="left"><strong>No record(s) to display.</strong></td></tr>';
		$html = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
				<tr>
	    			<td height="20" align="center" width="5%"><strong>Order</strong></td>
	      			<td height="20" align="center" width="5%"><strong>Invoice No.</strong></td>
		          	<td height="20" align="center" width="15%"><strong>IBM/IGS-Name</strong></td>
		          	<td height="20" align="center" width="5%"><strong>Price</strong></td>
		          	<td height="20" align="center" width="5%"><strong>Basic Discount</strong></td>
		          	<td height="20" align="center" width="5%"><strong>Gross Sale</strong></td>
		          	<td height="20" align="center" width="6%"><strong>Addl Discount</strong></td>
		          	<td height="20" align="center" width="6%"><strong>Sales w/VAT</strong></td>
		          	<td height="20" align="center" width="6%"><strong>12% VAT</strong></td>
		          	<td height="20" align="center" width="6%"><strong>Vatable</strong></td>
		          	<td height="20" align="center" width="6%"><strong>ADPP</strong></td>
		          	<td height="20" align="center" width="6%"><strong>Net</strong></td>
		          	<td height="20" align="center" width="6%"><strong>Invoice Amt</strong></td>
		          	<td height="20" align="center" width="6%"><strong>Inv 38 days</strong></td>
		          	<td height="20" align="center" width="6%"><strong>Inv 52 days</strong></td>
		          	<td height="20" align="center" width="6%"><strong>Status</strong></td>
	  			</tr>'.$txnRegister.'</table>';      					
		$pdf->writeHTML($html, true, false, true, false, "");
	}

	// reset pointer to the last page
	$pdf->lastPage();
	
	// Close and output PDF document
	$pdf->Output("SIRegister.pdf", "I");	
?>