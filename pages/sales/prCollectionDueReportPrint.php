<style>
    h2{font-family: arial; font-size:16px; text-align: center;}
    .pageset{margin-bottom: 20px;}
    .pageset table{border-collapse: collapse; font-family: arial; width: 100%; font-size:12px;}
    .pageset table tr.trheader td{padding: 5px; font-weight: bold; text-align: center;}
    .pageset table tr.tablelisttr td{padding: 5px;}
    @page{margin: 0.5in 0; size: landscape;}
    @media print{
        .pageset{page-break-after: always; margin:0;}
    }
</style>

<?php

include "../../initialize.php";
include IN_PATH.DS."scCollectionDueReport.php";

$datefrom = $_GET['datefrom'];
$dateto = $_GET['dateto'];
$sfmlevel = $_GET['sfmlevel'];
$sfafrom = $_GET['customerfromHidden'];
$sfato = $_GET['customertoHidden'];
$accounttype = $_GET['accounttype'];
$page = $_GET['page'];
$branch = $_GET['branchID'];
$total = 10;

$collectionduecount = collectiondue($database, $datefrom, $dateto, $sfmlevel, $sfafrom, $sfato, $accounttype, true, $page, $total, $branch);
$collectionduecountTotal = collectiondueTotal($database, $datefrom, $dateto, $sfmlevel, $sfafrom, $sfato, $accounttype, true, $page, $total, $branch);
$collectionduetotal = $collectionduecountTotal->fetch_object();

$header = '<div class="pageset">
            <table class="tablelisttable" border="1" cellspacing="0" cellpadding="0">
                <tr class="trheader">
                    <td width="10%">Account No.</td>
                    <td>Account Name</td>
                    <td width="10%">Credit Account Type</td>
                    <td width="10%">Credit Term</td>
                    <td width="10%">Invoice / Debit Memo</td>
                    <td width="10%">Ref. No. / Document No.</td>
                    <td width="10%">Total Amount Due</td>
					<td width="10%">Due Date</td>
                    <td width="10%">Contact No.</td>
                </tr>';
$footer = "</table></div>";
$row = 20;
$count = 1;

echo "<h2>Collection Due Report</h2>";

if($collectionduecount->num_rows){
    while($res = $collectionduecount->fetch_object()){
        if($count == 1){
            echo $header;
        }

        echo "<tr class='tablelisttr'>
                <td>".$res->AccountCode."</td>
                <td>".$res->AccountName."</td>
                <td align=\"center\">".$res->CreditAccountType."</td>
                <td align=\"center\">".$res->CreditTerm."</td>
                <td align=\"right\">".$res->Code."</td>
                <td>".$res->DocumentNo."</td>
                <td align=\"right\">".number_format($res->TotalAmount, 2)."</td>
				<td align=\"center\">".$res->DueDate."</td>
                <td align=\"center\">".$res->ContactNo."</td>
            </tr>";

        if($count == $row){
            echo $footer;
            $count = 0;
        }else{
            if($count == $collectionduecount->num_rows){
				echo '<tr class="tablelisttr">				
					<td colspan="6" align="right"><b>Total : </b></td>
					<td align="right"><b>'.number_format($collectionduetotal->TotalAmount, 2).'</b></td>
					<td colspan="2"></td>
				</tr>';
                echo $footer;
            }
        }

        $count++;
    }	
	
}else{
    echo $header;
    echo '<tr class="tablelisttr">
            <td colspan="8" align="center">No record found.</td>
        </tr>';
    echo $footer;
}

?>

<script>window.print();</script>