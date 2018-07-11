<?php
	global $database;
	include CS_PATH.DS."ClassPromoAndPricing.php";
	$date = time();
	$today = date("m/d/Y",$date);
	$errmsg = "";
	$update = 0;
    $pcode = $_GET['pcode'];
    
    $rspromo = $database->execute("SELECT * FROM promo WHERE ID = ".$_GET['biID']." AND promotypeid = 2");
    $rs_pmg = $sp->spSelectPMG($database);  
    $rs_pmg2 = $sp->spSelectPMG($database);
    $rs_promoAvailment = $sp->spSelectPromoAvailByPromoID($database, $_GET['biID']);
	$rs_gsutype = $sp->spSelectGSUType($database);
	
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
    
    if($rspromo->num_rows){
    	while($rowpromo = $rspromo->fetch_object()){
    		$promoID = $rowpromo->ID;
    	   	$promocode = $rowpromo->Code;
    	   	$promodesc = $rowpromo->Description;
    	   	$startdate = date('m/d/Y', strtotime($rowpromo->StartDate));
    	   	$enddate = date('m/d/Y', strtotime($rowpromo->EndDate));
    	   	$prmPplan = $rowpromo->IsPlusPlan;
			$PageNum  = $rowpromo->PageNum;
			$xPageNum = explode("-", $PageNum);
			$PageFrom = $xPageNum[0];
			$PageTo   = $xPageNum[1];
			$IsForNewRecruit = $rowpromo->IsForNewRecruit;
	   	}
    }
	
    $promodetailsquery = $database->execute("SELECT
							TRIM(p.Code) ProductCode,
							p.ID ProductID,
							pb.ID PromoBuyinID,
							pe.ID EntitlementID,
							pp.UnitPrice,
							ped.ID EntitlementDetailsID,
							(SELECT MinQty FROM promobuyin WHERE PromoID = pb.PromoID AND ProductID = p.ID) BuyinMinimumQuantity,
							p.Name ProductName,
							ppmg.ID BuyinPMGID,
							ppmg.Code BuyinPMGCode,
							epmg.ID EntitlementPMGID,
							epmg.Code EntitlementPMGCode,
							ped.EffectivePrice SpecialPrice
						FROM promobuyin pb 
						INNER JOIN promoentitlement pe ON pe.PromoBuyinID = pb.ID
						INNER JOIN promoentitlementdetails ped ON ped.PromoEntitlementID = pe.ID
						INNER JOIN product p ON p.ID = ped.ProductID
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						LEFT JOIN tpi_pmg ppmg ON ppmg.ID = pp.PMGID
						LEFT JOIN tpi_pmg epmg ON epmg.ID = ped.PMGID
						WHERE pb.PromoID = $promoID");
    
    if (isset($_POST['btnDelete']))
	{
		try
		{
			$prmID = $_POST['hID'];
			//die($prmID);
			$linked = $sp->spSelectLinkedBrochureProductByPromoID($database, $_GET['biID']);
			
			if($linked->num_rows)
			{
				echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=62&errmsg=Promo cannot be deleted because it is already linked to a Brochure or it has started.';
						window.close();
					</script>";
			}
			else
			{
				$database->beginTransaction();
				
				$affected_rows = $PromoAndPricing->spDeletePromo($database, $prmID);
				
				if ($affected_rows == "true"){	
					
				$database->commitTransaction();
				echo"<script language=JavaScript>
							opener.location.href = '../../index.php?pageid=62&msg=Successfully deleted promo.';
							window.close();
						</script>";
				}else{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
		}
	}
	
	if (isset($_POST['btnSave']))
	{
		try
		{
			$prodid_buyin = "";
			$prodid_entitlement = "";
			$prmID = $_GET['biID'];
			$buyinprod = $_POST['hBuyInCnt'];	
			//$buyinprod = $_POST['hBuyInCntSaving'];	
			$entprod = $_POST['hEntitlementCnt'];
			$promocode = htmlentities(addslashes($_POST['txtCode']));				
			$promodesc = htmlentities(addslashes($_POST['txtDescription']));
			$tmpsdate = strtotime($_POST['txtStartDate']);
			$startdate = date("Y-m-d", $tmpsdate);
			$tmpedate = strtotime($_POST['txtEndDate']);
			$enddate = date("Y-m-d", $tmpedate);
			$preqttype = $_POST['cboPReqtType'];
			$type = $_POST['cboType'];
			$selno = $_POST['txtTypeQty'];
			if(isset($_POST['chkPlusPlan'])){
				$isplusplan = $_POST['chkPlusPlan'];				
			}
			else{
				$isplusplan = 0;
			}
			$IsNewRecruit=$_POST['IsNewRecruit'];
			$PageNum = $_POST['bpage'].'-'.$_POST['epage'];
			//check if promo code already exist
			$rs_code_exist = $sp->spCheckPromoIfExistsByPromoID($database, $promocode, $prmID);
			if($rs_code_exist->num_rows){
				$errmsg = "Promo code already exists.";
			}
			else{
				$database->beginTransaction();
				//update promo header
				//$sp->spUpdatePromoHeaderByID($database, $prmID, $promodesc, $startdate, $enddate, $isplusplan);
				$PromoAndPricing->UpdatePromoHeaderByID($database, $prmID, $promodesc, $startdate, $enddate, $isplusplan, $PageNum,$IsNewRecruit);
				
				$rs_gsutype_save = $sp->spSelectGSUType($database);
				if($rs_gsutype_save->num_rows){
					while($row = $rs_gsutype_save->fetch_object()){
						if(isset($_POST["txtMaxAvail{$row->ID}"])){
							if($_POST["txtMaxAvail{$row->ID}"] != ""){
								$q = $database->execute("select * from promoavailment where PromoID = ".$prmID." and  GSUTypeID =".$row->ID);
								if($q->num_rows != 0){
									//die('update promoavailment set MaxAvailment = '.$_POST["txtMaxAvail{$row->ID}"].' where PromoID ='.$prmID." and GSUTypeID = ".$row->ID);
									$database->execute('update promoavailment set MaxAvailment = '.$_POST["txtMaxAvail{$row->ID}"].' where PromoID ='.$prmID." and GSUTypeID = ".$row->ID);
								}else{
									$rs_promoavail = $sp->spInsertPromoAvailment($database, $prmID, $row->ID, $_POST["txtMaxAvail{$row->ID}"]);
									if (!$rs_promoavail){
										throw new Exception("An error occurred, please contact your system administrator.");
									}
								}
							}
							
						}
					}
					$rs_gsutype_save->close();
				}
				
				/* Buy In Details */
			   /* $i = 1;
			     for ($itemcount = 1; $itemcount <= $buyinprod; $itemcount++){
			    	
			    		if (isset($_POST["hProdID{$i}"])){
				    		$prodid = $_POST["hProdID{$i}"];
				    		if ($prodid != ""){
				    			$prodid_buyin .= $prodid.",";
				    		}
							$prodcode = $_POST["txtProdCode{$i}"];
							$criteria = $_POST["cboCriteria{$i}"];
							$minimum = $_POST["txtQty{$i}"];
							$BUYIN = $_POST["BUYIN{$i}"];
			    		}	
							$i++;
						//update promobuyin - child
						if ($prodcode != ""){
							if ($criteria == 1){
								$sp->spUpdatePromoBuyInByID($database, $BUYIN, $prmID, $criteria, $minimum, 'null', 'null', 'null', 3, $prodid, 'null', $startdate, $enddate, 1);
							}else{
								$sp->spUpdatePromoBuyInByID($database, $BUYIN, $prmID, $criteria, 'null', $minimum, 'null', 'null', 3, $prodid, 'null', $startdate, $enddate, 1);
							}
						}
					}    	*/
			    
				
			    /* Entitlement Details */
			    /*$j = 1;
			    for ($itemcountEnt = 1; $itemcountEnt <= $entprod; $itemcountEnt++){
			    		if (isset($_POST["hEProdID{$j}"])){
				    		$prodid = $_POST["hEProdID{$j}"];
				    		if ($prodid != ""){
				    			$prodid_entitlement .= $prodid.",";
				    		}
							$produp 	= $_POST["hEUnitPrice{$j}"];
							$prodcode 	= $_POST["txtEProdCode{$j}"];
							$criteria 	= $_POST["cboECriteria{$j}"];
							$minimum 	= $_POST["txtEQty{$j}"];
							$pmg 		= $_POST["cboEPMG{$j}"];
							$ENTID 		= $_POST["ENTID{$j}"];
			    		}
						$j++;
					if ($prodcode != ""){
							//update promo entitlement details
							if($criteria == 1){
								//$sp->spUpdatePromoEntitlementDetailsByID($database, $ENTID, $parentEntitlementID, $prodid, $minimum, 0, $produp, $pmg);
								$database->execute("UPDATE `promoentitlementdetails`
													SET ProductID = ".$prodid.", Quantity = ".$minimum.", 
													EffectivePrice = 0, Savings = ".$produp.", PMGID = ".$pmg.",
													Changed = 1 WHERE ID = ".$ENTID."");
							}else{
								$savings = $produp - $minimum;
								//$sp->spUpdatePromoEntitlementDetailsByID($database, $ENTID, $parentEntitlementID, $prodid, 1, $minimum, $savings, $pmg);
								$database->execute("UPDATE `promoentitlementdetails` SET ProductID = ".$prodid.", Quantity = 1,
													EffectivePrice = ".$minimum.", Savings = ".$savings.", PMGID = ".$pmg.",
													Changed = 1 WHERE ID = ".$ENTID."");
							}
					}
			    }*/
				
				$Entitlement = $database->execute("SELECT 
												pe.ID EntitlementID,
												pb.ID ParentPromoBuyinID                                                                                                                                                     
												FROM promobuyin pb INNER JOIN promoentitlement pe ON pe.PromoBuyinID = pb.ID
												WHERE pb.PromoID = $prmID")->fetch_object();
				
				for($x = 1; $x <= $buyinprod; $x++){
					
					if(isset($_POST['ProductID'.$x])){
						
						$ProductIDList[] = $_POST['ProductID'.$x];
						$SpecialPrice = $_POST['SpecialPrice'.$x];
						$ProductID = $_POST['ProductID'.$x];
						$PromoBuyinID = $_POST['PromoBuyinID'.$x];
						$PromoEntitlementID = $_POST['PromoEntitlementID'.$x];
						$UnitPrice = $_POST['UnitPrice'.$x];
						$EntitlementPMGID = $_POST['EntitlementPMGID'.$x];
						$EntitlementDetailsID = $_POST['EntitlementDetailsID'.$x];
						$BuyinMinimumQuantity = $_POST['BuyinMinimumQuantity'.$x];
						$Savings = $UnitPrice - $SpecialPrice;
						
						$query = $database->execute("SELECT ID FROM promobuyin 
									WHERE PromoID = $prmID
									AND ProductID = $ProductID");						
						
						if($query->num_rows){
							
							$sp->spUpdatePromoBuyInByID($database, $PromoBuyinID, $prmID, 1, $BuyinMinimumQuantity, 'null', 'null', 'null', 3, $ProductID, 'null', $startdate, $enddate, 1);
							$PromoAndPricing->spUpdatePromoEntitlementDetails($database, $PromoEntitlementID, $ProductID, $SpecialPrice, $Savings, $EntitlementPMGID, $EntitlementDetailsID, $Savings, 2, $UnitPrice);
							
							$database->execute("UPDATE promobuyin SET 
											ParentPromoBuyinID = NULL,
											ProductID = NULL,
											LevelType = 0,
											PurchaseRequirementType = 1
											WHERE ID = ".$Entitlement->ParentPromoBuyinID."");
							
						}else{
							
							$database->execute("INSERT INTO promobuyin SET
												PromoID = $prmID,
												Type = 1,
												MinQty = 1,
												ProductLevelID = 3,
												ProductID = $ProductID,
												StartDate = '$startdate',
												ParentPromoBuyinID = ".$Entitlement->ParentPromoBuyinID.",
												EndDate = '$enddate',
												LevelType = 1,
												`Changed` = 1");
							
							$database->execute("INSERT INTO promoentitlementdetails SET
												PromoEntitlementID = ".$Entitlement->EntitlementID.",
												ProductID = $ProductID,
												Quantity = 1,
												EffectivePrice = ".round($SpecialPrice, 2).",
												Savings = $Savings,
												PMGID = $EntitlementPMGID,
												`Changed` = 1");
							
						}
					}
					
				}
				
				if(!empty($ProductIDList)){
				
					$ProductIDList = implode(',', $ProductIDList);
					
					$database->execute("SET FOREIGN_KEY_CHECKS = 0");
					
					$database->execute("DELETE FROM promobuyin WHERE ProductID NOT IN ($ProductIDList) AND PromoID = $prmID AND ID NOT IN (".$Entitlement->ParentPromoBuyinID.")");
					$database->execute("DELETE FROM promoentitlementdetails WHERE PromoEntitlementID = ".$Entitlement->EntitlementID." AND ProductID NOT IN ($ProductIDList)");
					
					$database->execute("SET FOREIGN_KEY_CHECKS = 1");
					
				}
				
			    $database->commitTransaction();
			    echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=62&msg=Successfully Updated promo.';
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