<?php
    require_once "../initialize.php";
   global $database;
	$ibmID = $_GET['IBMID'];
	unset($_SESSION['CustomerIDProdExchange']);
	
	$_SESSION['CustomerIDProdExchange'] = $ibmID;
	
	//echo $_SESSION['CustomerIDProdExchange'];
?>