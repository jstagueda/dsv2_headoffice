<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxRecordProductExchange.js"  type="text/javascript"></script>
<script src="js/shortcut.js" type="text/javascript"></script>
<script type = "text/javascript">
jQuery(function() {
	// if user clicked on button, the overlay layer or the dialogbox, close the dialog	
	jQuery('a.btn-ok, #dialog-overlay, #Close').click(function () {		
		jQuery('#dialog-overlay, #dialog-box').fadeOut();		
		return false;
	});
	// if user resize the window, call the same function again
	// to make sure the overlay fills the screen and dialogbox aligned to center	
	jQuery(window).resize(function () {
		//only do it if the dialog box is not hidden
		if (!jQuery('#dialog-box').is(':hidden')) popup();		
	});	
	
});

function buttonValidation()
{
	//alert(jQuery('#hcnt').val());
		if(jQuery('#hcnt').val()){
			var cnt = jQuery('#hcnt').val()
				for(var  i = 1 ; i <= cnt ; i ++){

							var txtExchangeProdCode = jQuery("#txtExchangeProdCode" + i).val();
							var error = 0;
							
							if (txtExchangeProdCode == ""){
								//alert('Please Input Qty');
								error++;
							}
				}
				
				
				if(error == 0){
							 jQuery("#frmRecordProdExchange").submit();
				}else{
					alert('Please Input Exchange Product Code');
					return false;
				}
				
				
		}
}
function cancel_btn()
{
	var r=confirm("Are you sure want to cancel?")
		if (r==true){
			//alert("You pressed OK!")
			window.location.href = "index.php?pageid=18";
			return false;
		}else{
			return false;
		}

}
</script>
</script>
<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
</style>

<style type="text/css">
<!--
.style1 {color: #FF0000}

div.autocomplete {
  position:absolute;
  /*width:300px;*/
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px;} 
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;  
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px solid #888;
  padding:2px;
  /*height:20px;*/
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
  cursor:pointer;
}

-->
</style>



<?PHP 
   include IN_PATH.DS."scViewProductExchangeDetails.php";
?>

<body> 	
<form name="frmRecordProdExchange" id="frmRecordProdExchange"  method="post" action="index.php?pageid=159">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory</a></td>
          </tr>
      </table>
      </td>
   </tr>
</table>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
			<td class="txtgreenbold13">View Product Exchange</td>
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

<table width="95%" border="0" align="center" cellpadding="0"	cellspacing="1" class="bordergreen" id="tbl1">
	<tr>
		<td valign="top" class="bgF9F8F7">
		<table width="98%" border="0" align="center" cellpadding="0"	cellspacing="1">
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td width="50%" valign="top">
				<table width="98%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td height="25" class="txt10" align="right">Customer Code :</td>
						<td width="60%" height="20">
							<input name="txtCustomer" type="text" class="txtfieldLabel" id="txtCustomer" value="<?php echo $custCode; ?>" readonly="readonly"  />
							
							<input name="hCustomerID" type="hidden" id="hCustomerID" value="<?php echo $custID; ?>"  />
		  				</td>
					</tr>	
					<tr>
						<td height="25" class="txt10" align="right">Customer Name :</td>
						<td height="25" class="txt10" align="left"><input type="text" class="txtfieldLabel" name="txtCustomerName" id="txtCustomerName" size="35" maxlength="15" value="<?php echo $custName; ?>" readonly="readonly"  ></td>
					</tr>	
					<tr>
						<td height="25" class="txt10" align="right">IBM Code / IBM Name :</td>
						<td height="25" class="txt10" align="left"><input type="text" class="txtfieldLabel" name="txtIBMName" id="txtIBMName" size="50" maxlength="15"  value="<?php echo $ibm; ?>"  readonly="readonly"  ></td>
					</tr>
					<tr>
						<td height="25" class="txt10"  align="right" >SI No. : </td>
						<td height="25" width="60%"> 
							<input name="txtSINo" type="text" class="txtfieldLabel" id="txtSINo"  align="left"   value="<?php echo $sino; ?>" readonly="readonly" />
								
							<input name="hSIID" type="hidden" id="hSIID"  value="<?php echo $siid; ?>"/>
						</td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Sales Invoice Date : </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtSIDate" id="txtSIDate" size="50" maxlength="15"  value="<?php echo $invoiceDate; ?>"  readonly="readonly"  ></td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Reference SO No. : </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtRefNo" id="txtRefNo" size="50" maxlength="15" value="<?php echo $refno; ?>" readonly="readonly"  ></td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Remarks: </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtRemarks" id="txtRemarks" size="50" maxlength="15" value="<?php echo $remarks; ?>" readonly="readonly" ></td>
					</tr>					
				</table>
				</td>
				<td valign="top">
				<table width="98%" border="0" cellspacing="1" cellpadding="0">
					<tr>
						<td height="25" class="txt10"  align="right" >Document No. : </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtDocNo" id="txtDocNo" size="50" maxlength="15"  value="<?php echo $docno; ?>" readonly="readonly"  ></td>
					</tr>
					<tr>
						<td height="25" class="txt10"  align="right" >Branch : </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtBranch" id="txtBranch" size="50" maxlength="15" value="<?php echo $branch; ?>" readonly="readonly"  ></td>
					</tr>
					<tr>
						<td height="25" class="txt10"  align="right" >Created By : </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtCreatedBy" id="txtCreatedBy" size="50" maxlength="15" value="<?php echo $createdby; ?>" readonly="readonly"  ></td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Status: </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtStatus" id="txtStatus" size="50" maxlength="15" value="<?php echo $status; ?>"  readonly="readonly" ></td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Confirmed By: </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtConfimedBy" id="txtConfimedBy" size="50" maxlength="15" value="<?php echo $confirmedby; ?>"  readonly="readonly" ></td>
					</tr>
					<tr>
						<td height="25" class="txt10"  align="right" >Date Confirmed: </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtDateConfirmed" id="txtDateConfirmed"size="50" maxlength="15" value="<?php echo $dateconfirmed; ?>"  readonly="readonly" ></td>
					</tr>
					
				</table> 
				</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
