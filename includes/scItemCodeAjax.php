<?php


	global $database;
	require_once "../initialize.php";
	
	$index = $_GET['index'];
	$txnid = $_GET['tid'];
	$whid = $_GET['whid'];
	$locid = $_GET['locid'];

	$vSearch = $_POST['txtItemCode'.$index];

	$rsGetProductList = $sp->spSelectRecordInvCountDetailsByItemCode($database, $vSearch, $txnid, $whid, $locid);
	
	if($rsGetProductList->num_rows){
		echo "<ul>";
		while($row = $rsGetProductList->fetch_object())
		{
			//$product = $row->tag.'_'.$row->prodid.'_'.$row->itemcode.'_'.$row->itemdesc.'_'.$row->cqty.'_'.$row->Location.'_'.$row->icid.'_'.$row->icdID;
			$product = $row->tag.'_'.$row->prodid.'_'.$row->itemcode.'_'.$row->itemdesc.'_'.$row->cqty.'_'.$row->Location.'_'.$row->icid.'_'.$row->icdID.'_'.$row->locid;
			echo "<li id=\"". str_replace('"','',$product) . "\"><div align='left'><strong>$row->itemcode - $row->itemdesc</strong></div></li> ";

		}
		echo "</ul>";
	}
?>
