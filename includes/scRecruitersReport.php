<?php
    //added by joebert italia
    function sfmlevel($database){
        $result = array();
        $query = $database->execute("SELECT * FROM sfm_level order by codeID ASC");
        while($res = $query->fetch_object()){
            $result['id'][] = $res->codeID;
            $result['code'][] = $res->desc_code." - ".$res->description;
        }
        return $result;
    }

    function sfaccount($database, $sfmlevel, $searched, $branch){
        $query = $database->execute("SELECT c.ID, c.Name, c.Code
                                        FROM customer c
										INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
                                        INNER JOIN tpi_rcustomeribm ribm
                                            ON ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE('-$branch', HOGeneralID) > 0)
                                            AND c.ID = ribm.CustomerID
											AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
                                        WHERE c.CustomerTypeID = $sfmlevel
                                        AND (c.Name LIKE '$searched%' OR c.Code LIKE '$searched%')
										AND b.ID = $branch
                                        LIMIT 10");
        return $query;
    }

    function recruitersreport($database, $datefrom, $dateto, $sfmlevel, $dealerfrom, $dealerto, $istotal, $page, $total, $branch){

        $start = ($page > 1)?(($page - 1) * $total):0;
        $limit = (!$istotal)?"LIMIT $start, $total":"";
        $dealerrange = ($dealerfrom == 0 AND $dealerto == 0)?"":"AND ((c.ID BETWEEN $dealerfrom AND $dealerto)
                                        OR (c.ID BETWEEN $dealerto AND $dealerfrom))";
        $datefrom = date("Y-m-d", strtotime($datefrom));
        $dateto = date("Y-m-d", strtotime($dateto));
        $daterange = "AND DATE_FORMAT(si.TxnDate, '%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";

        $query = $database->execute("SELECT 
										c.ID, 
										c.Name recruitName, 
										c.Code recruitCode,
										rc.Code recruiterCode, 
										rc.Name recruiterName,
										DATE_FORMAT(si.TxnDate, '%m/%d/%Y') DateRegister,
										(SELECT p.Code 
											FROM salesinvoice si2 
											INNER JOIN branch b ON b.ID = SPLIT_STR(si2.HOGeneralID, '-', 2)
											INNER JOIN salesinvoicedetails sid2 ON si2.ID = sid2.SalesInvoiceID
												AND LOCATE(CONCAT('-', b.ID), sid2.HOGeneralID) > 0
											INNER JOIN product p ON p.ID = sid2.ProductID
											WHERE sid2.ProductTypeID = 4
											AND sid2.IsParentKit = 1
											AND IFNULL(sid2.IsParentKitComponent, 0) = 0
											AND si2.StatusID = 7
											AND si2.CustomerID = c.ID
											AND b.ID = $branch) kitAvailed,
										(SELECT IFNULL((si2.GrossAmount - si2.TotalCPI), 0) 
											FROM salesinvoice si2
											INNER JOIN branch b ON b.ID = SPLIT_STR(si2.HOGeneralID, '-', 2)
											WHERE si2.ID = (SELECT MIN(ID) FROM salesinvoice 
												WHERE CustomerID = c.ID AND StatusID = 7
												AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
											AND si2.CustomerID = c.ID
											AND si2.StatusID = 7
											AND b.ID = $branch) FirstPOtpi_dealerkitpurchase
									FROM customer c
									INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
									INNER JOIN salesinvoice si ON si.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
									INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
									LEFT JOIN customer rc ON rc.ID = tcd.tpi_RecruiterID
										AND LOCATE(CONCAT('-', b.ID), rc.HOGeneralID) > 0
									WHERE c.CustomerTypeID = $sfmlevel
									AND b.ID = $branch
									AND IFNULL(c.IsEmployee, 0) = 0
									AND si.ID = (SELECT MIN(ID) FROM salesinvoice WHERE StatusID = 7 AND CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
									$daterange
									$dealerrange
									GROUP BY c.ID
									$limit");
        return $query;
    }
	
	function recruitersreportTotal($datefrom, $dateto, $sfmlevel, $dealerfrom, $dealerto, $branch){
		
		global $database;
		
        $dealerrange = ($dealerfrom == 0 AND $dealerto == 0)?"":"AND ((c.ID BETWEEN $dealerfrom AND $dealerto)
                                        OR (c.ID BETWEEN $dealerto AND $dealerfrom))";
        $datefrom = date("Y-m-d", strtotime($datefrom));
        $dateto = date("Y-m-d", strtotime($dateto));
        $daterange = "AND DATE_FORMAT(si.TxnDate, '%Y-%m-%d') BETWEEN '$datefrom' AND '$dateto'";

        $query = $database->execute("SELECT
									SUM(FirstPOtpi_dealerkitpurchase) TotalPurchaseKit,
									COUNT(CustomerID) TotalCustomer,
									COUNT(kitAvailed) TotalKit
									FROM (SELECT
										(SELECT IFNULL((si2.GrossAmount - si2.TotalCPI), 0) 
											FROM salesinvoice si2
											INNER JOIN branch b ON b.ID = SPLIT_STR(si2.HOGeneralID, '-', 2)
											WHERE si2.ID = (SELECT MIN(ID) FROM salesinvoice 
												WHERE CustomerID = c.ID AND StatusID = 7
												AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
											AND si2.CustomerID = c.ID
											AND si2.StatusID = 7
											AND b.ID = $branch) FirstPOtpi_dealerkitpurchase,
										c.ID CustomerID,
										(SELECT p.Code 
											FROM salesinvoice si2 
											INNER JOIN branch b ON b.ID = SPLIT_STR(si2.HOGeneralID, '-', 2)
											INNER JOIN salesinvoicedetails sid2 ON si2.ID = sid2.SalesInvoiceID
												AND LOCATE(CONCAT('-', b.ID), sid2.HOGeneralID) > 0
											INNER JOIN product p ON p.ID = sid2.ProductID
											WHERE sid2.ProductTypeID = 4
											AND sid2.IsParentKit = 1
											AND IFNULL(sid2.IsParentKitComponent, 0) = 0
											AND si2.StatusID = 7
											AND si2.CustomerID = c.ID
											AND b.ID = $branch) kitAvailed
									FROM customer c
									INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
									INNER JOIN salesinvoice si ON si.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
									INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
									LEFT JOIN customer rc ON rc.ID = tcd.tpi_RecruiterID
										AND LOCATE(CONCAT('-', b.ID), rc.HOGeneralID) > 0
									WHERE c.CustomerTypeID = $sfmlevel
									AND IFNULL(c.IsEmployee, 0) = 0
									AND si.ID = (SELECT MIN(ID) FROM salesinvoice WHERE StatusID = 7 AND CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
									AND b.ID = $branch
									$daterange
									$dealerrange
									GROUP BY c.ID) atbl");
        return $query;
    }
	
	function recruitersreportTotalPerDay($date, $sfmlevel, $dealerfrom, $dealerto, $branch){
		
		global $database;
		
        $dealerrange = ($dealerfrom == 0 AND $dealerto == 0)?"":"AND ((c.ID BETWEEN $dealerfrom AND $dealerto)
                                        OR (c.ID BETWEEN $dealerto AND $dealerfrom))";
										
        $daterange = "AND DATE_FORMAT(si.TxnDate, '%m/%d/%Y') = '$date'";

        $query = $database->execute("SELECT
									SUM(FirstPOtpi_dealerkitpurchase) TotalPurchaseKit,
									COUNT(CustomerID) TotalCustomer,
									COUNT(kitAvailed) TotalKit
									FROM (SELECT
										(SELECT IFNULL((si2.GrossAmount - si2.TotalCPI), 0) 
											FROM salesinvoice si2
											INNER JOIN branch b ON b.ID = SPLIT_STR(si2.HOGeneralID, '-', 2)
											WHERE si2.ID = (SELECT MIN(ID) FROM salesinvoice 
												WHERE CustomerID = c.ID AND StatusID = 7
												AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
											AND si2.CustomerID = c.ID
											AND si2.StatusID = 7
											AND b.ID = $branch) FirstPOtpi_dealerkitpurchase,
											c.ID CustomerID,
											(SELECT p.Code 
												FROM salesinvoice si2 
												INNER JOIN branch b ON b.ID = SPLIT_STR(si2.HOGeneralID, '-', 2)
												INNER JOIN salesinvoicedetails sid2 ON si2.ID = sid2.SalesInvoiceID
													AND LOCATE(CONCAT('-', b.ID), sid2.HOGeneralID) > 0
												INNER JOIN product p ON p.ID = sid2.ProductID
												WHERE sid2.ProductTypeID = 4
												AND sid2.IsParentKit = 1
												AND IFNULL(sid2.IsParentKitComponent, 0) = 0
												AND si2.StatusID = 7
												AND si2.CustomerID = c.ID
												AND b.ID = $branch) kitAvailed
									FROM customer c
									INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
									INNER JOIN salesinvoice si ON si.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
									INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID
										AND LOCATE(CONCAT('-', b.ID), sid.HOGeneralID) > 0
									INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
									LEFT JOIN customer rc ON rc.ID = tcd.tpi_RecruiterID
										AND LOCATE(CONCAT('-', b.ID), rc.HOGeneralID) > 0
									WHERE c.CustomerTypeID = $sfmlevel
									AND IFNULL(c.IsEmployee, 0) = 0
									AND si.ID = (SELECT MIN(ID) FROM salesinvoice WHERE StatusID = 7 AND CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
									AND b.ID = $branch
									$daterange
									$dealerrange
									GROUP BY c.ID) atbl");
        return $query;
    }


    function networkreport($database, $datefrom, $dateto, $sfm, $customerfrom, $customerto, $status, $isactive, $istotal, $page, $total){

        $start = ($page > 1)?($page - 1) * $total : 0;

        if($sfm > 1){
            $customerrange = ($customerfrom == 0 AND $customerto == 0)?"":" AND ((ibm.ID BETWEEN $customerfrom AND $customerto)
                                                                     OR (ibm.ID BETWEEN $customerto AND $customerfrom))";
            $customertype = " AND ibm.CustomerTypeID = $sfm";
        }else{
            $customerrange = ($customerfrom == 0 AND $customerto == 0)?"":" AND ((igs.ID BETWEEN $customerfrom AND $customerto)
                                                                     OR (igs.ID BETWEEN $customerto AND $customerfrom))";
            $customertype = " AND igs.CustomerTypeID = $sfm";
        }
        $datefrom = date("Y-m-d", strtotime($datefrom));
        $dateto = date("Y-m-d", strtotime($dateto));
        $limit = (!$istotal)?"LIMIT $start, $total":"";
        $statusquery = ($status > 0)?" AND s.ID = $status":" AND s.ID IN (4, 5)";
        $isactivequery = ($isactive)?" AND s2.ID = 1":" AND s2.ID = 2";

        $query = $database->execute("SELECT
                                        ibm.ID IBMID,
                                        igs.Code,
                                        igs.Name,
                                        IFNULL(rec.Name, '') RecruiterName,
                                        IFNULL(rec.Code, '') RecruiterCode,
                                        DATE(igs.EnrollmentDate) AppoinmentDate,
                                        IF(s.ID = 5, rcs.EnrollmentDate, '') LastDateTerminated,
                                        IFNULL((SELECT MAX(TxnDate) FROM salesinvoice WHERE CustomerID = igs.ID),'') LastPODate,
                                        ct.Name CreditTerm,
                                        ct.Name CreditLimit,
                                        -- tc.ApprovedCL CreditLimit,
                                        tc.AvailableCL RemainingCL,
                                        SUM(si.OutstandingBalance) OutStandingBalance,
                                        IFNULL(SUM(cp.OutstandingAmount),0.00) Penalty,
                                        IFNULL(tcd.StreetAdd, '') Address,
                                        IFNULL(tcd.TelNo, '') ContactNo,
                                        IFNULL(IF(SUM(DATEDIFF(NOW(), si.EffectivityDate)) < 0, 0, SUM(DATEDIFF(NOW(), si.EffectivityDate))),0) Daysdue,
                                        s.Name StatusName
                                        FROM customer igs
                                        INNER JOIN salesinvoice si
                                            ON si.CustomerID = igs.ID
                                        INNER JOIN tpi_rcustomerstatus rcs ON rcs.CustomerID = igs.ID
                                            AND rcs.ID = (SELECT MAX(ID) FROM tpi_rcustomerstatus WHERE CustomerID = igs.ID)
                                        INNER JOIN `status` s ON s.ID = rcs.CustomerStatusID
                                        INNER JOIN `status` s2 ON s2.ID = igs.StatusID
                                        INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = igs.ID
                                            AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = igs.ID)
                                        INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
                                        INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = igs.ID
                                        LEFT JOIN customer rec ON rec.ID = tcd.tpi_RecruiterID
                                        INNER JOIN tpi_credit tc ON tc.CustomerID = igs.ID
                                        INNER JOIN creditterm ct ON ct.ID = tc.CreditTermID
                                        LEFT JOIN customerpenalty cp ON cp.CustomerID = igs.ID
                                                AND DATE(cp.EnrollmentDate) = DATE(si.TxnDate)
                                        WHERE DATE(igs.EnrollmentDate) BETWEEN '$datefrom' AND '$dateto'
                                        $customertype
                                        $isactivequery
                                        $statusquery
                                        $customerrange
                                    GROUP BY igs.ID
                                    ORDER BY ibm.Code, igs.Name ASC
                                    $limit");
        return $query;

    }

    function totalNetworkPerIBM($database, $datefrom, $dateto, $customerfrom, $customerto, $status, $isactive, $ibmid, $sfm, $isprinting){
        if($sfm == 1){
            $customerrange = ($customerfrom == 0 AND $customerto == 0)?"":" AND ((igs.ID BETWEEN $customerfrom AND $customerto)
                                                                     OR (igs.ID BETWEEN $customerto AND $customerfrom))";
        }

        $ibmidquery = " AND ibm.ID = $ibmid";

        $datefrom = date("Y-m-d", strtotime($datefrom));
        $dateto = date("Y-m-d", strtotime($dateto));
        $statusquery = ($status > 0)?" AND s.ID = $status":" AND s.ID IN (4, 5)";
        $isactivequery = ($isactive)?" AND s2.ID = 1":" AND s2.ID = 2";

        $query = $database->execute("SELECT
                                        ibm.ID IBMID,
                                        ibm.Code,
                                        ibm.Name,
                                        IFNULL(rec.Name, '') RecruiterName,
                                        IFNULL(rec.Code, '') RecruiterCode,
                                        DATE_FORMAT(ibm.EnrollmentDate, '%m/%d/%Y') AppoinmentDate,
                                        IF(s.ID = 5, DATE_FORMAT(rcs.EnrollmentDate, '%m/%d/%Y'), '') LastDateTerminated,
                                        DATE_FORMAT(MAX(TxnDate), '%m/%d/%Y') LastPODate,
                                        ct.Name CreditTerm,
                                        ct.Name CreditLimit,
                                        tc.AvailableCL RemainingCL,
                                        SUM(si.OutstandingBalance) OutStandingBalance,
                                        IFNULL(SUM(cp.OutstandingAmount),0.00) Penalty,
                                        IFNULL(tcd.StreetAdd, '') Address,
                                        IFNULL(tcd.TelNo, '') ContactNo,
                                        IFNULL(IF(SUM(DATEDIFF(NOW(), si.EffectivityDate)) < 0, 0, SUM(DATEDIFF(NOW(), si.EffectivityDate))),0) Daysdue,
                                        s.Name StatusName
                                        FROM customer igs
                                        INNER JOIN salesinvoice si
                                            ON si.CustomerID = igs.ID
                                        INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = igs.ID
                                            AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = igs.ID)
                                        INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
                                        INNER JOIN tpi_rcustomerstatus rcs ON rcs.CustomerID = ibm.ID
                                            AND rcs.ID = (SELECT MAX(ID) FROM tpi_rcustomerstatus WHERE CustomerID = ibm.ID)
                                        INNER JOIN `status` s ON s.ID = rcs.CustomerStatusID
                                        INNER JOIN `status` s2 ON s2.ID = ibm.StatusID
                                        INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = ibm.ID
                                        LEFT JOIN customer rec ON rec.ID = tcd.tpi_RecruiterID
                                        INNER JOIN tpi_credit tc ON tc.CustomerID = ibm.ID
                                        INNER JOIN creditterm ct ON ct.ID = tc.CreditTermID
                                        LEFT JOIN customerpenalty cp ON cp.CustomerID = igs.ID
                                                AND DATE(cp.EnrollmentDate) = DATE(si.TxnDate)
                                        WHERE DATE(igs.EnrollmentDate) BETWEEN '$datefrom' AND '$dateto'
                                        $customertype
                                        $isactivequery
                                        $statusquery
                                        $customerrange
                                        $ibmidquery
                                    GROUP BY ibm.ID");

        $style = ($isprinting)?"style='font-weight:bold;'":"style='background:#FFDEF0; font-weight:bold;'";

        if($query->num_rows){
            while($res = $query->fetch_object()){
                echo "<tr class=\"listtr\" $style>
                    <td align='right' colspan='5'>Total for IBM ".$res->Name."</td>
                    <td align='center'></td>
                    <td align='right'>".number_format($res->RemainingCL, 2)."</td>
                    <td align='center'></td>
                    <td align='right'>".number_format($res->OutStandingBalance, 2)."</td>
                    <td align='center'>".$res->Daysdue."</td>
                    <td align='right'>".number_format($res->Penalty, 2)."</td>
                    <td align='center'></td>
                    <td></td>
                    <td></td>
                    <td align='center'></td>
                </tr>";
            }
        }

    }
?>