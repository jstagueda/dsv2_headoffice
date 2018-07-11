// JavaScript Document

function getSelectionProductList(text, li) 
{
	var txt = text.id;
	var ctr = txt.substr(11, txt.length);

	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmCreateMultiLine.hProdID' + ctr);
	i = eval('document.frmCreateMultiLine.txtProdCode' + ctr);
	j = eval('document.frmCreateMultiLine.txtProdDesc' + ctr);
	k = eval('document.frmCreateMultiLine.txtQty' + ctr);
	n = eval('document.frmCreateMultiLine.txtbPmg' + ctr);
	o = eval('document.frmCreateMultiLine.cboCriteria' + ctr);
	
	o.focus();
	//k.focus();
	//k.select();
	
	h.value = tmp_val[0];
	j.value = tmp_val[1];
	i.value = tmp_val[2];
	n.value = tmp_val[3];
}

function getSelectionProductList2(text, li) 
{
	var txt = text.id;
	var ctr = txt.substr(12, txt.length);
	
	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmCreateMultiLine.hEProdID' + ctr);
	i = eval('document.frmCreateMultiLine.txtEProdCode' + ctr);
	j = eval('document.frmCreateMultiLine.txtEProdDesc' + ctr);
	k = eval('document.frmCreateMultiLine.txtEQty' + ctr);
	l = eval('document.frmCreateMultiLine.hEUnitPrice' + ctr);
	m = eval('document.frmCreateMultiLine.hEpmgid' + ctr);
	//n = eval('document.frmCreateMultiLine.txtPmg' + ctr);
	o = eval('document.frmCreateMultiLine.cboEPMG' + ctr);
	p = eval('document.frmCreateMultiLine.cboECriteria' + ctr);

	p.focus();
	//k.focus();
	//k.select();
	
	h.value = tmp_val[0];
	j.value = tmp_val[1];
	l.value = tmp_val[2];
	m.value = tmp_val[3];
	i.value = tmp_val[4];
	//n.value = tmp_val[5];
	
	for ( var i = 0; i < o.options.length; i++ ) 
	{
		if (o.options[i].value == tmp_val[3]) 
		{
			o.options[i].selected = true;
			//return;
       }
	}
	
	for ( var i = 0; i < o.options.length; i++)
	{
		if (tmp_val[3] == 1)
		{
			o.remove(1);
			return;
		}
		
		if (tmp_val[3] == 2)
		{
			o.remove(0);
			return;
		}
	}
}

