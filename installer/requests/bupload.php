<?php
class BranchUpload
{
	public $database;
	
	//main method 
	public function minbranchUpload($database,$success_logs)
	{
					//$success_logs 		= "../installer/logs.txt";
					//$success_logs = LOGS_PATH.'process.log';
					//tpi_file_truncate($success_logs);
					$this->database = $database;
					
					$database->execute("SET FOREIGN_KEY_CHECKS = 0");
					
					//Drop Tmp Table if Exist and Create;
					self::spDropCreatetmp();
					
					//load here all branches...
					self::spLoadInvDataToTempTable();
                    tpi_error_log("Inserted Branch to Temporary table.<br />",$success_logs);
					
					
					//create tmp table here and load all data... 
					self::spLoadDataEmployeetoEmployeeTable();
					tpi_error_log("Inserted Employee to Temporary table.<br />",$success_logs);
					
					//insert all department...
					self::spInsertDepartmentToDepartmentTable();
					tpi_error_log("Done Insert Departments into Department table.<br />",$success_logs);
					
					//Sales Group
					self::spInsertDepartmentSalesGroupToDepartmentTable();
					tpi_error_log("Done Insert Sales Group to Department table.<br />",$success_logs);
					
					//insert all position
					self::spInsertAllPositionIntoPositionTable();
					tpi_error_log("Done insert positions to Department table.<br />",$success_logs);
					
					
					//Load to main table the Branches..
					self::spinsertbranch();
					tpi_error_log("Done Insert Branch to Main table.<br />",$success_logs);            
					
					//branch size..
					self::spInsertbranchdetails();
					tpi_error_log("Done Insert Branch Details to branchdetails table.<br />",$success_logs);
					
					
					//insert all employee..
					self::spInsertAllEmployeeTmpIntoEmployeeTable();
					tpi_error_log("Done Insert Employee to employee table.<br />",$success_logs);
					
					//insert employee branch..
					self::spInsertEmployeeBranch();
					tpi_error_log("Done Insert Employee Branch to remployeebranch table.<br />",$success_logs);
				
					//insert employee position..
					self::spInsertEmployeePosition();
					tpi_error_log("Done Insert Employee positions to remployeebranch table.<br />",$success_logs);
					
					self::spUpdateEmployeeContactPersonIntoBranchTable();
					tpi_error_log("Done update contact person branch table.<br />",$success_logs);
					self::spUpdatebranchdetails();
	}
	
