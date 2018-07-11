<?php
	//require_once("../initialize.php");
	global $database;
	if(isset($_POST['btnSave'])){
		try
		{
		$database->beginTransaction();

		//buyin
		$ID = $_GET['id'];
		$PromoTitle		= $_POST['txtPromoTitle'];
		$PReqtType 		= $_POST['PReqtType'];
		
		$BuyinSetStartDate 	= date("Y-m-d",strtotime($_POST['txtBuyinSetStartDate']));
		$BuyinSetEndDate 	= date("Y-m-d",strtotime($_POST['txtBuyinSetEndDate']));
		
		$EntitlemntSetStartDate = date("Y-m-d",strtotime($_POST['txtEntitlemntSetStartDate']));
		$EntitlemntSetEndDate	= date("Y-m-d",strtotime($_POST['txtEntitlemntSetEndDate']));
	

	
	
	
		//Header
		$database->execute("UPDATE loyaltypromo SET PromoTitle = '".$PromoTitle."', prt = ".$PReqtType.", StartDate = '".$BuyinSetStartDate."', 
							EndDate	= '".$BuyinSetEndDate."', LastModifiedDate = NOW(), Changed = 1 WHERE ID = ".$ID);
		//Max Availemnt
		if($_POST['txtNonGSU'] != "" ){
		
				if($_POST['txtNonGSU'] == ""){
					$NonGSU 	= 0;
				}else{
					$NonGSU = $_POST['txtNonGSU'];
				}
				if($_POST['txtDirectGSU'] == ""){
					$DirectGSU   = 0;
				}else{
					$DirectGSU   = $_POST['txtDirectGSU'];
				}
				if($_POST['txtIndirectGSU'] == ""){
					$IndirectGSU = 0;
				}else{
					$IndirectGSU = $_POST['txtIndirectGSU'];
				}
				if($_POST['txtNetOfCPI'] == ""){
					$NetOfCPI = 0;
				}else{
					$NetOfCPI = $_POST['txtNetOfCPI'];
				}
		
			$q = $database->execute("select * from loyaltypromoavailment where LoyaltyPromoID =".$ID);
			if($q->numrows){
				$database->execute("UPDATE loyaltypromoavailment SET NonGSU = ".$NonGSU.", DirectGSU = ".$DirectGSU.", IndirectGSU = ".$IndirectGSU.", NetOfCPI = ".$NetOfCPI.", Changed = 1 WHERE LoyaltyPromoID = ".$ID);
			}else{
				$database->execute("insert into loyaltypromoavailment LoyaltyPromoID, NonGSU, DirectGSU., IndirectGSU, NetOfCPI VALUES 
									(".$ID.",".$NonGSU.",".$DirectGSU.",".$NetOfCPI.")");
			}
		}
	
		$counterbuyin	= $_POST['counterbuyin'];
		for($i = 1; $i <= $counterbuyin; $i++){
			$Bcriteria 	 = $_POST["Bcriteria{$i}"];
			$BMinVal	 = $_POST["BMinVal{$i}"];
			$BPoints 	 = $_POST["BPoints{$i}"];
			if($Bcriteria == 1){
			//number_format($number, 2, '.', '');
				$database->execute('Update loyaltypromobuyin set StartDate = "'.$BuyinSetStartDate.'", EndDate = "'.$BuyinSetEndDate.'", CriteriaID = '.$Bcriteria.', MinQty = '.$BMinVal.', MinAmt = 0, Changed = 1, Points = '.$BPoints.' where LoyaltyPromoID = '.$ID);
			}else{
				$database->execute('Update loyaltypromobuyin set StartDate = "'.$BuyinSetStartDate.'", EndDate = "'.$BuyinSetEndDate.'", CriteriaID = '.$Bcriteria.', MinQty = 0, MinAmt = '.$BMinVal.', Changed = 1, Points = '.$BPoints.' where LoyaltyPromoID = '.$ID);
			}
		}
		
		
		//entitlement
		$entitlementcounter     = $_POST['entitlementcounter'];
		
		for($j=1; $j <= $entitlementcounter; $j++){
		$EProdCode1				= $_POST["EProdCode{$j}"];
		$EPoints                = $_POST["EPoints{$j}"];
		$EeID					= $_POST["EeID{$j}"];
			$ProductID = $database->execute("select ID from product where Code = '".$EProdCode1."'");
			if($ProductID->num_rows){
				while($ptble = $ProductID->fetch_object()){
					$Pid = $ptble->ID;
				}
			}
			$database->execute("Update loyaltypromoentitlement set StartDate = '".$EntitlemntSetStartDate."', EndDate = '".$EntitlemntSetEndDate."', ProductID = ".$Pid.", Points = ".$EPoints." , Changed = 1
			where ID = ".$EeID."");
		}
		
		$database->commitTransaction();
					echo"<script language=JavaScript>
							opener.location.href = '../../index.php?pageid=170&msg=Successfully updated promo.';
							window.close();
						</script>";	
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
		}
		
		
	}
	
	if(isset($_POST['btnDelete'])){
		try{
			$database->beginTransaction();
			$database->execute("DELETE from loyaltypromo where ID = ".$_GET['id']);
			$result = $database->execute("select ID from loyaltypromobuyin where LoyaltyPromoID = ".$_GET['id']." and LevelType = 0");
			if($result->num_rows){
				while($row = $result->fetch_object()){
					$ID = $row->ID;
				}
					$database->execute("delete from loyaltypromoentitlement where LoyaltyBuyinID = ".$ID);
					$database->execute("delete from loyaltypromobuyin where LoyaltyPromoID = ".$_GET['id']);
			}
			$database->commitTransaction();
			echo"<script language=JavaScript>
						opener.location.href = '../../index.php?pageid=170&msg=Successfully updated promo.';
						window.close();
					</script>";	
		}
		catch(exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
		}
	}
	
	
	
	
	$result = $database->execute("SELECT * FROM loyaltypromo where ID = ".$_GET['id']);
	if($result->num_rows){
		while($row = $result->fetch_object()){;
			$PromoCode 	 = $row->PromoCode;
			$prt		 = $row->prt;
			$PromoTitle  = $row->PromoTitle;
			$StartDate   = $row->StartDate;
			$EndDate	 = $row->EndDate;
		}
	}
?>