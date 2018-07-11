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
<script type="text/javascript" src="js/sfm_js/jquery.sfmLevel.js"></script>
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Level Parameter</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Level Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-levelForm" method="POST" action="">
                                    <div class="tbl-float-inherit"><b>Required Fields <span class="required-asterisk">*</span></b></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Code:</div>
                                    <div class="tbl-input tbl-float-inherit"><input disabled="disabled" style="width: 250px;" type="text" id="lcodeID" name="lcodeID" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Rank:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="lrank" name="lrank">
                                            <option value="">Select rank here...</option>
                                            <option class="rank-select hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Abbreviation:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" type="text" id="labbrv" name="labbrv" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Description:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="ldescription" type="text" id="ldescription" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Status:</div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="lstatus" type="text" id="lstatus" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Can Purchase?:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="lrank" name="lcan_purchase">
                                            <option value="">Select level if can purchase?</option>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Credit Line Classification:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="lCLC" name="lCLC">
                                            <option value="">Select level credit line classification</option>
                                            <option value="W_GSU_CCL">With GSU/CCL</option>
                                            <option value="PO_GSU_CCL">Part of GSU/CCL</option>
                                            <option value="OCL">Own CL</option>
                                            <option value="ZERO">Zero</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Has Downline?:</div>
                                    <div class="tbl-input tbl-float-inherit"><input name="ldownline" type="checkbox" id="ldownline" value="0" /> Check if level can have / has downline.</div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">With Personal Account?:</div>
                                    <div class="tbl-input tbl-float-inherit"><input name="lpaccount" type="checkbox" id="lpaccount" value="0" /> Check if level has personal account.</div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Non-Trade?:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="ltrade" name="ltrade">
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-medium"></div>

                                    <div class="tbl-lbl tbl-float-inherit bold">Effective Date:</div>
                                    <div class="tbl-clear clear-small"></div>
                                    <div class="tbl-lbl tbl-float-inherit">From:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" type="text" id="lstartDate" name="lstartDate" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <div class="tbl-lbl tbl-float-inherit">To:</div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" type="text" id="lendDate" name="lendDate" value="" /></div>
                                    
                                    <input type="hidden" name="laction" id="laction" value="insert" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Create New Level" id="frmbtnLevel" class="btn" name="btnNewLevel">
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 672px;"><span>Level List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 682px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="5%" class="td-bottom-border">Rank</th>
                                    <th width="5%" class="td-bottom-border">Code</th>
                                    <th width="35%" class="td-bottom-border">Description</th>
                                    <th width="5%" class="td-bottom-border">Can Purchase?</th>
                                    <th width="15%" class="td-bottom-border">Effective Start Date</th>
                                    <th width="15%" class="td-bottom-border">Effective End Date</th>
                                    <th width="5%" class="td-bottom-border">Delete</th>
                                </tr>
                                
                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="7">Fetching level lists...</td>
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