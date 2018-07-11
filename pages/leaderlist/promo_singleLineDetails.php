<?php

	require_once "../../initialize.php";
	include IN_PATH.DS."scSingleLinePromoDet.php";
?>


<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script language="javascript" src="../../js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/jquery-ui-1.8.5.custom.css" />

<script type="text/javascript">

var $ = jQuery.noConflict();
var urlpage = "leaderlist_call_ajax/ajax_single_line.php";

$(function(){
	
	if($('.promolist').get(0).scrollHeight > $('.promolist').height()){
		$('.trheader').find('td:last-child').css("width", "137px");
	}else{
		$('.trheader').find('td:last-child').css("width", "120px");
	}
	
	$('#txtStartDate').datepicker({
		maxDate		:	new Date($('#txtEndDate').val()),
		onClose		:	function(selectedDate){
			$( "#txtEndDate" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	
	$('#txtEndDate').datepicker({
		minDate		:	new Date($('#txtStartDate').val()),
		onClose		:	function(selectedDate){
			$( "#txtStartDate" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
	
	$('[name=btnAddItem]').click(function(){
		var btnfunction = {};		
		var html = "";
		
		html += "<div style='width:300px; text-align:center;'><br />" +
					"<b>Search Product &nbsp;&nbsp;:&nbsp;&nbsp;&nbsp;&nbsp; </b>" +
					"<input type='text' name='SearchItem' class='txtfield' onkeydown='return GetProduct(this);'>" +
					"<input type='hidden' value='0' name='SearchItemID'>" +
				"</div>";
		
		btnfunction['Add'] = function(){
			AddProduct();
		}
		
		btnfunction['Close'] = function(){
			$('.dialogmessage').dialog("close");
		}
		
		popupHTML(html, btnfunction);
		
	});
	
});

function AddProduct(){
	
	var error = 0;
	
	if($('[name=SearchItemID]').val() == 0 || $('[name=SearchItem]').val().trim() == ""){
		$('[name=SearchItem]').val('');
		alert("Please select product to add.");
		return false;
	}
	
	$('.ProductID').each(function(){
		if($(this).val() == $('[name=SearchItemID]').val()){
			error++;
			return false;
		}
	});
	
	if(error > 0){
		$('[name=SearchItem]').val('');
		alert("Item already exist.");
		return false;
	}
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : 'AddProduct', ProductID : $('[name=SearchItemID]').val(), PromoID : $('[name=PromoID]').val(), TotalRow : $('.ProductID').length},
		success	:	function(data){
			if($('.ProductID').length == 0){
				$('.trlist').remove();
			}
			
			$('.promolist table').append(data);
			$('[name=hBuyInCnt]').val($('.ProductID').length);
			$('.dialogmessage').dialog("close");
			
			if($('.promolist').get(0).scrollHeight > $('.promolist').height()){
				$('.trheader').find('td:last-child').css("width", "137px");
			}else{
				$('.trheader').find('td:last-child').css("width", "120px");
			}
			
		}
	});	
	
}

function GetProduct(field){
	$(field).autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	urlpage,
				data	:	{action : 'GetItems', Searched : request.term.trim()},
				success	:	function(data){
					response($.map(data, function(item){
						return{
							label	:	item.Label,
							value	:	item.Value,
							ID		:	item.ID
						}
					}));
				}
			});
		},
		select	:	function(event, ui){
			$('[name=SearchItemID]').val(ui.item.ID);
		}
	});
}

function confirmDelete() {
    if (confirm('Are you sure you want to delete this promo?') == false)
        return false;
    else
        return true;
        //opener.location.href = '../../index.php?pageid=60&msg=Successfully deleted promo.';
        //window.close();
}

