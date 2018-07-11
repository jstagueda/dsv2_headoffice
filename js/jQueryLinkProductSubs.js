$(document).ready(function(){
/*
 * Author: Gino C. Leabres
 * Date: 10/03/2013
 */
 GetListedProductSubs();

	$("select[name=cboPromoType]").change(function(){
		$("#PromoID").val("");
		$("#txtSearch").val("");
		var PromoType = $(this).val();
		$("#cboPromoType").val(PromoType);
	});
	
	//search here..
	
	$("input[name=btnSearch]").click(function(){
		var dynamic_html1 ="";
		dynamic_html1 += '<tr class="bgFFFFFF">';
		dynamic_html1 += '<td width="10%" height="220" valign="top" class="borderBR padl5" colspan="4">';
		dynamic_html1 += '<div align="center"><span class="">Fetching Data Please wait.</span></div>';
		dynamic_html1 += '</td>';
		dynamic_html1 += '</tr>';
		$("#ListingPromo").html(dynamic_html1);
		$("#request").val("SearchPromo");
		$.ajax({
			dataType:'json',
			type:'post',
			data: $("form[name=form_listing]").serialize(),
			url: 'pages/leaderlist/leaderlist_call_ajax/ajax_link_product_subs.php',
			success: _ajaxDoResponsePromo
		});
		return false;
	});
 
});

function GetListedProductSubs()
{
	$.ajax({
		dataType:'json',
		type:'post',
		data:{'request':'GetListedProductSubs'},
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_link_product_subs.php',
		success: _ajaxDoResponsePromo
	});
}

function _ajaxDoResponsePromo(response)
{
	
	var dynamic_html="";
			dynamic_html+='<tr class="trheader">';
			dynamic_html+='<td class="bdiv_r"><div align="center"><input id = "PromoSubsMainChecker" type="checkbox" onclick = "CheckPromoAll(); "class="CheckAllProductSubs"></div></td>';
			dynamic_html+='<td class="bdiv_r"><div align="left"><span class="txtredbold padl5">Code</span></div></td>';
			dynamic_html+='<td class="bdiv_r"><div align="left"><span class="txtredbold padl5">Description</span></div></td>';
			dynamic_html+='<td class="bdiv_r"><div align="right"><span class="txtredbold padr5">No. of Subs</span></div></td>';
			dynamic_html+='<td class="bdiv_r"><div align="right"><span class="txtredbold padr5">Branches</span></div></td>';
			dynamic_html+='</tr>';
	
	if(response['resp']=='success'){
		for(var i = 0; response['data_handler'].length > i; i++){
			dynamic_html += '<tr class="trlist">';
			dynamic_html += '<td>';
			dynamic_html += '<input name="psid[]" type="checkbox" id="psid" value="'+response["data_handler"][i].PSID+'" class="CheckAllProductSubs" />';
			dynamic_html += '</td>';
			dynamic_html += '<td>'+response["data_handler"][i].Code+'</td>';
			dynamic_html += '<td>'+response["data_handler"][i].Description+'</td>';
			dynamic_html += '<td align="center">'+response["data_handler"][i].NoofSubs+'</td>';
			dynamic_html += '<td align="center">'+response["data_handler"][i].TotaLinkBranches+'</td>';
			dynamic_html += '</tr>';
		}
	}else{
			dynamic_html += '<tr class="trlist">';
			dynamic_html += '<td colspan="4">';
			dynamic_html += '<div align="center"><span class="">No record(s) display.</span></div>';
			dynamic_html += '</td>';
			dynamic_html += '</tr>';
	}
	$("#ListingPromo").html(dynamic_html);
	_ClearAll();
}

function CheckPromoAll()
{
	$("#PromoSubsMainChecker").change(function(){
		if($(this).is(":checked")){
			$(".CheckAllProductSubs").attr("checked","checked");
		}else{
			$(".CheckAllProductSubs").removeAttr("checked","checked");
		}
	})
}

function checkAllbranch()
{
	$("#chkAll").change(function(){
		if($(this).is(":checked")){
			$(".inputOptChk").attr("checked","checked");
		}else{
			$(".inputOptChk").removeAttr("checked","checked");
		}
	})
}

function ConfirmSave(validation)
{
	var title = "Link Product Substitute to Branches";
	var message = "";
	var buttonFunction = {};

	if(validation==1){
		//save..		
		message = "Are you sure want to save this transaction?";
		buttonFunction['Yes'] = function(){
			$('#dialog-message').dialog('close');
			$("#request").val("save");
			$.ajax({
				dataType:'json',
				type:'post',
				data:$("form[name=form_listing]").serialize(),
				url: 'pages/leaderlist/leaderlist_call_ajax/ajax_link_product_subs.php',
				success: _ajaxDoResponsePromo
			});
		}
		
		buttonFunction['No'] = function(){
			$('#dialog-message').dialog('close');
		}
		
		dialogmessage(title, message, buttonFunction);		
		return false;
		
	}else{
		message = "Are you sure want jo cancel this transaction?";
		buttonFunction['Yes'] = function(){
			$('#dialog-message').dialog('close');
			_ClearAll();
		}
		
		buttonFunction['No'] = function(){
			$('#dialog-message').dialog('close');
		}
		
		dialogmessage(title, message, buttonFunction);		
		return false;
	}
}

function _ClearAll()
{
	$(".CheckAllPromo").removeAttr("checked","checked");
	$(".inputOptChk").removeAttr("checked","checked");
	$("#txtSearch").val("");
	$("select[name=cboPromoType]").val(0);
	$("#request").val("");
}

function OnkeySearch()
{
	$('#txtSearch').autocomplete({
		source:'pages/leaderlist/leaderlist_call_ajax/ajax_link_product_subs.php?request=search',
			select: function( event, ui ) {
				//$('option[name = "cboEPMG1" value=' + theValue + ']').attr('selected',true);
				$("#ProductID").val(ui.item.ID);
				$("#txtSearch").val(ui.item.Code);
			return false;
		}
	}).data( "ui-autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.Code + "</strong> - " + item.Description + "</a>" )
			.appendTo( ul );
	};
}

function dialogmessage(title, message, buttonFunction){
	$( "#dialog-message p" ).html(message);
    $( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
		draggable: false,
        title: title,
        buttons: buttonFunction
    });
    $( "#dialog-message" ).dialog( "open" );
}