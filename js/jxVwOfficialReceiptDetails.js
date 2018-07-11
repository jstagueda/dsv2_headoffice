$().ready(function() {
	

    $('#cancelOR')
    .dialog({
    autoOpen: false,
    height: 205,
    width: 380,
    resizable: false,
    modal: true,    
    buttons: { 
       'Save': function() {
    	
    	
    	var $reason = $('#lstReasonCode');
    	var $remarks = $('#txtCancelRemarks');
    	
    	if (!checkLength($reason, 'Reason code')) {
    		$reason.focus();
    		return false;
    	}
    	
    	if (!checkLength($remarks, 'Remarks')) {
    		$remarks.focus();
    		return false;
    	}
    	    	
    	if (confirm('Are you sure you want to cancel this official receipt?') == true) {
    		$.ajax({
	            type: 'POST',
	            url: 'includes/jxCancelOfficialReceipt.php',
	            data: {	        	
	        		orId: $('#hdnTxnID').val(), 
	        		reasonId: $reason.val(),
	        		remarks: $remarks.val()
	            },
	            success: function() {
	          	  location.href='index.php?pageid=96&message=Successfully Cancelled Official Receipt.';
	            },
	            dataType: 'text'
	          });  	   
    	} else {
    		$(this).dialog('close');
    	}
    	
    }
    },      
    open: function() {

    }                  
 });
});

function checkLength(o, field) 
{
	if ( o.val().length === 0) {
		alert(field + ' required.');
		return false;
	} else {
		return true;
	}
}

function NewWindow(mypage, myname, w, h, scroll) 
  	{
  		var winl = (screen.width - w) / 2;
  		var wint = (screen.height - h) / 2;
  		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
  		win = window.open(mypage, myname, winprops)
  		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
  	}
function validatePrint(id, page, prntcnt) 
  {

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
	  		pagetoprint = "pages/sales/prOfficialReceipt.php?orid=" + id + "&prntcnt=" + prntcnt;
		/*}*/
  		
  		NewWindow(pagetoprint,'printps','850','1100','yes');
		
		
  		return false;  		
  }	

function unlock_trans(id, table)
{
	
 if(document.getElementById('hdncnt').value != 1)
 {

  $.ajax({
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

function validateCancelOR()
{
	var tdate = Date.parse(document.frmViewOfficialReceipt.txtORDate.value);
	var tdate2 = new Date(tdate);
	var now = new Date();
	var currmonthno = now.getMonth();
	var ormonthno = tdate2.getMonth();
	var curryearno = now.getFullYear();
	var oryearno = tdate2.getFullYear();
	//alert(oryearno);
	//return false;
	if (oryearno < curryearno)
	{
		if (confirm('The Official Receipt you would like to cancel is from a previous month, would you like to continue?') == true) 
		{
			$('#cancelOR').dialog('open');
		}
		else
		{
			return false;
		}
	}
	else
	{
		if (ormonthno < currmonthno)
		{
			if (confirm('The Official Receipt you would like to cancel is from a previous month, would you like to continue?') == true) 
			{
				$('#cancelOR').dialog('open');
			}
			else
			{
				return false;
			}
		}
		else
		{
			$('#cancelOR').dialog('open');
		}
	}
}
