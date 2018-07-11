<?php
	/*
		@author:Gino C. leabres...
		@date: 9/13/2013...
		@explanation: generate report Contractor's fee and Growth bonus...
	*/
?>
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery.ContractorsfeeandGrowthBonus.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?php  include("sfpm_left_nav.php"); ?>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top" style="min-height: 610px; display: block;">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Contractor's fee and Growth Bonus</span></td>
                </tr>
                <tr>
                    <td>
                        <div style="width:100%;">
                            <!-- FORM STARTS HERE -->
                            <div class="tbl-content-div">
                                <div class="tbl-head-content-left tbl-float-inherit"></div>
                                <div class="tbl-head-content-center tbl-float-inherit" style="width: 468px;"><span>Actions</span></div>
                                <div class="tbl-head-content-right tbl-float-inherit"></div>
                                <div class="tbl-clear"></div>
                                <div class="tbl-mid-content tbl-float-inherit" style="width: 454px;">
                                    <form name="frmContractorsfeeandGrowthBonus" method="post" action="">
                                        <table width="100%" border="0" align="left" cellpadding="0" cellspacing="3">
                                            <tr>
                                                <td width="30%" align="right"><strong>Campaign</strong></td>
                                                <td width="3%" align="center">:</td>
                                                <td align="left">
                                                    <input id = "campaign_from" name = "campaign_from" class="txtfield">

                                                </td>
                                            </tr>
                                              <tr>
                                                <td width="30%" align="right"><strong>IBD From</strong></td>
                                                <td width="3%" align="center">:</td>
                                                <td align="left">
                                                    <input name="branchfromList" class="txtfield">
                                                    <input name="branchfrom" class="txtfield" type="hidden" value="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="30%" align="right"><strong>IBD To</strong></td>
                                                <td width="3%" align="center">:</td>
                                                <td align="left">
                                                    <input name="branchtoList" class ='txtfield'>
                                                    <input name="branchto" class ='txtfield' type="hidden" value="0">
                                                </td>
                                            </tr>
											<tr>
                                                <td colspan='2' align="right">&nbsp;</td>
                                                <td align="left">
                                                    <input type="submit" name="generate_list" id = "generate_list" class="btn" value = "Generate List">
                                                    <input style="display:none;" id = "btn-for-print" class = "btn" type = "submit" value = "Print as Excel">
													<input type="hidden" name="page" id = "page" value  = "1">
                                                </td>
                                            </tr>
                                        </table>
                                    </form>
                                </div>
                            </div>

                            <div style="clear:both;">&nbsp;</div>
                            <div style="clear:both;">&nbsp;</div>

                            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" style="margin-left: 15px; float:left;">
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
                            </table>
                            <div style="clear:both;"></div>
                            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2" style="margin-left: 15px; float:left;">
                                <td valign="top">
                                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td valign="top">
                                                <style>
                                                    .trheader td{border:1px solid #FFA3E0; padding:5px; font-weight:bold; background:#FFDEF0;}
                                                    .trlist td{border:1px solid #FFA3E0; padding:5px;}
                                                </style>
												<div id = "indicator" class="bordergreen" align="center" style="display:none;">
													<img src="images/sync_loader.gif">please wait....</img>
												</div>
                                                <div id="pgContent">
                                                    <table width='100%' cellpadding='0' border=0 cellspacing='0' class="bordergreen">
                                                        <tr class="trheader">
                                                            <td align='center'>IBD CODE</td>
                                                            <td align='center'>IBD NAME</td>
                                                            <td align='center'>CAMPAIGN</td>
                                                            <td align='center'>DGS (CFT & NCFT)</td>
                                                            <td align='center'>CPI</td>
                                                            <td align='center'>TOTAL</td>
                                                            <td align='center'>CF RATE</td>
                                                            <td align='center'>DGS (LY)</td>
                                                            <td align='center'>INCREASED/ (DECREASED) IN DGS</td>
                                                            <td align='center'>GB RATE</td>
                                                            <td align='center'>GROWTH BONUS</td>
                                                            <td align='center'>TOTAL EARNINGS</td>
                                                            <td align='center'>VAT</td>
                                                            <td align='center'>W/TAX</td>
                                                            <td align='center'>EARNINGS AFTER TAX</td>
                                                        </tr>
                                                        <tr class="trlist">
                                                            <td colspan="15" align="center" style="color:red;">No result found.</td>
                                                        </tr>
                                                    </table>
                                                </div>
												<div id='pagination'>
												  <!-- pagination here -->
                                                 </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>

                        <div class="tbl-clear clear-small"></div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>