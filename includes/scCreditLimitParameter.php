<?php
	global $database;
	$errmsg = "";
	if(isset($_GET['msg']))
	{
		$errmsg = $_GET['msg'];
	}
	
	if(isset($_POST['btnSave']))
	{
		try 
		{	
			$database->beginTransaction();
			$quota = $_POST['txtQuota'];
			$clincrease = $_POST['txtIncrease'];
			$enbaled = $_POST['cboEnable'];
			
			$rsCheckCreditLimitParameters = $sp->spSelectCreditLimitParameters($database);
			if($rsCheckCreditLimitParameters->num_rows)
			{
				$rsUpdateLimitParam = $sp->spUpdateCreditLimitParameters($database,$quota,$clincrease,$enbaled);
				
				
			}
			else
			{
				$rsInsertCreditLimitParam = $sp->spInsertCreditLimitParameters($database,$quota,$clincrease,$enbaled);
			}
			$database->commitTransaction();
			 $msg = "Successfully set Automatic Credit Limit Update Parameters.";
			 redirect_to("index.php?pageid=129&msg=$msg");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
		}
		
	}	
?>