<style>
.pageset table{font-size:12px; font-family:arial; border-collapse:collapse;}
h2{font-family:arial; text-align:center; font-size:16px;}
.pageset table td{padding:5px;}
.pageset table tr.header td{font-weight:bold; text-align:center;}

@page{
	margin : 0.5in 0;	
}

</style>

<?php 

include "../../initialize.php";
include IN_PATH."scProvisionalReceipt.php";

echo "<h2>Provisional Receipt Register</h2>";

$datefrom = date('Y-m-d', strtotime($_GET['datefrom']));
$dateto = date('Y-m-d', strtotime($_GET['dateto']));		
$customerfrom = $_GET['sfaccountfromHidden'];
$customerto = $_GET['sfaccounttoHidden'];
$sfmlevel = $_GET['sfmlevel'];
$page = $_GET['page'];
$total = 10;
$branch = $_GET['BranchID'];

$provisionalreceiptTotal = provisionalreceipt($datefrom, $dateto, $sfmlevel, $customerfrom, $customerto, $page, $total, true, $branch);

$header = "<table width='100%' border='1' cellpadding='0' cellspacing='0' class='bordergreen'>
		<tr class='header'>
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

if($provisionalreceiptTotal->num_rows){
	
	echo "<div class='pageset'>";
	echo $header;
	$totalamount = 0;
	
	while($res = $provisionalreceiptTotal->fetch_object()){
		
		$totalamount += $res->TotalAmount;
	
		echo "<tr>
						
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
	
	echo "<tr>
			<td colspan='7' align='right'>Total Amount : </td>
			<td align='right'>".number_format($totalamount, 2)."</td>
			<td></td>
		</tr>";
	
	echo "</table></div>";
	
}else{
	echo "<div class='pageset'>";
	echo $header;
	
	echo "<tr>
			<td colspan='9' align='center'><span class='txtredsbold'>No record(s) to display.</span></td>
		</tr>";
	
	echo "</table></div>";
}

?>