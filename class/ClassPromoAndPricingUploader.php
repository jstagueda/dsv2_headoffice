<?php
class PromoAndPricingUploader
{
	function spInsertTmpSingleLinePromo($database, $promocode, $promodesc, $promosdate, $promoedate, $buyintype, $buyinminqty, 
												   $buyinminamt, $buyinplevel, $buyinpcode, $buyinpdesc,$buyinreqtype, $buyinleveltype, 
												   $enttype, $entqty, $entprodcode, $entproddesc, $entdetqty, $entdetprice, $entdetsavings,  
												   $entdetpmg, $sl_isplusplan, $pageNum) 
	{
					
		
	$result = $database->execute("INSERT INTO `tpi_tmppromosingleline` (
									 PromoCode, PromoDescription, PromoStartDate, PromoEndDate, BuyinTypeID, BuyinMinQty, BuyinMinAmt, BuyinProductLevelID,
									 BuyinProductCode, BuyinProductDescription, BuyinPurchaseReqTypeID, BuyinLevelTypeID, EntitlementTypeID, EntitlementQty,
									 EntitlementProductCode, EntitlementProductDescription, EntitlementDetQty, EntitlementDetEffPrice, EntitlementDetSavings,
									 EntitlementDetPMG, IsPlusPlan, PageNum)
								  VALUES ('$promocode', '$promodesc', '$promosdate', '$promoedate', $buyintype, $buyinminqty, $buyinminamt, $buyinplevel, '$buyinpcode', '$buyinpdesc',
									 $buyinreqtype, $buyinleveltype, $enttype, $entqty, '$entprodcode', '$entproddesc', $entdetqty, $entdetprice, $entdetsavings, $entdetpmg, 
									 $sl_isplusplan,'".$pageNum."')");
		return $result;
	}
	
	function spInsertTmpMultiLineBuyinPromo($database, $mb_promocode, $mb_promodesc, $mb_promosdate, $mb_promoedate, $mb_buyintype, $mb_buyinminqty, $mb_buyinminamt, 
													   $mb_buyinplevel, $mb_buyinpcode, $mb_buyinpdesc, $mb_buyinreqtype, $mb_buyinleveltype, $ml_isplusplan, $ml_pagenum)
	{
		$result = $database->execute("INSERT INTO `tpi_tmppromomultiline_buyin` (
									PromoCode,	PromoDescription,PromoStartDate,PromoEndDate,BuyinTypeID,BuyinMinQty,BuyinMinAmt,BuyinProductLevelID,
								    BuyinProductCode,BuyinProductDescription,BuyinPurchaseReqTypeID,BuyinLevelTypeID,IsPlusPlan, PageNum)
							VALUES (
							        '$mb_promocode','$mb_promodesc','$mb_promosdate','$mb_promoedate',$mb_buyintype,$mb_buyinminqty,$mb_buyinminamt,
									$mb_buyinplevel,'$mb_buyinpcode','$mb_buyinpdesc',$mb_buyinreqtype,$mb_buyinleveltype,$ml_isplusplan,'$ml_pagenum')");
		return $result;
	}
	function spInsertTmpMultiLineEntitlementPromo($database, $mb_promocode, $mb_promodesc, $mb_promosdate, $mb_promoedate, $mb_enttype, $mb_entqty, $mb_entprodcode, 
															  $mb_entproddesc, $mb_entdetqty, $mb_entdetprice, $mb_entdetsavings, $mb_entdetpmg)
	{
	$result = $database->execute("INSERT INTO `tpi_tmppromomultiline_entitlement` (
										PromoCode,PromoDescription,PromoStartDate,PromoEndDate,EntitlementTypeID,EntitlementQty,EntitlementProductCode,EntitlementProductDescription,
										EntitlementDetQty,EntitlementDetEffPrice,EntitlementDetSavings,EntitlementDetPMG)
									VALUES (
										'$mb_promocode','$mb_promodesc','$mb_promosdate','$mb_promoedate',$mb_enttype,$mb_entqty,'$mb_entprodcode','$mb_entproddesc',
										$mb_entdetqty,$mb_entdetprice,$mb_entdetsavings,$mb_entdetpmg)");
		return $result;
	}
	
