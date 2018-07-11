<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    include('initialize.php');
    
    $HODBTblToReadInBranch = array('customer','customerdetails','customeraccountsreceivable','customerpenalty','tpi_customerdetails',
            'tpi_rcustomeribm','tpi_rcustomerstatus','tpi_rcustomerbranch','tpi_rcustomerpda','tpi_customerstats',
            'inventory','inventoryadjustment','inventorycount','inventoryinout','inventorytransfer','inventoryadjustmentdetails','inventorycountdetails',
            'inventoryinoutdetails','inventorytransferdetails',
            'salesorder','salesinvoice','salesorderdetails','salesinvoicedetails',
            'deliveryreceipt','deliveryreceiptdetails',
            'dmcm','dmcmdetails',
            'tpi_credit','tpi_creditlimitdetails',
            'officialreceipt','officialreceiptcash','officialreceiptcheck','officialreceiptcommission','officialreceiptdeposit','officialreceiptdetails',
            'tpi_dealerwriteoff','tpi_dealerpromotion','tpi_dealertransfer','tpi_branchcollectionrating','customercommission','cumulativesales','birseries');
    
    echo '<pre>';
        /*foreach($HODBTblToReadInBranch as $tbl):
            echo "DROP TABLE IF EXISTS `$tbl`;<br />";
            echo showTableStructure($tbl);
            echo '<br /><br />';
        endforeach;*/
        $DBTables = getAllDatabaseTables();
        foreach($DBTables as $tbl):
            if($structure = showTableStructure($tbl)):
                echo "DROP TABLE IF EXISTS `$tbl`;<br />";
                echo $structure.';';
                echo '<br /><br />';
            endif;
        endforeach;
    echo '</pre>';
    
    /* DATABASE FUNCTIONS */
    function showTableStructure($tableName = ''){
        global $mysqli;
        $structure = '';
        
        $q = $mysqli->query("SHOW CREATE TABLE $tableName");
        if($q->num_rows > 0):
            $obj = $q->fetch_object();
            $obj = get_object_vars($obj);
            
            if(isset($obj['Table'])):
                $structure = $obj['Create Table'];
            endif;
            
            return $structure;
        endif;
        
        return $structure;
    }
    
    function getAllDatabaseTables(){
        global $mysqli;
        $tables = array();
        
        $q = $mysqli->query("SHOW TABLES");
        if($q->num_rows > 0):
            while($row = $q->fetch_object()):
                $tables[] = $row->Tables_in_ems_tpi_test_ho;
            endwhile;
        endif;
        
        return $tables;
    }
?>
