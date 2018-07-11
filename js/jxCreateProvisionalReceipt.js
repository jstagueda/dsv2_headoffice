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

// function getDealer(text, li)
function getDealer(text, li) {
		
	tmp=li.id;
	tmp_val=tmp.split("_");
  	
	h=document.frmCreateProvisionReceipt.hCOA;
	h.value=tmp_val[0];
  	
	j=document.frmCreateProvisionReceipt.cboMemoType;
	
	k=document.frmCreateProvisionReceipt.txtCustomer;
  k.value=tmp_val[2];
  
	l=document.frmCreateProvisionReceipt.txtDealerName;
  l.value=tmp_val[1];
  
	m=document.frmCreateProvisionReceipt.txtIBMCode;		
	m.value=tmp_val[3];
  
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
	cDate=dateString.substring(0, curPos);
	
  // Extract the month portion					
	endPos=dateString.indexOf(sepChar, curPos+1);			
	cMonth=dateString.substring(curPos+1, endPos);

  // Extract the year portion					
	curPos=endPos;
	endPos=curPos+5;			
	cYear=curValue.substring(curPos+1, endPos);
	
	// Create Date Object
	dtObject=new Date(cYear, cMonth, cDate);	
	return dtObject;
}

function checkMOP()
{
	var bankName = document.frmCreateProvisionReceipt.txtBank;
	var amount = document.frmCreateProvisionReceipt.txtAmount;
	var bankBranch = document.frmCreateProvisionReceipt.txtBankBranch;
	var CheckNo = document.frmCreateProvisionReceipt.txtCheckNo;
	checkdate  = document.frmCreateProvisionReceipt.txtCheckDate;
	
	var now = new Date();
	var now_day = now.getDate();
	var now_month = now.getMonth() + 1;
	var now_year = now.getFullYear();
	var now_date = now_month + "/" + now_day + "/" + now_year;
	//var stale_month = now.getMonth() - 6;	
	var stale_month = now_month - 6;	
	var stale_date = stale_month + "/" + now_day + "/" + now_year;	
	var d1 = new Date(checkdate.value);
	var d2 = new Date(stale_date);
	//var tdate = Date.parse(checkdate.value);
	//var tdate2 = new Date(tdate);
	  
    temp1 = now_month +"/"+ now_day +"/"+ now_year;
    
    var cfd = Date.parse(temp1);
    var ctd = Date.parse(checkdate.value);
    
    var date1 = new Date(cfd); 
    var date2 = new Date(ctd);
	
	if(bankName.value =='')
	{
		alert('Bank Name is required.');
		bankName.focus();
		return false;
	}
	if(bankBranch.value =='')
	{
		alert('Bank Branch is required.');
		bankBranch.focus();
		return false;
	}
	if(CheckNo.value =='')
	{
		alert('Check No. is required.');
		CheckNo.focus();
		return false;
	}	
	if(date2 <= date1)
	{			
		alert("Check date should be future date.");
		checkdate.select();
		checkdate.focus();
		return false;
	}
	if (amount.value  == '')
	{
		alert('Amount required.');
		amount.focus();
		return false;
	}
	if (!isNumeric(amount.value) || amount.value < 0)
	{
		alert('Invalid numeric format for Amount.');
		amount.focus();
		amount.select();
		return false;
	}
	if (amount.value  == 0)
	{
		alert('Amount required.');
		amount.focus();
		amount.select();
		return false;
	}
	
	return true;
}

function validateSave()
{
	var customer = document.frmCreateProvisionReceipt.txtCustomer;
	var docNo = document.frmCreateProvisionReceipt.txtDocNo;
	var remarks = document.frmCreateProvisionReceipt.txtRemarks;
	var counter = document.frmCreateProvisionReceipt.counter;
	
	if(customer.value=='')
	{
		alert('Customer is required.');
		return false;
	}
	if(docNo.value=='')
	{
		alert('Document No. is required.');
		return false;
	}
	/*if(remarks.value=='')
	{
		alert('Remarks is required.');
		return false;
	}*/
	
	if(counter.value==0)
	{
		alert('Please add mode of payment.');
		return false;
	}

	if (confirm('Are you sure you want to save this record?') == false)
		return false;
	else
		return true;
}