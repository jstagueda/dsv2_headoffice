$ = jQuery.noConflict();

$(document).ready(function(){
	
	showPageList();
	
});

function showPageList(){
	var header = "";
		header += "<tr align='center' class='txtdarkgreenbold10' style='background:#FFDEF0;'>";
		header += "<td width='15%' class='txtpallete bdiv_r'><div align= 'center' class='padl5'>Promo Code</div></td>";
		header += "<td class='txtpallete bdiv_r'><div align='center' class='padl5'>Promo Title</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>Start Date</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>End Date</div></td>";
		header += "</tr>";
		var txtPromoCodeDesc = $("#txtPromoCodeDesc").val();
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {"request":"fetch data","txtPromoCodeDesc":txtPromoCodeDesc},
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_single_line.php',
		success: function(resp){
				//var i = 0; resp['fetch_data'].length > i; i++
			if(resp['response'] == 'successs'){
			//alert(resp['fetch_data']);
				for(var i = 0; resp['fetch_data'].length > i; i++){
					//<a href='javascript:void(0)' onclick='return openPopUp($ID)' class='txtnavgreenlink'>$row->Code</a>
					header += "<tr align='center' class='trlist'>";
					header += "<td class='bdiv_r'><div align= 'center' class=''><a href='javascript:void(0)' onclick='return openPopUp("+resp['fetch_data'][i].ID+")' class=''>"+resp['fetch_data'][i].Code+"</div></td>";
					header += "<td class='bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].Description+"</div></td>";			
					header += "<td class='bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].StartDate+"</div></td>";			
					header += "<td class='bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].EndDate+"</div></td>";
					header += "</tr>";
				}
			}else{
				header += "<tr align='center' class='trlist'>";
				header += "<td colspan='5' align='center' class='borderBR'><span class='txtredsbold'>No record(s) to display.</span></td>";
				header += "</tr>";
			}
			
			$("#DynamicTable").html(header);
			$("#pagination").html(resp['pagination'].page);
			return false;
		}
	});
}

function showPage(page)
{
	var start = $("#from_date_search").val();
	var end = $("#to_date_search").val()
	
	
	var header = "";
	header += "<tr align='center' class='txtdarkgreenbold10' style='background:#FFDEF0;'>";
	header += "<td width='15%' class='txtpallete bdiv_r'><div align= 'center' class='padl5'>Promo Code</div></td>";
	header += "<td class='txtpallete bdiv_r'><div align='center' class='padl5'>Promo Title</div></td>";			
	header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>Start Date</div></td>";			
	header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>End Date</div></td>";
	header += "</tr>";
									
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'fetch data', 'page': page},
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_single_line.php',
		success: function(resp){
				//var i = 0; resp['fetch_data'].length > i; i++
			if(resp['response'] == 'successs'){
			//alert(resp['fetch_data']);
				for(var i = 0; resp['fetch_data'].length > i; i++){
					//<a href='javascript:void(0)' onclick='return openPopUp($ID)' class='txtnavgreenlink'>$row->Code</a>
					header += "<tr align='center' class='trlist'>";
					header += "<td class=' bdiv_r'><div align= 'center' class=''><a href='javascript:void(0)' onclick='return openPopUp("+resp['fetch_data'][i].ID+")' class=''>"+resp['fetch_data'][i].Code+"</div></td>";
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].Description+"</div></td>";			
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].StartDate+"</div></td>";			
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].EndDate+"</div></td>";
					header += "</tr>";
				}
			}else{
				header += "<tr align='center' class='trlist'>";
				header += "<td colspan='5' align='center' class='borderBR'><span class='txtredsbold'>No record(s) to display.</span></td>";
				header += "</tr>";
			}
			
			$("#DynamicTable").html(header);
			$("#pagination").html(resp['pagination'].page);
			return false;
		}
	});
	//alert(page);
	//return false;
}

function xbtnSearch()
{
	//alert('xx');
	//return false;
	var txtPromoCodeDesc = $("#txtPromoCodeDesc").val();
	var txtProductCode	 = $("#txtProductCode").val();
	var header = "";
		header += "<tr align='center' class='txtdarkgreenbold10' style='background:#FFDEF0;'>";
		header += "<td width='15%' class='txtpallete bdiv_r'><div align= 'center' class='padl5'>Promo Code</div></td>";
		header += "<td class='txtpallete bdiv_r'><div align='center' class='padl5'>Promo Title</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>Start Date</div></td>";			
		header += "<td width='10%' class='txtpallete bdiv_r'><div align='center' class='padl5'>End Date</div></td>";
		header += "</tr>";
									
	
	$.ajax({
		type: 'post',
		dataType: 'json',
		data: {'request':'fetch data', 'txtPromoCodeDesc':txtPromoCodeDesc,'txtProductCode':txtProductCode},
		url: 'pages/leaderlist/leaderlist_call_ajax/ajax_single_line.php',
		success: function(resp){
				//var i = 0; resp['fetch_data'].length > i; i++
			if(resp['response'] == 'successs'){
			//alert(resp['fetch_data']);
				for(var i = 0; resp['fetch_data'].length > i; i++){
					//<a href='javascript:void(0)' onclick='return openPopUp($ID)' class='txtnavgreenlink'>$row->Code</a>
					header += "<tr align='center' class='trlist'>";
					header += "<td class=' bdiv_r'><div align= 'center' class=''><a href='javascript:void(0)' onclick='return openPopUp("+resp['fetch_data'][i].ID+")' class=''>"+resp['fetch_data'][i].Code+"</div></td>";
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].Description+"</div></td>";			
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].StartDate+"</div></td>";			
					header += "<td class=' bdiv_r'><div align='center' class=''>"+resp['fetch_data'][i].EndDate+"</div></td>";
					header += "</tr>";
				}
			}else{
				header += "<tr align='center' class='trlist'>";
				header += "<td colspan='5' align='center' class='borderBR'><span class='txtredsbold'>No record(s) to display.</span></td>";
				header += "</tr>";
			}
			
			$("#DynamicTable").html(header);
			header = "";
			$("#pagination").html(resp['pagination'].page);
			return false;
		}
	});
	return false;
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp(objID) 
{
	var objWin;
		popuppage = "pages/leaderlist/promo_singleLineDetails.php?prmsid=" + objID;
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','1500','700','yes');
		}
		
		return false;  		
}
