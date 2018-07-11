function showPage(str){
    if($('[name=salesforcelevel]').val() == 0){
        popinmessage("Please select from 'Sales Force Level' field.");
        return false;
    }
    
    /*if($('[name=salesforcefromHidden]').val() == ""){
        popinmessage("Please select from 'Customer Range'.");
        return false;
    }
    
    if($('[name=salesforcetoHidden]').val() == ""){
        popinmessage("Please select from 'Customer Range'.");
        return false;
    }*/
    
    if($('[name=campaign]').val() == ""){
        popinmessage("Please insert value to 'Campaign' field.");
        return false;
    }
    
    $('[name=page]').val(str);
    
    if($('[name=salesforcefrom]').val() == ""){
        $('[name=salesforcefromHidden]').val(0);
    }
    
    if($('[name=salesforceto]').val() == ""){
        $('[name=salesforcetoHidden]').val(0);
    }
    
    ajaxfn('.tablelist');
}

function ajaxfn(divpart){
    $.ajax({
        data        :   $('[name=frmValuationReport]').serialize(),
        type        :   "Post",
        url         :   "includes/jxcustomerDGSReport.php",
        success     :   function(data){
            $('.loader').html('&nbsp;');
            $(divpart).html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...').hide().fadeIn();
        }
    });
}

$(function(){

	$('[name=branchName]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	"pages/sales/call_ajax/ajaxcampaignDGSReport.php",
				data	:	{branchName	:	request.term},
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
        
    $('input[name=salesforcefrom]').keydown(function(){
        if($('[name=salesforcelevel]').val() == 0){
            popinmessage("Please select from 'Sales Force Level' field.");
            return false;
        }
    });
    
    //auto completer for salesforce range
    $('input[name=salesforcefrom]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type        :   "post",
                dataType    :   "json",
                data        :   {
                    searched    :   request.term,
                    sfmlevel    :   $('[name=salesforcelevel]').val(),
                    sfmfrom     :   "sfmfrom",
					branch		:	$('[name=branch]').val()
                },
                url         :   "pages/sales/call_ajax/ajaxcampaignDGSReport.php",
                success     :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Name,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('input[name=salesforcefromHidden]').val(ui.item.ID);
        }
    });
    
    $('input[name=salesforceto]').keydown(function(){
        if($('[name=salesforcelevel]').val() == 0){
            popinmessage("Please select from 'Sales Force Level' field.");
            return false;
        }
    });
    
    //auto completer for salesforce range
    $('input[name=salesforceto]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type        :   "post",
                dataType    :   "json",
                data        :   {
                    searched    :   request.term,
                    sfmlevel    :   $('[name=salesforcelevel]').val(),
                    sfmto       :   "sfmto",
					branch		:	$('[name=branch]').val()
                },
                url         :   "pages/sales/call_ajax/ajaxcampaignDGSReport.php",
                success     :   function(data){
                    response($.map(data, function(item){
                        return{
                            label   :   item.Name,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('input[name=salesforcetoHidden]').val(ui.item.ID);
        }
    });
    
    //removing all values to all input when changing the value of sfm level
    $('[name=salesforcelevel]').change(function(){
        $('[name=salesforcefrom]').val('');
		$('[name=salesforceto]').val('');
		
		$('[name=salesforcefromHidden]').val(0);
		$('[name=salesforcetoHidden]').val(0);
				
        $('[name=btnSubmit]').val('Submit');
        $('[name=btnPrint]').val('Print');
        $('[name=btnBack]').val('Back');
    });
    
    //for printing customer DGS
    $('[name=btnPrint]').click(function(){
        if($('[name=salesforcelevel]').val() == 0){
            popinmessage("Please select from 'Sales Force Level' field.");
            return false;
        }

        if($('[name=salesforcefromHidden]').val() == ""){
            popinmessage("Please select from 'Customer Range'.");
            return false;
        }

        if($('[name=salesforcetoHidden]').val() == ""){
            popinmessage("Please select from 'Customer Range'.");
            return false;
        }

        if($('[name=campaign]').val() == ""){
            popinmessage("Please insert value to 'Campaign' field.");
            return false;
        }
        
        var param = '?sfmlevel='+$('[name=salesforcelevel]').val()+
                    '&customerfrom='+$('[name=salesforcefromHidden]').val()+
                    '&customerto='+$('[name=salesforcetoHidden]').val()+
                    '&campaign='+$('[name=campaign]').val()+
                    '&sortby='+$('[name=sortby]').val()+
                    '&topnth='+$('[name=topnth]').val()+
					'&branch='+$('[name=branch]').val();
        var objWin;
        popuppage = "pages/sales/prCustomerDGSReportPrint.php" + param;
        if (!objWin){			
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }
    });
});

//open a new window
function NewWindow(mypage, myname, w, h, scroll){
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    
    if (parseInt(navigator.appVersion) >= 4){
        win.window.focus();
    }
}