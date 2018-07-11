<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxRecordProductExchange.js"  type="text/javascript"></script>
<script src="js/shortcut.js" type="text/javascript"></script>
<script type="text/javascript">
jQuery(document).ready(function(){
/*
-------------
Author: @Gino Leabres;
-----------------
*/
	jQuery('#btnNextClick').click(function(){
		if(jQuery('#hcnt').val()){
			var cnt = jQuery('#hcnt').val()
				for(var  i = 1 ; i <= cnt ; i ++){
				
					var isChecked = jQuery('#chkID' + i).attr('checked')?true:false;
						if ( isChecked == true ){
							var reason = jQuery("#cboReason" + i).val();
							var qty = jQuery("#txtQty" + i).val();
							var error = 0;
							
							if (qty == ""){
								//alert('Please Input Qty');
								error++;
							}
							
							if(reason == 0){
								//alert('Please Input Reason');
								error++;
							}
						}
				}
				if(error == 0){
							 jQuery("#frmRecordProdExchange").submit();
				}else{
					alert('Please Input Qty/Reason');
					return false;
				}
				
				
		}
		else{
			alert('Please Input Customer Code/SI Number');
			return false;
		}
	})
});

function qtyvalidation(cnt) 
{ 
	var clear = '';
	var ONfocus = setTimeout(function(){
										jQuery('#' + 'txtQty' + cnt).focus();
									   },1); 
	
	if( parseInt(jQuery('#' + 'txtQty' + cnt).val()) > parseInt(jQuery('#' + 'Qty' + cnt).val()) ){
	
		alert("Quantity should not exceed");
		ONfocus;
		jQuery('#' + 'txtQty' + cnt).val(clear);
	
	}
}

