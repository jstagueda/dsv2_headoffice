
<?php 

/*function IBMCumulative($database, $ibmfrom, $ibmto, $campaign, $istotal, $page, $total, $branch){
    
    $start = ($page > 1)?(($page-1) * $total):0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    
    $ibmrange = ($ibmfrom == 0 AND $ibmto == 0)?"":"AND (c.ID BETWEEN $ibmfrom AND $ibmto 
                                    OR c.ID BETWEEN $ibmto AND $ibmfrom)";
    
    $query = $database->execute("SELECT
                                    c.Code,
                                    c.Name,
                                    sfa.CampaignCode,
                                    sfa.TotalDGSSales,
                                    ((sfa.TotalOnTimeDGSPayment/sfa.TotalDGSSales) * 100) OnTimeBCR,
                                    sfa.TotalInvoiceAmount,
                                    (sfa.TotalInvoicePayment - sfa.TotalInvoiceAmount) Collection,
                                    sfa.TotalInvoicePayment,
                                    sfa.TotalBCR
                                    FROM tpi_sfasummary sfa
									INNER JOIN branch b ON b.ID = SPLIT_STR(sfa.HOGeneralID, '-', 2)
                                    INNER JOIN customer c ON c.ID = sfa.CustomerID
										AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
                                    WHERE sfa.CampaignCode = '$campaign'
                                    AND c.CustomerTypeID = 3
									AND b.ID = $branch
                                    $ibmrange
                                    ORDER BY c.ID
                                    $limit");
    return $query;
}*/

