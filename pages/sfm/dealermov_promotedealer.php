<?PHP 
	include IN_PATH.DS."scDealers.php";
	include IN_PATH.DS."pcPromoteDealer.php";

	//display error message
	$errMessage = '';
	if (isset($_GET['msg']))
    {
       $message = strtolower($_GET['msg']);
       $success = strpos("$message","success"); 
       $errMessage= "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
    }
    else if (isset($_GET['errmsg']))
    {
		$errormessage = strtolower($_GET['errmsg']);
		$error = strpos("$errormessage","error"); 
		$errMessage = "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";                        	
    }
	
	$customerRows = '';
	$cnt=0;
	
	if(isset($_POST['btnGenerate']))
	{
		if ($rs_customerall->num_rows)
		{
			$cnt=1;
			while ($row = $rs_customerall->fetch_object())
			{
			   $customerRows .= "<tr align='center'> <td width='5%' class='borderBR' height='20' align='center'><input type='checkbox' name='chkSelect[]' onclick='UnCheck();' id='chkSelect' value='$row->ID'></td>
						  <td width='30%' class='borderBR padl5' height='20' align='left'><span class='txt10'>$row->Code</span></td>
						  <td width='30%' class='borderBR padl5' height='20' align='left'><a href='#' class='txtnavgreenlink'>$row->Name</a></td>
						  <td width='35%' class='borderBR padl5' height='20' align='left'><span class='txt10'>$row->IBMCode</span></td></tr>";
			}
			$rs_customerall->close();
		}
		else
		{
			$customerRows = "<tr align='center'><td height='20' colspan='4' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.<b> </span></td></tr>";
		}		
	}
	else
	{
		$customerRows = "<tr align='center'><td height='20' colspan='4' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.<b> </span></td></tr>";
	}
	
	//customer type dropdown
    $drpCustomerType = '<option value=\'0\' >[SELECT HERE]</option>';
     if ($rs_cboCustomerType->num_rows)
    {
     while ($row = $rs_cboCustomerType->fetch_object())
      {  
      	 $sel = ($customertypeid == $row->ID) ? 'selected' : '';
         $drpCustomerType .= '<option value="'.$row->ID.'" '.$sel.'>'.$row->Name.'</option>';
       }
    }
    
    //customer type dropdown
    $drpDealerType = '<option value=\'0\' >[SELECT HERE]</option>';
     if ($rs_cboDealerType->num_rows)
    {
     while ($row = $rs_cboDealerType->fetch_object())
      {  
      	$sel = '';
        $drpDealerType .= '<option value="'.$row->ID.'" '.$sel.'>'.$row->Name.'</option>';
       }
    }
    
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

function form_validation()
{
	lang = 0;
	def = 0;
	count = 0;
	msg = '';
	str = '';
	obj = document.frmDealer.elements;
	
	// TEXT BOXES
	if (trim(obj["txtCodeEmployee"].value) == '') msg += '   * Code \n';
	if (trim(obj["txtlnameEmployee"].value) == '')msg += '   * Last Name \n';
	if (trim(obj["txtfnameEmployee"].value) == '')msg += '   * First Name \n';
	if (trim(obj["txtmnameEmployee"].value) == '')msg += '   * Middle Name \n';
	if (trim(obj["txtBdayEmployee"].value) == '')msg += '   * Birthday \n';
	if (obj["cboEmployeeType"].selectedIndex == 0) msg += '   * Employee Type \n';
	if (obj["cboDepartment"].selectedIndex == 0) msg += '   * Department \n';
	// if (obj["cboWarehouse"].selectedIndex == 0) msg += '   * Warehouse \n';

	if (msg != '')
	{ 
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	}
	else return true;
}

function checkAll(bin) 
{
	var elms = document.frmPromoteDealer.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkSelect[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
}

function UnCheck()
{
    var chkAll = document.frmPromoteDealer.chkAll;    
    chkAll.checked = false;
}

function checker()
{
	var ml = document.frmPromoteDealer;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkSelect[]" && e.checked == true) 
	    {
			return true;
	    }
	}
	return false;
}

