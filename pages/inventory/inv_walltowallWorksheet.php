<?php
/*
  @package Wall to Wall Worksheet printing page.
  @author John Paul Pineda.
  @email paulpineda19@yahoo.com
  @copyright 2012 John Paul Pineda.
  @version 1.0 October 15, 2012.

  @description This page is for printing of PDF document with one or more than one Inventory Count Transaction.
*/ 
?>
<link type="text/css" rel="stylesheet" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script src="js/shortcut.js" type="text/javascript"></script>

<style>
<!--
.style1 { color:#FF0000; }

div.autocomplete {

  position:absolute;
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px; }
 
div.autocomplete ul {

  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family:Verdana, Arial, Helvetica, sans-serif;
  font-size:10px;  
}

div.autocomplete ul li.selected { background-color:#ffb; }

div.autocomplete ul li {

  cursor:pointer;
  list-style-type:none;
  display:block;
  margin:0;
  border-bottom: 1px solid #888;
  padding: 2px;  
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size:10px;  
}
-->
</style>

<script type="text/javascript">
var ic1_id=0, ic2_id=0, individual_ic="";

function trim(s) {

	var l=0; var r=s.length-1;
	while(l<s.length && s[l]==' ')
	{	l++; }
	
	while(r>l && s[r]==' ')
	{	r-=1;	}
	return s.substring(l, r+1);
}

function print_worksheet() {
  
  if(document.getElementById('worksheet1').value=='') ic1_id=0;
  else if(document.getElementById('worksheet2').value=='') ic2_id=0;    
  
  if(ic1_id!=0 && ic2_id!=0)
  load_new_tpi_window("pages/inventory/inv_countDetailsWalltoWall_print.php?ic1_id="+ic1_id+"&ic2_id="+ic2_id+"&sort=1", "wall_to_wall_worksheets", "900", "900", "yes");
  else 
  load_new_tpi_window("pages/inventory/"+individual_ic+".php?tid="+(ic1_id==0?ic2_id:ic1_id)+"&sort=1", "wall_to_wall_worksheets", "900", "900", "yes");   
}

function getInventoryCountID(text, li) {
  
  tmp_id=li.id.split("_");
  
  if(text.id=='worksheet1') ic1_id=tmp_id[0]; 
  else if(text.id=='worksheet2') ic2_id=tmp_id[0];
  
  if(tmp_id[1]==1) individual_ic="inv_countDetails_print";
  else if(tmp_id[1]==2) individual_ic="inv_countDetailsInDG_print";      
}

var $tpi=jQuery.noConflict();

$tpi(document).ready(function() {
  
  new Ajax.Autocompleter("worksheet1", "worksheet1_choices", "tpl/tpi-choices-loader.php?what_to_load=InventoryCountList", {indicator:'worksheet1_indicator', afterUpdateElement:getInventoryCountID});
  new Ajax.Autocompleter("worksheet2", "worksheet2_choices", "tpl/tpi-choices-loader.php?what_to_load=InventoryCountList", {indicator: 'worksheet2_indicator', afterUpdateElement:getInventoryCountID});
});
</script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td class="topnav">
            <table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory Cycle Main</a></td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
      <br />
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
        <tr>
          <td class="txtgreenbold13">Wall to Wall Worksheet</td>
          <td>&nbsp;</td>
        </tr>
      </table>
      <br />
      
      <form name="frmValuationReport" action="" method="post">
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">                           
          <tr>
            <td>
              <table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">                                                                
                <tr>
                  <td height="20" align="left">
                    <strong>Work Sheet 1 Number :</strong>
                    <input type="text" id="worksheet1" name="worksheet1" value="" class="txtfield" />
                    <span id="worksheet1_indicator" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>
                    <div id="worksheet1_choices" class="autocomplete"></div>&nbsp;&nbsp;
                    
                    <strong>Work Sheet 2 Number :</strong>
                    <input type="text" id="worksheet2" name="worksheet2" value="" class="txtfield" />
                    <span id="worksheet2_indicator" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>
                    <div id="worksheet2_choices" class="autocomplete"></div>&nbsp;&nbsp;
                    
                    <input type="button" id="print_worksheets" name="print_worksheets" value="Print" onclick="print_worksheet();" class="btn" />              
                  </td>            
                </tr>                                    
              </table>
            </td>
          </tr>
        </table>          
      </form>
    </td>
  </tr>
</table><br />
