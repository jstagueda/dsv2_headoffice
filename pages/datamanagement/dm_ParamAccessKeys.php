<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: July 26, 2013
 */
?>
<script language="text/javascript" src="js/jquery-1.8.3.min.js" type="text/javascript"></script>
<script language="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js" type="text/javascript"></script>
<script language="text/javascript" src="js/jquery-ui-timepicker-addon.js" type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/datamgt/jquery.dmAccessKeys.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("nav.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top" style="min-height: 610px; display: block;">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Branch Access Keys</span></td>
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
                                <form class="tbl-float-inherit" id="DM-AKForm" method="POST" action="">
                                    <div class="tbl-float-inherit"><b>Required Fields <span class="required-asterisk">*</span></b></div>
                                    <div class="tbl-clear clear-small"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Branch:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <input class="txtfield" style="width: 188px;margin-right: 10px;" name="branchList">
                                            <input id="branch" name="branch" value="0" type='hidden'>
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Access Key:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" type="text" id="access_key" name="access_key" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Expiration Date:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" type="text" id="expdate" name="expdate" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <input type="hidden" name="ID" id="ID" value="" />
                                    <input type="hidden" name="action" id="action" value="insert" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Create New Access Key" id="frmbtn" class="btn" name="frmbtn">
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>

                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 672px;"><span>List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>

                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 682px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="10%" class="td-bottom-border">Branch</th>
                                    <th width="20%" class="td-bottom-border">Access Key</th>
                                    <th width="15%" class="td-bottom-border">Expiration</th>
                                    <th width="5%" class="td-bottom-border">Delete</th>
                                </tr>

                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="4">Fetching lists...</td>
                                </tr>
                            </table>

                            <div class="tbl-clear clear-small"></div>
                            <div class="tbl-float-inherit page">
                                <div id="tblPageNavigation"></div>
                            </div>
                            <div id="btn-for-checkbox" class="tbl-float-right hide">
                                <a href="javascript:void(0);" class="btn btn-delete">Delete</a>
                            </div>
                        </div>
                        <div class="tbl-clear clear-small"></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
