$(document).ready(function(){
$("#txtStartDate").datepicker();
$("#txtEndDate").datepicker();
});

function openPopUp() 
{
	var branches 	 = $("#branches").val();
	var start 		 = $("#txtStartDate").val();
	var end 		 = $("#txtEndDate").val();
	var dealer_from  = $("#dealer_from").val();
	var dealer_to 	 = $("#dealer_to").val();
	var product_from = $("#product_from").val();
	var product_to 	 = $("#product_to").val();
	var err_msg = "", error_count = 0;
	if(branches == 0){
		err_msg += "*Branch Required \n";
		error_count++;
	}
	
	if(start == ""){
		err_msg += "*Start Date Required \n";
		error_count++;
	}
	
	if(end == ""){
		err_msg += "*End Date Required \n";
		error_count++;
	}
	
	if(dealer_from == 0){
		err_msg += "*Dealer From Required \n";
		error_count++;
	}
	
	if(dealer_to == 0){
		err_msg += "*Dealer To Required \n";
		error_count++;
	}
	if(product_from == 0){
		err_msg += "*Product From Required \n";
		error_count++;
	}
	if(product_to == 0){
		err_msg += "*Product To Required \n";
		error_count++;
	}
	

	
	if(error_count == 0){
		var objWin;
		popuppage = "pages/ipm/backorderhoreportprint.php?BranchID="+ branches + "&StartDate=" + start + "&EndDate=" + end + "&DealerFrom=" + dealer_from + "&DealerTo=" + dealer_to  + "&productfrom=" + product_from  + "&productto=" + product_to ;
			
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','800','500','yes');
		}
		return false;  		
	}else{
		alert(err_msg);
	}
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}