var rAjax = 0;
$(document).ready(function(){

//AutoCompleter

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
	
//AutoCompleter for Marketing brands and Incentives
//AutoCompleter
//PromoBuyin
	$('#txtBRItemCode1').autocomplete({
		source:'includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				$( "#txtBRItemCode1").val( ui.item.label);
				$( "#txtBRItemDesc1").val( ui.item.ProductName);
		return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( ul );
	};	

//entitlement
	$('#txtEPromoCode1').autocomplete({
		source:'includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				$( "#txtEPromoCode1").val( ui.item.label);
				$( "#txtEProdDesc1").val( ui.item.ProductName);
		return false;
		}
	}).data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( ul );
	};	
	

/*Selection Validation*/
var IncentiveType = $("#inctvtype");
//Validation Field Buy In Requirement
	IncentiveType.change(function(){
		if(IncentiveType.val() == 1){
			$('#MechType').removeAttr("disabled", "disabled");
			$('#txtStartDate').removeAttr("disabled", "disabled");
			$('#txtEndDate').removeAttr("disabled", "disabled");
			$('#NoCPI').removeAttr("disabled", "disabled");
			$('#txtNonGSU').removeAttr("disabled", "disabled");
			$('#txtIndirectGSU').removeAttr("disabled", "disabled");
			$('#txtDirectGSU').removeAttr("disabled", "disabled");
			$('#chckIsPlus').removeAttr("disabled", "disabled");
			$('#buyincriteria').removeAttr("disabled", "disabled");
			$('#BuyinSelection').removeAttr("disabled", "disabled");
			$('#txtBRMinVal').removeAttr("disabled", "disabled");
			$('#txtBuyinSetStartDate').removeAttr("disabled", "disabled");
			$('#txtBuyinSetEndDate').removeAttr("disabled", "disabled");
			$('#txtEPromoCode').removeAttr("disabled", "disabled");
			$('#txtEProdDesc').removeAttr("disabled", "disabled");
			$('#EntitleCriteria').removeAttr("disabled", "disabled");
			$('#txtEMinVal').removeAttr("disabled", "disabled");
			$('#btnAdd').removeAttr("disabled", "disabled");

			
			$('#PromoDetails1').show();
			$('#ListPromoDetails1').show();	
			$('#PromoDetails2').hide();
			$('#ListPromoDetails2').hide();	
		}else if(IncentiveType.val() == 2){
			$('#MechType').removeAttr("disabled", "disabled");
			$('#txtStartDate').removeAttr("disabled", "disabled");
			$('#txtEndDate').removeAttr("disabled", "disabled");
			$('#NoCPI').removeAttr("disabled", "disabled");
			$('#txtNonGSU').removeAttr("disabled", "disabled");
			$('#txtIndirectGSU').removeAttr("disabled", "disabled");
			$('#txtDirectGSU').removeAttr("disabled", "disabled");
			$('#chckIsPlus').removeAttr("disabled", "disabled");
			$('#buyincriteria').removeAttr("disabled", "disabled");
			$('#BuyinSelection').removeAttr("disabled", "disabled");
			$('#txtBRMinVal').removeAttr("disabled", "disabled");
			$('#txtBuyinSetStartDate').removeAttr("disabled", "disabled");
			$('#txtBuyinSetEndDate').removeAttr("disabled", "disabled");
			$('#txtEPromoCode').removeAttr("disabled", "disabled");
			$('#txtEProdDesc').removeAttr("disabled", "disabled");
			$('#EntitleCriteria').removeAttr("disabled", "disabled");
			$('#txtEMinVal').removeAttr("disabled", "disabled");
			$('#btnAdd').removeAttr("disabled", "disabled");

			
			$('#PromoDetails1').show();
			$('#ListPromoDetails1').show();	
			$('#PromoDetails2').hide();
			$('#ListPromoDetails2').hide();
		}else if (IncentiveType.val() == 3){
			//alert('under construction');
			$('#MechType').removeAttr("disabled", "disabled");
			$('#txtStartDate').removeAttr("disabled", "disabled");
			$('#txtEndDate').removeAttr("disabled", "disabled");
			$('#NoCPI').removeAttr("disabled", "disabled");
			$('#txtNonGSU').removeAttr("disabled", "disabled");
			$('#txtIndirectGSU').removeAttr("disabled", "disabled");
			$('#txtDirectGSU').removeAttr("disabled", "disabled");
			$('#buyincriteria').removeAttr("disabled", "disabled");
			$('#chckIsPlus').removeAttr("disabled", "disabled");
			$('#BuyinSelection').removeAttr("disabled", "disabled");
			$('#txtBRMinVal').removeAttr("disabled", "disabled");
			$('#txtBuyinSetStartDate').removeAttr("disabled", "disabled");
			$('#txtBuyinSetEndDate').removeAttr("disabled", "disabled");
			$('#txtEPromoCode').removeAttr("disabled", "disabled");
			$('#txtEProdDesc').removeAttr("disabled", "disabled");
			$('#EntitleCriteria').removeAttr("disabled", "disabled");
			$('#txtEMinVal').removeAttr("disabled", "disabled");
			$('#btnAdd').removeAttr("disabled", "disabled");
			
			$('#PromoDetails2').show();
			$('#ListPromoDetails2').show();
			$('#PromoDetails1').hide();
			$('#ListPromoDetails1').hide();
		}else if (IncentiveType.val() == 4){
			//alert('under construction');
			$('#MechType').removeAttr("disabled", "disabled");
			$('#txtStartDate').removeAttr("disabled", "disabled");
			$('#txtEndDate').removeAttr("disabled", "disabled");
			$('#NoCPI').removeAttr("disabled", "disabled");
			$('#txtNonGSU').removeAttr("disabled", "disabled");
			$('#txtIndirectGSU').removeAttr("disabled", "disabled");
			$('#txtDirectGSU').removeAttr("disabled", "disabled");
			$('#buyincriteria').removeAttr("disabled", "disabled");
			$('#chckIsPlus').removeAttr("disabled", "disabled");
			$('#BuyinSelection').removeAttr("disabled", "disabled");
			$('#txtBRMinVal').removeAttr("disabled", "disabled");
			$('#txtBuyinSetStartDate').removeAttr("disabled", "disabled");
			$('#txtBuyinSetEndDate').removeAttr("disabled", "disabled");
			$('#txtEPromoCode').removeAttr("disabled", "disabled");
			$('#txtEProdDesc').removeAttr("disabled", "disabled");
			$('#EntitleCriteria').removeAttr("disabled", "disabled");
			$('#txtEMinVal').removeAttr("disabled", "disabled");
			$('#btnAdd').removeAttr("disabled", "disabled");
			$('#PromoDetails2').show();
			$('#ListPromoDetails2').show();
			$('#PromoDetails1').hide();
			$('#ListPromoDetails1').hide();
			
		}else if(IncentiveType.val() == 5){
			$('#MechType').removeAttr("disabled", "disabled");
			$('#txtStartDate').removeAttr("disabled", "disabled");
			$('#txtEndDate').removeAttr("disabled", "disabled");
			$('#NoCPI').removeAttr("disabled", "disabled");
			$('#txtNonGSU').removeAttr("disabled", "disabled");
			$('#txtIndirectGSU').removeAttr("disabled", "disabled");
			$('#txtDirectGSU').removeAttr("disabled", "disabled");
			$('#chckIsPlus').removeAttr("disabled", "disabled");
			$('#buyincriteria').removeAttr("disabled", "disabled");
			$('#BuyinSelection').removeAttr("disabled", "disabled");
			$('#txtBRMinVal').removeAttr("disabled", "disabled");
			$('#txtBuyinSetStartDate').removeAttr("disabled", "disabled");
			$('#txtBuyinSetEndDate').removeAttr("disabled", "disabled");
			$('#txtEPromoCode').removeAttr("disabled", "disabled");
			$('#txtEProdDesc').removeAttr("disabled", "disabled");
			$('#EntitleCriteria').removeAttr("disabled", "disabled");
			$('#txtEMinVal').removeAttr("disabled", "disabled");
			$('#btnAdd').removeAttr("disabled", "disabled");

			
			$('#PromoDetails1').show();
			$('#ListPromoDetails1').show();	
			$('#PromoDetails2').hide();
			$('#ListPromoDetails2').hide();
		}else{
			$('#PromoDetails1').show();
			$('#PromoDetails1').show();
			$('#PromoDetails2').hide();
			$('#PromoDetails2').hide();
			$('#MechType').attr("disabled", "disabled");
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
			$('#btnAdd').attr("disabled", "disabled");
			
		}
	})
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
			$("#buyincriteria").val(1);
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
	})
	
