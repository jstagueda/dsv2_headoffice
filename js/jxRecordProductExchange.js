$j = jQuery.noConflict();
//new for fuller
function getSelectionCOAID(text,li) {
	//alert (text);	
	//d = document.form;
	
	tmp = li.id;
	//alert(tmp);
	tmp_val = tmp.split("_");
	custID = eval('document.frmRecordProdExchange.hCustomerID');	
	custCode = eval('document.frmRecordProdExchange.txtCustomer');
	custName = eval('document.frmRecordProdExchange.txtCustomerName');
	custID.value = tmp_val[0];	
	custCode.value = tmp_val[2];
	custName.value  = tmp_val[1];
	customerdetails();
	setSessionIBMID(tmp_val[0]);
	enableSINO();
}
function customerdetails()
{
	$j.ajax({
       type: 'POST',
       url: 'includes/jxCustomerIBMProdExchange.php?mode=post',
       data: $j('#frmRecordProdExchange').serialize(),
       success: function(outCustomerDetails){
			tmp_val		= outCustomerDetails.split("_");	
			IBMName = eval('document.frmRecordProdExchange.txtIBMName');
			IBMName.value = tmp_val[0];			
			
			
		   },
       dataType: 'text'
     });
}

function enableSINO()
{
	txtSINo = eval('document.frmRecordProdExchange.txtSINo');
	siid = eval('document.frmRecordProdExchange.hSIID');	
	docno = eval('document.frmRecordProdExchange.txtDocNo');
	sidate = eval('document.frmRecordProdExchange.txtSIDate');
	refno = eval('document.frmRecordProdExchange.txtRefNo');
	branch = eval('document.frmRecordProdExchange.txtBranch');
	createdby = eval('document.frmRecordProdExchange.txtCreatedBy');
	status = eval('document.frmRecordProdExchange.txtStatus');
	confirmedby = eval('document.frmRecordProdExchange.txtConfimedBy');
	dateconfirmed = eval('document.frmRecordProdExchange.txtDateConfirmed');
	remarks = eval('document.frmRecordProdExchange.txtRemarks');
	siid.value = "";
	docno.value = "";
	sidate.value = "";
	refno.value ="";
	branch.value = "";
	createdby.value = "";
	status.value = "";
	confirmedby.value = "";
	dateconfirmed.value = "";
	remarks.value = "";
	txtSINo.value = "";
	txtSINo.disabled = false;
}
function setSessionIBMID(IBMID)
{
     var IBMID =  eval('document.frmRecordProdExchange.hCustomerID');
       
       $j.ajax
       ({
           type: 'POST',
       url: 'includes/jxSetCustomerIDProductExchange.php?IBMID='+IBMID.value,
       success: function() 
       {
       },
           dataType: 'text'
         });          
}

function generateSIDetails()
{
     var siid =  eval('document.frmRecordProdExchange.hSIID');
       
     $j.ajax({
         type: 'POST',
         url: 'includes/jxProductExchangeItemsbySI.php?SIID='+siid.value,
         data: $j('#frmRecordProdExchange').serialize(),
         success: function(test){
  			
    	 	$j('#prodlist').load('includes/jxProductExchangeItemsbySI.php?SIID='+siid.value);
  		   },
         dataType: 'text'
       });       
}

function getSI(text,li) 
{
	tmp = li.id;
	//alert(tmp);
	tmp_val = tmp.split("_");
	siid = eval('document.frmRecordProdExchange.hSIID');
	sino = eval('document.frmRecordProdExchange.txtSINo');
	docno = eval('document.frmRecordProdExchange.txtDocNo');
	sidate = eval('document.frmRecordProdExchange.txtSIDate');
	refno = eval('document.frmRecordProdExchange.txtRefNo');
	branch = eval('document.frmRecordProdExchange.txtBranch');
	createdby = eval('document.frmRecordProdExchange.txtCreatedBy');
	status = eval('document.frmRecordProdExchange.txtStatus');
	confirmedby = eval('document.frmRecordProdExchange.txtConfimedBy');
	dateconfirmed = eval('document.frmRecordProdExchange.txtDateConfirmed');
	remarks = eval('document.frmRecordProdExchange.txtRemarks');
	
	
	sino.value = tmp_val[0];
	siid.value = tmp_val[1];
	docno.value = tmp_val[2];
	sidate.value = tmp_val[3];
	refno.value = tmp_val[4];
	branch.value = tmp_val[6];
	createdby.value = tmp_val[7];
	status.value = tmp_val[8];
	confirmedby.value = tmp_val[9];
	dateconfirmed.value = tmp_val[10];
	remarks.value = tmp_val[11];
	generateSIDetails();
}

function enablefields(index)
{
	//alert(index);
	
	chkbox = eval('document.frmRecordProdExchange.chkID'+index);
	qty = eval('document.frmRecordProdExchange.txtQty'+index);
	reason = eval('document.frmRecordProdExchange.cboReason'+index);
	
	if(chkbox.checked == true)
	{
		qty.disabled = false;
		reason.disabled = false;
	
	}
	else
	{
		qty.disabled = true;
		reason.disabled = true;
	}
	
}

function enableAll()
{
	cnt = eval('document.frmRecordProdExchange.hcnt');
	chkAll = eval('document.frmRecordProdExchange.chkAll');
	if(cnt)
	{
		var tmp = cnt.value;
		//alert(tmp);
		if(chkAll.checked == true)
		{
			for(i=1 ; i <= tmp ; i++)
			{
				chkbox = eval('document.frmRecordProdExchange.chkID'+i);
				qty = eval('document.frmRecordProdExchange.txtQty'+i);
				reason = eval('document.frmRecordProdExchange.cboReason'+i);
				chkbox.checked = true;
				qty.disabled = false;
				reason.disabled = false;
			}
		}
		else
		{
			for(i=1 ; i <= tmp ; i++)
			{
				chkbox = eval('document.frmRecordProdExchange.chkID'+i);
				qty = eval('document.frmRecordProdExchange.txtQty'+i);
				reason = eval('document.frmRecordProdExchange.cboReason'+i);
				chkbox.checked = false;
				qty.disabled = true;
				reason.disabled = true;
				
			}
		}
	}
	else
	{
		//alert('no');
	}
}
function getProductExchange(text,li)
{
	//alert(text.value);
	tmp = li.id;
	tmp_val = tmp.split("_");
	var ctr = tmp_val[3];
	
	exchangeID = eval('document.frmRecordProdExchange.hProdExchangeID'+ctr);
	exchangeCode = eval('document.frmRecordProdExchange.txtExchangeProdCode'+ctr);
	exchangeName = eval('document.frmRecordProdExchange.txtExchangeProdName'+ctr);
	
	exchangeID.value = tmp_val[0];
	exchangeCode.value = tmp_val[1];
	exchangeName.value = tmp_val[2];
	
	
	
	;
	
}


function openPopUp(txnID) 
{

	var objWin;
	popuppage = "pages/sales/sales_productExchange_print.php?TxnID="+ txnID;
		
	if (!objWin) 
	{			
		objWin = NewWindow(popuppage,'printps','800','500','yes');
	}
	
	return false;  		
}

function NewWindow(mypage, myname, w, h, scroll) 
{
	var winl = (screen.width - w) / 2;
	var wint = (screen.height - h) / 2;
	winprops = 'height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scroll+',resizable,menubar=yes,toolbar=no';
	win = window.open(mypage, myname, winprops)
	if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
}



