<?php
require_once("../initialize.php");
#=This is the number of rows to display per page (results per page)
$RPP = '10';
if (isset($_GET['svalue']))
{
	$vSearch  = $_GET['svalue'];
}
else 
{
	$vSearch  = "";
}
global $database;

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
$query = $sp->spSelectInventoryInCount($database, $vSearch);
$row = $query->fetch_object();
$num = $row->numrows;
//############################################
//If there are some rows then start the pagination
if ($num>0) {;
	//Determine the maxpage and the offset for the query
	$maxPage = ceil($num/$RPP);
	$offset = ($pageNum - 1) * $RPP;

	//Initiate the navigation bar
	$nav  = '';

	//get low end
	$page = $pageNum-3;

	//get upperbound
	$upper =$pageNum+3;

	if ($page <=0) {
		$page=1;
	}

	if ($upper >$maxPage) {
		$upper =$maxPage;
	}

	//Make sure there are 7 numbers (3 before, 3 after and current
	if ($upper-$page <6){

		//We know that one of the page has maxed out
		//check which one it is
		//echo "$upper >=$maxPage<br>";
		if ($upper >=$maxPage){
			//the upper end has maxed, put more on the front end
			//echo "to begining<br>";
			$dif =$maxPage-$page;
			//echo "$dif<br>";
				if ($dif==3){
					$page=$page-3;
				}elseif ($dif==4){
					$page=$page-2;
				}elseif ($dif==5){
					$page=$page-1;
				}
		}elseif ($page <=1) {
			//its the low end, add to upper end
			//echo "to upper<br>";
			$dif =$upper-1;

			if ($dif==3){
				$upper=$upper+3;
			}elseif ($dif==4){
				$upper=$upper+2;
			}elseif ($dif==5){
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
	for($page; $page <=  $upper; $page++) {
		if ($page == $pageNum){
			//If this is the current page then disable the link
			$nav .= " <font color='red'>$page</font> ";
		}else{
			//If this is a different page then link to it
			$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\", \"".$vSearch."\")'>$page</a> ";
		}
	}

	//These are the button links for first/previous enabled/disabled
	if ($pageNum > 1){
		$page  = $pageNum - 1;
		$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\", \"".$vSearch."\")' style:'cursor:pointer'> ";
		$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\", \"".$vSearch."\")'  style:'cursor:pointer'> ";
	}else{
		$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
		$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
	}

	//These are the button links for next/last enabled/disabled
	if ($pageNum < $maxPage AND $upper <= $maxPage) {
		$page = $pageNum + 1;
		$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\", \"".$vSearch."\")'  >";
		$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\", \"".$vSearch."\")' >";
	} else {
		$next = " <img border='0' src='$dNextIc'  style:'cursor:pointer'>";
		$last = " <img border='0' src='$dLastIc'  style:'cursor:pointer'>";
	}

	if ($maxPage>=1 AND $type=='nav') {
		// print the navigation link
		echo $first . $prev . $nav . $next . $last;
	}elseif ($maxPage>=1 AND $type=='con') {
		//Build the header
/*		echo "<table border='1' width='70%'>";
		echo "	<tr>";
		echo "		<th width='50%'>Id</th>";
		echo "		<th>State</th>";
		echo "	</tr>";
*/
		echo "<table width='100%'  border='0' cellpadding='0' cellspacing='1' class='tab'>
                    <tr align='center'>
                      <td width='17%' class='bdiv_r'><b>Transaction Number </b></td>
                      <td width='17%' class='bdiv_r'><b>Document Number</b></td>
					  <td width='17%' class='bdiv_r' height='20'><b>Transaction Date</b></td>
                      <td width='17%' class='bdiv_r'><b>Warehouse</b></td>
                      <td width='17%' class='bdiv_r'><b>Inventory In Type</b></td>
                      <td ><b>Status</b></td>
                    </tr>";
		//Get all the rows
		
		$query = $sp->spSelectInventoryIn($database,$offset, $RPP, $vSearch);
		$num=$query->num_rows;

		//Echo each row
		$cnt = 0;
		while($row = $query->fetch_object()) {
			 
				$cnt ++;
				($cnt % 2) ? $alt = '' : $alt = 'bgEFF0EB';
				$txnno = $row->TxnNo;
				$id = $row->TxnID;
				$docno = $row->DocumentNo;
				$txndate = $row->TxnDate;
				$warehouse = $row->Warehouse;			
				$invintype = $row->InvInType;
				$status = $row->Status;

		  echo "<tr align='center' class='$alt'>
					  <td width='17%' height='20' class='borderBR'>
					  <a href='index.php?pageid=30.1&tid=$id' class='txtnavgreenlink'>$txnno</a></td>
					  <td width='17%' class='borderBR'> $docno </td>
					  <td width='17%' class='borderBR'> $txndate </td>
					  <td width='17%' class='borderBR'>$warehouse </td>
					  <td width='17%' class='borderBR'> $invintype </td>
					  <td class='borderBR'>$status </td>
				  </tr>";
		  }


		//Close table
		echo "</table>";

	}elseif ($maxPage>=1 AND $type=='num') {
		$offset = $offset + 1;
		
		
		$offset2 = $offset + 9;
		echo $offset." to  ";
		if ( $offset2 <= $RPP)
		{
			echo $offset2;
		}
		else
		{
			echo $num;
		}
		echo " Total Records: ".$num ;
		
	}
	else{
		echo "Table doesn't contain records.";
	}
}



//Close the db connection
mysql_close();
?>
