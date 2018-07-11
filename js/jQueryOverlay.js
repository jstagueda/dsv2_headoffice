$(document).ready(function(){

	$('[name=IsAnyPromo]').change(function(){
		if($(this).is(':checked')){
			$('[name=TotalPrice]').removeAttr('disabled');
			$('[name=cboType]').val(1);
			$('[name=chkApplyAsDiscount]').attr("checked", "checked");
		}else{
			$('[name=TotalPrice]').val('0.00');
			$('[name=TotalPrice]').attr('disabled', 'disabled');
			$('[name=cboType]').val(0);
			$('[name=chkApplyAsDiscount]').removeAttr("checked");
		}
	});

    $("#txtSetStartDate, #txtSetEndDate, #txtEndDate, #txtStartDate").datepicker(
	//{
    //    changeMonth: true,
    //    changeYear: true
    //}
	);

$('#txtProductCode').autocomplete({
		source:'includes/jxoverlay.php',
			select: function( event, ui ) {
				$( "#txtProductCode").val( ui.item.label);
				$( "#txtCDescription").val( ui.item.ProductName);
			return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( ul );
		};

	$('#cboRange').change(function(){
		if($(this).val() == 1){
			$("#cboCriteria").removeAttr("disabled","disabled");
			$("#txtMinimum").removeAttr("disabled","disabled");
			$("#txtSetStartDate").removeAttr("disabled","disabled");
			$("#txtSetEndDate").removeAttr("disabled","disabled");
			$("#txtProductCode").attr("disabled","disabled");
			$("#txtCDescription").attr("disabled","disabled");
			$("#pline").attr("disabled", "disabled");
			$("#txtPromoCodePromo").attr("disabled","disabled");
			$("#txtPromoCodePromo").val("");
			$("#btnAdd").removeAttr("disabled","disabled");
			//brochure enabled
			$("#BrochureStart").attr("disabled","disabled");
			$("#BrochureStart").val("");
			$("#BrochureEnd").attr("disabled","disabled");
			$("#BrochureEnd").val("");
                        $("#BrochureType").attr("disabled","disabled");

		}else if($(this).val() == 2){

			$("#pline").removeAttr("disabled", "disabled");
			$("#txtSetStartDate").removeAttr("disabled","disabled");
			$("#txtSetEndDate").removeAttr("disabled","disabled");
			$("#cboCriteria").removeAttr("disabled","disabled");
			$("#txtMinimum").removeAttr("disabled","disabled");
			$("#txtProductCode").attr("disabled","disabled");
			$("#txtCDescription").attr("disabled","disabled");
			$("#btnAdd").removeAttr("disabled","disabled");
			$("#txtPromoCodePromo").attr("disabled","disabled");
			$("#txtPromoCodePromo").val("");
			//brochure enabled
			$("#BrochureStart").attr("disabled","disabled");
			$("#BrochureStart").val("");
			$("#BrochureEnd").attr("disabled","disabled");
			$("#BrochureEnd").val("");
                        $("#BrochureType").attr("disabled","disabled");


		}else if($(this).val() == 3){
			$("#cboCriteria").removeAttr("disabled","disabled");
			$("#txtMinimum").removeAttr("disabled","disabled");
			$("#txtProductCode").removeAttr("disabled","disabled");
			$("#txtCDescription").removeAttr("disabled","disabled");
			$("#txtSetStartDate").removeAttr("disabled","disabled");
			$("#txtSetEndDate").removeAttr("disabled","disabled");
			$("#pline").attr("disabled", "disabled");
			$("#btnAdd").removeAttr("disabled","disabled");
			$("#txtPromoCodePromo").attr("disabled","disabled");
			$("#txtPromoCodePromo").val("");
			//brochure enabled
			$("#BrochureStart").attr("disabled","disabled");
			$("#BrochureStart").val("");
			$("#BrochureEnd").attr("disabled","disabled");
			$("#BrochureEnd").val("");
                        $("#BrochureType").attr("disabled","disabled");
		}else if ($(this).val() == 4 || $(this).val() == 5 || $(this).val()==7) {
			$("#cboCriteria").removeAttr("disabled","disabled");
			$("#txtMinimum").removeAttr("disabled","disabled");
			$("#txtProductCode").attr("disabled","disabled");
			$("#txtCDescription").attr("disabled","disabled");
			$("#txtSetStartDate").removeAttr("disabled","disabled");
			$("#txtSetEndDate").removeAttr("disabled","disabled");
			$("#pline").attr("disabled", "disabled");
			$("#txtPromoCodePromo").removeAttr("disabled","disabled");
			$("#btnAdd").removeAttr("disabled","disabled");
			//brochure enabled
			$("#BrochureStart").attr("disabled","disabled");
			$("#BrochureStart").val("");
			$("#BrochureEnd").attr("disabled","disabled");
			$("#BrochureEnd").val("");
                        $("#BrochureType").attr("disabled","disabled");
		}else if ($(this).val() == 6){
			$("#cboCriteria").removeAttr("disabled","disabled");
			$("#txtMinimum").removeAttr("disabled","disabled");
			$("#txtProductCode").attr("disabled","disabled");
			$("#txtCDescription").attr("disabled","disabled");
			$("#txtSetStartDate").removeAttr("disabled","disabled");
			$("#txtSetEndDate").removeAttr("disabled","disabled");
			$("#pline").attr("disabled", "disabled");
			$("#btnAdd").removeAttr("disabled","disabled");
			$("#txtPromoCodePromo").attr("disabled","disabled");
			//brochure enabled
			$("#BrochureStart").removeAttr("disabled","disabled");
			$("#BrochureStart").val("");
			$("#BrochureEnd").removeAttr("disabled","disabled");
			$("#BrochureEnd").val("");
                        $("#BrochureType").removeAttr("disabled","disabled");


		}else{
			$("#cboCriteria").attr("disabled","disabled");
			$("#txtMinimum").attr("disabled","disabled");
			$("#txtProductCode").attr("disabled","disabled");
			$("#txtCDescription").attr("disabled","disabled");
			$("#txtSetStartDate").attr("disabled","disabled");
			$("#txtSetEndDate").attr("disabled","disabled");
			$("#pline").attr("disabled", "disabled");
			$("#btnAdd").attr("disabled","disabled");
			$("#txtPromoCodePromo").attr("disabled","disabled");
			//brochure enabled
			$("#BrochureStart").attr("disabled","disabled");
			$("#BrochureStart").val("");
			$("#BrochureEnd").attr("disabled","disabled");
			$("#BrochureEnd").val("");
                        $("#BrochureType").attr("disabled","disabled");

		}
	});

	$("#cboType").change(function(){
		if($("#cboType").val() == 1){
			//Set
			$("#txtTypeQty").val("");
			$("#txtTypeQty").attr("readonly","readonly");
			return false;
		}else{
			$("#txtTypeQty").val("");
			$("#txtTypeQty").removeAttr("readonly","readonly");
			return false;
		}
	});
	
	
	$('[name=txtCode]').keyup(function(){
			
		var validation = /^[A-z 0-9]*$/i;
		var val = $(this).val();
		var string = '';
		
		if(!validation.test($(this).val())){
			
			for(var x = 0; x < $(this).val().length; x++){
			
				if(!validation.test(val.charAt(x))){
					string += '';
				}else{
					string += val.charAt(x);
				}
				
			}
			
			$(this).val(string);
		}
		
	});
	
	
	$('[name=txtDescription]').keyup(function(){
		
		var validation = /^[A-z 0-9]*$/i;
		var val = $(this).val();
		var string = '';
		
		if(!validation.test($(this).val())){
			
			for(var x = 0; x < $(this).val().length; x++){
			
				if(!validation.test(val.charAt(x))){
					string += '';
				}else{
					string += val.charAt(x);
				}
				
			}
			
			$(this).val(string);
		}
		
	});


});




function CheckPromo(e)
{
    var Code = document.getElementById('txtCode');
   if (e.keyCode == 13 || e.keyCode == 9) {
	if(Code.value != ""){
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
          if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var value = xmlhttp.responseText;
                  if(value.length > 2)
                {
                    alert("Promo Code Already exist please choose Other Promo Code");
                    document.getElementById('txtCode').value = '';
					Code.focus();
                }
                else
                {
				  $('#txtDescription').removeAttr("disabled");
				  $('#txtStartDate').removeAttr("disabled");
				  $('#txtEndDate').removeAttr("disabled");
				  $('#rdoBReqt').removeAttr("disabled");
				  $('#isRegular').removeAttr("disabled");
				  //$('#rdoBReqt1').removeAttr("disabled");
				  $("#txtMaxAvail1").removeAttr("disabled");
				  $("#txtMaxAvail2").removeAttr("disabled");
				  $("#txtMaxAvail3").removeAttr("disabled");
				  $('#cboPReqtType').removeAttr("disabled");
				  $('#selection_no').removeAttr("disabled");
				  $('#cboRange').removeAttr("disabled");
				  $('#bpage').removeAttr("disabled");
				  $('#epage').removeAttr("disabled");
				  $('#txtDescription').focus();
                }
            }
        }
        xmlhttp.open("GET","includes/jxsinglelinepromo.php?Code="+Code.value, true);
        xmlhttp.send(null);
    }else{
		alert('Please input Promo Code');
	}
	return false;
 }

}

