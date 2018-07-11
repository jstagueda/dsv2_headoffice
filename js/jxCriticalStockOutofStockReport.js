var xmlHttp
var xmlHttp2
var xmlHttp3

function showPage(str, 
					warehouseid, 
					productid,
					pmgid,
					pcode,
					invstatus,
					qtyfrom,
					qtyto) 
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
	
	warehouseidlen = document.frmCSOS.cbowarehouse.length
	
	for (x = 0; x < warehouseidlen; x++) {
		if(document.frmCSOS.cbowarehouse[x].selected) {
			warehouseid = document.frmCSOS.cbowarehouse[x].value
		}
	}	
	
	productidlen = document.frmCSOS.cboproductline.length
	
	for (x = 0; x < productidlen; x++) {
		if(document.frmCSOS.cboproductline[x].selected) {
			productid = document.frmCSOS.cboproductline[x].value
		}
	}		
	
	pmgidlen = document.frmCSOS.cbopmg.length
	
	for (x = 0; x < pmgidlen; x++) {
		if(document.frmCSOS.cbopmg[x].selected) {
			pmgid = document.frmCSOS.cbopmg[x].value
		}
	}	
	
	pcode = document.frmCSOS.txtitemcode.value
	
	invstatuslen = document.frmCSOS.cboStatus.length
	
	for (x = 0; x < invstatuslen; x++) {
		if(document.frmCSOS.cboStatus[x].selected) {
			invstatus = document.frmCSOS.cboStatus[x].value
		}
	}
	
	qtyfrom = document.frmCSOS.txtqtyfrom.value
	qtyto = document.frmCSOS.txtqtyto.value	
	//alert(qtyfrom)	
	// HTTP 1
	var url="includes/jxCriticalStockOutofStockReport.php"
	url=url+"?p="+str
	url=url+"&t=con"
	url=url+"&warehouseid="+warehouseid
	url=url+"&productid="+productid
	url=url+"&pmgid="+pmgid
	url=url+"&pcode="+pcode
	url=url+"&invstatus="+invstatus
	url=url+"&qtyfrom="+qtyfrom
	url=url+"&qtyto="+qtyto

	
	//Once the page finished loading put it into the div
	xmlHttp.onreadystatechange=stateChanged 
	
	//Get the php page
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)

	// HTTP 2	
	var url="includes/jxCriticalStockOutofStockReport.php"
	url=url+"?p="+str
	url=url+"&t=pageleft"
	url=url+"&warehouseid="+warehouseid
	url=url+"&productid="+productid
	url=url+"&pmgid="+pmgid
	url=url+"&pcode="+pcode
	url=url+"&invstatus="+invstatus
	url=url+"&qtyfrom="+qtyfrom
	url=url+"&qtyto="+qtyto

	xmlHttp2.onreadystatechange=navDone 

	xmlHttp2.open("GET",url,true)
	xmlHttp2.send(null)

	// HTTP 3	
	var url="includes/jxCriticalStockOutofStockReport.php"
	url=url+"?p="+str
	url=url+"&t=pageright"
	url=url+"&warehouseid="+warehouseid
	url=url+"&productid="+productid
	url=url+"&pmgid="+pmgid
	url=url+"&pcode="+pcode
	url=url+"&invstatus="+invstatus
	url=url+"&qtyfrom="+qtyfrom
	url=url+"&qtyto="+qtyto

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




