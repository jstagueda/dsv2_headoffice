<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />
<script type="text/javascript" src="js/popinbox.js"></script>

<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<script src="js/jxPagingRecruitersReport.js" language="javascript" type="text/javascript"></script>

<?php
    //setExpires(0);
    include IN_PATH.DS."scRecruitersReport.php";
?>

<script type="text/javascript">
//date picker
$(function(){
    $("#dateend, #datestart").datepicker({
        changeMonth: true,
        changeYear: true
    });
});

function trim(s){
    var l=0; var r=s.length -1;
    while(l < s.length && s[l] == ' '){l++;}
	
    while(r > l && s[r] == ' '){r-=1;}
    return s.substring(l, r+1);
}

</script>

<style type="text/css">
.style1 {
color: #FF0000;
font-weight: bold;
}
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
                                <a href="javascript:void(0);" onclick="return leavepage(71);" class="txtblueboldlink">Leave Page</a>
                                |
                                <a class="txtblueboldlink" href="index.php?pageid=71">Sales Force Management Main</a>
                            </td>
		    	</tr>
                    </table>
		</td>
            </tr>
	</table>
    <br>
        
    <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
            <td class="txtgreenbold13">Recruiters Report</td>
            <td>&nbsp;</td>
  	</tr>
    </table>
    <br />
    <form name="frmRecruitersReport" method="post">
    	<div style="width:95%; margin:0 auto;">
                <div style="float:left; width:550px;">
                    <div class="tbl-head-content-left tbl-float-inherit"></div>
                    <div class="tbl-head-content-center tbl-float-inherit" style="width: 540px;">
                        <span>Action</span>
                    </div>
                    <div class="tbl-head-content-right tbl-float-inherit"></div>
                    <div class="tbl-clear"></div>
                    
                    <table width="100%"  border="0" style="border-top:none;" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
                        <tr>
                            <td width="30%" height="20" align="right"></td>
                            <td width="3%" height="20"></td>
                            <td height="20" align="left"></td>   
			</tr>
                        <tr>
                            <td align="right"><strong>Branch</strong></td>
                            <td width="3%;" align="center">:</td>
                            <td>
                                <input type="text" id="branchName" name="branchName" value="" class="txtfield">
								<input type="hidden" value="0" name="branch">
                            </td>
                        </tr>
						<tr>
                            <td align="right"><strong>From Date</strong></td>
                            <td width="3%;" align="center">:</td>
                            <td>
                                <input type="text" id="datestart" name="datestart" value="<?=date("m/d/Y")?>" class="txtfield">
                                <i>(e.g. MM/DD/YYYY)</i>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><strong>To Date</strong></td>
                            <td width="3%;" align="center">:</td>
                            <td>
                                <input type="text" id="dateend" name="dateend" value="<?=date("m/d/Y")?>" class="txtfield">
                                <i>(e.g. MM/DD/YYYY)</i>
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><strong>Sales Force Level</strong></td>
                            <td align="center" height="20">:</td>
                            <td align="left">
                                <select name="saleforcelevel" class="txtfield">
                                    <option value="0">[SELECT]</option>
                                    <?php 
                                    $sfmlevel = sfmlevel($database);
                                    if(count($sfmlevel['id']) > 0){
                                        for($x = 0; $x < count($sfmlevel['id']); $x++){
                                            echo "<option value='".$sfmlevel['id'][$x]."'>";
                                            echo $sfmlevel['code'][$x];
                                            echo "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
			</tr>
                        <tr>
                            <td align="right"><strong>SF Account Range</strong></td>
                            <td align="center" height="20">:</td>
                            <td align="left">
                                <input name="sfaccountfrom" class="txtfield" value="">
                                <input name="sfaccountfromHidden" class="txtfield" value="0" type="hidden">
                                -
                                <input name="sfaccountto" class="txtfield" value="">
                                <input name="sfaccounttoHidden" class="txtfield" value="0" type="hidden">
                            </td>
			</tr>
                        <tr>
                            <td height="20" align="right"></td>
                            <td height="20"></td>
                            <td height="20" align="left">
                                <input type="hidden" value="" name="action">
                                <input type="hidden" value="1" name="page">
                                <input name="btnSubmit" type="button" class="btn" value="Submit" onclick="return showPage(1);" />
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

                <div style="width:100%; min-height:300px;">
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
                            <td colspan="3">
                                <div class="pgLoading">
                                    <table class="tablelisttable" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #FF00A6; border-top:none;">
                                        <tr class="tablelisttr">
                                            <td>Recruiter's Code</td>
                                            <td>Recruiter's Name</td>
                                            <td>Recruit's Code</td>
                                            <td>Recruit's Name</td>
                                            <td>Date Registered</td>
                                            <td>First PO(CSP less CPI)</td>
                                            <td>Kit Availed</td>
                                        </tr>
                                        <tr class="listtr">
                                            <td colspan="7" align="center">No result(s) found.</td>
                                        </tr>
                                    </table>
                                </div>
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
            <input name="bntBack" type="button" class="btn" value="Back" onclick="location.href='?pageid=71'"/>
            <input name="btnPrint" type="button" class="btn" value="Print"/>
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