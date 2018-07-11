$(function(){
    
    ibmrange('ibmfrom', 'ibmfromHidden');
    ibmrange('ibmto', 'ibmtoHidden');
    
    $('[name=btnSearch]').click(function(){        
        showPage(1);        
        return false;
    });
    
    $('[name=btnPrint]').click(function(){
        var param = $('[name=IBMSalesPerformanceReport]').serialize();            
        var objWin;
        popuppage = "pages/sfpm/IBMSalesPerformancePrint.php?" + param;

        if (!objWin){			
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }

        return false;
    });
	
	$('[name=branchName]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	'post',
				dataType:	'json',
				url		:	'pages/sfpm/ajax_requests/ajaxIBMSalesPerfomance.php',
				data    :   {searchbranch   :   request.term},
				success	:	function(data){
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
		select	:	function(event, ui){
			$('[name=branch]').val(ui.item.ID);
		}
	});
    
});

function ibmrange(fieldname, hiddenfield){
    
    $('[name='+ fieldname +']').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                data    :   {searched   :   request.term},
                url     :   "pages/sfpm/ajax_requests/ajaxIBMSalesPerfomance.php",
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
            $('[name='+ hiddenfield +']').val(ui.item.ID);
        }
    });
    
}

function showPage(page){
    
    if($('[name=ibmfrom]').val() == ""){
        $('[name=ibmfromHidden]').val(0);
    }
    
    if($('[name=ibmto]').val() == ""){
        $('[name=ibmtoHidden]').val(0);
    }
    
    $('[name=page]').val(page);
    
    $.ajax({
        type        :   "post",
        data        :   $('[name=IBMSalesPerformanceReport]').serialize()+"&action=pagination",
        url         :   "pages/sfpm/ajax_requests/ajaxIBMSalesPerfomance.php",
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

function NewWindow(mypage, myname, w, h, scroll){
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) {win.window.focus();}
}