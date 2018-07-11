/*   
  @modified by John Paul Pineda
  @date May 21, 2013
  @email paulpineda19@yahoo.com         
*/

$tpi=jQuery.noConflict();

// New for fuller
window.onload=function() {
	
  shortcut("F2",function() {
    
    $tpi("#tpi_dss_applied_promos, #tpi_dss_applied_incentives").toggle('slow');		
	});
  
	shortcut("F3",function() {
  	
		computebestprice(1);
		shortcut("F8",function() {
    
			 incentives();
			 shortcut("F3",function() {
				
			});
		});
	});
	
	shortcut("F4",function() {
  
		applicablepromo();
	});	
};

var objWin;
var flagincentives=0;

function chkAdvPO() {

	var adv=eval('document.frmCreateSalesOrder.hisAdvance');

	if(adv.value == 1) {
		
		var flag=eval('document.frmCreateSalesOrder.hApprvAdvPO');
		if(flag.value ==0) {
    
			alert("Can't create Advance PO. Advance PO period has not started. ");
			history.go(-1);
			//window.location.reload();			
		}
	}
  
	shortcut("F3",function() {
  		
		computebestprice(1);
    
		shortcut("F8",function() {
    
			 incentives();
			 shortcut("F3",function() {
				
			});
		});
	});
	
	shortcut("F4",function() {
  
		applicablepromo();
	});	
}

function applicablepromo() {

	pagetoprint = "pages/sales/sales_promopopup.php" ;
	if(!objWin) {
  			
		objWin=NewWindow(pagetoprint,'printps','980','600','yes');
	}
	objWin.focus();
	return false;
}

function changepromo(elemid, productid, promoid) {

	pagetoprint = "pages/sales/sales_changepromopopup.php?pid=" + productid + "&prid=" + promoid + "&elemid=" + elemid;
	if (!objWin) {
  			
	 objWin=NewWindow(pagetoprint,'printps','980','600','yes');
	}
	objWin.focus();
	return false;
}

function NewWindow(mypage, myname, w, h, scroll) {

	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}

function computebestprice(refreshProductlist) {

  custID=eval('document.frmCreateSalesOrder.hCOA');	
  custCode=eval('document.frmCreateSalesOrder.txtCustomer');	
  adv=eval('document.frmCreateSalesOrder.hisAdvance');
  txnid=eval('document.frmCreateSalesOrder.hTxnID');
  backOrder=eval('document.frmCreateSalesOrder.hBackOrder');
  txnID=eval('document.frmCreateSalesOrder.hTxnID');
  
	if(custID.value=='') {
  
		alert("Please select customer.");
		custCode.focus();
		return false;
	}
  
  number_of_unique_products=parseInt(document.getElementById('hcnt').value)-1;
    
  for(x=1;x<=number_of_unique_products;x++) {
    
    document.getElementById('divPromo'+x).innerHTML='';
    document.getElementById('hPromoID'+x).value='';
    document.getElementById('hPromoType'+x).value='';
    document.getElementById('txtEffectivePrice'+x).value=document.getElementById('txtUnitPrice'+x).value;  
  } 
    
	$tpi.ajax({
  
   type:'POST', 
   async:false,  
   url:'includes/jxCalculatePriceSO.php?mode=post&adv='+adv.value+'&txnid='+txnid.value+'&refresh='+refreshProductlist+'&custID='+custID.value+'&backOrder='+backOrder.value+'&txnID='+txnID.value,      
   data: $tpi("#frmCreateSalesOrder").serialize(),
   success: function(test) {
  	 	
    $tpi("#prodlist").load('includes/jxCalculatePriceSO.php', 'mode=get&refresh='+refreshProductlist+'&custID='+custID.value+'&backOrder='+backOrder.value, function() { 
      
      get_applicable_promos();                      					 													
      calculatetotals();
      
      if(document.getElementById('applied_promos_application_functions'))
      reapply_previously_applied_promos_entitlements(document.getElementById('applied_promos_application_functions').value);
      
      get_applicable_incentives();
      
      $tpi("#txtProdCode"+$tpi("#hcnt").val()).focus();            															
    });
   }, dataType:'text' 
  });            
}

