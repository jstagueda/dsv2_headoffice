$(function(){
	
	$('[name=branchName]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	'post',
				dataType:	'json',
				url		:	'pages/sales/call_ajax/ajaxOfficialReceiptRegister.php',
				data	:	{action : 'getBranch', BranchName : request.term},
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
	
    $('[name=datefrom], [name=dateto]').datepicker({
        changeMonth :   true,
        changeYear  :   true
    });

    $('[name=sfmlevel]').change(function(){
        $('[name=sfaccountfrom]').val('');
        $('[name=sfaccountfromHidden]').val(0);
        $('[name=sfaccountto]').val('');
        $('[name=sfaccounttoHidden]').val(0);
    });

    $('[name=btnPrint]').click(function(){
        var param = "?" + $('[name=officialreceiptregisterform]').serialize();

        var objWin;
        popuppage = "pages/sales/prOfficialReceiptRegisterPrint.php" + param;

        if (!objWin){
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }
    });

    salesforceaccount('[name=sfaccountfrom]', '[name=sfaccountfromHidden]');
    salesforceaccount('[name=sfaccountto]', '[name=sfaccounttoHidden]');

});

function salesforceaccount(field, hiddenfield){

    $(field).autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxOfficialReceiptRegister.php",
                data    :   {searched   :   request.term, sfmlevel  :   $('[name=sfmlevel]').val()},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            value   :   item.Value,
                            label   :   item.Label,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $(hiddenfield).val(ui.item.ID);
        }

    });

}

function showPage(page){
    if($('[name=sfaccountfrom]').val() == ""){
        $('[name=sfaccountfromHidden]').val(0);
    }

    if($('[name=sfaccountto]').val() == ""){
        $('[name=sfaccounttoHidden]').val(0);
    }

    $('[name=page]').val(page);

    $.ajax({
        type        :   "post",
        data        :   $('[name=officialreceiptregisterform]').serialize(),
        url         :   "pages/sales/call_ajax/ajaxOfficialReceiptRegister.php",
        success     :   function(data){
            $('.loader').html('&nbsp;');
            $('#pgContent').html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...');
        }
    });

    return false;

}

function NewWindow(mypage, myname, w, h, scroll){
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) {win.window.focus();}
}