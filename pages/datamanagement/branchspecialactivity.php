<link rel= "stylesheet" type= "text/css" href= "css/ems.css" />
<link rel= "stylesheet" type= "text/css" href= "css/calpopup.css" />
<link rel="stylesheet" type="text/css" href="css/jquery-ui-1.8.5.custom.css">
<script language="javascript" src="js/jquery-1.8.3.min.js"  type="text/javascript"></script>
<script language="javascript" src="js/jquery-ui-1.10.0.custom.min.js"  type="text/javascript"></script>
<script>
$(document).ready(function(){
	//$("#from_date").datepicker();
	//$("#to_date").datepicker();
	
	$("#check_all").click(function() {                          
		$(".checkboxes").attr('checked', this.checked);
	});
	fetch_data();
	
	$("a").live("click",function(){
		$('input:checkbox').removeAttr('checked');
		var id = $(this).data('id');
		var totalbranches = $("#totalbranches").val();
					$("#HolidayID").val("Please Wait..");
					$("#special_act").val("Please Wait..");
					$("#from_date").val("Please Wait..");
					$("#to_date ").val("Please Wait..");	
					$("#save").attr("disabled","disabled");
		$.ajax({
			type:'post',
			dataType:'json',
			data:{'request':'getting information' , 'ID':id},
			url:'pages/datamanagement/system_param_call_ajax/BranchSpecialActivity.php',
			success: function(resp){
				//Holiday Header
				if(resp['holiday_result'].response=="success"){
					$("#HolidayID").val(resp['Holiday_Data'].ID);
					$("#special_act").val(resp['Holiday_Data'].Description);
					$("#from_date").val(resp['Holiday_Data'].Date_From);
					$("#to_date ").val(resp['Holiday_Data'].Date);	
					$("#save").removeAttr("disabled","disabled");
				}
				//Branches
				if(resp['branch_result'].response == "success"){
					//alert(resp['BranchIDs'].length);
					for(var x = 0; resp['BranchIDs'].length > x; x++){
						
						for(var y = 0; totalbranches > y; y++){
						if($('#checkbox'+y).val() == resp['BranchIDs'][x].BranchID){
							var value =  $('#checkbox'+y).val();
							$('input[value="'+value+'"]').attr('checked', 'checked');
						}
						
						}
				}
				return false;
				}else{
					return false;
				}
			}
		})
	})
	
});

function fetch_data(){
	var call_ajax = "";
	call_ajax += '<tr>';
	call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">From Date</div></td>';
	call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">To Date</div></td>';
	call_ajax += '<td height="20" width = "30%" class="txtpallete borderBR"><div align="center" class="padl5">Holiday Description</div></td>';			
	call_ajax += '</tr>';
									
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'Fetch Data Holiday'},
		url: 'pages/datamanagement/system_param_call_ajax/HolidayAjax.php',
		success: function(resp){
			if(resp['result'].response == 'Success'){
				for(var i = 0; resp['fetch_data'].length > i; i++){
					var  StatusName    = resp['fetch_data'][i].StatusName;
					var  Description   = resp['fetch_data'][i].Description;
					var  Date   	   = resp['fetch_data'][i].Date;
					var  Date_From     = resp['fetch_data'][i].Date_From;
					var  ID    		   = resp['fetch_data'][i].ID;
					
					call_ajax += '<tr>';
					call_ajax += '<td class="borderBR"><div align="left" class="padl5">'+Date_From+'</div></td>';
					call_ajax += '<td class="borderBR"><div align="left" class="padl5">'+Date+'</div></td>';
					call_ajax += '<td class="borderBR"><div align="center" class="padl5"><a data-ID="'+ID+'" href="javascript:void(0);">'+Description+'</a></div></td>';			
					call_ajax += '</tr>';
				}
				$("#fetching_data_please_wait").remove();
				$("#call_ajax").html(call_ajax);
				call_ajax = "";
			}
			return false;
		}
	});
}