function customerdetails() {

  $tpi.ajax({
  
           type: 'POST',
           url: 'includes/jxCustomerDetailsSO.php?mode=post',
           data: $tpi("#frmCreateSalesOrder").serialize(),
           success: function(outCustomerDetails) {
					 					  					 
					  var creditLimit=eval('document.frmCreateSalesOrder.txtCLimit');
						var ARBalance=eval('document.frmCreateSalesOrder.txtARBalance');
						var Penalty=eval('document.frmCreateSalesOrder.txtPenalty');
						var AvalableCredit=eval('document.frmCreateSalesOrder.txtAvailableCredit');
						var currentNCFT=eval('document.frmCreateSalesOrder.totNCFT');
						var currentCFT=eval('document.frmCreateSalesOrder.totCFT');	
						var isEmployee=eval('document.frmCreateSalesOrder.isEmployee');	
						var customerstatus=eval('document.frmCreateSalesOrder.hCustomerStatus');	
						var MTDNCFT=document.getElementById('MTDNCFT');
						var MTDCFT=document.getElementById('MTDCFT');
						var YTDCFT=document.getElementById('YTDCFT');
						var YTDNCFT=document.getElementById('YTDNCFT');
						var IBM=document.getElementById('IBM');
						var GSUTypeID=eval('document.frmCreateSalesOrder.GSUTypeID');
						var GSUTypeCode=eval('document.frmCreateSalesOrder.txtDealerType');
						tmp_val=outCustomerDetails.split("_");						
						tmpcurrentNCFT=replaceAll(currentNCFT.value,",",""); 
						tmpcurrentCFT=replaceAll(currentCFT.value,",",""); 
						tmpcurrentNCFT=(tmpcurrentNCFT*100)/100;
						tmpcurrentCFT=(tmpcurrentCFT*100)/100;						
						tmpCreditLimit=(tmp_val[0]*100)/100;						
						tmpARBalance=(tmp_val[13]*100)/100;
						tmpPenalty=(tmp_val[2]*100)/100;	
						tmpMTDNCFT=(tmp_val[3]*100)/100;	
						tmpMTDCFT=(tmp_val[4]*100)/100;	
						tmpMTDNCFT=tmpMTDNCFT+tmpcurrentNCFT;
						tmpMTDCFT=tmpMTDCFT + tmpcurrentCFT;						
						tmpYTDCFT=(tmp_val[5]*100)/100;	
						tmpYTDNCFT=(tmp_val[6]*100)/100;	
						tmpYTDNCFT=tmpYTDNCFT + tmpcurrentNCFT;
						tmpYTDCFT=tmpYTDCFT+tmpcurrentCFT;
						
            tmpAvalableCredit=(tmp_val[14]*100)/100;																				
									
						creditLimit.value=CommaFormatted(tmpCreditLimit.toFixed(2));
						ARBalance.value=CommaFormatted(tmpARBalance.toFixed(2));					
					  Penalty.value=CommaFormatted(tmpPenalty.toFixed(2));
            AvalableCredit.value=CommaFormatted(tmpAvalableCredit.toFixed(2));
            
            if($tpi("#dealer_available_credit").html()=='')	$tpi("#dealer_available_credit").html(CommaFormatted(tmpAvalableCredit.toFixed(2)));						
						
						MTDNCFT.innerHTML=CommaFormatted(tmpMTDNCFT.toFixed(2));
						MTDCFT.innerHTML=CommaFormatted(tmpMTDCFT.toFixed(2));
						YTDCFT.innerHTML=CommaFormatted(tmpYTDCFT.toFixed(2));
						YTDNCFT.innerHTML=CommaFormatted(tmpYTDNCFT.toFixed(2));
            						
						IBM.innerHTML=tmp_val[7];
						isEmployee.value=tmp_val[8];
						GSUTypeID.value=tmp_val[9];
						customerstatus.value=tmp_val[10];
						GSUTypeCode.value=tmp_val[11];
												
						if(tmp_val[12]==1) {
            
			       alert("One of the GSU dealers in the GSU Network of the selected dealer has past due amounts and/or unpaid penalties.\n\n"+"Please select a different dealer.");
             
							window.location.reload();
							return false;							
						}
            
						if(tmpPenalty.toFixed(2)!=0.00) {
            
              alert("System can't create Sales Order for the selected dealer due to the following balances:\n\n"+"\t\t Total Past Due Amount: P "+ ARBalance.value+"\n"+"\t\t Total Unpaid Penalties: P "+Penalty.value+"\n\n"+"Please select a different dealer.");
              
							window.location.reload();
							return false;							
						}												
					 }, dataType: 'text' });
}

