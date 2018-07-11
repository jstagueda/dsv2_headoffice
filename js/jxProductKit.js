$j = jQuery.noConflict();

function getSelectionCOAID(text,li)
{
	tmp = li.id;
	tmp_val = tmp.split("_");
	prodID = eval('document.frmProdKitInfo.hProductID');
	prodCode = eval('document.frmProdKitInfo.hProductCode');
	prodName = eval('document.frmProdKitInfo.hProductName');
	var txtQuantity = eval('document.frmProdKitInfo.txtQty');
	prodID.value  	= tmp_val[0];
	prodName.value 	= tmp_val[1];
	prodCode.value	= tmp_val[2];
        if(tmp_val[0] == 0){
            eval('document.frmProdKitInfo.txtComponent').value = '';
        }else{
            txtQuantity.focus();
        }

}

function clickAdd()
{
	validateAdd();
}

function validateSave()
{
	var table = document.getElementById('dynamicTable');
	var rowCount = table.rows.length;
	var txtKit = eval('document.frmProdKitInfo.kitID');
	
	var title = "Product Kit";
	var message = "";
	var buttonFunction = {};
	
	if(txtKit.value == "")
	{
		message = "Product Kit is required.";
		buttonFunction['Ok'] = function(){
			$j('#dialog-message').dialog('close');
		}
		popinmessage(title, message, buttonFunction);
		return false;
	}

	if(rowCount == 0)
	{
		message = "Component(s) is required.";
		buttonFunction['Ok'] = function(){
			$j('#dialog-message').dialog('close');
		}
		popinmessage(title, message, buttonFunction);
		return false;
	}

	for (var i = 1; i <= rowCount; i++)
	{
		var qty = eval('document.frmProdKitInfo.txtComponentQty' + i);

		if(qty.value == "")
		{
			message = "Quantity is required.";
			buttonFunction['Ok'] = function(){
				$j('#dialog-message').dialog('close');
				qty.focus();
			}
			popinmessage(title, message, buttonFunction);
			return false;
			
		}

		if(!isNumeric(qty.value))
		{	
		
			message = "Invalid value for Quantity.";
			buttonFunction['Ok'] = function(){
				$j('#dialog-message').dialog('close');
				qty.focus();
				qty.select();
			}
			popinmessage(title, message, buttonFunction);
			return false;
			
		}
	}
	
	message = "Are you sure you want to link component(s) to kit?";
	buttonFunction['Yes'] = function(){
		$j('#dialog-message').dialog('close');
		$j('form[name=frmProdKitInfo]').append('<input type="hidden" value="1" name="btnSave">');
		$j('form[name=frmProdKitInfo]').submit();
	}
	
	buttonFunction['No'] = function(){
		$j('#dialog-message').dialog('close');
	}
	
	popinmessage(title, message, buttonFunction);
	return false;
	
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

function validateAdd()
{
	var rowCount = $j('tr.trlist').find('input[type=checkbox]').length;
	var txtComponent = eval('document.frmProdKitInfo.txtComponent');
	var txtQuantity = eval('document.frmProdKitInfo.txtQty');
	var txtSDate = eval('document.frmProdKitInfo.txtSDate');
	var txtEDate = eval('document.frmProdKitInfo.txtEDate');
	
	var title = "Product Kit";
	var message = '';
	var buttonFunction = {};

	if(txtComponent.value == "")
	{
		message = "Product is required.";
		buttonFunction['Ok'] = function(){
			$j('#dialog-message').dialog('close');
			txtComponent.focus();
		}
		popinmessage(title, message, buttonFunction);		
		return false;
	}

	if(txtQuantity.value == "")
	{
		message = "Quantity is required.";
		buttonFunction['Ok'] = function(){
			$j('#dialog-message').dialog('close');
			txtQuantity.focus();
		}
		popinmessage(title, message, buttonFunction);		
		return false;
	}

	if(!isNumeric(txtQuantity.value))
	{
	
		message = "Invalid value for Quantity.";
		buttonFunction['Ok'] = function(){
			$j('#dialog-message').dialog('close');
			txtQuantity.focus();
			txtQuantity.select();
		}
		popinmessage(title, message, buttonFunction);		
		return false;
	}

	if(txtSDate.value == "")
	{
		message = "Start Date is required.";
		buttonFunction['Ok'] = function(){
			$j('#dialog-message').dialog('close');
			txtSDate.focus();
		}
		popinmessage(title, message, buttonFunction);		
		return false;
	}

	if(txtEDate.value == "")
	{
		message = "End Date is required.";
		buttonFunction['Ok'] = function(){
			$j('#dialog-message').dialog('close');
			txtEDate.focus();
		}
		popinmessage(title, message, buttonFunction);		
		return false;
	}

	if(getDateObject(txtSDate.value, "/") > getDateObject(txtEDate.value, "/"))
	{
		message = "End date should be the same or later than Start date.";
		buttonFunction['Ok'] = function(){
			$j('#dialog-message').dialog('close');
			txtSDate.select();
			txtSDate.focus();
		}
		popinmessage(title, message, buttonFunction);		
		return false;
	}

	var now = new Date();
	var now_day = now.getDate();
	var now_month = now.getMonth() + 1;
	var now_year = now.getFullYear();
	var now_date = now_month + "/" + now_day + "/" + now_year;

	if(getDateObject(txtSDate.value, "/") < getDateObject(now_date, "/"))
	{
		message = "Start date should be current or future date.";
		buttonFunction['Ok'] = function(){
			$j('#dialog-message').dialog('close');
			txtSDate.select();
			txtSDate.focus();
		}
		popinmessage(title, message, buttonFunction);		
		return false;
	}

	if(rowCount > 0)
	{
		checkDuplicate = 0;
		checkDuplicate_s = 0;
		checkDuplicate_e = 0;

		for(i = 1 ; i <= rowCount; i++)
		{
			var prodID = eval('document.frmProdKitInfo.hProdID' + i);
			var curProdID =  eval('document.frmProdKitInfo.hProductID');

			prodSDate = eval('document.frmProdKitInfo.hDStart' + i);
			prodEDate = eval('document.frmProdKitInfo.hDEnd' + i);

			if(curProdID.value == prodID.value)
			{
				//start date
				if (getDateObject(txtSDate.value, "/") > getDateObject(prodSDate.value, "/"))
				{
					checkDuplicate_s = 0;
				}
				else if (getDateObject(txtSDate.value, "/") < getDateObject(prodSDate.value, "/"))
				{
					checkDuplicate_s = 0;
				}
				else
				{
					checkDuplicate_s = 1;
				}

				//end date
				if (getDateObject(txtEDate.value, "/") > getDateObject(prodEDate.value, "/"))
				{
					checkDuplicate_e = 0;
				}
				else if (getDateObject(txtEDate.value, "/") < getDateObject(prodEDate.value, "/"))
				{
					checkDuplicate_e = 0;
				}
				else
				{
					checkDuplicate_e = 1;
				}

				if (checkDuplicate_s == 1 && checkDuplicate_e == 1)
				{
					checkDuplicate = 1;
				}
			}
		}

		if(checkDuplicate == 1)
		{
		
			message = "This product has been added to the kit.";
			buttonFunction['Ok'] = function(){
				$j('#dialog-message').dialog('close');
				txtComponent.value = "";
				txtQuantity.value = "";
				txtComponent.focus();
			}
			popinmessage(title, message, buttonFunction);
			return false;
		}
		else
		{
			addRow();
			txtComponent.value = "";
			txtQuantity.value = "";
			txtComponent.focus();
		}
	}
	else
	{
		$j('#dynamicTable tr').remove();
		addRow() ;
		txtComponent.value = "";
		txtQuantity.value = "";
		txtComponent.focus();
	}
}

function addRow()
{
	var table = document.getElementById('dynamicTable');
	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);
	var index = eval(rowCount + 1);
	
	row.setAttribute('class', 'trlist');
	
	//alert(rowCount + 'b');
	var cell2 = row.insertCell(0);
	//createCell(cell2, index);
	cell2.setAttribute("align", "center");
	cell2.setAttribute("class", "borderBR");
	cell2.setAttribute("height", "22");
	cell2.setAttribute("width", "5%");

	var checkbox = document.createElement("input");
	checkbox.type = "checkbox";
	checkbox.setAttribute("id", "chkInclude");
	checkbox.setAttribute("name", "chkInclude[]");
	checkbox.setAttribute("style", "text-align:right");
	checkbox.setAttribute("value", index);
	cell2.appendChild(checkbox);

	var tmpprodID = eval('document.frmProdKitInfo.hProductID');
	var hProdID = document.createElement("input");
	hProdID.type = "hidden";
	hProdID.setAttribute("id", "hProdID" + index);
	hProdID.setAttribute("name", "hProdID" + index);
	hProdID.setAttribute("style", "text-align:right");
	hProdID.setAttribute("value",tmpprodID.value );
	cell2.appendChild(hProdID);

	var cell3 = row.insertCell(1);
	//createCell(cell2, index);
	cell3.setAttribute("align", "left");
	cell3.setAttribute("class", "borderBR padl5");
	cell3.setAttribute("width", "15%");

	var tmpprodCode = eval('document.frmProdKitInfo.hProductCode');
	var productCode = document.createElement("input");
	productCode.type = "text";
	productCode.setAttribute("class", "txtfieldLabel");
	productCode.setAttribute("readonly", "yes");
	productCode.setAttribute("id", "prodCode" + index);
	productCode.setAttribute("name", "prodCode" + index);

	productCode.setAttribute("value",tmpprodCode.value );
	cell3.appendChild(productCode);

	var cell4 = row.insertCell(2);
	//createCell(cell2, index);
	cell4.setAttribute("align", "left");
	cell4.setAttribute("class", "borderBR padl5");
	cell4.setAttribute("width", "40%");

	var tmpprodName = eval('document.frmProdKitInfo.hProductName');
	var productName = document.createElement("input");
	productName.type = "text";
	productName.setAttribute("class", "txtfieldLabel");
	productName.setAttribute("readonly", "yes");
	productName.setAttribute("id", "prodName" + index);
	productName.setAttribute("name", "prodName" + index);
	productName.setAttribute("size", "50");
	productName.setAttribute("value",tmpprodName.value );
	cell4.appendChild(productName);

	var cell5 = row.insertCell(3);
	//createCell(cell2, index);
	cell5.setAttribute("align", "left");
	cell5.setAttribute("class", "borderBR padl5");
	cell5.setAttribute("width", "15%");

	var tmpSDate = eval('document.frmProdKitInfo.txtSDate');
	var startDate = document.createElement("input");
	startDate.type = "text";
	startDate.setAttribute("class", "txtfieldLabel");
	startDate.setAttribute("readonly", "yes");
	startDate.setAttribute("id", "startDate" + index);
	startDate.setAttribute("name", "startDate" + index);
	startDate.setAttribute("size", "20");
	startDate.setAttribute("value", tmpSDate.value);
	cell5.appendChild(startDate);

	var hDStart = document.createElement("input");
	hDStart.type = "hidden";
	hDStart.setAttribute("id", "hDStart" + index);
	hDStart.setAttribute("name", "hDStart" + index);
	hDStart.setAttribute("style", "text-align:right");
	hDStart.setAttribute("value",tmpSDate.value );
	cell5.appendChild(hDStart);

	var cell6 = row.insertCell(4);
	//createCell(cell2, index);
	cell6.setAttribute("align", "left");
	cell6.setAttribute("class", "borderBR padl5");
	cell6.setAttribute("width", "15%");

	var tmpEDate = eval('document.frmProdKitInfo.txtEDate');
	var endDate = document.createElement("input");
	endDate.type = "text";
	endDate.setAttribute("class", "txtfieldLabel");
	endDate.setAttribute("readonly", "yes");
	endDate.setAttribute("id", "endDate" + index);
	endDate.setAttribute("name", "endDate" + index);
	endDate.setAttribute("size", "20");
	endDate.setAttribute("value", tmpEDate.value);
	cell6.appendChild(endDate);

	var hDEnd = document.createElement("input");
	hDEnd.type = "hidden";
	hDEnd.setAttribute("id", "hDEnd" + index);
	hDEnd.setAttribute("name", "hDEnd" + index);
	hDEnd.setAttribute("style", "text-align:right");
	hDEnd.setAttribute("value",tmpEDate.value );
	cell6.appendChild(hDEnd);

	var cell7 = row.insertCell(5);
	//createCell(cell2, index);
	cell7.setAttribute("align", "center");
	cell7.setAttribute("class", "borderBR");
	cell7.setAttribute("width", "10%");

	var tmpQty = eval('document.frmProdKitInfo.txtQty');
	var Qty = document.createElement("input");
	Qty.type = "text";
	Qty.setAttribute("class", "txtfield5");
	Qty.setAttribute("id", "txtComponentQty" + index);
	Qty.setAttribute("name", "txtComponentQty" + index);
	Qty.setAttribute("style", "text-align:right");
	Qty.setAttribute("value",tmpQty.value );
	cell7.appendChild(Qty);

	var currowCount = table.rows.length;
	var hrowCount = eval('document.frmProdKitInfo.hcnt');
	hrowCount.value = currowCount;
}

