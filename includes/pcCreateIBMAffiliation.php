<?php 
		
	global $database;
	$errmsg="";
	
	if(isset($_POST['btnSubmit']))
	{
		try
		{
			if(isset($_GET['custid']))
			{
				$database->beginTransaction();
				$custid = $_GET['custid'];
				$branchid = $_POST['cboBranchName'];
				$remarks = $_POST['txtRemarks'];
					
				$update = $sp->spInsertIBMAffiliation($database, $custid, $branchid, $_SESSION['emp_id']);
				if (!$update)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				$database->commitTransaction();
				$msg = "Successfully saved record.";
				redirect_to("index.php?pageid=79&msg=$msg");
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
		}
	}
?>