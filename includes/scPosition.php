<?php
	global $database;
	
	$posid = 0;
	$param = 0;
	$search = '';
	$code = "";
	$pname = "";
	
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

	$rs_positionall = $sp->spSelectPosition($database, $param, $search);
	
 	if (isset($_GET['posid']))
	{
		$posid = $_GET['posid'];
		
		$rs_position = $sp->spSelectPosition($database, $posid, $search);
	 	if ($rs_position->num_rows)
	 	{
			while ($row = $rs_position->fetch_object())
			{
				$code = $row->code;
				$pname = $row->name;
			} 
			$rs_position->close();
		}
 	}
?>