<?php 

include "../../../initialize.php";
include IN_PATH."pagination.php";
include IN_PATH."ProductInfo.php";

if(isset($_POST['action'])){
	
	//get the pagination of products
	if($_POST['action'] == "ProductPagination"){
		
		$total = 10;
		$page = $_POST['page'];
		$search = $_POST['search'];
		
		echo '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="bordersolo" style="border-top:none;">
				<tr class="trheader">
					<td>Item Code</td>
					<td>item Name</td>
				</tr>';
				$productlist = ProductList($search, $page, $total, false);
				$productlistTotal = ProductList($search, $page, $total, true);
					
				if($productlist->num_rows){
					while($res = $productlist->fetch_object()){
						echo "<tr class='trlist'>
								<td align='center'>
									<a onclick='return GetProductDetails(".$res->ID.")' href='javascript:void(0);' style='color:blue;'>".$res->Code."</a>
								</td>
								<td>".$res->Name."</td>
							</tr>";
					}
				}else{
					echo '<tr class="trlist">
							<td colspan="3" align="center">No result found.</td>
						</tr>';
				}
		echo '</table>';
		
		if($productlistTotal->num_rows){
			echo "<div style='margin-top:10px;'>".AddPagination($total, $productlistTotal->num_rows, $page)."</div>";
		}
		
		die();
	}
	
	//get the product details
	if($_POST['action'] == "GetProductDetails"){
		
		$ProductID = $_POST['ProductID'];
		$productdetails = ProductDetails($ProductID);
		$res = $productdetails->fetch_object();
		
		$result['ProductID'] = $res->ProductID;
		$result['ProductCode'] = $res->ProductCode;
		$result['ProductName'] = $res->ProductName;
		$result['ShortName'] = stripslashes($res->ShortName);
		$result['ItemClass'] = $res->ProductClass;
		$result['ProductType'] = $res->ProductType;
		$result['ProductLine'] = $res->ProductLine;
		$result['UOM'] = $res->UOM;
		$result['UnitCost'] = number_format($res->UnitCost, 2, '.', '');
		$result['UnitPrice'] = number_format($res->UnitPrice, 2, '.', '');
		$result['LaunchDate'] = date("m/d/Y", strtotime($res->LaunchDate));
		$result['LastPODate'] = date("m/d/Y", strtotime($res->LastPODate));
		
		$ifdetails = array(0, 17, 18);
		$productfield = array('SubBrand' => 0,							
							'ItemStyle' => 14,
							'SubForm' => 15,
							'ItemColor' => 16,
							'ItemSize' => 17,
							'ProductLife' => 18);
							
		foreach($productfield as $field => $val){			
			$pdetails = $sp->spSelectProductDMDynamicField($database, $ProductID, $val);
			$p = $pdetails->fetch_object();
			
			if(in_array($val, $ifdetails)){
				$result[$field] = ($p->Details);
			}else{
				$result[$field] = ($p->ValueID);
			}
		}
		
		$detailfield = array('ItemBrand' => 8, 'ItemForm' => 9);
		
		foreach($detailfield as $field => $val){
			$pdetails = $sp->spSelectProductDMDynamicField($database, $ProductID, $val);
			$p = $pdetails->fetch_object();
			
			$productdetailsfield = $database->execute("SELECT * FROM `value` WHERE FieldID = $val AND ID = ".$p->ValueID."");
			if($productdetailsfield->num_rows){
				$pdfield = $productdetailsfield->fetch_object();
				$result[$field] = $pdfield->Name;
			}else{
				$result[$field] = "";
			}
		}
		
		die(json_encode($result));
		
	}
	
	
	//save product details
	if($_POST['action'] == "SaveProductInfo"){
		
		try{
			
			$database->beginTransaction();
			
			$ProductID = $_POST['ProductID'];
			
			$ifdetails = array(0, 17, 18);
			$fielddetails = array('SubBrand' => 0,
							'ItemStyle' => 14,
							'SubForm' => 15,
							'ItemColor' => 16,
							'ItemSize' => 17,
							'ProductLife' => 18);
			
			$database->execute("SET FOREIGN_KEY_CHECKS = 0");
			
			foreach($fielddetails as $field => $val){
			
				$productdetailsquery = $database->execute("SELECT ID FROM productdetails WHERE ProductID = $ProductID AND FieldID = $val");
				if($productdetailsquery->num_rows){
					if(in_array($val, $ifdetails)){
						$database->execute("UPDATE productdetails SET Details = '".$_POST[$field]."', `Changed` = 1 WHERE ProductID = $ProductID AND FieldID = $val");
					}else{
						$database->execute("UPDATE productdetails SET ValueID = '".$_POST[$field]."', `Changed` = 1 WHERE ProductID = $ProductID AND FieldID = $val");
					}
				}else{
					if(in_array($val, $ifdetails)){
						$database->execute("INSERT INTO productdetails SET 
											Details = '".$_POST[$field]."',
											ProductID = $ProductID,
											FieldID = $val,
											EnrollmentDate = NOW(),
											`Changed` = 1");
					}else{
						$database->execute("INSERT INTO productdetails SET 
											ValueID = '".$_POST[$field]."',
											ProductID = $ProductID,
											FieldID = $val,
											EnrollmentDate = NOW(), 
											`Changed` = 1");
					}
				}
				
			}
			
			$database->execute("UPDATE product SET ShortName = '".addslashes($_POST['ShortName'])."' WHERE ID = $ProductID");
			
			$database->commitTransaction();
			$result['ErrorMessage'] = "Product Info successfully updated.";
			
		}catch(Exception $e){
			
			$database->rollbackTransaction();
			$result['ErrorMessage'] = $e->getMessage();
			
		}
		
		die(json_encode($result));
	}
	
}

?>