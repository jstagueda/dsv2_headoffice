$(function(){
    
	$('[name=branchName]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sfpm/ajax_requests/ajaxDealerSalesReport.php",
                data    :   {searchedbranch   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Name,
                            ID      :   item.ID,
							value	:	item.Value
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=branch]').val(ui.item.ID);
			$('[name=dealerfrom]').val("");
			$('[name=dealerfromHidden]').val(0);
			$('[name=dealerto]').val("");
			$('[name=dealertoHidden]').val(0);
        }
    });
	
    $('[name=dealerfrom]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sfpm/ajax_requests/ajaxDealerSalesReport.php",
                data    :   {searched   :   request.term, branch	:	$('[name=branch]').val()},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Name,
                            ID      :   item.ID,
							value	:	item.Value
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=dealerfromHidden]').val(ui.item.ID);
        }
    });
        
       
    $('[name=dealerto]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sfpm/ajax_requests/ajaxDealerSalesReport.php",
                data    :   {searched   :   request.term, branch	:	$('[name=branch]').val()},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Name,
                            ID      :   item.ID,
							value	:	item.Value
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=dealertoHidden]').val(ui.item.ID);
        }
    });
    
    
    $('[name=accumulativePO]').keyup(function(){
        if(isNaN($(this).val())){
            $(this).val('');
            popinmessage("Please insert numeric value.");
            return false;
        }
    });
    
    $('[name=amount]').keyup(function(){
        if(isNaN($(this).val())){
            $(this).val('');
            popinmessage("Please insert numeric value.");
            return false;
        }
    });
    
    $('[name=btnSearch]').click(function(){
        return showPage(1);
        return false;
    });
    
    $('[name=btnPrint]').click(function(){
        var param = "?"+$('[name=formdealersales]').serialize();

        var objWin;
        popuppage = "pages/sfpm/prDealerSalesReportPrint.php" + param;

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
    $.ajax({
        type        :   "post",
        data        :   $('[name=formdealersales]').serialize(),
        url         :   "pages/sfpm/ajax_requests/ajaxDealerSalesReport.php",
        success     :   function(data){
            $('.loader').html('&nbsp;');
            $('.pgLoading').html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...').hide().fadeIn();
        }
    });
    
    return false;
    
}