/*   
  @modified by John Paul Pineda
  @date May 21, 2013
  @email paulpineda19@yahoo.com         
*/

var $tpi=jQuery.noConflict();

var tpi_dss_template_directory=(location.protocol+"//"+location.host+(location.pathname).replace('/index.php', ''));

shortcut("F2", function() {

  $tpi("#tpi_dss_sales_cycle_transactions_overlay").toggle('slow');  		
});  				

// function loadMOP(custtypeid)
function loadMOP(custtypeid) {

  $tpi.ajax({
  
    type:'POST',
    url:'includes/jxLoadModeOfPayment.php?custtype='+custtypeid, 
    success: function(innerText) {
    
      tmp_val=innerText.split("_");
      $tpi("#MOPArea").html(tmp_val[0]);
    }, 
    dataType:'text'
  });   
}

// function loadOffsetBalance(custid, acctid, campid)
function loadOffsetBalance(custid, acctid, campid) {

  $tpi.ajax({
  
    type:'POST', 
    url:'includes/jxLoadOffsetBalance.php?custid='+custid+'&acctid='+acctid+'&campid='+campid, 
    success: function(innerText) {
    
      tmp_val=innerText.split("_");
      $tpi("#OffsetBalArea").html(tmp_val[0]);
    },
    dataType:'text'
  });	
}

// function loadIGSDealers(custid)
function loadIGSDealers(custid) {

  var ml=document.frmCreateOfficialReceipt;
  var cid=ml.hCustID
  
  cid.value=custid; 
  
  $tpi.ajax({
  
    type:'POST', 
    url:'includes/jxLoadIGSDealer.php?custid='+custid,  
    success: function(innerText) {        
    
      $tpi('#divIGS2').html(innerText);
    },
    dataType:'text'
  });   
}

// function hideIGS()
function hideIGS() {

  var ml=document.frmCreateOfficialReceipt;
  var settype=ml.cboSettlementType;
  
  for(var i=0;i<ml.rdoORType.length;i++) {
  
    if(ml.rdoORType[i].checked) {
    
      if(ml.rdoORType[i].value==1 && settype.value==2) {
      
        ml.cboSettlementType.disabled=false;
        ml.cboReason.disabled=true;
        document.getElementById('divIGS1').style.display='';
        document.getElementById('divIGS2').style.display='';
      } else if(ml.rdoORType[i].value==1 && settype.value==1) {
      
        ml.cboSettlementType.disabled=false;
        ml.cboReason.disabled=true;
        document.getElementById('divIGS1').style.display='none';
        document.getElementById('divIGS2').style.display='none';
      } else {
      
        ml.cboSettlementType.disabled=true;
        ml.cboReason.disabled=false;
        document.getElementById('divIGS1').style.display='none';
        document.getElementById('divIGS2').style.display='none';
      }
    }
  }
}

// function getDealer(text, li)
function getDealer(text, li) {

	tmp=li.id;
	tmp_val=tmp.split("_");
		
  h=document.frmCreateOfficialReceipt.hCOA;	
	h.value=tmp_val[0];	
	
	i=document.frmCreateOfficialReceipt.txtCustName;
	i.value=tmp_val[1];
	
	m=document.frmCreateOfficialReceipt.txtCustomer;
	m.value=tmp_val[2];
	
	j=document.frmCreateOfficialReceipt.hCustID;
	j.value=tmp_val[0];
	
	n=document.frmCreateOfficialReceipt.hCustTypeID;
	n.value=tmp_val[4];

	k=document.frmCreateOfficialReceipt.txtDocNo;
	l=document.frmCreateOfficialReceipt.txtRemarks;
	
	loadMOP(tmp_val[4]);
	
  $tpi("#divCash1, #divCash2, #divCheck1, #divCheck2, #divCheck3, #divCheck4, #divDeposit1, #divDeposit2, #divDeposit3, #divCommission1, #divCommission2, #divCommission3").fadeOut('slow');												
  
  setSessionIBMID(tmp_val[0]);  
  get_dealer_sales_cycle_transactions(tmp_val[0]);  
  
  if(parseFloat($tpi("#total_penalty_amount").val())>0) alert("Customer penalty: Php "+CommaFormatted((parseFloat($tpi("#total_penalty_amount").val())).toFixed(2)));
}