function confirmsave()
{
	if(confirm("Are you sure want to save this transation?")==false){
		return false;
	}else{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: $('form').serialize(),
			url: 'pages/datamanagement/system_param_call_ajax/BranchSpecialActivity.php',
			success: function(resp){
				if(resp.result=="success"){
					location.assign("index.php?pageid=196")
				}else{
					return false;
				}
				
			}
		
		});
			return false;
	}
}
</script>
<style>
#call_ajax td{padding:5px;}
</style>
<form method="post" action="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="200" valign="top" class="bgF4F4F6">
		<?PHP include("nav.php"); ?> <br>
		</td>
		<td class="divider">&nbsp;</td>
		<td valign="top" style="min-height: 610px; display: block;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td class="topnav">&nbsp;&nbsp;<span class="txtgreenbold13">Parameter</span></td>
		</tr>
		</table>
		
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td valign="top">
				<table width="95%" border="0" align="center" cellpadding="0"
					cellspacing="1">
					<tr>
						<td class="txtgreenbold13">Branch Special Holiday Maintenance</td>
						<td>&nbsp;</td>
					</tr>
				</table>	
		</table>
		<br>
		<!--Begin form-->
		<table width="100%" border="0" align="center" cellspacing="0" cellpadding="0">
		<tr><td>
                        <table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin"></td> 
					<td class="tabmin2"><div align="left" class="padl5 txtredbold">Action</div></td>
					<td class="tabmin3">&nbsp;</td>
				</tr>
			</table>
			<table width="95%"  border="0" align="center" cellpadding="0" cellspacing="1" class="bordersolo" style="border-top:none;">
			<tr>
				<td>
					<table width="50%"  border="0" align="left" cellpadding="0" cellspacing="1">
					<tr>
			  			
			  			<td width="" align="center">&nbsp;</td>
			  			<td width="" align="center">&nbsp;</td>
			  			<td width="" align="center">&nbsp;</td>
			  		</tr>
					<tr>
			  			
			  			<td width="" align="right" class="padr5"><strong>Special Activity</strong></td>
                                                <td align="center" width="5%">:</td>
			  			<td width="" align="left">
							<input type = "hidden" class = "txtfield" id = "HolidayID" name = 'HolidayID'>
							<input type = "text" class = "txtfield" id = "special_act" readonly = "readonly">
						</td>
			  		</tr>
					<tr>
			  			
			  			<td width="" align="right" class="padr5"><strong>From Date</strong></td>
                                                <td align="center" width="5%">:</td>
			  			<td width="" align="left"><input type = "text" class = "txtfield" id = "from_date" readonly = "readonly"></td>
			  
					</tr>
					<tr>
			  			
			  			<td width="" align="right" class="padr5"><strong>To Date:</strong></td>
                                                <td align="center" width="5%">:</td>
			  			<td width="" align="left"><input type = "text" class = "txtfield" id = "to_date" readonly = "readonly"></td>
			  			<td width=""></td>
					</tr>
					<tr>
			  			
			  			<td width="" align="center" class="padr5">&nbsp;</td>
                                                <td align="center" width="5%"></td>
			  			<td width="" align="left"><input type = "submit" value ="Save" disabled = "disabled" class = "btn" id = "save" onclick = "return confirmsave();"></td>
			  			<td width=""></td>
					</tr>
					
					
					<tr>
			  			<td width="" align="center" class="padr5">&nbsp;</td>
			  		</tr>
					</table>
				</td>
			</tr>
			</table>
			<tr>
				<td>&nbsp;</td>
			</tr>	
			</tr></td>
			<tr><td>

	<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td valign="top" width="45%">
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin"></td> 
					<td class="tabmin2"><div align="left" class="padl5 txtredbold">Result(s)</div></td>
					<td class="tabmin3">&nbsp;</td>
				</tr>
			</table>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
						<tr>
							<td class="bgF9F8F7">
								<div class="scroll_300">
									<table width="100%" id = "call_ajax"  border="0" cellpadding="0" cellspacing="0">
											<tr align="center" id = "">
												<td width="" height="20" class="txtpallete borderBR"><div align="left" class="padl5">From Date</div></td>
												<td width="" height="20" class="txtpallete borderBR"><div align="left" class="padl5">To Date</div></td>
												<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Special Activity</div></td>			
											</tr>
									</table>
								</div>
							</td>
						</tr>
						</table>
					</td>
				</tr>
			</table>
		<td width="1%">&nbsp;</td>
		<td width="47%" valign="top">
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td class="tabmin"></td> 
					<td class="tabmin2">
						<div align="left" class="padl5 txtredbold">Branches
						</div></td>
					<td class="tabmin3">&nbsp;</td>
				</tr>
			</table>
			<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="bordergreen" id="tbl2">
			
					<tr>
						<td valign="top" class="bgF9F8F7">
						</td>
					</tr>
					<tr>
						<td class="bgF9F8F7">
							<?PHP $q = $database->execute("SELECT * FROM branch WHERE id > 3 ORDER BY NAME asc"); ?>
							<div class="scroll_300">
								<table width="100%" id = ""  border="0" cellpadding="0" cellspacing="0">
									<tr align="center" id = "">
												<td width="" height="20" class="txtpallete borderBR">
												<div align="left" class="padl5">
													<input type = "checkbox" id = "check_all" name "check_all" />
													<input type = "hidden" id = "totalbranches" name "totalbranches" value = <?php echo $q->num_rows; ?> />
												</div></td>
												<td width="" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Branch Code</div></td>
												<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Branch Name</div></td>			
									</tr>
												<?php 
													  if($q->num_rows){
													  $ctr = 0;
													  while ($r = $q->fetch_object()){
												?>
									<tr align="center" id = "">
												<td width="" height="20" class="txtpallete borderBR">
													<div align="left" class="padl5">
														<input type = "checkbox" name = "checkbox[]" id ="checkbox<?php echo $ctr; ?>" class = "checkboxes" value = "<?php echo $r->ID; ?>">
													</div>
												</td>
												<td width="" height="20" class="txtpallete borderBR"><div align="left" class="padl5"><?php echo $r->Code; ?></div></td>
												<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5"><?php echo $r->Name; ?></div></td>			
									</tr>
											   <?php  $ctr++; }}?>
							</div>
						</td>
					</tr>
					
					</table>
				</td>
			</table>
	</tr>
	</table>
				
			</tr></td>
	</table>	
	
</table>
	
</form>

