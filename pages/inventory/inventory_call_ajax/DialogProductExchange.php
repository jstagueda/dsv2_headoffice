<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<title></title>
<!--script type="text/javascript" src="js/jquery.js"></script-->


<style type="text/css">

#dialog-overlay {

	/* set it to fill the whil screen */
	width:100%; 
	height:100%;
	
	/* transparency for different browsers */
	filter:alpha(opacity=50); 
	-moz-opacity:0.5; 
	-khtml-opacity: 0.5; 
	opacity: 0.5; 
	background:#000; 

	/* make sure it appear behind the dialog box but above everything else */
	position:absolute; 
	top:0; left:0; 
	z-index:3000; 

	/* hide it by default */
	display:none;
}


#dialog-box {
	
	/* css3 drop shadow */
	-webkit-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
	-moz-box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
	
	/* css3 border radius */
	-moz-border-radius: 5px;
    -webkit-border-radius: 5px;
	
	background:#eee;
	/* styling of the dialog box, i have a fixed dimension for this demo */ 
	width:70%; 
	height: 75%;
	/* make sure it has the highest z-index */
	position:absolute; 
	z-index:5000; 

	/* hide it by default */
	display:none;
}

#dialog-box .dialog-content {
	/* style the content */
	text-align:left;
	height: 90%;
	overflow: auto;
	padding:10px; 
	margin:13px;
	color:#666; 
	font-family:arial;
	font-size:11px; 
}

#dialog-box .dialog-content ul {
	margin:10px 0 10px 20px; 
	padding:0; 
	height:50px;
}



</style>

<script type="text/javascript">



//Popup dialog
function popup(counter) {
	var prodID = jQuery("#prodID"+counter).val();
	var same_color 	 = '';
	var same_style = '';
	var same_subform = '';
	var no_display = 0;
	var same_form = '';
	jQuery.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'getting available items', 'prodID':prodID},
		url: 'pages/inventory/inventory_call_ajax/PECDialog_Call_ajax.php',
		success: function(resp){
		
			if(resp['same_color_response'] == 'success'){
					for(var i = 0; resp['same_color'].length > i; i++){
					//same_product += '<tr><th width="10%" height="20" class="borderBR padl5"><a href ="javascript:void(0);" onclick = "return picK_item('+counter+','+resp['same_product'][i].ProductID+');">'+resp['same_product'][i].prodCode+'</a></th>';
			
						same_color += '<tr><th width="10%" height="20" class="borderBR padl5"><a href= "javascript:void(0);" onclick = "return picK_item('+counter+', '+resp['same_color'][i].ProductID+');">'+resp['same_color'][i].prodCode+'</a></th>';
						same_color += '<th width="50%" height="20" class="borderBR padl5">'+resp['same_color'][i].prodName+'</th>'
						same_color += '<th width="16%" height="20" class="borderBR padl5">'+resp['same_color'][i].SOH+'</th></tr>'
					}
			}else{
					no_display++;
			}
		
			
			if(resp['same_style_response'] == 'success'){
				for(var i = 0; resp['same_style'].length > i; i++){
					//<a href= "javascript:void(0);" onclick = "return picK_item('+counter+', '+resp['same_style'][i].ID+');">'+resp['same_style'][i].prodCode+'</a></th>
					same_style += '<tr><th width="10%" height="20" class="borderBR padl5"><a href= "javascript:void(0);" onclick = "return picK_item('+counter+', '+resp['same_style'][i].ProductID+');">'+resp['same_style'][i].prodCode+'</a></th>';
					same_style += '<th width="50%" height="20" class="borderBR padl5">'+resp['same_style'][i].prodName+'</th>'
					same_style += '<th width="16%" height="20" class="borderBR padl5">'+resp['same_style'][i].SOH+'</th></tr>'
				}
			}else{
					no_display++;
			}
			

			
			// get the screen height and width  
			var maskHeight = jQuery(document).height();  
			var maskWidth = jQuery(window).width();
			
			// calculate the values for center alignment
			//var dialogTop =  (maskHeight/3) - (jQuery('#dialog-box').height());  
			var dialogTop =  "10%";  
			var dialogLeft = (maskWidth/2) - (jQuery('#dialog-box').width()/2); 
			
			// assign values to the overlay and dialog box
			jQuery('#dialog-overlay').css({height:maskHeight, width:maskWidth}).fadeIn();
			jQuery('#dialog-box').css({top:dialogTop, left:dialogLeft}).fadeIn();
			
			// display the message
			//same product
			var combination_productexchange = "";
			if(no_display == 2){
				//<a href= "javascript:void(0);" onclick = "return picK_item('+counter+', '+resp['same_sub_form'][i].ID+');">'+resp['same_sub_form'][i].prodCode+'</a></th>
				if(resp['same_same_sub_form_response'] == 'success'){
					for(var i = 0; resp['same_sub_form'].length > i; i++){
						same_subform += '<tr><td width="10%" height="20" class="borderBR padl5"><a href= "javascript:void(0);" onclick = "return picK_item('+counter+', '+resp['same_sub_form'][i].ProductID+');">'+resp['same_sub_form'][i].prodCode+'</a></td>';
						same_subform += '<td width="50%" height="20" class="borderBR padl5">'+resp['same_sub_form'][i].prodName+'</td>';
						same_subform += '<td width="16%" height="20" class="borderBR padl5">'+resp['same_sub_form'][i].SOH+'</td></tr>';
					}
					combination_productexchange += same_color+""+same_subform; 
				}else{
					//<a href= "javascript:void(0);" onclick = "return picK_item('+counter+', '+resp['same_form'][i].ID+');">'+resp['same_form'][i].prodCode+'</a></th>
					if(resp['same_form_response'] == 'success'){
					for(var i = 0; resp['same_form'].length > i; i++){
						same_form += '<tr><td width="10%" height="20" class="borderBR padl5"><a href= "javascript:void(0);" onclick = "return picK_item('+counter+', '+resp['same_form'][i].ProductID+');">'+resp['same_form'][i].prodCode+'</a></td>';
						same_form += '<td width="50%" height="20" class="borderBR padl5">'+resp['same_form'][i].prodName+'</td>';
						same_form += '<td width="16%" height="20" class="borderBR padl5">'+resp['same_form'][i].SOH+'</td></tr>';
					}
					combination_productexchange += same_color+""+same_form; 
					}else{
						combination_productexchange += same_color+'<tr><th width="10%" height="20" class="borderBR padl5" colspan = "3" align = "center">No Products Available.</th></tr>';
					}
				}
			}else{
				combination_productexchange += same_color+""+same_style; 
			}
			jQuery('#available_product').html(combination_productexchange);
			//jQuery('#available_color').html(same_color);
			//jQuery('#available_style').html(same_style);
		
		
		}
	});
	return false;
			
}

