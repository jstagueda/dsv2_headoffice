<?php 

function StatementOfAccount($CustomerID, $Datefrom, $Dateto, $page, $total, $istotal, $BranchID){
	
	global $database;
	
	$start = ($page > 1) ? ($page - 1) * $total : 0;
	$limit = ($istotal) ? "" : " LIMIT $start, $total";
	
	$query = $database->execute("SELECT
									tj.TxnDate,
									IF(tj.TxnType = 'SI', (SELECT DocumentNo FROM salesinvoice WHERE ID = tj.RefTxnTypeID AND LOCATE('-".$BranchID."', HOGeneralID) > 0), 
										IF(tj.TxnType = 'OR', (SELECT DocumentNo FROM officialreceipt WHERE ID = tj.RefTxnTypeID AND LOCATE('-".$BranchID."', HOGeneralID) > 0), 
										IF(tj.TxnType IN ('DM', 'CM'), (SELECT DocumentNo FROM dmcm WHERE ID = tj.RefTxnTypeID AND LOCATE('-".$BranchID."', HOGeneralID) > 0), ''))) ReferenceNo,
									tj.TxnType,
									IF(tj.TxnType = 'SI' AND tj.TotalAmount < 0, tj.TotalAmount, 
										IF(tj.TxnType = 'OR' AND tj.TotalAmount >= 0, tj.TotalAmount, 
										IF(tj.TxnType = 'CM', tj.TotalAmount, 0))) Credit,
									IF(tj.TxnType = 'DM', tj.TotalAmount, 
										IF(tj.TxnType = 'SI' AND tj.TotalAmount >= 0, tj.TotalAmount,
										IF(tj.TxnType = 'OR' AND tj.TotalAmount < 0, tj.TotalAmount, 0))) Debit
									FROM igs_transactionjournal tj
								WHERE DATE(tj.TxnDate) BETWEEN '".$Datefrom."' AND '".$Dateto."'
								AND tj.CustomerID = ".$CustomerID."
								AND LOCATE('-".$BranchID."', tj.HOGeneralID) > 0
								$limit");
	
	return $query;
	
}

function StatementOfAccountByDateRange($Dateto, $CustomerID, $days, $BranchID){
	global $database;
	$query = $database->execute("SELECT
									IF(tj.TxnType = 'SI' AND tj.TotalAmount < 0, tj.TotalAmount, 
										IF(tj.TxnType = 'OR' AND tj.TotalAmount >= 0, tj.TotalAmount, 
										IF(tj.TxnType = 'CM', tj.TotalAmount, 0))) Credit,
									IF(tj.TxnType = 'DM', tj.TotalAmount, 
										IF(tj.TxnType = 'SI' AND tj.TotalAmount >= 0, tj.TotalAmount,
										IF(tj.TxnType = 'OR' AND tj.TotalAmount < 0, tj.TotalAmount, 0))) Debit
									FROM igs_transactionjournal tj
								WHERE DATE(tj.TxnDate) BETWEEN ADDDATE('".$Dateto."', ".$days.") AND '".$Dateto."'
								AND LOCATE('-".$BranchID."', tj.HOGeneralID) > 0
								AND tj.CustomerID = ".$CustomerID);
	$total = 0;
	if($query->num_rows){
		while($res = $query->fetch_object()){
			$total = ($total + abs($res->Debit)) - abs($res->Credit);
			if($total < 0){
				$total = 0;
			}
		}
	}
	return number_format($total, 2);
}

function Past60Days($Dateto, $CustomerID, $days, $BranchID){
	global $database;
	$query = $database->execute("SELECT
									IF(tj.TxnType = 'SI' AND tj.TotalAmount < 0, tj.TotalAmount, 
										IF(tj.TxnType = 'OR' AND tj.TotalAmount >= 0, tj.TotalAmount, 
										IF(tj.TxnType = 'CM', tj.TotalAmount, 0))) Credit,
									IF(tj.TxnType = 'DM', tj.TotalAmount, 
										IF(tj.TxnType = 'SI' AND tj.TotalAmount >= 0, tj.TotalAmount,
										IF(tj.TxnType = 'OR' AND tj.TotalAmount < 0, tj.TotalAmount, 0))) Debit
									FROM igs_transactionjournal tj
								WHERE DATE(tj.TxnDate) BETWEEN ADDDATE(ADDDATE('".$Dateto."', -60), -60) AND ADDDATE('".$Dateto."', ".$days.")
								AND LOCATE('-".$BranchID."', tj.HOGeneralID) > 0
								AND tj.CustomerID = ".$CustomerID);
	
	$total = 0;
	if($query->num_rows){
		while($res = $query->fetch_object()){
			$total = ($total + abs($res->Debit)) - abs($res->Credit);
			if($total < 0){
				$total = 0;
			}
		}
	}
	return number_format($total, 2);
}

?>