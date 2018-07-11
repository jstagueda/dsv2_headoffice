<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 02, 2013
 */ 

    $date = '2011-12-12';
    $end_date = '2013-12-12';
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmMovementFunctions.js"></script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmMovementTermination.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("nav_dealer.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Sales Force Movement Termination</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Termination Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-terminationForm" method="POST" action="">
                                    
                                    <div class="tbl-lbl tbl-float-inherit">SF Level:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="terminationSFL" name="terminationSFL">
                                            <option value="">Select sales force level here...</option>
                                            <option class="termination-SFL hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Campaign Month:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 252px;" id="terminationCM" name="terminationCM">
                                                <option value="">Select campaign month here...</option>
<!--                                                <option class="CB-CM hide"></option>-->
                                                <?php
                                                    while(strtotime($date) < strtotime($end_date)) {
                                                       $date = date("Y-m-d", strtotime("+1 month", strtotime($date)));
                                                ?>
                                                        <option value="<?php echo strtoupper(date('My',strtotime($date)))?>"><?php echo strtoupper(date('My',strtotime($date)))?></option>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                        
                                    <input type="hidden" name="action" id="action" value="lists" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <input type="submit" value="Generate Lists" id="frmbtnTermination" class="btn" name="btnTermination">
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 650px;"><span>For Termination List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 660px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="12%" class="td-bottom-border">Code</th>
                                    <th width="23%" class="td-bottom-border">Name</th>
                                    <th width="12%" class="td-bottom-border">Purchase Amount</th>
                                    <th width="12%" class="td-bottom-border">Last PO Date</th>
                                    <th width="8%" class="td-bottom-border">Terminate?</th>
                                </tr>
                                
                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="5">Select on the options to proceed...</td>
                                </tr>
                            </table>
                            
                            <div class="tbl-clear clear-small"></div>
                            <div class="tbl-float-inherit page">
                                <div id="tblPageNavigation"></div>
                            </div>
                            <div id="btn-for-checkbox" class="tbl-float-right hide">
                                <a href="javascript:void(0);" class="btn btn-cancel">Print and Save</a>
                            </div>
                        </div>
                        <div class="tbl-clear clear-small"></div>
                    </td>
                </tr>
            </table>              
        </td>
    </tr>
</table>
