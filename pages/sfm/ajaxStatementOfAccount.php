<?php 

include "../../initialize.php";
include IN_PATH."pagination.php";
include IN_PATH."scStatementOfAccount.php";

if(isset($_POST['action'])){
	
	if($_POST['action'] == 'SOAList'){
		
		$CustomerID = $_POST['CustomerID'];
		$Datefrom = date('Y-m-d', strtotime($_POST['DateFrom']));
		$Dateto = date('Y-m-d', strtotime($_POST['DateTo']));
		$page = $_POST['page'];
		$total = 10;
		$balance = 0;
		$counter = 1;
		$BranchID = $_POST['BranchID'];
		
		$statementofaccount = StatementOfAccount($CustomerID, $Datefrom, $Dateto, $page, $total, false, $BranchID);
		$statementofaccountTotal = StatementOfAccount($CustomerID, $Datefrom, $Dateto, $page, $total, true, $BranchID);
		
		echo '<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr class="trheader">
					<td>Date</td>
					<td>Reference #</td>
					<td>Transaction Type</td>
					<td>Debit</td>						
					<td>Credit</td>
					<td>Balance</td>
				</tr>';
		
		if($statementofaccount->num_rows){
			while($res = $statementofaccount->fetch_object()){
				
				if($counter == 1){
					echo '<tr class="trlist">
							<td colspan="5"><b>Beginning Balance</b></td>
							<td align="right"><b>'.number_format($balance, 2).'</b></td>
						</tr>';
				}
				
				$balance = ($balance + abs($res->Debit)) - abs($res->Credit);
				
				if($balance < 0){
					$balance = 0;
				}
				
				echo '<tr class="trlist">
						<td>'.date("m/d/Y", strtotime($res->TxnDate)).'</td>
						<td align="center">'.$res->ReferenceNo.'</td>
						<td align="center">'.$res->TxnType.'</td>
						<td align="right">'.number_format(abs($res->Debit), 2).'</td>
						<td align="right">'.number_format(abs($res->Credit), 2).'</td>
						<td align="right">'.number_format($balance, 2).'</td>
					</tr>';
				
				$counter++;
				
			}
		}else{
			echo '<tr class="trlist">
					<td colspan="6" align="center">No result found.</td>
				</tr>';
		}
				
		echo '</table>';
		die();
	}
	
	
	if($_POST['action'] == 'SearchCustomer'){
		
		$query = $database->execute("SELECT c.Name, c.Code, c.ID FROM customer c 
									INNER JOIN branch b ON b.ID = SPLIT_STR(c.HOGeneralID, '-', 2)
									WHERE c.Name LIKE '".$_POST['Customer']."%' OR c.Code LIKE '".$_POST['Customer']."%'
									AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
									AND b.ID = ".$_POST['BranchID']."
									LIMIT 10");
		if($query->num_rows){
			while($res = $query->fetch_object()){
				$result[] = array("Label" => trim($res->Code).' - '.$res->Name,
									"Value" => trim($res->Code).' - '.$res->Name,
									"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found",
									"Value" => "",
									"ID" => 0);
		}
		
		die(json_encode($result));
		
	}
	
	if($_POST['action'] == 'SearchBranch'){
		
		$query = $database->execute("SELECT * FROM branch WHERE Name LIKE '".$_POST['Branch']."%' OR Code LIKE '".$_POST['Branch']."%' LIMIT 10");
		if($query->num_rows){
			while($res = $query->fetch_object()){
				$result[] = array("Label" => trim($res->Code).' - '.$res->Name,
									"Value" => trim($res->Code).' - '.$res->Name,
									"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found",
									"Value" => "",
									"ID" => 0);
		}
		
		die(json_encode($result));
		
	}
	
}

?>