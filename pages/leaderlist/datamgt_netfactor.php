<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<?php 
/* 
 *  Modified by: marygrace cabardo 
 *  10.09.2012
 *  marygrace.cabardo@gmail.com
 */
	include IN_PATH.DS."scNetFactor.php";
?>
	
<script type="text/javascript">
function trim(s)
{
	  var l=0; var r=s.length -1;
	  while(l < s.length && s[l] == ' ')
	  {	l++; }
	  while(r > l && s[r] == ' ')
	  {	r-=1;	}
	  return s.substring(l, r+1);
}
	
function confirmSave()
{
	  msg = '';
	  obj = document.frmNetFactor.elements;
	  	  
	  // TEXT BOXES
	  if (trim(obj["cboPMG"].value) == 0) msg += '   * PMG Code \n';
	  //if (trim(obj["txtNetFactor"].value) == '') msg += '   * Net Factor \n';	
	  if (!isNumeric(obj["txtNetFactor"].value)) msg += '   * Invalid Net Factor \n';
	    
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
		<?php include("nav.php"); ?>
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
    				<td class="txtgreenbold13">Net Factor</td>
    				<td>&nbsp;</td>
  				</tr>
				</table>
				<br />
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="40%" valign="top">
        				<form name="frmSearchNetFactor" method="post" action="index.php?pageid=124">
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
						  				<input name="txtfldsearch" type="text"   class="txtfield" id="txtSearch" size="20">
						  				<input name="btnSearch"    type="submit" class="btn" value="Search" value="<?php echo $ksSearchTxt; ?>">
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
		          			<td class="tabmin2"><span class="txtredbold">List of Net Factor</span></td>
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
		                      				<td width="45%" height='25'><div align="left"   class="padl5 bdiv_r"><span class="txtpallete">PMG</span></div></td>
		                      				<td width="20%" height='25'><div align="center" class="bdiv_r">      <span class="txtpallete">Start Date</span></div></td>
		                      				<td width="20%" height='25'><div align="center" class="bdiv_r">      <span class="txtpallete">End Date</span></div></td>
		                      				<td width="20%" height='25'><div align="right"  class="padr5">       <span class="txtpallete">Net Factor</span></div></td>
		                				</table>
	                				</td>
		              			</tr>
		              			<tr>
		                			<td class="bordergreen_B"><div class="scroll_300">
		                				<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">								
								  			<?php 
												if($rs_netfactor->num_rows)
												{
													$rowalt = 0;
													while($row = $rs_netfactor->fetch_object())
													{
														$rowalt++;
														($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
														$sdate = date("m/d/Y", strtotime($row->StartDate));
														$edate = date("m/d/Y", strtotime($row->EndDate));
														$nfactor = number_format($row->NetFactor, 2);
														echo "<tr align='center' class='$class'>
								  									<td height='25' class='borderBR padl5' width='45%' align='left'><span class='txt10'>$row->PMGName</span></td>
								  									<td height='25' class='borderBR' width='20%' align='center'>&nbsp;<span class='txt10'>$sdate</td>
																	<td height='25' class='borderBR' width='20%' align='center'>&nbsp;<span class='txt10'>$edate</td>
																	<td height='25' class='borderBR padr5' width='20%' align='center'>&nbsp;<span class='txt10'>$nfactor</td>
				  											</tr>";
													}
													$rs_netfactor->close();
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
        			<form name="frmNetFactor" method="post" action="includes/pcNetFactor.php">
            			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
             			<tr>
               				<td class="tabmin">&nbsp;</td>
               				<td class="tabmin2"><span class="txtredbold">Details</span></td>
               				<td class="tabmin3">&nbsp;</td>
             			</tr>
           				</table>
           				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
						<tr>
               				<td class="bgF9F8F7">
                				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                       				<?php 
									if (isset($_GET['msg'])) {
										$message = strtolower($_GET['msg']);
										$success = strpos("$message","success"); 
									echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
									} 
									else if(isset($_GET['errmsg'])) {
										$errormessage = strtolower($_GET['errmsg']);
										$error = strpos("$errormessage","error"); 
										echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";
									}
									?>
									<tr>
											<td colspan="3" height="15">&nbsp;</td>
									</tr>
                					<tr>
                    					<td height="20" width="25%" align="right" class="txtpallete">PMG Code :</td>
                    					<td height="20" width="5%">&nbsp;</td>
                    					<td height="20" width="70%">
                    						<select name="cboPMG" class="txtfield" style="width: 200px;">
                    						<option value="0">[SELECT HERE]</option>
											<?php
												if($rs_pmglist->num_rows)
												{
													while($row = $rs_pmglist->fetch_object()) {
															echo "<option value='$row->ID'>$row->Name</option>";
													}
													$rs_pmglist->close();
												}
											?>
                    						</select>
                    					</td>
            						</tr>
            						<tr>
                    					<td height="20" align="right" class="txtpallete">Net Factor :</td>
                    					<td height="20" width="1%">&nbsp;</td>
                    					<td height="20"><input name="txtNetFactor" type="text" class="txtfieldg" id="txtNetFactor" size="20" value="">
                    					</td>
            						</tr>
            						<tr>
                    					<td height="20" align="right" class="txtpallete">Start Date :</td>
                    					<td height="20">&nbsp;</td>
                    					<td height="20">
                    						<input name="txtStartDate" type="text" class="txtfieldgs" id="txtStartDate" size="20" readonly="yes" value="<?php echo $startDate;?>">
                							<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value="">
                							<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
                    					</td>
            						</tr>
            						<tr>
                    					<td height="20" align="right" class="txtpallete">End Date :</td>
                    					<td height="20">&nbsp;</td>
                    					<td height="20">
                    						<input name="txtEndDate" type="text" class="txtfieldgs" id="txtEndDate" size="20" readonly="yes" value="<?php echo $endDate; ?>">
                							<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
                							<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
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
            			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
              			<tr>
                    		<td align="center">
                    			<input name="btnSave" type="submit" class="btn" value="Save" onclick="return confirmSave();">
                    			<input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=124'" />
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

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtEndDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divEndDate",
		button         :    "anchorEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>