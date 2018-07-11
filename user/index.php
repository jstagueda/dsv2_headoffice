<?php
	include "user/includes/users.php";
	include "user/includes/pagination.php";
?>

<html>
	<head>		
		<style>
			
			.mainwrapper{
				margin : auto;
				width : 960px;
			}
			
			.contentwrapper{
				min-height : 545px;
			}
			
			.headerwrapper{
				border-bottom : 1px solid #FF00A6;
			}
			
			.footerwrapper{
				border-top : 1px solid #FF00A6;
				padding-top : 5px;
				color : #FF00A6;
			}
			
			.trheader td{
				padding : 5px;
				background : #FFDEF0;
				border-right : 1px solid #FFA3E0;
				text-align : center;
				font-weight : bold;
			}
			
			.trlist td{
				padding : 5px;
				border-right : 1px solid #FFA3E0;
				border-top : 1px solid #FFA3E0;
			}
			
			.searchbar{
				text-align : right;
			}
			
			.fieldlabel{
				text-align : right;
				width : 35%;
				font-weight : bold;
			}
			
			.separator{
				width : 10%;
				font-weight : bold;
				text-align : center;
			}
			
		</style>
		
		<script>
			$ = jQuery.noConflict();
			function showPage(page){
				$('[name=page]').val(page);
				
				$.ajax({
					type	:	"post",
					url		:	"user/includes/ajaxUser.php",
					data	:	{action : "UserListPagination", page : page},
					success	:	function(data){
						$('.pageload').html(data);
					}
				});
				
			}
			
			function DisabledUser(UserID){
				var btnfunction = {};
				btnfunction['Ok'] = function(){
					$('#dialog-message').dialog("close");
				}
				
				$.ajax({
					type	:	"post",
					dataType:	"json",
					url		:	"user/includes/ajaxUser.php",					
					data	:	{action : "DisabledUser", UserID : UserID},
					success	:	function(data){
						if(data.Success == 1){
							var message = '<p>Successfully disabled user account.</p>';	
							showPage($('[name=page]').val());
							popupHTML(message, btnfunction);
						}else{
							popupHTML(data.ErrorMessage, btnfunction);
						}
					}
				});
				
			}
			
			function EnabledUser(UserID){
				var btnfunction = {};
				btnfunction['Ok'] = function(){
					$('#dialog-message').dialog("close");
				}
				
				$.ajax({
					type	:	"post",
					dataType:	"json",
					url		:	"user/includes/ajaxUser.php",					
					data	:	{action : "EnabledUser", UserID : UserID},
					success	:	function(data){
						if(data.Success == 1){
							var message = '<p>Successfully enabled user account.</p>';	
							showPage($('[name=page]').val());
							popupHTML(message, btnfunction);
						}else{
							popupHTML(data.ErrorMessage, btnfunction);
						}
					}
				});
				
			}
			
			function popupHTML(message, btnfunction){
			
				$('#dialog-message').html(message);
				$('#dialog-message').dialog({
					autoOpen: false,
					modal: true,
					position: 'center',
					height: 'auto',
					width: 'auto',
					resizable: false,
					draggable: false,
					title: "DSS Message",
					buttons : btnfunction
				});
				$('#dialog-message').dialog("open");
				
			}
			
			function getLogs(UserID){
				
				$.ajax({
					type	:	"post",
					url		:	"user/includes/ajaxUser.php",
					data	:	{action : "getUserLogs", UserID : UserID},
					success	:	function(data){
						var message = data;
						var btnfunction = {};
						
						btnfunction['Close'] = function(){
							$('#dialog-message').dialog("close");
						}
						
						popupHTML(message, btnfunction);
					}
				});
				
			}
			
			function ChangePasword(UserID){
				
				$.ajax({
					type	:	"post",
					url		:	"user/includes/ajaxUser.php",
					data	:	{action : "ChangePasswordForm", UserID : UserID},
					success	:	function(data){
						
						var btnfunction = {}
						
						btnfunction['Save'] = function(){
							SaveChangePassword();
						}
						
						btnfunction['Close'] = function(){
							$('#dialog-message').dialog("close");
						}
						
						popupHTML(data, btnfunction);
					}
				});
				
			}
			
			function SaveChangePassword(){
				
				$.ajax({
					type	:	"post",
					dataType:	"json",
					url		:	"user/includes/ajaxUser.php",
					data	:	$('[name=changepasswordform]').serialize() + "&action=SaveChangePassword",
					success	:	function(data){
						
						if(data.Success == 1){
							var btnfunction = {};
							btnfunction['Ok'] = function(){
								$('#dialog-message').dialog("close");
								showPage($('[name=page]').val());
							};
						}else{
							var btnfunction = {};
							btnfunction['Ok'] = function(){
								ChangePasword(data.UserID);
							}							
						}
						
						popupHTML(data.ErrorMessage, btnfunction);
						
					}
				});
				return false;
			}
			
			function ViewDetails(UserID){
				
				$.ajax({
					type	:	"post",
					url		:	"user/includes/ajaxUser.php",
					data	:	{action : "ViewUserDetails", UserID : UserID},
					success	:	function(data){
						var btnfunction = {};
						btnfunction['Close'] = function(){
							$('#dialog-message').dialog("close");
						}
						popupHTML(data, btnfunction);
					}
				});
				
			}
		</script>
		
	</head>
	
	<body>
		<div class="topnav">
			<table width="98%" cellspacing="1" cellpadding="0" border="0" align="center" style="padding:10px; padding-right:0;">
				<tr>
					<td width="70%" align="right">
						<a class="txtblueboldlink" href="index.php?pageid=10">Access Rights</a>
					</td>
				</tr>
			</table>
		</div>
		
		<br />
		<br />
		
		<div class="mainwrapper">
		
			<div class="txtgreenbold13">
				User Login Management
			</div>
			
			<div class="contentwrapper">
				
				<input type="hidden" value="1" name="page">
				
				<!--<div class="searchbar">
					<input type="text" name="SearchUser" placeholder="Search User" class="txtfield">
					<input type="button" name="btnSearch" class="btn" value="Search">
				</div>-->
				
				<br />
				
				<div class="pageload">
					<table width="100%" cellspacing="0" cellpadding="0" class="bordersolo">
					
						<tr class="trheader">
							<td>User Name</td>
							<td>Login Name</td>
							<td>User Type</td>
							<td>Date Enrolled</td>
							<td>Status</td>
							<td>Last Login</td>
							<td>Action</td>
						</tr>
						
						<?php 
							$user = userlist(1, 10, false);
							$usertotal = userlist(1, 10, true);
							
							if($usertotal->num_rows){
								while($res = $usertotal->fetch_object()){
									if($res->Status == "Active"){
										$isenabled = '<input type="button" class="btn" value="Disable" name="btnDisabled" onclick="return DisabledUser('.$res->UserID.');">';
										$style = "background:lightgreen; color:red;";																
									}else{
										if($res->Status == "Expired"){
							
											$isenabled = '<input type="button" class="btn" value="Change Password" name="btnChangePassword" onclick="return ChangePasword('.$res->UserID.');">';
											$style = "background:#FF6666; color:white;";
											
										}else{
											$isenabled = '<input type="button" class="btn" value="Enable" name="btnEnabled" onclick="return EnabledUser('.$res->UserID.');">';
											$style = "background:#FF6666; color:white;";
										}
									}
						?>
							
						<tr class="trlist">
							<td><a style="color:blue;" href="javascript:void(0);" onclick="return ViewDetails(<?=$res->UserID?>);"><?=$res->UserName?></a></td>
							<td><?=$res->LoginName?></td>
							<td><?=$res->UserType?></td>
							<td align="center"><?=$res->DateRegistered?></td>
							<td align="center" style="<?=$style?>"><?=$res->Status?></td>
							<td align="center"><?=$res->LastLoginTime?></td>
							<td align="center">
								<?=$isenabled?>
								<input type="button" class="btn" value="View Status Logs" name="btnStatusLogs" onclick="return getLogs(<?=$res->UserID;?>);">
							</td>
						</tr>
						
						<?php }}else{?>
						
						<tr class="trlist">
							<td align="center" colspan="7">No result found.</td>
						</tr>
						
						<?php }?>
						
					</table>
					
					<?php if($usertotal->num_rows){?>
					<div style="margin-top:10px;">
						
						<?php echo AddPagination(10, $user->num_rows, 1);?>
						
					</div>
					<?php }?>
				</div>
				
			</div>
		</div>
		
	</body>
</html>

<div id="dialog-message"></div>