<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<?php
	include IN_PATH.DS."scPromoPerformValidation.php";		
?>

<script src="js/jxViewPerformPromoValidation.js" language="javascript" type="text/javascript"></script>

<form name="frmPerformPromoValidation" method="post" action="index.php?pageid=68">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="topnav">
		<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
		    <td width="70%" class="txtgreenbold13" align="left"></td>
			<td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=80">Leader List Main</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="98%"  border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%">&nbsp;<a class="txtgreenbold13">Perform Promo Validation</a></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td> 
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Promo Effectivity Date</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="16%">&nbsp;</td>
					<td width="1%" align="right">&nbsp;</td>
					<td width="83%" align="right">&nbsp;</td>
				</tr>
				<tr>
				  <td height="20"><div align="right">&nbsp;&nbsp;<strong>From Date: </strong></div></td>
				  <td height="20">&nbsp;</td>
				  <td height="20">
						<input name="txtFromDate" type="text" class="txtfield" id="txtFromDate" size="20" readonly="yes" value="<?php  ?>">
						<input type="button" class="buttonCalendar" name="anchorTxnFromDate" id="anchorTxnFromDate" value=" ">
						<div id="divTxnFromDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
					</td>
				</tr>
				
				<tr>
				  <td height="20"><div align="right">&nbsp;&nbsp;<strong>To Date: </strong></div></td>
				  <td height="20">&nbsp;</td>
				  <td height="20">
						<input name="txtToDate" type="text" class="txtfield" id="txtToDate" size="20" readonly="yes" value="<?php  ?>">
						<input type="button" class="buttonCalendar" name="anchorTxnToDate" id="anchorTxnToDate" value=" ">
						<div id="divTxnToDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
					</td>
				</tr>
			    <tr>
				    <td height="20"><div align="right">&nbsp;&nbsp;<strong>Item Code From: </strong></div></td>
				    <td height="20">&nbsp;</td>
				    <td height="20"><input name="txtCode" type="text" class="txtfield" id="txtCode" value="<?php  ?>" size="30"></td>
			    </tr>
				
				<tr>
				    <td height="20"><div align="right">&nbsp;&nbsp;<strong></strong></div></td>
				    <td height="20">&nbsp;</td>
				    <td height="20"><input name="btnRun" type="submit" class='btn'  id="btnRun" value="Run Validation Routine" size="30">
					</td>
			    </tr>
				<tr>
					<td colspan="3" height="20">&nbsp;</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin"></td> 
	<td class="tabmin2"><div align="left" class="txtredbold padl5">View Conflicts</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>
<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
  <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
      <tr>
        <td class="" height="242" valign="top">
        <div id="pgContent">
        <b>Loading Content...</b><img border="0" src="images/ajax-loader.gif">&nbsp;
        </div>
        
        
        </td>
      </tr>
  </table></td>
</tr>
</table>
<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
  <td height="3" class="bgE6E8D9"></td>
</tr>
</table>
<br>
<table width="98%"  border="0" cellspacing="0" cellpadding="0" align="center">
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
	window.onload = showPage("1",  "<?php echo $fromdate; ?>", "<?php echo $todate; ?>", "<?php echo $code; ?>");    
</script>
<br>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
<!--	<td align="center">	-->
<!--		<input name="btnPrint" type="submit" class="btn" value="Print" onclick="">-->
<!--		<input name="btnConfirm" type="submit" class="btn" value="Confirm" onclick="">-->
<!--		<input name="btnCancel" type="submit" class="btn" value="Cancel" onclick="">-->
<!--		<input name="btnDelete" type="submit" class="btn" value="Delete" onclick="">-->
<!--	</td>			-->
</tr>
</table>
</form>

<br>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtFromDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divTxnFromDate",
		button         :    "anchorTxnFromDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtToDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divTxnToDate",
		button         :    "anchorTxnToDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
