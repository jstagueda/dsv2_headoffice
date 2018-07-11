<?php

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	}*/ 
	
	$offset = 0;
	$RPP= 0;
	global $database; 
	(isset($_POST['btnSearch'])) ? $vSearch = $_POST['txtSearch'] : $vSearch = '';

	$rs = $sp->spSelectDR($database, $offset, $RPP, '$vSearch');
	 

		
?>