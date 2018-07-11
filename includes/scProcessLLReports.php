<?php
	require_once "../initialize.php";
	global $database;
	$datemonth =  $_GET['month'];
	$dateyear = date("Y");
	$tmpdate = $dateyear."-".$datemonth."-01";  
	//Leader List Reports 
	try
	{
		$rsInsertNewRepeat = $sp->spInsertNewRepeat($database,$tmpdate);
		$rsInsertActualSales = $sp->spInsertActualSales($database,$tmpdate);
		$rsInsertBrochureView = $sp->spInsertBrochureView($database,$tmpdate);
		$rsInsertVWBanding	 = $sp->spInsertVWBanding($database,$tmpdate);
		$rsRollingForcast	 = $sp->spInsertRollingForcast($database,$tmpdate);
		$rsactivestaff		 = $sp->spInsertViewStaff($database);
		$rsInsertSalesPerProductLinePY	 = $sp->spInsertActualSalesPerProductLinePY($database,$tmpdate);
		
	}
	catch (Exception $e)
	{
		echo $e->getMessage();
		$database->rollbackTransaction();
	}

?>