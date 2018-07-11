<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<?PHP 
	include IN_PATH.DS."scArea.php";
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
	obj = document.frmArea.elements;
	

	// TEXT BOXES
	if (trim(obj["txtfldCode"].value) == '') msg += '   * Code \n';
	if (trim(obj["txtfldName"].value) == '')msg += '   * Last Name \n';

	if (obj["cboAreaLevel"].selectedIndex == 0) msg += '   * AreaLevel \n';
	// if (obj["cboWarehouse"].selectedIndex == 0) msg += '   * Warehouse \n';
	if (obj["cboParent"].selectedIndex == 0) msg += '   * Parent \n';
	if (msg != '')
	{ 
	  alert ('Please complete the following: \n\n' + msg);
	  return false;
	}
	else {	


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
			include("nav.php");
		?>
	<br></td>
	<td class="divider">&nbsp;</td>
	<td valign="top" style="min-height: 610px; display: block;">
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
					<td class="txtgreenbold13">Area</td>
					<td>&nbsp;</td>
  				</tr>
				</table>
				<br />
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="33%" valign="top">
					<form name="frmSearchArea" method="post" action="index.php?pageid=77">
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
					  			<td width="50%"></td>
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
				  		<td class="tabmin2"><span class="txtredbold">Area List </span></td>
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
		                		<td class="bordergreen_B"><div class="scroll_300">
		                			<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
									<?PHP	
									if ($rs_area2->num_rows)
									{
										$rowalt = 0;
										while ($row = $rs_area2->fetch_object())
										{
											 $rowalt += 1;
							 				($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
			                              echo  "<tr align='center' class='$class'>
											  <td height='20' class='borderBR' width='40%' align='left'>&nbsp;<span class='txt10'>$row->Code</span></td>
											  <td class='borderBR' width='60%' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=77&aid=$row->ID&svalue=$search' class='txtnavgreenlink'>$row->Name</a></td>
									  		</tr>
											";
										}
										$rs_area2->close();
									}
									else 
									{
									echo "<tr align='center'>
											  <td height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td>
										    </tr>";
										 
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
					<td width="60%" valign="top">
	 <form name="frmArea" method="post" action="includes/pcArea.php" > 
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Area Details</span>&nbsp;</td>
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
           <td class="bgF9F8F7">&nbsp;</td>
         </tr>
        <tr>
           <td class="bgF9F8F7">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td height="30" width="25%" align="right" class="txt10">Code :</td>
                <td height="30" width="5%">&nbsp;</td>
                <td height="30" width="70%"><input type="text" name="txtfldCode" maxlength="15" size="40" class="txtfield" value="<?PHP echo $code;?>" onkeyup="javascript:RemoveInvalidChars(txtfldCode);" />                  <?php 
                        if ($aid > 0){
                          echo "<input type=\"hidden\" name=\"hdnAreaID\" value=\"$aid\" />";
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td height="30" width="25%" align="right" class="txt10">Name :</td>
                <td height="30" width="5%">&nbsp;</td>
                <td height="30" width="70%"><input type="text" name="txtfldName" maxlength="50" size="40" class="txtfield" value="<?PHP echo $name; ?>" onkeyup="javascript:RemoveInvalidChars(txtfldName);"/></td>
            </tr>
            
              <tr>
              <td height="30" align="right" class="txt10">Parent :</td>
              <td height="30">&nbsp;</td>
              <td height="30">
                    <select name="cboParent" style="width:250px" class="txtfield">
                        <?PHP
						echo "<option value=\"0\" >[SELECT HERE]</option>";
                        if ($rs_parentarea->num_rows)
                        {
                            while ($row = $rs_parentarea->fetch_object())
                            {
                            ($prntaid == $row->ID) ? $sel = "selected" : $sel = "";
                            echo "<option value='$row->ID' $sel>$row->Name</option>";
                            }
                        }
                        ?>
                    </select>
              </td>
              </tr>
            
              <tr>
              <td height="30" align="right" class="txt10">Area Level :</td>
              <td height="30">&nbsp;</td>
              <td height="30">
                    <select name="cboAreaLevel" style="width:250px" class="txtfield">
                        <?PHP
						echo "<option value=\"0\" >[SELECT HERE]</option>";
                        if ($rs_CboAreaLevel->num_rows)
                        {
                            while ($row = $rs_CboAreaLevel->fetch_object())
                            {
                            ($arid == $row->ID) ? $sel = "selected" : $sel = "";
                            echo "<option value='$row->ID' $sel>$row->Name</option>";
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
					if ($aid > 0) 
					{ ?>					
	                    <input name="btnUpdate" type="submit" class="btn" value="Update" onclick="return form_validation(2);"/>
	                    <input name="btnDelete" type="submit" class="btn" value="Delete" onclick="return confirmDelete();"/>					
					<?php }
					else 
					{ ?>
						<input name="btnSave" type="submit" class="btn" value="Save" onclick="return form_validation(1);"> 
					<?php }
            	}				
			?>	
            <input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=77'">		
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




