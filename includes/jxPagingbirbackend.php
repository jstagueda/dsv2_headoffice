<?php
    require_once("../initialize.php");
    include IN_PATH.DS."pagination.php";
    include IN_PATH.DS."scBirBackEnd.php";
    

if(isset($_POST['action'])){
	
	if($_POST['action'] == 'pagination'){
	
		$datefrom = (isset($_POST['txtStartDate']))?$_POST['txtStartDate']:date("m/d/Y");
		$datefrom = date("Y-m-d", strtotime($datefrom));
		
		$dateto = (isset($_POST['txtEndDate']))?$_POST['txtEndDate']:date("m/d/Y");
		$dateto = date("Y-m-d", strtotime($dateto));
		
		$page = (isset($_POST['page']))?$_POST['page']:1;
		$total = 10;
		
		$branch = $_POST['branch'];
		
		$birbackend = birbackend($database, $datefrom, $dateto, false, $page, $total, $branch);
		$countbirbackend = birbackend($database, $datefrom, $dateto, true, $page, $total, $branch);
		$xcount = 0;
		
		echo "<table width='100%' cellpadding='0' border=0 cellspacing='0' class='bordergreen'>
				<tr class='trheader'>
					<td>Date</td>
					<td>Beginning Invoice</td>
					<td>Ending Invoice</td>                
					<td>Beginning Bal</td>
					<td>Ending Bal</td>
					<td>Total Sales</td>
					<td>VAT Sales</td>
					<td>VAT Amount</td>
					<td>Non-VAT Sales</td>
					<td>Zero Rated</td>
					<td>Discount Prev Purchase</td>
					<td>Returns</td>
					<td>Voids</td>
					<td>Overrun/Overflow</td>
				</tr>";
		
		if($birbackend->num_rows){
			while($row = $birbackend->fetch_object()){
				$cnt ++;
				
				
				echo "<tr class='trlist'>
			        <td align='center'>".$row->xDate."</td>
			        <td align='center'>".$row->BeginningInvoice."</td>
			        <td align='center'>".$row->EndingInvoice."</td>                    
			        <td align='right'>".$row->BeginningBalance."</td>
					<td align='right'>".$row->EndingBalance."</td>
					<td align='right'>".$row->TotalSales."</td>
			        <td align='right'>".$row->VATSales."</td>
			        <!-- td align='right'>$vatsales</td -->
			        <td align='right'>".number_format($row->VATAmount,2)."</td>
			        <td align='right'>".number_format(($row->NonVATSales2),2)."</td>
			        <td align='right'>".$row->ZeroRated."</td>
			        <td align='right'>".$row->DiscountPrevPurchase."</td>
			        <td align='right'>".$row->Returns."</td>
			        <td align='right'>".$row->Void."</td>
			        <td align='right'>".$row->OverunOverflow."</td>
			        <!-- td align='right'>$overrun</td -->   </tr>";
					
					
			}
		}else{
			echo '<tr class="trlist">
					<td colspan="14" align="center" style="color:red;">No result found.</td>
				</tr>';
		}
		
		echo "</table>";
		echo "<div style='margin-top:10px;'>".  AddPagination($total, $countbirbackend->num_rows, $page)."</div>";
		die();
	}
	
	
	if($_POST['action'] == "searchbranch"){
		
		$query = $database->execute("SELECT * FROM branch WHERE ID NOT IN(1,2,3) AND (Name LIKE '".$_POST['branchName']."%' OR Code LIKE '".$_POST['branchName']."%') LIMIT 10");
		if($query->num_rows){
			while($res = $query->fetch_object()){
				$result[] = array("Label" => trim($res->Code).' - '.$res->Name,
									"Value" => trim($res->Code).' - '.$res->Name,
									"ID" => $res->ID);
			}
		}else{
			$result[] = array("Label" => "No result found",
								"Value" => "",
								"ID" => 0);
		}
		
		die(json_encode($result));
		
	}
}
	
?>
