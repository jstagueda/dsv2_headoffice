<?php
	/*
		@Author: Gino Leabres
		@Date 6/3/2013
	*/
	require_once "../initialize.php";
	global $database;
	
	//Parameters
	$sessionID    = session_id();
	$index 		  = $_GET['index'];
	$productLine  = $_GET['prodLine'];
	$productID 	  = $_GET['productID'];
 	$v_search 	  = $_POST['txtExchangeProdCode'.$index];
	$isAvailable  = 0;	
	$colorID 	  = 0;
	$subformID 	  = 0;
	$styleID 	  = 0;
	$formID 	  = 0;
	$colorfield   = 16;
	$subformfield = 15;
	$stylefield   = 14;
	$formfield 	  = 9;
	
	
	
	/*$rsCheckProductLineParameter = $sp->spCheckProdExchangeParameter($database,$productLine);
	if($rsCheckProductLineParameter->num_rows)
	{
		while($row = $rsCheckProductLineParameter->fetch_object())
		{
			
		}
	}*/
	//getting color subform style and form
	$rsGetProductDetails = $sp->spSelectProductDetailsProdExchange($database,$productID);
	if($rsGetProductDetails->num_rows){
		while($row = $rsGetProductDetails->fetch_object()){
			$colorID = $row->colorID;
			$subformID = $row->subformID;
			$styleID = $row->styleID;
			$formID = $row->formID;
		}
	}
	//i'll check item if have stock on hand
	$rsCheckIfAvailable = $sp->spCheckIfAvailableProdExchange($database,$productID,1);
	if($rsCheckIfAvailable->num_rows){
		while($row = $rsCheckIfAvailable->fetch_object()){
			
			if($row->SOH > 0){
				$isAvailable = 1;
				
			}
		}
	}

	//if available 
	if($isAvailable == 1){
		//run this query
		$rsListProducts= $sp->spSelectListProductsAvailableforExchange($database,$v_search,1 ,$productID ,$colorfield ,$colorID,$subformfield ,$subformID , $stylefield , $styleID ,$formfield,$formID);
		
	}
	else{
		//color 
		$rsListProducts= $sp->spSelectListProductsAvailableforExchange($database,$v_search,2 ,$productID ,$colorfield ,$colorID,$subformfield ,$subformID , $stylefield , $styleID ,$formfield,$formID);
		
		if(!$rsListProducts->num_rows){
			//sub-form
			$rsListProducts= $sp->spSelectListProductsAvailableforExchange($database,$v_search, 3 ,$productID ,$colorfield ,$colorID,$subformfield ,$subformID , $stylefield , $styleID ,$formfield,$formID);
			if(!$rsListProducts->num_rows){
				//style
				$rsListProducts= $sp->spSelectListProductsAvailableforExchange($database,$v_search, 4 ,$productID ,$colorfield ,$colorID,$subformfield ,$subformID , $stylefield , $styleID ,$formfield,$formID);
				if(!$rsListProducts->num_rows){
					//form
					$rsListProducts= $sp->spSelectListProductsAvailableforExchange($database,$v_search, 5 ,$productID ,$colorfield ,$colorID,$subformfield ,$subformID , $stylefield , $styleID ,$formfield,$formID);
				}
			}
		}
	}
	
	
   if($rsListProducts->num_rows){
   echo "<ul> ";
      while($row = $rsListProducts->fetch_object()){
         	//$outstandingbalance = str_replace(",","",number_format($row->OutstandingBalance,2));
		 	$prodDetails = $row->prodID . "_" . $row->prodCode . "_" .$row->prodName."_".$index;
            echo "<li id='$prodDetails' ><div align='left'><strong>$row->prodCode - $row->prodName </strong></div></li>";

         }
   echo "</ul>";
   }
   else{
   		echo "<ul><li><strong> No Records to Display.</strong></li></ul>";
   }
?>