//==> Marketing Brands Incentives
//=>Marketing/brands incentives

var BuyinSelection1 = $("#BuyinSelection1");

	BuyinSelection1.change(function(){
		if(BuyinSelection1.val() == 1){
			$('#ProdLine1').attr("disabled", "disabled");
			$('#txtBRItemCode1').val("");
			$('#txtBRItemDesc1').val("");
			$('#ProdLine1').val(0);
			$("#txtPromoCodePromo1").val("");
			$("#txtPromoCodePromo1").attr("disabled","disabled");
			$('#txtBRItemCode1').attr("disabled", "disabled");
			$('#txtBRItemDesc1').attr("disabled", "disabled");
			$("#buyincriteria1").removeAttr("disabled","disabled");
			$("#txtBRMinVal1").removeAttr("disabled","disabled");
			$("#txtBuyinSetStartDate1").removeAttr("disabled","disabled");
			$("#txtBuyinSetEndDate1").removeAttr("disabled","disabled");
		}else if (BuyinSelection1.val() == 2){
			$('#ProdLine1').removeAttr("disabled", "disabled");
			$('#txtBRItemCode1').val("");
			$('#txtBRItemDesc1').val("");
			$('#ProdLine1').val(0);
			$("#buyincriteria1").val(0)
			$("#txtPromoCodePromo1").val("");
			$("#txtPromoCodePromo1").attr("disabled","disabled");
			$('#txtBRItemCode1').attr("disabled", "disabled");
			$('#txtBRItemDesc1').attr("disabled", "disabled");
			$("#buyincriteria1").removeAttr("disabled","disabled");
			$("#txtBRMinVal1").removeAttr("disabled","disabled");
			$("#txtBuyinSetStartDate1").removeAttr("disabled","disabled");
			$("#txtBuyinSetEndDate1").removeAttr("disabled","disabled");
	
		}else if (BuyinSelection1.val() == 3){
			$('#ProdLine1').attr("disabled", "disabled");
			$('#ProdLine1').val(0);
			$('#txtBRItemCode1').val("");
			$('#txtBRItemDesc1').val("");
			$("#txtPromoCodePromo1").val("");
			$("#buyincriteria1").val(0)
			$("#txtPromoCodePromo1").attr("disabled","disabled");
			$('#txtBRItemCode1').removeAttr("disabled", "disabled");
			$('#txtBRItemDesc1').removeAttr("disabled", "disabled");
			$("#buyincriteria1").removeAttr("disabled","disabled");
			$("#txtBRMinVal1").removeAttr("disabled","disabled");
			$("#txtBuyinSetStartDate1").removeAttr("disabled","disabled");
			$("#txtBuyinSetEndDate1").removeAttr("disabled","disabled");
		
		}else if (BuyinSelection1.val()==4 || BuyinSelection1.val()==5){
			$('#ProdLine1').attr("disabled", "disabled");
			$('#ProdLine1').val(0);
			$('#txtBRItemCode1').val("");
			$('#txtBRItemDesc1').val("");
			$("#txtPromoCodePromo1").val("");
			$("#buyincriteria1").val(1);
			$("#txtPromoCodePromo1").removeAttr("disabled","disabled");
			$('#txtBRItemCode1').attr("disabled", "disabled");
			$('#txtBRItemDesc1').attr("disabled", "disabled");
			$("#buyincriteria1").attr("disabled","disabled");
			$("#txtBRMinVal1").removeAttr("disabled","disabled");
			$("#txtBuyinSetStartDate1").attr("disabled","disabled");
			$("#txtBuyinSetEndDate1").attr("disabled","disabled");
			
		}else{
			$('#txtBRItemCode1').val("");
			$('#txtBRItemDesc1').val("");
			$('#ProdLine1').val(0);
			$("#txtPromoCodePromo1").val("");
			$("#buyincriteria1").val(0);
			$("#txtPromoCodePromo1").attr("disabled","disabled");
			$('#ProdLine1').attr("disabled", "disabled");
			$('#txtBRItemCode1').attr("disabled", "disabled");
			$('#txtBRItemDesc1').attr("disabled", "disabled");
			$("#buyincriteria1").attr("disabled","disabled");
			$("#txtBRMinVal1").attr("disabled","disabled");
			$("#txtBuyinSetStartDate1").attr("disabled","disabled");
			$("#txtBuyinSetEndDate1").attr("disabled","disabled");
	
		}
	})
