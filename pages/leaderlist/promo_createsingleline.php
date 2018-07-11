<?php  include IN_PATH . DS . "scCreateSingleLinePromo.php"; ?>

<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css"/>
<script language="javascript" src="js/jquery-1.9.1.min.js"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<!--<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>-->

<script language="javascript" src="js/jxCreateSingleLinePromo.js"  type="text/javascript"></script>

<style type="text/css">
div.autocomplete {
  position:absolute;
  width:300px;
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px;} 
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;  
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px solid #888;
  padding:2px;
  /*height:20px;*/
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
  cursor:pointer;
}

.trheader td{padding:5px; border: 1px solid #FFA3E0; font-weight:bold; text-align:center;}
.trlist td{padding:3px; border: 1px solid #FFA3E0; padding-right:5px;}
</style>

<body>
<?php //<form name="frmCreateSingleLine" method="post" action="index.php?pageid=61" onsubmit="return validateForm()" > ?>
<form name="frmCreateSingleLine" method="post" action="index.php?pageid=61" style="min-height:600px;">
<input type="hidden" id="hBuyInCnt" name="hBuyInCnt" value="0">
<input type="hidden" id="hBuyInIndex" name="hBuyInIndex" value="1">
<input type="hidden" id="hEntitlementCnt" name="hEntitlementCnt" value="0">
<input type="hidden" id="hEntIndex" name="hEntIndex" value="1">
<input type="hidden" id="hIndex" name="hIndex" value="1">
<input type="hidden" id="hPMGID" name="hPMGID" value="<?php echo $pmg_id; ?>">
<input type="hidden" id="hPMGCode" name="hPMGCode" value="<?php echo $pmg_code; ?>">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="topnav">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		    <td width="70%" class="txtgreenbold13" align="left"></td>
			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=80">Leader List Main</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%">&nbsp;<a class="txtgreenbold13">Create Single Line Promo</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php
if ($errmsg != "") {
?>
<br>
<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
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
?>
<br>
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td> 
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Promo Header</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
<!-- FORM HEADER -->
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="20%">&nbsp;</td>
					<td width="5%"></td>
					<td>&nbsp;</td>					
				</tr>			
				<tr>
				    <td height="20" align="right"><strong>Promo Code</strong></td>
					<td align="center">:</td>
				    <td height="20"><input type="text" class="txtfieldg" value="" name="txtCode" onkeydown = "return CheckPromo(event);" id="txtCode"></td>
			    </tr>
			    <tr>
				    <td height="20" align="right"><strong>Promo Description</strong></td>
					<td align="center">:</td>
				    <td width="20%" height="20"><input name="txtDescription" type="text" class="txtfieldg" onkeydown = "return NextField(event,'txtStartDate');"  disabled = "true" id="txtDescription" value="" size="30" style="width: 362px;" maxlength="60">
					</td>
			    </tr>
				<tr>
				  	<td height="20" align="right"><strong>Start Date</strong></td>
					<td align="center">:</td>
				  	<td height="20">
						<input name="txtStartDate" type="text" class="txtfieldg" id="txtStartDate" onkeydown = "return NextField(event,'txtEndDate');" size="20" value="<?php echo $today; ?>" disabled = "true">
					</td>
				</tr>
				<tr>
				  	<td height="20" align="right"><strong>End Date</strong></td>
					<td align="center">:</td>
				  	<td height="20">
						<input name="txtEndDate" type="text" class="txtfieldg" id="txtEndDate"  onkeydown = "return NextField(event,'bpage');" size="20"value="<?php echo $end; ?>" disabled = "true">
					</td>
				</tr>	
				<tr>
				  	<td height="20" align="right"><strong>Brochure Page</strong></td>
					<td align="center">:</td>
				  	<td height="20">
						<input name="bpage" type="text"  onkeyup="javascript:RemoveInvalidChars(bpage);" onkeydown = "return NextField(event,'epage');" value = "0" class="txtfieldg" id="bpage" size="10" value="" style = "width: 5%; text-align:center;" disabled = "true">
						&nbsp; - &nbsp;
						<input name="epage" type="text"  onkeyup="javascript:RemoveInvalidChars(epage);" onkeydown = "return NextField(event,'txtMaxAvail1');" value = "0" class="txtfieldg" id="epage" size="10" value="" style = "width: 5%; text-align:center;" disabled = "true">
					</td>
				</tr>
				<tr>
					<td colspan="3" height="20">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="15%">&nbsp;</td>
					<td width="5%"></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" align="right"><strong>Max Availment</strong></td>
					<td align="center">:</td>
					<td></td>
				</tr>
				<tr>
				    <td height="20" colspan=3>
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
							<tr>
								<td width="20%"></td>
								<td width="5%"></td>
								<td></td>	
							</tr>
							<tr>
								<td height='20' align="right"><strong>NonGSU</strong></td>
								<td align="center">:</td>
								<td height='20'><input type='text' id = 'txtMaxAvail1' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail2");' class='txtfield' name='txtMaxAvail1' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail1);'></td>
							</tr>
							<tr>
								<td height='20' align="right"><strong>Direct GSU</strong></td>
								<td align="center">:</td>
								<td height='20'><input type='text' id = 'txtMaxAvail2' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail3");' class='txtfield' name='txtMaxAvail2' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail2);'></td>
							</tr>
							<tr>
								<td height='20' align="right"><strong>Indirect GSU</strong></td>
								<td align="center">:</td>
								<td height='20'><input type='text' id = 'txtMaxAvail3' disabled = 'true' onkeydown = 'return NextField(event,"txtMaxAvail3");' class='txtfield' name='txtMaxAvail3' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail3);'></td>
							</tr>
				    	</table>
				    </td>
			    </tr>
				
				<!--<tr>
				    <td height="20" align="right"><strong>Is Plus Plan</strong></td>
					<td align="center">:</td>
				    <td height="20"><input type="checkbox" name="chkPlusPlan" value="1"></td>
				</tr> -->
				
				<input type="hidden" name="chkPlusPlan" value="1">
				
				<tr>
				    <td height="20" align="right"><strong>For New Recruit Only</strong></td>
					<td align="center">:</td>
				    <td height="20">
						<select name = "IsNewRecruit" class="txtfield">
							<option value = 0> No </option>
							<option Value = 1> Yes </option>
						</select>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<br />
	</td>
</tr>
</table>
<br />
<!-- END FORM HEADER -->
<table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
<!-- DYNAMIC BUYIN REQUIREMENT TABLE START HERE -->
	<td width= "100%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement and Entitlement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">		
		<tr>
			<td>
				<div style="max-height: 250px; overflow:auto;">
					<table width="100%"  border="0" cellpadding="0" cellspacing="0" id="dynamicTable" class="bgFFFFFF">
						<tr align="center" style="background:#FFDEF0;" class="trheader">
							<td width="3%">Action</td>
							<td width="5%">Line No.</td>
							<td width="8%">Item Code</td>
							<td width="30%">Item Description</td>			
							<td width="7%">Buyin Criteria</td>
							<td width="10%">Minimum Quantity</td>			
							<td width="10%">Entitlement Criteria</td>
							<td width="10%">Special Price</td>
							<td width="5%">Entitlement PMG</td>
						</tr>
						<tr align="center" class="trlist">
							<td>
								<!--<input name="btnRemove" type="button" class="btn" value="Remove" onclick="return deleteRow(this);">-->
								<input name="btnRemove1" type="button" class="btn" value="Remove" onclick="return false;">
							</td>
							<td>1</td>
							<td>
								<input name="txtProdCode1" type="text" class="txtfieldg" id="txtProdCode1" style="width: 100%;" onkeypress="return selectItemCode(this)" value="" disabled = "true" />
								<input name="hProdID1" type="hidden" id="hProdID1" value="" />
							</td>
							<td><input name="txtProdDesc1" type="text" class="txtfield" id="txtProdDesc1" style="width: 100%;" readonly="yes" /></td>			
							<td>
								<select name="cboCriteria1" class="txtfield" id="cboCriteria1" disabled = "true" style="width: 100%;" >
									<option value="2">Amount</option>
									<option value="1" selected="selected">Quantity</option>
								</select>
							</td>
							<td><input name="txtQty1" disabled = "true"  type="text" class="txtfield" id="txtQty1"  value="1" style="width: 100%; text-align:right" /></td>
							<td>
								<select name="cboECriteria1"  class="txtfield" id="cboECriteria1" style="width: 100%;">
									<option value="2" selected="selected">Price</option>
								</select>
							</td>
							<td><input name="txtEQty1" type="text" class="txtfield" id="txtEQty1"  onkeypress='return addRow(this, event);' value="1" style="width: 100%; text-align:right" /></td>			
							<td>
								<select name="txtbPmg1" id = "txtbPmg1" class = "txtfield" style="width: 100%">
									<option value="1">CFT</option>
									<option value="2">NCFT</option>
									<option value="3">CPI</option>
								</select>
							</td>			
						</tr>
					</table>
				</div>
			</td>
		</tr>
		</table>
	</td>
<!-- DYNAMIC BUYIN REQUIREMENT TABLE END HERE -->
	<td width= "1%">&nbsp;</td>
<!-- DYNAMIC ENTITLEMENT TABLE START HERE -->
	
<!-- DYNAMIC ENTITLEMENT TABLE END HERE -->
</tr>
</table>
<br>
<table width="98%" align="left"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
		<input name='btnSaves' type='submit' class='btn' value='Save' id = 'savebtn' disabled = "true"  onclick='return confirmSave();'>
		<input name='btnCancel' type='submit' class='btn' value='Cancel' onclick='return confirmCancel();'>
	</td>			
</tr>
</table>
<br>
</form>
<br>