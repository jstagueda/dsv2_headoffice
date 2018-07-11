<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: April 04, 2013
 * @description: Print process and preview for lists of sales force that should be terminated.
 */
    include('../../initialize.php');
    
    $IDsTerminationForPrinting = @implode(',', $_SESSION['IDsTerminationForPrinting']);
    
    $query = "SELECT c.`ID`,TRIM(c.`Code`) AS `Code`,c.`Name`,
                IFNULL(si.`NetAmount`,'0.00') AS `NetAmount`,
                IFNULL(DATE(si.`TxnDate`),'0000-00-00') AS `TxnDate`,
                DATE(cd.`LastPODate`) AS `LastPODate`
                FROM `customer` c
                INNER JOIN `tpi_customerdetails` cd ON cd.`CustomerID` = c.`ID`
                LEFT JOIN `salesinvoice` si ON si.`CustomerID` = c.`ID`
                WHERE c.`ID` IN($IDsTerminationForPrinting)";
    $lists = $mysqli->query($query);
?>
    <style>
        html{font-family: Verdana,Geneva,Arial,helvetica,sans-serif;font-size: 12px;}
        table{font-size: 12px;border: 1px solid #000;border-collapse: collapse;}
        table td{border: 1px solid #000;}
        table th{border: 1px solid #000;}
        h2{font-size: 16px;}
        .td-center{text-align: center;}
    </style>
    <script> window.onload = function(){ window.print(); } </script>
<?php
    if($lists->num_rows > 0):
?>
    <h2>Sales Force Movement For Termination</h2>
    <span>Date Printed: <?php echo date('F d, Y h:i A'); ?></span>
    <table width="100%" border="1" cellspacing="3" cellpadding="3">
        <tr>
            <th width="12%" class="td-bottom-border">Code</th>
            <th width="23%" class="td-bottom-border">Name</th>
            <th width="12%" class="td-bottom-border">Purchase Amount</th>
            <th width="12%" class="td-bottom-border">Last PO Date</th>
            <th width="12%" class="td-bottom-border">Movement Status</th>
        </tr>
<?php
        while($row = $lists->fetch_object()):
?>
        <tr class="tbl-td-rows">
            <td class="tbl-td-center td-bottom-border td-center"><?php echo $row->Code; ?></td>
            <td class="tbl-td-center td-bottom-border"><?php echo $row->Name; ?></td>
            <td class="tbl-td-center td-bottom-border td-center"><?php echo $row->NetAmount; ?></td>
            <td class="tbl-td-center td-bottom-border td-center"><?php echo $row->LastPODate; ?></td>
            <td class="tbl-td-center td-bottom-border td-center">TERMINATION</td>
        </tr>
    
<?php
        endwhile;
?>
    </table>
<?php
        unset($_SESSION['IDsTerminationForPrinting']);
    endif;
?>
