<?php

require_once "../initialize.php";
global $database;

$custID = $_POST['hCustomerID'];


$isEmployee = 0;
$ibm = 0;


	
	
	$rsIBM = $sp->spSelectCustomerIBM($database,$custID);
	//echo "spSelectCustomerIBM($database,$custID);";
	if($rsIBM->num_rows)
	{
		while($getIBM = $rsIBM->fetch_object())
		{
			if($getIBM->CustomerTypeID == 99)
			{ 	
				$isEmployee = 1;
			}
			$ibm	= $getIBM->IBM;
		}
	
	
	}
	
$outputString = $ibm;
echo $outputString;

?>
