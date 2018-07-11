<?php 
 /* 
  *  AUTHOR BY: Gino C. Leabres
  *  3.8.2013
  *  ginophp@yahoo.com
  */
// your file to upload  
if(isset($_GET['buyin'])){

	$file = 'LoyaltyPromoBuyin.xls';  
  	header('Content-disposition: attachment; filename='.$file);
	header("Content-Type: application/vnd.ms-excel");
	header('Content-Length: ' . filesize($file));
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
        header("Pragma: no-cache");
        header("Expires: 0");
	ob_flush();
        flush();
        readfile($file);
	ob_end_clean();
}
else if(isset($_GET['entitlement'])){
	
	$file = 'LoyaltyPromoEntitlment.xls';  
  	header('Content-disposition: attachment; filename='.$file);
	header("Content-Type: application/vnd.ms-excel");
	header('Content-Length: ' . filesize($file));
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
        header("Pragma: no-cache");
        header("Expires: 0");
	ob_flush();
        flush();
        readfile($file);
	ob_end_clean();
}
?>