function MM_jumpMenu(targ,selObj,restore)
{ 		
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore) 
		selObj.selectedIndex = 0;
}

function confirmCancel()
{
	if (confirm('Are you sure you want to cancel this transaction?') == false)
   	{
   		return false;
   	}    
}

function checkAll(bin, chkcnt) 
{
	var elms = document.frmDetails.elements;
 
	for (var i = 0; i < elms.length; i++)
	{
		for(var a = 0; a < chkcnt; a++)
		{ 
	      if (elms[i].name == "chkIID" + a) 
	      {  
		     elms[i].checked = bin;		  
	      }	
		}	
	}
}

function validateSave()
{ 
    var txtrefno = document.frmInventoryCountDetails.txtRefNo;
    var txtdocno = document.frmInventoryCountDetails.txtDocNo;
    var txttdate = document.frmInventoryCountDetails.startDate;
    var txtremarks = document.frmInventoryCountDetails.txtRemarks;
    
    var msg = "";

    //alert(txtrefno + "-" + txtdocno +"-"+ cboMoveType);
    //return false;
    
    if(txtrefno.value == "")
    {
        msg += "* Reference Number \n";      
    }
    
    if(txtdocno.value == "")
    { 
    	msg += "* Document Number \n";     
    }
    
    
    
    if(msg != "")
    {
        alert(msg);
        return false;
    }
    else
    {
        if(confirm('Are you sure you want to save this transaction?') == true)
        {
    	   window.location.href = "includes/pcInvCount.php?refno="+txtrefno.value+"&docno="+txtdocno.value+"&tdate="+txttdate.value+"&remarks="+txtremarks.value+"";
        }
        else
        {
            return false;
        }
    }
}

function numbersonly(myfield, e, dec)
{
	var str = myfield.id;
	var key;
	var keychar;

	if (window.event)
   		key = window.event.keyCode;
	else if (e)
   		key = e.which;
	else
   		return true;

	keychar = String.fromCharCode(key);

	//|| (key == 0 && str.substring(0, 11) == 'txtQuantity')
	// control keys
	if ((key == null) || (key == 0) || (key == 9) || (key == 8) || (key == 27)  || (key == 45))
	{
		return true;		
	}
   	else if ((key == 13 && str.substring(0, 11) == 'txtQuantity'))
   	{
		var  hrowcnt = document.getElementById('hrowcnt');
		var error_msg = "", error_cnt = 0;
		//alert (document.getElementById('txtCountTag'+hrowcnt.value).value);
		
		for(var x = 1; hrowcnt.value >= x; x++){
/*
			if(document.getElementById('txtCountTag'+x).value == ""){
				
				error_msg += "Count Tag no. required. \n";
				error_cnt++;
			}
*/			
			if(document.getElementById("txtItemCode"+x).value == ""){
				error_msg += "Item Code  required. \n";
				error_cnt++;
			
			}
			
			if(document.getElementById("cbolocation"+x).value == 0){
				error_msg += "Location required. \n";
				error_cnt++;
			}
			
			if(error_cnt > 0){
				alert(error_msg);
				return false;
			}
		}
		

		addRow();
		return false;
   	}
	//numbers
	else if ((("0123456789").indexOf(keychar) > -1))
	{
		return true;		
	}
	// decimal point jump
	else if (dec && (keychar == "."))
   	{
   		myfield.form.elements[dec].focus();
   		return false;
   	}
	else
   		return false;
}

