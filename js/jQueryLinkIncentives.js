$(document).ready(function(){

	if($("#inctvtype").val() <= 2 ){
		$("#PromoDetails2").hide();
		$("#ListPromoDetails2").hide();
		$("#PromoDetails1").show();
		$("#ListPromoDetails1").show();
	}else{
		//alert('here');

		/*Ajax*/
		$.ajax({
			type: 'post',
			dataType: 'json',
			data:	{xxxID: $("#xID").val()},
			url: '../../includes/ajaxincentivesBuyinandEntitlement.php?xUpdate=xUpdate',
			success: function(response){
				if(response.length != 0){
					var x = 1;
					var yy = 1;
					var xx = 0;
						var html = '<tr align="center"><td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td>';
							html += '<td width="25%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Selection</div></td>';
							html += '<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td>';
							html += '<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>';
							html += '<td width="11%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td>';
							html += '<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td>';
							html += '<td width="15%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Special Criteria</div></td>';
							html += '<td width="9%" height="20" class="txtpallete borderBR"><div align="center" class="padr5">Action</div></td></tr>';

						var html1 = '<tr align="center"><td width="15%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Criteria</div></td>';
							html1 +='<td width="5%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">No.</div></td>';
							html1 +='<td width="10%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Code</div></td>';
							html1 +='<td width="20%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td>';
							html1 +='<td width="10%" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Criteria</div></td>';
							html1 +='<td width="7%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Minimum</div></td>';
							html1 +='<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">Start Date</div></td>';
							html1 +='<td width="10%" height="20" class="txtpallete borderBR"><div align="right" class="padr5">End Date</div></td></tr>';

						var autocomplete = "";

							for (var i=0; i<response[0].length; i++) {
								var description = "";

								if(response[0][i].ProductDesc == null || response[0][i].ProductDesc == ""){
									if(response[0][i].ProductLevelID == 4){
										description = "Single Line - "+response[0][i].PromoCode;
									}
									if(response[0][i].ProductLevelID == 5){
										description = "Multi Line - "+response[0][i].PromoCode;
									}
								}

								else if(response[0][i].ProductDesc != null || response[0][i].ProductDesc != ""){
									description = response[0][i].ProductDesc;
								}

								var ctr = x++;
								html += '<tr align = "center" height="25px">';
								html += '<td height="20" class="borderBR">'+ ctr +'';
								html += '<input type = "hidden" readonly = "true" class = "txtfield" id = "bID'+yy+'" name = "bID'+yy+'" value = "'+response[0][i].ID+'" style ="width: 80%;"></td>';
								html += '<td height="20" class="borderBR"><input type = "text" readonly = "readonly" value = "'+ description +'" style = "width: 100%;" class = "txtfield"></td>';
								//html += '<td height="20" class="borderBR">'+ (response[0][i].CriteriaID == '1'?'1':'2')+'</td>';
								if(response[0][i].CriteriaID == '1'){
									html += "<td height='20' class='borderBR'><select name='BCriteria"+yy+"'  class='txtfield' id='BCriteria"+yy+"' style='width: 100%' >";
									html += "<option value='1'> Quantity</option>";
									html += "<option value='2'> Amount </option>";
									html += "</select></td>";
								}else{
									html += "<td height='20' class='borderBR'><select name='BCriteria"+yy+"'   class='txtfield' id='BCriteria"+yy+"' style='width: 100%' >";
									html += "<option value='2'> Amount </option>";
									html += "<option value='1'> Quantity</option>";
									html += "</select></td>";
								}
								html += '<td height="20" class="borderBR"><input type = "text" class = "txtfield" name = "BMinVal'+yy+'" value = "'+ (response[0][i].CriteriaID=='1'?response[0][i].MinQty:response[0][i].MinAmt)+'" style ="width: 80%;"></td>';
								html += '<td height="20" class="borderBR">'+ response[0][i].StartDate+'</td>';
								html += '<td height="20" class="borderBR">'+ response[0][i].EndDate+'</td>';
								html += '<td height="20" class="borderBR"><input id="submitTB" name="submitTB" class = "btn" type="submit" value="View" onclick = "return SpecialClick(\''+response[0][i].ID+'\')" /></td>';
								html += '<td height="20" class="borderBR"><input class="btn" type="submit" onclick="return SingleDelete(\''+response[0][i].ID+'\');" value="Delete" name="btnDelete"></td>';
								//onclick="return confirmDelete1(\''+response[0][i].ID+'\');
								html += '</tr>';

								//entitilement details

								rAjax=response[1][i].length;
								print_counter = true;

								for(var y=0;y<rAjax;y++) {
									html1+= '<tr align = "center" height="25px">';
									html1+= '<td height="20" class="borderBR">'+(print_counter?(response[0][i].Type==2?"SELECTION - "+response[0][i].Qty+"":"SET"):"&nbsp;")+'</td>';
									html1+= '<td height="20" class="borderBR">'+(print_counter?(i+1):'&nbsp;')+'';
									html1+= '<input type = "hidden" readonly = "true" class = "txtfield" id = "eID'+yy+'" name = "eID'+yy+'" value = "'+response[1][i][y].ID+'" style ="width: 80%;"></td>';
									html1+= '<td height="20" class="borderBR"><input type = "text" class = "txtfield" onkeypress = "autocompletexXx(\''+yy+'\')"  id =  "txtEentitlement'+yy+'" name = "txtEentitlement'+yy+'" value = "'+response[1][i][y].ItemCode+'" style ="width: 80%;"></td>';
									html1+= '<td height="20" class="borderBR"><input  type = "text" class = "txtfield" readonly = "true"   id =  "txtEEProdDesc'+yy+'" name = "txtEEProdDesc'+yy+'" value = "'+response[1][i][y].ProductDesc+'" style ="width: 100%;"></td>';
									//html1+= '<td height="20" class="borderBR">'+ (response[1][i][y].CriteriaID=='1'?'Quantity':'Ammount')+'</td>';
									if(response[1][i][y].CriteriaID=='1'){
										html1 += "<td height='20' class='borderBR'><select name='ECriteria"+yy+"'  class='txtfield' id='ECriteria"+yy+"' style='width: 100%' >";
										html1 += "<option value='1'> Quantity</option>";
										html1 += "<option value='2'> Amount </option>";
										html1 += "</select></td>";
									}else{
										html1 += "<td height='20' class='borderBR'><select name='ECriteria"+yy+"'  class='txtfield' id= 'ECriteria"+yy+"' style='width: 100%' >";
										html1 += "<option value='2'> Amount </option>";
										html1 += "<option value='1'> Quantity</option>";
										html1 += "</select></td>";
									}
									if(response[1][i][y].CriteriaID=='1'){
										html1+= '<td height="20" class="borderBR"><input type = "text" name = "EMinVal'+yy+'"  class = "txtfield" value = "'+ response[1][i][y].MinQty+'" style ="width: 80%;"></td>';
									}else{
										html1+= '<td height="20" class="borderBR"><input type = "text" name = "EMinVal'+yy+'"  class = "txtfield" value = "'+ response[1][i][y].MinAmt+'" style ="width: 80%;"></td>';
									}
									html1+= '<td height="20" class="borderBR">'+ response[1][i][y].StartDate+'</td>';
									html1+= '<td height="20" class="borderBR">'+ response[1][i][y].EndDate+'</td>';
									html1+= '</tr>';
									print_counter = false;
									xx++;
									yy++;
								}


							}

					autocomplete += "<input type = 'hidden' name = 'entitlementCount' id='xCountx' value ='"+xx+"'>";
					autocomplete += "<input type = 'hidden' name = 'BuyinCount' id='xCountx' value ='"+i+"'>";
					$("#TBLbuyinrequirements1").html(html);
					$('#TBLentitlement22').html(html1);
					$('#for_autocomplete').html(autocomplete);

				}
			}
			})
			$("#PromoDetails2").show();
			$("#ListPromoDetails2").show();
			$("#PromoDetails1").hide();
			$("#ListPromoDetails1").hide();
	}


	if($("#inctvtype").val() == 5 ){
		$("#PromoDetails2").hide();
		$("#ListPromoDetails2").hide();
		$("#PromoDetails1").show();
		$("#ListPromoDetails1").show();
	}

$("#activate").change(function() {
var check = $("#activate").attr('checked');
	if(check == true){
		$("#noofwiks").removeAttr("disabled","disabled");
		$("#NoOfWiksto").removeAttr("disabled","disabled");
		$("#SminValue").removeAttr("disabled","disabled");

	}else{
		$("#noofwiks").attr("disabled","disabled");
		$("#NoOfWiksto").attr("disabled","disabled");
		$("#txtSPStartDate1").removeAttr("disabled","disabled");
		$("#txtSPEndDate1").removeAttr("disabled","disabled");
		$("#SminValue").attr("disabled","disabled");
		$("#txtSPStartDate2").removeAttr("disabled","disabled");
		$("#txtSPEndDate2").removeAttr("disabled","disabled");
		$("#txtSPStartDate3").removeAttr("disabled","disabled");
		$("#txtSPEndDate3").removeAttr("disabled","disabled");
		$("#txtSPStartDate4").removeAttr("disabled","disabled");
		$("#txtSPEndDate4").removeAttr("disabled","disabled");
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

	$('#NoOfWiksto').change(function(){
		var NoOfWiksto = $('#NoOfWiksto');

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
		if($("#sType").val()==1){
			if($("#Sselection").val()==""){
				$("#Sselection").removeAttr('readonly','readonly');

				return false;
			}
		}else if($("#sType").val()==2){
			var clr = "";
			$("#Sselection").val(clr);
			$("#Sselection").attr('readonly','readonly');
			return false;
		}
	})


	$("#TBcancel").click(function() {

				$("#test_overlay").fadeOut('slow');
				return false;
	});
});



function BigDelete(){
	if(confirm("Are you sure want to delete this record?")==false){
		return false;
	}else{
		return true;
	}
}

function btnBigUpdate()
{
	if(confirm('Are you sure want to Update this records?') == false){
		return false;
	}else{
		return true;
	}
}

function autocompletexXx(counter)
{
		$('#txtEentitlement'+counter+'').autocomplete({
		source:'../../includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				$( "#txtEentitlement"+counter+"").val( ui.item.label);
				$( "#txtEEProdDesc"+counter+"").val( ui.item.ProductName);
			return false;
		}
		}).data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
				.appendTo( ul );
		};
}

function xXxautocompletexXx(counter)
{
		$('#txtEeentitlement'+counter+'').autocomplete({
		source:'../../includes/jxloyaltyvalidation.php',
			select: function( event, ui ) {
				$( "#txtEeentitlement"+counter+"").val( ui.item.label);
				$( "#txtEEeProdDesc"+counter+"").val( ui.item.ProductName);
			return false;
		}
		}).data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<a><strong>" + item.label + "</strong> - " + item.ProductName + "</a>" )
				.appendTo( ul );
		};
}
//hEntIndex

