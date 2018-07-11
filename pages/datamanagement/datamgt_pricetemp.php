<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<?PHP 
      include IN_PATH.DS."scPricingTemplate.php";
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
function ConfirmSave(x)
{
	lang = 0;
	def = 0;
	count = 0;
	msg = '';
	str = '';
	obj = document.frmPricetemplate.elements;
	
	// TEXT BOXES
	if (trim(obj["txtfldcode"].value) == '') msg += '   * Code \n';
	if (trim(obj["txtfldname"].value) == '')msg += '   * Name \n';
	//if (obj["cboRef"].selectedIndex == 0) msg += '   * Reference Template \n';
	
	if(trim(obj["txtfldmarkup"].value)> 100.00)
	    {
		 msg += '   * Mark Up must be less than or equal to 100 \n';
	    }
	if(trim(obj["txtfldd1"].value)> 100.00)
		{
		 msg += '   * Discount 1 must be less than or equal to 100 \n';
		}

	if(trim(obj["txtfldd2"].value)> 100.00)
		{
	     msg += '   * Discount 2 must be less than or equal to 100 \n';
		}

	if(trim(obj["txtfldd3"].value)> 100.00)
		{
		 msg += '   * Discount 3 must be less than or equal to 100 \n';
		}
	
	
	
	if(!isNumeric(trim(obj["txtfldmarkup"].value)))
	    {
		 msg += '   * Invalid numeric format for Mark Up field \n';
	    }
	if(!isNumeric(trim(obj["txtfldd1"].value)))
		{
		 msg += '   * Invalid numeric format for Discount 1 field \n';
		}

	if(!isNumeric(trim(obj["txtfldd2"].value)))
		{
	     msg += '   * Invalid numeric format for Discount 2 field \n';
		}

	if(!isNumeric(trim(obj["txtfldd3"].value)))
		{
		 msg += '   * Invalid numeric format for Discount 3 field \n';
		}



	
	if (msg != '')
		{ 
	 	 alert ('Please complete the following: \n\n' + msg);
	 	 return false;
		}
	
	else
	{
		//Checker(trim(obj["txtfldmarkup"].value));
		
		if(x = 1)
		{
		  if (confirm('Are you sure you want to save this transaction?') == false)
			  return false;
		  else
			  return true;
		}
		else if (x = 2)
		{
			if (confirm('Are you sure you want to update this transaction?') == false)
				  return false;
			  else
				  return true;
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

function isNumeric(val){return(parseFloat(val,10)==(val*1));}

function RemoveInvalidLetters3(strString)
{
    var iChars = "1234567890.";
	   
   var strtovalidate = strString.value;
   var strlength = strtovalidate.length;
   var strChar;
   var ctr = 0;
   var newStr = '';
   if (strlength == 0)
   {
	return false;
   }

	for (i = 0; i < strlength; i++)
	{
		strChar = strtovalidate.charAt(i);
			if 	(!(iChars.indexOf(strChar) == -1))
			{
				newStr = newStr + strChar;
			}
	}
	strString.value = newStr;
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
    <td class="txtgreenbold13"> Price Template</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="33%" valign="top">
     <form name="frmSearchPriceTemplate" method="post" action="index.php?pageid=9">
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
						  <input name="txtfldSearch" type="text" class="txtfield" id="txtSearch" size="20">
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
		          <td class="tabmin2"><span class="txtredbold">Price Templates</span></td>
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
		                <td class="bordergreen_B"><div class="scroll_300"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                           <?PHP 
						if ($rs_pricetempall->num_rows)
						{
							$rowalt = 0;
							while ($row = $rs_pricetempall->fetch_object())
							{
								 $rowalt += 1;
				 				($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
                              echo  "<tr align='center' class='$class'>
								  <td height='20' class='borderBR' width='40%' align='left'>&nbsp;<span class='txt10'>$row->Code</span></td>
								  <td class='borderBR' width='60%' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=9&ptid=$row->ID&svalue=$search' class='txtnavgreenlink'>$row->Name</a></td>
						  		</tr>
								";
							}
							$rs_pricetempall->close();
						}
						else 
						{
						echo "<tr align='center'>
								  <td height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td>
							    </tr>";
							 
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
     <form name="frmPricetemplate" method="post" action="includes/pcPricingTemplate.php">
	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
     <tr>
       <td class="tabmin">&nbsp;</td>
       <td class="tabmin2"><span class="txtredbold">Price Template Details</span>&nbsp;</td>
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
			<td height="30" width="70%"><input type="text" name="txtfldcode" maxlength="15" size="40" class="txtfield" value="<?PHP echo $code; ?>" onkeyup="javascript:RemoveInvalidChars(txtfldcode);">
            <?php 
                        if ($ptid > 0){
                          echo "<input type=\"hidden\" name=\"hdnPriceTempID\" value=\"$ptid\" />";
                        }
                    ?>
            </td>
		</tr>
		<tr>
			<td height="30" width="25%" align="right" class="txt10">Name :</td>
			<td height="30" width="5%">&nbsp;</td>
			<td height="30" width="70%"><input type="text" name="txtfldname" maxlength="30" size="40" class="txtfield" value="<?PHP echo $name; ?>" onkeyup="javascript:RemoveInvalidChars(txtfldname);"/></td>
		</tr>
		<tr>
		  <td height="30" align="right" class="txt10">Mark Up :</td>
		  <td height="30">&nbsp;</td>
		  <td height="30"><input type="text" name="txtfldmarkup" maxlength="6" size="40" class="txtfield" value="<?PHP echo number_format($markup,2); ?>" onkeyup="javascript:RemoveInvalidLetters3(txtfldmarkup);" />%</td>
		  </tr>
		<tr>
		  <td height="30" align="right" class="txt10">Discount 1 :</td>
		  <td height="30">&nbsp;</td>
		  <td height="30"><input type="text" name="txtfldd1" maxlength="6" size="40" class="txtfield" value="<?PHP echo number_format($discount1,2); ?>" onkeyup="javascript:RemoveInvalidLetters3(txtfldd1);" />%</td>
		  </tr>
		<tr>
		  <td height="30" align="right" class="txt10">Discount 2 :</td>
		  <td height="30">&nbsp;</td>
		  <td height="30"><input type="text" name="txtfldd2" maxlength="6" size="40" class="txtfield" value="<?PHP echo number_format($discount2,2); ?>" onkeyup="javascript:RemoveInvalidLetters3(txtfldd2);"/>%</td>
		  </tr>
		<tr>
		  <td height="31" align="right" class="txt10">Discount 3 :</td>
		  <td height="31">&nbsp;</td>
		  <td height="31"><input type="text" name="txtfldd3" maxlength="6" size="40" class="txtfield" value="<?PHP echo number_format($discount3,2); ?>" onkeyup="javascript:RemoveInvalidLetters3(txtfldd3);"/>%</td>
		  </tr>
		<tr>
			<td height="30" width="25%" align="right" class="txt10">Reference Template :</td>
			<td height="30" width="5%">&nbsp;</td>
			<td height="30" width="70%"><select name="cboRef" id="select" class="txtfield">
			
			<?PHP
			echo "<option value=\"0\" >[SELECT NONE]</option>";
			if ($rs_cboRef->num_rows)
			{
				while ($row = $rs_cboRef->fetch_object())
				{
					($ref == $row->ID) ? $sel = "selected" : $sel = "";
					echo "<option value='$row->ID' $sel>$row->Code</option>";											
				}
			}		
			?>
			<input type="hidden" id="hdnrefTemplate" name="hdnrefTemplate" value="<?php echo $ref;?> " >
			
										

			  </select></td>
		</tr>
		</table>		
	</td>
     </tr>
   </table>
   <br>
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  <tr>
	    <td align="center">
			<?PHP
            if ($ptid > 0)
            {
                    echo "<input name='btnUpdate' type='submit' class='btn' value='Update' onclick='return ConfirmSave(2);'/>";
                    echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onclick='return confirmDelete();' />";
            } 
            else
            {
                    echo "<input name='btnSave' type='submit' class='btn' value='Save' onclick='return ConfirmSave(1);'>";
            }
                  
            ?>
              <input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=9'"/></td>
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