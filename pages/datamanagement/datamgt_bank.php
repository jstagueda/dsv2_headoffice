<script type="text/javascript" src="js/datamgt_validation.js"></script>
<?php 
/* 
 *  Modified by: marygrace cabardo 
 *  10.08.2012
 *  marygrace.cabardo@gmail.com
 */
	include IN_PATH.DS."scBank.php";		
?>

<script type="text/javascript">
function trim(s) {
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ') 	{	
	  l++; 
	}	
	while(r > l && s[r] == ' ')	{
 	  r-=1;	
	}	
	return s.substring(l, r+1);
}



function form_validation(x) {
	lang = 0;
	def = 0;
	count = 0;
	msg = '';
	str = '';
	obj = document.frmBank.elements;
	
	// TEXT BOXES
	if (trim(obj["code"]   .value) == '') msg +=  '   * Code \n';
	if (trim(obj["name"]   .value) == '') msg +=  '   * Name \n';
	if (trim(obj["account"].value) == '') msg +=  '   * Account Number \n';
	if (trim(obj["branch"].value) == '')  msg +=  '   * Branch Name \n';

	if (msg != '') 	{ 
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	} 	else 	{
		if(x == 1) 	{
			if (confirm('Are you sure you want to save this transaction?') == false)
				return false;
		  	else
			  	return true;
		} else if (x == 2) {
			if (confirm('Are you sure you want to update this transaction?') == false)
		  		return false;
		  	else
		  		return true;
		}
	}
}
	
