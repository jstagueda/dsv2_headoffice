<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: May 03, 2013
 */

    //Let's get first the credit term options for the form...
    $cterms = tpi_getCreditTerms();
    $opts = '';
    if($cterms):
        foreach($cterms as $t):
            $opts.= '<option value="'.$t->ID.'">'.$t->Name.'</option>';
        endforeach;
    endif;
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript">var pageParam = true; </script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmAdhocCLIncrease.js"></script>
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Adhoc CL Increase</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Adhoc CL Increase Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-AHOCCLIForm" method="POST" action="">
                                    <div class="tbl-float-inherit"><b>Required Fields <span class="required-asterisk">*</span></b></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Description:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="description" type="text" id="description" value="" /></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Effectivity Date:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" type="text" id="eDate" name="eDate" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">SF Level:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="SFL" name="SFL">
                                            <option value="">Select sales force level here...</option>
                                            <option class="SFL hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Credit Term:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 119px;" id="CT" name="CT">
                                                <option value="">Select credit term</option>
                                                <?php echo $opts; ?>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Credit Type:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 119px;" id="GSUType" name="GSUType">
                                            <option value="">Select GSU</option>
                                            <option value="1">NonGSU</option>
                                            <option value="2">Direct GSU</option>
                                            <option value="3">Indirect GSU</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-medium"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit bold" style="width: 150px;">QUALIFYING CRITERIA</div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto">Mimimum months residency from effectivity period:</div>
                                    <div class="tbl-input tbl-float-right"><input class="text-align-right" style="width: 50px;" name="min_mon_res_fr_eff_period" type="text" id="min_mon_res_fr_eff_period" value="" /></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto">No. of months without PDA:</div>
                                    <div class="tbl-input tbl-float-right"><input class="text-align-right" style="width: 50px;" name="num_mon_wout_pda" type="text" id="num_mon_wout_pda" value="" /></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto">P.O. Period:</div>
                                    <div class="tbl-input tbl-float-right"><input style="width: 80px;" name="poPeriodEnd" type="text" id="poPeriodEnd" value="" /></div>
                                    <div class="tbl-input tbl-float-right">&nbsp;To&nbsp;</div>
                                    <div class="tbl-input tbl-float-right"><input style="width: 80px;" name="poPeriodStart" type="text" id="poPeriodStart" value="" /></div>
                                    <div class="tbl-input tbl-float-right">&nbsp;From&nbsp;</div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto">Minimum P.O. Amount:</div>
                                    <div class="tbl-input tbl-float-right"><input class="text-align-right" style="width: 50px;" name="minPOAmt" type="text" id="minPOAmt" value="" /></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto">Current Credit Limit:</div>
                                    <div class="tbl-input tbl-float-right"><input class="text-align-right" style="width: 80px;" name="CCLEnd" type="text" id="CCLEnd" value="" /></div>
                                    <div class="tbl-input tbl-float-right">&nbsp;To&nbsp;</div>
                                    <div class="tbl-input tbl-float-right"><input class="text-align-right" style="width: 80px;" name="CCLStart" type="text" id="CCLStart" value="" /></div>
                                    <div class="tbl-input tbl-float-right">&nbsp;From&nbsp;</div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto">Maximum CL Increase:</div>
                                    <div class="tbl-input tbl-float-right"><input class="text-align-right" style="width: 50px;" name="maxCLI" type="text" id="maxCLI" value="" /></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto">With advance payment:</div>
                                    <div class="tbl-input tbl-float-right"><input name="withAdvancePayment" type="checkbox" id="withAdvancePayment" value="0" /></div>
                                    <div class="tbl-clear clear-small"></div>
                                    <div id="withAdvancePayement-others" class="tbl-float-inherit hide" style="width: 350px;">
                                        <div class="tbl-lbl tbl-float-inherit width-auto">Minimum Amount:</div>
                                        <div class="tbl-input tbl-float-right"><input class="text-align-right" name="minAmount" type="text" id="minAmount" value="" /></div>
                                        <div class="tbl-clear clear-small"></div>
                                        <div class="tbl-lbl tbl-float-inherit width-auto">No. of Days:</div>
                                        <div class="tbl-input tbl-float-right"><input class="text-align-right" name="noOfDays" type="text" id="noOfDays" value="" /></div>
                                        <div class="tbl-clear clear-small"></div>
                                        <div class="tbl-lbl tbl-float-inherit width-auto">Payment Period:</div>
                                        <div class="tbl-input tbl-float-right"><input style="width: 80px;" name="PaymentDatePeriodEnd" type="text" id="PaymentDatePeriodEnd" value="" /></div>
                                        <div class="tbl-input tbl-float-right">&nbsp;To&nbsp;</div>
                                        <div class="tbl-input tbl-float-right"><input style="width: 80px;" name="PaymentDatePeriodStart" type="text" id="PaymentDatePeriodStart" value="" /></div>
                                        <div class="tbl-input tbl-float-right">&nbsp;From&nbsp;</div>
                                        <div class="tbl-clear clear-small"></div>
                                    </div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto">Basis of CL Increase:</div>
                                    <div class="tbl-input tbl-float-right"><input name="hasBOCLI" type="checkbox" id="hasBOCLI" value="0" /></div>
                                    <div class="tbl-clear clear-small"></div>
                                    <div id="hasBOCLI-others" class="tbl-float-inherit hide" style="width: 350px;">
                                        <div class="tbl-input tbl-float-right">
                                            <ul class="ul-BOCLI">
                                                <li><input name="hasBOCLIOption" type="radio" id="hasBOCLIOption" value="FA" /> Fixed Amount</li>
                                                <li><input name="hasBOCLIOption" type="radio" id="hasBOCLIOption" value="PCCL" /> Percentage of Current CL</li>
                                                <li><input name="hasBOCLIOption" type="radio" id="hasBOCLIOption" value="PAP" /> Percentage of Advance Payment</li>
                                                <li>
                                                    <input name="hasBOCLIOption" type="radio" id="hasBOCLIOption" value="PPOB" /> Percentage of PO Based
                                                    <ul class="ul-hasBOCLIOption hide" id="hasBOCLIOption-others">
                                                        <li><input name="hasBOCLIOptPPOB" type="radio" id="hasBOCLIOptPPOB" value="CSP" /> CSP</li>
                                                        <li><input name="hasBOCLIOptPPOB" type="radio" id="hasBOCLIOptPPOB" value="CSPLCPI" /> CSP Less CPI</li>
                                                        <li><input name="hasBOCLIOptPPOB" type="radio" id="hasBOCLIOptPPOB" value="IA" /> Invoice Amount</li>
                                                    </ul>
                                                </li>
                                            </ul>
                                            <div class="tbl-clear clear-small"></div>
                                            <div class="tbl-lbl tbl-float-left width-auto">Value: &nbsp;&nbsp;</div>
                                            <div class="tbl-input tbl-float-left"><input name="hasBOCLIValue" type="text" id="hasBOCLIValue" value="" /></div>
                                            <div class="tbl-clear clear-small"></div>
                                        </div>
                                    </div>
                                    
                                    <input type="hidden" name="editID" id="editID" value="" />
                                    <input type="hidden" name="action" id="action" value="insert" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Continue" id="frmbtnADHOC" class="btn" name="frmbtnADHOC">
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 650px;"><span>Adhoc CL List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 660px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="5%" class="td-bottom-border">Code</th>
                                    <th width="40%" class="td-bottom-border">Description</th>
                                    <th width="10%" class="td-bottom-border">Effective Date</th>
                                    <th width="10%" class="td-bottom-border">SF Level</th>
                                    <th width="8%" class="td-bottom-border">Credit Term</th>
                                    <th width="8%" class="td-bottom-border">Credit Type</th>
                                    <th width="5%" class="td-bottom-border">Delete</th>
                                </tr>
                                
                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="7">Fetching lists...</td>
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

