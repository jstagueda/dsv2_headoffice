<?php
class BranchUpload
{
	private $database;

	//main method 
	public function minbranchUpload($database)
	{
					$success_logs = LOGS_PATH.'process.log';
					tpi_file_truncate($success_logs);

					$this->database = $database;
					
					$database->execute("SET FOREIGN_KEY_CHECKS = 0");
					//Drop Tmp Table if Exist;
					$Droptmp = $this->spDroptmp();
					
					//create Tmp Table
					$CreateTmpTable = $this->spCreateTmpTable();
					
					//delete sales group and area operation group of department tables.
					$delete = "DELETE FROM department WHERE DepartmentLevelID in (4,5)";
					$this->database->execute($delete);
								
					//Load CSV to tmp
					$LoadInvDataToTempTable	 = $this->spLoadInvDataToTempTable();
                    tpi_error_log("Inserted Branch to Temporary table.<br />",$success_logs);                    
					//insert tmp_branch to branchtable
					$insertbranch = $this->spinsertbranch();
                    tpi_error_log("Inserted Branch to Main table.<br />",$success_logs);                    
					
					//insert Sales Group to Department
					$insertDepartmentSG = $this->spinsertDepartmentSG();
					tpi_error_log("Inserted Sales Group to Department table.<br />",$success_logs);
					//insert Area Operation Group to Department
					$insertDepartmentAOG = $this->spinsertDepartmentAOG();
					tpi_error_log("Inserted Area Operation Group to Department table.<br />",$success_logs);
                    /*******************************************************************/        
					
					//Insert sales group to branch department
					$InsertbranchdepartmentSG = $this->spInsertbranchdepartmentSG();
					tpi_error_log("Inserted Branch Department to Branch Department table.<br />",$success_logs);
                                        
					// Insert Area Operation Group to department
					$InsertbranchdepartmentAOG = $this->spInsertbranchdepartmentAOG();
                    tpi_error_log("Inserted Area Operation Group to Branch Department table.<br />",$success_logs);                    
					//Insert Sales Director to Employee
					$InsertemployeeSD = $this->spInsertemployeeSD();
                    tpi_error_log("Inserted Sales Director to Employee table.<br />",$success_logs);                    
					//Insert Sales Operation Director to Employee
					$InsertemployeeAOD = $this->spInsertemployeeAOD();
					tpi_error_log("Inserted Area Operation Director to Employee table.<br />",$success_logs);

					//insert employee Sales Director to remployeeposition
					$InsertremployeepositionSD = $this->spInsertremployeepositionSD();
					tpi_error_log("Inserted Sales Director Position to remployeeposition table.<br />",$success_logs);
					
					//insert employee Area Operation Director to remployeeposition
					$InsertremployeepositionAOD = $this->spInsertremployeepositionAOD();
					tpi_error_log("Inserted Area Operation Director Position to remployeeposition table.<br />",$success_logs);
					//Insert Sales Director and Area Operation Director to tremployeebranch
					$InsertremployeebranchSDandAOD = $this->spInsertremployeebranchSDandAOD();
					
					//Insert Branch Size to branchdetails
					$InsertbranchSize = $this->spInsertbranchdetails(); 
					tpi_error_log("Inserted Branch Details to branchdetails table.<br />",$success_logs);		
					$Updatebranchdetails = $this->spUpdatebranchdetails();
					tpi_error_log("Upload Successful<br />",$success_logs);
					//echo "SuccessFul!.";
					
	}
	
