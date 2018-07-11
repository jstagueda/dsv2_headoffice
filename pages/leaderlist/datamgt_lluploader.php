<?php
include IN_PATH.DS."scLeaderListUploader.php"; 
//echo '<pre>';var_dump($_GET);echo '</pre>';
?>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<script type="text/javascript">
function form_validation()
{
	obj = document.frmLeaderListUploader.elements;
	if (obj["cboUploadType"].selectedIndex == 0)
	{
		alert ('Please select file upload type.');
  		return false;
	}
	if (obj["cboUploadType"].selectedIndex == 2 && obj["cboCampaign"].selectedIndex == 0)
	{
		alert ('Please select a campaign');
  		return false;
	}
}

function toggleCampaign()
{
	obj = document.frmLeaderListUploader1.elements;
	if (obj["cboUploadType"].selectedIndex == 2)
	{
		obj["cboCampaign"].disabled = "";
	}
	else
	{
		obj["cboCampaign"].disabled = "disabled";
	}
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp(log) 
{
	var objWin;	
	popuppage = "pages/datamanagement/errorlog.php?elog="+log;		
		
	if (!objWin) 
	{			
		objWin = NewWindow(popuppage,'Error Log','800','500','yes');
	}
	return false;  		
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
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Leader List</span></td>
        </tr>
    </table>
    
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
 
</table>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	
	<td width="100%" valign="top">
    
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">

        <tr>
          <td class="mid_top">&nbsp;</td>
        </tr>
        <tr>
          <td class="mid2_top"><table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0">

              <tr>

				<td><span class="txtgreenbold13">Leader List Uploader</span></td>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table>
       <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td align="center" id="progressbar">&nbsp;</td>
        </tr>
      </table>
     
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" >
        <tr>
          <td class="cornerUL"></td>

          <td class="corsidesU"></td>
          <td class="cornerUR"></td>
        </tr>
        <tr>
         
          <td valign="top" class="bgFFFFFF">
          	<table width="60%"  border="0" cellpadding="0" cellspacing="0">
	     	<tr>
	       		<td class="tabmin">&nbsp;</td>
	       		<td class="tabmin2"><span class="txtredbold">Data Uploader</span>&nbsp;</td>
	       		<td class="tabmin3">&nbsp;</td>
       		</tr>
       		</table>
    		<form name="frmLeaderListUploader1" method="post" enctype="multipart/form-data" action="includes/pcLeaderListUploader.php">
	  		<table width="60%"  border="0" cellpadding="0" cellspacing="0" class="bordergreen">
            <?php 
						if (isset($_GET['msg']))
						{
							echo "<tr>";
	 						echo "<td height='20' colspan = '2' class='bgF9F8F7' align='right'>";
							$message = strtolower($_GET['msg']);
							$success = strpos("$message","success"); 
							echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
							echo "</td>";
							echo "</tr>";
                		} 
                		else if(isset($_GET['errmsg']))
                		{
                			echo "<tr>";
	 						echo "<td height='20' colspan = '2' class='bgF9F8F7' align='right'>";
                			// $errormessage = strtolower($_GET['errmsg']);
                			$errormessage = $_GET['errmsg'];
							$error = strpos("$errormessage","error"); 
							//echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";
							echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$errormessage."</div>";
							echo "</td>";
							echo "</tr>";
                		}
						?>
                <tr>
                  <td width="20%" height="20" class="bgF9F8F7">&nbsp;<span class="txtwhitebold">&nbsp;</span></td>
                  <td width="80%" height="20" class="bgF9F8F7">&nbsp;</td>
                </tr>
				<tr>
                  <td height="20" class="bgF9F8F7" align="right">&nbsp;<div align="right" class="txt10">Upload Type:</div></td>
                  <td width="80%" height="20" class="bgF9F8F7">&nbsp;
				  	<select name="cboUploadType" style="width:250px" class="txtfield" onClick="toggleCampaign();">
                        <option value="0">[SELECT HERE]</option>
						<option value="1">AOP</option>
						<option value="2">Rolling Forecast</option>
						<option value="3">Consolidated Rolling Forecast</option>
						<option value="4">Dummy Cost</option>
						<option value="5">Single Line Promo</option>
						<option value="6">Multiline Promo - Buyin</option>
						<option value="7">Multiline Promo - Entitlement</option>
						<option value="8">Overlay Promo - Buyin</option>
						<option value="9">Overlay Promo - Entitlement</option>
						<option value="10">Special Promo</option>
						<option value="11">Incentives</option>
                    </select>
				  </td>
                </tr>
                
                <tr>
                  <td height="20" class="bgF9F8F7" align="right">&nbsp;<div align="right" class="txt10">Campaign:</div></td>
                  <td width="80%" height="20" class="bgF9F8F7">&nbsp;
				  	<select name="cboCampaign" style="width:250px" class="txtfield" disabled="disabled">
                        <option value="0">[SELECT HERE]</option>
                        <?php while ($row = $rsCampaign->fetch_object())
                        {
                        	echo '<option value="' . $row->ID . '">' . $row->Code . '</option>\n';
                        }
                        ?>
                    </select>
				  </td>
                </tr>
                <tr>
                
                  <td height="20" class="bgF9F8F7" align="right">&nbsp;<div align="right" class="txt10">File:</div></td>
                  <td height="20" class="bgF9F8F7">&nbsp;
                    <input type="hidden" name="MAX_FILE_SIZE" value="31457280" />
                    <input type="file" name="file" class="btn"></td>
                </tr>
              
				
                <tr>
                  <td height="20" class="bgF9F8F7">&nbsp;</td>
                  <td height="20" class="bgF9F8F7">&nbsp;
                    <input name="btnUpload" type="submit" class="btn" value="Upload" onClick = "return form_validation();"></td>
                </tr>	
                <tr>
                	<td colspan="3" class="bgF9F8F7" height="20">&nbsp;</td>
                </tr>	
                
                
                 <tr> 
                  <td height="20" colspan="2" align="center" class="txtred">Right Format for Leader List Uploads. Please do not put a space in between.</td>
                </tr>	
                </form>

                <form name="frmLeaderListUploader" method="post" enctype="multipart/form-data" action="pages/leaderlist/datamgt_lluploader_forms.php">
                <tr>
                 
                  <td class="bgF9F8F7" height="20" align="center"  colspan="7" class="bgF9F8F7">&nbsp;
                    <a href="pages/leaderlist/datamgt_lluploader_forms.php" target="_blank">Correct Format Files to Upload</a> 
                  follow the correct FORMAT for Leader List Uploads</td> 
                </tr> 
                </form>
                 <tr>
                 <td colspan="7" class="bgF9F8F7" height="20">&nbsp;</td>     
                </tr>
                
                
                
              </table>
              </form>
       <br>
              </table>
              <?php
              	if(isset($_SESSION['ll_message_log']) && $_SESSION['ll_message_log'] != "")
              	{
              ?>
              <br>
              <table width="60%" align="left" border="0" cellpadding="0" cellspacing="0">
              <tr>
              		<td class="tabmin">&nbsp;</td>
              		<td class="tabmin2"><span class="txtredbold">Log Details</span>&nbsp;</td>
              		<td class="tabmin3">&nbsp;</td>
      			</tr>
          		</table>
          		<table width="60%"  border="0" cellpadding="0" cellspacing="0" class="bordergreen bgF9F8F7">
          		<tr>
          			<td><div class="scroll_300">
          			<?php echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_SESSION['ll_message_log']."</div>"; ?>
          			</div></td>
          		</tr>
          		</table>
              <?php              		
              	}              	
              ?>
              <?php
              	if(isset($_SESSION['ll_uploader_error']) && $_SESSION['ll_uploader_error'] != "")
              	{
              ?>
              <br>
              <table width="60%" align="left" border="0" cellpadding="0" cellspacing="0">
              <tr>
              		<td class="tabmin">&nbsp;</td>
              		<td class="tabmin2"><span class="txtredbold">Error Details</span>&nbsp;</td>
              		<td class="tabmin3">&nbsp;</td>
      			</tr>
          		</table>
          		<table width="60%"  border="0" cellpadding="0" cellspacing="0" class="bordergreen bgF9F8F7">
          		<tr>
          			<td><div class="scroll_300">
          			<?php echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_SESSION['ll_uploader_error']."</div>"; ?>
          			</div></td>
          		</tr>
          		</table>
              <?php              		
              	}              	
              ?>
            
            <br>
            <table width="60%" align="center" border="0" cellpadding="0" cellspacing="0">
            <tr>
            	<td>
            	</td>
            </tr>
            </table>
            
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="20" class="bgEFEDE9">&nbsp;</td>
              </tr>
            </table>
            </td>
          <td class="corsidesR">&nbsp;</td>
        </tr>
        <tr>

          <td class="cornerBL"></td>
          <td class="corsidesB"></td>
          <td class="cornerBR"></td>
        </tr>
      </table>
      
	</td>
  </tr>
</table>
	</td>
   </tr>
 </table>
<?php 
	unset($_SESSION['ll_message_log']);
	unset($_SESSION['ll_uploader_error']);
?>
