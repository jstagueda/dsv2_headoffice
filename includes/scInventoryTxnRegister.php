<?php
	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 

	$beg = 0.00;
	$end = 0.00;
	$qtyin = 0.00;
	$qtyout = 0.00;
	
	$wareid = 1;
	$offset = 0.00;
	$RPP = 0.00;
	
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$tmpStartDate = strtotime(date("Y-m-d", strtotime($today)));
	//$tmpStartDate = strtotime(date("Y-m-d", strtotime($today)) . " -1 month");
	$end = date("m/d/Y",$tmpdate);
	$start = date("m/d/Y",$tmpStartDate);
	$fromDate = '';
	$toDate = '';
	$search ='';
	
	if(isset($_GET['search'])){
            $search= $_GET['search'];
	}else{
            $search ='';
	}
	
	if (isset($_GET['dteFrom'])){
            $fromDate = $_GET['dteFrom'];
	}else{
            $fromDate = $start;
	}
	
	if (isset($_GET['dteTo'])){
            $toDate = $_GET['dteTo'];
	}else{
            $toDate = $end;
	}if (isset($_GET['mtid'])){
            $mtid = $_GET['mtid'];
	}else{
            $mtid = 0;
	}

	if(isset($_POST['btnSearch']))
	{
		if(isset($_POST['txtSearch']))
		{
			$search= $_POST['txtSearch'];
		}
		else
		{
			$search ='';
		}
		
		
		if(isset($_POST['txtStartDates']))
		{
			$fromDate = $_POST['txtStartDates'];
		}
		else
		{
			$fromDate = $start;
		}
		
		if(isset($_POST['txtEndDates']))
		{
			$toDate = $_POST['txtEndDates'];
		}
		else
		{
			$toDate = $end;
		}
		
		
	}	
	
	//$rs_stocklog = $sp->spSelectInventoryRegister($database, $offset, $RPP, $wareid, $fromDate, $toDate,$mtid,$search);
	$rs_movementType = $sp->spSelectMovementType($database,-3,'');
	

?>