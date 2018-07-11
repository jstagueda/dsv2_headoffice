<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @dat: July 06, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/sfpm/jquery.TopSellingReport.js"></script>
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Top Selling Report</span></td>
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
                                <form class="tbl-float-inherit" id="SFPM-TSRForm" method="POST" action="">
                                    <div class="tbl-lbl tbl-float-inherit">Branch:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <input style="width: 252px;margin-right: 10px;" id="branch" class="txtfield" name="branchList">
                                            <input style="width: 252px;margin-right: 10px;" id="branch" type="hidden" name="branch">
                                        </div>
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

                                    <div class="tbl-lbl tbl-float-inherit" style="width: 89px;">Report Type:</div>
                                    <div class="tbl-input tbl-float-left">
                                        <select style="width: 252px;margin-right: 10px;" id="report_type" name="report_type">
                                                <option value="">Select report type here...</option>
                                                <option value="PRODUCT">PRODUCT</option>
                                                <option value="CUSTOMER">CUSTOMER</option>
                                                <option value="NETWORK">NETWORK</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>

                                    <div id="sales-type-options" class="tbl-float-inherit hide">
                                        <div class="tbl-lbl tbl-float-inherit" style="width: 89px;">Sales Type:</div>
                                        <div class="tbl-input tbl-float-left">
                                            <select style="width: 252px;margin-right: 10px;" id="sales_type" name="sales_type">
                                                    <option value="">Select sales type here...</option>
                                                    <option value="DGS">DGS</option>
                                                    <option value="INVOICE">INVOICE</option>
                                            </select>
                                        </div>
                                        <div class="tbl-clear clear-small"></div>
                                    </div>

                                    <div class="tbl-lbl tbl-float-inherit">No. of Records To Show:</div>
                                    <div class="tbl-input tbl-float-left"><input class="align-right" style="width: 90px;" name="num_records" type="text" id="num_records" value="" /></div>
                                    <div class="tbl-clear clear-small"></div>

                                    <input type="hidden" name="action" id="action" value="generate" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Generate Report" id="frmbtnTSR" class="btn" name="frmbtnTSR">
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
                                        <tr id="product-tr-head" class="hide">
                                            <th width="10%" class="td-bottom-border">Product Code</th>
                                            <th width="25%" class="td-bottom-border">Description</th>
                                            <th width="10%" class="td-bottom-border">Product Line</th>
                                            <th width="10%" class="td-bottom-border">Total Quantity Sold</th>
                                        </tr>

                                        <tr id="customer-network-tr-head" class="hide">
                                            <th width="10%" class="td-bottom-border">Account No.</th>
                                            <th width="25%" class="td-bottom-border">Account Name</th>
                                            <th width="10%" class="td-bottom-border">Amount Less CPI</th>
                                            <th width="10%" class="td-bottom-border">Total Billed Amount</th>
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
