<?php
/*
  @package Applicable Promos' Suggester
  @author John Paul Pineda
  @email paulpineda19@yahoo.com
  @copyright 2013 John Paul Pineda
  @version 1.0 May 22, 2013

  @description Template that handles suggestion of all applicable promos upon entry of item/product code(s) in the Create Sales Order page.
*/

ini_set('display_errors', '1');

require_once('../initialize.php');

$sales_order_session_id=session_id();

$sales_order_date=date('Y-m-d');
$customer_id=$_POST['hCOA'];

$number_of_unique_products=((int)$_POST['hcnt'])-1;

try {
  
  $db->beginTransaction();
  
  $tpi->refreshProductsListBeingOrdered();
  
  $db->execute("DELETE FROM tmp_productslist WHERE SessionID='".$sales_order_session_id."';");
  $db->execute("DELETE FROM tmp_cumulative_productslist WHERE SessionID='".$sales_order_session_id."';");
  
  // Insertion of the applicable Single Line promos based on the product(s)/item(s) being purchased starts here...
  $db->execute("DELETE FROM tmp_applicable_single_line_promos WHERE SessionID='".$sales_order_session_id."';");
  
  for($x=1;$x<=$number_of_unique_products;$x++) {
    
    $product_code=$_POST['txtProdCode'.$x];
    $number_of_product_ordered=$_POST['txtOrderedQty'.$x];
    $product_price=((float)trim(str_replace(',', '', $_POST['txtEffectivePrice'.$x])));    
    
    $tpi->insertProductsListBeingOrdered($database, $sales_order_session_id, $sales_order_date, $product_code, $product_price, $number_of_product_ordered);
    
    $tpi->insertApplicableSingleLinePromos($database, $sales_order_session_id, $sales_order_date, $product_code, $product_price, $number_of_product_ordered);        
  } $tpi->insertProductsListOrdered($sales_order_session_id, $customer_id);
  // Insertion of the applicable Single Line promos based on the product(s)/item(s) being purchased end here...
  
  // Insertion of the applicable Multi Line promos based on the product(s)/item(s) being/have been purchased starts here...  
  $db->execute("DELETE FROM tmp_applicable_multi_line_promos WHERE SessionID='".$sales_order_session_id."';");     
    
  $rsApplicableMultiLinePromos=$tpi->getApplicableMultiLinePromos($database, $sales_order_date);
  if($rsApplicableMultiLinePromos->num_rows) {
    
    while($field=$rsApplicableMultiLinePromos->fetch_object()) $tpi->insertApplicableMultiLinePromoDetails($database, $sales_order_session_id, $sales_order_date, $customer_id, $field->ID, $field->Code, $field->StartDate, $field->EndDate);                                                            
  }
  // Insertion of the applicable Multi Line promos based on the product(s)/item(s) being/have been purchased ends here...
  
  // Insertion of the applicable Overlay promos based on the product(s)/item(s) being/have been purchased starts here...
  $db->execute("DELETE FROM tmp_applicable_overlay_promos WHERE SessionID='".$sales_order_session_id."';");    
  
  $rsApplicableOverlayPromos=$tpi->getApplicableOverlayPromos($sales_order_date);
  if($rsApplicableOverlayPromos->num_rows) {
    
    while($field=$rsApplicableOverlayPromos->fetch_object()) {
      
      $overlay_type=$field->OverlayType;
      
      $rsPromoBuyinRequirement=$tpi->getPromoBuyinRequirement($field->ID);
      if($rsPromoBuyinRequirement->num_rows) {
        
        $has_promo_buyin_requirement_been_met=array();
        $promo_entitlement_details_maximum_quantity=array();
        
        while($promo_buyin_requirement=$rsPromoBuyinRequirement->fetch_object()) {
          
          $purchase_requirement_type=$promo_buyin_requirement->PurchaseRequirementType;
                                                                          
          $rsHasPromoBuyinRequirementBeenMet=$tpi->checkIfPromoBuyinRequirementHasBeenMet($sales_order_session_id, $customer_id, $promo_buyin_requirement->PurchaseRequirementType, $promo_buyin_requirement->ProductLevelID, $promo_buyin_requirement->ProductID, $promo_buyin_requirement->Type, $promo_buyin_requirement->MinQty, $promo_buyin_requirement->MinAmt, $promo_buyin_requirement->StartDate, $promo_buyin_requirement->EndDate);
          
          if($rsHasPromoBuyinRequirementBeenMet->num_rows) {
            
            $has_promo_buyin_requirement_been_met[]=1;
            
            while($rsPromoEntitlementDetailsMaximumQuantity=$rsHasPromoBuyinRequirementBeenMet->fetch_object()) $promo_entitlement_details_maximum_quantity[]=((int)$rsPromoEntitlementDetailsMaximumQuantity->PromoEntitlementDetailsMaximumQuantity);
          } else $has_promo_buyin_requirement_been_met[]=0;                                                                                           
        }
                                              
        if($overlay_type=='2' && in_array(1, $has_promo_buyin_requirement_been_met)) {
          
          $promo_entitlement_details_maximum_quantity=array_sum($promo_entitlement_details_maximum_quantity);
          $tpi->insertApplicableOverlayPromoDetails($sales_order_session_id, $field->ID, $field->Code, $promo_entitlement_details_maximum_quantity, $purchase_requirement_type, $overlay_type);                  
        } elseif($overlay_type=='1' && !in_array(0, $has_promo_buyin_requirement_been_met)) {
          
          $promo_entitlement_details_maximum_quantity=min($promo_entitlement_details_maximum_quantity);
          $tpi->insertApplicableOverlayPromoDetails($sales_order_session_id, $field->ID, $field->Code, $promo_entitlement_details_maximum_quantity, $purchase_requirement_type, $overlay_type);
        }                                            
      }            
    }                                                            
  }
  // Insertion of the applicable Overlay promos based on the product(s)/item(s) being/have been purchased ends here...
    
  $db->commitTransaction();    
} catch(Exception $e) {
      
  $db->rollbackTransaction();
  $errmsg=$e->getMessage()."\n";          
}



