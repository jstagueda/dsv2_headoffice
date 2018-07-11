$(function(){
    //for printing..
    $('[name=btnPrint]').click(function(){
        var param = "?" + $('[name=formibmsalesreport]').serialize();
        var objWin;
        popuppage = "pages/sales/prLoyaltyProductListprint.php" + param;

        if (!objWin){
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }
    });

    $('[name=LoyaltyList]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searched   :   request.term , action: 'loyalty' },
                url     :   "pages/sales/call_ajax/ajaxLoyaltyProductList.php",
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
            $('[name=Loyalty]').val(ui.item.ID);
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

function showPage(page)
{
	
	if($('[name=Loyalty]').val() == '0')
	{
		alert('Please input valid Loyalty Program');
		return false;
	}
	
    $('[name=page]').val(page);

    if($('[name=customerfrom]').val() == ""){
        $('[name=customerfromHidden]').val(0);
    }

    if($('[name=customerto]').val() == ""){
        $('[name=customertoHidden]').val(0);
    }

    $.ajax({
        type        :   'post',
        url         :   'pages/sales/call_ajax/ajaxLoyaltyProductList.php',
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