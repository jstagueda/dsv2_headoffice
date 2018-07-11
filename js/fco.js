$(document).ready(function(){
	$("#from_date").datepicker();
	$("#to_date").datepicker();
	$("#to_date_search").datepicker();
	$("#from_date_search").datepicker();
	$("#Update").hide();
	$("#Cancel").hide();
	
	fetch_data();
	
	$('.padl5 a').live('click',function(){
		$("#Save").hide();
		$("#Update").show();
	
		var id = $(this).data('id');
		$.ajax({
			type:'post',
			dataType:'json',
			data:{'request':'getting information' , 'ID':id},
			url:'pages/datamanagement/system_param_call_ajax/FCOajax.php',
			success: function(resp){

				if(resp['result'].response=="Success"){
					$("#Cancel").show();
					$("#FCO_ID").val(id);
					$("#Year").val(resp['fetch_data'][0].Year);
					$("#period").val(resp['fetch_data'][0].Period);
					$("#from_date").val(resp['fetch_data'][0].DateFrom);
					$("#to_date ").val(resp['fetch_data'][0].DateTo);
				}
			}
		})
	
	})
});
function fetch_data()
{
	var call_ajax = "";
		call_ajax += '<tr>';
		call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Year</div></td>';			
		call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Date From</div></td>';
		call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Date To</div></td>';
		call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Period</div></td>';			
		call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Action</div></td>';			
		call_ajax += '</tr>';
			
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'Fetch Data'},
		url: 'pages/datamanagement/system_param_call_ajax/FCOajax.php',
		success: function(resp){
			if(resp['result'].response == 'Success'){
				for(var i = 0; resp['fetch_data'].length > i; i++){
					var  ID        = resp['fetch_data'][i].ID;
					var  year      = resp['fetch_data'][i].Year;
					var  period    = resp['fetch_data'][i].Period;
					var  datefrom  = resp['fetch_data'][i].DateFrom;
					var  dateto	   = resp['fetch_data'][i].DateTo;
					//alert(ID);
					call_ajax += '<tr>';
					call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5"><a href="javascript:void(0);"  data-ID= "'+ID+'" >'+year+'</a></div></td>';
					call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5">'+datefrom+'</div></td>';
					call_ajax +='<td height="20" class="borderBR"><div align="left" class="padr5">'+dateto+'</div></td>';
					call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5">'+period+'</div></td>';
					call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5"><input type = "submit" class = "btn" value = "Delete" onclick = "return btn_delete('+ID+');"></div></td>';
					call_ajax += '</tr>';
				}
			}
			$("#fetching_data_please_wait").remove();
			$("#call_ajax").html(call_ajax);
			var pagination = resp['pagination'].page;
			$("#pagination").html(pagination);
			call_ajax = "";
				return false;
		}
	});
}

function SaveClick(validation){

		if(validation == 3){
			if(confirm("are you sure want to cancel this transaction?")==  false){
				return false;
			}else{
				$("#FCO_ID").val("")
				$("#from_date").val("");
				$("#to_date").val("");
				$("#Year").val("");
				$("#period").val("");
				$("#Save").show();
				$("#Update").hide();
				$("#Cancel").hide();
				return false;
			}
		}
		var call_ajax = "";
			call_ajax += '<tr>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Year</div></td>';			
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Date From</div></td>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Date To</div></td>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Period</div></td>';			
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Action</div></td>';			
			call_ajax += '</tr>';
		
		var err_msg = "", err_cnt = 0;
		var from_date    	 = $("#from_date").val();
		var to_date      	 = $("#to_date").val();
		var Year     		 = $("#Year").val();
		var period   		 = $("#period").val();
		
		if(from_date == ""){
			err_msg += "*Date From is required. \n";
			err_cnt++;
		}
		
		if(to_date == ""){
			err_msg += "* Date To is required. \n";
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
					data : { 'request':'save','validation':validation,'year':Year, 'from_date':from_date,'to_date':to_date, 'period':period, 'ID':$("#FCO_ID").val()},
					url : 'pages/datamanagement/system_param_call_ajax/FCOajax.php',
					success: function(resp){
						if(resp['result'].response == 'Success'){
							for(var i = 0; resp['fetch_data'].length > i; i++){
								var  year      = resp['fetch_data'][i].Year;
								var  period    = resp['fetch_data'][i].Period;
								var  datefrom  = resp['fetch_data'][i].DateFrom;
								var  dateto	   = resp['fetch_data'][i].DateTo;
								var  ID	   = resp['fetch_data'][i].ID;
								call_ajax += '<tr>';
								call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5"><a href="javascript:void(0);"  data-ID= "'+ID+'" >'+year+'</a></div></td>';
								call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5">'+datefrom+'</div></td>';
								call_ajax +='<td height="20"  class="borderBR"><div align="left" class="padr5">'+dateto+'</div></td>';
								call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5">'+period+'</div></td>';
								call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5"><input type = "submit" class = "btn" value = "Delete" onclick = "return btn_delete('+ID+');"></div></td>';
								call_ajax += '</tr>';
							}
						}
							$("#fetching_data_please_wait").remove();
							var pagination = resp['pagination'].page;
							$("#call_ajax").html(call_ajax);
							$("#pagination").html(pagination);
								$("#FCO_ID").val("")
								$("#from_date").val("");
								$("#to_date").val("");
								$("#Year").val("");
								$("#period").val("");
							//show buttons
								$("#Save").show();
								$("#Update").hide();
								$("#Cancel").hide();
							call_ajax = "";
							return false;
						}
				
				});
				return false;
			}
		}
}

