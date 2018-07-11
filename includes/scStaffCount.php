<?php
	global $database;
	$ksID = 0;
	$pmg = "";
	$factor = "";
	$sdate = "";
	$edate = "";
	$msg = "";
	$ksSearchTxt = "";
	$campaignid = 0;
	$scount = "";
	$acount = "";
	
	if(isset($_GET['txnID']) && $_GET['txnID'] != 0)
	{
		$rs_campaign = $sp->spSelectCampaignStaffCount($database, $_GET['txnID']);		
	}
	else
	{
		$rs_campaign = $sp->spSelectCampaignStaffCount($database, 0);
		$_GET['txnID'] = 0;
	}
 	
	if(isset($_POST['btnSearch']))
	{
		$ksSearchTxt = addslashes($_POST['txtfldsearch']);	
		$rs_staffcount = $sp->spSelectStaffCount($database, $ksSearchTxt, 0);
	}	
	else
	{
		$rs_staffcount = $sp->spSelectStaffCount($database, $ksSearchTxt, 0);
	}
	
	if(isset($_GET['txnID']) && $_GET['txnID'] != 0)
	{
		$rs_staffcount_details = $sp->spSelectStaffCount($database, $ksSearchTxt, $_GET['txnID']);
		if($rs_staffcount_details->num_rows)
		{
			while($row = $rs_staffcount_details->fetch_object())
			{
				$campaignid = $row->CampaignID;
				$scount = number_format($row->StaffCount, 2);
				$acount = number_format($row->ActiveCount, 2);										
			}
			$rs_staffcount_details->close();
		}		
	}
?>