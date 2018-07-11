$(document).ready(function(){
	$("#selection_group2").html("<option>[SELECT HERE]</option>")
	$("#check_all").live('click',function() {                          
		if($("#check_all").is(":checked")){
			$(".checkboxes").attr('checked', 'checked');
		}else{
			$(".checkboxes").removeAttr('checked', 'checked');
		}
		
	});
	
	$("#selection_module").change(function(){
		var module_id = $("#selection_module").val();
		var dynamic_option = '';
		$.ajax({
			url:'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
			dataType:'json',
			type:'post',
			data:{'request':'get reason type','module_id':module_id},
			success:function(resp){
				if(resp['response'] == 'success'){
						dynamic_option += "<option value = 0>[SELECT HERE]</option>";
					for(var i = 0; resp['data_handler'].length > i; i++){
						 dynamic_option+='<option value="'+resp['data_handler'][i].ID+'">'+resp['data_handler'][i].Name+'</option>';
					}
					$("#selection_reason_type").html(dynamic_option);
				}else{
					$("#selection_reason_type").html("<option value=0>[SELECT HERE]</option>");
					$("#selection_group").attr("disabled","disabled");
					$("#selection_group").val(0);
					$("#save").attr("disabled","disabled");
					$("#selection_group2").html("<option value=0>[SELECT HERE]</option>");
                    //$("#dynamic_reason").html("<center>No Record(s) to displayed.</center>");
				}
				return false;
			}
		});
	});
	
	$("#selection_group").change(function(){
			var selection_group = $("#selection_group").val();
			var dynamic_option = "";
		
		if(selection_group == 3){
			$("#save").removeAttr("disabled","disabled");
			$("#selection_group2").html("<option value=0>[SELECT HERE]</option>");
			$(".checkboxes").removeAttr("checked","checked");
			var Module 		= $("#selection_module").val();
			var ReasonType  = $("#selection_reason_type").val();
			var GroupType 	= $("#selection_group2").val();
			var	SelectGroup = $("#selection_group").val();
			var LinkToDMCM = "";
			if($("#LinkToDMCM").is(":checked")){
				LinkToDMCM = $("#LinkToDMCM").val();
			}else{
				LinkToDMCM = 0;
			}
			var reason_total_records = $("#total_records").val();
					$.ajax({
							type: 'post',
							dataType: 'json',
							data: {'request':'check all checkboxes','Module':Module,'ReasonType':ReasonType,'GroupType':GroupType,'SelectGroup':SelectGroup,'LinkToDMCM':LinkToDMCM,
								   'mode_type_reason_tag':$("#mode_type_reason_tag").val(),'DocType':$("#doc_type").val()
							},
							url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
							success: function(response){
								if(response['response'] == 'success'){
									for(var i =0; response['data_handler'].length > i; i++){
										for(var h = 0; reason_total_records > h; h++){
											if($("#checkbox"+h).val() == response['data_handler'][i].checkedID){
												$("#checkbox"+h).attr("checked","checked");
											}	
										}
									}
								}else{
									$(".checkboxes").removeAttr('checked', 'checked');
								}
							}
					});
			
		}else{
			$("#save").attr("disabled","disabled");
			$.ajax({
				url:'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
				dataType:'json',
				type:'post',
				data:{'request':'dynamic grouping','selection_group':selection_group},
				success:function(resp){
					if(resp['response'] == 'success'){
							dynamic_option += "<option value = 0>[SELECT HERE]</option>";
						for(var i = 0; resp['data_handler'].length > i; i++){
							dynamic_option+='<option value="'+resp['data_handler'][i].ID+'">'+resp['data_handler'][i].Name+'</option>';
						}
						$("#selection_group2").html(dynamic_option);
					}else{
						//alert('error please contact administraor.');
						$(".checkboxes").removeAttr("checked","checked");
						$("#selection_group2").html("<option value=0>[SELECT HERE]</option>");
						//$("#save").attr("disabled","disabled");
					}
					return false;
				}
			});
		}
		
	});
	
	$("#selection_reason_type").change(function(){
			if($("#selection_reason_type").val() != 0){
				$("#selection_group").removeAttr("disabled","disabled");
			}else{
				$("#selection_group").attr("disabled","disabled");
				$("#selection_group").val(0);
			}
	});
	
	$("#selection_group2").change(function(){
		if($("#selection_group2").val() != 0){
			$("#save").removeAttr("disabled","disabled");
		}else{
			$("#save").attr("disabled","disabled");
		}
	});
        
        $("#selection_reason_type").change(function(){
           var reasontype = $("#selection_reason_type").val();
		   var mode_type_reason_tag = "";
		   if(reasontype == 8){
				//mode_type_reason_tag
				mode_type_reason_tag += '<option value="0">SELECT HERE</option>';
				mode_type_reason_tag += '<option value="1">Current</option>';
				mode_type_reason_tag += '<option value="2">Previous</option>';
		   }else{
				//mode_type_reason_tag
				mode_type_reason_tag += '<option value="0">SELECT HERE</option>';
				mode_type_reason_tag += '<option value="1">Automatic</option>';
				mode_type_reason_tag += '<option value="2">Manual</option>';
				mode_type_reason_tag += '<option value="3">Not Applicable</option>';
		   }
          var dynamic = '';
           $.ajax({
                url:'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
                type: 'post',
                dataType: 'json',
                data: {'request':'get available reason on reason table','parameter':reasontype},
                success: function(resp){
                     
                     if(resp['response']=='success'){
							 $("#total_records").val(resp['total_records']);
                              dynamic += '<tr align="center" id = "">'; 
                              dynamic += '<td width="" height="20" class="txtpallete borderBR">';
                              dynamic += '<div align="left" class="padl5">';
                              dynamic += '<input type = "checkbox" id = "check_all" name "check_all" />';
                              dynamic += '<input type = "hidden" id = "totalbranches" name "totalbranches" value = "'+resp['total_records']+'" />';
                              dynamic += '</div></td>';
                              dynamic += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Reason Name</div></td>';
                              dynamic += '</tr>';
                          for(var i = 0; resp['data_handler'].length > i; i++){
                              dynamic += '<tr>';
                              dynamic += '<td width="" height="20" class="txtpallete borderBR">';
                              dynamic += '<div align="left" class="padl5">';
                              dynamic += '<input type = "checkbox" name = "checkbox[]" id ="checkbox'+i+'" class = "checkboxes" value = "'+resp["data_handler"][i].ReasonID+'">';
                              dynamic += '</div>';
                              dynamic += '</td>';
                              dynamic += '<td width="" height="20" class=" borderBR">';
                              dynamic += '<div align="center" class="padl5">';
                              dynamic += resp["data_handler"][i].ReasonName
                              dynamic += '<div>';
                              dynamic += '</td>';     	   
                              dynamic += '</tr>';
                          }
                          
                     }else{
                        dynamic += "<center>No Record(s) to displayed.</center>";
						 $("#total_records").val(0);
                     }
                     $("#dynamic_reason").html(dynamic);
					 $("#mode_type_reason_tag").html(mode_type_reason_tag);
                } 
           });
        });
        
        $("#choose_cathegory").change(function(){
             if($("#choose_cathegory").val() == 1){
                  $("#reason_tagging_maintenance").fadeIn();
                  $("#reason_maintenance").hide();
                  $("#reason_type_maintenance").hide();
             }else if ($("#choose_cathegory").val() == 2){
                  $("#reason_maintenance").fadeIn();
                  $("#reason_tagging_maintenance").hide();
                  $("#reason_type_maintenance").hide();
             }else{
				  $("#reason_maintenance").hide();
                  $("#reason_tagging_maintenance").hide();
                  $("#reason_type_maintenance").fadeIn();
				  get_all_reason_type();
			 }
        });
		
		//search reason
		$("#go_search").click(function(){
				var dynamic_reason_x = '';
						 dynamic_reason_x += '<tr align="center" id = "">';
						 dynamic_reason_x += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Reason Name</div></td>';
						 dynamic_reason_x += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';
						 dynamic_reason_x += '</tr>';
				$.ajax({
						type: 'post',
						dataType: 'json',
						data: {'request':'search reason','like':$("#search").val()},
						url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
						success: function(response){
						if(response['response'] == 'success'){
							for(var i = 0; response['data_handler'].length > i; i++){
								dynamic_reason_x += '<tr>';
								dynamic_reason_x += '<td width="" height="20" class=" borderBR">';
								dynamic_reason_x += '<div align="center" class="padl5">';
								dynamic_reason_x += '<a id = "reason_link" href = "" onclick = "return reason_click('+response['data_handler'][i].ID+')">'+response['data_handler'][i].Name+'</a>';
								dynamic_reason_x += '</td>';
								
								dynamic_reason_x += '<td width="" height="20" class=" borderBR">';
								dynamic_reason_x += '<div align="center" class="padl5">';
								dynamic_reason_x += '<input type = "submit" onclick = "return delete_record('+response['data_handler'][i].ID+')" class = "btn" value = "Delete">';
								dynamic_reason_x += '</td>';
								dynamic_reason_x += '</tr>';
							}
							$("#dynamic_reason2").html(dynamic_reason_x);
						}
						}
				});
				return false;
			
		});
		
		$("#selection_group2").change(function(){
			if($("#selection_group2").val() == 0){
				$(".checkboxes").removeAttr('checked', 'checked');
			}else{
				$(".checkboxes").removeAttr("checked","checked");
				var Module 		= $("#selection_module").val();
				var ReasonType  = $("#selection_reason_type").val();
				var GroupType 	= $("#selection_group2").val();
				var	SelectGroup = $("#selection_group").val();
				var error_msg = '', error_cnt = 0;
				var LinkToDMCM = "";
				if($("#LinkToDMCM").is(":checked")){
					LinkToDMCM = $("#LinkToDMCM").val();
				}else{
					LinkToDMCM = 0;
				}
				if(Module == 0){
					error_msg = "*Module Required. \n";
					error_cnt++;
				}
				if(ReasonType == 0){
					error_msg = "*Reason type Required. \n";
					error_cnt++;
				}
				
				if(error_cnt==0){
					reason_total_records = $("#total_records").val();
					$.ajax({
							type: 'post',
							dataType: 'json',
							data: {'request':'check all checkboxes','Module':Module,'ReasonType':ReasonType,'GroupType':GroupType,'SelectGroup':SelectGroup,
								   'mode_type_reason_tag':$("#mode_type_reason_tag").val(), 'LinkToDMCM':LinkToDMCM,'DocType':$("#doc_type").val()
							},
							url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
							success: function(response){
								if(response['response'] == 'success'){
									for(var i =0; response['data_handler'].length > i; i++){
										for(var h = 0; reason_total_records > h; h++){
											if($("#checkbox"+h).val() == response['data_handler'][i].checkedID){
												$("#checkbox"+h).attr("checked","checked");
											}
										}
										if(response['data_handler'][0].ModeType==null){
											$("#selection_group3").val(3);
										}else{
											var x = response['data_handler'][0].ModeType;
											$("#selection_group3").val(x);
										}
									}
								}else{
									$(".checkboxes").removeAttr('checked', 'checked');
								}
							}
					});
					
				}else{
					alert(error_msg);
				}
				
			}
		});
		
		//used this for reason type on reason tag maintenance..
		$("#doc_type").change(function(){
			var reason_total_records = $("#total_records").val();
			$(".checkboxes").removeAttr("checked","checked");
			var Module 		= $("#selection_module").val();
			var ReasonType  = $("#selection_reason_type").val();
			var GroupType 	= $("#selection_group2").val();
			var	SelectGroup = $("#selection_group").val();
			var error_msg = '', error_cnt = 0;
			if($("#LinkToDMCM").is(":checked")){
				LinkToDMCM = $("#LinkToDMCM").val();
			}else{
				LinkToDMCM = 0;
			}
			
			
			if(Module == 0){
				error_msg = "*Module Required. \n";
				error_cnt++;
			}
			if(ReasonType == 0){
				error_msg = "*Reason type Required. \n";
				error_cnt++;
			}
			if(error_cnt==0){
				$.ajax({
								type: 'post',
								dataType: 'json',
								data: {	'request':'check all checkboxes by doctype','Module':Module,'ReasonType':ReasonType,
										'GroupType':GroupType,'SelectGroup':SelectGroup,
										'mode_type_reason_tag':$("#mode_type_reason_tag").val(),'DocType':$("#doc_type").val(),'LinkToDMCM':LinkToDMCM
								},
								url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
								success: function(response){
									if(response['response'] == 'success'){
										for(var i =0; response['data_handler'].length > i; i++){
											for(var h = 0; reason_total_records > h; h++){
												if($("#checkbox"+h).val() == response['data_handler'][i].checkedID){
													$("#checkbox"+h).attr("checked","checked");
												}
											}
											if(response['data_handler'][0].ModeType==null){
												$("#selection_group3").val(3);
											}else{
												var x = response['data_handler'][0].ModeType;
												$("#selection_group3").val(x);
											}
										}
									}else{
										$(".checkboxes").removeAttr('checked', 'checked');
									}
								}
				});
			}
		})
		
		$("#LinkToDMCM").change(function(){
			var reason_total_records = $("#total_records").val();
			$(".checkboxes").removeAttr("checked","checked");
			var Module 		= $("#selection_module").val();
			var ReasonType  = $("#selection_reason_type").val();
			var GroupType 	= $("#selection_group2").val();
			var	SelectGroup = $("#selection_group").val();
			var error_msg = '', error_cnt = 0;
			if($("#LinkToDMCM").is(":checked")){
				LinkToDMCM = $("#LinkToDMCM").val();
			}else{
				LinkToDMCM = 0;
			}
			
			
			if(Module == 0){
				error_msg = "*Module Required. \n";
				error_cnt++;
			}
			if(ReasonType == 0){
				error_msg = "*Reason type Required. \n";
				error_cnt++;
			}
			if(error_cnt==0){
				$.ajax({
								type: 'post',
								dataType: 'json',
								data: {	'request':'check all checkboxes by dmcm','Module':Module,'ReasonType':ReasonType,
										'GroupType':GroupType,'SelectGroup':SelectGroup,
										'mode_type_reason_tag':$("#mode_type_reason_tag").val(),'DocType':$("#doc_type").val(),'LinkToDMCM':LinkToDMCM
								},
								url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
								success: function(response){
									if(response['response'] == 'success'){
										for(var i =0; response['data_handler'].length > i; i++){
											for(var h = 0; reason_total_records > h; h++){
												if($("#checkbox"+h).val() == response['data_handler'][i].checkedID){
													$("#checkbox"+h).attr("checked","checked");
												}
											}
											if(response['data_handler'][0].ModeType==null){
												$("#selection_group3").val(3);
											}else{
												var x = response['data_handler'][0].ModeType;
												$("#selection_group3").val(x);
											}
										}
									}else{
										$(".checkboxes").removeAttr('checked', 'checked');
									}
								}
				});
			}else{
				alert(error_msg);
			}
			
			
			
			
			
		})
		
		
		
		
});
function confirmsave()
{
	if(confirm("Are you sure want to save this transation?")==false){
		return false;
	}else{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: $('form').serialize(),
			url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
			success: function(resp){
				if(resp.result=="success"){
					location.assign("index.php?pageid=520");
				}else{
					return false;
				}
			}
		
		});
			return false;
	}
}

