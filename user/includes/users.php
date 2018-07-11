<?php 

	function userlist($page, $total, $istotal){
	
		global $database;
		
		$start = ($page > 1) ? ($page - 1) * $total : 0;
		$limit = ($istotal) ? "" : "LIMIT $start, $total";
		
		$query = $database->execute("SELECT
				u.UserName,
				u.LoginName,
				ut.Name UserType,
				DATE_FORMAT(e.DateHired, '%m/%d/%Y') DateRegistered,
				IFNULL(lh.Status, 'Active') `Status`,
				IFNULL(DATE_FORMAT(ulh.LoginTime, '%m/%d/%Y %h:%i:%s %p'), 'N/A') LastLoginTime,
				u.ID UserID
				FROM `user` u
				INNER JOIN usertype ut ON ut.ID = u.UserTypeID
				LEFT JOIN loginhistory lh ON lh.UserID = u.ID
					AND lh.ID = (SELECT MAX(ID) FROM loginhistory WHERE UserID = u.ID)
				LEFT JOIN userloginhistory ulh ON ulh.UserID = u.ID
					AND ulh.ID = (SELECT MAX(ID) FROM userloginhistory WHERE UserID = u.ID)
				INNER JOIN employee e ON e.ID = u.EmployeeID
				ORDER BY IFNULL(DATE_FORMAT(ulh.LoginTime, '%m/%d/%Y %h:%i:%s %p'), 'N/A') DESC
				$limit");
				
		return $query;
		
	}
	
	function userstatuslogs($UserID){
		
		global $database;
		
		$query = $database->execute("SELECT 
							IFNULL(lh.Status, 'Active') UserStatus,
							DATE_FORMAT(IFNULL(lh.HistoryDate, u.EnrollmentDate), '%M %d, %Y %h:%i:%s %p') UserStatusDate,
							lh.CreateBy
							FROM `user` u 
							LEFT JOIN loginhistory lh ON lh.UserID = u.ID
							WHERE u.ID = $UserID
							ORDER BY lh.ID DESC");
		
		return $query;
		
	}
	
	function UpdatedBy($userid){
		
		global $database;
		
		$user = $database->execute("SELECT EmployeeID FROM user WHERE ID = $userid")->fetch_object();
		
		$query = $database->execute("SELECT * FROM employee WHERE ID = ".$user->EmployeeID."");
		if($query->num_rows){
			$res = $query->fetch_object();
			return $res->FirstName." ".$res->LastName;
		}else{
			return "System";
		}
		
	}

?>