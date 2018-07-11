<?php

Class UIInterfaceFileGenerator{
    
    public function UIInterfaceFileGenerator(){
        global $database;
        $this->database = $database;
    }
    
    //function to fetch all D1
    function callD1($BranchID){
        $query = $this->database->execute("SELECT
										b.Code BranchCode,
										pmg.Code PMGCode,
										SUM(si.GrossAmount - si.BasicDiscount) TotalDGS,
										SUM(si.NetAmount) TotalInvfaceoice,
										SUM(sid.Qty) Quantity,
										CASE WHEN s.ID = 7 THEN COUNT(s.ID)
										ELSE 0
										END ProcessPO
									FROM salesinvoice si
									INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID
									INNER JOIN branch b ON b.ID = SPLIT_STR(sid.HOGeneralID, '-', 2)
									AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
									INNER JOIN tpi_pmg pmg ON pmg.ID = sid.PMGID
									INNER JOIN `status` s ON s.ID = si.StatusID
									WHERE DATE_FORMAT(si.TxnDate, '%Y-%m-%d') BETWEEN DATE_FORMAT(NOW() - INTERVAL 1 DAY, '%Y-%m-%d') AND DATE_FORMAT(NOW() - INTERVAL 1 DAY, '%Y-%m-%d')
									AND b.ID = $BranchID
									GROUP BY pmg.ID
									ORDER BY b.ID, pmg.Name");
        return $query;
    }
    
    //function to fetch all branches
    public function branch(){
        $query = $this->database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3)");
        return $query;
    }
    
    
    //function to fetch all PFL
    function callPFL($BranchID){
        $query = $this->database->execute("SELECT b.Code branchCode, c.Code DealerCode,
										c.LastName DealerLName, c.FirstName DealerFName, 
										brgy.Name Address1, city.Name Address2, state.Name Address3,
										region.Name Address4, DATE_FORMAT(c.BirthDate, '%m/%d/%Y') Birthdate,
										tcd.MobileNo, tcd.TelNo, c.Name DealerName, tcd.Gender, v.Name MaritalStatus,
										gsu.Name GSUType, ct.Code CustomerType, ibm.Code IBMCode, r.Code RepCode, tcd.ZipCode
									FROM customer c
									INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
									INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
										AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
									INNER JOIN tpi_customerdetails tcd ON tcd.HOGeneralID = c.HOGeneralID    
									INNER JOIN customer ibm ON ibm.ID = ribm.IBMID AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
									INNER JOIN tpi_gsutype gsu ON gsu.ID = tcd.tpi_GSUTypeID
									LEFT JOIN `area` brgy ON brgy.ID = tcd.AreaID
									LEFT JOIN `area` city ON city.ID = brgy.ParentID
									LEFT JOIN `area` state ON state.ID = city.ParentID
									LEFT JOIN `area` region ON region.ID = state.ParentID
									INNER JOIN customerdetails cds ON cds.CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), cds.HOGeneralID) > 0
									AND cds.FieldID = 2
									LEFT JOIN `value` v ON v.ID = cds.ValueID
									INNER JOIN customertype ct ON ct.ID = c.CustomerTypeID
									LEFT JOIN customer r ON r.ID = tcd.tpi_RecruiterID
									WHERE b.ID = $BranchID
									AND DATE_FORMAT(c.EnrollmentDate, '%Y-%m-%d') 
									BETWEEN DATE_FORMAT(NOW() - INTERVAL 1 DAY, '%Y-%m-%d')
									AND DATE_FORMAT(NOW() - INTERVAL 1 DAY, '%Y-%m-%d')
                                ");
                                
        return $query;
    }
	
	//DST
	function callDST($BranchID){
		$query = $this->database->execute("SELECT
										b.Code BranchCode,
										b.Code SystemBranchName,
										'' vmin,
										DATE_FORMAT(so.TxnDate, '%m/%d/%y') SalesOrderDate,
										DATE_FORMAT(so.TxnDate, '%b%y') Campaign,
										'' BankCC,
										'' CreditNote,
										IF(si.StatusID = 7, 70, IF(si.StatusID = 8, 90, '')) Stage,
										'REGULAR' SalesOrderType,
										TRIM(c.Code) CustomerCode,
										c.Name CustomerName,
										ct.Code CreditTermCode,
										ct.Name CreditTermName,
										si.GrossAmount TotalCSP,
										(si.GrossAmount - si.TotalCPI) TotalCSPLessCPI,
										(si.GrossAmount * 0.75) DiscountedGrossAmount,
										si.BasicDiscount,
										si.AddtlDiscount AdditionalDiscount,
										si.VatAmount SalesWithVat,
										(si.GrossAmount * 0.12) VatPercentage,
										(si.GrossAmount / 1.12) VatableSales,
										0 TotalAdjustment,
										0 TotalNSV,
										si.NetAmount TotalSalesInvoice,
										0 CreditForInvoice,
										DATE_FORMAT(so.TxnDate, '%m/%d/%y') SalesOrderDate2
									FROM customer c 
									INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
									INNER JOIN salesinvoice si ON si.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
									INNER JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID
										AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
									INNER JOIN salesorder so ON so.ID = si.RefTxnID
										AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
									INNER JOIN creditterm ct ON ct.ID = si.CreditTermID
									INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
										AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
									INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
										AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
									WHERE b.ID = $BranchID
									AND DATE(so.TxnDate) = DATE(NOW())");
		return $query;
	}
	
	//CST
	function callCST($BranchID){
		
		$query = $this->database->execute("SELECT
										b.Code BranchCode,
										b.Code SystemBranchCode,
										DATE_FORMAT(rcs.EnrollmentDate, '%m/%d/%y') StatusDate,
										c.Code CustomerCode,
										IF(c.CustomerTypeID = 1, 'DL', 'GM') CustomerType,
										'' CustomerTypeDescription,
										c.LastName CustomerLastName,
										c.FirstName CustomerFirstName,
										s.Code `Status`,
										s.Name StatusDescription,
										rec.Code RecruitedBy,
										rec.Name RecruiterName,
										ibm.Code SalesRep,
										ibm.Name IBMName,
										tcd.ZipCode,
										DATE_FORMAT(c.Birthdate, '%m/%d/%Y') Birthdate,
										ct.Code CreditTerms,
										ct.Code CreditTerms,
										ct.Name CreditTermsDesc,
										tc.ApprovedCL MaxCreditLimit,
										DATE_FORMAT(rcs.EnrollmentDate, '%m/%d/%y') StatusDate,
										0 TransactionNo,
										'' DescriptionOfPADRAR,
										IFNULL((SELECT SUM(OutstandingAmount) FROM customeraccountsreceivable 
											WHERE CustomerID = c.ID 
											AND OutstandingAmount > 0 
											AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0), 0) CustomerBalance,
										IFNULL((SELECT SUM(OutstandingAmount) FROM customerpenalty 
											WHERE CustomerID = c.ID 
											AND OutstandingAmount > 0 
											AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0), 0) CustomerOvedueBalance,
										IF(tcd.tpi_GSUTypeID = 1, 'No', 'Yes') GSU,
										'' dswkfl
									FROM customer c
									INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
									INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
										AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
									INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
									INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
										AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
									INNER JOIN tpi_credit tc ON tc.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), tc.HOGeneralID) > 0
									INNER JOIN creditterm ct ON ct.ID = tc.CreditTermID
									LEFT JOIN customer rec ON rec.ID = tcd.tpi_RecruiterID
										AND LOCATE(CONCAT('-', b.ID), rec.HOGeneralID) > 0
									INNER JOIN tpi_rcustomerstatus rcs ON rcs.CustomerID = c.ID
										AND rcs.ID = (SELECT MAX(ID) FROM tpi_rcustomerstatus WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', b.ID), rcs.HOGeneralID) > 0
									INNER JOIN `status` s ON s.ID = rcs.CustomerStatusID
									WHERE b.ID = $BranchID
									AND DATE(rcs.EnrollmentDate) = DATE(NOW())");
		return $query;
		
	}
	
	//IST
	function callIST($BranchID){
		$query = $this->database->execute("SELECT
											b.Code BranchCode,
											b.Code SystemBranchCode,
											'' UnderSystemMaintenance,
											'' BankCC,
											c.Code DealerCode,
											c.Name DealerName,
											ct.Name CreditTerm,
											ct.Name CreditTermDescription,
											ibm.Code SalesRepIBM,
											ibm.Name IBMName,
											rec.Code SalesRepREC,
											rec.Name RecruiterName,
											(atbl.UnitPrice - atbl.ProductPrice) OfferDiscount,
											atbl.*
										FROM
										(SELECT 
											b.ID BranchID,
											b.Code BranchCode,
											DATE_FORMAT(so.TxnDate, '%m/%d/%y') SalesOrderDate,
											DATE_FORMAT(si.TxnDate, '%b%y') Campaign,
											'' CreditNote,
											'REGULAR' SalesOrderType,
											si.CustomerID,
											p.Code ProductCode,
											p.Name ProductName,
											pl.Code ProductLine,
											pmg.Code PMGCode,
											sid.LineNo LineCode,
											sid.UnitPrice ProductPrice,
											sid.Qty Quantity,
											si.ID SalesInvoiceID,
											pp.UnitPrice,
											si.GrossAmount CSP,
											(si.GrossAmount - si.TotalCPI) CSPLessCPI,
											(si.GrossAmount * 0.75) DGSAmount,
											si.BasicDiscount,
											si.AddtlDiscount AdditionalDiscount,
											0 AdjustmentAmount
										FROM salesinvoice si 
										INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
										INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID
											AND LOCATE(CONCAT('-', b.ID), sid.HOGeneralID) > 0
										INNER JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID
											AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
										INNER JOIN salesorder so ON so.ID = dr.RefTxnID
											AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
										INNER JOIN product p ON p.ID = sid.ProductID
										INNER JOIN product pl ON pl.ID = p.ParentID
										INNER JOIN tpi_pmg pmg ON pmg.ID = sid.PMGID
										INNER JOIN productpricing pp ON pp.ProductID = p.ID
										WHERE b.ID = $BranchID
										AND DATE(si.TxnDate) = DATE(NOW())

										UNION ALL

										SELECT
											b.ID BranchID,
											b.Code BranchCode,
											DATE_FORMAT(so.TxnDate, '%m/%d/%y') SalesOrderDate,
											DATE_FORMAT(si.TxnDate, '%b%y') Campaign,
											'' CreditNote,
											'REGULAR' SalesOrderType,
											si.CustomerID,
											p.Code ProductCode,
											p.Name ProductName,
											pl.Code ProductLine,
											pmg.Code PMGCode,
											0 LineCode,
											aied.ProductEffectivePrice ProductPrice,
											aied.ProductQuantity Quantity,
											si.ID SalesInvoiceID,
											pp.UnitPrice,
											si.GrossAmount CSP,
											(si.GrossAmount - si.TotalCPI) CSPLessCPI,
											(si.GrossAmount * 0.75) DGSAmount,
											si.BasicDiscount,
											si.AddtlDiscount AdditionalDiscount,
											0 AdjustmentAmount
										FROM salesinvoice si
										INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
										INNER JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID
											AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
										INNER JOIN salesorder so ON so.ID = dr.RefTxnID
											AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
										INNER JOIN applied_incentive_entitlement_details aied ON aied.ReferenceSalesOrderID = so.ID
											AND LOCATE(CONCAT('-', b.ID), aied.HOGeneralID) > 0
										INNER JOIN product p ON p.ID = aied.ProductID
										INNER JOIN product pl ON pl.ID = p.ParentID
										INNER JOIN tpi_pmg pmg ON pmg.ID = aied.ProductPMGID
										INNER JOIN productpricing pp ON pp.ProductID = p.ID
										WHERE b.ID = $BranchID
										AND DATE(si.TxnDate) = DATE(NOW())

										UNION ALL

										SELECT
											b.ID BranchID,
											b.Code BranchCode,
											DATE_FORMAT(so.TxnDate, '%m/%d/%y') SalesOrderDate,
											DATE_FORMAT(si.TxnDate, '%b%y') Campaign,
											'' CreditNote,
											'REGULAR' SalesOrderType,
											si.CustomerID,
											p.Code ProductCode,
											p.Name ProductName,
											pl.Code ProductLine,
											pmg.Code PMGCode,
											0 LineCode,
											aope.ProductEffectivePrice ProductPrice,
											aope.ProductQuantity Quantity,
											si.ID SalesInvoiceID,
											pp.UnitPrice,
											si.GrossAmount CSP,
											(si.GrossAmount - si.TotalCPI) CSPLessCPI,
											(si.GrossAmount * 0.75) DGSAmount,
											si.BasicDiscount,
											si.AddtlDiscount AdditionalDiscount,
											0 AdjustmentAmount
										FROM salesinvoice si
										INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
										INNER JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID
											AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
										INNER JOIN salesorder so ON so.ID = dr.RefTxnID
											AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
										INNER JOIN applied_overlay_promo_entitlement_details aope ON aope.ReferenceSalesOrderID = so.ID
											AND LOCATE(CONCAT('-', b.ID), aope.HOGeneralID) > 0
										INNER JOIN product p ON p.ID = aope.ProductID
										INNER JOIN product pl ON pl.ID = p.ParentID
										INNER JOIN tpi_pmg pmg ON pmg.ID = aope.ProductPMGID
										INNER JOIN productpricing pp ON pp.ProductID = p.ID
										WHERE b.ID = $BranchID
										AND DATE(si.TxnDate) = DATE(NOW())

										UNION ALL

										SELECT
											b.ID BranchID,
											b.Code BranchCode,
											DATE_FORMAT(so.TxnDate, '%m/%d/%y') SalesOrderDate,
											DATE_FORMAT(si.TxnDate, '%b%y') Campaign,
											'' CreditNote,
											'REGULAR' SalesOrderType,
											si.CustomerID,
											p.Code ProductCode,
											p.Name ProductName,
											pl.Code ProductLine,
											pmg.Code PMGCode,
											0 LineCode,
											aspe.ProductEffectivePrice ProductPrice,
											aspe.ProductQuantity Quantity,
											si.ID SalesInvoiceID,
											pp.UnitPrice,
											si.GrossAmount CSP,
											(si.GrossAmount - si.TotalCPI) CSPLessCPI,
											(si.GrossAmount * 0.75) DGSAmount,
											si.BasicDiscount,
											si.AddtlDiscount AdditionalDiscount,
											0 AdjustmentAmount
											FROM salesinvoice si
										INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
										INNER JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID
											AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
										INNER JOIN salesorder so ON so.ID = dr.RefTxnID
											AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
										INNER JOIN applied_single_line_promo_entitlement_details aspe ON aspe.ReferenceSalesOrderID = so.ID
											AND LOCATE(CONCAT('-', b.ID), aspe.HOGeneralID) > 0
										INNER JOIN product p ON p.ID = aspe.ProductID
										INNER JOIN product pl ON pl.ID = p.ParentID
										INNER JOIN tpi_pmg pmg ON pmg.ID = aspe.ProductPMGID
										INNER JOIN productpricing pp ON pp.ProductID = p.ID
										WHERE b.ID = $BranchID
										AND DATE(si.TxnDate) = DATE(NOW())
										) atbl

									INNER JOIN customer c ON c.ID = atbl.CustomerID
										AND LOCATE(CONCAT('-', atbl.BranchID), c.HOGeneralID) > 0
									INNER JOIN branch b ON b.ID = atbl.BranchID
									INNER JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
										AND LOCATE(CONCAT('-', atbl.BranchID), tcd.HOGeneralID) > 0
									INNER JOIN tpi_credit tc ON tc.CustomerID = c.ID
										AND LOCATE(CONCAT('-', atbl.BranchID), tc.HOGeneralID) > 0
									INNER JOIN creditterm ct ON ct.ID = tc.CreditTermID
									INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
										AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm 
											WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', atbl.BranchID), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', atbl.BranchID), ribm.HOGeneralID) > 0
									INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
										AND LOCATE(CONCAT('-', atbl.BranchID), ibm.HOGeneralID) > 0
									INNER JOIN customer rec ON rec.ID = tcd.tpi_RecruiterID
										AND LOCATE(CONCAT('-', atbl.BranchID), rec.HOGeneralID) > 0
									ORDER BY SalesInvoiceID");
		return $query;
	}
    
    //m1
    function callM1($BranchID){
        $query = $this->database->execute("SELECT b.HoGeneralID, b.ID, b.PMGType, 'DL' DL, SUM(b.NoofdealerswithDGSSalesBelowandEqualto1000Pesos) Columna,
									SUM(b.NoofdealerswithDGSSalesFrom1001to2500Pesos)Columnb,
									SUM(b.NoofdealerswithDGSSalesFrom2501to5000Pesos)Columnc,
									SUM(b.NoofdealerswithDGSSalesFrom5001to10000Pesos)Columnd,
									SUM(b.NoofdealerswithDGSSalesFrom10001to20000Pesos)Columne,
									SUM(b.NoofdealerswithDGSSalesFrom20001to30000Pesos)Columnf,
									SUM(b.NoofdealerswithDGSSalesFrom30001to40000Pesos)Columng,
									SUM(b.NoofdealerswithDGSSalesFrom40001to50000Pesos) Columnh,
									DATE_FORMAT(b.TxnDate, '%m/%d/%Y') TransactionDate, DATE_FORMAT(b.TxnDate, '%b%y') Campaign, b.CustomerID
										FROM (
										SELECT PMGType, HoGeneralID, ID,IFNULL((IF(TotalPMG >= 1,IF(TotalPMG <= 1000,1,0),0)),0) `NoofdealerswithDGSSalesBelowandEqualto1000Pesos`, 
										IFNULL((IF(TotalPMG >= 1001,IF(TotalPMG <= 2500,1,0),0)),0) `NoofdealerswithDGSSalesFrom1001to2500Pesos`, 
										IFNULL((IF(TotalPMG >= 2501,IF(TotalPMG <= 5000,1,0),0)),0) `NoofdealerswithDGSSalesFrom2501to5000Pesos`,
										IFNULL((IF(TotalPMG >= 5001,IF(TotalPMG <= 10000,1,0),0)),0) `NoofdealerswithDGSSalesFrom5001to10000Pesos`,
										IFNULL((IF(TotalPMG >= 10001,IF(TotalPMG <= 20000,1,0),0)),0) `NoofdealerswithDGSSalesFrom10001to20000Pesos`,
										IFNULL((IF(TotalPMG >= 20001,IF(TotalPMG <= 30000,1,0),0)),0) `NoofdealerswithDGSSalesFrom20001to30000Pesos`,
										IFNULL((IF(TotalPMG >= 30001,IF(TotalPMG <= 40000,1,0),0)),0) `NoofdealerswithDGSSalesFrom30001to40000Pesos`,
										IFNULL((IF(TotalPMG >= 40001,IF(TotalPMG <= 50000,1,0),0)),0) `NoofdealerswithDGSSalesFrom40001to50000Pesos`,
										TotalPMG, TxnDate, CustomerID FROM (
										
										SELECT HoGeneralID, ID,'CFT' PMGType, TotalCFT TotalPMG, TxnDate, CustomerID  FROM salesinvoice
										WHERE LOCATE('-$BranchID', HoGeneralID) > 0
										AND DATE_FORMAT(TxnDate, '%b%y') = DATE_FORMAT(NOW(), '%b%y')
										
										UNION ALL
										
										SELECT HoGeneralID, ID,'NCFT' PMGType, TotalNCFT TotalPMG, TxnDate, CustomerID FROM salesinvoice
										WHERE LOCATE('-$BranchID', HoGeneralID) > 0
										AND DATE_FORMAT(TxnDate, '%b%y') = DATE_FORMAT(NOW(), '%b%y')
										
										UNION ALL 
										
										SELECT HoGeneralID, ID,'CPI' PMGType, TotalCPI TotalPMG, TxnDate, CustomerID FROM salesinvoice
										WHERE LOCATE('-$BranchID', HoGeneralID) > 0
										AND DATE_FORMAT(TxnDate, '%b%y') = DATE_FORMAT(NOW(), '%b%y')	
										ORDER BY PMGType 
										) a 
									)b
									INNER JOIN customer c ON c.ID = b.CustomerID
									AND LOCATE('-$BranchID', c.HOGeneralID) > 0
									WHERE c.CustomerTypeID = 1
									GROUP BY b.PMGType, DATE_FORMAT(b.TxnDate, '%b%y')");
        return $query;
    }
    
    //m8
    function callM8($BranchID){
        $query = $this->database->execute("SELECT BranchCode,Campaign,PMGType,Minimum,Maximum, DealerCount, DiscountedGrossSales, Discount, (DiscountedGrossSales * (Discount / 100)) DiscountTotal, HoGeneralID
									FROM (
										SELECT BranchCode,Campaign,PMGType,Minimum,Maximum,COUNT(BranchCode) DealerCount,
										SUM(DiscountedGrossSales) DiscountedGrossSales,Discount,MY, HoGeneralID
										FROM (
											SELECT s.HoGeneralID, s.ID, b.Code BranchCode,UPPER(DATE_FORMAT(s.TxnDate,'%b%y')) Campaign,DATE_FORMAT(s.TxnDate,'%m%y') MY,
											'CFT' PMGType, s.TotalCFT DiscountedGrossSales,
											(SELECT Minimum FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=1) Minimum,
											(SELECT Maximum FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=1) Maximum,
											(SELECT Discount FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=1) Discount
											FROM salesinvoice s
											INNER JOIN branch b ON b.ID = SPLIT_STR(s.HOGeneralID,'-',2)
											WHERE DATE_FORMAT(s.TxnDate, '%b%y') = DATE_FORMAT(NOW(), '%b%y')
											AND b.ID = $BranchID
										) a
										WHERE Minimum IS NOT NULL
										GROUP BY BranchCode,Minimum,Maximum
										
										UNION ALL
										
										SELECT BranchCode,Campaign,PMGType,Minimum,Maximum,COUNT(BranchCode) DealerCount,
										SUM(DiscountedGrossSales),Discount,MY, HoGeneralID
										FROM (
											SELECT s.HoGeneralID, s.ID, b.Code BranchCode,UPPER(DATE_FORMAT(s.TxnDate,'%b%y')) Campaign,DATE_FORMAT(s.TxnDate,'%m%y') MY,
											'NCFT' PMGType, s.TotalNCFT DiscountedGrossSales,
											(SELECT Minimum FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=2) Minimum,
											(SELECT Maximum FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=2) Maximum,
											(SELECT Discount FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=2) Discount
											FROM salesinvoice s
											INNER JOIN branch b ON b.ID = SPLIT_STR(s.HOGeneralID,'-',2)
											WHERE DATE_FORMAT(s.TxnDate, '%b%y') = DATE_FORMAT(NOW(), '%b%y')
											AND b.ID = $BranchID
										) a
										GROUP BY BranchCode,Minimum,Maximum
									) a    
									WHERE Minimum IS NOT NULL
									ORDER BY BranchCode,MY");
        return $query;
    }
    
    //m9
    function callM9($BranchID){
        $query = $this->database->execute("SELECT BranchCode,Campaign,PMGType,Minimum,Maximum, DealerCount, DiscountedGrossSales, Discount, (DiscountedGrossSales * (Discount / 100)) DiscountTotal, HoGeneralID, SFMLevel
									FROM (
										SELECT BranchCode,Campaign,PMGType,Minimum,Maximum,COUNT(BranchCode) DealerCount,
										SUM(DiscountedGrossSales) DiscountedGrossSales,Discount,MY, HoGeneralID, SFMLevel
										FROM (
											SELECT s.HoGeneralID, s.ID, b.Code BranchCode,UPPER(DATE_FORMAT(s.TxnDate,'%b%y')) Campaign,DATE_FORMAT(s.TxnDate,'%m%y') MY,
											'CFT' PMGType, s.TotalCFT DiscountedGrossSales,
											(SELECT Minimum FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=1) Minimum,
											(SELECT Maximum FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=1) Maximum,
											(SELECT Discount FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=1) Discount,
											c.CustomerTypeID SFMLevel
											FROM salesinvoice s
											INNER JOIN branch b ON b.ID = SPLIT_STR(s.HOGeneralID,'-',2)
											INNER JOIN customer c ON c.ID = s.CustomerID
												AND LOCATE('-$BranchID', c.HOGeneralID) > 0
											WHERE DATE_FORMAT(s.TxnDate, '%b%y') = DATE_FORMAT(NOW(), '%b%y')
											AND LOCATE('-$BranchID', s.HOGeneralID) > 0
										) a
										WHERE Minimum IS NOT NULL
										GROUP BY BranchCode,Minimum,Maximum
										
										UNION ALL
										
										SELECT BranchCode,Campaign,PMGType,Minimum,Maximum,COUNT(BranchCode) DealerCount,
										SUM(DiscountedGrossSales),Discount,MY, HoGeneralID, SFMLevel
										FROM (
											SELECT s.HoGeneralID, s.ID, b.Code BranchCode,UPPER(DATE_FORMAT(s.TxnDate,'%b%y')) Campaign,DATE_FORMAT(s.TxnDate,'%m%y') MY,
											'NCFT' PMGType, s.TotalNCFT DiscountedGrossSales,
											(SELECT Minimum FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=2) Minimum,
											(SELECT Maximum FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=2) Maximum,
											(SELECT Discount FROM `discount` WHERE Minimum <= s.TotalCFT  AND Maximum >= s.TotalCFT AND PMGID=2) Discount,
											c.CustomerTypeID SFMLevel
											FROM salesinvoice s
											INNER JOIN branch b ON b.ID = SPLIT_STR(s.HOGeneralID,'-',2)
											INNER JOIN customer c ON c.ID = s.CustomerID
												AND LOCATE('-$BranchID', c.HOGeneralID) > 0
											WHERE DATE_FORMAT(s.TxnDate, '%b%y') = DATE_FORMAT(NOW(), '%b%y')
											AND LOCATE('-$BranchID', s.HOGeneralID) > 0
										) a
										GROUP BY BranchCode,Minimum,Maximum
									) a    
									WHERE Minimum IS NOT NULL
									AND SFMLevel = 1
									ORDER BY MY");
        return $query;
    }
    
    //m6
    function callM6($BranchID){
        
        $query = $this->database->execute("SELECT CampaignCode, ID, CustomerCode, BranchCode, TotalDGSSales, newRecruits, Actives, IBMCode
                                        FROM
                                        (SELECT sfa.CampaignCode,c.ID, c.Code CustomerCode,(SELECT bb.Code BranchCode FROM branchparameter b
                                        INNER JOIN branch bb ON b.BranchID = bb.ID) BranchCode, sfa.TotalDGSSales,
                                        (SELECT COUNT(*) FROM tpi_rcustomeribm WHERE CustomerID=c.ID) newRecruits,
                                        (SELECT COUNT(*) FROM tpi_rcustomeribm ribm INNER JOIN customer c2
                                        ON c2.ID = ribm.ID AND c2.StatusID = 1
                                        WHERE ribm.CustomerID = c.ID) Actives,
                                        c.CustomerTypeID, ibm.Code IBMCode
                                        FROM customer c
                                        INNER JOIN tpi_sfasummary sfa ON sfa.CustomerID = c.ID
                                        AND SPLIT_STR(sfa.HOGeneralID, '-', 2) = SPLIT_STR(c.HOGeneralID, '-', 2)
                                        INNER JOIN (SELECT MAX(HOGeneralID) HOGeneralID, CustomerID FROM tpi_rcustomeribm
                                            GROUP BY CustomerID, HOGeneralID) tribm ON tribm.CustomerID = c.ID
                                        INNER JOIN tpi_rcustomeribm ribm ON ribm.HOGeneralID = tribm.HOGeneralID
                                        AND c.ID = ribm.CustomerID
                                        INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
                                        AND SPLIT_STR(ibm.HOGeneralID, '-', 2) = SPLIT_STR(c.HOGeneralID, '-', 2)
                                        WHERE SPLIT_STR(c.HOGeneralID, '-', 2) = $BranchID) a
                                        WHERE CustomerTypeID = 3
                                        AND CampaignCode = DATE_FORMAT(NOW(), '%b%y')");
        return $query;
        
    }
    
    //m5
    function callM5($BranchID){
        $query = $this->database->execute("SELECT DATE_FORMAT(si.TxnDate, '%b%y') Campaign, pmg.Code PMGCode, SUM(si.TotalCFT) DiscountedDGS, ibm.Code IBMCode
                                        FROM salesinvoice si
                                        INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                        INNER JOIN customer c ON c.ID = si.CustomerID
                                        AND SPLIT_STR(c.HOGeneralID, '-', 2) = b.ID
                                        INNER JOIN (SELECT MAX(HOGeneralID) HOGeneralID, CustomerID FROM tpi_rcustomeribm GROUP BY CustomerID, HOGeneralID) tribm
                                        ON tribm.CustomerID = c.ID
                                        INNER JOIN tpi_rcustomeribm ribm ON ribm.HOGeneralID = tribm.HOGeneralID AND c.ID = ribm.CustomerID
                                        AND SPLIT_STR(ribm.HOGeneralID, '-', 2) = b.ID
                                        INNER JOIN customer ibm ON ibm.ID = ribm.IBMID 
                                        AND SPLIT_STR(ibm.HOGeneralID, '-', 2) = b.ID
                                        INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID
                                        AND SPLIT_STR(sid.HOGeneralID, '-', 2) = b.ID
                                        INNER JOIN tpi_pmg pmg ON pmg.ID = sid.PMGID
                                        WHERE b.ID = $BranchID 
                                        AND c.CustomerTypeID = 3
                                        AND DATE_FORMAT(si.TxnDate, '%b%y') = DATE_FORMAT(NOW(), '%b%y')
                                        GROUP BY pmg.ID, DATE_FORMAT(si.TxnDate, '%b%y'), ibm.ID
                                        ORDER BY ibm.ID, si.TxnDate");
        return $query;
    }
    
    
    //m10
    function callM10($BranchID){
        
        $query = $this->database->execute("SELECT 
                                        b.Code, 
                                        DATE_FORMAT(NOW(), '%b%y') Campaign,
                                        (SELECT COUNT(c.ID) FROM customer c 
                                                INNER JOIN (SELECT MAX(HOGeneralID) HOGeneralID, CustomerID 
                                                        FROM tpi_rcustomeribm GROUP BY CustomerID, HOGeneralID) tribm
                                                ON tribm.CustomerID = c.ID
                                                INNER JOIN tpi_rcustomeribm ribm ON ribm.HOGeneralID = tribm.HOGeneralID
                                                AND c.ID = ribm.CustomerID
                                                WHERE DATE(ribm.EnrollmentDate) BETWEEN 
                                                DATE(DATE_ADD(NOW(), INTERVAL -4 MONTH)) AND DATE(NOW())
                                                AND c.StatusID = 5
                                        ) TerminatedWithin4Months,
                                        (SELECT COUNT(c.ID) FROM customer c 
                                                INNER JOIN (SELECT MAX(HOGeneralID) HOGeneralID, CustomerID 
                                                        FROM tpi_rcustomeribm GROUP BY CustomerID, HOGeneralID) tribm
                                                ON tribm.CustomerID = c.ID
                                                INNER JOIN tpi_rcustomeribm ribm ON ribm.HOGeneralID = tribm.HOGeneralID
                                                AND c.ID = ribm.CustomerID
                                                WHERE DATE(ribm.EnrollmentDate) BETWEEN 
                                                DATE(DATE_ADD(NOW(), INTERVAL -8 MONTH)) AND DATE(NOW())
                                                AND c.StatusID = 5
                                        ) TerminatedWithin8Months,
                                        (SELECT COUNT(c.ID) FROM customer c 
                                                INNER JOIN (SELECT MAX(HOGeneralID) HOGeneralID, CustomerID 
                                                        FROM tpi_rcustomeribm GROUP BY CustomerID, HOGeneralID) tribm
                                                ON tribm.CustomerID = c.ID
                                                INNER JOIN tpi_rcustomeribm ribm ON ribm.HOGeneralID = tribm.HOGeneralID
                                                AND c.ID = ribm.CustomerID
                                                WHERE DATE(ribm.EnrollmentDate) BETWEEN 
                                                DATE(DATE_ADD(NOW(), INTERVAL -4 MONTH)) AND DATE(NOW())
                                                AND c.StatusID = 5
                                        ) TerminatedWithin12Months,
                                        (SELECT COUNT(c.ID) FROM customer c 
                                                INNER JOIN (SELECT MAX(HOGeneralID) HOGeneralID, CustomerID 
                                                        FROM tpi_rcustomeribm GROUP BY CustomerID, HOGeneralID) tribm
                                                ON tribm.CustomerID = c.ID
                                                INNER JOIN tpi_rcustomeribm ribm ON ribm.HOGeneralID = tribm.HOGeneralID
                                                AND c.ID = ribm.CustomerID
                                                INNER JOIN tpi_customerdetails tcd ON tcd.HOGeneralID = c.HOGeneralID
                                                WHERE DATE(ribm.EnrollmentDate) BETWEEN 
                                                DATE(DATE_ADD(NOW(), INTERVAL -24 MONTH)) AND DATE(DATE_ADD(NOW(), INTERVAL -13 MONTH))
                                                AND c.StatusID = 5
                                                AND tcd.tpi_GSUTypeID IN (2, 3)
                                        ) TerminatedWithin13To24Months,
                                        (SELECT COUNT(c.ID) FROM customer c 
                                                INNER JOIN (SELECT MAX(HOGeneralID) HOGeneralID, CustomerID 
                                                        FROM tpi_rcustomeribm GROUP BY CustomerID, HOGeneralID) tribm
                                                ON tribm.CustomerID = c.ID
                                                INNER JOIN tpi_rcustomeribm ribm ON ribm.HOGeneralID = tribm.HOGeneralID
                                                AND c.ID = ribm.CustomerID
                                                INNER JOIN tpi_customerdetails tcd ON tcd.HOGeneralID = c.HOGeneralID
                                                WHERE DATE(ribm.EnrollmentDate) BETWEEN 
                                                DATE(DATE_ADD(NOW(), INTERVAL -24 MONTH)) AND DATE(DATE_ADD(NOW(), INTERVAL -13 MONTH))
                                                AND c.StatusID = 5
                                                AND tcd.tpi_GSUTypeID = 1
                                        ) TerminatedWithin13To24MonthsNonGSU
                                FROM branch b WHERE b.ID NOT IN (1,2,3)
                                AND b.ID = $BranchID");
        return $query;
    }
    
    
    //m2
    function callM2($BranchID){
        $query = $this->database->execute("SELECT DealerCode, DealerFullName, TransactionDate, MotherIBM, recruit, Paid, DATE_FORMAT(TransactionDate, '%b%y') Campaign
				FROM (SELECT igs.Code DealerCode, CONCAT(TRIM(igs.LastName),',',TRIM(igs.FirstName),' ',TRIM(igs.MiddleName)) DealerFullName, 
                                    IFNULL((SELECT MIN(TxnDate) FROM salesinvoice WHERE CustomerID = igs.ID
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID ),'') TransactionDate,
                                    ibmd.Code MotherIBM,COUNT(igsl.ID) recruit, SUM(s.NetAmount) - SUM(s.OutStandingBalance) Paid
                                    FROM salesinvoice s
                                    INNER JOIN branch b ON b.ID = SPLIT_STR(s.HOGeneralID,'-',2)
                                    LEFT JOIN customer igs ON igs.ID = s.CustomerID AND b.ID = SPLIT_STR(igs.HOGeneralID,'-',2)
                                    LEFT JOIN tpi_rcustomeribm igsl ON igsl.IBMID = igs.ID AND b.ID = SPLIT_STR(igsl.HOGeneralID,'-',2)
                                    INNER JOIN tpi_rcustomeribm ibm ON igs.ID = ibm.CustomerID AND b.ID = SPLIT_STR(ibm.HOGeneralID,'-',2)
                                    INNER JOIN customer ibmd ON ibmd.ID = ibm.IBMID AND b.ID = SPLIT_STR(ibmd.HOGeneralID,'-',2)
                                    WHERE igs.CustomerTypeID = 3
                                    AND b.ID = $BranchID
                                    GROUP BY s.CustomerID
                                    ORDER BY igs.Code) a
				WHERE DATE_FORMAT(TransactionDate, '%b%y') = DATE_FORMAT(NOW(), '%b%y')");
        return $query;
    }
    
    //function for D3
    function callD3($BranchID){
        $query = $this->database->execute("SELECT 
                            b.Code BranchCode,
                            (SUM(si.GrossAmount) - SUM(si.BasicDiscount)) OrderAmount,
                            SUM(si.NetAmount) InvoiceAmount,
                            (SELECT COUNT(ID) FROM salesinvoicedetails 
                                    WHERE SalesInvoiceID = si.ID 
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID) SalesUnit,
                            (SELECT COUNT(ID) FROM salesinvoice
                                    WHERE b.ID = SPLIT_STR(HOGeneralID, '-', 2)
                                    AND CustomerID = si.CustomerID
                                    AND DATE_FORMAT(TxnDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND StatusID = 7) ProcessedPO,
                            (SELECT COUNT(IBMID) FROM tpi_rcustomeribm
                                    WHERE CustomerID = si.CustomerID
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')) IBMCFFNo,
                            (SELECT COUNT(IBMID) FROM tpi_rcustomeribm
                                    WHERE CustomerID = si.CustomerID
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND CustomerID IN (SELECT ID FROM customer WHERE StatusID = 1
                                            AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID)) ActiveIBMCFFNo,
                            (SELECT COUNT(IBMID) FROM tpi_rcustomeribm
                                    WHERE CustomerID = si.CustomerID
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND CustomerID IN (SELECT ID FROM customer WHERE StatusID = 5
                                            AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID)) TerminatedIBMCFFNo,
                            (SELECT COUNT(ID) FROM customer
                                    WHERE ID = si.CustomerID
                                    AND CustomerTypeID = 5
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID) IBMCNo,
                            (SELECT COUNT(ID) FROM customer
                                    WHERE ID = si.CustomerID
                                    AND CustomerTypeID = 5
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND StatusID = 1) ActiveIBMCNo,
                            (SELECT COUNT(ID) FROM customer
                                    WHERE ID = si.CustomerID
                                    AND CustomerTypeID = 5
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND StatusID = 5) TerminatedIBMNo,
                            0 BeginningCounts,
                            0 NewRecruits,
                            0 AddOther,
                            (SELECT COUNT(ID) FROM customer 
                                    WHERE StatusID = 5
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID) TerminatedAccounts,
                            0 RemovedOthers,
                            (SUM(si.GrossAmount) - SUM(si.BasicDiscount)) OrderAmount2,
                            0 BeginningCounts2,
                            0 NewRecruitsOther,
                            0 AddOther2,
                            (SELECT COUNT(ID) FROM customer 
                                    WHERE StatusID = 5
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID) TerminatedAccounts2,
                            0 RemovedOthers2,
                            0 OrderAmountBCCustomerType,
                            0 DLStartDateNo,
                            (SELECT COUNT(DISTINCT CustomerID) FROM salesinvoice
                                    WHERE SPLIT_STR(HOGeneralID, '-', 2) = b.ID
                                    AND DATE_FORMAT(EnrollmentDate, '%b%y') = DATE_FORMAT(si.TxnDate, '%b%y')) DLCampDateNo,
                            0 BCStartDateNo,
                            0 BCCampDateNo,
                            (SELECT COUNT(ID) FROM customer
                                    WHERE StatusID = 1
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID) ActivesNo,
                            0 CBCActives,
                            0 Reactivated,
                            0 CBCReactivated,
                            0 igsndgs,
                            0 bfcndgs,
                            (SELECT COUNT(ID) FROM customer
                                    WHERE StatusID = 1
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID) ActiveDealers,
                            0 ActiveBC
                            FROM salesinvoice si
                            INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                            WHERE b.ID = $BranchID
                            AND DATE_FORMAT(si.TxnDate, '%Y-%m-%d') BETWEEN DATE_FORMAT(NOW() + INTERVAL -1 DAY, '%Y-%m-%d') 
                            AND DATE_FORMAT(NOW() - INTERVAL 1 DAY, '%Y-%m-%d')
                            GROUP BY b.ID");
        return $query;
    }
    
	
    //function for epaHR
    function callepaHR($BranchID){
        $query = $this->database->execute("SELECT 
                            b.Code BranchCode, 
                            DATE_FORMAT(NOW(), '%m/01/%Y') StartDate,
                            DATE_FORMAT(NOW(), '%m/%d/%Y') EndDate,
                            c.Code DealerCode, 
                            ibm.Code IBMCode,
                            c.Name DealerName,
                            (SELECT 
                                MAX(ApprovedCL)
                                FROM tpi_credit 
                                    WHERE CustomerID = c.ID
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID) MaxCL,
                            (SELECT
                                SUM(OutstandingBalance)
                                FROM salesinvoice
                                    WHERE DATE_FORMAT(TxnDate, '%b%y') = DATE_FORMAT(si.TxnDate, '%b%y')
                                    AND CustomerID = c.ID
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID) RunningBalance,
                            IFNULL((SELECT
                                SUM(OutstandingBalance)
                                FROM salesinvoice
                                    WHERE DATE_FORMAT(TxnDate, '%b%y') = DATE_FORMAT(si.TxnDate + INTERVAL -1 MONTH, '%b%y')
                                    AND CustomerID = c.ID
                                    AND SPLIT_STR(HOGeneralID, '-', 2) = b.ID), 0) PreviousBalance,
                            DATE_FORMAT(si.TxnDate, '%b%y') Campaign
                                FROM customer c
                                    INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
                                    INNER JOIN (SELECT MAX(HOGeneralID) HOGeneralID, CustomerID 
                                            FROM tpi_rcustomeribm
                                            GROUP BY HOGeneralID, CustomerID) tribm
                                        ON tribm.CustomerID = c.ID
                                        AND b.ID = SPLIT_STR(tribm.HOGeneralID, '-', 2)
                                    INNER JOIN tpi_rcustomeribm ribm ON ribm.HOGeneralID = tribm.HOGeneralID
                                    AND c.ID = ribm.CustomerID
                                    INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
                                        AND b.ID = SPLIT_STR(ibm.HOGeneralID, '-', 2)
                                    INNER JOIN salesinvoice si ON si.CustomerID = c.ID
                                        AND b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                    INNER JOIN creditterm ct ON ct.ID = si.CreditTermID
                                WHERE DATE_FORMAT(si.TxnDate, '%b%y') = DATE_FORMAT(NOW(), '%b%y')
                                AND b.ID = $BranchID
                            GROUP BY c.HOGeneralID, DATE_FORMAT(si.TxnDate, '%b%y')");
        return $query;
    }
    
}

$uiInterface = new UIInterfaceFileGenerator();
?>
