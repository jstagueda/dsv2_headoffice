<?php
	global $database;
		
	$rs_cboIBMNetwork = $sp->spSelectCustomerByCustomerTypeID($database);
	$fromibmid = -1;
	$toibmid = -1;
	$ibmname = '';
	
        $_POST['txtToIBM'] = 0;
        $_POST['txtFromIBM'] = 0;
        
	if(isset($_POST['btnGenerateTransfer']))
	{
		$cd_fromibmid = $_POST['txtFromIBM'];
		$cd_toibmid = $_POST['txtToIBM'];
		$cd_ibmname = $_POST['txtIBMName'];

		if ($cd_fromibmid != '')
		{
			$fromibmid = (int)$cd_fromibmid;
		}
		else
		{
			$fromibmid = 0;
		}
		if ($cd_toibmid != '')
		{
			$toibmid = (int)$cd_toibmid;			
		}
		else
		{
			$toibmid = 0;
		}
				
		$rs_customerall = $sp->spSelectDealerTransfer($database, -1, $fromibmid, $toibmid, $cd_ibmname);
	}
?>