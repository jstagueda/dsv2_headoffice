<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 05, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/bpm/jquery.OnlineSalesInquiry.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("bpm_left_nav.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top" style="min-height: 610px; display: block;">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Online Sales Inquiry</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;min-height: 102px;">
                                <form class="tbl-float-inherit" id="IPM-DBRForm" method="POST" action="">
                                    <div class="tbl-lbl tbl-float-inherit">Branch:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <input style="width: 188px;margin-right: 10px;" class="txtfield" id="branch" name="branchList">
                                            <input type="hidden" value="0" name="branch">
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit width-auto" style="width: 89px;">Date:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 90px;" name="date" type="text" id="date" value="" /><span class="field-example">(e.g. YYYY-MM-DD)</span></div>
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
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 468px;"><span>Result</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>


                            <div class="tbl-mid-content tbl-float-inherit" style="width: 454px;min-height: 80px;">
                                <div class="tbl-float-left width-100-percent">

                                    <div class="tbl-float-right padding-left-small inquiry-data bold align-center">LESS CPI</div>
                                    <div class="tbl-float-right inquiry-data bold align-center">TOTAL</div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <div class="clear-with-border-bottom"></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-float-left">Campaign to Date DGS:</div>
                                    <div class="tbl-float-right padding-left-small inquiry-data align-right" id="CDDGS-less">0.00</div>
                                    <div class="tbl-float-right inquiry-data align-right" id="CDDGS">0.00</div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <div class="tbl-float-left">Invoice Amount:</div>
                                    <div class="tbl-float-right padding-left-small inquiry-data align-right" id="IA-less">0.00</div>
                                    <div class="tbl-float-right inquiry-data align-right" id="IA">0.00</div>

                                    <div class="tbl-clear clear-medium"></div>

                                    <div class="tbl-float-left">Today's DGS:</div>
                                    <div class="tbl-float-right padding-left-small inquiry-data align-right" id="TDGS-less">0.00</div>
                                    <div class="tbl-float-right inquiry-data align-right" id="TDGS">0.00</div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <div class="tbl-float-left">Invoice Amount:</div>
                                    <div class="tbl-float-right padding-left-small inquiry-data align-right" id="TIA-less">0.00</div>
                                    <div class="tbl-float-right inquiry-data align-right" id="TIA">0.00</div>

                                </div>
                            </div>

                            <div class="tbl-clear clear-small"></div>
                            <div class="tbl-float-inherit page">
                                <div id="tblPageNavigation"></div>
                            </div>
                            <div id="btn-for-print" class="tbl-float-right hide">
                                <a href="javascript:void(0);" class="btn btn-print">Download and print in Excel Format</a>
                            </div>
                        </div>
                        <div class="tbl-clear clear-small"></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
