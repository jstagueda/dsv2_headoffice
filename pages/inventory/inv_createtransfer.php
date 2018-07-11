<?php
	include IN_PATH.'scCreateTransfer.php';
?>

<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<script type="text/javascript">
	window.history.forward(1);
</script>
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jxCreateTransfer.js"></script>
<script language="javascript" type="text/javascript">
function MM_jumpMenu(targ,selObj,restore)
{ 		
	var docno = document.frmTransferDetails.txtDocNo.value;
	var remarks = document.frmTransferDetails.txtRemarks.value;
	
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"&dno=" + docno+ "&remarks=" + remarks + "#MMHead" +"'");
	if (restore) 
		selObj.selectedIndex = 0;
}

function TrimZero(str)
{		
	if (!str)
	{
		return false;
	}
	
	var len = str.length;
	ctr = 0;
	if (eval(len) > 1) 
	{ 	
		for (var i = 0; i < len; i++) 
		{
			t = str.charAt(i);
			if (t != '0') 
			{
				break;
			}		
			ctr++;
		}
	}
	str = str.substring (ctr, len);	
	return str;
}

function enableReason()
{
	cnt 		= document.frmTransferDetails.hProd;
   	objwid  	= document.frmTransferDetails.lstWarehouse;
   	objqty  	= document.frmTransferDetails.elements["txtquantity[]"];
   	objreason  	= document.frmTransferDetails.elements["cboReason[]"];
	 
   if (objwid.selectedIndex != 0) 
   {  
	   for (var c = 0; c < cnt.value; c++)
		{
			if (TrimZero(objqty[c].value) != '')
			{				
				objreason[c].disabled = false;
			}						
			else
			{					
				objreason[c].disabled = true;
			}
		}	
   }
}


function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	
		l++; 
	}
	while(r > l && s[r] == ' ')
	{	
		r-=1;	
	}
	return s.substring(l, r+1);
}
function isNumeric(val){return(parseFloat(val,10)==(val*1));}
function confirmAdd()
{
	var cnt = document.frmTransferDetails.hProd;
	var objwid = document.frmTransferDetails.lstWarehouse;
	var objqty = document.getElementsByName('txtquantity[]');
	var objsoh = document.getElementsByName('hSOH[]');
	var objreason = document.getElementsByName('cboReason[]');
	var objUOM = document.getElementsByName('cboUOM[]');
	var objBox = document.getElementsByName('hboxMultiplier[]');
    var objBooklet = document.getElementsByName('hbookletMultiplier[]');
	var length = objqty.length;
	var emptyslots = 0;
	
	for(var i = 0; i < length; i++)
	{
		if(objqty[i].value != '')
		{
			
			if (!isNumeric(objqty[i].value) || objqty[i].value == 0 || objqty[i].value < 0 || !isWholeNumber(objqty[i].value))
			{
				alert("Invalid numeric format for quantity.");
				objqty[i].focus();
				objqty[i].select();
				return false;			
			}			
		}
		
		if (eval(objsoh[i].value) < eval(objqty[i].value))
		{
			alert("Transferred quantity should be less than or equal SOH.");
			objqty[i].focus();
			objqty[i].select();
			return false;				
		}
		
		if(objqty[i].value != '' && objreason[i].selectedIndex == 0)
		{
			alert("Reason code required.");
			objreason[i].focus();
			return false;
		} 
		
		if(objqty[i].value == '' && objreason[i].selectedIndex > 0)
		{
			alert("Quantity required.");
			objqty[i].focus();
			return false;
		} 
		
		if(objqty[i].value == '' && objreason[i].selectedIndex == 0)
		{
			emptyslots++;
		} 
	}
	
	if(emptyslots == cnt.value)
	{
		alert("There are no products added.");
		return false;
	}	
}

function confirmCancel()
{
	if (confirm('Are you sure you want to cancel this transaction?') == false)
   {
        return false;
   }    
}

