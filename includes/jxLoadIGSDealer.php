<?php
   require_once "../initialize.php";
   global $database;

	$custid = $_GET['custid'];
	
	$rs_dealers = $sp->spSelectIGSDealersByIBMID($database, $custid);
	
	echo "<table width='95%'  border='0' cellspacing='0' align='center' cellpadding='0' class='bordergreen' id='tbl3'>
			<tr class='bgF9F8F7'>
				<td class='tab'>
					<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0' class='txtdarkgreenbold10'>
					<tr>
						<td width='5%' height='20' align='center' class='bdiv_r'><input type='checkbox' id='chkAll' name='chkAll' onclick='checkAll(this.checked);'></td>
						<td width='15%' height='20' align='left' class='bdiv_r padl5'>IGS Code</td>
						<td width='25%' height='20' align='left' class='bdiv_r padl5'>IGS Name</td>
						<td width='15%' height='20' align='right' class='bdiv_r padr5'>SI Balance</td>
						<td width='15%' height='20' align='right' class='bdiv_r padr5'>Penalties</td>
						<td width='15%' height='20' align='right' class='bdiv_r padr5'>Total Outstanding</td>
						<td width='10%' height='20' align='center'>Amount</td>																				
					</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<div class='scroll_150'>
					<table width='100%' align='center' cellpadding='0' cellspacing='0'>";
							if ($rs_dealers->num_rows)
							{
								$index = 0;
								while ($row = $rs_dealers->fetch_object())
								{
									$index += 1;
									$si_amt = number_format($row->OutstandingBalance, 2, '.', '');
									$pen_amt = number_format($row->OutstandingAmount, 2, '.', '');
									$tot_amt = number_format($row->TotalOutstanding, 2, '.', '');
						echo "<tr>
							<td width='5%' height='20' align='center' class='borderBR'>
								<input type='hidden' name='hdnCustID$index' value='$row->CustomerID'>
								<input type='checkbox' name='chkSelect[]' id='chkSelect' value='$index'>
								<input type='hidden' name='hdnTotOut$index' value='$tot_amt'>
							</td>
							<td width='15%' height='22' align='left' class='borderBR padl5'>$row->CustCode</td>
							<td width='25%' height='22' align='left' class='borderBR padl5'>$row->CustName</td>
							<td width='15%' height='22' align='right' class='borderBR padr5'>$si_amt</td>
							<td width='15%' height='22' align='right' class='borderBR padr5'>$pen_amt</td>
							<td width='15%' height='22' align='right' class='borderBR padr5'>$tot_amt</td>
							<td width='10%' height='22' align='center' class='borderBR'><input name='txtIGSAmt$index' type='text' class='txtfield' style='text-align:right; width:100px;' value='' size='20' maxlength='20' onkeyup='return calculateIGSAmt();'></td>																				
						</tr>";																			
								}
								$rs_dealers->close();																
							}
							else
							{
								echo "<tr><td colspan='7' align='center'><font color='#FF0000'><b>No record(s) to display.<b></font></td></tr>";								
							}
						echo "<tr>
							<td colspan='7' height='20'>&nbsp;</td>
						</tr>
						<tr>	
							<td colspan='5' height='20'>&nbsp;</td>
							<td height='20' align='right' class='padr5'><strong>Total Amount </strong></td>
							<td height='20' align='center'><input name='txtIGSTotAmt' type='text' class='txtfield' style='text-align:right; width:100px;' value='0.00' size='20' maxlength='20' readonly='yes'></td>
						</tr>
					</table>
					</div>
				</td>
			</tr>
			</table>";
?>