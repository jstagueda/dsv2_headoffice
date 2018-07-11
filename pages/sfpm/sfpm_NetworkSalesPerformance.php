<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 04, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/sfpm/jquery.NetworkSalesPerformance.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<style> .ui-datepicker table{display:none;} .ui-datepicker-current{display:none;} </style>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("sfpm_left_nav.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top" style="min-height: 610px; display: block;">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Network Sales Performance</span></td>
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
                                <form class="tbl-float-inherit" id="SFPM-NSPForm" method="POST" action="">
                                    <div class="tbl-lbl tbl-float-inherit width-auto">
                                        <span class="field-example">*Please fill out "Sales Force Level" and "Branch" fields first of all to smoothly generate results.</span>
                                    </div>
                                    <div class="tbl-clear clear-medium"></div>
                                    <?php
                                        //Let's get all level options..
                                        //2nd parameter means get only all level that can't purchase..
                                        $SFL = tpi_getSalesForceLevel(1,0);
                                    ?>
                                    <div class="tbl-lbl tbl-float-inherit">Sales Force Level:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;margin-right: 10px;" id="SFL" name="SFL">
                                            <option value="">Select level here...</option>
                                            <?php
                                                if($SFL->num_rows > 0){
                                                    while($lvl = $SFL->fetch_object()):
                                            ?>
                                                        <option value="<?php echo $lvl->codeID; ?>"><?php echo $lvl->desc_code.' - '.$lvl->description; ?></option>
                                            <?php
                                                    endwhile;
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Branch:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <input style="width: 252px;margin-right: 10px;" id="branch" name="branchList" class="txtfield">
                                            <input value="0" id="branch" type="hidden" name="branch">
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div id="network-for-branch-loader" class="hide">
                                        <div class="tbl-clear clear-small"></div>
                                        <div class="tbl-input tbl-float-left"><img src="images/ajax-loader.gif" /></div>
                                        <div class="tbl-input tbl-float-left padding-small-left bold">Preparing networks by branch, please wait...</div>
                                        <div class="tbl-clear clear-small"></div>
                                    </div>

                                    <div id="network-for-branch" class="tbl-float-inherit hide">
                                        <div class="tbl-clear clear-small"></div>
                                        <div class="tbl-lbl tbl-float-inherit width-auto">Network Range:<span class="field-example">Fill out "From" and "To" fields to find network.</span></div>
                                        <div class="tbl-clear clear-xmedium"></div>
                                        <div class="tbl-lbl tbl-float-inherit">From:</div>
                                        <div class="tbl-input tbl-float-left"><input style="width: 251px;" name="netFromDisplay" type="text" id="netFrom-Display" value="" /></div>
                                        <div class="tbl-clear clear-xsmall"></div>
                                        <div class="tbl-lbl tbl-float-inherit">To:</div>
                                        <div class="tbl-input tbl-float-left"><input style="width: 251px;" name="netToDisplay" type="text" id="netTo-Display" value="" /></div>
                                        <div class="tbl-clear clear-xmedium"></div>
                                        <input name="netFrom" type="hidden" id="netFrom" value="" />
                                        <input name="netTo" type="hidden" id="netTo" value="" />
                                    </div>

                                    <div class="tbl-lbl tbl-float-inherit width-auto" style="width: 89px;">Product Market Group:</div>
                                    <div class="tbl-input tbl-float-left">
                                        <select style="width: 188px;margin-right: 10px;" id="pmg" name="pmg">
                                            <option value="">Select PMG here...</option>
                                            <option value="ALL">ALL</option>
                                            <option value="CPI">CPI</option>
                                            <option value="CFT">CFT</option>
                                            <option value="NCFT">NCFT</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>

                                    <div class="tbl-lbl tbl-float-inherit width-auto" style="width: 89px;">Campaign Month:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 90px;" name="Period" type="text" id="Period" value="" /><span class="field-example">(e.g. January 2013)</span></div>
                                    <div class="tbl-clear clear-small"></div>

                                    <input type="hidden" name="action" id="action" value="generate" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Generate Report" id="frmbtnDBR" class="btn" name="frmbtnDBR">
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>

                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 669px;"><span>List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>

                            <div class="tbl-holder-autoscroller">
                                <div class="tbl-content-autoscroller">
                                    <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 660px;border-collapse: collapse;border-color: #959F63;">
                                        <tr>
                                            <th width="15%" class="td-bottom-border">Network Group</th>
                                            <th width="15%" class="td-bottom-border">Sales</th>
                                            <th width="15%" class="td-bottom-border">Recruit(s)</th>
                                            <th width="15%" class="td-bottom-border">Active(s)</th>
                                        </tr>

                                        <tr class="tbl-td-rows">
                                            <td class="tbl-td-center td-bottom-border" colspan="4">&nbsp;</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

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
