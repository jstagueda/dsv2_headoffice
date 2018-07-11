<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<?PHP 
	include IN_PATH.DS."scWarehouse.php";
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
	  obj = document.frmWarehouse.elements;
	  	  
	  // TEXT BOXES
	  if (trim(obj["txtfldCode"].value) == '') msg += '   * Code \n';
	  if (trim(obj["txtfldName"].value) == '') msg += '   * Name \n';	
	    
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
			include("nav.php");
		?>
	<br></td>
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
					<td class="txtgreenbold13">Warehouse</td>
					<td>&nbsp;</td>
  				</tr>
				</table>
				<br />
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="33%" valign="top">
					<form name="frmSearchWarehouse" method="post" action="index.php?pageid=5">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
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
				  		<td class="tabmin2"><span class="txtredbold">Warehouse List </span></td>
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
		                      			<td width="40%"><div align="center">&nbsp;<span class="txtredbold">Code</span></div></td>
		                      			<td width="60%"><div align="center"><span class="txtredbold">Name</span></div></td>
	                      			</tr>
		                			</table>
	                			</td>
	              			</tr>
		              		<tr>
		                		<td class="bordergreen_B"><div class="scroll_300">
		                			<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
									<?PHP	
									if ($rs_warehouse2->num_rows)
									{
										$rowalt = 0;
										while ($row = $rs_warehouse2->fetch_object())
										{
											 $rowalt += 1;
							 				($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
			                              echo  "<tr align='center' class='$class'>
											  <td height='20' class='borderBR' width='40%' align='left'>&nbsp;<span class='txt10'>$row->Code</span></td>
											  <td class='borderBR' width='60%' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=5&wid=$row->ID&svalue=$search' class='txtnavgreenlink'>$row->Name</a></td>
									  		</tr>
											";
										}
										$rs_warehouse2->close();
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
	 <form name="frmWarehouse" method="post" action="includes/pcWarehouse.php" > 
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Warehouse Details</span>&nbsp;</td>
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
                        if ($wid > 0){
                          echo "<input type=\"hidden\" name=\"hdnWarehouseID\" value=\"$wid\" />";
                        }
                    ?>
                </td>
            </tr>
            <tr>
                <td height="30" width="25%" align="right" class="txt10">Name :</td>
                <td height="30" width="5%">&nbsp;</td>
                <td height="30" width="70%"><input type="text" name="txtfldName" maxlength="50" size="40" class="txtfield" value="<?PHP echo $name; ?>" onkeyup="javascript:RemoveInvalidChars(txtfldName);"/></td>
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
				if ($wid > 0) 
				{ ?>					
                    <input name="btnUpdate" type="submit" class="btn" value="Update" onclick="return confirmSave();"/>
                    <input name="btnDelete" type="submit" class="btn" value="Delete" onclick="return confirmDelete();"/>					
				<?php }
				else 
				{ ?>
					<input name="btnSave" type="submit" class="btn" value="Save" onclick="return confirmSave();"> 
				<?php }				
			?>	
            <input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=5'">		
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




