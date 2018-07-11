<?php
  
	include IN_PATH.'scbmmgt_affiliation.php';
	include IN_PATH.DS."scBMAFF.php";
	global $database;
	
	$sessionUniqueID = uniqid();
	
	$userid = $_SESSION['user_id'];
?>


<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<script type="text/javascript" src="js/jxBMAFF.js?rand=<?php echo $sessionUniqueID?>"></script>


<script type="text/javascript">

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
	var effdate   =  $("#txtStartDate").val();
	var bmfdate   =  $("#txtbmfdate").val(); 
	var bmcode    =  $("#txtbmcode").val(); 
	var txtbmname =  $("#txtbmname").val();  
	var txtstatus =  $("#txtstatus").val();  
	var txtbranch  = $("#txtbranch").val();
	
	var mes = ' BM NAME:  ' + bmcode + ' - ' + txtbmname + '\n' + 'BMF DATE:  ' + bmfdate + '\n' + 'NEW AFFILIATED BRANCH: ' + txtbranch + '\n'  + ' EFFECTIVITY DATE: ' +  effdate ;
	
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
.formwrapper{border:1px solid #FF00A6; border-top:none; padding:10px; font-weight: bold;}
.tablelisttr td{padding:5px; text-align:center; font-weight: bold; border-left:1px solid #FFA3E0;}
    .tablelisttr{background: #FFDEF0;}
    .tablelisttable{width:100%;}
    .listtr td{border-top:1px solid #FFA3E0; border-left:1px solid #FFA3E0; padding:5px;}
    .ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
    .ui-widget-overlay{height:130%;}
</style>

<form name="frmConfirmReceiptDetails" method="post" action="index.php?pageid=180.6&tid=<?php echo $_GET['tid'];?>">
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
                              	<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=71"> Sales Force Movement</a></td>
                            </tr>
						</table>
					</td>
				</tr>
			</table>
      		<br>
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                    <td class="txtgreenbold13">BM Information</td>
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
                                <td class="txtredbold">BM Information</td>
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
											    
												<input type="hidden" id="txtuserid"   name="txtuserid"   class="txtfieldLabel"  value="<?php echo $userid;   ?>"  />
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
                                        </tr>			 							            							                              
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
										 <?php
										  if($bmfdate != '')
										  {
											   $date = date("m/d/Y", strtotime($bmfdate)); 
										  }	
											
										?>	
										<tr>
                                          	<td height="20" class="txt10 fieldlabel" width = '40%'>BMC Appointment Date</td>
                                                <td class="separated">:</td>
                                          	<td height="20">
                                          		<input name="txtbmcdate" type="text" class="txtfieldLabel" id="txtbmcdate" style="width:160px;" maxlength="20" value="<?php echo $bmcdate; ?>" readonly="yes">
                                          	</td>
                                        </tr>
										<tr>
											<td width="25%" height="20" class="txt10 fieldlabel">BMF Appointment Date</td>
                                            <td class="separated">:</td>
											<td width="75%" height="20">
												<input name="txtbmfdate" type="text" class="txtfieldLabel" id="txtbmfdate" readonly="yes" value="<?php echo  $bmfdate ?>">
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
                    <td class="tabmin">&nbsp;</td>
                    <td class="tabmin2">
                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td class="txtredbold">Additional Affiliated Branches</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                    <td class="tabmin3">&nbsp;</td>
                </tr>
			    <tr>
                    <td colspan="3">
                    <div class="pgLoading"> 
                         <table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
							 <tr>
                                <td align="right" class="txt10 fieldlabel">BRANCH</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <input name="branchName" value="" class="txtfield">
                                    <input name="branch"     type="hidden" class="txtfield">
                                </td>
                            </tr> 
							<tr>
								 <td width="25%" height="20" class="txt10 fieldlabel">EFFECTIVITY DATE</td>
                                 <td class="separated">:</td>
								 <td width="75%" height="20">
									 <input name="txtStartDate" type="text" class="txtfieldLabel" style = "font-weight: bold; font-size: 200%; " id="txtStartDate" size="20" readonly="yes" value="<?php  ?>">
									 <input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
								  	 <div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
								 </td>				 
							</tr>
                         </table>
                     </div>
                     </td>
                 </tr>
      		</table>
          	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="tabmin">&nbsp;</td>
                    <td class="tabmin2">
                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td class="txtredbold">Affliation and Same Account Information</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                    <td class="tabmin3">&nbsp;</td>
                </tr>
			    <tr>
                    <td colspan="3">
                    <div class="pgLoading"> 
                         <table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
							 <tr class="tablelisttr">
							     <td>CTR</td>
								 <td>BRANCH</td>
								 <td>NATIONAL ID</td>
								 <td>ACCOUNT CODE</td>
								 <td>ACCOUNT NAME</td>
								 <td>LEVEL</td>
								 <td>STATUS</td>
								 <td>BMF DATE</td>
								 <td>ACCT. TYPE</td>
							  </tr>
							  <?php
								  $accountdetailsq =  getconsolidatednetworklist($database,$HOGeneralID) ;
								  if ($rs->num_rows)
								  {
									 while ($res = $accountdetailsq->fetch_object())
									 {
										    $ctr = $ctr + 1;
											echo '<tr class="listtr">';
											echo '<td align="right">'.$ctr.'.</td>';
											echo '<td align="center">'.$res->bcode.'</td>';
											echo '<td align="center">'.$res->HOGeneralID.'</td>';
											echo '<td align="left">'.$res->ccode.'</td>';
											echo '<td align="left">'.$res->cnamne.'</td>';
											echo '<td align="center">'.$res->leveltype.'</td>';
											echo '<td align="left">'.$res->statusid.'</td>';
											echo '<td align="left">'.$res->bmfdate.'</td>';
											echo '<td align="left">'.$res->AccountType.'</td>';
											echo '</tr>';
									 }
								  }
								  else
								  {
									  echo '<tr class="listtr">
												 <td align="center" colspan="10">No result found.</td>
											</tr>';
								  }  
											
							  ?>	
							  
							  
                         </table>
                     </div>
                     </td>
                 </tr>
      		</table>
			
			
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
  				<tr>
    				<td valign="top" class="bgF9F8F7">
                    	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                              	<td colspan="2">&nbsp;</td>
                            </tr>
        				</table>	
				    </td>
				    <td class="tabmin3">&nbsp;</td>
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
