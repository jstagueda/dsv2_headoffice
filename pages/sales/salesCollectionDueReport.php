<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />
<script type="text/javascript" src="js/popinbox.js"></script>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">

<script src="js/jxCollectionDueReport.js" language="javascript" type="text/javascript"></script>

<style type="text/css">

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

.tablelisttr td{padding:5px; text-align:center; font-weight: bold; border-left:1px solid #FFA3E0;}
.tablelisttr{background: #FFDEF0;}
.tablelisttable{width:100%;}
.listtr td{border-top:1px solid #FFA3E0; border-left:1px solid #FFA3E0; padding:5px;}
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
        
    <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
            <td class="txtgreenbold13">Collection Due Report</td>
            <td>&nbsp;</td>
  	</tr>
    </table>
    <br />
    <form name="frmNetworkReport" method="post">
    	<div style="width:95%; margin:0 auto;">
                <div style="float:left; width:510px;">
                    <div class="tbl-head-content-left tbl-float-inherit"></div>
                    <div class="tbl-head-content-center tbl-float-inherit" style="width: 500px;">
                        <span>Action</span>
                    </div>
                    <div class="tbl-head-content-right tbl-float-inherit"></div>
                    <div class="tbl-clear"></div>
                    
                    <table width="100%"  border="0" style="border-top:none;" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
                        <tr>
                            <td width="" height="20" align="right"></td>
                            <td width="3%" height="20"></td>
                            <td height="20" align="left"></td>
						</tr>
						<tr>
                            <td align="right"><strong>Branch</strong></td>
                            <td align="center">:</td>
                            <td>
                                <input type="text" value="" name="branch" class="txtfield">
								<input type="hidden" value="0" name="branchID">
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><strong>Due Date Range</strong></td>
                            <td align="center">:</td>
                            <td>
                                <input name="datefrom" value="<?=date("m/d/Y")?>" readonly="yes" autocomplete="no" class="txtfield">
                                -
                                <input name="dateto" value="<?=date("m/d/Y")?>" readonly="yes" autocomplete="no" class="txtfield">
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><strong>SF Level</strong></td>
                            <td align="center">:</td>
                            <td>
                                <select class="txtfield" name="sfmlevel">
                                    <option value="0">SELECT</option>
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
                            <td align="right"><strong>SFA Range</strong></td>
                            <td align="center">:</td>
                            <td>
                                <input name="customerfrom" class="txtfield">
                                <input name="customerfromHidden" class="txtfield" type="hidden" value="0">
                                -
                                <input name="customerto" class="txtfield">
                                <input name="customertoHidden" class="txtfield" type="hidden" value="0">
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><strong>Credit Account Type</strong></td>
                            <td align="center">:</td>
                            <td>
                                <select class="txtfield" name="accounttype">
                                    <option value="0">SELECT ALL</option>
                                    <?php
                                        $accounttype = $database->execute("SELECT * from tpi_gsutype");
                                        if($accounttype->num_rows){
                                            while($res = $accounttype->fetch_object()){
                                                echo "<option value='".$res->ID."'>".trim($res->Code)." - ".$res->Name."</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td height="20" align="center" colspan="3">
                                <br />
                                <input name="btnSubmit" type="button" class="btn" value="Submit" onclick="return showPage(1);" />
                                <input type="hidden" value="1" name="page">
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="3" height="15">&nbsp;</td>
                        </tr>
                    </table>
                </div>
            
	
                <div style="clear:both;">&nbsp;</div>
                <div class="loader" style="text-align:center; font-weight: bold; margin-bottom: 10px; font-size:12px;">
                    &nbsp;
                </div>

                <div style="width:100%; min-height:290px;">
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
                            <td colspan="3" class="loadpage">
                                <table class="tablelisttable" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #FF00A6; border-top:none;">
                                    <tr class="tablelisttr">
                                        <td>Account No.</td>
                                        <td>Account Name</td>
                                        <td>Credit Account Type</td>
                                        <td>Credit Term</td>
                                        <td>Invoice / Debit Memo</td>
                                        <td>Ref. No. / Document No.</td>
                                        <td>Total Amount Due</td>
										<td>Due Date</td>
                                        <td>Contact No.</td>
                                    </tr>
                                    <tr class="listtr">
                                        <td align="center" colspan="9">No result found.</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
        
        </div>
    </form>
    </td>
</tr>
</table>

<table width="95%"  border="0" align="center">
    <tr>
        <td height="20" align="center">    
            <input name="btnBack" type="button" class="btn" value="Back" onclick="location.href='?pageid=18'">
            <input name="btnPrint" type="button" class="btn" value="Print" onclick="return printreport();">
    	</td>
    </tr>
</table>

<br>
<div id="dialog-message-with-button" style='display:none;'>
    <p></p>
</div>
<div id="dialog-message" style='display:none;'>
    <p></p>
</div>
<div id="popinleave" style='display:none;'>
    <p></p>
</div>