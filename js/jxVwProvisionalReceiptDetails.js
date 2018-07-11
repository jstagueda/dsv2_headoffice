$().ready(function() {
	

    $('#cancelPR')
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
    	    	
    	if (confirm('Are you sure you want to cancel this provisional receipt?') == true) 
    	{
    	
    		$.ajax(
    				{
    					type: 'POST',
    					url: 'includes/jxCancelProvisionalReceipt.php',
    					data: {prId: $('#hdnTxnID').val(), 
    					    reasonId: $reason.val(),
    					    remarks: $remarks.val()
    					   },
   					   success: function() 
   					   {    						   
   						   	location.href='index.php?pageid=51&message=Successfully Cancelled Provisional Receipt.';
   					   },
    				   dataType: 'text'
    				}
    			);  	   
    	} else {
    		$(this).dialog('close');
    	}
    	
    }
    },      
    open: function() {

    }                  
 });

    $('#btnCancelPR').bind('click', function() {
        $('#cancelPR').dialog('open');
        return false;
      }); 

});

function checkLength(o, field) {
	if ( o.val().length === 0) {
		alert(field + ' required.');
		return false;
	} else {
		return true;
	}
}

function unlock(id, table)
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

function NewWindow(mypage, myname, w, h, scroll) 
  	{
  		var winl = (screen.width - w) / 2;
  		var wint = (screen.height - h) / 2;
  		winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
  		win = window.open(mypage, myname, winprops)
  		if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
  	}
function validatePrint(id, page) 
  {

    $.ajax({
        type: 'POST',
        url: 'includes/jxReprintSI_OR.php',
        data: {
    		txnId: id, 
    		table: 'provisionalReceipt'
        }    
      });  
	
	   if (page == 1)
	   {
	  		pagetoprint = "pages/sales/sales_vwProvisionalReceiptPrint.php?TxnID=" + id;
		}
  		
  		NewWindow(pagetoprint,'printps','850','1100','yes');
		
		
  		return false;  		
  }



