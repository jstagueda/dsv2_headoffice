$(document).ready(function(){
	$("#Update").hide();
	$("#Cancel").hide();
	$("#from_date").datepicker();
	$("#to_date").datepicker();
	$("#from_date_search").datepicker();
	$("#to_date_search").datepicker();
	
	// call_ajax
	fetch_data();	
	
	$(".padl5 a").live("click",function(){
		$("#Save").hide();
		$("#Update").show();
	
		//$("#holiday").attr("readonly","readyonly");
		var id = $(this).data('id');
		$.ajax({
			type:'post',
			dataType:'json',
			data:{'request':'getting information' , 'ID':id},
			url:'pages/datamanagement/system_param_call_ajax/HolidayAjax.php',
			success: function(resp){
				// Holiday Header
				var status = 0;
				
				if(resp['Holiday_Data'].StatusName == 'YES'){
					status = 1;
				}else{
					status = 2;
				}
				
				if(resp['holiday_result'].response=="success"){
					$("#Cancel").show();
					$("#HolidayID").val(resp['Holiday_Data'].ID);
					$("#holiday").val(resp['Holiday_Data'].Description);
					$("#from_date").val(resp['Holiday_Data'].Date);
					$("#to_date ").val(resp['Holiday_Data'].Date_From);	
					$("#branch_close ").val(status);	
					// $("#save").removeAttr("disabled","disabled");
				}
			}
		})
	})
});

function fetch_data()
{
	var call_ajax = "";
	call_ajax += '<tr>';
	call_ajax += '<td height="20" width = "30%" class="txtpallete borderBR"><div align="left" class="padl5">Holiday Description</div></td>';			
	call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">Date To</div></td>';
	call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">Date From</div></td>';
	call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Branch is Closed?</div></td>';			
	call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';			
	call_ajax += '</tr>';
									
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'Fetch Data'},
		url: 'pages/datamanagement/system_param_call_ajax/HolidayAjax.php',
		success: function(resp){
			if(resp['result'].response == 'Success'){
				for(var i = 0; resp['fetch_data'].length > i; i++){
					var  StatusName    = resp['fetch_data'][i].StatusName;
					var  Description   = resp['fetch_data'][i].Description;
					var  Date   	   = resp['fetch_data'][i].Date;
					var  Date_From     = resp['fetch_data'][i].Date_From;
					var  StatusName    = resp['fetch_data'][i].StatusName;
					var  ID    = resp['fetch_data'][i].ID;
					
					call_ajax += '<tr>';
					call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5"><a href = "javascript:void(0)" data-ID="'+ID+'">'+Description+'</div></td>';			
					call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5">'+Date_From+'</div></td>';
					call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5">'+Date+'</div></td>';
					call_ajax += '<td height="20"  class="borderBR"><div align="left" class="padr5">'+StatusName+'</div></td>';
					call_ajax += '<td height="20"  class="borderBR"><div align="center" class="padr5"><input type = "submit" class ="btn" value = "Delete" onclick = "return btndelete('+ID+');"></div></td>';
					call_ajax += '</tr>';
				}
				var pagination = resp['pagination'].page;
				$("#pagination").html(pagination);
				$("#fetching_data_please_wait").remove();
				$("#call_ajax").html(call_ajax);
				call_ajax = "";
			}
			return false;
		}
	});
}

