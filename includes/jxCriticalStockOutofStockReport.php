<?php
	/*
		@author: Gino C. Leabres
		@date: 5/28/2013
		@email: ginophp@gmail.com
	*/
	require_once("../initialize.php");
	include CS_PATH.DS.'ClassInventory.php';
	global $database;
	ini_set('max_execution_time', 1000);
	error_reporting(0);
	$type='';
	$num = 0;
	$pageNum = 1;
	$warehouseid =	0;
	$productid	 =	0;
	$pmgid	     =	0;
	$pcode	 =	' ';
	$invstatus	 =	0;
	$qtyfrom	 =	0;
	$qtyto		 =	0;

	
	$offset  = 0;
	$RPP = 10;

	$PrevIc=		"images/bprv.gif";
	$FirstIc=		"images/bfrst.gif";
	$NextIc=		"images/bnxt.gif";
	$LastIc=		"images/blst.gif";
	
	$dPrevIc=		"images/dprv.gif";
	$dFirstIc=		"images/dfrst.gif";
	$dNextIc=		"images/dnxt.gif";
	$dLastIc=		"images/dlst.gif";

	if (isset($_GET["p"])) 	$pageNum    = $_GET["p"];
	if (isset($_GET["p"])) 	$offset		=	$_GET["p"];	
	if (isset($_GET["t"])) 	$type	    = $_GET["t"];
	if (isset($_GET["warehouseid"]))	$warehouseid =	$_GET["warehouseid"];
	if (isset($_GET["productid"]))	    $productid	 =	$_GET["productid"];
	if (isset($_GET["pmgid"]))	        $pmgid	     =	$_GET["pmgid"];
	if (isset($_GET["pcode"]))	    $pcode	 =	$_GET["pcode"];
	if (isset($_GET["invstatus"]))      $invstatus	 =	$_GET["invstatus"];
	if (isset($_GET["qtyfrom"]))        $qtyfrom	 =	$_GET["qtyfrom"];
	if (isset($_GET["qtyto"]))	        $qtyto		 =	$_GET["qtyto"];
	

	$rs_bor = $tpiInventory->spSelectCriticalStockOutofStockReport($database, 2,  0, 0, $warehouseid, $productid, $pmgid, $pcode, $invstatus, $qtyfrom, $qtyto);
	$row = $rs_bor->fetch_object();
	$num = $row->Total;
	
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
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$fromDate."\", \"".$toDate."\")'>$page</a> ";
		}
	}
	
	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1){
		$page  = $pageNum - 1;
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$fromDate."\", \"".$toDate."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$vSearch."\", \"".$fromDate."\", \"".$toDate."\")'  style:'cursor:pointer'> ";
	} else {
		$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
		$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
	}
	
	//These are the button links for next/last enabled/disabled
	if ($pageNum < $maxPage AND $upper <= $maxPage) {
		$page = $pageNum + 1;
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$fromDate."\", \"".$toDate."\")'  >";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$vSearch."\", \"".$fromDate."\", \"".$toDate."\")' >";
	} else {
		$next = " <img border='0' src='$dNextIc'  style:'cursor:pointer'>";
		$last = " <img border='0' src='$dLastIc'  style:'cursor:pointer'>";
	}
	
	if ($maxPage >= 1 AND $type=='pageleft') {
		// print the navigation link
		echo $first . $prev . $nav . $next . $last;
	}elseif ($maxPage>=1 AND $type=='con') {
		//Build the header
/*		echo	"<table border='1' width='70%'>";
					<tr>
						<th width='50%'>Id</th>
						<th>State</th>
					</tr>
				";
*/
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
				<tr align='center' >
					  <td align='left' class='bdiv_r'><strong>Product Line</strong></td>
					  <td   align='left' class='bdiv_r'><strong>Item Code</strong></td>
					  <td align='center' class='bdiv_r '><strong>Item Description</strong></td>
					  <td align='center' class='bdiv_r '><strong>PMG</strong></td>
					  <td align='center' class='bdiv_r '><strong>Campaign Price</strong></td>
					  <td align='center' class='bdiv_r '><strong>SOH</strong></td>
					  <td align='center' class='bdiv_r '><strong>Date Last Sold</strong></td>
					  <td  align='center' class='bdiv_r '><strong>Days not Availed</strong></td>
					  <td  align='center' class='bdiv_r '><strong>Intransit Qty</strong></td>
				    </tr>
					";
		//Get all the rows
		//$query = $sp->spSelectProductExchange($database,$offset, $RPP, $vSearch, $fromDate, $toDate);	
		$query = $tpiInventory->spSelectCriticalStockOutofStockReport($database, 1,  $offset, $RPP, $warehouseid, $productid, 
																	  $pmgid, $pcode, $invstatus, $qtyfrom, $qtyto);
	
		$num = $query->num_rows;
		//Echo each row
		$cnt = 0;
		while($row = $query->fetch_object()) {
			 
				$cnt ++;
				($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
					  
				echo"<tr align='center' class='$alt'>
					  <td align='left' height='30' class='padl5 bdiv_r borderBR'>".$row->ParentCode."</td>
					  <td   align='left' height='30'  class='padl5 bdiv_r borderBR'>".$row->ItemCode."</td>
					  <td align='center' height='30'  class='bdiv_r borderBR'>".$row->ItemDesc."</td>
					  <td align='center' height='30' class='bdiv_r borderBR'>".$row->PMG."</td>
					  <td align='center' height='30' class='bdiv_r borderBR'>".$row->CampaignPrice."</td>
					  <td align='center' height='30' class='bdiv_r borderBR'>".$row->SOH."</td>
					  <td align='center' height='30' class='bdiv_r borderBR'>".$row->DateLastSold."</td>
					  <td  align='center' height='30' class='bdiv_r borderBR'>".$row->DaysNotAvailed."</td>
					  <td  align='center' height='30' class='bdiv_r borderBR'>".$row->InTransit."</td>
				</tr>";
			
		  }


		//Close table
		echo "</table>";

	}elseif ($maxPage >= 1 AND $type == 'pageright') {
		$offsets = $offset + 1;
		$offset2s = $offsets + 9;
		
		echo "Displaying ". $offsets." to  ";
		
			if ( $offset2s <= $num){
				echo $offset2s;
	
			}else{
				echo $num;
			}
		
		echo " (of ".$num." records)" ;
		
	}
	else{
		echo "Table doesn't contain records.";
	}
}else{
	echo "<tr align='center'>
					  <td class='borderBR' colspan='9' height='30' ><span class='txtredsbold'>No record(s) to display.</span></td>
		  </tr>"; 
}
	
	
	
?>