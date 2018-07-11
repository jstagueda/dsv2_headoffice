<?php
	//This Design Used For Marketing Brands Incentives
	include IN_PATH.DS."scCreateIncentives.php";

	$database->execute("DELETE FROM tpiincentivesentltmp where sessionID =".$session->emp_id);
	$database->execute("DELETE FROM tpiincentivesbuyintmp where sessionID =".$session->emp_id);
?>
<body>
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/jquery-ui-1.8.5.custom.css" title="win2k-cold-1" />
<!-- product list -->
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/bridgingincentives.js"  type="text/javascript"></script>
<br />
<form>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%">&nbsp;<a class="txtgreenbold13">Create Bridging Incentives</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"><input type="hidden" id="hEntIndex" name="hEntIndex" value=""><input type="hidden" id="hEntCnt" name="hEntCnt" value=""></td>
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Header</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
	<tr>
		<td valign="top" class="bgF9F8F7">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td width="50%">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<input type = "Hidden" value = "" name = "PromoCode">
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Promo Code :</strong></div></td>
							<td height="20">&nbsp;&nbsp;<input name="txtPromoCode" onkeydown = "return validatePromoCode(event)" value = "" type="text" class="txtfield" id="txtPromoCode" style="width: 150px;" maxlength="60" />&nbsp;</td>
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>Promo Description:</strong></div></td>
							<td height="20">&nbsp;&nbsp;<input name="txtPromoDesc" disabled = "true" onkeydown = "return NextField(event);" value = "" type="text" class="txtfield" id="txtPromoDesc"  style="width: 150px;" maxlength="60"></td>
						</tr>
						<tr>
						<td height="20"><div align="right"  class="txtpallete"><strong>Mechanics Type: </strong></div></td>
						<td height="20">&nbsp;
							<select name="MechType" class="txtfield" disabled = "true" id="MechType" style="visibility: visible">
										<option value="0">[SELECT TYPE]</option>
								<?php if($MechanicsType->num_rows): ?>
									<?php while ($row = $MechanicsType->fetch_object()): ?>
										<?php print_r($row); ?>
										<option value="<?php echo $row->ID; ?>"><?php echo $row->Name; ?></option>
									<?php endwhile; ?>
								<?php endif; ?>
							</select>
						</td>
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">&nbsp;
							<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" disabled = "true" size="20" style="width: 150px"  value = "<?php echo $today; ?>" >
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">&nbsp;
							<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" disabled = "true" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today; ?>">
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
					</table>
				</td>

				<td width="50%">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="25%">&nbsp;</td>
							<td width="75%" align="right">&nbsp;</td>
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Net of CPI:</strong></div></td>
							<td height="20">
								<select name="NoCPI" style="width: 70px" disabled = "true" class="txtfield" id="NoCPI" style="visibility: visible">
									<option value="0">No</option>
									<option value="1">Yes</option>
								</select>
							</td>
						</tr>

						<tr>
							<td height="20"><div align="right"  class="txtpallete"><strong>Max Availment:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></div></td>
							<tr>
								<td height="20" align="right"><div align="right" class="txtpallete"><strong>Non GSU:</strong></div></td>
								<td height="20">&nbsp;&nbsp;<input name="txtNonGSU" value = "" onkeyup = "javascript:RemoveInvalidChars(txtNonGSU);" disabled = "true" type="text" class="txtfield" id="txtNonGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>
							</tr>

							<tr>
								<td height="20" align="right"><div align="right" class="txtpallete"><strong>Direct GSU:</strong></div></td>
								<td height="20">&nbsp;&nbsp;<input name="txtDirectGSU" value = "" onkeyup = "javascript:RemoveInvalidChars(txtDirectGSU);" disabled = "true" type="text" class="txtfield" id="txtDirectGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>
							</tr>
							<tr>
								<td height="20" align="right"><div align="right" class="txtpallete"><strong>Indirect GSU:</strong></div></td>
								<td height="20">&nbsp;&nbsp;<input name="txtIndirectGSU" onkeyup = "javascript:RemoveInvalidChars(txtIndirectGSU);"  value = ""  disabled = "true" type="text" class="txtfield" id="txtIndirectGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>
							</tr>
							<tr>
								<td height="20" align="right"><div align="right" class="txtpallete"><strong>Is Plus Plan:</strong></div></td>
								<td>&nbsp;&nbsp;<input type="checkbox" disabled = "true" name="chckIsPlus" id = "chckIsPlus" value="1"></td>
							</tr>
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
					</table>
				</td>
			</table>
		</td>
	</tr>
