<style>
    .pageset{margin-bottom:20px;}
    .pageset table{font-size: 12px; width: 100%; font-family: arial; border-collapse: collapse;}
    .pageset table .tablelisttr td{padding: 5px; font-weight: bold; text-align:center;}
    .pageset table td{padding: 5px;}
    h2{font-size: 16px; text-align: center; font-family: arial;}
    @page{size: landscape; margin:0.5in 0;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>
<?php
include "../../initialize.php";
include IN_PATH.DS."scProductSubstitute.php";

$branch = $_GET['branch'];
$datefrom = $_GET['datefrom'];
$dateto = $_GET['dateto'];
$productlinefrom = $_GET['productlevelfrom'];
$productlineto = $_GET['productlevelto'];
$productfrom = $_GET['productfromHidden'];
$productto = $_GET['producttoHidden'];
$page = $_GET['page'];
$total = 10;

$countproductsubstitute = productsubstitute($database, $branch, $datefrom, $dateto, $productlinefrom, $productlineto,
                                            $productfrom, $productto, true, $page, $total);

$header = "<div class='pageset'><table border=1>";
$trheader = '<tr class="tablelisttr">
                <td rowspan="2">Branch</td>
                <td rowspan="2">Date</td>
                <td colspan="3">Original Items</td>
                <td colspan="3">Substitute Items</td>
            </tr>
            <tr class="tablelisttr">
                <td>Product Line Description</td>
                <td>Product Code</td>
                <td>Product Description</td>
                <td>Product Line Description</td>
                <td>Product Code</td>
                <td>Product Description</td>
            </tr>';
$footer = "</table></div>";
$counter = 1;
$row = 13;

echo "<h2>List of Product Substitute</h2>";

if($countproductsubstitute->num_rows){

    while($res = $countproductsubstitute->fetch_object()){
        if($counter == 1){
            echo $header;
            echo $trheader;
        }

        $branchquery = branchselect($database, $branch);
        $branchx = $branchquery->fetch_object();

        echo '<tr class="listtr">
                <td align="center">'.trim($branchx->Code).' - '.$branchx->Name.'</td>
                <td>'.$res->ProdDate.'</td>
                <td align="center">'.$res->OrigProdLine.'</td>
                <td>'.$res->OrigCode.'</td>
                <td>'.$res->OrigDesc.'</td>
                <td align="center">'.$res->SubProdLine.'</td>
                <td>'.$res->SubCode.'</td>
                <td>'.$res->SubDesc.'</td>
            </tr>';

        if($counter == $row){
            echo $footer;
            $counter = 0;
        }else{
            if($counter == $countproductsubstitute->num_rows){
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
