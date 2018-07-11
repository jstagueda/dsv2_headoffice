<?php

	/*if (!ini_get('display_errors')) {
		ini_set('display_errors', 1);
	}*/ 
	
	$pid = 0;
	$code = "";
	$name = "";
	$desc = "";
	$msg = "";
	$search="";
	global $database;
	$param = 0;
	if(isset($_POST['btnSearch']))
	{
		$param = -1;
		$search = addslashes($_POST['txtfldSearch']);			
	}	
	elseif(isset($_GET['svalue']))
	{
		$param = -1;
		$search = addslashes($_GET['svalue']);	
	}
	
	$rs_prodclassall = $sp->spSelectProductClass($database, $param,$search);


	 
	
	
	 if (isset($_GET['pid'])){
		$pid = $_GET['pid'];
		 $rs_prodclass = $sp->spSelectProductClass($database, $pid,"");
		 if ($rs_prodclass->num_rows){
			while ($row = $rs_prodclass->fetch_object())
			{
				 $code   = $row->Code;
				 $name = $row->Name;
				 $desc = $row->Description;

			} 
			$rs_prodclass->close();
		 }
	 }
		
?>