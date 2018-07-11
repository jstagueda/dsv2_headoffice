<?php

function officialrecieptregister($database, $datefrom, $dateto, $istotal, $page, $total, $sfmlevel, $sfaccountFrom, $sfaccountTo,$branch = 0){

    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    $datefrom = date("Y-m-d", strtotime($datefrom));
    $dateto = date("Y-m-d", strtotime($dateto));

    $sfmlevel = ($sfmlevel == 0)?"":"AND c.CustomerTypeID = $sfmlevel";
    $sfaccountrange = ($sfaccountFrom == 0 && $sfaccountTo == 0)?"":"AND ((c.ID BETWEEN $sfaccountFrom AND $sfaccountTo)
                                                                    OR (c.ID BETWEEN $sfaccountTo AND $sfaccountFrom))";
		
    $query = $database->execute("SELECT a.*, IFNULL(cp.OutstandingAmount,0.00) PenaltyAmt FROM (
										SELECT
										DATE_FORMAT(or.TxnDate, '%m/%d/%Y') ORDate,
										CONCAT('OR',LPAD(or.ID,8,0)) ORno,
										or.DocumentNo,
										TRIM(c.ID) CustomerID,
										TRIM(c.Code) CustomerCode,
										c.Name CustomerName,
										IFNULL(orch.TotalAmount, 0)    cash,
										IFNULL(orck.TotalAmount, 0)    `check`,
										IFNULL(orcom.TotalAmount,0) `offset`,
										s.Name StatusName,
										s.ID StatusID, 
										ord.TotalAmount DepositSlip,
										or.TotalAmount NetAmount,
										or.TotalAppliedAmt TotalAppliedAmount,
										or.TotalUnappliedAmt TotalUaAppliedAmount,
										IFNULL(ords.RefTxnID,0) SalesInvoiceID,
										if(s.ID = 8, or.TotalUnappliedAmt, 0.00) Cancelled
										FROM officialreceipt `or`
										LEFT JOIN officialreceiptcash orch ON orch.OfficialReceiptID = or.ID and SPLIT_STR(or.HOGeneralID,'-',1) = orch.OfficialReceiptID and SPLIT_STR(orch.HOGeneralID,'-',2) = $branch
										LEFT JOIN officialreceiptcheck orck ON orck.OfficialReceiptID = or.ID AND SPLIT_STR(or.HOGeneralID,'-',1) = orck.OfficialReceiptID and SPLIT_STR(orck.HOGeneralID,'-',2) = $branch
										INNER JOIN customer c ON c.ID = or.CustomerID and SPLIT_STR(c.HOGeneralID,'-',2) = $branch
										INNER JOIN `status` s ON s.ID = or.StatusID
										LEFT JOIN officialreceiptdeposit `ord` ON ord.OfficialReceiptID = or.ID
										LEFT JOIN officialreceiptcommission orcom ON orcom.OfficialReceiptID = or.ID
										LEFT JOIN officialreceiptdetails `ords` ON `ords`.OfficialReceiptID=or.ID AND ords.ORReferenceType = 1
										WHERE DATE(or.TxnDate) BETWEEN '$datefrom' AND '$dateto' and SPLIT_STR(or.HOGeneralID,'-',2) = $branch
										AND or.IsORorMisc = 1
										".$sfmlevel." ".$sfaccountrange."
										GROUP BY or.ID, or.DocumentNo ".$limit."
								) a 
								LEFT JOIN customerpenalty cp ON cp.SalesInvoiceID = a.SalesInvoiceID AND a.CustomerID = cp.CustomerID								
								ORDER BY ORno DESC");

    return $query;
}

