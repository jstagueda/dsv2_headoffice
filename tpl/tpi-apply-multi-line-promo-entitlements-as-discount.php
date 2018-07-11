<?php
ini_set('display_errors', '1');

require_once('../initialize.php');

$sales_order_session_id=session_id();

$promo_id=trim($_GET['promo_id']);
$entitlement_type=trim($_GET['entitlement_type']);

$tpi->insertAppliedPromoApplicationFunction($sales_order_session_id, $promo_id, base64_encode(serialize('apply_multi_line_promo_entitlements_as_discount('.$promo_id.', \''.$_GET['applicable_multi_line_promo_entitlement_details_quantities'].'\');')));

$entitlement_criteria=2;
$entitlement_criteria_has_been_set=false;

$entitlement_number=0;
$applicable_multi_line_promo_entitlement_details_quantities=((array)explode('_', trim($_GET['applicable_multi_line_promo_entitlement_details_quantities'])));

$rsApplicableMultiLinePromoEntitlements=$tpi->getApplicableMultiLinePromoEntitlement($database, $promo_id, $entitlement_type, 0);
if($rsApplicableMultiLinePromoEntitlements->num_rows) {
  
  $applied_promo_entitlements_javascript='';
  
  while($field=$rsApplicableMultiLinePromoEntitlements->fetch_object()) {        
    
    $promo_code=$field->PromoCode;
    
    if(!$entitlement_criteria_has_been_set) {
      
      $entitlement_criteria=((float)$field->EffectivePrice)<=0?1:2;
      $entitlement_criteria_has_been_set=true;
    }
    
    $entitlement_quantity=$entitlement_criteria==1?((int)$field->Quantity):((int)$applicable_multi_line_promo_entitlement_details_quantities[$entitlement_number++]);
    $entitlement_outstanding_quantity=0;
    
    $rsAvailableProductQuantityForSI=$tpi->getAvailableProductQuantityForSI($sales_order_session_id, $field->ProductID, $entitlement_quantity);
    if($rsAvailableProductQuantityForSI->num_rows) {
      
      while($entitlement=$rsAvailableProductQuantityForSI->fetch_object()) {
        
        $entitlement_quantity=((int)$entitlement->AvailableProductQuantityForSI);
        $entitlement_outstanding_quantity=((int)$entitlement->ProductOutstandingQuantity);    
      }
    }
    
    $applied_promo_entitlements_javascript.='apply_promo_entitlement_as_discount(\''.$promo_code.'\', \''.$field->PromoID.'_2\', '.$field->ItemCode.', '.$entitlement_quantity.', '.$field->EffectivePrice.'); ';                    
  }
}

echo $applied_promo_entitlements_javascript?('<script type="text/javascript">eval("'.$applied_promo_entitlements_javascript.'");</script>'):'';