function addRow()
{
	var table = document.getElementById('dynamicTable');
	var hlocationName = document.getElementById('hlocationName').value;
	var hlocationID = document.getElementById('hlocationID').value;
	var hlocationcnt = document.getElementById('hlocationcnt').value; 
	var hrow =	 document.getElementById('hrowcnt');
	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);
	var index = eval(rowCount + 1);
	hrow.value = index;
	
	//row class
	if(index % 2 != 0)
	{
		row.setAttribute("class", "");
	}
	else
	{
		row.setAttribute("class", "bgEFF0EB");
	}
	
	//element - count tag
	
	//hidden product id
	var element1h = document.createElement("input");
	element1h.type = "hidden";
	element1h.setAttribute("value", "");
	element1h.setAttribute("id", "hdnProductID" + index);
	element1h.setAttribute("name", "hdnProductID" + index);
	
	//hidden inventory count id
	var element2h = document.createElement("input");
	element2h.type = "hidden";
	element2h.setAttribute("value", "");
	element2h.setAttribute("id", "hdnicdID" + index);
	element2h.setAttribute("name", "hdnicdID" + index);
	
	//input count tag
	var element1 = document.createElement("input");
	element1.type = "input";
	element1.setAttribute("class", "txtfield3");
	element1.setAttribute("id", "txtCountTag" + index);
	element1.setAttribute("name", "txtCountTag" + index);
	element1.setAttribute("style", "text-align:center;");
	element1.setAttribute("onKeyPress", "return enter_key(event, "+ index +");");
	//element1.setAttribute("readonly", "readonly") ;
	//element1.setAttribute("onKeyPress", "return disableEnterKey(this, event);");
	
	// span
	var element1s = document.createElement("span");
	element1s.setAttribute("id", "indicator" + index);
	element1s.setAttribute("name", "indicator" + index);
	element1s.setAttribute("style", "display: none");
	
	//div
	var element1d = document.createElement("div");
	element1d.setAttribute("id", "counttag_choices" + index);
	element1d.setAttribute("name", "counttag_choices" + index);
	element1d.setAttribute("class", "autocomplete");
	element1d.setAttribute("style", "display: none");
	
	var cell1 = row.insertCell(0);
	cell1.setAttribute("align", "center");
	cell1.setAttribute("class", "borderBR");
	cell1.setAttribute("height", "25");
	cell1.setAttribute("width", "10%");
	
	//append
	cell1.appendChild(element1h);
	cell1.appendChild(element2h);
	cell1.appendChild(element1);
	cell1.appendChild(element1s);
	cell1.appendChild(element1d);
	
	//element - item code
	//input - item code
	var element2 = document.createElement("input");
	element2.type = "input";
	element2.setAttribute("class", "txtfield");
	element2.setAttribute("id", "txtItemCode" + index);
	element2.setAttribute("name", "txtItemCode" + index);
	element2.setAttribute("style", "width:220px;");
	//element2.setAttribute("onKeyPress", "return disableEnterKey(this, event);");
	
	// span
	var element2s = document.createElement("span");
	element2s.setAttribute("id", "indicatori" + index);
	element2s.setAttribute("name", "indicatori" + index);
	element2s.setAttribute("style", "display: none");
	
	//div
	var element2d = document.createElement("div");
	element2d.setAttribute("id", "itemcode_choices" + index);
	element2d.setAttribute("name", "itemcode_choices" + index);
	element2d.setAttribute("class", "autocomplete");
	element2d.setAttribute("style", "display: none");
	
	var cell2 = row.insertCell(1);
	cell2.setAttribute("align", "left");
	cell2.setAttribute("class", "borderBR padl5");
	cell2.setAttribute("height", "25");
	cell2.setAttribute("width", "20%");
	
	//append
	cell2.appendChild(element2);
	cell2.appendChild(element2s);
	cell2.appendChild(element2d);
	
	//element - item name
	var element3 = document.createElement("input");
	element3.type = "input";
	element3.setAttribute("class", "txtfield");
	element3.setAttribute("id", "txtItemName" + index);
	element3.setAttribute("name", "txtItemName" + index);
	element3.setAttribute("style", "width:360px;");
	element3.setAttribute("readonly", "yes");
	
	var cell3 = row.insertCell(2);
	cell3.setAttribute("align", "left");
	cell3.setAttribute("class", "borderBR padl5");
	cell3.setAttribute("height", "25");
	cell3.setAttribute("width", "30%");
	
	//append
	cell3.appendChild(element3);
	
	//element - location
	var tmp_locationID = new Array();
	var tmp_locationName = new Array();
	
	tmp_locationID = hlocationID.split('_');
	tmp_locationName = hlocationName.split('_');
	var cnt = hlocationcnt - 1;
	
	var element4 = document.createElement("select");
	element4.setAttribute("class", "txtfield");
	element4.setAttribute("id", "cbolocation" + index);
	element4.setAttribute("name", "cbolocation" + index);
	//element4.setAttribute("style", "width:360px;");
	var option = document.createElement('option');
	var text = document.createTextNode('[SELECT HERE]');
	option.value = 0;	
	option.appendChild(text);
	element4.appendChild(option);
	
	for(var i = 0 ; i <= cnt ; i++)
	{
		var option = document.createElement('option');
		var text = document.createTextNode(tmp_locationName[i]);
		option.appendChild(text);
		option.value = tmp_locationID[i];
		element4.appendChild(option);
	}
	
	var cell4 = row.insertCell(3);
	cell4.setAttribute("align", "left");
	cell4.setAttribute("class", " padl5 borderBR");
	cell4.setAttribute("height", "25");
	cell4.setAttribute("width", "20%");

	//append
	cell4.appendChild(element4);
	
	//element - uom
	var element5 = document.createElement("input");
	element5.type = "input";
	element5.setAttribute("class", "txtfield");
	element5.setAttribute("id", "txtUOM" + index);
	element5.setAttribute("name", "txtUOM" + index);
	element5.setAttribute("style", "width:80px; text-align:center");
	element5.setAttribute("readonly", "yes");
	element5.setAttribute("value", document.frmRecInvCountDetails.hUOM.value);
	
	var cell5 = row.insertCell(4);
	cell5.setAttribute("align", "center");
	cell5.setAttribute("class", " borderBR");
	cell5.setAttribute("height", "25");
	cell5.setAttribute("width", "10%");
	
	//append
	cell5.appendChild(element5);
	
	//element - quantity
	var element6 = document.createElement("input");
	element6.type = "input";
	element6.setAttribute("class", "txtfield3");
	element6.setAttribute("id", "txtQuantity" + index);
	element6.setAttribute("name", "txtQuantity" + index);
	element6.setAttribute("style", "text-align:right");
	element6.setAttribute("size", "12");
	element6.setAttribute("maxlength", "20");
	element6.setAttribute("onKeyPress", "return numbersonly(this, event);");
	
	if (eval(document.frmRecInvCountDetails.hInvStatID.value) == 21)
	{
		element6.setAttribute("enabled", "");		
	}
	else
	{
		element6.setAttribute("disabled", "yes");
	}
	
	var cell6 = row.insertCell(5);
	cell6.setAttribute("align", "center");
	cell6.setAttribute("class", "borderBR");
	cell6.setAttribute("height", "25");
	cell6.setAttribute("width", "10%");
	
	//append
	cell6.appendChild(element6);
	
	var url_ctag = 'includes/scCountTagAjax.php?index=' + index + '&tid=' + document.frmRecInvCountDetails.hTxnID.value + '&whid=' + document.frmRecInvCountDetails.hWhouseID.value + '&locid=' + document.frmRecInvCountDetails.hLocationID.value;
	var counttag_choices = new Ajax.Autocompleter(element1.id, element1d.id, url_ctag , {afterUpdateElement : getSelectionCountTagList, indicator: element1s.id});
	
	var url_icode = 'includes/scItemCodeAjax.php?index=' + index + '&tid=' + document.frmRecInvCountDetails.hTxnID.value + '&whid=' + document.frmRecInvCountDetails.hWhouseID.value + '&locid=' + document.frmRecInvCountDetails.hLocationID.value;
	var itemcode_choices = new Ajax.Autocompleter(element2.id, element2d.id, url_icode , {afterUpdateElement : getSelectionItemCodeList, indicator: element2s.id});
	
	element1.focus();
	//element2.focus();
	
	return false;	
}

