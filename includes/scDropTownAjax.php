<?php

	require_once "../initialize.php";	
	global $database;

   	$rs_cboTownCityAjax = $sp->spSelectTownCity($database, -1, $_GET['Param']);
   	$drpTownCity = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboTownCityAjax->num_rows)
    {
     while ($row = $rs_cboTownCityAjax->fetch_object())
      {
      		(0== $row->ID) ? $sel = 'selected' : $sel = '';
	        $drpTownCity .= "<option value='$row->ID'' $sel>$row->Name</option>";
      }
       echo $drpTownCity; //Send Data back
     	exit; //we're finished so exit..
    }
   	
?>