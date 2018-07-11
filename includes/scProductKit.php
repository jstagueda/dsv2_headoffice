<?php

	global $database;
	$date = time();
	$search 	= "";
	$prodID		= "";
	$prodCode	= "";
	$prodName	= "";
	
	if(isset($_GET['prodID']))
	{
		$prodID = $_GET['prodID'] ;
		$rsProductKitDetails = $sp->spSelectProductKitByID($database,$prodID);
		if($rsProductKitDetails->num_rows)
		{
			while($row = $rsProductKitDetails->fetch_object())
			{
				$prodID   = $row->ID;
				$prodCode = $row->Code;
				$prodName = $row->Name;
			}
		}
		$rsProductKitComponents = $sp->spSelectProductKitComponentDetails($database,$prodID);
	}
	else
	{
		$start = date("m/d/Y", $date);
		$end = date("m/d/Y", $date);		
	}
	
	if(isset($_POST['btnSearch']))
	{
		$search = $_POST['txtfldsearch'];
		$rsProductKit = $sp->spSelectProductKit($database, $search);
	}
	else
	{
		$rsProductKit = $sp->spSelectProductKit($database, $search);
	}
?>