<?php

	if(!isset($_GET['msg']))
	{	
		$msg = '' ;
	}
	else 
	{
		$msg = $_GET['msg'];
	} 	
	if(!isset($_GET['errmsg']))
	{	
		$errmsg = '' ;
	}
	else 
	{
		$errmsg = $_GET['errmsg'];
	} 	
	
	global $database;
	
	$ptid = 0;
	$refid = 0;
	$search = "";
	/*$rs_cboRef =  $sp->spSelectPriceList(0,0,$search);*/
	
	if(isset($_POST['btnSearch2']))
	{
		$search = addslashes($_POST['txtSearch']);
		$ptid =	$_POST['txtptid'];
		$refid = $_POST['txtrefid'];
	}
	
/*	if(isset($_GET['ptid']))
	{
		$ptid = $_GET['ptid'];
	
	}
	if(isset($_GET['refid']))
	{
		$refid = $_GET['refid'];
	
	}*/
	$rs_pricing =  $sp->spSelectPricing($database, $search);
?>