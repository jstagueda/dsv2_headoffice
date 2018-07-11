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
/*Logic Part*/
if($_POST['request'] == 'Fetch Data'){
	$pageNum    = 1;
	$offset = 0;
	$RPP = 8;
	
	if(isset($_POST['page'])){
		$pageNum    = $_POST['page'];
		$offset 	= $_POST['page'];
	}
	$between = "";
	$between_pagination = "";
	if($_POST['start'] != "" && $_POST['end'] != ""){
		
		$start = date("Y-m-d",strtotime($_POST['start']));
		$end   = date("Y-m-d",strtotime($_POST['end']));
		
		$between_pagination = " where Date_From between '".$start."' and '".$end."' ";
		$between = " where hld.Date_From between '".$start."' and '".$end."' ";
	}
	$offset = ($pageNum - 1) * $RPP;
	
	$q = $database->execute("select * from holiday ".$between_pagination);
	$num = $q->num_rows;
	//$
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);
	
	$dbr = $database->execute(" select hld.*, s.Name StatusName from holiday hld 
								inner join status s on hld.StatusID = s.ID ".$between."
								limit ".$offset.", ".$RPP );
	if($dbr->num_rows){
		while($r = $dbr->fetch_object()){
			$ID	= $r->ID;			
			$Description	= $r->Description;			
			$Date 			= date("m/d/Y",strtotime($r->Date));
			$Date_From  	= date("m/d/Y",strtotime($r->Date_From)); 
			$StatusName 	= $r->StatusName;
			//Fetch Data
			$result['fetch_data'][] = array('Description' => $Description, 'Date' => $Date , 'Date_From' => $Date_From , 'StatusName' => $StatusName,'ID'=>$ID);
		}
			$result['result'] = array('response' => 'Success');
	}else{
			$result['result'] = array('response' => 'Failed');
	}
			
	//pagination 
	die(json_encode($result));
	
}

