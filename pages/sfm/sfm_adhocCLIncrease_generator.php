<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: May 06, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript">var pageParam = false; </script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmAdhocCLIncrease.js"></script>
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">ADHOC CL Generator</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Action(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-GenAdhocCLIForm" method="POST" action="">
                                    
                                    <div class="tbl-lbl tbl-float-inherit">CL Increase Description:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="ADHOC-CLC" name="ADHOC-CLC">
                                            <option value="">Select what to generate...</option>
                                            <option class="ADHOC-CLC hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                        
                                    <input type="hidden" name="action" id="action" value="generate" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <input type="submit" value="Generate Lists" id="btnGenAdhocCLI" class="btn" name="btnGenAdhocCLI">
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 550px;"><span>List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 560px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="8%" class="td-bottom-border">Code</th>
                                    <th width="20%" class="td-bottom-border">Name</th>
                                    <th width="10%" class="td-bottom-border">Enrolled</th>
                                    <th width="8%" class="td-bottom-border">Print</th>
                                </tr>
                                
                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="4">No lists...</td>
                                </tr>
                            </table>
                            
                            <div class="tbl-clear clear-small"></div>
                            <div class="tbl-float-inherit page">
                                <div id="tblPageNavigation"></div>
                            </div>
                            <div id="btn-for-checkbox" class="tbl-float-right hide">
                                <a href="javascript:void(0);" class="btn btn-cancel btn-print">Print</a>
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
