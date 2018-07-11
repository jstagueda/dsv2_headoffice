<?php 
include "../../../initialize.php";

if(isset($_POST['action'])){
	
	if($_POST['action'] == "GetProduct"){
		
		$SearchProduct = $_POST['SearchProduct'];
		
		$query = $database->execute("SELECT 
									p.ID ProductID,
									TRIM(p.`Code`) ProductCode,
									p.`Name` ProductName
								FROM product p
								INNER JOIN productpricing pp ON pp.ProductID = p.ID
								WHERE p.ProductLevelID = 3
								AND p.StatusID NOT IN (16, 18, 19)
								AND pp.PMGID IN (1,2,3)
								AND (p.`Code` LIKE '%$SearchProduct%' OR p.`Name` LIKE '%$SearchProduct%')
								ORDER BY p.`Name` LIMIT 10");
		if($query->num_rows){
			while($res = $query->fetch_object()){
				$result[] = array("Label" => TRIM($res->ProductCode)." - ".$res->ProductName,
								"Value" => TRIM($res->ProductCode),
								"ID" => $res->ProductID);
			}
		}else{
			$result[] = array("Label" => "No result found.",
							"Value" => "",
							"ID" => 0);
		}
		
		die(json_encode($result));
	}
	
	
	if($_POST['action'] == "AddProduct"){
		
		$html = "";
		$PromoID = $_POST['PromoID'];
		$ProductID = $_POST['ProductID'];
		$counter = $_POST['TotalRow'] + 1;
		
		$query = $database->execute("SELECT ID FROM promobuyin 
									WHERE PromoID = $PromoID
									AND ProductID = $ProductID");
		
		if($query->num_rows){
		
			$promodetailsquery = $database->execute("SELECT
												TRIM(p.Code) ProductCode,
												p.ID ProductID,
												pb.ID PromoBuyinID,
												pe.ID EntitlementID,
												pp.UnitPrice,
												ped.ID EntitlementDetailsID,
												(SELECT MinQty FROM promobuyin WHERE PromoID = pb.PromoID AND ProductID = p.ID) BuyinMinimumQuantity,
												p.Name ProductName,
												ppmg.ID BuyinPMGID,
												ppmg.Code BuyinPMGCode,
												epmg.ID EntitlementPMGID,
												epmg.Code EntitlementPMGCode,
												ped.EffectivePrice SpecialPrice
											FROM promobuyin pb 
											INNER JOIN promoentitlement pe ON pe.PromoBuyinID = pb.ID
											INNER JOIN promoentitlementdetails ped ON ped.PromoEntitlementID = pe.ID
											INNER JOIN product p ON p.ID = ped.ProductID
											INNER JOIN productpricing pp ON pp.ProductID = p.ID
											LEFT JOIN tpi_pmg ppmg ON ppmg.ID = pp.PMGID
											LEFT JOIN tpi_pmg epmg ON epmg.ID = ped.PMGID
											WHERE pb.PromoID = $PromoID
											AND p.ID = $ProductID");
			
		}else{
			
			$promodetailsquery = $database->execute("SELECT
												TRIM(p.Code) ProductCode,
												p.ID ProductID,
												0 PromoBuyinID,
												0 EntitlementID,
												pp.UnitPrice,
												0 EntitlementDetailsID,
												1 BuyinMinimumQuantity,
												p.Name ProductName,
												pmg.ID BuyinPMGID,
												pmg.Code BuyinPMGCode,
												pmg.ID EntitlementPMGID,
												'' EntitlementPMGCode,
												pp.UnitPrice SpecialPrice
											FROM product p
											INNER JOIN productpricing pp ON pp.ProductID = p.ID
											INNER JOIN tpi_pmg pmg ON pmg.ID = pp.PMGID
											WHERE p.ID = $ProductID");
			
		}
		
		if($promodetailsquery->num_rows){
			$res = $promodetailsquery->fetch_object();
			
			$ProductCode = $res->ProductCode;
			$PromoBuyinID = $res->PromoBuyinID;
			$EntitlementID = $res->EntitlementID;
			$UnitPrice = $res->UnitPrice;
			$EntitlementDetailsID = $res->EntitlementDetailsID;
			$BuyinMinimumQuantity = $res->BuyinMinimumQuantity;
			$ProductName = $res->ProductName;
			$BuyinPMGCode = $res->BuyinPMGCode;
			$SpecialPrice = number_format($res->SpecialPrice, 2, '.', '');
			$EntitlementPMGID = $res->EntitlementPMGID;
			
		}
		
		echo '<tr class="trlist">
				<td align="center" width="80px">
					<input type="button" class="btn" name="btnRemove" value="Remove" onclick="return RemoveItem(this);">
				</td>
				<td width="100px" align="center">
					<span>'.$ProductCode.'</span>
					<input type="hidden" value="'.$ProductID.'" name="ProductID'.$counter.'" class="ProductID">
					<input type="hidden" value="'.$PromoBuyinID.'" name="PromoBuyinID'.$counter.'">
					<input type="hidden" value="'.$EntitlementID.'" name="PromoEntitlementID'.$counter.'">
					<input type="hidden" value="'.$UnitPrice.'" name="UnitPrice'.$counter.'">
					<input type="hidden" value="'.$EntitlementDetailsID.'" name="EntitlementDetailsID'.$counter.'">
					<input type="hidden" value="'.$BuyinMinimumQuantity.'" name="BuyinMinimumQuantity'.$counter.'">
				</td>
				<td>
					<span>'.$ProductName.'</span>
				</td>
				<td width="120px" align="center">
					<span>'.$BuyinMinimumQuantity.'</span>
				</td>
				<td align="center" width="120px">
					<span>'.$BuyinPMGCode.'</span>
				</td>
				<td align="center" width="120px">
					<span>Price</span>
				</td>
				<td align="right" width="120px">
					<span>'.number_format($UnitPrice, 2).'</span>
				</td>
				<td width="120px">
					<input type="text" class="txtfield" style="width:100%; text-align:right;" onkeydown="return ValidateField(this);" onkeyup="return ValidateField(this);" value="'.$SpecialPrice.'" name="SpecialPrice'.$counter.'">
				</td>
				<td width="120px">';
					echo '<select name="EntitlementPMGID'.$counter.'" class="txtfield" style="width:100%;">
						<option value="0">Select</option>';
						$pmgquery = $database->execute("SELECT * FROM tpi_pmg WHERE ID IN (1,2,3)");
						if($pmgquery->num_rows){
							while($pmg = $pmgquery->fetch_object()){
								$sel = ($pmg->ID == $res->EntitlementPMGID) ? "selected='selected'" : "";
								echo "<option value='".$pmg->ID."' $sel>".$pmg->Code."</option>";
							}
						}
					echo '</select>
				</td>
			</tr>';
		
		die();
		
	}
	
}

?>