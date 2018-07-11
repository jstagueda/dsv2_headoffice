$(function(){

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
            ac_branch = $('input[name="branch"]').val();
        }
    });
    
    $('[name=productfrom]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                data    :   {searched   :   request.term},
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxProductSubstitue.php",
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
            $('[name=productfromHidden]').val(ui.item.ID);
        }
    });

    $('[name=productto]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                data    :   {searched   :   request.term},
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxProductSubstitue.php",
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
            $('[name=producttoHidden]').val(ui.item.ID);
        }
    });

    $('[name=btnPrint]').click(function(){

        var param = "?" + $('[name=formproductsubstitute]').serialize();
        var objWin;
        popuppage = "pages/sales/prProductSubstitutePrint.php" + param;

        if (!objWin){
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }

    });

});

function NewWindow(mypage, myname, w, h, scroll){
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) {win.window.focus();}
}

function showPage(page){

    $('[name=page]').val(page);

    if($('[name=productfrom]').val() == ""){
        $('[name=productfromHidden]').val(0);
    }

    if($('[name=productto]').val() == ""){
        $('[name=producttoHidden]').val(0);
    }

    $.ajax({
        type        :   "post",
        url         :   "pages/sales/call_ajax/ajaxProductSubstitue.php",
        data        :   $('[name=formproductsubstitute]').serialize(),
        success     :   function(data){
            $('.loader').html('&nbsp;');
            $('.pgLoading').html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...');
        }
    });

    return false;
}