</table>

</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="PromoDetails1">
	<tr>
		<td valign="top" class="bgF9F8F7">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td width="50%">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Buy-in Requirements &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></div></td>
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>Selection:</strong></div></td>
							<td>&nbsp;&nbsp;

							<select name="BuyinSelection"  disabled = "true" class="txtfield" id="BuyinSelection" style="visibility: visible">
							<option value="0">[SELECT TYPE]</option>
							<?php if($BuyinSelection->num_rows): ?>
									<?php while ($row = $BuyinSelection->fetch_object()): ?>
										<?php print_r($row); ?>
										<option value="<?php echo $row->ID; ?>"><?php echo $row->Name; ?></option>
									<?php endwhile; ?>
								<?php endif; ?>
							</select></td>
						</tr>
						<tr>
							<td height="20"><div align="right"  class="txtpallete"><strong>Item Code: </strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtBRItemCode" type="text" disabled = "true" class="txtfield" id="txtBRItemCode" size="20" style="width: 75px"  value = "" >
							<input name="txtBRItemDesc" type="text" disabled = "true" readonly = "true" class="txtfield" id="txtBRItemDesc" size="20" style="width: 195px" value = "" >
							</td>
						</tr>
						<tr>
							<td height="20"><div align="right"  class="txtpallete"><strong>Product Line: </strong></div></td>
							<td height="20">&nbsp;&nbsp;
								<select name="ProdLine"  disabled = "true" class="txtfield" id="ProdLine" style="visibility: visible">
								<option value="0">[SELECT TYPE]</option>
								<?php if($ProductLineSelection->num_rows): ?>
									<?php while ($row = $ProductLineSelection->fetch_object()): ?>
										<?php print_r($row); ?>
										<option value="<?php echo $row->ID; ?>"><?php echo $row->Name; ?></option>
									<?php endwhile; ?>
								<?php endif; ?>
								</select>
							</td>
						</tr>

						<tr>
							<td height="20"><div align="right"  class="txtpallete"><strong>Promo Code: </strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtPromoCodePromo" disabled = "disabled" type="text"  class="txtfield" id="txtPromoCodePromo" onkeypress ="get_promo(1);" size="20" style="width: 75px"  value = "" >
							</td>
						</tr>
						<tr>
							<td height="20"><div align="right"  class="txtpallete"><strong>Criteria: </strong></div></td>
							<td height="20">&nbsp;&nbsp;
								<select name="buyincriteria"  disabled = "true" class="txtfield" id="buyincriteria" style="visibility: visible">
									<option value="0">[SELECT TYPE]</option>
									<option value='2'> Amount </option>
									<option value='1'> Quantity</option>
								</select>
							</td>
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Minimum Value:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtBRMinVal" onkeyup = "javascript:RemoveInvalidChars(txtBRMinVal);" type="text" disabled = "true" class="txtfield" id="txtBRMinVal" size="20" style="width: 50px" value = "" >
							</td>
						</tr>

						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtBuyinSetStartDate" type="text" disabled = "true" class="txtfield" id="txtBuyinSetStartDate" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today; ?>" >
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtBuyinSetEndDate" type="text" disabled = "true" class="txtfield" id="txtBuyinSetEndDate" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today;?>">
							<i>(e.g. MM/DD/YYYY)</i>
							&nbsp;&nbsp;<input class="btn" id = "btnAdd_buyin" disabled = "true" onclick = "return add(1)" type = "submit" value = "Add"  name = "">
							</td>


						</tr>
					</table>
				</td>

				<td width="50%">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Entitlement &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></div></td>
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Item:</strong></div></td>
							<td height="20">&nbsp;&nbsp;<input name="txtEPromoCode" value = ""  disabled = "true" type="text" class="txtfield" id="txtEPromoCode" style="width: 75px;" maxlength="60" />&nbsp;
							<input name="txtEProdDesc" value = "" readonly = "true" disabled = "true" type="text" class="txtfield" id="txtEProdDesc" style="width: 195px;" maxlength="60" />&nbsp;</td>
						</tr>
						<tr>
							<td height="20"><div align="right"  class="txtpallete"><strong>Criteria: </strong></div></td>
							<td height="20">&nbsp;
								<select name="EntitleCriteria" disabled = "true" class="txtfield" id="EntitleCriteria" style="visibility: visible">
								<option value="0">[SELECT TYPE]</option>
										<option value="2">Price</option>
										<option value="1">Quantity</option>
								</select>

							</td></tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Minimum Value:</strong></div></td>
							<td height="20">&nbsp;
							<input name="txtEMinVal" onkeyup = "javascript:RemoveInvalidChars(txtEMinVal);" type="text" class="txtfield" id="txtEMinVal" disabled = "true" size="20" style="width: 50px" value = "" >
							&nbsp;&nbsp;<input class="btn" id = "btnAdd_ent"  onclick = "return add(2)" type = "submit" value = "Add" disabled = "true" name = "add_save"></td>

						</tr>

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
					</table>
				</td>
			</table>
		</td>
	</tr>
