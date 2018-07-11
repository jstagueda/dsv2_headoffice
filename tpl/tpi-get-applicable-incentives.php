<?php
/*
  @package Applicable Incentives' Suggester
  @author John Paul Pineda
  @email paulpineda19@yahoo.com
  @copyright 2013 John Paul Pineda
  @version 1.0 May 25, 2013

  @description Template that handles suggestion of all applicable incentives upon entry of item/product code(s) in the Create Sales Order page.
*/

ini_set('display_errors', '1');

require_once('../initialize.php');

$sales_order_session_id=session_id();

$sales_order_date=date('Y-m-d');
$customer_id=$_POST['hCOA'];

$number_of_unique_products=((int)$_POST['hcnt'])-1;

try {
  
  $db->beginTransaction();
  
  $db->execute("DELETE FROM tmp_applicable_incentives WHERE SessionID='".$sales_order_session_id."';");        
              
  $rsApplicableIncentives=$tpi->getApplicableIncentives($customer_id, $sales_order_date);
  if($rsApplicableIncentives->num_rows) {
    
    while($field=$rsApplicableIncentives->fetch_object()) {                        
      
      $incentive_type_id=$field->IncentiveTypeID;
      
      $rsIncentiveBuyinRequirement=$tpi->getIncentiveBuyinRequirement($field->ID);
      if($rsIncentiveBuyinRequirement->num_rows) {
        
        while($incentive_buyin_requirement=$rsIncentiveBuyinRequirement->fetch_object()) {
          
          if($incentive_type_id!=5) {
            
            $rsHasIncentiveBuyinRequirementBeenMet=$tpi->checkIfIncentiveBuyinRequirementHasBeenMet($sales_order_session_id, $customer_id, $sales_order_date, $incentive_type_id, $field->MechanicsTypeID, $incentive_buyin_requirement->ID, $incentive_buyin_requirement->ProductLevelID, $incentive_buyin_requirement->ProductID, $incentive_buyin_requirement->CriteriaID, $incentive_buyin_requirement->MinQty, $incentive_buyin_requirement->MinAmt, $incentive_buyin_requirement->StartDate, $incentive_buyin_requirement->EndDate, $incentive_buyin_requirement->Type, $incentive_buyin_requirement->Qty, $incentive_buyin_requirement->SpecialCriteria);  
          } else {
                        
          }
          
          if($rsHasIncentiveBuyinRequirementBeenMet->num_rows) {
            
            while($rsIncentiveEntitlementDetailsMaximumQuantity=$rsHasIncentiveBuyinRequirementBeenMet->fetch_object()) $incentive_entitlement_details_maximum_quantity=((int)$rsIncentiveEntitlementDetailsMaximumQuantity->IncentiveEntitlementDetailsMaximumQuantity);
            
            $tpi->insertApplicableIncentiveDetails($sales_order_session_id, $field->ID, $field->Code, $incentive_entitlement_details_maximum_quantity, $field->IncentiveTypeID, $field->MechanicsTypeID, $incentive_buyin_requirement->ID);
          }           
        }
      }            
    }                                                            
  }  
        
  $db->commitTransaction();    
} catch(Exception $e) {
      
  $db->rollbackTransaction();
  $errmsg=$e->getMessage()."\n";
  
  echo $errmsg;              
}



