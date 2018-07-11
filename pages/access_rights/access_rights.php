<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script type="text/javascript" src="js/jxAccessRight.js"></script>

<?PHP 
	include IN_PATH.DS."scAccessRights.php";
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
	  msg = '';
	  obj = document.frmAccessRights.elements;
	
	  if (obj["cboUserType"].selectedIndex == 0)
	  { 
		alert ('Please select User Type.');
		return false;
	  }
	  
	  else return true;
  }
 
  function checkAll(bin) 
  {
	  var elms=document.frmAccessRights.elements;
  
	  for(var i=0;i<elms.length;i++)
	  if(elms[i].name=='chkID[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
  }
	
  function checkBoxes()
  {
	 count = 0;
	 str = '';
	 for(x=0; x<document.frmBuildup.elements["chkID[]"].length; x++)
	 {
	  	if(document.frmBuildup.elements["chkID[]"][x].checked==true)
		{
		  str += document.frmBuildup.elements["chkID[]"][x].value + ',';
		  count++;
	  	}
	 }
	
	 if(count==0)
	 {
	  	alert("You didn\'t choose any checkboxes");
	  	return false;
	 }
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
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Access Rights</span></td>
        </tr>
    </table>
    <br />
   <form name="frmAccessRights" method="post" action="includes/pcAccessRights.php" onsubmit="return form_validation();">
   <table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td width="25%">
            <table width="40%"  border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin">&nbsp;</td>
								<td class="tabmin2"><span class="txtredbold">Action</span></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
						</table>
            <table width="40%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
          <tr>
		    <td><table width="99%"  border="0" align="left" cellpadding="0" cellspacing="1">	
		        <tr class="border_b01">			
                    <td width="7%" height="20">&nbsp;</td>
                    <td width="35%" height="20"><strong>SELECT USER TYPE : </strong></td>
                    <td width="58%" height="20">
						<select name="cboUserType" style="width:160px" class="txtfield" onchange="showDetails(this.value);">
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
		        <!--- <tr>
		          <td>&nbsp;</td>
		          <td align="right">&nbsp;</td>
		          <td align="right">&nbsp;</td>
		        </tr> --->
		    </table></td>		
		  </tr>
		</table>
	</td>
  </tr>
   <tr>
       <td>
       <br /><div id="showThis">
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
            </div>
         </td>
     </tr>
  <tr>
	<td>
	      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
	        <tr>
	          <td class="tabmin">&nbsp;</td>
	          <td class="tabmin2"><span class="txtredbold">Available Controls</span>&nbsp;</td>
	          <td class="tabmin2" height="20">&nbsp;</td>
              <td class="tabmin2" height="20">&nbsp;</td>
              <td class="tabmin3" width="5" height="20">&nbsp;</td>
	        </tr>
	      </table>
	      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen">
	        <tr>
	          <td valign="top" class="bgF9F8F7">
                  <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                      <tr>
                        <td class="tab bordergreen_T">
                            <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                            <tr >
                                <td width="6%" height="15" align="center"><input name="chkAll" type="checkbox" id="chkAll" value="1" onclick="checkAll(this.checked);" /></td>
                                <td width="14%" height="15" align="center"><strong>MODULE</strong></td>
                                <td width="17%"align="center"><strong>SUBMODULE</strong></td>
                                <td width="19%" height="15" align="center"><strong>MODULE CONTROL</strong></td>
                                <td width="18%" height="15" align="center"><strong>DESCRIPTION</strong></td>      
                            </tr>
                            </table>
                        </td>
                      </tr>
                      <tr>
                        <td class="bordergreen_B">
                            <div class="scroll_300" id="dvAccessRight">&nbsp;</div>
                        </td>
                      </tr>
                  </table>
              </td>
	        </tr>
	      </table>
	      <br>
	      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td height="20" align="center">
			    	   	<input name='btnSave' type='submit' class='btn' value='Save'/>
						<input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=16'">
            </tr>
		  </table>
	</td>	
  </tr>
</table>
<br>
   </form>
    </td>
  </tr>
</table>




