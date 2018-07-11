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
	include IN_PATH.DS."scw2w.php";
	
	global $database;
	ini_set('max_execution_time', 1000);
	
	$dteFrom  = $_GET['dteFrom'];	
	$dteFrom  = date("Y-m-d", strtotime($dteFrom));
	$search   = $_GET['search'];
	$branchID = $_GET['branch'];
	
	$branchquery = $database->execute("SELECT TRIM(Code) Branch FROM branch WHERE ID = $branchID");
	$b = $branchquery->fetch_object();
		
	echo "<table width='100%' cellpadding='0' cellspacing='0' style='font-size:12px; font-family:arial;'>
			<tr>
				<th align='center' colspan='6' style='padding-bottom:10px; font-size:14px;'>Inventory Count Report</th>
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
						<td align='center' width='3%'>Counter</td>
						<td align='center' width='5%'>Location</td>
						<td align='center' width='7%'>Product Code</td>
						<td align='center' width='7%'>3rd Item Number</td>
						<td align='center' width='14%'>Description</td>
						<td align='center' width='10%'>Regular Price</td>
						<td align='center' width='10%'>Channel Freezed Qty</td>                      	
						<td align='center' width='10%'>JDE Freezed Qty</td>
						<td align='center' width='10%'>Channel Counted Qty</td>
						<td align='center' width='10%'>Diff.(Channel - JDE)</td>
						<td align='center' width='10%'>Adjustment(JDE - Counted Qty)</td>
					</tr>";
	
	$footer = "</table></div>";
	
	$cnt     = 0;
	$rows    = 50000;
	$counter = 1;
	
	$getw2w      = getw2w($page, $totalrow, true, $txtStartDates, $search, $branchID);
	
	if($getw2w->num_rows)
	{
		while($row = $getw2w->fetch_object())
		{
			$cnt ++;
			
			
			if($counter == 1)
			{
				echo $header;
			}
			
			$counter ++;
			
			$diff = $row->CHfreezeQty - $row->HOFreezeQty;
			
			$totalCHfreezeQty  = $totalCHfreezeQty + $row->CHfreezeQty;
			$totalHOFreezeQty  = $totalHOFreezeQty + $row->HOFreezeQty;
			$totalCHCreatedQty = $totalCHCreatedQty + $row->CHCreatedQty;
    		$totaldiff         = $totaldiff + 	$diff;
			$totalADJ          = $totalADJ + $row->AdjustmentQty;
			
			if($row->wcode == 'WH')
			{
				$totalCHfreezeQtyWH  = $totalCHfreezeQtyWH + $row->CHfreezeQty;
				$totalHOFreezeQtyWH  = $totalHOFreezeQtyWH + $row->HOFreezeQty;
				$totalCHCreatedQtyWH = $totalCHCreatedQtyWH + $row->CHCreatedQty;
				$totaldiffWH         = $totaldiffWH + 	$diff;
				$totalADJWH          = $totalADJWH + $row->AdjustmentQty;
			}
			else
			{
				$totalCHfreezeQtyDG  = $totalCHfreezeQtyDG + $row->CHfreezeQty;
				$totalHOFreezeQtyDG  = $totalHOFreezeQtyDG + $row->HOFreezeQty;
				$totalCHCreatedQtyDG = $totalCHCreatedQtyDG + $row->CHCreatedQty;
				$totaldiffDG         = $totaldiffDG + 	$diff;
				$totalADJDG          = $totalADJDG + $row->AdjustmentQty;
			}
			
			echo "<tr class=\"trlist\">
						<td width='3%   align='center'>$cnt</td>
						<td width='5%'  align='center'>$row->wcode</td>
						<td width='7%'  align='center'>$row->pcode</td>
						<td width='7%'  align='center'>$row->thirdrditem</td>
						<td width='14%' align='center'>$row->Description</td>
						<td width='10%' align='right'>".number_format($row->RegularPrice,2) ."</td>
						<td width='10%' align='center'>$row->CHfreezeQty</td>
						<td width='10%' align='center'>$row->HOFreezeQty</td>
						<td width='10%' align='center'>$row->CHCreatedQty</td>
						<td width='10%' align='right'>".number_format($diff, 0)."</td>
						<td width='10%' align='center'>$row->AdjustmentQty</td>
					</tr>"; 

             					
		}
		
		
		echo "<tr class=\"trlist\">
				 <td colspan = 6 align='right'>WH TOTAL</td>
				 <td width='10%' align='center'>$totalCHfreezeQtyWH</td>
				 <td width='10%' align='center'>$totalHOFreezeQtyWH</td>
				 <td width='10%' align='center'>$totalCHCreatedQtyWH</td>
				 <td width='10%' align='right'>$totaldiffWH</td>
				 <td width='10%' align='center'>$totalADJWH</td>
			</tr>"; 	
			
		echo "<tr class=\"trlist\">
				 <td colspan = 6 align='right' >DG TOTAL</td>
				 <td width='10%' align='center'>$totalCHfreezeQtyDG</td>
				 <td width='10%' align='center'>$totalHOFreezeQtyDG</td>
				 <td width='10%' align='center'>$totalCHCreatedQtyDG</td>
				 <td width='10%' align='right'>$totaldiffDG</td>
				 <td width='10%' align='center'>$totalADJDG</td>
			</tr>"; 	
			
		echo "<tr class=\"trlist\">
				 <td colspan = 6 align='right' >GRAND TOTAL</td>
				 <td width='10%' align='center'>$totalCHfreezeQty</td>
				 <td width='10%' align='center'>$totalHOFreezeQty</td>
				 <td width='10%' align='center'>$totalCHCreatedQty</td>
				 <td width='10%' align='right'>$totaldiff</td>
				 <td width='10%' align='center'>$totalADJ</td>
			</tr>"; 	
			
		echo $footer;
	}else
	{
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
	
?>
<script>window.print();</script>