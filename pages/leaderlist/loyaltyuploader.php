<?php
require_once(IN_PATH.DS.'pcLoyaltyUploader.php');
	if (!isset($_GET['msglog']))
		unset($_SESSION['msg_log']);		
?>
<!--script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<!--script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<!--script src="js/jsloyalty.js" language="javascript" type="text/javascript"></script>
<!--script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<!--script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script-->
<script language = "javascript" type="text/javascript">
function openLogs() 
{

	var objWin;
	
		popuppage = "pages/leaderlist/viewloyaltylogs.php";
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','800','500','yes');
		}
		
		return false;  		
}
function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}
</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6"><?PHP include("nav.php"); ?> <br /></td><td class="divider">&nbsp;</td>
		<td valign="top" style="min-height: 610px; display: block;"><table width="100%"  border="0" cellspacing="0" cellpadding="0"></td>
		<tr><td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Leader List</span></td></tr>
		</table>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td valign="top">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1"></table>
	<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="100%" valign="top">
			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="mid_top">&nbsp;</td>
				</tr>
				<tr>
					<td class="mid2_top"><table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0"><tr><td><span class="txtgreenbold13">Uploader</span></td><td>&nbsp;</td></tr></table></td>
				</tr>
			</table>
			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1"><tr><td align="center" id="progressbar">&nbsp;</td></tr></table>
			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" >
				<tr><td class="cornerUL"></td><td class="corsidesU"></td><td class="cornerUR"></td></tr>
				<tr>
				<td valign="top" class="bgFFFFFF">
					<table width="60%"  border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td class="tabmin">&nbsp;</td><td class="tabmin2"><span class="txtredbold">Loyalty Uploader</span>&nbsp;</td><td class="tabmin3">&nbsp;</td>
					</tr>
					</table>
					<form name="frmPackingListUpload" method="post" enctype="multipart/form-data" action="">
						<table width="60%"  border="0" cellpadding="0" cellspacing="0" class="bordergreen">
							<?php 	if ($message != ""){  ?>
										<tr>
											<td height='20' colspan = '2' class='bgF9F8F7' align='right'> <div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'><?php echo $message; ?></div></td>
										</tr>
							<?php  } else if($errormessage != ""):	?>
										<tr>
											<td height='20' colspan = '2' class='bgF9F8F7' align='right'><div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'><?php echo $errormessage; ?></div></td>
										</tr>
							<?php 	endif; ?>
							<tr>
								<td width="20%" height="20" class="bgF9F8F7">&nbsp;<span class="txtwhitebold">&nbsp;</span></td>
								<td width="80%" height="20" class="bgF9F8F7">&nbsp;</td>
							</tr>
								<tr>
									<td height="20" class="bgF9F8F7" align="right">&nbsp;<div align="right" class="txt10">Buyin Requirement File :</div></td>
									<td height="20" class="bgF9F8F7">&nbsp;<input type="hidden" name="MAX_FILE_SIZE" value="1073741824" /><input type="file" name="loyalbr" class="btn" id = "loyaltyfile"></td>
								</tr>
								<tr>
									<td height="20" class="bgF9F8F7" align="right">&nbsp;<div align="right" class="txt10">Loyalty Entitlement File:</div></td>
									<td height="20" class="bgF9F8F7">&nbsp;<input type="hidden" name="MAX_FILE_SIZE" value="1073741824" /><input type="file" name="loyalent" class="btn" id = "loyaltyfile"></td>
								</tr>
								<tr>
									<td height="20" class="bgF9F8F7">&nbsp;</td><td height="20" class="bgF9F8F7">&nbsp;<input name="btnUpload" type="submit" class="btn" value="Upload" onClick = "return form_validation( );"></td>
								</tr>
							
							<tr> 
							<td height="20" colspan="2" align="center" class="txtred">RIGHT FORMAT FOR LOYALTY UPLOADS.</td>
							</tr>
							<form name="frmLeaderListUploader" method="post" enctype="multipart/form-data" action="pages/leaderlist/datamgt_lluploader_forms.php">
							<tr>
							<td class="bgF9F8F7" height="20" align="center"  colspan="7" class="bgF9F8F7">&nbsp;
								<a href="pages/leaderlist/loyalty_lluploader_forms.php?buyin" target="_blank">Loyalty Buyin Requirements</a> |
								<a href="pages/leaderlist/loyalty_lluploader_forms.php?entitlement" target="_blank">Loyalty Entitlement Requirements</a> <br />	
								FOLLOW THE CORRECT FORMAT FOR LOYALTY UPLOADS.</td> 
							</tr> 
							</form>

								
								<tr><td colspan="3" class="bgF9F8F7" height="20">&nbsp;</td></tr>						
						</table>
					</form>
					<br />
					<?php if(isset($logs_result)): ?>
					<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0'>
						<tr><td class='tabmin'>&nbsp;</td><td class='tabmin2'><span class='txtredbold'>Logs: </span>&nbsp;</td><td class='tabmin3'>&nbsp;</td></tr>
					</table>
					<div class='scroll_300h'>
					<table width='100%'  border='0' cellpadding='0' cellspacing='1'>
						<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='1' class='bordergreen' id='tbl2'>
							<tr><td valign='top' class='bgFFFFFF'></td></tr>
							<tr>
								<td>
									<div class='scroll_300'>
										<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='bgFFFFFF'>
										<tr>
										<td height='20' colspan = '2' class='bgFFFFFF' align='right'>
										<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>
										<?php echo "Total Loyalty Buyin Requirement File: ".$buyinCount ?></div></td>
										</tr>
										<tr>
										<td height='20' colspan = '2' class='bgFFFFFF' align='right'>
										<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>
										<?php echo "Total Loyalty Entitlement File: ".$entitlementcount ?></div></td>
										</tr>										
										<?php 
												if($logs_result->num_rows): 
												while($row = $logs_result->fetch_object()): ?>
												<tr>
													<td height='20' colspan = '2' class='bgFFFFFF' align='right'><div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'><?php echo "File Not Upload: ".$row->PromoCode." - Product Code: ".$row->ProductCode; ?></div></td>
												</tr>	
										<?php endwhile; 
										   endif; ?>
										</table>
									</div>
								</td>
							</tr>
						</table>
					</table> 
					</div>
					<?php endif; ?>
					<input type = "submit" value = "Print Logs" onclick = "return openLogs();" class = "btn">
			</table>
			<table width="100%"  border="0" cellspacing="0" cellpadding="0"><tr><td height="20" class="bgEFEDE9">&nbsp;</td></tr></table>
			</td>
				<td class="corsidesR">&nbsp;</td>
			</tr>
			<tr>
				<td class="cornerBL"></td>
				<td class="corsidesB"></td>
				<td class="cornerBR"></td>
			</tr>
		</table>
		</td>
		</tr>
	</table>
		</td>
	</tr>
</tr>
 </table>

