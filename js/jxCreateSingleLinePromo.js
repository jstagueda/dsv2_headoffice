// JavaScript Document

function getSelectionProductList(text, li) 
{
	var txt = text.id;
	var ctr = txt.substr(11, txt.length);

	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmCreateSingleLine.hProdID' + ctr);
	i = eval('document.frmCreateSingleLine.txtProdCode' + ctr);
	j = eval('document.frmCreateSingleLine.txtProdDesc' + ctr);
	k = eval('document.frmCreateSingleLine.txtQty' + ctr);
	n = eval('document.frmCreateSingleLine.txtbPmg' + ctr);
	o = eval('document.frmCreateSingleLine.cboCriteria' + ctr);
	
	o.focus();
	//k.focus();
	//k.select();
	
	h.value = tmp_val[0];
	j.value = tmp_val[1];
	i.value = tmp_val[2];
	//n.value = tmp_val[3];
	
	/*
		<option value="1">CFT</option>
		<option value="2">NCFT</option>
		<option value="3">CPI</option>
	*/
	var selectionx = "";
	if(tmp_val[4] == 1){
		selectionx += "<option value='1'>CFT</option>";
		selectionx += "<option value='3'>CPI</option>";
	}else if (tmp_val[4] == 2){
		selectionx += "<option value='2'>NCFT</option>";
		selectionx += "<option value='3'>CPI</option>";
	}else if (tmp_val[4] == 3){
		selectionx += "<option value='3'>CPI</option>";
	}else{
		selectionx += '<option value="1">CFT</option>';
		selectionx += '<option value="2">NCFT</option>';
		selectionx += '<option value="3">CPI</option>';
	}
	n.innerHTML = selectionx;
}

function getSelectionProductList2(text, li) 
{
	var txt = text.id;
	var ctr = txt.substr(12, txt.length);
	
	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmCreateSingleLine.hEProdID' + ctr);
	i = eval('document.frmCreateSingleLine.txtEProdCode' + ctr);
	j = eval('document.frmCreateSingleLine.txtEProdDesc' + ctr);
	k = eval('document.frmCreateSingleLine.txtEQty' + ctr);
	l = eval('document.frmCreateSingleLine.hEUnitPrice' + ctr);
	m = eval('document.frmCreateSingleLine.hEpmgid' + ctr);
	//n = eval('document.frmCreateSingleLine.txtPmg' + ctr);
	o = eval('document.frmCreateSingleLine.cboEPMG' + ctr);
	p = eval('document.frmCreateSingleLine.cboECriteria' + ctr);

	p.focus();
	//k.focus();
	//k.select();
	
	h.value = tmp_val[0];
	j.value = tmp_val[1];
	l.value = tmp_val[2];
	//m.value = tmp_val[3];
	i.value = tmp_val[4];
	o.value = tmp_val[5];
	
}
function deleteRow(field)
{	
	if($(field).closest('.trlist').find('td:nth-child(3) input[type=hidden]').val() != ""){
		$(field).closest('.trlist').remove();
	}else{
		return false;
	}
	
	var tableRow = $("#dynamicTable tr.trlist").length;
	$('[name=hBuyInCnt]').val((tableRow-1));
	
	if(tableRow > 0){
		$('#dynamicTable tr.trlist').each(function(index){
			var reIndex = (index + 1);
			
			$(this).find('td:nth-child(1)').find('input').attr('name', 'btnRemove'+reIndex);
			
			$(this).find('td:nth-child(2)').html(reIndex);
						
			$(this).find('td:nth-child(3)').find('input:nth-child(1)').attr('name', 'txtProdCode'+reIndex);
			$(this).find('td:nth-child(3)').find('input:nth-child(1)').attr('id', 'txtProdCode'+reIndex);			
			$(this).find('td:nth-child(3)').find('input[type=hidden]').attr('name', 'hProdID'+reIndex);
			$(this).find('td:nth-child(3)').find('input[type=hidden]').attr('id', 'hProdID'+reIndex);
			
			$(this).find('td:nth-child(4)').find('input:nth-child(1)').attr('name', 'txtProdDesc'+reIndex);
			$(this).find('td:nth-child(4)').find('input:nth-child(1)').attr('id', 'txtProdDesc'+reIndex);
			
			$(this).find('td:nth-child(5)').find('select:nth-child(1)').attr('name', 'cboCriteria'+reIndex);
			$(this).find('td:nth-child(5)').find('select:nth-child(1)').attr('id', 'cboCriteria'+reIndex);
			
			$(this).find('td:nth-child(6)').find('input:nth-child(1)').attr('name', 'txtQty'+reIndex);
			$(this).find('td:nth-child(6)').find('input:nth-child(1)').attr('id', 'txtQty'+reIndex);
			
			$(this).find('td:nth-child(7)').find('select:nth-child(1)').attr('name', 'cboECriteria'+reIndex);
			$(this).find('td:nth-child(7)').find('select:nth-child(1)').attr('id', 'cboECriteria'+reIndex);
			
			$(this).find('td:nth-child(8)').find('input:nth-child(1)').attr('name', 'txtEQty'+reIndex);
			$(this).find('td:nth-child(8)').find('input:nth-child(1)').attr('id', 'txtEQty'+reIndex);
			
			$(this).find('td:nth-child(9)').find('select:nth-child(1)').attr('name', 'txtbPmg'+reIndex);
			$(this).find('td:nth-child(9)').find('select:nth-child(1)').attr('id', 'txtbPmg'+reIndex);
			
		});
	}
	return false;
	
}

