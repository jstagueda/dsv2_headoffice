<link rel= "stylesheet" type= "text/css" href= "css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "css/calpopup.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script>
$(document).ready(function(){
	fetching_selection();
	$("#as_of_date").datepicker();
	$("#chkAll").click(function() {
		                          
		$(".checkboxes").attr('checked', this.checked);
	});
	$("#Generatelist").click(function(){
		
		var show_error ="",count_err = 0;
		
		var bool = "";
		
		if ($("#level").val() == 0){
			show_error +="*Level Required.\n";
			count_err++;
		}
		
		if($("#as_of_date").val() == ""){
			show_error +="*As of date Required.\n";
			count_err++;
		}
		if($("#IBM_Code_To").val() != ""){
				if($("#IBM_Code_From").val() == ""){
					show_error +="*Required IBM Code From.\n";
					count_err++;
				}
		}
		
		if($("#past_due_to").val() != ""){
				if($("#past_due_from").val() == ""){
					show_error +="*Required Past Due From.\n";
					count_err++;
				}
		}
		//check validation..
		if(count_err > 0){
			alert(show_error);
			return false;
		}else{
			var html1 = ""; 
			/*
			data: {'request': 'Generate List', 'level':$("#level").val(), 'as_of_date': $("#as_of_date").val(),  'dealer': $("#search_dealer").val()},
			*/
			$.ajax({
				type: 'post',
				dataType: 'json',
				data: { 'request': 'Generate List', 'level':$("#level").val(), 'as_of_date': $("#as_of_date").val(),  'dealer': $("#search_dealer").val(), 'dealer_to' : $('search_dealer_to').val(),
					    'IBM_Code_From' : $("#IBM_Code_From").val(), "IBM_Code_To" : $("#IBM_Code_To").val(),
					    'gsu_type': $("#gsu_type").val(), "past_due_from" : $("#past_due_from").val(), "past_due_to" : $("#past_due_to").val()},
				url: 'pages/sfm/param_ajax_calls/demand_letter_call_ajax.php',
				success: function(resp){
					if(resp['results'].fetching_data =='Success'){
					var GSU = "";
						for(var i = 0; resp['fetch_data'].length > i; i++){
						
						if (resp['fetch_data'][i].GSUTYPE == 1){
							GSU = "NO";
						}else{
							GSU = "YES";
						}
							html1 += "<tr>";	
							html1 += "<td width='3%'  class='borderBR padl5''  align='center' >";
							html1 += "<input type='checkbox' class = 'checkboxes' name='chkInclude[]' id ='chkInclude"+i+"' value='"+resp['fetch_data'][i].xID+","+resp['fetch_data'][i].salesID+"'>";
							html1 += "<input type='hidden' name='salesID[]' id ='salesID"+i+"' value='"+resp['fetch_data'][i].salesID+"'>";
							html1 += "</td>";
							html1 += "<td width='7%'  class='borderBR padl5'  align='left'>"+resp['fetch_data'][i].Code+"</td>";
							html1 += "<td width='10%'  class='borderBR padl5'  align='left'>"+resp['fetch_data'][i].DealerName+"</td>";
							html1 += "<td width='10%'   class='borderBR padl5'  align='left'>"+resp['fetch_data'][i].GSU_Name+"</td>";		
							html1 += "<td width='10%'   class='borderBR padl5'  align='left'>"+resp['fetch_data'][i].ibm+"</td>";		
							html1 += "<td width='10%'   class='borderBR padl5'  align='left'>"+resp['fetch_data'][i].PastDue+" <input type = 'hidden' value = '"+resp['fetch_data'][i].PastDue+"' name = 'past_due[]'></td>";		
							html1 += "<td width='10%'   class='borderBR padl5'  align='left'>"+resp['fetch_data'][i].AppliedAmount+"</td>";		
							//html1 += "<td width='10%'   class='borderBR padl5'  align='left'>"+GSU+"</td>";		
							html1 += "</tr>";	
						}
						
						var total_record = resp['fetch_data'].length;
						$("#dynamic_table").html(html1);
						$("#total_numrows").val(total_record);
						$("#print").removeAttr("disabled","disabled");
					}else{
						alert('no record(s) result.');
						$("#dynamic_table").html("");
						$("#print").attr("disabled","disabled");
					}
				}
			});
		}
		
		return false;
		
	})
})


