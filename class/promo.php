<?php
// Promo.php - Class that handles applicable promos given a set of products
//
// Author: Ryan C. Brozo
// Date: 2010-10-22
//
// Revisions: 2010-10-22 : Original Code

class Promo
{	
	private $database;
	// private $_mysqli = null;
	public $_debug = false;
	
	/**
	 * Used to print debugging messages. To turn off debugging, set the $_debug variable to false;
	 * @param $mesg
	 */
	private function debugecho($mesg)
	{
		if ($this->_debug)
		{
			echo $mesg;
		}
	}
	
	/**
	 * Used to print variables in a human-readable form. To turn off debugging, set the $_debug variable to false;
	 * @param $mesg
	 */
	private function print_r($var)
	{
	if ($this->_debug)
		{
			print_r($var);
		}
	}
	
	/**
	 * Main method called to calculate applicable promos. This method assumes
	 * that the productlist db table already contains the products that will be used
	 * to calculate which promos will be applied. 
	 */
	//public function CalculateBestPrice($database, $custID)
	public function CalculateBestPrice($database) 
	{
		//$customerID = $custID;
		$customerID = 0;
		$this->database = $database;

		// The first step is to retrieve a list of promos
		// where the products in the productlist table satisfy the product 
		// buyin requirements and entitlements. At this point, we still
		// do not know if the products themselves satisfy the quantity or price
		// requirements. This is just an initial filter, so to speak
		
		$promolist = $this->GetApplicablePromos(true,$customerID);
		//$promolist = array(148);
		
		if (!empty($promolist))
		{
			// If we obtained a list, now is the time to check whether indeed, the productlist
			// satisfy the other complicated parts of the buyin requirements. This also
			// calculates which of the promos applicable will result in maximum savings, and
			// applies it to the productlist 
			$this->Calculate($promolist, false, 0);
		}		
	}	
	
	/**
	 * Main method called to calculate applicable incentives.
	 */
	public function CalculateIncentives($database, $customerID)
	//public function CalculateIncentives($database) 
	{
		$this->database = $database;
		
		$database->execute("delete from applicableincentives where PHPSession='". session_id() ."';");
		/*$database->execute("INSERT INTO applicableincentives (PromoID,AmountBalance,QtyBalance,AmountAvailed,QtyAvailed,IsAvailed,PHPSession) " .
			"select ID,0,null,0,null,0,'" . session_id() ."' from promo where IsIncentive=1 and StatusID=6 and now() between StartDate and EndDate ;");
		*/
		
		// The first step is to insert the list of unclaimed products into 
		// the productlist table
		
		$query = "insert into productlist(ID,ProductID,Price,EffectivePrice,PromoID,AvailmentType,PMGID,PHPSession,tmpAvailed,PurchaseDate,ProductTypeID,GSUTypeID,ForIncentive,UnclaimedProductsID) "
			. "select null,ProductID,Price,null,null,null,PMGID,'" . session_id() . "',0,PurchaseDate,ProductTypeID,null,1,ID "
			. "from unclaimedproducts "
			. "where CustomerID=$customerID";

		$this->database->execute("delete from productlist where PHPSession='" . session_id() . "' and ForIncentive=1 and EffectivePrice is null");
		$this->database->execute($query);
		
		// Next is to retrieve a list of incentives 
		// where the products in the productlist table satisfy the product 
		// buyin requirements and entitlements. At this point, we still
		// do not know if the products themselves satisfy the quantity or price
		// requirements. This is just an initial filter, so to speak
		$incentivelist = $this->GetApplicablePromos(false,$customerID);
		//$incentivelist = array(235);
		if (!empty($incentivelist))
		{
			// If we obtained a list, now is the time to check whether indeed, the productlist
			// satisfy the other complicated parts of the buyin requirements. This also
			// calculates which of the promos applicable will result in maximum savings, and
			// applies it to the productlist 
			$this->CalcIncentives($incentivelist, false, $customerID);
		}
	}	

	/**
	 * Creates an applicable query depending on the given parameters. 
	 * 
	 * @param $productLevelID Determines the product level of the key ID the query will produce. 
	 * @param $startDate Used in conjunction with $endDate, this is used to filter the productlists purchase date
	 * @param $endDate Used in conjunction with $startDate, this is used to filter the productlists purchase date
	 * @param $isSuggestion Determines whether the table to use is either productlist or productlistpromosuggestion
	 */
	private function CreateProductListQuery($productLevelID, $startDate, $endDate, $isSuggestion, $isIncentives, $custID)
	{
		if ($isIncentives) 
		{
			$priceCol = "EffectivePrice";
		}
		else 
		{
			$priceCol = "Price";
		}
		if ($isSuggestion) 
		{
			$productTable = "productlistpromosuggestion";
		}
		else 
		{
			$productTable = "productlist";
		}
		
		// Depending on the $productLevelID, create the appropriate productlist query
		// that will be used in various joins of the main computation query 
		switch ($productLevelID)
		{
			case 1:
				$productlistQuery = "select p3.ID ProductID2,p.ProductID, gsutypeid, PHPSession,count(p.ID) Qty, max($priceCol) Price \n" .
			    "from $productTable p \n" .
			    "inner join product p1 on p1.id = p.ProductID \n" .
			    "inner join product p2 on p2.id = p1.ParentID \n" .
			    "inner join product p3 on p3.id = p2.ParentID \n" .
			    "where tmpAvailed=0 and PHPSession = '" . session_id() . "' \n" .
			    "and PurchaseDate between '$startDate' and '$endDate' \n" .
				"and ForIncentive=0 \n" .
			    "group by p3.ID,p.ProductID, p.gsutypeid,PHPSession";					
			    break;
			case 2:
				$productlistQuery = "select p2.ID ProductID2,p.ProductID, gsutypeid, PHPSession,count(p.ID) Qty, max($priceCol) Price \n" .
			    "from $productTable p \n" .
			    "inner join product p1 on p1.id = p.ProductID \n" .
			    "inner join product p2 on p2.id = p1.ParentID \n" .
			    "where tmpAvailed=0  and PHPSession = '" . session_id() . "' \n" .
			    "and PurchaseDate between '$startDate' and '$endDate' \n" .
				"and ForIncentive=0 \n" .
			    "group by p2.ID,p.ProductID, p.gsutypeid,PHPSession";					
			    break;
			case 3:
				$productlistQuery = "select p.ProductID ProductID2,p.ProductID, gsutypeid, PHPSession,count(p.ID) Qty, max($priceCol) Price \n" .
			    "from $productTable p \n" .
			    "where tmpAvailed=0  and PHPSession = '" . session_id() . "' \n" .
			    "and PurchaseDate between '$startDate' and '$endDate' \n" .
				"and ForIncentive=0 \n" .
			    "group by p.ProductID, p.gsutypeid,PHPSession";					
			    break;
			default:
				$productlistQuery = "";
				break;
		}
		return $productlistQuery;				
	}
	
	private function CreatePromoEntitlementQuery($promoBuyinID)
	{
		return "  select PromoBuyinID, ProductID, \n" .
		"  case \n" .
		// "    when Type=2 then pe.Qty \n" .
		"    when Type=2 then pe.Qty \n" .
		"    when Type=1 then Quantity \n" .
		"    else Quantity \n" .
		"  end Quantity, EffectivePrice,Type,pe.Qty PeQty,Quantity PedQty \n" .
		"    from promoentitlement pe \n" .
		"    inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
		"    where pe.PromoBuyinID=" . $promoBuyinID;
	}
	
