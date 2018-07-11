<?php

	global $database;
	if (!ini_get('display_errors')) 
	{
		ini_set('display_errors', 1);
	} 
	
	$empid = 0;
	$uid = 0;	
	$search = "";
	
	$employeecodeD = "";
	$employeenameD = "";
	$usernameD = "";
	$employeecode = "";
	$employeename = "";
	$uname = "";
	$loginU2 = "";
	$password2 = "";
	$usertypeid = 0;
	$msg = "";
	
	if(isset($_POST['btnSearch']))
	{
		$empid = -1;
		$search = addslashes($_POST['txtfldSearch']);	
	}	
	elseif(isset($_GET['svalue']))
	{
		$empid = -1;
		$search = addslashes($_GET['svalue']);	
	}

	$rs_userall = $sp->spSelectEmployeeList($database, $empid, $search);	
	
	/*DROP DOWN BOX*/
	 $rs_cboUserType = $sp->spSelectEmpUserType($database);
	
	if (isset($_GET['empid']))
	{
		$empid = $_GET['empid'];
		$rs_useremp = $sp->spSelectEmployeeList($database, $empid,$search);
	   	if ($rs_useremp->num_rows)
	   	{
		 	while ($row = $rs_useremp->fetch_object())
		  	{			   
			   $employeecodeD = $row->EmployeeCode;
			   $employeenameD = $row->EmployeeName;
			   $usernameD = $row->UserName;
		  	} 
		  	$rs_useremp->close();
	   	}
	 }
	 
	if (isset($_GET['uid']))
	{
		$uid = $_GET['uid'];
		$rs_user = $sp->spSelectUserEmployee($database, $uid);
	   	if ($rs_user->num_rows)
	   	{
		 	while ($row = $rs_user->fetch_object())
		  	{
			   $uname = $row->UserName;
			   $loginU2 = $row->LoginName;
			   $employeecode = $row->EmployeeCode;
			   $employeename = $row->EmployeeName;
			   $oldpassword = $row->Password;
			   $usertypeid = $row->UserTypeID;		   
		  	} 
		  	$rs_user->close();
	   	}
	 }
	
?>