function ConfirmPromote()
{
	var cboDealerType = document.frmPromoteDealer.cboDealerType;

	if(cboDealerType.value == 0)
	{
		alert('Select dealer type.');
		return false;
	}
	
	if (!checker())
	{
		alert('Select dealer(s) to be promoted.');
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
    	<?PHP
			include("nav_dealer.php");
		?>
	</td>
    <td class="divider">&nbsp;</td>
    <td valign="top">
    	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Dealer Movement </span></td>
        </tr>
    	</table>
    	<br />
		<table width="95%"  border="0" cellspacing="0" cellpadding="0">
  		<tr>
    		<td valign="top">
      			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
    				<td class="txtgreenbold13">Dealer Promotion</td>
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
							<td width="70%" class="txtreds">&nbsp;<b><?php echo $errmsg; ?></b></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<?php		
					}
				?>
				<br />
 				<form name="frmPromoteDealer" method="post" action="index.php?pageid=73">
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td colspan="7" height="10">&nbsp;</td>
				</tr>
				<tr>
					<td width="15%" height="20" align="right" class="padl5">Dealer Type</td>
					<td width="1%" height="20" align="left" class="padl5">:</td>
					<td width="20%" height="20" align="left" class="padl5">
						<select name="cboCustomerType" class="txtfield" style="width:190px;">
							<?php echo $drpCustomerType; ?>	
					 	</select></td>
					<td width="2%" height="20" align="left" class="padl5"></td>
					<td width="10%" height="20" align="left" class="padl5"></td>
					<td width="20%" height="20" align="left" class="padl5"></td>
					<td width="45%" height="20" align="left" class="padl5"></td>
		
				</tr>
				<tr>
					<td align="right" height="20" class="padl5">Month From </td>
					<td align="left" height="20" class="padl5">:</td>
					<td align="left" height="20" class="padl5">
						<select name="cboMonthFrom" id="cboMonthFrom" style="width:120px" class="txtfield">
							<option value="">[SELECT MONTH]</option>
		                    <option value="01" <?PHP if($monthFrom == "01") echo  "selected";    ?>>January</option>
		                    <option value="02" <?PHP if($monthFrom == "02") echo  "selected";    ?>>February</option>
		                    <option value="03" <?PHP if($monthFrom == "03") echo  "selected";    ?>>March</option>
		                    <option value="04" <?PHP if($monthFrom == "04") echo  "selected";    ?>>April</option>
		                    <option value="05" <?PHP if($monthFrom == "05") echo  "selected";    ?>>May</option>
		                    <option value="06" <?PHP if($monthFrom == "06") echo  "selected";    ?>>June</option>
		                    <option value="07" <?PHP if($monthFrom == "07") echo  "selected";    ?>>July</option>
		                    <option value="08" <?PHP if($monthFrom == "08") echo  "selected";    ?>>August</option>
		                    <option value="09" <?PHP if($monthFrom == "09") echo  "selected";    ?>>September</option>
		                    <option value="10" <?PHP if($monthFrom == "10") echo  "selected";    ?>>October</option>
		                    <option value="11" <?PHP if($monthFrom == "11") echo  "selected";    ?>>November</option>
		                    <option value="12" <?PHP if($monthFrom == "12") echo  "selected";    ?>>December</option>
						</select>
						&nbsp;
						<select name="cboYearFrom" id="cboYearFrom" style="width:60px" class="txtfield">
							<option value="1900">[ Year ]</option>
				        		<?php
				        			$st = date ("Y") ;
									for($x = $st; $x >=1970; $x--)
									{
										$newyear = $startyear + $x;
										
                                                                                if($yearFrom == $newyear) $sel = "selected";
                                                                                else $sel = "";
                                                                                
										echo "<option value='$newyear' $sel>$newyear</option>";
									}
								?>
						</select>
					</td>
					<td height="20" align="left" class="padl5">&nbsp;</td>
					<td height="20" align="right" class="padl5">To:</td>
					<td align="left" height="20" class="padl5">
						<select name="cboMonthTo" id="cboMonthTo" style="width:120px" class="txtfield">
							<option value="">[SELECT MONTH]</option>
		                    <option value="01" <?PHP if($monthTo == "01") echo  "selected";    ?>>January</option>
		                    <option value="02" <?PHP if($monthTo == "02") echo  "selected";    ?>>February</option>
		                    <option value="03" <?PHP if($monthTo == "03") echo  "selected";    ?>>March</option>
		                    <option value="04" <?PHP if($monthTo == "04") echo  "selected";    ?>>April</option>
		                    <option value="05" <?PHP if($monthTo == "05") echo  "selected";    ?>>May</option>
		                    <option value="06" <?PHP if($monthTo == "06") echo  "selected";    ?>>June</option>
		                    <option value="07" <?PHP if($monthTo == "07") echo  "selected";    ?>>July</option>
		                    <option value="08" <?PHP if($monthTo == "08") echo  "selected";    ?>>August</option>
		                    <option value="09" <?PHP if($monthTo == "09") echo  "selected";    ?>>September</option>
		                    <option value="10" <?PHP if($monthTo == "10") echo  "selected";    ?>>October</option>
		                    <option value="11" <?PHP if($monthTo == "11") echo  "selected";    ?>>November</option>
		                    <option value="12" <?PHP if($monthTo == "12") echo  "selected";    ?>>December</option>
						</select>
						&nbsp;
						<select name="cboYearTo" id="cboYear" style="width:60px" class="txtfield">
							<option value="1900">[ Year ]</option>
				        		<?php
				        			$st = date ("Y") ;
									for($x = $st; $x >=1970; $x--)
									{
										$newyear = $startyear + $x;
                                                                                
										if($yearTo == $newyear) $sel = "selected";
                                                                                else $sel = "";
                                                                                
										echo "<option value='$newyear' $sel>$newyear</option>";
									}
								?>
						</select>
					</td>
					<td height="20" align="left" class="padl5">&nbsp;</td>
				</tr>
				<tr>
					<td align="right" height="20" class="padl5">Monthly Sales </td>
					<td align="left" height="20" class="padl5">:</td>
					<td align="left" height="20" class="padl5"><input name="txtSalesFrom" type="text" class="txtfield" id="txtSalesFrom" size="20" value="<?php if(isset($_POST['txtSalesFrom'])) { echo $_POST['txtSalesFrom'] ;} ?>"></td>
					<td height="20" align="left" class="padl5">&nbsp;</td>
					<td height="20" align="right" class="padl5">To:</td>
					<td align="left" height="20" class="padl5"><input name="txtSalesTo" type="text" class="txtfield" id="txtSalesTo" size="20" value="<?php if(isset($_POST['txtSalesTo'])) { echo $_POST['txtSalesTo'] ;} ?>"></td>
					<td height="20" align="left" class="padl5">&nbsp;</td>
				</tr>
				<tr>
					<td align="right" height="20" class="padl5">Monthly BCR </td>
					<td align="left" height="20" class="padl5">:</td>
					<td align="left" height="20" class="padl5"><input name="txtBCRFrom" type="text" class="txtfield" id="txtBCRFrom" size="20"></td>
					<td height="20" align="left" class="padl5">&nbsp;</td>
					<td height="20" align="right" class="padl5">To:</td>
					<td align="left" height="20" class="padl5"><input name="txtBCRTo" type="text" class="txtfield" id="txtBCRTo" size="20"></td>
					<td height="20" align="left" class="padl5">&nbsp;</td>
	
				</tr>
				<tr>
					<td align="right" height="20"  class="padl5">Monthly Recruits</td>
					<td align="left" height="20" class="padl5">:</td>
					<td align="left" height="20" class="padl5"><input name="txtRAFrom" type="text" class="txtfield" id="txtRAFrom" size="20"></td>
					<td height="20" align="left" class="padl5">&nbsp;</td>
					<td height="20" align="right" class="padl5">To:</td>
					<td align="left" height="20" class="padl5"><input name="txtRATo" type="text" class="txtfield" id="txtRATo" size="20"></td>
					<td height="20" align="left" class="padl5">&nbsp;</td>					
				</tr>
				<tr>
					<td colspan="7" height="10">&nbsp;</td>
				</tr>
				</table>
				<br>
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td align="center"><input name="btnGenerate" type="submit" class="btn" value="Generate List"></td>
				</tr>
				</table>
 			</td>
 		</tr>
		</table>
		<br>
		<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
			<td valign="top">
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
	           				<td class="bgF9F8F7"><?php echo $errMessage; ?></td>
	         			</tr>
              			<tr>
                			<td>
                				<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="tab txtdarkgreenbold10">
                    			<tr align="center">   
				                    <?php 
				                    	if ($cnt==1)
				                    	{
				                    		$tmpDisabled="";
				                    	}
				                    	else
				                    	{
				                    		 $tmpDisabled = 'disabled="disabled"';
				                    	}
				                    ?>
                 					<td height="20" width="5%"><div align="center" class="bdiv_r"><input name="chkAll" type="checkbox" id="chkAll" value="1"  <?php echo $tmpDisabled ?> onclick="checkAll(this.checked);" /></div></td>	
                      				<td height="20" width="30%"><div align="left" class="bdiv_r padl5">IGS Code</div></td>
                      				<td height="20" width="30%"><div align="left" class="bdiv_r padl5">IGS Name</div></td>
                      				<td height="20" width="35%"><div align="left" class="padl5">IBM Code-Name</div></td>
              					</tr>
            					</table>
    						</td>
          				</tr>
              			<tr>
                			<td class="bordergreen_B"><div class="scroll_300">
                   				<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
									<?php echo $customerRows; ?>
                  				</table>
                          	</div></td>
          				</tr>
          				</table>
      				</td>
    			</tr>
      			</table>
      			<br>
      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  			<tr>
	  				<td align="left" class="padl5">
	  					<table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2" class="bordersolo">
	  					<tr>
	  						<td height="10">&nbsp;</td>
	  					</tr>
			  			<tr>
			  				<td width="30%" align="center">
			  					<strong>To Dealer Type :</strong> 
			  					&nbsp;&nbsp;&nbsp;
		    					<select name="cboDealerType" class="txtfield" style="width:250px">
									<?php echo $drpDealerType; ?>			
                				</select>
            				</td>
            			</tr>
            			<tr>
	  						<td height="10">&nbsp;</td>
	  					</tr>
            			</table>
    				</td>
  				</tr>
				</table>
				<br>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  			<tr>
	    			<td align="center">
	    				<input name="btnPromote" type="submit" class="btn" value="Promote" onclick="return ConfirmPromote();">
	    				<input name="btnCancel" type="submit" class="btn" value="Cancel">
	    			</td>
  				</tr>
				</table>
				<br>
			</td>
		</tr>
		</table>
		</form>
	</td>
</tr>
</table>