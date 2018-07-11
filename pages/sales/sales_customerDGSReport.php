<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />
<script type="text/javascript" src="js/popinbox.js"></script>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script src="js/jxcustomerDGSReport.js" language="javascript" type="text/javascript"></script>
<?php
	//setExpires(0);
	//include IN_PATH.DS."sccustomerDGSReport.php";
?>

<script type="text/javascript">


function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	l++; }
	
	while(r > l && s[r] == ' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}
</script>
<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
-->
</style>

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
                                    <a href="javascript:void(0);" onclick="return leavepage(203);" class="txtblueboldlink">Leave Page</a>                      |
                                    <a class="txtblueboldlink" href="index.php?pageid=203">Branch Performance Monitoring</a></td>
                                </td>
		    	</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
        
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td class="txtgreenbold13">Customer DGS Report </td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
                
        <form name="frmValuationReport" method="post" >
            
        <div style="width:95%; margin:0 auto;">
            <div style="float:left; width:500px;">
                <div class="tbl-head-content-left tbl-float-inherit"></div>
                <div class="tbl-head-content-center tbl-float-inherit" style="width: 490px;">
                    <span>Action</span>
                </div>
                <div class="tbl-head-content-right tbl-float-inherit"></div>
                <div class="tbl-clear"></div>           
		<table width="100%"  border="0" align="center" cellpadding="0" style="border-top:none;" cellspacing="0">
                    <tr>
                        <td>
                            <table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
								<tr>
                                    <td width="25%" height="20" align="right"></td>
                                    <td width="3%" height="20"></td>
                                    <td width="75%" height="20" align="left"></td>
								</tr>
								<tr>
                                    <td height="20" align="right"><strong>Branch</strong></td>
                                    <td height="20" align="center">:</td>
                                    <td height="20" align="left">
                                        <input type="text" name="branchName" value="" class="txtfield">
										<input type="hidden" name="branch" value="0">
                                    </td>
                                </tr>
								<tr>
                                    <td height="20" align="right"><strong>Sales Force Level</strong></td>
                                    <td height="20" align="center">:</td>
                                    <td height="20" align="left">
                                        <select name="salesforcelevel" class="txtfield">
                                            <option value="0" >[SELECT]</option>
                                            <?php 
                                                $salesforcelevel = $database->execute("SELECT * FROM sfm_level ORDER BY codeID");
                                                if($salesforcelevel->num_rows){
                                                    while($res = $salesforcelevel->fetch_object()){
                                                        
                                                        echo "<option value='".$res->codeID."'>".$res->desc_code." - ".$res->description."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>	
								<tr>
                                    <td align="right"><strong>Customer Range</strong></td>
                                    <td height="20" align="center">:</td>
                                    <td align="left">
                                        <input name="salesforcefrom" class="txtfield">
                                        <input name="salesforcefromHidden" class="txtfield" type="hidden">
                                        -
                                        <input name="salesforceto" class="txtfield">
                                        <input name="salesforcetoHidden" class="txtfield" type="hidden">
                                    </td>
				</tr>
                                <tr>
                                    <td align="right"><strong>Campaign</strong></td>
                                    <td height="20" align="center">:</td>
                                    <td align="left">
                                        <input name="campaign" class="txtfield">
                                    </td>
				</tr>
                                <tr>
                                    <td align="right"><strong>Sort By</strong></td>
                                    <td height="20" align="center">:</td>
                                    <td align="left">
                                        <?php 
                                            $sortby = array("Customer Code", "DGS Amount", "Paid-on Time", "Collection%");
                                        ?>
                                        <select name="sortby" class="txtfield">
                                            <option value="">SELECT</option>
                                            <?php foreach($sortby as $val){?>
                                            <option value="<?=$val?>"><?=$val?></option>
                                            <?php }?>
                                        </select>
                                    </td>
				</tr>
                                <tr>
                                    <td align="right"><strong>Top Nth</strong></td>
                                    <td height="20" align="center">:</td>
                                    <td align="left">
                                        <?php 
                                            $topnth = array(5, 10, 20, 50);
                                        ?>
                                        <select name="topnth" class="txtfield">
                                            <option value="0">SELECT</option>
                                            <?php foreach($topnth as $val){?>
                                            <option value="<?=$val?>"><?=$val?></option>
                                            <?php }?>
                                        </select>
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
                        </td>
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
				<td class="txtredbold">&nbsp;</td>
               	<td>&nbsp;</td>
			</tr>
          	</table>
      	</td>
      	<td class="tabmin3">&nbsp;</td>
	</tr>
  	</table>
        <style>
            .trheader td{
                padding: 5px;
                text-align: center;
                font-weight: bold;
                background: #FFDEF0;
                border: 1px solid #FFA3E0;
            }
            .trlist td{padding:5px; border: 1px solid #FFA3E0;}
        </style>
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0" id="tbl2">
            <tr>
  		<td valign="top" class="bgF9F8F7">
                    <div class="tablelist" style="min-height: 255px;">
                        <table border="0" width="100%" cellpadding="0" cellspacing="0" class="bordergreen">
                            <tr class="trheader">
                                <td>Customer Code</td>
                                <td>Customer Name</td>
                                <td>Campaign</td>
                                <td>Campaign-to-Date DGS Amount</td>
                                <td>Paid-on-Time DGS Amount</td>
                                <td>No. of Defaults</td>
                                <td>Collection%</td>
                                <td>Year-to-Date DGS Amount</td>
                                <td>Credit Term</td>
                            </tr>
                            <tr class="trlist">
                                <td colspan="9" align="center">No query found.</td>
                            </tr>
                        </table>
                    </div>
                    <div style="text-align:center;">
                        <input type="button" class="btn" name="btnPrint" value="Print">
                        <input type="button" class="btn" name="btnBack" onclick="location.href='?pageid=18'" value="Back">
                    </div>
		</td>
            </tr>
  	</table>
        </form>
    </td>
</tr>
</table>

<br />

<div id="dialog-message-with-button" style='display:none;'>
    <p></p>
</div>
<div id="dialog-message" style='display:none;'>
    <p></p>
</div>
<div id="popinleave" style='display:none;'>
    <p></p>
</div>