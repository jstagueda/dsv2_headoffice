<?php

global $database;
$errmsg="";

	if(isset($_POST['btnUpdate']))
	{
		try
		{
			$database->beginTransaction();
			$ibmid = $_POST["cboIBMNetwork"];
			
	        foreach ($_POST['chkSelect'] as $key=>$value) 
	 		{
				$update = $sp->spTransferDealer($database, $value, $ibmid, $session->emp_id);
		 		if (!$update)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
			}
	
			$database->commitTransaction();
			$msg = "Successfully updated record.";
			redirect_to("index.php?pageid=72&msg=$msg");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";	
		}
	}
?>