function deleteRow2(i)
{
	var table = document.getElementById('dynamicEntTable');
	var rowCount = table.rows.length;
  
	if(rowCount != 1){ 
		document.getElementById('dynamicEntTable').deleteRow(i);
		document.frmCreateSingleLine.hEntitlementCnt.value = rowCount;
	}else{
		return false;
	}
}

function addRow(field, e) 
{		 
	if(e.keyCode == 13 || e.keyCode == 9){//pressed enter or tab key
				
		var tableRow = $("#dynamicTable tr.trlist").length;
		var newIndex = (tableRow+1);
		
		var currentFieldLine = $(field).attr('name').substr(7);
				
		if($(field).closest('.trlist').find('td:nth-child(3) input[type=hidden]').val() == ""){
			$(field).closest('.trlist').find('td:nth-child(3) input:nth-child(1)').focus();
			return false;
		}
		
		var html = '<tr align="center" class="trlist">'+
						'<td>'+
							'<input name="btnRemove'+newIndex+'" type="button" class="btn" value="Remove" onclick="return deleteRow(this);">'+
						'</td>'+
						'<td>'+newIndex+'</td>'+
						'<td>'+
							'<input name="txtProdCode'+newIndex+'" type="text" class="txtfieldg" id="txtProdCode'+newIndex+'" style="width: 100%;" onkeypress="return selectItemCode(this)" value="" />'+
							'<input name="hProdID'+newIndex+'" type="hidden" id="hProdID'+newIndex+'" value="" />'+
						'</td>'+
						'<td><input name="txtProdDesc'+newIndex+'" type="text" class="txtfield" id="txtProdDesc'+newIndex+'" style="width: 100%;" readonly="yes" /></td>'+
						'<td>'+
							'<select name="cboCriteria'+newIndex+'" class="txtfield" id="cboCriteria'+newIndex+'" style="width: 100%;" >'+
								'<option value="2">Amount</option>'+
								'<option value="1" selected="selected">Quantity</option>'+
							'</select>'+
						'</td>'+
						'<td><input name="txtQty'+newIndex+'"  type="text" class="txtfield" id="txtQty'+newIndex+'" value="1" style="width: 100%; text-align:right" /></td>'+
						'<td>'+
							'<select name="cboECriteria'+newIndex+'"  class="txtfield" id="cboECriteria'+newIndex+'" style="width: 100%;">'+
								'<option value="2" selected="selected">Price</option>'+
							'</select>'+
						'</td>'+
						'<td><input name="txtEQty'+newIndex+'" type="text" class="txtfield" id="txtEQty'+newIndex+'"  onkeypress="return addRow(this, event);" value="1" style="width: 100%; text-align:right" /></td>'+
						'<td>'+
							'<select name="txtbPmg'+newIndex+'" id = "txtbPmg'+newIndex+'" class = "txtfield" style="width: 100%">'+
								'<option value="1">CFT</option>'+
								'<option value="2">NCFT</option>'+
								'<option value="3">CPI</option>'+
							'</select>'+
						'</td>'+
					'</tr>';
		
		if(currentFieldLine == tableRow){
			$('[name=hBuyInCnt]').val(tableRow);
			$("#dynamicTable").append(html);
			$('[name=txtProdCode'+newIndex+']').focus();
		}else{
			$('[name=txtProdCode'+tableRow+']').focus();
		}
		
		$('input[name=btnRemove1]').attr('onclick', 'return deleteRow(this);');		
		
		return false;
	}	
}

