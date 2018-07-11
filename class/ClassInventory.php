<?php
/*
	@author: Gino C. Leabres
	@date: 5/28/2013
	@email: ginophp@gmail.com
*/
Class InventoryClass
{
	function spSelectInvCntDetByID($database, $txnid, $v_mode)
	{
		if($v_mode == 2){
			$q = "SELECT COUNT(invd.ID) AS numrows
					FROM inventorycountchdetails invd
					INNER JOIN inventorycountch ic ON ic.`id` = invd.`inventorycountid`
					INNER JOIN product p ON invd.`ProductID` = p.`ID`
					INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
					INNER JOIN warehouse ware ON ware.id = ic.`WarehouseID`
					WHERE invd.`InventoryCountID` = ".$txnid." 
			      ";
		} else if ($v_mode == 4){
			$q = "SELECT COUNT(invd.ID) AS numrows
				FROM inventorycountdetails invd
				INNER JOIN inventorycount ic ON ic.`id` = invd.`inventorycountid`
				INNER JOIN product p ON invd.`ProductID` = p.`ID`
				INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
				INNER JOIN warehouse ware ON ware.id = ic.`WarehouseID`
				WHERE invd.`InventoryCountID` = ".$txnid;
		}else{
			$q = "SELECT DISTINCT invd.ID invdid, p.ID ProductID, p.Code pCode, p.Name pName, uom.name UOM, invd.`CreatedQty` CreatedQty, ic.`WarehouseID`,
								IFNULL(iv.ID, 0) InventoryID, IFNULL(iv.SOH, 0) SOH, IFNULL(invd.PrevBalance, 0) prevSOH, invd.tpi_CountTag CountTag,
								pp.unitprice up,ic.DocumentNo, CAST(CONCAT('IC', REPEAT('0', (8- LENGTH(ic.ID))), ic.ID) AS CHAR) TxnNo, lo.Name Location, lo.ID LocationID
				FROM inventorycountdetails invd
				INNER JOIN inventorycount ic ON ic.`id` = invd.`inventorycountid`
				INNER JOIN product p ON p.`ID`  =  invd.`ProductID`
				LEFT JOIN productpricing pp ON pp.`ProductID` = p.`ID`
				INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
				INNER JOIN warehouse ware ON ware.id = ic.`WarehouseID`
				LEFT JOIN inventory iv ON iv.ProductID = p.ID AND iv.WarehouseID = ware.ID
				INNER JOIN location lo ON lo.ID = invd.LocationID
				WHERE invd.`InventoryCountID` = ".$txnid." AND invd.`CreatedQty` IS NOT NULL
				GROUP BY invd.ProductID, invd.LocationID
				ORDER BY invd.tpi_CountTag "; //p.code
		}
		$result = $database->execute($q);
		return $result;
	}
	
	function spAjaxSelectInvCntDetByID($database, $txnid, $v_mode, $ItemCode)
	{
		if($v_mode == 2){
			$q = "SELECT COUNT(invd.ID) AS numrows
				FROM inventorycountdetails invd
				INNER JOIN inventorycount ic ON ic.`id` = invd.`inventorycountid`
				INNER JOIN product p ON invd.`ProductID` = p.`ID`
				INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
				INNER JOIN warehouse ware ON ware.id = ic.`WarehouseID`
				WHERE invd.`InventoryCountID` = ".$txnid." AND invd.`CreatedQty` IS NULL";
		} else if ($v_mode == 4){
			$q = "SELECT COUNT(invd.ID) AS numrows
				FROM inventorycountdetails invd
				INNER JOIN inventorycount ic ON ic.`id` = invd.`inventorycountid`
				INNER JOIN product p ON invd.`ProductID` = p.`ID`
				INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
				INNER JOIN warehouse ware ON ware.id = ic.`WarehouseID`
				WHERE invd.`InventoryCountID` = ".$txnid;
		}else{
			$q = "SELECT DISTINCT invd.ID invdid, p.ID ProductID, p.Code pCode, p.Name pName, uom.name UOM, invd.`CreatedQty` CreatedQty, ic.`WarehouseID`,
								IFNULL(iv.ID, 0) InventoryID, IFNULL(iv.SOH, 0) SOH, IFNULL(invd.PrevBalance, 0) prevSOH, invd.tpi_CountTag CountTag,
								pp.unitprice up,ic.DocumentNo, CAST(CONCAT('IC', REPEAT('0', (8- LENGTH(ic.ID))), ic.ID) AS CHAR) TxnNo, lo.Name Location, lo.ID LocationID
				FROM inventorycountdetails invd
				INNER JOIN inventorycount ic ON ic.`id` = invd.`inventorycountid`
				INNER JOIN product p ON p.`ID`  =  invd.`ProductID`
				LEFT JOIN productpricing pp ON pp.`ProductID` = p.`ID`
				INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
				INNER JOIN warehouse ware ON ware.id = ic.`WarehouseID`
				LEFT JOIN inventory iv ON iv.ProductID = p.ID AND iv.WarehouseID = ware.ID
				INNER JOIN location lo ON lo.ID = invd.LocationID
				WHERE invd.`InventoryCountID` = ".$txnid." AND p.Code like '%".$ItemCode."%' AND invd.`CreatedQty` IS NOT NULL
				GROUP BY invd.ProductID, invd.LocationID
				ORDER BY invd.tpi_CountTag LIMIT 10"; //p.code
		}
		$result = $database->execute($q);
		return $result;
	}
	
	function spSelectInventoryInRHO($database, $offset, $RPP, $vSearch)
	{
		
			if($vSearch == ""){	
				'select
				ii.ID TxnID,
				cast(concat("II", repeat("0", (8- length(ii.ID))), ii.ID) as char) TxnNo,
				ii.DocumentNo,
				s.Name Status,
				sum(iid.LoadedQty) ltotQty,
				case ii.StatusID
						when 6 then sum(iid.LoadedQty)
						else sum(iid.ConfirmedQty)
				end totQty,
				count(iid.id) totSKU,
				date_format(ii.ShipmentDate,"%m/%d/%Y" ) txnDate,
				date_format(ii.EnrollmentDate,"%m/%d/%Y" ) downloadedDate,
				date_format(ii.LastModifiedDate,"%m/%d/%Y" ) confirmedDate,
				concat(e.FirstName, "", e.LastName) ConfirmedBy,
				s.ID StatusID,
				b.code bcode,
				tob.Code ToBranchCode
				from `inventoryinout` ii
				inner join `inventoryinoutdetails` iid on ii.id = iid.InventoryInOutID
				inner join `movementtype` mt on mt.ID = ii.`MovementTypeID` and mt.id = 1 and mt.IsOut = 0
				inner join `branch` b on b.ID = ii.`BranchID`
				inner join `branch` tob on tob.ID = ii.`ToBranchID`
				inner join `warehouse` w on w.ID = ii.`WarehouseID`
				inner join `status` s on s.ID = ii.`StatusID`
				left join `employee` e on e.ID = ii.ConfirmedBy
				group by ii.id
				order by s.name desc, ii.ShipmentDate desc
				limit  '.$offset.', '.$RPP;
			}else{
				" SELECT
					ii.ID TxnID,
					CAST(CONCAT('II', REPEAT('0', (8- LENGTH(ii.ID))), ii.ID) AS CHAR) TxnNo,
					ii.DocumentNo,
					s.Name STATUS,
					SUM(iid.LoadedQty) ltotQty,
					CASE ii.StatusID
							WHEN 6 THEN SUM(iid.LoadedQty)
							ELSE SUM(iid.ConfirmedQty)
					END totQty,
					COUNT(iid.id) totSKU,
					DATE_FORMAT(ii.ShipmentDate,'%m/%d/%Y' ) txnDate,
					DATE_FORMAT(ii.EnrollmentDate,'%m/%d/%Y' ) downloadedDate,
					DATE_FORMAT(ii.LastModifiedDate,'%m/%d/%Y' ) confirmedDate,
					CONCAT(e.FirstName, ' ', e.LastName) ConfirmedBy,
					s.ID StatusID,
					b.code bcode,
					tob.Code ToBranchCode
				FROM `inventoryinout` ii
					INNER JOIN `inventoryinoutdetails` iid ON ii.id = iid.InventoryInOutID
					INNER JOIN `movementtype` mt ON mt.ID = ii.`MovementTypeID` AND mt.id = 1 AND mt.IsOut = 0
					INNER JOIN `branch` b ON b.ID = ii.`BranchID`
					INNER JOIN `branch` tob ON tob.ID = ii.`ToBranchID`
					INNER JOIN `warehouse` w ON w.ID = ii.`WarehouseID`
					INNER JOIN `status` s ON s.ID = ii.`StatusID`
					LEFT JOIN `employee` e ON e.ID = ii.ConfirmedBy
				WHERE (CONCAT('II', REPEAT('0', (8- LENGTH(ii.ID))), ii.ID) LIKE '".$vSearch."')) OR (CONCAT(ii.DocumentNo, '.', b.code) LIKE '".$vSearch."')
				GROUP BY ii.id
				ORDER BY s.name DESC, ii.ShipmentDate DESC";
			}
	
	}
	
	function spSelectCriticalStockOutofStockReport ($database, $v_flag, $v_offset, $v_perpage, $v_warehouse, $v_plineid, $v_pmg, $pcode, $v_invstatus, $v_qtyfrom, $v_qtyto)
	{	
	
	   $str = "";
	   if ($v_pmg <> 0){
		 $str .= 'and pp.PMGID = '.$v_pmg;
	   }
	   
	   else if ($v_invstatus <> 0){
		 $str .= ' and p.StatusID = '.$v_invstatus;
	   }
 
	   else if ($v_plineid <> 0){
			$str .= ' and p.ParentID = '.$v_plineid;
	   }
		if($v_flag == 1){
			$q = "SELECT
					p.ID,
					p.Code ItemCode,
					p.Name ItemDesc,
					pp.PMGID,
					pmg.Code PMG,
					CASE
						WHEN (DATE_FORMAT(NOW(),'%Y-%m-%d') BETWEEN DATE_FORMAT(pb.StartDate,'%Y-%m-%d') AND DATE_FORMAT(pb.EndDate,'%Y-%m-%d')) THEN ped.EffectivePrice
						ELSE pp.UnitPrice
					END CampaignPrice,
					i.SOH,
					IFNULL(DATE_FORMAT(si.TxnDate,'%Y-%m-%d'), '') DateLastSold,
					IFNULL(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(si.TxnDate,'%Y-%m-%d')), 0) DaysNotAvailed,
				    i.InTransit,
					pa.Code ParentCode
			      FROM product p
			      LEFT JOIN promoentitlementdetails ped ON ped.ProductID = p.ID
			      LEFT JOIN promobuyin pb ON pb.ProductID = p.ID
			      INNER JOIN productpricing pp ON pp.ProductID = p.ID
			      INNER JOIN tpi_pmg pmg ON pmg.ID = pp.PMGID
			      INNER JOIN inventory i ON i.ProductID = p.ID
			      LEFT JOIN( SELECT sid.SalesInvoiceID, sid.ProductID, sid.UnitPrice, MAX(si.TxnDate) FROM salesinvoicedetails sid
			      	INNER JOIN salesinvoice si ON si.ID = sid.SalesInvoiceID
			      	GROUP BY sid.SalesInvoiceID, sid.ProductID) tsi ON tsi.ProductID = p.ID
			      LEFT JOIN salesinvoice si ON si.ID = tsi.SalesInvoiceID
			      INNER JOIN product pa ON pa.ID = p.ParentID
				  WHERE i.WarehouseID = ".$v_warehouse."
				  AND i.SOH BETWEEN ".$v_qtyfrom." AND ".$v_qtyto." ".$str." LIMIT  ".$v_offset.", ".$v_perpage;
		}
		else if ($v_flag == 2){
			$q = "select count(p.ID) Total
                           from product p
						    LEFT JOIN promoentitlementdetails ped ON ped.ProductID = p.ID
			      LEFT JOIN promobuyin pb ON pb.ProductID = p.ID
			      INNER JOIN productpricing pp ON pp.ProductID = p.ID
			      INNER JOIN tpi_pmg pmg ON pmg.ID = pp.PMGID
			      INNER JOIN inventory i ON i.ProductID = p.ID
			      LEFT JOIN( SELECT sid.SalesInvoiceID, sid.ProductID, sid.UnitPrice, MAX(si.TxnDate) FROM salesinvoicedetails sid
			      	INNER JOIN salesinvoice si ON si.ID = sid.SalesInvoiceID
			      	GROUP BY sid.SalesInvoiceID, sid.ProductID) tsi ON tsi.ProductID = p.ID
			      LEFT JOIN salesinvoice si ON si.ID = tsi.SalesInvoiceID
			      INNER JOIN product pa ON pa.ID = p.ParentID
				  WHERE i.WarehouseID = ".$v_warehouse."
				  AND i.SOH BETWEEN ".$v_qtyfrom." AND ".$v_qtyto." ".$str;
				  // LIMIT  ".$v_offset.", ".$v_perpage;
				 
				  
		}else{
			$q = "SELECT
					p.ID,
					p.Code ItemCode,
					p.Name ItemDesc,
					pp.PMGID,
					pmg.Code PMG,
					CASE
						WHEN (DATE_FORMAT(NOW(),'%Y-%m-%d') BETWEEN DATE_FORMAT(pb.StartDate,'%Y-%m-%d') AND DATE_FORMAT(pb.EndDate,'%Y-%m-%d')) THEN ped.EffectivePrice
						ELSE pp.UnitPrice
					END CampaignPrice,
					i.SOH,
					IFNULL(DATE_FORMAT(si.TxnDate,'%Y-%m-%d'), '') DateLastSold,
					IFNULL(DATEDIFF(DATE_FORMAT(NOW(),'%Y-%m-%d'),DATE_FORMAT(si.TxnDate,'%Y-%m-%d')), 0) DaysNotAvailed,
					i.InTransit,
					pa.Code ParentCode
				  FROM product p
			      LEFT JOIN promoentitlementdetails ped ON ped.ProductID = p.ID
			      LEFT JOIN promobuyin pb ON pb.ProductID = p.ID
			      INNER JOIN productpricing pp ON pp.ProductID = p.ID
			      INNER JOIN tpi_pmg pmg ON pmg.ID = pp.PMGID
			      INNER JOIN inventory i ON i.ProductID = p.ID
			      LEFT JOIN(SELECT sid.SalesInvoiceID, sid.ProductID, sid.UnitPrice, MAX(si.TxnDate) FROM salesinvoicedetails sid
				   INNER JOIN salesinvoice si ON si.ID = sid.SalesInvoiceID
				   GROUP BY sid.SalesInvoiceID, sid.ProductID) tsi ON tsi.ProductID = p.ID
				  LEFT JOIN salesinvoice si ON si.ID = tsi.SalesInvoiceID
				  INNER JOIN product pa ON pa.ID = p.ParentID
				  WHERE i.WarehouseID = ".$v_warehouse."
				  AND i.SOH BETWEEN ".$v_qtyfrom." AND ".$v_qtyto." ".$str;
		}
		
		
		//echo $q."<br />";
		$result = $database->execute($q);
		return $result;
	}
	
	
	function spSelectInvCntDetByIDForPrinting($database, $txnid, $v_sessionid)
	{	
		
		$q =  "SELECT DISTINCT invd.ID invdid, p.ID ProductID,p.Code pCode,p.Name pName,uom.name UOM,invd.`CreatedQty` CreatedQty,
				ic.`WarehouseID`, IFNULL(iv.ID, 0) InventoryID, IFNULL(iv.SOH, 0) SOH, IFNULL(invd.PrevBalance, 0) prevSOH, invd.tpi_CountTag CountTag,
				pp.unitprice up,ic.DocumentNo, CAST(CONCAT('IC', REPEAT('0', (8- LENGTH(ic.ID))), ic.ID) AS CHAR) TxnNo,lo.Name Location
			FROM productlistforprinting pfp
				INNER JOIN inventorycountdetails invd ON invd.ProductID = pfp.ProductID AND invd.InventoryCountID = pfp.InventoryCountID
				INNER JOIN inventorycount ic ON ic.`id` = invd.`inventorycountid`
				INNER JOIN product p ON p.`ID`  =  invd.`ProductID`
				LEFT JOIN productpricing pp ON pp.`ProductID` = p.`ID`
				INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
				INNER JOIN warehouse ware ON ware.id = ic.`WarehouseID`
				LEFT JOIN inventory iv ON iv.ProductID = p.ID AND iv.WarehouseID = ware.ID
				INNER JOIN location lo ON lo.ID = invd.LocationID
			WHERE pfp.InventoryCountID = ".$txnid." AND pfp.PHPSession = '".$v_sessionid."'
			ORDER BY invd.tpi_CountTag";
		//echo $q;
		//die();
		$result = $database->execute($q);
		return $result;
	}
	
	function spSelectProductforProdReportStat($database,$offset, $RPP, $vSearch, $warehouseid, $plid, $pmgid, $location, $ref, $frmdate2)
	{
		$str = "";
		if ($warehouseid > 0){
			$str .= " and i.WarehouseID= ".$warehouseid;
		}
		
		if ($pmgid > 0){
			$str .= " and pp.`pmgid`= ".$pmgid;
		}
		
		if ($plid > 0){
		 $str .= " and p.`ParentID` = ".$plid;
		}
		
		if ($ref = 2){
		 $str .= " and i.`soh` < 0 ";
		}
		
		$q = 'Select distinct
                  p.ID pid,
                  i.id invid,
                  p.code prodcode,
                  p.name proddesc,
                  pp.unitprice up,
                  (select Name from product p1 where p1.`ID`= p.`ParentID`) ProdLine,
                  ifnull(i.SOH, 0) soh,
				  i.warehouseid wid,
                  p.Enrollmentdate,
                  pm.Name pmgname,
                  st.Name status
              from product p
                  inner join productpricing pp on pp.Productid = p.id
                  inner join inventory i on i.ProductID = p.id
                  inner join tpi_pmg pm on pm.id = pp.pmgid
                  inner join status st on st.ID = p.StatusID
          where p.ProductLevelID = 3 and p.`enrollmentdate` > "'.$frmdate2.'" and (p.Code like "%'.$vSearch.'%") 
		  '.$str.' 
		  GROUP BY p.ID
		  ORDER BY p.Name
		  limit '.$offset.', '.$RPP;
		//echo $q;
		$result = $database->execute($q);
		return $result;
	}
	
	function spSelectValuationReportv1($database, $offset, $RPP, $vSearch, $warehouseid, $location, $pmgid, $plid, $newdate)
	{
	
			$str = "";
			if ($warehouseid > 0){
			
				$str .= " and i.WarehouseID= ".$warehouseid;
			}
			if ($pmgid > 0){
				$str .= " and tpmg.`ID` = ".$pmgid;
			}
			
			if ($plid > 0){
				 $str .= " and p.`ParentID` = ".$plid;
			}
	
			
		$q = "
				select distinct
					sl.`TxnDate`,
					i.ID,
					i.SOH,
					i.ProductID,
					p.Code ItemCode,
					p.Name ItemDescription,
					u.Name UOM,
					w.Name,
					w.ID WarehouseID,
					tpmg.`Name` PMGName,
					(select Code from product p1 where p1.`ID`= p.`ParentID`) ProdLineCode,
					CONCAT( (select Code from product p1 where p1.`ID`= p.`ParentID`), '-' ,(select Name from product p1 where p1.`ID`= p.`ParentID` ) ) ProdLine,
					(SELECT ifnull(SUM(QtyOut),0) QtyOut FROM stocklog sl1 WHERE sl1.`InventoryID`= i.`ID`AND DATE_FORMAT(TxnDate, '%m/%d/%Y') > '".$newdate."') QtyOut,
					(SELECT ifnull(SUM(QtyIn),0) QtyIn FROM stocklog sl1 WHERE sl1.`InventoryID`= i.`ID`AND DATE_FORMAT(TxnDate, '%m/%d/%Y') > '".$newdate."') QtyIn,
					round(pp.`UnitPrice`,2) ProductPrice,
					round((pp.`UnitPrice`* i.`SOH`),2) TotalValue
				from inventory i
					inner join product p on p.ID = i.ProductID
					inner join unittype u on u.ID = p.UnitTypeID
					inner join warehouse w on w.ID = i.WarehouseID and w.StatusID = 1
					inner join `productpricing`pp on pp.`ProductID` = p.`ID`
					inner join `tpi_pmg` tpmg on tpmg.`ID`= pp.`PMGID`
					INNER JOIN ( SELECT InventoryID, QtyOut , TxnDate FROM stocklog ) sl ON sl.InventoryID = i.ID
					where (p.Name like '%".$vSearch."%' or p.Code like '%".$vSearch."%') ".$str."
					group by p.ID
					order by ProdLineCode asc,p.Code asc
					limit ".$offset.",".$RPP;
					//and DATE_FORMAT(sl.`TxnDate`, '%m/%d/%Y') >  '".$newdate."'
		$result = $database->execute($q);
		return $result;
	}
	
	function spSelectValuationReportCount($database, $vSearch, $warehouseid,$location,$pmgid,$plid,$newdate)
	{
		$str = "";
			if ($warehouseid > 0){
			
				$str .= " and i.WarehouseID= ".$warehouseid;
			}
			if ($pmgid > 0){
				$str .= " and tpmg.`ID` = ".$pmgid;
			}
			
			if ($plid > 0){
				 $str .= " and p.`ParentID` = ".$plid;
			}
	
			
		$q = "
				select distinct
					sl.`TxnDate`,
					i.ID,
					i.SOH,
					i.ProductID,
					p.Code ItemCode,
					p.Name ItemDescription,
					u.Name UOM,
					w.Name,
					w.ID WarehouseID,
					tpmg.`Name` PMGName,
					(select Code from product p1 where p1.`ID`= p.`ParentID`) ProdLineCode,
					CONCAT( (select Code from product p1 where p1.`ID`= p.`ParentID`), '-' ,(select Name from product p1 where p1.`ID`= p.`ParentID` ) ) ProdLine,
					(SELECT ifnull(SUM(QtyOut),0) QtyOut FROM stocklog sl1 WHERE sl1.`InventoryID`= i.`ID`AND DATE_FORMAT(TxnDate, '%m/%d/%Y') > '".$newdate."') QtyOut,
					(SELECT ifnull(SUM(QtyIn),0) QtyIn FROM stocklog sl1 WHERE sl1.`InventoryID`= i.`ID`AND DATE_FORMAT(TxnDate, '%m/%d/%Y') > '".$newdate."') QtyIn,
					round(pp.`UnitPrice`,2) ProductPrice,
					round((pp.`UnitPrice`* i.`SOH`),2) TotalValue
				from inventory i
					inner join product p on p.ID = i.ProductID
					inner join unittype u on u.ID = p.UnitTypeID
					inner join warehouse w on w.ID = i.WarehouseID and w.StatusID = 1
					inner join `productpricing`pp on pp.`ProductID` = p.`ID`
					inner join `tpi_pmg` tpmg on tpmg.`ID`= pp.`PMGID`
					INNER JOIN ( SELECT InventoryID, QtyOut , TxnDate FROM stocklog ) sl ON sl.InventoryID = i.ID
					where (p.Name like '%".$vSearch."%' or p.Code like '%".$vSearch."%') ".$str."
					group by p.ID
					order by ProdLineCode asc,p.Code asc";
					//and DATE_FORMAT(sl.`TxnDate`, '%m/%d/%Y') >  '".$newdate."'
		$result = $database->execute($q);
		return $result;
	}
	
	function spSelectInvAdjByID($database, $txnid)
	{
		$q = "SELECT p.ID ProductID, p.Code, p.Name, IFNULL(inv.`SOH`, 0) SOH, uom.name UOM, invd.`CreatedQty`, res.ID ResonID, res.name Reason, w.id WarehouseID,
				ia.StatusID stat,
				pp.UnitPrice regprice, IFNULL(invd.PrevBalance,0) PrevBalance FROM inventoryadjustmentdetails invd
				INNER JOIN inventoryadjustment ia ON ia.`ID` = invd.`InventoryAdjustmentID`
				INNER JOIN product p ON invd.`ProductID` = p.`ID`
				INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
				INNER JOIN reason res ON invd.`ReasonID` = res.`ID`
				INNER JOIN warehouse w ON ia.`WarehouseID` = w.id
				LEFT JOIN inventory inv ON p.id = inv.`ProductID` AND w.ID = inv.`WarehouseID`
				LEFT JOIN productpricing pp ON pp.ProductID = p.ID
			WHERE invd.`InventoryAdjustmentID` = ".$txnid;
		$result = $database->execute($q);
		return $result;
	}
	
	function spSelectInvAdjDetailsByID($database, $txnid)
	{
		$q = "SELECT
				p.ID ProductID,
				p.Code,
				p.Name,
				IFNULL(inv.`SOH`, 0) SOH,
				uom.name UOM,
				invd.`CreatedQty`,
				res.ID ResonID,
				res.name Reason,
				w.id WarehouseID,
				ia.StatusID stat,
				pp.UnitPrice regprice,
				IFNULL(invd.PrevBalance,0) PrevBalance
			FROM inventoryadjustmentdetails invd
			INNER JOIN inventoryadjustment ia ON ia.`ID` = invd.`InventoryAdjustmentID`
			INNER JOIN product p ON invd.`ProductID` = p.`ID`
			INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
			INNER JOIN reason res ON invd.`ReasonID` = res.`ID`
			INNER JOIN warehouse w ON ia.`WarehouseID` = w.id
			LEFT JOIN inventory inv ON p.id = inv.`ProductID` AND w.ID = inv.`WarehouseID`
			LEFT JOIN productpricing pp ON pp.ProductID = p.ID
			WHERE invd.`InventoryAdjustmentID` = ".$txnid;
			
			$result = $database->execute($q);
			return $result;
	}
	
	//Product Exchange..
	
	function spSelectProductDetailsProdExchange($database,$productID)
	{
			$q ="SELECT MAX(colorID) colorID, MAX(subformID) subformID, MAX(styleID) styleID , MAX(formID) formID,
			     productID FROM (SELECT IFNULL(valueID,0) colorID, 0 subformID, 0 styleID, 0 formID, productID
			  				     FROM productdetails WHERE ProductID = ".$productID." AND FieldID = 16 
			  				     UNION ALL
			  				     SELECT 0 colorID, IFNULL(valueID,0) subformID, 0 styleID, 0 formID, productID
			  				     FROM productdetails WHERE ProductID = ".$productID." AND FieldID = 15
			  				     UNION ALL 
			  				     SELECT 0 colorID, 0 subformID, IFNULL(valueID,0)  styleID, 0 formID, productID 
			  				     FROM productdetails WHERE ProductID = ".$productID." AND FieldID = 14 
			  				     UNION ALL
			  				     SELECT 0 colorID, 0 subformID, 0 styleID, IFNULL(valueID,0)  formID, 
			  				     productID FROM productdetails WHERE ProductID = ".$productID." AND FieldID = 9) a
			  	GROUP BY productID";
			$result = $database->execute($q);
			return $result;
	}
	
	function spSelectListProductsAvailableforExchange($database, $v_conditionID, $productID, $colorfield, $colorID, $subformfield, $subformID, $stylefield, $styleID, $formfield, 
																 $formID, $parent_id, $unit_price)
	{
			//parameters
			$v_formfield 	= $formfield;
			$v_formvalue    = $formID;
			$v_stylefield   = $stylefield; 
			$v_stylevalue   = $styleID;
			$v_subformfield = $subformfield;
			$v_subformvalue = $subformID;
			$v_colorfield   = $colorfield;
			$v_colorvalue   = $colorID;
		
			if ($v_conditionID == 1) {
			$q=		'SELECT p.ID prodID,
						p.Code prodCode,
						p.Name prodName
					FROM product p
					INNER JOIN status s ON s.ID = p.StatusID 
					WHERE p.ID = '.$productID.' AND s.Code NOT IN ("XPN","XP")';
			}elseif  ($v_conditionID == 2){
			$q=	'SELECT p.ID prodID,
					p.Code prodCode,
					p.Name prodName,
					i.SOH
				FROM product p
					INNER JOIN productdetails form ON form.ProductID = p.ID AND form.FieldID = '.$v_formfield.'  AND form.ValueID = '.$v_formvalue.'
					INNER JOIN productdetails style ON style.ProductID = p.ID AND   style.FieldID = '.$v_stylefield.' AND style.ValueID = '.$v_stylevalue.'
					INNER JOIN productdetails subform ON subform.ProductID = p.ID AND    subform.FieldID = '.$v_subformfield.' AND subform.ValueID = '.$v_subformvalue.'
					INNER JOIN productdetails color ON color.ProductID = p.ID AND   color.FieldID = '.$v_colorfield.'
					INNER JOIN status s ON s.ID = p.StatusID 
					INNER JOIN inventory i ON i.ProductID = p.ID AND i.WarehouseID = 1
				WHERE p.ID != '.$productID.' AND color.ValueID = '.$v_colorvalue.' And p.ParentID = '.$parent_id.' AND  i.SOH > 0  AND s.Code NOT IN ("XPN","XP")';
			}elseif  ($v_conditionID == 3){
			$q=	'SELECT
						p.ID prodID,
						p.Code prodCode,
						p.Name prodName,
						i.SOH
				FROM product p
				INNER JOIN productdetails form ON form.ProductID = p.ID AND form.FieldID = '.$v_formfield.' AND form.ValueID = '.$v_formvalue.'
				INNER JOIN productdetails style ON style.ProductID = p.ID AND   style.FieldID = '.$v_stylefield.' AND style.ValueID = '.$v_stylevalue.'
				INNER JOIN productdetails subform ON subform.ProductID = p.ID AND    subform.FieldID = '.$v_subformfield.'
				INNER JOIN inventory i ON i.ProductID = p.ID AND i.WarehouseID = 1
				INNER JOIN status s ON s.ID = p.StatusID 
				WHERE p.ID != '.$productID.' AND subform.ValueID = '.$v_subformvalue.' AND i.SOH > 0 AND p.ParentID = '.$parent_id.' AND  s.Code NOT IN ("XPN","XP")';
			}elseif  ($v_conditionID == 4){
			
			$q=	'SELECT
						p.ID prodID,
						p.Code prodCode,
						p.Name prodName,
						i.SOH
				FROM product p
				INNER JOIN productdetails form ON form.ProductID = p.ID AND form.FieldID = '.$v_formfield.'  AND form.ValueID = '.$v_formvalue.'
				INNER JOIN productdetails style ON style.ProductID = p.ID AND   style.FieldID = '.$v_stylefield.'
				INNER JOIN inventory i ON i.ProductID = p.ID AND i.WarehouseID = 1
				INNER JOIN status s ON s.ID = p.StatusID 
				WHERE p.ID != '.$productID.' AND style.ValueID = '.$v_stylevalue.' AND i.SOH > 0 And p.ParentID = '.$parent_id.'  AND  s.Code NOT IN ("XPN","XP")';
				
			}elseif  ($v_conditionID == 5){
					
			$q=		'SELECT
					p.ID prodID,
					p.Code prodCode,
					p.Name prodName,
					i.SOH
				FROM product p
				INNER JOIN productdetails form ON form.ProductID = p.ID AND form.FieldID = '.$v_formfield.'
				INNER JOIN inventory i ON i.ProductID = p.ID AND i.WarehouseID = 1
				INNER JOIN status s ON s.ID = p.StatusID 
				INNER JOIN productpricing pp on pp.ProductID = p.ID
				WHERE p.ID != '.$productID.' AND form.ValueID = '.$v_formvalue.' AND i.SOH > 0 And pp.UnitPrice <= '.$unit_price.' AND p.ParentID = '.$parent_id.' AND s.Code NOT IN ("XPN","XP")';
				//echo $q;
			}
			$result = $database->execute($q);
			return $result;
	}
	
	function spCheckIfAvailableProdExchange($database,$productID,$warehouseID)
	{
		
			$q = 'SELECT
						p.Code prodCode,
						p.ID prodID,
						p.Name prodName,
						i.SOH SOH
				   FROM product p
				   INNER JOIN inventory i ON i.ProductID = p.ID
				   inner join status s on s.ID = p.StatusID
				   WHERE p.ID = '.$productID.' AND i.WarehouseID = '.$warehouseID.'  AND s.Code NOT IN ("XPN","XP")';
			$result = $database->execute($q);
			return $result;
	}
	
	function spSelectRecordInvCountDetailsByCountTag($database, $vSearch, $txnid, $whid, $locid, $cbolocation)
	{
		if($cbolocation == 0){
			$location = "";
		}else{
			$location = " icd.locationid =".$cbolocation;
		}
		if ($locid == 0){
		$q=		"SELECT
						icd.tpi_counttag tag, p.id prodid, ic.id icid, icd.id icdID, p.code itemcode, p.name itemdesc, 
						ut.name uom, icd.locationid locid, ic.warehouseid, icd.createdqty cqty, lo.Name Location
				FROM inventorycountdetails icd
						INNER JOIN inventorycount ic ON ic.id = icd.inventorycountid
						INNER JOIN product p ON p.id = icd.productid
						INNER JOIN unittype ut ON ut.id = icd.unittypeid
						INNER JOIN location lo ON lo.ID = icd.LocationID
				WHERE ".$location." icd.inventorycountid = ".$txnid." AND icd.tpi_CountTag LIKE '%".$vSearch."%'
				ORDER BY icd.tpi_counttag";
		}elseif ($locid != 0){
		$q=		"SELECT
						icd.tpi_counttag tag, p.id prodid, ic.id icid, icd.id icdID, p.code itemcode, p.name itemdesc, ut.name uom, 
						icd.locationid locid, ic.warehouseid, icd.createdqty cqty, lo.Name Location
				FROM inventorycountdetails icd
						INNER JOIN inventorycount ic ON ic.id = icd.inventorycountid
						INNER JOIN product p ON p.id = icd.productid
						INNER JOIN unittype ut ON ut.id = icd.unittypeid
						INNER JOIN location lo ON lo.ID = icd.LocationID
				WHERE ".$location." icd.inventorycountid = ".$txnid." AND icd.tpi_CountTag LIKE '%".$vSearch."%'
				ORDER BY icd.tpi_counttag";
		}else{
		$q=		"SELECT
						icd.tpi_counttag tag, p.id prodid, ic.id icid, icd.id icdID, p.code itemcode, p.name itemdesc, ut.name uom, 
						icd.locationid locid, ic.warehouseid, icd.createdqty cqty, lo.Name Location
				FROM inventorycountdetails icd
						INNER JOIN inventorycount ic ON ic.id = icd.inventorycountid
						INNER JOIN product p ON p.id = icd.productid
						INNER JOIN unittype ut ON ut.id = icd.unittypeid
						INNER JOIN location lo ON lo.ID = icd.LocationID
				WHERE ".$location." icd.inventorycountid = ".$txnid." AND icd.locationid = ".$cbolocation."
				AND ic.warehouseid = ".$whid." AND icd.tpi_CountTag LIKE '%".$vSearch."%'
				ORDER BY icd.tpi_counttag";
		}
		$result = $database->execute($q);
		return $result;
	}
}
$tpiInventory = new InventoryClass();
?>