<?php
	require_once "../../initialize.php";
	include IN_PATH.DS."scSetPromoDetails.php";
?>

<!-- product list -->
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script language="javascript" src="../../js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/jquery-ui-1.8.5.custom.css"/>
<script src="../../js/shortcut.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
var $j = jQuery.noConflict();

$j(document).ready(function(){
	
	$j("[name=IsAnyPromo]").change(function(){
		if($j(this).is(":checked")){
			$j("[name=TotalPrice]").removeAttr("disabled");
		}else{
			$j("[name=TotalPrice]").attr("disabled", "disabled");
			$j("[name=TotalPrice]").val("0.00");
		}
	});

	$j('#rdoBReqt').change(function(){
		if($j(this).val()==2 || $j(this).val()==3){
			$j("#selection_no").removeAttr("disabled","disabled");
		}else{
			$j("#selection_no").attr("disabled","disabled");
		}
	})
        $j("#chkApplyAsDiscount").change(function(){
             if($j(this).is(":checked")){
                  $j(this).attr('checked','checked');
             }else{
                  $j(this).removeAttr('checked','checked');
             }
        })
});
function disableEnterKey(a, e, cnt)
{
     var key;
     var str = a.id;
     var sub = 7 + eval(cnt.toString().length);
     var sub2 = 12 + eval(cnt.toString().length);
     var field = "txtEQty" + cnt;
     var field2 = "txtEProdCode" + cnt;

     if(window.event)
     {
          key = window.event.keyCode; //IE
     }
     else
     {
          key = e.which; //firefox
     }

     if (str.substring(0, sub) == field && key == 13)
     {
    	 addRow2();
    	 return false;
     }
     else if (str.substring(0, sub2) == field2 && key == 13)
     {
     	return false;
     }
}

function getSelectionProductCriteriaList(text, li)
{
	var txt = text.id;
	var ctr = txt.substr(12, txt.length);

	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmSingleLine.hProdID_criteria');
	i = eval('document.frmSingleLine.txtCriteria');
	j = eval('document.frmSingleLine.txtCDescription');

	h.value = tmp_val[0];
	j.value = tmp_val[1];
	i.value = tmp_val[2];
}

function getSelectionProductList2(text, li)
{
	var txt = text.id;
	var ctr = txt.substr(12, txt.length);

	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmSingleLine.hEProdID' + ctr);
	i = eval('document.frmSingleLine.txtEProdCode' + ctr);
	j = eval('document.frmSingleLine.txtEProdDesc' + ctr);
	k = eval('document.frmSingleLine.txtEQty' + ctr);
	l = eval('document.frmSingleLine.hEUnitPrice' + ctr);
//	m = eval('document.frmCreateSet.hEpmgid' + ctr);
	n = eval('document.frmSingleLine.cboEPMG' + ctr);
	o = eval('document.frmSingleLine.cboECriteria' + ctr);

	o.focus();
	//k.focus();
	//k.select();

	h.value = tmp_val[0];
	j.value = tmp_val[1];
	l.value = tmp_val[2];
	i.value = tmp_val[4];
	n.value = tmp_val[3];

	for ( var i = 0; i < n.options.length; i++ )
	{
		if (n.options[i].value == tmp_val[3])
		{
			n.options[i].selected = true;
			//return;
		}
	}

	for ( var i = 0; i < n.options.length; i++)
	{
		if (tmp_val[3] == 1)
		{
			n.remove(1);
			return;
		}

		if (tmp_val[3] == 2)
		{
			n.remove(0);
			return;
		}
	}
}

function confirmDelete(inc)
{
	if (inc == 1)
  	{
  		inc = 2;
  	}
  	else
  	{
	  	inc = 1;
  	}

  	if (confirm('Are you sure you want to delete this promo?') == false)
  		return false;
  	else
  		return true;
}



function selection()
{
	var type = document.frmSingleLine.cboType.value;

  	if(type == 1)
  	{
  		document.frmSingleLine.txtTypeQty.readOnly = true;
	  	document.frmSingleLine.txtTypeQty.value = "";
  	}
  	else
  	{
  		document.frmSingleLine.txtTypeQty.readOnly = false;
  	}
}

function createCell(cell, text, style, align)
{
	var div = document.createElement('div');
  	var txt = document.createTextNode(text);

  	div.setAttribute('id', 'line');
  	div.setAttribute('class', style);
  	div.setAttribute('align', align);
  	div.appendChild(txt);
  	cell.appendChild(div);
}

