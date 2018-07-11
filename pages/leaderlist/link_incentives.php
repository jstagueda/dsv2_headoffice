<?php
	//This Design Used For Marketing Brands Incentives
	require_once "../../initialize.php";
	include IN_PATH.DS."scCreateIncentives.php";

?>
<body>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="../../css/jquery-ui-1.8.5.custom.css" title="win2k-cold-1" />
<!-- product list -->
<script language="javascript" src="../../js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="../../js/thickbox-compressed.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/blitzer/jquery-ui-1.8.2.custom.css">
<link rel="stylesheet" type="text/css" href="../../css/thickbox.css">
<!--script type="text/javascript" src="../../js/jQueryIncentives.js"></script-->
<script type="text/javascript" src="../../js/jQueryLinkIncentives.js"></script>
<script>
function SingleDelete(DeleteID)
{
	if(confirm("Are you sure want to delete this line?")==false){
		return false;
	}else{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:	{xDeletedID: DeleteID},
			url: '../../includes/ajaxincentivesBuyinandEntitlement.php?xDelete',
			success: function(response){
				if(response.result == 1){
					alert("Delete Successful");
					window.location.assign("link_incentives.php?ID=<?php echo $_GET['ID']?>");
				}else{
					alert('error');
					return false;
				}
			}
		})
		return false;
	}
}


