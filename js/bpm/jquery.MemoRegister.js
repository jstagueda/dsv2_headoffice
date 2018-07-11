/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 10, 2013
 */
$(function($){

	$('[name=btnPrint]').click(function(){
		var objWin;
		var param = $('[name=formMemoRegister]').serialize()+'&action=PrintDMCMRegister';
		popuppage = "pages/bpm/ajax_requests/MemoRegister.php?" + param;
		if (!objWin){			
			objWin = NewWindow(popuppage,'printps','1000','600','yes');
		}
		return false;
	});

    $('[name=datefrom]').datepicker({
		changeYear	:	true,
		changeMonth	:	true
	});
	
	$('[name=dateto]').datepicker({
		changeYear	:	true,
		changeMonth	:	true
	});
	
	$('[name=btnCancel]').click(function(){
		$('[name=formMemoRegister]')[0].reset();
		$('[name=page]').val(1);
		$('[name=branch]').val(0);
	});
	
    $('[name=branchList]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searched   :   request.term},
                url     :   "pages/bpm/ajax_requests/AppliedPaymentReport.php",
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Label,
                            value   :   item.Value,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=branch]').val(ui.item.ID);
        }
    });
	
	$('[name=btnSearch]').click(function(){
		showPage(1);
	});

});

function showPage(page){
	$('[name=page]').val(page);
	$.ajax({
		cache	: false,
		type	: 'POST',
		url		: 'pages/bpm/ajax_requests/MemoRegister.php',
		data	: $('[name=formMemoRegister]').serialize()+'&action=SearchMemoDetails',
		success	: function(data){
			$('.PageLoad').html(data).hide().fadeIn();
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
