<?php
include "../../../initialize.php";
global $database;
//Logic Pagination
function AddPagination($RPP, $num, $pageNum)
{
	$PrevIc=		"images/bprv.gif";
	$FirstIc=		"images/bfrst.gif";
	$NextIc=		"images/bnxt.gif";
	$LastIc=		"images/blst.gif";
	
	$dPrevIc=		"images/dprv.gif";
	$dFirstIc=		"images/dfrst.gif";
	$dNextIc=		"images/dnxt.gif";
	$dLastIc=		"images/dlst.gif";
	
	if ($num > 0) {
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
					} elseif ($dif==4){
						$page=$page-2;
					} elseif ($dif==5){
						$page=$page-1;
					}
			} elseif ($page <=1) {
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
		
		if ($upper > $maxPage) {
			$upper = $maxPage;
		}
		
		//These are the numbered links
		for($page; $page <=  $upper; $page++) {
	
			if ($page == $pageNum){
				//If this is the current page then disable the link
				$nav .= " <font color='red'>$page</font> ";
			} else {
				//If this is a different page then link to it
				$nav .= " <a style:'cursor:pointer' onclick='showPage(\"".$page."\" )'>$page</a> ";
			}
		}
		
		
		//These are the button links for first/previous enabled/disabled
		if ($pageNum > 1){
			$page  = $pageNum - 1;
			$prev  = "<img border='0' src='$PrevIc' onclick='showPage(\"".$page."\")' style:'cursor:pointer'> ";
			$first = "<img border='0' src='$FirstIc' onclick='showPage(\"1\")'  style:'cursor:pointer'> ";
		} else {
			$prev  = "<img border='0' src='$dPrevIc'  style:'cursor:pointer'> ";
			$first = "<img border='0' src='$dFirstIc'   style:'cursor:pointer'> ";
		}
		
		//These are the button links for next/last enabled/disabled
		if ($pageNum < $maxPage AND $upper <= $maxPage) {
			$page = $pageNum + 1;
			$next = " <img border='0' src='$NextIc' onclick='showPage(\"".$page."\")'  >";
			$last = " <img border='0' src='$LastIc'  onclick='showPage(\"".$maxPage."\")' >";
		} else {
			$next = " <img border='0' src='$dNextIc'  style:'cursor:pointer'>";
			$last = " <img border='0' src='$dLastIc'  style:'cursor:pointer'>";
		}
		
		if ($maxPage >= 1) {
			// print the navigation link
			return  $first . $prev . $nav . $next . $last;
		}
	}
}



if($_POST['request'] == 'Fetch Data'){
	
	$pageNum    = 1;
	$offset = 0;
	$RPP = 8;
	
	if(isset($_POST['page'])){
		$pageNum    = $_POST['page'];
		$offset 	= $_POST['page'];
	}
	
	$offset = ($pageNum - 1) * $RPP;
	$q = $database->execute("select * from tpi_fco");
	$num = $q->num_rows;
	$pagination = AddPagination($RPP, $num, $pageNum);
	
	$result['pagination'] = array('page'=>$pagination);
	
	
	$dbr = $database->execute("select * from tpi_fco order by Period limit ".$offset.", ".$RPP);
	if($dbr->num_rows){
		while($r = $dbr->fetch_object()){
			$ID 		= $r->ID;
			$Year       = $r->Year;
			$Period     = $r->Period;
			$DateFrom   = date("m/d/Y",strtotime($r->DateFrom));
			$DateTo		= date("m/d/Y",strtotime($r->DateTo));

			//Fetch Data
			$result['fetch_data'][] = array('Year' => $Year, 'Period' => $Period , 'DateFrom' => $DateFrom , 'DateTo' => $DateTo, 'ID'=>$ID);
		}
			$result['result'] = array('response' => 'Success');
	}else{
			$result['result'] = array('response' => 'Failed');
	}
			die(json_encode($result));
}

