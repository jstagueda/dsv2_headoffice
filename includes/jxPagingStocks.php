<?php
require_once("../initialize.php");
global $database;

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
	$pageNum = $_GET["p"];
}

$type='';
if (isset($_GET["t"])) 
{
	$type = $_GET["t"];
}

if (isset($_GET['svalue']))
{
	$vSearch = $_GET['svalue'];
	$warehouseid = $_GET['wid'];
}
else 
{
	$vSearch = "";
	$warehouseid = 0;
}

if( isset($_GET['lid']))
{
	$location = $_GET['lid'];
}
else
{
	$location = 0;
}


if(isset($_GET['pmgid']))
{
	$pmgid = $_GET['pmgid'];
}
else
{
	$pmgid = 0;
}

if(isset($_GET['isId']))
{
	$isId = $_GET['isId'];
}
else
{
	$isId = 0;
}


if(isset($_GET['plid']))
{
	$plid = $_GET['plid'];
}
else
{
	$plid = 0;
}

if(isset($_GET['brdid']))
{
	$brdid = $_GET['brdid'];
}
else
{
	$brdid = 0;
}

$bid = 0;
$cid = 0;
$pgeid = 0;
$colTypeid = 0;

//############################################
//Now import the settings
//include_once("settings.php");

//############################################
//Connect to the db
//mysql_connect($db_host,$MySqlUN,$MySqlPW);
//@mysql_select_db($database) or die( "Unable to select databases");

