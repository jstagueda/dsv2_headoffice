/* 
  @package TPI DSS Applicable Promos Application Functions 
  @author John Paul Pineda
  @email paulpineda19@yahoo.com
  @copyright 2013 John Paul Pineda
  @version 1.0 May 24, 2013
  
  @description TPI DSS Applicable Promos Application Functions.  
*/

var tpi_errmsg, tpi_dss_template_directory=(location.protocol+"//"+location.host+(location.pathname).replace('/index.php', ''));

var has_any_kit_been_added=false;

var buyin_product_code='', have_multi_line_promo_entitlements_been_applied=false, have_overlay_promo_entitlements_been_applied=false;

// function get_applicable_promos()
function get_applicable_promos() {
  
  have_overlay_promo_entitlements_been_applied=have_multi_line_promo_entitlements_been_applied=false;
  
  $tpi("#tpi_dss_applied_promos").html('');
  $tpi("#view_applicable_promos").fadeOut('slow');
  
  $tpi.ajax({
  
    url:"tpl/tpi-get-applicable-promos.php",  
    type:"POST", 
    async:false,  
    data: $tpi("#frmCreateSalesOrder").serialize(),  
    success: function(tpi_dss_responseText) {            
    
      $tpi("#tpi_dss_applicable_promos_overlay").height($tpi(document).height());
      $tpi("#tpi_dss_applicable_promos").html(tpi_dss_responseText);
      
      if(tpi_dss_responseText.indexOf('Apply')>=0) $tpi("#view_applicable_promos").fadeIn('slow');                                         
    } 
  });                  
}

// function view_applicable_promos()
function view_applicable_promos() {
  
  $tpi("#tpi_dss_applicable_promos_overlay").show(0);
  
  // jQuery('html, body').animate({ scrollTop: 0 }, 0); // This one liner jQuery snippet will force the browser to go to the top of the page.
  // $tpi("html, body").animate({ scrollTop:0 }, 0); // This one liner jQuery snippet will force the browser to go to the top of the page but with some smooth scrolling.
}

// function apply_applicable_single_line_promo(v_promo_code, v_promo_id_and_type_id, v_promo_entitlement_id, v_item_number, v_buyin_criteria_and_entitlement_details)
function apply_applicable_single_line_promo(v_promo_code, v_promo_id_and_type_id, v_promo_entitlement_id, v_item_number, v_buyin_criteria_and_entitlement_details) {
  
  promo_id_and_type_id=v_promo_id_and_type_id.split('_');
  
  v_promo_id=promo_id_and_type_id[0];
  v_promo_type_id=promo_id_and_type_id[1];
  
  buyin_criteria_and_entitlement_details=v_buyin_criteria_and_entitlement_details.split('_');
  
  var tpi_dss_query_string='promo_id='+v_promo_id+'&promo_entitlement_id='+v_promo_entitlement_id+'&promo_entitlement_product_quantity='+buyin_criteria_and_entitlement_details[4]+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }                    
      
  number_of_unique_products=parseInt(document.getElementById('hcnt').value)-1;                                                 
  
  buyin_product_code=buyin_criteria_and_entitlement_details[0];
  entitlement_type=buyin_criteria_and_entitlement_details[3];
  entitlement_quantity_or_amount=buyin_criteria_and_entitlement_details[4];            
  
  $tpi("#tpi_dss_applied_promos").fadeIn('slow');            
  
  if(entitlement_type=='2' && parseFloat(entitlement_quantity_or_amount)>=0.00) {            
            
    for(x=1;x<=number_of_unique_products;x++) {
      
      if($tpi("#txtProdCode"+x).val()==buyin_product_code) {                                                                          
        
        $tpi("#divPromo"+x).html('<font color="#EC008C">'+v_promo_code+'</font>');
        $tpi("#hPromoID"+x).val(v_promo_id);
        $tpi("#hPromoType"+x).val(v_promo_type_id);
        $tpi("#txtEffectivePrice"+x).val(CommaFormatted(parseFloat(entitlement_quantity_or_amount).toFixed(2)));
        $tpi("#txtTotalPrice"+x).val(CommaFormatted((parseInt($tpi("#txtOrderedQty"+x).val())*parseFloat(entitlement_quantity_or_amount)).toFixed(2)));                                                                  
        
        $tpi("#tpi_dss_applied_promos").fadeOut('slow');
      }           
    }
  }
  
  tpi_dss_connection.onreadystatechange=function() {
    
    if(tpi_dss_connection.readyState==4 && tpi_dss_connection.status==200) {                               
                                  
      compute_sales_order_totals();                               
    } //else alert('Testing Only...');    
  }  
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-single-line-promo-entitlements.php?"+tpi_dss_query_string, true);
  tpi_dss_connection.send(null);     
  
  $tpi(".close-applicable-promos-window").click();    
}