function SaveClick(validation){

if(validation == 3){ // Cancel
	if(confirm("Are you sure want to cancel this transaction?")==false){
		return false;
	}else{
		/*clear fields*/
		$("#HolidayID").val("");
		$("#holiday").val("");
		$("#holiday").removeAttr("readonly","readonly");
		$("#from_date").val("");
		$("#to_date ").val("");	
		$("#branch_close ").val(0);	
		$("#Save").show();
		$("#Update").hide();
		$("#Cancel").hide();
		return false;
	}
}else{
		var call_ajax = "";
			call_ajax += '<tr>';
			call_ajax += '<td height="20" width = "30%" class="txtpallete borderBR"><div align="left" class="padl5">Holiday Description</div></td>';			
			call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">Date From</div></td>';
			call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">Date To</div></td>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Branch is Closed?</div></td>';	
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';						
			call_ajax += '</tr>';
		
		var err_msg = "", err_cnt = 0;
		var holiday      = $("#holiday").val();
		var from_date    = $("#from_date").val();
		var to_date      = $("#to_date").val();
		var branch_close = $("#branch_close").val();
		
		if(holiday == ""){
			err_msg += "*Holiday field is required. \n";
			err_cnt++;
		}
		
		if(from_date == ""){
			err_msg += "*From date is required. \n";
			err_cnt++;
		}
		
		if(to_date == ""){
			err_msg += "*To Date is required. \n";
			err_cnt++;
		}
		
		if(branch_close == ""){
			err_msg += "*Branch Close is required. \n";
			err_cnt++;
		}
		
		if(err_cnt > 0){
			alert(err_msg);
			return false;
		}else{
			if(confirm("Are you sure want to save this transaction?")==false){
				return false
			}else{
				$.ajax({
					type: 'post',
					dataType: 'json',
					data : { 'request':'save','validation':validation,'holiday':holiday, 'from_date':from_date,'to_date':to_date, 'branch_close':branch_close,HolidayID:$("#HolidayID").val()},
					url : 'pages/datamanagement/system_param_call_ajax/HolidayAjax.php',
						success: function(resp){
								if(resp['result'].response == 'Success'){
									for(var i = 0; resp['fetch_data'].length > i; i++){
										var  StatusName    = resp['fetch_data'][i].StatusName
										var  Description   = resp['fetch_data'][i].Description
										var  Date   	   = resp['fetch_data'][i].Date
										var  Date_From     = resp['fetch_data'][i].Date_From
										var  StatusName    = resp['fetch_data'][i].StatusName
										var  ID    = resp['fetch_data'][i].ID
										
										call_ajax += '<tr>';
										call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5"><a href = "javascript:void(0)" data-ID="'+ID+'">'+Description+'</div></td>';			
										call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5">'+Date_From+'</div></td>';
										call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5">'+Date+'</div></td>';
										call_ajax += '<td height="20"  class="borderBR"><div align="left" class="padr5">'+StatusName+'</div></td>';
										call_ajax += '<td height="20"  class="borderBR"><div align="center" class="padr5"><input type = "submit" class ="btn" value = "Delete" onclick = "return btndelete('+ID+');"></div></td>';
										call_ajax += '</tr>';
									}
									$("#fetching_data_please_wait").remove();
									$("#call_ajax").html(call_ajax);
									var pagination = resp['pagination'].page;
									$("#pagination").html(pagination);
									call_ajax = "";
									/*clear fields*/
									$("#HolidayID").val("");
									$("#holiday").val("");
									$("#holiday").removeAttr("disabled","disabled");
									$("#from_date").val("");
									$("#to_date ").val("");	
									$("#branch_close ").val(0);	
									//show buttons
									$("#Save").show();
									$("#Update").hide();
									$("#Cancel").hide();
								}else if(resp['result'].response == 'Code Already Exist'){
									alert('*'+resp['result'].response)
								}
							return false;
					}
				
				});
				return false;
			}
		}
}
}


function btndelete(ID)
{
	var call_ajax = "";
			call_ajax += '<tr>';
			call_ajax += '<td height="20" width = "30%" class="txtpallete borderBR"><div align="left" class="padl5">Holiday Description</div></td>';			
			call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">Date From</div></td>';
			call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">Date To</div></td>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Branch is Closed?</div></td>';	
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';						
			call_ajax += '</tr>';
			//alert(call_ajax)
	if(confirm("Are you sure want to delete this transaction?") == false){
		return false;
	}else{
		$.ajax({
			type: 'post',
			dataType: 'json',
			data: {'request':'delete', 'ID':ID},
			url: 'pages/datamanagement/system_param_call_ajax/HolidayAjax.php',
			success: function(resp){
				if(resp['holiday_result'].response == 'success'){
					for(var i = 0; resp['fetch_data'].length > i; i++){
						var  StatusName    = resp['fetch_data'][i].StatusName
						var  Description   = resp['fetch_data'][i].Description
						var  Date   	   = resp['fetch_data'][i].Date
						var  Date_From     = resp['fetch_data'][i].Date_From
						var  StatusName    = resp['fetch_data'][i].StatusName
						var  xID  		   = resp['fetch_data'][i].ID
						
						call_ajax += '<tr>';
						call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5"><a href = "javascript:void(0)" data-ID="'+xID+'">'+Description+'</div></td>';			
						call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5">'+Date_From+'</div></td>';
						call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5">'+Date+'</div></td>';
						call_ajax += '<td height="20"  class="borderBR"><div align="left" class="padr5">'+StatusName+'</div></td>';
						call_ajax += '<td height="20"  class="borderBR"><div align="center" class="padr5"><input type = "submit" class ="btn" value = "Delete" onclick = "return btndelete('+xID+');"></div></td>';
						call_ajax += '</tr>';
					}
						$("#call_ajax").html(call_ajax);
						var pagination = resp['pagination'].page;
						$("#pagination").html(pagination);
				}
				return false;
			}
		})
	}
	return false;
}

