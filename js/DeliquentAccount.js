$ = jQuery.noConflict();

var urlpage = "includes/ajaxDeliquentAccount.php";

$(function(){
	showPage(1);
	
	//add deliquent account
	$('[name=btnAddDeliquent]').click(function(){
	
		if($('[name=BranchID]').val() == 0 || $('[name=Branch]').val() == ""){
			alert("Please select branch.");
			$('[name=Branch]').focus();
			return false;
		}
		
		if($('[name=CustomerID]').val() == 0 || $('[name=CustomerCode]').val() == ""){
			alert("Please select customer.");
			$('[name=Branch]').focus();
			return false;
		}
		
		$.ajax({
			type	:	"post",
			dataType:	"json",
			url		:	urlpage,
			data	:	$('[name=DeliquentAccountForm]').serialize() + '&action=AddToDeliquentAccount',
			success	:	function(data){
				if(data.Success == 1){
				
					$('[name=CustomerCode]').val("");
					$('[name=CustomerID]').val(0);
					$('[name=Branch]').val("");
					$('[name=BranchID]').val(0);					
					
					$('.CustomerSelection').find('.trlist td:nth-child(3)').html("");
					$('.CustomerSelection').find('.trlist td:nth-child(4)').html("N/A");
					$('.CustomerSelection').find('.trlist td:nth-child(5)').html("N/A");
					$('.CustomerSelection').find('.trlist td:nth-child(6)').html("");
					$('.CustomerSelection').find('.trlist td:nth-child(7)').find('span').html("0.00");					
					$('.CustomerSelection').find('.trlist td:nth-child(7)').find('input').val("0");
					
					alert(data.ErrorMessage);
					showPage(1);
					
				}else{
					alert(data.ErrorMessage);
				}
			}
		});
		
		return false;
	});
	
	//search function
	$('[name=btnSearch]').click(function(){
		showPage(1);
	});
	
	//branch autocompleter
	$('[name=Branch]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	urlpage,
				data	:	{action : "GetBranch", Branch : request.term},
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
			$('[name=BranchID]').val(ui.item.ID);
		}
	});
	
	$('[name=SearchBranch]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	urlpage,
				data	:	{action : "GetBranch", Branch : request.term},
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
			$('[name=SearchedBranchID]').val(ui.item.ID);
		}
	});
	
	$('[name=CustomerCode]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	urlpage,
				data	:	{action : "GetCustomerDetails", BranchID : $('[name=BranchID]').val(), Customer : request.term},
				success	:	function(data){
					response($.map(data, function(item){
						return{
							label	:	item.Label,
							value	:	item.Value,
							ID		:	item.ID,
							Name	:	item.Name,
							Birthdate:	item.Birthdate,
							Gender	:	item.Gender,
							Balance	:	item.Balance,
							OutBalance:	item.OutBalance,
							Address	:	item.Address
						}
					}));
				}
			});
		},
		select	:	function(event, ui){
			$('[name=CustomerID]').val(ui.item.ID);
			$('.CustomerSelection').find('.trlist').find('td:nth-child(3)').html(ui.item.Name);
			$('.CustomerSelection').find('.trlist').find('td:nth-child(4)').html(ui.item.Birthdate);
			$('.CustomerSelection').find('.trlist').find('td:nth-child(5)').html(ui.item.Gender);
			$('.CustomerSelection').find('.trlist').find('td:nth-child(6)').html(ui.item.Address);
			$('.CustomerSelection').find('.trlist').find('td:nth-child(7) > span').html(ui.item.OutBalance);
			$('[name=OutstandingBalance]').val(ui.item.Balance);
		}
	});
	
});

function RemoveDelinquentAccount(field, CustomerCode, BranchCode){
	
	$.ajax({
		type	:	"post",
		dataType:	"json",
		url		:	urlpage,
		data	:	{action : "RemoveDelinquentAccount", CustomerCode : CustomerCode, BranchCode : BranchCode},
		success	:	function(data){
			alert(data.ErrorMessage);
			
			if(data.Success == 1){
				$(field).closest('.trlist').remove();
				
				if($('.DeliquentAccountList').find('.trlist').length == 0){
					$('.DeliquentAccountList').find('table').append('<tr class="trlist"><td colspan="8" align="center">No result found.</td></tr>');
				}
			}
		}
	});
	
}

//listing of deliquent account...
function showPage(page){
	
	$('[name=page]').val(page);
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "DeliquentAccountList", BranchID : $('[name=SearchedBranchID]').val(), Searched : $('[name=SearchBar]').val(), page : page},
		success	:	function(data){
			$('.loader').html('');
			$('.DeliquentAccountList').html(data);
		},
		beforeSend:	function(){
			$('.loader').html('Searching... Please wait...');			
		}
	});
	
}

