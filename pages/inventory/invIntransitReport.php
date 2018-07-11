<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<script type="text/javascript" src="js/popinbox.js"></script>
<script type="text/javascript" src="js/jxinvIntransitReport.js"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<?php
    include "includes/pagination.php";
    include "invPagingIntransitReport.php";
?>
<style>
    .formwrapper{border:1px solid #FF00A6; border-top:none; padding:10px;}
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
                            <a href="javascript:void(0);" onclick="return leavepage(1);" class="txtblueboldlink">Leave Page</a>
                            |
                            <a class="txtblueboldlink" href="index.php?pageid=1">Inventory Cycle Main</a>
                        </td>
                        </tr>
                    </table>
                    </td>
                </tr>
            </table>
            <br />
            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                    <td class="txtgreenbold13">Inventory Intransit Report</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <br />
            
            <div style="width:95%; margin:0 auto;">
                <div style="float:left; width:450px;">
                    <div class="tbl-head-content-left tbl-float-inherit"></div>
                    <div class="tbl-head-content-center tbl-float-inherit" style="width: 440px;">
                        <span>Action</span>
                    </div>
                    <div class="tbl-head-content-right tbl-float-inherit"></div>
                    <div class="tbl-clear"></div>
                    <div class="formwrapper">
                        <form action="" method="post" name="intransitform">
                        <table width="100%">
                            <tr>
                                <td width="" align="right">From Date</td>
                                <td width="3%;" align="center">:</td>
                                <td>
                                    <input type="text" readonly="yes" autocomplete="off" id="datestart" name="datestart" value="<?=$datefrom?>" class="txtfield">
                                    <i>(e.g. MM/DD/YYYY)</i>
                                </td>
                            </tr>
                            <tr>
                                <td width="" align="right">To Date</td>
                                <td width="3%;" align="center">:</td>
                                <td>
                                    <input type="text" readonly="yes" autocomplete="off" id="dateend" name="dateend" value="<?=$dateto?>" class="txtfield">
                                    <i>(e.g. MM/DD/YYYY)</i>
                                </td>
                            </tr>
                            <tr>
                                <td width="" align="right">Branch Code</td>
                                <td width="3%;" align="center">:</td>
                                <td>
                                    <select class="txtfield" name="branch">
                                        <option value="0">[SELECT ALL]</option>
                                        <?php
                                            if ($rs_branch->num_rows){
                                                while ($row = $rs_branch->fetch_object()){
                                                    $sel = (isset($_POST['branch']) AND $row->ID == $_POST['branch'])?"selected":"";
                                                    echo "<option value='".$row->ID."' $sel >".$row->Code." - ".$row->Name."</option>";
                                                }
                                                $rs_branch->close();
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td width="" align="right">Movement Type</td>
                                <td width="3%;" align="center">:</td>
                                <td>
                                    <select class="txtfield" name="movementtype">
                                        <option value="0">[SELECT ALL]</option>
                                        <?php 
                                            if ($rs_mtype->num_rows){
                                                while ($row = $rs_mtype->fetch_object()){
                                                    $sel = (isset($_POST['movementtype']) AND $row->ID == $_POST['movementtype'])?"selected":"";
                                                    echo "<option value='".$row->ID."' $sel >".$row->Code."</option>";
                                                }
                                                $rs_mtype->close();
                                            }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>
                                    <input class="btn" type="submit" name="btnSearch" value="Submit">
                                    <input type="hidden" value="" name="action">
                                    <input type="hidden" value="1" name="page">
                                </td>
                            </tr>
                        </table>
                        </form>
                    </div>
                </div>
                
                <div style="clear:both;">&nbsp;</div>
                <div class="loader" style="display:block; text-align:center; font-weight: bold; margin-bottom: 10px; font-size:12px;">
                    &nbsp;
                </div>
                
                <div style="width:100%; min-height:300px;">
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
                            <td colspan="3" class="listtable">
                                <div style="border:1px solid #FF00A6; border-top:none;">
                                    <table class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
                                        <tr class="tablelisttr">
                                            <td width="10%" style="border-left:none;">Date</td>
                                            <td width="10%">Movement Type Code</td>
                                            <td width="10%">Reference No.</td>
                                            <td width="10%">DR No.</td>
                                            <td width="10%">Shipment Advice No.</td>
                                            <td colspan="2">Item Code / Description</td>
                                            <td width="10%">Loaded Qty</td>
                                            <td width="10%">Loaded Date</td>
                                        </tr>
                                        <?php 
                                        if($intransitreport->num_rows > 0){
                                            while($row = $intransitreport->fetch_object()){
                                        ?>
                                        <tr class="listtr">
                                            <td style="border-left:none;"><?=$row->TransactionDate?></td>
                                            <td><?=$row->MovementCode?></td>
                                            <td><?=$row->PicklistRefNo?></td>
                                            <td><?=$row->DocumentNo?></td>
                                            <td><?=$row->ShipmentAdviseNo?></td>
                                            <td width="5%"><?=$row->Code?></td>
                                            <td style="border-left:none;"><?=$row->Name?></td>
                                            <td align="right"><?=$row->LoadedQty?></td>
                                            <td><?=$row->EnrollmentDate?></td>
                                        </tr>
                                        <?php }}else{?>
                                        <tr class="listtr">
                                            <td colspan="9" style="color:red; text-align: center; border-left:none;">
                                                No result(s) found.
                                            </td>
                                        </tr>
                                        <?php }?>
                                    </table>
                                </div>
                                <br />
                                <div>
                                    <?php echo AddPagination(10, $countintransitreport->num_rows, $page);?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div style="text-align:center;">
                    <input type="button" class="btn" value="Back" onclick="location.href='?pageid=1'">
                    <input type="button" class="btn" value="Print" name="btnPrint">
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