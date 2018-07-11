<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 09, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/sfpm/jquery.AccountBalanceInquiry.js"></script>
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Account Balance Inquiry</span></td>
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
                                <form class="tbl-float-inherit" id="SFPM-ABIForm" method="POST" action="">
                                    <div class="tbl-lbl tbl-float-inherit">Branch:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <input style="width: 252px;margin-right: 10px;" id="branch" class="txtfield" name="branchList">
                                            <input type="hidden" name="branch" value="0">
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>

                                    <?php
                                        //Let's get all level options..
                                        $SFL = tpi_getSalesForceLevel();
                                    ?>
                                    <div class="tbl-lbl tbl-float-inherit">Sales Force Level:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;margin-right: 10px;" id="SFL" name="SFL">
                                            <option value="0">Select level here...</option>
                                            <?php
                                                if($SFL->num_rows > 0){
                                                    while($lvl = $SFL->fetch_object()):
                                            ?>
                                                        <option data-sflid="<?php echo $lvl->codeID; ?>" value="<?php echo $lvl->codeID.'_'.$lvl->can_purchase; ?>"><?php echo $lvl->desc_code.' - '.$lvl->description; ?></option>
                                            <?php
                                                    endwhile;
                                                }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="tbl-clear clear-small"></div>
                                    <div class="tbl-lbl tbl-float-inherit width-auto">Account No. Range:<span class="field-example">Fill out "From" and "To" fields to find accounts.</span></div>
                                    <div class="tbl-clear clear-xmedium"></div>
                                    <div class="tbl-lbl tbl-float-inherit">From:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 251px;" name="accountFromDisplay" type="text" id="accountFrom-Display" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <div class="tbl-lbl tbl-float-inherit">To:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 251px;" name="accountToDisplay" type="text" id="accountTo-Display" value="" /></div>
                                    <input name="accountFrom" type="hidden" id="accountFrom" value="" />
                                    <input name="accountTo" type="hidden" id="accountTo" value="" />
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Credit Line Classification:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="CLC" name="CLC">
                                            <option value="">Select credit line classification</option>
                                            <option value="W_GSU_CCL">With GSU/CCL</option>
                                            <option value="PO_GSU_CCL">Part of GSU/CCL</option>
                                            <option value="OCL">Own CL</option>
                                            <option value="ZERO">Zero</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>

                                    <div id="account-for-branch-loader" class="hide">
                                        <div class="tbl-input tbl-float-left"><img src="images/ajax-loader.gif" /></div>
                                        <div class="tbl-input tbl-float-left padding-small-left bold">Preparing accounts by branch, please wait...</div>
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
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 769px;"><span>List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>

                            <div class="tbl-holder-autoscroller">
                                <div class="tbl-content-autoscroller">
                                    <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 760px;border-collapse: collapse;border-color: #959F63;">
                                        <tr id="account-can-purchase" class="hide">
                                            <th width="10%" class="td-bottom-border">Account No.</th>
                                            <th width="20%" class="td-bottom-border">Account Name</th>
                                            <th width="8%" class="td-bottom-border">Account Level</th>
                                            <th width="12%" class="td-bottom-border">Total Outstanding Balance</th>
                                            <th width="12%" class="td-bottom-border">Total Overdue Balance</th>
                                            <th width="12%" class="td-bottom-border">Total Penalty Amount</th>
                                        </tr>

                                        <tr id="account-higher">
                                            <th width="10%" class="td-bottom-border">Account No.</th>
                                            <th width="20%" class="td-bottom-border">Account Name</th>
                                            <th width="8%" class="td-bottom-border">Account Level</th>
                                            <th colspan="3" width="36%" class="td-bottom-border">Network Viewer</th>
                                        </tr>

                                        <tr class="tbl-td-rows">
                                            <td class="tbl-td-center td-bottom-border" colspan="6">&nbsp;</td>
                                        </tr>
                                    </table>
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

