var $j=jQuery.noConflict();
//new for fuller
window.onload= function(){
	
//	shortcut("F3",function() {
//		computebestprice(1);
//	});
//	shortcut("F4",function() {
//		applicablepromo();
//	});
	
	shortcut("F3",function() {
		computebestprice(1);
//		shortcut("F8",function() {
//			 incentives();
//			 shortcut("F3",function() {
//				
//			});
//		});
	});
	
	shortcut("F4",function() {
		applicablepromo();
	});
	
};
var objWin;
var flagincentives = 0;

function incentives()
{ 
	//alert(flagincentives);
	if(flagincentives == 0)
	{
		
		flagincentives = 1;
	}
	 shortcut("F3",function() {
		 removecalculate();
		});
	$j('#incentive').dialog('open');
	return false;
	
     
}
function removecalculate()
{
	
}
function applicablepromo()
{
	pagetoprint = "pages/sales/sales_promopopup.php" ;
	if (!objWin) 
	{			
		objWin = NewWindow(pagetoprint,'printps','980','600','yes');
	}
	objWin.focus();
	return false;
}

function changepromo(elemid, productid, promoid)
{
	pagetoprint = "pages/sales/sales_changepromopopup.php?pid=" + productid + "&prid=" + promoid + "&elemid=" + elemid;
	if (!objWin) 
	{			
		objWin = NewWindow(pagetoprint,'printps','980','600','yes');
	}
	objWin.focus();
	return false;
}


