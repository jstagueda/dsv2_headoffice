<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />

<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<script src="js/jxPagingTopSellingProductsReport.js" language="javascript" type="text/javascript"></script>

<?php
	include IN_PATH.DS."scTopSellingReport.php";
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
    		<td class="txtgreenbold13">Top Selling Products</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
			<form name="frmTopSellingReport" method="post">
		  	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    <tr>
	      		<td>
	      			<table width="100%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
		        	<tr>
		          		<td width="15%" height="20">&nbsp;</td>
		          		<td width="2%" height="20">&nbsp;</td>
		          		<td width="83%" height="20">&nbsp;</td>
	            	</tr>
		        	<tr>
		          		<td height="20" align="right"><strong>From Date :</strong></td>
		          		<td height="20" align="right">&nbsp;</td>
		          		<td height="20" align="left">
		          			<input id="txtFromDate" name="txtFromDate" type="text" class="txtfield" style="width: 120px;" readonly="readonly" value="<?php echo $fromdate; ?>" />
		            		<input type="button" class="buttonCalendar" name="anchorFromDate" id="anchorFromDate" value=" " />
		            		<div id="divFromDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
	            		</td>
        			</tr>
		        	<tr>
		          		<td height="20" align="right"><strong>To Date :</strong></td>
		          		<td height="20">&nbsp;</td>
		          		<td height="20" align="left">
		          			<input id="txtToDate" name="txtToDate" type="text" class="txtfield" style="width: 120px;" readonly="yes" value="<?php echo $todate; ?>">
		        			<input type="button" class="buttonCalendar" name="anchorToDate" id="anchorToDate" value=" ">
							<div id="divToDate" style="background-color : white; position:absolute;visibility:hidden;"></div>
						</td>
            		</tr>
            		<tr>
			          <td height="20" align="right"><strong>From Time :</strong></td>
			          <td height="20">&nbsp;</td>
			          <td height="20" align="left"><input id="txtFromTime" name="txtFromTime" type="text" class="txtfield"  style="width: 120px;" value="<?php echo $fromtime ;?>">&nbsp;<strong>[hh:mm]</strong></td>
		            </tr>
			        <tr>
			          <td height="20" align="right"><strong>To Time :</strong></td>
			          <td height="20">&nbsp;</td>
			          <td height="20" align="left"><input id="txtToTime" name="txtToTime" type="text" class="txtfield"  style="width: 120px;" value="<?php echo $totime ;?>">&nbsp;<strong>[hh:mm]</strong></td>
		            </tr>
		        	<tr>
		          		<td height="20" align="right"><strong>Product :</strong></td>
		          		<td height="20">&nbsp;</td>
		          		<td height="20" align="left"><input id="txtProduct" name="txtProduct" type="text" class="txtfield" style="width: 120px;" value="<?php echo $product; ?>" /></td>
            		</tr>
		        	<tr>
		          		<td height="20" align="right"></td>
		          		<td height="20"></td>
		          		<td height="20" align="left"><input name="btnSubmit" type="submit" class="btn" onclick = "return xvalidation();" value="Submit" /></td>
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
		<br>
	   	<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center">
		<tr>
			<td align="center"><input name="input" type="button" class="btn" value="Print" onclick="openPopUp('<?php echo $sfromdate;?>', '<?php echo $stodate;?>', '<?php echo $fromtime;?>', '<?php echo $totime;?>', '<?php echo $product;?>')"/></td>
		</tr>
		</table>

	<script>
		//Onload start the user off on page one
		window.onload = showPage("1", "<?php echo $sfromdate; ?>","<?php echo $stodate; ?>", "<?php echo $fromtime; ?>", "<?php echo $totime; ?>", "<?php echo $product; ?>");    
	</script>
	
	<script type="text/javascript">
    		Calendar.setup({
			inputField     :    "txtFromDate",     // id of the input field
			ifFormat       :    "%m/%d/%Y",      // format of the input field
			displayArea    :	"divFromDate",
			button         :    "anchorFromDate",  // trigger for the calendar (button ID)
			align          :    "Bl",           // alignment (defaults to "Bl")
			singleClick    :    true
		});

		Calendar.setup({
			inputField     :    "txtToDate",     // id of the input field
			ifFormat       :    "%m/%d/%Y",      // format of the input field
			displayArea    :	"divToDate",
			button         :    "anchorToDate",  // trigger for the calendar (button ID)
			align          :    "Bl",           // alignment (defaults to "Bl")
			singleClick    :    true
		});
    </script>
	</form>
	</td>
</tr>
</table>
<br>