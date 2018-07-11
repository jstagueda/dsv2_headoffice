<?php
/*   
  @modified by John Paul Pineda.
  @date October 8, 2012.
  @email paulpineda19@yahoo.com         
*/
include CS_PATH.DS."ClassInventory.php";
if(!ini_get('display_errors')) ini_set('display_errors', 1);
 
global $database;

$txnid=$_GET['tid'];
$nullcnt=0;
$totVTABrnch=0;
$totCABrnch=0;
$totAQBrnch=0;
$totVUBrnch=0;
$totQUBrnch=0;
$totVOBrnch=0;
$totQOBrnch=0;
$amountcnt2=0;

//$rs_detailsall=$sp->spSelectInvCntDetByID($database, $txnid, 1);
//$rs_detailsallnull = $sp->spSelectInvCntDetByID($database,$txnid, 2);
$rs_detailsallnull = $tpiInventory->spSelectInvCntDetByID($database,$txnid, 2);
   
$rs=$sp->spSelectInvCntbyID($database, $txnid);

if($rs->num_rows) {

	while($row = $rs->fetch_object()) {
  			
  	$transno	=	$row->ID;
  	$transdate	=	$row->TransactionDate;
  	$docno		=	$row->DocumentNo;
  	$status		=	$row->Status;
  	$mtype		=	$row->MovementType;
  	$remarks	=	$row->Remarks;
  	$statusid	=	$row->StatusID;
  	$createdby	=	$row->EmployeeName;
    
  	if($statusid == 7) {
		$dateconfirmed	=	$row->LastModifiedDate;
	} else {
		$dateconfirmed	=	date("m/d/Y");
	}
  	
  	$confirmedby = $row->ConfirmedBy;
  	$warehouse = $row->Warehouse;
    $ic_warehouse_id=$row->WarehouseID;
	}
}
$rs_detailsall = $tpi->loadInventoryCountDetailsCache($database, $txnid, $ic_warehouse_id);
