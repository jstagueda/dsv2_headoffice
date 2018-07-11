<?php

	require_once "../initialize.php";	
	global $database;

   	$rs_cboBarangay = $sp->spSelectBarangay($database, -1, $_GET['Param']);
   	$drpBarangay = '<option value=\'0\' >[SELECT HERE]</option>';
    if ($rs_cboBarangay->num_rows)
    {
     while ($row = $rs_cboBarangay->fetch_object())
      {
      		(0== $row->ID) ? $sel = 'selected' : $sel = '';
	        $drpBarangay .= "<option value='$row->ID'' $sel>$row->Name</option>";
      }
       echo $drpBarangay; //Send Data back
       exit; //we're finished so exit..
    }
   	
?>