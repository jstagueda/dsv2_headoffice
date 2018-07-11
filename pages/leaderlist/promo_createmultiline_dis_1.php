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
<script language="javascript" src="js/jxCreateMultilinePromo.js"  type="text/javascript"></script>

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

<?php
	include IN_PATH.DS."scCreateMultilinePromo.php";
?>
<script type="text/javascript">
function RemoveInvalidChars(strString)
{
    var iChars = "1234567890.";
	   
   var strtovalidate = strString.value;
   var strlength = strtovalidate.length;
   var strChar;
   var ctr = 0;
   var newStr = '';
   if (strlength == 0)
   {
	return false;
   }

	for (i = 0; i < strlength; i++)
	{
		strChar = strtovalidate.charAt(i);
			if 	(!(iChars.indexOf(strChar) == -1))
			{
				newStr = newStr + strChar;
			}
	}
	strString.value = newStr;
}
</script>

<body onload="return enableFields();">
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<!-- product list -->
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<form name="frmCreateMultiLine" method="post" action="index.php?pageid=63">
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
			<td width="70%">&nbsp;<a class="txtgreenbold13">Create Multi Line Promo</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
	if ($errmsg != "")
	{
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
					<td width="25%">&nbsp;</td>
					<td width="75%" align="right">&nbsp;</td>
				</tr>			
					<tr>
				    <td height="20" align="right"><div align="right" class="txtpallete"><strong>Promo Code :</strong></div></td>
				     <td height="20">&nbsp;&nbsp;<input name="txtCode" type="text" class="txtfieldg " id="txtCode" value="<?php if (isset($_GET['promoID'])) { echo $promocode; } else { if (isset($_POST['txtCode'])) { echo $_POST['txtCode']; } } ?>" size="30" <?php if (isset($_GET['promoID'])) {?>readonly="yes"<?php } ?>>
					&nbsp;Click to verify Promo Code if Exist.&nbsp;<input name='btnVerify' type='submit' class='btn' onclick="" value="Verify"></td>
			    </tr>
			    <tr>
				    <td height="20"><div align="right" class="txtpallete"><strong>Promo Description :</strong></div></td>
				    <td height="20">&nbsp;&nbsp;<input  name="txtDescription" type="text" class="txtfieldg " id="txtDescription" value="<?php if (isset($_GET['promoID'])) { echo $promodesc; } else { if (isset($_POST['txtDescription'])) { echo $_POST['txtDescription']; } } ?>" style="width: 362px;" maxlength="60" <?php if (isset($_GET['promoID'])) {?>readonly="yes"<?php } ?>>
					</td>
			    </tr>
				<tr>
				  	<td width="20%" height="20"><div align="right" class="txtpallete"><strong>Start Date: </strong></div></td>
				  	<td width="20%" height="20">&nbsp;
						<input name="txtStartDate" type="text" class="txtfieldg" id="txtStartDate" size="20" readonly="yes" value="<?php echo $today; ?>" onChange="checkEndDate();">
						<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value="">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>
				<tr>
				  	<td width="20%" height="20"><div align="right" class="txtpallete"><strong>End Date: </strong></div></td>
				  	<td width="20%" height="20">&nbsp;
						<input name="txtEndDate" type="text" class="txtfieldg" id="txtEndDate" size="20" readonly="yes" value="<?php echo $today; ?>">
						<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
						<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>		
                                <tr>
				  	<td height="20"><div align="right" class="txtpallete"><strong>Purchase Requirement Type: </strong></div></td>
				  	<td height="20">&nbsp;
						<select name="cboPReqtType" class="txtfield" id="cboPReqtType">
							<option value="1">Single</option>
							<option value="2">Cumulative</option>
						</select>	
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
				    <td height="20" valign="top"><div align="right" class="txtpallete"><strong>Max Availment :</strong></div></td>
				    <td height="20">
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				    	<?php
				    	if($rs_gsutype->num_rows)
						{
							while($row = $rs_gsutype->fetch_object())
							{
								$txt = 'txtMaxAvail'.$row->ID;
								echo "<tr>
				    						<td width='15%' height='20'><div align='right' class='txtpallete padl5'><strong>$row->Name :</strong></div></td>
				    						<td width='75%' height='20'>&nbsp;<input type='text' class='txtfield' name='$txt' onkeyup='javascript:RemoveInvalidChars($txt);'></td>
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
				    <td height="20"><input type="checkbox" name="chkPlusPlan" value="1"></td>
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
	<td width= "45%">
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
                                                        <td width="11%" height="20" class="txtpallete borderBR"><div align="center" class="padl5"></div></td>
							<td width="8%"  height="20" class="txtpallete borderBR"><div align="center">Line No.</div></td>
							<td width="12%" height="20" class="txtpallete borderBR"><div align="center" class="">Item Code</div></td>
							<td width="32%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td>			
							<td width="13%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>
							<td width="13%" height="20" class="txtpallete borderBR"><div align="center">Minimum</div></td>			
							<td width="12%" height="20" class="txtpallete borderBR"><div align="center">PMG</div></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr align="center">
			<td height="25" class="borderBR">&nbsp;</td>
		</tr>
		<tr>
			<td>
				<div class="scroll_150">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicTable" class="bgFFFFFF">
					<tr align="center">
					   <input type="hidden"  name="chkSelect[]" id="chkSelect" checked="checked"  value="1">
					    <td width="10%" height="20" class="borderBR">
						 <input name="btnRemove1" type="button" class="btn" value="Remove" onclick="deleteRow(this.parentNode.parentNode.rowIndex, 1)">						
						</td>						
						<td width="8%" height="20" class="borderBR"><div align="center">1</div></td>
						<td width="12%" height="20" class="borderBR"><div align="right" class="txtpallete">
							<input name="txtProdCode1" type="text" class="txtfield" id="txtProdCode1" style="width: 70px;" value=""/>
							<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
							<div id="prod_choices1" class="autocomplete" style="display:none"></div>
							<script type="text/javascript">							
								 //<![CDATA[
		                        	var prod_choices = new Ajax.Autocompleter('txtProdCode1', 'prod_choices1', 'includes/scProductListAjax.php?index=1', {afterUpdateElement : getSelectionProductList, indicator: 'indicator1'});																			
		                        //]]>
							</script>
							<input name="hProdID1" type="hidden" id="hProdID1" value="" />
						</div></td>
						<td width="31%" height="20" class="borderBR"><div align="right" class="txtpallete"><input name="txtProdDesc1" type="text" class="txtfield" id="txtProdDesc1" style="width: 200px;" readonly="yes" /></div></td>			
						<td width="13%" height="20" class="borderBR"><div align="center">
							<select name="cboCriteria1" class="txtfield" id="cboCriteria1" style="width: 80px;" >
								<option value="2">Amount</option>
								<option value="1" selected="selected">Quantity</option>
							</select>
						</div></td>
						<td width="13%" height="20" class="borderBR"><div align="center"><input name="txtQty1" type="text" class="txtfield" id="txtQty1"  value="1" style="width: 45px; text-align:right" onKeyPress="return disableEnterKey(this, event, 1);"/></div></td>
						<td width="20%" height="20" class="borderBR"><div align="center">
						<input name="txtbPmg1" type="text" id="txtbPmg1" class="txtfield" readonly="readonly"  style="width: 75px; text-align:left" value=""/></div></td>						
					</tr>
					</table>
				</div>
			</td>
		</tr>
		</table>
	</td>
	<td width= "1%">&nbsp;</td>
	<td width= "45%">
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
                                                    <td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5"></div></td>
                                                    <td width="8%"  height="20" class="txtpallete borderBR"><div align="center">Line No.</div></td>
                                                    <td width="12%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Code</div></td>
                                                    <td width="32%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td>			
                                                    <td width="13%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>
                                                    <td width="13%" height="20" class="txtpallete borderBR"><div align="center">Qty/Price</div></td>			
                                                    <td width="12%" height="20" class="txtpallete borderBR"><div align="center">PMG</div></td>		
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr align="center">
			<td height="25" class="borderBR"><div align="left" class="txtpallete">&nbsp;&nbsp;&nbsp;&nbsp;
				<strong>Type :</strong>
				&nbsp;&nbsp;&nbsp;
				<select name="cboType" class="txtfield" id="cboType" style="width: 100px;"  onchange="selection();">
					<option value="2">Selection</option>
					<option value="1">Set</option>
				</select>
				&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;
				<input name="txtTypeQty" type="text" class="txtfield" id="txtTypeQty"  style="width: 60px; text-align: right;">
			</div></td>
		</tr>
		<tr>
			<td>
				<div class="scroll_150">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicEntTable" class="bgFFFFFF">
					<tr align="center">
					   <input type="hidden"  name="chkSelectEnt[]" id="chkSelectEnt" checked="checked"  value="1">
					    <td width="10%" height="20" class="borderBR"><input name="btnRemove1" type="button" class="btn" value="Remove" onclick="deleteRow2(this.parentNode.parentNode.rowIndex, 1)"></td>
						<td width="8%" height="20" class="borderBR"><div align="center">1</div></td>
						<td width="12%" height="20" class="borderBR"><div align="right" class="txtpallete">
							<input name="txtEProdCode1" type="text" class="txtfield" id="txtEProdCode1" style="width: 75px;" value=""/>
							<span id="indicatorE1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
							<div id="prod_choicesE1" class="autocomplete" style="display:none"></div>
							<script type="text/javascript">							
								 //<![CDATA[
		                        	var prod_choicesE1 = new Ajax.Autocompleter('txtEProdCode1', 'prod_choicesE1', 'includes/scProductListEntitlementAjax.php?index=1', {afterUpdateElement : getSelectionProductList2, indicator: 'indicatorE1'});																			
		                        //]]>
							</script>
							<input name="hEProdID1" type="hidden" id="hEProdID1" value="" />
							<input name="hEUnitPrice1" type="hidden" id="hEUnitPrice1" value=""/>
							<input name="hEpmgid1" type="hidden" id="hEpmgid1" value=""/>
						</div></td>
						<td width="31%" height="20" class="borderBR"><div align="right" class="txtpallete"><input name="txtEProdDesc1" type="text" class="txtfield" id="txtEProdDesc1" style="width: 200px;" readonly="yes" /></div></td>			
						<td width="12%" height="20" class="borderBR"><div align="center">
							<select name="cboECriteria1" class="txtfield" id="cboECriteria1" style="width: 80px;">
								<option value="2" selected="selected">Price</option>
								<option value="1">Quantity</option>
							</select>
						</div></td>
						<td width="12%" height="20" class="borderBR"><div align="center"><input name="txtEQty1" type="text" class="txtfield" id="txtEQty1"  value="1" style="width: 45px; text-align:right" onKeyPress='return disableEnterKey(this, event, 1)'/></div></td>
						<td width="20%" height="20" class="borderBR"><div align="center">
						<!--<input name="txtPmg1" type="text" id="txtPmg1" class="txtfield"  style="width: 138px; text-align:left" value=""/>-->
						<select name="cboEPMG1" class="txtfield" id="cboEPMG1" style="width: 80px;">
							<?php
								if($rs_pmg->num_rows)
								{
									while($row = $rs_pmg->fetch_object())
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
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
	
		<input name='btnSave' type='submit' class='btn' value='Save' onclick='return confirmSave();'>
		<input name='btnCancel' type='submit' class='btn' value='Cancel' onclick='return confirmCancel();'>
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