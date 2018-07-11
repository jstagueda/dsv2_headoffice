<?php
	
	require_once "../initialize.php";
	global $database;

	$range = $_GET['range'];
	//$index = $_GET['index'];
	//$vSearch = $_POST['txtCriteria'.$index];
	$vSearch = $_POST['txtCriteria'];
	
 	//$rsprodselection_ajax = $sp->spSelectProductListByLevelID($database, $range, $vSearch);
	$query = "select
			p.ID,
			p.Code,
			p.Name,
			ifnull(pp.UnitPrice, 0.00) UnitPrice,
			pp.PMGID,
			pmg.code pmgode
			from product p
				left join productpricing pp on pp.ProductID = p.ID
				left join tpi_pmg pmg on pmg.ID = pp.pmgid
			where
			p.ProductLevelID = $range and p.Code like '%$vSearch%' or p.Name like '%$vSearch%'
			order by p.Name desc
			Limit 10";
			
	 $rsprodselection_ajax = $database->execute($query);
	if($rsprodselection_ajax->num_rows)
	{
		echo "<ul> ";
		while($row = $rsprodselection_ajax->fetch_object())
		{
			$product = $row->ID.'_'.$row->Name.'_'.$row->Code.'_'.$row->pmgode;
			echo "
        			<li id=\"". str_replace('"','',$product) . "\" ><div align='left'><strong>$row->Code - $row->Name</strong></div></li>					
				  ";
	  	}
		echo "</ul>";
	}
?>