	/*stored procedures*/
	public function spDropCreatetmp()
	{
		
		 $sp = array("DROP TABLE IF EXISTS `tmp`;","
                        CREATE TABLE `tmp` (
                        `id` int(11) DEFAULT NULL,
                        `branch_code` varchar(50) DEFAULT NULL,
                        `branch_name` varchar(50) DEFAULT NULL,
                        `strt_add` varchar(255) DEFAULT NULL,
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
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;  ");
		foreach($sp as $createtmp):
			$this->database->execute($createtmp);
		endforeach;
		return "test";
	} 
        
	public function spLoadInvDataToTempTable()
	{
			$sp = "
					LOAD DATA LOCAL INFILE'C:/xampp/htdocs/10.132.54.166/dss_svn_new/HeadOffice_trunk/installer/data/DS PROJECT BRANCH DETAILS.csv'
					INTO TABLE tmp
					FIELDS TERMINATED BY ','
					Optionally enclosed by '\"'
					LINES TERMINATED BY '\n'
					Ignore 1 lines;	
				"; 	
			
			$rs = $this->database->execute($sp);
			return $rs;	
	}
	
	public function spLoadDataEmployeetoEmployeeTable()
	{
		$query = array(	"DROP TABLE IF EXISTS `employeetmp`;",
						"create table `employeetmp`( 
							`EmployeeID` bigint(100) , 
							`LastName` varchar(50) , 
							`FirstName` varchar(50) , 
							`MiddleName` varchar(50) , 
							`BirthDate` date , 
							`DateHired` date , 
							`BranchCode` varchar(50) , 
							`EmployeeType` varchar(50) , 
							`Department` varchar(50) , 
							`Position` varchar(50) 
						)ENGINE=InnoDB DEFAULT CHARSET=latin1",
						"LOAD DATA LOCAL INFILE'C:/xampp/htdocs/10.132.54.166/dss_svn_new/HeadOffice_trunk/installer/data/TBPI Employee Data.csv'
						INTO TABLE employeetmp
						FIELDS TERMINATED BY ','
						Optionally enclosed by '\"'
						LINES TERMINATED BY '\n'
						Ignore 1 lines
						(EmployeeID,LastName,FirstName, MiddleName, @column5, @coulumn6, BranchCode, EmployeeType, Department,Position)
                         SET 
                         BirthDate = STR_TO_DATE(TRIM(@column5),'%d/%m/%Y'),
                         DateHired = STR_TO_DATE(TRIM(@coulumn6),'%d/%m/%Y')");
			
			foreach($query as $q):
				$this->database->execute($q);
			endforeach;
	}
	
	
	public function spInsertDepartmentToDepartmentTable()
	{
		
		$q = self::_describe("employeetmp");
		if($q->num_rows):
			while($r = $q->fetch_object()):
					$this->database->execute("update employeetmp set ".$r->Field."=trim(".$r->Field.")");
			endwhile;
		else:
			//
		endif;
		
		
		$d = self::_describe("department");
		
		if($d->num_rows):
			while($dr = $d->fetch_object()):
				if($dr->Field <> "ID"):
					$InsertColumn[]=$dr->Field;
				endif;
			endwhile;
			$c = implode(',',$InsertColumn);
			$this->database->execute("insert into department (".$c.") values ('TB', 'Tupperware Brands', 1 , 0,1,1,1,NOW(),NOW(),1)");
		else:
			//
		endif;
		
		//department here..
		$dh = $this->database->execute("SELECT Department FROM employeetmp GROUP BY Department");
		if($dh->num_rows):
			while($rh=$dh->fetch_object()):
				$this->database->execute("insert into department (".$c.") values ('', '".$rh->Department."', 2 , 1,0,0,1,NOW(),NOW(),1)");
				$LastID = self::_GetLastID();
				$Code = self::_GenerateCode($LastID);
				$this->database->execute("update department set Code = '".$Code."' where ID = ".$LastID);
			endwhile;
		else:
		//
		endif;
		
		
	}
	public function spInsertDepartmentSalesGroupToDepartmentTable()
	{
		$d = self::_describe("department");
		
		if($d->num_rows):
			while($dr = $d->fetch_object()):
				if($dr->Field <> "ID"):
					$InsertColumn[]=$dr->Field;
				endif;
			endwhile;
			$c = implode(',',$InsertColumn);
			//department here..
			$dh = $this->database->execute("SELECT sales_group Department FROM tmp GROUP BY sales_group");
			if($dh->num_rows):
				while($rh=$dh->fetch_object()):
					$this->database->execute("insert into department (".$c.") values ('', '".$rh->Department."', 4 , 0,0,0,1,NOW(),NOW(),1)");
					$LastID = self::_GetLastID();
					$Code = self::_GenerateCode($LastID);
					$this->database->execute("update department set Code = '".$Code."' where ID = ".$LastID);
				endwhile;
			else:
		//
		endif;
		else:
			//
		endif;
	}
	public function spInsertAllPositionIntoPositionTable()
	{
		$d = self::_describe("position");
		
		if($d->num_rows):
			while($dr = $d->fetch_object()):
				if($dr->Field <> "ID"):
					$InsertColumn[]=$dr->Field;
				endif;
			endwhile;
			$c = implode(',',$InsertColumn);
			$mysqlquery = $this->database->execute("select `position` from `employeetmp` group by `position`");
			if($mysqlquery->num_rows):
				while($r = $mysqlquery->fetch_object()):
					$this->database->execute("insert into position (".$c.") values ('','".$r->position."', 1 ,NOW(),NOW(),1)");
					$LastID = self::_GetLastID();
					$Code = self::_GenerateCode($LastID);
					$this->database->execute("update position set Code = '".$Code."' where ID = ".$LastID);
				endwhile;
			else:
				//
			endif;
		else:
			//
		endif;
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
	
	public function spInsertAllEmployeeTmpIntoEmployeeTable()
	{
		$fields = self::_describe("employee");
		if($fields->num_rows):
			while($rfields = $fields->fetch_object()):
				if($rfields->Field <> "ID"):
					$InsertColumn[]=$rfields->Field;
				endif;	
			endwhile;
			$c = implode(',',$InsertColumn);
					$this->database->execute("insert into employee (".$c.")  
											(SELECT tmp.EmployeeID `Code`, tmp.LastName,tmp.FirstName,tmp.Middlename,tmp.BirthDate,
											 tmp.DateHired,IFNULL(emptyp.ID,emptyp1.ID) EmployeeTypeID,dpt.ID DepartmentID, 1 StatusID, NOW() EnrollmentDate, NOW() LastModifiedDate,1 `Changed`
											 FROM employeetmp tmp
											 LEFT JOIN employeetype emptyp ON tmp.EmployeeType=emptyp.Code
											 LEFT JOIN employeetype emptyp1 ON tmp.EmployeeType=emptyp1.Name
											 LEFT JOIN department dpt ON tmp.Department = dpt.Name)");
		endif;
	}
	
	public function spInsertEmployeePosition()
	{
		$fields = self::_describe("remployeeposition");
		if($fields->num_rows):
			while($rfields = $fields->fetch_object()):
				if($rfields->Field <> "ID"):
					$InsertColumn[]=$rfields->Field;
				endif;	
			endwhile;
			$c = implode(',',$InsertColumn);
			
			$this->database->execute("insert into remployeeposition (".$c.")  
			(SELECT  a.ID EmployeeID,c.ID PositionID,1 StatusID, NOW() EnrollmentDate, NOW() LastModifiedDate, 1 `Changed` FROM employee a
			LEFT JOIN employeetmp b
			ON a.Code=b.EmployeeID
			LEFT JOIN `position` c ON b.Position = c.Name)");
		endif;

	}
	
	public function spUpdatebranchdetails()
	{
			$sp = "
					UPDATE branchdetails SET ValueID = 0 WHERE ValueID = 336;	
				"; 	
		$rs = $this->database->execute($sp);
		
		return $rs;	
	}
	
	public function spUpdateEmployeeContactPersonIntoBranchTable()
	{
		$q = $this->database->execute("SELECT * FROM (
									   SELECT t.branch_code,IFNULL(e.ID,e1.ID) EmployeeID FROM tmp t
									   LEFT JOIN employee e ON CONCAT(e.FirstName,' ',e.LastName) = t.contact_person
									   LEFT JOIN employee e1 ON CONCAT(e1.FirstName,' ',IFNULL(CONCAT(LEFT(e1.MiddleName,1)),''),'',e1.LastName)=t.contact_person
									   ) tbl WHERE EmployeeID IS NOT NULL ");
		if($q->num_rows):
			while($r=$q->fetch_object()):
				$this->database->execute("UPDATE branch SET EmployeeID = ".$r->EmployeeID." WHERE `Code`='".$r->branch_code."'");
			endwhile;
		endif;
	}
	
	public function spInsertEmployeeBranch()
	{
		$fields = self::_describe("remployeebranch");
		if($fields->num_rows):
			while($rfields = $fields->fetch_object()):
				if($rfields->Field <> "ID"):
					$InsertColumn[]=$rfields->Field;
				endif;	
			endwhile;
			$c = implode(',',$InsertColumn);
			$this->database->execute("insert into remployeebranch (".$c.")  
			(SELECT c.ID BranchID,  a.ID EmployeeID,1 StatusID, NOW() EnrollmentDate, NOW() LastModifiedDate, 1 `Changed`,1 `Type` FROM employee a
			 INNER JOIN employeetmp b
			 ON a.Code=b.EmployeeID
			 LEFT JOIN branch c ON b.BranchCode = c.Code)");

		endif;	
	}
	
	public function _describe($table){
		$result = $this->database->execute("describe ".$table."");
		return $result;
	}
	
	public function _GetLastID()
	{
		$last_insert = $this->database->execute("select LAST_INSERT_ID() LastID");
		$lastID = $last_insert->fetch_object()->LastID;
		return $lastID;
	}
	
	public function _GenerateCode($ID)
	{
		 $q = $this->database->execute("SELECT CONCAT(
											  SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(".$ID.")*4294967296))*36+1, 1),
											  SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
											  SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
											  SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
											  SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
											  SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
											  SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed:=ROUND(RAND(@seed)*4294967296))*36+1, 1),
											  SUBSTRING('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', RAND(@seed)*36+1, 1)) Code");
											  
		if($q->num_rows):
			return $q->fetch_object()->Code;
		endif;
	}
	
	public function spinsertbranch()
	{
			

			$sp = array('INSERT INTO branch (Code,NAME,StreetAdd,AreaID,ZipCode,TelNo1,TelNo2,TelNo3,FaxNo, TIN,PermitNo,ServerSN,BranchTypeID,OfficeTypeID,EmployeeID, StatusID,EnrollmentDate,LastModifiedDate,CHANGED) VALUES 
						("HO", "Tupperware Brands Head Office","2288 Chino Roces Ext Makati City", 0, 1200, 8593000,"","","","","","",0,2,"",1,NOW(),NOW(),1), 
						("WHSE2", "Tupperware Brands Central Whse", "BLDG. F&G, J.Y. Compound Veterans Center Taguig Ci", 0, "", "","","","","","","",0,2,"",1,NOW(),NOW(),1), 
						("WHSE ", "Tupperware Brands Central Whse","BLDG. F&G, J.Y. Compound Veterans Center Taguig Ci", 0, "", "","","","","","","",0,2,"",1,NOW(),NOW(),1)',"
						insert into branch ( Code,Name,StreetAdd,AreaID,ZipCode,TelNo1,TelNo2,TelNo3,FaxNo, 
											TIN,PermitNo,ServerSN,BranchTypeID,OfficeTypeID,EmployeeID, 
											StatusID,EnrollmentDate,LastModifiedDate,Changed, DepartmentID
											)
						select 	tmp.branch_code Code,tmp.branch_name Name,tmp.strt_add StreetAdd,tmp.region Area,tmp.zip_code ZipCode,
								tmp.telno1,tmp.telno2,tmp.telno3,tmp.telno4,tmp.tin,tmp.permit_no,tmp.server_sn,ifnull(bt.ID,bt1.ID) BranchTypeId,
								2 OfficeTypeID,NULL EmployeeID,1 StatusID,now() EnrollmentDate,
								now() LastModifiedDate,0 Changed,d.ID DepartmentID
						from tmp tmp
						LEFT join branchtype bt on tmp.branch_type = bt.Name
						LEFT join branchtype bt1 on tmp.branch_type = bt.Code
						LEFT JOIN department d ON d.Name = tmp.sales_group
						GROUP BY tmp.branch_code
						;");
				foreach($sp as $query):
					$rs = $this->database->execute($query);
				endforeach;
	}




}

$BranchUpload = new BranchUpload();
$BranchUpload->minbranchUpload($database,$success_logs);
?>