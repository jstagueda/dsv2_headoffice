<?php
		
	require_once "../initialize.php";
	global $database;
	$sessionID= session_id();		
	$mode = $_GET['mode'];
	
	
	if($mode == 'post')
	{
		
		$cnt = $_POST['hcnt'] -1 ;	
		$soid = $_GET['soid'];
		
		try
		{
			$database->beginTransaction();
			$delete_rows = $sp->spDeleteProductList($database,$sessionID);	
			if (!$delete_rows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}	
			$delete_sorows = $sp->spDeleteSOProductList($database,$sessionID);
			if (!$delete_sorows)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}	
			
			for($i = 1 ; $i <= $cnt ; $i++)
			{
				
				$productID 		= $_POST['hProdID'.$i];
				$regularprice 	= $_POST['txtUnitPrice'.$i];
				$effectiveprice = $_POST['txtEffectivePrice'.$i];		
				$pmgid			= $_POST['hPMGID'.$i];
				$productype		= $_POST['hProductType'.$i];
				$soh 			= $_POST['hSOH'.$i];
				$transit		= $_POST['hTransit'.$i];
				$qty			= $_POST['txtOrderedQty'.$i];
				
				for($j = 1 ; $j <= $qty; $j++)
				{
					$inserttmpsodetail	= $sp->spInsertTmpSODetails($database,$productID, $regularprice, $effectiveprice, $sessionID, $pmgid, $productype , $soh, $transit,0,0,0) ;		
					if (!$inserttmpsodetail)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
				}
			
			}
		
			$rsGetSOHTmpSODetatils	= $sp->spCheckSOHSO($database,$sessionID);
					
			if($rsGetSOHTmpSODetatils->num_rows)
			{
				$gsutypeid			= $_POST['hGSUType'];
				while ($TmpSOH = $rsGetSOHTmpSODetatils->fetch_object())
				{	
					$tempID				=	$TmpSOH->DetailsID;	
					$tempQty 			= 	$TmpSOH->Qty;
					$tempSOH 			= 	$TmpSOH->SOH;
					$tempProductID		=	$TmpSOH->productID;
					$tempregularprice	=	$TmpSOH->UnitPrice;
					$tempEffectiveprice =	$TmpSOH->UnitPrice;
					$tempPMGID			=	$TmpSOH->PMGID;
					$tempProductType	=	$TmpSOH->ProductType;
					$rsgetPromoDate		= $sp-> spSelectPromoDatebySOID($database,$soid);
					if($rsgetPromoDate->num_rows)
					{
						while($getPromoDate = $rsgetPromoDate->fetch_object())
						{
							$purchaseDate =  $getPromoDate->PromoDate;
						}
					}
					
					if($tempSOH >=  $tempQty )
					{
							
						for($i = 1 ; $i <= $tempQty; $i++)
						{
						
							$affected_rows = $sp->spInsertProductList($database,$tempProductID, $tempregularprice, $tempEffectiveprice, $sessionID,$tempPMGID,$tempProductType,$purchaseDate,$gsutypeid); 
							if (!$affected_rows)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}
						}
						$rsDeleteTmpSODetails = $sp->spDeleteTmpSODetailsByProductID($database,$tempProductID,$sessionID);
						if (!$rsDeleteTmpSODetails)	
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					}
					else
					{
						if($tempSOH  < 0)
						{
							$tempSOH 	= 0;
						}
						$tempDifferece =  $tempQty - $tempSOH ;						
						$rsDetailsID	=	$sp->spSelectIDSODetailsLimit($database,$tempProductID, $sessionID,$tempSOH);
						//echo "spSelectIDSODetailsLimit($tempProductID, $sessionID,$tempSOH);";
						if($rsDetailsID->num_rows)
						{		
							$listID = "0";
							while($getDetailsID = $rsDetailsID->fetch_object())
							{
								$listID = $listID.",".$getDetailsID->ID;
								$affected_rows = $sp->spInsertProductList($database,$tempProductID, $tempregularprice, $tempEffectiveprice, $sessionID,$tempPMGID,$tempProductType,$purchaseDate,$gsutypeid); 
								if (!$affected_rows)
								{
									throw new Exception("An error occurred, please contact your system administrator.");
								}
								$deleteSODetails	=	$sp->spDeleteTmpSODetailsByID($database,$getDetailsID->ID);
								if (!$deleteSODetails)
								{
									throw new Exception("An error occurred, please contact your system administrator.");
								}
								
							}
						}				
						
						$affected_rows = $sp-> spUpdateEffectivePriceSODetails($database,$tempProductID, $sessionID);
						if (!$affected_rows)
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					}
				}			   		
						
			}
		$database->commitTransaction();
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";		
		}
				
		// Promo::Test();
		$promo = new Promo();
		$promo->CalculateBestPrice($database);
		$insertProductList = $sp->spSelectProductListSOAjax($database,$sessionID);
		
		try
		{
			$database->beginTransaction();
			if ($insertProductList->num_rows)
			{
			
				while ($prodList = $insertProductList->fetch_object())
				{
					
					$productID 			=	$prodList->productID ;	
					$qty 				= 	$prodList->Qty;
					$regularprice		=	number_format($prodList->UnitPrice,2,'.','');
					$effectiveprice		=	number_format($prodList->EffectivePrice,2,'.','');
					$pmgid				=	$prodList->PMGID;
					$producttype		= 	$prodList->ProductType;	
					$soh				=	$prodList->SOH;	
					$transit			=	$prodList->InTransit;
					$hasMorePromos		=   $prodList->HasMorePromos;
					$availment			= 	 $prodList->AvailmentType;
					if($prodList->AvailmentType != '')
					{
					
						$availment		=	$prodList->AvailmentType;
					}
					else
					{
						$availment		=	'null';
					}
					if($prodList->PromoID != '')
					{
					
						$promoID		=	$prodList->PromoID;
					}
					else
					{
						$promoID		=	'null';
					}
					
					for($i = 1 ; $i <= $qty; $i++)
					{
						$inserttmpsodetail	= $sp->spInsertTmpSODetails($database,$productID, $regularprice, $effectiveprice, $sessionID, $pmgid, $producttype , $soh, $transit,$promoID,$hasMorePromos,$availment) ;	
						
						if (!$inserttmpsodetail)
						{
							throw new Exception("An error occurred, please contact your system administrator.");
						}
						
					}
				}
			
			}
			$database->commitTransaction();
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();	
			$errmsg = $e->getMessage()."\n";	
		}
			
	}
	else
	{	
		echo '<table width="100%"  cellpadding="0" cellspacing="1" class="bgFFFFFF" id="dynamicTable" border="0">';
			
							
				
				$rsproductList = $sp->spCheckSOHSOList($database,$sessionID);
				$j = $rsproductList->num_rows + 1;
				
				if ($rsproductList->num_rows)
				{
					$row = $rsproductList;
					$i = 1;
					echo'<input type="hidden" name="hcnt" id="hcnt" value="'.$j.'" />';
				   while ($row = $rsproductList->fetch_object())
				   {						   		
				   	
						$productID 			=	$row->productID ;						
						$productcode 		= 	$row->Code;
						$productdescription =   str_replace('"','',$row->Description);
						$pmg 				= 	$row->PMG;	
						$pmgid				=	$row->PMGID;					
						$unitprice			= 	number_format($row->UnitPrice,2,'.','');
						$orderedQTY 		= 	$row->Qty;
						$effectiveprice 	= 	number_format($row->EffectivePrice,2,'.','');
						$tmptotalprice  	= 	$effectiveprice *  $orderedQTY ;
						$totalprice			= 	number_format($tmptotalprice,2,'.','');
						$promoCode			=	$row->PromoCode;
						$producttype		= 	$row->ProductType;
						$promoID			= 	$row->PromoID;
						$promoType			= 	$row->Promotype;
						$served				= 	$row->served;
					echo'<tr height="20">							
							
							<td width="4%" align="left" class="borderBR padl5">'.$i.'</td>
							<td width="8%" height="20" class="borderBR padl5"><div align="left" >
								<input name="txtProdCode'.$i.'" type="text" class="txtfieldItemLabel1" id="txtProdCode'.$i.'" style="width: 75px;" readonly="yes" value="'.$productcode.'"/>								
								<input name="hProdID'.$i.'" type="hidden" id="hProdID'.$i.'" value="'.$productID.'"/>
							</div></td>
							<td width="17%" align="left" class="borderBR padl5"><input name="txtProdDesc'.$i.'" type="text" class="txtfieldItemLabel1" style="width: 220px" id="txtProdDesc'.$i.'"  readonly="yes" value="'.$productdescription.'" /></td>
							<td width="5%" align="center" class="borderBR padl5">Piece</td>
							<td width="5%" align="center" class="borderBR padl5"><input readonly="yes" name="txtPMG'.$i.'" type="text" class="txtfieldItemLabel1" id="txtPMG'.$i.'"  value="'.$pmg.'" /><input type="hidden" name="hPMGID'.$i.'" id="hPMGID'.$i.'"  value="'.$pmgid.'" /> <input type="hidden" name="hProductType'.$i.'" id="hProductType'.$i.'"  value="'.$producttype.'" /></td>					
							<td width="8%" align="center" class="borderBR padl5"><input type="text"  name="txtUnitPrice'.$i.'" class="txtfieldItemLabel1" id="txtUnitPrice'.$i.'" readonly="yes" value="'.$unitprice.'"></td>
							<td width="10%" align="center" class="borderBR padl5">'.$promoCode.' <input type="hidden" name="hPromoID'.$i.'" id="hPromoID'.$i.'" value="'.$promoID.'" /><input type="hidden" name="hPromoType'.$i.'" id="hPromoType'.$i.'" value="'.$promoType.'" /></td>		
							<td width="5%" align="center" class="borderBR padl5"><div id="divSOH'.$i.'" name="divSOH'.$i.'">'.$row->SOH.'</div><input type="hidden" name="hSOH'.$i.'" id="hSOH'.$i.'" value="'.$row->SOH.'" ></td>					
							<td width="10%" align="center" class="borderBR padl5"><div id="divTransit'.$i.'" name="divTransit'.$i.'">'.$row->InTransit.'</div><input type="hidden" name="hTransit'.$i.'" id="hTransit'.$i.'" value="'.$row->InTransit.'"  ></td>
							<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtOrderedQty'.$i.'" readonly="yes" class="txtfieldItemLabel1" id="txtOrderedQty'.$i.'" value = "'.$orderedQTY.'" /><input type="hidden" name="hServed'.$i.'"  value = "'.$served.'"  /></td>
							<td width="10%" align="center" class="borderBR padl5"><input type="text" name="txtEffectivePrice'.$i.'" class="txtfieldItemLabel1" id="txtEffectivePrice'.$i.'" readonly="yes" value="'.$effectiveprice.'"> </td>					
							<td width="10%" align="center" class="borderBR padl5"><input type="text" name="txtTotalPrice'.$i.'" class="txtfieldItemLabel1" id="txtTotalPrice'.$i.'" readonly="yes" value="'.$totalprice.'"> </td>					
						</tr>' ;				
							
						$i ++;								
				   }			 
				  
				}
	
		
		echo '</table>' ;
	

		}
	
	

	
?>
