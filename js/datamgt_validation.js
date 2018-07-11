// JavaScript Document
	
	function trim(s)
	{
		var l=0; var r=s.length -1;
		while(l < s.length && s[l] == '')
		{	l++; }
		while(r > l && s[r] == '')
		{	r-=1;	}		
		return s.substring(l, r+1);
	}	
		
	function checkStr(form)
	{
		var	msg='';

		
		if(trim(form.code.value) == '') msg += ' *Code \n';
		if(trim(form.name.value) == '') msg += ' *Name \n';
		if(trim(form.description.value) == '') msg += ' *Description \n';
		
		if(msg != '')
		{
			alert ('Please complete the following: \n\n' + msg);			
			return false;
		}
		else
			return true;
	}	
	
	function checkStr2Ent(form)
	{
		var	msg='';

		
		if(trim(form.code.value) == '') msg += ' *Code \n';
		if(trim(form.name.value) == '') msg += ' *Name \n';		
		
		if(msg != '')
		{
			alert ('Please complete the following: \n\n' + msg);			
			return false;
		}
		else
			return true;
		

	}	
	
	function checkStr4Ent(form)
	{
			lang = 0;
		def = 0;
		count = 0;
		msg = '';
		str = '';
		obj = document.frmCustomer.elements;

		
		if(trim(form.code.value) == '') msg += ' * Code \n';
		if(trim(form.name.value) == '') msg += ' * Name \n';		
		if(trim(form.address.value) == '') msg += ' * Address \n';		
		if(trim(form.txnDate.value) == '') msg += ' * Enrollment Date \n';		
		if (obj["cboSalesman"].selectedIndex == 0) msg += ' * Salesman \n';
		if (obj["cboOutletType"].selectedIndex == 0) msg += ' * Outlet Type \n';
		if (obj["cboTerms"].selectedIndex == 0) msg += ' * Terms Type \n';
		
		if(msg != '')
		{
			alert ('Please complete the following: \n\n' + msg);			
			return false;
		}
		else
			return true;
	}	
	
	function valDtmgmtProd(form)
	{
		lang = 0;
		def = 0;
		count = 0;
		msg = '';
		str = '';
		obj = document.frmProduct.elements;

		
		if(trim(form.txtCode.value) == '') msg += ' * Code \n';
		if(trim(form.txtName.value) == '') msg += ' * Name \n';		
		if(trim(form.txtShrtName.value) == '') msg += ' * ShortName \n';		
		if(trim(form.txtUCost.value) == '') msg += ' * Unit Cost \n';		
		if (obj["pProdCls"].selectedIndex == 0) msg += ' * Product Class \n';
		if (obj["pProdType"].selectedIndex == 0) msg += ' * Product Type \n';
		if (obj["pCat"].selectedIndex == 0) msg += ' * Category \n';
		if (obj["pBrand"].selectedIndex == 0) msg += ' * Brand \n';
		if (obj["pUOM"].selectedIndex == 0) msg += ' * UOM \n';
		
		if(msg != '')
		{
			alert ('Please complete the following: \n\n' + msg);			
			return false;
		}
		else
			return true;
	}
	
	