// function apply_applicable_multi_line_promo(v_promo_id, v_entitlement_type, v_entitlement_selection_number)
function apply_applicable_multi_line_promo(v_promo_id, v_entitlement_type, v_entitlement_selection_number) {
  
  number_of_selected_entitlements=0;
  
  applicable_multi_line_promo_entitlement_details_ids='';
  applicable_multi_line_promo_entitlement_details_quantities='';    
  separator_needed=false;
  
  applicable_multi_line_promos_length=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.length');
  
  if(!applicable_multi_line_promos_length) {
    
    if(eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.checked')) {
      
      number_of_selected_entitlements++;
      applicable_multi_line_promo_entitlement_details_ids=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.value');
      applicable_multi_line_promo_entitlement_details_quantities=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity1.value');  
    }    
  } else {
    
    for(x=0;x<applicable_multi_line_promos_length;x++) {
    
      if(eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].checked')) {
        
        number_of_selected_entitlements++;
        applicable_multi_line_promo_entitlement_details_ids+=(separator_needed?('_'+eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].value')):eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].value'));
        applicable_multi_line_promo_entitlement_details_quantities+=(separator_needed?('_'+eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity'+(x+1)+'.value')):eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity'+(x+1)+'.value'));
        
        separator_needed=true;        
      } 
    }  
  }   
  
  if(v_entitlement_type==1) {
    
    if(!have_multi_line_promo_entitlements_been_applied && !have_overlay_promo_entitlements_been_applied) $tpi("#tpi_dss_applied_promos").append('<br /><center><div class="applied-multi-line-promos-header"><b>Applied Promos</b></div></center>');
    
    $tpi("#applicable_multi_line_promos_total_number").val(parseInt($tpi("#applicable_multi_line_promos_total_number").val())-1);        
    if(parseInt($tpi("#applicable_multi_line_promos_total_number").val())==0 && parseInt($tpi("#applicable_overlay_promos_total_number").val())==0) $tpi("#view_applicable_promos").fadeOut('slow');
      
    apply_multi_line_promo_entitlements(v_promo_id, applicable_multi_line_promo_entitlement_details_quantities);
    have_multi_line_promo_entitlements_been_applied=true;    
    $tpi(".promo"+v_promo_id+"-entitlement-row").remove();                                 
    $tpi(".close-applicable-promos-window").click();  
  } else {                            
    
    if(v_entitlement_selection_number==number_of_selected_entitlements) {
      
      if(!have_multi_line_promo_entitlements_been_applied && !have_overlay_promo_entitlements_been_applied) $tpi("#tpi_dss_applied_promos").append('<br /><center><div class="applied-multi-line-promos-header"><b>Applied Promos</b></div></center>');
      
      $tpi("#applicable_multi_line_promos_total_number").val(parseInt($tpi("#applicable_multi_line_promos_total_number").val())-1);        
      if(parseInt($tpi("#applicable_multi_line_promos_total_number").val())==0 && parseInt($tpi("#applicable_overlay_promos_total_number").val())==0) $tpi("#view_applicable_promos").fadeOut('slow');
      
      apply_selected_multi_line_promo_entitlements(v_promo_id, applicable_multi_line_promo_entitlement_details_ids, applicable_multi_line_promo_entitlement_details_quantities);
      have_multi_line_promo_entitlements_been_applied=true;
      $tpi(".promo"+v_promo_id+"-entitlement-row").remove();                                 
      $tpi(".close-applicable-promos-window").click();
    } else alert('Please select at least only '+v_entitlement_selection_number+' entitlement product(s)');        
  }  
}

// function apply_multi_line_promo_entitlements(v_promo_id, v_applicable_multi_line_promo_entitlement_details_quantities)
function apply_multi_line_promo_entitlements(v_promo_id, v_applicable_multi_line_promo_entitlement_details_quantities) {
  
  var tpi_dss_query_string='promo_id='+v_promo_id+'&entitlement_type=1&applicable_multi_line_promo_entitlement_details_quantities='+v_applicable_multi_line_promo_entitlement_details_quantities+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }               
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-multi-line-promo-entitlements.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_applied_promos").append(tpi_dss_connection.responseText);
  $tpi("#tpi_dss_applied_promos").fadeIn('slow');                                  
  compute_sales_order_totals();
}

// function apply_selected_multi_line_promo_entitlements(v_promo_id, v_applicable_multi_line_promo_entitlement_details_ids, v_applicable_multi_line_promo_entitlement_details_quantities)
function apply_selected_multi_line_promo_entitlements(v_promo_id, v_applicable_multi_line_promo_entitlement_details_ids, v_applicable_multi_line_promo_entitlement_details_quantities) {
     
  var tpi_dss_query_string='promo_id='+v_promo_id+'&entitlement_type=2&applicable_multi_line_promo_entitlement_details_ids='+v_applicable_multi_line_promo_entitlement_details_ids+'&applicable_multi_line_promo_entitlement_details_quantities='+v_applicable_multi_line_promo_entitlement_details_quantities+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }          
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-selected-multi-line-promo-entitlements.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_applied_promos").append(tpi_dss_connection.responseText);      
  $tpi("#tpi_dss_applied_promos").fadeIn('slow');                      
  compute_sales_order_totals();            
}