echo '  <input type="hidden" id="applied_promo_entitlements_total_cft_quantity" name="applied_promo_entitlements_total_cft_quantity" value="0" />';
echo '  <input type="hidden" id="applied_promo_entitlements_total_cft_amount" name="applied_promo_entitlements_total_cft_amount" value="0" />';

echo '  <input type="hidden" id="applied_promo_entitlements_total_ncft_quantity" name="applied_promo_entitlements_total_ncft_quantity" value="0" />';
echo '  <input type="hidden" id="applied_promo_entitlements_total_ncft_amount" name="applied_promo_entitlements_total_ncft_amount" value="0" />';

echo '  <input type="hidden" id="applied_promo_entitlements_total_cpi_quantity" name="applied_promo_entitlements_total_cpi_quantity" value="0" />';
echo '  <input type="hidden" id="applied_promo_entitlements_total_cpi_amount" name="applied_promo_entitlements_total_cpi_amount" value="0" />';

echo '<table align="center" border="1" width="100%">';
echo '  <tr>';
echo '    <td align="center" class="close-applicable-promos-window">Close</td>';
echo '  </tr>';
echo '</table>';

// Application of the Single Line promos to the product(s)/item(s) being purchased starts here...
try {
  
  $db->beginTransaction();        
  
  /*   
  echo '<table align="center" border="1" cellpadding="2" cellspacing="0" width="100%">';
  echo '  <tr>';
  echo '    <td align="center" valign="middle" colspan="7"><font size="2"><b>Applicable Single Line Promos</b></font></td>';
  echo '  </tr>';
  echo '  <tr>';
  echo '    <td align="center" valign="middle"><b>Promo Code</b></td>';
  echo '    <td align="center" valign="middle"><b>Item Code</b></td>';
  echo '    <td align="center" valign="middle"><b>Entitlement</b></td>';
  echo '    <td align="center" valign="middle"><b>PMG</b></td>';
  echo '    <td align="center" valign="middle"><b>Quantity / CSP</b></td>';  
  echo '    <td align="center" valign="middle"><b>Type</b></td>';    
  # echo '    <td align="center" valign="middle"><b>Max Savings</b></td>';
  # echo '    <td align="center" valign="middle"><b>Start Effectiviy Date</b></td>';
  # echo '    <td align="center" valign="middle"><b>End Effectiviy Date</b></td>';
  echo '    <td align="center" valign="middle"><b>Action</b></td>';
  echo '  </tr>';      
  */
  
  $rsApplicableSingleLinePromos=$tpi->getApplicableSingleLinePromos($database, $sales_order_session_id);
  if($rsApplicableSingleLinePromos->num_rows) {
    
    $x=0;
    $applied_promo_entitlements_javascript='';           
    
    $db->execute("DELETE FROM overlapped_applicable_promos_entitlements WHERE SessionID='".$sales_order_session_id."';");
            
    while($field=$rsApplicableSingleLinePromos->fetch_object()) {                        
      
      $buyin_criteria_and_entitlement_details=explode('_', $field->BuyinCriteriaAndEntitlementDetails);
      $entitlement_type=$buyin_criteria_and_entitlement_details[3]=='1'?'Quantity':'Price';
      $entitlement=$entitlement_type=='Quantity'?$buyin_criteria_and_entitlement_details[4]:number_format($buyin_criteria_and_entitlement_details[4], 2, '.', ',');
      
      $rsOverlappedApplicablePromoEntitlement=$tpi->getOverlappedApplicablePromoEntitlement($sales_order_session_id, $field->PromoID, $field->ProductID);
      if($rsOverlappedApplicablePromoEntitlement->num_rows && false) {
        
        echo '<tr class="single-line-entitlement">';
        echo '  <td align="center" valign="middle"><b>'.$field->PromoCode.'</b></td>';
        echo '  <td align="center" valign="middle">'.$field->ProductCode.'</td>';      
        echo '  <td align="center" valign="middle">'.$field->ProductName.'</td>';
        echo '  <td align="center" valign="middle">'.$field->PMGCode.'</td>';
        echo '  <td align="center" valign="middle">'.$entitlement.'</td>';
        echo '  <td align="center" valign="middle">'.$entitlement_type.'</td>';      
        # echo '  <td align="center" valign="middle">'.$field->TotalEntitlementAmount.'</td>';          
        # echo '  <td align="center" valign="middle">'.date('F j, Y', strtotime($field->StartDate)).'</td>';
        # echo '  <td align="center" valign="middle">'.date('F j, Y', strtotime($field->EndDate)).'</td>';      
        echo '  <td align="center" valign="middle"><b><a href="javascript: apply_applicable_single_line_promo('.$field->PromoID.', '.$field->PromoEntitlementID.', '.++$x.', \''.$field->BuyinCriteriaAndEntitlementDetails.'\');" title="Apply this promo" class="txtnavgreenlink">Apply</a></b></td>';
        echo '</tr>';  
      } else $applied_promo_entitlements_javascript.='apply_applicable_single_line_promo(\''.$field->PromoCode.'\', \''.$field->PromoID.'_1\', '.$field->PromoEntitlementID.', '.++$x.', \''.$field->BuyinCriteriaAndEntitlementDetails.'\'); ';                                                         
    }         
  } else {
    
    /*
    echo '  <tr>';
    echo '    <td align="center" valign="middle" colspan="7"><font color="red" size="2">No applicable Single Line promos found.</font></td>';
    echo '  </tr>';
    */
  }    
  
  # echo '</table>';            
  echo $applied_promo_entitlements_javascript?('<script type="text/javascript">eval("'.$applied_promo_entitlements_javascript.'");</script>'):'';  
  
  $db->commitTransaction();    
} catch(Exception $e) {
      
  $db->rollbackTransaction();
  $errmsg=$e->getMessage()."\n";          
}
// Application of the Single Line promos to the product(s)/item(s) being purchased ends here...



