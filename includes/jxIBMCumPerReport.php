<?php
    require_once("../initialize.php");
    include IN_PATH.DS."scIBMCumPerReport.php";
    include IN_PATH.DS."pagination.php";
?>

<table width='100%' border='0' cellpadding='0' cellspacing='0' style="border:1px solid #FF00A6; border-top:none;">
    <tr class="trheader">
        <td class="bdiv_r">Customer Code</td>
        <td class="bdiv_r">Customer Name</td>
        <td class="bdiv_r">Campaign</td>
        <td class="bdiv_r">Total DGS</td>
        <td class="bdiv_r">On-Time BCR</td>
        <td class="bdiv_r">Total Invoice Amount</td>
        <td class="bdiv_r">Collection Due Balance</td>
        <td class="bdiv_r">Total Written-Off Amount</td>
        <td class="bdiv_r">Paid Up Invoices</td>
        <td class="bdiv_r">Cumulative BCR On-Time-Or-Not</td>
    </tr>
    <?php 
    if($ibmcumulative->num_rows){
        while($res = $ibmcumulative->fetch_object()){
    ?>
    <tr>
        <td class='borderBR'><?=$res->CustomerCode?></td>
        <td class='borderBR'><?=$res->CustomerName?></td>
        <td class='borderBR'><?=$res->CampaignCode?></td>
        <td class='borderBR' align="right"><?=number_format($res->TotalDGSSales, 2)?></td>
        <td class='borderBR' align="right"><?=number_format($res->OnTimeBCR, 2)?></td>
        <td class='borderBR' align="right"><?=number_format($res->NetAmount, 2)?></td>
        <td class='borderBR' align="right"><?=number_format($res->CollectionDueBalance, 2)?></td>
        <td class='borderBR' align="right">0</td>
        <td class='borderBR' align="right"><?=number_format($res->PaidInvoice, 2)?></td>
        <td class='borderBR' align="right"><?=number_format($res->OnTimeOrNotBCR, 2)?></td>        
    </tr>
    <?php }}else{?>
    <tr align='center'>
        <td class='borderBR' colspan='10' height='20' ><span class='txtredsbold'>No record(s) to display.</span></td>
    </tr>
    <?php }?>
</table>
<div style="margin-top:10px;">
    <?=AddPagination($total, $countibmcumulative->num_rows, $page);?>
</div>