<br />
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<!--Products for exchange-->
		<td valign="top" width="100%">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			        <tr>
			          <td class="tabmin">&nbsp;</td>
			          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			              <tr>
			                <td class="txtredbold">Product Echange Details</td>
			              </tr>
			          </table></td>
			          <td class="tabmin3">&nbsp;</td>
			        </tr>
			  </table>  
			  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
			  <tr>
			  	<td class="tab">
			  		<table width="100%"  border="0" cellpadding="0" cellspacing="1" >
			  		<tr align="center" class="txtdarkgreenbold10">
			  			<td width="5%" height="20" class="bdiv_r">Line No.</td>
			  			<td width="10%" height="20" class="bdiv_r">Product Code</td>   
			  			<td width="16%" height="20" class="bdiv_r">Product Name</td>
			  			<td width="5%" height="20" class="bdiv_r">UOM</td>
			  			<td width="5%" height="20" class="bdiv_r">PMG</td>			  				
			  			<td width="5%" height="20" class="bdiv_r">Qty</td>		  			 			
			  			<td width="5%" height="20" class="bdiv_r">Price</td>
			  			<td width="8%" height="20" class="bdiv_r">Net Amount</td>			  			
			  			<td width="8%" height="20" class="bdiv_r">Reason</td>
			  			<td width="12%" height="20" class="bdiv_r"> Exchange Product Code</td>   
			  			<td width="16%" height="20" class="bdiv_r">Exchange Product Name</td>
					</tr>
				
			       
			        
			         <?php 
			         	if($rsDetails->num_rows)
			         	{
							echo '<input type = "hidden" value ='.$_GET["TxnInvoice"].' name = "TxnInvoice" / >';
							echo '<input type = "hidden" value ='.$_GET["TxnID"].' name = "TxnID" / >';
			         		$i = 1;
			         		echo '<input type="hidden" name="hcnt" id="hcnt" value="'.$rsDetails->num_rows.'">' ;
         	         		while($row = $rsDetails->fetch_object())
			         		{
								$statusID = $row->StatusID;
								if($statusID == 7){
								echo 
			         				'
										<tr align="center">			         					
											<td width="5%" height="20" class="borderBR padl5">'.$i.'</td>
											<td width="10%" height="20" class="borderBR padl5">'.$row->prodCode.'</td>   
											<td width="16%" height="20" class="borderBR padl5">'.$row->prodName.'</td>
											<td width="5%" height="20" class="borderBR padl5">'.$row->uom.'</td>
											<td width="5%" height="20" class="borderBR padl5">'.$row->pmg.'</td>			  				
											<td width="5%" height="20" class="borderBR padl5">'.$row->qty.'</td>		  			 			
											<td width="5%" height="20" class="borderBR padl5">'.number_format($row->UnitPrice,2).'</td>
											<td width="8%" height="20" class="borderBR padl5">'.number_format($row->TotalAmount,2).'</td>			  			
											<td width="8%" height="20" class="borderBR padl5" >'.$row->reason.'</td>
											<td width="12%" height="20" class="borderBR padl5">'.$row->ExchangeCode.'</td>   
											<td width="16%" height="20" class="borderBR padl5">'.$row->ExchangeName.'</td>
										</tr>
									';
								}else{
								echo 
										'
										<input type = "hidden" value ='.$row->ProductID.' name = "ProductID'.$i.'" >
										<input type = "hidden" value ='.$row->qty.' name = "qty'.$i.'" >
									
										<tr align="CENTER" class = "bgF9F8F7">			         					
											<td width="5%" height="20" class="borderBR padl5">'.$i.'</td>
											<td width="10%" height="20" class="borderBR padl5">'.$row->prodCode.' <input type = "hidden" value = "'.$row->prodID.'" id = "prodID'.$i.'"></td>   
											<td width="16%" height="20" class="borderBR padl5">'.$row->prodName.'</td>
											<td width="5%" height="20" class="borderBR padl5">'.$row->uom.'</td>
											<td width="5%" height="20" class="borderBR padl5">'.$row->pmg.'</td>			  				
											<td width="5%" height="20" class="borderBR padl5">'.$row->qty.'</td>		  			 			
											<td width="5%" height="20" class="borderBR padl5">'.number_format($row->UnitPrice,2).'</td>
											<td width="8%" height="20" class="borderBR padl5">'.number_format($row->TotalAmount,2).'</td>			  			
											<td width="8%" height="20" class="borderBR padl5" >'.$row->reason.'</td>
											<td width="12%" height="20" class="borderBR padl5">
												<input name="txtExchangeProdCode'.$i.'" type="text" class="txtfield" id="txtExchangeProdCode'.$i.'" value ="'.$row->ExchangeCode.'" readonly onclick = "return popup('.$i.')" />
												<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
												<div id="coa_choices" class="autocomplete" style="display:none"></div>
												<script type="text/javascript">							
													//<![CDATA[
															var coa_choices = new Ajax.Autocompleter(\'txtExchangeProdCode'.$i.'\', \'coa_choices\', \'includes/jxProductExchangeAjax.php?index='.$i.'&ProductLevelID='.$row->ProductLevelID.'&prodLine='.$row->prodLineID.'&ParentID='.$row->ParentID.'&productID='.$row->prodID.'\', {afterUpdateElement : getProductExchange, indicator: \'indicator1\'});																			
													//]]>
												</script>
												<input name="hProdExchangeID'.$i.'" type="hidden"  value ="'.$row->exchangeproductID.'" id="hProdExchangeID'.$i.'" />
											</td>   
											<td width="17%" height="20" class="borderBR padl5">
												<input name="txtExchangeProdName'.$i.'" type="text" value = "'.$row->ExchangeName.'" class="txtfieldLabel" readonly id="txtExchangeProdName'.$i.'" size="37" maxlength="20"  />
											</td>
										</tr>
									';
								}
			         			$i++ ;
			         		}
			         	}
			         ?>
			
			         </table>
			        </td>
			        </tr>			    
			</table>
		</td>		
	</tr>
</table>
	
   
         


<br />
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td height="20"><div align="center">				
         <?php if ($statusID == 7):?>
			<input name="btnPrint" type="submit" class="btn" value="Print" onclick="return openPopUp(<?php echo $txnID?>);"  />
			&nbsp;
		 <?php else: ?>
			<input type = "hidden" value = "<?php echo $_GET['TxnID']; ?>" name = 'TxnID' />
		    <input name="btnConfirmSave" type="submit" class="btn" value="Confirm"  onclick = "return buttonValidation();" />
		   &nbsp;
		 <?php endif; ?>
           <input name="btnCancel" type="submit" class="btn" value="Cancel" />
         
  	</div></td>
</tr>
</table>
<br>
<br>
</form>
<?php include "pages/inventory/inventory_call_ajax/DialogProductExchange.php"?>
</body>