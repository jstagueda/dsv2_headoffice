$ = jQuery.noConflict();
var urlpage = "pages/datamanagement/system_param_call_ajax/SICancellation.php";

$(function(){
	
	$('[name=btnSearch]').click(function(){
		showPage(1);
	});
	
	$("[name=DateFrom]").datepicker({
		maxDate	:	new Date($("[name=DateTo]").val()),
		onClose	:	function(SelectedDate){
			$("[name=DateTo]").datepicker("option", "minDate", SelectedDate);
		}
	});
	
	$("[name=DateTo]").datepicker({
		minDate	:	new Date($("[name=DateFrom]").val()),
		onClose	:	function(SelectedDate){
			$("[name=DateFrom]").datepicker("option", "MaxDate", SelectedDate);
		}
	});
	
	$("[name=Branch]").autocomplete({
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
			$("[name=BranchID]").val(ui.item.ID);
			$("[name=CustomerFrom]").val("");
			$("[name=CustomerFromHidden]").val(0);
			$("[name=CustomerTo]").val("");
			$("[name=CustomerToHidden]").val(0);
		}
	});
	
	//get customer range
	GetCustomer("[name=CustomerFrom]", "[name=CustomerFromHidden]");
	GetCustomer("[name=CustomerTo]", "[name=CustomerToHidden]");
	
});

function GetCustomer(field, HiddenField){
	
	$(field).autocomplete({
		source	:	function(request, response){
			if($("[name=Branch]").val() == "" || $("[name=BranchID]").val() == 0){
				$("[name=Branch]").focus();
				$("[name=CustomerFrom]").val("");
				$("[name=CustomerFromHidden]").val(0);
				$("[name=CustomerTo]").val("");
				$("[name=CustomerToHidden]").val(0);
				alert("Please select branch.");
				return false;
			}
			
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	urlpage,
				data	:	{action : "GetCustomer", Customer : request.term, BranchID : $("[name=BranchID]").val()},
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
			$(HiddenField).val(ui.item.ID);
		}
	});
	
}

function showPage(page){
	
	$('[name=page]').val(page);
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	$('[name=SICancellationForm]').serialize() + '&action=GetSalesInvoice',
		success	:	function(data){
			$('.loader').html("&nbsp;");
			$('.pageloader').html(data);
		},
		beforeSend:	function(){
			$('.loader').html("Loading... Please wait...");
		}
	});
	
}

function CancelSalesInvoice(SalesInvoiceID, BranchID){
	
	$.ajax({
		type	:	"post",
		url		:	urlpage,
		data	:	{action : "SICancellationReason", SalesInvoiceID : SalesInvoiceID, BranchID : BranchID},
		success	:	function(data){
			var btnfunction = {};
			btnfunction['Save'] = function(){
				SaveTransaction();				
			}
			
			btnfunction['Close'] = function(){
				$('.dialogmessage').dialog('close');
			}
			
			popupHTML(data, btnfunction);
		}
	});
	
}

function SaveTransaction(){
	
	if($('[name=SaveReasonCodeForm]').find('[name=ReasonCode]').val() == 0){
		alert("Please select reason code.");
		return false;
	}
	
	if($('[name=SaveReasonCodeForm]').find('[name=Remarks]').val().trim() == ""){
		$('[name=SaveReasonCodeForm]').find('[name=Remarks]').val("");
		alert("Please insert remarks.");
		return false;
	}
	
	$.ajax({		
		type	:	"post",
		dataType:	"json",
		url		:	urlpage,
		data	:	$('[name=SaveReasonCodeForm]').serialize() + "&action=SaveTransaction",
		success	:	function(data){
			var btnfunction = {}			
			
			if(data.Success == 1){
				btnfunction['Close'] = function(){
					showPage(1);
					$('.dialogmessage').dialog('close');
				}
			}else{
				btnfunction['Close'] = function(){
					$('.dialogmessage').dialog('close');
				}
			}
			
			popupHTML(data.ErrorMessage, btnfunction);
		}		
	});
	
	$('.dialogmessage').dialog('close');
	
}

function popupHTML(message, btnfunction){
	
	$('.dialogmessage').html(message);
	$('.dialogmessage').dialog({
		autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
		draggable:false,
        title: 'DSS Message',
        buttons: btnfunction
	});
	$('.dialogmessage').dialog('open');
	
}