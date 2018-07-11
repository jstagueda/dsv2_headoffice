<?php
	global $database;
	
	if(isset($_GET['svalue']))
	{
		$search =  $_GET['svalue'];	
	}
	else
	{
		 $search = '';
	}
	
 	$code = '';
 	$name = '';
 	$id = 0;
 	$campId = 0;
 	$ccode = '';
 	$cname = '';
 	$cid = 0; 
 	$date = time();
 	$today = date("m/d/Y",$date);
 	//$tmpdate = strtotime(date("Y-m-d", strtotime($today))); 
 	$startDate = $today;
 	$endDate = $today;
 	$advPOStartDate = $today;
 	$advPOEndDate = $today;
 	$statID = 0;
	 
 	if(isset($_POST['btnSearch']))
 	{
 		$search = addslashes($_POST['txtfldsearch']);	
 	}	
	
 	$rs_campaign = $sp->spSelectUpdateCampaignInfo($database,$search);
 	$rs_status = $sp->spSelectStatusListByStatusTypeID($database,3);
	 
 	if (isset($_GET['campId']))
 	{
		$campId = $_GET['campId'];
	 	$rs_campaignByID = $sp->spSelectUpdateCampaignInfoByID($database,$campId);
	 	
	 	if ($rs_campaignByID->num_rows)
	 	{
			while ($row = $rs_campaignByID->fetch_object())
			{
				$cid = $row->ID;
			 	$ccode = $row->Code;
			 	$cname = $row->Name;
			 	$startDate = $row->StartDate;
			 	$endDate = $row->EndDate;
			 	$advPOStartDate = $row->AdvPOStartDate;
			 	$advPOEndDate = $row->AdvPOEndDate;
			 	$statID = $row->StatusID;
			} 
			$rs_campaignByID->close();
 		}
 	}	
?>