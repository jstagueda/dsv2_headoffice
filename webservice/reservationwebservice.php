<?php
include "../initialize.php";

$reservationdate = $database->execute("SELECT IFNULL(SettingValue, '1945-01-01 00:00:00.000') SettingValue FROM headofficesetting WHERE TRIM(SettingCode) = 'RESDATE'")->fetch_object()->SettingValue;

$reservationdate = ($reservationdate == '') ? '1945-01-01 00:00:00.000' : $reservationdate;

$reserved = explode(' ', $reservationdate);

$LastModifiedDate = $reserved[0];
$LastModifiedTime = $reserved[1];

$getlink = "http://10.132.50.238:8080/webservices/ws_getreservations.php?user=2&num=10&format=json&LastModifiedDate=$LastModifiedDate&LastModifiedTime=$LastModifiedTime";

$jsonlink = file_get_contents($getlink);
$parsedjson = json_decode($jsonlink, true);

try{
	
	$database->beginTransaction();
	
	$settingarray = array("RESDAY" => "Expiration Days of Reservation", "RESDATE" => "Last Reservation Transaction Date");
	foreach($settingarray as $settingcode => $settingname){
		$query = $database->execute("SELECT ID FROM headofficesetting WHERE TRIM(SettingCode) = '".$settingcode."'");
		if(!$query->num_rows){
			$settingvalue = ($settingcode == "RESDAY") ? '30' : '';
			$database->execute("INSERT INTO headofficesetting(`SettingCode`, `SettingName`, `SettingValue`) VALUES('$settingcode', '$settingname', '$settingvalue')");
		}
	}
	
	$LastModifiedDate = "";
	$ReservationDueDays = $database->execute("SELECT IFNULL(SettingValue, 0) SettingValue FROM headofficesetting WHERE TRIM(SettingCode) = 'RESDAY'")->fetch_object()->SettingValue;
	
	if(count($parsedjson) > 0){
		foreach($parsedjson as $table){
			
			foreach($table as $record => $rec){
				
				//details for reservation header
				if($record == "record"){			
					
					$ReservationNo = $rec['sa_no'];
					$StartDate = $rec['sa_date'];
					$EndDate = date("Y-m-d", strtotime($rec['sa_date']." + $ReservationDueDays DAYS"));
					$BranchCode = $rec['id_branch'];
					$DMSDateUpdated = DATE("Y-m-d h:i:s", strtotime($rec['lastupd_date']));
					$ReservationNo = $rec['sa_no'];
					
					$reservationquery = $database->execute("SELECT * FROM reservation WHERE TRIM(ReservationNo) = '".TRIM($ReservationNo)."'");
					if(!$reservationquery->num_rows){
						$database->execute("INSERT INTO reservation SET
											BranchCode = '$BranchCode',
											ReservationNo = '".TRIM($ReservationNo)."',
											StartDate = '$StartDate',
											EndDate = '$EndDate',
											CreateDate = NOW(),
											LastUpdateDate = NOW(),
											CreateDateDMS = '$DMSDateUpdated',
											Changed = 1");
						echo "<p>Successfully loaded Reservation No. : $ReservationNo</p>";
					}
					
					$LastModifiedDate = DATE("Y-m-d", strtotime($rec['lastupd_date']));
					$LastModifiedTime = DATE("h:i:s", strtotime($rec['lastupd_date']));
					
				}
				
				//details for reservation details
				if($record == "details"){
					
					$ReservationNo = $rec['sa_no'];
					$ProductCode = $rec['id_itemsku'];
					//$RequestedQuantity = $rec['alloc_qty'];
					//$ApprovedQuantity = $rec['picked_qty'];
					$ApprovedQuantity = $rec['alloc_qty'];
					
					//echo "<ul>";
					
					$reservationdetailsquery = $database->execute("SELECT * FROM reservationdetails WHERE TRIM(ReservationNo) = '".TRIM($ReservationNo)."' AND TRIM(ProductCode) = '".TRIM($ProductCode)."'");
					if(!$reservationdetailsquery->num_rows){
					
						$database->execute("INSERT INTO reservationdetails SET
											ReservationNo = '".TRIM($ReservationNo)."',
											ProductCode = '".TRIM($ProductCode)."',
											-- RequestedQuantity = $RequestedQuantity,
											ApprovedQuantity = $ApprovedQuantity,
											RemainingQuantity = $ApprovedQuantity,
											CreateDate = NOW(),
											LastUpdateDate = NOW(),
											Changed = 1");
						
						//echo "<li>Successfully loaded product : $ProductCode</li>";
						
					}
					
					//echo "</ul>";
					
				}
				
			}
			
		}
	}
	
	$database->execute("UPDATE headofficesetting SET SettingValue = '".$LastModifiedDate.' '.$LastModifiedTime."' WHERE TRIM(SettingCode) = 'RESDATE'");
	
	$database->commitTransaction();

}catch(Exception $e){
	
	$database->rollbackTransaction();
	echo "Error : ".$e->getMessage();
	
}

/*echo "<pre>";
print_r($parsedjson);
echo "</pre>";*/
?>