function reason_click(ID)
{
     $.ajax({
          type: 'post',
          dataType: 'json',
          data: {'request':'get reason','ID':ID},
          url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
          success:function(resp){
            if(resp['response']=='success'){
               var ID             = resp['data_handler'].ID;
               var reason_code    = resp['data_handler'].Code;
               var Name           = resp['data_handler'].Name;
               var ReasonTypeID   = resp['data_handler'].ReasonTypeID;
               var CreditGLAccount = resp['data_handler'].CreditGLAccount;
               var CreditCostCenter = resp['data_handler'].CreditCostCenter;
               var DebitGLAccount = resp['data_handler'].DebitGLAccount;
               var DebitCostCenter = resp['data_handler'].DebitCostCenter;
			   
               $("#reason_code").val(reason_code);
               $("#reason_id").val(ID);
               $("#reason").val(Name);
               $("#selection_reason_type_r").val(ReasonTypeID);
               $("#rm_save").attr("onclick","return rm_saveclick(2)");
               $("#rm_save").removeAttr("disabled","disabled");
			   $("#CreditGLAcount").val(CreditGLAccount);
			   $("#CreditCostCenter").val(CreditCostCenter);
			   $("#DebitGLAccount").val(DebitGLAccount);
			   $("#DebitCostCenter").val(DebitCostCenter);
			   $("#reason_code").attr("readonly","readonly");
			   $("#cancel").show();
			   $("#clear").hide();
            }   
          }
     });
     return false;
}

