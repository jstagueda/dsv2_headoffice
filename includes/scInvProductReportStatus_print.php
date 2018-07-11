<?php
global $database;
$code = $_GET['search'];
$wid = $_GET['wid'];
$lid = $_GET['lid'];
$pmgid = $_GET['pmgid'];
$plid = $_GET['plid'];
$ref = $_GET['refno'];
$frmdate = $_GET['fromdate'];
$frmdate2 = date("Y-m-d", strtotime( $_GET['fromdate']));

if($wid == 0)
{
	
	$warehouse = 'ALL';
}
else
{
		$rs_warehouse = $sp->spSelectWarehouse($database,$wid,'');
		 if ($rs_warehouse->num_rows)
		 {
			while ($rowWare = $rs_warehouse->fetch_object())
			{
				$warehouse = $rowWare->Name;
				

			} 
		 }
}

if($lid == 0)
{
	$location = 'ALL';
}
else
{
	$rs_location = $sp->spSelectWarehouse($database,$lid);
		 if ($rs_location->num_rows)
		 {
			while ($rowLoc = $rs_location->fetch_object())
			{
				$location = $rowLoc->Name;
				

			} 
		 }
}



	

	
	
?>