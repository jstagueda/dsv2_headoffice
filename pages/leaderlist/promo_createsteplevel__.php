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
<script language="javascript" src="js/jxCreateStepLevelPromo.js"  type="text/javascript"></script>

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
	include IN_PATH.DS."scCreateStepLevelPromo.php";
?>

<form name="frmCreateStepLevel" method="post" action="index.php?pageid=65<?php if (isset( $_GET['promoID'])) {?>&promoID=<?php echo $_GET['promoID']; }?>">
<input type="hidden" id="hBuyinCnt" name="hBuyinCnt" value="0">
<input type="hidden" id="hEntitlementCnt" name="hEntitlementCnt" value="0">
<input type="hidden" id="hRangeID" name="hRangeID" value="<?php echo $levelid; ?>">
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
			<td width="70%">&nbsp;<a class="txtgreenbold13">Create Step Level Promo</a></td>
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
			<td width="50%">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="25%">&nbsp;</td>
					<td width="75%" align="right">&nbsp;</td>
				</tr>			
				<tr>
				    <td height="20"><div align="left" class="padl5"><strong>Promo Code :</strong></div></td>
				    <td height="20"><input name="txtCode" type="text" class="txtfield" id="txtCode" value="<?php if (isset($_GET['promoID'])) { echo $promocode; } else { if (isset($_POST['txtCode'])) { echo $_POST['txtCode']; } } ?>" size="30" <?php if (isset($_GET['promoID'])) {?>readonly="yes"<?php } ?>></td>
			    </tr>
			    <tr>
				    <td height="20"><div align="left" class="padl5"><strong>Promo Description :</strong></div></td>
				    <td height="20"><input name="txtDescription" type="text" class="txtfield" id="txtDescription" value="<?php if (isset($_GET['promoID'])) { echo $promodesc; } else { if (isset($_POST['txtDescription'])) { echo $_POST['txtDescription']; } } ?>" style="width: 362px;" maxlength="60" <?php if (isset($_GET['promoID'])) {?>readonly="yes"<?php } ?>>
					</td>
			    </tr>
				<tr>
				  	<td height="20"><div align="left" class="padl5"><strong>Start Date: </strong></div></td>
				  	<td height="20">
						<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php if (isset($_POST['txtStartDate'])) { $tmpsdate = strtotime($_POST['txtStartDate']); $sdate = date("m/d/Y",$tmpsdate); echo $sdate; } else { echo $today; } ?>"  onChange="checkEndDate();">
						<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" " <?php if (isset($_GET['promoID'])) {?>disabled<?php } ?>>
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>
				<tr>
				  	<td height="20"><div align="left" class="padl5"><strong>End Date: </strong></div></td>
				  	<td height="20">
						<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php if (isset($_POST['txtEndDate'])) { $tmpedate = strtotime($_POST['txtEndDate']); $edate = date("m/d/Y",$tmpedate); echo $edate; } else { echo $today; } ?>">
						<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" " <?php if (isset($_GET['promoID'])) {?>disabled<?php } ?>>
						<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>	
				<tr>
				  	<td height="20"><div align="left" class="padl5"><strong>Purchase Requirement Type: </strong></div></td>
				  	<td height="20">
						<select name="cboPReqtType" class="txtfield" id="cboPReqtType" <?php if (isset($_GET['promoID'])) {?>disabled<?php } ?>>
							<option value="1" <?php if (isset($_GET['promoID'])) { if ($_GET['promoID'] == 1) { echo "selected"; } } else { if (isset($_POST['cboPReqtType'])) { if ($_POST['cboPReqtType'] == 1) { echo "selected";} } }?>>Single</option>
							<option value="2" <?php if (isset($_GET['promoID'])) { if ($_GET['promoID'] == 2) { echo "selected"; } } else { if (isset($_POST['cboPReqtType'])) { if ($_POST['cboPReqtType'] == 2) { echo "selected";} } }?>>Cumulative</option>
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
				    <td height="20" valign="top"><div align="left" class="padl5"><strong>Max Availment :</strong></div></td>
				    <td height="20">
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				    	<?php
				    	if($rs_gsutype->num_rows)
						{
							while($row = $rs_gsutype->fetch_object())
							{
								$txt = 'txtMaxAvail'.$row->ID;
								echo "<tr>
				    						<td width='15%' height='20'><div align='left' class='padl5'><strong>$row->Name :</strong></div></td>
				    						<td width='75%' height='20'><input type='text' class='txtfield' name='$txt'></td>
				    				</tr>";
							}
							$rs_gsutype->close();
						}
				    	?>
				    	</table>
				    </td>
			    </tr>
			    <tr>
				    <td height="20"><div align="left" class="padl5"><strong>Is Plus Plan :</strong></div></td>
				    <td height="20"><input type="checkbox" name="chkPlusPlan" value="1" <?php if(isset($_POST['chkPlusPlan'])) { echo "checked"; } ?>></td>
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
	<td valign="top" width="48%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Step Level</div></td>
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
							<td height="25" align="left" class="borderBR padl5">
								<strong>Step No. :</strong>
								&nbsp;
								<?php echo $ctr; ?>
								&nbsp;&nbsp;
								<strong>Criteria :</strong>
								&nbsp;
								<select name="cboCriteria" class="txtfield" id="cboCriteria" style="width: 80px;">
									<option value="1">Quantity</option>
									<option value="2" selected="selected">Amount</option>
								</select>
								&nbsp;&nbsp;
								<strong>Minimum Value :</strong>
								&nbsp;
								<input name="txtMinimum" type="text" value="" class="txtfield" style="width: 80px">
								&nbsp;&nbsp;
								<strong>Maximum Value :</strong>
								&nbsp;
								<input name="txtMaximum" type="text" value="" class="txtfield" style="width: 80px">
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td valign="top" class="bgF9F8F7">
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td>
								<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
								<tr align="center">
								    <td width="15%" height="20" class="borderBR"><div align="center">&nbsp;</div></td>
									<td width="10%" height="20" class="borderBR"><div align="center">Line No.</div></td>
									<td width="25%" height="20" class="borderBR"><div align="left" class="padl5">Item Code</div></td>
									<td width="30%" height="20" class="borderBR"><div align="left" class="padl5">Item Description</div></td>						
									<td width="20%" height="20" class="borderBR"><div align="left" class="padl5">PMG</div></td>		
								</tr>
								</table>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="bgF9F8F7">
						<div class="scroll_150">
							<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicBuyInTable">
							<tr align="center">
								<td width="15%" height="20" class="borderBR"><div align="center"><input name="btnRemove1" type="button" class="btn" value="Remove" onclick="deleteRow(this.parentNode.parentNode.rowIndex, 1)"></div></td>
								<td width="10%" height="20" class="borderBR"><div align="center">1</div></td>
								<td width="25%" height="20" class="borderBR"><div align="left" class="padl5">
									<input name="txtCriteria1" type="text" class="txtfield" id="txtCriteria1" style="width: 120px;" value="" />
						    		<span id="indicatorCriteria1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>
						    		<div id="prod_choices_criteria1" class="autocomplete" style="display:none"></div>
						    		<script type="text/javascript">							
								 		//<![CDATA[
								 		var u = 'includes/scProductRangeAjax.php?range=3&index=1'; 
		                        		var prod_choices_criteria1 = new Ajax.Autocompleter('txtCriteria1', 'prod_choices_criteria1', u, {afterUpdateElement : getSelectionProductCriteriaList, indicator: 'indicatorCriteria1'});																			
		                        		//]]>
		                        	</script>
								</div></td>
								<td width="30%" height="20" class="borderBR"><div align="left" class="padl5">
									<input name="txtCDescription1" type="text" class="txtfield" id="txtCDescription1" style="width: 200px;" value="" readonly="yes" />
									<input name="hProdID_criteria1" type="hidden" id="hProdID_criteria1" value="" />
								</div></td>
								<td width="20%" height="20" class="borderBR"><div align="left" class="padl5"><input name="txtCPMG1" type="text" class="txtfield" id="txtCPMG1" style="width: 100px;" value="" readonly="yes" /></div></td>			
							</tr>
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
	<td width="46%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Entitlement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr align="center">
			<td height="25" class="borderBR"><div align="left" class="padl5">
				<strong>Type :</strong>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="cboType" class="txtfield" id="cboType" style="width: 100px;">
					<option value="1">Selection</option>
					<option value="2">Set</option>
				</select>
				&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;
				<input name="txtTypeQty" type="text" class="txtfield" id="txtTypeQty" style="width: 60px; text-align: right;">
			</div></td>
		</tr>
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="10%" height="20" class="borderBR"><div align="center" class="padl5">Line No.</div></td>
							<td width="12%" height="20" class="borderBR"><div align="left" class="padl5">Item Code</div></td>
							<td width="33%" height="20" class="borderBR"><div align="left" class="padl5">Item Description</div></td>			
							<td width="15%" height="20" class="borderBR"><div align="center">Criteria</div></td>
							<td width="15%" height="20" class="borderBR"><div align="center">Qty/Price</div></td>
							<td width="20%" height="20" class="borderBR"><div align="center">PMG</div></td>			
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_150">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicEntTable">
					<tr align="center">
						<td width="10%" height="20" class="borderBR"><div align="center">1</div></td>
						<td width="12%" height="20" class="borderBR"><div align="left" class="padl5">
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
						<td width="33%" height="20" class="borderBR"><div align="left" class="padl5"><input name="txtEProdDesc1" type="text" class="txtfield" id="txtEProdDesc1" style="width: 200px;" readonly="yes" /></div></td>			
						<td width="15%" height="20" class="borderBR"><div align="center">
							<select name="cboECriteria1" class="txtfield" id="cboECriteria1" style="width: 80px;">
								<option value="2" selected="selected">Price</option>
								<option value="1">Quantity</option>
							</select>
						</div></td>
						<td width="15%" height="20" class="borderBR"><div align="center"><input name="txtEQty1" type="text" class="txtfield" id="txtEQty1"  value="1" style="width: 50px; text-align:right"  onkeypress="return disableEnterKey(event)"/></div></td>
						<td width="20%" height="20" class="borderBR"><div align="center">
						<!--<input name="txtPmg1" type="text" id="txtPmg1" class="txtfield" readonly="readonly"  style="width: 138px; text-align:left" value=""/>-->
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
<table width="97%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="right">
		<input name='btnAdd' type='submit' class='btn' value='Add Step and Save' onclick='return confirmSave();'>
	</td>			