// Application of the Multi Line promos to the product(s)/item(s) being purchased starts here... 
try {
  
  $db->beginTransaction();    
    
  echo '<table align="center" border="1" cellpadding="2" cellspacing="0" width="100%">';
  echo '  <tr>';
  echo '    <td align="center" valign="middle" colspan="7"><font size="2"><b>Applicable Promos</b></font></td>';
  echo '  </tr>';
  echo '  <tr>';
  echo '    <td align="center" valign="middle"><b>Selection</b></td>';
  echo '    <td align="center" valign="middle"><b>Item Code</b></td>';
  echo '    <td align="center" valign="middle"><b>Entitlement</b></td>';
  echo '    <td align="center" valign="middle"><b>PMG</b></td>';
  echo '    <td align="center" valign="middle"><b>Quantity</b></td>';
  echo '    <td align="center" valign="middle"><b>CSP</b></td>';
  echo '    <td align="center" valign="middle"><b>Type</b></td>';        
  echo '  </tr>';
    
  $rsApplicableMultiLinePromos=$tpi->getApplicableMultiLinePromosSortByMaxSavings($database, $sales_order_session_id);
  if($rsApplicableMultiLinePromos->num_rows) {        
    
    while($field=$rsApplicableMultiLinePromos->fetch_object()) {            
      
      $entitlement_number=0;
      
      $entitlement_criteria=2;
      $entitlement_criteria_has_been_set=false;
      
      $rsApplicableMultiLinePromoEntitlements=$tpi->getApplicableMultiLinePromoEntitlements($database, $field->PromoID);
      if($rsApplicableMultiLinePromoEntitlements->num_rows) {
        
        while($entitlement=$rsApplicableMultiLinePromoEntitlements->fetch_object()) {
         
          $entitlement_number++;
          
          $entitlement_type=$entitlement->EntitlementType;
          
          if(!$entitlement_criteria_has_been_set) {
      
            $entitlement_criteria=((float)$entitlement->EffectivePrice)<=0?1:2;
            $entitlement_criteria_has_been_set=true;
          }
          
          $entitlement_selection_number=$entitlement_type=='1'?0:$entitlement->EntitlementSelectionNumber;
          
          echo '  <tr class="promo-entitlement-row promo'.$field->PromoID.'-entitlement-row">';
          echo '    <td align="center" valign="middle">
                      <input type="checkbox" id="promo'.$field->PromoID.'_entitlement'.$entitlement_number.'" name="promo'.$field->PromoID.'_entitlement"'.($entitlement_type=='1'?' checked="checked" disabled="disabled"':'').' value="'.$entitlement->PromoEntitlementDetailsID.'" />
                    </td>';
          echo '    <td align="center" valign="middle">'.$entitlement->ItemCode.'</td>';
          echo '    <td align="center" valign="middle">'.$entitlement->ItemDescription.'</td>';
          echo '    <td align="center" valign="middle">'.$entitlement->PMGCode.'</td>';
          echo '    <td align="center" valign="middle"><input type="text" id="promo'.$field->PromoID.'_entitlement_quantity'.$entitlement_number.'" name="promo'.$field->PromoID.'_entitlement_quantity'.$entitlement_number.'"'.($entitlement_criteria==1?' readonly="readonly"':'').' size="2" maxlength="8" value="'.((int)(false?$entitlement->Quantity:$field->MaximumEntitlementQuantity)).'" onkeyup="limit_maximum_promo_entitlement_quantity(this.id, '.((int)(false?$entitlement->Quantity:$field->MaximumEntitlementQuantity)).');" /></td>';
          echo '    <td align="center" valign="middle">'.number_format($entitlement->EffectivePrice, 2, '.', ',').'</td>';
          echo '    <td align="center" valign="middle">'.($entitlement_criteria==1?'Quantity':'Price').'</td>';                        
          echo '  </tr>';  
        }
      }
      
      echo '  <tr bgcolor="silver" class="promo'.$field->PromoID.'-entitlement-row">';                              
      echo '    <td align="center" valign="middle" colspan="7"><b>Promo Code: '.$field->PromoCode.' &nbsp;|&nbsp; '.('Entitlement Type: '.($entitlement_type=='1'?'Set':('Selection - '.$entitlement_selection_number))).' &nbsp;|&nbsp; <a href="javascript: apply_applicable_multi_line_promo('.$field->PromoID.', '.$entitlement_type.', '.$entitlement_selection_number.')" title="Apply this promo" class="txtnavgreenlink">Add</a> &nbsp;|&nbsp; <a href="javascript: apply_applicable_multi_line_discount_promo('.$field->PromoID.', '.$entitlement_type.', '.$entitlement_selection_number.');" title="Apply this promo" class="txtnavgreenlink">Apply as Discount</a></b></td>';
          
      echo '  </tr>';
      echo '  <tr class="promo'.$field->PromoID.'-entitlement-row">';                        
      echo '    <td colspan="7">&nbsp;</td>';    
      echo '  </tr>';        
    }  
  } else {
        
    echo '<tr>';
    echo '  <td align="center" valign="middle" colspan="6"><font color="red" size="2"><b>No applicable Multi Line promos found.</b></font></td>';
    echo '</tr>';    
  }          
            
  $db->commitTransaction();    
} catch(Exception $e) {
      
  $db->rollbackTransaction();
  $errmsg=$e->getMessage()."\n";          
}
// Application of the Multi Line promos to the product(s)/item(s) being purchased ends here...



