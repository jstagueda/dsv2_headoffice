<?php 

include "../../../initialize.php";
include IN_PATH."ReservationReport.php";

if(isset($_POST['action'])){
	
	if($_POST['action'] == "ReservationDetails"){
		
		echo '<table cellpadding="0" cellspacing="0" width="100%" class="bordersolo" style="border-top:none;">					
			<tr class="trheader">
				<td width="10%">Reservation No.</td>
				<td width="10%">Item Code</td>
				<td>Item Description</td>
				<td width="10%">Commitment Date</td>
				<td width="10%">Approved Quantity</td>
				<td width="10%">Delivered Quantity</td>
				<td width="10%">DR No</td>
				<td width="10%">DR Date</td>
			</tr>';
			
			$datefrom = date("Y-m-d", strtotime($_POST['datefrom']));
			$dateto = date("Y-m-d", strtotime($_POST['dateto']));
			$search = $_POST['Search'];
			$page = $_POST['page'];
			$total = 10;
			$branchID = $_POST['branchID'];
			
			$reservation = ReservationDetails($datefrom, $dateto, $search, $page, $total, false, $branchID);						
			
			if($reservation->num_rows){
				while($res = $reservation->fetch_object()){
					
					$delivery = getDeliveryNo($res->ReservationNo, $res->ProductCode, $res->BranchID);
					$DeliveryNo = implode(',', $delivery['DeliveryNo']);
					$DeliveryDate = implode(',', $delivery['DeliveryDate']);
				
					echo '<tr class="trlist">
						<td align="center">'.$res->ReservationNo.'</td>
						<td align="center">'.$res->ProductCode.'</td>
						<td>'.$res->ProductName.'</td>
						<td align="center">'.$res->CommitmentDate.'</td>
						<td align="right">'.$res->ApprovedQuantity.'</td>
						<td align="right">'.$res->LoadedQuantity.'</td>
						<td align="center">'.$DeliveryNo.'</td>
						<td align="center">'.$DeliveryDate.'</td>
					</tr>';
				}
			}else{
			
				echo '<tr class="trlist">
					<td align="center" colspan="9">No result found.</td>
				</tr>';
			}
			
		echo '</table>';
		
		$reservationCount = ReservationDetails($datefrom, $dateto, $search, $page, $total, true, $branchID);
		if($reservationCount->num_rows){
			echo "<div style='margin-top:10px;'>".AddPagination($total, $reservationCount->num_rows, $page)."</div>";
		}
		
		die();
	}
	
	
	if($_POST['action'] == "GetBranch"){
		
		$Search = $_POST['Branch'];
		$BranchQuery = $database->execute("SELECT * FROM branch 
										WHERE ID NOT IN (1,2,3)
										AND (TRIM(Code) LIKE '%$Search%' OR Name LIKE '%$Search%') LIMIT 10");
		
		if($BranchQuery->num_rows){
			while($res = $BranchQuery->fetch_object()){
				$result[] = array("Label" => $res->Code." - ".$res->Name,
									"Value" => $res->Code." - ".$res->Name,
									"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found.",
									"Value" => "",
									"ID" => 0);
		}
		
		die(json_encode($result));
	}
	
}

?>