function computeAddtlDiscount() {

  $tpi.ajax({
  
   type: 'POST',
   url: 'includes/jxCalculateAddtlDiscSO.php',
   data: $tpi("#frmCreateSalesOrder").serialize(),
   success: function(outAddtlDisc) {   	  
    
	  var grossamount=eval('document.frmCreateSalesOrder.txtGrossAmt');
   	var AddtlDisc=eval('document.frmCreateSalesOrder.txtAddtlDisc');
   	var ADPP=eval('document.frmCreateSalesOrder.txtADPP');
  	var CSPLessCPI=eval('document.frmCreateSalesOrder.txtCSPLessCPI');
		var BasicDiscount=eval('document.frmCreateSalesOrder.txtBasicDiscount');
		var SalesWVat=eval('document.frmCreateSalesOrder.txtSalesWVat');
		var AmtWOVat=eval('document.frmCreateSalesOrder.txtAmtWOVat'); 
		var VatAmt=eval('document.frmCreateSalesOrder.txtVatAmt'); 
		var NetAmt=eval('document.frmCreateSalesOrder.txtNetAmt');  
		var AddtlDisc=eval('document.frmCreateSalesOrder.txtAddtlDisc');
		var nextDiscCFT=document.getElementById('nextdiscCFT');
		var nextDiscNCFT=document.getElementById('nextdiscNCFT');
		var ADPP=eval('document.frmCreateSalesOrder.txtADPP');
		var isEmployee=eval('document.frmCreateSalesOrder.isEmployee');
		tmp_val=outAddtlDisc.split("_");
		AddtlDisc.value=tmp_val[0];
		ADPP.value=tmp_val[1];
		tmpCSPLessCPI=replaceAll(grossamount.value, ",", "");
		tmpBasicDiscount=replaceAll(BasicDiscount.value, ",", "");
		tmpAddtlDisc=replaceAll(AddtlDisc.value, ",", "");
		tmpADPP=replaceAll(ADPP.value, ",", "");
    
		if(isEmployee.value==1) {
    
			tmpAddtlDisc=0;
			tmpADPP=0;
		}
    
		tmpSalesWVat=tmpCSPLessCPI-tmpBasicDiscount-tmpAddtlDisc;
		tmpAmtWOVat=tmpSalesWVat/1.12;
		tmpVatAmt=tmpAmtWOVat*0.12;	
		tmpnextDiscCFT=(tmp_val[2]*100)/100;
		tmpnextDiscNCFT=(tmp_val[3]*100)/100;
		tmpNetAmt=tmpSalesWVat-tmpADPP;
		SalesWVat.value=CommaFormatted(tmpSalesWVat.toFixed(2));
		AmtWOVat.value=CommaFormatted(tmpAmtWOVat.toFixed(2));
		VatAmt.value=CommaFormatted(tmpVatAmt.toFixed(2));
		NetAmt.value=CommaFormatted(tmpNetAmt.toFixed(2));
    
		if(tmpnextDiscCFT<0) tmpnextDiscCFT=0;		
    
		if(tmpnextDiscNCFT<0) tmpnextDiscNCFT=0;		
		
		nextDiscCFT.innerHTML=CommaFormatted(tmpnextDiscCFT.toFixed(2));
		nextDiscNCFT.innerHTML=CommaFormatted(tmpnextDiscNCFT.toFixed(2));
    
	  if(isEmployee.value==1) {
      
			ADPP.value="0.00";
			AddtlDisc.value="0.00";
			nextDiscCFT.innerHTML="0.00";
			nextDiscNCFT.innerHTML="0.00";
		}
    
	   	customerdetails();
	   }, dataType: 'text'
	});	
}

