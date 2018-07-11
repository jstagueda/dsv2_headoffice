<?php
	//This Design Used For Marketing Brands Incentives
	include IN_PATH.DS."scCreateIncentives.php";
	$result = $database->execute("SELECT ID FROM tpiincentivesbuyintmp where sessionID =".$session->emp_id);
	if($result->num_rows):
		while ($delete = $result->fetch_object()):
			$database->execute("DELETE FROM tpiincentivesentltmp where IBuyinID =".$delete->ID);
			$database->execute("DELETE FROM tpispecialcriteriatmp where IBuyinID =".$delete->ID);
		endwhile;
	endif;
	$database->execute("DELETE FROM tpiincentivesbuyintmp where sessionID =".$session->emp_id);
	$database->execute("DELETE FROM tpispecialcriteriatmp where sessionID =".$session->emp_id);
?>
<body>
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/jquery-ui-1.8.5.custom.css" title="win2k-cold-1" />
<!-- product list -->
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jQueryIncentives.js"></script>

<form id = "frmCreateLoyalty" name="frmCreateOverlaySet" method="post" action="" style="min-height:610px;">
<br />
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%">&nbsp;<a class="txtgreenbold13">Create Incentives:</a></td>
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
						<td height="20"><div align="right"  class="txtpallete"><strong>Incentive Type: </strong></div></td>
						<td height="20">&nbsp;

							<select name="inctvtype" class="txtfield" disabled = "true" id="inctvtype" style="visibility: visible">
										<option value="0">[SELECT TYPE]</option>
								<?php if($IncentiveType->num_rows): ?>
									<?php while ($row = $IncentiveType->fetch_object()): ?>
										<?php print_r($row); ?>
										<option value="<?php echo $row->ID; ?>"><?php echo $row->Name; ?></option>
									<?php endwhile; ?>
								<?php endif; ?>
							</select>
						</td>
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
								<?php if($CriteriaSelection->num_rows): ?>
									<?php while ($row = $CriteriaSelection->fetch_object()): ?>
										<?php print_r($row); ?>
										<option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
									<?php endwhile; ?>
								<?php endif; ?>
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
							</td>
						</tr>
						<tr>&nbsp;</tr><tr>&nbsp;</tr>
						<tr>&nbsp;
							<td height="20"></td><td height="20"></td><td height="20">&nbsp;
								<input class="btn" id = "btnAdd" onclick = "return btn_add()" type = "submit" value = "Add" disabled = "true" name = "add_save"></td>
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
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="PromoDetails2" style = "display:none">
	<tr>
		<td valign="top" class="bgF9F8F7">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td width="35%">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="30%">&nbsp;</td>
							<td width="70%" align="right">&nbsp;</td>
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Buyin-in Requirements &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></div></td>
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>Selection:</strong></div></td>
							<td>&nbsp;&nbsp;

							<select name="BuyinSelection1"  class="txtfield" id="BuyinSelection1" style="visibility: visible">
							<option value="0">[SELECT TYPE]</option>
							<?php if($BuyinSelection1->num_rows): ?>
									<?php while ($row = $BuyinSelection1->fetch_object()): ?>
										<?php //print_r($row); ?>
										<option value="<?php echo $row->ID; ?>"><?php echo $row->Name; ?></option>
									<?php endwhile; ?>
								<?php endif; ?>
							</select></td>
						</tr>
						<tr>
							<td height="20"><div align="right"  class="txtpallete"><strong>Item Code: </strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtBRItemCode1" type="text" disabled = "true" class="txtfield" id="txtBRItemCode1" size="20" style="width: 75px"  value = "" >
							<input name="txtBRItemDesc1" type="text" disabled = "true" readonly = "true" class="txtfield1" id="txtBRItemDesc1" size="20" style="width: 195px" value = "" >
							</td>
						</tr>
						<tr>
							<td height="20"><div align="right"  class="txtpallete"><strong>Product Line: </strong></div></td>
							<td height="20">&nbsp;&nbsp;
								<select name="ProdLine1"  disabled = "true" class="txtfield" id="ProdLine1" style="visibility: visible">
								<option value="0">[SELECT TYPE]</option>
								<?php if($ProductLineSelection1->num_rows): ?>
									<?php while ($row = $ProductLineSelection1->fetch_object()): ?>
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
							<input name="txtPromoCodePromo1" disabled = "disabled" type="text"  class="txtfield" id="txtPromoCodePromo1" onkeypress ="get_promo(2);" size="20" style="width: 75px"  value = "" >
							</td>
						</tr>

						<tr>
							<td height="20"><div align="right"  class="txtpallete"><strong>Criteria: </strong></div></td>
							<td height="20">&nbsp;&nbsp;
								<select name="buyincriteria1"   class="txtfield" id="buyincriteria1" style="visibility: visible">
								<option value="0">[SELECT TYPE]</option>
								<?php if($CriteriaSelection2->num_rows): ?>
									<?php while ($row = $CriteriaSelection2->fetch_object()): ?>
										<?php print_r($row); ?>
										<option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
									<?php endwhile; ?>
								<?php endif; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Minimum Value:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtBRMinVal1" onkeyup = "javascript:RemoveInvalidChars(txtBRMinVal1);" type="text" class="txtfield" id="txtBRMinVal1" size="20" style="width: 50px" value = "" >
							</td>
						</tr>

						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtBuyinSetStartDate1" type="text"  class="txtfield" id="txtBuyinSetStartDate1" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today; ?>" >
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtBuyinSetEndDate1" type="text" class="txtfield" id="txtBuyinSetEndDate1" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today;?>">
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
					</table>
				</td>
				<td width="23%">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="30%">&nbsp;</td>
							<td width="70%" align="right">&nbsp;</td>
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Special Criteria &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></div></td>
						</tr>

						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Activate:</strong></div></td>
								<td>&nbsp;&nbsp;<input type="checkbox" name="activate" id = "activate" value="1"></td>
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>No. of Weeks Active:</strong></div></td>
							<td>&nbsp;&nbsp;

							From: <select name="noofwiks"  disabled = "true" class="txtfield" id="noofwiks" style = "width:20%; visibility: visible;">
							<option value="0">0</option>
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							</select>
							To: <select name="noofwiksto"  disabled = "true" class="txtfield" id="noofwiksto" style = "width:20%; visibility: visible;">
									<option value="0">0</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
								</select>
							</td>
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtSPStartDate1" type="text"  disabled = "true" class="txtfield" id="txtSPStartDate1" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today; ?>" >
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtSPEndDate1" type="text" disabled = "true" class="txtfield" id="txtSPEndDate1" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today;?>">
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr style = "display:none" class = "swik2">
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtSPStartDate2" type="text" disabled = "true" class="txtfield" id="txtSPStartDate2" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today; ?>" >
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr style = "display:none" class = "ewik2">
							<td height="20"><div align="right" disabled = "true" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtSPEndDate2" type="text" class="txtfield" id="txtSPEndDate2" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today;?>">
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr style = "display:none" class = "swik3">
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtSPStartDate3" disabled = "true" type="text" class="txtfield" id="txtSPStartDate3" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today; ?>" >
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr style = "display:none" class = "ewik3">
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtSPEndDate3"  disabled = "true" type="text" class="txtfield" id="txtSPEndDate3" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today;?>">
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr style = "display:none" class = "swik4">
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtSPStartDate4" disabled = "true" type="text" class="txtfield" id="txtSPStartDate4" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today; ?>" >
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr style = "display:none" class = "ewik4">
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtSPEndDate4" disabled = "true" type="text" class="txtfield" id="txtSPEndDate4" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today;?>">
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
							<td height="20"><div align="right" class="txtpallete"><strong>Minimum Value:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="SminValue" disabled = "true" type="text" class="txtfield" id="SminValue" size="20" style="width: 150px" value = "0" >
							</td>
						<tr>

						</tr>
					</table>
				</td>
				<td width="42%">
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<div align="left" class=" txtpallete padl5">Criteria:
												<select name="sType"  class="txtfield" id="sType" style="width: 20%" >
													<option value="2"> SELECTION</option>
													<option value="1"> SET </option>
												</select>
												&nbsp;&nbsp;&nbsp;Selection No:&nbsp;&nbsp;&nbsp;<input name="Sselection" onkeyup = "javascript:RemoveInvalidChars(Sselection);" type="text" class="txtfield" id="Sselection" size="20" style="width: 5%" value = "" >
					</div>
				</tr>
				<tr>
					<td class="tabmin"></td>
					<td class="tabmin2">
						<div align="left" class="padl5 txtredbold">Multi Entitlement
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
							<table width="100%" id = "multirow"  border="0" cellpadding="0" cellspacing="1">
								<tr align="center">
											<input type = "hidden" value = '2' id = "multixcounter" name = "multixcounter">
											<td width="5%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">No.</div></td>
											<td width="13%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">ItemCode</div></td>
											<td width="35%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Product Name</div></td>
											<td width="17%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Criteria</div></td>
											<td width="15%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Minimum</div></td>
											<td width="15%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Action</div></td>
								</tr>
								<tr align="center">
											<td height="20"  class="borderBR"><div  align="center" class="padl5">1</div></td>
											<td height="20"  class= "borderBR"><div align="center" class="padl5"><input type="text" class="txtfield" id="txtEPromoCode1" onkeypress = "keyfunction(1,event)"name = "txtEPromoCode1" size="20" style="width: 85%"  value = "" ></div></td>
											<td height="20"  class= "borderBR"><div align="center" class="padl5"><input type="text" class="txtfield" id="txtEProdDesc1"  name = "txtEProdDesc1" size="20" style="width: 92%" readonly="yes" value = "" ></div></td>
											<td height="20"  class= "borderBR"><div align="center" class="padr5">
												<select name="buyinEcriteria1"  class="txtfield" id="buyinEcriteria1" style="width: 100%" >
												<option value="0">[SELECT TYPE]</option>
													<option value="2">Price</option>
													<option value="1">Quantity</option>
												</select>
											</div></td>
											<td height="20" class= "borderBR"><div align="right" class="padr5"><input name="txtEMinVal1" onkeyup = "javascript:RemoveInvalidChars(txtEMinVal1)" id = "txtEMinVal1" type="text" class="txtfield" id="" size="20" style="width: 85%" onkeypress="return addRow(event, 1)"></div></td>
											<td height="20" class= "borderBR"><div align="right" class="padr5"><input type="button" onclick="removeRow(1);" value="Remove" name="btnRemove1" class ="btn"></div></td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
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
	<tr>
	<td align = "right" class="bgF9F8F7">
				<input type = "submit" value='ADD' id= "MBIncentiveAdd" onclick='return MBI_ADD();' class="btn">
	</td>
	</tr>
