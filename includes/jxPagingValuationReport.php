<?php
require_once("../initialize.php");
include CS_PATH.DS.'ClassInventory.php';
global $database;
ini_set('max_execution_time', 1000);

#=This is the number of rows to display per page (results per page)
$RPP = '10';

#=Images for the next and back button
$PrevIc=		"images/bprv.gif";
$FirstIc=		"images/bfrst.gif";
$NextIc=		"images/bnxt.gif";
$LastIc=		"images/blst.gif";

$dPrevIc=		"images/dprv.gif";
$dFirstIc=		"images/dfrst.gif";
$dNextIc=		"images/dnxt.gif";
$dLastIc=		"images/dlst.gif";

/*
This page will go out to a database, and grab a selection of rows.
Because we dont want to display hundreds of rows at a time we will
create dynamic paging.
*/

//############################################
//First get the page value passed to this page
$pageNum='1';
if (isset($_GET["p"])) 
{
	$pageNum=$_GET["p"];
}

$type='';
if (isset($_GET["t"])) 
{
	$type=$_GET["t"];
}
if (isset($_GET['svalue']))
{
	if($_GET['svalue'] == ""){
		$vSearch  = "";
		$warehouseid = $_GET['wid'];
	}else{
		$vSearch  = $_GET['svalue'];
		$warehouseid = 0;
	}
}


if( isset($_GET['lid']))
{
	if($_GET['lid'] <> ""){
		$location = $_GET['lid'];
	}else{
		$location=0;
	};
}



if(isset($_GET['pmgid']))
{
	if($_GET['pmgid']){
		$pmgid = $_GET['pmgid'];
	}else{
		$pmgid = 0;
	}
}



if(isset($_GET['plid']))
{
	if($_GET['plid'] <> ""){
		$plid=$_GET['plid'];
	}else{
			$plid=0;
	}
}


if(isset($_GET['dteAsOf']))
{
	if($_GET['dteAsOf'] <> ""){
		$dteAsOf = $_GET['dteAsOf'];
	}else{
		$dteAsOf = '01/01/1970';
	}	
}



//############################################
//Now import the settings
//include_once("settings.php");

//############################################
//Connect to the db
//mysql_connect($db_host,$MySqlUN,$MySqlPW);
//@mysql_select_db($database) or die( "Unable to select databases");

//############################################
//Get a quick count of all the rows
//$tmpDate = strtotime($dteAsOf. " +1 day");
$newdate = date("Y-m-d", strtotime($dteAsOf. " +1 day"));	
$query = $tpiInventory->spSelectValuationReportCount($database, $vSearch, $warehouseid,$location,$pmgid,$plid,$newdate);
//$query = $tpiInventory->spSelectValuationReportv1($database, 0, 0, $vSearch, $warehouseid,$location,$pmgid,$plid,$newdate);
$row = $query->fetch_object();
$num = $query->num_rows;

//############################################
//If there are some rows then start the pagination

