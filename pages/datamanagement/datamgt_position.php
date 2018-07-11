<?php 
	include IN_PATH.DS."scPosition.php";
?>

<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
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
	obj = document.frmPosition.elements;
	
	// TEXT BOXES
	if (trim(obj["txtCodePosition"].value) == '') msg += '   * Code \n';
	if (trim(obj["txtPosition"].value) == '')msg += '   * Name \n';

	// if (obj["cboWarehouse"].selectedIndex == 0) msg += '   * Warehouse \n';

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
    <td class="txtgreenbold13">Position</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="33%" valign="top">
    <form name="frmSearchPos" method="post" action="index.php?pageid=153">
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        <tr>
		          <td class="tabmin">&nbsp;</td>
		          <td class="tabmin2"><span class="txtredbold">Action</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		<table width="100%" style="border-top:none;" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
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
						  <input name="txtfldsearch" type="text" class="txtfield" id="txtSearch" size="20">
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
		          <td class="tabmin2"><span class="txtredbold">List of Position</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
		        <tr>
		          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		              <tr>
		                <td class="tab bordergreen_T"><table width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
		                    <tr align="center">
		                      <td width="40%"><div align="center" class="bdiv_r">&nbsp;<span class="txtredbold">Code</span></div></td>
		                      <td width="60%"><div align="center"><span class="txtredbold">Name</span></div></td>
		                </table></td>
		              </tr>
		              <tr>
		                <td class="bordergreen_B"><div class="scroll_300">
                        <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
						<?PHP
						if ($rs_positionall->num_rows)
						{
							while ($row = $rs_positionall->fetch_object())
							{
							   echo "<tr align='center'>
								  <td width='40%' height='20' class='borderBR' align='left'>&nbsp;<span class='txt10'>$row->code</span></td>
								  <td width='60%' class='borderBR' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=153&posid=$row->id&svalue=$search' class='txtnavgreenlink'>".$row->name." </a></span></td>
								</tr>";
							}
							$rs_positionall->close();
						}
						else
						{
							echo "<tr align='center'>
								  <td height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td>
							    </tr>";
						}
						?>
		                  </table>
                          </div>
	                    </td>
		              </tr>
		          </table></td>
		        </tr>
		      </table>
	</td>
	<td width="2%">&nbsp;</td>
	<td width="60%" valign="top">
     <form name="frmPosition" method="post" action="includes/pcPosition.php" >
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
         <tr>
           <td class="tabmin">&nbsp;</td>
           <td class="tabmin2"><span class="txtredbold">Position Details</span>&nbsp;</td>
           <td class="tabmin3">&nbsp;</td>
         </tr>
       </table>
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
         <tr>
           <td class="bgF9F8F7"><?php 
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
                         ?></td>
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
                <td height="30" width="70%"><input type="text" name="txtCodePosition" <?php if($_GET['posid'] != ""): ?>readonly = "readonly"<?php endif; ?>maxlength="15" size="30" class="txtfield" value="<?PHP echo $code;?>" onkeyup="javascript:RemoveInvalidChars(txtCodePosition);" />                 
                 <?php 
                        if ($posid > 0){
                          echo "<input type=\"hidden\" name=\"hdnPosID\" value=\"$posid\" />";
                        }
                    ?>
                 </td>
            </tr>
            <tr>
                <td height="30" align="right" class="txt10">Name :</td>
                <td height="30">&nbsp;</td>
                <td height="30"><input type="text" name="txtPosition" maxlength="50" size="40" class="txtfield" value="<?PHP echo $pname;?>" onkeyup="javascript:RemoveInvalidChars(txtPosition);"></td>
            </tr>
            <tr>
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
            <?PHP
            if ($posid> 0) {
                    echo "<input name='btnUpdate' type='submit' class='btn' value='Update' onClick = 'return form_validation(2);' />";
                    echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onClick = 'return form_validation_delete();' />";
            } else
            {
                    echo "<input name='btnSave' type='submit' class='btn' value='Save' onClick = 'return form_validation(1);'>";
            }
                  
            ?>
            <input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=153'" />
            </td>
          </tr>
        </table>
    </form>
	</td>
</table>
	</td>
  </tr>
</table>
<br /> 
    </td>
  </tr>
</table>