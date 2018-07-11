<script type="text/javascript" src="js/ProvisionalReceipt.js"></script>
<script type="text/javascript" src="js/popinbox.js"></script>

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


.trheader td{border-right:1px solid #FFA3E0; padding:5px; background:#FFDEF0; text-align: center; font-weight: bold;}
.trlist td{padding:5px; border-right:1px solid #FFA3E0; border-top:1px solid #FFA3E0;}
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


            <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
                    <td class="txtgreenbold13">Provisional Receipt Register</td>
                    <td>&nbsp;</td>
				</tr>
            </table>
            <br />


            <form name="provisionalreceiptregisterform" method="post" >

            <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0" >
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
                                    <input name="Branch" class="txtfield" value="">
                                    <input name="BranchID" value="0" type="hidden">
                                </td>
							</tr>
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
                                    <input name="btnSubmit" type="button" class="btn" value="Submit" />
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

                <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
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
                <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
                    <tr>
                        <td valign="top" class="bgF9F8F7">
                            <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td valign="top">                                        
                                        <div id="pgContent" style="min-height: 265px;">
                                            <table width='100%' border='0' cellpadding='0' cellspacing='0' class="bordergreen">
                                                <tr class="trheader">
                                                    <td>PR Date</td>
                                                    <td>PR No.</td>
                                                    <td>Transaction No.</td>
                                                    <td>Customer Code</td>
                                                    <td>Customer Name</td>
                                                    <td>Check No.</td>
													<td>Check Date</td>
													<td>Amount</td>
													<td>Status</td>
                                                </tr>
                                                <tr class="trlist">
                                                    <td colspan='9' align="center"><span class='txtredsbold'>No record(s) to display.</span></td>
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