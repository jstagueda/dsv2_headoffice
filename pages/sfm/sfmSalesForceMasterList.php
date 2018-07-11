<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<script type="text/javascript" src="js/popinbox.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script type="text/javascript" src="js/jxSalesForceMasterList.js"></script>

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
                    <td class="txtgreenbold13">Sales Force Master List</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <br />

            <div style="width:95%; margin:0 auto;">
                <div style="float:left; width:500px;">
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
                        <form action="" method="post" name="salesforcemasterlistform">
                        <table width="100%">
                            <tr>
                                <td width="" align="right">Branch</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <input class="txtfield" name="branchSearch" type="text">
                                    <input class="txtfield" name="branch" type="hidden" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">Sales Force Level</td>
                                <td align="center">:</td>
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
                                <td align="right">SFM Code Range</td>
                                <td align="center">:</td>
                                <td>
                                    <input class="txtfield" name="codefrom">
                                    <input class="txtfield" name="codefromHidden" type="hidden" value="0">
                                    -
                                    <input class="txtfield" name="codeto">
                                    <input class="txtfield" name="codetoHidden" type="hidden" value="0">
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
                                            <td>Branch</td>
                                            <td>Account Code</td>
                                            <td>Account Name</td>
                                            <td>SF Level</td>
                                            <td>Appointment Date</td>
                                            <td>Mother Network Code</td>
                                            <td>Mother Network Name</td>
                                            <td>TIN</td>
                                            <td>Bank Account No.</td>
                                            <td>Status</td>
                                            <td>Termination Date</td>
                                            <td>Offsetting / Payout</td>
                                            <td>Branch Affiliation</td>
                                        </tr>
                                        <tr class="listtr">
                                            <td align="center" colspan="14">No result found.</td>
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