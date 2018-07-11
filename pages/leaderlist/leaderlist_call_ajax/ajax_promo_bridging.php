<?php

include "../../../initialize.php";
global $database;
//Logic Pagination
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
			$incentivespromoentitlement = $database->execute("SELECT IncentivesPromoBuyinID ID FROM incentivespromoentitlement icpe
							INNER JOIN product p ON icpe.ProductID = p.ID 
							WHERE p.Code LIKE '%".$txtProductCode."%'");

							
			if($incentivespromoentitlement->num_rows){
					while($row = $incentivespromoentitlement->fetch_object()){
						$xxID[] = $row->ID;	
						$id = implode(',',$xxID); 
					}
					
				//$whereID = " and ID in (".$id.") ";
				$BuyinwhereID = " ID in (".$id.") ";
				//geting incentive ID
				$getting_incentives = $database->execute("SELECT PromoIncentiveID ID FROM incentivespromobuyin WHERE ".$BuyinwhereID);
				
				if($getting_incentives->num_rows){
					while($row = $getting_incentives->fetch_object()){
						$xxID[] = $row->ID;	
						$id = implode(',',$xxID); 
					}
				$whereID = " and ID in (".$id.") ";
				}
			}
		}
		
		 $offset = ($pageNum - 1) * $RPP;
		 $q = $database->execute("SELECT * FROM promoincentives WHERE IncentiveTypeID = 5 ".$search." ".$whereID);
		 $num = $q->num_rows;
		 $pagination = AddPagination($RPP, $num, $pageNum);
		 $result['pagination'] = array('page'=>$pagination);
		 
		 $q = "select * from promoincentives WHERE IncentiveTypeID = 5  ".$search." ".$whereID." order by ID desc limit ".$offset.", ".$RPP;
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