function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function computebestprice(refreshProductlist)
{
	custID 	 = eval('document.frmCreateSalesOrder.hCOA');	
	custCode = eval('document.frmCreateSalesOrder.txtCustomer');	
	txnid	 = eval('document.frmCreateSalesOrder.hTxnID');
	if(custID.value == '')
	{
		alert("Please select customer.");
		custCode.focus();
		return false;
	}
	$j.ajax({
                   type: 'POST',
                   url: 'includes/jxCalculatePriceUnconfirmedSO.php?mode=post&txnid='+txnid.value +'&refresh=' + refreshProductlist + '&custID=' + custID.value,
                   data: $j('#frmCreateSalesOrder').serialize(),
                   success: function(test){
					 	//alert(test);
					   $j('#prodlist').load('includes/jxCalculatePriceUnconfirmedSO.php','mode=get&refresh=' + refreshProductlist + '&custID=' + custID.value,function(){ customerdetails(); calculatetotals(); //customerdetails();		 validateSave();													  
						 																  });
					   },
                   dataType: 'text'
                 });
}
function customerdetails() {
	$j.ajax({
           type: 'POST',
           url: 'includes/jxCustomerDetailsUnconfirmedSO.php?mode=post',
           data: $j('#frmCreateSalesOrder').serialize(),
           success: function(outCustomerDetails) {
           					   
  					var creditLimit=eval('document.frmCreateSalesOrder.txtCLimit');
						var ARBalance=eval('document.frmCreateSalesOrder.txtARBalance');
						var Penalty=eval('document.frmCreateSalesOrder.txtPenalty');
						var AvalableCredit=eval('document.frmCreateSalesOrder.txtAvailableCredit');
						var currentNCFT=eval('document.frmCreateSalesOrder.totNCFT');
						var currentCFT=eval('document.frmCreateSalesOrder.totCFT');	            							
						var isEmployee=eval('document.frmCreateSalesOrder.isEmployee');	
						var MTDNCFT=document.getElementById('MTDNCFT');
						var MTDCFT=document.getElementById('MTDCFT');
						var YTDCFT=document.getElementById('YTDCFT');
						var YTDNCFT=document.getElementById('YTDNCFT');
						var IBM=document.getElementById('IBM');
						var customerstatus=eval('document.frmCreateSalesOrder.hCustomerStatus');	
						
						tmp_val=outCustomerDetails.split("_");
                                    
						tmpcurrentNCFT=replaceAll(currentNCFT.value, "," ,""); 
						tmpcurrentCFT=replaceAll(currentCFT.value, "," ,""); 
						tmpcurrentNCFT=(tmpcurrentNCFT*100)/100;
						tmpcurrentCFT=(tmpcurrentCFT*100)/100;
             						 												
						tmpCreditLimit=(tmp_val[0]*100)/100;				            
            tmpARBalance=(tmp_val[13]*100)/100;            
						tmpPenalty=(tmp_val[2]*100)/100;	
						tmpMTDNCFT=(tmp_val[3]*100)/100;            	
						tmpMTDCFT=(tmp_val[4]*100)/100;
            tmpMTDNCFT=tmpMTDNCFT+tmpcurrentNCFT;
						tmpMTDCFT=tmpMTDCFT+tmpcurrentCFT;																			
						tmpYTDCFT=(tmp_val[5]*100)/100;	
						tmpYTDNCFT=(tmp_val[6]*100)/100;	
						tmpYTDNCFT=tmpYTDNCFT+tmpcurrentNCFT;
						tmpYTDCFT=tmpYTDCFT+tmpcurrentCFT;
            
            tmpAvalableCredit=(tmp_val[14]*100)/100;														
						            
						creditLimit.value=CommaFormatted(tmpCreditLimit.toFixed(2));
						ARBalance.value=CommaFormatted(tmpARBalance.toFixed(2));					
					  Penalty.value=CommaFormatted(tmpPenalty.toFixed(2));						
						AvalableCredit.value=CommaFormatted(tmpAvalableCredit.toFixed(2));					
						MTDNCFT.innerHTML=CommaFormatted(tmpMTDNCFT.toFixed(2));
						MTDCFT.innerHTML=CommaFormatted(tmpMTDCFT.toFixed(2));
						YTDCFT.innerHTML=CommaFormatted(tmpYTDCFT.toFixed(2));
						YTDNCFT.innerHTML=CommaFormatted(tmpYTDNCFT.toFixed(2));
						IBM.innerHTML=tmp_val[7];
						isEmployee.value=tmp_val[8];
						customerstatus.value=tmp_val[10];
					 }, dataType: 'text' });
}
function computeAddtlDiscount()
{
	$j.ajax({
		   type: 'POST',
		   url: 'includes/jxCalculateAddtlDiscSO.php',
		   data: $j('#frmCreateSalesOrder').serialize(),
		   success: function(outAddtlDisc){
			   var grossamount  	= eval('document.frmCreateSalesOrder.txtGrossAmt');
			   	var AddtlDisc 	 	= eval('document.frmCreateSalesOrder.txtAddtlDisc');
			   	var ADPP 			= eval('document.frmCreateSalesOrder.txtADPP');
			  	var CSPLessCPI 	 	= eval('document.frmCreateSalesOrder.txtCSPLessCPI');
				var BasicDiscount 	= eval('document.frmCreateSalesOrder.txtBasicDiscount');
				var SalesWVat		= eval('document.frmCreateSalesOrder.txtSalesWVat');
				var AmtWOVat		= eval('document.frmCreateSalesOrder.txtAmtWOVat'); 
				var VatAmt			= eval('document.frmCreateSalesOrder.txtVatAmt'); 
				var NetAmt			= eval('document.frmCreateSalesOrder.txtNetAmt');  
				var AddtlDisc 	 	= eval('document.frmCreateSalesOrder.txtAddtlDisc');
				var nextDiscCFT		= document.getElementById('nextdiscCFT');
				var nextDiscNCFT	= document.getElementById('nextdiscNCFT');
				var ADPP 			= eval('document.frmCreateSalesOrder.txtADPP');
				var isEmployee 		= eval('document.frmCreateSalesOrder.isEmployee');
				tmp_val = outAddtlDisc.split("_");
				AddtlDisc.value 	= tmp_val[0];
				ADPP.value 	   		= tmp_val[1];
				tmpCSPLessCPI		= replaceAll(grossamount.value,",","");
				tmpBasicDiscount 	= replaceAll(BasicDiscount.value,",","");
				tmpAddtlDisc		= replaceAll(AddtlDisc.value,",","");
				tmpADPP				= replaceAll(ADPP.value,",","");
				if(isEmployee.value == 1)
				{
					tmpAddtlDisc		= 0;
					tmpADPP				= 0 ;
				}
				tmpSalesWVat		= tmpCSPLessCPI - tmpBasicDiscount - tmpAddtlDisc;
				tmpAmtWOVat 		= tmpSalesWVat/1.12 ;
				tmpVatAmt			= tmpAmtWOVat * 0.12;	
				tmpnextDiscCFT		= (tmp_val[2]*100)/100;
				tmpnextDiscNCFT		= (tmp_val[3]*100)/100;
				tmpNetAmt			= tmpSalesWVat	 - tmpADPP;
				SalesWVat.value 	= CommaFormatted(tmpSalesWVat.toFixed(2));
				AmtWOVat.value		= CommaFormatted(tmpAmtWOVat.toFixed(2));
				VatAmt.value		= CommaFormatted(tmpVatAmt.toFixed(2));
				NetAmt.value		= CommaFormatted(tmpNetAmt.toFixed(2));
				if(tmpnextDiscCFT < 0)
				{
					tmpnextDiscCFT = 0;
				}
				if(tmpnextDiscNCFT < 0)
				{
					tmpnextDiscNCFT = 0;
				}
				
				nextDiscCFT.innerHTML  = CommaFormatted(tmpnextDiscCFT.toFixed(2));
				nextDiscNCFT.innerHTML = CommaFormatted(tmpnextDiscNCFT.toFixed(2));
			   	if(isEmployee.value == 1)
				{
					ADPP.value = "0.00" ;
					AddtlDisc.value = "0.00";
					nextDiscCFT.innerHTML= "0.00";
					nextDiscNCFT.innerHTML = "0.00";
				}
			   	customerdetails();
			   
			   },
		   dataType: 'text'
		 });
	
}

