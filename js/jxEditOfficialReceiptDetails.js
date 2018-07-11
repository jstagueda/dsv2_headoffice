$j = jQuery.noConflict();
$j().ready(function() {
	
    $j('#cancelOR')
    .dialog({
    autoOpen: false,
    height: 205,
    width: 380,
    resizable: false,
    modal: true,    
    buttons: { 
       'Save': function() {
    	    	
    	var $jreason = $j('#lstReasonCode');
    	var $jremarks = $j('#txtCancelRemarks');
    	
    	if (!checkLength($jreason, 'Reason code')) {
    		$jreason.focus();
    		return false;
    	}
    	
    	if (!checkLength($jremarks, 'Remarks')) {
    		$jremarks.focus();
    		return false;
    	}
    	    	
    	if (confirm('Are you sure you want to cancel this official receipt?') == true) {
    		$j.ajax({
	            type: 'POST',
	            url: 'includes/jxCancelOfficialReceipt.php',
	            data: {	        	
	        		orId: $j('#hdnTxnID').val(), 
	        		reasonId: $jreason.val(),
	        		remarks: $jremarks.val()
	            },
	            success: function() {
	          	  location.href='index.php?pageid=96&message=Successfully Cancelled Official Receipt.';
	            },
	            dataType: 'text'
	          });  	   
    	} else {
    		$j(this).dialog('close');
    	}    	
    }
    },      
    open: function() {

    }                  
 });
});

function checkLength(o, field) {

	if(o.val().length===0) {
  
		alert(field+' required.');
		return false;
	} else {
  
		return true;
	}
}

function NewWindow(mypage, myname, w, h, scroll) {

	var winl=(screen.width-w)/2;
	var wint=(screen.height-h)/2;
	winprops='height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
  
	win=window.open(mypage, myname, winprops)
	if(parseInt(navigator.appVersion)>=4) { win.window.focus(); }
}

function validatePrint(id, page) {

  $.ajax({
  
      type: 'POST',
      url: 'includes/jxReprintSI_OR.php',
      data: {
  		txnId: id, 
  		table: 'officialreceipt'
      }    
  });  

  /* if (page == 1)
   {*/
  pagetoprint = "pages/sales/prOfficialReceipt.php?orid=" + id;
	/*}*/
		
	NewWindow(pagetoprint, 'printps', '850', '1100', 'yes');
		
	return false;  		
}	

function validateCancelOR() {

	var tdate=Date.parse(document.frmViewOfficialReceipt.txtORDate.value);
	var tdate2=new Date(tdate);
	var now=new Date();
	var currmonthno=now.getMonth();
	var ormonthno=tdate2.getMonth();
	
	if(ormonthno<currmonthno) {
  
		if (confirm('The Official Receipt you would like to cancel is from a previous month, would you like to continue?') == true) {
    
			$('#cancelOR').dialog('open');
		} else {
    
			return false;
		}
	} else {
  
	 $('#cancelOR').dialog('open');
	}
}

function trim(s) {

  var l=0; var r=s.length-1;
  
  while(l<s.length && s[l]==' ')
  {	l++; }
  
  while(r>l && s[r]==' ')
  {	r-=1;	}
  
  return s.substring(l, r+1);
}

function computeTotal(cnt) {	

  //	var len = cnt;
	var totamt=0;
	var totalAmount=eval('document.frmEditOfficialReceipt.txtTotalAmount');
	var amt='';	
	var tmpamt1=0;
	var len=document.frmEditOfficialReceipt.countRows.value;	
	
	for(var i = 0; i < len; i++) {
  
		tmpamt=eval('document.frmEditOfficialReceipt.txtAmountApplied'+i+'.value');
		tmpamt1=eval('document.frmEditOfficialReceipt.txtAmountApplied'+i+'');
		amt=eval('document.frmEditOfficialReceipt.txtAmountApplieds'+i+'');
		amt1=eval('document.frmEditOfficialReceipt.txtAmountApplieds'+i+'');
		outstandingBalance=eval('document.frmEditOfficialReceipt.txtOutstandingBalance'+i+'.value');
				
		amt.value=tmpamt1.value;
		
		if(Trim(amt.value)!='') {
    		
			if(!isPercent(TrimZero(amt.value))) {
      				
				alert('Invalid numeric format.');
				amt.value='0.00';
				tmpamt1.value='0.00';
			
				return false;
			}
						
			if(outstandingBalance<eval(amt.value)) {
      
				alert('Applied Amount must be less than or equal to Outstanding Balance.');
				amt.value='0.00';
				tmpamt1.value='0.00';
        
				return false;
			}						
	  }			
	}			
	return true;
}

