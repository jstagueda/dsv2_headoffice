<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 30, 2013
 */ 
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmMovementFunctions.js"></script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmMovementPromotion.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("nav_dealer.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Sales Force Movement Promotion</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Promotion Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-promotionForm" method="POST" action="">
                                    
                                    <div class="tbl-lbl tbl-float-inherit">From SF Level:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="eSFLfrom" name="eSFLfrom">
                                            <option value="">Select sales force level here...</option>
                                            <option class="promotion-SFL-from hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">To SF Level:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="eSFLto" name="eSFLto">
                                            <option value="">Select sales force level here...</option>
                                            <option class="promotion-SFL-to hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                        
                                    <input type="hidden" name="SFEpaction" id="SFEpaction" value="lists" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <input type="submit" value="Generate Lists" id="frmbtnSFE" class="btn" name="btnNewSFE">
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 650px;"><span>For Promotion List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 660px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="12%" class="td-bottom-border">Code</th>
                                    <th width="23%" class="td-bottom-border">Name</th>
                                    <th width="10%" class="td-bottom-border">Sales</th>
                                    <th width="8%" class="td-bottom-border">Recruits</th>
                                    <th width="8%" class="td-bottom-border">PRomote-Up</th>
                                    <th width="8%" class="td-bottom-border">BCR</th>
                                    <th width="8%" class="td-bottom-border">Approve?</th>
                                </tr>
                                
                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="7">Select on the options to proceed...</td>
                                </tr>
                            </table>
                            
                            <div class="tbl-clear clear-small"></div>
                            <div class="tbl-float-inherit page">
                                <div id="tblPageNavigation"></div>
                            </div>
                            <div id="btn-for-checkbox" class="tbl-float-right hide">
                                <a href="javascript:void(0);" class="btn btn-cancel">Proceed</a>
                            </div>
                        </div>
                        <div class="tbl-clear clear-small"></div>
                        <div class="tbl-clear clear-small"></div>
                    </td>
                </tr>
            </table>              
        </td>
    </tr>
</table>
