<script src="js/jxPagingORTxnRegister.js" language="javascript" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="js/popup-calendar/calendar-setup.js"></script>

<?PHP 
	include IN_PATH.DS."scORTxnRegister.php";
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
	<td valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
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
    		<td class="txtgreenbold13">Daily Cash Collection Report</td>
    		<td>&nbsp;</td>
  		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1">
  		<tr>
    		<td>
    			<form name="frmORRegister" method="post" action="index.php?pageid=95">
				<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo">
        		<tr>
          			<td width="8%">&nbsp;</td>
          			<td width="91%" align="right">&nbsp;</td>
        		</tr>
				<tr>
          			<td height="20" class="padr5" align="right">From Date :</td>
          			<td height="20">
          				<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $fromdate; ?>">
						<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
					</td>				 
				</tr>
				<tr>
          			<td height="20" class="padr5" align="right"">To Date :</td>
          			<td height="20">
          				<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $todate; ?>">	        			
						<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
						<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>
				<tr>
          			<td height="20"  class="padr5" align="right">Search :</td>            
          			<td height="20" align="left">
						<input name="txtSearch" type="text" class="txtfield" id="txtSearch" size="30" value="<?php echo $vSearch; ?>" />
						<input name="btnSearch" type="submit" class="btn" value="Search">						 
		  			</td>
				</tr>
        		<tr>
          			<td colspan="2" height="20">&nbsp;</td>
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
          	<td class="tabmin2 txtredbold">Cash Receipts</td>
          	<td class="tabmin3">&nbsp;</td>
      	</tr>
      	</table>
      	<table width="95%" align="center" border="0" cellpadding="0" cellspacing="1" class="bordergreen">
		<tr>
			<td>
				<table width="100%" align="center" border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10">
		        <tr align="center" class="tab">
		        	<td height="20" class="bdiv_r" align="center" width="10%">Memo No.</td>
		          	<td height="20" class="bdiv_r" align="center" width="10%">Number</td>
		          	<td height="20" class="bdiv_r" align="center" width="10%">IBM/IGS</td>
		          	<td height="20" class="bdiv_r padl5" align="left" width="20%">Name</td>
		          	<td height="20" class="bdiv_r padr5" align="right" width="10%">Cash</td>
		          	<td height="20" class="bdiv_r padr5" align="right" width="10%">Cheque</td>
		          	<td height="20" class="bdiv_r padr5" align="right" width="10%">Deposit Slip</td>
		          	<td height="20" class="bdiv_r padr5" align="right" width="10%">Cancelled</td>
		          	<td height="20" class="padr5" align="right" width="10%">Net</td>
		      	</tr>
		      	</table>
			</td>
		</tr>
		<tr>
			<td valign='top'><div class="scroll_500">
			<table width='100%' align='center' border='0' cellpadding='0' cellspacing='1'>
			<?php
				if($rs_orreg->num_rows != 0)
  				{
  					$net = 0;
  					$cancelled = 0;
  					$tot_cash = 0;
  					$tot_check = 0;
  					$tot_deposit = 0;
  					$tot_cancelled = 0;
  					$tot_net = 0;
  					$cnt = 0;
  					while($row = $rs_orreg->fetch_object()) 
  					{
  						$net = 0;
  						$cancelled = 0;
  						$cnt ++;
						($cnt % 2) ? $class = '' : $class = 'bgEFF0EB';
						$tot_cash += $row->CashAmount;
  						$tot_check += $row->CheckAmount;
  						$tot_deposit += $row->DepositAmount;
  						if ($row->StatusID == 8)
  						{
  							$cancelled = $row->CashAmount + $row->CheckAmount + $row->DepositAmount;
  							$net = 0;  							
  						}
  						else
  						{
  							$cancelled = 0;
  							$net = $row->NetAmount;  							
  						}
  						$tot_cancelled += $cancelled;
  						$tot_net += $net;
							
  						echo "<tr align='center' class='$class'>
						  		<td height='20' align='center' class='borderBR' width='10%'>$row->ORNO</td>
		                      	<td height='20' align='center' class='borderBR' width='10%'>$row->DocumentNo</td>
		                      	<td height='20' align='center' class='borderBR' width='10%'>$row->CustCode</td>
		                      	<td height='20' align='left' class='borderBR padl5' width='20%'>$row->Customer</td>
		                      	<td height='20' align='right' class='borderBR padr5' width='10%'>" . number_format($row->CashAmount, 2) . "</td>
		                      	<td height='20' align='right' class='borderBR padr5' width='10%'>" . number_format($row->CheckAmount, 2) . "</td>
		                      	<td height='20' align='right' class='borderBR padr5' width='10%'>" . number_format($row->DepositAmount, 2) . "</td>
		                      	<td height='20' align='right' class='borderBR padr5' width='10%'>" . number_format($cancelled, 2) . "</td>
		                      	<td height='20' align='right' class='borderBR padr5' width='10%'>" . number_format($net, 2) . "</td>
				  		</tr>";
  					}
  					echo "<tr>
		  						<td colspan='16' height='20' class='borderBR'>&nbsp;</td></tr>
		  					<tr align='center'>
						  		<td height='20' align='center' class='borderBR'>&nbsp;</td>
		                      	<td height='20' align='center' class='borderBR'>&nbsp;</td>
		                      	<td height='20' align='center' class='borderBR'>&nbsp;</td>
		                      	<td height='20' align='right' class='borderBR padr5'><strong>REPORT TOTAL :</strong></td>
		                      	<td height='20' align='right' class='borderBR padr5'><strong>" . number_format($tot_cash, 2) . "</strong></td>
		                      	<td height='20' align='right' class='borderBR padr5'><strong>" . number_format($tot_check, 2) . "</strong></td>
		                      	<td height='20' align='right' class='borderBR padr5'><strong>" . number_format($tot_deposit, 2) . "</strong></td>
		                      	<td height='20' align='right' class='borderBR padr5'><strong>" . number_format($tot_cancelled, 2) . "</strong></td>
		                      	<td height='20' align='right' class='borderBR padr5'><strong>" . number_format($tot_net, 2) . "</strong></td>
				  			</tr>
		  				";
  				}
  				else
  				{
  					echo "<tr align='center'><td height='20' colspan='9' align='center' class='borderBR txtredsbold'>No record(s) to display.</td></tr>";  					
  				}	
			?>
			</table>
			</div></td>
		</tr>
		</table>
      	<br>
     	<table width="95%"  border="0" align="center">
      	<tr>
      		<td height="20" align="center"><a class="txtnavgreenlink" href="index.php?pageid=1"><input name="Button" type="button" class="btn" value="Back"></a>
      			<input name="input" type="button" class="btn" value="Print" onclick="openPopUp('<?php echo $fromdate;?>','<?php echo $todate;?>', '<?php echo $vSearch;?>')"/>
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