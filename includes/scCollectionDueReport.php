<?php

function customerrange($database, $searched, $sfmlevel, $branch){

    $query = $database->execute("SELECT c.ID, TRIM(c.Code) Code, c.Name
                                FROM customer c
								INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
                                INNER JOIN tpi_rcustomeribm ribm ON c.ID = ribm.CustomerID
                                    AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
									AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
                                WHERE c.CustomerTypeID = $sfmlevel
                                    AND ((c.Code LIKE '$searched%') OR (c.Name LIKE '$searched%'))
									AND b.ID = $branch
                                ORDER BY c.Name LIMIT 10");
    return $query;

}

function collectiondue($database, $datefrom, $dateto, $sfmlevel, $sfafrom, $sfato, $creditAccountType, $istotal, $page, $total, $branch){

    $start = ($page > 1)?(($page - 1) * $total):0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    $gsurange = ($creditAccountType > 0)?"AND gsu.ID = $creditAccountType":"";
    $datefrom = date('Y-m-d', strtotime($datefrom));
    $dateto = date('Y-m-d', strtotime($dateto));

    if($sfmlevel > 1){

        $sfarange = ($sfafrom == 0 AND $sfato == 0)?"":"AND (ibm.ID BETWEEN $sfafrom AND $sfato)
                                                    OR (ibm.ID BETWEEN $sfato AND $sfafrom)";

        $query = $database->execute("SELECT
                                        ibm.Code AccountCode,
                                        ibm.Name AccountName,
                                        gsu.Name CreditAccountType,
                                        ct.Name CreditTerm,
                                        si.DocumentNo,
                                        si.OutstandingBalance TotalAmount,
                                        IFNULL(tcd.TelNo, 'N/A') ContactNo,
										DATE_FORMAT(si.EffectivityDate, '%m/%d/%Y') DueDate
                                        FROM salesinvoice si
										INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                        INNER JOIN customer c ON c.ID = si.CustomerID
											AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
                                        INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
                                            AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
											AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
                                        INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
											AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
                                        INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = ibm.ID
											AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
                                        INNER JOIN tpi_gsutype gsu ON gsu.ID = tcd.tpi_GSUTypeID
                                        INNER JOIN creditterm ct ON ct.ID = si.CreditTermID
                                        WHERE ibm.CustomerTypeID = $sfmlevel
                                        AND DATE(si.EffectivityDate) BETWEEN '$datefrom' AND '$dateto'
										AND si.OutstandingBalance > 0
										AND b.ID = $branch
                                        $gsurange
                                        $sfarange
                                        $limit");


    }else{

        $sfarange = ($sfafrom == 0 AND $sfato == 0)?"":"AND (c.ID BETWEEN $sfafrom AND $sfato)
                                                    OR (c.ID BETWEEN $sfato AND $sfafrom)";

        $query = $database->execute("SELECT
                                        c.Code AccountCode,
                                        c.Name AccountName,
                                        gsu.Name CreditAccountType,
                                        ct.Name CreditTerm,
                                        si.DocumentNo,
                                        si.OutstandingBalance TotalAmount,
                                        IFNULL(tcd.TelNo, 'N/A') ContactNo,
										DATE_FORMAT(si.EffectivityDate, '%m/%d/%Y') DueDate
                                        FROM salesinvoice si
										INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                        INNER JOIN customer c  ON c.ID = si.CustomerID
											AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
                                        INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
                                            AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
											AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
                                        INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
											AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
                                        INNER JOIN tpi_gsutype gsu ON gsu.ID = tcd.tpi_GSUTypeID
                                        INNER JOIN creditterm ct ON ct.ID = si.CreditTermID
                                        WHERE c.CustomerTypeID = 1
                                        AND DATE(si.EffectivityDate) BETWEEN '$datefrom' AND '$dateto'
										AND si.OutstandingBalance > 0
										AND b.ID = $branch
                                        $gsurange
                                        $sfarange
                                        $limit");
    }

    return $query;
}


function collectiondueTotal($database, $datefrom, $dateto, $sfmlevel, $sfafrom, $sfato, $creditAccountType, $istotal, $page, $total, $branch){

    $start = ($page > 1)?(($page - 1) * $total):0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    $gsurange = ($creditAccountType > 0)?"AND gsu.ID = $creditAccountType":"";
    $datefrom = date('Y-m-d', strtotime($datefrom));
    $dateto = date('Y-m-d', strtotime($dateto));

    if($sfmlevel > 1){

        $sfarange = ($sfafrom == 0 AND $sfato == 0)?"":"AND (ibm.ID BETWEEN $sfafrom AND $sfato)
                                                    OR (ibm.ID BETWEEN $sfato AND $sfafrom)";

        $query = $database->execute("SELECT                                        
                                        SUM(si.OutstandingBalance) TotalAmount
                                        FROM salesinvoice si
										INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                        INNER JOIN customer c ON c.ID = si.CustomerID
											AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
                                        INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
                                            AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
											AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
                                        INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
											AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
                                        INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = ibm.ID
											AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
                                        INNER JOIN tpi_gsutype gsu ON gsu.ID = tcd.tpi_GSUTypeID
                                        INNER JOIN creditterm ct ON ct.ID = si.CreditTermID
                                        WHERE ibm.CustomerTypeID = $sfmlevel
										AND b.ID = $branch
                                        AND DATE(si.EffectivityDate) BETWEEN '$datefrom' AND '$dateto'
										AND si.OutstandingBalance > 0
                                        $gsurange
                                        $sfarange
                                        $limit");


    }else{

        $sfarange = ($sfafrom == 0 AND $sfato == 0)?"":"AND (c.ID BETWEEN $sfafrom AND $sfato)
                                                    OR (c.ID BETWEEN $sfato AND $sfafrom)";

        $query = $database->execute("SELECT                                        
                                        SUM(si.OutstandingBalance) TotalAmount
                                        FROM salesinvoice si
										INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                        INNER JOIN customer c  ON c.ID = si.CustomerID
											AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
                                        INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
                                            AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
											AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
                                        INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
											AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
                                        INNER JOIN tpi_gsutype gsu ON gsu.ID = tcd.tpi_GSUTypeID
                                        INNER JOIN creditterm ct ON ct.ID = si.CreditTermID
                                        WHERE c.CustomerTypeID = 1
										AND b.ID = $branch
                                        AND DATE(si.EffectivityDate) BETWEEN '$datefrom' AND '$dateto'
										AND si.OutstandingBalance > 0
                                        $gsurange
                                        $sfarange
                                        $limit");
    }

    return $query;
}

?>
