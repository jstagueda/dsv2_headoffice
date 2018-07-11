<?php
	//LOGIC PART ON THIS MODULE
	//include IN_PATH.DS."scLoyaltyPromo.php";
	global $database;
	$today = date("m/d/Y");
	$database->execute("DELETE FROM tpiloyaltybuyinrequirement where session = ".$session->emp_id);

	/*DropDown Query*/
	$brandQry = "select * from value where FieldID = 8";
	$BrandDropDown = $database->execute($brandQry);

	$ProductLineQry = "SELECT ID, CODE, NAME ProductLine FROM product WHERE ProductLevelID = 2";
	$ProductLineDropDown = $database->execute($ProductLineQry);

	$pmgQry = "SELECT * FROM tpi_pmg WHERE ID < 4";
	$DropDownPmg = $database->execute($pmgQry);

	$formQry = "select * from value where FieldID = 9";
	$DropDownform = $database->execute($formQry);

	$styleQry = "select ID, Name Style from value where FieldID = 14";
	$DropDownStyle = $database->execute($styleQry);

?>
<!-- <meta http-equiv="refresh" content=""> -->
<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/jquery-ui-1.8.5.custom.css" title="win2k-cold-1" />
<!-- product list -->
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<?php //AJAX LOYALTY // ?>
<script type="text/javascript" src="js/jsloyalty.js"></script>
<form id = "frmCreateLoyalty" name="frmCreateOverlaySet" method="post" action="index.php?pageid=170.1">

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
		<td width="70%">&nbsp;<a class="txtgreenbold13">Create Loyalty:</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>

<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		</tr>
		</table>
	</td>
