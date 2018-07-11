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
	var elms = document.frmTransferDetails.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkIID[]') 
	  {
		  elms[i].checked = bin;		  
	  }		
}

function confirmSave() 
{	
	msg = '';
	obj = document.frmTransferDetails.elements;
	objiid = document.frmTransferDetails.elements["chkIID[]"];
	
	if (objiid == undefined)
	{
		alert ('There are no details added.');
		return false;		
	}
	else
	{
		// TEXT BOXES
		if (obj["cboDesWarehouse"].selectedIndex == 0) msg += '   * Destination Warehouse \n';
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

function showDesWareHouse(str)
{
	if (str=="")
  	{
		document.getElementById("txtHint").innerHTML="";
		return;
 	} 
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  	xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  	xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
	  	if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","includes/jxCreateTransfer.php?wid="+str,true);
	xmlhttp.send();
}

var http_request = false;
var mydiv = "ShowThis";

function makePOSTRequest(url, parameters) 
{
	http_request = false;
	if (window.XMLHttpRequest) 
	{ // Mozilla, Safari,...
	   	http_request = new XMLHttpRequest();
	   	if (http_request.overrideMimeType) 
		{
		  	// set type accordingly to anticipated content type
		  	//http_request.overrideMimeType('text/xml');
		  	http_request.overrideMimeType('text/html');
	   	}
	} 
	else if (window.ActiveXObject) 
	{ // IE
	   try 
	   {
		  	http_request = new ActiveXObject("Msxml2.XMLHTTP");
	   } 
	   catch (e) 
	   {
		  	try 
			{
				http_request = new ActiveXObject("Microsoft.XMLHTTP");
		  	} 
			catch (e) {}
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
	
	xmlHttpDetail=GetXmlHttpObject()
	xmlHttpWarehouse=GetXmlHttpObject()
    xmlHttpProd=GetXmlHttpObject()
	
	//If we cant do the request error out
	if (xmlHttpWarehouse==null || xmlHttpDetail==null ) 
	{
		alert ("Browser does not support HTTP Request")
		return
	} 
	
	var url="includes/jxCreateTransfer.php"
	url=url+"?mod=w"			
	//Once the page finished loading put it into the div
	xmlHttpWarehouse.onreadystatechange=disableWarehouse 
	//Get the php page
	xmlHttpWarehouse.open("GET",url,true)
	xmlHttpWarehouse.send(null)

	//Build the url to call
	//Pass variables through the url
	var url="includes/jxCreateTransfer.php"
	url=url+"?mod=t"
	url=url+"&wrid="+wid
	url=url+"&srch="+srch		
	//Once the page finished loading put it into the div
	xmlHttpDetail.onreadystatechange=navDone 
	//Get the php page
	xmlHttpDetail.open("GET",url,true)
	xmlHttpDetail.send(null)

	//Build the url to call
	//Pass variables through the url
	var url="includes/jxCreateTransfer.php"
	url=url+"?mod=p"			
	//Once the page finished loading put it into the div
	xmlHttpProd.onreadystatechange=stateChanged 
	//Get the php page
	xmlHttpProd.open("GET",url,true)
	xmlHttpProd.send(null)	
}
  
function navDone() 
{ 
	document.getElementById("dvProductList").innerHTML="<img border='0' src='images/ajax-loader.gif'>"
	//IF this is getting called when the page is done loading then fill the pagination div
	if (xmlHttpDetail.readyState==4 || xmlHttpDetail.readyState=="complete") 
	{ 	    
		//Update the Div tag with the outputted text
		document.getElementById("dvProductList").innerHTML=xmlHttpDetail.responseText 
	} 
}  
  
function stateChanged() 
{ 
	document.getElementById("dvTransferDetails").innerHTML="<img border='0' src='images/ajax-loader.gif'>"

	//IF this is getting called when the page is done loading the states then output the div
	if (xmlHttpProd.readyState==4 || xmlHttpProd.readyState=="complete") 
	{ 
		//Update the Div tag with the outputted text
		document.getElementById("dvTransferDetails").innerHTML=xmlHttpProd.responseText 
	}	
}

function disableWarehouse() 
{ 
	document.getElementById("dvWarehouse").innerHTML="<img border='0' src='images/ajax-loader.gif'>"
	
	//IF this is getting called when the page is done loading the states then output the div
	if (xmlHttpWarehouse.readyState==4 || xmlHttpWarehouse.readyState=="complete") 
	{ 
		//Update the Div tag with the outputted text
		document.getElementById("dvWarehouse").innerHTML=xmlHttpWarehouse.responseText 
	} 
}
	
function getTransfer(action, thisDiv) 
{
	mydiv = thisDiv  
	if (action == "Add")
	{
		objid   = document.frmTransferDetails.elements["hdnInventoryID[]"]
		objuom  = document.frmTransferDetails.elements["hdnUOM[]"]
		objqty  = document.frmTransferDetails.elements["txtquantity[]"]
		objrsn  = document.frmTransferDetails.elements["cboreason[]"]
		objwid  = document.frmTransferDetails.elements["hdncboWarehouse"]
		objsrch = document.frmTransferDetails.elements["hdnSearch"]
		                               
		var d = 0;		 			 
		var arrInventory  = new Array(),
			arrQuantity   = new Array(),
			arrReason     = new Array(),
			arrUOM        = new Array();
			 
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
			  var value = 0
			  for (var m = 0; m < objuom.length; m++)
			  {
				  if (objuom[m].selected)
				  {
					  value = objuom[m].value
				  }
			  }					 
			  arrInventory[0] = objid.value
			  arrQuantity[0]  = objqty.value
			  arrReason[0] 	= objrsn.value		
			  arrUOM[0] 		= value
			  d++
		 }
		
		if (objwid.selectedIndex != 0) 
		{   
			var d = 0;
			for (var c = 0; c < cnt.value; c++)
			{
				
				if (TrimZero(objqty[c].value) != '')
				{
					if (!isWholeNumber(objqty[c].value)) 
			        {
						alert ('Please input numeric values for Quantity.');
						objqty[c].focus();
						objqty[c].select();
						return false;
					}
					else if (TrimZero(objqty[c].value) == 0)
					{
						alert ('Quantity should be greater than 0.');
						objqty[c].focus();
						objqty[c].select();
						return false;
					}
					else if (TrimZero(objqty[c].value) < 0)
					{	
						alert ('Quantity should be greater than 0.');
						objqty[c].focus();
						objqty[c].select();
						return false;
					}
					else if (eval(objUOM[c].value) == 5)
					{
						$totQty = 	eval(objqty[c].value) * eval(objBooklet[c].value);
						if($totQty >  eval(objsoh[c].value))
						{
							alert ('Quantity should be less than or equal to the available SOH.');
							objqty[c].focus();
							objqty[c].select();
							return false;
						}
						else
						{
							d += 1;
						}
						
					}
					
					else if (eval(objUOM[c].value) == 6)
					{
						$totQty = 	eval(objqty[c].value) * eval(objBox[c].value);					
						if($totQty >  eval(objsoh[c].value))
						{
							
							alert ('Quantity should be less than or equal to the available SOH.');
							objqty[c].focus();
							objqty[c].select();
							return false;
						}
						else
						{
							d += 1;
						}
					
					}
					
					else if (eval(objqty[c].value) > eval(objsoh[c].value))
					{			
					
						alert ('Quantity should be less than or equal to the available SOH.');
						objqty[c].focus();
						objqty[c].select();
						return false;
					}
					
					else
					{
						d += 1;
					}				
				}
			}

			if (d == 0)
			{
				alert ('There are no product(s) to be added.');
				return false;		
			}
			else 
			{
				return true;
			}
		} 
		else 
		{
			alert ('There are no product(s) to be added.');
			return false;	
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
						"&hdnUOM=" + encodeURI(arrUOM)
				   
			  makePOSTRequest('includes/jxCreateTransfer.php', poststr)
		
			  showDetails(objwid.value, objsrch.value)
		 }		
   }
   else if (action == "Remove")
   {	   
	   objiid = document.frmTransferDetails.elements["chkIID[]"]
	   var arrInventory  = new Array()
  	   var d = 0
	   if (objiid.length > 0) 
	   {  
		   	for (var c = 0; c < objiid.length; c++)
		   	{
				if (objiid[c].checked)
				{
					arrInventory[d] = objiid[c].value
					d++
				}
		   	}
	   }
	   else if (objiid.checked)
	   {
			arrInventory[0] = objiid.value;
			d++
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
					 	  "&hdnInventoryID=" + encodeURI(arrInventory)
						
		  		makePOSTRequest('includes/jxCreateTransfer.php', poststr)	
		   }
	   }
	}
}