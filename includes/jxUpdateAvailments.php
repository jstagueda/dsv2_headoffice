<?php
require_once "../initialize.php";
global $database;

$promoID = $_GET['promo'];
$qty	 = $_GET['qty'];
$sessionID= session_id();
$rsUpdateAvailment = $sp->spUpdateMaxAvailmentbyPromoID($database,$promoID,$qty,$sessionID);

?>