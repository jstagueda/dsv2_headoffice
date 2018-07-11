<?php
   require_once "../initialize.php";
   global $database;
   
   $memoType = $_POST['memoType'];
   $reasonId = $_POST['reasonId'];
   
   if ($memoType == 1) 
   {
      $rs_reason = $sp->spSelectReason($database, 6, '');
   } 
   else if ($memoType == 2)
   {
      $rs_reason = $sp->spSelectReason($database, 7, '');
   }
   else
   {
      $rs_reason = $sp->spSelectReason($database, -1, '');
   }
   
	//retrieve latest bir series
	if($memoType == 1)
	{
		$rs_birseries = $sp->spSelectBIRSeriesByTxnTypeID($database, 5);
	}
	else if ($memoType == 2)
	{
		$rs_birseries = $sp->spSelectBIRSeriesByTxnTypeID($database, 6);
	}
	else
	{
		$rs_birseries = $sp->spSelectBIRSeriesByTxnTypeID($database, 0);
	}
	if ($rs_birseries->num_rows)
	{
		while ($row = $rs_birseries->fetch_object())
		{
			$bir_series	= $row->Series;
			$bir_id = $row->NextID;			
		}
		
		if($memoType == 1)
		{
			$amount_style = "black";			
		}		
		else
		{
			$amount_style = "red";
		}
	}
	else
	{
		$rs_branch = $sp->spSelectBranch($database, -2, "");
		if ($rs_branch->num_rows)
		{
			while ($row = $rs_branch->fetch_object())
			{
				if($memoType == 1)
				{
					$bir_series	= $row->Code."500000001";
					$bir_id = 1;
					$amount_style = "black";						
				}
				else if($memoType == 2)
				{
					$bir_series	= $row->Code."600000001";
					$bir_id = 1;		
					$amount_style = "red";				
				}
				else
				{
					$bir_series	= "";
					$bir_id = 0;
					$amount_style = "";						
				}
			}
		}
	}
   
   	echo '<select name="cboReason" id="cboReason" style="width:160px "  class="txtfield" tabindex="5">';
    echo "<option value=\"\" >[SELECT HERE]</option>";
    if ($rs_reason->num_rows)
    {
        while ($row = $rs_reason->fetch_object())
        {
        	($reasonId == $row->ID) ? $sel = "selected" : $sel = "";
        	echo "<option value='$row->ID' $sel>$row->Name</option>";
        }
    }
   	echo '</select>'.'_'.$bir_series."_".$bir_id."_".$amount_style;   
   	                                                 