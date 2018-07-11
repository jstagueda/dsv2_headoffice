<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 16, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/bpm/jquery.CollectionDueReport.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<style>
  .ui-autocomplete {
    max-height: 150px;
    overflow-y: auto;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    height: 100px;
  }
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("bpm_left_nav.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Collection Due Report</span></td>
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
                                <form class="tbl-float-inherit" id="BPM-CDRForm" method="POST" action="">
                                    <div class="tbl-lbl tbl-float-inherit">Branch:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 252px;margin-right: 10px;" id="branch" name="branch">
                                                <option value="">Select branch here...</option>
                                                <?php 
                                                    $branches = tpi_GetBranches();
                                                    $skip = array('WHSE2','WHSE','HO'); //skip items need not to include in display...
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
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <?php
                                        //Let's get all level options..
                                        $SFL = tpi_getSalesForceLevel();
                                    ?>
                                    <div class="tbl-lbl tbl-float-inherit">Sales Force Level:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;margin-right: 10px;" id="SFL" name="SFL">
                                            <option value="">Select level here...</option>
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
                                    <div class="tbl-clear clear-small"></div>
                                    <div class="tbl-lbl tbl-float-inherit">From:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 251px;" name="accountFromDisplay" type="text" id="accountFrom-Display" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <div class="tbl-lbl tbl-float-inherit">To:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 251px;" name="accountToDisplay" type="text" id="accountTo-Display" value="" /></div>
                                    <input name="accountFrom" type="hidden" id="accountFrom" value="" />
                                    <input name="accountTo" type="hidden" id="accountTo" value="" />                                    
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Credit Account Type:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="CAT" name="CAT">
                                            <option value="">Select credit account type...</option>
                                            <option value="ALL">ALL</option>
                                            <option value="2_3">GSU</option>
                                            <option value="1">NON-GSU</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto">Due Date Range:</div>
                                    <div class="tbl-clear clear-small"></div>
                                    <div class="tbl-lbl tbl-float-inherit" style="width: 89px;">From:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 90px;" name="dateFrom" type="text" id="dateFrom" value="" /><span class="field-example">(e.g. YYYY-MM-DD)</span></div>
                                    <div class="tbl-clear clear-small"></div>
                                    <div class="tbl-lbl tbl-float-inherit" style="width: 89px;">To:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 90px;" name="dateTo" type="text" id="dateTo" value="" /><span class="field-example">(e.g. YYYY-MM-DD)</span></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div id="account-for-branch-loader" class="hide">
                                        <div class="tbl-input tbl-float-left"><img src="images/ajax-loader.gif" /></div>
                                        <div class="tbl-input tbl-float-left padding-small-left bold">Preparing accounts by branch, please wait...</div>
                                    </div>
                                    
                                    <input type="hidden" name="action" id="action" value="generate" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Generate Report" id="frmbtnCDR" class="btn" name="frmbtnCDR">
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
                                        <tr id="account-can-purchase">
                                            <th width="10%" id="th-ibmcode" class="td-bottom-border hide">IBM Code</th>
                                            <th width="10%" id="th-ibmname" class="td-bottom-border hide">IBM Name</th>
                                            <th width="10%" class="td-bottom-border">Account No.</th>
                                            <th width="10%" class="td-bottom-border">Account Name</th>
                                            <th width="5%" class="td-bottom-border">Credit Account Type</th>
                                            <th width="5%" class="td-bottom-border">Credit Term</th>
                                            <th width="10%" class="td-bottom-border">Invoice No.</th>  
                                            <th width="10%" class="td-bottom-border">Due Date</th>
                                            <th width="10%" class="td-bottom-border">Total Amount Due</th>
                                            <th width="10%" class="td-bottom-border">Contact No.</th>
                                        </tr>

                                        <tr class="tbl-td-rows">
                                            <td class="tbl-td-center td-bottom-border" colspan="10">&nbsp;</td>
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
