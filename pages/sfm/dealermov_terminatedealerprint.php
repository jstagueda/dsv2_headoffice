<html>
<head>
	<title>Candidates for Termination</title>
</head>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<body>
<?PHP 
	require_once "../../initialize.php";	
	include IN_PATH.DS."scDealerTerminate.php";	
	
	 $customerRows = '';
	if ($rs_customerterminate->num_rows)
	{
		while ($row = $rs_customerterminate->fetch_object())
		{
			$amount = number_format($row->PastDueAmount, 2, '.', ',');
		   $customerRows .= "<tr align='center'> 
					  <td height='22' align='center'>&nbsp;<span class='txt10'>$row->Code</span></td>
					  <td height='22' align='center'>&nbsp;<a href='index.php?pageid=47' class='txtnavgreenlink'>$row->Name</a></td>
					  <td height='22' align='center'>&nbsp;<span class='txt10'>$row->IBMCode</span></td>
					  <td height='22' align='center'>&nbsp;<span class='txt10'>$row->ContactNo</span></td>
					  <td height='22' align='center'>&nbsp;<span class='txt10'></span></td>
					  <td height='22' align='center'>&nbsp;<span class='txt10'>$amount</span></td></tr>";
		}
		$rs_customerterminate->close();
	}
	else
	{
		$customerRows = "<tr align='center'><td height='20' class='borderBR'><span class='txt10 txtreds'>No record(s) to display. </span></td></tr>";
	}
?>

<br />
<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>	
        <td>
        	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
	            <tr>
	              	<td height="20"><strong>Candidates for Termination</strong></td>
	              	<td>&nbsp;</td>
	            </tr>
          	</table>
		</td>        
	</tr>
</table>  

<br />
<table width="95%" border="1" align="center" cellpadding="0" cellspacing="0" style="border-width:thin;border-color:black;">
	<tr>
		<td>
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
              	<tr>
	                <td height="20"><strong>&nbsp;Dealer Information</strong></td>
	               	<td>&nbsp;</td>
              	</tr>
          	</table>
		</td>
	</tr>
</table>
     
<table width="95%"  border="1" align="center" cellpadding="0" cellspacing="0" style="border-width:thin;border-color:black;" id="tbl3">
	<tr>
		<td valign="top" width="1020">
          	<table width="100%"  border="0" cellpadding="0" cellspacing="1" height="25">			
				<tr align="center">
			    	<td width="10%"><strong>IGS Code</strong></td>
			   		<td width="20%"><strong>IGS Name</strong></td>
					<td width="25%"><strong>IBM Name</strong></td>
					<td width="15%"><strong>IGS Contact Number</strong></td>
					<td width="15%"><strong>Last PO Date</strong></td>
					<td width="15%"><strong>Past Due Amount</strong></td>
				</tr>
					<?php echo $customerRows; ?>
				<tr><td heigth="10"></td></tr>
			</table>
		</td>
	</tr>
</table>


<br>
</body>
</html>