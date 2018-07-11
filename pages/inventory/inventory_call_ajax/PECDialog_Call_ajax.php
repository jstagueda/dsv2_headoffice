<?php
//PECDialog_Call_ajax
include "../../../initialize.php";
include CS_PATH.DS.'ClassInventory.php';

if($_POST['request'] == "getting available items"){
	//print_r($_POST);
	//die();
	$productID = $_POST['prodID'];

	$isAvailable  = 0;	
	$colorID 	  = 0;
	$subformID 	  = 0;
	$styleID 	  = 0;
	$formID 	  = 0;
	$colorfield   = 16;
	$subformfield = 15;
	$stylefield   = 14;
	$formfield 	  = 9;

	//getting productline ID 
	$prodline = $database->execute("select p.ParentID, pp.UnitPrice from product p inner join productpricing pp on p.ID = pp.ProductID where p.ID =".$productID);
	if($prodline->num_rows){
		
		while($r = $prodline->fetch_object()){
			$parent_id = $r->ParentID;
			$unit_price = $r->UnitPrice;
		}
	
	}

	$rsGetProductDetails = $tpiInventory->spSelectProductDetailsProdExchange($database,$productID);
	if($rsGetProductDetails->num_rows){
		while($row = $rsGetProductDetails->fetch_object()){
			$colorID = $row->colorID;
			$subformID = $row->subformID;
			$styleID = $row->styleID;
			$formID = $row->formID;
		}
	}
	//getting product line
	$get_prodline = $database->execute("select ParentID from product where ID = ".$productID);
	
	if($get_prodline->num_rows){
		while($r = $get_prodline->fetch_object()){
			$productLineID = $row->ParentID;
		}
	}
	
	//color
	$rsListProducts_color = $tpiInventory->spSelectListProductsAvailableforExchange($database,2 ,$productID ,$colorfield ,$colorID,$subformfield ,$subformID , $stylefield , $styleID ,$formfield,$formID, $parent_id, $unit_price);
	if($rsListProducts_color->num_rows){
		while($r = $rsListProducts_color->fetch_object()){
			$result['same_color'][] = array('ProductID'=>$r->prodID, 'prodCode'=>$r->prodCode, 'prodName'=> $r->prodName,'SOH'=>$r->SOH);
		}
		$result['same_color_response'] = 'success';
	}else{
		$result['same_color_response'] = 'failed';
	}
	
	//style
	$rsListProducts_style = $tpiInventory->spSelectListProductsAvailableforExchange($database, 4 ,$productID ,$colorfield ,$colorID,$subformfield ,$subformID , $stylefield , $styleID ,$formfield,$formID, $parent_id, $unit_price);
	if($rsListProducts_style->num_rows){
		while($r = $rsListProducts_style->fetch_object()){
			$result['same_style'][] = array('ProductID'=>$r->prodID, 'prodCode'=>$r->prodCode, 'prodName'=> $r->prodName,'SOH'=>$r->SOH);
		}
		$result['same_style_response'] = 'success';
	}else{
		$result['same_style_response'] = 'failed';
	}
	
	//sub-form
	$rsListProducts_sub_form = $tpiInventory->spSelectListProductsAvailableforExchange($database, 3 ,$productID ,$colorfield ,$colorID,$subformfield ,$subformID , $stylefield , $styleID ,$formfield,$formID, $parent_id, $unit_price);
	if($rsListProducts_sub_form->num_rows){
		while($r = $rsListProducts_sub_form->fetch_object()){
			$result['same_sub_form'][] = array('ProductID'=>$r->prodID, 'prodCode'=>$r->prodCode, 'prodName'=> $r->prodName,'SOH'=>$r->SOH);
		}
		$result['same_same_sub_form_response'] = 'success';
	}else{
		$result['same_same_sub_form_response'] = 'failed';
	}
	
	//form
	$rsListProducts_form = $tpiInventory->spSelectListProductsAvailableforExchange($database, 5,$productID ,$colorfield ,$colorID,$subformfield ,$subformID , $stylefield , $styleID ,$formfield,$formID, $parent_id, $unit_price);
	if($rsListProducts_form->num_rows){
		while($r = $rsListProducts_form->fetch_object()){
			$result['same_form'][] = array('ProductID'=>$r->prodID, 'prodCode'=>$r->prodCode, 'prodName'=> $r->prodName, 'SOH'=>$r->SOH);
		}
		$result['same_form_response'] = 'success';
	}else{
		$result['same_form_response'] = 'failed';
	}
	
	die(json_encode($result));
}

if($_POST['request'] == 'pick available items'){
	$query = $database->execute("select * from product where ID = ".$_POST['prodID']);
	
	if($query->num_rows){
		while($r = $query->fetch_object()){
			$result = array('ProductID'=>$r->ID, 'Code'=>$r->Code, 'Name'=>$r->Name);
		}
		die(json_encode($result));
	}
}
?>