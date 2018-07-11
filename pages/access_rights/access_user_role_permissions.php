<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: June 26, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/access_rights/jquery.UserRolePermissions.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("nav_access.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top" style="min-height: 610px; display: block;">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">User Role and Permissions</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 378px;"><span>Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 364px;">
                                <form class="tbl-float-inherit" id="URP-Form" method="POST" action="">
                                    <div class="tbl-lbl tbl-float-inherit">Search User:</div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 180px;" name="user" type="text" id="user" value="" /></div>
                                    <div class="tbl-input tbl-float-inherit hide" id="ajax-loader-small" style="padding-left: 6px;"><img src="images/ajax-loader.gif" border="0" /></div>
                                    <div class="tbl-clear clear-small"></div>

                                    <div class="tbl-float-inherit hide" id="tbl-URP-edits">
                                        <div class="tbl-lbl tbl-float-inherit bold">Name:</div>
                                        <div class="tbl-input tbl-float-inherit"><span id="user-name"></span></div>
                                        <div class="tbl-clear clear-small"></div>
                                        <div class="tbl-lbl tbl-float-inherit bold">UserName:</div>
                                        <div class="tbl-input tbl-float-inherit"><span id="user-username"></div>
                                        <div class="tbl-clear clear-small"></div>
                                        <div class="tbl-lbl tbl-float-inherit bold">Role:</div>
                                        <div class="tbl-input tbl-float-inherit"><span id="user-type"></div>
                                        <div class="tbl-clear clear-small"></div>
                                        <div class="tbl-lbl tbl-float-inherit bold">Department:</div>
                                        <div class="tbl-input tbl-float-inherit"><span id="user-department"></div>
                                        <div class="tbl-clear clear-small"></div>

                                        <div class="tbl-lbl tbl-float-inherit bold">Permissions:</div>
                                        <div class="tbl-input tbl-float-inherit">
                                            <ul class="ul">
                                                <li class="inline"><input name="role_perm[]" type="checkbox" id="role_perm_add" value="add" /> Add</li>
                                                <li class="inline"><input name="role_perm[]" type="checkbox" id="role_perm_edit" value="edit" /> Edit</li>
                                                <li class="inline"><input name="role_perm[]" type="checkbox" id="role_perm_delete" value="delete" /> Delete</li>
                                            </ul>
                                        </div>
                                        <div class="tbl-clear clear-small"></div>

                                        <input type="hidden" name="UserID" id="UserID" value="" />
                                        <input type="hidden" name="action" id="action" value="insert" />
                                        <div class="tbl-clear clear-medium"></div>
                                        <input type="submit" value="Add Permission to User" id="frmbtnPerm" class="btn" name="frmbtnPerm">
                                        <a href="" class="btn btn-cancel">Cancel</a>
                                        <span id="frmLoader"></span>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 650px;"><span>User Permissions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>

                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 660px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="20%" class="td-bottom-border">UserName</th>
                                    <th width="25%" class="td-bottom-border">Employee</th>
                                    <th width="20%" class="td-bottom-border">Role</th>
                                    <th width="15%" class="td-bottom-border">Permissions</th>
                                    <th width="10%" class="td-bottom-border">Delete</th>
                                </tr>

                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="5">Fetching user permissions...</td>
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