<?php

	include('../../initialize.php');
    include('../config.php');
	$success_logs = LOGS_PATH.'process.log';
	tpi_file_truncate($success_logs);
	// If it's going to need the database, then it's 
	// probably smart to require it before we start.
	require_once(CS_PATH.DS.'dbconnection.php');
	global $database;
	
	$dealerUpload = new DealerUpload();
	$maindealer = $dealerUpload->maindealerUpload($database);
	//$cancelSI->CancelSalesInvoice($database,241808,11401);
?>