function officialreceipttotal($datefrom, $dateto, $sfmlevel, $sfaccountFrom, $sfaccountTo, $branch){
	global $database;
	
	$datefrom = date("Y-m-d", strtotime($datefrom));
    $dateto = date("Y-m-d", strtotime($dateto));

    $sfmlevel = ($sfmlevel == 0)?"":"AND c.CustomerTypeID = $sfmlevel";
    $sfaccountrange = ($sfaccountFrom == 0 && $sfaccountTo == 0)?"":"AND ((c.ID BETWEEN $sfaccountFrom AND $sfaccountTo)  OR (c.ID BETWEEN $sfaccountTo AND $sfaccountFrom))";
																	
	$ListORIDS=array();																
	$ORIds = "";
	$dynamic_where = "";
	$q = $database->execute("SELECT or.ID from officialreceipt `or`
						INNER JOIN branch br ON br.ID = SPLIT_STR(or.HOGeneralID, '-', 2)
						INNER JOIN customer c on c.ID = or.CustomerID 
							AND LOCATE(CONCAT('-', br.ID), c.HOGeneralID) > 0
						WHERE br.ID = $branch
						AND or.IsORorMisc = 1
						AND DATE(or.TxnDate) BETWEEN '".$datefrom."' AND '".$dateto."'  ".$sfmlevel." ".$sfaccountrange);
	if($q->num_rows > 0 ){
		while($r = $q->fetch_object()){
			$ListORIDS[] = $r->ID;
		}
	}
	$ORIds = implode(',',$ListORIDS);
	if( count($ListORIDS) > 0 ){
		$dynamic_where = " AND a.ID in (".$ORIds.")";
	}
	
	$query = $database->execute("SELECT 
								( SELECT IFNULL(SUM(b.TotalAmount),0.00)  FROM officialreceipt a 
									INNER JOIN branch br ON br.ID = SPLIT_STR(a.HOGeneralID, '-', 2)
									INNER JOIN officialreceiptcash b ON a.ID = b.OfficialReceiptID
										AND LOCATE(CONCAT('-', br.ID), b.HOGeneralID) > 0
									WHERE br.ID = $branch
									AND DATE(a.TxnDate) BETWEEN '".$datefrom."' and '".$dateto."' AND a.StatusID = 7 ".$dynamic_where.") Cash,
								( SELECT IFNULL(SUM(b.TotalAmount),0.00)  FROM officialreceipt a 
									INNER JOIN branch br ON br.ID = SPLIT_STR(a.HOGeneralID, '-', 2)
									INNER JOIN officialreceiptdeposit b ON a.ID = b.OfficialReceiptID 
										AND LOCATE(CONCAT('-', br.ID), b.HOGeneralID) > 0
									WHERE  br.ID = $branch
									AND DATE(a.TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' AND a.StatusID = 7 ".$dynamic_where.") Deposit,
								( SELECT IFNULL(SUM(b.TotalAmount),0.00)  FROM officialreceipt a 
									INNER JOIN branch br ON br.ID = SPLIT_STR(a.HOGeneralID, '-', 2)
									INNER JOIN officialreceiptcheck b ON a.ID = b.OfficialReceiptID 
										AND LOCATE(CONCAT('-', br.ID), b.HOGeneralID) > 0
									WHERE  br.ID = $branch
									AND DATE(a.TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' AND a.StatusID = 7 ".$dynamic_where.") `Check`,
								(SELECT SUM(TotalAmount) FROM officialreceipt a 
									INNER JOIN branch br ON br.ID = SPLIT_STR(a.HOGeneralID, '-', 2)
									WHERE  br.ID = $branch
									AND a.StatusID = 8 AND DATE(TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' 
									AND a.IsORorMisc = 1
									".$dynamic_where.") CancelledAmount,
								(SELECT SUM(OutstandingAmount) FROM 
									(SELECT IFNULL(c.OutstandingAmount,0.00) OutstandingAmount 
										FROM officialreceipt a
										INNER JOIN branch br ON br.ID = SPLIT_STR(a.HOGeneralID, '-', 2)
										INNER JOIN officialreceiptdetails b ON a.ID = b.OfficialReceiptID
											AND LOCATE(CONCAT('-', br.ID), b.HOGeneralID) > 0
										LEFT JOIN customerpenalty c ON b.RefTxnID = c.SalesInvoiceID AND b.ORReferenceType = 1
											AND LOCATE(CONCAT('-', br.ID), c.HOGeneralID) > 0
										WHERE br.ID = $branch
										AND DATE(a.TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' 
										AND a.IsORorMisc = 1
										".$dynamic_where." GROUP BY a.ID) tbl) PenaltyAmount,
								(SELECT IFNULL( SUM(TotalAmount),0.00) FROM officialreceipt a 
									INNER JOIN branch br ON br.ID = SPLIT_STR(a.HOGeneralID, '-', 2)
									WHERE br.ID = $branch
									AND a.StatusID = 7 AND DATE(TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' 
									AND a.IsORorMisc = 1
									".$dynamic_where.") NetTotalAmount,
								( SELECT IFNULL(SUM(b.TotalAmount),0.00)  FROM officialreceipt a 
									INNER JOIN branch br ON br.ID = SPLIT_STR(a.HOGeneralID, '-', 2)
									INNER JOIN officialreceiptcommission b ON a.ID = b.OfficialReceiptID 
										AND LOCATE(CONCAT('-', br.ID), b.HOGeneralID) > 0
									WHERE br.ID = $branch
									AND DATE(a.TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' AND a.StatusID = 7 ".$dynamic_where.") Offsetting,
								(SELECT IFNULL( SUM(TotalAppliedAmt),0.00) FROM officialreceipt a 
									INNER JOIN branch br ON br.ID = SPLIT_STR(a.HOGeneralID, '-', 2)
									WHERE br.ID = $branch
									AND a.StatusID = 7 AND DATE(TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' 
									AND a.IsORorMisc = 1
									".$dynamic_where.") TotalAppliedAmount,
								(SELECT IFNULL( SUM(TotalUnappliedAmt),0.00) FROM officialreceipt a 
									INNER JOIN branch br ON br.ID = SPLIT_STR(a.HOGeneralID, '-', 2)
									WHERE br.ID = $branch
									AND a.StatusID = 7 AND DATE(TxnDate)  BETWEEN '".$datefrom."' and '".$dateto."' 
									AND a.IsORorMisc = 1
									".$dynamic_where.") TotalUnappliedAmt");
	return $query;
}

?>
