    <style>
	@media print {
	…
	}
	header nav, footer {
	display: none;
	}
    table {
        height: 900px
        width: 595px;
		    page-break-after: always; /* Always insert page break after this element */
			page-break-inside: avoid; /* Please don't break my page content up browser */
        font-style:normal;
		font-size:15px;
		font-family:"Arial";
		border-width: 1px;
	border-spacing: 1px;
	border-style: solid;
	border-color: gray;
	border-collapse: collapse;
	
    }
    </style>
	<script>
		function print_report(){
			window.print();
		}
	</script>
<?php

	require_once "initialize.php";
	
	// If it's going to need the database, then it's 
	// probably smart to require it before we start.
	require_once(CS_PATH.DS.'dbconnection.php');
	global $database;
	
	$dealerUpload = new DealerUpload();
	$maindealer = $dealerUpload->maindealerUpload($database);
	//$cancelSI->CancelSalesInvoice($database,241808,11401);
	
	if($maindealer == "successful"){
		$inventory  = $dealerUpload->spSelectInventoryTmp();
		$totalSOH = 0;
			if($inventory->num_rows){
				while ($row = $inventory->fetch_object()){
					$totalSOH += $row->SOH;
				}
			}else{
				$totalSOH = 0;
			}
		$OpenSi		= $dealerUpload->spSelectOpenSITmp();
		$TotalCSPOSI = 0;
			if($OpenSi->num_rows){
				while($row = $OpenSi->fetch_object()){
					$TotalCSPOSI += $row->csp;
				}
			}else{
				$TotalCSPOSI = 0;
			}
		$LastSI		= $dealerUpload->spSelectLastSITmp();
		$TotalCSPLSI = 0;		
			if($LastSI->num_rows){
				while($row = $LastSI->fetch_object()){
					$TotalCSPLSI += $row->csp;
				}
			}else{
				$TotalCSPLSI = 0;
			}
		$Customer	= $dealerUpload->spSelectCustomerTmp();
		$Product	= $dealerUpload->spSelectProductTmp();
		echo "
			<table width='70%'   align = 'center' border = '1'>
			<tr >
			<th colspan = '4'>
				Data Migration Logs
			</th>
			</tr>
			</tr>
			<tr>
				<th>File Type</th>
				<th>File Name</th>
				<th>Total Records&nbsp;Uploaded</th>
				<th>Total Amount</th>
			</tr>
			<tr align = 'center'>
				<td>Dealer</td>
				<td>".dlr."</td>
				<td align = 'center'>".number_format($Customer->num_rows)."</td>
				<td align = 'right'>N/A&nbsp;&nbsp;</td>
			</tr>
			<tr align = 'center'>
				<td>Inventory</td>
				<td>".iv."</td>
				<td align = 'center'>".number_format($inventory->num_rows)."</td>
				<td align = 'right'>".number_format($totalSOH,2)."&nbsp;&nbsp;</td>
			</tr>
			<tr align = 'center'>
				<td>Last SI</td>
				<td>".LastSI."</td>
				<td align = 'center'>".number_format($LastSI->num_rows)."&nbsp;&nbsp;</td>
				<td align = 'right'>".number_format($TotalCSPLSI,2)."&nbsp;&nbsp;</td>
			</tr>
			<tr align = 'center'>
				<td>Open SI</td>
				<td>".OpenSI."</td>
				<td align = 'center'>".number_format($OpenSi->num_rows)."</td>
				<td align = 'right'>".number_format($TotalCSPOSI,2)."&nbsp;&nbsp;</td>
			</tr>
			<tr align = 'center'>
				<td>Product File</td>
				<td>".prod_master."</td>
				<td align = 'center'>".$Product->num_rows."</td>
				<td align = 'right'>N/A&nbsp;&nbsp;</td>
			</tr>
			<tr>
				<td colspan = '5'><input type = 'submit' onclick = 'print_report();' value = 'Print'></td>
			</tr>
			</table>
			<br />
		";

	}else{
		echo "die";
	}
	
	
?>