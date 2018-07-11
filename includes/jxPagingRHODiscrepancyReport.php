<?php

require_once("../initialize.php");

if(isset($_POST['searched'])){
    
    $query = $database->execute("SELECT * FROM branch 
                                    WHERE ID NOT IN (1,2,3) 
                                    AND ((Code Like '".$_POST['searched']."%') OR (Name Like '".$_POST['searched']."%'))
                                        ORDER BY Code LIMIT 10");
    if($query->num_rows){
        while($res = $query->fetch_object()){
            $result[] = array("Label" => trim($res->Code)." - ".$res->Name,
                            "Value" => trim($res->Code)." - ".$res->Name,
                            "ID" => $res->ID);
        }
    }else{
        $result[] = array("Label" => "No result found.",
                            "Value" => "",
                            "ID" => 0);
    }
    die(json_encode($result));
    
}else{
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
$type = '';
if (isset($_GET["pgNum"])) 			$pageNum     	= $_GET["pgNum"];
if (isset($_GET["pgNum"])) 			$offset		 	= $_GET["pgNum"];	
if (isset($_GET["t"])) 				$type	     	= $_GET["t"];
if (isset($_GET["date"]))			$date 			= $_GET["date"];
if (isset($_GET["edate"]))			$edate 			= $_GET["edate"];
if (isset($_GET["bcode"]))			$bcode			= $_GET["bcode"];
if (isset($_GET["mtype"]))			$mtype			= $_GET["mtype"];

$date = date("Y-m-d", strtotime($date));
$edate = date("Y-m-d", strtotime($edate));
/*if ($bcode == 0)
{
	$bcode = "";	
}*/

$query = $sp->spSelectRHODiscrepancyReportv1($database, 0, 0, 2, $date, $edate, $bcode, $mtype);
$row = $query->fetch_object();
$num = $row->Total;	

if ($num > 0) 
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
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$date."\", \"".$edate."\", \"".$bcode."\", \"".$mtype."\")'>$page</a> ";
		}
	}

	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1)
	{
		
		$page  = $pageNum - 1;
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\" , \"".$date."\", \"".$date."\", \"".$bcode."\", \"".$mtype."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\",  \"".$date."\", \"".$date."\", \"".$bcode."\", \"".$mtype."\")'  style:'cursor:pointer'> ";
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
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$date."\", \"".$date."\", \"".$bcode."\", \"".$mtype."\")'  >";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\",\"".$date."\", \"".$date."\", \"".$bcode."\", \"".$mtype."\")' >";
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
		//Build the header
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab'>
					<tr>
					  <td width='8%' height='20' align='center' class='bdiv_r'><strong>Date</strong></td>
					  <td width='8%' height='20' align='left' class='padl5 bdiv_r'><strong>Reference No.</strong></td>
					  <td width='8%' height='20' align='left' class='padl5 bdiv_r'><strong>DR No.</strong></td>
					  <td width='10%' height='20' align='left' class='padl5 bdiv_r'><strong>Shipment Advise No.</strong></td>
					  <td width='27%' height='20' align='left' class='padl5 bdiv_r'><strong>Item Code - Description</strong></td>
					  <td width='8%' height='20' align='right' class='padr5 bdiv_r'><strong>Loaded Qty</strong></td>
					  <td width='8%' height='20' align='right' class='padr5 bdiv_r'><strong>Actual Qty</strong></td>
					  <td width='8%' height='20' align='right' class='padr5 bdiv_r'><strong>Discrepancy</strong></td>
					  <td width='15%' height='20' align='left' class='padl5'><strong>Reason</strong></td>
				    </tr>";
		
		//Get all the rows
		$query = $sp->spSelectRHODiscrepancyReportv1($database, $offset, $RPP, 1, $date, $edate, $bcode, $mtype);
		$num = $query->num_rows;
       
		//Echo each row
		$cnt = 0;
		while($row = $query->fetch_object()) 
		{	 
			$cnt ++;
			($cnt % 2) ? $class = '' : $class = 'bgEFF0EB';
			$discrepancy = abs($row->LoadedQty - $row->ConfirmedQty);	
	  		
	  		echo "<tr class='$class'>
		  			  <td height='20' align='center' class='borderBR'>".date("m/d/Y", strtotime($row->TransactionDate))."</td>
					  <td height='20' align='left' class='padl5 borderBR'>".$row->DocumentNo."</td>
					  <td height='20' align='left' class='padl5 borderBR'>".$row->PicklistRefNo."</td>
					  <td height='20' align='left' class='padl5 borderBR'>".$row->ShipmentAdviseNo."</td>
					  <td height='20' align='left' class='padl5 borderBR'>".$row->Code."-".$row->Name."</td>
					  <td height='20' align='right' class='padr5 borderBR'>".$row->LoadedQty."</td>
					  <td height='20' align='right' class='padr5 borderBR'>".$row->ConfirmedQty."</td>
					  <td height='20' align='right' class='padr5 borderBR'>".$discrepancy."</td>
					  <td height='20' align='left' class='padl5 borderBR'>".$row->Reason."</td>
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
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='1' class='tab txtdarkgreenbold10'>
				<tr>
				  <td width='10%' height='20' align='center' class='bdiv_r'><strong>Date</strong></td>
				  <td width='10%' height='20' align='left' class='padl5 bdiv_r'><strong>Reference No.</strong></td>
				  <td width='10%' height='20' align='left' class='padl5 bdiv_r'><strong>DR No.</strong></td>
				  <td width='10%' height='20' align='left' class='padl5 bdiv_r'><strong>Shipment Advise No.</strong></td>
				  <td width='15%' height='20' align='left' class='padl5 bdiv_r'><strong>Item Code - Description</strong></td>
				  <td width='10%' height='20' align='right' class='padr5 bdiv_r'><strong>Loaded Qty</strong></td>
				  <td width='10%' height='20' align='right' class='padr5 bdiv_r'><strong>Actual Qty</strong></td>
				  <td width='10%' height='20' align='right' class='padr5 bdiv_r'><strong>Discrepancy</strong></td>
				  <td width='15%' height='20' align='left' class='padl5'><strong>Reason</strong></td>
			    </tr>
				<tr>
				  <td class='borderBR' align='center' colspan='9' height='20'><span class='txtredsbold'>No record(s) to display.</span></td>
				</tr>
			</table>";
}
die();
}
?>