<?php
/*   
  @modified by John Paul Pineda.
  @date March 26, 2013.
  @email paulpineda19@yahoo.com         
*/

global $database;

$offset=0;
$RPP=0;
$date=time();
$today=date('m/d/Y', $date);
$tmpdate=strtotime(date('Y-m-d', strtotime($today)));
$tmpStartDate=strtotime(date('Y-m-d', strtotime($today)).' -1 month');
$end=date('m/d/Y', $tmpdate);
$start=date('m/d/Y', $tmpStartDate);

if(isset($_POST['txtStartDate'])) {

  $fromdate=$_POST['txtStartDate']!=''?date('d/m/Y', strtotime($_POST['txtStartDate'])):$start;       
}

if(isset($_POST['txtEndDate'])) {

  $todate=$_POST['txtEndDate']!=''?date('d/m/Y', strtotime($_POST['txtEndDate'])):'';     
}

if(isset($_POST['btnSearch'])) {

  $vSearch=$_POST['txtSearch'];
  $fromdate=date('m/d/Y', strtotime($_POST['txtStartDate']));
  $todate=date('m/d/Y', strtotime($_POST['txtEndDate']));
} else {

  $vSearch='';
  $fromdate=$start;
  $todate=$end;
}	

