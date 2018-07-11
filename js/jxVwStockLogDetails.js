function validatePrint(id, page,wid,fromDate,toDate) 
{  


  	pagetoprint = "pages/inventory/inv_vwStockLogDetailsPrint.php?pid="+id+"&wid="+wid+"&fdte="+fromDate+"&tdte="+toDate+"";
	
	    NewWindow(pagetoprint,'printps2','850','1100','yes');
	
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
