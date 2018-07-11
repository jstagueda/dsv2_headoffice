$().ready(function() {

  $('#cancelSI').dialog({
  
    autoOpen: false,
    height: 205,
    width: 380,
    resizable: false,
    modal: true,    
    buttons: {
     
      'Save': function() {
            
        var $reason=$('#lstReasonCode');
        var $remarks=$('#txtCancelRemarks');
        
        if(!checkLength($reason, 'Reason code')) {
        
          $reason.focus();
          return false;
        }
        
        if(!checkLength($remarks, 'Remarks')) {
        
          $remarks.focus();
          return false;
        }        
        
        if(confirm('Are you sure you want to cancel this invoice?')==true) {
        
          $.ajax({
          
            type: 'POST',
            url: 'includes/jxCancelSalesInvoice.php',
            data: {
            
              salesInvoiceId: $('#hTxnID').val(),
              customerId: $('#hCustomerID').val(),
              reasonId: $reason.val(),
              remarks: $remarks.val(),
              txnDate: $('#hTxnDate').val(),
              totalCFT: $('#hTotalCFT').val(),
              totalNCFT: $('#hTotalNCFT').val()
            },
            success: function(jp) {
            
              // alert(jp);
              location.href='index.php?pageid=40&message=Successfully cancelled invoice.';
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
  
  $('#cancelSIButton').bind('click', function() {
      
    $('#cancelSI').dialog('open');
    return false;    
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
  win=window.open(mypage, myname, winprops);
  if(parseInt(navigator.appVersion)>=4) { win.window.focus(); }
}

function validatePrint1(id, reprint, page) {

  $.ajax({
  
    type: 'POST',
    url: 'includes/jxReprintSI_OR.php',
    data: {
    
    	txnId: id, 
    	table: 'salesinvoice'
    }    
  });  
	
	pagetoprint2="pages/sales/sales_SalesInvoiceDetailsPrint.php?tid="+id+"&reprint="+reprint;
		
  NewWindow(pagetoprint2, 'printps2', '850', '1100', 'yes');

	return false;  		
}

function validatePrint(id, page) {

  $.ajax({
  
    type: 'POST',
    url: 'includes/jxReprintSI_OR.php',
    data: {
    
      txnId: id, 
      table: 'salesinvoice'
    }    
  });          
  
  pagetoprint2="pages/sales/sales_SalesInvoiceDetailsPrint.php?tid="+id+"&reprint=0";
    
  NewWindow(pagetoprint2, 'printps2', '850', '1100', 'yes');
  
  return false;  		
}	

function unlock_trans(id, table) {
	
 if(document.getElementById('hdncnt').value!=1) {

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
 
 document.getElementById('hdncnt').value=0;	
}	

function confirmSave(id, page) {

	if(confirm('Are you sure you want to confirm this invoice?')==false) {
		
		return false;
	} else {
  
		validatePrint(id, page) ;
		return true;
	}	
}