</script>
<form id = "frmCreateLoyalty" name="frmCreateOverlaySet" method="post" action="">
<br />
<div id = "for_autocomplete">
</div>
<div id = "autocomplete_counter">
</div>

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
<input type = "hidden" value = "<?php echo $_GET['ID']; ?>" id = "xID">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td>
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
							<td height="20">&nbsp;&nbsp;<input name="txtPromoCode" readonly = "yes" onblur = "validatePromoCode()" value = "<?php echo $Code;?>" type="text" class="txtfield" id="txtPromoCode" style="width: 150px;" maxlength="60" />&nbsp;</td>
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>Promo Description:</strong></div></td>
							<td height="20">&nbsp;&nbsp;<input name="txtPromoDesc" value = "<?php echo $Description?>" type="text" class="txtfield" id="txtPromoDesc"  style="width: 150px;" maxlength="60"></td>
						</tr>
						<tr>
						<td height="20"><div align="right"  class="txtpallete"><strong>Incentive Type: </strong></div></td>
						<td height="20">&nbsp;

							<select name="inctvtype" class="txtfield" readonly = "yes" id="inctvtype">
										<option value="<?php echo $IncentiveTypeID; ?>"><?php echo $IncentiveType; ?></option>
							</select>
						</td>
						</tr>
						<tr>
						<td height="20"><div align="right"  class="txtpallete"><strong>Mechanics Type: </strong></div></td>
						<td height="20">&nbsp;
							<select name="MechType" class="txtfield" readonly = "yes" id="MechType">
										<option value="<?php echo $MechanicsTypeID?>"><?php echo $MechanicsType;?></option>
							</select>
						</td>
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">&nbsp;
							<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" style="width: 150px"  value = "<?php echo $StartDate; ?>" >
							<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">&nbsp;
							<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" readonly = "true" size="20" style="width: 150px"  value = "<?php echo $EndDate; ?>">
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
								<select name="NoCPI" style="width: 70px" readonly = "yes" class="txtfield" id="NoCPI">
									<?php if ($NetOFCPI==0): ?>
										<option value="0">No</option>
										<option value="1">Yes</option>
									<?php else :?>
										<option value="1">Yes</option>
										<option value="0">No</option>
									<?php endif; ?>
								</select>
							</td>
						</tr>

						<tr>
							<td height="20"><div align="right"  class="txtpallete"><strong>Max Availment:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></div></td>
							<tr>
								<td height="20" align="right"><div align="right" class="txtpallete"><strong>Non GSU:</strong></div></td>
								<td height="20">&nbsp;&nbsp;<input name="txtNonGSU" value = "<?php if($NonGSU > 0){echo $NonGSU;} else{echo "";}?>" type="text" class="txtfield" id="txtNonGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>
							</tr>
							<tr>
								<td height="20" align="right"><div align="right" class="txtpallete"><strong>Direct GSU:</strong></div></td>
								<td height="20">&nbsp;&nbsp;<input name="txtDirectGSU" value = "<?php if($DirectGSU > 0){echo $DirectGSU;} else{echo "";}?>"  type="text" class="txtfield" id="txtDirectGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>
							</tr>
							<tr>
								<td height="20" align="right"><div align="right" class="txtpallete"><strong>Indirect GSU:</strong></div></td>
								<td height="20">&nbsp;&nbsp;<input name="txtIndirectGSU" value = "<?php if($InDirectGSU > 0){echo $InDirectGSU;} else{echo "";}?>"  type="text" class="txtfield" id="txtIndirectGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>
							</tr>
							<tr>
								<td height="20" align="right"><div align="right" class="txtpallete"><strong>Is Plus Plan:</strong></div></td>
								<td>&nbsp;&nbsp;<input type="checkbox" <?php if($IsPlusPlan == 1) {echo "checked = 'checked'";} ?> name="chckIsPlus" id = "chckIsPlus" value=""></td>
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

							<select name="BuyinSelection"  disabled = "true" class="txtfield" id="BuyinSelection">
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
								<select name="ProdLine"  disabled = "true" class="txtfield" id="ProdLine">
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
							<td height="20"><div align="right"  class="txtpallete"><strong>Criteria: </strong></div></td>
							<td height="20">&nbsp;&nbsp;
								<select name="buyincriteria"  disabled = "true" class="txtfield" id="buyincriteria">
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
							<input name="txtBRMinVal" type="text" disabled = "true" class="txtfield" id="txtBRMinVal" size="20" style="width: 50px" value = "" >
							</td>
						</tr>

						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtBuyinSetStartDatetxtBuyinSetStartDate" type="text" disabled = "true" class="txtfield" id="txtBuyinSetStartDate" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today; ?>" >
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
								<select name="EntitleCriteria" disabled = "true" class="txtfield" id="EntitleCriteria">
								<option value="0">[SELECT TYPE]</option>
										<option value="1">Price</option>
										<option value="2">Quantity</option>
								</select>
								</select>
							</td></tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Minimum Value:</strong></div></td>
							<td height="20">&nbsp;
							<input name="txtEMinVal" type="text" class="txtfield" id="txtEMinVal" disabled = "true" size="20" style="width: 50px" value = "" >
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
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="PromoDetails2" >
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

							<select name="BuyinSelection1"  class="txtfield" id="BuyinSelection1">
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
								<select name="ProdLine1"  disabled = "true" class="txtfield" id="ProdLine1">
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
							<td height="20"><div align="right"  class="txtpallete"><strong>Criteria: </strong></div></td>
							<td height="20">&nbsp;&nbsp;
								<select name="buyincriteria1"   class="txtfield" id="buyincriteria1">
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
							<input name="txtBRMinVal1" type="text" class="txtfield" id="txtBRMinVal1" size="20" style="width: 50px" value = "" >
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

				</td>
				<td width="42%">
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<div align="left" class=" txtpallete padl5">Criteria:
												<select name=""  class="txtfield" id="" style="width: 20%" >
													<option value="1"> SELECTION</option>
													<option value="2"> SET </option>
												</select>
												&nbsp;&nbsp;&nbsp;Selection No:&nbsp;&nbsp;&nbsp;<input name="" type="text" class="txtfield" id="" size="20" style="width: 5%" ue = "" >
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
											<input type = "hidden" value = 1 id = "multixcounter" name = "multixcounter">
											<td width="5%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">No.</div></td>
											<td width="13%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">ItemCode</div></td>
											<td width="30%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Product Name</div></td>
											<td width="12%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Criteria</div></td>
											<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Minimum</div></td>
											<td width="20%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Special Criteria</div></td>
											<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Action</div></td>
								</tr>
								<tr align="center">
											<td height="20"  class="borderBR"><div  align="center" class="padl5">1</div></td>
											<td height="20"  class= "borderBR"><div align="center" class="padl5"><input type="text" class="txtfield" id="txtEPromoCode1" name = "txtEPromoCode1" size="20" style="width: 85%"  value = "" ></div></td>
											<td height="20"  class= "borderBR"><div align="center" class="padl5"><input type="text" class="txtfield" id="txtEProdDesc1"  name = "txtEProdDesc1" size="20" style="width: 92%" readonly="yes" value = "" ></div></td>
											<td height="20"  class= "borderBR"><div align="center" class="padr5">
												<select name="buyinEcriteria1"  class="txtfield" id="buyinEcriteria1" style="width: 100%" >
												<option value="0">[SELECT TYPE]</option>
													<option value="1"> Quantity</option>
													<option value="2"> Amount </option>
												</select>
											</div></td>
											<td height="20" class= "borderBR"><div align="right" class="padr5"><input name="txtEMinVal1" id = "txtEMinVal1" type="text" class="txtfield" id="" size="20" style="width: 85%" onkeypress="return addRow(event, 1)"></div></td>
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
				<input type = "submit" value='ADD' id= "MBIncentiveAdd" disabled = "disabled" onclick='return MBI_ADD();' class="btn">
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
												<td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">ID</div></td>
												<td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td>
												<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td>
												<td width="5%" height="20"  class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>
												<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td>
												<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td>
												<td width="7%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Action</div></td>

										</tr>
										<?php $BuyinIncentives =  $database->execute("SELECT a.*,b.*, a.ID IncentivesPromoBuyinID, c.Code PromoCode, a.ProductLevelID PLEVEL
																   FROM incentivespromobuyin a LEFT JOIN product b ON a.ProductID = b.ID LEFT JOIN promo c ON c.ID = a.PromoID
																   WHERE a.PromoIncentiveID = ".$_GET['ID']); ?>
										<?php $ctr = 1; ?>
										<?php if($BuyinIncentives->num_rows):
												  while($row = $BuyinIncentives->fetch_object()): ?>
										<tr>
													<input type = "hidden" value = <?php echo $row->IncentivesPromoBuyinID; ?> name = "bbID<?php echo $ctr?>">
											<?php if($row->PLEVEL == 1 || $row->PLEVEL == 2 || $row->PLEVEL == 3){ ?>
													<td width="5%" height="20" class="borderBR"><div align="left" class="padl5"><?php echo $ctr;?></div></td>
													<td width="20%" height="20" class=" borderBR"><div align="left" class="padl5"><?php echo $row->Name; ?></div></td>
											<?php }else{ ?>

													<td width="5%" height="20" class="borderBR"><div align="left" class="padl5"><?php echo $ctr;?></div></td>
													<?php if($row->PLEVEL == 4){$Promo = "Single Line";}else{ $Promo = "Multi Line";}?>
													<td width="20%" height="20" class=" borderBR"><div align="left" class="padl5"><?php echo $Promo." - ".$row->PromoCode; ?></div></td>
										<?php } ?>
										<?php if($row->CriteriaID == 1): ?>
													<td height='20' class='borderBR'><select class='txtfield' id='' style='width: 100%' name = "BBCriteria<?php echo $ctr;?>">
													<option value='1'> Quantity</option>
													<option value='2'> Amount </option>
													</select></td>
										<?php else :?>
													<td height='20' class='borderBR'><select class='txtfield' id='' style='width: 100%' name = "BBCriteria<?php echo $ctr;?>">
													<option value='2'> Amount </option>
													<option value='1'> Quantity</option>
													</select></td>
										<?php endif;?>
										<?php if($row->CriteriaID == 1): ?>
												<td width="5%" height="20"  class=" borderBR"><div align="right" class="padr5"><input type = "text" name = "BBMinVal<?php echo $ctr;?>" class = "txtfield" value = "<?php echo $row->MinQty; ?>" style = "width: 70%;"></div></td>
										<?php else :?>
												<td width="5%" height="20"  class=" borderBR"><div align="right" class="padr5"><input type = "text" class = "txtfield" name = "BBMinVal<?php echo $ctr;?>" value = "<?php echo number_format($row->MinAmt, 2,'.',''); ?>" style = "width: 70%;"></div></td>
										<?php endif;?>
													<td width="10%" height="20" class=" borderBR"><div align="right" class="padr5"><?php echo date("m/d/Y",strtotime($row->StartDate)); ?></div></td>
													<td width="10%" height="20" class=" borderBR"><div align="right" class="padr5"><?php echo date("m/d/Y",strtotime($row->EndDate)); ?></div></td>
													<td width="7%" height="20" class="borderBR"><div align="right" class="padr5"><input type ="submit" name = "btn_delete1" value = "Delete" class = "btn" onclick = "return SingleDelete('<?php echo $row->PromoID; ?>');">



										</tr>
										<?php $ctr++; ?>
											<?php endwhile;
												  endif;?>



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
										<td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">ID.</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">ItemCode</div></td>
										<td width="30%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Description</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td>
										<td width="5%" height="20"  class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td>
							</tr>
							<tr>
								  <?php $result = $database->execute("SELECT ID FROM incentivespromobuyin WHERE PromoIncentiveID = $_GET[ID]");
								  if($result->num_rows){
										$ctr1 = 1;
										while ($row1 = $result->fetch_object()){
											$EntitlementIncentives = $tpiIncentives->tpiSelectEntitlementinIncentives($database, $row1->ID);
											if($EntitlementIncentives->num_rows){
												while($row = $EntitlementIncentives->fetch_object()){ ?>

													<tr>
														<td width="5%" height="20" class="borderBR"><div align="left"   class="padl5"><input name = "eeID<?php echo $ctr1; ?>" type ="hidden" value = "<?php echo $row->eID ?>"><?php echo $ctr1; ?></div></td>
														<td width="15%" height="20" class="borderBR"><div align="left"  class="padl5"><input style = "width: 70%;"type = "text" id ="txtEeentitlement<?php echo $ctr1; ?>" name = "txtEeentitlement<?php echo $ctr1; ?>" onkeypress = "xXxautocompletexXx('<?php echo $ctr1; ?>')" class = "txtfield" value ="<?php echo $row->Code; ?>"</div></td>
														<td width="30%" height="20" class="borderBR"><div align="center"class="padl5"><input style = "width: 90%;"type = "text" id ="txtEEeProdDesc<?php echo $ctr1?>" name = "txtEEeProdDesc<?php echo $ctr1;?>" value = "<?php echo $row->Name; ?>" class = "txtfield" </div></td>
													<?php if($row->CriteriaID == 1): ?>
															<td height='20' class='borderBR'>
															<select name='EECriteria<?php echo $ctr1?>'  class='txtfield' id='' style='width: 100%' >";
																<option value='1'> Quantity</option>
																<option value='2'> Amount </option>
															</select></td>
													<?php else :?>
															<td height='20' class='borderBR'>
															<select name='EECriteria<?php echo $ctr1?>'  class='txtfield' style='width: 100%' >";
																<option value='2'> Amount </option>
																<option value='1'> Quantity</option>
															</select></td>
													<?php endif;?>
													<?php if($row->CriteriaID == 1): ?>
															<td width="5%" height="20"  class=" borderBR"><div align="right" class="padr5"><input type = "text" name = "EEMinval<?php echo $ctr1; ?>" class = "txtfield" value = "<?php echo $row->MinQty; ?>" style = "width: 70%;"></div></td>
													<?php else :?>
															<td width="5%" height="20"  class=" borderBR"><div align="right" class="padr5"><input type = "text" name = "EEMinval<?php echo $ctr1; ?>" class = "txtfield" value = "<?php echo  number_format($row->MinAmt, 2,'.',''); ?>" style = "width: 70%;"></div></td>
													<?php endif;?>
															<td width="10%" height="20" class="borderBR"><div align="right" class="padr5"><?php echo date("m/d/Y",strtotime($row->StartDate)); ?></div></td>
															<td width="10%" height="20" class="borderBR"><div align="right" class="padr5"><?php echo date("m/d/Y",strtotime($row->EndDate)); ?></div></td>
													</tr>
										<?php $ctr1++; ?>
										<?php }
											}
										}
								  } ?>
							</tr>
							<input type = "hidden" value = "<?php echo ($ctr++ - 1); ?>" name = "BuyinCounterXx">
							<input type = "hidden" value = "<?php echo ($ctr1++ - 1); ?>" name = "EntitlementCounterXx">
						</table>
					</div>
				</td>
			</tr>

			</table>
		</td>
	</tr>
</table>
<br>
<table width="100%" align="right"  border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td align="center">
							<input name='btnUpdate2' type='submit' class='btn' value='Update' onclick='return btnBigUpdate()' >
							<input name='btnBigDelete' type='submit' class='btn' value='Delete' onclick='return BigDelete()'>
					</td>
				</tr>
</table>
</div>
<?php/****************************/ ?>
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
									<table width="100%" id = "buyinrequirements"  border="0" cellpadding="0" cellspacing="1">
										<div id ="TBLbuyinrequirements1">
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
						<table width="100%" id = "tblentitlement1"  border="0" cellpadding="0" cellspacing="1">
						<div id ="TBLentitlement22">


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
 						<input name='btnUpdate1' type='submit' class='btn' value='Update' onclick='return btnBigUpdate()' >
						<input name='btnBigDelete' type='submit' class='btn' value='Delete' onclick='return BigDelete()'>
				</td>
			</tr>
</table>
</table>
</div>
</div>
</form>


<form id = "form_specialcriteria">
<div id="test_overlay" style="left: 0pt; top: 0pt; width: 100%; height: 100%; position: fixed; background: none repeat scroll 0% 0% rgb(0, 0, 0); opacity: 0.96; display:none;">
<div id="TBcontent" style="width: 642px; height: 316px; background: none repeat scroll 0% 0% white; margin: 10% auto auto;">
<br /><br />
<div class="padl5 txtredbold" align="left">Special Criteria:</div>
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	<tr>
		<td height="20" align="right"><div align="right" class="txtpallete"><strong>Activate:</strong></div></td>
			<td>&nbsp;&nbsp;<input type="checkbox" name="activate" id = "activate" value="1"></td>
	</tr>
	<tr>
		<input type = "hidden" value = "" name = "promo_id" id = "promo_id">
		<td height="20"><div align="right" class="txtpallete"><strong>No. of Weeks Active:</strong></div></td>
		<td>&nbsp;&nbsp;
		From: <select name="noofwiks"  disabled = "true" class="txtfield" id="noofwiks" style = "width:20%">
		<option value="0">0</option>
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		</select>
		To: <select name="NoOfWiksto"  disabled = "true" class="txtfield" id="NoOfWiksto" style = "width:20%">
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
		<input name="txtSPStartDate1" type="text"  disabled = "true" class="txtfield" id="txtSPStartDate1" size="20" style="width: 150px"  value = "<?php echo $today; ?>" >
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
</table>
&nbsp;<input type="submit" class = "btn" id="TBcancel" value="Cancel" />
&nbsp;<input type="submit" class = "btn" id="TBsubmit" onclick = "return SpecialCriteriaUpdate();" value="Submit" />
</div>
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

    datePicker('[name=txtStartDate]');
    datePicker('[name=txtEndDate]');
    datePicker('[name=txtBuyinSetStartDate]');
    datePicker('[name=txtBuyinSetEndDate]');
    datePicker('[name=txtBuyinSetStartDate1]');
    datePicker('[name=txtSPStartDate1]');
    datePicker('[name=txtSPEndDate1]');
    datePicker('[name=txtSPStartDate2]');
    datePicker('[name=txtSPEndDate2]');
    datePicker('[name=txtSPStartDate3]');
    datePicker('[name=txtSPEndDate3]');
    datePicker('[name=txtSPStartDate4]');
    datePicker('[name=txtSPEndDate4]');

</script>
</html>