function NextField(e,a)
{
	 if (e.keyCode == 13 || e.keyCode == 9) {
	 //alert(a)
		document.getElementById(a).focus();
		// document.getElementById('txtStartDate').focus();
		return false;
	 }
}

function addRow(e, field)
{
	var html = "";
	
	if(e.keyCode == 13 || e.keyCode == 9){
		
		var table = $('#multirow tr.trlist');
		var tableRow = $('#multirow tr.trlist').length;
		var newIndex = (tableRow + 1);
		
		if($(field).closest('.trlist').find('td:nth-child(3)').find('input:eq(1)').val() == ""){
			return false;
		}else{
			if($(field).val() == 0 || $(field).val() == ""){
				return false;
			}else{
				html += '<tr align="center" class="trlist">'+
							'<td>'+
								'<input name="btnRemove'+newIndex+'" type="button" class="btn" value="Remove" onclick="return removeRow(this);">'+
							'</td>'+
							'<td>'+newIndex+'</td>'+
							'<td>'+
								'<input name="txtEProdCode'+newIndex+'" onkeypress="return xautocompleter('+newIndex+');" type="text" class="txtfield" id="txtEProdCode'+newIndex+'" style="width: 99%;" value=""/>'+
								'<input name="hEProdID'+newIndex+'" type="hidden" id="hEProdID'+newIndex+'" value="" />'+
								'<input name="hEUnitPrice'+newIndex+'" type="hidden" id="hEUnitPrice'+newIndex+'" value=""/>'+
							'</td>'+
							'<td>'+
								'<input name="txtEProdDesc'+newIndex+'" type="text" class="txtfield" id="txtEProdDesc'+newIndex+'" style="width: 99%;" readonly="yes" />'+
							'</td>'+
							'<td>'+
								'<select name="cboECriteria'+newIndex+'" class="txtfield" id="cboECriteria'+newIndex+'" style="width: 99%;">'+
									'<option value="2" selected="selected">Price</option>'+
									'<option value="1">Quantity</option>'+
								'</select>'+
							'</td>'+
							'<td><input name="txtEQty'+newIndex+'" type="text" class="txtfield" id="txtEQty'+newIndex+'"  value="1" style="width: 99%; text-align:right" onkeypress="return addRow(event, this)" /></td>'+
							'<td>'+
								'<select name="cboEPMG'+newIndex+'" class="txtfield" id="cboEPMG'+newIndex+'" style="width: 99%;" >'+
									'<option value="1">CFT</option>'+
									'<option value="2">NCFT</option>'+
									'<option value="3">CPI</option>'+
								'</select>'+
							'</td>'+
						'</tr>';
			}
		}
		
		if($(field).attr('name').substr(7) == tableRow){
			$("#multirow").append(html);
			$('#txtEProdCode'+newIndex).focus();
			$('#entitlementcnt').val(newIndex);
		}else{			
			$('#txtEProdCode'+tableRow).focus();
		}
		
		return false;

	}
}

