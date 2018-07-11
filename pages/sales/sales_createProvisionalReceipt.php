<?php
/*   
  @modified by John Paul Pineda
  @date June 1, 2013
  @email paulpineda19@yahoo.com         
*/

ini_set('display_errors', '1');

require_once(IN_PATH.DS.'scCreateProvisionalReceipt.php');
?>

<!-- Calendar Stylesheet starts here... -->
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<!-- Calendar Stylesheet ends here... -->

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>

<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<script type="text/javascript" src="js/jsUtils.js"></script>
<script type="text/javascript" src="js/prototype.js"></script>
<script type="text/javascript" src="js/scriptaculous.js"></script>
<script type="text/javascript" src="js/jxCreateProvisionalReceipt.js"></script>

<style>
.style1 { color:#FF0000; }

div.autocomplete {

  position:absolute;
  /*width:300px;*/
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px; }
 
div.autocomplete ul {

  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size:10px;  
}

div.autocomplete ul li.selected { background-color:#ffb; }

div.autocomplete ul li {

  st-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px sod #888;
  padding:2px;
  /*height:20px;*/
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size:10px;
  cursor:pointer;
}
</style>

<script type="text/javascript">
$tpi(document).ready(function() {
  
  $tpi("#tpi_dss_sales_cycle_transactions_overlay").height($tpi(document).height());  
});
 
function MM_jumpMenu(targ,selObj,restore) {
 	
	var docno = document.frmTransferDetails.txtDocNo.value;
	var custID = document.frmTransferDetails.hCOA.value;
	var remarks = document.frmTransferDetails.txtRemarks.value;
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"&docNo=" + docno+ "&custID=" + custID+ "&remarks=" + remarks + "#MMHead" +"'");
  	if (restore) 
	  		selObj.selectedIndex=0;
}

function hideMe(a) {
  	
	if (a == 2)
	{
		document.getElementById('trchkbank').style.display = ''; 
		document.getElementById('trchkbrbank').style.display = ''; 
		document.getElementById('trchkno').style.display = '';
		document.getElementById('trchkdate').style.display = '';
		document.getElementById('trdsvn').style.display = 'none';
		document.getElementById('trdepdate').style.display = 'none';
		document.getElementById('trcc').style.display = 'none';
	}
	if (a == 1)
	{
		document.getElementById('trchkbank').style.display = 'none'; 
		document.getElementById('trchkbrbank').style.display = 'none'; 
		document.getElementById('trchkno').style.display = 'none';
		document.getElementById('trchkdate').style.display = 'none';
		document.getElementById('trdsvn').style.display = 'none';
		document.getElementById('trdepdate').style.display = 'none';
		document.getElementById('trcc').style.display = 'none';
		
	}
	//for depositSlip
	if (a == 3)
	{
		document.getElementById('trdsvn').style.display = '';
		document.getElementById('trdepdate').style.display = '';
		document.getElementById('trcc').style.display = '';
		document.getElementById('trchkbank').style.display = 'none'; 
		document.getElementById('trchkbrbank').style.display = 'none'; 
		document.getElementById('trchkno').style.display = 'none';
		document.getElementById('trchkdate').style.display = 'none';
	}
}
</script>

<style>
<!--
.style1 { color:#FF0000; }
-->
</style>

<form name="frmCreateProvisionReceipt" method="post" action="index.php?pageid=50">
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
			<br>
			<input type="hidden" name="hCustomerID" id="hCustomerID" value="<?php echo $_GET['custID']; ?>">
			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td class="txtgreenbold13">Create Provisional Receipt</td>
					<td>&nbsp;</td>
				</tr>
			</table>
			<br />
			<?php if (isset($_GET['msg'])) { ?>
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td><span class="txtredsbold"><?php echo $_GET["msg"]; ?></span></td>
					</tr>
				</table>
				<br />
			<?php } ?>
			<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td valign="top">
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
							
								<td width="73%" valign="top">									
									<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
										<tr>
											<td class="tabmin">&nbsp;</td>
											<td class="tabmin2">
												<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
													<tr>
														<td class="txtredbold">General Information </td>
														<td>
															<table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
																<tr>
																	<td>&nbsp;</td>
																	<td width="15"><a href="#" onclick='unhideit("tbl1"); return false;' ><img src="../../images/max.gif" width="11" height="11" class="btnnone"></a></td>
																	<td width="12"><a href="#" onclick='hideit("tbl1"); return false;' ><img src="../../images/min.gif" width="11" height="11" class="btnnone"></a></td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
											<td class="tabmin3">&nbsp;</td>
										</tr>
									</table>
									<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
										<tr>
											<td valign="top" class="bgF9F8F7"><div class="scroll_350">
												<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
													<tr>
														<td width="50%" valign="top">
														<br>
															<table width="55%"  border="0" align="left" cellpadding="0" cellspacing="1">
																<tr>
																    <td height="20"  width="30%"class="txt10">Customer Code</td>	
														  			<td height="20"  width="3%" class="txt10"> : </td>														  	
														  			<td height="20" class="txt10">
														  			<?php 
														  			
														  			
														  			if (isset($_GET['custName']))
														  			{
														  				$tmpName =  $_GET['custName']; 	
														  			}
														  			else
														  			{
														  				$tmpName='';
														  			}
														  			
														  			 ?>
																	<input name="txtCustomer" type="text" class="txtfield" id="txtCustomer" value="<?php echo $hdnCode;?>" style="width: 220px;" />
																	<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
																	<div id="dealer_choices" class="autocomplete" style="display:none"></div>
																	<script type="text/javascript">							
																		 //<![CDATA[
										                                        var dealer_choices = new Ajax.Autocompleter('txtCustomer', 'dealer_choices', 'includes/scDealerListAjax.php', {afterUpdateElement : getDealer, indicator: 'indicator1'});																			
										                                        //]]>
																	</script>
																	<input name="hCOA" type="hidden" id="hCOA" value="<?php echo $hdnID;?>" />
													  				</td>
																</tr>
															
																<tr>
																	<td height="20" width="30%" class="txt10">Customer Name</td>	
																	<td height="20" width="3%" class="txt10">: </td>
																	<td height="20"><input type="text" name="txtDealerName" size="50" class="txtfield"  value="<?php echo $hdnName;?>" style="width: 220px;" readonly="yes"></td>
																</tr>
																<tr>
																	<td height="20"  class="txt10">IBM Code-Name</td>	
																	<td height="20" class="txt10"> :</td>
																	<td height="20"><input type="text" name="txtIBMCode" size="50" class="txtfield"  value="<?php echo $hdnIBMCode;?>" style="width: 220px;"  readonly="yes"></td>
																</tr>
																<tr>
																	<td height="20" width="15%" class="txt10" valign="top">Remarks</td>
																	<td height="20" width="3%" valign="top">:</td>
																	<?php if (isset($_GET['remarks']))
														  			{
														  				$tmpRemarks =  $_GET['remarks']; 	
														  			}
														  			else
														  			{
														  				$tmpRemarks='';
														  			}?>
																	<td height="20" width="89%" ><textarea name="txtRemarks" cols="30" rows="4" class="txtfieldnh" id="textarea" wrap="off" style="width: 220px;" ><?php echo $hdnRemarks;?></textarea></td>
																</tr>
																<tr>
																	<td colspan="3" height="10">&nbsp;</td>
																</tr>
															</table>
														</td>
														<td valign="top" width="50%">
															<br>
															<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
																<tr>
																    <td height="20" width="15%" class="txt10">PR No.</td>	
																    <td height="20" width="2%">:</td>
																	<td height="20" width="78%" class="txt10"><?php echo $prno; ?><input name="txtOrNo" type="hidden" value="<?php echo $prno; ?>"></td>
																	
																</tr>
																<tr>
																	<td height="20" class="txt10">PR Date </td>
																	<td height="20" class="txt10">:</td>
																	<td height="20" class="txt10">
																		<?php
																			$date = date("");
																			$datetoday = date("m/d/Y");
																			echo $datetoday;
																		?>
																		<input name="txtTxnDate" type="hidden" id="txtTxnDate" value="<?php echo $datetoday; ?>">
																	</td>
																</tr>
																<tr>
																    <td height="20" class="txt10">Document No.</td>	
																	<td height="20" class="txt10">:</td>
																	<td height="20" class="txt10"><?php echo $bir_series;?><input name="txtDocNo" type="hidden" value="<?php echo $bir_series;?>"></td>
																</tr>
																<tr>
																	<td height="20" class="txt10">Branch Name</td>	
																	  <td height="20" class="txt10">:</td>
																	<td height="20" class="txt10"><?php echo $branchName;?>	</td>
																</tr>
																<tr>
																	<td height="20" class="txt10">Created By</td>	
																	<td height="20" class="txt10">:</td>
																	<td height="20" class="txt10"><?php echo $userName;?></td>
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
											<td width="100%" valign="top">
												<table width="50%"  border="0" cellpadding="0" cellspacing="0">
													<tr>
														<td class="tabmin">&nbsp;</td>
														<td class="tabmin2">
															<table width="50%"  border="0" cellpadding="0" cellspacing="1">
																<tr>
																	<td class="txtredbold">Mode of Payment</td>
																</tr>
															</table>
														</td>
														<td class="tabmin3">&nbsp;</td>
													</tr>
												</table>
												<table width="50%"  border="0" cellspacing="0" cellpadding="0" class="bordergreen" id="tbl2">
													<tr class="bgF9F8F7">
														<td>
															<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0" height="100">
																	<tr class="bgF9F8F7">
																		<td width="3%">&nbsp;</td>
																		<td width="10%">&nbsp;</td>
																		<td height="20" width="3%" ></td>
																		<td width="47%">&nbsp;</td>
																	</tr>
																	<tr class="bgF9F8F7" id="trchkbank">
																	    <td width="3%" height="20" class="txt10"></td>
																		<td height="20" class="txt10" width="10%">Type</td>
																		<td height="20" width="3%" >:</td>
																		<td>
																			<input id="txtPaymentType" name="txtPaymentType" type="text" class="txtfield"   readonly="readonly" value="Check" size="25" maxlength="25">
																			<input name="hdnPtype" type="hidden" value="2">
																		</td>
																	</tr>
																	<tr class="bgF9F8F7" id="trchkbank">
																	    <td width="3%" height="20" class="txt10"></td>
																		<td height="20" class="txt10" width="10%">Bank Name</td>
																		<td height="20" width="3%" >:</td>
																		<td>
																			<input name="txtBank" type="text" class="txtfield" id="txtBank"  size="25" maxlength="25">
																		</td>
																	</tr>
																	<tr class="bgF9F8F7" id="trchkbrbank" >
																	 <td width="3%" height="20" class="txt10"></td>
																		<td height="20" class="txt10" width="10%">Bank Branch</td>
																		<td height="20" width="3%" >:</td>
																		<td>
																			<input name="txtBankBranch" type="text" class="txtfield" id="txtBankBranch"  size="25" maxlength="25">
																		</td>
																	</tr>																		
																	<tr class="bgF9F8F7" class="bgF9F8F7" id="trchkno" >
																	 <td width="5%" height="20" class="txt10"></td>
																		<td height="20" class="txt10" width="10%">Check No.</td>
																		<td height="20" width="3%" >:</td>
																		<td>
																			<input name="txtCheckNo" type="text" class="txtfield" id="txtCheckNo" size="25" maxlength="25">
																		</td>
																	</tr>																
																	<tr class="bgF9F8F7" id="trchkdate"  >
																	 <td height="20" width="3%" class="txt10"></td>
																	 <td height="20" class="txt10" width="10%" >Check Date</td>
																	 <td height="20" width="3%" >:</td>
																		<td>
																			<input name="txtCheckDate" type="text" class="txtfield" id="txtCheckDate" size="20" readonly="yes" value="<?php echo $datetoday; ?>">
																			<input type="button" class="buttonCalendar" name="anchorCheckDate" id="anchorCheckDate" value=" ">
																			<div id="divCheckDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
																		</td>
																	</tr>
																	
																	
																<!--
																<?php if ($_GET['ptypeID'] == 2) { ?>
																<?php } ?>
																<input type="hidden" id="hMOP" name="hMOP" value="<?php echo $mop; ?>">
																-->
																
																<tr class="bgF9F8F7" class="bgF9F8F7" id="trchkno" >
																    <td height="20" width="3%" class="txt10"></td>
																	<td height="20" width="10%" class="txt10">Amount</td>
																	<td height="20" width="3%" >:</td>
																	<td><input name="txtAmount" id="txtAmount" type="text" class="txtfield" id="txtAmount" size="25" maxlength="25"></td>
																</tr>
																<tr class="bgF9F8F7">
																<td height="20" class="txt10"></td>
																<td height="20"  ></td>
																<td height="20" width="3%" ></td>
																	<td colspan="2"></td>
																</tr>
																
															</table>
														</td>
													</tr>
												</table>
											</td>
											<td width="2%" valign="top">&nbsp;</td>
											<td width="60%" valign="top">
												
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<br>
						<table width="50%"  border="0"  cellpadding="0" cellspacing="0">
						<tr>
						<td>
						<div align="right"><input id="btnAddMOP" name="btnAddMOP" type="submit" class="btn" value="Add" onclick="return checkMOP();"></div>
						</td>
						</tr>
						</table>
						<br>
						<!--selected Mode Of Payment-->
						<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
													<tr>
														<td class="tabmin">&nbsp;</td>
														<td class="tabmin2">
															<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="1">
															<tr>
																<td class="txtredbold">Selected Mode of Payment</td>
															</tr>
															</table>
														</td>
														<td class="tabmin3">&nbsp;</td>
													</tr>
												</table>
												<table width="95%"  border="0" cellspacing="0" align="center" cellpadding="0" class="bordergreen" id="tbl3">
													<tr class="bgF9F8F7">
														<td>
														<br>
															<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" height="100">
																<tr>
																	<td>
																		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
																			<tr class="bgE6E8D9">
																				<td width="10%" height="20" class="txtredbold">Payment Type</td>
																				<td width="10%" height="20" class="txtredbold">Bank</td>
																				<td width="10%" height="20" class="txtredbold">CheckNo</td>
																				<td width="10%" height="20" class="txtredbold">Check Date</td>
																				<td width="10%" height="20" align="right" class="txtredbold">Amount</td>
																				<input type='hidden' name='counter' value='<?php echo sizeof($_SESSION['coll_mop']);?>'>																				
																			</tr>
																			<?php																			
																				$addedMOP = 0;
																				$totamount=0;
																				$count = 0;
																				
																				if (isset($_SESSION['coll_mop']))
																				if (sizeof($_SESSION['coll_mop'])) 
																				{																					
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
																						$s_branchName = $tmp_collmop1[$i]['BranchName'];
																						$s_unappamount = $tmp_collmop1[$i]['UnappliedAmount'];
																					
																						
																						echo "<td height='10'><input type='hidden' name='hdndepType1$i' value='$s_depositType'></td>";
																						
																			?>
																			<tr>
																		
																				
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
																				
																				echo "<td  height='10'><input type='hidden' name='hdnPaymentType$i' value='2'>Check</td>";
																				echo "<td height='10'>$s_bank<input type='hidden' name='hdnbankName$i' value='$s_bank'><input type='hidden' name='hdnbBranchName$i' value='$s_branchName'></td>";
																				echo "<td height='10'>$s_checkno<input type='hidden' name='hdnCheckNo$i' value='$s_checkno'></td>";
																				echo "<td height='10'>$s_checkdate<input type='hidden' name='hdnCheckDate$i' value='$s_checkdate'></td>";
																				echo "<td height='10' align='right'>".number_format($s_amount, 2, '.', ',')."<input type='hidden' name='txtUnapplied$i' value='$s_amount' style='text-align:right' class='txtfield' readonly='yes'>&nbsp;</td>";
																				    

																				
																				
																				?>																	
																			</tr>								  
																			<?php
																					} 
																				} else {
																			?>
																				<tr>
																					<td colspan="5" height="20"><div align="center" class="txt10 style1"><strong>No record(s) to display.</strong></div></td>
																				</tr>
																			<?php } ?>
																		</table>
																	</td>
																</tr>
																<tr>
																	<td>
																		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
																			<tr>
																			<?php echo "<td width='38%' height='20' class='txtredbold'><input type='hidden' name='txtCountRecord' value='$count'></td>";?>	
																				<td width="39%" height="20" class="txtredbold">&nbsp;</td>
																				<td width="62%" height="20" width="30%"><div align="right">
																					<strong>Total Amount </strong>&nbsp;<?php echo number_format($totamount, 2, '.', ','); ?>
																					<input type="hidden" name="txtMOPTotAmt" size="20" maxlength="20" readonly="yes" value="<?php echo number_format($totamount, 2, '.', ''); ?>" class="txtfield" style="text-align:right;" />
																				</div></td>
																			</tr>
																		</table>
																	</td>
																</tr>
																<tr class="bgF9F8F7">
																	<td height="20">&nbsp;</td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
						
            <div id="tpi_dss_sales_cycle_transactions_overlay">
              <div id="tpi_dss_sales_cycle_transactions"></div>
            </div>
            
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td width="95%" valign="top">
									
									
						<br />
						<input type="hidden" name="hAddedMOP" value="<?php echo $addedMOP; ?>">
						<input type="hidden" name="hAddedSI" value="<?php echo $addedsi; ?>">
						<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td height="20" colspan="5" align="center">
									<input name="btnSave" type="submit" class="btn" value="Save" onclick="return validateSave();">
									<input name="btnCancel" type="submit" class="btn" value="Cancel" >
								</td>
							</tr>
						</table>
						<br>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>

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