<?php
/*
Author By: Gino Leabres
*/



include CS_PATH.DS."ClassIncentives.php";

/*DropDown*/
$IncentiveType 		  = $tpiIncentives->tpiSelectIncentiveType($database);
$MechanicsType 		  = $tpiIncentives->tpiSelectMechanicsType($database);
$BuyinSelection 	  = $tpiIncentives->tpiSelectProductLevel($database);
$ProductLineSelection = $tpiIncentives->tpiSelectProductLine($database);
$CriteriaSelection 	  = $tpiIncentives->tpiSelectTpiCriteria($database);
$CriteriaSelection1   = $tpiIncentives->tpiSelectTpiCriteria($database);
$today = date('m/d/Y');
//=>
$BuyinSelection1	  = $tpiIncentives->tpiSelectProductLevel($database);
$CriteriaSelection2	  = $tpiIncentives->tpiSelectTpiCriteria($database);
$ProductLineSelection1 = $tpiIncentives->tpiSelectProductLine($database);

if(isset($_POST['btnCancel'])){
	 redirect_to('index.php?pageid=66');
}

if(isset($_POST['btnSave'])){
	redirect_to('index.php?pageid=66');
}



if(isset($_POST['btn_delete1'])){
$xID = $_POST['DbID'];
$database->execute("DELETE FROM incentivespromobuyin where ID = ".$xID);
$database->execute("DELETE FROM incentivespromoentitlement where IncentivesPromoBuyinID = ".$xID);
}

if(isset($_GET['ID'])){	
$xID = $_GET['ID'];
$headerIncentives   = $tpiIncentives->tpiSelectHeaderIncentives($database, $xID);

if($headerIncentives->num_rows){
		while($header = $headerIncentives->fetch_object()){
			$Code			  = $header->Code;
			$Description	  = $header->Description;
			$IncentiveTypeID  = $header->IncentiveTypeID;
			$IncentiveType	  = $header->IncentiveType;
			$MechanicsType	  = $header->MechanicsType;
			$MechanicsTypeID  = $header->MechanicsTypeID;
			$StartDate 		  = date("m/d/Y",strtotime($header->StartDate)); 
			$EndDate		  = date("m/d/Y",strtotime($header->EndDate));
			$IsPlusPlan		  = $header->IsPlusPlan;
			$NonGSU			  = $header->NonGSU;
			$InDirectGSU	  = $header->InDirectGSU;
			$DirectGSU	  	  = $header->DirectGSU;
			$NetOFCPI		  = $header->NetOFCPI;
		}
	}
}
if(isset($_POST['btnBigDelete'])){
	try
	{

		$database->commitTransaction();
		$database->execute("delete from promoincentives where ID = $_GET[ID]");
		$database->execute("delete from incentivespromoavailemnt where ID =$_GET[ID]");
		$promobuyin = $database->execute("select ID from incentivespromobuyin where PromoIncentiveID = $_GET[ID]");
		if($promobuyin->num_rows){
			while($row = $promobuyin->fetch_object()){
				//print_r($row->ID);
				//echo "delete from incentivesspecialcriteria where IBuyinID = $row->ID";
				//echo "delete from incentivespromoentitlement where IncentivesPromoBuyinID = $row->ID";
				
				$database->execute("delete from incentivesspecialcriteria where IBuyinID = $row->ID");
				$database->execute("delete from incentivespromoentitlement where IncentivesPromoBuyinID = $row->ID");
			}
		}
		$database->execute("delete from incentivespromobuyin where PromoIncentiveID = $_GET[ID]");
		
		echo "<script language=JavaScript>
				opener.location.href = '../../index.php?pageid=66&msg=Successfully deleted promo.';
				window.close();
			 </script>";
	}
		
	catch(Exception $e)
	{
		$database->rollbackTransaction();
		$message = $e->getMessage()."\n";
	}
}

