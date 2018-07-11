<?php
ini_set('display_errors', '1');

require_once('../initialize.php');

$sales_order_session_id=session_id();

$promo_id=trim($_GET['promo_id']);
$entitlement_type=trim($_GET['entitlement_type']);

$tpi->insertAppliedPromoApplicationFunction($sales_order_session_id, $promo_id, base64_encode(serialize('apply_selected_overlay_promo_entitlements('.$promo_id.', \''.$_GET['applicable_overlay_promo_entitlement_details_ids'].'\', \''.$_GET['applicable_overlay_promo_entitlement_details_quantities'].'\');')));

$entitlement_criteria=2;
$entitlement_criteria_has_been_set=false;

$applicable_overlay_promo_entitlement_details_ids=((array)explode('_', trim($_GET['applicable_overlay_promo_entitlement_details_ids'])));
$applicable_overlay_promo_entitlement_details_quantities=((array)explode('_', trim($_GET['applicable_overlay_promo_entitlement_details_quantities'])));

$applied_promo_entitlements_total_cft_quantity=0;
$applied_promo_entitlements_total_cft_amount=0;

$applied_promo_entitlements_total_ncft_quantity=0;
$applied_promo_entitlements_total_ncft_amount=0;

$applied_promo_entitlements_total_cpi_quantity=0;
$applied_promo_entitlements_total_cpi_amount=0;

foreach($applicable_overlay_promo_entitlement_details_ids as $key=>$applicable_overlay_promo_entitlement_details_id) {
  
  $rsApplicableOverlayPromoEntitlement=$tpi->getApplicableOverlayPromoEntitlement($database, $promo_id, $entitlement_type,  $applicable_overlay_promo_entitlement_details_id);
  if($rsApplicableOverlayPromoEntitlement->num_rows) {            
        
    while($field=$rsApplicableOverlayPromoEntitlement->fetch_object()) {            
      
      $promo_code=$field->PromoCode;
      
      if(!$entitlement_criteria_has_been_set) {
      
        $entitlement_criteria=((float)$field->EffectivePrice)<=0?1:2;        
        $entitlement_criteria_has_been_set=true;
      }
      
      $entitlement_quantity=$entitlement_criteria==1?((int)$field->Quantity):((int)$applicable_overlay_promo_entitlement_details_quantities[$key]);
      $entitlement_outstanding_quantity=0;
      
      $rsAvailableProductQuantityForSI=$tpi->getAvailableProductQuantityForSI($sales_order_session_id, $field->ProductID, $entitlement_quantity);
      if($rsAvailableProductQuantityForSI->num_rows) {
        
        while($entitlement=$rsAvailableProductQuantityForSI->fetch_object()) {
          
          $entitlement_quantity=((int)$entitlement->AvailableProductQuantityForSI);
          $entitlement_outstanding_quantity=((int)$entitlement->ProductOutstandingQuantity);    
        }
      }
      
      if($field->PMGID=='1') {
    
        $applied_promo_entitlements_total_cft_quantity+=$entitlement_quantity;
        $applied_promo_entitlements_total_cft_amount+=($entitlement_quantity*((float)$field->EffectivePrice));  
      } elseif($field->PMGID=='2') {
        
        $applied_promo_entitlements_total_ncft_quantity+=$entitlement_quantity;
        $applied_promo_entitlements_total_ncft_amount+=($entitlement_quantity*((float)$field->EffectivePrice));
      } elseif($field->PMGID=='3') {
        
        $applied_promo_entitlements_total_cpi_quantity+=$entitlement_quantity;
        $applied_promo_entitlements_total_cpi_amount+=($entitlement_quantity*((float)$field->EffectivePrice));
      }
      
      $applied_selected_overlay_promo_entitlements_html.='
      <tr>
        <td align="center" class="borderBR padl5">
          <input type="hidden" id="applicable_overlay_promo_entitlement'.++$x.'" name="applicable_overlay_promo_entitlements[]" value="'.$field->PromoID.'_'.$field->EntitlementType.'_'.$field->PromoEntitlementDetailsID.'_'.$field->ProductID.'_'.$entitlement_quantity.'_'.$entitlement_outstanding_quantity.'_'.$field->EffectivePrice.'_'.$field->Savings.'_'.$field->PMGID.'" />'.$field->ItemCode.'
        </td>              
        <td align="center" class="borderBR padl5">'.$field->ItemName.'</td>
        <td align="center" class="borderBR padl5">'.$field->PMGCode.'</td>
        <td align="center" class="borderBR padl5">'.$entitlement_quantity.'</td>
        <td align="center" class="borderBR padl5">'.$entitlement_outstanding_quantity.'</td>      
        <td align="center" class="borderBR padl5">'.number_format($field->EffectivePrice, 2, '.', ',').'</td>
        <td align="center" class="borderBR padl5">'.number_format(($entitlement_quantity*$field->EffectivePrice), 2, '.', ',').'</td>              
      </tr>';        
    }               
  }
}

echo '<script type="text/javascript">';
echo 'eval("document.getElementById(\'applied_promo_entitlements_total_cft_quantity\').value=parseInt(document.getElementById(\'applied_promo_entitlements_total_cft_quantity\').value)+'.$applied_promo_entitlements_total_cft_quantity.";\");";
echo 'eval("document.getElementById(\'applied_promo_entitlements_total_cft_amount\').value=parseInt(document.getElementById(\'applied_promo_entitlements_total_cft_amount\').value)+'.$applied_promo_entitlements_total_cft_amount.";\");";

echo 'eval("document.getElementById(\'applied_promo_entitlements_total_ncft_quantity\').value=parseInt(document.getElementById(\'applied_promo_entitlements_total_ncft_quantity\').value)+'.$applied_promo_entitlements_total_ncft_quantity.";\");";
echo 'eval("document.getElementById(\'applied_promo_entitlements_total_ncft_amount\').value=parseInt(document.getElementById(\'applied_promo_entitlements_total_ncft_amount\').value)+'.$applied_promo_entitlements_total_ncft_amount.";\");";

echo 'eval("document.getElementById(\'applied_promo_entitlements_total_cpi_quantity\').value=parseInt(document.getElementById(\'applied_promo_entitlements_total_cpi_quantity\').value)+'.$applied_promo_entitlements_total_cpi_quantity.";\");";
echo 'eval("document.getElementById(\'applied_promo_entitlements_total_cpi_amount\').value=parseInt(document.getElementById(\'applied_promo_entitlements_total_cpi_amount\').value)+'.$applied_promo_entitlements_total_cpi_amount.";\");";
echo '</script>';

echo '<center>';
echo '  <div id="promo'.$promo_id.'" title="Click to view promo entitlement(s)" class="applied-promo">'.$promo_code.'</div>';
echo '  <table align="center" border="0" cellpadding="1" cellspacing="1" width="72%" class="borderfullgreen promo'.$promo_id.' applied-promo-entitlements">';      

echo '    <tr>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Item Code</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Item Name</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>PMG</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Quantity</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Back Order</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>CSP</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Total Price</b></td>';        
echo '    </tr>';

echo $applied_selected_overlay_promo_entitlements_html;

echo '  </table>';
echo '</center>';