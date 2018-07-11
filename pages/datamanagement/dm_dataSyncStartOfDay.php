<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: May 28, 2013
 */
 
 $logPath = 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']);
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/datamgt/jquery.Sync.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("nav.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management - Start Of Day (Data Sync)</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="DM-syncForm" method="POST" action="">
                                                                        
                                    <div class="tbl-lbl tbl-float-inherit" style="width: 250px;">Method Options :<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <input type="radio" name="sync_opt" value="IC" checked="checked" />Internet Connection
                                        <input type="radio" name="sync_opt" value="DFU" /> Data File Upload
                                    </div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <input type="hidden" name="action" id="action" value="doSODAndHOSync" />
                                    <input type="submit" value="Process SOD & HO Sync to Branch" id="btnSync" class="btn" name="btnSync">
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 500px;"><span>Process Logs</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 486px;">
                                <img class="tbl-float-inherit hide sync-loader" src="images/sync_loader.gif" style="padding-right: 10px;"/>
                                <img class="tbl-float-inherit hide check-success" src="images/check-success.png" style="padding-right: 10px;" />
                                <img class="tbl-float-inherit hide sync-error" src="images/sync-error.png" style="padding-right: 10px;" />
                                
                                <iframe class="tbl-mid-logs tbl-float-inherit hide" id="syncframe-log-view" src="<?php echo $logPath; ?>/logs/syncSOD_process.log" frameborder="0"></iframe>
                            </div>
                        </div>
                        <div class="tbl-clear clear-small"></div>
                    </td>
                </tr>
            </table>              
        </td>
    </tr>
</table>