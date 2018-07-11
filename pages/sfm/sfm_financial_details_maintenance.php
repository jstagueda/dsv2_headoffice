<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 25, 2013
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
<script type="text/javascript" src="js/sfm_js/jquery.sfmFinancialDetails.js"></script>
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
            <?php  include("nav_dealer.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Sales Force Financial Details Maintenance</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Financial Details Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-FDMForm" method="POST" action="">
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">SF Level:</div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="SFL" name="SFL">
                                            <option value="">Select sales force level here...</option>
                                            <option class="SFL hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Code:</div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="customer_code" type="text" id="customer_code" value="" /></div>
                                    <div class="tbl-clear clear-small"></div>
                                    <div id="autocomplete-loader" class="hide bold"></div>
                                    
                                    <div class="tbl-float-inherit hide" id="tbl-FDM-edits">
                                        <div class="tbl-clear clear-xmedium"></div>
                                        <div class="tbl-lbl tbl-float-inherit" style="width: 150px;font-weight: bold;">CURRENTLY FOR UPDATE:</div>
                                        <div class="tbl-clear"></div>
                                        <div class="tbl-input tbl-float-inherit" id="customer_code_selected"></div>
                                        <div class="tbl-clear clear-small"></div>
                                        <div class="tbl-lbl tbl-float-inherit" style="width: 150px;font-weight: bold;">NETWORK DETAILS:</div>
                                        <div class="tbl-clear"></div>
                                        <div class="tbl-input tbl-float-inherit" id="customer_code_IBM"></div>
                                        <div class="tbl-clear clear-small"></div>
                                        
                                        <div class="tbl-lbl tbl-float-inherit">&nbsp;</div>
                                        <div class="tbl-input tbl-float-inherit"><div class="head-update-to"><p>UPDATE TO:</p></div></div>
                                        <div class="tbl-clear clear-small"></div>
                                        
                                        <div class="tbl-lbl tbl-float-inherit">GSU Type:</div>
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 119px;" id="GSUType-old" name="GSUType-old" disabled="disabled">
                                                <option value="1">NonGSU</option>
                                                <option value="2">Direct GSU</option>
                                                <option value="3">Indirect GSU</option>
                                            </select>
                                        </div>
                                        <div class="tbl-input tbl-float-inherit padding-left-small">
                                            <select style="width: 119px;" id="GSUType-new" name="GSUType-new">
                                                <option value="">Select new GSU</option>
                                                <option value="1">NonGSU</option>
                                                <option value="2">Direct GSU</option>
                                                <option value="3">Indirect GSU</option>
                                            </select>
                                        </div>
                                        <div class="tbl-clear clear-small"></div>
                                        
                                        <div class="tbl-lbl tbl-float-inherit">Credit Limit:</div>
                                        <div class="tbl-input tbl-float-inherit"><input class="input-text" style="width: 117px;" name="CL-old" type="text" id="CL-old" value="" disabled="disabled"/></div>
                                        <div class="tbl-input tbl-float-inherit padding-left-small"><input class="input-text" style="width: 117px;" name="CL-new" type="text" id="CL-new" value=""/></div>
                                        <div class="tbl-clear clear-small"></div>
                                        
                                        <div class="tbl-lbl tbl-float-inherit">Credit Term:</div>
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 119px;" id="CT-old" name="CT-old" disabled="disabled">
                                                <?php echo $opts; ?>
                                            </select>
                                        </div>
                                        <div class="tbl-input tbl-float-inherit padding-left-small">
                                            <select style="width: 119px;" id="CT-new" name="CT-new">
                                                <option value="">Select new term</option>
                                                <?php echo $opts; ?>
                                            </select>
                                        </div>
                                        <div class="tbl-clear clear-small"></div>
                                        
                                        <input type="hidden" name="IBMID" id="IBMID" value="" />
                                        <input type="hidden" name="CustomerID" id="CustomerID" value="" />
                                        <input type="hidden" name="action" id="action" value="update" />
                                        <div class="tbl-clear clear-medium"></div>
                                        <input type="submit" value="Update Financial Details" id="btnFDM" class="btn" name="btnFDM">
                                        <a href="" class="btn btn-cancel">Cancel</a>
                                    </div>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        <div class="tbl-clear clear-small"></div>
                    </td>
                </tr>
            </table>              
        </td>
    </tr>
</table>
