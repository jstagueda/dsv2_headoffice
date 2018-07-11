<?php

	require_once "../initialize.php";
	global $database;
	
	$index = $_GET['index'];
	$vSearch = $_POST['txtEProdCode'.$index];
	
	/*$rsGetProductList = $sp->spSelectProductListByLevelID($database, 3, $vSearch);*/
	$query = "SELECT p.ID, p.Code, p.Name, pp.PMGID, ifnull(pp.UnitPrice, 0.00) UnitPrice, pmg.code pmgode
			  from product p
			  left join productpricing pp on pp.ProductID = p.ID
			  left join tpi_pmg pmg on pmg.ID = pp.pmgid
			  where
			  p.`ProductLevelID` = 3 and p.Code like '%$vSearch%' or p.Name like '%$vSearch%'
			  order by p.Name desc limit 5";
	$rsGetProductList = $database->execute($query);
	
	if($rsGetProductList->num_rows)
	{
		echo "<ul>";
		while($row = $rsGetProductList->fetch_object())
		{
			$product = $row->ID.'_'.$row->Name.'_'.$row->UnitPrice.'_'.$row->PMGID.'_'.$row->Code.'_'.$row->pmgode;
			echo "<li id=\"". str_replace('"','',$product) . "\"><div align='left'><strong>$row->Code - $row->Name</strong></div></li> ";

		}
		echo "</ul>";
	}
?>
