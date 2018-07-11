

<link rel="stylesheet" type="text/css" href="../../css/ems.css"/>

<?PHP 
	include IN_PATH.DS."scVwUser.php";
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
	  msgEmp = '';
	  msg = '';
	  obj = document.frmVwUser.elements;
	  	  
	  // TEXT BOXES
	 // if (obj["hdnEmployeeID"].value == 0) msg += '   * Employee \n';

	  EmpID = obj["hdnEmployeeID"].value;
	  if (EmpID == 0)
	  {
		 
		  msg+= '   * Select an employee. \n';
		  
	  }
	  
	  if (trim(obj["txtUserName"].value) == '') msg += '   * User Name \n';
	  if (obj["cboUserType"].selectedIndex == 0) msg += '   * User Type \n';
	  if (trim(obj["txtLoginName"].value) == '') msg += '   * Login Name \n';
	  if (trim(obj["txtPassword"].value) == '') msg += '   * New Password \n';	
	  if (trim(obj["txtConPassword"].value) == '') msg += '   * Confirm New Password \n';
	  
	  Pword = trim(obj["txtPassword"].value);
	  CPword = trim(obj["txtConPassword"].value);

	  
	  if (Pword != CPword)
	  {
		  msg += '   * Passwords does not match. \n';
	  }

  
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
  
  function confirmDelete()
  {
	  if (confirm('Are you sure you want to delete this transaction?') == false)
		  return false;
	  else
		  return true;
  }
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6">
    	<?PHP
			include("nav_access.php");
		?>

      <br></td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 610px; display: block;"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Access Rights </span></td>
        </tr>
    </table>
    <br />
   
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">User</td>
    <td>&nbsp;</td>
  </tr>
</table>

