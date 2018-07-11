var $j = jQuery.noConflict();

$j().ready(function() {
	
    $j('#cboMemoType').change(function() {
    	$j.ajax({
            type: 'POST',
            url: 'includes/jxLoadReasonDropDownList.php',
            data: {
    			memoType: $j(this).val(),
    			reasonId: 0 
            },
            success: function(innerText) {
            	tmp_val = innerText.split("_");
            	var docno = document.frmCreateDMCM.txtDocNo;
            	var docid = document.frmCreateDMCM.txtBIRSeriesID;
            	var memamt = document.getElementById("txtMemoAmt");
            	docno.value = tmp_val[1];
            	docid.value = tmp_val[2];
            	memamt.style.color = tmp_val[3];
            	$j('#reasonArea').html(tmp_val[0]);
            },
            dataType: 'text'
          });  	    	
      }); 
}); 

function enableAmount(id, bin)
{
	var applied = eval('document.frmCreateDMCM.txtPayAmt' + id);
	
	if (bin)
	{
		applied.disabled = false;
	}
	else
	{
		applied.disabled = true;
	}
	
	if (bin==true)
	{
		applied.disabled = false;
	}
	else
	{
		applied.disabled = true;
	}
}

function checkAll2(bin) 
{
	var elms = document.frmCreateDMCM.elements;
	var cnt = document.frmCreateDMCM.cntrows.value;
	var chkAll = document.frmCreateDMCM.chkAll2;
	var chk = 0 ;
	 
	if(chkAll.checked == true)
	{
		for (var i = 1; i <= cnt; i++)
		{
			chk = eval('document.frmCreateDMCM.chkInclude'+i+'.checked=true');		
			enableAmount(i,true);
		}
	}
	else
	{
		for (var i = 1; i <= cnt; i++)
		{
			chk = eval('document.frmCreateDMCM.chkInclude'+i+'.checked=false');
			enableAmount(i,false);
		}
	}		
}

function updatePayment(id)
{
	var tmpapplied = 0;
	var applied = eval('document.frmCreateDMCM.txtPayAmt' + id);
	var outstanding = eval('document.frmCreateDMCM.hOutstanding' + id).value;
	var memoAmount = eval('document.frmCreateDMCM.txtMemoAmt');
	//var totalPayment = eval('document.frmCreateDMCM.txtTotPayment');
	
	/*if(memoAmount.value=='')
	{
		alert('Memo Amount is required.');
		memoAmount.focus;
		return false;
	}*/	
	
	if (!applied.value == '')
	{
		tmpapplied = TrimZero(applied.value);
	}
	
	if (!isNumeric(tmpapplied))
	{
		alert('Invalid numeric format for Payment');
		applied.value = '';
		applied.focus();
		applied.blur();	
		applied.select();
		return false;
	}
	
	if (tmpapplied < 0)
	{
		alert('Invalid numeric format for Payment');
		applied.value = '';
		applied.focus();
		applied.blur();	
		applied.select();
	}
	/*else
	{
		if (eval(applied.value) > outstanding)
		{			
			alert('Payment amount should not be greater than Outstanding Balance Amount.');
			applied.value = '';
			applied.focus();
			applied.blur();	
			applied.select();
			return false;
		}
	}*/	
	
	return computeTotal(id);
}
	
function computeTotal(id)
{	
	var doc = document.frmCreateDMCM;
	var len = document.frmCreateDMCM.cntrows.value;
	var tmpamt = 0;
	var totamt = 0;
	var totpay = eval('document.frmCreateDMCM.txtTotPayment');
	var amt = '';
	var tmpx = 0;
	var memoAmount = eval('document.frmCreateDMCM.txtMemoAmt');
	
	for (var i = 1; i <= len; i++)
	{
		amt =eval('document.frmCreateDMCM.txtPayAmt'+i+'.value');
	 
		if(amt == '' || amt == 0 || amt < 0)
		{		 
			totamt =eval(totamt);		  
		}
		else
		{		  
		   totamt =( eval(totamt)) +( eval(amt));
		   totamt =eval(totamt);
		}	 
	}
	
	totpay.value=  Math.round((eval(totamt))*100)/100 ;	
	return true;
}

function checker()
{
	var ml = document.frmCreateDMCM;
	var len = document.frmCreateDMCM.cntrows.value;	
	var chk = false;
	var cnt = 0;
	
	for (var i = 1; i <= len; i++) 
	{
		chk = eval('document.frmCreateDMCM.chkInclude'+i+'.checked');
		
		if(chk == true)
		{
			cnt = 1;
		}
	} 
	
	return cnt;
}

