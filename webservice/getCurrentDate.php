<?php 
	
include "../initialize.php";

$reservationdate = $database->execute("SELECT IFNULL(SettingValue, '1945-01-01 00:00:00.000') SettingValue FROM headofficesetting WHERE TRIM(SettingCode) = 'RESDATE'")->fetch_object()->SettingValue;

$reservationdate = ($reservationdate == '') ? '1945-01-01 00:00:00.000' : $reservationdate;

$date = array("LastModifiedDate" => $reservationdate);

echo json_encode($date);

?>