// function get_dealer_sales_cycle_transactions(v_customer_id)
function get_dealer_sales_cycle_transactions(v_customer_id) {
  
  var tpi_dss_query_string='customer_id='+v_customer_id+'&tpi_dss_sId='+Math.random();
        
  tpi_dss_connection=GetXmlHttpObject();
  
  if(tpi_dss_connection==null) {
    
    alert("Your browser does not support XMLHTTP !");
    return false;
  }               
  
  tpi_dss_connection.open("GET", tpi_dss_template_directory+"/tpl/tpi-get-dealer-sales-cycle-transactions.php?"+tpi_dss_query_string, false);
  tpi_dss_connection.send(null);
  $tpi("#tpi_dss_sales_cycle_transactions").html(tpi_dss_connection.responseText);                                                      
}

// function getDateObject(dateString, dateSeperator)
function getDateObject(dateString, dateSeperator) {

	// This function returns a date object after accepting a date string and a date separator as arguments. 
  	
	var curValue=dateString;
	var sepChar=dateSeperator;
	var curPos=0;
	var cDate, cMonth, cYear;

	// Extract the day portion
	curPos=dateString.indexOf(sepChar);
	cMonth=dateString.substring(0, curPos);
	
	// Extract the month portion				
	endPos=dateString.indexOf(sepChar, curPos+1);			
	cDate=dateString.substring(curPos+1, endPos);

	// Extract the year portion				
	curPos=endPos;
	endPos=curPos+5;			
	cYear=curValue.substring(curPos+1, endPos);
	
	//Create Date Object
	dtObject=new Date(cYear, cMonth, cDate);	
	return dtObject;
}

// function checkMOP(a, e, index)
function checkMOP(a, e, index) {

  var mop=document.frmCreateOfficialReceipt.lstType;
  var key;
  
  var str=a.id;
  
  if(window.event) key=window.event.keyCode; // IE
  else key=e.which; // Firefox
      
  if(key==1 && mop.value==0) return false;      
  
  var custName=document.frmCreateOfficialReceipt.txtCustomer;
  var amount=document.frmCreateOfficialReceipt.txtAmount;
  var amountDue=document.frmCreateOfficialReceipt.txtAmountDue;
  var custtypeid=document.frmCreateOfficialReceipt.hCustTypeID;
  
  var now=new Date();
  var now_day=now.getDate();
  var now_month=now.getMonth() + 1;
  var now_year=now.getFullYear();
  var now_date=now_month+"/"+now_day+"/"+now_year;    	
  var stale_month=now_month-6;
  
  temp1=now_month+"/"+now_day+"/"+now_year;
    
  if(custName.value=='') {
  
    alert("Select customer first.");
    custName.focus();
    return false;
  }
  
  if(mop.value==0) {
  
    alert("Select mode of payment.");		
    return false;
  } else if(mop.value==2) {
  		
    var bank=document.frmCreateOfficialReceipt.txtBank;
    var branch=document.frmCreateOfficialReceipt.txtBankBranch;
    var checkno=document.frmCreateOfficialReceipt.txtCheckNo;
    var checkdate=document.frmCreateOfficialReceipt.txtCheckDate;
    
    var stale_date=stale_month+"/"+now_day+"/"+now_year;	
    var d1=new Date(checkdate.value);
    var d2=new Date(stale_date);
    
    if(Trim(bank.value)=="") {
    
      alert("Bank is required.");
      bank.focus();
      return false;
    }
    
    if(Trim(branch.value)=="") {
    
      alert("Bank Branch is required.");
      branch.focus();
      return false;
    }
    
    if(Trim(checkno.value)=="") {
    
      alert("Check Number is required.");
      checkno.focus();
      return false;
    }
    
    if(checkdate.value=="") {
    
      alert("Check Date is required.");
      checkdate.focus();
      return false;
    }
    
    if(getDateObject(checkdate.value, "/")>getDateObject(now_date, "/")) {
    			
      alert("Check Date cannot be future date.");
      checkdate.select();
      checkdate.focus();
      return false;
    }	        
  } else if (mop.value == 3) {
  
    var depositSlipNo=document.frmCreateOfficialReceipt.txtDepSlipValNo;
    var depositSlipDate=document.frmCreateOfficialReceipt.txtDepDate;
    
    var cfd=Date.parse(temp1);
    var ctd=Date.parse(depositSlipDate.value);
    
    var date1=new Date(cfd); 
    var date2=new Date(ctd);
    
    if(Trim(depositSlipNo.value)=='') {
    
      alert("Deposit Slip No. is required.");
      depositSlipNo.focus();
      return false;
    }
    
    if(date2>date1) {
    			
      alert("Deposit Date should not be future date.");
      depositSlipDate.select();
      depositSlipDate.focus();
      return false;
    }
  } else if(mop.value==4) {
  
    var account=document.frmCreateOfficialReceipt.cboCommType;
    var campaign=document.frmCreateOfficialReceipt.cboCampaign;
    
    if(account.value==0) {
    
      alert("Select Account.");
      account.focus();
      return false;
    }
    
    if(campaign.value==0) {
    
      alert("Select Campaign.");
      campaign.focus();
      return false;
    }
  }
  
  if(amount.value=="") {
  
    alert("Invalid numeric format for Amount.");
    amount.focus();
    return false;
  }
  
  if(!isNumeric(amount.value) || amount.value<0) {
  
    alert("Invalid numeric format for Amount.");
    amount.focus();
    amount.select();
    return false;
  }
  
  if(amount.value==0) {
  
    alert("Amount is required.");
    amount.focus();
    amount.select();
    return false;
  }
  
  if(amountDue.value=="") {
  
    alert("Invalid numeric format for Amount Due.");
    amountDue.focus();
    return false;
  }
  
  if(!isNumeric(amountDue.value) || amountDue.value<0) {
  
    alert("Invalid numeric format for Amount Due.");
    amountDue.focus();
    amountDue.select();
    return false;
  }
  
  if(amountDue.value==0) {
  
    alert("Amount Due is required.");
    amountDue.focus();
    amountDue.select();
    return false;
  }
  
  if(parseFloat(amountDue.value)>parseFloat(amount.value)) {
  
    alert("Amount Due cannot be greater than the Amount.");
    amountDue.focus();
    amountDue.select();
    return false;
  }
  
  if(mop.value==4) {
  
    if(account.value!=4) {
    
      var balance=document.frmCreateOfficialReceipt.txtBalance;
      
      if(parseFloat(balance.value)<parseFloat(amount.value)) {
      
        alert("Payment Amount should not be greater than the Balance Amount.");
        amount.focus();
        amount.select();
        return false;
      }
    }
  }
  
  return true;
}

