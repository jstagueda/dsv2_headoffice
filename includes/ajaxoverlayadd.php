<?php
include "../initialize.php";
global $database;
/*
	Author: @Gino Leabres
	Date:	3/6/2013
*/
if(isset($_GET['add'])){
		/*
			[rdoBReqt] 		=> 2    
			[cboPHCriteria] => 1    
			[txtPHMinimum]  => 15    
		  
		*/
	if($_POST['cboRange'] == 1){
		//ALL PRODUCT
		$ProductLevelID	= $_POST['cboRange'];
		$BuyinRequirementType = $_POST['rdoBReqt'];
		
		
		$CriteriaID 	= $_POST['cboCriteria'];
		$Minimum		= $_POST['txtMinimum'];
		$StartDate		= date("Y-m-d",strtotime($_POST['txtSetStartDate']));	
		$EndDate		= date("Y-m-d",strtotime($_POST['txtSetEndDate']));
		
		//SELECT * FROM overlaybuyintmp
		if($CriteriaID == 1){
			$database->execute("INSERT INTO overlaybuyintmp (ProductLevelID, ProductID, CriteriaID, MinQty, MinAmt, StartDate, EndDate, sessionID) 
								VALUES (".$ProductLevelID.", 1, ".$CriteriaID.", ".$Minimum.", 0, '".$StartDate."', '".$EndDate."',".$session->emp_id.")");
		}else{
			$database->execute("INSERT INTO overlaybuyintmp (ProductLevelID, ProductID, CriteriaID, MinQty, MinAmt, StartDate, EndDate, sessionID) 
								VALUES (".$ProductLevelID.", 1, ".$CriteriaID.", 0,".$Minimum.", '".$StartDate."', '".$EndDate."', ".$session->emp_id.")");
		}
		
	}else if($_POST['cboRange'] == 2){
		//PRODUCT LINE
		$ProductLevelID	= $_POST['cboRange'];
		$Pline			= $_POST['pline'];
		
		$BuyinRequirementType = $_POST['rdoBReqt'];
		
		$CriteriaID 	= $_POST['cboCriteria'];
		$Minimum		= $_POST['txtMinimum'];
		$StartDate		= date("Y-m-d",strtotime($_POST['txtSetStartDate']));	
		$EndDate		= date("Y-m-d",strtotime($_POST['txtSetEndDate']));
		
		if($CriteriaID == 1){
			$database->execute("INSERT INTO overlaybuyintmp (ProductLevelID, ProductID, CriteriaID, MinQty, MinAmt, StartDate, EndDate, sessionID) 
								VALUES (".$ProductLevelID.", ".$Pline.", ".$CriteriaID.", ".$Minimum.", 0, '".$StartDate."', '".$EndDate."', ".$session->emp_id.")");
		}else{
			$database->execute("INSERT INTO overlaybuyintmp (ProductLevelID, ProductID, CriteriaID, MinQty, MinAmt, StartDate, EndDate, sessionID) 
								VALUES (".$ProductLevelID.", ".$Pline.", ".$CriteriaID.", 0,".$Minimum.", '".$StartDate."', '".$EndDate."', ".$session->emp_id.")");
		}
		
	}else if($_POST['cboRange'] == 3){
		//PRODUCT CODE
		$ProductLevelID	= $_POST['cboRange'];
		$ProductCode	= $_POST['txtProductCode'];		
		$BuyinRequirementType = $_POST['rdoBReqt'];
		
		$CriteriaID 	= $_POST['cboCriteria'];
		$Minimum		= $_POST['txtMinimum'];
		
		$StartDate		= date("Y-m-d",strtotime($_POST['txtSetStartDate']));	
		$EndDate		= date("Y-m-d",strtotime($_POST['txtSetEndDate']));
		
		
		$Query = $database->execute("SELECT ID from product where Code ='".$ProductCode."'");
		if($Query->num_rows){ while($row = $Query->fetch_object()){ $ProductID = $row->ID; }}
		
		if($CriteriaID == 1){
			$database->execute("INSERT INTO overlaybuyintmp (ProductLevelID, ProductID, CriteriaID, MinQty, MinAmt, StartDate, EndDate, sessionID) 
								VALUES (".$ProductLevelID.", ".$ProductID.", ".$CriteriaID.", ".$Minimum.", 0, '".$StartDate."', '".$EndDate."', ".$session->emp_id.")");
		}else{
			$database->execute("INSERT INTO overlaybuyintmp (ProductLevelID, ProductID, CriteriaID, MinQty, MinAmt, StartDate, EndDate, sessionID) 
								VALUES (".$ProductLevelID.", ".$ProductID.", ".$CriteriaID.", 0,".$Minimum.", '".$StartDate."', '".$EndDate."', ".$session->emp_id.")");
		}
		
	}else if ($_POST['cboRange'] == 4 || $_POST['cboRange'] == 5 || $_POST['cboRange'] == 7){
		//PROMO WITHIN PROMO
		$ProductLevelID		   = $_POST['cboRange'];
		$ProductCode		   = $_POST['txtProductCode'];		
		$BuyinRequirementType  = $_POST['rdoBReqt'];
		$txtPromoCodePromo	   = $_POST['txtPromoCodePromo'];
		$CriteriaID 		   = $_POST['cboCriteria'];
		$Minimum			   = $_POST['txtMinimum'];
		                          
		$StartDate			   = date("Y-m-d",strtotime($_POST['txtSetStartDate']));	
		$EndDate			   = date("Y-m-d",strtotime($_POST['txtSetEndDate']));
		
		if($CriteriaID == 1){
			$database->execute("INSERT INTO overlaybuyintmp (ProductLevelID, PromoCode, CriteriaID, MinQty, MinAmt, StartDate, EndDate, sessionID) 
								VALUES (".$ProductLevelID.", '".$txtPromoCodePromo."', ".$CriteriaID.", ".$Minimum.", 0, '".$StartDate."', '".$EndDate."', ".$session->emp_id.")");
		}else{
			$database->execute("INSERT INTO overlaybuyintmp (ProductLevelID, PromoCode, CriteriaID, MinQty, MinAmt, StartDate, EndDate, sessionID) 
								VALUES (".$ProductLevelID.", '".$txtPromoCodePromo."', ".$CriteriaID.", 0,".$Minimum.", '".$StartDate."', '".$EndDate."', ".$session->emp_id.")");
		}
	}else if ($_POST['cboRange']==6){
		//print_r($_POST);
		//die();
		 $BrochureStart = $_POST['BrochureStart'];
		 $BrochureEnd 	= $_POST['BrochureEnd']; 	                          
		 $StartDate			   = date("Y-m-d",strtotime($_POST['txtSetStartDate']));	
		 $EndDate			   = date("Y-m-d",strtotime($_POST['txtSetEndDate']));
		 $ProductLevelID	   = $_POST['cboRange'];
		 $CriteriaID 		   = $_POST['cboCriteria'];
		 $Minimum                  = $_POST['txtMinimum'];
                 $CollateralType           = $_POST['BrochureType'];
		 
		 if($CriteriaID == 1){
			$database->execute("INSERT INTO overlaybuyintmp (CollateralType,ProductLevelID, sessionID, BrochurePageFrom, BrohurePageTo, StartDate, EndDate, CriteriaID, MinQty, MinAmt) 
								VALUES (".$CollateralType.",".$ProductLevelID.", ".$session->emp_id.", ".$BrochureStart.",".$BrochureEnd.", '".$StartDate."', '".$EndDate."', ".$CriteriaID.", ".$Minimum.", 0)");
		 }else{
			$database->execute("INSERT INTO overlaybuyintmp (CollateralType, ProductLevelID, sessionID, BrochurePageFrom, BrohurePageTo, StartDate, EndDate,CriteriaID, MinQty, MinAmt) 
								VALUES (".$CollateralType.",".$ProductLevelID.", ".$session->emp_id.", ".$BrochureStart.",".$BrochureEnd.", '".$StartDate."', '".$EndDate."',".$CriteriaID.", 0,".$Minimum.")");
		 
		 }						
	
		
	}else{
		die('Error');
	}
	
	$sQuery = $database->execute("SELECT tmp.*, p.Description, ct.Name CollateralType FROM overlaybuyintmp tmp 
									LEFT JOIN product p on p.ID = tmp.ProductID
									LEFT JOIN promo pr on trim(tmp.PromoCode) = pr.Code
                                                                        LEFT join collateraltype ct on ct.ID = tmp.CollateralType
									where tmp.sessionID = ".$session->emp_id."");
	if($sQuery->num_rows){
		while($row = $sQuery->fetch_object()){
			if($row->Description != "" || $row->Description != NULL){
				$ProductName = $row->Description;
			}else if ($row->PromoCode != "" || $row->PromoCode != NULL){
				$ProductName = $row->PromoCode;
			}else{
				$ProductName = $row->CollateralType.": (".$row->BrochurePageFrom." - ".$row->BrohurePageTo.")";
			}
			
			$tmpID 		 = $row->ID;
			$CriteriaID	 = $row->CriteriaID;
			$MinQty		 = $row->MinQty;
			$MinAmt		 = number_format($row->MinAmt,2);
			$StartDate	 = date("m/d/Y",strtotime($row->StartDate));
			$EndDate	 = date("m/d/Y",strtotime($row->EndDate));
			
			$results[] = array('result'=>'Success','ProductName'=>$ProductName, 'CriteriaID' => $CriteriaID, 'MinQty' => $MinQty, 'MinAmt' => $MinAmt, 'StartDate' => $StartDate, 'EndDate' => $EndDate, 'tmpID'=>$tmpID);
		}
		die(json_encode($results));
	}else{
		die(json_encode(array('result'=>'Failed')));
	}
}

if(isset($_GET['save'])){
	try
	{
		if(isset($_POST['selection_no'])){
			if($_POST['rdoBReqt'] == 2){
				$column = "OverlayQty,";
				$overlay_header_selection = $_POST['selection_no'].",";
			}else if ($_POST['rdoBReqt'] == 3){
				$column = "OverlayAmt,";
				$overlay_header_selection = $_POST['selection_no'].",";
			}else{
				$column = "OverlayAmt,OverlayQty,";
				$overlay_header_selection = "0,0,";
			}
		}else{
			$column = "";
			$overlay_header_selection = "";
		}
                /*
                 * <strong>Apply as discount 
                 * <input type="checkbox" id="chkApplyAsDiscount" name="chkApplyAsDiscount" />
                 * </strong>
                 */
		$database->beginTransaction();
		$promocode 	= htmlentities(addslashes($_POST['txtCode']));				
		$promodesc 	= htmlentities(addslashes($_POST['txtDescription']));
		$tmpsdate 	= strtotime($_POST['txtStartDate']);
		$startdate 	= date("Y-m-d", $tmpsdate);
		$tmpedate 	= strtotime($_POST['txtEndDate']);
		$enddate 	= date("Y-m-d", $tmpedate);
		$PReqtType	= $_POST['cboPReqtType'];
		$IsAnyPromo = (isset($_POST['IsAnyPromo'])) ? $_POST['IsAnyPromo'] : 0;
		$TotalPrice = (isset($_POST['TotalPrice'])) ? $_POST['TotalPrice'] : 0;

		if(isset($_POST['chkPlusPlan'])){
				$isplusplan = $_POST['chkPlusPlan'];				
		}else{
				$isplusplan = 0;
		}
                
                if(isset($_POST['chkApplyAsDiscount'])){
                     $chkApplyAsDiscount = 1;
                }else{
                     $chkApplyAsDiscount = 0;
                }
		$pagenum = $_POST['bpage']."-". $_POST['epage'];
		
		$BuyinRequirementType = $_POST['rdoBReqt'];
		$isRegular = $_POST['isRegular'];
		

		//$rs_promoid = $sp->spInsertPromoHeaderOverlay($database, $promocode, $promodesc, $startdate, $enddate, 3, 1, 'null', 'null', $session->emp_id, 0, $isplusplan);

		$database->execute("SET FOREIGN_KEY_CHECKS = 0");
		$database->execute("INSERT INTO `promo` (Code, Description, StartDate, EndDate, PromoTypeID, OverlayType, ".$column." StatusID, IsIncentive, CreatedBy, EnrollmentDate, IsPlusPlan, PageNum, OverlayIsRegular,OverlayApplyAsDiscount, IsAnyPromo, TotalPrice)
							VALUES ('".$promocode."', '".$promodesc."', '".$startdate."', '".$enddate."', 3, ".$BuyinRequirementType.", ".$overlay_header_selection." 6, 0, ".$session->emp_id.", NOW(), $isplusplan, 
							'".$pagenum."',".$isRegular.",".$chkApplyAsDiscount.", $IsAnyPromo, $TotalPrice)");
		
		$rs_promoid = $database->execute("SELECT LAST_INSERT_ID() ID");
		
		if (!$rs_promoid){
			throw new Exception("An error occurred, please contact your system administrator.");
		}
		if($rs_promoid->num_rows){
			while($row = $rs_promoid->fetch_object()){
				$promoID = $row->ID;
			}
			$rs_promoid->close();
		}
		$rs_branch = $sp->spSelectBranch($database, -1, '');
				if($rs_branch->num_rows){
					while($row_branch = $rs_branch->fetch_object()){											
						$sp->spInsertPromoBranchLinking($database, $promoID, $row_branch->ID); 
					}
					$rs_branch->close();										
		}
		//insert to promoavailment table
		$rs_gsutype_save = $sp->spSelectGSUType($database);
		if($rs_gsutype_save->num_rows){
			while($row = $rs_gsutype_save->fetch_object()){
				if(isset($_POST["txtMaxAvail{$row->ID}"])){
					if($_POST["txtMaxAvail{$row->ID}"] != ""){
						$rs_promoavail = $sp->spInsertPromoAvailment($database, $promoID, $row->ID, $_POST["txtMaxAvail{$row->ID}"]);
													
					}else{
						$rs_promoavail = $sp->spInsertPromoAvailment($database, $promoID, $row->ID, 0);
					}

					if (!$rs_promoavail){
							throw new Exception("An error occurred, please contact your system administrator.");
						}
				}					
			}
		$rs_gsutype_save->close();
		}
		
		//insert to promobuyin - parent
		// RCB 20101130: set productlevelid = 3. doesn't have to be 3 but 
		// promo.php needs the parent promobuyin productlevelid
		// column to have a value
		$rs_promobuyin_parent = $sp->spInsertPromoBuyIn($database, $promoID, 'null', 'null', 'null', 'null', 'null', 'null', 3, 'null', $PReqtType, $startdate, $enddate, 0);
		if (!$rs_promobuyin_parent){
			throw new Exception("An error occurred, please contact your system administrator.");
		}
		if($rs_promobuyin_parent->num_rows)
		{
			while($row = $rs_promobuyin_parent->fetch_object())
			{
				$buyinparentID = $row->ID;
			}
			$rs_promobuyin_parent->close();
		}
		$querytmp = $database->execute("select * from overlaybuyintmp where sessionID = ".$session->emp_id);
		if($querytmp->num_rows){
			while($row = $querytmp->fetch_object()){
				if($row->ProductLevelID <= 3){
					if ($row->CriteriaID == 1){
						$rs_promobuyin = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $row->CriteriaID, $row->MinQty, 'null', 'null', 'null', 
																		$row->ProductLevelID, $row->ProductID, $PReqtType, $row->StartDate, $row->EndDate,1);
						if (!$rs_promobuyin){
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					}else{
						$rs_promobuyin = $sp->spInsertPromoBuyIn($database, $promoID, $buyinparentID, $row->CriteriaID, 'null', $row->MinAmt, 'null', 'null', 
																			$row->ProductLevelID, $row->ProductID, $PReqtType, $row->StartDate, $row->EndDate,1);
						if (!$rs_promobuyin){
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					}
				}else if ($row->ProductLevelID == 6){
					//additional for brohure..
					$ProductLevelID   = $row->ProductLevelID;
					$BrochurePageFrom = $row->BrochurePageFrom;
					$BrohurePageTo = $row->BrohurePageTo;
                                        $CollateralType = $row->CollateralType;
					
						$database->execute("INSERT INTO `promobuyin` (CollateralType,PromoID, ParentPromoBuyinID,  ProductLevelID, 
																	  PurchaseRequirementType, StartDate, EndDate, LevelType, Changed, BrochurePageFrom, BrohurePageTo,
																	  Type,MinQty,MinAmt)	
																	  VALUES 
												(".$CollateralType.",".$promoID.", ".$buyinparentID.",".$ProductLevelID.", ".$PReqtType.", '".$row->StartDate."', '".$row->EndDate."', 1, 1,
												".$BrochurePageFrom.", ".$BrohurePageTo.",".$row->CriteriaID.",".$row->MinQty.",".$row->MinAmt.")");
						
						$rs_promobuyin = $database->execute("SELECT LAST_INSERT_ID() ID");
						
						if (!$rs_promobuyin){
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					
					//die('^^');
				}else{
					$PRomoCode = $row->PromoCode;
					$query = $database->execute("select * from promo where Code = '".$PRomoCode."'");
					if($query->num_rows){
						while($r = $query->fetch_object()){
							$PromoWithinPromoID = $r->ID;
						}
					}else{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
					//$PromoWithinPromoID;
					if ($row->CriteriaID == 1){ //Quantity
						$database->execute("INSERT INTO `promobuyin` (PromoWithinPromoID ,PromoID, ParentPromoBuyinID, Type, MinQty, MinAmt, MaxQty, MaxAmt, ProductLevelID, 
																	  PurchaseRequirementType, StartDate, EndDate, LevelType, Changed)
											VALUES 
																	(".$PromoWithinPromoID .",".$promoID.", ".$buyinparentID.", ".$row->CriteriaID.", ".$row->MinQty.", null, 
																	   null, null, ".$row->ProductLevelID.", ".$PReqtType.", '".$row->StartDate."', '".$row->EndDate."', 1, 1)");
						$rs_promobuyin = $database->execute("SELECT LAST_INSERT_ID() ID");
						if (!$rs_promobuyin){
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					}else{ //Amount
							$database->execute("INSERT INTO `promobuyin` (PromoWithinPromoID ,PromoID, ParentPromoBuyinID, Type, MinQty, MinAmt, MaxQty, MaxAmt, ProductLevelID, 
																		  PurchaseRequirementType, StartDate, EndDate, LevelType, Changed)
											VALUES 
																		(".$PromoWithinPromoID.",".$promoID.", ".$buyinparentID.", ".$row->CriteriaID.", null, ".$row->MinAmt.", 
																		null, null, ".$row->ProductLevelID.", ".$PReqtType.", '".$row->StartDate."', '".$row->EndDate."', 1, 1)");
						
						$rs_promobuyin = $database->execute("SELECT LAST_INSERT_ID() ID");
						if (!$rs_promobuyin){
							throw new Exception("An error occurred, please contact your system administrator.");
						}
					}
				}
			}
		}
		$entprod = $_POST['entitlementcnt'];
		$type = $_POST['cboType'];
		
		if ($type == 2){
			$typeqty = $_POST['txtTypeQty'];
		}else{
			$typeqty = $entprod;
		}
		
		$rs_promoid = $sp->spInsertPromoEntitlement($database, $buyinparentID, $type, $typeqty);
		if (!$rs_promoid){
			throw new Exception("An error occurred, please contact your system administrator.");
		}if($rs_promoid->num_rows){
			while($row = $rs_promoid->fetch_object()){
				$entitlementID = $row->ID;
			}
		}
		
		for($i=1; $i <= $entprod; $i++){
			$prodid = $_POST["hEProdID{$i}"];
			$produp = $_POST["hEUnitPrice{$i}"];
			$prodcode = $_POST["txtEProdCode{$i}"];
			$criteria = $_POST["cboECriteria{$i}"];
			$minimum = $_POST["txtEQty{$i}"];
			//$pmg = $_POST["hEpmgid{$i}"];
			$pmg = $_POST["cboEPMG{$i}"];
			
			 if ($prodcode != ""){
				if ($criteria == 1){
					$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, $minimum, 0, $produp, $pmg);
					if (!$rs_promoent_details){
						throw new Exception("An error occurred, please contact your system administrator.");
					}					
				}else{
					$savings = $produp - $minimum;
					$rs_promoent_details = $sp->spInsertPromoEntitlementDetails($database, $entitlementID, $prodid, 1, $minimum, $savings, $pmg);
					if (!$rs_promoent_details){
						throw new Exception("An error occurred, please contact your system administrator.");
					}
				}
			}			
		}
		$database->execute("delete from overlaybuyintmp where sessionID = ".$session->emp_id);
		$database->commitTransaction();
		die(json_encode(array('result' => 'Gino','success' => 'Save Successful')));	
	}
	catch(Exception $e)
	{
		echo $e;
		$database->rollbackTransaction();
		$errmsg = $e->getMessage()."\n";			
	}

}

if(isset($_GET['remove'])){
	$tmpID = $_POST['xtmpID'];
	$database->execute("delete from overlaybuyintmp where ID = ".$tmpID);
	
	
	$sQuery = $database->execute("SELECT tmp.*, p.Description FROM overlaybuyintmp tmp 
										LEFT JOIN product p on p.ID = tmp.ProductID
										LEFT JOIN promo pr on trim(tmp.PromoCode) = pr.Code
										where tmp.sessionID = ".$session->emp_id."");
	if($sQuery->num_rows){
		while($row = $sQuery->fetch_object()){
			if($row->Description != "" || $row->Description != NULL){
				$ProductName = $row->Description;
			}else if ($row->PromoCode != "" || $row->PromoCode != NULL){
				$ProductName = $row->PromoCode;
			}else{
				$ProductName = "Brochure: ".$row->BrochurePageFrom." - ".$row->BrohurePageTo;
			}
			
			$tmpID 		 = $row->ID;
			$CriteriaID	 = $row->CriteriaID;
			$MinQty		 = $row->MinQty;
			$MinAmt		 = number_format($row->MinAmt,2);
			$StartDate	 = date("m/d/Y",strtotime($row->StartDate));
			$EndDate	 = date("m/d/Y",strtotime($row->EndDate));
			
			$results[] = array('result'=>'Success','ProductName'=>$ProductName, 'CriteriaID' => $CriteriaID, 'MinQty' => $MinQty, 'MinAmt' => $MinAmt, 'StartDate' => $StartDate, 'EndDate' => $EndDate, 'tmpID'=>$tmpID);
		}
	}else{
		$results[] = array('result'=>'Failed');
	}
		die(json_encode($results));
}

?>