function fetching_selection()
{
var dynamic_selection = "";
var gsu_dynamic_selection = "";
	$.ajax({
		dataType: 'json',
		type:'post',
		data: {'request': 'fetching_selection'},
		url:'pages/sfm/param_ajax_calls/welcome_letter_callajax.php',
		success: function(resp){

			for(var i = 0; resp['select'].length > i; i++){
				dynamic_selection += "<option value = '"+resp['select'][i].LevelID+"'>"+resp['select'][i].Code+"</option>";
			}
					gsu_dynamic_selection += "<option value = '0'>ALL</option>";
			for(var j = 0; resp['select_gsu'].length > j; j++){
					gsu_dynamic_selection += "<option value = '"+resp['select_gsu'][j].GSUID+"'>"+resp['select_gsu'][j].GSU_NAME+"</option>";
			}
			
			$("#gsu_type").html(gsu_dynamic_selection);
			
			$("#level").html(dynamic_selection);
			
			$("#Generatelist").removeAttr("disabled","disabled");
		}
	})
	
	
}

function dealer_search()
{
	
	//alert($("#search_dealer").val());
	
	$('#search_dealer').autocomplete({
		source:'pages/sfm/param_ajax_calls/demand_letter_call_ajax.php',
			select: function( event, ui ) {
				$( "#search_dealer").val(ui.item.Code);
			
			return false;
		}
	}).data( "uiAutocomplete" )._renderItem = function( ul, item ) {
			 return $( "<li style = 'list-style-type:circle;'></li>" )
			.data( "item.autocomplete", item )
			.append( "<a><strong>" + item.Code + "</strong> - " + item.DealerName + "</a>" )
			.appendTo( ul );
	}
}


function validate_numbers(event){
//$("#past_due_from").keydown(function(event) {
        // Allow: backspace, delete, tab, escape, and enter
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || 
             // Allow: Ctrl+A
            (event.keyCode == 65 && event.ctrlKey === true) || 
             // Allow: home, end, left, right
            (event.keyCode >= 35 && event.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
                event.preventDefault(); 
            }   
        }