function checkAll(bin)
{
	var elms = document.frmProdKitInfo.elements;

	for (var i = 0; i < elms.length; i++)
	  if (elms[i].name == 'chkInclude[]')
	  {
		  elms[i].checked = bin;
	  }
}

function rmrow()
{
	var table = document.getElementById('dynamicTable');
	var rowCount = table.rows.length;
	var ml = document.frmProdKitInfo;
	var len = ml.elements.length;
	var currRow = 0;
	var checker = 0;
	//alert(len);
	if(document.frmProdKitInfo.chkIncludeAll.checked == true){
		for(i = len; i > 0; i--)
		{

			var e = ml.elements[i];

			if(e)
			{

				if(e.name == "chkInclude[]")
				{
					if(e.checked == true)
					{
						if(checker == 0)
						{
							currRow = e.parentNode.parentNode.rowIndex ;

						}
						else
						{
							currRow = (e.parentNode.parentNode.rowIndex) - checker;
						}
						//alert(e.parentNode.parentNode.rowIndex);
						//alert(currRow);
						table.deleteRow(0);
						//return false;
						//fixColCount(currRow);
						checker++;
					}
				}
			}

		}
	}else{
		checker = len;
		for(i = len; i >= 0; i--)
		{

			var e = ml.elements[i];
			//alert(i);
			if(e)
			{

				if(e.name == "chkInclude[]")
				{
					//alert(i);
					if(e.checked == true)
					{
						if(checker == 58)
						{
							currRow = e.parentNode.parentNode.rowIndex ;

						}
						else
						{

							currRow = (e.parentNode.parentNode.rowIndex);
						}

						//alert(e.parentNode.parentNode.rowIndex);
						//alert(currRow+"tesr");
						//alert(currRow);
						table.deleteRow(currRow);
						//i = i - 6;
						//return false;
						//fixColCount(currRow);
						checker--;
					}
				}
			}

		}
	}
	
	if($j('#dynamicTable').find('tr').length == 0){
		$j('#dynamicTable').append("<tr align='center'><td colspan='6' height='20'>No result found.</td></tr>");
	}

}

