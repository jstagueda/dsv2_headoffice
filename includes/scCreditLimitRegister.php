<?php 

function CreditLimitRegister($branch, $sfmlevel, $sfmfrom, $sfmto, $datefrom, $dateto, $page, $total, $istotal){
	
	global $database;
	
	$start = ($page > 1) ? ($page - 1) * $total : 0;
	$limit = ($istotal) ? "" : " LIMIT $start, $total";
	
	$sfmrange = ($sfmfrom == 0 AND $sfmto == 0) ? "" : " AND ((c.ID BETWEEN $sfmfrom AND $sfmto) OR (c.ID BETWEEN $sfmto AND $sfmfrom))";
	$sfmlevel = ($sfmlevel == 0) ? "" : " AND c.CustomerTypeID = $sfmlevel";
	
	$query = $database->execute("SELECT * 
								FROM(SELECT 
									TRIM(c.Code) CustomerCode,
									c.Name CustomerName,
									IFNULL(crh.CreditLimit, tcd.ApprovedCL) CreditLimit,
									DATE_FORMAT(IFNULL(crh.DateUpdate, IFNULL(c.EnrollmentDate, tcd.EnrollmentDate)), '%M %d, %Y') DateUpdate
								FROM customer c 
								INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
								LEFT JOIN creditlimithistory crh ON crh.CustomerID = c.ID
									AND LOCATE(CONCAT('-', b.ID), crh.HOGeneralID) > 0
								LEFT JOIN tpi_credit tcd ON tcd.CustomerID = c.ID
									AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
								INNER JOIN sfm_level sf ON sf.codeID = c.CustomerTypeID
								WHERE b.ID = $branch
								AND ((DATE(IFNULL(crh.DateUpdate, IFNULL(c.EnrollmentDate, tcd.EnrollmentDate))) BETWEEN '$datefrom' AND '$dateto')
									OR (DATE(IFNULL(crh.DateUpdate, IFNULL(c.EnrollmentDate, tcd.EnrollmentDate))) BETWEEN '$dateto' AND '$datefrom'))
								$sfmrange
								$sfmlevel) atbl
								ORDER BY DateUpdate
								$limit");
	
	return $query;
	
}

function SalesForce($branch, $sfmlevel, $searched){
	
	global $database;
	
	$query = $database->execute("SELECT 
									c.ID CustomerID, 
									TRIM(c.Code) CustomerCode, 
									c.Name CustomerName
								FROM customer c
								INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
								WHERE (c.Code LIKE '%$searched%' OR c.Name LIKE '%$searched%')
								ORDER BY c.Name
								WHERE b.ID = $branch
								LIMIT 10");
	return $query;
}

?>