//Activate Special Criteria
$("#activate").change(function() {
var check = $("#activate").attr('checked');
	if(check == true){
		$("#noofwiks").removeAttr("disabled","disabled");
		$("#noofwiksto").removeAttr("disabled","disabled");
		$("#SminValue").removeAttr("disabled","disabled");
		
	}else{
		$("#noofwiks").attr("disabled","disabled");
		$("#noofwiksto").attr("disabled","disabled");
		$("#txtSPStartDate1").attr("disabled","disabled");
		$("#txtSPEndDate1").attr("disabled","disabled");
		$("#SminValue").attr("disabled","disabled");
		$("#txtSPStartDate2").attr("disabled","disabled");
		$("#txtSPEndDate2").attr("disabled","disabled");
		$("#txtSPStartDate3").attr("disabled","disabled");
		$("#txtSPEndDate3").attr("disabled","disabled");
		$("#txtSPStartDate4").attr("disabled","disabled");
		$("#txtSPEndDate4").attr("disabled","disabled");
	}
})
	$('#noofwiks').change(function(){
		var NoOfWiks = $('#noofwiks');
		
		if (NoOfWiks.val() == 1){
			if(NoOfWiks.val() < $('#noofwiksto').val()){
			//do nothing
			}else{
				$("#txtSPStartDate1").removeAttr("disabled","disabled");
				$("#txtSPEndDate1").removeAttr("disabled","disabled");
				$("#txtSPStartDate2").attr("disabled","disabled");
				$("#txtSPEndDate2").attr("disabled","disabled");
				$("#txtSPStartDate3").attr("disabled","disabled");
				$("#txtSPEndDate3").attr("disabled","disabled");
				$("#txtSPStartDate4").attr("disabled","disabled");
				$("#txtSPEndDate4").attr("disabled","disabled");
					//Hide Callendar
				$(".swik2").hide();
				$(".ewik2").hide();
				$(".swik3").hide();
				$(".ewik3").hide();
				$(".swik4").hide();
				$(".ewik4").hide();
				//dissabled text
			}
			$("#txtSPStartDate1").removeAttr("disabled","disabled");
			$("#txtSPEndDate1").removeAttr("disabled","disabled");
				
		}else if (NoOfWiks.val() == 2){
			if(NoOfWiks.val() < $('#noofwiksto').val()){
			//do nothing
			}else{
				$("#txtSPStartDate1").removeAttr("disabled","disabled");
				$("#txtSPEndDate1").removeAttr("disabled","disabled");
				$("#txtSPStartDate2").removeAttr("disabled","disabled");
				$("#txtSPEndDate2").removeAttr("disabled","disabled");
				$("#txtSPStartDate3").attr("disabled","disabled");
				$("#txtSPEndDate3").attr("disabled","disabled");
				$("#txtSPStartDate4").attr("disabled","disabled");
				$("#txtSPEndDate4").attr("disabled","disabled");
				
				$(".swik2").show();
				$(".ewik2").show();
				$(".swik3").hide();
				$(".ewik3").hide();
				$(".swik4").hide();
				$(".ewik4").hide();
			}
		}else if (NoOfWiks.val()==3){
			if(NoOfWiks.val() < $('#noofwiksto').val()){
				//do nothing
			}else{
				$("#txtSPStartDate1").removeAttr("disabled","disabled");
				$("#txtSPEndDate1").removeAttr("disabled","disabled");
				$("#txtSPStartDate2").removeAttr("disabled","disabled");
				$("#txtSPEndDate2").removeAttr("disabled","disabled");
				$("#txtSPStartDate3").removeAttr("disabled","disabled");
				$("#txtSPEndDate3").removeAttr("disabled","disabled");
				$("#txtSPStartDate4").attr("disabled","disabled");
				$("#txtSPEndDate4").attr("disabled","disabled");
				$("#txtSPStartDate1").removeAttr("disabled","disabled");
				$("#txtSPEndDate1").removeAttr("disabled","disabled");
				$(".swik2").show();
				$(".ewik2").show();
				$(".swik3").show();
				$(".ewik3").show();
				$(".swik4").hide();
				$(".ewik4").hide();
			}
		}else if (NoOfWiks.val()==4){
			if(NoOfWiks.val() < $('#noofwiksto').val()){
				//do nothing
			}else{
				$("#txtSPStartDate1").removeAttr("disabled","disabled");
				$("#txtSPEndDate1").removeAttr("disabled","disabled");
				$("#txtSPStartDate2").removeAttr("disabled","disabled");
				$("#txtSPEndDate2").removeAttr("disabled","disabled");
				$("#txtSPStartDate3").removeAttr("disabled","disabled");
				$("#txtSPEndDate3").removeAttr("disabled","disabled");
				$("#txtSPStartDate4").removeAttr("disabled","disabled");
				$("#txtSPEndDate4").removeAttr("disabled","disabled");
				
				$(".swik2").show();
				$(".ewik2").show();
				$(".swik3").show();
				$(".ewik3").show();
				$(".swik4").show();
				$(".ewik4").show();	
			}
		}else{
			$("#txtSPStartDate1").attr("disabled","disabled");
			$("#txtSPEndDate1").attr("disabled","disabled");
			$("#txtSPStartDate2").attr("disabled","disabled");
			$("#txtSPEndDate2").attr("disabled","disabled");
			$("#txtSPStartDate3").attr("disabled","disabled");
			$("#txtSPEndDate3").attr("disabled","disabled");
			$("#txtSPStartDate4").attr("disabled","disabled");
			$("#txtSPEndDate4").attr("disabled","disabled");
			$(".swik2").hide();
			$(".ewik2").hide();
			$(".swik3").hide();
			$(".ewik3").hide();
			$(".swik4").hide();
			$(".ewik4").hide();	
		}
	});
	
	$('#noofwiksto').change(function(){
		var NoOfWiksto = $('#noofwiksto');
		
		if (NoOfWiksto.val() == 1){
			$("#txtSPStartDate1").removeAttr("disabled","disabled");
			$("#txtSPEndDate1").removeAttr("disabled","disabled");
			$("#txtSPStartDate2").attr("disabled","disabled");
			$("#txtSPEndDate2").attr("disabled","disabled");
			$("#txtSPStartDate3").attr("disabled","disabled");
			$("#txtSPEndDate3").attr("disabled","disabled");
			$("#txtSPStartDate4").attr("disabled","disabled");
			$("#txtSPEndDate4").attr("disabled","disabled");
				//Hide Callendar
			$(".swik2").hide();
			$(".ewik2").hide();
			$(".swik3").hide();
			$(".ewik3").hide();
			$(".swik4").hide();
			$(".ewik4").hide();
			//dissabled text
			
		}else if (NoOfWiksto.val() == 2){
			$("#txtSPStartDate1").removeAttr("disabled","disabled");
			$("#txtSPEndDate1").removeAttr("disabled","disabled");
			$("#txtSPStartDate2").removeAttr("disabled","disabled");
			$("#txtSPEndDate2").removeAttr("disabled","disabled");
			$("#txtSPStartDate3").attr("disabled","disabled");
			$("#txtSPEndDate3").attr("disabled","disabled");
			$("#txtSPStartDate4").attr("disabled","disabled");
			$("#txtSPEndDate4").attr("disabled","disabled");
			
			$(".swik2").show();
			$(".ewik2").show();
			$(".swik3").hide();
			$(".ewik3").hide();
			$(".swik4").hide();
			$(".ewik4").hide();

		}else if (NoOfWiksto.val()==3){
			$("#txtSPStartDate1").removeAttr("disabled","disabled");
			$("#txtSPEndDate1").removeAttr("disabled","disabled");
			$("#txtSPStartDate2").removeAttr("disabled","disabled");
			$("#txtSPEndDate2").removeAttr("disabled","disabled");
			$("#txtSPStartDate3").removeAttr("disabled","disabled");
			$("#txtSPEndDate3").removeAttr("disabled","disabled");
			$("#txtSPStartDate4").attr("disabled","disabled");
			$("#txtSPEndDate4").attr("disabled","disabled");
			$("#txtSPStartDate1").removeAttr("disabled","disabled");
			$("#txtSPEndDate1").removeAttr("disabled","disabled");
			$(".swik2").show();
			$(".ewik2").show();
			$(".swik3").show();
			$(".ewik3").show();
			$(".swik4").hide();
			$(".ewik4").hide();
		
		}else if (NoOfWiksto.val()==4){
			$("#txtSPStartDate1").removeAttr("disabled","disabled");
			$("#txtSPEndDate1").removeAttr("disabled","disabled");
			$("#txtSPStartDate2").removeAttr("disabled","disabled");
			$("#txtSPEndDate2").removeAttr("disabled","disabled");
			$("#txtSPStartDate3").removeAttr("disabled","disabled");
			$("#txtSPEndDate3").removeAttr("disabled","disabled");
			$("#txtSPStartDate4").removeAttr("disabled","disabled");
			$("#txtSPEndDate4").removeAttr("disabled","disabled");
			
			$(".swik2").show();
			$(".ewik2").show();
			$(".swik3").show();
			$(".ewik3").show();
			$(".swik4").show();
			$(".ewik4").show();	
		}else{
			$("#txtSPStartDate1").attr("disabled","disabled");
			$("#txtSPEndDate1").attr("disabled","disabled");
			$("#txtSPStartDate2").attr("disabled","disabled");
			$("#txtSPEndDate2").attr("disabled","disabled");
			$("#txtSPStartDate3").attr("disabled","disabled");
			$("#txtSPEndDate3").attr("disabled","disabled");
			$("#txtSPStartDate4").attr("disabled","disabled");
			$("#txtSPEndDate4").attr("disabled","disabled");
			$(".swik2").hide();
			$(".ewik2").hide();
			$(".swik3").hide();
			$(".ewik3").hide();
			$(".swik4").hide();
			$(".ewik4").hide();	
		}
	});
	
	
	
	
	
	
	
	$("#sType").change(function(){
		if($("#sType").val()==2){
			if($("#Sselection").val()==""){
				$("#Sselection").removeAttr('readonly','readonly');
			
				return false;
			}
		}else if($("#sType").val()==1){
			var clr = "";
			$("#Sselection").val(clr);
			$("#Sselection").attr('readonly','readonly');
			return false;
		}
	})
	
	
});

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
		var IncentiveType = $('#inctvtype');
		var PromoDesc = $('#txtPromoDesc');
		var cls = '';
		
		if($("#txtPromoDesc").val() != ""){
				IncentiveType.removeAttr("disabled", "disabled");
				IncentiveType.focus();
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
function btn_add()
{
			var _error = "";
			var ProdLine		  = $("#ProdLine").val();
			var PromoCode 		  = $("#txtPromoCode").val();
			var PromoDesc 		  = $("#txtPromoDesc").val();
			var MechType  		  = $('#MechType').val();
			var StartDate 		  = $('#txtStartDate').val();
			var EndDate   		  = $('#txtEndDate').val();
			var BuyinSelection    = $('#BuyinSelection').val();
			var BuyinCriteria	  = $('#buyincriteria').val();
			var BRMinVal		  = $('#txtBRMinVal').val();
			var BuyinSetStartDate = $('#txtBuyinSetStartDate').val();
			var BuyinSetEndDate   = $('#txtBuyinSetEndDate').val();
			var ECode		  = $('#txtEPromoCode').val();
			var EProdDesc		  = $('#txtEProdDesc').val();
			var EntitleCriteria   = $('#EntitleCriteria').val();
			var EMinVal			  = $('#txtEMinVal').val();
			var BRItemCode		  = $('#txtBRItemCode').val();
			var b_error_msg		  = "";
			var error_cnt		  = 0;
			var e_error_msg		  = "";
	if(BuyinSelection == 0){
		b_error_msg += 'Buyin Selection Required.\n';
		error_cnt++;
	}else if (BuyinSelection == 1){
			if(BuyinCriteria == 0){
				b_error_msg += 'Buyin Criteria Required.\n';
				error_cnt++;
			}
			if (BRMinVal == ""){
				b_error_msg += 'Buyin Minimum Value Required.\n';
				error_cnt++;
		
			}
			if (BuyinSetStartDate == ""){
				b_error_msg += 'Buyin Criteria Required.\n';
				error_cnt++;
		
			}
			if(BuyinSetEndDate == ""){
				b_error_msg += 'End Date required.\n';
				error_cnt++;
				
			}

	}else if (BuyinSelection == 2){
			if(ProdLine == ""){
				b_error_msg += "Product Line required.";
				error_cnt++;
			}
			if(BuyinCriteria == 0){
				b_error_msg += 'Buyin Criteria Required.\n';
				error_cnt++;
			}
			
			if (BRMinVal == ""){
				b_error_msg += 'Buyin Minimum Value Required.\n';
				error_cnt++;
		
			}
			if (BuyinSetStartDate == ""){
				b_error_msg += 'Buyin Criteria Required.\n';
				error_cnt++;
		
			}
			if(BuyinSetEndDate == ""){
				b_error_msg += 'End Date required.\n';
				error_cnt++;
				
			}

	}else if (BuyinSelection == 3){
			if(BRItemCode == ""){
				b_error_msg += 'Buyin Item Code Required.\n';
				error_cnt++;
			}
			if(BuyinCriteria == 0){
				b_error_msg += 'Buyin Criteria Required.\n';
				error_cnt++;
			}
			
			if (BRMinVal == ""){
				b_error_msg += 'Buyin Minimum Value Required.\n';
				error_cnt++;
		
			}
			if (BuyinSetStartDate == ""){
				b_error_msg += 'Buyin Criteria Required.\n';
				error_cnt++;
		
			}
			if(BuyinSetEndDate == ""){
				b_error_msg += 'End Date required.\n';
				error_cnt++;
				
			}
	}else if (BuyinSelection == 4 || BuyinSelection == 5){
	
			if($("#txtPromoCodePromo").val() == ""){
				b_error_msg += 'Promo Code required.\n';
				error_cnt++;	
			}
			
			if (BRMinVal == ""){
				b_error_msg += 'Buyin Minimum Value Required.\n';
				error_cnt++;
		
			}
	}
	
	if(error_cnt > 0){
		alert(b_error_msg);
		return false;
		
	}else{
		error_cnt = 0;
		e_error_msg = "";
			if (ECode == ""){
				e_error_msg += 'Entitlement Item Code required. \n';
				error_cnt++;
			}if (EntitleCriteria == 0){
				e_error_msg += 'Entitlement Criteria required. \n';
				error_cnt++;
			}if (EMinVal == ""){
				e_error_msg += 'Entitlement Minimum Value required. \n';
				error_cnt++;
			}
			
		if(error_cnt > 0){
			alert(e_error_msg);
			return false
		}else{
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
				alert("Buy-in Requirements End date should be the same or later than Start date.");
				return false;
			}
			
			if(getDateObject($('#txtBuyinSetStartDate').val(), "/") < getDateObject(now_date, "/")){			
				alert("Start date should be current or future date.");
				return false;
			}
			
			//$("#txtEndDate").val();
			var headerEdate = Date.parse($("#txtEndDate").val());
			var eheaderdate = new Date(headerEdate);
			
			var headerSdate = Date.parse($("#txtStartDate").val());
			var sheaderdate = new Date(headerSdate);
			
			
			if(sheaderdate > sdate)
			{
				alert("	Buy in requirement start date must be within the assigned at the Promo Header date.");
				return false;
			
			}
			
			if(eheaderdate < edate)
			{
				alert("Buy in requirement end date must be within the assigned at the Promo Header date.");
				return false;
			}
			
		
			
			
			//
			if (confirm('Are you sure you want to add this Buy-in and Entitlement?') == false){
					return false;
			}else{
				//header buyin requirements
				var html = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Action</div></td></tr>';
				var html1 = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td></tr>';
				var x = 1;
				//Header Entitlement Requirements
				$.ajax({
					type: 'post',
					dataType: 'json',
					data: $("form").serialize(),
					url: 'includes/ajaxincentivesBuyinandEntitlement.php?btn_add=btn_add',
					success: function( response ) {
						for (var i=0; i<response.length; i++) {
							//Buyin Details
							var ctr = x++;
							html += '<tr align = "center" height="25px">';
							html += '<td height="20" class="borderBR">'+ ctr +'</td>';
							html += '<td height="20" class="borderBR">'+ response[i].BuyinProdDesc+'</td>';
							html += '<td height="20" class="borderBR">'+ response[i].BuyinCriteria+'</td>';
							html += '<td height="20" class="borderBR">'+ response[i].BMinVal+'</td>';
							html += '<td height="20" class="borderBR">'+ response[i].BuyinStartDate+'</td>';
							html += '<td height="20" class="borderBR">'+ response[i].BuyinEndDate+'</td>';
							html += '<td height="20" class="borderBR"><input class="btn" type="submit" onclick="return confirmDelete(\''+response[i].BuyinID+'\');" value="Delete" name="btnDelete"></td>';
							html += '</tr>';
							//entitilement details
							html1 += '<tr align = "center" height="25px">';
							html1 += '<td height="20" class="borderBR">'+ctr+'</td>';
							html1 += '<td height="20" class="borderBR">'+ response[i].EntProductDesc+'</td>';
							html1 += '<td height="20" class="borderBR">'+ response[i].EntCriteria+'</td>';
							html1 += '<td height="20" class="borderBR">'+ response[i].EntMin+'</td>';
							html1 += '<td height="20" class="borderBR">'+ response[i].EntStartDate+'</td>';
							html1 += '<td height="20" class="borderBR">'+ response[i].EntEndDate+'</td>';
							html1 += '</tr>';
						}
						$("#TBLbuyinrequirements").html(html);
						$("#TBIEntitlement").remove();
						$('#tblentitlement').html(html1);
						$('#btnSave').removeAttr("disabled", "disabled");
					}
				})
				return false;
			}
			
		}
	}
}