// function submitForm()
function submitForm() {

  enableFields();	
}

// function submitOffset()
function submitOffset() {

  enableFields();
}

// function enableFields()
function enableFields() {
	
	var ml=document.frmCreateOfficialReceipt;
	var mop=ml.lstType;
	var settype=ml.cboSettlementType;
	
	var tmpIBMID=document.frmCreateOfficialReceipt.hIBMID;	
	
	for(var i=0;i<ml.rdoORType.length;i++) {
  
	 if(ml.rdoORType[i].checked) {
   
    if(ml.rdoORType[i].value==1 && settype.value==2) {
    
      ml.cboSettlementType.disabled=false;
      ml.cboReason.disabled=true;
      ml.txtMemoNo.disabled=true;
      
      $tpi("#divIGS1, #divIGS2").fadeIn('slow');    
    } else if(ml.rdoORType[i].value==1 && settype.value==1) {
    
      ml.cboSettlementType.disabled=false;
      ml.cboReason.disabled=true;
      ml.txtMemoNo.disabled=true;
      
      $tpi("#divIGS1, #divIGS2").fadeOut('slow');    
    } else {
    
      ml.cboSettlementType.disabled=true;
      ml.txtMemoNo.disabled=false;
      ml.cboReason.disabled=false;
      
      $tpi("#divIGS1, #divIGS2").fadeOut('slow');      
    }
	 }
	}
	
	if(mop.value==1) {
  
    $tpi("#divCash1, #divCash2").fadeIn('slow');    
    $tpi("#divCheck1, #divCheck2, #divCheck3, #divCheck4").fadeOut('slow'); 
    $tpi("#divDeposit1, #divDeposit2, #divDeposit3").fadeOut('slow');    
    $tpi("#divCommission1, #divCommission2, #divCommission3").fadeOut('slow');      	  	  	  	  	  	  	  	  	  	
	} else if(mop.value==2) {
  
    $tpi("#divCash1, #divCash2").fadeOut('slow');    
    $tpi("#divCheck1, #divCheck2, #divCheck3, #divCheck4").fadeIn('slow'); 
    $tpi("#divDeposit1, #divDeposit2, #divDeposit3").fadeOut('slow');    
    $tpi("#divCommission1, #divCommission2, #divCommission3").fadeOut('slow');  				    								    						    					  
	} else if(mop.value==3) {
  
    $tpi("#divCash1, #divCash2").fadeOut('slow');    
    $tpi("#divCheck1, #divCheck2, #divCheck3, #divCheck4").fadeOut('slow'); 
    $tpi("#divDeposit1, #divDeposit2, #divDeposit3").fadeIn('slow');    
    $tpi("#divCommission1, #divCommission2, #divCommission3").fadeOut('slow');    						    						
	} else if(mop.value==4) {
  
    $tpi("#divCash1, #divCash2").fadeOut('slow');    
    $tpi("#divCheck1, #divCheck2, #divCheck3, #divCheck4").fadeOut('slow'); 
    $tpi("#divDeposit1, #divDeposit2, #divDeposit3").fadeOut('slow');    
    $tpi("#divCommission1, #divCommission2, #divCommission3").fadeIn('slow');  				    		      						    						
	}
	
	var acctid=ml.cboCommType;
	var campid=ml.cboCampaign;
	var custid=ml.hCustID

	loadOffsetBalance(custid.value, acctid.value, campid.value);
}