function confirmUpdate() {
	
	var error = 0;
	
	if($('[name=txtDescription]').val().trim() == ""){
		$('[name=txtDescription]').focus();
		alert("Please insert Promo Description.");
		return false;
	}
	
	if($('[name=bpage]').val() > $('[name=epage]').val()){
		$('[name=bpage]').focus();
		alert("Start brochure page should not greater than End brochure page.");
		return false;
	}
	
	$('.trlist').find('select').each(function(){
		if($(this).val() == 0){
			error++;
			return false;
		}
	});
	
	if(error > 0){
		alert("Please select Entitlement PMG.");
		return false;
	}
	
	if(confirm("Are you sure want to update this promo?")==false){
		return false;
	}else{
		return true;
	}
	
}

function ValidateSpecialCharacters(field){
	
	str = /^[a-zA-Z0-9-]*$/;
	
	if(str.test($(field).val()) == false){
		$(field).val($(field).val().replace(/[^a-z0-9-\s]/gi, ''));
	}
	
}

function ValidateInput(field){
	
	if(isNaN($(field).val())){
		$(field).val(0);
	}
	
	if($(field).val() > 999999){
		alert("Insert value should not be greater than 1,000,000.");
		$(field).val(0);
		$(field).select();
	}
	
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

function RemoveItem(field){
	$(field).closest('tr').remove();
	
	if($('.promolist table').find('.trlist').length == 0){
		$('.promolist table').append("<tr class='trlist'><td colspan='9' align='center'>No result found.</td></tr>");
	}
	
	if($('.promolist').get(0).scrollHeight > $('.promolist').height()){
		$('.trheader').find('td:last-child').css("width", "137px");
	}else{
		$('.trheader').find('td:last-child').css("width", "120px");
	}
	
	$('[name=hBuyInCnt]').val($('.ProductID').length);
}

function popupHTML(message, btnfunction){
	$('.dialogmessage').html(message);
	$('.dialogmessage').dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
        title: 'DSS Message',
        buttons:btnfunction
	});
	$('.dialogmessage').dialog("open");
}

