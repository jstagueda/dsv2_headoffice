<?php
class ClassInterface
{
	function spSelectBranch($database){
		$result = $database->execute("SELECT ID, Code, Name FROM branch WHERE StatusID = 1 ORDER BY Name");
		return $result;
	}

	function spSalesTransactionsInterfaceFile ($database, $ID, $lastrun){
                $result = $database->execute("SELECT
                                            br.Code BranchCode,
                                            DATE_FORMAT(si.TxnDate, '%d%/%m/%y') TxnDate,
                                            si.DocumentNo DocumentNo,
                                            cu.Code CustCode,
                                            CASE
                                                    WHEN cu.CustomerTypeID = 99 THEN em.Code
                                                    ELSE ''
                                            END EmpCode,
                                            CASE
                                                    WHEN cu.CustomerTypeID = 99 THEN CONCAT(em.LastName, ',', em.FirstName)
                                                    ELSE ''
                                            END EmpName,
                                            REPLACE(FORMAT(si.NetAmount, 2), ',', '') NetAmount,
                                            REPLACE(FORMAT(si.BasicDiscount, 2), ',', '') BasicDisc,
                                            REPLACE(FORMAT(si.AddtlDiscount, 2), ',', '') AddtlDisc
                                    FROM salesinvoice si
                                    INNER JOIN branch br ON br.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                    INNER JOIN customer cu ON cu.ID = si.CustomerID AND br.ID = SPLIT_STR(cu.HOGeneralID, '-', 2)
                                    LEFT JOIN employee em ON em.Code = cu.Code
                                    WHERE si.StatusID = 7 AND br.ID = ".$ID."
                                        AND DATE_FORMAT(si.TxnDate, '%Y%-%m-%d') = '".$lastrun."'");
		return $result;
	}

	function spCollectionHeaderInterfaceFile($database, $ID, $lastrun){
                $result = $database->execute("SELECT
                                        br.Code BranchCode,
                                        DATE_FORMAT(NOW(), '%d/%m/%Y') TxnDate,
                                        DATE_FORMAT(NOW(), '%d/%m/%Y') SentDate,
                                        REPLACE(FORMAT(ofr.TotalAmount, 2), ',', '') TotCollection,
                                        COUNT(ofr.ID) NoOfRecords
                                FROM officialreceipt ofr
                                    INNER JOIN branch br ON br.ID = SPLIT_STR(ofr.HOGeneralID, '-', 2)
                                WHERE ofr.StatusID = 7 AND br.ID = ".$ID."
                                    AND DATE_FORMAT(ofr.TxnDate, '%Y%-%m-%d') = '".$lastrun."'
                                GROUP BY ofr.StatusID");
		return $result;
	}

	function spCollectionTransactionsInterfaceFile($database, $ID, $lastrun){
		 
                $result = $database->execute("SELECT 
												'Q' PaymentType,
												ba.GLAccount AccountCode,
												orca.CheckNumber AccountNo,
												'no' LogicalVal,
												REPLACE(FORMAT(orca.TotalAmount, 2), ',', '') Amount,
												DATE_FORMAT(ofr.TxnDate, '%d%/%m/%y') TxnDate,
												CONCAT(orca.BankName, ' ', orca.BranchName) Remarks
											FROM officialreceiptcheck orca
											INNER JOIN officialreceipt ofr ON ofr.ID = orca.OfficialReceiptID
											AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = SPLIT_STR(orca.HOGeneralID, '-', 2)
											LEFT JOIN branchbank bb ON bb.BranchID = ofr.BranchID AND bb.IsPrimary = 1
											LEFT JOIN bank ba ON ba.ID = bb.BankID
											WHERE ofr.StatusID = 7
											AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = $ID
											AND DATE_FORMAT(ofr.TxnDate, '%Y%-%m-%d') = '$lastrun'
											
											UNION ALL
											SELECT * FROM (
												SELECT  PaymentType, AccountCode, AccountNo, LogicalVal, REPLACE(FORMAT(SUM(Amount),2), ',', '') Amount, TxnDate, Remarks FROM (
													SELECT 'C' PaymentType,
													ba.GLAccount AccountCode,
													'' AccountNo,
													'no' LogicalVal,
													orca.TotalAmount Amount,
													DATE_FORMAT(ofr.TxnDate, '%d%/%m/%y') TxnDate,
													'' Remarks
												FROM officialreceiptcash orca
												INNER JOIN officialreceipt ofr ON ofr.ID = orca.OfficialReceiptID
												AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = SPLIT_STR(orca.HOGeneralID, '-', 2)
												LEFT JOIN branchbank bb ON bb.BranchID = ofr.BranchID AND bb.IsPrimary = 1
												LEFT JOIN bank ba ON ba.ID = bb.BankID
												WHERE ofr.StatusID = 7
												AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = $ID
												AND DATE_FORMAT(ofr.TxnDate, '%Y%-%m-%d') = '$lastrun'
												) a GROUP BY PaymentType
											) b
									
                                    -- UNION ALL
                                    -- SELECT
                                    --     'D' PaymentType,
                                    --     ba.GLAccount AccountCode,
                                    --     orca.DepositSlipNo AccountNo,
                                    --     'no' LogicalVal,
                                    --     REPLACE(FORMAT(orca.TotalAmount, 2), ',', '') Amount,
                                    --     DATE_FORMAT(ofr.TxnDate, '%d%/%m/%y') TxnDate,
                                    --     '' Remarks
                                    -- FROM officialreceiptdeposit orca
                                    --     INNER JOIN officialreceipt ofr ON ofr.ID = orca.OfficialReceiptID
                                    --         AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = SPLIT_STR(orca.HOGeneralID, '-', 2)
                                    --     LEFT JOIN branchbank bb ON bb.BranchID = ofr.BranchID AND bb.IsPrimary = 1
                                    --     LEFT JOIN bank ba ON ba.ID = bb.BankID
                                    -- WHERE ofr.StatusID = 7
                                    --     AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = ".$ID."
                                    --     AND DATE_FORMAT(ofr.TxnDate, '%Y%-%m-%d') = '".$lastrun."'
                                    -- UNION ALL
                                    -- SELECT
                                    --     'S' PaymentType,
                                    --    '' AccountCode,
                                    --    '' AccountNo,
                                    --    'no' LogicalVal,
                                    --    REPLACE(FORMAT(orca.TotalAmount, 2), ',', '') Amount,
                                    --   DATE_FORMAT(ofr.TxnDate, '%d%/%m/%y') TxnDate,
                                    --    '' Remarks
                                    -- FROM officialreceiptcommission orca
                                    --    INNER JOIN officialreceipt ofr ON ofr.ID = orca.OfficialReceiptID
                                    --        AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = SPLIT_STR(orca.HOGeneralID, '-', 2)
                                    --    LEFT JOIN branchbank bb ON bb.BranchID = ofr.BranchID AND bb.IsPrimary = 1
                                    --    LEFT JOIN bank ba ON ba.ID = bb.BankID
                                    -- WHERE ofr.StatusID = 7
                                    --    AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = ".$ID."
                                    --    AND DATE_FORMAT(ofr.TxnDate, '%Y%-%m-%d') = '".$lastrun."'
									");

                return $result;
	}

	function spARHeaderInterfaceFile($database, $ID, $lastrun){
		$result = $database->execute("SELECT DISTINCT
                            br.Code BranchCode,
                            DATE_FORMAT(ofr.TxnDate, '%d%/%m/%Y') TxnDate,
                            'yes' LogicalValue,
                            'UNIX CP' Method,
                            'inhnAivHNllncklb' EPassword,
                            COUNT(ofr.ID) NoOfRecords
                    FROM officialreceipt ofr
                            INNER JOIN `branch` br ON br.ID = SPLIT_STR(ofr.HOGeneralID, '-', 2)
                    WHERE ofr.StatusID = 7 AND br.ID = ".$ID."
                        AND DATE_FORMAT(ofr.TxnDate, '%Y%-%m-%d') = '".$lastrun."'
                    GROUP BY ofr.StatusID;");

                return $result;
	}

	function spARTransactionsInterfaceFile($database, $ID, $lastrun){
		/*$result  = $database->execute("SELECT
                                DATE_FORMAT(ofr.TxnDate, '%d%/%m/%Y') TxnDate,
                                'P' PaymentType,
                                CASE ofrd.ORReferenceType
                                    WHEN 3 THEN 'N'
                                    ELSE 'A'
                                END Classification,
				IF(ofr.ReasonID=0,nre.Name,re.Name) Reason,
                                -- CASE ofr.ORTypeID
                                    -- WHEN 1 THEN IFNULL(re.Name, '')
                                    -- ELSE IFNULL(nre.Name, '')
                                -- END Reason,
                                REPLACE(FORMAT(ofrd.TotalAmount, 2), ',', '') Amount,
                                REPLACE(FORMAT(ofr.TotalAmount, 2), ',', '') xAmount,
                                -- CASE ofr.ORTypeID
                                   -- WHEN 1 THEN IFNULL(ba.GLAccount, '')
                                   -- ELSE IFNULL(nre.DebitGLAccount, '')
                                -- END DebitCode,
                                IF(ofr.ReasonID=0,nre.DebitGLAccount,re.DebitGLAccount) DebitCode,
                                CASE ofr.ORTypeID
                                    WHEN 1 THEN ''
                                    ELSE IFNULL(nre.DebitCostCenter, '')
                                END DebitCCenter,
                                CASE ofr.ORTypeID
                                    WHEN 1 THEN ''
                                    ELSE IFNULL(nre.CreditGLAccount, '')
                                END CreditCode,
                                CASE ofr.ORTypeID
                                    WHEN 1 THEN ''
                                    ELSE IFNULL(nre.CreditCostCenter, '')
                                END CreditCCenter
                    FROM officialreceipt ofr
                                LEFT JOIN officialreceiptdetails ofrd ON ofr.ID = ofrd.OfficialReceiptID
                                    AND SPLIT_STR(ofrd.HOGeneralID, '-', 2) = SPLIT_STR(ofr.HOGeneralID, '-', 2)
                                INNER JOIN bank ba ON ba.ID = ofr.BankID
                                LEFT JOIN reason re ON re.ID = ofr.ReasonID
                                LEFT JOIN reason nre ON nre.ID = ofr.NonTradeReasonID
                    WHERE ofr.StatusID = 7 AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = $ID
                        AND DATE_FORMAT(ofr.TxnDate, '%Y%-%m-%d') = '$lastrun'
                        AND ofr.ID NOT IN (SELECT OfficialReceiptID FROM `officialreceiptcommission`)");*/
		
		$result = $database->execute("SELECT  TxnDate,PaymentType,ORReferenceType, IF(Reason='TRADE','A',Classification)Classification, Reason,Amount,
					xAmount,DebitCode,DebitCCenter,CreditCode,CreditCCenter
					FROM (
						SELECT DATE_FORMAT(ofr.TxnDate, '%d%/%m/%Y') TxnDate, 'P' PaymentType,
						IF(ofrd.ORReferenceType IS NULL,3, ofrd.ORReferenceType) ORReferenceType, 
						IF( ofrd.ORReferenceType = 3 , 'N', IF(ofrd.ORReferenceType IS NULL, 'N','A') ) Classification,
						IF(ofr.ReasonID=0,nre.Name,re.Name) Reason, REPLACE(FORMAT(ofrd.TotalAmount, 2), ',', '') Amount,
						REPLACE(FORMAT(ofr.TotalAmount, 2), ',', '') xAmount, IF(ofr.ReasonID=0,nre.DebitGLAccount,re.DebitGLAccount) DebitCode,
						CASE ofr.ORTypeID WHEN 1 THEN '' ELSE IFNULL(nre.DebitCostCenter, '')END DebitCCenter,
						CASE ofr.ORTypeID WHEN 1 THEN '' ELSE IFNULL(nre.CreditGLAccount, '') END CreditCode,
						CASE ofr.ORTypeID WHEN 1 THEN '' ELSE IFNULL(nre.CreditCostCenter, '') END CreditCCenter
						FROM officialreceipt ofr
						LEFT JOIN officialreceiptdetails ofrd ON ofr.ID = ofrd.OfficialReceiptID AND SPLIT_STR(ofrd.HOGeneralID, '-', 2) = SPLIT_STR(ofr.HOGeneralID, '-', 2)
						INNER JOIN bank ba ON ba.ID = ofr.BankID
						LEFT JOIN reason re ON re.ID = ofr.ReasonID
						LEFT JOIN reason nre ON nre.ID = ofr.NonTradeReasonID
						WHERE ofr.StatusID = 7 AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = $ID
						AND DATE_FORMAT(ofr.TxnDate, '%Y%-%m-%d') = '$lastrun'
						AND ofr.ID NOT IN (SELECT OfficialReceiptID FROM `officialreceiptcommission`)
					) tbl");

		return $result;

	}

	function spDMCMHeaderInterfaceFile($database, $ID, $lastrun){
		$result = $database->execute("SELECT
                                  br.Code BranchCode,
                                  DATE_FORMAT(dm.TxnDate, '%d/%m/%Y') TxnDate,
                                  'yes' LogicalValue,
                                  'UNIX CP' Method,
                                  'inhnAivHNllncklb' EPassword,
                                  COUNT(dm.ID) NoOfRecords
                            FROM dmcm dm
                                 INNER JOIN branch br ON br.ID = SPLIT_STR(dm.HOGeneralID, '-', 2)
                            WHERE dm.StatusID = 7 AND br.ID = ".$ID."
                                AND DATE_FORMAT(dm.TxnDate, '%Y%-%m-%d') = '".$lastrun."'");
		//$query = $result->num_rows;
		return $result;
		//return $query;
	}

	function spDMCMTransactionsInterfaceFile($database, $ID, $lastrun){
		$result = $database->execute("SELECT
                                CASE dm.MemoTypeID
                                    WHEN 1 THEN 'D'
                                    ELSE 'C'
                                END MemoType,
                                re.Code ReasonName,
                                REPLACE(FORMAT(dm.TotalAmount, 2), ',', '') TotalAmount,
				-- CASE dm.MemoTypeID
                                --    WHEN 1 THEN re.DebitGLAccount
                                --    ELSE re.CreditGLAccount
                                -- END AccountCode,
                                -- CASE dm.MemoTypeID
                                   -- WHEN 1 THEN re.DebitCostCenter
                                   -- ELSE re.CreditCostCenter
                                -- END CostCenter,
                                -- cu.Code CustCode,
                                IF(re.CreditGLAccount='',re.DebitGLAccount,re.CreditGLAccount) AccountCode,
				IF(re.DebitCostCenter='', re.DebitGLAccount,re.DebitCostCenter)  CostCenter,
                                IF(LENGTH(cu.Code)<10, cu.Code ,RIGHT(cu.Code,10)) CustCode,
                                cu.Name CustName,
                                '' Campaign
                            FROM dmcm dm
                                INNER JOIN reason re ON re.ID = dm.ReasonID
                                INNER JOIN customer cu ON cu.ID = dm.CustomerID
                                    AND SPLIT_STR(dm.HOGeneralID, '-', 2) = SPLIT_STR(cu.HOGeneralID, '-', 2)
                            WHERE dm.StatusID = 7
                                AND SPLIT_STR(dm.HOGeneralID, '-', 2) = $ID
                                AND DATE_FORMAT(dm.TxnDate, '%Y%-%m-%d') = '$lastrun'
                            UNION ALL
                            SELECT
                                'C' MemoType,
                                IF(ofr.ReasonID=0,res.Code,re.Code) ReasonName,
                                REPLACE(FORMAT(orc.TotalAmount, 2), ',', '') TotalAmount,
                                -- '' AccountCode,
                                -- '' CostCenter,
                                IF(ofr.ReasonID=0, IF(re.DebitGLAccount='', re.CreditGLAccount ,re.DebitGLAccount),
					IF(res.DebitGLAccount='', res.CreditGLAccount ,res.DebitGLAccount)
                                ) AccountCode,
				IF(ofr.ReasonID=0, IF(re.DebitCostCenter='', re.DebitGLAccount ,re.DebitCostCenter),
					IF(res.DebitCostCenter='', res.DebitGLAccount ,res.DebitCostCenter)
                                )  CostCenter,
                                -- cu.Code CustCode,
                                IF(LENGTH(cu.Code)<10, cu.Code ,RIGHT(cu.Code,10)) CustCode,
                                cu.Name CustName,
                                cm.Name Campaign
                            FROM officialreceiptcommission orc
                                INNER JOIN officialreceipt ofr ON ofr.ID = orc.OfficialReceiptID
                                    AND SPLIT_STR(orc.HOGeneralID, '-', 2) = SPLIT_STR(ofr.HOGeneralID, '-', 2)
                                INNER JOIN branch b ON b.ID = SPLIT_STR(orc.HOGeneralID, '-', 2)
                                INNER JOIN customer cu ON cu.ID = ofr.CustomerID
                                    AND SPLIT_STR(cu.HOGeneralID, '-', 2) = b.ID
                                INNER JOIN customercommission cc ON cc.ID = orc.CustomerCommissionID
                                    AND SPLIT_STR(cc.HOGeneralID, '-', 2) = b.ID
                                LEFT JOIN campaignmonth cm ON cm.ID = cc.CampaignMonth
                                LEFT JOIN reason re ON ofr.ReasonID=re.ID
                                LEFT JOIN reason res ON res.ID= ofr.NonTradeReasonID
                            WHERE ofr.StatusID = 7 AND b.ID = $ID
                                AND DATE_FORMAT(ofr.TxnDate, '%Y%-%m-%d') = '$lastrun'");


		return $result;
	}

	function spSalesInvoiceHeaderInterfaceFile($database, $ID, $lastrun){
	//($row_bsh->DealerDisc) * (100/112)


			$result = $database->execute("SELECT BranchCode,
											COUNT(NoOfRecords) NoOfRecords,
											SUM(TotNetAmount) TotNetAmount ,
											SUM(DealerDisc) DealerDisc,
											-- SUM(DealerDisc),
											SUM(AddtlDisc) AddtlDisc,
											SUM(VatAmount) VatAmount,
											SUM(ADPP) ADPP
											FROM (
												SELECT 
												br.Code BranchCode,
												si.ID NoOfRecords,
												SUM(si.NetAmount) TotNetAmount,
												-- SUM(si.BasicDiscount) DealerDisc,
												IF(sd.PMGID = 3, 0, (((SUM(sd.UnitPrice) * SUM(sd.Qty)) * .25) * (100/112))) DealerDisc,
												SUM(si.AddtlDiscount) AddtlDisc,
												SUM(si.VatAmount) VatAmount,
												SUM(si.AddtlDiscountPrev) ADPP
												FROM salesinvoice si
												INNER JOIN salesinvoicedetails sd ON si.ID=sd.SalesInvoiceID AND SPLIT_STR(si.HOGeneralID, '-', 2) = SPLIT_STR(sd.HOGeneralID, '-', 2)
												INNER JOIN branch br ON br.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
												WHERE si.StatusID = 7 AND br.ID = ".$ID."
												AND DATE_FORMAT(si.TxnDate, '%Y%-%m-%d') = '".$lastrun."'
												GROUP BY si.ID 
											) tbl");
			return $result;
	}

	function spSalesInvoiceBSHDetailsInterfaceFile($database, $ID, $lastrun){
	        $result = $database->execute("SELECT
                                        SUM(sid.Qty) TotQty,
                                        SUM(sid.TotalAmount) * (100/112) TotCSP,
										SUM(sid.UnitPrice * sid.Qty) * (100/112) CampaignPrice,
                                        SUM(pp.UnitPrice * sid.Qty) * (100/112)  TotConPrice,
                                        SUM(IFNULL(si.AddtlDiscountPrev, 0)) TotADPP
										FROM salesinvoicedetails sid
                                        INNER JOIN salesinvoice si ON si.ID = sid.SalesInvoiceID
                                            AND SPLIT_STR(si.HOGeneralID, '-', 2) = SPLIT_STR(sid.HOGeneralID, '-', 2)
                                        INNER JOIN branch br ON br.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                        INNER JOIN product pr ON pr.ID = sid.ProductID
                                        INNER JOIN productpricing pp ON pp.ProductID = pr.ID
                                        WHERE si.StatusID = 7 AND br.ID = ".$ID." and sid.IsParentKitComponent = 0
                                        AND DATE_FORMAT(si.TxnDate, '%Y%-%m-%d') = '".$lastrun."'
                                        GROUP BY si.StatusID");
			return $result;
	}

	function spSalesInvoiceDetailsInterfaceFile($database, $ID, $lastrun){
			$result = $database->execute("-- SELECT
                                          --   br.Code BranchCode,
                                          --   -- DATE_FORMAT(si.TxnDate, '%d%/%m/%y')
                                          --   '' TxnDate,
                                          --   sid.Qty,
                                          --   pr.Code ProductCode,
                                          --   -- sid.UnitPrice * sid.Qty CampaignPrice,
                                          --   (sid.UnitPrice * sid.Qty) * (100/112) CampaignPrice,
                                          --   -- IF(sid.PMGID = 3, 0, ((sid.UnitPrice * sid.Qty) * .25)) BasicDisc,
                                          --   IF(sid.PMGID = 3, 0, (((sid.UnitPrice * sid.Qty) * .25) * (100/112))) BasicDisc,
                                          --   IFNULL((sid.AddDiscount) * (100/112),0) NetAmount,
                                          --   (pprc.UnitPrice * sid.Qty) * (100/112) ConsumerPrice,
                                          --   12 Tax,
                                          --   si.VatAmount VatAmount,
                                          --   IFNULL(sid.ADPP,0) AddtlDiscPrev,
                                          --   sid.AddDiscount AddtlDisc
                                          --   FROM salesinvoicedetails sid
                                          --   INNER JOIN salesinvoice si ON si.ID = sid.SalesInvoiceID
                                          --   AND SPLIT_STR(si.HOGeneralID, '-', 2) = SPLIT_STR(sid.HOGeneralID, '-', 2)
                                          --   INNER JOIN branch br ON br.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                          --   INNER JOIN product pr ON pr.ID = sid.ProductID
                                          --   LEFT JOIN productpricing pprc ON pprc.ProductID = pr.ID
                                          --   WHERE si.StatusID = 7 AND br.ID = $ID AND sid.IsParentKitComponent = 0
                                          --   AND DATE(si.TxnDate) = '$lastrun'
										  
											SELECT
											br.Code BranchCode,
											-- DATE_FORMAT(si.TxnDate, '%d%/%m/%y')
											'' TxnDate,
											sid.Qty,
											pr.Code ProductCode,
											-- sid.UnitPrice * sid.Qty CampaignPrice,
											(sid.UnitPrice * sid.Qty) * (100/112) CampaignPrice,
											-- IF(sid.PMGID = 3, 0, ((sid.UnitPrice * sid.Qty) * .25)) BasicDisc,
											IF(sid.PMGID = 3, 0, (((sid.UnitPrice * sid.Qty) * .25) * (100/112))) BasicDisc,
											IFNULL((sid.AddDiscount) * (100/112),0) NetAmount,
											(pprc.UnitPrice * sid.Qty) * (100/112) ConsumerPrice,
											12 Tax,
											si.VatAmount VatAmount,
											IFNULL(sid.ADPP,0) AddtlDiscPrev,
											(sid.AddDiscount * (100/112)) AddtlDisc
											FROM salesinvoicedetails sid
											INNER JOIN salesinvoice si ON si.ID = sid.SalesInvoiceID
											AND SPLIT_STR(si.HOGeneralID, '-', 2) = SPLIT_STR(sid.HOGeneralID, '-', 2)
											INNER JOIN branch br ON br.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
											INNER JOIN product pr ON pr.ID = sid.ProductID
											LEFT JOIN productpricing pprc ON pprc.ProductID = pr.ID
											WHERE si.StatusID = 7 AND br.ID = $ID AND sid.IsParentKitComponent = 0
											AND DATE(si.TxnDate) = '$lastrun'
											
											UNION ALL

											SELECT br.Code BranchCode, '' TxnDate, 
											aovpe.ProductQuantity Qty,
											pr.Code ProductCode,
											(aovpe.ProductEffectivePrice * aovpe.ProductQuantity) * (100/112) CampaignPrice,
											IF(aovpe.ProductPMGID = 3, 0, (((aovpe.ProductEffectivePrice * aovpe.ProductQuantity) * .25) * (100/112))) BasicDisc,
											-- IFNULL((aovpe.AddDiscount) * (100/112),0) NetAmount,
											0 NetAmount,
											(pprc.UnitPrice * aovpe.ProductQuantity) * (100/112) ConsumerPrice,
											12 Tax,
											si.VatAmount VatAmount,
											0 AddtlDiscPrev,
											0 AddtlDisc
											-- IFNULL(sid.ADPP,0) AddtlDiscPrev,
											-- aovpe.AddDiscount AddtlDisc
											FROM applied_overlay_promo_entitlement_details aovpe
											INNER JOIN deliveryreceipt dl ON dl.RefTxnID=aovpe.ReferenceSalesOrderID
											AND SPLIT_STR(aovpe.HOGeneralID, '-', 2) = SPLIT_STR(dl.HOGeneralID, '-', 2)
											INNER JOIN salesinvoice si ON si.RefTxnID=dl.ID
											INNER JOIN branch br ON br.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
											INNER JOIN product pr ON pr.ID = aovpe.ProductID
											LEFT JOIN productpricing pprc ON pprc.ProductID = pr.ID
											WHERE si.StatusID = 7 AND br.ID = $ID -- AND sid.IsParentKitComponent = 0
											AND DATE(si.TxnDate) = '$lastrun'");
			return $result;
	}

	function spRHOInterfaceFile($database, $ID, $lastrun){
			$result = $database->execute("SELECT
                                                io.DocumentNo ReceiptNo,
                                                IFNULL(iod.ShipmentAdviseNo, '') ShipmentNo,
                                                io.ID DocumentNo,
                                                pr.Code ProdCode,
                                                iod.LoadedQty,
                                                iod.ConfirmedQty
                                          FROM inventoryinoutdetails iod
                                                INNER JOIN inventoryinout io ON io.ID = iod.InventoryInOutID
                                                AND SPLIT_STR(iod.HOGeneralID, '-', 2) = SPLIT_STR(io.HOGeneralID, '-', 2)
                                                INNER JOIN movementtype mt ON mt.ID = io.MovementTypeID AND mt.ID = 1 AND mt.IsOut = 0
                                                INNER JOIN product pr ON pr.ID = iod.ProductID
                                          WHERE io.StatusID IN (7, 24)
                                                AND SPLIT_STR(io.HOGeneralID, '-', 2) = ".$ID."
                                                AND DATE_FORMAT(io.TransactionDate, '%Y%-%m-%d') = '".$lastrun."'");

			return $result;
	}

	function spZQTInterfaceFile($database, $ID, $lastrun){
		$result = $database->execute("SELECT
                                            DATE_FORMAT(inv.EnrollmentDate, '%d%/%m/%y') TxnDate,
                                            pr.Code ProdCode,
                                            inv.SOH
                                        FROM inventory inv
                                        INNER JOIN product pr ON pr.ID=inv.ProductID
                                        INNER JOIN `status` s ON s.ID=pr.StatusID
                                        WHERE SPLIT_STR(inv.HOGeneralID,'-',2)= ".$ID."
                                        AND inv.SOH = 0 AND s.Code NOT IN ('XP','XPN')
                                        AND DATE_FORMAT(inv.EnrollmentDate, '%Y%-%m-%d') = '".$lastrun."'
                                        ORDER BY pr.Code");
		return $result;
	}

	function spInventoryTransactionsInterfaceFile($database, $ID, $lastrun){

		$result = $database->execute("
                                SELECT * FROM (
                                SELECT
                                1 OrderBy,
                                ia.ID TxnID,
                                CAST(CONCAT(REPEAT('0', (8-LENGTH(ia.ID))), ia.ID) AS CHAR) MovementOrder,
                                DATE_FORMAT(ia.TransactionDate, '%d%/%m/%y') TxnDate,
                                mt.Type TxnType,
                                wa.Code SourceLocation,
                                '' ReceivingLocation,
                                pr.Code ItemCode,
                                -- iad.CreatedQty Qty,
                                IF(mt.Code= 'IBT' ,-(iad.CreatedQty),iad.CreatedQty) Qty,
                                ut.Code UOM,
                                CASE
                                        WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.DebitGLAccount, '')
                                        ELSE IFNULL(mt.DebitGLAccount, '')
                                END DebitAccount,
                                CASE
                                        WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.DebitCostCenter, '')
                                        ELSE IFNULL(mt.DebitCostCenter, '')
                                END DebitCCenter,
                                CASE
                                        WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.CreditGLAccount, '')
                                        ELSE IFNULL(mt.CreditGLAccount, '')
                                END CreditAccount,
                                CASE
                                        WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.CreditCostCenter, '')
                                        ELSE IFNULL(mt.CreditCostCenter, '')
                                END CreditCCenter,
                                ia.ID Total,
                                mt.Code MovementCode,
                                IFNULL(ia.DocumentNo, 0) ReferenceNo,
                                CASE mt.ID
                                        WHEN 12 THEN IFNULL(mt.DebitGLAccount, '')
                                        ELSE ''
                                END IBTAcct,
                                CASE mt.ID
                                        WHEN 12 THEN IFNULL(mt.DebitCostCenter, '')
                                        ELSE ''
                                END IBTCCenter,
                                CASE mt.ID
                                        WHEN 12 THEN ia.DocumentNo
                                        ELSE ''
                                END IBTProj
                            FROM inventoryadjustmentdetails iad
                                INNER JOIN inventoryadjustment ia ON ia.ID = iad.InventoryAdjustmentID
                                AND SPLIT_STR(ia.HOGeneralID, '-', 2) = SPLIT_STR(iad.HOGeneralID, '-', 2)
                                INNER JOIN movementtype mt ON mt.ID = ia.MovementTypeID
                                INNER JOIN warehouse wa ON wa.ID = ia.WarehouseID
                                INNER JOIN product pr ON pr.ID = iad.ProductID
                                INNER JOIN unittype ut ON ut.ID = iad.UnitTypeID
                                INNER JOIN reason re ON re.ID = iad.ReasonID
                            WHERE ia.StatusID IN (7, 24) AND ia.MovementTypeID != 6
                                AND SPLIT_STR(ia.HOGeneralID, '-', 2) = ".$ID."
                                AND DATE_FORMAT(ia.TransactionDate, '%Y%-%m-%d') = '".$lastrun."'
                            UNION ALL
                            SELECT
                                2 OrderBy,
                                ia.ID TxnID,
                                CAST(CONCAT(REPEAT('0', (8-LENGTH(ia.ID))), ia.ID) AS CHAR) MovementOrder,
                                DATE_FORMAT(ia.TransactionDate, '%d%/%m/%y') TxnDate,
                                mt.Type TxnType,
                                wa.Code SourceLocation,
                                '' ReceivingLocation,
                                pr.Code ItemCode,
                                iad.ConfirmedQty Qty,
                                ut.Code UOM,
                                IFNULL(mt.DebitGLAccount, '') DebitAccount,
                                IFNULL(mt.DebitCostCenter, '') DebitCCenter,
                                IFNULL(mt.CreditGLAccount, '') CreditAccount,
                                IFNULL(mt.CreditCostCenter, '') CreditCCenter,
                                ia.ID Total,
                                mt.Code MovementCode,
                                IFNULL(ia.DocumentNo, 0) ReferenceNo,
                                '' IBTAcct,
                                '' IBTCCenter,
                                '' IBTProj
                            FROM inventoryinoutdetails iad
                                INNER JOIN inventoryinout ia ON ia.ID = iad.InventoryInOutID
                                    AND SPLIT_STR(ia.HOGeneralID, '-', 2) = SPLIT_STR(iad.HOGeneralID, '-', 2)
                                INNER JOIN movementtype mt ON mt.ID = ia.MovementTypeID
                                INNER JOIN warehouse wa ON wa.ID = ia.WarehouseID
                                INNER JOIN product pr ON pr.ID = iad.ProductID
                                INNER JOIN unittype ut ON ut.ID = iad.UnitTypeID
                            WHERE mt.ID != 1 AND ia.StatusID IN (7, 24)
                                AND SPLIT_STR(ia.HOGeneralID, '-', 2) = ".$ID."
                                AND DATE_FORMAT(ia.TransactionDate, '%Y%-%m-%d') = '".$lastrun."'
                            UNION ALL
                            SELECT
                                3 OrderBy,
                                ia.ID TxnID,
                                CAST(CONCAT(REPEAT('0', (8-LENGTH(ia.ID))), ia.ID) AS CHAR) MovementOrder,
                                DATE_FORMAT(ia.TransactionDate, '%d%/%m/%y') TxnDate,
                                mt.Type TxnType,
                                fwa.Code SourceLocation,
                                twa.Code ReceivingLocation,
                                pr.Code ItemCode,
                                iad.CreatedQty Qty,
                                ut.Code UOM,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.DebitGLAccount, '')
                                    ELSE IFNULL(mt.DebitGLAccount, '')
                                END DebitAccount,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.DebitCostCenter, '')
                                    ELSE IFNULL(mt.DebitCostCenter, '')
                                END DebitCCenter,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.CreditGLAccount, '')
                                    ELSE IFNULL(mt.CreditGLAccount, '')
                                END CreditAccount,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.CreditCostCenter, '')
                                    ELSE IFNULL(mt.CreditCostCenter, '')
                                END CreditCCenter,
                                ia.ID Total,
                                mt.Code MovementCode,
                                IFNULL(ia.DocumentNo, 0) ReferenceNo,
                                '' IBTAcct,
                                '' IBTCCenter,
                                '' IBTProj
                            FROM inventorytransferdetails iad
                                INNER JOIN inventorytransfer ia ON ia.ID = iad.InventoryTransferID
                                    AND SPLIT_STR(ia.HOGeneralID, '-', 2) = SPLIT_STR(iad.HOGeneralID, '-', 2)
                                INNER JOIN branch b on b.ID = SPLIT_STR(ia.HOGeneralID, '-', 2)
                                INNER JOIN movementtype mt ON mt.ID = ia.MovementTypeID
                                INNER JOIN warehouse fwa ON fwa.ID = ia.FromWarehouseID
                                INNER JOIN warehouse twa ON twa.ID = ia.ToWarehouseID
                                INNER JOIN product pr ON pr.ID = iad.ProductID
                                INNER JOIN unittype ut ON ut.ID = iad.UnitTypeID
                                INNER JOIN reason re ON re.ID = iad.ReasonID
                            WHERE ia.StatusID IN (7, 24) AND SPLIT_STR(ia.HOGeneralID, '-', 2) = ".$ID."
                                AND DATE_FORMAT(ia.TransactionDate, '%Y%-%m-%d') = '".$lastrun."'
                            UNION ALL
                            SELECT
                                4 OrderBy,
                                ia.ID TxnID,
                                -- ia.ID MovementOrder,
                                CAST(CONCAT(REPEAT('0', (8-LENGTH(ia.ID))), ia.ID) AS CHAR) MovementOrder,
                                DATE_FORMAT(ia.LastModifiedDate, '%d%/%m/%y') TxnDate,
                                mt.Type TxnType,
                                wa.Code SourceLocation,
                                '' ReceivingLocation,
                                pr.Code ItemCode,
                                iad.Qty,
                                ut.Code UOM,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.DebitGLAccount, '')
                                    ELSE ''
                                END DebitAccount,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.DebitCostCenter, '')
                                    ELSE ''
                                END DebitCCenter,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.CreditGLAccount, '')
                                    ELSE ''
                                END CreditAccount,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.CreditCostCenter, '')
                                    ELSE ''
                                END CreditCCenter,
                                ia.ID Total,
                                mt.Code MovementCode,
                                IFNULL(ia.DocumentNo, 0) ReferenceNo,
                                CASE mt.ID
                                    WHEN 12 THEN IFNULL(mt.DebitGLAccount, '')
                                    ELSE ''
                                END IBTAcct,
                                CASE mt.ID
                                    WHEN 12 THEN IFNULL(mt.DebitCostCenter, '')
                                    ELSE ''
                                END IBTCCenter,
                                CASE mt.ID
                                    WHEN 12 THEN ia.DocumentNo
                                    ELSE ''
                                END IBTProj
                            FROM deliveryreceiptdetails iad
                                INNER JOIN deliveryreceipt ia ON ia.ID = iad.DeliveryReceiptID
                                    AND SPLIT_STR(ia.HOGeneralID, '-', 2) = SPLIT_STR(iad.HOGeneralID, '-', 2)
                                INNER JOIN salesorder so ON so.ID = ia.RefTxnID
                                    AND SPLIT_STR(so.HOGeneralID, '-', 2) = SPLIT_STR(ia.HOGeneralID, '-', 2)
                                INNER JOIN salesorderdetails sod ON sod.SalesOrderID = so.ID
                                    AND sod.ProductID = iad.ProductID
                                    AND SPLIT_STR(sod.HOGeneralID, '-', 2) = SPLIT_STR(ia.HOGeneralID, '-', 2)
                                INNER JOIN movementtype mt ON mt.ID = 13
                                INNER JOIN warehouse wa ON wa.ID = ia.WarehouseID
                                INNER JOIN product pr ON pr.ID = iad.ProductID
                                INNER JOIN unittype ut ON ut.ID = iad.UnitTypeID
                            WHERE ia.StatusID = 7 AND sod.UnitPrice = 0
                                    AND SPLIT_STR(ia.HOGeneralID, '-', 2) = ".$ID."
                                    AND DATE_FORMAT(ia.LastModifiedDate, '%Y%-%m-%d') = '".$lastrun."'
                            UNION ALL
                            SELECT
                                5 OrderBy,
                                dr.ID TxnID,
                                -- dr.ID MovementOrder,
                                CAST(CONCAT(REPEAT('0', (8-LENGTH(dr.ID))), dr.ID) AS CHAR) MovementOrder,
                                DATE_FORMAT(sl.TxnDate, '%d%/%m/%y') TxnDate,
                                mt.Type TxnType,
                                wa.Code SourceLocation,
                                '' ReceivingLocation,
                                pr.Code ItemCode,
                                -(sl.QtyIn),
                                ut.Code UOM,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.DebitGLAccount, '')
                                    ELSE ''
                                END DebitAccount,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.DebitCostCenter, '')
                                    ELSE ''
                                END DebitCCenter,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.CreditGLAccount, '')
                                    ELSE ''
                                END CreditAccount,
                                CASE
                                    WHEN mt.ID IN (1, 2, 3, 4, 13, 17) THEN IFNULL(mt.CreditCostCenter, '')
                                    ELSE ''
                                END CreditCCenter,
                                dr.ID Total,
                                mt.Code MovementCode,
                                IFNULL(dr.DocumentNo, 0) ReferenceNo,
                                CASE mt.ID
                                    WHEN 12 THEN IFNULL(mt.DebitGLAccount, '')
                                    ELSE ''
                                END IBTAcct,
                                CASE mt.ID
                                    WHEN 12 THEN IFNULL(mt.DebitCostCenter, '')
                                    ELSE ''
                                END IBTCCenter,
                                CASE mt.ID
                                    WHEN 12 THEN dr.DocumentNo
                                    ELSE ''
                                END IBTProj
                            FROM stocklog sl
                                INNER JOIN salesinvoice si ON si.ID = sl.RefTxnID
                                INNER JOIN branch b on b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
                                INNER JOIN deliveryreceipt dr ON dr.ID = si.RefTxnID
                                    AND SPLIT_STR(dr.HOGeneralID, '-', 2) = b.ID
                                INNER JOIN deliveryreceiptdetails drd ON drd.DeliveryReceiptID = dr.ID
                                    AND drd.ProductID = sl.ProductID
                                    AND b.ID = SPLIT_STR(drd.HOGeneralID, '-', 2)
                                INNER JOIN movementtype mt ON mt.ID = 13
                                INNER JOIN warehouse wa ON wa.ID = dr.WarehouseID
                                INNER JOIN product pr ON pr.ID = sl.ProductID
                                INNER JOIN unittype ut ON ut.ID = drd.UnitTypeID
                            WHERE sl.MovementTypeID = 14 AND b.ID = $ID
                            AND DATE_FORMAT(sl.TxnDate, '%Y%-%m-%d') = '".$lastrun."'
                            -- ORDER BY OrderBy, MovementOrder
                                                UNION ALL
                                                SELECT
                                                        6 OrderBy,
                                                        a.ID TxnID,
                               a.DocumentNo MovementOrder,
                               -- CAST(CONCAT(REPEAT('0', (8-LENGTH(a.ID))), a.ID) AS CHAR) MovementOrder,

