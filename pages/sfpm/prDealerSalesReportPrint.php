<style>
    .pageset{margin-bottom: 20px;}
    h2{font-size: 16px; font-family: arial; text-align: center;}
    .pageset table{font-size:12px; font-family: arial; width: 100%; border-collapse: collapse;}
    .tablelisttr td{padding:5px; text-align: center; font-weight: bold;}
    .listtr td{padding:5px;}
    @page{size:portrait; margin: 0.5in 0;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>

<?php
include "../../initialize.php";
include IN_PATH.DS."scDealerSalesReport.php";

$dealerfrom = (isset($_GET['dealerfromHidden']))?$_GET['dealerfromHidden']:0;
$dealerto = (isset($_GET['dealertoHidden']))?$_GET['dealertoHidden']:0;
$datefrom = (isset($_GET['datefrom']))?$_GET['datefrom']:0;
$dateto = (isset($_GET['dateto']))?$_GET['dateto']:0;
$po = (isset($_GET['accumulativePO']))?(($_GET['accumulativePO'] == "")?0:$_GET['accumulativePO']):0;
$amount = (isset($_GET['amount']))?(($_GET['amount'] == "")?0:$_POST['amount']):0;
$page = (isset($_GET['page']))?$_GET['page']:1;
$branch = $_GET['branch'];
$total = 10;

$countdealersales = dealersales($database, $dealerfrom, $dealerto, $datefrom, $dateto, $po, $amount, true, $page, $total, $branch);

$header = "<div class='pageset'><table border=1>";
$trheader = "<tr class=\"tablelisttr\">
                <td>Dealer Code</td>
                <td>Dealer Name</td>
                <td>CSP</td>
                <td>CSP Less CPI</td>
                <td>DGS</td>
                <td>DGS Less CPI</td>
                <td>Invoice Amount</td>
                <td>Invoice Amount Less CPI</td>
            </tr>";
$footer = "</table></div>";
$counter = 1;
$row = 20;

echo "<h2>Dealer Sales Report</h2>";

if($countdealersales->num_rows){
    
    while($res = $countdealersales->fetch_object()){
        
        if($counter == 1){
            echo $header;
            echo $trheader;
        }
        
        echo '<tr class="listtr">
                <td>'.$res->Code.'</td>
                <td>'.$res->Name.'</td>
                <td align="right">'.number_format($res->CSP, 2).'</td>
                <td align="right">'.number_format($res->CSPLessCPI, 2).'</td>
                <td align="right">'.number_format($res->DGS, 2).'</td>
                <td align="right">'.number_format($res->DGSLessCPI, 2).'</td>
                <td align="right">'.number_format($res->Invoice, 2).'</td>
                <td align="right">'.number_format($res->InvoiceLessCPI, 2).'</td>
            </tr>';
        
        if($counter == $row){
            echo $footer;
            $counter = 0;
        }else{
            if($countdealersales->num_rows == $counter){
                echo $footer;
            }
        }
        
        $counter++;
    }
    
}else{
    echo $header;
    echo $trheader;
    echo '<tr class="listtr">
            <td align="center" colspan="8">No result found.</td>
        </tr>';
    echo $footer;
}
?>