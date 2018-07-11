<?php
	$aid = 0;
	$code = "";
	$name = "";
	$msg = "";
	$search = "";
	$param = 0;
   global $database;
 $rs_CboAreaLevel = $sp->spSelectAreaLevelCbo($database);
 
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
	
	$rs_area2 = $sp->spSelectArea($database, $param,$search, 0);	 
	$rs_parentarea = $sp->spSelectArea($database,$aid,$search, 0);
	if (isset($_GET['aid']))
	{
		$aid = $_GET['aid'];
		$rs_area = $sp->spSelectArea($database,$aid,$search, 0);
		$rs_parentarea = $sp->spSelectArea($database,$aid,$search, 1);
		
		if ($rs_area->num_rows)
		{
			while ($row = $rs_area->fetch_object())
			{
				 $code   = $row->Code;
				 $name = $row->Name;
				 $arid = $row->AreaLevelID;
				 $prntaid = $row->ParentID;
			} 
		 }
	 }

?>