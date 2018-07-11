<style>
    @page{margin: 0.5in 0; size:landscape;}
    body{font-family: arial;}
    .pageset{margin-bottom:20px;}
    .pageset table{font-size:12px; border-collapse: collapse;}
    .pageset table td, .pageset table th{padding:5px;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>

<?PHP 
	require_once "../../initialize.php";
	include IN_PATH.DS."InventoryTransactionRegister.php";
	
	global $database;
	ini_set('max_execution_time', 1000);
	
	$dteFrom = $_GET['dteFrom'];	
	$dteFrom = date("Y-m-d", strtotime($dteFrom));
	$dteTo = $_GET['dteTo'];
	$dteTo = date("Y-m-d", strtotime($dteTo));
	$mtid = $_GET['mtid'];
	$search =$_GET['search'];
	$mtName = 'ALL MOVEMENT TYPE';
	$branchID = $_GET['branch'];
	$txnRegister = '';
	
	$branchquery = $database->execute("SELECT TRIM(Code) Branch FROM branch WHERE ID = $branchID");
	$b = $branchquery->fetch_object();
		
	echo "<table width='100%' cellpadding='0' cellspacing='0' style='font-size:12px; font-family:arial;'>
			<tr>
				<th align='center' colspan='6' style='padding-bottom:10px; font-size:14px;'>Inventory Transaction Register</th>
			</tr>
			<tr>
				<td width='100px'>Branch</td>
				<td width='30px' align='center'>:</td>
				<td width='50%'>".$b->Branch."</td>
				<td width='100px'>Run Date</td>
				<td width='30px' align='center'>:</td>
				<td>".date("m/d/Y h:i a")."</td>
			</tr>
			<tr>
				<td width='100px'>Date</td>
				<td width='30px' align='center'>:</td>
				<td width='50%'>".date('m/d/Y', strtotime($dteFrom)).' - '.date('m/d/Y', strtotime($dteTo))."</td>
				<td width='100px'>Run By</td>
				<td width='30px' align='center'>:</td>
				<td>".$_SESSION['user_session_name']."</td>
			</tr>
		</table><br />";
	
	$header = "<div class='pageset'>
				<table width='100%' cellspacing='0' cellpadding='0' border='1'>
					<tr>
						<th width='8%'>Transaction Date</th>
						<th width='5%'>Movement Type</th>
						<th width='8%'>Reference No.</th>
						<th width='8%'>Item Code</th>
						<th>Item Description</th>
						<th width='5%'>Issuing Branch</th>
						<th width='5%'>Location</th>
						<th width='5%'>Receiving Branch</th>
						<th width='5%'>Location</th>
						<th width='8%'>CSP</th>
						<th width='5%'>Qty In</th>
						<th width='5%'>Qty Out</th>
						<th width='8%'>Total</th>
					</tr>";
	
	$footer = "</table></div>";
	
	$cnt = 0;
	$tot = 0;
	$totalQty = 0;
	$total = 0;
	$movementtype = "";
	$ReferenceNo = "";
	$rows = 15;
	$counter = 1;
	
	$spSelectInventoryRegister = inventorytransactionreport(1, 10, true, $dteFrom, $dteTo, $mtid, $search, $branchID);
	$inventorytransactionTotal = inventorytransactionTotal($txtStartDates, $txtEndDates, $cboWarehouse, $txtSearch, $branchID);
	
	if($spSelectInventoryRegister->num_rows){
		
		$TotalQtyIn = 0;
		$TotalQtyOut = 0;
		$TotalAmount = 0;
		
		$QtyIn = 0;
		$QtyOut = 0;
		$Amout = 0;
		
		while($row = $spSelectInventoryRegister->fetch_object()){
			
			$cnt ++;
			$tot = $row->Qty * $row->UnitPrice;
			$qtyPrice = number_format($tot,2);
			
			if($counter == 1){
				echo $header;
			}
			
			if($cnt > 1){
				if($ReferenceNo != $row->RefTxnNo){
					echo "<tr class=\"trlist\">
						<td align='right' colspan=10><b>TOTAL FOR $row->RefTxnNo</b></td>   
						<td align='right'>".number_format($QtyIn)."</td>    
						<td align='right'>".number_format($QtyOut)."</td> 
						<td align='right'>".number_format($Amout,2)."</td>   
					</tr>";
					$QtyIn = 0;
					$QtyOut = 0;
					$Amout = 0;
				}
			}
			
			echo "<tr>
                <td align='center'>$row->TxnDate</td>
                <td align='center'>$row->MovementCode</td>
                <td align='center'>$row->RefTxnNo</td>
                <td align='center'>$row->Code</td>
                <td align='left'>$row->Product</td>
                <td align='center'>$row->IssuingBranch</td>
                <td align='center'>$row->Location1</td>
                <td align='center'>$row->ReceivingBranch</td>
                <td align='center'>$row->Location2</td>
                <td align='right'>".number_format($row->UnitPrice,2)."</td>
                <td align='right'>$row->QtyIn </td>
				<td align='right'>$row->QtyOut </td>
                <td align='right'>$qtyPrice</td>
            </tr>";
			
			$TotalQtyIn += $row->QtyIn;
			$TotalQtyOut += $row->QtyOut;
			$TotalAmount += $tot;
			
			$QtyIn += $row->QtyIn;
			$QtyOut += $row->QtyOut;
			$Amout += $tot;
			
			if($cnt == $spSelectInventoryRegister->num_rows){
			
				echo "<tr class=\"trlist\">
						<td align='right' colspan=10><b>TOTAL FOR $row->RefTxnNo</b></td>   
						<td align='right'>".number_format($QtyIn)."</td>    
						<td align='right'>".number_format($QtyOut)."</td> 
						<td align='right'>".number_format($Amout,2)."</td>   
					</tr>";
					
				echo "<tr class=\"trlist\">
					<td align='right' colspan=10><b>GRAND TOTAL</b></td>
					<td align='right'>".number_format($TotalQtyIn)."</td>
					<td align='right'>".number_format($TotalQtyOut)."</td>
					<td align='right'>".number_format($TotalAmount, 2)."</td>
				</tr>";
				echo $footer;
			}else{
				
				if($rows == $counter){
					echo $footer;
					$counter = 0;
				}
				
			}
			
			$movementtype = $row->MovementType;
			$ReferenceNo = $row->RefTxnNo;
			$counter++;
		}
		
	}else{
		echo $header;
		echo "<tr>
				<td align='center' colspan='13'>No result found.</td>
			</tr>";
		echo $footer;
	}
	
	echo '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size:12px;">
			<tr>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>
				<td height="20" align="left" width="15%"><strong>&nbsp;Prepared By :   </strong></td>
				<td height="20" align="left" width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;___________________________</td>       
				<td height="20" align="left" width="60%">&nbsp;</td>
			</tr>
			<tr>
				<td height="20" width="15%" >&nbsp;</td>
				<td height="20" align="left" width="25%" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$preparedby.' </td>       
				<td height="20" align="left" width="60% >&nbsp;</td>
			</tr>
		</table>
		<br><br><br>
		<table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="font-size:12px;">
			<tr>
				<td height="20" align="left" width="15%"><strong>&nbsp;Approved By :   </strong></td>
				<td height="20" align="left" width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_____________________________ </td>       
				<td height="20" align="left" width="60%"> </td>
			</tr>
			<tr>
				<td height="20">&nbsp;</td>
				<td height="20" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$approvedby.' </td>       
				<td height="20" align="left">&nbsp;</td>          	             		
			</tr>
		</table>';
	
    /*$preparedby = GetSettingValue($database, 'PREPAREDBY');
	$approvedby = GetSettingValue($database, 'APPROVEDBY');*/
        
	//$spSelectInventoryRegisterCount = $sp->spSelectInventoryRegisterCount($database, $mtid, $dteFrom, $dteTo, $search);
	/*$spSelectInventoryRegisterCount = inventorytransactionreport(1, 10, true, $dteFrom, $dteTo, $mtid, $search, $branchID);
	//$inventorytransactionTotal = inventorytransactionTotal($dteFrom, $dteTo, $mtid, $search);
	
	if ($spSelectInventoryRegisterCount->num_rows){
		$j = 0; 
		// Estimated string length of product description when the TCPDF engine will wrap the text,
		// therfore consuming and extra row
		$productLenThreshold = 24;
		
		// Threshold to determine whether the number of rows per page should be decremented
		// to accomodate product whose length is greater than $productLenThreshold
		$rowDeletionThreshold = 2; 
		
		// Counter of current row deletion threshold
		$deletionThreshold = 0;
		
		// Estimated number of rows per page
		$numRowsPerPage = 20;
		
		$numRows = $numRowsPerPage;
		
		$ctr = 0;
		$tot = 0;
		$ftot = 0;
		$total = 0;
		$totalQty = 0;
		$counter = 0;
		$QtyInTotal = 0;
		$QtyOutTotal = 0;
		
		//$invTotal = $inventorytransactionTotal->fetch_object();
		
		while ($row = $spSelectInventoryRegisterCount->fetch_object()){
			$counter++;
			$ctr += 1;
			$qtyin = $row->QtyIn;
			$qtyout = $row->QtyOut;
			$tot = $row->Qty * $row->UnitPrice;
			$ftot = number_format($tot,2);
			$total += $tot;
			$totalQty += $row->Qty;
			$QtyInTotal += $row->QtyIn;
			$QtyOutTotal += $row->QtyOut;
			
			if($counter > 1){
				if($ReferenceNo != $row->RefTxnNo){
					$totalPerMovement = inventorytransactionTotalPerMovement($dteFrom, $dteTo, $mtid, $movementtype, $ReferenceNo, $branchID);
					$mt = $totalPerMovement->fetch_object();
					$txnRegister .= "<tr class=\"trlist\">
						<td align='right' colspan=10><b>TOTAL FOR $ReferenceNo</b></td>   
						<td align='right'>".number_format($mt->QtyInTotal)."</td>    
						<td align='right'>".number_format($mt->QtyOutTotal)."</td>    
						<td align='right'>".number_format($mt->TotalAmount,2)."</td>   
						</tr>";
					$counter = 0;
				}
			}
			
			if ($j < $numRows)
			{
				$txnRegister .= '<tr>
                                    <td height="20" align="center" width="7%">'.$row->TxnDate.'</td>
                                    <td height="20" align="center">'.$row->MovementCode.'</td>
                                    <td height="20" align="center" width="7%">'.$row->RefTxnNo.'</td>
                                    <td height="20" align="center" width="7%">'.$row->Code.'</td>
                                    <td height="20" align="center" width="20%">'.$row->Product.'</td>
                                    <td height="20" align="center" width="7%">'.$row->IssuingBranch.'</td>
                                    <td height="20" align="center" width="7%">'.$row->Location1.'</td>
                                    <td height="20" align="center" width="7%">'.$row->ReceivingBranch.'</td>
                                    <td height="20" align="center" width="7%">'.$row->Location2.'</td>
                                    <td height="20" align="right" width="7%">'.$row->UnitPrice.'</td>
                                    <td height="20" align="right" width="7%">'.$row->QtyIn.'</td>
									<td height="20" align="right" width="7%">'.$row->QtyOut.'</td>
                                    <td height="20" align="right" width="7%">'.$ftot.'</td>
	  			</tr>';	  			
				
				// Determine if product string length is greater than threshold
				// If it is, subtract the number of rows per page if necessary
				if (strlen($row->Product) > $productLenThreshold || strlen($row->MovementCode) > $productLenThreshold) 
				{
					// Subtract the number of rows only if we reached threshold of the number
					// of rows whose string length is greater than product length threshold
					if ($deletionThreshold != $rowDeletionThreshold) 
					{
						$numRows--;
						$deletionThreshold++;
					}
					else 
					{
						// Reset the current count
						$deletionThreshold = 0;
					}
				}
				$j++;
			}
			else
			{
				// We only print the page to PDF if we have enough rows to print
				$txnRegister .= '<tr>
				  		<td height="20" align="center" width="7%">'.$row->TxnDate.'</td>
					  	<td height="20" align="center">'.$row->MovementCode.'</td>
					  	<td height="20" align="center" width="7%">'.$row->RefTxnNo.'</td>
				  		<td height="20" align="center" width="7%">'.$row->Code.'</td>
					  	<td height="20" align="center" width="20%">'.$row->Product.'</td>
					  	<td height="20" align="center" width="7%">'.$row->IssuingBranch.'</td>
					  	<td height="20" align="center" width="7%">'.$row->Location1.'</td>
					  	<td height="20" align="center" width="7%">'.$row->ReceivingBranch.'</td>
					  	<td height="20" align="center" width="7%">'.$row->Location2.'</td>
					  	<td height="20" align="right" width="7%">'.$row->UnitPrice.'</td>
					  	<td height="20" align="right" width="7%">'.$row->QtyIn.'</td>
						<td height="20" align="right" width="7%">'.$row->QtyOut.'</td>
					  	<td height="20" align="right" width="7%">'.$ftot.'</td>
		  			</tr>';
					
				$html = '<div class="pageset"><table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
						<tr>
							<td height="20" align="center" width="7%"><strong>Transaction Date</strong></td>
			            	<td height="20" align="center"><strong>Movement Type</strong></td>
			              	<td height="20" align="center" width="7%"><strong>Reference No.</strong></td>
			              	<td height="20" align="center" width="7%"><strong>Item Code</strong></td>
			              	<td height="20" align="center" width="20%"><strong>Item Description</strong></td>
			              	<td height="20" align="center" width="7%"><strong>Issuing BR</strong></td>                      	
			              	<td height="20" align="center" width="7%"><strong>Location</strong></td>
			              	<td height="20" align="center" width="7%"><strong>Receiving BR</strong></td>
			              	<td height="20" align="center" width="7%"><strong>Location</strong></td>
			              	<td height="20" align="center" width="7%"><strong>CSP </strong></td> 
			              	<td height="20" align="center" width="7%"><strong>Qty In</strong></td>   
							<td height="20" align="center" width="7%"><strong>Qty Out</strong></td>  
			              	<td height="20" align="center" width="7%"><strong>Total</strong></td>  
			         	</tr>'.$txnRegister.
					'<tr>
				</table></div>';
	       		
                echo $html;
				
				// reset the row counter and the details 
				$txnRegister = '';
				$j = 0;
				$numRows = $numRowsPerPage;				
			}
			
			if($ctr == $spSelectInventoryRegisterCount->num_rows){
				$totalPerMovement = inventorytransactionTotalPerMovement($dteFrom, $dteTo, $mtid, $row->MovementType, $row->RefTxnNo, $branchID);
				$mt = $totalPerMovement->fetch_object();
				$txnRegister .= "<tr class=\"trlist\">
						<td align='right' colspan=10><b>TOTAL FOR $row->RefTxnNo</b></td>   
						<td align='right'>".number_format($mt->QtyInTotal)."</td>    
						<td align='right'>".number_format($mt->QtyOutTotal)."</td>  
						<td align='right'>".number_format($mt->TotalAmount,2)."</td>   
					</tr>";
					
				$txnRegister .= "<tr class=\"trlist\">
						<td align='right' colspan=10><b>GRAND TOTAL</b></td>   
						<td align='right'>".number_format($QtyInTotal)."</td>    
						<td align='right'>".number_format($QtyOutTotal)."</td>    
						<td align='right'>".number_format($total, 2)."</td>   
					</tr>";
			}
			
			$movementtype = $row->MovementType;
			$ReferenceNo = $row->RefTxnNo;
			
			
		}
		$spSelectInventoryRegisterCount->close();
		
		// If we have gone through all the items and there are 
		// unprinted items left, print them one last time
		
		if ($txnRegister != '')
		{
			$total = number_format($total,2);
			$html .= '<div class="pageset">
						<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
							<tr>
								<td height="20" align="center" width="7%"><strong>Transaction Date</strong></td>
								<td height="20" align="center"><strong>Movement Type</strong></td>
								<td height="20" align="center" width="7%"><strong>Reference No.</strong></td>
								<td height="20" align="center" width="7%"><strong>Item Code</strong></td>
								<td height="20" align="center" width="20%"><strong>Item Description</strong></td>
								<td height="20" align="center" width="7%"><strong>Issuing BR</strong></td>                      	
								<td height="20" align="center" width="7%"><strong>Location</strong></td>
								<td height="20" align="center" width="7%"><strong>Receiving BR</strong></td>
								<td height="20" align="center" width="7%"><strong>Location</strong></td>
								<td height="20" align="center" width="7%"><strong>CSP </strong></td> 
								<td height="20" align="center" width="7%"><strong>Qty In</strong></td>   
								<td height="20" align="center" width="7%"><strong>Qty Out</strong></td>  
								<td height="20" align="center" width="7%"><strong>Total</strong></td>  
							</tr>'.$txnRegister.'
						</table>';
						
			//footer
			$html .= '<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
		            <tr>
		    		<td height="20" align="left" width="15%"><strong>&nbsp;Prepared By :   </strong></td>
		        	<td height="20" align="left" width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;___________________________</td>       
		        	<td height="20" align="left" width="60%">&nbsp;</td>
                    </tr>
                    <tr>
		    		<td height="20" width="15%" >&nbsp;</td>
		        	<td height="20" align="left" width="25%" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$preparedby.' </td>       
		        	<td height="20" align="left" width="60% >&nbsp;</td>
					
                            </tr>
                            </table>
                            <br><br><br><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="20" align="left" width="15%"><strong>&nbsp;Approved By :   </strong></td>
		                <td height="20" align="left" width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_____________________________ </td>       
		                <td height="20" align="left" width="60%"> </td>
                            </tr>
                            <tr>
		            	<td height="20">&nbsp;</td>
		                <td height="20" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$approvedby.' </td>       
		                <td height="20" align="left">&nbsp;</td>          	             		
                            </tr>
                            </table></div>';
				       		
                        echo $html;
		}
	}
	else
	{
		$txnRegister = '<tr ><td height="20" colspan="12" align="center"><strong> No record(s) to display. <strong></td></tr>';
		$total = number_format($total,2);
		$html = '<div class="pageset"><table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">							
			<tr>
	       		<td height="20" align="center" width="7%"><strong>Transaction Date</strong></td>
	            	<td height="20" align="center"><strong>Movement Type</strong></td>
	              	<td height="20" align="center" width="7%"><strong>Reference No.</strong></td>
	              	<td height="20" align="center" width="7%"><strong>Item Code</strong></td>
	              	<td height="20" align="center" width="20%"><strong>Item Description</strong></td>
	              	<td height="20" align="center" width="7%"><strong>Issuing BR</strong></td>                      	
	              	<td height="20" align="center" width="7%"><strong>Location</strong></td>
	              	<td height="20" align="center" width="7%"><strong>Receiving BR</strong></td>
	              	<td height="20" align="center" width="7%"><strong>Location</strong></td>
	              	<td height="20" align="center" width="7%"><strong>CSP </strong></td> 
	              	<td height="20" align="center" width="7%"><strong>Qty In</strong></td>   
	              	<td height="20" align="center" width="7%"><strong>Qty Out</strong></td>   
	              	<td height="20" align="center" width="7%"><strong>Total</strong></td>  
	         	</tr>'.$txnRegister.
			'<tr>
			</table>
			
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
		            <tr>
		    		<td height="20" align="left" width="15%"><strong>&nbsp;Prepared By :   </strong></td>
		        	<td height="20" align="left" width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;___________________________</td>       
		        	<td height="20" align="left" width="60%">&nbsp;</td>
                    </tr>
                    <tr>
		    		<td height="20" width="15%" >&nbsp;</td>
		        	<td height="20" align="left" width="25%" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$preparedby.' </td>       
		        	<td height="20" align="left" width="60% >&nbsp;</td>
					
                            </tr>
                            </table>
                            <br><br><br><table width="100%" border="0" align="left" cellpadding="0" cellspacing="0">
                            <tr>
                                <td height="20" align="left" width="15%"><strong>&nbsp;Approved By :   </strong></td>
		                <td height="20" align="left" width="25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_____________________________ </td>       
		                <td height="20" align="left" width="60%"> </td>
                            </tr>
                            <tr>
		            	<td height="20">&nbsp;</td>
		                <td height="20" align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; '.$approvedby.' </td>       
		                <td height="20" align="left">&nbsp;</td>          	             		
                            </tr>
                            </table></div>';
	       		
                echo $html;
	}*/
	
?>
<script>window.print();</script>