$(document).ready(function(){
	//buyin_add();
	
var BuyinSelection = $("#BuyinSelection");

	BuyinSelection.change(function(){
		if(BuyinSelection.val() == 1){
			$('#ProdLine').attr("disabled", "disabled");
			$('#txtBRItemCode').val("");
			$('#txtBRItemDesc').val("");
			$('#ProdLine').val(0);
			$("#txtPromoCodePromo").val("");
			$("#txtPromoCodePromo").attr("disabled","disabled");
			$('#txtBRItemCode').attr("disabled", "disabled");
			$('#txtBRItemDesc').attr("disabled", "disabled");
			$("#buyincriteria").removeAttr("disabled","disabled");
			$("#txtBRMinVal").removeAttr("disabled","disabled");
			$("#txtBuyinSetStartDate").removeAttr("disabled","disabled");
			$("#txtBuyinSetEndDate").removeAttr("disabled","disabled");
		}else if (BuyinSelection.val() == 2){
			$('#ProdLine').removeAttr("disabled", "disabled");
			$('#txtBRItemCode').val("");
			$('#txtBRItemDesc').val("");
			$('#ProdLine').val(0);
			$("#buyincriteria").val(0)
			$("#txtPromoCodePromo").val("");
			$("#txtPromoCodePromo").attr("disabled","disabled");
			$('#txtBRItemCode').attr("disabled", "disabled");
			$('#txtBRItemDesc').attr("disabled", "disabled");
			$("#buyincriteria").removeAttr("disabled","disabled");
			$("#txtBRMinVal").removeAttr("disabled","disabled");
			$("#txtBuyinSetStartDate").removeAttr("disabled","disabled");
			$("#txtBuyinSetEndDate").removeAttr("disabled","disabled");
	
		}else if (BuyinSelection.val() == 3){
			$('#ProdLine').attr("disabled", "disabled");
			$('#ProdLine').val(0);
			$('#txtBRItemCode').val("");
			$('#txtBRItemDesc').val("");
			$("#txtPromoCodePromo").val("");
			$("#buyincriteria").val(0)
			$("#txtPromoCodePromo").attr("disabled","disabled");
			$('#txtBRItemCode').removeAttr("disabled", "disabled");
			$('#txtBRItemDesc').removeAttr("disabled", "disabled");
			$("#buyincriteria").removeAttr("disabled","disabled");
			$("#txtBRMinVal").removeAttr("disabled","disabled");
			$("#txtBuyinSetStartDate").removeAttr("disabled","disabled");
			$("#txtBuyinSetEndDate").removeAttr("disabled","disabled");
		
		}else if (BuyinSelection.val()==4 || BuyinSelection.val()==5){
			$('#ProdLine').attr("disabled", "disabled");
			$('#ProdLine').val(0);
			$('#txtBRItemCode').val("");
			$('#txtBRItemDesc').val("");
			$("#txtPromoCodePromo").val("");
			$("#buyincriteria").val(2);
			$("#txtPromoCodePromo").removeAttr("disabled","disabled");
			$('#txtBRItemCode').attr("disabled", "disabled");
			$('#txtBRItemDesc').attr("disabled", "disabled");
			$("#buyincriteria").attr("disabled","disabled");
			$("#txtBRMinVal").removeAttr("disabled","disabled");
			$("#txtBuyinSetStartDate").attr("disabled","disabled");
			$("#txtBuyinSetEndDate").attr("disabled","disabled");
			
		}else{
			$('#txtBRItemCode').val("");
			$('#txtBRItemDesc').val("");
			$('#ProdLine').val(0);
			$("#txtPromoCodePromo").val("");
			$("#buyincriteria").val(0);
			$("#txtPromoCodePromo").attr("disabled","disabled");
			$('#ProdLine').attr("disabled", "disabled");
			$('#txtBRItemCode').attr("disabled", "disabled");
			$('#txtBRItemDesc').attr("disabled", "disabled");
			$("#buyincriteria").attr("disabled","disabled");
			$("#txtBRMinVal").attr("disabled","disabled");
			$("#txtBuyinSetStartDate").attr("disabled","disabled");
			$("#txtBuyinSetEndDate").attr("disabled","disabled");
	
		}
	});
	
	//Buyin
	$('#txtBRItemCode').autocomplete({
		source:'includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				$( "#txtBRItemCode").val( ui.item.label);
				$( "#txtBRItemDesc").val( ui.item.ProductName);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( ul );
	};	
	
	//Entitlement
	$('#txtEPromoCode').autocomplete({
		source:'includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				$( "#txtEPromoCode").val( ui.item.label);
				$( "#txtEProdDesc").val( ui.item.ProductName);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( ul );
	};	
	

});


function add(validation)
{
	var err_msg = "", err_cnt = 0;
	if(validation == 1){
			var BuyinSelection 		 = $("#BuyinSelection");
			var buyincriteria 		 = $("#buyincriteria");
			var BRMinVal 			 = $("#txtBRMinVal");
			var BuyinSetStartDate 	 = $("#txtBuyinSetStartDate");
			var BuyinSetEndDate 	 = $("#txtBuyinSetEndDate");
			
			if(BuyinSelection.val()==0){
				err_msg += "*Buyin Selection required. \n";
				err_cnt++;
			}
			
			if(BuyinSelection.val()==2){
				if($("#ProdLine").val() == 0){
					err_msg += "*Buyin Product Line Selection required. \n";
					err_cnt++;
				}
			}
			
			if(BuyinSelection.val()==3){
				if($("#txtBRItemCode").val() == 0){
					err_msg += "*Buyin Item Code required. \n";
					err_cnt++;
				}
			}
			
			if(buyincriteria.val()==0){
				err_msg += "*Buyin Criteria required. \n";
				err_cnt++;
			}
			
			if(BRMinVal.val() == ""){
				err_msg += "*Buyin Minimum value required. \n";
				err_cnt++;
			}
			if(BuyinSetStartDate.val() == ""){
				err_msg += "*Buyin Start Date required. \n";
				err_cnt++;
			}
				if(BuyinSetStartDate.val() == ""){
				err_msg += "*Buyin End Date required. \n";
				err_cnt++;
			}
			
			var s_date = Date.parse($('#txtBuyinSetStartDate').val());
			var sdate = new Date(s_date); 
			var e_date = Date.parse($('#txtBuyinSetEndDate').val());
			var edate = new Date(e_date);
			
			var now = new Date();
			var now_day = now.getDate();
			var now_month = now.getMonth() + 1;
			var now_year = now.getFullYear();
			var now_date = now_month + "/" + now_day + "/" + now_year;
			
			
			if(sdate > edate){			
				err_msg += "Buy-in Requirements End date should be the same or later than Start date.";
				err_cnt++;
			}
			
		
			
			if(getDateObject($('#txtBuyinSetStartDate').val(), "/") < getDateObject(now_date, "/")){			
				err_msg += "Start date should be current or future date.";
				err_cnt++;
	
			}
			
			if(err_cnt > 0){
				alert(err_msg);
				return false;
			}else{
							var html  = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR">';
								html += '<div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR">';
								html += '<div align="left" class="padl5">Selection</div></td><td width="10%" height="20" class="txtpallete borderBR">';
								html += '<div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR">';
								html += '<div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR">';
								html += '<div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR">';
								html += '<div align="right" class="padr5">End Date</div></td><td width="7%" height="20" class="txtpallete borderBR">'
								html += '<div align="right" class="padr5">Action</div></td></tr>';
							var ctr = 1;
					
							if(confirm('are you sure want to add buyin requirements?')==true){
											$.ajax({
													type: 'post',
													dataType: 'json',
													data:	$("form").serialize(),
													url: 'includes/ajaxbuyinbridging.php?request=buyin_add',
													success: function(resp){
														if(resp['validate'].result == 'true'){
															for(var x = 0; resp['fetch_data'].length > x; x++){
																html += '<tr align = "center" height="25px">';
																html += '<td height="20" class="borderBR">'+ ctr +'</td>';
																html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].ProductDesc+'</td>';
																html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].Criteria+'</td>';
																html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].Minimum+'</td>';
																html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].StartDate+'</td>';
																html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].EndDate+'</td>';
																html += '<td height="20" class="borderBR"><input class="btn" type="submit" onclick="return confirmDelete(1,\''+resp['fetch_data'][x].BuyinID+'\');" value="Delete" name="btnDelete"></td>';
																html += '</tr>';
																ctr++;
															}
															$("#TBLbuyinrequirements").html(html);
															$("#TBIEntitlement").remove();
															//$('#btnSave').removeAttr("disabled", "disabled");
														}
													}
											});
											return false;
									}else{
										
										return false;
									}
					}
					
	}else{
	
			err_msg = "", err_cnt = 0;
									
			if($("#txtEPromoCode").val() == ""){
				err_msg += "*Entitlement Item required. \n";
				err_cnt++;
			}
			if($("#EntitleCriteria").val() == 0){
				err_msg += "Entitlement Criteria selection required. \n"
				err_cnt++;
			}
			if($("#txtEMinVal").val()	 == ""){
				err_msg += "Entitlement Minimum value required. \n";
				err_cnt++;
			}
			
			if(err_cnt > 0){
				alert(err_msg);
				return false;
			}else{
			
			
									var html1  = '<tr align="center">';
										html1 += '<td width="5%" height="20" class="txtpallete borderBR">';
										html1 += 	'<div align="left" class="padl5">No.</div>';
										html1 += '</td>';
										html1 += '<td width="20%" height="20" class="txtpallete borderBR">';
										html1 += 	'<div align="left" class="padl5">Item Description</div>';
										html1 += '</td>';
										html1 += '<td width="10%" height="20" class="txtpallete borderBR">';
										html1 += 	'<div align="center" class="padl5">Criteria</div>';
										html1 += '</td>';
										html1 += '<td width="7%" height="20" class="txtpallete borderBR">';
										html1 += 	'<div align="right" class="padr5">Minimum</div>';
										html1 += '</td>';
										html1 += '<td width="10%" height="20" class="txtpallete borderBR">';
										html1 += 	'<div align="right" class="padr5">Start Date</div>';
										html1 += '</td>';
										html1 += '<td width="10%" height="20" class="txtpallete borderBR">';
										html1 += 	'<div align="right" class="padr5">End Date</div>';
										html1 += '</td>';
										html1 += '<td width="10%" height="20" class="txtpallete borderBR">';
										html1 += 	'<div align="right" class="padr5">Action</div>';
										html1 += '</td>';
										html1 += '</tr>';
									var ctrr = 1;
					
									if(confirm('are you sure want to add entitlement requirements?')==true){
										$.ajax({
												type: 'post',
												dataType: 'json',
												data:	$("form").serialize(),
												url: 'includes/ajaxbuyinbridging.php?request=ent_add',
												success: function(resp){
													if(resp['validate'].result == 'true'){
														//alert(resp['fetch_data'].length);
														for(var y = 0; resp['fetch_data'].length > y; y++){
															html1 += '<tr align = "center" height="25px">';
															html1 += '<td height="20" class="borderBR">'+ ctrr +'</td>';
															html1 += '<td height="20" class="borderBR">'+ resp['fetch_data'][y].ProductDesc+'</td>';
															html1 += '<td height="20" class="borderBR">'+ resp['fetch_data'][y].Criteria+'</td>';
															html1 += '<td height="20" class="borderBR">'+ resp['fetch_data'][y].Minimum+'</td>';
															html1 += '<td height="20" class="borderBR">'+ resp['fetch_data'][y].StartDate+'</td>';
															html1 += '<td height="20" class="borderBR">'+ resp['fetch_data'][y].EndDate+'</td>';
															html1 += '<td height="20" class="borderBR"><input class="btn" type="submit" onclick="return confirmDelete(2,\''+resp['fetch_data'][y].BuyinID+'\');" value="Delete" name="btnDelete"></td>';
															html1 += '</tr>';
															ctrr++;
														}
														$('#tblentitlement').html(html1);
														$('#btnSave').removeAttr("disabled", "disabled");
													}
												}
										});
										return false;
									}else{
										
										return false;
									}
			}
	}
}

