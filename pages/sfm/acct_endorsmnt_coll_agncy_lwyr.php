<link rel= "stylesheet" type= "text/css" href= "css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "css/calpopup.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script>
$(document).ready(function(){
	$("#as_of_date").datepicker();
	$("#chkAll").click(function() {
		                          
		$(".checkboxes").attr('checked', this.checked);
	});
	$("#Generatelist").click(function(){
		
		
		
		var show_error ="",count_err = 0, first_error = 0;
		
		var bool = "";
		if($("#w_o_from").val() == ""){
			show_error += "Write off from required.\n";
			first_error++;
		
		}
		if($("#w_o_to").val() == ""){
			show_error += "Write off to required. \n";
			first_error++;
		
		}
		if($("#days_overdue_from").val() == ""){
			show_error += "days overdue from required. \n";
			first_error++;
		}
		
		if($("#days_overdue_to").val() == ""){
			show_error += "days overdue to required. \n";
			first_error++;
		}
		
		if($("#as_of_date").val() == "") {
			show_error += "as of date required. \n";
			first_error++;	
		}
		
		
		
		if(first_error > 0){
			alert(show_error);
			return false;
		}else{
		if($("#w_o_from").val() < 500){
			show_error += "Write off amount from Only above 500 pesos is accepted. \n";
			count_err++;
		}
		
		
		if($("#w_o_too").val() < 500){
			show_error += "Write off to amount Only above 500 pesos is accepted. \n";
			count_err++;
		}
		
		if($("#days_overdue_from").val() < -1){
			show_error += "Days Overdue from. account with days higher than 30 days is accepted. \n";
			count_err++;
		}
		
		if($("#days_overdue_to").val() < -1){
			show_error += "Days Overdue to. account with days higher than 30 days is accepted. \n";
			count_err++;
		}
		if(count_err > 0){
			alert(show_error);
			return false;
		}else{
			var html1 = ""; 
			
			$.ajax({
				type: 'post',
				dataType: 'json',
				data: {'request': 'Generate List', 'writeoff_from':$("#w_o_from").val(), 'writeoff_to': $("#w_o_to").val(),  'days_overdue_from': $("#days_overdue_from").val(),
						'as_of_date': $('#as_of_date').val(),'days_overdue_to': $("#days_overdue_to").val()},
				url: 'pages/sfm/param_ajax_calls/acct_endrosmnt_call_ajax.php',
				success: function(resp){
					if(resp['results'].fetching_data =='Success'){
					var GSU = "";
						for(var i = 0; resp['fetch_data'].length > i; i++){
							html1 += "<tr>";	
							html1 += "<td width='3%'  class='borderBR padl5''  align='left' >";
							html1 += "<input type='checkbox' class = 'checkboxes' name='chkInclude[]' id ='chkInclude"+i+"' value='"+resp['fetch_data'][i].xID+","+resp['fetch_data'][i].salesID+"'>";
							html1 += "</td>";
							html1 += "<td width='7%'  class='borderBR padl5'  align='left'>"+resp['fetch_data'][i].Code+"</td>";
							html1 += "<td width='20%'  class='borderBR padl5'  align='left'>"+resp['fetch_data'][i].DealerName+"</td>";
							html1 += "<td width='10%'   class='borderBR padl5'  align='left'>"+resp['fetch_data'][i].StatusCode+"</td>";		
							html1 += "<td width='10%'   class='borderBR padl5'  align='left'>"+resp['fetch_data'][i].AmountOpen+"</td>";		
							html1 += "</tr>";	
						}

						
						
						var total_record = resp['fetch_data'].length;
						$("#dynamic_table").html(html1);
						$("#total_numrows").val(total_record);
						$("#print").removeAttr("disabled","disabled");
					}else{
						alert('No Record(s) displayed.');
					}
				}
			});
		return false;
		}
		}
		})
		
	})
</script>
<form method="post" action="pages/sfm/acct_endorsmnt_coll_agncy_lwyr_print.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
		<?PHP include("nav_dealer.php"); ?> <br>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Credit Management</span></td>
		</tr>
		</table>
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
				<table width="95%" border="0" align="center" cellpadding="0"
					cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Accounts for Endorsement to Collection Agency / Lawyer</td>
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

			  			<td width="15%" align="right" class="padr5"><strong>Write-Off Amount From:</strong></td>
			  			<td width="20%" align="left">
							<input type = 'text' value = '' id = "w_o_from" name = "w_o_from" class ='txtfield'>
							</td><td><strong>To: </strong><input type = 'text' value = '' name = 'w_o_to' id = "w_o_to" class ='txtfield'> 
						</td>
			  			<td width="30%"></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
			  			
			  			<td width="18%" align="right" class="padr5"><strong>Days Overdue:</strong></td>
			  			<td width="20%" align="left"><input type="text" name="days_overdue_from" id = "days_overdue_from"  class="txtfield" value=""></td>
			  			<td width="20%" align="left"><strong>To:</strong><input type="text" name="days_overdue_to" id = "days_overdue_to"  class="txtfield" value=""></td>
			  	
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
			  			
			  			<td width="18%" align="right" class="padr5"><strong>As of date:</strong></td>
			  			<td width="20%" align="left"><input type="text" name="as_of_date" id = "as_of_date"  class="txtfield" value=""></td>
						<td width="20%" align="left">&nbsp;<input type = "submit" id = "Generatelist"  value = "Generatelist" class = "btn"></td>
			  			<td width="30%"></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
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
				<td width="7%" class="padl5 bdiv_r" align="left">Code</td>
				<td width="20%" class="padl5 bdiv_r" align="left">Name</td>
				<td width="10%"  class="padl5 bdiv_r"align="left">Account Status</td>	
				<td width="10%"  class="padl5 bdiv_r"align="left">Proposed Write-Off Amount</td>	
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


