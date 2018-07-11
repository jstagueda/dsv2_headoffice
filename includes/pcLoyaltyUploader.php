<?php
/*
AUTHOR BY: GINO LEABRES
DATE 11/31/2012
New Submodule Loyalty Uploader
*/
	
	global $database;
	$array_truncate =  array("uploadloyalbrtmp", "uploadloyalenttmp");
	foreach ($array_truncate as $tmptable){
			$truncate = "truncate table ".$tmptable;
			$database->execute($truncate);
	}
	/*message and error parameter*/
	$errormessage 	= "";
	$errmsg			= "";
	$message		= "";
	$msglog2		= "";
	/* parameter loyalty buyin requirement */
	$start_date 				=	"";
	$end_date 				    =	"";
	$PromoCode 				    =	"";
	$PromoDescription		    =	"";
	$PurchaseRequirementtype    =	"";
	$Criteria  				    =	"";
	$min_val   				    =	"";
	$points					    =	"";
	$brand					    =	"";
	$form					    =	"";
	$style					    =	"";
	$pmg					    =	"";
	$pline						=	"";
	$sku						=	"";
	/*parameter Loyalty Entilement*/
	$lPromoCode 	=	"";
	$lSKU 			=   "";
	$lPOINTS 		=   "";
	$lstart_date 	=   "";
	$lend_date 		=   "";
	/*Process for Uploading Data to TMP table*/
	if(isset($_POST['btnUpload'])){
		$path_loyalbr = $_FILES['loyalbr']['name'];
		$path_loyalent = $_FILES['loyalent']['name'];
		$uploadErr = "";

		if($path_loyalbr == "" || $path_loyalent == ""){
	 		$errormessage = "Please select a file to upload.";
			redirect_to("../index.php?pageid=177&errmsg=$errormessage"); 
	 	}
		
		if (is_uploaded_file($file = $_FILES['loyalbr']['tmp_name'])){
			$fileData = trim(file_get_contents($file));
			
			$isrho = true;
			$rows = explode("\n", $fileData);
		 	$first_row = true;
			try 
			{
				$counter = 1;
				
					foreach ($rows as $row){
						$file = explode(',',trim($row));
						$start_date 				= date('Y-m-d', strtotime($file[0]));
						$end_date 					= date('Y-m-d', strtotime($file[1]));
						$PromoCode 					= trim($file[2]);
						$PromoDescription			= trim($file[3]);
						$PurchaseRequirementtype	= trim($file[4]);
						$Criteria  					= trim($file[5]);
						$min_val   					= trim($file[6]);
						$points						= trim($file[7]);
						$brand						= trim($file[8]);
						$form						= trim($file[9]);
						$style						= trim($file[10]);
						$pmg						= trim($file[11]);
						$pline						= trim($file[12]);
						$sku						= trim($file[13]);
						if($brand == ""){
							$brand = "";
						}
						if($form == ""){
							$form = '';
						}	
						if($pline == ""){
							$pline = '';
						}
						if($pmg == ""){
							$pmg = '';
						}
						if($style == ""){
							$style = '';
						}
						if($sku == ""){
							$sku = '';
						}
						if($PurchaseRequirementtype == "Single"){
							$PurchaseRequirementtype = 1;
						}else{
							$PurchaseRequirementtype = 2;
						}
							if($counter != 1){
								if($PromoCode != ''){
									$query_insert = "INSERT INTO uploadloyalbrtmp (start_date, end_date, PromoCode, Criteria, min_val, Points, Brand, Form, Style, Pmg, Pline, SKU, PromoTitle, prt )
													VALUES ('".$start_date."', '".$end_date."', '".$PromoCode."', '".$Criteria."', ".$min_val.", ".$points.", '".$brand."', 
															'".$form."', '".$style."', '".$pmg."', '".$pline."', '".$sku."','".$PromoDescription."',".$PurchaseRequirementtype.")";
									$database->execute($query_insert);
								}
							}
						$counter++;
					}
			}
			catch (Exception $e) 
			{
				$database->rollbackTransaction();
				$errmsg = $e->getMessage()."\n";

				redirect_to("../index.php?pageid=171&errmsg=$errmsg");
			}
		}
		
		if (is_uploaded_file($file = $_FILES['loyalent']['tmp_name'])){
			$fileData = trim(file_get_contents($file));
			$isrho = true;
			$rows = explode("\n", $fileData);
		 	$first_row = true;	
			try 
			{
				$counter = 1;
						foreach ($rows as $row){
								$file = explode(',',trim($row));
								$lPromoCode 	= $file[0];
								$lSKU 			= $file[1];
								$lPOINTS 		= $file[2];
								$lstart_date 	= date("Y-m-d",strtotime($file[3]));
								$lend_date 		= date("Y-m-d",strtotime($file[4]));
								if($counter != 1){
									if($lPromoCode != ""){
										$query_insert = " 
														INSERT INTO uploadloyalenttmp (PromoCode, SKU, Points, start_date, end_date)
														VALUES ('$lPromoCode', $lSKU, $lPOINTS, '$lstart_date', '$lend_date')
														";
										$database->execute($query_insert);
									}
								}                    
							$counter++;
					}
			}
			catch (Exception $e) 
			{
				$database->rollbackTransaction();
				$errmsg = $e->getMessage()."\n";
				redirect_to("../index.php?pageid=171&errmsg=$errmsg");
			}
		}
		
			$promobuyin 	  = $database->execute("SELECT * FROM uploadloyalbrtmp");
			$buyinCount		  = $promobuyin->num_rows;
			$promoentitlement = $database->execute("SELECT * FROM uploadloyalenttmp");
			$entitlementcount = $promoentitlement->num_rows;
			//$path_loyalbr
			//$path_loyalent
			$database->execute("INSERT INTO loyaltylogs (BuyinFileName, EntitFileName, TotalFileBuyin, TotalFileEnt, EnrollmentDate, UserID) 
								VALUES('".$path_loyalbr."','".$path_loyalent."',".$buyinCount.", ".$entitlementcount.", now(), ".$session->emp_id.")");
								
		/*	temporary table passing to Permanent table */
		/* Loyalty Buyin Requiremnt */
		$buyintmpquery = "SELECT * FROM uploadloyalbrtmp";
		$result = $database->execute($buyintmpquery);
			if($result->num_rows){
				/*header*/
				//Check Header if exist
					$headerChecker = "SELECT PromoCode FROM loyaltypromo GROUP BY PromoCode";
					$headerCheckerResult = $database->execute($headerChecker);
					if($headerCheckerResult->num_rows){
						while ($row = $headerCheckerResult->fetch_object()){
							$PromoCode = $row->PromoCode;
							$deleteheader = "DELETE FROM loyaltypromo where PromoCode = '$PromoCode' ";
							$database->execute($deleteheader);
						}
						$insertHeaderQuery = "INSERT INTO loyaltypromo (PromoCode, PromoTitle, prt, StartDate, EndDate, EnrollmentDate, LastModifiedDate)
											SELECT PromoCode, PromoTitle, prt, NOW(), NOW(),NOW(),NOW() FROM uploadloyalbrtmp
											GROUP BY PromoCode";
						$database->execute($insertHeaderQuery);
						
					}else{ 
						//if not exist execute this query
						$insertHeaderQuery = "INSERT INTO loyaltypromo (PromoCode, PromoTitle, prt, StartDate, EndDate, EnrollmentDate, LastModifiedDate)
											SELECT PromoCode, PromoTitle, prt, NOW(), NOW(),NOW(),NOW() FROM uploadloyalbrtmp
											GROUP BY PromoCode";
						$database->execute($insertHeaderQuery);
					}
				}
				
				
				
				

						//Product Code
						$ProductCodeQuery = "SELECT tmp.PromoCode, lp.ID LoyaltyID, p.ID ProductID, c.ID CriteriaID, tmp.min_val, tmp.Points, tmp.start_date, tmp.end_date FROM 
											 uploadloyalbrtmp tmp
											 INNER JOIN product p ON p.Code = CONCAT(REPEAT('0', (7-LENGTH(SKU))), tmp.SKU)
											 INNER JOIN criteria c ON c.code = tmp.Criteria
											 INNER JOIN loyaltypromo lp ON lp.PromoCode = tmp.PromoCode
											 WHERE tmp.SKU <> ''";
											 
						$ProductCodeSelection = $database->execute($ProductCodeQuery);
							if($ProductCodeSelection->num_rows){
							
								while($row1 = $ProductCodeSelection->fetch_object()){
							
									$PromoID1    = $row1->LoyaltyID;
									$PromoCode1  = $row1->PromoCode;
									$ProductID   = $row1->ProductID;
									$CriteriaID  = $row1->CriteriaID;
									$min_val	 = $row1->min_val;
									$Points		 = $row1->Points;
									$StartDate   = $row1->start_date;
									$EndDate	 = $row1->end_date;
									
									$validation = $database->execute("SELECT LoyaltyPromoID from loyaltypromobuyin where LoyaltyPromoID =".$PromoID1);
									if($validation->num_rows){
										while($vtable = $validation->fetch_object()){
											$parentID = $vtable->LoyaltyPromoID;
										}
									}else{
										//FOR PARENT ID 
											$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID, ValueID, CriteriaID, 
																Points, MinQty, MinAmt, StartDate, EndDate, LevelType)
																VALUES ($PromoID1, null, null, null, null, null, null, null, null, '$start_date','$end_date', 0)");
											$result1 = $database->execute("SELECT LAST_INSERT_ID() insert_id");
											if($result1->num_rows){
												while ($row = $result1->fetch_object()){
													$parentID = $row->insert_id;
												}
									
											}
									}
									
									if($CriteriaID == 1){ //QUANTITY
										$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID,
															CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
															VALUES (".$PromoID1.", ".$parentID.", 1, ".$ProductID.", ".$CriteriaID.", ".$Points.", ".$min_val.", 0, 
															'".$StartDate."', '".$EndDate."')");
									}else{ //AMOUNT
										$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID,
															CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
															VALUES (".$PromoID1.", ".$parentID.", 1, ".$ProductID.", ".$CriteriaID.", ".$Points.", 0, ".$min_val.", 
															'".$StartDate."', '".$EndDate."')");
									}
									//Entitlement
									$entProductCode = $database->execute("select tmp.*, p.ID ProductID from uploadloyalenttmp tmp  
																		  inner join product p on tmp.SKU = p.Code where tmp.PromoCode = '".$PromoCode1."'");
									if($entProductCode->num_rows){
										while($row2 = $entProductCode->fetch_object()){
											$sku    	= $row2->SKU;
											$Points 	= $row2->Points;
											$StartDate 	= $row2->start_date;
											$EndDate	= $row2->end_date;
											$ProductID	= $row2->ProductID;
											$tmpID		= $row2->ID;
											//$parentID
											$database->execute("INSERT INTO loyaltypromoentitlement (LoyaltyBuyinID, ProductID, Points, StartDate, EndDate) 
																values (".$parentID.", ".$ProductID.", ".$Points.", ".$StartDate.", ".$EndDate.")");	
																
											$database->execute("delete from uploadloyalenttmp where ID = '".$tmpID."'");
										}
									}
								}		
							}
							
							//Brand
							
							$BrandQuery = "SELECT tmp.Brand, lp.ID LoyaltyID, v.ID BrandID, tmp.PromoCode, c.ID CriteriaID, tmp.min_val, tmp.Points, tmp.start_date, tmp.end_date FROM uploadloyalbrtmp tmp
									       inner join criteria c on c.code = tmp.Criteria
									       inner join value v on tmp.Brand = v.Name
									       INNER JOIN loyaltypromo lp ON lp.PromoCode = tmp.PromoCode
									       where tmp.brand <> ''
									       ";
										   
							$Brand = $database->execute($BrandQuery);		
							if($Brand->num_rows){
								while($brow = $Brand->fetch_object()){
									$PromoID1		= $brow->LoyaltyID;
									$BrandID		= $brow->BrandID;
									$PromoCode1  	= $brow->PromoCode;
									$CriteriaID		= $brow->CriteriaID;
									$min_val		= $brow->min_val;
									$Points			= $brow->Points;
									$StartDate		= $brow->start_date;
									$EndDate		= $brow->end_date;
									
									$validation = $database->execute("SELECT LoyaltyPromoID from loyaltypromobuyin where LoyaltyPromoID =".$PromoID1);
									if($validation->num_rows){
										while($vtable = $validation->fetch_object()){
											$parentID1 = $vtable->LoyaltyPromoID;
										}
									}else{
										//FOR PARENT ID 
											$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID, ValueID, CriteriaID, 
																Points, MinQty, MinAmt, StartDate, EndDate, LevelType)
																VALUES ($PromoID1, null, null, null, null, null, null, null, null, '$start_date','$end_date', 0)");
											$result1 = $database->execute("SELECT LAST_INSERT_ID() insert_id");
											if($result1->num_rows){
												while ($row = $result1->fetch_object()){
													$parentID1 = $row->insert_id;
												}
									
											}
									}
									
									if($CriteriaID == 1){ //QUANTITY
										$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ValueID,
															CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
															VALUES (".$PromoID1.", ".$parentID1.", 3, ".$BrandID.", ".$CriteriaID.", ".$Points.", ".$min_val.", 0, 
															'".$StartDate."', '".$EndDate."')");
									}else{ //AMOUNT
										$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ValueID,
															CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
															VALUES (".$PromoID1.", ".$parentID1.", 3, ".$BrandID.", ".$CriteriaID.", ".$Points.", 0, ".$min_val.", 
															'".$StartDate."', '".$EndDate."')");
									}
									
									//ENTITLEMENT
									
									$entBrand = $database->execute("select tmp.*, p.ID ProductID from uploadloyalenttmp tmp  
																		  inner join product p on tmp.SKU = p.Code where tmp.PromoCode = '".$PromoCode1."'");
									if($entBrand->num_rows){
										while($row2 = $entBrand->fetch_object()){
											$sku    	= $row2->SKU;
											$Points 	= $row2->Points;
											$StartDate 	= $row2->start_date;
											$EndDate	= $row2->end_date;
											$ProductID	= $row2->ProductID;
											$tmpID		= $row2->ID;
											//$parentID
											$database->execute("INSERT INTO loyaltypromoentitlement (LoyaltyBuyinID, ProductID, Points, StartDate, EndDate) 
																values (".$parentID1.", ".$ProductID.", ".$Points.", ".$StartDate.", ".$EndDate.")");	
											$database->execute("delete from uploadloyalenttmp where ID = '".$tmpID."'");
										}
								}
							}
						}
							
							//Product Line
								$PlineQuery = "SELECT lp.ID LoyaltyID, tmp.Pline, tmp.PromoCode, c.ID CriteriaID, tmp.min_val, tmp.Points, tmp.start_date, tmp.end_date FROM uploadloyalbrtmp tmp
											INNER JOIN product p on tmp.Pline = p.ID
											INNER JOIN criteria c ON c.code = tmp.Criteria
											INNER JOIN loyaltypromo lp ON lp.PromoCode = tmp.PromoCode
											WHERE tmp.Pline <> ''";
											
								$ProductLine = $database->execute($PlineQuery);
								if($ProductLine->num_rows){
									while($plrow = $ProductLine->fetch_object()){
										$PromoID1 	= 	$plrow->LoyaltyID;
										$Pline	  	= 	$plrow->Pline;
										$CriteriaID	=   $plrow->CriteriaID;
										$min_val   =	$plrow->min_val;
										$Points   	=	$plrow->Points;
										$StartDate =   $plrow->start_date;
										$EndDate   =	$plrow->end_date;
										$PromoCode1 = 	$plrow->PromoCode;
									$validation = $database->execute("SELECT LoyaltyPromoID from loyaltypromobuyin where LoyaltyPromoID =".$PromoID1);
									if($validation->num_rows){
										while($vtable = $validation->fetch_object()){
											$parentID1 = $vtable->LoyaltyPromoID;
										}
									}else{
										//FOR PARENT ID 
											$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID, ValueID, CriteriaID, 
																Points, MinQty, MinAmt, StartDate, EndDate, LevelType)
																VALUES ($PromoID1, null, null, null, null, null, null, null, null, '$start_date','$end_date', 0)");
											$result1 = $database->execute("SELECT LAST_INSERT_ID() insert_id");
											if($result1->num_rows){
												while ($row = $result1->fetch_object()){
													$parentID1 = $row->insert_id;
												}
											}
									}
									if($CriteriaID == 1){ //QUANTITY
										$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID,
															CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
															VALUES (".$PromoID1.", ".$parentID1.", 2, ".$Pline.", ".$CriteriaID.", ".$Points.", ".$min_val.", 0, 
															'".$StartDate."', '".$EndDate."')");
									}else{ //AMOUNT
										$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID,
															CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
															VALUES (".$PromoID1.", ".$parentID1.", 2, ".$Pline.", ".$CriteriaID.", ".$Points.", 0, ".$min_val.", 
															'".$StartDate."', '".$EndDate."')");
									}
									//ENTITLEMENT
										
										$entProdline = $database->execute("select tmp.*, p.ID ProductID from uploadloyalenttmp tmp  
																		inner join product p on tmp.SKU = p.Code where tmp.PromoCode = '".$PromoCode1."'");
										if($entProdline->num_rows){
											while($row2 = $entProdline->fetch_object()){
												$sku    	= $row2->SKU;
												$Points 	= $row2->Points;
												$StartDate 	= $row2->start_date;
												$EndDate	= $row2->end_date;
												$ProductID	= $row2->ProductID;
												$tmpID		= $row2->ID;
												//$parentID
												$database->execute("INSERT INTO loyaltypromoentitlement (LoyaltyBuyinID, ProductID, Points, StartDate, EndDate) 
																	values (".$parentID1.", ".$ProductID.", ".$Points.", ".$StartDate.", ".$EndDate.")");	
												$database->execute("delete from uploadloyalenttmp where ID = '".$tmpID."'");
											}
										}
										
									}
								}
							
								//FORM
								$FormQuery = "SELECT lp.ID LoyaltyID, v.ID FormID, tmp.Form, tmp.PromoCode, c.ID CriteriaID, tmp.min_val, tmp.Points, tmp.start_date, tmp.end_date 
											  FROM uploadloyalbrtmp tmp
											  INNER JOIN value v ON v.Name = tmp.Form
											  INNER JOIN criteria c ON c.code = tmp.Criteria
											  INNER JOIN loyaltypromo lp ON lp.PromoCode = tmp.PromoCode
											  WHERE tmp.Form <> ''
											  ";
								$FORM = $database->execute($FormQuery);
								if($FORM->num_rows){
									while($Frow = $FORM->fetch_object()){
										$PromoID1 	= 	$Frow->LoyaltyID;
										$FormID	  	= 	$Frow->FormID;
										$CriteriaID	=   $Frow->CriteriaID;
										$min_val   =	$Frow->min_val;
										$Points   	=	$Frow->Points;
										$StartDate =   $Frow->start_date;
										$EndDate   =	$Frow->end_date;
										$PromoCode1 = 	$Frow->PromoCode;
										$validation = $database->execute("SELECT LoyaltyPromoID from loyaltypromobuyin where LoyaltyPromoID =".$PromoID1);
										if($validation->num_rows){
										while($vtable = $validation->fetch_object()){
											$parentID1 = $vtable->LoyaltyPromoID;
										}
										}else{
											//FOR PARENT ID 
												$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID, ValueID, CriteriaID, 
																	Points, MinQty, MinAmt, StartDate, EndDate, LevelType)
																	VALUES ($PromoID1, null, null, null, null, null, null, null, null, '$start_date','$end_date', 0)");
												$result1 = $database->execute("SELECT LAST_INSERT_ID() insert_id");
												if($result1->num_rows){
													while ($row = $result1->fetch_object()){
														$parentID1 = $row->insert_id;
													}
										
												}
										}
										
										if($CriteriaID == 1){ //QUANTITY
										$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ValueID,
															CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
															VALUES (".$PromoID1.", ".$parentID1.", 4, ".$FormID.", ".$CriteriaID.", ".$Points.", ".$min_val.", 0, 
															'".$StartDate."', '".$EndDate."')");
										}else{ //AMOUNT
											$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ValueID,
																CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
																VALUES (".$PromoID1.", ".$parentID1.", 4, ".$FormID.", ".$CriteriaID.", ".$Points.", 0, ".$min_val.", 
																'".$StartDate."', '".$EndDate."')");
										}
										
										$entForm = $database->execute("select tmp.*, p.ID ProductID from uploadloyalenttmp tmp  
																	  inner join product p on tmp.SKU = p.Code where tmp.PromoCode = '".$PromoCode1."'");
										if($entForm->num_rows){
											while($row2 = $entForm->fetch_object()){
												$sku    	= $row2->SKU;
												$Points 	= $row2->Points;
												$StartDate 	= $row2->start_date;
												$EndDate	= $row2->end_date;
												$ProductID	= $row2->ProductID;
												$tmpID		= $row2->ID;
												//$parentID
												$database->execute("INSERT INTO loyaltypromoentitlement (LoyaltyBuyinID, ProductID, Points, StartDate, EndDate) 
																	values (".$parentID1.", ".$ProductID.", ".$Points.", ".$StartDate.", ".$EndDate.")");	
											$database->execute("delete from uploadloyalenttmp where ID = '".$tmpID."'");
											}
										}
										
									}
								}
								/*STYLE*/
								$StyleQuery = "SELECT lp.ID LoyaltyID, tmp.Style, tmp.PromoCode, c.ID CriteriaID, tmp.min_val, tmp.Points, tmp.start_date, tmp.end_date FROM uploadloyalbrtmp tmp
											   INNER JOIN value v ON v.Name = tmp.Style
											   INNER JOIN criteria c ON c.code = tmp.Criteria
											   INNER JOIN loyaltypromo lp ON lp.PromoCode = tmp.PromoCode
											   WHERE tmp.Style <> ''
											   group by v.Name";
								$Style = $database->execute($StyleQuery);
								if($Style->num_rows){
									while($srow = $Style->fetch_object()){
										$PromoID1 	= 	$srow->LoyaltyID;
										$FormID	  	= 	$srow->FormID;
										$CriteriaID	=   $srow->CriteriaID;
										$min_val   =	$srow->min_val;
										$Points   	=	$srow->Points;
										$StartDate =   $srow->start_date;
										$EndDate   =	$srow->end_date;
										$PromoCode1 = 	$srow->PromoCode;
										
										$validation = $database->execute("SELECT LoyaltyPromoID from loyaltypromobuyin where LoyaltyPromoID =".$PromoID1);
										if($validation->num_rows){
										while($vtable = $validation->fetch_object()){
											$parentID1 = $vtable->LoyaltyPromoID;
										}
										}else{
											//FOR PARENT ID 
												$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ProductID, ValueID, CriteriaID, 
																	Points, MinQty, MinAmt, StartDate, EndDate, LevelType)
																	VALUES ($PromoID1, null, null, null, null, null, null, null, null, '$start_date','$end_date', 0)");
												$result1 = $database->execute("SELECT LAST_INSERT_ID() insert_id");
												if($result1->num_rows){
													while ($row = $result1->fetch_object()){
														$parentID1 = $row->insert_id;
													}
										
												}
										}
										
										if($CriteriaID == 1){ //QUANTITY
										$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ValueID,
															CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
															VALUES (".$PromoID1.", ".$parentID1.", 5, ".$FormID.", ".$CriteriaID.", ".$Points.", ".$min_val.", 0, 
															'".$StartDate."', '".$EndDate."')");
										}else{ //AMOUNT
											$database->execute("INSERT INTO loyaltypromobuyin (LoyaltyPromoID, ParentID, SelectionID, ValueID,
																CriteriaID, Points, MinQty, MinAmt, StartDate, EndDate)
																VALUES (".$PromoID1.", ".$parentID1.", 5, ".$FormID.", ".$CriteriaID.", ".$Points.", 0, ".$min_val.", 
																'".$StartDate."', '".$EndDate."')");
										}
										
										$entForm = $database->execute("select tmp.*, p.ID ProductID from uploadloyalenttmp tmp  
																	  inner join product p on tmp.SKU = p.Code where tmp.PromoCode = '".$PromoCode1."'");
										if($entProdline->num_rows){
											while($row2 = $entProdline->fetch_object()){
												$sku    	= $row2->SKU;
												$Points 	= $row2->Points;
												$StartDate 	= $row2->start_date;
												$EndDate	= $row2->end_date;
												$ProductID	= $row2->ProductID;
												$tmpID		= $row2->ID;
												//$parentID
												$database->execute("INSERT INTO loyaltypromoentitlement (LoyaltyBuyinID, ProductID, Points, StartDate, EndDate) 
																	values (".$parentID1.", ".$ProductID.", ".$Points.", ".$StartDate.", ".$EndDate.")");	
												$database->execute("delete from uploadloyalenttmp where ID = '".$tmpID."'");
											}
										}
									}
								}
							
				//	}
				//}
			$message = "Loyalty Promo File Successful";
			$queryLogs = "SELECT PromoCode, CONCAT(REPEAT('0', (7-LENGTH(SKU))), SKU) AS ProductCode FROM uploadloyalenttmp
						  WHERE CONCAT(REPEAT('0', (7-LENGTH(SKU))), SKU) NOT IN (SELECT CODE FROM product)";
			$logs_result = $database->execute($queryLogs);
			
	
		}


	///*PMG*/
	//$PMGQuery = "SELECT tmp.Pmg, tmp.PromoCode, c.ID CriteriaID, tmp.min_val, tmp.Points, tmp.start_date, tmp.end_date FROM uploadloyalbrtmp tmp
	//			   INNER JOIN value v ON v.Name = tmp.Brand
	//			   INNER JOIN criteria c ON c.code = tmp.Criteria
	//			   WHERE tmp.Pmg <> 0";
	//		
	//}					 	   
									
?>