// function confirmCancel()
function confirmCancel() {

  return confirm("Are you sure you want to cancel this transaction?");
}

function validateSave(a, e, index) {

  var key;
  var str=a.id;
  
  if(window.event) key=window.event.keyCode; // IE
  else key=e.which; // Firefox  
  
  if(key==13) return false;
    
  var custName=document.frmCreateOfficialReceipt.txtCustomer;
  var mop=document.frmCreateOfficialReceipt.hMOP;
  var ortype=0;
  var settype=document.frmCreateOfficialReceipt.cboSettlementType;
  var bacct=document.frmCreateOfficialReceipt.cboBankHeader;
  var mop_totamt=document.frmCreateOfficialReceipt.txtMOPTotAmt;
  var igs_totamt=document.frmCreateOfficialReceipt.txtIGSTotAmt;
  
  for(var i=0;i<document.frmCreateOfficialReceipt.rdoORType.length;i++) {
  
    if(document.frmCreateOfficialReceipt.rdoORType[i].checked) {
    
      if(document.frmCreateOfficialReceipt.rdoORType[i].value==1) {
      
        ortype=1;
        break;
      } else {
      
        ortype=2;
        
        var reasoncode=document.frmCreateOfficialReceipt.cboReason;
        var memocode=document.frmCreateOfficialReceipt.txtMemoNo;
        
        if(reasoncode.value==62 || reasoncode.value==63) {
        
          if(memocode.value=="") {
          
            alert("DMCM Reference No. is required.");
            return false;
          }
        }
        
        break;
      }
    }
  }
  
  if(custName.value=="") {
  
    alert("Select Customer first.");
    custName.focus();
    return false;
  }
  
  if(bacct.value==0) {
  
    alert("Bank Account is required.");
    bacct.focus();
    return false;
  }
  
  if(mop.value==0) {
  
    alert("Select Mode of Payments.");
    return false;
  }
  
  if(ortype==1 && settype.value==2) {
  
    if(!checker()) {
    
      alert("Please select IGS Dealer(s).");
      return false;		
    }
    
    if(checker()) {
    
      var ml=document.frmCreateOfficialReceipt;
      var len=ml.elements.length;
      
      for(var i=0;i<len;i++) {
      
        var e=ml.elements[i];
        
        if(e.name.substr(0, 9)=="txtIGSAmt") {
        
          retvar=e.name.substr(9);
          			
          var a=eval("document.frmCreateOfficialReceipt.txtIGSAmt"+retvar);
          var tmpb=eval("document.frmCreateOfficialReceipt.txtOutStanding"+retvar);
          var c=eval("document.frmCreateOfficialReceipt.hIGSID"+retvar);
          
          var b=tmpb.value.replace(",", "");                    
          
          if(c.value!="") {
          
            if(TrimZero(a.value)=="" || TrimZero(a.value)==0 || !isNumeric(a.value)) {
            
              alert("Invalid numeric format for Amount.");
              a.select();
              a.focus();
              return false;
            }
          }
          
          if(eval(a.value)>b) {
          
            alert("Applied Amount should not be greater than the Total Outstanding Amount.");
            a.select();
            a.focus();
            return false;
          }
        }
      }
    }
    
    if(eval(mop_totamt.value)!=eval(igs_totamt.value)) {
    
      alert("Total Applied Amount should be equal to Total Payment Amount.");
      return false;
    }
  }
  
  return confirm('Are you sure you want to save this transaction?');  
}

