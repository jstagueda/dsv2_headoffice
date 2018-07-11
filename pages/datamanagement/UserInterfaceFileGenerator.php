<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script src="js/jquery-1.9.1.min.js"></script>
<script src="js/jquery-ui-1.10.0.custom.min.js"></script>
<script src="js/UserInterfaceFileGenerator.js"></script>
<script src="js/popinbox.js"></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="200" valign="top" class="bgF4F4F6">
            <?PHP include("nav.php");?>
            <br>
        </td>
        <td class="divider">&nbsp;</td>
        <td valign="top">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Data Management </span></td>
                </tr>
            </table>
            <br />

            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td valign="top">
                        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td class="txtgreenbold13">SID - EOD & EOM File Generator</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                        <br />

                        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="1">
                            <tr>
                                <td width="50%" valign="top">
                                    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td class="tabmin">&nbsp;</td>
                                            <td class="tabmin2"><span class="txtredbold">Action</span></td>
                                            <td class="tabmin3">&nbsp;</td>
                                        </tr>
                                    </table>
                                    <table width="100%" style="border-top:none;" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
                                        <tr>
                                            <td>
                                                <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
                                                    <tr>
                                                        <td width="35%">&nbsp;</td>
                                                        <td width="5%" align="right">&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                    </tr>
													<tr>
                                                        <td align="right" style="padding:5px;"><b>Date</b></td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <input type="text" value="<?=date('m/d/Y')?>" name="RunningDate" class="txtfield">
															<i>(e.g. MM/DD/YYYY)</i>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right" style="padding:5px;"><b>Generate EOD - SID File</b></td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <input type="button" value="Generate EOD File" name="eod" class="btn">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right" style="padding:5px;"><b>Generate EOM - SID File</b></td>
                                                        <td align="center">:</td>
                                                        <td>
                                                            <input type="button" value="Generate EOM File" name="eom" class="btn">
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
                                </td>
                                <td width="2%">&nbsp;</td>
                                <td width="60%" valign="top"></td>
                            </tr>
                            <tr>
                                <td colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <div style="width:60%;">
                                        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="tabmin">&nbsp;</td>
                                                <td class="tabmin2"><span class="txtredbold">Result</span></td>
                                                <td class="tabmin3">&nbsp;</td>
                                            </tr>
                                        </table>
                                        <table width="100%" style="border-top:none;" border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
                                            <tr>
                                                <td>
                                                    <style>
                                                        .uiinterface{
                                                            height: 350px;
                                                            overflow: auto;
                                                            padding: 10px;
                                                        }
                                                        .uiinterface p{padding: 5px 2px; margin:0}
                                                        .uiinterface p.titlefile{background:pink;}
                                                        .uiinterface p.titlefileheader{background:none;}
                                                    </style>
                                                    <div class="uiinterface"></div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div>&nbsp;</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