function Search_Item()
{

	var call_ajax = "";
		call_ajax += '<tr>';
		call_ajax += '<td height="20" width = "30%" class="txtpallete borderBR"><div align="left" class="padl5">Holiday Description</div></td>';			
		call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">Date From</div></td>';
		call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">Date To</div></td>';
		call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Branch is Closed?</div></td>';	
		call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';						
		call_ajax += '</tr>';
			
	var from_date = $("#from_date_search").val();
	var to_date = 	$("#to_date_search").val();
	var error_msg = "", error_cnt = 0;
	
		if(from_date == ""){
			error_msg += "*From date is required. \n";
			error_cnt++;
		}
		
		if(to_date == ""){
			error_msg += "*To Date is required. \n";
			error_cnt++;
		}
		
		if(error_cnt > 0){
			alert(error_msg);
			return false;
		}else{
			$.ajax({
				type: 'post',
				dataType: 'json',
				data: {'request':'search', 'start':from_date, 'end':to_date},
				url: 'pages/datamanagement/system_param_call_ajax/HolidayAjax.php',
				success: function(resp){
				if(resp['holiday_result'].response == 'success'){
					for(var i = 0; resp['fetch_data'].length > i; i++){
						var  StatusName    = resp['fetch_data'][i].StatusName
						var  Description   = resp['fetch_data'][i].Description
						var  Date   	   = resp['fetch_data'][i].Date
						var  Date_From     = resp['fetch_data'][i].Date_From
						var  StatusName    = resp['fetch_data'][i].StatusName
						var  xID  		   = resp['fetch_data'][i].ID
						
						call_ajax += '<tr>';
						call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5"><a href = "javascript:void(0)" data-ID="'+xID+'">'+Description+'</div></td>';			
						call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5">'+Date_From+'</div></td>';
						call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5">'+Date+'</div></td>';
						call_ajax += '<td height="20"  class="borderBR"><div align="left" class="padr5">'+StatusName+'</div></td>';
						call_ajax += '<td height="20"  class="borderBR"><div align="center" class="padr5"><input type = "submit" class ="btn" value = "Delete" onclick = "return btndelete('+xID+');"></div></td>';
						call_ajax += '</tr>';
					}
						$("#call_ajax").html(call_ajax);
						$("#from_date_search").val("");
						$("#to_date_search").val("");
						
				}else{
					call_ajax += '<tr align = "center"><td height="20" class="borderBR" colspan = "6"><div align="center" class="padl5">No record(s) result.</div></td></tr>';
					$("#call_ajax").html(call_ajax);
					$("#from_date_search").val("");
					$("#to_date_search").val("");
				}
				return false;
			}
			})
		}
	return false;
}

function showPage(page)
{
	var start = $("#from_date_search").val();
	var end = $("#to_date_search").val()
	
	var call_ajax = "";
	call_ajax += '<tr>';
	call_ajax += '<td height="20" width = "30%" class="txtpallete borderBR"><div align="left" class="padl5">Holiday Description</div></td>';			
	call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">Date To</div></td>';
	call_ajax += '<td height="20" width = "20%" class="txtpallete borderBR"><div align="left" class="padl5">Date From</div></td>';
	call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Branch is Closed?</div></td>';			
	call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="center" class="padl5">Action</div></td>';			
	call_ajax += '</tr>';
									
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'Fetch Data', 'page': page, 'start': start, 'end': end },
		url: 'pages/datamanagement/system_param_call_ajax/HolidayAjax.php',
		success: function(resp){
			if(resp['result'].response == 'Success'){
				for(var i = 0; resp['fetch_data'].length > i; i++){
					var  StatusName    = resp['fetch_data'][i].StatusName;
					var  Description   = resp['fetch_data'][i].Description;
					var  Date   	   = resp['fetch_data'][i].Date;
					var  Date_From     = resp['fetch_data'][i].Date_From;
					var  StatusName    = resp['fetch_data'][i].StatusName;
					var  ID    = resp['fetch_data'][i].ID;
					
					call_ajax += '<tr>';
					call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5"><a href = "javascript:void(0)" data-ID="'+ID+'">'+Description+'</div></td>';			
					call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5">'+Date_From+'</div></td>';
					call_ajax += '<td height="20" class="borderBR"><div align="left" class="padl5">'+Date+'</div></td>';
					call_ajax += '<td height="20"  class="borderBR"><div align="left" class="padr5">'+StatusName+'</div></td>';
					call_ajax += '<td height="20"  class="borderBR"><div align="center" class="padr5"><input type = "submit" class ="btn" value = "Delete" onclick = "return btndelete('+ID+');"></div></td>';
					call_ajax += '</tr>';
				}
				var pagination = resp['pagination'].page;
				$("#pagination").html(pagination);
				$("#fetching_data_please_wait").remove();
				$("#call_ajax").html(call_ajax);
				call_ajax = "";
			}
			return false;
		}
	});
	//alert(page);
	//return false;
}
