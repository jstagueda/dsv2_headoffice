<?php 
/* 
 *  Modified by: marygrace cabardo 
 *  10.09.2012
 *  marygrace.cabardo@gmail.com
 */
	include IN_PATH.DS."scStaffCount.php";
?>

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>	
<script type="text/javascript">
function confirmSave()
{
    msg = '';
    obj = document.frmNetFactor.elements;

    // TEXT BOXES
    if (obj["cboCampaign"].value == 0) msg += '   * Campaign \n';
    if (!isNumeric(obj["txtStaffCount"].value)) msg += '   * Invalid Staff Count \n';
    if (!isNumeric(obj["txtActiveCount"].value)) msg += '   * Invalid Active Count \n';

    if (msg != '')
    { 
        alert ('Please complete the following: \n\n' + msg);
        return false;
    }	  
    else 
    {
            if (confirm('Are you sure you want to save this transaction?') == false)
                    return false;
            else
                    return true;
    }
}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
		<?php
			include("nav.php");
		?>
	<br></td>
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
    				<td class="txtgreenbold13">Staff and Active Count</td>
    				<td>&nbsp;</td>
  				</tr>
				</table>
				<br />
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="40%" valign="top">
        				<form name="frmSearchNetFactor" method="post" action="index.php?pageid=125&txnID=<?php echo $_GET['txnID']; ?>">
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		  				<tr>
		    				<td>
		      					<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
		        				<tr>
		          					<td width="50%">&nbsp;</td>
		          					<td width="29%" align="right">&nbsp;</td>
		          					<td width="21%" align="right">&nbsp;</td>
		        				</tr>
		        				<tr>
		          					<td class="txtpallete" colspan="3">&nbsp;&nbsp;Search :         
						  				<input name="txtfldsearch" type="text" class="txtfield" id="txtSearch" size="20">
						  				<input name="btnSearch" type="submit" class="btn" value="Search" value="<?php echo $ksSearchTxt; ?>">
				  					</td>
		        				</tr>
		        				<tr>
		          					<td>&nbsp;</td>
		          					<td align="right">&nbsp;</td>
		          					<td align="right">&nbsp;</td>
		        				</tr>
		    					</table>
	    					</td>
	  					</tr>
						</table>
        				</form>
		      			<br>
		      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        		<tr>
		          			<td class="tabmin">&nbsp;</td>
		          			<td class="tabmin2"><span class="txtredbold">&nbsp;List</span></td>
		          			<td class="tabmin3">&nbsp;</td>
		        		</tr>
		      			</table>
		      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		        		<tr>
		          			<td valign="top" class="bgF9F8F7">
		          				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		              			<tr>
		                			<td class="tab bordergreen_T">
		                				<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
		                    			<tr align="center">
		                      				<td width="40%" height='25'><div align="left" class="padl5 bdiv_r"><span class="txtredbold">Campaign Code - Description</span></div></td>
		                      				<td width="30%" height='25'><div align="center" class="bdiv_r"><span class="txtredbold">Staff Count</span></div></td>
		                      				<td width="30%" height='25'><div align="center" class="padr5"><span class="txtredbold">Active Count</span></div></td>
		                				</table>
	                				</td>
		              			</tr>
		              			<tr>
		                			<td class="bordergreen_B"><div class="scroll_300">
		                				<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">								
								  			<?php 
												if($rs_staffcount->num_rows)
												{
													$rowalt = 0;
													while($row = $rs_staffcount->fetch_object())
													{
														$rowalt++;
														($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
														$staff = number_format($row->StaffCount, 2);
														$active = number_format($row->ActiveCount, 2);
														echo "<tr align='center' class='$class'>
								  									<td height='20' class='borderBR padl5' width='40%' align='left'><a href='index.php?pageid=125&txnID=$row->ID' class='txtnavgreenlink'>$row->Campaign</a></td>
																	<td height='20' class='borderBR padr5' width='30%' align='center'><span class='txt10'>$staff</td>
																	<td height='20' class='borderBR padr5' width='30%' align='center'><span class='txt10'>$active</td>
				  											</tr>";
													}
													$rs_staffcount->close();
												}
												else
												{
													echo "<tr align='center'><td height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
												}
												?>
	                  						</table>
              						</div></td>
		              			</tr>
		          				</table>
	          				</td>
	        			</tr>
		      			</table>
					</td>
					<td width="2%">&nbsp;</td>
        			<td width="40%" valign="top">
        				<form name="frmNetFactor" method="post" action="includes/pcStaffCount.php?txnID=<?php echo $_GET['txnID']; ?>">
            			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
             			<tr>
               				<td class="tabmin">&nbsp;</td>
               				<td class="tabmin2"><span class="txtredbold">Details</span></td>
               				<td class="tabmin3">&nbsp;</td>
             			</tr>
           				</table>
           				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
						<tr>
               				<td class="bgF9F8F7">
                				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
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
									<tr>
										<td colspan="3" height="15">&nbsp;</td>
									</tr>
                					<tr>
                    					<td height="20" width="30%" align="right" class="txtpallete">Campaign Code - Description :</td>
                    					<td height="20" width="5%">&nbsp;</td>
                    					<td height="20" width="65%" >
                    						<select name="cboCampaign" id="cboCampaign"  style="width:200px;">
                    							<option value="0">[SELECT HERE]</option>
                    							<?php
                    								if($rs_campaign->num_rows)
                    								{
                    									while($row = $rs_campaign->fetch_object())
                    									{
                    										if ($campaignid == $row->ID)
                    										{
                    											$sel = "selected";
                    										}
                    										else
                    										{
                    											$sel = "";
                    										}
                    										echo "<option $sel value='$row->ID'>$row->Code - $row->Name</option>";                    										
                    									}
                    									$rs_campaign->close();                    									
                    								}
                    							?>
                    						</select>
                    					</td>
            						</tr>
            						<tr>
                    					<td height="20" align="right" class="txtpallete">Staff Count :</td>
                    					<td height="20">&nbsp;</td>
                    					<td height="20"><input name="txtStaffCount" type="text" class="txtfieldg" id="txtStaffCount" size="20" value="<?php echo $scount; ?>">
                    					</td>
            						</tr>
            						<tr>
                    					<td height="20" align="right" class="txtpallete">Active Count :</td>
                    					<td height="20">&nbsp;</td>
                    					<td height="20"><input name="txtActiveCount" type="text" class="txtfieldg" id="txtActiveCount" size="20" value="<?php echo $acount; ?>">
                    					</td>
            						</tr>
                					</table>		
    						</td>
         				</tr>
            			<tr>
              				<td class="bgF9F8F7">&nbsp;</td>
              			</tr>
            			<tr>
               				<td class="bgF9F8F7">&nbsp;</td>
             			</tr>
           				</table>
        				<br>
            			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
              			<tr>
                    		<td align="center">
                    			<input name="btnSave" type="submit" class="btn" value="<?php if($_GET['txnID'] != 0) { echo 'Update'; } else { echo 'Save'; } ?>" onclick="return confirmSave();">
                    			<input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=125'" />
                			</td>
          				</tr>
            			</table>
            			</form>
        			</td>
				</tr>
				</table>
			</td>
		</tr>
 		</table>
 		</form>
	</td>
</tr>
</table>