function calculatetotals() {

	var table=document.getElementById('dynamicTable');
	var rowCount=table.rows.length;
	
	var index=eval(rowCount); index--;
	var cnt=eval('document.frmCreateSalesOrder.hcnt');
		
	if(!cnt) {
  
		alert("Product is required.");	
		window.location.reload();
		return false;
	}
  
  cnt.value=index;
	var backOrder=eval('document.frmCreateSalesOrder.hBackOrder');
	if(backOrder.value==1) {
  
		if(cnt.value != 1) ctr=cnt.value-1;
		else ctr=cnt.value;		
	} else ctr=cnt.value-1;
	
	var tmpgross=0;
	var tmptotCFT=0;
	var tmptotNCFT=0;
	var tmptotCPI=0;
	var tmptotQtyCFT=0;
	var tmptotQtyNCFT=0;
	var tmptotQtyCPI=0;
	var tmptotQty=0;
	
	var grossamount=eval('document.frmCreateSalesOrder.txtGrossAmt');
	var totalCFT=eval('document.frmCreateSalesOrder.totCFT');
	var totalNCFT=eval('document.frmCreateSalesOrder.totNCFT');
	var totalCPI=document.getElementById('boldStuff');
	var CSPLessCPI=eval('document.frmCreateSalesOrder.txtCSPLessCPI');
	var BasicDiscount=eval('document.frmCreateSalesOrder.txtBasicDiscount');
	var SalesWVat=eval('document.frmCreateSalesOrder.txtSalesWVat');
	var AmtWOVat=eval('document.frmCreateSalesOrder.txtAmtWOVat'); 
	var VatAmt=eval('document.frmCreateSalesOrder.txtVatAmt'); 
	var NetAmt=eval('document.frmCreateSalesOrder.txtNetAmt');  
	var AddtlDisc=eval('document.frmCreateSalesOrder.txtAddtlDisc');
	var ADPP=eval('document.frmCreateSalesOrder.txtADPP');
	var totCPI=eval('document.frmCreateSalesOrder.totCPI');
	
	var isEmployee=eval('document.frmCreateSalesOrder.isEmployee');
	var totQtyCFT=eval('document.frmCreateSalesOrder.totQtyCFT');
	var totQtyNCFT=eval('document.frmCreateSalesOrder.totQtyNCFT');
	var totQtyCPI=eval('document.frmCreateSalesOrder.totQtyCPI');
	var totQty=eval('document.frmCreateSalesOrder.totQty');
  
	for(var i=1;i<=ctr;i++) {
  
		effective=eval('document.frmCreateSalesOrder.txtTotalPrice'+i);
		qty=eval('document.frmCreateSalesOrder.txtOrderedQty'+i);
    
		var tmpeffective=replaceAll(effective.value, ",", "");
		var tmpqty=qty.value;
		pmg=eval('document.frmCreateSalesOrder.txtPMG'+i);
		
		if(pmg.value=="CFT") {
    
			tmptotCFT=eval(tmptotCFT)+eval(tmpeffective);
			tmptotQtyCFT=eval(tmptotQtyCFT)+eval(tmpqty);
		}
		
		if(pmg.value=="NCFT") {
    
			tmptotNCFT=eval(tmptotNCFT)+eval(tmpeffective);
			tmptotQtyNCFT=eval(tmptotQtyNCFT)+eval(tmpqty);
		}
    
		if(pmg.value=="CPI") {
    
			tmptotCPI=eval(tmptotCPI)+eval(tmpeffective);
			tmptotQtyCPI=eval(tmptotQtyCPI)+eval(tmpqty);			
		}
						
		tmpgross=eval(tmpeffective)+eval(tmpgross);			
	}
	
	grossamount.value=tmpgross.toFixed(2);
	
	if(isEmployee.value==0) {
  
		tmpCSPLessCPI=grossamount.value-tmptotCPI;
		tmpBasicDiscount=tmpCSPLessCPI*0.25;
		tmptotCFTless=tmptotCFT*0.75;
		tmptotNCFTless=tmptotNCFT*0.75;
	} else {
  
		tmptotCFTless=tmptotCFT*0.50;
		tmptotNCFTless=tmptotNCFT*0.55;		
		tmpCSPLessCPI=grossamount.value-tmptotCPI;
		tmpBasicDiscount=(tmptotCFT-tmptotCFTless)+(tmptotNCFT-tmptotNCFTless);		
	}
	
  $tpi("#dealer_available_credit").html(CommaFormatted((parseFloat(replaceAll($tpi("#txtAvailableCredit").val(), ",", ""))-parseFloat(grossamount.value)).toFixed(2)));  
  
	totalCFT.value=CommaFormatted(tmptotCFTless.toFixed(2));
	totalNCFT.value=CommaFormatted(tmptotNCFTless.toFixed(2));
	totQtyCFT.value=tmptotQtyCFT;
	totQtyNCFT.value=tmptotQtyNCFT;
	totQtyCPI.value=tmptotQtyCPI;
	totQty.value=tmptotQtyCPI+tmptotQtyNCFT+tmptotQtyCFT;
	totalCPI.innerHTML=CommaFormatted(tmptotCPI.toFixed(2));
	totCPI.value=CommaFormatted(tmptotCPI.toFixed(2));
	CSPLessCPI.value=CommaFormatted(tmpCSPLessCPI.toFixed(2));
	BasicDiscount.value=CommaFormatted(tmpBasicDiscount.toFixed(2));
	grossamount.value=CommaFormatted(tmpgross.toFixed(2));
	
	computeAddtlDiscount();	
}

function getSelectionCOAID(text, li) {
			
	tmp=li.id;
	tmp_val=tmp.split('_');
	h=eval('document.frmCreateSalesOrder.hCOA');	
	h.value=tmp_val[0];	
	i=eval('document.frmCreateSalesOrder.txtCustomer');	
	j=eval('document.frmCreateSalesOrder.txtCustomerName');
	k=eval('document.frmCreateSalesOrder.hGSUType');
	i.value=tmp_val[2];
	j.value=tmp_val[1];
	k.value=tmp_val[3];
	prodCode=eval('document.frmCreateSalesOrder.txtProdCode1');
	prodCode.readOnly=false;
	i.readOnly=true;	
	i.setAttribute("class", "txtfieldLabel");
	
	customerdetails();		
}

function getSelectionCOAID2(text, li) {			
	
	tmp=li.id;
	tmp_val=tmp.split('_');
	h=eval('document.frmDealer.hCOA');
	h.value=tmp_val[0];	
	i=eval('document.frmDealer.txtIBMNo');	
	j=eval('document.frmDealer.txtibmname');
	
	i.value=tmp_val[2];
	j.value=tmp_val[1];			
}

