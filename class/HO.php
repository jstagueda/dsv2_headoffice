<?php
/* 
 * @package HO
 * @author John Paul Pineda
 * @email paulpineda19@yahoo.com
 * @copyright 2013 John Paul Pineda
 * @version 1.0 November 18, 2013
 * 
 * @description This class is used for ? 
 *          
 */

// Set the PHP error reporting to "E_ALL & ~E_NOTICE & ~E_STRICT" so as it won't display E_NOTICE and E_STRICT related errors.
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

require_once('config.php');
require_once('dbconnection.php');

class HO {

  public $_db, $_sp, $_session, $site_root, $directory_separator, $template_directory;
  public $campaign_code, $two_digit_date_year, $datemonth, $dateyear, $current_date;
  public $decoded_level_criterias; 
        
  // function __construct()
  function __construct() {
    
    global $db, $sp;
    
    $this->_db = & $db;
    $this->_sp = & $sp;
    
    $this->_session = & $_SESSION;
    $this->site_root = str_replace('class', '', dirname(__FILE__)); // This site root is temporary and will be modified in the future.    
    $this->directory_separator = DIRECTORY_SEPARATOR;
    
    $this->template_directory = $this->site_root.'pages/sfm/tmpl'; // To be modified
    
    $this->campaign_code = strtoupper(date('My'));
    $this->two_digit_date_year = date('y');
    	
    $this->datemonth = date('m');
    $this->dateyear = date('Y');
    $this->current_date = date('Y-m-d');
    
    $this->decoded_level_criterias = '';
  }
  
  function getTemplate($v_kpi_id, $v_decoded_level_criterias) {        
    
    $template_filename = 'TEMPLATE_FILENAME';    
        
    $query = "SELECT template_filename TemplateFilename FROM sfm_kpi_standards WHERE codeID=" . $v_kpi_id . ";";
    $rs = $this->_db->execute($query);
          
    if($rs->num_rows) while($field = $rs->fetch_object()) $template_filename = $this->template_directory . $this->directory_separator . $field->TemplateFilename . '.php';
    
    if(file_exists($template_filename)) {
      
      ob_start();
        $this->decoded_level_criterias = ((object)$v_decoded_level_criterias);
                                
        require_once($template_filename);  
        $template_contents = ob_get_contents();
      ob_end_clean();  
    } else $template_contents = '<div class="">The selected page\'s template doesn\'t exist. Please contact your system administrator.</div>';                                 
        
    return $template_contents;        
  }
  
  function getBranches() {
    
    $query = "SELECT * FROM branch WHERE StatusID = 1;";
    $rs = $this->_db->execute($query);
    return $rs;
  }
  
  function getMovementTypeByID($v_movement_type_id) {
    
    $query = "SELECT * FROM movementtype WHERE ID = $v_movement_type_id;";
    
    $rs = $this->_db->execute($query);
    if($rs->num_rows) return $rs->fetch_object();
  }
  
  function getParentKitProducts() {
    
    $query = "SELECT * FROM product WHERE ProductTypeID = 4;";
    $rs = $this->_db->execute($query);
    return $rs;
  }
  