function picK_item(counter, pick_id)
{
	jQuery.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'pick available items', 'prodID':pick_id},
		url: 'pages/inventory/inventory_call_ajax/PECDialog_Call_ajax.php',
		success: function(resp){
				
				var hProdExchangeID 	= resp.ProductID;
				var txtExchangeProdCode = resp.Code;
				var txtExchangeProdName = resp.Name;
				
				jQuery("#hProdExchangeID"+counter).val(hProdExchangeID);
				jQuery("#txtExchangeProdCode"+counter).val(txtExchangeProdCode);
				jQuery("#txtExchangeProdName"+counter).val(txtExchangeProdName);
				jQuery('#dialog-overlay, #dialog-box').fadeOut();
			return false;
		}
	});
	return false;
}

</script>

</head>
<div id="dialog-overlay"></div>
<div id="dialog-box">
	<div class="dialog-content">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
		<tr><td>
			<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">
			       <tr>
			         <td class="tabmin">&nbsp;</td>
			         <td class="tabmin2">
					 <table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
			             <tr>
			               <td class="txtredbold">Available Product</td>
			             </tr>
			         </table></td>
			         <td class="tabmin3">&nbsp;</td>
			       </tr>
			 </table>
			 <table width="100%" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="">
				<tr>
					<th width="10%" height="20" class="padl5">Product Code</th>
					<th width="50%" height="20" class="padl5">Product Name</th>
					<th width="16%" height="20" class="padl5">SOH</th>
				</tr>
			 </table>
			 <div class="scroll_300"> 
			 <table width="100%" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="available_product">
				<!-- AJAX HERE -->
			 </table>
			 </div>
	</tr></td>
	</table>
			<br />
			<input type = "submit" value ="Close" id = "Close" class = "btn"/>
	</div>
</div>
