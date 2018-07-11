<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="css/jquery-ui-1.8.5.custom.css" title="win2k-cold-1" />

<link rel="stylesheet" type="text/css" href="css/ems.css">
<?php
	include IN_PATH.DS."scCreateSetPromo.php";
	$database->execute("delete from overlaybuyintmp where sessionID = ".$session->emp_id);
?>
<script type="text/javascript" src="js/jQueryOverlay.js"></script>


<style>
.trheader td{background:#FFDEF0; padding:5px; font-weight:bold; border:1px solid #FFA3E0;}
.trlist td{padding:2px 5px; border:1px solid #FFA3E0;}
</style>

<body>
<form name="frmCreateSet" method="post" action="index.php?pageid=178.1&inc=<?php echo $inc; ?>">

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
		<?php if($inc == 1){ ?>
			<td width="70%">&nbsp;<a class="txtgreenbold13">Create Overlay Promo</a></td>
		<?php }else{ ?>
		<td width="70%">&nbsp;<a class="txtgreenbold13">Create Incentives Promo</a></td>
		<?php }?>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php if ($errmsg != ""){?>
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
<?php } ?>
<br>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin">
	<input type="hidden" id="hEntIndex" name="hEntIndex" value="">
	<input type="hidden" id="hEntCnt" name="hEntCnt" value="">
	</td>
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Promo Header</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<table width="98%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="50%">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="30%">&nbsp;</td>
					<td width="5%"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" align="right"><strong>Promo Code</strong></td>
					<td align="center">:</td>
				    <td height="20"><input name="txtCode" type="text" onkeydown ="return CheckPromo(event);" class="txtfield" id="txtCode" value="" size="30"></td>
			    </tr>
			    <tr>
				    <td height="20" align="right"><strong>Promo Description</strong></td>
					<td align="center">:</td>
				    <td height="20"><input name="txtDescription" type="text" disabled = "true" onkeydown = 'return NextField(event,"txtStartDate");' class="txtfield" id="txtDescription" style="width: 362px;" maxlength="">
					</td>
			    </tr>
				<tr>
				  	<td height="20" align="right"><strong>Start Date</strong></td>
					<td align="center">:</td>
				  	<td height="20">
						<input name="txtStartDate" disabled = "true" type="text" class="txtfield" id="txtStartDate" onkeydown = 'return NextField(event,"txtEndDate");' size="20" value="<?php echo $today; ?>">
						<i>(e.g. MM/DD/YYYY)</i>
					</td>
				</tr>
				<tr>
				  	<td height="20" align="right"><strong>End Date</strong></td>
					<td align="center">:</td>
				  	<td height="20">
						<input name="txtEndDate" disabled = "true" type="text" class="txtfield" id="txtEndDate" onkeydown = 'return NextField(event,"cboPReqtType");' size="20" value="<?php echo $today; ?>">
						<i>(e.g. MM/DD/YYYY)</i>
					</td>
				</tr>
				<tr>
				  	<td height="20" align="right"><strong>Purchase Requirement Type</strong></td>
					<td align="center">:</td>
				  	<td height="20">
						<select name="cboPReqtType" disabled = "true" class="txtfield" id="cboPReqtType" onkeydown = 'return NextField(event,"rdoBReqt");'  style="visibility: visible">
							<option value="1">Single</option>
							<option value="2">Cumulative</option>
						</select>
					</td>
				</tr>
				<tr>
				  	<td height="20" align="right"><strong>Buy-in Requirement</strong></td>
					<td align="center">:</td>
				  	<td height="20">
				  		<select name="rdoBReqt" class="txtfield" id="rdoBReqt" onchange = "buyin_requirement();" onkeydown = 'return NextField(event,"selection_no");' style="visibility: visible;" disabled = "true">
							<option value="2">Selection by Quantity</option>
							<option value="1">Multiple</option>
							<option value="3">Selection by Amount</option>
						</select>
						<strong style="margin-left:25px;">Minimum Value</strong>
						<span style="padding:0 10px; text-align:center;">:</span>
						<input type = "text" id="selection_no" name="selection_no" class = "txtfield" onkeydown = 'return NextField(event,"isRegular");' disabled="disabled" style="width:50px;" /> <?php //for selection ?>
					</td>
				</tr>
				<tr>
				  	<td height="20" align="right"><strong>Is Regular</strong></td>
					<td align="center">:</td>
				  	<td height="20">
						<select name="isRegular" disabled = "true" class="txtfield" id="isRegular" onkeydown = 'return NextField(event,"txtMaxAvail1");' style="visibility: visible">
							<option value="1">No</option>
							<option value="0">Yes</option>
						</select>
					</td>
				</tr>
				</table>
				<br />
			</td>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="20%">&nbsp;</td>
					<td width="5%"></td>
					<td width="75%">&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" align="right"><strong>Max Availment</strong></td>
					<td align="center">:</td>
					<td></td>
				</tr>
				<tr>
				    <td height="20" colspan="3" style="padding-left:30px;">
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
							<tr>
								<td width='25%' height='20' align="right"><strong>NonGSU</strong></td>
								<td align="center" width="5%">:</td>
								<td width='75%' height='20'>&nbsp;<input type='text' id = 'txtMaxAvail1' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail2");' class='txtfield' name='txtMaxAvail1' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail1);'></td>
							</tr>
							<tr>
								<td width='25%' height='20' align="right"><strong>Direct GSU</strong></td>
								<td align="center" width="5%">:</td>
								<td width='75%' height='20'>&nbsp;<input type='text' id = 'txtMaxAvail2' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail3");' class='txtfield' name='txtMaxAvail2' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail2);'></td>
							</tr>
							<tr>
								<td width='25%' height='20' align="right"><strong>Indirect GSU</strong></td>
								<td align="center" width="5%">:</td>
								<td width='75%' height='20'>&nbsp;<input type='text' id = 'txtMaxAvail3' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail3");' class='txtfield' name='txtMaxAvail3' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail3);'></td>
							</tr>
				    	</table>
				    </td>
			    </tr>
				
				<!--Edited by Alvin -->
			    <!-- <tr>
				    <td height="20" align="right"><strong>Is Plus Plan</strong></td>
					<td align="center">:</td>
				    <td height="20"><input type="checkbox" name="chkPlusPlan" value="1"></td>
				</tr> -->
				<input type="hidden" name="chkPlusPlan" value="1">
				<!--<tr>
				  	<td height="20" align="right"><strong>Brochure Page</strong></td>
					<td align="center">:</td>
				  	<td height="20">
						<input name="bpage" type="text" value = "0" onkeyup="javascript:RemoveInvalidChars(bpage);" class="txtfieldg" id="bpage" size="10" value="" style = "width: 5%;" disabled = "true" />&nbsp; - &nbsp;
						<input name="epage" type="text" value = "0" onkeyup="javascript:RemoveInvalidChars(epage);" class="txtfieldg" id="epage" size="10" value="" style = "width: 5%;" disabled = "true" />
					</td>
				</tr> -->
				
						<input name="bpage" type="hidden" value = "0" onkeyup="javascript:RemoveInvalidChars(bpage);" class="txtfieldg" id="bpage" size="10" value="" style = "width: 5%;" disabled = "true" />&nbsp; - &nbsp;
						<input name="epage" type="hidden" value = "0" onkeyup="javascript:RemoveInvalidChars(epage);" class="txtfieldg" id="epage" size="10" value="" style = "width: 5%;" disabled = "true" />
				<!--edit ends here -->
				
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
				<tr>
					<td height="20" align="right"><strong>Is Any-any Promo</strong></td>
					<td align="center">:</td>
					<td height="20">
						<input type="checkbox" value="1" name="IsAnyPromo">
					</td>
				</tr>
				<tr>
					<td height="20" align="right"><strong>Total Price</strong></td>
					<td align="center">:</td>
					<td height="20">
						<input type="text" value="0.00" name="TotalPrice" class="txtfield" style="width:100px; text-align:right;" disabled="disabled" onkeydown="return RemoveInvalidChars(this);" onkeyup="return RemoveInvalidChars(this);">
					</td>
				</tr>
				</table>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
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
							<td height="20" width="25%">&nbsp;</td>
							<td width="5%"></td>
							<td height="20" width="70%" align="right">&nbsp;</td>
						</tr>
					    <tr>
						    <td height="21" align="right"><strong>Range</strong></td>
							<td align="center">:</td>
						    <td height="21">
						    	<select name="cboRange"  id = "cboRange"  disabled = "true" style="width:150px; height:20px; visibility: visible;" class="txtfield" >
		                            <option value="0">[SELECT HERE]</option>
		                          	<?php
		                              	if ($rsprodlevel->num_rows){
		                                  	while ($row = $rsprodlevel->fetch_object()){
		                                      	echo "<option value='".$row->ID."'>".$row->Name."</option>";
		                                  	}
		                                  	$rsprodlevel->close();
		                              	}
		                          	?>
								</select>
						    </td>

					    <tr>
						    <td height="22" align="right"><strong>Product Code</strong></td>
							<td align="center">:</td>
						    <td height="22">
						    	<input name="txtProductCode" type="text" class="txtfield" id="txtProductCode" style="width: 150px;" value="" disabled = "true" />
						    	<input name="txtCDescription" type="text" class="txtfield" id="txtCDescription" style="width: 250px;" value="" readonly="yes" disabled = "true" />
						    </td>
						</tr>
						<tr>
						    <td height="22" align="right"><strong>Product Line :</strong></td>
							<td align="center">:</td>
						    <td height="22">
						    	<select name="pline"  id = "pline"  style="width:150px; height:20px; visibility: visible;" class="txtfield" disabled = "true">
		                            <option value="0">[SELECT HERE]</option>
									<?php $pline = $database->execute("SELECT ID, Description name FROM product WHERE ProductLevelID = 2");
										if($pline->num_rows){
											while($row = $pline->fetch_object()){
												$Name = $row->name;
												$ID	  = $row->ID;
									?>
										<option value="<?php echo $ID; ?>"> <?php echo $Name; ?> </option>
									<?php
											}
										}
									?>
								</select>
						    </td>
						</tr>
						<tr>
							<td height="22" align="right"><strong>Promo Code</strong></td>
							<td align="center">:</td>
							<td height="22">
								<input name="txtPromoCodePromo" disabled = "disabled" type="text"  class="txtfield" id="txtPromoCodePromo" onkeypress ="get_promo(1);" size="20" style="width: 150px"  value = "" >
							</td>
						</tr>
                        <tr>
							<td height="22" align="right"><strong>Brochure Type</strong></td>
							<td align="center">:</td>
							<td height="22">
								 <select name="BrochureType" id ="BrochureType" disabled="disabled" class="txtfield" style="width: 150px; visibility: visible;">
								 <option value ="0">SELECT HERE</option>
								 <?php $q = $database->execute("SELECT * FROM collateraltype");
								 if($q->num_rows):
									  while($r=$q->fetch_object()):
										  echo "<option value = '".$r->ID."'>".$r->Name."</option>";
									  endwhile;
								 endif;
								 ?>
								 </select>
							</td>
						</tr>
					    <tr>
						    <td height="22" align="right"><strong>Criteria</strong></td>
							<td align="center">:</td>
						    <td height="22">
						    	<select name="cboCriteria" class="txtfield" id="cboCriteria" style="width: 150px; visibility: visible;" disabled = "true">
									<option value="1">Quantity</option>
									<option value="2">Amount</option>
								</select>
						    </td>
					    </tr>
					    <tr>
						    <td height="22" align="right"><strong>Minimum Value</strong></td>
							<td align="center">:</td>
						    <td height="22"><input name="txtMinimum" id = "txtMinimum" type="text" value="" class="txtfield" style="width: 150px" disabled = "true"></td>
					    </tr>
					    <tr>
						    <td height="22" align="right"><strong>Start Date</strong></td>
							<td align="center">:</td>
						    <td height="22">
						    	<input name="txtSetStartDate" type="text" class="txtfield" id="txtSetStartDate" size="20" style="width: 150px" readonly="yes" disabled = "true" value="<?php echo $today; ?>">
								<i>(e.g. MM/DD/YYYY)</i>
							</td>
					    </tr>
					    <tr>
						    <td height="22" align="right"><strong>End Date</strong></td>
							<td align="center">:</td>
						    <td height="22">
						    	<input name="txtSetEndDate" type="text" class="txtfield" id="txtSetEndDate" size="20" style="width: 150px" readonly="yes" disabled = "true" value="<?php echo $end; ?>">
								<i>(e.g. MM/DD/YYYY)</i>
							</td>
					    </tr>
						<tr>
						    <td height="22" align="right"><strong>Brochure Page</strong></td>
							<td align="center">:</td>
						    <td height="22">
						    	<input name="BrochureStart" type="text" class="txtfield" id="BrochureStart" size="20" style="width: 67px;" disabled = "true" value="">
						    	-
								<input name="BrochureEnd" type="text" class="txtfield" id="BrochureEnd" size="20" style="width: 67px;"  disabled = "true" value="">
								<i>(eg. From-To)</i>
							</td>
					    </tr>						
						<tr>
							<td></td>
							<td></td>
							<td style="padding:5px 0;">
								<input name='btnAdd' id = 'btnAdd' type='submit' class='btn' value='Add Buy-In Requirement' onclick='return confirmAdd();' disabled = "true">
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
				<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
				<tr>
					<td class="bgF9F8F7">
						<div style="overflow:auto; height:179px;">
							<table width="100%"  border="0" cellpadding="0" cellspacing="0" id = "buyindetails">
								<tr align="center" class="trheader">
									<td width="12%">Overlay No.</td>
									<td width="25%">Selection</td>
									<td width="10%">Criteria</td>
									<td width="9%">Minimum</td>
									<td width="12%">Start Date</td>
									<td width="12%">End Date</td>
									<td width="5%">Action</td>
								</tr>								
								<tr class="trlist">
									<td colspan='7' align="center" style="padding:5px;">No record(s) to display.</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
					<tr>
					<td>
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
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Entitlement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
			<tr align="center" class="trheader">
				<td style="padding:2px;">
					<strong>Type&nbsp;&nbsp;:</strong>&nbsp;					
					<select name="cboType" class="txtfield" id="cboType" style="width: 100px;">
						<option value="2">Selection</option>
						<option value="1">Set</option>
					</select>
					
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<strong>Selection No.&nbsp;&nbsp;:</strong>&nbsp;
					<input name="txtTypeQty" type="text" onkeyup="javascript:RemoveInvalidChars(txtTypeQty);" class="txtfield" id="txtTypeQty" style="width: 60px; text-align: right;">
					
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<strong>Apply as discount&nbsp;&nbsp;:</strong>&nbsp;
					<input type="checkbox" id="chkApplyAsDiscount" name="chkApplyAsDiscount" />
					
				</td>
			</tr>
			<tr>
				<input type = "hidden" value = '1' id = "entitlementcnt" name = "entitlementcnt">
				<td class="bgF9F8F7">
					<div style="overflow:auto; height:473px;">
						<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="multirow">
							<tr class="trheader" align="center">
								<td width="1%">Action</td>
								<td width="10%">Line No.</td>
								<td width="12%">Item Code</td>
								<td width="31%">Item Description</td>
								<td width="12%">Criteria</td>
								<td width="12%">Minimum</td>
								<td width="20%">PMG</td>
							</tr>
							<tr align="center" class="trlist">
								<td>
									<input name="btnRemove1" type="button" class="btn" value="Remove" onclick="return removeRow(this);">
								</td>
								<td>1</td>
								<td>
									<input name="txtEProdCode1" onkeypress="return xautocompleter(1);" type="text" class="txtfield" id="txtEProdCode1" style="width: 99%;" value=""/>
									<input name="hEProdID1" type="hidden" id="hEProdID1" value="" />
									<input name="hEUnitPrice1" type="hidden" id="hEUnitPrice1" value=""/>
								</td>
								<td>
									<input name="txtEProdDesc1" type="text" class="txtfield" id="txtEProdDesc1" style="width: 99%;" readonly="yes" />
								</td>
								<td>
									<select name="cboECriteria1" class="txtfield" id="cboECriteria1" style="width: 99%;">
										<option value="2" selected="selected">Price</option>
										<option value="1">Quantity</option>
									</select>
								</td>
								<td><input name="txtEQty1" type="text" class="txtfield" id="txtEQty1"  value="1" style="width: 99%; text-align:right" onkeypress="return addRow(event, this)" /></td>
								<td>
									<select name="cboEPMG1" class="txtfield" id="cboEPMG1" style="width: 99%;" >

										<?php
											$rs_pmg = $sp->spSelectPMG($database);
											if ($rs_pmg->num_rows)
											{
												while ($row = $rs_pmg->fetch_object()){
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
		<input name='btnSave' id = "btnSave" 	disabled = "disabled" type='submit' class='btn' value='Save' 	 onclick='return confirmSave();' />
		<input name='btnCancel' id = "btnCancel" type='submit' class='btn' value='Cancel' onclick='return confirmCancel();' />
	</td>
</tr>
</table>
</form>
</body>
<br>