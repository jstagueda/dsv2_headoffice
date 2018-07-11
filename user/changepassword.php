

<?php
include ("initialize.php"); //start of database connections
 global $database;


	$newuser = $database->execute("SELECT `changenextlogon` FROM `user` WHERE id=".$_SESSION['user_id']);
			if($newuser->num_rows){
				while($rnu = $newuser->fetch_object())
								{
								$force_pw=$rnu->changenextlogon; 
								}
			}
//echo $force_pw;

?>

<link rel="stylesheet" type="text/css" href="../css/ems.css">
<script type="text/javascript" src="js/popinbox.js"></script>
<html>
	<head>	

		
		<style>
			
			.tablelisttr{background: #FFDEF0;}
			.tablelisttr td{padding:5px; text-align:center; font-weight: bold; border-left:1px solid #FFA3E0;}
			.tablelisttable{width:100%;}
			.listtr td{border-top:1px solid #FFA3E0; border-left:1px solid #FFA3E0; padding:5px;}
			.ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
			.ui-widget-overlay{height:130%;}
			.formwrapper{border:1px solid #FF00A6; border-top:none; padding:10px; font-weight: bold;}
			.formwrapper2{border:None; border-top:none; padding:10px; font-weight: bold;}
		</style>
		
		<script>
		$ = jQuery.noConflict();

		$(function(){ 
		
			  var pw_new = <?php echo $force_pw ?>;
		
			  $('#oldpassword').bind("cut copy paste",function(e) {
				  e.preventDefault();
			  });	

			  $('#newpassword').bind("cut copy paste",function(e) {
				  e.preventDefault();
			  });

			  $('#newpasswordconfirm').bind("cut copy paste",function(e) {
				  e.preventDefault();
			  });	  
				
			   if (pw_new == 1)
			   {
					popinmessage('Your account is new or your password has been reset by the System Administrator. Changing your password is required or you won\'t be able to use the system.');	
					
			   }
			   
			  clearconsole();	
			  
			  
			  
			});			
			
	
		function clearconsole() { 
		  console.log(window.console);
		  if(window.console || window.console.firebug) {
		   console.clear();
		  }
		}
		</script>
		
	</head>
	
	<body>
		<div class="topnav">
			<table width="98%" cellspacing="1" cellpadding="0" border="0" align="center" style="padding:10px; padding-right:0;">
				<tr>
					<!--<td width="70%" align="right">
						<a class="txtblueboldlink" href="index.php?pageid=10">Access Rights</a>
					</td>
					-->
				</tr>
			</table>
		</div>
		
		<br />
		<br />
		
		<div class="mainwrapper">
		
			<div class="txtgreenbold13">
				&nbsp;&nbsp;&nbsp;Change Password
			</div>
			
			<div class="contentwrapper">
				
				<input type="hidden" value="1" name="page">
				<br />
			<div style="width:95%; margin:0 auto;">
                <div style="float:left; width:540px;">
				
                    <div class="tbl-head-content-left tbl-float-inherit"></div>
					
                    <div class="tbl-head-content-center tbl-float-inherit" style="width: 530px;">
                        <span>Action</span>
                    </div>
					
                    <div class="tbl-head-content-right tbl-float-inherit"></div>
                    <div class="tbl-clear"></div>
                    <div class="formwrapper">
						
                        <form method="post" name="formPrompt" action="user/includes/ajaxchangepassword.php" >
                        <table width="100%">
							<tr>
								<td class="bgF9F8F7" colspan="3">
								<?php 
								if (isset($_GET['msg']))
								{
									$message = strtolower($_GET['msg']);
									$success = strpos("$message","success"); 
									echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>".$_GET['msg']."</div>";
								} 
								else if(isset($_GET['errmsg']))
								{
									$errormessage = strtolower($_GET['errmsg']);
									$error = strpos("$errormessage","error"); 
										echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredsbold'>".$_GET['errmsg']."</div>";
								}
								?>
								</td>
							</tr>
							
						
							<tr>
								<td class="fieldlabel" > Old Password</td>
								<td class="separator">:</td>
								<td>
									<input class="txtfield" name="oldpassword" type="password" id="oldpassword" size="25" maxlength="25" value=""> 
									<input class="txtfield" name="oldpasswordx" type="hidden" value="0">
								</td>
							</tr>
							
							
							<tr>
								<td class="fieldlabel" > New Password</td>
								<td class="separator">:</td>
								<td>
									<input class="txtfield" name="newpassword" type="password" id="newpassword" size="25" maxlength="25" value=""> 
									<input class="txtfield" name="newpasswordx" type="hidden" value="0">
								</td>
							</tr>							
						
							<tr>
								<td class="fieldlabel" > Confirm New Password</td>
								<td class="separator">:</td>
								<td>
									<input class="txtfield" name="newpasswordconfirm" type="password" id="newpasswordconfirm" size="25" maxlength="25" value=""> 
									<input class="txtfield" name="newpasswordconfirmx" type="hidden" value="0">
								</td>
							</tr>
							
                            <tr>
                                <td colspan="3" align="center">
                                    <br />
                                    <input type="hidden" name="page" value="1">
                                    <input class="btn" type="submit" name="changepassword" value="Save">
                                </td>
                            </tr>
                        </table>
                        </form>
						
                    </div>
                   <div class="formwrapper2" style="height: 300px;">
						
                   </div>					
					
					
					
                </div>
                
                <div style="clear:both;">&nbsp;</div> 
				
            </div>
				
				
			</div>
		</div>
		
	</body>
</html>

<!-- <div id="dialog-message"></div> -->

<!--<div class="dialogmessage"></div> -->

<div id="dialog-message" style='display:none;'>
    <p></p>
</div>
