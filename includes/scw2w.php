<?php

function getw2w($page, $totalrow, $isTotal, $txtStartDates, $txtSearch, $branch )
{
	global $database;
	
	$start = ($page > 1) ? ($page - 1) * $totalrow : 0;
	$limit = ($isTotal)? " ": 'limit '.$start.','.$totalrow ;
	
	$query = $database->execute("   SELECT hdr.id TID, hdr.BranchID, hdr.TransactionDate, hdr.RefID , hdr.DocumentNo , 
										   p.code pcode, p.Description Description, p.3rd_ItemNumber thirdrditem,
										   dtl.CHCreatedQty, dtl.CHfreezeQty, dtl.HOFreezeQty, dtl.RegularPrice, 
										   IFNULL(dtl.AdjustmentQty,0) AdjustmentQty, w.code wcode
									FROM inventorycountch hdr
									INNER JOIN inventorycountchdetails dtl ON dtl.InventoryCountID = hdr.id
									INNER JOIN warehouse w ON w.ID = dtl.WarehouseID
									INNER JOIN product p ON p.id = dtl.ProductID
									WHERE hdr.BranchID = '$branch'
									  AND hdr.RefID    = '$txtSearch'
									ORDER BY dtl.WarehouseID  
									$limit
	                           "); 
	
	return $query;
	
}
	
	
?>