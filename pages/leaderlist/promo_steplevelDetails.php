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

<?php
   	require_once "../../initialize.php";
	//include IN_PATH.DS."scPromo_popup.php";
	include IN_PATH.DS."scStepLevelPromoDet.php";	
?>

<script language="javascript" type="text/javascript">
var $j = jQuery.noConflict();
function getSelectionProductList(text, li) 
{
  	var txt = text.id;
  	var ctr = txt.substr(11, txt.length);

  	tmp = li.id;
  	tmp_val = tmp.split("_");
  	h = eval('document.frmCreateMultiLine.hProdID' + ctr);
  	i = eval('document.frmCreateMultiLine.txtProdCode' + ctr);
  	j = eval('document.frmCreateMultiLine.txtProdName' + ctr);
  	k = eval('document.frmCreateMultiLine.txtQty' + ctr);
	m = eval('document.frmCreateMultiLine.hbpmgid' + ctr);
	n = eval('document.frmCreateMultiLine.txtbPmg' + ctr);
	o = eval('document.frmCreateMultiLine.cboCriteria' + ctr);
  	
  	o.focus();
  	//k.focus();
  	//k.select();
  	
  	i.value = tmp_val[2];
  	h.value = tmp_val[0];
  	j.value = tmp_val[1];
	n.value = tmp_val[3];
	m.value = tmp_val[4];
}

