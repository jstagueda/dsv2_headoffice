<?php 

function inventorytransactionreport($page, $totalrow, $isTotal, $txtStartDates, $txtEndDates, $cboWarehouse, $txtSearch, $branch){
	global $database;
	
	$warehouse = ($cboWarehouse > 0) ? " AND mt.ID = $cboWarehouse" : "";
	
	if($cboWarehouse > 0){
		if($cboWarehouse == 7){
		
			$RDGIDG = "UNION ALL
					SELECT
						mt.IsOut,
						inv.WarehouseID,
						w.Name WarehouseName,
						sl.ProductID,
						p.Code,
						p.Name Product,
						ROUND(pp.UnitPrice, 2) UnitPrice,
						sl.InventoryID,
						mt.Name MovementType,
						'IDG' MovementCode,
						DATE_FORMAT(it.TransactionDate, '%m/%d/%Y') TxnDate,
						sl.RefTxnID,
						sl.RefNo,
						SUM(sl.QtyIn) QtyIn,
						SUM(sl.QtyOut) QtyOut,
						(SUM(sl.QtyIn) - SUM(sl.QtyOut)) EndingBalance,
						CONCAT('TR', LPAD(it.ID, 8, 0)) RefTxnNo,
						b.Code IssuingBranch,
						b.Code ReceivingBranch,
						(SELECT w3.Code FROM warehouse w3 WHERE w3.ID = w.ID ) Location1,
						(SELECT w4.Code FROM warehouse w4 WHERE w4.ID = w1.ID ) Location2,
						IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) Qty
					FROM stocklog sl 
					INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
					INNER JOIN inventory inv ON inv.ID = sl.InventoryID
						AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
					INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
						AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
					INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
					INNER JOIN branch b ON b.ID = it.BranchID
					INNER JOIN product p ON p.ID = sl.ProductID
					INNER JOIN productpricing pp ON pp.ProductID = p.ID
					INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
					INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
					WHERE mt.ID IN (7)
					AND bx.ID = $branch
					AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
					AND (p.Code LIKE '$txtSearch%'
						OR p.Name LIKE '$txtSearch%'
						OR CONCAT('TR', LPAD(it.ID, 8, 0)) LIKE '$txtSearch%')
					GROUP BY p.ID, it.ID, mt.ID";
					
		}else if($cboWarehouse == 8){
		
			$RDGIDG = "UNION ALL
					SELECT
						mt.IsOut,
						inv.WarehouseID,
						w.Name WarehouseName,
						sl.ProductID,
						p.Code,
						p.Name Product,
						ROUND(pp.UnitPrice, 2) UnitPrice,
						sl.InventoryID,
						mt.Name MovementType,
						'RDG' MovementCode,
						DATE_FORMAT(it.TransactionDate, '%m/%d/%Y') TxnDate,
						sl.RefTxnID,
						sl.RefNo,
						SUM(sl.QtyOut) QtyIn,
						SUM(sl.QtyIn) QtyOut,
						(SUM(sl.QtyIn) - SUM(sl.QtyOut)) EndingBalance,
						CONCAT('TR', LPAD(it.ID, 8, 0)) RefTxnNo,
						b.Code IssuingBranch,
						b.Code ReceivingBranch,
						(SELECT w4.Code FROM warehouse w4 WHERE w4.ID = w1.ID ) Location1,
						(SELECT w3.Code FROM warehouse w3 WHERE w3.ID = w.ID ) Location2,
						IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) Qty
					FROM stocklog sl 
					INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
					INNER JOIN inventory inv ON inv.ID = sl.InventoryID
						AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
					INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
						AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
					INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
					INNER JOIN branch b ON b.ID = it.BranchID
					INNER JOIN product p ON p.ID = sl.ProductID
					INNER JOIN productpricing pp ON pp.ProductID = p.ID
					INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
					INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
					WHERE mt.ID IN (7)
					AND bx.ID = $branch
					AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
					AND (p.Code LIKE '$txtSearch%'
						OR p.Name LIKE '$txtSearch%'
						OR CONCAT('TR', LPAD(it.ID, 8, 0)) LIKE '$txtSearch%')
					GROUP BY p.ID, it.ID, mt.ID";
					
		}
	}else{
		
		$RDGIDG = "UNION ALL
					SELECT
						mt.IsOut,
						inv.WarehouseID,
						w.Name WarehouseName,
						sl.ProductID,
						p.Code,
						p.Name Product,
						ROUND(pp.UnitPrice, 2) UnitPrice,
						sl.InventoryID,
						mt.Name MovementType,
						'IDG' MovementCode,
						DATE_FORMAT(it.TransactionDate, '%m/%d/%Y') TxnDate,
						sl.RefTxnID,
						sl.RefNo,
						SUM(sl.QtyIn) QtyIn,
						SUM(sl.QtyOut) QtyOut,
						(SUM(sl.QtyIn) - SUM(sl.QtyOut)) EndingBalance,
						CONCAT('TR', LPAD(it.ID, 8, 0)) RefTxnNo,
						b.Code IssuingBranch,
						b.Code ReceivingBranch,
						(SELECT w3.Code FROM warehouse w3 WHERE w3.ID = w.ID ) Location1,
						(SELECT w4.Code FROM warehouse w4 WHERE w4.ID = w1.ID ) Location2,
						IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) Qty
					FROM stocklog sl 
					INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
					INNER JOIN inventory inv ON inv.ID = sl.InventoryID
						AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
					INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
						AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
					INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
					INNER JOIN branch b ON b.ID = it.BranchID
					INNER JOIN product p ON p.ID = sl.ProductID
					INNER JOIN productpricing pp ON pp.ProductID = p.ID
					INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
					INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
					WHERE mt.ID IN (7)
					AND bx.ID = $branch
					AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
					AND (p.Code LIKE '$txtSearch%'
						OR p.Name LIKE '$txtSearch%'
						OR CONCAT('TR', LPAD(it.ID, 8, 0)) LIKE '$txtSearch%')
					GROUP BY p.ID, it.ID, mt.ID
					
					UNION ALL
					
					SELECT
						mt.IsOut,
						inv.WarehouseID,
						w.Name WarehouseName,
						sl.ProductID,
						p.Code,
						p.Name Product,
						ROUND(pp.UnitPrice, 2) UnitPrice,
						sl.InventoryID,
						mt.Name MovementType,
						'RDG' MovementCode,
						DATE_FORMAT(it.TransactionDate, '%m/%d/%Y') TxnDate,
						sl.RefTxnID,
						sl.RefNo,
						SUM(sl.QtyOut) QtyIn,
						SUM(sl.QtyIn) QtyOut,
						(SUM(sl.QtyIn) - SUM(sl.QtyOut)) EndingBalance,
						CONCAT('TR', LPAD(it.ID, 8, 0)) RefTxnNo,
						b.Code IssuingBranch,
						b.Code ReceivingBranch,
						(SELECT w4.Code FROM warehouse w4 WHERE w4.ID = w1.ID ) Location1,
						(SELECT w3.Code FROM warehouse w3 WHERE w3.ID = w.ID ) Location2,
						IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) Qty
					FROM stocklog sl
					INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
					INNER JOIN inventory inv ON inv.ID = sl.InventoryID
						AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
					INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
						AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
					INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
					INNER JOIN branch b ON b.ID = it.BranchID
					INNER JOIN product p ON p.ID = sl.ProductID
					INNER JOIN productpricing pp ON pp.ProductID = p.ID
					INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
					INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
					WHERE mt.ID IN (7)
					AND bx.ID = $branch
					AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
					AND (p.Code LIKE '$txtSearch%'
						OR p.Name LIKE '$txtSearch%'
						OR CONCAT('TR', LPAD(it.ID, 8, 0)) LIKE '$txtSearch%')
					GROUP BY p.ID, it.ID, mt.ID";
		
	}
	
	$start = ($page > 1)?($page - 1) * $totalrow : 0;
	$limit = ($isTotal)?"":" LIMIT $start, $totalrow";
	
	$query = $database->execute("SELECT * FROM
							(SELECT
								mt.IsOut,
								inv.WarehouseID,
								wh.Name WarehouseName,
								sl.ProductID,
								p.Code,
								p.Name Product,
								ROUND(pp.UnitPrice, 2) UnitPrice,
								sl.InventoryID,
								mt.Name MovementType,
								mt.Code MovementCode,
								DATE_FORMAT(iio.TransactionDate, '%m/%d/%Y') TxnDate,
								sl.RefTxnID,
								sl.RefNo,
								SUM(sl.QtyIn) QtyIn,
								SUM(sl.QtyOut) QtyOut,
								(SUM(sl.QtyIn) - SUM(sl.QtyOut)) EndingBalance,
								CONCAT('II', LPAD(iio.ID, 8, 0)) RefTxnNo,
								b.Code IssuingBranch,
								bto.Code ReceivingBranch,
								(SELECT w1.Code FROM `warehouse` w1 WHERE w1.`ID`= 1 ) Location1,
								(SELECT w2.Code FROM `warehouse` w2 WHERE w2.`ID`= 1 ) Location2,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) Qty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							INNER JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							INNER JOIN inventoryinout iio ON iio.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), iio.HOGeneralID) > 0
							INNER JOIN movementtype mt ON mt.ID = iio.MovementTypeID
							INNER JOIN branch b ON b.ID = iio.BranchID
							INNER JOIN branch bto ON bto.ID = iio.ToBranchID
							INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
							INNER JOIN product p ON p.ID = sl.ProductID
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							WHERE mt.ID IN (1, 3)
							AND bx.ID = $branch
							AND DATE(iio.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('II', LPAD(iio.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, iio.ID, mt.ID

							UNION ALL

							SELECT
								mt.IsOut,
								inv.WarehouseID,
								wh.Name WarehouseName,
								sl.ProductID,
								p.Code,
								p.Name Product,
								ROUND(pp.UnitPrice, 2) UnitPrice,
								sl.InventoryID,
								mt.Name MovementType,
								mt.Code MovementCode,
								DATE_FORMAT(iio.TransactionDate, '%m/%d/%Y') TxnDate,
								sl.RefTxnID,
								sl.RefNo,
								SUM(sl.QtyIn) QtyIn,
								SUM(sl.QtyOut) QtyOut,
								(SUM(sl.QtyIn) - SUM(sl.QtyOut)) EndingBalance,
								CONCAT('IO', LPAD(iio.ID, 8, 0)) RefTxnNo,
								b.Code IssuingBranch,
								bto.Code ReceivingBranch,
								(SELECT w1.Code FROM `warehouse` w1 WHERE w1.`ID`= 1 ) Location1,
								(SELECT w2.Code FROM `warehouse` w2 WHERE w2.`ID`= 1 ) Location2,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) Qty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							INNER JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							INNER JOIN inventoryinout iio ON iio.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), iio.HOGeneralID) > 0
							INNER JOIN movementtype mt ON mt.ID = iio.MovementTypeID
							INNER JOIN branch b ON b.ID = iio.BranchID
							INNER JOIN branch bto ON bto.ID = iio.ToBranchID
							INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
							INNER JOIN product p ON p.ID = sl.ProductID
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							WHERE mt.ID IN (2, 4)
							AND bx.ID = $branch
							AND DATE(iio.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('IO', LPAD(iio.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, iio.ID, mt.ID

							UNION ALL

							SELECT
								mt.IsOut,
								inv.WarehouseID,
								wh.Name WarehouseName,
								sl.ProductID,
								p.Code,
								p.Name Product,
								ROUND(pp.UnitPrice, 2) UnitPrice,
								sl.InventoryID,
								mt.Name MovementType,
								mt.Code MovementCode,
								DATE_FORMAT(ia.TransactionDate, '%m/%d/%Y') TxnDate,
								sl.RefTxnID,
								sl.RefNo,
								SUM(sl.QtyIn) QtyIn,
								SUM(sl.QtyOut) QtyOut,
								(SUM(sl.QtyIn) - SUM(sl.QtyOut)) EndingBalance,
								CONCAT('AD', LPAD(ia.ID, 8, 0)) RefTxnNo,
								b.Code IssuingBranch,
								'--' ReceivingBranch,
								(SELECT w1.Code FROM `warehouse` w1 WHERE w1.`ID`= 1 ) Location1,
								'--' Location2,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) Qty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							INNER JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							INNER JOIN inventoryadjustment ia ON ia.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), ia.HOGeneralID) > 0
							INNER JOIN movementtype mt ON mt.ID = ia.MovementTypeID
							INNER JOIN branch b ON b.ID = ia.BranchID
							INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
							INNER JOIN product p ON p.ID = sl.ProductID
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							WHERE mt.ID IN (5,6,9,10,11,12)
							AND bx.ID = $branch
							AND DATE(ia.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('AD', LPAD(ia.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, ia.ID, mt.ID

							UNION ALL 

							SELECT
								mt.IsOut,
								inv.WarehouseID,
								wh.Name WarehouseName,
								sl.ProductID,
								p.Code,
								p.Name Product,
								ROUND(pp.UnitPrice, 2) UnitPrice,
								sl.InventoryID,
								mt.Name MovementType,
								mt.Code MovementCode,
								DATE_FORMAT(ic.TransactionDate, '%m/%d/%Y') TxnDate,
								sl.RefTxnID,
								sl.RefNo,
								SUM(sl.QtyIn) QtyIn,
								SUM(sl.QtyOut) QtyOut,
								(SUM(sl.QtyIn) - SUM(sl.QtyOut)) EndingBalance,
								CONCAT('IC', LPAD(ic.ID, 8, 0)) RefTxnNo,
								b.Code IssuingBranch,
								'--' ReceivingBranch,
								l.Code Location1,
								'--' Location2,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) Qty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							INNER JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							INNER JOIN inventorycount ic ON ic.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), ic.HOGeneralID) > 0
							INNER JOIN movementtype mt ON mt.ID = ic.MovementTypeID
							INNER JOIN branch b ON b.ID = ic.BranchID
							INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
							INNER JOIN product p ON p.ID = sl.ProductID
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							INNER JOIN inventorycountdetails icDetails ON icDetails.InventoryCountID = icDetails.ID
								AND LOCATE(CONCAT('-', bx.ID), icDetails.HOGeneralID) > 0
							INNER JOIN location l ON l.ID = icDetails.LocationID
							WHERE mt.ID IN (6)
							AND bx.ID = $branch
							AND DATE(ic.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('IC', LPAD(ic.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, ic.ID, mt.ID

							$RDGIDG

							UNION ALL 

							SELECT
								mt.IsOut,
								inv.WarehouseID,
								wh.Name WarehouseName,
								sl.ProductID,
								p.Code,
								p.Name Product,
								ROUND(pp.UnitPrice, 2) UnitPrice,
								sl.InventoryID,
								mt.Name MovementType,
								mt.Code MovementCode,
								DATE_FORMAT(si.TxnDate, '%m/%d/%Y') TxnDate,
								sl.RefTxnID,
								sl.RefNo,
								SUM(sl.QtyIn) QtyIn,
								SUM(sl.QtyOut) QtyOut,
								(SUM(sl.QtyIn) - SUM(sl.QtyOut)) EndingBalance,
								CONCAT('SI', LPAD(si.ID, 8, 0)) RefTxnNo,
								b.Code IssuingBranch,
								'--' ReceivingBranch,
								(SELECT w3.Code FROM warehouse w3 WHERE w3.ID = wh.ID ) Location1,
								'--' Location2,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn) *-1) Qty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							INNER JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							INNER JOIN salesinvoice si ON si.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), si.HOGeneralID) > 0
							INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
							INNER JOIN branch b ON b.ID = si.BranchID
							INNER JOIN product p ON p.ID = sl.ProductID
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
							WHERE mt.ID IN (13, 14, 17)
							AND bx.ID = $branch
							AND DATE(si.TxnDate) BETWEEN '$txtStartDates' and '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('SI', LPAD(si.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, si.ID, mt.ID
							
							UNION ALL
							
							SELECT
								mt.IsOut,
								inv.WarehouseID,
								wh.Name WarehouseName,
								sl.ProductID,
								p.Code,
								p.Name Product,
								ROUND(pp.UnitPrice, 2) UnitPrice,
								sl.InventoryID,
								mt.Name MovementType,
								mt.Code MovementCode,
								DATE_FORMAT(sl.TxnDate, '%m/%d/%Y') TxnDate,
								sl.RefTxnID,
								sl.RefNo,
								SUM(sl.QtyIn) QtyIn,
								SUM(sl.QtyOut) QtyOut,
								(SUM(sl.QtyIn) - SUM(sl.QtyOut)) EndingBalance,
								CONCAT('PEC', LPAD(pc.ID, 8, 0)) RefTxnNo,
								(SELECT `Code` FROM branch WHERE ID = $branch) IssuingBranch,
								'--' ReceivingBranch,
								'--' Location1,
								'--' Location2,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) Qty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							LEFT JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							LEFT JOIN productexchange pc ON pc.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), pc.HOGeneralID) > 0
							LEFT JOIN movementtype mt ON mt.ID = sl.MovementTypeID
							LEFT JOIN warehouse wh ON wh.ID = inv.WarehouseID
							LEFT JOIN product p ON p.ID = sl.ProductID
							LEFT JOIN productpricing pp ON pp.ProductID = p.ID
							WHERE mt.ID IN (18, 19)
							AND bx.ID = $branch
							AND DATE(sl.TxnDate) BETWEEN '$txtStartDates' AND '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('PEC', LPAD(pc.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, pc.ID, mt.ID) atbl
							
							ORDER BY TxnDate, RefTxnNo, Product ASC
							$limit");
	return $query;
}

