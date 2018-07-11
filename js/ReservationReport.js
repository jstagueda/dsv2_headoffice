$ = jQuery.noConflict();

$(function(){
	
	$('[name=datefrom], [name=dateto]').datepicker({
		changeMonth : true,
		changeYear	: true
	});
	
	$('[name=btnSearch]').click(function(){
		showPage(1);
	});
	
	$('[name=btnPrint]').click(function(){
		
		var objWin = "";
		param = $('[name=ReservationReport]').serialize() + '&action=ReservationDetails';
        popuppage = "pages/inventory/ReservationReportPrint.php?" + param;
        if (!objWin){
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }
		
	});
	
	$('[name=branch]').autocomplete({
		source 	: 	function(request, response){
			
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	"pages/inventory/inventory_call_ajax/ReservationReport.php",
				data	:	{action : "GetBranch", Branch : request.term},
				success	:	function(data){
					response($.map(data, function(item){
						return{
							value	:	item.Value,
							label	:	item.Label,
							ID		:	item.ID
						}
					}));
				}
			});
			
		},
		select	:	function(event, ui){
			$('[name=branchID]').val(ui.item.ID);
		}
	});
	
});

function showPage(page){
	
	$('[name=page]').val(page);
	
	$.ajax({
		type	:	'post',
		url		:	'pages/inventory/inventory_call_ajax/ReservationReport.php',
		data	:	$('[name=ReservationReport]').serialize() + '&action=ReservationDetails',
		success	:	function(data){
			$('.loader').html("&nbsp;");
			$('.pageLoad').html(data).hide().fadeIn();
		},
		beforeSend:	function(){
			$('.loader').html("Loading... Please wait...");
		}
	});
	
}

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