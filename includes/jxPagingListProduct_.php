<?php
require_once("../initialize.php");
global $database;
#=This is the number of rows to display per page (results per page)
$RPP = '10';
if (isset($_GET['svalue'])) {
	$vSearch  = $_GET['svalue'];
} else {
	$vSearch  = "";
}

if (isset($_GET['id'])) {
	$id  = $_GET['id'];
} else {
	$id  = 0;
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
$query = $sp->spSelectProductDMList($database, 0, $vSearch, 0, 0);
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

	if ($upper >$maxPage) 
	{
		$upper =$maxPage;
	}

	//Make sure there are 7 numbers (3 before, 3 after and current
	if ($upper-$page <6)
	{
		//We know that one of the page has maxed out
		//check which one it is
		if ($upper >=$maxPage)
		{
			//the upper end has maxed, put more on the front end
			$dif = $maxPage-$page;
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
		elseif ($page <= 1) 
		{
			//its the low end, add to upper end
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
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$id."\",  \"".$vSearch."\")'>$page</a> ";
		}
	}

	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1)
	{
		$page  = $pageNum - 1;
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$id."\", \"".$vSearch."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\",  \"".$id."\",\"".$vSearch."\")'  style:'cursor:pointer'> ";
	}
	else
	{
		$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
		$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
	}

	//These are the button links for next/last enabled/disabled
	if ($pageNum < $maxPage AND $upper <= $maxPage) 
	{
		$page = $pageNum + 1;
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$id."\", \"".$vSearch."\")'  >";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\",  \"".$id."\", \"".$vSearch."\")' >";
	} 
	else 
	{
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
		echo " <table width='100%'  border='0' align='left' cellpadding='0' cellspacing='1' class=''>
					<tr align='left'>
                       <td height='25' class='padl5 txtpallete borderBR' width='30%'  align='left'>&nbsp;&nbsp;Item Code</span></td>
					   <td height='25' class='padl5 txtpallete borderBR' width='70%' align='left'>Item Name</td>	
                    </tr>";
                    
		//Get all the rows
		
		$query = $sp->spSelectProductDMList($database, $id, $vSearch, $offset, $RPP);
		$num = $query->num_rows;

		if($query->num_rows)
            {
                 $rowalt=0;
                 while($row = $query->fetch_object())
                  {
                    $rowalt++;
                       ($rowalt%2) ? $class = "" : $class = "bgEFF0EB";				  
					   echo "<tr align='left' class='$class'>
						<td height='25' class='padl5 borderBR' width='30%'  align='left'>&nbsp;&nbsp;$row->ProductCode</td>
						<td height='25' class='padl5 borderBR' width='70%'  align='left'>
								<a href='index.php?pageid=20&bID=$row->ProductID&searchedTxt=$vSearch' 
										class='txtnavgreenlink'>$row->ProductName
								</a>
						</td>										
					</tr>";
				  }				  
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
	echo "<table width='100%'  border='0' cellpadding='0' cellspacing='1'>
	  					 <tr>
                            <td class='tab bordergreen_T'>
                                <table width='100%' border='0' cellpadding='0' cellspacing='1' class='txtdarkgreenbold10'>
                                    <tr align='center'>
                                      <td width='40%'><div align='center'>&nbsp;<span class='txtredbold'>Item Code</span></div></td>
                                      <td width='60%'><div align='center'><span class='txtredbold'>Item Name</span></div></td>
                                </table>
                            </td>
                          </tr>
	    <tr><td colspan='10' height='20' class='borderBR'><div align='center'><span class='txtredsbold'>No record(s) to display.</span></div></tr>
	    </table>";
}
//Close the db connection
//mysql_close();
?>
