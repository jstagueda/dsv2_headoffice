<?php 
	
	include "../initialize.php";
	
	if(isset($_POST['action'])){
		if($_POST['action'] == "getListOfIGS"){
			
			$HOQuery = $database->execute("SELECT c.`Code`, c.FirstName,c.MiddleName, c.LastName, c.Birthdate, tcd.Gender, b.Code BranchCode,tcd.StreetAdd FROM customer c 
										 INNER JOIN branch b on b.ID = SPLIT_STR(HOGeneralID,'-',2)
										 INNER JOIN tpi_customerdetails tcd on tcd.CustomerID = c.ID
										 WHERE  FirstName LIKE '".$_POST['fname']."%' AND #MiddleName LIKE '".$_POST['mname']."%' AND 
										 LastName LIKE '".$_POST['lname']."%' and c.CustomerTypeID = 1");
										 
			if($HOQuery->num_rows){
				while($res = $HOQuery->fetch_object()){
					$return['details'][] = array("Code" => $res->Code,
												"FirstName" => $res->FirstName,
												"MiddleName" => $res->MiddleName,
												"LastName" => $res->LastName,
												"Birthdate" => date('m/d/Y',strtotime($r->Birthdate)),
												"Gender" => $res->Gender,
												"StreetAdd" => $res->StreetAdd,
												"BranchCode" => $res->BranchCode);
				}
			}
			
			$return['TotalRecords'] = $HOQuery->num_rows;
			
			die(json_encode($return));
			
		}
		
		if($_POST['action'] == "getSameDetails"){
			
			$terminated = 0;
			$lastname = $_POST['lname'];
			$firstname = $_POST['fname'];
			$middlename = $_POST['mname'];
			$birthday = date("Y-m-d", strtotime($_POST['bday']));
			$gender = $_POST['gender'];
			
			$HOQuery = $mysqliHO->query("SELECT 
										c.Code CustomerCode,
										IF(rcs.CustomerStatusID = 5, 1, 0) `Terminated`
									FROM customer c 
									INNER JOIN branch b on b.ID = SPLIT_STR(c.HOGeneralID,'-',2)
									INNER JOIN tpi_customerdetails tcd on tcd.CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
									INNER JOIN tpi_rcustomerstatus rcs ON rcs.CustomerID = c.ID
											AND rcs.ID = (SELECT MAX(ID) FROM tpi_rcustomerstatus WHERE CustomerID = c.ID AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
											AND LOCATE(CONCAT('-', b.ID), rcs.HOGeneralID) > 0
									WHERE TRIM(c.FirstName) = '$firstname' 
										AND TRIM(c.MiddleName) = '$middlename' 
										AND TRIM(c.LastName) = '$lastname' 
										AND DATE(c.Birthdate) = '$birthday'
										AND TRIM(tcd.Gender) = '$gender'
										AND c.CustomerTypeID = 1");
			if($HOQuery->num_rows){
				$countsamedetails++;
				$hocust = $HOQuery->fetch_object();
				$terminated = $hocust->Terminated;
			}
			
			$return['TotalRecords'] = $HOQuery->num_rows;
			$return['Terminated'] = $terminated;
			
			die(json_encode($return));
			
		}
	}
?>