function btn_delete(xID)
{
	var call_ajax = "";
			call_ajax += '<tr>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Year</div></td>';			
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Date From</div></td>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Date To</div></td>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Period</div></td>';			
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Action</div></td>';			
			call_ajax += '</tr>';
			
	if(confirm("are you sure want to delete this transation?")==false){
		return false;
	}else{
		$.ajax({
			type:'post',
			dataType:'json',
			data: {'ID':xID, 'request':'delete'},
			url : 'pages/datamanagement/system_param_call_ajax/FCOajax.php',
			success: function(resp){
						if(resp['result'].response == 'Success'){
							for(var i = 0; resp['fetch_data'].length > i; i++){
								var  year      = resp['fetch_data'][i].Year;
								var  period    = resp['fetch_data'][i].Period;
								var  datefrom  = resp['fetch_data'][i].DateFrom;
								var  dateto	   = resp['fetch_data'][i].DateTo;
								var  ID	   = resp['fetch_data'][i].ID;
								call_ajax += '<tr>';
								call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5"><a href="javascript:void(0);">'+year+'</a></div></td>';
								call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5">'+datefrom+'</div></td>';
								call_ajax +='<td height="20"  class="borderBR"><div align="left" class="padr5">'+dateto+'</div></td>';
								call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5">'+period+'</div></td>';
								call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5"><input type = "submit" class = "btn" value = "Delete" onclick = "return btn_delete('+ID+');"></div></td>';
								call_ajax += '</tr>';
							}
						}
						
							$("#fetching_data_please_wait").remove();
							$("#call_ajax").html(call_ajax);
							var pagination = resp['pagination'].page;
							$("#pagination").html(pagination);
							call_ajax = "";
							return false;
			}	
		})
	}
	return false;
}

function showPage(page)
{
	var call_ajax = "";
			call_ajax += '<tr>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Year</div></td>';			
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Date From</div></td>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Date To</div></td>';
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Period</div></td>';			
			call_ajax += '<td height="20" width = "10%" class="txtpallete borderBR"><div align="left" class="padl5">Action</div></td>';			
			call_ajax += '</tr>';
									
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'Fetch Data', 'page': page},
		url: 'pages/datamanagement/system_param_call_ajax/FCOajax.php',
		success: function(resp){
			if(resp['result'].response == 'Success'){
				for(var i = 0; resp['fetch_data'].length > i; i++){
					var  ID        = resp['fetch_data'][i].ID;
					var  year      = resp['fetch_data'][i].Year;
					var  period    = resp['fetch_data'][i].Period;
					var  datefrom  = resp['fetch_data'][i].DateFrom;
					var  dateto	   = resp['fetch_data'][i].DateTo;
					//alert(ID);
					call_ajax += '<tr>';
					call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5"><a href="javascript:void(0);"  data-ID= "'+ID+'" >'+year+'</a></div></td>';
					call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5">'+datefrom+'</div></td>';
					call_ajax +='<td height="20" class="borderBR"><div align="left" class="padr5">'+dateto+'</div></td>';
					call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5">'+period+'</div></td>';
					call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5"><input type = "submit" class = "btn" value = "Delete" onclick = "return btn_delete('+ID+');"></div></td>';
					call_ajax += '</tr>';
				}
			}
			$("#fetching_data_please_wait").remove();
			$("#call_ajax").html(call_ajax);
			var pagination = resp['pagination'].page;
			$("#pagination").html(pagination);
			call_ajax = "";
				return false;
		}
	});
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
				url: 'pages/datamanagement/system_param_call_ajax/FCOajax.php',
				success: function(resp){
					if(resp['result'].response == 'Success'){
						for(var i = 0; resp['fetch_data'].length > i; i++){
							var  ID        = resp['fetch_data'][i].ID;
							var  year      = resp['fetch_data'][i].Year;
							var  period    = resp['fetch_data'][i].Period;
							var  datefrom  = resp['fetch_data'][i].DateFrom;
							var  dateto	   = resp['fetch_data'][i].DateTo;
							//alert(ID);
							call_ajax += '<tr>';
							call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5"><a href="javascript:void(0);"  data-ID= "'+ID+'" >'+year+'</a></div></td>';
							call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5">'+datefrom+'</div></td>';
							call_ajax +='<td height="20" class="borderBR"><div align="left" class="padr5">'+dateto+'</div></td>';
							call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5">'+period+'</div></td>';
							call_ajax +='<td height="20" class="borderBR"><div align="left" class="padl5"><input type = "submit" class = "btn" value = "Delete" onclick = "return btn_delete('+ID+');"></div></td>';
							call_ajax += '</tr>';
						}
					}
					$("#fetching_data_please_wait").remove();
					$("#call_ajax").html(call_ajax);
					var pagination = resp['pagination'].page;
					$("#pagination").html(pagination);
					call_ajax = "";
				return false;
				}
					})
				}
				return false;
}
