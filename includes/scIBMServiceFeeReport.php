<?php

function IBMServiceFee($ibmfrom, $ibmto, $campaign, $istotal, $page, $total, $branch){
    
    global $database;
	    
    $ibmrange = ($ibmfrom == 0 AND $ibmto == 0)?"":"AND ((ibm.ID BETWEEN $ibmfrom AND $ibmto) OR (ibm.ID BETWEEN $ibmto AND $ibmfrom))";
    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
	
	$query = $database->execute("SELECT

						IBMCode,
						IBMName,
						CreditTerm,
						PMGCode,
						NetInvoice,
						PaidInvoice Payment,
						DiscountedGrossSales DiscountedGrossAmount,
						PaidDGS,
						IFNULL((((PaidDGS * (sf.Discount / 100)) * 0.90) + IF(IsVatable = 1, (((PaidDGS * (sf.Discount / 100)) * 0.90) * 0.12), 0)), 0) ServiceFeeAmount,
						IFNULL(sf.Discount, 0) ServiceFeePercentage

						FROM (SELECT	
								ibm.Code IBMCode,
								ibm.Name IBMName,
								ct.Name CreditTerm,
								PMGID,
								PMGCode,
								sfm.Vatable IsVatable,	
								SUM(NetInvoice) NetInvoice,
								SUM(PaidInvoice) PaidInvoice,
								SUM(DiscountedGrossSales) DiscountedGrossSales,
								SUM(PaidDGS) PaidDGS

							FROM (SELECT
									PMGID,
									PMGCode,
									SalesInvoiceID,
									sidx.CustomerID,
									NetInvoice,
									PaidInvoice,
									DiscountedGrossSales,
									PaidDGS
								FROM (SELECT 
										PMGID,
										PMGCode,
										SalesInvoiceID,
										CustomerID,
										SUM(NetInvoice) NetInvoice,
										SUM(PaidInvoice) PaidInvoice,
										SUM(DiscountedGrossSales) DiscountedGrossSales,
										SUM(PaidDGS) PaidDGS,
										DueDate
									FROM (SELECT
											si.ID SalesInvoiceID,
											pmg.ID PMGID,
											pmg.Code PMGCode,
											c.ID CustomerID,
											IFNULL(sid.TotalAmount - sid.BasicDiscount - sid.AddDiscount - sid.ADPP, 0) NetInvoice,
											IFNULL(sid.Payment,0) PaidInvoice,
											(sid.TotalAmount - sid.BasicDiscount) DiscountedGrossSales,
											IFNULL(sid.PaidDGS, 0) PaidDGS,
											DATE(si.EffectivityDate) DueDate
										FROM salesinvoicedetails sid
										INNER JOIN branch b ON b.ID = SPLIT_STR(sid.HOGeneralID, '-', 2)
										INNER JOIN salesinvoice si ON si.ID = sid.SalesInvoiceID
											AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
										INNER JOIN tpi_pmg pmg ON pmg.ID = sid.PMGID											
										INNER JOIN customer c ON c.ID = si.CustomerID										
											AND IFNULL(c.IsEmployee, 0) = 0 
											AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
										WHERE DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
										AND b.ID = $branch
										AND si.StatusID = 7
											
										UNION ALL

										SELECT 
											si.ID SalesInvoiceID,
											pmg.ID PMGID,
											pmg.Code PMGCode,
											c.ID CustomerID,
											IFNULL((aoed.ProductEffectivePrice * aoed.ProductQuantity) - aoed.BasicDiscount - aoed.AddDiscount - aoed.ADPP, 0) NetInvoice,
											IFNULL(aoed.Payment,0) PaidInvoice,
											IFNULL(aoed.ProductEffectivePrice * aoed.ProductQuantity - aoed.BasicDiscount, 0) DiscountedGrossSales,
											IFNULL(aoed.PaidDGS, 0) PaidDGS,
											DATE(si.EffectivityDate) DueDate
										FROM applied_overlay_promo_entitlement_details aoed 
										INNER JOIN branch b ON b.ID = SPLIT_STR(aoed.HOGeneralID, '-', 2)
										INNER JOIN salesorder so ON so.ID = aoed.ReferenceSalesOrderID
											AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
										INNER JOIN deliveryreceipt dr ON dr.RefTxnID = so.ID
											AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
										INNER JOIN salesinvoice si ON si.RefTxnID = dr.ID
											AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
										INNER JOIN tpi_pmg pmg ON pmg.ID = aoed.ProductPMGID
										INNER JOIN customer c ON c.ID = si.CustomerID
											AND IFNULL(c.IsEmployee, 0) = 0
											AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
										WHERE DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
										AND b.ID = $branch
										AND si.StatusID = 7

										UNION ALL

										SELECT 
											si.ID SalesInvoiceID,
											pmg.ID PMGID,
											pmg.Code PMGCode,
											c.ID CustomerID,
											IFNULL((aoed.ProductEffectivePrice * aoed.ProductQuantity) - aoed.BasicDiscount - aoed.AddDiscount - aoed.ADPP, 0) NetInvoice,
											IFNULL(aoed.Payment,0) PaidInvoice,
											IFNULL(aoed.ProductEffectivePrice * aoed.ProductQuantity - aoed.BasicDiscount, 0) DiscountedGrossSales,
											IFNULL(aoed.PaidDGS, 0) PaidDGS,
											DATE(si.EffectivityDate) DueDate
										FROM applied_incentive_entitlement_details aoed 
										INNER JOIN branch b ON b.ID = SPLIT_STR(aoed.HOGeneralID, '-', 2)
										INNER JOIN salesorder so ON so.ID = aoed.ReferenceSalesOrderID
											AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
										INNER JOIN deliveryreceipt dr ON dr.RefTxnID = so.ID
											AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
										INNER JOIN salesinvoice si ON si.RefTxnID = dr.ID
											AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
										INNER JOIN tpi_pmg pmg ON pmg.ID = aoed.ProductPMGID
										INNER JOIN customer c ON c.ID = si.CustomerID
											AND IFNULL(c.IsEmployee, 0) = 0
											AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
										WHERE DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
										AND b.ID = $branch
										AND si.StatusID = 7

										UNION ALL

										SELECT 
											si.ID SalesInvoiceID,
											pmg.ID PMGID,
											pmg.Code PMGCode,
											c.ID CustomerID,
											IFNULL((aoed.ProductEffectivePrice * aoed.ProductQuantity) - aoed.BasicDiscount - aoed.AddDiscount - aoed.ADPP, 0) NetInvoice,
											IFNULL(aoed.Payment,0) PaidInvoice,
											IFNULL(aoed.ProductEffectivePrice * aoed.ProductQuantity - aoed.BasicDiscount, 0) DiscountedGrossSales,
											IFNULL(aoed.PaidDGS, 0) PaidDGS,
											DATE(si.EffectivityDate) DueDate
										FROM applied_special_promo_entitlement_details aoed 
										INNER JOIN branch b ON b.ID = SPLIT_STR(aoed.HOGeneralID, '-', 2)
										INNER JOIN salesorder so ON so.ID = aoed.ReferenceSalesOrderID
											AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
										INNER JOIN deliveryreceipt dr ON dr.RefTxnID = so.ID
											AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
										INNER JOIN salesinvoice si ON si.RefTxnID = dr.ID
											AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
										INNER JOIN tpi_pmg pmg ON pmg.ID = aoed.ProductPMGID
										INNER JOIN customer c ON c.ID = si.CustomerID
											AND IFNULL(c.IsEmployee, 0) = 0
											AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
										WHERE DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
										AND b.ID = $branch
										AND si.StatusID = 7) atbl
									GROUP BY PMGID, SalesInvoiceID) sidx
								LEFT JOIN officialreceiptdetails ords ON ords.RefTxnID = SalesInvoiceID
								INNER JOIN branch b1 ON b1.ID = SPLIT_STR(ords.HOGeneralID, '-', 2)
								LEFT JOIN officialreceipt ors ON ors.ID = ords.OfficialReceiptID
									AND ors.StatusID = 7
									AND DueDate > DATE(ors.TxnDate)
									AND LOCATE(CONCAT('-', $branch), ors.HOGeneralID) > 0
								GROUP BY SalesInvoiceID, PMGID) cx
							INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = cx.CustomerID
								AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = cx.CustomerID AND LOCATE(CONCAT('-', $branch), HOGeneralID) > 0)
								AND LOCATE(CONCAT('-', $branch), ribm.HOGeneralID) > 0				
							INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
								AND LOCATE(CONCAT('-', $branch), ibm.HOGeneralID) > 0
								$ibmrange
							INNER JOIN sfm_manager sfm ON sfm.mCode = ibm.Code
								AND LOCATE(CONCAT('-', $branch), sfm.HOGeneralID) > 0
							INNER JOIN tpi_credit tc ON tc.CustomerID = ibm.ID
								AND LOCATE(CONCAT('-', $branch), tc.HOGeneralID) > 0
							INNER JOIN creditterm ct ON ct.ID = tc.CreditTermID
							GROUP BY PMGID, ibm.ID) sfx
						LEFT JOIN servicefeecommission sf ON sf.PMGID = sfx.PMGID
							AND sfx.PaidDGS BETWEEN sf.Minimum AND sf.Maximum
						ORDER BY IBMName
						$limit");
	
	return $query;
    
}

function getBranchDetails($branchID){
	
	global $database;
	
	$query = $database->execute("SELECT CONCAT(TRIM(Code), ' - ', Name) BranchName FROM branch WHERE ID = $branchID")->fetch_object()->BranchName;
	
	return $query;
	
}
?>
