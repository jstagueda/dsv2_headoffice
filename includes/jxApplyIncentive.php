<?php
require_once "../initialize.php";
global $database;

$promoID = $_GET['promo'];
$qty	 = $_GET['qty'];
$flag = $_GET['flag'];
echo $qty;
$promo = new Promo();
if($flag == 0)
{
	$promo->ApplyIncentives($database,$promoID,$qty);
}

echo "ApplyIncentives($promoID,$qty)";
?>