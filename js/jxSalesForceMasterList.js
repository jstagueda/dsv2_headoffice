$(function(){
    autocomplete('[name=codefrom]', '[name=codefromHidden]');
    autocomplete('[name=codeto]', '[name=codetoHidden]');


    $('[name=btnPrint]').click(function(){
        var param = "?" + $('[name=salesforcemasterlistform]').serialize();
        var objWin;
        popuppage = "pages/sfm/prSalesForceMasterListPrint.php" + param;

        if (!objWin){
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }

        return false;
    });

    $('[name=level]').change(function(){
        $('[name=codefrom]').val('');
        $('[name=codeto]').val('');
        $('[name=codefromHidden]').val(0);
        $('[name=codetoHidden]').val(0);
    });

    $('[name=branchSearch]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searchedbranch :   request.term},
                url     :   "pages/sfm/param_ajax_calls/ajaxSalesForceMasterList.php",
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
            $('[name=codefrom]').val('');
            $('[name=codeto]').val('');
            $('[name=codefromHidden]').val(0);
            $('[name=codetoHidden]').val(0);
        }
    });

});

function showPage(page){

    $('[name=page]').val(page);

    if($('[name=codefrom]').val() == ""){
        $('[name=codefromHidden]').val(0);
    }

    if($('[name=codeto]').val() == ""){
        $('[name=codetoHidden]').val(0);
    }

    $.ajax({
        type        :   "post",
        url         :   "pages/sfm/param_ajax_calls/ajaxSalesForceMasterList.php",
        data        :   $('[name=salesforcemasterlistform]').serialize(),
        success     :   function(data){
            $('.loader').html('&nbsp;');
            $('.pgLoad').html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...');
        }
    });

    return false;

}

function autocomplete(obj, objhidden){

    $(obj).autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   'post',
                dataType:   'json',
                url     :   'pages/sfm/param_ajax_calls/ajaxSalesForceMasterList.php',
                data    :   {
                    searched    :   request.term,
                    sfmlevel    :   $('[name=level]').val(),
                    branch      :   $('[name=branch]').val()
                },
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
            $(objhidden).val(ui.item.ID);
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