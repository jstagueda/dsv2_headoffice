//Global vars to hold connection to web pages
var xmlHttp
var xmlHttp2
var xmlHttp3
function showPage(str, bin,custId) 
{ 	
	//Function that gets called
	//Currently we only call one other sub, but this could change
	showStates(str, bin, custId)
}

function showStates(str, bin,custId) 
{ 
	//This sub will populate a table with all the states and get the 
	//pagination built
	//Make the AJAX connection for both the navigation and content
	xmlHttp=GetXmlHttpObject()
	xmlHttp2=GetXmlHttpObject()
	xmlHttp3=GetXmlHttpObject()
	
	//If we cant do the request error out
	if (xmlHttp==null || xmlHttp2==null ) {
	 	alert ("Browser does not support HTTP Request");
	 	return
	}
	
	var svalue = bin;
	//First build the navigation panel
	var url="includes/jxPagingEditOfficialReceipt.php"
	url=url+"?p="+str
	url=url+"&t=nav"
	url=url+"&sid="+Math.random()
	url=url+"&svalue="+svalue
	url=url+"&custId="+custId

	//Once the page finished loading put it into the div
	xmlHttp2.onreadystatechange=navDone 
	xmlHttp3.onreadystatechange=navDone2 

	//Get the php page
	xmlHttp2.open("GET",url,true)
	xmlHttp2.send(null)

	var url="includes/jxPagingEditOfficialReceipt.php"
	url=url+"?p="+str
	url=url+"&t=num"
	url=url+"&sid="+Math.random()
	url=url+"&svalue="+svalue
	url=url+"&custId="+custId
	
	xmlHttp3.open("GET",url,true)
	xmlHttp3.send(null)
	
	//Build the url to call
	//Pass variables through the url
	var url="includes/jxPagingEditOfficialReceipt.php"
	url=url+"?p="+str
	url=url+"&t=con"
	url=url+"&sid="+Math.random()
	url=url+"&svalue="+svalue
	url=url+"&custId="+custId
	//Once the page finished loading put it into the div
	xmlHttp.onreadystatechange=stateChanged 

	
	//Get the php page
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function navDone() 
{ 
	document.getElementById("pgNavigation").innerHTML="<img border='0' src='images/ajax-loader.gif'>"
	//IF this is getting called when the page is done loading then fill the pagination div
	if (xmlHttp2.readyState==4 || xmlHttp2.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgNavigation").innerHTML=xmlHttp2.responseText 
	} 
}

function navDone2() 
{ 
	document.getElementById("pgRecord").innerHTML="<img border='0' src='images/ajax-loader.gif'>"
	//IF this is getting called when the page is done loading then fill the pagination div
	if (xmlHttp3.readyState==4 || xmlHttp3.readyState=="complete") 
	{ 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgRecord").innerHTML=xmlHttp3.responseText 
	} 
}

function stateChanged() 
{ 
	document.getElementById("pgContent").innerHTML="<img border='0' src='images/ajax-loader.gif'>"
	//IF this is getting called when the page is done loading the states then output the div
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") 
	{ 
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

function CheckIncludes()
{	
	var ci = document.frmEditOfficialReceipts.elements["countRows1"];
    var chkAll = document.frmEditOfficialReceipts.chkAlls;
    var amt = 0;
    var len = ci.length;
    var isChecked = '';

	for(i=0; i< ci.value; i++)
	{
		amt = eval('document.frmEditOfficialReceipts.txtAmountApplieds'+i+'');
		isChecked = eval('document.frmEditOfficialReceipts.txtchks'+i+'');
		chkIDs = eval('document.frmEditOfficialReceipts.chkIDs'+i+'.checked');
		
		if(chkIDs== false)
		{	
			chkAll.checked = false;
			isChecked.value=0;
			amt.value='0.00';
			amt.disabled = true;
		}
		
		if(chkIDs == true)
		{	
			isChecked.value=1;
			amt.disabled = false;
		}
	}
}

function checkAllPaging(bin) 
{
	var elms = document.frmEditOfficialReceipts.elements;	
    var chkAll2 = document.frmEditOfficialReceipts.chkAlls;
    var amt = 0;
    var ci = document.frmEditOfficialReceipts.countRows1; 
    var amts = false;
  
    if(bin==true)
    {
    	for (var i = 0; i <ci.value; i++)
    	{
        	eval('document.frmEditOfficialReceipts.chkIDs'+i+'.checked = true');
        	amts = eval('document.frmEditOfficialReceipts.txtAmountApplieds'+i+'.disabled = false');
    	} 
    	
    }
    
    if(bin==false)
    {
    	for (var i = 0; i <ci.value; i++)
    	{
        	eval('document.frmEditOfficialReceipts.chkIDs'+i+'.checked = false');
        	amts = eval('document.frmEditOfficialReceipts.txtAmountApplieds'+i+'.disabled = true');
    	}
    }
}

function checkDetails()
{
	var ci = document.frmEditOfficialReceipts.elements["countRows1"];
	var unapp = eval(document.frmEditOfficialReceipt.txtTotalUnapplied.value);
    var isChecked = '';
    var cnt = 0;
    var totapp = 0;

	for(i=0; i < ci.value; i++)
	{
		chkIDs = eval('document.frmEditOfficialReceipts.chkIDs' + i + '.checked');
		
		if(chkIDs == true)
		{	
			cnt += 1;
		}
	}
	
	if (cnt == 0)
	{
		alert ('Please select sales invoice(s) or penalty(ies) to be added.');
		return false;
	}
	else
	{
		for(i=0; i < ci.value; i++)
		{
			chkIDs = eval('document.frmEditOfficialReceipts.chkIDs' + i + '.checked');

			if(chkIDs == true)
			{
				amt = eval('document.frmEditOfficialReceipts.txtAmountApplieds' + i);
				obal = eval('document.frmEditOfficialReceipts.txtOutstandingBalances' + i);
				hamt = eval('document.frmEditOfficialReceipts.txtAmountApplieds_1' + i);

				if (amt.value == '')
				{
					alert ("Amount Applied required.");
					amt.select();
					return false;
				}
				else if (!isNumeric(amt.value))
				{
					alert ("Invalid numeric format for Amount Applied.");
					amt.focus();
					amt.select();
					return false;
				}
				else if (amt.value == 0)
				{
					alert ("Amount Applied should be greater than 0.");
					amt.focus();
					amt.select();
					return false;
				}
				else if (eval(obal.value) < eval(amt.value))
				{
					alert ("Amount Applied should be less than or equal to Outstanding Balance.");
					amt.focus();
					amt.select();
					return false;
				}
				else
				{
					totapp += eval(amt.value);
					hamt.value = eval(amt.value);
				}
			}
		}

		if (eval(unapp) < eval(totapp))
		{
			alert ("Amount Applied should be less than or equal to Total Unapplied Amount.");
			return false;
		}
	}
}

function checkRemove()
{
	var chk = document.getElementsByName('chkIID[]');
    var isChecked = '';
    var cnt = 0;
    var totapp = 0;

	for(i = 0; i < chk.length; i++)
	{
		if(chk[i].checked == true)
		{	
			cnt += 1;
		}
	}
	
	if (cnt == 0)
	{
		alert ('Please select sales invoice(s) or penalty(ies) to be removed.');
		return false;
	}
}