<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" href="../../css/calpopup.css"/>
<?php
	require_once "../../initialize.php";
	include IN_PATH.DS."scPromoPopUpSO.php";

?>
<br>
<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
	<tr>		
	<td  valign="top"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2">
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			<tr>
				<td class="txtredbold">Available Promo</td>
				<td>&nbsp;</td>
			</tr>
			</table>
			</td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
	</table>
	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl1">
	<tr>
	<td valign="top" class="bgF9F8F7">
	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">	
		<tr>
		<td><div class="scroll_300">
				<table width="100%" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 " border="0">
				<tr height="22">
					
					<td width="20%" align="center" class="borderBR" >Promo</td>
					<td width="25%" align="center" class="borderBR">Item</td>
					<td width="5%" align="center" class="borderBR">Type</td>
					<td width="5%" align="center" class="borderBR">QTY</td>
					<td width="10%" align="center" class="borderBR">Regular Price</td>
					<td width="10%" align="center" class="borderBR">CSP</td>
					<td width="10%" align="center" class="borderBR">Total Price</td>
					<td width="10%" align="center" class="borderB" >Total Savings</td>			
				</tr>
				<?php
					if($rsGetPromoList->num_rows)
					{
						$tmpID = 0;
						while($row = $rsGetPromoList->fetch_object())
						{
							$Promo = "";
							if($row->AvailmentType == 1)
							{
								$type = "B";
							}
							if($row->AvailmentType == 2)
							{
								$type = "E";
							}
							if($row->AvailmentType == 3)
							{
								$type = "B/E";
							}
							if($tmpID  != $row->PromoID)
							{
								$Promo = $row->Description;
							}
							else
							{
								$Promo = "";
							}
							$regprice = number_format($row->Price,'2','.','');
							$effprice = number_format($row->EffectivePrice,'2','.','');
							$tmptotalprice = $effprice * $row->Qty;
							$totalprice	= number_format($tmptotalprice,'2','.','');
							$tmptotalsavings =  ($regprice - $effprice) * $row->Qty;
							$totalsavings	= number_format($tmptotalsavings,'2','.','');
							echo "
							<tr height=\"22\">
								<td width=\"20%\" align=\"left\" class=\"borderBR\" >&nbsp;$Promo</td>
								<td width=\"30%\" align=\"left\" class=\"borderBR\">&nbsp;$row->ProdCode - $row->ProdName</td>
								<td width=\"5%\" align=\"left\" class=\"borderBR\">&nbsp;$type</td>
								<td width=\"5%\" align=\"center\" class=\"borderBR\">$row->Qty</td>
								<td width=\"10%\" align=\"center\" class=\"borderBR\">$regprice</td>
								<td width=\"10%\" align=\"center\" class=\"borderBR\">$effprice</td>
								<td width=\"10%\" align=\"center\" class=\"borderBR\">$totalprice</td>
								<td width=\"10%\" align=\"center\" class=\"borderB\">$totalsavings</td>
							</tr> ";
							$tmpID =  $row->PromoID;
						}
					}
				?>
				</table>
			</div></td></tr></table>
	</td>
	</tr>
	</table>
	
	</td>
	</tr>
</table>