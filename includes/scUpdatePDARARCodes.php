<?php
	global $database;
	
	if(isset($_GET['message']))
	{
		$errmsg = $_GET['message'];
	}
	else
	{
		$errmsg = "";
	}
	$search = "";
  	$pdaID = 0;
  	$custFrom = "";
  	$custTo = "";
	
	$rs_cboPDACode = $sp->spSelectPDARARCode($database);
	
	if(isset($_POST['btnSearch']))
	{
		if($_POST['cboPDACodes'] == 0 && $_POST['txtIBMfrm'] != "" && $_POST['txtIBMto'] != "")
		{
			$pdaID = 0;
  			$custFrom = $_POST['txtIBMfrm'];
  			$custTo =	$_POST['txtIBMto'];
  			$rsDealerDetails = $sp->spSelectCustomerPDARARCodes($database, 3, $pdaID, $custFrom, $custTo);
		}
		elseif($_POST['cboPDACodes'] != 0 && ($_POST['txtIBMfrm'] == "" || $_POST['txtIBMto'] != ""))
		{
			$pdaID 	= $_POST['cboPDACodes'];
  			$custFrom = $_POST['txtIBMfrm'];
  			$custTo =	$_POST['txtIBMto'];
  			$rsDealerDetails = $sp->spSelectCustomerPDARARCodes($database, 2, $pdaID, $custFrom, $custTo);
		}
		elseif($_POST['cboPDACodes'] != 0 && $_POST['txtIBMfrm'] != "" && $_POST['txtIBMto'] != "")
		{
			$pdaID 	= $_POST['cboPDACodes'];
  			$custFrom = $_POST['txtIBMfrm'];
  			$custTo =	$_POST['txtIBMto'];
  			$rsDealerDetails = $sp->spSelectCustomerPDARARCodes($database, 1, $pdaID, $custFrom, $custTo);
		}
		else
		{
			$rsDealerDetails = $sp->spSelectCustomerPDARARCodes($database, 4, $pdaID, $custFrom, $custTo);			
		}
	}
	else
	{
		$rsDealerDetails = $sp->spSelectCustomerPDARARCodes($database, 4, $pdaID, $custFrom, $custTo);
	}
	
	$rs_cboNewPDACode = $sp->spSelectPDARARCode($database);
	
	if(isset($_POST['btnSave']))
	{
		try
		{
			if(isset($_POST['chkInclude']))
			{
				foreach($_POST['chkInclude'] as $key => $value)
				{		
					$rcustomerPDAID = $value;	
					$pdaCode = $_POST['cboNewRARCode']	;
					$customerID = $_POST['hCustomerID'.$rcustomerPDAID];
					$userID = $_SESSION['emp_id'];
					$database->beginTransaction();				
					
					$rsInsertPDACode = $sp->spInsertPDARARCodes($database,$rcustomerPDAID , $customerID , $userID , $pdaCode);
					if (!$rsInsertPDACode)
					{
						throw new Exception("An error occurred, please contact your system administrator.");
					}
					$database->commitTransaction();	
					$message = "Successfully updated PDA-RAR Code.";	
					redirect_to("index.php?pageid=120&message=$message");	
				}
			}
		}
		catch (Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage();
			redirect_to("index.php?pageid=120&message=$message");
		}
	}
?>