function SpecialClick(ID)
{
	$("#promo_id").val(ID);
	$.ajax({
			type: 'post',
			dataType: 'json',
			data:	{CriteriaD: ID},
			url: '../../includes/ajaxSpecialCriteria.php',
			success: function(response){
				if(response.result=="success"){

				var SpecialCriteria = response.SpecialCriteria;
				var NoOfWiks 		= response.NoOfWiks;
				var NoOfWiksto 		= response.NoOfWiksto;
				var StartWeek1 		= response.StartWeek1;
				var EndWeek1 		= response.EndWeek1;
				var StartWeek2 		= response.StartWeek2;
				var EndWeek2 		= response.EndWeek2;
				var StartWeek3 		= response.StartWeek3;
				var EndWeek3 		= response.EndWeek3;
				var StartWeek4 		= response.StartWeek4;
				var EndWeek4 		= response.EndWeek4;
				var MinVal 			= response.MinVal;
				var PromoID 		= response.PromoID;
				$("#test_overlay").fadeIn('slow');

				if(SpecialCriteria == 1){
					$("#activate").attr('checked','checked');
					$("#noofwiks").removeAttr("disabled","disabled");
					$("#NoOfWiksto").removeAttr("disabled","disabled");
					$("#SminValue").removeAttr("disabled","disabled");

									if (NoOfWiksto == 1 ){
											$("#txtSPStartDate1").removeAttr("disabled","disabled");
											$("#txtSPEndDate1").removeAttr("disabled","disabled");
											$("#txtSPStartDate2").removeAttr("disabled","disabled");
											$("#txtSPEndDate2").removeAttr("disabled","disabled");
											$("#txtSPStartDate3").removeAttr("disabled","disabled");
											$("#txtSPEndDate3").removeAttr("disabled","disabled");
											$("#txtSPStartDate4").removeAttr("disabled","disabled");
											$("#txtSPEndDate4").removeAttr("disabled","disabled");

											$("#txtSPStartDate1").val(StartWeek1);
											$("#txtSPEndDate1").val(EndWeek1);

											//Hide Callendar
											$(".swik2").hide();
											$(".ewik2").hide();
											$(".swik3").hide();
											$(".ewik3").hide();
											$(".swik4").hide();
											$(".ewik4").hide();
											//dissabled text

								}else if (NoOfWiksto == 2){
										$("#txtSPStartDate1").removeAttr("disabled","disabled");
										$("#txtSPEndDate1").removeAttr("disabled","disabled");
										$("#txtSPStartDate2").removeAttr("disabled","disabled");
										$("#txtSPEndDate2").removeAttr("disabled","disabled");
										$("#txtSPStartDate3").removeAttr("disabled","disabled");
										$("#txtSPEndDate3").removeAttr("disabled","disabled");
										$("#txtSPStartDate4").removeAttr("disabled","disabled");
										$("#txtSPEndDate4").removeAttr("disabled","disabled");

										$("#txtSPStartDate1").val(StartWeek1);
										$("#txtSPEndDate1").val(EndWeek1);
										$("#txtSPStartDate2").val(StartWeek2);
										$("#txtSPEndDate2").val(EndWeek2);

										$(".swik2").show();
										$(".ewik2").show();
										$(".swik3").hide();
										$(".ewik3").hide();
										$(".swik4").hide();
										$(".ewik4").hide();

								}else if (NoOfWiksto==3){
										$("#txtSPStartDate1").removeAttr("disabled","disabled");
										$("#txtSPEndDate1").removeAttr("disabled","disabled");
										$("#txtSPStartDate2").removeAttr("disabled","disabled");
										$("#txtSPEndDate2").removeAttr("disabled","disabled");
										$("#txtSPStartDate3").removeAttr("disabled","disabled");
										$("#txtSPEndDate3").removeAttr("disabled","disabled");
										$("#txtSPStartDate4").removeAttr("disabled","disabled");
										$("#txtSPEndDate4").removeAttr("disabled","disabled");
										$("#txtSPStartDate1").removeAttr("disabled","disabled");
										$("#txtSPEndDate1").removeAttr("disabled","disabled");

										$("#txtSPStartDate1").val(StartWeek1);
										$("#txtSPEndDate1").val(EndWeek1);
										$("#txtSPStartDate2").val(StartWeek2);
										$("#txtSPEndDate2").val(EndWeek2);
										$("#txtSPStartDate3").val(StartWeek3);
										$("#txtSPEndDate3").val(EndWeek3);


										$(".swik2").show();
										$(".ewik2").show();
										$(".swik3").show();
										$(".ewik3").show();
										$(".swik4").hide();
										$(".ewik4").hide();

								}else if (NoOfWiksto == 4){
										$("#txtSPStartDate1").removeAttr("disabled","disabled");
										$("#txtSPEndDate1").removeAttr("disabled","disabled");
										$("#txtSPStartDate2").removeAttr("disabled","disabled");
										$("#txtSPEndDate2").removeAttr("disabled","disabled");
										$("#txtSPStartDate3").removeAttr("disabled","disabled");
										$("#txtSPEndDate3").removeAttr("disabled","disabled");
										$("#txtSPStartDate4").removeAttr("disabled","disabled");
										$("#txtSPEndDate4").removeAttr("disabled","disabled");

										$("#txtSPStartDate1").val(StartWeek1);
										$("#txtSPEndDate1").val(EndWeek1);
										$("#txtSPStartDate2").val(StartWeek2);
										$("#txtSPEndDate2").val(EndWeek2);
										$("#txtSPStartDate3").val(StartWeek3);
										$("#txtSPEndDate3").val(EndWeek3);
										$("#txtSPStartDate4").val(StartWeek4);
										$("#txtSPEndDate4").val(EndWeek4);



										$(".swik2").show();
										$(".ewik2").show();
										$(".swik3").show();
										$(".ewik3").show();
										$(".swik4").show();
										$(".ewik4").show();
								}else{
										$("#txtSPStartDate1").removeAttr("disabled","disabled");
										$("#txtSPEndDate1").removeAttr("disabled","disabled");
										$("#txtSPStartDate2").removeAttr("disabled","disabled");
										$("#txtSPEndDate2").removeAttr("disabled","disabled");
										$("#txtSPStartDate3").removeAttr("disabled","disabled");
										$("#txtSPEndDate3").removeAttr("disabled","disabled");
										$("#txtSPStartDate4").removeAttr("disabled","disabled");
										$("#txtSPEndDate4").removeAttr("disabled","disabled");

										$(".swik2").hide();
										$(".ewik2").hide();
										$(".swik3").hide();
										$(".ewik3").hide();
										$(".swik4").hide();
										$(".ewik4").hide();

								}
				}else{
								if (NoOfWiksto == 1){
											$("#txtSPStartDate1").removeAttr("disabled","disabled");
											$("#txtSPEndDate1").removeAttr("disabled","disabled");
											$("#txtSPStartDate2").removeAttr("disabled","disabled");
											$("#txtSPEndDate2").removeAttr("disabled","disabled");
											$("#txtSPStartDate3").removeAttr("disabled","disabled");
											$("#txtSPEndDate3").removeAttr("disabled","disabled");
											$("#txtSPStartDate4").removeAttr("disabled","disabled");
											$("#txtSPEndDate4").removeAttr("disabled","disabled");

											$("#txtSPStartDate1").val(StartWeek1);
											$("#txtSPEndDate1").val(EndWeek1);

											//Hide Callendar
											$(".swik2").hide();
											$(".ewik2").hide();
											$(".swik3").hide();
											$(".ewik3").hide();
											$(".swik4").hide();
											$(".ewik4").hide();
											//dissabled text

								}else if (NoOfWiksto == 2){
										$("#txtSPStartDate1").removeAttr("disabled","disabled");
										$("#txtSPEndDate1").removeAttr("disabled","disabled");
										$("#txtSPStartDate2").removeAttr("disabled","disabled");
										$("#txtSPEndDate2").removeAttr("disabled","disabled");
										$("#txtSPStartDate3").removeAttr("disabled","disabled");
										$("#txtSPEndDate3").removeAttr("disabled","disabled");
										$("#txtSPStartDate4").removeAttr("disabled","disabled");
										$("#txtSPEndDate4").removeAttr("disabled","disabled");

										$("#txtSPStartDate1").val(StartWeek1);
										$("#txtSPEndDate1").val(EndWeek1);
										$("#txtSPStartDate2").val(StartWeek2);
										$("#txtSPEndDate2").val(EndWeek2);

										$(".swik2").show();
										$(".ewik2").show();
										$(".swik3").hide();
										$(".ewik3").hide();
										$(".swik4").hide();
										$(".ewik4").hide();

								}else if (NoOfWiksto==3){
										$("#txtSPStartDate1").removeAttr("disabled","disabled");
										$("#txtSPEndDate1").removeAttr("disabled","disabled");
										$("#txtSPStartDate2").removeAttr("disabled","disabled");
										$("#txtSPEndDate2").removeAttr("disabled","disabled");
										$("#txtSPStartDate3").removeAttr("disabled","disabled");
										$("#txtSPEndDate3").removeAttr("disabled","disabled");
										$("#txtSPStartDate4").removeAttr("disabled","disabled");
										$("#txtSPEndDate4").removeAttr("disabled","disabled");
										$("#txtSPStartDate1").removeAttr("disabled","disabled");
										$("#txtSPEndDate1").removeAttr("disabled","disabled");

										$("#txtSPStartDate1").val(StartWeek1);
										$("#txtSPEndDate1").val(EndWeek1);
										$("#txtSPStartDate2").val(StartWeek2);
										$("#txtSPEndDate2").val(EndWeek2);
										$("#txtSPStartDate3").val(StartWeek3);
										$("#txtSPEndDate3").val(EndWeek3);


										$(".swik2").show();
										$(".ewik2").show();
										$(".swik3").show();
										$(".ewik3").show();
										$(".swik4").hide();
										$(".ewik4").hide();

								}else if (NoOfWiksto==4){
										$("#txtSPStartDate1").removeAttr("disabled","disabled");
										$("#txtSPEndDate1").removeAttr("disabled","disabled");
										$("#txtSPStartDate2").removeAttr("disabled","disabled");
										$("#txtSPEndDate2").removeAttr("disabled","disabled");
										$("#txtSPStartDate3").removeAttr("disabled","disabled");
										$("#txtSPEndDate3").removeAttr("disabled","disabled");
										$("#txtSPStartDate4").removeAttr("disabled","disabled");
										$("#txtSPEndDate4").removeAttr("disabled","disabled");

										$("#txtSPStartDate1").val(StartWeek1);
										$("#txtSPEndDate1").val(EndWeek1);
										$("#txtSPStartDate2").val(StartWeek2);
										$("#txtSPEndDate2").val(EndWeek2);
										$("#txtSPStartDate3").val(StartWeek3);
										$("#txtSPEndDate3").val(EndWeek3);
										$("#txtSPStartDate4").val(StartWeek4);
										$("#txtSPEndDate4").val(EndWeek4);



										$(".swik2").show();
										$(".ewik2").show();
										$(".swik3").show();
										$(".ewik3").show();
										$(".swik4").show();
										$(".ewik4").show();
								}else{
										$("#txtSPStartDate1").removeAttr("disabled","disabled");
										$("#txtSPEndDate1").removeAttr("disabled","disabled");
										$("#txtSPStartDate2").removeAttr("disabled","disabled");
										$("#txtSPEndDate2").removeAttr("disabled","disabled");
										$("#txtSPStartDate3").removeAttr("disabled","disabled");
										$("#txtSPEndDate3").removeAttr("disabled","disabled");
										$("#txtSPStartDate4").removeAttr("disabled","disabled");
										$("#txtSPEndDate4").removeAttr("disabled","disabled");

										$(".swik2").hide();
										$(".ewik2").hide();
										$(".swik3").hide();
										$(".ewik3").hide();
										$(".swik4").hide();
										$(".ewik4").hide();

								}

				}
				$("#noofwiks").val(NoOfWiks);
				$("#NoOfWiksto").val(NoOfWiksto);
				$("#SminValue").val(MinVal);


				return false;
		}else{

			$("#promo_id").val(PromoID);
			$("#activate").attr('checked',false);
			$("#test_overlay").fadeIn('slow');
			$("#noofwiks").val(0);
			$("#noofwiks").attr("disabled","disabled");
			$("#txtSPStartDate1").attr("disabled","disabled");
			$("#txtSPEndDate1").attr("disabled","disabled");
			//hide start end end weeks
			$(".swik2").hide();
			$(".ewik2").hide();
			$(".swik3").hide();
			$(".ewik3").hide();
			$(".swik4").hide();
			$(".ewik4").hide();
			$("#SminValue").val("");
		}
		}
		})
		return false;
}

function SpecialCriteriaUpdate()
{
	if($('#activate').is(":checked") == true){
		if($("#noofwiks").val() > $("#NoOfWiksto").val()){
			alert('No of weeks From should be the same or lower than No of weeks to');
			return false;
		}else if($("#noofwiks").val() == 0 || $("#NoOfWiksto").val() == 0){
			alert('No of weeks from / to required.');
			return false;
		}
	}


	$.ajax({
		type: 'post',
		datatype: 'json',
		data: $('#form_specialcriteria').serialize(),
		url: '../../includes/ajaxSpecialCriteria.php?specialUpdate',
		success: function(resp){
				alert('update special criteria successful');
				$("#test_overlay").fadeOut('slow');
				return false;
			}
	});
return false;
}
