$ = jQuery.noConflict();

$(function(){
	
	$('[name=sfmlevel]').change(function(){
		$("[name=sfaccountfrom]").val("");
		$("[name=sfaccountfromHidden]").val(0);
		$("[name=sfaccountto]").val("");
		$("[name=sfaccounttoHidden]").val(0);
	});
	
	$('[name=btnSubmit]').click(function(){
		showPage(1);
	});
	
	dynamicdatepicker($('[name=datefrom]'));
	dynamicdatepicker($('[name=dateto]'));
	
	SearchCustomer($("[name=sfaccountfrom]"), $("[name=sfaccountfromHidden]"));
	SearchCustomer($("[name=sfaccountto]"), $("[name=sfaccounttoHidden]"));
	
	$('[name=btnPrint]').click(function(){
		var objWin;
		var param = $('[name=provisionalreceiptregisterform]').serialize();
		popuppage = "pages/sales/ProvisionalReceiptRegisterPrint.php?" + param;

		if (!objWin){
			objWin = NewWindow(popuppage,'printps','1000','600','yes');
		}
	});
	
	$('[name=Branch]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	"pages/sales/call_ajax/ajaxProvisionalReceipt.php",
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
	
});

function dynamicdatepicker(datefield){
	datefield.datepicker({
		changeMonth	:	true,
		changeYear	:	true
	});
}

function SearchCustomer(CustomerField, CustomerHiddenField){
	
	CustomerField.autocomplete({
		
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	"pages/sales/call_ajax/ajaxProvisionalReceipt.php",
				data	:	{action : "SearchCustomer", customer : request.item, sfmlevel : $('[name=sfmlevel]').val(), BranchID : $('[name=BranchID]').val()},
				success	:	function(data){
					response($.map(data, function(item){
						return {
							label	:	item.Label,
							value	:	item.Value,
							ID		:	item.ID
						}
					}));
				}
			});
		},
		select	:	function(event, ui){
			CustomerHiddenField.val(ui.item.ID);
		}
		
	});
	
}

function showPage(page){
	
	$('[name=page]').val(page);
	
	$.ajax({
		type	:	"post",
		url		:	"pages/sales/call_ajax/ajaxProvisionalReceipt.php",
		data	:	$('[name=provisionalreceiptregisterform]').serialize() + "&action=GetProvisionalReceiptList",
		success	:	function(data){
			$('.loader').html("&nbsp;");
			$('#pgContent').html(data).hide().fadeIn();
		},
		beforeSend:	function(){
			$('.loader').html("Loading... Please wait...");
		}
	});
	
}

function NewWindow(mypage, myname, w, h, scroll)
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}