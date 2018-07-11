<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<?PHP 
	include IN_PATH.DS."scBranch.php";
	include IN_PATH.DS."pcBranch.php";
	
	//display error message
	$errMessage = '';
	if (isset($_GET['msg']))
    {
       $message = strtolower($_GET['msg']);
       $success = strpos("$message","success"); 
       $errMessage= "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
    }
    else if (isset($_GET['errmsg']))
    {
		$errormessage = strtolower($_GET['errmsg']);
		$error = strpos("$errormessage","error"); 
		$errMessage = "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";                        	
    }
    
    //region dropdown
    $drpRegion = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboRegion->num_rows)
    {
    	while ($row = $rs_cboRegion->fetch_object())
      	{
      		($areaid == $row->ID) ? $sel = 'selected' : $sel = '';
      		$drpRegion .= "<option value='$row->ID'' $sel>$row->Name</option>";
  		}
    }
    
 	//branch type dropdown
 	$drpBranchType = '<option value=\'0\' >[SELECT HERE]</option>';
 	if ($rs_cboBranchType->num_rows)
 	{
 		while ($row = $rs_cboBranchType->fetch_object())
     	{
     		($branchtypeid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpBranchType .= "<option value='$row->ID'' $sel>$row->Name</option>";
       	}
    }
    
    //branch size dropdown
 	$drpBranchSize = '<option value=\'0\' >[SELECT HERE]</option>';
 	if ($rs_cboBranchSize->num_rows)
 	{
 		while ($row = $rs_cboBranchSize->fetch_object())
     	{
     		($branchsizeid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpBranchSize .= "<option value='$row->ID'' $sel>$row->Name</option>";
       	}
    }
    
    //contact person dropdown
    $drpEmployee = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboEmployee->num_rows)
    {
     	while ($row = $rs_cboEmployee->fetch_object())
      	{
      		($empid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpEmployee .= "<option value='$row->ID'' $sel>$row->Name</option>";
       	}
    }
    
    //sales director dropdown
    $drpSalesDir = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboSalesDir->num_rows)
    {
     	while ($row = $rs_cboSalesDir->fetch_object())
      	{
      		($sdid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpSalesDir .= "<option value='$row->ID'' $sel>$row->Name</option>";
       	}
    }
    
    //area operations director dropdown
    $drpAODir = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboSalesDir->num_rows)
    {
     	while ($row = $rs_cboAODir->fetch_object())
      	{
      		($aodid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpAODir .= "<option value='$row->ID'' $sel>$row->Name</option>";
       	}
    }
    
    //sales department dropdown
    $drpSalesDept = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboSalesDept->num_rows)
    {
     	while ($row = $rs_cboSalesDept->fetch_object())
      	{
      		($sgid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpSalesDept .= "<option value='$row->ID'' $sel>$row->Name</option>";
       	}
    }
    
    //area operations department dropdown
    $drpAODept = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboAODept->num_rows)
    {
     	while ($row = $rs_cboAODept->fetch_object())
      	{
      		($aogid == $row->ID) ? $sel = 'selected' : $sel = '';
        	$drpAODept .= "<option value='$row->ID'' $sel>$row->Name</option>";
       	}
    }
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
	  obj = document.frmBranch.elements;
	  	  
	  // TEXT BOXES
	  if (trim(obj["txtfldCode"].value) == '') msg += '   * Code \n';
	  if (trim(obj["txtfldName"].value) == '') msg += '   * Name \n';	
	  if (trim(obj["txtStreetAdd"].value) == '') msg += '   * Street Address \n';
	  if (trim(obj["txtZipCode"].value) == '') msg += '   * Zip Code \n';	
	  if (trim(obj["txtTelNo1"].value) == '') msg += '   * Telephone No. \n';
	  if (trim(obj["txtFaxNo"].value) == '') msg += '   * Fax No. \n';
	  if (trim(obj["txtTIN"].value) == '') msg += '   * TIN \n';
	  if (trim(obj["txtPermitNo"].value) == '') msg += '   * Permit No. \n';
	  if (trim(obj["txtServerSN"].value) == '') msg += '   * Server S/N \n';
	  if (obj["cboRegion"].value == 0) msg += '   * Region \n';
	//  if (obj["cboEmployee"].value == 0) msg += '   * Contact Person \n';
	//  if (obj["cboSalesDir"].value == 0) msg += '   * Sales Director \n';
	//  if (obj["cboAODir"].value == 0) msg += '   * Area Operations Director \n';
	//  if (obj["cboSalesDept"].value == 0) msg += '   * Sales Group \n';
	//  if (obj["cboAODept"].value == 0) msg += '   * Area Operations Group \n';
	  if (obj["cboBranchType"].value == 0) msg += '   * Branch Type \n';
	  if (obj["cboBranchSize"].value == 0) msg += '   * Branch Size \n';
	  	
	  if (msg != '')
	  { 
	  	alert ('Please complete the following: \n\n' + msg);
	  	return false;
	  }	  
	  else 
	  {
			if (checker())
			{
				if (confirm('Are you sure you want to save this transaction?') == false)
			  		return false;
		  		else
			  		return true;				
			}
			else
			{
				return false;
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

function checkAll(bin) 
{
	var elms = document.frmBranch.elements;
  
	for(var i = 0;i < elms.length;i++)
	{
		if(elms[i].name=='chkInclude[]') 
	  	{
	  		elms[i].checked = bin;		  
	  	}			
	}
}

function checker()
{
	//count = 0;
    //
	//for(x=0; x<document.frmBranch.elements["chkInclude[]"].length; x++)
	//{
	//	if(document.frmBranch.elements["chkInclude[]"][x].checked==true)
	//	{
	//		count++;
	//	}
	//}
	//
	//if(count == 0)
	//{
	//	alert("Select bank(s) to link.");
	//  	return false;
	//}
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
						<td class="txtgreenbold13">Branch</td>
						<td>&nbsp;</td>
					</tr>
				</table>
			<!--  	<?php
				if ($errmsg != ""){
				?>
				
				
				<br>
				<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="70%" class="txtreds">&nbsp;<b><?php echo $errmsg; ?></b></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
				<?php		
					}
				?>-->
				<br />
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td width="33%" valign="top">
						<form name="frmSearchBranch" method="post" action="index.php?pageid=5">
                                                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin">&nbsp;</td>
								<td class="tabmin2"><span class="txtredbold">Action</span></td>
								<td class="tabmin3">&nbsp;</td>
							</tr>
						</table>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
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
											Code / Name :            
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
						<br />
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td class="tabmin">&nbsp;</td>
								<td class="tabmin2"><span class="txtredbold">Branch List </span></td>
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
										<td class="bordergreen_B">
											<div class="scroll_300">
												<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
												<?PHP	
												if ($rs_branchall->num_rows){
													$rowalt = 0;
													while ($row = $rs_branchall->fetch_object()){
														$rowalt += 1;
														($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
													echo  "	<tr align='center' class='$class'>
																<td height='20' class='borderBR' width='40%' align='left'>&nbsp;<span class='txt10'>$row->Code</span></td>
																<td class='borderBR' width='60%' align='left'>&nbsp;<span class='txt10'><a href='index.php?pageid=5&bid=$row->ID&svalue=$search' class='txtnavgreenlink'>$row->Name</a></td>
															</tr>";
													}
													$rs_branchall->close();
												}
												else{
													echo "	<tr align='center'>
																<td height='20' class='borderBR'><span class='txt10 txtredsbold'>No record(s) to display. </span></td>
															</tr>";	 
												}
												?>
												</table>
											</div>
										</td>
									</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td width="2%">&nbsp;</td>
					<td width="60%" valign="top">
					<form name="frmBranch" method="post" action="index.php?pageid=5" > 
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tabmin">&nbsp;</td>
							<td class="tabmin2"><span class="txtredbold">Branch Details</span>&nbsp;</td>
							<td class="tabmin3">&nbsp;</td>
						</tr>
					</table>
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
					<tr>
						<td class="bgF9F8F7">
							<?php echo $errMessage; ?>
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
							<td height="30" width="70%"><input type="text" name="txtfldCode" <?php if($_GET['bid'] != ""):?> readonly = "true" <?php endif; ?>maxlength="15" size="40" class="txtfield" value="<?PHP echo $code;?>" onkeyup="javascript:RemoveInvalidChars(txtfldCode);" />                  <?php 
									if ($bid > 0){
									echo "<input type=\"hidden\" name=\"hdnBranchID\" value=\"$bid\" />";
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
							<td height="30" width="25%" align="right" class="txt10" valign="top">Street Address :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%"><textarea rows='2' cols='35' name='txtStreetAdd' style="font-size: x-small; font-family: sans-serif;"/><?php echo $address; ?></textarea></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Region :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%">  <select name='cboRegion' style='width:160px' class='txtfield' ><?php echo $drpRegion; ?></select></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Zip Code :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%"><input type="text" name="txtZipCode" maxlength="50" size="40" class="txtfield" value="<?PHP echo $zipcode; ?>" onkeyup="javascript:RemoveInvalidChars(txtZipCode);"/></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Telephone Nos. :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%"><input type="text" name="txtTelNo1" maxlength="50" size="40" class="txtfield" value="<?PHP echo $telno1; ?>" onkeyup="javascript:RemoveInvalidChars(txtTelNo1);"/></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10"></td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%"><input type="text" name="txtTelNo2" maxlength="50" size="40" class="txtfield" value="<?PHP echo $telno2; ?>" onkeyup="javascript:RemoveInvalidChars(txtTelNo2);"/></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10"></td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%"><input type="text" name="txtTelNo3" maxlength="50" size="40" class="txtfield" value="<?PHP echo $telno3; ?>" onkeyup="javascript:RemoveInvalidChars(txtTelNo3);"/></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Fax No. :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%"><input type="text" name="txtFaxNo" maxlength="50" size="40" class="txtfield" value="<?PHP echo $faxno; ?>" onkeyup="javascript:RemoveInvalidChars(txtFaxNo);"/></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Branch Type :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%">  <select name='cboBranchType' style='width:160px' class='txtfield' ><?php echo $drpBranchType; ?></select></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Branch Size :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%">  <select name='cboBranchSize' style='width:160px' class='txtfield' ><?php echo $drpBranchSize; ?></select></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Contact Person :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%">  <select name='cboEmployee' style='width:160px' class='txtfield' ><?php echo $drpEmployee; ?></select></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Sales Director :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%">  <select name='cboSalesDir' style='width:160px' class='txtfield' ><?php echo $drpSalesDir; ?></select></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Area Operations Director :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%">  <select name='cboAODir' style='width:160px' class='txtfield' ><?php echo $drpAODir; ?></select></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Sales Group :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%">  <select name='cboSalesDept' style='width:160px' class='txtfield' ><?php echo $drpSalesDept; ?></select></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Area Operations Group :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%">  <select name='cboAODept' style='width:160px' class='txtfield' ><?php echo $drpAODept; ?></select></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">TIN :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%"><input type="text" name="txtTIN" maxlength="50" size="40" class="txtfield" value="<?PHP echo $tin; ?>"/></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Permit No. :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%"><input type="text" name="txtPermitNo" maxlength="50" size="40" class="txtfield" value="<?PHP echo $permitno; ?>"/></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10">Server S/N :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td height="30" width="70%"><input type="text" name="txtServerSN" maxlength="50" size="40" class="txtfield" value="<?PHP echo $serversn; ?>"/></td>
						</tr>
						<tr>
							<td height="30" width="25%" align="right" class="txt10" valign="top">Bank(s) :</td>
							<td height="30" width="5%">&nbsp;</td>
							<td width="70%">
								<table width="75%" align="left" border="0" cellpadding="0" cellspacing="0">
								<tr>
									<td>
										<table width="100%" align="left" border="0" cellpadding="0" cellspacing="0" class="bordergreen bordergreen_T">
										<tr class="tab">
											<td height="20" align="center" width="10%" class="bdiv_r"><input name="chkAll" type="checkbox" id="chkAll" value="1" class="inputOptChk" onclick="checkAll(this.checked);" /></td>
											<td height="20" align="center" width="45%" class="txtredbold bdiv_r">Name</td>
											<td height="20" align="center" width="30%" class="txtredbold bdiv_r">GLAccount</td>
											<td height="20" align="center" width="15%" class="txtredbold">Primary?</td>
										</tr>
										<tr>
											<td colspan="4">
												<div class="scroll_300">
												<table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
												<?php
													if($rs_bankbranch->num_rows){
														while($row = $rs_bankbranch->fetch_object()){
															$rowalt++;
															($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
															if($row->IsLinked == 1){
																$linked = "checked='checked'";
																if($row->IsPrimary == 1){
																	$primary = "checked='checked'";														
																}else{
																	$primary = "";
																}									
															}else{
																$linked = "";
																$primary = "";
															}
															
															echo "<tr align='center' class='$class'>
																	<td height='20' width='10%' align='center' class='borderBR'><input type='checkbox' name='chkInclude[]' class='inputOptChk' value='$row->BankID' $linked></td>
																	<td height='20' width='45%' align='left' class='borderBR padl5'>$row->Name</td>
																	<td height='20' width='30%' align='left' class='borderBR padl5'>$row->GLAccount</td>
																	<td height='20' width='15%' align='center' class='borderBR'><input type='radio' id='isPrimary' name='isPrimary' value='$row->BankID' $primary></td>
																</tr>";												
														}
														$rs_bankbranch->close();
													}
												?>
												</table>
												</div>
											</td>
										</tr>
										</table>
									</td>
								</tr>
								</table>
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
					<br />
					<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td align="center">
						<?php
							if ($_SESSION['ismain'] == 1) {
								if ($bid > 0) { ?>					
									<input name="btnUpdate" type="submit" class="btn" value="Update" onclick="return confirmSave();" />
						<?php   }else { ?>
									<input name="btnSave" type="submit" class="btn" value="Save" onclick="return confirmSave();" /> 
							<?php 
								}
							}				
						?>	
						<input name="btnCancel" type="button" class="btn" value="Cancel" onclick="window.location.href='index.php?pageid=5'" />		
						</td>
					</tr>
					</table>
					</form>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td height="20"></td>
		</tr>
		</table>
    </td>
  </tr>
</table>