function ConfirmSave()
{
	var doc = document.frmCreateDMCM;
	var len = doc.elements.length;
	var custid = doc.hCustomerID;
	var docno = doc.txtDocNo;
	var mtype = doc.cboMemoType;
	var memamt = doc.txtMemoAmt;
	var remarks = doc.txtRemarks;
	var totpay = doc.txtTotPayment;
	var reason = doc.cboReason;
	var code = doc.txtCustomer;

	if (code.value == '')
	{
		alert('Please select Dealer.');
		doc.txtCustomer.focus();
		return false;
	}
	
	if (custid.value == 0)
	{
		alert('Please select Dealer.');
		doc.txtCustomer.focus();
		return false;
	}
	
	if (Trim(memamt.value) == '')
	{
		alert('Total Amount required.');
		memamt.focus();
		return false;
	}
	
	if (!isNumeric(memamt.value) || memamt.value == 0 || memamt.value < 0)
	{
		alert('Invalid numeric format for Memo Amount.');
		memamt.select();
		memamt.focus();
		return false;
	}
	
	if (mtype.value == 0 || mtype.value == 'index.php?pageid=46&mtid=0')
	{
		alert('Memo Type required.');
		mtype.focus();
		return false;
	}
	
	if (reason.value == '' || reason.value == 'index.php?pageid=46')
	{
		alert('Reason required.');
		reason.focus();
		return false;
	}
	
	if (reason.value == 25 || reason.value == 26)
	{
		var orno = doc.txtParticulars;
		if (orno.value == "")
		{
			alert('OR No. required.');
			orno.select();
			orno.focus();
			return false;
		}
	}
	
	if (remarks.value == "")
	{
		alert('Remarks required.');
		remarks.focus();
		return false;
	}
	
	if (checker() == 0)
	{
		if (confirm('Are you sure you want to save this record?') == false)
			return false;
		else
			return true;
	}
	else
	{
		var count = document.frmCreateDMCM.cntrows.value;	
		var chk = false;
		var cnt = 0;

		for (var i = 1; i <= count; i++) 
		{
			chk = eval('document.frmCreateDMCM.chkInclude'+ i +'.checked');
			qty = eval('document.frmCreateDMCM.txtPayAmt'+ i);
			
			if(chk == true)
			{
				if(qty.value == "" || !isNumeric(qty.value) || qty.value == 0 || qty.value < 0)
				{
					alert('Invalid numeric format for Amount.');
					qty.focus();
					qty.select();
					return false;
				}
			}
		} 
		
		if (eval(totpay.value) > eval(memamt.value))
		{
			alert('Total Payment Amount  should not be more than Total Amount.')
			return false;
		}
		else
		{
			if (confirm('Are you sure you want to save this record?') == false)
				return false;
			else
				return true;
		}
	}
}

function cancelTxn() 
{
	if (!VerifySave('Are you sure you want to cancel this transaction?')) {return false;}
	location.href = 'index.php?pageid=18';	
	return false;
}

function getSelectionCustomer(text,li) {
	//alert (text);	
	//d = document.form;
	tmp = li.id;
	tmp_val = tmp.split("_");
	
	h = eval('document.frmCreateDMCM.hCustomerID');
	h.value = tmp_val[0];	
	j = eval('document.frmCreateDMCM.cboMemoType');
	
	k = eval('document.frmCreateDMCM.txtCustomer');
	l = eval('document.frmCreateDMCM.txtDealerName');
		
	k.value = tmp_val[2];
	l.value = tmp_val[1];
	
	/*j.focus();
	j.select();*/

	$j('#siDetails').load('includes/jxLoadInvoicesDMCM.php', 'custID=' + h.value );
}

function LoadSI(i)
{
	$j('#siDetails').load('includes/jxLoadInvoicesDMCM.php', 'custID=' + i );	
}

function MM_jumpMenu(targ,selObj,restore)
{ 	
	var docNo = document.frmCreateDMCM.txtDocNo.value;	
	var custID = document.frmCreateDMCM.hCustomerID.value;
	var remarks = document.frmCreateDMCM.txtRemarks.value;
    var totAmount = document.frmCreateDMCM.txtMemoAmt.value;
	var dcode = document.frmCreateDMCM.txtCustomer.value;
	var dName = document.frmCreateDMCM.txtDealerName.value;
	var orNo = document.frmCreateDMCM.txtParticulars.value;
	var remarks = document.frmCreateDMCM.txtRemarks.value;

	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"&orNo="+orNo+"&amt="+totAmount+"&dName="+dName+"&dCode="+dcode +"&docNo=" + docNo+ "&custID=" + custID+ "&remarks=" + remarks + "#MMHead" +"'");
	  
	if (restore) 
		selObj.selectedIndex = 0;
	
	LoadSI(custID);
}