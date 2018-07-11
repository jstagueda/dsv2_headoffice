$(function(){
    
    $('[name=datefrom], [name=dateto]').datepicker({
        changeMonth :   true,
        changeYear  :   true
    });
    
    $('[name=customerfrom]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxCollectionDueReport.php",
                data    :   {
                                searched    :   request.term,
                                sfmlevel    :   $('[name=sfmlevel]').val(),
								branch		:	$('[name=branchID]').val()
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
            return false;
        },
        select  :   function(event, ui){
            $('[name=customerfromHidden]').val(ui.item.ID);
        }
    });
    
    $('[name=customerto]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxCollectionDueReport.php",
                data    :   {
                                searched    :   request.term,
                                sfmlevel    :   $('[name=sfmlevel]').val(),
								branch		:	$('[name=branchID]').val()
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
            return false;
        },
        select  :   function(event, ui){
            $('[name=customertoHidden]').val(ui.item.ID);
        }
    });
    
    $('[name=sfmlevel]').change(function(){
        $('[name=customerfrom]').val('');
        $('[name=customerto]').val('');
        $('[name=customerfromHidden]').val(0);
        $('[name=customertoHidden]').val(0);
    });
	
	$('[name=branch]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sales/call_ajax/ajaxCollectionDueReport.php",
                data    :   {
                                branch	    :   request.term,
								action		:	"GetBranch"
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
            return false;
        },
        select  :   function(event, ui){
            $('[name=branchID]').val(ui.item.ID);
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
    
    if($('[name=sfmlevel]').val() == 0){
        popinmessage("Please select SF Level.");
        return false;
    }
    
    $('[name=page]').val(page);
    
    $.ajax({
        type        :   "post",
        data        :   $('[name=frmNetworkReport]').serialize(),
        url         :   "pages/sales/call_ajax/ajaxCollectionDueReport.php",
        success     :   function(data){
            $('.loader').html('&nbsp;');
            $('.loadpage').html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...');
        }
    });
    
    return false;
}

function printreport(){
    
    var param = $('[name=frmNetworkReport]').serialize();            
    var objWin;
    popuppage = "pages/sales/prCollectionDueReportPrint.php?" + param;
    
    if (!objWin){			
        objWin = NewWindow(popuppage,'printps','1000','500','yes');
    }
            
    return false;    
}