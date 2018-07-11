<?php
	require_once "../../initialize.php";
	include IN_PATH.DS."scSetPromoDetails.php";
?>

<!-- calendar stylesheet -->
<link rel="stylesheet" type="text/css" href="../../css/ems.css">
<link rel="stylesheet" type="text/css" media="all" href="../../css/calendar-win2k-cold-1.css" title="win2k-cold-1" />
<script type="text/javascript" src="../../js/popup-calendar/calendar.js"></script>
<script type="text/javascript" src="../../js/popup-calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="../../js/popup-calendar/calendar-setup.js"></script>
<!-- product list -->
<script language="javascript" src="../../js/jsUtils.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/prototype.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/scriptaculous.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/jquery-1.4.2.min.js"  type="text/javascript"></script>
<script language="javascript" src="../../js/jquery-ui-1.8.5.custom.min.js"  type="text/javascript"></script>
<script src="../../js/shortcut.js" type="text/javascript"></script>

<script language="javascript" type="text/javascript">
var $j = jQuery.noConflict();

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
     	return false;
     }
}

function getSelectionProductCriteriaList(text, li) 
{
	var txt = text.id;
	var ctr = txt.substr(12, txt.length);
	
	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmSingleLine.hProdID_criteria');
	i = eval('document.frmSingleLine.txtCriteria');
	j = eval('document.frmSingleLine.txtCDescription');

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
	h = eval('document.frmSingleLine.hEProdID' + ctr);
	i = eval('document.frmSingleLine.txtEProdCode' + ctr);
	j = eval('document.frmSingleLine.txtEProdDesc' + ctr);
	k = eval('document.frmSingleLine.txtEQty' + ctr);
	l = eval('document.frmSingleLine.hEUnitPrice' + ctr);
//	m = eval('document.frmCreateSet.hEpmgid' + ctr);
	n = eval('document.frmSingleLine.cboEPMG' + ctr);
	o = eval('document.frmSingleLine.cboECriteria' + ctr);

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

function confirmDelete(inc)
{
	if (inc == 1)
  	{
  		inc = 2;
  	}
  	else
  	{
	  	inc = 1;
  	}

  	if (confirm('Are you sure you want to delete this promo?') == false)
  		return false;
  	else
  		return true;
}

function enableFields()
{
  	var ml = document.frmSingleLine;
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

function addRow2() 
{
	var ml = document.frmSingleLine;
  	var table = document.getElementById('dynamicEntTable');
  	var rowCount = table.rows.length;
	var cnt =document.frmSingleLine.hEntitlementCnt.value;
	var cnt2 =document.frmSingleLine.hEntitlementIndex.value;

	  	if(cnt2 >  rowCount)
	  	{
	  	
	  	   var a = eval('document.frmSingleLine.txtEProdDesc' + cnt2);
	   		
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
	  		 var a = eval('document.frmSingleLine.txtEProdDesc' + rowCount);

	  		if(a != undefined)
		   	{	
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
	  		 else
	         {
	        	 var index = eval(rowCount + 1);
	        	 var row = table.insertRow(rowCount);
	         }
	  	}
  	      
     var cell1 = row.insertCell(0);
     cell1.setAttribute("align", "center");
   	 cell1.setAttribute("class", "borderBR");
   	 cell1.setAttribute("width", "10%");
     var element1rem = document.createElement("input");
     element1rem.type = "button";
     element1rem.setAttribute("class", "btn");
     element1rem.setAttribute("style", "width: 65px");
     element1rem.setAttribute("id", "btnRemove" + index);
     element1rem.setAttribute("name", "btnRemove" + index);
     element1rem.setAttribute("value", "Remove");
     element1rem.onclick = function () {deleteRow2(this.parentNode.parentNode.rowIndex)};
  	 
     cell1.appendChild(element1rem);
 
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
    element1s.innerHTML = ("<img src='../../images/ajax-loader.gif' />");
  	//div
  	element1d.setAttribute("id", "prod_choicesE" + index);
  	element1d.setAttribute("name", "prod_choicesE" + index);
  	element1d.setAttribute("class", "autocomplete");
  	element1d.setAttribute("style", "display: none");
  	
  	element1.type = "input";
  	element1.setAttribute("class", "txtfield");
  	element1.setAttribute("id", "txtEProdCode" + index);
  	element1.setAttribute("name", "txtEProdCode" + index);
  	element1.setAttribute("onKeyPress", "return disableEnterKey(this, event, " + index + ")");
  	element1.setAttribute("style", "width: 80px");
  	
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
  	element2.setAttribute("style", "width: 220px");
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
  	element4.setAttribute("onBlur", "addRow2()");
  	element4.setAttribute("onKeyPress", "return disableEnterKey(this, event, " + index + ")");
  	element4.setAttribute("id", "txtEQty" + index);
  	element4.setAttribute("name", "txtEQty" + index);
  	element4.setAttribute("value", 1);
  	cell5.appendChild(element4);
  	
  	//pmg
  	var cell6 = row.insertCell(5);
  	cell6.setAttribute("align", "center");
  	cell6.setAttribute("class", "borderBR");

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
	
  	element5.setAttribute("class", "txtfield");
  	element5.setAttribute("id", "cboEPMG" + index);
  	element5.setAttribute("name", "cboEPMG" + index);
  	element5.setAttribute("style", "width: 80px");
  	cell6.appendChild(element5)
  	
  	document.frmSingleLine.hEntitlementCnt.value = rowCount + 1;
    document.frmSingleLine.hEntitlementIndex.value = index;
  	
  	var url = '../../includes/scProductListEntitlementAjax.php?index=' + index;
  	var prod_choicesE = new Ajax.Autocompleter(element1.id, element1d.id, url, {afterUpdateElement : getSelectionProductList2, indicator: element1s.id});
}

function deleteRow2(i)
{
	var table = document.getElementById('dynamicEntTable');
	var rowCount = table.rows.length;

  	if(rowCount != 1)
  	{	
  		document.getElementById('dynamicEntTable').deleteRow(i);
  		document.frmSingleLine.hEntitlementCnt.value = rowCount - 1;
  	}
  	else
  	{
      	return false;
  	}

}
  
function deleteRow(i)
{

	var table = document.getElementById('buyTable');
  	var rowCount = table.rows.length;

  	if(rowCount-1 != 1)
  	{	
  		document.getElementById('buyTable').deleteRow(i);
  		document.frmSingleLine.hbuyCnt.value = rowCount  - 1;
  	}
  	else
  	{
		return false;
  	}
}

function selection()
{  
	var type = document.frmSingleLine.cboType.value;

  	if(type == 1)
  	{
  		document.frmSingleLine.txtTypeQty.readOnly = true;
	  	document.frmSingleLine.txtTypeQty.value = "";
  	}
  	else
  	{
  		document.frmSingleLine.txtTypeQty.readOnly = false;
  	}
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
  
function confirmAdd()
{
  	 var table = document.getElementById('buyTable');
  	 var rowCount = table.rows.length;

     var ml = document.frmSingleLine;
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
 	
 
 	if (confirm('Are you sure you want to add this Buy-in requirement?') == false)
 		return false;
 	else
 	    var table = document.getElementById('buyTable');
	    var rowCount = table.rows.length;		  
	    document.frmSingleLine.hbuyCnt.value = rowCount;
 		return true;
}

function confirmSave()
{
  	var ml = document.frmSingleLine;
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
	var buyin = ml.hcntr;
	
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
		
		
		if (buyin.value == 0)
		{
			alert('Add atleast 1 Buy-in requirement.');
			return false;
		}
		if (eCount == 1)
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
//			var a = eval('document.frmSingleLine.txtEProdCode1');
//			
//			
//			if (a.value != "")
//			{
//				if (type.value == 2)
//				{
//					if (typeqty.value == "")
//					{
//						alert("Selection No. required.");
//						typeqty.focus();
//						return false;
//					}
//					if (!isNumeric(typeqty.value))
//					{
//						alert("Invalid numeric format for Selection No.");
//						typeqty.select();
//						typeqty.focus();
//						return false;
//					}
//				}
//			}
//			else
//			{
//				alert('Add atleast 1 Entitlement.');
//				return false;
//			}
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
		
		if (confirm('Are you sure you want to save this transaction?') == false)
		{
			return false;
		}
		else
		{
			//addRow2();
		  	var table = document.getElementById('buyTable');
		  	var rowCount = table.rows.length;		  
		  	document.frmSingleLine.hbuyCnt.value = rowCount - 1;
			//	opener.location.href = '../../index.php?pageid=66&msg=Successfully Updated promo.';
			//  window.close();
		  return true;
	}
}

function CheckInclude()
{	
  	var ci = document.frmSingleLine.elements["chkSelect[]"];

  	for(i=0; i< ci.length; i++)
  	{
  		if(ci[i].checked == false)
  		{
  			document.frmSingleLine.chkAll.checked = false;
  		}
  	}
  			
  	if (document.frmSingleLine.elements["chkSelect[]"].value > 1)
  	{
  		if(ci.checked == false)
  		{
  			document.frmSingleLine.chkAll.checked = false;
  		}
  	}
}

function checkAll(bin) 
{
  	var elms = document.frmSingleLine.elements;

  	for (var i = 0; i < elms.length; i++)
  	{
  		if (elms[i].name == 'chkSelect[]') 
  	  	{
  			elms[i].checked = bin;		  
  	  	}			
  	}
}
 
function displayRow(ctr)
{ 
	//  var row = document.getElementById("captionRow" + ctr); 
	//  if (row.style.display == '') row.style.display = 'none'; 
	//  else row.style.display = '';
	
	
	var ml = document.frmSingleLine;
	var len = ml.elements.length;
	
	for (var i = 0; i < len; i++) 
	{
		var e = ml.elements[i];
	    if (e.name == "chkSelect[]") 
	    {
	        alert(e.value);
	        alert(ctr);
			if(e.value == ctr)
			{
				e.checked = true;
				e.value = ctr - 1;
			}
	    }
	}
	return false;
}

function RemoveInvalidChars(strString)
{
     var iChars = "1234567890.";
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
}
</script>

<style type="text/css">
<!--
div.autocomplete {
  position:absolute;
  /*width:300px;*/
  background-color:white;
  border:1px solid #888;
  margin:0px;
  padding:0px;
}

div.autocomplete span { position:relative; top:2px;} 
div.autocomplete ul {
  list-style-type:none;
  margin:0px;
  padding:0px;
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;  
}
div.autocomplete ul li.selected { background-color: #ffb;}
div.autocomplete ul li {
  list-style-type:none;
  display:block;
  margin:0;
  border-bottom:1px solid #888;
  padding:2px;
  /*height:20px;*/
  font-family: Verdana, Arial, Helvetica, sans-serif;
  font-size: 10px;
  cursor:pointer;
}
-->
</style>

<form name="frmSingleLine" method="post" action="promo_setpromoDetails.php?prmsid=<?php echo $_GET['prmsid'];?>&inc=<?php echo $_GET['inc'];?> ">
<?php
if ($errmsg != "") {
	echo "<br>";
?>
<br>
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td>
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="70%" class="txtreds">&nbsp;<b><?php echo $errmsg; ?></b></td>
		</tr>
		</table>
	</td>
</tr>
</table>
<?php

}
?>
<br>
<body onload="return enableFields();">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td class="tabmin">
	<input type="hidden" id="hRangeID" name="hRangeID" value="<?php echo $levelid; ?>">
	<input type="hidden" id="hPMGID" name="hPMGID" value="<?php echo $pmg_id; ?>">
	<input type="hidden" id="hPMGCode" name="hPMGCode" value="<?php echo $pmg_code; ?>">
	<input type="hidden" id="hbuyCnt" name="hbuyCnt" value="<?php echo $totcntbuy;?>">
	<input type="hidden" id="hbuyCnt2" name="hbuyCnt2" value="">
	<input type="hidden" id="hEntitlementCnt" name="hEntitlementCnt" value="<?php echo $totcntent;?>">
	<input type="hidden" id="hEntitlementIndex" name="hEntitlementIndex" value="<?php echo $totcntent;?>">
	</td> 
	<td class="tabmin2"><div align="left" class="padl5 txtredbold">Promo Header</div></td>
	<td class="tabmin3">&nbsp;</td>
</tr>
</table>

<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td width="50%">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="30%">&nbsp;</td>
					<td width="70%" align="right">&nbsp;</td>
				</tr>			
				<tr>
				    <td height="20"><div align="right" class="txtpallete"><strong>Promo Code :</strong></div></td>
				    <td height="20">&nbsp;&nbsp;<input name="txtCode" type="text" class="txtredbold txtfieldLabel" id="txtCode" value="<?php if (isset($_POST['txtCode'])) { echo $_POST['txtCode']; }else{echo $promocode;} ?>" size="30" readonly="yes"></td>
			    </tr>
			    <tr>
				    <td height="20"><div align="right" class="txtpallete"><strong>Promo Description :</strong></div></td>
				    <td height="20">&nbsp;&nbsp;<input name="txtDescription" type="text" class="txtfield" id="txtDescription" value="<?php if (isset($_POST['txtDescription'])) { echo $_POST['txtDescription']; }else{echo $promodesc;} ?>" style="width: 362px;" maxlength="60">
					</td>
			    </tr>
				<tr>
				  	<td height="20"><div align="right" class="txtpallete"><strong>Start Date: </strong></div></td>
				  	<td height="20">&nbsp;
						<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php if (isset($_POST['txtStartDate'])) { $tmpsdate = strtotime($_POST['txtStartDate']); $sdate = date("m/d/Y",$tmpsdate); echo $sdate; } else { echo $today; } ?>">
						<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>
				<tr>
				  	<td height="20"><div align="right" class="txtpallete"><strong>End Date: </strong></div></td>
				  	<td height="20">&nbsp;
						<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php if (isset($_POST['txtEndDate'])) { $tmpedate = strtotime($_POST['txtEndDate']); $edate = date("m/d/Y",$tmpedate); echo $edate; } else { echo $end; } ?>">
						<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
						<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>	
				<tr>
				  	<td height="20"><div align="right" class="txtpallete"><strong>Purchase Requirement Type: </strong></div></td>
				  	<td height="20">&nbsp;
				  	<?php if(isset($_SESSION['buyins']))
				  	  {
					  	  if (sizeof($_SESSION['buyins']))
							   {
								$buyin_list = $_SESSION["buyins"];
								$rowalt=0;
													//while($row = $rspromobuyin->fetch_object())
								for ($i=0, $n=sizeof($buyin_list); $i < $n; $i++ ) 
									{
										$preqttype2 = $buyin_list[$i]['PReqType'];
									}
									
							   }				  	  	
				  	  ?>
						<select name="cboPReqtType" disabled="disabled" class="txtfield" id="cboPReqtType">
							<option class="txtpallete" value="1" <?php if ($preqttype2 == 1) { echo "selected";} ?>>Single</option>
							<option class="txtpallete" value="2" <?php if ($preqttype2 == 2) { echo "selected";} ?>>Cumulative</option>
						</select>
					<?php 	
				  	  }else{
				  	 ?>
				  	 
						<select name="cboPReqtType" class="txtfield" id="cboPReqtType">
							<option value="1" <?php if (isset($_POST['cboPReqtType'])) { if ($_POST['cboPReqtType'] == 1) { echo "selected";} } ?>>Single</option>
							<option value="2" <?php if (isset($_POST['cboPReqtType'])) { if ($_POST['cboPReqtType'] == 2) { echo "selected";} } ?>>Cumulative</option>
						</select>
						<?php 
						}
						?>	
			
					</td>
				</tr>
				<tr>
				  	<td height="20"><div align="right" class="txtpallete"><strong>Buy-in Requirement: </strong></div></td>
				  	<td height="20">
				  		<input type="radio" name="rdoBReqt" id="rdoBReqt" value="2" 
				  		<?php if (isset($_POST['rdoBReqt'])) 
				  				  { 
				  				  	if ($_POST['rdoBReqt'] == 2)
				  				  	{

				  				  	   echo "checked";
				  				  	}
				  				  }
				  		 		else if ($ovrlaytype == 2)
				  		 		     {
				  		 		     	
				  		 		     	echo "checked";
				  		 		     }
				  		 		     ?> 
				  		
				  		 onClick="return enableFields();"><font class="txtpallete">Selection	
					</td>
				</tr>	
				<tr>
				  	<td height="20">&nbsp;</td>
				  	<td height="20" class="txtpallete">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Criteria:&nbsp;
				  		<select name="cboPHCriteria" class="txtfield" id="cboPHCriteria" style="width: 150px;" <?php if (isset($_POST['rdoBReqt'])){if($_POST['rdoBReqt'] == 2){?>disabled="yes" <?php }}else if($ovrlaytype == 2){?>disabled="yes"<?php }?>>
							<option value="1" <?php if ($overlayQty != "") { echo "selected";}  ?>>Quantity</option>
							<option value="2" <?php if ($overlayAmt != "") { echo "selected";}  ?>>Amount</option>
						</select>	
					</td>
				</tr>	
				<tr>
				  	<td height="20">&nbsp;</td>
				  	<td height="20" class="txtpallete">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Minimum Value:&nbsp;
				  		<input name="txtPHMinimum" type="text" value="<?php if (isset($_POST['txtPHMinimum'])) { echo $_POST['txtPHMinimum']; } else if($overlayQty != "") {echo $overlayQty;} else {echo $overlayAmt;}?>"  <?php if (isset($_POST['rdoBReqt'])){if($_POST['rdoBReqt'] == 2){?>disabled="yes" <?php }}else if($ovrlaytype == 2){?>disabled="yes"<?php }?>class="txtfield" style="width: 150px">
					</td>
				</tr>	
				<tr>
				  	<td height="20">&nbsp;</td> 
				  	<td height="20">
				  		<input type="radio" name="rdoBReqt" id="rdoBReqt" value="1" onClick="return enableFields();"
				  			<?php if (isset($_POST['rdoBReqt'])) 
			  				  { 
			  				  	if ($_POST['rdoBReqt'] == 1)
			  				  	{			  				  		
			  				  	   echo "checked";
			  				  	}
			  				  }
			  		 		else if ($ovrlaytype == 1)
			  		 		     {
			  		 		     	echo "checked";
			  		 		     }
			  		 		     ?> 
				  		
				  		
				  		><font class="txtpallete">Multiple Buy-in Requirement	
					</td>
				</tr>		
				<tr>
					<td colspan="2" height="20">&nbsp;</td>
				</tr>
				</table>
			</td>
			<td width="50%" valign="top">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="15%">&nbsp;</td>
					<td width="85%">&nbsp;</td>
				</tr>
				<tr>
				    <td height="20" valign="top"><div align="right" class="txtpallete"><strong>Max Availment :</strong></div></td>
				    <td height="20">
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				    	<?php
					    	if($rs_promoAvailment->num_rows)
							{
								while($row = $rs_promoAvailment->fetch_object())
								{
									if (isset($_POST['txtMaxAvail'.$row->GSUTypeID]))
									{
										$txt = $_POST['txtMaxAvail'.$row->GSUTypeID];
									}
									else
									{
										$txt = $row->MaxAvailment;
									}
									echo "<tr>
					    						<td width='15%' height='20'><div align='right' class='txtpallete'><strong>$row->Name :</strong></div></td>
					    						<td width='75%' height='20'>
					    						<input type='text' id='txtMaxAvail$row->GSUTypeID' class='txtfield' name='txtMaxAvail$row->GSUTypeID' value='$txt' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail$row->GSUTypeID);'></td>
					    						
					    				</tr>";
								}
								$rs_promoAvailment->close();
							}
							else
							{
								if($rs_gsutype->num_rows)
								{
									while($row = $rs_gsutype->fetch_object())
									{
										if (isset($_POST['txtMaxAvail'.$row->ID]))
										{
											$txt = $_POST['txtMaxAvail'.$row->ID];
										}
										else
										{
											$txt = "";
										}
										echo "<tr>
						    					<td width='15%' height='20'><div align='right' class='txtpallete'><strong>$row->Name :</strong></div></td>
						    					<td width='75%' height='20'>&nbsp;
					    						<input type='text' id='txtMaxAvail$row->ID' class='txtfieldg' name='txtMaxAvail$row->ID' value='$txt' onkeyup='javascript:RemoveInvalidChars(txtMaxAvail$row->ID);'></td>
						    				</tr>";
									}
									$rs_gsutype->close();
								}
							}
				    	?>
				    	</table>
				    </td>
			    </tr>
			    <tr>
				    <td height="20"><div align="right" class="txtpallete"><strong>Is Plus Plan :</strong></div></td>
				    <td height="20"><input type="checkbox" name="chkPlusPlan" value="1" <?php if ($prmPplan == 1) { echo "checked"; } ?></td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
<tr>
	<td width="47%" valign="top">	
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
						<tr>
							<td height="20" width="30%">&nbsp;</td>
							<td height="20" width="70%" align="right">&nbsp;</td>
						</tr>			
						<tr>
						    <td height="20"><div align="right" class="txtpallete"><strong>Overlay No. :</strong></div></td>
						    <td height="20" class="txtredbold">&nbsp;&nbsp;&nbsp;<?php echo $buycnt + 1; ?></td>
					    </tr>
					    <tr>
						    <td height="21"><div align="right" class="txtpallete"><strong>Range :</strong></div></td>
						    <td height="21">&nbsp;
						    	<select name="cboRange" style="width:150px; height:20px;" class="txtfield" onChange="document.frmSingleLine.submit();">
		                            <option value="0">[SELECT HERE]</option>
		                          	<?php 
		                              	if ($rsprodlevel->num_rows)
		                              	{
		                                  	while ($row = $rsprodlevel->fetch_object())	
		                                  	{
		                                  		if (isset($_POST['cboRange']))
		                                  		{
		                                  			if ($_POST['cboRange'] == $row->ID)
		                                  			{
		                                  				$sel = 'selected';		                                  				
		                                  			}		              
		                                  			else
		                                  			{
		                                  				$sel = '';
		                                  			}                    			
		                                  		}
		                                      	echo "<option value='".$row->ID."' $sel >".$row->Name."</option>";
		                                  	}
		                                  	$rsprodlevel->close();
		                              	}
		                          	?>
								</select>
						    </td>
					    </tr>
					    <tr>
						    <td height="22"><div align="right" class="txtpallete"><strong>Selection :</strong></div></td>
						    <td height="22">&nbsp;
						    <?php
							echo"
								 <input name='txtCriteria' type='text' class='txtfield' id='txtCriteria' style='width: 75px;' value=''/>
								 <span id='indicatorCriteria' style='display: none'><img src='../../images/ajax-loader.gif' alt='Working...' /></span>                                    
								 <div id='prod_choices_criteria' class='autocomplete' style='display:none'></div>
								<script type='text/javascript'>							
								 //<![CDATA[
								 	var a = eval('document.frmSingleLine.hRangeID');
								 	var u = '../../includes/scProductRangeAjax.php?range=' + a.value; 
		                        	var prod_choices_criteria = new Ajax.Autocompleter('txtCriteria', 'prod_choices_criteria', u, {afterUpdateElement : getSelectionProductCriteriaList, indicator: 'indicatorCriteria'});																			
		                        //]]>
		                        </script>
								<input name='txtCDescription' type='text' class='txtfield' id='txtCDescription' style='width: 250px;' value='' readonly='yes' />
						    	<input name='hProdID_criteria' type='hidden' id='hProdID_criteria' value=''/>
								</div>
							</td>"; 
							?>
						    </td>
						</tr>
					    <tr>
						    <td height="22"><div align="right" class="txtpallete"><strong>Criteria :</strong></div></td>
						    <td height="22">&nbsp;
						    	<select name="cboCriteria" class="txtfield" id="cboCriteria" style="width: 150px;" <?php if (isset($_POST['rdoBReqt'])){if($_POST['rdoBReqt'] == 1){?>disabled="yes" <?php }}else if($ovrlaytype == 1){?>disabled="yes"<?php }?>>
									<option value="1">Quantity</option>
									<option value="2">Amount</option>
								</select>
						    </td>
					    </tr>
					    <tr>
						    <td height="22"><div align="right" class="txtpallete"><strong>Minimum Value :</strong></div></td>
						    <td height="22">&nbsp;&nbsp;<input name="txtMinimum" type="text" value="" class="txtfield" style="width: 150px" <?php if (isset($_POST['rdoBReqt'])){if($_POST['rdoBReqt'] == 1){?>disabled="yes" <?php }}else if($ovrlaytype == 1){?>disabled="yes"<?php }?>></td>
					    </tr>
					    <tr>
						    <td height="22"><div align="right" class="txtpallete"><strong>Start Date :</strong></div></td>
						    <td height="22">&nbsp;
						    	<input name="txtSetStartDate" type="text" class="txtfield" id="txtSetStartDate" size="20" style="width: 150px" readonly="yes" value="<?php echo $today; ?>" <?php if (isset($_POST['rdoBReqt'])){if($_POST['rdoBReqt'] == 1){?>disabled="yes" <?php }}else if($ovrlaytype == 1){?>disabled="yes"<?php }?>>
								<input type="button" class="buttonCalendar" name="anchorSetStartDate" id="anchorSetStartDate" value=" " disabled="yes">
								<div id="divSetStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
						    </td>
					    </tr>
					    <tr>
						    <td height="22"><div align="right" class="txtpallete"><strong>End Date :</strong></div></td>
						    <td height="22">&nbsp;
						    	<input name="txtSetEndDate" type="text" class="txtfield" id="txtSetEndDate" size="20" style="width: 150px" readonly="yes" value="<?php echo $end; ?>" <?php if (isset($_POST['rdoBReqt'])){if($_POST['rdoBReqt'] == 1){?>disabled="yes" <?php }}else if($ovrlaytype == 1){?>disabled="yes"<?php }?>>
								<input type="button" class="buttonCalendar" name="anchorSetEndDate" id="anchorSetEndDate" value=" " disabled="yes">
								<div id="divSetEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>
						    </td>
					    </tr>
						<tr>
							<td colspan="2" height="20">&nbsp;</td>
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		</table>
		<br>
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<td align="right">
				<input name='btnAdd' type='submit' class='btn' value='Add' onclick='return confirmAdd();'>
			</td>			
		</tr>
		</table>
		<br>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"><input type="hidden" id="hID" name="hID" value="<?php echo $promoID;?>"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Buy-in Requirement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
						    <td width="5%" height="25%" class="borderBR"><div align="center"><input name="chkAll" type="checkbox" id="chkAll" value="1"  onclick="checkAll(this.checked);"></div></td>
									<td width="10%" height="25%" class="txtpallete borderBR"><div align="center">Overlay No.</div></td>
									<td width="25%" height="25%" class="borderBR"><div align="left" class="txtpallete">&nbsp;&nbsp;Selection</div></td>
									<td width="8%"  height="25%" class="txtpallete borderBR"><div align="center">Criteria</div></td>			
									<td width="8%"  height="25%" class="txtpallete borderBR"><div align="center">Minimum</div></td>
									<td width="8%"  height="25%" class="txtpallete borderBR"><div align="center">PMG</div></td>
									<td width="10%" height="25%" class="txtpallete borderBR"><div align="center">Start Date</div></td>			
									<td width="10%" height="25%" class="txtpallete borderBR"><div align="center">End Date</div></td>		
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_150">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1" id="buyTable">					
						<?php
							$cntr = 0;
							if(isset($_SESSION['buyins']))	
							{
								if (sizeof($_SESSION['buyins']))
								{
									$buyin_list = $_SESSION["buyins"];
									$rowalt=0;
									
									
									//while($row = $rspromobuyin->fetch_object())
									for ($i=0, $n=sizeof($buyin_list); $i < $n; $i++ ) 
									{
										$rowalt++;
										$cntr++;
										($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
										if($buyin_list[$i]['Criteria'] == 1)
										{
											$criteria = "Quantity";
											$minimum = $buyin_list[$i]['MinQty'];
											$crit = 1;
										}
										else
										{
											
											$criteria = "Amount";
											$minimum = number_format($buyin_list[$i]['MinAmt'], 2, '.', '');
											$crit = 2;
										}

										if($buyin_list[$i]['StartDate'] == "00-00-0000" || $buyin_list[$i]['StartDate'])
										{
											$startdate = $today;                                                                                   
										}
										else
										{
											$startdate = $buyin_list[$i]['StartDate'];
										}
										if($buyin_list[$i]['EndDate'] == "00-00-0000" || $buyin_list[$i]['EndDate'])
										{
											$enddate = $end;                                                                                     
										}
										else
										{
											$enddate = $buyin_list[$i]['EndDate'];
										}
										$proddesc = $buyin_list[$i]['ProdDesc'];
										$prodid = $buyin_list[$i]['ProdID'];
										$rnge = $buyin_list[$i]['Range'];
										$pmg= $buyin_list[$i]['PMGCode'];
										$PReqType = $buyin_list[$i]['PReqType'];
										echo "<tr align='center' id='captionRow$cntr' class='$class'>		
												<td width='5%' height='20' class='borderBR'><div align='center'>	
														
												<input type='checkbox' name='chkSelect[]' id='chkSelect'  value='$cntr' onclick='return CheckInclude();'></td>
												<td width='11%' height='20' class='borderBR'><div align='center'>
												<input type='hidden' id='hrange$cntr' name='hrange$cntr' value='$rnge'>
												$cntr</div></td>
												<td width='26%' height='20' class='borderBR'><div align='left' class='padl5'>
												<input type='hidden' id='hprodIDID$cntr' name='hprodIDID$cntr' value='$prodid'>
												$proddesc</div></td>
												<td width='8%' height='20' class='borderBR'><div align='left' class='padl5'>
												<input type='hidden' id='hCrit$cntr' name='hCrit$cntr' value='$crit'>
												$criteria</div></td>			
												<td width='8%' height='20' class='borderBR'><div align='center' class='padr5'>
												<input type='hidden' id='hMin$cntr' name='hMin$cntr' value='$minimum'>
												$minimum</div></td>		
												<td width='8%' height='20' class='borderBR'><div align='center' class='padr5'>												
												$pmg</div></td>									
												<td width='11%' height='20' class='borderBR'><div align='center'>
												<input type='hidden' id='hSDate$cntr' name='hSDate$cntr' value='$startdate'>
												$startdate</div></td>
												<td width='10%' height='20' class='borderBR'><div align='center'>
												<input type='hidden' id='hEDate$cntr' name='hEDate$cntr' value='$enddate'>
												$enddate</div></td>
												
										</tr>";							
									}
								}
								echo"<input type='hidden' id='hcntr' name='hcntr' value='$cntr'>";
							}
							else
							{
								echo"<input type='hidden' id='hcntr' name='hcntr' value='$cntr'>";
								echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
							}
						?>
				</table>
				</div>
			</td>
		</tr>
		<tr>
		<td>
		&nbsp;&nbsp;&nbsp;<input name="btnRemove" type="submit" class="btn" value="Remove"/></td> <!--onClick ="return confirmRemove();"-->
		</tr> 
		</table>
	</td>
	<td width="1%"></td>
	<td width="47%" valign="top">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Entitlement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr align="center">
			<td height="25" class="borderBR"><div align="left" class="txtpallete">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Type :</strong>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="cboType" class="txtfield" id="cboType" style="width: 100px;" onChange ="selection();"">
					<option value="2" <?php if($entType == 2){ ?> selected="selected" <?php }?>>Selection</option>
					<option value="1" <?php if($entType == 1){ ?> selected="selected" <?php }?>>Set</option>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;&nbsp;&nbsp;
				<input name="txtTypeQty" type="text" class="txtfield" id="txtTypeQty" <?php if($entType == 1){ ?> readonly="readonly"  <?php }?> value="<?php echo $entQty; ?>" style="width: 60px; text-align: left;">
			</div></td>
		</tr>
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1" >
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="9%"  height="25" class="txtpallete borderBR"><div align="center">&nbsp;</div></td>
							<td width="12%" height="25" class="txtpallete borderBR"><div align="center" class="padr5">Item Code</div></td>
							<td width="30%" height="25" class="txtpallete borderBR"><div align="center" >Item Description</div></td>			
							<td width="10%" height="25" class="txtpallete borderBR"><div align="center">Criteria</div></td>
                                                            <td width="9%"  height="25" class="txtpallete borderBR"><div align="center">Qty/Price</div></td>	
							<td width="10%" height="25" class="txtpallete borderBR"><div align="center" >PMG</div></td>		
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="bgF9F8F7">
				<div class="scroll_150">
				<table width="100%"  border="0" cellpadding="0" cellspacing="1" id="dynamicEntTable">
				<?php
					if (isset($_GET['prmsid']))
					{
						$ctr = 0;
						$linecnt = 0;
						if ($rspromobuyin_ent->num_rows)
						{
							while($row = $rspromobuyin_ent->fetch_object())
							{
								$ctr += 1;
								//get promoentitlementid
								$rspromentitlement = "rspromentitlement".$ctr;
								$rspromentitlement = $sp->spSelectPromoEntitlementByPromoBuyInID($database, $row->PromoBuyinID);
								if ($rspromentitlement->num_rows)
								{
									$index = 0;
									while($rowEnt = $rspromentitlement->fetch_object())
									{
										$index += 1;
										//get promoentitlementdetails
										$rspromentitlement_details = "rspromentitlement_details".$index;
										$rspromentitlement_details = $sp->spSelectPromoEntitlementDetailsByPromoEntitlementID($database, $rowEnt->ID);
										if ($rspromentitlement_details->num_rows)
										{
											$rowalt=0;
											$tmpstep = "";
											while($row_det = $rspromentitlement_details->fetch_object())
											{
												$rowalt++;
												$linecnt++;
												($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
												if ($row_det->EffectivePrice > 0)
												{
													$criteria = number_format($row_det->EffectivePrice, 2, '.', '');
													$type = "Price";											
												}
												else
												{
													$criteria = number_format($row_det->Quantity, 0, '.', '');
													$type = "Quantity";
												}
			
												$step_ = "Overlay ".$ctr;
												if($tmpstep !=  $step_)
												{
													$step = "Overlay ".$ctr;
												}
												else
												{
													$step = "&nbsp;";
												}
												?>
												<tr align="center" class="<?php echo $class; ?>">
														<td width="11%" height="20" class="borderBR">
														 <input name="btnRemove<?php echo $linecnt; ?>" type="button" class="btn" value="Remove" onclick="deleteRow2(this.parentNode.parentNode.rowIndex)">
														</td>
															<?php
																echo "<td width='14%' height='20' class='borderBR'><div align='left' class='padl5'>
																<input name='txtEProdCode$linecnt' type='text' class='txtfield' readonly='yes' id='txtEProdCode$linecnt' style='width: 80px;' value='$row_det->ProdCode' onKeyPress='return disableEnterKey(this, event, $linecnt)'/>
																<span id='indicatorE$linecnt' style='display: none'><img src='../../images/ajax-loader.gif' alt='Working...' /></span>                                      
																<div id='prod_choicesE$linecnt' class='autocomplete' style='display:none'></div>
																<script type='text/javascript'>							
																	 //<![CDATA[
											                        	var prod_choicesE1 = new Ajax.Autocompleter('txtEProdCode$linecnt', 'prod_choicesE$linecnt', '../../includes/scProductListEntitlementAjax.php?index=$linecnt', {afterUpdateElement : getSelectionProductList2, indicator: 'indicatorE$linecnt'});																			
											                        //]]>
																</script>
																<input name='hEProdID$linecnt' type='hidden' id='hEProdID$linecnt' value='$row_det->ProductID' />
																<input name='hEUnitPrice$linecnt' type='hidden' id='hEUnitPrice$linecnt' value='$row_det->UnitPrice'/>
									
															</div></td>";						
															 ?>
						 
														<td width="30%" height="20" class="borderBR"><div align="right" class="txtpallete"><input name="txtEProdDesc<?php echo $linecnt; ?>" type="text" class="txtfield" id="txtEProdDesc<?php echo $linecnt; ?>" style="width: 220px;" readonly="yes"  value="<?php echo $row_det->ProdName;?>"/></div></td>			
														<td width="10%" height="20" class="borderBR"><div align="center">
															<select name="cboECriteria<?php echo $linecnt; ?>" class="txtfield" id="cboECriteria<?php echo $linecnt; ?>" style="width: 80px;">
																<option value="2" <?php if($type == "Price"){?> selected="selected"<?php }?>>Price</option>
																<option value="1" <?php if($type == "Quantity"){?> selected="selected"<?php }?>>Quantity</option>
															</select>
														</div></td>
														<td width="10%" height="20" class="borderBR"><div align="center"><input name="txtEQty<?php echo $linecnt; ?>" type="text" class="txtfield" id="txtEQty<?php echo $linecnt; ?>"  value="<?php echo $criteria; ?>" style="width: 50px; text-align:right" onBlur='addRow2();return false;' onKeyPress='return disableEnterKey(this, event, <?php echo $linecnt; ?>)'/></div></td>
														<td width="10%" height="20" class="borderBR"><div align="center">
															<select name="cboEPMG<?php echo $linecnt; ?>" class="txtfield" id="cboEPMG<?php echo $linecnt; ?>" style="width: 80px;">
																<?php
																
																$rs_pmg = $sp->spSelectPMG($database);
																	if($rs_pmg->num_rows)
																	{
																		
																		while($row = $rs_pmg->fetch_object())
																		{
																			($row_det->pmgid == $row->ID) ? $sel = "selected" : $sel = "";
																			if (($row_det->pmgid == 1 &&  $row->ID != 2) || ($row_det->pmgid == 2 &&  $row->ID != 1))
																			{	
																				echo "<option value='$row->ID'$sel>$row->Code</option>";
																			}										
																		}
																		$rs_pmg->close();
																	}
																?>
															</select>
														</div></td>																	
												</tr>	
												<?php 
												
												$tmpstep = $step_;							
											}					
											$rspromentitlement_details->close();			
										}
										else
										{
											echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
										} 									
									}
									$rspromentitlement->close();								
								}								
							}
							$rspromobuyin_ent->close();
						}
						else
						{
							echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
						}						
					}
					else
					{
						echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";	
					}
				?>
				<?php $linecnt++; ?>
				<tr align="center" class="$class">
					<td width="11%" height="20" class="borderBR"><input name="btnRemove<?php echo $linecnt; ?>" type="button" class="btn" value="Remove" onclick="deleteRow2(this.parentNode.parentNode.rowIndex)"></td>
						<?php
							echo "<td width='15%' height='20' class='borderBR'><div align='left' class='padl5'>
							<input name='txtEProdCode$linecnt' type='text' class='txtfield' id='txtEProdCode$linecnt' style='width: 80px;' value='' onKeyPress='return disableEnterKey(this, event, $linecnt)'/>
							<span id='indicatorE$linecnt' style='display: none'><img src='../../images/ajax-loader.gif' alt='Working...' /></span>                                      
							<div id='prod_choicesE$linecnt' class='autocomplete' style='display:none'></div>
							<script type='text/javascript'>							
								 //<![CDATA[
		                        	var prod_choicesE1 = new Ajax.Autocompleter('txtEProdCode$linecnt', 'prod_choicesE$linecnt', '../../includes/scProductListEntitlementAjax.php?index=$linecnt', {afterUpdateElement : getSelectionProductList2, indicator: 'indicatorE$linecnt'});																			
		                        //]]>
							</script>
							<input name='hEProdID$linecnt' type='hidden' id='hEProdID$linecnt' value='' />
							<input name='hEUnitPrice$linecnt' type='hidden' id='hEUnitPrice$linecnt' value=''/>
						</div></td>";						
						 ?>
					<td width="30%" height="20" class="borderBR"><div align="right" class="txtpallete"><input name="txtEProdDesc<?php echo $linecnt; ?>" type="text" class="txtfield" id="txtEProdDesc<?php echo $linecnt; ?>" style="width: 220px;" readonly="yes"  value=""/></div></td>			
					<td width="10%" height="20" class="borderBR"><div align="center">
						<select name="cboECriteria<?php echo $linecnt; ?>" class="txtfield" id="cboECriteria<?php echo $linecnt; ?>" style="width: 80px;">
							<option value="2" selected="selected">Price</option>
							<option value="1">Quantity</option>
						</select>
					</div></td>
					<td width="10%" height="20" class="borderBR"><div align="center"><input name="txtEQty<?php echo $linecnt; ?>" type="text" class="txtfield" id="txtEQty<?php echo $linecnt; ?>"  value="" style="width: 50px; text-align:right" onBlur='addRow2();return false;' onKeyPress='return disableEnterKey(this, event, <?php echo $linecnt; ?>)'/></div></td>
					<td width="10%" height="20" class="borderBR"><div align="center">
						<select name="cboEPMG<?php echo $linecnt; ?>" class="txtfield" id="cboEPMG<?php echo $linecnt; ?>" style="width: 80px;">
							<?php
								$rs_pmg = $sp->spSelectPMG($database);
								if($rs_pmg->num_rows)
								{
									while($row = $rs_pmg->fetch_object())
									{	
										echo "<option value='$row->ID'>$row->Code</option>";										
									}
									$rs_pmg->close();
								}
							?>
						</select>
					</div></td>																	
				</tr>
				</table>
				</div>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>
<br>
<br>
<table width="100%" align="left"  border="0" cellpadding="0" cellspacing="0">
			<tr>
				
				<td align="center">
						<input type="hidden" id="hInc" name="hInc" value="<?php echo $inc;?>">
						<?php
 						if ($_SESSION['ismain'] == 1)
 						{
 							if (($today != $today2) && (($today > $today2) || ($end < $today2)))
 							{
 								echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onclick='return confirmDelete(hInc.value);'>";
								echo "<input name='btnSave' type='submit' class='btn' value='Update' onclick='return confirmSave();'>"; 								
 							}
 						}
 						?>
				</td>			
			</tr>
			</table>
</form>
</body>
<br>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divStartDate",
		button         :    "anchorStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtEndDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divEndDate",
		button         :    "anchorEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtSetStartDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divSetStartDate",
		button         :    "anchorSetStartDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>
<script type="text/javascript">
	Calendar.setup({
		inputField     :    "txtSetEndDate",     // id of the input field
		ifFormat       :    "%m/%d/%Y",      // format of the input field
		displayArea    :	"divSetEndDate",
		button         :    "anchorSetEndDate",  // trigger for the calendar (button ID)
		align          :    "Bl",           // alignment (defaults to "Bl")
		singleClick    :    true
	});
</script>