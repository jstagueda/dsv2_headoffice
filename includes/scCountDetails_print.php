<?php
/*   
  @modified by John Paul Pineda.
  @date October 8, 2012.
  @email paulpineda19@yahoo.com         
*/

	global $database;
	
	$txnid = $_GET['tid'];
	$sort = $_GET['sort'];
  
	if (isset($_GET['prodline'])) $prodline=$_GET['prodline'];
	else $prodline=0;		
		
	$nullcnt=0;
	$totVTABrnch=0;
	$totCABrnch=0;
	$totAQBrnch=0;
	$totVUBrnch=0;
	$totQUBrnch=0;
	$totVOBrnch=0;
	$totQOBrnch=0;
	$amountcnt2=0;
	
	//$rs_detailsall=$sp->spSelectInvCntDetSortByID($database, $txnid, $sort, $prodline);  
  
  $rs_detailsallnull=$sp->spSelectInvCntDetByID($database, $txnid, 2);   
     
	$rs=$sp->spSelectInvCntbyID($database, $txnid);
	
	if ($rs->num_rows) {
  
		while ($row = $rs->fetch_object()) {
    			
			$transno=$row->ID;
			$transdate=$row->TransactionDate;
			$docno=$row->DocumentNo;
			$status=$row->Status;
			$mtype=$row->MovementType;
			$remarks=$row->Remarks;
			$statusid=$row->StatusID;
      
      $ic_warehouse_id=$row->WarehouseID;
		}
	}
  
  if($prodline==0) $rs_detailsall=$tpi->loadInventoryCountDetailsCache($database, $txnid, $ic_warehouse_id);
  else $rs_detailsall=$sp->sploadInventoryCountDetailsCache($database, $txnid, $ic_warehouse_id, $sort, $prodline);  