<?PHP 
	include "../initialize.php";
	include  IN_PATH.DS."scPrint2.php";
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
					<td  class="s11"><?php echo $serversn; ?></td>
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
	      <td align="left" nowrap="nowrap" class="s14"><?php echo $customeraddress; ?>&nbsp;</td>
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><table width="94%" border="0" align="right" cellpadding="0" cellspacing="0" class="s11">
      <tr class="s12">
        <td width="8%" align="left" valign="middle" class="s14"><span class="s11">Total</span></td>
        <td align="left" valign="middle" class="s14"><span class="s11">CSP Less (CPI)</span></td>
        <td width="5%" align="left" valign="middle" class="s14">&nbsp;</td>
        <td width="20%" align="left" valign="middle" class="s14">&nbsp;</td>
        <td width="10%" align="right" valign="middle" class="s14"><span class="s11"><?php echo number_format($totalLessCPI,2); ?></span></td>
      </tr>
      <tr class="s12">
        <td align="left" valign="middle" class="s14"><span class="s11">Less :</span></td>
        <td align="left" valign="middle" class="s14"><span class="s11">Basic Discount</span></td>
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="right" valign="middle" class="s14"><span class="s11"><?php echo number_format($basicdisc,2); ?></span></td>
      </tr>
      <tr class="s12">
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="left" valign="middle" class="s14"><span class="s11">Additional Discount</span></td>
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="right" valign="middle" class="s14"><span class="s11"><?php echo number_format($addDiscount,2); ?></span></td>
      </tr>
      <tr class="s12">
        <td colspan="2" align="left" valign="middle" class="s14"><span class="s11">Sales with VAT</span></td>
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="right" valign="middle" class="s14"><span class="s11"><?php echo number_format($saleswithvat,2); ?></span></td>
      </tr>
      <tr class="s12">
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="left" valign="middle" class="s14"><span class="s11">12% VAT</span></td>
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="right" valign="middle" class="s14"><span class="s11"><?php echo number_format($vat,2); ?></span></td>
        <td align="right" valign="middle" class="s14">&nbsp;</td>
      </tr>
      <tr class="s12">
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="left" valign="middle" class="s14"><span class="s11">Vatable Sales</span></td>
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="right" valign="middle" class="s14"><span class="s11"><?php echo number_format($vatable, 2); ?></span></td>
        <td align="right" valign="middle" class="s14">&nbsp;</td>
      </tr>
      <tr class="s12">
        <td align="left" valign="middle" class="s14"><span class="s11">Less :</span></td>
        <td align="left" valign="middle" class="s14"><span class="s11">Additional Discount on Previous Purchase</span></td>
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="right" valign="middle" class="s14">&nbsp;</td>
        <td align="right" valign="middle" class="s14"><span class="s11"><?php echo number_format($adddiscprev , 2); ?></span></td>
      </tr>
      <tr class="s12">
        <td colspan="2" align="left" valign="middle" class="s14"><span class="s11">Total Invoice Amount Due</span></td>
        <td align="left" valign="middle" class="s14">&nbsp;</td>
        <td align="right" valign="middle" class="s14">&nbsp;</td>
        <td align="right" valign="middle" class="s14"><span class="s11"><?php echo number_format($netamount, 2); ?></span></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table width="94%" border="0" align="right" cellpadding="0" cellspacing="8" style="border:1px solid #000">
      <tr align="center">
        <td width="90%" align="left" valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td colspan="7"><span class="s11">This Invoice ( in DGS ) :</span></td>
          </tr>
          <tr>
            <td width="20%" nowrap="nowrap"><span class="s11">Total CFT :</span></td>
            <td align="right"><span class="s11"><?php echo number_format($CFT, 2); ?></span></td>
            <td width="2%">&nbsp;</td>
            <td width="25%"><span class="s11">MTD CFT : </span></td>
            <td align="right"><span class="s11"><?php echo number_format($CFT, 2); ?></span></td>
            <td width="5%">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><span class="s11">Total NCFT :</span></td>
            <td align="right"><span class="s11"><?php echo number_format($NCFT, 2); ?></span></td>
            <td>&nbsp;</td>
            <td><span class="s11">MTD NCFT :</span></td>
            <td align="right"><span class="s11"><?php echo number_format($NCFT, 2); ?></span></td>
            <td>&nbsp;</td>
            <td nowrap="nowrap"><span class="s11"></span></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><span class="s11">TOT TW in NCFT :</span></td>
            <td align="right"><span class="s11"><?php echo number_format($CFT, 2); ?></span></td>
            <td>&nbsp;</td>
            <td nowrap="nowrap"><span class="s11">TOT MTD TW in NCFT :</span></td>
            <td align="right"><span class="s11"><?php echo number_format($NCFT, 2); ?></span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><span class="s11">YTD TW in NCFT :</span></td>
            <td align="right"><span class="s11"><?php echo number_format($NCFT, 2); ?></span></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>
