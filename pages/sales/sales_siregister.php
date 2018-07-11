<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<?PHP 
	include IN_PATH.DS."scSIRegister.php";
?>

<script src="js/jxPagingSIRegister.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<form name="frmORRegister" method="post" action="index.php?pageid=99">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
  		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
	    		<tr>
	      			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
	    		</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td class="txtgreenbold13">Sales Invoice Register</td>
    		<td>&nbsp;</td>
		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
	    	<td>
			  	<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		        <tr>
		          	<td width="8%">&nbsp;</td>
		          	<td width="91%" align="right">&nbsp;</td>
		        </tr>
	    		<tr>
		          	<td height="20" class="padr5" align="right">From Date :</td>
		          	<td height="20">
		          		<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $fromdate; ?>">
	    				<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
					</td>				 
				</tr>
				<tr>
		          	<td height="20" class="padr5" align="right"">To Date :</td>
		          	<td height="20">
		          		<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $todate; ?>">	        			
						<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
						<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>
				<tr>
		          	<td height="20"  class="padr5" align="right">Employee :</td>
		          	<td height="20">
		          		<select name="cboEmployee" id="cboEmployee" class="txtfield">
		          			<option value="0">[ALL]</option>
		          			<?php
		          				if($rs_emp->num_rows != 0)
      							{
      								$sel = "";
      								while($row = $rs_emp->fetch_object()) 
									{
										if ($_POST['cboEmployee'] == $row->ID)
										{
											$sel = "selected";												
										}
										else
										{
											$sel = "";												
										}
										echo "<option value='$row->ID' $sel>$row->Employee</option>";
									}
									$rs_emp->close();
      							}
		          			?>
		          		</select>
		          	</td>
				</tr>
				<tr>
		          	<td height="20"  class="padr5" align="right">Search :</td>            
		          	<td height="20" align="left">
						<input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="30" value="<?php echo $vSearch; ?>" />
						<input name="btnSearch" type="submit" class="btn" value="Search">						 
				  	</td>
	    		</tr>
		        <tr>
		          	<td colspan="2" height="20">&nbsp;</td>
		        </tr>
	    		</table>
			</td>
  		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2 txtredbold">Cash Receipts</td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="95%" align="center" border="0" cellpadding="0" cellspacing="1" class="bordergreen">
		<tr>
			<td>
				<table width="100%" align="center" border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
		        <tr align="center" class="tab">
		        	<td height="20" class="bdiv_r" align="center" width="7%">Order</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="7%">Invoice Number</td>
		          	<td height="20" class="bdiv_r" align="center" width="7%">IBM/IGS</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="12%">Name</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="5%">Campaign Price</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="5%">Basic Discount</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="5%">Disc Gross Sale</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="5%">Addl Discount</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="6%">Sales with VAT</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="5%">12% VAT</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="5%">Vatable Sales</td>
		          	<td height="20" class="bdiv_r" align="center" width="5%">ADPP</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="6%">Net Sales Value</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="5%">Invoice Amount</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="5%">Invoice 38 days</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="5%">Invoice 52 days</td>
		          	<td height="20" class="padl5" align="center" width="5%">Status</td>
		      	</tr>
		      	</table>
			</td>
		</tr>
		<tr>
			<td><div class="scroll_500">
				<table width='100%' align='center' border='0' cellpadding='0' cellspacing='1'>
				<?php
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
      				$rep_tot_emp = 0;
      				
      				if($num != 0)
      				{
      					while($row = $rs_stocklog->fetch_object()) 
						{	 
							$cnt ++;
							($cnt % 2) ? $class = '' : $class = 'bgEFF0EB';
							$txndate = strtotime($row->TxnDate);
							$txndate = date("d/m/Y", $txndate);
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
							 
					         if (($tmp_txndate != $txndate && $cnt != 1))
						  	 {
						  		echo "<tr>
						  				<td colspan='17' height='20' class='borderBR'>&nbsp;</td></tr><tr align='center' class='txtdarkgreenbold10'>
							            	<td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR padr5' align='right'>Total for $tmp_txndate :</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totCampaignPrice, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totBasicDicsount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totDiscGrossSales, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAddtnlDiscount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totSaleswithvat, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totVatAmount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAmountwovat, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAddtlDiscountPrev, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totNetSalesValue, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totInvoiceAmount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($tot_inv_38, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($tot_inv_52, 2) . "</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							             </tr>
							             <tr align='center' class='txtdarkgreenbold10'>
							            	<td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR padr5' align='right'>Less CPI :</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($campaign_cpi, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>&nbsp;</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($dgross_cpi, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($iamount_cpi, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							             </tr><td colspan='17' height='20' class='borderBR'>&nbsp;</td></tr>";
							    
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
						  	     
					  		echo "<tr align='center'>
								  		<td height='20' align='center' width='7%' class='borderBR' width='7%'>$orderno</td>
				                      	<td height='20' align='center' class='borderBR' width='7%'>$txno</td>
				                      	<td height='20' align='center' class='borderBR' width='7%'>$customerCode</td>
				                      	<td height='20' align='left' class='borderBR padl5' width='12%'>$customerName</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='5%'>" . number_format($campaignPrice, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='5%'>" . number_format($basicDicsount, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='5%'>" . number_format($discGrossSales, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='5%'>" . number_format($addtnlDiscount, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='6%'>" . number_format($saleswithvat, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='5%'>" . number_format($vatAmount, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='5%'>" . number_format($amountwovat, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='5%'>" . number_format($addtlDiscountPrev, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='6%'>" . number_format($netSalesValue, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='5%'>" . number_format($invoiceAmount, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='5%'>" . number_format($inv_38, 2) . "</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='5%'>" . number_format($inv_52, 2) . "</td>
				                      	<td height='20' align='center' class='borderBR' width='5%'>".$status."</td>
						  		</tr>";
						  		
						  	$tmp_txndate = $txndate;
						  	if ($cnt == $num)
						  	{
						  		$campaign_cpi += $row->TotalCPI;   
						 		$dgross_cpi += $row->TotalCPI;
						 		$iamount_cpi += $row->TotalCPI;
						 		
						  		echo "<tr>
						  				<td colspan='17' height='20' class='borderBR'>&nbsp;</td></tr><tr align='center' class='txtdarkgreenbold10'>
							            	<td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR padr5' align='right'>Total for $tmp_txndate :</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totCampaignPrice, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totBasicDicsount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totDiscGrossSales, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAddtnlDiscount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totSaleswithvat, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totVatAmount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAmountwovat, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAddtlDiscountPrev, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totNetSalesValue, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($totInvoiceAmount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							             </tr>
							             <tr align='center' class='txtdarkgreenbold10'>
							            	<td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR padr5' align='right'>Less CPI :</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($campaign_cpi, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>&nbsp;</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($dgross_cpi, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($iamount_cpi, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR padr5' align='right'>0.00</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							             </tr><td colspan='17' height='20' class='borderBR'>&nbsp;</td></tr>";						  		
						  	}  
				  		}
				  		
				  		//report total
				  		echo "<tr>
				  				<td colspan='17' height='20' class='borderBR'>&nbsp;</td></tr><tr align='center' class='txtdarkgreenbold10'>
					            	<td height='20' class='borderBR' align='center'>&nbsp;</td>
					                <td height='20' class='borderBR' align='center'>&nbsp;</td>
					                <td height='20' class='borderBR' align='center'>&nbsp;</td>
					                <td height='20' class='borderBR padr5' align='right'>Report Total :</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_totCampaignPrice, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_totBasicDicsount, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_totDiscGrossSales, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_totAddtnlDiscount, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_totSaleswithvat, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_totVatAmount, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_totAmountwovat, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_totAddtlDiscountPrev, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_totNetSalesValue, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_totInvoiceAmount, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_tot_inv_38, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_tot_inv_52, 2) . "</td>
					                <td height='20' class='borderBR' align='center'>&nbsp;</td>
					             </tr>
					             <tr align='center' class='txtdarkgreenbold10'>
					            	<td height='20' class='borderBR' align='center'>&nbsp;</td>
					                <td height='20' class='borderBR' align='center'>&nbsp;</td>
					                <td height='20' class='borderBR' align='center'>&nbsp;</td>
					                <td height='20' class='borderBR padr5' align='right'>Less CPI :</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_campaign_cpi, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>&nbsp;</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_dgross_cpi, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>0.00</td>
					                <td height='20' class='borderBR padr5' align='right'>0.00</td>
					                <td height='20' class='borderBR padr5' align='right'>0.00</td>
					                <td height='20' class='borderBR padr5' align='right'>0.00</td>
					                <td height='20' class='borderBR padr5' align='right'>0.00</td>
					                <td height='20' class='borderBR padr5' align='right'>0.00</td>
					                <td height='20' class='borderBR padr5' align='right'>" . number_format($rep_iamount_cpi, 2) . "</td>
					                <td height='20' class='borderBR padr5' align='right'>0.00</td>
					                <td height='20' class='borderBR padr5' align='right'>0.00</td>
					                <td height='20' class='borderBR' align='center'>&nbsp;</td>
					             </tr><td colspan='17' height='20' class='borderBR'>&nbsp;</td></tr>";
					             
			             //employee total
			             if($rs_emp_total->num_rows != 0)
			             {
			             	while($row = $rs_emp_total->fetch_object()) 
			             	{
			             		echo "<tr align='center' class='txtdarkgreenbold10'>
							            	<td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							                <td height='20' class='borderBR padr5' align='right'>Total for $row->Employee :</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($row->CampaignPrice, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($row->BasicDiscount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($row->DiscGrossSales, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($row->AddtlDiscount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($row->SalesWithVat, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($row->AmountWOVat, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($row->VatAmount, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($row->AddtlDiscountPrev, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format(($row->NetSalesValue - $row->VatAmount), 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>" . number_format($row->NetSalesValue, 2) . "</td>
							                <td height='20' class='borderBR padr5' align='right'>&nbsp;</td>
							                <td height='20' class='borderBR padr5' align='right'>&nbsp;</td>
							                <td height='20' class='borderBR' align='center'>&nbsp;</td>
							             </tr>";
			             	}
			             }
      				}
      				else
      				{
      					echo "<tr align='center'><td height='20' colspan='17' align='center' class='borderBR txtredsbold'>No record(s) to display.</td></tr>";      					
      				}	
				?>
				</table>
			</div></td>
		</tr>
		</table>
		<br>
		<table width="95%"  border="0" align="center">
		<tr>
			<td height="20" align="center">
				<a class="txtnavgreenlink" href="index.php?pageid=18"><input name="Button" type="button" class="btn" value="Back"></a>
				<input name="input" type="button" class="btn" value="Print" onclick="openPopUp('<?php echo $fromdate;?>','<?php echo $todate;?>', '<?php echo $vSearch;?>', '<?php echo $employee;?>')"/>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
</form>
<br>

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtEndDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divEndDate",
		button         :    "anchorEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>