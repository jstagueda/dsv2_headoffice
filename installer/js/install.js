/*
	@author: Gino Leabres
	@date 9/12/2013
*/
var interval = 3000;
var t;
var scroll = "";
var error_msg = ""; 
var error_cnt = 0;
var xbutton = "";
$(document).ready(function(){
	
	//autocompleter..
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
	
})
function getlog()
{
	$.ajax({
        cache: false,
        type:'GET',
        url: 'logs.txt',
        success: function(response){
            $('#logs').html(response);
        }
    });
	t = setTimeout('getlog()', interval);
}

function _autoScrollLogDisplay(){
    scroll = setInterval(function(){
        var pos = $('.status-content-parameter').scrollTop();
        $('.status-content-parameter').scrollTop(pos + 50);
    }, 90);
}

function submit_setup(xstep)
{
	if(xstep==1){ //config setup here...
					var Server 	 = $("#Database-Server");
					var DBName 	 = $("#Database-Name");
					var UserName = $("#Username");
					var Password = $("#Password");
					error_msg = "";
					error_cnt = 0;

					if(Server.val() == "" || Server.val() == "* e.g ems_tpi_test_branch"){
						error_msg += "Database Server Required.<br />";
						error_cnt++;
					}
					if(DBName.val() == "" || DBName.val() == "*e.g localhost"){
						error_msg += "Database Name Required.<br />";
						error_cnt++;
					}
					if(UserName.val() == "" || UserName.val() == "* e.g root"){
						error_msg += "User Name Required.<br />";
						error_cnt++;
					}
						
					if(error_cnt > 0 ){
						popinmessage(error_msg);
						return false;
					}else{
						$("#config_setup").fadeOut();
						$("#indicator").fadeIn();
						getlog();
						_autoScrollLogDisplay();
						$.ajax({
								type: 'post',
								//dataType: 'json',
								url: 'requests/StartSetupConfig.php',
								data: {'request':'start_setup','xServer': Server.val(), 'xDBName' : DBName.val(), 'xUserName': UserName.val(), 'xPassword': Password.val()},
								success: _doAjaxResponse
						});
					}
		return false;
	}else{
		
		error_msg = "";
		error_cnt = 0;
		
		if($('#BranchID').val()==""){
			error_msg+='Branch Required. <br />';
			error_cnt++;
		}
		if($('#TIN').val()==""){
			error_msg+='TIN Required. <br />';
			error_cnt++;
		}
		if($("#PermitNo").val()==""){
			error_msg+='Permit No Required <br />';
			error_cnt++;
		}
		if($("#ServerSN").val()==""){
			error_msg+='ServerSN Required. <br />';
			error_cnt++;
		}
		
		if(error_cnt==0){
			xbutton = "";
			$("#config_setup").fadeOut();
			$("#setup_branch_parameter").hide();
			$("#indicator").fadeIn();
			getlog();
			_autoScrollLogDisplay();
			$.ajax({
				url: "requests/BranchParameter.php",
				type: "post",
				//dataType: 'json',
				data: {'BranchID': $('#BranchID').val(), 'TIN': $('#TIN').val(), 'PermitNo': $("#PermitNo").val(), 'ServerSN': $("#ServerSN").val(),
						'WelcomeNoteLine1': $("#WelcomeNoteLine1").val(),'WelcomeNoteLine2':$("#WelcomeNoteLine2").val(),'WelcomeNoteLine3':$("#WelcomeNoteLine3").val()
					},
				success: _doAjaxResponseFinalProcess
			});
			return false;
		}else{
			popinmessage(error_msg);
			return false;
		}
	}
}

function _doAjaxResponse(response)
{
	
	var res = $.parseJSON(response);									

		//stop writing to logs..;
		setTimeout(function(){
			if(res.request != 'successful..'){
				//alert('Setup Config Successful');
				$("#first_setup").attr("disabled","disabled");
				$("#config_setup").fadeOut();
				$("#indicator").hide();
			}else{
				$("#indicator").hide();
				$("#setup_branch_parameter").fadeIn();
				//popinmessage(res.request);
			}
			clearTimeout(t);
			clearInterval(scroll);
		},5000);
		//stop drop down logs..
	return false;
}

function _doAjaxResponseFinalProcess(response)
{
	var res = $.parseJSON(response);			
		setTimeout(function(){
			if(res.result == 'success'){
				//alert('Setup Branch Parameter Successful');
					popinmessage('Setup successful');
					//stop writing to logs..
					clearTimeout(t);
					//stop drop down logs..
					clearInterval(scroll);
					$("#second_setup").attr("disabled","disabled");
			}else{
				popinmessage('Branch Parameter and Data Migration Already Setup');
			}
			xbutton += '<div style="text-align:center; margin-top:20px;">';
			xbutton += '<input class = "btn" type ="submit" onclick="return proceed_to_main_page();" value ="Proceed to DSv2 Login">';
			xbutton += '</div>';
			
			$(".for-setup").html(xbutton);
			$("#indicator").fadeOut();
		},5000);			

}
function proceed_to_main_page()
{
	confirmationpopinwithredirection("Are you sure want to procced to DSv2 Login?",'../login.php');
	return false;
}

//message popin
function popinmessage(message){
    $( "#dialog-message p" ).html(message);
    $( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: '300px',
        resizable: false,
        title: 'DSv2 System Setup Message',
        buttons:{
            "Ok" : function(){$(this).dialog("close");}
        }
    });
    $( "#dialog-message" ).dialog( "open" );
}

function confirmationpopinwithredirection(message, link){
    
    $( "#dialog-message p" ).html(message);
    $( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
        title: 'DSv2 System Setup Message',
        buttons:{
            "Yes"   : function(){
                window.location.href = link;
            },
            "No"    : function(){
                $(this).dialog("close");
            }
        }
    });
    $( "#dialog-message" ).dialog( "open" );
    
}
