<?php
require_once("../initialize.php");
global $database;

#=This is the number of rows to display per page (results per page)
$RPP = '10';
if (isset($_GET['year']))
{
	$year = $_GET['year'];
}
else 
{
	$year = 0;
}
if (isset($_GET['month']))
{
	$month = $_GET['month'];
}
else 
{
	$month = 0;
}

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

//############################################
//Now import the settings
//include_once("settings.php");

//############################################
//Connect to the db
//mysql_connect($db_host,$MySqlUN,$MySqlPW);
//@mysql_select_db($database) or die( "Unable to select databases");

//############################################
//Get a quick count of all the rows
$query = $sp->spORRegisterCount($database, $year, $month);
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
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$year."\", \"".$month."\")'>$page</a> ";
		}
	}

	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1)
	{
		$page  = $pageNum - 1;
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$year."\", \"".$month."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$year."\", \"".$month."\")'  style:'cursor:pointer'> ";
	}
	else
	{
		$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
		$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
	}

	//These are the button links for next/last enabled/disabled
	if ($pageNum < $maxPage AND $upper <= $maxPage) {
		$page = $pageNum + 1;
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$year."\", \"".$month."\")'  >";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$year."\", \"".$month."\")' >";
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
                    <tr align='center' class='txtdarkgreenbold10'>
                    	<td height='20' class='bdiv_r' align='center' width='10%'>OR No.</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>OR Date</td>                      	
                      	<td height='20' class='bdiv_r padl5' align='left' width='30%'>Customer</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='10%'>Bank</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>Check No.</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>CheckDate</td>
                      	<td height='20' class='bdiv_r padr5' align='right' width='10%'>OR Amount</td>
                      	<td height='20' align='center' width='10%'>Status</td>
                  	</tr>";
		
		//Get all the rows
		$query = $sp->spORRegister($database, $offset, $RPP, $year, $month);
		$num=$query->num_rows;

		//Echo each row
		$cnt = 0;
		while($row = $query->fetch_object()) 
		{	 
			$cnt ++;
			($cnt % 2) ? $class = '' : $class = 'bgEFF0EB';
			$tmpordate = strtotime($row->ORDate);
			$ordate = date("m/d/Y", $tmpordate);
			$tmpcheckdate = strtotime($row->CheckDate);
			$checkdate = date("m/d/Y", $tmpcheckdate);
			$amount = number_format($row->TotalAmount, 2, '.', '');
	  		
	  		echo "<tr align='center' class='$class'>
				  	<td width='10%' height='20' align='center' class='borderBR'>$row->ORNO</td>
				  	<td width='10%' height='20' align='center' class='borderBR'>$ordate</td>				  	
				  	<td width='20%' height='20' align='left' class='borderBR padl5'>$row->Name</td>
				  	<td width='10%' height='20' align='left' class='borderBR padl5'>$row->Bank</td>
				  	<td width='10%' height='20' class='borderBR'>$row->CheckNo</td>
				  	<td width='10%' height='20' align='center' class='borderBR'>$checkdate</td>
				  	<td width='10%' height='20' align='right' class='borderBR padr5'>$amount</td>
				  	<td width='10%' height='20' align='center' class='borderBR padr5'>$row->Status</td>
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
	echo "<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='tab txtdarkgreenbold10'>
                    <tr align='center'>
                    	<td height='20' class='bdiv_r' align='center' width='10%'>OR No.</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>OR Date</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>SI No.</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='20%'>Customer</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='10%'>Bank</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>Check No.</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>CheckDate</td>
                      	<td height='20' class='bdiv_r padr5' align='right' width='10%'>OR Amount</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>Department</td>
                  	</tr>
                  	</table>              
	            ";
}
?>