function confirmDelete(id)
{
		//header buyin requirements
		var html = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Action</div></td></tr>';
		var html1 = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td></tr>';
		var x = 1;
	
		if (confirm('Are you sure you want to delete this Buy-in and Entitlement?') == false){
				return false;
				}else{
				$.ajax({
						type: 'post',
						dataType: 'json',
						data: {xID: id},
						url: 'includes/ajaxincentivesBuyinandEntitlement.php',
						success: function( response ) {
						if(response.length != 0){
							for (var i=0; i<response.length; i++) {
								//alert(response[i].BuyinProdDesc);
								//Buyin Details
								var ctr = x++;
								html+= '<tr align = "center" height="25px">';
								html+= '<td height="20" class="borderBR">'+ ctr +'</td>';
								html+= '<td height="20" class="borderBR">'+ response[i].BuyinProdDesc+'</td>';
								html+= '<td height="20" class="borderBR">'+ response[i].BuyinCriteria+'</td>';
								html+= '<td height="20" class="borderBR">'+ response[i].BMinVal+'</td>';
								html+= '<td height="20" class="borderBR">'+ response[i].BuyinStartDate+'</td>';
								html+= '<td height="20" class="borderBR">'+ response[i].BuyinEndDate+'</td>';
								html+= '<td height="20" class="borderBR"><input class="btn" type="submit" onclick="return confirmDelete(\''+response[i].BuyinID+'\');" value="Delete" name="btnDelete"></td>';
								html+= '</tr>';
								
								//entitilement details
								
								html1+= '<tr align = "center" height="25px">';
								html1+= '<td height="20" class="borderBR">'+ctr+'</td>';
								html1+= '<td height="20" class="borderBR">'+ response[i].EntProductDesc+'</td>';
								html1+= '<td height="20" class="borderBR">'+ response[i].EntCriteria+'</td>';
								html1+= '<td height="20" class="borderBR">'+ response[i].EntMin+'</td>';
								html1+= '<td height="20" class="borderBR">'+ response[i].EntStartDate+'</td>';
								html1+= '<td height="20" class="borderBR">'+ response[i].EntEndDate+'</td>';
								html1+= '</tr>';
							}
							$("#TBLbuyinrequirements").html(html);
							$("#TBIEntitlement").remove();
							$('#tblentitlement').html(html1);
							$('#btnSave').removeAttr("disabled", "disabled");
						}else if (response.length == 0){
							$('#btnSave').attr("disabled", "disabled");
							$("#TBLbuyinrequirements").html(html);
							$("#TBIEntitlement").remove();
							$('#tblentitlement').html(html1);
						}
					}
					})
					return false;
				}
}

