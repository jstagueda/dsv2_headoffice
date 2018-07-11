<?php
	require_once("../initialize.php");
	global $database;
	ini_set('max_execution_time', 500);
	
	$type='';
	$num = 0;
	$pageNum = 1;
	$branchid = 0;
	$campgnid = 0;
	
	$offset  = 0;
	$perpage = 4;
	
	#=Images for the next and back button
	$PrevIc=		"/images/bprv.gif";
	$FirstIc=		"images/bfrst.gif";
	$NextIc=		"images/bnxt.gif";
	$LastIc=		"images/blst.gif";
	
	$dPrevIc=		"images/dprv.gif";
	$dFirstIc=		"images/dfrst.gif";
	$dNextIc=		"images/dnxt.gif";
	$dLastIc=		"images/dlst.gif";
	
	if (isset($_GET["p"])) 	$pageNum	=	$_GET["p"];
	if (isset($_GET["p"])) 	$offset		=	$_GET["p"];
	if (isset($_GET["t"])) 	$type		=	$_GET["t"];
	if (isset($_GET["b"])) 	$branchid	=	$_GET["b"];
	if (isset($_GET["c"])) 	$campgnid	=	$_GET["c"];
		
	//$rs_campaign = $sp->spSelectCampaignByID($database, $campgnid);
	$rs_pcpr = $sp->spSelectBCEProfileReport ($database, 0, 0, $branchid, $campgnid, 2);
	$row = $rs_pcpr->fetch_object();
	$num = $row->Total;
	//$num = $rs_pcpr->num_rows;
	
	
	//############################################
	//If there are some rows then start the pagination
	if ($num<=0) 
	{
		if ($type == 'con')	{
			echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
						<tr align='center'>
							  <td width='14%'	height='20'	align='left'	class='padl5 bdiv_r'><strong>Branch</strong></td>
							  <td width='14%'	height='20' align='left'	class='padl5 bdiv_r'><strong>Campaign</strong></td>
							  <td width='14%'	height='20' align='left'	class='padl5 bdiv_r'><strong>IBM Code</strong></td>
							  <td width='14%'	height='20' align='left'	class='padl5 bdiv_r'><strong>Name</strong></td>
							  <td width='15%'	height='20' align='center'	class='padl5 bdiv_r'><strong>Amount</strong></td>
							  <td width='15%'	height='20' align='center'	class='bdiv_r'><strong>Paid-Up</strong></td>
							  <td width='15%'	height='20' align='center'	class='bdiv_r'><strong>BCE</strong></td>
						</tr>
						<tr align='center'>
						  <td class='borderBR' colspan='13' height='20' ><span class='txtredsbold'>No record(s) to display.</span></td>
						</tr>
						</table>                  
						";
		}		
	}
	else {
		$rs_cpr = $sp->spSelectBCEProfileReport ($database, $offset-1, $perpage, $branchid, $campgnid, 1);
		if ($type == 'con')	{
			echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
								<tr align='center'>
									  <td width='14%'	height='20'	align='left'	class='padl5 bdiv_r'><strong>Branch</strong></td>
									  <td width='14%'	height='20' align='left'	class='padl5 bdiv_r'><strong>Campaign Range</strong></td>
									  <td width='14%'	height='20' align='left'	class='padl5 bdiv_r'><strong>IBM Code</strong></td>
									  <td width='14%'	height='20' align='left'	class='padl5 bdiv_r'><strong>Name</strong></td>
									  <td width='15%'	height='20' align='center'	class='padl5 bdiv_r'><strong>Amount</strong></td>
									  <td width='15%'	height='20' align='center'	class='bdiv_r'><strong>Paid-Up</strong></td>
									  <td width='15%'	height='20' align='center'	class='bdiv_r'><strong>BCE</strong></td>
								</tr>";
									
			while($rowD = $rs_cpr->fetch_object())  {	 
				
				$alt = 'bgEFF0EB';
				
				echo"<tr align='center' class='$alt'>
						<td width='14%'	height='20'	align='left'	class='padl5 bdiv_r'>".$rowD->Branch."</td>
						<td width='14%'	height='20'	align='left'	class='padl5 bdiv_r'>".$rowD->Campaign."</td>
						<td width='14%'	height='20'	align='left'	class='padl5 bdiv_r'>".$rowD->IBMCode."</td>
						<td width='14'	height='20'	align='left'	class='padl5 bdiv_r'>".$rowD->Name."</td>
						<td width='15%'	height='20'	align='right'	class='padl5 bdiv_r'>".$rowD->Amount."</td>
						<td width='15%'	height='20'	align='right'	class='padl5 bdiv_r'>".$rowD->PaidUp."</td>
						<td width='15%'	height='20'	align='right'	class='padl5 bdiv_r'>".$rowD->BCE."</td>
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
					$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$branchid."\", \"".$campgnid."\")'>$page</a> ";
				}
			}
		
			//These are the button links for first/previous enabled/disabled
			if ($pageNum > 1) {
				$page  = $pageNum - 1;		
				
				$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$branchid."\", \"".$campgnid."\");' style:'cursor:pointer'> ";
				$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$branchid."\", \"".$campgnid."\");'  style:'cursor:pointer'> ";
			} else {
				$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
				$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
			}
		
			//These are the button links for next/last enabled/disabled
			//echo "PageNum = ".$pageNum."; MaxPage = ".$maxPage."; Upper = ".$upper;
			if ($pageNum < $maxPage AND $upper <= $maxPage) {
				$page = $pageNum + 1;
				$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$branchid."\", \"".$campgnid."\");'  >";
				$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$branchid."\", \"".$campgnid."\");' >";
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