function rm_saveclick(validation){
     if(validation == 0){ //cancel..
          if(confirm("Are you sure want to cancel this transaction?")==false){
               return false
          }else{
               $("#reason_id").val("");
               $("#reason").val("");
               $("#selection_reason_type_r").val(0);
			   $("#reason_code").removeAttr("readonly","readonly");
               //$("#rm_save").attr("disabled","disabled");
			   $("#CreditGLAcount").val("");
			   $("#CreditCostCenter").val("");
			   $("#DebitGLAccount").val("");
			   $("#DebitCostCenter").val("");
			   $("#reason_code").val("");
			   $("#rm_save").attr("onclick","return rm_saveclick(1)");
               $("#cancel").hide();
			   $("#clear").show();
               return false;
          }
     }else if(validation == 1){	 //save..
			   var reason = $("#reason").val();
               var selection_reason_type= $("#selection_reason_type_r").val();
               var CreditGLAcount = $("#CreditGLAcount").val();
			   var CreditCostCenter = $("#CreditCostCenter").val();
			   var DebitGLAccount= $("#DebitGLAccount").val();
			   var DebitCostCenter = $("#DebitCostCenter").val();
			   var reason_code = $("#reason_code").val();
			   var error_cnt = 0, err_msg = "";
			   
			   if(reason_code == ""){
				err_msg += "*Reason Code required. \n";
				error_cnt++;
			   }
			   if(reason == ""){
					err_msg += "*Reason required. \n";
					error_cnt++;
			   }
			   //if(selection_reason_type == 0){
				//	err_msg += "*Reason type required. \n";
				//	error_cnt++;
			   //}
			   //'selection_reason_type':selection_reason_type,
			   if(error_cnt == 0){
					if(confirm("Are you sure want to save this transaction?")==false){
						return false
					}else{
						//dynamic reason2
						var dynamic_reason_x = '';
						 dynamic_reason_x += '<tr align="center" id = "">';
						 dynamic_reason_x += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Reason Name</div></td>';
						 dynamic_reason_x += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';
						 dynamic_reason_x += '</tr>';
						$.ajax({
							type: 'post',
							dataType: 'json',
							data: {'request':'save reason','reason':reason,  'CreditGLAcount':CreditGLAcount, 
									'CreditCostCenter':CreditCostCenter, 'DebitGLAccount':DebitGLAccount,'DebitCostCenter':DebitCostCenter,'reason_code':reason_code},
							url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
							success: function(response){
								if(response['response'] == 'success'){
									for(var i = 0; response['data_handler'].length > i; i++){
										 dynamic_reason_x += '<tr>';
										 dynamic_reason_x += '<td width="" height="20" class=" borderBR">';
										 dynamic_reason_x += '<div align="center" class="padl5">';
										 dynamic_reason_x += '<a id = "reason_link" href = "" onclick = "return reason_click('+response['data_handler'][i].ID+')">'+response['data_handler'][i].Name+'</a>';
										 dynamic_reason_x += '</td>';
										 
										 dynamic_reason_x += '<td width="" height="20" class=" borderBR">';
										 dynamic_reason_x += '<div align="center" class="padl5">';
										 dynamic_reason_x += '<input type = "submit" onclick = "return delete_record('+response['data_handler'][i].ID+')" class = "btn" value = "Delete">';
										 dynamic_reason_x += '</td>';
										 
										 dynamic_reason_x += '</tr>';
									}
									$("#dynamic_reason2").html(dynamic_reason_x);
									$("#reason_id").val("");
									$("#reason").val("");
									$("#selection_reason_type_r").val(0);
									$("#reason_code").removeAttr("readonly","readonly");
									//$("#rm_save").attr("disabled","disabled");
									$("#CreditGLAcount").val("");
									$("#CreditCostCenter").val("");
									$("#DebitGLAccount").val("");
									$("#DebitCostCenter").val("");
									$("#reason_code").val("");
									$("#rm_save").attr("onclick","return rm_saveclick(1)");
									$("#cancel").hide();
									$("#clear").show();
								}else{
									alert("Reason already in used.");
								}
							}
						});
					}
			   }else{
				alert(err_msg);
				return false;
			   }
          return false;
     }else if (validation == 4){
			   $("#reason_id").val("");
               $("#reason").val("");
               $("#selection_reason_type_r").val(0);
			   $("#reason_code").removeAttr("readonly","readonly");
               //$("#rm_save").attr("disabled","disabled");
			   $("#CreditGLAcount").val("");
			   $("#CreditCostCenter").val("");
			   $("#DebitGLAccount").val("");
			   $("#DebitCostCenter").val("");
			   $("#reason_code").val("");
			   $("#rm_save").attr("onclick","return rm_saveclick(1)");
               $("#cancel").hide();
			   $("#clear").show();
               return false;
	 }else { //update..
             var reason_id  = $("#reason_id").val();
             var reason     = $("#reason").val();
             var selection_reason_type= $("#selection_reason_type_r").val();
             var CreditGLAcount = $("#CreditGLAcount").val();
			 var CreditCostCenter = $("#CreditCostCenter").val();
			 var DebitGLAccount= $("#DebitGLAccount").val();
			 var DebitCostCenter = $("#DebitCostCenter").val();
			 var reason_code = $("#reason_code").val();
			 var error_cnt = 0, err_msg = "";
			if(reason_code == ""){
				err_msg = "*Reason Code required. \n";
			}
			if(reason == ""){
				err_msg = "*Reason required. \n";
				error_cnt++;
			}
			if(selection_reason_type == 0){
				err_msg = "*Reason type required. \n";
				error_cnt++;
			}
			if(error_cnt == 0){
                var selection_reason_type = $("#selection_reason_type_r").val();
				$.ajax({
                  type: 'post',
                  dataType: 'json',
                  data: {'request':'update reason oye!','ID':reason_id, 'reason':reason, 
                         'selection_reason_type':selection_reason_type, 'CreditGLAcount':CreditGLAcount, 'CreditCostCenter':CreditCostCenter, 
						 'DebitGLAccount':DebitGLAccount,'DebitCostCenter':DebitCostCenter,'reason_code':reason_code},
                  url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
                  success: function(response){
				  var dynamic_reason_x = '';
				  dynamic_reason_x += '<tr align="center" id = "">';
				  dynamic_reason_x += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Reason Name</div></td>';
				  dynamic_reason_x += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';
				  dynamic_reason_x += '</tr>';
			
						if(response['response'] == 'success'){
							for(var i = 0; response['data_handler'].length > i; i++){
								dynamic_reason_x += '<tr>';
								dynamic_reason_x += '<td width="" height="20" class=" borderBR">';
								dynamic_reason_x += '<div align="center" class="padl5">';
								dynamic_reason_x += '<a id = "reason_link" href = "" onclick = "return reason_click('+response['data_handler'][i].ID+')">'+response['data_handler'][i].Name+'</a>';
								dynamic_reason_x += '</td>';
								
								dynamic_reason_x += '<td width="" height="20" class=" borderBR">';
								dynamic_reason_x += '<div align="center" class="padl5">';
								dynamic_reason_x += '<input type = "submit" onclick = "return delete_record('+response['data_handler'][i].ID+')" class = "btn" value = "Delete">';
								dynamic_reason_x += '</td>';
								dynamic_reason_x += '</tr>';
							}
									$("#dynamic_reason2").html(dynamic_reason_x);
									$("#reason_id").val("");
									$("#reason").val("");
									$("#selection_reason_type_r").val(0);
									$("#reason_code").removeAttr("readonly","readonly");
									//$("#rm_save").attr("disabled","disabled");
									$("#CreditGLAcount").val("");
									$("#CreditCostCenter").val("");
									$("#DebitGLAccount").val("");
									$("#DebitCostCenter").val("");
									$("#reason_code").val("");
									$("#rm_save").attr("onclick","return rm_saveclick(1)");
									$("#cancel").hide();
									$("#clear").show();
						}
					}
				});
               return false;
			}else{
				alert(err_msg);
				return false;
			}
     }
}

