<?php
	global $database;
		
	//print_r($_SESSION);
	$code = "";
	$name = "";
	$enrolldate = "";
	$affiliatedbranch = "";
	$brremarks = "";
	$ibmcode = "";
	$search = "";

	if(isset($_POST['btnSearch']))
	{
		$ibmcode = $_POST['txtfldsearchIBMCode']; 		
		$search = $_POST['txtfldsearch'];
	}		
	$rsIBMList = $sp->spSelectIBMListSearch($database, $search, $ibmcode);
	$rs_cboBranch = $sp->spSelectBranch($database, -1, '');
		
	if(isset($_GET['custid']))
	{
		$customerID  = $_GET['custid'];
		//IBM Details 
		$rs_customerdetails =  $sp->spSelectCustomer($database,$customerID,"");	
		if($rs_customerdetails->num_rows)
		{
			while($row = $rs_customerdetails->fetch_object())
			{
				$code = $row->Code;
				$name = $row->Name;
				$enrolldate = $row->EnrollmentDate;
			}
		}	
			
		$rsAffiliatedBranch = $sp->spSelectAffiliatedBranch($database,$customerID);
		if($rsAffiliatedBranch->num_rows)
		{
			while($row = $rsAffiliatedBranch->fetch_object())
			{
				$affiliatedbranch = $affiliatedbranch . "," . $row->branchname;
			}
		}			
	}	
?>