function validatePromoCode(e)
{
	var clear = "";
	var PromoCode = $('#txtPromoCode');	
	//validation 
	if (e.keyCode == 13 || e.keyCode == 9) {
		if(PromoCode.val() != ""){
				jQuery.ajax({
					type: 'post',
					dataType: 'json',
					data: { xvalidate: 'validate', PromoCode: PromoCode.val() },
					url: 'includes/ajaxincentivesBuyinandEntitlement.php',
					success: function( response ) {
							if(response.results == 1){
								var clr = '';
								alert(response.information);
								PromoCode.val(clr);
								PromoCode.focus();
							}else{
								//alert(response.information);
								var PromoDesc = $('#txtPromoDesc');
								var IncentiveType = $('#inctvtype');
								PromoDesc.removeAttr("disabled", "disabled");
								//IncentiveType.removeAttr("disabled", "disabled");
								PromoDesc.focus();
							}
					}
				})
		}else{
		}
		return false;
	}
}

function NextField(e)
{
	if (e.keyCode == 13 || e.keyCode == 9){
		var IncentiveType = $('#MechType');
		var PromoDesc = $('#txtPromoDesc');
		var cls = '';
		
		if($("#txtPromoDesc").val() != ""){
				IncentiveType.removeAttr("disabled", "disabled");
				IncentiveType.focus();
				all_fieds_enabled();
				e.preventDefault();
				return false;
		}else{
			$("#btnCancel").attr("disabled","disabled");
			alert("Promo Description Required.");
			$("#txtPromoDesc").focus();
			$("#btnCancel").removeAttr("disabled", "disabled");
		}
		return false;
	}
	
		
	//return false;
	
}