function delete_record(ID){
	if(confirm("Are you sure want to delete this record?")==false){
		return false;
	}else{
		var dynamic_reason_x = '';
			dynamic_reason_x += '<tr align="center" id = "">';
			dynamic_reason_x += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Reason Name</div></td>';
			dynamic_reason_x += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';
			dynamic_reason_x += '</tr>';
			
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: {'request':'save reason','ID':ID},
			url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
			success: function(response){
				if(response['response'] == 'success'){
					for(var i = 0; response['data_handler'].length > i; i++){
						 dynamic_reason_x += '<tr>';
						 dynamic_reason_x += '<td width="" height="20" class=" borderBR">';
						 dynamic_reason_x += '<div align="center" class="padl5">';
						 dynamic_reason_x += '<a id = "reason_link" href = "" onclick = "return reason_click('+response['data_handler'][i].ID+')">'+response['data_handler'][i].Name+'</a>';
						 dynamic_reason_x += '</td>';
						 
						 dynamic_reason_x += '<td width="" height="20" class=" borderBR">';
						 dynamic_reason_x += '<div align="center" class="padl5">';
						 dynamic_reason_x += '<input type = "submit" onclick = "return delete_record('+response['data_handler'][i].ID+')" class = "btn" value = "Delete">';
						 dynamic_reason_x += '</td>';
						 dynamic_reason_x += '</tr>';
					}
					$("#dynamic_reason2").html(dynamic_reason_x);
					$("#cancel").hide();
					$("#clear").show();
				}
			}
		});
		return false;
	}
}