function addRow() 
{		 
	var table = document.getElementById('dynamicTable');
	var cnt2 =document.frmCreateMultiLine.hBuyInIndex.value;
	var rowCount = table.rows.length;
		
   	if(cnt2 >  rowCount)
   	{
   		
   		 var a = eval('document.frmCreateMultiLine.txtProdDesc' + cnt2);
    		
 	  	 	if(a != undefined)
 		   	{	
 		         if(a.value == "")
 		         {  		        	
 		      	   return false;
 		         }
 		         else
 		         {
 		        	
 		        	 var index = eval(parseInt(cnt2) + 1);
 		        	 var row = table.insertRow(rowCount);
 		         }
 		   	}
 		   	else
 		   	{
 		   		 var index = eval(parseInt(cnt2) + 1);
         		 var row = table.insertRow(rowCount);
 		   	}

   	}
   	else
   	{
   	  
   		 var a = eval('document.frmCreateMultiLine.txtProdDesc' + rowCount);
     		
          if(a.value == "")
          {  
       	   return false;
          }
          else
          {
         	 var index = eval(rowCount + 1);
         	 var row = table.insertRow(rowCount);
          }
   	}
      
	
	
	//row class
	if(index % 2 != 0)
	{
		row.setAttribute("class", "");
	}
	else
	{
		row.setAttribute("class", "bgEFF0EB");
	}
	
    var cellRem = row.insertCell(0);
    cellRem.setAttribute("align", "center");
    cellRem.setAttribute("class", "borderBR");
    cellRem.setAttribute("width", "10%");
    var element1rem = document.createElement("input");
    element1rem.type = "button";
    element1rem.setAttribute("class", "btn");
    element1rem.setAttribute("style", "width: 65px");
    element1rem.setAttribute("id", "btnRemove" + index);
    element1rem.setAttribute("name", "btnRemove" + index);
    element1rem.setAttribute("value", "Remove");
    element1rem.onclick =  function () {deleteRow(this.parentNode.parentNode.rowIndex, index)};
 	   	
    var elementChk = document.createElement("input");
    elementChk.type = "hidden";
    elementChk.setAttribute("id", "chkSelect");
    elementChk.setAttribute("name", "chkSelect[]");
    elementChk.setAttribute("checked","checked");
    elementChk.setAttribute("value", index);
    cellRem.appendChild(element1rem);
    cellRem.appendChild(elementChk);
   
	//line no.
	var cell1 = row.insertCell(1);
	cell1.setAttribute("align", "center");
	cell1.setAttribute("class", "borderBR");
	cell1.setAttribute("width", "8%");
	createCell(cell1, index, '', 'center');
	
	//item code
	var cell2 = row.insertCell(2);
	cell2.setAttribute("align", "left");
	cell2.setAttribute("class", "borderBR padl5");
	cell2.setAttribute("width", "12%");
	var element1 = document.createElement("input");
	var element1h = document.createElement("input");
	var element1s = document.createElement("span");
	var element1d = document.createElement("div");
	
	//hidden product id
	element1h.type = "hidden";
	element1h.setAttribute("value", "");
	element1h.setAttribute("id", "hProdID" + index);
	element1h.setAttribute("name", "hProdID" + index);
	
	//span
	element1s.setAttribute("id", "indicator" + index);
	element1s.setAttribute("name", "indicator" + index);
	element1s.setAttribute("style", "display: none");
	element1s.innerHTML = ("<img src='images/ajax-loader.gif' />");
	 
	//div
	element1d.setAttribute("id", "prod_choices" + index);
	element1d.setAttribute("name", "prod_choices" + index);
	element1d.setAttribute("class", "autocomplete");
	element1d.setAttribute("style", "display: none");
	
	element1.type = "input";
	element1.setAttribute("class", "txtfield");
	element1.setAttribute("id", "txtProdCode" + index);
	element1.setAttribute("name", "txtProdCode" + index);
	element1.setAttribute("style", "width: 70px");
	
	cell2.appendChild(element1);
	cell2.appendChild(element1h);
	cell2.appendChild(element1s);
	cell2.appendChild(element1d);
 
	//item description
	var cell3 = row.insertCell(3);
	cell3.setAttribute("align", "left");
	cell3.setAttribute("class", "borderBR padl5");
	cell3.setAttribute("width", "31%");
	
	var element2 = document.createElement("input");
	element2.type = "text";
	element2.setAttribute("class", "txtfield");
	element2.setAttribute("style", "width: 200px");
	element2.setAttribute("readonly", "yes");
	element2.setAttribute("id", "txtProdDesc" + index);
	element2.setAttribute("name", "txtProdDesc" + index);
	cell3.appendChild(element2);
	
	//criteria
	var cell4 = row.insertCell(4);
	cell4.setAttribute("align", "center");
	cell4.setAttribute("class", "borderBR");
	cell4.setAttribute("width", "13%");
	var element3 = document.createElement("select");
	var option1 = document.createElement('option');
	var option2 = document.createElement('option');
	var criteria = [ 'Amount', 'Quantity' ];
	var text1 = document.createTextNode(criteria[0]);
	var text2 = document.createTextNode(criteria[1]);

	option1.appendChild(text1);
	option1.value = 2;
	element3.appendChild(option1);
	option2.appendChild(text2);
	option2.value = 1;
	option2.selected = "selected";
	element3.appendChild(option2);
	element3.setAttribute("class", "txtfield");
	element3.setAttribute("id", "cboCriteria" + index);
	element3.setAttribute("name", "cboCriteria" + index);
	element3.setAttribute("style", "width: 80px");
	cell4.appendChild(element3);
	
	//minimum
	var cell5 = row.insertCell(5);
	cell5.setAttribute("align", "center");
	cell5.setAttribute("class", "borderBR");
	cell5.setAttribute("width", "13%");
	var element4 = document.createElement("input");
	element4.type = "input";
	element4.setAttribute("class", "txtfield");
	element4.setAttribute("style", "width: 45px; text-align: right");
	element4.setAttribute("onKeyPress", "return disableEnterKey(this, event, " + index + ")");
	//element4.setAttribute("onBlur", "addRow()");
	element4.setAttribute("id", "txtQty" + index);
	element4.setAttribute("name", "txtQty" + index);
	element4.setAttribute("value", "1");
	cell5.appendChild(element4);
	
	//pmg
	var cell6 = row.insertCell(6);
	cell6.setAttribute("align", "center");
	cell6.setAttribute("class", "borderBR");
	
	
	var elementpmgvalue = document.createElement("input");
	elementpmgvalue.type = "input";
	elementpmgvalue.setAttribute("value", "");
	elementpmgvalue.setAttribute("id", "txtbPmg" + index);
	elementpmgvalue.setAttribute("name", "txtbPmg" + index);
	elementpmgvalue.setAttribute("class", "txtfield");
	elementpmgvalue.setAttribute("readonly", "readonly");
	elementpmgvalue.setAttribute("style", "width: 75px");

	cell6.setAttribute("width", "20%");
	cell6.appendChild(elementpmgvalue);
	document.frmCreateMultiLine.hBuyInIndex.value = index;
	var url = 'includes/scProductListAjax.php?index=' + index;
	var prod_choices = new Ajax.Autocompleter(element1.id, element1d.id, url, {afterUpdateElement : getSelectionProductList, indicator: element1s.id});
	
	element1.focus();
}

