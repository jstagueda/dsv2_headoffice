<?php
	global $database;
	
	if (isset($_POST["btnSubmit"])){
		$bcode = $_POST["cboBCode"];
		$mtype = $_POST["cboMType"];
		$sdate = date("Y-m-d", strtotime($_POST["txtDate"]));		
		$ssdate = date("Y-m-d", strtotime($_POST["txtDate1"]));		
	}else{
		$sdate  = date("Y-m-d");
		$ssdate = date("Y-m-d");
		$bcode  = 0;
		$mtype  = 0;
	}
	
	$rs_branch = $sp->spSelectBranch($database, -1, "");
	$rs_mtype  = $sp->spSelectMovementType($database, -4, "");
?>