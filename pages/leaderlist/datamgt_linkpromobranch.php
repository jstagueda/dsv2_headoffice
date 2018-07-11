<?php
	global $database;
	include IN_PATH.DS."scLinkPromoBranch.php";
?>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<script type="text/javascript">
function checkAll(bin) 
{
	var elms = document.frmPromoBranchLink.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkInclude[]') {
		  elms[i].checked = bin;		  
	  }		
}

function validateSave()
{
	objiid = document.frmPromoBranchLink.elements["chkInclude[]"];
	var pid = eval('document.frmPromoBranchLink.hPID');
   	var arrList  = new Array();
   	var d = 0;
   	
   	if (pid.value == 0){
   		alert('Select promo first.');
   		return false;   		
   	}
   	
   	if (confirm('Are you sure you want to save this transaction?') == false)
		return false;
	else
   		return true;
   	
   	
   	/*if (objiid.length > 0) {
   		for (var c = 0; c < objiid.length; c++){
			if (objiid[c].checked){
				arrList[d] = objiid[c].value
				d++
			}
	   	}
   }else if (objiid.checked){
		arrList[0] = objiid.value;
		d++
   }
   	  
   if(d == 0){
		alert('Please select branch(es) to link.');
		return false;
   }else{
   }*/
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp(ptypeid, promoid, isincentive, promocode)
{
	if (ptypeid == 1)
	{
		var objWin;
		popuppage = "pages/leaderlist/promo_singleLineDetails.php?prmsid=" + promoid +"&link_branch=not_allowed_to_edit";
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','1500','700','yes');
		}
		
		return false;  		
	}
	else if (ptypeid == 2)
	{
		var width = 1500;
	    var height = 700;
	    var left = parseInt((screen.availWidth/2) - (width/2));
	    var top = parseInt((screen.availHeight/2) - (height/2));
	    var windowFeatures = "width=" + width + ",height=" + height + ",status,resizable,left=" + left + ",top=" + top + "screenX=" + left + ",screenY=" + top;
	    window.open("pages/leaderlist/promo_popup.php?biID="+promoid+"&link_branch=not_allowed_to_edit&pcode="+promocode,'popup',windowFeatures); 
	    return false;
	}
	else if (ptypeid == 3)
	{
		if (isincentive == 1)
		{
			inc = 2;
		}
		else
		{
			inc = 1;			
		}
		var objWin;
		popuppage = "pages/leaderlist/promo_setpromoDetails.php?&link_branch=not_allowed_to_edit&prmsid=" + promoid + "&inc="+inc;
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','1500','700','yes');
		}
		
		return false; 
	}
	else
	{
		var objWin;
		popuppage = "pages/leaderlist/promo_steplevelDetails.php?prmsid=" + promoid +"&link_branch=not_allowed_to_edit";
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','1500','700','yes');
		}
		
		return false;  
	}
}
</script>