function getSelectionCOAID3(text, li) {
				
	tmp=li.id;
	tmp_val=tmp.split('_');
	h=eval('document.frmDealer.hdnRec');
	h.value=tmp_val[0];	
	i=eval('document.frmDealer.txtRecruiteAcctNo');	
	j=eval('document.frmDealer.txtRecruiteName');
	k=eval('document.frmDealer.txtRecruiterCP');
	k.value=tmp_val[3];
	i.value=tmp_val[2];
	j.value=tmp_val[1];			
}

function addRow() {
		 
	var table = document.getElementById('dynamicTable');
	var rowCount = table.rows.length;
	 
	var row = table.insertRow(rowCount);
	var index = eval(rowCount + 1); index--;
	var cnt = eval('document.frmCreateSalesOrder.hcnt');
	cnt.value =  index;
	var txtboxclass;
	
	//row class
	if(index % 2 != 0) {
  
		row.setAttribute("class", "");
		txtboxclass = "txtfieldItemLabel1";
	} else {
  
		row.setAttribute("class", "");
		txtboxclass = "txtfieldItemLabel2";
	}
	//elements

	var cell2 = row.insertCell(0);
	createCell(cell2, index);
	cell2.setAttribute("align", "center");
	cell2.setAttribute("class", "borderBR padl5");
	cell2.setAttribute("width", "4%");
	
	//item code
	var cell3 = row.insertCell(1);
	cell3.setAttribute("align", "center");
	cell3.setAttribute("class", "borderBR padl5");
	cell3.setAttribute("width", "8%");
	
	var element1 = document.createElement("input");
	var element1h = document.createElement("input");
	var element1hs = document.createElement("input");
	var element1kc = document.createElement("input");
	var element1s = document.createElement("span");
	var element1d = document.createElement("div");
	
	//hidden product id
	element1h.type = "hidden";
	element1h.setAttribute("value", "");
	element1h.setAttribute("id", "hProdID" + index);
	element1h.setAttribute("name", "hProdID" + index);
	
	element1hs.type = "hidden";
	element1hs.setAttribute("value", "");
	element1hs.setAttribute("id", "hSubsID" + index);
	element1hs.setAttribute("name", "hSubsID" + index);
	
	//hidden kit component
	element1kc.type = "hidden";
	element1kc.setAttribute("value", "");
	element1kc.setAttribute("id", "hKitComponent" + index);
	element1kc.setAttribute("name", "hKitComponent" + index);
	
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
	element1.setAttribute("onKeyPress","return disableEnterKey(this, event,"+index+");");
	
	cell3.appendChild(element1);
	cell3.appendChild(element1h);
	cell3.appendChild(element1hs);	
	cell3.appendChild(element1kc);
	cell3.appendChild(element1s);
	cell3.appendChild(element1d);
	
	//item description
	var cell4 = row.insertCell(2);
	cell4.setAttribute("align", "center");
	cell4.setAttribute("class", "borderBR padl5");
	cell4.setAttribute("width", "24%");
	
	var element2 = document.createElement("input");
	element2.type = "text";
	element2.setAttribute("class", "txtfield");
	element2.setAttribute("style", "width: 300px");
	element2.setAttribute("readonly", "yes");
	element2.setAttribute("id", "txtProdDesc" + index);
	element2.setAttribute("name", "txtProdDesc" + index);
	cell4.appendChild(element2);
	
	//UOM
	var cell5 = row.insertCell(3);
	createCell(cell5, 'Piece');
	cell5.setAttribute("align", "center");
	cell5.setAttribute("class", "borderBR padl5");
	cell5.setAttribute("width", "5%");
	
	//PMG		
	var cell6 = row.insertCell(4);	
	cell6.setAttribute("align", "center");
	cell6.setAttribute("class", "borderBR padl5");
	cell6.setAttribute("width", "8%");
	
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
	cell7.setAttribute("width", "8%");
	
	var element3 = document.createElement("input");
	element3.type = "text";
	element3.setAttribute("class", txtboxclass);	
	element3.setAttribute("readonly", "yes");
	element3.setAttribute("id", "txtUnitPrice" + index);
	element3.setAttribute("name", "txtUnitPrice" + index);
	element3.setAttribute("style", "text-align:center");
	cell7.appendChild(element3);
	
	//Promo		
	var cell8 = row.insertCell(6);
	createCell(cell8,'');
	cell8.setAttribute("align", "center");
	cell8.setAttribute("class", "borderBR padl5");
	cell8.setAttribute("width", "5%");
	
	var element10 = document.createElement("input");
	element10.type = "hidden";	
	element10.setAttribute("id", "hPromoID" + index);
	element10.setAttribute("name", "hPromoID" + index);
	element10.setAttribute("value", "0");
	
	var element11 = document.createElement("input");
	element11.type = "hidden";	
	element11.setAttribute("id", "hPromoType" + index);
	element11.setAttribute("name", "hPromoType" + index);
	
	var element11Incentive = document.createElement("input");
	element11Incentive.type = "hidden";	
	element11Incentive.setAttribute("id", "hForIncentive" + index);
	element11Incentive.setAttribute("name", "hForIncentive" + index);
	element11Incentive.setAttribute("value", "0");
	
	var element11d = document.createElement("div");	
	element11d.setAttribute("id", "divPromo" + index);
	element11d.setAttribute("name", "divPromo" + index);
	
	cell8.appendChild(element10);
	cell8.appendChild(element11);
	cell8.appendChild(element11Incentive);
	cell8.appendChild(element11d);
	
	//SOH		
	var cell9 = row.insertCell(7);
	createCell(cell9,'');
	cell9.setAttribute("align", "center");
	cell9.setAttribute("class", "borderBR padl5");
	cell9.setAttribute("width", "5%");
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
	cell10.setAttribute("width", "8%");
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
	cell11.setAttribute("width", "8%");
	
	var element4 = document.createElement("input");
	element4.type = "text";
	element4.setAttribute("class", "txtfield3");	
	element4.setAttribute("id", "txtOrderedQty" + index);
	element4.setAttribute("name", "txtOrderedQty" + index);
	element4.setAttribute("style", "text-align:center");
	element4.setAttribute("onKeyPress","return disableEnterKey(this, event,"+index+");");
	
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
	cell12.setAttribute("width", "8%");
	
	var element5 = document.createElement("input");
	element5.type = "text";
	element5.setAttribute("class", txtboxclass);	
	element5.setAttribute("readonly", "yes");
	element5.setAttribute("id", "txtEffectivePrice" + index);
	element5.setAttribute("name", "txtEffectivePrice" + index);
	element5.setAttribute("style", "text-align:center;");
	cell12.appendChild(element5);
	
	//Effective	 
	var cell13 = row.insertCell(11);
	cell13.setAttribute("align", "center");
	cell13.setAttribute("class", "borderBR padl5");
	cell13.setAttribute("width", "10%");
	
	var element6 = document.createElement("input");
	element6.type = "text";
	element6.setAttribute("class", txtboxclass);	
	element6.setAttribute("readonly", "yes");
	element6.setAttribute("id", "txtTotalPrice" + index);
	element6.setAttribute("name", "txtTotalPrice" + index);
	element6.setAttribute("style", "text-align:center");
	cell13.appendChild(element6);
	
	
	var url = 'includes/scProductListAjaxSO.php?index='+index;
	var prod_choices = new Ajax.Autocompleter(element1.id, element1d.id, url , {afterUpdateElement : getSelectionProductList, indicator: element1s.id});
	element1.focus();	
}

