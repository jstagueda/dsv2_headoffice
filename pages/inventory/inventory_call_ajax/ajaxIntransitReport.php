<?php
include "../../../initialize.php";
include "../../../includes/pagination.php";
include "../../inventory/invPagingIntransitReport.php";

if(isset($_POST['action'])){
?>
<div style="border:1px solid #FF00A6; border-top:none;">
    <table class="tablelisttable" border="0" cellspacing="0" cellpadding="0">
        <tr class="tablelisttr">
            <td width="10%" style="border-left:none;">Date</td>
            <td width="10%">Movement Type Code</td>
            <td width="10%">Reference No.</td>
            <td width="10%">DR No.</td>
            <td width="10%">Shipment Advice No.</td>
            <td colspan="2">Item Code / Description</td>
            <td width="10%">Loaded Qty</td>
            <td width="10%">Loaded Date</td>
        </tr>
        <?php 
        if($intransitreport->num_rows > 0){
            while($row = $intransitreport->fetch_object()){
        ?>
        <tr class="listtr">
            <td style="border-left:none;"><?=$row->TransactionDate?></td>
            <td><?=$row->MovementCode?></td>
            <td><?=$row->PicklistRefNo?></td>
            <td><?=$row->DocumentNo?></td>
            <td><?=$row->ShipmentAdviseNo?></td>
            <td width="5%"><?=$row->Code?></td>
            <td style="border-left:none;"><?=$row->Name?></td>
            <td align="right"><?=$row->LoadedQty?></td>
            <td><?=$row->EnrollmentDate?></td>
        </tr>
        <?php }}else{?>
        <tr class="listtr">
            <td colspan="9" style="color:red; text-align: center; border-left:none;">
                No result(s) found.
            </td>
        </tr>
        <?php }?>
    </table>
</div>
<br />
<div>
    <?php echo AddPagination(10, $countintransitreport->num_rows, $page);?>
</div>
<?php
}
?>