function addRow2() 
{
	var ml = document.frmCreateSingleLine;
	var table = document.getElementById('dynamicEntTable');
	var rowCount = table.rows.length;

	var cnt2 = document.frmCreateSingleLine.hEntIndex.value;

	   
		if(cnt2 >  rowCount)
	   	{
	   		
	   		 var a = eval('document.frmCreateSingleLine.txtEProdDesc' + cnt2);
	    		
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
	   	  
	   		 var a = eval('document.frmCreateSingleLine.txtEProdDesc' + rowCount);
	     		
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
     var elementRem = document.createElement("input");
     elementRem.type = "button";
     elementRem.setAttribute("class", "btn");
     elementRem.setAttribute("style", "width: 65px");
     elementRem.setAttribute("id", "btnRemove" + index);
     elementRem.setAttribute("name", "btnRemove" + index);
     elementRem.setAttribute("value", "Remove");
     elementRem.onclick = function () {deleteRow2(this.parentNode.parentNode.rowIndex)};
	 
     cellRem.appendChild(elementRem);
		
	//line no.
	var cell1 = row.insertCell(1);
	createCell(cell1, index, '', 'center');
	cell1.setAttribute("width", "8%");
	cell1.setAttribute("align", "left");
	cell1.setAttribute("class", "borderBR padl5");
	
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
	cell3.setAttribute("width", "24%");
	var element2 = document.createElement("input");
	element2.type = "text";
	element2.setAttribute("class", "txtfield");
	element2.setAttribute("style", "width: 95%");
	element2.setAttribute("readonly", "yes");
	element2.setAttribute("id", "txtEProdDesc" + index);
	element2.setAttribute("name", "txtEProdDesc" + index);
	cell3.appendChild(element2);
	
	//criteria
	var cell4 = row.insertCell(4);
	cell4.setAttribute("align", "center");
	cell4.setAttribute("class", "borderBR");
	cell4.setAttribute("width", "10%");
	var element3 = document.createElement("select");
	var option1 = document.createElement('option');
	var option2 = document.createElement('option');
	var criteria = [ 'Price', 'Quantity' ];
	var text1 = document.createTextNode(criteria[0]);
	//var text2 = document.createTextNode(criteria[1]);

	option1.appendChild(text1);
	option1.value = 2;
	option1.selected = "selected";
	element3.appendChild(option1);
	//option2.appendChild(text2);
	//option2.value = 1;
	//element3.appendChild(option2);
	element3.setAttribute("class", "txtfield");
	element3.setAttribute("id", "cboECriteria" + index);
	element3.setAttribute("name", "cboECriteria" + index);
	element3.setAttribute("style", "width: 90%");
	cell4.appendChild(element3)
	
	//minimum
	var cell5 = row.insertCell(5);
	cell5.setAttribute("align", "center");
	cell5.setAttribute("class", "borderBR");
	cell5.setAttribute("width", "11%");
	var element4 = document.createElement("input");
	element4.type = "input";
	element4.setAttribute("class", "txtfield");
	element4.setAttribute("style", "width: 90%; text-align: right");
	element4.setAttribute("onBlur", "addRow2()");
	element4.setAttribute("id", "txtEQty" + index);
	element4.setAttribute("name", "txtEQty" + index);
	element4.setAttribute("value", "1");
	cell5.appendChild(element4);
	
	
	//pmg
	var cell6 = row.insertCell(6);
	cell6.setAttribute("align", "center");
	cell6.setAttribute("class", "borderBR");
	cell6.setAttribute("width", "9%");
	
	var elementpmgvalue = document.createElement("input");
	elementpmgvalue.type = "input";
	elementpmgvalue.setAttribute("value", "");
	elementpmgvalue.setAttribute("id", "cboEPMG" + index);
	elementpmgvalue.setAttribute("name", "cboEPMG" + index);
	elementpmgvalue.setAttribute("class", "txtfield");
	elementpmgvalue.setAttribute("readonly", "readonly");
	elementpmgvalue.setAttribute("style", "width: 90%");

	cell6.appendChild(elementpmgvalue);
	
	document.frmCreateSingleLine.hEntIndex.value = index;
	var url = 'includes/scProductListEntitlementAjax.php?index=' + index;
	var prod_choicesE = new Ajax.Autocompleter(element1.id, element1d.id, url, {afterUpdateElement : getSelectionProductList2, indicator: element1s.id});
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

function getDateObject(dateString,dateSeperator)
{
	//This function return a date object after accepting 
	//a date string ans dateseparator as arguments
	var curValue=dateString;
	var sepChar=dateSeperator;
	var curPos=0;
	var cDate,cMonth,cYear;

	//extract day portion
	curPos=dateString.indexOf(sepChar);
	cMonth=dateString.substring(0,curPos);
	
	//extract month portion				
	endPos=dateString.indexOf(sepChar,curPos+1);			
	cDate=dateString.substring(curPos+1,endPos);

	//extract year portion				
	curPos=endPos;
	endPos=curPos+5;			
	cYear=curValue.substring(curPos+1,endPos);
	
	//Create Date Object
	dtObject=new Date(cYear,cMonth,cDate);	
	return dtObject;
}

function confirmSave()
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
	
	//addRow();
	//addRow2();
	
	var bTable = document.getElementById('dynamicTable');
	var bCount = bTable.rows.length;
	//var eTable = document.getElementById('dynamicEntTable');
	//var eCount = eTable.rows.length;
	
	//buyin_cnt.value = bCount;
	//entitlement_cnt.value = eCount;
	//ml.hBuyInCnt.value = bCount;
	//ml.hEntitlementCnt.value = eCount;
	
	var now = new Date();
	var now_day = now.getDate();
	var now_month = now.getMonth() + 1;
	var now_year = now.getFullYear();
	var now_date = now_month + "/" + now_day + "/" + now_year;
	
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
	
	if(getDateObject(ml.txtStartDate.value, "/") < getDateObject(now_date, "/"))
	{			
		alert("Start date should be current or future date.");
		ml.txtStartDate.select();
		ml.txtStartDate.focus();
		return false;
	}
	
	if(sdate > edate){			
		alert("End date should be the same or later than Start date.");
		ml.txtEndDate.select();
		ml.txtEndDate.focus();
		return false;
	}
	
	if (bCount == 1){
		var a = eval('document.frmCreateSingleLine.txtProdCode1');
		
		if (a.value == ""){
			alert("Select Item Code.");
			a.focus();
			return false;
		}
	}
	

	if (confirm('Are you sure you want to save this transaction?') == false){
		
		return false;
	}else{
	
		return true;
	}
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

function checkEndDate()
{
	var staDate = document.getElementById('txtStartDate');
	var endDate = document.getElementById('txtEndDate');
	
	endDate.value = staDate.value;
}


function confirmVerify()
{
	var ml = document.frmCreateSingleLine;
	var promo_code = ml.txtCode;	
	
	if (Trim(promo_code.value) == "")
	{
		alert ('Promo Code required.');
		promo_code.focus();
		return false;
	}	
}


function CheckPromo(e){
    var Code = document.getElementById('txtCode');
    if (e.keyCode == 13 || e.keyCode == 9) {
	if(Code.value != "")
    {
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
                var value = xmlhttp.responseText;

                if(value.length<=2)
                {
					document.getElementById('txtDescription').removeAttribute("disabled"); 
					document.getElementById('txtStartDate').removeAttribute("disabled"); 
					document.getElementById('txtEndDate').removeAttribute("disabled"); 
					document.getElementById('txtProdCode1').removeAttribute("disabled"); 
					document.getElementById('cboCriteria1').removeAttribute("disabled"); 
					document.getElementById('txtQty1').removeAttribute("disabled"); 
					//document.getElementById('txtEProdCode1').removeAttribute("disabled"); 
					//document.getElementById('cboECriteria1').removeAttribute("disabled"); 
					//document.getElementById('cboEPMG1').removeAttribute("disabled"); 
					document.getElementById('bpage').removeAttribute("disabled"); 
					document.getElementById('epage').removeAttribute("disabled"); 
					document.getElementById('savebtn').removeAttribute("disabled"); 
					document.getElementById('txtMaxAvail1').removeAttribute("disabled"); 
					document.getElementById('txtMaxAvail2').removeAttribute("disabled"); 
					document.getElementById('txtMaxAvail3').removeAttribute("disabled"); 
				  document.getElementById('txtDescription').focus();
                }   
                else {
                  alert("Promo Code Already exist please choose Other Promo Code");
                    document.getElementById('txtCode').value = '';
					Code.focus();
                }
            }
          }
        xmlhttp.open("GET","includes/jxsinglelinepromo.php?Code="+Code.value, true);
        xmlhttp.send(null);
    }
	return false;
  }
}


