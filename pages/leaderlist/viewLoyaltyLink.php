<?php
	require_once("../../initialize.php");
	include IN_PATH.DS."scLoyaltyView.php"; 
	global $database;
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>ERP Branch</title>
			<link rel="shortcut icon" href="../../images/favicon.ico" type="image/x-icon" />
	<link href="../../css/ems.css" rel="stylesheet" type="text/css" />
			<link rel="stylesheet" type="text/css" href="../../css/calpopup.css"/>
	<script language="javascript" src="../../js/string_validation.js"></script>
	<script type="text/javascript" src="../../js/calpopup.js"></script>
	<script type="text/javascript" src="../../js/dateparse.js"></script>
	<!-- TPI Developer 1's source codes added by JP start here... -->
	<script type="text/javascript" src="../../js/ajax-connection.js"></script>
	<script type="text/javascript" src="../../js/tpi-dss-functions.js"></script>
	<!-- TPI Developer 1's source codes added by JP start here... -->
</head>

<body>
	<!-- <meta http-equiv="refresh" content=""> -->
	<!-- calendar stylesheet -->
	<link rel="stylesheet" type="text/css" href="css/ems.css">
	<link rel="stylesheet" type="text/css" media="all" href="../../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
	<link rel="stylesheet" type="text/css" media="all" href="../../css/jquery-ui-1.8.5.custom.css" title="win2k-cold-1" />
	<script type="text/javascript" src="../../js/popup-calendar/calendar.js"></script>
	<script type="text/javascript" src="../../js/popup-calendar/lang/calendar-en.js"></script>
	<script type="text/javascript" src="../../js/popup-calendar/calendar-setup.js"></script>
	<!-- product list -->
	<script language="javascript" src="../../js/jsUtils.js"  type="text/javascript"></script>
	<script language="javascript" src="../../js/prototype.js"  type="text/javascript"></script>
	<script language="javascript" src="../../js/scriptaculous.js"  type="text/javascript"></script>
	<script language="javascript" src="../../js/jquery-1.4.2.min.js"  type="text/javascript"></script>
	<script language="javascript" src="../../js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script>
function confirmDelete(){
	if (confirm('Are you sure you want to delete this transaction?') == false){
		
		return false;
	}else{
	
		return true;
	}
}

function confirmSave(){
	if (confirm('Are you sure you want to update this transaction?') == false){
		
		return false;
	}else{
	
		return true;
	}
}

function autocompletexXx(counter)
{
		jQuery('#EProdCode'+counter+'').autocomplete({
		source:'../../includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				jQuery( "#EProdCode"+counter+"").val( ui.item.label);
				jQuery( "#EProdDesc"+counter+"").val( ui.item.ProductName);
			return false;
		}
		}).data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li style = 'list-style-type:circle;'></li>" )
				.data( "item.autocomplete", item )
				.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
				.appendTo( ul );
		};
}

function deletebtn1(xID)
{
	if(confirm('Are you Sure Want to Delete this Entitlement?')==false){
			return false;
	}else{
			jQuery.ajax({
				type: 		'post',
				datatype: 	'json',
				data:	 	{EntID: xID},
				url: 		'../../includes/scLoyaltyPromo.php?EntDeleteID',
				success:	function(response){
					window.location.assign("viewLoyaltyLink.php?id=<?php echo $_GET['id']?>");
				}
			});
			return false;
	}
}

function deletebtn(xID)
{
	if(confirm('Are you Sure Want to Delete this PromoBuyin?')==false){
			return false;
	}else{
			jQuery.ajax({
				type: 		'post',
				datatype: 	'json',
				data:	 	{BuyinID: xID},
				url: 		'../../includes/scLoyaltyPromo.php?DeleteBuyinID',
				success:	function(response){
					window.location.assign("viewLoyaltyLink.php?id=<?php echo $_GET['id']?>");
				}
			});
			return false;
	}
}
</script>
<form id = "frmCreateLoyalty" name="frmCreateOverlaySet" method="post" action="">
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%">&nbsp;<a class="txtgreenbold13">View Loyalty:</a></td>	
		</tr>
		</table>
	</td>
