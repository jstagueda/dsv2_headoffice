<?php
/*
	@author: Gino C. Leabres
	@date: 6/19/2013
	@email: ginophp@gmail.com
*/
Class SalesReportClass
{
	function spSelectSIRegisterPrint($database, $fromDate, $toDate, $BranchID, $isCancelled = 0)
	{
		/*$q = "SELECT DISTINCT em.ID, CONCAT(em.FirstName, ' ', em.LastName) Employee, SUM(GrossAmount) CampaignPrice,
            SUM(BasicDiscount) BasicDiscount, (SUM(GrossAmount) - SUM(BasicDiscount)) DiscGrossSales,
            SUM(AddtlDiscount) AddtlDiscount,(SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) SalesWithVat,
            ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) / 1.12) AmountWOVat,
            (SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) / 1.12) VatAmount, 
			SUM(AddtlDiscountPrev) AddtlDiscountPrev, (SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - SUM(AddtlDiscountPrev) NetSalesValue,
            ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - SUM(AddtlDiscountPrev)) + ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) / 1.12)) InvoiceAmount
		  FROM `salesinvoice` si
		  INNER JOIN `employee` em ON em.ID = si.CreatedBy
		  WHERE si.StatusID IN (7, 8) AND date_format(si.TxnDate,'%Y-%m-%d') BETWEEN '".$fromDate."' AND '".$toDate."' AND SUBSTRING_INDEX(SUBSTRING_INDEX(si.HOGeneralID,'-',2),'-',-1) = ".$BranchID."
		  GROUP BY si.CreatedBy
		  ORDER BY em.LastName, em.FirstName";*/
            
			$Status = ($isCancelled) ? "(8)" : "(7, 8)";
			
            $q = "	SELECT DISTINCT 
					CONCAT('SO', REPEAT('0', (8- LENGTH(so.ID))), so.ID) OrderNo,
					-- CONCAT('SI', REPEAT('0', (8- LENGTH(s.ID))), s.ID) TxnNo,
					s.DocumentNo TxnNo,
                    s.TxnDate,
                    c.Name CustomerName,
                    CONCAT(SUBSTRING(c.FirstName, 1, 1), SUBSTRING(c.MiddleName, 1, 1), SUBSTRING(c.LastName, 1, 1)) CustomerInitial,
                    c.Code CustomerCode,
                     s.`GrossAmount` CampaignPrice,
                    -- (s.`GrossAmount` * (100/112)) CampaignPrice,
                     s.`BasicDiscount`,
					-- (s.`BasicDiscount` * (100/112)) BasicDiscount,
                    s.`AddtlDiscount`,
                    s.`NetAmount`,
                    s.`VatAmount` m,
                    s.AddtlDiscountPrev,
                    s.TotalCPI,
                    ss.Name `Status`,
                    s.CreditTermID
                    FROM salesinvoice s
                    INNER JOIN branch b ON SPLIT_STR(s.HOGeneralID, '-', 2) = b.ID	
					LEFT JOIN deliveryreceipt dr ON dr.ID = s.RefTxnID AND SPLIT_STR(dr.HOGeneralID, '-', 2) = b.ID
                    LEFT JOIN salesorder so
                        ON so.ID = dr.RefTxnID AND SPLIT_STR(so.HOGeneralID, '-', 2) = b.ID
                    INNER JOIN customer c ON c.ID = s.CustomerID AND SPLIT_STR(c.HOGeneralID, '-', 2) = b.ID
                    INNER JOIN `status` ss ON ss.ID = s.StatusID
                    WHERE s.StatusID IN $Status AND DATE_FORMAT(s.TxnDate, '%Y-%m-%d') BETWEEN '$fromDate' AND '$toDate'
                        AND s.DocumentNo != ''
                        AND b.ID = $BranchID
					ORDER BY s.TxnDate, s.DocumentNo
					
					-- SELECT DISTINCT CONCAT('SO', REPEAT('0', (8- LENGTH(so.ID))), so.ID) OrderNo,
					-- s.DocumentNo TxnNo,s.TxnDate, c.Name CustomerName,
					-- CONCAT(SUBSTRING(c.FirstName, 1, 1), SUBSTRING(c.MiddleName, 1, 1), SUBSTRING(c.LastName, 1, 1)) CustomerInitial,
					-- c.Code CustomerCode,
					-- -- s.`GrossAmount` CampaignPrice, 
					-- SUM((sid.UnitPrice) * (sid.Qty) * (100/112)) CampaignPrice,
					-- -- s.`BasicDiscount`, 
					-- IF(sid.PMGID = 3, 0, (SUM(((sid.UnitPrice * sid.Qty) * .25) * (100/112)))) BasicDiscount,
					-- -- s.`AddtlDiscount`, 
					-- SUM(sid.AddDiscount) AddtlDiscount,
					-- s.`NetAmount`,
					-- -- s.`VatAmount` ,
					-- -- s.AddtlDiscountPrev,
					-- s.VatAmount VatAmount,
					-- IFNULL(sid.ADPP,0) AddtlDiscountPrev,
					-- s.TotalCPI,
					-- ss.Name `Status`,
					-- s.CreditTermID
					-- FROM salesinvoicedetails sid
					-- INNER JOIN salesinvoice s  ON sid.SalesInvoiceID=s.ID 
					-- AND SPLIT_STR(s.HOGeneralID, '-', 2)=SPLIT_STR(sid.HOGeneralID, '-', 2)
					-- INNER JOIN branch b ON SPLIT_STR(s.HOGeneralID, '-', 2) = b.ID	
					-- LEFT JOIN salesorder so
					-- ON so.ID = s.RefTxnID AND SPLIT_STR(so.HOGeneralID, '-', 2) = b.ID
					-- INNER JOIN customer c ON c.ID = s.CustomerID AND SPLIT_STR(c.HOGeneralID, '-', 2) = b.ID
					-- INNER JOIN `status` ss ON ss.ID = s.StatusID
					-- WHERE s.StatusID IN (7, 8) 
					-- AND DATE(s.TxnDate) = '".$fromDate."' AND '".$toDate."'
					-- AND s.DocumentNo != ''
					-- AND b.ID = ".$BranchID."
					-- GROUP BY s.ID
					
					";
            
            $result = $database->execute($q);
            return $result;
	}
	
	function spSelectCreatedBySalesInvoice($database, $fromDate, $toDate, $BranchID, $isCancelled = 0)
	{
		/*$q = "SELECT DISTINCT em.ID, CONCAT(em.FirstName, ' ', em.LastName) Employee, SUM(GrossAmount) CampaignPrice,
            SUM(BasicDiscount) BasicDiscount, (SUM(GrossAmount) - SUM(BasicDiscount)) DiscGrossSales,
            SUM(AddtlDiscount) AddtlDiscount,(SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) SalesWithVat,
            ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) / 1.12) AmountWOVat,
            (SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) / 1.12) VatAmount, 
			SUM(AddtlDiscountPrev) AddtlDiscountPrev, (SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - SUM(AddtlDiscountPrev) NetSalesValue,
            ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - SUM(AddtlDiscountPrev)) + ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) / 1.12)) InvoiceAmount
		  FROM `salesinvoice` si
		  LEFT JOIN `employee` em ON em.ID = si.CreatedBy
		  WHERE si.StatusID IN (7, 8) AND date_format(si.TxnDate,'%Y-%m-%d') BETWEEN '".$fromDate."' AND '".$toDate."' AND SUBSTRING_INDEX(SUBSTRING_INDEX(si.HOGeneralID,'-',2),'-',-1) = ".$BranchID."
		  GROUP BY si.CreatedBy
		  ORDER BY em.LastName, em.FirstName";*/
		  
		  $Status = ($isCancelled) ? "(8)" : "(7, 8)";
		  
            $q = "SELECT DISTINCT
                        em.ID,
                        CONCAT(em.FirstName, ' ', em.LastName) Employee,
                        SUM(GrossAmount) CampaignPrice,
                        SUM(BasicDiscount) BasicDiscount,
                        (SUM(GrossAmount) - SUM(BasicDiscount)) DiscGrossSales,
                        SUM(AddtlDiscount) AddtlDiscount,
                        (SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) SalesWithVat,
                        ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) / 1.12) AmountWOVat,
                        (SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) / 1.12) VatAmount,
                        SUM(AddtlDiscountPrev) AddtlDiscountPrev,
                        (SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - SUM(AddtlDiscountPrev) NetSalesValue,
                        ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - SUM(AddtlDiscountPrev)) + ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) - ((SUM(GrossAmount) - SUM(BasicDiscount) - SUM(AddtlDiscount)) / 1.12)) InvoiceAmount
                FROM `salesinvoice` si
                    INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                    INNER JOIN `employee` em ON em.ID = si.CreatedBy
                WHERE si.StatusID IN $Status AND DATE_FORMAT(si.TxnDate, '%Y-%m-%d') BETWEEN '$fromDate' AND '$toDate'
                AND b.ID = $BranchID
                GROUP BY si.CreatedBy
                ORDER BY em.LastName, em.FirstName";
            $result = $database->execute($q);
            return $result;
	}
	
	function spSelectSIConfirmInvoices($database, $fromDate, $toDate, $BranchID, $DealerFrom, $DealerTo)
	{
		$q = "SELECT CONCAT('SO', REPEAT('0', (8- LENGTH(so.ID))), so.ID) OrderNo,  s.ID SalesInvoice, 
			  s.`GrossAmount` CampaignPrice,  s.`BasicDiscount`, s.`AddtlDiscount`,s.AddtlDiscountPrev,
			  c.Code DealerCode, c.Name DealerName, CONCAT(SUBSTRING(c.FirstName, 1, 1), SUBSTRING(c.MiddleName, 1, 1), SUBSTRING(c.LastName, 1, 1)) DealerInitial,
			  s.`GrossAmount`  TotalCSPAmount, s.`GrossAmount` - s.`BasicDiscount` TotalDGSAmount ,s.NetAmount TotalInvoiceAmount , s.TxnDate TransactionDueDate, 
			  so.IsAdvanced ,ss.Name StatusName FROM salesinvoice s
			  CROSS JOIN salesorder so ON SUBSTRING_INDEX(SUBSTRING_INDEX(so.HOGeneralID,'-',1),'-',-1) = s.RefTxnID
			  CROSS JOIN customer c ON SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',1),'-',-1) = s.CustomerID
			  CROSS JOIN status ss ON ss.ID = s.StatusID
			  where s.StatusID <> 6 and DATE_FORMAT(s.TxnDate,'%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."' 
			  and  SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',2),'-',-1) = ".$BranchID."
			  and  SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',1),'-',-1) between ".$DealerFrom." and ".$DealerTo;
		
		//echo $q."<br />";
		$result = $database->execute($q);
		return $result;
	}
	
	function spTotalSIConfirmInvoicesBranches($database, $fromDate, $toDate, $BranchID, $DealerFrom, $DealerTo)
	{
		$q = "SELECT sum(s.`GrossAmount`)  TotalCSPAmount, sum(s.`GrossAmount` - s.`BasicDiscount`) TotalDGSAmount ,sum(s.NetAmount) TotalInvoiceAmount,
			  ((SUM(s.GrossAmount) - SUM(s.BasicDiscount) - SUM(s.AddtlDiscount)) / 1.12) AmountWOVat,
			  (SUM(s.GrossAmount) - SUM(s.BasicDiscount) - SUM(s.AddtlDiscount)) - ((SUM(s.GrossAmount) - SUM(s.BasicDiscount) - SUM(s.AddtlDiscount)) / 1.12) VatAmount
			  FROM salesinvoice s
			  CROSS JOIN salesorder so ON SUBSTRING_INDEX(SUBSTRING_INDEX(so.HOGeneralID,'-',1),'-',-1) = s.RefTxnID
			  CROSS JOIN customer c ON SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',1),'-',-1) = s.CustomerID
			  CROSS JOIN status ss ON ss.ID = s.StatusID
			  where s.StatusID <> 6 and DATE_FORMAT(s.TxnDate,'%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."' 
			  and  SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',2),'-',-1) = ".$BranchID."
			  and  SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',1),'-',-1) between ".$DealerFrom." and ".$DealerTo."
			  GROUP BY SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',2),'-',-1)
			  ";
	
		$result = $database->execute($q);
		return $result;
	}
	
	function spTotalSIConfirmInvoicesDate($database, $fromDate, $toDate, $BranchID, $DealerFrom, $DealerTo){
		$q = "SELECT sum(s.`GrossAmount`)  TotalCSPAmount, sum(s.`GrossAmount` - s.`BasicDiscount`) TotalDGSAmount ,sum(s.NetAmount) TotalInvoiceAmount,
			  ((SUM(s.GrossAmount) - SUM(s.BasicDiscount) - SUM(s.AddtlDiscount)) / 1.12) AmountWOVat,
			  (SUM(s.GrossAmount) - SUM(s.BasicDiscount) - SUM(s.AddtlDiscount)) - ((SUM(s.GrossAmount) - SUM(s.BasicDiscount) - SUM(s.AddtlDiscount)) / 1.12) VatAmount
			  FROM salesinvoice s
			  CROSS JOIN salesorder so ON SUBSTRING_INDEX(SUBSTRING_INDEX(so.HOGeneralID,'-',1),'-',-1) = s.RefTxnID
			  CROSS JOIN customer c ON SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',1),'-',-1) = s.CustomerID
			  CROSS JOIN status ss ON ss.ID = s.StatusID
			  where s.StatusID <> 6 and DATE_FORMAT(s.TxnDate,'%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."' 
			  and  SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',2),'-',-1) = ".$BranchID."
			  and  SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',1),'-',-1) between ".$DealerFrom." and ".$DealerTo."
			  GROUP BY DATE_FORMAT(s.TxnDate,'%Y-%m-%d')";
		$result = $database->execute($q);
		return $result;
	}
	function spSelectBackOrderReport($database, $fromDate, $toDate, $BranchID, $DealerFrom, $DealerTo, $productfrom, $productto)
	{
		$q = "SELECT  DISTINCT s.ID, s.BranchID, s.TxnDate TransactionDate, s.CustomerID DealerID, c.Code DealerCode, c.Name DealerName,p.Code ProductCode, 
							   p.Description ProductName, sd.Qty QtyOrdered, (sd.Qty - sd.OutstandingQty) QtyServed, sd.OutstandingQty BackOrderQty,
							   CONCAT('SO', REPEAT('0', (8- LENGTH(s.ID))), s.ID) OrderNo
			  FROM salesorder s
			  CROSS JOIN salesorderdetails sd ON sd.SalesOrderID = SUBSTRING_INDEX(SUBSTRING_INDEX(s.HOGeneralID,'-',1),'-',-1)
			  CROSS JOIN product p ON p.ID = sd.ProductID
			  CROSS JOIN branch b ON b.ID = SUBSTRING_INDEX(SUBSTRING_INDEX(s.HOGeneralID,'-',2),'-',-1)
			  CROSS JOIN customer c ON SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',1),'-',-1) = s.CustomerID
			  WHERE s.StatusID = 7 AND sd.OutstandingQty != 0 and SUBSTRING_INDEX(SUBSTRING_INDEX(s.HOGeneralID,'-',2),'-',-1) = ".$BranchID."
			  AND DATE_FORMAT(s.TxnDate,'%Y-%m-%d') BETWEEN '".$fromDate."' and '".$toDate."' 
			  AND SUBSTRING_INDEX(SUBSTRING_INDEX(c.HOGeneralID,'-',1),'-',-1) between ".$DealerFrom." and ".$DealerTo."
			  AND p.ID BETWEEN ".$productfrom." AND ".$productto;
			  
		$result = $database->execute($q);
		
		return $result;
	}
	
}
$tpiSalesReport = new SalesReportClass();
?>