function unlock_trans(id, table)
{
	
 if(document.getElementById('hdncnt').value != 1)
 {

  $j.ajax({
      type: 'POST',
      url: 'includes/scUnlocktransaction.php',
      data: {
  		txnId: id, 
  		table: table
      }    
    });  
	
		return false;
 }
 
 document.getElementById('hdncnt').value = 0;
	
}	


function getSelectionCOAID(text,li) {
	//alert (text);	
	//d = document.form;
	
	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmCreateSalesOrder.hCOA');
	h.value = tmp_val[0];	
	i = eval('document.frmCreateSalesOrder.txtCustomer');	
	j = eval('document.frmCreateSalesOrder.txtCustomerName');
	
	i.value = tmp_val[2];
	j.value = tmp_val[1];
	customerdetails();
	//i.focus();
	//i.select();
}

function getSelectionCOAID2(text,li) {
	//alert (text);	
	//d = document.form;
	
	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmDealer.hCOA');
	h.value = tmp_val[0];	
	i = eval('document.frmDealer.txtIBMNo');	
	j = eval('document.frmDealer.txtibmname');
	
	i.value = tmp_val[2];
	j.value = tmp_val[1];
	
	//i.focus();
	//i.select();
}

function getSelectionCOAID3(text,li) {
	//alert (text);	
	//d = document.form;
	
	tmp = li.id;
	tmp_val = tmp.split("_");
	h = eval('document.frmDealer.hdnRec');
	h.value = tmp_val[0];	
	i = eval('document.frmDealer.txtRecruiteAcctNo');	
	j = eval('document.frmDealer.txtRecruiteName');
	k = eval('document.frmDealer.txtRecruiterCP');	
	
	k.value = tmp_val[3];
	i.value = tmp_val[2];
	j.value = tmp_val[1];
	
	//i.focus();
	//i.select();
}

