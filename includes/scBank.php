<?php 
/* 
 *  Modified by: marygrace cabardo 
 *  10.09.2012
 *  marygrace.cabardo@gmail.com
 */
	global $database;
	$msg = "";
	
	if(isset($_GET['searchedTxt']))
	{
		$dSearchTxt = $_GET['searchedTxt'];		
	}	
	else
	{
		if(isset($_POST['btnSearch']))
		{		
			$dSearchTxt = addslashes($_POST['searchTxtFld']);					
		}	
		else
		{
			$dSearchTxt = "";  
		}
	}
	
	if(isset($_GET['bID']))
	{
		$bID = $_GET['bID'];
		$rs_bank = $sp->spSelectBank($database, 0, $dSearchTxt);
		
		$rs_bankDetails =  $sp->spSelectBank($database, $bID, $dSearchTxt);
		if($rs_bankDetails->num_rows)
		{
			while($row = $rs_bankDetails->fetch_object())
			{
				$code = $row->Code;
				$name = $row->Name;							
				$account = $row->GLAccount;
                                $branch = $row->Branch;

			}
			$rs_bankDetails->close();
		}		
	}
	else
	{
		$_GET['bID'] = 0;
		$bID = 0;
		$code = "";
		$name = "";
		$account = "";
                $branch = "";
		$rs_bank = $sp->spSelectBank($database, $bID, $dSearchTxt);
	}
?>