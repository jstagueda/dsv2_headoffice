//modified by joebert italia


function showPage(str){
    $('[name=page]').val(str);
    if($('[name=cboSIBMR]').val() == ""){$('[name=cboSIBMRHidden]').val(0);}
    if($('[name=cboEIBMR]').val() == ""){$('[name=cboEIBMRHidden]').val(0);}
    
    ajaxfn();
}

function ajaxfn(){
    $.ajax({
        url         :   "includes/jxIBMCumPerReport.php",
        type        :   "post",
        data        :   $('[name=frmIBMCumPerReport]').serialize(),
        success     :   function(data){
            $('#pgContent').html(data).hide().fadeIn('slow');
            $('.loader').html('&nbsp;');
        },
        beforeSend  :   function(){
            $('.loader').html('Loading... Please wait...');
        }
    });
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
                window.location.href = "index.php?pageid=71";
            },
            "No"    :   function(){$(this).dialog("close");}
        }
    });
    $( "#dialog-message" ).dialog( "open" );
    
}

$(function(){
    
	$('[name=branchName]').autocomplete({
		source	:	function(request, response){
			$.ajax({
				type	:	"post",
				dataType:	"json",
				url		:	"pages/inventory/inventory_call_ajax/ajaxIBMCumPerReport.php",
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
	
    //autocompleter for IBM range from
    $('input[name=cboSIBMR]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type        :   "post",
                url         :   "pages/inventory/inventory_call_ajax/ajaxIBMCumPerReport.php",
                dataType    :   "json",
                data        :   {
                    ibmfromx:   "ibmfrom",
                    searched:   request.term,
					branch	:	$('[name=branch]').val()
                },
                success     :   function(data){
                    response( $.map( data, function( item ){
                        return {
                            label   :   item.Name,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('input[name=cboSIBMRHidden]').val(ui.item.ID);
        }
    });
    
    
    //autocomplete for IBM range to
    $('input[name=cboEIBMR]').autocomplete({
        source  :   function(request, response){
            $.ajax({
                type        :   "post",
                url         :   "pages/inventory/inventory_call_ajax/ajaxIBMCumPerReport.php",
                dataType    :   "json",
                data        :   {
                    ibmtox  :   "ibmto",
                    searched:   request.term,
                    ibmfrom :   $('input[name=cboSIBMRHidden]').val(),
					branch	:	$('[name=branch]').val()
                },
                success     :   function(data){
                    response( $.map( data, function( item ){
                        return {
                            label   :   item.Name,
                            ID      :   item.ID
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('input[name=cboEIBMRHidden]').val(ui.item.ID);
        }
    });
        
    $('[name=btnPrint]').click(function(){
        
        var objWin;
        var param = $('[name=frmIBMCumPerReport]').serialize();
        
        popuppage = "pages/sales/prIBMCumulativeReportPrint.php?" + param;
        if (!objWin){			
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
        }		
        return false; 
        
    });
});

function NewWindow(mypage, myname, w, h, scroll){
    var winl = (screen.width - w) / 2;
    var wint = (screen.height - h) / 2;
    winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
    win = window.open(mypage, myname, winprops)
    if (parseInt(navigator.appVersion) >= 4) {win.window.focus();}
}