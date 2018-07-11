<!--Added by joebert-->
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/popinbox.js" type="text/javascript"></script>
<script language="javascript" src="js/synclogs.js" type="text/javascript"></script>

<style>

.ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
.ui-widget-overlay{height:130%;}


.tablelisttr td{padding:5px; text-align:center; font-weight: bold; border-left:1px solid #FFA3E0;}
.tablelisttr{background: #FFDEF0;}
.tablelisttable{width:100%;}
.listtr td{border-top:1px solid #FFA3E0; border-left:1px solid #FFA3E0; padding:5px;}
</style>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="200" valign="top" class="bgF4F4F6">
        <?PHP
            include("nav.php");
        ?>
	<br></td>
	<td class="divider">&nbsp;</td>
	<td valign="top" style="display:block; min-height: 610px;">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management</span></td>
		</tr>
		</table>
    	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
 		<tr>
			<td valign="top">
  				<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  				<tr>
					<td class="txtgreenbold13">Sync Logs</td>
					<td>&nbsp;</td>
  				</tr>
				</table>
				<br />
				<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
                                    <tr>
					<td width="33%" valign="top">
                                            <form name="formSyncLogs" method="post" action="">
                                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td class="tabmin">&nbsp;</td>
                                                    <td class="tabmin2"><span class="txtredbold">Action</span></td>
                                                    <td class="tabmin3">&nbsp;</td>
                                                </tr>
                                            </table>
                                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
                                                <tr>
                                                    <td>
							<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
							<tr>
					  			<td></td>
					  			<td>&nbsp;</td>
					  			<td>&nbsp;</td>
							</tr>
                                                        <tr>
					  			<td align="right">Branch</td>
                                                                <td align="center" width="5%">:</td>
                                                                <td>
                                                                    <input type="text" value="" name="branch" class="txtfield">
                                                                    <input type="hidden" value="0" name="branchHidden" class="txtfield">
                                                                </td>
							</tr>
							<tr>
					  			<td align="right">Date</td>
                                                                <td align="center" width="5%">:</td>
                                                                <td>
                                                                    <input type="text" readonly="yes" value="<?=date("m/d/Y")?>" name="RunningDate" class="txtfield">
                                                                </td>
							</tr>
                                                        <tr>
					  			<td align="right" width="30%"></td>
                                                                <td align="center" width="5%"></td>
                                                                <td>
                                                                    <input type="submit" value="Submit" class="btn" name="submitForm">
                                                                </td>
							</tr>
							<tr>
					  			<td>&nbsp;</td>
					  			<td align="right">&nbsp;</td>
					  			<td align="right">&nbsp;</td>
							</tr>
							</table>
                                                    </td>
                                                </tr>
                                            </table>
                                            </form>

                                        </td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="loader" align="center" style="font-weight:bold;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">

                                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                                <tr>
                                                    <td class="tabmin">&nbsp;</td>
                                                    <td class="tabmin2"><span class="txtredbold">Result</span></td>
                                                    <td class="tabmin3">&nbsp;</td>
                                                </tr>
                                            </table>
                                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="bordersolo" style="border-top:none;">
                                                <tr>
                                                    <td>
														<div class="syncLogs">

                                                            <p align="center">Type your statement here.</p>

                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>

                                        </td>
                                    </tr>
                                </table>

                        </td>

                </tr>

        </table>

        </td>

</tr>

</table>


<!--Added by joebert-->
<div id="dialog-message" style='display:none;'>
    <p></p>
</div>
<div id="dialog-message-with-button" style='display:none;'>
    <p></p>
</div>
<!--end-->