</table>
<br />
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

						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
						<tr>
							<td class="bgF9F8F7">
								<div class="scroll_150">
									<table width="100%" id = "buyinrequirements"  border="0" cellpadding="0" cellspacing="0">

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
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">

			<tr>
				<td valign="top" class="bgF9F8F7">
				</td>
			</tr>
			<tr>
				<td class="bgF9F8F7">
					<div class="scroll_150">
						<table width="100%" id = "tblentitlement"  border="0" cellpadding="0" cellspacing="0">
							<tr align="center">
										<td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td>
										<td width="17%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td>
										<td width="7%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Criteria</div></td>
										<td width="5%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">End Date</div></td>

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


<div id = "ListPromoDetails2" style = "display:none">
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
									<table width="100%" id = "buyinrequirements"  border="0" cellpadding="0" cellspacing="0">

										<div id ="TBLbuyinrequirements1">
										<tr align="center" id = "TBIEntitlement1">
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
			</td>
			</table>
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
						<table width="100%" id = "tblentitlement1"  border="0" cellpadding="0" cellspacing="0">
						<div id ="TBLentitlement22">
							<tr align="center" id = "tblentitlement2">
										<td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td>
										<td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td>
										<td width="5%" height="20"  class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td>
										<td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Action</div></td>
							</tr>
						</div>
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

				<td align="left">
 						<input name='btnSave' id = "btnsave1" type='submit' class='btn' value='Save' onclick='return ConfirmSave(2);' disabled = "true">
						<input name='btnCancel' type='submit' class='btn' value='Cancel' onclick='return ConfirmCancel();'>
				</td>
			</tr>
</table>
<br />
</td></tr>
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
    datePicker("[name=txtBuyinSetStartDate1]");
    datePicker("[name=txtBuyinSetEndDate1]");
    datePicker("[name=txtSPStartDate1]");
    datePicker("[name=txtSPEndDate1]");
    datePicker("[name=txtSPStartDate2]");
    datePicker("[name=txtSPEndDate2]");
    datePicker("[name=txtSPStartDate3]");
    datePicker("[name=txtSPEndDate3]");
    datePicker("[name=txtSPStartDate4]");
    datePicker("[name=txtSPEndDate4]");

</script>
</html>