// function apply_applicable_multi_line_discount_promo(v_promo_id, v_entitlement_type, v_entitlement_selection_number)
function apply_applicable_multi_line_discount_promo(v_promo_id, v_entitlement_type, v_entitlement_selection_number) {
  
  number_of_selected_entitlements=0;
  
  applicable_multi_line_promo_entitlement_details_ids='';
  applicable_multi_line_promo_entitlement_details_quantities='';    
  separator_needed=false;
  
  applicable_multi_line_promos_length=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.length');
  
  if(!applicable_multi_line_promos_length) {
    
    if(eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.checked')) {
      
      number_of_selected_entitlements++;
      applicable_multi_line_promo_entitlement_details_ids=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.value');
      applicable_multi_line_promo_entitlement_details_quantities=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity1.value');  
    }    
  } else {
    
    for(x=0;x<applicable_multi_line_promos_length;x++) {
    
      if(eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].checked')) {
        
        number_of_selected_entitlements++;
        applicable_multi_line_promo_entitlement_details_ids+=(separator_needed?('_'+eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].value')):eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].value'));
        applicable_multi_line_promo_entitlement_details_quantities+=(separator_needed?('_'+eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity'+(x+1)+'.value')):eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity'+(x+1)+'.value'));
        
        separator_needed=true;        
      } 
    }  
  }   
  
  if(v_entitlement_type==1) {        
    
    $tpi("#applicable_multi_line_promos_total_number").val(parseInt($tpi("#applicable_multi_line_promos_total_number").val())-1);        
    if(parseInt($tpi("#applicable_multi_line_promos_total_number").val())==0 && parseInt($tpi("#applicable_overlay_promos_total_number").val())==0) $tpi("#view_applicable_promos").fadeOut('slow');
                  
    apply_multi_line_promo_entitlements_as_discount(v_promo_id, applicable_multi_line_promo_entitlement_details_quantities);        
    $tpi(".promo"+v_promo_id+"-entitlement-row").remove();                                 
    $tpi(".close-applicable-promos-window").click();  
  } else {                            
    
    if(v_entitlement_selection_number==number_of_selected_entitlements) {            
      
      $tpi("#applicable_multi_line_promos_total_number").val(parseInt($tpi("#applicable_multi_line_promos_total_number").val())-1);        
      if(parseInt($tpi("#applicable_multi_line_promos_total_number").val())==0 && parseInt($tpi("#applicable_overlay_promos_total_number").val())==0) $tpi("#view_applicable_promos").fadeOut('slow');
      
      apply_selected_multi_line_promo_entitlements_as_discount(v_promo_id, applicable_multi_line_promo_entitlement_details_ids, applicable_multi_line_promo_entitlement_details_quantities);      
      $tpi(".promo"+v_promo_id+"-entitlement-row").remove();                                 
      $tpi(".close-applicable-promos-window").click();
    } else alert('Please select at least only '+v_entitlement_selection_number+' entitlement product(s)');        
  }  
}

// function apply_multi_line_promo_entitlements_as_discount(v_promo_id, v_applicable_multi_line_promo_entitlement_details_quantities)
function apply_multi_line_promo_entitlements_as_discount(v_promo_id, v_applicable_multi_line_promo_entitlement_details_quantities) {
  
  var tpi_dss_query_string='promo_id='+v_promo_id+'&entitlement_type=1&applicable_multi_line_promo_entitlement_details_quantities='+v_applicable_multi_line_promo_entitlement_details_quantities+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }               
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-multi-line-promo-entitlements-as-discount.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_applied_promos").append(tpi_dss_connection.responseText);
  $tpi("#tpi_dss_applied_promos").fadeIn('slow');                                  
  compute_sales_order_totals();
}

// function apply_selected_multi_line_promo_entitlements_as_discount(v_promo_id, v_applicable_multi_line_promo_entitlement_details_ids, v_applicable_multi_line_promo_entitlement_details_quantities)
function apply_selected_multi_line_promo_entitlements_as_discount(v_promo_id, v_applicable_multi_line_promo_entitlement_details_ids, v_applicable_multi_line_promo_entitlement_details_quantities) {
     
  var tpi_dss_query_string='promo_id='+v_promo_id+'&entitlement_type=2&applicable_multi_line_promo_entitlement_details_ids='+v_applicable_multi_line_promo_entitlement_details_ids+'&applicable_multi_line_promo_entitlement_details_quantities='+v_applicable_multi_line_promo_entitlement_details_quantities+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }          
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-selected-multi-line-promo-entitlements-as-discount.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_applied_promos").append(tpi_dss_connection.responseText);      
  $tpi("#tpi_dss_applied_promos").fadeIn('slow');                      
  compute_sales_order_totals();            
}

