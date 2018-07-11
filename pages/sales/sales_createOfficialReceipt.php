<?php
/*   
  @modified by John Paul Pineda
  @date June 1, 2013
  @email paulpineda19@yahoo.com         
*/

ini_set('display_errors', '1');
 
require_once(IN_PATH.DS.'scCreateOfficialReceipt.php'); 
?>

<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="../../css/calpopup.css"/>
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />

<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<script type="text/javascript" src="js/jsUtils.js"></script>
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js"></script>
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>
<script type="text/javascript" src="js/jxCreateOfficialReceipt.js"></script>

<style>
.style1 {color: #FF0000}

div.autocomplete {

  position:absolute;
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

  st-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px sod #888;
  padding:2px;  
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
  cursor:pointer;
}
</style>

<style>
<!--
.style1 {color: #FF0000}
}
-->
</style>


<script text="text/javascript">
$tpi(document).ready(function() {
  
  $tpi("#tpi_dss_sales_cycle_transactions_overlay").height($tpi(document).height());
  
  <?php if(isset($_GET['action']) && isset($_GET['or_id']) && isset($_GET['prntcnt']) && base64_decode($_GET['action'])=='confirm_and_print'): ?>load_new_tpi_window("pages/sales/prOfficialReceipt.php?orid=<?php echo $_GET['or_id']; ?>&prntcnt=0", "official_receipt", "900", "900", "yes");<?php endif; ?>
}); 		
</script>

<body onload="return enableFields();">
<form name="frmCreateOfficialReceipt" method="post" action="index.php?pageid=97&ctypeid="<?php echo $_GET['ctypeid']; ?>>
<input type="hidden" name="hCustID" value="0">
<input type="hidden" name="hMOP" value="<?php echo sizeof($_SESSION['coll_mop']); ?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<br />
    <h1 align="center"><font color="red">Note: This page is currently being updated.</font></h1>
		<input type="hidden" name="hCustomerID" id="hCustomerID" value="<?php echo $_GET['custID']; ?>">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td class="txtgreenbold13">Create Official Receipt</td>
			<td>&nbsp;</td>
		</tr>
		</table>
		<br />
		<?php if (isset($_GET['msg'])) { ?>
			<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td><span class="txtblueboldlink"><?php echo $_GET["msg"]; ?></span></td>
			</tr>
			</table>
			<br />
		<?php } ?>
    
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top">									
						<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin">&nbsp;</td>
							<td class="tabmin2">
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
								<tr>
									<td class="txtredbold">General Information </td>
									<td>&nbsp;</td>
								</tr>
								</table>
							</td>
							<td class="tabmin3">&nbsp;</td>
						</tr>
						</table>
						<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
						<tr>
							<td valign="top" class="bgF9F8F7"><div class="scroll_350">
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
								<tr>
									<td width="50%" valign="top">
										<br>
										<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1">
										<tr>
								  			<td width="20%" height="20" align="left" class="txt10 padl5">Customer Code : </td>														  	
								  			<td width="80%" height="20" class="txt10">
												<input name="txtCustomer" type="text" class="txtfield" id="txtCustomer" value="<?php if (isset($_POST['txtCustomer'])) { echo $_POST['txtCustomer']; } ?>" />
												<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
												<div id="dealer_choices" class="autocomplete" style="display:none"></div>
												<script type="text/javascript">							
												 	//<![CDATA[
				                                        var dealer_choices = new Ajax.Autocompleter('txtCustomer', 'dealer_choices', 'includes/scDealerListAjax.php', {afterUpdateElement : getDealer, indicator: 'indicator1'});																			
			                                        //]]>
												</script>
												<input name="hCOA" type="hidden" id="hCOA" value=""/>
							  				</td>
										</tr>
										<tr>
											<td height="20" align="left" class="txt10 padl5">Customer Name :</td>
											<td height="20"><input name="txtCustName" id="txtCustName" type="text" class="txtfield" size="50" maxlength="30" readonly="readonly" value="<?php if(isset($_POST['txtCustName'])){ echo $_POST['txtCustName']; }?>"></td>
										</tr>
										<tr>
											<td height="20" align="left" class="txt10 padl5">Depository Bank :</td>
											<td height="20">
												<select name="cboBankHeader" style="width:160px " class="txtfield">
													<option value="0" <?php if(isset($_POST['cboBankHeader'])) { if($_POST['cboBankHeader'] == 0) { echo "selected"; } } ?> >[SELECT HERE]</option>
													<?php
														if ($rs_bank->num_rows)
														{
															while ($row = $rs_bank->fetch_object())
															{
																if(isset($_POST['cboBankHeader']))
																{
																	if($_POST['cboBankHeader'] == $row->ID)
																	{
																		$sel = "selected";
																	}
																	else
																	{
																		$sel = "";
																	}																		
																}
																else
																{
																	if($row->IsPrimary)
																	{
																		$sel = "selected";
																	}
																	else
																	{
																		$sel = "";																		
																	}
																}
																echo "<option value='$row->ID' $sel>$row->Name</option>";																	
															}
														}
													?>
												</select>
											</td>
										</tr>
										<tr>
											<td height="20" align="left" class="txt10 padl5">Branch Name :</td>
											<td height="20"><?php echo $branchname; ?></td>
										</tr>
										<tr>
											<td height="20" align="left" class="txt10 padl5">Created By :</td>
											<td height="20"><?php echo $username; ?></td>
										</tr>
										<tr>
											<td height="20" align="left" class="txt10 padl5" valign="top">OR Type :</td>
											<td height="20">
												<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
												<tr>
													<td height="20"><input type="radio" name="rdoORType" value="1" <?php if(isset($_POST['rdoORType'])) { if($_POST['rdoORType'] == 1) { echo "checked"; } } else { echo "checked"; } ?> onClick="return enableFields();">&nbsp;Trade</td>
												</tr>
												<tr>
													<td height="20">
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														Settlement Type :
														&nbsp;
														<select name="cboSettlementType" style="width:160px " class="txtfield" onchange="hideIGS();">
															<option value="1" <?php if(isset($_POST['cboSettlementType'])) { if($_POST['cboSettlementType'] == 1) { echo "selected"; } } else { echo "selected"; } ?> >Automatic</option>
															<option value="2" <?php if(isset($_POST['cboSettlementType'])) { if($_POST['cboSettlementType'] == 2) { echo "selected"; } } ?>>Manual</option>
														</select>
													</td>
												</tr>
													<td height="20"><input type="radio" name="rdoORType" value="2" <?php if(isset($_POST['rdoORType'])) { if($_POST['rdoORType'] == 2) { echo "checked"; } } ?> onClick="return enableFields();">&nbsp;Non-Trade</td>
												</tr>
												<tr>
													<td height="20">
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														Reason Code :
														&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
														<select name="cboReason" style="width:160px " class="txtfield" >
														<?php
															$rs_reason = $sp->spSelectReason($database, 9, "");
															if ($rs_reason->num_rows)
															{
																while ($row = $rs_reason->fetch_object())
																{
																	if(isset($_POST['cboReason']))
																	{
																		if($_POST['cboReason'] == $row->ID)
																		{
																			$sel = "selected";
																		}
																		else
																		{
																			$sel = "";
																		}																		
																	}
																	else
																	{
																		$sel = "";
																	}
																	echo "<option value='$row->ID' $sel>$row->Name</option>";																	
																}
																$rs_reason->close();																
															}
														?>
														</select>
													</td>
												</tr>
												<tr class="bgF9F8F7" >
											 		<td height="20" class="txt10 padl5" >DMCM Reference No :  &nbsp;&nbsp;&nbsp;<input type="text" name="txtMemoNo" id="txtMemoNo" class="txtfield"  disabled="disabled"></td>
												
												</tr>
												</table>
											</td>
										</tr>
										</table>
									</td>
									<td valign="top" width="50%">
										<br>
										<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
										<tr>
											<td width="20%" height="20" align="left" class="txt10 padl5">Document No. :</td>
											<td width="80%" height="20"><?php echo $bir_series; ?><input name="txtDocNo" type="hidden" value="<?php echo $bir_series; ?>"></td>
										</tr>
										<tr>
											<td height="20" align="left" class="txt10 padl5">OR No. :</td>
											<td height="20"><?php echo $orno; ?><!--<input name="txtOrNo" type="text" class="txtfield" size="50" maxlength="30" readonly="readonly" value="<?php echo $orno; ?>">--></td>
										</tr>
										<tr>
											<td height="20" align="left" class="txt10 padl5">OR Date :</td>
											<td height="20">
												<?php $date = date(""); $datetoday = date("m/d/Y"); echo $datetoday; ?>
												<input name="hdnTxnDate" type="hidden" id="hdnTxnDate" value="<?php echo $datetoday; ?>">
												<!--<input type="button" class="buttonCalendar" name="anchorTxnDate" id="anchorTxnDate" value=" ">
												<div id="divTxnDate" style="background-color : white; position:absolute;visibility:hidden;"></div>-->
											</td>
										</tr>
										<tr>
											<td height="20" align="left" class="txt10 padl5" valign="top">Remarks :</td>
											<td height="20"><textarea name="txtRemarks" cols="50" rows="5" class="txtfieldnh" id="txtRemarks" wrap="off"><?php if(isset($_POST['txtRemarks'])){ echo $_POST['txtRemarks'];}?></textarea></td>
										</tr>
										<tr>
											<td height="20" colspan="2">&nbsp;</td>
										</tr>
										</table>
									</td>
								</tr>
								</table>
							</div></td>
						</tr>
						</table>
						<br />
						<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td width="50%" valign="top">
									<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
									<tr>
										<td class="tabmin">&nbsp;</td>
										<td class="tabmin2 txtredbold">Mode of Payment</td>
										<td class="tabmin3">&nbsp;</td>
									</tr>
									</table>
									<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="bordergreen" id="tbl2">
									<tr class="bgF9F8F7">
										<td>
											<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0" height="100">
											<tr class="bgF9F8F7">
												<td colspan="2" height="10">&nbsp;</td>
											</tr>
											<tr class="bgF9F8F7">
												<td width="25%" height="22" class="txt10 padl5">Type :</td>
												<td width="75%" height="22" class="txt10"><div id="MOPArea">
													<input type="hidden" name="hCustTypeID" id="hCustTypeID" value="0">
													<select name="lstType" id="lstType" style="width:160px" class="txtfield" onChange="submitForm();">
														<option value="0" <?php if(isset($_POST['lstType'])) { if($_POST['lstType'] == 0) { echo "selected"; } } else { echo "selected"; } ?>>[SELECT HERE]</option>
														<?php
															$rs_ptype = $sp->spSelectPaymentType($database);
															if ($rs_ptype->num_rows)
															{
																while ($row = $rs_ptype->fetch_object())
																{
																	if(isset($_POST['lstType']))
																	{
																		if($_POST['lstType'] == $row->ID)
																		{
																			$sel = "selected";
																		}
																		else
																		{
																			$sel = "";
																		}																		
																	}
																	else
																	{
																		$sel = "";
																	}
																	echo "<option value='$row->ID' $sel>$row->Name</option>";																	
																}
																$rs_ptype->close();																
															}
														?>
													</div></select>
													</td>
												</tr>
												<tr class="bgF9F8F7" id="divCheck1" style="display:none;">
													<td height="22" class="txt10 padl5">Bank Name :</td>
													<td height="22"><input id="txtBank" name="txtBank" type="text" class="txtfield" id="txtBank" style="width:160px" size="25" maxlength="25"></td>
												</tr>
												<tr class="bgF9F8F7" id="divCheck2" style="display:none;">
													<td height="22" class="txt10 padl5">Bank Branch :</td>
													<td height="22"><input id="txtBankBranch" name="txtBankBranch" type="text" class="txtfield" id="txtBankBranch" style="width:160px" size="25" maxlength="25"></td>
												</tr>																		
												<tr class="bgF9F8F7" id="divCheck3" style="display:none;">
													<td height="22" class="txt10 padl5">Check No. :</td>
													<td height="22"><input id="txtCheckNo"  name="txtCheckNo" type="text" class="txtfield" id="txtCheckNo" style="width:160px" size="25" maxlength="25"></td>
												</tr>																
												<tr class="bgF9F8F7" id="divCheck4" style="display:none;">
											 		<td height="22" class="txt10 padl5">Check Date :</td>
													<td height="22">
														<input name="txtCheckDate" type="text" class="txtfield" id="txtCheckDate" size="20" style="width:160px" readonly="yes" value="<?php echo $datetoday; ?>">
														<input type="button" class="buttonCalendar" name="anchorCheckDate" id="anchorCheckDate" value=" ">
														<div id="divCheckDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
													</td>
												</tr>
												<tr class="bgF9F8F7" class="bgF9F8F7" id="divDeposit1"  style="display:none;">
													<td height="22" class="txt10 padl5">Deposit Slip Validation No. :</td>
													<td height="22"><input name="txtDepSlipValNo" type="text" class="txtfield" id="txtDepSlipValNo" style="width:160px" size="25" maxlength="25"></td>
												</tr>
												<tr class="bgF9F8F7" id="divDeposit2" style="display:none;">
												 	<td height="22" class="txt10 padl5" >Date Of Deposit :</td>
													<td height="22">
														<input name="txtDepDate" type="text" class="txtfield" id="txtDepDate" size="20" readonly="yes" style="width:160px" value="<?php echo $datetoday; ?>">
														<input type="button" class="buttonCalendar" name="anchorDepDate" id="anchorDepDate" value=" ">
														<div id="divDepDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
													</td>
												</tr>
												<tr class="bgF9F8F7" class="bgF9F8F7" id="divDeposit3" style="display:none;">
													<td height="22" class="txt10 padl5">Type of Deposit :</td>
													<td height="22">
														<input type="radio" name="rdDepositType" value="1" checked>Cash
														<input type="radio" name="rdDepositType" value="2">Check
													</td>
												</tr>
												<tr class="bgF9F8F7" class="bgF9F8F7" id="divCommission1" style="display:none;">
													<td height="22" class="txt10 padl5">Account :</td>
													<td height="22">
														<select name="cboCommType" style="width:160px" class="txtfield" onChange="submitOffset();">
															<option value="0">[SELECT HERE]</option>
															<?php
																$rs_ctype = $sp->spSelectCommissionType($database);
																if ($rs_ctype->num_rows)
																{
																	while ($row = $rs_ctype->fetch_object())
																	{
																		if(isset($_POST['cboCommType']))
																		{
																			if($_POST['cboCommType'] == $row->ID)
																			{
																				$sel = "selected";
																			}
																			else
																			{
																				$sel = "";
																			}																		
																		}
																		else
																		{
																			$sel = "";
																		}
																		echo "<option value='$row->ID' $sel>$row->Name</option>";																	
																	}
																	$rs_ctype->close();																
																}
															?>
														</select>
													</td>
												</tr>
												<tr class="bgF9F8F7" class="bgF9F8F7" id="divCommission2" style="display:none;">
													<td height="22" class="txt10 padl5">Campaign :</td>
													<td height="22">
														<select name="cboCampaign" style="width:160px" class="txtfield" onChange="submitOffset();">
															<option value="0">[SELECT HERE]</option>
															<?php
																$rs_catype = $sp->spSelectCampaignMonth($database);
																if ($rs_catype->num_rows)
																{
																	while ($row = $rs_catype->fetch_object())
																	{
																		$cdate = date("M  d, Y", strtotime($row->Month));
																		if(isset($_POST['cboCampaign']))
																		{
																			if($_POST['cboCampaign'] == $row->ID)
																			{
																				$sel = "selected";
																			}
																			else
																			{
																				$sel = "";
																			}																		
																		}
																		else
																		{
																			$sel = "";
																		}
																		echo "<option value='$row->ID' $sel>$cdate</option>";																	
																	}
																	$rs_catype->close();																
																}
															?>
														</select>
													</td>
												</tr>
												<tr class="bgF9F8F7" class="bgF9F8F7" id="divCommission3" style="display:none;">
													<td height="22" class="txt10 padl5">Balance :</td>
													<td height="22"><div id="OffsetBalArea">
														<?php
															if(isset($_POST['cboCommType']))
															{
																$comm_id = $_POST['cboCommType'];
															}
															else
															{
																$comm_id = 0;
															}
															if(isset($_POST['cboCampaign']))
															{
																$camp_id = $_POST['cboCampaign'];
															}
															else
															{
																$camp_id = 0;
															}
															$custcommid = 0;
															$balance = 0;
															$rs_balance = $sp->spSelectCustomerBalanceByCommissionCampaignID($database, $_SESSION['CustomerID'], $comm_id, $camp_id);
															
															if($rs_balance->num_rows)
															{
																while($row = $rs_balance->fetch_object())
																{
																	$balance = number_format($row->OustandingBalance, 2, '.', '');
																	$custcommid = $row->ID;
																}
																$rs_balance->close();
															}
														?>
														<input name="hdnCustCommID" id="hdnCustCommID" type="hidden" value="<?php echo $custcommid; ?>">
														<input name="txtBalance" id="txtBalance" type="text" class="txtfield" readonly="yes" size="25" maxlength="25" style="width:160px" value="<?php echo $balance; ?>">
													</div></td>
												</tr>
												<tr class="bgF9F8F7" class="bgF9F8F7">
													<td height="22" class="txt10 padl5">Amount :</td>
													<td height="22"><input name="txtAmount" type="text" class="txtfield" id="txtAmount" size="25" maxlength="25" onkeyup="computeAmountDue();" style="width:160px"></td>
												</tr>
												<tr class="bgF9F8F7" class="bgF9F8F7" id="divCash1" style="display:none;">
													<td height="22" class="txt10 padl5">Amount Due :</td>
													<td height="22"><input name="txtAmountDue" type="text" class="txtfield" id="txtAmountDue" size="25" maxlength="25" onkeyup="computeChange();" style="width:160px"></td>
												</tr>
												<tr class="bgF9F8F7" class="bgF9F8F7" id="divCash2" style="display:none;">
													<td height="22" class="txt10 padl5">Change :</td>
													<td height="22"><input name="txtAmountChange" type="text" class="txtfield" id="txtAmountChange" size="25" maxlength="25" style="width:160px; text-align:left" readonly="yes" value="0.00"></td>
												</tr>
												<tr class="bgF9F8F7">
													<td>&nbsp;</td>
													<td>&nbsp;</td>
												</tr>
											</table>
										</td>
									</tr>
									</table>
									<br>
									<table width="100%"  border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td colspan="2"><div align="right"><input id="btnAddMOP" name="btnAddMOP" type="submit" class="btn" value="Add" onclick="return checkMOP(this,event,1);"></div></td>
									</tr>
									</table>
								</td>
								<td width="2%" valign="top">&nbsp;</td>
								<td width="60%" valign="top">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<br>
			<!--selected Mode Of Payment-->
			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td class="tabmin">&nbsp;</td>
				<td class="tabmin2 txtredbold">Payment Details</td>
				<td class="tabmin3">&nbsp;</td>
			</tr>
			</table>
			<table width="95%"  border="0" cellspacing="0" align="center" cellpadding="0" class="bordergreen" id="tbl3">
				<tr class="bgF9F8F7">
					<td><div class="scroll_150">
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tab">
									<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
										<tr class="txtdarkgreenbold10">
											<td width="10%" height="20" class="bdiv_r padl5">Payment Type</td>
											<td width="10%" height="20" class="bdiv_r padl5">Bank</td>
											<td width="10%" height="20" class="bdiv_r padl5">Check No.</td>
											<td width="10%" height="20" class="bdiv_r padl5">Check Date</td>
											<td width="10%" height="20" class="bdiv_r padl5">Deposit Slip No.</td>
											<td width="10%" height="20" class="bdiv_r padl5">Date Of Deposit</td>
											<td width="10%" height="20" class="bdiv_r padl5">Type Of Deposit</td>
											<td width="10%" height="20" class="bdiv_r padl5">Account-Campaign</td>
											<td width="10%" height="20" align="right" class="padr5">Amount</td>																				
										</tr>
										<?php																			
											$addedMOP = 0;
											$totamount=0;
											$count = 0;
											$rowalt = 0;
											if (sizeof($_SESSION['coll_mop'])) 
											{
												$rowalt += 1; 
												($rowalt % 2) ? $alt = "" : $alt = "bgEFF0EB";																					
												$addedMOP = sizeof($_SESSION['coll_mop']);
												$tmp_collmop1 = $_SESSION['coll_mop'];													
												for ($i=0, $n=sizeof($tmp_collmop1); $i<$n; $i++ )
												{
													$count++;
													$totamount = $totamount + $tmp_collmop1[$i]['UnappliedAmount'];
													$index = $tmp_collmop1[$i]['ctr'];
													$ptype = $tmp_collmop1[$i]['PTypeID'];
													$s_bank = $tmp_collmop1[$i]['Bank'];
													$s_checkno = $tmp_collmop1[$i]['CheckNo'];														
													$s_checkdate = $tmp_collmop1[$i]['CheckNDate'];
													$s_amount = $tmp_collmop1[$i]['Amount'];
													$s_unappamount = $tmp_collmop1[$i]['UnappliedAmount'];
													$s_branchName = $tmp_collmop1[$i]['BranchName'];
													$s_deposValidNo = $tmp_collmop1[$i]['DepositValNo'];
													$s_dteDeposit = $tmp_collmop1[$i]['DateOfDeposit'];
													$s_depositType = $tmp_collmop1[$i]['DepositType'];
													$s_account = $tmp_collmop1[$i]['Account'];
													$s_custcommid = $tmp_collmop1[$i]['CustCommID'];
													$s_accountid = $tmp_collmop1[$i]['AccountID'];
													$s_campid = $tmp_collmop1[$i]['CampaignID'];
										?>
										<tr class="<?php echo $alt; ?>">
											<td height="20" class="borderBR padl5">
													<?php
														if ($s_unappamount > 0) {
													?>
													<?php 
													if ($ptype == 1)
												 	{ 
												 		echo 'Cash'; 
												 	} 
													else if ($ptype == 2)
													{ 
														echo 'Check'; 
													}
													else if ($ptype == 3)
													{
														echo 'Deposit Slip';
													}
													else
													{
														echo 'Commissions';
													}
													?>
											</td>	
											<?php 
											
											if ($s_bank =='')
											{
												$s_bank = "N/A ";
											}
											if ($s_checkno =='')
											{
												$s_checkno = "N/A ";
											}
											if ($s_checkdate =='')
											{
												$s_checkdate = "N/A ";
											}
											if ($s_deposValidNo =='')
											{
												$s_deposValidNo = "N/A ";
											}
											if ($s_dteDeposit =='')
											{
												$s_dteDeposit = "N/A ";
											}
											
											if ($s_depositType == '')
											{
												$s_deptypeid = 0;
												$s_depositType = "N/A ";
											}
											if ($s_depositType > 0)
											{
												if($s_depositType==1)
												{
													$s_deptypeid = 1;
													$s_depositType = "Cash";
												}
												else
												{
													$s_deptypeid = 2;
													$s_depositType = "Check";
												}
											}
											if ($s_account == '')
											{
												$s_account = "N/A ";
											}
									
											echo "<input type='hidden' name='hdnPaymentType$i' value='$ptype'><input type='hidden' name='hdndepType1$i' value='$s_depositType'>
												<td height='22' class='borderBR padl5'>$s_bank<input type='hidden' name='hdnbankName$i' value='$s_bank'><input type='hidden' name='hdnbBranchName$i' value='$s_branchName'></td>
												<td height='22' class='borderBR padl5'>$s_checkno<input type='hidden' name='hdnCheckNo$i' value='$s_checkno'></td>
												<td height='22' class='borderBR padl5'>$s_checkdate<input type='hidden' name='hdnCheckDate$i' value='$s_checkdate'></td>
												<td height='22' class='borderBR padl5'>$s_deposValidNo<input type='hidden' name='hdnDsvn$i' value='$s_deposValidNo'></td>
												<td height='22' class='borderBR padl5'>$s_dteDeposit<input type='hidden' name='hdnbdteDeposit$i' value='$s_dteDeposit'></td>
												<td height='22' class='borderBR padl5'>$s_depositType<input type='hidden' name='hdndepType$i' value='$s_depositType'><input type='hidden' name='hdndepTypeId$i' value='$s_deptypeid'></td>
												<td height='22' class='borderBR padl5'>$s_account<input type='hidden' name='hdncustcomm$i' value='$s_custcommid'><input type='hidden' name='hdnaccount$i' value='$s_accountid'><input type='hidden' name='hdncampaign$i' value='$s_campid'></td>
												<td height='22' class='borderBR padr5' align='right'>".number_format($s_unappamount, 2, '.', ',')."<input type='hidden' name='txtUnapplied$i' value='$s_unappamount' style='text-align:right; width:100px;' class='txtfield'></td>";
											?>																	
										</tr>								  
										<?php
												} 
											}
											} 
											else 
											{
										?>
											<tr>
												<td colspan="9" height="20"><div align="center" class="txt10 style1"><strong>No record(s) to display.</strong></div></td>
											</tr>
										<?php } ?>
									</table>
								</td>
							</tr>
							<tr>
								<td>
									<br>
									<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
										<tr>
											<td width="38%" height="25" class="txtredbold"><input type="hidden" name="txtCountRecord" value="<?php echo $count; ?>"></td>	
											<td width="38%" height="25" class="txtredbold">&nbsp;</td>
											<td width="62%" height="25" width="30%"><div align="right" class="padr5">
												<strong>Total Amount :</strong>&nbsp;<?php echo number_format($totamount, 2, '.', ','); ?>
												<input type="hidden" name="txtMOPTotAmt" size="20" maxlength="20" readonly="yes" value="<?php echo number_format($totamount, 2, '.', ''); ?>" class="txtfield" style="text-align:right; width:100px;" />
											</div></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr class="bgF9F8F7">
								<td height="20">&nbsp;</td>
							</tr>
						</table>
					</div>
          <div id="tpi_dss_sales_cycle_transactions_overlay">
            <div id="tpi_dss_sales_cycle_transactions"></div>
          </div>
          </td>
				</tr>
			</table>
			<br>
			<!--IGS dealers-->
			<br>
			<div id="divIGS1">
			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td class="tabmin">&nbsp;</td>
				<td class="tabmin2 txtredbold">List of IGS Dealers</td>
				<td class="tabmin3">&nbsp;</td>
			</tr>
			</table>
			</div>
			<div id="divIGS2">
			<table width="95%"  border="0" cellspacing="0" align="center" cellpadding="0" class="bordergreen" id="tbl3">
			<tr class="bgF9F8F7">
				<td class="tab">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
					<tr>
						<td width="5%" height="20" align="center" class="bdiv_r">Line No.</td>
						<td width="15%" height="20" align="left" class="bdiv_r padl5">IGS Code</td>
						<td width="25%" height="20" align="left" class="bdiv_r padl5">IGS Name</td>
						<td width="15%" height="20" align="right" class="bdiv_r padr5">SI Balance</td>
						<td width="15%" height="20" align="right" class="bdiv_r padr5">Penalties</td>
						<td width="15%" height="20" align="right" class="bdiv_r padr5">Total Outstanding</td>
						<td width="10%" height="20" align="center">Amount</td>																				
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div class="scroll_150">
					<table width="100%" align="center" cellpadding="0" cellspacing="0" id="dynamicTable">
						<input type="hidden" name="hcntdynamic" id="hcntdynamic" value="1">
						<tr>
							<td width="5%" height="20" align="center" class='borderBR padl5'>1</td>
							<td width="15%" height="20" align="left" class='borderBR padl5'>
								
								<input name="txtIGSCode1" type="text" class="txtfield" id="txtIGSCode1" value="" />
								
								<span id="indicator2" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
								<div id="dealer_choices2" class="autocomplete" style="display:none"></div>
								<script type="text/javascript">				
											
											var url = "includes/scIGSListAjax.php?index=1"				
											 	//<![CDATA[								
			                                 var dealer_choices = new Ajax.Autocompleter('txtIGSCode1', 'dealer_choices2', url , {afterUpdateElement : getIGS, indicator: 'indicator2'});																			
			                                        //]]>
											</script>
								<input name="hIGSID1" type="hidden" id="hIGSID1" value=""/>
							</td>
							<td width="25%" height="20" align="left" class="borderBR padl5"><input name="txtIGSName1" type="text" class="txtfieldLabel" id="txtIGSName1"  readonly="yes"/></td>
							<td width="15%" height="20" align="right" class="borderBR padl5"><input name="txtSIBalance1" type="text" class="txtfieldLabel" id="txtSIBalance1"  readonly="yes"/></td>
							<td width="15%" height="20" align="right" class="borderBR padl5"><input name="txtPenalty1" type="text" class="txtfieldLabel" id="txtPenalty1"  readonly="yes"/></td>
							<td width="15%" height="20" align="right" class="borderBR padl5"><input name="txtOutStanding1" type="text" class="txtfieldLabel" id="txtOutStanding1"  readonly="yes"/></td>
							<td width="15%" height="20" align="right" class="borderBR padl5"><input name='txtIGSAmt1' type='text' class='txtfield' style='text-align:right; width:100px;' value='' size='20' maxlength='20' onkeyup='return calculateIGSAmt(this,event,1);'></td>
						</tr>
						
					</table>
					<table width="100%" align="center" cellpadding="0" cellspacing="0" >						
						<tr>	
							
							<td height="20" align="right" class="padr5" width="90%"><strong>Total Amount </strong></td>
							<td height="20" align="right"><input name="txtIGSTotAmt" type="text" class="txtfield" style="text-align:right; width:100px;" value="0.00" size="20" maxlength="20" readonly="yes"></td>
						</tr>
					</table>
					</div>
				</td>
			</tr>
			</table>
			</div>	
			<br>	
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
					<td width="95%" valign="top">
						<input type="hidden" name="hAddedMOP" value="<?php echo $addedMOP; ?>">
						<input type="hidden" name="hAddedSI" value="<?php echo $addedsi; ?>">
						<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td height="20" colspan="7" align="center">
								<input name="btnSave" type="submit" class="btn" value="Confirm and Print" onclick="return validateSave(this,event,1);">
								<input name="btnCancel" type="submit" class="btn" value="Cancel" onclick="return confirmCancel();">
							</td>
						</tr>
						</table>
				</td>
			</tr>
			</table>
			<?php
				if(isset($_POST['btnAddMOP']))
				{
					echo'<script language="javascript" type="text/javascript">
							loadMOP('.$_GET['ctypeid'].');
							//loadIGSDealers('.$_SESSION['CustomerID'].');
						</script>';					
				} 
			?>
		</td>
	</tr>
</table>
</form>
</body>
<br />
<!--<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtTxnDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divTxnDate",
		button         :    "anchorTxnDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>-->
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtCheckDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divCheckDate",
		button         :    "anchorCheckDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtDepDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divDepkDate",
		button         :    "anchorDepDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
