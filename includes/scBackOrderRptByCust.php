<?php
global $database;

if(!ini_get('display_errors')) ini_set('display_errors', 1);
 
$filter='';

$branchID=isset($_GET['branchID'])?$_GET['branchID']:0;

$filter=1;
	
if(isset($_POST['btnSearch'])) {

  $filter=$_POST['rdFilter'];
  
  if($branchID!=0) {
  
    if($filter==1) {
    
      $tmpfromdate=$_POST['txtStartDate'];
      $tmpdatefrom=strtotime(date('Y-m-d', strtotime($tmpfromdate)));
      $tmpenddate=$_POST['txtEndDate'];
      $tmpdateend=strtotime(date('Y-m-d', strtotime($tmpenddate)).'1 day');
      
      $fromdate=date('Y-m-d', $tmpdatefrom);
      $todate=date('Y-m-d', $tmpdateend);
      $vSearchProdLine=$_POST['txtSearchProdLine'];
      $vSearchProdCode=$_POST['txtSearchProdCode'];
      $campaignID=0;
      
      $rsDetailedDetails=$tpi->selectBORptByCustomer($database, 1, $filter, $branchID, $fromdate, $todate, $vSearchProdLine, $vSearchProdCode, 0);
      $rsSummarizedDetails=$tpi->selectBORptByCustomer($database, 2, $filter, $branchID, $fromdate, $todate, $vSearchProdLine, $vSearchProdCode, 0);
    } else {
    	
      $date=time();
      $today=date('m/d/Y', $date);
      $tmpdate=strtotime(date('Y-m-d', strtotime($today)));
      $tmpStartDate=strtotime(date('Y-m-d', strtotime($today)).' -1 month');
      $fromdate=date('Y-m-d', $tmpdate);
      $todate=date('Y-m-d', $tmpdate);
      
      $vSearchProdLine=$_POST['txtSearchProdLine'];
      $vSearchProdCode=$_POST['txtSearchProdCode'];
      $campaignID=$_POST['cboCampaign'];
      
      $rsDetailedDetails=$tpi->selectBORptByCustomer($database, 1, $filter, $branchID, '', '', $vSearchProdLine, $vSearchProdCode, $campaignID);
      $rsSummarizedDetails=$tpi->selectBORptByCustomer($database, 2, $filter, $branchID, '', '', $vSearchProdLine, $vSearchProdCode, $campaignID);
    }
  } else {
  
    $date=time();
    $today=date('m/d/Y', $date);
    $tmpdate=strtotime(date('Y-m-d', strtotime($today)));
    $tmpStartDate=strtotime(date('Y-m-d', strtotime($today)).' -1 month');
    $fromdate=date('Y-m-d', $tmpdate);
    $todate=date('Y-m-d', $tmpdate);
    $vSearchProdLine='';
    $vSearchProdCode='';
    $filter=1;
    $campaignID=0;
    
    $rsDetailedDetails=$tpi->selectBORptByCustomer($database, 1, $filter, $branchID, '', '', '', '', 0);
    $rsSummarizedDetails=$tpi->selectBORptByCustomer($database, 2, $filter, $branchID, '', '', '', '', 0);  
  }
} else {

  $date=time();
  $today=date('m/d/Y', $date);
  $tmpdate=strtotime(date('Y-m-d', strtotime($today)));
  $tmpStartDate=strtotime(date('Y-m-d', strtotime($today)).' -1 month');
  $fromdate=date('Y-m-d', $tmpdate);
  $todate=date('Y-m-d', $tmpdate);
  $vSearchProdLine='';
  $vSearchProdCode='';
  $filter=1;
  $campaignID=0;
  
  $rsDetailedDetails=$tpi->selectBORptByCustomer($database, 1, $filter, $branchID, '', '', '', '', 0);
  $rsSummarizedDetails=$tpi->selectBORptByCustomer($database, 2, $filter, $branchID, '', '', '', '', 0);
}
	
	