<?php
	//This Design Used For Marketing Brands Incentives
	require_once "../../initialize.php";
	include IN_PATH.DS."scCreateBridgingIncentives.php";

?>
<body>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="../../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<link rel="stylesheet" type="text/css" media="all" href="../../css/jquery-ui-1.8.5.custom.css" title="win2k-cold-1" />
<script type="text/javascript" src="../../js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="../../js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="../../js/popup-calendar/calendar-setup.js"></script>
<!-- product list -->
<script language="javascript" src="../../js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="../../js/thickbox-compressed.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/blitzer/jquery-ui-1.8.2.custom.css">
<!--link rel="stylesheet" type="text/css" href="../../css/thickbox.css--">
<!--script type="text/javascript" src="../../js/jQueryIncentives.js"></script-->
<script>
//function SingleDelete(singleID){
//	if(confirm("are you sure want to delete this record?")==false){
//		return false;
//	}else{
//		$.ajax({
//			url: '../../includes/ajaxbuyinblridging.php'
//		});
//	}
//}
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
								<input type="button"  class="buttonCalendar" name="anchorStxtStartDate" id="anchorStxtStartDate" value=" " ="yes">
								<div id="divSettxtStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
							</td>						
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">&nbsp;
							<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" readonly = "true" size="20" style="width: 150px"  value = "<?php echo $EndDate; ?>">
								<input type="button"  class="buttonCalendar" name="anchorSettxtEndDate" id="anchorSettxtEndDate" value=" " ="yes">
								<div id="divSettxtEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
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
							<tr>
								<td height="20" align="right"><div align="right" class="txtpallete"><strong>Page Num:</strong></div></td>
								<td>&nbsp;&nbsp;<input type="text"  class="txtfield" style = "width:5%;" name="p_from" id = "p_from" value="<?php echo $Pfrom; ?>"> - <input type="text" name="p_to" id = "p_to" value="<?php echo $Pto; ?>" class="txtfield" style = "width:5%;" > </td>
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
								<input type="button"  class="buttonCalendar" name="anchorSBuyinStartDate" id="anchorSBuyinStartDate" value=" " ="yes">
								<div id="divSetBuyinStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
							</td>						
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">&nbsp;&nbsp;
							<input name="txtBuyinSetEndDate" type="text" disabled = "true" class="txtfield" id="txtBuyinSetEndDate" size="20" style="width: 150px" readonly="yes" value = "<?php echo $today;?>">
								<input type="button"  class="buttonCalendar" name="anchorSetButyinSetEndDate" id="anchorSetButyinSetEndDate" value=" " ="yes">
								<div id="divEndSetEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
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
												<td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">ID</div></td>
												<td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td>
												<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td>			
												<td width="5%" height="20"  class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>
												<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td>
												<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td>
												<td width="7%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Action</div></td>
												
										</tr>
										<?php $BuyinIncentives = $tpiIncentives->tpiSelectBuyinIncentives($database, $_GET['ID']); ?>
										<?php $ctr = 1;?>
										<?php if($BuyinIncentives->num_rows):
												  while($row = $BuyinIncentives->fetch_object()): ?>
										<tr>										
													<input type = "hidden" value = <?php echo $row->IncentivesPromoBuyinID; ?> name = "bbID<?php echo $ctr?>">
													<?php if($row->PLEVEL == 1 || $row->PLEVEL == 2 || $row->PLEVEL == 3){ ?>
													<td width="5%" height="20" class="borderBR"><div align="left" class="padl5"><?php echo $ctr;?></div></td>
													<td width="20%" height="20" class=" borderBR"><div align="left" class="padl5"><?php echo $row->Name; ?></div></td>
													<?php }else{ ?>
												
													<td width="5%" height="20" class="borderBR"><div align="left" class="padl5"><?php echo $ctr;?></div></td>
													<?php if($row->PLEVEL == 4){$Promo = "Single Line";}
														else{ $Promo = "Multi Line";} ?>
													<td width="20%" height="20" class=" borderBR"><div align="left" class="padl5"><?php echo $Promo." - ".$row->PromoCode; ?></div></td>
													<?php } ?>
													<?php if($row->CriteriaID == 1): ?>
													<td height='20' class='borderBR'>
														<select class='txtfield' id='' style='width: 100%' name = "BBCriteria<?php echo $ctr;?>">
															<option value='1'> Quantity</option>>
															<option value='2'> Amount </option>
														</select>
													</td>
													<?php else :?>
														<td height='20' class='borderBR'>
															<select class='txtfield' id='' style='width: 100%' name = "BBCriteria<?php echo $ctr;?>">
																<option value='2'>Amount</option>
																<option value='1'> Quantity </option>
															</select>
														</td>
													<?php endif;?>
													<?php if($row->CriteriaID == 1): ?>
														<td width="5%" height="20"  class=" borderBR">
															<div align="right" class="padr5"><input type = "text" name = "BBMinVal<?php echo $ctr;?>" class = "txtfield" value = "<?php echo $row->MinQty; ?>" style = "width: 70%;"></div>
														</td>
													<?php else :?>
														<td width="5%" height="20"  class=" borderBR">
															<div align="right" class="padr5"><input type = "text" class = "txtfield" name = "BBMinVal<?php echo $ctr;?>" value = "<?php echo number_format($row->MinAmt, 2,'.',''); ?>" style = "width: 70%;"></div>
														</td>
													<?php endif;?>
														<td width="10%" height="20" class=" borderBR">
															<div align="right" class="padr5"><?php echo date("m/d/Y",strtotime($row->StartDate)); ?></div>
														</td>
														<td width="10%" height="20" class=" borderBR">
															<div align="right" class="padr5"><?php echo date("m/d/Y",strtotime($row->EndDate)); ?></div>
														</td>
														<td width="7%" height="20" class="borderBR">
															<div align="right" class="padr5"><input type ="submit" name = "btn_delete1" value = "Delete" class = "btn" onclick = "return SingleDelete('<?php echo $row->PromoID; ?>');">
														</td>
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
										<td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">ID.</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">ItemCode</div></td>
										<td width="30%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Description</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td>			
										<td width="5%" height="20"  class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td>
										<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td>
							</tr>
							<tr>
								  <?php $result = $database->execute("SELECT ID FROM incentivespromobuyin WHERE PromoIncentiveID = ".$_GET['ID']); 
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
																<option value='2'> Price </option>
															</select></td>
													<?php else :?>
															<td height='20' class='borderBR'>
															<select name='EECriteria<?php echo $ctr1?>'  class='txtfield' style='width: 100%' >";
																<option value='2'> Price </option>
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
							<input type = "hidden" value = "<?php echo ($ctr++ - 1); ?>" name  = "BuyinCounterXx">
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

</form>



<?//DatePicker For Buyin Requiremnt ?>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divSettxtStartDate",
		button         :    "anchorStxtStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtEndDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divSettxtEndDate",
		button         :    "anchorSettxtEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>


<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtBuyinSetStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divSetBuyinStartDate",
		button         :    "anchorSBuyinStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtBuyinSetEndDate",    	    // id of the input field
		ifFormat       :    "%m/%d/%Y",      		 	   // format of the input field
		displayArea    :	"divEndSetEndDate",
		button         :    "anchorSetButyinSetEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           			 // alignment (defaults to "Bl")
		singleClick    :    true
});
</script>
</html>