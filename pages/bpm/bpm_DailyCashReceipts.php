<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 10, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/bpm/jquery.DailyCashReceipts.js"></script>
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Daily Cash Receipts</span></td>
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
                                <form class="tbl-float-inherit" id="BPM-DCRForm" method="POST" action="">
                                    <div class="tbl-lbl tbl-float-inherit">Branch:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <input style="width: 188px;margin-right: 10px;" class="txtfield" id="branch" name="branchList">
                                            <input name="branch" type="hidden" value="0">
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit width-auto" style="width: 89px;">Date:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 90px;" name="date" type="text" id="date" value="" /><span class="field-example">(e.g. YYYY-MM-DD)</span></div>
                                    <div class="tbl-clear clear-small"></div>

                                    <input type="hidden" name="action" id="action" value="generate" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Generate Report" id="frmbtnDCR" class="btn" name="frmbtnDCR">
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>

                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Result(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>

                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;min-height: 80px;">
                                <div class="tbl-float-left width-100-percent">

                                    <div class="tbl-lbl tbl-float-inherit bold width-auto">Branch:</div>
                                    <div class="tbl-input tbl-float-right" id="branch-text"></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit bold width-auto">Date:</div>
                                    <div class="tbl-input tbl-float-right" id="date-text"></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="clear-with-border-bottom"></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit bold width-auto">Total Cash:</div>
                                    <div class="tbl-input tbl-float-right" id="total-cash">0.00</div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit bold width-auto">Total Checks:</div>
                                    <div class="tbl-input tbl-float-right" id="total-checks">0.00</div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit bold width-auto">Total Desposit Slip:</div>
                                    <div class="tbl-input tbl-float-right" id="total-deposit">0.00</div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit bold width-auto">Total Cancelled Cash:</div>
                                    <div class="tbl-input tbl-float-right" id="total-cancelled-cash">0.00</div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit bold width-auto">Total Cancelled Checks:</div>
                                    <div class="tbl-input tbl-float-right" id="total-cancelled-checks">0.00</div>
                                    <div class="tbl-clear clear-xsmall"></div>
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
