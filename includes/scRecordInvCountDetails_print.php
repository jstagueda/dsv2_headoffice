	<?php
ini_set('max_input_time', 300);
	global $database;	

	$txid = $_GET['tid'];	
	$wwid = $_GET['wid'];
	$llid = $_GET['lid'];
	$sort = $_GET['sort'];	
	$addtnl = $_GET['addtnl'];
	
	if($addtnl == 1)
	{
		$prodlist = $_GET['prodlist'];
	}
	
	$printDate = date("m/d/Y");
	$rs = $sp->spSelectInvCnt_print($database, $txid);
	   	
	if ($rs->num_rows)
	{
		while ($row = $rs->fetch_object())
		{			
			$transno = $row->ID;
			$branchname = $row->bname;
		}
	}
	
	$rs_product = $sp->spSelectInvCountDet_print($database,$txid, $wwid, $llid, $sort);
?>