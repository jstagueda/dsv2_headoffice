<style>
    body{font-family: arial;}
    .pageset table{font-size:12px; border-collapse:collapse; width:100%;}
    .headertable{font-size:12px; width:100%; margin-bottom: 20px;}
    .firsttd{width:100px;}
    .separator{width:15px; text-align: center; font-weight:bold;}
    .trheader td{font-weight:bold; text-align: center;}
    .pageset td{padding:3px;}
    .pageset{margin-bottom:20px;}
    h2{font-size:16px; text-align: center;}
    @page{margin:0.5in 0; size:landscape;}
    @media print{
        .pageset{page-break-after: always;}
    }
</style>
<?php 
include "../../initialize.php";
include "invPagingIntransitReport.php";

$querybranch = $database->execute("SELECT * FROM branch WHERE ID = ".$_GET['branch']."");
$branch = $querybranch->fetch_object();
if($querybranch->num_rows == 0){
    $branchName = "All";
}else{
    $branchName = $branch->Code." - ".$branch->Name;
}

$querymovement = $database->execute("SELECT * FROM movementtype WHERE ID = ".$_GET['movementtype']."");
$movementtype = $querymovement->fetch_object();
if($querymovement->num_rows == 0){
    $movementtypeName = "All";
}else{
    $movementtypeName = $movementtype->Code." - ".$movementtype->Name;
}

$rows = 20;

$title = "<h2>Inventory Intransit Report</h2>";
$title .= "<table class='headertable'>
            <tr>
                <td class='firsttd'><b>Date From</b></td>
                <td class='separator'>:</td>
                <td width='50%'>".$_GET['datestart']."</td>
                <td class='firsttd'><b>Branch</b></td>
                <td class='separator'>:</td>
                <td>".$branchName."</td>
            </tr>
            <tr>
                <td class='firsttd'><b>Date To</b></td>
                <td class='separator'>:</td>
                <td>".$_GET['dateend']."</td>
                <td class='firsttd'><b>Movement Type</b></td>
                <td class='separator'>:</td>
                <td>".$movementtypeName."</td>
            </tr>
          </table>";
$header = "<div class='pageset'><table border=1>";
$headertr = "<tr class='trheader'>
                <td>Date</td>
                <td>Movement Type Code</td>
                <td>Reference No.</td>
                <td>DR No.</td>
                <td>Shipment Advice No.</td>
                <td colspan='2'>Item Code / Description</td>
                <td>Loaded Qty</td>
                <td>Loaded Date</td>
            </tr>";
$footer = "</table></div>";
$counter = 1;

echo $title;

if($countintransitreport->num_rows > 0){
    while($row = $countintransitreport->fetch_object()){
        if($counter == 1){
            echo $header;
            echo $headertr;
        }
?>
    <tr>
        <td width="8%"><?=$row->TransactionDate?></td>
        <td><?=$row->MovementCode?></td>
        <td width="10%"><?=$row->PicklistRefNo?></td>
        <td width="10%"><?=$row->DocumentNo?></td>
        <td width="10%"><?=$row->ShipmentAdviseNo?></td>
        <td width="7%" style="border-right:none;"><?=$row->Code?></td>
        <td style="border-left:none;"><?=$row->Name?></td>
        <td width="8%" align="right"><?=$row->LoadedQty?></td>
        <td width="8%"><?=$row->EnrollmentDate?></td>
    </tr>
<?php
        if($counter == $rows){
            echo $footer;
            $counter = 0;
        }else{
            if($countintransitreport->num_rows == $counter){
                echo $footer;
            }
        }
        $counter++;
    }
}else{
    echo $header;
    echo $headertr;
?>
    <tr>
        <td colspan="9" align="center">
            No result(s) found.
        </td>
    </tr>
<?php
    echo $footer;
}
?>