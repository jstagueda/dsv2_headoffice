<?php
/* 
 *  Modified by: marygrace cabardo 
 *  10.22.2012
 *  marygrace.cabardo@gmail.com
 */
  include IN_PATH . DS . "scCreateSingleLinePromo.php";
?>
<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<!-- product list -->
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxCreateSingleLinePromo.js"  type="text/javascript"></script>
<script>
function confirmVerify()
{
	var ml = document.frmCreateSingleLine;
	var promo_code = ml.txtCode;	
	
	if (Trim(promo_code.value) == "")
	{
		alert ('Promo Code required.');
		promo_code.focus();
		return false;
	}	
}


function confirmVerifiedDesc()
{
	alert(jQuery('#txtDescription').val());
	alert(jQuery('#txtQty').val());
	return false;
}

function validateForm()
{
	var x=document.forms["frmCreateMultiLine"]["txtDescription"].value;
	if (x==null || x=="")
	  {
	  alert("Enter Promo Description.");
	  redirect_to("index.php?pageid=61");
	  return false;
	  }
	  
	var y=document.forms["frmCreateMultiLine"]["txtQty"].value;
	if (y==null || y=="")
	  {
	  alert("Enter Quantity / Amount value!!!!!!!!!.");
	  redirect_to("index.php?pageid=61");
	  return false;
	  }  
}
</script>



<style type="text/css">
<!--
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

<body onload="return enableFields();">
<form name="frmCreateSingleLine" method="post" action="index.php?pageid=61" onsubmit="return validateForm()">
<input type="hidden" id="hBuyInCnt" name="hBuyInCnt" value="0">
<input type="hidden" id="hBuyInIndex" name="hBuyInIndex" value="1">
<input type="hidden" id="hEntitlementCnt" name="hEntitlementCnt" value="0">
<input type="hidden" id="hEntIndex" name="hEntIndex" value="1">
<input type="hidden" id="hIndex" name="hIndex" value="1">
<input type="hidden" id="hPMGID" name="hPMGID" value="<?php echo $pmg_id; ?>">
<input type="hidden" id="hPMGCode" name="hPMGCode" value="<?php echo $pmg_code; ?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="topnav">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		    <td width="70%" class="txtgreenbold13" align="left"></td>
			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=80">Leader List Main</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%">&nbsp;<a class="txtgreenbold13">Create Single Line Promo</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
