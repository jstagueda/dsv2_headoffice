<?php
/*
***********************************
**  Author by: Gino C. Leabres  ***
**  4.15.2013                  ***
**  ginophp@yahoo.com           ***
***********************************
*/
	include('../../initialize.php');
    include('../config.php');
	
	$success_logs = LOGS_PATH.'process.log';
	global $database;
	//tpi_file_truncate($success_logs);
	//truncate employeetmp;
	$database->execute("truncate employeetmp");
	//Load Data Employee.csv to emoployeetmp table
	$database->execute("LOAD DATA LOCAL INFILE'".data_upload.employee."'
					INTO TABLE employeetmp
					FIELDS TERMINATED BY ','
					Optionally enclosed by '\"'
					LINES TERMINATED BY '\n'
					Ignore 1 lines
					(BranchCode, LastName ,FirstName ,MiddleName,@column5,@column6,Department,Position)
					SET
					Birthdate = STR_TO_DATE(TRIM(@column5),'%d/%m/%Y'),
					Datehired = STR_TO_DATE(TRIM(@column6),'%d/%m/%Y')");
		tpi_error_log("Inserted employee to tmp table Successful <br />",$success_logs);
	//insert to employee table
				$result_employee = $database->execute(" SELECT CONCAT(LEFT(a.FirstName, 1), '-', a.LastName) CODE, a.LastName, a.FirstName, a.MiddleName, a.Birthdate, a.Datehired, b.ID employeetypeID,
														c.ID departmentID, 1 statusID,
														b.ID BranchID, d.ID PositionID
														FROM employeetmp a
														LEFT JOIN employeetype b ON b.Code = a.BranchCode
														LEFT JOIN department c ON a.Department =  c.Name
														LEFT JOIN POSITION d ON d.Name = a.Position");
				if($result_employee->num_rows){
					while($row = $result_employee->fetch_object()){
					
							if($row->PosistionID == ""){
								$PosistionID = 0;
							}else{
								$PosistionID = $row->PosistionID;
							}
							
							$database->execute("INSERT INTO employee 
									(CODE, LastName,FirstName,MiddleName, Birthdate, DateHired, EmployeeTypeID, DepartmentID,StatusID,EnrollmentDate, LastModifiedDate, Changed)
									VALUES
									('".$row->CODE."','".$row->LastName."','".$row->FirstName."', '".$row->MiddleName."', '".$row->Birthdate."', '".$row->Datehired."', ".$row->employeetypeID.",
									".$row->departmentID.", ".$row->statusID.", NOW(),NOW(), 1)");
							tpi_error_log("Inserted employee ".$row->LastName.", ".$row->FirstName." ".$row->MiddleName." Successful <br />",$success_logs);		
							$last_id = $database->execute("SELECT last_insert_id() lastID");
							while($id = $last_id->fetch_object()){

								$last_insert_id = $id->lastID;
								
							}
							$database->execute("insert into remployeebranch 
							
												(BranchID, EmployeeID, StatusID, EnrollmentDate, LastModifieddate, Changed, Type)
												VALUES
												(".$row->BranchID.", ".$last_insert_id.", 1, NOW(), NOW(), 1, 1)
											   ");
											   
							$database->execute("SET FOREIGN_KEY_CHECKS=0");
						    $database->execute("INSERT INTO remployeeposition (EmployeeID, PositionID, StatusID, 
											   EnrollmentDate, LastModifiedDate, Changed)
											   VALUES
											   (".$last_insert_id.", ".$PosistionID.", 1, NOW(), NOW(), 1)");
					}
				}
				
?>
