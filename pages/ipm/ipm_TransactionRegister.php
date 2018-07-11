<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 15, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/ipm/jquery.TransactionRegister.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("ipm_left_nav.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Transaction Register</span></td>
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
                                <form class="tbl-float-inherit" id="IPM-TRForm" method="POST" action="">
                                    <div class="tbl-lbl tbl-float-inherit">Branch:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 188px;margin-right: 10px;" id="branch" name="branch">
                                                <option value="">Select branch here...</option>
                                                <?php 
                                                    $branches = tpi_GetBranches();
                                                    $skip = array('WHSE2','WHSE','HO');
                                                    if($branches){
                                                        foreach($branches as $b):
                                                            if(in_array($b->Code,$skip)) continue;
                                                ?>
                                                        <option value="<?php echo $b->ID; ?>"><?php echo $b->Code.' - '.$b->Name?></option>
                                                <?php
                                                        endforeach;
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <?php $mtypes = $sp->spSelectMovementType($database,-3,''); ?>
                                    <div class="tbl-lbl tbl-float-inherit">Movement Type:</div>
                                    <div class="tbl-input tbl-float-left">
                                        <select style="width: 188px;margin-right: 10px;" id="mtype" name="mtype">
                                            <option value="">Select location here...</option>
                                            <?php
                                                if($mtypes){
                                                    while($type = $mtypes->fetch_object()):
                                            ?>
                                                    <option value="<?php echo $type->ID; ?>"><?php echo $type->Code.' - '.$type->Name; ?></option>
                                            <?php
                                                    endwhile;
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xmedium"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto" style="width: 89px;">Date Range:</div>
                                    <div class="tbl-clear clear-xmedium"></div>
                                    <div class="tbl-lbl tbl-float-inherit">From:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 90px;" name="dateFrom" type="text" id="dateFrom" value="" /><span class="field-example">(e.g. YYYY-MM-DD)</span></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <div class="tbl-lbl tbl-float-inherit">To:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 90px;" name="dateTo" type="text" id="dateTo" value="" /><span class="field-example">(e.g. YYYY-MM-DD)</span></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto" style="width: 89px;">Search:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 188px;" name="keyword" type="text" id="keyword" value="" /></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <input type="hidden" name="action" id="action" value="generate" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Generate Report" id="frmbtnVR" class="btn" name="frmbtnVR">
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 769px;"><span>Result(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <div class="tbl-holder-autoscroller">
                                <div class="tbl-content-autoscroller">
                                    <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 760px;border-collapse: collapse;border-color: #959F63;">
                                        <tr>
                                            <th width="10%" class="td-bottom-border">Transaction Date</th>
                                            <th width="10%" class="td-bottom-border">Movement Type</th>
                                            <th width="10%" class="td-bottom-border">Reference/Document No.</th>
                                            <th width="10%" class="td-bottom-border">Item Code</th>
                                            <th width="20%" class="td-bottom-border">Item Description</th>
                                            <th width="6%" class="td-bottom-border">Issuing Branch</th>
                                            <th width="6%" class="td-bottom-border">Location</th>
                                            <th width="6%" class="td-bottom-border">Receiving Branch</th>
                                            <th width="6%" class="td-bottom-border">Transaction Qty</th>
                                        </tr>

                                        <tr class="tbl-td-rows">
                                            <td class="tbl-td-center td-bottom-border" colspan="9">&nbsp;</td>
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
