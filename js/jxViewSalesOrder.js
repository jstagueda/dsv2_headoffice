function validateConfirm()
{
	if (confirm('Are you sure you want to confirm this transaction?') == false)
		return false;
	else
		return true;
}

function validateDelete()
{
	if (confirm('Are you sure you want to delete this transaction?') == false)
		return false;
	else
		return true;
}

var $j = jQuery.noConflict();
function unlock_trans(id, table)
{

	
 if(document.getElementById('hdncnt').value != 1)
 {

  $j.ajax({
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