function ConfirmSave(xformID){
	var error_msg = "", error_cnt = 0;
	
	if ($("#txtPromoDesc").val() == ""){
		error_msg += 'Promo Description required. \n';
		error_cnt++;
	}
	if ($("#inctvtype").val()==0){
		error_msg += 'Incentive type required. \n';
		error_cnt++;
	}
	else if($("#MechType").val()== 0){
		error_msg += 'Mechanics Type required.'
		error_cnt++;
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
		error_msg += "Start date should be current or future date.";
		error_cnt++;
	}
	
	//javascript:RemoveInvalidChars(txtMaxAvail1);
	if(sdate > edate){			
		error_msg +="End date should be the same or later than Start date.";
		error_cnt++;
	}

	if(error_cnt == 0){
		if (confirm('Are you sure you want to Save this Incentives Promo Buy-in and Entitlement?') == false){
					return false;
		}else{
				if (xformID == 1){
					var xdata = {
									xSave: 			'save', 
									xPromoCode: 	$('#txtPromoCode').val(),
									xPromoDesc: 	$('#txtPromoDesc').val(), 
									xinctvtype: 	$('#inctvtype').val(),
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
							url: 'includes/ajaxincentivesBuyinandEntitlement.php',
							success: function( response ){
								if(response.success == 1){
									alert(response.result);
									window.location.assign("index.php?pageid=66&inc=2");
									return true;
								}else{
									alert(response.result);
								}
							}
					});
					return false;
				}else if(xformID == 2){
					var xdata = {
									//Header
									xSave1: 			'save', 
									xPromoCode: 	$('#txtPromoCode').val(),
									xPromoDesc: 	$('#txtPromoDesc').val(), 
									xinctvtype: 	$('#inctvtype').val(),
									xmechtype: 	 	$('#MechType').val(),
									xStartDate: 	$('#txtStartDate').val(),
									xEndDate: 		$('#txtEndDate').val(),
									xNoCPI:			$('#NoCPI').val(),
									xNonGSU:		$('#txtNonGSU').val(),
									xdirectGSU:		$('#txtDirectGSU').val(),
									xIndirectGSU:	$('#txtIndirectGSU').val(),
									xchckIsPlus:	CheckBox($('#chckIsPlus').is(":checked"))
								};
					$.ajax({
							type: 'post',
							dataType: 'json',
							data: xdata,
							url: 'includes/ajaxincentivesBuyinandEntitlement.php',
							success: function( response ){
								if(response.success == 1){
									alert(response.result);
									window.location.assign("index.php?pageid=66&inc=2");
									return true;
								}else{
									alert(response.result);
								}
							}
					});
					return false;
				}
		}
	}else{
		alert(error_msg);
		return false;
	}
}
//checkBox
function CheckBox(bool){
	if(bool)
		return 1;
		return 0;
}