                                DATE_FORMAT(a.`Date`, '%d%/%m/%y') TxnDate,
                                                        rtrim(ltrim(trim(mt.Type))) TxnType,
                                                        wa.Code SourceLocation,
                                                        '' ReceivingLocation,
                                                        pr.Code ItemCode,
                                                        a.Qty,
                                                        ut.Code UOM,
                                                        -- a.UOM,
                                                        mt.DebitGLAccount DebitAccount,
                                                        mt.DebitCostCenter DebitCCenter,
                                                        mt.CreditGLAccount CreditAccount,
                                                        mt.CreditCostCenter CreditCCenter,
                                                        a.ID Total,
                                                        a.MovementTypeCode MovementCode,
                                                        IFNULL( a.DocumentNo, 0) ReferenceNo,
                                                        CASE mt.ID
                                    WHEN 12 THEN IFNULL(mt.DebitGLAccount, '')
                                    ELSE ''
                                END IBTAcct,
                                CASE mt.ID
                                    WHEN 12 THEN IFNULL(mt.DebitCostCenter, '')
                                    ELSE ''
                                END IBTCCenter,
                                CASE mt.ID
                                    WHEN 12 THEN a.DocumentNo
                                    ELSE ''
                                END IBTProj
                                    from isa a
                                    LEFT JOIN movementtype mt on mt.Code='ISA'
                                    INNER JOIN product pr on a.ProductID=pr.ID
                                    LEFT JOIN warehouse wa on wa.ID = 1
                                    LEFT JOIN unittype ut on ut.ID = a.UOM
                                    WHERE a.BranchID = $ID
                                    AND DATE_FORMAT(a.`Date`, '%Y%-%m-%d') = '".$lastrun."'
                            ) a where MovementCode <> 'ISO'");
		return $result;
	}

	function spVDSInterfaceFile($database, $ID, $lastrun){
			$result = $database->execute("SELECT
                        CASE orca.DepositType
                            WHEN 1 THEN 'C'
                            ELSE 'Q'
                        END PaymentType,
                        REPLACE(FORMAT(orca.TotalAmount, 2), ',', '') Amount,
                        DATE_FORMAT(ofr.TxnDate, '%d%/%m/%y') TxnDate,
                        DATE_FORMAT(orca.DepositDate, '%d%/%m/%y') DepositDate,
                        orca.DepositSlipNo DepositSlipNo,
                        ba.Name Bank
                    FROM officialreceiptdeposit orca
                        INNER JOIN officialreceipt ofr ON ofr.ID = orca.OfficialReceiptID
                        AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = SPLIT_STR(orca.HOGeneralID, '-', 2)
                        INNER JOIN branch b ON b.ID = SPLIT_STR(ofr.HOGeneralID, '-', 2)
                        LEFT JOIN bank ba ON ba.ID = orca.BankID
                    WHERE ofr.StatusID = 7 AND SPLIT_STR(ofr.HOGeneralID, '-', 2) = ".$ID."
                        AND DATE_FORMAT(ofr.TxnDate, '%Y%-%m-%d') >= '".$lastrun."'
                    UNION ALL
                    SELECT
                        'D' PaymentType,
                        REPLACE(FORMAT((bds.TotalCashAmount + bds.TotalCheckAmount), 2), ',', '') Amount,
                        DATE_FORMAT(bds.CollectionDate, '%d%/%m/%y') TxnDate,
                        DATE_FORMAT(bds.DepositDate, '%d%/%m/%y') DepositDate,
                        bds.CheckNumbers DepositSlipNo,
                        b.Name Bank
                    FROM bankdepositslip bds
                        INNER JOIN bank b ON b.ID = bds.BankID
                    WHERE StatusID = 7 AND bds.BranchID = ".$ID."
                        AND DATE_FORMAT(bds.EnrollmentDate, '%Y%-%m-%d') = '".$lastrun."'");
		return $result;
	}

	function spRFDInterfaceFile($database, $ID, $lastrun){
		$concat = '"';
		$result = $database->execute("SELECT
                        DATE_FORMAT(NOW(), '%d%/%m/%Y') TxnDate,
                        br.Code BranchCode,
                        pr.Code ProductCode,
                        pr.Name ProductName,
                        iad.CreatedQty
                    FROM `inventoryadjustmentdetails` iad
                        INNER JOIN `inventoryadjustment` ia ON ia.ID = iad.InventoryAdjustmentID
                            AND SPLIT_STR(ia.HOGeneralID, '-', 2) = SPLIT_STR(iad.HOGeneralID, '-', 2)
                        INNER JOIN `branch` br ON br.ID = SPLIT_STR(iad.HOGeneralID, '-', 2)
                        INNER JOIN `product` pr ON pr.ID = iad.ProductID
                    WHERE ia.MovementTypeID = 10 AND ia.StatusID = 7 AND br.ID = ".$ID."
                        AND DATE_FORMAT(ia.TransactionDate, '%Y%-%m-%d') = '".$lastrun."'");
		return $result;
	}

	function spLOSInterfaceFile($database, $ID, $lastrun){
		$result = $database->execute("SELECT
                        br.Code BranchCode,
                        wa.Code Location,
                        pr.Code ProductCode,
                        iad.CreatedQty QtyCounted,
                        iv.TotSOH,
                        IFNULL(iad.PrevBalance, 0) PrevQtyCounted
                    FROM inventorycountdetails iad
                        INNER JOIN inventorycount ia ON ia.ID = iad.InventoryCountID
                            AND SPLIT_STR(ia.HOGeneralID, '-', 2) = SPLIT_STR(iad.HOGeneralID, '-', 2)
                        INNER JOIN branch br ON br.ID = SPLIT_STR(iad.HOGeneralID, '-', 2)
                        INNER JOIN warehouse wa ON wa.ID = ia.WarehouseID
                        INNER JOIN product pr ON pr.ID = iad.ProductID
                        INNER JOIN( SELECT ProductID, SUM(SOH) TotSOH FROM inventory
                            WHERE SPLIT_STR(HOGeneralID, '-', 2) = $ID
                            GROUP BY ProductID ) iv
                        ON iv.ProductID = pr.ID
                    WHERE ia.StatusID = 7 AND br.ID = ".$ID."
                        AND DATE_FORMAT(ia.TransactionDate, '%Y%-%m-%d') = '".$lastrun."'");
		return $result;
	}

	function spInsertOutputInterfaceLogs($database, $branchid, $transactiondate, $filename, $filetypeid, $totalrecords)
	{
		if($totalrecords == ""){
			$records = 0;
		}else{
			$records = $totalrecords;
		}

        	$result = $database->execute("INSERT INTO outputinterfacelogs
		         		                	 (BranchID, TransactionDate, DateGenerated, FileName, FileTypeID, TotalNoRecords)
										  VALUES
		         		                	  (".$branchid.", '".$transactiondate."', NOW(),'".$filename."', ".$filetypeid.", ".$records.")");
			return $result;

	}
}
$tpiInterface = new ClassInterface();
?>