

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<?php

	require_once "initialize.php";
	
	if (isset($_GET['logout'])){
		
		if(isset($_SESSION['user_id'])){
			$database->execute("UPDATE userloginhistory SET LogoutTime = NOW() WHERE UserID = ".$session->session_get('user_id')." ORDER BY ID DESC LIMIT 1");
		}		
            User::unsetUserSession($session->session_get('user_id')); //Added by: jdymosco 2013-05-20
            $session->logout();
        }
	
	/*
		************************************
		** 	Author by: @Gino C. Leabres@****
		** 	12.17.2012					****
		** 	ginophp@yahoo.com           ****
		**	@version 1.0 December 17, 2012**	
		************************************
		Modified By: Benjie Timogan
		@Version 2.0 October 7, 2014
	*/
	 
	if (isset($_SESSION['user_id'])){
		redirect_to("index.php?pageid=0");
	}

	
?>
<!--META-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Tupperware Brands - Head Office</title>
<!--STYLESHEETS-->
<link href="css/style_login.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<!--SCRIPTS-->
<script language="javascript" type="text/javascript" src="js/jsUtils.js"></script>
<script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
<!--JQUERY SCRIPT-->
<script type="text/javascript" src = "js/g_login.js"></script>
<!--END JQUERY SCRIPT-->
</head>
<body>
<br />
<!--WRAPPER-->
<div id="wrapper">

	<!--SLIDE-IN ICONS-->
    <div class="user-icon"></div>
    <div class="pass-icon"></div>
    <!--END SLIDE-IN ICONS-->
<!--LOGIN FORM-->
<form name="login-form" class="login-form"  action="includes/pcLogin.php" method="post">
	<!--HEADER-->
    <div class="header">
			<!--LOGO-->
				<div class ="logo">
					<img src = "images/login_images/tupperwarebrands_logo.png">
				</div>
			<!--END LOGO-->
			
			<!--TITLE-->
				<h1>Direct Selling System 2.0</h1>
			<!--END TITLE-->
			
			<!--DESCRIPTION-->
				<?PHP if(isset($_GET["msg"])): ?> 
							<span> <?php echo $_GET["msg"]; ?> </span>
				<?PHP ELSE: ?>
							<span>&nbsp;</span>
				<?PHP endif; ?>
			<!--END DESCRIPTION-->	
	</div>
    <!--END HEADER-->
	
	<!--CONTENT-->
    <div class="content">
	<!-- USERNAME AND PASSWORD -->
		<?PHP IF(isset($_GET["msg"])): ?>
				<input name="txtUser" type="text" class="input username" id = "username" value="Username" onfocus="if($('#username').val() == 'Username'){this.value=''}" style = "border-color: red;"/>
				<!--<font size = "1" color = "Gray">Format Initial First, Middle and Full Surname</font><br />-->
				<!--<font size = "1" color = "red">Sample: JBLASPINAS</font>-->
				<input name="txtPass" type="password" class="input password" value="Password" id = "password" onfocus="if($('#password').val() == 'Password'){this.value=''}" style = "border-color: red;" />
				<a href = "javascript:void(0);" id = "change_password"><font size = "2" color = "blue">Change Password</font></a>
		<?PHP ELSE: ?>
				<input name="txtUser" type="text" class="input username" id = "username" value="Username" onfocus="if($('#username').val() == 'Username'){this.value=''}" />
				<!--<font size = "1" color = "Gray">Format Initial First, Middle and Full Surname</font><br />-->
				<!-- <font size = "1" color = "red">Sample: JBLASPINAS</font> -->
				<input name="txtPass" type="password" class="input password" value="Password" id = "password" onfocus="if($('#password').val() == 'Password'){this.value=''}"  />
				<a href = "javascript:void(0);" id = "change_password"><font size = "2" color = "blue">Change Password</font></a>
	     <?php ENDIF; ?>
    <!--END USERNAME AND PASSWORD -->
	</div>
	<!--END CONTENT-->
	
    <!--FOOTER-->
    <div class="footer">
    <!--LOGIN BUTTON-->
		<input type = "button" name = "btnLogin" value="Login" class="button" onclick = "return CheckSubmit();" />
	<!--END LOGIN BUTTON-->
    </div>
    <!--END FOOTER-->
</form>
<!--END LOGIN FORM-->
</div>
<!--END WRAPPER-->

<!--GRADIENT-->
	<div class=""></div>
<!--END GRADIENT-->
<!--DIALOG BOX-->
<div id="test_overlay" style="left: 0pt; top: 0pt; width: 100%; height: 100%; position: fixed; background: none repeat scroll 0% 0% rgb(0, 0, 0); opacity: 0.96; display:none;">
<div id="TBcontent" style="width: 642px; height: 400px; background: none repeat scroll 0% 0% #F3F3F3; margin: 5% auto auto;">
	<br />
	<div class="logo">
			<img src="images/login_images/tupperwarebrands_logo.png">
	</div>
	<div align = "center"><h3>Change Password</h3><br />
	<font size = '2' color= "black">Username: &nbsp;&nbsp; &nbsp; 
											  &nbsp; &nbsp;&nbsp; &nbsp;
									<input type = "text" id = "user_name" style = "border: 1px solid gray;height:15px;">
	</font>
	<br /><br />
	<font size = '2' color= "black">Password: &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; 
											  &nbsp; &nbsp;
									<input type = "password" id = "pass_word" style = "border: 1px solid gray;height:15px;">
	</font>
	<br /><br />
	<font size = '2' color= "black">New Password: &nbsp; &nbsp;&nbsp; 
									<input type = "password" id = "new_password" style = "border: 1px solid gray;height:15px;">
	</font>
	<br /><br />
	<font size = '2' color= "black">Confirm Passowrd: 
									<input type = "password" id = "confirm_password" style = "border: 1px solid gray;height:15px;">
	</font>
	<br /><br />
	<input type = "submit" style = "color: #FFF; background-color: #56C2E1;font-weight: bold;border: 1px solid #46B3D3;" value = "Submit" id = "change_password_submit" /> &nbsp;&nbsp;&nbsp;
	<input type = "submit" style = "color: #FFF; background-color: #56C2E1;font-weight: bold;border: 1px solid #46B3D3;" value = "Cancel" id = "change_password_cancel"/>
	</div>
</div>
</div>
<!--END DIALOG BOX-->


</body>
</html>