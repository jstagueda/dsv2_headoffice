<?php
	global $database;
	require_once "../initialize.php";
	
	$isworn = $_GET["isWorn"];
	if ($isworn == 1)
	{
		$vSearch = $_POST['txtWornItem'];		
	}
	else
	{
		$vSearch = $_POST['txtHeroedItem'];
	}
	
	$rsGetProductList = $sp->spSelectProductListByLevelID($database, 3, $vSearch);
	
	if($rsGetProductList->num_rows)
	{
		echo "<ul>";
		while($row = $rsGetProductList->fetch_object())
		{
			$product = $row->ID.'_'.$row->Name.'_'.$row->Code;
			echo "<li id=\"". str_replace('"','',$product) . "\"><div align='left'><strong>$row->Code - $row->Name</strong></div></li> ";
		}
		echo "</ul>";
	}
?>