function CheckInclude()
{	
	var ci = document.frmTransferDetails.elements["chkInclude[]"];

	for(i=0; i< ci.length; i++)
	{
		if(ci[i].checked == false)
		{
			document.frmTransferDetails.chkAll.checked = false;
		}
	}
			
	if (document.frmTransferDetails.elements["chkInclude[]"].value > 1)
	{
		if(ci.checked == false)
		{
			document.frmTransferDetails.chkAll.checked = false;
		}
	}
}
function checkAll(bin, chkcnt) 
{
	
	var elms = document.frmTransferDetails.elements;
	var btnRemove = document.frmTransferDetails.btnRemoveInv;
    var hasCheckedItems = false;
    var chkAll = document.frmTransferDetails.chkAll;



	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkIID[]') 
	  {
		  elms[i].checked = bin;
		  hasCheckedItems = true;		  
	  }		

    
    if(chkAll.checked)
	{
		btnRemove.disabled = false;
	}
	else
	{
		btnRemove.disabled = true;
	}
//	for (var i = 1; i <= elms.length; i++)
//	{
//		
//		for(var a = 1; a <= chkcnt; a++)
//		{
//			alert(a);
//	      if (elms[i].name == "chkIID" + a) 
//	      {  
//		     elms[i].checked = bin;		
//		     hasCheckedItems = true;
//	      }
//
//		}	
//	}
    
	
}

function validateRemove(bin) 
{
	var elms = document.frmTransferDetails.elements;
    var hasCheckedItems = false;
	var btnRemove = document.frmTransferDetails.btnRemoveInv;
    var chkAll = document.frmTransferDetails.chkAll;




	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkIID[]') 
	  {

		  if (elms[i].checked)
		  {
		 		btnRemove.disabled = false;
			 	hasCheckedItems = true;
			 	break;
		  }
		  else
		  {
			  btnRemove.disabled = true;
			  hasCheckedItems = false;
		  }

			if(hasCheckedItems)
			{
				break;
			}	
		 	  
	  }		

   
//	for (var i = 0; i < elms.length; i++)
//	{ 
//		for(var a = 0; a < chkcnt; a++)
//		{
//			if (elms[i].name == "chkIID" + a) 
//	      	{
//	      		if(elms[i].checked)
//			 	{
//			 		btnRemove.disabled = false;
//				 	hasCheckedItems = true;
//				 	break;
//		     	}
//		     	else
//		     	{
//		    	 	btnRemove.disabled = true;
//				 	hasCheckedItems = false;
//		     	}
//	     	}
//      	}
//
//		if(hasCheckedItems)
//		{
//			break;
//		}	
//	}

	chkAll.checked = false;
}