// function apply_applicable_overlay_promo(v_promo_id, v_entitlement_type, v_entitlement_selection_number)
function apply_applicable_overlay_promo(v_promo_id, v_entitlement_type, v_entitlement_selection_number) {
  
  number_of_selected_entitlements=0;
  
  applicable_overlay_promo_entitlement_details_ids='';
  applicable_overlay_promo_entitlement_details_quantities='';    
  separator_needed=false;
  
  applicable_overlay_promos_length=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.length');
  
  if(!applicable_overlay_promos_length) {
    
    if(eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.checked')) {
      
      number_of_selected_entitlements++;
      applicable_overlay_promo_entitlement_details_ids=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.value');
      applicable_overlay_promo_entitlement_details_quantities=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity1.value');  
    }      
  } else {
    
    for(x=0;x<applicable_overlay_promos_length;x++) {
    
      if(eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].checked')) {
        
        number_of_selected_entitlements++;
        applicable_overlay_promo_entitlement_details_ids+=(separator_needed?('_'+eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].value')):eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].value'));
        applicable_overlay_promo_entitlement_details_quantities+=(separator_needed?('_'+eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity'+(x+1)+'.value')):eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity'+(x+1)+'.value'));
        
        separator_needed=true;        
      } 
    }  
  }   
  
  if(v_entitlement_type==1) {
    
    if(!have_multi_line_promo_entitlements_been_applied && !have_overlay_promo_entitlements_been_applied) $tpi("#tpi_dss_applied_promos").append('<br /><center><div class="applied-overlay-promos-header"><b>Applied Promos</b></div></center>');
    
    $tpi("#applicable_overlay_promos_total_number").val(parseInt($tpi("#applicable_overlay_promos_total_number").val())-1);        
    if(parseInt($tpi("#applicable_multi_line_promos_total_number").val())==0 && parseInt($tpi("#applicable_overlay_promos_total_number").val())==0) $tpi("#view_applicable_promos").fadeOut('slow');          
      
    apply_overlay_promo_entitlements(v_promo_id, applicable_overlay_promo_entitlement_details_quantities);
    have_overlay_promo_entitlements_been_applied=true;    
    $tpi(".promo"+v_promo_id+"-entitlement-row").remove();                                 
    $tpi(".close-applicable-promos-window").click();  
  } else {                            
    
    if(v_entitlement_selection_number==number_of_selected_entitlements) {
      
      if(!have_multi_line_promo_entitlements_been_applied && !have_overlay_promo_entitlements_been_applied) $tpi("#tpi_dss_applied_promos").append('<br /><center><div class="applied-overlay-promos-header"><b>Applied Promos</b></div></center>');
      
      $tpi("#applicable_overlay_promos_total_number").val(parseInt($tpi("#applicable_overlay_promos_total_number").val())-1);        
      if(parseInt($tpi("#applicable_multi_line_promos_total_number").val())==0 && parseInt($tpi("#applicable_overlay_promos_total_number").val())==0) $tpi("#view_applicable_promos").fadeOut('slow');
      
      apply_selected_overlay_promo_entitlements(v_promo_id, applicable_overlay_promo_entitlement_details_ids, applicable_overlay_promo_entitlement_details_quantities);
      have_overlay_promo_entitlements_been_applied=true;
      $tpi(".promo"+v_promo_id+"-entitlement-row").remove();                                 
      $tpi(".close-applicable-promos-window").click();
    } else alert('Please select at least only '+v_entitlement_selection_number+' entitlement product(s)');        
  }  
}

// function apply_overlay_promo_entitlements(v_promo_id, v_applicable_overlay_promo_entitlement_details_quantities)
function apply_overlay_promo_entitlements(v_promo_id, v_applicable_overlay_promo_entitlement_details_quantities) {
  
  var tpi_dss_query_string='promo_id='+v_promo_id+'&entitlement_type=1&applicable_overlay_promo_entitlement_details_quantities='+v_applicable_overlay_promo_entitlement_details_quantities+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }               
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-overlay-promo-entitlements.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_applied_promos").append(tpi_dss_connection.responseText);
  $tpi("#tpi_dss_applied_promos").fadeIn('slow');                                  
  compute_sales_order_totals();
}

// function apply_selected_overlay_promo_entitlements(v_promo_id, v_applicable_overlay_promo_entitlement_details_ids, v_applicable_overlay_promo_entitlement_details_quantities)
function apply_selected_overlay_promo_entitlements(v_promo_id, v_applicable_overlay_promo_entitlement_details_ids, v_applicable_overlay_promo_entitlement_details_quantities) {
     
  var tpi_dss_query_string='promo_id='+v_promo_id+'&entitlement_type=2&applicable_overlay_promo_entitlement_details_ids='+v_applicable_overlay_promo_entitlement_details_ids+'&applicable_overlay_promo_entitlement_details_quantities='+v_applicable_overlay_promo_entitlement_details_quantities+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }          
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-selected-overlay-promo-entitlements.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_applied_promos").append(tpi_dss_connection.responseText);      
  $tpi("#tpi_dss_applied_promos").fadeIn('slow');                      
  compute_sales_order_totals();            
}

