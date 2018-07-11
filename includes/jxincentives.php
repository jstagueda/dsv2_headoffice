<?php	
	require_once "../initialize.php";
?>
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script language="javascript">
function cancel()
{
	$j('#incentive').dialog('close');
}
function enableQty(chkInclude)
{
	var ctr = chkInclude.value;
	txtQtyString =  'txtIncentiveQty' + ctr;
	var txtQty	= document.getElementById(txtQtyString);
	if(chkInclude.checked == true)
	{
		txtQty.readOnly = false;
	}
}
function applyincentives(promoID,qty,flag)
{
		
	//alert(promoID);
	$j.ajax({
		   type: 'POST',
		   url: 'includes/jxApplyIncentive.php?mode=post&promo='+promoID +'&qty='+qty+'&flag='+flag,		   
		   success: function(outAddtlDisc){ },
	    dataType: 'text'
	 });

}
function updateMaxAvailment(promoID,qty)
{
	$j.ajax({
		   type: 'POST',
		   url: 'includes/jxUpdateAvailments.php?mode=post&promo='+promoID +'&qty='+qty,		   
		   success: function(outAddtlDisc){ },
	    dataType: 'text'
	 });
}
function insertToTable()
{
	var cntString 		= 'hcount';
	var cnt	= document.getElementById(cntString);

	var tmpholder	=	'flagincentive';
	var flagholder = document.getElementById(tmpholder);
	
	var ctr = cnt.value  - 1;
	//alert(ctr);
	var tmppromoID =0 ;
	var  qtyperpromo = 0;
	for(i=0 ; i<=ctr ; i++)
	{
		var chkboxString	= 'chkInclude'+i;
		var chkbox	= document.getElementById(chkboxString);
		if(chkbox.checked == true)
		{
			var hmaxAvailmentString		= 'hMaxAvailment'+i;
			var prodIDString			= 'hprodIDIncentive'+i;
			var prodCodeString			= 'hprodCodeIncentive'+i;	
			var prodNameString			= 'hprodNameIncentive'+i;
			var hProductTypeString		= 'hProductTypeIncentive'+i;
			var promotypeString			= 'hpromotypeIncentive'+i;
			var PromoIDString			= 'hPromoIDIncentive'+i;
			var PMGString				= 'hPMGIncentive'+i;
			var PMGIDString				= 'hPMGIDIncentive'+i;
			var SOHString				= 'hSOHIncentive'+i;
			var InTransitString			= 'hInTransitIncentive'+i;
			var hEffectivePriceString	= 'hEffectivePriceIncentive'+i;
			var hUnitPriceString		= 'hUnitPriceIncentive'+i;	
			var hPromoCodeString		= 'hPromoCodeIncentive'+i;
			var QtyString				= 'txtIncentiveQty'+i;			

			var	hMaxAvailment	= document.getElementById(hmaxAvailmentString);
			var iprodID			= document.getElementById(prodIDString);
			var iprodCode		= document.getElementById(prodCodeString);
			var prodName		= document.getElementById(prodNameString);
			var ProductType		= document.getElementById(hProductTypeString);
			var promotype		= document.getElementById(promotypeString);
			var PromoID			= document.getElementById(PromoIDString);
			var PMG				= document.getElementById(PMGString);
			var PMGID			= document.getElementById(PMGIDString);
			var iSOH			= document.getElementById(SOHString);
			var InTransit		= document.getElementById(InTransitString);
			var EffectivePrice	= document.getElementById(hEffectivePriceString);
			var UnitPrice		= document.getElementById(hUnitPriceString);
			var PromoCode		= document.getElementById(hPromoCodeString);
			var Qty				= document.getElementById(QtyString);

			//alert(tmppromoID);
			//alert(PromoID.value);
			if(Qty.value > hMaxAvailment.value)
			{
				alert("Quantity should be less than or equal the available SOH.");
				Qty.select();
				return false;
			}
			if(tmppromoID == PromoID.value)
			{
				qtyperpromo = eval(qtyperpromo) + eval(Qty.value);
				//alert(qtyperpromo);
				//alert(iSOH.value);
				if(qtyperpromo > hMaxAvailment.value)
				{
					alert('Qty should be less than maximum availment.');
					return false;
							
				}				
			}
			else
			{
				qtyperpromo = 0;
				tmppromoID = PromoID.value;
				qtyperpromo = eval(qtyperpromo) + eval(Qty.value);
			}
			
		}
	}
	for(i = 0 ; i<=ctr ; i++)
	{
		var chkboxString	= 'chkInclude'+i;
		var chkbox	= document.getElementById(chkboxString);
		if(chkbox.checked == true)
		{
			var hmaxAvailmentString		= 'hMaxAvailment'+i;
			var prodIDString			= 'hprodIDIncentive'+i;
			var prodCodeString			= 'hprodCodeIncentive'+i;	
			var prodNameString			= 'hprodNameIncentive'+i;
			var hProductTypeString		= 'hProductTypeIncentive'+i;
			var promotypeString			= 'hpromotypeIncentive'+i;
			var PromoIDString			= 'hPromoIDIncentive'+i;
			var PMGString				= 'hPMGIncentive'+i;
			var PMGIDString				= 'hPMGIDIncentive'+i;
			var SOHString				= 'hSOHIncentive'+i;
			var InTransitString			= 'hInTransitIncentive'+i;
			var hEffectivePriceString	= 'hEffectivePriceIncentive'+i;
			var hUnitPriceString		= 'hUnitPriceIncentive'+i;	
			var hPromoCodeString		= 'hPromoCodeIncentive'+i;
			var QtyString				= 'txtIncentiveQty'+i;			

			var	hMaxAvailment	= document.getElementById(hmaxAvailmentString);
			var iprodID			= document.getElementById(prodIDString);
			var iprodCode		= document.getElementById(prodCodeString);
			var prodName		= document.getElementById(prodNameString);
			var ProductType		= document.getElementById(hProductTypeString);
			var promotype		= document.getElementById(promotypeString);
			var PromoID			= document.getElementById(PromoIDString);
			var PMG				= document.getElementById(PMGString);
			var PMGID			= document.getElementById(PMGIDString);
			var iSOH			= document.getElementById(SOHString);
			var InTransit		= document.getElementById(InTransitString);
			var EffectivePrice	= document.getElementById(hEffectivePriceString);
			var UnitPrice		= document.getElementById(hUnitPriceString);
			var PromoCode		= document.getElementById(hPromoCodeString);
			var Qty				= document.getElementById(QtyString);
			
			var table = document.getElementById('dynamicTable');
			var index = table.rows.length  ;
			//alert(table.rows.length);
			var prodCode		= eval('document.frmCreateSalesOrder.txtProdCode' + index);
			
			var prodID			= eval('document.frmCreateSalesOrder.hProdID' + index);
			var prodDesc		= eval('document.frmCreateSalesOrder.txtProdDesc' + index);
			var pmgCode			= eval('document.frmCreateSalesOrder.txtPMG' + index);
			var pmgid 			= eval('document.frmCreateSalesOrder.hPMGID' + index);
			var unitprice		= eval('document.frmCreateSalesOrder.txtUnitPrice' + index);
			var effectiveprice	= eval('document.frmCreateSalesOrder.txtEffectivePrice' + index);
			var producttype 	= eval('document.frmCreateSalesOrder.hProductType' + index);
			var hSOH 			= eval('document.frmCreateSalesOrder.hSOH' + index);
			var hTransit 		= eval('document.frmCreateSalesOrder.hTransit' + index);
			var hPromoID 		= eval('document.frmCreateSalesOrder.hPromoID' + index);
			var hPromoType 		= eval('document.frmCreateSalesOrder.hPromoType' + index);
			var hForIncentive	= eval('document.frmCreateSalesOrder.hForIncentive' + index);
			var totalPrice 		= eval('document.frmCreateSalesOrder.txtTotalPrice' + index);
			var orderedQty 		= eval('document.frmCreateSalesOrder.txtOrderedQty' + index);
			var served 			= eval('document.frmCreateSalesOrder.hServed' + index)
			
			var tmpSOH 			= 'divSOH' + index;
			var tmpTransit		= 'divTransit' + index;
			var tmpPromo		= 'divPromo' + index;
			var SOH				= document.getElementById(tmpSOH);
			var Transit			= document.getElementById(tmpTransit);
			var Promo			= document.getElementById(tmpPromo);				
			
			if(Trim(Qty.value) == "")
			{
				alert("Quantity should be less than or equal the available SOH.");
				return false;				
			}			
			else
			{
				if(eval(Qty.value) < (iSOH.value))
				{
					addRow();
					prodCode.value		= iprodCode.value;
					prodID.value		= iprodID.value;
					prodDesc.value		= prodName.value;
					pmgCode.value		= PMG.value;
					SOH.innerHTML		= iSOH.value;
					Transit.innerHTML 	= InTransit.value;
					Promo.innerHTML 	= PromoCode.value;
					producttype.value	= ProductType.value;
					hSOH.value			= iSOH.value;
					hTransit.value		= InTransit.value;
					pmgid.value			= PMGID.value;
					hPromoID.value		= PromoID.value;
					hPromoType.value	= promotype.value;
					hForIncentive.value	= "1";
					tmpunitprice		= eval(UnitPrice.value);
					tmpEffectivePrice	= eval(EffectivePrice.value);
					unitprice.value		= tmpunitprice.toFixed(2);
					effectiveprice.value= tmpEffectivePrice.toFixed(2);
					tmpTotalPrice		= tmpEffectivePrice.toFixed(2) * eval(Qty.value);
					totalPrice.value 	= tmpTotalPrice.toFixed(2);
					orderedQty.value	= Qty.value;
					served.value		= 1;

					
					//updateMaxAvailment(PromoID.value,Qty.value,flagholder.value);
					
					
				}	
				else
				{
					alert("Quantity should be less than or equal the available SOH.");
					return false;
				}
			}
		}
	}	
	
	applyincentives(PromoID.value,Qty.value,flagholder.value);
	calculatetotals();	
	//addRow();
	$j('#incentive').dialog('close');
}