if(isset($_POST['btnUpdate1'])){	
	
	try
	{
		$database->beginTransaction();

		$xID				= $_GET['ID'];
		$EntitlementCounter	= $_POST['entitlementCount'];
		$BuyinCount			= $_POST['BuyinCount'];
		$PromoDesc 			= $_POST['txtPromoDesc'];
		$StartDate 			= date("Y-m-d",strtotime($_POST['txtStartDate']));
		$EndDate 			= date("Y-m-d",strtotime($_POST['txtEndDate']));
		$NoCPI 				= 0;
		$NonGSU 			= 0;
		$IndirectGSU 		= 0;
		
		if(trim($_POST['NoCPI'] != "")){
			$NoCPI 	= $_POST['NoCPI'];
		}else{
			$NoCPI 	= 0;
		}
		
		if(trim($_POST['txtNonGSU'] != "")){
			$NonGSU 	= $_POST['txtNonGSU'];
		}else{
			$NonGSU 	= 0;
		}
		
		if(trim($_POST['txtIndirectGSU'] != "")){
			$IndirectGSU 	= $_POST['txtIndirectGSU'];
		}else{
			$IndirectGSU 	= 0;
		}
		//txtDirectGSU
		if(trim($_POST['txtDirectGSU'] != "")){
			$DirectGSU 	= $_POST['txtDirectGSU'];
		}else{
			$DirectGSU 	= 0;
		}
		
		
		if(isset($_POST['chckIsPlus'])){
			$chckIsPlus = 1;
		}else{
			$chckIsPlus 	= 0;
		}

		$database->execute("UPDATE promoincentives set Description = '$PromoDesc', StartDate = '$StartDate', EndDate = '$EndDate', IsPlusPlan = $chckIsPlus where ID = ".$xID);
		$q = $database->execute("select * from incentivespromoavailemnt where PromoIncentiveID = ".$xID);
		if($q->num_rows != 0){
			$database->execute("UPDATE incentivespromoavailemnt set NonGSU = ".$NonGSU.", IndirectGSU = ".$IndirectGSU.", NetOFCPI = ".$NoCPI.", 
																DirectGSU = ".$DirectGSU." where PromoIncentiveID = ".$xID);
		}else{
			$database->execute("INSERT INTO incentivespromoavailemnt (PromoIncentiveID, NonGSU, DirectGSU, IndirectGSU, NetOFCPI)
								VALUES (".$xID.", ".$NonGSU.", ".$DirectGSU.", ".$IndirectGSU.", ".$NoCPI.")");
		}
		
		//buyin
		for($a = 1; $a <= $EntitlementCounter; $a++){	
			//$BCriteria 	= $_POST["BCriteria{$a}"];
			$bID		= "";
			$BMinVal    = "";
			$BCriteria  = "";
			
			if(isset($_POST["BCriteria{$a}"])){
				$BCriteria 	= $_POST["BCriteria{$a}"];
			}
			if(isset($_POST["BMinVal{$a}"])){
				$BMinVal 	= $_POST["BMinVal{$a}"];
			}
			if(isset($_POST["bID{$a}"])){
				$bID   		= $_POST["bID{$a}"]; 
			}
				if($bID != ""){
					if($BCriteria == 1){
						$database->execute("update incentivespromobuyin set CriteriaID = $BCriteria, MinQty = $BMinVal,  MinAmt = 0 where ID = $bID");
					}else{
						$database->execute("update incentivespromobuyin set CriteriaID = $BCriteria, MinQty = 0,  MinAmt = $BMinVal where ID = $bID");
					}	
				}
		};
		
		//entitlement
		for($i = 1; $EntitlementCounter >= $i; $i++){
		$Eentitlement	= $_POST["txtEentitlement{$i}"];	
			$EMinVal   		= $_POST["EMinVal{$i}"];
			$ECriteria 		= $_POST["ECriteria{$i}"];
			$eID			= $_POST["eID{$i}"];
			
			$ForProductID   = $database->execute("SELECT ID FROM product where Code = '".$Eentitlement."'");
			if($ForProductID->num_rows){
				while($xID = $ForProductID->fetch_object()){
					$ProductID = $xID->ID;
	
				}
			}
			
		if($ECriteria == 1){
				$database->execute("update incentivespromoentitlement set ProductID = $ProductID, CriteriaID = $ECriteria, MinQty = $EMinVal,  MinAmt = 0 where ID = $eID");
				}else{
					$database->execute("update incentivespromoentitlement set ProductID = $ProductID, CriteriaID = $ECriteria, MinQty = 0,  MinAmt = $EMinVal where ID = $eID");
				}
		}
		$database->commitTransaction();
		echo"<script language=JavaScript>
				opener.location.href = '../../index.php?pageid=66&msg=Successfully Updated promo.';
				window.close();
			</script>";
	}
	catch(Exception $e)
	{
		$database->rollbackTransaction();
		$message = $e->getMessage()."\n";
		
	}
}

if(isset($_POST['btnUpdate2'])){
	try
	{
		
		$database->beginTransaction();
		$xID				= $_GET['ID'];
		$PromoDesc 			= $_POST['txtPromoDesc'];
		$StartDate 			= date("Y-m-d",strtotime($_POST['txtStartDate']));
		$EndDate 			= date("Y-m-d",strtotime($_POST['txtEndDate']));
		
		$NoCPI 				= 0;
		$NonGSU 			= 0;
		$IndirectGSU 		= 0;
		$NoCPI 	= $_POST['NoCPI'];

		
		if(trim($_POST['txtNonGSU'] != "")){
			$NonGSU 	= $_POST['txtNonGSU'];
		}else{
			$NonGSU 	= 0;
		}
		
		if(trim($_POST['txtIndirectGSU'] != "")){
			$IndirectGSU 	= $_POST['txtIndirectGSU'];
		}else{
			$IndirectGSU 	= 0;
		}
		//txtDirectGSU
		if(trim($_POST['txtDirectGSU'] != "")){
			$DirectGSU 	= $_POST['txtDirectGSU'];
		}else{
			$DirectGSU 	= 0;
		}
		
		if(isset($_POST['chckIsPlus'])){
			$chckIsPlus = 1;
		}else{
			$chckIsPlus 	= 0;
		}
		//echo "UPDATE incentivespromoavailemnt set NonGSU = ".$NonGSU.", IndirectGSU = ".$IndirectGSU.", NetOFCPI = ".$NoCPI.", 
		//														DirectGSU = ".$DirectGSU." where PromoIncentiveID = ".$xID."<br />";
		
		$database->execute("UPDATE promoincentives set Description = '$PromoDesc', StartDate = '$StartDate', EndDate = '$EndDate', IsPlusPlan = $chckIsPlus where ID = ".$xID);
		$q = $database->execute("select * from incentivespromoavailemnt where PromoIncentiveID = ".$xID);
		if($q->num_rows != 0){
			//echo "have record";
			//$database->execute("UPDATE promoincentives set Description = '$PromoDesc', StartDate = '$StartDate', EndDate = '$EndDate', IsPlusPlan = $chckIsPlus where PromoIncentiveID = ".$xID);
			$database->execute("UPDATE incentivespromoavailemnt set NonGSU = ".$NonGSU.", IndirectGSU = ".$IndirectGSU.", NetOFCPI = ".$NoCPI.", 
																DirectGSU = ".$DirectGSU." where PromoIncentiveID = ".$xID);
		}else{
			$database->execute("INSERT INTO incentivespromoavailemnt (PromoIncentiveID, NonGSU, DirectGSU, IndirectGSU, NetOFCPI)
								VALUES (".$xID.", ".$NonGSU.", ".$DirectGSU.", ".$IndirectGSU.", ".$NoCPI.")");
		}
		
		//$database->execute("UPDATE incentivespromoavailemnt set NonGSU = ".$NonGSU.", IndirectGSU = ".$IndirectGSU.", NetOFCPI = ".$NoCPI.", 
		//														DirectGSU = ".$DirectGSU." where PromoIncentiveID = ".$xID);

		for($i=1; $i<=$_POST['BuyinCounterXx']; $i++){
			$BCriteria 	= $_POST["BBCriteria{$i}"];
			$BMinVal   	= $_POST["BBMinVal{$i}"]; 
			$bID   		= $_POST["bbID{$i}"]; 
				if($BCriteria == 1){
					$database->execute("update incentivespromobuyin set CriteriaID = $BCriteria, MinQty = $BMinVal,  MinAmt = 0 where ID = $bID");
				}else{
					$database->execute("update incentivespromobuyin set CriteriaID = $BCriteria, MinQty = 0,  MinAmt = $BMinVal where ID = $bID");
				}
		}
		
		//entitlement
		for($j = 1; $_POST['EntitlementCounterXx'] >= $j; $j++){
			$EMinVal   	= $_POST["EEMinval{$j}"];
			$ECriteria 	= $_POST["EECriteria{$j}"];
			$eID		= $_POST["eeID{$j}"];
			$Eeentitlement	= $_POST["txtEeentitlement{$j}"];
			$ForProductID   = $database->execute("SELECT ID FROM product where Code = '".$Eeentitlement."'");
			if($ForProductID->num_rows){
				while($xID = $ForProductID->fetch_object()){
					$ProductID = $xID->ID;
				}
			}
				//echo "update incentivespromoentitlement set ProductID = $ProductID, CriteriaID = $ECriteria, MinQty = $EMinVal,  MinAmt = 0 where ID = $eID";
				//die();
				if($ECriteria == 1){
					$database->execute("update incentivespromoentitlement set ProductID = $ProductID, CriteriaID = $ECriteria, MinQty = $EMinVal,  MinAmt = 0 where ID = $eID");
				}else{
					$database->execute("update incentivespromoentitlement set ProductID = $ProductID, CriteriaID = $ECriteria, MinQty = 0,  MinAmt = $EMinVal where ID = $eID");
				}
		}
		$database->commitTransaction();
		echo"<script language=JavaScript>
				opener.location.href = '../../index.php?pageid=66&msg=Successfully Updated promo.';
				window.close();
			</script>";
	}
	
	catch(Exception $e)
	{
		$database->rollbackTransaction();
		$message = $e->getMessage()."\n";
		
	}

}

?>