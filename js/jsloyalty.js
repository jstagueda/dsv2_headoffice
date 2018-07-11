/*
	Author : @Gino C. Leabres
	Date   : @1/24/2012
*/
//Validation from Selection DropDown:
$(document).ready(function(){
/*
bka gamitin next generation :)
*/
$('#ajaxvalidation').hide();

var scSection = $("#sSection");
//Validation Field Buy In Requirement
	scSection.change(function(){
		if(scSection.val() == 1){
			$('#txtProdCode').removeAttr("disabled", "disabled");
			$('#sBrand').attr("disabled", "disabled");
			$('#sForm').attr("disabled", "disabled");
			$('#sStyle').attr("disabled", "disabled");
			//$('#sPMG').attr("disabled", "disabled");
			$('#cProdLine').attr("disabled", "disabled");

			//Clear text boxes and selections;
			$('#sBrand').val(0);
			$('#sForm').val(0);
			$('#sStyle').val(0);
			$('#cProdLine').val(0);
			//txtProdCodeDescription
		}else if (scSection.val() == 2){
			$('#txtProdCode').attr("disabled", "disabled");
			$('#sBrand').attr("disabled", "disabled");
			$('#sForm').attr("disabled", "disabled");
			$('#sStyle').attr("disabled", "disabled");
			$('#cProdLine').removeAttr("disabled", "disabled");

			//Clear text boxes and selections;
			$('#sBrand').val(0);
			$('#sForm').val(0);
			$('#sStyle').val(0);
			//$('#cProdLine').val(0);
			$( "#txtProdID" ).val("");
			$('#txtProdCode').val("");
			$( "#txtProdCodeDescription" ).val("");


		}else if (scSection.val() == 3){
			$('#txtProdCode').attr("disabled", "disabled");
			$('#sBrand').removeAttr("disabled", "disabled");
			$('#sForm').attr("disabled", "disabled");
			$('#sStyle').attr("disabled", "disabled");
			//$('#sPMG').attr("disabled", "disabled");
			$('#cProdLine').attr("disabled", "disabled");

			//Clear text boxes and selections;
			//$('#sBrand').val(0);
			$('#sForm').val(0);
			$('#sStyle').val(0);
			$('#cProdLine').val(0);
			$( "#txtProdID" ).val("");
			$('#txtProdCode').val("");
			$( "#txtProdCodeDescription" ).val("");

		}else if (scSection.val() == 4){
			$('#txtProdCode').attr("disabled", "disabled");
			$('#txtProdCode').val("");
			$( "#txtProdCodeDescription" ).val("");
			$( "#txtProdID" ).val("");
			$('#sBrand').attr("disabled", "disabled");
			$('#sForm').removeAttr("disabled", "disabled");
			$('#sStyle').attr("disabled", "disabled");
			//$('#sPMG').attr("disabled", "disabled");
			$('#cProdLine').attr("disabled", "disabled");

			//Clear text boxes and selections;
			$('#sBrand').val(0);
			//$('#sForm').val(0);
			$('#sStyle').val(0);
			$('#cProdLine').val(0);
			$( "#txtProdID" ).val("");
			$('#txtProdCode').val("");
			$( "#txtProdCodeDescription" ).val("");

		}else if (scSection.val() == 5){
			$('#txtProdCode').attr("disabled", "disabled");
			$('#sBrand').attr("disabled", "disabled");
			$('#sForm').attr("disabled", "disabled");
			$('#sStyle').removeAttr("disabled", "disabled");
			//$('#sPMG').attr("disabled", "disabled");
			$('#cProdLine').attr("disabled", "disabled");

			//Clear text boxes and selections;
			$('#sBrand').val(0);
			$('#sForm').val(0);
			//$('#sStyle').val(0);
			$('#cProdLine').val(0);
			$( "#txtProdID" ).val("");
			$('#txtProdCode').val("");
			$( "#txtProdCodeDescription" ).val("");

		//}else if (scSection.val() == 6){
		//	$('#txtProdCode').attr("disabled", "disabled");
		//	$('#txtProdCode').val("");
		//	$( "#txtProdCodeDescription" ).val("");
		//	$( "#txtProdID" ).val("");
		//	$('#sBrand').attr("disabled", "disabled");
		//	$('#sForm').attr("disabled", "disabled");
		//	$('#sStyle').attr("disabled", "disabled");
		//	$('#sPMG').removeAttr("disabled", "disabled");
		//	$('#cProdLine').attr("disabled", "disabled");
		}else{
			$('#txtProdCode').attr("disabled", "disabled");
			$('#sBrand').attr("disabled", "disabled");
			$('#sForm').attr("disabled", "disabled");
			$('#sStyle').attr("disabled", "disabled");
			//$('#sPMG').attr("disabled", "disabled");
			$('#cProdLine').attr("disabled", "disabled");

			//Clear text boxes and selections;
			$('#sBrand').val(0);
			$('#sForm').val(0);
			$('#sStyle').val(0);
			$('#cProdLine').val(0);
			$( "#txtProdID" ).val("");
			$('#txtProdCode').val("");
			$( "#txtProdCodeDescription" ).val("");


		};
	});
//End Validation BuyIn

//Validation for Code
	 $( "#txtProdCode" ).autocomplete({
		 source:'includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				$( "#txtProdCode" ).val( ui.item.label);
				$( "#txtProdCodeDescription" ).val( ui.item.ProductName);
				$( "#txtProdID" ).val( ui.item.ProductID);
			return false;
		}
	 }).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( ul );
		};


