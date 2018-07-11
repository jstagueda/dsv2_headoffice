<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * @author: jdymosco
 * @date: January 16, 2013
 * @description: First cron job process...
 */

    //require_once(dirname(__DIR__) . CS_PATH . DS . 'config.php');
    include_once('config_inc.php');
       
       
    $query_jobs = array();
    
    //C1B means done by cron job 1 for branch...
    $query_jobs['customerC1B'] = 'SELECT * FROM customer WHERE Changed = 1';
    $query_jobs['inventoryadjustmentC1B'] = 'SELECT * FROM `inventoryadjustment` where Changed = 1 and StatusID = 7 and MovementTypeID != 10 UNION ALL SELECT * FROM `inventoryadjustment` where Changed = 1 and StatusID in (7, 23) and MovementTypeID = 10';
    $query_jobs['salesorderC1B'] = 'SELECT * FROM `salesorder` where Changed = 1 and StatusID in (7, 9)';
    $query_jobs['inventoryC1B'] = 'SELECT * FROM `inventory` where Changed = 1 and SOH = 0';
    $query_jobs['salesinvoiceC1B'] = 'SELECT * FROM `salesinvoice` where Changed = 1 and StatusID in (7, 8)';
    
    $query_jobs['inventorycountC1B'] = 'SELECT * FROM `inventorycount` where Changed = 1 and StatusID = 7';
    $query_jobs['inventoryinoutC1B'] = 'SELECT * FROM `inventoryinout` where Changed = 1 and StatusID = 7';
    $query_jobs['inventorytransferC1B'] = 'SELECT * FROM `inventorytransfer` where Changed = 1 and StatusID = 7';
    $query_jobs['deliveryreceiptC1B'] = 'SELECT * FROM `deliveryreceipt` where Changed = 1 and StatusID = 7';
    $query_jobs['officialreceiptC1B'] = 'SELECT * FROM `officialreceipt` where Changed = 1 and StatusID = 7';
    $query_jobs['dmcmC1B'] = 'SELECT * FROM `dmcm` where Changed = 1 and StatusID = 7';
    
    
    cron_doQueryJobs($query_jobs);
    
?>
