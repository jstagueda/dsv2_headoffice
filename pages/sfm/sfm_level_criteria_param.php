<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 21, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmLevelCriteria.js"></script>
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Level Criteria Parameter</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Level Criteria Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-criteriaForm" method="POST" action="">
                                    <div class="tbl-float-inherit"><b>Required Fields <span class="required-asterisk">*</span></b></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Code:</div>
                                    <div class="tbl-input tbl-float-inherit"><input disabled="disabled" style="width: 250px;" type="text" id="lccodeID" name="lccodeID" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">SF Level:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="lcSFL" name="lcSFL">
                                            <option value="">Select sales force level here...</option>
                                            <option class="level-criteria-SFL hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">KPIs:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="lcKPI" name="lcKPI">
                                            <option value="">Select KPI here...</option>
                                            <option class="level-criteria-KPI hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Criteria Type:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="lctype" name="lctype">
                                            <option value="">Select criteria type here...</option>
<!--                                            <option value="qualification">Qualification Criteria</option>
                                            <option value="maintenance">Maintenance Criteria</option>-->
                                            <option value="PROMOTION">Promotion Criteria</option>
                                            <option value="DEMOTION">Reversion Criteria</option>
                                            <option value="TERMINATION">Termination Criteria</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Description:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="lcdescription" type="text" id="lcdescription" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Min. Value:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input class="input-text" style="width: 100px;" name="lcmin_value" type="text" id="lcmin_value" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Max Value:</div>
                                    <div class="tbl-input tbl-float-inherit"><input class="input-text" style="width: 100px;" name="lcmax_value" type="text" id="lcmax_value" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">No. of months:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" name="lcnum_months" type="text" id="lcnum_months" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Avg. / Total:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="lcavg_total" name="lcavg_total">
                                            <option value="">Select type here...</option>
                                            <option value="average">AVERAGE</option>
                                            <option value="total">TOTAL</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="level-criteria-units tbl-float-inherit hide">
                                        <div class="tbl-lbl tbl-float-inherit">No. of req. units:<span class="required-asterisk">*</span></div>
                                        <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" name="lcreq_units" type="text" id="lcreq_units" value="" /></div>
                                    </div>
                                    <div class="tbl-clear clear-medium"></div>

                                    <div class="tbl-lbl tbl-float-inherit bold">Effective Date:</div>
                                    <div class="tbl-clear clear-small"></div>
                                    <div class="tbl-lbl tbl-float-inherit">From:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" type="text" id="lcstartDate" name="lcstartDate" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <div class="tbl-lbl tbl-float-inherit">To:</div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" type="text" id="lcendDate" name="lcendDate" value="" /></div>
                                    
                                    <input type="hidden" name="lcaction" id="lcaction" value="insert" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Create New Criteria" id="frmbtnCriteria" class="btn" name="btnNewCriteria">
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 665px;"><span>Level Criteria List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 675px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="5%" class="td-bottom-border">Code</th>
                                    <th width="6%" class="td-bottom-border">Level</th>
                                    <th width="20%" class="td-bottom-border">KPI</th>
                                    <th width="30%" class="td-bottom-border">Description</th>
                                    <th width="10%" class="td-bottom-border">Criteria Type</th>
                                    <th width="12%" class="td-bottom-border">Effective Start Date</th>
                                    <th width="12%" class="td-bottom-border">Effective End Date</th>
                                </tr>
                                
                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="7">Fetching level criteria lists...</td>
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