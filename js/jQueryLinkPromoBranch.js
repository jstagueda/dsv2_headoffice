$(document).ready(function(){
/*
 * Author: Gino C. Leabres
 * Date: 10/03/2013
 */
 GetListedPromo();

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
			url: 'pages/leaderlist/leaderlist_call_ajax/ajax_link_promo_branch.php',
			success: _ajaxDoResponsePromo
		});
		return false;
	});
 
});

function GetListedPromo()
{
	$.ajax({
		dataType:'json',
		type:'post',
		data:{'request':'GetListedPromo'},
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_link_promo_branch.php',
		success: _ajaxDoResponsePromo
	});
}

function _ajaxDoResponsePromo(response)
{
	
	var dynamic_html="";
			dynamic_html+='<tr class="tab">';
			dynamic_html+='<td width="10%" class="bdiv_r"><div align="center"><input id = "PromoMainChecker" type="checkbox" onclick = "CheckPromoAll(); "class="CheckAllPromo"></div></td>';
			dynamic_html+='<td width="25%" class="bdiv_r"><div align="left"><span class="txtredbold padl5">Promo Code</span></div></td>';
			dynamic_html+='<td width="50%" class="bdiv_r"><div align="left"><span class="txtredbold padl5">Promo Description</span></div></td>';
			dynamic_html+='<td width="20%" class="bdiv_r"><div align="right"><span class="txtredbold padr5">Branches</span></div></td>';
			dynamic_html+='</tr>';
	
	if(response['resp']=='success'){
		for(var i = 0; response['data_handler'].length > i; i++){
			dynamic_html += '<tr class="tab">';
			dynamic_html += '<td width="10%" class="bdiv_r"><div align="center">';
			dynamic_html += '<input name="PromoID[]" type="checkbox" id="PromoID" value="'+response["data_handler"][i].ID+'" class="CheckAllPromo" /></div>';
			dynamic_html += '</td>';
			dynamic_html += '<td width="25%" class="bdiv_r" ><div align="center"><span></span></div>'+response["data_handler"][i].Code+'</td>';
			dynamic_html += '<td width="50%" class="bdiv_r" ><div align="center"><span></span></div>'+response["data_handler"][i].Description+'</td>';
			dynamic_html += '<td width="20%" class="bdiv_r" ><div align="center"><span></span></div>'+response["data_handler"][i].TotaLinkBranches+'</td>';
			dynamic_html += '</tr>';
		}
	}else{
			dynamic_html += '<tr class="bgFFFFFF">';
			dynamic_html += '<td width="10%" height="220" valign="top" class="borderBR padl5" colspan="4">';
			dynamic_html += '<div align="center"><span class="">No record(s) display.</span></div>';
			dynamic_html += '</td>';
			dynamic_html += '</tr>';
	}
	$("#ListingPromo").html(dynamic_html);
	_ClearAll();
}

function CheckPromoAll()
{
	$("#PromoMainChecker").change(function(){
		if($(this).is(":checked")){
			$(".CheckAllPromo").attr("checked","checked");
		}else{
			$(".CheckAllPromo").removeAttr("checked","checked");
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
	if(validation==1){
		//save..
		if(confirm("Are you sure want to save this transaction?")==false){
			return false;
		}else{
			//do process here..
			$("#request").val("save");
			$.ajax({
				dataType:'json',
				type:'post',
				data:$("form[name=form_listing]").serialize(),
				url: 'pages/leaderlist/leaderlist_call_ajax/ajax_link_promo_branch.php',
				success: _ajaxDoResponsePromo
			});
			
			return false;
		}
	}else{
		if(confirm("Are you sure want to cancel this transaction?")==false){
			return false;
		}else{
			//do process here..
			_ClearAll();
			return false;
		}
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
		source:'pages/leaderlist/leaderlist_call_ajax/ajax_link_promo_branch.php?request=search&PromoType='+$("#cboPromoType").val(),
			select: function( event, ui ) {
				//$('option[name = "cboEPMG1" value=' + theValue + ']').attr('selected',true);
				$("#PromoID").val(ui.item.ID);
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
