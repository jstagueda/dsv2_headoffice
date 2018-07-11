<?PHP
	include IN_PATH.DS."scEmployee.php";
?>

<script type="text/javascript">

$(function(){
    $('[name=cboBranchList]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searched   :   request.term},
                url     :   "includes/scEmployee.php",
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Label,
                            value   :   item.Value,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=cboBranch]').val(ui.item.ID);
        }
    });

    $('[name=txtBdayEmployee]').datepicker({
        changeMonth :   true,
        changeYear  :   true
    });

    $('[name=txtDateHired]').datepicker({
        changeMonth :   true,
        changeYear  :   true
    });
});

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
	obj = document.frmEmployee.elements;

	var str1 = document.getElementById("txtBdayEmployee").value;
    var str2 = document.getElementById("txtDateHired").value;
    var dt1  = parseInt(str1.substring(0,2),10);
    var mon1 = parseInt(str1.substring(3,5),10);
    var yr1  = parseInt(str1.substring(6,10),10);
    var dt2  = parseInt(str2.substring(0,2),10);
    var mon2 = parseInt(str2.substring(3,5),10);
    var yr2  = parseInt(str2.substring(6,10),10);
    var date1 = new Date(yr1, mon1, dt1);
    var date2 = new Date(yr2, mon2, dt2);


	// TEXT BOXES
	if (trim(obj["txtCodeEmployee"].value) == '') msg += '   * Code \n';
	if (trim(obj["txtlnameEmployee"].value) == '')msg += '   * Last Name \n';
	if (trim(obj["txtfnameEmployee"].value) == '')msg += '   * First Name \n';
	if (trim(obj["txtmnameEmployee"].value) == '')msg += '   * Middle Name \n';
	if (trim(obj["txtBdayEmployee"].value) == '')msg += '   * Birthday \n';
	if (trim(obj["txtDateHired"].value) == '')msg += '   * Date Hired \n';
	//if (obj["cboBranch"].selectedIndex == 0) msg += '   * Branch \n';
	//if (obj["cboEmployeeType"].selectedIndex == 0) msg += '   * Employee Type \n';
	//if (obj["cboDepartment"].selectedIndex == 0) msg += '   * Department \n';
	//if (obj["cboPosition"].selectedIndex == 0) msg += '   * Position \n';
	// if (obj["cboWarehouse"].selectedIndex == 0) msg += '   * Warehouse \n';

	if (msg != '')
	{
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	}
	else
	{
		if(date2 < date1)
       	{
     		alert("Birthdate cannot be greater than date hired.");
         	return false;
   		}

		if(x==1)
		{
			if (confirm('Are you sure you want to save this transaction?') == false)
		  	{
		  		return false;
		  	}
		  	else
		  	{
		  		return true;
		  	}
		}
		else if (x == 2)
		{
     		if (confirm('Are you sure you want to update this transaction?') == false)
	 		{
   				return false;
		 	}
	 		else
	 		{
	    		return true;
	 		}
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

function CompareDates()
{
	if(date2 < date1)
    {
        alert("Birthdate cannot be greater than date hired.");
        return false;
    }
    else
    {
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
	</td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 610px; display: block;">
    	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
    				<td class="txtgreenbold13">Employee</td>
    				<td>&nbsp;</td>
  				</tr>
				</table>
				<br />
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="33%" valign="top">
    					<form name="frmSearchEmp" method="post" action="index.php?pageid=12">
                                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin">&nbsp;</td>
								<td class="tabmin2"><span class="txtredbold">Action</span></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
						</table>
						<table width="100%" style="border-top:none;" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
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
						  				<input name="txtfldsearch" type="text" class="txtfield" id="txtSearch" size="20">
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
		          			<td class="tabmin2"><span class="txtredbold">List of Employee</span></td>
		          			<td class="tabmin3">&nbsp;</td>
		        		</tr>
		      			</table>
		      			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
		        		<tr>
		          			<td valign="top" class="bgF9F8F7">
		          				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		              			<tr>
		                			<td class="tab bordergreen_T">
		                				<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
		                    			<tr align="center">
		                      				<td width="40%"><div align="center">&nbsp;<span class="txtredbold">Code</span></div></td>
		                      				<td width="60%"><div align="center"><span class="txtredbold">Name</span></div></td>
		                      			</tr>
		                				</table>
	                				</td>
	              				</tr>
		              			<tr>
		                			<td class="bordergreen_B">
                                                            <div class="scroll_300">
                                                                <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
										<?php
										if ($rs_employeeall->num_rows)
										{
											while ($row = $rs_employeeall->fetch_object())
											{
							   					echo "<tr align='center'>
								  						<td width='40%' height='20' class='borderBR' align='left'>&nbsp;<span class='txt10'>$row->Code</span></td>
								  						<td width='60%' class='borderBR' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=12&empid=$row->ID&svalue=$search' class='txtnavgreenlink'>".$row->FirstName." ".$row->MiddleName." ".$row->LastName." </a></span></td>
													</tr>";
											}
											$rs_employeeall->close();
										}
										else
										{
											echo "<tr align='center'>
								  						<td height='20' colspan='2' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td>
							    				</tr>";
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
					<td width="2%">&nbsp;</td>
					<td width="60%" valign="top">
     					<form name="frmEmployee" method="post" action="includes/pcEmployee.php" >
        				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         				<tr>
           					<td class="tabmin">&nbsp;</td>
           					<td class="tabmin2"><span class="txtredbold">Employee Details</span>&nbsp;</td>
           					<td class="tabmin3">&nbsp;</td>
         				</tr>
       					</table>
        				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
         				<tr>
           					<td class="bgF9F8F7">
           						<?php
           						if (isset($_GET['msg']))
           						{
                            		$message = strtolower($_GET['msg']);
                            		$success = strpos("$message","success");
                            		echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
                        		}
                        		else if (isset($_GET['errmsg']))
                        		{
									$errormessage = strtolower($_GET['errmsg']);
									$error = strpos("$errormessage","error");
									echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";
                        		}
                         		?>
                     		</td>
     					</tr>
         				<tr>
           					<td class="bgF9F8F7">&nbsp;</td>
         				</tr>
        				<tr>
           					<td class="bgF9F8F7">
            					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            					<tr>
                					<td width="27%" height="30" align="right" class="txt10">Code :</td>
                					<td height="30" width="3%">&nbsp;</td>
                					<td height="30" width="70%"><input type="text" name="txtCodeEmployee" maxlength="15" size="30" class="txtfield" value="<?PHP echo $code;?>" onkeyup="javascript:RemoveInvalidChars(txtCodeEmployee);" />
                						<?php
                        				if ($empid > 0)
                        				{
                          					echo "<input type=\"hidden\" name=\"hdnEmployeeID\" value=\"$empid\" />";
                        				}
                    					?>
             						</td>
        						</tr>
            					<tr>
                					<td height="30" align="right" class="txt10">Last Name :</td>
                					<td height="30">&nbsp;</td>
                					<td height="30"><input type="text" name="txtlnameEmployee" maxlength="50" size="40" class="txtfield" value="<?PHP echo $lname;?>" onkeyup="javascript:RemoveInvalidChars(txtlnameEmployee);"></td>
        						</tr>
            					<tr>
                					<td height="30" align="right" class="txt10">First Name :</td>
                					<td height="30">&nbsp;</td>
                					<td height="30"><input type="text" name="txtfnameEmployee" maxlength="50" size="40" class="txtfield" value="<?PHP echo $fname;?>" onkeyup="javascript:RemoveInvalidChars(txtfnameEmployee);"/></td>
            					</tr>
            					<tr>
                					<td height="30" align="right" class="txt10">Middle Name :</td>
                					<td height="30">&nbsp;</td>
                					<td height="30"><input type="text" name="txtmnameEmployee" maxlength="50" size="10" class="txtfield" value="<?PHP echo $mname;?>" onkeyup="javascript:RemoveInvalidChars(txtmnameEmployee);"></td>
            					</tr>
            					<tr>
              						<td height="30" align="right" class="txt10">Birthday :</td>
              						<td height="30">&nbsp;</td>
              						<td height="30">
                                                            <input name="txtBdayEmployee" type="text" class="txtfield" id="txtBdayEmployee" size="20" readonly="yes" value="<?PHP echo $bday;?>">
                                                            <i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
        						</tr>
			 					<tr>
              						<td height="30" align="right" class="txt10">Date Hired:</td>
              						<td height="30">&nbsp;</td>
              						<td height="30">
              							<input name="txtDateHired" type="text" class="txtfield" id="txtDateHired" size="20" readonly="yes" value="<?PHP echo $dhired;?>">
                						<i>(e.g. MM/DD/YYYY)</i>
              						</td>
        						</tr>
        						<tr>
                                                            <td height="30" align="right" class="txt10">Branch :</td>
                                                            <td height="30">&nbsp;</td>
                                                            <td height="30">
                                                                <?php
                                                                    $branch = $database->execute("SELECT * FROM branch
                                                                        WHERE ID = $brid");
                                                                    $b = $branch->fetch_object();
                                                                ?>
                                                                <input name="cboBranchList" value="<?=($branch->num_rows)?$b->Name:"";?>" style="width:250px; visibility:visible;" class="txtfield">
                                                                <input name="cboBranch" value="<?=$brid?>" type="hidden">
                                                            </td>
          						</tr>
            					<tr>
              						<td height="30" align="right" class="txt10">Employee Type :</td>
              						<td height="30">&nbsp;</td>
              						<td height="30">
                    					<select name="cboEmployeeType" style="width:250px;  visibility:visible;" class="txtfield">
                        				<?php
											echo "<option value=\"0\" >[SELECT HERE]</option>";
                        					if ($rs_cboEmployeeType->num_rows)
                        					{
                            					while ($row = $rs_cboEmployeeType->fetch_object())
                            					{
                            						($etid == $row->ID) ? $sel = "selected" : $sel = "";
                            						echo "<option value='$row->ID' $sel>$row->Name</option>";
                            					}
                        					}
                    					?>
                    					</select>
          							</td>
          						</tr>
            					<tr>
                					<td height="30" align="right" class="txt10">Department  :</td>
                					<td height="30">&nbsp;</td>
                					<td height="30">
                            			<select name="cboDepartment" style="width:250px;  visibility:visible;" class="txtfield">
                            			<?php
											echo "<option value=\"0\" >[SELECT HERE]</option>";
                            				if ($rs_cboDepartment->num_rows)
                            				{
                                				while ($row = $rs_cboDepartment->fetch_object())
                                				{
                                					($deptid == $row->ID) ? $sel = "selected" : $sel = "";
                                					echo "<option value='$row->ID' $sel>$row->Name</option>";
                                				}
                            				}
                         				?>
                            			</select>
            						</td>
        						</tr>
             					<tr>
                					<td height="30" align="right" class="txt10">Position  :</td>
                					<td height="30">&nbsp;</td>
                					<td height="30">
                            			<select name="cboPosition" style="width:250px;  visibility:visible;" class="txtfield">
                            			<?php
											echo "<option value=\"0\" >[SELECT HERE]</option>";
                            				if ($rs_cboPosition->num_rows)
                            				{
                                				while ($row = $rs_cboPosition->fetch_object())
                                				{
                                					($postid == $row->id) ? $sel = "selected" : $sel = "";
                                					echo "<option value='$row->id' $sel>$row->name</option>";
                                				}
                            				}
                         				?>
                            			</select>
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
            				<?php
            					if ($_SESSION['ismain'] == 1)
            					{
		            				if ($empid > 0)
		            				{
		            					echo "<input name='btnUpdate' type='submit' class='btn' value='Update' onClick = 'return form_validation(2);' />";
		            					echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onClick = 'return form_validation_delete();' />";
		            				}
		            				else
		            				{
		            					echo "<input name='btnSave' type='submit' class='btn' value='Save'onClick = 'return form_validation(1);' >";
		            				}
            					}
        					?>
            				<input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=12'" />
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