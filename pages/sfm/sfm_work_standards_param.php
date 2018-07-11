<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 24, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmWorkStandards.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("nav_dealer.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top" style="min-height: 610px; display: block;">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Work Standards Parameter</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Work Standards Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-wsForm" method="POST" action="">
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">SF Level:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="wsSFL" name="wsSFL">
                                            <option value="">Select sales force level here...</option>
                                            <option class="ws-SFL hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">KPIs:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="wsKPI" name="wsKPI">
                                            <option value="">Select KPI here...</option>
                                            <option class="ws-KPI hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Criteria Type:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="wsCType" name="wsCType">
                                            <option value="">Select criteria type here...</option>
<!--                                            <option value="qualification">Qualification Criteria</option>
                                            <option value="maintenance">Maintenance Criteria</option>-->
                                            <option value="PROMOTION">Promotion Criteria</option>
                                            <option value="DEMOTION">Reversion Criteria</option>
                                            <option value="TERMINATION">Termination Criteria</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-medium"></div>
                                    
                                    <input type="hidden" name="wsaction" id="wsaction" value="lists" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="View Work Standards" id="frmbtnWS" class="btn" name="btnViewWS">
                                    <a href="" class="btn btn-cancel">Reset</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 650px;"><span>Work Standards List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 660px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="15%" class="td-bottom-border">Level</th>
                                    <th width="15%" class="td-bottom-border">KPI</th>
                                    <th width="40%" class="td-bottom-border">Criteria Description</th>
                                </tr>
                                
                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="3">Select on the form options to view work standards...</td>
                                </tr>
                            </table>
                            
                            <div class="tbl-clear clear-small"></div>
                            <div class="tbl-float-inherit page">
                                <div id="tblPageNavigation"></div>
                            </div>
                        </div>
                        <div class="tbl-clear clear-small"></div>
                    </td>
                </tr>
            </table>              
        </td>
    </tr>
</table>