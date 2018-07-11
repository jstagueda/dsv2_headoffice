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
<script type="text/javascript" src="js/sfm_js/jquery.sfmKPI.js"></script>
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">KPI Standards Parameter</span></td>
                </tr>
                <tr>
                    <td>
                        <!-- FORM STARTS HERE -->
                        <div class="tbl-content-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 368px;"><span>KPI Standards Actions</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            <div class="tbl-mid-content tbl-float-inherit" style="width: 354px;">
                                <form class="tbl-float-inherit" id="SFM-kpiForm" method="POST" action="">
                                    <div class="tbl-float-inherit"><b>Required Fields <span class="required-asterisk">*</span></b></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Code:</div>
                                    <div class="tbl-input tbl-float-inherit"><input disabled="disabled" style="width: 250px;" type="text" id="KPIcodeID" name="KPIcodeID" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <?php /* hide this not yet done. ?>
                                    <div class="tbl-lbl tbl-float-inherit">KPI Routine:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit">
                                        <select style="width: 252px;" id="KPIroutine" name="KPIroutine">
                                            <option value="">Select KPI routine here...</option>    
                                            <?php
                                                $routines = tpi_KPISelectRoutines();
                                                foreach($routines as $r):
                                            ?>
                                                <option value="<?php echo $r; ?>"><?php echo $r; ?></option>
                                            <?php
                                                endforeach;
                                            ?>
                                        </select>    
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div><?php */?>
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Description:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 250px;" name="KPIdescription" type="text" id="KPIdescription" value="" /></div>
                                    <div class="tbl-clear clear-medium"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit bold">Effective Date:</div>
                                    <div class="tbl-clear clear-small"></div>
                                    <div class="tbl-lbl tbl-float-inherit">From:<span class="required-asterisk">*</span></div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" type="text" id="KPIstartDate" name="KPIstartDate" value="" /></div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    <div class="tbl-lbl tbl-float-inherit">To:</div>
                                    <div class="tbl-input tbl-float-inherit"><input style="width: 100px;" type="text" id="KPIendDate" name="KPIendDate" value="" /></div>
                                    
                                    <input type="hidden" name="KPIaction" id="KPIaction" value="insert" />
                                    <div class="tbl-clear clear-medium"></div>
                                    <input type="submit" value="Create New KPI" id="frmbtnKPI" class="btn" name="btnNewKPI">
                                    <a href="" class="btn btn-cancel">Cancel</a>
                                    <span id="frmLoader"></span>
                                </form>
                            </div>
                        </div>
                        
                        <!-- LISTING STARTS HERE -->
                        <div class="tbl-listing-div">
                            <div class="tbl-head-content-left tbl-float-inherit"></div>
                            <div class="tbl-head-content-center tbl-float-inherit" style="width: 550px;"><span>KPI Standards List(s)</span></div>
                            <div class="tbl-head-content-right tbl-float-inherit"></div>
                            <div class="tbl-clear"></div>
                            
                            <table width="100%" border="1" cellspacing="3" cellpadding="3" style="width: 560px;border-collapse: collapse;border-color: #959F63;">
                                <tr>
                                    <th width="20%" class="td-bottom-border">Code</th>
                                    <th width="40%" class="td-bottom-border">Description</th>
                                    <th width="20%" class="td-bottom-border">Effective Start Date</th>
                                    <th width="20%" class="td-bottom-border">Effective End Date</th>
                                    <th width="5%" class="td-bottom-border">Delete</th>
                                </tr>
                                
                                <tr class="tbl-td-rows">
                                    <td class="tbl-td-center td-bottom-border" colspan="5">Fetching KPI standards lists...</td>
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