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
				$sp = "SELECT PromoCode FROM tpiloyaltyheader where PromoCode = '$PromoCode'";
				$dbresult = $database->execute($sp);
				if($dbresult->num_rows){
					$result = array("message"=> 'true' , "error_msg" => 'Promo Code Already Exist');
				}
				else{
					$result = array("message"=> 'false' , "information" => "Available PromoCode");
		
				}
			die(tpi_JSONencode($result));
		}
		
			
	if(isset($_GET['term'])){

		$PromoCode = $_GET['term'];
		
		$sp = " SELECT a.Code, a.ID ProductID, a.Name, c.ID PMGID, b.UnitPrice FROM product a 
				INNER JOIN productpricing b ON a.ID = b.ProductID 
				INNER JOIN tpi_pmg c ON b.PMGID = c.ID
				WHERE a.Code LIKE '%$PromoCode%' OR a.Name like '%$PromoCode%' AND a.ProductLevelID = 3 
				ORDER BY a.ID LIMIT 10 ";
			   
		$dbresult = $database->execute($sp);
		if($dbresult->num_rows){
			while($row = $dbresult->fetch_array(MYSQLI_ASSOC)){
				$results[] = array('ProductID'=>$row['ProductID'],'label' => $row['Code'], 'ProductName' => $row['Name'], 'ProductID' => $row['ProductID'], 'PMGID' => $row['PMGID'], 'UnitPrice' => $row['UnitPrice']);
				
			}
		}else{
			$results[] = array('ProductID'=>'No Record(s) Display','label' => 'No Record(s) Display', 'ProductName' => 'No Record(s) Display');
		}
			echo tpi_JSONencode($results);
	}
	
?>