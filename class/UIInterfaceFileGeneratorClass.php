
<?php

Class UIInterfaceFileGenerator{
    
    public function UIInterfaceFileGenerator(){
        global $database;
        $this->database = $database;
    }
	
	//function to fetch all branches
    public function branch(){
        $query = $this->database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3)");
        return $query;
    }
    
	
	//SID - EOD
	//==================================================================================================
	
    //function to fetch all D1
    function callD1($BranchID, $runningDate){
			
        $query = $this->database->execute("SELECT 

								BranchCode,
								PMGCode,
								IF(PMGCode = 'CPI', SUM(GrossAmount), SUM(GrossAmount) * 0.75) TotalDGS,
								IF(PMGCode = 'CPI', SUM(GrossAmount) - SUM(AdditionalDiscount), (SUM(GrossAmount) * 0.75) - SUM(AdditionalDiscount)) TotalInvfaceoice,
								SUM(Quantity) Quantity,
								(SELECT COUNT(ID) FROM salesinvoice WHERE StatusID = 7 AND LOCATE('-$BranchID', HOGeneralID) > 0 AND DATE(TxnDate) = '$runningDate') ProcessPO

							FROM(SELECT
							b.Code BranchCode,
							pmg.Code PMGCode,
							IFNULL(sid.AddDiscount, 0) AdditionalDiscount,
							IFNULL(sid.ADPP, 0) ADPP,
							sid.TotalAmount GrossAmount,
							sid.Qty Quantity
							FROM salesinvoice si 
							INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
							INNER JOIN customer c ON c.ID = si.CustomerID
								AND LOCATE('-$BranchID', c.HOGeneralID) > 0
							INNER JOIN salesinvoicedetails sid ON si.ID = sid.SalesInvoiceID
								AND LOCATE('-$BranchID', sid.HOGeneralID) > 0
							INNER JOIN tpi_pmg pmg ON pmg.ID = sid.PMGID
							WHERE b.ID = $BranchID
							AND si.StatusID = 7
							AND DATE(si.TxnDate) = '$runningDate'

							UNION ALL 

							SELECT
							b.Code BranchCode,
							pmg.Code PMGCode,
							0 AdditionalDiscount,
							0 ADPP,
							(aoed.ProductEffectivePrice * aoed.ProductQuantity) GrossAmount,
							aoed.ProductQuantity Quantity
							FROM salesinvoice si 
							INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
							INNER JOIN customer c ON c.ID = si.CustomerID
								AND LOCATE('-$BranchID', c.HOGeneralID) > 0
							INNER JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID
								AND LOCATE('-$BranchID', dr.HOGeneralID) > 0
							INNER JOIN applied_overlay_promo_entitlement_details aoed ON aoed.ReferenceSalesOrderID = dr.RefTxnID
								AND LOCATE('-$BranchID', aoed.HOGeneralID) > 0
							INNER JOIN tpi_pmg pmg ON pmg.ID = aoed.ProductPMGID
							WHERE b.ID = $BranchID
							AND si.StatusID = 7
							AND DATE(si.TxnDate) = '$runningDate'

							UNION ALL

							SELECT
							b.Code BranchCode,
							pmg.Code PMGCode,
							0 AdditionalDiscount,
							0 ADPP,
							(aied.ProductEffectivePrice * aied.ProductQuantity) GrossAmount,
							aied.ProductQuantity Quantity
							FROM salesinvoice si 
							INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
							INNER JOIN customer c ON c.ID = si.CustomerID
								AND LOCATE('-$BranchID', c.HOGeneralID) > 0
							INNER JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID
								AND LOCATE('-$BranchID', dr.HOGeneralID) > 0
							INNER JOIN applied_incentive_entitlement_details aied ON aied.ReferenceSalesOrderID = dr.RefTxnID
								AND LOCATE('-$BranchID', aied.HOGeneralID) > 0
							INNER JOIN tpi_pmg pmg ON pmg.ID = aied.ProductPMGID
							WHERE b.ID = $BranchID
							AND si.StatusID = 7
							AND DATE(si.TxnDate) = '$runningDate'

							UNION ALL

							SELECT
							b.Code BranchCode,
							pmg.Code PMGCode,
							0 AdditionalDiscount,
							0 ADPP,
							(aspe.ProductEffectivePrice * aspe.ProductQuantity) GrossAmount,
							aspe.ProductQuantity Quantity
							FROM salesinvoice si 
							INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
							INNER JOIN customer c ON c.ID = si.CustomerID
								AND LOCATE('-$BranchID', c.HOGeneralID) > 0
							INNER JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID
								AND LOCATE('-$BranchID', dr.HOGeneralID) > 0
							INNER JOIN applied_special_promo_entitlement_details aspe ON aspe.ReferenceSalesOrderID = dr.RefTxnID
								AND LOCATE('-$BranchID', aspe.HOGeneralID) > 0
							INNER JOIN tpi_pmg pmg ON pmg.ID = aspe.ProductPMGID
							WHERE b.ID = $BranchID
							AND si.StatusID = 7
							AND DATE(si.TxnDate) = '$runningDate') atbl
							GROUP BY PMGCode");
        return $query;
    }
    
	//function for D3
    function callD3($BranchID, $runningDate){
        $query = $this->database->execute("SELECT 
                            b.Code BranchCode,
							DATE_FORMAT(si.TxnDate, '%m/%d/%Y') TxnDate,
                            (SUM(si.GrossAmount) - SUM(si.BasicDiscount)) OrderAmount,
                            SUM(si.NetAmount) InvoiceAmount,
                            (SELECT COUNT(ID) FROM salesinvoicedetails 
                                    WHERE SalesInvoiceID = si.ID 
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0) SalesUnit,
                            (SELECT COUNT(ID) FROM salesinvoice
                                    WHERE LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0
                                    AND CustomerID = si.CustomerID
                                    AND DATE_FORMAT(TxnDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND StatusID = 7) ProcessedPO,
                            (SELECT COUNT(IBMID) FROM tpi_rcustomeribm
                                    WHERE CustomerID = si.CustomerID
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')) IBMCFFNo,
                            (SELECT COUNT(IBMID) FROM tpi_rcustomeribm
                                    WHERE CustomerID = si.CustomerID
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND CustomerID IN (SELECT ID FROM customer WHERE StatusID = 1
                                            AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)) ActiveIBMCFFNo,
                            (SELECT COUNT(IBMID) FROM tpi_rcustomeribm
                                    WHERE CustomerID = si.CustomerID
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND CustomerID IN (SELECT ID FROM customer WHERE StatusID = 5
                                            AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)) TerminatedIBMCFFNo,
                            (SELECT COUNT(ID) FROM customer
                                    WHERE ID = si.CustomerID
                                    AND CustomerTypeID = 2
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0) IBMCNo,
                            (SELECT COUNT(ID) FROM customer
                                    WHERE ID = si.CustomerID
                                    AND CustomerTypeID = 2
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND StatusID = 1) ActiveIBMCNo,
                            (SELECT COUNT(ID) FROM customer
                                    WHERE ID = si.CustomerID
                                    AND CustomerTypeID = 2
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND StatusID = 5) TerminatedIBMNo,
                            0 BeginningCounts,
                            0 NewRecruits,
                            0 AddOther,
                            (SELECT COUNT(ID) FROM customer 
                                    WHERE StatusID = 5
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0) TerminatedAccounts,
                            0 RemovedOthers,
                            (SUM(si.GrossAmount) - SUM(si.BasicDiscount)) OrderAmount2,
                            0 BeginningCounts2,
                            0 NewRecruitsOther,
                            0 AddOther2,
                            (SELECT COUNT(ID) FROM customer 
                                    WHERE StatusID = 5
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0) TerminatedAccounts2,
                            0 RemovedOthers2,
                            0 OrderAmountBCCustomerType,
                            0 DLStartDateNo,
                            (SELECT COUNT(DISTINCT CustomerID) FROM salesinvoice
                                    WHERE LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0
                                    AND DATE_FORMAT(EnrollmentDate, '%b%y') = DATE_FORMAT(si.TxnDate, '%b%y')) DLCampDateNo,
                            0 BCStartDateNo,
                            0 BCCampDateNo,
                            (SELECT COUNT(ID) FROM customer
                                    WHERE StatusID = 1
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0) ActivesNo,
                            0 CBCActives,
                            0 Reactivated,
                            0 CBCReactivated,
                            0 igsndgs,
                            0 bfcndgs,
                            (SELECT COUNT(ID) FROM customer
                                    WHERE StatusID = 1
                                    AND DATE_FORMAT(EnrollmentDate, '%Y-%m-%d') = DATE_FORMAT(si.TxnDate, '%Y-%m-%d')
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0) ActiveDealers,
                            0 ActiveBC
                            FROM salesinvoice si
                            INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                            WHERE b.ID = $BranchID
                            AND DATE_FORMAT(si.TxnDate, '%Y-%m-%d') = '$runningDate'
                            GROUP BY b.ID");
        return $query;
    }
    
    //function to fetch all PFL
    function callPFL($BranchID, $runningDate){
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
									AND DATE_FORMAT(c.EnrollmentDate, '%Y-%m-%d') = '$runningDate'");
                                
        return $query;
    }
    
	
	
	//SID - EOM
	//====================================================================================================================================================
	
    //m1
    function callM1($BranchID, $runningDate){
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
												WHERE DATE_FORMAT(TxnDate, '%b%y') = DATE_FORMAT('$runningDate', '%b%y')
												
                                                UNION ALL
                                                SELECT HoGeneralID, ID,'NCFT' PMGType, TotalNCFT TotalPMG, TxnDate, CustomerID FROM salesinvoice
												WHERE DATE_FORMAT(TxnDate, '%b%y') = DATE_FORMAT('$runningDate', '%b%y')
												
                                                UNION ALL 
                                                SELECT HoGeneralID, ID,'CPI' PMGType, TotalCPI TotalPMG, TxnDate, CustomerID FROM salesinvoice
												WHERE DATE_FORMAT(TxnDate, '%b%y') = DATE_FORMAT('$runningDate', '%b%y')
												
                                                ORDER BY SPLIT_STR(HoGeneralID,'-',2), PMGType 
                                                
                                            ) a
                                        )b 
                                        INNER JOIN customer c ON c.ID = b.CustomerID
											AND LOCATE('-$BranchID', c.HOGeneralID) > 0
                                        WHERE LOCATE('-$BranchID', b.HOGeneralID) > 0
                                        AND c.CustomerTypeID = 1
                                        GROUP BY SPLIT_STR(b.HoGeneralID,'-',2),b.PMGType, DATE_FORMAT(b.TxnDate, '%b%y')");
        return $query;
    }
    
    //m8
    function callM8($BranchID, $runningDate){
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
                                                WHERE DATE_FORMAT(s.TxnDate,'%b%y') = DATE_FORMAT('$runningDate', '%b%y')
                                                AND LOCATE('-$BranchID',s.HOGeneralID) > 0
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
                                                WHERE DATE_FORMAT(s.TxnDate,'%b%y') = DATE_FORMAT('$runningDate', '%b%y')
                                                AND LOCATE('-$BranchID',s.HOGeneralID) > 0
                                            ) a
                                            GROUP BY BranchCode,Minimum,Maximum
                                        ) a    
                                        WHERE Minimum IS NOT NULL
                                        ORDER BY BranchCode,MY");
        return $query;
    }
    
    //m9
    function callM9($BranchID, $runningDate){
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
                                                WHERE DATE_FORMAT(s.TxnDate,'%b%y') = DATE_FORMAT('$runningDate', '%b%y')
                                                AND LOCATE('-$BranchID', s.HOGeneralID) > 0
                                                AND c.CustomerTypeID = 1
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
                                                WHERE DATE_FORMAT(s.TxnDate,'%b%y') = DATE_FORMAT('$runningDate', '%b%y')
                                                AND LOCATE('-$BranchID', s.HOGeneralID) > 0
                                                AND c.CustomerTypeID = 1
                                            ) a
                                            GROUP BY BranchCode,Minimum,Maximum
                                        ) a    
                                        WHERE Minimum IS NOT NULL
                                        ORDER BY BranchCode,MY");
        return $query;
    }
    
    //m6
    function callM6($BranchID, $runningDate){
        
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
                                        AND LOCATE('-$BranchID', sfa.HOGeneralID) > 0
                                        INNER JOIN tpi_rcustomeribm ribm ON c.ID = ribm.CustomerID
											AND LOCATE('-$BranchID', ribm.HOGeneralID) > 0
											AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID
												AND LOCATE('-$BranchID', HOGeneralID) > 0)
                                        INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
											AND LOCATE('-$BranchID', ibm.HOGeneralID) > 0
                                        WHERE LOCATE('-$BranchID', c.HOGeneralID) > 0) a
                                        WHERE CustomerTypeID = 3
                                        AND CampaignCode = DATE_FORMAT('$runningDate', '%b%y')");
        return $query;
        
    }
    
    //m5
    function callM5($BranchID, $runningDate){
        $query = $this->database->execute("SELECT DATE_FORMAT(si.TxnDate, '%b%y') Campaign, pmg.Code PMGCode, SUM(si.TotalCFT) DiscountedDGS, ibm.Code IBMCode
                                        FROM salesinvoice si
                                        INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                        INNER JOIN customer c ON c.ID = si.CustomerID
											AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
                                        INNER JOIN tpi_rcustomeribm ribm ON c.ID = ribm.CustomerID
											AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm 
												WHERE CustomerID = c.ID
												AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
											AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
                                        INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
											AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
                                        INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID
											AND LOCATE(CONCAT('-', b.ID), sid.HOGeneralID) > 0
                                        INNER JOIN tpi_pmg pmg ON pmg.ID = sid.PMGID
                                        WHERE b.ID = $BranchID
                                        AND c.CustomerTypeID = 3
                                        AND DATE_FORMAT(si.TxnDate, '%b%y') = DATE_FORMAT('$runningDate', '%b%y')
                                        GROUP BY pmg.ID, DATE_FORMAT(si.TxnDate, '%b%y'), ibm.ID
                                        ORDER BY ibm.ID, si.TxnDate");
        return $query;
    }
    
    
    //m10
    function callM10($BranchID, $runningDate){
        
        $query = $this->database->execute("SELECT 
                                        b.Code, 
                                        DATE_FORMAT('$runningDate', '%b%y') Campaign,
                                        (SELECT COUNT(c.ID) FROM customer c 
                                                INNER JOIN tpi_rcustomeribm ribm ON c.ID = ribm.CustomerID
												AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID
													AND LOCATE('-$BranchID', HOGeneralID) > 0)
                                                WHERE DATE(ribm.EnrollmentDate) BETWEEN 
                                                DATE(DATE_ADD('$runningDate', INTERVAL -4 MONTH)) AND DATE('$runningDate')
                                                AND c.StatusID = 5
                                        ) TerminatedWithin4Months,
                                        (SELECT COUNT(c.ID) FROM customer c 
                                                INNER JOIN tpi_rcustomeribm ribm ON c.ID = ribm.CustomerID
												AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID
													AND LOCATE('-$BranchID', HOGeneralID) > 0)
                                                WHERE DATE(ribm.EnrollmentDate) BETWEEN 
                                                DATE(DATE_ADD('$runningDate', INTERVAL -8 MONTH)) AND DATE('$runningDate')
                                                AND c.StatusID = 5
                                        ) TerminatedWithin8Months,
                                        (SELECT COUNT(c.ID) FROM customer c 
                                                INNER JOIN tpi_rcustomeribm ribm ON c.ID = ribm.CustomerID
												AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID
													AND LOCATE('-$BranchID', HOGeneralID) > 0)
                                                WHERE DATE(ribm.EnrollmentDate) BETWEEN 
                                                DATE(DATE_ADD('$runningDate', INTERVAL -12 MONTH)) AND DATE('$runningDate')
                                                AND c.StatusID = 5
                                        ) TerminatedWithin12Months,
                                        (SELECT COUNT(c.ID) FROM customer c 
                                                INNER JOIN tpi_rcustomeribm ribm ON c.ID = ribm.CustomerID
												AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID
													AND LOCATE('-$BranchID', HOGeneralID) > 0)
                                                INNER JOIN tpi_customerdetails tcd ON tcd.HOGeneralID = c.HOGeneralID
                                                WHERE DATE(ribm.EnrollmentDate) BETWEEN 
                                                DATE(DATE_ADD('$runningDate', INTERVAL -24 MONTH)) AND DATE(DATE_ADD('$runningDate', INTERVAL -13 MONTH))
                                                AND c.StatusID = 5
                                                AND tcd.tpi_GSUTypeID IN (2, 3)
                                        ) TerminatedWithin13To24Months,
                                        (SELECT COUNT(c.ID) FROM customer c 
                                                INNER JOIN tpi_rcustomeribm ribm ON c.ID = ribm.CustomerID
												AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID
													AND LOCATE('-$BranchID', HOGeneralID) > 0)
                                                INNER JOIN tpi_customerdetails tcd ON tcd.HOGeneralID = c.HOGeneralID
                                                WHERE DATE(ribm.EnrollmentDate) BETWEEN 
                                                DATE(DATE_ADD('$runningDate', INTERVAL -24 MONTH)) AND DATE(DATE_ADD('$runningDate', INTERVAL -13 MONTH))
                                                AND c.StatusID = 5
                                                AND tcd.tpi_GSUTypeID = 1
                                        ) TerminatedWithin13To24MonthsNonGSU
                                FROM branch b WHERE b.ID NOT IN (1,2,3)
                                AND b.ID = $BranchID");
        return $query;
    }
    
    
	//function for M7
    function callM7($BranchID, $runningDate){
        $query = $this->database->execute("SELECT
                         b.ID BranchID,
                         sc.CampaignCode,
                         DATE_FORMAT('$runningDate', '%m/%d/%y') RunningDate,
                        (SELECT
                            SUM(TotalDGSAmount)
                        FROM tpi_sfasummary_pmg
                        WHERE CampaignCode = DATE_FORMAT('$runningDate', '%b%y')
                        AND LOCATE(CONCAT('-', $BranchID), HOGeneralID) > 0)    TotalDGS,
                        (SELECT
                            SUM(TotalInvoiceAmount)
                        FROM tpi_sfasummary_pmg
                        WHERE CampaignCode = DATE_FORMAT('$runningDate', '%b%y')
                            AND PMGType IN ('CFT', 'NCFT')
                            AND LOCATE(CONCAT('-', $BranchID), HOGeneralID) > 0) TotalInvoiceAmount,
                        (SELECT
                            SUM(sid.Qty)
                        FROM salesinvoice si
                            INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID
				AND LOCATE(CONCAT('-',$BranchID), sid.HOGeneralID) > 0
				AND LOCATE(CONCAT('-',$BranchID), si.HOGeneralID) > 0
                            WHERE DATE_FORMAT(si.TxnDate, '%b%y') = DATE_FORMAT('$runningDate', '%b%y')) UnitSolds,
                        (SELECT
                            SUM(TotalNumberOfPO)
                        FROM tpi_sfasummary
                            WHERE CampaignCode = DATE_FORMAT('$runningDate', '%b%y')
                            AND LOCATE(CONCAT('-', $BranchID), HOGeneralID) > 0)    TotalNumberOfPO,
                        SUM(sc.TotalBeginningCount)    BeginningCount,
                        SUM(sc.TotalRecruits) NewRecuits,
                        SUM(sc.TotalReactivated) Reactivated,
                        -- SUM(sc.PlusTotalTransferred) AddOther,
                        0 AddOther,
                        SUM(sc.TotalTerminated) Termination,
                        -- SUM(sc.LessTotalTransferred) RemoveOther,
                        0 RemoveOther,
                        (SELECT (SUM(TotalDGSAmount) - SUM(TotalCPIAmount)) FROM tpi_sfasummary_pmg 
                            WHERE CampaignCode = DATE_FORMAT('$runningDate', '%b%y')
                            AND LOCATE(CONCAT('-', $BranchID), HOGeneralID) > 0) TotalSales,
                        (SELECT COUNT(ID) FROM customer WHERE StatusID = 1
                            AND LOCATE(CONCAT('-', $BranchID), HOGeneralID) > 0
                            AND DATE_FORMAT(EnrollmentDate, '%b%y') = DATE_FORMAT('$runningDate', '%b%y')) TotalActive,
                        0 BeginningCount2,
                        0 NewRecruit2,
                        0 Reactivated2,
                        0 AddOther2,
                        0 Termination2,
                        0 RemoveOther2,
                        0 Totals,
                        0 Active2,
                        (SELECT SUM(TotalCPIAmount) FROM tpi_sfasummary_pmg 
                            WHERE CampaignCode = DATE_FORMAT('$runningDate', '%b%y')
                            AND LOCATE(CONCAT('-', $BranchID), HOGeneralID) > 0) TotalSales
                        FROM staffcount sc
                        INNER JOIN branch b ON b.ID = SPLIT_STR(sc.HOGeneralID, '-', 2)
                        WHERE b.ID = $BranchID 
                        AND sc.CampaignCode = DATE_FORMAT('$runningDate', '%b%y')
                        GROUP BY sc.CampaignCode, b.ID");
    }
	
    //m2
    function callM2($BranchID, $runningDate){
        $query = $this->database->execute("SELECT DealerCode, DealerFullName, TransactionDate, MotherIBM, recruit, Paid, DATE_FORMAT(TransactionDate, '%b%y') Campaign
									FROM (SELECT igs.Code DealerCode, CONCAT(TRIM(igs.LastName),',',TRIM(igs.FirstName),' ',TRIM(igs.MiddleName)) DealerFullName, 
                                    IFNULL((SELECT MIN(TxnDate) FROM salesinvoice WHERE CustomerID = igs.ID
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID)),'') TransactionDate,
                                    ibmd.Code MotherIBM,COUNT(igsl.ID) recruit, SUM(s.NetAmount) - SUM(s.OutStandingBalance) Paid
                                    FROM salesinvoice s
                                    INNER JOIN branch b ON b.ID = SPLIT_STR(s.HOGeneralID,'-',2)
										AND LOCATE(CONCAT('-', b.ID), s.HOGeneralID) > 0
                                    LEFT JOIN customer igs ON igs.ID = s.CustomerID 
										AND LOCATE(CONCAT('-', b.ID), igs.HOGeneralID) > 0
                                    LEFT JOIN tpi_rcustomeribm igsl ON igsl.IBMID = igs.ID
										AND LOCATE(CONCAT('-', b.ID), igsl.HOGeneralID) > 0
                                    INNER JOIN tpi_rcustomeribm ibm ON igs.ID = ibm.CustomerID
										AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
                                    INNER JOIN customer ibmd ON ibmd.ID = ibm.IBMID 
										AND LOCATE(CONCAT('-', b.ID), ibmd.HOGeneralID) > 0
                                    WHERE igs.CustomerTypeID = 3
										AND b.ID = $BranchID
										AND DATE_FORMAT(s.TxnDate, '%b%y') = DATE_FORMAT('$runningDate', '%b%y')
                                    GROUP BY s.CustomerID
                                    ORDER BY igs.Code				    
                                    ) a");
        return $query;
    }
    
    //function for epaHR
    function callepaHR($BranchID, $runningDate){
        $query = $this->database->execute("SELECT 
                            b.Code BranchCode, 
                            DATE_FORMAT('$runningDate', '%m/01/%Y') StartDate,
                            DATE_FORMAT('$runningDate', '%m/%d/%Y') EndDate,
                            c.Code DealerCode, 
                            ibm.Code IBMCode,
                            c.Name DealerName,
                            (SELECT 
                                MAX(ApprovedCL)
                                FROM tpi_credit 
                                    WHERE CustomerID = c.ID
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0) MaxCL,
                            (SELECT
                                SUM(OutstandingBalance)
                                FROM salesinvoice
                                    WHERE DATE_FORMAT(TxnDate, '%b%y') = DATE_FORMAT(si.TxnDate, '%b%y')
                                    AND CustomerID = c.ID
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0) RunningBalance,
                            (SELECT
                                SUM(OutstandingBalance)
                                FROM salesinvoice
                                    WHERE DATE_FORMAT(TxnDate, '%b%y') = DATE_FORMAT(si.TxnDate + INTERVAL -1 MONTH, '%b%y')
                                    AND CustomerID = c.ID
                                    AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0) PreviousBalance,
                            DATE_FORMAT(si.TxnDate, '%b%y') Campaign
                                FROM customer c
                                    INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)                                    
                                    INNER JOIN tpi_rcustomeribm ribm ON c.ID = ribm.CustomerID
					AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
					AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID
						AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
                                    INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
                                        AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
                                    INNER JOIN salesinvoice si ON si.CustomerID = c.ID
                                        AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
                                    INNER JOIN creditterm ct ON ct.ID = si.CreditTermID
                                WHERE DATE_FORMAT(si.TxnDate, '%b%y') = DATE_FORMAT('$runningDate', '%b%y')
                                AND b.ID = $BranchID
                            GROUP BY c.HOGeneralID, DATE_FORMAT(si.TxnDate, '%b%y')");
        return $query;
    }
    
	
	
	//SID - DS Cons
	//========================================================================================================
	
    //CST
	function callCST($BranchID, $runningDate){
		
		$query = $this->database->execute("SELECT
										b.Code BranchCode,
										b.Code SystemBranchCode,
										DATE_FORMAT(rcs.EnrollmentDate, '%d/%m/%y') StatusDate,
										c.Code CustomerCode,
										IF(c.CustomerTypeID = 1, 'DL', 'SC') CustomerType,
										IF(c.CustomerTypeID = 1, 'Dealers', 'Group Sales Co-ordinators') CustomerTypeDescription,
										c.LastName CustomerLastName,
										c.FirstName CustomerFirstName,
										s.Code `Status`,
										s.Name StatusDescription,
										rec.Code RecruitedBy,
										rec.Name RecruiterName,
										ibm.Code SalesRep,
										ibm.Name IBMName,
										IFNULL(tcd.ZipCode, '0000') ZipCode,
										DATE_FORMAT(c.Birthdate, '%d/%m/%y') Birthdate,
										ct.Code CreditTerms,
										ct.Name CreditTermDescription,
										tc.ApprovedCL MaxCreditLimit,
										DATE_FORMAT(rcs.EnrollmentDate, '%d/%m/%y') StatusDate,
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
										0 dswkfl
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
									AND DATE(rcs.EnrollmentDate) = '$runningDate'");
		return $query;
		
	}
	
    //function for IST
	function callIST($BranchID, $runningDate){
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
										AND DATE(si.TxnDate) = '$runningDate'

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
										AND DATE(si.TxnDate) = '$runningDate'

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
										AND DATE(si.TxnDate) = '$runningDate'

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
										AND DATE(si.TxnDate) = '$runningDate'
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
    
	//DST
	function callDST($BranchID, $runningDate){
		$query = $this->database->execute("SELECT
										b.Code BranchCode,
										b.Code SystemBranchName,
										'' vmin,
										DATE_FORMAT(so.TxnDate, '%d/%m/%y') SalesOrderDate,
										DATE_FORMAT(so.TxnDate, '%b%y') Campaign,
										'' BankCC,
										LPAD(so.ID, 8, 0) CreditNote,
										si.DocumentNo SINumber,
										IF(si.StatusID = 7, 70, IF(si.StatusID = 8, 90, '')) Stage,
										'REGULAR' SalesOrderType,
										TRIM(c.Code) CustomerCode,
										c.Name CustomerName,
										ct.Code CreditTermCode,
										ct.Name CreditTermName,										
										ibm.Code IBMCode,
										ibm.Name IBMName,
										rec.Code RecruiterCode,
										rec.Name RecruiterName,
										si.GrossAmount TotalCSP,
										si.TotalCPI TotalCSPLessCPI,
										(si.TotalCFT + si.TotalNCFT + si.TotalCPI) DiscountedGrossAmount,
										si.BasicDiscount,
										si.AddtlDiscount AdditionalDiscount,
										(si.GrossAmount - si.BasicDiscount - si.AddtlDiscount) SalesWithVat,
										(((si.GrossAmount - si.BasicDiscount - si.AddtlDiscount) / 1.12) * 0.12) VatPercentage,
										((si.GrossAmount - si.BasicDiscount - si.AddtlDiscount) / 1.12) VatableSales,
										si.AddtlDiscountPrev TotalAdjustment,
										(((si.GrossAmount - si.BasicDiscount - si.AddtlDiscount) / 1.12) - si.AddtlDiscountPrev) TotalNSV,
										si.NetAmount TotalSalesInvoice,
										IF(si.StatusID = 8, LPAD(si.ID, 8, 0), '') CreditForInvoice,
										DATE_FORMAT(so.TxnDate, '%d/%m/%y') SalesOrderDate2
									FROM customer c 
									INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
									INNER JOIN tpi_rcustomeribm ribm ON ribm.CustomerID = c.ID
										AND ribm.ID = (SELECT MAX(ID) FROM tpi_rcustomeribm WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
										AND LOCATE(CONCAT('-', b.ID), ribm.HOGeneralID) > 0
									INNER JOIN customer ibm ON ibm.ID = ribm.IBMID
										AND LOCATE(CONCAT('-', b.ID), ibm.HOGeneralID) > 0
									LEFT JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
									LEFT JOIN customer rec ON rec.ID = tcd.tpi_RecruiterID
										AND LOCATE(CONCAT('-', b.ID), rec.HOGeneralID) > 0
									INNER JOIN salesinvoice si ON si.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), si.HOGeneralID) > 0
									LEFT JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID AND LOCATE(CONCAT('-', b.ID), dr.HOGeneralID) > 0
									LEFT JOIN salesorder so ON so.ID = dr.RefTxnID AND LOCATE(CONCAT('-', b.ID), so.HOGeneralID) > 0
									INNER JOIN creditterm ct ON ct.ID = si.CreditTermID
									WHERE b.ID = $BranchID
									AND DATE(si.TxnDate) = '$runningDate'
									GROUP BY si.ID");
		return $query;
	}
    
    
    
    
    //function for OPT
    function callOPT($BranchID, $runningDate){
        $query = $this->database->execute("SELECT * FROM 
                        (SELECT 
                            b.Code BranchCode,
                            '' SystemBranchName,
                            DATE_FORMAT(dm.TxnDate, '%m/%d/%y') EffectiveDate,
                            IF(mt.ID = 1, 'DM', 'CM') PaymentType,
                            CONCAT('DCM', REPEAT('0', (5- LENGTH(dm.ID))), dm.ID) MemoNo,
                            '' AREntryNo,
                            dm.DocumentNo CollectionReceipt,
                            c.Code DealerCode,
                            c.Name DealerName,
                            CASE WHEN c.CustomerTypeID IN (1,6)  THEN 'DL'
                                WHEN c.CustomerTypeID IN (2,3,4,5) THEN 'SC'
                                END CustomerType,
							CASE WHEN c.CustomerTypeID IN (1,6)  THEN 'Dealers'
                                WHEN c.CustomerTypeID IN (2,3,4,5) THEN 'Group Sales Co-ordinators'
                                END CustomerTypeDesc,
                            IF(c.StatusID = 5, 'TM', 'AP') CustomerStatus,
                            IF(c.StatusID = 5, 'Terminated', 'Appointed') StatusDescription,
                            r.Code ReasonCode,
                            r.Name ReasonName,
                            'NON-TRADE' TradeNonTrade,
                            dm.TotalAmount InvoiceAmount,
                            dm.TotalAppliedAmount AppliedAmount,
							dm.Remarks Remarks1,
                            "" Remarks2,
							"" UnknownField,
                            'Blank' Voptcmp,
                            0 voptsfearned,
                            0 voptsfclaimed
                        FROM dmcm dm 
                        INNER JOIN branch b ON b.ID = SPLIT_STR(dm.HOGeneralID, '-', 2)
                        INNER JOIN customer c ON c.ID = dm.CustomerID 
							AND LOCATE(CONCAT('-', b.ID), c.HoGeneralID) > 0
                        INNER JOIN memotype mt ON mt.ID = dm.MemoTypeID
                        INNER JOIN sfm_level sf ON sf.codeID = c.CustomerTypeID
                        INNER JOIN `status` s ON s.ID = c.StatusID
                        LEFT JOIN reason r ON r.ID = dm.ReasonID
                        WHERE DATE(dm.TxnDate) = '$runningDate'
                        AND b.ID = $BranchID

                        UNION ALL

                        SELECT 
                            b.Code BranchCode,
                            '' SystemBranchName,
                            DATE_FORMAT(or.TxnDate, '%m/%d/%y') EffectiveDate,
                            IF(orc.ID != '', 'CASH', '') PaymentType,
                            CONCAT('OR', REPEAT('0', (6- LENGTH(or.ID))), or.ID) MemoNo,
                            '' AREntryNo,
                            or.DocumentNo CollectionReceipt,
                            c.Code DealerCode,
                            c.Name DealerName,
                            CASE WHEN c.CustomerTypeID IN (1,6)  THEN 'DL'
                                WHEN c.CustomerTypeID IN (2,3,4,5) THEN 'SC'
                                END CustomerType,
							CASE WHEN c.CustomerTypeID IN (1,6)  THEN 'Dealers'
                                WHEN c.CustomerTypeID IN (2,3,4,5) THEN 'Group Sales Co-ordinators'
                                END CustomerTypeDesc,
                            IF(c.StatusID = 5, 'TM', 'AP') CustomerStatus,
                            IF(c.StatusID = 5, 'Terminated', 'Appointed') StatusDescription,
                            r.Code ReasonCode,
                            r.Name ReasonName,
                            ofr.Name TradeNonTrade,
                            or.TotalAmount InvoiceAmount,
                            or.TotalAppliedAmt AppliedAmount,
                            or.Remarks Remarks1,
							"" Remarks2,
							"" UnknownField,
                            'Blank' Voptcmp,
                            0 voptsfearned,
                            0 voptsfclaimed
                        FROM officialreceipt `or` 
                        INNER JOIN branch b ON b.ID = SPLIT_STR(or.HOGeneralID, '-', 2)
                        LEFT JOIN officialreceiptcash orc ON orc.OfficialReceiptID = or.ID 
                                AND LOCATE(CONCAT('-', b.ID), orc.HoGeneralID) > 0
                                AND orc.ID IS NOT NULL
                        INNER JOIN customer c ON c.ID = or.CustomerID 
				AND LOCATE(CONCAT('-', b.ID), c.HoGeneralID) > 0
                        INNER JOIN sfm_level sf ON sf.codeID = c.CustomerTypeID
                        INNER JOIN `status` s ON s.ID = c.StatusID
                        INNER JOIN officialreceipttype ofr ON ofr.ID = or.ORTypeID
                        LEFT JOIN reason r ON r.ID = or.ReasonID
                        WHERE DATE(or.TxnDate) = '$runningDate'
                        AND b.ID = $BranchID) atbl
                        ORDER BY DealerName, EffectiveDate");
        return $query;
    }
    
}

$uiInterface = new UIInterfaceFileGenerator();
?>
