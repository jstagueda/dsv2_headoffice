<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<?php
	include IN_PATH.'scbmmgtDetails.php';
	global $database;
?>

<script type="text/javascript">
function openPopUp(obj) 
{
	var objWin;
	//var wc=prompt("Warehouse Clerk : ","");
	//var ws=prompt("Warehouse Supervisor : ","");
	
	popuppage = "pages/inventory/inv_ConfirmIssuanceofSTA_print.php?tid="+obj.value;		
		
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

function confirmCancel()
{
	if (confirm('Are you sure you want to cancel this transaction?') == false)
   	{
        return false;
   	}    
}

function confirmSave()
{
	var bmfdate   =  $("#txtStartDate").val();
	var bmcode    =  $("#txtbmcode").val(); 
	var txtbmname =  $("#txtbmname").val();  
	var txtstatus =  $("#txtstatus").val();  
	
	var mes = ' BM NAME:  ' + bmcode + ' - ' + txtbmname + '\n' + 'BMF DATE:  ' + bmfdate;
	
	if(txtstatus == 'TERMINATED' )
	{
		alert('System does not allow appoitment of BMC with terminated status .');
		return false;	
	}
	
	if ( bmfdate == '')
	{
				alert('Please input appointed date.');
				bmfdate.focus();
				return false;				
	}
		
	if (confirm('Are you sure you want to confirm this transaction?\n\n' + mes ) == false){
		return false;
	}
}
</script>

<style>
.separated{font-weight: bold; width: 5%; text-align: center;}
.fieldlabel{text-align: right; font-weight: bold;}
</style>

<form name="frmConfirmReceiptDetails" method="post" action="index.php?pageid=180.2&tid=<?php echo $_GET['tid'];?>">
<input type="hidden" name="hTxnID" value="<?php echo $_GET['tid'];?>">
<input type="hidden" name="hStatID" value="<?php echo $statid;?>">
<input type="hidden" name="hProdListID" value="<?php echo $_GET['prodlist'];?>">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
    	<td valign="top">
      		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="topnav">
                    	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                              	<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=71">Sales Force Movement</a></td>
                            </tr>
						</table>
					</td>
				</tr>
			</table>
      		<br>
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                    <td class="txtgreenbold13">BM-F Appointment Approval Details</td>
                    <td>&nbsp;</td>
                </tr>
			</table>
			<br />
 		
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="tabmin">&nbsp;</td>
                    <td class="tabmin2">
                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td class="txtredbold">BMC Information</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                    <td class="tabmin3">&nbsp;</td>
                </tr>
      		</table>
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
  				<tr>
    				<td valign="top" class="bgF9F8F7">
                    	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                              	<td colspan="2">&nbsp;</td>
                            </tr>
        					<tr>
          						<td width="40%" valign="top">
                                	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
									    <tr>
                                          	<td width="25%" height="20" class="txt10 fieldlabel">Branch</td>
                                                <td class="separated">:</td>
                                          	<td width="75%">
											    <input type="text"   id="txtbranch"   name="txtbranch"   class="txtfieldLabel" readonly="readonly" value="<?php echo $bcode; ?>" style="width:250px;" maxlength="20" />
												<input type="hidden" id="txtbranchid" name="txtbranchid" class="txtfieldLabel" readonly="readonly" value="<?php echo $BID;   ?>" style="width:250px;" maxlength="20" />
											</td>
            							</tr>  
                                        <tr>
                                          	<td width="25%" height="20" class="txt10 fieldlabel">National ID</td>
                                                <td class="separated">:</td>
                                          	<td width="75%"><input type="text" id="txtnatid" name="txtnatid" class="txtfieldLabel" readonly="readonly" value="<?php echo $HOGeneralID; ?>" style="width:250px;" maxlength="20" /></td>
            							</tr>  
										<tr>
                                          	<td height="20" class="txt10 fieldlabel">SF Level</td>
                                                <td class="separated">:</td>
                                           	<td height="20"><input name="txtlevel" type="text" class="txtfieldLabel" id="txtlevel" style="width:160px;" readonly="yes" maxlength="20" value="<?php echo $mlevel;?>"></td>
                                        </tr>
                                        <tr>
                                          	<td height="20" class="txt10 fieldlabel">BM Code</td>
                                                <td class="separated">:</td>
                                          	<td height="20">
											   <input name="txtbmcode" type="text" class="txtfieldLabel" id="txtbmcode" style="width:160px;" maxlength="20" readonly="yes" value="<?php echo $code; ?>">
											   <input name="txtbmcodedsv1" type="hidden" class="txtfieldLabel" id="txtbmcodedsv1" style="width:160px;" maxlength="20" readonly="yes" value="<?php echo $dsv1_igscode; ?>">
											</td>
                                        </tr>			 							            							                     *                   
                                        <tr>
                                          	<td height="20" class="txt10 fieldlabel">BM Name</td>
                                                <td class="separated">:</td>
                                          	<td height="20">
												<input name="txtbmname" type="text" class="txtfieldLabel" id="txtbmname" style="width:200px;" maxlength="30" value="<?php echo $Name; ?>" readonly="yes">
                                          	</td>
            							</tr>
										<tr>
                                          	<td height="20" valign="top" class="txt10 fieldlabel">Birth Date</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtbday" type="text" class="txtfieldLabel" id="txtbday" size="20" maxlength="20" readonly="yes" value="<?php echo $Birthdate; ?>"></td>
                                        </tr>
            							<tr>
                                          	<td height="20" class="txt10 fieldlabel">Network Code</td>
                                                <td class="separated">:</td>
                                          	<td height="20">
												<input name="txtmibm" type="text" class="txtfieldLabel" id="txtmibm" style="width:160px;" maxlength="20" value="<?php echo $manager_code; ?>" readonly="yes">
                                          	</td>
            							</tr>
            							<tr>
                                          	<td height="20" class="txt10 fieldlabel">M-BM Name</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtmibmname" type="text" class="txtfieldLabel" id="txtmibmname" style="width:200px;"  readonly="yes" maxlength="30" value="<?php echo $mbmname; ?>"></td>
                                        </tr> 
                                         <?php
											$date = date("");
											$datetoday = date("m/d/Y");
										?>										
										<tr>
                                          	<td height="20" class="txt10 fieldlabel" width = '40%'>BMC Appointment Date</td>
                                                <td class="separated">:</td>
                                          	<td height="20">
                                          		<input name="txtbmcdate" type="text" class="txtfieldLabel" id="txtbmcdate" style="width:160px;" maxlength="20" value="<?php echo $bmcdate; ?>" readonly="yes">
                                          	</td>
                                        </tr>
										
          							</table>
                             	</td>
          						<td width="60%" valign="top">
                                	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
                                       
										
                                        <tr>
                                          	<td height="20" valign="top" class="txt10 fieldlabel">Status</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtstatus" type="text" class="txtfieldLabel" id="txtstatus" size="20" maxlength="20" readonly="yes" value="<?php echo $stat; ?>"></td>
                                        </tr>
										
										<tr>
                                          	<td height="20" valign="top" class="txt10 fieldlabel">Payout/Offsetting</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtpay" type="text" class="txtfieldLabel" id="txtpay" size="20" maxlength="20" readonly="yes" value="<?php echo $PayoutOrOffset; ?>"></td>
                                        </tr>
										<tr>
                                          	<td height="20" valign="top" class="txt10 fieldlabel">Vatable</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtvatable" type="text" class="txtfieldLabel" id="txtvatable" size="20" maxlength="20" readonly="yes" value="<?php echo $Vatable; ?>"></td>
                                        </tr>
										<tr>
                                          	<td height="20" valign="top" class="txt10 fieldlabel">With OR</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtwithor" type="text" class="txtfieldLabel" id="txtwithor" size="20" maxlength="20" readonly="yes" value="<?php echo $withOR; ?>"></td>
                                        </tr>
										<tr>
                                          	<td height="20" valign="top" class="txt10 fieldlabel">Credit Term</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtCLT" type="text" class="txtfieldLabel" id="txtCLT" size="20" maxlength="20" readonly="yes" value="<?php echo $Duration; ?>"></td>
                                        </tr>
										<tr>
                                          	<td height="20" valign="top" class="txt10 fieldlabel">Credit Limit</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtCL" type="text" class="txtfieldLabel" id="txtCL" size="20" maxlength="20" readonly="yes" value="<?php echo $credit_limit; ?>"></td>
                                        </tr>
										<tr>
											<td width="25%" height="20" class="txt10 fieldlabel">BMF Appointment Date</td>
                                            <td class="separated">:</td>
											<td width="75%" height="20">
												<input name="txtStartDate" type="text" class="txtfieldLabel" style = "font-weight: bold; font-size: 200%; " id="txtStartDate" size="20" readonly="yes" value="<?php  ?>">
												<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
												<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
											</td>				 
										</tr>
          							</table>
                               	</td>
                            </tr>
                            <tr>
                              	<td height="18" colspan="2">&nbsp;</td>
                            </tr>
    					</table>
                   </td>
  				</tr>
			</table>
          	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
            	<tr>
                  	<td height="3" class="bgE6E8D9"></td>
                </tr>
          	</table>      
      		<br>
      		<br>
            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
                <td align="center">
                	<?php if ($statid != 7) 
					{?>
                    <input name="btnSave" type="submit" class="btn" value="Process" onClick="return confirmSave();">		
                    <?php 
					}
					else{?><!--
                      <input name="btnPrint" type="submit" class="btn" onclick="window.open('pages/inventory/inv_ConfirmIssuanceofSTA_print.php?tid=<?php echo $hdnTxnID ?>', 'print', 'width=900, height=900'); return false;" value=" Print "/>	
                    
                    -->
                    <input name="hdnTXNID" type="hidden" value="<?php echo $_GET['tid']; ?>">
                    <input name="btnPrint" type="submit" class="btn" onclick="return openPopUp(hdnTXNID)" value=" Print "/>
                    <?php } ?> 
                    <input name="btnCancel" type="submit" class="btn" value="Cancel" onClick="return confirmCancel();">
                </td>
            </tr>
            </table>
            <!-- end of details list -->
            <div id="ShowThis"></div>
            <br>
		</td>
 	</tr>
</table>
</form>

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID) bmfdate
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtbmfdate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divbmfdate",
		button         :    "anchorbmfdate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
