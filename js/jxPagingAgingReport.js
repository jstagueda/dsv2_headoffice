//Global vars to hold connection to web pages
var xmlHttp
var xmlHttp2
var xmlHttp3
function showPage(str, bin, fromDate, toDate) 
{ 
	//Function that gets called
	//Currently we only call one other sub, but this could change

	showStates(str, bin, fromDate, toDate)
}

function showStates(str, bin, fromDate, toDate) 
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
	var url="includes/jxPagingSIRegister.php"
	url=url+"?p="+str
	url=url+"&t=nav"
	url=url+"&sid="+Math.random()
	url=url+"&svalue="+svalue+"&toDate="+toDate+"&fromDate="+fromDate


	//Once the page finished loading put it into the div
	xmlHttp2.onreadystatechange=navDone 
	xmlHttp3.onreadystatechange=navDone2 

	//Get the php page
	xmlHttp2.open("GET",url,true)
	xmlHttp2.send(null)

	var url="includes/jxPagingSIRegister.php"
	url=url+"?p="+str
	url=url+"&t=num"
	url=url+"&sid="+Math.random()
	url=url+"&svalue="+svalue+"&toDate="+toDate+"&fromDate="+fromDate
	
	xmlHttp3.open("GET",url,true)
	xmlHttp3.send(null)
	
	//Build the url to call
	//Pass variables through the url
	var url="includes/jxPagingSIRegister.php"
	url=url+"?p="+str
	url=url+"&t=con"
	url=url+"&sid="+Math.random()
	url=url+"&svalue="+svalue+"&toDate="+toDate+"&fromDate="+fromDate
	//Once the page finished loading put it into the div
	xmlHttp.onreadystatechange=stateChanged 

	
	//Get the php page
	xmlHttp.open("GET",url,true)
	xmlHttp.send(null)
}

function navDone() { 
	document.getElementById("pgNavigation").innerHTML="<img border='0' src='images/ajax-loader.gif'>"
	//IF this is getting called when the page is done loading then fill the pagination div
	if (xmlHttp2.readyState==4 || xmlHttp2.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgNavigation").innerHTML=xmlHttp2.responseText 
	} 
}
function navDone2() { 
document.getElementById("pgRecord").innerHTML="<img border='0' src='images/ajax-loader.gif'>"
	//IF this is getting called when the page is done loading then fill the pagination div
	if (xmlHttp3.readyState==4 || xmlHttp3.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgRecord").innerHTML=xmlHttp3.responseText 
	} 
}

function stateChanged() { 
document.getElementById("pgContent").innerHTML="<img border='0' src='images/ajax-loader.gif'>"
	//IF this is getting called when the page is done loading the states then output the div
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete") { 
	 	//Update the Div tag with the outputted text
	 	document.getElementById("pgContent").innerHTML=xmlHttp.responseText 
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



function openPopUp() 
{
    var agerange = [];
    var age = [];
    $("input[name^=ageRange]").each(function(){
        if($(this).is(":checked")){
            age.push($(this).val());
        }
        agerange.push($(this).val());
    });
    
    var agingasof = $('#txtEndDate').val();
    if(agingasof == ""){
        popinmessage("Please insert 'Aging as of' date.");
        return false;
    }
    
    /*if($('input[name=cboCustomerFrom]').val() == 0){
        popinmessage("Please select from 'IBM Code from' field.");
        return false;
    }*/

    /*if($('input[name=cboCustomerTo]').val() == 0){
        popinmessage("Please select from 'IBM Code to' field.");
        return false;
    }*/
    
    if(age.length == 0){
        popinmessage("Please select from 'Age' field.");
        return false;
    }
    
    /*if($('.action').val() == ""){
        popinmessage("Please search details to print.");
        return false;
    }*/
    
    var ibmfrom = $('input[name=cboCustomerFromHidden]').val();
    var ibmto = $('input[name=cboCustomerToHidden]').val();
    var ibmfromCode = $('input[name=cboCustomerFrom]').val();
    var ibmtoCode = $('input[name=cboCustomerTo]').val();
	var branch = $('input[name=branch]').val();
    
    var objWin;
    popuppage = "pages/sales/sales_agingreportprint.php?agerange="  +
                    agerange.join("_") +
                    "&age="+age.join("_") +
                    "&agingasof=" + agingasof +
                    "&ibmfrom=" + ibmfrom +
                    "&ibmto=" + ibmto +
                    "&ibmfromCode=" + ibmfromCode +
                    "&ibmtoCode=" + ibmtoCode +
					"&branch=" + branch;

    if (!objWin) 
    {			
            objWin = NewWindow(popuppage,'printps','1000','500','yes');
    }

    return false;  		
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) {win.window.focus();}
}

