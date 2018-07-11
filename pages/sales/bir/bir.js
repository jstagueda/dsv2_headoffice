function showPage(page){
    $('[name=page]').val(page);
    $.ajax({
        type        :   "post",
        data        :   $('[name=frmbirbackend]').serialize(),
        url         :   "pages/sales/bir/ajax.php",
        success     :   function(data){
            $('.loader').html('&nbsp;');
            $('#pgContent').html(data).hide().fadeIn('slow');
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...').hide().fadeIn();
        }
    });
    return false;
}


function openPopUp(){
    var objWin;
    var datefrom = $('[name=txtStartDate]').val();
    var dateto = $('[name=txtEndDate]').val();
    var Branch = $('[name=Branch]').find(":selected").val();
    popuppage = "pages/sales/sales_birbackend_print.php?frmdte="+datefrom+"&todte="+dateto+"&branch="+Branch;
    if (!objWin){			
        objWin = NewWindow(popuppage,'printps','1000','500','yes');
    }
    return false;  		
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

//leave page popin
function leavepage(){
    
    $( "#dialog-message p" ).html("Do you want to leave this page?");
    $( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
        title: 'DSS Message',
        buttons: {
            "Yes"   :   function(){
                window.location.href = "index.php?pageid=18";
            },
            "No"    :   function(){$(this).dialog("close");}
        }
    });
    $( "#dialog-message" ).dialog( "open" );
    
}

$(function(){
    $("#txtStartDate, #txtEndDate").datepicker({
        changeMonth: true,
        changeYear: true
    });
});