function getSelectionProductList2(text, li) 
{
	var txt = text.id;
  	var ctr = txt.substr(12, txt.length);
  	
  	tmp = li.id;
  	tmp_val = tmp.split("_");
  	h = eval('document.frmCreateMultiLine.hEProdID' + ctr);
  	i = eval('document.frmCreateMultiLine.txtEProdCode' + ctr);
  	j = eval('document.frmCreateMultiLine.txtEProdName' + ctr);
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
  
function confirmDelete()
{
  		if (confirm('Are you sure you want to delete this promo?') == false)
    		return false;
    	else
    		return true;
}
  
  function confirmUpdate()
  {
  	var ml = document.frmCreateMultiLine;
	var type = ml.cboType;
	var typeqty = ml.txtTypeQty;
	  
	var promo_code = ml.txtCode;
	var promo_desc = ml.txtDescription;
	var promo_sdate = ml.txtStartDate;
	var promo_edate = ml.txtEndDate;

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

  	//addRow(); 
  	//addRow2(); 

  	if (confirm('Are you sure you want to update this promo?') == false)
		return false;
	else
		return true;
  }

  function deleteRow(i)
  {
  	var table = document.getElementById('tabletest');
  	var rowCount = table.rows.length;	

	  if (rowCount != 1)
	  {
	  	document.getElementById('tabletest').deleteRow(i);
	  	document.frmCreateMultiLine.hBuyInCnt.value = rowCount - 1;
	  }
	  else
	  {
	      return false;
	  }
  }
  
  function deleteRow2(i)
  {
      var table = document.getElementById('tableETest');
      var rowCount = table.rows.length;

      if(rowCount != 1)
      {	
      	document.getElementById('tableETest').deleteRow(i);
      	document.frmCreateMultiLine.hEntitlementCnt.value = rowCount - 1;
      }
      else
      {
          return false;
      }
  }
  
  function addRow() 
  {	
  	 var table = document.getElementById('tabletest');
  	 var rowCount = table.rows.length;
  	 var cnt =document.frmCreateMultiLine.hBuyInCnt.value;
  	 var cnt2 =document.frmCreateMultiLine.hBuyInIndex.value;
     var y = rowCount + 1;
	
  	if(cnt2 >  rowCount)
  	{
  		var a = eval('document.frmCreateMultiLine.txtProdName' + cnt2);
   		
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
  		 var a = eval('document.frmCreateMultiLine.txtProdName' + rowCount);
    		
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
 	 
     var cell1 = row.insertCell(0);
     cell1.setAttribute("align", "center");
   	 cell1.setAttribute("class", "borderBR");
   	cell1.setAttribute("width", "11%");
     var element1 = document.createElement("input");
     element1.type = "button";
     element1.setAttribute("class", "btn");
   	 element1.setAttribute("style", "width: 65px");
   	 element1.setAttribute("id", "btnRemove" + index);
   	 element1.setAttribute("name", "btnRemove" + index);
   	 element1.setAttribute("value", "Remove");
   	 element1.onclick = function () {deleteRow(this.parentNode.parentNode.rowIndex)};
  	 
     cell1.appendChild(element1);

     var cell2 = row.insertCell(1);
     cell2.setAttribute("align", "left");
 	 cell2.setAttribute("class", "borderBR padl5");
 	cell2.setAttribute("width", "13%");
     var element2 = document.createElement("input");
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
  	 element1s.innerHTML = ("<img src='../../images/ajax-loader.gif' />");
  	
  	 //div
  	 element1d.setAttribute("id", "prod_choices" + index);
  	 element1d.setAttribute("name", "prod_choices" + index);
  	 element1d.setAttribute("class", "autocomplete");
  	 element1d.setAttribute("style", "display: none");

	 element2.type = "input";
	 element2.setAttribute("class", "txtfieldg");
	 element2.setAttribute("id", "txtProdCode" + index);
	 element2.setAttribute("name", "txtProdCode" + index);
	 element2.setAttribute("style", "width: 75px");

  	 
  	 cell2.appendChild(element2);
  	 cell2.appendChild(element1h);
  	 cell2.appendChild(element1s);
  	 cell2.appendChild(element1d);
   	 
   	 var cell3 = row.insertCell(2);
   	 cell3.setAttribute("align", "center");
  	 cell3.setAttribute("class", "borderBR");
  	 cell3.setAttribute("width", "35%");
     var element3 = document.createElement("input");
     element3.type = "input";
     element3.setAttribute("class", "txtfieldg");
     element3.setAttribute("readonly", "readonly");
   	 element3.setAttribute("id", "txtProdName" + index);
   	 element3.setAttribute("name", "txtProdName" + index);
   	 element3.setAttribute("style", "width: 200px");
   	 cell3.appendChild(element3);

   	//criteria
   	var cell4 = row.insertCell(3);
   	cell4.setAttribute("align", "center");
   	cell4.setAttribute("class", "borderBR");
   	cell4.setAttribute("width", "14%")
   	var element4 = document.createElement("select");
   	var option1 = document.createElement('option');
   	var option2 = document.createElement('option');
   	var criteria = [ 'Amount', 'Quantity' ];
   	var text1 = document.createTextNode(criteria[0]);
   	var text2 = document.createTextNode(criteria[1]);

   	option1.appendChild(text1);
  	option1.value = 2;
  	element4.appendChild(option1);
  	option2.appendChild(text2);
  	option2.value = 1;
  	option2.selected = "selected";
  	element4.appendChild(option2);
  	element4.setAttribute("class", "txtfieldg");
  	element4.setAttribute("id", "cboCriteria" + index);
  	element4.setAttribute("name", "cboCriteria" + index);
  	element4.setAttribute("style", "width: 80px");
  	cell4.appendChild(element4);

  	var cell5 = row.insertCell(4);	 
    cell5.setAttribute("align", "center");
  	cell5.setAttribute("class", "borderBR");
  	cell5.setAttribute("width", "14%") 
    var element5 = document.createElement("input");
    element5.type = "input";
    element5.setAttribute("class", "txtfieldg");
    element5.setAttribute("onBlur", "addRow()");
    element5.setAttribute("id", "txtQty" + index);
    element5.setAttribute("name", "txtQty" + index);
    element5.setAttribute("style", "width: 50px; text-align: right");
    element5.setAttribute("value", 1);	 
    cell5.appendChild(element5);

  //pmg
  	var cell6 = row.insertCell(5);
  	cell6.setAttribute("align", "center");
  	cell6.setAttribute("class", "borderBR");
  	cell6.setAttribute("width", "20%");
  	//hidden pmgid
 	var elementhpmgid = document.createElement("input");
 	elementhpmgid.type = "hidden";
 	elementhpmgid.setAttribute("value", "");
 	elementhpmgid.setAttribute("id", "hbpmgid" + index);
 	elementhpmgid.setAttribute("name", "hbpmgid" + index);
 	
 	var elementpmgvalue = document.createElement("input");
 	elementpmgvalue.type = "input";
 	elementpmgvalue.setAttribute("value", "");
 	elementpmgvalue.setAttribute("id", "txtbPmg" + index);
 	elementpmgvalue.setAttribute("name", "txtbPmg" + index);
 	elementpmgvalue.setAttribute("class", "txtfieldg");
 	elementpmgvalue.setAttribute("readonly", "readonly");
 	elementpmgvalue.setAttribute("style", "width: 75px;");
	cell6.appendChild(elementhpmgid);
	cell6.appendChild(elementpmgvalue);
	
    document.frmCreateMultiLine.hBuyInCnt.value = rowCount + 1;
    document.frmCreateMultiLine.hBuyInIndex.value = index;
    document.frmCreateMultiLine.hBuyInCntSaving.value = y;
   	var url = '../../includes/scProductListAjax.php?index=' + index; 
  	var prod_choices = new Ajax.Autocompleter(element2.id, element1d.id, url, {afterUpdateElement : getSelectionProductList, indicator: element1s.id});
  }

  function addRow2() 
  {		 
  	var ml = document.frmCreateMultiLine;
  	var table = document.getElementById('tableETest');
  	var rowCount = table.rows.length;
  	
 	var cnt =document.frmCreateMultiLine.hEntitlementCnt.value;
  	var cnt2 =document.frmCreateMultiLine.hEntitlementIndex.value;
	
  	if(cnt2 >  rowCount)
  	{
  	
  		 var a = eval('document.frmCreateMultiLine.txtEProdName' + cnt2);
   		
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
  		 var a = eval('document.frmCreateMultiLine.txtEProdName' + rowCount);
    		
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


  	 
  	var cell1 = row.insertCell(0);
    cell1.setAttribute("align", "center");
	 cell1.setAttribute("class", "borderBR");
	 cell1.setAttribute("width", "10%");
    var element1 = document.createElement("input");
    element1.type = "button";
    element1.setAttribute("class", "btn");
	 element1.setAttribute("style", "width: 65px");
	 element1.setAttribute("id", "btnRemove" + index);
	 element1.setAttribute("name", "btnRemove" + index);
	 element1.setAttribute("value", "Remove");
	 element1.onclick = function () {deleteRow(this.parentNode.parentNode.rowIndex)};
  	 
       cell1.appendChild(element1);

     var cell2 = row.insertCell(1);
     cell2.setAttribute("align", "left");
   	 cell2.setAttribute("class", "borderBR padl5");
     var element2 = document.createElement("input");
     element2.type = "input";
     element2.setAttribute("class", "txtfieldg");
   	 element2.setAttribute("id", "txtEProdCode" + index);
   	 element2.setAttribute("name", "txtEProdCode" + index);
   	 element2.setAttribute("style", "width: 75px");

   
  	 var element1h = document.createElement("input");
  	 var element1hup = document.createElement("input");
  	 var element1s = document.createElement("span");
  	 var element1d = document.createElement("div");
  	 var element1img = document.createElement("img");
  	
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

     cell2.appendChild(element2);
   	 cell2.appendChild(element1h);
  	 cell2.appendChild(element1hup);
  	 cell2.appendChild(element1s);
  	 cell2.appendChild(element1d); 	

   	 var cell3 = row.insertCell(2);
   	 cell3.setAttribute("align", "center");
  	 cell3.setAttribute("class", "borderBR");
     cell3.setAttribute("width", "34%");
     var element3 = document.createElement("input");
     element3.type = "input";
     element3.setAttribute("class", "txtfieldg");
     element3.setAttribute("readonly", "readonly");     
   	 element3.setAttribute("id", "txtEProdName" + index);
   	 element3.setAttribute("name", "txtEProdName" + index);
   	 element3.setAttribute("style", "width: 200px");
   	 cell3.appendChild(element3);

   	//criteria
   	var cell4 = row.insertCell(3);
   	cell4.setAttribute("align", "center");
   	cell4.setAttribute("class", "borderBR");
   	
   	var element4 = document.createElement("select");
   	var option1 = document.createElement('option');
   	var option2 = document.createElement('option');
   	var criteria = [ 'Quantity', 'Amount' ];
   	var text1 = document.createTextNode(criteria[0]);
   	var text2 = document.createTextNode(criteria[1]);

   	option1.appendChild(text1);
  	option1.value = 1;
  	element4.appendChild(option1);
  	option2.appendChild(text2);
  	option2.value = 2;
  	element4.appendChild(option2);
  	element4.setAttribute("class", "txtfieldg");
  	element4.setAttribute("id", "cboECriteria" + index);
  	element4.setAttribute("name", "cboECriteria" + index);
  	element4.setAttribute("style", "width: 80px");
  	cell4.appendChild(element4);


  	 var cell5 = row.insertCell(4);	 
  	 cell5.setAttribute("align", "center");
  	 cell5.setAttribute("class", "borderBR");
  	 
     var element5 = document.createElement("input");
     element5.type = "input";
     element5.setAttribute("class", "txtfieldg");
     element5.setAttribute("onBlur", "addRow2()");
   	 element5.setAttribute("id", "txtEQty" + index);
   	 element5.setAttribute("name", "txtEQty" + index);
   	element5.setAttribute("value", 1);
   	 element5.setAttribute("style", "width: 50px; text-align: right");
     	 
   	 cell5.appendChild(element5);


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
	elementpmgvalue.setAttribute("class", "txtfieldg");
	elementpmgvalue.setAttribute("readonly", "readonly");
	elementpmgvalue.setAttribute("style", "width: 75px;");
	
	cell6.appendChild(elementhpmgid);
	//cell6.appendChild(elementpmgvalue);
   	
   	var element6 = document.createElement("select");
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
   		element6.appendChild(eval("option" + i));
   	}

   	element6.setAttribute("class", "txtfieldg");
   	element6.setAttribute("id", "cboEPMG" + index);
   	element6.setAttribute("name", "cboEPMG" + index);
   	element6.setAttribute("style", "width: 75px");
   	cell6.appendChild(element6)
  	
    document.frmCreateMultiLine.hEntitlementCnt.value = rowCount + 1;
    document.frmCreateMultiLine.hEntitlementIndex.value = index; 
  
   	var url = '../../includes/scProductListEntitlementAjax.php?index=' + index;
  	var prod_choicesE = new Ajax.Autocompleter(element2.id, element1d.id, url, {afterUpdateElement : getSelectionProductList2, indicator: element1s.id});
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

<form name="frmCreateMultiLine" method="post" action="promo_steplevelDetails.php?prmsid=<?php echo $_GET['prmsid'];?>">

<br>

<input type="hidden" id="hID" name="hID" value="<?php echo $promoID;?>">
<table width="95%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
<tr>
	<td valign="top" class="bgF9F8F7">
		<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
		<tr>
			<td>
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td width="10%">&nbsp;</td>
					<td width="90%" align="right">&nbsp;</td>
				</tr>			
				<tr>
				    <td width="20%" height="20"><div align="left" class="padl5"><strong>Promo Code :</strong></div></td>
				    <td width="20%" height="20"><input name="txtCode" type="text" class="txtfieldLabel" id="txtCode" value="<?php echo $prmcode;?>" size="30" readonly="yes"></td>
			    </tr>
			    <tr>
				    <td width="20%" height="20"><div align="left" class="padl5"><strong>Promo Description :</strong></div></td>
				    <td width="20%" height="20"><input name="txtDescription" type="text" class="txtfield" id="txtDescription" value="<?php echo $prmdesc;?>" style="width: 362px;" maxlength="60">
					</td>
			    </tr>
				<tr>
				  	<td width="20%" height="20"><div align="left" class="padl5"><strong>Start Date: </strong></div></td>
				  	<td width="20%" height="20">
						<input name="txtStartDate" type="text" class="txtfield" id="txtStartDate" size="20" readonly="yes" value="<?php echo $prmsdate; ?>">
						<input type="button" class="buttonCalendar" name="anchorStartDate" id="anchorStartDate" value=" ">
						<div id="divStartDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
					</td>
				</tr>
				<tr>
				  	<td width="20%" height="20"><div align="left" class="padl5"><strong>End Date: </strong></div></td>
				  	<td width="20%" height="20">
						<input name="txtEndDate" type="text" class="txtfield" id="txtEndDate" size="20" readonly="yes" value="<?php echo $prmedate; ?>">
						<input type="button" class="buttonCalendar" name="anchorEndDate" id="anchorEndDate" value=" ">
						<div id="divEndDate" style="background-color : white; position:absolute;visibility:hidden;"> </div>	
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
				    <td height="20" valign="top"><div align="left" class="padl5"><strong>Max Availment :</strong></div></td>
				    <td height="20">
				    	<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				    	<?php
				    	if($rs_promoAvailment->num_rows)
						{
							while($row = $rs_promoAvailment->fetch_object())
							{
								$txt = $row->MaxAvailment;
								echo "<tr>
				    						<td width='15%' height='20'><div align='left' class='padl5'><strong>$row->Name :</strong></div></td>
				    						<td width='75%' height='20'>
				    						<input type='text' id='txtMaxAvail$row->GSUTypeID' class='txtfield' name='txtMaxAvail$row->GSUTypeID' value='$txt'></td>
				    						
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
									
									echo "<tr>
					    						<td width='15%' height='20'><div align='left' class='padl5'><strong>$row->Name :</strong></div></td>
					    						<td width='75%' height='20'>
				    						    <input type='text' id='txtMaxAvail$row->ID' class='txtfield' name='txtMaxAvail$row->ID' value=''></td>
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
				    <td height="20"><div align="left" class="padl5"><strong>Is Plus Plan :</strong></div></td>
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
	<td width= "45%" valign="top">
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
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="10%" height="20" class="txtpallete borderBR"><div align="center"></div></td>						
							<td width="12%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Code</div></td>
							<td width="30%" height="20" class="txtpallete borderBR"><div align="left" class="padl5">Item Description</div></td>			
							<td width="11%" height="20" class="txtpallete borderBR"><div align="center">Criteria</div></td>
							<td width="11%" height="20" class="txtpallete borderBR"><div align="center">Minimum</div></td>	
							<td width="9%" height="20" class="txtpallete borderBR"><div align="center">Maximum</div></td>	
							<td width="9%" height="20" class="txtpallete borderBR"><div align="center">PMG</div></td>	
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div class="scroll_300">
					<table width="100%" id="tabletest"  border="0" cellpadding="0" cellspacing="0"  class="bgFFFFFF">
					<?php		
		      			if (isset($_GET['prmsid']))
						{
						if ($rspromobuyin->num_rows)
						{
							$step = 0;
							$step_str = "";
							$parentbuyin = 0;
							$rowalt = 0;
							$lineno = 0;
							while($row = $rspromobuyin->fetch_object())
							{
								$lineno++;
								$rowalt++;
								($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
								
								if($row->Criteria == 1)
								{
									$criteria = "Quantity";
									$minimum = number_format($row->Minimum, 0, '.', '');
									$maximum = number_format($row->Maximum, 0, '.', '');
								}
								else
								{
									$criteria = "Amount";
									$minimum = number_format($row->Minimum, 2, '.', '');
									$maximum = number_format($row->Maximum, 2, '.', '');
								}
								
								if ($row->ParentPromoBuyinID != $parentbuyin)
								{
									$step++;
									$step_str = $step;									
								}
								else
								{
									$step_str = "";
								}
								
								$parentbuyin = $row->ParentPromoBuyinID;
							
									//echo"<pre>";
									//	var_dump($row);
									//echo"</pre>";
									echo"
									<tr align='center' class='bgEFF0EB'>
										<td width='10%' height='20' class='borderBR'><input name='btnRemove$lineno' type='button' class='btn' value='Remove' onclick='deleteRow(this.parentNode.parentNode.rowIndex)'></td>
										<td width='12%' height='20' class='borderBR'>
										<div align='left' class='padl5'>
										<input name='txtProdCode$lineno' type='text' class='txtfieldg' id='txtProdCode$lineno' style='width: 75px;' value='$row->ProdCode' />
										<span id='indicator$lineno' style='display: none'><img src='../../images/ajax-loader.gif' alt='Working...' /></span>                                      
										<div id='prod_choices$lineno' class='autocomplete' style='display:none'></div>
										<script type='text/javascript'>							
												//<![CDATA[
													var prod_choices = new Ajax.Autocompleter('txtProdCode$lineno', 'prod_choices$lineno', '../../includes/scProductListAjax.php?index=$lineno', {afterUpdateElement : getSelectionProductList, indicator: 'indicator$lineno'});																		
											//]]>
										</script>
										<input name='hProdID$lineno' type='hidden' id='hProdID$lineno' value='' />
										<input name='hbpmgid$lineno' type='hidden' id='hbpmgid$lineno' value=''/>
										</div>
										</td>
										<td width='30%' height='20' class='borderBR'>
										<input name='txtProdName$lineno' readonly ='readonly' type='text' class='txtfieldg' id='txtProdName$lineno' style='width: 200px;' value='$row->ProdName'/>
										</td>
										<td width='11%' height= '20' class='borderBR'>
											<select name='cboCriteria$lineno' class='txtfieldg' id='cboCriteria$lineno' style='width: 80px;'>
												<option value='2'>Amount</option>	
												<option value='1' selected='selected'>Quantity</option>								
											</select>
										</td>
										<td width='11%' height= '20' class='borderBR'><div align='center'><input name='txtQty$lineno' type='text' class='txtfieldg' id='txtQty$lineno'  value='$minimum' style='width: 50px; text-align:right' onBlur='addRow();return false;'/></div></td>
										<td width='9%' height= '20' class='borderBR'><div align='center'><input name='txtQty$lineno' type='text' class='txtfieldg' id='txtQty$lineno'  value='$maximum' style='width: 50px; text-align:right' onBlur='addRow();return false;'/></div></td>
										<td width='9%' height= '20' class='borderBR'><div align='center'><input name='txtbPmg$lineno' type='text' id='txtbPmg' class='txtfieldg'  style='width: 75px; text-align:left' readonly='yes' value='$row->pmgCode'/></td>
									</tr>
										";		
							}
							$rspromobuyin->close();
						}
						else
						{
							echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
						}						
					}
				else{
						echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
				}
				?>
					</table>
				</div>
			</td>
		</tr>
		</table>
	</td>
	<td width= "1%">&nbsp;</td>
	<td width= "45%">
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td class="tabmin"></td> 
			<td class="tabmin2"><div align="left" class="padl5 txtredbold">Entitlement</div></td>
			<td class="tabmin3">&nbsp;</td>
		</tr>
		</table>
		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="1" class="bordergreen" id="tbl2">
		<tr align="center">
			<td height="25" class="txtpallete borderBR"><div align="left" class="padl5">
				<strong>Type :</strong>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<select name="cboType" class="txtfieldg" id="cboType" style="width: 100px;" onchange="selection()">
					<option value="1" <?php if($entType == "1"){?> selected="selected" <?php }?>>Set</option>
					<option value="2" <?php if($entType == "2"){?> selected="selected" <?php }?>>Selection</option>
				</select>
				&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;
				<input name="txtTypeQty" type="text" class="txtfieldg" id="txtTypeQty" <?php if($entType == "1"){?> readonly="readonly" <?php }?> value="<?php echo $entQty; ?>" style="width: 60px; text-align: right;"><!--		
			
				<strong>Type :</strong>
				&nbsp;&nbsp;
				
				<?php if($entType == 1)
				{
					echo "Set";					
				}
				else
				{
					echo "Selection";
				}

				?>			   
				&nbsp;&nbsp;
				<strong>Selection No. :</strong>
				&nbsp;
				<?php echo $entQty; ?>
			--></div></td>
		</tr>
		<tr>
			<td valign="top" class="bgF9F8F7">
				<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="1">
				<tr>
					<td>
						<table width="100%"  border="0" cellpadding="0" cellspacing="1" class="txtdarkgreenbold10 tab">
						<tr align="center">
							<td width="10%" height="20" class="borderBR"><div align="center">&nbsp;</div></td>
							<td width="15%" height="20" class="borderBR"><div align="left" class="padl5">Item Code</div></td>
							<td width="30%" height="20" class="borderBR"><div align="left" class="padl5">Item Description</div></td>			
							<td width="10%" height="20" class="borderBR"><div align="center">Criteria</div></td>
							<td width="10%" height="20" class="borderBR"><div align="right" class="padr5">Qty/Price</div></td>	
							<td width="10%" height="20" class="borderBR"><div align="right" class="padr5">PMG</div></td>			
						</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<div class="scroll_300">
					<table width="100%" id="tableETest"  border="0" cellpadding="0" cellspacing="0" id="dynamicEntTable" class="bgFFFFFF">
				<?php
					if (isset($_GET['prmsid']))
					{
						if ($rspromobuyin_ent->num_rows)
						{
							$ctr = 0;
							$parentbuyin = 0;
							$lineno22 = 0;
							while($row = $rspromobuyin_ent->fetch_object())
							{
								 
								if ($row->ParentPromoBuyinID != $parentbuyin)
								{
									$ctr += 1;
									//get promoentitlementid
									$rspromentitlement = "rspromentitlement".$ctr;
									$rspromentitlement = $sp->spSelectPromoEntitlementByPromoBuyInID($database, $row->ParentPromoBuyinID);
									if ($rspromentitlement->num_rows)
									{
										while($rowEnt = $rspromentitlement->fetch_object())
										{
											$promoentID = $rowEnt->ID; 									
										}
										$rspromentitlement->close();								
									}
									
									//get promoentitlementdetails
									$rspromentitlement_details = "rspromentitlement_details".$ctr;
									$rspromentitlement_details = $sp->spSelectPromoEntitlementDetailsByPromoEntitlementID($database, $promoentID);
									if ($rspromentitlement_details->num_rows)
									{
										$rowalt=0;
										$tmpstep = "";
										while($row_det = $rspromentitlement_details->fetch_object())
										{
											$rowalt++;
											($rowalt%2) ? $class = "" : $class = "bgEFF0EB";
											if ($row_det->EffectivePrice > 0){
												$criteria = number_format($row_det->EffectivePrice, 2, '.', '');
												$type = "Amount";											
											}
											else{
												$criteria = number_format($row_det->Quantity, 0, '.', '');
												$type = "Quantity";
											}
											
											$step_ = "Step ".$ctr;
											if($tmpstep !=  $step_){
												$step = "Step ".$ctr;
											}
											else{
												$step = "&nbsp;";
											}
											//<td width='10%' height='20' class='borderBR'><input name='btnRemove$lineno' type='button' class='btn' value='Remove' onclick='deleteRow(this.parentNode.parentNode.rowIndex)'></td>
													//	echo $lineno22++;
											echo "	<tr>
													<td width='10%' height='20' class='borderBR'><div align='center'>$step</div></td>
													<td width='12%' height='20' class='borderBR'>
													 <input name='txtEProdCode$lineno22' type='text' class='txtfieldg' id='txtEProdCode$lineno22' style='width: 75px;' value='$row_det->ProdCode'/>
														<div align='left' class='padl5'>
														<span id='indicatorE$lineno22' style='display: none'><img src='../../images/ajax-loader.gif' alt='Working...' /></span>                                      
														<div id='prod_choicesE$lineno22' class='autocomplete' style='display:none'></div>
														<script type='text/javascript'>							
																//<![CDATA[
																	var prod_choices = new Ajax.Autocompleter('txtEProdCode$lineno22', 'prod_choicesE$lineno22', '../../includes/scProductListEntitlementAjax.php?index=$lineno22', {afterUpdateElement : getSelectionProductList, indicator: 'indicatorE$lineno22'});																		
															//]]>
														</script>
														<input name='hEProdID$lineno22' type='hidden' id='hProdID$lineno22' value='' />
														<input name='hEUnitPrice$lineno22' type='hidden' id='hEUnitPrice$lineno22' value=''/>
														<input name='hEpmgid$lineno22' type='hidden' id='hEpmgid$lineno22' value=''/>
														</div>
													</td>
													<td width='30%' height='20' class='borderBR'>
														<input name='txtProdName$lineno22' readonly ='readonly' type='text' class='txtfieldg' id='txtProdName$lineno22' style='width: 200px;' value='$row_det->ProdName'/>
													</td>
													<td width='11%' height= '20' class='borderBR'><div align='center'><input name='cboCriteria$lineno22' type='text' class='txtfieldg' id='cboCriteria$lineno22'  value='$minimum' style='width: 50px; text-align:right' onBlur='addRow();return false;'/></div></td>
													<td width='9%' height= '20' class='borderBR'><div align='center'><input name='txtQty$lineno22' type='text' class='txtfieldg' id='txtQty$lineno22'  value='$maximum' style='width: 50px; text-align:right' onBlur='addRow();return false;'/></div></td>
													<td width='9%' height= '20' class='borderBR'><div align='center'><input name='txtbPmg$lineno22' type='text' id='txtbPmg' class='txtfieldg'  style='width: 75px; text-align:left' readonly='yes' value='$row_det->pmgcode'/></td>
													</tr>											
													";
											$tmpstep = $step_;			
											$lineno22++;											
										}					
										$rspromentitlement_details->close();			
									}
									else
									{
										echo "<tr><td colspan='5' height='20' class='borderBR'><div align='center' class='txtredsbold'>No record(s) to display.</div></td></tr>";						
									}									
								}
								
								$parentbuyin = $row->ParentPromoBuyinID;
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
	<?php
	//	if ($_SESSION['ismain'] == 1)
	//	{
	//		if (($startdate != $today) && (($startdate > $today) || ($enddate < $today)))
	//		{
				echo "<input name='btnDelete' type='submit' class='btn' value='Delete' onclick='return confirmDelete();'>";
				echo "<input name='btnSave' type='submit' class='btn' value='Update' onclick='return confirmUpdate();'>";				
	//		}
	//	}
	//?>
	</td>			
</tr>
</table>

</form>
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