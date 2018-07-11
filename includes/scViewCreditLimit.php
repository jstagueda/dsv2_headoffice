<?php
	global $database;
	$search = "";
	$branchID = 0;
		
	$rs_branch = $sp->spSelectBranch($database,-1,'');

	if(isset($_POST['btnSearch']))
	{
		$branchID = $_POST['cboBranch'];
		$search = $_POST['txtSearch'];
		
		if ($search != "")
		{
			$branchID = 0;			
		}
	}
	
	$rsCustomerList = $sp->spSelectCreditLimitDetails($database, $branchID, $search);

	if(isset($_POST['btnSave']))
	{
		try
		{
			$database->beginTransaction();
			
			if(isset($_POST['chkInclude']))
			{	
				foreach($_POST['chkInclude'] as $key => $value)
				{
					$newCreditLimit = $_POST['hNewCreditLimit'.$value];
					$customerId = $_POST['hCustomerID'.$value];
					$branchID = $_POST['hBranchID'.$value];

					$rsUpdateCreditLimit = $sp->spUpdateCreditLimit($database, $customerId, $newCreditLimit);
					$rsUpdateCreditLimitDetails = $sp->spUpdateCreditLimitDetails($database, $value);
				}	
			}

			$database->commitTransaction();	
			$message = "Successfully approved credit limit of Dealer(s)";	
			redirect_to("index.php?pageid=122&message=$message");
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage();
			redirect_to("index.php?pageid=122&errmsg=$message");
		}
	}
	
	if(isset($_POST['btnCancel']))
	{
		redirect_to("index.php?pageid=122");
	}
?>