function inventorytransactionTotalPerMovement($txtStartDates, $txtEndDates, $cboWarehouse, $MovementName, $ReferenceNo, $branch){
	global $database;
	
	$warehouse = ($cboWarehouse > 0) ? " AND mt.ID = $cboWarehouse" : "";
	
	$RDGIDG = "";
	if($cboWarehouse > 0){
		if($cboWarehouse == 7){
			$RDGIDG = "UNION ALL 
						SELECT
							SUM(sl.QtyIn) QtyInTotal,
							SUM(sl.QtyOut) QtyOutTotal,
							(pp.UnitPrice * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn))) TotalAmount,
							IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
						FROM stocklog sl 
						INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
						INNER JOIN inventory inv ON inv.ID = sl.InventoryID
							AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
						INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
							AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
						INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
						INNER JOIN branch b ON b.ID = it.BranchID
						INNER JOIN product p ON p.ID = sl.ProductID
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
						INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
						WHERE mt.ID IN (7)
						AND bx.ID = $branch
						AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
						AND CONCAT('TR', LPAD(it.ID, 8, 0)) = '$ReferenceNo'
						GROUP BY p.ID";
		}else if($cboWarehouse == 8){
			$RDGIDG = "UNION ALL 
						SELECT
							SUM(sl.QtyOut) QtyInTotal,
							SUM(sl.QtyIn) QtyOutTotal,
							(pp.UnitPrice * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn))) TotalAmount,
							IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
						FROM stocklog sl 
						INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
						INNER JOIN inventory inv ON inv.ID = sl.InventoryID
							AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
						INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
							AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
						INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
						INNER JOIN branch b ON b.ID = it.BranchID
						INNER JOIN product p ON p.ID = sl.ProductID
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
						INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
						WHERE mt.ID IN (7)
						AND bx.ID = $branch
						AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
						AND CONCAT('TR', LPAD(it.ID, 8, 0)) = '$ReferenceNo'
						GROUP BY p.ID";
		}
	}else{
		$RDGIDG = "UNION ALL 
						SELECT
							SUM(sl.QtyIn) QtyInTotal,
							SUM(sl.QtyOut) QtyOutTotal,
							(pp.UnitPrice * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn))) TotalAmount,
							IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
						FROM stocklog sl 
						INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
						INNER JOIN inventory inv ON inv.ID = sl.InventoryID
							AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
						INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
							AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
						INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
						INNER JOIN branch b ON b.ID = it.BranchID
						INNER JOIN product p ON p.ID = sl.ProductID
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
						INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
						WHERE mt.ID IN (7)
						AND bx.ID = $branch
						AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
						AND CONCAT('TR', LPAD(it.ID, 8, 0)) = '$ReferenceNo'
						GROUP BY p.ID
						
						UNION ALL 
						
						SELECT
							SUM(sl.QtyOut) QtyInTotal,
							SUM(sl.QtyIn) QtyOutTotal,
							(pp.UnitPrice * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn))) TotalAmount,
							IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
						FROM stocklog sl 
						INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
						INNER JOIN inventory inv ON inv.ID = sl.InventoryID
							AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
						INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
							AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
						INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
						INNER JOIN branch b ON b.ID = it.BranchID
						INNER JOIN product p ON p.ID = sl.ProductID
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
						INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
						WHERE mt.ID IN (7)
						AND bx.ID = $branch
						AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
						AND CONCAT('TR', LPAD(it.ID, 8, 0)) = '$ReferenceNo'
						GROUP BY p.ID";
	}
	
	$query = $database->execute("SELECT
								SUM(TotalAmount) TotalAmount,
								SUM(TotalQty) TotalQty,
								SUM(QtyInTotal) QtyInTotal,
								SUM(QtyOutTotal) QtyOutTotal
								FROM (SELECT
									SUM(sl.QtyIn) QtyInTotal,
									SUM(sl.QtyOut) QtyOutTotal,
									(pp.UnitPrice * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn))) TotalAmount,
									IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
								FROM stocklog sl
								INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
								INNER JOIN inventory inv ON inv.ID = sl.InventoryID
									AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
								INNER JOIN inventoryinout iio ON iio.ID = sl.RefTxnID
									AND LOCATE(CONCAT('-', bx.ID), iio.HOGeneralID) > 0
								INNER JOIN movementtype mt ON mt.ID = iio.MovementTypeID
								INNER JOIN branch b ON b.ID = iio.BranchID
								INNER JOIN branch bto ON bto.ID = iio.ToBranchID
								INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
								INNER JOIN product p ON p.ID = sl.ProductID
								INNER JOIN productpricing pp ON pp.ProductID = p.ID
								WHERE mt.ID IN (1, 3)
								AND bx.ID = $branch
								AND DATE(iio.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
								$warehouse
								AND CONCAT('II', LPAD(iio.ID, 8, 0)) = '$ReferenceNo'
								GROUP BY p.ID

								UNION ALL

								SELECT
									SUM(sl.QtyIn) QtyInTotal,
									SUM(sl.QtyOut) QtyOutTotal,
									(pp.UnitPrice * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn))) TotalAmount,
									IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
								FROM stocklog sl 
								INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
								INNER JOIN inventory inv ON inv.ID = sl.InventoryID
									AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
								INNER JOIN inventoryinout iio ON iio.ID = sl.RefTxnID
									AND LOCATE(CONCAT('-', bx.ID), iio.HOGeneralID) > 0
								INNER JOIN movementtype mt ON mt.ID = iio.MovementTypeID
								INNER JOIN branch b ON b.ID = iio.BranchID
								INNER JOIN branch bto ON bto.ID = iio.ToBranchID
								INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
								INNER JOIN product p ON p.ID = sl.ProductID
								INNER JOIN productpricing pp ON pp.ProductID = p.ID
								WHERE mt.ID IN (2, 4)
								AND bx.ID = $branch
								AND DATE(iio.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
								$warehouse
								AND CONCAT('IO', LPAD(iio.ID, 8, 0)) = '$ReferenceNo'
								GROUP BY p.ID

								UNION ALL

								SELECT
									SUM(sl.QtyIn) QtyInTotal,
									SUM(sl.QtyOut) QtyOutTotal,
									(pp.UnitPrice * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn))) TotalAmount,
									IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
								FROM stocklog sl 
								INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
								INNER JOIN inventory inv ON inv.ID = sl.InventoryID
									AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
								INNER JOIN inventoryadjustment ia ON ia.ID = sl.RefTxnID
									AND LOCATE(CONCAT('-', bx.ID), ia.HOGeneralID) > 0
								INNER JOIN movementtype mt ON mt.ID = ia.MovementTypeID
								INNER JOIN branch b ON b.ID = ia.BranchID
								INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
								INNER JOIN product p ON p.ID = sl.ProductID
								INNER JOIN productpricing pp ON pp.ProductID = p.ID
								WHERE mt.ID IN (5,6,9,10,11,12)
								AND bx.ID = $branch
								AND DATE(ia.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
								$warehouse
								AND CONCAT('AD', LPAD(ia.ID, 8, 0)) = '$ReferenceNo'
								GROUP BY p.ID

								UNION ALL 

								SELECT
									SUM(sl.QtyIn) QtyInTotal,
									SUM(sl.QtyOut) QtyOutTotal,
									(pp.UnitPrice * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn))) TotalAmount,
									IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
								FROM stocklog sl 
								INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
								INNER JOIN inventory inv ON inv.ID = sl.InventoryID
									AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
								INNER JOIN inventorycount ic ON ic.ID = sl.RefTxnID
									AND LOCATE(CONCAT('-', bx.ID), ic.HOGeneralID) > 0
								INNER JOIN movementtype mt ON mt.ID = ic.MovementTypeID
								INNER JOIN branch b ON b.ID = ic.BranchID
								INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
								INNER JOIN product p ON p.ID = sl.ProductID
								INNER JOIN productpricing pp ON pp.ProductID = p.ID
								INNER JOIN inventorycountdetails icDetails ON icDetails.InventoryCountID = icDetails.ID
									AND LOCATE(CONCAT('-', bx.ID), icDetails.HOGeneralID) > 0
								INNER JOIN location l ON l.ID = icDetails.LocationID
								WHERE mt.ID IN (6)
								AND bx.ID = $branch
								AND DATE(ic.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
								$warehouse
								AND CONCAT('IC', LPAD(ic.ID, 8, 0)) = '$ReferenceNo'
								GROUP BY p.ID

								$RDGIDG

								UNION ALL 

								SELECT
									SUM(sl.QtyIn) QtyInTotal,
									SUM(sl.QtyOut) QtyOutTotal,
									(pp.UnitPrice * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn) * -1)) TotalAmount,
									IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
								FROM stocklog sl 
								INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
								INNER JOIN inventory inv ON inv.ID = sl.InventoryID
									AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
								INNER JOIN salesinvoice si ON si.ID = sl.RefTxnID
									AND LOCATE(CONCAT('-', bx.ID), si.HOGeneralID) > 0
								INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
								INNER JOIN branch b ON b.ID = si.BranchID
								INNER JOIN product p ON p.ID = sl.ProductID
								INNER JOIN productpricing pp ON pp.ProductID = p.ID
								INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
								WHERE mt.ID IN (13, 14, 17)
								AND bx.ID = $branch
								AND DATE(si.TxnDate) BETWEEN '$txtStartDates' and '$txtEndDates'
								$warehouse
								AND CONCAT('SI', LPAD(si.ID, 8, 0)) = '$ReferenceNo'
								GROUP BY p.ID, mt.ID
								
								UNION ALL
								
								SELECT
									SUM(sl.QtyIn) QtyInTotal,
									SUM(sl.QtyOut) QtyOutTotal,
									ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalAmount,
									IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
								FROM stocklog sl 
								INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
								LEFT JOIN inventory inv ON inv.ID = sl.InventoryID
									AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
								LEFT JOIN productexchange pc ON pc.ID = sl.RefTxnID
									AND LOCATE(CONCAT('-', bx.ID), pc.HOGeneralID) > 0
								LEFT JOIN movementtype mt ON mt.ID = sl.MovementTypeID
								LEFT JOIN warehouse wh ON wh.ID = inv.WarehouseID
								LEFT JOIN product p ON p.ID = sl.ProductID
								LEFT JOIN productpricing pp ON pp.ProductID = p.ID
								WHERE mt.ID IN (18, 19)
								AND bx.ID = $branch
								AND DATE(sl.TxnDate) BETWEEN '$txtStartDates' AND '$txtEndDates'
								$warehouse
								AND CONCAT('PEC', LPAD(pc.ID, 8, 0)) = '$ReferenceNo'
								GROUP BY p.ID) atbl");
								
	return $query;
}

