<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 08, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/sfpm/jquery.StatementOfAccount.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("sfpm_left_nav.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top" style="min-height: 610px; display: block;">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Statement Of Account</span></td>
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
                                <form class="tbl-float-inherit" id="SFPM-SOAForm" method="POST" action="">
                                    <div class="tbl-lbl tbl-float-inherit">Branch:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <input style="width: 252px;margin-right: 10px;" id="branch" class="txtfield" name="branchList">
                                            <input style="width: 252px;margin-right: 10px;" value="0" name="branch" type="hidden">
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>


                                    <div id="account-for-branch" class="tbl-float-inherit">
                                        <div class="tbl-lbl tbl-float-inherit">Account No.:</div>
                                        <div class="tbl-input tbl-float-left"><input style="width: 251px;" name="accountNo-Display" type="text" id="accountNo-Display" value="" /></div>
                                        <div class="tbl-clear clear-xsmall"></div>
                                        <input name="accountNo" type="hidden" id="accountNo" value="" />
                                    </div>

                                    <div class="tbl-clear clear-small"></div>
                                    <div class="tbl-lbl tbl-float-inherit width-auto" style="width: 89px;">Date Range:</div>
                                    <div class="tbl-clear clear-small"></div>
                                    <div class="tbl-lbl tbl-float-inherit" style="width: 89px;">From:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 90px;" name="dateFrom" type="text" id="dateFrom" value="" /><span class="field-example">(e.g. YYYY-MM-DD)</span></div>
                                    <div class="tbl-clear clear-small"></div>
                                    <div class="tbl-lbl tbl-float-inherit" style="width: 89px;">To:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 90px;" name="dateTo" type="text" id="dateTo" value="" /><span class="field-example">(e.g. YYYY-MM-DD)</span></div>
                                    <div class="tbl-clear clear-small"></div>

                                    <div class="tbl-lbl tbl-float-inherit width-auto" style="width: 89px;">Transaction Type:</div>
                                    <div class="tbl-input tbl-float-left">
                                        <select style="width: 188px;margin-right: 10px;" id="ttype" name="ttype">
                                            <option value="">Select transaction type here...</option>
                                            <option value="ALL">ALL</option>
                                            <option value="1">DEBIT</option>
                                            <option value="2">CREDIT</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>

                                    <div id="account-for-branch-loader" class="hide">
                                        <div class="tbl-clear clear-small"></div>
                                        <div class="tbl-input tbl-float-left"><img src="images/ajax-loader.gif" /></div>
                                        <div class="tbl-input tbl-float-left padding-small-left bold">Preparing accounts by branch, please wait...</div>
                                        <div class="tbl-clear clear-small"></div>
                                    </div>

                                    <input type="hidden" name="action" id="action" value="generate" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Generate Report" id="frmbtnSOA" class="btn" name="frmbtnSOA">
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>

                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 650px;"><span>List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>

                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 660px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="12%" class="td-bottom-border">Date</th>
                                    <th width="12%" class="td-bottom-border">Transaction Type</th>
                                    <th width="16%" class="td-bottom-border">Document No.</th>
                                    <th width="16%" class="td-bottom-border">Debit/Credit Amount</th>
                                    <th width="16%" class="td-bottom-border">Running Balance</th>
                                </tr>

                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="6">&nbsp;</td>
                                </tr>
                            </table>

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