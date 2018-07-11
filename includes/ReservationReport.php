<?php 

include IN_PATH."pagination.php";

function ReservationDetails($datefrom, $dateto, $search, $page, $total, $istotal, $branchID){
	
	global $database;
	
	$start = ($page > 1) ? ($page - 1) * $total : 0;
	$limit = ($istotal) ? "" : "LIMIT $start, $total";
	
	$query = $database->execute("SELECT 
									b.ID BranchID,
									TRIM(r.ReservationNo) ReservationNo,
									TRIM(p.Code) ProductCode,
									p.Name ProductName,
									DATE_FORMAT(r.EnrollmentDate, '%m/%d/%Y') CommitmentDate,
									rd.ApprovedQuantity,
									rd.LoadedQuantity
								FROM reservation r 
								INNER JOIN branch b ON TRIM(b.Code) = TRIM(r.BranchCode)
								INNER JOIN reservationdetails rd ON TRIM(rd.ReservationNo) = TRIM(r.ReservationNo)
								INNER JOIN product p ON TRIM(p.Code) = TRIM(rd.ProductCode)
								LEFT JOIN reservationdeliverydetails rdd ON TRIM(rdd.ReservationNo) = TRIM(r.ReservationNo)
									AND TRIM(rd.ProductCode) = TRIM(rdd.ProductCode)
									AND rdd.BranchID = b.ID
								WHERE DATE(r.EnrollmentDate) BETWEEN '$datefrom' AND '$dateto'
								AND (TRIM(r.ReservationNo) LIKE '%$search%'
									OR TRIM(rd.ProductCode) LIKE '%$search%'
									OR TRIM(p.Name) LIKE '%$search%'
									OR TRIM(rdd.DeliveryNo) LIKE '%$search%')
								GROUP BY rd.ProductCode, r.ReservationNo
								ORDER BY r.ReservationNo DESC, p.Name ASC
								$limit");
	
	return $query;
	
}

function getDeliveryNo($ReservationNo, $ProductCode, $branchID){
	
	global $database;
	
	$query = $database->execute("SELECT 
									TRIM(DeliveryNo) DeliveryNo,
									DATE_FORMAT(EnrollmentDate,'%m/%d/%Y') DeliveryDate
								FROM reservationdeliverydetails 
								WHERE TRIM(ReservationNo) = '".TRIM($ReservationNo)."' 
								AND TRIM(ProductCode) = '".TRIM($ProductCode)."'
								AND BranchID = $branchID");
	
	$delivery = array();
	
	if($query->num_rows){
		while($res = $query->fetch_object()){
			$delivery['DeliveryNo'][] = $res->DeliveryNo;
			$delivery['DeliveryDate'][] = $res->DeliveryDate;
		}
	}else{
		$delivery['DeliveryNo'][] = "";
		$delivery['DeliveryDate'][] = "";
	}
	
	return $delivery;
	
}

function getBranchDetails($branchID){
	
	global $database;
	
	$query = $database->execute("SELECT CONCAT(TRIM(Code), ' - ', Name) BranchName FROM branch WHERE ID = $branchID")->fetch_object()->BranchName;
	
	return $query;
	
}

?>