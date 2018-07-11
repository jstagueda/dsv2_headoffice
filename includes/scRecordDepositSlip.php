<?php
global $database;

$date=time();
$today=date('m/d/Y', $date);
$sdate='';
$cash=0;
$check=0;

$rs_banklist=$sp->spSelectBankByBranchID($database);

$rs_totamts=$sp->spSelectTotalORCashCheckPerDate($database, date('Y-m-d', $date));
if($rs_totamts->num_rows) {

  while($row=$rs_totamts->fetch_object()) {
  
    $cash=$row->CashAmt;
    $check=$row->CheckAmt;
  } $rs_totamts->close();
}

if(isset($_POST['btnSearch'])) {

  $sdate=date('Y-m-d', strtotime($_POST['txtSearchDate']));
  $rs_depsliplist=$sp->spSelectBranchCollDeposits($database, $sdate);
  $sdate=date('m/d/Y', strtotime($_POST['txtSearchDate']));		
} else {

  $sdate=$today;
  $rs_depsliplist=$sp->spSelectBranchCollDeposits($database, '');		
}