function NumberOnly(field, value){	
	if(isNaN($(field).val())){
		$(field).val(value);
	}
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

.fieldlabel {
	font-weight:bold;
	text-align:right;
	width:25%;
}

.separator{
	font-weight:bold;
	text-align:center;
	width:5%;
}

.trlist td{border-top: 1px solid #FFA3E0; padding:5px; border-right: 1px solid #FFA3E0;}
.trheader td{padding:5px; border-right: 1px solid #FFA3E0; background:#ffdef0; text-align:center; font-weight:bold;}

</style>

<body>
</body>

<form name="frmSingleLine" method="post" action="promo_singleLineDetails.php?prmsid=<?php echo $_GET['prmsid'];?>" >
<br />
<table cellspacing="1" cellpadding="0" width="95%" border="0" align="center">
	<tbody>
		<tr>
			<td width="70%">
				<a class="txtgreenbold13">Single Line Promo</a>
			</td>
		</tr>
	</tbody>
</table>

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
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin">
	<input type="hidden" id="hBuyInCnt" name="hBuyInCnt" value="<?php echo $promodetailsquery->num_rows;?>">
	<input type="hidden" id="hBuyInCntSaving" name="hBuyInCntSaving" value="<?php echo $totcntbuy;?>">
	<input type="hidden" id="hBuyInIndex" name="hBuyInIndex" value="<?php echo $totcntbuy;?>">
	<input type="hidden" id="hEntitlementCnt" name="hEntitlementCnt" value="<?php echo $totcntent;?>">
	<input type="hidden" id="hEntitlementIndex" name="hEntitlementIndex" value="<?php echo $totcntent;?>">
	<input type="hidden" id="hPMGID" name="hPMGID" value="<?php echo $pmg_id; ?>">
	<input type="hidden" id="hPMGCode" name="hPMGCode" value="<?php echo $pmg_code; ?>">
	</td>
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Promo Header</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<input type="hidden" id="hID" name="hID" value="<?php echo $prmid;?>">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td valign="top">
				<table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2">
					
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="fieldlabel">Promo Code</td>
						<td class="separator">:</td>
						<td>
							<input name="txtCode" type="text" class="txtredbold txtfieldLabel" id="txtCode" value="<?php echo $prmcode;?>" size="30" readonly="yes">
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Promo Description</td>
						<td class="separator">:</td>
						<td>
							<input name="txtDescription" type="text" class="txtfield" id="txtDescription" value="<?php echo $prmdesc;?>" style="width: 362px;" maxlength="60" onkeyup="return ValidateSpecialCharacters(this);" onkeydown="return ValidateSpecialCharacters(this);">
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Start Date</td>
						<td class="separator">:</td>
						<td>
							<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $prmsdate; ?>">
							<i>(e.g. MM/DD/YYYY)</i>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">End Date</td>
						<td class="separator">:</td>
						<td>
							<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $prmedate; ?>">
							<i>(e.g. MM/DD/YYYY)</i>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">Brochure Page</td>
						<td class="separator">:</td>
						<td>
							<input name="bpage" type="text" class="txtfieldg" id="bpage" size="10" value="<?php echo $PageFrom; ?>" style="width: 5%;" onkeydown="return NumberOnly(this,<?=$PageFrom?>);" onkeyup="return NumberOnly(this,<?=$PageFrom?>);">
							&nbsp; - &nbsp;
							<input name="epage" type="text" class="txtfieldg" id="epage" size="10" value="<?php echo $PageTo; ?>" style="width: 5%;" onkeydown="return NumberOnly(this,<?=$PageTo;?>);" onkeyup="return NumberOnly(this,<?=$PageTo;?>);">
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td class="fieldlabel">Max Availment</td>
						<td class="separator">:</td>
						<td></td>
					</tr>
					<tr>
						<td class="fieldlabel"></td>
						<td class="separator"></td>
						<td>
							<?php
								if($rs_promoAvailment->num_rows){
									while($row = $rs_promoAvailment->fetch_object()){
										$txt = $row->MaxAvailment;
										echo "<div style='padding:2px;'>
												<strong style='display:block;width:80px; float:left; text-align:right;'>$row->Name</strong>
												<span style='width:30px; display:block; float:left; text-align:center;'><b>:<b/></span>
												<input type='text' id='txtMaxAvail$row->GSUTypeID' class='txtfield' name='txtMaxAvail$row->GSUTypeID' value='$txt' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail$row->GSUTypeID);' />
											</div>";
									}
									$rs_promoAvailment->close();
								}else{
									if($rs_gsutype->num_rows){
										while($row = $rs_gsutype->fetch_object()){

											echo "<div style='padding:2px;'>
													<strong style='display:block;width:80px; float:left; text-align:right;'>$row->Name</strong>
													<span style='width:30px; display:block; float:left; text-align:center;'><b>:<b/></span>
													<input type='text' id='txtMaxAvail$row->ID' class='txtfield' name='txtMaxAvail$row->ID' value='' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail$row->ID);'>
												</div>";
											}
										$rs_gsutype->close();
									}
								}
							?>
						</td>
					</tr>
					<tr>
						<td class="fieldlabel">For New Recuit Only</td>
						<td class="separator">:</td>
						<td>
							<select name = "IsNewRecruit" class="txtfield">
								<?php
									$x = array(0=>"No",1=>"Yes");
									$selected="";
									foreach($x as $key => $value){
										$selected = ($key == $IsForNewRecruit) ? "selected='selected'":"";
										echo "<option value = ".$key." ".$selected."> ".$value." </option>";
									}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>			  	
				</table>
			</td>

		</tr>
		</table>
	</td>
</tr>
</table>
<br>

<div style='width:95%; margin:auto;'>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td>
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in and Entitlement Requirement</div></td>
			<td class="tabmin3"></td>
		</tr>
	</table>
	<div class="bordersolo" style="border-top:none;">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr class='trheader'>
				<td width="80px">Action</td>
				<td width="100px">Item Code</td>
				<td>Item Description</td>
				<td width="120px">Minimum Qty</td>
				<td width="120px">Buy-in PMG</td>
				<td width="120px">Criteria</td>
				<td width="120px">CSP</td>
				<td width="120px">Special Price</td>
				<td width="120px">Entitlement PMG</td>
			</tr>
		</table>
		<div style="max-height:350px; overflow:auto;" class="promolist">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">		
				<?php 
					$counter = 1;
					if($promodetailsquery->num_rows){
						while($res = $promodetailsquery->fetch_object()){
				?>
				<tr class="trlist">
					<td align="center" width="80px">
						<input type="button" class="btn" name="btnRemove" value="Remove" onclick="return RemoveItem(this);">
					</td>
					<td width="100px" align="center">
						<span><?=$res->ProductCode;?></span>						
						<input type="hidden" value="<?=$res->ProductID?>" name="ProductID<?=$counter?>" class="ProductID">
						<input type="hidden" value="<?=$res->PromoBuyinID?>" name="PromoBuyinID<?=$counter?>">
						<input type="hidden" value="<?=$res->EntitlementID?>" name="PromoEntitlementID<?=$counter?>">
						<input type="hidden" value="<?=$res->UnitPrice?>" name="UnitPrice<?=$counter?>">
						<input type="hidden" value="<?=$res->EntitlementDetailsID?>" name="EntitlementDetailsID<?=$counter?>">
						<input type="hidden" value="<?=$res->BuyinMinimumQuantity?>" name="BuyinMinimumQuantity<?=$counter?>">
					</td>
					<td>
						<span><?=$res->ProductName?></span>
					</td>
					<td width="120px" align="center">
						<span><?=$res->BuyinMinimumQuantity?></span>
					</td>
					<td align="center" width="120px">
						<span><?=$res->BuyinPMGCode?></span>
					</td>
					<td align="center" width="120px">
						<span>Price</span>
					</td>
					<td align="right" width="120px">
						<span><?=number_format($res->UnitPrice, 2)?></span>
					</td>
					<td width="120px">
						<input type="text" class="txtfield" style="width:100%; text-align:right;" onkeydown="return ValidateInput(this);" onkeyup="return ValidateInput(this);" value="<?=number_format($res->EntitlementPrice, 2, '.', '')?>" name="SpecialPrice<?=$counter?>">
					</td>
					<td width="120px">
						<select name="EntitlementPMGID<?=$counter?>" class="txtfield" style="width:100%;">
							<option value="0">Select</option>
							<?php 
								$pmgquery = $database->execute("SELECT * FROM tpi_pmg WHERE ID IN (1,2,3)");
								if($pmgquery->num_rows){
									while($pmg = $pmgquery->fetch_object()){
										$sel = ($pmg->ID == $res->EntitlementPMGID) ? "selected='selected'" : "";
										echo "<option value='".$pmg->ID."' $sel>".$pmg->Code."</option>";
									}
								}
							?>
						</select>
					</td>
				</tr>
				<?php 	
						$counter++;
						}
					}else{
				?>
					<tr class="trlist">
						<td align="center" colspan="7">No result found.</td>
					</tr>
				<?php }?>
			</table>
		</div>
	</div>
</div>

<br />

<table width="100%" align="left"  border="0" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
		<?php
			if(isset($_GET['link_branch'])){
				
			}else{
				echo "<input name='btnAddItem' type='button' class='btn' value='Add Item'>";
				echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onclick='return confirmDelete();'>";
				echo "<input name='btnSave'   type='submit' class='btn' value='Update' onclick='return confirmUpdate();'>";
			}
		?>
		<input type="hidden" value="<?=$_GET['prmsid']?>" name="PromoID">
	</td>
</tr>
</table>

<div class="dialogmessage"></div>
