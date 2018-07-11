<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<script language="javascript">
function substitute(index)
{
	var prodList =	document.getElementsByName('rdProduct');
	if(prodList)
	{
	var len = prodList.length ;
	
	if(len)
	{
		
		
		for(i=0; i<=len ; i++ )
		{
			
			if(prodList[i].checked == true)
			{
				//pop-up fields
				var prodCodeString	= 'hProdCodeSubstitute'+i;
				var prodDescString	= 'hProdDescSubstitute'+i;
				var pmgString		= 'hPMGSubstitute'+i;
				var sohString		= 'hSOHSubstitute'+i;
				var intransitString	= 'hInTransitSubstitute'+i;
				var unitpriceString	= 'hUnitPriceSubstitute'+i;
				var prodTypeString	= 'hProductTypeSubstitute'+i;
				var pmgidString		= 'hPMGIDSubstitute'+i;
				var hasSubstituteString	= 'hHasSubstitute'+i;
				
				var subsprodCode	= document.getElementById(prodCodeString);
				var subsprodDesc	= document.getElementById(prodDescString);
				var subspmg			= document.getElementById(pmgString);
				var subspmgid		= document.getElementById(pmgidString);
				var subssoh			= document.getElementById(sohString);
				var subsintransit	= document.getElementById(intransitString);
				var subsunitprice	= document.getElementById(unitpriceString);
				var subsprodType	= document.getElementById(prodTypeString);
				var hasSubstitute	= document.getElementById(hasSubstituteString);
				var subsprodID		= prodList[i].value;
				
				//dynamic table fields				
				var prodCode		= eval('document.frmCreateSalesOrder.txtProdCode' + index);
				var prodID			= eval('document.frmCreateSalesOrder.hSubsID' + index);
				var prodDesc		= eval('document.frmCreateSalesOrder.txtProdDesc' + index);
				var pmgCode			= eval('document.frmCreateSalesOrder.txtPMG' + index);
				var pmgid 			= eval('document.frmCreateSalesOrder.hPMGID' + index);
				var unitprice		= eval('document.frmCreateSalesOrder.txtUnitPrice' + index);
				var effectiveprice	= eval('document.frmCreateSalesOrder.txtEffectivePrice' + index);
				var producttype 	= eval('document.frmCreateSalesOrder.hProductType' + index);
				var hSOH 			= eval('document.frmCreateSalesOrder.hSOH' + index);
				var hTransit 		= eval('document.frmCreateSalesOrder.hTransit' + index);
				var tmpSOH 			= 'divSOH' + index;
				var tmpTransit		= 'divTransit' + index;
				var SOH				= document.getElementById(tmpSOH);
				var Transit			= document.getElementById(tmpTransit);			
				prodCode.value		= subsprodCode.value;
				prodID.value		= subsprodID;
				prodDesc.value		= subsprodDesc.value;
				pmgCode.value		= subspmg.value;
				SOH.innerHTML		= subssoh.value;
				Transit.innerHTML 	= subsintransit.value;
				producttype.value	= subsprodType.value;
				hSOH.value			= subssoh.value;
				hTransit.value		= subsintransit.value;
				pmgid.value			= subspmgid.value;
				//tmpunitprice		= eval(subsunitprice.value);
				//unitprice.value		= tmpunitprice.toFixed(2);
				//effectiveprice.value= tmpunitprice.toFixed(2);
				
				if(hasSubstitute.value == 1)
				{
					
					SOH.setAttribute('onclick', 'substituteItem('+subsprodID+','+index+','+subssoh.value+')');
					SOH.setAttribute('onMouseover', 'this.style.cursor = "pointer"');
					SOH.style.color = "blue";
					SOH.style.fontWeight  = "bold";
				}
				else
				{
					
					 SOH.setAttribute('onclick', '');
					 SOH.setAttribute('onMouseover', 'this.style.cursor = "default"');
					SOH.style.color = "black";
					SOH.style.fontWeight  = "normal";
					//SOH.style.fontWeight  = "bold";
				}
				
				$j('#substituteItem').dialog('close');
				break;
				
			}			
		}
		
	}
	}
	

}
</script>
<?php
	require_once "../initialize.php";
	global $database;
	$list = $_GET['pid'];
	$prod = explode("_",$list);
	$prodID = $prod[0];
	$index = $prod[1];
	$soh = $prod[2];
	
	echo '<table width="98%" align="center"  border="0" cellspacing="0" cellpadding="0">';
	echo'<tr height="22">
				<td width="5%">&nbsp;</td>
				<td width="15%"><strong>Item Code</strong></td>
				<td width="50%"><strong>Item Name</strong></td>
				<td width="10%"><strong>SOH</strong></td>
			</tr>
			<tr>
				<td colspan="4">&nbsp;</td>
			</tr>'
			;
			
	if($soh != 0)
	{
		$rsGetProductList = $sp->spSelectProductSubstituteSO($database,$prodID,0);
		$cnt =0;
		if($rsGetProductList->num_rows)
		{		
			while($row = $rsGetProductList->fetch_object())
			{		
				$checkSOH		= 0;
				$cntSubstitute	= 0;
				$substitute		= 0 ;
				//echo $row->prodID;
				$rsCheckSubstitute = $sp->spCheckIfSubstitute($database,$row->prodID);
				if($rsCheckSubstitute->num_rows)
				{
					while($substitute = $rsCheckSubstitute->fetch_object())
					{
						$checkSOH		= $substitute->CheckAvailability;
						$cntSubstitute	= $substitute->cnt;
					}
				}
				
				if($checkSOH > 0)
				{
					if($row->SOH > 0)
					{
						if($cntSubstitute > 0)
						{
							$substitute	= 1;	
						}
					}
				}
				else
				{
					if($cntSubstitute > 0)
					{
						$substitute	= 1;	
					}
				}
				echo'<tr height="22">
					<td width="5%"><input type="radio" class="btn" name="rdProduct" id="rdProduct" value='.$row->prodID.' checked /></td>
					<td width="15%"><input type="hidden" name="hProdCodeSubstitute'.$cnt.'" id="hProdCodeSubstitute'.$cnt.'"value='.$row->prodCode.'>'.$row->prodCode.'</td>
					<td width="50%"><input type="hidden" name="hProdDescSubstitute'.$cnt.'" id="hProdDescSubstitute'.$cnt.'"value="'.$row->prodName.'">'.$row->prodName.'</td>
					<td width="10%">'.$row->SOH.'</td>
					<input type="hidden" name="hPMGSubstitute'.$cnt.'" id="hPMGSubstitute'.$cnt.'"value="'.$row->pmgCode.'">
					<input type="hidden" name="hPMGIDSubstitute'.$cnt.'" id="hPMGIDSubstitute'.$cnt.'"value="'.$row->pmgID.'">
					<input type="hidden" name="hSOHSubstitute'.$cnt.'" id="hSOHSubstitute'.$cnt.'"value="'.$row->SOH.'">
					<input type="hidden" name="hInTransitSubstitute'.$cnt.'" id="hInTransitSubstitute'.$cnt.'"value="'.$row->InTransit.'">
					<input type="hidden" name="hUnitPriceSubstitute'.$cnt.'" id="hUnitPriceSubstitute'.$cnt.'"value="'.$row->UnitPrice.'">
					<input type="hidden" name="hProductTypeSubstitute'.$cnt.'" id="hProductTypeSubstitute'.$cnt.'"value="'.$row->ProductType.'">
					<input type="hidden" name="hHasSubstitute'.$cnt.'" id="hHasSubstitute'.$cnt.'"value="'.$substitute	.'">
				</tr>';
				$cnt++;
			}
		}
	}
	else
	{
		
		$rsGetProductList = $sp->spSelectProductSubstituteSO($database,$prodID,1);
		$cnt =0;
		if($rsGetProductList->num_rows)
		{		
			while($row = $rsGetProductList->fetch_object())
			{		
				$checkSOH		= 0;
				$cntSubstitute	= 0;
				$substitute		= 0 ;
				
				$rsCheckSubstitute = $sp->spCheckIfSubstitute($database,$row->prodID);
				if($rsCheckSubstitute->num_rows)
				{
					while($substitute = $rsCheckSubstitute->fetch_object())
					{
						$checkSOH		= $substitute->CheckAvailability;
						$cntSubstitute	= $substitute->cnt;
					}
				}
				
				if($checkSOH > 0)
				{
					if($row->SOH > 0)
					{
						if($cntSubstitute > 0)
						{
							$substitute	= 1;	
						}
					}
				}
				else
				{
					if($cntSubstitute > 0)
					{
						$substitute	= 1;	
					}
				}
				echo'<tr height="22">
					<td width="5%"><input type="radio" class="btn" name="rdProduct" id="rdProduct" value='.$row->prodID.' checked /></td>
					<td width="15%"><input type="hidden" name="hProdCodeSubstitute'.$cnt.'" id="hProdCodeSubstitute'.$cnt.'"value='.$row->prodCode.'>'.$row->prodCode.'</td>
					<td width="50%"><input type="hidden" name="hProdDescSubstitute'.$cnt.'" id="hProdDescSubstitute'.$cnt.'"value="'.$row->prodName.'">'.$row->prodName.'</td>
					<td width="10%">'.$row->SOH.'</td>
					<input type="hidden" name="hPMGSubstitute'.$cnt.'" id="hPMGSubstitute'.$cnt.'"value="'.$row->pmgCode.'">
					<input type="hidden" name="hPMGIDSubstitute'.$cnt.'" id="hPMGIDSubstitute'.$cnt.'"value="'.$row->pmgID.'">
					<input type="hidden" name="hSOHSubstitute'.$cnt.'" id="hSOHSubstitute'.$cnt.'"value="'.$row->SOH.'">
					<input type="hidden" name="hInTransitSubstitute'.$cnt.'" id="hInTransitSubstitute'.$cnt.'"value="'.$row->InTransit.'">
					<input type="hidden" name="hUnitPriceSubstitute'.$cnt.'" id="hUnitPriceSubstitute'.$cnt.'"value="'.$row->UnitPrice.'">
					<input type="hidden" name="hProductTypeSubstitute'.$cnt.'" id="hProductTypeSubstitute'.$cnt.'"value="'.$row->ProductType.'">
					<input type="hidden" name="hHasSubstitute'.$cnt.'" id="hHasSubstitute'.$cnt.'"value="'.$substitute	.'">
				</tr>';
				$cnt++;
			}
		}
	}
	
	echo '<table>';
	echo '<br>';
	echo '<table width="98%" align="center"  border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="4"><input type="submit" value="Substitute" class="btn" onclick="return substitute('.$index.');" /></td>
			</tr>
			</table>';

	
?>
