$(function()
{  
    $('[name=btnPrint]').click(function(){
        if($('[name=action]').val() == ""){
            popinmessage("Please submit parameters.");
            return false;
        }
        //alert("Under Construction!");
        var param = "?displaytype="+$('[name=displaytype]').val()
                    +"&datestart="+$('[name=datestart]').val()
                    +"&dateend="+$('[name=dateend]').val()
                    +"&sidtype="+$('[name=sidtype]').val()
					+"&branch="+$('[name=branch]').val();
       
        var objWin;
        popuppage = "pages/datamanagement/prSIDErrorLogPrint.php" + param;

        if (!objWin){			
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }
    });
	
	
	$('[name=branchName]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	'post',
				dataType:	'json',
				url		:	'includes/jxSIDErrorLogReport.php',
				data	:	{searchBranch	:	request.term},
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
    $('[name=action]').val('submitted');
    
    $.ajax({
        url         :   "includes/jxSIDErrorLogReport.php",
        data        :   $('[name=frmRecruitersReport]').serialize(),
        type        :   "post",
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