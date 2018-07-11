<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 07, 2013
 * @explanation: Setup main page for setup options of DSS system, such as dealer, product, inventory and sales invoice.
 */

    include('config.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <title>DSS System Setup and Settings</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link type="image/x-icon" href="../images/favicon.ico" rel="shortcut icon" />
        <link type="text/css" href="setup.css" rel="stylesheet"/>
        <link type="text/css" href="css/jquery-ui-1.8.5.custom.css" rel="stylesheet"/>
        <script type="text/javascript" src="js/jquery-1.8.3.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
		
        <script type="text/javascript" src="js/systemFileSetup.js"></script>
        <!--script type="text/javascript" src="js/jquery.Setup.js"></script-->
		<script>
		$(document).ready(function(){
				$('#start-setup-config').click(function(){
					
					var Server 	 = $("#Database-Server");
					var DBName 	 = $("#Database-Name");
					var UserName = $("#Username");
					var Password = $("#Password");
					var FilePath = $("#FilePath");
					var HOSync	 = $("#HOSync");
					var error_msg = "", error_cnt = 0;
					
					/*
					*ex localhost/sample/dbsync.php
					*ex: dss_folder
					Password
					*ex: username
					*ex: ems_tpi_test_branch
					*ex: localhost
										
					*/
					if(Server.val() == "" || Server.val() == "*ex: localhost"){
						error_msg += "Database Server Required.\n";
						error_cnt++;
					}
					if(DBName.val() == "" || DBName.val() == "*ex: ems_tpi_test_branch"){
						error_msg += "Database Name Required.\n";
						error_cnt++;
					}
					if(UserName.val() == "" || UserName.val() == "*ex: username"){
						error_msg += "User Name Required.\n";
						error_cnt++;
					}
					if(FilePath.val() == "" || FilePath.val() == "*ex: dss_folder"){
						error_msg += "File Path Required.\n";
						error_cnt++;
					}
					if(HOSync.val() == "" || HOSync.val() == "*ex localhost/sample/dbsync.php"){
						error_msg += "HO Sync Required.\n";
						error_cnt++;
					}
					
				
					
					if(error_cnt > 0 ){
						alert(error_msg);
						return false;
					}else{
						
						if(Password.val() == "Password"){
							Password.val("");
						}
						$.ajax({
							type: 'post',
							dataType: 'json',
							url: 'requests/StartSetupConfig.php',
							data: {'request':'start_setup','xServer': Server.val(), 'xDBName' : DBName.val(), 'xUserName': UserName.val(), 'xPassword': Password.val(), 'xFilePath': FilePath.val(), 'xHOSync': HOSync.val()},
							success: function( response ){
									
									if(response.request == 'successful'){
										alert('Setup Config Successful');
										window.location.assign("index.php");
										return false;
									}else{
										alert(response.request);
										return false;
									}
							}
							
						});
					}
			
				});
		});
		</script>
    </head>
    <body>
        <div class="main">
            <div class="container">
                <div class="content">
                    <div id="header">
                        <h2>DSS System Setup</h2>
                    </div>
                    <div id="main-content">
						<div id="setup-status-parameter" class = "setup_branch_parameter">
                            <div class="status-content-parameter">
								<p>Database Server: <input type = "text" value = "*ex: localhost" 
													  onfocus="if($('#Database-Server').val() == '*ex: localhost'){this.value=''}" 
													  onblur = "if($('#Database-Server').val() == ''){this.value='*ex: localhost'}" 
													  id = "Database-Server" style = "width:30%;" /><br /></p>
								<p>Database Name : &nbsp;<input type = "text" value = "*ex: ems_tpi_test_branch" id = "Database-Name" 
													  onfocus="if($('#Database-Name').val() == '*ex: ems_tpi_test_branch'){this.value=''}" 
													  onblur ="if($('#Database-Name').val() == ''){this.value='*ex: ems_tpi_test_branch'}" style = "width:30%;" /><br /></p>
								<p>User Name&nbsp;&nbsp;&nbsp;&nbsp; :&nbsp;&nbsp;<input type = "text" value = "*ex: username" id = "Username" style = "width:30%;" 
													  onfocus="if($('#Username').val() == '*ex: username'){this.value=''}" 
													  onblur ="if($('#Username').val() == ''){this.value='*ex: username'}" style = "width:30%;" 
														/><br /></p>
								<p>Password &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<input type = "password" value = "Password" 
														id = "Password" style = "width:30%;" 
													    onfocus="if($('#Password').val() == 'Password'){this.value=''}" 
													    onblur ="if($('#Password').val() == ''){this.value='Password'}" style = "width:30%;" /><br /></p>
								<p>Data Migration File Path &nbsp;&nbsp;:&nbsp;&nbsp;<input type = "text" value = "*ex: dss_folder" id = "FilePath" style = "width:23%;" 
														onfocus="if($('#FilePath').val() == '*ex: dss_folder'){this.value=''}" 
													    onblur ="if($('#FilePath').val() == ''){this.value='*ex: dss_folder'}" style = "width:30%;" /><br /></p>
								<p>HO SYNC URL&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp;<input type = "text" value = "*ex localhost/sample/dbsync.php" 
														 onfocus="if($('#HOSync').val() == '*ex localhost/sample/dbsync.php'){this.value=''}" 
													     onblur ="if($('#HOSync').val() == ''){this.value='*ex localhost/sample/dbsync.php'}" style = "width:30%;" 
														 id = "HOSync" style = "width:30%;"/><br /></p>
								<p><input type="submit" id = "start-setup-config" class="button" value="START SETUP" /></p>
							</div>
                        </div>
						
                    </div>
                    <div id="spacer">&nbsp;</div>
                </div>
            </div>
        </div>
    </body>
</html>
