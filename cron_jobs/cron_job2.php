<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * @author: jdymosco
 * @date: January 16, 2013
 * @description: Second cron job process...
 */
    include_once('config_inc.php');
        
    $query_jobs = array();
    
    $query_jobs['customerdetailsC2B'] = 'SELECT * FROM customerdetails where Changed = 1';
    $query_jobs['tpi_customerdetailsC2B'] = 'SELECT * FROM tpi_customerdetails where Changed = 1';
    $query_jobs['customeraccountsreceivableC2B'] = 'SELECT * FROM customeraccountsreceivable where Changed = 1';
    $query_jobs['customerpenaltyC2B'] = 'SELECT * FROM customerpenalty where Changed = 1';
    $query_jobs['tpi_rcustomeribmC2B'] = 'SELECT * FROM tpi_rcustomeribm where Changed = 1';
    $query_jobs['tpi_rcustomerstatusC2B'] = 'SELECT * FROM tpi_rcustomerstatus where Changed = 1';
    $query_jobs['tpi_rcustomerbranchC2B'] = 'SELECT cu.Code CustomerCode, tcb.BranchID, tcb.IsPrimary, tcb.Remarks, tcb.CreatedBy, tcb.EnrollmentDate, tcb.Changed FROM tpi_rcustomerbranch tcb inner join customer cu on cu.ID = tcb.CustomerID where tcb.Changed = 1';
    $query_jobs['tpi_rcustomerpdaC2B'] = 'SELECT * FROM tpi_rcustomerpda where Changed = 1';
    $query_jobs['tpi_creditC2B'] = 'SELECT * FROM tpi_credit where Changed = 1';
    $query_jobs['tpi_creditlimitdetailsC2B'] = 'SELECT * FROM tpi_creditlimitdetails where Changed = 1 and StatusID = 23';
    $query_jobs['tpi_dealerwriteoffC2B'] = 'SELECT * FROM tpi_dealerwriteoff where Changed = 1 and StatusID = 23';
    $query_jobs['tpi_dealerpromotionC2B'] = 'SELECT * FROM tpi_dealerpromotion where Changed = 1';
    $query_jobs['tpi_dealertransferC2B'] = 'SELECT * FROM tpi_dealertransfer where Changed = 1';
    $query_jobs['tpi_branchcollectionratingC2B'] = 'SELECT * FROM tpi_branchcollectionrating';
    $query_jobs['tpi_customerstatsC2B'] = 'SELECT * FROM tpi_customerstats';
    $query_jobs['salesorderdetailsC2B'] = 'SELECT * FROM salesorderdetails where SalesOrderID in (SELECT ID FROM salesorder where Changed = 1 and StatusID in (7, 9))';
    $query_jobs['deliveryreceiptdetailsC2B'] = 'SELECT * FROM deliveryreceiptdetails where DeliveryReceiptID in (SELECT ID FROM deliveryreceipt where Changed = 1 and StatusID = 7)';
    $query_jobs['salesinvoicedetailsC2B'] = 'SELECT * FROM salesinvoicedetails where SalesInvoiceID in (SELECT ID FROM salesinvoice where Changed = 1 and StatusID in (7, 8))';
    $query_jobs['officialreceiptcashC2B'] = 'SELECT * FROM officialreceiptcash where OfficialReceiptID in (SELECT ID FROM officialreceipt where Changed = 1 and StatusID = 7)';
    $query_jobs['officialreceiptcheckC2B'] = 'SELECT * FROM officialreceiptcheck where OfficialReceiptID in (SELECT ID FROM officialreceipt where Changed = 1 and StatusID = 7)';
    $query_jobs['officialreceiptcommissionC2B'] = 'SELECT * FROM officialreceiptcommission where OfficialReceiptID in (SELECT ID FROM officialreceipt where Changed = 1 and StatusID = 7)';
    $query_jobs['officialreceiptdepositC2B'] = 'SELECT * FROM officialreceiptdeposit where OfficialReceiptID in (SELECT ID FROM officialreceipt where Changed = 1 and StatusID = 7)';
    $query_jobs['officialreceiptdetailsC2B'] = 'SELECT * FROM officialreceiptdetails where OfficialReceiptID in (SELECT ID FROM officialreceipt where Changed = 1 and StatusID = 7)';
    $query_jobs['dmcmdetailsC2B'] = 'SELECT * FROM dmcmdetails where DMCMID in (SELECT ID FROM dmcm where Changed = 1 and StatusID = 7)';
    $query_jobs['inventoryadjustmentdetailsC2B'] = 'SELECT * FROM inventoryadjustmentdetails where InventoryAdjustmentID in (Select ID from inventoryadjustment where Changed = 1 and StatusID = 7 and MovementTypeID != 10 UNION ALL Select ID from inventoryadjustment where Changed = 1 and StatusID = 23 and MovementTypeID = 10)';
    $query_jobs['inventorycountdetailsC2B'] = 'SELECT * FROM inventorycountdetails where InventoryCountID in (SELECT ID FROM inventorycount where Changed = 1 and StatusID = 7)';
    $query_jobs['inventoryinoutdetailsC2B'] = 'SELECT * FROM inventoryinoutdetails where InventoryInOutID in (SELECT ID FROM inventoryinout where Changed = 1 and StatusID = 7)';
    $query_jobs['inventorytransferdetailsC2B'] = 'SELECT * FROM inventorytransferdetails where InventoryTransferID in (SELECT ID FROM inventorytransfer where Changed = 1 and StatusID = 7)';
    
    cron_doQueryJobs($query_jobs);
    
?>
