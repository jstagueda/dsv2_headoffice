<?php 

function provisionalreceipt($datefrom, $dateto, $sfmlevel, $customerfrom, $customerto, $page, $total, $istotal, $branch){
	
	global $database;
	
	$start = ($page > 1) ? ($page - 1) * $total : 0;
	$limit = ($istotal) ? "" : " LIMIT $start, $total";
	
	$customerquery = ($customerfrom == 0 AND $customerto == 0) ? "" : " AND c.ID BETWEEN $customerfrom AND $customerto";
	
	$query = $database->execute("SELECT
								DATE_FORMAT(pr.TxnDate, '%m/%d/%Y') PRDate,
								pr.DocumentNo PRNumber,
								CONCAT('PR', LPAD(pr.ID, 8, 0)) TransactionNumber,
								TRIM(c.Code) CustomerCode,
								c.Name CustomerName,
								prc.CheckNumber CheckNumber,
								DATE_FORMAT(prc.CheckDate, '%m/%d/%Y') CheckDate,
								prc.TotalAmount,
								s.Name StatusName
							FROM provisionalreceiptcheck prc
							INNER JOIN branch b ON b.ID = SPLIT_STR(prc.HOGeneralID, '-', 2)
							INNER JOIN provisionalreceipt pr ON pr.ID = prc.ProvisionalReceiptID
								AND LOCATE(CONCAT('-', b.ID), prc.HOGeneralID) > 0
							INNER JOIN customer c ON c.ID = pr.CustomerID
								AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
							INNER JOIN `status` s ON s.ID = pr.StatusID
							WHERE c.CustomerTypeID = 1
							AND DATE(pr.TxnDate) BETWEEN '$datefrom' AND '$dateto'
							AND b.ID = $branch
							$customerquery
							$limit");
							
	return $query;
	
}

function provisionalreceiptTotal($datefrom, $dateto, $sfmlevel, $customerfrom, $customerto, $branch){
	
	global $database;
	
	$customerquery = ($customerfrom == 0 AND $customerto == 0) ? "" : " AND c.ID BETWEEN $customerfrom AND $customerto";
	
	$query = $database->execute("SELECT
								SUM(prc.TotalAmount) TotalAmount
							FROM provisionalreceiptcheck prc
							INNER JOIN branch b ON b.ID = SPLIT_STR(prc.HOGeneralID, '-', 2)
							INNER JOIN provisionalreceipt pr ON pr.ID = prc.ProvisionalReceiptID
								AND LOCATE(CONCAT('-', b.ID), prc.HOGeneralID) > 0
							INNER JOIN customer c ON c.ID = pr.CustomerID
								AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
							INNER JOIN `status` s ON s.ID = pr.StatusID
							WHERE c.CustomerTypeID = 1
							AND DATE(pr.TxnDate) BETWEEN '$datefrom' AND '$dateto'
							AND b.ID = $branch
							$customerquery");
							
	return $query;
	
}


?>