<?PHP 
//die();
	include "../../initialize.php";
	if($_POST['type'] == 1){
	//sales invoice
		include "SalesInvoicePrint.php";
	}else{
	//official receipt
		include "officialreceipt.php";
	}
?>