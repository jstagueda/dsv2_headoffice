<?php
	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	}
	
	$utid = 0;
	$code = "";
	$name = "";
	$msg = "";

	$rs_usertypeall = $sp->spSelectUserType($database, 0);	
	
	if (isset($_GET['utid']))
	{
		$utid = $_GET['utid'];
		$rs_usertype = $sp->spSelectUserType($database, $utid);
		
		if ($rs_usertype->num_rows)
		{
			while ($row = $rs_usertype->fetch_object())
			{
				 $code = $row->Code;
				 $name = $row->Name;
			} 
			$rs_usertype->close();
		 }
	 }
?>