function createCell(cell, text, style, align) {
  
	var div=document.createElement('div');  
	var txt=document.createTextNode(text); 
	
	div.setAttribute('id', 'line');
	div.setAttribute('class', style);  
	div.setAttribute('align', align);
	div.appendChild(txt);
	cell.appendChild(div);  
}
 
function substituteItem(prodID, index, soh) {
 		
	var productID=document.getElementById('hProdIDSubstitute');
	productID.value=prodID+'_'+index+'_'+soh;	
	$tpi("#substituteItem").dialog('open');
  
	return false;     
}

function incentives() {
 
	if(flagincentives==0) flagincentives=1;
	
	 shortcut("F3", function() {
   
		 removecalculate();
	 });
   
	$tpi("#incentive").dialog('open');
  
	return false;	     
}

function removecalculate() {
	
}

function validateSaveSO() {

	penalty			= eval('document.frmCreateSalesOrder.txtPenalty');	
	netamt			= eval('document.frmCreateSalesOrder.txtNetAmt');
	availablecredit	= eval('document.frmCreateSalesOrder.txtAvailableCredit');
	grossamount		= eval('document.frmCreateSalesOrder.txtGrossAmt');
	customer		= eval('document.frmCreateSalesOrder.hCOA');
	status 			= eval('document.frmCreateSalesOrder.hCustomerStatus');
	arbalance 		= eval('document.frmCreateSalesOrder.txtARBalance');
	
	var cnt = eval('document.frmCreateSalesOrder.hcnt');
	var backOrder = eval('document.frmCreateSalesOrder.hBackOrder');
	if(backOrder.value == 1) {
  
		if(cnt.value != 1) ctr=cnt.value-1;		
		else ctr=cnt.value;		
	} else ctr=cnt.value-1;
	
	var flag=0;	
  
	for(var i=1;i<=ctr;i++) {  
		
		hdSOH = eval('document.frmCreateSalesOrder.hSOH' + i);		
		if(hdSOH.value != 0) flag=1;				
	}
  
	if(flag != 0) {
  
		alert("Please click on Create SI button to confirm this transaction and create a Sales Order with Back Orders.");
		return false;
	}	
}

