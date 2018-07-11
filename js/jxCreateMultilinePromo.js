

// JavaScript Document
function SelectionField(){
	//alert(document.getElementById("cboType").value);
	if(document.getElementById("cboType").value == 1){
			document.getElementById("txtTypeQty").setAttribute("readonly","readonly");
			document.getElementById("txtTypeQty").value = "";
	}else{
			document.getElementById('txtTypeQty').removeAttribute("readonly"); 
	}
}
function confirmVerify()
{
	alert(jQuery('#txtCode').val());
	return false;
}

function validateForm()
{
var x=document.forms["frmCreateMultiLine"]["txtCode"].value;
if (x==null || x=="")
  {
  alert("Enter Promo code.");
  redirect_to("index.php?pageid=63");
  return false;
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

function CheckPromo(e){
    var Code = document.getElementById('txtCode');
    if(e.keyCode == 9 || e.keyCode == 13){
		if(Code.value != ""){
			if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp=new XMLHttpRequest();
			}else{// code for IE6, IE5
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function()
			{
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
				{
					var value = xmlhttp.responseText;
					if(value.length > 2)
					{
						alert("Promo Code Already exist please choose Other Promo Code");
						document.getElementById('txtCode').value = '';
						Code.focus();
					}
					else
					{
					
					document.getElementById('txtDescription').removeAttribute("disabled"); 
					document.getElementById('txtStartDate').removeAttribute("disabled"); 
					document.getElementById('txtEndDate').removeAttribute("disabled"); 
					document.getElementById('txtProdCode1').removeAttribute("disabled"); 
					document.getElementById('cboCriteria1').removeAttribute("disabled"); 
					document.getElementById('txtQty1').removeAttribute("disabled"); 
					document.getElementById('txtEProdCode1').removeAttribute("disabled"); 
					document.getElementById('cboECriteria1').removeAttribute("disabled"); 
					document.getElementById('cboEPMG1').removeAttribute("disabled"); 
					document.getElementById('btnSave').removeAttribute("disabled"); 
					document.getElementById('cboPReqtType').removeAttribute("disabled"); 
					document.getElementById('txtProdDesc1').removeAttribute("disabled"); 
					document.getElementById('txtbPmg1').removeAttribute("disabled"); 
					document.getElementById('txtEProdDesc1').removeAttribute("disabled"); 
					document.getElementById('txtEQty1').removeAttribute("disabled"); 
					document.getElementById('cboType').removeAttribute("disabled"); 
					document.getElementById('txtTypeQty').removeAttribute("disabled"); 
					document.getElementById('chkPlusPlan').removeAttribute("disabled"); 
					document.getElementById('txtMaxAvail1').removeAttribute("disabled"); 
					document.getElementById('txtMaxAvail2').removeAttribute("disabled"); 
					document.getElementById('txtMaxAvail3').removeAttribute("disabled"); 
					document.getElementById('bpage').removeAttribute("disabled"); 
					document.getElementById('epage').removeAttribute("disabled"); 
					document.getElementById('txtDescription').focus();
					
					}
				}
			}
			xmlhttp.open("GET","includes/jxsinglelinepromo.php?Code="+Code.value, true);
			xmlhttp.send(null);
		}
	  return false;
	}
	
}


function deleteRow(field)
{	
	if($j(field).closest('.trlist').find('td:nth-child(3) input[type=hidden]').val() != ""){
		$j(field).closest('.trlist').remove();
	}else{
		return false;
	}
	
	var tableRow = $j("#dynamicTable tr.trlist").length;
	$j('[name=hBuyInIndex]').val((tableRow-1));
	
	if(tableRow > 0){
		$j('#dynamicTable tr.trlist').each(function(index){
			var reIndex = (index + 1);
			
			$j(this).find('td:nth-child(1)').find('input').attr('name', 'btnRemove'+reIndex);
			
			$j(this).find('td:nth-child(2)').html(reIndex);
						
			$j(this).find('td:nth-child(3)').find('input:nth-child(1)').attr('name', 'txtProdCode'+reIndex);
			$j(this).find('td:nth-child(3)').find('input:nth-child(1)').attr('id', 'txtProdCode'+reIndex);
			$j(this).find('td:nth-child(3)').find('input[type=hidden]').attr('name', 'hProdID'+reIndex);
			$j(this).find('td:nth-child(3)').find('input[type=hidden]').attr('id', 'hProdID'+reIndex);
			
			$j(this).find('td:nth-child(4)').find('input:nth-child(1)').attr('name', 'txtProdDesc'+reIndex);
			$j(this).find('td:nth-child(4)').find('input:nth-child(1)').attr('id', 'txtProdDesc'+reIndex);
			
			$j(this).find('td:nth-child(5)').find('select:nth-child(1)').attr('name', 'cboCriteria'+reIndex);
			$j(this).find('td:nth-child(5)').find('select:nth-child(1)').attr('id', 'cboCriteria'+reIndex);
			
			$j(this).find('td:nth-child(6)').find('input:nth-child(1)').attr('name', 'txtQty'+reIndex);
			$j(this).find('td:nth-child(6)').find('input:nth-child(1)').attr('id', 'txtQty'+reIndex);
			
			$j(this).find('td:nth-child(7)').find('input').attr('name', 'txtbPmg'+reIndex);
			$j(this).find('td:nth-child(7)').find('input').attr('id', 'txtbPmg'+reIndex);
			
		});
	}
	return false;
	
}

function deleteRow2(field)
{
	if($j(field).closest('.trlist').find('td:nth-child(3) input[type=hidden]').val() != ""){
		$j(field).closest('.trlist').remove();
	}else{
		return false;
	}
	
	var tableRow = $j("#dynamicEntTable tr.trlist").length;
	$j('[name=hEntIndex]').val((tableRow-1));
	
	if(tableRow > 0){
		$j('#dynamicEntTable tr.trlist').each(function(index){
			var reIndex = (index + 1);
			
			$j(this).find('td:nth-child(1)').find('input').attr('name', 'btnERemove'+reIndex);
			
			$j(this).find('td:nth-child(2)').html(reIndex);
						
			$j(this).find('td:nth-child(3)').find('input:nth-child(1)').attr('name', 'txtEProdCode'+reIndex);
			$j(this).find('td:nth-child(3)').find('input:nth-child(1)').attr('id', 'txtEProdCode'+reIndex);
			$j(this).find('td:nth-child(3)').find('input[type=hidden]').attr('name', 'hEProdID'+reIndex);
			$j(this).find('td:nth-child(3)').find('input[type=hidden]').attr('id', 'hEProdID'+reIndex);
			
			$j(this).find('td:nth-child(4)').find('input[type=hidden]').attr('name', 'hEUnitPrice'+reIndex);
			$j(this).find('td:nth-child(4)').find('input[type=hidden]').attr('id', 'hEUnitPrice'+reIndex);
						
			$j(this).find('td:nth-child(4)').find('input:nth-child(1)').attr('name', 'txtEProdDesc'+reIndex);
			$j(this).find('td:nth-child(4)').find('input:nth-child(1)').attr('id', 'txtEProdDesc'+reIndex);
			
			$j(this).find('td:nth-child(5)').find('input[type=hidden]').attr('name', 'hEpmgid'+reIndex);
			$j(this).find('td:nth-child(5)').find('input[type=hidden]').attr('id', 'hEpmgid'+reIndex);
			
			$j(this).find('td:nth-child(5)').find('select:nth-child(1)').attr('name', 'cboECriteria'+reIndex);
			$j(this).find('td:nth-child(5)').find('select:nth-child(1)').attr('id', 'cboECriteria'+reIndex);
			
			$j(this).find('td:nth-child(6)').find('input:nth-child(1)').attr('name', 'txtEQty'+reIndex);
			$j(this).find('td:nth-child(6)').find('input:nth-child(1)').attr('id', 'txtEQty'+reIndex);
			
			$j(this).find('td:nth-child(7)').find('select').attr('name', 'cboEPMG'+reIndex);
			$j(this).find('td:nth-child(7)').find('select').attr('id', 'cboEPMG'+reIndex);
			
		});
	}
	return false;
}

function addRow(field, e){
			
	if(e.keyCode==13 || e.keyCode==9){
		
		var tableRow = $j('#dynamicTable').find('.trlist').length;
		var newIndex = (tableRow+1);
		
		var currentFieldLine = $j(field).attr('name').substr(6);
		
		if($j(field).closest('.trlist').find('td:nth-child(3) input[type=hidden]').val() == ""){
			$j(field).closest('.trlist').find('td:nth-child(3) input:nth-child(1)').focus();
			return false;
		}
		
		var html = '<tr align="center" class="trlist">'+
						'<td width="9%">'+
							'<input name="btnRemove'+newIndex+'" type="button" class="btn" value="Remove" onclick="return deleteRow(this);">'+
							'<input type="hidden"  name="chkSelect[]" id="chkSelect" checked="checked"  value="1">'+
						'</td>'+
						'<td width="7%" align="center">'+newIndex+'</td>'+
						'<td width="13%">'+
							'<input name="txtProdCode'+newIndex+'" type="text" class="txtfieldg" id="txtProdCode'+newIndex+'" onkeypress="return selectItemCode(this);" style="width: 99%;" value=""/>'+						
							'<input name="hProdID'+newIndex+'" type="hidden" id="hProdID'+newIndex+'" value="" />'+
						'</td>'+
						'<td width="28%"><input name="txtProdDesc'+newIndex+'" type="text" class="txtfieldg" id="txtProdDesc'+newIndex+'" style="width: 99%;" readonly="yes" /></td>'+
						'<td width="11%">'+
							'<select name="cboCriteria'+newIndex+'" class="txtfieldg" id="cboCriteria'+newIndex+'" style="width: 99%;">'+
								'<option value="2">Amount</option>'+
								'<option value="1" selected="selected">Quantity</option>'+
							'</select>'+
						'</td>'+
						'<td width="10%">'+
							'<input name="txtQty'+newIndex+'" type="text" class="txtfieldg" id="txtQty'+newIndex+'"  value="1" style="width: 99%; text-align:right" onkeypress="return addRow(this, event);"/>'+
						'</td>'+
						'<td width="11%">'+
							'<input name="txtbPmg'+newIndex+'" type="text" id="txtbPmg'+newIndex+'" class="txtfieldg" readonly="readonly"  style="width: 99%; text-align:left" value=""/>'+
						'</td>'+
					'</tr>';
		
		if(currentFieldLine == tableRow){
			$j('[name=hBuyInIndex]').val(tableRow);
			$j("#dynamicTable").append(html);
			$j('[name=txtProdCode'+newIndex+']').focus();
		}else{
			$j('[name=txtProdCode'+tableRow+']').focus();
		}
		
		$j('input[name=btnRemove1]').attr('onclick', 'return deleteRow(this);');
		
		return false;
	}
}


function addRow2(field, e) 
{

	if(e.keyCode==13 || e.keyCode==9){
		
		var tableRow = $j('#dynamicEntTable').find('.trlist').length;
		var newIndex = (tableRow+1);
		
		var currentFieldLine = $j(field).attr('name').substr(7);
		
		if($j(field).closest('.trlist').find('td:nth-child(3) input[type=hidden]:nth-child(1)').val() == ""){
			$j(field).closest('.trlist').find('td:nth-child(3) input:nth-child(1)').focus();
			return false;
		}
		
		var html = '<tr align="center" class="trlist">'+
						'<td>'+
							'<input name="btnERemove'+newIndex+'" type="button" class="btn" value="Remove" onclick="return deleteRow2(this)">'+
							'<input type="hidden"  name="chkSelectEnt[]" id="chkSelectEnt" checked="checked"  value="1">'+
						'</td>'+
						'<td>'+newIndex+'</td>'+
						'<td>'+
							'<input name="txtEProdCode'+newIndex+'" type="text" class="txtfieldg" id="txtEProdCode'+newIndex+'" style="width: 99%;" value="" onkeypress="return selectItemCode2(this);"/>'+
							'<input name="hEProdID'+newIndex+'" type="hidden" id="hEProdID'+newIndex+'" value="" />'+							
						'</td>'+
						'<td>'+
							'<input name="txtEProdDesc'+newIndex+'" type="text" class="txtfieldg" id="txtEProdDesc'+newIndex+'" style="width: 99%;" readonly="yes" />'+
							'<input name="hEUnitPrice'+newIndex+'" type="hidden" id="hEUnitPrice'+newIndex+'" value=""/>'+
						'</td>'+
						'<td>'+
							'<input name="hEpmgid'+newIndex+'" type="hidden" id="hEpmgid'+newIndex+'" value=""/>'+
							'<select name="cboECriteria'+newIndex+'" class="txtfieldg" id="cboECriteria'+newIndex+'" style="width: 99%;">'+
								'<option value="2" selected="selected">Price</option>'+
								'<option value="1">Quantity</option>'+
							'</select>'+
						'</td>'+
						'<td>'+
							'<input name="txtEQty'+newIndex+'" type="text" class="txtfieldg" id="txtEQty'+newIndex+'"  value="1" style="width: 99%; text-align:right" onkeypress="return addRow2(this, event)"/>'+
						'</td>'+
						'<td>'+
							'<select name="cboEPMG'+newIndex+'" class="txtfieldg" id="cboEPMG'+newIndex+'" style="width: 99%;">'+
								'<option value="1">CFT</option>'+
								'<option value="2">NCFT</option>'+
								'<option value="3">CPI</option>'+
							'</select>'+
						'</td>'+
					'</tr>';
		
		if(currentFieldLine == tableRow){
			$j('[name=hEntIndex]').val(tableRow);
			$j("#dynamicEntTable").append(html);
			$j('[name=txtEProdCode'+newIndex+']').focus();
		}else{
			$j('[name=txtEProdCode'+tableRow+']').focus();
		}
		
		$j('input[name=btnERemove1]').attr('onclick', 'return deleteRow2(this);');
		
		return false;
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
	var ml = document.frmCreateMultiLine;
	var promo_code = ml.txtCode;
	var promo_desc = ml.txtDescription;
	var promo_sdate = ml.txtStartDate;
	var promo_edate = ml.txtEndDate;
	var qty = ml.txtQty1;
	var buyin_cnt = 0;
	var entitlement_cnt = 0;
	var type = ml.cboType;
	var typeqty = ml.txtTypeQty;
	
	var bTable = document.getElementById('dynamicTable');
	var bCount = bTable.rows.length;
	var eTable = document.getElementById('dynamicEntTable');
	var eCount = eTable.rows.length;
	
	var now = new Date();
	var now_day = now.getDate();
	var now_month = now.getMonth() + 1;
	var now_year = now.getFullYear();
	var now_date = now_month + "/" + now_day + "/" + now_year;
	
	buyin_cnt.value = bCount;
	entitlement_cnt.value = eCount;
	ml.hBuyInCnt.value = bCount;
	ml.hEntitlementCnt.value = eCount;
	
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

	if(getDateObject(promo_sdate.value, "/") < getDateObject(now_date, "/"))
	{			
		alert("Start date should be current or future date.");
		ml.txtStartDate.select();
		ml.txtStartDate.focus();
		return false;
	}
	
	if(sdate > edate)
	{
		alert("End date should be the same or later than Start date.");
		ml.txtEndDate.select();
		ml.txtEndDate.focus();
		return false;
	}
	
	/*if(sdate > edate)
	{			
		alert("End date should be the same or later than Start date.");
		ml.txtEndDate.select();
		ml.txtEndDate.focus();
		return false;
	}*/
	
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
	
	if (bCount == 1)
	{
		var a = eval('document.frmCreateMultiLine.txtQty1');
		
		if (a.value == "")
		{
			alert("Insert Quantity / Amount.");
			a.focus();
			return false;
		}
	}
	
	if(document.getElementById('cboType').value == 2){
		if(document.getElementById('txtTypeQty').value == ''){
			alert('Insert Selection Number');
			document.getElementById('txtTypeQty').focus();
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

function enableFields()
{
	var ml = document.frmCreateMultiLine;
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
function NextField(e,a)
{
	 if (e.keyCode == 13 || e.keyCode == 9) {
		document.getElementById(a).focus();
		// document.getElementById('txtStartDate').focus();
		return false;
	 }
}

$j=$.noConflict()
$j(document).ready(function(){
	$j("#txtStartDate, #txtEndDate").datepicker(//{
       //changeMonth: true,
       //changeYear: true
    //}
	);
});

function selectItemCode(item){
    
    var index = $j(item).attr('name').substr(11);
	
    $j(item).autocomplete({
        source  :   function(request, response){
            $j.ajax({
                type    :   "post",
                url     :   "includes/jxCreateSpecialPromo.php",
                dataType:   "json",
                data    :   {promocode  :   request.term},
                success :   function(data){
                    response($j.map(data, function(item){
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
            $j('[name=hProdID'+ index +']').val(ui.item.ID);
            $j('[name=txtProdDesc'+ index +']').val(ui.item.Name);
			$j('[name=txtbPmg'+ index +']').val(ui.item.PMGCode);
			$j('[name=hBuyInIndex]').val(index);
			$j('[name=txtQty'+ index +']').focus();
        }
    });
}

function selectItemCode2(item){
    
    var index = $j(item).attr('name').substr(12);
	
    $j(item).autocomplete({
        source  :   function(request, response){
            $j.ajax({
                type    :   "post",
                url     :   "includes/jxCreateSpecialPromo.php",
                dataType:   "json",
                data    :   {promocode  :   request.term},
                success :   function(data){
                    response($j.map(data, function(item){
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
            $j('[name=hEProdID'+ index +']').val(ui.item.ID);
            $j('[name=txtEProdDesc'+ index +']').val(ui.item.Name);
            $j('[name=hEUnitPrice'+ index +']').val(ui.item.Price);			
			$j('[name=hEpmgid'+ index +']').val(ui.item.PMGID);
			$j('[name=hEntIndex]').val(index);
			$j('[name=txtEQty'+ index +']').focus();
        }
    });
}

$j(function(){
	$j('[name=txtCode]').keyup(function(){
			
		var validation = /^[A-z 0-9]*$/i;
		var val = $j(this).val();
		var string = '';
		
		if(!validation.test($j(this).val())){
			
			for(var x = 0; x < $j(this).val().length; x++){
			
				if(!validation.test(val.charAt(x))){
					string += '';
				}else{
					string += val.charAt(x);
				}
				
			}
			
			$j(this).val(string);
		}
		
	});
	
	$j('[name=txtDescription]').keyup(function(){
		
		var validation = /^[A-z 0-9]*$/i;
		var val = $j(this).val();
		var string = '';
		
		if(!validation.test($j(this).val())){
			
			for(var x = 0; x < $j(this).val().length; x++){
			
				if(!validation.test(val.charAt(x))){
					string += '';
				}else{
					string += val.charAt(x);
				}
				
			}
			
			$j(this).val(string);
		}
		
	});
});