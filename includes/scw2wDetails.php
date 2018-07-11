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

$rs_detailsallnull = $tpiInventory->spSelectInvCntDetByID($database,$txnid, 2);

$rs=$sp->spSelectInvCntbyID($database, $txnid);

if($rs->num_rows) 
{
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

$rs_detailsall  =   $database->execute(" SELECT invd.ID invdid, p.ID ProductID, p.Code pCode, p.3rd_ItemNumber ItemNumber, p.Name pName, uom.name UOM, ic.`WarehouseID`,invd.RegularPrice,
											   ic.DocumentNo, CAST(CONCAT('IC', REPEAT('0', (8- LENGTH(ic.ID))), ic.ID) AS CHAR) TxnNo,
											   ware.code wcode, invd.CHfreezeQty , invd.HOFreezeQty, invd.HOLocation, invd.CHCreatedQty, invd.AdjustmentQty
										FROM inventorycountchdetails invd
										INNER JOIN inventorycountch ic ON ic.`id` = invd.`inventorycountid`
										INNER JOIN product p ON p.`ID`  =  invd.`ProductID`
										INNER JOIN unittype uom ON invd.`UnitTypeID` = uom.`ID`
										INNER JOIN warehouse ware ON ware.id = invd.`WarehouseID`
										WHERE invd.`InventoryCountID` = $txnid
										order by invd.`WarehouseID` , p.Code
									  ");
							 
#$rs_detailsall = $tpi->loadInventoryCountDetailsCache($database, $txnid, $ic_warehouse_id);