// function apply_applicable_overlay_discount_promo(v_promo_id, v_entitlement_type, v_entitlement_selection_number)
function apply_applicable_overlay_discount_promo(v_promo_id, v_entitlement_type, v_entitlement_selection_number) {
  
  number_of_selected_entitlements=0;
  
  applicable_overlay_promo_entitlement_details_ids='';
  applicable_overlay_promo_entitlement_details_quantities='';    
  separator_needed=false;
  
  applicable_overlay_promos_length=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.length');
  
  if(!applicable_overlay_promos_length) {
    
    if(eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.checked')) {
      
      number_of_selected_entitlements++;
      applicable_overlay_promo_entitlement_details_ids=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement.value');
      applicable_overlay_promo_entitlement_details_quantities=eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity1.value');  
    }      
  } else {
    
    for(x=0;x<applicable_overlay_promos_length;x++) {
    
      if(eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].checked')) {
        
        number_of_selected_entitlements++;
        applicable_overlay_promo_entitlement_details_ids+=(separator_needed?('_'+eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].value')):eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement['+x+'].value'));
        applicable_overlay_promo_entitlement_details_quantities+=(separator_needed?('_'+eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity'+(x+1)+'.value')):eval('document.frmCreateSalesOrder.promo'+v_promo_id+'_entitlement_quantity'+(x+1)+'.value'));
        
        separator_needed=true;        
      } 
    }  
  }   
  
  if(v_entitlement_type==1) {        
    
    $tpi("#applicable_overlay_promos_total_number").val(parseInt($tpi("#applicable_overlay_promos_total_number").val())-1);        
    if(parseInt($tpi("#applicable_multi_line_promos_total_number").val())==0 && parseInt($tpi("#applicable_overlay_promos_total_number").val())==0) $tpi("#view_applicable_promos").fadeOut('slow');
      
    apply_overlay_promo_entitlements_as_discount(v_promo_id, applicable_overlay_promo_entitlement_details_quantities);        
    $tpi(".promo"+v_promo_id+"-entitlement-row").remove();                                 
    $tpi(".close-applicable-promos-window").click();  
  } else {                            
    
    if(v_entitlement_selection_number==number_of_selected_entitlements) {            
      
      $tpi("#applicable_overlay_promos_total_number").val(parseInt($tpi("#applicable_overlay_promos_total_number").val())-1);        
      if(parseInt($tpi("#applicable_multi_line_promos_total_number").val())==0 && parseInt($tpi("#applicable_overlay_promos_total_number").val())==0) $tpi("#view_applicable_promos").fadeOut('slow');
      
      apply_selected_overlay_promo_entitlements_as_discount(v_promo_id, applicable_overlay_promo_entitlement_details_ids, applicable_overlay_promo_entitlement_details_quantities);      
      $tpi(".promo"+v_promo_id+"-entitlement-row").remove();                                 
      $tpi(".close-applicable-promos-window").click();
    } else alert('Please select at least only '+v_entitlement_selection_number+' entitlement product(s)');        
  }  
}

// function apply_overlay_promo_entitlements_as_discount(v_promo_id, v_applicable_overlay_promo_entitlement_details_quantities)
function apply_overlay_promo_entitlements_as_discount(v_promo_id, v_applicable_overlay_promo_entitlement_details_quantities) {
  
  var tpi_dss_query_string='promo_id='+v_promo_id+'&entitlement_type=1&applicable_overlay_promo_entitlement_details_quantities='+v_applicable_overlay_promo_entitlement_details_quantities+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }               
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-overlay-promo-entitlements-as-discount.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_applied_promos").append(tpi_dss_connection.responseText);
  $tpi("#tpi_dss_applied_promos").fadeIn('slow');                                  
  compute_sales_order_totals();
}

// function apply_selected_overlay_promo_entitlements_as_discount(v_promo_id, v_applicable_overlay_promo_entitlement_details_ids, v_applicable_overlay_promo_entitlement_details_quantities) 
function apply_selected_overlay_promo_entitlements_as_discount(v_promo_id, v_applicable_overlay_promo_entitlement_details_ids, v_applicable_overlay_promo_entitlement_details_quantities) {
     
  var tpi_dss_query_string='promo_id='+v_promo_id+'&entitlement_type=2&applicable_overlay_promo_entitlement_details_ids='+v_applicable_overlay_promo_entitlement_details_ids+'&applicable_overlay_promo_entitlement_details_quantities='+v_applicable_overlay_promo_entitlement_details_quantities+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }          
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-selected-overlay-promo-entitlements-as-discount.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_applied_promos").append(tpi_dss_connection.responseText);      
  $tpi("#tpi_dss_applied_promos").fadeIn('slow');                      
  compute_sales_order_totals();            
}