  function consolidateISATransactionByDate($v_date) {
    
    $numeric_date_format = date('ymd', strtotime($v_date));
        
    $rsParentKitProducts = $this->getParentKitProducts();
    if($rsParentKitProducts->num_rows) {            
      
      $isa = $this->getMovementTypeByID(17);
      
      $isa_gl_accounts = implode(',', array((string)$isa->CreditGLAccount, (string)$isa->CreditCostCenter, (string)$isa->DebitGLAccount, (string)$isa->DebitCostCenter));
      
      while($field = $rsParentKitProducts->fetch_object()) {
        
        $product_id = $field->ID;
        
        $rsBranches = $this->getBranches();
        if($rsBranches->num_rows) {
          
          while($branch = $rsBranches->fetch_object()) {
            
            // Parent Kit
            $query = "SELECT COUNT(sid.ProductID) NumberOfParentKitProducts FROM salesinvoice si INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID WHERE DATE(si.TxnDate) = '$v_date' AND SPLIT_STR(sid.HOGeneralID, '-', 2) = {$branch->ID} AND sid.ProductID = $product_id AND sid.IsParentKit = 1;";
            $rsNumberOfParentKitProducts = $this->_db->execute($query);
            if($rsNumberOfParentKitProducts->num_rows) $number_of_parent_kit_products = $rsNumberOfParentKitProducts->fetch_object()->NumberOfParentKitProducts;
            
            if($number_of_parent_kit_products > 0) {
              
              $query = "INSERT IGNORE INTO isa(BranchID, Date, DocumentNo, MovementType, ProductID, ProductCode, Qty, UOM, GLAccount, MovementTypeCode) VALUES({$branch->ID}, '$v_date', '{$branch->ID}{$numeric_date_format}', '{$isa->Type}', $product_id, '', $number_of_parent_kit_products, 1, '$isa_gl_accounts', '{$isa->Code}');";
              
              try {
              
                $this->_db->beginTransaction();                
                $this->_db->execute($query);                
                $this->_db->commitTransaction();
              } catch(Exception $e) {
                
                $this->_db->rollbackTransaction();	
                # $errmsg = $e->getMessage() . "\n";
              }                            
            }
            
            // Component Kit
            $query = "SELECT sid.ProductID, SUM(sid.Qty) NumberOfKitComponentProducts FROM salesinvoice si INNER JOIN salesinvoicedetails sid ON sid.SalesInvoiceID = si.ID WHERE DATE(si.TxnDate) = '$v_date' AND SPLIT_STR(sid.HOGeneralID, '-', 2) = {$branch->ID} AND sid.IsParentKitComponent = 1 GROUP BY sid.ProductID;";
            $rsKitComponentProducts = $this->_db->execute($query);
            if($rsKitComponentProducts->num_rows) {
              
              while($kit_component_product = $rsKitComponentProducts->fetch_object()) {
                
                $query = "INSERT IGNORE INTO isa(BranchID, Date, DocumentNo, MovementType, ProductID, ProductCode, Qty, UOM, GLAccount, MovementTypeCode) VALUES({$branch->ID}, '$v_date', '{$branch->ID}{$numeric_date_format}', '{$isa->Type}', {$kit_component_product->ProductID}, '', -{$kit_component_product->NumberOfKitComponentProducts}, 1, '$isa_gl_accounts', '{$isa->Code}');";
                
                try {
              
                  $this->_db->beginTransaction();                
                  $this->_db->execute($query);                
                  $this->_db->commitTransaction();
                } catch(Exception $e) {

                  $this->_db->rollbackTransaction();	
                  # $errmsg = $e->getMessage() . "\n";
                }
              }
            }                                    
          }
        }
      }
    }
  }
  
  function consolidateISATransactionByCampaignYear($v_from_campaign_year, $v_to_campaign_year) {
    
    $campaign_year_last_date_timestamp = strtotime(($v_to_campaign_year . '-12-31'));
    $campaign_year_first_date = ($v_from_campaign_year . '-01-01');
        
    while(strtotime($campaign_year_first_date) <= $campaign_year_last_date_timestamp) {            
      
      $previous_day = date('D', strtotime($campaign_year_first_date));      
      $rsCheckHoliday = $this->_sp->spCheckIfHoliday($this->_db, $campaign_year_first_date);            
      
      if($previous_day != 'Sun' && $rsCheckHoliday->num_rows <= 0) $this->consolidateISATransactionByDate($campaign_year_first_date);                              
      $campaign_year_first_date = date('Y-m-d', strtotime($campaign_year_first_date . '+1 day'));            
    }          
  }
  
  function consolidateISATransactionByDateToDate($v_campaign_year, $v_campaign_month, $v_campaign_day) {
    
    $current_date_timestamp = strtotime($this->current_date);    
    $previous_date = ($v_campaign_year . '-' . $v_campaign_month . '-' . $v_campaign_day);
        
    while(strtotime($previous_date) <= $current_date_timestamp) {            
      
      $previous_day = date('D', strtotime($previous_date));      
      $rsCheckHoliday = $this->_sp->spCheckIfHoliday($this->_db, $previous_date);            
      
      if($previous_day != 'Sun' && $rsCheckHoliday->num_rows <= 0) $this->consolidateISATransactionByDate($previous_date);                              
      $previous_date = date('Y-m-d', strtotime($previous_date . '+1 day'));            
    }          
  }
}

$ho = new HO();