<script language="javascript" src="js/tab-view.js"  type="text/javascript"></script>
<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<link type="text/css" href="css/jquery-ui-1.8.5.custom.css" rel="stylesheet"/>
<script language="javascript" src="js/jxProductKit.js"  type="text/javascript"></script>
<script src="js/shortcut.js" type="text/javascript"></script>
<style type="text/css">
div.autocomplete {
  position:absolute;
  /*width:300px;*/
  /*border:1px solid #888;
  margin:0px;
  padding:0px;*/
}

div.autocomplete span { position:relative; top:2px; }

div.autocomplete ul {
    width: 319px;
    border: 1px solid #FF00A6;
    color: #312E25;
    list-style-type:none;
    margin:0px;
    padding:0px;
    border-radius:6px;
    background-color:white;
    background: #F5F3E5;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  /*font-family: Verdana, Arial, Helvetica, sans-serif;*/
  /*font-size: 10px;*/
}

div.autocomplete ul li.selected{
    background-color: #EB0089;
    border:1px solid #c40370;
    color:white;
    font-weight:bold;
    margin:3px;
    border-radius:6px;
}

div.autocomplete ul li {
    line-height: 1.5;
    padding: 0.2em 0.4em;
    list-style-type:none;
    display:block;
    /*height:20px;*/
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    cursor:pointer;
}

.ui-dialog .ui-dialog-titlebar-close span{margin : -10px 0 0 -10px;}
</style>
<script>

jQuery(function(){
    jQuery('[name=txtSDate]').datepicker({
        changeMonth :   true,
        changeYear  :   true
    });

    jQuery('[name=txtEDate]').datepicker({
        changeMonth :   true,
        changeYear  :   true
    });
});

function RemoveInvalidChars(strString)
{
	var iChars = "1234567890";
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

	return true;
}
</script>
<?PHP
	include IN_PATH.DS."scProductKit.php";
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="200" valign="top" class="bgF4F4F6">
    	<?PHP
			include("nav.php");
		?>

      <br></td>
    <td class="divider">&nbsp;</td>
    <td valign="top" style="min-height: 600px; display: block;"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Promo and Pricing Management System (PPMS)</span></td>
        </tr>
    </table>
    <br />

   <table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">Product Kit - Component Linking</td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php
	if ((isset($_GET['errmsg2'])))
	{
?>
<br>
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%" class="txtredsbold">&nbsp;<b><?php echo $_GET['errmsg2'] ?></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
	}
