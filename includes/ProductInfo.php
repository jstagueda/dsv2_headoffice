<?php 
	
	function ProductList($search = "", $page, $total, $istotal){
		
		global $database;
		
		$start = ($page > 1) ? ($page - 1) * $total : 0;
		$limit = ($istotal) ? "" : " LIMIT $start, $total";
		
		$query = $database->execute("SELECT 
								`Name`, 
								`Code`, 
								ID 
							FROM product
							WHERE `Name` LIKE '%".$search."%'
							OR `Code` LIKE '%".$search."'
							ORDER BY `Name`
							$limit");
		return $query;
		
	}
	
	function ProductDetails($ProductID){
		
		global $database;
		$query = $database->execute("SELECT
								p.ID ProductID,
								TRIM(p.Code) ProductCode,
								p.Name ProductName,
								p.ShortName,
								pmg.Name ProductClass,
								pt.Name ProductType,
								pl.Name ProductLine,
								uom.Name UOM,
								pc.UnitCost,
								pp.UnitPrice,
								p.LastPODate,
								p.LaunchDate
							FROM product p
							INNER JOIN productpricing pp ON pp.ProductID = p.ID
							INNER JOIN tpi_pmg pmg ON pmg.ID = pp.PMGID
							INNER JOIN producttype pt ON pt.ID = p.ProductTypeID
							INNER JOIN productcost pc ON pc.ProductID = p.ID
							LEFT JOIN product pl ON pl.ID = p.ParentID
							INNER JOIN unittype uom ON uom.ID = p.UnitTypeID
							WHERE p.ID = $ProductID");
		return $query;
	}
	
	$stylequery = $sp->spSelectPStyle($database);
	$subformquery = $sp->spSelectPSubForm($database);
	$brandquery = $sp->spSelectBrand($database);
	$colorquery = $sp->spSelectPColor($database);
	
?>