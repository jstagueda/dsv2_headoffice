<?php
	global $database;
	$errmsg = "";

	if(isset($_POST['btnPromote']))
	{
		try
		{
			$database->beginTransaction();
			$chkSelect = $_POST["chkSelect"];
			$customertypeid = $_POST['cboDealerType'];
			$fromcustomertypeid = $_POST['cboCustomerType'];
			
			foreach ($chkSelect as $key=>$ID) 
			{
				$insertDealerPromotion = $sp->spInsertDealerPromotion($database,$ID,$fromcustomertypeid, $customertypeid);
				$update = $sp->spPromoteDealer($database, $ID, $customertypeid);
				if (!$update)
				{
					throw new Exception("An error occurred, please contact your system administrator.");
				}
			}
	
			$database->commitTransaction();
			$msg = "Successfully updated record";
			redirect_to("index.php?pageid=73&msg=$msg");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$errmsg = $e->getMessage()."\n";
			redirect_to("index.php?pageid=73&errmsg=$errmsg");
		}
	}
?>