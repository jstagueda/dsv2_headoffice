<?php 

function Reservation($BranchID, $DateStart, $DateEnd, $Search, $page, $total, $istotal){

	global $database;
	
	$start = ($page > 1) ? ($page - 1) * $total : 0;
	$limit = ($istotal) ? "" : "LIMIT $start, $total";
	
	$branchrange = ($BranchID == 0) ? "" : "AND branch.ID = $BranchID"; 
	
	return $database->execute("SELECT
							reservation.ReservationNo,
							reservation.BranchCode,
							DATE_FORMAT(reservation.StartDate, '%M %d, %Y') StartDate,
							DATE_FORMAT(reservation.EndDate, '%M %d, %Y') EndDate,
							DATE_FORMAT(reservation.EnrollmentDate, '%M %d, %Y') TransactionDate,
							IF(reservation.IsClosed = 1, 'Close', 'Open') ReservationStatus
						FROM reservation 
						INNER JOIN branch ON branch.Code = reservation.BranchCode
						WHERE DATE(reservation.EnrollmentDate) BETWEEN '$DateStart' AND '$DateEnd'
						AND (TRIM(reservation.ReservationNo) LIKE '%$Search%')
						$branchrange
						ORDER BY reservation.EnrollmentDate DESC
						$limit");
	
}

?>