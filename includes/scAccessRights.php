<?php

	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	
	$utypeid = 0;
	$message = "";
	
	/*DROP DOWN BOX*/
 	$rs_cboUserType = $sp->spSelectEmpUserType($database);	
	
?>