//############################################
//Get a quick count of all the rows
$query = $sp->spSelectStockzCount($database, $vSearch, $warehouseid, $location, $pmgid, $isId, $plid, $brdid);
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
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$warehouseid."\",\"".$location."\",\"".$bid."\",\"".$cid."\",\"".$pgeid."\",\"".$colTypeid."\",\"".$plid."\",\"".$pmgid."\",\"".$isId."\",\"".$brdid."\")'>$page</a> ";
		}
	}

	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1)
	{
		$page  = $pageNum - 1;		
		
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$warehouseid."\",\"".$location."\",\"".$bid."\",\"".$cid."\",\"".$pgeid."\",\"".$colTypeid."\",\"".$plid."\",\"".$pmgid."\",\"".$isId."\",\"".$brdid."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$vSearch."\", \"".$warehouseid."\",\"".$location."\",\"".$bid."\",\"".$cid."\",\"".$pgeid."\",\"".$colTypeid."\",\"".$plid."\",\"".$pmgid."\",\"".$isId."\",\"".$brdid."\")'  style:'cursor:pointer'> ";
	}
	else
	{
		$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
		$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
	}

	//These are the button links for next/last enabled/disabled
	if ($pageNum < $maxPage AND $upper <= $maxPage) {
		$page = $pageNum + 1;
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$warehouseid."\",\"".$location."\",\"".$bid."\",\"".$cid."\",\"".$pgeid."\",\"".$colTypeid."\",\"".$plid."\",\"".$pmgid."\",\"".$isId."\",\"".$brdid."\")'  >";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$vSearch."\", \"".$warehouseid."\",\"".$location."\",\"".$bid."\",\"".$cid."\",\"".$pgeid."\",\"".$colTypeid."\",\"".$plid."\",\"".$pmgid."\",\"".$isId."\",\"".$brdid."\")' >";
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
                      <td width='8%' height='20' align='left' class='padl5 bdiv_r'><strong>Item Code</strong></td>
                      <td width='22%' height='20' align='left' class='padl5 bdiv_r'><strong>Item Description</strong></td>
					  <td width='6%' height='20' align='center' class='bdiv_r'><strong>UOM</strong></td>
					  <td width='12%' height='20' align='center' class='bdiv_r'><strong>Prod Line</strong></td>
					  <td width='8%' height='20' align='center' class='bdiv_r'><strong>CSP</strong></td>
					  <td width='6%' height='20' align='center' class='bdiv_r'><strong>PMG</strong></td>
					  <td width='8%' height='20' align='center' class='bdiv_r'><strong>Inv Status</strong></td>
					  <td width='8%' height='20' align='center' class='bdiv_r'><strong>SOH</strong></td>
					  <td width='6%' height='20' align='center' class='bdiv_r'><strong>In Transit</strong></td>
					  <td width='8%' height='20' align='center' class='bdiv_r'><strong>Date Last Sold</strong></td>
					  <td width='8%' height='20' align='center'><strong>Days Not Availed</strong></td>
                    </tr>";

		//Get all the rows
		$query = $sp->spSelectStockz($database, $offset, $RPP, $vSearch, $warehouseid, $location, $pmgid, $isId, $plid, $brdid);
		$num=$query->num_rows;

		//Echo each row
		$cnt = 0;
		while($row = $query->fetch_object()) 
		{	 
			$cnt ++;
			($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
			$soh = number_format($row->SOH,0);
			$intransit = number_format($row->InTransit,0);
			$csp = number_format($row->CSP,2);
	  		
	  		echo "<tr align='center' class='$alt'>
	                  <td height='20' align='left' class='padl5 borderBR'><span class='txt10'><a href='index.php?pageid=33.1&pid=$row->ProductID&wid=$row->WarehouseID' class='txtnavgreenlink'>$row->Code</a></span></td>
	                  <td height='20' align='left' class='padl5 borderBR'>$row->Product</td>
	                  <td height='20' class='borderBR'>$row->UOMCode</td>
	                  <td height='20' class='borderBR'>$row->ProdLineCode</td>
	                  <td height='20' class='borderBR'>$csp</td>
	                  <td height='20' class='borderBR'>$row->PMGCode</td>
	                  <td height='20' class='borderBR'>$row->StatusCode</td>
	                  <td height='20' class='borderBR'>$soh</td>
	                  <td height='20' class='borderBR'>$intransit</td>
	                  <td height='20' class='borderBR'>$row->DateLastSold</td>
					  <td height='20' class='borderBR'>--</td>
	            </tr>";
		  }
		//Close table
		echo "</table>";
	}
	elseif ($maxPage>=1 AND $type=='num') 
	{
		$offsets = $offset + 1;
		$offset2s = $offsets + 9;
		
		echo "Displaying ". $offsets." to  ";
		if ( $offset2s <= $num)
		{
			echo $offset2s;

		}
		else
		{
			echo $num;
		}
		echo " (of ".$num." records)" ;
	}
	else
	{
		echo "Table doesn't contain records.";
	}
}
else if (($num <= 0) && ($type == 'con'))
{
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
	            <tr align='center'>
                      <td width='8%' height='20' align='left' class='padl5 bdiv_r'><strong>Item Code</strong></td>
                      <td width='22%' height='20' align='left' class='padl5 bdiv_r'><strong>Item Description</strong></td>
					  <td width='6%' height='20' align='center' class='bdiv_r'><strong>UOM</strong></td>
					  <td width='12%' height='20' align='center' class='bdiv_r'><strong>Prod Line</strong></td>
					  <td width='8%' height='20' align='center' class='bdiv_r'><strong>CSP</strong></td>
					  <td width='6%' height='20' align='center' class='bdiv_r'><strong>PMG</strong></td>
					  <td width='8%' height='20' align='center' class='bdiv_r'><strong>Inv Status</strong></td>
					  <td width='8%' height='20' align='center' class='bdiv_r'><strong>SOH</strong></td>
					  <td width='6%' height='20' align='center' class='bdiv_r'><strong>In Transit</strong></td>
					  <td width='8%' height='20' align='center' class='bdiv_r'><strong>Date Last Sold</strong></td>
					  <td width='8%' height='20' align='center'><strong>Days Not Availed</strong></td>
                    </tr>
	            <tr align='center'>
	              <td class='borderBR' colspan='12' height='20' ><span class='txtredsbold'>No record(s) to display.</span></td>
	            </tr>
	          	</table>                  
	            ";
}
?>
