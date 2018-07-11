<?php 
	global $database;
	$fromibmid = '';
	$toibmid = '';
	$fromnopo='';
	$tonopo='';
	$id = isset($_GET['id']) ? $_GET['id'] : '';
	if(isset($_POST['btnGenerate']))
	{
		$cd_fromibmid = $_POST['txtIBMRangeFrom'];
		$cd_toibmid = $_POST['txtIBMRangeTo'];
		$cd_fromnopo = $_POST['txtDateFrom'];
		$cd_tonopo = $_POST['txtDateTo'];

		if ($cd_fromibmid != '' && $cd_toibmid != '')
		{
			$fromibmid = $cd_fromibmid;
			$toibmid = $cd_toibmid;
		}

		if($cd_fromnopo != ''  && $cd_tonopo != '')
		{
			$fromnopo=$cd_fromnopo;
			$tonopo=$cd_tonopo;
		} 
		
		$rs_customerall = $sp->spSelectDealerReverseIBMC($database, $fromibmid, $toibmid, $fromnopo, $tonopo);
	}
	else
	{
		$rs_customerall = $sp->spSelectDealerReverseIBMC($database, '', '', '', '');
		$rs_customerterminate = $sp->spSelectDealerReverseIBMCByID($database, $id);
	}
?>