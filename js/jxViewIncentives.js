


function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function openPopUp(objID, objInc) 
{
	var objWin;
		popuppage = "pages/leaderlist/link_incentives.php?ID=" + objID
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','1500','700','yes');
		}
		
		return false;  		
}	


