<?php
	ini_set('display_errors', 1);
	//require_once "../../initialize.php";
	global $database;
	include CS_PATH.DS."ClassPromoAndPricing.php";
	
	$PromoID = $_GET['prmsid'];
	
	$promodetailsquery = $database->execute("SELECT
										p.ID PromoID,
										pb.ID PromoBuyinID,
										pb.MinQty BuyinMinimumQuantity,
										ped.Quantity EntitlementQuantity,
										ped.EffectivePrice EntitlementPrice,
										ped.ID EntitlementDetailsID,
										p1.ID ProductID,
										p1.Code ProductCode,
										p1.Name ProductName,
										ped.PMGID EntitlementPMGID,
										bpp.PMGID BuyinPMGID,
										bpmg.Code BuyinPMGCode,
										pmg.Code EntitlementPMGCode,
										pp.UnitPrice,
										pe.ID EntitlementID
									FROM promo p 
									INNER JOIN promobuyin pb ON p.ID = pb.PromoID
									INNER JOIN promoentitlement pe ON pe.PromoBuyinID = pb.ID
									INNER JOIN promoentitlementdetails ped ON ped.PromoEntitlementID = pe.ID
									INNER JOIN product p1 ON p1.ID = pb.ProductID
									INNER JOIN productpricing pp ON pp.ProductID = ped.ProductID
									INNER JOIN productpricing bpp ON bpp.ProductID = pb.ProductID
									LEFT JOIN tpi_pmg bpmg ON bpmg.ID = bpp.PMGID
									LEFT JOIN tpi_pmg pmg ON pmg.ID = ped.PMGID
									WHERE p.PromoTypeID = 1 AND p.ID = $PromoID
									AND p.StatusID NOT IN (16, 18, 19)
									GROUP BY ped.ProductID
									ORDER BY pb.ID");
	
	if (isset($_GET['prmsid']))
	{
		$prmSID = $_GET['prmsid'];		
	}
	else
	{
		$prmSID = 0;
	}
	$errmsg = "";
	$update = 0;
	$date = time();
	$today = date("m/d/Y",$date);
		
	$rs_promoSingleDetails = $PromoAndPricing->spSelectSinglePromoDet($database, $PromoID);
	$database->clearStoredResults();
	$rs_promoAvailment = $sp->spSelectPromoAvailByPromoID($database, $PromoID);
	$database->clearStoredResults();
	$rs_gsutype = $sp->spSelectGSUType($database);
	
	if ($rs_promoSingleDetails->num_rows)
	{
		while ($row = $rs_promoSingleDetails->fetch_object())
		{	
			$prmid 						= $row->prmID;		
			$prmcode 					= $row->prmCode;
			$prmdesc 					= $row->prmDesc;
			$prmsdate 					= $row->prmSDate;
			$prmedate 					= $row->prmEDate;			
			$prmPplan 					= $row->prmPPlan;
			$PageNum  					= $row->PageNum;
			$xPageNum 					= explode("-", $PageNum);
			$PageFrom 					= $xPageNum[0];
			$PageTo 					= $xPageNum[1];
			$IsForNewRecruit 			= $row->IsForNewRecruit;


		}
	}
	$database->clearStoredResults();

	/*$rs_pmg = $sp->spSelectPMG($database);
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
	}*/
	
	/*if(isset($_GET['prmsid']))
	{
		$prmSID = $_GET['prmsid'];
		$rs_promoSingleDetailsView = $PromoAndPricing->spSelectSinglePromoDetById($database, $prmSID, 1);
		
		
		//$rs_promoSingleDetailsView = $database->execute($rs_promoSingleDetailsViewQRY);
		$totcntbuy = $rs_promoSingleDetailsView->num_rows;
		
		//$totcntbuy = $rs_promoSingleDetailsView->num_rows;
		//$database->clearStoredResults();
		
		$rs_promoSingleDetailsView2 = $PromoAndPricing->spSelectSinglePrmDetbyIDEnt($database, $prmSID);
																
		$totcntent = $rs_promoSingleDetailsView2->num_rows;
	}*/
	
	if (isset($_POST['btnDelete']))
	{
		try
		{
			$prmSID = $_GET['prmsid'];
			$linked = $sp->spSelectLinkedBrochureProductByPromoID($database, $prmSID);
			
			if($linked->num_rows)
			{
				echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=60&errmsg=Promo cannot be deleted because it is already linked to a Brochure or it has started.';
						window.close();
					</script>";					
			}
			else
			{
				$database->beginTransaction();
				//$affected_rows = $sp->spDeletePromo($database, $prmSID);
				//
				//if (!$affected_rows)
				//{	
				//	throw new Exception("An error occurred, please contact your system administrator.");
				//}
				
				$database->execute("delete FROM promoentitlementdetails where PromoEntitlementID in (
					SELECT ID FROM promoentitlement WHERE PromoBuyinID IN ( SELECT ID FROM promobuyin WHERE PromoID  = ".$prmSID."))");
					
				$database->execute("delete from promoentitlement where PromoBuyinID in ( SELECT ID FROM  promobuyin WHERE PromoID  = ".$prmSID." )");
				$database->execute("delete from promobuyin where PromoID  = ".$prmSID);
				$database->execute("set FOREIGN_KEY_CHECKS = 0");
				$database->execute("delete FROM `promo`  WHERE ID = ".$prmSID);	
				
				
				$database->commitTransaction();
				
				echo"<script language=JavaScript>
							opener.location.href = '../../index.php?pageid=60&msg=Successfully deleted promo.';
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
	
	if (isset($_POST['btnSave']))
	{	
		try
		{
			
			$prodid_buyin = "";
			$prodid_entitlement = "";
			$prmSID = $_GET['prmsid'];
			$buyinprod = $_POST['hBuyInCnt'];		
			$entprod = $_POST['hEntitlementCnt'];
			$promocode = htmlentities(addslashes($_POST['txtCode']));				
			$promodesc = htmlentities(addslashes($_POST['txtDescription']));
			$tmpsdate = strtotime($_POST['txtStartDate']);
			$startdate = date("Y-m-d", $tmpsdate);
			$tmpedate = strtotime($_POST['txtEndDate']);
			$enddate = date("Y-m-d", $tmpedate);
			if(isset($_POST['chkPlusPlan'])){
				$isplusplan = $_POST['chkPlusPlan'];				
			}else{
				$isplusplan = 0;
			}
			$IsNewRecruit=$_POST['IsNewRecruit'];
			$PageNum = $_POST['bpage'].'-'.$_POST['epage'];
			
			//check if promo code already exist
	    	//$rs_promoSingleDetailsView2 = $sp->spSelectSinglePromoDetById($database, $prmSID, 1);
			$rs_code_exist = $sp->spCheckPromoIfExistsByPromoID($database, $promocode, $prmSID);
			
			if($rs_code_exist->num_rows){
				$errmsg = "Promo code already exists.";
			}
			else{
				$database->beginTransaction();
				
				//update promo header

				$PromoAndPricing->UpdatePromoHeaderByID($database, $prmSID, $promodesc, $startdate, $enddate, $isplusplan, $PageNum,$IsNewRecruit);
				//PromoAvailment
				$rs_gsutype_save = $sp->spSelectGSUType($database);
				if($rs_gsutype_save->num_rows){
					while($row = $rs_gsutype_save->fetch_object()){
						if(isset($_POST["txtMaxAvail{$row->ID}"])){
							if($_POST["txtMaxAvail{$row->ID}"] != ""){
								//Modified by Gino Leabres
								if(isset($_POST["txtMaxAvail{$row->ID}"])){
									if($_POST["txtMaxAvail{$row->ID}"] != ""){
										//checking if the max availement are inserted in promoavailment table
										$q = $database->execute("select * from promoavailment where PromoID = ".$prmSID." and  GSUTypeID =".$row->ID);
										//if have result
										if($q->num_rows != 0){
											//update table
											$database->execute('update promoavailment set MaxAvailment = '.$_POST["txtMaxAvail{$row->ID}"].' where PromoID ='.$prmSID." and GSUTypeID = ".$row->ID);
										}else{
											//otherwise insert
											$rs_promoavail = $sp->spInsertPromoAvailment($database, $prmSID, $row->ID, $_POST["txtMaxAvail{$row->ID}"]);
											if (!$rs_promoavail){
												throw new Exception("An error occurred, please contact your system administrator.");
											}
										}
									}
								}
							}
						}
					}
					$rs_gsutype_save->close();
				}
				
				$ProductIDList = array();
				
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
									WHERE PromoID = $PromoID
									AND ProductID = $ProductID");						
						
						if($query->num_rows){
							
							$sp->spUpdatePromoBuyInByID($database, $PromoBuyinID, $PromoID, 1, $BuyinMinimumQuantity, 'null', 'null', 'null', 3, $ProductID, 'null', $startdate, $enddate, 1);
							$PromoAndPricing->Updatepromoentitlement($database, 2, $PromoBuyinID);							
							$PromoAndPricing->spUpdatePromoEntitlementDetails($database, $PromoEntitlementID, $ProductID, $SpecialPrice, $Savings, $EntitlementPMGID, $EntitlementDetailsID, $Savings, 2, $UnitPrice);
						
						}else{
							
							$database->execute("INSERT INTO promobuyin SET
												PromoID = $PromoID,
												Type = 1,
												MinQty = 1,
												ProductLevelID = 3,
												ProductID = $ProductID,
												StartDate = '$startdate',
												EndDate = '$enddate',
												LevelType = 1,
												`Changed` = 1");
							
							$database->execute("INSERT INTO promoentitlement SET 
												PromoBuyinID = (SELECT MAX(ID) PromoBuyinID FROM promobuyin WHERE PromoID = $PromoID),
												Type = 2,
												Qty = 1,
												`Changed` = 1");
							
							$database->execute("INSERT INTO promoentitlementdetails SET
												PromoEntitlementID = (SELECT MAX(ID) FROM promoentitlement WHERE PromoBuyinID = (SELECT MAX(ID) PromoBuyinID FROM promobuyin WHERE PromoID = $PromoID)),
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
					$database->execute("DELETE FROM promobuyin, promoentitlement, promoentitlementdetails 
									USING promobuyin 
									INNER JOIN promoentitlement ON promoentitlement.PromoBuyinID = promobuyin.ID 
									INNER JOIN promoentitlementdetails ON promoentitlementdetails.PromoEntitlementID = promoentitlement.ID 
									WHERE promobuyin.ProductID NOT IN ($ProductIDList) AND promobuyin.PromoID = $PromoID");									
					$database->execute("SET FOREIGN_KEY_CHECKS = 1");
					
				}
				//update promo details
				/*$itemcount = 0;
				$i = 1;
				$j = 1;*/
				
				/*while ($itemcount <= ($buyinprod))
				{
					
						//Promo Buyin
						if (isset($_POST["hProdID{$i}"]))
						{
							$prodid = $_POST["hProdID{$i}"]; 
							if ($prodid != "")
							{
								$prodid_buyin .= $prodid.",";
							}
							$prodcode = $_POST["txtProdCode{$i}"];
							$criteria = $_POST["cboCriteria{$i}"];
							$minimum = $_POST["txtQty{$i}"];
							$promoBuyinID = $_POST["promoBuyinID{$i}"];
						}
						else
						{
							$prodcode = "";							
						}
						$i++;	
						//Promo Entitlement
						if (isset($_POST["hEProdID{$j}"]))
						{
							$prodid1 = $_POST["hEProdID{$j}"];
							if ($prodid1 != "")
							{
								$prodid_entitlement .= $prodid1.",";
							}
							$produp1 = $_POST["hEUnitPrice{$j}"];
							$prodcode1 = $_POST["txtEProdCode{$j}"];
							$criteria1 = $_POST["cboECriteria{$j}"];
							$minimum1 = $_POST["txtEQty{$j}"];
							//$pmg1 = $_POST["hEpmgid{$j}"];
							$pmg1 = $_POST["cboEPMG{$j}"];
							$promoEntitleID = $_POST["promoEntitleID{$j}"];
							$promoEntitleIDDetails = $_POST["promoEntitleIDDetails{$j}"];
						}
						else{
							$prodcode1 = "";							
						}	
						$j++;	
					
							
							if ($criteria == 1){
								$sp->spUpdatePromoBuyInByID($database, $promoBuyinID, $prmSID, $criteria, $minimum, 'null', 'null', 'null', 3, $prodid, 'null', $startdate, $enddate, 1);

							}
							else{
								$sp->spUpdatePromoBuyInByID($database, $promoBuyinID, $prmSID, $criteria, 'null', $minimum, 'null', 'null', 3, $prodid, 'null', $startdate, $enddate, 1);
							

							}							
							//die('update promoentitlement set type ='.$criteria1.' where PromoBuyinID ='.$promoBuyinID);
					
							$PromoAndPricing->Updatepromoentitlement($database,$criteria1, $promoBuyinID);
							$savings = $produp1 - $minimum1;
							$PromoAndPricing->spUpdatePromoEntitlementDetails($database, $promoEntitleID, $prodid1, $minimum1, $savings, $pmg1, $promoEntitleIDDetails, $savings, $criteria1, $produp1);
									
						$itemcount++;						
				}*/           
				$database->commitTransaction();
				//die();
				$message = "Successfully updated record.";
				echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=60&msg=Successfully updated promo.';
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
?>