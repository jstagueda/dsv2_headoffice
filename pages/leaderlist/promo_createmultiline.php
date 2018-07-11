
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<?php
	include IN_PATH.DS."scCreateMultilinePromo.php";
?>

<!-- calendar stylesheet -->

<link rel="stylesheet" type="text/css" href="css/ems.css">
<!-- product list -->
<script language="javascript" src="js/jxCreateMultilinePromo.js"  type="text/javascript"></script>

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>




<style type="text/css">
div.autocomplete {
  position:absolute;
}

div.autocomplete span { position:relative; top:2px; }

div.autocomplete ul {
    /* max-height: 150px;*/
    overflow-x: hidden;
    overflow-y: auto;
    width: 319px;
    border: 1px solid #FF00A6;
    color: #312E25;
    list-style-type:none;
    margin:0px;
    padding:0px;
    border-radius:6px;
    background-color:white;
    background: #F5F3E5;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  /*font-family: Verdana, Arial, Helvetica, sans-serif;*/
  /*font-size: 10px;*/
}

div.autocomplete ul li.selected{
    background-color: #EB0089;
    border:1px solid #c40370;
    color:white;
    font-weight:bold;
    margin:3px;
    border-radius:6px;
}

div.autocomplete ul li {
    line-height: 1.5;
    padding: 0.2em 0.4em;
    list-style-type:none;
    display:block;
    /*height:20px;*/
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    cursor:pointer;
}