function validateSave(a,e,index) {

  var key;
  var str=a.id;
    
 if(window.event) key=window.event.keyCode; //IE
 else key=e.which; //firefox
     	
  var totalAmount=document.frmEditOfficialReceipt2.txtTotalAmount;
  var totalUnApplidedAmount=document.frmEditOfficialReceipt2.txtTotalUnapplieds;
  var totRows=document.frmEditOfficialReceipt2.hcntdynamic;
  var amountapplied=0;
  var flag=0;
  
  tpi_errmsg='';
   
	for(var i=1;i<=totRows.value;i++) {
  
  	var si=eval('document.frmEditOfficialReceipt2.hdnRID'+i);
  	var amount=eval('document.frmEditOfficialReceipt2.txtAppliedAmount'+i);
    
		if(si.value!="") {
    
			flag=1;
			amountapplied=amountapplied+eval(amount.value);
      
      if(parseFloat(document.getElementById('txtOutStandingBalance'+i).value)<parseFloat(document.getElementById('txtAppliedAmount'+i).value))
      tpi_errmsg+='The Amount Applied to '+document.getElementById('txtSINO'+i).value+' should be less than or equal to its Outstanding Balance.'+"\n";
		}		    		  
	} 
	
	if(flag==0) {
  
		alert('Select sales invoice(s)/penalty(ies) first.');
		return false;
	} else {
  
		if(amountapplied>eval(totalUnApplidedAmount.value)) {
    
			alert('Total Payment should not be greater than Total Unapplied Amount.');
			return false;
		}
    
    if(tpi_errmsg!='') {
      
      alert(tpi_errmsg); return false;
    }
		
		return confirm('Are you sure you want to save this transaction?');	
	}	
}

function checkAll(bin) {

	var elms=document.frmEditOfficialReceipt.elements;	
  var chkAll=document.frmEditOfficialReceipt.chkAll;
  var amt=0;
  var ci=document.frmEditOfficialReceipt.elements["chkID[]"];
  var len=ci.length;
    
  for(var i=0;i<elms.length;i++) {
    
    if(elms[i].name=='chkID[]') elms[i].checked=bin;		          		  
	} 
    
  if(chkAll.checked==true) {
  
  	for(var j=0;j<ci.length;j++) {
    
  	
      amt=eval('document.frmEditOfficialReceipt.txtAmountApplied'+j+'');
      amt.disabled=false;
  	}
  } else {
  
  	for(var j=0;j<ci.length;j++) {
      
 	    amt=eval('document.frmEditOfficialReceipt.txtAmountApplied'+j+'');
 	    amt.disabled=true;
  	}
  }   
}


function CheckInclude() {
	
	var ci=document.frmEditOfficialReceipt.elements["chkID1[]"];
  var chkAll=document.frmEditOfficialReceipt.chkAll1;
  var amt=0;
  var len=ci.length;
        
  var isChecked='';

	for(i=0;i<=len;i++) {
  		
		amt=eval('document.frmEditOfficialReceipt.txtAmountApplied'+i+'');
		isChecked=eval('document.frmEditOfficialReceipt.txtchk'+i+'');
					
		if(ci[i].checked==false) {
    	
			document.frmEditOfficialReceipt.chkAll.checked=false;
			isChecked.value=0;
			amt.value='0.00';
			amt.disabled=true;
		}
		
		if(ci[i].checked==true) {
    	
			isChecked.value=1;
			amt.disabled=false;
		}	
	}			
}

function checkAlls(bin) {
	
  var elms=document.frmEditOfficialReceipt2.elements;	
  var chkAll2=document.frmEditOfficialReceipt2.chkAll2;
  var amt=0;
  var ci=document.frmEditOfficialReceipt2.elements["chkIID[]"];
  var len=ci.length;
    
  for(var i=0;i<elms.length;i++) {
  
	  if(elms[i].name=='chkIID[]') elms[i].checked=bin;		  	      		  
  } 	
}

