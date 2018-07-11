<?php
	include IN_PATH.DS."scAddPageDetails.php";
	include IN_PATH.DS."scLinkPromosandProds.php";
	include IN_PATH.DS."scBrochures.php";

	global $database;	
	$brochureid = '';
	$broid = '';
	if(isset($_GET['ID']))
	{
		$brochureid = "&ID=".$_GET['ID'];
		$broid = $_GET['ID'];
	}
?>

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

<link rel="stylesheet" type="text/css" href="css/ems.css">
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script type="text/javascript">
var inival = 0;
 
function getSelectionProductList(text, li) 
{
	var txt = text.id;

  	tmp = li.id;
  	tmp_val = tmp.split("_");
  	h = eval('document.frmLink.hProdID');
  	i = eval('document.frmLink.txtWornItem');
  	
  	h.value = tmp_val[0];
  	i.value = tmp_val[2] + ' - ' + tmp_val[1];
}

function getSelectionProductListH(text, li) 
{
	var txt = text.id;

  	tmp = li.id;
  	tmp_val = tmp.split("_");
  	h = eval('document.frmLink.hProdIDH');
  	i = eval('document.frmLink.txtHeroedItem');
  	
  	h.value = tmp_val[0];
  	i.value = tmp_val[2] + ' - ' + tmp_val[1];
}
  
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

function validateCallOut()
{
	var callout = eval('document.frmLink.txtCallouts');
	
	if (callout.value == "")
	{
		alert ('Call-outs required.');
		callout.focus();
		return false;		
	}	
}

function validateViolator()
{
	var violator = eval('document.frmLink.txtOfferViol');
	
	if (violator.value == "")
	{
		alert ('Offer Violators required.');
		violator.focus();
		return false;		
	}	
}

function validateHeroed()
{
	var heroed = eval('document.frmLink.txtHeroedItem');
	var itemid = eval('document.frmLink.hProdIDH');
	
	if (itemid.value == "")
	{
		alert ('Heroed Shade/Colors required.');
		heroed.focus();
		return false;		
	}	
}

function validateWornBy()
{
	var itemworn = eval('document.frmLink.txtWornItem');
	var itemid = eval('document.frmLink.hProdID');
	
	if (itemid.value == "")
	{
		alert ('Item worn by model required.');
		itemworn.focus();
		return false;		
	}	
}

function confirmSave()
{
	var pid = eval('document.frmLink.hPageID');
	var page = eval('document.frmLink.cboPage');
	var cnt1 = eval('document.frmLink.hCallOut');	
	var cnt2 = eval('document.frmLink.hViolator');
	var cnt3 = eval('document.frmLink.hHeroed');
	var cnt4 = eval('document.frmLink.hWornBy');
	
	if (pid.value == 0)
	{
		alert('Page required.');
		page.focus();
		return false;
	}
	
	if (cnt1.value == 0 && cnt2.value == 0 && cnt3.value == 0 && cnt4.value == 0)
	{
		alert('Add Page Details information first.');
		return false;		
	}
	else
	{
		if (confirm('Are you sure you want to save Page Details?') == false)
			return false;
		else
			return true;
	}
}
</script>

