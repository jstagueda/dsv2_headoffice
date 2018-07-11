var xmlHttp
var xmlHttp2
var xmlHttp3

function showPage(str, 
					sdate, 
					edate,
					sbranch,
					ebranch,
					sdealer,
					edealer,
					sprod,
					eprod,
					sid,
					eid,
					group) 
{ 
	
	//This sub will populate a table with all the states and get the 
	//pagination built
	//Make the AJAX connection for both the navigation and content
	xmlHttp=GetXmlHttpObject()
	xmlHttp2=GetXmlHttpObject()
	xmlHttp3=GetXmlHttpObject()
	/**/
	
	//If we cant do the request error out
	if (xmlHttp==null ) {
	 	alert ("Browser does not support HTTP Request")
	 	return
	}
	
	//Build the url to call
	//Pass variables through the url
	
	
	sdate = document.frmBOR.txtStartDates.value
	
	/*for (x = 0; x < sdatelen; x++) {
		if(document.frmBOR.txtStartDates[x].selected) {
			sdate = document.frmBOR.txtStartDates[x].value
		}
	}
    alert(document.frmBOR.txtStartDates.value)*/
	edate = document.frmBOR.txtEndDates.value
	
	/*for (x = 0; x < edatelen; x++) {
		if(document.frmBOR.txtEndDates[x].selected) {
			edate = document.frmBOR.txtEndDates[x].value
		}
	}*/	
	
	sbranchlen = document.frmBOR.cboBranchfrom.length
	
	for (x = 0; x < sbranchlen; x++) {
		if(document.frmBOR.cboBranchfrom[x].selected) {
			sbranch = document.frmBOR.cboBranchfrom[x].value
		}
	}	
	
	ebranchlen = document.frmBOR.cboBranchto.length
	
	for (x = 0; x < ebranchlen; x++) {
		if(document.frmBOR.cboBranchto[x].selected) {
			ebranch = document.frmBOR.cboBranchto[x].value
		}
	}		
	
	sdealerlen = document.frmBOR.cboDealerFrom.length
	
	for (x = 0; x < sdealerlen; x++) {
		if(document.frmBOR.cboDealerFrom[x].selected) {
			sdealer = document.frmBOR.cboDealerFrom[x].value
		}
	}		
	
	edealerlen = document.frmBOR.cboDealerTo.length
	
	for (x = 0; x < edealerlen; x++) {
		if(document.frmBOR.cboDealerTo[x].selected) {
			edealer = document.frmBOR.cboDealerTo[x].value
		}
	}		
	
	sprodlen = document.frmBOR.cboProdFrom.length
	
	for (x = 0; x < sprodlen; x++) {
		if(document.frmBOR.cboProdFrom[x].selected) {
			sprod = document.frmBOR.cboProdFrom[x].value
		}
	}	
	
	eprodlen = document.frmBOR.cboProdTo.length
	
	for (x = 0; x < eprodlen; x++) {
		if(document.frmBOR.cboProdTo[x].selected) {
			eprod = document.frmBOR.cboProdTo[x].value
		}
	}		
	
	sidlen = document.frmBOR.cboSOfrom.length
	
	for (x = 0; x < sidlen; x++) {
		if(document.frmBOR.cboSOfrom[x].selected) {
			sid = document.frmBOR.cboSOfrom[x].value
		}
	}			
	
	eidlen = document.frmBOR.cboSOto.length
	
	for (x = 0; x < eidlen; x++) {
		if(document.frmBOR.cboSOto[x].selected) {
			eid = document.frmBOR.cboSOto[x].value
		}
	}	
	
	grouplen = document.frmBOR.cboGroup.length
	
	for (x = 0; x < grouplen; x++) {
		if(document.frmBOR.cboGroup[x].selected) {
			group = document.frmBOR.cboGroup[x].value
		}
	}		
	
	

	// HTTP 1
	var url="includes/jxBackOrderReport.php"
	url=url+"?p="+str
	url=url+"&t=con"
	url=url+"&sd="+sdate
	url=url+"&ed="+edate
	url=url+"&sb="+sbranch
	url=url+"&eb="+ebranch
	url=url+"&sdr="+sdealer
	url=url+"&edr="+edealer
	url=url+"&sp="+sprod
	url=url+"&ep="+eprod
	url=url+"&si="+sid
	url=url+"&ei="+eid
	url=url+"&g="+group

	
	
	//Once the page finished loading put it into the div
	xmlHttp.onreadystatechange=stateChanged 
	
	//Get the php page
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)

	// HTTP 2	
	var url="includes/jxBackOrderReport.php"
	url=url+"?p="+str
	url=url+"&t=pageleft"
	url=url+"&sd="+sdate
	url=url+"&ed="+edate
	url=url+"&sb="+sbranch
	url=url+"&eb="+ebranch
	url=url+"&sdr="+sdealer
	url=url+"&edr="+edealer
	url=url+"&sp="+sprod
	url=url+"&ep="+eprod
	url=url+"&si="+sid
	url=url+"&ei="+eid
	url=url+"&g="+group

	xmlHttp2.onreadystatechange=navDone 

	xmlHttp2.open("GET",url,true)
	xmlHttp2.send(null)

	// HTTP 3	
	var url="includes/jxBackOrderReport.php"
	url=url+"?p="+str
	url=url+"&t=pageright"
	url=url+"&sd="+sdate
	url=url+"&ed="+edate
	url=url+"&sb="+sbranch
	url=url+"&eb="+ebranch
	url=url+"&sdr="+sdealer
	url=url+"&edr="+edealer
	url=url+"&sp="+sprod
	url=url+"&ep="+eprod
	url=url+"&si="+sid
	url=url+"&ei="+eid
	url=url+"&g="+group

	xmlHttp3.onreadystatechange=navDone2 
	
	xmlHttp3.open("GET",url,true)
	xmlHttp3.send(null)
	


}

/*
function navDone2() { 
	//IF this is getting called when the page is done loading then fill the pagination div
	if (xmlHttp3.readyState==4 || xmlHttp3.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgRecord").innerHTML=xmlHttp3.responseText 
	} 
}
*/

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




