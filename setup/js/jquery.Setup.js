/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 11, 2013
 */
var interval = 100;
var t;
var path = dir_path;
var scroll;

$(function($){

	$('select[name="for-setup"]').change(function(){
		if($('select[name="for-setup"] option:selected').val() == "branchparameter.php"){
			$('.setup_branch_parameter').show();
			$('.process_logs').hide();
			$('#start_setup').hide();
		}else{
			$('.setup_branch_parameter').hide();
			$('.process_logs').show();
			$('#start_setup').show();
		}
	});

    $('#DSS-Setup-Form').on('submit',function(){
		
        var self = $(this);
        var forSetupFile = self.find('select[name="for-setup"]').val();
        
        if(forSetupFile != ''){
            $('.setup-progress').removeClass('hide');
            _doSetup( forSetupFile );
        }else{
            alert('Please select process file first before stating setup.');
        }
        
        return false;
    });
    
    //event handler for view error logs link
    $('#view-setup-errors').on('click',function(){
        var fileLog = $('select[name="for-setup"] option:selected').data('errorlogs');
        
        $.ajax({
            cache: false,
            type:'GET',
            url: path + '/logs/' + fileLog,
            success: function(response){
                $('.status-content p').html(response);
            }
        });
		
		
    });
    
    //function that will process upload data...
    function _doSetup(file){
        startShowingProcessLogs();
        _autoScrollLogDisplay();
        $("input[type=submit]").attr("disabled", "disabled");
        $('#view-setup-errors').addClass('hide');
        
        $.ajax({
            cache: false,
            type:'POST',
			dataType: 'json',
            url: 'requests/start_setup.php',
            data: {'file' : file},
            success: function(response){
                //var res = $.parseJSON(response);
                
                if(response.status == 'success'){
                    $('.setup-progress').addClass('hide');
                    $('.setup-done-lists p').append(response.setup_file + '<br />');
                    $("input[type=submit]").removeAttr("disabled");
                    if($('select[name="for-setup"] option:selected').val() == "DataMigration.php"){
						$('#view-setup-migration-logs').removeClass('hide');
						$('#view-setup-errors').addClass('hide');
					}else{
						$('#view-setup-errors').removeClass('hide');
						$('#view-setup-migration-logs').addClass('hide');
                    }
					clearTimeout(t);
                    clearInterval(scroll);
                }
                
            }
        });
        
    }
	
	//Branch AutoCompleter
	$( "#BranchName" ).autocomplete({
	 source:'requests/Search_Branch.php',
		select: function( event, ui ) {
			$( "#BranchName" ).val( ui.item.label );
			$( "#BranchID" ).val( ui.item.BranchID == ""?"N/A":ui.item.BranchID);
			$( "#TIN" ).val( ui.item.TIN==""?"N/A":ui.item.TIN);
			$( "#PermitNo" ).val( ui.item.PermitNo==""?"N/A":ui.item.PermitNo);
			$( "#ServerSN" ).val( ui.item.ServerSN != ""?ui.item.ServerSN:"");
			return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
		return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.label + "</strong></a>" )
			.appendTo( ul );
	};
	
	//Process BranchParameter
	
	$("#start_setup_branchparameter_submit").click(function(){
		$.ajax({
			url: "requests/BranchParameter.php",
			type: "post",
			dataType: 'json',
			data: {'BranchID': $('#BranchID').val(), 'TIN': $('#TIN').val(), 'PermitNo': $("#PermitNo").val(), 'ServerSN': $("#ServerSN").val()},
			success: function( resp ){

				if(resp.result == 'success'){
					alert('Setup Branch Parameter Successful');
				}else{
					alert('Branch Parameter Already Setup')
				}
			}
		});
		return false;
	})
});



//functions for real time update of logs...
function startShowingProcessLogs(){
    _getSetupLogs();
}

function _getSetupLogs(){
    $.ajax({
        cache: false,
        type:'GET',
        url: path + '/logs/process.log',
        success: function(response){
            $('.status-content p').html(response);
        }
    });
    
    t = setTimeout('_getSetupLogs()', interval);
}

function _autoScrollLogDisplay(){
    scroll = setInterval(function(){
        var pos = $('.status-content').scrollTop();
        $('.status-content').scrollTop(pos + 50);
    }, 200);
}


function print_report() 
{

	var objWin;
	
	popuppage = "requests/DataMigrationLogs.php";
	
	if (!objWin)
	{			
		objWin = NewWindow(popuppage,'printps','800','500','yes');
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
