<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script language="javascript" src="../../js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/jquery-ui-1.8.5.custom.css" />

<?php
   	require_once "../../initialize.php";
	include IN_PATH.DS."scPromo_popup.php";
?>

<script language="javascript" type="text/javascript">
var $ = jQuery.noConflict();
var urlpage = "leaderlist_call_ajax/ajaxMultiline.php";

$(function(){
	
	if($('.promolist').get(0).scrollHeight > $('.promolist').height()){
		$('.trheader').find('td:last-child').css("width", "137px");
	}else{
		$('.trheader').find('td:last-child').css("width", "120px");
	}
	
	$('[name=txtStartDate]').datepicker({
		maxDate	:	new Date($('[name=txtEndDate]').val()),
		onClose	:	function(SelectedDate){
			$('[name=txtEndDate]').datepicker('option', 'minDate', SelectedDate);
		},
		changeMonth : true,
		changeYear	:	true
	});
	
	$('[name=txtEndDate]').datepicker({
		minDate	:	new Date($('[name=txtStartDate]').val()),
		onClose	:	function(SelectedDate){
			$('[name=txtStartDate]').datepicker('option', 'maxDate', SelectedDate);
		},
		changeMonth : true,
		changeYear	:	true
	});
	
	
	$('[name=btnAddItem]').click(function(){
	
		var message = "";
		var btnfunction = {};
		
		btnfunction['Add'] = function(){
			AddProduct();
		}
		
		btnfunction['Close'] = function(){
			$('.dialogmessage').dialog("close");
		}
		
		message = "<div style='width:300px; padding:10px; text-align:center;'>" +
					"<b>Search Product &nbsp;&nbsp;:&nbsp;&nbsp; </b>" +
					"<input type='text' class='txtfield' value='' name='SearchProduct' onkeydown='return GetProduct(this);'>" +
					"<input type='hidden' value='0' name='SearchProductID'>" +
				"</div>";
		
		popupHTML(message, btnfunction);
		
	});
	
});

function GetProduct(field){
	$(field).autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	urlpage,
				data	:	{action : 'GetProduct', SearchProduct : request.term},
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
			$('[name=SearchProductID]').val(ui.item.ID);
		}
	});
}

function AddProduct(){
	
	if($('[name=SearchProduct]').val().trim() == "" || $('[name=SearchProductID]').val() == 0){
		alert("Please select product to add.");
		return false;
	}
	
	var error = 0;
	
	if($('[name=SearchProductID]').val() == 0 || $('[name=SearchProduct]').val().trim() == ""){
		$('[name=SearchProduct]').val('');
		alert("Please select product to add.");
		return false;
	}
	
	$('.ProductID').each(function(){
		if($(this).val() == $('[name=SearchProductID]').val()){
			error++;
			return false;
		}
	});
	
	if(error > 0){
		$('[name=SearchProduct]').val('');
		alert("Item already exist.");
		return false;
	}
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : 'AddProduct', ProductID : $('[name=SearchProductID]').val(), PromoID : $('[name=hID]').val(), TotalRow : $('.ProductID').length},
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

function ValidateField(field){
	
	if(isNaN($(field).val())){
		$(field).val(0);
	}
	
	if($(field).val() > 999999){		
		alert("Inserted value should not be greater than or equal to 1,000,000.00");
		$(field).val(0);
		$(field).select();
	}
	
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
	$('.dialogmessage').dialog('open');
	
}


function confirmDelete()
{
  		if (confirm('Are you sure you want to delete this promo?') == false)
    		return false;
    	else
    		return true;
}

