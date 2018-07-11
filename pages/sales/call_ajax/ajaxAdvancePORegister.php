<?php
include "../../../initialize.php";
include IN_PATH.DS."scAdvancePORegister.php";
include IN_PATH.DS."pagination.php";

if(isset($_POST['searched'])){
    $customerrange = customerrange($database, $_POST['searched'], $_POST['branchId']);
    if($customerrange->num_rows){
        while($res = $customerrange->fetch_object()){
            $result[] = array("ID" => $res->ID, "Value" => TRIM($res->Code)." - ".$res->Name, "Label" => TRIM($res->Code)." - ".$res->Name);
        }
    }else{
        $result[] = array("ID" => 0, "Value" => "", "Label" => "No result found.");
    }
    tpi_JSONencode($result);
}

if(isset($_POST['page'])){
    
    $datefrom = $_POST['datefrom'];
    $dateto = $_POST['dateto'];
    $customerfrom = $_POST['customerfromHidden'];
    $customerto = $_POST['customertoHidden'];
    $page = $_POST['page'];
    $branchID = $_POST['branch'];
    $total = 10;
    
    $advancepo = advancepo($database, $datefrom, $dateto, $customerfrom, $customerto, false, $page, $total, $branchID);
    $advancepocount = advancepo($database, $datefrom, $dateto, $customerfrom, $customerto, true, $page, $total, $branchID);
    
    $header = "<table style='border:1px solid #FF00A6; border-top:none;' class='tablelisttable'border='0' cellspacing='0' cellpadding='0'>
                <tr class='tablelisttr'>
                    <td>Branch</td>
                    <td>Campaign</td>
                    <td>Dealer Code</td>
                    <td>Dealer Name</td>
                    <td>Advance PO No.</td>
                    <td>PO Date</td>
                    <td>CSP</td>
					<td>Basic Discount</td>
					<td>DGS</td>
                    <td>DGS Less CPI</td>
                    <td>IBM ID</td>
                    <td>IBM Name</td>
                    <td>Status</td>
                    <td>Encoder</td>
                </tr>";
    $footer = "</table>";
    
    echo $header;
    $totalCSP = 0;
	$totalDGS = 0;
	$totalBasicDiscount = 0;
    $totalDGSLessCPI = 0;
    $counter = 1;
    
    if($advancepocount->num_rows){
        while($res = $advancepo->fetch_object()){
            
            echo '<tr class="listtr">
                    <td>'.$res->BranchName.'</td>
                    <td align=center>'.$res->Campaign.'</td>
                    <td>'.$res->IGSCode.'</td>
                    <td>'.$res->IGSName.'</td>
                    <td>'.$res->AdvancePONo.'</td>
                    <td>'.$res->PODate.'</td>
                    <td align="right">'.number_format($res->CSP, 2).'</td>
					<td align="right">'.number_format($res->BasicDiscount, 2).'</td>
					<td align="right">'.number_format($res->DGS, 2).'</td>
                    <td align="right">'.number_format($res->DGSLessCPI, 2).'</td>
                    <td>'.$res->IBMCode.'</td>
                    <td>'.$res->IBMName.'</td>
                    <td align=center>'.$res->StatusName.'</td>
                    <td>'.$res->Encoder.'</td>
                </tr>';
            
            $totalCSP += $res->CSP;
            $totalDGSLessCPI += $res->DGSLessCPI;
			$totalDGS += $res->BasicDiscount;
			$totalBasicDiscount += $res->DGS;
            
            if(countdate($res->PODate) == $counter){
                echo "<tr class='tablelisttr'>
                    <td colspan=6 style='text-align:right;'>TOTAL</td>
					<td style='text-align:right;'>".number_format($totalCSP, 2)."</td>
					<td style='text-align:right;'>".number_format($totalDGS, 2)."</td>
                    <td style='text-align:right;'>".number_format($totalBasicDiscount, 2)."</td>
                    <td style='text-align:right;'>".number_format($totalDGSLessCPI, 2)."</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>";
                
                $totalCSP = 0;
				$totalDGS = 0;
				$totalBasicDiscount = 0;
                $totalDGSLessCPI = 0;
                $counter = 0;
            }
            
            $counter++;
        }
    }else{
        echo '<tr class="listtr">
                <td align="center" colspan="14">No result found.</td>
            </tr>';
    }
    echo $footer;
    
    echo "<div style='margin-top:10px;'>".AddPagination($total, $advancepocount->num_rows, $page)."</div>";
    die();
}

?>
