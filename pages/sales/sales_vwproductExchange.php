<?PHP 
/*Gino C. Leabres*/
//include/scViewSalesOrder.php
//-----------------------------------------//
include IN_PATH.DS."scViewSalesOrder.php";
//-----------------------------------------//
?>
<script src="js/jxPagingListProdExchange.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    <tr>
		      <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=1">Inventory</a></td>
		    </tr>
		</table>
		</td>
	</tr>
</table>
<br>
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
<?php 
if (isset($_GET['msg']))
{
?>	
	<tr>
		<td>
			<?php 
				$message = strtolower($_GET['msg']);
				$success = strpos("$message","success"); 
				echo "<div align='left' style='padding:5px 0 0 5px;' class='txtblueboldlink'>
							<strong>".$_GET['msg']."</strong>
					  </div>"; 
			?> 
		</td>
	</tr>
<?php 
}
else if(isset($_GET['errmsg']))
{
?>
	<tr>
		<td>
			<?php
				$errormessage = strtolower($_GET['errmsg']);
				$error = strpos("$errormessage","error"); 
				echo "<div align='left' style='padding:5px 0 0 5px;' class='txtredbold'>"
							.$_GET['errmsg'].
					  "</div>";
			?>
		</td>
	</tr>
<?php			
} 
?>
<tr>
	<td class="txtgreenbold13">List of Product Exchange</td>
</tr>
	</table>
	<form name="frmListSO" action="index.php?pageid=160" method="post">
	<br />
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
	  <tr>
		<td>
			<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td width="12%">&nbsp;</td>
							<td width="89%" align="right">&nbsp;</td>
						</tr>
						<tr>
							<td height="20">From Date :</td>
							<td height="20">
								<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $fromdate; ?>">
								<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
								<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
							</td>				 
						</tr>
						<tr>
							<td height="20">To Date &nbsp; &nbsp;: </td>
							<td height="20">
								<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $todate; ?>">	        			
								<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
								<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
							</td>
						</tr>
						<tr>
							<td height="20" align="left">Search &nbsp; &nbsp; : </td>            
							<td height="20" align="left">
								<input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="30" value="<?php echo $vSearch; ?>" />
								<input name="btnSearch" type="submit" class="btn" value="Search">						 
							</td>
						</tr>
						<tr>
							<td colspan="2" height="20">&nbsp;</td>
						</tr>
			</table>
		</td>
	  </tr>
	</table>
				<br /><br />
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
				<td valign="top" class="" height="242">
				<div id="pgContent">
					<b>Loading Content...</b><img border="0" src="images/ajax-loader.gif">&nbsp;
					</div>
				</td>
			</tr>
		</table></td>
		</tr>
	</table>

	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td height="3" class="bgE6E8D9"></td>
		</tr>
	</table>
		  
		  <br />
		  
	<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td height="20" class="txtblueboldlink" width="50%">
				<div id="pgNavigation">
					<b>Loading Navigation...</b>
					<img border="0" src="images/ajax-loader.gif">
				</div>	
			</td>
			<td height="20" class="txtblueboldlink" width="48%">
				<div id="pgRecord" align="right">
					<b>Loading Navigation...</b>
						<img border="0" src="images/ajax-loader.gif">
				</div>
			</td>
		</tr>
	</table>
		  <br />
		  <br />
		  <br />
		  
		<script>
			//Onload start the user off on page one//
			window.onload = showPage("1", "<?php echo $vSearch; ?>","<?php echo $fromdate; ?>","<?php echo $todate; ?>");    
		</script>
</form>
    </td>
  </tr>
</table>
<?php /*Calendar Setup*/ ?>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      	// format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",				// alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtEndDate",    	   // id of the input field
		ifFormat       :    "%m/%d/%Y",      	  // format of the input field
		displayArea    :	"divEndDate",
		button         :    "anchorEndDate",  	 // trigger for the calendar (button ID)
		align          :    "Bl",				// alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>