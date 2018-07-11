<style>
body{font-family:arial; font-size:12px;}
.setPage{margin-bottom:20px;}
.setPage table{
	border-collapse : collapse;
	font-size:12px;
}

.trlist td{
	padding:5px;
}

.trheader td{padding:5px; font-weight:bold; text-align:center;}

@page{
	margin-top:0.5in;
	margin-bottom:0.5in;
	size:landscape;
}

@media print{
	.setPage{page-break-after: always; margin-bottom:0px;}	
}
</style>

<?php 

require_once "../../initialize.php";
include IN_PATH.DS."scStatementOfAccount.php";

$branchquery = $database->execute("SELECT b.Code, b.StreetAdd FROM branchparameter bp INNER JOIN branch b ON b.ID = bp.BranchID");
$branch = $branchquery->fetch_object();

$Customer = $_GET['Customer'];
$CustomerID = $_GET['CustomerID'];
$Datefrom = date('Y-m-d', strtotime($_GET['DateFrom']));
$Dateto =  date('Y-m-d', strtotime($_GET['DateTo']));
$page = 1;
$total = 10;
$counter = 1;
$balance = 0;
$row = 15;
$count = 1;
$BranchID = $_GET['BranchID'];

$CustomerDetailsQuery = $database->execute("SELECT StreetAdd FROM tpi_customerdetails WHERE CustomerID = ".$CustomerID." AND LOCATE(HOGeneralID, '-".$BranchID."') > 0");
$CustomerDetails = $CustomerDetailsQuery->fetch_object();

$title = '<div class="setPage">
			<div style="padding:10px;">
				
				<div style="text-align:center; font-weight:bold; font-size:14px;">Statement of Account</div>
				<div style="clear:both;"></div>
				
				<div style="float:left;">DATE : '.date('m/d/Y').'</div>
				<div style="float:right;">TIME : '.date('h:i:s').'</div>
				<div style="clear:both;"></div>
				
				<hr style="margin:10px 0;" />
				
				<div>Branch : '.$branch->Code.'</div>
				<div>'.$branch->StreetAdd.'</div>
				<div style="clear:both;"></div>
				
				<br />
				<div>'.$Customer.'</div>
				<div>'.$CustomerDetails->StreetAdd.'</div>
				
			</div>';
			
$header = '<table cellpadding="0" cellspacing="0" border="1" width="100%">
				<tr class="trheader">
					<td>Date</td>
					<td>Reference #</td>
					<td>Transaction Type</td>
					<td>Debit</td>						
					<td>Credit</td>
					<td>Balance</td>
				</tr>';

$footer = '</table></div>';

$statementofaccount = StatementOfAccount($CustomerID, $Datefrom, $Dateto, $page, $total, true, $BranchID);

if($statementofaccount->num_rows){
	while($res = $statementofaccount->fetch_object()){
				
		if($counter == 1){
			echo $title;
			echo $header;
			echo '<tr class="trlist">
					<td colspan="5"><b>Beginning Balance</b></td>
					<td align="right"><b>'.number_format($balance, 2).'</b></td>
				</tr>';
		}else{
			if($count == 1){
				echo '<div class="setPage">';
				echo $header;
			}
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
		
		if($statementofaccount->num_rows == $counter){
			echo '<tr class="trlist">
					<td colspan="5" align="right"><b>Total Due</b></td>
					<td align="right"><b>'.number_format($balance, 2).'</b></td>
				</tr>';
			echo '</table>';
			
			echo '<table border="0" width="100%" style="margin-top:10px;">
					<tr style="font-weight:bold;">
						<td align="right">Current</td>
						<td align="right">30 Days</td>
						<td align="right">60 Days</td>
						<td align="right">Past 60 Days</td>
					</tr>
					<tr>
						<td align="right">'.$balance.'</td>
						<td align="right">'.StatementOfAccountByDateRange($Dateto, $CustomerID, -30, $BranchID).'</td>
						<td align="right">'.StatementOfAccountByDateRange($Dateto, $CustomerID, -60, $BranchID).'</td>
						<td align="right">'.Past60Days($Dateto, $CustomerID, -60, $BranchID).'</td>
					</tr>
				</table>';
			echo '<hr />';
			echo '<div style="margin-top:10px;">
					<div style="float:left; width:60px;">NOTICE : </div>
					<p>
						All overdue accounts are subject to penalty, plus interest and other collection charges if account is referred to a third party for collection purposes.
					</p>
					<div style="clear:both;"></div>
				</div>';
			echo '</div>';
		}else{
			if($row == $count){
				echo $footer;
				$count = 0;
			}
		}
		
		$counter++;
		$count++;
	}
}else{
	echo $title;
	echo $header;
	echo '<tr class="trlist">
			<td colspan="6" align="center">No result found.</td>
		</tr>';
	echo $footer;
}

?>