<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="../../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="../../js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="../../js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="../../js/popup-calendar/calendar-setup.js"></script>
<!-- product list -->
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jxCreateSetPromo.js"  type="text/javascript"></script>
<script language="javascript" type="text/javascript">
  function confirmDelete()
  {
  	if (confirm('Are you sure you want to delete this promo?') == false)
  		return false;
  	else
  		return true; 		
  		//opener.location.href = '../../index.php?pageid=64&msg=Successfully deleted promo.';
		//window.close();
  }
</script>
<?php
	require_once "../../initialize.php";
	include IN_PATH.DS."scStepLevelPromoDet.php";
?>

<form name="frmSingleLine" method="post" action="promo_steplevelDetails.php?prmsid=<?php echo $_GET['prmsid'];?>">
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
					<td width="10%">&nbsp;</td>
					<td width="90%" align="right">&nbsp;</td>
				</tr>			
				<tr>
				    <td width="20%" height="20"><div align="left" class="padl5"><strong>Promo Code :</strong></div></td>
				    <td width="20%" height="20"><input name="txtCode" type="text" class="txtfield" id="txtCode" value="<?php echo $prmcode;?>" size="30"></td>
			    </tr>
			    <tr>
				    <td width="20%" height="20"><div align="left" class="padl5"><strong>Promo Description :</strong></div></td>
				    <td width="20%" height="20"><input name="txtDescription" type="text" class="txtfield" id="txtDescription" value="<?php echo $prmdesc;?>" style="width: 362px;" maxlength="60">
					</td>
			    </tr>
				<tr>
				  	<td width="20%" height="20"><div align="left" class="padl5"><strong>Start Date: </strong></div></td>
				  	<td width="20%" height="20">
						<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $prmsdate; ?>">
						<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>
				<tr>
				  	<td width="20%" height="20"><div align="left" class="padl5"><strong>End Date: </strong></div></td>
				  	<td width="20%" height="20">
						<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $prmedate; ?>">
						<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
						<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
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
				    	if($rs_promoAvailment->num_rows)
						{
							while($row = $rs_promoAvailment->fetch_object())
							{
								$txt = $row->MaxAvailment;
								echo "<tr>
				    						<td width='15%' height='20'><div align='left' class='padl5'><strong>$row->Name :</strong></div></td>
				    						<td width='75%' height='20'>
				    						<input type='text' id='txtMaxAvail$row->GSUTypeID' class='txtfield' name='txtMaxAvail$row->GSUTypeID' value='$txt'></td>
				    						
				    				</tr>";
							}
							$rs_promoAvailment->close();
						}
						else
						{
							if($rs_gsutype->num_rows)
							{
								while($row = $rs_gsutype->fetch_object())
								{
									
									echo "<tr>
					    						<td width='15%' height='20'><div align='left' class='padl5'><strong>$row->Name :</strong></div></td>
					    						<td width='75%' height='20'>
				    						    <input type='text' id='txtMaxAvail$row->ID' class='txtfield' name='txtMaxAvail$row->ID' value=''></td>
					    				</tr>";
								}
								$rs_gsutype->close();
							}
						}
				    	?>
				    	</table>
				    </td>
			    </tr>
			    <tr>
				    <td height="20"><div align="left" class="padl5"><strong>Is Plus Plan :</strong></div></td>
				    <td height="20"><input type="checkbox" name="chkPlusPlan" value="1" <?php if ($prmPplan == 1) { echo "checked"; } ?></td>
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
	<td width="47%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"><input type="hidden" id="hID" name="hID" value="<?php echo $promoID;?>"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr align="center">
				<td height="25" class="borderBR">
				</td>
				</tr>
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="10%" height="20" class="borderBR"><div align="center">Step No.</div></td>
							<td width="35%" height="20" class="borderBR"><div align="left" class="padl5">Selection</div></td>
							<td width="15%" height="20" class="borderBR"><div align="left" class="padl5">Criteria</div></td>			
							<td width="20%" height="20" class="borderBR"><div align="right" class="padr5">Minimum</div></td>
							<td width="20%" height="20" class="borderBR"><div align="right" class="padr5">Maximum</div></td>			
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_300">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1">
				<?php
					if (isset($_GET['prmsid']))
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
								
								echo "<tr align='center' class='$class'>
										<td width='10%' height='20' class='borderBR'><div align='center'>$step_str</div></td>
										<td width='35%' height='20' class='borderBR'><div align='left' class='padl5'>$row->ProdName</div></td>
										<td width='15%' height='20' class='borderBR'><div align='left' class='padl5'>$criteria</div></td>			
										<td width='20%' height='20' class='borderBR padr5'><div align='right'>$minimum</div></td>
										<td width='20%' height='20' class='borderBR padr5'><div align='right'>$maximum</div></td>
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
	<td width="1%"></td>
	<td width="47%">
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
				&nbsp;&nbsp;
				<?php if($entType == 1)
				{
					echo "Selection";					
				}
				else
				{
					echo "Set";
				}

				?>			   
				&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;
				<?php echo $entQty; ?>
			</div></td>
		</tr>
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="10%" height="20" class="borderBR"><div align="center">&nbsp;</div></td>
							<td width="15%" height="20" class="borderBR"><div align="left" class="padl5">Item Code</div></td>
							<td width="30%" height="20" class="borderBR"><div align="left" class="padl5">Item Description</div></td>			
							<td width="10%" height="20" class="borderBR"><div align="center">Criteria</div></td>
							<td width="10%" height="20" class="borderBR"><div align="right" class="padr5">Qty/Price</div></td>	
							<td width="10%" height="20" class="borderBR"><div align="right" class="padr5">PMG</div></td>			
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_300">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1">
				<?php
					if (isset($_GET['prmsid']))
					{
						if ($rspromobuyin_ent->num_rows)
						{
							$ctr = 0;
							$parentbuyin = 0;
							
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
										$rowalt=0;
										$tmpstep = "";
										while($row_det = $rspromentitlement_details->fetch_object())
										{
											$rowalt++;
											($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
											if ($row_det->EffectivePrice > 0)
											{
												$criteria = number_format($row_det->EffectivePrice, 2, '.', '');
												$type = "Amount";											
											}
											else
											{
												$criteria = number_format($row_det->Quantity, 0, '.', '');
												$type = "Quantity";
											}
											
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
													<td width='30%' height='20' class='borderBR'><div align='left' class='padl5'>$row_det->ProdName</div></td>			
													<td width='10%' height='20' class='borderBR'><div align='center'>$type</div></td>
													<td width='10%' height='20' class='borderBR'><div align='right' class='padr5'>$criteria</div></td>
													<td width='10%' height='20' class='borderBR'><div align='right' class='padr5'>$row_det->pmgcode</div></td>
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
<br>
<table width="100%" align="left"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
		<?php
 			if ($_SESSION['ismain'] == 1)
 			{
 				/*if (($prmsdate != $today) && (($prmsdate > $today) || ($prmedate < $today)))
 				{*/
 					echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onclick='return confirmDelete();'>"; 					
 				/*}*/
 			}
		?>
	</td>			
</tr>
</table>
</form>
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