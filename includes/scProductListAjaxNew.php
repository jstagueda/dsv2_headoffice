<?php
/*

Modified By : Benjie Timogan | 09-25-2014
NEWDS-1259 Single Line Promo


*/
	global $database;
	require_once "../initialize.php";
	
	$index = $_GET['index'];
	$vSearch = $_POST['txtProdCode'.$index];

	/*$rsGetProductList = $sp->spSelectProductListByLevelID($database, 3, $vSearch);*/
	
	$query = "SELECT p.ID, p.Code, p.Name, pp.PMGID, pmg.code pmgcode, pp.UnitPrice unitprice
			  from product p
			  left join productpricing pp on pp.ProductID = p.ID
			  left join tpi_pmg pmg on pmg.ID = pp.pmgid
			  left join status sta on p.StatusID = sta.ID
			  where
			  sta.Code NOT IN('XP','XPN') AND sta.ID NOT IN ('18','19') AND p.`ProductLevelID` = 3 and pmg.Code IN ('CFT','NCFT','CPI') and p.Code like '%$vSearch%' or p.Name like '%$vSearch%'
			  order by p.Name desc limit 5";
	
	$rsGetProductList = $database->execute($query);
	
	if($rsGetProductList->num_rows):
		echo "<ul>";
		while($row = $rsGetProductList->fetch_object()):
			$product = $row->ID.'_'.$row->Name.'_'.$row->Code.'_'.$row->pmgcode.'_'.$row->PMGID.'_'.$row->unitprice;
			echo "<li id=\"". str_replace('"','',$product) . "\" ><div align='left'><strong>$row->Code - $row->Name</strong></div></li> ";
		endwhile;
		echo "</ul>";
	endif;
?>