if($_POST['request'] == 'Fetch Data Holiday'){
	if(isset($_POST['page'])){
		$pageNum    = $_POST['page'];
		$offset 	= $_POST['page'];
	}
	$between = "";
	if($_POST['start'] != "" && $_POST['end'] != ""){
		
		$start = date("Y-m-d",strtotime($_POST['start']));
		$end   = date("Y-m-d",strtotime($_POST['end']));
		
		$between = " where hld.Date_From between '".$start."' and '".$end."' ";
	}
	$dbr = $database->execute(" select hld.*, s.Name StatusName from holiday hld 
								inner join status s on hld.StatusID = s.ID ".$between);
	if($dbr->num_rows){
		while($r = $dbr->fetch_object()){
			$ID	= $r->ID;			
			$Description	= $r->Description;			
			$Date 			= date("m/d/Y",strtotime($r->Date));
			$Date_From  	= date("m/d/Y",strtotime($r->Date_From)); 
			$StatusName 	= $r->StatusName;
			//Fetch Data
			$result['fetch_data'][] = array('Description' => $Description, 'Date' => $Date , 'Date_From' => $Date_From , 'StatusName' => $StatusName,'ID'=>$ID);
		}
			$result['result'] = array('response' => 'Success');
	}else{
			$result['result'] = array('response' => 'Failed');
	}
			
	//pagination 
	die(json_encode($result));
	
}

if($_POST['request']=="save"){
	//print_r($_POST);
	//die();	
	
	$holiday	= $_POST['holiday'];
	$from_date  = date("Y-m-d", strtotime($_POST['from_date']));
	$to_date 	= date("Y-m-d", strtotime($_POST['to_date']));
	if($_POST['branch_close'] == 1){ $branch_close = 29; }else{ $branch_close = 30; }
	
	if($_POST['validation'] == 1){
		//save transaction

		$qvalidate = $database->execute("select * from holiday where Description ='".$holiday."'");
		if($qvalidate->num_rows){
			$result['result'] = array('response' => 'Code Already Exist');
		}else{
			$database->execute("INSERT into holiday (Description, Date, Date_From, StatusID, EnrollmentDate, LastModifiedDate, Changed) VALUES
							('".$holiday."','".$to_date."','".$from_date."',".$branch_close.",NOW(),NOW(),1)");
			$result['result'] = array('response' => 'Success');
							
		}
	}else{
		$HolidayID	= $_POST['HolidayID'];	
		if($_POST['branch_close'] == 1){ $branch_close = 29; }else{ $branch_close = 30; }
		$database->execute("update holiday set Description = '".$holiday."', Date='".$to_date."', Date_From='".$from_date."',StatusID=".$branch_close.",LastModifiedDate=NOW(), Changed=1 where ID=".$HolidayID);
		$result['result'] = array('response' => 'Success');
	}
	//fetching data
	
	$pageNum   	 = 1;
	$RPP		 = 8;
	$offset = ($pageNum - 1) * $RPP;
	
	$q 		= $database->execute("select * from holiday");
	$num 	= $q->num_rows;
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);
	
	$dbr = $database->execute(" select hld.*, s.Name StatusName, hld.StatusID statusID from holiday hld 
							    inner join status s on hld.StatusID = s.ID
								limit ".$offset.", ".$RPP);
	if($dbr->num_rows){
		while($r = $dbr->fetch_object()){
			$Description	= $r->Description;			
			$Date 			= date("m/d/Y",strtotime($r->Date));
			$Date_From  	= date("m/d/Y",strtotime($r->Date_From)); 
			$StatusName 	= $r->StatusName;
			$statusID 	= $r->statusID;
			$ID 	= $r->ID;
			//Fetch Data
			$result['fetch_data'][] = array('Description' => $Description, 'Date' => $Date , 'Date_From' => $Date_From ,
											'StatusName' => $StatusName,'ID'=>$ID, 'statusID'=>$statusID,'ID'=>$ID);
		}
			
	}else{
			$result['result'] = array('response' => 'Failed');
	}
	die(json_encode($result));
}
	




if($_POST['request']=="getting information"){
	$ID = $_POST['ID'];
	$q = $database->execute("select hld.*, s.ID StatusID, s.Name StatusName from holiday hld
							 inner join status s on hld.StatusID = s.ID
							where hld.ID = ".$ID);
	
	if($q->num_rows){
		while($r = $q->fetch_object()){
		$Description	= $r->Description;			
			$Date 			= date("m/d/Y",strtotime($r->Date));
			$Date_From  	= date("m/d/Y",strtotime($r->Date_From)); 
			$StatusName 	= $r->StatusName;
			$statusID 	= $r->statusID;
			//Fetch Data
		$result['Holiday_Data'] = array('Description' => $Description, 'Date' => $Date ,'ID'=>$ID, 'Date_From' => $Date_From , 'StatusName' => $StatusName, 'statusID'=>$statusID);
		}
		$result['holiday_result'] = array('response' => 'success');
	}else{
		$result['holiday_result'] = array('response' => 'Failed');
	}
	
	die(json_encode($result));
	
}

if($_POST['request']== 'delete'){
	$database->execute("delete from holiday where ID = ".$_POST['ID']);
	
	//fetching data
	$pageNum   	 = 1;
	$RPP		 = 8;
	$offset = ($pageNum - 1) * $RPP;
	
	$q = $database->execute("select * from holiday");
	$num = $q->num_rows;
	
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);
	
	$dbr = $database->execute(" select hld.*, s.Name StatusName, hld.StatusID statusID from holiday hld 
							    inner join status s on hld.StatusID = s.ID
								limit ".$offset.", ".$RPP);
	if($dbr->num_rows){
		while($r = $dbr->fetch_object()){
			$Description	= $r->Description;			
			$Date 			= date("m/d/Y",strtotime($r->Date));
			$Date_From  	= date("m/d/Y",strtotime($r->Date_From)); 
			$StatusName 	= $r->StatusName;
			$statusID 	= $r->statusID;
			$ID 	= $r->ID;
			//Fetch Data
			$result['fetch_data'][] = array('Description' => $Description, 'Date' => $Date , 'Date_From' => $Date_From ,
											'StatusName' => $StatusName,'ID'=>$ID, 'statusID'=>$statusID,'ID'=>$ID);
		}
			$result['holiday_result'] = array('response' => 'success');
	}else{
			$result['holiday_result'] = array('response' => 'Failed');
	}
	die(json_encode($result));
}


if($_POST['request']=='search'){
	//fetching data
	//fetching data
	$pageNum   	 = 1;
	$RPP		 = 8;
	$offset = ($pageNum - 1) * $RPP;
	/*
	$start = date("Y-m-d",strtotime($_POST['start']));
	$end   = date("Y-m-d",strtotime($_POST['end']));
	*/
	if($_POST['start'] != "" && $_POST['end'] != ""){
		
		$start = date("Y-m-d",strtotime($_POST['start']));
		$end   = date("Y-m-d",strtotime($_POST['end']));
		
		$between_pagination = " where Date_From between '".$start."' and '".$end."' ";
		$between = " where hld.Date_From between '".$start."' and '".$end."' ";
	}
	
	$q   = $database->execute("select * from holiday ".$between_pagination);
	$num = $q->num_rows;
	
	$pagination = AddPagination($RPP, $num, $pageNum);
	$result['pagination'] = array('page'=>$pagination);
	
	
	$dbr = $database->execute(" select hld.*, s.Name StatusName, hld.StatusID statusID from holiday hld 
							    inner join status s on hld.StatusID = s.ID
								".$between." limit ".$offset.", ".$RPP);
	if($dbr->num_rows){
		while($r = $dbr->fetch_object()){
			$Description	= $r->Description;			
			$Date 			= date("m/d/Y",strtotime($r->Date));
			$Date_From  	= date("m/d/Y",strtotime($r->Date_From)); 
			$StatusName 	= $r->StatusName;
			$statusID 	= $r->statusID;
			$ID 	= $r->ID;
			//Fetch Data
			$result['fetch_data'][] = array('Description' => $Description, 'Date' => $Date , 'Date_From' => $Date_From ,
											'StatusName' => $StatusName,'ID'=>$ID, 'statusID'=>$statusID,'ID'=>$ID);
		}
			$result['holiday_result'] = array('response' => 'success');
	}else{
			$result['holiday_result'] = array('response' => 'Failed');
	}
	die(json_encode($result));
}
?>