	/*stored procedures*/
	public function spDroptmp()
	{
		$sp = "DROP TABLE IF EXISTS `tmp`;";
		$rs = $this->database->execute($sp);
		return $rs;
	} 
        public function spCreateTmpTable()
        {
            $sp = "
                        CREATE TABLE `tmp` (
                        `id` int(11) DEFAULT NULL,
                        `branch_code` varchar(50) DEFAULT NULL,
                        `branch_name` varchar(50) DEFAULT NULL,
                        `strt_add` varchar(50) DEFAULT NULL,
                        `region` varbinary(50) DEFAULT NULL,
                        `zip_code` varchar(50) DEFAULT NULL,
                        `telno1` varchar(50) DEFAULT NULL,
                        `telno2` varchar(50) DEFAULT NULL,
                        `telno3` varchar(50) DEFAULT NULL,
                        `telno4` varchar(50) DEFAULT NULL,
                        `voip` varchar(50) DEFAULT NULL,
                        `voip1` varchar(50) DEFAULT NULL,
                        `cpno1` tinytext,
                        `cpno2` text,
                        `cpno3` tinytext,
                        `branch_type` varchar(50) DEFAULT NULL,
                        `branch_size` varchar(50) DEFAULT NULL,
                        `contact_person` varchar(50) DEFAULT NULL,
                        `sales_director` varchar(50) DEFAULT NULL,
                        `AO_director` varchar(50) DEFAULT NULL,
                        `sales_group` varchar(50) DEFAULT NULL,
                        `AO_group` varchar(50) DEFAULT NULL,
                        `tin` varchar(50) DEFAULT NULL,
                        `permit_no` varchar(50) DEFAULT NULL,
                        `server_sn` varchar(50) DEFAULT NULL,
                        `bank` varchar(50) DEFAULT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;   
                       

                 ";
            $rs = $this->database->execute($sp);
            return $rs;
            
        }
        
	public function spLoadInvDataToTempTable()
	{
			$sp = "
					LOAD DATA LOCAL INFILE'".data_upload.branch."'
					INTO TABLE tmp
					FIELDS TERMINATED BY ','
					Optionally enclosed by '\"'
					LINES TERMINATED BY '\n'
					Ignore 1 lines;	
				"; 	
			
			$rs = $this->database->execute($sp);
			return $rs;	
	}
	
	public function spinsertbranch()
	{
			$sp = "
					insert into branch ( Code,Name,StreetAdd,AreaID,ZipCode,TelNo1,TelNo2,TelNo3,FaxNo, 
										 TIN,PermitNo,ServerSN,BranchTypeID,OfficeTypeID,EmployeeID, 
										 StatusID,EnrollmentDate,LastModifiedDate,Changed
										)
					select 	tmp.branch_code Code,tmp.branch_name Name,tmp.strt_add StreetAdd,tmp.region Area,tmp.zip_code ZipCode,
							tmp.telno1,tmp.telno2,tmp.telno3,tmp.telno4,tmp.tin,tmp.permit_no,tmp.server_sn,bt.ID BranchTypeId,
							2 OfficeTypeID,NULL EmployeeID,1 StatusID,now() EnrollmentDate,
							now() LastModifiedDate,0 Changed
					from tmp tmp
					LEFT join branchtype bt on tmp.branch_type = bt.Name;	
				"; 	
				
		$rs = $this->database->execute($sp);
		return $rs;	
	}

	public function spinsertDepartmentSG()
	{
			$sp = "
					INSERT INTO department (CODE, NAME, DepartmentLevelID, ParentID, Lt, Rt, StatusID, EnrollmentDate, LastModifiedDate)
					
					SELECT CONCAT('SG', '-' , SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(sales_group), ' ', 2), ' ', -1)) AS CODE,
								tmp.sales_group,
								dl.ID DepartmentLevelID,
								0 ParentID,
								0 lt,
								0 rt,
								1 StatusID,
								NOW() EnrollmentDate,
								NOW() LastModifiedDate
					FROM tmp tmp
					INNER JOIN departmentlevel dl ON dl.Code = 'SG'
					WHERE sales_group != ''
					GROUP BY sales_group;
				"; 	
				
