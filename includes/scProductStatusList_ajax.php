<?php
	global $database;
	require_once "../initialize.php";
	
	
	$vSearch = $_POST['txtProdCode1'];
	
	if(isset($_GET['plid']))
	  {
		$tmpPlid = $_GET['plid'];
	  }
	  else
	  {
		$tmpPlid = 0;
	  }

	$rsGetProductList = $sp->spSelectProdlistforProdRepStat($database, 3, 1, $vSearch);
	
	if($rsGetProductList->num_rows)
	{
		echo "<ul>";
		while($row = $rsGetProductList->fetch_object())
		{
			$product = $row->ID.'_'.$row->Name;
			echo "<li id=\"". str_replace('"','',$product) . "\"><div align='left'><strong>$row->Code - $row->Name</strong></div></li> ";

		}
		echo "</ul>";
	}
?>