function all_fieds_enabled()
{
			$('#MechType').change(function(){
				if($('#MechType').val() != 0){
						$('#txtStartDate').removeAttr("disabled", "disabled");
						$('#txtEndDate').removeAttr("disabled", "disabled");
						$('#NoCPI').removeAttr("disabled", "disabled");
						$('#txtNonGSU').removeAttr("disabled", "disabled");
						$('#txtIndirectGSU').removeAttr("disabled", "disabled");
						$('#txtDirectGSU').removeAttr("disabled", "disabled");
						$('#chckIsPlus').removeAttr("disabled", "disabled");
						$('#BuyinSelection').removeAttr("disabled", "disabled");
						$('#buyincriteria').removeAttr("disabled", "disabled");
						$('#txtBRMinVal').removeAttr("disabled", "disabled");
						$('#txtBuyinSetStartDate').removeAttr("disabled", "disabled");
						$('#txtBuyinSetEndDate').removeAttr("disabled", "disabled");
						$('#txtEPromoCode').removeAttr("disabled", "disabled");
						$('#txtEProdDesc').removeAttr("disabled", "disabled");
						$('#EntitleCriteria').removeAttr("disabled", "disabled");
						$('#txtEMinVal').removeAttr("disabled", "disabled");
						$('#btnAdd_buyin').removeAttr("disabled", "disabled");
						$('#btnAdd_ent').removeAttr("disabled", "disabled");
						$('#btnAdd_buyin').removeAttr("disabled", "disabled");
						
				}else{
						$('#txtStartDate').attr("disabled", "disabled");
						$('#txtEndDate').attr("disabled", "disabled");
						$('#NoCPI').attr("disabled", "disabled");
						$('#txtNonGSU').attr("disabled", "disabled");
						$('#txtIndirectGSU').attr("disabled", "disabled");
						$('#txtDirectGSU').attr("disabled", "disabled");
						$('#chckIsPlus').attr("disabled", "disabled");
						$('#BuyinSelection').attr("disabled", "disabled");
						$('#buyincriteria').attr("disabled", "disabled");
						$('#txtBRMinVal').attr("disabled", "disabled");
						$('#txtBuyinSetStartDate').attr("disabled", "disabled");
						$('#txtBuyinSetEndDate').attr("disabled", "disabled");
						$('#txtEPromoCode').attr("disabled", "disabled");
						$('#txtEProdDesc').attr("disabled", "disabled");
						$('#EntitleCriteria').attr("disabled", "disabled");
						$('#txtEMinVal').attr("disabled", "disabled");
						$('#btnAdd_buyin').attr("disabled", "disabled");
						$('#btnAdd_ent').attr("disabled", "disabled");
				}
			
			})
			
}