function IBMCumulative($ibmfrom, $ibmto, $campaign, $istotal, $page, $total, $branch){
	
	global $database;
	
	$start = ($page > 1)?(($page-1) * $total):0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    
    $ibmrange = ($ibmfrom == 0 AND $ibmto == 0)?"":"AND (c.ID BETWEEN $ibmfrom AND $ibmto 
                                    OR c.ID BETWEEN $ibmto AND $ibmfrom)";
	
	$query = $database->execute("SELECT 
	
									CustomerID,
									'$campaign' CampaignCode,
									CustomerCode,
									CustomerName,
									SUM(NetAmount) NetAmount,
									IFNULL(SUM(PaidInvoice), 0) PaidInvoice,
									SUM(DiscountedGrossAmount) TotalDGSSales,
									SUM(CollectionDueBalance) CollectionDueBalance,
									IFNULL(((IF(SUM(PaidInvoice) > SUM(NetAmount), SUM(NetAmount), SUM(PaidInvoice)) / SUM(NetAmount)) * 100), 0) OnTimeOrNotBCR,
									IFNULL(((IF(SUM(OnTimePaidInvoice) > SUM(OnTimeNetAmount), SUM(OnTimeNetAmount), SUM(OnTimePaidInvoice)) / SUM(OnTimeNetAmount)) * 100), 0) OnTimeBCR
									
								FROM (SELECT 
									atb.*,
									SUM(ords.TotalAmount) PaidInvoice,
									car.OutstandingAmount CollectionDueBalance,
									IF(car.DaysDue > 0, 0, NetAmount) OnTimeNetAmount,
									IF(car.DaysDue > 0, 0, SUM(ords.TotalAmount)) OnTimePaidInvoice
								FROM (SELECT 
									SalesInvoiceID,
									CustomerID,
									CustomerCode,
									CustomerName,
									SINetAmount TotalNetInvoice,
									SUM(NetAmount) NetAmount,
									SUM(DGS) DiscountedGrossAmount,
									BranchID
								FROM (SELECT 
									si.NetAmount SINetAmount,
									si.ID SalesInvoiceID,
									c.ID CustomerID,
									c.Code CustomerCode,
									c.Name CustomerName,
									SUM(sid.TotalAmount * 0.75) DGS,
									SUM(sid.AddDiscount) AdditionalDiscount,
									SUM(sid.ADPP) ADPP,
									(SUM(sid.TotalAmount * 0.75) - SUM(sid.AddDiscount) - SUM(sid.ADPP)) NetAmount,
									b.ID BranchID
								FROM salesinvoice si 
								INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
								INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID
									AND LOCATE(CONCAT('-', b.ID), sid.HOGeneralID) > 0
								INNER JOIN customer c ON c.ID = si.CustomerID
									AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
								WHERE IFNULL(c.IsEmployee, 0) = 0
								AND sid.PMGID < 3
								AND IFNULL(sid.IsParentKit, 0) = 0
								AND IFNULL(sid.IsParentKitComponent, 0) = 0
								AND si.StatusID = 7
								AND DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
								AND b.ID = $branch
								$ibmrange
								GROUP BY SalesInvoiceID

								UNION ALL

								SELECT 
									si.NetAmount SINetAmount,
									si.ID SalesInvoiceID,
									c.ID CustomerID,
									c.Code CustomerCode,
									c.Name CustomerName,
									SUM((aied.ProductEffectivePrice * aied.ProductQuantity) * 0.75) DGS,
									SUM(aied.AddDiscount) AdditionalDiscount,
									SUM(aied.ADPP) ADPP, 
									(SUM((aied.ProductEffectivePrice * aied.ProductQuantity) * 0.75) - SUM(aied.AddDiscount) - SUM(aied.ADPP)) NetAmount,
									b.ID BranchID
								FROM applied_incentive_entitlement_details aied
								INNER JOIN branch b ON b.ID = SPLIT_STR(aied.HOGeneralID, '-', 2)
								INNER JOIN salesorder so ON so.ID = aied.ReferenceSalesOrderID
									AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
								INNER JOIN deliveryreceipt dr ON dr.RefTxnID = so.ID
									AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
								INNER JOIN salesinvoice si ON si.RefTxnID = dr.ID
									AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
								INNER JOIN customer c ON c.ID = si.CustomerID
									AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
								WHERE IFNULL(c.IsEmployee, 0) = 0
								AND aied.ProductPMGID < 3
								AND si.StatusID = 7
								AND DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
								AND b.ID = $branch
								$ibmrange
								GROUP BY si.ID

								UNION ALL

								SELECT 
									si.NetAmount SINetAmount,
									si.ID SalesInvoiceID,
									c.ID CustomerID,
									c.Code CustomerCode,
									c.Name CustomerName,
									SUM((aied.ProductEffectivePrice * aied.ProductQuantity) * 0.75) DGS,
									SUM(aied.AddDiscount) AdditionalDiscount,
									SUM(aied.ADPP) ADPP, 
									(SUM((aied.ProductEffectivePrice * aied.ProductQuantity) * 0.75) - SUM(aied.AddDiscount) - SUM(aied.ADPP)) NetAmount,
									b.ID BranchID
								FROM applied_overlay_promo_entitlement_details aied
								INNER JOIN branch b ON b.ID = SPLIT_STR(aied.HOGeneralID, '-', 2)
								INNER JOIN salesorder so ON so.ID = aied.ReferenceSalesOrderID
									AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
								INNER JOIN deliveryreceipt dr ON dr.RefTxnID = so.ID
									AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
								INNER JOIN salesinvoice si ON si.RefTxnID = dr.ID
									AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
								INNER JOIN customer c ON c.ID = si.CustomerID
									AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
								WHERE IFNULL(c.IsEmployee, 0) = 0
								AND aied.ProductPMGID < 3
								AND si.StatusID = 7
								AND DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
								AND b.ID = $branch
								$ibmrange
								GROUP BY si.ID

								UNION ALL

								SELECT 
									si.NetAmount SINetAmount,
									si.ID SalesInvoiceID,
									c.ID CustomerID,
									c.Code CustomerCode,
									c.Name CustomerName,
									SUM((aied.ProductEffectivePrice * aied.ProductQuantity) * 0.75) DGS,
									SUM(aied.AddDiscount) AdditionalDiscount,
									SUM(aied.ADPP) ADPP, 
									(SUM((aied.ProductEffectivePrice * aied.ProductQuantity) * 0.75) - SUM(aied.AddDiscount) - SUM(aied.ADPP)) NetAmount,
									b.ID BranchID
								FROM applied_special_promo_entitlement_details aied
								INNER JOIN branch b ON b.ID = SPLIT_STR(aied.HOGeneralID, '-', 2)
								INNER JOIN salesorder so ON so.ID = aied.ReferenceSalesOrderID
									AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
								INNER JOIN deliveryreceipt dr ON dr.RefTxnID = so.ID
									AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
								INNER JOIN salesinvoice si ON si.RefTxnID = dr.ID
									AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
								INNER JOIN customer c ON c.ID = si.CustomerID
									AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
								WHERE IFNULL(c.IsEmployee, 0) = 0
								AND aied.ProductPMGID < 3
								AND si.StatusID = 7
								AND DATE_FORMAT(si.TxnDate, '%b%y') = '$campaign'
								AND b.ID = $branch
								$ibmrange
								GROUP BY si.ID) atbl

								GROUP BY SalesInvoiceID) atb
								
								INNER JOIN branch bx ON bx.ID = atb.BranchID
								LEFT JOIN officialreceiptdetails ords ON ords.RefTxnID = atb.SalesInvoiceID
									AND LOCATE(CONCAT('-', bx.ID), ords.HOGeneralID) > 0
								INNER JOIN customeraccountsreceivable car ON car.SalesInvoiceID = atb.SalesInvoiceID
									AND car.ID = (SELECT MAX(ID) FROM customeraccountsreceivable WHERE SalesInvoiceID = atb.SalesInvoiceID
										AND LOCATE(CONCAT('-', bx.ID), HOGeneralID) > 0)
									AND LOCATE(CONCAT('-', bx.ID), car.HOGeneralID) > 0

								GROUP BY atb.SalesInvoiceID) atx
								GROUP BY CustomerID
								$limit");
	return $query;
	
}

$total = 10;
$page = (isset($_POST['page']))?$_POST['page']:1;
$ibmfrom = (isset($_POST['cboSIBMRHidden']))?$_POST['cboSIBMRHidden']:((isset($_GET['cboSIBMRHidden']))?$_GET['cboSIBMRHidden']:0);
$ibmto = (isset($_POST['cboEIBMRHidden']))?$_POST['cboEIBMRHidden']:((isset($_GET['cboEIBMRHidden']))?$_GET['cboEIBMRHidden']:0);
$campaign = (isset($_POST['campaign']))?$_POST['campaign']:((isset($_GET['campaign']))?$_GET['campaign']:"");
$branch = (isset($_POST['branch']))?$_POST['branch']:((isset($_GET['branch']))?$_GET['branch']:"");

$ibmcumulative = IBMCumulative($ibmfrom, $ibmto, $campaign, false, $page, $total, $branch);
$countibmcumulative = IBMCumulative($ibmfrom, $ibmto, $campaign, true, $page, $total, $branch);

?>