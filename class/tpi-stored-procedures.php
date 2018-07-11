<?php
/* 
  @package TPI DSS Stored Procedures
  @author John Paul Pineda
  @email paulpineda19@yahoo.com
  @copyright 2013 John Paul Pineda
  @version 1.0 June 5, 2013
  
  @description This class is a separate and additional Database Stored Procedures. The purpose of this class is: To simply avoid any source code conflict or error, like for example there's an existing database stored procedure named: "spInsertSOHeader" which is being used by the "Create Sales Order" sub-module and let's say I updated the said stored procedure and unfortunately was not aware that the said stored procedure is being also used by the other modules/sub-modules, then those other modules/sub-modules might not work correctly as well right? So by using this on modules or sub-modules where I will only make revisions I can ensure that my source code revisions won't affect other modules/sub-modules.
*/

class TPI_DSS_Stored_Procedures {

  public $_db;
  
  // function __construct()
  function __construct() {
    
    global $db;
    
    $this->_db=& $db;    
  }
  
  // function spSelectExistingCustomer($database, $customer_id)
  function spSelectExistingCustomer($database, $customer_id) {
      
  	$query="CALL dev1_spSelectExistingCustomer($customer_id);";
  	$rs=$database->execute($query);
  	return $rs;
	}
  
  // function selectCustomerIBM($customer_id)
  function selectCustomerIBM($customer_id) {
    
    $query="CALL tpiSelectCustomerIBM($customer_id);";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function checkIfAdvPO()
  function checkIfAdvPO() {
    
    global $database;
    
    $query="CALL tpiCheckifAdvPO()";
    $rs=$database->execute($query);
    return $rs;            
  }
  
  // function cacheInventoryCountDetails($database)
  function cacheInventoryCountDetails($database) {
  
		$query="CALL tpiCacheInventoryCountDetails();"; 
		$database->execute($query);			
	}
  
  // function loadInventoryCountDetailsCache($database, $txn_id, $warehouse_id)
  function loadInventoryCountDetailsCache($database, $txn_id, $warehouse_id) {
    
    $query="CALL tpiLoadInventoryCountDetailsCache($txn_id, $warehouse_id);";
    $rs=$database->execute($query);
    return $rs;
  }
  
  // function spLoadTableRecords($database, $table, $fields=array(), $criteria)
  function spLoadTableRecords($database, $table, $fields=array(), $criteria) {
    
    $fields=sizeof($fields)>1?implode(', ', $fields):'*';
    
    $query="SELECT ".$fields." FROM ".$table." WHERE ".$criteria;
    $rs=$database->execute($query);
    return $rs;
  }
  
  // function kitComponent($database, $product_id)
  function kitComponent($database, $product_id) {
    
   global $sp;
   
   $query="SELECT KitID FROM productkit WHERE ComponentID='".$product_id."'";
   $rs=$database->execute($query);
   
   return ((int)$rs->num_rows)>0?true:false; 
  }
  
  // function insertSOHeader($database, $v_CustomerID, $v_DocumentNo, $v_EmployeeID, $v_WarehouseID, $v_GrossAmount, $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount, $v_TotalCPI, $v_TotalCFT, $v_TotalNCFT, $v_statusID, $v_termsID, $gsutypeID, $promoDate, $v_isAdvanced, $branchID, $v_Remarks='')
  function insertSOHeader($database, $v_CustomerID, $v_DocumentNo, $v_EmployeeID, $v_WarehouseID, $v_GrossAmount, $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount, $v_TotalCPI, $v_TotalCFT, $v_TotalNCFT, $v_statusID, $v_termsID, $gsutypeID, $promoDate, $v_isAdvanced, $branchID, $v_Remarks='') {
  		
	 $query="CALL tpiInsertSOHeader($v_CustomerID, '$v_DocumentNo', $v_EmployeeID, $v_WarehouseID, $v_GrossAmount, $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount, $v_TotalCPI, $v_TotalCFT, $v_TotalNCFT, $v_statusID, $v_termsID, $gsutypeID, '$promoDate', $v_isAdvanced, $branchID, '$v_Remarks');";					
	 $rs=$database->execute($query);
	 return $rs;
	}
  
  // function insertSOHeaderDraft($database, $v_CustomerID, $v_DocumentNo, $v_EmployeeID, $v_WarehouseID, $v_GrossAmount, $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount, $v_TotalCPI, $v_TotalCFT, $v_TotalNCFT, $v_statusID, $v_termsID, $gsutypeID, $v_Remarks='')
  function insertSOHeaderDraft($database, $v_CustomerID, $v_DocumentNo, $v_EmployeeID, $v_WarehouseID, $v_GrossAmount, $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount, $v_TotalCPI, $v_TotalCFT, $v_TotalNCFT, $v_statusID, $v_termsID, $gsutypeID, $v_Remarks='') {
  		
		$query="CALL tpiInsertSOHeaderDraft($v_CustomerID, '$v_DocumentNo', $v_EmployeeID, $v_WarehouseID, $v_GrossAmount, $v_BasicDiscount, $v_AddtlDiscount, $v_VatAmount, $v_AddtlDiscountPrev, $v_NetAmount, $v_TotalCPI, $v_TotalCFT, $v_TotalNCFT, $v_statusID, $v_termsID, $gsutypeID, '$v_Remarks');";					
		$rs=$database->execute($query);
		return $rs;
	}
  
  // function getAvailableCreditByCustomerID($database, $customer_id)
  function getAvailableCreditByCustomerID($database, $customer_id) {
    
    $query="CALL tpigetAvailableCreditByCustomerID($customer_id);";
    $rs=$database->execute($query);
    return $rs;
  }
  
  // function selectSalesInvoiceForAdjustment($v_txnID, $v_customerID)
  function selectSalesInvoiceForAdjustment($v_txnID, $v_customerID) {
  	
		$query="CALL tpiSelectSalesInvoiceForAdjustment($v_txnID, $v_customerID);";			
	  $rs=$this->database->execute($query);
	  return $rs;		
	}
  
  // function selectSalesInvoiceByID($database, $v_tid)
  function selectSalesInvoiceByID($database, $v_tid) {
  		
		$query="CALL tpiSelectSalesInvoiceByID($v_tid);";			
		$rs=$database->execute($query);
		return $rs;
	}
  
  // function insertBranchCollectionDeposits($database, $colldate, $bankid, $branchcode, $depdate, $valno, $cash, $check, $checknos)
  function insertBranchCollectionDeposits($database, $colldate, $bankid, $branchcode, $depdate, $valno, $cash, $check, $checknos) {
  
		$query="CALL tpiInsertBranchCollectionDeposits('$colldate', $bankid, '$branchcode', '$depdate', '$valno', $cash, $check, '$checknos');";				
		$rs=$database->execute($query);				
	  return $rs;		
	}
  
  // function selectOutstandingInvoicesForAutoSettlement($database, $vCustomerId)
  function selectOutstandingInvoicesForAutoSettlement($database, $vCustomerId) {
  
    $query="CALL tpiSelectOutstandingInvoicesForAutoSettlement($vCustomerId);";
    $rs=$database->execute($query);
    return $rs; 
   }
  
  // function selectBORptByProd($database, $isDetailed, $filter, $branchID, $fromDate, $toDate, $productLine, $prodCode, $campaignID) 
  function selectBORptByProd($database, $isDetailed, $filter, $branchID, $fromDate, $toDate, $productLine, $prodCode, $campaignID) {
  
    $query="CALL tpiSelectBORptByProd($isDetailed, $filter, $branchID, '$fromDate', '$toDate', '%$productLine%', '%$prodCode%', $campaignID);";			
    $rs=$database->execute($query);				
    return $rs;		
	}
  
  // function selectBORptBySO($database, $filter, $branchID, $fromDate, $toDate, $productLine, $prodCode, $campaignID)  
  function selectBORptBySO($database, $isDetailed, $filter, $branchID, $fromDate, $toDate, $productLine, $prodCode, $campaignID) {
  
		$query="CALL tpiSelectBORptBySO($isDetailed, $filter, $branchID, '$fromDate', '$toDate', '%$productLine%', '%$prodCode%', $campaignID);";							
		$rs=$database->execute($query);				
	  return $rs;		
	}
  
  // function selectBORptByCustomer($database, $isDetailed, $filter, $branchID, $fromDate, $toDate, $productLine, $prodCode, $campaignID)
  function selectBORptByCustomer($database, $isDetailed, $filter, $branchID, $fromDate, $toDate, $productLine, $prodCode, $campaignID) {
  
    $query="CALL tpiSelectBORptByCustomer($isDetailed, $filter, $branchID, '$fromDate', '$toDate', '%$productLine%', '%$prodCode%', $campaignID);";	    
    $rs=$database->execute($query);				
    return $rs;		
	}
  
  // function selectModuleControl($database, $userid, $pageid)
  function selectModuleControl($database, $userid, $pageid) {
		
		$query="CALL tpiSelectModuleControl($userid, $pageid);";
		$rs=$database->execute($query);
		return $rs;	
	}
  
  // function getDealerInfoByID($database, $v_CustomerID, $v_fields, $v_order_by)
  function getDealerInfoByID($database, $v_CustomerID, $v_fields, $v_order_by) {
    
    $query="CALL tpiGetDealerInfoByID($v_CustomerID, '$v_fields', '$v_order_by');";
		$rs=$database->execute($query);
		return $rs; 
  }
  
  // function updateDailySalesForMonthlySales($database, $v_IBMID, $v_IBMLevelID, $v_IBMCode, $v_CustomerID, $v_CustomerLevelID, $v_CustomerCode, $v_DGSSales, $v_Campaign, $v_RefTxnID, $v_TxnDate, $v_CreatedBy)
  function updateDailySalesForMonthlySales($database, $v_IBMID, $v_IBMLevelID, $v_IBMCode, $v_CustomerID, $v_CustomerLevelID, $v_CustomerCode, $v_DGSSales, $v_Campaign, $v_RefTxnID, $v_TxnDate, $v_CreatedBy) {
  
    $query="CALL tpiUpdateDailySalesForMonthlySales($v_IBMID, $v_IBMLevelID, '$v_IBMCode', $v_CustomerID, $v_CustomerLevelID, '$v_CustomerCode', $v_DGSSales, '$v_Campaign', $v_RefTxnID, '$v_TxnDate', $v_CreatedBy);";
		$rs=$database->execute($query);
		// return $rs;    
  }
  
  // function updateIBMNumberOfRecruits($database, $v_IBMID, $v_CustomerID, $v_RefTxnID, $v_TxnDate, $v_CreatedBy)
  function updateIBMNumberOfRecruits($database, $v_IBMID, $v_CustomerID, $v_RefTxnID, $v_TxnDate, $v_CreatedBy) {
    
    $query="CALL tpiUpdateIBMNumberOfRecruits($v_IBMID, $v_CustomerID, $v_RefTxnID, '$v_TxnDate', $v_CreatedBy);";
		$rs=$database->execute($query);
    // return $rs;  
  }
  
  // function insertApplicableSingleLinePromos($database, $v_sales_order_session_id, $v_current_date, $v_product_code, $v_product_price, $v_number_of_product_ordered)
  function insertApplicableSingleLinePromos($database, $v_sales_order_session_id, $v_current_date, $v_product_code, $v_product_price, $v_number_of_product_ordered) {
    
    $query="CALL tpiInsertApplicableSingleLinePromos('$v_sales_order_session_id', '$v_current_date', '$v_product_code', $v_product_price, $v_number_of_product_ordered);";
		$rs=$database->execute($query);
    // return $rs;  
  }
  
  // function getApplicableSingleLinePromos($database, $v_sales_order_session_id)
  function getApplicableSingleLinePromos($database, $v_sales_order_session_id) {
    
    $query="CALL tpiGetApplicableSingleLinePromos('$v_sales_order_session_id');";
    $rs=$database->execute($query);
    return $rs;  
  }
  
  // function getApplicableSingleLinePromoEntitlement($database, $v_promo_id, $v_promo_entitlement_id)
  function getApplicableSingleLinePromoEntitlement($database, $v_promo_id, $v_promo_entitlement_id) {
    
    $query="CALL tpiGetApplicableSingleLinePromoEntitlement($v_promo_id, $v_promo_entitlement_id);";
    $rs=$database->execute($query);
    return $rs;  
  }
  
  // function insertProductsListBeingOrdered($database, $v_sales_order_session_id, $v_current_date, $v_product_code, $v_product_price, $v_number_of_product_ordered)
  function insertProductsListBeingOrdered($database, $v_sales_order_session_id, $v_current_date, $v_product_code, $v_product_price, $v_number_of_product_ordered) {
    
    $query="CALL tpiInsertProductsListBeingOrdered('$v_sales_order_session_id', '$v_current_date', '$v_product_code', $v_product_price, $v_number_of_product_ordered);";
    $rs=$database->execute($query);
    // return $rs;  
  }
  
  // function getApplicableMultiLinePromos($database, $v_current_date)
  function getApplicableMultiLinePromos($database, $v_current_date) {
        
    $query="CALL tpiGetApplicableMultiLinePromos('$v_current_date');";
    $rs=$database->execute($query);
    return $rs;
  }
    
  // function insertApplicableMultiLinePromoDetails($database, $v_sales_order_session_id, $v_current_date, $v_customer_id, $v_promo_id, $v_promo_code, $v_promo_start_date, $v_promo_end_date)
  function insertApplicableMultiLinePromoDetails($database, $v_sales_order_session_id, $v_current_date, $v_customer_id, $v_promo_id, $v_promo_code, $v_promo_start_date, $v_promo_end_date) {
    
    $query="CALL tpiInsertApplicableMultiLinePromoDetails('$v_sales_order_session_id', '$v_current_date', $v_customer_id, $v_promo_id, '$v_promo_code', '$v_promo_start_date', '$v_promo_end_date');";
    $rs=$database->execute($query);
    // return $rs;  
  }
  
  // function getApplicableMultiLinePromosSortByMaxSavings($database, $v_sales_order_session_id)
  function getApplicableMultiLinePromosSortByMaxSavings($database, $v_sales_order_session_id) {
    
    $query="CALL tpiGetApplicableMultiLinePromosSortByMaxSavings('$v_sales_order_session_id');";
    $rs=$database->execute($query);
    return $rs;  
  }
  
  // function getApplicableMultiLinePromoEntitlements($database, $v_promo_id)
  function getApplicableMultiLinePromoEntitlements($database, $v_promo_id) {
    
    $query="CALL tpiGetApplicableMultiLinePromoEntitlements($v_promo_id);";
    $rs=$database->execute($query);
    return $rs;  
  }
  
  // function getApplicableMultiLinePromoEntitlement($database, $v_promo_id, $v_entitlement_type, $v_applicable_multi_line_promo_entitlement_details_id)
  function getApplicableMultiLinePromoEntitlement($database, $v_promo_id, $v_entitlement_type, $v_applicable_multi_line_promo_entitlement_details_id) {
    
    $query="CALL tpiGetApplicableMultiLinePromoEntitlement($v_promo_id, $v_entitlement_type, $v_applicable_multi_line_promo_entitlement_details_id);";
    $rs=$database->execute($query);
    return $rs;  
  }
  
  // function insertAppliedMultiLinePromoEntitlementDetails($database, $v_customer_id, $v_reference_sales_order_id, $v_promo_id, $v_promo_entitlement_type, $v_promo_entitlement_details_id, $v_product_id, $v_product_quantity, $v_product_outstanding_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id)
  function insertAppliedMultiLinePromoEntitlementDetails($database, $v_customer_id, $v_reference_sales_order_id, $v_promo_id, $v_promo_entitlement_type, $v_promo_entitlement_details_id, $v_product_id, $v_product_quantity, $v_product_outstanding_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id) {
    
    $query="CALL tpiInsertAppliedMultiLinePromoEntitlementDetails($v_customer_id, $v_reference_sales_order_id, $v_promo_id, $v_promo_entitlement_type, $v_promo_entitlement_details_id, $v_product_id, $v_product_quantity, $v_product_outstanding_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id);";
    $rs=$database->execute($query);
    // return $rs;  
  }
  
  // function insertAppliedSingleLinePromoEntitlementDetails($database, $v_customer_id, $v_reference_sales_order_id, $v_promo_id, $v_promo_entitlement_criteria, $v_promo_entitlement_id, $v_product_id, $v_product_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id)
  function insertAppliedSingleLinePromoEntitlementDetails($database, $v_customer_id, $v_reference_sales_order_id, $v_promo_id, $v_promo_entitlement_criteria, $v_promo_entitlement_id, $v_product_id, $v_product_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id) {
    
    $query="CALL tpiInsertAppliedSingleLinePromoEntitlementDetails($v_customer_id, $v_reference_sales_order_id, $v_promo_id, $v_promo_entitlement_criteria, $v_promo_entitlement_id, $v_product_id, $v_product_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id);";
    $rs=$database->execute($query);
    // return $rs;  
  }
  
  // function getAppliedPromoEntitlements($v_reference_sales_order_id)
  function getAppliedPromoEntitlements($v_reference_sales_order_id) {
    
    $query="CALL tpiGetAppliedPromoEntitlements($v_reference_sales_order_id);";    
    $rs=$this->_db->execute($query);				
    return $rs;  
  }
  
  // function getOverlappedApplicablePromoEntitlement($v_sales_order_session_id, $v_applicable_single_line_promo_id, $v_product_id)
  function getOverlappedApplicablePromoEntitlement($v_sales_order_session_id, $v_applicable_single_line_promo_id, $v_product_id) {
    
    $query="CALL tpiGetOverlappedApplicablePromoEntitlement('$v_sales_order_session_id', $v_applicable_single_line_promo_id, $v_product_id);";    
    $rs=$this->_db->execute($query);    				
    return $rs;    
  }
  
  // function refreshProductsListBeingOrdered()
  function refreshProductsListBeingOrdered() {
    
    $query="CALL tpiRefreshProductsListBeingOrdered();";    
    $rs=$this->_db->execute($query);    				
    // return $rs;  
  }
  
  // function getAvailableProductQuantityForSI($v_sales_order_session_id, $v_product_id, $v_entitlement_quantity)  
  function getAvailableProductQuantityForSI($v_sales_order_session_id, $v_product_id, $v_entitlement_quantity) {
    
    $query="CALL tpiGetAvailableProductQuantityForSI('$v_sales_order_session_id', $v_product_id, $v_entitlement_quantity);";    
    $rs=$this->_db->execute($query);    				
    return $rs;  
  }
  
  // function getApplicableOverlayPromos($v_current_date)
  function getApplicableOverlayPromos($v_current_date) {
        
    $query="CALL tpiGetApplicableOverlayPromos('$v_current_date');";
    $rs=$this->_db->execute($query);    				
    return $rs;
  }
  
  // function insertApplicableOverlayPromoDetails($v_sales_order_session_id, $v_promo_id, $v_promo_code, $v_promo_entitlement_details_maximum_quantity, $v_purchase_requirement_type, $v_overlay_type)
  function insertApplicableOverlayPromoDetails($v_sales_order_session_id, $v_promo_id, $v_promo_code, $v_promo_entitlement_details_maximum_quantity, $v_purchase_requirement_type, $v_overlay_type) {
    
    $query="CALL tpiInsertApplicableOverlayPromoDetails('$v_sales_order_session_id', $v_promo_id, '$v_promo_code', $v_promo_entitlement_details_maximum_quantity, $v_purchase_requirement_type, $v_overlay_type);";
    $rs=$this->_db->execute($query);
    // return $rs;  
  }
  
  // function getPromoBuyinRequirement($v_promo_id)
  function getPromoBuyinRequirement($v_promo_id) {
    
    $query="CALL tpiGetPromoBuyinRequirement($v_promo_id);";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function checkIfPromoBuyinRequirementHasBeenMet($v_sales_order_session_id, $v_customer_id, $v_purchase_requirement_type, $v_product_level_id, $v_product_id, $v_type, $v_minimum_quantity, $v_minimum_amount, $v_start_date, $v_end_date)
  function checkIfPromoBuyinRequirementHasBeenMet($v_sales_order_session_id, $v_customer_id, $v_purchase_requirement_type, $v_product_level_id, $v_product_id, $v_type, $v_minimum_quantity, $v_minimum_amount, $v_start_date, $v_end_date) {
    
    $query="CALL tpiCheckIfPromoBuyinRequirementHasBeenMet('$v_sales_order_session_id', $v_customer_id, $v_purchase_requirement_type, $v_product_level_id, $v_product_id, $v_type, $v_minimum_quantity, $v_minimum_amount, '$v_start_date', '$v_end_date');";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function getApplicableOverlayPromosSortByMaxSavings($v_sales_order_session_id)
  function getApplicableOverlayPromosSortByMaxSavings($v_sales_order_session_id) {
    
    $query="CALL tpiGetApplicableOverlayPromosSortByMaxSavings('$v_sales_order_session_id');";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function getApplicableOverlayPromoEntitlements($v_promo_id)
  function getApplicableOverlayPromoEntitlements($v_promo_id) {
    
    $query="CALL tpiGetApplicableOverlayPromoEntitlements($v_promo_id);";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function getApplicableOverlayPromoEntitlement($database, $v_promo_id, $v_entitlement_type, $v_applicable_overlay_promo_entitlement_details_id)
  function getApplicableOverlayPromoEntitlement($database, $v_promo_id, $v_entitlement_type, $v_applicable_overlay_promo_entitlement_details_id) {
    
    $query="CALL tpiGetApplicableOverlayPromoEntitlement($v_promo_id, $v_entitlement_type, $v_applicable_overlay_promo_entitlement_details_id);";
    $rs=$database->execute($query);
    return $rs;  
  }
  
  // function insertAppliedOverlayPromoEntitlementDetails($database, $v_customer_id, $v_reference_sales_order_id, $v_promo_id, $v_promo_entitlement_type, $v_promo_entitlement_details_id, $v_product_id, $v_product_quantity, $v_product_outstanding_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id)  
  function insertAppliedOverlayPromoEntitlementDetails($database, $v_customer_id, $v_reference_sales_order_id, $v_promo_id, $v_promo_entitlement_type, $v_promo_entitlement_details_id, $v_product_id, $v_product_quantity, $v_product_outstanding_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id) {
    
    $query="CALL tpiInsertAppliedOverlayPromoEntitlementDetails($v_customer_id, $v_reference_sales_order_id, $v_promo_id, $v_promo_entitlement_type, $v_promo_entitlement_details_id, $v_product_id, $v_product_quantity, $v_product_outstanding_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id);";
    $rs=$database->execute($query);
    // return $rs;  
  }
  
  // function insertProductsListOrdered($v_sales_order_session_id, $v_customer_id)
  function insertProductsListOrdered($v_sales_order_session_id, $v_customer_id) {
		
		$query="CALL tpiInsertProductsListOrdered('$v_sales_order_session_id', $v_customer_id);";
		$rs=$this->_db->execute($query);
		// return $rs;
	}
  
  function insertAppliedPromoApplicationFunction($v_sales_order_session_id, $v_promo_id, $v_application_function) {
  
    $query="CALL tpiInsertAppliedPromoApplicationFunction('$v_sales_order_session_id', $v_promo_id, '$v_application_function');";
    $rs=$this->_db->execute($query);
    // return $rs;    
  }
  
  // function insertAvailedApplicablePromoByCustomer($v_customer_id, $v_gsu_type_id, $v_promo_id)
  function insertAvailedApplicablePromoByCustomer($v_customer_id, $v_gsu_type_id, $v_promo_id) {
		
  	$query="CALL tpiInsertAvailedApplicablePromoByCustomer($v_customer_id, $v_gsu_type_id, $v_promo_id);";
  	$rs=$this->_db->execute($query);
  	// return $rs;
	}
  
  // function getPreviouslyAppliedPromosEntitlements($v_sales_order_session_id)
  function getPreviouslyAppliedPromosEntitlements($v_sales_order_session_id) {
  
    $query="CALL tpiGetPreviouslyAppliedPromosEntitlements('$v_sales_order_session_id');";
    $rs=$this->_db->execute($query);
    return $rs;
  }
  
  // function getApplicableIncentives($v_customer_id, $v_current_date)
  function getApplicableIncentives($v_customer_id, $v_current_date) {
        
    $query="CALL tpiGetApplicableIncentives($v_customer_id, '$v_current_date');";
    $rs=$this->_db->execute($query);
    return $rs;
  }
  
  // function getIncentiveBuyinRequirement($v_incentive_id)
  function getIncentiveBuyinRequirement($v_incentive_id) {
    
    $query="CALL tpiGetIncentiveBuyinRequirement($v_incentive_id);";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function checkIfIncentiveBuyinRequirementHasBeenMet($v_sales_order_session_id, $v_customer_id, $v_sales_order_date, $v_incentive_type_id, $v_mechanics_type_id, $v_incentive_buyin_id, $v_product_level_id, $v_product_id, $v_criteria_id, $v_minimum_quantity, $v_minimum_amount, $v_start_date, $v_end_date, $v_entitlement_type, $v_entitlement_quantity, $v_special_criteria) 
  function checkIfIncentiveBuyinRequirementHasBeenMet($v_sales_order_session_id, $v_customer_id, $v_sales_order_date, $v_incentive_type_id, $v_mechanics_type_id, $v_incentive_buyin_id, $v_product_level_id, $v_product_id, $v_criteria_id, $v_minimum_quantity, $v_minimum_amount, $v_start_date, $v_end_date, $v_entitlement_type, $v_entitlement_quantity, $v_special_criteria) {
    
    $query="CALL tpiCheckIfIncentiveBuyinRequirementHasBeenMet('$v_sales_order_session_id', $v_customer_id, '$v_sales_order_date', $v_incentive_type_id, $v_mechanics_type_id, $v_incentive_buyin_id, $v_product_level_id, $v_product_id, $v_criteria_id, $v_minimum_quantity, $v_minimum_amount, '$v_start_date', '$v_end_date', $v_entitlement_type, $v_entitlement_quantity, $v_special_criteria);";
    $rs=$this->_db->execute($query);
    return $rs;    
  }
  
  // function insertApplicableIncentiveDetails($v_sales_order_session_id, $v_incentive_id, $v_incentive_code, $v_incentive_entitlement_details_maximum_quantity, $v_incentive_type_id, $v_mechanics_type_id, $v_incentive_buyin_id)
  function insertApplicableIncentiveDetails($v_sales_order_session_id, $v_incentive_id, $v_incentive_code, $v_incentive_entitlement_details_maximum_quantity, $v_incentive_type_id, $v_mechanics_type_id, $v_incentive_buyin_id) {
    
    $query="CALL tpiInsertApplicableIncentiveDetails('$v_sales_order_session_id', $v_incentive_id, '$v_incentive_code', $v_incentive_entitlement_details_maximum_quantity, $v_incentive_type_id, $v_mechanics_type_id, $v_incentive_buyin_id);";
    $rs=$this->_db->execute($query);
    // return $rs;    
  }
  
  // function getApplicableIncentivesBuyinIDs($v_sales_order_session_id)
  function getApplicableIncentivesBuyinIDs($v_sales_order_session_id) {
    
    $query="CALL tpiGetApplicableIncentivesBuyinIDs('$v_sales_order_session_id');";
    $rs=$this->_db->execute($query);
    return $rs;    
  }
  
  // function getApplicableIncentiveEntitlementsByBuyinID($v_sales_order_session_id, $v_incentive_buyin_id)
  function getApplicableIncentiveEntitlementsByBuyinID($v_sales_order_session_id, $v_incentive_buyin_id) {
    
    $query="CALL tpiGetApplicableIncentiveEntitlementsByBuyinID('$v_sales_order_session_id', $v_incentive_buyin_id);";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function getApplicableIncentiveEntitlement($v_incentive_buyin_id, $v_entitlement_type, $v_applicable_incentive_entitlement_details_id)
  function getApplicableIncentiveEntitlement($v_incentive_buyin_id, $v_entitlement_type, $v_applicable_incentive_entitlement_details_id) {
    
    $query="CALL tpiGetApplicableIncentiveEntitlement($v_incentive_buyin_id, $v_entitlement_type, $v_applicable_incentive_entitlement_details_id);";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function insertAppliedIncentiveEntitlementDetails($v_customer_id, $v_reference_sales_order_id, $v_incentive_id, $v_incentive_buyin_id, $v_incentive_entitlement_type, $v_incentive_entitlement_details_id, $v_product_id, $v_product_quantity, $v_product_outstanding_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id)
  function insertAppliedIncentiveEntitlementDetails($v_customer_id, $v_reference_sales_order_id, $v_incentive_id, $v_incentive_buyin_id, $v_incentive_entitlement_type, $v_incentive_entitlement_details_id, $v_product_id, $v_product_quantity, $v_product_outstanding_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id) {
    
    $query="CALL tpiInsertAppliedIncentiveEntitlementDetails($v_customer_id, $v_reference_sales_order_id, $v_incentive_id, $v_incentive_buyin_id, $v_incentive_entitlement_type, $v_incentive_entitlement_details_id, $v_product_id, $v_product_quantity, $v_product_outstanding_quantity, $v_product_effective_price, $v_product_savings, $v_product_pmg_id);";
    $rs=$this->_db->execute($query);
    // return $rs;  
  }
  
  // function getAppliedIncentiveEntitlements($v_reference_sales_order_id)
  function getAppliedIncentiveEntitlements($v_reference_sales_order_id) {
    
    $query="CALL tpiGetAppliedIncentiveEntitlements($v_reference_sales_order_id);";    
    $rs=$this->_db->execute($query);				
    return $rs;  
  }
  
  // function insertAvailedApplicableIncentiveByCustomer($v_customer_id, $v_gsu_type_id, $v_incentive_id)
  function insertAvailedApplicableIncentiveByCustomer($v_customer_id, $v_gsu_type_id, $v_incentive_id) {
		
  	$query="CALL tpiInsertAvailedApplicableIncentiveByCustomer($v_customer_id, $v_gsu_type_id, $v_incentive_id);";
  	$rs=$this->_db->execute($query);
  	// return $rs;
	}
  
  // function getDealerInformationByIDOrCode($v_customer_id, $v_customer_code)
  function getDealerInformationByIDOrCode($v_customer_id, $v_customer_code) {
    
    $query="CALL tpiGetDealerInformationByIDOrCode($v_customer_id, '$v_customer_code');";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function getOpenSalesInvoices($v_customer_id, $v_are_open_sales_invoices_only=0)
  function getOpenSalesInvoices($v_customer_id, $v_are_open_sales_invoices_only=0) {
    
    $query="CALL tpiGetOpenSalesInvoices($v_customer_id, $v_are_open_sales_invoices_only);";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function getNumberOfApplicableIncentiveBuyinRequirements($v_sales_order_session_id, $v_incentive_id)
  function getNumberOfApplicableIncentiveBuyinRequirements($v_sales_order_session_id, $v_incentive_id) {
    
    $query="CALL tpiGetNumberOfApplicableIncentiveBuyinRequirements('$v_sales_order_session_id', $v_incentive_id);";                
    $rs=$this->_db->execute($query);
    
    if($rs->num_rows) {
      
      while($field=$rs->fetch_object()) return ((int)$field->NumberOfApplicableIncentiveBuyinRequirements);  
    }          
  }
  
  // function updateSFABCRByIGS($v_campaign_code, $v_month, $v_year, $v_ibm_id, $v_customer_level_id, $v_customer_id, $v_gross_amount, $v_basic_discount, $v_net_amount, $v_outstanding_balance, $v_total_on_time_invoice_payment, $v_total_bcr, $v_total_number_of_recruits, $v_total_number_of_actives, $v_total_number_of_pos, $v_created_by)
  function updateSFABCRByIGS($v_campaign_code, $v_month, $v_year, $v_ibm_id, $v_customer_level_id, $v_customer_id, $v_gross_amount, $v_basic_discount, $v_net_amount, $v_outstanding_balance, $v_total_on_time_invoice_payment, $v_total_bcr, $v_total_number_of_recruits, $v_total_number_of_actives, $v_total_number_of_pos, $v_created_by) {
  
    $query="CALL tpiUpdateSFABCRByIGS('$v_campaign_code', $v_month, $v_year, $v_ibm_id, $v_customer_level_id, $v_customer_id, $v_gross_amount, $v_basic_discount, $v_net_amount, $v_outstanding_balance, $v_total_on_time_invoice_payment, $v_total_bcr, $v_total_number_of_recruits, $v_total_number_of_actives, $v_total_number_of_pos, $v_created_by);";
    $rs=$this->_db->execute($query);
    // return $rs;  
  }
  
  // function getDGSbyIBM($v_campaign_month, $v_campaign_year)
  function getDGSbyIBM($v_campaign_month, $v_campaign_year) {
    
    $query="CALL tpiGetDGSbyIBM($v_campaign_month, $v_campaign_year);";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function updateSFABCRByIBM($v_campaign_code, $v_campaign_month, $v_campaign_year, $v_ibm_id, $v_customer_level_id, $v_customer_id, $v_total_dgs_sales, $v_total_invoice_amount, $v_total_on_time_dgs_payment, $v_total_on_time_invoice_payment, $v_total_dgs_payment, $v_total_invoice_payment, $v_total_bcr, $v_total_number_of_recruits, $v_total_number_of_actives, $v_total_number_of_pos, $v_created_by)
  function updateSFABCRByIBM($v_campaign_code, $v_campaign_month, $v_campaign_year, $v_ibm_id, $v_customer_level_id, $v_customer_id, $v_total_dgs_sales, $v_total_invoice_amount, $v_total_on_time_dgs_payment, $v_total_on_time_invoice_payment, $v_total_dgs_payment, $v_total_invoice_payment, $v_total_bcr, $v_total_number_of_recruits, $v_total_number_of_actives, $v_total_number_of_pos, $v_created_by) {
  
    $query="CALL tpiUpdateSFABCRByIBM('$v_campaign_code', $v_campaign_month, $v_campaign_year, $v_ibm_id, $v_customer_level_id, $v_customer_id, $v_total_dgs_sales, $v_total_invoice_amount, $v_total_on_time_dgs_payment, $v_total_on_time_invoice_payment, $v_total_dgs_payment, $v_total_invoice_payment, $v_total_bcr, $v_total_number_of_recruits, $v_total_number_of_actives, $v_total_number_of_pos, $v_created_by);";
    $rs=$this->_db->execute($query);
    // return $rs;  
  }
  
  // function getDealersOpenSalesInvoices()
  function getDealersOpenSalesInvoices() {
    
    $query="CALL tpiGetDealersOpenSalesInvoices();";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  // function updateSFABCRPMGByIGSOrIBM($v_campaign_code, $v_campaign_month, $v_campaign_year, $v_ibm_id, $v_customer_level_id, $v_customer_id, $v_pmg_type, $v_total_invoice_amount, $v_total_dgs_amount, $v_created_by)
  function updateSFABCRPMGByIGSOrIBM($v_campaign_code, $v_campaign_month, $v_campaign_year, $v_ibm_id, $v_customer_level_id, $v_customer_id, $v_pmg_type, $v_total_invoice_amount, $v_total_dgs_amount, $v_created_by) {
  
    $query="CALL tpiUpdateSFABCRPMGByIGSOrIBM('$v_campaign_code', $v_campaign_month, $v_campaign_year, $v_ibm_id, $v_customer_level_id, $v_customer_id, '$v_pmg_type', $v_total_invoice_amount, $v_total_dgs_amount, $v_created_by);";
    $rs=$this->_db->execute($query);
    // return $rs;
  }
  
  // function getDGSbyIBMAndPMG($v_campaign_month, $v_campaign_year)
  function getDGSbyIBMAndPMG($v_campaign_month, $v_campaign_year) {
    
    $query="CALL tpiGetDGSbyIBMAndPMG($v_campaign_month, $v_campaign_year);";
    $rs=$this->_db->execute($query);
    return $rs;  
  }
  
  function getOfficialReceiptsByCustomerID($v_customer_id) {
    
    $query="CALL tpiGetOfficialReceiptsByCustomerID($v_customer_id);";
    $rs=$this->_db->execute($query);
    return $rs;  
  }            
}

$tpi=new TPI_DSS_Stored_Procedures();