<?php
/* 
  @package TPI DSS Start of Day Transactions
  @author John Paul Pineda
  @email paulpineda19@yahoo.com
  @copyright 2013 John Paul Pineda
  @version 1.0 June 6, 2013
  
  @description This class is used for "Start of Day" transactions, which process the following:   
    1. Automatic creation of Sales Invoices for Sales Orders created as Advanced PO.
    2. Perform data synchronization to download updates on "Product Master File", "Promo" and "Incentives".
    3. Manually print Official Receipts that were auto-generated from Provisional Receipts the previous day.
    4. Manually print Sales Invoices that were auto-generated from Advance PO.
    5. Automatic computation of customers' penalty for unpaid sales invoices that are past due.    
*/

class TPI_DSS_Start_Of_Day_Transactions {

  public $_db, $_sp, $_tpi, $errmsg;
  
  // function __construct()
  function __construct() {
    
    global $db, $sp, $tpi;
    
    $this->_db=& $db;
    $this->_sp=& $sp;
    $this->_tpi=& $tpi;    
  }
  
  /**
	 * Method to use for automatic computation of customers' penalty for unpaid sales invoices that are past due. 	  	 
	 */
  function computeCustomersPenalty() {
    
    try {
  
      $this->_db->BeginTransaction();
      
      $rsDealersOpenSalesInvoices=$this->_tpi->getDealersOpenSalesInvoices();
      if($rsDealersOpenSalesInvoices->num_rows) {
        
        while($field=$rsDealersOpenSalesInvoices->fetch_object()) {
          
          $customer_id=$field->CustomerID;
          $sales_invoice_id=$field->ID;
          $sales_invoice_transaction_date=$field->TxnDate;
          $amount_due=$field->AmountDue;
          $outstanding_amount=$field->OutstandingBalance;
          $due_date=$field->DueDate;
          $days_due=$field->DaysDue;                        
          
          $penalty=tpi_get_dealer_penalty($amount_due, $days_due);
          
          $rs=$this->_sp->spCheckEODCustomerAccountsReceivable($this->_db, $customer_id, $sales_invoice_id);
          if($rs->num_rows) $this->_sp->spUpdateEODCustomerAccountsReceivable($this->_db, $customer_id, $sales_invoice_id, $outstanding_amount, $days_due);      
          else $this->_sp->spInsertEODCustomerAccountsReceivable($this->_db, $customer_id, $sales_invoice_id, $outstanding_amount, $days_due);      
                
          $rs=$this->_sp->spCheckEODCustomerPenalty($this->_db, $customer_id, $sales_invoice_id);
          if($rs->num_rows) $this->_sp->spUpdateEODCustomerPenalty($this->_db, $customer_id, $sales_invoice_id, $penalty, $penalty);	      
          else $this->_sp->spInsertEODCustomerPenalty($this->_db, $customer_id, $sales_invoice_id, $penalty, $penalty);  
        }
      }     
      
      $this->errmsg='Automatic computation of customers\' penalty for unpaid sales invoices that are past due has been successfully processed.';  
      $this->_db->commitTransaction();
      
      return true;
    } catch(Exception $e) {
    
      $this->errmsg='Failed to compute the customers\' penalty for unpaid sales invoices that are past due. The following error occured: '.$e->getMessage();
      $this->_db->rollbackTransaction();
      
      return false;
    }
  }            
}