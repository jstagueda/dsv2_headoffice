<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxRecordProductExchange.js"  type="text/javascript"></script>
<script src="js/shortcut.js" type="text/javascript"></script>
<script type="text/javascript">
/*
Author: @Gino C. Leabres
*/
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

function buttonValidation(){
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
					alert('*Required Exchange Product Code');
					return false;
				}
		}
}
function cancel_btn(){
	var r = confirm("Are you sure want to cancel?")
		if (r==true){
			window.location.href = "index.php?pageid=1";
			//alert("You pressed OK!");
			return false;
		}else{
			return false;
		}

}

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
   include IN_PATH.DS."scRecordProductExchange.php";
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
            <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1"> Inventory Cycle Main</a></td>
          </tr>
      </table>
      </td>
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
			                <td class="txtredbold">Sales Invoice Details</td>
			              </tr>
			          </table></td>
			          <td class="tabmin3">&nbsp;</td>
			        </tr>
			  </table>  
			  <table width="100%" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
			  <?php //<tr>
			  	//<td class="tab">?>
			  		<!--table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10"-->
			  		<tr align="center" class = 'tab'>
			  			<th width="4%" height="20" class="borderBR padl5" >Line No.&nbsp;</th>
			  			<th width="10%" height="20" class="borderBR padl5">Product Code</th>   
			  			<th width="16%" height="20" class="borderBR padl5">Product Name</th>
			  			<th width="5%" height="20" class="borderBR padl5">UOM</th>
			  			<th width="5%" height="20" class="borderBR padl5">PMG</th>			  				
			  			<th width="5%" height="20" class="borderBR padl5">Qty</th>		  			 			
			  			<th width="5%" height="20" class="borderBR padl5">Price</th>
			  			<th width="8%" height="20" class="borderBR padl5">Net Amount</th>			  			
			  			<th width="8%" height="20" class="borderBR padl5">Reason</th>
			  			<th width="12%" height="20" class="borderBR padl5"> Exchange Product Code</th>   
			  			<th width="16%" height="20" class="borderBR padl5">Exchange Product Name</th>
			  			<!--th width="5%" height="20" class="borderBR padl5">Qty</th-->
					</tr>
				<?php
					/**********************************
					**  Modified by: Gino C. Leabres***
					**  10.09.2012*********************
					**  ginophp@yahoo.com**************
					***********************************/
				?>
					 <?php 
			         	if($rsDetails->num_rows)
			         	{
			         		$i = 1;
			         		echo '<input type="hidden" name="hcnt" id="hcnt" value="'.$rsDetails->num_rows.'">' ;
         	         		while($row = $rsDetails->fetch_object())
			         		{	
			         			echo 
			         				'
									
			         				<tr align="CENTER" class = "bgF9F8F7">			         					
							  			<td width="5%" height="20" class="borderBR padl5">'.$i.'</td>
							  			<td width="10%" height="20" class="borderBR padl5">'.$row->prodCode.'<input type = "hidden" value = "'.$row->prodID.'" id = "prodID'.$i.'"></td>   
							  			<td width="16%" height="20" class="borderBR padl5">'.$row->prodName.'</td>
							  			<td width="5%" height="20" class="borderBR padl5">'.$row->uom.'</td>
							  			<td width="5%" height="20" class="borderBR padl5">'.$row->pmg.'</td>			  				
							  			<td width="5%" height="20" class="borderBR padl5">'.$row->qty.'</td>		  			 			
							  			<td width="5%" height="20" class="borderBR padl5">'.number_format($row->UnitPrice,2).'</td>
							  			<td width="8%" height="20" class="borderBR padl5">'.number_format($row->TotalAmount,2).'</td>			  			
							  			<td width="8%" height="20" class="borderBR padl5" >'.$row->reason.'</td>
							  			<td width="12%" height="20" class="borderBR padl5">
											<input name="txtExchangeProdCode'.$i.'" type="text" class="txtfield" id="txtExchangeProdCode'.$i.'" readonly onclick = "return popup('.$i.')"  />
											<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
											<div id="coa_choices" class="autocomplete" style="display:none"></div>
											<script type="text/javascript">							
												//<![CDATA[
														var coa_choices = new Ajax.Autocompleter(\'txtExchangeProdCode'.$i.'\', \'coa_choices\', \'includes/jxProductExchangeAjax.php?index='.$i.'&ProductLevelID='.$row->ProductLevelID.'&prodLine='.$row->prodLineID.'&ParentID='.$row->ParentID.'&productID='.$row->prodID.'\', {afterUpdateElement : getProductExchange, indicator: \'indicator1\'});																			
												//]]>
											</script>
											<input name="hProdExchangeID'.$i.'" type="hidden" id="hProdExchangeID'.$i.'" />
							  			</td>   
							  			<td width="17%" height="20" class="borderBR padl5">
											<input name="txtExchangeProdName'.$i.'" type="text" class="txtfieldLabel" readonly id="txtExchangeProdName'.$i.'" size="37" maxlength="20"  />
										</td>
									</tr>
			         			' ;
			         			
			         			$i++ ;
			         		}
			         	}			         
			         
			         ?>
			         </div>
			    
			</table>
		</td>		
	</tr>
</table>
	
   
         


<br />
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td height="20">
		<div align="center">				
			<input name="btnSave" type="submit" class="btn" value="Save as draft" onclick = "return buttonValidation();" />
			&nbsp;
			<input name="btnConfirm" type="submit" class="btn" value="Confirm" onclick = "return buttonValidation();" />
			&nbsp;
			<input name="btnPrint" type="submit" class="btn" value="Print"  onclick = "return buttonValidation();"/>
			&nbsp;
			<input name="btnCancel" type="submit" class="btn" value="Cancel" onclick = "return cancel_btn();"/>
		</div>
	</td>
</tr>
</table>
<br>
<br>
</form>
<?php include "pages/inventory/inventory_call_ajax/DialogProductExchange.php"?>
</body>