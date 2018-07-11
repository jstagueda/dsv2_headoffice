<?php
require_once("../initialize.php");
global $database;

#=This is the number of rows to display per page (results per page)
$RPP = '10';

if (isset($_GET['fromdate']))
{
	$frmdate = $_GET['fromdate'];
	$frmdate2 = date("Y-m-d", strtotime($frmdate));
}
else 
{
	$frmdate = 0;
}
if (isset($_GET['todate']))
{
	$todate = $_GET['todate'];
	$todate2 = date("Y-m-d", strtotime($todate));
}
else 
{
	$todate = 0;
}
if (isset($_GET['rrid']))
{
	$rid = $_GET['rrid'];
}
else 
{
	$rid = 0;
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

$query = $sp->spSelectDMCMRegisterCount($database, $frmdate2, $todate2, $rid);
$row = $query->fetch_object();
$num = $query->num_rows;
//echo $num; exit();
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
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$frmdate2."\", \"".$todate2."\", \"".$rid."\")'>$page</a> ";
		}
	}

	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1)
	{
		$page  = $pageNum - 1;
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$frmdate2."\", \"".$todate2."\", \"".$rid."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$frmdate2."\", \"".$todate2."\", \"".$rid."\")'  style:'cursor:pointer'> ";
	}
	else
	{
		$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
		$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
	}

	//These are the button links for next/last enabled/disabled
	if ($pageNum < $maxPage AND $upper <= $maxPage) {
		$page = $pageNum + 1;
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$frmdate2."\", \"".$todate2."\", \"".$rid."\")'  >";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$frmdate2."\", \"".$todate2."\", \"".$rid."\")' >";
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
                    	<td height='20' class='bdiv_r' align='center' width='10%'>Date</td>
                    	<td height='20' class='bdiv_r' align='center' width='10%'>Dealer</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='20%'>Name</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='15%'>Reason Code</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>Document No.</td>
                      	<td height='20' class='bdiv_r padr5' align='right' width='10%'>Debit</td>
                      	<td height='20' class='bdiv_r padr5' align='right' width='10%'>Credit</td>
                      	<td height='20' class='padl5' align='left' width='15%'>Remarks</td>
                  	</tr>";
		
		//Get all the rows
		$query = $sp->spSelectDMCMRegister($database, $offset, $RPP, $frmdate2, $todate2, $rid);
		$num=$query->num_rows;

		//Echo each row
		$ctr = 0;
		$cnt = 0;
		$num = 0;
		$num3 = 0;
		$temp = 0;
		$flag = 1;
		$debittotal = 0;
		$credittotal = 0;
		$debittotal2 =0;
		$credittotal2 = 0;
		$totalvalued = 0;
		$totalvaluec = 0;
		$totalvalued2 = 0;
		$totalvaluec2 = 0;
		$ctr3 = 0;
		$txndate2 = "";
		while($row = $query->fetch_object()) 
		{	 
			$cnt ++;
			($cnt % 2) ? $class = '' : $class = 'bgEFF0EB';
			$txndate = strtotime($row->TxnDate);			
			$refno = $row->RefNo;
			$docno = $row->DocumentNo;
			$customer = $row->Customer;
			$particulars = $row->Particulars;
			$debit = number_format($row->Debit, 2, '.', '');
			$credit = number_format($row->Credit, 2, '.', '');						
	  		$custcode = $row->CustCode;
	  		$remarks = $row->rem;
	  		
			if($credit == 0)
	  		{
	  			$credit = '  ';
	  		}
			if($debit == 0)
			{
				$debit = '  ';
			}
	  		
	  		$debittotal = $debittotal + $debit;
	  		$credittotal = $credittotal + $credit;
	  		
	  		if($ctr == 0)
	  		{
	  		  $rcode = $row->Reasonname;
	  		}
	  		else
	  		{
	  			$rcode =  "";
	  		}
	  		
			if($ctr3 == 0)
	  		{	  		  
	  		  $txndate = date("m/d/Y", $txndate);
	  		}
	  		else
	  		{	  			
	  			$txndate = "";
	  		}
	  		
	  		$totalvalued = $totalvalued + $row->Debit;
	  		$totalvaluec = $totalvaluec + $row->Credit;
	  		
		
	  		echo "<tr align='center' class='$class'>
				  		<td height='20' class='borderBR' align='center' width='10%'>$txndate</td>
				  		<td height='20' class='borderBR' align='center' width='10%'>$custcode</td>
                      	<td height='20' class='borderBR padl5' align='left' width='20%'>$customer</td>
                      	<td height='20' class='borderBR padl5' align='left' width='15%'>$rcode</td>
                      	<td height='20' class='borderBR' align='center' width='10%'>$docno</td>
                      	<td height='20' class='borderBR padr5' align='right' width='10%'>$debit</td>
                      	<td height='20' class='borderBR padr5' align='right' width='10%'>$credit</td>
                      	<td height='20' class='borderBR padl5' align='left' width='15%'>$remarks</td>
    
		  		</tr>";
				
				$txndate2 = date("Y-m-d",strtotime($row->TxnDate));
	  		    $queryCnt = $sp->spSelectDMCMRegisterCount2($database,$txndate2, $row->reasonID);
	  		    $num2=$queryCnt->num_rows;
							
				
				$ctr = $ctr + 1;
				if($ctr == $num2)
						{
							$debittotal2 = number_format($debittotal, 2, '.', '');
							$credittotal2 = number_format($credittotal, 2, '.', '');
						  echo"<table width='100%'  border='0' cellpadding='0' cellspacing='0'>
					
							<tr class='bgE6E8D9'>
								<td colspan='11' height='20'>
									<table width='100%'  border='0' cellpadding='0' cellspacing='0'>
									<tr>
										<td height='20' align='center' class='borderBR' width='10%'></td>
								  		<td height='20' align='center' class='borderBR' width='10%'></td>
				                      	<td height='20' align='center' class='borderBR' width='20%'></td>
				                      	<td height='20' align='center' class='borderBR' width='15%'></td>
				                      	<td height='20' align='right'  class='borderBR padr5' width='10%'><strong>$row->Reasonname Subtotal :</strong></td>
				                      	<td height='20' align='right'  class='borderBR padr5' width='10%'><strong>$debittotal2</strong></td>
				                      	<td height='20' align='right'  class='borderBR padr5' width='10%'><strong>$credittotal2</strong></td>
				                      	<td height='20' align='center' class='borderBR' width='15%'></td>
									</tr>
									</table>
								</td>
							</tr>";	
						  $debittotal = 0;
						  $credittotal = 0;
						  $ctr = 0;							  
						}

				$queryCnt2 = $sp->spSelectDMCMRegisterCount2($database,$txndate2, $rid);		
				$num3=$queryCnt2->num_rows;
				$dailytotaldate = date("d/m/Y",strtotime($row->TxnDate));
				$ctr3 = $ctr3 + 1;
				
				if($ctr3 == $num3)
						{
							
							$totalvalued2 = number_format($totalvalued, 2, '.', '');
	  						$totalvaluec2 = number_format($totalvaluec, 2, '.', '');
	  		
						  echo"<table width='100%'  border='0' cellpadding='0' cellspacing='0'>
								<tr>
								<td colspan='11' height='20'>
									<table width='100%'  border='0' cellpadding='0' cellspacing='0'>
									<tr>
										<td height='20' align='center' class='borderBR' width='10%'>&nbsp;</td>
								  		<td height='20' align='center' class='borderBR' width='10%'>&nbsp;</td>
				                      	<td height='20' align='center' class='borderBR' width='20%'>&nbsp;</td>
				                      	<td height='20' align='right' class='borderBR padr5' width='15%'><strong>Daily Total For</strong></td>
				                      	<td height='20' align='right'  class='borderBR padr5' width='10%'><strong>$dailytotaldate :  </strong></td>
				                      	<td height='20' align='right'  class='borderBR padr5' width='10%'><strong>$totalvalued2</strong></td>
				                      	<td height='20' align='right'  class='borderBR padr5' width='10%'><strong>$totalvaluec2</strong></td>
				                      	<td height='20' align='center' class='borderBR' width='15%'></td>
									</tr>
									</table>
								</td>
							</tr>";	
						  $ctr3 = 0;	
						  $totalvaluec = 0;
						  $totalvalued = 0;						  
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
	echo "<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='tab txtdarkgreenbold10'>
                    <tr align='center'>
                    	<td height='20' class='bdiv_r' align='center' width='5%'>Date</td>
                    	<td height='20' class='bdiv_r' align='center' width='7%'>Dealer</td>
                      	<td height='20' class='bdiv_r' align='center' width='20%'>Name</td>
                      	<td height='20' class='bdiv_r' align='center' width='15%'>Reason Code</td>
                      	<td height='20' class='bdiv_r' align='center' width='7%'>Reference #</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='7%'>Debit</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='7%'>Credit</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>Remarks</td>
                  	</tr>
                  	</table>              
	            ";
}
?>
