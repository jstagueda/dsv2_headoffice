<?PHP 
	include IN_PATH.DS."scBirBackEnd.php";
?>

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script src="js/jxPagingbirbackend.js" language="javascript" type="text/javascript"></script>

<style>
.style1 {font-weight: bold; color: #FF0000}
.style2 {color: #FF0000}


div.autocomplete {

  position:absolute;
  /*width:300px;*/
  /*border:1px solid #888;
  margin:0px;
  padding:0px;*/
}

div.autocomplete span { position:relative; top:2px; }
 
div.autocomplete ul {
    max-height: 150px;
    overflow-x: hidden;
    overflow-y: auto;
    width: 319px;
    border: 1px solid #FF00A6;
    color: #312E25;
    list-style-type:none;
    margin:0px;
    padding:0px;
    border-radius:6px;
    background-color:white;
    background: #F5F3E5;
    /* prevent horizontal scrollbar */
    overflow-x: hidden;
  /*font-family: Verdana, Arial, Helvetica, sans-serif;*/
  /*font-size: 10px;*/
}

div.autocomplete ul li.selected{ 
    background-color: #EB0089; 
    border:1px solid #c40370; 
    color:white; 
    font-weight:bold; 
    margin:3px; 
    border-radius:6px;
}

div.autocomplete ul li {
    line-height: 1.5;
    padding: 0.2em 0.4em;
    list-style-type:none;
    display:block;
    /*height:20px;*/
    font-family: Verdana, Arial, Helvetica, sans-serif;
    font-size: 10px;
    cursor:pointer;
}

.ui-dialog .ui-dialog-titlebar-close span{margin: -10px 0 0 -10px;}
.ui-widget-overlay{height:130%;}

</style>

<div id="dialog-message" style='display:none;'>
    <p></p>
</div>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    <tr>
		      <td width="70%" align="right">&nbsp;
                          <a href="javascript:void(0);" onclick="return leavepage();" class="txtblueboldlink">Leave Page</a>
                            |
                          <a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
		    </tr>
		</table>
		</td>
	</tr>
</table>
      <br>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">BIR Back End Report</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />

<div style="width:95%; margin:0 auto;">
    <div style="float:Left;">
        <div class="tbl-head-content-left tbl-float-inherit"></div>
        <div class="tbl-head-content-center tbl-float-inherit" style="width: 400px;">
            <span>Action</span>
        </div>
        <div class="tbl-head-content-right tbl-float-inherit"></div>
        <div class="tbl-clear"></div>
        <div class="tbl-mid-content tbl-float-inherit" style="width: 386px;">
            <style>
                .separator{float:left; text-align: center; width: 3%;}
            </style>
            <form name="frmbirbackend" method="post" action="index.php?pageid=107">
				
				<div style="float:left; text-align: right;" class="tbl-lbl tbl-float-inherit">Branch</div>
                <div class="separator">:</div>
                <div class="tbl-input tbl-float-inherit">
                    <input name="branchName" class="txtfield" value="">
                    <input type="hidden" name="branch" value="0">
                </div>
                <div class="tbl-clear clear-xsmall"></div>
			
                <div style="float:left; text-align: right;" class="tbl-lbl tbl-float-inherit">From Date</div>
                <div class="separator">:</div>
                <div class="tbl-input tbl-float-inherit">
                    <input name="txtStartDate" class="txtfield" id="txtStartDate" readonly="yes" value="<?=date("m/d/Y")?>">
                    <i>(e.g. MM/DD/YYYY)</i>	
                </div>
                <div class="tbl-clear clear-xsmall"></div>
                                
                <div style="float:left; text-align: right;" class="tbl-lbl tbl-float-inherit">To Date</div>
                <div class="separator">:</div>
                <div class="tbl-input tbl-float-inherit">
                    <input value="<?=date("m/d/Y")?>" readonly="yes" id="txtEndDate" class="txtfield" name="txtEndDate">
                    <i>(e.g. MM/DD/YYYY)</i>
                </div>
                
                <div class="tbl-clear clear-xsmall"></div>
                <div style="float:left;" class="tbl-lbl tbl-float-inherit">&nbsp;</div>
                <div class="separator">&nbsp;</div>
                <div class="tbl-input tbl-float-inherit">
                    <input type="hidden" value="1" name="page">
                    <input type="submit" value="Search" class="btn" name="btnSearch" onclick="return showPage(1);">
                </div>
                <div class="tbl-clear clear-xsmall"></div>
            </form>
        </div>
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
                                .trheader td{border:1px solid #FFA3E0; padding:5px; font-weight:bold; background:#FFDEF0; text-align:center;}
                                .trlist td{border:1px solid #FFA3E0; padding:5px;}
                            </style>
                            <div id="pgContent" style="min-height:315px;">
                                <table width='100%' cellpadding='0' border=0 cellspacing='0' class="bordergreen">
                                    <tr class="trheader">
                                        <td>Date</td>
                                        <td>Beginning Invoice</td>
                                        <td>Ending Invoice</td>
										<td>Beginning Bal</td>
                                        <td>Ending Bal</td>                                        
                                        <td>Total Sales</td>
                                        <td>VAT Sales</td>
                                        <td>VAT Amount</td>
                                        <td>Non-VAT Sales</td>
                                        <td>Zero Rated</td>
                                        <td>Discount Prev Purchase</td>
                                        <td>Returns</td>
                                        <td>Voids</td>
                                        <td>Overrun/Overflow</td>
                                    </tr>
                                    <tr class="trlist">
                                        <td colspan="14" align="center" style="color:red;">No result found.</td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table width="95%"  border="0" align="center">
        <tr>
            <td height="20" align="center">
                <a class="txtnavgreenlink" href="index.php?pageid=18"><input name="Button" type="button" class="btn" value="Back"></a>
                <input name="input" type="button" class="btn" value="Print" onclick="openPopUp();"/>
            </td>
        </tr>
    </table>


        </td>
    <tr>
</table>
<br>