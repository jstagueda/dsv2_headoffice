<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<?PHP 
	include IN_PATH.DS."scDMCMRegister.php";
?>

<script src="js/jxPagingSIRegister.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<script src="js/jxPagingDMCMRegister.js" language="javascript" type="text/javascript"></script>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
    <td class="txtgreenbold13">Memo Register</td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<form name="frmORRegister" method="post" action="index.php?pageid=98">
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td>
		  <table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
		        <tr>
		          	<td width="8%">&nbsp;</td>
		          	<td width="91%" align="right">&nbsp;</td>
		        </tr>
        		<tr>
		          	<td height="20" class="padl5">From Date :</td>
		          	<td height="20">
		          		<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $fromdate; ?>">
        				<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
					</td>				 
				</tr>
				<tr>
		          	<td height="20" class="padl5">To Date :</td>
		          	<td height="20">
		          		<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $todate; ?>">	        			
						<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
						<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>
				<tr>
				  	<td height="20" class="padl5">Reason Code :</td>
		          	<td height="20">                                              
  					<select name="cboReasons" style="width:1657x" class="txtfield">
							<?PHP
							$reasonlist = $sp->spSelectReasonForDMCM($database, 0);
							echo "<option value=\"0\" >All</option>";
							    if ($reasonlist->num_rows)
								 {
									 while ($row =  $reasonlist->fetch_object())
									  {
									     ($rid == $row->ID) ? $sel = "selected" : $sel = "";
									      echo "<option value='$row->ID' $sel>$row->Name</option>";
									   }
								 }
							 ?>
					 </select>
                 </tr>
				<tr>
		          	<td height="20" align="left" class="padl5"></td>            
		          	<td height="20" align="left">
<!--						<input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="30" value="<?php echo $vSearch; ?>" />-->
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
</form>
<br />
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">Cash Receipts</td>
               	<td>&nbsp;</td>
              </tr>
          </table></td>
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
	
		window.onload = showPage("1", "<?php echo $fromdate; ?>","<?php echo $todate; ?>", "<?PHP echo $rid; ?>");    
	</script>
      <table width="95%"  border="0" align="center">
      <tr>
      	<td height="20" align="center">
      		<a class="txtnavgreenlink" href="index.php?pageid=18"><input name="Button" type="button" class="btn" value="Back"></a>
      		<input name="input" type="button" class="btn" value="Print" onclick="openPopUp('<?php echo $fromdate; ?>','<?php echo $todate; ?>', '<?PHP echo $rid; ?>');"/>
      	</td>
  	  </tr>
	  </table>
	  </td>
  </tr>
</table>
<br>
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