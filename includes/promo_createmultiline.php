<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<?php
	include IN_PATH.DS."scCreateMultilinePromo.php";
?>

<!-- calendar stylesheet -->

<link rel="stylesheet" type="text/css" href="css/ems.css">
<!-- product list -->
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />
<script language="javascript" src="js/jxCreateMultilinePromo.js"  type="text/javascript"></script>

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>




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

<form name="frmCreateMultiLine" method="post" action="index.php?pageid=63" onsubmit="return validateForm()" style="min-height:600px">
<input type="hidden" id="hBuyInIndex" name="hBuyInIndex" value="1">
<input type="hidden" id="hEntIndex" name="hEntIndex" value="1">
<input type="hidden" id="hBuyInCnt" name="hBuyInCnt" value="0">
<input type="hidden" id="hEntitlementCnt" name="hEntitlementCnt" value="0">
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
				    <td height="20" align="right"><div align="right" class="txtpallete padl5"><strong>Promo Code :</strong></div></td>
				    <td height="20">&nbsp;&nbsp;<input type="text" class="txtfieldg" value="" name="txtCode" onkeydown = "return CheckPromo(event);"   id="txtCode">
			    </tr>
			    <tr>
				    <td height="20" align="right"><div align="right" class="txtpallete padl5"><strong>Promo Description :</strong></div></td>
				    <td width="20%" height="20">&nbsp;&nbsp;<input name="txtDescription" disabled = "true" onkeydown = "return NextField(event,'txtStartDate');"type="text" class="txtfieldg" id="txtDescription" value="" style="width: 362px;" maxlength="60">
					</td>
			    </tr>
				<tr>
				  	<td height="20" align="right"><div align="right" class="txtpallete padl5"><strong>Start Date: </strong></div></td>
				  	<td width="20%" height="20">&nbsp;
						<input name="txtStartDate" type="text" class="txtfieldg" id="txtStartDate" onkeydown = "return NextField(event,'txtEndDate');" size="20" <? //readonly="yes" ?> disabled = "true" value="<?php echo $today; ?>">
                                                <i>(e.g. MM/DD/YYYY)</i>
                                        </td>
				</tr>
				<tr>
				  	<td height="20" align="right"><div align="right" class="txtpallete padl5"><strong>End Date: </strong></div></td>
				  	<td width="20%" height="20">&nbsp;
						<input name="txtEndDate" type="text" class="txtfieldg" id="txtEndDate" onkeydown = "return NextField(event,'cboPReqtType');" size="20" <? //readonly="yes" ?> disabled = "true" value="<?php echo $end; ?>">
                                                <i>(e.g. MM/DD/YYYY)</i>
                                        </td>
				</tr>	
				<tr>
				  	<td height="20"><div align="right" class="txtpallete"><strong>Purchase Requirement Type: </strong></div></td>
				  	<td height="20">&nbsp;
						<select name="cboPReqtType" class="txtfieldg" id="cboPReqtType" disabled = "true" onkeydown = "return NextField(event,'bpage');">
							<option value="1">Single</option>
							<option value="2">Cumulative</option>
						</select>	
					</td>
				</tr>
				<tr>
				  	<td height="20" align="right"><div align="right" class="txtpallete padl5"><strong>Brochure Page: </strong></div></td>
				  	<td width="10%" height="20">&nbsp;
						<input name="bpage" type="text" value = "0" onkeyup='javascript:RemoveInvalidChars(bpage);' onkeydown = "return NextField(event,'epage');" class="txtfieldg" id="bpage" size="10" value="" style = "width: 5%;" disabled = "true">&nbsp; - &nbsp;
						<input name="epage" type="text" value = "0" onkeyup='javascript:RemoveInvalidChars(epage);' onkeydown = "return NextField(event,'txtMaxAvail1');" class="txtfieldg" id="epage" size="10" value="" style = "width: 5%;" disabled = "true">
					</td>
				</tr>					
				<tr>
					<td colspan="2" height="20">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" disabled = "true">
				<tr>
					<td width="15%">&nbsp;</td>
					<td width="85%">&nbsp;</td>
				</tr>
					<tr>
				    <td height="20" valign="top"><div align="right" class="txtpallete"><strong>Max Availment :</strong></div></td>
				    <td height="20">
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" disabled = "true">
				    	<?php
				    	//if($rs_gsutype->num_rows)
						//{
						//	while($row = $rs_gsutype->fetch_object())
						//	{
						//		$txt = 'txtMaxAvail'.$row->ID;
						//		
						//		echo "<tr>
				    	//					<td width='15%' height='20'><div align='right' class='txtpallete padl5' disabled = 'true'><strong>$row->Name :</strong></div></td>
				    	//					<td width='75%' height='20'>&nbsp;<input type='text' id = '$txt' disabled = 'true' onkeydown = 'return NextField(event,".$txt.");' class='txtfield' name='$txt' onkeyup='javascript:RemoveInvalidChars($txt);'></td>
				    	//			</tr>";
						//	}
						//	$rs_gsutype->close();
						//}
				    	?>
						<tr>
								<td width='15%' height='20'><div align='right' class='txtpallete padl5' disabled = 'true'><strong>NonGSU  :</strong></div></td>
								<td width='75%' height='20'>&nbsp;<input type='text' id = 'txtMaxAvail1' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail2");' class='txtfield' name='txtMaxAvail1' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail1);'></td>
						</tr>
						<tr>
								<td width='15%' height='20'><div align='right' class='txtpallete padl5' disabled = 'true'><strong>Direct GSU  :</strong></div></td>
								<td width='75%' height='20'>&nbsp;<input type='text' id = 'txtMaxAvail2' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail3");' class='txtfield' name='txtMaxAvail2' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail2);'></td>
						</tr>
						<tr>
								<td width='15%' height='20'><div align='right' class='txtpallete padl5' disabled = 'true'><strong>Indirect GSU  :</strong></div></td>
								<td width='75%' height='20'>&nbsp;<input type='text' id = 'txtMaxAvail3' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail3");' class='txtfield' name='txtMaxAvail3' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail3);'></td>
						</tr>
				    	</table>
				    </td>
			    </tr>
			    <tr>
				    <td height="20"><div align="right" class="txtpallete"><strong>Is Plus Plan :</strong></div></td>
				    <td height="20"><input type="checkbox" name="chkPlusPlan" id = "chkPlusPlan" value="1" disabled = "true"></td>
				</tr>

				<tr>
				<td height="20"><div align="right" class="txtpallete"><strong>Is New Recruit :</strong></div></td>
				<td height="20">
						<select name = "IsNewRecruit" class="txtfield">
							<option value = 0> No </option>
							<option Value = 1> Yes </option>
						</select>
				</td>
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
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2" disabled = "true">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
                            <td width="9%" height="20" class="txtpallete "> <div align="center" class="padl5"></div></td>
							<td width="7%"  height="20" class="txtpallete"><div align="center">Line No.</div></td>
							<td width="13%" height="20" class="txtpallete"><div align="right" class="">Item Code</div></td>
							<td width="28%" height="20" class="txtpallete"><div align="center" class="padl5">Item Description</div></td>			
							<td width="11%" height="20" class="txtpallete"><div align="center">Criteria</div></td>
							<td width="10%" height="20" class="txtpallete"><div align="right">Minimum</div></td>			
							<td width="11%" height="20" class="txtpallete"><div align="center">PMG</div></td>
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
					    <td width="9%" height="20" class="borderBR">
						<input name="btnRemove1" type="button" class="btn" value="Remove" onclick="deleteRow(this.parentNode.parentNode.rowIndex, 1)">						
						</td>						
						<td width="7%" height="20" class="borderBR"><div align="center">1</div></td>
						<td width="13%" height="20" class="borderBR"><div align="left" class="padl5">
							<input name="txtProdCode1" type="text" class="txtfieldg" id="txtProdCode1" style="width: 80%;" value="" disabled = "true"/>
							<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
							<div id="prod_choices1" class="autocomplete" style="display:none"></div>
							<script type="text/javascript">							
								//<![CDATA[
								 var prod_choices = new Ajax.Autocompleter('txtProdCode1', 'prod_choices1', 'includes/scProductListAjax.php?index=1', {afterUpdateElement : getSelectionProductList, indicator: 'indicator1'});																			
								//]]>
							</script>
							<input name="hProdID1" type="hidden" id="hProdID1" value="" />
						</div></td>
						<td width="28%" height="20" class="borderBR"><div align="left" class="padl5"><input disabled = "true" name="txtProdDesc1" type="text" class="txtfieldg" id="txtProdDesc1" style="width: 95%;" readonly="yes" /></div></td>			
						<td width="11%" height="20" class="borderBR"><div align="center">
							<select name="cboCriteria1" class="txtfieldg" id="cboCriteria1" style="width: 85%;" disabled = "true">
								<option value="2">Amount</option>
								<option value="1" selected="selected">Quantity</option>
							</select>
						</div></td>
						
						<td width="10%" height="20" class="borderBR"><div align="center">
							<input disabled = "true" name="txtQty1" type="text" class="txtfieldg" id="txtQty1"  value="1" style="width: 45px; text-align:right" onkeypress="return addRow(event);"/></div></td>							
						<td width="11%" height="20" class="borderBR"><div align="center">
						<input name="txtbPmg1" disabled = "true" type="text" id="txtbPmg1" class="txtfieldg" readonly="readonly"  style="width: 70%; text-align:left" value=""/></div></td>						
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
							<td width="10%" height="20" class="txtpallete"><div align="center" class="padl5"></div></td>
							<td width="9%"  height="20" class="txtpallete"><div align="center">Line No.</div></td>
							<td width="14%" height="20" class="txtpallete"><div align="center" >&nbsp;Item Code</div></td>
							<td width="33%" height="20" class="txtpallete"><div align="center" >&nbsp;Item Description</div></td>			
							<td width="12%" height="20" class="txtpallete"><div align="center">Criteria</div></td>
							<td width="10%" height="20" class="txtpallete"><div align="center">Qty/Price</div></td>			
							<td width="12%" height="20" class="txtpallete"><div align="center">PMG</div></td>		
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr align="center">
			<td height="25" class="txtpallete borderBR"><div align="left" class="padl5">
				<strong>Type :</strong>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="cboType" class="txtfield" id="cboType" style="width: 100px;" onchange = "SelectionField()">
					<option value="2">Selection</option>
					<option value="1">Set</option>
				</select>
				&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;
				<input name="txtTypeQty" type="text" class="txtfield" id="txtTypeQty" onkeyup = "javascript:RemoveInvalidChars(txtTypeQty);"  style="width: 60px; text-align: right;">
			</div></td>
		</tr>
		<tr>
			<td>
				<div class="scroll_150">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicEntTable" class="bgFFFFFF">
					<tr align="center">
					   <input type="hidden"  name="chkSelectEnt[]" id="chkSelectEnt" checked="checked"  value="1">
					    <td width="10%" height="20"  class="borderBR"><input name="btnRemove1" type="button" class="btn" value="Remove" onclick="deleteRow2(this.parentNode.parentNode.rowIndex, 1)"></td>
						<td width="9%"  height="20"  class="borderBR"><div align="center">1</div></td>
						<td width="14%" height="20" class="borderBR"><div align="left" class="padl5">
							<input name="txtEProdCode1" disabled = "true" type="text" class="txtfieldg" id="txtEProdCode1" style="width: 80%;" value=""/>
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
						<td width="33%" height="20" class="borderBR"><div align="left" class="padl5"><input name="txtEProdDesc1" disabled = "true" type="text" class="txtfieldg" id="txtEProdDesc1" style="width: 92%;" readonly="yes" /></div></td>			
						<td width="12%" height="20" class="borderBR"><div align="center">
							<select name="cboECriteria1" class="txtfieldg" id="cboECriteria1" style="width: 90%;" disabled = "true">
								<option value="2" selected="selected">Price</option>
								<option value="1">Quantity</option>
							</select>
						</div></td>
						<td width="10%" height="20" class="borderBR"><div align="center"><input name="txtEQty1" disabled = "true" type="text" class="txtfieldg" id="txtEQty1"  value="1" style="width: 75%; text-align:right" onkeypress='return addRow2(event)'/></div></td>
						<td width="12%" height="20" class="borderBR"><div align="center">
						<!--<input name="txtPmg1" type="text" id="txtPmg1" class="txtfieldg"  style="width: 138px; text-align:left" value=""/>-->
						<select name="cboEPMG1" class="txtfieldg" id="cboEPMG1" style="width: 80%;">
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
	
		<input name='btnSave'  id = "btnSave" type='submit' class='btn' value='Save' onclick='return confirmSave();'>
		<input name='btnCancel' type='submit' class='btn' value='Cancel' onclick='return confirmCancel();'>
	</td>			
</tr>
</table>
</form>
<br>
