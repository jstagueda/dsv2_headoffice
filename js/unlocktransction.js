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
