<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 07, 2013
 * @explanation: Setup main page for setup options of DSS system, such as dealer, product, inventory and sales invoice.
 */

	header("Cache-Control: no-cache, must-revalidate");
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	
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
        <script type="text/javascript" src="js/jquery.Setup.js"></script>
		
    </head>
    <body>
        <div class="main">
            <div class="container">
                <div class="content">
                    <div id="header">
                        <h2>DSS System Setup</h2>
                    </div>
                    <div id="main-content">
                        <div class="setup-form">
                            <form method="POST" id="DSS-Setup-Form">
                                <select name="for-setup" class="select-setup">
                                    <option value="">Select process file....</option>
                                    <?php
                                        foreach($setup_config['select'] as $sc):
                                    ?>
                                        <option value="<?php echo $sc['file_process']; ?>" data-errorlogs="<?php echo $sc['file_error_log']; ?>"><?php echo $sc['label']; ?></option>
                                    <?php
                                        endforeach;
                                    ?>
                                <input type="submit" id = "start_setup" class="button" value="START SETUP" />
                                </select>
                            </form>
                            <div class="setup-progress hide">Processing setup, please wait...</div>
                            <div class="setup-done-lists">
                                <br />
                                Done for setup:<br />
                                <p></p>
                            </div>
                        </div>
                        <div id="setup-status" class = "process_logs">
                            <div class="status-content">
                               <p>
                                Process logs here...<br />
                               </p>
                            </div>
                            <a class="hide" href="javascript:void(0);" id="view-setup-errors">View Setup Error Logs</a>
                            <a class="hide"href="javascript:void(0);" onclick = 'return print_report();' id="view-setup-migration-logs">View Logs</a>
                        </div> 
                        
						<div id="setup-status-parameter" class = "setup_branch_parameter" style = "display:none">
                            <div class="status-content-parameter">
								<p>Branch Name:&nbsp;&nbsp;&nbsp;&nbsp;<input type = "text" value = "" id = "BranchName" keypress = "gino();"/><br /></p>
								<input type = "hidden" value = "" id = "BranchID" />
								<p>TIN :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "text" value = "" id = "TIN" /><br /></p>
								<p>PermitNo :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "text" value = "" id = "PermitNo" /><br /></p>
								<p>ServerSN :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = "text" value = "" id = "ServerSN" /><br /></p>
								<p> <input type="submit" id = "start_setup_branchparameter_submit" class="button" value="START SETUP" />
							</div>
                        </div>
						
                    </div>
                    <div id="spacer">&nbsp;</div>
                </div>
            </div>
        </div>
    </body>
</html>
