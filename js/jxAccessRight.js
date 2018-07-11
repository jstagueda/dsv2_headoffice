// JavaScript Document



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
	
	function showDetails(str) 
	{ 
		//This sub will populate a table with all the states and get the 
		//pagination built
		//Make the AJAX connection for both the navigation and content
		
		xmlHttpModuleControl=GetXmlHttpObject()
		xmlHttpClear=GetXmlHttpObject()
		
		//If we cant do the request error out
		if (xmlHttpModuleControl==null || xmlHttpClear==null ) 
		{
			alert ("Browser does not support HTTP Request")
			return
		}
		
		//Build the url to call
		//Pass variables through the url
		var url="includes/jxAccessRight.php"
		url=url+"?mod=t"		
		url=url+"&utid="+str
		
		//Once the page finished loading put it into the div
		//alert('meron')
		xmlHttpModuleControl.onreadystatechange=navDone 
		//Get the php page
		xmlHttpModuleControl.open("GET",url,true)
		xmlHttpModuleControl.send(null)

		//alert('wala')
		//Build the url to call
		//Pass variables through the url
		var  url="includes/jxAccessRight.php"
		url=url+"?mod=p"
		url=url+"&utid="+str
		
		//Once the page finished loading put it into the div
		xmlHttpClear.onreadystatechange=navSecond 
		//Get the php page
		xmlHttpClear.open("GET",url,true)
		xmlHttpClear.send(null)
			
	}
	
	function navDone() 
	{ 
		//IF this is getting called when the page is done loading then fill the pagination div
		if (xmlHttpModuleControl.readyState==4 || xmlHttpModuleControl.readyState=="complete") 
		{ 
			//Update the Div tag with the outputted text
			document.getElementById("dvAccessRight").innerHTML=xmlHttpModuleControl.responseText 
		} 
	}

	function navSecond() 
	{ 
		//IF this is getting called when the page is done loading then fill the pagination div
		if (xmlHttpClear.readyState==4 || xmlHttpClear.readyState=="complete") 
		{ 
			//Update the Div tag with the outputted text
			document.getElementById("showThis").innerHTML=xmlHttpClear.responseText 
		} 
	}
		
