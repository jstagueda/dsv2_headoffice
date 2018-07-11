<?php
/*
	Author: @Gino C. Leabres
	Date: 2/7/2013 9:33am
*/
require_once("../initialize.php");
global $database;
		//validation
		if(isset($_GET['Code'])){
			$PromoCode = $_GET['Code'];	
			$rs_code_exist = $sp->spCheckPromoIfExists($database, $PromoCode);
				if($rs_code_exist->num_rows){
					echo "true";
				}	
		}
			
		
	