function addRow2() 
{
	var ml = document.frmCreateMultiLine;
	var table = document.getElementById('dynamicEntTable');		
	var cnt2 = document.frmCreateMultiLine.hEntIndex.value;
	var rowCount = table.rows.length;
		
   	if(cnt2 >  rowCount)
   	{
   		
   		 var a = eval('document.frmCreateMultiLine.txtEProdDesc' + cnt2);
    		
 	  	 	if(a != undefined)
 		   	{	
 		         if(a.value == "")
 		         {  		        	
 		      	   return false;
 		         }
 		         else
 		         {
 		        	
 		        	 var index = eval(parseInt(cnt2) + 1);
 		        	 var row = table.insertRow(rowCount);
 		         }
 		   	}
 		   	else
 		   	{
 		   		 var index = eval(parseInt(cnt2) + 1);
         		 var row = table.insertRow(rowCount);
 		   	}

   	}
   	else
   	{
   	  
   		 var a = eval('document.frmCreateMultiLine.txtEProdDesc' + rowCount);
     		
          if(a.value == "")
          {  
       	   return false;
          }
          else
          {
         	 var index = eval(rowCount + 1);
         	 var row = table.insertRow(rowCount);
          }
   	}
	
	
	//row class
	if(index % 2 != 0)
	{
		row.setAttribute("class", "");
	}
	else
	{
		row.setAttribute("class", "bgEFF0EB");
	}
	
	var cellRem = row.insertCell(0);
    cellRem.setAttribute("align", "center");
    cellRem.setAttribute("class", "borderBR");
    cellRem.setAttribute("width", "10%");
    var element1rem = document.createElement("input");
    element1rem.type = "button";
    element1rem.setAttribute("class", "btn");
    element1rem.setAttribute("style", "width: 65px");
    element1rem.setAttribute("id", "btnRemove" + index);
    element1rem.setAttribute("name", "btnRemove" + index);
    element1rem.setAttribute("value", "Remove");
    element1rem.onclick =  function () {deleteRow2(this.parentNode.parentNode.rowIndex, index)};
 	   	
    var elementChk = document.createElement("input");
    elementChk.type = "hidden";
    elementChk.setAttribute("id", "chkSelectEnt");
    elementChk.setAttribute("name", "chkSelectEnt[]");
    elementChk.setAttribute("checked","checked");
    elementChk.setAttribute("value", index);
    cellRem.appendChild(element1rem);
    cellRem.appendChild(elementChk);
	
	//line no.
	var cell1 = row.insertCell(1);
	createCell(cell1, index, '', 'center');
	cell1.setAttribute("align", "center");
	cell1.setAttribute("class", "borderBR");
	cell1.setAttribute("width", "8%");
	
	//item code
	var cell2 = row.insertCell(2);
	cell2.setAttribute("align", "left");
	cell2.setAttribute("class", "borderBR padl5");
	cell2.setAttribute("width", "12%");
	
	var element1 = document.createElement("input");
	var element1h = document.createElement("input");
	var element1hup = document.createElement("input");
	var element1s = document.createElement("span");
	var element1d = document.createElement("div");
	
	//hidden product id
	element1h.type = "hidden";
	element1h.setAttribute("value", "");
	element1h.setAttribute("id", "hEProdID" + index);
	element1h.setAttribute("name", "hEProdID" + index);
	
	//hidden unit price
	element1hup.type = "hidden";
	element1hup.setAttribute("value", "");
	element1hup.setAttribute("id", "hEUnitPrice" + index);
	element1hup.setAttribute("name", "hEUnitPrice" + index);
	
	//span
	element1s.setAttribute("id", "indicatorE" + index);
	element1s.setAttribute("name", "indicatorE" + index);
	element1s.setAttribute("style", "display: none");
	element1s.innerHTML = ("<img src='images/ajax-loader.gif' />");
	 
	//div
	element1d.setAttribute("id", "prod_choicesE" + index);
	element1d.setAttribute("name", "prod_choicesE" + index);
	element1d.setAttribute("class", "autocomplete");
	element1d.setAttribute("style", "display: none");
	
	element1.type = "input";
	element1.setAttribute("class", "txtfield");
	element1.setAttribute("id", "txtEProdCode" + index);
	element1.setAttribute("name", "txtEProdCode" + index);
	element1.setAttribute("style", "width: 75px");
	
	cell2.appendChild(element1);
	cell2.appendChild(element1h);
	cell2.appendChild(element1hup);
	cell2.appendChild(element1s);
	cell2.appendChild(element1d);
 
	//item description
	var cell3 = row.insertCell(3);
	cell3.setAttribute("align", "left");
	cell3.setAttribute("class", "borderBR padl5");
	cell3.setAttribute("width", "31%");
	var element2 = document.createElement("input");
	element2.type = "text";
	element2.setAttribute("class", "txtfield");
	element2.setAttribute("style", "width: 200px");
	element2.setAttribute("readonly", "yes");
	element2.setAttribute("id", "txtEProdDesc" + index);
	element2.setAttribute("name", "txtEProdDesc" + index);
	cell3.appendChild(element2);
	
	//criteria
	var cell4 = row.insertCell(4);
	cell4.setAttribute("align", "center");
	cell4.setAttribute("class", "borderBR");
	cell4.setAttribute("width", "12%");
	var element3 = document.createElement("select");
	var option1 = document.createElement('option');
	var option2 = document.createElement('option');
	var criteria = [ 'Price', 'Quantity' ];
	var text1 = document.createTextNode(criteria[0]);
	var text2 = document.createTextNode(criteria[1]);

	option1.appendChild(text1);
	option1.value = 2;
	option1.selected = "selected";
	element3.appendChild(option1);
	option2.appendChild(text2);
	option2.value = 1;
	element3.appendChild(option2);
	element3.setAttribute("class", "txtfield");
	element3.setAttribute("id", "cboECriteria" + index);
	element3.setAttribute("name", "cboECriteria" + index);
	element3.setAttribute("style", "width: 80px");
	cell4.appendChild(element3);
	
	//minimum
	var cell5 = row.insertCell(5);
	cell5.setAttribute("align", "center");
	cell5.setAttribute("class", "borderBR");
	cell5.setAttribute("width", "12%");
	var element4 = document.createElement("input");
	element4.type = "input";
	element4.setAttribute("class", "txtfield");
	element4.setAttribute("style", "width: 45px; text-align: right");
	//element4.setAttribute("onBlur", "addRow2()");
	element4.setAttribute("onKeyPress", "return disableEnterKey(this, event, " + index + ")");
	element4.setAttribute("id", "txtEQty" + index);
	element4.setAttribute("name", "txtEQty" + index);
	element4.setAttribute("value", "1");
	cell5.appendChild(element4);
	
	//pmg
	var cell6 = row.insertCell(6);
	cell6.setAttribute("align", "center");
	cell6.setAttribute("class", "borderBR");
	
	//hidden pmgid
	var elementhpmgid = document.createElement("input");
	elementhpmgid.type = "hidden";
	elementhpmgid.setAttribute("value", "");
	elementhpmgid.setAttribute("id", "hEpmgid" + index);
	elementhpmgid.setAttribute("name", "hEpmgid" + index);
	
	var elementpmgvalue = document.createElement("input");
	elementpmgvalue.type = "input";
	elementpmgvalue.setAttribute("value", "");
	elementpmgvalue.setAttribute("id", "txtPmg" + index);
	elementpmgvalue.setAttribute("name", "txtPmg" + index);
	elementpmgvalue.setAttribute("class", "txtfield");
	elementpmgvalue.setAttribute("readonly", "readonly");
	elementpmgvalue.setAttribute("style", "width: 138px");
	
	cell6.appendChild(elementhpmgid);
	//cell6.appendChild(elementpmgvalue);
	
	var element6 = document.createElement("select");
	var pmgcode = document.frmCreateMultiLine.hPMGCode.value;
	var pmgid = ml.hPMGID.value;
	var criteria = '[' + pmgcode + ']';
	var critid = '[' + pmgid + ']';
	var tmp_criteria = new Array();
	var tmp_critid = new Array();
	tmp_criteria = criteria.split(',');
	tmp_critid = critid.split(',');

	//dynamic combo box
	for (var i = 0; i < tmp_criteria.length; i++)
	{
		var val = eval("tmp_criteria[" + i + "]").replace('[', '').replace(']', '').replace(/'/g, '');
		var id = eval("tmp_critid[" + i + "]").replace('[', '').replace(']', '').replace(/'/g, '');
		eval("var option" + i + " = document.createElement('option');");
		eval("var text" + i + " = document.createTextNode('" + val + "');");
		eval("option" + i).appendChild(eval("text" + i));
		eval("option" + i).value = eval(id); 
		element6.appendChild(eval("option" + i));
	}

	element6.setAttribute("class", "txtfield");
	element6.setAttribute("id", "cboEPMG" + index);
	element6.setAttribute("name", "cboEPMG" + index);
	element6.setAttribute("style", "width: 80px");
	cell6.setAttribute("width", "20%");
	cell6.appendChild(element6);
	
//	var element5 = document.createElement("select");
//	var pmgcode = ml.hPMGCode.value;
//	var pmgid = ml.hPMGID.value;
//	var criteria = '[' + pmgcode + ']';
//	var critid = '[' + pmgid + ']';
//	var tmp_criteria = new Array();
//	var tmp_critid = new Array();
//	tmp_criteria = criteria.split(',');
//	tmp_critid = critid.split(',');
//	//dynamic combo box
//	for (var i = 0; i < tmp_criteria.length; i++)
//	{
//		var val = eval("tmp_criteria[" + i + "]").replace('[', '').replace(']', '').replace(/'/g, '');
//		var id = eval("tmp_critid[" + i + "]").replace('[', '').replace(']', '').replace(/'/g, '');
//		eval("var option" + i + " = document.createElement('option');");
//		eval("var text" + i + " = document.createTextNode('" + val + "');");
//		eval("option" + i).appendChild(eval("text" + i));
//		eval("option" + i).value = eval(id); 
//		element5.appendChild(eval("option" + i));
//	}
//
//	//var option1 = document.createElement('option');
//	//var option2 = document.createElement('option');
//	//var text1 = document.createTextNode(criteria[0]);
//	//var text2 = document.createTextNode(criteria[1]);
//	//option1.appendChild(text1);
//	//option1.value = 1;
//	//element5.appendChild(option1);
//	//option2.appendChild(text2);
//	//option2.value = 2;
//	//element5.appendChild(option2);	
//	element5.setAttribute("class", "txtfield");
//	element5.setAttribute("id", "cboEPMG" + index);
//	element5.setAttribute("name", "cboEPMG" + index);
//	element5.setAttribute("style", "width: 80px");
//	cell6.appendChild(element5)
	document.frmCreateMultiLine.hEntIndex.value = index;
	var url = 'includes/scProductListEntitlementAjax.php?index=' + index;
	var prod_choicesE = new Ajax.Autocompleter(element1.id, element1d.id, url, {afterUpdateElement : getSelectionProductList2, indicator: element1s.id});
	
	element1.focus();
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



//rdoBReqt $_GET['promoID']
function enableFields()
{
	var ml = document.frmCreateSingleLine;
        var promo_code = ml.txtCode;
        var promo_desc = ml.txtDescription;
        var promo_sdate = ml.txtStartDate;
        var promo_edate = ml.txtEndDate;
        var buyin_cnt = 0;
        var entitlement_cnt = 0;
        var type = ml.cboType;
        var typeqty = ml.txtTypeQty;

        addRow();
        addRow2();
                            
	for (var i=0; i < ml.txtCode.length; i++)
	{
		if (ml.ml.txtCode[i].value = $_GET['promoID'])
		{
			if (ml.txtCode[i].value == 2)
			{         
  				ml.txtDescription.disabled = true;
				ml.txtStartDate.disabled = true;
				ml.txtSetStartDate.disabled = true;
				ml.txtEndDate.disabled = true;				
			}
			else
			{
				ml.txtDescription.disabled = false;
				ml.txtStartDate.disabled = false;
				ml.txtSetStartDate.disabled = false;
				ml.txtEndDate.disabled = false;					
			}
		}
	}
	return true;
}



function confirmCancel()
{
	if (confirm('Are you sure you want to cancel this transaction?') == false)
		return false;
	else
		return true;
}

function confirmSave()
{
	var ml = document.frmCreateMultiLine;
	var promo_code = ml.txtCode;
	var promo_desc = ml.txtDescription;
	var promo_sdate = ml.txtStartDate;
	var promo_edate = ml.txtEndDate;
	var buyin_cnt = ml.hBuyInCnt;
	var entitlement_cnt = ml.hEntitlementCnt;
	var type = ml.cboType;
	var typeqty = ml.txtTypeQty;
	var bTable = document.getElementById('dynamicTable');
	var bCount = bTable.rows.length;
	var eTable = document.getElementById('dynamicEntTable');
	var eCount = eTable.rows.length;
	
	buyin_cnt.value = bCount;
	entitlement_cnt.value = eCount;
	
	var s_date = Date.parse(promo_sdate.value);
    var sdate = new Date(s_date); 
    var e_date = Date.parse(promo_edate.value);
    var edate = new Date(e_date);
	
	if (Trim(promo_code.value) == "")
	{
		alert ('Promo Code required.');
		promo_code.focus();
		return false;
	}
	
	if (Trim(promo_desc.value) == "")
	{
		alert ('Promo Description required.');
		promo_desc.focus();
		return false;
	}
	
	if(sdate > edate)
	{			
		alert("End date should be the same or later than Start date.");
		ml.txtEndDate.select();
		ml.txtEndDate.focus();
		return false;
	}
	
	if (bCount == 1)
	{
		var a = eval('document.frmCreateMultiLine.txtProdCode1');
		
		if (a.value == "")
		{
			alert("Select Item Code.");
			a.focus();
			return false;
		}
	}
	
	if (type.value == 2)
	{
		if (typeqty.value == "")
		{
			alert("Selection No. required.");
			typeqty.focus();
			return false;
		}
		if (typeqty.value == "0")
		{
			alert("Selection No. must be greater than 0.");
			typeqty.focus();
			return false;
		}
		if (!isNumeric(typeqty.value))
		{
			alert("Invalid numeric format for Selection No.");
			typeqty.select();
			typeqty.focus();
			return false;
		}
	}
	
	if (eCount == 1)
	{
		var a = eval('document.frmCreateMultiLine.txtEProdCode1');
		
		if (a.value == "")
		{
			alert("Select Item Code.");
			a.focus();
			return false;
		}
	}
	
	if (confirm('Are you sure you want to save this transaction?') == false)
		return false;
	else
		return true;
}
function RemoveInvalidChars(strString)
{

	
	var iChars = "1234567890";
   	var strtovalidate = strString.value;
	var strlength = strtovalidate.length;
	var strChar;
	var ctr = 0;
	var newStr = '';
	
	if (strlength == 0)
	{
		return false;
	}

	for (i = 0; i < strlength; i++)
	{	
		strChar = strtovalidate.charAt(i);
		if 	(!(iChars.indexOf(strChar) == -1))
		{
			newStr = newStr + strChar;
		}
	}
	
	strString.value = newStr;

	return true;
}
function deleteRow(j, ctr){

	var table = document.getElementById('dynamicTable');
    var rowCount = table.rows.length;	
    
    
	var ml = document.frmCreateMultiLine;
	var len = ml.elements.length;
	if(rowCount != 1)
	{
	for (var i = 0; i < len; i++) 
	{
		
		var e = ml.elements[i];
		
	    if (e.name == "chkSelect[]") 
	    {
	       
			if(e.value == ctr)
			{
				e.checked = false;
				document.getElementById('dynamicTable').deleteRow(j);
		
			}
	    }
	}
	}
	else
	{
		return false;
	}
	
    	
    	return false;
   

}
function deleteRow2(j, ctr){

	var table = document.getElementById('dynamicEntTable');
    var rowCount = table.rows.length;	
    
    
	var ml = document.frmCreateMultiLine;
	var len = ml.elements.length;
	
	if(rowCount != 1)
	{
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkSelectEnt[]") 
	    {
	       
			if(e.value == ctr)
			{
				e.checked = false;
				document.getElementById('dynamicEntTable').deleteRow(j);
		
			}
	    }
	}
	}
	else
	{
		return false;
	}
	   	
    	return false;
}
function selection()
{  
	  var type = document.frmCreateMultiLine.cboType.value;

	  if(type == 1)
	  {
		  document.frmCreateMultiLine.txtTypeQty.readOnly = true;
		  document.frmCreateMultiLine.txtTypeQty.value = "";
	  }
	  else
	  {
		  document.frmCreateMultiLine.txtTypeQty.readOnly = false;
	  }
}
function disableEnterKey(a, e, cnt)
{
     var key;
     var str = a.id;
     var sub = 6 + eval(cnt.toString().length);
     var sub2 = 11 + eval(cnt.toString().length);
     var field = "txtQty" + cnt;
     var field2 = "txtProdCode" + cnt;
     
     var sub3 = 7 + eval(cnt.toString().length);
     var sub4 = 12 + eval(cnt.toString().length);
     var field3 = "txtEQty" + cnt;
     var field4 = "txtEProdCode" + cnt;

     if(window.event)
     {
          key = window.event.keyCode; //IE
     } 
     else
     {
          key = e.which; //firefox
     }
     
     if (str.substring(0, sub) == field && key == 13)
     {
    	 addRow();
    	 return false;
     }
     else if (str.substring(0, sub2) == field2 && key == 13)
     {
    	 var ctr = str.substring(sub2, str.length);
         var code = eval('document.frmCreateMultiLine.txtProdCode' + eval(ctr + 1));
         code.focus();
         code.select();
         return false;
     }
     else if (str.substring(0, sub3) == field3 && key == 13)
     {
    	 addRow2();
    	 return false;
     }
     else if (str.substring(0, sub4) == field4 && key == 13)
     {
    	 var ctr = str.substring(sub4, str.length);
         var code = eval('document.frmCreateMultiLine.txtEProdCode' + eval(ctr + 1));
         code.focus();
         code.select();
         return false;
     }
     
     return (key!=13);
}