</tr>
</table>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"><input type="hidden" id="hEntIndex" name="hEntIndex" value=""><input type="hidden" id="hEntCnt" name="hEntCnt" value=""></td> 
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Header</div></td>
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
							<input type = "Hidden" value = "" name = "PromoCode" value = "<?php echo $PromoCode; ?>" readonly = "yes">
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Promo Code :</strong></div></td>
							<td height="20">&nbsp;&nbsp;<input name="txtPromoCode" value = "<?php echo $PromoCode; ?>" readonly = "yes" type="text" class="txtfield" id="txtPromoCode" style="width: 362px;" maxlength="60" />&nbsp;</td>						
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>Promo Title:</strong></div></td>
							<td height="20">&nbsp;&nbsp;<input name="txtPromoTitle" value = "<?php echo $PromoTitle; ?>"  type="text" class="txtfield" id="txtPromoTitle"  style="width: 362px;" maxlength="60"></td>
						</tr>
						<tr>
						<td height="20"><div align="right"  class="txtpallete"><strong>Purchase Requirement Type: </strong></div></td>
						<td height="20">&nbsp;
						
							<select name="PReqtType"  readonly = "yes" class="txtfield" id="PReqtType">
									<?php if($prt == 1): ?>
											<option value= '1' >Single</option>"	
											<option value= '2' >Cumulative</option>
									<?php else: ?>
											<option value= '2' >Cumulative</option>
											<option value= '1' >Single</option>"		  
									<?php endif;?>
							</select>
						</td>
						</tr>
						<?php $query = "SELECT StartDate, EndDate, ID FROM loyaltypromobuyin where LoyaltyPromoID =".$_GET['id']." and LevelType = 0";
								  $buyinDate = $database->execute($query);
								if($buyinDate->num_rows){
									while($xbuyinDate = $buyinDate->fetch_object()){
										$bStartDate = date("m/d/Y",strtotime($xbuyinDate->StartDate));
										$bEndDate = date("m/d/Y",strtotime($xbuyinDate->EndDate));
										$BDate = $xbuyinDate->ID;
									}
								}
							?>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Buy-in-Requirements:</strong></div></td>
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">
							<input name="txtBuyinSetStartDate" type="text" class="txtfield" id="txtBuyinSetStartDate" size="20" style="width: 150px" readonly="yes" value = "<?php echo $bStartDate; ?>" >
								<input type="button"  class="buttonCalendar" name="anchorSBuyinStartDate" id="anchorSBuyinStartDate" value=" " ="yes">
								<div id="divSetBuyinStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
							</td>						
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">
							<input name="txtBuyinSetEndDate" type="text" class="txtfield" id="txtBuyinSetEndDate" size="20" style="width: 150px" readonly="yes" value = "<?php echo $bEndDate; ?>">
								<input type="button"  class="buttonCalendar" name="anchorSetButyinSetEndDate" id="anchorSetButyinSetEndDate" value=" " ="yes">
								<div id="divEndSetEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
							</td>
						</tr>
						<tr>
						<td height="20"><div align="right"  class="txtpallete"><strong>Entitlement:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></div></td>
							<?php $query = "SELECT * FROM loyaltypromoentitlement where LoyaltyBuyinID =".$BDate;
								  $EntnDate = $database->execute($query);
								if($EntnDate->num_rows){
									while($xEntnDate = $EntnDate->fetch_object()){
										$EStartDate = date("m/d/Y",strtotime($xEntnDate->StartDate));
										$EEndDate = date("m/d/Y",strtotime($xEntnDate->EndDate));
									}
								}
							?>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Start Date:</strong></div></td>
							<td height="20">
							<input name="txtEntitlemntSetStartDate" type="text" class="txtfield" id="txtEntitlemntSetStartDate" size="20" style="width: 150px" readonly="yes" value = "<?php echo $EStartDate; ?>">
								<input type="button"  class="buttonCalendar" name="anchorSetEntitlementStartDate" id="anchorSetEntitlementStartDate" value=" " ="yes">
								<div id="divSetStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
							</td>						
						</tr>
						<tr>
							<td height="20"><div align="right" class="txtpallete"><strong>End Date:</strong></div></td>
							<td height="20">
							<input name="txtEntitlemntSetEndDate" type="text" class="txtfield" id="txtEntitlemntSetEndDate" size="20" style="width: 150px" readonly="yes" value = "<?php echo $EEndDate; ?>" >
								<input type="button"  class="buttonCalendar" name="anchorSetEntitlemntSetEndDate" id="anchorSetEntitlemntSetEndDate" value=" " ="yes">
								<div id="divEntitlemntSetEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
							</td>
						</tr>
					</table>
				</td>
				
				<td width="50%">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<?php 
					$maxavailement = $database->execute("SELECT * FROM loyaltypromoavailment where LoyaltyPromoID = ".$_GET['id']);
					if($maxavailement->num_rows){
						while($row = $maxavailement->fetch_object()){
							$NonGSU		= $row->NonGSU;
							$DirectGSU	= $row->DirectGSU;
							$IndirectGSU = $row->IndirectGSU;
							$NetOfCPI	= $row->NetOfCPI;	
						}
					}
				?>
				<td valign="top" class="bgF9F8F7" width="100%">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr><td width = "15%">&nbsp;</td><td width = "85%">&nbsp;</td></tr>
						<tr>
							<td height="20" width = "15%"><div align="right"  class="txtpallete"><strong>Max Availment:</strong></div></td>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Non GSU:</strong></div></td>
							<td height="20">&nbsp;&nbsp;<input name="txtNonGSU" value = "<?php if($NonGSU != 0){echo $NonGSU;}else{echo "";} ?>" 	 onkeyup="javascript:RemoveInvalidChars(txtNonGSU);" type="text" class="txtfield" id="txtNonGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>						
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Direct GSU:</strong></div></td>
							<td height="20">&nbsp;&nbsp;<input name="txtDirectGSU" value = "<?php if($DirectGSU != 0){echo $DirectGSU;}else{echo "";} ?>"   onkeyup="javascript:RemoveInvalidChars(txtDirectGSU);" type="text" class="txtfield" id="txtDirectGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>						
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Indirect GSU:</strong></div></td>
							<td height="20">&nbsp;&nbsp;<input name="txtIndirectGSU" value = "<?php if($IndirectGSU != 0){echo $IndirectGSU;}else{echo "";} ?>"   onkeyup="javascript:RemoveInvalidChars(txtIndirectGSU);"  type="text" class="txtfield" id="txtIndirectGSU" style="width: 150px;" maxlength="60" />&nbsp;</td>						
						</tr>
						<tr>
							<td height="20" align="right"><div align="right" class="txtpallete"><strong>Net of CPI:</strong></div></td>
							<td height="20">&nbsp;&nbsp;<input name="txtNetOfCPI" value = "<?php if($NetOfCPI != 0){echo $NetOfCPI;}else{echo "";} ?>"   onkeyup="javascript:RemoveInvalidChars(txtNetOfCPI);"  type="text" class="txtfield" id="txtNetOfCPI" style="width: 150px;" maxlength="60" />&nbsp;</td>						
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
<br />
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
								<div class="scroll_400">
									<table width="100%" id = "buyinrequirements"  border="0" cellpadding="0" cellspacing="1">
										<tr align="center">
													<td width="10%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Line No.</div></td>
													<td width="50%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Description</div></td>
													<td width="10%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>			
													<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>
													<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Points</div></td>
													<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Action</div></td>
										</tr>
										<?php $result = $database->execute("SELECT * FROM loyaltypromobuyin where LoyaltyPromoID =".$_GET['id']." AND LevelType = 1");
											  if($result->num_rows):
											  $ctr = 1;
											  while($row = $result->fetch_object()): 
												
											  ?>
											  
											  <tr>
													<td width="10%" height="20" class="borderBR"><div align="left" class="padl5"><?php echo $ctr; ?></div></td>
											  <?php if($row->SelectionID == 1 || $row->SelectionID == 2): ?>
													<td width="50%" height="20" class="borderBR"><div align="left" class="padl5">
													<?php $presult = $database->execute ("SELECT * FROM product where ID = ".$row->ProductID.""); 
														if($presult->num_rows){
															while($row1 = $presult->fetch_object()){
																echo $row1->Description;
															}
														}
													?>
													</div></td>
											  <?php else: ?>
													<td width="50%" height="20" class="borderBR"><div align="left" class="padl5">
													<?php 
														$vresult = $database->execute("SELECT * FROM value WHERE ID = ".$row->ValueID); 
														if($vresult->num_rows){
															while($row2 = $vresult->fetch_object()){
																echo $row2->Name;
															}
														} ?></div></td>
											  <?php endif; ?>
													<td width="10%" height="20" class="txtpallete borderBR">
													<select name="Bcriteria<?php echo $ctr?>"  readonly = "yes" class="txtfield" id="PReqtType" style = "width: 100%;">
											  <?php if($row->CriteriaID == 1): ?>
													<option value= '1' >Quantiy</option>
											 		<option value= '2' >Amount</option>
											  <?php else: ?>
											 		<option value= '2' >Amount</option>
											 		<option value= '1' >Quantiy</option>
											  <?php endif;?>
													</select>
													</td>
													<td width="10%" height="20" class="txtpallete borderBR">
											  <?php if($row->CriteriaID == 1): ?>
													<input type = "text" name = "BMinVal<?php echo $ctr?>"class = "txtfield" value = "<?php echo $row->MinQty; ?>" style = "width: 75%;">
											  <?php else: ?>
													<input type = "text" name = "BMinVal<?php echo $ctr?>"class = "txtfield" value = "<?php echo number_format($row->MinAmt,2,".",""); ?>" style = "width: 75%;">
											  <?php endif; ?>
													</td>
													<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">
														<input type = "text" name = "BPoints<?php echo $ctr?>" class = "txtfield" value = "<?php echo $row->Points; ?>" style = "width: 75%;"></div></td>
													<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">
														<input type = "submit" name = "dlt_btn" class = "btn" value = "Delete" onclick = "return deletebtn('<?php echo $row->ID; ?>')"></div></td>
											  <?php	
												echo "<input type ='hidden' name = 'counterbuyin' value = '".$ctr++."'>";
												endwhile;
												endif; ?>
											</tr>
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
					<div class="scroll_400">
							<table width="100%" id = "buyinEnt"  border="0" cellpadding="0" cellspacing="1">
							<tr align="center">
								<td width="10%" height="20" class="borderBR"><div align="left"   class="txtpallete">Line No.</div></td>
								<td width="10%" height="20" class="borderBR"><div align="left"   class="txtpallete">Item Code</div></td>
								<td width="50%" height="20" class="borderBR"><div align="left"   class="txtpallete">Item Description</div></td>			
								<td width="10%" height="20" class="txtpallete borderBR"><div align="center">Points</div></td>	
								<td width="10%" height="20" class="txtpallete borderBR"><div align="center">Action</div></td>	
							</tr>
							<?php $buyinent = $database->execute("SELECT ID from loyaltypromobuyin where LoyaltyPromoID =".$_GET['id']." AND LevelType = 0"); 
								  if($buyinent->num_rows){
									while($rowID = $buyinent->fetch_object())
										$buyinID = $rowID->ID;
										$ctr1 = 1;
										$loyaltyEnt = $database->execute("SELECT a.*, b.Description,b.Code ProductCode FROM loyaltypromoentitlement a
																		  left join product b on a.ProductID = b.ID where a.LoyaltyBuyinID = ".$buyinID);
											if($loyaltyEnt->num_rows){
												while($rowtble = $loyaltyEnt->fetch_object()){
							?>
							<tr>
								<td height="20" class="borderBR"><div align="left" class="txtpallete"><?php echo $ctr1;?></div></td>
								<td height="20" class="borderBR"><div align="left" class="txtpallete"><input onkeyup = "autocompletexXx('<?php echo $ctr1; ?>')" type = "text" class = "txtfield" id = "EProdCode<?php echo $ctr1?>" name = "EProdCode<?php echo $ctr1?>" value = "<?php echo $rowtble->ProductCode; ?>" style = "width: 75%;"></div></td>
								<td height="20" class="borderBR"><div align="left"   class="txtpallete"><input type = "text" class = "txtfield" id = "EProdDesc<?php echo $ctr1?>" name = "EProdDesc<?php echo $ctr1?>" value = "<?php echo $rowtble->Description;  ?>" style = "width: 75%;" ></div></td>			
								<td height="20" class="txtpallete borderBR"><div align="center"><input type = "text" style = "width: 85%;" name = "EPoints<?php echo $ctr1?>" class = "txtfield" value = "<?php echo $rowtble->Points; ?>"></div></td>	
								<td height="20" class="txtpallete borderBR"><div align="center"><input type = "submit" class = "btn" onclick = "return deletebtn1('<?php echo $rowtble->ID; ?>');"  value = "Delete"></div></td>	
								<input type = 'hidden' value = '<?php echo $rowtble->ID; ?>' name = 'EeID<?php echo $ctr1; ?>'>
							</tr>
							<?php			echo "<input type = 'hidden' value = '".$ctr1++."' name = 'entitlementcounter'>";
											}
											}
								  }
							?>
							
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
						<?php
						//<input type="hidden" id="hInc" name="hInc" value=" $inc">
 						//if ($_SESSION['ismain'] == 1)
 						//{
 							//if (($today != $today2) && (($today > $today2) || ($end < $today2)))
 							//{
 								echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onclick='return confirmDelete();'>";
								echo "<input name='btnSave' type='submit' class='btn' value='Update' onclick='return confirmSave();'>"; 								
 							//}
 						//}
 						?>
				</td>			
			</tr>
			</table>
<br />
</body>
</html>

<?//DatePicker For Buyin Requiremnt ?>
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
		inputField     :    "txtBuyinSetEndDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divEndSetEndDate",
		button         :    "anchorSetButyinSetEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>

<?// Datepicker for entitlemnt?>

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtEntitlemntSetStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divSetStartDate",
		button         :    "anchorSetEntitlementStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtEntitlemntSetEndDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divEntitlemntSetEndDate",
		button         :    "anchorSetEntitlemntSetEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
