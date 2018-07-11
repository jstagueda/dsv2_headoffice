<?php
require_once("../initialize.php");
include IN_PATH.DS."pagination.php";
include IN_PATH.DS."InventoryTransactionRegister.php";

if(isset($_POST['action'])){
	
	if($_POST['action'] == "GetBranch"){
		$Search = $_POST['searchbranch'];
		$branchquery = $database->execute("SELECT * FROM branch WHERE ID NOT IN (1,2,3) AND (Code LIKE '%$Search%' OR Name LIKE '%Search%') LIMIT 10");
		if($branchquery->num_rows){
			while($res = $branchquery->fetch_object()){
				$result[] = array("Label" => TRIM($res->Code)." - ".TRIM($res->Name),
									"Value" => TRIM($res->Code)." - ".TRIM($res->Name),
									"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found.",
								"Value" => "",
								"ID" => 0);
		}
		
		die(json_encode($result));
	}

	if($_POST['action'] == "GetTransactionList"){
		$page = (isset($_POST['page']))?$_POST['page']:1;
		$cboWarehouse = (isset($_POST['cboWarehouse']))?$_POST['cboWarehouse']:0;

		$txtEndDates = (isset($_POST['txtEndDates']))?$_POST['txtEndDates']:date("m/d/Y");
		$txtEndDates = date("Y-m-d", strtotime($txtEndDates));

		$txtStartDates = (isset($_POST['txtStartDates']))?$_POST['txtStartDates']:date("m/d/Y");
		$txtStartDates = date("Y-m-d", strtotime($txtStartDates));

		$txtSearch = (isset($_POST['txtSearch']))?$_POST['txtSearch']:"";

		$branch = $_POST['branch'];

		$totalrow = 10;
		$spSelectInventoryRegisterCount = inventorytransactionreport($page, $totalrow, true, $txtStartDates, $txtEndDates, $cboWarehouse, $txtSearch, $branch);

		echo "<table width='100%' cellpadding='0' border=0 cellspacing='0' class=\"bordergreen\">
				<tr class=\"trheader\">
					<td align='center' width='7%'>Transaction Date</td>
					<td align='center'>Movement Type</td>
					<td align='center' width='7%'>Reference No.</td>
					<td align='center' width='5%'>Item Code</td>
					<td align='center' width='18%'>Item Description</td>
					<td align='center' width='5%'>Issuing Branch</td>                      	
					<td align='center' width='5%'>Location</td>
					<td align='center' width='5%'>Receiving Branch</td>
					<td align='center' width='5%'>Location</td>
					<td align='center' width='5%'>CSP</td>   
					<td align='center' width='5%'>Qty In</td>    
					<td align='center' width='5%'>Qty Out</td>  
					<td align='center' width='5%'>Total </td>   
				</tr>";
				
		//Get all the rows

		$spSelectInventoryRegister = inventorytransactionreport($page, $totalrow, false, $txtStartDates, $txtEndDates, $cboWarehouse, $txtSearch, $branch);
		$inventorytransactionTotal = inventorytransactionTotal($txtStartDates, $txtEndDates, $cboWarehouse, $txtSearch, $branch);

		//Echo each row
		$cnt = 0;
		$tot = 0;
		$totalQty = 0;
		$total = 0;
		$movementtype = "";
		$ReferenceNo = "";

		if($spSelectInventoryRegister->num_rows){

			$invTotal = $inventorytransactionTotal->fetch_object();

			while($row = $spSelectInventoryRegister->fetch_object()){	 
				$cnt ++;		

				$tot = $row->Qty * $row->UnitPrice;
				$qtyPrice = number_format($tot,2);
				
				if($cnt > 1){
					if($ReferenceNo != $row->RefTxnNo){
						$totalPerMovement = inventorytransactionTotalPerMovement($txtStartDates, $txtEndDates, $cboWarehouse, $movementtype, $ReferenceNo, $branch);
						$mt = $totalPerMovement->fetch_object();
						echo "<tr class=\"trlist\">
							<td align='right' colspan=10><b>TOTAL FOR $ReferenceNo</b></td>   
							<td align='right'>".number_format($mt->QtyInTotal)."</td>    
							<td align='right'>".number_format($mt->QtyOutTotal)."</td>    
							<td align='right'>".number_format($mt->TotalAmount,2)."</td>   
							</tr>";
					}
				}
				
				echo "<tr class=\"trlist\">
						<td width='7% align='center'>$row->TxnDate</td>
						<td width='12%' align='center'>$row->MovementCode</td>
						<td width='7%' align='center'>$row->RefTxnNo</td>
						<td width='5%' align='center'>$row->Code</td>
						<td width='18%' align='left'>$row->Product</td>
						<td width='5%' align='center'>$row->IssuingBranch</td>
						<td width='5%' align='center'>$row->Location1</td>
						<td width='5%' align='center'>$row->ReceivingBranch</td>
						<td width='5%' align='center'>$row->Location2</td>
						<td align='right'>".number_format($row->UnitPrice,2)."</td>
						<td align='right'>$row->QtyIn </td>
						<td align='right'>$row->QtyOut </td>
						<td align='right'>$qtyPrice</td>
					</tr>";
				
				if($cnt == $spSelectInventoryRegister->num_rows){
					$totalPerMovement = inventorytransactionTotalPerMovement($txtStartDates, $txtEndDates, $cboWarehouse, $row->MovementType, $row->RefTxnNo, $branch);
					$mt = $totalPerMovement->fetch_object();
					echo "<tr class=\"trlist\">
						<td align='right' colspan=10><b>TOTAL FOR $row->RefTxnNo</b></td>   
						<td align='right'>".number_format($mt->QtyInTotal)."</td>    
							<td align='right'>".number_format($mt->QtyOutTotal)."</td> 
						<td align='right'>".number_format($mt->TotalAmount,2)."</td>   
						</tr>";
				}
				
				$movementtype = $row->MovementType;
				$ReferenceNo = $row->RefTxnNo;
			}
			
			echo "<tr class=\"trlist\">
					<td align='right' colspan=10><b>GRAND TOTAL</b></td>   
					<td align='right'>".number_format($invTotal->QtyInTotal)."</td>    
					<td align='right'>".number_format($invTotal->QtyOutTotal)."</td>    
					<td align='right'>".number_format($invTotal->TotalAmount, 2)."</td>   
					</tr>";
		}else{
			echo '<tr class="trlist">
					<td colspan="13" align="center" style="color:red;">No result found.</td>
				</tr>';
		}

		echo "</table>";
		echo "<div style='margin-top:10px;'>".AddPagination($totalrow, $spSelectInventoryRegisterCount->num_rows, $page)."</div>";
	}
}
?>
