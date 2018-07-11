$(function(){
	
	branchAutocomplete('[name=frombranch]', '[name=frombranchHidden]');
	branchAutocomplete('[name=tobranch]', '[name=tobranchHidden]');
	
	$('[name=btnSearch]').click(function(){
		
		$.ajax({
			type	:	"post",
			url		:	"pages/datamanagement/system_param_call_ajax/ajaxTransferDealerToOtherBranch.php",
			data	:	{action : 'getCustomerList', branch : $('[name=frombranchHidden]').val(), IBM : $('[name=fromIBMHidden]').val()},
			success	:	function(data){
				$('.dealerlist').html(data);
			}
		});
		
	});
	
});

function checkedAll(field){
	
	$('.CustomerIDField').each(function(){
		this.checked = field.checked;
	});
	
}

function branchAutocomplete(field, hiddenfield){
	
	$(field).autocomplete({
		source : function(request, response){
			
			$.ajax({
				
				type	:	"post",
				dataType:	"json",
				url		:	"pages/datamanagement/system_param_call_ajax/ajaxTransferDealerToOtherBranch.php",
				data	:	{action : "getBranch", branch : request.term},
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
		select : function(event, ui){
			
			$(hiddenfield).val(ui.item.ID);
			
		}
	});
	
}


function IBMAutocomplete(field, hiddenfield, isFrom){

	if(isFrom == 1){
		branch = $('[name=frombranchHidden]').val();
	}else{
		branch = $('[name=tobranchHidden]').val();
	}
	
	$(field).autocomplete({
		source : function(request, response){
			
			$.ajax({
				
				type	:	"post",
				dataType:	"json",
				url		:	"pages/datamanagement/system_param_call_ajax/ajaxTransferDealerToOtherBranch.php",
				data	:	{action : "getIBM", IBM : request.term, branch : branch},
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
		select : function(event, ui){
			
			$(hiddenfield).val(ui.item.ID);
			
		}
	});
	
}