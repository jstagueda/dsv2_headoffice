<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author: jdymosco
 * @date: January 25, 2013
 */

    $commissions = tpi_getCommissionTypes();
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script>
    var comTypes = <?php echo json_encode($commissions); ?>;
</script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script type="text/javascript" src="js/sfm_js/jquery.sfmSFEnrollment.js"></script>
<style>
  .ui-autocomplete {
    /*max-height: 150px;*/
    /*overflow-y: auto;*/
    /* prevent horizontal scrollbar */
    /*overflow-x: hidden;*/
  }
  /* IE 6 doesn't support max-height
   * we use height instead, but this forces the menu to always be this tall
   */
  * html .ui-autocomplete {
    /*height: 100px;*/
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Sales Force Manager Enrollment</span></td>
                </tr>
                <tr>
                    <td style="min-height:575px; display:block;">
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Sales Force Manager Enrollment Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-enrollmentForm" method="POST" action="">
                                    <div class="tbl-float-right hide" id="checker-if-exists"><img src="images/ajax-loader.gif" /> Checking if exists...</div>
                                    <div class="tbl-float-inherit"><b>Required Fields <span class="required-asterisk">*</span></b></div>
                                    <div class="tbl-clear clear-small"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Code:</div>
                                    <div class="tbl-input tbl-float-inherit"><input disabled="disabled" style="width: 250px;" type="text" id="ecodeID" name="ecodeID" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">SF Level:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="eSFL" name="eSFL">
                                            <option value="">Select sales force level here...</option>
                                            <option class="enrollment-SFL hide"></option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Last Name:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="elast_name" type="text" id="elast_name" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">First Name:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="efirst_name" type="text" id="efirst_name" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Middle Name:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="emiddle_name" type="text" id="emiddle_name" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="tbl-lbl tbl-float-inherit">Birth Date:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <input style="width: 100px;" name="edob" type="text" id="edob" value="" />
                                        <i>(e.g. YYYY-MM-DD)</i>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>

                                    <div class="hide tbl-float-inherit tbl-enrollment-others">
                                        <div class="tbl-lbl tbl-float-inherit">Network Code:</div>
                                        <div class="tbl-input tbl-float-inherit">
                                            <input type="text" style="width: 252px;" id="eULN" name="eULN" />
                                            <input type="hidden" id="eULN-IBMIDused" name="eULN-IBMIDused" />
                                        </div>
                                        <div class="tbl-clear clear-xsmall"></div>

                                        <div class="tbl-lbl tbl-float-inherit">Credit Term:<span class="required-asterisk">*</span></div>
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 252px;" id="eCT" name="eCT">
                                                <option value="">Select credit term here...</option>
                                                <option class="enrollment-eCT hide"></option>
                                            </select>
                                        </div>
                                        <div class="tbl-clear clear-xsmall"></div>

                                        <div class="tbl-lbl tbl-float-inherit">Payout or Offsetting:<span class="required-asterisk">*</span></div>
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 252px;" id="ePOorOS" name="ePOorOS">
                                                <option value="">Select here...</option>
                                                <option value="1">PAYOUT</option>
                                                <option value="0">OFFSETTING</option>
                                            </select>
                                        </div>
                                        <div class="tbl-clear clear-xsmall"></div>

                                        <div class="tbl-lbl tbl-float-inherit">Vatable:<span class="required-asterisk">*</span></div>
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 252px;" id="eVatable" name="eVatable">
                                                <!--<option value="">Select here...</option>-->
												<option value="0">NO</option>
                                                <option value="1">YES</option>
                                                
                                            </select>
                                        </div>
                                        <div class="tbl-clear clear-xsmall"></div>

                                        <div class="tbl-lbl tbl-float-inherit">Applicable Bonuses:<span class="required-asterisk">*</span></div>
                                        <div class="tbl-input tbl-float-inherit">
                                            <?php if($commissions): ?>
                                                <ul class="ul tbl-float-inherit" id="applicable-bonuses">
                                                    <?php foreach($commissions as $com): ?>
                                                        <li><input type="checkbox" name="eAB[]" id="com<?php echo $com->Code; ?>" value="<?php echo $com->Code; ?>" /><?php echo $com->Name; ?></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                        <div class="tbl-clear clear-xsmall"></div>

                                        <div class="tbl-lbl tbl-float-inherit">Credit Limit:<span class="required-asterisk">*</span></div>
                                        <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" name="ecredit_limit" type="text" id="ecredit_limit" value="" /></div>
                                        <div class="tbl-clear clear-small"></div>

                                        <div class="tbl-lbl tbl-float-inherit">TIN:<span class="required-asterisk">*</span></div>
                                        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="eTIN" type="text" id="eTIN" value="" /></div>
                                        <div class="tbl-clear clear-xsmall"></div>

                                        <div class="tbl-lbl tbl-float-inherit bold" style="width: 250px;">Bank Information:</div>
                                        <div class="tbl-clear clear-small"></div>
                                        <div class="tbl-lbl tbl-float-inherit">Acct. No.:</div>
                                        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="ebank_account_num" type="text" id="ebank_account_num" value="" /></div>
                                        <div class="tbl-clear clear-xsmall"></div>

                                        <div class="tbl-lbl tbl-float-inherit">Acct. Name:</div>
                                        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="ebank_account_name" type="text" id="ebank_account_name" value="" /></div>
                                        <div class="tbl-clear clear-xsmall"></div>

                                        <div class="tbl-lbl tbl-float-inherit">Bank Name:</div>
                                        <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="ebank_name" type="text" id="ebank_name" value="" /></div>
                                    </div>


                                    <input type="hidden" name="SFEaction" id="SFEaction" value="insert" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <a href="" class="btn btn-cancel">Clear</a>
                                    <a href="" class="btn btn-cancel btn-browse-network hide">Browse Network</a>
                                    <input type="submit" value="Next" id="frmbtnSFE" class="btn hide" name="btnNewSFE">
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                            <div style="clear:both;">&nbsp;</div>
                            <input type="button" value="Search Profile" name="btnSearch" class="btn" onclick="return searchProfile();">
                        </div>

                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 550px;"><span>Sales Force Manager List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>

                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="20%" class="td-bottom-border">IBM Code</th>
                                    <th width="20%" class="td-bottom-border">SF Level</th>
                                    <th width="40%" class="td-bottom-border">Name</th>
                                </tr>

                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="3">Fetching manager lists...</td>
                                </tr>
                            </table>

                            <div class="tbl-clear clear-small"></div>
                            <div class="tbl-float-inherit page">
                                <div id="tblPageNavigation"></div>
                            </div>
                            <div id="edit-loader" class="tbl-float-right hide"><img src="images/ajax-loader.gif" /> Preparing, please wait...</div>
                        </div>
                        <div class="tbl-clear clear-small"></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<!--Added by joebert-->
<div id="dialogbox" style='display:none;'>
    <div style="margin-top:20px; padding:0 20px;">
        <label>Search:</label>
        <input type="text" name="search" class="txtfield">
        <input type="hidden" name="SearchCode" value="">
        <input type="hidden" name="searchID" value="0">
    </div>
</div>