	/**
	 * Determines which are the applicable incentives for the current sales order 
	 *  
	 * @param $incentivelist An array containing the list of incentive IDs
	 */
	private function CalcIncentives($incentivelist, $isSuggestion, $custID)
	{
		$productTable = "productlist";
		
		$hasAvailments = false; 
		$maxSavings = 0;
		$maxSavingsPromoID = 0;
		$maxSavingsPromoBuyinID = 0;
		$maxSavingsPromoType = 0;
		$maxSavingsAvailements = 0;
		$maxSavingsEntitlementType = 0;
		$maxSavingsPromoListIndex = 0;
		$maxSavingsProductQuery = "";
		$maxSavingsOverlayType = 0;
		$maxSavingsOverlayQty = 0;
		$maxSavingsStartDate = "";
		$maxSavingsEndDate = "";
		
		foreach ($incentivelist as $i => $incentive)
		{			
			// Determine promo attributes
			$query = "select PromoTypeID,StartDate,EndDate,OverlayType,OverlayQty,OverlayAmt from promo where ID=" . $incentive;
			$rs = $this->database->execute($query);
			$row = $rs->fetch_object();
			$promoTypeID = $row->PromoTypeID;
			$startDate = $row->StartDate;
			$endDate = $row->EndDate;
			
			$this->debugecho("startdate = $startDate enddate = $endDate <br/><br/>");
			
			// Enumarate all promobuyins associated with the promo
			$rsBuyins = $this->spSelectPromoBuyinByPromoID($incentive);
			while ($rowBuyin = $rsBuyins->fetch_object())
			{
				$productLevelID = $rowBuyin->ProductLevelID;
				
				// Get associated promoentitlement
				
				$this->debugecho("rowBuyin->ID = $rowBuyin->ID");
				$rsEntitlement = $this->spPromoSelectPromoEntitlementByPromoBuyinID($rowBuyin->ID);
				$rowEntitlement = $rsEntitlement->fetch_object();
				
				if ($rowEntitlement){
					$entitlementType = $rowEntitlement->Type;
					$entitlementQty = $rowEntitlement->Qty;
				}
				
				// Create appropriate productlist query
				$productlistQuery = $this->CreateProductListQuery($productLevelID, $startDate, $endDate, $isSuggestion, true, $custID);
				// Run the query to obtain the number of availments possible with the current promo
				$rsProductList = $this->database->execute($productlistQuery);
				$prodlistCnt = 0;
				while ($numProductListRow = $rsProductList->fetch_object())
				{
					$prodlistCnt += 1;					
				}
				$this->debugecho( "productlistQuery = " . $productlistQuery . "<br/><br/>" . "numProductList: " . $prodlistCnt . "<br/><br/>"); 
				
				// Determin the query header depending on whether
				// the buyin requirements are determined by quantity (Type 1)
				// or Amount (Type 2)
				if ($rowBuyin->Type == 1)
				{
					if (!$isSuggestion)
					{
						$numAvailmentsQueryHeader = "select min(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty))) NumAvailments ";
					}
					else 
					{
						$numAvailmentsQueryHeader = "select max(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty))) NumAvailments ";
					}
				}
				else 
				{
					if (!$isSuggestion)
					{
						$numAvailmentsQueryHeader = "select min(floor(Qty/SumItems)/(least(Quantity,MinAmtQty)/least(Quantity,MinAmtQty))) NumAvailments ";
					}
					else 
					{
						$numAvailmentsQueryHeader = "select max(floor(Qty/SumItems)/(least(Quantity,MinAmtQty)/least(Quantity,MinAmtQty))) NumAvailments ";
					}
				}
				
				// Create query to determine maximum number of availments
				// depending on the promo type (1-Single Line, 2 - MultiLine, 3 - Overlay, 4 - Step).
				//
				// Unlike promos, the query produced will only check if the qty/amt of products in the productlist satisfy the
				// buyin requirements only. 
				
				switch ($promoTypeID)
				{
					case 1: 
						// Single line and Multi line promos have the same way 
						// of determining the number of availments
						if ($entitlementType==1)
						{
							$numAvailmentsQuery2 = "select ifnull(p.Qty,0),ped.Quantity, \n".
									"case \n".
									"    when b.id is not null then null \n".
									"    else ifnull(MinQty,ifnull(p.Qty,ped.Quantity)) \n".
									"end MinQty,ifnull(MinQty,p.Qty),Price,MinAmt, \n" .
									"case EffectivePrice \n" .
									"   when 0 then ped.Quantity+ifnull(MinQty,0) \n" .
									"   else greatest(ped.Quantity,ifnull(MinQty,0)) \n" .
									"end SumItems ".
									"from ( \n" . $productlistQuery . ") p \n" .
									"left join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($rowBuyin->ID) . ")) b on b.productid=p.productid2 \n" .
									"right join (" . $this->CreatePromoEntitlementQuery($rowBuyin->ID) . ") ped on ped.ProductID=p.ProductID \n" .
									"where ped.ProductID is not null ";
						}
						else 
						{
							$numAvailmentsQuery2 = "select sum(ifnull(p.Qty,0)),ped.Quantity, \n".
									"case \n".
									"    when b.id is not null then null \n".
									"    else ifnull(MinQty,ifnull(p.Qty,ped.Quantity)) \n".
									"end MinQty,ifnull(MinQty,p.Qty),Price,MinAmt, \n" .
									"case EffectivePrice \n" .
									"   when 0 then ped.Quantity+ifnull(MinQty,0) \n" .
									"   else greatest(ped.Quantity,ifnull(MinQty,0)) \n" .
									"end SumItems ".
									"from ( \n" . $productlistQuery . ") p \n" .
									"left join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($rowBuyin->ID) . ")) b on b.productid=p.productid2 \n" .
									"right join (" . $this->CreatePromoEntitlementQuery($rowBuyin->ID) . ") ped on ped.ProductID=p.ProductID \n" .
									"where ped.ProductID is not null \n" .
									"group by ped.Quantity,ifnull(MinQty,p.Qty),ifnull(MinQty,p.Qty),Price,MinAmt,SumItems";
						}
						$numAvailmentsQuery = $numAvailmentsQueryHeader .
							"from ( \n" . 
							"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,1 Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
							"case \n" .
							"   when b.Type=1 then MinQty \n" .
							"   when b.Type=2 then ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
							"end SumItems ".
							"from ( \n" . $productlistQuery . ") p \n" .
							"right join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($rowBuyin->ID) . ")) b on b.productid=p.productid2 \n" .
							"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
							//"where b.id is not null";
							"where b.id is not null ";
							
						if (!$isSuggestion) 
						{
							$numAvailmentsQuery = $numAvailmentsQuery  . ") a";
						}
						break;
					case 2:
					
						$overlayType = 1;
						$overlayQty = 1;
						$numAvailmentsQuery = $this->CreateIncentiveMultilineNumAvailmentsQuery($rowBuyin->ID, $entitlementType, $startDate, $endDate, $isSuggestion);
						break;
						
					case 3: 
					
						// For overlay promo, we need to call a function
						// that produces a specialized query, which is also
						// based on the query that is used for single line 
						// and multi line promos
						
						$overlayType = $row->OverlayType;
						$overlayQty = $row->OverlayQty;
						$overlayAmt = $row->OverlayAmt;	
						$numAvailmentsQuery = $this->CreateIncentiveOverlayNumAvailmentsQuery($rowBuyin->ID, $entitlementType, $overlayType, $overlayQty, $overlayAmt, $startDate, $endDate, $isSuggestion);
						break;
						
					case 4: // step
						if ($entitlementType==1)
						{
							$numAvailmentsQuery2 = "select ifnull(p.Qty,0),ped.Quantity,ifnull(MinQty,p.Qty),ifnull(MinQty,p.Qty),Price,MinAmt, \n" .
								"Quantity SumItems \n" .
								"from ( \n" . $productlistQuery . ") p \n" .
								"left join ( \n" .
								"select PromoBuyinID,ProductID, \n" .
								"  case \n" .
								"    when Type=2 then pe.Qty \n" .
								"    when Type=1 then Quantity \n" .
								"    else Quantity \n" .
								"  end Quantity,EffectivePrice,Type,pe.Qty PeQty,Quantity PedQty \n" .
								"  from promoentitlement pe \n" .
								"  inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
								"  where pe.PromoBuyinID =" . $rowBuyin->ID . " \n" .
								") ped on ped.ProductID=p.ProductID \n" .
								"left join ( \n" .
								"  select * from promobuyin \n" .
								"  where id =" . $rowBuyin->ID . " \n" .
								") b on b.id=ped.PromoBuyinID \n" .
								"where p.Qty between MinQty and MaxQty ";								
						}
						else 
						{
							$numAvailmentsQuery2 = "select sum(ifnull(p.Qty,0)),ped.Quantity,ifnull(MinQty,p.Qty),ifnull(MinQty,p.Qty),Price,MinAmt, \n" .
								"Quantity SumItems \n" .
								"from ( \n" . $productlistQuery . ") p \n" .
								"left join ( \n" . $this->CreatePromoEntitlementQuery($rowBuyin->ID) . ") ped on ped.ProductID=p.ProductID \n" .
								"left join ( \n" .
								"  select * from promobuyin \n" .
								"  where id =" . $rowBuyin->ID . " \n" .
								") b on b.id=ped.PromoBuyinID \n" .
								"where p.Qty between MinQty and MaxQty \n" .
								"group by ped.Quantity,ifnull(MinQty,p.Qty),Price,MinAmt,Quantity";
						}
						
						$numAvailmentsQuery = $numAvailmentsQueryHeader .
							"from ( \n" .
							"select 1 Qty,1 Quantity,1 MinQty, 1 MinAmtQty,Price, 1 MinAmt, 1 SumItems \n" .
							"from ( \n" . $productlistQuery . ") p \n" .
							"right join ( \n" .
							"  select * from promobuyin \n" .
							"  where id =" . $rowBuyin->ID . " \n" .
							") b on b.productid=p.productid2 \n" .
							"left join (" . $this->CreatePromoEntitlementQuery($rowBuyin->ID) . ") ped on ped.ProductID=p.ProductID \n" .
							"where p.Qty between MinQty and MaxQty \n" .
							"union \n" . $numAvailmentsQuery2 . " \n" .
							") a";							
						break;
				}
				
				$numAvailmentsQuery = str_replace("tmpAvailed=0", "tmpAvailed in (0,1)", $numAvailmentsQuery);
				$this->debugecho( "promo id = $incentive  numAvailmentsQuery= " . $numAvailmentsQuery . "<br/><br/>");
				// Run the query to obtain the number of availments possible with the current promo
				$rsNumAvailments = $this->database->execute($numAvailmentsQuery);
				$numAvailmentsRow = $rsNumAvailments->fetch_object();
				$numAvailments = $numAvailmentsRow->NumAvailments;
				$this->debugecho( "numAvailments= " . $numAvailments . "<br/><br/>");
				
				// Create query to determine the amount of savings
				// depending on the entitlement type
				switch ($entitlementType)
				{
					case 1: // Set
						$savingsQuery = "select sum(Savings) Savings \n" . 
						"from promoentitlement pe \n" .
						"inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
						"where pe.PromoBuyinID=" . $rowBuyin->ID;
						break;
					case 2: // Selection
						$savingsQuery = "select sum(Savings) Savings \n" .
						"from ( \n" .
						"  select Savings \n" .
						"  from $productTable p \n" .
						"  inner join ( \n" .
						"    select ProductID,Savings \n" .
						"    from promoentitlement pe \n" .
						"    inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
						"    where pe.PromoBuyinID=" . $rowBuyin->ID .
						"  ) e on e.productid=p.productid \n" .
						"  where PHPSession = '" . session_id() . "' \n" .
						"  and PurchaseDate between '$startDate' and '$endDate' \n" .
						"  order by Savings desc limit \n" . $entitlementQty .
						") a";						
						break;
				}
				
				$this->debugecho( "savingsQuery= " . $savingsQuery . "<br/><br/>");
				
				// Obtain the number of availments possible with the current promo
				$rsSavings = $this->database->execute($savingsQuery);
				$savingsRow = $rsSavings->fetch_object();
				$savings = $savingsRow->Savings;
				
				$this->debugecho( "savings= " . $savings . "<br/><br/>");
				$this->debugecho( "promo savings= " . ($numAvailments * $savings) . "<br/><br/>");
				
				// If this promo has possible incentives, save it to the
				// applicableincentives
				//$numAvailments 
				if ($numAvailments > 0)
				{
					$this->InsertApplicableIncentives($incentive, session_id(),$numAvailments);
				}
			}
		}		
	}

	/**
	 * Determines which of the promos gives the maximum savings to the user, 
	 * and applies that promo to the productlist. This is the heart and soul
	 * of the class
	 *  
	 * @param $promolist An array containing the list of promo IDs
	 * @param $isSuggestion Determines whether to compute for main promolist table or suggestion table (promolistpromosuggestion)
	 */
	private function Calculate($promolist, $isSuggestion, $custID)
	{
		// Summary of the main logic in calculating the "best" possible promo combination is as follows
		// 
		// do 
		// 	 maxPromoid = 0
		// 	 maxSavings = 0
		// 	 hasAvailments = false
		// 	 for every promo in the promolist array
		// 		numAvailments = number of availments possible for the current promo in the productlist which are not yet availed (tmpAvailed=0)
		// 		savings = amount of savings of one availment for the current promo
		// 
		// 		promoSavings = numAvailments * savings
		// 		
		// 		if (promoSavings is greater than current maxSavings)
		// 			set this as the current maxSavings (maxSavings = promoSavings)
		// 			tag this promo for later use (maxPromoID = current promo)
		// 		end if
		// 	 end for
		// 	
		// 	 if we obtained a promo which gives the highest savings (maxPromoID>0)
		// 		set hasAvailments = true;
		// 		apply the buyin requirements and entitlements of promo with ID = maxPromoID to the productlist and tag the products as availed (tmpAvailed=1)
		// 	 end if
		// while hasAvailments = true 
		//
		// Note: This algorithm may or may not produce the best possible promo combination but most of the time, it
		// should give savings computation that is very close or equal to the theoretical maximum savings. Determining the latter, 
		// involves exhausting all possible promo combinations which can be computationally expensive and is exponentially proportional
		// to the number of distinct products in the productlist and the number of promos to against the list. 
		
		if ($isSuggestion) 
		{
			$productTable = "productlistpromosuggestion";
		}
		else 
		{
			$productTable = "productlist";
		}
		
		do {
			$hasAvailments = false; 
			$maxSavings = 0;
			$maxSavingsPromoID = 0;
			$maxSavingsPromoBuyinID = 0;
			$maxSavingsPromoType = 0;
			$maxSavingsAvailements = 0;
			$maxSavingsEntitlementType = 0;
			$maxSavingsPromoListIndex = 0;
			$maxSavingsProductQuery = "";
			$maxSavingsOverlayType = 0;
			$maxSavingsOverlayQty = 0;
			$maxSavingsOverlayAmt = 0;
			$maxSavingsStartDate = "";
			$maxSavingsEndDate = "";
			
			foreach ($promolist as $i => $promo)
			{
				$this->print_r($promolist);
				// Determine promo attributes
				$query = "select PromoTypeID,StartDate,EndDate,OverlayType,OverlayQty,OverlayAmt from promo where ID=" . $promo;
				$rs = $this->database->execute($query);
				$row = $rs->fetch_object();
				$promoTypeID = $row->PromoTypeID;
				$startDate = $row->StartDate;
				$endDate = $row->EndDate;
				
				$this->debugecho("startdate = $startDate enddate = $endDate <br/><br/>");
				
				
				// Enumarate all promobuyins associated with the promo
				$rsBuyins = $this->spSelectPromoBuyinByPromoID($promo);
				while ($rowBuyin = $rsBuyins->fetch_object())
				{
					$productLevelID = $rowBuyin->ProductLevelID;
					
					// Get associated promoentitlement
					$rsEntitlement = $this->spPromoSelectPromoEntitlementByPromoBuyinID($rowBuyin->ID);
					$rowEntitlement = $rsEntitlement->fetch_object();
					$entitlementType = $rowEntitlement->Type;
					$entitlementQty = $rowEntitlement->Qty;
					
					// Create appropriate productlist query
					$productlistQuery = $this->CreateProductListQuery($productLevelID, $startDate, $endDate, $isSuggestion, false, 0);
					
					$this->debugecho( "productlistQuery = " . $productlistQuery . "<br/><br/>"); 
					
					// Determin the query header depending on whether
					// the buyin requirements are determined by quantity (Type 1)
					// or Amount (Type 2)
					if ($rowBuyin->Type==1)
					{
						if (!$isSuggestion)
						{
							$numAvailmentsQueryHeader = "select min(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty))) NumAvailments ";
							//$numAvailmentsQueryHeader = "select min(ifnull((floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty))),0)) NumAvailments ";
						}
						else 
						{
							$numAvailmentsQueryHeader = "select max(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty))) NumAvailments ";
							//$numAvailmentsQueryHeader = "select max(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty))) NumAvailments ";
						}
					}
					else 
					{
						if (!$isSuggestion)
						{
							$numAvailmentsQueryHeader = "select min(floor(Qty/SumItems)/(least(Quantity,MinAmtQty)/least(Quantity,MinAmtQty))) NumAvailments ";
							//$numAvailmentsQueryHeader = "select min(ifnull((floor(Qty/SumItems)/(least(Quantity,MinAmtQty)/least(Quantity,MinAmtQty))),0)) NumAvailments ";
						}
						else 
						{
							$numAvailmentsQueryHeader = "select max(floor(Qty/SumItems)/(least(Quantity,MinAmtQty)/least(Quantity,MinAmtQty))) NumAvailments ";
							//$numAvailmentsQueryHeader = "select max(floor(Qty/SumItems)/(least(Quantity,MinAmtQty)/least(Quantity,MinAmtQty))) NumAvailments ";
						}
					}
					
					// Create query to determine maximum number of availments
					// depending on the promo type (1-Single Line, 2 - MultiLine, 3 - Overlay, 4 - Step).
					//
					// The query produced will check if the qty/amt of products in the productlist satisfy both the
					// buyin and entitlements (entitlement-inclusive). To clarify, consider this promo example
					// 
					// Productlist: 5A 1B
					// Buyin Reqt: 1A
					// Entitlement: 1B
					//
					// If we will not consider the entitlements (entitlement-exclusive), the productlist can avail of 
					// the promo 5 times but if we will also consider the entitlements (entitlement-inclusive), 
					// the productlist can only avail of the promo once because there is only one product B in the 
					// productlist
					 
					switch ($promoTypeID)
					{
						case 1: 
							// Single line and Multi line promos have the same way 
							// of determining the number of availments
							if ($entitlementType==1)
							{
								$numAvailmentsQuery2 = "select ifnull(p.Qty,0),ped.Quantity, \n".
										"case \n".
										"    when b.id is not null then null \n".
										"    else ifnull(MinQty,ifnull(p.Qty,ped.Quantity)) \n".
										"end MinQty,ifnull(MinQty,p.Qty),Price,MinAmt, \n" .
										"case EffectivePrice \n" .
										"   when 0 then ped.Quantity+ifnull(MinQty,0) \n" .
										"   else greatest(ped.Quantity,ifnull(MinQty,0)) \n" .
										"end SumItems ".
										"from ( \n" . $productlistQuery . ") p \n" .
										"left join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($rowBuyin->ID) . ")) b on b.productid=p.productid2 \n" .
										"right join (" . $this->CreatePromoEntitlementQuery($rowBuyin->ID) . ") ped on ped.ProductID=p.ProductID \n" .
										"where ped.ProductID is not null ";
							}
							else 
							{
								$numAvailmentsQuery2 = "select sum(ifnull(p.Qty,0)),ped.Quantity, \n".
										"case \n".
										"    when b.id is not null then null \n".
										"    else ifnull(MinQty,ifnull(p.Qty,ped.Quantity)) \n".
										"end MinQty,ifnull(MinQty,p.Qty),Price,MinAmt, \n" .
										"case EffectivePrice \n" .
										"   when 0 then ped.Quantity+ifnull(MinQty,0) \n" .
										"   else greatest(ped.Quantity,ifnull(MinQty,0)) \n" .
										"end SumItems ".
										"from ( \n" . $productlistQuery . ") p \n" .
										"left join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($rowBuyin->ID) . ")) b on b.productid=p.productid2 \n" .
										"right join (" . $this->CreatePromoEntitlementQuery($rowBuyin->ID) . ") ped on ped.ProductID=p.ProductID \n" .
										"where ped.ProductID is not null \n" .
										"group by ped.Quantity,ifnull(MinQty,p.Qty),ifnull(MinQty,p.Qty),Price,MinAmt,SumItems";
							}
							$numAvailmentsQuery = $numAvailmentsQueryHeader .
								"from ( \n" . 
								"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,ifnull(ped.Quantity,1) Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
								"case \n" .
								"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+MinQty \n" .
								"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+MinQty \n" . 
								"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),MinQty) \n" . 
								"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),MinQty) \n" . 
								"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
								"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
								"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" . 
								"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" .
								"end SumItems ".
								"from ( \n" . $productlistQuery . ") p \n" .
								"right join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($rowBuyin->ID) . ")) b on b.productid=p.productid2 \n" .
								"left join (" . $this->CreatePromoEntitlementQuery($rowBuyin->ID) . ") ped on ped.ProductID=p.ProductID \n" .
								"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
								//"where b.id is not null";
								"where b.id is not null ";
							if (!$isSuggestion) 
							{
								$numAvailmentsQuery = $numAvailmentsQuery . " union \n" . $numAvailmentsQuery2 . ") a";
							}
							break;
						case 2:
							$overlayType = 1;
							$overlayQty = 1;
							
							$numAvailmentsQuery = $this->CreateMultilineNumAvailmentsQuery($rowBuyin->ID, $entitlementType, $startDate, $endDate, $isSuggestion);
							
							break;
							
						case 3: 
							// For overlay promo, we need to call a function
							// that produces a specialized query, which is also
							// based on the query that is used for single line 
							// and multi line promos
							
							$overlayType = $row->OverlayType;
							$overlayQty = $row->OverlayQty;
							$overlayAmt = $row->OverlayAmt;
							
							$numAvailmentsQuery = $this->CreateOverlayNumAvailmentsQuery($rowBuyin->ID, $entitlementType, $overlayType, $overlayQty, $overlayAmt, $startDate, $endDate, $isSuggestion);
							
							break;
						case 4: // step
							if ($entitlementType==1)
							{
								$numAvailmentsQuery2 = "select ifnull(p.Qty,0),ped.Quantity,ifnull(MinQty,p.Qty),ifnull(MinQty,p.Qty),Price,MinAmt, \n" .
									"Quantity SumItems \n" .
									"from ( \n" . $productlistQuery . ") p \n" .
									"left join ( \n" .
									"select PromoBuyinID,ProductID, \n" .
									"  case \n" .
									"    when Type=2 then pe.Qty \n" .
									"    when Type=1 then Quantity \n" .
									"    else Quantity \n" .
									"  end Quantity,EffectivePrice,Type,pe.Qty PeQty,Quantity PedQty \n" .
									"  from promoentitlement pe \n" .
									"  inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
									"  where pe.PromoBuyinID =" . $rowBuyin->ID . " \n" .
									") ped on ped.ProductID=p.ProductID \n" .
									"left join ( \n" .
									"  select * from promobuyin \n" .
									"  where id =" . $rowBuyin->ID . " \n" .
									") b on b.id=ped.PromoBuyinID \n" .
									"where p.Qty between MinQty and MaxQty ";								
							}
							else 
							{
								$numAvailmentsQuery2 = "select sum(ifnull(p.Qty,0)),ped.Quantity,ifnull(MinQty,p.Qty),ifnull(MinQty,p.Qty),Price,MinAmt, \n" .
									"Quantity SumItems \n" .
									"from ( \n" . $productlistQuery . ") p \n" .
									"left join ( \n" . $this->CreatePromoEntitlementQuery($rowBuyin->ID) . ") ped on ped.ProductID=p.ProductID \n" .
									"left join ( \n" .
									"  select * from promobuyin \n" .
									"  where id =" . $rowBuyin->ID . " \n" .
									") b on b.id=ped.PromoBuyinID \n" .
									"where p.Qty between MinQty and MaxQty \n" .
									"group by ped.Quantity,ifnull(MinQty,p.Qty),Price,MinAmt,Quantity";
							}
							
							$numAvailmentsQuery = $numAvailmentsQueryHeader .
								"from ( \n" .
								"select 1 Qty,1 Quantity,1 MinQty, 1 MinAmtQty,Price, 1 MinAmt, 1 SumItems \n" .
								"from ( \n" . $productlistQuery . ") p \n" .
								"right join ( \n" .
								"  select * from promobuyin \n" .
								"  where id =" . $rowBuyin->ID . " \n" .
								") b on b.productid=p.productid2 \n" .
								"left join (" . $this->CreatePromoEntitlementQuery($rowBuyin->ID) . ") ped on ped.ProductID=p.ProductID \n" .
								"where p.Qty between MinQty and MaxQty \n" .
								"union \n" . $numAvailmentsQuery2 . " \n" .
								") a";							
							break;
					}
					
					$this->debugecho( "promo id = $promo  numAvailmentsQuery= " . $numAvailmentsQuery . "<br/><br/>");
					// Run the query to obtain the number of availments possible with the current promo
					$rsNumAvailments = $this->database->execute($numAvailmentsQuery);
					$numAvailmentsRow = $rsNumAvailments->fetch_object();
					$numAvailments = $numAvailmentsRow->NumAvailments;
					$this->debugecho( "numAvailments= " . $numAvailments . "<br/><br/>");
					
					// Create query to determine the amount of savings
					// depending on the entitlement type
					switch ($entitlementType)
					{
						case 1: // Set
							$savingsQuery = "select sum(Savings) Savings \n" . 
							"from promoentitlement pe \n" .
							"inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
							"where pe.PromoBuyinID=" . $rowBuyin->ID;
							break;
						case 2: // Selection
							$savingsQuery = "select sum(Savings) Savings \n" .
							"from ( \n" .
							"  select Savings \n" .
							"  from $productTable p \n" .
							"  inner join ( \n" .
							"    select ProductID,Savings \n" .
							"    from promoentitlement pe \n" .
							"    inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
							"    where pe.PromoBuyinID=" . $rowBuyin->ID .
							"  ) e on e.productid=p.productid \n" .
							"  where tmpAvailed=0  and PHPSession = '" . session_id() . "' \n" .
							"  and PurchaseDate between '$startDate' and '$endDate' \n" .
							"  order by Savings desc limit \n" . $entitlementQty .
							") a";						
							break;
					}
					
					$this->debugecho( "savingsQuery= " . $savingsQuery . "<br/><br/>");
					
					// Obtain the number of availments possible with the current promo
					$rsSavings = $this->database->execute($savingsQuery);
					$savingsRow = $rsSavings->fetch_object();
					$savings = $savingsRow->Savings;
					
					$this->debugecho( "savings= " . $savings . "<br/><br/>");
					$this->debugecho( "promo savings= " . ($numAvailments * $savings) . "<br/><br/>");
					
					// If this promo has possible availments, save it to the
					// productlistpromo 
					if ($numAvailments>0)
					{
						$this->InsertApplicablePromo($rowBuyin->ID,$startDate,$endDate,$promoTypeID);
					}
					
					// Tag this promo if this has the greatest amount of savings.
					// We will apply this promo later. But first we need to check the priorities 
					// of the promo. Overlay always has a higher priority than multiline
					// than single line
					if (($promoTypeID==$maxSavingsPromoType)&&(($numAvailments * $savings) > $maxSavings)
                        || ($promoTypeID>$maxSavingsPromoType && ($numAvailments * $savings)>0))
					{
						$maxSavings = $numAvailments * $savings;
						$maxSavingsPromoID = $promo;
						$maxSavingsPromoBuyinID = $rowBuyin->ID;
						$maxSavingsAvailements = $numAvailments;
						$maxSavingsPromoType = $promoTypeID;
						$maxSavingsEntitlementType = $entitlementType;
						$maxSavingsPromoListIndex = $i;
						$maxSavingsProductQuery = $productlistQuery;
						$maxSavingsStartDate = $startDate;
						$maxSavingsEndDate = $endDate;
						if ($promoTypeID==3)
						{
							$maxSavingsOverlayType = $overlayType;
							$maxSavingsOverlayQty = $overlayQty;
							$maxSavingsOverlayAmt = $overlayAmt;
						}
						else 
						{
							$maxSavingsOverlayType = 0;
							$maxSavingsOverlayQty = 0;
							$maxSavingsOverlayAmt = 0;
						}
						$this->debugecho( "MaxSavings=$maxSavings, MaxSavingsPromoID=$maxSavingsPromoID, MaxSavingsAvailements=$maxSavingsAvailements MaxSavingsPromoType=$maxSavingsPromoType MaxSavingsPromoBuyinID=$maxSavingsPromoBuyinID<br/><br/>");
					}
													
				}
			}
			
			// Check which promo obtained the maximum savings
			// and apply it to the product list. If there are
			// no promos applicable with the current product list,
			// this mean's we're already done
			if ($maxSavingsPromoID>0)
			{
				if ($maxSavingsPromoType==3 && $maxSavingsOverlayType==2) 
				{
					$hasAvailments = $this->ApplySelectionOverlayPromo($maxSavingsPromoID,$maxSavingsPromoType,$maxSavingsAvailements,$maxSavingsEntitlementType,$maxSavingsProductQuery,$maxSavingsOverlayType,$maxSavingsOverlayQty,$maxSavingsOverlayAmt,$maxSavingsStartDate,$maxSavingsEndDate,$isSuggestion);
				}
				else 
				{
					$hasAvailments = $this->ApplyPromo($maxSavingsPromoID,$maxSavingsPromoType,$maxSavingsPromoBuyinID,$maxSavingsAvailements,$maxSavingsEntitlementType,$maxSavingsProductQuery,$maxSavingsOverlayType,$maxSavingsOverlayQty,$maxSavingsOverlayAmt,$maxSavingsStartDate,$maxSavingsEndDate,$isSuggestion);
				}
				// $hasAvailments = false;
				
				// If the applied promo is not a single line item,
				// remove the current promo so it will not be used again.
				// If it's a single line promo, there might be a chance
				// that other buyin requirements will still be satisfied
				// by the same promo, so we do won't remove it 
				if ($maxSavingsPromoType!=1)
				{
					unset($promolist[$maxSavingsPromoListIndex]);
				}
			}
			else 
			{
				$hasAvailments = false;
			} 
			
		}
		while ($hasAvailments==true);
		$this->spCleanupProductListPromo();
		$this->debugecho( "No more availments");
	}
	
	
	
	
	private function ApplySelectionOverlayPromo($maxSavingsPromoID,$maxSavingsPromoType,$maxSavingsAvailements,$maxSavingsEntitlementType, $productlistQuery,$maxSavingsOverlayType,$maxSavingsOverlayQty,$maxSavingsOverlayAmt,$startDate,$endDate, $isSuggestion)
	{
		$hasAvailments = false;
		$rsPromoBuyin = $this->database->execute("SELECT ID from promobuyin where PromoID=$maxSavingsPromoID and LevelType=0");
		$promoBuyin = $rsPromoBuyin->fetch_object();
		$promoBuyinID = $promoBuyin->ID;
		
		$buyinquery = "select p.ID,p.ProductID,ifnull(ped.Qty,1) Qty,ped.EffectivePrice,ped.PMGID \n" .
			"from productlist p \n" .
			"inner join promobuyin b on b.ProductID=p.ProductID and b.id in (" . $this->GetChildPromoBuyinIDs($promoBuyinID) . ") \n" .
			"left join (  \n" .
			"select ProductID,Qty,EffectivePrice,Savings,PMGID  \n" .
			"from promoentitlement pe \n" .
			"inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
			"where pe.PromoBuyinID=$promoBuyinID \n" .
			") ped on ped.ProductID=p.ProductID \n" .
			"where p.tmpAvailed=0 and PHPSession='" . session_id() . "' and PurchaseDate between '$startDate' and '$endDate'  \n" .
			"order by Savings,p.ID,p.ProductID desc;";
		
		$this->debugecho("maxSavingsPromoID = $maxSavingsPromoID buyinquery = $buyinquery <br/><br/>");
		
		$i=0;
		do 
		{
			$rsBuyin = $this->database->execute($buyinquery);
			$buyin = $rsBuyin->fetch_object();
			
			$j=0;
			
			if ($maxSavingsOverlayQty>0){
				$entitlementQty = 0;
				while (!empty($buyin) && $j<$maxSavingsOverlayQty)
				{
					$entitlementQty = $buyin->Qty;
					
					$id = $buyin->ID;
					$query = "update productlist set PromoID=$maxSavingsPromoID, AvailmentType=1, tmpAvailed=1 where ID=$id";
					$this->database->execute($query);
					
					$j++;
					$buyin = $rsBuyin->fetch_object();
				}
				
				$query = "select p.ID,p.ProductID,ped.EffectivePrice,ped.PMGID \n" . 
					"from productlist p \n" .
					"inner join (  \n" .
					"	select ProductID,EffectivePrice,Savings,PMGID  \n" .
					"	from promoentitlement pe \n" .
					"	inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
					"	where pe.PromoBuyinID=$promoBuyinID \n" .
					"	) ped on ped.ProductID=p.ProductID \n" .
					"left join promobuyin b on b.ProductID=p.ProductID and b.id in (" . $this->GetChildPromoBuyinIDs($promoBuyinID) . ") \n" .
					"where (b.id is null and  p.tmpAvailed=0) or (p.PromoID=$maxSavingsPromoID and p.AvailmentType=1 and p.tmpAvailed=1) \n" .
					"and PHPSession='" . session_id() . "' and PurchaseDate between '$startDate' and '$endDate'  \n" .
					"order by AvailmentType desc,Savings limit $entitlementQty";
				$rsAvailement = $this->database->execute($query);
				
				$this->debugecho("avquery = $query<br/><br/>");
				
				while ($availment = $rsAvailement->fetch_object())
				{
					$id = $availment->ID;
					$pmgid = $availment->PMGID;
					$effectiveprice = $availment->EffectivePrice; 
					$query = "update productlist set PromoID=$maxSavingsPromoID, AvailmentType=ifnull(AvailmentType,0)+2, tmpAvailed=1, EffectivePrice=$effectiveprice, PMGID=$pmgid where ID=$id";
					$this->debugecho("avquery2 = $query<br/><br/>");
					$this->database->execute($query);
				}
			}
			else {
				$entitlementAmt = 0;
				while (!empty($buyin) && $j<$maxSavingsOverlayAmt)
				{
					$entitlementQty = $buyin->Price;
					
					$id = $buyin->ID;
					$query = "update productlist set PromoID=$maxSavingsPromoID, AvailmentType=1, tmpAvailed=1 where ID=$id";
					$this->database->execute($query);
					
					$j++;
					$buyin = $rsBuyin->fetch_object();
				}
			}
			
			
			
			$hasAvailments = true;
			$i++;
		}	
		while (!empty($buyin) && $i<$maxSavingsAvailements);
		
		return $hasAvailments;
	} 
	
    public function ApplyIncentives($database,$promoID,$numAvailments)
	{
		// Get necessary information from the given promoid
		$this->database = $database;
		
		$rsPromo = $this->database->execute("SELECT PromoTypeID,OverlayType,OverlayQty,OverlayAmt,StartDate,EndDate FROM promo WHERE ID=$promoID");
		if ($rowPromo = $rsPromo->fetch_object())
		{
            $promoType = $rowPromo->PromoTypeID;
            $overlayType = $rowPromo->OverlayType;
            $overlayQty = $rowPromo->OverlayQty;
            $overlayAmt = $rowPromo->OverlayAmt;
           	$startDate = $rowPromo->StartDate;
            $endDate = $rowPromo->EndDate;
            
            if ($promoType == 3 && $overlayType == 2) 
            {
                $this->ApplySelectionOverlayIncentive($promoID,$numAvailments,$promoType,$overlayQty,$overlayAmt,$startDate,$endDate);
            }
            else 
            {
                $this->ApplyNormalIncentive($promoID,$numAvailments,$promoType,$overlayType,$startDate,$endDate);
            }
        }
    }
    
    private function ApplySelectionOverlayIncentive($promoID,$numAvailements,$promoType,$overlayQty,$overlayAmt,$startDate,$endDate)
	{
        $hasAvailments = false;
        $rsPromoBuyin = $this->database->execute("SELECT ID from promobuyin where PromoID=$promoID and LevelType=0");
        $promoBuyin = $rsPromoBuyin->fetch_object();
        $promoBuyinID = $promoBuyin->ID;
        
        $buyinquery = "select p.ID,p.ProductID \n" .
            "from productlist p \n" .
            "inner join promobuyin b on b.ProductID=p.ProductID and b.id in (" . $this->GetChildPromoBuyinIDs($promoBuyinID) . ") \n" .
            "where p.tmpAvailed=0 and PHPSession='" . session_id() . "' and PurchaseDate between '$startDate' and '$endDate'  \n" .
            "order by p.ID,p.ProductID desc;";
        
        $this->debugecho("promoID = $promoID buyinquery = $buyinquery <br/><br/>");
        
        $i=0;
        do 
        {
            $rsBuyin = $this->database->execute($buyinquery);
            $buyin = $rsBuyin->fetch_object();
            
            $j=0;
            
            if ($overlayQty>0){
                $entitlementQty = 0;
                while (!empty($buyin) && $j<$overlayQty*$numAvailements)
                {
                    $entitlementQty = $buyin->Qty;
                    
                    $id = $buyin->ID;
                    $query = "update productlist set PromoID=$promoID, AvailmentType=1, tmpAvailed=1 where ID=$id";
                    $this->database->execute($query);
                    
                    $j++;
                    $buyin = $rsBuyin->fetch_object();
                }
                
                $query = "select p.ID,p.ProductID \n" . 
                    "from productlist p \n" .
                    "left join promobuyin b on b.ProductID=p.ProductID and b.id in (" . $this->GetChildPromoBuyinIDs($promoBuyinID) . ") \n" .
                    "where (b.id is null and  p.tmpAvailed=0) or (p.PromoID=$promoID and p.AvailmentType=1 and p.tmpAvailed=1) \n" .
                    "and PHPSession='" . session_id() . "' and PurchaseDate between '$startDate' and '$endDate'  \n" .
                    "order by AvailmentType desc limit $entitlementQty";
                $rsAvailement = $this->database->execute($query);
                
                $this->debugecho("avquery = $query<br/><br/>");
                
                while ($availment = $rsAvailement->fetch_object())
                {
                    $id = $availment->ID;
                    $effectiveprice = $availment->EffectivePrice; 
                    $query = "update productlist set IncentiveID=$promoID, tmpAvailed=1, where ID=$id";
                    $this->debugecho("avquery2 = $query<br/><br/>");
                    $this->database->execute($query);
                }
            }
            else {
                $entitlementAmt = 0;
                while (!empty($buyin) && $j<$numAvailements)
                {
                    $id = $buyin->ID;
                    $query = "update productlist set IncentiveID=$promoID, tmpAvailed=1 where ID=$id";
                    $this->database->execute($query);
                    
                    $j++;
                    $buyin = $rsBuyin->fetch_object();
                }
            }
            $hasAvailments = true;
            $i++;
        }	
        while (!empty($buyin) && $i<$numAvailements);
	}
    
	public function ApplyNormalIncentive($promoID,$numAvailments,$promoType,$overlayType,$startDate,$endDate)
	{
        $rsBuyins = $this->spSelectPromoBuyinByPromoID($promoID);
        $selectionEntitlementQty = 0;
        $hasAvailments = false;
        while ($rowBuyin = $rsBuyins->fetch_object())
        {
        	$productLevelID = $rowBuyin->ProductLevelID;
        	
        	$productlistQuery = $this->CreateProductListQuery($productLevelID, $startDate, $endDate, false, true, 0);
        	
            if ($promoType==3 && $overlayType==1) //overlay
            {
                $availmentsQuery = $this->CreateOverlayIncentivesAvailmentsQuery($rowBuyin->ID);
            }
            else 
            {
                $header = "select distinct p.ProductID,Type,MinQty,ifnull(MaxQty,MinQty) MaxQty,MinAmt,ifnull(MaxAmt,MinAmt) MaxAmt,p.Qty,Price ";
                $availmentsQuery = $header . 
                    "from ( \n" . $productlistQuery . ") p \n" .
                    "left join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($rowBuyin->ID) . ")) b on b.productid=p.productid2 \n" .
                    "where b.id is not null order by Type desc";
            }
            $this->debugecho( "availmentsQuery = " . $availmentsQuery . "<br/><br/>");
            $rsAvailments = $this->database->execute($availmentsQuery);
            while ($availmentsRow = $rsAvailments->fetch_object())
            {
                if ($availmentsRow->Type==1)
                {
                    if ($promoType==4)
                    {
                        if ($availmentsRow->MaxQty < $availmentsRow->Qty)
                        {
                            $buyinQty = $availmentsRow->MaxQty;
                        }
                        else 
                        {
                            $buyinQty = $availmentsRow->Qty;
                        }
                    }
                    else
                    {
                        $buyinQty = $numAvailments * $availmentsRow->MinQty;
                    }
                }
                else 
                {
                    if ($promoType==4)
                    {
                        if (floor($availmentsRow->MaxAmt/$availmentsRow->Price) < $availmentsRow->Qty)
                        {
                            $buyinQty = floor($availmentsRow->MaxAmt/$availmentsRow->Price);
                        }
                        else 
                        {
                            $buyinQty = $availmentsRow->Qty;
                        }
                    }
                    else
                    {
                        $buyinQty = $numAvailments * ceil($availmentsRow->MinAmt/$availmentsRow->Price);
                    }
                }
                $this->spPromoUpdateProductListIncentive(
                    $availmentsRow->ProductID,
                    $buyinQty,
                    $maxSavingsPromoID);
                $hasAvailments = true;
            }
        }
	}
	
	private function ApplyPromo($maxSavingsPromoID,$maxSavingsPromoType,$maxSavingsPromoBuyinID,$maxSavingsAvailements,$maxSavingsEntitlementType, $productlistQuery,$maxSavingsOverlayType,$maxSavingsOverlayQty,$maxSavingsOverlayAmt,$startDate,$endDate, $isSuggestion)
	{
		// if the promo to be applied is a single line,
		// apply $maxSavingsAvailements only to the given
		// $maxSavingsPromoBuyinID
		if ($maxSavingsPromoType==1 || $maxSavingsPromoType==4) 
		{
			$rsBuyins = $this->spSelectPromoBuyinByID($maxSavingsPromoBuyinID);
		}
		else 
		{
			$rsBuyins = $this->spSelectPromoBuyinByPromoID($maxSavingsPromoID);
		}
		$selectionEntitlementQty = 0;
		$hasAvailments = false;
		while ($rowBuyin = $rsBuyins->fetch_object())
		{
			if ($maxSavingsPromoType==3 && $maxSavingsOverlayType==1) //overlay
			{
				$availmentsQuery = $this->CreateOverlayAvailmentsQuery($rowBuyin->ID, $maxSavingsEntitlementType, $isSuggestion);
			}
			else 
			{
				$header = "select distinct p.ProductID,Type,EntitlementType,EntitlementQty, MinQty,ifnull(MaxQty,MinQty) MaxQty,MinAmt,ifnull(MaxAmt,MinAmt) MaxAmt,Quantity,EffectivePrice,p.Qty,Price,PMGID ";
				if ($maxSavingsEntitlementType==1)
				{
					$entitlementQuery = "select ProductID, EffectivePrice, Quantity, PMGID, Type EntitlementType, pe.Qty EntitlementQty";
					$entitlementQueryFooter= "";
				}
				else 
				{
					$entitlementQuery = "select ProductID, EffectivePrice, Quantity, PMGID, max(Type) EntitlementType, max(pe.Qty) EntitlementQty";
					$entitlementQueryFooter= "group by  ProductID, EffectivePrice, Quantity, PMGID";
				}
				$availmentsQuery = $header . 
					"from ( \n" . $productlistQuery . ") p \n" .
					"left join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($rowBuyin->ID) . ")) b on b.productid=p.productid2 \n" .
					"left join (" .
					$entitlementQuery . " \n" .
					"    from promoentitlement pe \n" .
					"    inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
					"    where pe.PromoBuyinID=" . $rowBuyin->ID . " " . $entitlementQueryFooter . " \n" .
					") ped on ped.ProductID=p.ProductID \n" .
					"where b.id is not null or ped.ProductID is not null order by Type desc";
			}
			$this->debugecho( "availmentsQuery = " . $availmentsQuery . "<br/><br/>");
			$rsAvailments = $this->database->execute($availmentsQuery);
			while ($availmentsRow = $rsAvailments->fetch_object())
			{
				if (!empty($availmentsRow->Type))
				{
					if ($availmentsRow->Type==1)
					{
						if ($maxSavingsPromoType==4)
						{
							if ($availmentsRow->MaxQty < $availmentsRow->Qty)
							{
								$buyinQty = $availmentsRow->MaxQty;
							}
							else 
							{
								$buyinQty = $availmentsRow->Qty;
							}
						}
						else
						{
							$buyinQty = $maxSavingsAvailements * $availmentsRow->MinQty;
						}
					}
					else 
					{
						if ($maxSavingsPromoType==4)
						{
							if (floor($availmentsRow->MaxAmt/$availmentsRow->Price) < $availmentsRow->Qty)
							{
								$buyinQty = floor($availmentsRow->MaxAmt/$availmentsRow->Price);
							}
							else 
							{
								$buyinQty = $availmentsRow->Qty;
							}
						}
						else
						{
							$buyinQty = $maxSavingsAvailements * ceil($availmentsRow->MinAmt/$availmentsRow->Price);
						}
					}
					
					if ($availmentsRow->EntitlementType==1)
					{
						if ($maxSavingsPromoType==4)
						{
							$availmentQty = $maxSavingsAvailements * $buyinQty;
						}
						else 
						{
							$availmentQty = $maxSavingsAvailements * $availmentsRow->Quantity;
						}
					}
					else 
					{
					if ($maxSavingsPromoType==4)
						{
							$availmentQty = $maxSavingsAvailements * $buyinQty;
						}
						else 
						{
							$availmentQty = $maxSavingsAvailements * $availmentsRow->Quantity;
							//$availmentQty = $maxSavingsAvailements * $availmentsRow->EntitlementQty;
						}
						
					}
					
					if (!empty($availmentsRow->EffectivePrice))
					{
						if ($availmentsRow->EffectivePrice==0)
						{
							// buy 1 take x
							
							// Apply buyin-entitlement items first
							$this->spPromoUpdateProductList(
								$availmentsRow->ProductID,
								$buyinQty,
								$availmentsRow->Price,
								$maxSavingsPromoID,
								1,
								$availmentsRow->PMGID);
							

							// Then apply entitlement items next
							$this->spPromoUpdateProductList(
								$availmentsRow->ProductID,
								$availmentQty,
								$availmentsRow->EffectivePrice,
								$maxSavingsPromoID,
								2,
								$availmentsRow->PMGID);
						}
						else  
						{	
							// discounted price
							
							// Apply buyin-entitlement items first. Quantity
							// should be the lower between buyinQty and availmentQty
							
							if ($buyinQty < $availmentQty)
							{
								$this->spPromoUpdateProductList(
									$availmentsRow->ProductID,
									$buyinQty,
									$availmentsRow->EffectivePrice / $availmentsRow->Quantity,
									$maxSavingsPromoID,
									3,
									$availmentsRow->PMGID);
									
								// Apply the difference between the two values as entitlement
								$this->spPromoUpdateProductList(
									$availmentsRow->ProductID,
									$availmentQty - $buyinQty,
									$availmentsRow->EffectivePrice / $availmentsRow->Quantity,
									$maxSavingsPromoID,
									2,
									$availmentsRow->PMGID);
							}
							else 
							{
								$this->spPromoUpdateProductList(
									$availmentsRow->ProductID,
									$availmentQty,
									$availmentsRow->EffectivePrice / $availmentsRow->Quantity,
									$maxSavingsPromoID,
									3,
									$availmentsRow->PMGID);

								// Apply the difference between the two values as entitlement
								$this->spPromoUpdateProductList(
									$availmentsRow->ProductID,
									$buyinQty - $availmentQty,
									$availmentsRow->Price,
									$maxSavingsPromoID,
									1,
									$availmentsRow->PMGID);
							}
								
						}
						
					}
					else 
					{
						$this->spPromoUpdateProductList(
							$availmentsRow->ProductID,
							$buyinQty,
							$availmentsRow->Price,
							$maxSavingsPromoID,
							1,
							$availmentsRow->PMGID);
					}
					$hasAvailments = true;
				}
				else if (!empty($availmentsRow->EffectivePrice) && $hasAvailments)
				{
					if ($availmentsRow->EntitlementType == 1)
					{
						$availmentQty = $maxSavingsAvailements * $availmentsRow->Quantity;
						
						$this->spPromoUpdateProductList(
								$availmentsRow->ProductID,
								$availmentQty,
								$availmentsRow->EffectivePrice,
								$maxSavingsPromoID,
								2,
								$availmentsRow->PMGID);
					}
					else 
					{
						if ($selectionEntitlementQty == 0) 
						{
							$selectionEntitlementQty = $maxSavingsAvailements * $availmentsRow->EntitlementQty ;	
						}
						$rs = $this->spPromoUpdateProductListSelection($maxSavingsPromoID, 2, $rowBuyin->ID, $startDate, $endDate, $selectionEntitlementQty);
						$row = $rs->fetch_object();
						$selectionEntitlementQty -= $row->NumApplied;
						 
						// $selectionEntitlementQty -= $availmentsRow->Qty;
						
						// if we already got the desired number of availments,
						// do not process any other availments (i.e. do not execute the preceding if statement)  
						if ($selectionEntitlementQty == 0) 
						{
							$selectionEntitlementQty = -1;	
						}
					}
				}
			}
		}
		return $hasAvailments;		
	}
	
	private function GetChildPromoBuyinIDs($promoBuyinID)
	{
		$rsPromoBuyin = $this->database->execute("SELECT ID,LevelType FROM promobuyin WHERE ID=" . $promoBuyinID);
		$promoBuyin = $rsPromoBuyin->fetch_object();
		if ($promoBuyin->LevelType == 0)
		{
			$hasComma = false;
			$rsPromoChildBuyin = $this->database->execute("SELECT ID FROM promobuyin WHERE ParentPromoBuyinID=" . $promoBuyinID);
			while ($promoChildBuyin = $rsPromoChildBuyin->fetch_object())
			{
				if (!$hasComma)
				{
					$ids = $this->GetChildPromoBuyinIDs($promoChildBuyin->ID);
					$hasComma = true;
				}
				else 
				{
					$ids = $ids . ", \n" . $this->GetChildPromoBuyinIDs($promoChildBuyin->ID);
				}
				
			}
			return $ids;
		}
		else 
		{
			return 	$promoBuyinID;
		}
	}
	
	private function CreateOverlayNumAvailmentsQuery($promoBuyinID,$entitlementType,$overlayType,$overlayQty,$overlayAmt, $startDate, $endDate, $isSuggestion)
	{
		if ($overlayType == 1) // multiple
		{
			$numAvailmentsQuery = $this->CreateChildNumAvailmentsQuery($promoBuyinID,$entitlementType,false,null,null,$isSuggestion);
			return "select min(NumAvailments) NumAvailments \n" .
				"from (". $numAvailmentsQuery . ") a";
		}
		else // selection
		{
//			return "select floor(sum(NumAvailments)/$overlayQty) NumAvailments \n" .
//				"from (". $numAvailmentsQuery . ") a";
			$productlistQuery = $this->CreateProductListQuery(3,$startDate ,$endDate, $isSuggestion, false, 0);
			
			if ($entitlementType==1) 
			{
				$numAvailmentsQuery2 = "select ped.ProductID, Quantity OverlayQty, sum(ifnull(p.Qty,0)) Qty,ped.Quantity,  \n" .
					"case  \n" .
					"    when b.id is not null then null  \n" .
					"    else ifnull(MinQty,ifnull(p.Qty,ped.Quantity))  \n" .
					"end MinQty,ifnull(MinQty,p.Qty) MinAmtQty,Price,MinAmt,  \n" .
					"case EffectivePrice  \n" .
					"   when 0 then ped.Quantity+ifnull(MinQty,0)  \n" .
					"   else greatest(ped.Quantity,ifnull(MinQty,0))  \n" .
					"end SumItems from ($productlistQuery) p  \n" .
					"left join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($promoBuyinID) . ")) b on b.productid=p.productid2  \n" .
					"right join (  select PromoBuyinID, ProductID,Quantity, EffectivePrice  \n" .
					"    from promoentitlement pe  \n" .
					"    inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID  \n" .
					"    where pe.PromoBuyinID=$promoBuyinID) ped on ped.ProductID=p.ProductID  \n" .
					"where ped.ProductID is not null \n" .
					"group by ped.ProductID, ped.Quantity,ifnull(MinQty,p.Qty),ifnull(MinQty,p.Qty),Price,MinAmt,SumItems  \n";
			}
			else
			{
				$numAvailmentsQuery2 = "select ped.ProductID, Quantity OverlayQty, sum(ifnull(p.Qty,0)) Qty,ped.Quantity,  \n" .
					"case  \n" .
					"    when b.id is not null then null  \n" .
					"    else ifnull(MinQty,ifnull(p.Qty,ped.Quantity))  \n" .
					"end MinQty,ifnull(MinQty,p.Qty) MinAmtQty,Price,MinAmt,  \n" .
					"case EffectivePrice  \n" .
					"   when 0 then ped.Quantity+ifnull(MinQty,0)  \n" .
					"   else greatest(ped.Quantity,ifnull(MinQty,0))  \n" .
					"end SumItems from ($productlistQuery) p  \n" .
					"left join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($promoBuyinID) . ")) b on b.productid=p.productid2  \n" .
					"right join (  select PromoBuyinID, ProductID,Quantity, EffectivePrice  \n" .
					"    from promoentitlement pe  \n" .
					"    inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID  \n" .
					"    where pe.PromoBuyinID=$promoBuyinID) ped on ped.ProductID=p.ProductID  \n" .
					"where ped.ProductID is not null \n" .
					"group by ped.ProductID, ped.Quantity,ifnull(MinQty,p.Qty),ifnull(MinQty,p.Qty),Price,MinAmt,SumItems  \n";
			}
			
			if (!empty($overlayQty))
			{
				$numAvailmentsQuery = "select min(NumAvailments) NumAvailments \n" .
					"from ( \n" .
					"select floor(sum(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty)))/max(OverlayQty)) NumAvailments \n" .
					"from ( \n" .
					"select b.ProductID, $overlayQty OverlayQty, least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,ifnull(ped.Quantity,1) Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt,  \n" .
					"case  \n" .
					"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+MinQty \n" .
					"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+MinQty \n" . 
					"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),MinQty) \n" . 
					"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),MinQty) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" .					
					"end SumItems from ($productlistQuery) p  \n" .
					"right join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($promoBuyinID) . ")) b on b.productid=p.productid2  \n" .
					"left join (  select PromoBuyinID, ProductID, Quantity, EffectivePrice,Type,pe.Qty PeQty,Quantity PedQty  \n" .
					"    from promoentitlement pe  \n" .
					"    inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID  \n" .
					"    where pe.PromoBuyinID=$promoBuyinID) ped on ped.ProductID=p.ProductID  \n" .
					"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid  \n" .
					"where b.id is not null  \n" .
					") a \n" .
					"union  \n" .
					"select  \n" .
					"case  \n" .
					"    when sum(ifnull(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty)),-99999))<0 then null  \n" .
					"    else floor(sum(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty)))/MinQty) \n" .
					"end NumAvailments \n" .
					"from ($numAvailmentsQuery2) b \n" .
					") a";
			}
			else 
			{
				$numAvailmentsQuery = "select min(NumAvailments) NumAvailments \n" .
					"from ( \n" .
					"select floor(sum(floor((Qty*Price)/SumItems)/(least(Quantity,MinAmtQty)/least(Quantity,MinAmtQty)))/max(OverlayAmt)) NumAvailments \n" .
					"from ( \n" .
					"select b.ProductID, $overlayAmt OverlayAmt, least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,ifnull(ped.Quantity,1) Quantity,MinQty, 1 MinAmtQty,Price,MinAmt,  \n" .
					"case  \n" .
					"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+MinQty \n" .
					"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+MinQty \n" . 
					"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),MinQty) \n" . 
					"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),MinQty) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" .
					"end SumItems from ($productlistQuery) p  \n" .
					"right join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($promoBuyinID) . ")) b on b.productid=p.productid2  \n" .
					"left join (  select PromoBuyinID, ProductID, Quantity, EffectivePrice,Type,pe.Qty PeQty,Quantity PedQty  \n" .
					"    from promoentitlement pe  \n" .
					"    inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID  \n" .
					"    where pe.PromoBuyinID=$promoBuyinID) ped on ped.ProductID=p.ProductID  \n" .
					"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid  \n" .
					"where b.id is not null  \n" .
					") a \n" .
					"union  \n" .
					"select  \n" .
					"case  \n" .
					"    when sum(ifnull(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty)),-99999))<0 then null  \n" .
					"    else floor(sum(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty)))/MinQty) \n" .
					"end NumAvailments \n" .
					"from ($numAvailmentsQuery2) b \n" .
					") a";
			}
			return $numAvailmentsQuery;
		}
	}
	
	private function CreateIncentiveOverlayNumAvailmentsQuery($promoBuyinID,$entitlementType,$overlayType,$overlayQty,$overlayAmt, $startDate, $endDate, $isSuggestion)
	{
		if ($overlayType == 1) // multiple
		{
			$numAvailmentsQuery = $this->CreateIncentiveChildNumAvailmentsQuery($promoBuyinID,$entitlementType,false,null,null,$isSuggestion);
			return "select min(NumAvailments) NumAvailments \n" .
				"from (". $numAvailmentsQuery . ") a";
		}
		else // selection
		{
//			return "select floor(sum(NumAvailments)/$overlayQty) NumAvailments \n" .
//				"from (". $numAvailmentsQuery . ") a";
			$productlistQuery = $this->CreateProductListQuery(3,$startDate ,$endDate, $isSuggestion, true, 0);
			
			if (!empty($overlayQty))
			{
				$numAvailmentsQuery = "select min(NumAvailments) NumAvailments \n" .
					"from ( \n" .
					"select floor(sum(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty)))/max(OverlayQty)) NumAvailments \n" .
					"from ( \n" .
					"select distinct b.ProductID, $overlayQty OverlayQty, least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,1 Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt,  \n" .
					"case  \n" .
					"   when b.Type=1 then MinQty  \n" .
					"   when b.Type=2 then ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
					"end SumItems from ($productlistQuery) p  \n" .
					"right join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($promoBuyinID) . ")) b on b.productid=p.productid2  \n" .
					"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid  \n" .
					"where b.id is not null  \n" .
					") a \n" .
					") a";
			}
			else 
			{
				$numAvailmentsQuery = "select min(NumAvailments) NumAvailments \n" .
					"from ( \n" .
					"select floor(sum(floor((Qty*Price)/SumItems)/(least(Quantity,MinAmtQty)/least(Quantity,MinAmtQty)))/max(OverlayAmt)) NumAvailments \n" .
					"from ( \n" .
					"select distinct b.ProductID, $overlayAmt OverlayAmt, least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,1 Quantity,MinQty, 1 MinAmtQty,Price,MinAmt,  \n" .
					"case  \n" .
					"   when b.Type=1 then MinQty  \n" .
					"   when b.Type=2 then ceiling(MinAmt/ifnull(Price,MinAmt)) \n" .
					"end SumItems from ($productlistQuery) p  \n" .
					"right join (select * from promobuyin where id in (" . $this->GetChildPromoBuyinIDs($promoBuyinID) . ")) b on b.productid=p.productid2  \n" .
					"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid  \n" .
					"where b.id is not null  \n" .
					") a \n" .
					") a";
			}
			return $numAvailmentsQuery;
		}
	}
	