function confirmSave()
{
	msg 		= '';
	objdocno  	= document.frmTransferDetails.txtDocNo;
	objdesid	= document.frmTransferDetails.cboDesWarehouse;
	objswid 	= document.frmTransferDetails.lstWarehouse;
	objmvid  	= document.frmTransferDetails.cboMovementType;
	objiid 		= document.frmTransferDetails.elements["chkIID[]"];

	if (objiid == undefined)
	{
		alert ('There are no product(s) added.');
		return false;		
	}
	else
	{
		if (trim(objdocno.value) == '') msg += '   * Document No. \n';		
		if (objmvid.selectedIndex == 0) msg += '   * MovementType \n';
		if (objswid.selectedIndex == 0) msg += '   * Source Warehouse \n';
		if (objdesid.selectedIndex == 0) msg += '   * Destination Warehouse \n';
		
		if (msg != '')
		{ 
		  alert ('Please complete the following: \n\n' + msg);
		  return false;
		}
		else 
		{

			ml = document.frmTransferDetails;
	    	//alert(ml.hdncnt.length);
	    	if (ml.hdncnt.length > 1)
	    	{
	        	
	    		for(i = 0; i < ml.hdncnt.length; i++)
	    		{
	    			var ctr = i + 1;
	    			var desc = eval('document.frmTransferDetails.txtQuantity' + ctr);
	    			var reason = eval('document.frmTransferDetails.cboReasons' + ctr);
	    			var soh= eval('document.frmTransferDetails.hSOH' + ctr);


	    			if(isNaN(desc.value) || eval(desc.value) == 0 || eval(desc.value) < 0)
	    			{
	    				alert("Invalid Numeric format.");
	    				desc.select();
	    				desc.focus();
	    				return false;
	    			}
	    				    			
	    			
	    			if(eval(soh.value) == 0 || eval(soh.value) < 0)
	    			{	   			
	    				alert("SOH should be greater than 0.");
	    				return false;	
	    			}

	    			if(eval(soh.value) < eval(desc.value))
	    			{	   			
	    				alert("Transferred quantity should be less than or equal SOH.");
	    				desc.select();
	    				desc.focus();
	    				return false;	
	    			}	
	    			
	    			if (desc.value !="" && desc.value !="-")
	    			{
	    				
	    				if (reason.value == 0)
	    				{
	    					alert('Reason Code required.');
	    					reason.focus();
	    					return false;
	    				}				
	    			}
	    			
	    			if(eval(desc.value) == 0 || eval(desc.value)  < 0)
	    			{	   			
	    				alert("Quantity should be greater than 0.");
	    				desc.select();
						desc.focus();
	    				return false;	
	    			}
	    			if(desc.value == "" )
	    			{
	    				alert('Quantity is required.');
	    				desc.focus();
						return false;
	    			}
	    		}		
	    	}
	    	else
	    	{
		
	    		var desc = eval('document.frmTransferDetails.txtQuantity1');
	    		var reason = eval('document.frmTransferDetails.cboReasons1');
	    		var soh = eval('document.frmTransferDetails.hSOH1');

	  			if(isNaN(desc.value))
				{
					alert("Invalid Numeric format.");
					desc.select();
					desc.focus();
					return false;
				}
				
				if(eval(soh.value) == 0)
    			{	   			
    				alert("SOH should be greater than 0.");
    				soh.select();
    				soh.focus();
    				return false;	
    			}

    			if(eval(soh.value) < eval(desc.value))
    			{	   			
    				alert("Transferred quantity should be less than or equal SOH.");
    				desc.select();
    				desc.focus();
    				return false;	
    			}
	  			
	    		if (desc.value !="")
	    		{
	    			if (reason.value == 0)
	    			{
	    				alert('Reason Code required.');
	    				reason.focus();
	    				return false;
	    			}
	    		}	
	    		if(eval(desc.value) == 0 || eval(desc.value) < 0)
    			{	   			
    				alert("Quantity should be greater than 0.");
    				desc.select();
					desc.focus();
    				return false;	
    			}
	    		if(desc.value == "" )
    			{
    				alert('Quantity is required.');
    				desc.select();
					desc.focus();
					return false;
    			}				
	    	}

			if (confirm('Are you sure you want to save this transaction?') == false)
				return false;
			else
				return true;
		}		
	}
}

function checker()
{
	var ml = document.frmTransferDetails;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
	    var e = ml.elements[i];
	    if (e.name == "chkIID[]" && e.checked == true) 
	    {
			return true;
	    }
	}
   return false;	
}

function confirmRemove()
{
	if (!checker())
	{
		alert('Please select product(s) to be removed.');
		return false;
	}
	else
	{
		if (confirm('Are you sure you want to remove product(s)?') == false)
			return false;
		else
			return true;		
	}
}
function winSelectProduct() 
{
	var wid = document.frmTransferDetails.hdncboWarehouse.value;
 	var docNo = document.frmTransferDetails.txtDocNo.value;
 	var mtypeid = document.frmTransferDetails.hdncboMovement.value;
 	var rem = document.frmTransferDetails.txtRemarks.value;
 	var desid = document.frmTransferDetails.hdncboDesWarehouse.value;
 	var plist = document.frmTransferDetails.hProdListID.value;
     
 	if (mtypeid == 0)
	{
     	alert("Movement Type is required.");
     	document.frmTransferDetails.cboMovementType.focus();
     	return false;
 	}
     
	var pge = 3;
	subWin = window.open('pages/inventory/inv_ProductPopUp.php?wid=' + wid + '&dno=' + docNo + '&mtypeid=' + mtypeid + '&remarks=' + rem + '&desid=' + desid + '&pge=' + pge + '&prodlist=' + plist, 'newWin','width=600,height=600,scrollbars=yes');
	subWin.focus();
	return false;
}
</script>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
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
                    <td class="txtgreenbold13">Create Transfers </td>
                    <td>&nbsp;</td>
                </tr>
			</table>
			<?php
			if (isset($_GET['message']))
		  	{
		  		$message = strtolower($_GET['message']);
			  	$success = strpos("$message","success"); 
		  	?>
		  	<br>
		  	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
		  	<tr>
		  		<td class="txtreds"><b><?php echo $_GET['message']; ?></b></td>
		    </tr>
			</table>
		  	<?php
		  	}
			?>
			<br />
 			<form name="frmTransferDetails" method="post" action="includes/pcTransfer.php?prodlist=<?php echo $_GET['prodlist'];?>&wid=<?php echo $_GET['wid']; ?>&plid=<?php echo $_GET['plid']; ?>&dno=<?php echo $_GET['dno']; ?>&desid=<?php echo $_GET['desid']; ?>&remarks=<?php echo $_GET['remarks']; ?>&mtypeid=<?php echo $_GET['mtypeid']; ?>&search=<?php echo $_GET['search']; ?>">
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="tabmin">&nbsp;</td>
                    <td class="tabmin2">
                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                            <input type="hidden" name="hProdListID" value="<?php echo $_SESSION['trans_prodlist'];?>">
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
    				<td valign="top" class="bgF9F8F7">
                    	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                              	<td colspan="2">&nbsp;</td>
                            </tr>
        					<tr>
          						<td width="50%" valign="top">
                                	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
                                		<tr>
                                          	<td width="25%" height="20" class="txt10">Movement Type :</td>
                                          	<td width="75%" height="20">
                                            <?php
							              			
                                             if (isset($_SESSION['trans_prodlist']) && $_SESSION['trans_prodlist'] != "")
					                            {
													$able = "disabled";																										
												}
												else
												{
													$able = "";
												}
