<style>
	body{font-family:arial;}
    table{width: 100%; font-family: arial; font-size: 12px; border-collapse: collapse;}
    h2{font-family: arial; font-size: 16px; text-align: center;}
    .trheader td{padding: 5px; text-align: center; font-weight: bold;}
    .trlist td{padding: 5px;}
    .pageset{margin-bottom: 20px;}
    @page{margin: 0.5in 0; size:landscape;}
    @media print{
        .pageset{margin:0; page-break-after: always;}
    }
</style>

<?php

include "../../initialize.php";
include IN_PATH.DS."scOfficialReceiptRegister.php";
include IN_PATH.DS."reportheader.php";

if(isset($_GET['page'])){
    $datefrom = $_GET['datefrom'];
    $dateto = $_GET['dateto'];
    $page = $_GET['page'];
    $total = 10;
    $sfmlevel = $_GET['sfmlevel'];
    $sfaccountFrom = $_GET['sfaccountfromHidden'];
    $sfaccountTo = $_GET['sfaccounttoHidden'];
	
    $officialrecieptregistercount = officialrecieptregister($database, $datefrom, $dateto, true, $page, $total, $sfmlevel, $sfaccountFrom, $sfaccountTo,$_GET['branch']);
	$officialreceipttotal = officialreceipttotal($datefrom, $dateto, $sfmlevel, $sfaccountFrom, $sfaccountTo, $_GET['branch'])->fetch_object();
	
    $header = "<div class='pageset'><table border='1'>";
    $trheader = "<tr class='trheader'>
                    <td>Collection Receipt Date</td>
                    <td>Collection Receipt No.</td>
                    <td>Transaction No.</td>
                    <td>Customer Code</td>
                    <td>Customer Name</td>
                    <td>Cash</td>
                    <td>Check</td>
                    <td>Deposit Slip</td>
                    <td>Cancelled Amount</td>
					<td>Penalty Amount</td>
                    <td>Net Total Amount</td>
                    <td>Offsetting</td>
                    <td>Total Applied Amount</td>
                    <td>Total Unapplied Amount</td>
                </tr>";
    $footer = "</table></div>";

    $row = 10;
    $counter = 1;
	$count = 1;
	
	$branchquery = $database->execute("SELECT * FROM branch WHERE ID = ".$_GET['branch']."");
	$branch = $branchquery->fetch_object();
	
	echo reportheader('Collection Receipt Regsiter', $_SESSION['user_session_name'], $branch->Code.' - '.$branch->Name, $branch->StreetAdd, true, $datefrom, $dateto);
    
    if($officialrecieptregistercount->num_rows){

        while($res = $officialrecieptregistercount->fetch_object()){

            if($counter == 1){
                echo $header;
                echo $trheader;
            }

            echo "<tr class='trlist'>
                    <td>".$res->ORDate."</td>                    
                    <td>".$res->DocumentNo."</td>
					<td>".str_replace('OR','',$res->ORno)."</td>
                    <td>".$res->CustomerCode."</td>
                    <td>".$res->CustomerName."</td>
                    <td align=right>".number_format($res->cash, 2)."</td>
                    <td align=right>".number_format($res->check, 2)."</td>
                    <td align=right>".number_format($res->DepositSlip, 2)."</td>
                    <td align='center'>".number_format($res->Cancelled, 2)."</td>
					<td align='center'>".number_format($res->PenaltyAmount, 2)."</td>
                    <td align=right>".number_format($res->NetAmount, 2)."</td>
                    <td align=right>".number_format($res->offset, 2)."</td>
                    <td align=right>".number_format($res->TotalAppliedAmount, 2)."</td>
                    <td align=right>".number_format($res->TotalUaAppliedAmount, 2)."</td>
                </tr>";
				
				if($res->StatusID == 7){
					$Cash 					+= $res->cash;
					$Check 					+= $res->check;
					$Deposit 				+= $res->DepositSlip;
					$CancelledAmount		+= $res->Cancelled;
					$PenaltyAmount 			+= $res->PenaltyAmount;
					$NetTotalAmount 		+= $res->NetAmount;
					$Offsetting 			+= $res->offset;
					$TotalAppliedAmount 	+= $res->TotalAppliedAmount;
					$TotalUnappliedAmt 		+= $res->TotalUaAppliedAmount;
				}

            if($count == $officialrecieptregistercount->num_rows){
				echo "<tr class='trlist' style='font-weight:bold;'>					
					<td colspan='5' align='right'>Total :</td>
					<td align=right>".number_format($Cash, 2)."</td>
					<td align=right>".number_format($Check, 2)."</td>
					<td align=right>".number_format($Deposit, 2)."</td>
					<td align=right>".number_format($CancelledAmount, 2)."</td>
					<td align=right>".number_format($PenaltyAmount, 2)."</td>
					<td align=right>".number_format($NetTotalAmount, 2)."</td>
					<td align=right>".number_format($Offsetting, 2)."</td>
					<td align=right>".number_format($TotalAppliedAmount, 2)."</td>
					<td align=right>".number_format($TotalUnappliedAmt, 2)."</td>
				</tr>";
				$Cash 				=0;
				$Check 				=0;
				$Deposit 			=0;
				$CancelledAmount	=0;
				$PenaltyAmount 		=0;
				$NetTotalAmount 	=0;
				$Offsetting 		=0;
				$TotalAppliedAmount =0;
				$TotalUnappliedAmt 	=0;
				
				echo $footer;
            }else{
				if($counter == $row){
					echo $footer;
					$counter = 0;
				}
			}
			
			$count++;
            $counter++;
        }

    }else{

        echo $header;
        echo $trheader;
        echo "<tr class='trlist'>
                <td colspan='13' align='center'><span class='txtredsbold'>No record(s) to display.</span></td>
            </tr>";
        echo $footer;

    }
}

?>

<script>
window.print();
</script>