function cancel_btn()
{
	var r=confirm("Are you sure want to cancel?")
		if (r==true){
		//alert("You pressed OK!")
		window.location.href = "index.php?pageid=1";
		return false;
		}
		else{
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
<form name="frmRecordProdExchange" id="frmRecordProdExchange"  method="post" action="includes/scRecordProductExchangeSubmit.php">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
   <tr>
      <td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory Cycle Main</a></td>
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
							<input name="txtCustomer" type="text" class="txtfield" id="txtCustomer"  />
							<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
							<div id="coa_choices" class="autocomplete" style="display:none"></div>
							<script type="text/javascript">							
								 //<![CDATA[
                                        var coa_choices = new Ajax.Autocompleter('txtCustomer', 'coa_choices', 'includes/scCustomerListAjax.php', {afterUpdateElement : getSelectionCOAID, indicator: 'indicator1'});																			
                                        //]]>
							</script>
							<input name="hCustomerID" type="hidden" id="hCustomerID" />
		  				</td>
					</tr>	
					<tr>
						<td height="25" class="txt10" align="right">Customer Name :</td>
						<td height="25" class="txt10" align="left"><input type="text" readonly="readonly"  class="txtfieldLabel" name="txtCustomerName" id="txtCustomerName" size="35" maxlength="15" ></td>
					</tr>	
					<tr>
						<td height="25" class="txt10" align="right">IBM Code / IBM Name :</td>
						<td height="25" class="txt10" align="left"><input type="text" class="txtfieldLabel" readonly="readonly"  name="txtIBMName" id="txtIBMName" size="50" maxlength="15" ></td>
					</tr>
					<tr>
						<td height="25" class="txt10"  align="right" >SI No. : </td>
						<td height="25" width="60%"> 
							<input name="txtSINo" type="text" class="txtfield" id="txtSINo" value="" align="left"  disabled="disabled"/>
								
								<span id="indicator2" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
								<div id="dealer_choices2" class="autocomplete" style="display:none"></div>
								<script type="text/javascript">				
										
										var url = "includes/jxListInvoiceProductExchange.php"				
										 	//<![CDATA[								
		                                 var dealer_choices = new Ajax.Autocompleter('txtSINo', 'dealer_choices2', url , {afterUpdateElement : getSI, indicator: 'indicator2'});																			
		                                        //]]>
										</script>
							<input name="hSIID" type="hidden" id="hSIID" value=""/>
						</td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Sales Invoice Date : </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtSIDate" readonly="readonly"  id="txtSIDate" size="50" maxlength="15" ></td>
					</tr>		
					<tr>
						<td height="25" class="txt10"  align="right" >Reference SO No. : </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtRefNo" readonly="readonly"  id="txtRefNo" size="50" maxlength="15" ></td>
					</tr>
					<tr>
						<td height="25" class="txt10"  align="right" >Remarks: </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtRemarks" readonly="readonly"  id="txtRemarks" size="50" maxlength="15" ></td>
					</tr>					
				</table>
				</td>
				<td valign="top">
				<table width="98%" border="0" cellspacing="1" cellpadding="0">
					
					<tr>
						<td height="25" class="txt10"  align="right" >Document No. : </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtDocNo" readonly="readonly"  id="txtDocNo" size="50" maxlength="15" ></td>
					</tr>
				
					<tr>
						<td height="25" class="txt10"  align="right" >Branch : </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtBranch" readonly="readonly"  id="txtBranch" size="50" maxlength="15" ></td>
					</tr>
					<tr>
						<td height="25" class="txt10"  align="right" >Created By : </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtCreatedBy" readonly="readonly"  id="txtCreatedBy" size="50" maxlength="15" ></td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Status: </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtStatus" readonly="readonly"  id="txtStatus" size="50" maxlength="15" ></td>
					</tr>	
					<tr>
						<td height="25" class="txt10"  align="right" >Confirmed By: </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtConfimedBy" readonly="readonly"  id="txtConfimedBy" size="50" maxlength="15" ></td>
					</tr>
					<tr>
						<td height="25" class="txt10"  align="right" >Date Confirmed: </td>
						<td height="25" width="60%"><input type="text" class="txtfieldLabel" name="txtDateConfirmed" readonly="readonly"  id="txtDateConfirmed"size="50" maxlength="15" ></td>
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
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">Sales Invoice Details</td>
              </tr>
          </table></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
  </table>
  
  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl3">
  <tr>
  <?php
	/**********************************
	**  Modified by: Gino C. Leabres***
	**  10.09.2012*********************
	**  ginophp@yahoo.com**************
	***********************************/
	/*
  	<td class="tab">
	
  		<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
  		<tr align="center">
  			<td width="5%" height="20" class="bdiv_r">&nbsp; &nbsp;<input type="checkbox" name="chkAll" id="chkAll" onclick="return enableAll();"></td>
  			<td width="10%" height="20" class="bdiv_r">Product Code</td>   
  			<td width="20%" height="20" class="bdiv_r">Product Name</td>
  			<td width="5%" height="20" class="bdiv_r">UOM</td>
  			<td width="7%" height="20" class="bdiv_r">PMG</td>
  			<td width="10%" height="20" class="bdiv_r">Promo</td>
  			<td width="5%" height="20" class="bdiv_r">Invoice Qty</td>  			
  			<td width="5%" height="20" class="bdiv_r">Price</td>
  			<td width="8%" height="20" class="bdiv_r">Net Amount</td>
  			<td width="5%" height="20" class="bdiv_r">Qty</td>
  			<td width="13%" height="20" >Reason</td>
		</tr>
		</table>
		
	</td>
	*/ ?>
	</tr>
        <tr>
        <td valign="top" class="bgF9F8F7">
        <div class="scroll_300" id="prodlist">
        <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF" id="dynamicTable">
		<?php
		/**********************************
		**  Modified by: Gino C. Leabres***
		**  10.09.2012*********************
		**  ginophp@yahoo.com**************
		***********************************/
		?>
			<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
				<tr align="center" class ="tab">
					<td width="5%" height="20" class="bdiv_r">&nbsp; &nbsp;<input type="checkbox" name="chkAll" id="chkAll" onclick="return enableAll();"></td>
					<td width="10%" height="20" class="bdiv_r">Product Code</td>   
					<td width="20%" height="20" class="bdiv_r">Product Name</td>
					<td width="5%" height="20" class="bdiv_r">UOM</td>
					<td width="7%" height="20" class="bdiv_r">PMG</td>
					<td width="10%" height="20" class="bdiv_r">Promo</td>
					<td width="5%" height="20" class="bdiv_r">Invoice Qty</td>  			
					<td width="5%" height="20" class="bdiv_r">Price</td>
					<td width="8%" height="20" class="bdiv_r">Net Amount</td>
					<td width="5%" height="20" class="bdiv_r">Qty</td>
					<td width="13%" height="20" >Reason</td>
				</tr>
			</table>
        </table>
		<br />
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20">
					<div align="center" id = 'Buttons'>				
						<input name="btnNext" id = "btnNextClick"type="submit" class="btn" value="Next" />
						&nbsp;
						<input name="btnCancel" type="submit" class="btn" value="Cancel" onclick = "return cancel_btn();"/>
					</div>
				</td>
			</tr>
		</table>
         </div>
        </td>
        </tr>
    
  </table>
      

      <br />
    
   </td>
  </tr>
</table>
<br />
<br />
</form>
</body>