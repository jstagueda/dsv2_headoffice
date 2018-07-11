<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<script type="text/javascript" src="js/popinbox.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script type="text/javascript" src="js/jxIBMSalesPerformance.js"></script>

<style>
    .formwrapper{border:1px solid #FF00A6; border-top:none; padding:10px; font-weight: bold;}
    .tablelisttr td{padding:5px; text-align:center; font-weight: bold; border-left:1px solid #FFA3E0; border-top:1px solid #FFA3E0;}
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
                    <td class="txtgreenbold13">IBM Sales Performance Report(ISPR)</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <br />
            
            <div style="width:95%; margin:0 auto;">
                <div style="float:left; width:600px;">
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
                        <form action="" method="post" name="IBMSalesPerformanceReport">
                        <table width="100%">
							<tr>
                                <td align="right">Branch</td>
                                <td align="center" width="5%">:</td>
                                <td>
                                    <input class="txtfield" name="branchName">
                                    <input class="txtfield" name="branch" type="hidden" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">IBM Range</td>
                                <td align="center">:</td>
                                <td>
                                    <input class="txtfield" name="ibmfrom">
                                    <input class="txtfield" name="ibmfromHidden" type="hidden" value="0">
                                    -
                                    <input class="txtfield" name="ibmto">
                                    <input class="txtfield" name="ibmtoHidden" type="hidden" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">Product Management Group</td>
                                <td align="center">:</td>
                                <td>
                                    <select class="txtfield" name="productcategory">
                                        <option value="0">SELECT</option>
                                        <?php 
                                            $producttype = $database->execute("SELECT * FROM tpi_pmg");
                                            if($producttype->num_rows){
                                                while($res = $producttype->fetch_object()){
                                                    echo "<option value='".$res->ID."'>".$res->Code."</option>";
                                                }
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td align="right">Campaign</td>
                                <td align="center">:</td>
                                <td>
                                    <input class="txtfield" name="campaign">
                                </td>
                            </tr>
                        </table>
                        
                        <div style="text-align:center; margin-top:20px;">
                            <input type="hidden" value="1" name="page">
                            <input class="btn" type="submit" name="btnSearch" value="Submit">
                        </div>
                        </form>
                        <div style="clear:both;"></div>
                    </div>
                </div>
                
                <div style="clear:both;">&nbsp;</div>
                <div class="loader" style="text-align:center; font-weight: bold; margin-bottom: 10px; font-size:12px;">
                    &nbsp;
                </div>
                
                <div style="width:100%; min-height:320px;">
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
                            <td colspan="3">
                                <div class="pgLoad">
                                    <table class="tablelisttable" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #FF00A6; border-top:none;">
                                        <tr class="tablelisttr">
                                            <td rowspan="3">IBM Code</td>
                                            <td rowspan="3">IBM Name</td>
                                            <td colspan="6">Discount Gross Sales</td>
                                            <td colspan="7">Staff Count</td>
                                            <td rowspan="3">Sales Amount</td>
                                            <td rowspan="3">Active Account</td>
                                            <td rowspan="3">% Active (Active / End Count)</td>
                                            <td rowspan="3">Avg. Order</td>                                            
                                        </tr>
                                        <tr class="tablelisttr">
                                            <td rowspan="2">This Month</td>
                                            <td rowspan="2">YTD</td>
                                            <td rowspan="2">Previous Month</td>
                                            <td rowspan="2">Inc/Dec vs. Prev. Month</td>
                                            <td rowspan="2">Same Month Previous Year</td>
                                            <td rowspan="2">Growth Vs. Same Month Previous Year</td>
                                            <td rowspan="2">Beginning Count</td>
                                            <td colspan="3">ADD</td>
                                            <td colspan="2">Remove</td>
                                            <td rowspan="2">End Count</td>
                                        </tr>
                                        <tr class="tablelisttr">
                                            <td>New Recruit</td>
                                            <td>Reactivated</td>
                                            <td>Others</td>
                                            <td>Terminated</td>
                                            <td>Others</td>
                                        </tr>
                                        <tr class="listtr">
                                            <td align="center" colspan="19">No result found.</td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
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