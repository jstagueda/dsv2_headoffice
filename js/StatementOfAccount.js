$ = jQuery.noConflict();

$(function(){
	
	$('[name=btnSearch]').click(function(){
		showPage(1);
	});
	
	$('[name=DateFrom]').datepicker({
		changeMonth : true,
		changeYear	: true
	});
	
	$('[name=DateTo]').datepicker({
		changeMonth : true,
		changeYear	: true
	});
	
	$('[name=Customer]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	'post',
				dataType:	'json',
				url		:	'pages/sfm/ajaxStatementOfAccount.php',
				data	:	{action : 'SearchCustomer', Customer : request.term, BranchID : $('[name=BranchID]').val()},
				success	:	function(data){
					response($.map(data, function(item){
						return{
							label : item.Label,
							value : item.Value,
							ID	  : item.ID
						}
					}));
				}
			});
		},
		select	:	function(event, ui){
			$('[name=CustomerID]').val(ui.item.ID);
		}
	});
	
	$('[name=BranchName]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	'post',
				dataType:	'json',
				url		:	'pages/sfm/ajaxStatementOfAccount.php',
				data	:	{action : 'SearchBranch', Branch : request.term},
				success	:	function(data){
					response($.map(data, function(item){
						return{
							label : item.Label,
							value : item.Value,
							ID	  : item.ID
						}
					}));
				}
			});
		},
		select	:	function(event, ui){
			$('[name=BranchID]').val(ui.item.ID);
		}
	});
	
	$('[name=btnPrint]').click(function(){
	
		var objWin;
		var param = $('[name=statementofaccountform]').serialize();
		popuppage = "pages/sfm/prStatementOfAccount.php?" + param;

		if (!objWin){
			objWin = NewWindow(popuppage,'printps','1000','500','yes');
		}
		
	});
	
});

function showPage(page){

	$('[name=page]').val(page);
	$.ajax({
		type	:	'post',
		url		:	'pages/sfm/ajaxStatementOfAccount.php',
		data	:	$('[name=statementofaccountform]').serialize()+"&action=SOAList",
		success	:	function(data){
			$('.statementofaccountlist').html(data).hide().fadeIn();
			$('.loader').html('&nbsp;');
		},
		beforeSend:	function(){
			$('.loader').html('Loading... Please wait...');
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