if($_POST['request']=="save"){



$validation = $_POST['validation'];
$year 		= $_POST['year'];
$from_date 	= date("Y-m-d",strtotime($_POST['from_date']));
$to_date 	= date("Y-m-d",strtotime($_POST['to_date']));
$period 	= $_POST['period'];
if($validation == 1){
	$database->execute("Insert into tpi_fco (Year,Period,DateFrom,DateTo, Changed, ModifiedDate, LastModifiedDate) values ('".$year."',".$period.",'".$from_date."','".$to_date."',1,now(),now())");
}else{
	$database->execute("update tpi_fco set Year = '".$year."', Period ='".$period."', DateFrom = '".$from_date."', DateTo = '".$to_date."',Changed = 1, LastModifiedDate = 1 Where ID = ".$_POST['ID']);
}

	$pageNum    = 1;
	$offset = 0;
	$RPP = 8;
	$q = $database->execute("select * from tpi_fco");
	$num = $q->num_rows;
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);

	$dbr = $database->execute("select * from tpi_fco order by period limit ".$offset.", ".$RPP);
	if($dbr->num_rows){
		while($r = $dbr->fetch_object()){
			$ID 		= $r->ID;
			$Year       = $r->Year;
			$Period     = $r->Period;
			$DateFrom   = date("m/d/Y",strtotime($r->DateFrom));
			$DateTo		= date("m/d/Y",strtotime($r->DateTo));

			//Fetch Data
			$result['fetch_data'][] = array('Year' => $Year, 'Period' => $Period , 'DateFrom' => $DateFrom , 'DateTo' => $DateTo, 'ID'=>$ID);
		}
			$result['result'] = array('response' => 'Success');
	}else{
			$result['result'] = array('response' => 'Failed');
	}
			die(json_encode($result));
	
	
}

if($_POST['request']=='delete'){

	$database->execute("delete from tpi_fco where ID = ".$_POST['ID']);
	$pageNum    = 1;
	$offset = 0;
	$RPP = 8;
	
	$q = $database->execute("select * from tpi_fco limit ".$offset.", ".$RPP);
	$num = $q->num_rows;
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);

	
	$dbr = $database->execute("select * from tpi_fco");
	if($dbr->num_rows){
		while($r = $dbr->fetch_object()){
			$ID 		= $r->ID;
			$Year       = $r->Year;
			$Period     = $r->Period;
			$DateFrom   = date("m/d/Y",strtotime($r->DateFrom));
			$DateTo		= date("m/d/Y",strtotime($r->DateTo));

			//Fetch Data
			$result['fetch_data'][] = array('Year' => $Year, 'Period' => $Period , 'DateFrom' => $DateFrom , 'DateTo' => $DateTo, 'ID'=>$ID);
		}
		$result['result'] = array('response' => 'Success');
	}else{
		$result['result'] = array('response' => 'Failed');
	}
		die(json_encode($result));
	
}

if($_POST['request']=='getting information'){
	
	$q = $database->execute('select * from tpi_fco where ID = '.$_POST['ID']);
	if($q->num_rows){
		while($r = $q->fetch_object()){
			$ID 		= $r->ID;
			$Year       = $r->Year;
			$Period     = $r->Period;
			$DateFrom   = date("m/d/Y",strtotime($r->DateFrom));
			$DateTo		= date("m/d/Y",strtotime($r->DateTo));

			//Fetch Data
			$result['fetch_data'][] = array('Year' => $Year, 'Period' => $Period , 'DateFrom' => $DateFrom , 'DateTo' => $DateTo, 'ID'=>$ID);
		}
		$result['result'] = array('response' => 'Success');
		}else{
			$result['result'] = array('response' => 'Failed');
		}
			die(json_encode($result));
}

if($_POST['request']=='search'){
	
	$pageNum   	 = 1;
	$RPP		 = 8;
	$offset = ($pageNum - 1) * $RPP;
	
	if($_POST['start'] != "" && $_POST['end'] != ""){
		
		$start = date("Y-m-d",strtotime($_POST['start']));
		$end   = date("Y-m-d",strtotime($_POST['end']));
		
		$between_pagination = " where DateTo between '".$start."' and '".$end."' ";
	}
	
	$q   = $database->execute("select * from tpi_fco ".$between_pagination);
	$num = $q->num_rows;
	
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);
	
	
	$q = $database->execute("SELECT * FROM tpi_fco ".$between_pagination." 
								".$between." limit ".$offset.", ".$RPP);
	if($q->num_rows){
	while($r = $q->fetch_object()){
		$ID 		= $r->ID;
		$Year       = $r->Year;
		$Period     = $r->Period;
		$DateFrom   = date("m/d/Y",strtotime($r->DateFrom));
		$DateTo		= date("m/d/Y",strtotime($r->DateTo));

		//Fetch Data
		$result['fetch_data'][] = array('Year' => $Year, 'Period' => $Period , 'DateFrom' => $DateFrom , 'DateTo' => $DateTo, 'ID'=>$ID);
	}
	$result['result'] = array('response' => 'Success');
	}else{
		$result['result'] = array('response' => 'Failed');
	}
		die(json_encode($result));
}

?>