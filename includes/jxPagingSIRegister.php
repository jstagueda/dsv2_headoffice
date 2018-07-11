<?php
require_once("../initialize.php");
global $database;

#=This is the number of rows to display per page (results per page)
$RPP = '10';
$year = 0;
$month = 0;

if (isset($_GET['svalue']))
{
	$vSearch  = $_GET['svalue'];
}
else 
{
	$vSearch  = "";
}

if (isset($_GET['fromDate']))
{
	$tmptxndate = strtotime($_GET['fromDate']);
	$fromDate = date("Y-m-d", $tmptxndate);
}
else 
{
	$fromDate  = "";
}

if (isset($_GET['toDate']))
{
	$tmptxndate = strtotime($_GET['toDate']. " +1 day");
	$toDate = date("Y-m-d", $tmptxndate);
}
else 
{
	$toDate  = "";
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

$query = $sp->spSelectSIRegisterCount($database, $fromDate, $toDate, $vSearch);
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
	for($page; $page <=  $upper; $page++) {
		if ($page == $pageNum){
			//If this is the current page then disable the link
			$nav .= " <font color='red'>$page</font> ";
		}else{
			//If this is a different page then link to it
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$fromDate."\", \"".$toDate."\")'>$page</a> ";
		}
	}

	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1){
		$page  = $pageNum - 1;
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$fromDate."\", \"".$toDate."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$vSearch."\", \"".$fromDate."\", \"".$toDate."\")'  style:'cursor:pointer'> ";
	}else{
		$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
		$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
	}

	//These are the button links for next/last enabled/disabled
	if ($pageNum < $maxPage AND $upper <= $maxPage) {
		$page = $pageNum + 1;
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$vSearch."\", \"".$fromDate."\", \"".$toDate."\")'  >";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$vSearch."\", \"".$fromDate."\", \"".$toDate."\")' >";
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
                    	<td height='20' class='bdiv_r' align='center' width='7%'>Order</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='8%'>Invoice Number</td>
                      	<td height='20' class='bdiv_r' align='center' width='8%'>IBM/IGS</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='13%'>Name</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Campaign Price</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Basic Discount</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Disc Gross Sale</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Addl Discount</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='7%'>Sales with VAT</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>12% VAT</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Vatable Sales</td>
                      	<td height='20' class='bdiv_r' align='center' width='5%'>ADPP</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='7%'>Net Sales Value</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Invoice Amount</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Invoice 38 days</td>
                      	<td height='20' class='padl5' align='left' width='5%'>Invoice 52 days</td>
                  	</tr>";
		
		//Get all the rows
		$query = $sp->spSelectSIRegister($database, $offset, $RPP, $fromDate, $toDate, $vSearch);
		$num=$query->num_rows;

		//Echo each row
		$cnt = 0;
      	$totCampaignPrice = 0;
      	$totBasicDicsount = 0;
      	$totDiscGrossSales = 0;
      	$totAddtnlDiscount = 0;    
      	$totSaleswithvat = 0;
      	$totAmountwovat = 0;                 
      	$totVatAmount = 0;
      	$totAddtlDiscountPrev = 0;
      	$totNetSalesValue = 0;
      	$totInvoiceAmount = 0;
      	$totCPI = 0;   		
      	
		while($row = $query->fetch_object()) 
		{	 
			$cnt ++;
			($cnt % 2) ? $class = '' : $class = 'bgEFF0EB';
			$txndate = strtotime($row->TxnDate);
			$txndate = date("m/d/Y", $txndate);
			$txno = $row->TxnNo;
			$orderno = $row->OrderNo;
			$customerName = $row->CustomerName;
			$status = $row->Status;
			$customerCode = $row->CustomerCode;
			$campaignPrice = $row->CampaignPrice;
			$basicDicsount = $row->BasicDiscount;
			$discGrossSales = $campaignPrice - $basicDicsount;
			$addtnlDiscount = $row->AddtlDiscount;		
			$saleswithvat = $campaignPrice - $basicDicsount - $addtnlDiscount;
	         $amountwovat = $saleswithvat / 1.12;                 
	         $vatAmount = $saleswithvat - $amountwovat;
	         $addtlDiscountPrev = $row->AddtlDiscountPrev;
	         $netSalesValue = $amountwovat - $addtlDiscountPrev;
	         $invoiceAmount = $netSalesValue + $vatAmount;	  		
	         
	         $totCampaignPrice += $campaignPrice;
	         $totBasicDicsount += $basicDicsount;
	         $totDiscGrossSales += $discGrossSales;
	         $totAddtnlDiscount += $addtnlDiscount;    
	         $totSaleswithvat += $saleswithvat;
	         $totAmountwovat += $amountwovat;                 
	         $totVatAmount += $vatAmount;
	         $totAddtlDiscountPrev += $addtlDiscountPrev;
	         $totNetSalesValue += $netSalesValue;
	         $totInvoiceAmount += $invoiceAmount;  
	         $totCPI += $row->TotalCPI;
	                
	  		echo "<tr align='center' class='$class'>
				  	<td height='20' align='center' width='7%' class='borderBR'>$orderno</td>
                      	<td height='20' align='center' class='borderBR'>$txno</td>
                      	<td height='20' align='center' class='borderBR'>$customerCode</td>
                      	<td height='20' align='left' class='borderBR padl5'>$customerName</td>
                      	<td height='20' align='right' class='borderBR padr5'>" . number_format($campaignPrice, 2) . "</td>
                      	<td height='20' align='right' class='borderBR padr5'>" . number_format($basicDicsount, 2) . "</td>
                      	<td height='20' align='right' class='borderBR padr5'>" . number_format($discGrossSales, 2) . "</td>
                      	<td height='20' align='right' class='borderBR padr5'>" . number_format($addtnlDiscount, 2) . "</td>
                      	<td height='20' align='right' class='borderBR padr5'>" . number_format($saleswithvat, 2) . "</td>
                      	<td height='20' align='right' class='borderBR padr5'>" . number_format($vatAmount, 2) . "</td>
                      	<td height='20' align='right' class='borderBR padr5'>" . number_format($amountwovat, 2) . "</td>
                      	<td height='20' align='right' class='borderBR padr5'>" . number_format($addtlDiscountPrev, 2) . "</td>
                      	<td height='20' align='right' class='borderBR padr5'>" . number_format($netSalesValue, 2) . "</td>
                      	<td height='20' align='right' class='borderBR padr5'>" . number_format($invoiceAmount, 2) . "</td>
                      	<td height='20' align='right' class='borderBR padr5'>0.00</td>
                      	<td height='20' align='right' class='borderBR padr5'>0.00</td>
		  		</tr>";
		  }
		
		$campaign_cpi = $totCampaignPrice - $totCPI;   
		$dgross_cpi = $totDiscGrossSales - $totCPI;
		$iamount_cpi = $totInvoiceAmount - $totCPI;
        echo "<tr><td colspan='16' height='20' class='borderBR'>&nbsp;</td></tr><tr align='center' class='txtdarkgreenbold10'>
            	<td height='20' class='borderBR' align='center'>&nbsp;</td>
                <td height='20' class='borderBR' align='center'>&nbsp;</td>
                <td height='20' class='borderBR' align='center'>&nbsp;</td>
                <td height='20' class='borderBR padr5' align='right'>Total :</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totCampaignPrice, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totBasicDicsount, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totDiscGrossSales, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAddtnlDiscount, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totSaleswithvat, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totVatAmount, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAmountwovat, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAddtlDiscountPrev, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totNetSalesValue, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totInvoiceAmount, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
             </tr>
             <tr align='center' class='txtdarkgreenbold10'>
            	<td height='20' class='borderBR' align='center'>&nbsp;</td>
                <td height='20' class='borderBR' align='center'>&nbsp;</td>
                <td height='20' class='borderBR' align='center'>&nbsp;</td>
                <td height='20' class='borderBR padr5' align='right'>Less CPI :</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($campaign_cpi, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>&nbsp;</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($dgross_cpi, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($iamount_cpi, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
             </tr>";
        
        echo "<tr><td colspan='16' height='20' class='borderBR'>&nbsp;</td></tr>
        		<tr align='center' class='txtdarkgreenbold10'>
            	<td height='20' class='borderBR padr5' align='right'>&nbsp;</td>
                <td height='20' class='borderBR padr5' align='right'>&nbsp;</td>
                <td height='20' class='borderBR padr5' align='right'>&nbsp;</td>
                <td height='20' class='borderBR padr5' align='right'>Report Total :</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totCampaignPrice, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totBasicDicsount, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totDiscGrossSales, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAddtnlDiscount, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totSaleswithvat, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totVatAmount, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAmountwovat, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totAddtlDiscountPrev, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totNetSalesValue, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>" . number_format($totInvoiceAmount, 2) . "</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
             </tr>
             <tr align='center' class='txtdarkgreenbold10'>
            	<td height='20' class='borderBR' align='center'>&nbsp;</td>
                <td height='20' class='borderBR' align='center'>&nbsp;</td>
                <td height='20' class='borderBR' align='center'>&nbsp;</td>
                <td height='20' class='borderBR padr5' align='right'>Less CPI :</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
                <td height='20' class='borderBR padr5' align='right'>0.00</td>
             </tr>";		  
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
                    <tr align='center' class='txtdarkgreenbold10'>
                    	<td height='20' class='bdiv_r' align='center' width='7%'>Order</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='8%'>Invoice Number</td>
                      	<td height='20' class='bdiv_r' align='center' width='10%'>IBM/IGS</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='15%'>Name</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Campaign Price</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Basic Discount</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Disc Gross Sale</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Addl Discount</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Sales with VAT</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>12% VAT</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Vatable Sales</td>
                      	<td height='20' class='bdiv_r' align='center' width='5%'>ADPP</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Net Sales Value</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Invoice Amount</td>
                      	<td height='20' class='bdiv_r padl5' align='left' width='5%'>Invoice 38 days</td>
                      	<td height='20' class='padl5' align='left' width='5%'>Invoice 52 days</td>
                  	</tr>
                  	<tr><td colspan='16' height='20' class='txtreds borderBR' align='center'><strong>No record(s) to display.</strong></td></tr>
                  	</table>              
	            ";
}
?>
