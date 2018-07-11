function openPopUp(){
    
    var param = "?dteFrom=" + $('[name=txtStartDates]').val() +
                "&dteTo=" + $('[name=txtEndDates]').val() +
                "&mtid=" + $('[name=cboWarehouse]').val() +
                "&search=" + $('[name=txtSearch]').val() +
				"&branch=" + $('[name=branch]').val();
    
    var objWin;
    popuppage = "pages/inventory/inv_txnregisterprint.php" + param;
    if (!objWin){			
        objWin = NewWindow(popuppage,'printps','1000','500','yes');
    }

    return false;
}

function NewWindow(mypage, myname, w, h, scroll){
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) {win.window.focus();}
}

function showPage(page){
    $('[name=page]').val(page);
    
    $.ajax({
        type        :   "post",
        data        :   $('[name=frmStockLog]').serialize() + "&action=GetTransactionList",
        url         :   "includes/jxPagingInvTxnRegister.php",
        success     :   function(data){
            $('.loader').html('&nbsp;');
            $('#pgContent').html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...').hide().fadeIn();
        }
    });
    
    return false;
    
}

$(function(){
    $("#txtStartDates, #txtEndDates").datepicker({
        changeMonth: true,
        changeYear: true
    });
	
	$('[name=branchName]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	'post',
				dataType:	'json',
				url		:	'includes/jxPagingInvTxnRegister.php',
				data	:	{searchbranch : request.term, action : "GetBranch"},
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
			$('[name=branch]').val(ui.item.ID);
		}
	});
});