function checker()
{
	var ml = document.frmProdKitInfo;
	var len = ml.elements.length;

	for (var i = 0; i < len; i++)
	{
		var e = ml.elements[i];
	    if (e.name == "chkInclude[]" && e.checked == true)
	    {
			return true;
	    }
	}
	return false;
}

function confirmRemove()
{
	var title = 'Product Kit';
	var message = '';
	var buttonFunction = {};
	
	if (!checker()){
		message = "Please select product(s) to be removed.";
		buttonFunction['Ok'] = function(){
			$j('#dialog-message').dialog('close');
		}
		popinmessage(title, message, buttonFunction);
		return false;
	}
	else
	{
		
		message = "Are you sure you want to remove component(s)?";
		buttonFunction['Yes'] = function(){
			$j('#dialog-message').dialog('close');
			rmrow();
		}
		
		buttonFunction['No'] = function(){
			$j('#dialog-message').dialog('close');
		}
		popinmessage(title, message, buttonFunction);
		return false;		
	}
}

function fixColCount(currRow)
{
	var table = document.getElementById('dynamicTable');
	var rowCount = table.rows.length;
	var newRow = currRow + 1;

	for(j = newRow ; j <= rowCount ; j++)
	{
		var h = j - 1;
		var componetQty = eval('document.frmProdKitInfo.txtComponentQty'+ j);
		var hProdID = eval('document.frmProdKitInfo.hProdID'+ j);

		hProdID.setAttribute("name", "hProdID" + h);
		hProdID.setAttribute("id", "hProdID" + h);
		componetQty.setAttribute("name", "txtComponentQty" + h);
		componetQty.setAttribute("id", "txtComponentQty" + h);
	}
}

function popinmessage(title, message, buttonFunction){
	$j( "#dialog-message p" ).html(message);
    $j( "#dialog-message" ).dialog({
        autoOpen: false,
        modal: true,
        position: 'center',
        height: 'auto',
        width: 'auto',
        resizable: false,
		draggable: false,
        title: title,
        buttons: buttonFunction
    });
    $j( "#dialog-message" ).dialog( "open" );
}
