<?php
/*
	Author: @Gino C. Leabres
	Date: 1/25/2013 8:15am
*/
require_once("../initialize.php");
global $database;
		//validation for promocode
		if(isset($_POST['PromoCode'])){
			$PromoCode = $_POST['PromoCode'];	
				$sp = "SELECT PromoCode FROM loyaltypromo where PromoCode = '$PromoCode'";
				$dbresult = $database->execute($sp);
				if($dbresult->num_rows){
					$result = array("message"=> 'true' , "error_msg" => 'Promo Code Already Exist');
				}
				else{
					$result = array("message"=> 'false' , "information" => "Available PromoCode");
		
				}
			die(tpi_JSONencode($result));
		}
		
	//use this code code for Incentives promo and loyalty on future;
	if(isset($_GET['BuyinSelection'])){
		$search = $_GET['term'];
		$StartDate = date("Y-m-d",strtotime($_GET['StartDate']));
		$EndDate =   date("Y-m-d",strtotime($_GET['EndDate']));
		if($_GET['BuyinSelection'] == 4){ //SINGLE LINE
			$q = $database->execute("SELECT * FROM promo WHERE PromoTypeID = 1 AND EndDate >= DATE('".$EndDate."') AND Code LIKE '%".trim($search)."%'");
		}else if ($_GET['BuyinSelection'] == 5){ //Multi Line
			$q = $database->execute("SELECT * FROM promo WHERE PromoTypeID = 2 AND EndDate >= DATE('".$EndDate."') AND Code LIKE '%".trim($search)."%'");
		}else{ // Overlay
			$q = $database->execute("SELECT * FROM promo WHERE PromoTypeID = 3 AND EndDate >= DATE('".$EndDate."') AND Code LIKE '%".trim($search)."%'");
		
		}
		
		
		if($q->num_rows){
			while($r = $q->fetch_array(MYSQLI_ASSOC)){
				$results[] = array('Code'=>$r['Code'], 'Description' => $r['Description']);
			}	
		}else{
			$results[] = array('Code'=>'No Result(s) Display', 'Description' => 'No Result(s) Display');
		}
			 
		die(tpi_JSONencode($results));
	}

	
	if(isset($_GET['term'])){

		$PromoCode = $_GET['term'];
		
		$sp = "SELECT a.ID, a.Code, a.Name FROM product a WHERE CODE LIKE '%$PromoCode%' AND ProductLevelID = 3 LIMIT 10";
		$dbresult = $database->execute($sp);
		if($dbresult->num_rows){
			while($row = $dbresult->fetch_array(MYSQLI_ASSOC)){
				$results[] = array('label' => $row['Code'], 'ProductName' => $row['Name'], 'ProductID' => $row['ID']);
				
			}
			echo tpi_JSONencode($results);
		}else{
		$results[] = array('label' => "No Result(s) Display", 'ProductName' => "No Result(s) Display", 'ProductID' => "No Result(s) Display");
		echo tpi_JSONencode($results);
		}
	}
	
?>