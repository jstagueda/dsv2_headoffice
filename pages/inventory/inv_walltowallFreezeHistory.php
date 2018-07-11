<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>


<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script src="js/shortcut.js" type="text/javascript"></script>

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>

<script src="js/jxPagingValuationReport.js" language="javascript" type="text/javascript"></script>

<?php
	//setExpires(0);

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
<!--
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
}z

-->
</style>


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
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td class="txtgreenbold13">Wall to Wall Freeze History</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<form name="frmValuationReport" method="post" action="">
		  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    <tr>
		      <td><table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
		        <tr>
		          <td width="20%" height="20" align="right"></td>
		          <td width="2%" height="20"></td>
		          <td  height="20" align="left"></td>
	            </tr>
		        <tr>
		          <td height="20" align="right"><strong>From Worksheet No. :</strong></td>
		          <td height="20"></td>
		          <td height="20" align="left"><input name="txtStartDate" type="text" class="txtfield" value="" /></td>
	            </tr>
		        <tr>
		          <td height="20" align="right"><strong>To Worksheet No. :</strong></td>
		          <td height="20"></td>
		          <td height="20" align="left"><input name="txtStartDate2" type="text" class="txtfield" value="" /></td>
	            </tr>
		        <tr>
		          <td height="20" align="right"></td>
		          <td height="20"></td>
		          <td height="20" align="left"><input name="btnSubmit" type="button" class="btn" value="Submit" /></td>
	            </tr>
		        <tr>
		          <td colspan="3" height="15">&nbsp;</td>
	            </tr>
		        </table></td>
	        </tr>
	      </table>
		  <br />
		  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
		    <tr>
		      <td class="tabmin">&nbsp;</td>
		      <td class="tabmin2">&nbsp;</td>
		      <td class="tabmin3">&nbsp;</td>
	        </tr>
	      </table>
          <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
            <tr>
              <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
                <tr>
                  <td valign="top" class="" height="242"><table id = "pgContent" width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
                    <tr align='center' >
                      <td width='20%' height='20' align='left' 	 class='padl5 bdiv_r'><strong>Worksheet Number</strong></td>
                      <td width='10%' height='20' align='left' 	 class='padl5 bdiv_r'><strong>Location</strong></td>
                      <td width='5%' height='20' align='center'  class='bdiv_r'><strong>Date Created</strong></td>
                      <td width='5%' height='20' align='center' class='bdiv_r'><strong>Freeze Date</strong></td>
                      <td width='5%' align='center' class='bdiv_r'><strong>Confirmed Date</strong></td>
                      <td width='10%' align='center' class='bdiv_r'><strong>Status</strong></td>
                      </tr>
                  </table></td>
                </tr>
              </table></td>
            </tr>
          </table>
          <br>
   	<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td height="20" class="txtblueboldlink" width="50%">
			<div id="pgNavigation"><b>Loading Navigation...</b><img border="0" src="images/ajax-loader.gif"></div>
		</td>
		<td height="20" class="txtblueboldlink" width="48%">
			<div id="pgRecord" align="right"><b>Loading Navigation...</b><img border="0" src="images/ajax-loader.gif"></div>
		</td>
	</tr>
	</table>
	<?php 
		if(isset($_GET['lid']))
		{
			$vLocation = $_GET['lid'];		
		}
		else
		{
			$vLocation = 0;	
		}
		
		if(isset($_GET['wid']))
		{
			$warehouseid = $_GET['wid'];		
		}
		else
		{
			$warehouseid = 1;	
		}
		
	?>
	
	<script>
		//Onload start the user off on page one
		window.onload = showPage("1", "<?php echo $prodCode; ?>","<?PHP echo $warehouseid; ?>", "<?PHP echo $tmpLid; ?>",	"<?PHP echo $tmpPlid; ?>","<?PHP echo $tmpPmg; ?>","<?php echo $vdte;?>");    
	</script>
	
	</form>
	</td>
</tr>
</table>

<table width="95%"  border="0" align="center">
	<tr>
		<td height="20" align="center">
    	<!--<a class="txtnavgreenlink" href="index.php?pageid=1"><input name="Button" type="button" class="btn" value="Back"></a>
    	-->
    	<input name="input" type="button" class="btn" value="Print" onclick="openPopUp('<?php echo $prodCode; ?>','<?PHP echo $warehouseid; ?>','<?PHP echo $tmpLid; ?>','<?PHP echo $tmpPlid; ?>','<?PHP echo $tmpPmg; ?>','<?php echo $vdte;?>')"/>
    	</td>
	</tr>
</table>



<br>