<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script src="js/jxPagingIBMCumPerReport.js" language="javascript" type="text/javascript"></script>

<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
</style>

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
	<td valign="top" style="min-height:575px; display:block;">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    	<tr>
		      		<td width="70%" align="right">&nbsp;
                                    <a href="javascript:void(0);" onclick="return leavepage();" class="txtblueboldlink">Leave Page</a>
                                    |
                                    <a class="txtblueboldlink" href="index.php?pageid=204">Sales Force Performance Monitoring</a></td>
		    	</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
            <tr>
    		<td class="txtgreenbold13">IBM Cumulative Performance Report</td>
    		<td>&nbsp;</td>
            </tr>
	</table>
		<br />
		<form name="frmIBMCumPerReport" method="post">
<div style="width:95%; margin:0 auto;">
    <div style="float:left; width:500px;">
        <div class="tbl-head-content-left tbl-float-inherit"></div>
        <div class="tbl-head-content-center tbl-float-inherit" style="width: 490px;">
            <span>Action</span>
        </div>
        <div class="tbl-head-content-right tbl-float-inherit"></div>
        <div class="tbl-clear"></div>
        <table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
            <tr>
                <td width="25%" height="20" align="right"></td>
                <td width="3%" height="20"></td>
                <td width="70%" height="20" align="left"></td>   
            </tr>
			 <tr>
                <td align="right"><strong>Branch</strong></td>
                <td align="center" height="20">:</td>
                <td align="left">
                    <input name="branchName" class="txtfield" value="">
					<input name="branch" type="hidden" value="0">
                </td>
            </tr>
            <tr>
                <td align="right"><strong>Campaign</strong></td>
                <td align="center" height="20">:</td>
                <td align="left">
                    <input name="campaign" class="txtfield" value="">
                </td>
            </tr>
            <tr>
                <td align="right"><strong>IBM Range</strong></td>
                <td align="center" height="20">:</td>
                <td align="left">
                    <input name="cboSIBMR" class="txtfield" value="">
                    <input name="cboSIBMRHidden" class="txtfield" value="0" type="hidden">
                    -	   
                    <input name="cboEIBMR" class="txtfield" value="">
                    <input name="cboEIBMRHidden" class="txtfield" value="0" type="hidden">
                </td>
            </tr>
            <tr>
                <td height="20" align="right"></td>
                <td height="20"></td>
                <td height="20" align="left">
                    <input type="hidden" name="page" value="1">
                    <input name="btnSubmit" type="button" class="btn" value="Submit" onclick="return showPage(1);" />
                </td>
            </tr>
            <tr>
                <td colspan="3" height="15">&nbsp;</td>
            </tr>
        </table>
    </div>
</div>

        <div style="clear:both;">&nbsp;</div>        
	<div class="loader" style="display:block; text-align:center; font-weight: bold; margin-bottom: 10px; font-size:12px;">
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
                                    .bdiv_r{border-bottom:2px solid #FFA3E0; padding:5px; background:#FFDEF0;}
                                    .trheader td{font-weight:bold; text-align: center;}
                                    .borderBR{padding:3px;}
                                </style>
                                <div id="pgContent" style="min-height:242px;">
                                    <table width='100%' border='0' cellpadding='0' cellspacing='0' style="border:1px solid #FF00A6; border-top:none;">
                                        <tr class="trheader">
                                            <td class="bdiv_r">Customer Code</td>
                                            <td class="bdiv_r">Customer Name</td>
                                            <td class="bdiv_r">Campaign</td>
                                            <td class="bdiv_r">Total DGS</td>
                                            <td class="bdiv_r">On-Time BCR</td>
                                            <td class="bdiv_r">Total Invoice Amount</td>
                                            <td class="bdiv_r">Collection Due Balance</td>
                                            <td class="bdiv_r">Total Written-Off Amount</td>
                                            <td class="bdiv_r">Paid Up Invoices</td>
                                            <td class="bdiv_r">Cumulative BCR On-Time-Or-Not</td>
                                        </tr>
                                        <tr align='center'>
                                            <td class='borderBR' colspan='10' height='20' ><span class='txtredsbold'>No record(s) to display.</span></td>
                                        </tr>
                                    </table>
                                </div>                                    
                            </td>
	  		</tr>
                    </table>
		</td>
            </tr>
  	</table>
  	<br>	
	</form>
	</td>
</tr>
</table>

<table width="95%"  border="0" align="center">
	<tr>
            <td height="20" align="center">
                <input name="Button" type="button" class="btn" value="Back" onclick="location.href='index.php?pageid=71';" />
    		<input name="btnPrint" type="button" class="btn" value="Print"/>
            </td>
	</tr>
</table>
<br>