function removeRow(field){
	
	if($("#multirow").find('tr.trlist').length > 1){
		
		$(field).closest('tr.trlist').remove();
		
		$("#multirow").find('tr.trlist').each(function(index){			
		
			$(this).find('td:nth-child(1) input').attr('name', 'btnRemove' + (index+1));
			
			$(this).find('td:nth-child(2)').html((index+1));
			
			$(this).find('td:nth-child(3)').find('input:eq(0)').attr('name', 'txtEProdCode'+(index+1));
			$(this).find('td:nth-child(3)').find('input:eq(0)').attr('id', 'txtEProdCode'+(index+1));
			
			$(this).find('td:nth-child(3)').find('input:eq(1)').attr('name', 'hEProdID'+(index+1));
			$(this).find('td:nth-child(3)').find('input:eq(1)').attr('id', 'hEProdID'+(index+1));
			
			$(this).find('td:nth-child(3)').find('input:eq(2)').attr('name', 'hEUnitPrice'+(index+1));
			$(this).find('td:nth-child(3)').find('input:eq(2)').attr('id', 'hEUnitPrice'+(index+1));
			
			$(this).find('td:nth-child(4) input').attr('name', 'txtEProdDesc'+(index+1));
			$(this).find('td:nth-child(4) input').attr('id', 'txtEProdDesc'+(index+1));
			
			$(this).find('td:nth-child(5) select').attr('name', 'cboECriteria'+(index+1));
			$(this).find('td:nth-child(5) select').attr('id', 'cboECriteria'+(index+1));
			
			$(this).find('td:nth-child(6) input').attr('name', 'txtEQty'+(index+1));
			$(this).find('td:nth-child(6) input').attr('id', 'txtEQty'+(index+1));
			
			$(this).find('td:nth-child(7) select').attr('name', 'cboEPMG'+(index+1));
			$(this).find('td:nth-child(7) select').attr('id', 'cboEPMG'+(index+1));
			
		});
		
		$('#entitlementcnt').val($("#multirow").find('tr.trlist').length);
		return false;
	}
	
}

