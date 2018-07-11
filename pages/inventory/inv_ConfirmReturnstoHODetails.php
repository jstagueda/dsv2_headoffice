<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<?php
	include IN_PATH.'scConfirmReturnstoHODetails.php';
	global $database;
?>

<script type="text/javascript">
function RemoveInvalidChars(strString, cnt)
{
	var iChars = "1234567890";
   	var strtovalidate = strString.value;
	var strlength = strtovalidate.length;
	var strChar;
	var ctr = 0;
	var newStr = '';
	
	if (strlength == 0)
	{
		return false;
	}

	for (i = 0; i < strlength; i++)
	{	
		strChar = strtovalidate.charAt(i);
		if 	(!(iChars.indexOf(strChar) == -1))
		{
			newStr = newStr + strChar;
		}
	}
	
	strString.value = newStr;
	var a = eval('document.frmConfirmReceiptDetails.hdnQuantity' + cnt);
	var b = eval('document.frmConfirmReceiptDetails.txtdescr' + cnt);
	var c = eval('document.frmConfirmReceiptDetails.cboReasons' + cnt);
	var d = eval('document.frmConfirmReceiptDetails.txtActQuantity' + cnt);
	var e = eval('document.frmConfirmReceiptDetails.hdnSOH' + cnt);
	
	var actqty = parseFloat(strString.value);
    var loadedqty = parseFloat(a.value);
    var sohqty = e.value;
    
    if ((actqty > loadedqty) || (sohqty < actqty))
	{
		alert('Actual Quantity cannot be greater than the Loaded Quantity or SOH balance.');
		d.value = a.value;
		b.value = '0';
		c.disabled = true;	
		document.frmConfirmReceiptDetails.btnCancel.focus();
			
		return false;
	}

	var x = actqty - loadedqty;
	//var x = parseFloat(strString.value) - parseFloat(a.value);
	
	b.value = x;
	if(x != 0)
	{
		c.disabled = false;
	}
	else
	{
		c.disabled = true;
	}
	return true;
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
	ml = document.frmConfirmReceiptDetails;
	
	if (ml.hdncnt.length > 1)
	{
		for(i = 0; i < ml.hdncnt.length; i++)
		{
			var ctr = i + 1;
			var desc = eval('document.frmConfirmReceiptDetails.txtdescr' + ctr);
			var reason = eval('document.frmConfirmReceiptDetails.cboReasons' + ctr);
			var ActQuantity = eval('document.frmConfirmReceiptDetails.txtActQuantity' + ctr)
			
			if(ActQuantity.value == ""){
				alert('Actual Quantity required');
				return false;
			}
			
			if (desc.value > 0 || desc.value < 0)
			{
				if (reason.value == 0)
				{
					alert('Reason Code required.');
					reason.focus();
					return false;
				}				
			}
			
			
		}		
	}
	else
	{
		var desc = eval('document.frmConfirmReceiptDetails.txtdescr1');
		var reason = eval('document.frmConfirmReceiptDetails.cboReasons1');
		
		if (desc.value > 0 || desc.value < 0)
		{
			if (reason.value == 0)
			{
				alert('Reason Code required.');
				reason.focus();
				return false;
			}				
		}				
	}
	
	if (confirm('Are you sure you want to confirm this transaction?') == false)
	{
		return false;
	}    
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp(obj) 
{
	var objWin;
	//var wc=prompt("Warehouse Clerk : ","");
	//var ws=prompt("Warehouse Supervisor : ","");
	
	popuppage = "pages/inventory/inv_ConfirmReturnstoHO_print.php?tid="+obj.value;		
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','800','500','yes');
		}
		
		return false;  		
}
</script>
<style>
.separated{font-weight: bold; width: 5%; text-align: center;}
.fieldlabel{text-align: right; font-weight: bold;}
</style>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<form name="frmConfirmReceiptDetails" method="post" action="index.php?pageid=31.1&tid=<?php echo $_GET['tid'];?>&prodlist=<?php echo $_GET['prodlist'];?>">
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
                              	<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory Cycle Main</a></td>
                            </tr>
						</table>
					</td>
				</tr>
			</table>
      		<br>
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                    <td class="txtgreenbold13">Confirm Returns to Head Office</td>
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
                                <td class="txtredbold">General Information</td>
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
                                      	<td width="25%" height="20" class="txt10 fieldlabel">Movement Type</td>
                                        <td class="separated">:</td>
                                      	<td width="75%" height="20"><input type="text" id="txtInvType" name="txtInvType" class="txtfieldLabel" readonly="readonly" value="<?php echo $invtype; ?>" style="width:220px;" maxlength="20" /></td>
        							</tr> 	
                                    <tr>
                                      	<td height="20" class="txt10 fieldlabel">STA No.</td>
                                        <td class="separated">:</td>
                                      	<td height="20"><input name="txtDocNo" type="text" class="txtfieldLabel" id="txtDocNo" style="width:160px;" maxlength="20" readonly="yes" value="<?php echo $docno; ?>"></td>
                                    </tr>			 							            							                                        
        							<tr>
                                      	<td height="20" class="txt10 fieldlabel">Delivery Date</td>
                                        <td class="separated">:</td>
                                       	<td height="20"><input name="txtShipmentDate" type="text" class="txtfieldLabel" id="txtShipmentDate" style="width:160px;" readonly="yes" maxlength="20" value="<?php echo $shipdate;?>"></td>
                                    </tr>
                                    <tr>
                                      	<td height="20" class="txt10 fieldlabel">Issuing Branch</td>
                                        <td class="separated">:</td>
                                      	<td height="20">
                                      		<input type="hidden" name="hdnSourceWarehouseID" value="<?php echo $sbrancid; ?>"/>
											<input name="txtWarehouse" type="text" class="txtfieldLabel" id="txtSWarehouse" style="width:160px;" maxlength="20" value="<?php echo $sbranch; ?>" readonly="yes">
                                      	</td>
        							</tr>
        							<tr>
                                      	<td height="20" class="txt10 fieldlabel">Receiving Branch</td>
                                        <td class="separated">:</td>
                                      	<td height="20">
                                      		<input type="hidden" name="hdnDestinationWarehouseID" value="<?php echo $bbrancid; ?>"/>
											<input name="txtWarehouse" type="text" class="txtfieldLabel" id="txtDWarehouse" style="width:160px;" maxlength="20" value="<?php echo $rbranch; ?>" readonly="yes">
                                      	</td>
        							</tr>
        							<tr>
                                      	<td height="20" class="txt10 fieldlabel">Total Loaded Quantity</td>
                                        <td class="separated">:</td>
                                      	<td height="20"><input name="txtLoadedQty" type="text" class="txtfieldLabel" id="txtLoadedQty" size="20" readonly="yes" maxlength="20" value="<?php echo $rs_detailsall->num_rows; ?>"></td>
                                    </tr>    
          							</table>
                             	</td>
          						<td width="60%" valign="top">
                                	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
                                        <?php
											$date = date("");
											$datetoday = date("m/d/Y");
										?>
										<tr>
	                                      	<td height="20" class="txt10 fieldlabel">Reference No.</td>
                                                <td class="separated">:</td>
	                                      	<td height="20"><input name="txtRefNo" type="text" class="txtfieldLabel" id="txtRefNo" style="width:160px;" maxlength="20" value="<?php echo $txnno; ?>" readonly="yes"></td>
	                                    </tr>
                                        <tr>
                                          	<td width="20%" height="20" class="txt10 fieldlabel">Transaction Date</td>
                                                <td class="separated">:</td>
                                          	<td width="80%" height="20">
                                          		<input name="startDate" type="text" class="txtfieldLabel" id="startDate" size="20" readonly="yes" value="<?php if($statid == 6){ echo $datetoday;}else{echo $txndate;} ?>"></td>
                  								<!--<input type="button" class="buttonCalendar" name="anchorTxnDate" id="anchorTxnDate" value=" " <?php if ($statid == 7) {?> disabled <?php } ?>>
                  								<div id="divTxnDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>-->
                                        </tr>
                                     	<tr>
                                          	<td height="20" class="txt10 fieldlabel">Branch Name</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtBranch" type="text" class="txtfieldLabel" id="txtBranch" size="20" maxlength="20" readonly="yes" value="<?php echo $rbranch; ?>"></td>
                                        </tr>
                                        <?php
                                        	if ($statid == 7)
                                        	{
                                        ?>
                                        <tr>
                                          	<td height="20" valign="top" class="txt10 fieldlabel">Confirmed By</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtConfirmedBy" type="text" class="txtfieldLabel" id="txtConfirmedBy" size="20" maxlength="20" readonly="yes" style="width:220px;" value="<?php echo $confirmedby; ?>"></td>
                                        </tr>
                                        <?php
                                        	}
                                    	?>
                                    	<tr>
                                          	<td height="20" valign="top" class="txt10 fieldlabel">Status</td>
                                                <td class="separated">:</td>
                                          	<td height="20"><input name="txtStatus" type="text" class="txtfieldLabel" id="txtStatus" size="20" maxlength="20" readonly="yes" value="<?php echo $status; ?>"></td>
                                        </tr>
                                        <tr>
                                          	<td height="20" valign="top" class="txt10 fieldlabel">Remarks</td>
                                                <td class="separated" valign="top">:</td>
                                          	<td height="20"><textarea name="txtRemarks" cols="40" rows="3" class="txtfieldnh" id="txtRemarks" <?php if ($statid == 7) {?> readonly="yes" <?php } ?>><?php echo $remarks; ?></textarea></td>
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
            <!-- START PRODUCT LIST -->
            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="tabmin">&nbsp;</td>
                    <td class="tabmin2">
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td class="txtredbold">Product Details</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                    <td class="tabmin3">&nbsp;</td>
                </tr>
            </table>
           <!-- Details -->
            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
                <tr>
                    <td colspan="2" valign="top" class="bgF9F8F7">
                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
	                       <tr>
	                            <td style="border-bottom: 2px solid #FFA3E0;">
	                                <table width="100%"  border="0" cellpadding="0" cellspacing="" class="txtdarkgreenbold10 " height="25">
	                                <tr align="center">
	                                    <td width="5%" class="bdiv_r">Line No.</td>
	                                    <td width="15%" class="bdiv_r">Item Code</td>
	                                    <td width="25%" class="bdiv_r">Item Description</td>
	                                    <td width="10%" class="bdiv_r">UOM</td>
	                                    <td width="10%" class="bdiv_r">Loaded Quantity</td>
	                                    <td width="10%" class="bdiv_r">Actual Quantity</td>
	                                    <td width="10%" class="bdiv_r">Discrepancy</td>
	                                  	<td width="15%" class="bdiv_r">Reason Code</td>
	                                </tr>
	                                </table>
	                            </td>
	                        </tr>
                            <tr>
                                <td>
                                    <input type="hidden" name="hdnSearch" value="<?php echo $Search; ?>" />
                                    <input type="hidden" name="hdnlstWarehouse" value="<?php echo $warehouseid; ?>" />
                                    <div id="dvProductList" class="scroll_300">
                                        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
                                            <?php
                                            	$cnt = 0;
                                                $cntid = 0;
                                                $lineno = 0;
												if ($rs_detailsall->num_rows)
                                                {
                                                    while ($row = $rs_detailsall->fetch_object())
                                                    {	 
                                                    	$cntid ++;
                                                        $cnt ++;
                                                        ($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
                                                        $txid = $row->txnid;
                                                        $lineno = $cnt;
														$dtxnID = $row->dtxnID;
                                                        //$lineno = $row->line;
                                                        $pcode = $row->pcode;
                                                        $pname = $row->pname;
														$pid = $row->PID;
                                                        $desc = $row->description;
                                                        //$quantity = number_format($row->quantity,0);
                                                        $quantity = $row->quantity;
                                                        //$cquantity = number_format($row->cquantity,0);
                                                        $cquantity = $row->cquantity;
                                                        $dquantity =  $cquantity - $quantity;
                                                        $utname = $row->utname;
                                                        $rtid = $row->rtid;
														$invID = $row->InvID;
														$soh = $row->SOH; 
														if ($statid != 7)
														{
															$hidden = "text";
														}
														else
														{
															$hidden = "hidden";
														} 
                                            ?>			
                                            <tr class="<?php echo $alt; ?>">
                                            	<input name="hdncnt" type="hidden" value="<?php echo $cntid; ?>">
												<input name="hdndtxnID<?php echo $cntid; ?>" type="hidden" value="<?php echo $dtxnID; ?>">
                                                <input name="hdnProductID<?php echo $cntid?>" type="hidden" value="<?php echo $pid; ?>">
                                                <input name="hdnQuantity<?php echo $cntid?>" type="hidden" value="<?php echo $quantity; ?>">
												<input name="hdnInventoryID<?php echo $cntid?>" type="hidden" value="<?php echo $invID; ?>">
												<input name="hdnSOH<?php echo $cntid?>" type="hidden" value="<?php echo $soh; ?>">
                                                <td width="5%" height="20" align="center" class="borderBR"><?php echo $cnt; ?></td>
                                                <td width="15%" height="20" align="center"  class="borderBR"><?php echo $pcode; ?></td>
                                                <td width="25%" align="center"  height="20" class="borderBR"><?php echo $pname; ?></td>
                                                <td width="10%" align="center"  height="20" class="borderBR"><?php echo $utname; ?></td>
                                                <td width="10%" align="center" class="borderBR"><?php echo $quantity; ?></td>
                                                <td width="10%" align="center" class="borderBR"><input name="txtActQuantity<?php echo $cntid?>" type="text" class="txtfield" style="width:60px; text-align:right" value="<?php if ($statid != 7) { echo $quantity; } else { echo $cquantity;}?>" onkeyup="javascript:RemoveInvalidChars(txtActQuantity<?php echo $cntid?>,<?php echo $cntid?>);" <?php if ($statid == 7) {?> disabled <?php } ?>/></td>
                                                <td width="10%" align="center" class="borderBR"><input name="txtdescr<?php echo $cntid?>" readonly ="true" type="<?php echo $hidden; ?>" class="txtfield" style="width:60px; text-align:right" value="<?php if ($statid != 7) { echo 0; } else { echo $dquantity;}?>" <?php if ($statid == 7) {?> disabled <?php } ?>/>
                                                 <?php  if ($statid != 7)
														{
															
														}
														else
														{
															echo $dquantity;
														}
														?> 
                                                </td>
                                                <td width="15%" align="center" class="borderBR">                                               
  							                        <select name="cboReasons<?php echo $cntid;?>" style="width:150px" disabled="disabled" class="txtfield">
									                        <?PHP
									                        $reasonlist = $rs_reasons.$cnt;
									                       	$reasonlist = $sp->spSelectReason($database, 1,$search);
															echo "<option value=\"0\" >[SELECT HERE]</option>";
									                        if ($reasonlist->num_rows)
									                        {
									                            while ($row =  $reasonlist->fetch_object())
									                            {
									                            ($rtid == $row->ID) ? $sel = "selected" : $sel = "";
									                            echo "<option value='$row->ID' $sel>$row->Name</option>";
									                            }
									                        }
									                        ?>
								                      </select>
                                                </td>
                                            </tr>
                                            <?php
                                                    }
                                                    $rs_detailsall->close(); 
                                                }
                                            
                                            if (isset($_GET['prodlist']) && $_GET['prodlist'] != "")
                                            {
                                            	$prodlist_url = split(',', $_GET['prodlist']);
                                            	$_SESSION['prod_list'] = $prodlist_url;
                                            	
                                            	for ($i = 0, $n = sizeof($_SESSION['prod_list']); $i < $n; $i++ ) 
                                            	{
                                            		$rs_proddet = $sp->spSelectProductbyID($database,$_SESSION['prod_list'][$i], 1);
                                            		if ($rs_proddet->num_rows)
                                            		{
                                            			while ($row = $rs_proddet->fetch_object())
                                            			{
                                            				$cntid ++;
                                                        	$cnt ++;
                                                        	($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
	                                                        $lineno += 1;
	                                                        $pcode = $row->Code;
	                                                        $pname = $row->Name;
															$pid = $row->ID;
	                                                        $desc = $row->Description;
	                                                        $utname = $row->UnitType;
															$invID = $row->InvID;
															$soh = $row->SOH;
											?>
											<tr class="<?php echo $alt; ?>">
                                            	<input name="hdncnt<?php echo $cntid?>" type="hidden" value="<?php echo $cntid; ?>">
                                                <input name="hdnProductID<?php echo $cntid?>" type="hidden" value="<?php echo $pid; ?>">
                                                <input name="hdnQuantity<?php echo $cntid?>" type="hidden" value="0">
												<input name="hdnInventoryID<?php echo $cntid?>" type="hidden" value="<?php echo $invID; ?>">
												<input name="hdnSOH<?php echo $cntid?>" type="hidden" value="<?php echo $soh; ?>">
                                                <td width="5%" height="20" align="center" class="borderBR"><?php echo $lineno; ?></td>
                                                <td width="15%" height="20" align="center"  class="borderBR"><?php echo $pcode; ?></td>
                                                <td width="25%" align="center"  height="20" class="borderBR"><?php echo $pname; ?></td>
                                                <td width="10%" align="center"  height="20" class="borderBR"><?php echo $utname; ?></td>
                                                <td width="10%" align="center" class="borderBR">0</td>
                                                <td width="10%" align="center" class="borderBR"><input name="txtActQuantity<?php echo $cntid?>" type="text" class="txtfield" style="width:60px; text-align:right" value="0" onkeyup="javascript:RemoveInvalidChars(txtActQuantity<?php echo $cntid?>,<?php echo $cntid?>);" <?php if ($statid == 7) {?> disabled <?php } ?>/></td>
                                                <td width="10%" align="center" class="borderBR"><input name="txtdescr<?php echo $cntid?>" readonly ="true" type="text" class="txtfield" style="width:60px; text-align:right" value="0" <?php if ($statid == 7) {?> disabled <?php } ?>/></td>
                                                <td width="15%" align="center" class="borderBR">                                               
  							                        <select name="cboReasons<?php echo $cntid;?>" style="width:150px" disabled="disabled" class="txtfield">
									                        <?PHP
									                        $reasonlist = $rs_reasons.$cnt;
									                       	$reasonlist = $sp->spSelectReason($database,1,$search);
															echo "<option value=\"0\" >[SELECT HERE]</option>";
									                        if ($reasonlist->num_rows)
									                        {
									                            while ($row = $reasonlist->fetch_object())
									                            {
									                            ($rtid == $row->ID) ? $sel = "selected" : $sel = "";
									                            echo "<option value='$row->ID'>$row->Name</option>";
									                            }
									                        }
									                        ?>
								                      </select>
                                                </td>
                                            </tr>
											<?php                                                        	                                            				
                                            			}
                                            			$rs_proddet->close();                                            			
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
                <!--<?php if ($statid != 7) {?>
                <tr class="bgF4F4F6">
                    <td height="20">&nbsp;</td>
                    <td align="right"><input name="btnAdd" type="button" class="btn" value="Add" onclick="javascript:winSelectProduct();"/></td>
                </tr>
                <?php } ?>-->
            </table>
            <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td height="3" class="bgE6E8D9"></td>
			</tr>
            </table>
            <br>
            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
                <td align="center">
                	<?php if ($statid != 7) {?>
                    <input name="btnSave" type="submit" class="btn" value="Save" onClick="return confirmSave();">
                    <input name="btnPrint" type="submit" class="btn" onclick="window.open('pages/inventory/inv_ReturnstoHO_print.php?tid=<?php echo $_GET['tid']; ?>', 'Prooflist', 'width=900, height=900'); return false;" value=" Prooflist "/>									
                   <?php }else{?>
                  	  <input name="hdnTXNID" type="hidden" value="<?php echo $_GET['tid']; ?>">
                      <!--<input name="btnPrint" type="submit" class="btn" onclick="window.open('pages/inventory/inv_ConfirmReturnstoHO_print.php?tid=<?php echo $_GET['tid']; ?>', 'print', 'width=900, height=900'); return false;" value=" Print "/>	
                    -->
                    <input name='input' type='button' class='btn' value='Print' onclick='openPopUp(hdnTXNID)'/>
                    <?php } ?> 
                    <!--<input name="btnCancel" type="submit" class="btn" value="Cancel" onClick="return confirmCancel();">-->
                    <input name="btnCancel" type="submit" class="btn" value="Back to list" onClick="location.href='?pageid=31'; return false;">
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