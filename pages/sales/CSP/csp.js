$ = jQuery.noConflict();

$(document).ready(function(){
	showPage(1);
	
	$("[name=btnSearch]").click(function(){
		showPage(1);
		return false;
	})
	
	$('[name=Search]').autocomplete({
		source:'pages/sales/CSP/ajax/process.php',
			select: function( event, ui ) {
				// $( "#txtEentitlement"+counter+"").val( ui.item.label);
				// $( "#txtEEProdDesc"+counter+"").val( ui.item.ProductName);
				if(ui.item.Code == "No Result(s) Display"){
					$('[name=Search]').val("");
				}else{
					$('[name=Search]').val(ui.item.Code);
				}
				
			return false;
		}
		}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			return $( "<li style = 'list-style-type:circle;'></li>" )
				.data( "item.autocomplete", item )
				.append( "<a><strong>" + item.Code + "</strong> - " + item.Description + "</a>" )
				.appendTo( ul );
		};
});

function showPage(page){
    $('[name=page]').val(page);
    
    $.ajax({
        type        :   "post",
        url         :   "pages/sales/CSP/ajax/process.php",
		dataType	:	"json",
        data        :   $('[name=CSPForm]').serialize()+"&action=pagination",
        beforeSend  :   function(){
            //popinmessage("Loading... Please wait...");
        },
        success     :   function(data){
            //$('#dialog-message').dialog("close");			
            $('[name=ajaxProcess]').html(data['data_handler']);
			if(data['num'] > 1){
				$(".pagination").html(Pagination(page,data['RPP'],data['num']));
			}else{
				$(".pagination").html("");
			}
        }
        
    });
    return false;
}

function printnewpage(ID){
    
    objLink = "pages/sales/CSP/CSPview.php?PMG="+$("[name=PMGType]").find(":selected").val()+'&Search='+$("[name=Search]").val();
    openPopUp(objLink);
    return false;
    
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

function NewWindow(mypage, myname, w, h, scroll) 
{
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}
