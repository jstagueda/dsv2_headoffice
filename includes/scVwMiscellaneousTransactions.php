<?php

	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	
	$offset = 0;
	$RPP= 10;
	(isset($_POST['btnSearch'])) ? $vSearch = $_POST['txtSearch'] : $vSearch = '';
	$rs = $sp->spSelectAdjustment($database, $offset, $RPP, $vSearch);
		
?>