?>
<?php
	if ((isset($_GET['msg'])))
	{
?>
<br>
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
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
<br />
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
	<td width="35%" valign="top">
		<form name="frmKit" method="post" action="index.php?pageid=118">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin">&nbsp;</td>
					<td class="tabmin2"><span class="txtredbold">Action</span>&nbsp;</td>
					<td class="tabmin3">&nbsp;</td>
				</tr>
			</table>
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
				<tr>
					<td>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
							<tr>
								<td width="25%"></td>
								<td width="5%">&nbsp;</td>
								<td></td>
							</tr>
							<tr>
								<td align="right">Search</td>
								<td align="center">:</td>
								<td>
									<input name="txtfldsearch" type="text" class="txtfield" id="txtfldsearch" autocomplete="off" onkeypress = "xautocomplete();" size="20" value="<?php echo $search;?>">
									<input name="btnSearch" type="submit" class="btn" value="Search">
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
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
			  <td class="tabmin2"><span class="txtredbold">Product List</span></td>
			  <td class="tabmin3">&nbsp;</td>
			</tr>
		</table>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
			<tr>
				<td valign="top" class="bgF9F8F7">
					<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
						<tr>
							<td class="tab" style='background:#FFDEF0; border-bottom:2px solid #FFA3E0;'>
								<table height="100%" width="100%"  border="0" cellpadding="0" cellspacing="0" class="txtdarkgreenbold10">
									<tr align="center">
										<td width="30%" class="bdiv_r">
											<div align="center"><span class="txtredbold">Code</span></div>
										</td>
										<td width="70%"><div align="center"><span class="txtredbold">Name</span></div></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td>
								<div>
									<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="bgFFFFFF">
										<?PHP
										if ($rsProductKit->num_rows){
											while ($row = $rsProductKit->fetch_object())
											{
											   echo "<tr align='center'>
													<td width='30%' height='20' class='borderBR' align='center'>
														<span class='txt10'>
															<a href='index.php?pageid=118&prodID=$row->ID&search=$search' class='txtnavgreenlink' style='color:blue;'>$row->Code</a>
														</span>
													</td>
													<td width='70%' class='borderBR' align='left' style='padding:3px;'><span class='txt10'>".$row->Name."</span></td>
												</tr>";
											}
											$rsProductKit->close();
										}else{
											echo "<tr align='center'>
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
	<td width="1%">&nbsp;</td>
	<td width="64%" valign="top">
		<form name="frmProdKitInfo" method="post" action="includes/pcProductKit.php">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin">&nbsp;</td>
					<td class="tabmin2"><span class="txtredbold">Product Kit Details</span>&nbsp;</td>
					<td class="tabmin3">&nbsp;</td>
				</tr>
			</table>
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
				<tr>
					<td class="bgF9F8F7">&nbsp;</td>
				</tr>
				<tr>
					<td class="bgF9F8F7">
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
							<tr>
								<td width=""></td>
								<td width="5%"></td>
								<td></td>
							</tr>
							<tr>
								<td align="right" style="padding:5px;"><b>Code</b></td>
								<td align="center">:</td>
								<td>
									<?php echo $prodCode ;?>
									<input type="hidden" name="kitID" maxlength="15" size="30" class="txtfield" value="<?php echo $prodID ;?>" />
								</td>
							</tr>
							<tr>
								<td align="right" style="padding:5px;"><b>Name</b></td>
								<td align="center">:</td>
								<td><?php echo $prodName ;?></td>
							</tr>
							<tr>
								<td align="right" style="padding:5px;"><b>Component Code/Name</b></td>
								<td align="center">:</td>
								<td>
								<input name="txtComponent" type="text" class="txtfield" id="txtComponent" />
									<span id="indicator1" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>
									<div id="coa_choices" class="autocomplete" style="display:none"></div>
									<script type="text/javascript">
										 //<![CDATA[
												var coa_choices = new Ajax.Autocompleter('txtComponent', 'coa_choices', 'includes/scProductKitComponentAjax.php', {afterUpdateElement : getSelectionCOAID, indicator: 'indicator1'});
												//]]>
									</script>
									<input name="hProductID" type="hidden" id="hProductID" />
									<input name="hProductCode" type="hidden" id="hProductCode" />
									<input name="hProductName" type="hidden" id="hProductName" />
								</td>
							</tr>
							<tr>
								<td align="right" style="padding:5px;"><b>Quantity</b></td>
								<td align="center">:</td>
								<td><input type="text" class="txtfield5" name="txtQty" onkeyup="javascript:RemoveInvalidChars(txtQty);" id="txtQty" style="text-align:right" /></td>
							</tr>
							<tr>
								<td align="right" style="padding:5px;"><b>Start Date</b></td>
								<td align="center">:</td>
								<td>
									<input name="txtSDate" type="text" class="txtfield" readonly="readonly" id="txtSDate" size="20" value="" style="width: 80px;" >
								</td>
							</tr>
							<tr>
								<td align="right" style="padding:5px;"><b>End Date</b></td>
								<td align="center">:</td>
								<td>
									<input name="txtEDate" type="text" class="txtfield" id="txtEDate" readonly="readonly" size="20" value="" style="width: 80px;">
								</td>
							</tr>
							<?php if ($_SESSION['ismain'] == 1){ ?>
							<tr>
								<td></td>
								<td></td>
								<td style="padding-top:5px;"><input type="button" class="btn" name="btnAdd" id="btnAdd" value="Add" onclick="return clickAdd();"  /></td>
							</tr>
							<?php }?>
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
			
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin">&nbsp;</td>
					<td class="tabmin2"><span class="txtredbold">List of Components </span>&nbsp;</td>
					<td class="tabmin3">&nbsp;</td>
				</tr>
			</table>
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
				<tr>
					<td class="bgF9F8F7">
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" style="border-bottom:2px solid #FFA3E0;">
							<tr style="background:#FFDEF0; font-weight:bold;">
								<td width="4%" align="center"><input type="checkbox" name="chkIncludeAll"  id="chkIncludeAll"  onclick="return checkAll(this.checked)" ></td>
								<td width="15%" align="center"><span class="txtredbold">Item Code</span></td>
								<td width="35%" align="center"><span class="txtredbold">Description</span></td>
								<td width="12%" align="center"><span class="txtredbold">Start Date</span></td>
								<td width="12%" align="center"><span class="txtredbold">End Date</span></td>
								<td width="12%" align="center"><span class="txtredbold">Qty</span></td>
							</tr>
						</table>
						
						<!--Dynamic table -->
						<input type="hidden" name="hcnt" id="hcnt" value="<?php if(isset($rsProductKitComponents)) { echo $rsProductKitComponents->num_rows; } ?>" />
						<!--Dynamic Table -->
						<div style="overflow:auto; max-height:200px;">	
							<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" id="dynamicTable">
								<?PHP
								if(isset($_GET['prodID'])){
									if ($rsProductKitComponents->num_rows)
									{
										$ctr = 1;
										while ($row = $rsProductKitComponents ->fetch_object())
										{
											$start = date("m/d/Y", strtotime($row->StartDate));
											$end = date("m/d/Y", strtotime($row->EndDate));

										   echo "<tr align='center' class='trlist'>
													<td width='5%' height='20' class='borderBR' align='center'><input type='checkbox' name='chkInclude[]' id='chkInclude[]' value='$ctr'><input type='hidden' value='$row->ProdCompID'  name='hProdID$ctr'  id='hProdID$ctr'/></td>
													<td width='15%' height='20' class='borderBR padl5' align='left'><input type='text' name='prodCode$ctr' id='prodCode$ctr' class='txtfieldLabel' value ='$row->ProdCompCode' readonly> </td>
													<td width='40%' height='20' class='borderBR padl5' align='left'><input type='text' name='prodName$ctr' size='50' id='prodName$ctr' class='txtfieldLabel' value ='$row->ProdCompName' readonly> </td>
													<td width='15%' height='20' class='borderBR' align='center'><input type='text' name='startDate$ctr' size='20' id='startDate$ctr' class='txtfieldLabel' value ='$start' readonly><input type='hidden' value='$start'  name='hDStart$ctr'  id='hDStart$ctr'/></td>
													<td width='15%' height='20' class='borderBR' align='center'><input type='text' name='endDate$ctr' size='20' id='endDate$ctr' class='txtfieldLabel' value ='$end' readonly><input type='hidden' value='$end'  name='hDEnd$ctr'  id='hDEnd$ctr'/></td>
													<td width='10%' height='20' class='borderBR' align='center'><input type='text' style='text-align:right' name='txtComponentQty$ctr' id='txtComponentQty$ctr' class='txtfield5' value ='$row->ProdCompQty' > </td>
											</tr>";
											$ctr++;
										}
										$rsProductKitComponents->close();
									}else{
										echo "<tr align='center'><td colspan='6' height='20'>No result found.</td></tr>";
									}
								}else{
									echo "<tr align='center'><td colspan='6' height='20'>No result found.</td></tr>";
								}
								?>
							</table>
						</div>
					</td>
				</tr>
			</table>
       <?php
       if ($_SESSION['ismain'] == 1)
       {
       	?>
	   	<br>
    	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
      	<tr>
        	<td align="center">
				<input name='btnSave' type='submit' class='btn' value='Save' onClick = 'return validateSave();' >
				<?php if ($_SESSION['ismain'] == 1){ ?>
					<input type="button" name="btnremove" class="btn" value="Remove"  onclick="return confirmRemove();"/>
				<?php } ?>
            	<input name="btnCancel" type="submit" class="btn" value="Cancel"/>
        	</td>
      	</tr>
        </table>
      <?php
       }
       ?>
    </form>
	</td>
  </tr>
</table>
	</td>
  </tr>
</table>
   </form>
    </td>
  </tr>
</table>
<br />
<div id="dialog-message" style="display:none;">
	<p></p>
</div>