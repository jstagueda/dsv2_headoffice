<?php
require_once("../initialize.php");
	global $database;
	ini_set('max_execution_time', 500);

	$type='';
	$num = 0;
	$pageNum = 1;
	$sbranch	=	0;
	$ebranch	=	0;
	$sdealer	=	0;
	$edealer	=	0;
	$sprod		=	0;
	$eprod		=	0;
	$sid		=	0;
	$eid		=	0;
	$grp        = 0;
	
	$offset  = 0;
	$perpage = 10;

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
	if (isset($_GET["sd"]))	$sdate		=	$_GET["sd"];
	if (isset($_GET["ed"]))	$edate		=	$_GET["ed"];
	if (isset($_GET["sb"]))	$sbranch	=	$_GET["sb"];
	if (isset($_GET["eb"]))	$ebranch	=	$_GET["eb"];
	if (isset($_GET["sdr"])) $sdealer	=	$_GET["sdr"];
	if (isset($_GET["edr"])) $edealer	=	$_GET["edr"];
	if (isset($_GET["sp"]))	$sprod		=	$_GET["sp"];
	if (isset($_GET["ep"]))	$eprod		=	$_GET["ep"];
	if (isset($_GET["si"]))	$sid		=	$_GET["si"];
	if (isset($_GET["ei"]))	$eid		=	$_GET["ei"];
	if (isset($_GET["g"]))	$grp		=	$_GET["g"];			
	
	$rs_bor = $sp->spSelectBackOrderReport($database, 2,  0, 0, $sdate, $edate, $sbranch, $ebranch, $sdealer, $edealer, $sprod, $eprod, $sid, $eid, $grp);
	$row = $rs_bor->fetch_object();
	$num = $row->ctr;


	if ($num<=0) 
	{
		if ($type == 'con')	{
			echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
					<tr align='center' >
					  <td align='left' class='padl5 bdiv_r'><strong>So Number</strong></td>
					  <td   align='left' class='padl5 bdiv_r '><strong>BranchID</strong></td>
					  <td align='center' class='bdiv_r '><strong>SO Date</strong></td>
					  <td align='center' class='bdiv_r '><strong>Dealer Code</strong></td>
					  <td align='center' class='bdiv_r '><strong>Dealer Name</strong></td>
					  <td align='center' class='bdiv_r '><strong>Item Code</strong></td>
					  <td align='center' class='bdiv_r '><strong>Item Description</strong></td>
					  <td  align='center' class='bdiv_r '><strong>Order Qty</strong></td>
					  <td  align='center' class='bdiv_r '><strong>Served Qty</strong></td>
					  <td  align='center' class='bdiv_r '><strong>Back Order Qty</strong></td>
					</tr>
					<tr align='center'>
					  <td class='borderBR' colspan='10' height='30' ><span class='txtredsbold'>No record(s) to display.</span></td>
					</tr>
				</table>                  
						";
		}		
	}
	else {
		//($database, 1,  $offset, $perpage, $sdate, $edate, $sbranch, $ebranch, $sdealer, $edealer, $sprod, $eprod, $sid, $eid, $grp)
		//($database, 1, 0, 10, '2011-01-01', '2011-01-29', 1, 93, 13, 11988, 0001, 7311807, 1, 2451, 1)
		echo "else";
		$rs_cpr = $sp->spSelectBackOrderReport($database, 1,  $offset, $perpage, $sdate, $edate, $sbranch, $ebranch, $sdealer, $edealer, $sprod, $eprod, $sid, $eid, $grp);
		if ($type == 'con')	{
			echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
								<tr align='center' >
								  <td align='left' class='padl5 bdiv_r '><strong>So Number</strong></td>
								  <td   align='left' class='padl5 bdiv_r '><strong>BranchID</strong></td>
								  <td align='center' class='bdiv_r '><strong>SO Date</strong></td>
								  <td align='center' class='bdiv_r '><strong>Dealer Code</strong></td>
								  <td align='center' class='bdiv_r '><strong>Dealer Name</strong></td>
								  <td align='center' class='bdiv_r '><strong>Item Code</strong></td>
								  <td align='center' class='bdiv_r '><strong>Item Description</strong></td>
								  <td  align='center' class='bdiv_r '><strong>Order Qty</strong></td>
								  <td  align='center' class='bdiv_r '><strong>Served Qty</strong></td>
								  <td  align='center' class='bdiv_r '><strong>Back Order Qty</strong></td>
								</tr>";
									
			while($rowD = $rs_cpr->fetch_object())  {	 
				
				$alt = 'bgEFF0EB';
				
				echo"<tr align='center' >
					  <td align='left' class='padl5 bdiv_r borderBR'><strong>".$rowD->ID."</strong></td>
					  <td   align='left' class='padl5 bdiv_r borderBR'><strong>".$rowD->BranchID."</strong></td>
					  <td align='center' class='bdiv_r borderBR'><strong>".$rowD->TxnDate."</strong></td>
					  <td align='center' class='bdiv_r borderBR'><strong>".$rowD->CustomerID."</strong></td>
					  <td align='center' class='bdiv_r borderBR'><strong>".$rowD->Name."</strong></td>
					  <td align='center' class='bdiv_r borderBR'><strong>".$rowD->Code."</strong></td>
					  <td align='center' class='bdiv_r borderBR'><strong>".$rowD->Description."</strong></td>
					  <td  align='center' class='bdiv_r borderBR'><strong>".$rowD->Qty."</strong></td>
					  <td  align='center' class='bdiv_r borderBR'><strong>".$rowD->Served."</strong></td>
					  <td  align='center' class='bdiv_r borderBR'><strong>".$rowD->OutstandingQty."</strong></td>
					</tr>";
		 	 }									
			 
			echo "</table>";
		} else if ($type == 'pageleft') {
			//Determine the maxpage and the offset for the query
			$maxPage = ceil($num/$perpage);		
			$offset = ($pageNum - 1) * $perpage;	
			//Initiate the navigation bar
			$nav  = '';
			//get low end
			$page = $pageNum-3;
			//get upperbound
			$upper =$pageNum+3;
		
			if ($page <=0) {
				$page=1;
			}
		
			if ($upper > $maxPage) {
				$upper = $maxPage;
			}
			//Make sure there are 7 numbers (3 before, 3 after and current
			if ($upper-$page <6) {
				//We know that one of the page has maxed out
				//check which one it is
				//echo "$upper >=$maxPage<br>";
				if ($upper >= $maxPage) {
					//the upper end has maxed, put more on the front end
					//echo "to begining<br>";
					$dif = $maxPage-$page;
					//echo "$dif<br>";
						if ($dif==3) {
							$page=$page-3;
						} elseif ($dif==4) {
							$page=$page-2;
						} elseif ($dif==5) {
							$page=$page-1;
						}
				} elseif ($page <=1) {
					//its the low end, add to upper end
					//echo "to upper<br>";
					$dif =$upper-1;
		
					if ($dif==3) {
						$upper=$upper+3;
					} elseif ($dif==4) {
						$upper=$upper+2;
					} elseif ($dif==5) {
						$upper=$upper+1;
					}
				}
			}
		
			if ($page <=0) {
				$page=1;
			}
			if ($upper >$maxPage) {
				$upper =$maxPage;
			}
		
			//These are the numbered links
			for($page; $page <=  $upper; $page++) 
			{
				if ($page == $pageNum) {
					//If this is the current page then disable the link
					$nav .= " <font color='red'>$page</font> ";
				} else {
					//If this is a different page then link to it
					$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$sdate."\", \"".$edate."\", \"".$sbranch."\", \"".$ebranch."\", \"".$sdealer."\", \"".$edealer."\", \"".$sprod."\", \"".$eprod."\", \"".$sid."\", \"".$eid."\", \"".$grp."\")'>$page</a> ";
				}
			}

			//These are the button links for first/previous enabled/disabled
			if ($pageNum > 1) {
				$page  = $pageNum - 1;		
				
				$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$sdate."\", \"".$edate."\", \"".$sbranch."\", \"".$ebranch."\", \"".$sdealer."\", \"".$edealer."\", \"".$sprod."\", \"".$eprod."\", \"".$sid."\", \"".$eid."\", \"".$grp."\");' style:'cursor:pointer'> ";
				$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$sdate."\", \"".$edate."\", \"".$sbranch."\", \"".$ebranch."\", \"".$sdealer."\", \"".$edealer."\", \"".$sprod."\", \"".$eprod."\", \"".$sid."\", \"".$eid."\", \"".$grp."\");'  style:'cursor:pointer'> ";
			} else {
				$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
				$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
			}
		
			//These are the button links for next/last enabled/disabled
			//echo "PageNum = ".$pageNum."; MaxPage = ".$maxPage."; Upper = ".$upper;
			if ($pageNum < $maxPage AND $upper <= $maxPage) {
				$page = $pageNum + 1;
				$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$sdate."\", \"".$edate."\", \"".$sbranch."\", \"".$ebranch."\", \"".$sdealer."\", \"".$edealer."\", \"".$sprod."\", \"".$eprod."\", \"".$sid."\", \"".$eid."\", \"".$grp."\");'  >";
				$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$sdate."\", \"".$edate."\", \"".$sbranch."\", \"".$ebranch."\", \"".$sdealer."\", \"".$edealer."\", \"".$sprod."\", \"".$eprod."\", \"".$sid."\", \"".$eid."\", \"".$grp."\");' >";
			} else {
				$next = " <img border='0' src='$dNextIc'  style:'cursor:pointer'>";
				$last = " <img border='0' src='$dLastIc'  style:'cursor:pointer'>";
			}
				
			echo $first . $prev . $nav . $next . $last;				
		} else if ($type == 'pageright') {
			$offsets = $offset + 1;
			$offset2s = $offsets + 9;
			
			echo "Displaying ". $offsets." to  ";
			if ( $offset2s <= $num) {
				echo $offset2s;
	
			} else {
				echo $num;
			}
			echo " (of ".$num." records)" ;
		}
		
	}
?>