//	private function CreateIncentiveOverlayNumAvailmentsQuery($promoid, $promoBuyinID,$entitlementType,$overlayType,$overlayQty, $isSuggestion)
//	{
//		$numAvailmentsQuery = $this->CreateIncentiveChildNumAvailmentsQuery($promoBuyinID,$entitlementType,false,null,null,$isSuggestion);
//		
//		return "select $promoid promoid, sum(Qty) Qty, Sum(Price) Price \n".
//			"from ( \n $numAvailmentsQuery \n ) a";  
//	}

	private function CreateIncentiveMultilineNumAvailmentsQuery($promoBuyinID,$entitlementType,$startDate,$endDate,$isSuggestion)
	{
		$numAvailmentsQuery = $this->CreateIncentiveChildNumAvailmentsQuery($promoBuyinID,$entitlementType,true,$startDate,$endDate,$isSuggestion);
		return "select min(NumAvailments) NumAvailments from (". $numAvailmentsQuery . ") a";
	}
	
	private function CreateMultilineNumAvailmentsQuery($promoBuyinID,$entitlementType,$startDate,$endDate,$isSuggestion)
	{
		$numAvailmentsQuery = $this->CreateChildNumAvailmentsQuery($promoBuyinID,$entitlementType,true,$startDate,$endDate,$isSuggestion);
		return "select min(NumAvailments) NumAvailments from (". $numAvailmentsQuery . ") a";
	}
	
	private function CreateChildNumAvailmentsQuery($promoBuyinID,$entitlementType,$useStartEndDateParam,$startDate,$endDate,$isSuggestion)
	{
		$hasComma = false;
		$rsPromoChildBuyin = $this->database->execute("SELECT ID,Type,StartDate,EndDate,ProductLevelID FROM promobuyin WHERE ParentPromoBuyinID=" . $promoBuyinID);
		while ($promoChildBuyin = $rsPromoChildBuyin->fetch_object())
		{
			// Create appropriate productlist query
			if ($useStartEndDateParam)
			{	
				$productlistQuery = $this->CreateProductListQuery($promoChildBuyin->ProductLevelID, $startDate, $endDate, $isSuggestion, false, 0);
			}
			else 
			{
				$productlistQuery = $this->CreateProductListQuery($promoChildBuyin->ProductLevelID, $promoChildBuyin->StartDate, $promoChildBuyin->EndDate, $isSuggestion, false, 0);
			}
			
			
			$this->debugecho( "productlistQuery = " . $productlistQuery . "<br/><br/>"); 
			
			if ($promoChildBuyin->Type==1) // Qty
			{
				// $numAvailmentsQueryHeader = "select min(floor(floor(Qty/ifnull(MinQty/Quantity,1))/ifnull(MinQty,1))) NumAvailments ";
				$numAvailmentsQueryHeader = "select min(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty))) NumAvailments ";
			}
			else // Amount
			{
				// $numAvailmentsQueryHeader = "select min(floor(Price*floor(Qty/ifnull(Quantity,1))/ifnull(MinAmt,Price))) NumAvailments ";
				$numAvailmentsQueryHeader = "select min(floor(Qty/SumItems)/(least(Quantity,MinAmtQty)/least(Quantity,MinAmtQty))) NumAvailments ";
			}
			
			$numAvailmentsQuery2 = "select ifnull(p.Qty,0) Qty,ped.Quantity, \n".
				"case \n".
				"    when b.id is not null then null \n".
				"    else ifnull(MinQty,ifnull(p.Qty,ped.Quantity)) \n".
				"end MinQty,ifnull(MinQty,p.Qty),Price,MinAmt, \n" .
				"case EffectivePrice \n" .
				"   when 0 then ped.Quantity+ifnull(MinQty,0) \n" .
				"   else greatest(ped.Quantity,ifnull(MinQty,0)) \n" .
				"end SumItems ".
				"from ( \n" . $productlistQuery . ") p \n" .
				"left join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
				"right join (" . $this->CreatePromoEntitlementQuery($promoBuyinID) . ") ped on ped.ProductID=p.ProductID \n" .
				"where ped.ProductID is not null ";
			
			if ($entitlementType==1)
			{
				if (!$hasComma)
				{
					
					$numAvailmentsQuery = $numAvailmentsQueryHeader .
						"from ( \n" . 
						"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,ifnull(ped.Quantity,1) Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
						"case \n" .
						"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+MinQty \n" .
						"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+MinQty \n" . 
						"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),MinQty) \n" . 
						"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),MinQty) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" .
						"end SumItems ".
						"from ( \n" . $productlistQuery . ") p \n" .
						"right join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
						"left join (" . $this->CreatePromoEntitlementQuery($promoBuyinID) . ") ped on ped.ProductID=p.ProductID \n" .
						"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
						"where b.id is not null \n" .
						"union \n" . $numAvailmentsQuery2 . " \n" . 
						") a";
										
					$hasComma = true;
				}
				else 
				{
					$numAvailmentsQuery = $numAvailmentsQuery . " \n" .
						"union \n" .
						$numAvailmentsQueryHeader .
						"from ( \n" . 
						"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,ifnull(ped.Quantity,1) Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
						"case \n" .
						"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+MinQty \n" .
						"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+MinQty \n" . 
						"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),MinQty) \n" . 
						"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),MinQty) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" .
						"end SumItems ".
						"from ( \n" . $productlistQuery . ") p \n" .
						"right join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
						"left join (" . $this->CreatePromoEntitlementQuery($promoBuyinID) . ") ped on ped.ProductID=p.ProductID \n" .
						"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
						"where b.id is not null \n" .
						"union \n" . $numAvailmentsQuery2 . " \n" . 
						") a";
				}				
			}
			else 
			{
				if (!$hasComma)
				{
					
					$numAvailmentsQuery = $numAvailmentsQueryHeader .
						"from ( \n" . 
						"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,ifnull(ped.Quantity,1) Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
						"case \n" .
						"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+MinQty \n" .
						"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+MinQty \n" . 
						"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),MinQty) \n" . 
						"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),MinQty) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" .
						"end SumItems ".
						"from ( \n" . $productlistQuery . ") p \n" .
						"right join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
						"left join (" . $this->CreatePromoEntitlementQuery($promoBuyinID) . ") ped on ped.ProductID=p.ProductID \n" .
						"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
						"where b.id is not null ) a\n" .
						"union all \n" . 
						"select floor(sum(Qty)/SumItems) NumAvailments \n" .
						"from (" . $numAvailmentsQuery2 . " \n" .
						") b";
										
					$hasComma = true;
				}
				else 
				{
					$numAvailmentsQuery = $numAvailmentsQuery . " \n" .
						"union all \n" .
						$numAvailmentsQueryHeader .
						"from ( \n" . 
						"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,ifnull(ped.Quantity,1) Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
						"case \n" .
						"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+MinQty \n" .
						"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+MinQty \n" . 
						"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),MinQty) \n" . 
						"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),MinQty) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" . 
						"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" .
						"end SumItems ".
						"from ( \n" . $productlistQuery . ") p \n" .
						"right join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
						"left join (" . $this->CreatePromoEntitlementQuery($promoBuyinID) . ") ped on ped.ProductID=p.ProductID \n" .
						"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
						"where b.id is not null ) a\n" .
						"union all \n" . 
						"select floor(sum(Qty)/SumItems) NumAvailments \n" .
						"from (" . $numAvailmentsQuery2 . " \n" .
						") b";
				}	
			}
		}
		return $numAvailmentsQuery;
	}
	
	private function CreateIncentiveChildNumAvailmentsQuery($promoBuyinID,$entitlementType,$useStartEndDateParam,$startDate,$endDate,$isSuggestion)
	{
		$hasComma = false;
		$rsPromoChildBuyin = $this->database->execute("SELECT ID,Type,StartDate,EndDate,ProductLevelID FROM promobuyin WHERE ParentPromoBuyinID=" . $promoBuyinID);
		while ($promoChildBuyin = $rsPromoChildBuyin->fetch_object())
		{
			// Create appropriate productlist query
			if ($useStartEndDateParam)
			{	
				$productlistQuery = $this->CreateProductListQuery($promoChildBuyin->ProductLevelID, $startDate, $endDate, $isSuggestion, true, 0);
			}
			else 
			{
				$productlistQuery = $this->CreateProductListQuery($promoChildBuyin->ProductLevelID, $promoChildBuyin->StartDate, $promoChildBuyin->EndDate, $isSuggestion, true, 0);
			}
			
			
			$this->debugecho( "productlistQuery = " . $productlistQuery . "<br/><br/>"); 
			
			if ($promoChildBuyin->Type==1) // Qty
			{
				// $numAvailmentsQueryHeader = "select min(floor(floor(Qty/ifnull(MinQty/Quantity,1))/ifnull(MinQty,1))) NumAvailments ";
				$numAvailmentsQueryHeader = "select min(floor(Qty/SumItems)/(least(Quantity,MinQty)/least(Quantity,MinQty))) NumAvailments ";
			}
			else // Amount
			{
				// $numAvailmentsQueryHeader = "select min(floor(Price*floor(Qty/ifnull(Quantity,1))/ifnull(MinAmt,Price))) NumAvailments ";
				$numAvailmentsQueryHeader = "select min(floor(Qty/SumItems)/(least(Quantity,MinAmtQty)/least(Quantity,MinAmtQty))) NumAvailments ";
			}

			
			if ($entitlementType==1)
			{
				if (!$hasComma)
				{
					
					$numAvailmentsQuery = $numAvailmentsQueryHeader .
						"from ( \n" . 
						"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,1 Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
						"case \n" .
						"   when b.Type=1 then MinQty  \n" .
						"   when b.Type=2 then ceiling(MinAmt/ifnull(Price,MinAmt)) \n" .
						"end SumItems ".
						"from ( \n" . $productlistQuery . ") p \n" .
						"right join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
						"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
						"where b.id is not null \n" .
						") a";
										
					$hasComma = true;
				}
				else 
				{
					$numAvailmentsQuery = $numAvailmentsQuery . " \n" .
						"union \n" .
						$numAvailmentsQueryHeader .
						"from ( \n" . 
						"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,1 Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
						"case \n" .
						"   when b.Type=1 then MinQty  \n" .
						"   when b.Type=2 then ceiling(MinAmt/ifnull(Price,MinAmt)) \n" .
						"end SumItems ".
						"from ( \n" . $productlistQuery . ") p \n" .
						"right join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
						"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
						"where b.id is not null \n" .
						") a";
				}				
			}
			else 
			{
				if (!$hasComma)
				{
					
					$numAvailmentsQuery = $numAvailmentsQueryHeader .
						"from ( \n" . 
						"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,1 Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
						"case \n" .
						"   when b.Type=1 then MinQty  \n" .
						"   when b.Type=2 then ceiling(MinAmt/ifnull(Price,MinAmt)) \n" .
						"end SumItems ".
						"from ( \n" . $productlistQuery . ") p \n" .
						"right join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
						"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
						"where b.id is not null ) a\n";										
					$hasComma = true;
				}
				else 
				{
					$numAvailmentsQuery = $numAvailmentsQuery . " \n" .
						"union all \n" .
						$numAvailmentsQueryHeader .
						"from ( \n" . 
						"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,1 Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
						"case \n" .
						"   when b.Type=1 then MinQty  \n" .
						"   when b.Type=2 then ceiling(MinAmt/ifnull(Price,MinAmt)) \n" .
						"end SumItems ".
						"from ( \n" . $productlistQuery . ") p \n" .
						"right join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
						"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
						"where b.id is not null ) a\n";
				}	
			}
		}
		return $numAvailmentsQuery;
	}
		/*
	private function CreateIncentiveChildNumAvailmentsQuery($promoBuyinID,$entitlementType,$useStartEndDateParam,$startDate,$endDate,$isSuggestion)
	{
		$hasComma = false;
		$rsPromoChildBuyin = $this->database->execute("SELECT ID,Type,StartDate,EndDate,ProductLevelID FROM promobuyin WHERE ParentPromoBuyinID=" . $promoBuyinID);
		while ($promoChildBuyin = $rsPromoChildBuyin->fetch_object())
		{
			// Create appropriate productlist query
			if ($useStartEndDateParam)
			{	
				$productlistQuery = $this->CreateProductListQuery($promoChildBuyin->ProductLevelID, $startDate, $endDate, $isSuggestion, false, 0);
			}
			else 
			{
				$productlistQuery = $this->CreateProductListQuery($promoChildBuyin->ProductLevelID, $promoChildBuyin->StartDate, $promoChildBuyin->EndDate, $isSuggestion, false, 0);
			}
			
			
			$this->debugecho( "productlistQuery = " . $productlistQuery . "<br/><br/>"); 
			
			$numAvailmentsQueryHeader = "select ifnull(Qty,0) Qty, Price \n";
			
			if (!$hasComma)
			{
				
				$numAvailmentsQuery = $numAvailmentsQueryHeader .
					"from ( \n" . 
					"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,ifnull(ped.Quantity,1) Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
					"case \n" .
					"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+MinQty \n" .
					"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+MinQty \n" . 
					"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),MinQty) \n" . 
					"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),MinQty) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" .
					"end SumItems ".
					"from ( \n" . $productlistQuery . ") p \n" .
					"right join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
					"left join (" . $this->CreatePromoEntitlementQuery($promoBuyinID) . ") ped on ped.ProductID=p.ProductID \n" .
					"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
					"where b.id is not null \n" . 
					") a";
									
				$hasComma = true;
			}
			else 
			{
				$numAvailmentsQuery = $numAvailmentsQuery . " \n" .
					"union all \n" .
					$numAvailmentsQueryHeader .
					"from ( \n" . 
					"select least(Ifnull(p.qty, 0),ifnull(pav.maxavailment,Ifnull(p.qty, 0))) Qty,ifnull(ped.Quantity,1) Quantity,MinQty, ceiling(MinAmt/ifnull(Price,MinAmt)) MinAmtQty,Price,MinAmt, \n" .
					"case \n" .
					"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+MinQty \n" .
					"   when b.Type=1 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+MinQty \n" . 
					"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),MinQty) \n" . 
					"   when b.Type=1 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),MinQty) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=1 then ifnull(ped.Quantity,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)=0 and ped.Type=2 then ifnull(ped.PedQty,0)+ceiling(MinAmt/ifnull(Price,MinAmt)) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=1 then greatest(ifnull(ped.Quantity,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" . 
					"   when b.Type=2 and ifnull(EffectivePrice,0)<>0 and ped.Type=2 then greatest(ifnull(ped.PedQty,0),ceiling(MinAmt/ifnull(Price,MinAmt))) \n" .
					"end SumItems ".
					"from ( \n" . $productlistQuery . ") p \n" .
					"right join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
					"left join (" . $this->CreatePromoEntitlementQuery($promoBuyinID) . ") ped on ped.ProductID=p.ProductID \n" .
					"left join promoavailment pav on pav.promoid=b.promoid and pav.gsutypeid=p.gsutypeid \n" .
					"where b.id is not null \n" . 
					") a";
			}
			
		}
		return $numAvailmentsQuery;
	}
		*/
	private function CreateOverlayAvailmentsQuery($promoBuyinID,$maxSavingsEntitlementType,$isSuggestion)
	{
		$hasComma = false;
		$rsPromoChildBuyin = $this->database->execute("SELECT ID,Type,StartDate,EndDate,ProductLevelID FROM promobuyin WHERE ParentPromoBuyinID=" . $promoBuyinID);
		while ($promoChildBuyin = $rsPromoChildBuyin->fetch_object())
		{
			// Create appropriate productlist query
			$productlistQuery = $this->CreateProductListQuery($promoChildBuyin->ProductLevelID, $promoChildBuyin->StartDate, $promoChildBuyin->EndDate, $isSuggestion, false, 0);
			
			$header = "select distinct p.ProductID,Type,EntitlementType,EntitlementQty, MinQty,ifnull(MaxQty,MinQty) MaxQty,MinAmt,ifnull(MaxAmt,MinAmt) MaxAmt,Quantity,EffectivePrice,p.Qty,Price,PMGID ";
			if ($maxSavingsEntitlementType==1)
			{
				$entitlementQuery = "select ProductID, EffectivePrice, Quantity, PMGID, Type EntitlementType, pe.Qty EntitlementQty";
				$entitlementQueryFooter= "";
			}
			else 
			{
				$entitlementQuery = "select ProductID, EffectivePrice, Quantity, PMGID, max(Type) EntitlementType, max(pe.Qty) EntitlementQty";
				$entitlementQueryFooter= "group by  ProductID, EffectivePrice, Quantity, PMGID";
			}
			if (!$hasComma)
			{
				$availmentsQuery = $header . 
					"from ( \n" . $productlistQuery . ") p \n" .
					"left join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
					"left join (" .
					$entitlementQuery . " \n" .
					"    from promoentitlement pe \n" .
					"    inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
					"    where pe.PromoBuyinID=" . $promoBuyinID . " " . $entitlementQueryFooter . " \n" .
					") ped on ped.ProductID=p.ProductID \n" .
					"where b.id is not null or ped.ProductID is not null";
									
				$hasComma = true;
			}
			else 
			{
				$availmentsQuery = $availmentsQuery . " \n" . 
					"union \n" .
					$header . 
					"from ( \n" . $productlistQuery . ") p \n" .
					"left join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
					"left join (" .
					$entitlementQuery . " \n" .
					"    from promoentitlement pe \n" .
					"    inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
					"    where pe.PromoBuyinID=" . $promoBuyinID . " " . $entitlementQueryFooter . " \n" .
					") ped on ped.ProductID=p.ProductID \n" .
					"where b.id is not null or ped.ProductID is not null";
			}
			
		}
		return "select * \n" .
			"from (". $availmentsQuery . ") a \n" .
			"order by Type desc";	
	}

	private function CreateOverlayIncentivesAvailmentsQuery($promoBuyinID)
	{
		$hasComma = false;
		$rsPromoChildBuyin = $this->database->execute("SELECT ID,Type,StartDate,EndDate,ProductLevelID FROM promobuyin WHERE ParentPromoBuyinID=" . $promoBuyinID);
		while ($promoChildBuyin = $rsPromoChildBuyin->fetch_object())
		{
			// Create appropriate productlist query
			$productlistQuery = $this->CreateProductListQuery($promoChildBuyin->ProductLevelID, $promoChildBuyin->StartDate, $promoChildBuyin->EndDate, false, true, 0);
			
			$header = "select distinct distinct p.ProductID,Type,MinQty,ifnull(MaxQty,MinQty) MaxQty,MinAmt,ifnull(MaxAmt,MinAmt) MaxAmt,p.Qty,Price ";
			
			if (!$hasComma)
			{
				$availmentsQuery = $header . 
					"from ( \n" . $productlistQuery . ") p \n" .
					"left join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
					"where b.id is not null";
				$hasComma = true;
			}
			else 
			{
				$availmentsQuery = $availmentsQuery . " \n" . 
					"union \n" .
					$header . 
					"from ( \n" . $productlistQuery . ") p \n" .
					"left join (select * from promobuyin where id in (" . $promoChildBuyin->ID . ")) b on b.productid=p.productid2 \n" .
					"where b.id is not null";
			}
			
		}
		return "select * \n" .
			"from (". $availmentsQuery . ") a \n" .
			"order by Type desc";	
	}
	
	private function GetApplicablePromos($isPromo,$customerID) 
	{
		// Given the Product List (productlist table), select all promos 
		// where products bought are inside the buyin+entitlement
		
		if ($isPromo)
		{
			$rsPromos = $this->spSelectApplicablePromos(false);
		}
		else
		{
			$rsPromos = $this->spSelectApplicableIncentives(false);
		}
		
		while ($row = $rsPromos->fetch_object())
		{			
			$promoarray[] =  $row->ID;
			$promoType[] = $row->PromoTypeID; 
			$overlayType[] = $row->OverlayType;
		}
		$rsPromos->free_result();
		
		if (!empty($promoarray))
		{
			$this->print_r($promoarray);
			
			// Check if buyin requirements of the promos we got above
			// if the productlist satisfy the buyin requirements
			foreach ($promoarray as $i => $promo)
			{
				$pass = false;
				
				// Enumarate all buyins associated with the promo
				$rsBuyins = $this->spSelectPromoBuyinByPromoID($promo);
				while ($rowBuyin = $rsBuyins->fetch_object())
				{
					//$this->debugecho( "Testing promobuyin id=" . $rowBuyin->ID . "<br/><br/>");
					
					if ($promoType[$i]==3) // overlay
					{
						if ($overlayType[$i]==2) // selection
						{
							$operator =	 "OR";
						}
						else 
						{
							$operator =	 "AND";
						}	
					}
					else 
					{
						$operator =	 "AND";
					}

					$query = "select distinct true promomatch from productlist where PHPSession='" . session_id() . "' AND (exists \n" . $this->CreateBuyinRequirementCheckQuery($rowBuyin->ID,$operator) . ")";

					// $this->debugecho( $query . "<br/>");
					
					try {
						$rs = $this->database->execute($query);
						
						$row = $rs->fetch_object();
						// $this->debugecho( "Is Empty: \n" . empty($row) . "<br/>";
						// $this->debugecho( $query . "<br/><br/>";
						
						// if the first promobuyin already
						if (!empty($row)) 
						{
							$pass = true;
							break;
						}
						$rs->close();
						
					} catch (Exception $e) {
	            $pass = true;
        	}
					
				}
				$rsBuyins->close();
				
				// remove the promo id from the array if 
				// it did not pass the buyin criteria
				if (!$pass)
				{
					unset($promoarray[$i]);
				}
			}
			unset($promo);
			$promoarray = array_values($promoarray);
			$this->print_r($promoarray);
			return $promoarray;
		}
		else return null;
	}
	
	private function InsertApplicablePromo($promobuyinID,$startDate, $endDate, $promoType)
	{
/*		if ($promoType!=3)
		{
*/		
			$query = "select ProductLevelID from promobuyin where ID=$promobuyinID";
			$rs = $this->database->execute($query);
			$row = $rs->fetch_object();
	
			switch ($row->ProductLevelID)
			{
				case 1:
				    $productlistQuery = "select p3.ID ProductID2, p.ID, p.ProductID, PHPSession \n" .
				    "from productlist p \n" .
				    "inner join product p1 on p1.id = p.ProductID \n" .
				    "inner join product p2 on p2.id = p1.ParentID \n" .
				    "inner join product p3 on p3.id = p2.ParentID \n" .
				    "where tmpAvailed=0 and PHPSession = '" . session_id() . "' \n" .
				    "and PurchaseDate between '$startDate' and '$endDate'";
				    break;
				case 2:
				    $productlistQuery = "select p2.ID ProductID2, p.ID, p.ProductID, PHPSession \n" .
				    "from productlist p \n" .
				    "inner join product p1 on p1.id = p.ProductID \n" .
				    "inner join product p2 on p2.id = p1.ParentID \n" .
				    "where tmpAvailed=0  and PHPSession = '" . session_id() . "' \n" .
				    "and PurchaseDate between '$startDate' and '$endDate' ";
				    break;
				case 3:
				    $productlistQuery = "select p.ProductID ProductID2, p.ID, p.ProductID, PHPSession \n" .
				    "from productlist p \n" .
				    "where tmpAvailed=0  and PHPSession = '" . session_id() . "' \n" .
				    "and PurchaseDate between '$startDate' and '$endDate' ";
				    break;
			}
			 
			$query = "insert into productlistpromo select p.ID, b.PromoID, ped.PromoEntitlementDetailID \n" .
				"from ( \n" . $productlistQuery . ") p \n" .
				"inner join ( \n" .
				"	select pe.PromoBuyinID,ped.ID PromoEntitlementDetailID, ped.ProductID \n" .
				"	from promoentitlement pe \n" .
				"	inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
				"	where pe.PromoBuyinID=$promobuyinID) ped on ped.ProductID=p.ProductID2 \n" .
				"inner join (select * from promobuyin where id=$promobuyinID) b on b.ID=ped.PromoBuyinID \n" .
				// "left join productlistpromo plp on plp.ProductListID=p.ID and plp.PromoID=b.PromoID and plp.PromoEntitlementDetailID = ped.PromoEntitlementDetailID \n" . 
				"left join productlistpromo plp on plp.ProductListID=p.ID and plp.PromoID=b.PromoID \n" .
				"where plp.ProductListID is null";
			
			$this->debugecho("productlistpromoquery = $query<br/><br/>");
			$this->database->execute($query);
/*		}
		else 
		{
			$rsPromoChildBuyin = $this->database->execute("SELECT ID,ProductLevelID FROM promobuyin WHERE ParentPromoBuyinID=" . $promobuyinID);
			while ($promoChildBuyin = $rsPromoChildBuyin->fetch_object())
			{
				switch ($promoChildBuyin->ProductLevelID)
				{
					case 1:
					    $productlistQuery = "select p3.ID ProductID2, p.ID, p.ProductID, PHPSession \n" .
					    "from productlist p \n" .
					    "inner join product p1 on p1.id = p.ProductID \n" .
					    "inner join product p2 on p2.id = p1.ParentID \n" .
					    "inner join product p3 on p3.id = p2.ParentID \n" .
					    "where tmpAvailed=0 and PHPSession = '" . session_id() . "' \n" .
					    "and PurchaseDate between '$startDate' and '$endDate'";
					    break;
					case 2:
					    $productlistQuery = "select p2.ID ProductID2, p.ID, p.ProductID, PHPSession \n" .
					    "from productlist p \n" .
					    "inner join product p1 on p1.id = p.ProductID \n" .
					    "inner join product p2 on p2.id = p1.ParentID \n" .
					    "where tmpAvailed=0  and PHPSession = '" . session_id() . "' \n" .
					    "and PurchaseDate between '$startDate' and '$endDate' ";
					    break;
					case 3:
					    $productlistQuery = "select p.ProductID ProductID2, p.ID, p.ProductID, PHPSession \n" .
					    "from productlist p \n" .
					    "where tmpAvailed=0  and PHPSession = '" . session_id() . "' \n" .
					    "and PurchaseDate between '$startDate' and '$endDate' ";
					    break;
				}
				
				$query = "insert into productlistpromo select p.ID, b.PromoID, ped.PromoEntitlementDetailID \n" .
				"from ( \n" . $productlistQuery . ") p \n" .
				"inner join ( \n" .
				"	select pe.PromoBuyinID,ped.ID PromoEntitlementDetailID, ped.ProductID \n" .
				"	from promoentitlement pe \n" .
				"	inner join promoentitlementdetails ped on ped.PromoEntitlementID=pe.ID \n" .
				"	where pe.PromoBuyinID=$promoChildBuyin->ID) ped on ped.ProductID=p.ProductID2 \n" .
				"inner join (select * from promobuyin where id=$promoChildBuyin->ID) b on b.ID=ped.PromoBuyinID \n" .
				"left join productlistpromo plp on plp.ProductListID=p.ID and plp.PromoID=b.PromoID and plp.PromoEntitlementDetailID = ped.PromoEntitlementDetailID \n" . 
				"where plp.ProductListID is null";
				
				$this->database->execute($query);
			}	
		}
*/		
	}
	
	private function InsertApplicableIncentives($promoID, $sessionID, $numAvailments)
	{
// 		$query = "insert into applicableincentives (PromoID, AmountBalance, QtyBalance, AmountAvailed, QtyAvailed, IsAvailed, PHPSession, MaxAvailments)" .
// 		 		 "values ($promoID, 0, null, 0, null, 0, '$sessionID',$numAvailments)";
		$query = "insert into applicableincentives (PromoID, AmountBalance, QtyBalance, AmountAvailed, QtyAvailed, IsAvailed, PHPSession, MaxAvailments) " .
		         "select $promoID,0,null,0,null,0,'$sessionID', " .
		         "case " .
 				    "when $numAvailments - count(*) < 0 then 0 " .
				    "else $numAvailments - count(*) " .
				 "end NumAvailments " .
				 "from productlist " .
				 "where PHPSession='$sessionID' " .
				 "and PromoID=$promoID";
		$this->debugecho($query."<br><br>");
		$this->database->execute($query);		
	}
	
	private function CreateBuyinRequirementCheckQuery($promoBuyinID, $operator)
	{
        $query = "select ProductLevelID,ProductID,MinQty,MinAmt,Type,LevelType from promobuyin where id=" . $promoBuyinID;
		$result_set = $this->database->execute($query);
		
		$row = $result_set->fetch_object();
		$productLevelID = $row->ProductLevelID; 	
		$productID = $row->ProductID;
        $multiplicity = $row->MinQty;
        $worthprice = $row->MinAmt;
        $type = $row->Type;
        $levelType = $row->LevelType;
        
        if ($levelType==0) 
        {
            $hasFirstItem = false;
            // get all children
            $query = "select ID from promobuyin where ParentPromoBuyinID=" . $promoBuyinID;
            $childResultSet = $this->database->execute($query);
            while ($childRow = $childResultSet->fetch_object()) 
            {
                if (!empty($childRow->ID)) {
                	if (empty($multiplicity) && empty($worthprice))
                	{
                		if (!$hasFirstItem) 
	                    {
	                        $childQuery = $this->CreateBuyinRequirementCheckQuery($childRow->ID,$operator);
	                        $hasFirstItem = true;
	                    }
	                    else 
	                    {
	                        $childQuery = $childQuery . " $operator exists" . $this->CreateBuyinRequirementCheckQuery($childRow->ID,$operator);
	                    }
                	}
                	else 
                	{
                		if (!$hasFirstItem) 
	                    {
	                        $childQuery = $this->CreateBuyinRequirementCheckQuery($childRow->ID);
	                        $hasFirstItem = true;
	                    }
	                    else 
	                    {
	                        $childQuery = $childQuery . ', ' . $this->CreateBuyinRequirementCheckQuery($childRow->ID,$operator);
	                    }
                	}
                }
            }
            if (empty($multiplicity) && empty($worthprice))
            {
            	return $childQuery;
            }
        	else 
        	{
        		
	        	$productlistQuery = "";
	        	
		        switch ($productLevelID)
		        {
		        	case 1:
		        		$productlistQuery = "select p3.ID ProductID,PHPSession,sum(Price) Price, count(p.ID) Qty" .
		        			"from productlist p" .
		        			"inner join product p1 on p1.id = p.ProductID \n" .
							"inner join product p2 on p2.id = p1.ParentID \n" .
		        			"inner join product p3 on p3.id = p2.ParentID \n" .
		        			"where p3.ID in (" . $childQuery . ") \n" . 
		        			"and PHPSession='" . session_id() . "' \n" .
		        			"group by p3.ID,PHPSession";
		        		break;
		        	case 2:
		        		$productlistQuery = "select p2.ID ProductID,PHPSession,sum(Price) Price, count(p.ID) Qty" .
		        			"from productlist p" .
		        			"inner join product p1 on p1.id = p.ProductID \n" .
							"inner join product p2 on p2.id = p1.ParentID \n" .
		        			"where p2.ID in (" . $childQuery . ") \n" . 
		        			"and PHPSession='" . session_id() . "' \n" .
		        			"group by p2.ID,PHPSession";
		        		break;
		        	case 3:
		        		// max(Price) is used to eliminate duplicate rows arising 
		        		// from same productid with different prices 
		        		$productlistQuery = "select p.ProductID,PHPSession,max(Price) Price, count(p.ID) Qty \n" .
		        			"from productlist p" .
		        			"where p.ProductID in (" . $childQuery . ") \n" . 
		        			"and PHPSession='" . session_id() . "' \n" .
		        			"group by p.ProductID,PHPSession";
		        		break;
		        }
		        
        		if (!empty($multiplicity)) 
				{
					return "(select true \n" .
						"from (" . $productlistQuery . ") a \n" .
						"having sum(Qty)>" . $multiplicity . ")";
				}
				else 
				{
					return "(select true \n" .
						"from (" . $productlistQuery . ") a \n" .
						"having sum(Price*Qty)>" . $worthprice . ")";
				}
        	}
            
        }
        else 
        {
	        $productlistQuery = "";
	        	
	        switch ($productLevelID)
	        {
	        	case 1:
	        		$productlistQuery = "select p3.ID ProductID,PHPSession,Price,count(p.ID) Qty \n" .
						"from productlist p \n" .
						"inner join product p1 on p1.id = p.ProductID \n" .
						"inner join product p2 on p2.id = p1.ParentID \n" .
	        			"inner join product p3 on p3.id = p2.ParentID \n" .
						"group by p3.ID,PHPSession,Price";
	        		break;
	        	case 2:
	        		$productlistQuery = "select p2.ID ProductID,PHPSession,Price,count(p.ID) Qty \n" .
						"from productlist p \n" .
						"inner join product p1 on p1.id = p.ProductID \n" .
						"inner join product p2 on p2.id = p1.ParentID \n" .
						"group by p2.ID,PHPSession,Price";
	        		break;
	        	case 3:
	        		$productlistQuery = "select p.ProductID,PHPSession,Price, count(p.ID) Qty from productlist p group by ProductID,PHPSession,Price";
	        		break;
	        }
            // type 1 (X items of A)
            if ($type==1)
            {
                return "(select true from (" . $productlistQuery . ") a where ProductID = " . $productID . " and Qty>=" . $multiplicity . " and PHPSession='" . session_id() . "')";
            }
            // type 1 (X worth of A)
            elseif ($type==2)
            {
                return "(select true from (" . $productlistQuery . ") a where ProductID = " . $productID . " and Price*Qty>=" . $worthprice . " and PHPSession='" . session_id() . "')";
            }
            elseif ($type==3)
            {
            	return $productID;
            }
        }
	}
	
