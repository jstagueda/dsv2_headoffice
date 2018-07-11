<?php
	include IN_PATH.DS."scLinkPromosandProds.php";
	include IN_PATH.DS."scBrochures.php";
	
	$brochureid = '';
	$broid = '';
	if(isset($_GET['ID']))
	{
		$brochureid = "&ID=".$_GET['ID'];
		$broid = $_GET['ID'];
	}
?>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<style type="text/css">
	<!--
	.style1 {
	color: #FF0000;
	font-weight: bold;
	}
	-->
</style>

<script type="text/javascript">
var inival = 0;
function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}

function MM_jumpMenu(targ,selObj,restore)
{
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value +"'");
	if (restore) 
		selObj.selectedIndex = 0;
}

function checkAll(bin) 
{
	var elms = document.frmLink.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkInclude[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
}

function checkAll2(bin) 
{
	var elms = document.frmLink.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkInclude2[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
}

function checkAll3(bin) 
{
	var elms = document.frmLink.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkInclude3[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
}

function checkRemove()
{
	objiid = document.frmLink.elements["chkInclude[]"]
   	var arrList  = new Array()
   	var d = 0
   	var pageID 	 = eval('document.frmLink.hPageID');
   	
   	if (pageID.value == 0)
   	{
   		alert('Select Page first.');
   		pageID.focus();
		return false;   		
   	}
   	
   	if (objiid.length > 0) 
   	{
   		for (var c = 0; c < objiid.length; c++)
	   	{
			if (objiid[c].checked)
			{
				arrList[d] = objiid[c].value
				d++
			}
	   	}
   }
   else if (objiid.checked)
   {
		arrList[0] = objiid.value;
		d++
   }
   	  
   if(d == 0)
   {
		alert('Please select promo(s) to link.');
		return false;
   }
   else
   {
   		if (confirm('Are you sure you want to link promo(s)?') == false)
			return false;
   		else
	   	{
	   		return true;
	   	}
   }
}

function checkRemove2()
{
	objiid = document.frmLink.elements["chkInclude2[]"]
   	var arrList  = new Array()
   	var d = 0
   	var pageID 	 = eval('document.frmLink.hPageID');
   	
   	if (pageID.value == 0)
   	{
   		alert('Select Page first.');
   		pageID.focus();
		return false;   		
   	}
   	
   	if (objiid.length > 0) 
   	{
   		for (var c = 0; c < objiid.length; c++)
	   	{
			if (objiid[c].checked)
			{
				arrList[d] = objiid[c].value
				d++
			}
	   	}
   }
   else if (objiid.checked)
   {
		arrList[0] = objiid.value;
		d++
   }
   	  
   if(d == 0)
   {
		alert('Please select product(s) to link.');
		return false;
   }
   else
   {
   		if (confirm('Are you sure you want to link product(s)?') == false)
			return false;
   		else
	   	{
	   		return true;
	   	}
   }
}

function checkRemove3(cnt)
{
	if (cnt == 0)
	{
		alert('Please select product(s) to be removed.');
		return false;		
	}
	else
	{
		objiid = document.frmLink.elements["chkInclude3[]"]
	   	var arrList  = new Array()
	   	var d = 0
	   	
	   	if (objiid.length > 0) 
	   	{
	   		for (var c = 0; c < objiid.length; c++)
		   	{
				if (objiid[c].checked)
				{
					arrList[d] = objiid[c].value
					d++
				}
		   	}
	   }
	   else if (objiid.checked)
	   {
			arrList[0] = objiid.value;
			d++
	   }
	   	  
	   if(d == 0)
	   {
			alert('Please select product(s) to be removed.');
			return false;
	   }
	   else
	   {
	   		if (confirm('Are you sure you want to remove product(s)?') == false)
				return false;
	   		else
		   	{
		   		return true;
		   	}
	   }
   }
}

function checkSave(cnt, a)
{
	if (cnt == 0)
	{
		alert('Please select product(s) to be linked.');
		return false;		
	}
	else
	{
		if (confirm('Are you sure you want to save linking of Promos and Products?') == false)
			return false;
		else
   		{
   			return true;
   		}
	}
}
function checkEnterKey(a, e)
{
	var key;
	var str = a.id;

    if(window.event)
 	{
		key = window.event.keyCode; //IE
 	} 
 	else
 	{
  		key = e.which; //firefox
 	}
 	
 	if (key == 13)
 	{
 		return false; 		
 	}	
}
function disableEnterKey(a, e, cnt)
{
     var key;
     var str = a.id;

     if(window.event)
     {
          key = window.event.keyCode; //IE
     } 
     else
     {
          key = e.which; //firefox
     }
     
     if (str.substring(0, 14) == "txtPromoSearch" && key == 13)
     {
     	var btn = eval('document.frmLink.btnPromoSearch');
     	btn.focus();
     	btn.select();
	 	return false;
     }
     else if (str.substring(0, 16) == "txtProductSearch" && key == 13)
     {
     	var btn = eval('document.frmLink.btnProductSearch');
     	btn.focus();
     	btn.select();
     	return false;
     }
     
     return (key!=13);
}
function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}
function openPopUp(ptypeid, promoid, isincentive, promocode)
{
	if (ptypeid == 1)
	{
		var objWin;
		popuppage = "pages/leaderlist/promo_singleLineDetails.php?prmsid=" + promoid;
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','1500','700','yes');
		}
		
		return false;  		
	}
	else if (ptypeid == 2)
	{
		var width = 1500;
	    var height = 700;
	    var left = parseInt((screen.availWidth/2) - (width/2));
	    var top = parseInt((screen.availHeight/2) - (height/2));
	    var windowFeatures = "width=" + width + ",height=" + height + ",status,resizable,left=" + left + ",top=" + top + "screenX=" + left + ",screenY=" + top;
	    window.open("pages/leaderlist/promo_popup.php?biID="+promoid+"&pcode="+promocode,'popup',windowFeatures); 
	    return false;
	}
	else if (ptypeid == 3)
	{
		if (isincentive == 1)
		{
			inc = 2;
		}
		else
		{
			inc = 1;			
		}
		var objWin;
		popuppage = "pages/leaderlist/promo_setpromoDetails.php?prmsid=" + promoid + "&inc="+inc;
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','1500','700','yes');
		}
		
		return false; 
	}
	else
	{
		var objWin;
		popuppage = "pages/leaderlist/promo_steplevelDetails.php?prmsid=" + promoid;
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','1500','700','yes');
		}
		
		return false;  
	}
}
</script>

