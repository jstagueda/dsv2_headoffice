<?php

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	}*/ 
	
	$wid = 0;
	$code = "";
	$name = "";
	$msg = "";
	$search = "";
	$param = 0;

	if(isset($_POST['btnSearch']))
	{
		$param = -1;
		$search = addslashes($_POST['txtfldsearch']);	
	}	
	elseif(isset($_GET['svalue']))
	{
		$param = -1;
		$search = addslashes($_GET['svalue']);	
	}
	
	$rs_warehouse2 = $sp->spSelectWarehouse($param,$search);	 
	
	if (isset($_GET['wid']))
	{
		$wid = $_GET['wid'];
		$rs_warehouse = $sp->spSelectWarehouse($wid,$search);
		
		if ($rs_warehouse->num_rows)
		{
			while ($row = $rs_warehouse->fetch_object())
			{
				 $code   = $row->Code;
				 $name = $row->Name;
			} 
		 }
	 }
?>