//});



}
</script>
<form method="post" action="pages/sfm/demandleterprint.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
		<?PHP include("nav_dealer.php"); ?> <br>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Correspondence</span></td>
		</tr>
		</table>
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
				<table width="95%" border="0" align="center" cellpadding="0"
					cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Demand Letter</td>
						<td>&nbsp;</td>
					</tr>
				</table>	
		</table>
		<br>
		<!--Begin form-->
		<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr><td>
		<!--Begin Search Tab-->
		
				<table width="70%"  border="0" align="left" cellpadding="0" cellspacing="1" class="bordersolo">
			<tr>
				<td>
					<table width="99%"  border="0" align="center" cellpadding="0" cellspacing="1">
					<tr>
			  			
			  			<td width="10%" align="right">&nbsp;</td>
			  			<td width="20%" align="right">&nbsp;</td>
			  			<td width="40%" align="right">&nbsp;</td>
			  			<td width="30%"></td>
					</tr>
					<tr>
			  			
			  			<td width="18%" align="right" class="padr5"><strong>As of date:</strong></td>
			  			<td width="20%" align="left"><input type="text" name="as_of_date" id = "as_of_date"  class="txtfield" value="<?php echo date("m/d/Y"); ?>"></td>
			  	
					</tr>
					<tr>
					<td>&nbsp;</td></tr>
					<tr>
			  			
			  			<td width="15%" align="right" class="padr5"><strong>Level:</strong></td>
			  			<td width="20%" align="left"><select class="txtfield"  id = "level" name = "level"><option id = "level">Fetching Data Please Wait</option></select></td>
						<td>&nbsp;&nbsp; <strong>GSU Type:</strong>&nbsp;&nbsp; 
						<select class = "txtfield" id = "gsu_type" style="width:41%;">
							<option = '0' id = "gsu_type">Fetching Data Please Wait</option>
						</select></td>
						<td width="30%"></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
			  			
			  			<td width="15%" align="right" class="padr5"><strong>Past Due: </strong> </td>
			  			<td width="20%" align="left"><input type = "text"  id = "past_due_from" value = "" class="txtfield">
						</td>
						<td width="20%" align="left">&nbsp;&nbsp;&nbsp;<strong>To:</strong>&nbsp;&nbsp;<input type = "text"  id = "past_due_to" onkeydown = "validate_numbers(event);" value = "" class="txtfield">
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
			  			
			  			<td width="15%" align="right" class="padr5"><strong>Dealer:</strong></td>
			  			<td width="20%" align="left"><input type = "text"  id = "search_dealer" onkeypress = "dealer_search()" value = "" class="txtfield">
						</td>
						<td width="20%" align="left">&nbsp;&nbsp;&nbsp;<strong>To:</strong>&nbsp;&nbsp; <input type = "text"  id = "search_dealer_to" onkeypress = "dealer_search_to()"  value = "" class="txtfield">
						</td>
			  			<td width="30%"></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td width="15%" align="right" class="padr5"><strong>IBM Code:</strong></td>
			  			<td width="20%" align="left"><input type = "text"  id = "IBM_Code_From" value = "" class="txtfield">
						</td>
						<td>&nbsp;&nbsp; <strong>To:</strong>&nbsp;&nbsp; 
						<input type = "text" id = "IBM_Code_To" value ="" class = "txtfield">
						</td>
						<td width="30%"></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td width="20%" align="left">&nbsp;<input type = "submit" id = "Generatelist" disabled = "disabled" value = "Generatelist" class = "btn"></td>
					</tr>
					<tr>
			  			
			  			<td width="10%" align="right">&nbsp;</td>
			  			<td width="20%" align="right">&nbsp;</td>
			  			<td width="40%" align="right">&nbsp;</td>
			  			<td width="30%"></td>
					</tr>					
					
					</table>
				</td>
			</tr>
			</table>			
			<!--End Search Tab-->	
		</td></tr>

		<tr><td>
		<!--Start Dealer List-->
		<br>
		<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin">&nbsp;</td>
			<td class="tabmin2">
				<table width="98%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td class="txtredbold">Dealer List</td>
					<td>
						<table width="50%"  border="0" align="right" cellpadding="0" cellspacing="1">
						<tr>
							<td>&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<br />
		<table width="95%"  border="0" align="left" cellpadding="0" cellspacing="0" class="borderfullgreen" >
		<tr>
		<td class="tab">
			<table width="100%" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 " border="0">
			<tr>				
				<td width="3%" class="padl5 bdiv_r" align="center" ><input type="checkbox" name="chkAll" id = "chkAll"><input type = "hidden" name = "total_numrows" id = "total_numrows"></td>
				<td width="7%" class="padl5 bdiv_r" align="left">IGS Code</td>
				<td width="10%" class="padl5 bdiv_r" align="left">IGS Name</td>
				<td width="10%"  class="padl5 bdiv_r"align="left">GSU Type</td>	
				<td width="10%"  class="padl5 bdiv_r"align="left">IBM Code - Name</td>	
				<td width="10%"  class="padl5 bdiv_r"align="left">Past Due Amount</td>	
				<td width="10%"  class="padl5 bdiv_r"align="left">Penalty Amount</td>	
			</tr>
			</table>
		</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_400" id="dynamic">				
					<!--Dealer Details-->
					<table width="100%" cellpadding="0" cellspacing="1"  border="0" id = "dynamic_table">
						<?php //show tables ?>		
					</table>
						
				</div>
			</td>
		</tr>	
		</table>
		</td></tr>

		<br>		
		</td>
	</tr>
</table>
<br>
<table width="95%" border="0" align="center" cellspacing="0" cellpadding="0">
<tr>
	<td><input type = "submit"  id = "print" value = "Print" disabled = "disabled" onclick="this.form.target='_blank';return true;"></td>
</tr>
</table>
</form>
<br />


