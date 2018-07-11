<?PHP 
	include "../initialize.php";
	include  IN_PATH.DS."scPrint.php";
?>
<link rel="stylesheet" type="text/css" href="../css/style.css">
<br />
<table border="0" width="100%">
	<tr align="center">
		<td width="39%" height="90" valign="bottom">&nbsp;
<table width="80%" border="0" align="right" cellpadding="0" cellspacing="0">				
				<tr>
					<td align="left" valign="middle" class="s11"><?php echo $branchname; ?></td>
				</tr>
				<tr>
					<td align="left" valign="middle"><span class="s11">VAT REG: <?php echo $tinno; ?></span></td>
				</tr>
				<tr>
				  <td align="left" valign="middle" class="s11">Permit No. <?php echo $permitno; ?></td>
			  </tr>
				<tr>
					<td align="left" valign="middle"><span class="s11"><?php echo $address; ?></span></td>
			  </tr>
			</table>
        </td>
		<td width="61%">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
				  <td height="24" align="center">&nbsp;</td>
				  <td class="s11">&nbsp;</td>
			  </tr>
				<tr>
					<td width="60%" align="center">&nbsp;</td>
					<td  class="s11"><?php echo $salesinvoice; ?></td>
				</tr>
				<tr>
					<td align="center">&nbsp;</td>
					<td  class="s11"><?php echo $txndate; ?></td>
				</tr>
				<tr>
					<td align="center">&nbsp;</td>
					<td  class="s11"><?php echo $reftxnid; ?></td>
				</tr>
				<tr>
					<td align="center">&nbsp;</td>
					<td  class="s11"><?php echo $serverno; ?></td>
				</tr>
				<tr>
					<td align="center">&nbsp;</td>
					<td  class="s11"><?php echo $pageno; ?></td>
				</tr>
			</table>
		</td>
	</tr>	
</table>
<br />
<table border="0" width="100%">
	<tr align="center">
	  <td width="40%" align="center" style="padding-left:265px;"><table width="100%" border="0" align="left" cellpadding="0" cellspacing="2">
	    <tr>
	      <td height="6"  colspan="2" align="left" nowrap="nowrap"></td>
        </tr>
	    <tr>
	      <td width="68%" align="left" nowrap="nowrap" class="s14"><?php echo $customercode; ?>&nbsp;</td>
	      <td align="left" nowrap="nowrap" class="s14">&nbsp;</td>
        </tr>
	    <tr>
	      <td align="left" nowrap="nowrap" class="s14"><?php echo $customername; ?>&nbsp;</td>
	      <td align="left" nowrap="nowrap" class="s14">&nbsp;</td>
        </tr>
	    <tr>
	      <td align="left" class="s14"><?php echo $customeraddress; ?>&nbsp;</td>
	      <td align="left" nowrap="nowrap" class="s14">&nbsp;</td>
        </tr>
	    <tr>
	      <td align="left" nowrap="nowrap" class="s14"><?php echo $customertin; ?></td>
	      <td align="left" class="s14"><span class="s16"><?php echo $customeribmcode; ?></span></td>
        </tr>
      </table></td>
  </tr>	
</table>
<br />
<br />
<br />
<br />
<br />
<br />
<table width="100%" border="0">
	<tr >
	  <td valign="bottom" style="padding-left:80px;">&nbsp;
		  <?php if($db->affected_rows($rsProductSaleInvoice)){
			  	echo "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>";
					while ($row = $db->fetch_array($rsProductSaleInvoice)) {
						 echo "<tr class='s12'>";
						 echo "<td width='10%' align='left' valign='middle' class='s11'>".$row["ProductCode"]."</td>";
						 echo "<td align='left' valign='middle' class='s11'>".$row["Product"]."</td>";
						 echo "<td width='5%' align='left' valign='middle' class='s11'>".$row["Qty"]."</td>";
						 echo "<td width='10%' align='right' valign='middle' class='s11'>".number_format($row["UnitPrice"],2)."</td>";
						 echo "<td width='10%' align='right' valign='middle' class='s11'>".number_format($row["TotalAmount"],2)."</td>";
						 echo "</tr>";
						 $totalqty += $row["Qty"];
						 $grandtotal += $row["TotalAmount"];
						 
					}
				echo "</table>";
				}	 	 		 	 
		  ?>
      	</td>
  </tr>
	<tr >
	  <td valign="bottom" style="padding-left:80px;">&nbsp;</td>
  </tr>
	<tr >
	  <td valign="bottom" style="padding-left:80px;">
      <table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>
						 <tr class='s12'>
						 <td width='10%' align='left' valign='middle' class='s11'>&nbsp;</td>
						 <td align='left' valign='middle' class='s11'>Total with CPI :</td>
						 <td width='5%' align='left' valign='middle' class='s11'><?php echo $totalqty; ?></td>
						 <td width='10%' align='left' valign='middle' class='s11'>&nbsp;</td>
						 <td width='10%' align='right' valign='middle' class='s11'><?php echo number_format($grandtotal,2); ?></td>
						 </tr>
		</table>
      </td>
  </tr>	
</table>
</body>
</html>