/*$.fn.CreateAutocomplete = function(count){

	$( "#txtEProdCode"+count+"").autocomplete({
		source:'includes/jxoverlay.php',
		select: function( event, ui ) {
			$( "#txtEProdCode"+count+"").val( ui.item.label);
			$( "#txtEProdDesc"+count+"").val( ui.item.ProductName);
			$( "#hEProdID"+count+"").val( ui.item.ProductID);
			$( "#hEUnitPrice"+count+"").val( ui.item.UnitPrice);
			var theValue = ui.item.PMGID;
			$("#cboEPMG"+count+"").val(theValue).change();
			return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( li, item ) {
		return jQuery( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( li );
	};

				//$('option[name = "cboEPMG1" value=' + theVa
};*/

function confirmAdd(){

if($('#cboRange').val() == 1){

	if($("#txtMinimum").val()==""){
		alert('Required Quantity / Amount');
		return false;
	}


}else if($('#cboRange').val() == 2){

	if($("#pline").val() == 0){
		alert('Required Product Line');
		return false;
	}

		if($("#txtMinimum").val()==""){
			alert('Required Quantity / Amount');
			return false;
		}


}else if($('#cboRange').val() == 3){

	if($("#txtProductCode").val() == ""){
		alert('Required Product Code');
		return false;
	}

			if($("#txtMinimum").val()==""){
				alert('Required Quantity / Amount');
				return false;
			}


}else if($('#cboRange').val() == 6){

	if($("#BrochureStart").val() == ""){
		alert('Brochure Page from Required.');
		return false;
	}

	if($("#BrochureEnd").val()==""){
		alert('Brochure Page to Required.');
		return false;
	}


}
var s_date = Date.parse($('#txtSetStartDate').val());
var sdate = new Date(s_date);
var e_date = Date.parse($('#txtSetEndDate').val());
var edate = new Date(e_date);

var now = new Date();
var now_day = now.getDate();
var now_month = now.getMonth() + 1;
var now_year = now.getFullYear();
var now_date = now_month + "/" + now_day + "/" + now_year;

if(sdate > edate){
	alert("Buy-in Requirements End date should be the same or later than Start date.");
	return false;
}

if(getDateObject($('#txtSetStartDate').val(), "/") < getDateObject(now_date, "/")){
	alert("Start date should be current or future date.");
	return false;
}
if(confirm("Are you sure want to add this buy-in requirement?")==false){
	return false;
}else{
	var html = "";
	$.ajax({
		type: 'post',
		dataType: 'json',
		data:	$('form').serialize(),
		url:	'includes/ajaxoverlayadd.php?add=add',
		success: function(response){
			if(response[0].result == "Success"){
				var ctr = 1;
				
				html += '<tr align="center" class="trheader">'+
							'<td width="12%">Overlay No.</td>'+
							'<td width="25%">Selection</td>'+
							'<td width="10%">Criteria</td>'+
							'<td width="9%">Minimum</td>'+
							'<td width="12%">Start Date</td>'+
							'<td width="12%">End Date</td>'+
							'<td width="5%">Action</td>'+
						'</tr>';
				
				for(i=0; response.length > i; i++){
					html += '<tr class="trlist"><td align="center">'+ctr+'</td>';
					html += '<td>'+response[i].ProductName+'</td>';
					html += '<td align="center">'+(response[i].CriteriaID==1 ? response[i].MinQty :(response[i].CriteriaID==2?response[i].MinAmt:''))+'</div></td>';
					html += '<td>'+(response[i].CriteriaID==1 ? "Quantity":(response[i].CriteriaID==2 ?"Amount":""))+'</td>';
					html += '<td>'+response[i].StartDate+'</td>';
					html += '<td></div>'+response[i].EndDate+'</td>';
					html += '<td align="center"><input type = "submit" value = "remove" class = "btn" onclick = "return removeClick('+response[i].tmpID+')"</td></tr>';
				ctr++;
				}
				//alert(html);
				$("#buyindetails").html(html);
				$("#btnSave").removeAttr("disabled","disabled");
				//$("#btnAdd").attr("disabled","disabled");
			}else{
				alert('xxxxx');
			}
		}
	})
	return false;
}
return false
}

