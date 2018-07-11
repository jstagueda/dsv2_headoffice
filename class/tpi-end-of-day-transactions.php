<?php
/* 
  @package TPI DSS End of Day Transactions
  @author John Paul Pineda
  @email paulpineda19@yahoo.com
  @copyright 2013 John Paul Pineda
  @version 1.0 June 6, 2013
  
  @description This class is used for "End of Day" transactions, which process the following: 
    1. Automatic closing of sales orders with back orders that are 45 days old.
    2. Automatic closing of all unconfirmed sales orders(e.g sales orders that were saved as draft) created during the day, the status of the sales orders will be changed to "Cancelled".
    3. Automatic conversion of "Provisional Receipts" with cheques due on the following day.   
    4. Consolidation of sales per PMG(e.g DGS, CSP or Invoice), payment per PMG(e.g DGS, CSP or Invoice), BCR, Actives and Recruits based on the network.    
*/

class TPI_DSS_End_Of_Day_Transactions {

  public $_db, $_sp, $_tpi, $errmsg;
  public $end_of_day_transaction_session_employee_id;
  public $campaign_code, $two_digit_date_year, $datemonth, $dateyear, $current_date; 
  
  // function __construct()
  function __construct() {
    
    global $db, $sp, $tpi;
    
    $this->_db=& $db;
    $this->_sp=& $sp;
    $this->_tpi=& $tpi;
    
    $this->end_of_day_transaction_session_employee_id=isset($_SESSION['emp_id'])?$_SESSION['emp_id']:0;
    
    $this->campaign_code=strtoupper(date('My'));
    $this->two_digit_date_year=date('y');
    	
    $this->datemonth=date('m');
    $this->dateyear=date('Y');
    $this->current_date=date('Y-m-d');    
  }
  
