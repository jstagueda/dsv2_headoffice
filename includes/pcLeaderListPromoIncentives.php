<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
 
/*
	@Author: Gino Leabres
	@Date: 12-20-2013
	@Description: These process used it to Special Promo Uploader..
*/

	if($uType == 11){ //buyin requirements..
		/*
			INCENTIVE PROMO CODE	
			INCENTIVE DESCRIPTION	
			INCENTIVE TYPE 
			MECHANICS TYPE	
			START DATE	
			END DATE	
			Non GSU	Direct GSU	
			Indirect GSU	
			NET OF CPI	
			PRODUCT CODE	
			Amount	
			Quantity
		*/
		$rows = explode("\n", $fileData);
		$session = $_SESSION['emp_id'];
		$TotalRowsInFile = 0;
		$cd_cnt = 0;
		$TotalRowsNotUploaded = 0;
		foreach ($rows as $row){
			$list = explode(',',trim($row));
			// Listing Of Header Here..
			$PromoCode		  = trim($list[0]);
			$PromoDescription = trim($list[1]);
			$IncentiveType    = trim($list[2]);
			$MechanicsType    = trim($list[3]);
			$StartDate        = date("Y-m-d",strtotime(trim($list[4])));
			$EndDate          = date("Y-m-d",strtotime(trim($list[5])));
			$NetofCPI         = trim($list[9]);
			$chkIsPlus         = trim($list[13]);
			
			//Max Availment:
			$NonGSU			  = (trim($list[6])==""?"0":trim($list[6]));
			$DirectGSU        = (trim($list[7])==""?"0":trim($list[7]));
			$IndirectGSU      = (trim($list[8])==""?"0":trim($list[8]));
			
			//Buyin Requirements
			$ProductCode 	 	 = trim($list[10]); //Product Code
			$Quantity  			 = trim($list[12]); //Quantity
			$Amount 			 = trim($list[11]); //Amount
			
			//Entitlement..
			$EntitlementProductCode 	 = trim($list[14]);
			$EntitlementEntilementAmount = trim($list[15]);
			//Check Here If Exist..
			$Checker = $database->execute("select ID PromoID from promoincentives where Code='".$PromoCode."'");
			
			//Check if it is Quantity or Amount..
			if($Quantity > 0){
				$CriteriaID = 1;
			}else{
				$CriteriaID = 2;
			}
			

			
			if($Checker->num_rows > 0){
				$PromoID = $Checker->fetch_object()->PromoID;
				$database->execute("UPDATE promoincentives set  Description = '".$PromoDescription."', IncentiveTypeID = ".$IncentiveType.", MechanicsTypeID = ".$MechanicsType.", 
									StartDate = '".$StartDate."', EndDate = '".$EndDate."', IsPlusPlan = ".$chkIsPlus.", CreatedBy = ".$session." where ID = ".$PromoID);
			}else{
				$qID = $database->execute("SELECT IFNULL(MAX(ID)+1,1) PromoID FROM promoincentives");
				$PromoID = $qID->fetch_object()->PromoID;
				//If Promo Code is Not exist.. Do insert here..
				$database->execute("INSERT INTO promoincentives (ID,Code, Description, IncentiveTypeID, MechanicsTypeID, StartDate, EndDate,
										EnrollmentDate, LastModifiedDate, IsPlusPlan, CreatedBy)
									VALUES ($PromoID, '$PromoCode', '$PromoDescription', $IncentiveType, $MechanicsType, '$StartDate', '$EndDate', NOW(), NOW(), $chkIsPlus, $session)");	
			}
			
			//Check Here If Promo Availment Is Already Exist..
			$CheckerPromoAvailment = $database->execute("SELECT * FROM incentivespromoavailemnt WHERE PromoIncentiveID = ".$PromoID);
			
			if($CheckerPromoAvailment->num_rows>0){
					$database->execute("UPDATE incentivespromoavailemnt set NonGSU = $NonGSU, InDirectGSU =$IndirectGSU, 
										NetOFCPI = $NetofCPI, DirectGSU = $DirectGSU 
										WHERE PromoIncentiveID = ".$PromoID);
			}else{
				$database->execute("INSERT INTO incentivespromoavailemnt (PromoIncentiveID, NonGSU, InDirectGSU, NetOFCPI, DirectGSU) 
									VALUES ($PromoID, $NonGSU, $IndirectGSU, $NetofCPI, $DirectGSU)");
			}
			
			

			
			
			//Buyin Entitlement here..
			
			//Check Product Here..
			$qP = $database->execute("SELECT ProductLevelID,ID ProductID FROM product WHERE `Code`=trim('".$ProductCode."')");
			
			if($qP->num_rows){ // If Exist Proceed..
				while($pp = $qP->fetch_object()){
					$ProductID		 = $pp->ProductID;
					$ProductLevelID  = $pp->ProductLevelID;
				}
				
				//Buyin Check if exist promo buyin..
				//$buyinChecker = $database->execute("select * from incentivespromobuyin where PromoIncentiveID = ".$PromoID." and ProductID =".$ProductID);
				//
				//if($buyinChecker->num_rows > 0){
				//		$database->execute("UPDATE incentivespromobuyin SET CriteriaID = ".$CriteriaID." , 
				//							MinQty = ".$Quantity.", MinAmt = ".$Amount.", StartDate = '".$StartDate."' , EndDate = '".$EndDate."' 
				//							WHERE PromoIncentiveID = ".$PromoID." and ProductID =".$ProductID);
				//		$bq = $database->execute("SELECT ID BuyinID from incentivespromobuyin WHERE PromoIncentiveID = ".$PromoID." and ProductID =".$ProductID);
				//		$BuyinID = $bq->fetch_object()->BuyinID;
				//		$cd_cnt++;
				//		
				//}else{
					$bq = $database->execute("SELECT IFNULL(MAX(ID) + 1 ,1) BuyinID from incentivespromobuyin");
					$BuyinID = $bq->fetch_object()->BuyinID;
					$database->execute("INSERT INTO incentivespromobuyin (ID,PromoIncentiveID,ProductLevelID,ProductID,CriteriaID, MinQty,
					MinAmt,StartDate,EndDate,EnrollmentDate,LastModifiedDate, Type, Qty, SpecialCriteria) 
					VALUES
					(".$BuyinID.",".$PromoID.", ".$ProductLevelID.", ".$ProductID.",".$CriteriaID.", ".$Quantity.", ".$Amount.", '".$StartDate."','".$EndDate."',NOW(),NOW(),0,0,0)");
					$cd_cnt++;
				//}
				
				
				//Entitlement Process Here..
				$EntitlementProductCheck = $database->execute("SELECT ID ProductID FROM product WHERE `Code`='".$EntitlementProductCode."'");
				
				//Check Here If Exists.
				if($EntitlementProductCheck->num_rows){
				
						$EProductID = $EntitlementProductCheck->fetch_object()->ProductID;
						$EntitlementEntilementAmount;
						
						$ee = $database->execute("SELECT * FROM incentivespromoentitlement WHERE ProductID = $EProductID AND IncentivesPromoBuyinID = $BuyinID");
						if($ee->num_rows>0){
							$EntitlementID = $ee->fetch_object()->ID;
							
							$database->execute("UPDATE incentivespromoentitlement SET  MinAmt = $EntitlementEntilementAmount, StartDate = '$StartDate', EndDate = '$EndDate' 
							WHERE ID = ".$EntitlementID);
						}else{
							$database->execute("INSERT INTO incentivespromoentitlement (IncentivesPromoBuyinID, ProductID,CriteriaID,MinQty,MinAmt,StartDate,EndDate,
							EnrollmentDate,LastModifiedDate) VALUES ($BuyinID,$EProductID,2,0,$EntitlementEntilementAmount,'$StartDate','$EndDate',NOW(),NOW())");
						}
						
				}else{
					$TotalRowsNotUploaded++;
					$errmsg .= "Entitlement Product Code - ".$EntitlementProductCode." for Promo Code ".$PromoCode." does not exist in Product master. <br>";
				}
			}else{
				$TotalRowsNotUploaded++;
				$errmsg .= "Promo Buyin Requirement Product Code - ".$ProductCode." for Promo Code ".$PromoCode." does not exist in Product master. <br>";
			}
			$TotalRowsInFile++;
		}
		
		$msgLog.= "<br><br><br>";
		$msgLog.= "Total Rows In File: ".$TotalRowsInFile."<br>";
		$msgLog.= "Total Rows Uploaded: ". $cd_cnt."<br>";
		$msgLog.= "Total Rows Not Uploaded: ".$TotalRowsNotUploaded."<br><br><br>";		
		$LogType   		= "Promo Incentives";
		$Description    = $msgLog;
		$xDate			= "NOW()";
		$database->execute("insert into systemlog (FileName,LogType,Description,xDate) VALUES ('".$path_Info."','".$LogType."','".$Description."',".$xDate.")");	
		
	}

?>