function openSOA(obj, obj3)
{
	var objWin;
	popuppage = "pages/sales/sales_soaprint.php?f="+ obj + "&e=" + obj3 + "&branch=" + $('[name=branch]').val();
		
	if (!objWin) 
	{			
		objWin = NewWindow(popuppage,'printps','1000','500','yes');
	}
	
	return false;  		
}

$(function(){
    $("#txtEndDate").datepicker({
        changeMonth: true,
        changeYear: true
    });
});


function showPage(page){
    $('.action').val('Paginate');
    $('.page').val(page);
    
    ajaxfunct();
    return false;
}


$(function(){
    $('input[name=btnSearch]').click(function(){
        
        if($('#txtEndDate').val() == ""){
            popinmessage("Please insert 'Aging as of' date.");
            return false;
        }
        
        /*if($('input[name=cboCustomerFrom]').val() == 0){
            popinmessage("Please select from 'IBM Code from' field.");
            return false;
        }*/
        
        /*if($('input[name=cboCustomerTo]').val() == 0){
            popinmessage("Please select from 'IBM Code to' field.");
            return false;
        }*/
        
        var count = 0;
        $("input[name^=ageRange]").each(function(){
            if($(this).is(":checked")){
                count++;
            } 
        });
        
        if(count == 0){
            popinmessage("Please select from 'Age' field.");
            return false;
        }
        
        $('.action').val('Search');
        $('.page').val(1);
        
        ajaxfunct();
        return false;
    });
    
    
    $('input[name=cboCustomerFrom]').autocomplete({
        source  : function(request, response){
            $.ajax({
                url     :   "pages/sales/call_ajax/ajaxAgingOverDue.php",
                data    :   {searchParam : request.term, branch : $('[name=branch]').val()},
                dataType:   "json",
                type    :   "Post",
                success :   function(data){
                    response($.map(data, function(item) {
                        return { label: item.IBM, value: item.Code, id: item.ID};
                    }));
                }
            });
        },
        select  : function(event, ui){
            $('input[name=cboCustomerFromHidden]').val(ui.item.id);
			$('input[name=cboCustomerFrom]').val(ui.item.Code);
        }
    });
    
    $('input[name=cboCustomerTo]').autocomplete({
        source  : function(request, response){
            $.ajax({
                url     :   "pages/sales/call_ajax/ajaxAgingOverDue.php",
                data    :   {searchParam : request.term, branch : $('[name=branch]').val()},
                dataType:   "json",
                type    :   "Post",
                success :   function(data){
                    response($.map(data, function(item) {
                        return { label: item.IBM, value: item.Code, id: item.ID};
                    }));
                }
            });
        },
        select  : function(event, ui){
            $('input[name=cboCustomerToHidden]').val(ui.item.id);
			$('input[name=cboCustomerTo]').val(ui.item.Code);
        }
    });
	
	$('input[name=branchName]').autocomplete({
        source  : function(request, response){            
            $.ajax({
                url     :   "pages/sales/call_ajax/ajaxAgingOverDue.php",
                data    :   {searchbranch : request.term},
                dataType:   "json",
                type    :   "Post",
                success :   function(data){
                    response($.map(data, function(item) {
                        return { label: item.Label, value: item.Value, id: item.ID};
                    }));
                }
            });
        },
        select  : function(event, ui){
            $('input[name=branch]').val(ui.item.id);
        }
    });
})


//ajax function for fetching data from everyday collection query
function ajaxfunct(){
    var action = $('.action').val();
    $.ajax({        
        url         : "pages/sales/call_ajax/ajaxAgingOverDue.php",
        type        : "Post",
        data        : $('form[name=frmORRegister]').serialize(),
        beforeSend  : function(){
            $(".loader").html('Loading... Please wait...');
            $(".loader").hide().fadeIn('slow');
        },
        success     : function(data){
            $('.loader').html('&nbsp;');
            $('.tablelisttable').html(data);
            $(".tablelisttable").hide().fadeIn('slow');                
        }
        
    });
}