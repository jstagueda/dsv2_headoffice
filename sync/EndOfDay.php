<?php 

include "../initialize.php";

$BranchID = $_POST['BranchID'];
$BranchURL = $_POST['BranchURL']."/sync/EndOfDayWebservice.php";

$ParseURL = json_decode(file_get_contents($BranchURL));
$result = array();

/*echo "<pre>";
print_r($ParseURL);
echo "<pre>";*/

try{
	$database->beginTransaction();	
	$Tables = array();
	
	if(count($ParseURL) > 0){
		foreach($ParseURL as $table => $details){
			
			$Tables[] = $table;
			
			foreach($details as $values){
				$data = array();
				
				//for new record from branch
				foreach($values as $field => $val){
					if($field == "Changed"){
						$data[$table][] = $field."=0";
					}else{
						$data[$table][] = $field."='$val'";
					}
				}
				
				$setdetails = implode(',', $data[$table]);
				
				//for existing record checker
				//========================================================================
				
				//reservation
				if($table == "reservation"){
					$query = $database->execute("SELECT * FROM $table WHERE TRIM(BranchCode) = '".$values->BranchCode."' AND TRIM(ReservationNo) = '".$values->ReservationNo."'");
					if($query->num_rows){
						$database->execute("UPDATE $table SET $setdetails WHERE TRIM(BranchCode) = '".$values->BranchCode."' AND TRIM(ReservationNo) = '".$values->ReservationNo."'");
					}else{
						$database->execute("INSERT INTO $table SET $setdetails");
					}
				}
				
				//reservationdetails
				if($table == "reservationdetails"){
					$query = $database->execute("SELECT * FROM $table WHERE TRIM(ProductCode) = '".$values->ProductCode."' AND TRIM(ReservationNo) = '".$values->ReservationNo."'");
					if($query->num_rows){
						$database->execute("UPDATE $table SET $setdetails WHERE TRIM(ProductCode) = '".$values->ProductCode."' AND TRIM(ReservationNo) = '".$values->ReservationNo."'");
					}else{
						$database->execute("INSERT INTO $table SET $setdetails");
					}
				}
				
				//reservationdeliverydetails
				if($table == "reservationdeliverydetails"){
					$query = $database->execute("SELECT * FROM $table WHERE TRIM(ProductCode) = '".$values->ProductCode."' AND TRIM(ReservationNo) = '".$values->ReservationNo."' AND BranchID = $BranchID AND TRIM(DeliveryNo) = '".$values->DeliveryNo."'");
					if($query->num_rows){
						$database->execute("UPDATE $table SET $setdetails WHERE TRIM(ProductCode) = '".$values->ProductCode."' AND TRIM(ReservationNo) = '".$values->ReservationNo."' AND BranchID = $BranchID AND TRIM(DeliveryNo) = '".$values->DeliveryNo."'");
					}else{
						$database->execute("INSERT INTO $table SET $setdetails");
					}
				}
				
				//igs item reservation
				if($table == "igsreservationdetails" || $table == "igsreservation"){
					$query = $database->execute("SELECT * FROM $table WHERE TRIM(HOGeneralID) = '".$values->HOGeneralID."'");
					if($query->num_rows){
						$database->execute("UPDATE $table SET $setdetails WHERE TRIM(HOGeneralID) = '".$values->HOGeneralID."'");
					}else{
						$database->execute("INSET INTO $table SET $setdetails");
					}
				}
				
				//========================================================================		
			}	
		}
	}
	
	$database->commitTransaction();
	$result['Tables'] = implode(',', $Tables);
	$result['Success'] = 1;
	
}catch(Exception $e){
	
	$result['Success'] = 0;
	
}

die(json_encode($result));
?>