<?php

	global $database;
    $fromdate = '';
    $todate = '';
    $code = '';
    
    (isset($_POST['txtCode']) ? $code = $_POST['txtCode'] :  $code = '');
      
    
    if(isset($_POST['txtFromDate']))
    {
    	if($_POST['txtFromDate'] != '')
    	{
    	     $fromdate = date('d/M/Y',strtotime($_POST['txtFromDate']));
    	} 
    	else
    	{
    		$fromdate = '';
    	}
    }
    
    if(isset($_POST['txtToDate']))
    {
    	if($_POST['txtToDate'] != '')
    	{
    	     $todate = date('d/M/Y',strtotime($_POST['txtToDate']));
    	} 
    	else
    	{
    		$todate = '';
    	}
    }
    
	$rs = $sp->spSelectPromoValidation($database, $fromdate, $todate, $code);
?>