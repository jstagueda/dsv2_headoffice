<?php
	global $database;
	$ksID = 0;
	$code = "";
	$name = "";
	$desc = "";
	$msg = "";
	$ksSearchTxt = "";	
	
	if(isset($_POST['btnSearch']))
	{
		$ksSearchTxt = addslashes($_POST['txtfldsearch']);	
		$rs_keySpread = $sp->spSelectPageType($database, $ksSearchTxt, $ksID);
	}	
	else
	{
		$rs_keySpread = $sp->spSelectPageType($database, $ksSearchTxt, $ksID);
	}

	if(isset($_GET['ksID']))
	{
		$ksID = $_GET['ksID'];
		$rs_ksDetails =  $sp->spSelectPageType($database, $ksSearchTxt, $ksID);		
		
		if($rs_ksDetails->num_rows)
		{
			while($row = $rs_ksDetails->fetch_object())
			{
				$code = $row->Code;
				$name = $row->Name;						
			}
			$rs_ksDetails->close();
		}
	}
?>