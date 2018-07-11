<?php
    /*
      ********************************
    **  Modified by: Gino C. Leabres**
    **  12.17.2012********************
    **  ginophp@yahoo.com*************
    **********************************
    */
    global $database;
    $bid = 0;
    $code = "";
    $name = "";
    $address = "";
    $areaid = 0;
    $zipcode = "";
    $telno1 = "";
    $telno2 = "";
    $telno3 = "";
    $faxno = "";
    $tin = "";
    $permitno= "";
    $serversn = "";
    $branchtypeid = 0;
    $branchsizeid = 0;
    $empid = 0;
    $sdid = 0;
    $aodid = 0;
    $sgid = 0;
    $aogid = 0;
    $msg = "";
    $search = "";
    $param = -1;
  
	/* DROP DOWN BOX */
		$rs_cboRegion 		= 	$sp->spSelectAreaByAreaLevel($database,2);
		$rs_cboBranchType 	= 	$sp->spSelectBranchType($database);
		$rs_cboBranchSize 	= 	$sp->spSelectBranchSize($database);
		$rs_cboEmployee 	= 	$sp->spSelectContactEmployeev1($database, $bid); // CHECK! 
	     // $rs_cboEmployee 	= 	$sp->spSelectEmployee($database, $contact_param,""); // CHECK! 
		$rs_cboSalesDir 	= 	$sp->spSelectEmployee($database,-2, "");  //CHECK! Sales Director
		$rs_cboAODir 		= 	$sp->spSelectEmployee($database,-1, "");  //CHECK!
		$rs_cboSalesDept 	= 	$sp->spSelectDepartment($database, 0,""); //CHECK! Sales Director Group
		$rs_cboAODept 		= 	$sp->spSelectDepartment($database, -2,"");//CHECK! Area Operation Director Group
	/* END DROP DOWN BOX */ 

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
       
  	$rs_branchall = $sp->spSelectBranch($database,$param,$search);
	$rs_bankbranch = $sp->spSelectBranchBank($database, 0);	 
	
	if (isset($_GET['bid']))
	{
		$bid = $_GET['bid'];
                $rs_cboEmployee = $sp->spSelectContactEmployeev1($database, $bid); // CHECK! 
		$rs_branch = $sp->spSelectBranch($database,$bid,$search);
		$rs_bankbranch = $sp->spSelectBranchBank($database, $bid);
		
		if ($rs_branch->num_rows)
		{
			while ($row = $rs_branch->fetch_object())
			{
                         $code = $row->Code;
                         $name = $row->Name;
                         $address = $row->StreetAdd;
		         $areaid = $row->AreaID;
		         $zipcode = $row->ZipCode;
		         $telno1 = $row->TelNo1;
		         $telno2 = $row->TelNo2;
		         $telno3 = $row->TelNo3;
		         $faxno = $row->FaxNo;
		         $branchtypeid = $row-> BranchTypeID;
		         $empid = $row->EmployeeID;
		         $tin = $row->TIN;
		         $permitno = $row->PermitNo;
		         $serversn = $row->ServerSN;
			} 
	 	}
		 
	 	$rs_branch_emp = $sp->spSelectBranchEmployee($database, $bid);
	 	if ($rs_branch_emp->num_rows)
		{
			while ($row_bemp = $rs_branch_emp->fetch_object())
			{
				if ($row_bemp->Type == 1)
				{
					$sdid = $row_bemp->EmployeeID; 										
				}
				else
				{
					$aodid = $row_bemp->EmployeeID;
				}
			}
			$rs_branch_emp->close();
		}
		
		$rs_branch_dept = $sp->spSelectBranchDepartment($database, $bid);
		if ($rs_branch_dept->num_rows)
		{
			while ($row_bdep = $rs_branch_dept->fetch_object())
			{
				if ($row_bdep->Type == 1)
				{
					$sgid = $row_bdep->DepartmentID; 										
				}
				else
				{
					$aogid = $row_bdep->DepartmentID;
				}
			}
			$rs_branch_dept->close();
		}
		
		$rs_branch_details = $sp->spSelectBranchDetails($database, $bid);
		if ($rs_branch_details->num_rows)
		{
			while ($row_bdet = $rs_branch_details->fetch_object())
			{
				if ($row_bdet->FieldID == 7)
				{
					$branchsizeid = $row_bdet->ValueID; 										
				}
			}
			$rs_branch_details->close();
		}
	}
?>