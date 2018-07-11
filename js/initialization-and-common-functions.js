if(document.getElementById) {
 
  document.write('<style type="text/css">\n');
  document.write('.submenu{display: none;}\n');
  document.write('.submenu2{display: ;}\n');
  document.write('</style>\n');
}

// My "Testing Function ^_'_^"
function my_test() {
  
  if(document.getElementById('has_any_kit_been_added_via_ajax')) alert('It exists!');
  else alert('It didn\'t exist');
}

// function SwitchMenu(obj)
function SwitchMenu(obj) {

  if(document.getElementById) {
    
    var el=document.getElementById(obj);
    var ar=document.getElementById("masterdiv").getElementsByTagName("span");
     
    if(el.style.display!="block") {
        
      for(var i=0;i<ar.length;i++) {
            
        if(ar[i].className=="submenu") ar[i].style.display="none";
      } el.style.display="block";
    } else el.style.display="none";    
  }
}

// function hideit(elem)
function hideit(elem) {

  document.getElementById(elem).style.display='none';
}

// function unhideit(elem)
function unhideit(elem) {

  document.getElementById(elem).style.display='block';
}

// function load_areas_under_me(area_id, area_level_id)
function load_areas_under_me(area_id, area_level_id) {
  
  var areas_under_me='';
  var tpi_dss_query_string='area_id='+area_id+'&area_level_id='+area_level_id+"&tpi_dss_sId="+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }    
  
  tpi_dss_connection.onreadystatechange=function() {
    
    if(tpi_dss_connection.readyState==4 && tpi_dss_connection.status==200) {
     
      areas_under_me=tpi_dss_connection.responseText;
      
      if(area_level_id==4) {
      
        $tpi("#cboTown").html(areas_under_me);
        
        first_area_under_me=parseInt(document.getElementById('cboTown').value);
        
        if(!isNaN(first_area_under_me))                
        load_areas_under_me(first_area_under_me, 5);
        else 
        $tpi("#cboBarangay").html('Selected Town/City doesn\'t have a Barangay');
      } else if(area_level_id==5) {
        
        $tpi("#cboBarangay").html(areas_under_me);
        
        first_area_under_me=parseInt(document.getElementById('cboBarangay').value);
        
        if(!isNaN(first_area_under_me))                
        load_areas_under_me(first_area_under_me, 6);
      } else if(area_level_id==6) document.getElementById('txtZipCode').value=areas_under_me;            
    }    
  }  
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-dss-load-areas-under-me.php?"+tpi_dss_query_string, true);
  tpi_dss_connection.send(null);
}

// function load_new_tpi_window(page_to_load, page_name, page_width, page_height, scroll)
function load_new_tpi_window(page_to_load, page_name, page_width, page_height, scroll) {
  
  var winl=(screen.width-page_width)/2, wint=(screen.height-page_height)/2;
  	
	window_properties="width="+page_width+", height="+page_height+", left="+winl+", top="+wint+", scrollbars="+scroll+", resizable, menubar=yes, toolbar=no";
  
	tpi_window=window.open(page_to_load, page_name, window_properties);
  
	// if(parseInt(navigator.appVersion)>=4) tpi_window.focus(); 
}

/*
 * @author: jdymosco
 * @date: May 23, 2013
 * @description: Script that will load jquery if the page still not loading it.
 * @update: June 17, 2013, Changed the script used instead of window.onload since it's giving conflict on other modules that's using
 *          same script which is not allowed..
 */
 /*
window.setTimeout('WindowDotLoad()',100);
function WindowDotLoad(){
    var script_session = document.createElement('script');
    script_session.type = "text/javascript";
    script_session.src = "js/jquery.UserSessioner.js";
    
    var script = document.createElement('script');
    script.type = "text/javascript";
    script.src = "js/jquery-1.8.3.min.js";                

    if(typeof jQuery == 'undefined'){
        document.getElementsByTagName('head')[0].appendChild(script);        
        document.getElementsByTagName('head')[0].appendChild(script_session);
    }else{
        document.getElementsByTagName('body')[0].appendChild(script);        
        document.getElementsByTagName('body')[0].appendChild(script_session);
    }            
    
    var global_user_interfaces_script = document.createElement('script');
    global_user_interfaces_script.type = "text/javascript";
    global_user_interfaces_script.src = "js/global-user-interfaces.js";
    
    document.getElementsByTagName('head')[0].appendChild(global_user_interfaces_script);
}
*/