<br />
<br />
<table width="98%" align="center" cellpadding="0" cellspacing="0" style="border:1px solid #000">
<tr>
	<td width="30%" valign="top">
		<!--CREDIT LINE-->
		<table width="98%" align="left" cellpadding="0" cellspacing="0"  style="border:1px dashed  #000">
			<tr height="20">
				<td width="50%" align="right"><span class="s11">CREDIT LINE</span></td>
				<td width="50%" align="right"><span class="s11">0.00</span></td>
			</tr>
			<tr height="20">
				<td width="50%" align="right"><span class="s11">LESS PURCHASE</span></td>
				<td width="50%" align="right"><span class="s11">0.00</span></td>
			</tr>
			<tr height="20">
				<td width="50%" align="right"><span class="s11">AVAILABLE CL</span></td>
				<td width="50%" align="right"><span class="s11">0.00</span></td>
			</tr>
			<tr height="20">
				<td width="50%" align="right"><span class="s11">CREDIT LINE</span></td>
				<td width="50%" align="right"><span class="s11">0.00</span></td>
			</tr>
			<tr height="20">
				<td width="50%" align="right"><span class="s11">UPCOMING DUES</span></td>
				<td width="50%" align="right"><span class="s11">&nbsp;</span></td>
			</tr>
			<tr height="20">
				<td width="50%%" align="right"><span class="s11">CREDIT LINE</span></td>
				<td width="50%" align="right"><span class="s11">0.00</span></td>
			</tr>			
		</table>
	</td>
	<td width="30%" valign="top">
		<!--CREDIT LINE-->
		<table width="98%" align="center" cellpadding="0" cellspacing="0"  style="border:1px dashed #000">
			<tr height="20">
				<td width="30%" align="right"><span class="s11">SUMMARY</span></td>
				<td width="40" align="right"><span class="s11">THIS INVOICE</span></td>
				<td width="40" align="right"><span class="s11">MTD</span></td>
			</tr>
			<tr height="20">
				<td width="30%" align="right"><span class="s11">Cust Sel Price</span></td>
				<td width="40" align="right"><span class="s11">0.00</span></td>
				<td width="40" align="right"><span class="s11">0.00</span></td>
			</tr>
			<tr height="20">
				<td width="30%" align="right"><span class="s11">Basic Discount</span></td>
				<td width="40" align="right"><span class="s11">0.00</span></td>
				<td width="40" align="right"><span class="s11">0.00</span></td>
			</tr>
			<tr height="20">
				<td width="30%" align="right"><span class="s11">Disc Gr Sales</span></td>
				<td width="40" align="right"><span class="s11">0.00</span></td>
				<td width="40" align="right"><span class="s11">0.00</span></td>
			</tr>
			<tr height="20">
				<td width="30%" align="right"><span class="s11">Add'l Discount</span></td>
				<td width="40" align="right"><span class="s11">0.00</span></td>
				<td width="40" align="right"><span class="s11">0.00</span></td>
			</tr>
			<tr height="20">
				<td width="30%" align="right"><span class="s11">Add'l DPP</span></td>
				<td width="40" align="right"><span class="s11">0.00</span></td>
				<td width="40" align="right"><span class="s11">0.00</span></td>
			</tr>			
		</table>
	</td>
	<td width="30%" valign="top">
		<!--CREDIT LINE-->
		<table width="98%" align="center" cellpadding="0" cellspacing="0"  style="border:1px dashed #000">
			<tr height="20">
				<td align="right"><span class="s11">*THANK YOU FOR PATRONIZING OUR PRODUCTS* DELAYED PAYMENTS SUBJECT TO PENALTY* 
				AVAIL OF BIG PROMO OFFERS AND DISCOUNTS*</span></td>				
			</tr>
			<tr valign="top">
				<td width="30%" align="left"><span class="s11">_________________________________________</span></td>				
			</tr>
			<tr height="20">
				<td width="30%" align="left"><span class="s11">
				<p>
				Realesed by:__________________________
				Received the above items in good order 
				and conditon. I also agree to the terms
				stated at the back og this invoice
				________________________________________
				Signature over Printed Name/Date</p></span></td>			
			</tr>
				
		</table>
	</td>
</tr>
</table>
<br />
<?PHP if($printctr > 1) {
?>
<table width="95%" align="center">
<tr>
	<td><span class="s11"><strong>**Duplicate Copy - <?php echo $printctr;?> copies printed.</strong></span></td>
</tr>
</table>
<?PHP }
?>
<br />

</body>
</html>