function MBI_ADD()
{
	var _error = "";
			var ProdLine		  = $("#ProdLine").val();
			var PromoCode 		  = $("#txtPromoCode").val();
			var PromoDesc 		  = $("#txtPromoDesc").val();
			var MechType  		  = $('#MechType').val();
			var StartDate 		  = $('#txtStartDate').val();
			var EndDate   		  = $('#txtEndDate').val();
			var BuyinSelection    = $('#BuyinSelection1').val();
			var BuyinCriteria	  = $('#buyincriteria1').val();
			var BRMinVal		  = $('#txtBRMinVal1').val();
			var BuyinSetStartDate = $('#txtBuyinSetStartDate1').val();
			var BuyinSetEndDate   = $('#txtBuyinSetEndDate1').val();
			var BRItemCode		  = $('#txtBRItemCode1').val();
	if(BuyinSelection == 0){
		alert('Please Select Selection');
		return false;
	}else if (BuyinSelection == 1){
			if(BuyinCriteria == 0){
				alert('Please Select Criteria');
			return false;
			}
			else if (BRMinVal == ""){
				alert('Please Input Minimum Value');
				return false;
			}else if (BuyinSetStartDate == ""){
				alert('Please Input Start Date');
				return false;
			}else if(BuyinSetEndDate == ""){
				alert('Please Input End Date');
				return false;
			}
	}else if (BuyinSelection == 2){
			if(ProdLine == ""){
				alert("Please Select Product Line");
				return false;
			}else if(BuyinCriteria == 0){
				alert('Please Select Criteria');
			return false;
			}else if (BRMinVal == ""){
				alert('Please Input Minimum Value');
				BRMinVal;
				return false;
			}else if (BuyinSetStartDate == ""){
				alert('Please Input Start Date');
				return false;
			}else if(BuyinSetEndDate == ""){
				alert('Please Input End Date');
				return false;
			}
	}else if (BuyinSelection == 3){
			if(BRItemCode == ""){
				alert("Please Input Item Code");
				return false;
			}else if(BuyinCriteria == 0){
				alert('Please Select Criteria');
			return false;
			}else if (BRMinVal == ""){
				alert('Please Input Minimum Value');
				return false;
			}else if (BuyinSetStartDate == ""){
				alert('Please Input Start Date');
				return false;
			}else if(BuyinSetEndDate == ""){
				alert('Please Input End Date');
				return false;
			}
			
	}
	if($("#sType").val()==2){
		if($("#Sselection").val()==""){
			alert("Please Input Selection Etilement Selection No.");
			$("#Sselection").focus();
			return false;
		}
	}
	//var Multi Entitlement
	var MEvalidation = $("#multixcounter").val();
	var err = 0;
	for(var i=1; i < MEvalidation; i++){
		if($("#txtEPromoCode"+i).val() == ""){
			err++;
		}else if($("#buyinEcriteria" + i).val() == 0){
			err++;
		}else if( $("#txtEMinVal" + i).val() == ""){
			err++;
		}
	}
	
	var s_date = Date.parse($('#txtBuyinSetStartDate1').val());
    var sdate = new Date(s_date); 
    var e_date = Date.parse($('#txtBuyinSetEndDate1').val());
    var edate = new Date(e_date);
	
	var now = new Date();
	var now_day = now.getDate();
	var now_month = now.getMonth() + 1;
	var now_year = now.getFullYear();
	var now_date = now_month + "/" + now_day + "/" + now_year;
	
	if(getDateObject($('#txtBuyinSetStartDate1').val(), "/") < getDateObject(now_date, "/"))
	{			
		alert("Start date should be current or future date.");
		return false;
	}
	
	
	if(sdate > edate){			
		alert("End date should be the same or later than Start date.");
		return false;
	}

	var headerEdate = Date.parse($("#txtEndDate").val());
	var eheaderdate = new Date(headerEdate);
	
	var headerSdate = Date.parse($("#txtStartDate").val());
	var sheaderdate = new Date(headerSdate);
			
			
	if(sheaderdate > sdate)
	{
		alert("	Buy in requirement start date must be within the assigned at the Promo Header date.");
		return false;
	
	}
	
	if(eheaderdate < edate)
	{
		alert("Buy in requirement end date must be within the assigned at the Promo Header date.");
		return false;
	}
	
	if(CheckBox($('#activate').is(":checked"))){
		if($("#noofwiks").val() > $("#noofwiksto").val()){
			alert('No of weeks From should be the same or lower than No of weeks to');
			return false;
		}else if($("#noofwiks").val() == 0 || $("#noofwiksto").val() == 0){
			alert('No of weeks from / to required.');
			return false;
		}
	}
	
	if(err == 0){
	if (confirm('Are you sure you want to add this Buy-in and Entitlement?') == false){
		return false;
	}else{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:	$("form").serialize(),
			url: 'includes/ajaxincentivesBuyinandEntitlement.php?XMBI_ADD=MBI_ADD',
			success: function(response){
				if(response.length != 0){
				var x = 1;
					var html = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Action</div></td></tr>';
					var html1 = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td></tr>';
					var description = "";		
						for (var i=0; i<response[0].length; i++) {
							
							if( response[0][i].ProductDesc== '' || response[0][i].ProductDesc == null ){
									if(response[0][i].ProductLevelID == 4){
										description = "Single Line - "+response[0][i].PromoCode;
									}else{
										description = "Multi Line - "+response[0][i].PromoCode;
									}
							}
							
							if( response[0][i].PromoCode == '' || response[0][i].PromoCode == null ){
								description = response[0][i].ProductDesc;
							}
							
							var ctr = x++;
							html += '<tr align = "center" height="25px">';
							html += '<td height="20" class="borderBR">'+ ctr +'</td>';
							html += '<td height="20" class="borderBR">'+ description +'</td>';
							html += '<td height="20" class="borderBR">'+ (response[0][i].CriteriaID=='1'?'Quantity':'Amount')+'</td>';
							html += '<td height="20" class="borderBR">'+ (response[0][i].CriteriaID=='1'?response[0][i].MinQty:response[0][i].MinAmt)+'</td>';
							html += '<td height="20" class="borderBR">'+ response[0][i].StartDate+'</td>';
							html += '<td height="20" class="borderBR">'+ response[0][i].EndDate+'</td>';
							html += '<td height="20" class="borderBR"><input class="btn" type="submit" onclick="return confirmDelete1(\''+response[0][i].ID+'\');" value="Delete" name="btnDelete"></td>';
							html += '</tr>';
							
							//entitilement details
												
							rAjax=response[1][i].length;																										
							print_counter = true;
							
							for(var y=0;y<rAjax;y++) {													
								html1+= '<tr align = "center" height="25px">';
								html1+= '<td height="20" class="borderBR">'+(print_counter?(i+1):'')+'</td>';
								html1+= '<td height="20" class="borderBR">'+ response[1][i][y].ProductDesc+'</td>';
								html1+= '<td height="20" class="borderBR">'+ (response[1][i][y].CriteriaID=='1'?'Quantity':'Price')+'</td>';
								html1+= '<td height="20" class="borderBR">'+ (response[1][i][y].CriteriaID=='1'?response[1][i][y].MinQty:response[1][i][y].MinAmt)+'</td>';
								html1+= '<td height="20" class="borderBR">'+ response[1][i][y].StartDate+'</td>';
								html1+= '<td height="20" class="borderBR">'+ response[1][i][y].EndDate+'</td>';
								html1+= '</tr>';
								print_counter = false;
							}																
						}
							$("#TBLbuyinrequirements1").html(html);
							$("#TBIEntitlement1").remove();
							$("#tblentitlement2").remove();
							$('#TBLentitlement22').html(html1);
							$('#btnsave1').removeAttr("disabled", "disabled");
                                                        var row  = "";
                                                        var count = 1;
                                                        row += '<tr align="center">';
                                                        row +=    '<input type = "hidden" value = "2" id = "multixcounter" name = "multixcounter">';
                                                        row +=    '<td width="5%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">No.</div></td>';
                                                        row +=    '<td width="13%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">ItemCode</div></td>';			
                                                        row +=    '<td width="35%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Product Name</div></td>';
                                                        row +=    '<td width="17%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Criteria</div></td>';
                                                        row +=    '<td width="15%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Minimum</div></td>';
                                                        row +=    '<td width="15%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Action</div></td>';
							row += '</tr>';
                                                        row += "<tr align='center' class = 'remove"+count+"'>";
							row += "<td height='20'  class='borderBR'><div  align='center' class='padl5'>"+count+"</div></td>";
							row += "<td height='20'  class= 'borderBR'><div align='center' class='padl5'><input type='text' class='txtfield'  onkeydown = 'xautocompleter("+count+")' onkeypress = 'keyfunction("+count+",event)' id='txtEPromoCode"+count+"' name = 'txtEPromoCode"+count+"' size='20' style='width: 85%'  value = '' ></div></td>";
							row += "<td height='20'  class= 'borderBR'><div align='center' class='padl5'><input type='text' class='txtfield' id='txtEProdDesc"+count+"'  name = 'txtEProdDesc"+count+"' size='20' style='width: 92%' readonly='yes' value = '' ></div></td>";
							row += "<td height='20'  class= 'borderBR'><div align='center' class='padr5'>";
							row += "<select name='buyinEcriteria"+count+"'  class='txtfield' id='buyinEcriteria"+count+"' style='width: 100%' >";
							row += "<option value='0'>[SELECT TYPE]</option>";
							row += "<option value='2'> Price</option>";
							row += "<option value='1'> Quantity </option>";
							row += "</select>";
							row += "</div></td>";
							row += "<td height='20' class= 'borderBR'><div align='right' class='padr5'><input name='txtEMinVal"+count+"' onkeyup ='javascript:RemoveInvalidChars(txtEMinVal"+count+")' id = 'txtEMinVal"+count+"' type='text' class='txtfield' id='' size='20' style='width: 85%' onkeypress='return addRow(event, "+count+")'></div></td>";
							row += "<td height='20' class= 'borderBR'><div align='right' class='padr5'><input type='button' onclick='removeRow("+count+");' value='Remove' name='btnRemove"+count+"' class ='btn'></div></td>";
							row += "</tr>";
							
							$("#multirow").html(row);
                                                        
				}else{
                                     
					$("#TBLbuyinrequirements1").html(html);
					$("#TBIEntitlement1").remove();
					$("#tblentitlement2").remove();
					$('#TBLentitlement22').html(html1);
					$('#btnsave1').attr("disabled", "disabled");
				}
			}
		})
	}
	return false;
	}else{
			alert('Please Complete Entitlement Form or Remove Blank Form');
			return false;
	}
}

