<?php
ini_set('display_errors', '1');

require_once('../initialize.php');

$sales_order_session_id=session_id();

$incentive_buyin_id=trim($_GET['incentive_buyin_id']);
$entitlement_type=trim($_GET['entitlement_type']);

# $tpi->insertAppliedIncentiveApplicationFunction($sales_order_session_id, $incentive_buyin_id, base64_encode(serialize('apply_selected_incentive_entitlements('.$incentive_buyin_id.', \''.$_GET['applicable_incentive_entitlement_details_ids'].'\', \''.$_GET['applicable_incentive_entitlement_details_quantities'].'\');')));

$entitlement_criteria=2;
$entitlement_criteria_has_been_set=false;

$applicable_incentive_entitlement_details_ids=((array)explode('_', trim($_GET['applicable_incentive_entitlement_details_ids'])));
$applicable_incentive_entitlement_details_quantities=((array)explode('_', trim($_GET['applicable_incentive_entitlement_details_quantities'])));

$applied_incentive_entitlements_total_cft_quantity=0;
$applied_incentive_entitlements_total_cft_amount=0;

$applied_incentive_entitlements_total_ncft_quantity=0;
$applied_incentive_entitlements_total_ncft_amount=0;

$applied_incentive_entitlements_total_cpi_quantity=0;
$applied_incentive_entitlements_total_cpi_amount=0;

foreach($applicable_incentive_entitlement_details_ids as $key=>$applicable_incentive_entitlement_details_id) {
  
  $rsApplicableIncentiveEntitlement=$tpi->getApplicableIncentiveEntitlement($incentive_buyin_id, $entitlement_type,  $applicable_incentive_entitlement_details_id);
  if($rsApplicableIncentiveEntitlement->num_rows) {            
        
    while($field=$rsApplicableIncentiveEntitlement->fetch_object()) {            
      
      $incentive_code=$field->IncentiveCode;
      
      if(!$entitlement_criteria_has_been_set) {
      
        $entitlement_criteria=((float)$field->EffectivePrice)<=0?1:2;        
        $entitlement_criteria_has_been_set=true;
      }
      
      $entitlement_quantity=$entitlement_criteria==1?((int)$field->Quantity):((int)$applicable_incentive_entitlement_details_quantities[$key]);
      $entitlement_outstanding_quantity=0;
      
      $rsAvailableProductQuantityForSI=$tpi->getAvailableProductQuantityForSI($sales_order_session_id, $field->ProductID, $entitlement_quantity);
      if($rsAvailableProductQuantityForSI->num_rows) {
        
        while($entitlement=$rsAvailableProductQuantityForSI->fetch_object()) {
          
          $entitlement_quantity=((int)$entitlement->AvailableProductQuantityForSI);
          $entitlement_outstanding_quantity=((int)$entitlement->ProductOutstandingQuantity);    
        }
      }
      
      if($field->PMGID=='1') {
    
        $applied_incentive_entitlements_total_cft_quantity+=$entitlement_quantity;
        $applied_incentive_entitlements_total_cft_amount+=($entitlement_quantity*((float)$field->EffectivePrice));  
      } elseif($field->PMGID=='2') {
        
        $applied_incentive_entitlements_total_ncft_quantity+=$entitlement_quantity;
        $applied_incentive_entitlements_total_ncft_amount+=($entitlement_quantity*((float)$field->EffectivePrice));
      } elseif($field->PMGID=='3') {
        
        $applied_incentive_entitlements_total_cpi_quantity+=$entitlement_quantity;
        $applied_incentive_entitlements_total_cpi_amount+=($entitlement_quantity*((float)$field->EffectivePrice));
      }
      
      $applied_selected_incentive_entitlements_html.='
      <tr>
        <td align="center" class="borderBR padl5">
          <input type="hidden" id="applicable_incentive_entitlement'.++$x.'" name="applicable_incentive_entitlements[]" value="'.$field->IncentiveID.'_'.$field->IncentiveBuyinID.'_'.$field->EntitlementType.'_'.$field->IncentiveEntitlementDetailsID.'_'.$field->ProductID.'_'.$entitlement_quantity.'_'.$entitlement_outstanding_quantity.'_'.$field->EffectivePrice.'_'.$field->Savings.'_'.$field->PMGID.'" />'.$field->ItemCode.'
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
echo 'eval("document.getElementById(\'applied_promo_entitlements_total_cft_quantity\').value=parseInt(document.getElementById(\'applied_promo_entitlements_total_cft_quantity\').value)+'.$applied_incentive_entitlements_total_cft_quantity.";\");";
echo 'eval("document.getElementById(\'applied_promo_entitlements_total_cft_amount\').value=parseFloat(document.getElementById(\'applied_promo_entitlements_total_cft_amount\').value)+'.$applied_incentive_entitlements_total_cft_amount.";\");";

echo 'eval("document.getElementById(\'applied_promo_entitlements_total_ncft_quantity\').value=parseInt(document.getElementById(\'applied_promo_entitlements_total_ncft_quantity\').value)+'.$applied_incentive_entitlements_total_ncft_quantity.";\");";
echo 'eval("document.getElementById(\'applied_promo_entitlements_total_ncft_amount\').value=parseFloat(document.getElementById(\'applied_promo_entitlements_total_ncft_amount\').value)+'.$applied_incentive_entitlements_total_ncft_amount.";\");";

echo 'eval("document.getElementById(\'applied_promo_entitlements_total_cpi_quantity\').value=parseInt(document.getElementById(\'applied_promo_entitlements_total_cpi_quantity\').value)+'.$applied_incentive_entitlements_total_cpi_quantity.";\");";
echo 'eval("document.getElementById(\'applied_promo_entitlements_total_cpi_amount\').value=parseFloat(document.getElementById(\'applied_promo_entitlements_total_cpi_amount\').value)+'.$applied_incentive_entitlements_total_cpi_amount.";\");";
echo '</script>';

echo '<center>';
echo '  <div id="incentive'.$incentive_buyin_id.'" title="Click to view incentive entitlement(s)" class="applied-incentive">'.$incentive_code.'</div>';
echo '  <table align="center" border="0" cellpadding="1" cellspacing="1" width="72%" class="borderfullgreen incentive'.$incentive_buyin_id.' applied-incentive-entitlements">';      

echo '    <tr>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Item Code</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Item Name</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>PMG</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Quantity</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Back Order</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>CSP</b></td>';
echo '      <td align="center" valign="middle" class="borderBR padl5"><b>Total Price</b></td>';        
echo '    </tr>';

echo $applied_selected_incentive_entitlements_html;

echo '  </table>';
echo '</center>';