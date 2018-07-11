<?php
	if (!isset($_GET['msglog']))
	{
		unset($_SESSION['msg_log']);		
	}
?>
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery-ui-1.10.0.custom.min.js"></script>
<script type="text/javascript">
function form_validation()
{
	obj = document.frmPackingListUpload.elements;
	if (obj["cboUploadType"].selectedIndex == 0)
	{
		alert ('Please select file upload type.');
  		return false;
	}else{
		//
		$("#indicatorx").show();
		$(".scroll_300").html('');
		$("#btnPrint").attr("disabled","disabled");
	}
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	var winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	var win = window.open(mypage,myname,winprops)
	
	if (parseInt(navigator.appVersion) >= 4) 
	{ 
		win.window.focus(); 
	}
}

function openPopUp() 
{
	var objWin;	
	//popuppage = "pages/datamanagement/errorlog.php?elog="+log;		
	popuppage = "pages/datamanagement/errorlog.php";
		
	if (!objWin) 
	{			
		objWin = NewWindow(popuppage,'ErrorLog','800','500','yes');
		return true;
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
    <td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management </span></td>
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
    
	<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">

        <tr>
          <td class="mid_top">&nbsp;</td>
        </tr>
        <tr>
          <td class="mid2_top">
		  <table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0">
              <tr>

				<td><span class="txtgreenbold13">Uploader</span></td>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table>
       <table width="90%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td align="center" id="progressbar">&nbsp;</td>
        </tr>
      </table>
     
      <table width="60%"  border="0" align="left" cellpadding="0" cellspacing="0" >
        <tr>
          <td class="cornerUL"></td>

          <td class="corsidesU"></td>
          <td class="cornerUR"></td>
        </tr>
        <tr>
         
          <td valign="top" class="bgFFFFFF">
          	<table width="100%"  border="0" cellpadding="0" cellspacing="0">
	     	<tr>
	       		<td class="tabmin">&nbsp;</td>
	       		<td class="tabmin2"><span class="txtredbold">Packing List Uploader</span>&nbsp;</td>
	       		<td class="tabmin3">&nbsp;</td>
       		</tr>
       		</table>
    		<form name="frmPackingListUpload" method="post" enctype="multipart/form-data" action="includes/pcPackingListUpload.php">
	  		<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bordergreen">
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
                			$errormessage = strtolower($_GET['errmsg']);
							$error = strpos("$errormessage","error"); 
							echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";
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
				  	<select name="cboUploadType" style="width:250px" class="txtfield">
                        <option value="0">[SELECT HERE]</option>
						<option value="1">RHO</option>
						<option value="2">STA</option>
						<option value="3">Product Master</option>
						<option value="4">CE-Charge Account</option>
                    </select>
				  </td>
                </tr>
                <tr>
                
                
                </tr>
                <tr>
                
                  <td height="20" class="bgF9F8F7" align="right">&nbsp;<div align="right" class="txt10">File:</div></td>
                  <td height="20" class="bgF9F8F7">&nbsp;
                    <?php  ?><input type="hidden" name="MAX_FILE_SIZE" value="1073741824" /><?php ?>
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
              </table>
              </form>
       <br>
           
              <?php 
                  
		          if(isset($_GET['err']))
					{
						$errFnd = $_GET['err'];
					
					}
					else
					{
						$errFnd = 0;
					}
					
					if($errFnd != 0)
					{
					echo "	
					<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0'>
					<tr>
						<td class='tabmin'>&nbsp;</td>
						<td class='tabmin2'><span class='txtredbold'>Error uploading file</span>&nbsp;</td>
						<td class='tabmin3'>&nbsp;</td>
					</tr>
					</table>
					
					<div class='scroll_300h'>
            		<table width='100%'  border='0' cellpadding='0' cellspacing='1'>
						
						<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='1' class='bordergreen' id='tbl2'>
						<tr>
							<td valign='top' class='bgF9F8F7'>
						
							</td>
						</tr>
						<tr>
							<td>
							<div class='scroll_300'>
								<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='bgFFFFFF'>";

								$rs_checkingExistingProd2 = $sp->spCheckNotExistingProdPL();
								
									if($rs_checkingExistingProd2->num_rows)		
									{
										
										$rowalt=0;
				
										while($row = $rs_checkingExistingProd2->fetch_object())
										{
											$rowalt++;
											($rowalt%2) ? $class = "" : $class = "bgEFF0EB";	
						 				  echo "<tr align='center'>
												  <td width='10%' height='20' class='borderBR'><div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>$row->errMsg</div></td>";
										  echo "</tr>";
										  
										}
										  
										  
									}		
						
										echo "</table>
							</div>
						
						
						</table>
						</td>
						</tr>
						</table> ";						
						
					}
              
              
              ?>

              </table>
              <?php //if(isset($_SESSION['msg_log']) && $_SESSION['msg_log'] != ""){ ?>
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
          			<td> <!-- print logs here -->
					<div id = "indicatorx" style="display:none;">
						<br /><div style='text-align:center;' ><img src='images/ajax-progress-bar.gif'></img><br />please wait.</div>
					</div>
					<div class="scroll_300">
          			<?php 
						if(isset($_SESSION['msg_log']) && $_SESSION['msg_log'] != ""){
						echo "<div align='left' id = 'logs' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_SESSION['msg_log']."</div>"; 
						}else{
						//no here..
						}
					?>
          			</div></td>
          		</tr>
          		</table>
				<br />
				<table width="60%" align="center" border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><input name="btnPrint" id = "btnPrint" <?php if(!isset($_SESSION['msg_log']) && $_SESSION['msg_log'] == ""): echo "disabled=disabled"; endif;?> type="button" class="btn" value="Print Log" onclick="return openPopUp();"></td>
				</tr>
				</table>
              <?php              		
              	//}              	
              ?>
				
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