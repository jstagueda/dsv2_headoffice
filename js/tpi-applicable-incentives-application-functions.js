/* 
  @package TPI DSS Applicable Incentives Application Functions 
  @author John Paul Pineda
  @email paulpineda19@yahoo.com
  @copyright 2013 John Paul Pineda
  @version 1.0 May 24, 2013
  
  @description TPI DSS Applicable Incentives Application Functions.  
*/

var buyin_product_code='', have_incentive_entitlements_been_applied=false;

// function get_applicable_incentives()
function get_applicable_incentives() {
  
  have_incentive_entitlements_been_applied=false;
  
  $tpi("#tpi_dss_applied_incentives").html('');
  $tpi("#view_applicable_incentives").fadeOut('slow');
  
  $tpi.ajax({
  
    url:"tpl/tpi-get-applicable-incentives.php",  
    type:"POST", 
    async:false,  
    data: $tpi("#frmCreateSalesOrder").serialize(),  
    success: function(tpi_dss_responseText) {            
    
      $tpi("#tpi_dss_applicable_incentives_overlay").height($tpi(document).height());
      $tpi("#tpi_dss_applicable_incentives").html(tpi_dss_responseText);
      
      if(tpi_dss_responseText.indexOf('Apply')>=0) $tpi("#view_applicable_incentives").fadeIn('slow');                                         
    } 
  });                  
}

// function view_applicable_incentives()
function view_applicable_incentives() {
  
  $tpi("#tpi_dss_applicable_incentives_overlay").show(0);
  
  // jQuery('html, body').animate({ scrollTop: 0 }, 0); // This one liner jQuery snippet will force the browser to go to the top of the page.
  // $tpi("html, body").animate({ scrollTop:0 }, 0); // This one liner jQuery snippet will force the browser to go to the top of the page but with some smooth scrolling.
}

// function apply_applicable_incentive(v_incentive_id, v_incentive_buyin_id, v_entitlement_type, v_entitlement_selection_number)
function apply_applicable_incentive(v_incentive_id, v_incentive_buyin_id, v_entitlement_type, v_entitlement_selection_number) {
  
  number_of_selected_entitlements=0;
  
  applicable_incentive_entitlement_details_ids='';
  applicable_incentive_entitlement_details_quantities='';    
  separator_needed=false;
  
  applicable_incentives_length=eval('document.frmCreateSalesOrder.incentive'+v_incentive_buyin_id+'_entitlement.length');
  
  if(!applicable_incentives_length) {
    
    if(eval('document.frmCreateSalesOrder.incentive'+v_incentive_buyin_id+'_entitlement.checked')) {
      
      number_of_selected_entitlements++;
      applicable_incentive_entitlement_details_ids=eval('document.frmCreateSalesOrder.incentive'+v_incentive_buyin_id+'_entitlement.value');
      applicable_incentive_entitlement_details_quantities=eval('document.frmCreateSalesOrder.incentive'+v_incentive_buyin_id+'_entitlement_quantity1.value');  
    }    
  } else {
    
    for(x=0;x<applicable_incentives_length;x++) {
    
      if(eval('document.frmCreateSalesOrder.incentive'+v_incentive_buyin_id+'_entitlement['+x+'].checked')) {
        
        number_of_selected_entitlements++;
        applicable_incentive_entitlement_details_ids+=(separator_needed?('_'+eval('document.frmCreateSalesOrder.incentive'+v_incentive_buyin_id+'_entitlement['+x+'].value')):eval('document.frmCreateSalesOrder.incentive'+v_incentive_buyin_id+'_entitlement['+x+'].value'));
        applicable_incentive_entitlement_details_quantities+=(separator_needed?('_'+eval('document.frmCreateSalesOrder.incentive'+v_incentive_buyin_id+'_entitlement_quantity'+(x+1)+'.value')):eval('document.frmCreateSalesOrder.incentive'+v_incentive_buyin_id+'_entitlement_quantity'+(x+1)+'.value'));
        
        separator_needed=true;        
      } 
    }  
  }   
  
  if(v_entitlement_type==1) {
    
    if(!have_incentive_entitlements_been_applied) $tpi("#tpi_dss_applied_incentives").append('<br /><center><div class="applied-incentives-header"><b>Applied Incentives</b></div></center>');
        
    $tpi("#applicable_incentives_total_number").val(parseInt($tpi("#applicable_incentives_total_number").val())-$tpi(".incentive"+v_incentive_id+"-entitlement-application-link").length);        
    if(parseInt($tpi("#applicable_incentives_total_number").val())==0) $tpi("#view_applicable_incentives").fadeOut('slow');        
      
    apply_incentive_entitlements(v_incentive_buyin_id, applicable_incentive_entitlement_details_quantities);
    have_incentive_entitlements_been_applied=true;    
    $tpi(".incentive"+v_incentive_id).remove();                                 
    $tpi(".close-applicable-incentives-window").click();  
  } else {                            
    
    if(v_entitlement_selection_number==number_of_selected_entitlements) {
      
      if(!have_incentive_entitlements_been_applied) $tpi("#tpi_dss_applied_incentives").append('<br /><center><div class="applied-incentives-header"><b>Applied Incentives</b></div></center>');
      
      $tpi("#applicable_incentives_total_number").val(parseInt($tpi("#applicable_incentives_total_number").val())-$tpi(".incentive"+v_incentive_id+"-entitlement-application-link").length);        
      if(parseInt($tpi("#applicable_incentives_total_number").val())==0) $tpi("#view_applicable_incentives").fadeOut('slow');
      
      apply_selected_incentive_entitlements(v_incentive_buyin_id, applicable_incentive_entitlement_details_ids, applicable_incentive_entitlement_details_quantities);
      have_incentive_entitlements_been_applied=true;
      $tpi(".incentive"+v_incentive_id).remove();                                 
      $tpi(".close-applicable-incentives-window").click();
    } else alert('Please select at least only '+v_entitlement_selection_number+' entitlement product(s)');        
  }  
}

// function apply_incentive_entitlements(v_incentive_buyin_id, v_applicable_incentive_entitlement_details_quantities)
function apply_incentive_entitlements(v_incentive_buyin_id, v_applicable_incentive_entitlement_details_quantities) {
  
  var tpi_dss_query_string='incentive_buyin_id='+v_incentive_buyin_id+'&entitlement_type=1&applicable_incentive_entitlement_details_quantities='+v_applicable_incentive_entitlement_details_quantities+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }               
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-incentive-entitlements.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_applied_incentives").append(tpi_dss_connection.responseText);
  $tpi("#tpi_dss_applied_incentives").fadeIn('slow');                                  
  compute_sales_order_totals();
}

// function apply_selected_incentive_entitlements(v_incentive_buyin_id, v_applicable_incentive_entitlement_details_ids, v_applicable_incentive_entitlement_details_quantities) 
function apply_selected_incentive_entitlements(v_incentive_buyin_id, v_applicable_incentive_entitlement_details_ids, v_applicable_incentive_entitlement_details_quantities) {
     
  var tpi_dss_query_string='incentive_buyin_id='+v_incentive_buyin_id+'&entitlement_type=2&applicable_incentive_entitlement_details_ids='+v_applicable_incentive_entitlement_details_ids+'&applicable_incentive_entitlement_details_quantities='+v_applicable_incentive_entitlement_details_quantities+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }          
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-apply-selected-incentive-entitlements.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_applied_incentives").append(tpi_dss_connection.responseText);      
  $tpi("#tpi_dss_applied_incentives").fadeIn('slow');                      
  compute_sales_order_totals();            
}