function getSelectionProductList(text, li) 
{
	var txt = text.id;
	var ctr = txt.substr(11, txt.length);

	tmp = li.id;
	if(tmp != 0)
	{
	tmp_val = tmp.split("_");
	h = eval('document.frmCreateSalesOrder.hProdID' + ctr);
	i = eval('document.frmCreateSalesOrder.txtProdCode' + ctr);
	j = eval('document.frmCreateSalesOrder.txtProdDesc' + ctr);
	k = eval('document.frmCreateSalesOrder.txtUnitPrice' + ctr);
	l = eval('document.frmCreateSalesOrder.txtOrderedQty' + ctr);
	n = eval('document.frmCreateSalesOrder.txtEffectivePrice' + ctr);
	tmpSOH = 'divSOH' + ctr;
	tmpTransit = 'divTransit' + ctr;
	var SOH	= document.getElementById(tmpSOH);
	var Transit	= document.getElementById(tmpTransit);
	pmg =  eval('document.frmCreateSalesOrder.txtPMG' + ctr);
	pmgid =  eval('document.frmCreateSalesOrder.hPMGID' + ctr);
	producttype =  eval('document.frmCreateSalesOrder.hProductType' + ctr);
	m = eval('document.frmCreateSalesOrder.hcnt');
	
	hSOH =  eval('document.frmCreateSalesOrder.hSOH' + ctr);
	hTransit =  eval('document.frmCreateSalesOrder.hTransit' + ctr);
	//k.focus();
	//k.select();
	//alert(tmp_val[0]);
	h.value = tmp_val[0];
	hSOH.value = tmp_val[6];
	hTransit.value = tmp_val[7];
	j.value = tmp_val[1];
	k.value = tmp_val[2];
	n.value = tmp_val[2];
	pmg.value = tmp_val[3];
	pmgid.value = tmp_val[4];
	producttype.value = tmp_val[5];
	SOH.innerHTML 	= tmp_val[6];
	Transit.innerHTML 	= tmp_val[7];
	i.value = tmp_val[9];
	l.value = 1;
	m.value = ctr;
	
	if(tmp_val[8] == 1)
	{
	 SOH.setAttribute('onclick', 'substituteItem('+h.value+','+ctr+')');
	 SOH.setAttribute('onMouseover', 'this.style.cursor = "pointer"');
	 SOH.style.color = "blue";
	 SOH.style.fontWeight  = "bold";
	}
		
	addRow() ;
	}
	else
	{
			return false;
	}
	 
}