//////////////////////////////////////////////
//
// Stored Procedures
//
//////////////////////////////////////////////
	
	private function spSelectApplicablePromos($isSuggestion) 
	{
		$sp = "Call spPromoSelectApplicablePromos('" . session_id() . "', " . (int) $isSuggestion . ")";
		//$this->debugecho( $sp);
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	private function spSelectApplicableIncentives($isSuggestion) 
	{
		$sp = "Call spPromoSelectApplicableIncentives('" . session_id() . "', " . (int) $isSuggestion . ")";
		//$this->debugecho( $sp);
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	private function spSelectBuyinByPromoID($promoid) 
	{
		$sp = "Call spPromoSelectBuyinByPromoID(" . $promoid . ")";
		//$this->debugecho( $sp; exit;	
		$rs = $this->database->execute($sp);
		return $rs;	
	}	
	
	private function spSelectPromoBuyinByPromoID($promoid) 
	{
		$sp = "Call spPromoSelectPromoBuyinByPromoID(" . $promoid . ")";
		//$this->debugecho( $sp; exit;	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	private function spSelectPromoBuyinByID($promobuyinid) 
	{
		$sp = "Call spPromoSelectPromoBuyinByID(" . $promobuyinid . ")";
		//$this->debugecho( $sp; exit;	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	private function spPromoSelectPromoEntitlementByPromoBuyinID($promoBuyinID) 
	{
		$sp = "Call spPromoSelectPromoEntitlementByPromoBuyinID(" . $promoBuyinID . ")";
		//$this->debugecho( $sp; exit;	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	private function spPromoUpdateProductList($productID, $numAvailments, $effectivePrice, $promoID, $availmentType, $pmgID) 
	{
		if (empty($pmgID))
		{
			$pmgID = "NULL";
		}
		
		$sp = "Call spPromoUpdateProductList($productID,'" . session_id() . "',$numAvailments,$effectivePrice,$promoID,$availmentType,$pmgID)";
		// $this->debugecho( $sp . "<br/><br/>";	
		$rs = $this->database->execute($sp);
		return $rs;	
	}

	private function spPromoUpdateProductListIncentive($productID, $numAvailments, $incentiveID)
	{
		$sp = "Call spPromoUpdateProductListIncentive($productID,'" . session_id() . "',$numAvailments,$incentiveID)";
		// $this->debugecho( $sp . "<br/><br/>";	
		$rs = $this->database->execute($sp);
		return $rs;	
	}

	private function spPromoUpdateProductListSelection($promoID, $availmentType, $promoBuyinID, $startDate, $endDate, $numAvailments) 
	{
		$sp = "Call spPromoUpdateProductListSelection($promoID,$availmentType,$promoBuyinID,'" . session_id() . "','$startDate', '$endDate', $numAvailments)";
		// $this->debugecho( $sp . "<br/><br/>";	
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	private function spCleanupProductListPromo()
	{
		$query = "delete plp \n" . 
			"from productlist pl \n" .
			"inner join productlistpromo plp on plp.productlistid=pl.id \n" .
			"inner join promo p1 on p1.id=pl.PromoID \n" .
			"inner join promo p2 on p2.id=plp.PromoID \n" .
			"where \n" . 
			"p1.PromoTypeID < p2.PromoTypeID \n" .
			"and PHPSession = '" . session_id() . "' \n";
		$rs = $this->database->execute($query);
		return $rs;
	}
}
