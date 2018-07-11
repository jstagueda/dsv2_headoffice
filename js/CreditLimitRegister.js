$ = jQuery.noConflict();

$(function(){

	$("[name=btnSearch]").click(function(){
		showPage(1);
	});
	
	datepick($('[name=datefrom]'));
	datepick($('[name=dateto]'));
	
	SearchCustomer('[name=sfmfrom]', '[name=sfmfromhidden]');
	SearchCustomer('[name=sfmto]', '[name=sfmtohidden]');
	
	 $('[name=btnPrint]').click(function(){
	 
        var param = $('[name=creditlimitregisterform]').serialize();
       
        var objWin;
        popuppage = "pages/sfm/prCreditLimitRegister.php?" + param;

        if (!objWin){			
            objWin = NewWindow(popuppage,'printps','1000','600','yes');
        }
    });
	
	$('[name=branch]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	"pages/sfm/param_ajax_calls/ajaxCreditLimitRegister.php",
				data	:	{action : "GetBranch", branch : request.term},
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
			$('[name=branchID]').val(ui.item.ID);
		}
	});
	
	$('[name=sfmlevel]').change(function(){
		$('[name=sfmfrom]').val("");
		$('[name=sfmfromhidden]').val(0);
		$('[name=sfmto]').val("");
		$('[name=sfmtohidden]').val(0);
	});

});

function datepick(field){
	field.datepicker({
		changeMonth : true,
		changeYear : true
	});
}

function showPage(page){
	
	$('[name=page]').val(page);
	
	$.ajax({
		type	:	"post",
		url		:	"pages/sfm/param_ajax_calls/ajaxCreditLimitRegister.php",
		data	:	$('[name=creditlimitregisterform]').serialize() + "&action=ShowCreditLimit",
		success	:	function(data){
			$('.loader').html("&nbsp;");
			$('.pageloader').html(data);
		},
		beforeSend:	function(){
			$('.loader').html("Loading... Please wait...");
		}
	});
	
}

function SearchCustomer(field, hiddenfield){
	
	$(field).autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	"pages/sfm/param_ajax_calls/ajaxCreditLimitRegister.php",
				data	:	{action : "SearchedCustomer", sfmlevel : $('[name=sfmlevel]').val(), Customer : request.term, branchID : $('[name=branchID]').val()},
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
			$(hiddenfield).val(ui.item.ID);
		}
	});
	
}

function NewWindow(mypage, myname, w, h, scroll){
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) {win.window.focus();}
}