</tr>
</table>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin">
	<input type="hidden" id="hEntIndex" name="hEntIndex" value="">
	<input type="hidden" id="hEntCnt" name="hEntCnt" value="">
	</td>
	<td class="tabmin2"><div align="left" class="padl5 txtredbold"></div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="50%">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="25%">&nbsp;</td>
					<td width="75%" align="right">&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" align="right"><div align="right" class="txtpallete"><strong>Promo Code :</strong></div></td>
					<td height="20">&nbsp;&nbsp;<input onkeydown = "return ajaxVal(event)" name="txtPromoCode" type="text" class="txtfield" id="txtPromoCode" style="width: 362px;" maxlength="60" />&nbsp;</td>

			    </tr>
			    <tr>
				    <td height="20"><div align="right" class="txtpallete"><strong>Promo Title:</strong></div></td>
				    <td height="20">&nbsp;&nbsp;<input disabled = "disabled" name="txtPromoTitle" type="text" onkeydown = "return NextField(event)" class="txtfield" id="txtPromoTitle"  style="width: 362px;" maxlength="60">
					</td>
			    </tr>
				<tr>
				  	<td height="20"><div align="right"  class="txtpallete"><strong>Purchase Requirement Type: </strong></div></td>
				  	<td height="20">&nbsp;
					<select name="PReqtType" disabled = "disabled"  style="visibility: visible" class="txtfield" id="PReqtType">
							<option value="1">Single</option>
							<option value="2">Cumulative</option>
					</select>
					</td>
				</tr>
				<tr align = "left">
					<td height="20" align="right" valign = "top"><div align="right" class="txtpallete"><strong>Buy-in Requirement :</strong></div></td>
					<td height = "20">
						<table cellspacing="1" cellpadding="0" border="0" align="center" width="100%">
						<tr><td>&nbsp;</td></tr>
					    <tr>
						    <td height="22" width = "15%"><div  align="right" class="txtpallete"><strong>Start Date :</strong></div></td>
						    <td height="22">&nbsp;
						    	<input name="txtBuyinReqSetStartDate" type="text" class="txtfield" disabled = "disabled" id="txtBuyinReqSetStartDate" size="20" style="width: 150px" readonly="yes"  value ="<?php echo  $today;?>">
                                                        <i>(e.g. MM/DD/YYYY)</i>
                                                    </td>
					    </tr>
						<tr>
						    <td height="22" width = "15%"><div  align="right" class="txtpallete"><strong>End Date :</strong></div></td>
						    <td height="22">&nbsp;
						    	<input name="txtBuyinReqSetEndDate"  type="text" class="txtfield" disabled = "disabled" id="txtBuyinReqSetEndDate" size="20" style="width: 150px" readonly="yes" value ="<?php echo  $today;?>">
                                                        <i>(e.g. MM/DD/YYYY)</i>
                                                    </td>
					    </tr>
						</table>
					</td>
				</tr>
							<?//ENTITELEMT ?>
				<tr align = "left">
					<td height="20" align="right" valign = "top"><div align="right" class="txtpallete"><strong>Entitlement :</strong></div></td>
					<td height = "20">
						<table cellspacing="1" cellpadding="0" border="0" align="center" width="100%">
						<tr><td>&nbsp;</td></tr>
					    <tr>
						    <td height="22" width = "15%"><div  align="right" class="txtpallete"><strong>Start Date :</strong></div></td>
						    <td height="22">&nbsp;
						    	<input name="txtEntitlemntSetStartDate" type="text" class="txtfield" disabled = "disabled" id="txtEntitlemntSetStartDate" size="20" style="width: 150px" readonly="yes" value ="<?php echo  $today;?>">
                                                        <i>(e.g. MM/DD/YYYY)</i>
                                                    </td>
					    </tr>
						<tr>
						    <td height="22" width = "15%"><div  align="right" class="txtpallete"><strong>End Date :</strong></div></td>
						    <td height="22">&nbsp;
						    	<input name="txtSetentitlementEndDate"  type="text" class="txtfield" disabled = "disabled" id="txtSetentitlementEndDate" size="20" style="width: 150px" readonly="yes" value ="<?php echo  $today;?>">
                                                        <i>(e.g. MM/DD/YYYY)</i>
                                                    </td>
					    </tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="20">&nbsp;</td>
				</tr>
				</table>
			</td>
			<?//BUYIN ?>
			<td valign="top" class="bgF9F8F7">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr><td width = "15%">&nbsp;</td><td width = "85%">&nbsp;</td></tr>
				<tr>
				<td valign="top" class="bgF9F8F7" width="100%">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr><td width = "15%">&nbsp;</td><td width = "85%">&nbsp;</td></tr>
					<tr>
						<td height="20" width = "15%"><div align="right"  class="txtpallete"><strong>Max Availment:</strong></div></td>
					<tr>
						<td height="20" align="right"><div align="right" class="txtpallete"><strong>Non GSU:</strong></div></td>
						<td height="20">&nbsp;&nbsp;<input name="txtNonGSU" value = "" 	disabled = "true" onkeyup="javascript:RemoveInvalidChars(txtNonGSU);" type="text" class="txtfield" id="txtNonGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>
					</tr>
					<tr>
						<td height="20" align="right"><div align="right" class="txtpallete"><strong>Direct GSU:</strong></div></td>
						<td height="20">&nbsp;&nbsp;<input name="txtDirectGSU" value = ""  disabled = "true" onkeyup="javascript:RemoveInvalidChars(txtDirectGSU);" type="text" class="txtfield" id="txtDirectGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>
					</tr>
					<tr>
						<td height="20" align="right"><div align="right" class="txtpallete"><strong>Indirect GSU:</strong></div></td>
						<td height="20">&nbsp;&nbsp;<input name="txtIndirectGSU" value = ""  disabled = "true" onkeyup="javascript:RemoveInvalidChars(txtIndirectGSU);"  type="text" class="txtfield" id="txtIndirectGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>
					</tr>
					</tr>
					<tr>
						<td colspan="2" height="20">&nbsp;</td>
					</tr>
				</table>
				</td>
				</tr>

				</table>
			</td>
	</tr>
</table>
            </td></tr></table>
