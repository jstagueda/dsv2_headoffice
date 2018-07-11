// JavaScript Document

function getSelectionProductCriteriaList(text, li) 
{
	var txt = text.id;
	var ctr = txt.substr(12, txt.length);
	
	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmCreateOverlaySet.hProdID_criteria');
	i = eval('document.frmCreateOverlaySet.txtCriteria');
	j = eval('document.frmCreateOverlaySet.txtCDescription');

	h.value = tmp_val[0];
	j.value = tmp_val[1];
	i.value = tmp_val[2];
}

function getSelectionProductList2(text, li) 
{
	var txt = text.id;
	var ctr = txt.substr(12, txt.length);
	
	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmCreateOverlaySet.hEProdID' + ctr);
	i = eval('document.frmCreateOverlaySet.txtEProdCode' + ctr);
	j = eval('document.frmCreateOverlaySet.txtEProdDesc' + ctr);
	k = eval('document.frmCreateOverlaySet.txtEQty' + ctr);
	l = eval('document.frmCreateOverlaySet.hEUnitPrice' + ctr);
//	m = eval('document.frmCreateOverlaySet.hEpmgid' + ctr);
	n = eval('document.frmCreateOverlaySet.cboEPMG' + ctr);
	o = eval('document.frmCreateOverlaySet.cboECriteria' + ctr);

	o.focus();
	//k.focus();
	//k.select();
	
	h.value = tmp_val[0];
	j.value = tmp_val[1];
	l.value = tmp_val[2];
	i.value = tmp_val[4];
	n.value = tmp_val[3];
	
	for ( var i = 0; i < n.options.length; i++ ) 
	{
		if (n.options[i].value == tmp_val[3]) 
		{
			n.options[i].selected = true;
			//return;
		}
	}
	
	for ( var i = 0; i < n.options.length; i++)
	{
		if (tmp_val[3] == 1)
		{
			n.remove(1);
			return;
		}
		
		if (tmp_val[3] == 2)
		{
			n.remove(0);
			return;
		}
	}
}