//reason type here...
function click_reason_type_save(validation)
{
	var dynamic_reason_type = "";
	if(validation == 1){ //save
		alert('under construction...');
		//clear reason type texboxes
		clear_reason_type_cache();
	}else if (validation==2){//cancel
		//clear reason type texboxes
		clear_reason_type_cache();
		//auto hide..
		$("#TRIsTradeNonTrade").hide();
		$("#TRModeType").hide();
		//reoverwrite update to save..
		$("#reason_type_update").hide();
		$("#reason_type_save").show();
	}else{ //update
		$("#request").val("update reason type");
		var xdata = "";
		var isTradeNonTrade = 0;
		var ModeType = 0;
		if($("#reason_type_ID").val() == 9){
			//here for OR
			if($("#isTradeNontrade").is(":checked")){
				isTradeNonTrade = $("#isTradeNontrade").val();
			}
			if($("#ModeType").is(":checked")){
				ModeType = $("#ModeType").val();
			}
		}
		xdata = {'request':'update reason type','reason_type_ID':$("#reason_type_ID").val(), 'SelectionModuleType':$("#selection_module_type").val(),
					 'ReasonTypeGroup':$("#reason_type_group").val(),'reason_type_name':$("#reason_type_name").val(), 'isTradeNonTrade':isTradeNonTrade,
					 'ModeType':ModeType
				};
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: xdata,
			url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
			success: function(response){
				if(response['response'] == 'success'){
					for(var i = 0; response['data_handler'].length > i; i++){
						 dynamic_reason_type += '<tr>';
						 dynamic_reason_type += '<td width="" height="20" class=" borderBR">';
						 dynamic_reason_type += '<div align="center" class="padl5">';
						 dynamic_reason_type += '<a id = "reason_link" href = "" onclick = "return reason_type_click('+response['data_handler'][i].ID+')">'+response['data_handler'][i].Name+'</a>';
						 dynamic_reason_type += '</td>';
						 dynamic_reason_type += '<td width="" height="20" class=" borderBR">';
						 dynamic_reason_type += '<div align="center" class="padl5">';
						 dynamic_reason_type += '<input type = "submit" onclick = "return delete_record_reasontype('+response['data_handler'][i].ID+')" class = "btn" value = "Delete">';
						 dynamic_reason_type += '</td>';
						 dynamic_reason_type += '</tr>';
					}
					$("#dynamic_reason_type").html(dynamic_reason_type);
					//clear reason type texboxes
					clear_reason_type_cache();
				}
			}
		});
		
		return false;
	}
	return false;
}
//remove..
function clear_reason_type_cache()
{
	$("#reason_type_ID").val();
	$("#reason_type_name").val("");
	$("#selection_module_type").val("");
	$("#reason_type_group").val("");

	//for or transaction..
	$("#isTradeNontrade").removeAttr("checked","checked");
	$("#ModeType").removeAttr("checked","checked");
	$("#TRModeType").hide();
	$("#TRIsTradeNonTrade").hide();
	//exhibition on save and update...
	$("#reason_type_update").hide();
	$("#reason_type_save").show();
}
//fetch all reason type...
function get_all_reason_type()
{
	var dynamic_reason_type = '';
		dynamic_reason_type += '<tr align="center" id = "">';
		dynamic_reason_type += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Reason Type Name</div></td>';
		dynamic_reason_type += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';
		dynamic_reason_type += '</tr>';
	$.ajax({
			type: 'post',
			dataType: 'json',
			data: {'request':'fetch_reasontype',},
			url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
			success: function(response){
				if(response['response'] == 'success'){
					for(var i = 0; response['data_handler'].length > i; i++){
						 dynamic_reason_type += '<tr>';
						 dynamic_reason_type += '<td width="" height="20" class=" borderBR">';
						 dynamic_reason_type += '<div align="center" class="padl5">';
						 dynamic_reason_type += '<a id = "reason_link" href = "" onclick = "return reason_type_click('+response['data_handler'][i].ID+')">'+response['data_handler'][i].Name+'</a>';
						 dynamic_reason_type += '</td>';
						 dynamic_reason_type += '<td width="" height="20" class=" borderBR">';
						 dynamic_reason_type += '<div align="center" class="padl5">';
						 dynamic_reason_type += '<input type = "submit" onclick = "return delete_record_reasontype('+response['data_handler'][i].ID+')" class = "btn" value = "Delete">';
						 dynamic_reason_type += '</td>';
						 dynamic_reason_type += '</tr>';
					}
					$("#dynamic_reason_type").html(dynamic_reason_type);
				}
			}
		});
}

