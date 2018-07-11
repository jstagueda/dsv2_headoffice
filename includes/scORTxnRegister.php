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
	}
	else
	{
		$vSearch = '' ;
		$fromdate = $start;
		$todate = $end;
		
		$fromdate_q = date("Y-m-d", $tmpdate);
		$todate_q = date("Y-m-d", strtotime($end. " +1 day"));
	}
	
	$rs_orreg = $sp->spDCCRRegister($database, $fromdate_q, $todate_q , $vSearch);
?>