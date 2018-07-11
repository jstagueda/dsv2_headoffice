<?php

function ibmrange($database, $searched, $branch){    
    $query = $database->execute(" SELECT c.customerid ID, c.code Code, c.name  Name
								FROM customer c
								WHERE IF($branch = '', ((c.Name LIKE '$searched%') OR (c.Code LIKE '$searched%')) , 
										  c.BranchID = $branch AND
										  ((c.Name LIKE '%$searched%') OR (c.Code LIKE '%$searched%'))
									    )
                                LIMIT 10");
    return $query;
}

function campaign($database){
    $query = $database->execute("SELECT * FROM tpi_sfasummary GROUP BY CampaignCode ORDER BY EnrollmentDAY");
    return $query;
}


function getbranchlist($database,$status, $istotal, $page, $total, $branch,$branch2, $cmp)
{
    $start = ($page > 1)?($page - 1)*$total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
	$branch = ($branch == '' )?"":"AND (b.ID = $branch2 )";
	
	if($status != '' && $status == 'BMLIST' )
	{
										
	    $query = $database->execute(" 
	                                   SELECT bcode , lastDAY,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 1,'bgcolor=red','' )) a1,  IF(lastDAY = '','',IF(DAY(lastDAY) >= 2,'bgcolor=red','' )) a2,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 3,'bgcolor=red','' )) a3,  IF(lastDAY = '','',IF(DAY(lastDAY) >= 4,'bgcolor=red','' )) a4,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 5,'bgcolor=red','' )) a5,  IF(lastDAY = '','',IF(DAY(lastDAY) >= 6,'bgcolor=red','' )) a6,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 7,'bgcolor=red','' )) a7,  IF(lastDAY = '','',IF(DAY(lastDAY) >= 8,'bgcolor=red','' )) a8,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 9,'bgcolor=red','' )) a9,  IF(lastDAY = '','',IF(DAY(lastDAY) >= 10,'bgcolor=red','' )) a10,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 11,'bgcolor=red','' )) a11, IF(lastDAY = '','',IF(DAY(lastDAY) >= 12,'bgcolor=red','' )) a12,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 13,'bgcolor=red','' )) a13, IF(lastDAY = '','',IF(DAY(lastDAY) >= 14,'bgcolor=red','' )) a14, 
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 15,'bgcolor=red','' )) a15, IF(lastDAY = '','',IF(DAY(lastDAY) >= 16,'bgcolor=red','' )) a16,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 17,'bgcolor=red','' )) a17, IF(lastDAY = '','',IF(DAY(lastDAY) >= 17,'bgcolor=red','' )) a18,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 19,'bgcolor=red','' )) a19, IF(lastDAY = '','',IF(DAY(lastDAY) >= 20,'bgcolor=red','' )) a20,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 21,'bgcolor=red','' )) a21, IF(lastDAY = '','',IF(DAY(lastDAY) >= 22,'bgcolor=red','' )) a22,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 23,'bgcolor=red','' )) a23, IF(lastDAY = '','',IF(DAY(lastDAY) >= 24,'bgcolor=red','' )) a24,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 25,'bgcolor=red','' )) a25, IF(lastDAY = '','',IF(DAY(lastDAY) >= 26,'bgcolor=red','' )) a26,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 27,'bgcolor=red','' )) a27, IF(lastDAY = '','',IF(DAY(lastDAY) >= 28,'bgcolor=red','' )) a28,
											   IF(lastDAY = '','',IF(DAY(lastDAY) >= 29,'bgcolor=red','' )) a29, IF(lastDAY = '','',IF(DAY(lastDAY) >=30,'bgcolor=red','&nbsp' )) a30,
											   IF(lastDAY = '','&nbsp',IF(DAY(lastDAY) >= 31,'bgcolor=red','' )) a31
											   
										FROM
									    (
										SELECT b.code bcode, 
											   IFNULL((
												 SELECT s.Filedate 
											 FROM SID_Files s
											 WHERE DATE_FORMAT(s.Filedate,'%b%y') = '$cmp'
											  AND s.branch = b.code
											  ORDER BY s.FileDATE DESC Limit 1
											  
											   ),'') lastDAY
										FROM branch b
										WHERE b.id NOT IN (1,2,3)
										and b.statusid = 1
										$branch
										) atbl 
	                               ");
								   
								   
		return $query;						   
    
	}
	else
	{
		$query = $database->execute(" 
	                                    SELECT bcode,
											   IF(a1 = '','','bgcolor=red') a1, IF(a2 = '','','bgcolor=red') a2, IF(a3 = '','','bgcolor=red') a3, IF(a4 = '','','bgcolor=red') a4,
											   IF(a5 = '','','bgcolor=red') a5, IF(a6 = '','','bgcolor=red') a6, IF(a7 = '','','bgcolor=red') a7, IF(a8 = '','','bgcolor=red') a8,
											   IF(a9 = '','','bgcolor=red') a9, IF(a10 = '','','bgcolor=red') a10, IF(a11 = '','','bgcolor=red') a11, IF(a12 = '','','bgcolor=red') a12,
											   IF(a13 = '','','bgcolor=red') a13, IF(a14 = '','','bgcolor=red') a14, IF(a15 = '','','bgcolor=red') a15, IF(a16 = '','','bgcolor=red') a16,
											   IF(a17 = '','','bgcolor=red') a17, IF(a18 = '','','bgcolor=red') a18, IF(a19 = '','','bgcolor=red') a19, IF(a20 = '','','bgcolor=red') a20,
											   IF(a21 = '','','bgcolor=red') a21, IF(a22 = '','','bgcolor=red') a22, IF(a23 = '','','bgcolor=red') a23, IF(a24 = '','','bgcolor=red') a24,
											   IF(a25 = '','','bgcolor=red') a25, IF(a26 = '','','bgcolor=red') a26, IF(a27 = '','','bgcolor=red') a27, IF(a28 = '','','bgcolor=red') a28,
											   IF(a29 = '','','bgcolor=red') a29, IF(a30 = '','','bgcolor=red') a30, IF(a31 = '','','bgcolor=red') a31   
										FROM
										(
											SELECT b.code bcode, 
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 1 AND `type` = '$status' AND branch = b.code ),'') a1,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 2 AND `type` = '$status' AND branch = b.code ),'') a2,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 3 AND `type` = '$status' AND branch = b.code ),'') a3,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 4 AND `type` = '$status' AND branch = b.code ),'') a4,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 5 AND `type` = '$status' AND branch = b.code ),'') a5,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 6 AND `type` = '$status' AND branch = b.code ),'') a6,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 7 AND `type` = '$status' AND branch = b.code ),'') a7,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 8 AND `type` = '$status' AND branch = b.code ),'') a8,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 9 AND `type` = '$status' AND branch = b.code ),'') a9,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 10 AND `type` = '$status' AND branch = b.code ),'') a10,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 11 AND `type` = '$status' AND branch = b.code ),'') a11,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 12 AND `type` = '$status' AND branch = b.code ),'') a12,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 13 AND `type` = '$status' AND branch = b.code ),'') a13,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 14 AND `type` = '$status' AND branch = b.code ),'') a14,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 15 AND `type` = '$status' AND branch = b.code ),'') a15,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 16 AND `type` = '$status' AND branch = b.code ),'') a16,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 17 AND `type` = '$status' AND branch = b.code ),'') a17,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 18 AND `type` = '$status' AND branch = b.code ),'') a18,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 19 AND `type` = '$status' AND branch = b.code ),'') a19,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 20 AND `type` = '$status' AND branch = b.code ),'') a20,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 21 AND `type` = '$status' AND branch = b.code ),'') a21,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 22 AND `type` = '$status' AND branch = b.code ),'') a22,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 23 AND `type` = '$status' AND branch = b.code ),'') a23,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 24 AND `type` = '$status' AND branch = b.code ),'') a24,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 25 AND `type` = '$status' AND branch = b.code ),'') a25,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 26 AND `type` = '$status' AND branch = b.code ),'') a26,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 27 AND `type` = '$status' AND branch = b.code ),'') a27,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 28 AND `type` = '$status' AND branch = b.code ),'') a28,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 29 AND `type` = '$status' AND branch = b.code ),'') a29,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 30 AND `type` = '$status' AND branch = b.code ),'') a30,
												  IFNULL((SELECT filedate FROM SID_Files WHERE DATE_FORMAT(Filedate,'%b%y') = '$cmp' AND DAY(Filedate) = 31 AND `type` = '$status' AND branch = b.code ),'') a31
												  
											FROM branch b
											WHERE b.id NOT IN (1,2,3)
											AND b.statusid = 1
											$branch
										) atbl
	                               ");
								   			   
		return $query;					
	}
}



