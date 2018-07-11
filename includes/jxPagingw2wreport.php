<?php
require_once("../initialize.php");
include IN_PATH.DS."pagination.php";
include IN_PATH.DS."scw2w.php";

if(isset($_POST['action']))
{
	
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

	if($_POST['action'] == "GetTransactionList")
	{
		
		$page          = (isset($_POST['page']))?$_POST['page']:1;

		$txtEndDates   = (isset($_POST['txtEndDates']))?$_POST['txtEndDates']:date("m/d/Y");
		$txtEndDates   = date("Y-m-d", strtotime($txtEndDates));

		$txtStartDates = (isset($_POST['txtStartDates']))?$_POST['txtStartDates']:date("m/d/Y");
		$txtStartDates = date("Y-m-d", strtotime($txtStartDates));

		$txtSearch     = (isset($_POST['txtSearch']))?$_POST['txtSearch']:"";

		$branch        = $_POST['branch'];

		$totalrow      = 30;
		
		$getw2w      = getw2w($page, $totalrow, false, $txtStartDates, $txtSearch, $branch);
        $getw2wTotal = getw2w($page, $totalrow, true, $txtStartDates, $txtSearch, $branch);
		
		echo "<table width='100%' cellpadding='0' border=0 cellspacing='0' class=\"bordergreen\">
				<tr class=\"trheader\">
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
				</tr>
		     ";	
        $page2 = $page - 1;
		$cnt   = $totalrow * $page2;
		if($getw2w->num_rows)
		{
			while($row = $getw2w->fetch_object())
			{	 
				$cnt ++;		
				$diff = $row->CHfreezeQty - $row->HOFreezeQty;
			
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
			
			$diff = 0;
			if($getw2wTotal->num_rows)
			{
				while($row2 = $getw2wTotal->fetch_object())
				{
					$diff = $row2->CHfreezeQty - $row2->HOFreezeQty;
				
					$totalCHfreezeQty  = $totalCHfreezeQty + $row2->CHfreezeQty;
					$totalHOFreezeQty  = $totalHOFreezeQty + $row2->HOFreezeQty;
					$totalCHCreatedQty = $totalCHCreatedQty + $row2->CHCreatedQty;
					$totaldiff         = $totaldiff + 	$diff;
					$totalADJ          = $totalADJ + $row2->AdjustmentQty;
					
					if($row2->wcode == 'WH')
					{
						$totalCHfreezeQtyWH  = $totalCHfreezeQtyWH + $row2->CHfreezeQty;
						$totalHOFreezeQtyWH  = $totalHOFreezeQtyWH + $row2->HOFreezeQty;
						$totalCHCreatedQtyWH = $totalCHCreatedQtyWH + $row2->CHCreatedQty;
						$totaldiffWH         = $totaldiffWH + 	$diff;
						$totalADJWH          = $totalADJWH + $row2->AdjustmentQty;
					}
					else
					{
						$totalCHfreezeQtyDG  = $totalCHfreezeQtyDG + $row2->CHfreezeQty;
						$totalHOFreezeQtyDG  = $totalHOFreezeQtyDG + $row2->HOFreezeQty;
						$totalCHCreatedQtyDG = $totalCHCreatedQtyDG + $row2->CHCreatedQty;
						$totaldiffDG         = $totaldiffDG + 	$diff;
						$totalADJDG          = $totalADJDG + $row2->AdjustmentQty;
					}
				}
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
			
			
		
		}
		else
		{
			echo '<tr class="trlist">
					<td colspan="13" align="center" style="color:red;">No result found.</td>
				 </tr>';
		}

		echo "</table>";
		echo "<div style='margin-top:10px;'>".AddPagination($totalrow, $getw2wTotal->num_rows, $page)."</div>";
		
		
	}
	
}
?>