//							              		if (isset($_GET['prodlist']) && sizeof($session->prod_add_trans) != 0) 
//												{
//													$able = "disabled";																										
//												}
//												else
//												{
//													$able = "";
//												}
							              	?>     
                                          		<select name="cboMovementType" style="width:160px "  <?php echo $able; ?>  class="txtfield"  onChange="MM_jumpMenu('parent',this,0)" >
							                		<option value="index.php?pageid=25&plid=<?php echo $_GET['plid']; ?>&desid=0&swid=0&mvid=0" selected>[SELECT HERE]</option>
							                        <?php 
							                        	$tmpMVid = $_GET['mtypeid'];
							                        	$tmpSWid = $_GET['wid'];
							                        	$tmpDesWid = $_GET['desid'];
							                        	$tmpPLid = $_GET['plid'];
							                        	
							                        	
							                        	
							                        	if ($rs_MovementType->num_rows)
														{
															while ($row = $rs_MovementType->fetch_object())	
															{
																if ($_GET['mtypeid'] == $row->ID) 
																	$sel = "selected";
																else 
																	$sel = "";

																if($row->ID == 7)
																{
																	echo "<option value='index.php?pageid=25&mtypeid=".$row->ID."&wid=1&desid=2&plid=$tmpPLid' $sel >".$row->Code." - ".$row->Name."</option>";
																}
																else if($row->ID== 8)
																{
																	echo "<option value='index.php?pageid=25&mtypeid=".$row->ID."&wid=2&desid=1&plid=$tmpPLid' $sel >".$row->Code." - ".$row->Name."</option>";
																}
																else if($row->ID == 0)
															    {
																	echo "<option value='index.php?pageid=25&mtypeid=".$row->ID."&wid=0&desid=0&plid=$tmpPLid' $sel >".$row->Code." - ".$row->Name."</option>";
																}
															}
															$rs_MovementType->close();
														}
													?>
							              	</select>
							              	<input type="hidden" name="hdncboMovement" value="<?php echo $_GET['mtypeid']; ?>" />								          	
                                          	</td>
                                        </tr>
                                        <tr>
                                          	<td height="20" class="txt10">Source Warehouse :</td>
                                          	<td height="20">       
                                          	    <?php
							              				if (isset($session->prod_add_trans) && sizeof($session->prod_add_trans) != 0) 
														{
															$able = "disabled";																										
														}
														else
														{
															$able = "";
														}														
							              		?> 
                                          		<input type="hidden" name="hdncboWarehouse"    value="<?php echo $_GET['wid']; ?>" />
                                          		<select name="lstWarehouse" style="width:160px "  disabled="disabled"  class="txtfield"  onChange="MM_jumpMenu('parent',this,0)" >
							                		<option value="index.php?pageid=25&desid=<?php echo $tmpDid ?>&plid=<?php echo $_GET['plid']; ?>&mtypeid=<?php echo $_GET['mtypeid']; ?>&wid=0" selected>[SELECT HERE]</option>
							                        <?php 							                        
							                        	$tmpMVid = $_GET['mtypeid'];
							                        	$tmpPLid = $_GET['plid'];
							                        	$tmpDesid = $_GET['desid'];
							                        	
														if ($rs_sourcewarehouse->num_rows)
														{
															while ($row = $rs_sourcewarehouse->fetch_object())	
															{
																if ($_GET['wid'] == $row->ID) 
																	$sel = "selected";
																else 
																	$sel = "";

																
																	echo "<option value='index.php?pageid=25&mtypeid=$tmpMVid&desid=$tmpDesid&plid=$tmpPLid&wid=".$row->ID."' $sel >".$row->Name."</option>";
															
																
															}
															$rs_sourcewarehouse->close();
														}
													?>
							              	</select>
                                          	</td>
            							</tr> 
            							<tr>
                                          	<td width="500" height="20" class="txt10">Destination Warehouse :</td>
                                          	<td width="500">
                                          	   <?php
							              				if (isset($session->prod_add_trans) && sizeof($session->prod_add_trans) != 0) 
														{
															$able = "disabled";																										
														}
														else
														{
															$able = "";
														}
							              		?>
                                          	<select name="cboDesWarehouse" style="width:160px "  disabled="disabled" class="txtfield"  onChange="MM_jumpMenu('parent',this,0)" >
							                		<option value="index.php?pageid=25&mtypeid=<?php echo $_GET['mtypeid']; ?>&wid=<?php echo $_GET['wid']; ?>&plid=<?php echo $_GET['plid']; ?>&desid=0" selected>[SELECT HERE]</option>
							                        <?php 
							                        
							                        	$tmpMVid = $_GET['mtypeid'];
							                        	$tmpSWid = $_GET['wid'];
							                        	$tmpDesWid = $_GET['desid'];
							                        	$tmpPLid = $_GET['plid'];
							                        	
							                        	if ($rs_deswarehouse->num_rows)
														{
															while ($row = $rs_deswarehouse->fetch_object())	
															{
																if ($_GET['desid'] == $row->ID) 
																	$sel = "selected";
																else 
																	$sel = "";
																		
																echo "<option value='index.php?pageid=25&desid=".$row->ID."&wid=$tmpSWid&mtypeid=$tmpMVid&plid=$tmpPLid' $sel >".$row->Name."</option>";
															}
															$rs_deswarehouse->close();
														}
													?>
							              	</select>
                                          	<input type="hidden" name="hdncboDesWarehouse" value="<?php echo $_GET['desid']; ?>" />
                                          	</td>
            							</tr>  
            							<tr>
                                          	<td height="20" class="txt10">Document No. :</td>
                                          	<td height="20"><input name="txtDocNo" type="text" class="txtfield" id="txtDocNo" size="20" maxlength="20" value="<?php if(isset($_GET['dno'])){ echo $_GET['dno']; }; ?>"></td>
                                        </tr>	 							            							                                        
          							</table>
                             	</td>
          						<td valign="top">
                                	<table width="100%"  border="0" cellspacing="1" cellpadding="0">
                                        <tr>
                                          	<td width="25%" height="20" class="txt10">Reference No. :</td>
                                          	<td width="75%" height="20"><input type="text" id="txtReferenceNo" name="txtReferenceNo" class="txtfieldLabel" id="txtRefNo" size="20" maxlength="20" value="<?php echo $trno;?>" readonly="readonly" /></td>
            							</tr>
                                        <tr>
                                          	<td height="20" class="txt10">Transaction Date :</td>
                                          	<td height="20">
							              		<input name="startDate" type="text" class="txtfieldLabel" id="startDate" size="20" readonly="yes" value="<?php if (isset($_GET['txnDate'])){echo $_GET['txnDate'];}else{echo $datetoday;} ?>">
							                </td>
                                        </tr>
                                        <tr>
                                          	<td height="20" class="txt10">Branch :</td>
                                          	<td height="20">
								              <input type="text" id="txtBranch" name="txtBranch" class="txtfieldLabel" size="20" maxlength="20" value="<?php echo $branchName;?>"/>
                                          	  <input type="hidden" id="txtBranchID" name="txtBranchID" class="txtfield" size="20" maxlength="20" value="<?php echo $branchID;?>" readonly="readonly" />
                                          	</td>
            							</tr>
            							<tr>
                                          	<td height="20" class="txt10">Created By :</td>
                                          	<td height="20"><input type="text" id="txtCreatedBy" name="txtCreatedBy" class="txtfieldLabel" style="width: 200px;" value="<?php echo $username;?>"/></td>
            							</tr> 
                                        <tr>
                                          	<td height="20" valign="top" class="txt10">Remarks :</td>
                                          	<td height="20"><textarea name="txtRemarks" cols="40" rows="3" class="txtfieldnh"   id="txtRemarks"><?php if(isset($_GET['remarks'])){ echo $_GET['remarks']; }; ?></textarea></td>
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

      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="tabmin">&nbsp;</td>
                    <td class="tabmin2">
                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td class="txtredbold">Transfer Details </td>
                                <td>
                                    <table width="50%" border="0" align="right" cellpadding="0" cellspacing="1">
                                        <tr>
                                            <td>&nbsp;</td>                                 
                                        </tr>
                                    </table>
                                </td>
                            </tr>                          
                        </table>
                    </td>
                    <td class="tabmin3">&nbsp;</td>
                </tr>
            </table>
               <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen">
	                <tr>
	                    <td height="30" class="bgF9F8F7">
	                       
	                        <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
	                         <tr>
	                            <td width="64" class="txt10"><input name="btnSearch" type="submit" class="btn" value="Search" onclick="javascript:winSelectProduct();" /> </td>
	                            <td width="133" align="left" class="txt10"></td>
	                            <td width="1098" align="left" class="txt10">
	                         </td>           
	                    <td width="5" align="right" class="txt10"></td>
	              </tr>
	          </table>                       
	                </td>
                </tr>
            </table>
            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl3">
                <tr>
                    <td valign="top" class="bgF9F8F7">           
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td class="tab ">
                                    <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
                                        <tr align="center">                                        
                                        <?php
							              				if (isset($session->prod_add_trans) && sizeof($session->prod_add_trans) != 0) 
														{
																$able = "";																								
														}
														else
														{
															$able = "disabled";																
														}
							              		?>
                                            <td width="4%" class="bdiv_r">
                                            <input name="chkAll" type="checkbox" id="chkAll" value="1"  onclick="checkAll(this.checked);">
                                            </td>
                                           <td width="5%" class="bdiv_r">Line No.</td>
                                            <td width="15%" align="left" class="padl5 bdiv_r">Product Code</td>   
                                            <td width="30%" align="left" class="padl5 bdiv_r">Product Name</td>                        
                                            <td width="10%" align="center" class="bdiv_r">SOH</td>
                                            <td width="10%" class="bdiv_r">UOM</td>
                                            <td width="15%" align="center" class="bdiv_r">Quantity to Transfer</td>
                                            <td width="15%" align="center" >Reason</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
				                <td valign="top" class="">
				                  	<div class="scroll_300" id="dvTransferDetails">
				                  	
									<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
				                    	<?php
				                    	  	if (isset($_SESSION['trans_prodlist']) && $_SESSION['trans_prodlist'] != "")
		                             		{ 	
				                             	$cnt = 0;
				                               	//$prodlist_url = split(',', $_SESSION['trans_prodlist']);
				                                //$_SESSION['prod_list'] = $prodlist_url;
				                                            	
				                                for ($i = 0, $n = sizeof($_SESSION['trans_prodlist']); $i < $n; $i++ ) 
				                                {
				                                	$cnt ++;
			                                     	$rs_proddet = $sp->spSelectProductListInventoryTransfer2($database, $_SESSION['trans_prodlist'][$i], $_GET['wid']);
			                                     	if ($rs_proddet->num_rows)
		                                         	{
		                                         		while ($row = $rs_proddet->fetch_object())
		                                            	{	
		                                            		($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
															$pname = $row->Product;
															$pid = $row->ProductID;
															$pcode = $row->ProductCode;								
															$soh = number_format($row->SOH,0);
															$uom = $row->UOM;
		                                            	}
                                        	?>	
				                                    <tr class="<?php echo $alt; ?>">
				                                    <input name="hdncnt" type="hidden" value="<?php echo $cnt; ?>">
<!--											  		<input name="hdnProductID<?php echo $cnt;?>" id="hdnProductID<?php echo $cnt;?>" type="hidden" value="<?php echo $pid; ?>"/>-->
<!--							                  		<input name="hdnProductCode<?php echo $cnt;?>" type="hidden" value="<?php echo $pcode; ?>"/>-->
<!--													<input name="hdnProductName<?php echo $cnt;?>" type="hidden" value="<?php echo $pname; ?>"/>-->
<!--													<input name="hdnSOH<?php echo $cnt;?>" type="hidden" value="<?php echo $soh; ?>"/>-->
													
													<input name="hdnInventoryID<?php echo $cnt;?>" type="hidden" value="<?php echo $pid; ?>"/> 
                                                    <input name="hdnUOM<?php echo $cnt;?>" type="hidden" value ="<?php echo $uom; ?>"/>                                                       
                                                    <input name="hSOH<?php echo $cnt;?>" type="hidden" value="<?php echo $soh; ?>" />	
													<td width="4%" align="center" class='borderBR'>															 	
												 		<input name="chkIID[]" onclick="validateRemove(this.checked)" type="checkbox" value="<?php echo $pid;?>" >
												 	</td>
							                  		<td width="5%" align="center" height="20" class="borderBR"><?php echo $cnt; ?></td>
							                  		<td width="15%" height="20" class="borderBR padl5"><?php echo $pcode; ?></td>
							                    	<td width="30%" height="20" class="borderBR padl5"><?php echo $pname; ?></td>
							                    	<td width="10%" align="center" class="borderBR"><?php echo $soh; ?></td>
							                    	<td width="10%" align="center" class="borderBR"><span class="txt10">
						                      		<select name="cboUOM<?php echo $cnt;?>" id="cboUOM<?php echo $cnt;?>" class="txtfield" style="width:100px;" disabled="disabled" >
						                        	<?php
						                        		if ($rs_uom->num_rows)
														{  
												    		$box = 1;
															while ($row_uom = $rs_uom->fetch_object())
															{
																$id = $row_uom->ID;
																$uomname = $row_uom->Name;
																($id == $uom) ? $sel = 'selected' : $sel = '';
																($box == 2) ? $sel1 = 'selected' : $sel1 = ''; 
																$box += 1;
													?>	
						                        			<option <?php echo $sel;?> value="<?php echo $id; ?>"><?php echo $uomname; ?></option>
						                        	<?php
															}
															$rs_uom->data_seek(0);
														}
													?>
						                      		</select>
							                		</span></td>
							                    	<td width="15%" align="center" class="borderBR"><input name="txtQuantity<?php echo $cnt;?>"  id="txtQuantity<?php echo $cnt;?>" style = "width: 50px; text-align: right;"  type="text" class="txtfield3" size="12" maxlength="20" ></td>				                    
							                    	<td width="15%" align="center" class="borderB">                     
							                      		<select name="cboReasons<?php echo $cnt;?>" id="cboReasons<?php echo $cnt;?>" class="txtfield" style="width:150px;"> 
												  		<option value="0" selected>[SELECT HERE]</option>
															<?php
							                        		if ($rs_reasons->num_rows)
															{
																while ($row_reasons = $rs_reasons->fetch_object())
																{
																	$id = $row_reasons->ID;
																	$uomname = $row_reasons->Name;
																	($id == $uomid) ? $sel = 'selected' : $sel = '';
															?>
							                        		<option <?php echo $sel;?> value="<?php echo $id; ?>"><?php echo $uomname; ?></option>
							                        		<?php
																}
																$rs_reasons->data_seek(0);
															}
															?>
							                  			</select>
							               			</td>
							              		</tr>
							                  	<?php
				                                         	}
							                  		}
							                  		$rs_uom->close();
							                  		
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
      		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
		      	<tr>
			        <td align="center">
			        	<input name="hProdCount" type="hidden" value="<?php echo $prodCount; ?>">
				        <input name="btnSaveInv" type="submit" class="btn" value=" Save" onClick="return confirmSave();"> 
				        <input name="btnRemoveInv" disabled="disabled" type="submit" class="btn" value="Remove" onClick="return confirmRemove();">
				        <input name="btnCancelInv" type="submit" class="btn" value="Cancel" onClick="return confirmCancel();">
			        </td>
		      	</tr>
    		</table>
            <br>
		</td>	
 	</tr>
 	
</table>
</form>