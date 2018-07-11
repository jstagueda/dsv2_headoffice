<style>
    .pageset table{font-family: arial; font-size:12px; width: 100%; border-collapse: collapse;}
    .pageset{margin-bottom: 20px;}
    .tablelisttr td{padding:5px; font-weight:bold; text-align: center;}
    .listtr td{padding: 5px;}
    h2{font-size:16px; font-family:arial; text-align: center;}
    @page{margin: 0.5in 0; size: landscape;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>

<?php

include "../../initialize.php";
include IN_PATH.DS."scIBMServiceFeeReport.php";

$ibmfrom = $_GET['ibmfromHidden'];
$ibmto = $_GET['ibmtoHidden'];
$campaign = $_GET['campaign'];
$branch = $_GET['branch'];
$page = $_GET['page'];
$total = 10;

echo "<div style='margin-bottom:5px;'>
		<table width='100%' cellspacing='0' cellpadding='0' style='font-family:arial;'>
			<tr>
				<th colspan='6' style='font-size:14px;'>IBM Service Fee Report</th>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td width='100px;'><b>Branch</b></td>
				<td>:</td>
				<td>".getBranchDetails($branch)."</td>
				<td width='100px;'><b>Running Date</b></td>
				<td>:</td>
				<td>".date("m/d/Y h:i")."</td>
			</tr>
			<tr>
				<td><b>Campaign</b></td>
				<td>:</td>
				<td>".$_GET['campaign']."</td>
				<td><b>Create By</b></td>
				<td>:</td>
				<td>".$_SESSION['user_session_name']."</td>
			</tr>
		</table>
	</div>";

$ibmservicefeeCount = IBMServiceFee($ibmfrom, $ibmto, $campaign, true, $page, $total, $branch);

$header = '<div class="pageset"><table class="tablelisttable" border="1">
            <tr class="tablelisttr">
                <td>IBM/IGS Code</td>
                <td>Name</td>
                <td>Credit Term</td>
                <td>PMG</td>
                <td>Invoice Amount</td>
				<td>DGS Amount</td>
                <td>Payments</td>
                <td>Paid DGS</td>
                <td>Service Fee%</td>
                <td>Service Fee Amount</td>
            </tr>';
$footer = "</table></div>";
$row = 20;
$counter = 1;

if($ibmservicefeeCount->num_rows){
    while($res = $ibmservicefeeCount->fetch_object()){
        
        if($counter == 1){
            echo $header;
        }
			
        
        echo "<tr class='listtr'>
                    <td>".trim($res->IBMCode)."</td>
                    <td>".$res->IBMName."</td>
                    <td align='center'>".$res->CreditTerm."</td>
                    <td align='center'>".$res->PMGCode."</td>
                    <td align='right'>".number_format($res->NetInvoice, 2)."</td>
					<td align='right'>".number_format($res->DiscountedGrossAmount, 2)."</td>
                    <td align='right'>".number_format($res->Payment, 2)."</td>
                    <td align='right'>".number_format($res->PaidDGS, 2)."</td>
					<td align='right'>".round($res->ServiceFeePercentage, 2)."%</td>
					<td align='right'>".number_format($res->ServiceFeeAmount, 2)."</td>
                </tr>";
        
        if($counter == $row){
            echo $footer;
            $counter = 0;
        }else{
            if($counter == $ibmservicefeeCount->num_rows){
                echo $footer;
            }
        }
        
        $counter++;
    }
}else{
    echo $header;
    echo '<tr class="listtr">
                <td align="center" colspan="10">No result found.</td>
            </tr>';
    echo $footer;
}

?>