<br>
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
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td height="20" width="30%">&nbsp;</td>
							<td height="20" width="70%" align="right">&nbsp;</td>
						</tr>

					    <tr>
						    <td height="21"><div  align="right" class="txtpallete"><strong>Selection :</strong></div></td>
						    <td height="21">&nbsp;
						    	<select name="sSection" id = "sSection" disabled = "disabled"  style="width:150px; height:20px; visibility: visible;" class="txtfield" >
									<option value="0">[SELECT HERE]</option>
									<?php $selection = $database->execute("SELECT * FROM loyaltyselection");
										if($selection->num_rows):
											while($row = $selection->fetch_object()):
												echo "<option value='$row->ID'>$row->Name</option>";
											endwhile;
										endif;
									?>
                                </select>
							</td>
						</tr>

						<tr>
						    <td height="21"><div  align="right" class="txtpallete"><strong>Brand :</strong></div></td>
						    <td height="21">&nbsp;
						    	<select name="sBrand" id = "sBrand" style="width:150px; height:20px; visibility: visible;" class="txtfield" disabled = "true">
									<option value="0">[SELECT HERE]</option>
									<?php
										if($BrandDropDown->num_rows):
											while($row = $BrandDropDown->fetch_object()): ?>
										<option value='<?php echo $row->ID; ?>'><?php echo $row->Name; ?></option>
									<?php	endwhile;
										endif; ?>
								</select>
							</td>
						</tr>
						<tr>
						    <td height="21"><div  align="right" class="txtpallete"><strong>Form :</strong></div></td>
						    <td height="21">&nbsp;
						    	<select name="sForm" id = "sForm" style="width:150px; height:20px; visibility: visible;" class="txtfield" disabled = "true">
								   <option value="0">[SELECT HERE]</option>
								   <?php
									if($DropDownform->num_rows):
											while($row = $DropDownform->fetch_object()):?>
												<option value='<?php echo $row->ID; ?>'><?php echo $row->Name; ?></option>
								   <?php 	endwhile;
									endif; ?>
								</select>
							</td>
						</tr>
						<tr>
						    <td height="21"><div  align="right" class="txtpallete"><strong>Style :</strong></div></td>
						    <td height="21">&nbsp;
						    	<select name="sStyle" id = "sStyle" style="width:150px; height:20px;visibility: visible;" class="txtfield" disabled= "true">
									<option value="0">[SELECT HERE]</option>
								    <?php if($DropDownStyle->num_rows):
										  while($row = $DropDownStyle->fetch_object()): ?>
											 <option value='<?php echo $row->ID; ?>'><?php echo $row->Style; ?></option>
									<?php endwhile;
										  endif; ?>
								</select>
							</td>
						</tr>
						<?php /*<tr>
						    <td height="21"><div  align="right" class="txtpallete"><strong>PMG :</strong></div></td>
						    <td height="21">&nbsp;
						    	<select name="sPMG" id = "sPMG" style="width:150px; height:20px;" class="txtfield" disabled = "true">
									<option value="0">[SELECT HERE]</option>
									<?php if($DropDownPmg->num_rows):
										  while($row = $DropDownPmg->fetch_object()): ?>
											 <option value='<?php echo $row->ID; ?>'><?php echo $row->Style; ?></option>
									<?php endwhile;
										  endif; ?>
								</select>
							</td>
						</tr> */ ?>
						<tr>
						    <td height="21"><div  align="right" class="txtpallete"><strong>Product Line :</strong></div></td>
						    <td height="21">&nbsp;
						    	<select name="sProdLine" id = "cProdLine" style="width:150px; height:20px;visibility: visible;" class="txtfield" disabled = "true" >
									<option value="0">[SELECT HERE]</option>
									<?php if($ProductLineDropDown->num_rows):
										  while($row = $ProductLineDropDown->fetch_object()): ?>
											 <option value='<?php echo $row->ID; ?>'><?php echo $row->ProductLine; ?></option>
									<?php endwhile;
										  endif; ?>
								</select>
							</td>
						</tr>
					    <tr>

						    <td height="22"><div align="right" class="txtpallete"><strong>Product Code :</strong></div></td>
						    <td height="22">&nbsp;
						    	<input name="txtProdCode" type="text"  class="txtfield" id="txtProdCode" style="width: 150px;" value="" disabled = "true" />
						    	<span id="indicatorProdCode" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>
						    	<div id="indicatorProdCode_Choices" class="autocomplete" style="display:none"></div>
						    	<br />&nbsp;
						    	<input name="txtProdCodeDescription"   type="text" class="txtfield" id="txtProdCodeDescription" style="width: 250px;" value="" readonly="yes" />
						    	<input name="txtProdID"   type="hidden" class="txtfield" id="txtProdID" style="width: 250px;" value="" readonly="yes" />

						    </td>
						</tr>
					    <tr>
						    <td height="22"><div align="right" class="txtpallete"><strong>Criteria :</strong></div></td>
						    <td height="22">&nbsp;
							<select name="sCriteria"  class="txtfield" id="sCriteria" style="width: 150px; visibility: visible" disabled = "disabled" >
									<option value="0" Selected>[SELECTE HERE]</option>
									<option value="1">Quantity</option>
									<option value="2">Amount</option>
							</select>
						    </td>
					    </tr>
					    <tr>
						    <td height="22"><div  align="right" class="txtpallete"><strong>Minimum Value :</strong></div></td>
						    <td height="22">&nbsp;&nbsp;<input  id = "txtMinimum" name="txtMinimum" onkeyup="javascript:RemoveInvalidChars(txtMinimum)" type="text" value="" disabled = "disabled" class="txtfield" style="width: 150px" ></td>
					    </tr>
						<tr>
						    <td height="22"><div  align="right" class="txtpallete"><strong>Points :</strong></div></td>
						    <td height="22">&nbsp;&nbsp;<input  name="txtPointsBuyin" id = "txtPoints" onkeyup="javascript:RemoveInvalidChars(txtPoints)" type="text" value="" class="txtfield" style="width: 150px"></td>
					    </tr>
						<tr>
							<td colspan="2" height="20">&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<br>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right">
				<?php
					if(isset($_POST['btnAdd'])){
						echo $addbtn;
					}else{
						echo "<input name='btnAdd' id = 'btnAdd' type='submit' class='btn' value='Add' onclick='return confirmAdd();'>";
					}
				?>
			</td>
		</tr>
		</table>
		<br>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td>
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
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="bgF9F8F7">
						<div class="scroll_150">
						<table width="100%" id = "buyinrequirements"  border="0" cellpadding="0" cellspacing="1">
								<?//ajax?>
						</table>
						</div>
					</td>
				</tr>
				</table>
			</td>
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
				<input name="entitlementcnt" type="hidden" class="txtfield" id="entitlementcnt" id = "entitlementcnt" value="1" />
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
				<div class="scroll_400">
						<table border="0" id="dynamicTable" class="txtdarkgreenbold10" cellspacing="0">
							<tr align="center">
								<td width="8%"  height="20" class="borderBR"><div align="center" class="txtpallete">Line No.</div></td>
								<td width="12%" height="20" class="borderBR"><div align="left"   class="txtpallete">Item Code</div></td>
								<td width="31%" height="20" class="borderBR"><div align="left"   class="txtpallete">Item Description</div></td>
								<td width="12%" height="20" class="txtpallete borderBR"><div align="center">Points</div></td>
								<td width="10%" height="20" class="borderBR"><div align="center" class="txtpallete">Action</div></td>

								<tr align="center">
									<td width="8%" height="20" class="borderBR"><div align="center" id = "1" class="padl5">1</div></td>
									<td width="12%" height="20" class="borderBR">
										<div align="left" class="padl5">
												<input name="txtEProdCode1" disabled = "disabled" type="text" class="txtfield" id="txtEProdCode1" style="width: 80%;" value=""/>
												<span id="indicatorE1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>
												<div id="prod_choicesE1" class="autocomplete" style="display:none"></div>
												<input name="hEProdID[1]" type="hidden" id="hEProdID1" value="" />
										</div>
									</td>
									<td width="31%" height="20" class="borderBR">
										<div align="left" class="padl5">
												<input name="txtEProdDesc1" type="text" class="txtfield" id="txtEProdDesc1" readonly = "yes"style="width: 95%;"  />
										</div>
									</td>
									<td width="12%" height="20" class="borderBR">
										<div align="center">
											<input name="txtPoints1" onkeyup="javascript:RemoveInvalidChars(txtPoints1)" disabled = "disabled" type="text" class="txtfield" id="txtPoints1"  value="" onkeypress="return addRow(event, 1);" style="width: 50%; text-align:right"/>
										</div>
									</td>
									<td width="20%" height="20" class="borderBR">
										<div align="center">
												<!--input name="btnRemove1" type="button" class="btn remove" value="Remove" onclick="removeRow(1);"-->
										</div>
									</td>
								</tr>
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
     <td align="right">
		<input name='btnSave' disabled = "disabled" id = 'btnSave' type='submit' class='btn' value='Save' onclick='return confirmSave();'>
     </td>
	<td align="left">
		<input name='btnCancel' id = "btnCancel" type='submit' class='btn' value='Cancel' onclick='return Cancel();'>

 </td>
</tr>
</table>
</td>
</tr></table>
</form>
<script type="text/javascript">

    function datePicker(field){
        $(field).datepicker({
            changeMonth :   true,
            changeYear  :   true
        });
    }

    datePicker("[name=txtBuyinReqSetStartDate]");
    datePicker("[name=txtBuyinReqSetEndDate]");
    datePicker("[name=txtEntitlemntSetStartDate]");
    datePicker("[name=txtSetentitlementEndDate]");

</script>