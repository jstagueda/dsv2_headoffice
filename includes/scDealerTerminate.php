<?php
global $database;
 
	$fromibmid = '';
	$toibmid = '';
	$fromnopo='';
	$tonopo='';
	$id = isset($_GET['id']) ? $_GET['id'] :0;
	
	if(isset($_POST['btnGenerateTerminate']))
	{
		$cd_fromibmid = $_POST['txtIBMRangeFrom'];
		$cd_toibmid = $_POST['txtIBMRangeTo'];
		$cd_fromnopo = $_POST['txtDateFrom'];
		$cd_tonopo = $_POST['txtDateTo'];

		if ($cd_fromibmid != '')
		{
			$fromibmid = (int)$cd_fromibmid;			
		}
		if ($cd_toibmid != '')
		{
			$toibmid = (int)$cd_toibmid;			
		}

		if($cd_fromnopo != '')
		{
			$fromnopo = $cd_fromnopo;			
		}
		if($cd_tonopo != '')
		{
			$tonopo = $cd_tonopo;			
		}
		$rs_customerall = $sp->spSelectDealerTerminate($database, $fromibmid, $toibmid, $fromnopo, $tonopo);
	}
	else
	{
		//$rs_customerall = $sp->spSelectDealerTerminate($database,'', '', '', '');
		//$rs_customerterminate = $sp->spSelectDealerTerminateByID($database,$id);
                $rs_customerall = (object) array('num_rows' => 0);
	}
?>