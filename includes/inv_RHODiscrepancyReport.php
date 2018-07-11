<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<script language="javascript" src="js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>

<script src="js/jxRHODiscrepancyReport.js" language="javascript" type="text/javascript"></script>

<?php
	include IN_PATH.DS."scRHODiscrepancyReport.php";
?>

<style type="text/css">
<!--
.style1 {
color: #FF0000;
font-weight: bold;
}
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
    		<td class="txtgreenbold13">RHO/STA Discrepancy Report</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<form name="frmCSOS" method="post">
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td>
    			<table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
				<tr>
					<td width="35%" height="20" align="right"></td>
          			<td width="2%" height="20">&nbsp;</td>
          			<td width="80%" height="20" align="left"></td>   
				</tr>
				<tr>
          			<td height="20" align="right"><strong>Date :</strong></td>
          			<td height="20" align="right">&nbsp;</td>
          			<td height="20" align="left">
            			<input id="txtDate" name="txtDate" type="text" class="txtfield"  style="width: 120px;" readonly="yes" value="<?php echo $ssdate ;?>">
		        		<input type="button" class="buttonCalendar" name="anchorDate" id="anchorDate" value="">
						<div id="divDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
          			</td>
        		</tr>
		    	<tr>
		      		<td height="20" align="right"><strong>Branch Code :</strong></td>
    		      	<td height="20">&nbsp;</td>
    		      	<td height="20" align="left">
    		      		<select id="cboBCode" name="cboBCode" class="txtfield" style="width: 120px;">
    		        		<option value="0" selected="selected">[SELECT ALL]</option>
							<?php																						
							if ($rs_branch->num_rows)
							{
								while ($row = $rs_branch->fetch_object())
								{
						 			if ($row->ID == $bcode)
									{
										$sel = "selected";										
									}
						 			else
						 			{
						 				$sel = "";
						 			}
						 			echo "<option value='".$row->ID."' $sel >".$row->Code."</option>";	
								}
								$rs_branch->close();
							}
							?>
	        			</select>
        			</td>
	      		</tr>
	      		<tr>
		      		<td height="20" align="right"><strong>Movement Type :</strong></td>
    		      	<td height="20">&nbsp;</td>
    		      	<td height="20" align="left">
    		      		<select id="cboMType" name="cboMType" class="txtfield" style="width: 120px;">
    		        		<option value="0" selected="selected">[SELECT ALL]</option>
							<?php																						
							if ($rs_mtype->num_rows)
							{
								while ($row = $rs_mtype->fetch_object())
								{
						 			if ($row->ID == $mtype)
									{
										$sel = "selected";										
									}
						 			else
						 			{
						 				$sel = "";
						 			}
						 			echo "<option value='".$row->ID."' $sel >".$row->Code."</option>";	
								}
								$rs_mtype->close();
							}
							?>
	        			</select>
        			</td>
	      		</tr>
		    	<tr>
	  				<td height="20" align="right"></td>
    		  		<td height="20">&nbsp;</td>
    		  		<td height="20" align="left"><input id="btnSubmit" name="btnSubmit" type="submit" class="btn" value="Submit"/></td>
  				</tr>
    			<tr>
    				<td colspan="3" height="20">&nbsp;</td>
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
	<br>
   	<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
	<tr>
		<td align="center"><input name="input" type="button" class="btn" value="Print" onclick="openPopUp('<?php echo $sdate;?>', '<?php echo $bcode;?>', <?php echo $mtype;?>)"/></td>
	</tr>
	</table>

	<script>
		//Onload start the user off on page one
		window.onload = showPage("1", "<?php echo $sdate;?>", "<?php echo $bcode;?>", "<?php echo $mtype;?>");    
	</script>
	
	</form>
	</td>
</tr>
</table>
<br>

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divDate",
		button         :    "anchorDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>