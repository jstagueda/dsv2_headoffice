<?php
	//error_reporting(E_ALL);
	//ini_set('display_errors','On');

	require_once("../initialize.php");
	global $database;
	ini_set('max_execution_time', 500);

	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$dateToday= date("Y-m-d",$tmpdate);		

	$type='';
	$num = 0;
	$pageNum = 1;	

	$branchid = 0;
	$fromdate = $dateToday;
	$todate = $dateToday;
	
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
	
	if (isset($_GET["p"])) 	$pageNum  = $_GET["p"];
	if (isset($_GET["p"])) 	$offset   = $_GET["p"];
	if (isset($_GET["t"])) 	$type	  = $_GET["t"];
	if (isset($_GET["b"]))  $branchid = $_GET["b"]; //branch
	if (isset($_GET["fd"]) && ($_GET["fd"] == "")) {
		$fromdate = $dateToday; 
	} else if ((isset($_GET["fd"]) && ($_GET["fd"] != ""))) {
		$fromdate = $_GET["fd"] ;
	}
	
	if (isset($_GET["td"]) && ($_GET["td"] == "")) {
		$todate = $dateToday; 
	} else if ((isset($_GET["td"]) && ($_GET["td"] != ""))) {
		$todate = $_GET["td"];
	}


	$rs_pcacount = $sp->spSelectIBDBadDebts($database, 2, 0, 0, $branchid, $fromdate, $todate);
	$row = $rs_pcacount->fetch_object();
	$num = $row->Total;
	



	if ($num == 0) {
		if ($type == 'con')	{
			
				echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
						<tr align='center'>
							  <td width='20%' height='20' align='left' 	 class='padl5 bdiv_r'><strong>IGS</strong></td>
							  <td width='10%' height='20' align='left' 	 class='padl5 bdiv_r'><strong>Name</strong></td>
							  <td width='5%' height='20' align='center'  class='bdiv_r'><strong>Ref No</strong></td>
							  <td width='10%' height='20' align='center' class='bdiv_r'><strong>Effective Date</strong></td>
							  <td width='10%' height='20' align='center' class='bdiv_r'><strong>Due Date</strong></td>
							  <td width='10%' height='20' align='center' class='bdiv_r'><strong>Amount Due</strong></td>
							</tr>
						<tr align='center'>
						  <td class='borderBR' colspan='6' height='20' ><span class='txtredsbold'>No record(s) to display.</span></td>
						</tr>
						</table>
						";
		}
		
	}
	else {

		$rs_pca = $sp->spSelectIBDBadDebts($database, 1, $offset, $perpage, $branchid, $fromdate, $todate); 
		if ($type == 'con')	{
			echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
					<tr align='center'>
						  <td width='10%' height='20' align='left' 	 class='padl5 bdiv_r'><strong>IGS</strong></td>
						  <td  height='20' align='left' 	 class='padl5 bdiv_r'><strong>Name</strong></td>
						  <td width='15%' height='20' align='center'  class='bdiv_r'><strong>Ref No</strong></td>
						  <td width='15%' height='20' align='center' class='bdiv_r'><strong>Effective Date</strong></td>
						  <td width='15%' height='20' align='center' class='bdiv_r'><strong>Due Date</strong></td>
						  <td width='10%' height='20' align='center' class='bdiv_r'><strong>Amount Due</strong></td>
					</tr>";
			$cnt = 0;									
			while($rowD = $rs_pca->fetch_object())  {	 
				
				$cnt ++;
				($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
				echo"<tr align='center' class='$alt'>
						  <td width='10%' height='20' align='left' 	 class='padl5 bdiv_r'>".$rowD->Code."</td>
						  <td height='20' align='left' 	 class='padl5 bdiv_r'>".$rowD->Name."</td>
						  <td width='15%' height='20' align='center'  class='bdiv_r'>".$rowD->DocumentNo."</td>
						  <td width='15%' height='20' align='center' class='bdiv_r'>".$rowD->TxnDate."</td>
						  <td width='15%' height='20' align='center' class='bdiv_r'>".$rowD->EffectivityDate."</td>
						  <td width='10%' height='20' align='center' >".$rowD->OutstandingBalance."</td>
					</tr>";
		 	 }									
			 
		$rs_totaldue = $sp->spSelectIBDBadDebts($database, 3, 0, 0, $branchid, $fromdate, $todate);
		
		while($rowD = $rs_totaldue->fetch_object())  {	 
				
				$alt = 'bgEFF0EB';
				echo"<tr align='center' class='$alt'>
						  <td width='10%' height='20' align='left' 	 ></td>
						  <td  height='20' align='left' 	 ></td>
						  <td width='15%' height='20' align='center' ></td>
						  <td width='15%' height='20' align='center' ></td>
						  <td width='15%' height='20' align='center' class='bdiv_r'><strong>Total Due</strong></td>
						  <td width='10%' height='20' align='center' ><strong>".$rowD->TotalDue."</strong></td>
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
					$nav .= " <a style='cursor:pointer' onclick='showPage(\"".$page."\", \"".$branchid."\", \"".$fromdate."\",\"".$todate."\")'>$page</a> ";
				}
			}
		
			//These are the button links for first/previous enabled/disabled
			if ($pageNum > 1) {
				$page  = $pageNum - 1;		
				
				$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$branchid."\", \"".$fromdate."\",\"".$todate."\");' style:'cursor:pointer'> ";
				$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$branchid."\", \"".$fromdate."\",\"".$todate."\");'  style:'cursor:pointer'> ";
			} else {
				$prev  = "<img border='0' src='$dPrevIc'  style='cursor:pointer'> ";
				$first = "<img border='0' src='$dFirstIc'   style='cursor:pointer'> ";
			}
		
			//These are the button links for next/last enabled/disabled
			if ($pageNum < $maxPage AND $upper <= $maxPage) {
				$page = $pageNum + 1;
				$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$branchid."\", \"".$fromdate."\",\"".$todate."\");'  >";

				$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$branchid."\", \"".$fromdate."\",\"".$todate."\");' >";
			} else {
				$next = " <img border='0' src='$dNextIc'  style='cursor:pointer'>";
				$last = " <img border='0' src='$dLastIc'  style='cursor:pointer'>";
			}
				
			echo $first . $prev . $nav . $next . $last;				
		} else if ($type == 'pageright') {
			$offsets = $offset + 1;
			$offset2s = $offsets + 9;
			
			echo "Displaying ". $offsets." to " ;
			if ( $offset2s <= $num) {
				echo $offset2s;
	
			} else {
				echo $num;
			}
			echo " (of ".$num." records)" ;
		}
		
	}


?>