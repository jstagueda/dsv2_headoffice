<?PHP 
	include IN_PATH.DS."scViewProvisionalReceipt.php";
?>

<script src="js/jxPagingProvisionalReceipt.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<!--<link rel="stylesheet" type="text/css" href="../../css/ems.css">
--><table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
		    <tr>
		      <td width="70%" align="right">&nbsp;<a class="txtblueboldlink" href="index.php?pageid=18">Sales Main</a></td>
		    </tr>
		</table>
		</td>
	</tr>
</table>
      <br>
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td class="txtgreenbold13">List of Provisional Receipt</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<form name="frmSalesProvisionalReceipt" action="index.php?pageid=51" method="post">
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		<tr>
	    	<td>
	    		<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
			        <tr>
			          	<td width="1%">&nbsp;</td>
			          	<td width="10%" align="right">&nbsp;</td>
			          	<td width="1%" align="right">&nbsp;</td>
			          	<td width="70%" align="right">&nbsp;</td>
			        </tr>
	        		<tr>
		        		<td width="1%">&nbsp;</td>
				    	<td width="10%" align="right">From Date</td>
				        <td width="1%" align="center">:</td>
				        <td width="70%" align="left"><input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $fromdate; ?>">
		        		<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
						</td>
					</tr>
					<tr>
						<td width="1%">&nbsp;</td>
				          	<td width="10%" align="right">To Date</td>
				          	<td width="1%" align="center">:</td>
				          	<td width="70%" align="left">
				          	<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $todate; ?>">	        			
							<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
							<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
						</td>
					</tr>
					<tr>
							<td width="1%">&nbsp;</td>
			        		<td width="10%" align="right">Search</td>
			        		<td width="1%" align="center">:</td>
			        		<td width="70%" align="left">
			        		<input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="30" value="<?php echo $vSearch; ?>" />
							<input name="btnSearch" type="submit" class="btn" value="Search">
							</td>
				    </tr>
				    <tr>
			    		<td width="1%"></td>
			        	<td width="10%" align="right"></td>
			        	<td width="1%" align="center"></td>
			        	<td width="70%" align="left"></td>
			    	</tr>
	    		</table>
	   		</td>
	  	</tr>
	</table>
	
	<br />
	<br /> 

	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          	<td class="">
			  	<?php 
				  	if (isset($_GET['msg']))
				  	{
					  	$message = strtolower($_GET['msg']);
					  	$success = strpos("$message","success"); 
					  	echo "<div align='left' class='txtblueboldlink'><strong>".$_GET['msg']."</strong></div>"; 
				  	} 
			  	?> 
         	</td>
        </tr>
	</table>
	
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
                <td class="" height="242" valign="top">
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
	window.onload = showPage("1", "<?php echo $vSearch; ?>","<?php echo $fromdate; ?>","<?php echo $todate; ?>");    
    </script>
    <br />
    </form>
    </td>
  </tr>
</table>

<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtEndDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divEndDate",
		button         :    "anchorEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>