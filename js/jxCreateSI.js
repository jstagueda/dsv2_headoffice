function TotalAmount()
{	
	var ml = document.frmCreateSI;
	var len = ml.elements.length;	
	
	var retvar = 0;
	var totdisc = eval('document.frmCreateSI.txtTotDisc');
	
	var netamt = eval('document.frmCreateSI.txtNetAmt');
	var hnetamt = eval('document.frmCreateSI.hNetAmt');
	var disc = eval('document.frmCreateSI.txtSIDisc');
	var disc1 = eval('document.frmCreateSI.txtDisc1');
	var disc2 = eval('document.frmCreateSI.txtDisc2');
	var disc3 = eval('document.frmCreateSI.txtDisc3');
	var wtax = eval('document.frmCreateSI.txtWTax');
	var ocharges = eval('document.frmCreateSI.txtOCharges');
	var grossamt = eval('document.frmCreateSI.txtGrossAmt');
	var vamt = eval('document.frmCreateSI.txtVATAmt');
	var vat = document.getElementById('hVat').value;
	
	var totaltnp = '0.00';
	var gross = '0.00'
	var vatamt = 0;
	var addcharges = 0;
	var rawdisc = 0;
	var discamt = 0;
	var toggledisc = 0;
	var rawwtax = 0;
	var wtamt = 0;
	var togglewt = 0;
	var discamount = 0;		
	var totaltnpd = 0;		
	
	// FOR SI DISCOUNT BEGIN:
	//Check that the first keystroke is a non-zero integer (w or w/o %)
	
	tempdisc = 0;					
	totaltnpd = grossamt.value.replace(",","");

	if (Trim(disc.value) != '') 
	{
		tempdisc = TrimZero(disc.value);
	}

	if (!isPercent(tempdisc))
	{
		alert('Invalid numeric format for SI Discount.')
		disc.value = '';				
		TotalAmount();						
		return false;
	} 
	
	//then check if the input have a trailing % symbol
	if (disc.value.lastIndexOf('%') == (disc.value.length - 1)) 
	{
		rawdisc = disc.value.substring(0,disc.value.length - 1);
		toggledisc = 1;
	} 
	else
	{	
		rawdisc = eval(tempdisc);
	}

	//it's a percent discount, check validity then compute and pass value 
	if (toggledisc) 
	{
		if (eval(rawdisc) >= 100)
		{
			alert('Invalid amount for SI Discount.')
			disc.value = '';	
			TotalAmount();
			return false;
		}					
		discamount = eval(totaltnpd) * (rawdisc/100);

	//it's an amount discount, check validity then pass value
	} 
	else 
	{
		if (eval(rawdisc) >= eval(totaltnpd))
		{ 
			alert('Invalid amount for SI Discount.')
			disc.value = '';
			TotalAmount();
			return false;
		}					
		discamount = rawdisc;		
	}	
	
	//FOR VAT AMOUNT 
	var Gross;
	var VatAmt = 0;
	Gross = totaltnpd - discamount;

	VatAmt = Gross *(vat/100) ;
		
	vamt.value = Math.round((eval(VatAmt) )   *100)/100 ;
	//alert (VatAmt);
			
	// FOR WITHHOLDING TAX  BEGIN:
	//Check that the first keystroke is a non-zero integer (w or w/o %)
	if (Trim(wtax.value) != '') 
	{
		if (!isPercent(TrimZero(wtax.value)))
		{
			alert('Invalid numeric format for Withholding Tax.')
			wtax.value = '';
			TotalAmount();
			return false;
		}						
	}
			
	//then check if the input have a trailing % symbol
	if (wtax.value.lastIndexOf('%') == (wtax.value.length - 1)) 
	{
		rawwtax = wtax.value.substring(0,wtax.value.length - 1);
		togglewt = 1;
	} 
	else 
	{
		rawwtax = eval(wtax.value);
	}
	
	//it's a percent wtaxount, check validity then compute and pass value 
	if (togglewt) 
	{
		if (eval(rawwtax) >= 100)
		{
			alert('Invalid amount for Withholding Tax.')
			wtax.value = '';
			TotalAmount();	
			return false;
		}					
		wtamt = ((eval(totaltnpd) - eval(discamount)) + eval(vatamt)) * (rawwtax/100);
	//it's an amount wtaxount, check validity then pass value
	} 
	else 
	{
		wtamt = rawwtax;
	}
			
	//FOR OTHER CHARGES BEGIN
	if (!ocharges.value == '') 
	{
		addcharges = TrimZero(ocharges.value);
	}					
	
	if (!isNumeric(addcharges)) 
	{
		alert('Invalid numeric format for Additional Charges.')						
		ocharges.focus();
		ocharges.blur();	
		ocharges.select();
		ocharges.value = '';
		TotalAmount();
		return false;
	}
	
	//FOR OTHER CHARGES END
	gross = eval(totaltnpd) - eval(discamount) + eval(wtamt) + eval(addcharges) + eval(VatAmt);
	
	netamt.value = Math.round(eval(gross)*100)/100
}

function checkSave() 
{	
	var d = document.forms[0];	
	docno = d.txtDocumentNo;	
	txndate = d.txtTxnDate;
	deldate = d.txtEffectivityDate;
	terms = d.cboPaymentTerms;
	salesman = d.cboSalesman;
	
	
	if (isBlank(docno.value, 'Document No. required.')) {
		docno.focus();
		return false;				
	}
	if (isBlank(txndate.value, 'Transaction Date required.')) {
		txndate.focus();
		return false;				
	}	
	if (isBlank(deldate.value, 'Effectivity Date required.')) {
		deldate.focus();
		return false;				
	}
	if (!isDate(txndate.value)) {
		txndate.focus();
		txndate.blur();
		txndate.select();
		return false;				
	}	
	if (!isDate(deldate.value)) {
		deldate.focus();
		deldate.blur();
		deldate.select();
		return false;				
	}			
	if (terms.value == 0)
	{
		alert('Payment Terms required');
		terms.focus();
		return false;
	}
	if (salesman.value == 0)
	{
		alert('Salesman required');
		salesman.focus();
		return false;
	}
	if (!VerifySave('Are you sure you want to create this SI?')) {return false;}	
	return true;
}
function cancelTxn() 
{
	if (!VerifySave('Are you sure you want to cancel this transaction?')) {return false;}
	location.href = 'index.php?pageid=45';	
	return false;
}
