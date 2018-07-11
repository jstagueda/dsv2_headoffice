<?php
global $database;
require_once "../initialize.php";
$vSearch = $_POST['txtSubstitute'];
//$tmpPID = $_POST['hProdid2'];


	$rsGetProductList = $sp->spSelectProductListSubs($database, 3, 1, $vSearch);
        echo "<ul>";
	if($rsGetProductList->num_rows){

		while($row = $rsGetProductList->fetch_object())
		{
			$product = $row->ID.'_'.$row->Name.'_'.$row->Code;
			echo "<li id=\"". str_replace('"','',$product) . "\"><div align='left'><strong>$row->Code - $row->Name</strong></div></li> ";

		}

	}else{
            $product = '0__';
            echo "<li id='$product'><div align='left'><strong>No result found.</strong></div></li> ";
        }
        echo "</ul>";
        die();
?>