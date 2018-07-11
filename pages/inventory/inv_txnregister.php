
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<script src="js/jxPagingInvTxnRegister.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript" src="js/popinbox.js"></script>

<style>

.ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
.ui-widget-overlay{height:130%;}

</style>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<?PHP 
	include IN_PATH.DS."scInventoryTxnRegister.php";
?>


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
<br>

<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
      <td class="txtgreenbold13">Transaction by Order / Item Report</td>
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
        <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
                <td>
                    <form name="frmStockLog" method="post" action="">
                        <table width="100%" style="border-top:none;" border="0" align="left" cellpadding="0" cellspacing="3"  class="bordersolo">
                            <tr><td colspan="3">&nbsp;</td></tr>
							<tr>
                                <td width="25%" align="right"><strong>Branch</strong></td>
                                <td width="3%" align="center">:</td>
                                <td align="left">
									<input type="text" name="branchName" value="" class="txtfield">
									<input type="hidden" value="0" name="branch">
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" align="right"><strong>Movement Type</strong></td>
                                <td width="3%" align="center">:</td>
                                <td align="left">
                                    <select name="cboWarehouse" class="txtfield">
                                        <option value="0" selected>ALL MOVEMENT TYPE</option>
                                            <?php	
                                                if($rs_movementType->num_rows){
                                                    while ($row = $rs_movementType->fetch_object()){
                                                        ($_GET['mtid']== $row->ID) ? $sel = "selected" : $sel = "";
                                                        echo "<option value='".$row->ID."' $sel>".$row->Code." - ".$row->Name."</option>";
                                                    }
                                                    $rs_movementType->close();
                                                }
                                            ?>
                                    </select>
                                </td>
                            </tr>		 
                            <tr>
                                <td width="25%" align="right"><strong>From Date</strong></td>
                                <td width="3%" align="center">:</td>
                                <td align="left">
                                    <input name="txtStartDates" type="text" class="txtfield" id="txtStartDates" size="20" readonly="yes" value="<?php  echo $fromDate; ?>">
                                    <i>(e.g. MM/DD/YYYY)</i>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" align="right"><strong>To Date</strong></td>
                                <td width="3%" align="center">:</td>
                                <td align="left">
                                    <input name="txtEndDates" type="text" class="txtfield" id="txtEndDates" size="20" readonly="yes" value="<?php echo $toDate; ?>">	        			
                                    <i>(e.g. MM/DD/YYYY)</i>
                                </td>
                            </tr>
                            <tr>
                                <td width="25%" align="right"><strong>Search</strong></td>
                                <td width="3%" align="center">:</td>
                                <td class="txt10">
                                    <input name="txtSearch" type="text" class="txtfield" id="txtSearch" value="<?php echo $search;?>" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center"></td>
                                <td>
                                    <input type="hidden" name="page" value="1">
                                    <input type="submit" name="btnSearch" class="btn" id="btnSearch" value="Submit" onclick="return showPage(1)" />
                                </td>
                            </tr>
                            <tr><td colspan="3">&nbsp;</td></tr>
                        </table>
                    </form>
                </td>
            </tr>
        </table>
    </div>
    
    <div style="clear:both;">&nbsp;</div>
    <div class="loader" style="text-align:center; font-weight: bold; margin-bottom: 10px; font-size:12px;">
        &nbsp;
    </div>
    
    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
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
    <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
        <tr>
            <td valign="top">
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td valign="top">
                            <style>
                                .trheader td{border:1px solid #FFA3E0; padding:5px; font-weight:bold; background:#FFDEF0;}
                                .trlist td{border:1px solid #FFA3E0; padding:5px;}
                            </style>
                            <div id="pgContent" style="min-height:270px;">
                                <table width='100%' cellpadding='0' border=0 cellspacing='0' class="bordergreen">
                                    <tr class="trheader">
                                        <td align='center' width='7%'>Transaction Date</td>
                                        <td align='center' width='12%'>Movement Type</td>
                                        <td align='center' width='7%'>Reference No.</td>
                                        <td align='center' width='5%'>Item Code</td>
                                        <td align='center' width='18%'>Item Description</td>
                                        <td align='center' width='5%'>Issuing Branch</td>                      	
                                        <td align='center' width='5%'>Location</td>
                                        <td align='center' width='5%'>Receiving Branch</td>
                                        <td align='center' width='5%'>Location</td>
                                        <td align='center' width='7%'>CSP</td>   
										<td align='center' width='7%'>Qty In</td>    
                                        <td align='center' width='7%'>Qty Out</td>   
                                        <td align='center' width='7%'>Total </td>   
                                    </tr>
                                    <tr class="trlist">
                                        <td colspan="13" align="center" style="color:red;">No result found.</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
	</tr>
    </table>
</div>
	
            <table width="95%"  border="0" align="center">
                <tr>
                    <td height="20" align="center">
                        <a class="txtnavgreenlink" href="index.php?pageid=1">
                            <input name="Button" type="button" class="btn" value="Back">
                        </a>
                        <!--<input name="input" type="button" class="btn" value="Print" onclick="openPopUp(<?php echo $fromDate.','.$toDate.','.$wareid;?>)"/>-->
                        <input name="input" type="button" class="btn" value="Print" onclick="openPopUp()"/>
                    </td>
                </tr>
            </table>	 
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