<form name="frmLink" method="post" action="index.php?pageid=114.1&ID=<?php echo $_GET["ID"]; ?>&PID=<?php echo $_GET["PID"]; ?>">
<input type="hidden" name="hPageID" value="<?php echo $pgid; ?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
		<?PHP
			include("nav.php");
		?>
	</td>
    <td class="divider">&nbsp;</td>
    <td valign="top">
    	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
        	<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Leader List</span></td>
        </tr>
    	</table>
    	<br />
   		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  		<tr>
    		<td valign="top">
      			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
    				<td class="txtgreenbold13">Link Promos and Products </td>
    				<td>&nbsp;</td>
  				</tr>
   				<tr>
    				<td height="20">&nbsp;</td>
    				<td>&nbsp;</td>
  				</tr>
				</table>
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="33%" valign="top">
						<?php 
							if (isset($_GET['msg']))
							{
								$message = strtolower($_GET['msg']);
								$success = strpos("$message","success"); 
								echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
	    					} 
	    					else if(isset($_GET['errmsg']))
	    					{
	    						$errormessage = strtolower($_GET['errmsg']);
								$error = strpos("$errormessage","error"); 
								echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";
	    					}
						?>
      					<table width="70%"  border="0" align="left" cellpadding="0" cellspacing="0">
        				<tr>
          					<td class="tabmin">&nbsp;</td>
          					<td class="tabmin2"><span class="txtredbold">General Information</span></td>
          					<td class="tabmin3">&nbsp;</td>
        				</tr>
      					</table>
      					<table width="70%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl0">
        				<tr>
          					<td valign="top" class="bgF9F8F7">
                      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                       			<tr>
			                		<td height="10" width="15%">&nbsp;</td>
			                		<td height="10" width="20%">&nbsp;</td>
			                		<td height="10" width="15%">&nbsp;</td>
			                		<td height="10" width="20%">&nbsp;</td>
			                	</tr>
                           		<tr>
			                		<td height="20" align="left" class="txtredbold padl5">Brochure Code</td>
			                		<td height="20" align="left" class="txtpallete padl5"><?php echo ": " . $bcode; ?></td>
			                		<td height="20" align="left" class="txtredbold padl5">Number of Pages</td>
			                		<td height="20" align="left" class="txtpallete padl5"><?php echo ": " . $nopage; ?></td>
			                	</tr>
			              		<tr>
			                		<td height="20" align="left" class="txtredbold padl5">Brochure Name</td>
			                		<td height="20" align="left" class="txtpallete padl5"><?php echo ": " . $bname; ?></td>
			                		<td height="20" align="left" class="txtredbold padl5">Size</td>
			                		<td height="20" align="left" class="txtpallete padl5"><?php echo ": " . $height . " x " . $width; ?></td>
			                	</tr>
			               		<tr>
			                		<td height="20" align="left" class="txtredbold padl5">Date Created</td>
			                		<td height="20" align="left" class="txtpallete padl5">
			                			<?php 
			                				$date = date("M d, Y", strtotime($datecreated));
											$datecreated = $date;
			                				echo ": " . $datecreated; 
			                			?>
			                		</td>
			                		<td height="20" align="left" class="txtredbold padl5">Status</td>
			                		<td height="20" align="left" class="txtpallete padl5"><?php echo ": " . $status; ?></td>
			              		</tr>
			              		<tr valign="top">
			                		<td height="20" align="left" class="txtredbold padl5">Campaign Code</td>
			                		<td height="20" align="left" class="txt10 padl5">
			                			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			                			<?php
			                				if($rs_campaignlist->num_rows)
											{
												while($row = $rs_campaignlist->fetch_object())
												{
													echo "<tr><td height='20' align='left' class='txtredbold'>: $row->CampaignCode - $row->Campaign</td></tr>";
												}
												$rs_campaignlist->close();
											}	
			                			?>
			                			</table>
			                		</td>
			                		<td height="10" colspan="2">&nbsp;</td>
			              		</tr>
              			 		<tr>
			                		<td height="10" colspan="4">&nbsp;</td>
			                	</tr>
                      			</table>
                  			</td>
        				</tr>
      					</table>
      					<br>
   					</td>
				</tr>
				</table>
				<br>
				<table width="100%" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td width="50%" valign="top">
						<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td height="20" align="left" calss="padl5">
										<strong class="txtpallete">Page :</strong>
										&nbsp;
										<select name="cboPage" class="txtfield" style="width:80px" onChange="MM_jumpMenu('parent',this,0)">
											<option value="index.php?pageid=114.1&ID=<?php echo $_GET["ID"]; ?>&PID=0">[SELECT]</option>
											<?php
                            					if ($rs_brochurepagedetails->num_rows)
                            					{
                            						$sel = "";
                                					while ($row = $rs_brochurepagedetails->fetch_object())
                                					{
                                						if ($_GET["PID"] == $row->ID)
                                						{
                                							$sel = "selected";
                                						}
                                						else
                                						{
                                							$sel = "";
                                						}
                                						echo "<option $sel value='index.php?pageid=114.1&ID=" . $_GET["ID"] . "&PID=$row->ID'>$row->PageNum</option>";											
                                					}
                                					$rs_brochurepagedetails->close();
                            					} 
                        					?>
										</select>
										&nbsp;&nbsp;&nbsp;
										<strong class="txtpallete">Layout :</strong>
										&nbsp;
										<?php echo $ltype; ?>
										&nbsp;&nbsp;&nbsp;
										<strong class="txtpallete">Key Spread:</strong>
										&nbsp;
										<?php echo $ptype; ?>
									</td>
								</tr>
								</table>
								<br>
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
					  					<tr>
					  						<td class="tabmin">&nbsp;</td>
						  					<td class="tabmin2"><span class="txtredbold padl5">List of Promos and Products Linked</span></td>
						  					<td class="tabmin3">&nbsp;</td>
					  					</tr>
				  						</table>
				  					</td>
				  				</tr>
				  				<tr>
				  					<td>
				  						<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen">
				       					<tr align="center" class="tab">
				       						<td width="5%" class="bdiv_r" height="20"><div align="center"><input name="chkAll3" type="checkbox" id="chkAll3" value="1" class="inputOptChk" onclick="checkAll3(this.checked);" /></div></td>
				       						<td width="15%" class="bdiv_r padl5" height="20"><div align="left"><span class="txtpallete">Product Code</span></div></td>
				       						<td width="40%" class="bdiv_r padl5" height="20"><div align="left"><span class="txtpallete">Product Name</span></div></td>
				       						<td width="25%" class="padl5" height="20"><div align="left"><span class="txtpallete">Promo Code</span></div></td>
				       					</tr>
				       					</table>
				       					<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen">
				       					<tr>
				       						<td>
				       							<div class="scroll_300">
				       							<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="">
						      						<?php
						      							if($rs_brochurepagelink->num_rows)
														{
															$uid = '';
															while($row = $rs_brochurepagelink->fetch_object())
															{
																$uid = $row->PromoID . '_' . $row->ProductID;
																echo "
																		<tr>
						      												<td width='5%'  class='borderBR' height='20'><div align='center'><input type='checkbox' name='chkInclude3[]' class='inputOptChk' value='$uid'></div></td>
						      												<td width='15%' class='borderBR padl5' height='20'><div align='left'>$row->ProductCode</div></td>
						      												<td width='40%' class='borderBR padl5' height='20'><div align='left'>$row->ProductName</div></td>
						      												<td width='25%' class='borderBR padl5' height='20'><div align='left'>$row->PromoCode</div></td>
						      											</tr>		
																		";
															}
															$rs_brochurepagelink->close();
														}
														else
														{
															echo"<tr align='center'><td height='20' colspan='4' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
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
				  				<table width="96%"  border="0" align="left" cellpadding="0" cellspacing="1" class="">
		  						<tr>
		  							<td height="20" align="center">
		  								<input name="btnSave" type="submit" class="btn" value="Back to List" />
		  								<input name="btnRemove" type="submit" class="btn" value="Remove and Save" onClick="return checkRemove3(<?php echo $linkcount; ?>);" />
	  								</td>
		  						</tr>
		  						</table> 
							</td>
						</tr>
						</table>
					</td>
					<td width="50%">
						<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
						<tr>
							<td height="30">&nbsp;</td>
						</tr>
						</table>
						<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0">
			  					<tr>
			  						<td class="tabmin">&nbsp;</td>
				  					<td class="tabmin2"><span class="txtredbold padl5">Promo List</span></td>
				  					<td class="tabmin3">&nbsp;</td>
			  					</tr>
		  						</table>       
		  						<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordergreen">
		  						<tr class="tab">
		  							<td height="20" align="left" class="txtpallete padl5">
		  								<strong>Promo Code :</strong>
		  								&nbsp;
		  								<input name="txtPromoSearch" type="text" class="txtfield" id="txtPromoSearch" size="20" value="<?php echo $promosearch; ?>" onKeyPress="return disableEnterKey(this, event);">
                          				<input name="btnPromoSearch" type="submit" class="btn" value="Search" />
	  								</td>
		  						</tr>
		  						<tr>
		  							<td>
		  								<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1">
				       					<tr align="center" class="tab">
				       						<td width="5%" class="bdiv_r" height="20"><div align="center"><input name="chkAll" type="checkbox" id="chkAll" value="1" class="inputOptChk" onclick="checkAll(this.checked);" /></div></td>
				       						<td width="15%" class="bdiv_r padl5" height="20"><div align="left"><span class="txtpallete">Product Code</span></div></td>
				       						<td width="40%" class="bdiv_r padl5" height="20"><div align="left"><span class="txtpallete">Product Name</span></div></td>
				       						<td width="25%" class="padl5" height="20"><div align="left"><span class="txtpallete">Promo Code</span></div></td>
				       					</tr>
				       					</table>
				       					<div class="scroll_300">      
				      					<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class="">
				           					<?php 
				           						if($rs_promosandprods->num_rows)
				           						{
				           							$rowalt = 0;
				                        			while($row = $rs_promosandprods->fetch_object())
				                        			{
				                        				$vid = $row->ID . "_" . $row->ProdID;
				                        				($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
				                        				$popup = 'openPopUp('.$row->PromoTypeID.', '.$row->ID.', '.$row->IsIncentive.', "'.$row->Code.'")';
				                        				echo "<tr align='center' class='$class'>
				                        						<td height='20' class='borderBR' width='5%' align='center'><input type='checkbox' name='chkInclude[]' class='inputOptChk' value='$vid'></td>
				                        						<td height='20' class='borderBR padl5' width='15%' align='left'><span class='txt10'>$row->ProdCode</span></td>
				                         						<td height='20' class='borderBR padl5' width='40%' align='left'><span class='txt10'>$row->ProdName</span></td>
				                         						<td height='20' class='borderBR padl5' width='25%' align='left'><span class='txt10'><a href='#' onclick='$popup' class='txtnavgreenlink'>$row->Code</a></span></td>
															</tr>";
				                         				$rowalt += 1;
				                        			}
				                        			$rs_promosandprods->close();
				                    			}
				                  				else
				                  				{
				                  					echo "<tr align='center'><td height='20' colspan='4' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
				                  				} 
				              				?>                        
				         				</table>
				         				</div>
		  							</td>
		  						</tr>
		  						</table>
		  						<br>
		  						<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="1" class="">
		  						<tr>
		  							<td height="20">&nbsp;</td>
		  						</tr>
		  						<tr>
		  							<td height="20" align="right"><input name="btnAddPromo" type="submit" class="btn" value="Add and Save" onClick="return checkRemove();"/></td>
		  						</tr>
		  						</table>
							</td>
						</tr>
						<tr>
							<td height="20">&nbsp;</td>
						</tr>
						<tr>
							<td>
								<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0">
			  					<tr>
			  						<td class="tabmin">&nbsp;</td>
				  					<td class="tabmin2"><span class="txtredbold padl5">Product List</span></td>
				  					<td class="tabmin3">&nbsp;</td>
			  					</tr>
		  						</table>
		  						<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordergreen">
		  						<tr class="tab">
		  							<td height="20" align="left" class="padl5">
		  								<strong class="txtpallete">Product Code :</strong>
		  								&nbsp;
		  								<input name="txtProductSearch" type="text" class="txtfield" id="txtProductSearch" value="<?php echo $prodsearch; ?>" size="20" onKeyPress="return disableEnterKey(this, event);">
                          				<input name="btnProductSearch" type="submit" class="btn" value="Search" />
	  								</td>
		  						</tr>
		  						<tr>
		  							<td>
		  								<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1">
				       					<tr align="center" class="tab">
				       						<td width="5%" class="bdiv_r" height="20"><div align="center"><input name="chkAll2" type="checkbox" id="chkAll2" value="1" class="inputOptChk" onclick="checkAll2(this.checked);" /></div></td>
				       						<td width="18%" class="bdiv_r padl5" height="20"><div align="left"><span class="txtpallete">Product Code</span></div></td>
				       						<td width="80%" class="padl5" height="20"><div align="left"><span class="txtpallete">Product Name</span></div></td>
				       					</tr>
				       					</table>
				       					<div class="scroll_300">      
				      					<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class="">
				           					<?php 
				           						if($searchclick == 1)
				           						{
					           						if($rs_prodlist->num_rows)
					           						{
					           							$rowalt = 0;
					                        			while($row = $rs_prodlist->fetch_object())
					                        			{
					                        				($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
					                        				echo "<tr align='center' class='$class'>
					                        						<td height='20' class='borderBR' width='5%' align='center'><input type='checkbox' name='chkInclude2[]' class='inputOptChk' value='$row->ID'></td>
					                        						<td height='20' class='borderBR padl5' width='18%' align='left'><span class='txt10'>$row->Code</span></td>
					                         						<td height='20' class='borderBR padl5' width='80%' align='left'><span class='txt10'>$row->Name</span></td>
																</tr>";
					                         				$rowalt += 1;
					                        			}
					                        			$rs_prodlist->close();
					                    			}
					                  				else
					                  				{
					                  					echo "<tr align='center'><td height='20' colspan='3' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
					                  				}
				           						}
				           						else
				                  				{
				                  					echo "<tr align='center'><td height='20' colspan='3' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
				                  				}
				              				?>                      
				         				</table>
				         				</div>
		  							</td>
		  						</tr>
		  						</table>
		  						<br>
		  						<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="1" class="">
		  						<tr>
		  							<td height="20">&nbsp;</td>
		  						</tr>
		  						<tr>
		  							<td height="20" align="right"><input name="btnAddProduct" type="submit" class="btn" value="Add and Save" onClick="return checkRemove2();" /></td>
		  						</tr>
		  						</table>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="20">&nbsp;</td>
		</tr>
 		</table>
   	</td>
</tr>
</table>
</form>