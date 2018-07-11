<?php
require_once("../initialize.php");
global $database;
#=This is the number of rows to display per page (results per page)
$RPP = '2';

#=Images for the next and back button
$PrevIc=		"images/bprv.gif";
$FirstIc=		"images/bfrst.gif";
$NextIc=		"images/bnxt.gif";
$LastIc=		"images/blst.gif";

$dPrevIc=		"images/dprv.gif";
$dFirstIc=		"images/dfrst.gif";
$dNextIc=		"images/dnxt.gif";
$dLastIc=		"images/dlst.gif";

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


if(isset($_GET['bid']))
{
	$bid =$_GET['bid'];
}
else
{
	$bid = 0;
}


$query = $sp->spSelectBrochureLayout($database, $bid);
$row = $query->fetch_object();
$num =$query->num_rows;

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
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$bid."\")'>$page</a> ";
		}
	}

	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1)
	{
		
		$page  = $pageNum - 1;
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\" , \"".$bid."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\",  \"".$bid."\")'  style:'cursor:pointer'> ";
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
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$bid."\")'  >";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$page."\",\"".$bid."\")' >";
	} else 
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

		//Get all the rows

		$query = $sp->spSelectBrochureLayoutLimit($database, $bid, $offset, $RPP);
		$query1 = $sp->spSelectBrochureLayoutLimit($database, $bid, $offset, $RPP);
		$query2 = $sp->spSelectBrochureLayoutLimit($database, $bid, $offset, $RPP);
		$num=$query->num_rows;

							echo "<table width='100%'><tr>";
                           if($query->num_rows)
                            {
                            while($row = $query->fetch_object())
                            {
                       
                           	 echo "<td height='20'>&nbsp;<span class='txt10'>Page: &nbsp;<strong>$row->PageNum</strong></span></td>
                           	 	   <td height='20'>&nbsp;<span class='txt10'>Layout: &nbsp;<strong>$row->LayoutType</strong></span></td>";
                            }
                             $query->close();
                            }
							echo "</tr><tr>";
                           if($query1->num_rows)
                            {
                            while($row1 = $query1->fetch_object())
                            {
	                       		if($row1->LayoutTypeID == 1)
	                       		{
	                           	   echo "<td height='20' colspan='2'>
	                           	   			<TABLE BORDER='0' cellpadding='0' CELLSPACING='0'>
											<TR>
											
											<TD WIDTH='410' HEIGHT='360' BACKGROUND='images/horizontalhalf.bmp' VALIGN='middle' ALIGN='center'>
											
											<FONT SIZE='+2' COLOR='black'>$row1->Promo</FONT><br><br><br><br><br><br><br><br><br><br>
											
											<FONT SIZE='+2' COLOR='black'>$row1->Promo1</FONT>
											
											</TD>
											
											</TR>
											</TABLE>
	                           	  		 </td>";
	                           	  
	                       		}
	                       		else
	                       		{
	                       			$align = 'center';
	                       			$space = '';
	                       			if($row1->Promo == "")
	                       			{
	                       				$align = 'right';
	                       				$space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp';
	                       			}
	                       			if($row1->Promo1 == "")
	                       			{
	                       				$align = 'left';
	                       				$space  = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp';
	                       			}
	                       			echo "<td height='20' colspan='2'>
	                       					<TABLE BORDER='0' cellpadding='0' CELLSPACING='0'>
											<TR>
											
											<TD WIDTH='410' HEIGHT='360' BACKGROUND='images/verticalhalf.bmp' VALIGN='middle' align='$align'>
											
											$space<FONT SIZE='+2' COLOR='black'>$row1->Promo</FONT>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											
											<FONT SIZE='+2' COLOR='black'>$row1->Promo1</FONT>$space
											
											</TD>
											
											</TR>
											</TABLE>
	                       				
	                       				 </td>";
	                       		}
                            }
                             $query1->close();
                            }
                            echo "</tr></table>";
	}
	elseif ($maxPage>=1 AND $type=='num') 
	{
		$offsets = $offset + 1;
		$offset2s = $offsets + 9;
		
		echo "" ;
	}
	else
	{
		echo "";
	}
}
else if (($num <= 0) && ($type == 'con'))
{
	echo " ";
}
		
?>
