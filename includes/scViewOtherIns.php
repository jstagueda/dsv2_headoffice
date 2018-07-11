<?php

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	}*/ 
	global $database;
	$offset = 0;
	$RPP= 0;
	(isset($_POST['btnSearch'])) ? $vSearch = $_POST['txtSearch'] : $vSearch = '';

	$rs = $sp->spSelectInventoryIn($database,$offset, $RPP, $vSearch);
	 

		
?>