// Application of the Overlay promos to the product(s)/item(s) being purchased starts here... 
try {
  
  $db->beginTransaction();    
    
  echo '<table align="center" border="1" cellpadding="2" cellspacing="0" width="100%">';
  echo '  <tr>';
  echo '    <td align="center" valign="middle" colspan="7">&nbsp;</td>';
  echo '  </tr>';
  echo '  <tr>';
  echo '    <td align="center" valign="middle"><b>Selection</b></td>';
  echo '    <td align="center" valign="middle"><b>Item Code</b></td>';
  echo '    <td align="center" valign="middle"><b>Entitlement</b></td>';
  echo '    <td align="center" valign="middle"><b>PMG</b></td>';
  echo '    <td align="center" valign="middle"><b>Quantity</b></td>';
  echo '    <td align="center" valign="middle"><b>CSP</b></td>';
  echo '    <td align="center" valign="middle"><b>Type</b></td>';        
  echo '  </tr>';
    
  $rsApplicableOverlayPromos=$tpi->getApplicableOverlayPromosSortByMaxSavings($sales_order_session_id);
  if($rsApplicableOverlayPromos->num_rows) {        
    
    while($field=$rsApplicableOverlayPromos->fetch_object()) {            
      
      $entitlement_number=0;
      
      $entitlement_criteria=2;
      $entitlement_criteria_has_been_set=false;
      
      $rsApplicableOverlayPromoEntitlements=$tpi->getApplicableOverlayPromoEntitlements($field->PromoID);
      if($rsApplicableOverlayPromoEntitlements->num_rows) {
        
        while($entitlement=$rsApplicableOverlayPromoEntitlements->fetch_object()) {
         
          $entitlement_number++;
          
          $entitlement_type=$entitlement->EntitlementType;
          
          if(!$entitlement_criteria_has_been_set) {
      
            $entitlement_criteria=((float)$entitlement->EffectivePrice)<=0?1:2;
            $entitlement_criteria_has_been_set=true;
          }
          
          $entitlement_selection_number=$entitlement_type=='1'?0:$entitlement->EntitlementSelectionNumber;
          
          echo '  <tr class="promo-entitlement-row promo'.$field->PromoID.'-entitlement-row">';
          echo '    <td align="center" valign="middle">
                      <input type="checkbox" id="promo'.$field->PromoID.'_entitlement'.$entitlement_number.'" name="promo'.$field->PromoID.'_entitlement"'.($entitlement_type=='1'?' checked="checked" disabled="disabled"':'').' value="'.$entitlement->PromoEntitlementDetailsID.'" />
                    </td>';
          echo '    <td align="center" valign="middle">'.$entitlement->ItemCode.'</td>';
          echo '    <td align="center" valign="middle">'.$entitlement->ItemDescription.'</td>';
          echo '    <td align="center" valign="middle">'.$entitlement->PMGCode.'</td>';
          echo '    <td align="center" valign="middle"><input type="text" id="promo'.$field->PromoID.'_entitlement_quantity'.$entitlement_number.'" name="promo'.$field->PromoID.'_entitlement_quantity'.$entitlement_number.'"'.($entitlement_criteria==1?' readonly="readonly"':'').' size="2" maxlength="8" value="'.((int)(false?$entitlement->Quantity:$field->MaximumEntitlementQuantity)).'" onkeyup="limit_maximum_promo_entitlement_quantity(this.id, '.((int)(false?$entitlement->Quantity:$field->MaximumEntitlementQuantity)).');" /></td>';
          echo '    <td align="center" valign="middle">'.number_format($entitlement->EffectivePrice, 2, '.', ',').'</td>';
          echo '    <td align="center" valign="middle">'.($entitlement_criteria==1?'Quantity':'Price').'</td>';                        
          echo '  </tr>';  
        }
      }
      
      echo '  <tr bgcolor="silver" class="promo'.$field->PromoID.'-entitlement-row">';                              
      echo '    <td align="center" valign="middle" colspan="7"><b>Promo Code: '.$field->PromoCode.' &nbsp;|&nbsp; '.('Entitlement Type: '.($entitlement_type=='1'?'Set':('Selection - '.$entitlement_selection_number))).' &nbsp;|&nbsp; <a href="javascript: apply_applicable_overlay_promo('.$field->PromoID.', '.$entitlement_type.', '.$entitlement_selection_number.');" title="Apply this promo" class="txtnavgreenlink">Add</a> &nbsp;|&nbsp; <a href="javascript: apply_applicable_overlay_discount_promo('.$field->PromoID.', '.$entitlement_type.', '.$entitlement_selection_number.');" title="Apply this promo" class="txtnavgreenlink">Apply as Discount</a></b></td>';
          
      echo '  </tr>';
      echo '  <tr class="promo'.$field->PromoID.'-entitlement-row">';                        
      echo '    <td colspan="7">&nbsp;</td>';    
      echo '  </tr>';        
    }  
  } else {
        
    echo '<tr>';
    echo '  <td align="center" valign="middle" colspan="6"><font color="red" size="2"><b>No applicable Overlay promos found.</b></font></td>';
    echo '</tr>';    
  }          
            
  $db->commitTransaction();    
} catch(Exception $e) {
      
  $db->rollbackTransaction();
  $errmsg=$e->getMessage()."\n";          
}
// Application of the Overlay promos to the product(s)/item(s) being purchased ends here...



echo '<table align="center" border="1" width="100%">';
echo '  <tr>';
echo '    <td align="center" class="close-applicable-promos-window">Close</td>';
echo '  </tr>';
echo '</table>';

echo '<input type="hidden" id="applicable_multi_line_promos_total_number" name="applicable_multi_line_promos_total_number" value="'.((int)$rsApplicableMultiLinePromos->num_rows).'" />';

echo '<input type="hidden" id="applicable_overlay_promos_total_number" name="applicable_overlay_promos_total_number" value="'.((int)$rsApplicableOverlayPromos->num_rows).'" />';

$rsPreviouslyAppliedPromosEntitlements=$tpi->getPreviouslyAppliedPromosEntitlements($sales_order_session_id);
if($rsPreviouslyAppliedPromosEntitlements->num_rows) {    
  
  while($field=$rsPreviouslyAppliedPromosEntitlements->fetch_object()) $applied_promos_entitlements_javascript.=unserialize(base64_decode($field->ApplicationFunction));
  
  echo '<input type="hidden" id="applied_promos_application_functions" name="applied_promos_application_functions" value="'.str_replace('\'', '`', $applied_promos_entitlements_javascript).'" />';                      
}