if ($errmsg != "") {
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%" class="txtreds">&nbsp;<b><?php echo $errmsg; ?></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php

}
?>
<br>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td> 
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Promo Header</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="20%">&nbsp;</td>
					<td width="90%" align="right">&nbsp;</td>
				</tr>			
				<tr>
				    <td height="20" align="right"><div align="right" class="txtpallete padl5"><strong>Promo Code :</strong></div></td>
				    <td height="20">&nbsp;&nbsp;<input type="text" class="txtfieldg" value="<?php echo $_GET['code']; ?>" name="txtCode" readonly="yes" id="txtCode">
			    </tr>
			    <tr>
				    <td height="20" align="right"><div align="right" class="txtpallete padl5"><strong>Promo Description :</strong></div></td>
				    <td width="20%" height="20">&nbsp;&nbsp;<input name="txtDescription" type="text" class="txtfieldg" onkeypress='confirmVerifiedDesc()' id="txtDescription" value="" size="30" style="width: 362px;" maxlength="60">
					</td>
			    </tr>
				<tr>
				  	<td height="20" align="right"><div align="right" class="txtpallete padl5"><strong>Start Date: </strong></div></td>
				  	<td width="20%" height="20">&nbsp;
						<input name="txtStartDate" type="text" class="txtfieldg" id="txtStartDate" size="20" readonly="yes" value="<?php echo $today; ?>">
						<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>
				<tr>
				  	<td height="20" align="right"><div align="right" class="txtpallete padl5"><strong>End Date: </strong></div></td>
				  	<td width="20%" height="20">&nbsp;
						<input name="txtEndDate" type="text" class="txtfieldg" id="txtEndDate" size="20" readonly="yes" value="<?php echo $end; ?>">
						<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
						<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>		
				<tr>
					<td colspan="2" height="20">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="15%">&nbsp;</td>
					<td width="85%">&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" valign="top"><strong align="right" class="txtpallete">Max Availment :</strong></td>
				    <td height="20">
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				    	<?php
						if ($rs_gsutype->num_rows) {
							while ($row = $rs_gsutype->fetch_object()) {
								$txt = 'txtMaxAvail' . $row->ID;
								echo "<tr>
				    					<td width='15%' align='right' height='20'><strong  class='txtpallete'>$row->Name :</strong></div></td>
				    					<td width='75%' height='20'>&nbsp;<input type='text'  class='txtfieldgd' name='$txt'></td>
				    				     </tr>";
							}
							$rs_gsutype->close();
						}
						?>
				    	</table>
				    </td>
			    </tr>
			   <tr>
				    <td height="20"><div align="right" class="txtpallete"><strong>Is Plus Plan :</strong></div></td>
				    <td height="20"><input type="checkbox" name="chkPlusPlan" value="1" <?php if(isset($_POST['chkPlusPlan'])) { echo "checked"; } ?>></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td width= "44%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="12%" height="20" class="txtpallete borderBR"><div align="center"></div></td>
							<td width="9%"  height="20" class="txtpallete borderBR"><div align="center">Line No.</div></td>
							<td width="16%" height="20" class="txtpallete borderBR"><div align="center">Item Code</div></td>
							<td width="28%" height="20" class="txtpallete borderBR"><div align="left">&nbsp;Item Description</div></td>			
							<td width="12%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>
							<td width="13%" height="20" class="txtpallete borderBR"><div align="center">Minimum</div></td>			
							<td width="13%" height="20" class="txtpallete borderBR"><div align="center">PMG</div></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div class="scroll_150">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicTable" class="bgFFFFFF">
					<tr align="center">
						<td width="6%" height="20" class="borderBR">
						 <input name="btnRemove1" type="button" class="btn" value="Remove" onclick="deleteRow(this.parentNode.parentNode.rowIndex)">
						</td>
						<td width="8%" height="20" class="txtredbold borderBR"><div align="center">1</div></td>
						<td width="10%" height="20" class="borderBR"><div align="left"  class="padl5">
							<input name="txtProdCode1" type="text" class="txtfieldg" id="txtProdCode1" style="width: 70px; value=""/>
							<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
							<div id="prod_choices1" class="autocomplete" style="display:none"></div>
							<script type="text/javascript">							
								 //<![CDATA[
		                        	var prod_choices = new Ajax.Autocompleter('txtProdCode1', 'prod_choices1', 'includes/scProductListAjax.php?index=1', {afterUpdateElement : getSelectionProductList, indicator: 'indicator1'});																			
		                        //]]>
							</script>
							<input name="hProdID1" type="hidden" id="hProdID1" value="" />
						
						</div></td>
						<td width="24%" height="20" class="borderBR"><div align="left" class="padl5"><input name="txtProdDesc1" type="text" class="txtfield" id="txtProdDesc1" style="width: 100%;" readonly="yes" /></div></td>			
						<td width="10%" height="20" class="borderBR"><div align="center">
							<select name="cboCriteria1" class="txtfield" id="cboCriteria1" style="width: 90%;" >
								<option value="2">Amount</option>
								<option value="1" selected="selected">Quantity</option>
							</select>
						</div></td>
						<td width="11%" height="20" class="borderBR"><div align="center"><input name="txtQty1" onkeypress='confirmVerifiedDesc()' type="text" class="txtfield" id="txtQty1"  value="1" style="width: 90%; text-align:right" onBlur='addRow("dynamicTable");return false;'/></div></td>
						<td width="9%" height="20" class="borderBR"><div align="center">
						<input name="txtbPmg1" type="text" id="txtbPmg1" class="txtfield" readonly="readonly"  style="width: 90%; text-align:left" value=""/></div></td>			
					</tr>
					</table>
				</div>
			</td>
		</tr>
		</table>
	</td>
	<td width= "1%">&nbsp;</td>
	<td width= "50%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Entitlement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="11%" height="20" class="txtpallete borderBR"><div align="center" class="padl5"></div></td>
							<td width="9%" height="20" class="txtpallete borderBR"><div align="center">Line No.</div></td>
							<td width="16%" height="20" class="txtpallete borderBR"><div align="center">Item Code</div></td>
							<td width="29%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td>			
							<td width="12%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>
							<td width="13%" height="20" class="txtpallete borderBR"><div align="center">Qty/Price</div></td>			
							<td width="13%" height="20" class="txtpallete borderBR"><div align="center">PMG</div></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div class="scroll_150">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicEntTable" class="bgFFFFFF">
					<tr align="center">
						<td width="8%" height="20" class="borderBR">
						 <input name="btnRemove1" type="button" class="btn" value="Remove" onclick="deleteRow2(this.parentNode.parentNode.rowIndex)">
						</td>
						<td width="8%" height="20" class="txtredbold borderBR"><div align="center" class="padl5">1</div></td>
						<td width="12%" height="20" class="borderBR"><div align="left" class="padl5">
							<input name="txtEProdCode1" type="text" class="txtfieldg" id="txtEProdCode1" style="width: 75px;" value=""/>
							<span id="indicatorE1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
							<div id="prod_choicesE1" class="autocomplete" style="display:none"></div>
							<script type="text/javascript">							
								 //<![CDATA[
		                        	var prod_choices = new Ajax.Autocompleter('txtEProdCode1', 'prod_choicesE1', 'includes/scProductListEntitlementAjax.php?index=1', {afterUpdateElement : getSelectionProductList2, indicator: 'indicatorE1'});																			
		                        //]]>
								</script>
							<input name="hEProdID1" type="hidden" id="hEProdID1" value="" />
							<input name="hEUnitPrice1" type="hidden" id="hEUnitPrice1" value=""/>
							<input name="hEpmgid1" type="hidden" id="hEpmgid1" value=""/>
						</div></td>
						<td width="24%" height="20" class="borderBR"><div align="left" class="padl5"><input name="txtEProdDesc1" type="text" class="txtfield" id="txtEProdDesc1" style="width: 100%;" readonly="yes" /></div></td>			
						<td width="10%" height="20" class="borderBR"><div align="center">
							<select name="cboECriteria1" class="txtfield" id="cboECriteria1" style="width: 90%;">
								<option value="1">Quantity</option>
								<option value="2" selected="selected">Price</option>
							</select>
						</div></td>
						<td width="11%" height="20" class="borderBR"><div align="center"><input name="txtEQty1" type="text" class="txtfield" id="txtEQty1"  value="1" style="width: 90%; text-align:right" onBlur='addRow2("dynamicEntTable");return false;'/></div></td>			
						<td width="9%" height="20" class="borderBR"><div align="center">
						<!--<input name="txtPmg1" type="text" id="txtPmg1" class="txtfield" readonly="readonly"  style="width: 92px; text-align:left" value=""/>-->
							<select name="cboEPMG1"  class="txtfield" id="cboEPMG1" style="width: 90%;" >
								<?php
									if ($rs_pmg->num_rows) 
									{
										while ($row = $rs_pmg->fetch_object()) 
										{
											echo "<option value='$row->ID'>$row->Code</option>";
										}
										$rs_pmg->close();
									}
								?>
							</select>
						</div></td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="90%" align="left"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
		<input name='btnSaves' type='submit' class='btn' value='Save' onclick='return confirmSaves();'>
		<input name='btnCancel' type='submit' class='btn' value='Cancel' onclick='return confirmCancel();'>
	</td>			
</tr>
</table>
<br>
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