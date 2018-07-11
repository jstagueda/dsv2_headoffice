<?php
	require_once("../initialize.php");
	global $database;
	
	$unlock = $sp->spUnlockLockedTransactions($database);
?>
