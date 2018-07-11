
jQuery(document).ready(function() {
//SLIDER-IN ICONS
	$(".username").focus(function() {
		$(".user-icon").css("left","-48px");
	});
	$(".username").blur(function() {
		$(".user-icon").css("left","0px");
	});
	
	$(".password").focus(function() {
		$(".pass-icon").css("left","-48px");
	});
	$(".password").blur(function() {
		$(".pass-icon").css("left","0px");
	});
	
	$("#change_password").click(function(){
		var username = $("#username").val();
		if(username == "" || username == "Username"){
			alert("Username Required.");
		}else{
		
		dialog_open(username);
		}
	});
	
	$("#change_password_cancel").click(function(){
			dialog_close();
	});
	
	$("#change_password_submit").click(function(){
	
		if($("#user_name").val() == ""){
			alert("Username required.");
			$("#user_name").focus();
			return false;
		}
	
		if($("#pass_word").val() == ""){
			alert("password required.");
			$("#pass_word").focus();
			return false;
		}
		
		if($("#new_password").val() == ""){
			alert("New Password required.");
			$("#new_password").focus();
			return false;
		}
		
		if($("#confirm_password").val() == ""){
			alert("Confirm Password required.");
			$("#confirm_password").focus();
			return false;
		}
		if($("#new_password").val() == $("#confirm_password").val()){
				$.ajax({
					url: "includes/change_password.php",
					type: "post",
					dataType: "json",
					data: {'xusername': $("#user_name").val(), 'xpassword': $("#pass_word").val(), 'new_password': $("#new_password").val()},
					success: function(response){
						if(response.gino == "success"){
							alert(response.message);
							dialog_close();
						}else{
							alert(response.message_error);
						}
					}
				})

		}else{
			alert("New Password and Confirm Password Not Match");
		}
	});
	
});
//END SLIDER-IN ICONS

//JAVASCRIPT VALIDATION
function CheckSubmit() 
{
/* 	alert '1234';
	return false; */
	var username = $('#username').val();
	var password = $('#password').val();
	var _error		   = 0;
	var error_username = "";
	var error_password = "";
	
	if(username == null || username == "" || username == 'Username'){
		$("#username").css('border-color', 'red');
		$('#username').val("Username");
		error_username = "*Username";
		_error++;
	}else{
		$("#username").css('border-color', '#9d9e9e');
	}
	
	if(password == null || password == "" || password == "Password"){
		$("#password").css('border-color', 'red');
		$('#password').val("Password");
		error_password = "*Password";
		_error++;
	}else{
			$("#password").css('border-color', '#9d9e9e');
	}
	
	if(_error > 0){
		alert("Please input "+error_username+" "+error_password);
		return false;
	}else{
		
			$.ajax({
				type	:	"post",
				url		:	"includes/ajaxLogin.php",
				dataType:	"json",
				data	:	{action : "LoginUser", username : username, password : password},
				success	:	function(data){
					if(data.Success == 0){
						
						alert(data.ErrorMessage);
						return false;
						
					}else{
						
						$(".login-form").submit();
						
					}
				}
				
			});
			
			/* $(".login-form").submit();	 */
		
	}
	
}
function dialog_close(){
var clr = "";
		$("#user_name").val(clr);
		$("#pass_word").val(clr);
		$("#new_password").val(clr);
		$("#confirm_password").val(clr);
		
							$("#test_overlay").hide();
							//loginform
							$(".login-form").fadeIn();
							$(".user-icon").fadeIn();
							$(".pass-icon").fadeIn();
}

function dialog_open(username){

		$("#user_name").val(username);		
		$("#test_overlay").fadeIn();
		$(".login-form").hide();
		$(".user-icon").hide();
		$(".pass-icon").hide();
		
}
//END JAVASCRIPT VALIDATION