<?php
global $database;

//$code = $_GET['search'];
$bcode="";
$bname="";
$rname="";
$rid = $_GET['rid'];
$frmdate = $_GET['frmdte'];
$frmdate2 = date("Y-m-d", strtotime( $_GET['frmdte']));
$todate = $_GET['todte'];
$todate2 = date("Y-m-d", strtotime( $_GET['todte']));

if($rid != 0)
{
$reason = $sp->spSelectReasonForDMCM($database, $rid);

while($rowreason = $reason->fetch_object()) 
		{
			$rname = $rowreason->Name;
		}
}else
{
	$rname = 'All';
}		
?>