<?php
    require_once "../initialize.php";
   global $database;
	$ibmID = $_GET['IBMID'];
	unset($_SESSION['IBMIDOfficialReceipt']);
	
	$_SESSION['IBMIDOfficialReceipt'] = $ibmID;
	

?>