<?php
	global $database;
	$sessionID = session_id();
	
	$rsGetPromoList = $sp->spSelectChangePromoPopup($database, $sessionID);

?>