<?php
require_once("../initialize.php");
global $database;
ini_set('max_execution_time', 500);

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
$type='';

if (isset($_GET["p"])) 
{
	$pageNum = $_GET["p"];
}

if (isset($_GET["t"])) 
{
	$type = $_GET["t"];
}

if(isset($_GET['fdate']))
{
	$fdate = $_GET['fdate'];
}
else
{
	$fdate = "";
}

if(isset($_GET['tdate']))
{
	$tdate = $_GET['tdate'];
}
else
{
	$tdate = "";
}

if(isset($_GET['ftime']))
{
	$ftime = $_GET['ftime'];
}
else
{
	$ftime = "";
}

if(isset($_GET['ttime']))
{
	$ttime = $_GET['ttime'];
}
else
{
	$ttime = "";
}

if(isset($_GET['product']))
{
	$product = $_GET['product'];
}
else
{
	$product = "";
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
$query = $sp->spSelectTopSellingProducts($database, 1, 0, 0, $fdate, $tdate, $ftime, $ttime, $product);
$row = $query->fetch_object();
$num =$query->num_rows;

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
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$fdate."\", \"".$tdate."\", \"".$ftime."\", \"".$ttime."\", \"".$product."\")'>$page</a> ";
		}
	}

	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1)
	{
		$page  = $pageNum - 1;		
		
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$fdate."\", \"".$tdate."\", \"".$ftime."\", \"".$ttime."\", \"".$product."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\",  \"".$fdate."\", \"".$tdate."\", \"".$ftime."\", \"".$ttime."\", \"".$product."\")'  style:'cursor:pointer'> ";
	}
	else
	{
		$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
		$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
	}

	//These are the button links for next/last enabled/disabled
	if ($pageNum < $maxPage AND $upper <= $maxPage) {
		$page = $pageNum + 1;
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\",  \"".$fdate."\", \"".$tdate."\", \"".$ftime."\", \"".$ttime."\", \"".$product."\")'>";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\",  \"".$fdate."\", \"".$tdate."\", \"".$ftime."\", \"".$ttime."\", \"".$product."\")' >";
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
	              <td width='40%' height='20' align='left' class='padl5 bdiv_r'><strong>Item Code and Description</strong></td>
	              <td width='20%' height='20' align='right' class='padr5 bdiv_r'><strong>Units Sold</strong></td>
				  <td width='20%' height='20' align='right' class='padr5 bdiv_r'><strong>Total DGS</strong></td>
				  <td width='20%' height='20' align='right' class='padr5 bdiv_r'><strong>Current SOH Balance</strong></td>
				</tr>";

		//Get all the rows
		$alt = '';
		$query = $sp->spSelectTopSellingProducts($database, 2, $offset, $RPP, $fdate, $tdate, $ftime, $ttime, $product);
		$num = $query->num_rows;

		$cnt = 0;		
		while($row = $query->fetch_object()) 
		{	 
			$cnt ++;
			($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
				
	  		echo "<tr align='center' class='$alt'>
	                  			<td height='20' align='left' class='padl5 borderBR'>$row->Product</td>
	                  			<td height='20' align='right' class='padr5 borderBR'>".$row->TotalQty."</td>
	                  			<td height='20' align='right' class='padr5 borderBR'>".number_format($row->TotalDGS,2)."</td>
	                  			<td height='20' align='right' class='padr5 borderBR'>$row->SOH</td> 
	                  		</tr>";	  		
		  }
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
	              <td width='40%' height='20' align='left' class='padl5 bdiv_r'><strong>Item Code and Description</strong></td>
	              <td width='20%' height='20' align='right' class='padr5 bdiv_r'><strong>Units Sold</strong></td>
				  <td width='20%' height='20' align='right' class='padr5 bdiv_r'><strong>Total DGS</strong></td>
				  <td width='20%' height='20' align='right' class='padr5 bdiv_r'><strong>Current SOH Balance</strong></td>
				</tr>
	            <tr align='center'>
	              <td class='borderBR' colspan='4' height='20'><span class='txtredsbold'>No record(s) to display.</span></td>
	            </tr>
          	</table>";
}
?>