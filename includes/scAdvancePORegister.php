<?php

function customerrange($database, $searched, $branchid){
    $query = $database->execute("SELECT c.Name, c.Code, c.ID
							FROM customer c
							WHERE c.CustomerTypeID = 1
							AND ((c.Name LIKE '$searched%') OR (c.Code LIKE '$searched%'))
							AND LOCATE('-$branchid', c.HOGeneralID) > 0
							ORDER BY c.Name
							LIMIT 10");
    return $query;
}

function advancepo($database, $datefrom, $dateto, $customerfrom, $customerto, $istotal, $page, $total, $branchID){
    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    $customerrange = ($customerfrom == 0 AND $customerto == 0)?"":" AND ((igs.ID BETWEEN $customerfrom AND $customerto)
                                                                    OR (igs.ID BETWEEN $customerto AND $customerfrom))";
    $datefrom = date("Y-m-d", strtotime($datefrom));
    $dateto = date("Y-m-d", strtotime($dateto));
	
    $query = $database->execute("SELECT
								b.Name BranchName,
								DATE_FORMAT(apo.TxnDate, '%b%y') Campaign,
								igs.Code IGSCode, igs.Name IGSName,
								CONCAT('ADVPO', LPAD(apo.ID, 8, 0)) AdvancePONo,
								-- CAST(CONCAT('ADVPO', REPEAT('0', (8-LENGTH(apo.ID))), apo.ID) AS CHAR) AdvancePONo,
								DATE_FORMAT(apo.TxnDate, '%m/%d/%Y') PODate,
								apo.GrossAmount CSP,
								apo.BasicDiscount,
								(apo.GrossAmount - apo.BasicDiscount) DGS,
								(apo.TotalCFT + apo.TotalNCFT) DGSLessCPI,
								ibm.Code IBMCode, ibm.Name IBMName, s.Name StatusName, 
								CONCAT(e.FirstName, ' ', e.MiddleName, ' ', e.LastName) Encoder
							FROM advancepo apo
							INNER JOIN branch b ON b.ID = apo.BranchID
							INNER JOIN customer igs ON igs.ID = apo.CustomerID
							AND LOCATE('-$branchID', igs.HOGeneralID) > 0
							INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = igs.ID
								AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = igs.ID AND LOCATE('-$branchID', HOGeneralID) > 0)
							INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
							AND LOCATE('-$branchID', ibm.HOGeneralID) > 0
							INNER JOIN `status` s ON s.ID = apo.StatusID
							LEFT JOIN employee e ON e.ID = apo.CreatedBy
							WHERE DATE_FORMAT(apo.TxnDate, '%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'
							AND LOCATE('-$branchID', apo.HOGeneralID) > 0
							AND b.ID = $branchID
							$customerrange
							ORDER BY apo.TxnDate ASC
							$limit");
    return $query;
}

function countdate($date){
    global $database, $customerfrom, $customerto, $page, $total, $branchID;
    $advancepo = advancepo($database, $date, $date, $customerfrom, $customerto, false, $page, $total, $branchID);        
    return $advancepo->num_rows;
}
?>
