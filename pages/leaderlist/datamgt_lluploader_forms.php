<?php 
/* 
 *  Modified by: marygrace cabardo 
 *  10.09.2012
 *  marygrace.cabardo@gmail.com
 */


// your file to upload  
$file = 'Leader_List.xls';  

 
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


?>