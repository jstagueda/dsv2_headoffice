<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 11, 2013
 */
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.global.js"></script>
<script type="text/javascript" src="js/ipm/jquery.ValuationReport.js"></script>
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
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Valuation Report</span></td>
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
                                <form class="tbl-float-inherit" id="IPM-VRForm" method="POST" action="">
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
                                    
                                    <div class="tbl-lbl tbl-float-inherit">Location:</div>
                                    <div class="tbl-input tbl-float-left">
                                        <select style="width: 188px;margin-right: 10px;" id="location" name="location">
                                            <option value="">Select location here...</option>
                                            <option value="1">WAREHOUSE</option>
                                            <option value="2">DAMAGE GOODS</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <?php $rs_productline = $database->execute("SELECT ID, Code, Name FROM product WHERE ProductLevelID =  2 ORDER BY Code ASC"); ?>
                                    <div class="tbl-lbl tbl-float-inherit">Product Line:</div>
                                    <div class="tbl-input tbl-float-left">
                                        <select style="width: 188px;margin-right: 10px;" id="pline" name="pline">
                                            <option value="">Select product line here...</option>
                                            <?php 				
                                                if($rs_productline->num_rows)
                                                {
                                                    while($row= $rs_productline->fetch_object())
                                                    {									
                                            ?>
                                                <option value="<?php echo $row->ID; ?>"><?php echo $row->Code."-".$row->Name; ?></option>
                                            <?php
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-xsmall"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto" style="width: 89px;">PMG:</div>
                                    <div class="tbl-input tbl-float-left">
                                        <select style="width: 188px;margin-right: 10px;" id="pmg" name="pmg">
                                            <option value="">Select PMG here...</option>
                                            <option value="0">ALL</option>
                                            <option value="1">CFT</option>
                                            <option value="2">NCFT</option>
                                            <option value="3">CPI</option>
                                        </select>
                                    </div>
                                    <div class="tbl-clear clear-small"></div>
                                    
                                    <div class="tbl-lbl tbl-float-inherit width-auto" style="width: 89px;">As Of Date:</div>
                                    <div class="tbl-input tbl-float-left"><input style="width: 90px;" name="AsOfDate" type="text" id="AsOfDate" value="" /><span class="field-example">(e.g. YYYY-MM-DD)</span></div>
                                    <div class="tbl-clear clear-small"></div>
                                    
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
                                            <th width="10%" class="td-bottom-border">Product Line</th>
                                            <th width="10%" class="td-bottom-border">Item Code</th>
                                            <th width="20%" class="td-bottom-border">Item Description</th>
                                            <th width="10%" class="td-bottom-border">Price</th>
                                            <th width="8%" class="td-bottom-border">SOH</th>
                                            <th width="15%" class="td-bottom-border">Total Value</th>
                                        </tr>

                                        <tr class="tbl-td-rows">
                                            <td class="tbl-td-center td-bottom-border" colspan="6">&nbsp;</td>
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