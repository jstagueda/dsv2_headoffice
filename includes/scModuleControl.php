<?php

	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	
	$mcid = 0;
	$code = "";
	$modid = 0;
	$subid = 0;
	$name = "";
	$desc = "";
	$modname = "";
	$subname = "";
	$msg = "";
	$pageno = "";	
	
	$rs_module = $sp->spSelectModule($database);	
	
	if (isset($_GET['mcid']))
	{
		$mcid = $_GET['mcid'];
		$subid = $_GET['subid'];
		
		$rs_modcontrol = $sp->spSelectModControlDetails($database, $subid,$mcid);
		if ($rs_modcontrol->num_rows)
		{
			while ($row = $rs_modcontrol->fetch_object())
			{
				$code = $row->Code;
				$modid = $row->ModuleID;
				$subid = $row->SubModuleID;
				$name = $row->Name;
				$desc = $row->Description;
				$modname = $row->ModName;
				$subname = $row->SubName;
				$pageno = $row->PageNum;
			} 
			
			$rs_modcontrol->close();
			$rs_submod = $sp->spSelectSubModuleByModuleID($database, $modid);
		}
	}
	
	$rs_suball = $sp->spSelectModControlDetails($database, $subid,0);
		
?>