		$rs = $this->database->execute($sp);
		return $rs;	
	}	

	public function spinsertDepartmentAOG()
	{
			$sp = "
					INSERT INTO department (CODE, NAME, DepartmentLevelID, ParentID, Lt, Rt, StatusID, EnrollmentDate, LastModifiedDate)
					SELECT CONCAT('AOG', '-', 
								SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(tmp.AO_group), ' ', 2), ' ', -1)) AS CODE,
								tmp.AO_group,
								dl.ID DepartmentLevelID,
								0 ParentID,
								0 lt,
								0 rt,
								1 StatusID,
								NOW() EnrollmentDate,
								NOW() LastModifiedDate
					FROM tmp tmp
					INNER JOIN departmentlevel dl ON dl.Code = 'AOG'
					WHERE AO_group != ''
					GROUP BY AO_group;	
				"; 	
			
		$rs = $this->database->execute($sp);
		return $rs;	
	}		

	public function spInsertbranchdepartmentSG()
	{
			$sp = "
					 insert into branchdepartment (BranchID, DepartmentID, Type, StatusID, Changed)

					 SELECT br.ID BranchID, dp.ID DepartmentID, 1 TYPE, 1 StatusID, 1 CHANGED FROM tmp tmp
					 LEFT JOIN branch br ON br.Code = tmp.branch_code
					 LEFT JOIN department dp ON dp.Name = tmp.sales_group
					 WHERE dp.ID != ''
					 GROUP BY br.ID;
				"; 	
			
		$rs = $this->database->execute($sp);
		return $rs;	
	}

	public function spInsertbranchdepartmentAOG()
	{
			$sp = "
					insert into branchdepartment (BranchID, DepartmentID, Type, StatusID, Changed)

					SELECT br.ID, dp.ID, 2 TYPE, 1 StatusID, 1 CHANGED FROM tmp tmp
					LEFT JOIN branch br ON br.Code = tmp.branch_code
					LEFT JOIN department dp ON dp.Name = tmp.AO_group
					WHERE dp.ID != ''
					GROUP BY br.ID;
				"; 	
			
		$rs = $this->database->execute($sp);
		return $rs;	
	}

	public function spInsertemployeeSD()
	{
			$sp = "
					INSERT INTO `employee` (CODE,  FirstName, MiddleName, LastName, BirthDate, DateHired, 
											EmployeeTypeID, DepartmentID, StatusID, EnrollmentDate, LastModifiedDate)
					SELECT  CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(LEFT(tmp.sales_director,1), ' ', 1), ' ', -1), '-' , 
							SUBSTRING_INDEX(SUBSTRING_INDEX(tmp.sales_director, ' ', 3), ' ', -1)) AS CODE,
							SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(tmp.sales_director), ' ', 1), ' ', -1) AS First_Name,
							SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(tmp.sales_director), ' ', 2), ' ', -1) AS Middle_Name,
							SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(tmp.sales_director), ' ', 3), ' ', -1) AS Last_Name,	
							NOW() BirthDate, NOW() DateHired, 1 EmployeeTypeID, 9 DepartmentID, 1 StatusID, 
							NOW() EnrollmentDate, NOW() LastModifiedDate
					FROM tmp tmp
					WHERE tmp.sales_director != ''
					GROUP BY tmp.sales_director;
				"; 	
				
				
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	public function spInsertemployeeAOD()
	{
			$sp = "
					INSERT INTO `employee` (CODE,  FirstName, MiddleName, LastName, BirthDate, DateHired, 
											EmployeeTypeID, DepartmentID, StatusID, EnrollmentDate, LastModifiedDate)
					SELECT  CONCAT(SUBSTRING_INDEX(SUBSTRING_INDEX(LEFT(tmp.AO_director,1), ' ', 1), ' ', -1), '-' , 
							SUBSTRING_INDEX(SUBSTRING_INDEX(tmp.AO_director, ' ', 3), ' ', -1)) AS CODE,
							SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(tmp.AO_director), ' ', 1), ' ', -1) AS First_Name,
							SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(tmp.AO_director), ' ', 2), ' ', -1) AS Middle_Name,
							SUBSTRING_INDEX(SUBSTRING_INDEX(TRIM(tmp.AO_director), ' ', 3), ' ', -1) AS Last_Name,	
							NOW() BirthDate, NOW() DateHired, 1 EmployeeTypeID, 7 DepartmentID, 1 StatusID,
							NOW() EnrollmentDate, NOW() LastModifiedDate
					FROM tmp tmp
					WHERE tmp.AO_director != ''
					GROUP BY tmp.AO_director;
				"; 	
				
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	public function spInsertremployeepositionSD()
	{

					 
			$sp = 	"
					 INSERT INTO `remployeeposition` (EmployeeID, PositionID, StatusID, EnrollmentDate, LastModifiedDate)

					 select e.ID EmployeeID, p.ID PositionID, 1 StatusID, now() EnrollmentDate, now() LastModifiedDate from tmp tmp
					 inner join position p on p.Name = 'Sales Director'
					 inner join employee e on CONCAT(e.FirstName,' ',e.MiddleName,' ',e.LastName) = tmp.sales_director
					 group by tmp.sales_director;
                                    "; 
			//echo $sp;		
					
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	public function spInsertremployeepositionAOD()
	{	
			$sp = "
					INSERT INTO `remployeeposition` (EmployeeID, PositionID, StatusID, EnrollmentDate, LastModifiedDate)
					SELECT e.ID EmployeeID, p.ID PositionID,
					1 StatusID, NOW() EnrollmentDate, NOW() LastModifiedDate FROM tmp tmp
					INNER JOIN position p ON p.Name = 'Area Operations Director'
					INNER JOIN employee e ON CONCAT(e.FirstName,' ',e.MiddleName,' ',e.LastName)  = tmp.AO_director
					GROUP BY tmp.AO_director;
				"; 	
		
		$rs = $this->database->execute($sp);
		return $rs;	
	}

	public function spInsertremployeebranchSDandAOD()
	{
			$sp = "
					INSERT INTO remployeebranch (BranchID, EmployeeID, StatusID, EnrollmentDate, LastModifiedDate, CHANGED, TYPE)

					SELECT br.ID BranchID, e.ID EmployeeID,1 StatusID, NOW() EnrollmentDate, NOW() LastModifiedDate, 1 CHANGED, 1 TYPE FROM tmp tmp
					INNER JOIN employee e ON 
						CONCAT(e.FirstName,' ',e.MiddleName,' ',e.LastName)  = tmp.sales_director
					LEFT JOIN branch br ON tmp.branch_code = br.Code
					UNION 
					SELECT br.ID BranchID, e.ID EmployeeID,1 StatusID , NOW() EnrollmentDate, NOW() LastModifiedDate, 1 CHANGED, 2 TYPE FROM tmp tmp
					INNER JOIN employee e ON 
						CONCAT(e.FirstName,' ',e.MiddleName,' ',e.LastName)  = tmp.AO_director
					LEFT JOIN branch br ON tmp.branch_code = br.Code;

				"; 	
				
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	public function spInsertbranchdetails()
	{
			$sp = "
					insert into branchdetails (BranchID, FieldID, ValueID, Details, EnrollmentDate, LastModifiedDate, Changed)

					SELECT br.ID BranchID, 7 FieldID,v.ID ValueID, NULL Details, NOW() EnrollmentDate, NOW() LastModifiedDate, 1 changed FROM tmp tmp
					LEFT JOIN branch br ON br.Code = tmp.branch_code 
					LEFT JOIN value v ON v.Code = tmp.branch_size
					GROUP BY br.ID
					ORDER BY br.ID;
				"; 	
		
		
		$rs = $this->database->execute($sp);
		return $rs;	
	}
	
	public function spUpdatebranchdetails()
	{
			$sp = "
					UPDATE branchdetails SET ValueID = 0 WHERE ValueID = 336;	
				"; 	
		$rs = $this->database->execute($sp);
		
		return $rs;	
	}	
}

?>