<form name="frmPromoBranchLink" method="post" action="index.php?pageid=127&pid=<?php echo $pid; ?>">
<input type="hidden" name="hPID" value="<?php echo $pid; ?>">
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
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Leader List</span></td>
        </tr>
    </table>
    <br />
   
   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
    	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td class="txtgreenbold13">Promo - Branch Linking</td>
    		<td>&nbsp;</td>
  		</tr>
   		<tr>
    		<td height="10"> </td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<?php
		if ((isset($_GET['msg'])))
		{
		?>
		<br>
		<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="70%" class="txtblueboldlink">&nbsp;<b><?php echo $_GET['msg'] ?></b></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<?php		
		}
		?>
		<?php
		if ((isset($_GET['errmsg'])))
		{
		?>
		<br>
		<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="70%" class="txtreds">&nbsp;<b><?php echo $_GET['errmsg'] ?></b></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<?php		
		}
		?>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="40%" valign="top">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		  <tr>
		    <td>
                    <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
                    <tr>
                      <td width="50%">&nbsp;</td>
                      <td width="29%" align="right">&nbsp;</td>
                      <td width="21%" align="right">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="3">
                         Promo Type :            
                              <select name="cboPromoType" style="width:160px" class="txtfield">
                              	<?php							
  									echo "<option value=\"0\" >[SELECT HERE]</option>";
									if ($rs_promotype->num_rows)
									{
										while ($row = $rs_promotype->fetch_object())
										{
											($ptypeid == $row->ID) ? $sel = "selected" : $sel = "";
											echo "<option value='$row->ID' $sel>$row->Name</option>";
										}
										$rs_promotype->close();
									}
                            	?>
                              </select>
                    </tr>
                    <tr>
                      <td colspan="3">
                         Promo Code :            
                              <input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="20" value="<?php echo $search; ?>">
                              <input name="btnSearch" type="submit" class="btn" value="Search" /></td>
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
		      <br>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		        <tr>
		          <td class="tabmin">&nbsp;</td>
		          <td class="tabmin2"><span class="txtredbold">List of Promos</span></td>
		          <td class="tabmin3">&nbsp;</td>
		        </tr>
		      </table>
		      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl0">
		        <tr>
		          <td valign="top" class="bgF9F8F7">
                      <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                          <tr>
                            <td class="tab">
                                <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                                    <tr align="center">
                                      <td width="35%" class=""><div align="left"><span class="txtredbold padl5">Promo Code</span></div></td>
                                      <td width="50%" class=""><div align="left"><span class="txtredbold padl5">Promo Description</span></div></td>
                                      <td width="15%"><div align="right"><span class="txtredbold padr5">Branches</span></div></td>
                                </table>
                            </td>
                          </tr>
                          <tr>
                            <td class=""><div class="scroll_300">                        
                                    <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
	                                   <?php 
	                                        if($rs_promolist->num_rows)
	                                        {
	                                            $rowalt = 0;
	                                            while($row = $rs_promolist->fetch_object())
	                                            {
	                                            	$rs_branch_list = $sp->spSelectBranchByPromoID($database, $row->ID);
	                                            	$count = $rs_branch_list->num_rows;
	                                                ($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
	                                                $popup = 'openPopUp('.$row->PromoTypeID.', '.$row->ID.', '.$row->IsIncentive.', "'.$row->Code.'")';
	                                                
	                                                echo "<tr align='center' class='$class'>
	                                                    	<td height='20' class='borderBR padl5' width='35%' align='left'><a href='#' onclick='$popup' class='txtnavgreenlink'>$row->Code</a></td>
	                                                    	<td height='20' class='borderBR padl5' width='50%' align='left'><a href='index.php?pageid=127&pid=$row->ID&search=$search' class='txtnavgreenlink'>$row->Description</a></td>
	                                                    	<td height='20' class='borderBR padr5' width='15%' align='right'><span class='txt10'>$count</span></td>
	                                                     </tr>";
	                                            	$rowalt +=1; 
	                                            }
	                                            $rs_promolist->close();
	                                        }
	                                        else
	                                        {
	                                            echo "<tr align='center'><td height='20' colspan='3' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td></tr>";
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
	<td width="5%">&nbsp;</td>
	<td width="50%" valign="top">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2"><span class="txtredbold">Promo Details</span></td>
			<td class="tabmin3">&nbsp;</td>
        </tr>
      	</table>
      	<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bordergreen bgF9F8F7">
      	<tr>
      		<td colspan="2" height="15">&nbsp;</td>
      	</tr>
      	<tr>
      		<td height="20" width="25%" align="right" class="padr5"><strong>Promo Code :</strong></td>
      		<td height="20" width="75%" align="left" class="padl5"><?php echo $code; ?></td>
      	</tr>
      	<tr>
      		<td height="20" align="right" class="padr5"><strong>Promo Description :</strong></td>
      		<td height="20" align="left" class="padl5"><?php echo $desc; ?></td>
      	</tr>
      	<tr>
      		<td height="20" align="right" class="padr5"><strong>Start Date :</strong></td>
      		<td height="20" align="left" class="padl5"><?php echo $sdate; ?></td>
      	</tr>
      	<tr>
      		<td height="20" align="right" class="padr5"><strong>End Date :</strong></td>
      		<td height="20" align="left" class="padl5"><?php echo $edate; ?></td>
      	</tr>
      	<tr>
      		<td colspan="2" height="15">&nbsp;</td>
      	</tr>
      	</table>
      	<br><br>
      	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2"><span class="txtredbold">List of Branches</span></td>
			<td class="tabmin3">&nbsp;</td>
        </tr>
      	</table>
      	<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bordergreen">
      	<tr>
      		<td>
      			<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
                    <tr align="center" class="tab">
                      <td width="10%" class="bdiv_r"><div align="center"><input name="chkAll" type="checkbox" id="chkAll" value="1" class="inputOptChk" onclick="checkAll(this.checked);" /></div></td>
                      <td width="30%" class="bdiv_r"><div align="left"><span class="txtredbold padl5">Code</span></div></td>
                      <td width="60%"><div align="left"><span class="txtredbold padl5">Name</span></div></td>
                </table>
                <div class="scroll_300">
      			<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="">
      			<?php
      				if($rs_branch->num_rows)
                    {
                    	if (isset($_GET['pid']))
                    	{
                    		$pid = $_GET['pid'];
                    	}
                    	else
                    	{
                    		$pid = 0;                    		
                    	}
                    	$rowalt = 0;
                    	while($row = $rs_branch->fetch_object())
                        {
                        	$linked = $sp->spSelectBranchByPromoIDAndBranchID($database, $pid, $row->ID);
                        	$clinked = $linked->num_rows;
                        	if($clinked != 0)
                        	{
                        		$str = 'checked';
                        	}
                        	else
                        	{
                        		$str = '';
                        	}
                        	
                    	 	($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
                        	echo "<tr class='$class'>
		      						<td width='10%' height='20' align='center' class='borderBR'><input type='checkbox' name='chkInclude[]' class='inputOptChk' value='$row->ID' $str></td>
		      						<td width='30%' height='20' align='left' class='borderBR padl5'>$row->Code</td>
		      						<td width='58%' height='20' align='left' class='borderBR padl5'>$row->Name</td>
		      					</tr>";
		      				 $rowalt += 1;
                        }
                        $rs_branch->close();
                    }
      			?>
      			</table>
      			</div>
      		</td>
      	</tr>
      	</table>
      	<br>
      	<?php
		if ($_SESSION['ismain'] == 1)
		{
		?>
      		<table width="100%"  border="0" cellpadding="0" cellspacing="1">
      		<tr align="center">
      			<td height="20">
      				<input name="btnSave" type="submit" class="btn" value="Save" onClick="return validateSave();" />
      				<input name="btnCancel" type="submit" class="btn" value="Cancel"/>
      			</td>
      		</tr>
      		</table>
  		<?php
		}
		?>
	</td>
  </tr>
</table>
	</td>
   </tr>
   <tr><td height="20"></td></tr>
 </table>
 
    </td>
  </tr>
</table>
</form>