<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script type="text/javascript" src="js/datamgt_validation.js"></script>

<?PHP 
		include IN_PATH.DS."scDepartment.php";		
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
		obj = document.frmDept.elements;
		
		// TEXT BOXES
		if (trim(obj["code"].value) == '') msg += '   * Code \n';
		if (trim(obj["name"].value) == '')msg +=  '   * Name \n';
		//if (trim(obj["description"].value) == '')msg += '   * Description \n';
		// if (obj["cboWarehouse"].selectedIndex == 0) msg += '   * Warehouse \n';
	
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
    <td valign="top" style="min-height: 610px; display: block;"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
    <td class="txtgreenbold13">Department </td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="33%" valign="top">
    	<form name="frmSearchDept" method="post" action="index.php?pageid=14">
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
		          <td class="tabmin2"><span class="txtredbold">List of Department</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		        <tr>
		          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		              <tr>
		                <td class="tab bordergreen_T"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
		                    <tr align="center">
		                      <td width="40%"><div align="center">&nbsp;<span class="txtredbold">Code</span></div></td>
		                      <td width="60%"><div align="center"><span class="txtredbold">Name</span></div></td>
		                </table></td>
		              </tr>
		              <tr>
		                <td class="bordergreen_B"><div class="scroll_300">
                        	<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                                    <tr align="center">
                                    	<?php
										
                                      		if($rs_department->num_rows)
											{												
												$rowalt=0;
												while($row = $rs_department->fetch_object())
												{
													$rowalt++;
													($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
													echo "<tr align='center' class='$class'>
								  					<td height='20' class='borderBR' width='40%' align='left'>&nbsp;<span class='txt10'>$row->Code</span></td>
								  					<td class='borderBR' width='60%' align='left'>&nbsp;<span class='txt10'>
													<a href='index.php?pageid=14&dID=$row->ID&searchedTxt=$dSearchTxt' class='txtnavgreenlink'>$row->Name</a></td></tr>";
												} 
												$rs_department->close();
											}
											else
											{
												echo "<tr align='center'><td height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
											}
											//$mysqli->close();
									  	?>
                                    </tr>
							
		                  </table></div>
	                    </td>
		              </tr>
		          </table></td>
		        </tr>
		      </table>
	</td>
	<td width="2%">&nbsp;</td>
    
    <form name="frmDept" method="post" action="includes/pcDepartment.php" onsubmit="return checkStr(frmDept);">
        <td width="60%" valign="top">
            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
             <tr>
               <td class="tabmin">&nbsp;</td>
               <td class="tabmin2"><span class="txtredbold">Department Details</span>&nbsp;</td>
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
                    <td height="30" width="25%" align="right" class="txt10">Code :</td>
                    <td height="30" width="5%">&nbsp;</td>
                    <td height="30" width="70%">
					<input name="code" type="text" <?php if($_GET['dID'] != ""): ?> readonly = "readonly" <?php endif; ?> class="txtfield" value="<?php echo $code; ?>" size="40" maxlength="15" onkeyup="javascript:RemoveInvalidChars(code);">                     
					<input name="ParentID" type="hidden" value = "<?php echo $ParentID; ?>">                     
						<?php
							if ($dID > 0)
							{
							  echo "<input type=\"hidden\" name=\"hdnDeptID\" value=\"$dID\" />";
							}
						?>
                    </td>
                </tr>
                <tr>
                    <td height="30" width="25%" align="right" class="txt10">Name :</td>
                    <td height="30" width="5%">&nbsp;</td>
                    <td height="30" width="70%"><input name="name" type="text" class="txtfield" value="<?php echo $name; ?>" size="40" maxlength="30" <?php if ($_SESSION['ismain'] != 1) { echo 'readonly = "yes"'; } ?>></td>
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
							if($dID > 0)
							{
								echo "<input name='btnUpdate' type='submit' class='btn' value='Update' onClick='return form_validation(2);' />";
								echo "\t\t\t";
								echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onClick = 'return form_validation_delete();'/>";
							}
							else
							{
								echo "<input name='btnSave' type='submit' class='btn' value='Save' onClick='return form_validation(1);'/>"; 	
							}						 
						}
					?>                        
                      <input name="btnCancel2" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=14'" />
                 </td>
              </tr>
            </table>
        </td>
    </form>
    
  </tr>
</table>
	</td>
   </tr>
 </table>
   
    </td>
  </tr>
</table>