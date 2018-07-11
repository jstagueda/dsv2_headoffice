<?php 

include "../../../initialize.php";
include IN_PATH.DS."pagination.php";
include IN_PATH.DS."reportheader.php";

function CountDMCM($frmdate2, $todate2, $rid, $branch){
	global $database;
		
	$query = $database->execute("
		SELECT SUM(Debit) TotalDebit, SUM(Credit) TotalCredit FROM
			(SELECT
				CASE d.`MemoTypeID` WHEN 1 THEN d.`TotalAmount` WHEN 2 THEN '0'  END AS Debit,
				CASE d.`MemoTypeID` WHEN 1 THEN '0' WHEN 2 THEN d.`TotalAmount` END AS Credit
			FROM dmcm d
			INNER JOIN branch b ON b.ID = SPLIT_STR(d.HOGeneralID, '-', 2)
			INNER JOIN customer c ON d.`CustomerID` = c.`ID`
				AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
			INNER JOIN ordmcmreasons ordc ON ordc.ReasonCode = d.ORDMCMReasonCodes
			WHERE DATE(d.`TxnDate`) BETWEEN '".$frmdate2."' AND '".$todate2."'
			AND b.ID = $branch
			AND ordc.ID = ".$rid.") atbl");
		
	return $query;
	
}

function spSelectDMCMRegisterCount($frmdate2, $todate2, $rid, $page, $total, $isTotal, $branch){
	global $database;
	
	$start = ($page > 0)?($page - 1) * $total : 0;
	$limit = (!$isTotal)?" LIMIT $start, $total":"";
	
	$xrid = ($rid > 0) ? " AND ordc.ID = ".$rid : " ";
	
	$query = $database->execute("SELECT d.`TxnDate`,
			d.`DocumentNo` RefNo,
			d.`DocumentNo`, d.`Particulars`,
			IF(IFNULL(md.OfficialReceiptID, 0) = 0, TRIM(c.Code), '') CustomerCode,
			IF(IFNULL(md.OfficialReceiptID, 0) = 0, TRIM(c.Name), md.Names) CustomerName,
			CASE d.`MemoTypeID` WHEN 1 THEN d.`TotalAmount` WHEN 2 THEN '0'  END AS Debit,
			CASE d.`MemoTypeID` WHEN 1 THEN '0' WHEN 2 THEN d.`TotalAmount`
			END AS Credit, ordc.`ReasonCode` Reasonname, ordc.`ReasonName` Rname, c.`Code` CustCode,
			ordc.`ID` reasonID, d.`Remarks` rem, b.`code` bcode, b.`name` bname,
			CAST(CONCAT(IF(d.MemoTypeID=1,'DM','CM'),REPEAT('0', (8- LENGTH(d.DMorCMID))), 
			(d.DMorCMID)) AS CHAR) DmOrCM
		FROM dmcm d
		INNER JOIN branch b ON b.ID = SPLIT_STR(d.HOGeneralID, '-', 2)
		INNER JOIN customer c ON d.`CustomerID` = c.`ID`
			AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
		INNER JOIN ordmcmreasons ordc ON ordc.ReasonCode = d.ORDMCMReasonCodes
		LEFT JOIN miscdetails md ON md.OfficialReceiptID = dc.OfficialReceiptID
		WHERE DATE(d.`TxnDate`) BETWEEN '".$frmdate2."' AND '".$todate2."'
		AND b.ID = $branch
		$xrid
		ORDER BY d.MemoTypeID, d.`TxnDate`,   d.DMorCMID ASC
		$limit");
		
	return $query;
}

if(isset($_POST['action'])){

	if($_POST['action'] == 'SearchMemoDetails'){
		
		$reason = $_POST['reason'];
		$branch = $_POST['branch'];
		$frmdate2 = date('Y-m-d', strtotime($_POST['datefrom']));
		$todate2 = date('Y-m-d', strtotime($_POST['dateto']));
		$total = 10;
		$page = $_POST['page'];
	
		$DMCMquery = spSelectDMCMRegisterCount($frmdate2, $todate2, $reason, $page, $total, false, $branch);		
		$DMCMqueryCount = spSelectDMCMRegisterCount($frmdate2, $todate2, $reason, $page, $total, true, $branch)->num_rows;
		
		$header = "<table width='100%' cellspacing='0' cellpadding='0' border='0' align='center' class='bordersolo' style='border-top:none;'>
				<tr class='trheader'>
					<td>Transaction Date</td>
					<td>DM/CM No.</td>
					<td>Reason Code</td>
					<td>Transaction No.</td>
					<td>Customer Code</td>
					<td>Name</td>
					<td>Debit</td>
					<td>Credit</td>
					<td>Remarks</td>
				</tr>";
		$counter = 1;
		$results = array();
		$RID = array();
		
		echo $header;
		
		if($DMCMquery->num_rows > 0){
			while($res = $DMCMquery->fetch_object()){
				if(!in_array($res->Rname, $results)){
				
					//if($counter > 1){
					//	$totalcountquery = CountDMCM($frmdate2, $todate2, $RID[count($RID) - 1], $branch);
					//	$totalcount = $totalcountquery->fetch_object();
					//	echo "<tr class='trheader'>
					//			<td colspan='5' style='text-align:right;'>".$results[count($results) - 1]." Subtotal : </td>
					//			<td  style='text-align:right;'>".number_format($totalcount->TotalDebit,2)."</td>
					//			<td  style='text-align:right;'>".number_format($totalcount->TotalCredit,2)."</td>
					//			<td></td>
					//		</tr>";						
					//}
					//
					//echo "<tr class='trheader'><td colspan='10' style='text-align:left;'>".$res->Rname."</td></tr>";
					//$results[] = $res->Rname;
					//$RID[] = $res->reasonID;
				}
				
				echo '<tr class="trlist">';
					echo '<td align="center" width="10%">'.date('m/d/Y',strtotime($res->TxnDate)).'</td>';
					echo '<td align="center" width="15%">'.$res->RefNo.'</td>';
					echo '<td align="center" width="15%">'.$res->Rname.'</td>';
					echo '<td align="center" width="10%">'.$res->DmOrCM.'</td>';
					echo '<td align="center" width="25%">'.$res->CustomerCode.'</td>';
					echo '<td align="left" width="25%">'.$res->Customer.'</td>';		
					echo '<td align="right" width="10%">'.number_format($res->Debit,2).'</td>';
					echo '<td align="right" width="10%">'.number_format($res->Credit,2).'</td>';
					echo '<td align="center" width="10%">'.$res->rem.'</td>';
				echo '</tr>';
				
				if($counter == $DMCMquery->num_rows){
					//$totalcountquery = CountDMCM($frmdate2, $todate2, $res->reasonID, $branch);
					//$totalcount = $totalcountquery->fetch_object();
					//echo "<tr class='trheader'>
					//		<td colspan='5'  style='text-align:right;'>".$res->Rname." Subtotal : </td>
					//		<td  style='text-align:right;'>".number_format($totalcount->TotalDebit,2)."</td>
					//		<td style='text-align:right;'>".number_format($totalcount->TotalCredit,2)."</td>
					//		<td></td>
					//	</tr>";
					//	
				}
				
				$counter++;
				
			}
			
		}else{
			echo '<tr class="trlist">
				<td colspan="9" align="center">No result found.</td>
			</tr>';
		}
		
		echo '</table>';
		
		echo '<div style="margin-top:10px;">'.AddPagination($total, $DMCMqueryCount, $page).'</div>';
		
		die();
		
	}
	
}


//print report
if(isset($_GET['action'])){
	if($_GET['action'] == 'PrintDMCMRegister'){
		
		echo '<style>
				body{font-family:arial;}
				.setPage{margin-bottom:20px;}
				.setPage table{
					border-collapse : collapse;
					font-size:12px;
				}

				.trlist td{
					padding:5px;
				}

				.trheader td{padding:5px; font-weight:bold;}

				@page{
					margin-top:0.5in;
					margin-bottom:0.5in;
					size:landscape;
				}

				@media print{
					.setPage{page-break-after: always; margin-bottom:0px;}	
				}
				</style>';
		
		$reason = $_GET['reason'];
		$branch = $_GET['branch'];
		$frmdate2 = date('Y-m-d', strtotime($_GET['datefrom']));
		$todate2 = date('Y-m-d', strtotime($_GET['dateto']));
		$total = 10;
		$page = $_GET['page'];
	
		$DMCMquery = spSelectDMCMRegisterCount($frmdate2, $todate2, $reason, $page, $total, true, $branch);
		
		$header = '<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
			<tr class="trheader">
				<td height="20" align="center" width="10%"><strong>Transaction Date</strong></td>				
				<td height="20" align="center" width="10%"><strong>DM/CM No.</strong></td>
				<td height="20" align="center" width="10%"><strong>Reason Code</strong></td>
				<td height="20" align="center" width="15%"><strong>Transaction No.</strong></td>
				<td height="20" align="center" width="10%"><strong>Customer Code</strong></td>
				<td height="20" align="center" width="25%"><strong>Name</strong></td>
				<td height="20" align="center" width="10%"><strong>Debit</strong></td>
				<td height="20" align="center" width="10%"><strong>Credit</strong></td>
				<td height="20" align="center" width="10%"><strong>Remarks</strong></td>
			</tr>';
		
		
		$pageCounter = 1;
		$num = $DMCMquery->num_rows;
		$rows = 18;
		$results = array();
		$counter = 1;
		$debit 	= 0;
		$credit = 0;
		
		$branchquery = $database->execute("SELECT * FROM branch WHERE ID = ".$branch."");
		$branch = $branchquery->fetch_object();
		
		if($DMCMquery->num_rows > 0){
			while($row = $DMCMquery->fetch_object()){		
				if($counter == 1){
					echo '<div class="setPage">';
					
						echo reportheader('Memo Register', $_SESSION['user_session_name'], $branch->Code.' - '.$branch->Name, $branch->StreetAdd, true, date('m/d/Y', strtotime($frmdate2)), date('m/d/Y', strtotime($todate2)));					
						echo '<br>';
						echo $header;
				}
			
				//table header
				if($pageCounter == 1 AND $counter > 1){
					echo '<div class="setPage">';
					echo $header;
				}
			
			//total
				if(!in_array($row->Rname, $results)){
					//if($counter > 1){
					//	echo "<tr class='trheader'>
					//						<td colspan='5' align='right'>".$results[count($results) - 1]." Subtotal : </td>
					//						<td align='center'>".number_format($debit,2)."</td>
					//						<td align='center'>".number_format($credit,2)."</td>
					//						<td></td>
					//					</tr>";
					//	$pageCounter++;
					//}
					//$debit 	= 0;
					//$credit = 0;
					//echo "<tr class='trheader'><td colspan='10'>".$row->Rname."</td></tr>";
					//$pageCounter++;
					//$results[] = $row->Rname;
				}
			
				$debit 	+= $row->Debit;
				$credit += $row->Credit;
					
				echo '<tr class="trlist">';
					echo '<td height="20" align="center" width="10%">'.date('m/d/Y',strtotime($row->TxnDate)).'</td>';
					echo '<td height="20" align="center" width="15%">'.$row->RefNo.'</td>';
					echo '<td height="20" align="center" width="15%">'.$row->Rname.'</td>';
					echo '<td height="20" align="center" width="10%">'.$row->DmOrCM.'</td>';
					echo '<td height="20" align="center" width="25%">'.$row->CustomerCode.'</td>';
					echo '<td height="20" align="center" width="25%">'.$row->Customer.'</td>';		
					echo '<td height="20" align="center" width="10%">'.number_format($row->Debit,2).'</td>';
					echo '<td height="20" align="center" width="10%">'.number_format($row->Credit,2).'</td>';
					echo '<td height="20" align="center" width="10%">'.$row->rem.'</td>';
				echo '</tr>';
			
				if($pageCounter == $rows AND $num != $counter){
					echo '</table></div>';
					$pageCounter = 0;
				}else{
					if($num == $counter){
						echo "<tr class='trheader'>
											<td colspan='6' align='right'> Total : </td>
											<td align='center'>".number_format($debit,2)."</td>
											<td align='center'>".number_format($credit,2)."</td>
											<td></td>
										</tr>";
						echo '</table></div>';
						//echo "<tr class='trheader'>
						//					<td colspan='5' align='right'>".$row->Rname." Subtotal : </td>
						//					<td align='center'>".number_format($debit,2)."</td>
						//					<td align='center'>".number_format($credit,2)."</td>
						//					<td></td>
						//				</tr>";
						//echo '</table></div>';
						$pageCounter = 0;
					}
				}
			
				$counter++;
				$pageCounter++;
			}
		}else{
		
			echo '<div class="setPage">';
					
			echo '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td height="20" align="center" style="font-size:16px;"><strong>Memo Register</strong></td>
				</tr>
				<tr>
					<td align="right"><b>Date From : </b>'.date('m/d/Y', strtotime($frmdate2)).'</td>
				</tr>
				<tr>
					<td align="right"><b>Date To : </b>'.date('m/d/Y', strtotime($todate2)).'</td>
				</tr>
				</table>
				<br>';
			echo $header;
			echo '<tr class="trlist">';
				echo '<td align="center" colspan="8">No result found.</td>';
			echo '</tr>';
			echo '</table></div>';
		}
		
	}
}

?>