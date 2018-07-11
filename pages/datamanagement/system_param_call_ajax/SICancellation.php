<?php 
	include "../../../initialize.php";
	include IN_PATH."pagination.php";
	
	//functions list	
	//=========================================================================================
	//function to get the list of searched branches
	function GetBranch($Search){
		global $database;
		$query = $database->execute("SELECT 
										ID BranchID, 
										TRIM(Code) BranchCode,
										Name BranchName
									FROM branch
									WHERE (TRIM(Code) LIKE '%$Search%' OR Name LIKE '%$Search%')
									AND ID NOT IN (1,2,3)
									LIMIT 10");
		return $query;
	}
	
	//function to get the list of searched customer	under searched branch
	function GetCustomer($Search, $BranchID){
		global $database;
		$query = $database->execute("SELECT 
										c.ID CustomerID,
										TRIM(c.Code) CustomerCode,
										c.Name CustomerName
									FROM customer c
									INNER JOIN branch b ON b.ID = SPLIT_STR(HOGeneralID, '-', 2)
									WHERE b.ID = $BranchID
									AND (TRIM(c.Code) LIKE '%$Search%' OR c.Name LIKE '%$Search%')
									LIMIT 10");
		return $query;
	}
	
	//function to get the list of searched invoice based on the inputted parameters
	function GetSalesInvoiceList($Search, $BranchID, $CustomerFrom, $CustomerTo, $DateFrom, $DateTo, $page, $total, $istotal){
		global $database;
		$start = ($page > 1) ? ($page - 1) * $total : 0;
		$limit = ($istotal) ? "" : "LIMIT $start, $total";
		
		$customerrange = ($CustomerFrom == 0 AND $CustomerTo == 0) ? "" : "AND (c.ID BETWEEN $CustomerFrom AND $CustomerTo
																			OR c.ID BETWEEN $CustomerTo AND $CustomerFrom)";
		
		$query = $database->execute("SELECT
										si.ID SalesInvoiceID,
										CONCAT('SI', LPAD(si.ID, 8, 0)) DocumentNo,
										si.DocumentNo TransactionID,
										c.Code CustomerCode,
										c.Name CustomerName,
										si.GrossAmount,
										si.NetAmount,
										DATE_FORMAT(si.TxnDate, '%M %d, %Y') TransactionDate,
										s.Name StatusName
									FROM salesinvoice si 
									INNER JOIN branch b ON b.ID = SPLIT_STR(si.HOGeneralID, '-', 2)
									INNER JOIN customer c ON c.ID = si.CustomerID
										AND LOCATE(CONCAT('-', b.ID), c.HOGeneralID) > 0
									INNER JOIN `status` s ON s.ID = si.StatusID
									WHERE b.ID = $BranchID
									AND s.ID != 8
									$customerrange
									AND DATE(si.TxnDate) BETWEEN '$DateFrom' AND '$DateTo'
									AND (TRIM(c.Code) LIKE '%$Search%'
										OR c.Name LIKE '%$Search%'
										OR CONCAT('SI', LPAD(si.ID, 8, 0)) LIKE '$Search%'
										OR si.DocumentNo LIKE '$Search%')
									ORDER BY DATE(si.TxnDate) DESC
									$limit");		
		return $query;
	}
	
	//triggered action from ajax
	//=========================================================================================
	if(isset($_POST['action'])){
		
		//get the list of branch searched
		if($_POST['action'] == "GetBranch"){
			
			$Search = $_POST['Branch'];
			$branchquery = GetBranch($Search);
			
			if($branchquery->num_rows){
				while($res = $branchquery->fetch_object()){
					$result[] = array("Label" => $res->BranchCode." - ".$res->BranchName,
									"Value" => $res->BranchCode." - ".$res->BranchName,
									"ID" => $res->BranchID);
				}
			}else{
				$result[] = array("Label" => "No result found.",
									"Value" => "",
									"ID" => 0);
			}
			
			die(json_encode($result));
			
		}
		
		//get the list of customer searched
		if($_POST['action'] == "GetCustomer"){
			
			$Search = $_POST['Customer'];
			$BranchID = $_POST['BranchID'];
			$customerquery = GetCustomer($Search, $BranchID);
			
			if($customerquery->num_rows){
				while($res = $customerquery->fetch_object()){
					$result[] = array("Label" => $res->CustomerCode." - ".$res->CustomerName,
									"Value" => $res->CustomerCode." - ".$res->CustomerName,
									"ID" => $res->CustomerID);
				}
			}else{
				$result[] = array("Label" => "No result found.",
									"Value" => "",
									"ID" => 0);
			}
			
			die(json_encode($result));
			
		}
		
		//get the list of salesinvoice searched
		if($_POST['action'] == "GetSalesInvoice"){
			
			$Search = $_POST['Search'];
			$BranchID = $_POST['BranchID'];
			$CustomerFrom = $_POST['CustomerFromHidden'];
			$CustomerTo = $_POST['CustomerToHidden'];
			$DateFrom = date("Y-m-d", strtotime($_POST['DateFrom']));
			$DateTo = date("Y-m-d", strtotime($_POST['DateTo']));
			$page = $_POST['page'];
			$total = 10;
			
			$salesinvoicequery = GetSalesInvoiceList($Search, $BranchID, $CustomerFrom, $CustomerTo, $DateFrom, $DateTo, $page, $total, false);
			$salesinvoicequeryTotal = GetSalesInvoiceList($Search, $BranchID, $CustomerFrom, $CustomerTo, $DateFrom, $DateTo, $page, $total, true);
			
			echo '<table cellspacing="0" cellpadding="0" class="bordersolo" style="border-top:none;" width="100%">
					<tr class="trheader">
						<td width="10%">Transaction No.</td>
						<td width="10%">Document No.</td>
						<td width="10%">Customer Code</td>
						<td>Customer Name</td>
						<td width="10%">Gross Amount</td>
						<td width="10%">Net Amount</td>
						<td width="10%">Transaction Date</td>
						<td width="10%">Status</td>
						<td width="5%">Action</td>
					</tr>';
			
			if($salesinvoicequery->num_rows){
				while($res = $salesinvoicequery->fetch_object()){
					echo "<tr class='trlist'>
							<td align='center'>".$res->TransactionID."</td>
							<td align='center'>".$res->DocumentNo."</td>
							<td align='center'>".$res->CustomerCode."</td>
							<td>".$res->CustomerName."</td>
							<td align='right'>".number_format($res->GrossAmount, 2)."</td>
							<td align='right'>".number_format($res->NetAmount, 2)."</td>
							<td align='center'>".$res->TransactionDate."</td>
							<td align='center'>".$res->StatusName."</td>
							<td align='center'>
								<input type='button' value='Cancel' name='btnCancel' onclick='return CancelSalesInvoice(".$res->SalesInvoiceID.", ".$BranchID.");' class='btn'>
							</td>
						</tr>";
				}
			}else{
				echo '<tr class="trlist">
						<td colspan="9" align="center">No result found.</td>
					</tr>';
			}
			
			echo '</table>';
			echo '<div style="margin-top:10px;">'.AddPagination($total, $salesinvoicequeryTotal->num_rows, $page).'</div>';
			die();
			
		}
		
		//reason code for sales invoice cancellation
		if($_POST['action'] == "SICancellationReason"){
			
			$reasons = "";			
			$reasonquery = $database->execute("SELECT ID ReasonID, TRIM(Code) ReasonCode, Name ReasonName FROM reason WHERE ReasonTypeID = 4");
			if($reasonquery->num_rows){
				while($res = $reasonquery->fetch_object()){
					$reasons .= "<option value='".$res->ReasonID."'>".$res->ReasonName."</option>";
				}
			}
			
			echo '<div style="width:350px; margin:10px;">
					<form action="" name="SaveReasonCodeForm" method="post">
						
						<input type="hidden" value="'.$_POST['SalesInvoiceID'].'" name="SalesInvoiceID">
						<input type="hidden" value="'.$_POST['BranchID'].'" name="BranchID">
						
						<table cellpadding="2" cellspacing="2" width="100%">
							<tr>
								<td class="fieldlabel">Reason</td>
								<td class="separator">:</td>
								<td>
									<select class="txtfield" name="ReasonCode">
										<option value="0">Select</option>
										'.$reasons.'
									</select>
								</td>
							</tr>
							<tr>
								<td valign="top" class="fieldlabel">Remarks</td>
								<td valign="top" class="separator">:</td>
								<td>
									<textarea class="txtfield" name="Remarks" style="height:100px; width:200px;"></textarea>								
								</td>
							</tr>
						</table>
					</form>
				</div>';
			die();
			
		}
		
		//save cancellation transaction
		if($_POST['action'] == "SaveTransaction"){
			
			$SalesInvoiceID = $_POST['SalesInvoiceID'];
			$BranchID = $_POST['BranchID'];
			$ReasonCodeID = $_POST['ReasonCode'];
			$Remarks = $_POST['Remarks'];
			
			try{
			
				$database->beginTransaction();
				
				$database->execute("UPDATE salesinvoice SET StatusID = 8, `Changed` = 1 WHERE ID = $SalesInvoiceID AND SPLIT_STR(HOGeneralID, '-', 2) = $BranchID");
				
				$database->commitTransaction();
				$result['Success'] = 1;
				$result['ErrorMessage'] = "<p>Sales Invoice successfully cancelled.</p>";
			
			}catch(Exception $e){
			
				$database->rollback();
				$result['Success'] = 1;
				$result['ErrorMessage'] = "<p>".$e->getMessage()."</p>";
				
			}
			
			die(json_encode($result));
			
		}
		
	}
	
?>