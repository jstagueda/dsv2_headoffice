<?php
ini_set('display_errors', '1');

require_once('../initialize.php');

$promo_id=trim($_GET['promo_id']);
$promo_entitlement_id=trim($_GET['promo_entitlement_id']);
$promo_entitlement_product_quantity=trim($_GET['promo_entitlement_product_quantity']);

$applied_promo_entitlements_total_cft_quantity=0;
$applied_promo_entitlements_total_cft_amount=0;

$applied_promo_entitlements_total_ncft_quantity=0;
$applied_promo_entitlements_total_ncft_amount=0;

$applied_promo_entitlements_total_cpi_quantity=0;
$applied_promo_entitlements_total_cpi_amount=0;

$rsApplicableSingleLinePromoEntitlements=$tpi->getApplicableSingleLinePromoEntitlement($database, $promo_id, $promo_entitlement_id);
if($rsApplicableSingleLinePromoEntitlements->num_rows) {
  
  while($field=$rsApplicableSingleLinePromoEntitlements->fetch_object()) {
    
    $promo_code=$field->PromoCode;
    
    if($field->PMGID=='1') {
    
      $applied_promo_entitlements_total_cft_quantity+=((int)$promo_entitlement_product_quantity);
      $applied_promo_entitlements_total_cft_amount+=((float)$field->EffectivePrice);  
    } elseif($field->PMGID=='2') {
      
      $applied_promo_entitlements_total_ncft_quantity+=((int)$promo_entitlement_product_quantity);
      $applied_promo_entitlements_total_ncft_amount+=((float)$field->EffectivePrice);
    } elseif($field->PMGID=='3') {
      
      $applied_promo_entitlements_total_cpi_quantity+=((int)$promo_entitlement_product_quantity);
      $applied_promo_entitlements_total_cpi_amount+=((float)$field->EffectivePrice);
    }
    
    
    $applied_single_line_promo_entitlements_html.='
    <tr>
      <td align="center" class="borderBR padl5">
        <input type="hidden" id="applicable_single_line_promo_entitlement'.++$x.'" name="applicable_single_line_promo_entitlements[]" value="'.$field->PromoID.'_'.$field->EntitlementCriteria.'_'.$field->PromoEntitlementID.'_'.$field->ProductID.'_'.$promo_entitlement_product_quantity.'_'.$field->EffectivePrice.'_'.$field->Savings.'_'.$field->PMGID.'" />'.$field->ItemCode.'  
      </td>      
      <td align="center" class="borderBR padl5">'.$field->ItemName.'</td>
      <td align="center" class="borderBR padl5">'.$field->PMGCode.'</td>            
      <td align="center" class="borderBR padl5">'.(($field->EntitlementCriteria=='1' || ($field->EntitlementCriteria=='2' && ((float)$field->EffectivePrice)<=0.00))?$promo_entitlement_product_quantity:$field->Quantity).'</td>
      <td align="center" class="borderBR padl5">'.number_format($field->EffectivePrice, 2, '.', '').'</td>      
    </tr>';
  }
}

echo '<br />';
echo '<center>';
echo '  <input type="hidden" id="applied_promo_entitlements_total_cft_quantity" name="applied_promo_entitlements_total_cft_quantity" value="'.$applied_promo_entitlements_total_cft_quantity.'" />';
echo '  <input type="hidden" id="applied_promo_entitlements_total_cft_amount" name="applied_promo_entitlements_total_cft_amount" value="'.$applied_promo_entitlements_total_cft_amount.'" />';

echo '  <input type="hidden" id="applied_promo_entitlements_total_ncft_quantity" name="applied_promo_entitlements_total_ncft_quantity" value="'.$applied_promo_entitlements_total_ncft_quantity.'" />';
echo '  <input type="hidden" id="applied_promo_entitlements_total_ncft_amount" name="applied_promo_entitlements_total_ncft_amount" value="'.$applied_promo_entitlements_total_ncft_amount.'" />';

echo '  <input type="hidden" id="applied_promo_entitlements_total_cpi_quantity" name="applied_promo_entitlements_total_cpi_quantity" value="'.$applied_promo_entitlements_total_cpi_quantity.'" />';
echo '  <input type="hidden" id="applied_promo_entitlements_total_cpi_amount" name="applied_promo_entitlements_total_cpi_amount" value="'.$applied_promo_entitlements_total_cpi_amount.'" />';

echo '  <table align="center" border="0" cellpadding="1" cellspacing="1" width="72%" class="borderfullgreen">';

echo '    <tr>';
echo '      <td align="center" valign="middle" colspan="5" class="borderBR"><b>Applied Single Line Promo - '.$promo_code.'</b></td>';        
echo '    </tr>';

echo '    <tr>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Item Code</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Item Name</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>PMG</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Quantity</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>CSP</b></td>';        
echo '    </tr>';

echo $applied_single_line_promo_entitlements_html;

echo '  </table>';
echo '</center>';