// function reapply_previously_applied_promos_entitlements(v_applied_promos_application_functions)
function reapply_previously_applied_promos_entitlements(v_applied_promos_application_functions) {
  
  applied_promos_application_functions=(v_applied_promos_application_functions.replace(/`/g, '\'')).split(';');
  applied_promos_decoded_application_functions='';
  
  for(x=0;x<applied_promos_application_functions.length;x++) {
    
    if(applied_promos_application_functions[x]!='') {
      
      if(applied_promos_application_functions[x].indexOf('multi_line_promo_entitlements')>=0) {
      
        if(applied_promos_application_functions[x].indexOf('multi_line_promo_entitlements_as_discount')<0) {
          
          if(!have_multi_line_promo_entitlements_been_applied && !have_overlay_promo_entitlements_been_applied) $tpi("#tpi_dss_applied_promos").append('<br /><center><div class="applied-multi-line-promos-header"><b>Applied Promos</b></div></center>');  
        }      
        
        $tpi("#applicable_multi_line_promos_total_number").val(parseInt($tpi("#applicable_multi_line_promos_total_number").val())-1);        
        if(parseInt($tpi("#applicable_multi_line_promos_total_number").val())==0 && parseInt($tpi("#applicable_overlay_promos_total_number").val())==0) $tpi("#view_applicable_promos").fadeOut('slow');                        
        
        if(applied_promos_application_functions[x].indexOf('multi_line_promo_entitlements_as_discount')<0)      
        have_multi_line_promo_entitlements_been_applied=true;                   
      } else {
        
        if(applied_promos_application_functions[x].indexOf('overlay_promo_entitlements_as_discount')<0) {
          
          if(!have_multi_line_promo_entitlements_been_applied && !have_overlay_promo_entitlements_been_applied) $tpi("#tpi_dss_applied_promos").append('<br /><center><div class="applied-overlay-promos-header"><b>Applied Promos</b></div></center>');  
        }
            
        $tpi("#applicable_overlay_promos_total_number").val(parseInt($tpi("#applicable_overlay_promos_total_number").val())-1);        
        if(parseInt($tpi("#applicable_multi_line_promos_total_number").val())==0 && parseInt($tpi("#applicable_overlay_promos_total_number").val())==0) $tpi("#view_applicable_promos").fadeOut('slow');
        
        if(applied_promos_application_functions[x].indexOf('overlay_promo_entitlements_as_discount')<0)    
        have_overlay_promo_entitlements_been_applied=true; 
      }
               
      v_promo_id=applied_promos_application_functions[x].split('(');
      v_promo_id=v_promo_id[1].split(',');          
      
      applied_promos_decoded_application_functions+=applied_promos_application_functions[x]+";";
                
      $tpi(".promo"+v_promo_id[0]+"-entitlement-row").remove();                                 
      $tpi(".close-applicable-promos-window").click();        
    }                  
  }
  
  eval(applied_promos_decoded_application_functions);
}

// function apply_promo_entitlement_as_discount(v_promo_code, v_promo_id_and_type_id, v_product_code, v_entitlement_quantity, v_effective_price)
function apply_promo_entitlement_as_discount(v_promo_code, v_promo_id_and_type_id, v_product_code, v_entitlement_quantity, v_effective_price) {
  
  promo_id_and_type_id=v_promo_id_and_type_id.split('_');
  
  v_promo_id=promo_id_and_type_id[0];
  v_promo_type_id=promo_id_and_type_id[1];
  
  product_number=parseInt($tpi("#hcnt").val());  
  number_of_unique_products=product_number-1;
      
  for(x=1;x<=number_of_unique_products;x++) {
  
    if($tpi("#txtProdCode"+x).val()==v_product_code) {                                                                                      
      
      if(v_entitlement_quantity>=parseInt($tpi("#txtOrderedQty"+x).val())) {
        
        $tpi("#divPromo"+x).html('<font color="#EC008C">'+v_promo_code+'</font>');
        $tpi("#hPromoID"+x).val(v_promo_id);
        $tpi("#hPromoType"+x).val(v_promo_type_id);
        
        $tpi("#txtOrderedQty"+x).val(v_entitlement_quantity);
        document.getElementById('txtOrderedQty'+x).disabled=true;
        
        $tpi("#txtEffectivePrice"+x).val(CommaFormatted(parseFloat(v_effective_price).toFixed(2)));
        $tpi("#txtTotalPrice"+x).val(CommaFormatted((v_entitlement_quantity*v_effective_price).toFixed(2)));                                                           
      } else {
        
        number_of_product_ordered=parseInt($tpi("#txtOrderedQty"+x).val())-v_entitlement_quantity;
        
        $tpi("#txtOrderedQty"+x).val(number_of_product_ordered);
        $tpi("#txtTotalPrice"+x).val(CommaFormatted((number_of_product_ordered*parseFloat($tpi("#txtEffectivePrice"+x).val())).toFixed(2)));                                 
        
        $tpi("#txtProdCode"+product_number).val($tpi("#txtProdCode"+x).val());
        $tpi("#hProdID"+product_number).val($tpi("#hProdID"+x).val());
        $tpi("#hKitComponent"+product_number).val($tpi("#hKitComponent"+x).val());
        $tpi("#txtProdDesc"+product_number).val($tpi("#txtProdDesc"+x).val());
        $tpi("#txtPMG"+product_number).val($tpi("#txtPMG"+x).val());
        $tpi("#hPMGID"+product_number).val($tpi("#hPMGID"+x).val());        
        $tpi("#hProductType"+product_number).val($tpi("#hProductType"+x).val());
        $tpi("#txtUnitPrice"+product_number).val($tpi("#txtUnitPrice"+x).val());        
        $tpi("#divPromo"+product_number).html('<font color="#EC008C">'+v_promo_code+'</font>');      
        $tpi("#hPromoID"+product_number).val(v_promo_id);
        $tpi("#hPromoType"+product_number).val(v_promo_type_id);
        $tpi("#hForIncentive"+product_number).val($tpi("#hForIncentive"+x).val());                    
        $tpi("#divSOH"+product_number).html($tpi("#divSOH"+x).html());        
        $tpi("#hSOH"+product_number).val($tpi("#hSOH"+x).val());
        $tpi("#divTransit"+product_number).html($tpi("#divTransit"+x).html());            
        $tpi("#hTransit"+product_number).val($tpi("#hTransit"+x).val());
                
        $tpi("#txtOrderedQty"+product_number).val(v_entitlement_quantity);                
        document.getElementById('txtOrderedQty'+product_number).disabled=true;
                        
        eval('document.frmCreateSalesOrder.hServed'+product_number+'.value=1;');        
        $tpi("#txtEffectivePrice"+product_number).val(CommaFormatted(parseFloat(v_effective_price).toFixed(2)));        
        $tpi("#txtTotalPrice"+product_number).val(CommaFormatted((v_entitlement_quantity*v_effective_price).toFixed(2)));                                        
        
        addRow();
      }                              
    }           
  }  
}

// function notify_user_on_applicable_promo_entitlements(v_element)
function notify_user_on_applicable_promo_entitlements(v_element) {
  
  applicable_promo_entitlements_notification_message="Updating the quantity of item \""+$tpi(("#txtProdDesc"+((v_element.id).substr(13, (v_element.id).length)))).val()+"\" might yield "+((parseInt(v_element.value)>parseInt(v_element.defaultValue))?"more":"lesser")+" applicable promo entitlements based on the set of product buyin requirements met. Do you want to continue ?";
  
  if($tpi("#tpi_dss_applied_promos").html()!='') {        
            
    if(!confirm(applicable_promo_entitlements_notification_message)) v_element.value=v_element.defaultValue;           
  }
}

// function limit_maximum_promo_entitlement_quantity(v_document_element_id, v_maximum_promo_entitlement_quantity)
function limit_maximum_promo_entitlement_quantity(v_document_element_id, v_maximum_promo_entitlement_quantity) {
  
  if(parseInt($tpi("#"+v_document_element_id).val())>v_maximum_promo_entitlement_quantity) $tpi("#"+v_document_element_id).val(v_maximum_promo_entitlement_quantity); 
}

// function compute_sales_order_totals()
function compute_sales_order_totals() {

  var table=document.getElementById('dynamicTable');
	var rowCount=table.rows.length;
	
	var index=eval(rowCount); index--;
	var cnt=eval('document.frmCreateSalesOrder.hcnt');
		
	if(!cnt) {
  
		alert("Product is required.");	
		window.location.reload();
		return false;
	}
  
  cnt.value=index;
	var backOrder=eval('document.frmCreateSalesOrder.hBackOrder');
	if(backOrder.value==1) {
  
		if(cnt.value!=1) ctr=cnt.value-1;
		else ctr=cnt.value;		
	} else ctr=cnt.value-1;
	
	var tmpgross=0;
	var tmptotCFT=0;
	var tmptotNCFT=0;
	var tmptotCPI=0;
	var tmptotQtyCFT=0;
	var tmptotQtyNCFT=0;
	var tmptotQtyCPI=0;
	var tmptotQty=0;
	
	var grossamount=eval('document.frmCreateSalesOrder.txtGrossAmt');
	var totalCFT=eval('document.frmCreateSalesOrder.totCFT');
	var totalNCFT=eval('document.frmCreateSalesOrder.totNCFT');
	var totalCPI=document.getElementById('boldStuff');
	var CSPLessCPI=eval('document.frmCreateSalesOrder.txtCSPLessCPI');
	var BasicDiscount=eval('document.frmCreateSalesOrder.txtBasicDiscount');
	var SalesWVat=eval('document.frmCreateSalesOrder.txtSalesWVat');
	var AmtWOVat=eval('document.frmCreateSalesOrder.txtAmtWOVat'); 
	var VatAmt=eval('document.frmCreateSalesOrder.txtVatAmt'); 
	var NetAmt=eval('document.frmCreateSalesOrder.txtNetAmt');  
	var AddtlDisc=eval('document.frmCreateSalesOrder.txtAddtlDisc');
	var ADPP=eval('document.frmCreateSalesOrder.txtADPP');
	var totCPI=eval('document.frmCreateSalesOrder.totCPI');
	
	var isEmployee=eval('document.frmCreateSalesOrder.isEmployee');
	var totQtyCFT=eval('document.frmCreateSalesOrder.totQtyCFT');
	var totQtyNCFT=eval('document.frmCreateSalesOrder.totQtyNCFT');
	var totQtyCPI=eval('document.frmCreateSalesOrder.totQtyCPI');
	var totQty=eval('document.frmCreateSalesOrder.totQty');
  
	for(var i=1;i<=ctr;i++) {
  
		effective=eval('document.frmCreateSalesOrder.txtTotalPrice'+i);
		qty=eval('document.frmCreateSalesOrder.txtOrderedQty'+i);
    
		var tmpeffective=replaceAll(effective.value, ",", "");
		var tmpqty=qty.value;
		pmg=eval('document.frmCreateSalesOrder.txtPMG'+i);
		
		if(pmg.value=="CFT") {
    
			tmptotCFT=tmptotCFT+parseFloat(tmpeffective);
			tmptotQtyCFT=tmptotQtyCFT+parseInt(tmpqty);
		}
		
		if(pmg.value=="NCFT") {
    
			tmptotNCFT=tmptotNCFT+parseFloat(tmpeffective);
			tmptotQtyNCFT=tmptotQtyNCFT+parseInt(tmpqty);
		}
    
    if(pmg.value=="CPI") {
    
			tmptotCPI=eval(tmptotCPI)+eval(tmpeffective);
			tmptotQtyCPI=eval(tmptotQtyCPI)+eval(tmpqty);			
		}    		
						
		tmpgross=tmpgross+parseFloat(tmpeffective);			
	}    
    
  tmptotQtyCFT+=parseInt($tpi("#applied_promo_entitlements_total_cft_quantity").val());
  tmptotCFT+=parseFloat($tpi("#applied_promo_entitlements_total_cft_amount").val());
  
  tmptotQtyNCFT+=parseInt($tpi("#applied_promo_entitlements_total_ncft_quantity").val());
  tmptotNCFT+=parseFloat($tpi("#applied_promo_entitlements_total_ncft_amount").val());
  
  tmptotQtyCPI+=parseInt($tpi("#applied_promo_entitlements_total_cpi_quantity").val());
  tmptotCPI+=parseFloat($tpi("#applied_promo_entitlements_total_cpi_amount").val());
  
  tmpgross+=(parseFloat($tpi("#applied_promo_entitlements_total_cft_amount").val())+parseFloat($tpi("#applied_promo_entitlements_total_ncft_amount").val())+parseFloat($tpi("#applied_promo_entitlements_total_cpi_amount").val()));      
	
	grossamount.value=tmpgross.toFixed(2);
	
	if(isEmployee.value==0) {
  
		tmpCSPLessCPI=grossamount.value-tmptotCPI;
		tmpBasicDiscount=tmpCSPLessCPI*0.25;
		tmptotCFTless=tmptotCFT*0.75;
		tmptotNCFTless=tmptotNCFT*0.75;
	} else {
  
		tmptotCFTless=tmptotCFT*0.50;
		tmptotNCFTless=tmptotNCFT*0.55;		
		tmpCSPLessCPI=grossamount.value-tmptotCPI;
		tmpBasicDiscount=(tmptotCFT-tmptotCFTless)+(tmptotNCFT-tmptotNCFTless);		
	}
  
  $tpi("#dealer_available_credit").html(CommaFormatted((parseFloat(replaceAll($tpi("#txtAvailableCredit").val(), ",", ""))-parseFloat(grossamount.value)).toFixed(2)));
	
	totalCFT.value=CommaFormatted(tmptotCFTless.toFixed(2));
	totalNCFT.value=CommaFormatted(tmptotNCFTless.toFixed(2));
	totQtyCFT.value=tmptotQtyCFT;
	totQtyNCFT.value=tmptotQtyNCFT;
	totQtyCPI.value=tmptotQtyCPI;
	totQty.value=tmptotQtyCPI+tmptotQtyNCFT+tmptotQtyCFT;
	totalCPI.innerHTML=CommaFormatted(tmptotCPI.toFixed(2));
	totCPI.value=CommaFormatted(tmptotCPI.toFixed(2));
	CSPLessCPI.value=CommaFormatted(tmpCSPLessCPI.toFixed(2));
	BasicDiscount.value=CommaFormatted(tmpBasicDiscount.toFixed(2));
	grossamount.value=CommaFormatted(tmpgross.toFixed(2));
	
	computeAddtlDiscount();	
}


