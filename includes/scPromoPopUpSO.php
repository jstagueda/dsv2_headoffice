<?php
	global $database;
	$sessionID = session_id();
	
	$rsGetPromoList = $sp->spSelectPromoPopUp($database, $sessionID);

?>