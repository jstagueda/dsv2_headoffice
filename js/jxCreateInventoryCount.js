$j = jQuery.noConflict();

// JavaScript Document
function trim(s)
{
	var l=0; var r=s.length -1;
	while(l < s.length && s[l] == ' ')
	{	
		l++; 
	}
	while(r > l && s[r] == ' ')
	{	
		r-=1;	
	}
	return s.substring(l, r+1);
}

function checkAll(bin) 
{
	var elms = document.frmInventoryCountDetails.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkIID[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
}

function confirmSave() 
{	
	msg = '';
	obj = document.frmInventoryCountDetails.elements;
	objiid = document.frmInventoryCountDetails.elements["chkIID[]"];
	
	if (objiid == undefined)
	{
		alert ('There are no details added.');
		return false;		
	}
	else
	{
		// TEXT BOXES		
		if (trim(obj["txtDocNo"].value) == '') msg += '   * Document No. \n';
	   
		if (msg != '')
		{ 
		  alert ('Please complete the following: \n\n' + msg);
		  return false;
		}
		else 
		{
			if (confirm('Are you sure you want to save this transaction?') == false)
				return false;
			else
				return true;
		}
	}
}

var http_request = false;
var mydiv = "ShowThis";

function makePOSTRequest(url, parameters)
{
	http_request = false;
	
	if (window.XMLHttpRequest) 
	{ 
		// Mozilla, Safari,...
		http_request = new XMLHttpRequest();
		
		if (http_request.overrideMimeType) 
		{
			// set type accordingly to anticipated content type
			//http_request.overrideMimeType('text/xml');
			http_request.overrideMimeType('text/html');
		}
	} 
	else if (window.ActiveXObject) 
	{ 
		// IE
		try 
		{
			http_request = new ActiveXObject("Msxml2.XMLHTTP");
		} 
		catch (e) 
		{
			try 
			{
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!http_request) 
	{
		alert('Cannot create XMLHTTP instance');
		return false;
	}
  
		http_request.onreadystatechange = alertContents;
		http_request.open('POST', url, true);
		http_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		http_request.setRequestHeader("Content-length", parameters.length);
		http_request.setRequestHeader("Connection", "close");
      http_request.send(parameters);
}

function alertContents() 
{
	if (http_request.readyState == 4) 
	{
		if (http_request.status == 200) 
		{
			//alert(http_request.responseText);
            result = http_request.responseText;
            document.getElementById(mydiv).innerHTML = result;            
		} 
		else 
		{
			alert('There was a problem with the request.');
		}
  	}
}

function checkRequired()
{
	var doc = document.frmInventoryCountDetails.txtDocNo
	var msg=''
	   
	if(trim(doc.value) == '')
	{
		msg += ' *Document No ';
	}
  
	if(msg != '')
	{
		alert ('Please complete the following: \n\n' + msg);	
		return false;
	}
	else
		return true;
}
   
function GetXmlHttpObject() 
{
	//Determine what browser we are on and make a httprequest connection for ajax
	var xmlHttp=null;

	try 
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e) 
	{
		//Internet Explorer
		try 
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e) 
		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	
	return xmlHttp;
}
	
function showDetails(wid, srch) 
{ 
	//This sub will populate a table with all the states and get the 
	//pagination built
	//Make the AJAX connection for both the navigation and content
	
	xmlHttpProd=GetXmlHttpObject()
	xmlHttpDetail=GetXmlHttpObject()
	xmlHttpWarehouse=GetXmlHttpObject()
	
	//If we cant do the request error out
	if (xmlHttpProd==null || xmlHttpDetail==null ) 
	{
		alert ("Browser does not support HTTP Request")
		return
	}
	
	//Build the url to call
	//Pass variables through the url
	var url="includes/jxCreateInventoryCount.php"
	url=url+"?mod=t"		
	url=url+"&wid="+wid
	url=url+"&srch="+srch
	
	//Once the page finished loading put it into the div
	xmlHttpDetail.onreadystatechange=navDone 
	//Get the php page
	xmlHttpDetail.open("GET",url,true)
	xmlHttpDetail.send(null)

	//Build the url to call
	//Pass variables through the url

	var url="includes/jxCreateInventoryCount.php"
	url=url+"?mod=p"			
	//Once the page finished loading put it into the div
	xmlHttpProd.onreadystatechange=stateChanged 
	//Get the php page
	xmlHttpProd.open("GET",url,true)
	xmlHttpProd.send(null)
	
	var url="includes/jxCreateInventoryCount.php"
	url=url+"?mod=w"			
	//Once the page finished loading put it into the div
	xmlHttpWarehouse.onreadystatechange=disableWarehouse 
	//Get the php page
	xmlHttpWarehouse.open("GET",url,true)
	xmlHttpWarehouse.send(null)				
}
	
function navDone() 
{ 
	//IF this is getting called when the page is done loading then fill the pagination div
	document.getElementById("dvProductList").innerHTML="<img border='0' src='images/ajax-loader.gif'>"
	if (xmlHttpDetail.readyState==4 || xmlHttpDetail.readyState=="complete") 
	{ 
		//Update the Div tag with the outputted text
		document.getElementById("dvProductList").innerHTML=xmlHttpDetail.responseText 
	} 
}
	
function stateChanged() 
{ 
	//IF this is getting called when the page is done loading the states then output the div
	
	document.getElementById("dvAdjustmentDetails").innerHTML="<img border='0' src='images/ajax-loader.gif'>"		
	if (xmlHttpProd.readyState==4 || xmlHttpProd.readyState=="complete") 
	{ 
		//Update the Div tag with the outputted text
		document.getElementById("dvAdjustmentDetails").innerHTML=xmlHttpProd.responseText 
	} 
}

function disableWarehouse() 
{ 
	//IF this is getting called when the page is done loading the states then output the div
	document.getElementById("dvWarehouse").innerHTML="<img border='0' src='images/ajax-loader.gif'>"	
	if (xmlHttpWarehouse.readyState==4 || xmlHttpWarehouse.readyState=="complete") 
	{ 
		//Update the Div tag with the outputted text
		document.getElementById("dvWarehouse").innerHTML=xmlHttpWarehouse.responseText 
	} 
}

function getInventoryCount(action, thisDiv) 
{
	 mydiv = thisDiv  
 	 //alert ('asdsa')
	 if (action == "Add")
	 {
		 var d = 0
		 objid   = document.frmList.elements["hdnInventoryID[]"];
		 objuom  = document.frmList.elements["cboUOM[]"];
		 objqty  = document.frmList.elements["txtquantity[]"];
		 objrsn  = document.frmList.elements["txtreason[]"];
		 objwid  = document.frmList.elements["hdnlstWarehouse"];
		 objsrch = document.frmList.elements["hdnSearch"];
		 
		 var arrInventory  = new Array(),
			 arrQuantity   = new Array(),
			 arrReason     = new Array(),
			 arrUOM        = new Array()
		 
		 if (objid.length > 0) 
		 {   				 
			 for (var c = 0; c < objid.length; c++)
			 {
				 if (objqty[c].value != '')
				 {
					var value = 0;
					for (var m = 0; m < objuom[c].length; m++)
					{
						if (objuom[c][m].selected)
						{
							value = objuom[c][m].value;
						}
					}
					arrUOM[d] 		= value;
					arrInventory[d] = objid[c].value;
					arrQuantity[d]  = objqty[c].value;
					arrReason[d] 	= objrsn[c].value;
					d++;
				 }
			 }
		 } 
		 else 
		 {
			var value = 0;
			for (var m = 0; m < objuom.length; m++)
			{
				if (objuom[m].selected)
				{
					value = objuom[m].value;
				}
			}
			
			arrInventory[0] = objid.value;
			arrQuantity[0]  = objqty.value;
			arrReason[0] 	= objrsn.value	;	
			arrUOM[0] 		= value;
			d++;
		 }
		 
		 if (d == 0)
		 {
			 alert ('There are no details to be added.');			
		 }
		 else
		 {
			  poststr = "action="   + encodeURI(action) + 
						"&hdnInventoryID=" + encodeURI(arrInventory) +
						"&hdnQuantity=" + encodeURI(arrQuantity) +
						"&hdnReason=" + encodeURI(arrReason) + 
						"&hdnUOM=" + encodeURI(arrUOM);
			 
			  makePOSTRequest('includes/jxCreateInventoryCount.php', poststr);
			  showDetails(objwid.value, objsrch.value);
		 }
 	}
	else if (action == "Remove")
	{		   
	   objiid = document.frmInventoryCountDetails.elements["chkIID[]"];
	   var  arrInventory  = new Array();
	   var d = 0;
	   
	   if (objiid.length > 0) 
	   {   
		   for (var c = 0; c < objiid.length; c++)
		   {
				if (objiid[c].checked)
				{
					arrInventory[d] = objiid[c].value;
					d++;
				}
		   }
	   }
	   else if (objiid.checked)
	   {
			arrInventory[0] = objiid.value;
			d++;
	   }
   		   
	   if(d == 0)
	   {
			alert('Please select product(s) to remove.');
	   }
	   else
	   {
		   if (confirm('Are you sure you want to remove this/these detail(s)?') == false)
			   return false;
		   else
		   {
			   poststr = "action="   + encodeURI(action) +
					 "&hdnInventoryID=" + encodeURI(arrInventory);
			   makePOSTRequest('includes/jxCreateInventoryCount.php', poststr);	
		   }
	   }
   }
}

function getSelectionCountTagList(text, li) 
{
   	var txt = text.id;
   	var tmp = li.id;
   	var ctr = txt.substr(11, txt.length);
   	var tmp_val = tmp.split("_");
	  	
	a = eval('document.frmList.hdnProductID' + ctr);
	b = eval('document.frmList.hdnicdID' + ctr);
	c = eval('document.frmList.txtItemCode' + ctr);
	d = eval('document.frmList.txtItemName' + ctr);
	
	cboLocation = eval('document.frmList.cbolocation' + ctr);
	cbolen 	= cboLocation.options.length;
	//cboLocation.options[1].selected = true;
	
	f = eval('document.frmList.txtQuantity' + ctr);
   	
   	a.value = tmp_val[1];
   	b.value = tmp_val[7];
   	c.value = tmp_val[2];
   	d.value = tmp_val[3];
   //	e.value = tmp_val[5];
   	//alert(tmp_val[8]);
   	for(var i=1 ; i <= cbolen ; i++)
   	{
   		if(cboLocation.options[i].value == tmp_val[8])
   		{
   			cboLocation.options[i].selected = true;
   			cboLocation.disabled = true;
   			break;
   		}
   	}
	alert(event);	
	f.focus();
	f.select();

	return false;
}

function getSearchList(text, li) 
{
   	var txt = text.id;
   	var tmp = li.id;
	alert(tmp);
}

function getSelectionItemCodeList(text, li) 
{
	//alert(event.which);
	
   	var txt = text.id;
   	var tmp = li.id;
   	var ctr = txt.substr(11, txt.length);
   	var tmp_val = tmp.split("_");

	a = eval('document.frmList.hdnProductID' + ctr);
	b = eval('document.frmList.hdnicdID' + ctr);
	c = eval('document.frmList.txtCountTag' + ctr);
	d = eval('document.frmList.txtItemName' + ctr);
	cboLocation = eval('document.frmList.cbolocation' + ctr);
	//cboLocation.disabled = false;
	cbolen 	= cboLocation.options.length;
	f = eval('document.frmList.txtQuantity' + ctr);
   	g = eval('document.frmList.txtItemCode' + ctr);
   	if(tmp_val[0] == ""){
		c.value = "";
	}else{
		c.value = tmp_val[0];
	}
	
	if(tmp_val[1] == ""){
		a.value = "";
	}else{
		a.value = tmp_val[1];
	}
	
	
	if(tmp_val[2] == ""){
		g.value = "";
	}else{
		g.value = tmp_val[2];
	} 
   	if(tmp_val[3] == ""){
		d.value = "";
	}else{
		d.value = tmp_val[3];
	}
	
	if(tmp_val[7] == ""){
		b.value = "";
	}else{
		b.value = tmp_val[7]; 
	}
  // 	b.value = "";
   if(tmp_val[8] != ""){
		for(var i=1 ; i <= cbolen ; i++)
		{
			if(cboLocation.options[i].value == tmp_val[8])
			{
				cboLocation.options[i].selected = true;
				cboLocation.disabled = true;
				break;
			}
		}
	}
	
   	if(event.which == 13){
		if(tmp_val[8] != ""){
			f.focus();
			f.select();
		}else{
			cboLocation.focus();
		    cboLocation.select();
		} 
		return false;
	}
   	//cboLocation.focus();
   	//cboLocation.select();
	return false;
}

function disableEnterKey(a, e)
{
	var key;
	var str = a.id;

	if(window.event)
	{
		key = window.event.keyCode; //IE
	} 
	else
	{
		key = e.which; //firefox
	}

	if (str.substring(0, 11) == 'txtCountTag' && key == 13)
	{
		 var ctr = str.substring(11, str.length);
		 var inv = eval('document.frmRecInvCountDetails.hInvStatID');
		 
		 if (eval(inv.value) == 21)
		 {
			 var code = eval('document.frmList.txtQuantity' + ctr);
			 code.disabled = false;
	    	 code.focus();
	    	 code.select();		
		 }
		 else
		 {
			 var code = eval('document.frmList.txtCountTag' + ctr);
	    	 code.focus();
	    	 code.select();
		 }
		 return false;
	 }
     
     if (str.substring(0, 11) == 'txtItemCode' && key == 13)
     {
    	 var ctr = str.substring(11, str.length);
    	 var inv = eval('document.frmRecInvCountDetails.hInvStatID');
		 
		 if (eval(inv.value) == 21)
		 {
			 var code = eval('document.frmList.txtQuantity' + (ctr-1));
			 code.disabled = false;
	    	 code.focus();
	    	 code.select();		
		 }
		 else
		 {
			 var code = eval('document.frmList.txtItemCode' + ctr);
	    	 code.focus();
	    	 code.select();
		 }
		 return false;
     }

     return (key!=13);
}

function enter_key(event,ctr)
{
	//alert('test');
	var f = eval('document.frmList.txtQuantity' + ctr);
	if(event.which == 13){
		//alert('click enter');
		f.focus();
		return false;
	}
	//return false;
	//alert(event.which);
}

//function changeselection(xval)
//{
//	alert(xval.value);
//}