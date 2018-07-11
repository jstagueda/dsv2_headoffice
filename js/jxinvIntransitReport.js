//date picker
$(function(){        
    $("#datestart, #dateend").datepicker({
        changeMonth: true,
        changeYear: true
    });
    
    $('input[name=btnSearch]').click(function(){
        if($('#datestart').val() == ""){
            popinmessage("Please insert date from 'From Date' field.");
            return false;
        }
        
        if($('#dateend').val() == ""){
            popinmessage("Please insert date from 'To Date' field.");
            return false;
        }
        
        $('input[name=page]').val(1);
        $('input[name=action]').val("Submit");
        fnajax();
        return false;
    });
    
    $('input[name=btnPrint]').click(function(){
        var datestart = $('#datestart').val();
        var dateend = $('#dateend').val();
        var branch = $('select[name=branch]').val();
        var movementtype = $('select[name=movementtype]').val();
        
        var param = "?datestart=" + datestart +
                    "&dateend=" + dateend +
                    "&branch=" + branch +
                    "&movementtype=" + movementtype;
        
        if($('input[name=action]').val() == ""){
            popinmessage("Please submit parameter(s).");
            return false;
        }
        
        var objWin = "";
        popuppage = "pages/inventory/invIntransitReportPrint.php" + param;
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

//functio for pagination
function showPage(page){
    $('input[name=page]').val(page);
    $('input[name=action]').val("Paginate");
    fnajax();
}

//ajax function
function fnajax(){
    $.ajax({
        type        :   "Post",
        url         :   "pages/inventory/inventory_call_ajax/ajaxIntransitReport.php",
        data        :   $('form[name=intransitform]').serialize(), 
        success     :   function(data){
            $('.listtable').html(data).hide().fadeIn("slow");
            $('.loader').html('&nbsp;');
        },
        beforeSend  :   function(){
            $('.loader').html("Loading... Please wait...");
        }
    });
}