function form_validation_delete() {
	if (confirm('Are you sure you want to delete this record?') == false)
		return false;
	else
		return true;
}
</script>
<!-- first Table s-->
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td width="200" valign="top" class="bgF4F4F6">
		<?php
			include("nav.php");
		?>
    <br>
	</td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 610px; display: block;">
	
		<!-- second Table s-->
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management</span></td>
        </tr>
    	</table>
		<!-- second Table e-->
		
    <br />
   
		<!-- third Table s-->
   		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td valign="top">
			<!-- forth Table s-->
      			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
    				<td class="txtgreenbold13">Bank</td>
    				<td>&nbsp;</td>
				</tr>
				</table>
				<!-- forth Table e-->
				<br />
				<!-- fifth Table s seach products-->
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="33%" valign="top">
						<!-- first form s seach -->
    					<form name="frmSearchDept" method="post" action="index.php?pageid=152&bID=<?php echo $_GET['bID']; ?>">
						<!-- sixth Table s seach -->
                                                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        		<tr>
		          			<td class="tabmin">&nbsp;</td>
		          			<td class="tabmin2"><span class="txtredbold">Action</span></td>
		          			<td class="tabmin3">&nbsp;</td>
		        		</tr>
		      			</table>
            			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
              			<tr>
                			<td>
								<!-- seventh Table s seach -->
                				<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    			<tr>
                      				<td width="50%" colspan="3" align="right">&nbsp;</td>
                    			</tr>
                    			<tr>
                      				<td colspan="3" class="txtpallete"> &nbsp; Search : &nbsp;            
                              			<input name="searchTxtFld" type="text"   class="txtfield" id="txtSearch" size="20" value="<?php echo $dSearchTxt; ?>">
                              			<input name="btnSearch"    type="submit" class="btn btn-primary btn-search" value="Search">
                  					</td>
                				</tr>
                    			<tr>
                      				<td>&nbsp;</td>
                      				<td align="right">&nbsp;</td>
                      				<td align="right">&nbsp;</td>
                				</tr>
                				</table>
								<!-- seventh Table e seach -->
            				</td>
          				</tr>
            			</table><!--paging-->
						<!-- sixth Table e seach -->
        				</form><!-- first form e seach -->
						
		      			<br>
						
						<!-- eight Table s -->		
		      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        		<tr>
		          			<td class="tabmin">&nbsp;</td>
		          			<td class="tabmin2"><span class="txtredbold">List of Bank</span></td>
		          			<td class="tabmin3">&nbsp;</td>
		        		</tr>
		      			</table>
						<!-- eight Table e -->	
						
						<!-- nineth Table s -->
		      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
		        		<tr>
		          			<td >
								<!-- tenth Table s -->
		          				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		              			<tr>
		                			<td >
										<!-- eleventh Table s -->
		                				<table width="100%"  border="0" cellpadding="0" class = "bgFFFFFF" cellspacing="0" >
		                    			<tr >		                                    
											<td height='20' class='padl5 txtpallete borderBR' width='15%' align='left'>Code</span></td>
											<td height='20' class='padl5 txtpallete borderBR' width='30%'  align='left'>Bank Name</td>	
											<td height='20' class='padl5 txtpallete borderBR' width='25%' align='left'>Account No.</td>
											<td height='20' class='padl5 txtpallete borderBR' width='30%'  align='left'>Bank Branch</td>												
	                      				</tr>
		                				</table>
										<!-- eleventh Table e -->
	                				</td>
	              				</tr>
		              			<tr>
		                			<td class="bordergreen_B"><div class="scroll_300">
                        				<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
                                    	<tr align="center">
                                    		<?php
                                      		if($rs_bank->num_rows)
                                                {		
                                                        $rowalt = 0;
                                                        while($row = $rs_bank->fetch_object())
                                                        {
                                                                $rowalt++;
                                                                ($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
                                                                    echo "<tr align='left' class='$class'>
                                                                                <td height='30' class='padl5 borderBR' width='15%' align='left'>
                                                                                        <a href='index.php?pageid=152&bID=$row->ID&searchedTxt=$dSearchTxt' 
                                                                                                class='txtnavgreenlink'>$row->Code
                                                                                        </a>
                                                                                </td>
                                                                                <td class='padl5 borderBR' width='30%'  align='left'>$row->Name</td>
                                                                                <td class='padl5 borderBR' width='25%' align='left'>&nbsp;$row->GLAccount</td>
                                                                                <td class='padl5 borderBR' width='30%'  align='left'>&nbsp;$row->Branch</td>
                                                                            </tr>";
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
								if (isset($_GET['msg'])){    
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
                                        <br>
                                        <br>
            				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
			                    <td height="12" width="5%">&nbsp;</td>
			                    <td height="12" width="5%">&nbsp;</td>
			                    <td height="12" width="5%">&nbsp;</td>			                   
			                </tr>
            				<tr>
			                    <td height="30" width="25%" align="right" class="txtpallete">Code :</td>
			                    <td height="30" width="5%">&nbsp;</td>
			                    <td height="30" width="70%">
									<input name="code" type="text" class="txtfield" <?php if($_GET['bID'] != ""): ?> readonly = "readonly" <?php endif; ?> value="<?php echo $code; ?>" size="40" 	maxlength="30" 
										<?php 
											if ($_SESSION['ismain'] != 1) { 
												echo 'readonly = "yes"'; 
											} 
										?>>
								</td>
			                </tr>
                			<tr>
			                    <td height="30" width="25%" align="right" class="txtpallete">Bank Name :</td>
			                    <td height="30" width="5%">&nbsp;</td>
			                    <td height="30" width="70%">
								<input name="name" type="text" class="txtfield" 
										value="<?php echo $name; ?>" size="40" maxlength="200" <?php 
												if ($_SESSION['ismain'] != 1) {
														echo 'readonly = "yes"'; 
												} 
										?>>                     
										<?php
												if ($bID > 0)
												{
													echo "<input type=\"hidden\" name=\"hdnBankID\" value=\"$bID\" />";
												}
										?>
			                    </td>
			                </tr>
			                <tr>
			                    <td height="30" width="25%" align="right" class="txtpallete">Account Number :</td>
			                    <td height="30" width="5%">&nbsp;</td>
			                    <td height="30" width="70%">
									<input name="account" type="text" class="txtfield" 
										value="<?php echo $account; ?>" size="40" maxlength="30" <?php 
											if ($_SESSION['ismain'] != 1) {
												echo 'readonly = "yes"'; } 
										?>>
								</td>
			                </tr>

							<tr>
			                    <td height="30" width="25%" align="right" class="txtpallete">Bank Branch :</td>
			                    <td height="30" width="5%">&nbsp;</td>
			                    <td height="30" width="70%">
									<input name="branch" type="text" class="txtfield" 
										value="<?php echo $branch; ?>" size="40" maxlength="100" <?php 
											if ($_SESSION['ismain'] != 1) { 
												echo 'readonly = "yes"'; 
											} 
										?>>                     
									<?php
										if ($bID > 0)
										{
										  echo "<input type=\"hidden\" name=\"hdnBankID\" value=\"$bID\" />";
										}
									?>
			                    </td>
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
							if ($_SESSION['ismain'] == 1) {
								if($bID > 0) {
									echo "<input name='btnUpdate' type='submit' class='btn btn-primary btn-search' value='Update' onClick='return form_validation(2);' />";
									echo "&nbsp;<input name='btnDelete' type='submit' class='btn btn-primary btn-search' value='Delete' onClick='return form_validation_delete();' />";
								}	else  {
									echo "<input name='btnSave' type='submit'   class='btn btn-primary btn-reset' value='Save' onClick='return form_validation(1);'/>"; 	
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