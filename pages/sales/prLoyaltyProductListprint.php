<style>
    .pageset table{font-size: 12px; font-family: arial; border-collapse: collapse; width:100%;}
    h2{font-size: 16px; font-family: arial; text-align:center;}
    .pageset table td{padding:5px;}
    .pageset{margin-bottom: 20px;}
    .pageset .tablelisttr td{font-weight:bold; text-align:center;}
	.pageset .tablelisttr2 td{font-weight:bold; text-align:left;}
    @page{margin:0.5in 0; size: landscape;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>

<?php

include "../../initialize.php";
include IN_PATH.DS."scLoyaltyProductList.php";


$datefrom = $_GET['datefrom'];
$dateto = $_GET['dateto'];
$page = $_GET['page'];
$loyaltyID = $_GET['Loyalty'];
$total = 30;

$loyaltyprdlist = loyaltyprdlist($database, $datefrom, $dateto,$loyaltyID,true, $page, $total);

$header = "<div class='pageset'><table class='tableheader' border=1>";
$trheader = "
             <tr class='tablelisttr2'>
			    <td  colspan = '4'>Loyalty Program : ".$_GET['LoyaltyList']."</td>
		     </tr>
             <tr class='tablelisttr'>
                <td>Product Code</td>
                <td>Product Description</td>
                <td>Start Date</td>
                <td>End Date</td>
            </tr>";
$footer = "</table></div>";
$row = 30;
$counter = 1;
$count = 1;

echo "<h2>Loyalty Program Product List Report</h2>";

if($loyaltyprdlist->num_rows){
    while($res = $loyaltyprdlist->fetch_object())
	{
        
        if($counter == 1){
            echo $header;
            echo $trheader;
        }
        
        echo '<tr>
                <td>'.$res->code.'</td>
                <td>'.$res->desc.'</td>
                <td>'.$res->Start_date.'</td>
                <td>'.$res->End_date.'</td>
            </tr>';
        
        if($counter == $row){
            echo $footer;
            $counter = 0;
        }else{
            if($loyaltyprdlist->num_rows == $counter){
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
