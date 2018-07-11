<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />
<script type="text/javascript" src="js/popinbox.js"></script>
<script src="js/jxOfficialReceiptRegister.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<style type="text/css">
.style1 {color: #FF0000}

div.autocomplete {
  position:absolute;
  /*width:300px;*/
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px;}
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px solid #888;
  padding:2px;
  /*height:20px;*/
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
  cursor:pointer;
}

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
                                    <a href="javascript:void(0);" onclick="return leavepage(18);" class="txtblueboldlink">Leave Page</a>
                                    |
                                    <a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <br>


            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
  		<tr>
                    <td class="txtgreenbold13">Collection Receipt Register</td>
                    <td>&nbsp;</td>
  		</tr>
            </table>
            <br />


            <form name="officialreceiptregisterform" method="post" >

            <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" >
                <tr>
                    <td>
                        <table width="600px"  border="0" align="left" cellpadding="0" cellspacing="0">
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
                        <div style="clear:both;"></div>
                        <table width="600px"  border="0" align="left" cellpadding="0" cellspacing="1" style="border-top:none;" class="bordersolo">
                            <tr>
                                <td height="20" width="30%" align="right"></td>
                                <td height="20"></td>
                                <td height="20" align="left"></td>
                            </tr>
							<tr>
                                <td align="right"><strong>Branch</strong></td>
                                <td width="3%" height="20" align="center">:</td>
								<td align="left">
                                    <input type="text" value="" name="branchName" class="txtfield">
									<input type="hidden" value="0" name="branch" class="txtfield">
                                </td>
							</tr>
                            <tr>
                            <tr>
                                <td align="right"><strong>Date Range</strong></td>
                                <td width="3%" height="20" align="center">:</td>
				<td align="left">
                                    <input name="datefrom" class="txtfield" readonly="yes" value="<?=date("m/d/Y")?>">
                                    -
                                    <input name="dateto" class="txtfield" readonly="yes" value="<?=date("m/d/Y")?>">
                                </td>
			    </tr>
                            <tr>
                                <td align="right"><strong>SFM Level</strong></td>
                                <td width="3%" height="20" align="center">:</td>
				<td align="left">
                                    <select name="sfmlevel" class="txtfield">
                                        <option value="0">SELECT</option>
                                    <?php
                                        $sfmlevel = $database->execute("SELECT * FROM sfm_level ORDER BY codeID");
                                        if($sfmlevel->num_rows){
                                            while($res = $sfmlevel->fetch_object()){
                                                echo "<option value='".$res->codeID."'>".$res->desc_code." - ".$res->description."</option>";
                                            }
                                        }
                                    ?>
                                    </select>
                                </td>
			    </tr>
                            <tr>
                                <td align="right"><strong>Sales Force Account</strong></td>
                                <td width="3%" height="20" align="center">:</td>
				<td align="left">
                                    <input name="sfaccountfrom" class="txtfield">
                                    <input name="sfaccountfromHidden" type="hidden" value="0">
                                    -
                                    <input name="sfaccountto" class="txtfield">
                                    <input name="sfaccounttoHidden" type="hidden" value="0">
                                </td>
			    </tr>
                            <tr>
                                <td colspan="3" height="20" align="center">
                                    <br />
                                    <input type="hidden" name="page" value="1">
                                    <input name="btnSubmit" type="button" class="btn" value="Submit" onclick="return showPage(1);" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" height="15">&nbsp;</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <div style="clear:both;">&nbsp;</div>
            <div class="loader" style="text-align:center; font-weight: bold; margin-bottom: 10px; font-size:12px;">
                &nbsp;
            </div>

                <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
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
                <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
                    <tr>
                        <td valign="top" class="bgF9F8F7">
                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td valign="top">
                                        <style>
                                            .trheader td{border:1px solid #FFA3E0; padding:5px; background:#FFDEF0; text-align: center; font-weight: bold;}
                                            .trlist td{padding:5px; border:1px solid #FFA3E0;}
                                        </style>
                                        <div id="pgContent" style="min-height: 330px;">
                                            <table width='100%' border='0' cellpadding='0' cellspacing='0' class="bordergreen">
                                                <tr class="trheader">
                                                    <td>Collection Receipt Date</td>
                                                    <td>Collection Receipt No.</td>
                                                    <td>Transaction No.</td>
                                                    <td>Customer Code</td>
                                                    <td>Customer Name</td>
                                                    <td>Cash</td>
                                                    <td>Check</td>
                                                    <td>Deposit Slip</td>
                                                    <td>Cancelled</td>
                                                    <td>Penalty Amount</td>
                                                    <td>Net Total Amount</td>
                                                    <td>Offsetting</td>
                                                    <td>Total Applied Amount</td>
                                                    <td>Total Unapplied Amount</td>
                                                </tr>
                                                <tr class="trlist">
                                                    <td colspan='14' align="center"><span class='txtredsbold'>No record(s) to display.</span></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div style="text-align:center;">
                                            <input type="button" class="btn" value="Print" name="btnPrint">
                                            <input type="button" class="btn" value="Back" name="btnBack" onclick="location.href='?pageid=18'">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </form>
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