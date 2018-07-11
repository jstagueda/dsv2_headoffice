<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxCreateSI.js"  type="text/javascript"></script>
<style type="text/css">
<!--
.style1 {font-weight: bold; color: #FF0000}
.style2 {color: #FF0000}
-->
</style>
<?PHP 
	include IN_PATH.DS."scCreateSI.php";
	global $database;
?>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<form name="frmCreateSI" method="post" action="includes/pcCreateSI.php"  >
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
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
    <td class="txtgreenbold13">Create Sales Invoice </td>
    <td>&nbsp;</td>
  </tr>
</table>
<br /> 
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">      
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td class="txtredbold">General Information </td>
              	<td>&nbsp;</td>
            </tr>
          </table></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>      
	  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
        <tr>
          <td valign="top" class="bgF9F8F7"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
              <td colspan="2">
				  <?php 
					  if (isset($_GET['msg']))
					  {
						  $message = strtolower($_GET['msg']);
						  $success = strpos("$message","success"); 
						  echo "<div align='left' style='padding:5px 0 0 5px;' class='txtreds'>".$_GET['msg']."</div>";
					  } 
                  ?> 
              </td>
            </tr>
            <tr>
              <td width="50%" valign="top"> 
             
			  <table width="98%"  border="0" cellspacing="1" cellpadding="0">
                  <tr>
                    <td height="20" class="txt10" width="500">Reference No.</td>
                    <td width="500"><input name="txtRefNo" type="text" class="txtfield" value="<?php echo $txnno; ?>" size="30" readonly="yes"></td>
                  </tr><input name="hTxnID" type="hidden" value="<?php echo $id; ?>">
				  <tr>
                    <td height="20" class="txt10">DR Date</td>
                    <td><input name="txtDRDate" type="text" class="txtfield" value="<?php echo $txndate; $txndate = date("Y-m-d") ?>" size="30" readonly="yes"></td>
                  </tr>
				  <tr>
                    <td height="20" class="txt10">Customer Code</td>
                    <td><input name="hCustomerID" type="hidden" value="<?php echo $customerid; ?>">
                    <input name="txtCustomerCode" type="text" class="txtfield" value="<?php echo $code; ?>" size="30" readonly="yes"></td>
                  </tr>
                  <tr>
                    <td height="20" class="txt10">Customer Name</td>
                    <td><input name="txtCustomerName" type="text" class="txtfield" value="<?php echo $name; ?>" size="30" readonly="yes"></td>
                  </tr>
                  <!--<tr>
                     <td height="20" class="txt10">Credit Limit</td>
                    <td><input name="txtCreditLimit" type="text" class="txtfield" value="" size="30" readonly="yes"></td>
                  </tr>
                  <tr>
                    <td height="20" class="txt10">Credit Status</td>
                    <td><input name="txtCreditStatus" type="text" class="txtfield" value="" size="30" readonly="yes"></td>
                  </tr>-->
                  <tr>
                    <td height="20" class="txt10">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td height="20" class="txt10">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
              </table>
			  </td><td valign="top">
			  <table width="98%"  border="0" cellspacing="1" cellpadding="0">
			  	<tr>
                    <td height="20" class="txt10">Document No.</td>
                    <td><input name="txtDocumentNo" type="text" class="txtfield" value="" size="30"></td>
                </tr>
                <tr>
	              <td width="500" height="20" class="txt10">SI Date</td>
	              <td width="500"><input name="txtTxnDate" type="text" class="txtfield" id="txtTxnDate" size="20" readonly="yes" value="<?php echo $datetoday; ?>">
	                <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'txtTxnDate', 'mm/dd/yyyy')" title="Show popup calendar"><img src="images/btn_Calendar.gif" width="25" height="19" border="0" align="absmiddle" /></a></td>
	            </tr>
	            <tr>
	              <td width="500" height="20" class="txt10">Effectivity Date</td>
	              <td width="500"><input name="txtEffectivityDate" type="text" class="txtfield" id="txtEffectivityDate" size="20" readonly="yes" value="<?php echo $datetoday; ?>">
	                <a href="javascript:void(0);" onclick="g_Calendar.show(event, 'txtEffectivityDate', 'mm/dd/yyyy')" title="Show popup calendar"><img src="images/btn_Calendar.gif" width="25" height="19" border="0" align="absmiddle" /></a></td>
	            </tr>
	            <tr>
                  	<td width="500" height="20" class="txt10">Payment Terms</td>
                  	<td width="500" height="20" class="txt10"><select name="cboPaymentTerms" id="select" class="txtfield">
                  	<?PHP
                  		echo "<option value=\"0\" >[SELECT HERE]</option>";
							$rs_terms = $sp->spSelectTerms($database);	
                  			if ($rs_terms->num_rows)
                  			{
                  				while ($row = $rs_terms->fetch_object())
                  				{                  					
                  					if($termsid == $row->ID )
									{
										$x = "selected";
									}
									else
									{
										$x="";	
									}
									echo "<option value='$row->ID' $x>$row->Name</option>";	
                  				}
                  				$rs_terms->close();
                  			}
                  			?>
                  	</td>
				</tr>  
	            <tr>
                  	<td width="500" height="20" class="txt10">Salesman</td>
                  	<td width="500" height="20" class="txt10"><select name="cboSalesman" id="select" class="txtfield">
                  	<?PHP
                  		echo "<option value=\"0\" >[SELECT HERE]</option>";
							$rs_salesman = $sp->spSelectSalesman($database);	
                  			if ($rs_salesman->num_rows)
                  			{
                  				while ($row = $rs_salesman->fetch_object())
                  				{                  					
                  					if($salesmanid == $row->ID )
									{
										$y = "selected";
									}
									else
									{
										$y="";	
									}
									echo "<option value='$row->ID' $y>$row->Name</option>";	
                  				}
                  				$rs_salesman->close();
                  			}
                  			?>
                  	</td>
				</tr>   
				<tr>
                  <td height="20" valign="top" class="txt10">Remarks</td>
                  <td><textarea name="txtRemarks" cols="30" rows="5" class="txtfieldnh"></textarea></td>
                </tr>
              </table>
			 </td>
            </tr>
            <tr>
              <td colspan="2">&nbsp;</td>
            </tr>
          </table></td>
        </tr>
  </table>      
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="3" class="bgE6E8D9"></td>
                </tr>
              </table>

<br />
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">Delivered Products</td>
               	<td>&nbsp;</td>
              </tr>
          </table></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>      
      <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tb13">
      	<tr>
      		<td valign="top" class="tab" width="1020">
      			<table width="100%" border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
      				<tr align="center">
      					<td width="5%" height="20" class="bdiv_r">Line No</td>
      					<td width="10%" class="bdiv_r">Product Code</td>
      					<td width="25%" class="bdiv_r">Product Name</td>
      					<td width="10%" class="bdiv_r">UOM</td>
      					<td width="10%" class="bdiv_r">CSP</td>
      					<td width="10%" class="bdiv_r">Allocated Qty</td>
      					<td width="10%" class="bdiv_r">Committed Qty</td>
      					<td width="10%" class="bdiv_r">Discount</td>
      					<td width="10%">Net Amount</td>
      				</tr>
      			</table>
      		</td>
      	</tr>  
      	<tr>
      		<td valign="top" class="bgF9F8F7">
      		<div class="scroll_250">
      			<table width="100%" border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
      				<?PHP
      					$ctr = 0;
      					$rowalt = 0;
      					$TotOrdQty = 0;
      					$TotDelQty = 0;
      					$TotTNP = 0;
      					$gross = 0;
      					
      					$totgross = 0;
      					$vat = $_SESSION['vatpercent'];
      					$vat_percent = $vat / 100;
      					
      					if ($rs_drproddetails->num_rows)
      					{
      						while ($row = $rs_drproddetails->fetch_object())
      						{ 
      							$ctr += 1;
      							  
      							if ($row->IsPercentDiscount == 1)
      							{
      								
	      							$unit = $row->UnitPrice / (1+($vat/100));
	      							//echo  	$unit . '<br>';
	      							$unit = $unit * $row->DelQty;
	      							$unit = $unit * (100-$row->discount)/100;
	      							//$unit = str_replace($unit,",","");    
	      															      							
	      							$unit = $unit * (1+($vat/100));
	      							$TotalNetPrice = $unit;
      							}
      							else
      							{
      								$unit = $row->UnitPrice / (1+($vat/100));
      							
      								$unit = $unit * $row->DelQty;
      								$unit = $unit - $row->discount;
      								//$unit = str_replace($unit,",","");
      								
      								$unit = $unit * (1+($vat/100));
      								$TotalNetPrice = $unit;
      							}
      							
      							$TotOrdQty = $TotOrdQty + $row->OrdQty;
      							$TotDelQty = $TotDelQty + $row->DelQty;
      							$TotTNP = $TotTNP + $TotalNetPrice;
      							      							   										
								$rowalt += 1;      
								($rowalt % 2) ? $alt = "" : $alt = "bgEFF0EB";
								echo"<tr align='center' >";
					?>
						<input name="hVat" type="hidden" id="hVat" value="<?php echo $vat; ?>">
						<input name="hVatPercent" type="hidden" id="hVat" value="<?php echo $vat_percent; ?>">												
						<input name="hIndex" type="hidden" id="hIndex" value="<?php echo $ctr; ?>">
						<input name="hProductID#ctr#" type="hidden" id="hProductID#ctr#" value="<?php echo $ProductID; ?>">
						<input name="hUOMID#ctr#" type="hidden" id="hUOMID#ctr#" value="<?php echo $UOMID; ?>">
						<input name="hProd#ctr#" type="hidden" id="hProd#ctr#" value="<?php echo $Product; ?>">
						<input name="hUnitPrice#ctr#" type="hidden" id="hUnitPrice#ctr#" value="<?php echo $UnitPrice; ?>">
						
						<td width="5%" height="20" class="borderBR"><?php echo $row->LineNo; ?></td>
	      				<td width="10%" height="20" class="borderBR"><?php echo $row->ProductCode; ?></td>
	      				<td width="25%" height="20" class="borderBR"><?php echo $row->Product; ?></td>
	      				<td width="10%" height="20" class="borderBR"><?php echo $row->UOM; ?></td>
	      				<td width="10%" height="20" class="borderBR"><?php echo $row->UnitPrice; ?></td>
	      				<td width="10%" height="20" class="borderBR"><?php echo number_format($row->OrdQty,0); ?></td>
	      				<td width="10%" height="20" class="borderBR"><?php echo number_format($row->DelQty,0); ?></td>
	      				<td width="10%" height="20" class="borderBR">
		      				<?php 
							  	if ($row->IsPercentDiscount == 1)
							  	{
							  		echo $row->discount."%";			  		
							  	}
							  	else
							  	{
							  		echo $row->discount;			  		
							  	}
						  	?>
					  	</td>
	      				<td width="10%" height="20" class="borderBR"><?php echo $row->TotalAmount; ?></td>
      				</tr>
      				
      				<?php	
      						}
      						$rs_drproddetails->close();       										
      					}
      					else
      					{
      						echo "<tr align='center'>
							  	  	<td width='100%' height='20' class='borderBR' colspan=10>There are no products for this Sales Invoice. </span></td>
								  </tr>";
      					}	     				
      				?>
      			</table>
      		</div>
      		<input name="txtTotOrdQty" type="hidden" value="<?php echo $TotOrdQty; ?>">
      		<input name="txtTotDelQty" type="hidden" value="<?php echo $TotDelQty; ?>">
      		
      		<table width="100%" border="0" cellpadding="0" cellspacing="1">
      			<tr class="bgE6E8D9">
      				<td width="85%" height="20" align="right" class="txtbold">Gross Amount : &nbsp;</td>
      				<td width="15%" height="20" align="right" class="txtbold">      
      					<input name="hGrossAmt" type="hidden" value="<?php echo number_format($TotTNP,2); ?>">					
      					<input name="txtTotAmt" type="text" value="<?php echo number_format($TotTNP,2); ?>" class="txtfield" style="text-align:right" readonly="yes">&nbsp;</td>
      			</tr>
      			<?php
      			$grosswotax = $TotTNP / (1 + ($vat/100));      			
      			$disc1 = $grosswotax - ($grosswotax * ($tmpdisc1/100));
      			$disc2 = $disc1 - ($disc1 * ($tmpdisc2/100));      			
      			$totnetamt = $disc2 - ($disc2 * ($tmpdisc3/100));      			
      			$amtwovat = $totnetamt;
      			$vat = 0;
      			?>
      			<input name="txtgrosswotax" type="hidden" value="<?php echo number_format($grosswotax,99.9); ?>">
      			
      			<tr class="bgE6E8D9">
      				<td height="20" align="right" class="txtbold">Basic Discount : &nbsp;</td>
      				<td height="20" align="right" class="txtbold"><input name="txtDisc1" type="text" value="<?php echo number_format($tmpdisc1,2); ?>" class="txtfield" style="text-align:right" readonly="yes">&nbsp;</td>
      			</tr>
      			<tr class="bgE6E8D9">
      				<td height="20" align="right" class="txtbold">Additional Discount : &nbsp;</td>
      				<td height="20" align="right" class="txtbold">
      					<input name="txtDisc2" type="text" value="<?php echo number_format($tmpdisc2,2); ?>" class="txtfield" style="text-align:right" readonly="yes">&nbsp;
   					</td>
      			</tr>
      			<input name="txtWTax" id="txtWTax" type="hidden" class="txtfield" value="0" size="20" maxlength="9" style="text-align:right; ">
		        <?php
		        $vatamt = $amtwovat * ($_SESSION['vatpercent']/100);
		        $vatmt = str_replace($vatamt,",","");		
		        $vatamt = number_format($vatamt,2);        
		        ?>
		        <tr class="bgE6E8D9">
		          <td height="20" align="right" class="txtbold">Sales With Vat :&nbsp;</td>
		          <td height="20" align="right"><input name="txtVATAmt" id="txtVAmt" type="text"  readonly="yes" class="txtfield" <?php echo number_format($grosswotax,99.9); ?> size="20" maxlength="9" style="text-align:right; ">&nbsp;</td>
		        </tr>	        
    			<tr class="bgE6E8D9">
		          <td height="20" align="right" class="txtbold">Vat Amount :&nbsp;</td>
		          <td height="20" align="right"><input name="txtVATAmt" id="txtVAmt" type="text"  readonly="yes" class="txtfield" onKeyUp="return TotalAmount();" value="<?php echo $vatamt; ?>" size="20" maxlength="9" style="text-align:right; ">&nbsp;</td>
		        </tr>
		        <tr class="bgE6E8D9">
      				<td width="85%" height="20" align="right" class="txtbold">Amount w/o Vat: &nbsp;</td>
      				<td width="15%" height="20" align="right" class="txtbold"><input name="txtGrossAmt" type="text" value="<?php echo number_format($amtwovat,2); ?>" class="txtfield" style="text-align:right" readonly="yes">&nbsp;</td>
      			</tr>
      			<tr class="bgE6E8D9">
      				<td height="20" align="right" class="txtbold">Additional Discount on Previous Purchase : &nbsp;</td>
      				<td height="20" align="right" class="txtbold">
      					<input name="txtDisc3" type="text" value="<?php echo number_format($tmpdisc3,2); ?>" class="txtfield" style="text-align:right" readonly="yes">&nbsp;
   					</td>
      			</tr>
      			<?php
		        	//$vatamt = str_replace($vatamt,",","");		
		        	
		        	$netamt = $totnetamt + $vatamt;
		        	//$netamt = str_replace($netamt,",","");
		        	
		        	$netamt = number_format($netamt,2);
		        ?>
		        <tr class="bgE6E8D9">
		          <td height="20" align="right" class="txtbold">Total Invoice Amount Due :&nbsp;</td>
		          <td height="20" align="right"><input type="hidden" name="hNetAmt" value="<?php echo $netamt; ?>"><input name="txtNetAmt" type="text"  class="txtfield txtbold" readonly="yes" onKeyUp="return TotalAmount();" value="<?php echo $netamt; ?>" size="20" style="text-align:right;">&nbsp;</td>
		        </tr>	
		        <tr class="bgE6E8D9">
		          <td height="20" align="right" class="txtbold">Total CFT :&nbsp;</td>
		          <td height="20" align="right">0.00&nbsp;&nbsp;&nbsp;&nbsp;</td>
		        </tr>
		        <tr class="bgE6E8D9">
		          <td height="20" align="right" class="txtbold">Total NCFT :&nbsp;</td>
		          <td height="20" align="right">0.00&nbsp;&nbsp;&nbsp;&nbsp;</td>
		        </tr>		
		        <tr class="bgE6E8D9">
		          <td height="20" align="right" class="txtbold">MTD CFT :&nbsp;</td>
		          <td height="20" align="right">0.00&nbsp;&nbsp;&nbsp;&nbsp;</td>
		        </tr>
		        <tr class="bgE6E8D9">
		          <td height="20" align="right" class="txtbold">MTD NCFT :&nbsp;</td>
		          <td height="20" align="right">0.00&nbsp;&nbsp;&nbsp;&nbsp;</td>
		        </tr>		
		        <tr class="bgE6E8D9">
		          <td height="20" align="right" class="txtbold">Previous MTD CFT :&nbsp;</td>
		          <td height="20" align="right">0.00&nbsp;&nbsp;&nbsp;&nbsp;</td>
		        </tr>
		        <tr class="bgE6E8D9">
		          <td height="20" align="right" class="txtbold">Previous MTD NCFT :&nbsp;</td>
		          <td height="20" align="right">0.00&nbsp;&nbsp;&nbsp;&nbsp;</td>
		        </tr>		
      		</table>
      		</td>      		
      	</tr>    
      </table>
   	  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  		<tr>
			<td height="3" class="bgE6E8D9"></td>
  		</tr>
	  </table>

     <!-- <br>
	  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
	  	<tr>
    		<td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
    			<tr>
		          <td width="85%" height="20" align="right" class="txtbold">SI DISCOUNT :&nbsp;</td>
		          <td width="15%" height="20" align="right"><input name="txtSIDisc" id="txtSIDisc" type="text" class="txtfield" onKeyUp="return TotalAmount();" value="0.00" size="20" maxlength="9" style="text-align:right; "></td>
		        </tr>
    			<tr>
		          <td height="20" align="right" class="txtbold">OTHER CHARGES :&nbsp;</td>
		          <td height="20" align="right"><input name="txtOCharges" id="txtOCharges" type="text"  class="txtfield" onKeyUp="return TotalAmount();" value="0.00" size="20" maxlength="9" style="text-align:right; "></td>
		        </tr>
    		</table></td>
		</tr>
	  </table>-->
	<br>
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  <tr>
	    <td align="center">
			  <input name="btnSave" id="btnSave" type="submit" class="btn" value="Save" onClick="return checkSave();">
			  <input name="btnCancel" id="btnCancel" type="submit" class="btn" value="Cancel" onclick="return cancelTxn();">
	  	</td>
	  </tr>
	</table>
	<br>
  	</td>
  </tr>
</table>
</form>