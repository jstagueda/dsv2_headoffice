<?PHP 
	include IN_PATH.DS."scCreateWriteOff.php";
	global $database;
        
        /*
         * @author: jdymosco
         * @date: May 11, 2013
         * @update: Added "As of Date" field, identified by user will be the basis of days overdue counting to qualify on the criteria for write-off creation.
         */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
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

function checkAll(bin) 
{	
	var elms = document.frmCreateWriteOff.elements;

	for (var i = 0; i < elms.length; i++)
	{
  		if (elms[i].name == 'chkInclude[]') 
  		{	
  			elms[i].checked = bin;		  
	  	}
  	}		
}

function checker()
{
	var ml = document.frmCreateWriteOff;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkInclude[]" && e.checked == true) 
	    {
			return true;
	    }
	}
	return false;
}

function confirmSubmit()
{
	if (!checker())
	{
		alert('Please select Dealer(s).');
		return false;		
	}
	else
	{
		if (confirm('Are you sure you want to create Write-Off?') == false)
			return false;
		else
			return true;
	}
}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
		<?PHP
			include("nav_dealer.php");
		?>
	</td>
    <td class="divider">&nbsp;</td>
    <td valign="top">
    	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Dealer Management </span></td>
        </tr>
    	</table>
    	<br />
    	
		<form name="frmCreateWriteOff" method="post" action="index.php?pageid=91">
		<body>
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  		<tr>
    		<td valign="top">
      			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
    				<td class="txtgreenbold13">Create Write-Off</td>
    				<td>&nbsp;</td>
  				</tr>
				</table>
				<?php
				if ($errmsg != "")
				{
				?>
				<br>
				<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="70%" class="txtredsbold">&nbsp;<b><?php echo $errmsg; ?></b></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<?php		
					}
				?>
				<?php
				if ($msg != "")
				{
				?>
				<br>
				<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="70%" class="txtblueboldlink">&nbsp;<b><?php echo $msg; ?></b></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<?php		
					}
				?>
				<br />
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td width="50%" valign="top">
						<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" class="">
        				<tr>
          					<td width="20%" valign="top">
          						<table width="75%"  border="0" cellspacing="1" cellpadding="0">
          						<tr>
          							<td height="10">&nbsp;</td>
          						</tr>
          						<tr>
            						<td height='20' colspan='2' class='txt10'><strong>Include :</strong></td> 
    							</tr>
    							<?php 
        							if ($rs_gsuType->num_rows)
            						{
            							$cnt = 0;
            							while($row = $rs_gsuType->fetch_object())
             							{                     	
             								$id = $row->ID;   							
             	   							$code = $row->Code;
             	   							$Name = $row->Name;
             	   							
	                     	   				echo "<tr>
														<td height='20' class='txt10' width='10%' align='left'><input name='chkIDGSU$id' type='checkbox' value='$id'></td>
	                                					<td height='20' class='txt10' width='90%' align='left'>$Name</td>                                            	
	                            				</tr>";
	                     	   				$cnt++;
             	 						} 
             							echo "<input name='txtCountInclude' type='hidden' value='$cnt'>";
            						}                     		 
    							?>
          						</table>
            				</td>
          					<td width="35%" valign="top">	 
                        		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1">
                        		<tr>
          							<td height="10">&nbsp;</td>
          						</tr>
                        		<tr>
                            	 	<td height='20' colspan='2' class='txt10'><strong>Exclude :</strong></td> 
                                </tr>
                                 <?php 
                            		if ($rs_tpipda->num_rows)
        							{
        								$cntTpipda = 0;
        							
        								while($row = $rs_tpipda->fetch_object())
         	   							{                     	
         	   								$id = $row->ID;   							
         	   								$code = $row->Code;
         	   								$Name = $row->Name;
         	   							
         	   								 echo "<tr>
                              					  	<td height='20' class='txt10' width='5%' align='left'><input name='chkID$id' type='checkbox' value='$id'></td>
                              					  	<td height='20' class='txt10' width='95%' align='left'>$Name</td>                                            	
                           				          </tr>";
         	   								 $cntTpipda++;
         	   							} 
         	   							echo "<input name='txtCountExclude' type='hidden' value='$cntTpipda'>";
        							} 
                                ?>
      							</table>
  							</td>                                                        
						</tr>
                        <tr>
                            <td height="20" align="left" colspan="2">
                                <strong>As of Date:</strong>
                                <input class="txt10" type="text" name="asOfDate" id="asOfDate" value="" />
                            </td>
                        </tr> 
                        <tr>
                        	<td height="20">&nbsp;</td>
                        </tr>
                        <tr>
                        	<td height="20"  align="left" colspan="2"><input name="btnGenerate" type="submit" class="btn" value="Generate List"></td>
                        </tr>
                        <tr>
                        	<td height="20">&nbsp;</td>
                        </tr>
						</table>
					</td>
				</tr>
				</table>
				<br>
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="100%" valign="top">
      					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
        				<tr>
          					<td class="tabmin">&nbsp;</td>
          					<td class="tabmin2"><span class="txtredbold">Dealer List</span></td>
          					<td class="tabmin3">&nbsp;</td>
        				</tr>
      					</table>
      					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
        				<tr>
          					<td valign="top" class="bgF9F8F7">
          						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              					<tr>
                					<td class="tab">
                						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                    					<tr align="center">
											<td width="5%" class="bdiv_r"><div align="center"> <input name="chkAll" type="checkbox" id="chkAll"  onclick="checkAll(this.checked);" /></div></td>	
											<td width="15%" class="bdiv_r"><div align="center">&nbsp;<span class="txtredbold">Code</span></div></td>
											<td width="20%" class="bdiv_r"><div align="center"><span class="txtredbold">IGS Name</span></div></td>
											<td width="20%" class="bdiv_r"><div align="center"><span class="txtredbold">IBM Name</span></div></td>
											<td width="10%" class="bdiv_r"><div align="center"><span class="txtredbold">Days Overdue</span></div></td>
											<td width="10%" class="bdiv_r"><div align="center"><span class="txtredbold">Overdue Amount</span></div></td>
											<td width="20%"><div align="center"><span class="txtredbold">Reason</span></div></td>
                      					</tr>
                						</table>
            						</td>
          						</tr>
              					<tr>
                					<td class="bordergreen_B"><div class="scroll_300">
                   					<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
										<?php 					
										if ($rs_dealers->num_rows)
					                     {  
					                     	$n = $rs_dealers->num_rows;
					                    
					                    		$cnt=0;
					                    		echo  "<input name='hdnCountResult' type='hidden' value='$n'/> ";
					                    		
					                     		while($row = $rs_dealers->fetch_object())
					                     	    {                     	  
						                     	   	$cnt ++;
						                            ($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
						                     		$id = $row->ID;
						                     		$code = $row->IGSCode;
						                     		$igsName =$row->IGSName;
						                     		$ibmName= $row->IBMID;
						                     		$daysdue = $row->DaysDue;
						                     		$amountDue = $row->OverdueAmount;
					                     	
													  echo "<tr class='$alt'>
													  			<td width='5%' height='20' class='borderBR' align='center'><input name='chkInclude[]' type='checkbox' id='chkInclude[]' value='$id'></td>
													  			<td width='15%' height='20' class='borderBR padl5' align='left'><span class='txt10'>$code</span></td>
												 			 	<td width='20%' height='20' class='borderBR padl5' align='left'>$igsName</td>
													  			<td width='20%' height='20' class='borderBR padl5' align='left'>";
														   			$rs_ibmName = $sp->spSelectCustomer($database, $ibmName,'');
														   			if($rs_ibmName->num_rows)
														   			{	
														   				while($row_ibmNames = $rs_ibmName->fetch_object())
														   				{
														   					$ibmNames = $row_ibmNames->Name;
														   				}
														   				$rs_ibmName->close();
														   			}		  
														  			echo "   $ibmNames
														  		</td>
													  			<td width='10%' height='20' class='borderBR padr5' align='right'>$daysdue</td>
													  			<td width='10%' height='20' class='borderBR padr5' align='right'>$amountDue<input name='hdnPastDue$id' type='hidden' value='$amountDue'/></td>
													  			<td width='20%' height='20' class='borderBR' align='center' valign='middle'>";
													  				echo "<select name='cboReason$id' class='txtfield' style='width:150px;'>";
								                          			if ($rs_reasons->num_rows)
								                           			{
								                           				while ($row_reason = $rs_reasons->fetch_object())
								                                		{
								                                			$id = $row_reason->ID;
								                                    		$reasonName = $row_reason->Name;
								                                    		($id == $reasonid) ? $sel = 'selected' : $sel = '';
								                                    		echo "<option $sel value='$id'>$reasonName</option>";
								                                		}
								                                		$rs_reasons->data_seek(0);
								                            		} 
								                     				echo "</select>
																</td>
															</tr>" ;
					                     		}
					                     	}
					                    	else
					                    	{
				                    			echo "<tr align='center'><td height='20' class='bgFFFFFF' colspan='7'><span class='txtredsbold'>No record(s) to display.</span></td></tr>";	
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
	 				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  				<tr>
	    				<td align="center">
	    					<input name="btnSubmit" type="submit" class="btn" value="Submit" onClick="return confirmSubmit();">
	    					<input name="btnCancel" type="submit" class="btn" value="Cancel">
	    					<input name="hdnInclude" type="hidden"   value="<?php echo ($_GET['tmpI']?$_GET['tmpI']:'');?>"   > 
	    					<input name="hdnExclude" type="hidden"  value="<?php echo ($_GET['tmpE']?$_GET['tmpE']:'');?>" ></td>
    					</td>
  					</tr>
					</table>
				</td>
				<td width="20%">&nbsp;</td>
				<td width="20%" valign="top">&nbsp;</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
	<br>
</td>
</tr>
</table>
</body>
</form>
<script>
    $(document).ready(function(){ doDatePickerLoad("#asOfDate"); });
</script>