if ($num>0) 
{
	//Determine the maxpage and the offset for the query
	$maxPage = ceil($num/$RPP);
	$offset = ($pageNum - 1) * $RPP;

	//Initiate the navigation bar
	$nav  = '';

	//get low end
	$page = $pageNum-3;

	//get upperbound
	$upper =$pageNum+3;

	if ($page <=0) 
	{
		$page=1;
	}

	if ($upper > $maxPage) 
	{
		$upper = $maxPage;
	}

	//Make sure there are 7 numbers (3 before, 3 after and current
	if ($upper-$page <6)
	{
		//We know that one of the page has maxed out
		//check which one it is
		//echo "$upper >=$maxPage<br>";
		if ($upper >= $maxPage)
		{
			//the upper end has maxed, put more on the front end
			//echo "to begining<br>";
			$dif = $maxPage-$page;
			//echo "$dif<br>";
				if ($dif==3)
				{
					$page=$page-3;
				}
				elseif ($dif==4)
				{
					$page=$page-2;
				}
				elseif ($dif==5)
				{
					$page=$page-1;
				}
		}
		elseif ($page <=1) 
		{
			//its the low end, add to upper end
			//echo "to upper<br>";
			$dif =$upper-1;

			if ($dif==3)
			{
				$upper=$upper+3;
			}
			elseif ($dif==4)
			{
				$upper=$upper+2;
			}
			elseif ($dif==5)
			{
				$upper=$upper+1;
			}
		}
	}

	if ($page <=0) 
	{
		$page=1;
	}

	if ($upper >$maxPage) 
	{
		$upper =$maxPage;
	}

	//These are the numbered links
	for($page; $page <=  $upper; $page++) 
	{
		if ($page == $pageNum)
		{
			//If this is the current page then disable the link
			$nav .= " <font color='red'>$page</font> ";
		}
		else
		{
			//If this is a different page then link to it
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$warehouseid."\",\"".$location."\",\"".$plid."\",\"".$pmgid."\",\"".$dteAsOf."\")'>$page</a> ";
		}
	}

	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1)
	{
		$page  = $pageNum - 1;		
		
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$warehouseid."\",\"".$location."\",\"".$plid."\",\"".$pmgid."\",\"".$dteAsOf."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$vSearch."\", \"".$warehouseid."\",\"".$location."\",\"".$plid."\",\"".$pmgid."\",\"".$dteAsOf."\")'  style:'cursor:pointer'> ";
	}
	else
	{
		$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
		$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
	}

	//These are the button links for next/last enabled/disabled
	if ($pageNum < $maxPage AND $upper <= $maxPage) {
		$page = $pageNum + 1;
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$warehouseid."\",\"".$location."\",\"".$plid."\",\"".$pmgid."\",\"".$dteAsOf."\")'  >";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$vSearch."\", \"".$warehouseid."\",\"".$location."\",\"".$plid."\",\"".$pmgid."\",\"".$dteAsOf."\")' >";
	} else {
		$next = " <img border='0' src='$dNextIc'  style:'cursor:pointer'>";
		$last = " <img border='0' src='$dLastIc'  style:'cursor:pointer'>";
	}

	if ($maxPage>=1 AND $type=='nav') 
	{
		// print the navigation link
		echo $first . $prev . $nav . $next . $last;
	}
	elseif ($maxPage>=1 AND $type=='con') 
	{
		//Build the header
		echo "<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='tab'>
                    <tr align='center'>
                      <td width='15%' height='20' align='left' class='padl5 bdiv_r'><strong>Product Line</strong></td>
                      <td width='15%' height='20' align='center' class='padl5 bdiv_r'><strong>Item Code</strong></td>
					  <td width='25%' height='20' align='center' class='bdiv_r'><strong>Item Description</strong></td>
					  <td width='15%' height='20' align='right' class='bdiv_r padr5'><strong>Price</strong></td>
					  <td width='15%' height='20' align='center' class='bdiv_r'><strong>SOH</strong></td>
					  <td width='15%' height='20' align='right' class='padr5'><strong>Total Value</strong></td>
					</tr>";

		//Get all the rows
		$tmpDate = strtotime($dteAsOf);
		$newdate = date("m/d/Y", $tmpDate);	
	
		//$query = $sp->spSelectValuationReport($database, $offset, $RPP, $vSearch, $warehouseid,$location,$pmgid,$plid,$newdate);
		$query = $tpiInventory->spSelectValuationReportv1($database, $offset, $RPP, $vSearch, $warehouseid,$location,$pmgid,$plid,$newdate);
			
		
		$num = $query->num_rows;
		
		$ctrProdLine = '';
		$ctrTotal = '';
		$strTotal='';
		$ctrTotVal=0;
		$ctrGrandTotal=0;
		//Echo each row
		$cnt = 0;

		if($num <> 0){
		// $query = $sp->spSelectValuationReportv2($database, $offset, $RPP, $vSearch, $warehouseid,$location,$pmgid,$plid,$newdate);
		//$num=$query->num_rows;
		while($row = $query->fetch_object()) 
		{	
			$cnt ++;
			($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
			//$totamt = number_format($row->TotalValue, 2, '.', ',');			
			$Backtrack = $row->QtyOut - $row->QtyIn;
			$SOH = $row->SOH + $Backtrack;
			$TotalSOH = number_format($SOH, 2, '.', ',');					
			$totamt = number_format($SOH * $row->ProductPrice, 2, '.', ',');					
			
			$ctrTest = $row->ProdLine;
				
			if($ctrProdLine != '')
			{
				if(trim($ctrTest, '') != trim($ctrProdLine, '')){
					$ctrTotVal = number_format($ctrTotVal, 2, '.', ',');	
					$strTotal = "
								    <tr align='center' class='$alt'>
								   		<td height='20' align='left' class='padl5 borderBR'></td>
								   		<td height='20' align='center' class='padl5 borderBR'></td>
								   		<td height='20' align='left' class='padl5 borderBR'></td> 
								   		<td height='20' align='right' class='padl5 borderBR'></td>
								   		<td height='20' align='right' class='padl5 borderBR'><strong>Sub-total:</strong></td>
								   		<td height='20' align='right' class='padr5 borderBR'><strong>$ctrTotVal</strong></td>
								    </tr>
							    ";
					$ctrTotVal = $row->TotalValue;
				}else{
					$ctrTotValv1 = $SOH * $row->ProductPrice;
					$strTotal = '';
					$ctrTotVal = $ctrTotVal + $ctrTotValv1;
				}		
			}else{	
					$ctrTotValv1 = $SOH * $row->ProductPrice;
					$strTotal = '';
					$ctrTotVal = $ctrTotVal + $ctrTotValv1;
			}
	  		echo "	$strTotal
					<tr align='center' class='$alt'>
	                  <td height='20' align='left' class='padl5 borderBR'>$row->ProdLineCode</td>
	                  <td height='20' align='center' class='padl5 borderBR'>$row->ItemCode</td>
	                  <td height='20' align='left' class='padl5 borderBR'>$row->ItemDescription</td> 
	                  <td height='20' align='right' class='padl5 borderBR'>$row->ProductPrice &nbsp;</td>
	                  <td height='20' align='center' class='padl5 borderBR'>$TotalSOH</td>
	                  <td height='20' align='right' class='padr5 borderBR'>$totamt</td>
					</tr>
				  ";
	  		$ctrProdLine = $row->ProdLine;	  		
		}
		/* ############################################################################################################################## */
		/*
			echo "	
					<tr align='center'>
						<td class='borderBR' colspan='13' height='20'>
								<span class='txtredsbold'>No record(s) to display.</span>
						</td>
					</tr>
				 ";
		*/
		}

		while($row = $query->fetch_object()) 
		{	
			
				/*
					***********************************
					**  Modified by: Gino C. Leabres **
					**  9.24.2012**********************
					**  ginophp@yahoo.com**************
				***********************************
				*/

			$cnt ++;
			($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
			//$totamt = number_format($row->TotalValue, 2, '.', ',');			
			$Backtrack = $row->QtyOut - $row->QtyIn;
			$SOH = $row->SOH + $Backtrack;
			$TotalSOH = number_format($SOH, 2, '.', ',');					
			$totamt = number_format($SOH * $row->ProductPrice, 2, '.', ',');					
			
			$ctrTest = $row->ProdLine;
				
			if($ctrProdLine != ''){
			
				if(trim($ctrTest, '') != trim($ctrProdLine, '')){
					$ctrTotVal = number_format($ctrTotVal, 2, '.', ',');	
					$strTotal = "<tr align='center' class='$alt'>
		                  <td height='20' align='left' class='padl5 borderBR'></td>
		                  <td height='20' align='center' class='padl5 borderBR'></td>
		                  <td height='20' align='left' class='padl5 borderBR'></td> 
		                  <td height='20' align='right' class='padl5 borderBR'></td>
		                  <td height='20' align='right' class='padl5 borderBR'><strong>Sub-total:</strong></td>
		                  <td height='20' align='right' class='padr5 borderBR'><strong>$ctrTotVal</strong></td></tr>";
					$ctrTotVal = $row->TotalValue;
				}else{
					$ctrTotValv1 = $SOH * $row->ProductPrice;
					$strTotal = '';
					$ctrTotVal = $ctrTotVal + $ctrTotValv1;
				}		
			}else{	
					$ctrTotValv1 = $SOH * $row->ProductPrice;
					$strTotal = '';
					$ctrTotVal = $ctrTotVal + $ctrTotValv1;
			}
		
	  		echo  "   	$strTotal
						<tr align='center' class='$alt'>
							<td height='20' align='left' class='padl5 borderBR'>$row->ProdLineCode</td>
							<td height='20' align='center' class='padl5 borderBR'>$row->ItemCode</td>
							<td height='20' align='left' class='padl5 borderBR'>$row->ItemDescription</td> 
							<td height='20' align='right' class='padl5 borderBR'>$row->ProductPrice &nbsp;</td>
							<td height='20' align='center' class='padl5 borderBR'>$TotalSOH</td>
							<td height='20' align='right' class='padr5 borderBR'>$totamt</td>
						</tr>
	              ";
	  		
	  		$ctrProdLine = $row->ProdLine;	  		
		  }
		//Close table
			$query = $sp->spSelectValuationReportCountv1($database, $vSearch, $warehouseid,$location,$pmgid,$plid,$newdate);
			
			if($query->num_rows){
				while($row = $query->fetch_object()) 
				{	 
					//Print Rows
					$ctrSOH = $ctrSOH + $row->SOH;
					$ctrProductPrice = $ctrProductPrice + $row->ProductPrice;
					$QtyIn = $QtyIn + $row->QtyIn;
					$QtyOut = $QtyOut + $row->QtyOut; 
					//$ctrGrandTotal = $ctrGrandTotal + $row->TotalValue;
				}
				$cBacktrack = $QtyOut - $QtyIn;
				$cSOH = $ctrSOH + $cBacktrack;
				$cGrandTotal = $cSOH * $ctrProductPrice;
				$cGrandTotal = number_format($cGrandTotal, 2, '.', ',');	
				//$ctrGrandTotal = number_format($ctrGrandTotal, 2, '.', ',');	
				$ctrTotVal = number_format($ctrTotVal, 2, '.', ',');
				
				echo "<tr align='center' >
								<td height='20' align='left' class='padl5 borderBR'></td>
								<td height='20' align='center' class='padl5 borderBR'></td>
								<td height='20' align='left' class='padl5 borderBR'></td> 
								<td height='20' align='right' class='padl5 borderBR'></td>
								<td height='20' align='right' class='padl5 borderBR'><strong>Sub-total:</strong></td>
								<td height='20' align='right' class='padr5 borderBR'><strong>$ctrTotVal</strong></td></tr>
					<tr align='center' >
								<td height='20' align='left' class='padl5 borderBR'></td>
								<td height='20' align='center' class='padl5 borderBR'></td>
								<td height='20' align='left' class='padl5 borderBR'></td> 
								<td height='20' align='right' class='padl5 borderBR'></td>
								<td height='20' align='right' class='padl5 borderBR'><strong>Grand Total:</strong></td>
								<td height='20' align='right' class='padr5 borderBR'><strong>$cGrandTotal</strong></td></tr></table>";
		}
	}
	elseif ($maxPage>=1 AND $type=='num') 
	{
	}
		
}
?>