function confirmDelete1(id)
{
		
		var x = 1;
		if (confirm('Are you sure you want to delete this Buy-in and Entitlement?') == false){
				return false;
				}else{
				$.ajax({
						type: 'post',
						dataType: 'json',
						data: {xIDx: id},
						url: 'includes/ajaxincentivesBuyinandEntitlement.php',
						success: function( response ) {
						if(response.length != 0){
							var x = 1;
							var html = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Action</div></td></tr>';
							var html1 = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td></tr>';
						
								for (var i=0; i<response[0].length; i++) {
							
							
									if( response[0][i].ProductDesc== '' || response[0][i].ProductDesc == null ){
										if(response[0][i].ProductLevelID == 4){
											description = "Single Line - "+response[0][i].PromoCode;
										}else{
											description = "Multi Line - "+response[0][i].PromoCode;
										}
									}
									
									if( response[0][i].PromoCode == '' || response[0][i].PromoCode == null ){
										description = response[0][i].ProductDesc;
									}
							
									var ctr = x++;
									html += '<tr align = "center" height="25px">';
									html += '<td height="20" class="borderBR">'+ ctr +'</td>';
									html += '<td height="20" class="borderBR">'+ description +'</td>';
									html += '<td height="20" class="borderBR">'+ (response[0][i].CriteriaID=='1'?'Quantity':'Amount')+'</td>';
									html += '<td height="20" class="borderBR">'+ (response[0][i].CriteriaID=='1'?response[0][i].MinQty:response[0][i].MinAmt)+'</td>';
									html += '<td height="20" class="borderBR">'+ response[0][i].StartDate+'</td>';
									html += '<td height="20" class="borderBR">'+ response[0][i].EndDate+'</td>';
									html += '<td height="20" class="borderBR"><input class="btn" type="submit" onclick="return confirmDelete1(\''+response[0][i].ID+'\');" value="Delete" name="btnDelete"></td>';
									html += '</tr>';
									
									//entitilement details
														
									rAjax=response[1][i].length;																										
									print_counter = true;
									
									for(var y=0;y<rAjax;y++) {													
										
										html1+= '<tr align = "center" height="25px">';
										html1+= '<td height="20" class="borderBR">'+(print_counter?(i+1):'')+'</td>';
										html1+= '<td height="20" class="borderBR">'+ response[1][i][y].ProductDesc+'</td>';
										html1+= '<td height="20" class="borderBR">'+ (response[1][i][y].CriteriaID=='1'?'Quantity':'Amount')+'</td>';
										html1+= '<td height="20" class="borderBR">'+ (response[1][i][y].CriteriaID=='1'?response[1][i][y].MinQty:response[1][i][y].MinAmt)+'</td>';
										html1+= '<td height="20" class="borderBR">'+ response[1][i][y].StartDate+'</td>';
										html1+= '<td height="20" class="borderBR">'+ response[1][i][y].EndDate+'</td>';
										html1+= '</tr>';
			
										print_counter = false;
									}																
								}
							$("#TBLbuyinrequirements1").html(html);
							$("#TBIEntitlement1").remove();
							$("#tblentitlement2").remove();
							$('#TBLentitlement22').html(html1);
							$('#btnSave').removeAttr("disabled", "disabled");
						}else if (response.length == 0){
							var html = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Action</div></td></tr>';
							var html1 = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td><td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td><td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td><td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td></tr>';
							$("#TBLbuyinrequirements1").html(html);
							$('#TBLentitlement22').html(html1);
							$('#btnSave').attr("disabled", "disabled");
						}
					}
				})
					return false;
				}
}