function confirmUpdate(){
  	var error = 0;
	
	if($('[name=txtDescription]').val().trim() == ""){
		$('[name=txtDescription]').focus();
		alert("Please insert Promo Description.");
		return false;
	}
	
	if(eval($('[name=bpage]').val()) > eval($('[name=epage]').val())){
		$('[name=bpage]').focus();
		alert("Start brochure page should not greater than End brochure page.");
		return false;
	}
	
	$('.trlist').find('td:nth-child(8) > input').each(function(){
		if(isNaN($(this).val()) || $(this).val() == ""){
			error++;
			errormessage = "Please insert Special Price.";
			return false;
		}
	});
	
	$('.trlist').find('select').each(function(){
		if($(this).val() == 0){
			error++;
			errormessage = "Please select Entitlement PMG.";
			return false;
		}
	});
	
	if(error > 0){
		alert(errormessage);
		return false;
	}
	
	if(confirm("Are you sure want to update this promo?")==false){
		return false;
	}else{
		return true;
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
  
function NumbersOnly(field, value){
	if(isNaN($(field).val())){
		$(field).val(value);
	}
}

function ValidateSpecialCharacters(field){
	
	str = /^[a-zA-Z0-9-]*$/;
	
	if(str.test($(field).val()) == false){
		$(field).val($(field).val().replace(/[^a-z0-9-\s]/gi, ''));
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

.fieldlabel{
	font-weight:bold;
	width:35%;
	text-align:right;
}

.separator{
	font-weight:bold;
	width:5%;
	text-align:center;
}

.trheader td{padding:5px; text-align:center; font-weight:bold; background:#ffdef0; border-right:1px solid #ffa3e0;}
.trlist td{padding:5px; border-right:1px solid #ffa3e0; border-top:1px solid #ffa3e0;}
</style>

<form name="frmCreateMultiLine" method="post" action="promo_popup.php?biID=<?php echo $_GET['biID'];?>&pcode=<?php echo $_GET['pcode']; ?>">
<?php
if ($errmsg != "")
{
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
	<td class="tabmin">	<input type="hidden" id="hBuyInCnt" name="hBuyInCnt" value="<?php echo $promodetailsquery->num_rows;?>">
	<input type="hidden" id="hBuyInCntSaving" name="hBuyInCntSaving" value="<?php echo $promodetailsquery->num_rows;?>">
	<input type="hidden" id="hBuyInIndex" name="hBuyInIndex" value="<?php echo $promodetailsquery->num_rows;?>">
	<input type="hidden" id="hEntitlementCnt" name="hEntitlementCnt" value="<?php echo $totcntent;?>">
	<input type="hidden" id="hEntitlementIndex" name="hEntitlementIndex" value="<?php echo $totcntent;?>">
	<input type="hidden" id="hPMGID" name="hPMGID" value="<?php echo $pmg_id; ?>">
	<input type="hidden" id="hPMGCode" name="hPMGCode" value="<?php echo $pmg_code; ?>"></td>
	<td class="tabmin2"><div align="left" class="txtredbold">Promo Header</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<input type="hidden" id="hID" name="hID" value="<?php echo $promoID;?>">
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	<tr>
		<td>
			<table width="100%" cellpadding="2" cellspacing="2">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td class="fieldlabel">Promo Code</td>
					<td class="separator">:</td>
					<td><input name="txtCode" type="text" class="txtredbold txtfieldLabel" id="txtSearch" value="<?php echo $promocode; ?>" size="30" readonly="yes"></td>
				</tr>
				<tr>
					<td class="fieldlabel">Promo Description</td>
					<td class="separator">:</td>
					<td><input name="txtDescription" type="text" class="txtfieldg" id="txtSearch" value="<?php echo $promodesc; ?>" style="width: 362px;" maxlength="60" onkeyup="return ValidateSpecialCharacters(this);" onkeydown="return ValidateSpecialCharacters(this);"></td>
				</tr>
				<tr>
					<td class="fieldlabel">Promo Effectivity Date</td>
					<td class="separator"></td>
					<td></td>
				</tr>
				<tr>
					<td class="fieldlabel">Start Date</td>
					<td class="separator">:</td>
					<td>
						<input name="txtStartDate" type="text" class="txtfieldg" id="txtStartDate" size="20" readonly="yes" value="<?php echo $startdate; ?>">
						<i>(e.g. MM/DD/YYYY)</i>
					</td>
				</tr>
				<tr>
					<td class="fieldlabel">End Date</td>
					<td class="separator">:</td>
					<td>
						<input name="txtEndDate" type="text" class="txtfieldg" id="txtEndDate" size="20" readonly="yes" value="<?php echo $enddate; ?>">
						<i>(e.g. MM/DD/YYYY)</i>
					</td>
				</tr>
				<tr>
					<td class="fieldlabel">Purchase Requirement Type</td>
					<td class="separator">:</td>
					<td>
						<select name="cboPReqtType" class="txtfieldg" id="cboPReqtType">
							<option value="1" <?php if($preqtype == 1) {?> selected="selected"<?php }?> >Single</option>
							<option value="2" <?php if($preqtype == 2) {?> selected="selected"<?php }?>>Cumulative</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class="fieldlabel">Brochure Page</td>
					<td class="separator">:</td>
					<td>
						<input name="bpage" type="text" class="txtfieldg" id="bpage" size="10" value="<?php echo $PageFrom; ?>" style = "width: 5%;" onkeyup="return NumbersOnly(this, <?=$PageFrom;?>);" onkeydown="return NumbersOnly(this, <?=$PageFrom;?>);">
						-
						<input name="epage" type="text" class="txtfieldg" id="epage" size="10" value="<?php echo $PageTo; ?>" style = "width: 5%;" onkeyup="return NumbersOnly(this, <?=$PageTo;?>);" onkeydown="return NumbersOnly(this, <?=$PageTo;?>);">
					</td>
				</tr>
				<tr><td>&nbsp;</td></tr>
			</table>
		</td>
		<td width="50%" valign="top">
			<table width="100%" cellpadding="2" cellspacing="2">
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td class="fieldlabel" valign="top">Max Availment</td>
					<td class="separator" valign="top">:</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">
						<table width="100%" cellpadding="2" cellspacing="2">
							<?php 
							if($rs_promoAvailment->num_rows){
								while($row = $rs_promoAvailment->fetch_object()){
									$txt = $row->MaxAvailment;
									echo "<tr>
											<td class='fieldlabel' style='width:40%;'>$row->Name </td>
											<td class='separator'>:</td>
											<td>
												<input type='text' id='txtMaxAvail$row->GSUTypeID' class='txtfieldg' name='txtMaxAvail$row->GSUTypeID' value='$txt' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail$row->GSUTypeID);'>
											</td>
										</tr>";
								}
								$rs_promoAvailment->close();
							}else{
								
								if($rs_gsutype->num_rows){
									while($row = $rs_gsutype->fetch_object()){
										echo "<tr>
											<td class='fieldlabel' style='width:40%;'>$row->Name </td>
											<td class='separator'>:</td>
											<td>
												<input type='text' id='txtMaxAvail$row->ID' class='txtfieldg' name='txtMaxAvail$row->ID' value='' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail$row->ID);'>
											</td>
										</tr>";
									}
									$rs_gsutype->close();
								}
								
							}
							?>
						</table>
					</td>
				</tr>
				<!--<tr>
					<td class="fieldlabel">Is Plus Plan</td>
					<td class="separator">:</td>
					<td>
						<input type="checkbox" name="chkPlusPlan" value="1" <?php if ($prmPplan == 1) { echo "checked"; } ?> >
					</td>
				</tr>-->
				<tr>
					<td class="fieldlabel">For New Recruit Only</td>
					<td class="separator">:</td>
					<td>
						<select name="IsNewRecruit" class="txtfield">
							<?php
								$x = array(0=>"No",1=>"Yes");
								foreach($x as $key => $value){									
									$selected = ($IsForNewRecruit==$key) ? "selected='selected'" : "";									
									echo "<option value = ".$key." ".$selected."> ".$value." </option>";
								}
							?>
						</select>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>

</table>
<br>

<div style="width:95%; margin:auto;">
	<table width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td>
			<td class="tabmin2 txtredbold">Buyin-in Requirements and Entitlement</td>
			<td class="tabmin3"></td>
		</tr>
	</table>
	
	<div class="bordersolo" style="border-top:none;">
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr class="trheader">
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
		<div class="promolist" style="max-height:350px; overflow:auto;">
		<table width="100%" cellpadding="0" cellspacing="0">
			<?php 
				if($promodetailsquery->num_rows){
					$counter = 1;
					while($res = $promodetailsquery->fetch_object()){
						$pmgquery = $database->execute("SELECT * FROM tpi_pmg WHERE ID IN (1,2,3)");
						
						echo "<tr class='trlist'>
								<td align='center' width='80px'>
									<input type='button' onclick='return RemoveItem(this)' value='Remove' class='btn' name='btnRemove'>
								</td>
								<td align='center' width='100px'>
									<span>".$res->ProductCode."</span>
									<input type='hidden' value='".$res->ProductID."' name='ProductID$counter' class='ProductID'>
									<input type='hidden' value='".$res->PromoBuyinID."' name='PromoBuyinID$counter'>
									<input type='hidden' value='".$res->EntitlementID."' name='PromoEntitlementID$counter'>
									<input type='hidden' value='".$res->UnitPrice."' name='UnitPrice$counter'>
									<input type='hidden' value='".$res->EntitlementDetailsID."' name='EntitlementDetailsID$counter'>
									<input type='hidden' value='".$res->BuyinMinimumQuantity."' name='BuyinMinimumQuantity$counter'>
								</td>
								<td><span>".$res->ProductName."</span></td>
								<td width='120px' align='center'><span>".$res->BuyinMinimumQuantity."</span></td>
								<td width='120px' align='center'><span>".$res->BuyinPMGCode."</span></td>
								<td width='120px' align='center'><span>Price</span></td>
								<td width='120px' align='right'><span>".number_format($res->UnitPrice, 2)."</span></td>
								<td width='120px' align='right'>
									<input type='text' value='".number_format($res->SpecialPrice, 2, '.', '')."' class='txtfield' style='width:100%; text-align:right;' name='SpecialPrice$counter' onkeydown='return ValidateField(this);' onkeyup='return ValidateField(this);'>
								</td>
								<td width='120px' align='center'>
									<select name='EntitlementPMGID$counter' class='txtfield' style='width:100%;'>
										<option value='0'>Select</option>";
										if($pmgquery->num_rows){
											while($pmg = $pmgquery->fetch_object()){
												$selected = ($pmg->ID == $res->EntitlementPMGID) ? "selected='selected'" : "";
												echo "<option value='".$pmg->ID."' $selected>".$pmg->Code."</option>";
											}
										}
									echo "</select>
								</td>
							</tr>";
						
						$counter++;
						
					}
				}else{
					echo '<tr class="trlist">
							<td colspan="9" align="center">No result found.</td>
						</tr>';
				}
			?>
			
		</table>
		</div>
	</div>
	<br />
	<div style='text-align:center;'>
		<?php 
			if(isset($_GET['link_branch'])){
				//
			}else{
				echo "<input type='button' name='btnAddItem' value='Add Item' class='btn'>";
				echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onclick='return confirmDelete();'>";
				echo "<input name='btnSave' type='submit' class='btn' value='Update' onclick='return confirmUpdate();'>";
			}
		?>
	</div>
	
</div>

<br />
</form>

<div class="dialogmessage"></div>

<!--<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td width= "45%" valign="top">
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
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="14%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Code</div></td>
							<td width="32%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td>
							<td width="13%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>
							<td width="13%" height="20" class="txtpallete borderBR"><div align="center">Minimum</div></td>
							<td width="12%" height="20" class="txtpallete borderBR"><div align="center">PMG</div></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div class="scroll_300">
					<table width="100%" id="tabletest"  border="0" cellpadding="0" cellspacing="0"  class="bgFFFFFF">
					<?php
		      			$rowalt = 0;
						$lineno = 0;
						if($rsbuyin->num_rows)
						{
							while($rowbuyin = $rsbuyin->fetch_object())
							{

								$lineno++;
								$rowalt++;
								($rowalt%2) ? $class = "" : $class = "bgEFF0EB";

						       $itemcode = $rowbuyin->BuyInProduct;
			        	       $itemdesc = $rowbuyin->BuyInDescription;
			        	       $amt = $rowbuyin->Amt;
			        	       $qty = $rowbuyin->Qty;
			        	       if ($rowbuyin->Type == 1)
			        	       {
			        	       		$type = "Quantity";
			        	       }
			        	       else
			        	       {
			        	       		$type = "Amount";
			        	       }

			        	       if(is_null($amt))
			        	       {
			        	         $criteria = $qty;
			        	       }

			        	       if(is_null($qty))
			        	       {
			        	       	  $criteria = number_format($amt, 2, '.', '');
			        	       }
					?>
					<tr align="center" class="<?php echo $class; ?>">
						<?php
						echo"<td width='14%' height='20' class='borderBR'>
						     <div align='left' class='padl5'>
							 <input name='txtProdCode$lineno' type='text' class='txtfieldg' id='txtProdCode$lineno' style='width: 75px;' value='$itemcode'/>
							 <span id='indicator$lineno' style='display: none'><img src='../../images/ajax-loader.gif' alt='Working...' /></span>
							 <div id='prod_choices$lineno' class='autocomplete' style='display:none'></div>
							 <script type='text/javascript'>
									 //<![CDATA[
										var prod_choices = new Ajax.Autocompleter('txtProdCode$lineno', 'prod_choices$lineno', '../../includes/scProductListAjax.php?index=$lineno', {afterUpdateElement : getSelectionProductList, indicator: 'indicator$lineno'});
			                        //]]>
							 </script>
							 <input name='BUYIN$lineno' type='hidden' id='BUYIN$lineno' value='$rowbuyin->buyinID' />
							 <input name='hProdID$lineno' type='hidden' id='hProdID$lineno' value='$rowbuyin->prodid' />
							 <input name='hbpmgid$lineno' type='hidden' id='hbpmgid$lineno' value='$rowbuyin->pmgid'/>
							</div>
						</td>";
						?>
						<td width="32%" height="20" class="borderBR">
						<input name="txtProdName<?php echo $lineno; ?>" readonly ="readonly" type="text" class="txtfieldg" id="txtProdName<?php echo $lineno; ?>" style="width: 200px;" value="<?php echo $itemdesc; ?>"/></td>
						<td width="13%" height="20" class="borderBR">
							<select name="cboCriteria<?php echo $lineno; ?>" class="txtfieldg" id="cboCriteria<?php echo $lineno; ?>" style="width: 80px;">
								<option value="2" <?php if($type == "Amount"){?> selected="selected" <?php }?>>Amount</option>
								<option value="1" <?php if($type == "Quantity"){?> selected="selected" <?php }?>>Quantity</option>
							</select>
						</td>
						<td width="13%" height="20" class="borderBR"><div align="center"><input name="txtQty<?php echo $lineno; ?>" type="text" class="txtfieldg" id="txtQty<?php echo $lineno; ?>"  value="<?php echo $criteria; ?> " style="width: 50px; text-align:right" /></div></td>
						<td width="12%" height="20" class="borderBR"><div align="center"><input name="txtbPmg<?php echo $lineno; ?>" type="text" id="txtbPmg<?php echo $lineno; ?>" class="txtfieldg"  style="width: 75px; text-align:left" readonly="yes" value="<?php echo $rowbuyin->pmgCode; ?>"/></td>
					</tr>
					<?php
					 }
						}
						else
						{
							echo "<tr align='center'><td height='20' colspan='5' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</b></span></td></tr>";
						}
					?>
					<?php $lineno++; ?>

					</table>
				</div>
			</td>
		</tr>
		</table>
	</td>
	<td width= "1%">&nbsp;</td>
	<td width= "45%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td>
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Entitlement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr align="center">
			<td height="25" class="txtpallete borderBR"><div align="left" class="padl5">
				<strong>Type :</strong>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="cboType" class="txtfieldg" id="cboType" style="width: 100px;" onchange="selection()">
					<option value="1" <?php if($entType == "1"){?> selected="selected" <?php }?>>Set</option>
					<option value="2" <?php if($entType == "2"){?> selected="selected" <?php }?>>Selection</option>
				</select>
				&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;
				<?php if ($entType==1){?>
					<input name="txtTypeQty" type="text" class="txtfieldg" id="txtTypeQty" <?php if($entType == "1"){?> readonly="readonly" <?php }?> value="" style="width: 60px; text-align: right;">
				<?php }else{ ?>
					<input name="txtTypeQty" type="text" class="txtfieldg" id="txtTypeQty" <?php if($entType == "1"){?> readonly="readonly" <?php }?> value="<?php echo $entQty; ?>" style="width: 60px; text-align: right;">
				<?php } ?>
				<!--
				<strong>Type :</strong>
				&nbsp;&nbsp;

				<?php if($entType == 1)
				{
					echo "Set";
				}
				else
				{
					echo "Selection";
				}

				?>
				&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;
				<?php echo $entQty; ?>-->
			<!--</div></td>
		</tr>
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="14%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Code</div></td>
							<td width="34%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td>
							<td width="14%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>
							<td width="14%" height="20" class="txtpallete borderBR"><div align="center">Qty/Price</div></td>
							<td width="20%" height="20" class="txtpallete borderBR"><div align="center">PMG</div></td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div class="scroll_300">
					<table width="100%" id="tableETest"  border="0" cellpadding="0" cellspacing="0" id="dynamicEntTable" class="bgFFFFFF">
					<?php
		      			$rowalt = 0;
						$lineCnt2 = 1;
						if($rsbuyin1->num_rows)
						{
							while($rowbuyin = $rsbuyin1->fetch_object())
							{
								$rowalt++;
								($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
			        	       $bonuscode = $rowbuyin->EntProduct;
			        	       $bonusdesc = $rowbuyin->EntProdDesc;
			        	       $bonusqty = $rowbuyin->Quantity;
			        	       $bonusamt = $rowbuyin->EffectivePrice;
			        	       $type = $rowbuyin->Type;

						     /*
							   if($type == 2)
							   {
								 $criteria = number_format($bonusamt, 2, '.', '');
								 $type = "Price";
							   }
							   else
							   {
								  $criteria = $bonusqty;
								  $type = "Quantity";
							   }
							*/

							   if($bonusamt > 0){
			        	         $criteria = number_format($bonusamt, 2, '.', '');
			        	         $type = "Price";
			        	       }else{
			        	       	  $criteria = $bonusqty;
			        	       	  $type = "Quantity";
			        	       }
					?>
						<?php
						echo"<td width='14%' height='20' class='borderBR'>
						     <div align='left' class='padl5'>
							 <input name='txtEProdCode$lineCnt2' type='text' class='txtfieldg' id='txtEProdCode$lineCnt2' style='width: 75px;' value='$bonuscode'/>
							 <span id='indicatorE$lineCnt2' style='display: none'><img src='../../images/ajax-loader.gif' alt='Working...' /></span>
							 <div id='prod_choicesE$lineCnt2' class='autocomplete' style='display:none'></div>
							 <script type='text/javascript'>
									 //<![CDATA[
										var prod_choices = new Ajax.Autocompleter('txtEProdCode$lineCnt2', 'prod_choicesE$lineCnt2', '../../includes/scProductListEntitlementAjax.php?index=$lineCnt2', {afterUpdateElement : getSelectionProductList2, indicator: 'indicatorE$lineCnt2'});
			                        //]]>
							 </script>
							 <input name='ENTID$lineCnt2' type='hidden' id='ENTID$lineCnt2' value='$rowbuyin->ENTID' />
							 <input name='hEProdID$lineCnt2' type='hidden' id='hEProdID$lineCnt2' value='$rowbuyin->prodid' />
							 <input name='hEUnitPrice$lineCnt2' type='hidden' id='hEUnitPrice$lineCnt2' value='$rowbuyin->unitprice' />
							 <input name='hEpmgid$lineCnt2' type='hidden'id='hEpmgid$lineCnt2' value='$rowbuyin->pmgid'/>
							</div>
						</td>";
					   ?>
						<td width="34%" height="20" class="borderBR">
						<input name="txtEProdName<?php echo $lineCnt2; ?>"type="text" class="txtfieldg" id="txtEProdName<?php echo $lineCnt2; ?>" style="width: 200px;" value="<?php echo $bonusdesc; ?>"/></td>
						<td width="14%" height="20" class="borderBR">
						<select name="cboECriteria<?php echo $lineCnt2; ?>" class="txtfieldg" id="cboECriteria<?php echo $lineCnt2; ?>" style="width: 80px;">
								<option value="2" <?php if($type == "Price"){?> selected="selected" <?php }?>>Price</option>
								<option value="1" <?php if($type == "Quantity"){?> selected="selected" <?php }?>>Quantity</option>
							</select>
						</td>
						<td width="14%" height="20" class="borderBR"><div align="center"><input name="txtEQty<?php echo $lineCnt2; ?>" type="text" class="txtfieldg" id="txtEQty<?php echo $lineCnt2; ?>"  value="<?php echo $criteria; ?> " style="width: 50px; text-align:right" /></div></td>
						<td width="20%" height="20" class="borderBR"><div align="center">
							<!--<input name="txtPmg<?php echo $lineCnt2; ?>" type="text" id="txtPmg<?php echo $lineCnt2; ?>" readonly="readonly" class="txtfieldg"  style="width: 75px; text-align:left" value="<?php echo $rowbuyin->pmgCode; ?>"/>-->
							<!--<select name="cboEPMG<?php echo $lineCnt2; ?>"  class="txtfieldg" id="cboEPMG<?php echo $lineCnt2; ?>" style="width: 80px;">
								<?php
									$rs_pmg_det = "rs_pmg".$lineCnt2;
									$rs_pmg_det = $sp->spSelectPMG($database);
									if ($rs_pmg_det->num_rows)
									{
										while ($row = $rs_pmg_det->fetch_object())
										{
											if ($rowbuyin->pmgid == $row->ID)
											{
												$sel = "selected";
											}
											else
											{
												$sel = "";
											}
											//if (($rowbuyin->pmgid == 1 &&  $row->ID != 2) || ($rowbuyin->pmgid == 2 &&  $row->ID != 1))
											//{
												echo "<option $sel value='$row->ID'>$row->Code</option>";
											//}
										}
										$rs_pmg_det->close();
									}
								?>
							</select>
						</div></td>
					</tr>
					<?php
				   $lineCnt2++;
					 }
						}
						else
						{
							echo "<tr align='center'><td height='20' colspan='5' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</b></span></td></tr>";
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
<br>
<table width="100%" align="left"  border="0" cellpadding="0" cellspacing="0">
<tr>

	<td align="center">
	<?php
	//	if ($_SESSION['ismain'] == 1)
	//	{
	//		if (($startdate != $today) && (($startdate > $today) || ($enddate < $today)))
	//		{
			if(isset($_GET['link_branch'])){
				//
			}else{
				echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onclick='return confirmDelete();'>";
				echo "<input name='btnSave' type='submit' class='btn' value='Update' onclick='return confirmUpdate();'>";
			}
	//		}
	//	}
	//?>
	</td>
</tr>
</table>

</form>
<br>-->