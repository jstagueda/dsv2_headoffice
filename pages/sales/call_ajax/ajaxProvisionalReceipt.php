<?php 

include "../../../initialize.php";
include IN_PATH."scProvisionalReceipt.php";
include IN_PATH."pagination.php";


if(isset($_POST['action'])){
	
	if($_POST['action'] == "GetBranch"){
		$Branch = $_POST['Branch'];
		$branchquery = $database->execute("SELECT ID BranchID, TRIM(Code) BranchCode, Name BranchName 
											FROM branch
											WHERE ID NOT IN (1,2,3)
											AND (TRIM(Code) LIKE '%$Branch%' OR Name LIKE '%$Branch%')
											ORDER BY Name
											LIMIT 10");
		if($branchquery->num_rows){
			while($res = $branchquery->fetch_object()){
				$result[] = array("Label" => $res->BranchCode.' - '.$res->BranchName,
								"Value" => $res->BranchCode.' - '.$res->BranchName,
								"ID" => $res->BranchID);
			}
		}else{
			$result[] = array("Label" => "No result found.",
								"Value" => "",
								"ID" => 0);
		}
		
		die(json_encode($result));
	}
	
	if($_POST['action'] == "SearchCustomer"){
		
		$customerquery = $database->execute("SELECT 
											ID, 
											Name, 
											TRIM(Code) Code 
											FROM customer 
											WHERE CustomerTypeID = ".$_POST['sfmlevel']."
											AND SPLIT_STR(HOGeneralID, '-', 2) = ".$_POST['BranchID']."
											AND (TRIM(Code) LIKE '".$_POST['customer']."%' OR TRIM(Name) LIKE '%".$_POST['customer']."%')
											
											LIMIT 10");
											
		if($customerquery->num_rows){
			
			while($res = $customerquery->fetch_object()){
				
				$result[] = array("Label" => $res->Code." - ". $res->Name,
									"Value" => $res->Code." - ". $res->Name,
									"ID" => $res->ID);
				
			}
			
		}else{
			$result[] = array("Label" => "No result found.",
								"Value" => "",
								"ID" => 0);
		}
		
		die(json_encode($result));
	}
	
	
	if($_POST['action'] == "GetProvisionalReceiptList"){
		
		$datefrom = date('Y-m-d', strtotime($_POST['datefrom']));
		$dateto = date('Y-m-d', strtotime($_POST['dateto']));		
		$customerfrom = $_POST['sfaccountfromHidden'];
		$customerto = $_POST['sfaccounttoHidden'];
		$sfmlevel = $_POST['sfmlevel'];
		$page = $_POST['page'];
		$total = 10;
		$branch = $_POST['BranchID'];
		
		echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='bordergreen'>
				<tr class='trheader'>
					<td>PR Date</td>
					<td>PR No.</td>
					<td>Transaction No.</td>
					<td>Customer Code</td>
					<td>Customer Name</td>
					<td>Check No.</td>
					<td>Check Date</td>
					<td>Amount</td>
					<td>Status</td>
				</tr>";
				
		$provisionalreceipt = provisionalreceipt($datefrom, $dateto, $sfmlevel, $customerfrom, $customerto, $page, $total, false, $branch);
		$provisionalreceiptTotal = provisionalreceipt($datefrom, $dateto, $sfmlevel, $customerfrom, $customerto, $page, $total, true, $branch);
		$provisionalreceiptTotalAmount = provisionalreceiptTotal($datefrom, $dateto, $sfmlevel, $customerfrom, $customerto, $branch);
		$TotalAmount = $provisionalreceiptTotalAmount->fetch_object()->TotalAmount;
		
		if($provisionalreceipt->num_rows){
			while($res = $provisionalreceipt->fetch_object()){
				
				echo "<tr class='trlist'>
						
						<td align='center'>$res->PRDate</td>
						<td align='center'>$res->PRNumber</td>
						<td align='center'>$res->TransactionNumber</td>
						<td align='center'>$res->CustomerCode</td>
						<td>$res->CustomerName</td>
						<td align='center'>$res->CheckNumber</td>
						<td align='center'>$res->CheckDate</td>
						<td align='right'>".number_format($res->TotalAmount, 2)."</td>
						<td align='center'>$res->StatusName</td>
						
					</tr>";
				
			}
			
			echo "<tr class='trlist'>
					<td align='right' colspan='7'>Total Amount</td>
					<td align='right'>".number_format($TotalAmount, 2)."</td>
					<td></td>
				</tr>";
		}else{
			echo "<tr class='trlist'>
					<td colspan='9' align='center'><span class='txtredsbold'>No record(s) to display.</span></td>
				</tr>";
		}
		
		echo "</table>";
		echo "<div style='margin-top:10px;'>".AddPagination($total, $provisionalreceiptTotal->num_rows, $page)."</div>";
		
		die();
	}
	
}

?>