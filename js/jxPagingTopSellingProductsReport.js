var xmlHttp
var xmlHttp2
var xmlHttp3
function showPage(str, fdate, tdate, ftime, ttime, product) 
{ 
	//Function that gets called
	//Currently we only call one other sub, but this could change
	
	showStates(str, fdate, tdate, ftime, ttime, product)
}

function showStates(str, fdate, tdate, ftime, ttime, product) 
{
	//This sub will populate a table with all the states and get the 
	//pagination built
	//Make the AJAX connection for both the navigation and content
	xmlHttp=GetXmlHttpObject()
	xmlHttp2=GetXmlHttpObject()
	xmlHttp3=GetXmlHttpObject()
	
	//If we cant do the request error out
	if (xmlHttp==null || xmlHttp2==null ) {
	 	alert ("Browser does not support HTTP Request")
	 	return
	}
	
	//First build the navigation panel
	var url="includes/jxPagingTopSellingProductsReport.php"
	url=url+"?p="+str
	url=url+"&t=nav"
	url=url+"&sid="+Math.random()
	url=url+"&fdate="+fdate
	url=url+"&tdate="+tdate
	url=url+"&ftime="+ftime
	url=url+"&ttime="+ttime
	url=url+"&product="+product

	//Once the page finished loading put it into the div
	xmlHttp2.onreadystatechange=navDone 
	xmlHttp3.onreadystatechange=navDone2 

	//Get the php page
	xmlHttp2.open("GET",url,true)
	xmlHttp2.send(null)

	var url="includes/jxPagingTopSellingProductsReport.php"
	url=url+"?p="+str
	url=url+"&t=num"
	url=url+"&sid="+Math.random()
	url=url+"&fdate="+fdate
	url=url+"&tdate="+tdate
	url=url+"&ftime="+ftime
	url=url+"&ttime="+ttime
	url=url+"&product="+product
	
	xmlHttp3.open("GET",url,true)
	xmlHttp3.send(null)
	
	//Build the url to call
	//Pass variables through the url
	var url="includes/jxPagingTopSellingProductsReport.php"
	url=url+"?p="+str
	url=url+"&t=con"
	url=url+"&sid="+Math.random()
	url=url+"&fdate="+fdate
	url=url+"&tdate="+tdate
	url=url+"&ftime="+ftime
	url=url+"&ttime="+ttime
	url=url+"&product="+product
	
	//Once the page finished loading put it into the div
	xmlHttp.onreadystatechange=stateChanged
	
	//Get the php page
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function navDone() 
{ 
	//IF this is getting called when the page is done loading then fill the pagination div
	if (xmlHttp2.readyState==4 || xmlHttp2.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgNavigation").innerHTML=xmlHttp2.responseText 
	} 
}

function navDone2() 
{ 
	//IF this is getting called when the page is done loading then fill the pagination div
	if (xmlHttp3.readyState==4 || xmlHttp3.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgRecord").innerHTML=xmlHttp3.responseText 
	} 
}

function stateChanged() 
{ 
	//IF this is getting called when the page is done loading the states then output the div
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgContent").innerHTML=xmlHttp.responseText 
	} 
}

function GetXmlHttpObject() 
{
	//Determine what browser we are on and make a httprequest connection for ajax
	var xmlHttp=null;

	try {
	 	// Firefox, Opera 8.0+, Safari
	 	xmlHttp=new XMLHttpRequest();
	}
	catch (e) {
	 	//Internet Explorer
	 	try {
	  		xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
	  	}
	 	catch (e) {
	  		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	  	}
	}
	
	return xmlHttp;
}


function openPopUp(obj1, obj2, obj3, obj4, obj5) 
{

	var objWin;
	
		popuppage = "pages/inventory/inv_TopSellingProductsPrint.php?fdate="+obj1+"&tdate="+obj2+"&ftime="+obj3+"&ttime="+obj4+"&product="+obj5
		
		if (!objWin) 
		{			
			objWin = NewWindow(popuppage,'printps','800','500','yes');
		}
		
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


function xvalidation(){
	//var s_date = Date.parse(document.getElementById("txtFromDate").value);
    //var sdate = new Date(s_date); 
    //var e_date = Date.parse(document.getElementById("txtToDate").value);
    //var edate = new Date(e_date);
	//
	//var now = new Date();
	//var now_day = now.getDate();
	//var now_month = now.getMonth() + 1;
	//var now_year = now.getFullYear();
	//var now_date = now_month + "/" + now_day + "/" + now_year;
	//
	//if(getDateObject(document.getElementById("txtFromDate").value, "/") < getDateObject(now_date, "/")){			
	//	alert("Start date should be current or future date.");
	//	return false;
	//}
	//
	////javascript:RemoveInvalidChars(txtMaxAvail1);
	//if(sdate > edate){			
	//	alert("End date should be the same or later than Start date.");
	//	return false;
	//}
	return true;
}


function getDateObject(dateString,dateSeperator)
{
	//This function return a date object after accepting 
	//a date string ans dateseparator as arguments
	var curValue=dateString;
	var sepChar=dateSeperator;
	var curPos=0;
	var cDate,cMonth,cYear;

	//extract day portion
	curPos=dateString.indexOf(sepChar);
	cMonth=dateString.substring(0,curPos);
	
	//extract month portion				
	endPos=dateString.indexOf(sepChar,curPos+1);			
	cDate=dateString.substring(curPos+1,endPos);

	//extract year portion				
	curPos=endPos;
	endPos=curPos+5;			
	cYear=curValue.substring(curPos+1,endPos);
	
	//Create Date Object
	dtObject=new Date(cYear,cMonth,cDate);	
	return dtObject;
}