function NextField(e,a)
{
	 if (e.keyCode == 13 || e.keyCode == 9) {
		document.getElementById(a).focus();
		// document.getElementById('txtStartDate').focus();
		return false;
	 }
}

function selectItemCode(item){
    
    var index = $(item).attr('name').substr(11);
	
    $(item).autocomplete({
        source  :   function(request, response){
            $.ajax({
                type    :   "post",
                url     :   "includes/jxCreateSpecialPromo.php",
                dataType:   "json",
                data    :   {promocode  :   request.term},
                success :   function(data){
                    response($.map(data, function(item){
                        return{
                            value   :   item.Value,
                            label   :   item.Label,
                            Name    :   item.Name,
                            ID      :   item.ID,
                            Code    :   item.Code,
                            PMGID   :   item.PMGID,
                            PMGCode :   item.PMGCode,
                            Price   :   item.UnitPrice
                        }
                    }));
                }
            });
        },
        select  :   function(event, ui){
            $('[name=hProdID'+ index +']').val(ui.item.ID);
            $('[name=txtProdCode'+ index +']').val(ui.item.Code);			
            $('[name=txtProdDesc'+ index +']').val(ui.item.Name);
			$('[name=txtEQty'+ index +']').focus();
			$('[name=hBuyInCnt]').val(index);
			
			var selectionx = '';
			
			if(ui.item.PMGID == 1){
				selectionx += "<option value='1'>CFT</option>";
				selectionx += "<option value='3'>CPI</option>";
			}else if(ui.item.PMGID == 2){
				selectionx += "<option value='2'>NCFT</option>";
				selectionx += "<option value='3'>CPI</option>";
			}else if(ui.item.PMGID == 3){
				selectionx += "<option value='3'>CPI</option>";
			}else{
				selectionx += '<option value="1">CFT</option>';
				selectionx += '<option value="2">NCFT</option>';
				selectionx += '<option value="3">CPI</option>';
			}
			
			$('[name=txtbPmg'+index+']').html(selectionx);
        }
    });
}
//function ClickDate(item){
//	$("#"+item).datepicker();
//}
$(document).ready(function(){
	$("#txtStartDate, #txtEndDate").datepicker();
	
	
	$('[name=txtCode]').keyup(function(){
			
		var validation = /^[A-z 0-9]*$/i;
		var val = $(this).val();
		var string = '';
		
		if(!validation.test($(this).val())){
			
			for(var x = 0; x < $(this).val().length; x++){
			
				if(!validation.test(val.charAt(x))){
					string += '';
				}else{
					string += val.charAt(x);
				}
				
			}
			
			$(this).val(string);
		}
		
	});
	
	$('[name=txtDescription]').keyup(function(){
			
		var validation = /^[A-z 0-9]*$/i;
		var val = $(this).val();
		var string = '';
		
		if(!validation.test($(this).val())){
			
			for(var x = 0; x < $(this).val().length; x++){
			
				if(!validation.test(val.charAt(x))){
					string += '';
				}else{
					string += val.charAt(x);
				}
				
			}
			
			$(this).val(string);
		}
		
	});
});