function CheckInclude2() {	

	var cis=document.frmEditOfficialReceipt2.elements["chkIID[]"];
  var chkAll=document.frmEditOfficialReceipt2.chkAll2;
  var amt=0;
  var len=cis.length;
       
	for(i=0;i<=len;i++) {
  		
  	if(cis[i].checked==false) document.frmEditOfficialReceipt2.chkAll2.checked=false;  					
	}			
}

function confirmAdd() {

	var cnt = document.frmEditOfficialReceipt.hProd;		
	var length = objchk.length;
	var emptyslots = 0;
	
	alert(length);	
}

function getSIPenalty(text,li) {

	tmp=li.id;
	
	tmp_val=tmp.split("_");
	var index=tmp_val[8];	
	
	i=eval('document.frmEditOfficialReceipt2.hdnRID'+index);
	i.value=tmp_val[0];
		
	h=eval('document.frmEditOfficialReceipt2.hdnIID'+index);	
	h.value=tmp_val[1];
	
	j=eval('document.frmEditOfficialReceipt2.txtIGSName'+index);
	j.value=tmp_val[3];
	
	n=eval('document.frmEditOfficialReceipt2.txttxnDate'+index);
	n.value=tmp_val[4];
	
	o=eval('document.frmEditOfficialReceipt2.txttxnAmount'+index);
	o.value=tmp_val[5];
	
	m=eval('document.frmEditOfficialReceipt2.hdnCreditTerm'+index);
	m.value=tmp_val[7];
			
	q=eval('document.frmEditOfficialReceipt2.txtOutStandingBalance'+index);
	q.value=tmp_val[6];	
			
	p=eval('document.frmEditOfficialReceipt2.txtAppliedAmount'+index);
	p.focus();		
}


