
$(function(){
    //load list of promo
    showPage(1);
    
    $('[name=btnSearch]').click(function(){
        showPage(1);
        return false;
    });
    
});



function NewWindow(mypage, myname, w, h, scroll) 
{
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp(ObjLink) 
{
    var objWin;
    popuppage = ObjLink;

    if (!objWin) 
    {			
            objWin = NewWindow(popuppage,'printps','1500','700','yes');
    }

    return false;  		
}

function showPage(page){
    $('[name=page]').val(page);
    
    $.ajax({
        type        :   "post",
        url         :   "pages/leaderlist/leaderlist_call_ajax/ajaxSpecialPromo.php?action=pagination",
        data        :   $('[name=frmSpecialPromo]').serialize(),
        beforeSend  :   function(){
            //popinmessage("Loading... Please wait...");
        },
        success     :   function(data){
            //$('#dialog-message').dialog("close");
            $('.pgLoad').html(data);
        }
        
    });
    return false;
}


function showSpecialPromo(spid, pid){
    
    objLink = "pages/leaderlist/specialPromoDetails.php?spid="+spid+"&pid="+pid;
    openPopUp(objLink);
    return false;
    
}