<?php

function customerrange($database, $searched, $branch){
    $query = $database->execute("SELECT c.* FROM customer c
								INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
                                INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
									AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
                                WHERE c.CustomerTypeID = 1
								AND b.ID = $branch
                                AND (c.Name LIKE '$searched%' OR c.Code LIKE '$searched%')
                                ORDER BY c.Name LIMIT 10");
    return $query;
}


function dealersales($database, $dealerfrom, $dealerto, $datefrom, $dateto, $po, $amount, $istotal, $page, $total, $branch){
    $datefrom = date("Y-m-d", strtotime($datefrom));
    $dateto = date("Y-m-d", strtotime($dateto));
    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    
    $dealerrange = ($dealerfrom == 0 AND $dealerto == 0)?"":"AND ((c.ID BETWEEN $dealerfrom AND $dealerto) OR (c.ID BETWEEN $dealerto AND $dealerfrom))";
    $query = $database->execute("SELECT *
                        FROM (SELECT
                                c.Code,
                                c.Name,
                                SUM(si.NetAmount)    Invoice,
                                (SUM(si.NetAmount) - SUM(si.TotalCPI))    InvoiceLessCPI,
                                (SUM(si.GrossAmount) - SUM(si.TotalCPI))    PO,
                                SUM(si.GrossAmount) CSP,
                                (SUM(si.GrossAmount) - SUM(si.BasicDiscount)) DGS,
                                (SUM(si.GrossAmount) - SUM(si.TotalCPI)) CSPLessCPI,
                                ((SUM(si.GrossAmount) - SUM(si.BasicDiscount)) - SUM(si.TotalCPI)) DGSLessCPI
                            FROM customer c
								INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
                                INNER JOIN salesinvoice si ON si.CustomerID = c.ID
									AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
								INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
									AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
									AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
                            WHERE DATE_FORMAT(si.TxnDate, '%Y-%m-%d') BETWEEN '$datefrom'
                                AND '$dateto'
                                AND c.CustomerTypeID = 1
								AND b.ID = $branch
                                $dealerrange
                            GROUP BY c.ID
                            ORDER BY  si.TxnDate, ribm.IBMID) tbl
                        WHERE PO >= $po
                        $limit");
    return $query;
}

?>
