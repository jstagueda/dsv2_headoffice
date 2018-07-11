<?php
require_once('../initialize.php');
include CS_PATH.DS."ClassIncentives.php";
global $database;
/*For Update*/
if(isset($_GET['xUpdate'])){
	$ID = $_POST['xxxID'];
	//AJAX to View Tables
	$result1 = $database->execute("SELECT ID FROM incentivespromobuyin WHERE PromoIncentiveID = ".$ID);
	if($result1->num_rows){
	$g=0;
		while ($row = $result1->fetch_object()){
			$IDS = $row->ID;
				$result2 = $database->execute("SELECT a.Type, a.Qty, a.ID, a.PromoID, c.Code PromoCode,b.Name ProductDesc, a.ProductLevelID, a.ProductID, a.CriteriaID,  a.MinQty MinQty, REPLACE(FORMAT(MinAmt,2), ',', '') MinAmt, DATE_FORMAT(a.StartDate, '%m/%d/%y') StartDate, DATE_FORMAT(a.EndDate, '%m/%d/%y') EndDate FROM incentivespromobuyin a
												left join  product b on a.ProductID = b.ID 
												left join promo c on a.PromoID = c.ID
												WHERE a.ID = ".$IDS);
					if($result2->num_rows){
						while($row2 = $result2->fetch_object()){
							$Buyin[$g]=$row2;
						}
					}
				$result3 = $database->execute("SELECT b.Name ProductDesc, b.Code ItemCode, a.ID, a.ProductID,  a.CriteriaID, a.MinQty, REPLACE(FORMAT(MinAmt,2), ',', '') MinAmt, DATE_FORMAT(a.StartDate,'%m/%d/%Y') StartDate, DATE_FORMAT(a.EndDate,'%m/%d/%Y') EndDate FROM incentivespromoentitlement a 
											   inner join product b on a.ProductID = b.ID WHERE a.IncentivesPromoBuyinID= ".$IDS);
				if($result3->num_rows){
						while($row3 = $result3->fetch_object()){
														
							$entitnement[$g][]=$row3;
						}
				}
		$g++;
		}
		$buyin_entitlement=array($Buyin, $entitnement);		
		//echo $html1.'smile'.$html2;						
	}else{
		$buyin_entitlement = "";
	}
		//print_r($buyin_entitlement);
		die(tpi_JSONencode($buyin_entitlement));
	
	
}


if(isset($_GET['XMBI_ADD'])){

	$activate = 0;
	if(isset($_POST['activate'])){
			$activate = 1;
			
	}else{
			$activate = 0;
	}
	$BuyinSelection1 = $_POST['BuyinSelection1'];
	if($BuyinSelection1 == 1){
		$tpiSelection 	= $tpiIncentives->tpiSelectAllProduct($database, 1);
		while ($row = $tpiSelection->fetch_object()){
			$ProductDesc 	= addslashes($row->Name);
			$ProductID 		= $row->ID;
		}
	}else if($BuyinSelection1 == 2){
		$ID = $_POST['ProdLine1'];
		$tpiSelection = $tpiIncentives->tpiSelectProductLineSelected($database, $ID);
		while ($row = $tpiSelection->fetch_object()){
			$ProductDesc 	= addslashes($row->Name);
			$ProductID 		= $row->ID;
		}
	}else if ($BuyinSelection1 == 3){
		$BRItemCode = $_POST['txtBRItemCode1'];
		$tpiSelection = $tpiIncentives->tpiSelectProductCode($database, $BRItemCode);
		while ($row = $tpiSelection->fetch_object()){
			$ProductDesc 	= addslashes($row->Name);
			$ProductID 		= $row->ID;
		}
	}
	if($_POST['sType'] == 2){
		$sType = $_POST['Sselection'];
		$Sselection = 2;
	}else{
		$sType = 0;
		$Sselection = 1;
	}

	$multiEcounter		= $_POST['multixcounter'];
	
	$BRMinVal 			= $_POST['txtBRMinVal1'];
	
	/* Buyin Requirements Tmp*/
	if($BuyinSelection1 == 1 || $BuyinSelection1 == 2 || $BuyinSelection1 == 3){
		$buyincriteria 		= $_POST['buyincriteria1'];
		$BuyinSetStartDate 	= $_POST['txtBuyinSetStartDate1']; 
		$BuyinSetEndDate 	= $_POST['txtBuyinSetEndDate1'];
		
		$InsertIncentivesTmpBuyin = $tpiIncentives->tpiInsertIncentivesTmpBuyin1($database, $BuyinSelection1, $ProductID, $ProductDesc, $buyincriteria, $BRMinVal, $BuyinSetStartDate, $BuyinSetEndDate, 1, $_SESSION['emp_id'],$activate, $Sselection, $sType);
	}
	
	if($BuyinSelection1 == 4 || $BuyinSelection1 == 5){
	
		$PromoCode = $_POST['txtPromoCodePromo1'];
		$tpiSelection = $database->execute("select * from promo where Code ='".$PromoCode."'");
		if($tpiSelection->num_rows){
			while($r = $tpiSelection->fetch_object()){
				$PromoID = $r->ID;
				$PromoCode = $r->Code;
				$BuyinSetStartDate = $r->StartDate;
				$BuyinSetEndDate = $r->EndDate;
			}
		
			
			$database->execute("Insert into tpiincentivesbuyintmp (ProductLevelID, PromoID, PromoCode, CriteriaID, MinQty, MinAmt, StartDate, EndDate, Changed, sessionID, SpecialCriteria, Type, Qty)
						values ($BuyinSelection1, $PromoID, '$PromoCode', 1, $BRMinVal, 0, '$BuyinSetStartDate', '$BuyinSetEndDate', 1, ".$_SESSION['emp_id'].", $activate, $Sselection, $sType)");
		}
	}
	
	$tmp = $tpiIncentives->insert_last_id($database);
	if($tmp->num_rows){
		while($row = $tmp->fetch_object()){
			$IBuyinID = $row->insert_id;
		}
	}
	//Special Criteria Tmp
	$Swik1 = "";
	$Ewik1 = "";
	$Swik2 = "";
	$Ewik2 = "";
	$Swik3 = "";
	$Ewik3 = "";
	$Swik4 = "";
	$Ewik4 = "";
	$SminValue = 0;
	if($activate == 1){
	
		$noofwiks = 0;
		if(isset($_POST['noofwiks'])){
			$noofwiks = $_POST['noofwiks'];
		}else{
			$noofwiks = 0;
		}
		
		$noofwiksto = 0;
		if(isset($_POST['noofwiksto'])){
			$noofwiksto = $_POST['noofwiksto'];
		}else{
			$noofwiksto = 0;
		}
		
		if(isset($_POST['txtSPStartDate1']) && isset($_POST['txtSPEndDate1'])){
			$Swik1 = date("Y-m-d",strtotime($_POST['txtSPStartDate1']));
			$Ewik1 = date("Y-m-d",strtotime($_POST['txtSPEndDate1']));
		}else{
			$Swik1 = "0000-00-00";
			$Ewik1 = "0000-00-00";
		}
		
		if(isset($_POST['txtSPStartDate2']) && isset($_POST['txtSPEndDate2'])){
			$Swik2 = date("Y-m-d",strtotime($_POST['txtSPStartDate2']));
			$Ewik2 = date("Y-m-d",strtotime($_POST['txtSPEndDate2']));
		}else{
			$Swik2 = "0000-00-00";
			$Ewik2 = "0000-00-00";
		}
		
		if(isset($_POST['txtSPStartDate3']) && isset($_POST['txtSPEndDate3'])){
			$Swik3 = date("Y-m-d",strtotime($_POST['txtSPStartDate3']));
			$Ewik3 = date("Y-m-d",strtotime($_POST['txtSPEndDate3']));
		}else{
			$Swik3 = "0000-00-00";
			$Ewik3 = "0000-00-00";
		}
		
		if(isset($_POST['txtSPStartDate4']) && isset($_POST['txtSPEndDate4'])){
			$Swik4 = date("Y-m-d",strtotime($_POST['txtSPStartDate4']));
			$Ewik4 = date("Y-m-d",strtotime($_POST['txtSPEndDate4']));
		}else{
			$Swik4 = "0000-00-00";
			$Ewik4 = "0000-00-00";
		}
		if(isset($_POST['SminValue'])){
			$SminValue = $_POST['SminValue'];
		}else{
			$SminValue = 0;
		}
		$tpiIncentives->tpiInsertSpecialCriteria($database, $Swik1, $Ewik1, $Swik2, $Ewik2, $Swik3, $Ewik3, $Swik4, $Ewik4,$IBuyinID, $SminValue, $noofwiks, $noofwiksto, $_SESSION['emp_id']);
	}
	
	
	//Multi enetitlement
	for($i = 1; $i < $multiEcounter; $i++){
		if(isset($_POST["txtEPromoCode{$i}"])){
			$EProductCode 		   =	$_POST["txtEPromoCode{$i}"];
			$tpiEntitlementtmp = $tpiIncentives->tpiSelectProductCode($database, $EProductCode);
			while ($row = $tpiEntitlementtmp->fetch_object()){
				$EProductDesc 	= addslashes($row->Name);
				$EProductID 	= $row->ID;
			}
			$EntitlementCriteria 	= 	$_POST["buyinEcriteria{$i}"];
			$EMinVal 				=	$_POST["txtEMinVal{$i}"];
			/*PromoEntitlement Tmp*/
			$tpiIncentives->tpiInsertIncentivesTmpEntitlement($database, $EProductID, $EProductDesc, $EMinVal, $EntitlementCriteria, 1, $BuyinSetStartDate, $BuyinSetEndDate, $IBuyinID);
		}
	}
	
	$result1 = $database->execute("SELECT ID FROM tpiincentivesbuyintmp WHERE sessionID = $session->emp_id");
	if($result1->num_rows){
	$ctr = 0;
		while ($row = $result1->fetch_object()){
			$IDS = $row->ID;
			
				$result2 = $database->execute("SELECT ID, ProductLevelID, PromoCode, ProductID, ProductDesc, CriteriaID,  FORMAT(MinQty,0) MinQty, FORMAT(MinAmt,2)MinAmt, DATE_FORMAT(StartDate, '%m/%d/%y') StartDate, DATE_FORMAT(EndDate, '%m/%d/%y') EndDate FROM tpiincentivesbuyintmp WHERE ID = ".$IDS);
					if($result2->num_rows){
						while($row2 = $result2->fetch_object()){
							$buyin[$ctr]=$row2;
						}
					}
				$result3 = $database->execute("SELECT ID, ProductID, ProductDesc, CriteriaID, MinQty, FORMAT(MinAmt,2)MinAmt, DATE_FORMAT(StartDate,'%m/%d/%Y') StartDate, DATE_FORMAT(EndDate,'%m/%d/%Y') EndDate FROM tpiincentivesentltmp WHERE IBuyinID= ".$IDS);
				if($result3->num_rows){
						while($row3 = $result3->fetch_object()){		
							$entitlement[$ctr][]=$row3;
						}
				}
		$ctr++;	
		}
		$buyin_entitlement=array($buyin, $entitlement);		
	}
		die(tpi_JSONencode($buyin_entitlement));
}
if(isset($_POST['xID'])){
	$ID = $_POST['xID'];
	//Delete Incentives Buyin and Entitlement
	$tpiIncentives->tpiDeleteIncentivesPromoBuyinAndEntitlement($database, $ID);
	//AJAX to View Tables
	$tmpSelect = $tpiIncentives->tpiSelectTmpTableGetingPromo($database,$session->emp_id);
	if($tmpSelect->num_rows){
		while($row = $tmpSelect->fetch_object()){
			$BuyinMin			= "";
			$BCriteria			= "";
			$BuyinID			= $row->BuyinID;
			if($row->BuyinProdDesc == ""){
				$BuyinProdDesc		= $row->PromoCode;
			}else{
				$BuyinProdDesc		= $row->BuyinProdDesc;
			}
			$BuyinCriteria		= $row->BuyinCriteria;
			$BuyinMinQty		= $row->BuyinMinQty;
			$BuyinMinAmt		= $row->BuyinMinAmt;
			$BuyinStartDate     = $row->BuyinStartDate;
			$BuyinEndDate       = $row->BuyinEndDate;
			if($BuyinCriteria == 1){
				$BCriteria			= "Quantity";
				$BuyinMin			= $BuyinMinQty;
			}else{
				$BCriteria			= "Amount";
				$BuyinMin			= $BuyinMinAmt;
			}
			$EntMin				= "";
			$ECriteria			= "";
			$EntProductDesc     = $row->EntProductDesc;
			$EntCriteria        = $row->EntCriteria;
			$EntMinQty          = $row->EntMinQty;
			$EntMinAmt          = $row->EntMinAmt;
			$EntStartDate       = $row->EntStartDate;
			$EntEndDate         = $row->EntEndDate;
			if($EntCriteria == 1){
				$ECriteria			= "Quantity";
				$EntMin			= $EntMinQty;
			}else{
				$ECriteria			= "Amount";
				$EntMin			= $EntMinAmt;
			}
			
			$results[] = array( 'BuyinProdDesc' => $BuyinProdDesc,'BuyinCriteria' => $BCriteria, 'BMinVal' => $BuyinMin,
								'BuyinStartDate' => date("m/d/Y",strtotime($BuyinStartDate)), 
								'BuyinEndDate' => date("m/d/Y",strtotime($BuyinEndDate)), 'EntProductDesc' => $EntProductDesc, 
								'EntCriteria' => $ECriteria,'EntMin' => $EntMin, 'EntStartDate' => date("m/d/Y",strtotime($EntStartDate)), 
								'EntEndDate' => date("m/d/Y",strtotime($EntEndDate)), 'BuyinID' => $BuyinID);
		}
	die(tpi_JSONencode($results));
	}else{
	$results = 0;
	die(tpi_JSONencode($results));
	}
}

if(isset($_POST['xIDx'])){
	$ID = $_POST['xIDx'];
	//Delete Incentives Buyin and Entitlement
	$tpiIncentives->tpiDeleteIncentivesPromoBuyinAndEntitlement($database, $ID);
	$tpiIncentives->tpiDeleteIncentivesPromoSpecialCriteria($database, $ID);
	
	//AJAX to View Tables
	$result1 = $database->execute("SELECT ID FROM tpiincentivesbuyintmp WHERE sessionID = $session->emp_id");
	if($result1->num_rows){
	$g=0;
		while ($row = $result1->fetch_object()){
			$IDS = $row->ID;
				$result2 = $database->execute("SELECT ID, ProductLevelID, PromoCode, ProductID, ProductDesc, CriteriaID,  FORMAT(MinQty,0) MinQty, FORMAT(MinAmt,2)MinAmt, DATE_FORMAT(StartDate, '%m/%d/%y') StartDate, DATE_FORMAT(EndDate, '%m/%d/%y') EndDate FROM tpiincentivesbuyintmp WHERE ID = ".$IDS);
					if($result2->num_rows){
						while($row2 = $result2->fetch_object()){
							$Buyin[$g]=$row2;
						}
					}
				$result3 = $database->execute("SELECT ID, PromoCode, ProductID, ProductDesc, CriteriaID, MinQty, FORMAT(MinAmt,2)MinAmt, DATE_FORMAT(StartDate,'%m/%d/%Y') StartDate, DATE_FORMAT(EndDate,'%m/%d/%Y') EndDate FROM tpiincentivesentltmp WHERE IBuyinID= ".$IDS);
				if($result3->num_rows){
						while($row3 = $result3->fetch_object()){
														
							$entitnement[$g][]=$row3;
						}
				}
		$g++;
		}
		$buyin_entitlement=array($Buyin, $entitnement);		
		//echo $html1.'smile'.$html2;						
	}else{
		$buyin_entitlement = "";
	}
		die(tpi_JSONencode($buyin_entitlement));
}




if(isset($_GET['btn_add'])){

	$ProductID = "";
	$ProductDesc = "";
	/*Buyin Requirements*/
    $BuyinSelection 	= $_POST['BuyinSelection'];
	
	if($BuyinSelection == 1){
		$tpiSelection 	= $tpiIncentives->tpiSelectAllProduct($database, 1);
		while ($row = $tpiSelection->fetch_object()){
			$ProductDesc 	= addslashes($row->Name);
			$ProductID 		= $row->ID;
		}
	}else if($BuyinSelection == 2){
		$ID = $_POST['ProdLine'];
		$tpiSelection = $tpiIncentives->tpiSelectProductLineSelected($database, $ID);
		
		while ($row = $tpiSelection->fetch_object()){
			$ProductDesc 	= addslashes($row->Name);
			$ProductID 		= $row->ID;
		}
	}else if ($BuyinSelection == 3){
		
		$BRItemCode = $_POST['txtBRItemCode'];
		$tpiSelection = $tpiIncentives->tpiSelectProductCode($database, $BRItemCode);
		
		while ($row = $tpiSelection->fetch_object()){
			$ProductDesc 	= addslashes($row->Name);
			$ProductID 		= $row->ID;
		}
	}else if($BuyinSelection == 4 || $BuyinSelection == 5){
	
			$PromoCode = $_POST['txtPromoCodePromo'];
			$BRMinVal  = $_POST['txtBRMinVal'];
			$tpiSelection = $database->execute("select * from promo where Code ='".$PromoCode."'");
		if($tpiSelection->num_rows){
			while($r = $tpiSelection->fetch_object()){
				$PromoID = $r->ID;
				$PromoCode = $r->Code;
				$StartDate = $r->StartDate;
				$EndDate = $r->EndDate;
			}
			
			
			//Here Insert Promo Incentives
			$database->execute("Insert into tpiincentivesbuyintmp (ProductLevelID, PromoID, PromoCode, CriteriaID, MinQty, MinAmt, StartDate, EndDate, Changed, sessionID)
				values ($BuyinSelection, $PromoID, '$PromoCode', 1, $BRMinVal, 0, '$StartDate', '$EndDate', 1, ".$_SESSION['emp_id'].")");
			
			$tmp = $tpiIncentives->insert_last_id($database);
			if($tmp->num_rows){
				while($row = $tmp->fetch_object()){
					$IBuyinID = $row->insert_id;
				}
			}
			
			
			/*PromoEntitlement Tmp*/
			$EProductCode 		= $_POST['txtEPromoCode'];
			$EProdDesc 			= $_POST['txtEProdDesc'];
			$EMinVal 			= $_POST['txtEMinVal'];
			$EntitlementCriteria = $_POST['EntitleCriteria'];
			
			$tpiEntitlementtmp = $tpiIncentives->tpiSelectProductCode($database, $EProductCode);
				
			while ($row = $tpiEntitlementtmp->fetch_object()){
				$EProductDesc 	= addslashes($row->Name);
				$EProductID 	= $row->ID;
			}
			$tpiIncentives->tpiInsertIncentivesTmpEntitlement($database, $EProductID, $EProductDesc, $EMinVal, $EntitlementCriteria, 1, $StartDate, $EndDate, $IBuyinID);
			
		}
		
	}
	
	if($BuyinSelection == 1 || $BuyinSelection == 2 || $BuyinSelection == 3){
	
			$buyincriteria 		= $_POST['buyincriteria'];
			
			$BRMinVal 			= $_POST['txtBRMinVal'];
			$BuyinSetStartDate 	= $_POST['txtBuyinSetStartDate']; 
			$BuyinSetEndDate 	= $_POST['txtBuyinSetEndDate'];
			/*PromoEntitlement*/
			$EProductCode 		= $_POST['txtEPromoCode'];
				$tpiEntitlementtmp = $tpiIncentives->tpiSelectProductCode($database, $EProductCode);
				
				while ($row = $tpiEntitlementtmp->fetch_object()){
					$EProductDesc 	= addslashes($row->Name);
					$EProductID 	= $row->ID;
				}
			$EProdDesc 			= $_POST['txtEProdDesc'];
			$EMinVal 			= $_POST['txtEMinVal'];
			$EntitlementCriteria = $_POST['EntitleCriteria'];
			/* Buyin Requirements Tmp*/
			$InsertIncentivesTmpBuyin = $tpiIncentives->tpiInsertIncentivesTmpBuyin($database, $BuyinSelection, $ProductID, $ProductDesc, $buyincriteria, $BRMinVal, $BuyinSetStartDate, $BuyinSetEndDate, 1, $_SESSION['emp_id']);
			
			$tmp = $tpiIncentives->insert_last_id($database);
			if($tmp->num_rows){
				while($row = $tmp->fetch_object()){
					$IBuyinID = $row->insert_id;
				}
			}
			
			
			/*PromoEntitlement Tmp*/
			$tpiIncentives->tpiInsertIncentivesTmpEntitlement($database, $EProductID, $EProductDesc, $EMinVal, $EntitlementCriteria, 1, $BuyinSetStartDate, $BuyinSetEndDate, $IBuyinID);
			
	}
	
	$tmpSelect = $tpiIncentives->tpiSelectTmpTableGetingPromo($database,$session->emp_id);
		

		if($tmpSelect->num_rows){
		
				while($r = $tmpSelect->fetch_object()){
					
					//fetching promo
					$BuyinMin			= "";
					$BCriteria			= "";
					$BuyinID			= $r->BuyinID;
					if($r->BuyinProdDesc == ""){
						$BuyinPromoCode		= $r->PromoCode;
					}else{
						$BuyinPromoCode		= $r->BuyinProdDesc;
					}
					$BuyinCriteria		= $r->BuyinCriteria;
					$BuyinMinQty		= $r->BuyinMinQty;
					$BuyinMinAmt		= number_format($r->BuyinMinAmt,2);
					$BuyinStartDate     = $r->BuyinStartDate;
					$BuyinEndDate       = $r->BuyinEndDate;
					$ProductLevelID     = $r->ProductLevelID;
					
					if($BuyinCriteria == 1){
						$BCriteria			= "Quantity";
						$BuyinMin			= $BuyinMinQty;
					}else{
						$BCriteria			= "Amount";
						$BuyinMin			= $BuyinMinAmt;
					}
					
					$EntMin				= "";
					$ECriteria			= "";
					$EntProductDesc     = $r->EntProductDesc;
					$EntCriteria        = $r->EntCriteria;
										   
					$EntMinQty          = $r->EntMinQty;
					$EntMinAmt          = number_format($r->EntMinAmt,2);
					$EntStartDate       = $r->EntStartDate;
					$EntEndDate         = $r->EntEndDate;
					if($EntCriteria == 1){
						$ECriteria			= "Quantity";
						$EntMin			= $EntMinQty;
					}else{
						$ECriteria			= "Price";
						$EntMin			= $EntMinAmt;
					}
					
					if($ProductLevelID == 4){
						$ProductLevel = "Single Line - ";
					}else if($ProductLevelID == 5){
						$ProductLevel = "Multi Line - ";
					}else{
						$ProductLevel = "";
					}
					$results[] = array( 'BuyinProdDesc' => $ProductLevel."".$BuyinPromoCode, 
										'BuyinCriteria' => $BCriteria, 
										'BMinVal'		=> $BuyinMin,
										'BuyinStartDate' => date("m/d/Y",strtotime($BuyinStartDate)), 
										'BuyinEndDate' => date("m/d/Y",strtotime($BuyinEndDate)), 
										'EntProductDesc' => $EntProductDesc, 
										'EntCriteria' => $ECriteria,
										'EntMin' => $EntMin, 
										'EntStartDate' => date("m/d/Y",strtotime($EntStartDate)), 
										'EntEndDate' => date("m/d/Y",strtotime($EntEndDate)),
										'BuyinID' => $BuyinID
									  );	
				
						
			}
			
			die(tpi_JSONencode($results));
		}
}	

if(isset($_POST['xSave'])){

		try
		{ 	
			$database->beginTransaction();
			$NonGSU		 	= 0;	
			$IndirectGSU 	= 0;
			$session 		= $_SESSION['emp_id'];
			$PromoCode		= $_POST["xPromoCode"];
			$PromoDesc		= $_POST["xPromoDesc"];
			$inctype		= $_POST["xinctvtype"];
			$mechtype		= $_POST["xmechtype"];
			$startdate		= date("Y-m-d",strtotime($_POST["xStartDate"]));
			$endDate		= date("Y-m-d",strtotime($_POST["xEndDate"]));
			$NoCpi			= $_POST["xNoCPI"];
			$xNonGSU		= $_POST["xNonGSU"];
			if($xNonGSU == ""){
				$NonGSU	= 0;
			}else{
				$NonGSU	= $xNonGSU;
			}
			$xIndirectGSU 	= $_POST["xIndirectGSU"];
			if($xIndirectGSU == ""){
				$IndirectGSU = 0;
			}else{
				$IndirectGSU = $xIndirectGSU;
			}
			$chkIsPlus		= $_POST["xchckIsPlus"];
			
			$xdirectGSU		= $_POST['xdirectGSU'];
			if($xdirectGSU == ""){
				$directGSU = 0;
			}else{
				$directGSU = $_POST['xdirectGSU'];
			}
			
			//Incentives header
			$tpiIncentives->InsertIncentivesHeader($database,$PromoCode,$PromoDesc,$inctype,$mechtype,$startdate,$endDate,$xNonGSU,$IndirectGSU,$chkIsPlus, $session);
			$ID = $tpiIncentives->insert_last_id($database);
			
			if($ID->num_rows){ while($row = $ID->fetch_object()){ $IDHeader = $row->insert_id; } };
			
			//Incentives Promoavailment
			$tpiIncentives->InsertIncentivesPromoAvailment($database,$IDHeader,$NoCpi,$NonGSU,$IndirectGSU, $directGSU);
			
			//Incentives Buyin & Incentives Entitlement
			$BuyinEntitlement = $tpiIncentives->selectIncentivesPromoBuyinAndEntitlement($database, $IDHeader, $_SESSION['emp_id']);
			if($BuyinEntitlement->num_rows){
				while($row = $BuyinEntitlement->fetch_object()){
					$IDHeader; 
					$BProductLevelID	= $row->BProductLevelID;
					$BProductID	 	 	= $row->BProductID;
					$BPromoID	 	 	= $row->PromoID;
					$BCriteriaID	 	= $row->BCriteriaID;
					$BMinQty 	 		= $row->BMinQty; 
					$BMinAmt	 		= $row->BMinAmt;
					$BStartDate	 		= date("Y-m-d",strtotime($row->BStartDate));
					$BEndDate	 		= date("Y-m-d",strtotime($row->BEndDate));
					$EProductID	 		= $row->EProductID;
					$ECriteriaID		= $row->ECriteriaID;
					$EMinQty 	 		= $row->EMinQty; 
					$EMinAmt			= $row->EMinAmt;
					$EStartDate 		= date("Y-m-d",strtotime($row->EStartDate)); 
					$EEndDate	 		= date("Y-m-d",strtotime($row->EEndDate));
					$Btype	= 0; 
					$BQty	= 0;
					if($BProductLevelID == 1 || $BProductLevelID == 2 || $BProductLevelID == 3){
						$tpiIncentives->IncentivesInsertPromoBuyin1($database,$IDHeader,$BProductLevelID,$BProductID,$BCriteriaID,$BMinQty,$BMinAmt,$BStartDate,$BEndDate, $Btype, $BQty);
						$lastID = $tpiIncentives->insert_last_id($database);
					}
					if($BProductLevelID == 4 || $BProductLevelID == 5){
						$tpiIncentives->IncentivesInsertPromoBuyinWithinPromo($database,1,$IDHeader,$BProductLevelID,$BPromoID,$BCriteriaID,$BMinQty,$BMinAmt,$BStartDate,$BEndDate, $Btype, $BQty,0);
						$lastID = $tpiIncentives->insert_last_id($database);
				
					}
					
					if($lastID->num_rows){
						while($row = $lastID->fetch_object()){
							$IncentiveBuyinID = $row->insert_id;
						}
					}
					$tpiIncentives->IncentivesInsertEntitlement($database, $IncentiveBuyinID, $EProductID, $ECriteriaID, $EMinQty, $EMinAmt, $EStartDate, $EEndDate);
			}
				//Delete Table
				$tpiIncentives->DeleteIncentivesEntitlementTmp($database, $session);
				$tpiIncentives->DeleteIncentivesPromoBuyinTmp($database, $session);
				$database->commitTransaction();
				
				$result = array('success'=> 1, 'result'=>'Save Successfull');
				die(tpi_JSONencode($result));
				
			}
		}
		catch(Exception $e)
		{		
				$database->rollbackTransaction();
				$message = $e->getMessage()."\n";	
				$result = array('failed'=> 0, 'result' => $message);
				die(tpi_JSONencode($result));
			
		}
	
	
}

if(isset($_POST['xSave1'])){
		try
		{ 	
		
			$database->beginTransaction();
			$NonGSU		 	= 0;	
			$IndirectGSU 	= 0;
			$session 		= $_SESSION['emp_id'];
			$PromoCode		= $_POST["xPromoCode"];
			$PromoDesc		= $_POST["xPromoDesc"];
			$inctype		= $_POST["xinctvtype"];
			$mechtype		= $_POST["xmechtype"];
			$startdate		= date("Y-m-d",strtotime($_POST["xStartDate"]));
			$endDate		= date("Y-m-d",strtotime($_POST["xEndDate"]));
			$NoCpi			= $_POST["xNoCPI"];
			$xNonGSU		= $_POST["xNonGSU"];
			if($xNonGSU == ""){
				$NonGSU	= 0;
			}else{
				$NonGSU	= $xNonGSU;
			}
			$xIndirectGSU 	= $_POST["xIndirectGSU"];
			if($xIndirectGSU == ""){
				$IndirectGSU = 0;
			}else{
				$IndirectGSU = $xIndirectGSU;
			}
			$xdirectGSU		= $_POST['xdirectGSU'];
			if($xdirectGSU == ""){
				$directGSU = 0;
			}else{
				$directGSU = $xdirectGSU;
			}
			$chkIsPlus		= $_POST["xchckIsPlus"];
			
			//Incentives header
			$tpiIncentives->InsertIncentivesHeader($database,$PromoCode,$PromoDesc,$inctype,$mechtype,$startdate,$endDate,$xNonGSU,$IndirectGSU,$chkIsPlus, $session);
			
			$ID = $tpiIncentives->insert_last_id($database);
			if($ID->num_rows){ while($row = $ID->fetch_object()){ $IDHeader = $row->insert_id; } };
			//Incentives Promoavailment
			$tpiIncentives->InsertIncentivesPromoAvailment($database,$IDHeader,$NoCpi,$NonGSU,$IndirectGSU, $directGSU);
			$incentivesBuyin = $database->execute("SELECT a.*, c.IBuyinID SBuyinID, c.NoOfwiks sNoOfWiks, c.StartWeek1, c.EndWeek1,c.StartWeek2, 
												   c.EndWeek2, c.StartWeek3, c.EndWeek3,c.StartWeek4, c.EndWeek4 
												   , c.MinVal csMinVal, c.NoOfWiks,  c.RequiredNoOfWeeksMet FROM tpiincentivesbuyintmp a 
												   LEFT JOIN tpispecialcriteriatmp c ON a.ID = c.IBuyinID WHERE a.sessionID = $_SESSION[emp_id]");
			
			if($incentivesBuyin->num_rows){
			
				while($row = $incentivesBuyin->fetch_object()){
					$IDHeader; 
					$xID				= $row->ID;
					$BProductLevelID	= $row->ProductLevelID;
					$BProductID	 	 	= $row->ProductID;
					$BCriteriaID	 	= $row->CriteriaID;
					$BMinQty 	 		= $row->MinQty; 
					$BMinAmt	 		= $row->MinAmt;
					$BStartDate	 		= date("Y-m-d",strtotime($row->StartDate));
					$BEndDate	 		= date("Y-m-d",strtotime($row->EndDate));
					$BType				= $row->Type;
					$BQty				= $row->Qty;
					$SpecialCriteria	= $row->SpecialCriteria;
					$BPromoID			= $row->PromoID;
					$SBuyinID			= $row->SBuyinID;
					$sNoOfWiks          = $row->sNoOfWiks;
					$StartWeek1         = $row->StartWeek1;
					$EndWeek1           = $row->EndWeek1;
					$StartWeek2         = $row->StartWeek2;
					$EndWeek2           = $row->EndWeek2;
					$StartWeek3         = $row->StartWeek3;
					$EndWeek3           = $row->EndWeek3;
					$StartWeek4         = $row->StartWeek4;
					$EndWeek4           = $row->EndWeek4;
					$csMinVal           = $row->csMinVal;
					$NoOfWiks			= $row->NoOfWiks;
					$RequiredNoOfWeeksMet = $row->RequiredNoOfWeeksMet;
					
					if($BProductLevelID == 1 || $BProductLevelID == 2 || $BProductLevelID == 3){
						$tpiIncentives->IncentivesInsertPromoBuyin($database,$IDHeader,$BProductLevelID,$BProductID,$BCriteriaID,$BMinQty,$BMinAmt, $BStartDate, $BEndDate, $BType, $BQty, $SpecialCriteria);
					}
					if($BProductLevelID == 4 || $BProductLevelID == 5){
					
						$tpiIncentives->IncentivesInsertPromoBuyinWithinPromo($database,2,$IDHeader,$BProductLevelID,$BPromoID,$BCriteriaID,$BMinQty,$BMinAmt,$BStartDate,$BEndDate, $BType, $BQty, $SpecialCriteria );
						
					}
					
					$lastID = $tpiIncentives->insert_last_id($database);
					if($lastID->num_rows){
						while($row = $lastID->fetch_object()){
							$IncentiveBuyinID = $row->insert_id;
						}
					}
					
					
					if($SBuyinID != '' || $SBuyinID != null){
						$tpiIncentives->tpiInsertSpecialCriteria1($database, $StartWeek1,$EndWeek1, $StartWeek2, $EndWeek2, $StartWeek3, $EndWeek3, $StartWeek4, $EndWeek4 , $IncentiveBuyinID, $csMinVal ,$NoOfWiks, $RequiredNoOfWeeksMet);
					}
					
					$incentivesEntitlement = $database->execute("SELECT * FROM tpiincentivesentltmp where IBuyinID = $xID");
						if($incentivesEntitlement->num_rows){
							while($row = $incentivesEntitlement->fetch_object()){
								$EProductID	 		= $row->ProductID;
								$ECriteriaID		= $row->CriteriaID;
								$EMinQty 	 		= $row->MinQty; 
								$EMinAmt			= $row->MinAmt;
								$EStartDate 		= date("Y-m-d",strtotime($row->StartDate)); 
								$EEndDate	 		= date("Y-m-d",strtotime($row->EndDate));
								$tpiIncentives->IncentivesInsertEntitlement($database, $IncentiveBuyinID, $EProductID, $ECriteriaID, $EMinQty, $EMinAmt, $EStartDate, $EEndDate);
							}
						}
				}
			}
				//Delete Table
				$tpiIncentives->DeleteIncentivesEntitlementTmp($database, $session);
				$tpiIncentives->DeleteIncentivesPromoBuyinTmp($database, $session);
				$tpiIncentives->DeleteIncentivesSpecialCriteriaTmp($database, $session);
				$database->commitTransaction();
				$result = array('success'=> 1, 'result'=>'Save Successfull');
				die(tpi_JSONencode($result));	
			
		}
		catch(Exception $e)
		{		
				$database->rollbackTransaction();
				$message = $e->getMessage()."\n";	
				$result = array('failed'=> 0);
				die(tpi_JSONencode($result));
			
		}
}	
	




if(isset($_POST['xvalidate'])){
$PromoCode = $_POST['PromoCode'];
	$validate = $tpiIncentives->IncentivesValidateIfExistPromoCode($database, $PromoCode);
		if($validate->num_rows){
			$result = array('results'=> 1, 'information'=>'Promo Code is Already Exist');
		}else{
			$result = array('results'=> 0, 'information'=>'Available PromoCode');
		}
		die(tpi_JSONencode($result));
}

if(isset($_GET['xDelete'])){
	$xID = $_POST['xDeletedID'];
	$database->execute("DELETE FROM incentivespromobuyin where ID = ".$xID);
	$database->execute("DELETE FROM incentivesspecialcriteria where IBuyinID = ".$xID);
	$database->execute("DELETE FROM incentivespromoentitlement where IncentivesPromoBuyinID = ".$xID);
	die(tpi_JSONencode(array('result'=>1)));
}
?>