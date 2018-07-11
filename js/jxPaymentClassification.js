var xmlHttp
var xmlHttp2
var xmlHttp3

function showPage(str, 
					branchid, 
					fromdate,
					todate) 
{ 
	
	
	//This sub will populate a table with all the states and get the 
	//pagination built
	//Make the AJAX connection for both the navigation and content
	xmlHttp=GetXmlHttpObject()
	xmlHttp2=GetXmlHttpObject()
	xmlHttp3=GetXmlHttpObject()

	
	//If we cant do the request error out
	if (xmlHttp==null ) {
	 	alert ("Browser does not support HTTP Request")
	 	return
	}
	
	
	
	/*
	//First build the navigation panel

	


	//Once the page finished loading put it into the div
	
	
	*/
	
	//Build the url to call
	//Pass variables through the url
	
	blen  = document.frmValuationReport.cboBranch.length
	fromdate = document.frmValuationReport.txtStartDates.value;
	todate = document.frmValuationReport.txtEndDate.value;
	
	for (x = 0; x < blen; x++) {
		if(document.frmValuationReport.cboBranch[x].selected) {
			branchid = document.frmValuationReport.cboBranch[x].value
		}
	}
	
	// HTTP 1
	var url="includes/jxPaymentClassification.php"
	url=url+"?p="+str
	url=url+"&t=con"
	url=url+"&b="+branchid
	url=url+"&fd="+fromdate
	url=url+"&td="+todate
	
	//Once the page finished loading put it into the div
	xmlHttp.onreadystatechange=stateChanged 
	
	//Get the php page
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)

	// HTTP 2	
	var url="includes/jxPaymentClassification.php"
	url=url+"?p="+str
	url=url+"&t=pageleft"
	url=url+"&b="+branchid
	url=url+"&fd="+fromdate
	url=url+"&td="+todate

	xmlHttp2.onreadystatechange=navDone 

	xmlHttp2.open("GET",url,true)
	xmlHttp2.send(null)

	// HTTP 3
	var url="includes/jxPaymentClassification.php"
	url=url+"?p="+str
	url=url+"&t=pageright"
	url=url+"&b="+branchid
	url=url+"&fd="+fromdate
	url=url+"&td="+todate

	xmlHttp3.onreadystatechange=navDone2 
	
	xmlHttp3.open("GET",url,true)
	xmlHttp3.send(null)
	
}

function stateChanged() { 
	//IF this is getting called when the page is done loading the states then output the div
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgContent").innerHTML=xmlHttp.responseText 
	} 
}

function navDone() { 
	//IF this is getting called when the page is done loading then fill the pagination div
	if (xmlHttp2.readyState==4 || xmlHttp2.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgNavigation").innerHTML=xmlHttp2.responseText 
	} 
}


function navDone2() { 
	//IF this is getting called when the page is done loading then fill the pagination div
	if (xmlHttp3.readyState==4 || xmlHttp3.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgRecord").innerHTML=xmlHttp3.responseText 
	} 
}



function GetXmlHttpObject() {
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