function delete_record_reasontype(ID){
	var dynamic_reason_type = '';
		dynamic_reason_type += '<tr align="center" id = "">';
		dynamic_reason_type += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Reason Type Name</div></td>';
		dynamic_reason_type += '<td width="" height="20" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';
		dynamic_reason_type += '</tr>';
		
	if(confirm("Are you sure want to delete this record?")==false){
		return false;
	}else{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: {'request':'delete reason type','ID':ID},
			url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
			success: function(response){
				if(response['response'] == 'success'){
					for(var i = 0; response['data_handler'].length > i; i++){
						 dynamic_reason_type += '<tr>';
						 dynamic_reason_type += '<td width="" height="20" class=" borderBR">';
						 dynamic_reason_type += '<div align="center" class="padl5">';
						 dynamic_reason_type += '<a id = "reason_link" href = "" onclick = "return reason_type_click('+response['data_handler'][i].ID+')">'+response['data_handler'][i].Name+'</a>';
						 dynamic_reason_type += '</td>';
						 dynamic_reason_type += '<td width="" height="20" class=" borderBR">';
						 dynamic_reason_type += '<div align="center" class="padl5">';
						 dynamic_reason_type += '<input type = "submit" onclick = "return delete_record_reasontype('+response['data_handler'][i].ID+')" class = "btn" value = "Delete">';
						 dynamic_reason_type += '</td>';
						 dynamic_reason_type += '</tr>';
					}
					$("#dynamic_reason_type").html(dynamic_reason_type);
				}
			}
		});
	}
}

