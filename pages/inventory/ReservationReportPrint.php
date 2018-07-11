<style>
	.pageset, table{
		font-size : 12px;
		font-family : arial;
	}
	
	.pageset{
		margin-bottom : 20px;
	}
	
	.tablelist{
		border-collapse : collapse;
	}
	
	.pageset td, .pageset th{
		padding : 5px;		
	}
	
	@page{
		margin : 0.5in 0;
	}
	
	@media print{
		.pageset{
			margin : 0px;
			page-break-after : always;
		}
	}
	
</style>

<?php 
	
	include "../../initialize.php";
	include IN_PATH."ReservationReport.php";
	
	$datefrom = date("Y-m-d", strtotime($_GET['datefrom']));
	$dateto = date("Y-m-d", strtotime($_GET['dateto']));
	$branchID = $_GET['branchID'];
	$search = $_GET['Search'];
	$page = $_GET['page'];
	$total = 10;
	
	$counter = 1;
	$count = 1;
	$row = 20;
	
	echo "<div style='margin-bottom:5px;'>
			<table width='100%' cellspacing='0' cellpadding='0'>
				<tr>
					<th colspan='6' style='font-size:14px;'>Reservation Report</th>
				</tr>
				<tr><td>&nbsp;</td></tr>
				<tr>
					<td width='100px;'><b>Branch</b></td>
					<td>:</td>
					<td>".getBranchDetails($branchID)."</td>
					<td width='100px;'><b>Running Date</b></td>
					<td>:</td>
					<td>".date("m/d/Y h:i")."</td>
				</tr>
				<tr>
					<td><b>Date Range</b></td>
					<td>:</td>
					<td>".$_GET['datefrom']." - ".$_GET['dateto']."</td>
					<td><b>Create By</b></td>
					<td>:</td>
					<td>".$_SESSION['user_session_name']."</td>
				</tr>
			</table>
		</div>";
	
	$header = "<div class='pageset'>";
	$header .= "<table width='100%' cellpadding='0' cellspacing='0' border='1' class='tablelist'>
			<tr>
				<th width='10%'>Reservation No.</th>
				<th width='10%'>Item Code</th>
				<th>Item Description</th>
				<th width='10%'>Commitment Date</th>
				<th width='10%'>Approved Quantity</th>
				<th width='10%'>Delivered Quantity</th>
				<th width='10%'>DR No.</th>
				<th width='10%'>DR Date</th>
			</tr>";
			
	$footer = "</table>";
	$footer .= "</div>";
	
	$reservation = ReservationDetails($datefrom, $dateto, $search, $page, $total, true, $branchID);
	if($reservation->num_rows){
		
		while($res = $reservation->fetch_object()){
			
			if($counter == 1){
				echo $header;
			}
			
			$delivery = getDeliveryNo($res->ReservationNo, $res->ProductCode, $branchID);
			$DeliveryNo = implode(',', $delivery['DeliveryNo']);
			$DeliveryDate = implode(',', $delivery['DeliveryDate']);
			
			echo "<tr>
					<td align='center'>".$res->ReservationNo."</td>
					<td align='center'>".$res->ProductCode."</td>
					<td>".$res->ProductName."</td>
					<td align='center'>".$res->CommitmentDate."</td>
					<td align='right'>".$res->ApprovedQuantity."</td>
					<td align='right'>".$res->LoadedQuantity."</td>
					<td align='center'>$DeliveryNo</td>
					<td align='center'>$DeliveryDate</td>
				</tr>";
			
			if($row == $counter){
				echo $footer;
				$counter = 0;
			}else{
				if($counter == $reservation->num_rows){
					echo $footer;
				}
			}
			
			$counter++;
			$count++;
		}
		
	}else{
		echo $header;
		echo "<tr>
				<td colspan='8' align='center'>No result found.</td>
			</tr>";
		echo $footer;
	}
	
?>