function validateSave() {
	
	penalty			= eval('document.frmCreateSalesOrder.txtPenalty');	
	netamt			= eval('document.frmCreateSalesOrder.txtNetAmt');
	availablecredit	= eval('document.frmCreateSalesOrder.txtAvailableCredit');
	grossamount		= eval('document.frmCreateSalesOrder.txtGrossAmt');
	customer		= eval('document.frmCreateSalesOrder.hCOA');
	status 			= eval('document.frmCreateSalesOrder.hCustomerStatus');
	arbalance 		= eval('document.frmCreateSalesOrder.txtARBalance');
	
	var cnt = eval('document.frmCreateSalesOrder.hcnt');
	var backOrder = eval('document.frmCreateSalesOrder.hBackOrder');
  
	if(backOrder.value == 1) {
  
		if(cnt.value != 1) ctr=cnt.value-1;		
		else ctr=cnt.value;		
	} else ctr=cnt.value-1;
	  
	var flag = 0;
	if(status.value == 3) {
  		
		var tmpnetamount=parseFloat(netamt.value.replace(',', ''));
		var netamount=tmpnetamount.toFixed(2);
     
		if(netamount<500) {
    
			alert("Applicant dealers should reach the minimum amount for purchase in a single receipt.");
			return false;
		} else if(!has_any_kit_been_added && !document.getElementById('has_any_kit_been_added_via_ajax')) {
      
      alert("Dealer(s) with Applicant(AL) status are required to purchase Kit Items.");
      return false;
    }
	}
  
	if(status.value == 4)
	for( var i=1; i <= ctr ; i++)
	{
		productType = eval('document.frmCreateSalesOrder.hProductType' + i);
		if(productType.value == 4) {
    
			alert("Only Applicant Dealers may purchase Kit Items.");
			return false;
		}
	}
	for( var i=1; i <= ctr ; i++)
	{
		
		hdSOH = eval('document.frmCreateSalesOrder.hSOH' + i);
		if(hdSOH.value != 0)
		{
			flag=1;
		}		
	}
	if(flag == 0) {
  
		alert("Please click on Create SO button to confirm this transaction and create a Sales Order with Back Orders.");
		return false;
	}
	if(customer.value == '') {
  
			alert("Please select customer.");
			return false;			
	}
	
	if(replaceAll(grossamount.value,",","") == '' || replaceAll(grossamount.value,",","") == 0.00) {
  
  	alert("Please calculate net amounts first by pressing F3.");
  	return false;			
	}
	
	if(replaceAll(penalty.value,",","") != 0.00) {  
		
		if(replaceAll(grossamount.value,",","") > 500) {
    			
			alert("Customer selected has insufficient credit limit and/or unpaid penalties.");
			return false;
		}
	}
	
	if(replaceAll(availablecredit.value,",","") == 0.00) {
  		
		if(replaceAll(netamt.value,",","") > 500.00 ) {
    			
			alert("Customer selected has insufficient credit limit and/or unpaid penalties.");
			return false;
		}		
	}
	
	if(eval(replaceAll(grossamount.value,",","")) > eval(replaceAll(availablecredit.value,",",""))) {
  	
		if(replaceAll(netamt.value,",","") > 500.00 ) {
    
			alert("Customer selected has insufficient credit limit and/or unpaid penalties.");
			return false;
		}
	}	
}

function disableEnterKey(a, e, index) {

  var key;
  var str=a.id;
  
  if(window.event) key=window.event.keyCode; // Internet Explorer      
  else key=e.which; // Mozilla Firefox    
             
  if(key==13 && str.substring(0, 11)=='txtProdCode') {
  	
  	barCode(a.value, index);
  }
  
  if(str.substring(0, 13)=='txtOrderedQty' && key==13) {
     
    addRow();
    computebestprice(1);            
  }   	     
   
  if(str.substring(0, 11)=='txtProdCode' && key==96) {
  	 
    var ctr=str.substring(11, str.length);
    var code=eval('document.frmCreateSalesOrder.txtOrderedQty'+ctr);
    code.focus();
    code.select();
    return false;
  }
   
  if(str.substring(0, 13)=='txtOrderedQty' && key==96) {
   
    var ctr=str.substring(13, str.length);
  	var nctr=eval(ctr)+1;
  	var code=eval('document.frmCreateSalesOrder.txtProdCode'+nctr);
  	code.focus();
  	code.select();
  	return false;
  }
   
  if(str.substring(0, 13)=='txtOrderedQty' && key==126) {
   
    var ctr=str.substring(13, str.length);
  	var code=eval('document.frmCreateSalesOrder.txtProdCode'+ctr);
  	code.focus();
  	code.select();
  	return false;
  }
   
  if(str.substring(0, 11)=='txtProdCode' && key==126) {
   
    var ctr=str.substring(11, str.length);
     
  	if(ctr!=1) {
     
  	 var code=eval('document.frmCreateSalesOrder.txtOrderedQty'+(ctr-1));
     code.focus();
     code.select();        	
  	}
    
    return false;
  }
  
  return (key!=13);
}