</tr>
</table>
<br>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td width="48%">
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
							<td width="10%" height="20" class="borderBR"><div align="center">Step No.</div></td>
							<td width="33%" height="20" class="borderBR"><div align="left" class="padl5">Selection</div></td>
							<td width="15%" height="20" class="borderBR"><div align="left" class="padl5">Criteria</div></td>			
							<td width="15%" height="20" class="borderBR"><div align="center">Minimum</div></td>
							<td width="15%" height="20" class="borderBR"><div align="center">Maximum</div></td>	
							<td width="15%" height="20" class="borderBR"><div align="center">PMG</div></td>				
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_150">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1">
				<?php
					if (isset($_GET['promoID']))
					{
						if ($rspromobuyin->num_rows)
						{
							$step = 0;
							$step_str = "";
							$parentbuyin = 0;
							$rowalt = 0;
							while($row = $rspromobuyin->fetch_object())
							{
								$rowalt++;
								($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
								
								if ($row->ParentPromoBuyinID != $parentbuyin)
								{
									$step++;
									$step_str = $step;									
								}
								else
								{
									$step_str = "";
								}
								
								$parentbuyin = $row->ParentPromoBuyinID;
								
								if($row->Criteria == 1)
								{
									$criteria = "Quantity";
									$minimum = number_format($row->Minimum, 0, '.', '');
									$maximum = number_format($row->Maximum, 0, '.', '');
								}
								else
								{
									$criteria = "Amount";
									$minimum = number_format($row->Minimum, 2, '.', '');
									$maximum = number_format($row->Maximum, 2, '.', '');
								}
								
								echo "<tr align='center' class='$class'>
										<td width='10%' height='20' class='borderBR'><div align='center'>$step_str</div></td>
										<td width='33%' height='20' class='borderBR'><div align='left' class='padl5'>$row->ProdName</div></td>
										<td width='15%' height='20' class='borderBR'><div align='left' class='padl5'>$criteria</div></td>			
										<td width='15%' height='20' class='borderBR'><div align='center'>$minimum</div></td>
										<td width='15%' height='20' class='borderBR'><div align='center'>$maximum</div></td>
										<td width='15%' height='20' class='borderBR'><div align='center'>$row->pmgCode</div></td>
								</tr>";							
							}
							$rspromobuyin->close();
						}
						else
						{
							echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
						}						
					}
					else
					{
						echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
					}
				?>
				</table>
				</div>
			</td>
		</tr>
		</table>
	</td>
	<td width="1%">&nbsp;</td>
	<td width="47%">
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
							<td width="10%" height="20" class="borderBR"><div align="center">&nbsp;</div></td>
							<td width="15%" height="20" class="borderBR"><div align="left" class="padl5">Item Code</div></td>
							<td width="35%" height="20" class="borderBR"><div align="left" class="padl5">Item Description</div></td>			
							<td width="15%" height="20" class="borderBR"><div align="center">Quantity</div></td>
							<td width="15%" height="20" class="borderBR"><div align="center">Offer Price</div></td>		
							<td width="15%" height="20" class="borderBR"><div align="center">PMG</div></td>		
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_150">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1">
				<?php
					if (isset($_GET['promoID']))
					{
						if ($rspromobuyin_ent->num_rows)
						{
							$parentbuyin = 0;
							$ctr = 0;
							while($row = $rspromobuyin_ent->fetch_object())
							{
								if ($row->ParentPromoBuyinID != $parentbuyin)
								{
									$ctr += 1;
									//get promoentitlementid
									$rspromentitlement = "rspromentitlement".$ctr;
									$rspromentitlement = $sp->spSelectPromoEntitlementByPromoBuyInID($database, $row->ParentPromoBuyinID);
									if ($rspromentitlement->num_rows)
									{
										while($rowEnt = $rspromentitlement->fetch_object())
										{
											$promoentID = $rowEnt->ID; 									
										}
										$rspromentitlement->close();								
									}
									
									//get promoentitlementdetails
									$rspromentitlement_details = "rspromentitlement_details".$ctr;
									$rspromentitlement_details = $sp->spSelectPromoEntitlementDetailsByPromoEntitlementID($database, $promoentID);
									if ($rspromentitlement_details->num_rows)
									{
										$rowalt = 0;
										$tmpstep = "";
										
										while($row_det = $rspromentitlement_details->fetch_object())
										{
											$rowalt++;
											($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
											$qty = number_format($row_det->Quantity, 0, '.', '');
											$amt = number_format($row_det->EffectivePrice, 2, '.', '');
											$step_ = "Step ".$ctr;
											if($tmpstep !=  $step_)
											{
												$step = "Step ".$ctr;
											}
											else
											{
												$step = "&nbsp;";
											}
											echo "<tr align='center' class='$class'>
													<td width='10%' height='20' class='borderBR'><div align='center'>$step</div></td>
													<td width='15%' height='20' class='borderBR'><div align='left' class='padl5'>$row_det->ProdCode</div></td>
													<td width='35%' height='20' class='borderBR'><div align='left' class='padl5'>$row_det->ProdName</div></td>			
													<td width='15%' height='20' class='borderBR'><div align='center'>$qty</div></td>
													<td width='15%' height='20' class='borderBR'><div align='center'>$amt</div></td>
													<td width='15%' height='20' class='borderBR'><div align='center'>$row_det->pmgcode</div></td>
											</tr>";	
											$tmpstep = $step_;
										}					
										$rspromentitlement_details->close();			
									}
									else
									{
										echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
									}									
								}
								
								$parentbuyin = $row->ParentPromoBuyinID;
							}
							$rspromobuyin_ent->close();
						}
						else
						{
							echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
						}						
					}
					else
					{
						echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";	
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
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
		<!--<input name='btnSave' type='submit' class='btn' value='Save' onclick='return confirmSave();'>-->
		<?php
		if (isset($_GET['promoID']))
		{
			echo "<input name='btnDone' type='submit' class='btn' value='Done'>";
		}
		else
		{
			echo "<input name='btnCancel' type='submit' class='btn' value='Cancel' onclick='return confirmCancel();'>";			
		}
		?>
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