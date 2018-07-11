<?php

function ibmrange($database, $searched, $branch){     
    $query = $database->execute(" SELECT c.customerid ID, c.code Code, c.name  Name
								FROM customer c
								WHERE IF($branch = '', ((c.Name LIKE '$searched%') OR (c.Code LIKE '$searched%')) , 
										  c.BranchID = $branch AND
										  ((c.Name LIKE '%$searched%') OR (c.Code LIKE '%$searched%'))
									    )
							    AND c.AccountType = 'H'
								#and c.customertypeid = 2
                                LIMIT 10");
    return $query;
}

function campaign($database){
    $query = $database->execute("SELECT * FROM tpi_sfasummary GROUP BY CampaignCode ORDER BY EnrollmentDate");
    return $query;
}

function getconsolidatednetworklist($database, $HOGeneralID)
{
    $query = $database->execute(" SELECT b.code bcode, c.HOGeneralID , c.Code ccode , c.Name cnamne, IF(c.StatusID = 5 ,'TERMINATED','ACTIVE') statusid,
									s.desc_code leveltype, net.manager_code networkcode, IF(sfm.Vatable='0','NVAT','VAT') vatable,  c.AccountType,
								   IF(sfm.withOR='0','NO','YES') withOR,
									if(c.StatusID=5,'',(SELECT DATE(sfm_appthist.appointment_date) 
								     FROM sfm_appthist 
									 WHERE sfm_appthist.CustomerID = c.customerid
									 AND sfm_appthist.sfm_level = 3 ORDER BY id DESC LIMIT 1
									)) bmfdate
									FROM customer c
									INNER JOIN branch b ON b.id = c.BranchID
									INNER JOIN sfm_level s ON s.codeID = c.CustomerTypeID
									INNER JOIN sfm_manager sfm ON sfm.mid = c.customerid
									INNER JOIN sfm_manager_networks net ON net.manager_network_codecustID = c.customerid
									WHERE c.AccountType != 'H'
									AND c.HOGeneralID  = '$HOGeneralID'
									order by AccountType desc  , statusid desc , bcode
							   ");
	 return $query;						   
							   
}

function getconsolidatednetwork($database, $ibmfrom, $ibmto, $status, $istotal, $page, $total, $branch)
{
    $start = ($page > 1)?($page - 1)*$total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    $ibmrange = ($ibmfrom == 0 AND $ibmto == 0)?"":"AND ((c.customerid BETWEEN $ibmfrom AND $ibmto) OR (c.customerid BETWEEN $ibmto AND $ibmfrom))";
	
	$branch = ($branch == 0 )?"":"AND (c.BranchID = $branch )";
	
	if($status == '0' || $status == '1')
	{
		$status = 'and c.StatusID in (1,4) ';
	}
	else
	{
		$status = 'and c.StatusID in (5) ';
	}
	
	$query = $database->execute(" 
	                            select * 
								from 
								(
								Select b.code bcode, c.HOGeneralID , c.Code ccode , c.Name cnamne, IF(c.StatusID = 5 ,'TERMINATED','ACTIVE') statusid,
								   s.desc_code leveltype, net.manager_code networkcode, IF(sfm.Vatable='0','NVAT','VAT') vatable, 
								   IF(sfm.withOR='0','NO','YES') withOR,c.customerid CID,
								   (SELECT DATE(sfm_appthist.appointment_date) 
								     FROM sfm_appthist 
									 WHERE sfm_appthist.CustomerID = c.customerid
									 AND sfm_appthist.sfm_level = 2 ORDER BY id DESC LIMIT 1
									) bmcdate,
									(SELECT DATE(sfm_appthist.appointment_date) 
								     FROM sfm_appthist 
									 WHERE sfm_appthist.CustomerID = c.customerid
									 AND sfm_appthist.sfm_level = 3 ORDER BY id DESC LIMIT 1
									) bmfdate
								FROM customer c
								INNER JOIN branch b ON b.id = c.BranchID
								INNER JOIN sfm_level s ON s.codeID = c.CustomerTypeID
								INNER JOIN sfm_manager sfm ON sfm.mid = c.customerid
								INNER JOIN sfm_manager_networks net ON net.manager_network_codecustID = c.customerid
								#left join customer c2 on c2.customerid = net.manager_codecustID
								WHERE  c.AccountType = 'H' 
								$branch
								$status
								$ibmrange
								and c.customertypeid = 3
								ORDER BY c.branchid, c.HOGeneralID
								#$limit
								)a order by bcode, bmfdate desc 
	                           ");
    
     return $query;
}

function ibmsalesreportsummary($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, $istotal, $page, $total, $branch){
    $start = ($page > 1)?($page - 1)*$total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    $ibmrange = ($ibmfrom == 0 AND $ibmto == 0)?"":"AND ((ibm.ID BETWEEN $ibmfrom AND $ibmto) OR (ibm.ID BETWEEN $ibmto AND $ibmfrom))";
    $pmgx = ($pmg == 0)?"":"AND pmg.ID = $pmg";
    
    $query = $database->execute("SELECT 
									Campaign,
									CustomerID CID,
									CustomerCode IGSCode,
									CustomerName IGSName,
									PMGCode PMG,
									ROUND(CSP, 2) CSP,
									ROUND((GrossAmount - BasicDiscount), 2) DGS,	
									ROUND(AdditionalDiscount, 2) AdditionalDiscount,
									ROUND((GrossAmount - BasicDiscount - AdditionalDiscount), 2) Invoice,
									ROUND(((GrossAmount - BasicDiscount - AdditionalDiscount) - (((GrossAmount - BasicDiscount - AdditionalDiscount) / 1.12) * 0.12)), 2) InvoiceWithoutVAT
								FROM(SELECT 
										c.ID CustomerID,
										c.Code CustomerCode,
										c.Name CustomerName,
										pmg.ID PMGID,
										pmg.Code PMGCode,
										SUM(sid.UnitPrice) CSP,
										SUM(sid.TotalAmount) GrossAmount,
										SUM(IF(pmg.ID = 3, 0, sid.TotalAmount * 0.25)) BasicDiscount,
										SUM(sid.AddDiscount) AdditionalDiscount,
										DATE_FORMAT(si.TxnDate, '%b%y') Campaign
									FROM salesinvoice si 
									INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
									INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID
										AND LOCATE(CONCAT('-', b.ID), sid.HOGeneralID) > 0
									INNER JOIN customer c ON c.ID = si.CustomerID
										AND IFNULL(c.IsEmployee, 0) = 0
										AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
									INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
										AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
									INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
										AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
									INNER JOIN tpi_pmg pmg ON pmg.ID = sid.PMGID
									WHERE DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
									AND b.ID = $branch
									$ibmrange
									$pmgx
									GROUP BY sid.PMGID, c.ID) atbl
									WHERE CSP >= $IGSMinimumCSP
									ORDER BY CustomerName
									$limit");
    return $query;
}

function ibmsalesreportdetail($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, $istotal, $page, $total, $branch){
    $start = ($page > 1)?($page - 1)*$total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    $ibmrange = ($ibmfrom == 0 AND $ibmto == 0)?"":"AND ((ibm.ID BETWEEN $ibmfrom AND $ibmto) OR (ibm.ID BETWEEN $ibmto AND $ibmfrom))";
    $pmgx = ($pmg == 0)?"":"AND pmg.ID = $pmg";
    
    $query = $database->execute("SELECT 
									Campaign,
									CustomerID CID,
									CustomerCode IGSCode,
									CustomerName IGSName,
									PMGCode PMG,
									ROUND(CSP, 2) CSP,
									ROUND(BasicDiscount) BasicDiscount,
									ROUND((GrossAmount - BasicDiscount), 2) DGS,	
									ROUND(AdditionalDiscount, 2) AdditionalDiscount,
									ROUND((GrossAmount - BasicDiscount - AdditionalDiscount), 2) Invoice,
									ROUND(((GrossAmount - BasicDiscount - AdditionalDiscount) - (((GrossAmount - BasicDiscount - AdditionalDiscount) / 1.12) * 0.12)), 2) InvoiceWithoutVAT
								FROM(SELECT 
										c.ID CustomerID,
										c.Code CustomerCode,
										c.Name CustomerName,
										pmg.ID PMGID,
										pmg.Code PMGCode,
										SUM(sid.UnitPrice) CSP,
										SUM(sid.TotalAmount) GrossAmount,
										SUM(IF(pmg.ID = 3, 0, sid.TotalAmount * 0.25)) BasicDiscount,
										SUM(sid.AddDiscount) AdditionalDiscount,
										DATE_FORMAT(si.TxnDate, '%b%y') Campaign
									FROM salesinvoice si 
									INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
									INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID
										AND LOCATE(CONCAT('-', b.ID), sid.HOGeneralID) > 0
									INNER JOIN customer c ON c.ID = si.CustomerID
										AND IFNULL(c.IsEmployee, 0) = 0
										AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
									INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
										AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
									INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
										AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
									INNER JOIN tpi_pmg pmg ON pmg.ID = sid.PMGID
									WHERE DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
									AND b.ID = $branch
									$ibmrange
									$pmgx
									GROUP BY sid.PMGID, c.ID) atbl
									WHERE CSP >= $IGSMinimumCSP
									ORDER BY CustomerName
									$limit");
    return $query;
}

function pmg($database){
    $query = $database->execute("SELECT * FROM tpi_pmg WHERE ID IN (1,2,3)");
    return $query;
}

function summaryperibm($database, $ibmfrom, $ibmto, $campaign, $IGSMinimumCSP, $pmg, $istotal, $page, $total, $branch){
    $start = ($page > 1)?($page - 1)*$total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    $ibmrange = ($ibmfrom == 0 AND $ibmto == 0)?"":"AND ((ibm.ID BETWEEN $ibmfrom AND $ibmto) OR (ibm.ID BETWEEN $ibmto AND $ibmfrom))";
    $pmgx = ($pmg == 0)?"":"AND pmg.ID = $pmg";
    
    $query = $database->execute("SELECT
									IBMID,
									IBMCode,
									IBMName,
									SUM(DGS) DGS,
									COUNT(CustomerID) Actives
									FROM
									(SELECT 
										CustomerID,
										IBMID,
										IBMCode,
										IBMName,
										ROUND((GrossAmount - BasicDiscount), 2) DGS
									FROM(SELECT 
											c.ID CustomerID,
											ibm.ID IBMID,
											ibm.Code IBMCode,
											ibm.Name IBMName,
											SUM(sid.UnitPrice) CSP,
											SUM(sid.TotalAmount) GrossAmount,
											SUM(IF(pmg.ID = 3, 0, sid.TotalAmount * 0.25)) BasicDiscount
										FROM salesinvoice si 
										INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
										INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID
											AND LOCATE(CONCAT('-', b.ID), sid.HOGeneralID) > 0
										INNER JOIN customer c ON c.ID = si.CustomerID
											AND IFNULL(c.IsEmployee, 0) = 0
											AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
										INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
											AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
											AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
										INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
											AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
										INNER JOIN tpi_pmg pmg ON pmg.ID = sid.PMGID
										WHERE DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
										$ibmrange
										$pmgx
										GROUP BY c.ID) atbl) atblx
									WHERE DGS >= $IGSMinimumCSP
									GROUP BY IBMID
									ORDER BY IBMName
									$limit");
    return $query;
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
