var $j = jQuery.noConflict();

function trim(s)
{
	  var l=0; var r=s.length -1;
	  while(l < s.length && s[l] == ' ')
	  {	l++; }
	  while(r > l && s[r] == ' ')
	  {	r-=1;	}
	  return s.substring(l, r+1);
}

function loadTotalAmounts()
{
	var colldate =  eval('document.frmRecordDepositSlip.txtCollectionDate');

	$j.ajax
	({
		type: 'POST',
        url: 'includes/jxLoadCollectionTotAmt.php?cdate=' + colldate.value,
        success: function(innerText) 
        {
        	tmp_val = innerText.split("_");
        	$j('#CollTotAmts').html(tmp_val[0]);
        },
        dataType: 'text'
      });
}

function confirmSave()
{
	msg = '';
  	obj = document.frmRecordDepositSlip.elements;
  	totcash = document.getElementById('hdnTotCashAmt');
  	totcheck = document.getElementById('hdnTotCheckAmt');
  	checknos = document.getElementById('txtCheckNos');
	  	  
  	if (trim(obj["cboBank"].value) == 0) msg += '   * Bank \n';
  	if (trim(obj["txtValidationNo"].value) == '') msg += '   * Deposit Validation No. \n';
  	if (obj["txtTotCash"].value == '' && obj["txtTotCheck"].value == '') msg += '   * Total Cash/Check Amount \n';
  	if (!isNumeric(obj["txtTotCash"].value) && (obj["txtTotCash"].value) != "") msg += '   * Invalid Total Cash Amount \n';
  	if (!isNumeric(obj["txtTotCheck"].value) && (obj["txtTotCheck"].value) != "") msg += '   * Invalid Total Check Amount \n';
  	if (trim(checknos.value) == '') msg += '   * Check Numbers \n';
	    
	if (eval(obj["txtTotCash"].value) > eval(totcash.value))
	{
		msg += '   * Total Cash should be less than or equal to Remaining amount to be deposited \n';
	}
	
	if (eval(obj["txtTotCheck"].value) > eval(totcheck.value))
	{
		msg += '   * Total Check should be less than or equal to Remaining amount to be deposited \n';
	}
	
	if (msg != '')
  	{ 
		alert ('Please complete the following: \n\n' + msg);
		return false;
  	}
	else
	{
		if ((eval(obj["txtTotCash"].value) < eval(totcash.value)) || (eval(obj["txtTotCash"].value) < eval(totcash.value)))
		{
			if (confirm('Remaining amount to be deposited: \n\n' + 'Check      P ' + eval(totcheck.value) + '\n' + 'Cash        P ' + eval(totcash.value) + '\n\n Proceed with saving this transaction?\n') == false)
			{
		  		return false;
			}
		  	else
		  	{	
		  		if (confirm('Are you sure you want to save this transaction?') == false)
			  		return false;
			  	else
				  	return true;
		  	}
		}
	}
}