<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script src="js/jxBackOrderReport.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<?php
	//setExpires(0);
	include IN_PATH.DS."scBackOrderReport.php";
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
		      		<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Sales Main</a></td>
		    	</tr>
				</table>
			</td>
		</tr>
		</table>
      	<br>
      	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td class="txtgreenbold13">Back Order Report</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<form name="frmBOR" method="post" >
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td><table width="70%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
              <tr>
                <td width="30%" height="20" align="right"></td>
                <td width="2%" height="20"></td>
                <td width="80%" height="20" align="left"></td>
              </tr>
              <tr>
                <td height="20" align="right"><strong>SO Date  :</strong></td>
                <td height="20" align="right">&nbsp;</td>
                <td height="20" align="left"><strong>From</strong>
                  <input name="txtStartDates" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $sdate ;?>" />
                  <input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" " />
                  <div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
                  
                  <strong> To </strong>
                  <input name="txtEndDates" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $edate ;?>" />
                  <input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" " />
                  <div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div></td>
              </tr>
              <tr>
                <td align="right"><strong>Branch ID  :</strong></td>
                <td width="2%" height="20">&nbsp;</td>
                <td align="left"><strong>From</strong>
                  <select name="cboBranchfrom" class="txtfield" >
                    <option value="0" selected>SELECT BRANCH</option>
                    <?php							
																						
							if ($rs_frombranches->num_rows)
							{
								while ($row = $rs_frombranches->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->ID."' $sel >".$row->ID."</option>";	
								}
								$rs_frombranches->close();
							}
						?>
                    </select>
                  <strong>To</strong>
                  <select name="cboBranchto" class="txtfield" >
                    <option value="0" selected>SELECT BRANCH</option>
                    <?php							
																						
							if ($rs_tobranches->num_rows)
							{
								while ($row = $rs_tobranches->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->ID."' $sel >".$row->ID."</option>";	
								}
								$rs_tobranches->close();
							}
						?>
                  </select></td>
              </tr>
              <tr>
                <td height="20" align="right"><strong>Dealer Code  :</strong></td>
                <td height="20"></td>
                <td height="20" align="left"><strong>From</strong>
                  <select name="cboDealerFrom" class="txtfield" >
                    <option value="0" selected="selected">SELECT DEALER CODE</option>
                    <?php							
																						
							if ($rs_dealerfrom->num_rows)
							{
								while ($row = $rs_dealerfrom->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->CustomerID."' $sel >".$row->CustomerID."</option>";	
								}
								$rs_dealerfrom->close();
							}
						?>
                    </select>
                  <strong> To 
                    <select name="cboDealerTo" class="txtfield" >
                      <option value="0" selected="selected">SELECT DEALER CODE</option>
                      <?php							
																						
							if ($rs_dealerto->num_rows)
							{
								while ($row = $rs_dealerto->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->CustomerID."' $sel >".$row->CustomerID."</option>";	
								}
								$rs_dealerto->close();
							}
						?>
                    </select>
                  </strong></td>
              </tr>
              <tr>
                <td height="20" align="right"><strong>Product Code  :</strong></td>
                <td height="20"></td>
                <td height="20" align="left"><strong>From</strong>
                  <select name="cboProdFrom" class="txtfield" >
                    <option value="0" selected="selected">SELECT PRODUCT CODE</option>
                    <?php							
																						
							if ($rs_PCfrom->num_rows)
							{
								while ($row = $rs_PCfrom->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->Code."' $sel >".$row->Code."</option>";	
								}
								$rs_PCfrom->close();
							}
						?>
                    </select>
                  <strong> To 
                  <select name="cboProdTo" class="txtfield" >
                    <option value="0" selected="selected">SELECT PRODUCT CODE</option>
                    <?php							
																						
							if ($rs_PCto->num_rows)
							{
								while ($row = $rs_PCto->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->Code."' $sel >".$row->Code."</option>";	
								}
								$rs_PCto->close();
							}
						?>
                  </select>
                  </strong></td>
              </tr>
              <tr>
                <td height="20" align="right"><strong>SO Number   :</strong></td>
                <td height="20"></td>
                <td height="20" align="left"><strong>From</strong>
                  <select name="cboSOfrom" class="txtfield" >
                    <option value="0" selected="selected">SELECT SO</option>
                    <?php							
																						
							if ($rs_SOfrom->num_rows)
							{
								while ($row = $rs_SOfrom->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->ID."' $sel >".$row->ID."</option>";	
								}
								$rs_SOfrom->close();
							}
						?>
                    </select>
                  <strong> To 
                    <select name="cboSOto" class="txtfield" >
                      <option value="0" selected="selected">SELECT SO</option>
                      <?php							
																						
							if ($rs_SOto->num_rows)
							{
								while ($row = $rs_SOto->fetch_object())
								{
						 			$sel = "";
						 			echo "<option value='".$row->ID."' $sel >".$row->ID."</option>";	
								}
								$rs_SOto->close();
							}
						?>
                    </select>
                  </strong></td>
              </tr>
              <tr>
                <td height="20" align="right"><strong>Grouped by :</strong></td>
                <td height="20"></td>
                <td height="20" align="left"><strong>
                  <select name="cboGroup" class="txtfield" >
                    <option value="0" selected="selected">SELECT GROUP</option>
                    <option value="1" >SO Date</option>
                    <option value="2" >Branch ID</option>
                    <option value="3" >Dealer Code</option>
                    <option value="4" >Item Code</option>
                    <option value="5" >SO Number</option>
                  </select>
                </strong></td>
              </tr>
              <tr>
                <td height="20" align="right"></td>
                <td height="20"></td>
                <td height="20" align="left"><input name="btnSubmit" type="button" class="btn" value="Submit" onclick="return showPage(1, '<?php echo $sdate; ?>', '<?PHP echo $edate; ?>', <?PHP echo $sbranch; ?>, <?PHP echo $ebranch; ?>, <?PHP echo $sdealer; ?>, <?PHP echo $edealer; ?>, <?PHP echo $sprod; ?>, <?PHP echo $eprod; ?>, <?PHP echo $sid; ?>, <?PHP echo $eid; ?>, <?PHP echo $grp; ?>);" /></td>
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
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
	<tr>
  		<td valign="top" class="bgF9F8F7">
	  		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	  		<tr>
				<td valign="top" class="" height="242">
	  				<div id="pgContent"><b>Loading Content...</b><img border="0" src="images/ajax-loader.gif">&nbsp;</div>
	  			</td>
	  		</tr>
	  		</table>
		</td>
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
	<script>
		//Onload start the user off on page one
		window.onload = showPage(1, '<?php echo $sdate; ?>', '<?PHP echo $edate; ?>', <?PHP echo $sbranch; ?>, <?PHP echo $ebranch; ?>, <?PHP echo $sdealer; ?>, <?PHP echo $edealer; ?>, <?PHP echo $sprod; ?>, <?PHP echo $eprod; ?>, <?PHP echo $sid; ?>, <?PHP echo $eid; ?>, <?PHP echo $grp; ?>);    
	</script>
	 <script type="text/javascript">
    		Calendar.setup({
			inputField     :    "txtStartDate",     // id of the input field
			ifFormat       :    "%Y-%m-%d",      // format of the input field
			displayArea    :	"divStartDate",
			button         :    "anchorStartDate",  // trigger for the calendar (button ID)
			align          :    "Bl",           // alignment (defaults to "Bl")
			singleClick    :    true
		});

		Calendar.setup({
			inputField     :    "txtEndDate",     // id of the input field
			ifFormat       :    "%Y-%m-%d",      // format of the input field
			displayArea    :	"divEndDate",
			button         :    "anchorEndDate",  // trigger for the calendar (button ID)
			align          :    "Bl",           // alignment (defaults to "Bl")
			singleClick    :    true
		});
		
    </script>
	</form>
	</td>
</tr>
</table>



<br>