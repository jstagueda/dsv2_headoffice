$(function(){

    $('[name=customerfrom]').autocomplete({
        source      :   function(request, response){
            $.ajax({
                url         :   "pages/sales/call_ajax/ajaxAdvancePORegister.php",
                data        :   {searched : request.term, branchId : $('[name=branch]').val()},
                dataType    :   "json",
                type        :   "post",
                success     :   function(data){
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
        select      :   function(event, ui){
            $('[name=customerfromHidden]').val(ui.item.ID);
        }
    });


    $('[name=customerto]').autocomplete({
        source      :   function(request, response){
            $.ajax({
                url         :   "pages/sales/call_ajax/ajaxAdvancePORegister.php",
                data        :   {searched : request.term, branchId : $('[name=branch]').val()},
                dataType    :   "json",
                type        :   "post",
                success     :   function(data){
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
        select      :   function(event, ui){
            $('[name=customertoHidden]').val(ui.item.ID);
        }
    });


    //for printing..
    $('[name=btnPrint]').click(function(){
        var param = "?" + $('[name=formibmsalesreport]').serialize();
        var objWin;
        popuppage = "pages/sales/prAdvancePORegisterPrint.php" + param;

        if (!objWin){
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }
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

    if($('[name=customerfrom]').val() == ""){
        $('[name=customerfromHidden]').val(0);
    }

    if($('[name=customerto]').val() == ""){
        $('[name=customertoHidden]').val(0);
    }

    $.ajax({
        type        :   'post',
        url         :   'pages/sales/call_ajax/ajaxAdvancePORegister.php',
        data        :   $('[name=formibmsalesreport]').serialize(),
        success     :   function(data){
            $('.loader').html('&nbsp;');
            $('.pgLoading').html(data);
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...');
        }
    });
    return false;

}