// Application of the Multi Line promos to the product(s)/item(s) being purchased starts here... 
try {
  
  $db->beginTransaction();                                                
      
  $rsApplicableIncentivesBuyinIDs=$tpi->getApplicableIncentivesBuyinIDs($sales_order_session_id);
  if($rsApplicableIncentivesBuyinIDs->num_rows) {        
    
    $applicable_incentives_met_by_buyin_requirements=array();
    
    while($field=$rsApplicableIncentivesBuyinIDs->fetch_object()) {
      
      if(!in_array($field->IncentiveID, $applicable_incentives_met_by_buyin_requirements)) { 
                    
        $applicable_incentives_met_by_buyin_requirements[]=$field->IncentiveID;
        
        $number_of_applicable_incentive_buyin_requirements=$tpi->getNumberOfApplicableIncentiveBuyinRequirements($sales_order_session_id, $field->IncentiveID);
        
        echo '<div id="applicable_incentive'.$field->IncentiveID.'" class="applicable-incentive"><b>'.$field->IncentiveCode.' - '.$field->Description.'</b></div>';
        
        echo '<table id="applicable_incentive_entitlement_details'.$field->IncentiveID.'" align="center" border="1" cellpadding="2" cellspacing="0" width="100%" class="applicable-incentive-entitlement-details">';
        echo '  <tr class="incentive'.$field->IncentiveID.' incentive'.$field->IncentiveBuyinID.'-entitlement-row">';
        echo '    <td align="center" valign="middle"><b>Selection</b></td>';
        echo '    <td align="center" valign="middle"><b>Item Code</b></td>';
        echo '    <td align="center" valign="middle"><b>Entitlement</b></td>';
        echo '    <td align="center" valign="middle"><b>PMG</b></td>';
        echo '    <td align="center" valign="middle"><b>Quantity</b></td>';
        echo '    <td align="center" valign="middle"><b>CSP</b></td>';
        echo '    <td align="center" valign="middle"><b>Type</b></td>';        
        echo '  </tr>';
      }            
      
      $entitlement_number=0;
      
      $entitlement_criteria=2;
      $entitlement_criteria_has_been_set=false;
      
      $rsApplicableIncentiveEntitlements=$tpi->getApplicableIncentiveEntitlementsByBuyinID($sales_order_session_id, $field->IncentiveBuyinID);
      if($rsApplicableIncentiveEntitlements->num_rows) {
        
        while($entitlement=$rsApplicableIncentiveEntitlements->fetch_object()) {
         
          $entitlement_number++;
          
          $entitlement_type=$entitlement->EntitlementType;
          
          if(!$entitlement_criteria_has_been_set) {
            
            $purchase_requirement='Purchase Requirement: '.($entitlement->PurchaseRequirementTypeID=='1'?('Quantity - '.((int)$entitlement->MinimumPurchaseQuantity)):('Amount - '.number_format(((float)$entitlement->MinimumPurchaseAmount), 2, '.', ',')));
            
            $entitlement_criteria=((float)$entitlement->EffectivePrice)<=0?1:2;
            $entitlement_criteria_has_been_set=true;
          }
          
          $entitlement_selection_number=$entitlement_type=='1'?0:$entitlement->EntitlementSelectionNumber;
          
          echo '  <tr class="incentive-entitlement-row incentive'.$field->IncentiveID.' incentive'.$field->IncentiveBuyinID.'-entitlement-row">';
          echo '    <td align="center" valign="middle">
                      <input type="checkbox" id="incentive'.$field->IncentiveBuyinID.'_entitlement'.$entitlement_number.'" name="incentive'.$field->IncentiveBuyinID.'_entitlement"'.($entitlement_type=='1'?' checked="checked" disabled="disabled"':'').' value="'.$entitlement->IncentiveEntitlementDetailsID.'" />
                    </td>';
          echo '    <td align="center" valign="middle">'.$entitlement->ItemCode.'</td>';
          echo '    <td align="center" valign="middle">'.$entitlement->ItemDescription.'</td>';
          echo '    <td align="center" valign="middle">'.$entitlement->PMGCode.'</td>';
          echo '    <td align="center" valign="middle"><input type="text" id="incentive'.$field->IncentiveBuyinID.'_entitlement_quantity'.$entitlement_number.'" name="incentive'.$field->IncentiveBuyinID.'_entitlement_quantity'.$entitlement_number.'"'.($entitlement_criteria==1?' readonly="readonly"':'').' size="2" maxlength="8" value="'.((int)$field->MaximumEntitlementQuantity).'" onkeyup="limit_maximum_promo_entitlement_quantity(this.id, '.((int)$field->MaximumEntitlementQuantity).');" /></td>';
          echo '    <td align="center" valign="middle">'.number_format($entitlement->EffectivePrice, 2, '.', ',').'</td>';
          echo '    <td align="center" valign="middle">'.($entitlement_criteria==1?'Quantity':'Price').'</td>';                        
          echo '  </tr>';  
        }
      }
      
      echo '  <tr bgcolor="silver" class="incentive'.$field->IncentiveID.' incentive'.$field->IncentiveBuyinID.'-entitlement-row">';                              
      echo '    <td align="center" valign="middle" colspan="7"><b>'.$purchase_requirement.' &nbsp;|&nbsp; '.('Entitlement Type: '.($entitlement_type=='1'?'Set':('Selection - '.$entitlement_selection_number))).' &nbsp;|&nbsp; <a href="javascript: apply_applicable_incentive('.$field->IncentiveID.', '.$field->IncentiveBuyinID.', '.$entitlement_type.', '.$entitlement_selection_number.')" title="Apply this incentive" class="txtnavgreenlink incentive'.$field->IncentiveID.'-entitlement-application-link">Add</a></b></td>';          
      echo '  </tr>';
      
      echo '  <tr class="incentive'.$field->IncentiveID.' incentive'.$field->IncentiveBuyinID.'-entitlement-row">';                        
      echo '    <td colspan="7">&nbsp;</td>';    
      echo '  </tr>';
      
      echo (--$number_of_applicable_incentive_buyin_requirements==0)?'</table>':'';        
    }  
  } else {
        
    echo '<tr>';
    echo '  <td align="center" valign="middle" colspan="6"><font color="red" size="2"><b>No applicable incentives found.</b></font></td>';
    echo '</tr>';    
  }                             
            
  $db->commitTransaction();    
} catch(Exception $e) {
      
  $db->rollbackTransaction();
  $errmsg=$e->getMessage()."\n";
  
  echo $errmsg;          
}
// Application of the Multi Line promos to the product(s)/item(s) being purchased ends here...



echo '<input type="hidden" id="applicable_incentives_total_number" name="applicable_incentives_total_number" value="'.((int)$rsApplicableIncentivesBuyinIDs->num_rows).'" />';

/*
$rsPreviouslyAppliedPromosEntitlements=$tpi->getPreviouslyAppliedPromosEntitlements($sales_order_session_id);
if($rsPreviouslyAppliedPromosEntitlements->num_rows) {    
  
  while($field=$rsPreviouslyAppliedPromosEntitlements->fetch_object()) $applied_promos_entitlements_javascript.=unserialize(base64_decode($field->ApplicationFunction));
  
  echo '<input type="hidden" id="applied_promos_application_functions" name="applied_promos_application_functions" value="'.str_replace('\'', '`', $applied_promos_entitlements_javascript).'" />';                      
}
*/