</table>
<br>
<div id = "ListPromoDetails1">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top" width="45%">
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin"></td>
					<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement</div></td>
					<td class="tabmin3">&nbsp;</td>
				</tr>
			</table>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td>

						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
						<tr>
							<td class="bgF9F8F7">
								<div class="scroll_150">
									<table width="100%" id = "buyinrequirements"  border="0" cellpadding="0" cellspacing="1">

										<div id ="TBLbuyinrequirements">
										<tr align="center" id = "TBIEntitlement">
												<td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td>
												<td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td>
												<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td>
												<td width="5%" height="20"  class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>
												<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td>
												<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td>
												<td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Action</div></td>
										</tr>
										</div>
									</table>
								</div>
							</td>
						</tr>
						</table>
				</tr>
			</table>
		</td>
		<td width="1%">&nbsp;</td>
		<td width="47%" valign="top">
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td class="tabmin"></td>
				<td class="tabmin2">
					<div align="left" class="padl5 txtredbold">Entitlement
					</div></td>
				<td class="tabmin3">&nbsp;</td>
			</tr>
			</table>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">

			<tr>
				<td valign="top" class="bgF9F8F7">
				</td>
			</tr>
			<tr>
				<td class="bgF9F8F7">
					<div class="scroll_150">
						<table width="100%" id = "tblentitlement"  border="0" cellpadding="0" cellspacing="1">
							<tr align="center">
										<td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td>
										<td width="17%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td>
										<td width="7%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Criteria</div></td>
										<td width="5%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">End Date</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Action</div></td>

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
<table width="100%" align="left"  border="0" cellpadding="0" cellspacing="0">
			<tr>

				<td align="center">

 						<input name='btnSave' id = "btnSave" type='submit' class='btn' value='Save' onclick='return ConfirmSave(1);' disabled = "true">
						<input name='btnCancel' id = "btnCancel" type='submit' class='btn' value='Cancel' onclick='return ConfirmCancel();'>

				</td>
			</tr>
</table>
</div>

</form>
<?//DatePicker For Buyin Requiremnt ?>
<script type="text/javascript">

    function datePicker(field){
        $(field).datepicker({
            changeMonth :   true,
            changeYear  :   true
        });
    }

    datePicker("[name=txtStartDate]");
    datePicker("[name=txtEndDate]");
    datePicker("[name=txtBuyinSetStartDate]");
    datePicker("[name=txtBuyinSetEndDate]");

</script>
</html>