function confirmAdd()
{
  	 var table = document.getElementById('buyTable');
  	 var rowCount = table.rows.length;

     var ml = document.frmSingleLine;
 	 var range = ml.cboRange;
 	 var selection = ml.hProdID_criteria;
 	 var breqid = 0;

 	//check buy-in requirement
 	for (var i=0; i < ml.rdoBReqt.length; i++)
 	{
 		if (ml.rdoBReqt[i].checked)
 		{
 			if (ml.rdoBReqt[i].value == 2)
 			{
 				var minimum = ml.txtPHMinimum;
 				break;
 			}
 			else
 			{
 				var minimum = ml.txtMinimum;
 				break;
 			}
 		}
 	}

 	if (range.value == 0)
 	{
 		alert ('Range required.');
 		range.focus();
 		return false;
 	}

 	if (selection.value == "")
 	{
 		alert ('Selection required.');
 		selection.focus();
 		return false;
 	}

 	if (Trim(minimum.value) == "")
 	{
 		alert ('Minimum value required.');
 		minimum.focus();
 		return false;
 	}

 	if (!isNumeric(minimum.value))
 	{
 		alert("Invalid numeric format for Minimum value.");
 		minimum.select();
 		minimum.focus();
 		return false;
 	}

 	if (confirm('Are you sure you want to add this Buy-in requirement?') == false)
 		return false;
 	else
 	    var table = document.getElementById('buyTable');
	    var rowCount = table.rows.length;
	    document.frmSingleLine.hbuyCnt.value = rowCount;
 		return true;
}

function confirmSave()
{
		var error_msg = "";
		var error_cnt = 0;
		if($j("#cboType").val()==2){
			if($j("#txtTypeQty").val()==""){
				error_msg += "Selection No. Required \n";
				error_cnt++;
			}
		}

		if(error_cnt == 0){
			if(confirm('Are you sure you want to save this transaction?') == false){
				return false;
			}else{
				return true;
			}
		}else{
			alert(error_msg);
			return false;
		}
}


function CheckInclude()
{
  	var ci = document.frmSingleLine.elements["chkSelect[]"];

  	for(i=0; i< ci.length; i++)
  	{
  		if(ci[i].checked == false)
  		{
  			document.frmSingleLine.chkAll.checked = false;
  		}
  	}

  	if (document.frmSingleLine.elements["chkSelect[]"].value > 1)
  	{
  		if(ci.checked == false)
  		{
  			document.frmSingleLine.chkAll.checked = false;
  		}
  	}
}

function checkAll(bin)
{
  	var elms = document.frmSingleLine.elements;

  	for (var i = 0; i < elms.length; i++)
  	{
  		if (elms[i].name == 'chkSelect[]')
  	  	{
  			elms[i].checked = bin;
  	  	}
  	}
}

function displayRow(ctr)
{
	//  var row = document.getElementById("captionRow" + ctr);
	//  if (row.style.display == '') row.style.display = 'none';
	//  else row.style.display = '';


	var ml = document.frmSingleLine;
	var len = ml.elements.length;

	for (var i = 0; i < len; i++)
	{
		var e = ml.elements[i];
	    if (e.name == "chkSelect[]")
	    {
	        alert(e.value);
	        alert(ctr);
			if(e.value == ctr)
			{
				e.checked = true;
				e.value = ctr - 1;
			}
	    }
	}
	return false;
}

function RemoveInvalidChars(strString)
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

function confirmRemove()
{
	if(confirm("Are you sure want to remove this buyin requirements?")==false)
		return false;
	else
		return true;
}

function confirmRemove1()
{
	if(confirm("Are you sure want to remove this Entitlement requirements?")==false)
		return false;
	else
		return true;
}
</script>

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
    max-height: 150px;
    overflow-x: hidden;
    overflow-y: auto;
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
</style>