</script>
<?php
global $database;
$sessionID= session_id();	
$flagincentive = $_GET['flag'];
	echo '<input type="hidden" name="flagincentive" id="flagincentive"value="'.$flagincentive.'">';
	echo '<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" >	
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="1" border="0">
				<tr height="20" >
					<td width="2%" align="center" >&nbsp;</td>	
					<td width="25%" align="center">&nbsp;</td>
					<td width="10%" align="left" ><strong>Max Availments</strong></td>
					<td width="15%" align="left" ><strong>Buyin Criteria</strong></td>
					<td width="10%" align="left" ><strong>Minimum</strong></td>
					<td width="10%" align="left" ><strong>Offer Price</strong></td>
					<td width="8%" align="center" ><strong>Qty</strong></td>						
				</tr>
				</table>
			</td>
		</tr>';
		/*<td width="10%" align="left" ><strong>Accumulated</strong></td>	
					<td width="12%" align="left" ><strong>Unavailed</strong></td>*/
		echo '<tr>
				<td>
				<div class="scroll_400"><table width="100%" cellpadding="0" cellspacing="1" border="0">';
	$rsIncentiveHeader = $sp->spSelectIncentivesHeader($database,$sessionID);
	$cnt = 0;
	if($rsIncentiveHeader->num_rows)
	{
		
		while($row = $rsIncentiveHeader->fetch_object())
		{
			$PromoID = $row->promoid;
			echo '
				<tr height="20" >	
					<td width="2%" align="center" class="borderB padl5">&nbsp;</td>				
					<td width="25%" align="left" class="borderBR padl5" ><strong>'.$row->Description.'<strong></td>
					<td width="10%" align="left" class="borderBR padl5">&nbsp;</td>
					<td width="15%" align="left" class="borderBR padl5">&nbsp;</td>
					<td width="10%" align="left" class="borderBR padl5">&nbsp;</td>
					<td width="10%" align="left" class="borderBR padl5">&nbsp;</td>
					<td width="8%" align="center" class="borderBR padl5">&nbsp;</td>
					
				</tr>';
				/*<td width="10%" align="right" class="borderBR padl5">'.$row->Balance.'&nbsp;</td>	
					<td width="10%" align="left" class="borderBR padl5">&nbsp;</td>	*/
			$rsIncentiveDetails = $sp->spSelectIncentiveEntitlementDetails($database,$sessionID,$PromoID);
			if($rsIncentiveDetails->num_rows)
			{
				while($row2 = $rsIncentiveDetails->fetch_object())
				{
					echo '
						<tr height="22" >
							<td width="2%" align="left" class="borderB padl5"><input name="chkInclude'.$cnt.'" id ="chkInclude'.$cnt.'"    type="checkbox" onclick="return enableQty(this);" value="'.$cnt.'" ></td>						
							<td width="25%" align="left" class="borderBR padl5" >'.$row2->prodDesc.'</td>
							<td width="10%" align="left" class="borderBR padl5">'.$row2->MaxAvailments.'</td>
							<td width="15%" align="left" class="borderBR padl5">'.$row2->Criteria.'</td>
							<td width="10%" align="right" class="borderBR padl5">'.$row2->Minimum.'&nbsp;</td>
							<td width="10%" align="right" class="borderBR padl5">'.$row2->offerprice.'&nbsp;</td>
							<td width="8%" align="center" class="borderBR padl5"><input type="text" class="txtfield5"  style="text-align:right" readonly name="txtIncentiveQty'.$cnt.'" id="txtIncentiveQty'.$cnt.'"></td>
							
							<input type="hidden" name="hpromotypeIncentive'.$cnt.'" id="hpromotypeIncentive'.$cnt.'"value="'.$row2->promotype.'">
							<input type="hidden" name="hPromoIDIncentive'.$cnt.'" id="hPromoIDIncentive'.$cnt.'"value="'.$row2->PromoID.'">
							<input type="hidden" name="hprodNameIncentive'.$cnt.'" id="hprodNameIncentive'.$cnt.'"value="'.$row2->prodName.'">
							<input type="hidden" name="hprodCodeIncentive'.$cnt.'" id="hprodCodeIncentive'.$cnt.'"value="'.$row2->prodCode.'">
							<input type="hidden" name="hprodIDIncentive'.$cnt.'" id="hprodIDIncentive'.$cnt.'"value="'.$row2->prodID.'">	
							<input type="hidden" name="hPMGIncentive'.$cnt.'" id="hPMGIncentive'.$cnt.'"value="'.$row2->PMG.'">							
							<input type="hidden" name="hPMGIDIncentive'.$cnt.'" id="hPMGIDIncentive'.$cnt.'"value="'.$row2->pmgid.'">
							<input type="hidden" name="hSOHIncentive'.$cnt.'" id="hSOHIncentive'.$cnt.'"value="'.$row2->soh.'">
							<input type="hidden" name="hInTransitIncentive'.$cnt.'" id="hInTransitIncentive'.$cnt.'"value="'.$row2->Intransit.'">
							<input type="hidden" name="hEffectivePriceIncentive'.$cnt.'" id="hEffectivePriceIncentive'.$cnt.'"value="'.$row2->offerprice.'">
							<input type="hidden" name="hProductTypeIncentive'.$cnt.'" id="hProductTypeIncentive'.$cnt.'"value="'.$row2->ProductTypeID.'">
							<input type="hidden" name="hUnitPriceIncentive'.$cnt.'" id="hUnitPriceIncentive'.$cnt.'"value="'.$row2->unitprice.'">
							<input type="hidden" name="hPromoCodeIncentive'.$cnt.'" id="hPromoCodeIncentive'.$cnt.'"value="'.$row2->PromoCode.'">
							<input type="hidden" name="hMaxAvailment'.$cnt.'" id="hMaxAvailment'.$cnt.'"value="'.$row2->MaxAvailments.'">
						</tr>';
						/*<td width="10%" align="left" class="borderBR padl5">&nbsp;</td>	
						<td width="12%" align="left" class="borderBR padl5">&nbsp;</td>	*/
						$cnt ++ ;
				}
			}
		}
		
	}
	echo '<input type="hidden" name="hcount" id="hcount"value="'.$cnt.'">' ;
	echo '</table></div></td></tr>
	<tr>
		<td><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td height="20"><div align="center">
						<input type="submit" value="Add" class="btn"  onclick="return insertToTable();"/> &nbsp;&nbsp;&nbsp;
						<input type="submit" value="Cancel" class="btn"  onclick="return cancel();"/>
			</td>
		</tr></td>		
	</tr>	
	</table>';
?>