function inventorytransactionTotal($txtStartDates, $txtEndDates, $cboWarehouse, $txtSearch, $branch){
	global $database;
	
	$warehouse = ($cboWarehouse > 0) ? " AND mt.ID = $cboWarehouse" : "";
	
	$RDGIDG = "";
	
	if($cboWarehouse > 0){
		if($cboWarehouse == 7){
			$RDGIDG = "UNION ALL
						SELECT
							SUM(sl.QtyIn) QtyInTotal,
							SUM(sl.QtyOut) QtyOutTotal,
							ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalAmount,
							IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
						FROM stocklog sl 
						INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
						INNER JOIN inventory inv ON inv.ID = sl.InventoryID
							AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
						INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
							AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
						INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
						INNER JOIN branch b ON b.ID = it.BranchID
						INNER JOIN product p ON p.ID = sl.ProductID
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
						INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
						WHERE mt.ID IN (7)
						AND bx.ID = $branch
						AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
						AND (p.Code LIKE '$txtSearch%'
							OR p.Name LIKE '$txtSearch%'
							OR CONCAT('TR', LPAD(it.ID, 8, 0)) LIKE '$txtSearch%')
						GROUP BY p.ID, it.ID, mt.ID";
						
		}else if($cboWarehouse == 8){
			$RDGIDG = "UNION ALL
						SELECT
							SUM(sl.QtyOut) QtyInTotal,
							SUM(sl.QtyIn) QtyOutTotal,
							ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalAmount,
							IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
						FROM stocklog sl 
						INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
						INNER JOIN inventory inv ON inv.ID = sl.InventoryID
							AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
						INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
							AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
						INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
						INNER JOIN branch b ON b.ID = it.BranchID
						INNER JOIN product p ON p.ID = sl.ProductID
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
						INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
						WHERE mt.ID IN (7)
						AND bx.ID = $branch
						AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
						AND (p.Code LIKE '$txtSearch%'
							OR p.Name LIKE '$txtSearch%'
							OR CONCAT('TR', LPAD(it.ID, 8, 0)) LIKE '$txtSearch%')
						GROUP BY p.ID, it.ID, mt.ID";
		}
	}else{
		$RDGIDG = "UNION ALL
						SELECT
							SUM(sl.QtyIn) QtyInTotal,
							SUM(sl.QtyOut) QtyOutTotal,
							ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalAmount,
							IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
						FROM stocklog sl 
						INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
						INNER JOIN inventory inv ON inv.ID = sl.InventoryID
							AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
						INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
							AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
						INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
						INNER JOIN branch b ON b.ID = it.BranchID
						INNER JOIN product p ON p.ID = sl.ProductID
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
						INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
						WHERE mt.ID IN (7)
						AND bx.ID = $branch
						AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
						AND (p.Code LIKE '$txtSearch%'
							OR p.Name LIKE '$txtSearch%'
							OR CONCAT('TR', LPAD(it.ID, 8, 0)) LIKE '$txtSearch%')
						GROUP BY p.ID, it.ID, mt.ID
					
					UNION ALL
					
						SELECT
							SUM(sl.QtyOut) QtyInTotal,
							SUM(sl.QtyIn) QtyOutTotal,
							ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalAmount,
							IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
						FROM stocklog sl 
						INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
						INNER JOIN inventory inv ON inv.ID = sl.InventoryID
							AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
						INNER JOIN inventorytransfer it ON it.ID = sl.RefTxnID
							AND LOCATE(CONCAT('-', bx.ID), it.HOGeneralID) > 0
						INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
						INNER JOIN branch b ON b.ID = it.BranchID
						INNER JOIN product p ON p.ID = sl.ProductID
						INNER JOIN productpricing pp ON pp.ProductID = p.ID
						INNER JOIN warehouse w ON w.ID = it.FromWarehouseID
						INNER JOIN warehouse w1 ON w1.ID = it.ToWarehouseID
						WHERE mt.ID IN (7)
						AND bx.ID = $branch
						AND DATE(it.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
						AND (p.Code LIKE '$txtSearch%'
							OR p.Name LIKE '$txtSearch%'
							OR CONCAT('TR', LPAD(it.ID, 8, 0)) LIKE '$txtSearch%')
						GROUP BY p.ID, it.ID, mt.ID";
	}
	
	$query = $database->execute("SELECT 
								SUM(TotalAmount) TotalAmount,
								SUM(TotalQty) TotalQty,
								SUM(QtyInTotal) QtyInTotal,
								SUM(QtyOutTotal) QtyOutTotal
							FROM
							(SELECT
								SUM(sl.QtyIn) QtyInTotal,
								SUM(sl.QtyOut) QtyOutTotal,
								ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalAmount,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							INNER JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							INNER JOIN inventoryinout iio ON iio.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), iio.HOGeneralID) > 0
							INNER JOIN movementtype mt ON mt.ID = iio.MovementTypeID
							INNER JOIN branch b ON b.ID = iio.BranchID
							INNER JOIN branch bto ON bto.ID = iio.ToBranchID
							INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
							INNER JOIN product p ON p.ID = sl.ProductID
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							WHERE mt.ID IN (1, 3)
							AND bx.ID = $branch
							AND DATE(iio.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('II', LPAD(iio.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, iio.ID, mt.ID

							UNION ALL

							SELECT
								SUM(sl.QtyIn) QtyInTotal,
								SUM(sl.QtyOut) QtyOutTotal,
								ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalAmount,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							INNER JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							INNER JOIN inventoryinout iio ON iio.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), iio.HOGeneralID) > 0
							INNER JOIN movementtype mt ON mt.ID = iio.MovementTypeID
							INNER JOIN branch b ON b.ID = iio.BranchID
							INNER JOIN branch bto ON bto.ID = iio.ToBranchID
							INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
							INNER JOIN product p ON p.ID = sl.ProductID
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							WHERE mt.ID IN (2, 4)
							AND bx.ID = $branch
							AND DATE(iio.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('IO', LPAD(iio.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, iio.ID, mt.ID

							UNION ALL

							SELECT
								SUM(sl.QtyIn) QtyInTotal,
								SUM(sl.QtyOut) QtyOutTotal,
								ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalAmount,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							INNER JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							INNER JOIN inventoryadjustment ia ON ia.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), ia.HOGeneralID) > 0
							INNER JOIN movementtype mt ON mt.ID = ia.MovementTypeID
							INNER JOIN branch b ON b.ID = ia.BranchID
							INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
							INNER JOIN product p ON p.ID = sl.ProductID
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							WHERE mt.ID IN (5,6,9,10,11,12)
							AND bx.ID = $branch
							AND DATE(ia.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('AD', LPAD(ia.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, ia.ID, mt.ID

							UNION ALL 

							SELECT
								SUM(sl.QtyIn) QtyInTotal,
								SUM(sl.QtyOut) QtyOutTotal,
								ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalAmount,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							INNER JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							INNER JOIN inventorycount ic ON ic.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), ic.HOGeneralID) > 0
							INNER JOIN movementtype mt ON mt.ID = ic.MovementTypeID
							INNER JOIN branch b ON b.ID = ic.BranchID
							INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
							INNER JOIN product p ON p.ID = sl.ProductID
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							INNER JOIN inventorycountdetails icDetails ON icDetails.InventoryCountID = icDetails.ID
								AND LOCATE(CONCAT('-', bx.ID), icDetails.HOGeneralID) > 0
							INNER JOIN location l ON l.ID = icDetails.LocationID
							WHERE mt.ID IN (6)
							AND bx.ID = $branch
							AND DATE(ic.TransactionDate) BETWEEN '$txtStartDates' and '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('IC', LPAD(ic.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, ic.ID, mt.ID

							$RDGIDG

							UNION ALL 

							SELECT
								SUM(sl.QtyIn) QtyInTotal,
								SUM(sl.QtyOut) QtyOutTotal,
								ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn) * -1) TotalAmount,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							INNER JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							INNER JOIN salesinvoice si ON si.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), si.HOGeneralID) > 0
							INNER JOIN movementtype mt ON mt.ID = sl.MovementTypeID
							INNER JOIN branch b ON b.ID = si.BranchID
							INNER JOIN product p ON p.ID = sl.ProductID
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							INNER JOIN warehouse wh ON wh.ID = inv.WarehouseID
							WHERE mt.ID IN (13, 14, 17)
							AND bx.ID = $branch
							AND DATE(si.TxnDate) BETWEEN '$txtStartDates' and '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('SI', LPAD(si.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, si.ID, mt.ID
							
							UNION ALL
							
							SELECT
								SUM(sl.QtyIn) QtyInTotal,
								SUM(sl.QtyOut) QtyOutTotal,
								ROUND(pp.UnitPrice, 2) * IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalAmount,
								IF(SUM(sl.QtyIn) <= 0, SUM(sl.QtyOut), SUM(sl.QtyIn)) TotalQty
							FROM stocklog sl 
							INNER JOIN branch bx ON bx.ID = SPLIT_STR(sl.HOGeneralID, '-', 2)
							LEFT JOIN inventory inv ON inv.ID = sl.InventoryID
								AND LOCATE(CONCAT('-', bx.ID), inv.HOGeneralID) > 0
							LEFT JOIN productexchange pc ON pc.ID = sl.RefTxnID
								AND LOCATE(CONCAT('-', bx.ID), pc.HOGeneralID) > 0
							LEFT JOIN movementtype mt ON mt.ID = sl.MovementTypeID
							LEFT JOIN warehouse wh ON wh.ID = inv.WarehouseID
							LEFT JOIN product p ON p.ID = sl.ProductID
							LEFT JOIN productpricing pp ON pp.ProductID = p.ID
							WHERE mt.ID IN (18, 19)
							AND bx.ID = $branch
							AND DATE(sl.TxnDate) BETWEEN '$txtStartDates' AND '$txtEndDates'
							$warehouse
							AND (p.Code LIKE '$txtSearch%'
								OR p.Name LIKE '$txtSearch%'
								OR CONCAT('PEC', LPAD(pc.ID, 8, 0)) LIKE '$txtSearch%')
							GROUP BY p.ID, pc.ID, mt.ID) atbl");
	return $query;
}

?>