function ConfirmSave(validate)
{
	if($("#txtPromoDesc").val() == ""){
		alert('Promo Description required.');
		$("#txtPromoDesc").focus();
		return false;
	}

	else if($("#MechType").val()== 0){
		alert('Mechanics Type required.');
		return false;
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
	
	if(getDateObject($('#txtStartDate').val(), "/") < getDateObject(now_date, "/")){			
		alert("Start date should be current or future date.");
		return false;
	}
	
	//javascript:RemoveInvalidChars(txtMaxAvail1);
	if(sdate > edate){			
		alert("End date should be the same or later than Start date.");
		return false;
	}
	
	if (confirm('Are you sure you want to Save this Incentives Promo Buy-in and Entitlement?') == false){
				return false;
	}else{
		if (validate == 1){
			var xdata = {
							xPromoCode: 	$('#txtPromoCode').val(),
							xPromoDesc: 	$('#txtPromoDesc').val(), 
							xmechtype: 	 	$('#MechType').val(),
							xStartDate: 	$('#txtStartDate').val(),
							xEndDate: 		$('#txtEndDate').val(),
							xNoCPI:			$('#NoCPI').val(),
							xdirectGSU:		$('#txtDirectGSU').val(),
							xNonGSU:		$('#txtNonGSU').val(),
							xIndirectGSU:	$('#txtIndirectGSU').val(),
							xchckIsPlus:	CheckBox($('#chckIsPlus').is(":checked"))
						 };
			$.ajax({
							type: 'post',
							dataType: 'json',
							data: xdata,
							url: 'includes/ajaxbuyinbridging.php?request=save_file',
							success: function( response ){
								if(response.success == 1){
									alert(response.result);
									window.location.assign("index.php?pageid=201");
									return true;
								}else{
									alert('Save Unsuccessful');
								}
							}
			});
				return false;
			}
	}
}
function CheckBox(bool){
	if(bool)
		return 1;
		return 0;
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

function confirmDelete(validate,xID){
	
		var html  = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR">';
			html += '<div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR">';
			html += '<div align="left" class="padl5">Selection</div></td><td width="10%" height="20" class="txtpallete borderBR">';
			html += '<div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR">';
			html += '<div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR">';
			html += '<div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR">';
			html += '<div align="right" class="padr5">End Date</div></td><td width="7%" height="20" class="txtpallete borderBR">'
			html += '<div align="right" class="padr5">Action</div></td></tr>';
		var ctr = 1;
	
	if(validate == 1){
		//delete buyin requirements
		if(confirm('are you sure want to delete buyin requirement?')==false){
			return false;
		}else{
			$.ajax({
				type: 'post',
				url: 'includes/ajaxbuyinbridging.php?request=delete_buyin',
				dataType: 'json',
				data: {'BuyinID': xID},
				success: function(resp){
						if(resp['validate'].result == 'true'){
							for(var x = 0; resp['fetch_data'].length > x; x++){
								html += '<tr align = "center" height="25px">';
								html += '<td height="20" class="borderBR">'+ ctr +'</td>';
								html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].ProductDesc+'</td>';
								html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].Criteria+'</td>';
								html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].Minimum+'</td>';
								html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].StartDate+'</td>';
								html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].EndDate+'</td>';
								html += '<td height="20" class="borderBR"><input class="btn" type="submit" onclick="return confirmDelete(1,\''+resp['fetch_data'][x].BuyinID+'\');" value="Delete" name="btnDelete"></td>';
								html += '</tr>';
								ctr++;
							}
							$("#TBLbuyinrequirements").html(html);
							$("#TBIEntitlement").remove();
							//$('#btnSave').removeAttr("disabled", "disabled");
						}else{
							$("#TBLbuyinrequirements").html(html);
							$("#TBIEntitlement").remove();
						}
				}
				
			});
			
				return false;
		}
	}else{
		//delete entitlement
		//delete buyin requirements
		if(confirm('are you sure want to delete buyin requirement?')==false){
			return false;
		}else{
		
			var html1  = '<tr align="center">';
							html1 += '<td width="5%" height="20" class="txtpallete borderBR">';
							html1 += 	'<div align="left" class="padl5">No.</div>';
							html1 += '</td>';
							html1 += '<td width="20%" height="20" class="txtpallete borderBR">';
							html1 += 	'<div align="left" class="padl5">Item Description</div>';
							html1 += '</td>';
							html1 += '<td width="10%" height="20" class="txtpallete borderBR">';
							html1 += 	'<div align="center" class="padl5">Criteria</div>';
							html1 += '</td>';
							html1 += '<td width="7%" height="20" class="txtpallete borderBR">';
							html1 += 	'<div align="right" class="padr5">Minimum</div>';
							html1 += '</td>';
							html1 += '<td width="10%" height="20" class="txtpallete borderBR">';
							html1 += 	'<div align="right" class="padr5">Start Date</div>';
							html1 += '</td>';
							html1 += '<td width="10%" height="20" class="txtpallete borderBR">';
							html1 += 	'<div align="right" class="padr5">End Date</div>';
							html1 += '</td>';
							html1 += '<td width="10%" height="20" class="txtpallete borderBR">';
							html1 += 	'<div align="right" class="padr5">Action</div>';
							html1 += '</td>';
							html1 += '</tr>';
			var ctrr = 1;
						
			$.ajax({
				type: 'post',
				url: 'includes/ajaxbuyinbridging.php?request=delete_ent',
				dataType: 'json',
				data: {'BuyinID': xID},
				success: function(resp){
						if(resp['validate'].result == 'true'){
							for(var x = 0; resp['fetch_data'].length > x; x++){
								html += '<tr align = "center" height="25px">';
								html += '<td height="20" class="borderBR">'+ ctr +'</td>';
								html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].ProductDesc+'</td>';
								html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].Criteria+'</td>';
								html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].Minimum+'</td>';
								html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].StartDate+'</td>';
								html += '<td height="20" class="borderBR">'+ resp['fetch_data'][x].EndDate+'</td>';
								html += '<td height="20" class="borderBR"><input class="btn" type="submit" onclick="return confirmDelete(1,\''+resp['fetch_data'][x].BuyinID+'\');" value="Delete" name="btnDelete"></td>';
								html += '</tr>';
								ctr++;
							}
							$('#tblentitlement').html(html);
							//$('#btnSave').removeAttr("disabled", "disabled");
						}else{
							$('#tblentitlement').html(html);
						}
				}
			});
		
		return false;
	}
	}
}

function ConfirmCancel(){
	if(confirm('are you sure want to cancel this promo?')==false){
		return false;
	}else{
		window.location.assign("index.php?pageid=201");
		return false;
	}
}

function get_promo(ValidationIncentiveType)
{
	var BuyinSelection	 =  "";
	var PromoCodePromo	 = 	"";
	var	StartDate 		= $("#txtStartDate").val();
	var	EndDate 		= $("#txtEndDate").val();
	
	if(ValidationIncentiveType == 1){
		BuyinSelection  = $("#BuyinSelection").val();
		PromoCodePromo 	= $('#txtPromoCodePromo');
	
	}else{
		
		BuyinSelection  = $("#BuyinSelection1").val();
		PromoCodePromo 	= $('#txtPromoCodePromo1');	
	}
	//auto complete
	PromoCodePromo.autocomplete({
	source:'includes/jxloyaltyvalidation.php?BuyinSelection='+BuyinSelection+'&StartDate='+StartDate+'&EndDate='+EndDate,
		select: function( event, ui ) {
			PromoCodePromo.val( ui.item.Code);
			return false;
	}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.Code + "</strong> - " + item.Description + "</a>" )
			.appendTo( ul );
	};	
	
}