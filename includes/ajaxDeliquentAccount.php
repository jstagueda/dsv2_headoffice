<?php 

include "../initialize.php";
include IN_PATH.DS."pagination.php";

function DeliquentAccountList($BranchID, $Searched, $page, $total, $istotal){
	
	global $database;
	
	$start = ($page > 1) ? ($page - 1) * $total : 0;
	$limit = ($istotal) ? "" : "LIMIT $start, $total";
	
	$branchquery = ($BranchID == 0) ? "" : "AND b.ID = $BranchID";
	
	$query = $database->execute("SELECT 
									da.*,
									c.Name CustomerName,
									b.Name BranchName,
									DATE_FORMAT(da.Birthdate, '%M %d, %Y') BirthDate,
									IFNULL((SELECT Gender FROM tpi_customerdetails 
										WHERE CustomerID = c.ID
										AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0), 'N/A') Gender
								FROM dal da
								INNER JOIN branch b ON b.Code = da.BranchCode
								INNER JOIN customer c ON TRIM(c.Code) = TRIM(da.CustomerCode)
									AND b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
								WHERE (c.Code LIKE '%$Searched%' OR c.Name LIKE '%$Searched%')
								$branchquery
								ORDER BY da.EnrollmentDate DESC
								$limit");
	
	return $query;
	
}

if(isset($_POST['action'])){
	
	//get the searched branch
	if($_POST['action'] == "GetBranch"){
		
		$searchedbranch = $_POST['Branch'];
		$branchquery = $database->execute("SELECT ID, TRIM(`Code`) `Code`, Name FROM branch 
										WHERE (Name LIKE '%$searchedbranch%' OR TRIM(Code) LIKE '%$searchedbranch%') 
										AND ID NOT IN (1,2,3)
										ORDER BY Code 
										LIMIT 10");
		if($branchquery->num_rows){
			while($res = $branchquery->fetch_object()){
				$result[] = array("Label" => TRIM($res->Code)." - ".$res->Name,
								"Value" => TRIM($res->Code),
								"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found.",
							"Value" => "",
							"ID" => 0);
		}
		
		die(json_encode($result));
		
	}
	
	
	//get the searched customer
	if($_POST['action'] == "GetCustomerDetails"){
		
		$SearchedCustomer = $_POST['Customer'];
		$BranchID = $_POST['BranchID'];
		
		$customerdetails = $database->execute("SELECT
									c.ID CustomerID, 
									TRIM(c.Code) CustomerCode,
									c.Name CustomerName,
									DATE_FORMAT(c.Birthdate, '%M %d, %Y') BirthDate,
									IFNULL(tcd.Gender, 'N/A') Gender,
									tcd.StreetAdd Address,
									IFNULL((SELECT SUM(OutstandingBalance) FROM salesinvoice 
										WHERE CustomerID = c.ID
										AND StatusID = 7
										AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0), 0) OutstandingBalance
								FROM customer c 
								INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
								LEFT JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
									AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
								LEFT JOIN tpi_rcustomerstatus rcs ON rcs.CustomerID = c.ID
									AND rcs.ID = (SELECT MAX(ID) FROM tpi_rcustomerstatus 
									WHERE CustomerID = c.ID
									AND LOCATE(CONCAT('-', b.ID), HOGeneralID) > 0)
								WHERE b.ID = $BranchID
								-- AND rcs.CustomerStatusID = 5
								AND c.CustomerTypeID = 1
								AND TRIM(c.Code) NOT IN (SELECT TRIM(CustomerCode) FROM dal WHERE BranchCode = b.Code)
								AND (TRIM(c.Code) LIKE '%$SearchedCustomer%' OR TRIM(c.Name) LIKE '%$SearchedCustomer%')
								LIMIT 10");
		
		if($customerdetails->num_rows){
			while($res = $customerdetails->fetch_object()){
				$result[] = array("Label" => $res->CustomerCode." - ".$res->CustomerName,
								"Value" => $res->CustomerCode,
								"ID" => $res->CustomerID,
								"Name" => $res->CustomerName,
								"Birthdate" => $res->BirthDate,
								"Gender" => $res->Gender,
								"Address" => $res->Address,
								"OutBalance" => number_format($res->OutstandingBalance, 2),
								"Balance" => $res->OutstandingBalance);
			}
		}else{
			$result[] = array("Label" => "No result found.",
								"Value" => "",
								"ID" => 0,
								"Name" => "",
								"Birthdate" => "N/A",
								"Gender" => "N/A",
								"Address" => "",
								"OutBalance" => "0.00",
								"Balance" => 0);
		}
		
		die(json_encode($result));
		
	}
	
	
	//show list of deliquent account
	if($_POST['action'] == "DeliquentAccountList"){
		
		$BranchID = $_POST['BranchID'];
		$Searched = $_POST['Searched'];
		$total = 10;
		$page = $_POST['page'];
		
		$DeliquentAccount = DeliquentAccountList($BranchID, $Searched, $page, $total, false);
		$DeliquentAccountTotal = DeliquentAccountList($BranchID, $Searched, $page, $total, true);
		
		echo '<table class="bordersolo" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top:none;">
				<tr class="trheader">
					<td width="10%;">Branch</td>
					<td width="25%;">Customer</td>
					<td width="10%;">Birth Date</td>
					<td width="10%;">Gender</td>
					<td>Address</td>
					<td width="10%;">Total Outstanding Balance</td>
					<td width="5%;">Action</td>
				</tr>';
		
		if($DeliquentAccount->num_rows){
			while($res = $DeliquentAccount->fetch_object()){
				echo "<tr class='trlist'>
						<td align='center'>".$res->BranchName."</td>
						<td>".TRIM($res->CustomerCode)." - ".$res->CustomerName."</td>
						<td align='center'>".$res->BirthDate."</td>
						<td align='center'>".$res->Gender."</td>
						<td>".$res->Address."</td>
						<td align='right'>".number_format($res->OutstandingAmnt, 2)."</td>
						<td align='center'>
							<input type='button' class='btn' value='Remove' name='btnRemove' onclick='return RemoveDelinquentAccount(this, \"".$res->CustomerCode."\", \"".$res->BranchCode."\")'>
						</td>
					</tr>";
			}
		}else{
			echo '<tr class="trlist">
					<td colspan="7" align="center">No result found.</td>
				</tr>';
		}
		
		echo '</table>';
		echo '<div style="margin-top:10px;">'.AddPagination($total, $DeliquentAccountTotal->num_rows, $page).'</div>';
		die();
	}
	
	//save transaction
	if($_POST['action'] == "AddToDeliquentAccount"){
		
		try{
		
			$database->beginTransaction();
			
			$CustomerID = $_POST['CustomerID'];
			$BranchID = $_POST['BranchID'];
			$OutstandingBalance = ROUND($_POST['OutstandingBalance'], 2);
			
			$database->execute("INSERT INTO dal(BranchCode, CustomerCode, FirstName, MiddleName, LastName, Birthdate, Address, OutstandingAmnt, EnrollmentDate)
								SELECT
									b.Code,
									TRIM(c.Code) CustomerCode,
									c.FirstName,
									c.MiddleName,
									c.LastName,
									DATE(c.Birthdate),
									tcd.StreetAdd Address,
									$OutstandingBalance,
									NOW()
								FROM customer c 
								INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
								LEFT JOIN tpi_customerdetails tcd ON tcd.CustomerID = c.ID
								AND LOCATE(CONCAT('-', b.ID), tcd.HOGeneralID) > 0
								WHERE b.ID = $BranchID
								AND c.ID = $CustomerID");
			
			$database->commitTransaction();
			
			$result['Success'] = 1;
			$result['ErrorMessage'] = "Successfully added new delinquent account.";
		
		}catch(Exception $e){
		
			$database->rollbackTransaction();
			$result['Success'] = 0;
			$result['ErrorMessage'] = $e->getMessage();
			
		}
		
		die(json_encode($result));
		
	}
	
	
	if($_POST['action'] == "RemoveDelinquentAccount"){
		
		$CustomerCode = $_POST['CustomerCode'];
		$BranchCode = $_POST['BranchCode'];
		
		try{
			$database->beginTransaction();
			$database->execute("DELETE FROM dal WHERE CustomerCode = '$CustomerCode' AND BranchCode = '$BranchCode'");
			$database->commitTransaction();
			$result['Success'] = 1;
			$result['ErrorMessage'] = "Delinquent Account successfully removed.";
			
		}catch(Exception $e){
			
			$database->rollbackTransaction();
			$result['Success'] = 0;
			$result['ErrorMessage'] = $e->getMessage();
			
		}
		
		die(json_encode($result));
	}
	
}

?>