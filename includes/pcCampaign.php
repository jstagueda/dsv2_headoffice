<?php

	require_once("../initialize.php");
	global $database;

 	if(isset($_POST['btnSave']))
  	{
		$icode = htmlentities(addslashes($_POST['txtCode']));
		$iname =htmlentities(addslashes($_POST['txtName']));
		$istartDate = addslashes($_POST['txtStartDate']);
		$iendDate = addslashes($_POST['txtEndDate']);
		$iadvpoStartDate = addslashes($_POST['txtAdvancePOStartDate']);
		$iadvpoEndDate = addslashes($_POST['txtAdvancePOEndDate']);
		$istatus = 7;	
 		
		$rs_spSelectExistingCampaign = $sp->spSelectExistingCampaign($database,$icode);
		if($rs_spSelectExistingCampaign->num_rows)
		{
			$errormessage = "Code already exists.";
			redirect_to("../index.php?pageid=112&errmsg=$errormessage");	
		}	
		else
		{
			$rs_insertcampaign = $sp->spInsertCampaign($database, $icode, $iname, date('Y-m-d',strtotime($istartDate)), date('Y-m-d',strtotime($iendDate)), date('Y-m-d',strtotime($iadvpoStartDate)), date('Y-m-d',strtotime($iadvpoEndDate)), $istatus);
			$message = "Successfully saved record.";
			redirect_to("../index.php?pageid=112&msg=$message");		
		}											
										
	 	$message = "Successfully created record.";
		redirect_to("../index.php?pageid=112&err1msg=$message");	
	}
	elseif (isset($_POST['btnUpdate'])) 
	{
		try
		{
			$database->beginTransaction();
			
			$id = $_POST["txtID"];
			$icode = htmlentities(addslashes($_POST['txtCode']));
		 	$iname =htmlentities(addslashes($_POST['txtName']));
		 	$istartDate = addslashes($_POST['txtStartDate']);
	 		$iendDate = addslashes($_POST['txtEndDate']);
	 		$iadvpoStartDate = addslashes($_POST['txtAdvancePOStartDate']);
	 		$iadvpoEndDate = addslashes($_POST['txtAdvancePOEndDate']);
	 		$istatus = 7;	
			
			$rs_spSelectExistingCampaign2 = $sp->spSelectExistingCampaign2($database,$id,$icode);
			if($rs_spSelectExistingCampaign2->num_rows)
			{
				$database->commitTransaction();
				$errormessage = "Code already exists.";
				redirect_to("../index.php?pageid=112&errmsg=$errormessage");	
			}
			else
			{
				$affected_rows = $sp->spUpdateCampaign($database, $id, $icode, $iname, date('Y-m-d',strtotime($istartDate)), date('Y-m-d',strtotime($iendDate)), date('Y-m-d',strtotime($iadvpoStartDate)), date('Y-m-d',strtotime($iadvpoEndDate)), $istatus);
				if (!$affected_rows)
				{				
					throw new Exception("An error occurred, please contact your system administrator.");
				}
				
				$database->commitTransaction();
				$message = "Successfully updated record.";
				redirect_to("../index.php?pageid=112&msg=$message");
			}
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=112&errmsg=$message");	
		}					   
	}
	elseif (isset($_POST['btnDelete']))
	{
		try
		{
			$database->beginTransaction();
			$id = 0;	
			$id = $_POST["txtID"];
	
		    $affected_rows = $sp->spDeleteCampaign($database, $id);
			if (!$affected_rows)
			{	
				throw new Exception("An error occurred, please contact your system administrator.");
			}
			
			$database->commitTransaction();
			$message = "Successfully deleted record.";
			redirect_to("../index.php?pageid=112&msg=$message");
		}
		catch(Exception $e)
		{
			$database->rollbackTransaction();
			$message = $e->getMessage()."\n";
			redirect_to("../index.php?pageid=112&errmsg=$message");	
		}	
	}	
	
	

?>