function getSelectionProductList(text, li) {

	var txt = text.id;
	var ctr = txt.substr(11, txt.length);

	tmp = li.id;
	var table = document.getElementById('dynamicTable');
	var rowCount = table.rows.length;
	var hcntr = eval ('document.frmCreateSalesOrder.hcnt');
	var customerStatus = eval('document.frmCreateSalesOrder.hCustomerStatus');
	var prodCode = eval('document.frmCreateSalesOrder.txtProdCode' + ctr);
	var prodDesc = eval('document.frmCreateSalesOrder.txtProdDesc' + ctr);
	
	var tmp_val = tmp.split("_");

	if(customerStatus.value == "") {
  
		alert("Please select customer first.");
		window.location.reload();
	} else {
	
		if(customerStatus.value != 3 && tmp_val[5] == 4 ) {
			
			//prodDesc.focus();
			prodCode.value="";	
			prodCode.focus();
			alert("Only dealers with Applicant(AP) status are allowed to purchase Kit items.\n\n" +					
					" Please select a different item code/name.");
						
			return false;												
		}	else {
    
			hcntr.value = rowCount-1;
			//alert(rowCount + 'a');
			if(tmp != 0) {
      
				//alert("here");
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
				
				//check if kit
				if(tmp_val[5]==4) has_any_kit_been_added=true;
				
				//k.focus();
				//k.select();
				//alert(tmp_val[0]);
				i.value = tmp_val[9];
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
				
				l.value = 1;
				l.focus();
				l.select();
				//m.value = ctr;
				
				if(tmp_val[8] == 1) {
        
				 SOH.setAttribute('onclick', 'substituteItem('+h.value+','+ctr+','+hSOH.value+')');
				 SOH.setAttribute('onMouseover', 'this.style.cursor = "pointer"');
				 SOH.style.color = "blue";
				 SOH.style.fontWeight  = "bold";
				}
				
				//if(rowCount == ctr)	
				//{
				//	addRow() ;
				//}
			} else {
				return false;
			}
		}
	}	
}

function barCode(prodCode, index) {

	if(prodCode!="") {
		
		var ctr=index;
		$tpi.ajax({
	        type: 'POST',
	        url: 'includes/scBarCodeSO.php?prod='+prodCode,
	        data: $tpi("#frmCreateSalesOrder").serialize(),
	        success: function(outCustomerDetails) {
			
			if(outCustomerDetails) {
      
				var tmp_val = outCustomerDetails.split("_");
				var customerStatus = eval('document.frmCreateSalesOrder.hCustomerStatus');
				var prodDesc = eval('document.frmCreateSalesOrder.txtProdDesc' + ctr);
        
				if(customerStatus.value == "") {
        
					alert("Please select customer first.");
					window.location.reload();
				} else {
        
					if(customerStatus.value != 3 && tmp_val[5] == 4 ) {
          
						prodCode.value="";	
						prodCode.focus();
						alert("Only dealers with Applicant(AP) status are allowed to purchase Kit items.\n\n" +					
								" Please select a different item code/name.");
						
						return false;																		
					}	else {
          
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
						i.value = tmp_val[9];
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
						
						l.value = 1;
						l.focus();
						l.select();
						//m.value = ctr;
						
						if(tmp_val[8] == 1) {
            
						 SOH.setAttribute('onclick', 'substituteItem('+h.value+','+ctr+','+hSOH.value+')');
						 SOH.setAttribute('onMouseover', 'this.style.cursor = "pointer"');
						 SOH.style.color = "blue";
						 SOH.style.fontWeight  = "bold";
						}
            
						addRow();																									
					}
				}
			}
			else {
      
			 return false;
			}
					
				   },
	        dataType: 'text'
	      });
	} else {
  
	   return false;
	}	
}

$tpi(document).ready(function() {
	
  $tpi("#substituteItem").dialog({
  
    autoOpen: false,
    height: 350,
    width: 500,
    resizable: false,
    modal: true,           
    open: function() {
    
      var $jprodID=$tpi("#hProdIDSubstitute").val();
      var $jurlString="pid="+$jprodID;
      
      $tpi("#substitutetable").load('includes/jxSubstituteItemSO.php', $jurlString, function() {  });		
    }                  
  });
});

// Incentive pop-up
$tpi(document).ready(function() {
	
  $tpi("#incentive").dialog({
  
    autoOpen: false,
    height: 600,
    width: 1050,
    resizable: false,
    modal: true,           
    open: function() {
    
      $tpi("#incentivetable").load('includes/jxincentives.php?flag='+flagincentives, function() {  });		
    }                  
  });
});