// function checkAll(bin)
function checkAll(bin) {

  var elms=document.frmCreateOfficialReceipt.elements;
  
  for(var i=0;i<elms.length;i++) {
  
    if(elms[i].name=="chkSelect[]") elms[i].checked=bin;    			
  }
}

// function checker()
function checker() {

  var ml=document.frmCreateOfficialReceipt;
  var len=ml.elements.length;
  
  for(var i=0;i<len;i++) {
  
    var e=ml.elements[i];
    
    if(e.name.substr(0, 9)=="txtIGSAmt") {
    
      retvar=e.name.substr(9);
      	
      var a=eval("document.frmCreateOfficialReceipt.hIGSID"+retvar);
      
      if(TrimZero(a.value)!="") return true;      
    }
  }
  
  return false;
}

// function calculateIGSAmt(a, e, index)
function calculateIGSAmt(a, e, index) {

	var key;
  var str=a.id;
    
	if(window.event) key=window.event.keyCode; // IE
  else key=e.which; // Firefox
            
  if(key==113) {
      
  } else if(key==13) {
  
    addRow();
    return false;
  }
  
  var ml=document.frmCreateOfficialReceipt;
  var len=ml.elements.length;
  var igs_totamt=0;
  var totmop=ml.txtMOPTotAmt;
  var totamt=ml.txtIGSTotAmt; 
  
  for(var i=0;i<len;i++) {
  
    var e=ml.elements[i];
    
    if(e.name.substr(0, 9)=="txtIGSAmt") {
    
      retvar=e.name.substr(9);			
      var a=eval("document.frmCreateOfficialReceipt.txtIGSAmt"+retvar);
      
      if(TrimZero(a.value)=="" || !isNumeric(a.value)) igs_totamt=igs_totamt;      
      else igs_totamt+=eval(a.value);      
    }
  }
  
  tmp_totamt=(igs_totamt*100)/100;
  totamt.value=tmp_totamt.toFixed(2);
}

// function computeAmountDue()
function computeAmountDue() {

  var ml=document.frmCreateOfficialReceipt;
	var gamt=ml.txtAmount;
	var damt=ml.txtAmountDue;
	var camt=ml.txtAmountChange;
	
	damt.value=gamt.value
	camt.value=eval(gamt.value)-eval(damt.value);
}

// function computeChange()
function computeChange() {

  var ml=document.frmCreateOfficialReceipt;
	var gamt=ml.txtAmount;
	var damt=ml.txtAmountDue;
	var camt=ml.txtAmountChange;
	
	camt.value=eval(gamt.value)-eval(damt.value);
}

// function NewWindow(mypage, myname, w, h, scroll)
function NewWindow(mypage, myname, w, h, scroll) {

  var winl=(screen.width-w)/2;
	var wint=(screen.height-h)/2;
  
	winprops="height="+h+", width="+w+", top="+wint+", left="+winl+", scrollbars="+scroll+", resizable, menubar=yes, toolbar=no";
  
	win=window.open(mypage, myname, winprops);  
	if(parseInt(navigator.appVersion)>=4) win.window.focus();
}

// function setSessionIBMID(IBMID)
function setSessionIBMID(IBMID) {     
       
  $tpi.ajax({
  
    type:'POST', 
    url:"includes/jxSetIBMIDOfficialReceipt.php?IBMID="+IBMID, 
    success: function() {
    
    }, 
    dataType: 'text'
  });          
}

