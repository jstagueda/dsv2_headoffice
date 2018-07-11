<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
/*
	@Author: Gino Leabres
	@Date: 11-25-2013
	@Description: These process used it to Special Promo Uploader..
*/
 //Create here Temporary table..
	$spUploader->CreateSpecialPromoTmpTable($database);
	$rows = explode("\n", $fileData);
	$cd_cnt = 0;
	$not_uploaded = 0;
	
	foreach ($rows as $row){
		$xfile = explode(',',trim($row));
		
		$PromoCode 				= trim($xfile[0]); //Special Promo Code..
		$Description            = $mysqli->real_escape_string(trim($xfile[1])); // Special Description..
		$PromoType              = trim($xfile[2]); // Promo Type..
		$StartDate              = date("Y-m-d",strtotime(trim($xfile[3]))); // Start Date..
		$EndDate                = date("Y-m-d",strtotime(trim($xfile[4]))); // End Date..
		$BrochurePage           = trim($xfile[5]); // Brochure Page..
		$NonGSU                 = trim($xfile[6]); // Non GSU..
		$DirectGSU				= trim($xfile[7]); // Direct GSU..
		$InDirectGSU 			= trim($xfile[8]); // Indirect GSU..
		$IsPlusPlan 			= trim($xfile[9]); // Is Plus Plan..
		$ProductCode 			= trim($xfile[10]); // Product Code..
		$ProductName			= $mysqli->real_escape_string(trim($xfile[11])); // Product Name..
		$BuyinCriteria			= trim($xfile[12]); // Buyin Criteria..
		$BuyinMinimumQty		= trim($xfile[13]); // Buyin Minimum Qty..
		$BuyinMinimumAmnt 		= trim($xfile[14]); // Buyin Minimum Amnt..
		$EntitlementAmnt 		= trim($xfile[15]); //  // Enttitlement Amount..
		
		//Insert Here to temporary table..
		$spUploader->InsertDataToTmpTablle($database,$PromoCode,$Description,$PromoType,$StartDate,$EndDate,$BrochurePage,
							$NonGSU,$DirectGSU,$InDirectGSU,$IsPlusPlan,$ProductCode,$ProductName,
							$BuyinCriteria,$BuyinMinimumQty,$BuyinMinimumAmnt,$EntitlementAmnt);
	}
	
	//Header Here..
	$SpecialPromoSelectHeader = $spUploader->SpecialPromoSelectHeader($database);
	if($SpecialPromoSelectHeader->num_rows > 0):
		while($r = $SpecialPromoSelectHeader->fetch_object()):
				$PromoCode 		= $r->PromoCode;
				$Description 	= $r->Description;  
				$PromoType  	= $r->PromoType ; 
				$StartDate 		= $r->StartDate;
				$EndDate 		= $r->EndDate ;
				$BrochurePage 	= $r->BrochurePage; 
				$NonGSU 		= $r->NonGSU;
				$DirectGSU 		= $r->DirectGSU;
				$InDirectGSU 	= $r->InDirectGSU;
				$IsPlusPlan 	= $r->IsPlusPlan;
				
				$InsertSpecialPromoHeader = $spUploader->InsertSpecialPromoHeader($database,$PromoCode,$Description,$PromoType,$StartDate,$EndDate,$BrochurePage,$NonGSU,$DirectGSU,		
										$InDirectGSU,$IsPlusPlan);
				//Inserted ID..

				$InsertedID = $InsertSpecialPromoHeader;
			
				$GetAllSpecialPromoBuyinAndEntitlement = $spUploader->GetAllSpecialPromoBuyinAndEntitlement($database,$PromoCode);
				if($GetAllSpecialPromoBuyinAndEntitlement > 0){
					while($s = $GetAllSpecialPromoBuyinAndEntitlement->fetch_object()){
						//Buyin and Entitlement here..
					
						$ProductID			= $s->ProductID;
						$BuyinCriteria 		= $s->BuyinCriteria; 
						$BuyinMinimumQty	= $s->BuyinMinimumQty;
						$BuyinMinimumAmnt 	= $s->BuyinMinimumAmnt; 
						$EntitlementAmnt 	= $s->EntitlementAmnt; 						
						//Insert Here..
						$spUploader->InsertPromoBuyinAndEntitlement($database,$InsertedID, $ProductID,$BuyinCriteria,$BuyinMinimumQty,$BuyinMinimumAmnt,$EntitlementAmnt);
						$cd_cnt++;
					}
				}
		endwhile;
		$msgLog .= "<br><br><br>";
		$msgLog .= "Total Rows In File: ".$spUploader->TotalRowsInFile($database)."<br>";
		$msgLog .= "Total Rows Uploaded: ". $cd_cnt."<br>";
		$msgLog .= "Total Rows Not Uploaded: ". $spUploader->GetProductCodeAndPromoCodeDoesNotExistinProductMasterCount($database)."<br><br><br>";
		$GetProductCodeAndPromoCodeDoesNotExistinProductMaster = $spUploader->GetProductCodeAndPromoCodeDoesNotExistinProductMaster($database);
		
				$LogType   		= "Special Promo";
				$Description    = $msgLog;
				$xDate			= "NOW()";
				// $database->execute("insert into systemlog (LogType,Description,xDate) VALUES ('".$LogType."','".$Description."',".$xDate.")");
				$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");
		
		
		if($GetProductCodeAndPromoCodeDoesNotExistinProductMaster > 0){
			while($t=$GetProductCodeAndPromoCodeDoesNotExistinProductMaster->fetch_object()){
				$PromoCode = $t->PromoCode;
				$Product = $t->Product;
				$errmsg .= "Entitlement Product Code - ".$Product." for Promo Code ".$PromoCode." does not exist in Product master. <br>";
			}
		}
	endif;
	//Drop Table here..
	$database->execute("DROP TABLE IF EXISTS `tmpspecialpromo`");
	// END process here..
 
?>