function addRow2() 
{	
	var ml = document.frmCreateOverlaySet;
	var table = document.getElementById('dynamicEntTable');
	var rowCount = table.rows.length;
	var cnt2 = document.frmCreateOverlaySet.hEntIndex.value;
	
	if(cnt2 >  rowCount)
   	{
   		
   		 var a = eval('document.frmCreateOverlaySet.txtEProdDesc' + cnt2);
    		
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
   		 var a = eval('document.frmCreateOverlaySet.txtEProdDesc' + rowCount);
     		
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
	
//	 var a = eval('document.frmCreateOverlaySet.txtEProdDesc' + rowCount);

//     if(a.value == "")
//     {  
//  	   return false;
//     }
//     else
//     {
//    	 var row = table.insertRow(rowCount);
//    	 var index = eval(rowCount + 1);
//    	 var ml = document.frmCreateOverlaySet;
//   		
//     }
	
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
    element1rem.onclick =  function () {deleteRow2(this.parentNode.parentNode.rowIndex)};
    cellRem.appendChild(element1rem);
	//line no.
	var cell1 = row.insertCell(1);
	createCell(cell1, index, 'borderBR padl5', 'center');
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
	
	//div
	element1d.setAttribute("id", "prod_choicesE" + index);
	element1d.setAttribute("name", "prod_choicesE" + index);
	element1d.setAttribute("class", "autocomplete");
	element1d.setAttribute("style", "display: none");
	
	element1.type = "input";
	element1.setAttribute("class", "txtfield");
	element1.setAttribute("id", "txtEProdCode" + index);
	element1.setAttribute("name", "txtEProdCode" + index);
	element1.setAttribute("style", "width: 80px");
	
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
	element2.setAttribute("style", "width: 220px");
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
	cell5.setAttribute("width", "13%");
	var element4 = document.createElement("input");
	element4.type = "input";
	element4.setAttribute("class", "txtfield");
	element4.setAttribute("style", "width: 50px; text-align: right");
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
	
//	//hidden pmgid
//	var elementhpmgid = document.createElement("input");
//	elementhpmgid.type = "hidden";
//	elementhpmgid.setAttribute("value", "");
//	elementhpmgid.setAttribute("id", "hEpmgid" + index);
//	elementhpmgid.setAttribute("name", "hEpmgid" + index);
//	
//	var elementpmgvalue = document.createElement("input");
//	elementpmgvalue.type = "input";
//	elementpmgvalue.setAttribute("value", "");
//	elementpmgvalue.setAttribute("id", "txtPmg" + index);
//	elementpmgvalue.setAttribute("name", "txtPmg" + index);
//	elementpmgvalue.setAttribute("class", "txtfield");
//	elementpmgvalue.setAttribute("readonly", "readonly");
//	elementpmgvalue.setAttribute("style", "width: 130px");
//	
//	cell6.appendChild(elementhpmgid);
//	cell6.appendChild(elementpmgvalue);
	
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
//	var option1 = document.createElement('option');
//	var option2 = document.createElement('option');
//	var text1 = document.createTextNode(criteria[0]);
//	var text2 = document.createTextNode(criteria[1]);
//	option1.appendChild(text1);
//	option1.value = 1;
//	element5.appendChild(option1);
//	option2.appendChild(text2);
//	option2.value = 2;
//	element5.appendChild(option2);	
	element5.setAttribute("class", "txtfield");
	element5.setAttribute("id", "cboEPMG" + index);
	element5.setAttribute("name", "cboEPMG" + index);
	element5.setAttribute("style", "width: 80px");
	cell6.appendChild(element5)
	document.frmCreateOverlaySet.hEntIndex.value = index;
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

function confirmAdd()
{
	var ml = document.frmCreateOverlaySet;
	var range = ml.cboRange;
	var selection = ml.hProdID_criteria;
	var breqid = 0;
		
	//check buy-in requirement
	for (var i=0; i < ml.rdoBReqt.length; i++)
	{
		if (ml.rdoBReqt[i].checked)
		{
			if (ml.rdoBReqt[i].value == 2)
			{
				var minimum = ml.txtPHMinimum;
				break;
			}
			else
			{
				var minimum = ml.txtMinimum;
				break;
			}
		}
	}
		
	if (range.value == 0)
	{
		alert ('Range required.');
		range.focus();
		return false;
	}
	
	if (selection.value == "")
	{
		alert ('Selection required.');
		selection.focus();
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
		
	if (confirm('Are you sure you want to add this Buy-in requirement?') == false)
		return false;
	else
		return true;
}

function confirmSave()
{
	var ml = document.frmCreateOverlaySet;
	var promo_code = ml.txtCode;
	var promo_desc = ml.txtDescription;
	var promo_sdate = ml.txtStartDate;
	var promo_edate = ml.txtEndDate;
	var buyin_cnt = ml.hBuyInCnt;
	var entitlement_cnt = ml.hEntitlementCnt;
	var type = ml.cboType;
	var typeqty = ml.txtTypeQty;
	var eTable = document.getElementById('dynamicEntTable');
	var eCount = eTable.rows.length;
	var breqid = 0;
	
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
	
	//check buy-in requirement
	for (var i=0; i < ml.rdoBReqt.length; i++)
	{
		if (ml.rdoBReqt[i].checked)
		{
			if (ml.rdoBReqt[i].value == 2)
			{
				var minimum = ml.txtPHMinimum;
				break;
			}
			else
			{
				var minimum = ml.txtMinimum;
				break;
			}
		}
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
	
	/*if (buyin_cnt.value == 0)
	{
		alert('Add atleast 1 Buy-in requirement.');
		return false;
	}*/
	if (eCount == 1)
	{
		var a = eval('document.frmCreateOverlaySet.txtEProdCode1');
		
		
		if (a.value != "")
		{
			if (type.value == 2)
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
		}
		else
		{
			alert('Add atleast 1 Entitlement.');
			return false;
		}
	}
	else
	{
		if (type.value == 2)
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
	}
	addRow2();
	var eTable2 = document.getElementById('dynamicEntTable');
	var eCount2 = eTable.rows.length;
	entitlement_cnt.value = eCount2;
	if (confirm('Are you sure you want to save this transaction?') == false)
		return false;
	else
		return true;
}

function enableFields()
{
	var ml = document.frmCreateOverlaySet;
	for (var i=0; i < ml.rdoBReqt.length; i++)
	{
		if (ml.rdoBReqt[i].checked)
		{
			if (ml.rdoBReqt[i].value == 2)
			{
				ml.cboCriteria.disabled = true;
				ml.txtMinimum.disabled = true;
				ml.txtSetStartDate.disabled = true;
				ml.anchorSetStartDate.disabled = true;
				ml.txtSetEndDate.disabled = true;
				ml.anchorSetEndDate.disabled = true;
				ml.cboPHCriteria.disabled = false;
				ml.txtPHMinimum.disabled = false;
			}
			else
			{
				ml.cboPHCriteria.disabled = true;
				ml.txtPHMinimum.disabled = true;
				ml.cboCriteria.disabled = false;
				ml.txtMinimum.disabled = false;
				ml.txtSetStartDate.disabled = false;
				ml.anchorSetStartDate.disabled = false;
				ml.txtSetEndDate.disabled = false;
				ml.anchorSetEndDate.disabled = false;
			}
		}
	}
	return true;
}
function deleteRow2(i){

    var table = document.getElementById('dynamicEntTable');
    var rowCount = table.rows.length;

    if(rowCount != 1)
    { 
    	document.getElementById('dynamicEntTable').deleteRow(i);
    	document.frmCreateOverlaySet.hEntitlementCnt.value = rowCount;
    }
    else
    {
        return false;
    }
}
function selection()
{  
	  var type = document.frmCreateOverlaySet.cboType.value;

	  if(type == 1)
	  {
		  document.frmCreateOverlaySet.txtTypeQty.readOnly = true;
		  document.frmCreateOverlaySet.txtTypeQty.value = "";
	  }
	  else
	  {
		  document.frmCreateOverlaySet.txtTypeQty.readOnly = false;
	  }
} 

function CheckInclude()
{	
	var ci = document.frmCreateOverlaySet.elements["chkSelect[]"];

	for(i=0; i< ci.length; i++)
	{
		if(ci[i].checked == false)
		{
			document.frmCreateOverlaySet.chkAll.checked = false;
		}
	}
			
	if (document.frmCreateOverlaySet.elements["chkSelect[]"].value > 1)
	{
		if(ci.checked == false)
		{
			document.frmCreateOverlaySet.chkAll.checked = false;
		}
	}
}

function checkAll(bin) 
{
	var elms = document.frmCreateOverlaySet.elements;

	for (var i = 0; i < elms.length; i++)
	{
		if (elms[i].name == 'chkSelect[]') 
	  	{
			elms[i].checked = bin;		  
	  	}			
	}
}

function disableEnterKey(a, e, cnt)
{
     var key;
     var str = a.id;
     var sub = 7 + eval(cnt.toString().length);
     var sub2 = 12 + eval(cnt.toString().length);
     var field = "txtEQty" + cnt;
     var field2 = "txtEProdCode" + cnt;

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
    	 addRow2();
    	 return false;
     }
     else if (str.substring(0, sub2) == field2 && key == 13)
     {
    	 var ctr = str.substring(sub2, str.length);
         var code = eval('document.frmCreateOverlaySet.txtEProdCode' + eval(ctr + 1));
         code.focus();
         code.select();
         return false;
     }
     
     return (key!=13);
}