<?php
require_once("../initialize.php");
	ini_set('max_execution_time', 500);
	error_reporting(0);
	$type='';
	$branchid = -1;
	$campaignid = 0;
	$ibmfrom = '';
	$ibmto = '';
	$ibmcode = 0;
	
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
	if (isset($_GET["t"])) 	$type	  = $_GET["t"];
	if (isset($_GET["b"])) 	$branchid = $_GET["b"];
	if (isset($_GET["c"])) 	$campaignid = $_GET["c"];
	if (isset($_GET["pmg"])) 	$ibmcode = $_GET["pmg"];
	if (isset($_GET["ibmf"])) 	$ibmfrom = $_GET["ibmf"];
	if (isset($_GET["ibmt"])) 	$ibmto = $_GET["ibmt"];
	
	

	$rs_pcacount = $sp->spSelectIBMOnlineSalesAndActiveReport ($database, 0, 0, $branchid,  $campaignid, $ibmcode, $ibmfrom, $ibmto,  2);
	$row = $rs_pcacount->fetch_object();
	$num = $row->Total;

	if ($num == 0) {
		if ($type == 'con')	{
			
				echo "    <table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
							<tr align='center' >
							  <td width='10%' align='left' class='padl5 bdiv_r '><strong>Branch</strong></td>
							  <td   align='left' class='padl5 bdiv_r '><strong>IBM Code</strong></td>
							  <td width='10%' align='center' class='bdiv_r '><strong>Campaign Date</strong></td>
							  <td width='10%' align='center' class='bdiv_r '><strong>PMG</strong></td>
							  <td width='10%' align='center' class='bdiv_r '><strong>IBM Name</strong></td>
							  <td width='10%' align='center' class='bdiv_r '><strong>Amt Less CPI</strong></td>
							  <td width='10%' align='center' class='bdiv_r '><strong>Total Billed Amount</strong></td>
							  <td width='5%'  align='center' class='bdiv_r '><strong>Actives</strong></td>
							  <td width='5%'  align='center' class='bdiv_r '><strong>Recruits</strong></td>                     		</tr>
							<tr align='center'>
							  <td class='borderBR' colspan='9' height='30' ><span class='txtredsbold'>No record(s) to display.</span></td>
							</tr>
							</table>                  
							";
			
		}
/*		else {
			echo "No Records";
		}	*/	
	}
	else {
		$rs_pca = $sp->spSelectIBMOnlineSalesAndActiveReport ($database, $offset, $perpage, $branchid, $campaignid, $ibmcode, $ibmfrom, $ibmto,  1);
		if ($type == 'con')	{
			echo "
			<table width='100%' border='0' cellpadding='0' cellspacing='1'  class='tab'>
					<tr align='center' >
							  <td width='10%' align='left' class='padl5 bdiv_r borderBR'><strong>Branch</strong></td>
							  <td   align='left' class='padl5 bdiv_r borderBR'><strong>IBM Code</strong></td>
							  <td width='10%' align='center' class='bdiv_r borderBR'><strong>Campaign Date</strong></td>
							  <td width='10%' align='center' class='bdiv_r borderBR'><strong>PMG</strong></td>
							  <td width='10%' align='center' class='bdiv_r borderBR'><strong>IBM Name</strong></td>
							  <td width='10%' align='center' class='bdiv_r borderBR'><strong>Amt Less CPI</strong></td>
							  <td width='10%' align='center' class='bdiv_r borderBR'><strong>Total Billed Amount</strong></td>
							  <td width='5%'  align='center' class='bdiv_r borderBR'><strong>Actives</strong></td>
							  <td width='5%'  align='center' class='bdiv_r borderBR'><strong>Recruits</strong></td>                     		</tr>	
								";
									
			while($row = $rs_pca->fetch_object())  {	 
				
				$alt = 'bgEFF0EB';
				
				echo"
				<tr align='center' class='$alt'>
				  <td width='10%' align='left' class='padl5 bdiv_r borderBR' height='20'>".$row->Name."</td>
				  <td   align='left' class='padl5 bdiv_r borderBR'>".$row->tpi_IBMCode."</td>
				  <td width='10%' align='center' class='bdiv_r borderBR'>".$row->Code."</td>
				  <td width='10%' align='center' class='bdiv_r borderBR'>".$row->PMG."</td>
				  <td width='10%' align='center' class='bdiv_r borderBR'>".$row->NickName."</td>
				  <td width='10%' align='center' class='bdiv_r borderBR'>".$row->AmtlessCPI."</td>
				  <td width='10%' align='center' class='bdiv_r borderBR'>".$row->totalga."</td>
				  <td width='5%'  align='center' class='bdiv_r borderBR'>".$row->count1."</td>
				  <td width='5%'  align='center' class='bdiv_r borderBR'>".$row->count2."</td>				  
				</tr>";
		 	 }									
			 
			echo "</table>";
		}
		else if ($type == 'pageleft') {
			
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
					$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$branchid."\",\"".$campaignid."\",\"".$ibmcode."\",\"".$ibmfrom."\",\"".$ibmto."\")'>$page</a> ";
				}
			}
		
			//These are the button links for first/previous enabled/disabled
			if ($pageNum > 1) {
				$page  = $pageNum - 1;		
				
				$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$branchid."\",\"".$campaignid."\",\"".$ibmcode."\",\"".$ibmfrom."\",\"".$ibmto."\")' style:'cursor:pointer'> ";
				$first = "<img border='0' src='$FirstIc' onclick='showPage(\"".$page."\", \"".$branchid."\",\"".$campaignid."\",\"".$ibmcode."\",\"".$ibmfrom."\",\"".$ibmto."\")'  style:'cursor:pointer'> ";
			} else {
				$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
				$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
			}
		
			//These are the button links for next/last enabled/disabled
			if ($pageNum < $maxPage AND $upper <= $maxPage) {
				$page = $pageNum + 1;
				$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$branchid."\",\"".$campaignid."\",\"".$ibmcode."\",\"".$ibmfrom."\",\"".$ibmto."\")'  >";
				$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$page."\", \"".$branchid."\",\"".$campaignid."\",\"".$ibmcode."\",\"".$ibmfrom."\",\"".$ibmto."\")' >";
			} else {
				$next = " <img border='0' src='$dNextIc'  style:'cursor:pointer'>";
				$last = " <img border='0' src='$dLastIc'  style:'cursor:pointer'>";
			}
				
			echo $first . $prev . $nav . $next . $last;				
		}
		else if ($type == 'pageright') {
			$offsets = $offset + 1;
			$offset2s = $offsets + 9;
			
			echo "Displaying ". $offsets." to  ";
			if ( $offset2s <= $num) {
				echo $offset2s;
	
			} else {
				echo $num;
			}
			echo " (of ".$num." record(s))" ;
		}
		
	}

		


?>