.trheader td{padding:5px 3px; background:#FFDEF0; border:1px solid #FFA3E0; text-align:center; font-weight:bold;}
.trlist td{padding:3px; border:1px solid #FFA3E0;}
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
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
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
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
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
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td>
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Promo Header</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<table width="98%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="30%">&nbsp;</td>
					<td width="5%"></td>
					<td width="70%" align="right">&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" align="right"><strong>Promo Code</strong></td>
					<td align="center">:</td>
				    <td height="20"><input type="text" class="txtfieldg" value="" name="txtCode" onkeydown = "return CheckPromo(event);" id="txtCode">
			    </tr>
			    <tr>
				    <td height="20" align="right"><strong>Promo Description</strong></td>
					<td align="center">:</td>
				    <td width="20%" height="20"><input name="txtDescription" disabled = "true" onkeydown = "return NextField(event,'txtStartDate');"type="text" class="txtfieldg" id="txtDescription" value="" style="width: 362px;" maxlength="60">
					</td>
			    </tr>
				<tr>
				  	<td height="20" align="right"><strong>Start Date: </strong></td>
					<td align="center">:</td>
				  	<td width="20%" height="20">
						<input name="txtStartDate" type="text" class="txtfieldg" id="txtStartDate" onkeydown = "return NextField(event,'txtEndDate');" size="20"  disabled = "true" value="<?php echo $today; ?>">
                        <!--<i>(e.g. MM/DD/YYYY)</i>-->
                    </td>
				</tr>
				<tr>
				  	<td height="20" align="right"><strong>End Date</strong></td>
					<td align="center">:</td>
				  	<td width="20%" height="20">
						<input name="txtEndDate" type="text" class="txtfieldg" id="txtEndDate" onkeydown = "return NextField(event,'cboPReqtType');" size="20"  disabled = "true" value="<?php echo $end; ?>">
                        <!--<i>(e.g. MM/DD/YYYY)</i>-->
                    </td>
				</tr>
				<tr>
				  	<td height="20" align="right"><strong>Purchase Requirement Type</strong></td>
					<td align="center">:</td>
				  	<td height="20">
						<select name="cboPReqtType" class="txtfieldg" id="cboPReqtType" disabled = "true" onkeydown = "return NextField(event,'bpage');">
							<option value="1">Single</option>
							<option value="2">Cumulative</option>
						</select>
					</td>
				</tr>
				
				<!--Edited by Alvin to hidden Brochure Page-->
				<!--<tr>
				  	<td height="20" align="right"><strong>Brochure Page</strong></td>
					<td align="center">:</td>
				  	<td width="10%" height="20">
						<input name="bpage" type="text" value = "0" onkeyup='javascript:RemoveInvalidChars(bpage);' onkeydown = "return NextField(event,'epage');" class="txtfieldg" id="bpage" size="10" value="" style = "width: 5%;" disabled = "true">&nbsp; - &nbsp;
						<input name="epage" type="text" value = "0" onkeyup='javascript:RemoveInvalidChars(epage);' onkeydown = "return NextField(event,'txtMaxAvail1');" class="txtfieldg" id="epage" size="10" value="" style = "width: 5%;" disabled = "true">
					</td>
				</tr>
				<tr>
					<td colspan="3" height="20">&nbsp;</td>
				</tr> -->
				
					<input name="bpage" type="hidden" value = "0" onkeyup='javascript:RemoveInvalidChars(bpage);' onkeydown = "return NextField(event,'epage');" class="txtfieldg" id="bpage" size="10" value="" style = "width: 5%;" disabled = "true">&nbsp; - &nbsp;
					<input name="epage" type="hidden" value = "0" onkeyup='javascript:RemoveInvalidChars(epage);' onkeydown = "return NextField(event,'txtMaxAvail1');" class="txtfieldg" id="epage" size="10" value="" style = "width: 5%;" disabled = "true">
				<!--Edit ends here -->	
				
				</table>
			</td>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" disabled = "true">
					<tr>
						<td width="15%">&nbsp;</td>
						<td width="5%"></td>
						<td width="85%">&nbsp;</td>
					</tr>
					<tr>
						<td height="20" align="right"><strong>Max Availment</strong></td>
						<td align="center">:</td>
						<td></td>
					</tr>
					<tr>
						<td height="20" colspan="3" style="padding-left:40px;">
							<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" disabled = "true">
								<tr>
									<td width='15%' height='20' align="right"><strong>NonGSU</strong></td>
									<td align="center" width="5%">:</td>
									<td width='75%' height='20'><input type='text' id = 'txtMaxAvail1' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail2");' class='txtfield' name='txtMaxAvail1' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail1);'></td>
								</tr>
								<tr>
									<td width='15%' height='20' align="right"><strong>Direct GSU</strong></td>
									<td align="center" width="5%">:</td>
									<td width='75%' height='20'><input type='text' id = 'txtMaxAvail2' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail3");' class='txtfield' name='txtMaxAvail2' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail2);'></td>
								</tr>
								<tr>
									<td width='15%' height='20' align="right"><strong>Indirect GSU</strong></td>
									<td align="center" width="5%">:</td>
									<td width='75%' height='20'><input type='text' id = 'txtMaxAvail3' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail3");' class='txtfield' name='txtMaxAvail3' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail3);'></td>
								</tr>
							</table>
						</td>
					</tr>
					<!--<tr>
						<td height="20" align="right"><strong>Is Plus Plan</strong></td>
						<td align="center">:</td>
						<td height="20"><input type="checkbox" name="chkPlusPlan" id = "chkPlusPlan" value="1" disabled = "true"></td>
					</tr> -->
					
					<input type="hidden" name="chkPlusPlan" id = "chkPlusPlan" value="1" disabled = "true">
					
					<tr>
						<td height="20" align="right"><strong>For New Recruit Only</strong></td>
						<td align="center">:</td>
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
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td width= "45%" valign=top>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td>
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2" disabled = "true">
			<tr>
				<td valign="top" class="bgF9F8F7">
					<div style="max-height:227px; overflow:auto;">
						<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicTable" class="bgFFFFFF">
							<tr align="center" class="trheader">
								<td width="9%">Action</td>
								<td width="7%">Line No.</td>
								<td width="13%">Item Code</td>
								<td width="28%">Item Description</td>
								<td width="11%">Criteria</td>
								<td width="10%">Minimum</td>
								<td width="11%">PMG</td>
							</tr>
							<tr align="center" class="trlist">							
								<td width="9%">
									<input name="btnRemove1" type="button" class="btn" value="Remove" onclick="return deleteRow(this, 1);">
									<input type="hidden"  name="chkSelect[]" id="chkSelect" checked="checked"  value="1">
								</td>
								<td width="7%" align="center">1</td>
								<td width="13%">
									<input name="txtProdCode1" type="text" class="txtfieldg" id="txtProdCode1" style="width: 99%;" onkeypress="return selectItemCode(this);" value="" disabled = "true"/>
									<input name="hProdID1" type="hidden" id="hProdID1" value="" />
								</td>
								<td width="28%"><input disabled = "true" name="txtProdDesc1" type="text" class="txtfieldg" id="txtProdDesc1" style="width: 99%;" readonly="yes" /></td>
								<td width="11%">
									<select name="cboCriteria1" class="txtfieldg" id="cboCriteria1" style="width: 99%;" disabled = "true">
										<option value="2">Amount</option>
										<option value="1" selected="selected">Quantity</option>
									</select>
								</td>
								<td width="10%">
									<input disabled = "true" name="txtQty1" type="text" class="txtfieldg" id="txtQty1"  value="1" style="width: 99%; text-align:right" onkeypress="return addRow(this, event);"/>
								</td>
								<td width="11%">
									<input name="txtbPmg1" disabled = "true" type="text" id="txtbPmg1" class="txtfieldg" readonly="readonly"  style="width: 99%; text-align:left" value=""/>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
	</td>
	<td width= "1%">&nbsp;</td>
	<td width= "45%" valign=top>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td>
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Entitlement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
			<tr align="center" style="background:#FFDEF0;">
				<td height="25" class="txtpallete borderBR">
					<div align="left" class="padl5">
						<strong>Type :</strong>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<select name="cboType" class="txtfield" id="cboType" style="width: 100px;" onchange = "SelectionField()">
							<option value="1">Set</option>
							<option value="2">Selection</option>
						</select>
						&nbsp;&nbsp;
						<strong>Selection No. :</strong>
						&nbsp;
						<input name="txtTypeQty" type="text" class="txtfield" id="txtTypeQty" onkeyup = "javascript:RemoveInvalidChars(txtTypeQty);"  style="width: 60px; text-align: right;">
					</div>
				</td>
			</tr>
			<tr>
				<td>
					<div style="max-height:200px; overflow:auto;">
						<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicEntTable" class="bgFFFFFF">
							<tr align="center" class="trheader">
								<td width="10%">Action</td>
								<td width="9%">Line No.</td>
								<td width="14%">Item Code</td>
								<td width="33%">Item Description</td>
								<td width="12%">Criteria</td>
								<td width="10%">Qty/Price</div></td>
								<td width="12%">PMG</td>
							</tr>
							<tr align="center" class="trlist">								
								<td>
									<input name="btnERemove1" type="button" class="btn" value="Remove" onclick="return deleteRow2(this)">
									<input type="hidden"  name="chkSelectEnt[]" id="chkSelectEnt" checked="checked"  value="1">
								</td>
								<td>1</td>
								<td>
									<input name="txtEProdCode1" disabled = "true" type="text" class="txtfieldg" id="txtEProdCode1" style="width: 99%;" value="" onkeypress="return selectItemCode2(this);"/>									
									<input name="hEProdID1" type="hidden" id="hEProdID1" value="" />																		
								</td>
								<td>
									<input name="txtEProdDesc1" disabled = "true" type="text" class="txtfieldg" id="txtEProdDesc1" style="width: 99%;" readonly="yes" />
									<input name="hEUnitPrice1" type="hidden" id="hEUnitPrice1" value=""/>
								</td>
								<td>
									<input name="hEpmgid1" type="hidden" id="hEpmgid1" value=""/>
									<select name="cboECriteria1" class="txtfieldg" id="cboECriteria1" style="width: 99%;" disabled = "true">
										<option value="2" selected="selected">Price</option>
										<option value="1">Quantity</option>
									</select>
								</td>
								<td>
									<input name="txtEQty1" disabled = "true" type="text" class="txtfieldg" id="txtEQty1"  value="1" style="width: 99%; text-align:right" onkeypress='return addRow2(this, event)'/>
								</td>
								<td>
									<select name="cboEPMG1" class="txtfieldg" id="cboEPMG1" style="width: 99%;">
										<?php
											if($rs_pmg->num_rows){
												while($row = $rs_pmg->fetch_object()){
													echo "<option value='$row->ID'>$row->Code</option>";
												}
												$rs_pmg->close();
											}
										?>
									</select>
								</td>
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
<table width="98%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">

		<input name='btnSave'  id = "btnSave" type='submit' class='btn' value='Save' onclick='return confirmSave();'>
		<input name='btnCancel' type='submit' class='btn' value='Cancel' onclick='return confirmCancel();'>
	</td>
</tr>
</table>
</form>
<br>