function createCell(cell, text, style, align)
{  
	var div = document.createElement('div');  
	var txt = document.createTextNode(text); 
	
	div.setAttribute('id', 'line');
	div.setAttribute('class', style);  
	div.setAttribute('align', align);
	div.appendChild(txt);
	cell.appendChild(div);  
} 

function confirmAdd(cnt)
{
	var table = document.getElementById('dynamicTable');	
	var hrow =	 document.getElementById('hrowcnt');
	var rowCount = table.rows.length;
	var cnt = hrow.value - 1 ;
	//alert(cnt);
	
	for (var i= 1 ; i <= cnt ; i++)
	{		
		var qty = eval('document.frmList.txtQuantity' + i);
		if(qty.value == "")
		{
			alert ("Quantity is required.")
			qty.focus();
			return false;
		}	
	}
	
 	if(confirm('Are you sure you want to save this transaction?') == true)
	{
	   return true;
	}
	else
	{
	    return false;
	}
}

function confirmCancel()
{ 
	if(confirm('Are you sure you want to cancel this transaction?') == true)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function winSelectProduct() 
{
	var tid = document.frmRecInvCountDetails.hTxnID.value;
	var sid = document.frmRecInvCountDetails.hStatID.value;
	var plist = document.frmRecInvCountDetails.hProdListID.value;
	var whid = document.frmRecInvCountDetails.hWhouseID.value;
	var locid = document.frmList.hdnlid.value;
	 
	subWin = window.open('pages/inventory/inv_RecordInventoryCountProductList.php?tid=' + tid + '&prodlist=' + plist + '&whid=' + whid + '&locid=' + locid , 'newWin', 'width=600,height=600,scrollbars=yes');
	subWin.focus();
	return false;
}

function NewWindow(mypage, myname, w, h, scroll) 
{
		var winl = (screen.width - w) / 2;
		var wint = (screen.height - h) / 2;
		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no'
		win = window.open(mypage, myname, winprops)
		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function validatePrint() 
{	
	var wid = document.frmList.hdnwid.value;
	var lid = document.frmList.hdnlid.value;
	var tid = document.frmList.hdntid.value;

  	if(confirm('Print by item code?') == true)
  	{
   		pagetoprint = "pages/inventory/inv_RecordInvCountDetails_print.php?tid="+tid+"&wid="+wid+"&lid="+lid+"&sort=1";
	   	objWin = NewWindow(pagetoprint,'print','900','900','yes');
	   	return false;
  	}
  	else
  	{
	  	pagetoprint = "pages/inventory/inv_RecordInvCountDetails_print.php?tid="+tid+"&wid="+wid+"&lid="+lid+"&sort=2";
	  	objWin = NewWindow(pagetoprint,'print','900','900','yes');
      	return false;
  	}
}

var answerFunction;
function myConfirm(text, button1, button2, button3, answerFunc) 
{
	var box = document.getElementById("confirmBox");
	box.getElementsByTagName("p")[0].firstChild.nodeValue = text;
	var button = box.getElementsByTagName("input");
	button[0].value = button1;
	button[1].value = button2;
	button[2].value = button3;
	answerFunction = answerFunc;
	box.style.visibility = "visible";
}

function answer(response) 
{
	document.getElementById("confirmBox").style.visibility="hidden";
	answerFunction(response);
}

function confirmFreeze(statid)
{
	var msg = ""
	if(statid == 20)
	{
		msg = "Are you sure you want to freeze Inventory?";
	}
	else
	{
		msg = "Are you sure you want to unfreeze Inventory?";
	}	
	if(confirm(msg) == true)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function confirmPrintAddtnlProd()
{
	var wid = document.frmList.hdnwid.value;
	var lid = document.frmList.hdnlid.value;
	var tid = document.frmList.hdntid.value;
	
	var prodlist = new Array();
	var cntlist = new Array();
	var quantitylist = new Array();
	var loclist = new Array();

	//var url_prod = document.frmRecInvCountDetails.hProdListID.value;
	//var url_cnttag = document.frmRecInvCountDetails.hdncnttag.value;
	//var url_quantity = document.frmRecInvCountDetails.txtQuantity.value;

		var ml = document.frmList;
		var len = ml.elements.length;
		var index = 0;
		
		for (var i = 0; i < len; i++) 
		{
			var e = ml.elements[i];
			
		    if (e.name == "chkSelect[]" && e.checked == true) 
		    {
		    	var a = eval('document.frmList.hdnProductID' + e.value);
		    	var b = eval('document.frmList.hdncnttag' + e.value);
		    	var c = eval('document.frmList.txtQuantity' + e.value);
                var d = eval('document.frmList.hdnLocation' + e.value);
		    	
				prodlist[index] = a.value;
				cntlist[index] = b.value;
				quantitylist[index] = c.value;
				loclist[index] = d.value;
				index = index + 1;
				
				
				
		    }
		   
		}

		if(prodlist == "")
		{
			alert("There are no additional count tags.");
			return false;
		}
	
		pagetoprint = "pages/inventory/inv_RecordInvCountDetails_print.php?tid="+tid+"&wid="+wid+"&lid="+lid+"&sort=1"+"&prodlist="+prodlist+"&cntlist="+cntlist+"&quantitylist="+quantitylist+"&loclist="+loclist+"&addtnl=1";
		//pagetoprint = "pages/inventory/inv_printCountWSandTags_print.php?pmgid=0&prodlineid=0&datecreated="+dtecreated+"&prod=3&wid="+whouse.value+"&ref="+refno+"&txnno="+txnno+"&prodlist="+prodlist+"&loclist="+loclist+"&cntlist="+cnttaglist+"";
		
		//if (!objWin) 
		//{			
			objWin = NewWindow(pagetoprint,'print','900','900','yes');
		//}
			return false;
		
}

function selectField(cntid)
{
	var id = eval(cntid - 1);
	var objqty  = document.frmList.elements["txtQuantity[]"];

	objqty[id].focus();	
	objqty[id].select();
}

function addItem()
{ 	
	$j('#addItem').dialog('open');
	return false;
}

//add item pop-up
$j().ready(function() 
{
    $j('#addItem')
    .dialog
    ({
    	autoOpen: false,
    	height: 350,
    	width: 500,
    	resizable: false,
    	modal: true,           
    	open: function() 
    	{
			var $jprodID = $j('#hAddProdID').val();
		 	var $jurlString = "pid=" + $jprodID;
		 	$j('#addItemtable').load('includes/jxAddItemInvCount.php',function(){});		
    	}                  
 	});
});