function reason_type_click(ID)
{
	$.ajax({
			type: 'post',
			dataType: 'json',
			data: {'request':'get reason type by id','ID':ID},
			url: 'pages/datamanagement/system_param_call_ajax/reasons_call_ajax.php',
			success: function(response){
				if(response['response'] == 'success'){
					var ID 			= response['data_handler'].ID
					var Name 		= response['data_handler'].Name;
					var ModuleID 	= response['data_handler'].ModuleID;
					var GroupType 	= response['data_handler'].GroupType;
					var ModeType 	= response['data_handler'].ModeType;
					var DocType 	= response['data_handler'].DocType;
					
					$("#reason_type_name").val(Name);
					$("#reason_type_ID").val(ID);
					$("#selection_module_type").val(ModuleID);
					
					if(ID == 9){
						//here for OR
						$("#TRIsTradeNonTrade").show();
						$("#TRModeType").show();
						//for automatic or manual...
						if(ModeType == 1){
							$("#ModeType").attr("checked","checked");
						}else{
							$("#ModeType").removeAttr("checked","checked");
						}
						
						//for trade or non-trade..
						if(DocType == 1){
							$("#isTradeNontrade").attr("checked","checked");
						}else{
							$("#isTradeNontrade").removeAttr("checked","checked");
						}
					}else{
						$("#TRIsTradeNonTrade").hide();
						$("#TRModeType").hide();
						$("#isTradeNontrade").removeAttr("checked","checked");
						$("#ModeType").removeAttr("checked","checked");
						if(GroupType == ""){
							$("#reason_type_group").val(3);
						}else{
							if(GroupType=="SF"){
								$("#reason_type_group").val(1);
							}else if(GroupType=="MV"){ 
								$("#reason_type_group").val(2);
							}else{
								$("#reason_type_group").val(3);
							}
						}
					}
					$("#reason_type_save").hide();
					$("#reason_type_update").show();
				}
			}
		})
	return false;
}