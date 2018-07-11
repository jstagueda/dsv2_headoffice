// JavaScript Document

function getSelectionProductCriteriaList(text, li) 
{
	var txt = text.id;
	var ctr = txt.substr(11, txt.length);
	
	tmp = li.id;
	tmp_val = tmp.split("_");

	h = eval('document.frmCreateStepLevel.hProdID_criteria' + ctr);
	i = eval('document.frmCreateStepLevel.txtCriteria' + ctr);
	j = eval('document.frmCreateStepLevel.txtCDescription' + ctr);
	k = eval('document.frmCreateStepLevel.txtCPMG' + ctr);

	h.value = tmp_val[0];
	j.value = tmp_val[1];
	i.value = tmp_val[2];
	k.value = tmp_val[3];
	
	addRow();
}

function getSelectionProductList2(text, li) 
{
	var txt = text.id;
	var ctr = txt.substr(12, txt.length);
	
	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmCreateStepLevel.hEProdID' + ctr);
	i = eval('document.frmCreateStepLevel.txtEProdCode' + ctr);
	j = eval('document.frmCreateStepLevel.txtEProdDesc' + ctr);
	k = eval('document.frmCreateStepLevel.txtEQty' + ctr);
	l = eval('document.frmCreateStepLevel.hEUnitPrice' + ctr);
	m = eval('document.frmCreateStepLevel.hEpmgid' + ctr);
	//n = eval('document.frmCreateStepLevel.txtPmg' + ctr);
	o = eval('document.frmCreateStepLevel.cboEPMG' + ctr);
	p = eval('document.frmCreateStepLevel.cboECriteria' + ctr);

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
	var table = document.getElementById('dynamicBuyInTable');
	var rowCount = table.rows.length;
	var a = eval('document.frmCreateStepLevel.txtCriteria' + rowCount);
	
	if(a.value == "")
    {  
 	   return false;
    }
	else
    {
		var row = table.insertRow(rowCount);
		var index = eval(rowCount + 1);
		var ml = document.frmCreateStepLevel;
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
	
	//remove button
	var cellRem = row.insertCell(0);
    cellRem.setAttribute("align", "center");
    cellRem.setAttribute("class", "borderBR");
    
    var element1rem = document.createElement("input");
    element1rem.type = "button";
    element1rem.setAttribute("class", "btn");
    element1rem.setAttribute("id", "btnRemove" + index);
    element1rem.setAttribute("name", "btnRemove" + index);
    element1rem.setAttribute("value", "Remove");
    element1rem.onclick =  function () {deleteRow(this.parentNode.parentNode.rowIndex, index)};
    cellRem.appendChild(element1rem);
	
	//line no.
	var cell1 = row.insertCell(1);
	createCell(cell1, index, 'borderBR', 'center');
	
	//item code
	var cell2 = row.insertCell(2);
	cell2.setAttribute("align", "left");
	cell2.setAttribute("class", "borderBR padl5");
	
	var element1 = document.createElement("input");
	var element1h = document.createElement("input");
	var element1hup = document.createElement("input");
	var element1s = document.createElement("span");
	var element1d = document.createElement("div");
	
	//hidden product id
	element1h.type = "hidden";
	element1h.setAttribute("value", "");
	element1h.setAttribute("id", "hProdID_criteria" + index);
	element1h.setAttribute("name", "hProdID_criteria" + index);
	
	//span
	element1s.setAttribute("id", "indicatorCriteria" + index);
	element1s.setAttribute("name", "indicatorCriteria" + index);
	element1s.setAttribute("style", "display: none");
	element1s.innerHTML = ("<img src='images/ajax-loader.gif' />");
	
	//div
	element1d.setAttribute("id", "prod_choices_criteria" + index);
	element1d.setAttribute("name", "prod_choices_criteria" + index);
	element1d.setAttribute("class", "autocomplete");
	element1d.setAttribute("style", "display: none");
	
	element1.type = "input";
	element1.setAttribute("class", "txtfield");
	element1.setAttribute("id", "txtCriteria" + index);
	element1.setAttribute("name", "txtCriteria" + index);
	element1.setAttribute("style", "width: 120px");
	
	cell2.appendChild(element1);
	cell2.appendChild(element1h);
	cell2.appendChild(element1s);
	cell2.appendChild(element1d);
	
	//item description
	var cell3 = row.insertCell(3);
	cell3.setAttribute("align", "left");
	cell3.setAttribute("class", "borderBR padl5");
	
	var element2 = document.createElement("input");
	element2.type = "text";
	element2.setAttribute("class", "txtfield");
	element2.setAttribute("style", "width: 200px");
	element2.setAttribute("readonly", "yes");
	element2.setAttribute("id", "txtCDescription" + index);
	element2.setAttribute("name", "txtCDescription" + index);
	cell3.appendChild(element2);
	
	//pmg
	var cell6 = row.insertCell(4);
	cell6.setAttribute("align", "left");
	cell6.setAttribute("class", "padl5 borderBR");
		
	var elementpmgvalue = document.createElement("input");
	elementpmgvalue.type = "input";
	elementpmgvalue.setAttribute("value", "");
	elementpmgvalue.setAttribute("id", "txtCPMG" + index);
	elementpmgvalue.setAttribute("name", "txtCPMG" + index);
	elementpmgvalue.setAttribute("class", "txtfield");
	elementpmgvalue.setAttribute("readonly", "readonly");
	elementpmgvalue.setAttribute("style", "width: 100px");
	cell6.appendChild(elementpmgvalue);
	
	var url = 'includes/scProductRangeAjax.php?range=3&index=' + index;
	var prod_choices_criteria = new Ajax.Autocompleter(element1.id, element1d.id, url, {afterUpdateElement : getSelectionProductCriteriaList, indicator: element1s.id});
	
	element1.focus();
}

function addRow2() 
{		 
	var table = document.getElementById('dynamicEntTable');
	var rowCount = table.rows.length;
	
	 var a = eval('document.frmCreateStepLevel.txtEProdDesc' + rowCount);

     if(a.value == "")
     {  
  	   return false;
     }
     else
     {
    	var row = table.insertRow(rowCount);
    	var index = eval(rowCount + 1);
    	var ml = document.frmCreateStepLevel;
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
	
	//line no.
	var cell1 = row.insertCell(0);
	createCell(cell1, index, 'borderBR', 'center');
	
	//item code
	var cell2 = row.insertCell(1);
	cell2.setAttribute("align", "left");
	cell2.setAttribute("class", "borderBR padl5");
	
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
	var cell3 = row.insertCell(2);
	cell3.setAttribute("align", "left");
	cell3.setAttribute("class", "borderBR padl5");
	
	var element2 = document.createElement("input");
	element2.type = "text";
	element2.setAttribute("class", "txtfield");
	element2.setAttribute("style", "width: 200px");
	element2.setAttribute("readonly", "yes");
	element2.setAttribute("id", "txtEProdDesc" + index);
	element2.setAttribute("name", "txtEProdDesc" + index);
	cell3.appendChild(element2);
	
	//criteria
	var cell4 = row.insertCell(3);
	cell4.setAttribute("align", "center");
	cell4.setAttribute("class", "borderBR");
	
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
	var cell5 = row.insertCell(4);
	cell5.setAttribute("align", "center");
	cell5.setAttribute("class", "borderBR");
	
	var element4 = document.createElement("input");
	element4.type = "input";
	element4.setAttribute("class", "txtfield");
	element4.setAttribute("style", "width: 50px; text-align: right");
	element4.setAttribute("onkeypress", "return  disableEnterKey(event)");
	element4.setAttribute("id", "txtEQty" + index);
	element4.setAttribute("name", "txtEQty" + index);
	element4.setAttribute("value", "1");
	cell5.appendChild(element4);
	
	//pmg
	var cell6 = row.insertCell(5);
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
	
	var element5 = document.createElement("select");
	var pmgcode = ml.hPMGCode.value;
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
		element5.appendChild(eval("option" + i));
	}
	//var option1 = document.createElement('option');
	//var option2 = document.createElement('option');
	//var text1 = document.createTextNode(criteria[0]);
	//var text2 = document.createTextNode(criteria[1]);
	//option1.appendChild(text1);
	//option1.value = 1;
	//element5.appendChild(option1);
	//option2.appendChild(text2);
	//option2.value = 2;
	//element5.appendChild(option2);	
	element5.setAttribute("class", "txtfield");
	element5.setAttribute("id", "cboEPMG" + index);
	element5.setAttribute("name", "cboEPMG" + index);
	element5.setAttribute("style", "width: 80px");
	cell6.appendChild(element5)
	
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

function confirmCancel()
{
	if (confirm('Are you sure you want to cancel this transaction?') == false)
		return false;
	else
		return true;
}

function disableEnterKey(e)
{
     var key;      
     if(window.event)
     {
          key = window.event.keyCode; //IE
     } 
     else
     {
          key = e.which; //firefox
     }
     if(key == 13)
     {    	
    	addRow2();
    	return false;
     }  
}

function confirmSave()
{
	var ml = document.frmCreateStepLevel;
	var promo_code = ml.txtCode;
	var promo_desc = ml.txtDescription;
	var promo_sdate = ml.txtStartDate;
	var promo_edate = ml.txtEndDate;
	var minimum = ml.txtMinimum;
	var maximum = ml.txtMaximum;
	var buyin_cnt = ml.hBuyinCnt;
	var entitlement_cnt = ml.hEntitlementCnt;
	var type = ml.cboType;
	var typeqty = ml.txtTypeQty;
	var bTable = document.getElementById('dynamicBuyInTable');
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
	
	if (Trim(minimum.value) == "")
	{
		alert ('Minimum value required.');
		minimum.focus();
		return false;
	}
	
	if (!isNumeric(minimum.value))
	{
		alert("Invalid numeric format for Minimum value.");
		minimum.select();
		minimum.focus();
		return false;
	}
	
	if (Trim(maximum.value) == "")
	{
		alert ('Maximum value required.');
		maximum.focus();
		return false;
	}
	
	if (!isNumeric(maximum.value))
	{
		alert("Invalid numeric format for Maximum value.");
		maximum.select();
		maximum.focus();
		return false;
	}
	
	if (eval(minimum.value) > eval(maximum.value) || eval(minimum.value) == eval(maximum.value))
	{
		alert("Maximum value must be greater than Minimum value.");
		maximum.select();
		maximum.focus();
		return false;
	}
	
	if (bCount == 1)
	{
		var a = eval('document.frmCreateStepLevel.txtCriteria1');
		
		if (a.value == "")
		{
			alert("Select Item Code.");
			a.focus();
			return false;
		}
	}

	if (type.value == 1)
	{
		if (typeqty.value == "")
		{
			alert("Selection No. required.");
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
		var a = eval('document.frmCreateStepLevel.txtEProdCode1');
		
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
function validateDone()
{
	var eTable = document.getElementById('dynamicEntTable');
	var eCount = eTable.rows.length;
	var hasData = 0;
	
	if (eCount == 1)
	{
		var a = eval('document.frmCreateStepLevel.txtEProdCode1');
		
		if (a.value != "")
		{
			hasData = 1;
		}
	}
	else
	{
		hasData = 1;
	}
	
	if (hasData == 0)
	{
		alert("Add Step Level details first.");
		return false;
	}
	
	return true;
}