<?php
	global $database;
	
	$prmSID = 0;
	$prmBuyINID = 0;
	$entType = 0;
	$entQty = 0;
	$prmBuyStartdate = "";
	$prmBuyEnddate = "";
	$inc = $_GET['inc'];
	$ctr = 0;
	$buycnt = 0;
	$date = time();
	$today2 = date("m/d/Y",$date);
	$errmsg = "";
	$update = 0;
	$totcntbuy = 0;
	$buycnt = 0;
	
	if (isset($_GET['fromremove']))
	{
		$fromremove = 1;
	}
	else
	{
		$fromremove = 0;		
	}
	
	if(isset($_GET['prmsid']) && !isset($_POST['btnRemove']) && !isset($_GET['fromremove']) && !isset($_POST['btnSave']) && !isset($_POST['cboRange']))
	{
		unset($_SESSION["buyin"]);		
	}
	
	if(isset($_GET['prmsid']))
	{
		//get promo header
		$rspromodet = $sp->spSelectPromoByID($database, $_GET['prmsid']);
		if($rspromodet->num_rows)
		{
			while($row = $rspromodet->fetch_object())
			{
				$promoID = $row->ID;
				$promocode = $row->Code;
				$promodesc = $row->Description;
				$tmpsdate = strtotime($row->StartDate);
				$today = date("m/d/Y",$tmpsdate);
				$tmpedate = strtotime($row->EndDate);
				$end = date("m/d/Y",$tmpedate);
				$ovrlaytype = $row->OverlayType;
				$overlayQty = $row->OverlayQty;
				$overlayAmt = $row->OverlayAmt;
				$prmPplan = $row->IsPlusPlan;
			}
			$rspromodet->close();
		}
		
		//$rs_pmg = $sp->spSelectPMG($database);
	    $rs_pmg2 = $sp->spSelectPMG($database);
	   
		//for dynamic combo box
		$index = 0;
		$pmg_id = "";
		$pmg_code = "";
		if($rs_pmg2->num_rows)
		{
			while($row = $rs_pmg2->fetch_object())
			{
				$index += 1;
				if ($index == $rs_pmg2->num_rows)
				{
					$pmg_id = $pmg_id.$row->ID;
					$pmg_code = $pmg_code."'".$row->Code."'";
				}
				else
				{
					$pmg_id = $pmg_id.$row->ID.", ";				
					$pmg_code = $pmg_code."'".$row->Code."'".", ";
				}	
			}
			$rs_pmg2->close();
		}
		
		//get product level list
	    $rsprodlevel = $sp->spSelectProductLevel($database);
	    	//get product selection
		if (isset($_POST['cboRange']))
		{
			$levelid = $_POST['cboRange'];
			$inc = $_GET['inc'];
		} 
		else
		{
			$levelid = 0;		
		}
		
		$rsprodselection = $sp->spSelectProductListByLevelID($database, $levelid, "");
		
		//get promobuyin
		$rspromobuyin_count = $sp->spSelectPromoBuyInCountByPromoID($database, $_GET['prmsid']);
		if($rspromobuyin_count->num_rows)
		{
			while($row = $rspromobuyin_count->fetch_object())
			{
				$tmpcnt = $row->Cnt;
				$prtype = $row->Type;	
				$prmBuyINID = $row->ID;		
			}
			$rspromobuyin_count->close();
		}
		
		$rspromentitlementType = $sp->spSelectPromoEntitlementByPromoBuyInID($database, $prmBuyINID);
		if($rspromentitlementType->num_rows)
		{
			while($row = $rspromentitlementType->fetch_object())
			{
				$entType = $row->Type;
				$entQty = $row->Qty;	
					
			}
			$rspromentitlementType->close();
		}
		
		$rspromobuyin = $sp->spSelectPromoBuyInByPromoID($database, $_GET['prmsid']);
		$rspromobuyin_ent = $sp->spSelectPromoBuyInByPromoIDOverlay($database, $_GET['prmsid']);
		
		$totcntbuy = $rspromobuyin->num_rows;
		$buycnt = $rspromobuyin->num_rows;
		
		//get total count of ENT upon loading
		$rspromobuyin_ent2 = $sp->spSelectPromoBuyInByPromoIDOverlay($database, $_GET['prmsid']);
		if ($rspromobuyin_ent2->num_rows)
		{
		 	while($row2 = $rspromobuyin_ent2->fetch_object())
			{
		 		$ctr += 1;
			  	
			  	//get promoentitlementid
			  	$rspromentitlement2 = "rspromentitlement".$ctr;
			  	$rspromentitlement2 = $sp->spSelectPromoEntitlementByPromoBuyInID($database, $row2->PromoBuyinID);
			  	if ($rspromentitlement2->num_rows)
				{
					$index = 0;
					while($rowEnt2 = $rspromentitlement2->fetch_object())
					{
				 		$index += 1;
					 	//get promoentitlementdetails
					 	$rspromentitlement_details2 = "rspromentitlement_details".$index;
					 	$rspromentitlement_details2 = $sp->spSelectPromoEntitlementDetailsByPromoEntitlementID($database, $rowEnt2->ID);
					 	$totcntent = $rspromentitlement_details2->num_rows;
					}
				}
			}
		}
		
		$rs_promoAvailment = $sp->spSelectPromoAvailByPromoID($database, $_GET['prmsid']);
	    $rs_gsutype = $sp->spSelectGSUType($database);
	}
	
	if(isset($_SESSION['buyin']))	
	{
		$buycnt = 0;
		if (sizeof($_SESSION['buyin']))
		{
			$buyin_list2 = $_SESSION["buyin"];
			for ($i=0, $n=sizeof($buyin_list2); $i < $n; $i++ ) 
		    {
		    	$buycnt++;
		    }			    		    
		}
	}
	else
	{
		if(isset($_POST['cboRange']))
		{
		}
		else
		{
			//load saved data and add to session
			if ($rspromobuyin->num_rows)
			{
				$prmBuyStartdate = "";
				$prmBuyEnddate = "";
				$totcntbuy = $rspromobuyin->num_rows;
				$buycnt = $rspromobuyin->num_rows;
	
				while($row = $rspromobuyin->fetch_object())
		    	{
					$preqttype = $row->PurchaseRequirementType;
					$range = $row->plid;
					$prodid = $row->prodID;
					$proddesc = $row->ProdName;
					$breqid = 0;
					$pmgCode = $row->pmgCode;
						
					if($row->Criteria == 1)
					{
						$criteria = 1;
						$minimum = $row->Minimum;
					}
					else
					{
						$criteria = 2;
						$minimum = number_format($row->Minimum, 2, '.', '');
					}
					
					//$minimum = number_format($row->Minimum, 2, '.', '');
					$maximum = number_format($row->Maximum, 2, '.', '');
									
				 	if ($row->StartDate == "00/00/0000")
					{
						$prmBuyStartdate = "&nbsp;";								 	
					}
				 	else
				 	{
						$prmBuyStartdate = $row->StartDate;
				 	}
									 
					if ($row->EndDate == "00/00/0000")
				 	{
				 		$prmBuyEnddate = "&nbsp;";								 	
			     	}
					else
				 	{
			    		$prmBuyEnddate = $row->EndDate;
				 	}
									 
				 	$_SESSION['buyin'][] = array('PReqType'=>$preqttype, 'Range'=>$range, 'ProdID'=>$prodid, 'ProdDesc'=>$proddesc, 'BReqID'=>$breqid, 'Criteria'=>$criteria, 'MinQty'=>$minimum, 'MinAmt'=>$minimum, 'StartDate'=>$prmBuyStartdate, 'EndDate'=>$prmBuyEnddate,'PMGCode'=>$pmgCode);
				}
			}
		}
	}
	
	if (isset($_POST['btnDelete']))
	{
		try
		{
			$linked = $sp->spSelectLinkedBrochureProductByPromoID($database, $_GET['prmsid']);
			
			if($_GET['inc']== 1)
			{
				//redirect to set overlay page
				$incn = 2;
			}
			else
			{
				//redirect to incentives page
				$incn = 1;
			}
			
			if($linked->num_rows)
			{
				echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=66&inc=2&errmsg=Promo cannot be deleted because it is already linked to a Brochure or it has started.';
						window.close();
					</script>";					
			}
			else
			{
				$database->beginTransaction();
				$affected_rows = $sp->spDeletePromo($database, $_GET['prmsid']);
				
				if (!$affected_rows)
				{	
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				$database->commitTransaction();
				echo"<script language=JavaScript>
							opener.location.href = '../../index.php?pageid=66&inc=2&msg=Successfully deleted promo.';
							window.close();
						</script>";		
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";	
		}
	}
				
	if (isset($_POST['btnAdd']))
	{	
		$tmpsdate = strtotime($_POST['txtStartDate']);
		$startdate = date("Y-m-d", $tmpsdate);
		$tmpedate = strtotime($_POST['txtEndDate']);
		$enddate = date("Y-m-d", $tmpedate);
		
		//purchase reqt type
		if(!isset($_POST['cboPReqtType']))
		{
			$preqttype = $prtype;			
		}
		else
		{
			$preqttype = $_POST['cboPReqtType'];
		}
		
		if(isset($_SESSION['buyin']))	
	 	{
			if (sizeof($_SESSION['buyin']))
		 	{
				$buyin_list = $_SESSION["buyin"];
			
				for ($i=0, $n=sizeof($buyin_list); $i < $n; $i++ ) 
				{
			  		$preqttype = $buyin_list[$i]['PReqType'];
				}
		 	}
		}
		
		//buy-in requirement
		$range = $_POST["cboRange"];
		$prodid = $_POST["hProdID_criteria"];
		$breqid = $_POST['rdoBReqt'];
		if ($breqid == 2)
		{
			$criteria = $_POST["cboPHCriteria"];
			$minimum = $_POST["txtPHMinimum"];
			// $br_startdate = 'null';			
			// $br_enddate = 'null';
			$br_startdate = $startdate;
			$br_enddate = $enddate;
			if ($criteria == 1)
			{
				//$min_qty = $_POST["txtPHMinimum"];
				$min_qty = 1;
				$min_amt = 'null';				
			}
			else
			{
				$min_qty = 'null';
				$min_amt = $_POST["txtPHMinimum"];
			}
		}
		else
		{
			$criteria = $_POST["cboCriteria"];
			$minimum = $_POST["txtMinimum"];
			$tmpbsdate = strtotime($_POST['txtSetStartDate']);
			$br_startdate = date("m/d/Y", $tmpbsdate);
			$tmpbedate = strtotime($_POST['txtSetEndDate']);
			$br_enddate = date("m/d/Y", $tmpbedate);
			if ($criteria == 1)
			{
				$min_qty = $_POST["txtMinimum"];
				$min_amt = 'null';				
			}
			else
			{
				$min_qty = 'null';
				$min_amt = $_POST["txtMinimum"];
			}				
		}
		
		//get product description
		$rs_prod = $sp->spSelectProductbyID($database, $prodid, 1);
		if($rs_prod->num_rows)
		{
			while($row = $rs_prod->fetch_object())
			{
				$proddesc = $row->Name;
				$pmgCode = $row->pmgCode;
			}
		}
		
		//add to session
		$_SESSION['buyin'][] = array('PReqType'=>$preqttype, 'Range'=>$range, 'ProdID'=>$prodid, 'ProdDesc'=>$proddesc, 'BReqID'=>$breqid, 'Criteria'=>$criteria, 'MinQty'=>$min_qty, 'MinAmt'=>$min_amt, 'StartDate'=>$br_startdate, 'EndDate'=>$br_enddate,'PMGCode'=>$pmgCode);
	}
	
	if (isset($_POST['btnRemove']))
	{
		if(isset($_POST["chkSelect"]))	
		{
			$tmpbuyin = $_SESSION["buyin"];
			$x = sizeof($tmpbuyin);
		    $pricing = $_POST["chkSelect"];
		    
			foreach ($pricing as $key=>$ID) 
			{
				for ($i = 0; $i < $x; $i++)
				{
					if (isset($tmpbuyin[$i]['ProdID']))
					{		
						if (($_POST['hprodIDID'.$ID] == $tmpbuyin[$i]['ProdID']))
						{									
							unset($tmpbuyin[$i]);
							break;	
						}
					}
				}
			}
			
			unset($_SESSION["buyin"]);
			for ($i = 0; $i < $x; $i++)
			{
				if (isset($tmpbuyin[$i]['ProdID']))
				{	 
					$_SESSION['buyin'][] = array('PReqType'=>$tmpbuyin[$i]['PReqType'], 'Range'=>$tmpbuyin[$i]['Range'], 'ProdID'=>$tmpbuyin[$i]['ProdID'], 'ProdDesc'=>$tmpbuyin[$i]['ProdDesc'], 'BReqID'=>$tmpbuyin[$i]['BReqID'], 'Criteria'=>$tmpbuyin[$i]['Criteria'], 'MinQty'=>$tmpbuyin[$i]['MinQty'], 'MinAmt'=>$tmpbuyin[$i]['MinAmt'], 'StartDate'=>$tmpbuyin[$i]['StartDate'], 'EndDate'=>$tmpbuyin[$i]['EndDate'],'PMGCode'=>$tmpbuyin[$i]['PMGCode']);
				}	
			}

			$buycnt = 0;
			if(isset($_SESSION['buyin']))	
			{
				if (sizeof($_SESSION['buyin']))
				{
					$buyin_list2 = $_SESSION["buyin"];
					for ($i=0, $n=sizeof($buyin_list2); $i < $n; $i++ ) 
				    {
				    	$buycnt++;
				    }			    		    
				}
				
				if($_GET['inc']== 0)
				{
					$incn = 0;
				}
				else
				{
					$incn = 1;
				}
				$prmID = $_GET['prmsid'];	
				redirect_to("./promo_setpromoDetails.php?prmsid=$prmID&inc=$incn&fromremove=1");
			}
		}		
	}	
	
	if (isset($_POST['btnSave']))
	{
		try
		{
			$prodid_buyin = "";
			$prodid_entitlement = "";
			$prmID = $_GET['prmsid'];
			$promocode = htmlentities(addslashes($_POST['txtCode']));				
			$promodesc = htmlentities(addslashes($_POST['txtDescription']));
			$tmpsdate = strtotime($_POST['txtStartDate']);
			$startdate = date("Y-m-d", $tmpsdate);
			$tmpedate = strtotime($_POST['txtEndDate']);
			$enddate = date("Y-m-d", $tmpedate);
			if(isset($_POST['chkPlusPlan']))
			{
				$isplusplan = 1;				
			}
			else
			{
				$isplusplan = 0;
			}
			if(!isset($_POST['cboPReqtType']))
			{
				$preqttype = $prtype;			
			}
			else
			{
				$preqttype = $_POST['cboPReqtType'];
			}
			
			if(isset($_SESSION['buyin']))	
		 	{
				if (sizeof($_SESSION['buyin']))
				{
					$buyin_list = $_SESSION["buyin"];
					for ($i = 0, $n = sizeof($buyin_list); $i < $n; $i++ ) 
					{
						$preqttype = $buyin_list[$i]['PReqType'];
					}		
				}
			}
			
			$entprod = $_POST['hEntitlementCnt'];
			$buyprod = $_POST['hbuyCnt'];
			$type = $_POST['cboType'];
			if ($type == 2)
			{
				$typeqty = $_POST['txtTypeQty'];
			}
			else
			{
				$typeqty = 0;
			}
			
			//buy-in requirement variables
			$range = $_POST["cboRange"];
			$prodid = $_POST["hProdID_criteria"];
			$breqid = $_POST['rdoBReqt'];
			if ($breqid == 2)
			{
				$breqid = 1;
				$criteria = $_POST["cboPHCriteria"];
				$minimum = $_POST["txtPHMinimum"];
				$br_startdate = 'null';			
				$br_enddate = 'null';
				if ($criteria == 1)
				{
					$min_qty = $_POST["txtPHMinimum"];
					$min_amt = 'null';				
				}
				else
				{
					$min_qty = 'null';
					$min_amt = $_POST["txtPHMinimum"];
				}
			}
			else
			{
				$breqid = 2;
				$min_qty = 'null';
				$min_amt = 'null';
			}
			
			//check if promo code already exist
			$rs_code_exist = $sp->spCheckPromoIfExistsByPromoID($database, $promocode, $prmID);
			if($rs_code_exist->num_rows)
			{
				$errmsg = "Promo code already exists.";
			}
			else
			{
				$database->beginTransaction();
				if($_GET['inc'] == 0)
				{
					// save IsIncentives false
					$incn = 0;
				}
				else
				{
					// save IsIncentives true
					$incn = 1;
				}
				
				//update promo header
				$sp->spUpdatePromoHeaderByID($database, $prmID, $promodesc, $startdate, $enddate, $isplusplan);
				
				//retrieve promobuyin - parent
				$rs_promobuyin_parent = $sp->spSelectParentPromoBuyIn($database, $prmID);
				if($rs_promobuyin_parent->num_rows != 0)
				{
					while($row = $rs_promobuyin_parent->fetch_object())
					{
						$buyinparentID = $row->ID;
					}
				}
				else
				{
					//insert to promobuyin - parent
					if ($breqid == 1)
					{
						$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $prmID, 'null', 'null', 'null', 'null', 'null', 'null', 3, 'null', 'null', $startdate, $enddate, 0);
					}
					else
					{
						$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $prmID, 'null', 'null', 'null', 'null', 'null', 'null', 3, 'null', 'null', 'null', 'null', 0);
					}
					
					if($rs_promobuyin_parent->num_rows)
					{
						while($row = $rs_promobuyin_parent->fetch_object())
						{
							$buyinparentID = $row->ID;
						}
					}
				}
				
				$itemcountbuy = 0;
			    $i = 1;
				/* buyin */
			    while ($itemcountbuy <= ($buyprod))
			    {
			    	//do 
			    	//{
			    		if (isset($_POST["hprodIDID{$i}"]))
			    		{
				    		$prodid = $_POST["hprodIDID{$i}"];
				    		if ($prodid != "")
				    		{
				    			$prodid_buyin .= $prodid.",";
				    		}
							$criteria = $_POST["hCrit{$i}"];
							$minimum = $_POST["hMin{$i}"];
							$sDate = $_POST["hSDate{$i}"];
							$eDate = $_POST["hEDate{$i}"];
					        $range = $_POST["hrange{$i}"];
			    		}
				        $i++;	
				    //}
				    //while (empty($prodid));
				    
				    if ($prodid != "")
				    {
				    	//get product csp
						$csp = 0;
						$prod_csp = $sp->spSelectProductDM($database, $prodid, "");
						if($prod_csp->num_rows)
						{
							while($row_csp = $prod_csp->fetch_object())
							{
								$csp = $row_csp->UnitPrice;
							}
							$prod_csp->close();
						}
						
						//check if promo buyin exists
						$promo_buyin_exist = $sp->spCheckIfExistPromoBuyIn($database, $prmID, $breqid, $prodid);
						if ($promo_buyin_exist->num_rows == 0)
						{								
							//insert to promobuyin - child
							if ($criteria == 1)
							{
								if ($breqid  == 2)
								{
									$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $prmID, $buyinparentID, $criteria, 1, 'null', 'null', 'null', $range, $prodid, $breqid, $sDate, $eDate, 1);												
								}
								else
								{
									$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $prmID, $buyinparentID, $criteria, $minimum, 'null', 'null', 'null', $range, $prodid, $breqid, $sDate, $eDate, 1);												
								}
							}
							else
							{
								if ($breqid  == 2)
								{
									$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $prmID, $buyinparentID, $criteria, 'null', $csp, 'null', 'null', $range, $prodid, $breqid, $sDate, $eDate, 1);												
								}
								else
								{
									$rs_promobuyin_child = $sp->spInsertPromoBuyIn($database, $prmID, $buyinparentID, $criteria, 'null', $minimum, 'null', 'null', $range, $prodid, $breqid, $sDate, $eDate, 1);												
								}
							}
						}
						else
						{
							if($promo_buyin_exist->num_rows)
							{
								while($row = $promo_buyin_exist->fetch_object())
								{
									$buyinchildID = $row->ID;
								}
								$promo_buyin_exist->close();
							}
							
							//update promobuyin - child
							if ($criteria == 1)
							{
								if ($breqid  == 2)
								{
									$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $prmID, $criteria, 1, 'null', 'null', 'null', $range, $prodid, $breqid, $sDate, $eDate, 1);												
								}
								else
								{
									$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $prmID, $criteria, $minimum, 'null', 'null', 'null', $range, $prodid, $breqid, $sDate, $eDate, 1);												
								}
							}
							else
							{
								if ($breqid  == 2)
								{
									$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $prmID, $criteria, 'null', $csp, 'null', 'null', $range, $prodid, $breqid, $sDate, $eDate, 1);												
								}
								else
								{
									$sp->spUpdatePromoBuyInByID($database, $buyinchildID, $prmID, $criteria, 'null', $minimum, 'null', 'null', $range, $prodid, $breqid, $sDate, $eDate, 1);												
								}
							}
						}					    	
				    }
				    $itemcountbuy++;
			    }
			    
			    if($type != 2)
				{
				 	$typeqty = $entprod - 1;
				}
				else
				{
					$typeqty = $_POST['txtTypeQty'];
				}
			
				//check if promo entitlement exists
				$promo_entitlement_exist = $sp->spCheckIfExistPromoEntitlement($database, $buyinparentID, $type);
				if ($promo_entitlement_exist->num_rows == 0)
				{
					//insert entitlement
					$rs_promoentid = $sp->spInsertPromoEntitlement($database, $buyinparentID, $type, $typeqty);
					if($rs_promoentid->num_rows)
					{
						while($row = $rs_promoentid->fetch_object())
						{
							$parentEntitlementID = $row->ID;
						}
						$rs_promoentid->close();
					}
				}
				else
				{
					if($promo_entitlement_exist->num_rows)
					{
						while($row = $promo_entitlement_exist->fetch_object())
						{
							$parentEntitlementID = $row->ID;
						}
						$promo_entitlement_exist->close();
					}
					
					//update promo entitlement
					$sp->spUpdatePromoEntitlementByID($database, $parentEntitlementID, $buyinparentID, $type, $typeqty);											
				}
				
				$itemcountent = 0;
			    $j =1;
			    
			    /* entitlement details */
			    while ($itemcountent <= ($entprod))
			    {
			    	//do 
			    	//{
			    		if (isset($_POST["hEProdID{$j}"]))
			    		{
				    		$prodid = $_POST["hEProdID{$j}"];
				    		if ($prodid != "")
				    		{
				    			$prodid_entitlement .= $prodid.",";
				    		}
							$produp = $_POST["hEUnitPrice{$j}"];
							$prodcode = $_POST["txtEProdCode{$j}"];
							$criteria = $_POST["cboECriteria{$j}"];
							$minimum = $_POST["txtEQty{$j}"];
							$pmg = $_POST["cboEPMG{$j}"];
			    		}
					     $j++;	
				    //}
				    //while (empty($prodcode));
				    
				    if ($prodid != "")
				    {
				    	//check if promo entitlement details exists
						$promo_entitlementdet_exist = $sp->spCheckIfExistPromoEntitlementDetails($database, $parentEntitlementID, $prodid);
						if (!$promo_entitlementdet_exist->num_rows)
						{
							//insert entitlementdetails
							if ($criteria == 1)
							{
								$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $parentEntitlementID, $prodid, $minimum, 0, $produp, $pmg);					
							}
							else
							{
								$savings = $produp - $minimum;
								$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $parentEntitlementID, $prodid, 1, $minimum, $savings, $pmg);
							}									
						}
						else
						{
							if($promo_entitlementdet_exist->num_rows)
							{
								while($row = $promo_entitlementdet_exist->fetch_object())
								{
									$entitlementdetID = $row->ID;
								}
								$promo_entitlementdet_exist->close();
							}
							
							//update promo entitlement details
							if($criteria == 1)
							{
								$sp->spUpdatePromoEntitlementDetailsByID($database, $entitlementdetID, $parentEntitlementID, $prodid, $minimum, 0, $produp, $pmg);									
							}
							else
							{
								$savings = $produp - $minimum;
								$sp->spUpdatePromoEntitlementDetailsByID($database, $entitlementdetID, $parentEntitlementID, $prodid, 1, $minimum, $savings, $pmg);
							}											
						}
				    }
				    $itemcountent++;
			    }
			    
				$prodid_entitlement = substr($prodid_entitlement, 0, -1);
				$prodid_buyin = substr($prodid_buyin, 0, -1);
				
				//save to promodeleteddetails - buyin 
				$rs_promo_buyin_deleted = $sp->spSelectDeletedPromoDetails($database, 1, $prmID, $prodid_buyin);
				if($rs_promo_buyin_deleted->num_rows != 0)
				{
					while($row = $rs_promo_buyin_deleted->fetch_object())
					{
						$sp->spInsetDeletedPromoDetails($database, 1, 1, $row->ID);
					}
					$rs_promo_buyin_deleted->close();
				}
							
				//save to promodeleteddetails - entitlement
				$rs_promo_ent_deleted = $sp->spSelectDeletedPromoDetails($database, 2, $prmID, $prodid_entitlement);
				if($rs_promo_ent_deleted->num_rows != 0)
				{
					while($row = $rs_promo_ent_deleted->fetch_object())
					{
						$sp->spInsetDeletedPromoDetails($database, 1, 2, $row->ID);
					}
					$rs_promo_ent_deleted->close();
				}
				
				//save to promodeleteddetails - entitlementdetails 
				$rs_promo_entdet_deleted = $sp->spSelectDeletedPromoDetails($database, 3, $prmID, $prodid_entitlement);
				if($rs_promo_entdet_deleted->num_rows != 0)
				{
					while($row = $rs_promo_entdet_deleted->fetch_object())
					{
						$sp->spInsetDeletedPromoDetails($database, 1, 3, $row->ID);
					}
					$rs_promo_entdet_deleted->close();
				}
				
				//remove deleted product details
				$sp->spDeletePromoDetails($database, 4, $prmID, $prodid_entitlement, 0);
				$sp->spDeletePromoDetails($database, 4, $prmID, $prodid_buyin, 1);
				
				$database->commitTransaction();	
				
				if($_GET['inc'] == 1)
				{
					//redirect to set overlay page
					$incn = 2;
				}
				else
				{
					//redirect to incentives page
					$incn = 1;
				}
				echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=66&inc=$incn&msg=Successfully Updated promo.';
						window.close();
					</script>";				
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";			
		}
	}	
?>