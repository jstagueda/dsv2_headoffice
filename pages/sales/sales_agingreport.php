<?php 
/*   
 * Created by joebert italia
 * September 3, 2013
*/

include(IN_PATH.DS.'scAgingReport.php'); 
?>

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.10.0.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css" />
<script src="js/popinbox.js" language="javascript" type="text/javascript"></script>
<script src="js/jxPagingAgingReport.js" language="javascript" type="text/javascript"></script>

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

.separator{width:3%; text-align: center; vertical-align: top;}
</style>

<script>
function leavepage(){
    
    $( "#dialog-message p" ).html("Do you want to leave this page?");
    $( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
        title: 'DSS Message',
        buttons: {
            "Yes"   :   function(){
                window.location.href = "index.php?pageid=18";
            },
            "No"    :   function(){$(this).dialog("close");}
        }
    });
    $( "#dialog-message" ).dialog( "open" );
    
}    
</script>

<div id="dialog-message" style='display:none;'>
    <p></p>
</div>

<form name="frmORRegister" method="post" action="">
  <input type="hidden" name="action" class="action" value="">
  <input type="hidden" name="page" class="page" value="1">
  <table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td valign="top">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td class="topnav">
              <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
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
            <td class="txtgreenbold13" colspan="2">IBM Overdue Aging Report</td>
          </tr>
        </table>
        <br />
        
        <div style="width:95%; margin:0 auto;">
            
            <div style="float:left; width:600px; margin-bottom:20px; clear:both;">
                <div class="tbl-head-content-left tbl-float-inherit"></div>
                <div class="tbl-head-content-center tbl-float-inherit" style="width: 590px;">
                    <span>Action</span>
                </div>
                <div class="tbl-head-content-right tbl-float-inherit"></div>
                <div class="tbl-clear"></div>
                
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
					<tr>
                        <td height="20" class="padr5" align="right"><b>Branch</b></td>
                        <td class="separator">:</td>
                        <td height="20" colspan="4">
                            <input name="branchName" type="text" class="txtfield" id="branchName" size="20" value="" />
							<input name="branch" type="hidden" id="branch" value="0" />
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td height="20" class="padr5" align="right"><b>Aging as of</b></td>
                        <td class="separator">:</td>
                        <td height="20" colspan="4">
                            <input name="AgingasOf" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?=$agingasof;?>" />
                            <i>(e.g. MM/DD/YYYY)</i>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td height="20"  class="padr5" align="right"><b>IBM Code from</b></td>
                        <td class="separator">:</td>
                        <td height="20" align="left">
                            <!--<select name="cboCustomerFrom" style="width:165px" class="txtfield">
                            <option value="0">[SELECT HERE]</option>
                            <?php 
                                if(!empty($rsIBM)){
                                    for($x = 0; $x < count($rsIBM['Code']); $x++){
                                        $sel = (isset($_POST['cboCustomerFrom']))?(($rsIBM['Code'][$x] == $_POST['cboCustomerFrom'])?"selected":""):"";
                            ?>
                            <option <?=$sel?> value="<?=$rsIBM['Code'][$x]?>">
                                    <?php echo $rsIBM['Code'][$x]; ?>
                            </option>
                            <?php 
                                    }
                                }
                            ?>
                            </select>-->
                            <input type="text" name="cboCustomerFrom" value="" class="txtfield">
                            <input type="hidden" name="cboCustomerFromHidden" value="0" class="txtfield">
                        </td>
                        <td height="20"  class="padr5" align="right"><b>IBM Code to</b></td>      
                        <td class="separator">:</td>
                        <td height="20" align="left">
                            <!--<select name="cboCustomerTo" style="width:165px" class="txtfield">
                            <option value="0">[SELECT HERE]</option>
                            <?php 
                                if(!empty($rsIBM)){
                                    for($x = 0; $x < count($rsIBM['Code']); $x++){
                                        $sel = (isset($_POST['cboCustomerTo']))?(($rsIBM['Code'][$x] == $_POST['cboCustomerTo'])?"selected":""):"";
                            ?>
                            <option <?=$sel?> value="<?=$rsIBM['Code'][$x]?>">
                                    <?php echo $rsIBM['Code'][$x]; ?>
                            </option>
                            <?php
                                    }
                                }
                            ?>
                            </select>-->
                            <input type="text" name="cboCustomerTo" value="" class="txtfield">
                            <input type="hidden" name="cboCustomerToHidden" value="0" class="txtfield">
                        </td>
                    </tr>
                    <tr>
                        <td height="20" class="padr5" align="right" valign="top"><b>Age</b></td>
                        <td class="separator">:</td>
                        <td height="20" colspan="4">
                            <?php 
                                $count = 1;
                                foreach($agerange as $key => $val){
                            ?>
                            <span style="width:200px; display: block; float:left;">
                                <input type="checkbox" checked="checked" name="ageRange[]" id="ageRange" value="<?=$key?>">
                                <?=($val == "Not Yet Due")?$val:$val." Days"?>
                            </span>
                            <?php
                                    if((($count) % 2) == 0){echo "<div style='clear:both;'></div>";}
                                    $count++;
                                }
                            ?>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="6" align="center" height="20">
                            <br />
                            <input type="submit" value="Search" name="btnSearch" class="btn">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6" height="20">&nbsp;</td>
                    </tr>
                </table>
                
            </div>
            
            
            <div style="width:100%; clear:both; min-height: 255px;">
                <div class="loader" style="display:block; text-align:center; font-weight: bold; margin-bottom: 10px; font-size:12px;">
                    &nbsp;
                </div>
                
                <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="tabmin">&nbsp;</td>
                        <td class="tabmin2 txtredbold">
                            <span style="font-weight:bold;">Result(s)</span>
                        </td>
                        <td class="tabmin3">&nbsp;</td>
                    </tr>
                </table>
                <style>
                    .tableheader{background:#FFDEF0;}
                    .tableheader td{border:1px solid #FFA3E0; padding:3px; font-weight:bold;}
                    .bordergreen tr td{border:1px solid #FFA3E0; padding:3px;}
                </style>
                <div class="tablelisttable">
                    <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="bordergreen">
                        <tr class="tableheader">
                            <td align="center">Customer</td>
                            <td align="center">Total</td>
                            <td align="center">Not Yet Due</td>
                            <td align="center">(1-30) Days</td>
                            <td align="center">(31-60) Days</td>
                            <td align="center">(61-90) Days</td>
                            <td align="center">(91-120) Days</td>
                            <td align="center">(121-150) Days</td>
                            <td align="center">(151-180) Days</td>
                            <td align="center">(181-over) Days</td>
                        </tr>
                        <tr align="center">
                            <td colspan="10" align="center" style="color:red;">No record(s) to display.</td>
                        </tr>
                    </table>
                </div> 
            </div>
        </div>
       
        <br />
        <table width="95%"  border="0" align="center">
          <tr>
            <td height="20" align="center">
              <input type="button" name="input" value="Print" onclick="return openPopUp();" class="btn" />
              <a class="txtnavgreenlink" href="index.php?pageid=18">
                <input name="Button" type="button" class="btn" value="Back" />
              </a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</form>
<br />
