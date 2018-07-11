<?php
    include "../../../initialize.php";
    include IN_PATH.DS."pagination.php";
    global $database;
	
    $datefrom = (isset($_POST['txtStartDate']))?$_POST['txtStartDate']:date("m/d/Y");
    $datefrom = date("Y-m-d", strtotime($datefrom));
    
    $dateto = (isset($_POST['txtEndDate']))?$_POST['txtEndDate']:date("m/d/Y");
    $dateto = date("Y-m-d", strtotime($dateto));
    
    $page = (isset($_POST['page']))?$_POST['page']:1;
    $total = 10;
    $branch = $_POST['Branch'];
    $birbackend = birbackend($database, $datefrom, $dateto, false, $page, $total,$branch);
    $countbirbackend = birbackend($database, $datefrom, $dateto, true, $page, $total,$branch);
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
	

	
if (!ini_get('display_errors')){
    ini_set('display_errors', 1);
} 
	
$offset = 0;
$RPP = 0;

$date = time();
$today = date("m/d/Y",$date);
$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
$tmpStartDate = strtotime(date("Y-m-d", strtotime($today)) . " -1 month");
$end = date("m/d/Y",$tmpdate);
$start = date("m/d/Y",$tmpdate);

$bname = "";
$btin = "";
$bsn = "";
	
$queryBranch = $sp->spSelectBranchbyBranchParameter($database);
if ($queryBranch->num_rows){
    while ($rowB =  $queryBranch->fetch_object()){
        $bname = $rowB->Name;
        $btin = $rowB->TIN;
        $bsn = $rowB->ServerSN;			    
    }
}		
        
function birbackend($database, $datefrom, $dateto, $istotal, $page, $total,$branch){
    
    $start = ($page > 1)?($page - 1) * $total:0;
    $limit = (!$istotal)?"LIMIT $start, $total":"";
    // vatsales
	
	
	$query= $database->execute("select `Date` xDate,`BeginningInvoice`, `EndingInvoice`,`BeginningBalance`,`EndingBalance`,`TotalSales`,`VATSales`,
								`VATAmount`,`Non-VATSales` NonVATSales,`ZeroRated`,`DiscountPrevPurchase`,`Returns`,`Void`,`OverunOverflow` 
								from birbackend where date(`Date`) between '".$datefrom."' AND '".$dateto."' AND SPLIT_STR(HOGeneralID,'-',2)=".$branch." ".$limit);
    return $query;
}
?>
