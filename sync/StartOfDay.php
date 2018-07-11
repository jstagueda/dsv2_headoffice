<?php 

include "../initialize.php";

$BranchID = $_POST['BranchID'];
$BranchCode = $database->execute("SELECT TRIM(Code) BranchCode FROM branch WHERE ID = $BranchID")->fetch_object()->BranchCode;

$queries['reservation'] = "SELECT * FROM reservation WHERE `Changed` = 1 AND TRIM(BranchCode) = '".$BranchCode."'";
$queries['reservationdetails'] = "SELECT * FROM reservationdetails WHERE `Changed` = 1 
										AND TRIM(ReservationNo) IN (SELECT ReservationNo FROM reservation WHERE TRIM(BranchCode) = '".$BranchCode."')";

$data = array();

foreach($queries as $key => $val){
	
	$query = $database->execute($val);
	while($res = $query->fetch_object()){
		$data[$key][] = $res;
	}
	
}

echo json_encode($data);

$update_queries['reservation'] = "UPDATE reservation SET `Changed` = 0 WHERE `Changed` = 1 AND TRIM(BranchCode) = '".$BranchCode."'";
$update_queries['reservationdetails'] = "UPDATE reservationdetails SET `Changed` = 0 WHERE `Changed` = 1
									AND TRIM(ReservationNo) IN (SELECT TRIM(ReservationNo) FROM reservation WHERE TRIM(BranchCode) = '".$BranchCode."')";

foreach($update_queries as $key){
	$database->execute($key);
}

?>