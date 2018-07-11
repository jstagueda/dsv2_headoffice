<style>
    .pageset table{font-size: 12px; font-family: arial; border-collapse: collapse; width:100%;}
    h2{font-size: 16px; font-family: arial; text-align:center;}
    .pageset table td{padding:5px;}
    .pageset{margin-bottom: 20px;}
    .pageset .tablelisttr td{font-weight:bold; text-align:center;}
    @page{margin:0.5in 0; size: landscape;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>

<?php

include "../../initialize.php";
include IN_PATH.DS."scAdvancePORegister.php";


$datefrom = $_GET['datefrom'];
$dateto = $_GET['dateto'];
$customerfrom = $_GET['customerfromHidden'];
$customerto = $_GET['customertoHidden'];
$page = $_GET['page'];
$branchID = $_GET['branch'];
$total = 10;

$advancepo = advancepo($database, $datefrom, $dateto, $customerfrom, $customerto, true, $page, $total, $branchID);

$header = "<div class='pageset'><table class='tableheader' border=1>";
$trheader = "<tr class='tablelisttr'>
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
$footer = "</table></div>";
$row = 8;
$counter = 1;
$count = 1;

echo "<h2>Advance PO Register Report</h2>";

if($advancepo->num_rows){
    while($res = $advancepo->fetch_object()){
        
        if($counter == 1){
            echo $header;
            echo $trheader;
        }
        
        echo '<tr>
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
		$totalBasicDiscount += $res->BasicDiscount;
		$totalDGS += $res->DGS;
        $totalDGSLessCPI += $res->DGSLessCPI;

        if(countdate($res->PODate) == $count){
            echo "<tr class='tablelisttr'>
                <td colspan=6 style='text-align:right;'>TOTAL</td>
                <td style='text-align:right;'>".number_format($totalCSP, 2)."</td>
				<td style='text-align:right;'>".number_format($totalBasicDiscount, 2)."</td>
				<td style='text-align:right;'>".number_format($totalDGS, 2)."</td>
                <td style='text-align:right;'>".number_format($totalDGSLessCPI, 2)."</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>";

            $totalCSP = 0;
            $totalBasicDiscount = 0;
			$totalDGS = 0;
            $totalDGSLessCPI = 0;
            $count = 0;
        }
        
        if($counter == $row){
            echo $footer;
            $counter = 0;
        }else{
            if($advancepo->num_rows == $counter){
                echo $footer;
            }            
        }
        
        $counter++;
        $count++;
    }
}else{
    echo $header;
    echo $trheader;
    echo '<tr>
            <td align="center" colspan="14">No result found.</td>
         </tr>';
    echo $footer;
}
?>
