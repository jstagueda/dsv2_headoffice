<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: March 05, 2013
 */

    $months = range(1,12);
    $bonus_rates = array('0.05' => '5%', '0.10' => '10%', '0.15' => '15%', '0.20' => '20%', '0.25' => '25%', '0.30' => '30%'); //We declared it statically for now...
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/sfm_js/jquery.sfmCandidacyMatrix.js"></script>
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Candidacy Matrix Parameter</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>Candidacy Matrix Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-candiMatrixForm" method="POST" action="">
                                    <div class="tbl-float-inherit"><b>Required Fields <span class="required-asterisk">*</span></b></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <input type="hidden" value="" name="matrixID" id="matrixID" />
                                    <div class="tbl-lbl tbl-float-inherit">SF Level:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 252px;" id="matrixSFL" name="matrixSFL">
                                                <option value="">Select sales force level here...</option>
                                                <option class="matrix-SFL hide"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <div class="tbl-lbl tbl-float-inherit">Month:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 150px;margin-right: 10px;" id="matrixMonth" name="matrixMonth">
                                                <option value="">Select number of month...</option>
                                                <?php foreach($months as $m){ ?>
                                                    <option value="<?php echo $m;?>"><?php echo $m; ?></option >
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <?php /* ?>
                                    <div class="tbl-lbl tbl-float-inherit">Campaign For Month:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 250px;margin-right: 10px;" id="matrixCampaign" name="matrixCampaign">
                                                <option value="">Select campaign for the month above...</option>
                                                <option class="matrix-Campaign hide"></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <?php  */ ?>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Paid Up DGS:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="PUDGS" type="text" id="PUDGS" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Recruits:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="recruits" type="text" id="recruits" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">BCR:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="BCR" type="text" id="BCR" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Candidacy Bonus Rate:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <div class="tbl-input tbl-float-inherit">
                                            <select style="width: 150px;margin-right: 10px;" id="CBR" name="CBR">
                                                <option value="">Select bonus rate here...</option>
                                                <?php foreach($bonus_rates as $i => $br){ ?>
                                                    <option value="<?php echo $i;?>"><?php echo $br; ?></option >
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <input type="hidden" name="action" id="action" value="insert" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Create New Matrix" id="frmbtnMatrix" class="btn" name="btnNewMatrix">
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 650px;"><span>Candidacy Matrix List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 660px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="15%" class="td-bottom-border">Level</th>
                                    <th width="15%" class="td-bottom-border">Month</th>
                                    <th width="15%" class="td-bottom-border">Paid Up DGS</th>
                                    <th width="15%" class="td-bottom-border">Recruits</th>
                                    <th width="15%" class="td-bottom-border">BCR</th>
                                    <th width="15%" class="td-bottom-border">Bonus Rate (%)</th>
                                </tr>
                                
                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="6">Fetching candidacy matrix lists...</td>
                                </tr>
                            </table>
                            
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