	function spInsertTmpOverlayBuyinPromo( $database, $ob_promocode, $ob_promodesc, $ob_promosdate, $ob_promoedate, $ob_nongsu, $ob_dirgsu, $ob_idirgsu, $ob_buyinreqtype, 
													  $ob_buyintype, $ob_buyinplevel, $ob_buyinpcode, $ob_buyinpdesc, $ob_buyincriteria, $ob_buyinminval, $ob_buyinsdate, 
													  $ob_buyinedate, $ob_buyinleveltype, $ob_isincentive, $ol_isplusplan, $ol_PageNum)
	{
	$result = $database->execute("INSERT INTO `tpi_tmppromooverlay_buyin` (
            PromoCode,PromoDescription,PromoStartDate,PromoEndDate,MaxAvailNonGSU,MaxAvailDirectGSU,MaxAvailIndirectGSU,BuyinPurchaseReqTypeID,BuyinTypeID,BuyinProductLevelID,
            BuyinProductCode,BuyinProductDescription,Criteria,MinimumValue,BuyinStartDate,BuyinEndDate,BuyinLevelTypeID,IsIncentive,IsPlusPlan, PageNum)
		 VALUES (
            '$ob_promocode', '$ob_promodesc', '$ob_promosdate', '$ob_promoedate', $ob_nongsu, $ob_dirgsu, $ob_idirgsu, 
			 $ob_buyinreqtype, $ob_buyintype, $ob_buyinplevel, '$ob_buyinpcode', '$ob_buyinpdesc', $ob_buyincriteria, $ob_buyinminval, '$ob_buyinsdate', '$ob_buyinedate', 
			 $ob_buyinleveltype, $ob_isincentive, $ol_isplusplan,'$ol_PageNum')");
		return $result;
	}
	
	function spInsertTmpOverlayEntitlementPromo($database, $oe_promocode, $oe_promodesc, $oe_promosdate, $oe_promoedate, $oe_enttype, $oe_entqty, $oe_entprodcode, 
														   $oe_entproddesc, $oe_entdetqty, $oe_entdetprice, $oe_entdetsavings, $oe_entdetpmg)
    {
		
	 $result = $database->execute("INSERT INTO `tpi_tmppromooverlay_entitlement` (
									PromoCode,PromoDescription,PromoStartDate,PromoEndDate,EntitlementTypeID,EntitlementQty,
									EntitlementProductCode,EntitlementProductDescription,EntitlementDetQty,EntitlementDetEffPrice,
									EntitlementDetSavings,EntitlementDetPMG)
								  VALUES (
								   '$oe_promocode', '$oe_promodesc', '$oe_promosdate', '$oe_promoedate', $oe_enttype, $oe_entqty, '$oe_entprodcode', '$oe_entproddesc', 
								    $oe_entdetqty, $oe_entdetprice, $oe_entdetsavings, $oe_entdetpmg)");
		return $result;
    }
	
