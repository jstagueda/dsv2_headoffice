<?php
	require_once "../initialize.php";	
	global $database;

   	$rs_zipcode = $sp->spSelectBarangay($database, 3, $_GET['Param']);
   	if($rs_zipcode->num_rows)
	{
		while($row_zip = $rs_zipcode->fetch_object())
		{
			$zipcode = $row_zip->Name;
		}
		$rs_zipcode->close();
		
		echo $zipcode; //Send Data back
       	exit; //we're finished so exit..
	}
?>