<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="45%" valign="top">
    <form name="frmSearchUser" method="post" action="index.php?pageid=15">
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td class="tabmin">&nbsp;</td>
                <td class="tabmin2"><span class="txtredbold">Action</span>&nbsp;</td>
                <td class="tabmin3">&nbsp;</td>
            </tr>
        </table>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
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
						  <input name="txtfldSearch" type="text" class="txtfield" id="txtSearch" size="20" value="<?php echo $search; ?>">
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
		          <td class="tabmin2"><span class="txtredbold">List of Employees</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		        <tr>
		          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		              <tr>
		                <td class="tab bordergreen_T"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
		                    <tr >
		                      <td width="20%"><div align="center"><span class="txtredbold">Employee Code</span></div></td>
		                      <td width="40%"><div align="center"><span class="txtredbold">Employee Name</span></div></td>
		                      <td width="20%"><div align="center"><span class="txtredbold">User Name</span></div></td>
		                      <td width="20%"><div align="center"><span class="txtredbold">Login Name</span></div></td>
		                      </table></td>
		              </tr>
		              <tr>
		                <td class="bordergreen_B"><div class="scroll_300"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
							<?PHP	
                            if ($rs_userall->num_rows)
                            {
                                $rowalt = 0;
                                while ($row = $rs_userall->fetch_object())
                                {
                                    $rowalt += 1;
                                    ($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
                                    echo "<tr align='center' class='$class'>
                                        <td height='20' class='borderBR' width='20%' align='left'>&nbsp;<span class='txt10'>&nbsp;$row->EmployeeCode</span></td>
                                        <td class='borderBR' width='40%' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=15&empid=$row->ID&uid=$row->UserID&svalue=$search' class='txtnavgreenlink'>$row->EmployeeName</a></span></td>
										<td class='borderBR' width='20%' align='left'>&nbsp;<span class='txt10'>$row->UserName</span></td>
										<td class='borderBR' width='20%' align='left'>&nbsp;<span class='txt10'>$row->LoginName</span></td>
										</tr>";
                                }
                                $rs_userall->close();
                            }
                            else 
                            {
                                echo "<tr align='center'>
                                      <td height='20' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</b></span></td>
                                    </tr>";                                     
                            }
                            /*$mysqli->close();*/
                        ?>		
							
		                  </table></div>
	                    </td>
		              </tr>
		          </table></td>
		        </tr>
		      </table>
	</td>
	<td width="2%">&nbsp;</td>
	<td width="53%" valign="top">
   
	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
     <tr>
       <td class="tabmin">&nbsp;</td>
       <td class="tabmin2"><span class="txtredbold">User Information</span>&nbsp;</td>
       <td class="tabmin3">&nbsp;</td>
     </tr>
   </table>
    
 	
 <form name="frmVwUser" method="post" action="includes/pcVwUser.php"> <!--onsubmit="return form_validation();"-->
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
			else if(isset($_GET['errmsg']))
			{
				$errormessage = strtolower($_GET['errmsg']);
				$error = strpos("$errormessage","error"); 
					echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";
			}
			?>
			</td>
		</tr>
	<tr>
       <td class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		            <tr>
		              <td height="23" align="right">Employee Code : </td>
		              <td height="23">&nbsp;</td>
		              <td height="23"><strong><?PHP echo $employeecodeD;?></strong>
						<input type="hidden" name="hdnEmployeeID" value="<?PHP echo $empid; ?>" />
                        <input type="hidden" name="hdnUserID" value="<?PHP echo $uid ?>" />                           
                      </td>
		            </tr>
		            <tr>
		              <td height="23" align="right">Employee : </td>
		              <td height="23">&nbsp;</td>
		              <td height="23"><strong><?PHP echo $employeenameD;?></strong></td>
		            </tr>
		            <tr>
		              <td width="28%" height="23" align="right">User Name :</td>
		              <td width="4%" height="23">&nbsp;</td>
		              <td width="61%" height="23">
                      	<input name="txtUserName" type="text" id="txtUserName" size="30" maxlength="30" class="txtfield" value="<?PHP echo $uname; ?>">
                      </td>
		            </tr>
		            <tr>
		              <td height="23" align="right">User Type :</td>
		              <td height="23">&nbsp;</td>
		              <td height="23">
                      	<select name="cboUserType" style="width:250px" class="txtfield">
							<?PHP							
  								echo "<option value=\"0\" >[SELECT HERE]</option>";
								if ($rs_cboUserType->num_rows)
								{
									while ($row = $rs_cboUserType->fetch_object())
									{
									($usertypeid == $row->ID) ? $sel = "selected" : $sel = "";
									echo "<option value='$row->ID' $sel>$row->Name</option>";
									}
								}
                            ?>
                        </select>
                      </td>
		            </tr>
		            <tr>
		              <td height="23" align="right">Login Name : </td>
		              <td height="23">&nbsp;</td>
		              <td height="23">
                      	<input name="txtLoginName" type="text" id="" size="25" maxlength="25" class="txtfield" value="<?PHP echo $loginU2;?>">
                      	
                      </td>
		            </tr>
		            <?php //if ($uid > 0)
		            //{?>
		            <!--
					<tr>
		              <td height="23" align="right">Old Password :</td>
		              <td height="23">&nbsp;</td>
		              <td height="23"><input name="txtOldPassword" type="password" id="" size="25" maxlength="25" class="txtfield" value=""></td>
		            </tr>
					-->
		            <?php //}?>            
		            
		            <tr>
		              <td height="23" align="right"><?php if ($uid > 0){ echo "New "; }?>Password :</td>
		              <td height="23">&nbsp;</td>
		              <td height="23"><input name="txtPassword" type="password" id="" size="25" maxlength="25" class="txtfield" value=""></td>
		            </tr>
		            <tr>
		              <td height="23" align="right">Confirm <?php if ($uid > 0){ echo "New "; }?>Password : </td>
		              <td height="23">&nbsp;</td>
		              <td height="23"><input name="txtConPassword" type="password" id="" size="25" maxlength="25" class="txtfield"></td>
		            </tr>
                    <tr>
		              <td height="23" align="right">&nbsp;</td>
		              <td height="23">&nbsp;</td>
		              <td height="23">&nbsp;</td>
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
				if ($uid > 0) 
				{ ?>					
                    <input name="btnUpdate" type="submit" class="btn" value="Update" onclick="return confirmSave();"/>
                    <input name="btnDelete" type="submit" class="btn" value="Delete" onclick="return confirmDelete();"/>					
				<?php }
				else 
				{ ?>
					<input name="btnSave" type="submit" class="btn" value="Save" onclick="return confirmSave();"> 
				<?php }				
			?>	
            <input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=15'">	
                
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
    </td>
  </tr>
</table>