<form name="frmLink" method="post" action="index.php?pageid=116.1&ID=<?php echo $_GET["ID"]; ?>&PID=<?php echo $_GET["PID"]; ?>">
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
    				<td class="txtgreenbold13">Add Page Details</td>
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
								echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div><br>";
	    					} 
	    					else if(isset($_GET['errmsg']))
	    					{
	    						$errormessage = strtolower($_GET['errmsg']);
								$error = strpos("$errormessage","error"); 
								echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div><br>";
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
			                		<td height="20" align="left" class="txt10 padl5">Brochure Code</td>
			                		<td height="20" align="left" class="txt10 padl5"><?php echo ": " . $bcode; ?></td>
			                		<td height="20" align="left" class="txt10 padl5">Number of Pages</td>
			                		<td height="20" align="left" class="txt10 padl5"><?php echo ": " . $nopage; ?></td>
			                	</tr>
			              		<tr>
			                		<td height="20" align="left" class="txt10 padl5">Brochure Name</td>
			                		<td height="20" align="left" class="txt10 padl5"><?php echo ": " . $bname; ?></td>
			                		<td height="20" align="left" class="txt10 padl5">Size</td>
			                		<td height="20" align="left" class="txt10 padl5"><?php echo ": " . $height . " x " . $width; ?></td>
			                	</tr>
			               		<tr>
			                		<td height="20" align="left" class="txt10 padl5">Date Created</td>
			                		<td height="20" align="left" class="txt10 padl5">
			                			<?php 
			                				$date = date("M d, Y", strtotime($datecreated));
											$datecreated = $date;
			                				echo ": " . $datecreated; 
			                			?>
			                		</td>
			                		<td height="20" align="left" class="txt10 padl5">Status</td>
			                		<td height="20" align="left" class="txt10 padl5"><?php echo ": " . $status; ?></td>
			              		</tr>
			              		<tr valign="top">
			                		<td height="20" align="left" class="txt10 padl5">Campaign Code</td>
			                		<td height="20" align="left" class="txt10 padl5">
			                			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			                			<?php
			                				if($rs_campaignlist->num_rows)
											{
												while($row = $rs_campaignlist->fetch_object())
												{
													echo "<tr><td height='20' align='left' class=''>: $row->CampaignCode - $row->Campaign</td></tr>";
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
					<td width="40%" valign="top">
						<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<table width="90%"  border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td height="20" align="left" calss="padl5">
										<strong>Page :</strong>
										&nbsp;
										<select name="cboPage" class="txtfield" style="width:80px" onChange="MM_jumpMenu('parent',this,0)">
											<option value="index.php?pageid=116.1&ID=<?php echo $_GET["ID"]; ?>&PID=0">[SELECT]</option>
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
                                						echo "<option $sel value='index.php?pageid=116.1&ID=" . $_GET["ID"] . "&PID=$row->ID'>$row->PageNum</option>";											
                                					}
                                					$rs_brochurepagedetails->close();
                            					} 
                        					?>
										</select>
										&nbsp;&nbsp;&nbsp;
										<strong>Layout :</strong>
										&nbsp;
										<?php echo $ltype; ?>
										&nbsp;&nbsp;&nbsp;
										<strong>Key Spread:</strong>
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
				       						<td width="15%" class="bdiv_r padl5" height="20"><div align="left"><span class="txtredbold">Product Code</span></div></td>
				       						<td width="40%" class="bdiv_r padl5" height="20"><div align="left"><span class="txtredbold">Product Name</span></div></td>
				       						<td width="30%" class="padl5" height="20"><div align="left"><span class="txtredbold">Promo Code</span></div></td>
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
																		<input type='hidden' name='hInclude[]' value='$uid'>
																		<tr>
						      												<td width='15%' class='borderBR padl5' height='20'><div align='left'>$row->ProductCode</div></td>
						      												<td width='40%' class='borderBR padl5' height='20'><div align='left'>$row->ProductName</div></td>
						      												<td width='30%' class='borderBR padl5' height='20'><div align='left'>$row->PromoCode</div></td>
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
							</td>
						</tr>
						</table>
					</td>
					<td width="60%" valign="top">
						<br><br><br>
						<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin"></td> 
							<td class="tabmin2"><div align="left" class="padl5 txtredbold">Page Details</div></td>
							<td class="tabmin3">&nbsp;</td>
						</tr>
						</table>
						<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0" class="" id="tbl2">
						<tr>
							<td valign="top" class="">
								<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen">
								<tr class="bgF9F8F7">
									<td height="15">&nbsp;</td>
								</tr>
								<tr>
									<td class="bgF9F8F7"><div class="scroll_300">
										<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
										<tr>
									   	 	<td height="20" valign="top"><div align="left" class="padl5"><strong>Call-outs : </strong></div></td>
										    <td height="20">
										    	<input type="text" name="txtCallouts" id="txtCallouts" style="width: 228px;" class="txtfield">
										    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="btnAdd1" id="btnAdd1" type="submit" class="btn" value="Add" onClick="return validateCallOut();">
									    	</td>
									    </tr> 
									    <?php
									    	if (isset($_SESSION['callouts']))
									    	{
									    		$tmplist = $_SESSION['callouts'];
									    		$n = sizeof($tmplist);
									    		for ($i = 0; $i < $n; $i++)
									    		{
									    			$value = $tmplist[$i];
									    			echo "<tr>
									    					<td height='20'>&nbsp;</td>
															<td height='20'>$value</td>
										    			</tr>";									    			
									    		}
									    	}
									    ?> 
									    <tr>
									   	 	<td height="20" valign="top"><div align="left" class="padl5"><strong>Offer Violators :</strong></div></td>
										    <td height="20">
										    	<input type="text" name="txtOfferViol" id="txtOfferViol" style="width: 228px;" class="txtfield">
										    	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="btnAdd2" id="btnAdd1" type="submit" class="btn" value="Add" onClick="return validateViolator();">
										    </td>
									    </tr>
									    <?php
									    	if (isset($_SESSION['violators']))
									    	{
									    		$tmplist = $_SESSION['violators'];
									    		$n = sizeof($tmplist);
									    		for ($i = 0; $i < $n; $i++)
									    		{
									    			$value = $tmplist[$i];
									    			echo "<tr>
									    					<td height='20'>&nbsp;</td>
															<td height='20'>$value</td>
										    			</tr>";									    			
									    		}
									    	}
									    ?> 
									   <tr>
									   	 	<td height="20"><div align="left" class="padl5"><strong>Heroed Shade/Colors :</strong></div></td>
										    <td height="20">
										    	<?php
													echo"<input name='txtHeroedItem' type='text' class='txtfield' style='width: 228px;' id='txtHeroedItem' value=''/>
														 <span id='indicatorH' style='display: none'><img src='images/ajax-loader.gif' alt='Working...' /></span>                                      
														 <div id='prod_choicesH' class='autocomplete' style='display:none'></div>
														 <script type='text/javascript'>							
														 	//<![CDATA[
																var prod_choicesH = new Ajax.Autocompleter('txtHeroedItem', 'prod_choicesH', 'includes/scProductListPageDetAjax.php?isWorn=0', {afterUpdateElement : getSelectionProductListH, indicator: 'indicatorH'});																		
									                        //]]>
														 </script>
														 <input name='hProdIDH' type='hidden' id='hProdIDH' value=''/>"; 
												?>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="btnAdd3" id="btnAdd1" type="submit" class="btn" value="Add" onClick="return validateHeroed();">
											</td>
									    </tr>
									    <?php
									    	if (isset($_SESSION['heroed']))
									    	{
									    		$tmplist = $_SESSION['heroed'];
									    		$n = sizeof($tmplist);
									    		for ($i = 0; $i < $n; $i++)
									    		{
									    			$id = $tmplist[$i];
									    			$rs_colordet = $sp->spSelectProductDM($database, $id, "");
									    			if($rs_colordet->num_rows)
													{
														while($row = $rs_colordet->fetch_object())
														{						
															$code = $row->ProductCode;
															$name = $row->ProductName;
														}
														$rs_colordet->close();
													}
									    			echo "<tr>
									    					<td height='20'>&nbsp;</td>
															<td height='20'>$code - $name</td>
										    			</tr>";							    			
									    		}
									    	}
									    ?> 
									    <tr>
									   	 	<td height="20"><div align="left" class="padl5"><strong>Item worn by model :</strong></div></td>
										    <td height="20">
										    	<?php
													echo"<input name='txtWornItem' type='text' class='txtfield' style='width: 228px;' id='txtWornItem' value=''/>
														 <span id='indicator' style='display: none'><img src='images/ajax-loader.gif' alt='Working...' /></span>                                      
														 <div id='prod_choices' class='autocomplete' style='display:none'></div>
														 <script type='text/javascript'>							
														 	//<![CDATA[
																var prod_choices = new Ajax.Autocompleter('txtWornItem', 'prod_choices', 'includes/scProductListPageDetAjax.php?isWorn=1', {afterUpdateElement : getSelectionProductList, indicator: 'indicator'});																		
									                        //]]>
														 </script>
														 <input name='hProdID' type='hidden' id='hProdID' value=''/>"; 
												?>
												&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="btnAdd4" id="btnAdd2" type="submit" class="btn" value="Add" onClick="return validateWornBy();">
										    </td>
									    </tr>
									     <?php
									    	if (isset($_SESSION['itemworn']))
									    	{
									    		$tmplist = $_SESSION['itemworn'];
									    		$n = sizeof($tmplist);
									    		for ($i = 0; $i < $n; $i++)
									    		{
									    			$id = $tmplist[$i];
									    			$rs_itemdet = $sp->spSelectProductDM($database, $id, "");
									    			if($rs_itemdet->num_rows)
													{
														while($row = $rs_itemdet->fetch_object())
														{						
															$code = $row->ProductCode;
															$name = $row->ProductName;
														}
														$rs_itemdet->close();
													}
									    			echo "<tr>
									    					<td height='20'>&nbsp;</td>
															<td height='20'>$code - $name</td>
										    			</tr>";									    			
									    		}
									    	}
									    ?>
									    </table>
									</div></td>
								</tr>
								</table>
								<br>
							</td>
						</tr>
						<tr>
							<td align="center">
								<input type="hidden" name="hCallOut" value="<?php echo $cnt_callout; ?>">
								<input type="hidden" name="hViolator" value="<?php echo $cnt_violator; ?>">
								<input type="hidden" name="hHeroed" value="<?php echo $cnt_heroed; ?>">
								<input type="hidden" name="hWornBy" value="<?php echo $cnt_wornby; ?>">
								<input type="hidden" name="hPageID" value="<?php echo $_GET['PID']; ?>">
								<input name="btnBack" id="btnCancel" type="submit" class="btn" value="Back to List">
								<input name="btnSaveInfo" id="btnSaveInfo" type="submit" class="btn" value="Save" onClick="return confirmSave();">
								<input name="btnCancel" id="btnCancel" type="submit" class="btn" value="Cancel">
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