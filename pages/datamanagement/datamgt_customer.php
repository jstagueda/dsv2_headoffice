<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<script type="text/javascript" src="js/datamgt_validation.js"></script>
<?php
	include IN_PATH.DS."scCustomer.php";
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
	  msg = '';
	  obj = document.frmCustomer.elements;
	  
	  if (trim(obj["code"].value) == '') msg += '   * Code \n';
	  if (trim(obj["name"].value) == '') msg += '   * Name \n'; 
	  if (trim(obj["address"].value) == '') msg += '   * Address \n';
	  if (trim(obj["txnDate"].value) == '') msg += '   * Enrollment Date \n';
	  if (obj["cboSalesman"].selectedIndex == 0) msg += '   * Salesman \n';
	  if (obj["cboOutletType"].selectedIndex == 0) msg += '   * Outlet Type \n';
	  if (obj["cboTerms"].selectedIndex == 0) msg += '   * Payment Terms \n';
	  
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
    	<?PHP
			include("nav.php");
		?>

      <br></td>
    <td class="divider">&nbsp;</td>
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management </span></td>
        </tr>
    </table>
    <br />
  
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Customer </td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="33%" valign="top">
    		
            <form name="frmSearchCustomer" method="post" action="index.php?pageid=23">
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
              <tr>
                <td><table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                      <td width="50%">&nbsp;</td>
                      <td width="29%" align="right">&nbsp;</td>
                      <td width="21%" align="right">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3">
                         Search            
                              <input name="searchTxtFld" type="text" class="txtfield" id="txtSearch" size="20">
                              <input name="btnSearch" type="submit" class="btn" value="Search">
                      </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td align="right">&nbsp;</td>
                      <td align="right">&nbsp;</td>
                    </tr>
                </table></td>
              </tr>
            </table>
            </form>
       		
          <br>
          <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td class="tabmin">&nbsp;</td>
              <td class="tabmin2"><span class="txtredbold"> 	Customer List </span></td>
              <td class="tabmin3">&nbsp;</td>
            </tr>
          </table>
          <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
            <tr>
              <td valign="top" class="bgF9F8F7" height="200px">
              <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		              <tr>
		                <td class="tab bordergreen_T">
                            <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                                <tr align="center">
                                  <td width="40%"><div align="center">&nbsp;<span class="txtredbold">Code</span></div></td>
                                  <td width="60%"><div align="center"><span class="txtredbold">Name</span></div></td>
                            </table>
						</td>
		              </tr>
		              <tr>
		                <td class="bordergreen_B"><div class="scroll_300">                        
                        		<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                                    <tr>
                                   		   <?php 
												if($rs_customer->num_rows)
												{
													$rowalt=0;
													while($row = $rs_customer->fetch_object())
													{
														$rowalt++;
														($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
														echo "<tr align='center' class='$class'>
															<td height='20' class='borderBR' width='40%' align='left'>&nbsp;<span class='txt10'>\t\t\t\t\t\t$row->Code</span></td>
															<td class='borderBR' width='60%' align='left'>&nbsp;<span class='txt10'>
															<a href='index.php?pageid=23&custID=$row->ID&searchedTxt=$custSearchTxt' class='txtnavgreenlink'>
															\t\t\t\t\t\t$row->Name</a></td></tr>";
													}
													$rs_customer->close();
													
												}
												else
												{
													echo "<tr align='center'><td height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
												}
											?>
                                    </tr>                        
								</table></div>
	                    </td>
		              </tr>
		          </table>
              
              </td>              
            </tr>
            
         
            
          </table>
	</td>
	<td width="2%">&nbsp;</td>
    
        <form name="frmCustomer" method="post" action="includes/pcCustomer.php" >
            <td width="60%" valign="top">
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Customer Details</span>&nbsp;</td>
           <td class="tabmin3">&nbsp;</td>
         </tr>
       </table>
       <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
         <tr>
           <td class="bgF9F8F7"> <?php 
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
							?>&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="3">
    
                            <!--<tr>
                                <td height="20" align="right" class="txt10">Status :</td>
                                <td height="20">&nbsp;</td>
                                <td height="20">
                                            <input type="radio" name="active" value="1" checked="checked" />
                                            Active
                                            &nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="active" value="0" />
                                            Inactive
                                  </td>
                            </tr>-->

                            <tr>
                                <td height="20" width="27%" align="right" class="txt10">Code :</td>
                                <td height="20" width="3%">&nbsp;</td>
                                <td height="20" width="70%"><input name="code" type="text" class="txtfield" id="code" value="<?php echo $code; ?>" size="30" maxlength="14" onkeyup="javascript:RemoveInvalidChars(code);">
                                <?php
									if ($custID > 0)
									{
									  echo "<input type=\"hidden\" name=\"hdnCustomerID\" value=\"$custID\" />";
									}
								 ?>
                                </td>
                            </tr>
                            <tr>
                                <td height="20" width="27%" align="right" class="txt10">Name :</td>
                                <td height="20" width="3%">&nbsp;</td>
                                <td height="20" width="70%"><input name="name" type="text" class="txtfield4" value="<?php echo $name; ?>" size="30" maxlength="49" /></td>
                            </tr>
                            <tr>
                              <td height="20" align="right" class="txt10">Address :</td>
                              <td height="20">&nbsp;</td>
                              <td height="20"><input name="address" type="text" class="txtfield4" value="<?php echo $address; ?>" size="30" maxlength="99" /></td>
                            </tr>
                            <tr>
                              <td height="20" align="right" class="txt10">Enrollment Date :</td>
                              <td height="20">&nbsp;</td>
                              <td height="20">
                                <input name="txnDate" type="text" class="txtfield" id="txnDate" size="20" readonly="yes" value="<?php echo $txnDate; ?>" size="30" readonly="yes">
				                <input type="button" class="buttonCalendar" name="anchorTxnDate" id="anchorTxnDate" value=" ">
				                <div id="divtxnDate" style="background-color : white; position:absolute; visibility:hidden;"> </div>
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
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="tabmin">&nbsp;</td>
            <td class="tabmin2"><span class="txtredbold">Other Details</span>&nbsp;</td>
            <td class="tabmin3">&nbsp;</td>
        </tr>
        </table>
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
            <tr>
                <td class="bgF9F8F7">&nbsp;</td>
            </tr>
            <tr>
                <td class="bgF9F8F7">
                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="27%" height="30" align="right" class="txt10">Salesman :</td>
                            <td height="30" width="3%">&nbsp;</td>
                            <td height="30" width="70%">
                                <select name="cboSalesman" style="width:200px" class="txtfield">
                                    <!--<option value="0">[SELECT ONE]</option>
                                    <option value="">Salesman</option>-->
                                    <?PHP
									 echo "<option value=\"0\" >[SELECT HERE]</option>";
										if ($rs_cboSalesman->num_rows)
										{
											while ($row = $rs_cboSalesman->fetch_object())
											{
											($salesmanID == $row->ID) ? $sel = "selected" : $sel = "";
											echo "<option value='$row->ID' $sel>$row->FirstName".' '.$row->MiddleName.' '.$row->LastName."</option>";											
											}
										}
										
										
										?>
                                </select>                                		
										</td>
                        </tr>			
                        <tr>
                            <td width="27%" height="30" align="right" class="txt10">Outlet Type :</td>
                            <td height="30" width="3%">&nbsp;</td>
                            <td height="30" width="70%">								
                                <!--<cfif url.Status EQ 0 or url.CustomerD EQ 0>
                                    <cfparam name="form.cboCustType" default="0">
                                    <select name="cboCustType" style="width:200px" class="txtfield">
                                        <option value="0">[SELECT ONE]</option>
                                        <cfoutput query="customertype">
                                            <cfif url.CustomerTypeID EQ #CustomerTypeID#>
                                                <cfset c = "selected">
                                            <cfelse>
                                                <cfset c = "">
                                            </cfif>
                                            <option #c# value="#CustomerTypeID#">#TypeName#</option>
                                        </cfoutput>
                                    </select>
                                    <cfparam name="form.cboCustType" default="#CustomerEdit.CustomerTypeID#">
                                </cfif>-->
                                
                            	<select name="cboOutletType" class="txtfield" style="width:200px">
                                    <!--<option value="0">[SELECT ONE]</option>
                                    <option value="">Salesman</option>-->
                                    <?PHP
									 echo "<option value=\"0\" >[SELECT HERE]</option>";
										if ($rs_cboOutletType->num_rows)
										{
											while ($row = $rs_cboOutletType->fetch_object())
											{
											($otID == $row->ID) ? $sel = "selected" : $sel = "";
											echo "<option value='$row->ID' $sel>$row->Name</option>";											
											}
										}										
										
										?>
                                </select>          
                                
                            </td>
                        </tr>
                        <tr>
                            <td width="27%" height="30" align="right" class="txt10">Payment terms :</td>
                            <td height="30" width="3%">&nbsp;</td>
                            <td height="30" width="70%">
                                    <select name="cboTerms" style="width:200px" class="txtfield">
                                        <!--<option value="0">[SELECT ONE]</option>
                                            <option value="ID">Name</option>-->
										<?PHP
										 echo "<option value=\"0\" >[SELECT HERE]</option>";
										 
										if ($rs_cboPaymentTerms->num_rows)
										{
											while ($row = $rs_cboPaymentTerms->fetch_object())
											{
											($ptID == $row->ID) ? $sel = "selected" : $sel = "";
											echo "<option value='$row->ID' $sel>$row->Name</option>";											
											}
										}		
										?>
                                  </select></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bgF9F8F7">&nbsp;</td>
            </tr>
        </table>
    	<?php if($_SESSION['ismain'] == 1)
       	{
       	?>
        <br />
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
          <tr>
            <td align="center">
                    <?php						
                            if($custID > 0)
                            {
                                echo "<input name='btnUpdate' type='submit' class='btn' value='Update' onclick='return form_validation(2);' />";
                                echo "\t\t\t";
                                echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onclick='return form_validation_delete();' />";
                            }
                            else
                            {
                                echo "<input name='btnSave' type='submit' class='btn' value='Save' onclick='return form_validation(1);' >"; 	
                            }									
                        ?>       
                  <input name="btnCancel2" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=23'"/></td>
          </tr>
        </table>
        <? } ?>
        </td>
  </tr>
</table>
	</td>
   </tr>
 </table>
   
    </td>
  </tr>
</table>


<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txnDate",     // id of the input field
		ifFormat       :    "%m-%d-%Y",      // format of the input field
		displayArea    :	"divtxnDate",
		button         :    "anchorTxnDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>