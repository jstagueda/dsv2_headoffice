<?php

include "../../../initialize.php";
global $database;

if(isset($_POST['action'])){
	
	if($_POST['action'] == "GetItems"){
		
		$SearchItem = $_POST['Searched'];
		
		$query = $database->execute("SELECT 
									p.ID ProductID,
									TRIM(p.`Code`) ProductCode,
									p.`Name` ProductName
								FROM product p
								INNER JOIN productpricing pp ON pp.ProductID = p.ID
								WHERE p.ProductLevelID = 3
								AND p.StatusID NOT IN (18, 19)
								AND pp.PMGID IN (1,2,3)
								AND (p.`Code` LIKE '%$SearchItem%' OR p.`Name` LIKE '%$SearchItem%')
								ORDER BY p.`Name` LIMIT 10");
		
		if($query->num_rows){
			while($res = $query->fetch_object()){
				$result[] = array("Label" => $res->ProductCode." - ".$res->ProductName,
									"Value" => $res->ProductCode,
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
												TRIM(pbuy.Code) ProductCode,
												pbuy.ID ProductID,
												pb.ID PromoBuyinID,
												pe.ID EntitlementID,
												pp.UnitPrice,
												ped.ID EntitlementDetailsID,
												pb.MinQty BuyinMinimumQuantity,
												pbuy.Name ProductName,
												ppmg.ID BuyinPMGID,
												ppmg.Code BuyinPMGCode,
												epmg.ID EntitlementPMGID,
												epmg.Code EntitlementPMGCode,
												ped.EffectivePrice SpecialPrice
											FROM product pbuy
											INNER JOIN promobuyin pb ON pb.ProductID = pbuy.ID
											INNER JOIN promoentitlement pe ON pe.PromoBuyinID = pb.ID
											INNER JOIN promoentitlementdetails ped ON ped.PromoEntitlementID = pe.ID
											INNER JOIN product pent ON pent.ID = ped.ProductID
											INNER JOIN productpricing pp ON pp.ProductID = pbuy.ID
											LEFT JOIN tpi_pmg ppmg ON ppmg.ID = pp.PMGID
											LEFT JOIN tpi_pmg epmg ON epmg.ID = ped.PMGID
											WHERE pb.PromoID = $PromoID
											AND pbuy.ID = $ProductID");
			
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
					<input type="text" class="txtfield" style="width:100%; text-align:right;" onkeydown="return RemoveInvalidChars(this);" onkeyup="return RemoveInvalidChars(this);" value="'.$SpecialPrice.'" name="SpecialPrice'.$counter.'">
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

function AddPagination($RPP, $num, $pageNum)
{
	$PrevIc=		"images/bprv.gif";
	$FirstIc=		"images/bfrst.gif";
	$NextIc=		"images/bnxt.gif";
	$LastIc=		"images/blst.gif";
	
	$dPrevIc=		"images/dprv.gif";
	$dFirstIc=		"images/dfrst.gif";
	$dNextIc=		"images/dnxt.gif";
	$dLastIc=		"images/dlst.gif";
	
	if ($num > 0) {
		//Determine the maxpage and the offset for the query
		$maxPage = ceil($num/$RPP);
		$offset = ($pageNum - 1) * $RPP;
		//Initiate the navigation bar
		$nav  = '';
		//get low end
		$page = $pageNum-3;
		//get upperbound
		$upper =$pageNum+3;
		if ($page <=0) {
			$page=1;
		}
		if ($upper >$maxPage) {
			$upper =$maxPage;
		}
		
		//Make sure there are 7 numbers (3 before, 3 after and current
		if ($upper-$page <6){
	
			//We know that one of the page has maxed out
			//check which one it is
			//echo "$upper >=$maxPage<br>";
			if ($upper >=$maxPage){
				//the upper end has maxed, put more on the front end
				//echo "to begining<br>";
				$dif =$maxPage-$page;
				//echo "$dif<br>";
					if ($dif==3){
						$page=$page-3;
					} elseif ($dif==4){
						$page=$page-2;
					} elseif ($dif==5){
						$page=$page-1;
					}
			} elseif ($page <=1) {
				//its the low end, add to upper end
				//echo "to upper<br>";
				$dif =$upper-1;
	
				if ($dif==3){
					$upper=$upper+3;
				}elseif ($dif==4){
					$upper=$upper+2;
				}elseif ($dif==5){
					$upper=$upper+1;
				}
			}
		}
		
		if ($page <=0) {
			$page=1;
		}
		
		if ($upper > $maxPage) {
			$upper = $maxPage;
		}
		
		//These are the numbered links
		for($page; $page <=  $upper; $page++) {
	
			if ($page == $pageNum){
				//If this is the current page then disable the link
				$nav .= " <font color='red'>$page</font> ";
			} else {
				//If this is a different page then link to it
				$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\" )'>$page</a> ";
			}
		}
		
		
		//These are the button links for first/previous enabled/disabled
		if ($pageNum > 1){
			$page  = $pageNum - 1;
			$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\")' style:'cursor:pointer'> ";
			$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\")'  style:'cursor:pointer'> ";
		} else {
			$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
			$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
		}
		
		//These are the button links for next/last enabled/disabled
		if ($pageNum < $maxPage AND $upper <= $maxPage) {
			$page = $pageNum + 1;
			$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\")'  >";
			$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\")' >";
		} else {
			$next = " <img border='0' src='$dNextIc'  style:'cursor:pointer'>";
			$last = " <img border='0' src='$dLastIc'  style:'cursor:pointer'>";
		}
		
		if ($maxPage >= 1) {
			// print the navigation link
			return  $first . $prev . $nav . $next . $last;
		}
	}
}

	if($_POST['request'] == "fetch data"){

		$pageNum    = 1;
		$offset = 0;
		$RPP = 8;
		$search = " ";
		$whereID = " ";
		if(isset($_POST['page'])){
			$pageNum    = $_POST['page'];
			$offset 	= $_POST['page'];
		}
		
		if($_POST['txtPromoCodeDesc'] != ""){
			$search = " And Code like '%".$_POST['txtPromoCodeDesc']."%' ";
		}
		
		if($_POST['txtProductCode'] != ""){
			$txtProductCode = $_POST['txtProductCode'];
			$PromoIncentivesID = $database->execute("SELECT b.ID FROM promobuyin a
													 INNER JOIN promo b ON a.PromoID = b.ID
													 INNER JOIN product c ON a.ProductID = c.ID
													 WHERE c.Code = '".$txtProductCode."' and b.StatusID <> 8
													 GROUP BY b.ID");
			if($PromoIncentivesID->num_rows){
					while($row = $PromoIncentivesID->fetch_object()){
						$xxID[] = $row->ID;	
						$id = implode(',',$xxID); 
					}
				$whereID = " and ID in (".$id.") ";
			}
		}
		
		 $offset = ($pageNum - 1) * $RPP;
		 $q = $database->execute("SELECT * FROM promo WHERE PromoTypeID = 1".$search." ".$whereID);
		 $num = $q->num_rows;
		 $pagination = AddPagination($RPP, $num, $pageNum);
		 $result['pagination'] = array('page'=>$pagination);
		 
		 $q = "select * from promo WHERE PromoTypeID = 1 ".$search." ".$whereID." order by ID desc limit ".$offset.", ".$RPP;
		 $dbr = $database->execute($q);
		
		if($dbr->num_rows){
			while($row = $dbr->fetch_object()){
				 $ID = $row->ID;
				 $Code = $row->Code;
				 $Description = $row->Description;
				 $StartDate = 	date("m/d/Y",strtotime($row->StartDate));
				 $EndDate   = 	date("m/d/Y",strtotime($row->EndDate));
				$result['fetch_data'][] = array('Code'=> $Code,'Description' => $Description, 'StartDate'=>$StartDate, 'EndDate'=>$EndDate, 'ID'=>$ID);
			}
			$result['response']	= 'successs';		
		}else{
			$result['response']	= 'failed';
		}
		die(json_encode($result));
	}
	

	
?>