<?php

	global $database;
	$loginname = "";
	$userid =  $session->user_id;

	$rs_user = $sp->spSelectUserById($database, $userid);	
	if ($rs_user->num_rows)
	{
		while ($row = $rs_user->fetch_object())
		{
			$loginname = $row->UserName;
		}
	}
	
	if (isset($_GET['pageid']))
	{
		$pageid = $_GET['pageid'];
	 	$rs_navsub = $sp->spSelectSubModule($database, $userid, $pageid);	
		$num = $rs_navsub->num_rows;
		if ($num == 0)
		{
		 	$rs_navsub = $sp->spSelectSubModuleModuleControl($database, $userid, $pageid);
		}
	}
	
	$rs_navmod = $sp->spSelectUserModule($database, $userid);
        
        /*
         * @author: jdymosco
         * @date: April 12, 2013
         */
        $new_rs_navmod = array();
        $groupIDLabels = array();
        //We just created a static value assigning for the main labels of navigations for now.
        $groupIDLabels[1] = 'Transaction Modules';
        $groupIDLabels[2] = 'Report Modules';
        $groupIDLabels[3] = 'Master Data Management Module';
        //Group main navigations...
        if($rs_navmod->num_rows){
            while($new_row = $rs_navmod->fetch_object()):
                $new_rs_navmod[$new_row->GroupID][] = $new_row;
            endwhile;
        }
	
	//check status of inventory
	$rs_freeze = $sp->spCheckInventoryStatus($database);
	if ($rs_freeze->num_rows)
	{
		while ($row = $rs_freeze->fetch_object())
		{
			$statusid_inv = $row->StatusID;			
		}		
	}
	else
	{
		$statusid_inv = 20;
	}
?>