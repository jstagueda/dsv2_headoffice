<?php

function salesforcemasterlist($database, $branch, $sfmlevel, $codefrom, $codeto, $istotal, $page, $total){

    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?" LIMIT $start, $total":"";
    $coderange = ($codefrom == 0 AND $codeto == 0)?"":" AND ((c.ID BETWEEN $codefrom AND $codeto) OR (c.ID BETWEEN $codeto AND $codefrom))";

    $query = $database->execute("SELECT
										b.Name BranchName,
										c.Code AccountCode,
										c.Name AccountName,
										sl.desc_code SFMLevel,
										IFNULL(c.TIN, '') TIN,
										sfm.bank_acct_num BankAccount,
										nt.Code NetworkCode,
										nt.Name NetworkName,
										s.Name StatusName,
										IFNULL(DATE_FORMAT(sfmh.MovementDate, '%m/%d/%Y'), '') TerminationDate,
										CASE WHEN sfm.PayoutOrOffset = 1 THEN 'PAYOUT'
										WHEN sfm.PayoutOrOffset = 0 THEN 'OFFSET'
										END PayoutOrOffset,
										IFNULL(DATE_FORMAT((SELECT MIN(TxnDate) FROM salesinvoice WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID),HOGeneralID) > 0), '%m/%d/%Y'), '') AppoinmentDate
									FROM customer c
									LEFT JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
									LEFT JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
									LEFT JOIN tpi_rcustomerstatus rcs ON rcs.CustomerID = c.ID 
										AND rcs.ID = (SELECT MAX(ID) FROM tpi_rcustomerstatus WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', b.ID), rcs.HOGeneralID) > 0
									LEFT JOIN `status` s ON s.ID = rcs.CustomerStatusID
									LEFT JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
										AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
									LEFT JOIN sfm_level sl ON sl.codeID = c.CustomerTypeID
									LEFT JOIN sfm_manager sfm ON sfm.mCode = c.Code
										AND LOCATE(CONCAT('-', b.ID), sfm.HOGeneralID) > 0
									LEFT JOIN customer nt ON nt.ID = ribm.IBMID
										AND LOCATE(CONCAT('-', b.ID), nt.HOGeneralID) > 0
									LEFT JOIN sfm_movement_history sfmh ON sfmh.CustomerID = c.ID
										AND sfmh.MovementType = 'TERMINATION'
                                    WHERE c.CustomerTypeID = $sfmlevel
                                    AND b.ID = $branch
                                    $coderange
                                    ORDER BY c.Name
                                    $limit");
    return $query;

}

?>
