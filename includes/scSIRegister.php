<?php
	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	
	$offset = 0;
	$RPP = 10;
	
	$date = time();
	$today = date("m/d/Y",$date);
	$tmpdate = strtotime(date("Y-m-d", strtotime($today)));
	$tmpStartDate = strtotime(date("Y-m-d", strtotime($today)) . " -1 month");
	$end = date("m/d/Y",$tmpdate);
	$start = date("m/d/Y",$tmpdate);
	$employee = 0;
	
	if(isset($_POST['txtStartDate']))
    {
    	if($_POST['txtStartDate'] != '')
    	{
    	     $fromdate = date('d/m/Y',strtotime($_POST['txtStartDate']));
    	} 
    	else
    	{
    		$fromdate = $start;
    	}
    }
    
    if(isset($_POST['txtEndDate']))
    {
    	if($_POST['txtEndDate'] != '')
    	{
    	     $todate = date('d/m/Y',strtotime($_POST['txtEndDate']));
    	} 
    	else
    	{
    		$todate = '';
    	}
    }
    
    if (isset($_POST['btnSearch']))
	{
		$vSearch = $_POST['txtSearch'] ;
		$fromdate = date('m/d/Y',strtotime($_POST['txtStartDate']));
		$todate = date('m/d/Y',strtotime($_POST['txtEndDate']));
		
		$fromdate_q = date("Y-m-d", strtotime($_POST['txtStartDate']));
		$todate_q = date("Y-m-d", strtotime($_POST['txtEndDate']. " +1 day"));
		if (isset($_POST['cboEmployee']))
		{
			$employee = $_POST['cboEmployee'];			
		}
		else
		{
			$employee = 0;
		}
	}
	else
	{
		$vSearch = '' ;
		$fromdate = $start;
		$todate = $end;
		
		$fromdate_q = date("Y-m-d", $tmpdate);
		$todate_q = date("Y-m-d", strtotime($end. " +1 day"));
		$employee = 0;
	}

    $rs_emp = $sp->spSelectCreatedBySalesInvoice($database, $fromdate_q, $todate_q, "");
    $rs_emp_total = $sp->spSelectCreatedBySalesInvoice($database, $fromdate_q, $todate_q, "");
	$rs_stocklog = $sp->spSelectSIRegister($database, $offset, $RPP, $fromdate_q, $todate_q, "", $employee);
?>