function AddPagination($RPP, $num, $pageNum, $customertype){
    $PrevIc=		"images/bprv.gif";
    $FirstIc=		"images/bfrst.gif";
    $NextIc=		"images/bnxt.gif";
    $LastIc=		"images/blst.gif";

    $dPrevIc=		"images/dprv.gif";
    $dFirstIc=		"images/dfrst.gif";
    $dNextIc=		"images/dnxt.gif";
    $dLastIc=		"images/dlst.gif";
	
    if($num > 0) {
        //Determine the maxpage and the offset for the query
        $maxPage = ceil($num/$RPP);
        $offset = ($pageNum - 1) * $RPP;
        //Initiate the navigation bar
        $nav  = '';
        //get low end
        $page = $pageNum - 3;
        //get upperbound
        $upper =$pageNum + 3;
        if($page <= 0){
            $page = 1;
        }
        if($upper > $maxPage){
            $upper = $maxPage;
        }

        //Make sure there are 7 numbers (3 before, 3 after and current
        if($upper-$page < 6){

            //We know that one of the page has maxed out
            //check which one it is
            //echo "$upper >=$maxPage<br>";
            if($upper >= $maxPage){
                //the upper end has maxed, put more on the front end
                //echo "to begining<br>";
                $dif = $maxPage - $page;
                //echo "$dif<br>";
                if($dif == 3){
                   $page = $page - 3;
                }elseif ($dif == 4){
                    $page = $page - 2;
                }elseif ($dif == 5){
                    $page = $page - 1;
                }
                
            }elseif ($page <= 1){
                //its the low end, add to upper end
                //echo "to upper<br>";
                $dif = $upper-1;

                if ($dif == 3){
                    $upper = $upper + 3;
                }elseif ($dif == 4){
                    $upper = $upper + 2;
                }elseif ($dif == 5){
                    $upper = $upper + 1;
                }
            }
        }

        if($page <= 0) {
            $page = 1;
        }

        if($upper > $maxPage) {
            $upper = $maxPage;
        }

        //These are the numbered links
        for($page; $page <=  $upper; $page++) {

            if($page == $pageNum){
                //If this is the current page then disable the link
                $nav .= " <font color='red'>$page</font> ";
            }else{
                //If this is a different page then link to it
                $nav .= " <a style='cursor:pointer;' onclick=\"return showPage(".$page.", '$customertype')\">$page</a> ";
            }
        }


        //These are the button links for first/previous enabled/disabled
        if($pageNum > 1){
            $page  = $pageNum - 1;
            $prev  = "<img border='0' src='$PrevIc' onclick=\"return showPage(".$page.", '$customertype')\" style='cursor:pointer;'> ";
            $first = "<img border='0' src='$FirstIc' onclick=\"return showPage(1, '$customertype')\"  style='cursor:pointer;'> ";
        }else{
            $prev  = "<img border='0' src='$dPrevIc'  style='cursor:pointer;'> ";
            $first = "<img border='0' src='$dFirstIc'   style='cursor:pointer;'> ";
        }

        //These are the button links for next/last enabled/disabled
        if($pageNum < $maxPage AND $upper <= $maxPage) {
            $page = $pageNum + 1;
            $next = " <img border='0' src='$NextIc' style='cursor:pointer;' onclick=\"return showPage(".$page.", '$customertype')\" >";
            $last = " <img border='0' src='$LastIc' style='cursor:pointer;' onclick=\"return showPage(".$maxPage.", '$customertype')\" >";
        }else{
            $next = " <img border='0' src='$dNextIc' style='cursor:pointer;'>";
            $last = " <img border='0' src='$dLastIc' style='cursor:pointer;'>";
        }

        if($maxPage >= 1){
            // print the navigation link
            return  $first . $prev . $nav . $next . $last;
        }
    }
}
?>