function removeClick(tmpID){
if(confirm("are you sure want to remove this buy-in requirements?")==false){
	return false;
}else{
var html1 = "";
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {xtmpID: tmpID},
		url: 'includes/ajaxoverlayadd.php?remove=remove',
		success:	function(response){
			var ctr = 1;
			
			html1 += '<tr align="center" class="trheader">'+
							'<td width="12%">Overlay No.</td>'+
							'<td width="25%">Selection</td>'+
							'<td width="10%">Criteria</td>'+
							'<td width="9%">Minimum</td>'+
							'<td width="12%">Start Date</td>'+
							'<td width="12%">End Date</td>'+
							'<td width="5%">Action</td>'+
						'</tr>';
			
			if(response[0].result == "Success"){
			
				for(i=0; response.length > i; i++){
					html1 += '<tr class="trlist"><td align="center">'+ctr+'</td>';
					html1 += '<td>'+response[i].ProductName+'</td>';
					html1 += '<td align="center">'+(response[i].CriteriaID==1 ? response[i].MinQty :(response[i].CriteriaID==2?response[i].MinAmt:''))+'</div></td>';
					html1 += '<td>'+(response[i].CriteriaID==1 ? "Quantity":(response[i].CriteriaID==2 ?"Amount":""))+'</td>';
					html1 += '<td>'+response[i].StartDate+'</td>';
					html1 += '<td></div>'+response[i].EndDate+'</td>';
					html1 += '<td align="center"><input type = "submit" value = "remove" class = "btn" onclick = "return removeClick('+response[i].tmpID+')"</td></tr>';
				ctr++;
				}
				//alert(html);
				$("#buyindetails").html(html1);
				//$("#btnAdd").attr("disabled","disabled");
			}else{
				html1 += '<tr class="trlist"><td colspan="7" align="center" style="padding:5px;">No record(s) to display.</td></tr>';
				$("#btnSave").attr("disabled","disabled");
				$("#buyindetails").html(html1);
			}
		}
	})
}
return false;
}

function confirmSave()
{

	var PormoCode		= $('#txtCode').val();
	var Description 	= $('#txtDescription').val();
	var StartDate 		= $('#txtStartDate').val();
	var EndDate			= $('#txtEndDate').val();
	var cboPReqtType	= $('#cboPReqtType').val();
	var cboRange		= $('#cboRange').val();
	var error			= 0;

	if(PormoCode == ""){
		alert("Promo Code required");
	}

	if(Description	== ""){
		alert("Promo Description required");
		return false;
	}
	else if(StartDate 	== ""){
		alert("Start Date required");
		return false;
	}
	else if(EndDate == ""){
		alert("input End Date required");
		return false;
	}else if(cboPReqtType == ""){
		alert("Promo Purchase Requirement Type required");
		return false;
	}

	if($("#cboType").val() == 2){
		if($("#txtTypeQty").val()==""){
			alert("Selection No. required");
			return false;
		}
	}


	if($("#rdoBReqt").val()==2 || $("#rdoBReqt").val()==3){
		if($("#selection_no").val()==""){
			alert("Selection No. required.");
			$("#selection_no").focus();
			return false;
		}
	}
	
	if($('[name=IsAnyPromo]').is(":checked")){
		if($('[name=cboType]').val() != 1){
			alert("Promo is set as Any Promo. Please select entitlement type as Set.");
			$('[name=cboType]').val(1);
			return false;
		}
		
		if($('[name=chkApplyAsDiscount]').is(':checked') == false){
			alert("Promo is set as Any Promo. Please apply this as discount.");
			$('[name=chkApplyAsDiscount]').attr("checked", "checked");
			return false;
		}
	}


	var s_date = Date.parse($('#txtStartDate').val());
    var sdate = new Date(s_date);
    var e_date = Date.parse($('#txtEndDate').val());
    var edate = new Date(e_date);

	var now = new Date();
	var now_day = now.getDate();
	var now_month = now.getMonth() + 1;
	var now_year = now.getFullYear();
	var now_date = now_month + "/" + now_day + "/" + now_year;

	if(sdate > edate){
		alert("End date should be the same or later than Start date.");
		return false;
	}

	if(getDateObject($('#txtStartDate').val(), "/") < getDateObject(now_date, "/"))
	{
		alert("Start date should be current or future date.");
		return false;
	}

	//var Multi Entitlement
	var MEvalidation = $("#entitlementcnt").val();
	var err = 0;
	for(var i=1; i <= MEvalidation; i++){
		if($("#txtEProdCode"+i).val() == ""){
			err++;
		}else if($("#cboECriteria" + i).val() == 0){
			err++;
		}else if( $("#txtEQty" + i).val() == ""){
			err++;
		}
	}
	if(err == 0){

		if(confirm("Are you sure want to save this promo?")==false){
			return false;
		}else{
			$.ajax({
				type: 		'post',
				dataType: 	'json',
				data: 		 $('form').serialize(),
				url:		'includes/ajaxoverlayadd.php?save=save',
				success:	function(response){
					if(response.result == "Gino"){
						//alert(response.success);
						window.location.assign("index.php?pageid=178&inc=1");
						return false;
					}
					return false;
				}
			});
			return false;
		}
	}else{
			alert('Please Complete Entitlement Form or Remove Blank Form');
			return false;
	}
return false;
}