function addRow() 
{		 
	var table = document.getElementById('dynamicTable');
	var rowCount = table.rows.length;
	//alert(rowCount + 'b');
	var row = table.insertRow(rowCount);
	var index = eval(rowCount + 1);
	var cnt = eval('document.frmCreateSalesOrder.hcnt');
	cnt.value =  index;
	var txtboxclass;
	
	//row class
	if(index % 2 != 0)
	{
		row.setAttribute("class", "");
		txtboxclass = "txtfieldItemLabel1";
	}
	else
	{
		row.setAttribute("class", "bgEFF0EB");
		txtboxclass = "txtfieldItemLabel2";
	}
	//elements


	var cell2 = row.insertCell(0);
	createCell(cell2, index);
	cell2.setAttribute("align", "left");
	cell2.setAttribute("class", "borderBR padl5");

	
	//item code
	var cell3 = row.insertCell(1);
	cell3.setAttribute("align", "left");
	cell3.setAttribute("class", "borderBR padl5");
	
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
	
	//div
	element1d.setAttribute("id", "prod_choices" + index);
	element1d.setAttribute("name", "prod_choices" + index);
	element1d.setAttribute("class", "autocomplete");
	element1d.setAttribute("style", "display: none");
	
	element1.type = "input";
	element1.setAttribute("class", "txtfield");
	element1.setAttribute("id", "txtProdCode" + index);
	element1.setAttribute("name", "txtProdCode" + index);
	element1.setAttribute("style", "width: 75px");
	element1.setAttribute("onKeyPress","return disableEnterKey(event)");
	
	
	cell3.appendChild(element1);
	cell3.appendChild(element1h);
	cell3.appendChild(element1s);
	cell3.appendChild(element1d);
	
	//item description
	var cell4 = row.insertCell(2);
	cell4.setAttribute("align", "left");
	cell4.setAttribute("class", "borderBR padl5");
	
	var element2 = document.createElement("input");
	element2.type = "text";
	element2.setAttribute("class", "txtfield");
	element2.setAttribute("style", "width: 220px");
	element2.setAttribute("readonly", "yes");
	element2.setAttribute("id", "txtProdDesc" + index);
	element2.setAttribute("name", "txtProdDesc" + index);
	cell4.appendChild(element2);
	
	//UOM
	var cell5 = row.insertCell(3);
	createCell(cell5, 'Piece');
	cell5.setAttribute("align", "center");
	cell5.setAttribute("class", "borderBR padl5");
	
	//PMG		
	var cell6 = row.insertCell(4);	
	cell6.setAttribute("align", "center");
	cell6.setAttribute("class", "borderBR padl5");
	
	var element7 = document.createElement("input");
	element7.type = "text";
	element7.setAttribute("class", txtboxclass);	
	element7.setAttribute("readonly", "yes");
	element7.setAttribute("id", "txtPMG" + index);
	element7.setAttribute("name", "txtPMG" + index);
	element7.setAttribute("style", "text-align:center");
	
	
	var element8 = document.createElement("input");
	element8.type = "hidden";	
	element8.setAttribute("id", "hPMGID" + index);
	element8.setAttribute("name", "hPMGID" + index);
	
	var element9 = document.createElement("input");
	element9.type = "hidden";	
	element9.setAttribute("id", "hProductType" + index);
	element9.setAttribute("name", "hProductType" + index);
	
	
	cell6.appendChild(element7);
	cell6.appendChild(element8);
	cell6.appendChild(element9);
	
	//unitprice
	var cell7 = row.insertCell(5);
	cell7.setAttribute("align", "center");
	cell7.setAttribute("class", "borderBR padl5");
	
	var element3 = document.createElement("input");
	element3.type = "text";
	element3.setAttribute("class", txtboxclass);	
	element3.setAttribute("readonly", "yes");
	element3.setAttribute("id", "txtUnitPrice" + index);
	element3.setAttribute("name", "txtUnitPrice" + index);
	element3.setAttribute("style", "text-align:right");
	cell7.appendChild(element3);
	
	//Promo		
	var cell8 = row.insertCell(6);
	createCell(cell8,'');
	cell8.setAttribute("align", "center");
	cell8.setAttribute("class", "borderBR padl5");
	
	var element10 = document.createElement("input");
	element10.type = "hidden";	
	element10.setAttribute("id", "hPromoID" + index);
	element10.setAttribute("name", "hPromoID" + index);
	
	var element11 = document.createElement("input");
	element11.type = "text";	
	element11.setAttribute("id", "hPromoType" + index);
	element11.setAttribute("name", "hPromoType" + index);
	
	var element11Incentive = document.createElement("input");
	element11Incentive.type = "hidden";	
	element11Incentive.setAttribute("id", "hForIncentive" + index);
	element11Incentive.setAttribute("name", "hForIncentive" + index);
	element11Incentive.setAttribute("value", "0");
	
	cell8.appendChild(element10);
	cell8.appendChild(element11);
	cell8.appendChild(element11Incentive);
	
	
	//SOH		
	var cell9 = row.insertCell(7);
	createCell(cell9,'');
	cell9.setAttribute("align", "center");
	cell9.setAttribute("class", "borderBR padl5");
	var element2d = document.createElement("div");	
	element2d.setAttribute("id", "divSOH" + index);
	element2d.setAttribute("name", "divSOH" + index);	
	var element12 = document.createElement("input");
	element12.type = "hidden";	
	element12.setAttribute("id", "hSOH" + index);
	element12.setAttribute("name", "hSOH" + index);
	cell9.appendChild(element2d);
	cell9.appendChild(element12);
	
	//Allocated QTY		
	var cell10 = row.insertCell(8);
	createCell(cell10,'');
	cell10.setAttribute("align", "center");
	cell10.setAttribute("class", "borderBR padl5");
	var element3d = document.createElement("div");	
	element3d.setAttribute("id", "divTransit" + index);
	element3d.setAttribute("name", "divTransit" + index);	
	var element13 = document.createElement("input");
	element13.type = "hidden";	
	element13.setAttribute("id", "hTransit" + index);
	element13	.setAttribute("name", "hTransit" + index);
	
	cell10.appendChild(element13);
	cell10.appendChild(element3d);
	
	//ordered QTY
	var cell11 = row.insertCell(9);
	cell11.setAttribute("align", "center");
	cell11.setAttribute("class", "borderBR padl5");
	
	var element4 = document.createElement("input");
	element4.type = "text";
	element4.setAttribute("class", "txtfield3");	
	element4.setAttribute("id", "txtOrderedQty" + index);
	element4.setAttribute("name", "txtOrderedQty" + index);
	element4.setAttribute("style", "text-align:right");
	
	var element14 = document.createElement("input");
	element14.type = "hidden";	
	element14.setAttribute("id", "hServed" + index);
	element14	.setAttribute("name", "hServed" + index);
	
	cell11.appendChild(element4);
	cell11.appendChild(element14);
	
	//Effective	 Price
	var cell12 = row.insertCell(10);
	cell12.setAttribute("align", "center");
	cell12.setAttribute("class", "borderBR padl5");
	
	var element5 = document.createElement("input");
	element5.type = "text";
	element5.setAttribute("class", txtboxclass);	
	element5.setAttribute("readonly", "yes");
	element5.setAttribute("id", "txtEffectivePrice" + index);
	element5.setAttribute("name", "txtEffectivePrice" + index);
	element5.setAttribute("style", "text-align:right");
	cell12.appendChild(element5);
	
	//Effective	 
	var cell13 = row.insertCell(11);
	cell13.setAttribute("align", "center");
	cell13.setAttribute("class", "borderBR padl5");
	
	var element6 = document.createElement("input");
	element6.type = "text";
	element6.setAttribute("class", txtboxclass);	
	element6.setAttribute("readonly", "yes");
	element6.setAttribute("id", "txtTotalPrice" + index);
	element6.setAttribute("name", "txtTotalPrice" + index);
	element5.setAttribute("style", "text-align:right");
	cell13.appendChild(element6);
	
	
	var url = 'includes/scProductListAjaxSO.php?index=' + index;
	var prod_choices = new Ajax.Autocompleter(element1.id, element1d.id, url , {afterUpdateElement : getSelectionProductList, indicator: element1s.id});
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
function substituteItem(prodID,index)
{ 
		
	var productID	= document.getElementById('hProdIDSubstitute');
	productID.value = prodID+'_'+index;	
	$j('#substituteItem').dialog('open');
	return false;
     
}
function validateSave()
{
	
	penalty			= eval('document.frmCreateSalesOrder.txtPenalty');	
	netamt			= eval('document.frmCreateSalesOrder.txtNetAmt');
	availablecredit	= eval('document.frmCreateSalesOrder.txtAvailableCredit');
	grossamt		= eval('document.frmCreateSalesOrder.txtGrossAmt');
	
	if(replaceAll(grossamt.value,",","") == '')
	{
			alert("Please calculate net amounts first by pressing F3.");
			return false;
			
	}
	
	if(replaceAll(penalty.value,",","") != 0.00)
	{
		
		if(replaceAll(netamt.value,",","") > 500)
		{			
			alert("Customer selected has insufficient credit limit and/or unpaid penalties.");
			return false;
		}
	}
	
	if(replaceAll(availablecredit.value,",","") == 0.00)
	{
		
		if(netamt.value > 500 )
		{			
			alert("Customer selected has insufficient credit limit and/or unpaid penalties.");
			return false;
		}
		
	}
	

	if(eval(replaceAll(netamt.value,",","")) > eval(replaceAll(availablecredit.value,",","")))
	{	
				if( replaceAll(netamt.value,",","")> 500.00 )
				{
				alert("Customer selected has insufficient credit limit and/or unpaid penalties.");
				return false;
				}
	}
		
	}

//substitute pop-up

$j().ready(function() {
	

    $j('#substituteItem')
    .dialog({
    autoOpen: false,
    height: 350,
    width: 500,
    resizable: false,
    modal: true,           
    open: function() {
		var $jprodID = $j('#hProdIDSubstitute').val();
		 var $jurlString = "pid=" + $jprodID;
		
		 $j('#substitutetable').load('includes/jxSubstituteItemSO.php',$jurlString ,function(){ 															  
						 																  });		
    }                  
 });


});

function calculatetotals()
{
	var table = document.getElementById('dynamicTable');
	var rowCount = table.rows.length;
	var row = table.insertRow(rowCount);
	var index = eval(rowCount);
	var cnt = eval('document.frmCreateSalesOrder.hcnt');
	cnt.value =  index;
	if(!cnt)
	{
		alert("Product is required.");	
		window.location.reload();
		return false;
	}
	ctr = cnt.value - 1 ;
	var tmpgross 	= 0;
	var tmptotCFT 	= 0;
	var tmptotNCFT 	= 0;
	var tmptotCPI 	= 0;
	
	var grossamount  	= eval('document.frmCreateSalesOrder.txtGrossAmt');
	var totalCFT 		= eval('document.frmCreateSalesOrder.totCFT');
	var totalNCFT 		= eval('document.frmCreateSalesOrder.totNCFT');
	var totalCPI		= document.getElementById('boldStuff');
	var CSPLessCPI 	 	= eval('document.frmCreateSalesOrder.txtCSPLessCPI');
	var BasicDiscount 	= eval('document.frmCreateSalesOrder.txtBasicDiscount');
	var SalesWVat		= eval('document.frmCreateSalesOrder.txtSalesWVat');
	var AmtWOVat		= eval('document.frmCreateSalesOrder.txtAmtWOVat'); 
	var VatAmt			= eval('document.frmCreateSalesOrder.txtVatAmt'); 
	var NetAmt			= eval('document.frmCreateSalesOrder.txtNetAmt');  
	var AddtlDisc 	 	= eval('document.frmCreateSalesOrder.txtAddtlDisc');
	var ADPP 			= eval('document.frmCreateSalesOrder.txtADPP');
	var totCPI 			= eval('document.frmCreateSalesOrder.totCPI');
	var isEmployee 		= eval('document.frmCreateSalesOrder.isEmployee');
	
	
	
	for( var i=1; i <= ctr ; i++)
	{
		effective = eval('document.frmCreateSalesOrder.txtTotalPrice' + i);
		var tmpeffective = replaceAll(effective.value,",","");
		pmg = eval('document.frmCreateSalesOrder.txtPMG' + i);
		
		if(pmg.value == "CFT")
		{
			tmptotCFT = eval(tmptotCFT)  +   eval(tmpeffective) ;
		}
		
		if(pmg.value == "NCFT")
		{
			tmptotNCFT = eval(tmptotNCFT)  +   eval(tmpeffective) ;
		}
		if(pmg.value == "CPI")
		{
			tmptotCPI = eval(tmptotCPI)  +   eval(tmpeffective) ;
		}
		
		
		
		tmpgross = eval(tmpeffective) + eval(tmpgross);	
		
	}
	
	grossamount.value 	= tmpgross.toFixed(2) ;
	
	if(isEmployee.value == 0)
	{
		tmpCSPLessCPI	 	= grossamount.value - tmptotCPI ;
		tmpBasicDiscount	= tmpCSPLessCPI * 0.25 ;
		tmptotCFTless		= tmptotCFT * 0.75;
		tmptotNCFTless		= tmptotNCFT * 0.75;
	}
	else
	{
		tmptotCFTless		= tmptotCFT * 0.50;
		tmptotNCFTless		= tmptotNCFT * 0.55;		
		tmpCSPLessCPI	 	= grossamount.value - tmptotCPI ;
		tmpBasicDiscount	= (tmptotCFT - tmptotCFTless ) + (tmptotNCFT - tmptotNCFTless )  ;
		
	}
	
	totalCFT.value 		= CommaFormatted(tmptotCFTless.toFixed(2));
	totalNCFT.value 	= CommaFormatted(tmptotNCFTless.toFixed(2));
	totalCPI.innerHTML 	= CommaFormatted(tmptotCPI.toFixed(2));
	totCPI.value		= CommaFormatted(tmptotCPI.toFixed(2));
	CSPLessCPI.value	= CommaFormatted(tmpCSPLessCPI.toFixed(2));
	BasicDiscount.value = CommaFormatted(tmpBasicDiscount.toFixed(2));
	grossamount.value 	= CommaFormatted(tmpgross.toFixed(2));
	computeAddtlDiscount();
	
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

     return (key!=13);
}
//incentive pop-up
$j().ready(function() {
	

    $j('#incentive')
    .dialog({
    autoOpen: false,
    height: 600,
    width: 1050,
    resizable: false,
    modal: true,           
    open: function() {		
		 $j('#incentivetable').load('includes/jxincentives.php?flag='+ flagincentives,function(){			 
		  });		
    }                  
 });


}); 