<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script type="text/javascript" src="js/jxModuleControl.js"></script>
<?php 
		include IN_PATH.DS."scModuleControl.php";
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
	  obj = document.frmModuleControl.elements;
	  
	  if (obj["cboModule"].selectedIndex == 0) msg += '   * Module and SubModule \n';
	  if (trim(obj["txtfldCode"].value) == '') msg += '   * Control Code \n';
	  if (trim(obj["txtfldName"].value) == '') msg += '   * Control Name \n';
	  if (trim(obj["txtfldDesc"].value) == '') msg += '   * Description \n';				
	    
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
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Access Rights </span></td>
        </tr>
    </table>
    <br />
  <form name="frmModuleControl" method="post" action="includes/pcModuleControl.php"> 
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">   
    Module Control</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="33%" valign="top">
    		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		  <tr>
		    <td><table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
		        <tr>
		          <td height="20" colspan="3">&nbsp;</td>
		        </tr>
		        <tr>
		          <td width="30%" height="15" align="right" valign="middle"><strong>MODULE : </strong></td>
		          <td width="4%" height="15">&nbsp;</td>
	              <td width="65%" height="15" valign="middle">
				  	<select name="cboModule" onchange="showUser(this.value);" style="width:160px" class="txtfield">
	                	<option value="0" selected>[SELECT HERE]</option>
                        <?php 
								if ($rs_module->num_rows){
									
									while ($row = $rs_module->fetch_object()){
										($modid == $row->ID)? $sel = "selected" : $sel ="";
										echo "<option value='$row->ID' $sel>$row->Name</option>";
									}
										
								}
						?>
	                </select>
				 </td>
		        </tr>
		        <tr>
		          <td width="30%" height="15" align="right" valign="middle"><strong>SUBMODULE : </strong></td>
		          <td width="4%" height="15">&nbsp;</td>
	              <td width="65%" height="15" valign="middle"><div id="txtHint">
						<?php 
                          echo "<select name=\"cboSubModule\" style=\"width:160px\" class=\"txtfield\" onchange=\"showModule(this.value);\">";
                          echo "<option value=\"0\" >[SELECT HERE]</option>";
                          
                          if ($rs_submod->num_rows){
                             while ($row = $rs_submod->fetch_object())  {
								($subid == $row->ID)? $sel = "selected" : $sel ="";
                                echo "<option value='$row->ID' $sel>$row->Name</option>";
                             }
                          }
                          
                          echo "</select>";
                          
                        ?>
	                </div>
				  </td>
		        </tr>
		        <tr>
		          <td height="20" colspan="3">&nbsp;</td>
		        </tr>
		    </table></td>
		  </tr>
		</table>
        <br />
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        <tr>
		          <td class="tabmin">&nbsp;</td>
		          <td class="tabmin2"><span class="txtredbold">Module Control Name</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		        <tr>
		          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		             <!-- <tr>
		                <td class="tab bordergreen_T"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
	                    <tr align="center">
	                      	<td width="60%"><div align="left"><span class="txtredbold">Module Control Name</span></div></td>	                      
	                	</tr>
						</table></td>
		              </tr>-->
		              <tr>
		                <td class="bordergreen_B"><div class="scroll_300" id="dvName">
                        			<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
							
								 <?php 
									  if ($rs_suball->num_rows){
										  $b = 0;
										 while ($row = $rs_suball->fetch_object())  {
											$b++;
											($b % 2) ? $class = "" : $class = "bgEFF0EB";
											
											echo "<tr align=\"center\" class=\"$class\">
														<td height=\"20\" align=\"left\" class=\"borderBR\">
															<a href='index.php?pageid=13&mcid=$row->ID&subid=$subid&modid=$row->ModuleID' class='txtnavgreenlink'>$row->Name
														</td>
												  </tr>";
										 }
									  }
									  
									  
									?>
							
		                  </table></div>
	                    </td>
		              </tr>
		          </table></td>
		        </tr>
		      </table>
	</td>
	<td width="2%">&nbsp;</td>
	<td width="60%" valign="top">
	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
     <tr>
       <td class="tabmin">&nbsp;</td>
       <td class="tabmin2"><span class="txtredbold">Module Control Information</span>&nbsp;</td>
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
       <td class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		            <tr>
		              <td width="27%" height="20" align="right">Module : </td>
		              <td width="3%">&nbsp;</td>
		              <td width="70%">
		              <div id ="txtHint">
		              <strong><?PHP echo $modname;?></strong>
		              </div>
		              </td>
		            </tr>
		            <tr>
		              <td width="27%" height="23" align="right">Submodule :</td>
		              <td width="3%" height="23">&nbsp;</td>
		              <td width="70%" height="23"><strong><?PHP echo $subname;?></strong></td>
		            </tr>
		            <tr>
		              <td width="27%" height="23" align="right">Control Code : </td>
		              <td width="3%" height="23">&nbsp;</td>
		              <td width="70%" height="23">
                      	<input type="text" name="txtfldCode" maxlength="40" size="40" class="txtfield" value="<?PHP echo $code;?>">
                        <?php 
							if ($mcid > 0)
							{
								echo "<input type=\"hidden\" name=\"hdnModControlID\" value=\"$mcid\" />";
							}
							if ($modid != 0)
							{
								echo "<input type=\"hidden\" name=\"hdnModuleID\" value=\"$modid\" />";
							}
							if ($subid != 0)
							{
								echo "<input type=\"hidden\" name=\"hdnSubModuleID\" value=\"$subid\" />";
							}							
						?>
                      </td>
		            </tr>
		            <tr>
		              <td width="27%" height="23" align="right">Control Name :</td>
		              <td width="3%" height="23">&nbsp;</td>
		              <td width="70%" height="23"><input type="text" name="txtfldName" maxlength="40" size="40" class="txtfield" value="<?PHP echo $name;?>"></td>
		            </tr>
		            <tr>
		              <td width="27%" height="23" align="right">Description :</td>
		              <td width="3%" height="23">&nbsp;</td>
		              <td width="70%" height="23"><input type="text" name="txtfldDesc" maxlength="40" size="40" class="txtfield" value="<?PHP echo $desc;?>"></td>
		            </tr>
					<tr>
		              <td width="27%" height="23" align="right">Page ID :</td>
		              <td width="3%" height="23">&nbsp;</td>
		              <td width="70%" height="23"><input type="text" name="txtfldPageNo" maxlength="40" size="40" class="txtfield" disabled="yes" value="<?PHP echo $pageno;?>"></td>
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
				if ($mcid > 0) 
				{ ?>					
                    <input name="btnUpdate" type="submit" class="btn" value="Update" onclick="return confirmSave();"/>
                    <input name="btnDelete" type="submit" class="btn" value="Delete" onclick="return confirmDelete();"/>					
				<?php }
				else 
				{ ?>
					<input name="btnSave" type="submit" class="btn" value="Save" onclick="return confirmSave();"> 
				<?php }				
			?>	
            <input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=13'">
        </td>
	  </tr>
	</table>
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