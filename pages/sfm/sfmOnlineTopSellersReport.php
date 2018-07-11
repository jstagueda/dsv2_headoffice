<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<script type="text/javascript" src="js/popinbox.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script type="text/javascript" src="js/jxOnlineTopSellersReport.js"></script>
<script type="text/javascript" src="js/GlobalFunctions.js"></script>

<style>
    .formwrapper{border:1px solid #FF00A6; border-top:none; padding:10px; font-weight: bold;}
    .tablelisttr td{padding:5px; text-align:center; font-weight: bold; border-left:1px solid #FFA3E0;}
    .tablelisttr{background: #FFDEF0;}
    .tablelisttable{width:100%;}
    .listtr td{border-top:1px solid #FFA3E0; border-left:1px solid #FFA3E0; padding:5px;}
    .ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
    .ui-widget-overlay{height:130%;}
</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td valign="top">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">
                    <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
                        <tr>
                            <td width="70%" align="right">&nbsp;
                                <a href="javascript:void(0);" onclick="return leavepage(71);" class="txtblueboldlink">Leave Page</a>
                                |
                                <a class="txtblueboldlink" href="index.php?pageid=71">Sales Force Management Main</a>
                            </td>
                        </tr>
                    </table>
                    </td>
                </tr>
            </table>
            <br />
            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                    <td class="txtgreenbold13">Online Top Sellers Report</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <br />
            
            <div style="width:95%; margin:0 auto;">
                <div style="float:left; width:100%;">
                    <table cellspacing="0" cellpadding="0" width="100%">
                        <tr>
                            <td class="tabmin">&nbsp;</td>
                            <td class="tabmin2">
                                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                                    <tr>
                                        <td class="txtredbold"><b>Action</b></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="tabmin3">&nbsp;</td>
                        </tr>
                    </table>
                    <div class="formwrapper">
                        <form action="" method="post" name="onlinetopsellersreportform">
                        <table width="50%" align="left" style="min-width: 490px;">
                            <tr>
                                <td width="25%" align="right">Branch</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <input class="txtfield" name="branchx">
                                    <input class="txtfield" name="branch" type="hidden" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" align="right">Level</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <select class="txtfield" name="level">
                                        <option value="0">[SELECT]</option>
                                        <?php 
                                            $sfmlevel = $database->execute("SELECT * FROM sfm_level");
                                            if($sfmlevel->num_rows){
                                                while($res = $sfmlevel->fetch_object()){
                                                    echo "<option value='".$res->codeID."'>".trim($res->desc_code)." - ".$res->description."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">Code Range</td>
                                <td align="center">:</td>
                                <td>
                                    <input class="txtfield" name="codefrom">
                                    <input class="txtfield" name="codefromHidden" type="hidden">
                                    -
                                    <input class="txtfield" name="codeto">
                                    <input class="txtfield" name="codetoHidden" type="hidden">
                                </td>
                            </tr>                            
                            <tr>
                                <td align="right">Date Range</td>
                                <td align="center">:</td>
                                <td>
                                    <input type="text" id="datestart" name="datestart" value="<?=date("m/d/Y")?>" class="txtfield" readonly="yes">
                                    -
                                    <input type="text" id="dateend" name="dateend" value="<?=date("m/d/Y")?>" class="txtfield" readonly="yes">
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <i style="font-weight:normal;">(e.g. MM/DD/YYYY)</i>
                                    <i style="font-weight:normal; margin-left:70px;">(e.g. MM/DD/YYYY)</i>
                                </td>
                            </tr>
                        </table>
                            
                            
                        <table width="50%" style="min-width: 500px;">
                            <tr>
                                <td width="25%" align="right">Product Market Group</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <select class="txtfield" name="productmarketgroup">
                                        <option value="0">[SELECT ALL]</option>
                                        <?php
                                            $pmg = $database->execute("SELECT * FROM tpi_pmg where ID < 4");
                                            if($pmg->num_rows){
                                                while($res = $pmg->fetch_object()){
                                                    echo "<option value='".$res->ID."'>".trim($res->Code)."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">Invoice/DGS</td>
                                <td align="center">:</td>
                                <td>
                                    <select class="txtfield" name="invoiceDGS">
                                        <option value="0">[SELECT]</option>
                                        <option value="invoice">Invoice</option>
                                        <option value="dgs">DGS</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right" >Collection As Of</td>
                                <td align="center" >:</td>
                                <td>
                                    <input type="text" name="collectiondate" id="collectiondate" value="<?=date("m/d/Y")?>" class="txtfield" readonly="yes" />
                                    <i style="font-weight:normal;">(e.g. MM/DD/YYYY)</i>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">Sort By</td>
                                <td align="center">:</td>
                                <td>
                                    <select class="txtfield" name="sortby">
                                        <option value="">[SELECT]</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Paid Up">Paid Up</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">No Of Records To Show</td>
                                <td align="center">:</td>
                                <td>
                                    <select class="txtfield" name="recordtoshow">
                                        <option value="0">[SELECT]</option>
                                        <option value="10">10</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                    
                                </td>
                            </tr>
                        </table>
                        <div style="text-align:center; margin-top:20px;">
                            <input type="hidden" value="1" name="page">
                            <input class="btn" type="submit" name="btnSearch" value="Submit" onclick="return showPage(1);">
                        </div>
                        </form>
                        <div style="clear:both;"></div>
                    </div>
                </div>
                
                <div style="clear:both;">&nbsp;</div>
                <div class="loader" style="text-align:center; font-weight: bold; margin-bottom: 10px; font-size:12px;">
                    &nbsp;
                </div>
                
                <div style="width:100%; min-height:275px;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="tablelist">
                        <tr>
                            <td class="tabmin">&nbsp;</td>
                            <td class="tabmin2">
                                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                                    <tr>
                                        <td class="txtredbold"><b>Result(s)</b></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            <td class="tabmin3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3" style="border:1px solid #FF00A6; border-top:none;">
                                <div class="pgLoad">
                                    <table class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
                                        <tr class="tablelisttr">
                                            <td>Sequence</td>
                                            <td>Account No.</td>
                                            <td>Customer</td>
                                            <td>Amount Less CPI</td>
                                            <td>Total Billed Amount</td>
                                            <td>Online Payments</td>
                                            <td>Overdue Payments</td>
                                            <td>On-Time-Or-Not Payments</td>
                                            <td>On-Time BCR</td>
                                            <td>On-Time or/not BCR</td>
                                        </tr>
                                        <tr class="listtr">
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr class="listtr">
                                            <td align="center" colspan="10">No result found.</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class = "pagination">
					<!-- Ajax Pagination -->
                </div>
                <div style="text-align:center;">
                    <input class="btn" type="button" value="Back" name="btnBack" onclick="location.href='?pageid=71'">
                    <input class="btn" type="button" value="Print" name="btnPrint">
                </div>
            </div>
            
            <div style="clear:both;"></div>
        </td>
    </tr>
</table>
<br>

<!--Added by joebert-->
<div id="dialog-message" style='display:none;'>
    <p></p>
</div>
<div id="dialog-message-with-button" style='display:none;'>
    <p></p>
</div>
<!--end-->