function confirmCancel(){
	if(confirm("are you sure want to cancel?")==false){
	return false;
	}else{
		window.location.assign("index.php?pageid=178&inc=1");
	}
return false;
}

function getDateObject(dateString,dateSeperator)
{
	//This function return a date object after accepting
	//a date string ans dateseparator as arguments
	var curValue=dateString;
	var sepChar=dateSeperator;
	var curPos=0;
	var cDate,cMonth,cYear;

	//extract day portion
	curPos=dateString.indexOf(sepChar);
	cMonth=dateString.substring(0,curPos);

	//extract month portion
	endPos=dateString.indexOf(sepChar,curPos+1);
	cDate=dateString.substring(curPos+1,endPos);

	//extract year portion
	curPos=endPos;
	endPos=curPos+5;
	cYear=curValue.substring(curPos+1,endPos);

	//Create Date Object
	dtObject=new Date(cYear,cMonth,cDate);
	return dtObject;
}


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

function xautocompleter(counter){
	$('#txtEProdCode'+counter+'').autocomplete({
		source:'includes/jxoverlay.php',
			select: function( event, ui ) {
				$( "#txtEProdCode"+counter+"").val( ui.item.label);
				$( "#txtEProdDesc"+counter+"").val( ui.item.ProductName);
				$( "#hEProdID"+counter+"").val( ui.item.ProductID);
				$( "#hEUnitPrice"+counter+"").val( ui.item.UnitPrice);
				var theValue = ui.item.PMGID;
				$("#cboEPMG"+counter+"").val(theValue).change();
				$("#txtEQty"+counter).focus();
				//hEProdID
				return false;
		}}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( ul );
		};
}

function keyfunction(counter,event)
{
	//testing only
	if(event.keyCode == 13 || event.keyCode == 9){
		$("#txtEQty"+counter).focus();
		return false;
	}
	return false;
}


//promo within promo

//getting promo code
function get_promo()
{
	var BuyinSelection	 =  "";
	var PromoCodePromo	 = 	"";
	var	StartDate 		= $("#txtStartDate").val();
	var	EndDate 		= $("#txtEndDate").val();

	BuyinSelection  = $("#cboRange").val();
	PromoCodePromo 	= $('#txtPromoCodePromo');

	//auto complete
	PromoCodePromo.autocomplete({
	source:'includes/jxloyaltyvalidation.php?BuyinSelection='+BuyinSelection+'&StartDate='+StartDate+'&EndDate='+EndDate,
		select: function( event, ui ) {
			PromoCodePromo.val( ui.item.Code);
			return false;
	}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.Code + "</strong> - " + item.Description + "</a>" )
			.appendTo( ul );
	};
}

function buyin_requirement()
{
	if($("#rdoBReqt").val()==2 || $("#rdoBReqt").val()==3){
		$("#selection_no").removeAttr("disabled","disabled");
	}else{
		$("#selection_no").attr("disabled","disabled");
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