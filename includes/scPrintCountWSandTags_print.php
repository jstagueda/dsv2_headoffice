<?php
ini_set('max_execution_time', 300);
global $database;

$prodline = $_GET['prodlineid'];
$datecreate = $_GET['datecreated'];
$pmg = $_GET['pmgid'];
$whid = $_GET['wid'];
$refno = $_GET['ref'];
$txnno = $_GET['txnno'];
$prodall = $_GET['prod'];
if($prodall == 3)
{
$prodlist = $_GET['prodlist'];
$loclist = $_GET['loclist'];
}
$branch = "";
$strTable="";

$rs_warehouse = $sp->spSelectWarehouse($database, $whid, "");
$rs_invcount = $sp->spSelectInventoryCountUnconfirmed($database);

if($refno == 0)
{
	$txnno = $_GET['txnno'];	
   
}
else
{
	
if ($rs_invcount->num_rows)
	{
	
	 while ($row_invcount = $rs_invcount->fetch_object())
	  {
			if ($refno == $row_invcount->ID) 
			{
			  $txnno = $row_invcount->TxnNo;							
			}
			
	  }
	
	}
}
?>