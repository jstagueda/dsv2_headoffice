<?php		
	require_once "../initialize.php";
	global $database;
	$sessionID= session_id();		
	$mode = $_GET['mode'];
	$refresh = $_GET['refresh'];
	$custID = $_GET['custID'];

	if($mode == 'post')
	{
		$cnt = $_POST['hcnt'] - 1;
		
		if ($refresh==1)	
		{	
			try
			{
				//$database->beginTransaction();
				
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
					$forIncentive	= $_POST['hForIncentive'.$i];
					$subsID			= $_POST['hSubsID'.$i];
					$promoID		= $_POST['hPromoID'.$i];
					$isKitComponent = $_POST['hKitComponent'.$i];
					
					if($isKitComponent == "")
					{
						$isKitComponent = 0;
					}
					
					if($subsID == "")
					{
						$subsID = 0;
					}
					
					if($promoID == "")
					{
						$promoID = 0;
					}
					
					for($j = 1 ; $j <= $qty; $j++)
					{
						//insert all line items to temp tables of sodetails 
						if($isKitComponent != 1)
						{
							$inserttmpsodetail	= $sp->spInsertTmpSODetails($database,$productID, $regularprice, $effectiveprice, $sessionID, $pmgid, $productype , $soh, $transit,$promoID,0,1,$subsID,$forIncentive) ;		
							if (!$inserttmpsodetail)
							{
								throw new Exception("An error occurred, please contact your system administrator.");
							}
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
						$tempForIncentive	=	$TmpSOH->ForIncentive;
						$substituteID		=	$TmpSOH->SubstituteID;
						$promoID			=	$TmpSOH->PromoID;
						if ($promoID == "0") {
							$promoID = "null";
						}
						
						//if Advance PO get the set the purchase/promo date to the start of next campaign.						
						if(isset($_GET['adv']))
						{
							if($_GET['adv'] == 1)						
							{
								$tmpdate = date("Y-m-d");
								//echo $tmpdate;
								$rsAdvPODate = $sp->spSelectAdvPOStart($database,$tmpdate);	
								//echo $rsAdvPODate->num_rows."test";							
								if($rsAdvPODate->num_rows)
								{
									while($getDate = $rsAdvPODate->fetch_object())
									{
										$purchaseDate = $getDate->startdate;									
									}
								}
							}
							else						
							{
								if($_GET['backOrder'] == 1 )
								{
									$soid = $_GET['txnID'];
									$rsgetPromoDate		= $sp-> spSelectPromoDatebySOID($database,$soid);
										if($rsgetPromoDate->num_rows)
										{
											while($getPromoDate = $rsgetPromoDate->fetch_object())
											{
												$purchaseDate =  $getPromoDate->PromoDate;
											}
										}
					
									
								}
								else
								{
									
									$purchaseDate = date("Y-m-d");	
								}
								
							}
						}
						else
						{
							//if viewing an unconfirmed/backorder SO get purchased Date of SO.
							$soid = $_GET['txnid'];
							//echo session_id();
							$rsgetPromoDate = $sp->spSelectPromoDatebySOID($database,$soid);
							if($rsgetPromoDate->num_rows)
							{
								while($getPromoDate = $rsgetPromoDate->fetch_object())
								{
									if($getPromoDate->PromoDate != "")
									{
										$purchaseDate =  $getPromoDate->PromoDate;
									}
									else
									{
										$purchaseDate = date("Y-m-d");
									}
								}
							}
						}
						
						//if product kit check if the components are available.
						if($tempProductType == 4)
						{
							$availkit = 1;
							//select all the components of the selected kit.
							$rsComponent = $sp->spSelectKitComponents($database,$tempProductID);						
							if($rsComponent->num_rows)
							{	
								
								while($row =$rsComponent->fetch_object())
								{									
									//if component has 0 SOH: check if it has substitute 
									if($row->SOH == 0)
									{
											$rsCheckSubstitute = $sp->spCheckIfSubstitute($database,$row->ProdID);
											
											if($rsCheckSubstitute->num_rows)
											{
												
												while($substitute = $rsCheckSubstitute->fetch_object())
												{
													$checkSOH		= $substitute->CheckAvailability;
													$cntSubstitute	= $substitute->cnt;
												}
											}
											else
											{
												$availkit = 0;
												break;
											}
									}									
								}						
								
							};
							
							if($availkit == 1)
							{
								$affected_rows = $sp->spInsertProductList($database,$tempProductID, $tempregularprice, $tempEffectiveprice, $sessionID,$tempPMGID,$tempProductType,$purchaseDate,$gsutypeid,$substituteID,$tempForIncentive,$promoID);
								if (!$affected_rows)
								{
									throw new Exception("An error occurred, please contact your system administrator.");
								}
								$rsDeleteTmpSODetails = $sp->spDeleteTmpSODetailsByProductID($database,$tempProductID,$sessionID);
							}
							else
							{
								$affected_rows = $sp-> spUpdateEffectivePriceSODetails($database,$tempProductID, $sessionID);
								echo "One of the component(s) has zero SOH.";
							}
							
							
							
							
						}
						else
						{
							//if available SOH is great than ordered quantity insert into product list for calculation of best price.
							if($tempSOH >=  $tempQty )
							{								
								for($i = 1 ; $i <= $tempQty; $i++)
								{
									
									$affected_rows = $sp->spInsertProductList($database,$tempProductID, $tempregularprice, $tempEffectiveprice, $sessionID,$tempPMGID,$tempProductType,$purchaseDate,$gsutypeid,$substituteID,$tempForIncentive,$promoID); 
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
								//echo "test";
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
									if($_GET['adv'] == 1)						
									{
										$tmpdate = date("Y-m-d");
										$rsAdvPODate = $sp->spSelectAdvPOStart($tmpdate);
										if($rsAdvPODate->num_rows)
										{
											while($getDate = $rsAdvPODate->fetch_object())
											{
												$purchaseDate = date("Y-m-d",$getDate->startdate);
											}
										}
									}
									else						
									{
										$purchaseDate = date("Y-m-d");
									}
									while($getDetailsID = $rsDetailsID->fetch_object())
									{
										$listID = $listID.",".$getDetailsID->ID;
										$affected_rows = $sp->spInsertProductList($database,$tempProductID, $tempregularprice, $tempEffectiveprice, $sessionID,$tempPMGID,$tempProductType,$purchaseDate,$gsutypeid,$substituteID,$tempForIncentive,$promoID); 
										
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
					}//end regular products
						
						
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
			$promo->CalculateIncentives($database,$custID);
			//$promo->CalculateBestPrice($database, $custID);
			//$promo->CalculateIncentives($database, $custID);
			$insertProductList = $sp->spSelectProductListSOAjax($database,$sessionID);
		}
				
		
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
					$availment			= 	$prodList->AvailmentType;
					$subsID				=	$prodList->SubstituteID;
					$forIncentive		=	$prodList->ForIncentive;
					if($prodList->PromoID != '')
					{
					
						$promoID		=	$prodList->PromoID;
					}
					else
					{
						$promoID		=	'null';
					}
					if($prodList->AvailmentType != '')
					{
					
						$availment		=	$prodList->AvailmentType;
					}
					else
					{
						$availment		=	'null';
					}
					
					for($i = 1 ; $i <= $qty; $i++)
					{
						
						$inserttmpsodetail	= $sp->spInsertTmpSODetails($database,$productID, $regularprice, $effectiveprice, $sessionID, $pmgid, $producttype , $soh, $transit,$promoID,$hasMorePromos,$availment,$subsID,$forIncentive) ;
						
						
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
					$rsCheckIncentive = $sp->spCheckApplicableIncentives($database,$sessionID);
					if ($rsproductList->num_rows > 0)
					{
						echo'<input type="hidden" name="hHasIncentive" id="hHasIncentive" value="1" />';
					}
				
				   while ($row = $rsproductList->fetch_object())
				   {	   		
				   	
						$productID 			=	$row->productID ;						
						$productcode 		= 	$row->Code;
						$productdescription = 	str_replace('"','',$row->Description);
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
						$hasMorePromos		= 	$row->HasMorePromos;
						$served				= 	$row->served;
						$substituteID		= 	$row->SubstituteID;
						$forIncentive		= 	$row->ForIncentive;
					    $onclick ="";
						$onmouse="";
						$font="";
						$checkSOH="";
						$cntSubstitute="";
						
						//check if component has substitute
					   $rsCheckSubstitute = $sp->spCheckIfSubstitute($database,$productID);	
					   //echo "spCheckIfSubstitute($database,$prodID);";								
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
									$onclick = "onclick='substituteItem($productID,$i,$row->SOH)'";
									$onmouse = "onmouseovere = 'this.style.cursor = 'pointer''";
									$font =		"style='color: blue; font-weight: bold; cursor: pointer;'";
									
								}
								else
								{
									$onclick ="";
									$onmouse="";
									$font="";
								}
							}
						}
						else
						{
							if($cntSubstitute > 0)
							{
								$onclick = "onclick='substituteItem($productID,$i,$row->SOH)'";											
								$onmouse = "onmouseovere = 'this.style.cursor = 'pointer''";
								$font =		"style='color: blue; font-weight: bold; cursor: pointer;'"	;
							}
							else
							{
								$onclick ="";
								$onmouse="";
								$font="";
							}
						}
						if ($hasMorePromos!=0) 
						{
							$promoCode = '<a href="#" id="link_'. $i . '" onclick="changepromo(\'link_' . $i .'\', ' . $productID . ', ' . $promoID .')">' . $promoCode . "</a>";
						}
					if($substituteID == 0)
					{
						echo'<tr height="20">							
								
								<td width="4%" align="left" class="borderBR padl5">'.$i.'</td>
								<td width="8%" height="20" class="borderBR padl5"><div align="left" >
									<input name="txtProdCode'.$i.'" type="text" class="txtfield" id="txtProdCode'.$i.'" onKeyPress="return disableEnterKey(this,event)"  style="width: 75px;" value="'.$productcode.'"/>
									<span id="indicator2" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
									<div id="prod_choices'.$i.'" class="autocomplete" style="display:none"></div>
									<script type="text/javascript">							
										 //<![CDATA[
											var prod_choices = new Ajax.Autocompleter(\'txtProdCode'.$i.'\', \'prod_choices'.$i.'\', \'includes/scProductListAjaxSO.php?index='.$i.'\', {afterUpdateElement : getSelectionProductList, indicator: \'indicator2\'});																			
										//]]>
									</script>
									<input name="hProdID'.$i.'" type="hidden" id="hProdID'.$i.'" value="'.$productID.'"/>
									<input name="hSubsID'.$i.'" type="hidden" id="hSubsID'.$i.'" value="'.$substituteID.'"/>
									<input name="hKitComponent'.$i.'" type="hidden" id="hKitComponent'.$i.'" value="" />
								</div></td>
								<td width="24%" align="left" class="borderBR padl5"><input name="txtProdDesc'.$i.'" type="text" class="txtfield" style="width: 300px" id="txtProdDesc'.$i.'"  readonly="yes" onKeyPress="return disableEnterKey(this,event)" value="'.$productdescription.'" /></td>
								<td width="5%" align="center" class="borderBR padl5">Piece</td>
								<td width="8%" align="center" class="borderBR padl5"><input name="txtPMG'.$i.'" type="text" class="txtfieldItemLabel1" readonly="yes" style="text-align:center" id="txtPMG'.$i.'"  value="'.$pmg.'" /><input type="hidden" name="hPMGID'.$i.'" id="hPMGID'.$i.'"  value="'.$pmgid.'" /> <input type="hidden" name="hProductType'.$i.'" id="hProductType'.$i.'"  value="'.$producttype.'" /></td>					
								<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtUnitPrice'.$i.'" class="txtfieldItemLabel1" id="txtUnitPrice'.$i.'" readonly="yes" style="text-align:right" value="'.$unitprice.'"></td>
								<td width="5%" align="center" class="borderBR padl5"><div id="divPromo'.$i.'" name="divPromo'.$i.'">'.$promoCode.'</div><input type="hidden" name="hPromoID'.$i.'" id="hPromoID'.$i.'" value="'.$promoID.'" /><input type="hidden" name="hPromoType'.$i.'" id="hPromoType'.$i.'" value="'.$promoType.'" /><input type="hidden" name="hForIncentive'.$i.'" id="hForIncentive'.$i.'" value="'.$forIncentive.'" /></td>		
								<td width="5%" align="center" class="borderBR padl5"><div id="divSOH'.$i.'" name="divSOH'.$i.'" '.$onclick.$onmouse.$font.'>'.$row->SOH.'</div><input type="hidden" name="hSOH'.$i.'" id="hSOH'.$i.'" value="'.$row->SOH.'" ></td>					
								<td width="8%" align="center" class="borderBR padl5"><div id="divTransit'.$i.'" name="divTransit'.$i.'">'.$row->InTransit.'</div><input type="hidden" name="hTransit'.$i.'" id="hTransit'.$i.'" value="'.$row->InTransit.'"  ></td>
								<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtOrderedQty'.$i.'" class="txtfield3" id="txtOrderedQty'.$i.'" onKeyPress="return disableEnterKey(this,event)" style="text-align:right" value = "'.$orderedQTY.'" /><input type="hidden" name="hServed'.$i.'"  value = "'.$served.'"  /></td>
								<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtEffectivePrice'.$i.'" class="txtfieldItemLabel1" id="txtEffectivePrice'.$i.'" readonly="yes" style="text-align:right" value="'.$effectiveprice.'"> </td>					
								<td width="10%" align="center" class="borderBR padl5"><input type="text" name="txtTotalPrice'.$i.'" class="txtfieldItemLabel1" id="txtTotalPrice'.$i.'" readonly="yes" style="text-align:right" value="'.number_format($totalprice,2,".",",").'"> </td>					
							</tr>' ;											
					}
					else
					{
						$rsSubstituteDetails = $sp->spCheckSOHSOListSubstitute($database,$sessionID,$productID,$substituteID);
						if ($rsSubstituteDetails->num_rows)
						{
							while($subsDetails = $rsSubstituteDetails->fetch_object())
							{
								$productID 			=	$subsDetails->productID ;						
								$productcode 		= 	$subsDetails->Code;
								$productdescription = 	str_replace('"','',$subsDetails->Description);
								$pmg 				= 	$subsDetails->PMG;	
								$pmgid				=	$subsDetails->PMGID;					
								$unitprice			= 	number_format($subsDetails->UnitPrice,2,'.','');
								$orderedQTY 		= 	$subsDetails->Qty;
								$effectiveprice 	= 	number_format($subsDetails->EffectivePrice,2,'.','');
								$tmptotalprice  	= 	$effectiveprice *  $orderedQTY ;
								$totalprice			= 	number_format($tmptotalprice,2,'.','');
								$promoCode			=	$subsDetails->PromoCode;
								$producttype		= 	$subsDetails->ProductType;
								$promoID			= 	$subsDetails->PromoID;
								$promoType			= 	$subsDetails->Promotype;
								$hasMorePromos		= 	$subsDetails->HasMorePromos;
								$served				= 	$subsDetails->served;
								$substituteID		= 	$subsDetails->SubstituteID;
								$forIncentive		= 	$subsDetails->ForIncentive;
								$onclick ="";
								$onmouse="";
								$font="";
								$checkSOH="";
								$cntSubstitute="";
								
								//check if component has substitute
							   $rsCheckSubstitute = $sp->spCheckIfSubstitute($database,$productID);	
							   //echo "spCheckIfSubstitute($database,$prodID);";								
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
											$onclick = "onclick='substituteItem($productID,$i,$subsDetails->SOH)'";
											$onmouse = "onmouseovere = 'this.style.cursor = 'pointer''";
											$font =		"style='color: blue; font-weight: bold; cursor: pointer;'";
											
										}
										else
										{
											$onclick ="";
											$onmouse="";
											$font="";
										}
									}
								}
								else
								{
									if($cntSubstitute > 0)
									{
										$onclick = "onclick='substituteItem($productID,$i,$subsDetails->SOH)'";											
										$onmouse = "onmouseovere = 'this.style.cursor = 'pointer''";
										$font =		"style='color: blue; font-weight: bold; cursor: pointer;'"	;
									}
									else
									{
										$onclick ="";
										$onmouse="";
										$font="";
									}
								}
								echo'<tr height="20">							
										
										<td width="4%" align="left" class="borderBR padl5">'.$i.'</td>
										<td width="8%" height="20" class="borderBR padl5"><div align="left" >
											<input name="txtProdCode'.$i.'" type="text" class="txtfield" id="txtProdCode'.$i.'" onKeyPress="return disableEnterKey(this,event)"  style="width: 75px;" value="'.$productcode.'"/>
											<span id="indicator2" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
											<div id="prod_choices'.$i.'" class="autocomplete" style="display:none"></div>
											<script type="text/javascript">							
												 //<![CDATA[
													var prod_choices = new Ajax.Autocompleter(\'txtProdCode'.$i.'\', \'prod_choices'.$i.'\', \'includes/scProductListAjaxSO.php?index='.$i.'\', {afterUpdateElement : getSelectionProductList, indicator: \'indicator2\'});																			
												//]]>
											</script>
											<input name="hProdID'.$i.'" type="hidden" id="hProdID'.$i.'" value="'.$productID.'"/>
											<input name="hSubsID'.$i.'" type="hidden" id="hSubsID'.$i.'" value="'.$substituteID.'"/>
											<input name="hKitComponent'.$i.'" type="hidden" id="hKitComponent'.$i.'" value="" />
										</div></td>
										<td width="24%" align="left" class="borderBR padl5"><input name="txtProdDesc'.$i.'" type="text" class="txtfield" style="width: 300px" id="txtProdDesc'.$i.'"  readonly="yes" onKeyPress="return disableEnterKey(this,event)" value="'.$productdescription.'" /></td>
										<td width="5%" align="center" class="borderBR padl5">Piece</td>
										<td width="8%" align="center" class="borderBR padl5"><input name="txtPMG'.$i.'" type="text" class="txtfieldItemLabel1" readonly="yes" style="text-align:center" id="txtPMG'.$i.'"  value="'.$pmg.'" /><input type="hidden" name="hPMGID'.$i.'" id="hPMGID'.$i.'"  value="'.$pmgid.'" /> <input type="hidden" name="hProductType'.$i.'" id="hProductType'.$i.'"  value="'.$producttype.'" /></td>					
										<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtUnitPrice'.$i.'" class="txtfieldItemLabel1" id="txtUnitPrice'.$i.'" readonly="yes" style="text-align:right" value="'.$unitprice.'"></td>
										<td width="5%" align="center" class="borderBR padl5"><div id="divPromo'.$i.'" name="divPromo'.$i.'">'.$promoCode.'</div><input type="hidden" name="hPromoID'.$i.'" id="hPromoID'.$i.'" value="'.$promoID.'" /><input type="hidden" name="hPromoType'.$i.'" id="hPromoType'.$i.'" value="'.$promoType.'" /><input type="hidden" name="hForIncentive'.$i.'" id="hForIncentive'.$i.'" value="'.$forIncentive.'" /></td>		
										<td width="5%" align="center" class="borderBR padl5"><div id="divSOH'.$i.'" name="divSOH'.$i.'" '.$onclick.$onmouse.$font.' >'.$subsDetails->SOH.'</div><input type="hidden" name="hSOH'.$i.'" id="hSOH'.$i.'" value="'.$subsDetails->SOH.'" ></td>					
										<td width="8%" align="center" class="borderBR padl5"><div id="divTransit'.$i.'" name="divTransit'.$i.'">'.$subsDetails->InTransit.'</div><input type="hidden" name="hTransit'.$i.'" id="hTransit'.$i.'" value="'.$subsDetails->InTransit.'"  ></td>
										<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtOrderedQty'.$i.'" class="txtfield3" id="txtOrderedQty'.$i.'" onKeyPress="return disableEnterKey(this,event)" style="text-align:right" value = "'.$orderedQTY.'" /><input type="hidden" name="hServed'.$i.'"  value = "'.$served.'"  /></td>
										<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtEffectivePrice'.$i.'" class="txtfieldItemLabel1" id="txtEffectivePrice'.$i.'" readonly="yes" style="text-align:right" value="'.$effectiveprice.'"> </td>					
										<td width="10%" align="center" class="borderBR padl5"><input type="text" name="txtTotalPrice'.$i.'" class="txtfieldItemLabel1" id="txtTotalPrice'.$i.'" readonly="yes" style="text-align:right" value="'.number_format($totalprice,2,".",",").'"> </td>					
									</tr>' ;
							}
						
						}	
						
					}
						if($producttype == 4)
						{
							$rsComponent = $sp->spSelectKitComponents($database,$productID); 
							$j = $j + $rsComponent->num_rows;
							if($rsComponent->num_rows)
							{
								$i = $i + 1;
								while ($row1 = $rsComponent->fetch_object())
				   				{
									$productcode 		= 	$row1->ProdCode;
									$productdescription = 	 str_replace('"','',$row1->ProdName);
									$pmg 				= 	$row1->PMG;	
									$unitprice			= 	number_format($row1->UnitPrice,2,'.','');
									$orderedQTY 		= 	$row1->Qty;
									$prodID 			= $row1->ProdID;
									$pmgID				= $row1->PMGID;
									$producttype		= $row1->ProductType;
									$onclick ="";
									$onmouse="";
									$font="";
									$checkSOH="";
									$cntSubstitute="";
									
									//check if component has substitute
								   $rsCheckSubstitute = $sp->spCheckIfSubstitute($database,$prodID);	
								   //echo "spCheckIfSubstitute($database,$prodID);";								
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
												$onclick = "onclick='substituteItem($prodID,$i,$row1->SOH)'";
												$onmouse = "onmouseovere = 'this.style.cursor = 'pointer''";
												$font =		"style='color: blue; font-weight: bold; cursor: pointer;'";
												
											}
											else
											{
												$onclick ="";
												$onmouse="";
												$font="";
											}
										}
										else
										{
											$onclick = "onclick='substituteItem($prodID,$i,$row1->SOH)'";
											$onmouse = "onmouseovere = 'this.style.cursor = 'pointer''";
											$font =		"style='color: blue; font-weight: bold; cursor: pointer;'";
										}
									}
									else
									{
										if($cntSubstitute > 0)
										{
											$onclick = "onclick='substituteItem($prodID,$i,$row1->SOH)'";											
											$onmouse = "onmouseovere = 'this.style.cursor = 'pointer''";
											$font =		"style='color: blue; font-weight: bold; cursor: pointer;'"	;
										}
										else
										{
											$onclick ="";
											$onmouse="";
											$font="";
										}
									}
									
									echo'<tr height="20">							
										
										<td width="4%" align="left" class="borderBR padl5">'.$i.'</td>
										<td width="8%" height="20" class="borderBR padl5"><div align="left" >
											<input name="txtProdCode'.$i.'" type="text" class="txtfield" id="txtProdCode'.$i.'" onKeyPress="return disableEnterKey(this,event,'.$i.')"  style="width: 75px;" value="'.$productcode.'"/>
											<span id="indicator2" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
											<div id="prod_choices'.$i.'" class="autocomplete" style="display:none"></div>
											<script type="text/javascript">							
												 //<![CDATA[
													var prod_choices = new Ajax.Autocompleter(\'txtProdCode'.$i.'\', \'prod_choices'.$i.'\', \'includes/scProductListAjaxSO.php?index='.$i.'\', {afterUpdateElement : getSelectionProductList, indicator: \'indicator2\'});																			
												//]]>
											</script>
											<input name="hProdID'.$i.'" type="hidden" id="hProdID'.$i.'" value="'.$prodID .'" />
											<input name="hSubsID'.$i.'" type="hidden" id="hSubsID'.$i.'" value=""/>
											<input name="hKitComponent'.$i.'" type="hidden" id="hKitComponent'.$i.'" value="1" />
										</div></td>
										<td width="24%" align="left" class="borderBR padl5"><input name="txtProdDesc'.$i.'" type="text" class="txtfield" style="width: 300px" id="txtProdDesc'.$i.'"  readonly="yes" onKeyPress="return disableEnterKey(this,event,'.$i.')" value="'.$productdescription.'" /></td>
										<td width="5%" align="center" class="borderBR padl5">Piece</td>
										<td width="8%" align="center" class="borderBR padl5"><input name="txtPMG'.$i.'" type="text" class="txtfieldItemLabel1" readonly="yes" style="text-align:center" id="txtPMG'.$i.'"  value="'.$pmg.'"  /><input type="hidden" name="hPMGID'.$i.'" id="hPMGID'.$i.'"  value="'.$pmgid.'"  /> <input type="hidden" name="hProductType'.$i.'" id="hProductType'.$i.'"  value="'.$producttype.'"  /></td>					
										<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtUnitPrice'.$i.'" class="txtfieldItemLabel1" id="txtUnitPrice'.$i.'" readonly="yes" style="text-align:right" value="'.$unitprice.'" ></td>
										<td width="5%" align="center" class="borderBR padl5"><div id="divPromo'.$i.'" name="divPromo'.$i.'">'.$promoCode.'</div><input type="hidden" name="hPromoID'.$i.'" id="hPromoID'.$i.'" value="" /><input type="hidden" name="hPromoType'.$i.'" id="hPromoType'.$i.'" value="" /><input type="hidden" name="hForIncentive'.$i.'" id="hForIncentive'.$i.'" value="0" /></td>		
										<td width="5%" align="center" class="borderBR padl5"><div id="divSOH'.$i.'" name="divSOH'.$i.'" '.$onclick.$onmouse.$font.'>'.$row1->SOH.'</div><input type="hidden" name="hSOH'.$i.'" id="hSOH'.$i.'" value="'.$row->SOH.'" ></td>					
										<td width="8%" align="center" class="borderBR padl5"><div id="divTransit'.$i.'" name="divTransit'.$i.'">'.$row1->Intransit.'</div><input type="hidden" name="hTransit'.$i.'" id="hTransit'.$i.'" value="'.$row->InTransit.'"  > </td>
										<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtOrderedQty'.$i.'" class="txtfield3" id="txtOrderedQty'.$i.'" onKeyPress="return disableEnterKey(this,event,'.$i.')" style="text-align:right" value = "'.$orderedQTY.'"/> <input type="hidden" name="hServed'.$i.'"  value = "'.$served.'"  /></td>
										<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtEffectivePrice'.$i.'" class="txtfieldItemLabel1" id="txtEffectivePrice'.$i.'" value="0.00" readonly="yes" style="text-align:right" > </td>					
										<td width="10%" align="center" class="borderBR padl5"><input type="text" name="txtTotalPrice'.$i.'" class="txtfieldItemLabel1" id="txtTotalPrice'.$i.'" readonly="yes" style="text-align:right" value="0.00"> </td>					
									</tr>' ;
									$i ++ ;
								}
							}
						}
						else
						{
							$i ++;								
								
						} 	
				   }			 
				  
				}
		if(isset($_GET['backOrder']))
		{
			$backOrder = $_GET['backOrder'];
		}
		else
		{
			$backOrder = 0;
		}
		if($backOrder != 1)
		{
			
		echo'<tr height="20">		
									
							
							<td width="4%" align="left" class="borderBR padl5">'.$j.'</td>
							<td width="8%" height="20" class="borderBR padl5"><div align="left" >
								<input name="txtProdCode'.$j.'" type="text" class="txtfield" id="txtProdCode'.$j.'" onKeyPress="return disableEnterKey(this,event,'.$j.')"  style="width: 75px;"/>
								<span id="indicator2" style="display: none"><img src="images/ajax-loader.gif" alt="Working..." /></span>                                      
								<div id="prod_choices'.$j.'" class="autocomplete" style="display:none"></div>
								<script type="text/javascript">							
									 //<![CDATA[
										var prod_choices = new Ajax.Autocompleter(\'txtProdCode'.$j.'\', \'prod_choices'.$j.'\', \'includes/scProductListAjaxSO.php?index='.$j.'\', {afterUpdateElement : getSelectionProductList, indicator: \'indicator2\'});																			
									//]]>
								</script>
								<input name="hProdID'.$j.'" type="hidden" id="hProdID'.$j.'" value="" />
								<input name="hSubsID'.$j.'" type="hidden" id="hSubsID'.$j.'" value=""/>
								<input name="hKitComponent'.$j.'" type="hidden" id="hKitComponent'.$j.'" value="" />
							</div></td>
							<td width="24%" align="left" class="borderBR padl5"><input name="txtProdDesc'.$j.'" type="text" class="txtfield" style="width: 300px" id="txtProdDesc'.$j.'"  readonly="yes" onKeyPress="return disableEnterKey(this,event,'.$j.')"  /></td>
							<td width="5%" align="center" class="borderBR padl5">Piece</td>
							<td width="8%" align="center" class="borderBR padl5"><input name="txtPMG'.$j.'" type="text" class="txtfieldItemLabel1" id="txtPMG'.$j.'"  readonly="yes" style="text-align:center"/><input type="hidden" name="hPMGID'.$j.'" id="hPMGID'.$j.'"   /><input type="hidden" name="hProductType'.$j.'" id="hProductType'.$j.'" /></td>					
							<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtUnitPrice'.$j.'" class="txtfieldItemLabel1" id="txtUnitPrice'.$j.'" readonly="yes" style="text-align:right" ></td>
							<td width="5%" align="center" class="borderBR padl5"><div id="divPromo'.$j.'" name="divPromo'.$j.'"></div><input type="hidden" name="hPromoID'.$j.'" id="hPromoID'.$j.'" /><input type="hidden" name="hPromoType'.$j.'" id="hPromoType'.$j.'" /><input type="hidden" name="hForIncentive'.$j.'" id="hForIncentive'.$j.'" value="0"/></td>		
							<td width="5%" align="center" class="borderBR padl5"><div id="divSOH'.$j.'" name="divSOH'.$j.'">&nbsp;</div><input type="hidden" name="hSOH'.$j.'" id="hSOH'.$j.'"></td>					
							<td width="8%" align="center" class="borderBR padl5"><div id="divTransit'.$j.'" name="divTransit'.$j.'">&nbsp;</div><input type="hidden" name="hTransit'.$j.'" id="hTransit'.$j.'"></td>
							<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtOrderedQty'.$j.'" class="txtfield3" id="txtOrderedQty'.$j.'" onKeyPress="return disableEnterKey(this,event,'.$j.')" style="text-align:right"/> <input type="hidden" name="hServed'.$j.'"   /></td>
							<td width="8%" align="center" class="borderBR padl5"><input type="text" name="txtEffectivePrice'.$j.'" class="txtfieldItemLabel1" id="txtEffectivePrice'.$j.'" readonly="yes" style="text-align:right"> </td>					
							<td width="10%" align="center" class="borderBR padl5"><input type="text" name="txtTotalPrice'.$j.'" class="txtfieldItemLabel1" id="txtTotalPrice'.$j.'" readonly="yes" style="text-align:right"> </td>					
						</tr>' ;				
		
		
		echo '</table>' ;
		}

		}
	
	

	
?>
