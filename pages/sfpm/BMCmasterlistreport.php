<?php
#include ("initialize.php"); //start of database connections
#global $database;
$sessionUniqueID = uniqid();
?>

<script type="text/javascript" src="js/popinbox.js"></script>
<script type="text/javascript" src="js/jxBMCmasterlistrpt.js?rand=<?php echo $sessionUniqueID?>"></script>

<?php include IN_PATH.DS."scBMCmasterlist.php";?>

<script>
    //date picker
    $(function(){        
        $("[name=datefrom], [name=dateto]").datepicker({
            changeMonth: true,
            changeYear: true
        });
    });
</script>

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
                    <td class="txtgreenbold13">Candidates for BM-F Recommendation Report</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <br />
            
            <div style="width:95%; margin:0 auto;">
                <div style="float:left; width:540px;">
                    <div class="tbl-head-content-left tbl-float-inherit"></div>
                    <div class="tbl-head-content-center tbl-float-inherit" style="width: 530px;">
                        <span>Action</span>
                    </div>
                    <div class="tbl-head-content-right tbl-float-inherit"></div>
                    <div class="tbl-clear"></div>
                    <div class="formwrapper">
                        <form action="" method="post" name="formibmsalesreport">
                        <table width="100%">
							<tr>
                                <td align="right">Branch</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <input name="branchName" value="" class="txtfield">
                                    <input name="branch" value="0" type="hidden" class="txtfield">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">IBM Range</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <input name="IBMfrom" value="" class="txtfield">
                                    <input name="IBMfromHidden" value="0" type="hidden" class="txtfield">
                                    -
                                    <input name="IBMto" value="" class="txtfield">
                                    <input name="IBMtoHidden" value="0" type="hidden" class="txtfield">
                                </td>
                            </tr>
                            <tr>
                                <td align="right">STATUS</td>
                                <td width="3%" align="center">:</td>
                                <td>
                                    <select name="status" class="txtfield">
                                        <option value="0">SELECT ALL</option>
                                        <option value="1">ACTIVE</option>
										<option value="5">TERMINATED</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" align="center">
                                    <br />
                                    <input type="hidden" name="page" value="1">
                                    <input type="hidden" name="pageibm" value="1">
                                    <input class="btn" type="submit" name="btnSearch" value="Submit">
                                </td>
                            </tr>
                        </table>
                        </form>
                    </div>
                </div>
                <div style="clear:both;">&nbsp;</div>
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
                            <td colspan="3">
                                <div class="pgLoading">
                                    <table style="border:1px solid #FF00A6; border-top:none;" class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
                                        <tr class="tablelisttr">
                                            <td>BRANCH</td>
                                            <td>NATIONAL ID</td>
                                            <td>ACCOUNT CODE</td>
                                            <td>ACCOUNT NAME</td>
											<td>M-IBM</td>
                                            <td>LEVEL</td>
                                            <td>STATUS</td>
											<td>VATABLE</td>
											<td>WITH OR</td>
											<td>CANDIDACY DATE</td>
											<td>LAST TM DATE</td>
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