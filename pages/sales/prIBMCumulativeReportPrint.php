<style>
    h2{font-size:16px; font-family: arial; text-align: center;}
    .pageset table{font-size:12px; width:100%; border-collapse:collapse; font-family: arial;}
    .pageset{margin-bottom: 20px;}
    .pageset table td{padding: 5px;}
    .trheader td{text-align: center; font-weight: bold;}
    @page{margin:0.5in 0; size: landscape;}
    @media print{
        .pageset{page-break-after: always; margin: 0;}
    }
</style>
<?php
    include "../../initialize.php";
    include IN_PATH.DS."scIBMCumPerReport.php";
    
    
    $header = "<div class='pageset'><table border=1>";
    $footer = "</table></div>";
    
    $trheader = "<tr class='trheader'>
                    <td width='8%'>Customer Code</td>
                    <td>Customer Name</td>
                    <td width='8%'>Campaign</td>
                    <td width='8%'>Total DGS</td>
                    <td width='8%'>On-Time BCR</td>
                    <td width='8%'>Total Invoice Amount</td>
                    <td width='8%'>Collection Due Balance</td>
                    <td width='8%'>Total Written-Off Amount</td>
                    <td width='8%'>Paid Up Invoices</td>
                    <td width='8%'>Cumulative BCR On-Time-Or-Not</td>
                </tr>";
    
    $row = 18;
    $counter = 1;
    
    echo "<h2>IBM Cumulative Performance Report</h2><br />";
    
    if($countibmcumulative->num_rows){
        while($res = $countibmcumulative->fetch_object()){
            
            if($counter == 1){
                echo $header;
                echo $trheader;
            }
            echo "<tr>
                    <td>".$res->CustomerCode."</td>
                    <td>".$res->CustomerName."</td>
                    <td>".$res->CampaignCode."</td>
                    <td align='right'>".number_format($res->TotalDGSSales, 2)."</td>
                    <td align='right'>".number_format($res->OnTimeBCR, 2)."</td>
                    <td align='right'>".number_format($res-NetAmount, 2)."</td>
                    <td align='right'>".number_format($res->CollectionDueBalance, 2)."</td>
                    <td align='right'>0</td>
                    <td align='right'>".number_format($res->PaidInvoice, 2)."</td>
                    <td align='right'>".number_format($res->OnTimeOrNotBCR, 2)."</td>
                 </tr>";
            if($counter == $row){
                echo $footer;
                $counter = 0;
            }else{
                if($countibmcumulative->num_rows == $counter){
                    echo $footer;
                }
            }
            
            $counter++;
        }        
    }else{
        echo $header;
        echo $trheader;
        echo "<tr><td colspan='10' align='center'>No record found.</td></tr>";
        echo $footer;
    }
?>