  /**
	 * Method to use for consolidation of sales per PMG(e.g DGS, CSP or Invoice), payment per PMG(e.g DGS, CSP or Invoice), BCR, Actives and Recruits based on the network. 	  	 
	 */
  function consolidateBranchCollectionRating() {
    
    try {

      $this->_db->beginTransaction();
    
      // Get the list of the customers with Net Amount and Outstanding balance.
      $rsListDealer=$this->_sp->spSelectDGSbyCustomer($this->_db, $this->current_date);  
      if($rsListDealer->num_rows) {
      
        while($customerDetails=$rsListDealer->fetch_object()) {
        
          $total_dgs_sales=(((float)$customerDetails->GrossAmount)-((float)$customerDetails->BasicDiscount));      
          $total_on_time_amount_percentage_paid=(((float)$customerDetails->OutstandingBalance)/((float)$customerDetails->NetAmount));		
          $total_on_time_dgs_payment=($total_on_time_amount_percentage_paid*$total_dgs_sales);
          
          $total_dgs_cft_sales=((float)$customerDetails->DGSCFT);
          $total_dgs_ncft_sales=((float)$customerDetails->DGSNCFT);
          $total_dgs_cpi_sales=((float)$customerDetails->DGSCPI);          
          
          $customerID=$customerDetails->customerID;
          $outstandingBalance=$customerDetails->OutstandingBalance;
          $ibmID=$customerDetails->ibmID;            
          
          $paidCFT=($total_on_time_amount_percentage_paid*$total_dgs_cft_sales);
          $paidNCFT=($total_on_time_amount_percentage_paid*$total_dgs_ncft_sales);
          $paidCPI=($total_on_time_amount_percentage_paid*$total_dgs_cpi_sales);      
                
          $bcr=($total_on_time_dgs_payment/$total_dgs_sales)*100;
          $actives=$customerDetails->Actives;
          
          $rsCheckifBCRExist=$this->_sp->spSelectBCRbyCustomerID($this->_db, $customerID, $this->datemonth, $this->dateyear);            
          if($rsCheckifBCRExist->num_rows) $updateBCR=$this->_sp->spUpdateBrachCollectionRating($this->_db, $customerID, $this->dateyear, $this->datemonth, $total_on_time_dgs_payment, $total_dgs_sales, $bcr, $paidCFT, $paidNCFT, $paidCPI, $total_dgs_cft_sales, $total_dgs_ncft_sales, $total_dgs_cpi_sales);
          else $insertBCR=$this->_sp->spInsertBrachCollectionRating($this->_db, $customerID, $ibmID, $this->dateyear, $this->datemonth, $bcr, $total_on_time_dgs_payment, $total_dgs_sales, $paidCFT, $paidNCFT, $paidCPI, $total_dgs_cft_sales, $total_dgs_ncft_sales, $total_dgs_cpi_sales);
                
          $this->_tpi->updateSFABCRByIGS($this->campaign_code, $this->datemonth, $this->two_digit_date_year, $ibmID, $customerDetails->CustomerLevelID, $customerID, ((float)$customerDetails->GrossAmount), ((float)$customerDetails->BasicDiscount), ((float)$customerDetails->NetAmount), ((float)$customerDetails->OutstandingAmount), ((float)$customerDetails->OutstandingBalance), $bcr, $customerDetails->TotalNumberOfRecruits, $customerDetails->TotalNumberOfActives, ((int)$customerDetails->TotalNumberOfPOs), $this->end_of_day_transaction_session_employee_id);
          
          $this->_tpi->updateSFABCRPMGByIGSOrIBM($this->campaign_code, $this->datemonth, $this->two_digit_date_year, $ibmID, $customerDetails->CustomerLevelID, $customerID, 'CFT', ((float)$customerDetails->TotalCFTInvoiceAmount), $total_dgs_cft_sales, $this->end_of_day_transaction_session_employee_id);      
          $this->_tpi->updateSFABCRPMGByIGSOrIBM($this->campaign_code, $this->datemonth, $this->two_digit_date_year, $ibmID, $customerDetails->CustomerLevelID, $customerID, 'NCFT', ((float)$customerDetails->TotalNCFTInvoiceAmount), $total_dgs_ncft_sales, $this->end_of_day_transaction_session_employee_id);      
          $this->_tpi->updateSFABCRPMGByIGSOrIBM($this->campaign_code, $this->datemonth, $this->two_digit_date_year, $ibmID, $customerDetails->CustomerLevelID, $customerID, 'CPI', ((float)$customerDetails->TotalCPIInvoiceAmount), $total_dgs_cpi_sales, $this->end_of_day_transaction_session_employee_id);                          
        }
        
        $rsDGSbyIBM=$this->_tpi->getDGSbyIBM($this->datemonth, $this->two_digit_date_year);
        if($rsDGSbyIBM->num_rows) {
          
          while($field=$rsDGSbyIBM->fetch_object()) {
          
            $this->_tpi->updateSFABCRByIBM($this->campaign_code, $this->datemonth, $this->two_digit_date_year, $field->IBMID, $field->CustomerLevelID, $field->CustomerID, $field->TotalDGSSales, $field->TotalInvoiceAmount, $field->TotalOnTimeDGSPayment, $field->TotalOnTimeInvoicePayment, $field->TotalDGSPayment, $field->TotalInvoicePayment, $field->TotalBCR, $field->TotalNumberOfRecruits, $field->TotalNumberOfActives, $field->TotalNumberOfPO, $this->end_of_day_transaction_session_employee_id);                      
          }
        }
        
        $rsDGSbyIBMAndPMG=$this->_tpi->getDGSbyIBMAndPMG($this->datemonth, $this->two_digit_date_year);
        if($rsDGSbyIBMAndPMG->num_rows) {
          
          while($field=$rsDGSbyIBMAndPMG->fetch_object()) {
          
            $this->_tpi->updateSFABCRPMGByIGSOrIBM($this->campaign_code, $this->datemonth, $this->two_digit_date_year, $field->IBMID, $field->CustomerLevelID, $field->CustomerID, $field->PMGType, ((float)$field->TotalInvoiceAmount), ((float)$field->TotalDGSAmount), $this->end_of_day_transaction_session_employee_id);                      
          }
        }
      }
      
      $this->errmsg='Consolidation of Branch Collection Rating has been successfully processed.';      		
      $this->_db->commitTransaction();
      
      return true;		
    } catch(Exception $e) {
      
      $this->errmsg='Failed to consolidate the Branch Collection Rating. The following error occured: '.$e->getMessage();  
      $this->_db->rollbackTransaction();
      
      return false;
    }  
  }            
}