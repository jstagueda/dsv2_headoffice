<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>
<script language="javascript" src="js/jxVwStockLogDetails.js"  type="text/javascript"></script>

<?PHP
	include IN_PATH.DS."scStockLogDetails.php";
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="topnav"><table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
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
    <td class="txtgreenbold13">Stock Log </td>
    <td>&nbsp;</td>
  </tr>
</table>
<br />
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  <tr>
    <td>
     <form name="frmStockLog" method="post" action="index.php?pageid=33.1&pid=<?php echo $_GET['pid']; ?>&wid=<?php echo $_GET['wid']; ?>">
     <input type="hidden" name="hdnProductID" value="<?php echo $prodid; ?>" />
	 <input type="hidden" name="hdnWarehouseID" value="<?php echo $wareid; ?>" />
     <input type="hidden" name="hdnBookletNo" value="<?php echo $bookid; ?>" />
     <table width="400" border="0" align="left" cellpadding="3" cellspacing="3"  class="bordersolo">
	<tr>
		<td align="left"><strong>Date From:</strong></td>
		<td align="left">
			<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $datefrom; ?>" style="width:140px">
			<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
			<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
		</td>
	</tr>
	<tr>
		<td align="left"><strong>Date To:</strong></td>
		<td align="left">
			<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $dateto; ?>" style="width:140px">
			<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
			<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
		</td>
	</tr>
   	<tr>
   		<td align="left">&nbsp;</td>
   		<td align="left"><input type="submit" name="btnSearch" class="btn" id="button" value="Submit" /></td>
	</tr>
	</table>
</form>
</td>
  </tr>
</table>
<br />
      <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><span class="txtredbold">Product Details</span></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>      
	  <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
        <tr>
          <td class="bgF9F8F7">
		  	<table width="95%"  border="0" cellspacing="0" cellpadding="2" align="center">
			  <tr>
				<td width="15%">&nbsp;</td>
				<td width="28%">&nbsp;</td>
				<td width="14%">&nbsp;</td>
				<td width="43%">&nbsp;</td>
			  </tr>
			  <tr>
				<td>&nbsp;<strong>LOCATION :</strong></td>
				<td><?PHP echo $warehouse; ?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			  <tr>
				<td>&nbsp;<strong>PRODUCT CODE :</strong></td>
				<td><?PHP echo $prodcode; ?></td>
				<td>&nbsp;<strong>PRODUCT NAME :</strong></td>
				<td><?PHP echo $prodname; ?></td>
			  </tr>
			  <tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			  </tr>
			</table>

		  </td>
        </tr>
    </table>      
        <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="3" class="bgE6E8D9"></td>
                </tr>
              </table>
<br />
	<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td class="tabmin">&nbsp;</td>
          <td class="tabmin2"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="txtredbold">&nbsp;</td>
               	<td>&nbsp;</td>
              </tr>
          </table></td>
          <td class="tabmin3">&nbsp;</td>
        </tr>
      </table>
     
     
     <table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
        <tr>
          <td valign="top" class="bgF9F8F7"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              <tr>
                <td class="tab">
                <table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10" height="25">
                    <tr align="center">
                      <td width="10%" height="20" class="bdiv_r">Date</td>
                      <td width="30%" height="20" class="bdiv_r">Movement Type</td>
                      <td width="15%" height="20" class="bdiv_r">Reference No.</td>
                      <td width="15%" height="20" class="bdiv_r">Document No.</td>
                      <td width="10%" height="20" class="bdiv_r">Qty In</td>
                      <td width="10%" height="20" class="bdiv_r">Qty Out</td>
                      <td width="10%" height="20">Running Balance</td>
                    </tr>
                </table></td>
              </tr>
			  <tr>
                <td class="bordergreen_B"><div class="scroll_300"><table width="100%"  border="0" cellpadding="0" cellspacing="1" class="bgFFFFFF">
                 <tr class="bgE6E8D9">
                 	<td colspan="4" height="20" class="borderBR" align="right"><span class="txtbold">Beginning Balance :&nbsp;</span></td>		  
				  	<td height="20" class="borderBR">&nbsp;</td>
				  	<td height="20" class="borderBR">&nbsp;</td>
				  	<td height="20" class="borderBR padr5" align="right"><span class="txtbold"><?PHP echo number_format($beg, 0, '.', ''); ?>&nbsp;</span></td>
				  </tr>
				  <?PHP
				  		if ($rs_stocklog->num_rows)
						{
							$runbal = $beg;
							$rowalt = 0;
							while ($row = $rs_stocklog->fetch_object())
							{
							 	$rowalt += 1;
								($rowalt % 2) ? $class = "" : $class = "bgEFF0EB";
								$runbal += $row->QtyIn; 
								$runbal -= $row->QtyOut;
				  ?>
              	<tr align='center' class='<?PHP echo $class;?>'>
                      <td width='10%' height='20' class='borderBR'><?PHP echo $row->TxnDate;?></td>
                      <td width='30%' height='20' class='borderBR'><?PHP echo $row->MovementType; ?></td>
                      <td width='15%' height='20' class='borderBR'><?PHP echo $row->RefTxnNo; ?></td>		  
                      <td width='15%' height='20' class='borderBR'><?PHP echo $row->RefNo; ?></td>
                      <td width='10%' height='20' class='borderBR'><?PHP echo number_format($row->QtyIn,0); ?></td>
                      <td width='10%' height='20' class='borderBR'><?PHP echo number_format($row->QtyOut,0); ?></td>
                      <td width='10%' height='20' class='borderBR padr5' align='right'><?PHP echo number_format($runbal,0); ?></td>
				</tr>
				<!--<tr>
					<td colspan="4" height="20" class="borderBR" align="right"><span class="txtbold">Running Balance :&nbsp;</span></td>		  
					<td height="20" class="borderBR">&nbsp;</td>
					<td height="20" class="borderBR padr5" align="right"><span class="txtbold"><?PHP echo number_format($runbal, 0, '.', '');?></span>&nbsp;</td>
				</tr>-->
			 	<?PHP
                    	}
						$rs_stocklog->close();
					}
					else
					{
						 echo "<tr align='center'>
						  <td height='20' colspan='6' class='borderBR'><span class='txt10 txtreds'><b>No record(s) to display.</b></span></td>
				        </tr>";
					}
					?>
                       <tr class="bgE6E8D9">
						<td colspan="4" height="20" class="borderBR" align="right"><span class="txtbold">Ending Balance :&nbsp;</span></td>		  
						<td height="20" class="borderBR">&nbsp;</td>
						<td height="20" class="borderBR">&nbsp;</td>
						<td height="20" class="borderBR padr5" align="right"><span class="txtbold"><?PHP echo number_format($end, 0, '.', '');?></span></td>
				  	</tr>    
					
                  </table>	    		  
                </div>
                </td>
              </tr>
          </table></td>
        </tr>
      </table>
      <br>

      <table width="95%"  border="0" align="center">
		  <tr>
			<td height="26" align="center"><a class="txtnavgreenlink" href="index.php?pageid=33">
			<input name="Button" type="button" class="btn" value="Back"></a>
			<input name="btnPrint" type="submit" class="btn" value="Print" onclick="javascript: return validatePrint(<?php echo $_GET['pid']; ?>,1,'<?php echo $wareid; ?>','<?php echo $datefrom_q;?>','<?php echo $dateto_q;?>');"/> </td>             
		  </tr>
</table>
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