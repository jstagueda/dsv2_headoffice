<script type="text/javascript" src="js/datamgt_validation.js"></script>

<?php 
	include IN_PATH.DS."scBank.php";		
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

function form_validation(x)
{
	lang = 0;
	def = 0;
	count = 0;
	msg = '';
	str = '';
	obj = document.frmBank.elements;
	
	// TEXT BOXES
	if (trim(obj["name"].value) == '') msg += '   * Name \n';
	if (trim(obj["account"].value) == '')msg +=  '   * GL Account \n';

	if (msg != '')
	{ 
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	}
	else 
	{
		if(x == 1)
		{
			if (confirm('Are you sure you want to save this transaction?') == false)
				return false;
		  	else
			  	return true;
		}
		else if (x == 2)
		{
			if (confirm('Are you sure you want to update this transaction?') == false)
		  		return false;
		  	else
		  		return true;
		}
	}
}
	
function form_validation_delete()
{
	if (confirm('Are you sure you want to delete this record?') == false)
		return false;
	else
		return true;
}
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
		<?php
			include("nav.php");
		?>
      <br>
	</td>
    <td class="divider">&nbsp;</td>
    <td valign="top">
    	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management</span></td>
        </tr>
    	</table>
    	<br />
   		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  		<tr>
    		<td valign="top">
      			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
    				<td class="txtgreenbold13">Bank</td>
    				<td>&nbsp;</td>
				</tr>
				</table>
				<br />
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="33%" valign="top">
    					<form name="frmSearchDept" method="post" action="index.php?pageid=152&bID=<?php echo $_GET['bID']; ?>">
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
                      				<td colspan="3">
                         				Search            
                              			<input name="searchTxtFld" type="text" class="txtfield" id="txtSearch" size="20" value="<?php echo $dSearchTxt; ?>">
                              			<input name="btnSearch" type="submit" class="btn" value="Search">
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
		          			<td class="tabmin2"><span class="txtredbold">List of Bank</span></td>
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
		                      				<td width="40%" class="bdiv_r"><div align="center">&nbsp;<span class="txtredbold">Name</span></div></td>
		                      				<td width="60%"><div align="center"><span class="txtredbold">GL Account</span></div></td>
	                      				</tr>
		                				</table>
	                				</td>
	              				</tr>
		              			<tr>
		                			<td class="bordergreen_B"><div class="scroll_300">
                        				<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                                    	<tr align="center">
                                    		<?php
                                      		if($rs_bank->num_rows)
											{												
												$rowalt = 0;
												while($row = $rs_bank->fetch_object())
												{
													$rowalt++;
													($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
													echo "<tr align='center' class='$class'>
								  					<td height='20' class='borderBR' width='40%' align='left'>&nbsp;<a href='index.php?pageid=152&bID=$row->ID&searchedTxt=$dSearchTxt' class='txtnavgreenlink'>$row->Name</a></td>
								  					<td height='20' class='borderBR' width='60%' align='left'>&nbsp;<span class='txt10'>$row->GLAccount</td></tr>";
												} 
												$rs_bank->close();
											}
											else
											{
												echo "<tr align='center'><td height='20' class='borderBR' colspan='2'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
											}
									  		?>
                                		</tr>
		                  				</table>
	                  				</div></td>
	              				</tr>
		          				</table>
	          				</td>
		        		</tr>
		      			</table>
					</td>
					<td width="2%">&nbsp;</td>
        			<td width="60%" valign="top">
        				<form name="frmBank" method="post" action="includes/pcBank.php">
            			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
             			<tr>
               				<td class="tabmin">&nbsp;</td>
               				<td class="tabmin2"><span class="txtredbold">Bank Details</span>&nbsp;</td>
               				<td class="tabmin3">&nbsp;</td>
         				</tr>
           				</table>
           				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
            			<tr>
               				<td class="bgF9F8F7">
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
            				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                			<tr>
			                    <td height="30" width="25%" align="right" class="txt10">Name :</td>
			                    <td height="30" width="5%">&nbsp;</td>
			                    <td height="30" width="70%"><input name="name" type="text" class="txtfield" value="<?php echo $name; ?>" size="40" maxlength="15" <?php if ($_SESSION['ismain'] != 1) { echo 'readonly = "yes"'; } ?>>                     
									<?php
										if ($bID > 0)
										{
										  echo "<input type=\"hidden\" name=\"hdnBankID\" value=\"$bID\" />";
										}
									?>
			                    </td>
			                </tr>
			                <tr>
			                    <td height="30" width="25%" align="right" class="txt10">GL Account :</td>
			                    <td height="30" width="5%">&nbsp;</td>
			                    <td height="30" width="70%"><input name="account" type="text" class="txtfield" value="<?php echo $account; ?>" size="40" maxlength="30" <?php if ($_SESSION['ismain'] != 1) { echo 'readonly = "yes"'; } ?>></td>
			                </tr>
			                <tr>
			                	<td colspan="3" height="20">&nbsp;</td>
			                </tr>
                			</table>		
        				</td>
         			</tr>
           			</table>
            		<br>
            		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
              		<tr>
                		<td align="center">
                		<?php
							if ($_SESSION['ismain'] == 1) 
							{
								if($bID > 0)
								{
									echo "<input name='btnUpdate' type='submit' class='btn' value='Update' onClick='return form_validation(2);' />";
								}
								else
								{
									echo "<input name='btnSave' type='submit' class='btn' value='Save' onClick='return form_validation(1);'/>"; 	
								}						 
							}
						?>                        
                      	<input name="btnCancel2" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=152'" />
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
 	<br>
    </td>
</tr>
</table>