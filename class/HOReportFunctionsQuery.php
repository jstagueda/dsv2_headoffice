<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: July 16, 2013
 * @description: Function handler for all HO report modules.
 */
    
    /*
     * @description: Function used in generating report for Top Selling Report.
     */
    function HOReportTopSellingReportQuery($BranchID,$dateFrom,$dateTo,$report_type,$sales_type,$records){
        global $mysqli;
        
        $MonthDateFrom = date('n',strtotime($dateFrom));
        $MonthDateTo = date('n',strtotime($dateTo));
        $YearDateFrom = date('y',strtotime($dateFrom));
        $YearDateTo = date('y',strtotime($dateTo));
        
        //let's prepare query statement's by what report type was selected.
        //if report type selected was for product.
        if($report_type == 'PRODUCT'){ 
            $qStr = "SELECT p.`Code`,p.`Description`,(pl.`Code`) AS `ProductLine`,SUM(sid.`Qty`) AS `TotalQtySold`
                        FROM `salesinvoice` si
                        INNER JOIN `salesinvoicedetails` sid ON sid.`SalesInvoiceID` = si.`ID`
                        INNER JOIN `product` p ON p.`ID` = sid.`ProductID`
                        INNER JOIN `product` pl ON pl.`ID` = p.`ParentID`
                        WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',si.`HOGeneralID`,' '))
                        AND LOCATE(BINARY '-$BranchID',CONCAT(' ',sid.`HOGeneralID`,' '))
                        AND DATE(si.`TxnDate`) BETWEEN '$dateFrom' AND '$dateTo'
                        GROUP BY DATE(si.`TxnDate`),sid.`ProductID`
                        ORDER BY `TotalQtySold` DESC
                        LIMIT $records";
        }

        //if report type selected was for customer or network.
        if($report_type == 'CUSTOMER'){

            if($sales_type == 'DGS'){
                $fetchFields = "FORMAT(SUM(sfa.`TotalDGSSales`),2) AS `TotalBilledAmt`,
                                FORMAT((SUM(sfa.`TotalDGSSales`) - SUM(sfa.`TotalCPIAmount`)),2) AS `AmtLessCPI`";
                $orderBy = "ORDER BY SUM(sfa.`TotalDGSSales`) DESC";
            }

            if($sales_type == 'INVOICE'){
                $fetchFields = "FORMAT(SUM(sfa.`TotalInvoiceAmount`),2) AS `TotalBilledAmt`,
                                FORMAT((SUM(sfa.`TotalInvoiceAmount`) - SUM(sfa.`TotalCPIAmount`)),2) AS `AmtLessCPI`";
                $orderBy = "ORDER BY SUM(sfa.`TotalInvoiceAmount`) DESC";
            }

            $qStr = "SELECT c.`Code`,c.`Name`,
                            $fetchFields
                        FROM `tpi_sfasummary` sfa
                        INNER JOIN `customer` c ON c.`ID` = sfa.`CustomerID`
                        WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',sfa.`HOGeneralID`,' '))
                        AND LOCATE(BINARY '-$BranchID',CONCAT(' ',c.`HOGeneralID`,' '))
                        AND sfa.`IBMID` != sfa.`CustomerID`
                        AND sfa.`CampaignMonth` BETWEEN $MonthDateFrom AND $MonthDateTo
                        AND sfa.`CampaignYear` BETWEEN $YearDateFrom AND $YearDateTo
                        GROUP BY sfa.`CustomerID`
                        $orderBy
                        LIMIT $records";
        }

        if($report_type == 'NETWORK'){

            if($sales_type == 'DGS'){
                $fetchFields = "FORMAT(SUM(sfa.`TotalDGSSales`),2) AS `TotalBilledAmt`,
                                FORMAT((SUM(sfa.`TotalDGSSales`) - SUM(sfa.`TotalCPIAmount`)),2) AS `AmtLessCPI`";
                $orderBy = "ORDER BY SUM(sfa.`TotalDGSSales`) DESC";
            }

            if($sales_type == 'INVOICE'){
                $fetchFields = "FORMAT(SUM(sfa.`TotalInvoiceAmount`),2) AS `TotalBilledAmt`,
                                FORMAT((SUM(sfa.`TotalInvoiceAmount`) - SUM(sfa.`TotalCPIAmount`)),2) AS `AmtLessCPI`";
                $orderBy = "ORDER BY SUM(sfa.`TotalInvoiceAmount`) DESC";
            }

            $qStr = "SELECT c.`Code`,c.`Name`,
                            $fetchFields
                        FROM `tpi_sfasummary` sfa
                        INNER JOIN `customer` c ON c.`ID` = sfa.`IBMID`
                        WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',sfa.`HOGeneralID`,' '))
                        AND LOCATE(BINARY '-$BranchID',CONCAT(' ',c.`HOGeneralID`,' '))
                        AND sfa.`IBMID` = sfa.`CustomerID`
                        AND sfa.`CampaignMonth` BETWEEN $MonthDateFrom AND $MonthDateTo
                        AND sfa.`CampaignYear` BETWEEN $YearDateFrom AND $YearDateTo
                        GROUP BY sfa.`IBMID`
                        $orderBy
                        LIMIT $records";
        }

        $q = $mysqli->query($qStr);
        
        return $q;
    }

    /*
     * @description: Function used in generating report for Statement of Account.
     */
    function HOReportStatementOfAccountQuery($BranchID,$dateFrom,$dateTo,$trans_type,$account_no){
        global $mysqli;
        
        if($trans_type == 'ALL') $ANDTransType = "";
        else $ANDTransType = "AND dc.`MemoTypeID` = $trans_type";
        
        //Query statement that will get all customer records debit and credit depending on date range and transaction type set.
        $q = $mysqli->query("SELECT DATE_FORMAT(dc.`EnrollmentDate`,'%b %d, %Y') AS `Date`,
                                    dc.`CustomerID`,dc.`DocumentNo`,mem.`Name` AS `MemoType`,
                                    FORMAT(IFNULL(dc.`TotalAmount`,0.00),2) AS `TotalAmount`,
                                    FORMAT(IFNULL(car.`OutstandingAmount`,0.00),2) AS `RunningBalance`
                                FROM `dmcm` dc
                                INNER JOIN `memotype` mem ON mem.`ID` = dc.`MemoTypeID`
                                LEFT JOIN (SELECT car.`CustomerID`,car.`OutstandingAmount` AS `OutstandingAmount`
                                        FROM `customeraccountsreceivable` car
                                        WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',`HOGeneralID`,' '))
                                        AND car.`OutstandingAmount` > 0
                                        GROUP BY car.`CustomerID`
                                        ) car ON car.`CustomerID` = dc.`CustomerID`
                                WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',dc.`HOGeneralID`,' ')) 
                                AND dc.`CustomerID` = $account_no
                                $ANDTransType
                                AND DATE(dc.`EnrollmentDate`) BETWEEN '$dateFrom' AND '$dateTo'");
        return $q;
    }
    
    /*
     * @description: Function used in generating report for Network Salse Performance.
     */
    function HOReportNetworkSalesPerformanceQuery($BranchID,$Month,$Year,$netFrom,$netTo,$pmg,$SFL){
        global $mysqli;
        $add_where = '';
        
        if($pmg != 'ALL') $add_where = " AND sfapmg.`PMGType` = '$pmg'";
        if($SFL) $add_where.= " AND c.`CustomerTypeID` = $SFL";
        
        $q = $mysqli->query("SELECT sfa.`IBMID`,c.`Code`,sfapmg.`PMGType`,
                                        SUM(sfapmg.`TotalDGSAmount`) AS `Sales`,
                                        sfa.`TotalNumberOfRecruits`,sfa.`TotalNumberOfActives`
                                    FROM `tpi_sfasummary` sfa
                                    INNER JOIN `tpi_sfasummary_pmg` sfapmg ON sfapmg.`IBMID` = sfa.`IBMID`
                                    INNER JOIN `customer` c ON c.`ID` = sfa.`IBMID`
                                    WHERE sfa.`IBMID` = sfa.`CustomerID`
                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',sfa.`HOGeneralID`,' '))
                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',c.`HOGeneralID`,' '))
                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',sfapmg.`HOGeneralID`,' '))
                                    AND sfa.`CampaignMonth` = $Month AND sfa.`CampaignYear` = $Year
                                    AND sfapmg.`CampaignMonth` = $Month AND sfapmg.`CampaignYear` = $Year
                                    AND c.`Code` BETWEEN '$netFrom' AND '$netTo'
                                    $add_where
                                    GROUP BY sfapmg.`PMGType`,sfapmg.`IBMID`
                                    ORDER BY sfapmg.`IBMID`");
        
        return $q;
    }

    /*
     * @description: Function used in generating report for Memo Register.
     */
    function HOReportMemoRegisterQuery($BranchID,$Date,$Reason){
        global $mysqli;
        $xReason = ($Reason=="ALL")?"":" AND dc.`ReasonID` = $Reason";
		
        $q = $mysqli->query("SELECT dc.`CustomerID`,c.`Name`,DATE_FORMAT(dc.`TxnDate`,'%b %d, %Y') AS `TransactionDate`,
                                        dc.`DocumentNo`,dc.`Remarks`,
                                        r.`Name` AS `Reason`,
                                        mt.`Name` AS `DMCMType`,IFNULL(FORMAT(dc.`TotalAmount`,2),0.00) AS `DCMCTotalAmount`
                                    FROM `dmcm` dc
                                    INNER JOIN `customer` c ON c.`ID` = dc.`CustomerID`
                                    INNER JOIN `reason` r ON r.`ID` = dc.`ReasonID`
                                    INNER JOIN `memotype` mt ON mt.`ID` = dc.`MemoTypeID`
                                    WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',c.`HOGeneralID`,' '))
                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',dc.`HOGeneralID`,' '))
                                    AND DATE(dc.`TxnDate`) = '$Date' ".$xReason);
        return $q;
    }
    
    /*
     * @description: Function that will get results for daily cash receipts report...
     */
    function HOReportDailyCashReceiptsQuery($BranchID,$Date){
        global $mysqli;
        /*
        * Query process was done in different queries because tables are separated as the GIC did the OR table structures.
        * If you think there are better things to do with the process, please improve code.
        */
        //Let's get total amount of cash ORs
        $ORCash = $mysqli->query("   SELECT IFNULL(FORMAT(SUM(orc.`TotalAmount`),2),0.00) AS `ORCashTotal`
                                     FROM `officialreceiptcash` orc
                                     INNER JOIN `officialreceipt` orec ON orec.`ID` = orc.`OfficialReceiptID`
                                     WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',orc.`HOGeneralID`,' '))
                                     AND LOCATE(BINARY '-$BranchID',CONCAT(' ',orec.`HOGeneralID`,' '))
                                     AND orec.`StatusID` = 7
                                     AND DATE(orec.`TxnDate`) = '$Date'
									-- SELECT IFNULL(FORMAT(SUM(orc.`TotalAmount`),2),0.00) AS `ORCashTotal`
									-- FROM `officialreceiptcash` orc where date(orc.EnrollmentDate) = '$Date'
									");
        if($ORCash->num_rows > 0){
            $ORCashTotal = $ORCash->fetch_object()->ORCashTotal;
        }else{
            $ORCashTotal = 0.00;
        }

        //Let's get total amount of check ORs
        $ORCheque = $mysqli->query("
									SELECT IFNULL(FORMAT(SUM(orck.`TotalAmount`),2),0.00) AS `ORChequeTotal`
                                    FROM `officialreceiptcheck` orck
                                    INNER JOIN `officialreceipt` orec ON orec.`ID` = orck.`OfficialReceiptID`
                                    WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',orck.`HOGeneralID`,' '))
                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',orec.`HOGeneralID`,' '))
                                    AND orec.`StatusID` = 7
                                    AND DATE(orec.`TxnDate`) = '$Date'
									-- SELECT IFNULL(FORMAT(SUM(orck.`TotalAmount`),2),0.00) AS `ORChequeTotal
									-- FROM `officialreceiptcheck` orck
									-- where date(orck.EnrollmentDate)='$Date' and LOCATE(BINARY '-$BranchID',CONCAT(' ',orck.`HOGeneralID`,' '))
									");
        if($ORCheque->num_rows > 0){
            $ORChequeTotal = $ORCheque->fetch_object()->ORChequeTotal;
        }else{
            $ORChequeTotal = 0.00;
        }

        //Let's get total amount of deposit ORs
        $ORDeposit = $mysqli->query("
									SELECT IFNULL(FORMAT(SUM(orcd.`TotalAmount`),2),0.00) AS `ORDepositTotal`
                                    FROM `officialreceiptdeposit` orcd
                                    INNER JOIN `officialreceipt` orec ON orec.`ID` = orcd.`OfficialReceiptID`
                                    WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',orcd.`HOGeneralID`,' '))
                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',orec.`HOGeneralID`,' '))
                                    AND orec.`StatusID` = 7
                                    AND DATE(orec.`TxnDate`) = '$Date'
									
									-- SELECT IFNULL(FORMAT(SUM(orcd.`TotalAmount`),2),0.00) AS `ORDepositTotal`
									-- FROM `officialreceiptdeposit` orcd
									-- where date(orcd.EnrollmentDate)='$Date' and LOCATE(BINARY '-$BranchID',CONCAT(' ',orcd.`HOGeneralID`,' '))
									");
        if($ORDeposit->num_rows > 0){
            $ORDepositTotal = $ORDeposit->fetch_object()->ORDepositTotal;
        }else{
            $ORDepositTotal = 0.00;
        }

        //Let's get total amount of cancelled cash ORs
        $ORCashCancelled = $mysqli->query("
									SELECT IFNULL(FORMAT(SUM(orc.`TotalAmount`),2),0.00) AS `ORCashCancelledTotal`
                                    FROM `officialreceiptcash` orc
                                    INNER JOIN `officialreceipt` orec ON orec.`ID` = orc.`OfficialReceiptID`
                                    WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',orc.`HOGeneralID`,' '))
                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',orec.`HOGeneralID`,' '))
                                    AND orec.`StatusID` = 8
                                    AND DATE(orec.`TxnDate`) = '$Date'
									-- SELECT IFNULL(FORMAT(SUM(orc.`TotalAmount`),2),0.00) AS `ORCashCancelledTotal`
									-- FROM `officialreceiptcash` orc
									-- where date(orc.EnrollmentDate)='$date'  and LOCATE(BINARY '-$BranchID',CONCAT(' ',orc.`HOGeneralID`,' '))
									");
        if($ORCashCancelled->num_rows > 0){
            $ORCashCancelledTotal = $ORCashCancelled->fetch_object()->ORCashCancelledTotal;
        }else{
            $ORCashCancelledTotal = 0.00;
        }

        //Let's get total amount of cancelled check ORs
        $ORChequeCancelled = $mysqli->query("
									SELECT IFNULL(FORMAT(SUM(orck.`TotalAmount`),2),0.00) AS `ORChequeCancelledTotal`
                                    FROM `officialreceiptcheck` orck
                                    INNER JOIN `officialreceipt` orec ON orec.`ID` = orck.`OfficialReceiptID`
                                    WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',orck.`HOGeneralID`,' '))
                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',orec.`HOGeneralID`,' '))
                                    AND orec.`StatusID` = 8
                                    AND DATE(orec.`TxnDate`) = '$Date'
									-- SELECT IFNULL(FORMAT(SUM(orck.`TotalAmount`),2),0.00) AS `ORChequeCancelledTotal`
									-- FROM `officialreceiptcheck` orck where date(orck.EnrollmentDate)='$Date' and LOCATE(BINARY '-$BranchID',CONCAT(' ',orck.`HOGeneralID`,' '))
									
									");
        if($ORChequeCancelled->num_rows > 0){
            $ORChequeCancelledTotal = $ORChequeCancelled->fetch_object()->ORChequeCancelledTotal;
        }else{
            $ORChequeCancelledTotal = 0.00;
        }
        
        $returnObj = (object) array('ORCashTotal' => $ORCashTotal,'ORChequeTotal' => $ORChequeTotal, 'ORDepositTotal' => $ORDepositTotal,
                                    'ORCashCancelledTotal' => $ORCashCancelledTotal, 'ORChequeCancelledTotal' => $ORChequeCancelledTotal);
        
        return $returnObj;
    }
    
    /*
     * @description: Function used in generating report for online sales inquiry.
     */
    function HOReportOnlineSalesInquiryQuery($BranchID,$Month,$Year,$CURDATE){
        global $mysqli;
        
        //Using campaign month and year...
        $c = $mysqli->query("SELECT IFNULL(FORMAT(SUM(sfa.`TotalDGSSales`),2),0.00) AS `CampaignToDateDGS`,
                                    IFNULL(FORMAT(SUM(sfa.`TotalInvoiceAmount`),2),0.00) AS `InvoiceAmount`,
                                    IFNULL(FORMAT(SUM(sfa.`TotalDGSSales`) - SUM(sfa.`TotalCPIAmount`),2),0.00) AS `CampaignToDateDGSLessCPI`,
                                    IFNULL(FORMAT(SUM(sfa.`TotalInvoiceAmount`) - SUM(sfa.`TotalCPIAmount`),2),0.00) AS `InvoiceAmountLessCPI`
                                FROM `tpi_sfasummary` sfa
                                WHERE LOCATE(BINARY -'$BranchID',CONCAT(' ',sfa.`HOGeneralID`,' '))
                                AND sfa.`CampaignMonth` = $Month AND sfa.`CampaignYear` = $Year");

        //For today... IFNULL(FORMAT(((SUM(`GrossAmount`) - SUM(`TotalCPI`)) * 0.75),2),0.00)
        $today = $mysqli->query("SELECT IFNULL(FORMAT(SUM(`GrossAmount`),2),0.00) AS `TodayTotalDGS`,
                                        IFNULL(FORMAT(SUM(`NetAmount`),2),0.00) AS `TodayTotaInvoiceAmount`,
                                        IFNULL(FORMAT(SUM(`GrossAmount`) - SUM(`TotalCPI`),2),0.00) AS `TodayTotalDGSLessCPI`,
                                        IFNULL(FORMAT(SUM(`NetAmount`) - SUM(`TotalCPI`),2),0.00) AS `TodayTotalInvoiceAmountLessCPI`
                                    FROM `salesinvoice`
                                    WHERE LOCATE(BINARY -'$BranchID',CONCAT(' ',`HOGeneralID`,' '))
                                    AND DATE(`TxnDate`) = '$CURDATE'");
        
        $returnObj = (object) array('Campaign' => $c, 'Today' => $today);
        
        return $returnObj;
    }

    /*
     * @description: Function used in generating report for Daily Bullet Report.
     */
    function HOReportDailyBulletReportQuery($BranchID,$Month,$Year){
        global $mysqli;
		        
		$q = $mysqli->query("SELECT
							PMGCode PMGType,
							FORMAT(SUM(DGSAmount),2) TotalDGSAmount,
							FORMAT(SUM(CSPAmount),2) TotalCSPAmount,
							FORMAT((SUM(DGSAmount) - SUM(CPI)),2) TotalCSPLessCPIAmt,
							FORMAT(SUM(Netinvoice),2) TotalInvoiceAmount,
							FORMAT(SUM(TotalUnitSold), 0) TotalUnitsSold
							FROM
								(SELECT TRIM(pmg.Code) PMGCode,
								(si.GrossAmount - si.BasicDiscount) DGSAmount,
								si.GrossAmount CSPAmount,
								si.TotalCPI CPI,
								si.NetAmount NetInvoice,
								SUM(sid.Qty) TotalUnitSold
								FROM salesinvoice si
									INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
									INNER JOIN salesinvoicedetails sid
										ON si.ID = sid.SalesInvoiceID
										AND LOCATE(CONCAT('-', b.ID), sid.HOGeneralID) > 0
									INNER JOIN tpi_pmg pmg ON pmg.ID = sid.PMGID
								WHERE DATE_FORMAT(si.TxnDate, '%c') = $Month
								AND DATE_FORMAT(si.TxnDate, '%y') = $Year
								AND b.ID = $BranchID
								GROUP BY pmg.ID, si.ID) atbl
							GROUP BY PMGCode");
		
        /*$q = $mysqli->query("SELECT sfapmg.`PMGType`,
                                    FORMAT(SUM(DISTINCT(sfapmg.`TotalDGSAmount`)),2) AS `TotalDGSAmount`,
                                    FORMAT(SUM(DISTINCT(sfapmg.`TotalCSPAmount`)),2) AS `TotalCSPAmount`,
                                    FORMAT(SUM(DISTINCT(sfapmg.`TotalCSPAmount`)) - SUM(DISTINCT(sfapmg.`TotalCPIAmount`)),2) AS `TotalCSPLessCPIAmt`,
                                    FORMAT(SUM(DISTINCT(sfapmg.`TotalInvoiceAmount`)),2) AS `TotalInvoiceAmount`, 
                                    SUM(sid.`Qty`) AS `TotalUnitsSold`        
                                FROM `tpi_sfasummary_pmg` sfapmg
                                INNER JOIN `salesinvoice` si ON si.`CustomerID` = sfapmg.`CustomerID`
                                INNER JOIN `salesinvoicedetails` sid ON sid.`SalesInvoiceID` = si.`ID`
                                LEFT JOIN `tpi_pmg` pmg ON pmg.`ID` = sid.`PMGID`
                                WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',sfapmg.`HOGeneralID`,' '))
                                AND LOCATE(BINARY '-$BranchID',CONCAT(' ',si.`HOGeneralID`,' '))
                                AND LOCATE(BINARY '-$BranchID',CONCAT(' ',sid.`HOGeneralID`,' '))
                                AND sfapmg.`CampaignMonth` = $Month AND sfapmg.`CampaignYear` = $Year
                                AND MONTH(si.`TxnDate`) = $Month AND DATE_FORMAT(si.`TxnDate`,'%y') = $Year
                                AND sfapmg.`PMGType` = pmg.`Code`
                                GROUP BY sfapmg.`PMGType`");*/
        return $q;
    }

    /*
     * @description: Function used in generating report for Applied Payment Report.
     */
    function HOReportAppliedPaymentReportQuery($BranchID,$CustomerID,$Date){
        global $mysqli;
        
        $q = $mysqli->query("SELECT DATE_FORMAT(orec.`TxnDate`,'%b %d, %Y') AS `PaymentDate`,
                                        orec.`DocumentNo` AS `ORNumber`,FORMAT(orec.`TotalAmount`,2) AS `Amount`,
                                        si.`DocumentNo` AS `InvoiceNo`,DATE_FORMAT(si.`TxnDate`,'%b %d, %Y') AS `InvoiceDate`,
                                        DATE_FORMAT(si.`EffectivityDate`,'%b %d, %Y') AS `InvoiceDueDate`,
                                        FORMAT(orecd.`TotalAmount`,2) AS `AmountApplied`
                                    FROM `officialreceipt` orec
                                    INNER JOIN `officialreceiptdetails` orecd ON orecd.`OfficialReceiptID` = orec.`ID`
                                    INNER JOIN `salesinvoice` si ON si.`ID` = orecd.`RefTxnID`
                                    WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',orec.`HOGeneralID`,' '))
                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',orecd.`HOGeneralID`,' '))
                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',si.`HOGeneralID`,' '))
                                    AND orec.`CustomerID` = $CustomerID
                                    AND DATE(orec.`TxnDate`) = '$Date'");
        return $q;
    }
    
    /*
     * @description: Function used in generating report for Valuation Report.
     */
    function HOReportValuationReportQuery($BranchID,$Date,$location,$productLine,$pmg,$keyword){
        global $mysqli;
        
        if($pmg) $ANDpmg = " AND tpmg.`ID` = $pmg";
        else $ANDpmg = "";
        
        if($productLine) $ANDpline = " AND p.`ParentID` = $productLine";
        else $ANDpline = "";
        
        if($location) $ANDlocation = " AND i.`WarehouseID` = $location";
        else $ANDlocation = "";
        
        $q = $mysqli->query("SELECT SQL_CALC_FOUND_ROWS DISTINCT
                                    sl.`TxnDate`,
                                    i.`ID`,
                                    i.`SOH`,
                                    i.`ProductID`,
                                    p.`Code` AS `ItemCode`,
                                    p.`Name` AS `ItemDescription`,
                                    u.`Name` AS `UOM`,
                                    w.`Name`,
                                    w.`ID` AS `WarehouseID`,
                                    tpmg.`Name` AS `PMGName`,
                                    (SELECT `Code` FROM `product` p1 WHERE p1.`ID`= p.`ParentID`) AS `ProdLineCode`,
                                    CONCAT( (SELECT `Code` FROM `product` p1 WHERE p1.`ID`= p.`ParentID`), '-' ,(SELECT `Name` FROM `product` p1 WHERE p1.`ID`= p.`ParentID`)) AS `ProdLine`,
                                    (SELECT IFNULL(SUM(QtyOut),0) QtyOut FROM `stocklog` sl1 WHERE sl1.`InventoryID`= i.`ID` AND DATE(`TxnDate`) > '$Date') AS `QtyOut`,
                                    (SELECT IFNULL(SUM(QtyIn),0) QtyIn FROM `stocklog` sl1 WHERE sl1.`InventoryID`= i.`ID` AND DATE(`TxnDate`) > '$Date') AS `QtyIn`,
                                    ROUND(pp.`UnitPrice`,2) AS `ProductPrice`,
                                    ROUND((pp.`UnitPrice`* i.`SOH`),2) AS `TotalValue`
                                FROM `inventory` i
                                    INNER JOIN `product` p ON p.`ID` = i.`ProductID`
                                    INNER JOIN `unittype` u ON u.`ID` = p.`UnitTypeID`
                                    INNER JOIN `warehouse` w ON w.`ID` = i.`WarehouseID` AND w.`StatusID` = 1
                                    INNER JOIN `productpricing`pp ON pp.`ProductID` = p.`ID`
                                    INNER JOIN `tpi_pmg` tpmg ON tpmg.`ID`= pp.`PMGID`
                                    INNER JOIN ( SELECT `InventoryID`, `QtyOut` , `TxnDate` FROM `stocklog` ) sl ON sl.`InventoryID` = i.`ID`
                                WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',i.`HOGeneralID`,' '))
                                AND (p.`Name` LIKE '%$keyword%' OR p.`Code` LIKE '%$keyword%')
                                AND DATE(sl.`TxnDate`) <= '$Date'
                                $ANDlocation
                                $ANDpline
                                $ANDpmg
                                GROUP BY p.`ID`
                                ORDER BY `ProdLineCode`,p.`Code` ASC");
        return $q;
    }
    
    /*
     * @description: Function used in generating report for inventory Transaction Register.
     */
    function HOReportTransactionRegisterQuery($BranchID,$dateFrom,$dateTo,$mtype,$keyword){
        global $mysqli;
        
        if($mtype) $ANDmtype = " AND mt.`ID` = $mtype";
        else $ANDmtype = "";
        
        $q = $mysqli->query("SELECT DISTINCT `IsOut`,
                                                `WarehouseID`,
                                                `WarehouseName`,
                                                `ProductID`,
                                                `Code`,
                                                `Product`,
                                                `UnitPrice`,
                                                `InventoryID`,
                                                `MovementType`,
                                                `TxnDate`,
                                                `RefTxnID`,
                                                `RefNo`,
                                                `QtyIn`,
                                                `QtyOut`,
                                                `EndingBalance`,
                                                `RefTxnNo`,
                                                `IssuingBranch`,
                                                `ReceivingBranch`,
                                                `Location1`,
                                                `Location2`,
                                                `Qty`
                                    FROM (
                                            SELECT DISTINCT
                                                    mt.IsOut,
                                                    i.WarehouseID,
                                                    w.Name WarehouseName,
                                                    s.ProductID,
                                                    p.Code,
                                                    p.Name Product,
                                                    ROUND(pp.UnitPrice,2) UnitPrice,
                                                    s.InventoryID,
                                                    mt.Name MovementType,
                                                    DATE_FORMAT(iio.LastModifiedDate, '%m/%d/%Y') TxnDate,
                                                    s.RefTxnID,
                                                    s.RefNo,
                                                    SUM(s.QtyIn) QtyIn,
                                                    SUM(s.QtyOut) QtyOut,
                                                    SUM(s.QtyIn) - SUM(s.QtyOut) EndingBalance,
                                                    CAST(CONCAT('II', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) AS CHAR) RefTxnNo,
                                                    b.`Code` IssuingBranch,
                                                    b2.`Code` ReceivingBranch,
                                                    (SELECT w1.Code FROM `warehouse` w1 WHERE w1.`ID`= 1 ) Location1,
                                                    (SELECT w2.Code FROM `warehouse` w2 WHERE w2.`ID`= 1 ) Location2,
                                                    CASE WHEN  SUM(s.QtyIn) <= 0
                                                    THEN
                                                        SUM(s.QtyOut)
                                                    ELSE
                                                        SUM(s.QtyIn)
                                                    END Qty
                                            FROM stocklog s
                                                    INNER JOIN inventory i ON i.ID = s.InventoryID
                                                    INNER JOIN movementtype mt ON mt.ID = s.MovementTypeID
                                                    INNER JOIN product p ON p.ID = s.ProductID
                                                    LEFT JOIN productpricing pp ON p.ID =  pp.ProductID
                                                    INNER JOIN warehouse w ON w.ID = i.WarehouseID
                                                    INNER JOIN `inventoryinout` iio ON iio.`MovementTypeID` = mt.`ID` AND iio.`ID`= s.`RefTxnID`
                                                    INNER JOIN `branch` b ON b.`ID`=iio.`BranchID`
                                                    INNER JOIN branch b2 ON b2.`ID` = iio.`ToBranchID`
                                            WHERE
                                                    (CONCAT('II', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) LIKE UPPER('%$keyword%')
                                                    OR s.RefTxnID LIKE UPPER('%$keyword%')
                                                    OR p.`Code` LIKE UPPER('%$keyword%')
                                                    OR p.`Name` LIKE UPPER('%$keyword%'))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',i.`HOGeneralID`,' '))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',iio.`HOGeneralID`,' '))
                                                    AND mt.ID IN (1,3)  
                                                    $ANDmtype
                                                    AND iio.LastModifiedDate BETWEEN '$dateFrom' AND '$dateTo'
                                                    GROUP BY p.ID, s.RefTxnID,mt.ID
                                        UNION ALL
                                            SELECT DISTINCT
                                                    mt.IsOut,
                                                    i.WarehouseID,
                                                    w.Name WarehouseName,
                                                    s.ProductID,
                                                    p.Code,
                                                    p.Name Product,
                                                    ROUND(pp.UnitPrice,2) UnitPrice,
                                                    s.InventoryID,
                                                    mt.Name MovementType,
                                                    DATE_FORMAT(iio.LastModifiedDate, '%m/%d/%Y') TxnDate,
                                                    s.RefTxnID,
                                                    s.RefNo,
                                                    SUM(s.QtyIn) QtyIn,
                                                    SUM(s.QtyOut) QtyOut,
                                                    SUM(s.QtyIn) - SUM(s.QtyOut) EndingBalance,
                                                    CAST(CONCAT('IO', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) AS CHAR) RefTxnNo,
                                                    b.`Code` IssuingBranch,
                                                    b2.`Code` ReceivingBranch,
                                                    (SELECT w1.Code FROM `warehouse` w1 WHERE w1.`ID`= 1 ) Location1,
                                                    (SELECT w2.Code FROM `warehouse` w2 WHERE w2.`ID`= 1 ) Location2,
                                                    CASE WHEN  SUM(s.QtyIn) <= 0
                                                    THEN
                                                        SUM(s.QtyOut)
                                                    ELSE
                                                        SUM(s.QtyIn)
                                                    END Qty
                                            FROM stocklog s
                                                    INNER JOIN inventory i ON i.ID = s.InventoryID
                                                    INNER JOIN movementtype mt ON mt.ID = s.MovementTypeID
                                                    INNER JOIN product p ON p.ID = s.ProductID
                                                    LEFT JOIN productpricing pp ON p.ID =  pp.ProductID
                                                    INNER JOIN warehouse w ON w.ID = i.WarehouseID
                                                    INNER JOIN `inventoryinout` iio ON iio.`MovementTypeID` = mt.`ID` AND iio.`ID`= s.`RefTxnID`
                                                    INNER JOIN `branch` b ON b.`ID`=iio.`BranchID`
                                                    INNER JOIN branch b2 ON b2.`ID` = iio.`ToBranchID`
                                            WHERE
                                                    (CONCAT('IO', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) LIKE UPPER('%$keyword%')
                                                    OR s.RefTxnID LIKE UPPER('%$keyword%')
                                                    OR p.`Code` LIKE UPPER('%$keyword%')
                                                    OR p.`Name` LIKE UPPER('%$keyword%'))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',i.`HOGeneralID`,' '))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',iio.`HOGeneralID`,' '))
                                                    AND mt.ID IN (2,4)    
                                                    $ANDmtype
                                                    AND DATE(iio.LastModifiedDate) BETWEEN '$dateFrom' AND '$dateTo'
                                                    GROUP BY p.ID, s.RefTxnID,mt.ID
                                        UNION ALL
                                            SELECT DISTINCT
                                                    mt.IsOut,
                                                    i.WarehouseID,
                                                    w.Name WarehouseName,
                                                    s.ProductID,
                                                    p.Code,
                                                    p.Name Product,
                                                    ROUND(pp.UnitPrice,2) UnitPrice,
                                                    s.InventoryID,
                                                    mt.Name MovementType,
                                                    DATE_FORMAT(ia.TransactionDate, '%m/%d/%Y') TxnDate,
                                                    s.RefTxnID,
                                                    s.RefNo,
                                                    SUM(s.QtyIn) QtyIn,
                                                    SUM(s.QtyOut) QtyOut,
                                                    SUM(s.QtyIn) - SUM(s.QtyOut) EndingBalance,
                                                    CAST(CONCAT('AD', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) AS CHAR)  RefTxnNo,
                                                    b.`Code` IssuingBranch,
                                                    ''--'' ReceivingBranch,
                                                    (SELECT w1.Code FROM `warehouse` w1 WHERE w1.`ID`=i.`WarehouseID` ) Location1,
                                                    '--' Location2,
                                                    CASE WHEN  SUM(s.QtyIn) <= 0
                                                    THEN
                                                        SUM(s.QtyOut)
                                                    ELSE
                                                        SUM(s.QtyIn)
                                                    END Qty
                                            FROM stocklog s
                                                    INNER JOIN `inventory` i ON i.ID = s.InventoryID
                                                    INNER JOIN `movementtype` mt ON mt.ID = s.MovementTypeID
                                                    INNER JOIN `product` p ON p.ID = s.ProductID
                                                    LEFT JOIN `productpricing` pp ON p.ID =  pp.ProductID
                                                    INNER JOIN `warehouse` w ON w.ID = i.WarehouseID
                                                    INNER JOIN `inventoryadjustment` ia ON ia.`ID`= s.`RefTxnID`
                                                    INNER JOIN `branch` b ON b.`ID`= ia.`BranchID`
                                            WHERE
                                                (CONCAT('AD', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) LIKE UPPER('%$keyword%')
                                                    OR s.RefTxnID LIKE UPPER('%$keyword%')
                                                    OR p.`Code` LIKE UPPER('%$keyword%')
                                                    OR p.`Name` LIKE UPPER('%$keyword%'))
                                                AND LOCATE(BINARY '-$BranchID',CONCAT(' ',i.`HOGeneralID`,' '))
                                                AND LOCATE(BINARY '-$BranchID',CONCAT(' ',ia.`HOGeneralID`,' '))
                                                AND  mt.ID IN (5,6,9,10,11,12) 
                                                $ANDmtype
                                                AND ia.TransactionDate BETWEEN '$dateFrom' AND '$dateTo'
                                                GROUP BY p.ID, s.RefTxnID,mt.ID
                                        UNION ALL
                                            SELECT DISTINCT
                                                    mt.IsOut,
                                                    i.WarehouseID,
                                                    w.Name WarehouseName,
                                                    s.ProductID,
                                                    ROUND(pp.UnitPrice,2) UnitPrice,
                                                    p.Code,
                                                    p.Name Product,
                                                    s.InventoryID,
                                                    mt.Name MovementType,
                                                    DATE_FORMAT(ic.TransactionDate, '%m/%d/%Y') TxnDate,
                                                    s.RefTxnID,
                                                    s.RefNo,
                                                    SUM(s.QtyIn) QtyIn,
                                                    SUM(s.QtyOut) QtyOut,
                                                    SUM(s.QtyIn) - SUM(s.QtyOut) EndingBalance,
                                                    CAST(CONCAT('IC', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) AS CHAR) RefTxnNo,
                                                    b.`Code` IssuingBranch,
                                                    '--'ReceivingBranch,
                                                    l.`Code` Location1,
                                                    '--' Location2,
                                                    CASE WHEN  SUM(s.QtyIn) <= 0
                                                    THEN
                                                        SUM(s.QtyOut)
                                                    ELSE
                                                        SUM(s.QtyIn)
                                                    END Qty
                                            FROM stocklog s
                                                    INNER JOIN `inventory` i ON i.ID = s.InventoryID
                                                    INNER JOIN `movementtype` mt ON mt.ID = s.MovementTypeID
                                                    INNER JOIN `product` p ON p.ID = s.ProductID
                                                    LEFT JOIN `productpricing` pp ON p.ID =  pp.ProductID
                                                    INNER JOIN `warehouse` w ON w.ID = i.WarehouseID
                                                    INNER JOIN `inventorycount` ic ON ic.`MovementTypeID`= mt.Id AND s.`RefTxnID` = ic.`ID`
                                                    INNER JOIN `branch` b ON b.`ID`= ic.`BranchID`
                                                    INNER JOIN `inventorycountdetails` icDetails ON icDetails.`InventoryCountID` = icDetails.ID
                                                    INNER JOIN  `location` l ON l.`ID` = icDetails.`LocationID`
                                            WHERE
                                                    (CONCAT('IC', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) LIKE UPPER('%$keyword%')
                                                    OR s.RefTxnID LIKE UPPER('%$keyword%')
                                                    OR p.`Code` LIKE UPPER('%$keyword%')
                                                    OR p.`Name` LIKE UPPER('%$keyword%'))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',i.`HOGeneralID`,' '))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',ic.`HOGeneralID`,' '))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',icDetails.`HOGeneralID`,' '))
                                                    AND mt.ID IN (6)  
                                                    $ANDmtype
                                                AND ic.TransactionDate BETWEEN '$dateFrom' AND '$dateTo'
                                                    GROUP BY p.ID, s.RefTxnID,mt.ID
                                        UNION ALL
                                            SELECT DISTINCT
                                                    mt.IsOut,
                                                    i.WarehouseID,
                                                    w.Name WarehouseName,
                                                    s.ProductID,
                                                    p.Code,
                                                    p.Name Product,
                                                    ROUND(pp.UnitPrice,2) UnitPrice,
                                                    s.InventoryID,
                                                    mt.Name MovementType,
                                                    DATE_FORMAT(it.TransactionDate, '%m/%d/%Y') TxnDate,
                                                    s.RefTxnID,
                                                    s.RefNo,
                                                    SUM(s.QtyIn) QtyIn,
                                                    SUM(s.QtyOut) QtyOut,
                                                    SUM(s.QtyIn) - SUM(s.QtyOut) EndingBalance,
                                                    CAST(CONCAT('TR', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) AS CHAR) RefTxnNo,
                                                    b.`Code` IssuingBranch,
                                                    b.`Code` ReceivingBranch,
                                                    (SELECT w3.Code FROM `warehouse` w3 WHERE w3.`ID`= w.`ID` ) Location1,
                                                    (SELECT w4.Code FROM `warehouse` w4 WHERE w4.`ID`= w1.`ID` ) Location2,
                                                    CASE WHEN  SUM(s.QtyIn) <= 0
                                                    THEN
                                                        SUM(s.QtyOut)
                                                    ELSE
                                                        SUM(s.QtyIn)
                                                    END Qty
                                            FROM stocklog s
                                                    INNER JOIN `inventory` i ON i.ID = s.InventoryID
                                                    INNER JOIN `movementtype` mt ON mt.ID = s.MovementTypeID
                                                    INNER JOIN `product` p ON p.ID = s.ProductID
                                                    LEFT JOIN `productpricing` pp ON p.ID =  pp.ProductID
                                                    INNER JOIN `inventorytransfer` it ON it.`MovementTypeID` = s.`MovementTypeID` AND s.`RefTxnID`= it.`ID`
                                                    INNER JOIN `branch` b ON b.`ID`= it.`BranchID`
                                                    INNER JOIN `warehouse` w ON w.ID = it.`FromWarehouseID`
                                                    INNER JOIN `warehouse` w1 ON w1.ID =it.`ToWarehouseID`
                                            WHERE
                                                    (CONCAT('TR', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) LIKE UPPER('%$keyword%')
                                                    OR s.RefTxnID LIKE UPPER('%$keyword%')
                                                    OR p.`Code` LIKE UPPER('%$keyword%')
                                                    OR p.`Name` LIKE UPPER('%$keyword%'))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',i.`HOGeneralID`,' '))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',it.`HOGeneralID`,' '))
                                                    AND mt.ID IN (7,8,15,16)    
                                                    $ANDmtype
                                                    AND it.TransactionDate BETWEEN '$dateFrom' AND '$dateTo'
                                                    GROUP BY p.ID, s.RefTxnID,mt.ID
                                        UNION ALL
                                            SELECT DISTINCT
                                                    mt.IsOut,
                                                    i.WarehouseID,
                                                    w.Name WarehouseName,
                                                    s.ProductID,
                                                    p.Code,
                                                    p.Name Product,
                                                    ROUND(pp.UnitPrice,2) UnitPrice,
                                                    s.InventoryID,
                                                    mt.Name MovementType,
                                                    DATE_FORMAT(si.TxnDate, '%m/%d/%Y') TxnDate,
                                                    s.RefTxnID,
                                                    s.RefNo,
                                                    SUM(s.QtyIn) QtyIn,
                                                    SUM(s.QtyOut) QtyOut,
                                                    SUM(s.QtyIn) - SUM(s.QtyOut) EndingBalance,
                                                    CAST(CONCAT('SI', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) AS CHAR) RefTxnNo,
                                                    b.`Code` IssuingBranch,
                                                    '--'ReceivingBranch,
                                                    (SELECT w3.Code FROM `warehouse` w3 WHERE w3.`ID`= w.`ID` ) Location1,
                                                    '--' Location2,
                                                    CASE WHEN  SUM(s.QtyIn) <= 0
                                                    THEN
                                                        SUM(s.QtyOut)
                                                    ELSE
                                                        SUM(s.QtyIn)
                                                    END Qty
                                            FROM stocklog s
                                                    INNER JOIN `inventory` i ON i.ID = s.InventoryID
                                                    INNER JOIN `movementtype` mt ON mt.ID = s.MovementTypeID
                                                    INNER JOIN `product` p ON p.ID = s.ProductID
                                                    LEFT JOIN `productpricing` pp ON p.ID =  pp.ProductID
                                                    INNER JOIN `warehous`e w ON w.ID = i.WarehouseID
                                                    INNER JOIN `salesinvoice` si ON s.`RefTxnID`= si.ID AND s.`MovementTypeID` IN (13, 14, 17)
                                                    INNER JOIN `branch` b ON b.`ID` = si.`BranchID`
                                            WHERE
                                                    (CONCAT('SI', REPEAT('0', (8- LENGTH(s.RefTxnID))), s.RefTxnID) LIKE UPPER('%$keyword%')
                                                    OR s.RefTxnID LIKE UPPER('%$keyword%')
                                                    OR p.`Code` LIKE UPPER('%$keyword%')
                                                    OR p.`Name` LIKE UPPER('%$keyword%'))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',i.`HOGeneralID`,' '))
                                                    AND LOCATE(BINARY '-$BranchID',CONCAT(' ',si.`HOGeneralID`,' '))
                                                    AND mt.ID IN (13, 14, 17)   
                                                    $ANDmtype
                                                    AND si.TxnDate BETWEEN '$dateFrom' AND '$dateTo'
                                                    GROUP BY p.ID, s.RefTxnID,mt.ID
                                        ) a ORDER BY  TxnDate, MovementType ,RefTxnNo ASC");
        
        return $q;
    }
    
    /*
     * @description: Function used in generating report for Account Balance Inquiry.
     */
    function HOReportAccountBalanceInquiryQuery($BranchID,$SFL_ID,$CanPurchase,$accountFrom,$accountTo,$CLC = ''){
        global $mysqli;
        
        if($CLC) $AND_CLC = " AND lvl.credit_line = '$CLC'";
        else $AND_CLC = "";

        if($CanPurchase > 0):
            $JOINStr = " LEFT JOIN (SELECT `CustomerID`,SUM(`OutstandingAmount`) AS `TotalOutstandingBalance`
                                FROM `customeraccountsreceivable`
                                WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',`HOGeneralID`,' ')) 
                                GROUP BY `CustomerID`) crOB ON crOB.`CustomerID` = c.`ID`
                        LEFT JOIN (SELECT `CustomerID`,SUM(`OutstandingAmount`) AS `TotalOverDueBalance`
                                FROM `customeraccountsreceivable` 
                                WHERE `OutstandingAmount` > 0 AND `DaysDue` > 0
                                AND LOCATE(BINARY '-$BranchID',CONCAT(' ',`HOGeneralID`,' '))
                                GROUP BY `CustomerID`) crOverB ON crOverB.`CustomerID` = c.`ID`
                        LEFT JOIN (SELECT `CustomerID`,SUM(`Amount`) AS `Penalty` 
                                FROM `customerpenalty` 
                                WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',`HOGeneralID`,' '))
                                GROUP BY `CustomerID`) cp ON cp.`CustomerID` = c.`ID`";

            $SELECTFields = "IFNULL(FORMAT(crOB.`TotalOutstandingBalance`,2),0.00) AS `TotalOutstandingBalance`,
                            IFNULL(FORMAT(crOverB.`TotalOverDueBalance`,2),0.00) AS `TotalOverDueBalance`,
                            IFNULL(FORMAT(cp.`Penalty`,2),0.00) AS `TotalPenaltyAmount`";
            $WHEREAnd = " AND `TotalOutstandingBalance` > 0";
        else:
            $JOINStr = " LEFT JOIN (SELECT `IBMID`,COUNT(`CustomerID`) AS `LowerLevelsCtr` 
                                FROM `tpi_rcustomeribm`
                                WHERE `IBMID` != `CustomerID`
                                AND LOCATE(BINARY '-$BranchID',CONCAT(' ',`HOGeneralID`,' '))
                                GROUP BY `IBMID`) llc ON llc.`IBMID` = c.`ID`";
            $SELECTFields = "IFNULL(llc.`LowerLevelsCtr`,0) AS `LowerLevelsCtr`";
            $WHEREAnd = " AND `LowerLevelsCtr` > 0";
        endif; 

        $FullQry = "SELECT c.`ID`,c.`Code`,c.`Name`,lvl.`desc_code`,
                            $SELECTFields
                        FROM `customer` c
                        INNER JOIN `sfm_level` lvl ON lvl.`codeID` = c.`CustomerTypeID`
                        $JOINStr
                        WHERE c.`CustomerTypeID` = $SFL_ID
                        $WHEREAnd
                        AND LOCATE(BINARY '-$BranchID',CONCAT(' ',c.`HOGeneralID`,' '))
                        AND c.`Code` BETWEEN '$accountFrom' AND '$accountTo'
                        $AND_CLC
                        ORDER BY c.`Code` ASC";

        $q = $mysqli->query($FullQry);
        
        return $q;
    }
    
     /*
     * @description: Function used in getting accounts under a higher level selection in Account Balance Inquiry.
     */
    function HOReportABIGetNetworksQuery($BranchID,$IBMID){
       global $mysqli;
       
       $q = $mysqli->query("SELECT c.`ID`,c.`Code`,c.`Name`,lvl.`desc_code`,
                                    IFNULL(FORMAT(crOB.`TotalOutstandingBalance`,2),0.00) AS `TotalOutstandingBalance`,
                                    IFNULL(FORMAT(crOverB.`TotalOverDueBalance`,2),0.00) AS `TotalOverDueBalance`,
                                    IFNULL(FORMAT(cp.`Penalty`,2),0.00) AS `TotalPenaltyAmount`
                                FROM `customer` c
                                INNER JOIN `sfm_level` lvl ON lvl.`codeID` = c.`CustomerTypeID`
                                LEFT JOIN (SELECT `CustomerID`,`IBMID`,MAX(`EnrollmentDate`) 
                                            FROM `tpi_rcustomeribm` 
                                            GROUP BY `CustomerID`) ibm ON ibm.`CustomerID` = c.`ID`
                                LEFT JOIN (SELECT `CustomerID`,SUM(`OutstandingAmount`) AS `TotalOutstandingBalance`
                                        FROM `customeraccountsreceivable`
                                        WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',`HOGeneralID`,' ')) 
                                        GROUP BY `CustomerID`) crOB ON crOB.`CustomerID` = c.`ID`
                                LEFT JOIN (SELECT `CustomerID`,SUM(`OutstandingAmount`) AS `TotalOverDueBalance`
                                        FROM `customeraccountsreceivable` 
                                        WHERE `OutstandingAmount` > 0 AND `DaysDue` > 0
                                        AND LOCATE(BINARY '-$BranchID',CONCAT(' ',`HOGeneralID`,' '))
                                        GROUP BY `CustomerID`) crOverB ON crOverB.`CustomerID` = c.`ID`
                                LEFT JOIN (SELECT `CustomerID`,SUM(`Amount`) AS `Penalty` 
                                        FROM `customerpenalty` 
                                        WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',`HOGeneralID`,' '))
                                        GROUP BY `CustomerID`) cp ON cp.`CustomerID` = c.`ID`
                                WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',c.`HOGeneralID`,' '))
                                AND `TotalOutstandingBalance` > 0
                                AND ibm.`IBMID` = $IBMID
                                ORDER BY c.`Code` ASC");
       return $q;
    }
    
    /*
     * @descriptio: Function used in getting accounts for Collection Due Report...
     */
    function HOReportCollectionDueReportQuery($BranchID,$SFL,$dueFrom,$dueTo,$accountFrom,$accountTo,$CAT){
        global $mysqli;
        
        $SFLId = $SFL[0];
        $CanPurchase = $SFL[1];
        
        if($CAT == 'ALL') $AND_CAT = "";
        else if($CAT == '1') $AND_CAT = "AND cd.`tpi_GSUTypeID` = $CAT";
        else $AND_CAT = "AND cd.`tpi_GSUTypeID` IN (".str_replace('_',',',$CAT).")";

        if($CanPurchase > 0){
            $SELECT_FIELDS = "";
            $AND_SFL = "AND c.`CustomerTypeID` = $SFLId";
            $AND_DueDate = "AND c.`Code` BETWEEN '$accountFrom' AND '$accountTo'";
            $JOIN_IBM = "";
        }else{
            $SELECT_FIELDS = "cibm.`Code` AS `IBMCode`,cibm.`Name` AS `IBMName`,";
            $AND_SFL = "AND cibm.`CustomerTypeID` = $SFLId";
            $AND_DueDate = "AND cibm.`Code` BETWEEN '$accountFrom' AND '$accountTo'";
            $JOIN_IBM = "INNER JOIN (SELECT `CustomerID`,`IBMID`,MAX(`EnrollmentDate`) 
                                    FROM `tpi_rcustomeribm`
                                    WHERE LOCATE(BINARY '-$BranchID',CONCAT(' ',`HOGeneralID`,' ')) 
                                    GROUP BY `CustomerID`) ibm ON ibm.`CustomerID` = si.`CustomerID`
                            INNER JOIN `customer` cibm ON cibm.`ID` = ibm.`IBMID`";
        }

        $q = $mysqli->query("SELECT $SELECT_FIELDS si.`CustomerID`,c.`Code`,c.`Name`,
                                    gsu.`Name` AS `CAT`,ct.`Name` AS `CreditTerm`,
                                    si.`DocumentNo` AS `InvoiceNo`,DATE_FORMAT(si.`EffectivityDate`,'%b %d, %Y') AS `DueDate`,
                                    FORMAT(car.`OutstandingAmount`,2) AS `TotalAmountDue`,IFNULL(cd.`MobileNo`,cd.`TelNo`) AS `ContactNo`
                                FROM `salesinvoice` si
                                INNER JOIN `customeraccountsreceivable` car ON car.`CustomerID` = si.`CustomerID` AND car.`SalesInvoiceID` = si.`ID`
                                INNER JOIN `customer` c ON c.`ID` = si.`CustomerID`
                                INNER JOIN `tpi_customerdetails` cd ON cd.`CustomerID` = si.`CustomerID`
                                INNER JOIN `tpi_credit` cr ON cr.`CustomerID` = si.`CustomerID`
                                INNER JOIN `creditterm` ct ON ct.`ID` = cr.`CreditTermID`
                                INNER JOIN `tpi_gsutype` gsu ON gsu.`ID` = cd.`tpi_GSUTypeID`
                                $JOIN_IBM
                                WHERE
                                LOCATE(BINARY '-$BranchID',CONCAT(' ',c.`HOGeneralID`,' '))
                                AND LOCATE(BINARY '-$BranchID',CONCAT(' ',cd.`HOGeneralID`,' '))
                                AND LOCATE(BINARY '-$BranchID',CONCAT(' ',si.`HOGeneralID`,' '))
                                AND LOCATE(BINARY '-$BranchID',CONCAT(' ',car.`HOGeneralID`,' '))
                                AND DATE(si.`EffectivityDate`) BETWEEN '$dueFrom' AND '$dueTo' 
                                $AND_SFL
                                $AND_CAT
                                $AND_DueDate");
        
        return $q;
    }
	
	
	function ibm_consolidated_sales_earnings_summary_report($IBMFROM,$IBMTO,$BRanchTo,$BranchFrom,$CAMPAIGNFROM,$CAMPAIGNTO)
	{				
		global $mysqli;
		
	
		$query = "SELECT cm.ID CustomerCommission, a.CampaignCode , a.IBMID, ibm.Name,ibm.Code IBMCODE, a.CampaignCode, manager.PayoutOrOffset, 
							(SELECT TotalDGSAmount FROM tpi_sfasummary_pmg WHERE CustomerID = a.IBMID AND CampaignCode = a.CampaignCode AND PMGType='CFT') DGSAmountCFT, 
							(SELECT TotalDGSAmount FROM tpi_sfasummary_pmg WHERE CustomerID = a.IBMID AND CampaignCode = a.CampaignCode AND PMGType='NCFT') DGSAmountNCFT, 
							(SELECT TotalDGSAmount FROM tpi_sfasummary_pmg WHERE CustomerID = a.IBMID AND CampaignCode = a.CampaignCode AND PMGType='CPI') DGSAmountCPI,
							(SELECT SUM(TotalDGSAmount) FROM tpi_sfasummary_pmg WHERE CustomerID = a.IBMID AND CampaignCode = a.CampaignCode AND PMGType IN ('CPI','NCFT','CFT')) PMGTotalDGSAmount , 
							(SELECT TotalDGSPayment FROM tpi_sfasummary_pmg WHERE CustomerID = a.IBMID AND CampaignCode = a.CampaignCode AND PMGType='CFT') PaidUpDGSCFT, 
							(SELECT TotalDGSPayment FROM tpi_sfasummary_pmg WHERE CustomerID = a.IBMID AND CampaignCode = a.CampaignCode AND PMGType='NCFT') PaidUpDGSNCFT, 
							(SELECT TotalDGSPayment FROM tpi_sfasummary_pmg WHERE CustomerID = a.IBMID AND CampaignCode = a.CampaignCode AND PMGType='CPI') PaidUpDGCPI, 
							(SELECT SUM(TotalDGSPayment) FROM tpi_sfasummary_pmg WHERE CustomerID = a.IBMID AND CampaignCode = a.CampaignCode AND PMGType IN ('CPI','NCFT','CFT')) PMGTotalPaidUpDGS, 
							cm.OutstandingBalance BalanceServiceFee, cm.EarnedBonusAmount EarnedServiceFee, cm.VAT,cm.TotalNetOfTax, cm.WithHoldingTax, 
							IFNULL((SELECT SUM(OffsetAmount) FROM customercommission_summary WHERE CustomerCommissionID = cm.ID),0) `TotalOffsetAmount`, 
							cm.BranchID ,br.Name BranchName,br.Code BranchCode
							FROM tpi_sfasummary_pmg a
							INNER JOIN customer ibm ON a.CustomerID = SPLIT_STR(ibm.HOGeneralID,'-',1) 
							INNER JOIN sfm_manager manager ON SPLIT_STR(manager.HOGeneralID,'-',2) = SPLIT_STR(ibm.HOGeneralID,'-',2)
							AND ibm.Code = manager.mCode
							and SPLIT_STR(ibm.HOGeneralID,'-',2) = SPLIT_STR(a.HOGeneralID,'-',2)
							INNER JOIN customercommission cm ON a.CustomerID=cm.CustomerID AND cm.CampaignMonth = a.CampaignMonth AND cm.CampaignYear=cm.CampaignYear
							INNER JOIN branch br ON cm.BranchID = br.ID
							WHERE a.LevelID = 4 
							and (a.CampaignCode in ('".$CAMPAIGNFROM."'))
							and (a.CustomerID between ".$IBMFROM." and ".$IBMTO.") 
							and (br.Code between '".$BranchFrom."' and '".$BRanchTo."')
							GROUP BY cm.ID
							ORDER BY a.CampaignCode and br.Code asc ";
		$q = $mysqli->query($query);
	
		return $q;
	}
	
	function dccr_query($datefrom,$dateto,$branchfrom,$branchto,$issummary,$offset,$RPP){
		global $mysqli;
		if($offset==0 &&  $RPP==0){
			$xlimit = "";
		}else{
			$xlimit = "limit ".$offset.", ".$RPP;
		}
		//if summary is yes..
		if($issummary == 1){
			//summary..
			$q="SELECT `Date` xDate,BranchName,BranchCode ,BankName,'' Reference,ReasonID,xCash,xCheck,xOffsite,Canceled,ReasonName,StatusID,xoffseting, 
						xOffsite+xCash+xCheck `PaymentThruOffseting`,xOffsite+xCash+xCheck+xoffseting `TotalCollection`
 
				FROM (
					SELECT DATE_FORMAT(a.TxnDate,'%m/%d/%Y') `Date`,  branch.Name BranchName, branch.Code BranchCode,bank.Name BankName,
					CONCAT('OR','',LPAD(SPLIT_STR(a.HOGeneralID,'-',1),8,0)) Reference, IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID) ReasonID,
					SUM(cash.TotalAmount) `xCash`, 0 `xCheck`, 0 `xOffsite`, IF(a.StatusID=8,cash.TotalAmount,0) `Canceled`,
					IFNULL(reason.Name,'No Reason') ReasonName,a.StatusID,0 `xoffseting`
					FROM officialreceipt a
					INNER JOIN officialreceiptcash cash 
					ON SPLIT_STR(a.HOGeneralID,'-',2)  = SPLIT_STR(cash.HOGeneralID,'-',2) 
					AND SPLIT_STR(a.HOGeneralID,'-',1) = cash.OfficialReceiptID
					INNER JOIN branch branch ON branch.ID=SPLIT_STR(a.HOGeneralID,'-',2)
					INNER JOIN bank bank ON bank.ID=a.BankID
					LEFT JOIN reason reason ON reason.ID=IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID)
					WHERE (branch.ID BETWEEN   '".$branchfrom."' AND '".$branchto."' ) AND (DATE(a.TxnDate) BETWEEN '".$datefrom."' AND '".$dateto."')
					GROUP BY bank.ID , DATE_FORMAT(a.TxnDate,'%m/%d/%Y'), branch.ID
					
					UNION ALL
					
					SELECT  DATE_FORMAT(a.TxnDate,'%m/%d/%Y') `Date`,  branch.Name BranchName, branch.Code BranchCode,bank.Name BankName,
					CONCAT('OR','',LPAD(SPLIT_STR(a.HOGeneralID,'-',1),8,0)) Reference,IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID) ReasonID,
					0 `xCash`, SUM(`check`.TotalAmount) `xCheck`, 0 `xOffsite`, IF(a.StatusID=8,`check`.TotalAmount,0) `Canceled`,
					IFNULL(reason.Name,'No Reason') ReasonName,a.StatusID,0 `xoffseting`
					FROM officialreceipt a
					INNER JOIN officialreceiptcheck `check`
					ON SPLIT_STR(a.HOGeneralID,'-',2)  = SPLIT_STR(`check`.HOGeneralID,'-',2) 
					AND SPLIT_STR(a.HOGeneralID,'-',1) = `check`.OfficialReceiptID
					INNER JOIN branch branch ON branch.ID=SPLIT_STR(a.HOGeneralID,'-',2)
					INNER JOIN bank bank ON bank.ID=a.BankID
					LEFT JOIN reason reason ON reason.ID=IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID)
					WHERE (branch.ID BETWEEN   '".$branchfrom."' AND '".$branchto."' ) AND (DATE(a.TxnDate) BETWEEN '".$datefrom."' AND '".$dateto."')
					GROUP BY bank.ID , DATE_FORMAT(a.TxnDate,'%m/%d/%Y'), branch.ID
					
					UNION ALL
					
					SELECT  DATE_FORMAT(a.TxnDate,'%m/%d/%Y') `Date`,  branch.Name BranchName, branch.Code BranchCode,bank.Name BankName,
					CONCAT('OR','',LPAD(SPLIT_STR(a.HOGeneralID,'-',1),8,0)) Reference,IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID) ReasonID,
					0 `xCash`, 0 `xCheck`, SUM(`offsite`.TotalAmount)  `xOffsite`, IF(a.StatusID=8,`offsite`.TotalAmount,0) `Canceled`,
					IFNULL(reason.Name,'No Reason') ReasonName,a.StatusID,0 `xoffseting`
					FROM officialreceipt a
					INNER JOIN officialreceiptdeposit `offsite`
					ON SPLIT_STR(a.HOGeneralID,'-',2)  = SPLIT_STR(`offsite`.HOGeneralID,'-',2) 
					AND SPLIT_STR(a.HOGeneralID,'-',1) = `offsite`.OfficialReceiptID
					INNER JOIN branch branch ON branch.ID=SPLIT_STR(a.HOGeneralID,'-',2)
					INNER JOIN bank bank ON bank.ID=a.BankID
					LEFT JOIN reason reason ON reason.ID=IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID)
					WHERE (branch.ID BETWEEN   '".$branchfrom."' AND '".$branchto."' ) AND (DATE(a.TxnDate) BETWEEN '".$datefrom."' AND '".$dateto."')
					GROUP BY bank.ID , DATE_FORMAT(a.TxnDate,'%m/%d/%Y'), branch.ID
					
					UNION ALL
					
					SELECT  DATE_FORMAT(a.TxnDate,'%m/%d/%Y') `Date`,  branch.Name BranchName, branch.Code BranchCode,bank.Name BankName,
					CONCAT('OR','',LPAD(SPLIT_STR(a.HOGeneralID,'-',1),8,0)) Reference,IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID) ReasonID,
					0 `xCash`, 0 `xCheck`, 0 `xOffsite`, IF(a.StatusID=8,`commission`.TotalAmount,0) `Canceled`,
					IFNULL(reason.Name,'No Reason') ReasonName,a.StatusID, SUM(`commission`.TotalAmount) `xoffseting`
					FROM officialreceipt a
					INNER JOIN officialreceiptcommission `commission`
					ON SPLIT_STR(a.HOGeneralID,'-',2)  = SPLIT_STR(`commission`.HOGeneralID,'-',2) 
					AND SPLIT_STR(a.HOGeneralID,'-',1) = `commission`.OfficialReceiptID
					INNER JOIN branch branch ON branch.ID=SPLIT_STR(a.HOGeneralID,'-',2)
					INNER JOIN bank bank ON bank.ID=a.BankID
					LEFT JOIN reason reason ON reason.ID=IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID)
					WHERE (branch.ID BETWEEN   '".$branchfrom."' AND '".$branchto."' ) AND (DATE(a.TxnDate) BETWEEN '".$datefrom."' AND '".$dateto."') 
					GROUP BY bank.ID , DATE_FORMAT(a.TxnDate,'%m/%d/%Y'), branch.ID
				) tble 
				
				ORDER BY `Date` ASC".$xlimit;
		}else{
			//detailed..
			$q="SELECT `Date` xDate,BranchName, BranchCode, BankName, Reference,ReasonID,xCash,xCheck,xOffsite,Canceled,ReasonName,StatusID,xoffseting, 
					xOffsite+xCash+xCheck `PaymentThruOffseting`,xOffsite+xCash+xCheck+xoffseting `TotalCollection`
				FROM (
				SELECT  DATE_FORMAT(a.TxnDate,'%m/%d/%Y') `Date`,  branch.Name BranchName, branch.Code BranchCode,bank.Name BankName,
				CONCAT('OR','',LPAD(SPLIT_STR(a.HOGeneralID,'-',1),8,0)) Reference,IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID) ReasonID,
				cash.TotalAmount `xCash`, 0 `xCheck`, 0 `xOffsite`, IF(a.StatusID=8,cash.TotalAmount,0) `Canceled`,
				IFNULL(reason.Name,'') ReasonName,a.StatusID,0 `xoffseting`
				FROM officialreceipt a
				INNER JOIN officialreceiptcash cash 
				ON SPLIT_STR(a.HOGeneralID,'-',2)  = SPLIT_STR(cash.HOGeneralID,'-',2) 
				AND SPLIT_STR(a.HOGeneralID,'-',1) = cash.OfficialReceiptID
				INNER JOIN branch branch ON branch.ID=SPLIT_STR(a.HOGeneralID,'-',2)
				INNER JOIN bank bank ON bank.ID=a.BankID
				LEFT JOIN reason reason ON reason.ID=IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID)
				WHERE (branch.ID BETWEEN ".$branchfrom." AND ".$branchto.") AND 
				(date(a.TxnDate) BETWEEN '".$datefrom."' AND '".$dateto."')
				
				UNION ALL
				
				SELECT  DATE_FORMAT(a.TxnDate,'%m/%d/%Y') `Date`,  branch.Name BranchName, branch.Code BranchCode,bank.Name BankName,
				CONCAT('OR','',LPAD(SPLIT_STR(a.HOGeneralID,'-',1),8,0)) Reference,IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID) ReasonID,
				0 `xCash`, `check`.TotalAmount `xCheck`, 0 `xOffsite`, IF(a.StatusID=8,`check`.TotalAmount,0) `Canceled`,
				IFNULL(reason.Name,'') ReasonName,a.StatusID,0 `xoffseting`
				FROM officialreceipt a
				INNER JOIN officialreceiptcheck `check`
				ON SPLIT_STR(a.HOGeneralID,'-',2)  = SPLIT_STR(`check`.HOGeneralID,'-',2) 
				AND SPLIT_STR(a.HOGeneralID,'-',1) = `check`.OfficialReceiptID
				INNER JOIN branch branch ON branch.ID=SPLIT_STR(a.HOGeneralID,'-',2)
				INNER JOIN bank bank ON bank.ID=a.BankID
				LEFT JOIN reason reason ON reason.ID=IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID)
				WHERE (branch.ID BETWEEN ".$branchfrom." AND ".$branchto.") AND 
				(date(a.TxnDate) BETWEEN '".$datefrom."' AND '".$dateto."')
				
				UNION ALL
				
				SELECT  DATE_FORMAT(a.TxnDate,'%m/%d/%Y') `Date`,  branch.Name BranchName, branch.Code BranchCode,bank.Name BankName,
				CONCAT('OR','',LPAD(SPLIT_STR(a.HOGeneralID,'-',1),8,0)) Reference,IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID) ReasonID,
				0 `xCash`, 0 `xCheck`, `offsite`.TotalAmount  `xOffsite`, IF(a.StatusID=8,`offsite`.TotalAmount,0) `Canceled`,
				IFNULL(reason.Name,'') ReasonName,a.StatusID,0 `xoffseting`
				FROM officialreceipt a
				INNER JOIN officialreceiptdeposit `offsite`
				ON SPLIT_STR(a.HOGeneralID,'-',2)  = SPLIT_STR(`offsite`.HOGeneralID,'-',2) 
				AND SPLIT_STR(a.HOGeneralID,'-',1) = `offsite`.OfficialReceiptID
				INNER JOIN branch branch ON branch.ID=SPLIT_STR(a.HOGeneralID,'-',2)
				INNER JOIN bank bank ON bank.ID=a.BankID
				LEFT JOIN reason reason ON reason.ID=IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID)
				WHERE (branch.ID BETWEEN ".$branchfrom." AND ".$branchto.") AND 
				(date(a.TxnDate) BETWEEN '".$datefrom."' AND '".$dateto."')
				
				UNION ALL
				
				SELECT  DATE_FORMAT(a.TxnDate,'%m/%d/%Y') `Date`,  branch.Name BranchName, branch.Code BranchCode,bank.Name BankName,
				CONCAT('OR','',LPAD(SPLIT_STR(a.HOGeneralID,'-',1),8,0)) Reference,IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID) ReasonID,
				0 `xCash`, 0 `xCheck`, 0 `xOffsite`, IF(a.StatusID=8,`commission`.TotalAmount,0) `Canceled`,
				IFNULL(reason.Name,'') ReasonName,a.StatusID, `commission`.TotalAmount `xoffseting`
				FROM officialreceipt a
				INNER JOIN officialreceiptcommission `commission`
				ON SPLIT_STR(a.HOGeneralID,'-',2)  = SPLIT_STR(`commission`.HOGeneralID,'-',2) 
				AND SPLIT_STR(a.HOGeneralID,'-',1) = `commission`.OfficialReceiptID
				INNER JOIN branch branch ON branch.ID=SPLIT_STR(a.HOGeneralID,'-',2)
				INNER JOIN bank bank ON bank.ID=a.BankID
				LEFT JOIN reason reason ON reason.ID=IF(a.NonTradeReasonID=0,a.ReasonID,a.NonTradeReasonID)
				WHERE (branch.ID BETWEEN ".$branchfrom." AND ".$branchto.") AND 
				(date(a.TxnDate) BETWEEN '".$datefrom."' AND '".$dateto."') 
				) tble
				ORDER BY `Date` asc ".$xlimit;
		}
		
		 $result = $mysqli->query($q);
		//$result = $q;
		return $result;
	}
	
	
	function PaymentClassification_query($datefrom,$dateto,$branchfrom,$branchto,$offset,$RPP)
	{
		global $mysqli;
		if($offset==0 &&  $RPP==0){
			$xlimit = "";
		}else{
			$xlimit = "limit ".$offset.", ".$RPP;
		}
		
		$q = "SELECT `BrancCode`, ModeOfPayment, OnTime38Ddays a1, Beyond38DdaysButWithin52 a2, Beyond38days a3, 
			OnTime52Ddays a4, `52daysOnwards` a5 FROM (
				SELECT `Code` BrancCode, 'CASH' ModeOfPayment,(
				SELECT  IFNULL(SUM(TotalAmount),0) 
				FROM (
						SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID, cash.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
						IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
						AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
						FROM salesinvoice sv
						INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
						INNER JOIN officialreceiptcash cash ON SPLIT_STR(cash.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
						AND offd.OfficialReceiptID = cash.OfficialReceiptID
						INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
						INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
						AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
						WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
				) tble  WHERE TxnDate <> '' AND DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(EffectivityDate,'%m/%d/%Y')
				AND CreditTermID = 2  AND BranchID = b.ID ) OnTime38Ddays, 
				(SELECT IFNULL(SUM(TotalAmount),0) Beyond38DdaysButWithin52
				 FROM (
					SELECT BranchID, tpi_GSUTypeID, TotalAmount,TxnDate, EffectivityDate
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , cash.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcash cash ON SPLIT_STR(cash.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = cash.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 2
					) xtable WHERE DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(DATE_ADD(EffectivityDate, INTERVAL +58 DAY) ,'%m/%d/%Y')
					AND tpi_GSUTypeID = 2 AND
					BranchID = b.ID
				) Beyond38DdaysButWithin52,
				(SELECT IFNULL(SUM(TotalAmount),0)
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , cash.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcash cash ON SPLIT_STR(cash.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = cash.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 2 AND BranchID = b.ID) Beyond38days,
				(SELECT IFNULL(SUM(TotalAmount),0) 
				FROM (
					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID, cash.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcash cash ON SPLIT_STR(cash.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = cash.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
				) tble  WHERE TxnDate <> '' AND DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(EffectivityDate,'%m/%d/%Y')
				AND CreditTermID = 3 AND BranchID = b.ID) OnTime52Ddays,
				(SELECT IFNULL(SUM(TotalAmount),0)
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , cash.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcash cash ON SPLIT_STR(cash.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = cash.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 3 AND BranchID = b.ID) `52daysOnwards`
				FROM branch b


				UNION ALL
				SELECT `Code` BrancCode, 'CHECK' ModeOfPayment,(
				SELECT  IFNULL(SUM(TotalAmount),0) 
				FROM (
						SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID, `check`.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
						IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
						AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
						FROM salesinvoice sv
						INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
						INNER JOIN officialreceiptcheck `check` ON SPLIT_STR(`check`.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
						AND offd.OfficialReceiptID = `check`.OfficialReceiptID
						INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
						INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
						AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
						WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
				) tble  WHERE TxnDate <> '' AND DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(EffectivityDate,'%m/%d/%Y')
				AND CreditTermID = 2  AND BranchID = b.ID ) OnTime38Ddays, 
				(SELECT IFNULL(SUM(TotalAmount),0) Beyond38DdaysButWithin52
				 FROM (
					SELECT BranchID, tpi_GSUTypeID, TotalAmount,TxnDate, EffectivityDate
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , `check`.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcheck `check` ON SPLIT_STR(`check`.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = `check`.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 2
					) xtable WHERE DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(DATE_ADD(EffectivityDate, INTERVAL +58 DAY) ,'%m/%d/%Y')
					AND tpi_GSUTypeID = 2 AND
					BranchID = b.ID
				) Beyond38DdaysButWithin52,
				(SELECT IFNULL(SUM(TotalAmount),0)
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , `check`.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcheck `check` ON SPLIT_STR(`check`.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = `check`.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 2 AND BranchID = b.ID) Beyond38days,
				(SELECT IFNULL(SUM(TotalAmount),0) 
				FROM (
					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID, `check`.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcheck `check` ON SPLIT_STR(`check`.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = `check`.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
				) tble  WHERE TxnDate <> '' AND DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(EffectivityDate,'%m/%d/%Y')
				AND CreditTermID = 3 AND BranchID = b.ID) OnTime52Ddays,
				(SELECT IFNULL(SUM(TotalAmount),0)
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , `check`.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcheck `check` ON SPLIT_STR(`check`.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = `check`.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 3 AND BranchID = b.ID) `52daysOnwards`
				FROM branch b

				UNION ALL 
				SELECT `Code` BrancCode, 'DEPOSIT' ModeOfPayment,(
				SELECT  IFNULL(SUM(TotalAmount),0) 
				FROM (
						SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID, deposit.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
						IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
						AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
						FROM salesinvoice sv
						INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
						INNER JOIN officialreceiptdeposit deposit ON SPLIT_STR(deposit.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
						AND offd.OfficialReceiptID = deposit.OfficialReceiptID
						INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
						INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
						AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
						WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
				) tble  WHERE TxnDate <> '' AND DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(EffectivityDate,'%m/%d/%Y')
				AND CreditTermID = 2  AND BranchID = b.ID ) OnTime38Ddays, 
				(SELECT IFNULL(SUM(TotalAmount),0) Beyond38DdaysButWithin52
				 FROM (
					SELECT BranchID, tpi_GSUTypeID, TotalAmount,TxnDate, EffectivityDate
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , deposit.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptdeposit deposit ON SPLIT_STR(deposit.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = deposit.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 2
					) xtable WHERE DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(DATE_ADD(EffectivityDate, INTERVAL +58 DAY) ,'%m/%d/%Y')
					AND tpi_GSUTypeID = 2 AND
					BranchID = b.ID
				) Beyond38DdaysButWithin52,
				(SELECT IFNULL(SUM(TotalAmount),0)
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , deposit.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptdeposit deposit ON SPLIT_STR(deposit.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = deposit.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 2 AND BranchID = b.ID) Beyond38days,
				(SELECT IFNULL(SUM(TotalAmount),0) 
				FROM (
					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID, deposit.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptdeposit deposit ON SPLIT_STR(deposit.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = deposit.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
				) tble  WHERE TxnDate <> '' AND DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(EffectivityDate,'%m/%d/%Y')
				AND CreditTermID = 3 AND BranchID = b.ID) OnTime52Ddays,
				(SELECT IFNULL(SUM(TotalAmount),0)
					FROM (
					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , deposit.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptdeposit deposit ON SPLIT_STR(deposit.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = deposit.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 3 AND BranchID = b.ID) `52daysOnwards`
				FROM branch b

				UNION ALL

				SELECT `Code` BrancCode, 'OFFSET' ModeOfPayment,(
				SELECT  IFNULL(SUM(TotalAmount),0) 
				FROM (
						SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID, `offset`.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
						IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
						AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
						FROM salesinvoice sv
						INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
						INNER JOIN officialreceiptcommission `offset` ON SPLIT_STR(`offset`.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
						AND offd.OfficialReceiptID = `offset`.OfficialReceiptID
						INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
						INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
						AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
						WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
				) tble  WHERE TxnDate <> '' AND DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(EffectivityDate,'%m/%d/%Y')
				AND CreditTermID = 2  AND BranchID = b.ID ) OnTime38Ddays, 
				(SELECT IFNULL(SUM(TotalAmount),0) Beyond38DdaysButWithin52
				 FROM (
					SELECT BranchID, tpi_GSUTypeID, TotalAmount,TxnDate, EffectivityDate
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , `offset`.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcommission `offset` ON SPLIT_STR(`offset`.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = `offset`.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 2
					) xtable WHERE DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(DATE_ADD(EffectivityDate, INTERVAL +58 DAY) ,'%m/%d/%Y')
					AND tpi_GSUTypeID = 2 AND
					BranchID = b.ID
				) Beyond38DdaysButWithin52,
				(SELECT IFNULL(SUM(TotalAmount),0)
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , `offset`.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcommission `offset` ON SPLIT_STR(`offset`.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = `offset`.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 2 AND BranchID = b.ID) Beyond38days,
				(SELECT IFNULL(SUM(TotalAmount),0) 
				FROM (
					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID, `offset`.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcommission `offset` ON SPLIT_STR(`offset`.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = `offset`.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
				) tble  WHERE TxnDate <> '' AND DATE_FORMAT(TxnDate,'%m/%d/%Y') <= DATE_FORMAT(EffectivityDate,'%m/%d/%Y')
				AND CreditTermID = 3 AND BranchID = b.ID) OnTime52Ddays,
				(SELECT IFNULL(SUM(TotalAmount),0)
					FROM (

					SELECT SPLIT_STR(sv.HOGeneralID,'-',2) BranchID , `offset`.TotalAmount, sv.CustomerID, custd.tpi_GSUTypeID,cd.ID CreditTermID, cd.Duration ,offd.OfficialReceiptID ,SPLIT_STR(sv.HOGeneralID,'-',1) salesinvoiceID, sv.EffectivityDate,
					IFNULL((SELECT TxnDate FROM officialreceipt WHERE SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(sv.HOGeneralID,'-',2)
					AND SPLIT_STR(HOGeneralID,'-',1) = SPLIT_STR(offd.OfficialReceiptID,'-',1)),'')`TxnDate`
					FROM salesinvoice sv
					INNER JOIN officialreceiptdetails offd ON SPLIT_STR(sv.HOGeneralID,'-',2)  = SPLIT_STR(offd.HOGeneralID,'-',2)
					INNER JOIN officialreceiptcommission `offset` ON SPLIT_STR(`offset`.HOGeneralID,'-',2) = SPLIT_STR(offd.HOGeneralID,'-',2)
					AND offd.OfficialReceiptID = `offset`.OfficialReceiptID
					INNER JOIN creditterm cd ON cd.ID = sv.CreditTermID
					INNER JOIN tpi_customerdetails custd ON SPLIT_STR(custd.HOGeneralID,'-',1)=sv.CustomerID
					AND SPLIT_STR(sv.HOGeneralID,'-',1) = offd.RefTxnID
					WHERE sv.OutstandingBalance = 0 AND DATE_FORMAT(TxnDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."'
					) tble  
					WHERE TxnDate <> '' AND (DATE_FORMAT(TxnDate,'%m/%d/%Y') > DATE_FORMAT(EffectivityDate,'%m/%d/%Y'))
					AND CreditTermID = 3 AND BranchID = b.ID) `52daysOnwards`
				FROM branch b
			) xaaatble  WHERE BrancCode BETWEEN '".$branchfrom."' AND '".$branchto."'
			ORDER BY BrancCode , ModeOfPayment ".$xlimit;
		
		$result = $mysqli->query($q);
		return $result;
	}
	
	function ReturnedCheckSummary_Query($datefrom,$dateto,$branchfrom,$branchto,$sortby,$offset,$RPP)
	{
		global $mysqli;
		if($offset==0 &&  $RPP==0){
			$xlimit = "";
		}else{
			$xlimit = "limit ".$offset.", ".$RPP;
		}
		
		
		$q = "SELECT b.Code BranchCode,b.Name BranchName,dmcm.DocumentNo DMCMReferenceNo,dmcm.TxnDate DMDATE,
			  CONCAT('OR','',LPAD(dmcm.OfficialReceiptID,8,0)) ORNUMBER, DATE_FORMAT(offr.TxnDate,'%m/%d/%Y') ORDate,
			  ofc.CheckNumber CheckNo, 
			  igs.Code `IGSCODE`, igs.Name `IGSName`,ibm.Code IBMCODE, r.Code ReasonCode, r.Name ReasonName, ofc.TotalAmount `Amount`,offr.StatusID
			  FROM officialreceiptcheck ofc
			  INNER JOIN officialreceipt offr ON SPLIT_STR(offr.HOGeneralID,'-',2) = SPLIT_STR(ofc.HOGeneralID,'-',2)
			  AND SPLIT_STR(offr.HOGeneralID,'-',1) = ofc.OfficialReceiptID
			  INNER JOIN customer igs ON SPLIT_STR(igs.HOGeneralID,'-',2) = SPLIT_STR(offr.HOGeneralID,'-',2)
			  AND SPLIT_STR(igs.HOGeneralID,'-',1) = offr.CustomerID
			  INNER JOIN tpi_rcustomeribm ibm1 ON SPLIT_STR(ibm1.HOGeneralID,'-',2) = SPLIT_STR(igs.HOGeneralID,'-',2)
			  AND SPLIT_STR(igs.HOGeneralID,'-',1) = ibm1.CustomerID
			  INNER JOIN customer ibm ON SPLIT_STR(ibm.HOGeneralID,'-',2) = SPLIT_STR(ibm1.HOGeneralID,'-',2)
			  AND SPLIT_STR(ibm.HOGeneralID,'-',1) = ibm1.IBMID
			  INNER JOIN branch b ON b.ID = SPLIT_STR(offr.HOGeneralID,'-',2)
			  LEFT JOIN dmcm dmcm ON SPLIT_STR(offr.HOGeneralID,'-',2) = SPLIT_STR(offr.HOGeneralID,'-',2) AND
			  dmcm.OfficialReceiptID=SPLIT_STR(offr.HOGeneralID,'-',1)
			  LEFT JOIN reason r ON r.ID = IF(offr.NonTradeReasonID=0,offr.ReasonID,offr.NonTradeReasonID)
			  WHERE (b.Code BETWEEN '".$branchfrom."' AND '".$branchto."') 
			  AND (DATE_FORMAT(ofc.LastModifiedDate,'%m/%d/%Y') BETWEEN '".$datefrom."' AND '".$dateto."')
			  AND offr.StatusID = 8
			  ORDER BY ".$sortby." ASC ".$xlimit;
		
		$result = $mysqli->query($q);
		return $result;
	}
	
	function _getReason($reasonID)
	{	
		global $mysqli;
		$rq = $mysqli->query("select * from reason where ID = ".$reasonID);
		if($rq->num_rows){
			while($rr = $rq->fetch_object()){
				$result = $rr->Code;
			}
		}
		return $result;
	}
	
	function cfagbr_query($campaign_from,$branchfrom,$branchto,$offset,$RPP){
		
		global $mysqli;
		if($offset==0 &&  $RPP==0){
			$xlimit = "";
		}else{
			$xlimit = "limit ".$offset.", ".$RPP;
		}
		
		/*
			Author: Gino C. Leabres
			Date: 9/17/2013
			Explanation: Generate Contractors fee report in HO ...
			Enjoy my code ^^
		*/
		
		$q = "
				SELECT 
				-- BranchID, CampaignMonth, CampaignYear,
				IBDCode, IBDName,Campaign,DGS,CPI, xtotal,
				CfCreate, CFAmount, DGSLY, IncreasedDecreasedinDGS,  GBRate, GrowthBonus , (CFAmount + GrowthBonus)TotalEarnings, 0 VAT ,0 WithTax, 0 EarningsAfterTax
				FROM (
					SELECT BranchID, CampaignMonth, CampaignYear,IBDCode, IBDName,Campaign,DGS,CPI, xtotal,
					CfCreate, CFAmount, DGSLY, IncreasedDecreasedinDGS,  GBRate, (GBRate * xtotal) GrowthBonus
					 FROM (
						SELECT BranchID, CampaignMonth, CampaignYear,IBDCode, IBDName,Campaign,DGS,CPI, xtotal,
						CfCreate, CFAmount, DGSLY, IncreasedDecreasedinDGS, 
						IFNULL((SELECT Discount FROM gbrate WHERE Minimum <= IncreasedDecreasedinDGS AND Maximum >= IncreasedDecreasedinDGS),0) GBRate FROM (
							SELECT BranchID, CampaignMonth, CampaignYear,IBDCode, IBDName,Campaign,DGS,CPI, xtotal,
							CfCreate, CFAmount, DGSLY, IFNULL((DGS-DGSLY)/DGSLY,0) IncreasedDecreasedinDGS,LastYearDate FROM (
								SELECT BranchID,LastYearDate, CampaignMonth, CampaignYear,IBDCode, IBDName,Campaign,DGS,CPI, xtotal,
								CfCreate, CFAmount,
								IFNULL((SELECT SUM(TotalDGSAmount) FROM tpi_sfasummary_pmg WHERE PMGType IN ('CFT','NCFT') AND
								SPLIT_STR(HOGeneralID,'-',2) = BranchID AND CampaignCode=LastYearDate),0) DGSLY
								FROM (
									SELECT BranchID, CampaignMonth, CampaignYear,IBDCode, IBDName,Campaign,DGS,CPI, xtotal,
										   CONCAT(CfCreate,'%') CfCreate, (xtotal * (CfCreate/100)) CFAmount , 
										   UPPER(DATE_FORMAT(DATE_ADD(DATE_FORMAT(CONCAT(CampaignYear,'-',CampaignMonth,'-1'),'%Y-%m-%d'), INTERVAL -1 YEAR),'%b%y'))
										   LastYearDate
									FROM (
										 SELECT BranchID, CampaignMonth, CampaignYear, IBDCode, IBDName,Campaign,DGS,CPI, xtotal, 
										-- this part will change soon..
										(CASE 
											WHEN xtotal <= 999999.99 THEN 9
											WHEN  xtotal <= 2499999.99 THEN 10
											ELSE 11
										 END ) AS CfCreate FROM (
											SELECT BranchID, CampaignMonth, CampaignYear , IBDCode, IBDName,Campaign,DGS,CPI, (DGS+CPI) xtotal FROM (
												SELECT  ibd.ID BranchID, sfa.CampaignMonth, sfa.CampaignYear, ibd.Code IBDCode, ibd.Name IBDName,sfa.CampaignCode Campaign, SUM(sfa.TotalDGSAmount) DGS,
												(SELECT SUM(TotalDGSAmount) FROM tpi_sfasummary_pmg WHERE PMGType='CPI' AND SPLIT_STR(HOGeneralID,'-',2) = ibd.ID) `CPI`
												FROM branch ibd
												INNER JOIN tpi_sfasummary_pmg sfa ON SPLIT_STR(sfa.HOGeneralID,'-',2) = ibd.ID
												WHERE sfa.PMGType IN ('CFT','NCFT')
												GROUP BY ibd.ID, sfa.CampaignCode
											) a
										) b
									) c
								) d  
							) e
						) f
					) g
				) h
				WHERE IBDCode between '".$branchfrom."' and '".$branchto."' and Campaign = '".$campaign_from."'
				ORDER BY IBDCode ,CampaignMonth, CampaignYear";
	}
	
	
	function sfandrfreport($ibmid,$campaign,$branch){
		global $mysqli;
		$result = $mysqli->query("SELECT xName `Name`, AppointedDate, xTotalDGSAmount , CFT,NCFT,CPI, PaidUP, (PaidUP/xTotalDGSAmount)*100 BCR FROM (
							SELECT CONCAT(NAME,'-',`Code`) `xName`, AppointedDate, TotalDGSAmount + ifnull(CPI,0) xTotalDGSAmount, CFT, NCFT, CPI,
							TotalOnTimeDGSPayment PaidUP
							FROM (
								SELECT b.Code, a.IBMID, c.Name, a.CampaignCode, DATE(c.EnrollmentDate) AppointedDate, a.TotalDGSSales TotalDGSAmount,
								(SELECT SUM(TotalDGSPayment) FROM tpi_sfasummary_pmg WHERE CampaignCode = a.CampaignCode AND SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(c.HOGeneralID,'-',2) AND CustomerID =  a.CustomerID AND IBMID = a.CustomerID AND PMGType='CFT') CFT,
								(SELECT SUM(TotalDGSPayment) FROM tpi_sfasummary_pmg WHERE CampaignCode = a.CampaignCode AND SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(c.HOGeneralID,'-',2) AND CustomerID =  a.CustomerID AND IBMID = a.CustomerID AND PMGType='NCFT') NCFT,
								(SELECT SUM(TotalDGSPayment) FROM tpi_sfasummary_pmg WHERE CampaignCode = a.CampaignCode AND SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(c.HOGeneralID,'-',2) AND CustomerID = a.CustomerID AND IBMID = a.CustomerID AND PMGType='CPI') CPI,
								a.TotalOnTimeDGSPayment
								FROM tpi_sfasummary a 
								INNER JOIN customer c ON c.ID = a.CustomerID AND c.ID =  a.IBMID AND SPLIT_STR(a.HOGeneralID,'-',2)=SPLIT_STR(c.HOGeneralID,'-',2)
								INNER JOIN branch b ON b.ID = SPLIT_STR(a.HOGeneralID,'-',2)
								WHERE a.IBMID = $ibmid AND a.CustomerID = $ibmid
								AND a.CampaignCode = '$campaign' AND SPLIT_STR(a.HOGeneralID,'-',2) = $branch
							) a
						) b
						UNION ALL 
						SELECT xName `Name`, AppointedDate, xTotalDGSAmount, CFT,NCFT,CPI, PaidUP, CONCAT(FORMAT((PaidUP/xTotalDGSAmount)*100,2),'') BCR FROM (
							SELECT CONCAT(NAME,'-',`Code`) `xName`, AppointedDate, TotalDGSAmount + CPI xTotalDGSAmount, CFT, NCFT, CPI,
								TotalOnTimeDGSPayment PaidUP
								FROM (
								SELECT  b.Code, c.ID, c.Name, sfa.CampaignCode, DATE(c.EnrollmentDate) AppointedDate, sfa.TotalDGSSales TotalDGSAmount,
								(SELECT SUM(TotalDGSPayment) FROM tpi_sfasummary_pmg WHERE CampaignCode = sfa.CampaignCode AND SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(cl.HOGeneralID,'-',2) AND CustomerID =  sfa.CustomerID AND IBMID = sfa.CustomerID AND PMGType='CFT') CFT,
								(SELECT SUM(TotalDGSPayment) FROM tpi_sfasummary_pmg WHERE CampaignCode = sfa.CampaignCode AND SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(cl.HOGeneralID,'-',2) AND CustomerID =  sfa.CustomerID AND IBMID = sfa.CustomerID AND PMGType='NCFT') NCFT,
								(SELECT SUM(TotalDGSPayment) FROM tpi_sfasummary_pmg WHERE CampaignCode = sfa.CampaignCode AND SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(cl.HOGeneralID,'-',2) AND CustomerID =  sfa.CustomerID AND IBMID = sfa.CustomerID AND PMGType='CPI') CPI,
								sfa.TotalOnTimeDGSPayment
								FROM customerlinkedaccounts cl
								INNER JOIN customer c ON cl.LinkedAccountID = c.ID AND SPLIT_STR(cl.HOGeneralID,'-',2) = SPLIT_STR(c.HOGeneralID,'-',2)
								INNER JOIN branch b ON b.ID = SPLIT_STR(cl.HOGeneralID,'-',2)
								INNER JOIN tpi_sfasummary sfa ON c.ID = sfa.CustomerID 
								AND c.ID = sfa.IBMID AND SPLIT_STR(sfa.HOGeneralID,'-',2) = SPLIT_STR(cl.HOGeneralID,'-',2)
								WHERE cl.AccountOriginID = $ibmid
								AND sfa.CampaignCode = '$campaign' AND SPLIT_STR(cl.HOGeneralID,'-',2) = $branch
							) a
						) b
						UNION ALL 
						SELECT xName `Name`, AppointedDate, xTotalDGSAmount, CFT,NCFT,CPI, PaidUP, CONCAT(FORMAT((PaidUP/xTotalDGSAmount)*100,2),'') BCR FROM (
							SELECT CONCAT(NAME,'-',`Code`) `xName`, AppointedDate, TotalDGSAmount + CPI xTotalDGSAmount, CFT, NCFT, CPI,
								TotalOnTimeDGSPayment PaidUP
								FROM (
									SELECT  b.Code, c.ID, c.Name, sfa.CampaignCode, DATE(c.EnrollmentDate) AppointedDate, sfa.TotalDGSSales TotalDGSAmount,
											(SELECT SUM(TotalDGSPayment) FROM tpi_sfasummary_pmg WHERE CampaignCode = sfa.CampaignCode AND SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(c.HOGeneralID,'-',2) AND CustomerID =  sfa.CustomerID AND IBMID = sfa.CustomerID AND PMGType='CFT') CFT,
											(SELECT SUM(TotalDGSPayment) FROM tpi_sfasummary_pmg WHERE CampaignCode = sfa.CampaignCode AND SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(c.HOGeneralID,'-',2) AND CustomerID =  sfa.CustomerID AND IBMID = sfa.CustomerID AND PMGType='NCFT') NCFT,
											(SELECT SUM(TotalDGSPayment) FROM tpi_sfasummary_pmg WHERE CampaignCode = sfa.CampaignCode AND SPLIT_STR(HOGeneralID,'-',2) = SPLIT_STR(c.HOGeneralID,'-',2) AND CustomerID =  sfa.CustomerID AND IBMID = sfa.CustomerID AND PMGType='CPI') CPI,
											sfa.TotalOnTimeDGSPayment
									FROM affliatedibm af
									INNER JOIN customer c ON c.Code = af.MCustCode AND af.AFBranchID = SPLIT_STR(c.HOGeneralID,'-',2)
									INNER JOIN tpi_sfasummary sfa ON c.ID = sfa.CustomerID 
									INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID,'-',2)
									WHERE af.CustomerID = $ibmid AND af.MBranchID = $branch
							) a
						) b");
		return $result;
	}
	
?>