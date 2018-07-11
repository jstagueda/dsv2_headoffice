<?php
require_once('../initialize.php');
include CS_PATH.DS."ClassIncentives.php";
global $database;
	if($_GET['request'] == 'buyin_add'){
			//data
			$ProductID = "";
			$ProductDesc = "";
			/*Buyin Requirements*/
			$BuyinSelection 	= $_POST['BuyinSelection'];
			
			if($BuyinSelection == 1){
				$tpiSelection 	= $tpiIncentives->tpiSelectAllProduct($database, 1);
				while ($row = $tpiSelection->fetch_object()){
					$ProductDesc 	= str_replace("'", "\'",$row->Name);
					$ProductID 		= $row->ID;
				}
			}else if($BuyinSelection == 2){
				$ID = $_POST['ProdLine'];
				$tpiSelection = $tpiIncentives->tpiSelectProductLineSelected($database, $ID);
				
				while ($row = $tpiSelection->fetch_object()){
					$ProductDesc 	= str_replace("'", "\'",$row->Name);
					$ProductID 		= $row->ID;
				}
			}else if ($BuyinSelection == 3){
				
				$BRItemCode = $_POST['txtBRItemCode'];
				$tpiSelection = $tpiIncentives->tpiSelectProductCode($database, $BRItemCode);
				
				while ($row = $tpiSelection->fetch_object()){
					$ProductDesc 	= str_replace("'", "\'",$row->Name);
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
											values ($BuyinSelection, $PromoID, '$PromoCode', 1, 0, $BRMinVal, '$StartDate', '$EndDate', 1, ".$_SESSION['emp_id'].")");
					}
		}
		
		if($BuyinSelection == 1 || $BuyinSelection == 2 || $BuyinSelection == 3){
			
				$buyincriteria 		= $_POST['buyincriteria'];
				$BRMinVal 			= $_POST['txtBRMinVal'];
				$BuyinSetStartDate 	=  date("Y-m-d",strtotime($_POST['txtBuyinSetStartDate'])); 
				$BuyinSetEndDate 	=  date("Y-m-d",strtotime($_POST['txtBuyinSetEndDate']));
				/*PromoEntitlement*/
				$EProductCode 		= $_POST['txtEPromoCode'];
					$tpiEntitlementtmp = $tpiIncentives->tpiSelectProductCode($database, $EProductCode);
					
					while ($row = $tpiEntitlementtmp->fetch_object()){
						$EProductDesc 	= $row->Name;
						$EProductID 	= $row->ID;
					}
				/* Buyin Requirements Tmp*/
				$InsertIncentivesTmpBuyin = $tpiIncentives->tpiInsertIncentivesTmpBuyin($database, $BuyinSelection, $ProductID, $ProductDesc, $buyincriteria, $BRMinVal, $BuyinSetStartDate, $BuyinSetEndDate, 1, $_SESSION['emp_id']);
		}
		
		
		//$q = $database->execute("select * from tpiincentivesbuyintmp where sessionID = ".$_SESSION['emp_id']);
		$q = $database->execute(
				"SELECT ibt.ID BuyinID, ibt.ProductDesc BuyinProdDesc, ibt.CriteriaID, ibt.MinQty,
					ibt.MinAmt, ibt.StartDate,ibt.EndDate, prm.Code PromoCode, ibt.ProductLevelID
				FROM tpiincentivesbuyintmp ibt
				LEFT JOIN promo prm ON ibt.PromoID = prm.ID
				WHERE ibt.sessionID = ".$_SESSION['emp_id']
		);
		
		if($q->num_rows){
			while($r = $q->fetch_object()){
			
				if($r->BuyinProdDesc == ""){
					$ProductDesc		= $r->PromoCode;
				}else{
					$ProductDesc		= $r->BuyinProdDesc;
				}
				
				$ProductLevelID     = $r->ProductLevelID;
				if($ProductLevelID == 4){
						$ProductLevel = "Single Line - ";
					}else if($ProductLevelID == 5){
						$ProductLevel = "Multi Line - ";
					}else{
						$ProductLevel = "";
				}
				
				//$ProductDesc  =  mysql_real_escape_string($r->ProductDesc);
				$CriteriaID   = $r->CriteriaID;
				$MinQty		  = $r->MinQty;
				$MinAmt		  = $r->MinAmt;
				$StartDate 	  = date("m/d/Y",strtotime($r->StartDate));
				$EndDate 	  = date("m/d/Y",strtotime($r->EndDate));
				$bID 		  = $r->BuyinID;
				
				if($r->CriteriaID == 2){
					$Criteria		= "Quantity";
					$Minimum		= $r->MinQty;
				}else{
					$Criteria		= "Amount";
					$Minimum		= number_format($r->MinAmt,2);
				}
				
				$res['fetch_data'][] = array('ProductDesc'=> $ProductLevel."".$ProductDesc, 'Criteria'=> $Criteria, 'Minimum'=> $Minimum, 'StartDate'=>$StartDate, 'EndDate'=>$EndDate, 'BuyinID'=>$bID);
			}
				$res['validate'] = array('result'=>'true');
				die(json_encode($res));
		}else{
				$res['validate'] = array('result'=>'false');
				die(json_encode($res));
		}
			
			
			
	}
	if($_GET['request'] == 'ent_add'){
		//print_r($_POST);
		//die();
		/*PromoEntitlement*/
			$EProductCode 		= $_POST['txtEPromoCode'];
				$tpiEntitlementtmp = $tpiIncentives->tpiSelectProductCode($database, $EProductCode);
				
				while ($row = $tpiEntitlementtmp->fetch_object()){
					$EProductDesc 	= $row->Name;
					$EProductID 	= $row->ID;
				}
			$EProdDesc 			 = mysql_real_escape_string($_POST['txtEProdDesc']);
			$EMinVal 			 = $_POST['txtEMinVal'];
			$EntitlementCriteria = $_POST['EntitleCriteria'];
			$BuyinSetStartDate 	=  date("Y-m-d",strtotime($_POST['txtStartDate'])); 
			$BuyinSetEndDate 	=  date("Y-m-d",strtotime($_POST['txtStartDate']));
			
			//$tpiIncentives->tpiInsertIncentivesTmpEntitlement($database, $EProductID, $EProductDesc, $EMinVal, $EntitlementCriteria, 1, $BuyinSetStartDate, $BuyinSetEndDate, 0);
			
			if($EntitlementCriteria == 1){
				$query = "Insert into tpiincentivesentltmp (ProductID, ProductDesc, CriteriaID, MinQty, MinAmt , StartDate, EndDate, Changed, sessionID)
						  values (".$EProductID.", '".$EProductDesc."', ".$EntitlementCriteria.", ".$EMinVal.", 0, '".$BuyinSetStartDate."', '".$BuyinSetEndDate."',1, ".$_SESSION['emp_id'].")";
			}else{
				$query = "Insert into tpiincentivesentltmp (ProductID, ProductDesc, CriteriaID, MinQty, MinAmt , StartDate, EndDate, Changed, sessionID)
						  values (".$EProductID.", '".$EProductDesc."', ".$EntitlementCriteria.", 0, ".$EMinVal.", '".$BuyinSetStartDate."', '".$BuyinSetEndDate."',1, ".$_SESSION['emp_id'].")";
			}	
			$database->execute($query);

			//fetch_data
				$q = $database->execute("select * from tpiincentivesentltmp where sessionID = ".$_SESSION['emp_id']);
					if($q->num_rows){
							while($r = $q->fetch_object()){
							
									$ProductDesc  = mysql_real_escape_string($r->ProductDesc);
									$CriteriaID   = $r->CriteriaID;
									$MinQty		  = $r->MinQty;
									$MinAmt		  = $r->MinAmt;
									$StartDate 	  = date("m/d/Y",strtotime($r->StartDate));
									$EndDate 	  = date("m/d/Y",strtotime($r->EndDate));
									$bID 		  = $r->ID;
									
									if($r->CriteriaID == 2){
										$Criteria		= "Price";
										$Minimum		= number_format($r->MinAmt,2);
									}else{
										$Criteria		= "Quantity";
										$Minimum		= $r->MinQty;
									}
									
									$res['fetch_data'][] = array('ProductDesc'=> $ProductDesc, 'Criteria'=> $Criteria, 'Minimum'=> $Minimum, 'StartDate'=>$StartDate, 
																 'EndDate'=>$EndDate, 'BuyinID'=>$bID);
								}
									$res['validate'] = array('result'=>'true');
									die(json_encode($res));
							}else{
									$res['validate'] = array('result'=>'false');
									die(json_encode($res));
							}
				
	}
	
	
	if($_GET['request']=='save_file'){
			try
			{ 	
				$database->beginTransaction();
					$NonGSU		 	= 0;	
					$IndirectGSU 	= 0;
					$session 		= $_SESSION['emp_id'];
					$PromoCode		= $_POST["xPromoCode"];
					$PromoDesc		= $_POST["xPromoDesc"];
					$inctype		= 5;
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
					if($ID->num_rows){ 
						while($row = $ID->fetch_object()){ 
							$IDHeader = $row->insert_id; 
						}
					}
					//promo availment
					$tpiIncentives->InsertIncentivesPromoAvailment($database,$IDHeader,$NoCpi,$NonGSU,$IndirectGSU, $directGSU);
					$incentivesBuyin = $database->execute("select * from tpiincentivesbuyintmp where sessionID = ".$_SESSION['emp_id']);
					

					if($incentivesBuyin->num_rows){
					
						$database->execute("INSERT INTO incentivespromobuyin (PromoIncentiveID, ProductLevelID, ProductID, CriteriaID, MinQty, MinAmt, StartDate, 
											EndDate, TYPE, Qty, ParentID) VALUES (".$IDHeader.",0,0,0,0,0,0,0,0,0,'')");
						$PID = $tpiIncentives->insert_last_id($database);
						if($PID->num_rows){
							while($r = $PID->fetch_object()){
								$ParentID = $r->insert_id;
							}
						}
						while($r = $incentivesBuyin->fetch_object()){
								if($r->PromoID == ""){
									$PromoID = 0;
								}else{
									$PromoID			= $r->PromoID;
								}
								$IDHeader; 
								$xID				= $r->ID;
								$BProductLevelID	= $r->ProductLevelID;
								$BProductID	 	 	= $r->ProductID;
								$BCriteriaID	 	= $r->CriteriaID;
								$BMinQty 	 		= $r->MinQty; 
								$BMinAmt	 		= $r->MinAmt;
								$BStartDate	 		= date("Y-m-d",strtotime($r->StartDate));
								$BEndDate	 		= date("Y-m-d",strtotime($r->EndDate));
								$BType				= $r->Type;
								$BQty				= $r->Qty;
								
							
									$query = "insert into incentivespromobuyin (PromoIncentiveID, ProductLevelID, ProductID, CriteriaID, MinQty,
											MinAmt, StartDate, EndDate, EnrollmentDate, LastModifiedDate, Type, Qty, ParentID,PromoID)
											values (".$IDHeader.", ".$BProductLevelID.", ".$BProductID.", ".$BCriteriaID.", ".$BMinQty.", ".$BMinAmt.",
											'".$BStartDate."', '".$BEndDate."', NOW(), NOW(), ".$BType.", ".$BQty.", ".$ParentID.",".$PromoID.")";
									$result = $database->execute($query);
						}
					}
					
					$incentivesEntitlement = $database->execute("SELECT * FROM tpiincentivesentltmp where sessionID = ".$_SESSION['emp_id']);
					if($incentivesEntitlement->num_rows){
						while($row = $incentivesEntitlement->fetch_object()){
						//print_r($row);
						//die();
							$EProductID	 		= $row->ProductID;
							$ECriteriaID		= $row->CriteriaID;
							$EMinQty 	 		= $row->MinQty; 
							$EMinAmt			= $row->MinAmt;
							$EStartDate 		= date("Y-m-d",strtotime($row->StartDate)); 
							$EEndDate	 		= date("Y-m-d",strtotime($row->EndDate));

							$tpiIncentives->IncentivesInsertEntitlement($database, $ParentID, $EProductID, $ECriteriaID, $EMinQty, $EMinAmt, $EStartDate, $EEndDate);
						}
					}
					$tpiIncentives->DeleteIncentivesEntitlementTmp($database, $session);
					$tpiIncentives->DeleteIncentivesPromoBuyinTmp($database, $session);
					$database->commitTransaction();
					$result = array('success'=> 1, 'result'=>'Save Successfull');
					die(tpi_JSONencode($result));	
			}
			catch(Exception $e)
			{	
				echo $e;
				$database->rollbackTransaction();
				$message = $e->getMessage()."\n";	
				$result = array('failed'=> 0);
				die(tpi_JSONencode($result));	
			}
	}
	
	if($_GET['request'] == 'delete_buyin'){
		
		//echo "delete from tpiincentivesbuyintmp where ID =".$_POST['BuyinID'];
		//die();
		//delete to tmp table
		$database->execute("delete from tpiincentivesbuyintmp where ID =".$_POST['BuyinID']);
		//fetching_data
		$q = $database->execute(
				"SELECT ibt.ID BuyinID, ibt.ProductDesc BuyinProdDesc, ibt.CriteriaID, ibt.MinQty,
					ibt.MinAmt, ibt.StartDate,ibt.EndDate, prm.Code PromoCode, ibt.ProductLevelID
				FROM tpiincentivesbuyintmp ibt
				LEFT JOIN promo prm ON ibt.PromoID = prm.ID
				WHERE ibt.sessionID = ".$_SESSION['emp_id']
		);
		
		if($q->num_rows){
			while($r = $q->fetch_object()){
			
				if($r->BuyinProdDesc == ""){
					$ProductDesc		= $r->PromoCode;
				}else{
					$ProductDesc		= $r->BuyinProdDesc;
				}
				
				$ProductLevelID     = $r->ProductLevelID;
				if($ProductLevelID == 4){
						$ProductLevel = "Single Line - ";
					}else if($ProductLevelID == 5){
						$ProductLevel = "Multi Line - ";
					}else{
						$ProductLevel = "";
				}
				
				//$ProductDesc  =  mysql_real_escape_string($r->ProductDesc);
				$CriteriaID   = $r->CriteriaID;
				$MinQty		  = $r->MinQty;
				$MinAmt		  = $r->MinAmt;
				$StartDate 	  = date("m/d/Y",strtotime($r->StartDate));
				$EndDate 	  = date("m/d/Y",strtotime($r->EndDate));
				$bID 		  = $r->BuyinID;
				
				if($r->CriteriaID == 2){
					$Criteria		= "Quantity";
					$Minimum		= $r->MinQty;
				}else{
					$Criteria		= "Amount";
					$Minimum		= number_format($r->MinAmt,2);
				}
				
				$res['fetch_data'][] = array('ProductDesc'=> $ProductLevel."".$ProductDesc, 'Criteria'=> $Criteria, 'Minimum'=> $Minimum, 'StartDate'=>$StartDate, 'EndDate'=>$EndDate, 'BuyinID'=>$bID);
			}
				$res['validate'] = array('result'=>'true');
				die(json_encode($res));
		}else{
				$res['validate'] = array('result'=>'false');
				die(json_encode($res));
		}

	}

	if($_GET['request'] == 'delete_ent'){
	
		//delete to tmp table
		$database->execute("delete from tpiincentivesentltmp where ID =".$_POST['BuyinID']);
		//fetching_data
			$q = $database->execute("select * from tpiincentivesentltmp where sessionID = ".$_SESSION['emp_id']);
					if($q->num_rows){
							while($r = $q->fetch_object()){
									$ProductDesc  = mysql_real_escape_string($r->ProductDesc);
									$CriteriaID   = $r->CriteriaID;
									$MinQty		  = $r->MinQty;
									$MinAmt		  = $r->MinAmt;
									$StartDate 	  = date("m/d/Y",strtotime($r->StartDate));
									$EndDate 	  = date("m/d/Y",strtotime($r->EndDate));
									$bID 		  = $r->ID;
									
									if($r->CriteriaID == 2){
										$Criteria		= "Price";
										$Minimum		= $r->MinQty;
									}else{
										$Criteria		= "Quantity";
										$Minimum		= $r->MinAmt;
									}
									
									$res['fetch_data'][] = array('ProductDesc'=> $ProductDesc, 'Criteria'=> $Criteria, 'Minimum'=> $Minimum, 'StartDate'=>$StartDate, 
																 'EndDate'=>$EndDate, 'BuyinID'=>$bID);
								}
									$res['validate'] = array('result'=>'true');
									die(json_encode($res));
							}else{
									$res['validate'] = array('result'=>'false');
									die(json_encode($res));
							}
	}
		
		
	
?>