	function spInsertPromoHeader($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, $PromoTypeID, $session, $promo_isplusplan, $promo_pagenum)
	{
	 $database->execute("INSERT INTO `promo` (CODE, Description, StartDate, EndDate, PromoTypeID, StatusID, CreatedBy, EnrollmentDate, Changed, IsPlusPlan, PageNum)
								   VALUES ('$promo_code', '$promo_desc', '$promo_sdate', '$promo_edate', $PromoTypeID, 6, $session, NOW(), 1, $promo_isplusplan,'$promo_pagenum')");
	 $result = $database->execute("SELECT LAST_INSERT_ID() ID");
     return $result;
	}

	
	function spUpdatePromoHeaderByID($database, $promoID, $promo_desc, $promo_sdate, $promo_edate, $promo_isplusplan, $promo_pagenum)
	{
		//echo "SELECT ID cnt FROM `brochureproduct` WHERE PromoID = $promoID";
		 $rows = $database->execute("SELECT ID cnt FROM `brochureproduct` WHERE PromoID = $promoID");
			if ($rows->num_rows == 0){ 
					$statid = 6;
			}
			else{
				$statid = 7;
			}
			if ($promo_isplusplan != 2) 
				$result = $database->execute("UPDATE `promo` SET Description = '$promo_desc', StartDate = '$promo_sdate', EndDate = '$promo_edate', StatusID = $statid, LastModifiedDate = NOW(),
											  Changed = 1, IsPlusPlan = $promo_isplusplan, PageNum = '$promo_pagenum' WHERE ID = $promoID");
			else{
				$result = $database->execute("UPDATE `promo` SET Description = '$promo_desc', StartDate = '$promo_sdate', EndDate = '$promo_edate', StatusID = $statid, LastModifiedDate = NOW(),
											  Changed = 1, PageNum = '$promo_pagenum' WHERE ID = $promoID");
			}
		return $result;
	}
	
	function spInsertPromoHeaderOverlay($database, $promo_code, $promo_desc, $promo_sdate, $promo_edate, $PromoTypeID, $buyin_type, $min_qty, $min_amt, $session, 
													$isincentive, $promo_isplusplan, $promo_pagenum)
	{
	
		$database->execute("INSERT INTO `promo` (CODE, Description, StartDate, EndDate, PromoTypeID, OverlayType, OverlayQty, OverlayAmt, StatusID, IsIncentive, CreatedBy, EnrollmentDate, IsPlusPlan, PageNum)
		 VALUES ('$promo_code', '$promo_desc', '$promo_sdate', '$promo_edate', $PromoTypeID, $buyin_type, $min_qty, $min_amt, 6, $isincentive, $session, NOW(), $promo_isplusplan, '$promo_pagenum')");
		$result = $database->execute("SELECT LAST_INSERT_ID() ID");
		return $result;
	}
	
	function spSelectTmp_PromoSingleLine($database)
	{
		$result = $database->execute("SELECT *, CONCAT(REPEAT('0', (7-LENGTH(BuyinProductCode))), BuyinProductCode) BuyinProductCode, CONCAT(REPEAT('0', (7-LENGTH(EntitlementProductCode))), EntitlementProductCode) EntitlementProductCode FROM tpi_tmppromosingleline");
		return $result;
	}
	
	//Special Promo..
	function CreateSpecialPromoTmpTable($database)
	{
		$x = array(	"DROP TABLE IF EXISTS `tmpspecialpromo`",
					"CREATE TABLE `tmpspecialpromo`( 
					`PromoCode` VARCHAR(500) NOT NULL , 
					`Description` VARCHAR(500) NOT NULL , 
					`PromoType` VARCHAR(255) NOT NULL , 
					`StartDate` DATETIME NOT NULL , 
					`EndDate` DATETIME NOT NULL , 
					`BrochurePage` VARCHAR(255) , 
					`NonGSU` INT(11) , 
					`DirectGSU` INT(11) , 
					`InDirectGSU` INT(11) , 
					`IsPlusPlan` TINYINT,
					`ProductCode` BIGINT(255) NOT NULL , 
					`ProductName` BIGINT(255) NOT NULL , 
					`BuyinCriteria` VARCHAR(500) NOT NULL , 
					`BuyinMinimumQty` INT(11) NOT NULL , 
					`BuyinMinimumAmnt` DECIMAL(11,2) NOT NULL , 
					`EntitlementAmnt` DECIMAL(11,2) NOT NULL 
					) ENGINE=INNODB"
			);
		foreach ($x as $y):
			$database->execute($y);
		endforeach;
	}
	
	function InsertDataToTmpTablle($database,$PromoCode,$Description,$PromoType,$StartDate,$EndDate,$BrochurePage,$NonGSU,$DirectGSU,
									$InDirectGSU,$IsPlusPlan,$ProductCode,$ProductName,$BuyinCriteria,$BuyinMinimumQty,$BuyinMinimumAmnt,
									$EntitlementAmnt)
	{
		$database->execute("INSERT INTO tmpspecialpromo (PromoCode,Description,PromoType,StartDate,EndDate,BrochurePage,
							NonGSU,DirectGSU,InDirectGSU,IsPlusPlan,ProductCode,ProductName,BuyinCriteria,BuyinMinimumQty,
							BuyinMinimumAmnt,EntitlementAmnt)
							VALUES ('".$PromoCode."','".$Description."','".$PromoType."','".$StartDate."','".$EndDate."','".$BrochurePage."',
							".$NonGSU.",".$DirectGSU.",".$InDirectGSU.",".$IsPlusPlan.",'".$ProductCode."','".$ProductName."',
							'".$BuyinCriteria."',".$BuyinMinimumQty.",".$BuyinMinimumAmnt.",".$EntitlementAmnt.")");
	}
	
	function SpecialPromoSelectHeader($database)
	{
			$result = $database->execute("SELECT * FROM (
				SELECT TRIM(PromoCode) PromoCode, TRIM(Description) Description, TRIM(PromoType) PromoType, TRIM(StartDate) StartDate, TRIM(EndDate) EndDate, 
				TRIM(BrochurePage) BrochurePage, TRIM(NonGSU) NonGSU, TRIM(DirectGSU) DirectGSU, TRIM(InDirectGSU) InDirectGSU, TRIM(IsPlusPlan) IsPlusPlan
				FROM tmpspecialpromo
			) a GROUP BY PromoCode");
			
			return $result;
			
	}
	
	function InsertSpecialPromoHeader($database,$PromoCode,$Description,$PromoType,$StartDate,$EndDate,$BrochurePage,$NonGSU,$DirectGSU,		
										$InDirectGSU,$IsPlusPlan)
	{
		
		if($PromoType == "Coupon"){
			$xPromoType = 1;
		}else{ // New Dealer Incentives..
			$xPromoType = 2;
		}

		//Checker here..
		$ifPromoExist = $database->execute("select ID from specialpromo where `Code` = '".$PromoCode."'");
		if($ifPromoExist->num_rows==0){
			$database->execute("INSERT INTO specialpromo (`Code`,`Description`,`PromoType`,`StartDate`,`EndDate`,
								`BrochurePage`,`NonGSU`,`DirectGSU`,`InDirectGSU`,`IsPlusPlan`,`Changed`)
								VALUES
								('".$PromoCode."','".$Description."',".$xPromoType.",'".$StartDate."',
								'".$EndDate."','".$BrochurePage."',".$NonGSU.",".$DirectGSU.",".$InDirectGSU.",".$IsPlusPlan.",1)");
			$q = $database->execute("SELECT LAST_INSERT_ID() ID");
			$r = $q->fetch_object()->ID;
		}else{
			//Update Here..
			$r = $ifPromoExist->fetch_object()->ID;
			$database->execute("UPDATE specialpromo SET `Description` = '".$Description."' ,`PromoType` = ".$xPromoType.",
								`StartDate` = DATE('".$StartDate."') ,`EndDate` = '".$StartDate."',
								`BrochurePage` = '".$BrochurePage."',
								`NonGSU` = ".$NonGSU.",`DirectGSU` = ".$DirectGSU." ,
								`InDirectGSU` = ".$InDirectGSU." , `IsPlusPlan`=".$IsPlusPlan.", `Changed` = 1 Where ID =".$r);
		}
		return $r;
		
		
	}
	
	function GetAllSpecialPromoBuyinAndEntitlement($database,$PromoCode)
	{

		$r = $database->execute("SELECT b.ID ProductID ,a.BuyinCriteria, a.BuyinMinimumQty, a.BuyinMinimumAmnt, a.EntitlementAmnt 
							FROM tmpspecialpromo a 
							INNER JOIN product b ON a.`ProductCode` = b.`Code`
							WHERE a.PromoCode = '".$PromoCode."'");
		return $r;
	}
	
	function InsertPromoBuyinAndEntitlement($database,$InsertedID, $ProductID,$BuyinCriteria,$BuyinMinimumQty,$BuyinMinimumAmnt,$EntitlementAmnt)
	{
		if($BuyinCriteria=="Quantity"){
			$BuyinCriteria = 1;
		}else{
			$BuyinCriteria = 2;
		}
		$q = $database->execute("SELECT * FROM specialpromobuyinandentitlement WHERE SpecialPromoID = ".$InsertedID." AND ProductID = ".$ProductID);
		if($q->num_rows == 0){
			$database->execute("INSERT INTO specialpromobuyinandentitlement 
							(SpecialPromoID, ProductID, BuyinCriteria, BuyinMinimumQty, BuyinMinimumAmnt, 
							EntitlementCriteria, EntitlementQty, EntitlementAmnt, `Changed`)
							VALUES
							(".$InsertedID.", ".$ProductID.", ".$BuyinCriteria.", ".$BuyinMinimumQty.", 
							".$BuyinMinimumAmnt.", 2, 0,".$EntitlementAmnt.", 1)");
		}else{
				$database->execute("UPDATE specialpromobuyinandentitlement set BuyinCriteria = ".$BuyinCriteria.", BuyinMinimumQty = ".$BuyinMinimumQty.",
				BuyinMinimumAmnt = ".$BuyinMinimumAmnt.", EntitlementCriteria = 2, EntitlementQty = 0, EntitlementAmnt = ".$EntitlementAmnt.", Changed = 1
				WHERE SpecialPromoID = ".$InsertedID." and ProductID = ".$ProductID);
		}
	}
	
	function TotalRowsInFile($database)
	{
		$q = $database->execute("SELECT COUNT(*) xcount from tmpspecialpromo");
		$r = $q->fetch_object()->xcount;
		return $r;
	}
	
	function GetProductCodeAndPromoCodeDoesNotExistinProductMaster($database)
	{

		$r = $database->execute("SELECT a.PromoCode,  CONCAT(ProductCode,'-',ProductName) Product
							FROM tmpspecialpromo a 
							LEFT JOIN product b ON a.`ProductCode` = b.`Code`
							WHERE b.ID IS NULL");
		return $r;
	}
	
	function GetProductCodeAndPromoCodeDoesNotExistinProductMasterCount($database)
	{

		$r = $database->execute("SELECT COUNT(a.PromoCode) xcount  FROM tmpspecialpromo a 
							LEFT JOIN product b ON a.`ProductCode` = b.`Code`
							WHERE b.ID IS NULL");
		return $r->fetch_object()->xcount;
	}
	
	function spInsertPromoBuyIn($database, $promoid, $ppbuyinid, $type, $minqty, $minamt, $maxqty, $maxamt, $plevelid, $preqid, $start, $end, $leveltype,$PromoWithinPromo="")
	{
			if($PromoWithinPromo != ""){
					//getting here the promo id..
					$q = $database->execute("select * from promo where Code = '".$PromoWithinPromo."'");
					if($q->num_rows){
						while($r=$q->fetch_object()){
							$PromoID = $r->ID; //Promo ID..
							$PromoTypeID = $r->PromoTypeID;
						}
					}else{
						$PromoID = 0; // No Product ID..
						$PromoTypeID = 0;
					}
					
					/*  SLP
						MLP
						OLP
					*/
					if($PromoID <> 0){
						if($PromoTypeID == 1){ //SL
							$ProductLevelIDQ = $databae->execute("SELECT ID FROM productlevel where `Code` ='SLP'");
						}else if ($PromoTypeID == 2){ // ML
							$ProductLevelIDQ = $databae->execute("SELECT ID FROM productlevel where `Code` ='MLP'");
						}else if ($PromoTypeID == 3){ // OL
							$ProductLevelIDQ = $databae->execute("SELECT ID FROM productlevel where `Code` ='OLP'");
						}
						
						$ProductLevelID = $ProductLevelIDQ->fetch_object()->ID;
						$database->execute("INSERT INTO `promobuyin` (PromoID, ParentPromoBuyinID, Type, MinQty, MinAmt, MaxQty, MaxAmt, ProductLevelID, PurchaseRequirementType, StartDate, EndDate, LevelType, `Changed`)
											VALUES ($promoid, $ppbuyinid, $type, $minqty, $minamt, $maxqty, $maxamt, $ProductLevelID, $preqid, $start, $end, $leveltype, 1);");
						return	$database->execute("SELECT LAST_INSERT_ID() ID");
					}else{
						return $database->execute("SELECT 0 ID");
					}
			}else{
				$database->execute("INSERT INTO `promobuyin` (PromoID, ParentPromoBuyinID, Type, MinQty, MinAmt, MaxQty, MaxAmt, ProductLevelID, PurchaseRequirementType, StartDate, EndDate, LevelType, `Changed`)
											VALUES ($promoid, $ppbuyinid, $type, $minqty, $minamt, $maxqty, $maxamt, $ProductLevelID, $preqid, $start, $end, $leveltype, 1);");
									return	$database->execute("SELECT LAST_INSERT_ID() ID");
			}
	
	
	}
}

$spUploader = new PromoAndPricingUploader();
?>