// function getIGS(text, li)
function getIGS(text, li) {

	tmp=li.id;	
	tmp_val=tmp.split("_");
  
	var index=tmp_val[6];
	
	h=eval("document.frmCreateOfficialReceipt.hIGSID"+index);	
	h.value=tmp_val[0];
		
	i=eval("document.frmCreateOfficialReceipt.txtIGSCode"+index);
	i.value=tmp_val[1];
	
	m=eval("document.frmCreateOfficialReceipt.txtIGSName"+index);
	m.value=tmp_val[2];
	
	j=eval("document.frmCreateOfficialReceipt.txtSIBalance"+index);
	j.value=tmp_val[3];
	
	n=eval("document.frmCreateOfficialReceipt.txtPenalty"+index);
	n.value=tmp_val[4];
	
	o=eval("document.frmCreateOfficialReceipt.txtOutStanding"+index);
	o.value=tmp_val[5];
	
	p=eval("document.frmCreateOfficialReceipt.txtIGSAmt"+index);
	p.focus();		
}

// function createCell(cell, text, style, align)
function createCell(cell, text, style, align) {
  
  var div=document.createElement("div");  
	var txt=document.createTextNode(text); 
	
	div.setAttribute("id", "line");
	div.setAttribute("class", style);  
	div.setAttribute("align", align);
	div.appendChild(txt);
	cell.appendChild(div);  
}
 
function addRow()
{
	var table = document.getElementById('dynamicTable');
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
	
	//hidden product id
	element1h.type = "hidden";
	element1h.setAttribute("value", "");
	element1h.setAttribute("id", "hIGSID" + index);
	element1h.setAttribute("name", "hIGSID" + index);
	
	
	
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
	element1.setAttribute("id", "txtIGSCode" + index);
	element1.setAttribute("name", "txtIGSCode" + index);

	
	cell3.appendChild(element1);
	cell3.appendChild(element1h);	
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
	cell5.setAttribute("align", "left");
	cell5.setAttribute("class", "borderBR padl5");
	cell5.setAttribute("width", "15%");
	
	var element3 = document.createElement("input");
	element3.type = "input";
	element3.setAttribute("class", "txtfieldLabel");
	element3.setAttribute("id", "txtSIBalance" + index);
	element3.setAttribute("name", "txtSIBalance" + index);
	
	cell5.appendChild(element3);
	
	//Penalty
	var cell6 = row.insertCell(4);
	cell6.setAttribute("align", "left");
	cell6.setAttribute("class", "borderBR padl5");
	cell6.setAttribute("width", "15%");
	
	var element4 = document.createElement("input");
	element4.type = "input";
	element4.setAttribute("class", "txtfieldLabel");
	element4.setAttribute("id", "txtPenalty" + index);
	element4.setAttribute("name", "txtPenalty" + index);
	
	cell6.appendChild(element4);
	
	//OutStanding
	var cell7 = row.insertCell(5);
	cell7.setAttribute("align", "left");
	cell7.setAttribute("class", "borderBR padl5");
	cell7.setAttribute("width", "15%");
	
	var element5 = document.createElement("input");
	element5.type = "input";
	element5.setAttribute("class", "txtfieldLabel");
	element5.setAttribute("id", "txtOutStanding" + index);
	element5.setAttribute("name", "txtOutStanding" + index);
	
	cell7.appendChild(element5);
	
	//OutStanding
	var cell8 = row.insertCell(6);
	cell8.setAttribute("align", "right");
	cell8.setAttribute("class", "borderBR padl5");
	cell8.setAttribute("width", "15%");
	
	var element6 = document.createElement("input");
	element6.type = "input";
	element6.setAttribute("class", "txtfield");
	element6.setAttribute("id", "txtIGSAmt" + index);
	element6.setAttribute("name", "txtIGSAmt" + index);
	element6.setAttribute("style", "width: 100px");
	element6.setAttribute("onKeyUp","return calculateIGSAmt(this, event,"+index+");");
	
	cell8.appendChild(element6);
	
	var url = 'includes/scIGSListAjax.php?index=' + index;
	var dealer_choices = new Ajax.Autocompleter(element1.id, element1d.id, url , {afterUpdateElement : getIGS, indicator: element1s.id});
	element1.focus();
	
	
	return false;
}


function disableEnterKey(a, e,index)
{
     var key;
     var str = a.id;
    
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
function enableMemoNo(index)
{
	var reason = index.value;
	if(reason == 62 ||reason == 63)
	{
		document.getElementById('divMemo').style.display='';	
	}
	else
	{
		document.getElementById('divMemo').style.display='none';
	}
}