<form name="frmSingleLine" method="post" action="promo_setpromoDetails.php?prmsid=<?php echo $_GET['prmsid'];?>&inc=<?php echo $_GET['inc'];?> ">
<?php
if ($errmsg != "") {
	echo "<br>";
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
?>
<br>
<body>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin">
	<input type="hidden" id="hRangeID" name="hRangeID" value="<?php echo $levelid; ?>">
	<input type="hidden" id="hPMGID" name="hPMGID" value="<?php echo $pmg_id; ?>">
	<input type="hidden" id="hPMGCode" name="hPMGCode" value="<?php echo $pmg_code; ?>">
	<input type="hidden" id="hbuyCnt" name="hbuyCnt" value="<?php echo $totcntbuy;?>">
	<input type="hidden" id="hbuyCnt2" name="hbuyCnt2" value="">
	<input type="hidden" id="hEntitlementCnt" name="hEntitlementCnt" value="<?php echo $totcntent;?>">
	<input type="hidden" id="hEntitlementIndex" name="hEntitlementIndex" value="<?php echo $totcntent;?>">
	</td>
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Promo Header</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="50%">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="30%">&nbsp;</td>
					<td width="70%" align="right">&nbsp;</td>
				</tr>
				<tr>
				    <td height="20"><div align="left" class="padl5"><strong>Promo Code :</strong></div></td>
				    <td height="20"><input name="txtCode" type="text" class="txtfieldLabel" id="txtCode" value="<?php if (isset($_POST['txtCode'])) { echo $_POST['txtCode']; }else{echo $promocode;} ?>" size="30" readonly="yes"></td>
			    </tr>
			    <tr>
				    <td height="20"><div align="left" class="padl5"><strong>Promo Description :</strong></div></td>
				    <td height="20"><input name="txtDescription" type="text" class="txtfield" id="txtDescription" value="<?php if (isset($_POST['txtDescription'])) { echo $_POST['txtDescription']; }else{echo $promodesc;} ?>" style="width: 362px;">
					</td>
			    </tr>
				<tr>
				  	<td height="20"><div align="left" class="padl5"><strong>Start Date: </strong></div></td>
				  	<td height="20">
						<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php if (isset($_POST['txtStartDate'])) { $tmpsdate = strtotime($_POST['txtStartDate']); $sdate = date("m/d/Y",$tmpsdate); echo $sdate; } else { echo $today; } ?>">
                                                <i>(e.g. MM/DD/YYYY)</i>
                                        </td>
				</tr>
				<tr>
				  	<td height="20"><div align="left" class="padl5"><strong>End Date: </strong></div></td>
				  	<td height="20">
						<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php if (isset($_POST['txtEndDate'])) { $tmpedate = strtotime($_POST['txtEndDate']); $edate = date("m/d/Y",$tmpedate); echo $edate; } else { echo $end; } ?>">
						<i>(e.g. MM/DD/YYYY)</i>
					</td>
				</tr>
				<tr>
				  	<td height="20"><div align="left" class="padl5"><strong>Purchase Requirement Type: </strong></div></td>
				  	<td height="20">
				<?php if($PurchaseRequirementType == 1){ ?>
						<select name="cboPReqtType" class="txtfield" id="cboPReqtType">
							<option value="1">Single</option>
							<option value="2">Cumulative</option>
						</select>
				<?php }else{ ?>
					<select name="cboPReqtType" class="txtfield" id="cboPReqtType">
							<option value="2">Cumulative</option>
							<option value="1">Single</option>
						</select>
				<?php } ?>

					</td>
				</tr>
				<tr>
				  	<td height="20"><div align="left" class="padl5"><strong>Buy-in Requirement: </strong></div></td>
				  	<td height="20">
					<select name="rdoBReqt" class="txtfield" id="rdoBReqt" style="visibility: visible;" >
					<?php
							$rdoBReqt = array("1"=>"Multitple Buyin Requirement","2"=>"Selection by Quantity","3"=>"Selection by Amount");
							foreach ($rdoBReqt as $key => $value){
								if($ovrlaytype == $key){
									$selected = "selected='selected'";
								}else{
									$selected ="";
								}
								echo '<option value="'.$key.'" '.$selected.'>'.$value.'</option>';
							}
					?>
					</select>
						<strong>Minimum Value: </strong>
						<input type = "text" id="selection_no" name="selection_no" class = "txtfield"
						<?php if($ovrlaytype == 1){ ?> disabled="disabled" <?php } ?> <?php echo "value = '".$overlayQty."'"; ?> style="width:50px;" /> <?php //for selection ?>
					</td>
				</tr>
				<tr>
				  	<td height="20"><div align="left" class="padl5"><strong>Is Regular</strong></div></td>
				  	<td height="20">
						<select name="isRegular"  class="txtfield" id="isRegular" style="visibility: visible">
							<!-- option value="1">No</option>
							<option value="0">Yes</option -->
							<?php
								$selected='';
								$i = array('1'=>'No','0'=>'Yes');
								foreach($i as $key => $value):
									if($OverlayIsRegular == $key){
										$selected="selected='selected'";
									}else{
										$selected="";
									}
									echo "<option value = ".$key." ".$selected.">".$value."</option>";
								endforeach;
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="20">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="25%">&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" valign="top"><div align="right" class="padl5"><strong>Max Availment :</strong></div></td>
				    <td height="20">
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				    	<?php
					    	if($rs_promoAvailment->num_rows)
							{
								while($row = $rs_promoAvailment->fetch_object())
								{
									if (isset($_POST['txtMaxAvail'.$row->GSUTypeID]))
									{
										$txt = $_POST['txtMaxAvail'.$row->GSUTypeID];
									}
									else
									{
										$txt = $row->MaxAvailment;
									}
									echo "<tr>
					    						<td width='25%' height='20'><div align='left' class='padl5'><strong>$row->Name :</strong></div></td>
					    						<td width='75%' height='20'>
					    						<input type='text' id='txtMaxAvail$row->GSUTypeID' class='txtfield' name='txtMaxAvail$row->GSUTypeID' value='$txt' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail$row->GSUTypeID);'></td>

					    				</tr>";
								}
								$rs_promoAvailment->close();
							}
							else
							{
								if($rs_gsutype->num_rows)
								{
									while($row = $rs_gsutype->fetch_object())
									{
										if (isset($_POST['txtMaxAvail'.$row->ID]))
										{
											$txt = $_POST['txtMaxAvail'.$row->ID];
										}
										else
										{
											$txt = "";
										}
										echo "<tr>
						    						<td width='25%' height='20'><div align='left' class='padl5'><strong>$row->Name :</strong></div></td>
						    						<td width='75%' height='20'>
					    						    <input type='text' id='txtMaxAvail$row->ID' class='txtfield' name='txtMaxAvail$row->ID' value='$txt' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail$row->ID);'></td>
						    				</tr>";
									}
									$rs_gsutype->close();
								}
							}
				    	?>
				    	</table>
				    </td>
			    </tr>
			    <tr>
				    <td height="20"><div align="right" class="padl5"><strong>Is Plus Plan :</strong></div></td>
				    <td height="20"><input type="checkbox" name="chkPlusPlan" value="1" <?php if ($prmPplan == 1) { echo "checked"; } ?>></td>
				</tr>
				<tr>
				  	<td height="20"><div align="right" class="padl5"><strong>Brochure Page: </strong></div></td>
				  	<td height="20">
						<input name="bpage" type="text"  class="txtfieldg" id="bpage" size="10" value="<?php echo $fPage; ?>" style = "width: 5%;" >&nbsp; - &nbsp;
						<input name="epage" type="text"  class="txtfieldg" id="epage" size="10" value="<?php echo $ePage; ?>" style = "width: 5%;" >
					</td>
				</tr>
				<tr>
					<td height="20"><div align="right" class="txtpallete"><strong>For New Recruit only :</strong></div></td>
				    <td height="20">
						<select name = "IsNewRecruit" class="txtfield">
						<?php
							$x = array(0=>"No",1=>"Yes");
							$selected="";
							foreach($x as $key => $value){
								if($IsForNewRecruit==$key){
									$selected="selected=selected";
								}else{
									$selected="";
								}
								echo "<option value = ".$key." ".$selected."> ".$value." </option>";
							}
						?>
					</td>
				</tr>
				<tr>
					<td height="20"><div align="right" class="txtpallete"><strong>Is Any-any Promo : </strong></div></td>
				    <td height="20">
						<input type="checkbox" value="1" name="IsAnyPromo" <?=($IsAnyPromo) ? "checked='checked'" : "" ;?>>
					</td>
				</tr>
				<tr>
					<td height="20"><div align="right" class="txtpallete"><strong>Total Price : </strong></div></td>
				    <td height="20">
						<input type="text" value="<?=$TotalPrice?>" name="TotalPrice" class="txtfield" <?=($IsAnyPromo) ? "" : "disabled='disabled'" ;?> style="width:100px; text-align:right;" onkeydown="return RemoveInvalidChars(this);" onkeyup="return RemoveInvalidChars(this);">
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td width="47%" valign="top">
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td>
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td height="20" width="30%">&nbsp;</td>
							<td height="20" width="70%" align="right">&nbsp;</td>
						</tr>

					    <tr>
						    <td height="21"><div align="left" class="padl5"><strong>Range :</strong></div></td>
						    <td height="21">
						    	<select name="cboRange" style="width:150px; height:20px;" class="txtfield" disabled = "true" onChange="document.frmSingleLine.submit();">
		                            <option value="0">[SELECT HERE]</option>
		                          	<?php
		                              	if ($rsprodlevel->num_rows)
		                              	{
		                                  	while ($row = $rsprodlevel->fetch_object())
		                                  	{
		                                  		if (isset($_POST['cboRange']))
		                                  		{
		                                  			if ($_POST['cboRange'] == $row->ID)
		                                  			{
		                                  				$sel = 'selected';
		                                  			}
		                                  			else
		                                  			{
		                                  				$sel = '';
		                                  			}
		                                  		}
		                                      	echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
		                                  	}
		                                  	$rsprodlevel->close();
		                              	}
		                          	?>
								</select>
						    </td>
					    </tr>
					    <tr>
						    <td height="22"><div align="left" class="padl5"><strong>Selection :</strong></div></td>
						    <td height="22">
						    <?php
							echo"
								 <input name='txtCriteria' type='text' class='txtfield' disabled = 'true' id='txtCriteria' style='width: 75px;' value=''/><br />
								 <span id='indicatorCriteria' style='display: none'><img src='../../images/ajax-loader.gif' alt='Working...' /></span>
								 <div id='prod_choices_criteria' class='autocomplete' style='display:none'></div>
								<script type='text/javascript'>
								 //<![CDATA[
								 	var a = eval('document.frmSingleLine.hRangeID');
								 	var u = '../../includes/scProductRangeAjax.php?range=' + a.value;
		                        	var prod_choices_criteria = new Ajax.Autocompleter('txtCriteria', 'prod_choices_criteria', u, {afterUpdateElement : getSelectionProductCriteriaList, indicator: 'indicatorCriteria'});
		                        //]]>
		                        </script>
								<input name='txtCDescription' type='text' class='txtfield' id='txtCDescription' style='width: 250px;' value='' readonly='yes' />
						    	<input name='hProdID_criteria' type='hidden' id='hProdID_criteria' value=''/>
								</div>
							</td>";
							?>
						    </td>
						</tr>
					    <tr>
						    <td height="22"><div align="left" class="padl5"><strong>Criteria :</strong></div></td>
						    <td height="22">
						    	<select name="cboCriteria" class="txtfield" id="cboCriteria" style="width: 150px;" <?php if (isset($_POST['rdoBReqt'])){if($_POST['rdoBReqt'] == 1){?>disabled="yes" <?php }}else if($ovrlaytype == 1){?>disabled="yes"<?php }?>>
									<option value="1">Quantity</option>
									<option value="2">Amount</option>
								</select>
						    </td>
					    </tr>
					    <tr>
						    <td height="22"><div align="left" class="padl5"><strong>Minimum Value :</strong></div></td>
						    <td height="22"><input name="txtMinimum" type="text" value="" class="txtfield" style="width: 150px" <?php if (isset($_POST['rdoBReqt'])){if($_POST['rdoBReqt'] == 1){?>disabled="yes" <?php }}else if($ovrlaytype == 1){?>disabled="yes"<?php }?>></td>
					    </tr>
					    <tr>
						    <td height="22"><div align="left" class="padl5"><strong>Start Date :</strong></div></td>
						    <td height="22">
						    	<input name="txtSetStartDate" type="text" class="txtfield" id="txtSetStartDate" size="20" style="width: 150px" readonly="yes" value="<?php echo $today; ?>" <?php if (isset($_POST['rdoBReqt'])){if($_POST['rdoBReqt'] == 1){?>disabled="yes" <?php }}else if($ovrlaytype == 1){?>disabled="yes"<?php }?>>
                                                        <i>(e.g. MM/DD/YYYY)</i>
						    </td>
					    </tr>
					    <tr>
						    <td height="22"><div align="left" class="padl5"><strong>End Date :</strong></div></td>
						    <td height="22">
						    	<input name="txtSetEndDate" type="text" class="txtfield" id="txtSetEndDate" size="20" style="width: 150px" readonly="yes" value="<?php echo $end; ?>" <?php if (isset($_POST['rdoBReqt'])){if($_POST['rdoBReqt'] == 1){?>disabled="yes" <?php }}else if($ovrlaytype == 1){?>disabled="yes"<?php }?>>
                                                        <i>(e.g. MM/DD/YYYY)</i>
						    </td>
					    </tr>

						<tr>
							<td colspan="2" height="20">&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<br>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right">
				<input name='btnAdd' disabled = "true" type='submit' class='btn' value='Add' onclick='return confirmAdd();'>
			</td>
		</tr>
		</table>
		<br>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"><input type="hidden" id="hID" name="hID" value="<?php echo $promoID;?>"></td>
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>

		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">

		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
									<td width="15%" height="20" class="borderBR"><div align="center">Overlay No.</div></td>
									<td width="25%" height="20" class="borderBR"><div align="left" class="padl5">Selection</div></td>
									<td width="10%" height="20" class="borderBR"><div align="center">Criteria</div></td>
									<td width="10%" height="20" class="borderBR"><div align="right" class="padr5">Minimum</div></td>
									<td width="15%" height="20" class="borderBR"><div align="center">Start Date</div></td>
									<td width="15%" height="20" class="borderBR"><div align="center">End Date</div></td>
									<td width="15%" height="20" class="borderBR"><div align="center">Action</div></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_150">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1" id="buyTable">
				<?php
				if ($rspromobuyin->num_rows)
				{
				$prmBuyStartdate = "";
				$prmBuyEnddate = "";
				$totcntbuy = $rspromobuyin->num_rows;
				$buycnt = $rspromobuyin->num_rows;
				$ctr = 1;
				while($row = $rspromobuyin->fetch_object())
		    	{

					$preqttype = $row->PurchaseRequirementType;
					$range = $row->plid;
					$prodid = $row->prodID;
					if($row->PromoCode != "" || $row->PromoCode != NULL){
						$proddesc = $row->PromoCode;
					}else if ($row->ProdName || $row->ProdName != NULL){
						$proddesc = $row->ProdName;
					}else{
						$proddesc = $row->CollateralType." : (".$row->BrochurePageFrom." - ".$row->BrohurePageTo.")";
					}


					$breqid = 0;
					$pmgCode = $row->pmgCode;
					$ID = $row->PromoBuyinID;
					//	print_r($row);
					if($row->Type == 1){
						$criteria = "Quantity";
						//$minimum = $row->Minimum;
						$minimum = $row->MinQty;
					}
					else{
						$criteria = "Amount";
						//$minimum = number_format($row->Minimum, 2, '.', '');
						$minimum = number_format($row->MinAmt,2);
					}

					//$minimum = number_format($row->Minimum, 2, '.', '');
					$maximum = number_format($row->Maximum, 2, '.', '');

				 	if ($row->StartDate == "00/00/0000")
					{
						$prmBuyStartdate = "&nbsp;";
					}
				 	else
				 	{
						$prmBuyStartdate = $row->StartDate;
				 	}

					if ($row->EndDate == "00/00/0000")
				 	{
				 		$prmBuyEnddate = "&nbsp;";
			     	}
					else
				 	{
			    		$prmBuyEnddate = $row->EndDate;
				 	}
					?>

				<tr>
					<td width="15%" height="20" class="borderBR"><div align="center"><?php echo $ctr; ?></div></td>
					<td width="25%" height="20" class="borderBR"><div align="left" class="padl5"><?php if($range == 1):?>All Product<?php else: echo $proddesc; endif;?></div></td>
					<td width="10%" height="20" class="borderBR"><div align="center"><?php echo $criteria; ?>	</div></td>
					<td width="10%" height="20" class="borderBR"><div align="right" class="padr5"><?php echo $minimum; ?></div></td>
					<td width="15%" height="20" class="borderBR"><div align="center"><?php echo $prmBuyStartdate; ?></div></td>
					<td width="15%" height="20" class="borderBR"><div align="center"><?php echo $prmBuyEnddate; ?></div></td>
					<td width="15%" height="20" class="borderBR"><div align="center">
					<form action="" method="post">
						<input type = 'hidden' name = "BuyinID" value ="<?php echo $ID; ?>">
						<input type = 'submit' value = "Remove" name = "btn_remove" class = "btn" onclick = "return confirmRemove();"></div></td>
					</form>
				</tr>
				<?php $ctr++;
				}
				} ?>
			</tr>


				</table>
				</div>
			</td>
		</tr>
		<tr>
		<td>
		</tr>
		</table>
	</td>
	<td width="1%"></td>
	<td width="47%" valign="top">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td>
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Entitlement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr align="center">
			<td height="25" class="borderBR"><div align="left" class="padl5">
				<strong>Type :</strong>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="cboType" class="txtfield" id="cboType" style="width: 100px;" onChange ="selection();">
					<option value="2" <?php if($entType == 2){ ?> selected="selected" <?php }?>>Selection</option>
					<option value="1" <?php if($entType == 1){ ?> selected="selected" <?php }?>>Set</option>
				</select>
				&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;
			<?php if($entType == 2){
				echo '<input name="txtTypeQty" type="text" class="txtfield" id="txtTypeQty"  value= "'.$entQty.'" style="width: 60px; text-align: right;">';
			}else{
				echo '<input name="txtTypeQty" type="text" class="txtfield" id="txtTypeQty" readonly="readonly" value = "" style="width: 60px; text-align: right;">';

			}?>
                                &nbsp;
                                <strong>Apply as discount
                                   <input type="checkbox" <?php echo $OverlayApplyAsDiscount; ?> id="chkApplyAsDiscount" name="chkApplyAsDiscount" value = 1 />
                                </strong>

			</div></td>
		</tr>
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" >
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="10%" height="20" class="borderBR"><div align="center">&nbsp;</div></td>
							<td width="15%" height="20" class="borderBR"><div align="left" class="padl5">Item Code</div></td>
							<td width="30%" height="20" class="borderBR"><div align="left" class="padl5">Item Description</div></td>
							<td width="10%" height="20" class="borderBR"><div align="center">Criteria</div></td>
							<td width="10%" height="20" class="borderBR"><div align="right" class="padr5">Qty/Price</div></td>
							<td width="10%" height="20" class="borderBR"><div align="right" class="padr5">PMG</div></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_150">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1" id="dynamicEntTable">
				<?php
					if (isset($_GET['prmsid']))
					{
						$ctr = 0;
						$linecnt = 0;

						if ($rspromobuyin_ent->num_rows)
						{
							while($row = $rspromobuyin_ent->fetch_object())
							{
								$ctr += 1;
								//get promoentitlementid
								$rspromentitlement = "rspromentitlement".$ctr;
								$rspromentitlement = $sp->spSelectPromoEntitlementByPromoBuyInID($database, $row->PromoBuyinID);
								if ($rspromentitlement->num_rows)
								{
									$index = 0;
									while($rowEnt = $rspromentitlement->fetch_object())
									{
										$index += 1;
										//get promoentitlementdetails
										$rspromentitlement_details = "rspromentitlement_details".$index;
										//$rspromentitlement_details = $sp->spSelectPromoEntitlementDetailsByPromoEntitlementID($database, $rowEnt->ID);
										$rspromentitlement_details = $database->execute("SELECT
																						 	ped.ID EntitlementID,
																						 	p.ID ProductID,
																						 	p.Code ProdCode,
																						 	p.Name ProdName,
																						 	Quantity,
																						 	EffectivePrice,
																						 	Savings,
																						 	pmg.Code pmgcode,
																						 	IFNULL(pp.UnitPrice, 0.00) UnitPrice,
																						 	ped.pmgid pmgid
																						 FROM `promoentitlementdetails` ped
																						 	INNER JOIN `product` p ON p.ID = ped.ProductID
																						 	LEFT JOIN productpricing pp ON pp.ProductID = p.ID
																						 	LEFT JOIN tpi_pmg pmg ON pmg.ID = ped.pmgid
																						 WHERE ped.PromoEntitlementID = ".$rowEnt->ID."
																						 ORDER BY p.Name");

										if ($rspromentitlement_details->num_rows)
										{
											$rowalt=0;
											$tmpstep = "";
											while($row_det = $rspromentitlement_details->fetch_object())
											{
												$rowalt++;
												$linecnt++;
												($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
												if ($row_det->EffectivePrice > 0)
												{
													$criteria = number_format($row_det->EffectivePrice, 2, '.', '');
													$type = "Price";
												}
												else
												{
													$criteria = number_format($row_det->Quantity, 0, '.', '');
													$type = "Quantity";
												}

												$step_ = "Overlay ".$ctr;
												if($tmpstep !=  $step_)
												{
													$step = "Overlay ".$ctr;
												}
												else
												{
													$step = "&nbsp;";
												}
												?>
												<tr align="center" class="<?php echo $class; ?>">
														<td width="11%" height="20" class="borderBR">
														 <form action = "" method = "post">
														 <input name="btnRemove1" type="submit" class="btn" value="Remove" onclick="return confirmRemove1();">
														 <input name="EID" type="hidden" value="<?php echo $row_det->EntitlementID; ?>" onclick="return confirmRemove1();">
														 </form>
														</td>
														 <input name="entitID<?php echo $linecnt; ?>" type="hidden" value="<?php echo $row_det->EntitlementID; ?>">
															<?php
																echo "<td width='15%' height='20' class='borderBR'><div align='left' class='padl5'>
																<input name='txtEProdCode$linecnt' type='text' class='txtfield'  id='txtEProdCode$linecnt' style='width: 80px;' value='$row_det->ProdCode' onKeyPress='return disableEnterKey(this, event, $linecnt)'/>
																<span id='indicatorE$linecnt' style='display: none'><img src='../../images/ajax-loader.gif' alt='Working...' /></span>
																<div id='prod_choicesE$linecnt' class='autocomplete' style='display:none'></div>
																<script type='text/javascript'>
																	 //<![CDATA[
											                        	var prod_choicesE1 = new Ajax.Autocompleter('txtEProdCode$linecnt', 'prod_choicesE$linecnt', '../../includes/scProductListEntitlementAjax.php?index=$linecnt', {afterUpdateElement : getSelectionProductList2, indicator: 'indicatorE$linecnt'});
											                        //]]>
																</script>
																<input name='hEProdID$linecnt' type='hidden' id='hEProdID$linecnt' value='$row_det->ProductID' />
																<input name='hEUnitPrice$linecnt' type='hidden' id='hEUnitPrice$linecnt' value='$row_det->UnitPrice'/>

															</div></td>";
															 ?>

														<td width="30%" height="20" class="borderBR"><div align="left" class="padl5"><input name="txtEProdDesc<?php echo $linecnt; ?>" type="text" class="txtfield" id="txtEProdDesc<?php echo $linecnt; ?>" style="width: 220px;" readonly="yes"  value="<?php echo $row_det->ProdName;?>"/></div></td>
														<td width="10%" height="20" class="borderBR"><div align="center">
															<select name="cboECriteria<?php echo $linecnt; ?>" class="txtfield" id="cboECriteria<?php echo $linecnt; ?>" style="width: 80px;">
																<option value="2" <?php if($type == "Price"){?> selected="selected"<?php }?>>Price</option>
																<option value="1" <?php if($type == "Quantity"){?> selected="selected"<?php }?>>Quantity</option>
															</select>
														</div></td>
														<td width="10%" height="20" class="borderBR"><div align="center"><input name="txtEQty<?php echo $linecnt; ?>" type="text" class="txtfield" id="txtEQty<?php echo $linecnt; ?>"  value="<?php echo $criteria; ?>" style="width: 50px; text-align:right" onBlur='addRow2();return false;' onKeyPress='return disableEnterKey(this, event, <?php echo $linecnt; ?>)'/></div></td>
														<td width="10%" height="20" class="borderBR"><div align="center">
															<select name="cboEPMG<?php echo $linecnt; ?>" class="txtfield" id="cboEPMG<?php echo $linecnt; ?>" style="width: 80px;">
																<?php

																$rs_pmg = $sp->spSelectPMG($database);
																	if($rs_pmg->num_rows)
																	{

																		while($row = $rs_pmg->fetch_object())
																		{
																			($row_det->pmgid == $row->ID) ? $sel = "selected" : $sel = "";
																			//if (($row_det->pmgid == 1 &&  $row->ID != 2) || ($row_det->pmgid == 2 &&  $row->ID != 1))
																			//{
																				echo "<option value='$row->ID'$sel>$row->Code</option>";
																			//}
																		}
																		$rs_pmg->close();
																	}
																?>
															</select>
														</div></td>
												</tr>
												<?php

												$tmpstep = $step_;
											}
											$rspromentitlement_details->close();
										}
										else
										{
											echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";
										}
									}
									$rspromentitlement->close();
								}
							}
							$rspromobuyin_ent->close();
						}
						else
						{
							echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";
						}
					}
					else
					{
						echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";
					}
				?>
				<?php $linecnt++; ?>
				</table>
				</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<br>
<table width="100%" align="left"  border="0" cellpadding="0" cellspacing="0">
			<tr>

				<td align="center">
						<input type="hidden" id="hInc" name="hInc" value="<?php echo $inc;?>">
						<?php
 						//if ($_SESSION['ismain'] == 1)
 						//{
 							//if (($today != $today2) && (($today > $today2) || ($end < $today2)))
 							//{
							if(isset($_GET['link_branch'])){
								//
							}else{
 								echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onclick='return confirmDelete(hInc.value);'>";
								echo "<input name='btnSave' type='submit' class='btn' value='Update' onclick='return confirmSave();'>";
							}
 							//}
 						//}
 						?>
				</td>
			</tr>
			</table>
</form>
</body>
<br>
<script type="text/javascript">
    function DatePicker(field){
        $j(field).datepicker({
            changeMonth :   true,
            changeYear  :   true
        });
    }

    DatePicker('[name=txtStartDate]');
    DatePicker('[name=txtEndDate]');
    DatePicker('[name=txtSetStartDate]');
    DatePicker('[name=txtSetEndDate]');
</script>