function addRow() {

	var table = document.getElementById('dynamicTableSIP');
	var cnt  = document.getElementById('hcntdynamic');
	var rowCount = table.rows.length;	
	var row = table.insertRow(rowCount);
	var index = eval(rowCount + 1);
	cnt.value = index;
	//var cnt = eval('document.frmCreateSalesOrder.hcnt');
	//cnt.value =  index;
	var txtboxclass;
	
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
	
	var cell2 = row.insertCell(0);
	createCell(cell2, index);
	cell2.setAttribute("align", "center");
	cell2.setAttribute("class", "borderBR padl5");
	cell2.setAttribute("width", "5%");
	
	//IGS code
	var cell3 = row.insertCell(1);
	cell3.setAttribute("align", "left");
	cell3.setAttribute("class", "borderBR padl5");
	cell3.setAttribute("width", "15%");
	
	var element1 = document.createElement("input");
	var element1h = document.createElement("input");	
	var element1s = document.createElement("span");
	var element1d = document.createElement("div");
	var element1h2 = document.createElement("input");
	var element1h3 = document.createElement("input");
	
	//hidden product id
	element1h.type = "hidden";
	element1h.setAttribute("value", "");
	element1h.setAttribute("id", "hdnIID" + index);
	element1h.setAttribute("name", "hdnIID" + index);
	
	element1h2.type = "hidden";
	element1h2.setAttribute("value", "");
	element1h2.setAttribute("id", "hdnCreditTerm" + index);
	element1h2.setAttribute("name", "hdnCreditTerm" + index);
	
	element1h3.type = "hidden";
	element1h3.setAttribute("value", "");
	element1h3.setAttribute("id", "hdnRID" + index);
	element1h3.setAttribute("name", "hdnRID" + index);
	
	
	//span
	element1s.setAttribute("id", "indicator" + index);
	element1s.setAttribute("name", "indicator" + index);
	element1s.setAttribute("style", "display: none");
	
	//div
	element1d.setAttribute("id", "dealer_choices" + index);
	element1d.setAttribute("name", "dealer_choices" + index);
	element1d.setAttribute("class", "autocomplete");
	element1d.setAttribute("style", "display: none");
	
	element1.type = "input";
	element1.setAttribute("class", "txtfield");
	element1.setAttribute("id", "txtSINO" + index);
	element1.setAttribute("name", "txtSINO" + index);

	
	cell3.appendChild(element1);
	cell3.appendChild(element1h);
	cell3.appendChild(element1h2);
	cell3.appendChild(element1h3);
	cell3.appendChild(element1s);
	cell3.appendChild(element1d);
	
	
	//IGS Name
	var cell4 = row.insertCell(2);
	cell4.setAttribute("align", "left");
	cell4.setAttribute("class", "borderBR padl5");
	cell4.setAttribute("width", "25%");
	
	var element2 = document.createElement("input");
	element2.type = "input";
	element2.setAttribute("class", "txtfieldLabel");
	element2.setAttribute("id", "txtIGSName" + index);
	element2.setAttribute("name", "txtIGSName" + index);
	
	cell4.appendChild(element2);
	
	//SI Balance
	var cell5 = row.insertCell(3);
	cell5.setAttribute("align", "right");
	cell5.setAttribute("class", "borderBR padl5");
	cell5.setAttribute("width", "10%");
	
	var element3 = document.createElement("input");
	element3.type = "input";
	element3.setAttribute("class", "txtfieldLabel");
	element3.setAttribute("id", "txttxnDate" + index);
	element3.setAttribute("name", "txttxnDate" + index);
	
	cell5.appendChild(element3);
	
	//Penalty
	var cell6 = row.insertCell(4);
	cell6.setAttribute("align", "right");
	cell6.setAttribute("class", "borderBR padl5");
	cell6.setAttribute("width", "15%");
	
	var element4 = document.createElement("input");
	element4.type = "input";
	element4.setAttribute("class", "txtfieldLabel");
	element4.setAttribute("id", "txttxnAmount" + index);
	element4.setAttribute("name", "txttxnAmount" + index);
	
	cell6.appendChild(element4);
	
	//OutStanding
	var cell7 = row.insertCell(5);
	cell7.setAttribute("align", "right");
	cell7.setAttribute("class", "borderBR padl5");
	cell7.setAttribute("width", "15%");
	
	var element5 = document.createElement("input");
	element5.type = "input";
	element5.setAttribute("class", "txtfieldLabel");
	element5.setAttribute("id", "txtOutStandingBalance" + index);
	element5.setAttribute("name", "txtOutStandingBalance" + index);
	
	cell7.appendChild(element5);
	
	//OutStanding
	var cell8 = row.insertCell(6);
	cell8.setAttribute("align", "right");
	cell8.setAttribute("class", "borderBR padr5");
	cell8.setAttribute("width", "15%");
	
	var element6 = document.createElement("input");
	element6.type = "input";
	element6.setAttribute("class", "txtfield");
	element6.setAttribute("id", "txtAppliedAmount" + index);
	element6.setAttribute("name", "txtAppliedAmount" + index);
	
  element6.setAttribute("onkeyup", "validateAmountApplied(this.id);");
	element6.setAttribute("onkeypress", "return calculateIGSAmt(this, event,"+index+");");  
	
	cell8.appendChild(element6);
	
	var url = 'includes/jxListSIApplyPayment.php?index=' + index;
	var dealer_choices = new Ajax.Autocompleter(element1.id, element1d.id, url , {afterUpdateElement : getSIPenalty, indicator: element1s.id});
	
	element1.focus();
	
	stopexe();	
}

function stopexe() {

	return false;
}

function calculateIGSAmt(a,e,index) {

  var key, str=a.id;
  
  if(window.event) key=window.event.keyCode; //IE
  else key=e.which; //firefox      	
  
  if(key==13) {
  
    addRow(); 
    return false;
  }	 	 	 
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

function validateAmountApplied(amount_applied) {
  
  if(!isNaN(parseFloat(document.getElementById('txtOutStandingBalance'+amount_applied.substr(16, 17)).value))) {
    
    if(parseFloat(document.getElementById('txtAppliedAmount'+amount_applied.substr(16, 17)).value)>parseFloat(document.getElementById('txtOutStandingBalance'+amount_applied.substr(16, 17)).value)) {
     
      alert('The Amount Applied to '+document.getElementById('txtSINO'+amount_applied.substr(16, 17)).value+' should be less than or equal to its Outstanding Balance.'+"\n");
      
      document.getElementById('txtAppliedAmount'+amount_applied.substr(16, 17)).value="";
      document.getElementById('txtAppliedAmount'+amount_applied.substr(16, 17)).focus();     
    }  
  }
} 
