<?php
	//unlock transaction
	try
	{
		
			require_once "../initialize.php";
			global $database;
			
			$txnId = $_POST['txnId'];
		   	$table = $_POST['table'];
		 
			$database->beginTransaction();
			if ($table == 1)
			{
				$updatestatus = $sp->spUpdateLockStatus($database, 'salesorder', 0, 0, $txnId);			
			}
			else if ($table == 2)
			{
				$updatestatus = $sp->spUpdateLockStatus($database, 'salesinvoice', 0, 0, $txnId);			
			}
			else if ($table == 3)
			{
				$updatestatus = $sp->spUpdateLockStatus($database, 'provisionalreceipt', 0, 0,$txnId);			
			}
			else if ($table == 4)
			{
				$updatestatus = $sp->spUpdateLockStatus($database, 'officialreceipt', 0, 0, $txnId);			
			}
			else if ($table == 5)
			{
				$updatestatus = $sp->spUpdateLockStatus($database, 'dmcm', 0, 0, $txnId);			
			}
			
			if (!$updatestatus)
			{
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			$database->commitTransaction();					
		
	}
	catch (Exception $e)
	{
		$database->rollbackTransaction();	
		$errmsg = $e->getMessage()."\n";
		throw new Exception($errmsg);	
	}
?>