$.fn.CreateAutocomplete = function(count){

	jQuery(this).autocomplete({
		source:'includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				jQuery( "#txtEPromoCode"+count+"").val( ui.item.label);
				jQuery( "#txtEProdDesc"+count+"").val( ui.item.ProductName);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( li, item ) {
		return jQuery( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( li );
	};	
	
	
};		
function addRow(e, cnt) 
{
			var count = 1 + cnt;
			if(e.keyCode == 13){
				if(jQuery("#txtEPromoCode"+cnt+"").val().length != 0){
						var row  = "";
							row	+= "<tr align='center' class = 'remove"+count+"'>";
							row += "<td height='20'  class='borderBR'><div  align='center' class='padl5'>"+count+"</div></td>";
							row += "<td height='20'  class= 'borderBR'><div align='center' class='padl5'><input type='text' class='txtfield'  onkeydown = 'xautocompleter("+count+")' onkeypress = 'keyfunction("+count+",event)' id='txtEPromoCode"+count+"' name = 'txtEPromoCode"+count+"' size='20' style='width: 85%'  value = '' ></div></td>";
							row += "<td height='20'  class= 'borderBR'><div align='center' class='padl5'><input type='text' class='txtfield' id='txtEProdDesc"+count+"'  name = 'txtEProdDesc"+count+"' size='20' style='width: 92%' readonly='yes' value = '' ></div></td>";
							row += "<td height='20'  class= 'borderBR'><div align='center' class='padr5'>";
							row += "<select name='buyinEcriteria"+count+"'  class='txtfield' id='buyinEcriteria"+count+"' style='width: 100%' >";
							row += "<option value='0'>[SELECT TYPE]</option>";
							row += "<option value='2'> Price</option>";
							row += "<option value='1'> Quantity </option>";
							row += "</select>";
							row += "</div></td>";
							row += "<td height='20' class= 'borderBR'><div align='right' class='padr5'><input name='txtEMinVal"+count+"' onkeyup ='javascript:RemoveInvalidChars(txtEMinVal"+count+")' id = 'txtEMinVal"+count+"' type='text' class='txtfield' id='' size='20' style='width: 85%' onkeypress='return addRow(event, "+count+")'></div></td>";
							row += "<td height='20' class= 'borderBR'><div align='right' class='padr5'><input type='button' onclick='removeRow("+count+");' value='Remove' name='btnRemove"+count+"' class ='btn'></div></td>";
							row += "</tr>";
							
							$("#multirow").append(row);
							//$('#txtEPromoCode'+cnt+'').attr('readonly','readonly');
							$('#txtEPromoCode'+count+'').focus();
							//$('#txtEPromoCode'+count+'').CreateAutocomplete(count);
							$('#entitlementcnt').val(count);
							//aditional 1 count for validation
							var multixxcounter = count + 1;
							$('#multixcounter').val(multixxcounter);
							return false;
							
				}else{
							
							alert('Fillup All Form');
							$('#txtEPromoCode'+cnt+'').focus();
							$('#txtPoints'+cnt+'').val("");
							return false;
				}
			}
			
}

function removeRow(cnt)
{
	jQuery('.remove'+cnt+'').remove();
}

function ConfirmCancel()
{
	if (confirm('Are you sure you want to Cancel Incentive Promo?') == false){
		return false;
	}else{
			return true;
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
//getting promo code
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

function xautocompleter(count){
	$("#txtEPromoCode"+count+"").autocomplete({
		source:'includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				$( "#txtEPromoCode"+count+"").val( ui.item.label);
				$( "#txtEProdDesc"+count+"").val( ui.item.ProductName);
			return false;
		}
	}).data( "autocomplete" )._renderItem = function( li, item ) {
		return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
			.appendTo( li );
	};
}

function keyfunction(counter,event)
{
	txtEMinVal
	//testing only
	if(event.keyCode == 13){
		$("#txtEMinVal"+counter+"").focus();
		return false;
	}
	return false;
}


$(function(){
    $("#txtStartDate, #txtEndDate, #txtBuyinSetStartDate, #txtBuyinSetEndDate, #txtBuyinSetStartDate1, #txtBuyinSetEndDate1, #txtSPStartDate1, #txtSPEndDate1, #txtSPStartDate2, #txtSPEndDate2, #txtSPStartDate3, #txtSPEndDate3, #txtSPStartDate4, #txtSPEndDate4").datepicker({
        changeMonth: true,
        changeYear: true
    });
});