//AutoCompleter
	$('#txtEProdCode1').autocomplete({
		source:'includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				$( "#txtEProdCode1").val( ui.item.label);
				$( "#txtEProdDesc1").val( ui.item.ProductName);
				$( "#hEProdID1").val( ui.item.ProductID);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( ul );
	};

//End for Code

//Load BuyIn Requirements Whithout Refresh the Page.
       //$('#buyinrequirements').load('includes/jxloyaltybuyinrequirements.php');  // You get the moule

});
//Validation From PromoCode
function ajaxVal(e)
{
	var Code 		= $('#txtPromoCode');
	var PromoTitle 	= $('#txtPromoTitle');

	if (e.keyCode == 13 || e.keyCode == 9) {
			$.ajax({
						type: 'post',
						dataType: 'json',
						data: { PromoCode: Code.val() },
						url: 'includes/jxloyaltyvalidation.php',
						success: function( response ) {
							if(response.message == 'true'){
								alert(response.error_msg)
								Code.val("");
								//PromoTitle.focus();
							}else{
								$('#ajaxvalidation').hide();
								Code.attr("readonly", "readonly");
								PromoTitle.removeAttr("disabled", "disabled");
								$('#txtPromoTitle').focus();
							}
						}
				})

	return false;
	}
}

$.fn.CreateAutocomplete = function(count){

	$(this).autocomplete({
		source:'includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				$( "#txtEProdCode"+count+"").val( ui.item.label);
				$( "#txtEProdDesc"+count+"").val( ui.item.ProductName);
				$( "#hEProdID"+count+"").val( ui.item.ProductID);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( li, item ) {
		return $( "<li></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( li );
	};

};
function addRow(e, cnt)
{
	var count = 1 + cnt;
		if(e.keyCode == 13){
			if($("#txtEProdCode"+cnt+"").val().length != 0){
				var row = "<tr align=center' class = 'remove"+count+"'><td width='8%' height='20' class='borderBR'><div align='center' id = '"+count+"' class='padl5'>"+count+"</div></td><td><div align='left' class='padl5'><input type = 'text' value = '' name = 'txtEProdCode"+count+"' class='txtfield' style='width: 80%' id ='txtEProdCode"+count+"'><span id='indicatorE1' style='display: none'><img src='images/ajax-loader.gif' alt='Working...' /></span><div id='prod_choicesE1' class='autocomplete' style='display:none'></div><input name='hEProdID["+count+"]' type='hidden' id='hEProdID"+count+"' value='' /></div></td><td width='31%' height='20' class='borderBR'><div align='left' class='padl5'><input name='txtEProdDesc"+count+"' type='text' class='txtfield' id='txtEProdDesc"+count+"' readonly = 'yes'style='width: 95%'  /></div></td><td width='12%' height='20' class='borderBR'><div align='center'><input name='txtPoints"+count+"' onkeyup='javascript:RemoveInvalidChars(txtPoints"+count+")' type='text' class='txtfield' id='txtPoints"+count+"'  value='' style='width: 50%; text-align:right' onKeyPress='return addRow(event,"+count+")'/></div></td><td width='20%' height='20' class='borderBR'><div align='center'><input name='btnRemove' type='button' class='btn' value='Remove' onclick='removeRow("+count+")'></div></td></tr>";
						$("#dynamicTable").append(row);
						$('#txtEProdCode'+cnt+'').attr('readonly','readonly');
						$('#txtEProdCode'+count+'').focus();
						$('#txtEProdCode'+count+'').CreateAutocomplete(count);
						$('#entitlementcnt').val(count);
						return false;
			}else{
						alert('Fillup All Form');
						$('#txtEProdCode'+cnt+'').focus();
						$('#txtPoints'+cnt+'').val("");
						return false;
			}
		}
}

function removeRow(cnt)
{
	if(confirm("are you sure what to remove this entitlement?")==true){
		$('.remove'+cnt+'').remove();
	}
}

function confirmSave()
{

	var err = 0;
	var counter = $('#entitlementcnt').val();
	for(i=1; i <= counter; i++)
	{
		if($("#txtEProdCode" + i).val() == ""){
			err++;
		}
		if($("#txtPoints" + i).val() == ""){
			err++;
		}
	}

	var s_date = Date.parse($('#txtBuyinReqSetStartDate').val());
    var sdate = new Date(s_date);
    var e_date = Date.parse($('#txtBuyinReqSetEndDate').val());
    var edate = new Date(e_date);

	if(sdate > edate){
		alert("End date should be the same or later than Start date.");
		return false;
	}

	var a = Date.parse($('#txtEntitlemntSetStartDate').val());
    var b = Date.parse($('#txtSetentitlementEndDate').val());
    var c = new Date(a);
    var d = new Date(b);

	var now = new Date();
	var now_day = now.getDate();
	var now_month = now.getMonth() + 1;
	var now_year = now.getFullYear();
	var now_date = now_month + "/" + now_day + "/" + now_year;

	if(c > d){
		alert("End date should be the same or later than Start date.");
		return false;
	}

	if(getDateObject($('#txtBuyinReqSetStartDate').val(), "/") < getDateObject(now_date, "/")){
		alert("Start date should be current or future date.");
		return false;
	}

	if(getDateObject($('#txtEntitlemntSetStartDate').val(), "/") < getDateObject(now_date, "/")){
		alert("Start date should be current or future date.");
		return false;
	}

		if(err == 0){
		 if (confirm('"Are you sure want to Save this Transaction?') == false){
			return false;
		 }else{

		 		$.ajax({
					type: 'post',
					dataType: 'json',
					data: $("form").serialize(),
					url: 'includes/scLoyaltyPromo.php?btnSave=save',
					success: function(response){
						if(response.Result == "Done"){
							alert('Save Successful')
							window.location.assign("index.php?pageid=170.1");
						}
					}
				});
		 }
			return false;
		}else{
			alert('Please Complete Entitlement Form or Remove Blank Form');
			return false;
		}
}
function confirmAdd()
{
var counter 	 = 0;
	var PromoCode	 = $("#txtPromoCode").val();
	var PromoTitle 	 = $("#txtPromoTitle").val();
	var StartDate 	 = $("#txtStartDate").val();
	var EndDate 	 = $("#txtEndDate").val();
	var DateRange 	 = $("#txtDateRange").val();
	var PReqtType 	 = $("#PReqtType").val();
	var Minimum 	 = $("#txtMinimum").val();
	var Points 		 = $("#txtPoints").val();
	var SetStartDate = $("#txtSetStartDate").val();
	var SetEndDate 	 = $("#txtSetEndDate").val();
	var Criteria 	 = $("#sCriteria").val();
	var error_msg = "";
	if(PromoCode == ""){
		error_msg += "Promo Code required. \n";
		counter++;
	}
	if(PromoTitle == ""){
		error_msg += "Promo Title required. \n";
		counter++;
	}
	if(StartDate == ""){
		error_msg += "Start Date required. \n";
		counter++;
	}
	if(EndDate == ""){
		error_msg += "End Date required. \n";
		counter++;
	}
	if(DateRange == ""){

		error_msg += "Date Range required. \n";
		counter++;
	}
	if(PReqtType == ""){
		error_msg += "Purchase Requirement Type required. \n";
		counter++;
	}
	if(Minimum == ""){
		error_msg += "Minimum Value required. \n";
		counter++;
	}
	if(Points == ""){
		error_msg += "Points required. \n";
		counter++;
	}

	if(Criteria = 0){
		error_msg += "Criteria required. \n";
		counter++;
	}

	if (counter > 0){
		alert(error_msg);
		return false;
	}

	var s_date = Date.parse($('#txtBuyinReqSetStartDate').val());
    var sdate = new Date(s_date);
    var e_date = Date.parse($('#txtBuyinReqSetEndDate').val());
    var edate = new Date(e_date);

	if(sdate > edate){
		alert("End date should be the same or later than Start date.");
		return false;
	}
	var now = new Date();
	var now_day = now.getDate();
	var now_month = now.getMonth() + 1;
	var now_year = now.getFullYear();
	var now_date = now_month + "/" + now_day + "/" + now_year;
	if(getDateObject($('#txtBuyinReqSetStartDate').val(), "/") < getDateObject(now_date, "/")){
		alert("Start date should be current or future date.");
		return false;
	}



	if(counter == 0){
		if (confirm('Are you sure you want to add this Buy-in requirement?') == false){
			return false;
		}else{
			var header = '';
			header += '<tr align="center">';
			header += '<td width="10%" height="25" class="txtpallete borderBR"><div align="center">Line No.</div></td>';
			header += '<td width="27%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Description</div></td>';
			header += '<td width="10%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>';
			header += '<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>';
			header += '<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Points</div></td>';
			header += '<td width="15%" height="25" class="txtpallete borderBR"><div align="center" class="padr5">Action</div></td>';
			header += '</tr>';

			$.ajax({
				type: 'post',
				dataType: 'json',
				data:	$("form").serialize(),
				url: 'includes/scLoyaltyPromo.php?btnAdd=btnAdd',
				success: function( response ){
				var ctr = 1;
					for(var i=0; i < response.length; i++){
						header += '<tr align="center">';
						header += '<td width="5%"  height="25" class="borderBR"><div align="center">'+ctr+'</div></td>';
						header += '<td width="10%" height="25" class=" borderBR"><div align="center">'+response[i].Description+'</div></td>';
						header += '<td width="32%" height="20" class=" borderBR"><div align="left" class="padl5">'+(response[i].Criteria=='1'?'Quantity':'Amount')+'</div></td>';
						header += '<td width="10%" height="20" class=" borderBR"><div align="center">'+(response[i].Criteria=='1'?response[i].MinQty : response[i].MinAmt)+'</div></td>';
						header += '<td width="10%" height="20" class=" borderBR"><div align="center">'+response[i].Points+'</div></td>';
						header += '<td width="10%" height="20" class=" borderBR"><div align="center">';
						header += '<input type = "submit" class = "btn" value = "Delete" onclick = "return confirmRemove(\''+response[i].ID+'\',\''+response[i].SelectionTyp+'\');"></div></td>';
						header += '</tr>';
						if(response[i].SelectionTyp != 'ProductID'){
							$("#btnAdd").attr("disabled","disabled");
						}else{
							$("#btnAdd").removeAttr("disabled","disabled");
						}
					ctr++;
					}
					$('#buyinrequirements').html(header);
					$("#btnSave").removeAttr("disabled","disabled");
				}
			});
			return false;
		}
	}else{
			return false;
	}
}

function confirmRemove(xID,SelectionType)
{
	if(confirm("are you sure want to delete this Buyin-Requirement?")==false){
		return false;
	}else{
		var header = '';
			header += '<tr align="center">';
			header += '<td width="10%" height="25" class="txtpallete borderBR"><div align="center">Line No.</div></td>';
			header += '<td width="27%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Description</div></td>';
			header += '<td width="10%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>';
			header += '<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>';
			header += '<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Points</div></td>';
			header += '<td width="15%" height="25" class="txtpallete borderBR"><div align="center" class="padr5">Action</div></td>';
			header += '</tr>';

			$.ajax({
				type: 'post',
				dataType: 'json',
				data:	{xIDx: xID, xSelectionType: SelectionType},
				url: 'includes/scLoyaltyPromo.php?btndelete=btndelete',
				success: function( response ){
				var ctr = 1;
					for(var i=0; i < response.length; i++){
						header += '<tr align="center">';
						header += '<td width="5%"  height="25" class="borderBR"><div align="center">'+ctr+'</div></td>';
						header += '<td width="10%" height="25" class=" borderBR"><div align="center">'+response[i].Description+'</div></td>';
						header += '<td width="32%" height="20" class=" borderBR"><div align="left" class="padl5">'+(response[i].Criteria=='1'?'Quantity':'Amount')+'</div></td>';
						header += '<td width="10%" height="20" class=" borderBR"><div align="center">'+(response[i].Criteria=='1'? response[i].MinQty : response[i].MinAmt)+'</div></td>';
						header += '<td width="10%" height="20" class=" borderBR"><div align="center">'+response[i].Points+'</div></td>';
						header += '<input type = "submit" class = "btn" value = "Delete" onclick = "return confirmRemove(\''+response[i].ID+'\',\''+response[i].SelectionTyp+'\');"></div></td>';
						header += '</tr>';
					ctr++;
					}
					$('#buyinrequirements').html(header);
				}
			});
			if(SelectionType != "ProductID"){
				$("#btnAdd").removeAttr("disabled","disabled");
			}
	}
	return false;
}

function Cancel()
{
	if (confirm('Are you sure you want to Cancel?') == false){
			return false;
	}else{
		window.location.assign("index.php?pageid=170");
		return true;
	}
	return false;
}


function PopUP(ID)
{
	var popuppage = "pages/leaderlist/viewLoyaltyLink.php?id=" + ID;
	window.open(popuppage,'Popup','toolbar=no, location=no,status=no,menubar=no,scrollbars=yes,resizable=no, width=1500,height=700,left=430,top=23');
	return false;
}

function form_validation()
{
	//alert('upload under construction');
	//return false;
}

function NextField(e)
{


	var StartDate 				= $('#txtStartDate');
	var EndDate 				= $('#txtEndDate');
	var DateRange 				= $('#txtDateRange');
	var PReqtType 				= $('#PReqtType');
	var Section 				= $('#sSection');
	var Criteria 				= $('#sCriteria');
	var Points					= $('#txtPoints');
	var SetStartDate 			= $('#txtSetStartDate');
	var SetEndDate 	 			= $('#txtSetEndDate');
	var MinimumVal 				= $('#txtMinimum');
	var NonGSU 					= $('#txtNonGSU');
	var DirectGSU 				= $('#txtDirectGSU');
	var IndirectGSU 			= $('#txtIndirectGSU');
	var NetOfCPI 				= $('#txtNetOfCPI');
	var BuyinReqSetStartDate	= $('#txtBuyinReqSetStartDate');
	var BuyinReqSetEndDate		= $('#txtBuyinReqSetEndDate');
	var EntitlemntSetStartDate	= $('#txtEntitlemntSetStartDate');
	var SetentitlementEndDate	= $('#txtSetentitlementEndDate');
	var EProdCode1				= $("#txtEProdCode1");
	var Points1			        = $("#txtPoints1");

	if (e.keyCode == 13 || e.keyCode == 9) {
		var cls = "";
			if($.trim($("#txtPromoTitle").val()) != ""){
				StartDate.removeAttr("disabled","disabled");
				EndDate.removeAttr("disabled","disabled");
				DateRange.removeAttr("disabled","disabled");
				PReqtType.removeAttr("disabled","disabled");
				Section.removeAttr("disabled","disabled");
				Criteria.removeAttr("disabled","disabled");
				Points.removeAttr("disabled","disabled");
				SetStartDate.removeAttr("disabled","disabled");
				SetEndDate.removeAttr("disabled","disabled");
				MinimumVal.removeAttr("disabled","disabled");
				NonGSU.removeAttr("disabled","disabled");
				DirectGSU.removeAttr("disabled","disabled");
				IndirectGSU.removeAttr("disabled","disabled");
				NetOfCPI.removeAttr("disabled","disabled");
				BuyinReqSetStartDate.removeAttr("disabled","disabled");
				BuyinReqSetEndDate.removeAttr("disabled","disabled");
				EntitlemntSetStartDate.removeAttr("disabled","disabled");
				SetentitlementEndDate.removeAttr("disabled","disabled");
				$(".buttonCalendar").removeAttr("disabled","disabled");
				EProdCode1.removeAttr("disabled","disabled");
				Points1.removeAttr("disabled","disabled");
				$("#PReqtType").focus();
				return false;
			}else{
				StartDate.attr("disabled","disabled");
				EndDate.attr("disabled","disabled");
				DateRange.attr("disabled","disabled");
				PReqtType.attr("disabled","disabled");
				Section.attr("disabled","disabled");
				Criteria.attr("disabled","disabled");
				Points.attr("disabled","disabled");
				SetStartDate.attr("disabled","disabled");
				SetEndDate.attr("disabled","disabled");
				MinimumVal.attr("disabled","disabled");
				NonGSU.attr("disabled","disabled");
				DirectGSU.attr("disabled","disabled");
				IndirectGSU.attr("disabled","disabled");
				NetOfCPI.attr("disabled","disabled");
				BuyinReqSetStartDate.attr("disabled","disabled");
				BuyinReqSetEndDate.attr("disabled","disabled");
				EntitlemntSetStartDate.attr("disabled","disabled");
				SetentitlementEndDate.attr("disabled","disabled");
				$(".buttonCalendar").attr("disabled","disabled");
				EProdCode1.attr("disabled","disabled");
				Points1.attr("disabled","disabled");



				$("#txtPromoTitle").val(cls);
				$("#PReqtType").attr("disabled","disabled");
				$(".btn").attr("disabled","disabled");
				alert("Promo Description required.");
				$("#txtPromoTitle").focus();
				$(".btn").removeAttr("disabled","disabled");
			}
	}
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