<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>


<script src="js/jxPaymentClassification.js" language="javascript" type="text/javascript"></script>
<?php
	//setExpires(0);
	include IN_PATH.DS."scPaymentClassification.php";
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
    		<td class="txtgreenbold13">Payments Classification Report</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<form name="frmValuationReport" method="post" >
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td>
    		<table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td width="20%" height="20" align="right"></td>
          			<td width="2%" height="20"></td>
          			<td width="80%" height="20" align="left"></td>   
				</tr>
				<tr>
          			<td height="20" align="right"><strong>Branch :</strong></td>
          			<td height="20" align="right">&nbsp;</td>
          			<td height="20" align="left">
                    	<?php
						while($rowC = $rs_defaultbranch->fetch_object())
						{
						$def = $rowC->Id;
						}
						?>
            			<select name="cboBranch" class="txtfield" >
            				<option value="0" selected>SELECT BRANCH</option>
							<?php							
																						
							if ($rs_branches->num_rows)
							{
								while ($row = $rs_branches->fetch_object())
								{
						 			if ($row->ID == $def)
									{ 
										$sel = "selected";
									}else
									{
									$sel = "";
									}
						 			echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";	
								}
								$rs_branches->close();
							}
						?>
            			</select>
          			</td>
        		</tr>
         		<tr>
		  		<td width="20%" align="right"><strong>Date From:</strong></td>
				<td width="2%" height="20">:</td>
				<td width="70%" align="left"><input name="txtStartDates" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="">
		        <input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
				<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
				</td>
		  </tr>

				        		<tr>
		  		<td width="20%" align="right"><strong>Date To: </strong></td>
				<td width="2%" height="20">:</td>
				<td width="70%" align="left">
                <input name="txtEndDates" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="">
		        <input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
				<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
				</td>
		  </tr>

    		<tr>
    		  <td height="20" align="right"></td>
    		  <td height="20"></td>
    		  <td height="20" align="left"><input name="btnSubmit" type="button" class="btn" value="Submit" onclick="return showPage(0, <?php echo $branchid; ?>, '<?PHP echo $fromdate; ?>', '<?PHP echo $todate; ?>');" /></td>
  		  </tr>
    		<tr>
    			<td colspan="3" height="15">&nbsp;</td>
    		</tr>
    		</table>
    	</td>
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
	<script >
		//Onload start the user off on page one
		window.onload = showPage(0, <?php echo $branchid; ?>, '<?PHP echo $fromdate; ?>', '<?PHP echo $todate; ?>');    
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