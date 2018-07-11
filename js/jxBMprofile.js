$(function()
{
	
	if($('[name=branch]').val() !='')
	{
	   showPage(1, '');
	}
    
	
	$('[name=branchName]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sfpm/ajax_requests/ajaxBMprofile.php",
                data    :   {searchbranch   :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Label,
							value	:	item.Value,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=branch]').val(ui.item.ID);
			$('[name=IBMfrom]').val("");
			$('[name=IBMfromHidden]').val(0);
			$('[name=IBMto]').val("");
			$('[name=IBMtoHidden]').val(0);
        }
    });
	
    $('[name=IBMfrom]').autocomplete({
        source  :   function(request, response)
		{
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sfpm/ajax_requests/ajaxBMprofile.php",
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
        select  :   function(event, ui)
		{
            $('[name=IBMfromHidden]').val(ui.item.ID);
        }
    });
        
       
    $('[name=IBMto]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                dataType:   "json",
                url     :   "pages/sfpm/ajax_requests/ajaxBMprofile.php",
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
            $('[name=IBMtoHidden]').val(ui.item.ID);
        }
    });
    
    
    $('[name=btnSearch]').click(function()
	{
        return showPage(1, '');
        return false;
    });
    
    $('[name=btnPrint]').click(function(){
        var param = $('[name=formibmsalesreport]').serialize();

        var objWin;
        popuppage = "pages/sfm/pBMCmasterlist.php?" + param;

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

function showPage(page, customertype)
{
    $.ajax({
        type        :   "post",
        data        :   $('[name=formibmsalesreport]').serialize(),
        url         :   "pages/sfpm/ajax_requests/ajaxBMprofile.php",
        success     :   function(data)
		{
            $('.loader').html('');
            $('.pgLoading').html(data);
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...').hide().fadeIn();
        }
    });
    
    return false;
    
}